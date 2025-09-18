<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reply from {{ $companyName }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #1e3a8a, #1e40af); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0;">
        <h1 style="margin: 0; font-size: 24px;">{{ $companyName }}</h1>
        <p style="margin: 10px 0 0 0; opacity: 0.9;">Thank you for contacting us</p>
    </div>
    
    <div style="background: white; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 8px 8px;">
        <p>Dear {{ $originalMessage->sender_name ?? 'Valued Customer' }},</p>
        
        <p>Thank you for your inquiry through our website. {{ $adminName }} from our team has responded to your message:</p>
        
        <div style="background: #f9fafb; border-left: 4px solid #1e40af; padding: 20px; margin: 20px 0; border-radius: 4px;">
            <div style="white-space: pre-wrap; line-height: 1.6;">{{ $replyMessage->body }}</div>
        </div>
        
        <div style="background: #f3f4f6; padding: 15px; border-radius: 4px; margin: 20px 0; font-size: 14px;">
            <strong>Your Original Message:</strong><br>
            <em>{{ $originalMessage->subject }}</em><br>
            <div style="margin-top: 10px; color: #6b7280;">{{ Str::limit($originalMessage->body, 200) }}</div>
        </div>
        
        <p>If you have any further questions or need additional assistance, please don't hesitate to contact us:</p>
        
        <div style="background: #eff6ff; padding: 20px; border-radius: 4px; margin: 20px 0;">
            <p style="margin: 0;"><strong>Contact Information:</strong></p>
            <p style="margin: 5px 0;">📧 Email: {{ \App\Models\Setting::get('company_email', 'info@denipinvestments.com') }}</p>
            <p style="margin: 5px 0;">📞 Phone: {{ \App\Models\Setting::get('company_phone', '(254) 788 225 898') }}</p>
            <p style="margin: 5px 0;">📍 Address: {{ \App\Models\Setting::get('company_address', 'Nairobi, Kenya') }}</p>
        </div>
        
        <p>Best regards,<br>
        <strong>{{ $adminName }}</strong><br>
        {{ $companyName }}</p>
    </div>
    
    <div style="text-align: center; padding: 20px; color: #6b7280; font-size: 12px;">
        <p>This email was sent in response to your inquiry on our website.</p>
        <p>© {{ date('Y') }} {{ $companyName }}. All rights reserved.</p>
    </div>
</body>
</html>