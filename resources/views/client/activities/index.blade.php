@extends('layouts.client')

@section('title', 'Activity Log - Denip Investments Ltd')
@section('page-title', 'Activity Log')

@section('content')
<div class="dashboard-header">
    <h1>Activity Log</h1>
    <p>Track all activities related to your account</p>
</div>

<div class="dashboard-section">
    <div class="section-header">
        <h2>Recent Activities</h2>
    </div>
    
    @forelse($activities as $activity)
    <div class="activity-item">
        <div class="activity-icon">
            @if($activity->action === 'created')
                <i class="fas fa-plus-circle" style="color: var(--success);"></i>
            @elseif($activity->action === 'updated')
                <i class="fas fa-edit" style="color: var(--secondary);"></i>
            @elseif($activity->action === 'deleted')
                <i class="fas fa-trash" style="color: var(--accent);"></i>
            @else
                <i class="fas fa-info-circle" style="color: var(--primary);"></i>
            @endif
        </div>
        
        <div class="activity-content">
            <div class="activity-description">
                <strong>{{ $activity->user->name }}</strong> {{ $activity->description }}
            </div>
            <div class="activity-meta">
                <span class="activity-time">{{ $activity->created_at->diffForHumans() }}</span>
                @if($activity->model_type)
                    <span class="activity-type">{{ class_basename($activity->model_type) }}</span>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="empty-state">
        <i class="fas fa-history"></i>
        <h3>No activities yet</h3>
        <p>Your account activities will appear here</p>
    </div>
    @endforelse
    
    <div class="pagination-wrapper">
        <x-pagination :paginator="$activities" />
    </div>
</div>

@push('styles')
<style>
.activity-item {
    display: flex;
    gap: 1rem;
    padding: 1.5rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 1rem;
    transition: transform 0.3s ease;
}

.activity-item:hover {
    transform: translateY(-2px);
}

.activity-icon {
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--light);
    border-radius: 50%;
    font-size: 1.2rem;
}

.activity-content {
    flex: 1;
}

.activity-description {
    color: var(--dark);
    margin-bottom: 0.5rem;
    line-height: 1.5;
}

.activity-meta {
    display: flex;
    gap: 1rem;
    font-size: 0.85rem;
    color: var(--dark);
    opacity: 0.7;
}

.activity-type {
    background: var(--light);
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.75rem;
}

.pagination-wrapper {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
}

.custom-pagination {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.pagination-info {
    font-size: 0.9rem;
    color: var(--dark);
    opacity: 0.8;
}

.pagination-controls {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.pagination-btn {
    padding: 0.5rem 1rem;
    background: var(--primary);
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.3s ease;
    min-width: 80px;
    text-align: center;
}

.pagination-btn:hover {
    background: var(--dark);
    color: white;
}

.pagination-btn.disabled {
    background: #e9ecef;
    color: #6c757d;
    cursor: not-allowed;
}

.pagination-numbers {
    display: flex;
    gap: 0.25rem;
    align-items: center;
}

.pagination-number {
    padding: 0.5rem 0.75rem;
    background: transparent;
    color: var(--dark);
    text-decoration: none;
    border-radius: 4px;
    transition: all 0.3s ease;
    min-width: 40px;
    text-align: center;
}

.pagination-number:hover {
    background: var(--light);
    color: var(--primary);
}

.pagination-number.active {
    background: var(--primary);
    color: white;
    font-weight: 600;
}

.pagination-dots {
    padding: 0.5rem;
    color: #6c757d;
}

@media (max-width: 768px) {
    .custom-pagination {
        padding: 1rem;
    }
    
    .pagination-controls {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .pagination-btn {
        min-width: 120px;
    }
}
</style>
@endpush
@endsection