@extends('layouts.client')

@section('title', $project->title . ' - Denip Investments Ltd')
@section('page-title', 'Project Details')

@section('content')
<div class="dashboard-header">
    <h1>Project Details</h1>
    <p>View and manage your project information</p>
</div>

<div class="dashboard-section">
    <div class="section-header">
        <h2>{{ $project->title }}</h2>
        <div style="display: flex; gap: 1rem; flex-shrink: 0;">
            @if($project->status === 'planning')
            <a href="{{ route('client.projects.edit', $project) }}" class="btn btn-outline">
                <i class="fas fa-edit"></i> Edit Project
            </a>
            @endif
            <a href="{{ route('client.projects.index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Back to Projects
            </a>
        </div>
    </div>

<div class="project-details">
    <div class="project-grid">
        <!-- Project Information -->
        <div class="project-section">
            <div class="section-header">
                <h2>Project Information</h2>
                <span class="status status-{{ strtolower($project->status) }}">{{ ucfirst($project->status) }}</span>
            </div>
            
            <div class="project-info">
                <div class="info-item">
                    <label>Description</label>
                    <div>{!! $project->description !!}</div>
                </div>
                
                <div class="info-grid">
                    @if($project->category)
                    <div class="info-item">
                        <label>Category</label>
                        <span style="background: var(--secondary); color: white; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.8rem; font-weight: 600;">{{ $project->category->name }}</span>
                    </div>
                    @endif
                    
                    <div class="info-item">
                        <label>Start Date</label>
                        <span>{{ $project->start_date ? $project->start_date->format('M j, Y') : 'Not set' }}</span>
                    </div>
                    
                    <div class="info-item">
                        <label>End Date</label>
                        <span>{{ $project->end_date ? $project->end_date->format('M j, Y') : 'Not set' }}</span>
                    </div>
                    
                    @if($project->budget)
                    <div class="info-item">
                        <label>Budget</label>
                        <span>{{ \App\Helpers\CurrencyHelper::format($project->budget) }}</span>
                    </div>
                    @endif
                    
                    <div class="info-item">
                        <label>Progress</label>
                        <div class="progress-donut">
                            <svg width="80" height="80" viewBox="0 0 42 42">
                                <circle cx="21" cy="21" r="15.915" fill="transparent" stroke="var(--light)" stroke-width="3"></circle>
                                <circle cx="21" cy="21" r="15.915" fill="transparent" stroke="var(--secondary)" stroke-width="3" 
                                    stroke-dasharray="{{ $project->progress }} {{ 100 - $project->progress }}" 
                                    stroke-dashoffset="25" 
                                    transform="rotate(-90 21 21)">
                                </circle>
                            </svg>
                            <div class="progress-text">{{ $project->progress }}%</div>
                        </div>
                    </div>
                    
                    @if($project->creator)
                    <div class="info-item">
                        <label>Created By</label>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            @if($project->creator->isClient())
                                <span style="padding: 0.2rem 0.5rem; background: #e3f2fd; color: #1976d2; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">
                                    YOU
                                </span>
                                <span style="font-size: 0.9rem;">Self-created project</span>
                            @else
                                <span style="padding: 0.2rem 0.5rem; background: #f3e5f5; color: #7b1fa2; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">
                                    ADMIN
                                </span>
                                <span style="font-size: 0.9rem;">Created by {{ $project->creator->name }}</span>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
                
                @if($project->assigned_users && count($project->assigned_users) > 0)
                <div class="info-item">
                    <label>Assigned Team Members</label>
                    <div class="assigned-users">
                        @foreach($project->assignedUsers() as $user)
                        <div class="user-item">
                            <div class="user-avatar">
                                @if($user->profile_photo)
                                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                                @else
                                    <div class="avatar-placeholder">{{ substr($user->name, 0, 1) }}</div>
                                @endif
                            </div>
                            <div class="team-user-info">
                                <span class="user-name">{{ $user->name }}</span>
                                <span class="user-role">{{ $user->job_title ?? 'Team Member' }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Project Actions -->
        <div class="project-section">
            <div class="section-header">
                <h2>Quick Actions</h2>
            </div>
            
            <div class="action-cards">
                @if($project->status === 'planning')
                <div class="action-card danger" onclick="deleteProject({{ $project->id }})">
                    <i class="fas fa-trash"></i>
                    <h4>Delete Project</h4>
                    <p>Permanently remove this project</p>
                </div>
                @endif
                
                <a href="#invoices" class="action-card" onclick="scrollToSection('invoices')">
                    <i class="fas fa-file-invoice"></i>
                    <h4>View Invoices</h4>
                    <p>{{ $project->invoices->count() }} invoice(s)</p>
                </a>
                
                <a href="#proposals" class="action-card" onclick="scrollToSection('proposals')">
                    <i class="fas fa-handshake"></i>
                    <h4>View Proposals</h4>
                    <p>{{ $project->proposals->count() }} proposal(s)</p>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Project Media -->
    @if($project->media && is_array($project->media) && count($project->media) > 0)
    <div class="project-section">
        <div class="section-header">
            <h2>Project Files & Media</h2>
            <span class="file-count">{{ count($project->media) }} file(s)</span>
        </div>
        
        <div class="media-grid">
            @foreach($project->media as $media)
            <div class="media-item">
                @if(str_starts_with($media['type'], 'image/'))
                    <div class="media-preview">
                        <img src="{{ asset('storage/' . $media['path']) }}" alt="{{ $media['name'] }}">
                    </div>
                @else
                    <div class="media-icon">
                        <i class="fas fa-file"></i>
                    </div>
                @endif
                
                <div class="media-info">
                    <h4>{{ $media['name'] }}</h4>
                    <p>{{ number_format($media['size'] / 1024, 1) }} KB</p>
                    <a href="{{ asset('storage/' . $media['path']) }}" target="_blank" class="btn btn-sm btn-outline">
                        <i class="fas fa-download"></i> Download
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    
    <!-- Project Proposals -->
    @if($project->proposals->count() > 0)
    <div class="project-section" id="proposals">
        <div class="section-header">
            <h2>Project Proposals</h2>
            <span class="file-count">{{ $project->proposals->count() }} proposal(s)</span>
        </div>
        
        <div class="proposals-list">
            @foreach($project->proposals as $proposal)
            <div class="proposal-item">
                <div class="proposal-info">
                    <h4><a href="{{ route('client.proposals.show', $proposal) }}">{{ $proposal->title }}</a></h4>
                    <p>{{ Str::limit($proposal->description, 100) }}</p>
                    <div class="proposal-meta">
                        <span class="status status-{{ strtolower($proposal->status) }}">{{ ucfirst($proposal->status) }}</span>
                        <span class="date">{{ $proposal->created_at->format('M j, Y') }}</span>
                    </div>
                </div>
                <div class="proposal-actions">
                    <a href="{{ route('client.proposals.show', $proposal) }}" class="btn btn-sm btn-outline">
                        <i class="fas fa-eye"></i> View
                    </a>
                    <a href="{{ route('client.proposals.pdf', $proposal) }}" class="btn btn-sm btn-outline">
                        <i class="fas fa-download"></i>
                    </a>
                    @if($proposal->status === 'sent')
                    <button onclick="updateProposalStatus({{ $proposal->id }}, 'accepted')" class="btn btn-sm btn-success">
                        <i class="fas fa-check"></i>
                    </button>
                    <button onclick="updateProposalStatus({{ $proposal->id }}, 'rejected')" class="btn btn-sm btn-danger">
                        <i class="fas fa-times"></i>
                    </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    
    <!-- Project Invoices -->
    @if($project->invoices->count() > 0)
    <div class="project-section" id="invoices">
        <div class="section-header">
            <h2>Project Invoices</h2>
            <span class="file-count">{{ $project->invoices->count() }} invoice(s)</span>
        </div>
        
        <div class="invoices-list">
            @foreach($project->invoices as $invoice)
            <div class="invoice-item">
                <div class="invoice-info">
                    <h4><a href="{{ route('client.invoices.show', $invoice) }}">Invoice #{{ $invoice->invoice_number }}</a></h4>
                    <p>{{ $invoice->description ?? 'No description' }}</p>
                    <div class="invoice-meta">
                        <span class="amount">{{ \App\Helpers\CurrencyHelper::format($invoice->total) }}</span>
                        <span class="status status-{{ strtolower($invoice->status) }}">{{ ucfirst($invoice->status) }}</span>
                        <span class="date">{{ $invoice->created_at->format('M j, Y') }}</span>
                    </div>
                </div>
                <div class="invoice-actions">
                    <a href="{{ route('client.invoices.show', $invoice) }}" class="btn btn-sm btn-outline">
                        <i class="fas fa-eye"></i> View
                    </a>
                    <a href="{{ route('client.invoices.pdf', $invoice) }}" class="btn btn-sm btn-outline">
                        <i class="fas fa-download"></i>
                    </a>
                    @if($invoice->status === 'sent')
                    <button onclick="markInvoicePaid({{ $invoice->id }})" class="btn btn-sm btn-success">
                        <i class="fas fa-check"></i> Mark Paid
                    </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    
    <!-- Similar Projects -->
    @php
    $similarProjects = App\Models\Project::where('category_id', $project->category_id)
        ->where('id', '!=', $project->id)
        ->where('is_public', true)
        ->limit(3)
        ->get();
    @endphp
    @if($similarProjects->count() > 0)
    <div class="project-section">
        <div class="section-header">
            <h2>Similar Projects</h2>
            <span class="file-count">{{ $similarProjects->count() }} project(s)</span>
        </div>
        
        <div class="similar-projects-grid">
            @foreach($similarProjects as $similar)
            <div class="similar-project-card">
                @if($similar->media && count($similar->media) > 0)
                    @php $firstImage = collect($similar->media)->first(fn($m) => str_starts_with($m['type'], 'image/')) @endphp
                    @if($firstImage)
                        <div class="project-image">
                            <img src="{{ asset('storage/' . $firstImage['path']) }}" alt="{{ $similar->title }}">
                        </div>
                    @endif
                @endif
                
                <div class="project-card-content">
                    <h4>{{ $similar->title }}</h4>
                    <p>{!! Str::limit(strip_tags($similar->description), 80) !!}</p>
                    <div class="project-card-meta">
                        <span class="status status-{{ strtolower($similar->status) }}">{{ ucfirst($similar->status) }}</span>
                        <span class="progress">{{ $similar->progress }}% Complete</span>
                    </div>
                    <a href="{{ route('landing.project.show', $similar->slug) }}" target="_blank" class="btn btn-sm btn-outline">
                        <i class="fas fa-external-link-alt"></i> View Project
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
</div>

@push('styles')
<style>


.project-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.project-section {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1), 0 4px 16px rgba(0,0,0,0.05);
    padding: 2rem;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--light);
}

