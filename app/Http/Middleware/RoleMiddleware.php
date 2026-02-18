<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Usage: ->middleware('role:admin') or ->middleware('role:admin,operator')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if (! in_array($user->role, $roles)) {
            return response()->json([
                'message' => 'Forbidden. You do not have permission to access this resource.',
            ], 403);
        }

        if ($user->status === 'blocked') {
            return response()->json([
                'message' => 'Your account has been blocked.',
            ], 403);
        }

        return $next($request);
    }
}
