<?php

namespace App\Http\Controllers;

use App\Jobs\SendUpdateBillNotificationJob;
use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\Bill;
use App\Models\Payment;
use App\Services\ActivityLogService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
    public function index(Request $request)
    {
        $months = get_months();
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $query = Bill::with([
            'student' => function ($q) {
                $q->select(['id', 'fullname', 'nis', 'avatar']);
            }
        ])
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->whereHas('student', function ($q) use ($request) {
                    $q->where('fullname', 'like', '%' . $request->search . '%')
                        ->orWhere('nis', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->filled('academic_year_id'), function ($query) use ($request) {
                $query->where('academic_year_id', $request->input('academic_year_id'));
            })
            ->when($request->filled('month'), function ($query) use ($request) {
                $month = $request->input('month');
                $query->where('billing_period', 'like', "%-{$month}");
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('escalation_status'), function ($query) use ($request) {
                $query->where('escalation_status', $request->escalation_status);
            });

        $bills = $query
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('bills.index', compact('bills', 'academicYears', 'months'));
    }

    public function show(Bill $bill)
    {
        $bill->load([
            'student' => function ($q) {
                $q->select(['id', 'fullname', 'nis', 'enrollment_year', 'status']);
            }
        ]);

        return view('bills.show', compact('bill'));
    }

    public function storeEscalationNote(Request $request, Bill $bill)
    {
        $validated =
            $request->validate([
                'note' => 'required|string'
            ]);

        $bill
            ->escalationNotes()
            ->create([
                'user_id' => auth()->id(),
                'note' => $validated['note']
            ]);

        return back()->with(
            'success',
            'Escalation note added.'
        );
    }

    public function generateForm()
    {
        $months = get_months();
        $activeAcademicYear = activeAcademicYear();

        $startYear = $activeAcademicYear
            ? substr(trim($activeAcademicYear->name), 0, 4)
            : now()->format('Y');

        [$year1, $year2] = explode('/', str_replace(' ', '', $activeAcademicYear->name));

        return view('bills.generate', compact(
            'activeAcademicYear',
            'startYear',
            'year1',
            'year2',
            'months'
        ));
    }

    public function generateBills(Request $request, ActivityLogService $activityLog)
    {
        DB::enableQueryLog();

        $validated = $request->validate([
            'month' => 'required|digits:2|between:01,12',
            'year'  => 'required|digits:4|integer|between:2000,2100',
            'amount' => 'required|integer|min:1'
        ]);

        $billingPeriod = $validated['year'] . '-' . $validated['month'];

        $exists = Bill::where('billing_period', $billingPeriod)->exists();

        if ($exists) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Periode tagihan telah terdaftar');
        }

        $students = Student::all();

        try {
            DB::transaction(
                function () use ($billingPeriod, $validated, $students, $activityLog) {
                    foreach ($students as $student) {
                        Bill::firstOrCreate(
                            [
                                'student_id' => $student->id,
                                'academic_year_id' => activeAcademicYear()->id,
                                'billing_period' => $billingPeriod
                            ],
                            [
                                'amount' => $validated['amount'],
                                'status' => 'unpaid'
                            ]
                        );
                    }

                    $activityLog->log(
                        'buat tagihan',
                        'tagihan',
                        null,
                        'Tagihan telah dibuat ' . $billingPeriod
                    );
                }
            );

            return redirect()
                ->back()
                ->with('success', 'Tagihan berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal membuat tagihan: ' . $e->getMessage());
        }
    }

    public function listBill(Request $request)
    {
        $months = get_months();
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $activeAcademicYear = activeAcademicYear();
        [$year1, $year2] = explode('/', str_replace(' ', '', $activeAcademicYear->name));

        $query = Bill::select(
            'academic_year_id',
            'billing_period',
            DB::raw('COUNT(*) as total_students'),
            DB::raw('MIN(amount) as amount'), // Optional: Show total batch revenue instead of a single amount
            DB::raw('MIN(created_at) as created_at'),
        )
            ->with([
                'academicYear' => function ($q) {
                    $q->select(['id', 'name']);
                }
            ])
            ->when($request->filled('academic_year_id'), function ($query) use ($request) {
                $query->where('academic_year_id', $request->input('academic_year_id'));
            })
            // Group ONLY by academic year and billing period
            ->groupBy('academic_year_id', 'billing_period');

        $bills = $query
            ->orderBy('billing_period', 'desc')
            ->paginate(5)
            ->withQueryString();

        $bills->getCollection()->transform(function ($bill) {
            $date = Carbon::parse($bill->billing_period);

            $bill->month_name = $date->translatedFormat('F');
            $bill->month_number = $date->format('m');
            $bill->year = $date->format('Y');

            return $bill;
        });

        return view('bills.list-bill', compact(
            'bills',
            'academicYears',
            'months',
            'activeAcademicYear',
            'year1',
            'year2',
        ));
    }

    public function updateBatch(Request $request, ActivityLogService $activityLog)
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'billing_period'   => 'required|string',
            'amount'           => 'required|integer|min:1'
        ]);

        $academicYearId = $validated['academic_year_id'];
        $billingPeriod  = $validated['billing_period'];
        $newAmount      = $validated['amount'];

        $billsToUpdate = Bill::where('academic_year_id', $academicYearId)
            ->where('billing_period', $billingPeriod)
            ->with(['payment', 'student'])
            ->get();

        if ($billsToUpdate->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada tagihan yang ditemukan untuk periode ini.');
        }

        DB::transaction(function () use ($billsToUpdate, $newAmount) {
            foreach ($billsToUpdate as $bill) {
                $oldAmount = $bill->amount;

                $bill->update(['amount' => $newAmount]);

                if ($newAmount < $oldAmount) {
                    $payment = $bill->payment;

                    if ($payment && $payment->status === 'paid') {
                        $parent = $payment->student_parent;

                        $payment->update([
                            'refund_amount' => $oldAmount - $newAmount,
                            'refund_status' => 'pending_parent_response',
                        ]);

                        if ($parent && $parent->phone) {
                            SendUpdateBillNotificationJob::dispatch($parent, $bill, $oldAmount, $newAmount);
                        }
                    }
                }
            }
        });

        $activityLog->log(
            'update batch tagihan',
            'bills',
            null,
            "Memperbarui batch tagihan periode {$billingPeriod} dengan nominal baru " . rupiah($newAmount)
        );

        return redirect()->route('bills.index')->with('success', 'Batch tagihan berhasil diperbarui.');
    }
}
