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
        $totalStudents = Student::count();
        $unpaidBills = Bill::where('status', 'unpaid')->count();
        $paymentThisMonth = Payment::whereMonth('paid_at', now()->month)->count();
        $incomeThisMonth = Payment::whereMonth('paid_at', now()->month)->sum('amount_paid');

        return view('dashboard', [
            'totalStudents' => $totalStudents,
            'unpaidBills' => $unpaidBills,
            'paymentThisMonth' => $paymentThisMonth,
            'incomeThisMonth' => $incomeThisMonth,
        ]);
    }
}
