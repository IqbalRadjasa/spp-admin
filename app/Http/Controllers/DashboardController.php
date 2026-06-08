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
        $paidBills = Bill::where('status', 'paid')->count();
        $unpaidBills = Bill::where('status', 'unpaid')->count();
        $paymentThisMonth = Payment::whereMonth('paid_at', now()->month)->count();
        $incomeThisMonth = Payment::whereMonth('paid_at', now()->month)->sum('amount_paid');
        $monthlyIncome = Payment::selectRaw('MONTH(paid_at) as month, SUM(amount_paid) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        $paymentMethodDistribution = Payment::selectRaw('
        payment_methods.name as method,
        COUNT(payments.id) as total')
            ->join(
                'payment_methods',
                'payments.payment_method_id',
                '=',
                'payment_methods.id'
            )
            ->groupBy('payment_methods.name')
            ->get();
        $paymentMethodLabels = $paymentMethodDistribution->pluck('method');
        $paymentMethodTotals = $paymentMethodDistribution->pluck('total');
        $recentPayments = Payment::with([
            'bill.student',
            'paymentMethod'
        ])
            ->latest('paid_at')
            ->take(5)
            ->get();

        return view('dashboard', [
            'paidBills' => $paidBills,
            'unpaidBills' => $unpaidBills,
            'totalStudents' => $totalStudents,
            'monthlyIncome' => $monthlyIncome,
            'recentPayments' => $recentPayments,
            'incomeThisMonth' => $incomeThisMonth,
            'paymentThisMonth' => $paymentThisMonth,
            'paymentMethodLabels' => $paymentMethodLabels,
            'paymentMethodTotals' => $paymentMethodTotals,
        ]);
    }
}
