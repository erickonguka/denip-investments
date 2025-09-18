@extends('pdf.layout')

@section('title', 'Project Report - ' . $project->title)
@section('document_type', 'project')
@section('document_id', $project->id)

@section('content')
<div class="document-title">PROJECT MANUAL</div>

<div style="display: flex; justify-content: space-between; margin-bottom: 15px; gap: 20px;">
    <div style="flex: 1; background: #f8fafc; padding: 12px; border-radius: 4px;">
        <div style="font-weight: 600; color: #1e40af; margin-bottom: 8px; font-size: 14px;">{{ $project->title }}</div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 3px; font-size: 12px;"><span>Client:</span><span>{{ $project->client->name }}</span></div>
        @if($project->client->company)<div style="display: flex; justify-content: space-between; margin-bottom: 3px; font-size: 12px;"><span>Company:</span><span>{{ $project->client->company }}</span></div>@endif
        <div style="display: flex; justify-content: space-between; margin-bottom: 3px; font-size: 12px;"><span>Contact:</span><span>{{ $project->client->email }}</span></div>
    </div>
    <div style="flex: 1; background: #f8fafc; padding: 12px; border-radius: 4px;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 3px; font-size: 12px;"><span>Start Date:</span><span>{{ $project->start_date->format('M j, Y') }}</span></div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 3px; font-size: 12px;"><span>End Date:</span><span>{{ $project->end_date?->format('M j, Y') ?? 'Not set' }}</span></div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 3px; font-size: 12px;"><span>Status:</span><span class="status-badge status-{{ strtolower($project->status) }}">{{ ucfirst($project->status) }}</span></div>
        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px;"><span>Progress:</span><span>{{ $project->progress }}%</span></div>
        <div class="progress-bar" style="margin-top: 5px;"><div class="progress-fill" style="width: {{ $project->progress }}%"></div></div>
    </div>
</div>

<div style="display: flex; justify-content: space-between; gap: 20px; margin-bottom: 15px;">
    <div style="flex: 2;">
        <div style="font-size: 14px; font-weight: 600; color: #1e40af; margin-bottom: 8px; border-bottom: 1px solid #e5e7eb; padding-bottom: 3px;">Project Overview</div>
        <div style="background: #f8fafc; padding: 12px; border-radius: 4px; border-left: 3px solid #10b981; font-size: 12px; line-height: 1.5;">
            {!! $project->description ?? 'No description provided' !!}
        </div>
    </div>
    <div style="flex: 1;">
        <div style="font-size: 14px; font-weight: 600; color: #1e40af; margin-bottom: 8px; border-bottom: 1px solid #e5e7eb; padding-bottom: 3px;">Budget</div>
        <div style="background: #f8fafc; padding: 12px; border-radius: 4px; text-align: center;">
            <div style="font-size: 11px; color: #6b7280; margin-bottom: 5px;">Total Budget</div>
            <div style="font-size: 18px; font-weight: 700; color: #1e40af;">{{ \App\Helpers\SettingsHelper::get('currency_symbol', 'KSh') }}{{ number_format($project->budget, 2) }}</div>
        </div>
    </div>
</div>

@if($project->invoices->count() > 0)
<div style="margin-bottom: 15px;">
    <div style="font-size: 14px; font-weight: 600; color: #1e40af; margin-bottom: 8px; border-bottom: 1px solid #e5e7eb; padding-bottom: 3px;">Financial Records - Invoices</div>
    <table style="margin: 5px 0;">
        <thead>
            <tr>
                <th style="width: 25%;">Invoice #</th>
                <th style="width: 20%;">Issue Date</th>
                <th style="width: 20%;">Due Date</th>
                <th style="text-align: right; width: 20%;">Amount</th>
                <th style="text-align: center; width: 15%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($project->invoices as $invoice)
            <tr>
                <td>{{ $invoice->invoice_number }}</td>
                <td>{{ $invoice->issue_date->format('M j, Y') }}</td>
                <td>{{ $invoice->due_date->format('M j, Y') }}</td>
                <td style="text-align: right; font-weight: 600;">{{ \App\Helpers\SettingsHelper::get('currency_symbol', 'KSh') }}{{ number_format($invoice->total, 2) }}</td>
                <td style="text-align: center;"><span class="status-badge status-{{ strtolower($invoice->status) }}">{{ ucfirst($invoice->status) }}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@if($project->proposals->count() > 0)
