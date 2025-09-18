@extends('layouts.app')

@section('title', 'Calendar')

@section('content')
<div style="max-width: 1200px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="color: var(--primary-blue); font-size: 2rem; font-weight: 700; margin: 0;">Calendar</h1>
            <p style="color: #6c757d; margin: 0.5rem 0 0 0;">Manage projects and client bookings</p>
        </div>
        <a href="{{ route('admin.calendar.bookings') }}" style="background: var(--primary-blue); color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none;">
            <i class="fas fa-list"></i> View Bookings
        </a>
    </div>
    
    <div class="calendar-legend" style="display: flex; gap: 1.5rem; margin-bottom: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 8px; flex-wrap: wrap;">
        <div class="legend-item" style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem;">
            <span style="width: 16px; height: 16px; border-radius: 50%; background: #007bff; display: inline-block;"></span>
            <span>Project Start</span>
        </div>
        <div class="legend-item" style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem;">
            <span style="width: 16px; height: 16px; border-radius: 50%; background: #6f42c1; display: inline-block;"></span>
            <span>Project End</span>
        </div>
        <div class="legend-item" style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem;">
            <span style="width: 16px; height: 16px; border-radius: 50%; background: #ffc107; display: inline-block;"></span>
            <span>Pending Booking</span>
        </div>
        <div class="legend-item" style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem;">
            <span style="width: 16px; height: 16px; border-radius: 50%; background: #28a745; display: inline-block;"></span>
            <span>Approved Booking</span>
        </div>
        <div class="legend-item" style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem;">
            <span style="width: 16px; height: 16px; border-radius: 50%; background: #dc3545; display: inline-block;"></span>
            <span>Rejected Booking</span>
        </div>
    </div>
    
    <div id="calendar" style="background: white; padding: 1rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"></div>
</div>

<!-- Booking Modal -->
<div id="bookingModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; padding: 2rem; width: 90%; max-width: 500px; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="margin: 0; color: var(--primary-blue);">Manage Booking</h3>
            <button onclick="closeBookingModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #6c757d;">&times;</button>
        </div>
        
        <div id="bookingContent"></div>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
<style>
.fc-toolbar-title {
    color: var(--primary-blue) !important;
    font-weight: 600;
}

.fc-button-primary {
    background: var(--primary-blue) !important;
    border-color: var(--primary-blue) !important;
    font-size: 0.85rem;
    padding: 0.375rem 0.75rem;
}

.fc-button-primary:hover {
    background: var(--deep-blue) !important;
    border-color: var(--deep-blue) !important;
}

.fc-event {
    border-radius: 4px !important;
    font-size: 0.85rem;
    cursor: pointer;
}

.fc-daygrid-event {
    padding: 2px 4px !important;
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
        eventClick: function(info) {
            if (info.event.extendedProps.type === 'booking') {
                showBookingModal(info.event.extendedProps.id);
            } else if (info.event.extendedProps.type === 'project_start' || info.event.extendedProps.type === 'project_end') {
                window.location.href = `/projects/${info.event.extendedProps.id}`;
            }
        },
        height: 'auto',
        eventDisplay: 'block'
    });
    
    calendar.render();
});

async function showBookingModal(bookingId) {
    try {
        const response = await fetch(`/admin/bookings/${bookingId}`);
        const booking = await response.json();
        
        const statusColors = {
            pending: '#ffc107',
            approved: '#28a745',
            rejected: '#dc3545'
        };
        
        document.getElementById('bookingContent').innerHTML = `
            <div style="margin-bottom: 1.5rem; padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                <div style="margin-bottom: 0.5rem;"><strong>Client:</strong> ${booking.client.name}</div>
                <div style="margin-bottom: 0.5rem;"><strong>Date:</strong> ${new Date(booking.date).toLocaleDateString()}</div>
                <div style="margin-bottom: 0.5rem;"><strong>Status:</strong> 
                    <span style="color: ${statusColors[booking.status]}; font-weight: 600; text-transform: uppercase;">${booking.status}</span>
                </div>
                ${booking.notes ? `<div><strong>Notes:</strong> ${booking.notes}</div>` : ''}
            </div>
            
            ${booking.status === 'pending' ? `
                <div style="display: flex; gap: 1rem; margin-bottom: 1rem; flex-wrap: wrap;">
                    <button onclick="updateBookingStatus(${bookingId}, 'approved')" style="background: #28a745; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 6px; cursor: pointer; flex: 1; min-width: 120px;">
                        <i class="fas fa-check"></i> Approve
                    </button>
                    <button onclick="updateBookingStatus(${bookingId}, 'rejected')" style="background: #dc3545; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 6px; cursor: pointer; flex: 1; min-width: 120px;">
                        <i class="fas fa-times"></i> Reject
                    </button>
                    <button onclick="showRescheduleForm(${bookingId})" style="background: #ffc107; color: #212529; border: none; padding: 0.75rem 1.5rem; border-radius: 6px; cursor: pointer; flex: 1; min-width: 120px;">
                        <i class="fas fa-calendar"></i> Reschedule
                    </button>
                </div>
            ` : `
                <div style="text-align: center; padding: 1rem; color: #6c757d;">
                    This booking has already been ${booking.status}.
                </div>
            `}
            
            <div id="rescheduleForm" style="display: none;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">New Date:</label>
                <input type="date" id="newDate" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 1rem;">
                <button onclick="rescheduleBooking(${bookingId})" style="background: var(--primary-blue); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 6px; cursor: pointer; width: 100%;">
                    <i class="fas fa-check"></i> Confirm Reschedule
                </button>
            </div>
        `;
        
        document.getElementById('bookingModal').style.display = 'flex';
    } catch (error) {
        showNotification('Failed to load booking details', 'error');
    }
}

function updateBookingStatus(bookingId, status) {
    const action = status === 'approved' ? 'approve' : 'reject';
    showConfirmation(
        `${action.charAt(0).toUpperCase() + action.slice(1)} Booking`,
        `Are you sure you want to ${action} this booking?`,
        async () => {
            try {
                const response = await fetch(`/admin/bookings/${bookingId}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ status })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    closeBookingModal();
                    showNotification(`Booking ${status} successfully`, 'success');
                    location.reload();
                } else {
                    showNotification('Failed to update booking', 'error');
                }
            } catch (error) {
                showNotification('An error occurred', 'error');
            }
        }
    );
}

function showRescheduleForm(bookingId) {
    document.getElementById('rescheduleForm').style.display = 'block';
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('newDate').min = today;
}

function rescheduleBooking(bookingId) {
    const newDate = document.getElementById('newDate').value;
    
    if (!newDate) {
        showNotification('Please select a date', 'error');
        return;
    }
    
    showConfirmation(
        'Reschedule Booking',
        `Are you sure you want to reschedule this booking to ${new Date(newDate).toLocaleDateString()}?`,
        async () => {
            try {
                const response = await fetch(`/admin/bookings/${bookingId}/reschedule`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ date: newDate })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    closeBookingModal();
                    showNotification('Booking rescheduled successfully', 'success');
                    location.reload();
                } else {
                    showNotification('Failed to reschedule booking', 'error');
                }
            } catch (error) {
                showNotification('An error occurred', 'error');
            }
        }
    );
}

function closeBookingModal() {
    document.getElementById('bookingModal').style.display = 'none';
}
</script>
@endpush
@endsection