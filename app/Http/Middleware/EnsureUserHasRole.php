<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserHasRole
{
    /**
     * Restrict route access to one or more roles (e.g. role:teacher or role:teacher,organization).
     * Admins are always allowed.
     */
    public function handle(Request $request, Closure $next, string $roles = '')
    {
        $user = $request->user();

        if (!$user) {
            abort(403);
        }

        if ($user->isAdmin()) {
            return $next($request);
        }

        $allowedRoles = array_filter(array_map('trim', explode(',', $roles)));

        foreach ($allowedRoles as $role) {
            if ($this->userMatchesRole($user, $role)) {
                return $next($request);
            }
        }

        abort(403);
    }

    private function userMatchesRole($user, string $role): bool
    {
        return match ($role) {
            'teacher' => $user->isTeacher(),
            'organization' => $user->isOrganization(),
            'admin' => $user->isAdmin(),
            'user' => $user->isUser(),
            default => false,
        };
    }
}
