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
        $role = $user->role->value;
        $occupations = Occupation::select('id', 'name')->where('is_active', true)->orderBy('code', 'asc')->get();

        return view('profile.edit', compact('user', 'occupations', 'role'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request, ActivityLogService $activityLog): RedirectResponse
    {
        $user = auth()->user();
        $role = $user->role->value;
        $studentParent = $user->studentParent;

        if (!$studentParent && $role === 'parent') {
            return redirect()->back()->with('error', 'Data profil orang tua tidak ditemukan.');
        }

        if ($request->filled('phone')) {
            $request->merge([
                'phone' => normalizePhone($request->phone),
            ]);
        }

        $rules = [
            'fullname' => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', Rule::unique('users', 'email')->ignore($user->id)->whereNull('deleted_at')],
            'address'  => ['required', 'string'],
        ];

        if ($role === 'parent') {
            $rules['nickname']          = ['nullable', 'string', 'max:100'];
            $rules['phone']             = ['required', 'string', 'max:20', Rule::unique('student_parents', 'phone')->ignore($studentParent?->id)->whereNull('deleted_at')];
            $rules['relationship']      = ['nullable', 'string'];
            $rules['occupation_id']     = ['nullable', 'exists:occupations,id'];
            $rules['occupation_custom'] = ['nullable', 'string', 'max:255'];
        } else {
            $rules['phone'] = ['nullable', 'string', 'max:20'];
            $rules['address'] = ['nullable', 'string'];
        }

        $validated = $request->validate($rules);

        try {
            DB::transaction(function () use ($validated, $user, $studentParent, $request, $role) {
                // 1. Update User Account
                $user->update([
                    'name'    => $validated['fullname'],
                    'email'   => $validated['email'],
                    'phone'   => isset($validated['phone']) ? normalizePhone($validated['phone']) : $user->phone,
                    'address' => $validated['address'],
                ]);

                if ($role === 'parent') {
                    $studentParent->update([
                        'fullname'          => $validated['fullname'],
                        'nickname'          => $validated['nickname'] ?? null,
                        'phone'             => normalizePhone($validated['phone']),
                        'relationship'      => $validated['relationship'] ?? null,
                        'occupation_id'     => $validated['occupation_id'] ?? null,
                        'occupation_custom' => $validated['occupation_custom'] ?? null,
                        'address'           => $validated['address'],
                    ]);
                }

                if ($request->filled('relationship')) {
                    foreach ($user->children as $student) {
                        $user->children()->updateExistingPivot($student->id, [
                            'relationship' => $validated['relationship'],
                        ]);
                    }
                }
            });

            $entity = $role === 'parent' ? 'user & student_parent' : 'user';
            $activityLog->log(
                'update profil',
                $entity,
                $user->id,
                'Profil diperbarui: ' . $validated['fullname']
            );

            return redirect()
                ->route('profile.edit')
                ->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
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
