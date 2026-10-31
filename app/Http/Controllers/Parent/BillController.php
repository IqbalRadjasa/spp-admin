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

        $bills = $activeChild->bills()->latest()->paginate(5);

        return view('parent.bills.index', compact('children', 'activeChild', 'bills'));
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
