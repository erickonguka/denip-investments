@extends('layouts.client')

@section('title', 'Calendar - Denip Investments Ltd')
@section('page-title', 'Calendar')

@section('content')
<div class="dashboard-header">
    <h1>Project Calendar</h1>
    <p>View your project timelines and important dates</p>
</div>

<div class="dashboard-section">
    <div class="section-header">
        <h2>Project Schedule</h2>
        <a href="{{ route('client.calendar.bookings') }}" class="btn btn-outline">
            <i class="fas fa-list"></i> My Bookings
        </a>
    </div>
    
    <div class="calendar-legend">
        <div class="legend-item">
            <span class="legend-color" style="background: #28a745;"></span>
            <span>Project Start / Approved Booking</span>
        </div>
        <div class="legend-item">
            <span class="legend-color" style="background: #dc3545;"></span>
            <span>Project End / Rejected Booking</span>
        </div>
        <div class="legend-item">
            <span class="legend-color" style="background: #ffc107;"></span>
            <span>Pending Booking</span>
        </div>
    </div>
    
    <div id="calendar"></div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
<style>
#calendar {
    background: white;
    padding: 1rem;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.fc-toolbar {
    flex-wrap: wrap;
    gap: 0.5rem;
}

.fc-toolbar-title {
    color: var(--primary) !important;
    font-weight: 600;
}

.fc-button-primary {
    background: var(--primary) !important;
    border-color: var(--primary) !important;
    font-size: 0.85rem;
    padding: 0.375rem 0.75rem;
}

.fc-button-primary:hover {
    background: var(--dark) !important;
    border-color: var(--dark) !important;
}

.fc-event {
    border-radius: 4px !important;
    font-size: 0.85rem;
}

.fc-daygrid-event {
    padding: 2px 4px !important;
}

.fc-daygrid-day:hover {
    background: rgba(243, 156, 18, 0.1);
    cursor: pointer;
}

.calendar-legend {
    display: flex;
    gap: 1.5rem;
    margin-bottom: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    flex-wrap: wrap;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
}

.legend-color {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    display: inline-block;
}

@media (max-width: 768px) {
    .fc-toolbar {
        flex-direction: column;
        align-items: center;
    }
    
    .fc-toolbar-chunk {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.25rem;
    }
    
    .fc-button {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    
    .fc-toolbar-title {
        font-size: 1.25rem;
        margin: 0.5rem 0;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const events = @json($events);
    
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,dayGridWeek'
        },
        events: events,
        dateClick: function(info) {
            const selectedDate = info.dateStr;
            const today = new Date().toISOString().split('T')[0];
            
            if (selectedDate < today) {
                showNotification('Cannot book dates in the past', 'error');
                return;
            }
            
            showBookingModal(selectedDate);
        },
        eventClick: function(info) {
            showNotification(info.event.title, 'info');
        },
        height: 'auto',
        eventDisplay: 'block'
    });
    
    calendar.render();
});

function showBookingModal(date) {
    const formattedDate = new Date(date).toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    
    showConfirmation(
        'Book Appointment',
        `Would you like to request an appointment for ${formattedDate}?`,
        () => bookAppointment(date)
    );
}

async function bookAppointment(date) {
    try {
        const response = await fetch('/client/calendar/book', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ date: date })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Appointment request sent for admin approval', 'success');
        } else {
            showNotification(result.message || 'Failed to book appointment', 'error');
        }
    } catch (error) {
        showNotification('An error occurred while booking', 'error');
    }
}
</script>
@endpush
@endsection