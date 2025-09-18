<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectController extends Controller
{
    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Redirect based on user role
        if ($user->isClient()) {
            return redirect()->route('client.dashboard');
        }
        
        // Check if user has admin privileges
        if ($user->isAdmin()) {
            return redirect()->route('dashboard');
        }
        
        // If no valid role, logout and redirect to login
        Auth::logout();
        return redirect()->route('login')->with('error', 'Invalid user role');
    }
}