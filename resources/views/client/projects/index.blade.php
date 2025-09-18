@extends('layouts.client')

@section('title', 'Projects - Denip Investments Ltd')
@section('page-title', 'My Projects')

@section('content')
<div class="dashboard-header">
    <h1>My Projects</h1>
    <p>Track your construction projects and their progress</p>
</div>

<div class="dashboard-section">
    <div class="section-header">
        <h2>All Projects</h2>
        <a href="{{ route('client.projects.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            New Project
        </a>
    </div>
    
    <div class="projects-grid">
        @forelse($projects as $project)
        <div class="project-document">
            <div class="document-header">
                <div class="document-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="document-corner"></div>
            </div>
            
            <div class="document-content">
                <h3>{{ $project->title }}</h3>
                
                <div class="document-meta">
                    @if($project->category)
                    <div class="meta-row">
                        <i class="fas fa-tag"></i>
                        <span style="background: var(--secondary); color: white; padding: 0.2rem 0.5rem; border-radius: 8px; font-size: 0.7rem; font-weight: 600;">{{ $project->category->name }}</span>
                    </div>
                    @endif
                    <div class="meta-row">
                        <i class="fas fa-calendar"></i>
                        <span>{{ $project->start_date ? $project->start_date->format('M j, Y') : 'Not set' }}</span>
                    </div>
                    @if($project->budget)
                    <div class="meta-row">
                        <i class="fas fa-coins"></i>
                        <span>{{ \App\Helpers\CurrencyHelper::format($project->budget) }}</span>
                    </div>
                    @endif
                    <div class="meta-row">
                        <span class="status status-{{ strtolower($project->status) }}">{{ ucfirst($project->status) }}</span>
                    </div>
                    <div class="meta-row">
                        <i class="fas fa-chart-line"></i>
                        <span>{{ $project->progress }}% Complete</span>
                    </div>
                </div>
                
                <div class="progress-bar">
                    <div class="progress-fill" style="width: {{ $project->progress }}%"></div>
                </div>
                
                @if($project->media && count($project->media) > 0)
                <div class="document-attachments">
                    <i class="fas fa-paperclip"></i>
                    <span>{{ count($project->media) }} attachments</span>
                </div>
                @endif
            </div>
            
            <div class="document-actions">
                <a href="{{ route('client.projects.show', $project) }}" class="action-btn primary">
                    <i class="fas fa-eye"></i>
                </a>
                @if($project->status === 'planning')
                <a href="{{ route('client.projects.edit', $project) }}" class="action-btn secondary">
                    <i class="fas fa-edit"></i>
                </a>
                <button onclick="deleteProject({{ $project->id }})" class="action-btn danger">
                    <i class="fas fa-trash"></i>
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-project-diagram"></i>
            <h3>No projects yet</h3>
            <p>Start your first construction project with us</p>
            <a href="{{ route('client.projects.create') }}" class="btn btn-primary">Create Project</a>
        </div>
        @endforelse
    </div>
    
    <div class="pagination-wrapper">
        <x-pagination :paginator="$projects" />
    </div>
</div>

@push('styles')
<style>
.projects-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
}

.project-document {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1), 0 4px 16px rgba(0,0,0,0.05);
    position: relative;
    transition: all 0.3s ease;
    overflow: hidden;
}

.project-document:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.document-header {
    position: relative;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    padding: 1rem;
    border-bottom: 1px solid #dee2e6;
}

.document-icon {
    color: var(--primary);
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.document-corner {
    position: absolute;
    top: 0;
    right: 0;
    width: 0;
    height: 0;
    border-left: 20px solid transparent;
    border-top: 20px solid #dee2e6;
}

.document-corner::after {
    content: '';
    position: absolute;
    top: -18px;
    right: -18px;
    width: 0;
    height: 0;
    border-left: 16px solid transparent;
    border-top: 16px solid white;
}

.document-content {
    padding: 1.5rem;
}

.document-content h3 {
    margin: 0 0 1rem 0;
    color: var(--primary);
    font-size: 1.1rem;
    font-weight: 600;
}

.document-content p {
    color: var(--dark);
    font-size: 0.9rem;
    line-height: 1.5;
    margin-bottom: 1rem;
}

.document-meta {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.meta-row {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: var(--dark);
}

.meta-row i {
    width: 16px;
    color: var(--secondary);
}

.document-attachments {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem;
    background: #f8f9fa;
    border-radius: 4px;
    font-size: 0.8rem;
    color: var(--dark);
    margin-top: 1rem;
}

.document-attachments i {
    color: var(--secondary);
}

.document-actions {
    position: absolute;
    top: 1rem;
    right: 1rem;
    display: flex;
    gap: 0.25rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.project-document:hover .document-actions {
    opacity: 1;
}

.action-btn {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.8rem;
    text-decoration: none;
}

.action-btn.primary {
    background: var(--primary);
    color: white;
}

.action-btn.secondary {
    background: var(--secondary);
    color: white;
}

.action-btn.danger {
    background: var(--accent);
    color: white;
}

.action-btn:hover {
    transform: scale(1.1);
}

.status {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-planning { background: #fff3cd; color: #856404; }
.status-active { background: #d1ecf1; color: #0c5460; }
.status-completed { background: #d4edda; color: #155724; }

.progress-bar {
    width: 100%;
    height: 8px;
    background: #e9ecef;
    border-radius: 4px;
    overflow: hidden;
    margin: 1rem 0;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--secondary), #e67e22);
    border-radius: 4px;
    transition: width 0.3s ease;
}

.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 3rem 1rem;
    color: #6c757d;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

@media (max-width: 1024px) {
    .projects-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }
}

@media (max-width: 768px) {
    .projects-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .document-actions {
        opacity: 1;
        position: static;
        justify-content: center;
        margin-top: 1rem;
        padding: 0 1.5rem 1.5rem;
    }
}

.pagination-wrapper {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
}
</style>
@endpush

@push('scripts')
<script>
function deleteProject(projectId) {
    showConfirmation(
        'Delete Project',
        'Are you sure you want to delete this project? This action cannot be undone.',
        async () => {
            try {
                const response = await fetch(`/client/projects/${projectId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification('Project deleted successfully', 'success');
                    location.reload();
                } else {
                    showNotification('Failed to delete project', 'error');
                }
            } catch (error) {
                showNotification('An error occurred', 'error');
            }
        }
    );
}
</script>
@endpush
@endsection