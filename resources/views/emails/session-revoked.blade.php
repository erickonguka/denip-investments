@extends('emails.layout', ['subject' => 'Session Security Alert', 'headerSubtitle' => 'Security Action Required'])

@section('content')
<h2 style="color: #0f172a; margin-bottom: 1rem;">Session Revoked - Security Alert</h2>

<p>Hello {{ $user->name }},</p>

<p>This is an urgent security notification regarding your Denip Investments account.</p>

<div class="alert alert-danger">
    <strong>Action Taken:</strong> We have automatically revoked {{ $revokedCount }} active session(s) from your account due to suspicious activity.
</div>

<div class="device-info">
    <strong>Security Event Details:</strong><br>
    Reason: {{ $reason }}<br>
    Sessions Affected: {{ $revokedCount }}<br>
    Time: {{ $timestamp }}<br>
    Triggered by: {{ $triggeredBy }}
</div>

<div class="alert alert-warning">
    <strong>Immediate Actions Required:</strong>
    <ol style="margin: 0.5rem 0;">
        <li>Change your password immediately</li>
        <li>Review and enable two-factor authentication</li>
        <li>Check for any unauthorized account changes</li>
        <li>Review recent login activity</li>
    </ol>
</div>

<div style="text-align: center; margin: 2rem 0;">
    <a href="{{ $securityUrl }}" class="btn">Secure My Account Now</a>
</div>

<p><strong>What happened?</strong> Our security systems detected potentially unauthorized access to your account and automatically revoked active sessions to protect your data.</p>

<p><strong>What should you do?</strong> Please log in to your account using the button above and follow the security recommendations to ensure your account remains protected.</p>

<p>If you have any questions or need assistance, please contact our security team immediately at security@denipinvestments.com</p>

<p>Best regards,<br>
The Denip Investments Security Team</p>
@endsection