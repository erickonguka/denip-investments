<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;

class ClientAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.client-login');
    }
    
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.client-onboarding');
    }
    
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'company' => 'required|string|max:255',
            'country' => 'required|string|size:2',
            'password' => 'required|min:8|confirmed',
            'project_type' => 'required|string|max:100',
            'project_scale' => 'required|string|max:100',
            'project_location' => 'required|string|max:255',
            'project_timeline' => 'required|string|max:100',
            'industry' => 'required|string|max:100',
            'job_title' => 'required|string|max:255',
            'contact_preference' => 'required|string|max:50',
            'company_size' => 'nullable|string|max:50',
            'years_in_business' => 'nullable|string|max:50',
            'registration_number' => 'nullable|string|max:100',
            'project_description' => 'nullable|string|max:2000',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'formatted_address' => 'nullable|string|max:500',
            'place_id' => 'nullable|string|max:255',
        ]);
        
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company' => $request->company,
            'country' => $request->country,
            'password' => Hash::make($request->password),
            'email_verification_token' => $verificationCode,
            'email_verified_at' => null,
            'role' => 'client',
            'status' => 'pending',
            'job_title' => $request->job_title,
            'industry' => $request->industry,
            'project_type' => $request->project_type,
            'project_scale' => $request->project_scale,
            'project_location' => $request->project_location,
            'project_timeline' => $request->project_timeline,
            'contact_preference' => $request->contact_preference,
            'company_size' => $request->company_size,
            'years_in_business' => $request->years_in_business,
            'registration_number' => $request->registration_number,
            'project_description' => $request->project_description,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'formatted_address' => $request->formatted_address,
            'place_id' => $request->place_id,
        ]);
        
        // Assign client role in RBAC system
        $clientRole = \App\Models\Role::where('name', 'client')->first();
        if ($clientRole) {
            $user->roles()->attach($clientRole->id);
        }
        
        // Send verification email
        Mail::send('emails.verify-email', [
            'user' => $user,
            'code' => $verificationCode
        ], function ($message) use ($user) {
            $message->to($user->email)
                   ->subject('Verify Your Email - Denip Investments');
        });
        
        return response()->json([
            'success' => true,
            'redirect' => route('client.verify', ['email' => $user->email])
        ]);
    }
    
    public function showVerifyEmail(Request $request)
    {
        $email = $request->get('email');
        if (!$email) {
            return redirect()->route('client.register');
        }
        return view('auth.verify-email', compact('email'));
    }
    
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6'
        ]);
        
        $user = User::where('email', $request->email)
                   ->where('email_verification_token', $request->code)
                   ->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'errors' => ['code' => ['Invalid verification code']]
            ]);
        }
        
        $user->update([
            'email_verified_at' => now(),
            'email_verification_token' => null,
            'status' => 'active'
        ]);
        
        Auth::login($user);
        
        return response()->json([
            'success' => true,
            'redirect' => route('dashboard')
        ]);
    }
    
    public function resendVerification(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['success' => false]);
        }
        
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->update(['email_verification_token' => $verificationCode]);
        
        Mail::send('emails.verify-email', [
            'user' => $user,
            'code' => $verificationCode
        ], function ($message) use ($user) {
            $message->to($user->email)
                   ->subject('Verify Your Email - Denip Investments');
        });
        
        return response()->json(['success' => true]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'mfa_code' => 'nullable|string|size:6'
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

        // Check if MFA is enabled
        if ($user->mfa_enabled) {
            if (!$user->mfa_secret || empty($user->mfa_secret)) {
                Auth::login($user, true);
                return response()->json([
                    'success' => true,
                    'redirect' => route('dashboard')
                ]);
            }
            
            if (!$request->mfa_code) {
                return response()->json([
                    'success' => true,
                    'requires_mfa' => true
                ]);
            }

            $google2fa = new \PragmaRX\Google2FA\Google2FA();
            $secret = decrypt($user->mfa_secret);
            if (!$google2fa->verifyKey($secret, $request->mfa_code)) {
                return response()->json([
                    'success' => false,
                    'errors' => ['mfa_code' => ['Invalid verification code']]
                ]);
            }
        }

        // Update login info
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip()
        ]);

        Auth::login($user, true);
        
        \App\Models\ActivityLog::log('login', $user, 'Logged in successfully');

        return response()->json([
            'success' => true,
            'redirect' => route('dashboard')
        ]);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        
        \App\Models\ActivityLog::log('logout', $user, 'Logged out');
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('landing.index');
    }
}