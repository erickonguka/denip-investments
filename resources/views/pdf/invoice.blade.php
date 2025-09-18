@extends('pdf.layout')

@section('title', 'Invoice ' . $invoice->invoice_number)
@section('document_type', 'invoice')
@section('document_id', $invoice->id)

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
    <div class="document-title" style="margin: 0;">INVOICE</div>
    <div style="text-align: right;">
        <div style="font-size: 18px; font-weight: 700; color: #1e40af;">{{ $invoice->invoice_number }}</div>
        <div style="font-size: 11px; color: #6b7280;">{{ $invoice->issue_date->format('M j, Y') }}</div>
    </div>
</div>

<div style="display: flex; justify-content: space-between; margin-bottom: 15px; gap: 30px;">
    <div style="flex: 1;">
        <div style="font-weight: 600; color: #1e40af; margin-bottom: 5px; font-size: 12px;">BILL TO:</div>
        <div style="font-weight: 600;">{{ $invoice->client->name }}</div>
        @if($invoice->client->company)<div>{{ $invoice->client->company }}</div>@endif
        <div>{{ $invoice->client->email }}</div>
        @if($invoice->client->phone)<div>{{ $invoice->client->phone }}</div>@endif
    </div>
    <div style="flex: 1; text-align: right;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 3px;"><span>Issue Date:</span><span>{{ $invoice->issue_date->format('M j, Y') }}</span></div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 3px;"><span>Due Date:</span><span>{{ $invoice->due_date->format('M j, Y') }}</span></div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 3px;"><span>Status:</span><span class="status-badge status-{{ strtolower($invoice->status) }}">{{ ucfirst($invoice->status) }}</span></div>
        @if($invoice->project)<div style="display: flex; justify-content: space-between;"><span>Project:</span><span>{{ $invoice->project->title }}</span></div>@endif
    </div>
</div>

<table style="margin: 10px 0;">
    <thead>
        <tr>
            <th style="width: 50%;">Description</th>
            <th style="text-align: center; width: 15%;">Qty</th>
            <th style="text-align: right; width: 17.5%;">Rate</th>
            <th style="text-align: right; width: 17.5%;">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($invoice->items as $item)
        <tr>
            <td>{{ $item->description }}</td>
            <td style="text-align: center;">{{ $item->quantity }}</td>
            <td style="text-align: right;">{{ \App\Helpers\SettingsHelper::get('currency_symbol', 'KSh') }}{{ number_format($item->price, 2) }}</td>
            <td style="text-align: right; font-weight: 600;">{{ \App\Helpers\SettingsHelper::get('currency_symbol', 'KSh') }}{{ number_format($item->quantity * $item->price, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div style="display: flex; justify-content: space-between; margin-top: 10px;">
    <div style="flex: 1;">@if($invoice->notes)<div style="font-weight: 600; margin-bottom: 5px;">Notes:</div><div style="font-size: 11px;">{{ $invoice->notes }}</div>@endif</div>
    <div style="width: 250px; background: #f8fafc; padding: 10px; border-radius: 4px;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 3px;"><span>Subtotal:</span><span>{{ \App\Helpers\SettingsHelper::get('currency_symbol', 'KSh') }}{{ number_format($invoice->subtotal, 2) }}</span></div>
        @if($invoice->tax_rate > 0)<div style="display: flex; justify-content: space-between; margin-bottom: 3px;"><span>Tax ({{ $invoice->tax_rate }}%):</span><span>{{ \App\Helpers\SettingsHelper::get('currency_symbol', 'KSh') }}{{ number_format($invoice->tax_amount, 2) }}</span></div>@endif
        <div style="display: flex; justify-content: space-between; font-weight: 700; font-size: 16px; color: #1e40af; border-top: 2px solid #1e40af; padding-top: 5px; margin-top: 5px;"><span>TOTAL:</span><span>{{ \App\Helpers\SettingsHelper::get('currency_symbol', 'KSh') }}{{ number_format($invoice->total, 2) }}</span></div>
    </div>
</div>

<div style="margin-top: 15px; padding: 8px; background: #f0f9ff; border-radius: 4px; font-size: 11px;">
    <div style="font-weight: 600; color: #0c4a6e; margin-bottom: 3px;">Payment Terms:</div>
    <div style="color: #164e63;">{{ \App\Helpers\SettingsHelper::get('invoice_terms', 'Payment is due within 30 days of invoice date. Late payments may incur additional charges.') }}</div>
</div>
@endsection