.section-header h2 {
    color: var(--primary);
    font-size: 1.25rem;
    margin: 0;
}

.file-count {
    background: var(--light);
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    color: var(--dark);
}

.info-item {
    margin-bottom: 1.5rem;
}

.info-item label {
    display: block;
    font-weight: 600;
    color: var(--primary);
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.info-item p {
    color: var(--dark);
    line-height: 1.6;
    margin: 0;
}

.info-item span {
    color: var(--dark);
    font-size: 1rem;
}

.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.progress-donut {
    position: relative;
    display: inline-block;
}

.progress-donut svg {
    transform: rotate(-90deg);
}

.progress-donut .progress-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--primary);
}

.action-cards {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.action-card {
    padding: 1.5rem;
    border: 2px solid var(--light);
    border-radius: 12px;
    text-decoration: none;
    color: var(--dark);
    transition: all 0.3s ease;
    cursor: pointer;
}

.action-card:hover {
    border-color: var(--secondary);
    transform: translateY(-2px);
}

.action-card.danger {
    border-color: #fee2e2;
    background: #fef2f2;
}

.action-card.danger:hover {
    border-color: var(--accent);
}

.action-card i {
    font-size: 1.5rem;
    color: var(--secondary);
    margin-bottom: 0.5rem;
}

.action-card.danger i {
    color: var(--accent);
}

.action-card h4 {
    color: var(--primary);
    margin: 0 0 0.25rem 0;
    font-size: 1rem;
}

.action-card p {
    margin: 0;
    font-size: 0.875rem;
    opacity: 0.8;
}

.media-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1.5rem;
}

