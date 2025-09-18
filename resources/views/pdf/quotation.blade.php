@extends('pdf.layout')

@section('title', 'Quotation ' . $quotation->quotation_number)
@section('document_type', 'quotation')
@section('document_id', $quotation->id)

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
    <div class="document-title" style="margin: 0;">QUOTATION</div>
    <div style="text-align: right;">
        <div style="font-size: 18px; font-weight: 700; color: #1e40af;">{{ $quotation->quotation_number }}</div>
        <div style="font-size: 11px; color: #6b7280;">Valid until: {{ $quotation->valid_until->format('M j, Y') }}</div>
    </div>
</div>

<div style="display: flex; justify-content: space-between; margin-bottom: 15px; gap: 30px;">
    <div style="flex: 1;">
        <div style="font-weight: 600; color: #1e40af; margin-bottom: 5px; font-size: 12px;">QUOTE FOR:</div>
        <div style="font-weight: 600;">{{ $quotation->client->name }}</div>
        @if($quotation->client->company)<div>{{ $quotation->client->company }}</div>@endif
        <div>{{ $quotation->client->email }}</div>
        @if($quotation->client->phone)<div>{{ $quotation->client->phone }}</div>@endif
    </div>
    <div style="flex: 1; text-align: right;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 3px;"><span>Date:</span><span>{{ $quotation->created_at->format('M j, Y') }}</span></div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 3px;"><span>Valid Until:</span><span>{{ $quotation->valid_until->format('M j, Y') }}</span></div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 3px;"><span>Status:</span><span class="status-badge status-{{ strtolower($quotation->status) }}">{{ ucfirst($quotation->status) }}</span></div>
        @if($quotation->project)<div style="display: flex; justify-content: space-between;"><span>Project:</span><span>{{ $quotation->project->title }}</span></div>@endif
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
        @foreach($quotation->items as $item)
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
    <div style="flex: 1;">@if($quotation->notes)<div style="font-weight: 600; margin-bottom: 5px;">Notes:</div><div style="font-size: 11px;">{{ $quotation->notes }}</div>@endif</div>
    <div style="width: 250px; background: #f8fafc; padding: 10px; border-radius: 4px;">
        <div style="display: flex; justify-content: space-between; font-weight: 700; font-size: 16px; color: #1e40af;"><span>TOTAL:</span><span>{{ \App\Helpers\SettingsHelper::get('currency_symbol', 'KSh') }}{{ number_format($quotation->total, 2) }}</span></div>
    </div>
</div>

<div style="margin-top: 15px; padding: 8px; background: #f0f9ff; border-radius: 4px; font-size: 11px;">
    <div style="font-weight: 600; color: #0c4a6e; margin-bottom: 3px;">Terms & Conditions:</div>
    <div style="color: #164e63;">{{ \App\Helpers\SettingsHelper::get('quotation_terms', 'This quotation is valid for 30 days from the date of issue. Prices are subject to change without notice. Payment terms: 50% deposit required, balance due upon completion.') }}</div>
</div>
@endsection