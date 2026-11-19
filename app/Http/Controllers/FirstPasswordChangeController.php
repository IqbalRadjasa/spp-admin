<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class FirstPasswordChangeController extends Controller
{
    public function edit()
    {
        return view('auth.first-change-password');
    }

    public function update(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = $request->user();

        try {
            $user->update([
                'password' => Hash::make($request->password),
                'must_change_password' => false,
            ]);

            
            $roleValue = $user->role->value ?? $user->role;
            // dd($roleValue);
            if ($roleValue === 'admin') {
                return redirect()->route('dashboard')->with('success', 'Password Anda berhasil diperbarui.');
            } elseif ($roleValue === 'tu_staff') {
                return redirect()->route('students.index')->with('success', 'Password Anda berhasil diperbarui.');;
            } elseif ($roleValue === 'parent') {
                return redirect()->route('parent.dashboard')->with('success', 'Password Anda berhasil diperbarui.');;
            }
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal memperbarui password, coba lagi nanti.');
        }
    }
}
