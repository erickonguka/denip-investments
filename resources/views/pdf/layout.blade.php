<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 20px;
            color: #1f2937;
            line-height: 1.4;
            background: #ffffff;
            font-size: 29px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #2563eb;
            position: relative;
        }
        
        .header::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, #3b82f6, #1d4ed8);
        }
        
        .logo-section {
            flex: 1;
        }
        
        .logo {
            width: 60px;
            height: auto;
            margin-bottom: 35px;
            display: block;
        }
        
        .company-info {
            text-align: left;
        }
        
        .company-slogan {
            font-size: 23px;
            font-weight: 500;
            color: #6b7280;
            margin-top: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .company-details {
            font-size: 14px;
            color: #4b5563;
            line-height: 1.8;
        }
        
        .document-title {
            font-size: 50px;
            font-weight: 700;
            color: #1e40af;
            margin: 10px 0 10px 0;
            text-align: left;
            letter-spacing: -0.5px;
            position: relative;
        }
        
        .document-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #3b82f6, #1d4ed8);
            border-radius: 2px;
        }
        
        .document-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            gap: 20px;
        }
        
        .client-info, .document-info {
            flex: 1;
            background: #f8fafc;
            padding: 10px;
            border-radius: 6px;
            border-left: 3px solid #3b82f6;
        }
        
        .info-section-title {
            font-size: 26px;
            font-weight: 600;
            color: #1e40af;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .info-label {
            font-weight: 600;
            color: #374151;
            font-size: 23px;
            margin-bottom: 2px;
        }
        
        .info-value {
            color: #1f2937;
            margin-bottom: 6px;
            font-size: 26px;
        }
        
        .content-section {
            margin: 15px 0;
        }
        
        .section-title {
            font-size: 32px;
            font-weight: 600;
            color: #1e40af;
            margin-bottom: 8px;
            padding-bottom: 3px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
            background: #ffffff;
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }
        
        th {
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            color: #ffffff;
            font-weight: 600;
            font-size: 23px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            padding: 8px 6px;
            text-align: left;
        }
        
        td {
            padding: 6px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 26px;
            color: #374151;
        }
        
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        tr:hover {
            background-color: #f3f4f6;
        }
        
        .total-section {
            margin-top: 10px;
            background: #f8fafc;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #e5e7eb;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 6px 0;
            padding: 4px 0;
            font-size: 29px;
        }
        
        .total-row span:first-child {
            font-weight: 500;
            color: #374151;
        }
        
        .total-row span:last-child {
            font-weight: 600;
            color: #1f2937;
        }
        
        .total-final {
            font-size: 38px;
            font-weight: 700;
            border-top: 2px solid #1e40af;
            padding-top: 8px;
            margin-top: 8px;
            color: #1e40af;
        }
        
        .notes-section {
            margin-top: 10px;
            background: #fffbeb;
            padding: 10px;
            border-radius: 4px;
            border-left: 3px solid #f59e0b;
        }
        
        .notes-title {
            font-size: 16px;
            font-weight: 600;
            color: #92400e;
            margin-bottom: 12px;
        }
        
        .notes-content {
            color: #78350f;
            line-height: 1.7;
        }
        
        .terms-section {
            margin-top: 10px;
            background: #f0f9ff;
            padding: 10px;
            border-radius: 4px;
            border-left: 3px solid #0ea5e9;
        }
        
        .terms-title {
            font-size: 16px;
            font-weight: 600;
            color: #0c4a6e;
            margin-bottom: 12px;
        }
        
        .terms-content {
            color: #164e63;
            line-height: 1.7;
        }
        
        .footer {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            text-align: left;
            color: #6b7280;
            font-size: 12px;
            background: #f9fafb;
            margin-left: -20px;
            margin-right: -20px;
            margin-bottom: -20px;
            padding-left: 20px;
            padding-right: 20px;
            padding-bottom: 15px;
        }
        
        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .verification-link {
            color: #1e40af;
            text-decoration: none;
            font-weight: 600;
            padding: 8px 16px;
            background: #ffffff;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            transition: all 0.2s;
        }
        
        .verification-link:hover {
            background: #1e40af;
            color: #ffffff;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 26px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-paid {
            background: #dcfce7;
            color: #166534;
        }
        
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        
        .status-overdue {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .status-draft {
            background: #f3f4f6;
            color: #374151;
        }
        
        .progress-bar {
            width: 100%;
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 8px;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #10b981, #059669);
            transition: width 0.3s ease;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-section">
            <img src="data:image/svg+xml;base64,{{ base64_encode(file_get_contents(public_path('img/denip-logo.svg'))) }}" class="logo" alt="Denip Investments Logo">
            <div class="company-slogan">{{ \App\Helpers\SettingsHelper::get('company_slogan', 'Your Investment Partner') }}</div>
        </div>
        <div class="company-details">
            <div>{{ \App\Helpers\SettingsHelper::get('company_address', 'Nairobi, Kenya') }}</div>
            <div>{{ \App\Helpers\SettingsHelper::get('company_phone', '+254 700 000 000') }}</div>
            <div>{{ \App\Helpers\SettingsHelper::get('company_email', 'info@denipinvestments.com') }}</div>
        </div>
    </div>

    @yield('content')

    <div class="footer">
        <div class="footer-content">
            <div>
                <div style="font-weight: 600; margin-bottom: 8px;">{{ \App\Helpers\SettingsHelper::get('site_name', 'Denip Investments Ltd') }}</div>
                <div>{{ \App\Helpers\SettingsHelper::get('company_email', 'info@denipinvestments.com') }} | Generated on {{ now()->format('F j, Y \a\t g:i A') }}  &nbsp;&nbsp;<a href="{{ url('/verify/' . md5(($__env->yieldContent('document_type', 'unknown') . '_' . $__env->yieldContent('document_id', 'unknown')))) }}" class="verification-link">🔒 Verify Document</a></div>
            </div>
        </div>
    </div>
</body>
</html>