<?php

namespace App\Http\Controllers;

use App\Exports\OverdueExport;
use App\Models\Bill;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

use App\Exports\PaymentsExport;
use App\Models\AcademicYear;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function overdueReport(Request $request)
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
            });

        $totalOutstanding = (clone $query)->sum('amount');

        $bills = $query
            ->latest()
            ->paginate(5)
            ->withQueryString();


        return view(
            'reports.overdue-report',
            compact(
                'bills',
                'totalOutstanding',
                'months',
                'academicYears'
            )
        );
    }

    public function paymentReport(Request $request)
    {
        $query = Payment::with([
            'bill.student',
            'paymentMethod'
        ]);

        if ($request->month) {

            $query->whereMonth(
                'paid_at',
                Carbon::parse(
                    $request->month
                )->month
            );
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

    public function exportOverdue(Request $request)
    {
        $nameSegments = ['laporan-tunggakan'];

        if ($request->filled('academic_year_id')) {
            $academicYear = AcademicYear::find($request->academic_year_id);
            if ($academicYear) {
                $nameSegments[] = str_replace('/', '-', $academicYear->name);
            }
        }

        if ($request->filled('month')) {
            $months = get_months();
            $monthName = $months[$request->month] ?? $request->month;
            $nameSegments[] = strtolower($monthName);
        } else {
            $nameSegments[] = 'semua-periode';
        }

        $fileName = implode('-', $nameSegments) . '-' . now()->format('YmdHis') . '.xlsx';

        return Excel::download(
            new OverdueExport($request),
            $fileName
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
