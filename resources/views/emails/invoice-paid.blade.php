<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice Paid - Denip Investments</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #2c3e50, #34495e); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0;">
        <h1 style="margin: 0; font-size: 24px;">Invoice Paid</h1>
        <p style="margin: 10px 0 0 0; opacity: 0.9;">Denip Investments Ltd</p>
    </div>
    
    <div style="background: white; padding: 30px; border: 1px solid #e0e0e0; border-top: none; border-radius: 0 0 8px 8px;">
        <p>Hello Admin,</p>
        
        <p>{{ $client->name }} has marked invoice <strong>#{{ $invoice->invoice_number }}</strong> as paid.</p>
        
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #28a745;">
            <h3 style="margin: 0 0 10px 0; color: #2c3e50;">Invoice Details</h3>
            <p style="margin: 0;"><strong>Invoice Number:</strong> #{{ $invoice->invoice_number }}</p>
            <p style="margin: 5px 0 0 0;"><strong>Amount:</strong> {{ \App\Helpers\CurrencyHelper::format($invoice->total) }}</p>
            <p style="margin: 5px 0 0 0;"><strong>Client:</strong> {{ $client->name }}</p>
        </div>
        
        <p>Please verify the payment and update the invoice status accordingly.</p>
        
        <p>
            <a href="{{ url('/invoices/' . $invoice->id) }}" style="background: #f39c12; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block; font-weight: bold;">
                View Invoice
            </a>
        </p>
        
        <hr style="border: none; border-top: 1px solid #e0e0e0; margin: 30px 0;">
        
        <p style="font-size: 14px; color: #666; margin: 0;">
            This email was sent from Denip Investments Ltd admin system.
        </p>
    </div>
</body>
</html>