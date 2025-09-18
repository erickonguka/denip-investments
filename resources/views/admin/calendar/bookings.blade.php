@extends('layouts.app')

@section('title', 'Bookings')

@section('content')
<div style="max-width: 1200px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="color: var(--primary-blue); font-size: 2rem; font-weight: 700; margin: 0;">Client Bookings</h1>
            <p style="color: #6c757d; margin: 0.5rem 0 0 0;">Manage client appointment requests</p>
        </div>
        <a href="{{ route('admin.calendar.index') }}" style="background: #6c757d; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none;">
            <i class="fas fa-calendar"></i> Calendar View
        </a>
    </div>
    
    @forelse($bookings as $booking)
    <div style="display: flex; align-items: center; gap: 1.5rem; padding: 1.5rem; background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 1rem; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
        <div style="flex-shrink: 0; text-align: center; padding: 1rem; background: linear-gradient(135deg, var(--primary-blue), var(--deep-blue)); color: white; border-radius: 8px; min-width: 80px;">
            <div style="font-size: 1.5rem; font-weight: 700; line-height: 1;">{{ $booking->date->format('d') }}</div>
            <div style="font-size: 0.8rem; opacity: 0.9; margin-top: 0.25rem;">{{ $booking->date->format('M Y') }}</div>
        </div>
        
        <div style="flex: 1;">
            <h4 style="margin: 0 0 0.5rem 0; color: var(--primary-blue); font-size: 1.1rem;">{{ $booking->client->name }}</h4>
            <p style="margin: 0; color: #6c757d; font-size: 0.9rem;">{{ $booking->date->format('l, F j, Y') }}</p>
            @if($booking->notes)
                <p style="margin: 0.5rem 0 0 0; font-style: italic; opacity: 0.8; color: #6c757d;">{{ $booking->notes }}</p>
            @endif
        </div>
        
        <div style="flex-shrink: 0; text-align: right;">
            <span style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.5rem; 
                background: {{ $booking->status === 'pending' ? '#fff3cd' : ($booking->status === 'approved' ? '#d1fae5' : '#fee2e2') }};
                color: {{ $booking->status === 'pending' ? '#856404' : ($booking->status === 'approved' ? '#065f46' : '#dc2626') }};">
                @if($booking->status === 'pending')
                    <i class="fas fa-clock"></i> Pending
                @elseif($booking->status === 'approved')
                    <i class="fas fa-check-circle"></i> Approved
                @else
                    <i class="fas fa-times-circle"></i> Rejected
                @endif
            </span>
            <div style="display: block; color: #6c757d; opacity: 0.7; font-size: 0.8rem;">{{ $booking->created_at->diffForHumans() }}</div>
        </div>
        
        <div style="flex-shrink: 0;">
            <button onclick="showBookingModal({{ $booking->id }})" style="background: var(--primary-blue); color: white; border: none; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer;">
                <i class="fas fa-cog"></i> Manage
            </button>
        </div>
    </div>
    @empty
    <div style="text-align: center; padding: 3rem; color: #6c757d;">
        <i class="fas fa-calendar-check" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
        <h3>No bookings yet</h3>
        <p>Client appointment requests will appear here</p>
    </div>
    @endforelse
    
    <div class="pagination-wrapper">
        <x-pagination :paginator="$bookings" />
    </div>
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
<style>
.pagination-wrapper {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
}
</style>
@endpush

@push('scripts')
<script>
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