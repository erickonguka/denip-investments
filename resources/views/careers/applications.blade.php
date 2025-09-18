@extends('layouts.app')

@section('title', 'Applications - ' . $career->title)

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 class="page-title">Applications for {{ $career->title }}</h1>
        <p class="page-subtitle">{{ $career->location }} • {{ ucfirst(str_replace('-', ' ', $career->type)) }}</p>
    </div>
    <a href="{{ route('careers.index') }}" class="btn" style="background: var(--gray-300); color: var(--gray-700);">
        <i class="fas fa-arrow-left"></i> Back to Careers
    </a>
</div>

<x-data-table 
    title="All Applications ({{ $applications->total() }})" 
    :headers="['Applicant', 'Contact', 'Applied', 'Status', 'Resume']"
    searchPlaceholder="Search applicants..."
    :pagination="$applications">
    
    @forelse($applications as $application)
    <tr style="border-bottom: 1px solid var(--gray-200);">
        <td style="padding: 1rem;">
            <div>
                <div style="font-weight: 600; color: var(--primary-blue);">{{ $application->name }}</div>
                @if($application->cover_letter)
                    <div style="font-size: 0.8rem; color: var(--gray-500); margin-top: 0.25rem;">
                        {{ Str::limit($application->cover_letter, 80) }}
                    </div>
                @endif
            </div>
        </td>
        <td style="padding: 1rem;">
            <div style="font-size: 0.9rem;">
                <div><i class="fas fa-envelope" style="margin-right: 0.5rem; color: var(--gray-400);"></i>{{ $application->email }}</div>
                @if($application->phone)
                    <div style="margin-top: 0.25rem;"><i class="fas fa-phone" style="margin-right: 0.5rem; color: var(--gray-400);"></i>{{ $application->phone }}</div>
                @endif
            </div>
        </td>
        <td style="padding: 1rem;">{{ $application->created_at->format('M j, Y') }}</td>
        <td style="padding: 1rem;">
            <select onchange="updateStatus({{ $application->id }}, this.value)" style="padding: 0.25rem 0.5rem; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 0.8rem; 
                background: {{ $application->status === 'hired' ? '#dcfce7' : ($application->status === 'shortlisted' ? '#fef3c7' : ($application->status === 'reviewing' ? '#dbeafe' : ($application->status === 'rejected' ? '#fef2f2' : '#f9fafb'))) }}; 
                color: {{ $application->status === 'hired' ? '#16a34a' : ($application->status === 'shortlisted' ? '#d97706' : ($application->status === 'reviewing' ? '#2563eb' : ($application->status === 'rejected' ? '#dc2626' : '#6b7280'))) }};">
                <option value="pending" {{ $application->status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="reviewing" {{ $application->status === 'reviewing' ? 'selected' : '' }}>Reviewing</option>
                <option value="shortlisted" {{ $application->status === 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                <option value="rejected" {{ $application->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="hired" {{ $application->status === 'hired' ? 'selected' : '' }}>Hired</option>
            </select>
        </td>
        <td style="padding: 1rem;">
            @if($application->resume_path)
                <a href="{{ asset('storage/' . $application->resume_path) }}" target="_blank" class="btn" style="background: var(--primary-blue); color: white; padding: 0.5rem; font-size: 0.8rem;">
                    <i class="fas fa-file-pdf"></i> View Resume
                </a>
            @else
                <span style="color: var(--gray-500); font-style: italic;">No resume</span>
            @endif
        </td>
        <td style="padding: 1rem;">
            <button class="btn" style="background: var(--secondary); color: white; padding: 0.5rem;" onclick="viewApplication({{ $application->id }})">
                <i class="fas fa-eye"></i>
            </button>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="6" style="padding: 2rem; text-align: center; color: var(--gray-600);">No applications received yet</td>
    </tr>
    @endforelse
</x-data-table>

<x-modal id="application-modal" title="Application Details">
    <div id="application-content">
        <!-- Content will be loaded here -->
    </div>
</x-modal>
@endsection

@push('scripts')
<script>
function updateStatus(applicationId, status) {
    fetch(`/admin/career-applications/${applicationId}/status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            showNotification(response.message, 'success');
            // Update the select background color
            const select = event.target;
            const colors = {
                'pending': { bg: '#f9fafb', color: '#6b7280' },
                'reviewing': { bg: '#dbeafe', color: '#2563eb' },
                'shortlisted': { bg: '#fef3c7', color: '#d97706' },
                'rejected': { bg: '#fef2f2', color: '#dc2626' },
                'hired': { bg: '#dcfce7', color: '#16a34a' }
            };
            select.style.background = colors[status].bg;
            select.style.color = colors[status].color;
        } else {
            showNotification('Failed to update status', 'error');
        }
    })
    .catch(error => {
        showNotification('An error occurred', 'error');
    });
}

function viewApplication(applicationId) {
    fetch(`/admin/career-applications/${applicationId}`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            const app = response.data;
            document.getElementById('application-content').innerHTML = `
                <div style="margin-bottom: 1.5rem;">
                    <h4 style="color: var(--primary-blue); margin-bottom: 0.5rem;">${app.name}</h4>
                    <div style="color: var(--gray-600); font-size: 0.9rem;">
                        <div><i class="fas fa-envelope" style="margin-right: 0.5rem;"></i>${app.email}</div>
                        ${app.phone ? `<div style="margin-top: 0.25rem;"><i class="fas fa-phone" style="margin-right: 0.5rem;"></i>${app.phone}</div>` : ''}
                        <div style="margin-top: 0.25rem;"><i class="fas fa-calendar" style="margin-right: 0.5rem;"></i>Applied on ${new Date(app.created_at).toLocaleDateString()}</div>
                    </div>
                </div>
                
                ${app.cover_letter ? `
                <div style="margin-bottom: 1.5rem;">
                    <h5 style="color: var(--deep-blue); margin-bottom: 0.5rem;">Cover Letter</h5>
                    <div style="background: #f8f9fa; padding: 1rem; border-radius: 8px; white-space: pre-wrap; line-height: 1.6;">${app.cover_letter}</div>
                </div>
                ` : ''}
                
                ${app.resume_path ? `
                <div style="margin-bottom: 1.5rem;">
                    <h5 style="color: var(--deep-blue); margin-bottom: 0.5rem;">Resume</h5>
                    <a href="/storage/${app.resume_path}" target="_blank" class="btn btn-primary">
                        <i class="fas fa-file-pdf"></i> View Resume
                    </a>
                </div>
                ` : ''}
                
                <div>
                    <h5 style="color: var(--deep-blue); margin-bottom: 0.5rem;">Admin Notes</h5>
                    <textarea id="admin-notes" placeholder="Add notes about this application..." style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 8px; min-height: 100px;">${app.admin_notes || ''}</textarea>
                    <button onclick="saveNotes(${app.id})" class="btn btn-primary" style="margin-top: 0.5rem;">
                        Save Notes
                    </button>
                </div>
            `;
            openModal('application-modal');
        }
    })
    .catch(error => console.error('Error:', error));
}

function saveNotes(applicationId) {
    const notes = document.getElementById('admin-notes').value;
    
    fetch(`/admin/career-applications/${applicationId}/status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ admin_notes: notes })
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            showNotification('Notes saved successfully', 'success');
        } else {
            showNotification('Failed to save notes', 'error');
        }
    })
    .catch(error => {
        showNotification('An error occurred', 'error');
    });
}
</script>
@endpush