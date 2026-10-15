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
        $query = Bill::with('student');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->billing_period) {
            $query->where(
                'billing_period',
                $request->billing_period
            );
        }

        if ($request->search) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $bills = $query
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('bills.index', compact('bills'));
    }

    public function detail(Bill $bill)
    {
        return view('bills.detail', compact('bill'));
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
        ));
    }

    public function generateBills(Request $request, ActivityLogService $activityLog)
    {
        DB::enableQueryLog();

        $validated = $request->validate([
            'billing_period' => 'required',
            'amount' => 'required|integer|min:1'
        ]);

        $exists = Bill::where('billing_period', $validated['billing_period'])->exists();

        if ($exists) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Billing period has been registered.');
        }

        $students = Student::all();

        try {
            DB::transaction(
                function () use ($validated, $students, $activityLog) {
                    foreach ($students as $student) {
                        Bill::firstOrCreate(
                            [
                                'student_id' => $student->id,
                                'academic_year_id' => activeAcademicYear()->id,
                                'billing_period' => $validated['billing_period']
                            ],
                            [
                                'amount' => $validated['amount'],
                                'status' => 'unpaid'
                            ]
                        );
                    }

                    $activityLog->log(
                        'generate bills',
                        'bill',
                        null,
                        'Bills Generated ' .
                            $validated['billing_period']
                    );
                }
            );

            return redirect()
                ->back()
                ->with('success', 'Bill successfully generated!');
        } catch (\Exception $th) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Something went wrong, please try again later.');
        }
    }
}
