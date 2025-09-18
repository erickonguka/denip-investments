<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Proposal {{ ucfirst($status) }} - Denip Investments</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #2c3e50, #34495e); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0;">
        <h1 style="margin: 0; font-size: 24px;">Proposal {{ ucfirst($status) }}</h1>
        <p style="margin: 10px 0 0 0; opacity: 0.9;">Denip Investments Ltd</p>
    </div>
    
    <div style="background: white; padding: 30px; border: 1px solid #e0e0e0; border-top: none; border-radius: 0 0 8px 8px;">
        <p>Hello Admin,</p>
        
        <p>{{ $client->name }} has <strong>{{ $status }}</strong> the proposal "{{ $proposal->title }}".</p>
        
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid {{ $status === 'accepted' ? '#28a745' : '#dc3545' }};">
            <h3 style="margin: 0 0 10px 0; color: #2c3e50;">Proposal Details</h3>
            <p style="margin: 0;"><strong>Title:</strong> {{ $proposal->title }}</p>
            <p style="margin: 5px 0 0 0;"><strong>Client:</strong> {{ $client->name }}</p>
            <p style="margin: 5px 0 0 0;"><strong>Status:</strong> <span style="color: {{ $status === 'accepted' ? '#28a745' : '#dc3545' }}; font-weight: 600;">{{ strtoupper($status) }}</span></p>
        </div>
        
        @if($status === 'accepted')
            <p>Great news! The client has accepted the proposal. You can now proceed with the project.</p>
        @else
            <p>The client has rejected the proposal. You may want to follow up or create a revised proposal.</p>
        @endif
        
        <p>
            <a href="{{ url('/proposals/' . $proposal->id) }}" style="background: #f39c12; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block; font-weight: bold;">
                View Proposal
            </a>
        </p>
        
        <hr style="border: none; border-top: 1px solid #e0e0e0; margin: 30px 0;">
        
        <p style="font-size: 14px; color: #666; margin: 0;">
            This email was sent from Denip Investments Ltd admin system.
        </p>
    </div>
</body>
</html>