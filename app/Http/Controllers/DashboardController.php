<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Student;
use App\Models\Payment;

use Illuminate\Http\Request;


class DashboardController extends Controller
{
    public function index()
    {
        $total_students = Student::count();
        $unpaid_bills = Bill::where('status', 'unpaid')->count();
        $payments_this_month = Payment::whereMonth('paid_at', now()->month)->count();
        $income_this_month = Payment::whereMonth('paid_at', now()->month)->sum('amount_paid');

        return view('dashboard', [
            'total_students' => $total_students,
            'unpaid_bills' => $unpaid_bills,
            'payments_this_month' => $payments_this_month,
            'income_this_month' => $income_this_month,
        ]);
    }
}