.media-item {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1), 0 4px 16px rgba(0,0,0,0.05);
    overflow: hidden;
    transition: all 0.3s ease;
}

.media-item:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.media-preview {
    height: 150px;
    overflow: hidden;
}

.media-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.media-icon {
    height: 150px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--light);
    color: var(--primary);
    font-size: 3rem;
}

.media-info {
    padding: 1rem;
}

.media-info h4 {
    margin: 0 0 0.25rem 0;
    font-size: 0.9rem;
    color: var(--primary);
}

.media-info p {
    margin: 0 0 0.75rem 0;
    font-size: 0.8rem;
    color: var(--dark);
    opacity: 0.7;
}

.assigned-users {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.user-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: var(--light);
    border-radius: 8px;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    overflow: hidden;
}

.user-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--primary);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.1rem;
}

.team-user-info {
    display: flex;
    flex-direction: column;
}

.user-name {
    font-weight: 600;
    color: var(--primary);
    font-size: 0.9rem;
}

.user-role {
    font-size: 0.8rem;
    color: var(--dark);
    opacity: 0.7;
}

.proposals-list, .invoices-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.proposal-item, .invoice-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1), 0 4px 16px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.proposal-item:hover, .invoice-item:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.proposal-info h4, .invoice-info h4 {
    margin: 0 0 0.5rem 0;
    color: var(--primary);
    font-size: 1rem;
}

