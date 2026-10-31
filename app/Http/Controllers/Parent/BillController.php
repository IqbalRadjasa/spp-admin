<?php

namespace App\Http\Controllers\Parent;

use App\Models\Bill;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BillController extends ParentController
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $children = $user->children;

        if ($children->isEmpty()) {
            return view('parent.dashboard-empty', ['message' => 'Belum ada data siswa yang dihubungkan dengan akun Anda.']);
        }

        $activeChild = $this->getActiveChild();

        $months = get_months();
        $availableYears = $activeChild->bills()
            ->pluck('billing_period')
            ->map(function ($period) {
                return trim(substr($period, 0, 4));
            })
            ->filter(function ($year) {
                return is_numeric($year) && strlen($year) === 4;
            })
            ->unique()
            ->sortDesc()
            ->values();

        $query = $activeChild->bills()
            ->when($request->filled('year'), function ($query) use ($request) {
                $selectedYear = $request->input('year');
                $query->where('billing_period', 'like', "%{$selectedYear}%");
            })
            ->when($request->filled('month'), function ($query) use ($request) {
                $month = $request->input('month');
                $query->where('billing_period', 'like', "%-{$month}");
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            });

        $bills = $query->latest()->paginate(5)->withQueryString();

        return view('parent.bills.index', compact('children', 'activeChild', 'bills', 'availableYears', 'months'));
    }

    public function show(Bill $bill)
    {
        $user = auth()->user();
        $ownsBill = $user->children()->where('students.id', $bill->student_id)->exists();

        if (!$ownsBill) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat tagihan ini.');
        }

        session(['active_child_id' => $bill->student_id]);

        $bill->load([
            'student' => function ($q) {
                $q->select(['id', 'fullname', 'nis', 'enrollment_year', 'status']);
            }
        ]);

        return view('parent.bills.show', compact('bill'));
    }
}
