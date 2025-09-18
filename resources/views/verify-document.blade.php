<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Verification - Denip Investments</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: white;
            border-radius: 12px;
            padding: 3rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            max-width: 500px;
            width: 90%;
            text-align: center;
        }
        .logo {
            width: 120px;
            height: auto;
            margin-bottom: 2rem;
        }
        .status-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        .verified {
            color: #10b981;
        }
        .invalid {
            color: #ef4444;
        }
        h1 {
            color: #1f2937;
            margin-bottom: 1rem;
        }
        .document-info {
            background: #f9fafb;
            padding: 1.5rem;
            border-radius: 8px;
            margin: 1.5rem 0;
            text-align: left;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }
        .label {
            font-weight: 600;
            color: #6b7280;
        }
        .value {
            color: #1f2937;
        }
        .footer {
            margin-top: 2rem;
            color: #6b7280;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="{{ asset('img/denip-logo.svg') }}" alt="Denip Investments" class="logo">
        
        @if($document)
            <div class="status-icon verified">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1>Document Verified</h1>
            <p>This document is authentic and was issued by Denip Investments Ltd.</p>
            
            <div class="document-info">
                <div class="info-row">
                    <span class="label">Document Type:</span>
                    <span class="value">{{ $type }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Document Number:</span>
                    <span class="value">
                        @if($type === 'Invoice')
                            {{ $document->invoice_number }}
                        @elseif($type === 'Quotation')
                            {{ $document->quotation_number }}
                        @elseif($type === 'Proposal')
                            {{ $document->proposal_number }}
                        @else
                            {{ $document->title }}
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="label">Client:</span>
                    <span class="value">{{ $document->client->name }}</span>
                </div>
                <div class="info-row">
                    <span class="label">{{ $type === 'Project' ? 'Start Date' : 'Issue Date' }}:</span>
                    <span class="value">
                        @if($type === 'Invoice')
                            {{ $document->issue_date->format('M j, Y') }}
                        @elseif($type === 'Project')
                            {{ $document->start_date->format('M j, Y') }}
                        @else
                            {{ $document->created_at->format('M j, Y') }}
                        @endif
                    </span>
                </div>
                @if($type === 'Invoice' || $type === 'Quotation')
                <div class="info-row">
                    <span class="label">Total Amount:</span>
                    <span class="value">KSh {{ number_format($document->total, 2) }}</span>
                </div>
                @elseif($type === 'Proposal')
                <div class="info-row">
                    <span class="label">Estimated Value:</span>
                    <span class="value">KSh {{ number_format($document->estimated_value, 2) }}</span>
                </div>
                @elseif($type === 'Project')
                <div class="info-row">
                    <span class="label">Project Budget:</span>
                    <span class="value">KSh {{ number_format($document->budget, 2) }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Status:</span>
                    <span class="value">{{ ucfirst($document->status) }}</span>
                </div>
                @endif
            </div>
        @else
            <div class="status-icon invalid">
                <i class="fas fa-times-circle"></i>
            </div>
            <h1>Document Not Found</h1>
            <p>This document could not be verified. It may not be authentic or the verification link may be invalid.</p>
        @endif
        
        <div class="footer">
            <p><strong>Denip Investments Ltd</strong></p>
            <p>For any questions about this verification, contact us at info@denipinvestments.com</p>
        </div>
    </div>
</body>
</html>