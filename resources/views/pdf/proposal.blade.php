@extends('pdf.layout')

@section('title', 'Proposal ' . $proposal->proposal_number)
@section('document_type', 'proposal')
@section('document_id', $proposal->id)

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
    <div class="document-title" style="margin: 0;">BUSINESS PROPOSAL</div>
    <div style="text-align: right;">
        <div style="font-size: 18px; font-weight: 700; color: #1e40af;">{{ $proposal->proposal_number }}</div>
        <div style="font-size: 11px; color: #6b7280;">Valid until: {{ $proposal->valid_until->format('M j, Y') }}</div>
    </div>
</div>

<div style="display: flex; justify-content: space-between; margin-bottom: 15px; gap: 30px;">
    <div style="flex: 1;">
        <div style="font-weight: 600; color: #1e40af; margin-bottom: 5px; font-size: 12px;">PREPARED FOR:</div>
        <div style="font-weight: 600;">{{ $proposal->client->name }}</div>
        @if($proposal->client->company)<div>{{ $proposal->client->company }}</div>@endif
        <div>{{ $proposal->client->email }}</div>
        @if($proposal->client->phone)<div>{{ $proposal->client->phone }}</div>@endif
    </div>
    <div style="flex: 1; text-align: right;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 3px;"><span>Date:</span><span>{{ $proposal->created_at->format('M j, Y') }}</span></div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 3px;"><span>Valid Until:</span><span>{{ $proposal->valid_until->format('M j, Y') }}</span></div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 3px;"><span>Status:</span><span class="status-badge status-{{ strtolower($proposal->status) }}">{{ ucfirst($proposal->status) }}</span></div>
        @if($proposal->project)<div style="display: flex; justify-content: space-between;"><span>Project:</span><span>{{ $proposal->project->title }}</span></div>@endif
    </div>
</div>

<div style="margin-bottom: 15px;">
    <div style="font-size: 16px; font-weight: 600; color: #1e40af; margin-bottom: 8px; border-bottom: 1px solid #e5e7eb; padding-bottom: 3px;">{{ $proposal->title }}</div>
    <div style="background: #f0f9ff; padding: 12px; border-radius: 4px; border-left: 3px solid #0ea5e9;">
        <div style="font-size: 13px; font-weight: 600; color: #0c4a6e; margin-bottom: 5px;">Executive Summary</div>
        <div style="color: #164e63; font-size: 12px; line-height: 1.5;">
            {!! nl2br(e($proposal->description)) !!}
        </div>
    </div>
</div>

<div style="display: flex; justify-content: space-between; margin-bottom: 15px; gap: 20px;">
    <div style="flex: 1;">
        <div style="font-size: 14px; font-weight: 600; color: #1e40af; margin-bottom: 8px;">Investment Summary</div>
        <div style="background: #f0fdf4; padding: 12px; border-radius: 4px; border-left: 3px solid #10b981;">
            <div style="color: #065f46; font-size: 12px; line-height: 1.5;">
                <strong>We are excited about this opportunity.</strong><br>
                This proposal outlines our comprehensive approach to deliver exceptional value for your investment.
            </div>
        </div>
    </div>
    <div style="width: 200px; background: #f8fafc; padding: 12px; border-radius: 4px; text-align: center;">
        <div style="font-size: 12px; color: #6b7280; margin-bottom: 5px;">Total Estimated Value</div>
        <div style="font-size: 20px; font-weight: 700; color: #1e40af;">{{ \App\Helpers\SettingsHelper::get('currency_symbol', 'KSh') }}{{ number_format($proposal->estimated_value, 2) }}</div>
    </div>
</div>

<div style="display: flex; justify-content: space-between; gap: 20px; margin-bottom: 15px;">
    <div style="flex: 1;">
        <div style="font-size: 14px; font-weight: 600; color: #1e40af; margin-bottom: 8px;">Next Steps</div>
        <div style="background: #f0fdf4; padding: 10px; border-radius: 4px; border-left: 3px solid #10b981; font-size: 11px;">
            <div style="color: #065f46;">
                <strong>To proceed:</strong><br>
                1. Review proposal details<br>
                2. Contact us with questions<br>
                3. Sign acceptance form<br>
                4. We'll schedule kick-off meeting
            </div>
        </div>
    </div>
    <div style="flex: 1;">
        <div style="font-size: 14px; font-weight: 600; color: #1e40af; margin-bottom: 8px;">Terms & Conditions</div>
        <div style="background: #f0f9ff; padding: 10px; border-radius: 4px; border-left: 3px solid #0ea5e9; font-size: 11px;">
            <div style="color: #164e63;">
                {{ \App\Helpers\SettingsHelper::get('proposal_footer', 'Valid for 30 days. 30% deposit required to commence work, balance due upon completion.') }}
            </div>
        </div>
    </div>
</div>
@endsection