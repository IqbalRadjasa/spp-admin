<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordIsChanged
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->must_change_password) {
            if ($request->routeIs('password.first_change', 'password.first_change.update', 'logout')) {
                return $next($request);
            }

            return redirect()->route('password.first_change')
                ->with('warning', 'Harap ubah password sementara Anda terlebih dahulu sebelum melanjutkan.');
        }

        return $next($request);
    }
}
