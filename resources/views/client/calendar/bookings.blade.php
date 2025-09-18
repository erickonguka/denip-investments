@extends('layouts.client')

@section('title', 'My Bookings - Denip Investments Ltd')
@section('page-title', 'My Bookings')

@section('content')
<div class="dashboard-header">
    <h1>My Bookings</h1>
    <p>View and manage your appointment requests</p>
</div>

<div class="dashboard-section">
    <div class="section-header">
        <h2>Booking Requests</h2>
        <a href="{{ route('client.calendar.index') }}" class="btn btn-outline">
            <i class="fas fa-calendar"></i> Calendar View
        </a>
    </div>
    
    @forelse($bookings as $booking)
    <div class="booking-card">
        <div class="booking-date">
            <div class="date-day">{{ $booking->date->format('d') }}</div>
            <div class="date-month">{{ $booking->date->format('M Y') }}</div>
        </div>
        
        <div class="booking-info">
            <h4>Appointment Request</h4>
            <p>{{ $booking->date->format('l, F j, Y') }}</p>
            @if($booking->notes)
                <p class="booking-notes">{{ $booking->notes }}</p>
            @endif
        </div>
        
        <div class="booking-status">
            <span class="status status-{{ $booking->status }}">
                @if($booking->status === 'pending')
                    <i class="fas fa-clock"></i> Pending
                @elseif($booking->status === 'approved')
                    <i class="fas fa-check-circle"></i> Approved
                @else
                    <i class="fas fa-times-circle"></i> Rejected
                @endif
            </span>
            <small class="booking-time">{{ $booking->created_at->diffForHumans() }}</small>
        </div>
    </div>
    @empty
    <div class="empty-state">
        <i class="fas fa-calendar-check"></i>
        <h3>No bookings yet</h3>
        <p>Your appointment requests will appear here</p>
        <a href="{{ route('client.calendar.index') }}" class="btn btn-primary">
            <i class="fas fa-calendar-plus"></i> Book Appointment
        </a>
    </div>
    @endforelse
    
    <div class="pagination-wrapper">
        <x-pagination :paginator="$bookings" />
    </div>
</div>

@push('styles')
<style>
.booking-card {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 1.5rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 1rem;
    transition: transform 0.3s ease;
}

.booking-card:hover {
    transform: translateY(-2px);
}

.booking-date {
    flex-shrink: 0;
    text-align: center;
    padding: 1rem;
    background: linear-gradient(135deg, var(--primary), var(--dark));
    color: white;
    border-radius: 8px;
    min-width: 80px;
}

.date-day {
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1;
}

.date-month {
    font-size: 0.8rem;
    opacity: 0.9;
    margin-top: 0.25rem;
}

.booking-info {
    flex: 1;
}

.booking-info h4 {
    margin: 0 0 0.5rem 0;
    color: var(--primary);
    font-size: 1.1rem;
}

.booking-info p {
    margin: 0;
    color: var(--dark);
    font-size: 0.9rem;
}

.booking-notes {
    margin-top: 0.5rem !important;
    font-style: italic;
    opacity: 0.8;
}

.booking-status {
    flex-shrink: 0;
    text-align: right;
}

.status {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-approved {
    background: #d1fae5;
    color: #065f46;
}

.status-rejected {
    background: #fee2e2;
    color: #dc2626;
}

.booking-time {
    display: block;
    color: var(--dark);
    opacity: 0.7;
    font-size: 0.8rem;
}

.pagination-wrapper {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
}

@media (max-width: 768px) {
    .booking-card {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .booking-status {
        text-align: center;
    }
}
</style>
@endpush
@endsection