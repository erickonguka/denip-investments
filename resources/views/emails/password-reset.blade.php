@extends('emails.layout', ['subject' => 'Password Reset Request', 'headerSubtitle' => 'Security Alert'])

@section('content')
<h2 style="color: #0f172a; margin-bottom: 1rem;">Password Reset Request</h2>

<p>Hello {{ $user->name }},</p>

<p>We received a request to reset your password for your Denip Investments account. If you made this request, click the button below to reset your password:</p>

<div style="text-align: center; margin: 2rem 0;">
    <a href="{{ $resetUrl }}" class="btn">Reset Password</a>
</div>

<div class="alert alert-warning">
    <strong>Security Notice:</strong> This link will expire in 60 minutes for your security.
</div>

<div class="device-info">
    <strong>Request Details:</strong><br>
    IP Address: {{ $ipAddress }}<br>
    Time: {{ $timestamp }}<br>
    User Agent: {{ $userAgent }}
</div>

<p>If you didn't request this password reset, please ignore this email. Your password will remain unchanged.</p>

<p>For security reasons, if you continue to receive these emails without requesting them, please contact our support team immediately.</p>

<p>Best regards,<br>
The Denip Investments Security Team</p>
@endsection