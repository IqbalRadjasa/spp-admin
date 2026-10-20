<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\Bill;

use App\Services\ActivityLogService;

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
        $bil = $bill::with([
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
}
