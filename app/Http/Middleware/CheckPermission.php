<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission)
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

        // Check if user has the required permission
        if (!$user->hasPermission($permission)) {
            abort(403, 'Access denied - Insufficient permissions');
        }

        return $next($request);
    }
}