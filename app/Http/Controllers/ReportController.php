<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

use App\Exports\PaymentsExport;

use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function overdueReport(Request $request)
    {
        $query = Bill::with('student')
            ->where('status', 'unpaid');

        if ($request->billing_period) {

            $query->where(
                'billing_period',
                $request->billing_period
            );
        }

        if ($request->search) {

            $query->whereHas('student', function ($q) use ($request) {

                $q->where(
                    'name',
                    'like',
                    '%' . $request->search . '%'
                );
            });
        }

        $bills = $query
            ->latest()
            ->paginate(5)
            ->withQueryString();

        $totalOutstanding = $query->sum('amount');

        return view(
            'reports.overdue-report',
            compact('bills', 'totalOutstanding')
        );
    }

    public function paymentReport(Request $request)
    {
        $query = Payment::with([
            'bill.student',
            'paymentMethod'
        ]);

        if ($request->month) {

            $date = explode('-', $request->month);

            $year = $date[0];
            $month = $date[1];

            $query->whereYear('paid_at', $year)
                ->whereMonth('paid_at', $month);
        }

        if ($request->payment_method) {

            $query->where(
                'payment_method_id',
                $request->payment_method
            );
        }

        if ($request->search) {

            $query->whereHas('bill.student', function ($q) use ($request) {

                $q->where(
                    'name',
                    'like',
                    '%' . $request->search . '%'
                );
            });
        }

        $paymentMethods = PaymentMethod::all();
        $totalIncome = $query->sum('amount_paid');
        $payments = $query
            ->latest('paid_at')
            ->paginate(5)
            ->withQueryString();


        return view(
            'reports.payment-report',
            compact('payments', 'totalIncome', 'paymentMethods')
        );
    }

    public function exportPayments(Request $request)
    {
        $fileName = 'payments-report';

        if ($request->month) {

            $fileName .= '-' . $request->month;
        }

        if ($request->payment_method) {

            $paymentMethod = PaymentMethod::find(
                $request->payment_method
            );

            $fileName .= '-' . Str::slug(
                $paymentMethod->name
            );
        }

        $fileName .= '.xlsx';

        return Excel::download(
            new PaymentsExport($request),
            $fileName
        );
    }
}
