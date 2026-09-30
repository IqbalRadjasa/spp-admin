<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        $userRoleValue = $user->role instanceof UserRole ? $user->role->value : $user->role;

        if (! in_array($userRoleValue, $roles, true)) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
