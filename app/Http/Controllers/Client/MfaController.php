<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FA\Google2FA;

class MfaController extends Controller
{
    public function enable(Request $request)
    {
        $user = Auth::user();
        $google2fa = new Google2FA();
        
        if (!$user->mfa_secret) {
            $secret = $google2fa->generateSecretKey();
            $user->update(['mfa_secret' => $secret]);
        }
        
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            'Denip Investments',
            $user->email,
            $user->mfa_secret
        );
        
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

    public function confirm(Request $request)
    {
        $request->validate([
            'secret' => 'required|string',
            'code' => 'required|string|size:6'
        ]);

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

    public function disable(Request $request)
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
}