@extends('emails.layout', ['subject' => 'New Device Login Alert', 'headerSubtitle' => 'Security Notification'])

@section('content')
<h2 style="color: #0f172a; margin-bottom: 1rem;">New Device Login Detected</h2>

<p>Hello {{ $user->name }},</p>

<p>We detected a new login to your Denip Investments account from a device we haven't seen before.</p>

<div class="device-info">
    <strong>Login Details:</strong><br>
    Device: {{ $deviceType }} ({{ $browser }})<br>
    IP Address: {{ $ipAddress }}<br>
    Location: {{ $location ?? 'Unknown' }}<br>
    Time: {{ $loginTime }}
</div>

<div class="alert alert-warning">
    <strong>Was this you?</strong> If you recognize this login, no action is needed. Your account remains secure.
</div>

<div class="alert alert-danger">
    <strong>Don't recognize this login?</strong> Your account may be compromised. Take action immediately:
    <ul style="margin: 0.5rem 0;">
        <li>Change your password immediately</li>
        <li>Review your active sessions</li>
        <li>Enable two-factor authentication if not already active</li>
    </ul>
</div>

<div style="text-align: center; margin: 2rem 0;">
    <a href="{{ $securityUrl }}" class="btn">Review Account Security</a>
</div>

<p>If you need assistance securing your account, please contact our support team immediately.</p>

<p>Best regards,<br>
The Denip Investments Security Team</p>
@endsection