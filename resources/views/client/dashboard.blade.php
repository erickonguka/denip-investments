@extends('layouts.client')

@section('title', 'Dashboard - Denip Investments Ltd')
@section('page-title', 'Dashboard')

@section('content')
<div class="dashboard-header">
    <h1>Welcome back, {{ auth()->user()->first_name }}!</h1>
    <p>Here's what's happening with your projects today</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-project-diagram"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['projects'] }}</h3>
            <p>Active Projects</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['bookings'] }}</h3>
            <p>Pending Bookings</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-file-invoice"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['invoices'] }}</h3>
            <p>Recent Invoices</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-comments"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['messages'] }}</h3>
            <p>Unread Messages</p>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    <div class="dashboard-section">
        <div class="section-header">
            <h2>Recent Projects</h2>
            <a href="{{ route('client.projects.index') }}" class="btn btn-outline">View All</a>
        </div>
        <div class="projects-list">
            @forelse($recentProjects as $project)
            <div class="project-item">
                <div class="project-info">
                    <h4><a href="{{ route('client.projects.show', $project) }}">{{ $project->title }}</a></h4>
                    <p>{!! Str::limit(strip_tags($project->description), 100) !!}</p>
                    <span class="status status-{{ strtolower($project->status) }}">{{ ucfirst($project->status) }}</span>
                </div>
                <div class="project-actions">
                    <a href="{{ route('client.projects.show', $project) }}" class="btn btn-sm btn-primary">View</a>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-project-diagram"></i>
                <h3>No projects yet</h3>
                <p>Create your first project to get started</p>
                <a href="{{ route('client.projects.create') }}" class="btn btn-primary">Create Project</a>
            </div>
            @endforelse
        </div>
    </div>

    <div class="dashboard-section">
        <div class="section-header">
            <h2>Quick Actions</h2>
        </div>
        <div class="quick-actions">
            <a href="{{ route('client.calendar.index') }}" class="action-card">
                <i class="fas fa-calendar"></i>
                <h4>Book Appointment</h4>
                <p>Schedule a meeting</p>
            </a>
            <a href="{{ route('client.invoices.index') }}" class="action-card">
                <i class="fas fa-file-invoice"></i>
                <h4>View Invoices</h4>
                <p>Check your billing history</p>
            </a>
            <a href="{{ route('client.messages.create') }}" class="action-card">
                <i class="fas fa-comments"></i>
                <h4>Send Message</h4>
                <p>Contact our team</p>
            </a>
        </div>
    </div>
</div>

<div class="dashboard-section" style="margin-top: 2rem;">
    <div class="section-header">
        <h2>Recent Activity</h2>
        <a href="{{ route('client.activities.index') }}" class="btn btn-outline">View All</a>
    </div>
    <div class="activity-feed">
        @forelse($recentActivities as $activity)
        <div class="activity-item">
            <div class="activity-icon">
                @if($activity->action === 'created')
                    <i class="fas fa-plus-circle"></i>
                @elseif($activity->action === 'updated')
                    <i class="fas fa-edit"></i>
                @elseif($activity->action === 'deleted')
                    <i class="fas fa-trash"></i>
                @else
                    <i class="fas fa-info-circle"></i>
                @endif
            </div>
            <div class="activity-content">
                <p><strong>{{ $activity->user->name }}:</strong> {{ $activity->description }}</p>
                <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-history"></i>
            <h3>No recent activity</h3>
            <p>Your account activities will appear here</p>
        </div>
        @endforelse
    </div>
</div>

@push('styles')
<style>
.activity-feed {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid var(--secondary);
}

.activity-icon {
    width: 40px;
    height: 40px;
    background: var(--secondary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}

.activity-content {
    flex: 1;
}

.activity-content p {
    margin: 0 0 0.25rem 0;
    color: var(--dark);
}

.project-info h4 a {
    color: var(--primary);
    text-decoration: none;
    transition: color 0.3s ease;
}

.project-info h4 a:hover {
    color: var(--dark);
}

.text-muted {
    color: #6c757d;
    font-size: 0.875rem;
}
</style>
@endpush
@endsection