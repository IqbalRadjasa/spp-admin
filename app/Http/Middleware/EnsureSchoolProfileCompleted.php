<?php

namespace App\Http\Middleware;

use App\Models\SchoolProfile;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSchoolProfileCompleted
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        $roleValue = $user->role->value ?? $user->role;

        if ($roleValue !== 'admin') {
            return $next($request);
        }

        // Prevent redirect loops on setup and logout routes
        if ($request->routeIs([
            'onboarding.school.*',
            'logout',
        ])) {
            return $next($request);
        }

        $schoolProfile = SchoolProfile::first();

        $isProfileIncomplete = ! $schoolProfile
            || blank($schoolProfile->school_name)
            || blank($schoolProfile->education_level);

        if ($isProfileIncomplete) {
            return redirect()
                ->route('onboarding.school.create')
                ->with('info', 'Silakan lengkapi profil sekolah terlebih dahulu sebelum mengakses sistem.');
        }

        return $next($request);
    }
}