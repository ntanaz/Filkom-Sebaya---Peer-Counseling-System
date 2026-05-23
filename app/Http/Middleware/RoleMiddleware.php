<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // If user is already authenticated but doesn't have permissions, redirect to their respective dashboard
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        } elseif ($user->role === 'counselor') {
            return redirect()->route('counselor.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        } else {
            return redirect()->route('konseli.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }
    }
}
