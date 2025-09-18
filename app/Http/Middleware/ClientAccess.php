<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('client.login');
        }

        $user = Auth::user();
        
        // Block inactive users
        if ($user->status !== 'active') {
            Auth::logout();
            return redirect()->route('client.login')->with('error', 'Account suspended');
        }

        // Only allow clients (users with role = 'client' and no admin roles)
        if ($user->isAdmin()) {
            abort(403, 'Access denied - Admin users cannot access client area');
        }
        
        if (!$user->isClient()) {
            abort(403, 'Access denied - Only clients can access client area');
        }

        return $next($request);
    }
}