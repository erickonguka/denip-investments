<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureMfaConfigured
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        // If user has MFA enabled but no secret configured, redirect to setup
        if ($user && $user->mfa_enabled && (!$user->mfa_secret || empty($user->mfa_secret))) {
            return redirect()->route('mfa.setup');
        }
        
        return $next($request);
    }
}