<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if (Auth::check() && (Auth::user()->role == $role || Auth::user()->role == 'super')) {
            return $next($request);
        }

        return response()->json(['message' => $role], 403);
    }
}
