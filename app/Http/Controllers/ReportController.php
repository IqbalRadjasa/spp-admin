<?php

namespace App\Http\Controllers;

use App\Models\Bill;

use Illuminate\Http\Request;

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
}
