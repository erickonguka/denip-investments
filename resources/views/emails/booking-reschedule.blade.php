<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Booking Rescheduled - Denip Investments</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #2c3e50, #34495e); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0;">
        <h1 style="margin: 0; font-size: 24px;">Booking Rescheduled</h1>
        <p style="margin: 10px 0 0 0; opacity: 0.9;">Denip Investments Ltd</p>
    </div>
    
    <div style="background: white; padding: 30px; border: 1px solid #e0e0e0; border-top: none; border-radius: 0 0 8px 8px;">
        <p>Hello {{ $booking->user->name }},</p>
        
        <p>Your booking has been rescheduled to a new date.</p>
        
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #ffc107;">
            <h3 style="margin: 0 0 10px 0; color: #2c3e50;">Updated Booking Details</h3>
            <p style="margin: 0;"><strong>New Date:</strong> {{ \Carbon\Carbon::parse($newDate)->format('F j, Y') }}</p>
            <p style="margin: 5px 0 0 0;"><strong>Status:</strong> <span style="color: #ffc107; font-weight: 600;">PENDING APPROVAL</span></p>
        </div>
        
        <p>The booking has been moved to {{ \Carbon\Carbon::parse($newDate)->format('F j, Y') }} and is now pending approval. You will receive another notification once the new date is confirmed.</p>
        
        <p>
            <a href="{{ url('/client/calendar/bookings') }}" style="background: #f39c12; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block; font-weight: bold;">
                View My Bookings
            </a>
        </p>
        
        <hr style="border: none; border-top: 1px solid #e0e0e0; margin: 30px 0;">
        
        <p style="font-size: 14px; color: #666; margin: 0;">
            This email was sent from Denip Investments Ltd. If you have any questions, please contact us.
        </p>
    </div>
</body>
</html>