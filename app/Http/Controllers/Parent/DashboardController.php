<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // 1. Get student parent profile (for names, phone, etc. if needed in UI)
        $studentParent = $user->studentParent;

        if (!$studentParent) {
            return view('parent.dashboard-empty', ['message' => 'Profil orang tua belum ditemukan.']);
        }

        // 2. Get all students linked to this user via pivot table
        $children = $user->children;

        if ($children->isEmpty()) {
            return view('parent.dashboard-empty', ['message' => 'Belum ada data siswa yang dihubungkan dengan akun Anda.']);
        }

        // 3. Determine active selected child (fallback to the first child)
        $selectedChildId = $request->input('child_id', $children->first()->id);
        $activeChild = $children->where('id', $selectedChildId)->first() ?? $children->first();

        // 4. Child-specific metrics
        $unpaidBillsCount = $activeChild->bills()->where('status', 'unpaid')->count();
        $paidBillsCount = $activeChild->bills()->where('status', 'paid')->count();
        $totalDebt = $activeChild->bills()->where('status', 'unpaid')->sum('amount');

        // Recent payments made for this active child
        $recentPayments = \App\Models\Payment::with(['bill.student', 'paymentMethod'])
            ->whereHas('bill', function ($q) use ($activeChild) {
                $q->where('student_id', $activeChild->id);
            })
            ->latest('paid_at')
            ->take(5)
            ->get();

        return view('parent.dashboard', compact(
            'studentParent',
            'children',
            'activeChild',
            'unpaidBillsCount',
            'paidBillsCount',
            'totalDebt',
            'recentPayments'
        ));
    }
}
