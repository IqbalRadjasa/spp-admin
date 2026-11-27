<?php

namespace App\Http\Controllers\Auth;

use App\Models\SchoolProfile;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        if ($request->user()->status === 'suspended') {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors([
                    'email' => 'Akun Anda telah ditangguhkan / Suspended. Silakan hubungi administrator.',
                ]);
        }

        $request->session()->regenerate();

        $user = auth()->user();

        $roleValue = $user->role->value ?? $user->role;

        if ($roleValue === 'admin') {
            $schoolProfile = SchoolProfile::first();

            $isProfileIncomplete = ! $schoolProfile
                || blank($schoolProfile->school_name)
                || blank($schoolProfile->education_level);

            if ($isProfileIncomplete) {
                return redirect()
                    ->route('onboarding.school.create')
                    ->with('info', 'Silakan lengkapi profil sekolah terlebih dahulu sebelum masuk ke dashboard.');
            }

            return redirect()->route('dashboard')->with('success', 'Berhasil masuk ke akun.');
        } elseif ($roleValue === 'tu_staff') {
            return redirect()->route('students.index')->with('success', 'Berhasil masuk ke akun.');
        } elseif ($roleValue === 'parent') {
            return redirect()->route('parent.dashboard')->with('success', 'Berhasil masuk ke akun.');
        }

        // Safe error reporting for Enums
        abort(403, 'Unauthorized role: ' . (is_object($user->role) ? $user->role->value : $user->role));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
