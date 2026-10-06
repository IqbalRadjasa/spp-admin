<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Enums\UserRole;
use App\Models\Occupation;
use App\Models\StudentParent;

use App\Services\ActivityLogService;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Http\RedirectResponse;
use App\Notifications\WelcomeUserNotification;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::query()
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('user-management.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $occupations = Occupation::select('id', 'name')->where('is_active', true)->orderBy('code', 'asc')->get();

        return view('user-management.create', [
            'occupations' => $occupations,
            'roles' => UserRole::cases()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ActivityLogService $activityLog)
    {
        $validated = $request->validate([
            'role'              => ['required', new Enum(UserRole::class)],
            'fullname'   => ['required', 'string', 'max:255'],
            'nickname'   => ['nullable', 'string', 'max:100'],
            'phone'      => ['required', 'string', 'max:20'],
            'email'      => ['required', 'string', 'email', Rule::unique('users', 'email')->whereNull('deleted_at')],
            'address'    => ['required', 'nullable', 'string'],
            // Conditional validations for Parent role
            'relationship'      => [Rule::requiredIf($request->role === UserRole::PARENT->value), 'nullable', 'string'],
            'occupation_id'     => [Rule::requiredIf($request->role === UserRole::PARENT->value), 'nullable', 'exists:occupations,id'],
            'occupation_custom' => ['nullable', 'string', 'max:255'],
        ]);

        $plainPassword = Str::password(12);

        try {
            DB::transaction(function () use ($validated, $plainPassword, &$user) {
                $user = User::create([
                    'name'     => $validated['fullname'],
                    'email'    => $validated['email'],
                    'password' => Hash::make($plainPassword),
                    'role'     => $validated['role'],
                ]);

                if ($user->role === UserRole::PARENT) {
                    StudentParent::create([
                        'user_id'           => $user->id,
                        'fullname'          => $validated['fullname'],
                        'nickname'          => $validated['nickname'] ?? null,
                        'phone'             => normalizePhone($validated['phone']),
                        'relationship'      => $validated['relationship'],
                        'occupation_id'     => $validated['occupation_id'],
                        'occupation_custom' => $validated['occupation_custom'] ?? null,
                        'address'           => $validated['address'],
                    ]);
                } else {
                    StudentParent::create([
                        'user_id'           => $user->id,
                        'fullname'          => $validated['fullname'],
                        'nickname'          => $validated['nickname'] ?? null,
                        'phone'             => normalizePhone($validated['phone']),
                        'address'           => $validated['address'],
                    ]);
                }
            });

            $user->notify(new WelcomeUserNotification($plainPassword));

            $activityLog->log(
                'created',
                'user',
                $user->id,
                'Created Account ' .
                    $validated['fullname']
            );

            return redirect()
                ->route('users.index')
                ->with('success', "Pengguna berhasil dibuat. Kredensial login telah dikirim ke {$user->email}.");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create data!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // dd($user->studentParent->occupation_id);
        $occupations = Occupation::select('id', 'name')->where('is_active', true)->orderBy('code', 'asc')->get();
        return view('user-management.edit', [
            'user' => $user,
            'occupations' => $occupations,
            'roles' => UserRole::cases()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        DB::transaction(function () use ($user) {
            $timestamp = time();

            $user->update([
                'email' => $user->email . '_deleted_' . $timestamp,
            ]);

            if ($user->studentParent) {
                $user->studentParent->update([
                    'phone' => $user->studentParent->phone . '_deleted_' . $timestamp,
                ]);

                $user->studentParent->delete();
            }

            $user->delete();
        });

        return redirect()
            ->route('users.index')
            ->with('success', "Pengguna {$user->name} berhasil dinonaktifkan.");
    }

    public function restore(string $id): RedirectResponse
    {
        $user = User::withTrashed()->findOrFail($id);

        DB::transaction(function () use ($user) {
            $user->restore();

            $user->studentParent()->withTrashed()->restore();
        });

        return redirect()
            ->route('users.index')
            ->with('success', "Akun {$user->name} dan data orang tua berhasil diaktifkan kembali.");
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menangguhkan akun Anda sendiri.');
        }

        $user->status = ($user->status === 'active') ? 'suspended' : 'active';
        $user->save();

        return back()->with('success', "Status akun {$user->name} berhasil diubah menjadi {$user->status}.");
    }
}