<div style="margin-bottom: 15px;">
    <div style="font-size: 14px; font-weight: 600; color: #1e40af; margin-bottom: 8px; border-bottom: 1px solid #e5e7eb; padding-bottom: 3px;">Project Proposals</div>
    <table style="margin: 5px 0;">
        <thead>
            <tr>
                <th style="width: 20%;">Proposal #</th>
                <th style="width: 30%;">Title</th>
                <th style="width: 15%;">Date</th>
                <th style="text-align: right; width: 20%;">Value</th>
                <th style="text-align: center; width: 15%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($project->proposals as $proposal)
            <tr>
                <td>{{ $proposal->proposal_number }}</td>
                <td>{{ $proposal->title }}</td>
                <td>{{ $proposal->created_at->format('M j, Y') }}</td>
                <td style="text-align: right; font-weight: 600;">{{ \App\Helpers\SettingsHelper::get('currency_symbol', 'KSh') }}{{ number_format($proposal->estimated_value, 2) }}</td>
                <td style="text-align: center;"><span class="status-badge status-{{ strtolower($proposal->status) }}">{{ ucfirst($proposal->status) }}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@if($project->quotations->count() > 0)
<div style="margin-bottom: 15px;">
    <div style="font-size: 14px; font-weight: 600; color: #1e40af; margin-bottom: 8px; border-bottom: 1px solid #e5e7eb; padding-bottom: 3px;">Project Quotations</div>
    <table style="margin: 5px 0;">
        <thead>
            <tr>
                <th style="width: 20%;">Quote #</th>
                <th style="width: 20%;">Date</th>
                <th style="width: 20%;">Valid Until</th>
                <th style="text-align: right; width: 25%;">Amount</th>
                <th style="text-align: center; width: 15%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($project->quotations as $quotation)
            <tr>
                <td>{{ $quotation->quotation_number }}</td>
                <td>{{ $quotation->created_at->format('M j, Y') }}</td>
                <td>{{ $quotation->valid_until->format('M j, Y') }}</td>
                <td style="text-align: right; font-weight: 600;">{{ \App\Helpers\SettingsHelper::get('currency_symbol', 'KSh') }}{{ number_format($quotation->total, 2) }}</td>
                <td style="text-align: center;"><span class="status-badge status-{{ strtolower($quotation->status) }}">{{ ucfirst($quotation->status) }}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<div style="display: flex; justify-content: space-between; gap: 15px;">
    <div style="flex: 1; background: #f0fdf4; padding: 10px; border-radius: 4px; border-left: 3px solid #10b981;">
        <div style="font-size: 12px; font-weight: 600; color: #065f46; margin-bottom: 5px;">Financial Summary</div>
        <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 2px;"><span>Invoiced:</span><span>{{ \App\Helpers\SettingsHelper::get('currency_symbol', 'KSh') }}{{ number_format($project->invoices->sum('total'), 2) }}</span></div>
        <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 2px;"><span>Proposed:</span><span>{{ \App\Helpers\SettingsHelper::get('currency_symbol', 'KSh') }}{{ number_format($project->proposals->sum('estimated_value'), 2) }}</span></div>
        <div style="display: flex; justify-content: space-between; font-weight: 600; font-size: 12px; color: #065f46; border-top: 1px solid #10b981; padding-top: 3px; margin-top: 3px;"><span>Total Value:</span><span>{{ \App\Helpers\SettingsHelper::get('currency_symbol', 'KSh') }}{{ number_format($project->invoices->sum('total') + $project->proposals->sum('estimated_value'), 2) }}</span></div>
    </div>
    <div style="flex: 1; background: #f0f9ff; padding: 10px; border-radius: 4px; border-left: 3px solid #0ea5e9;">
        <div style="font-size: 12px; font-weight: 600; color: #0c4a6e; margin-bottom: 5px;">Project Status</div>
        <div style="color: #164e63; font-size: 11px; line-height: 1.4;">
            This project manual contains all relevant documentation, financial records, and status updates for comprehensive project tracking and management.
        </div>
    </div>
</div>
@endsection