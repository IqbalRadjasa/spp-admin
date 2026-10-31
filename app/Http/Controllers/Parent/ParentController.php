<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    /**
     * Global helper to get the active child from session with fallback.
     */
    protected function getActiveChild()
    {
        $user = auth()->user();
        $children = $user->children;

        if ($children->isEmpty()) {
            return null;
        }

        $activeId = session('active_child_id');

        // Find child in user's children collection, or fallback to the first one
        $activeChild = $children->where('id', $activeId)->first() ?? $children->first();

        // Ensure session is synchronized
        session(['active_child_id' => $activeChild->id]);

        return $activeChild;
    }

    /**
     * Global handler for switching active child from the header dropdown.
     */
    public function switchChild(Request $request)
    {
        $request->validate([
            'child_id' => 'required|exists:students,id'
        ]);

        $user = auth()->user();

        // Security check: Verify this parent actually owns this child
        $ownsChild = $user->children()->where('students.id', $request->child_id)->exists();

        if ($ownsChild) {
            session(['active_child_id' => $request->child_id]);
        }

        return redirect()->route('parent.bills.index');
    }
}
