<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UnifiedAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.unified-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'errors' => ['email' => ['Invalid credentials']]
            ]);
        }

        if ($user->status !== 'active') {
            return response()->json([
                'success' => false,
                'errors' => ['email' => ['Account is inactive']]
            ]);
        }

        Auth::login($user, true);

        return response()->json([
            'success' => true,
            'redirect' => route('dashboard')
        ]);
    }
}