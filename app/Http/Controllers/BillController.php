<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Bill;

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

        $bills = $query->latest()->paginate(5);

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
        return view('bills.generate');
    }

    public function generateBills(Request $request)
    {
        DB::enableQueryLog();

        $validated = $request->validate([
            'billing_period' => 'required',
            'amount' => 'required|integer|min:1'
        ]);


        $students = Student::all();

        foreach ($students as $student) {
            Bill::firstOrCreate(
                [
                    'student_id' => $student->id,
                    'billing_period' => $validated['billing_period']
                ],
                [
                    'amount' => $validated['amount'],
                    'status' => 'unpaid'
                ]
            );
        }
        // dd(DB::getQueryLog());

        try {
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
