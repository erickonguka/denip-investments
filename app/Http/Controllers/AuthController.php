<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;
use PragmaRX\Google2FA\Google2FA;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Additional security checks
        if (!$request->ajax() && !$request->wantsJson()) {
            return response()->json(['success' => false, 'errors' => ['general' => ['Invalid request']]], 400);
        }

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
            \Log::info('MFA Check', ['user_id' => $user->id, 'mfa_enabled' => $user->mfa_enabled, 'has_secret' => !empty($user->mfa_secret)]);
            
            // If MFA is enabled but no secret exists, login and let middleware redirect
            if (!$user->mfa_secret || empty($user->mfa_secret)) {
                Auth::login($user, true);
                \Log::info('Logging in user with incomplete MFA', ['user_id' => $user->id]);
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

            $google2fa = new Google2FA();
            $secret = decrypt($user->mfa_secret);
            if (!$google2fa->verifyKey($secret, $request->mfa_code)) {
                return response()->json([
                    'success' => false,
                    'errors' => ['mfa_code' => ['Invalid verification code']]
                ]);
            }
        }

        // Check for new device
        $this->checkNewDevice($request, $user);

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

    private function checkNewDevice(Request $request, User $user)
    {
        $userAgent = $request->userAgent();
        $ip = $request->ip();
        
        // Simple device detection (in production, use more sophisticated method)
        $deviceHash = md5($userAgent . $ip);
        $knownDevices = Session::get('known_devices', []);
        
        if (!in_array($deviceHash, $knownDevices)) {
            // Send email notification for new device
            $this->sendNewDeviceNotification($user, $request);
            
            // Store device
            $knownDevices[] = $deviceHash;
            Session::put('known_devices', $knownDevices);
        }
    }

    private function sendNewDeviceNotification(User $user, Request $request)
    {
        // In production, implement proper email notification
        // For now, just log it
        \Log::info('New device login', [
            'user' => $user->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'time' => now()
        ]);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        
        \App\Models\ActivityLog::log('logout', $user, 'Logged out');
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
    
    public function account()
    {
        $user = Auth::user();
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }
    
    public function updateAccount(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'profile_photo' => 'nullable|image|max:5120|mimes:jpeg,jpg,png,gif,webp',
        ]);
        
        $user = Auth::user();
        
        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo && \Storage::disk('public')->exists($user->profile_photo)) {
                \Storage::disk('public')->delete($user->profile_photo);
            }
            $validated['profile_photo'] = $request->file('profile_photo')->store('profile-photos', 'public');
        }
        
        $user->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Account updated successfully'
        ]);
    }

    public function showMfaSetup()
    {
        // Must be authenticated to access MFA setup
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();
        
        return view('auth.mfa-setup', compact('secret', 'user'));
    }

    public function enableMfa(Request $request)
    {
        $user = Auth::user();
        $google2fa = new Google2FA();
        
        if (!$user->mfa_secret) {
            $secret = $google2fa->generateSecretKey();
            $user->update(['mfa_secret' => $secret]);
        }
        
        // Generate QR code using BaconQrCode
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            'Denip Investments',
            $user->email,
            $user->mfa_secret
        );
        
        // Generate QR code as data URL
        $writer = new \BaconQrCode\Writer(
            new \BaconQrCode\Renderer\ImageRenderer(
                new \BaconQrCode\Renderer\RendererStyle\RendererStyle(200),
                new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
            )
        );
        
        $qrCodeSvg = $writer->writeString($qrCodeUrl);
        $qrCodeDataUrl = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);
        
        return response()->json([
            'success' => true,
            'qr_code' => $qrCodeDataUrl,
            'secret' => $user->mfa_secret
        ]);
    }

    public function confirmMfa(Request $request)
    {
        $request->validate([
            'secret' => 'required|string',
            'code' => 'required|string|size:6'
        ]);

        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated. Please login again.'
            ]);
        }
        
        $user = Auth::user();

        $google2fa = new Google2FA();
        
        if ($google2fa->verifyKey($request->secret, $request->code)) {
            $user->update([
                'mfa_secret' => encrypt($request->secret),
                'mfa_enabled' => true
            ]);
            
            \App\Models\ActivityLog::log('mfa_enabled', $user, 'Multi-factor authentication enabled');
            
            return response()->json([
                'success' => true,
                'message' => 'MFA enabled successfully'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Invalid verification code'
        ]);
    }

    public function disableMfa(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'mfa_code' => 'required|string|size:6'
        ]);

        $user = Auth::user();
        
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'errors' => ['password' => ['Invalid password']]
            ]);
        }

        $google2fa = new Google2FA();
        $secret = decrypt($user->mfa_secret);
        if (!$google2fa->verifyKey($secret, $request->mfa_code)) {
            return response()->json([
                'success' => false,
                'errors' => ['mfa_code' => ['Invalid verification code']]
            ]);
        }

        $user->update([
            'mfa_enabled' => false,
            'mfa_secret' => null
        ]);
        
        \App\Models\ActivityLog::log('mfa_disabled', $user, 'Multi-factor authentication disabled');
        
        return response()->json([
            'success' => true,
            'message' => 'MFA disabled successfully'
        ]);
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'errors' => ['email' => ['No account found with this email address']]
            ]);
        }

        $token = Str::random(64);
        \DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        $resetUrl = url('/reset-password/' . $token . '?email=' . urlencode($request->email));
        
        Mail::send('emails.password-reset', [
            'user' => $user,
            'resetUrl' => $resetUrl,
            'ipAddress' => $request->ip(),
            'timestamp' => now()->format('M j, Y g:i A'),
            'userAgent' => $request->userAgent()
        ], function ($message) use ($user) {
            $message->to($user->email)
                   ->subject('Password Reset Request - Denip Investments');
        });

        return response()->json([
            'success' => true,
            'message' => 'Password reset link sent to your email'
        ]);
    }

    public function showResetPassword(Request $request, $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed'
        ]);

        $resetRecord = \DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
            return response()->json([
                'success' => false,
                'errors' => ['token' => ['Invalid or expired reset token']]
            ]);
        }

        if (now()->diffInMinutes($resetRecord->created_at) > 60) {
            return response()->json([
                'success' => false,
                'errors' => ['token' => ['Reset token has expired']]
            ]);
        }

        $user = User::where('email', $request->email)->first();
        $user->update(['password' => Hash::make($request->password)]);

        \DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully',
            'redirect' => route('login')
        ]);
    }
}