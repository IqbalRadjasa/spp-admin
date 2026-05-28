<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Bill;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
    public function index()
    {
        $bills = Bill::with('student')
            ->latest()
            ->get();

        return view('bills.index', compact('bills'));
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
