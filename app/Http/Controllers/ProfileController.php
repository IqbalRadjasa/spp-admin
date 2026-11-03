<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;

use App\Models\Occupation;
use App\Models\StudentParent;

use App\Enums\UserRole;

use App\Services\ActivityLogService;

use Illuminate\Support\Facades\DB;

use Illuminate\Validation\Rule;

use Illuminate\View\View;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(): View
    {
        $user = auth()->user()->load(['studentParent']);
        $occupations = Occupation::select('id', 'name')->where('is_active', true)->orderBy('code', 'asc')->get();

        // dd($user->studentParent->phone);

        return view('profile.edit', compact('user', 'occupations'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request, ActivityLogService $activityLog): RedirectResponse
    {
        // dd($request);
        $user = auth()->user();
        $studentParent = $user->studentParent; // Get the related student_parent record

        // Handle case where parent record might not exist yet
        if (!$studentParent) {
            return redirect()->back()->with('error', 'Data profil orang tua tidak ditemukan.');
        }

        if ($request->filled('phone')) {
            $request->merge([
                'phone' => normalizePhone($request->phone),
            ]);
        }

        $validated = $request->validate([
            'fullname'          => ['required', 'string', 'max:255'],
            'nickname'          => ['nullable', 'string', 'max:100'],
            'phone'             => ['required', 'string', 'max:20', Rule::unique('student_parents', 'phone')->ignore($studentParent->id)->whereNull('deleted_at')],
            'email'             => ['required', 'string', 'email', Rule::unique('users', 'email')->ignore($user->id)->whereNull('deleted_at')],
            'address'           => ['required', 'string'], // Removed redundant 'nullable' since 'required' is there
            'relationship'      => ['nullable', 'string'],
            'occupation_id'     => ['nullable', 'exists:occupations,id'],
            'occupation_custom' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            DB::transaction(function () use ($validated, $user, $studentParent, $request) {
                // 1. Update User Account
                $user->update([
                    'name'    => $validated['fullname'],
                    'email'   => $validated['email'],
                    'phone'   => normalizePhone($validated['phone']),
                    'address' => $validated['address'],
                ]);

                // 2. Update StudentParent Profile
                $studentParent->update([
                    'fullname'          => $validated['fullname'],
                    'nickname'          => $validated['nickname'] ?? null,
                    'phone'             => normalizePhone($validated['phone']),
                    'relationship'      => $validated['relationship'] ?? null,
                    'occupation_id'     => $validated['occupation_id'] ?? null,
                    'occupation_custom' => $validated['occupation_custom'] ?? null,
                    'address'           => $validated['address'],
                ]);

                if ($request->filled('relationship')) {
                    foreach ($user->children as $student) {
                        $user->children()->updateExistingPivot($student->id, [
                            'relationship' => $validated['relationship'],
                        ]);
                    }
                }
            });

            $activityLog->log(
                'update profil',
                'user & student_parent',
                $user->id,
                'Profil diperbarui: ' . $validated['fullname']
            );

            return redirect()
                ->route('profile.edit') // Adjusted to redirect back to parent profile or appropriate route
                ->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
