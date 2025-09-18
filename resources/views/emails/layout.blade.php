<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $subject ?? 'Denip Investments' }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #374151;
            margin: 0;
            padding: 0;
            background-color: #f9fafb;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #0f172a 0%, #1e40af 100%);
            padding: 2rem;
            text-align: center;
            color: #ffffff;
        }
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #fbbf24;
            margin-bottom: 0.5rem;
        }
        .content {
            padding: 2rem;
        }
        .footer {
            background: #f3f4f6;
            padding: 1.5rem;
            text-align: center;
            font-size: 0.875rem;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: #1e40af;
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 1rem 0;
        }
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin: 1rem 0;
        }
        .alert-warning {
            background: #fef3c7;
            border: 1px solid #fbbf24;
            color: #92400e;
        }
        .alert-danger {
            background: #fee2e2;
            border: 1px solid #ef4444;
            color: #dc2626;
        }
        .device-info {
            background: #f3f4f6;
            padding: 1rem;
            border-radius: 8px;
            margin: 1rem 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">Denip Investments</div>
            <div style="opacity: 0.9; font-size: 0.9rem;">{{ $headerSubtitle ?? 'Investment Management Platform' }}</div>
        </div>
        
        <div class="content">
            @yield('content')
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} Denip Investments Ltd. All rights reserved.</p>
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>