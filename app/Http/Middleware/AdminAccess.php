<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Block inactive users
        if ($user->status !== 'active') {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Account suspended');
        }

        // Check if user has admin role first
        if (!$user->isAdmin()) {
            // Log for debugging
            \Log::info('Admin access denied', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role_field' => $user->role,
                'roles' => $user->roles->pluck('name'),
                'isAdmin' => $user->isAdmin(),
                'isClient' => $user->isClient()
            ]);
            abort(403, 'Access denied - No admin privileges');
        }

        return $next($request);
    }
}