.proposal-info h4 a, .invoice-info h4 a {
    color: var(--primary);
    text-decoration: none;
    transition: color 0.3s ease;
}

.proposal-info h4 a:hover, .invoice-info h4 a:hover {
    color: var(--dark);
}

.proposal-info p, .invoice-info p {
    margin: 0 0 0.75rem 0;
    color: var(--dark);
    opacity: 0.8;
    font-size: 0.9rem;
}

.proposal-meta, .invoice-meta {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.amount {
    font-weight: 600;
    color: var(--success);
    font-size: 1.1rem;
}

.date {
    font-size: 0.8rem;
    color: var(--dark);
    opacity: 0.7;
}

.status {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-draft { background: #f3f4f6; color: #6b7280; }
.status-sent { background: #dbeafe; color: #1d4ed8; }
.status-accepted { background: #d1fae5; color: #065f46; }
.status-rejected { background: #fee2e2; color: #dc2626; }
.status-paid { background: #d1fae5; color: #065f46; }
.status-overdue { background: #fee2e2; color: #dc2626; }
.status-planning { background: #fef3c7; color: #92400e; }
.status-active { background: #dbeafe; color: #1d4ed8; }
.status-completed { background: #d1fae5; color: #065f46; }

.similar-projects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}

.similar-project-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
}

.similar-project-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    border-color: var(--secondary);
}

.similar-project-card .project-image {
    height: 150px;
    overflow: hidden;
}

.similar-project-card .project-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.project-card-content {
    padding: 1.5rem;
}

.project-card-content h4 {
    color: var(--primary);
    margin: 0 0 0.75rem 0;
    font-size: 1rem;
    font-weight: 600;
}

.project-card-content p {
    color: #6b7280;
    margin: 0 0 1rem 0;
    font-size: 0.9rem;
    line-height: 1.5;
}

.project-card-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.progress {
    font-size: 0.8rem;
    color: var(--secondary);
    font-weight: 600;
}

@media (max-width: 768px) {
    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .section-header > div {
        width: 100%;
        justify-content: center;
    }
    
    .project-grid {
        grid-template-columns: 1fr;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .media-grid {
        grid-template-columns: 1fr;
    }
    
    .similar-projects-grid {
        grid-template-columns: 1fr;
    }
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
                    setTimeout(() => window.location.href = '{{ route("client.projects.index") }}', 1000);
                } else {
                    showNotification('Failed to delete project', 'error');
                }
            } catch (error) {
                showNotification('An error occurred', 'error');
            }
        }
    );
}

function scrollToSection(sectionId) {
    const element = document.getElementById(sectionId);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
    }
}

function updateProposalStatus(proposalId, status) {
    const action = status === 'accepted' ? 'accept' : 'reject';
    showConfirmation(
        `${action.charAt(0).toUpperCase() + action.slice(1)} Proposal`,
        `Are you sure you want to ${action} this proposal?`,
        async () => {
            try {
                const response = await fetch(`/client/proposals/${proposalId}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ status })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification(`Proposal ${status} successfully`, 'success');
                    location.reload();
                } else {
                    showNotification('Failed to update proposal', 'error');
                }
            } catch (error) {
                showNotification('An error occurred', 'error');
            }
        }
    );
}

function markInvoicePaid(invoiceId) {
    showConfirmation(
        'Mark Invoice as Paid',
        'Are you sure you want to mark this invoice as paid?',
        async () => {
            try {
                const response = await fetch(`/client/invoices/${invoiceId}/mark-paid`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification('Invoice marked as paid successfully', 'success');
                    location.reload();
                } else {
                    showNotification('Failed to mark invoice as paid', 'error');
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