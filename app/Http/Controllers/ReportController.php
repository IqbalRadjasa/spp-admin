<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Models\Bill;
use App\Models\Payment;
use App\Models\AcademicYear;
use App\Models\PaymentMethod;

use App\Exports\OverdueExport;
use App\Exports\PaymentsExport;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            })
            ->where(function ($q) {
                $q->where('status', 'unpaid');
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
        $months = get_months();
        $paymentMethods = PaymentMethod::all();
        $years = Payment::select(DB::raw('YEAR(paid_at) as year'))
            ->whereNotNull('paid_at')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $query = Payment::with([
            'bill.student' => function ($q) {
                $q->select(['id', 'fullname', 'nis', 'avatar']);
            },
            'paymentMethod'
        ])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->whereHas('bill.student', function ($q) use ($search) {
                    $q->where('fullname', 'like', '%' . $search . '%')
                        ->orWhere('nis', 'like', '%' . $search . '%');
                });
            })
            ->when($request->filled('payment_method_id'), function ($query) use ($request) {
                $query->where('payment_method_id', $request->input('payment_method_id'));
            })
            ->when($request->filled('date'), function ($query) use ($request) {
                $query->whereDate('paid_at', $request->input('date'));
            })
            ->when($request->filled('year'), function ($query) use ($request) {
                $query->whereYear('paid_at', $request->input('year'));
            })
            ->when($request->filled('month'), function ($query) use ($request) {
                $query->whereMonth('paid_at', $request->input('month'));
            })
            ->where(function ($q) {
                $q->where('status', 'paid');
            });

        $totalIncome = (clone $query)->sum('amount_paid');

        $payments = $query
            ->latest('paid_at')
            ->paginate(5)
            ->withQueryString();

        return view(
            'reports.payment-report',
            compact(
                'payments',
                'totalIncome',
                'paymentMethods',
                'months',
                'years'
            )
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
        $fileName = 'laporan-pembayaran';

        if ($request->year) {

            $fileName .= '-' . $request->year;
        }

        if ($request->month) {

            $fileName .= '-' . $request->month;
        }

        if ($request->payment_method_id) {

            $paymentMethod = PaymentMethod::find(
                $request->payment_method_id
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
