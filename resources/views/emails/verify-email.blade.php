<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verify Your Email - Denip Investments</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2c3e50; color: white; padding: 20px; text-align: center; }
        .content { background: #f8f9fa; padding: 30px; }
        .code { background: #fff; border: 2px solid #f39c12; padding: 20px; text-align: center; margin: 20px 0; border-radius: 8px; }
        .code-number { font-size: 32px; font-weight: bold; color: #2c3e50; letter-spacing: 5px; }
        .footer { background: #34495e; color: white; padding: 20px; text-align: center; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Denip Investments Ltd</h1>
            <p>Email Verification</p>
        </div>
        
        <div class="content">
            <h2>Welcome {{ $user->first_name }}!</h2>
            <p>Thank you for creating an account with Denip Investments. To complete your registration, please verify your email address using the code below:</p>
            
            <div class="code">
                <p>Your verification code is:</p>
                <div class="code-number">{{ $code }}</div>
                <p><small>This code will expire in 15 minutes</small></p>
            </div>
            
            <p>If you didn't create an account with us, please ignore this email.</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Denip Investments Ltd. All rights reserved.</p>
        </div>
    </div>
</body>
</html>