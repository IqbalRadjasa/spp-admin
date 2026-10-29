<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BillController extends ParentController
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $children = $user->children;

        if ($children->isEmpty()) {
            return view('parent.dashboard-empty', ['message' => 'Belum ada data siswa yang dihubungkan dengan akun Anda.']);
        }

        // Active selected child
        $activeChild = $this->getActiveChild();

        // Fetch bills for this active child
        $bills = $activeChild->bills()->latest()->paginate(5);

        return view('parent.bills.index', compact('children', 'activeChild', 'bills'));
    }
}
