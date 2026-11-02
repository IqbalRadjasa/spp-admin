<?php

namespace App\Http\Controllers\Parent;

use Carbon\Carbon;

use App\Models\Bill;
use App\Models\Payment;
use App\Models\AcademicYear;
use App\Models\PaymentMethod;

use App\Exports\OverdueExport;
use App\Exports\PaymentsExport;
use App\Http\Controllers\Parent\ParentController;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PaymentHistoryController extends ParentController
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $children = $user->children;

        // 1. Ensure the parent has children linked to their account
        if ($children->isEmpty()) {
            return view('parent.dashboard-empty', [
                'message' => 'Belum ada data siswa yang dihubungkan dengan akun Anda.'
            ]);
        }

        // 2. Get the currently selected active child
        $activeChild = $this->getActiveChild();

        $months = get_months();
        $paymentMethods = PaymentMethod::all();

        // 3. Get distinct years specifically for the active child's payments
        $years = Payment::whereHas('bill', function ($q) use ($activeChild) {
            $q->where('student_id', $activeChild->id);
        })
            ->select(DB::raw('YEAR(paid_at) as year'))
            ->whereNotNull('paid_at')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // 4. Base query restricted to the active child's bills & successful payments
        $query = Payment::with([
            'bill' => function ($q) {
                $q->select(['id', 'billing_period', 'status']);
            },
            'bill.student' => function ($q) {
                $q->select(['id', 'fullname', 'nis', 'avatar']);
            },
            'paymentMethod'
        ])
            ->whereHas('bill', function ($q) use ($activeChild) {
                $q->where('student_id', $activeChild->id);
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
            ->where('status', 'paid');

        $totalIncome = (clone $query)->sum('amount_paid');

        $payments = $query
            ->latest('paid_at')
            ->paginate(5)
            ->withQueryString();

        return view(
            'parent.payment-history.index',
            compact(
                'children',
                'activeChild',
                'payments',
                'totalIncome',
                'paymentMethods',
                'months',
                'years'
            )
        );
    }
}
