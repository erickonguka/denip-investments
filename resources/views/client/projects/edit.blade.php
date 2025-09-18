@extends('layouts.client')

@section('title', 'Edit Project - Denip Investments Ltd')
@section('page-title', 'Edit Project')

@section('content')
<div class="dashboard-header">
    <h1>Edit Project</h1>
    <p>Update your project details and requirements</p>
</div>

<div class="dashboard-section">
    <div class="wizard-container">
        <div class="wizard-steps">
            <div class="step active" data-step="1">
                <span class="step-number">1</span>
                <span class="step-title">Basic Info</span>
            </div>
            <div class="step" data-step="2">
                <span class="step-number">2</span>
                <span class="step-title">Timeline & Budget</span>
            </div>
            <div class="step" data-step="3">
                <span class="step-number">3</span>
                <span class="step-title">Documents</span>
            </div>
        </div>
        
        <form id="editProjectForm" onsubmit="handleProjectUpdate(event)">
            @csrf
            @method('PUT')
            
            <!-- Step 1: Basic Info -->
            <div class="wizard-step active" data-step="1">
                <h3>Project Basic Information</h3>
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>Project Title *</label>
                        <input type="text" name="title" class="form-control" placeholder="Enter project title" value="{{ $project->title }}" required>
                    </div>
                    
                    <div class="form-group full-width">
                        <label>Project Category *</label>
                        <select name="category_id" class="form-control" required>
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $project->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group full-width">
                        <label>Project Description *</label>
                        <x-rich-text-editor name="description" id="client-project-edit-description-editor" height="200px" value="{{ $project->description }}" />
                    </div>
                </div>
            </div>
            
            <!-- Step 2: Timeline & Budget -->
            <div class="wizard-step" data-step="2">
                <h3>Timeline & Budget</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" name="start_date" class="form-control" min="{{ date('Y-m-d') }}" value="{{ $project->start_date ? $project->start_date->format('Y-m-d') : '' }}">
                    </div>
                    
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $project->end_date ? $project->end_date->format('Y-m-d') : '' }}">
                    </div>
                    
                    <div class="form-group full-width">
                        <label>Estimated Budget</label>
                        <input type="number" name="budget" class="form-control" step="0.01" placeholder="0.00" value="{{ $project->budget }}">
                    </div>
                </div>
            </div>
            
            <!-- Step 3: Documents -->
            <div class="wizard-step" data-step="3">
                <h3>Project Documents & Media</h3>
                <div class="form-group">
                    <x-upload-dropbox 
                        name="media[]" 
                        accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.doc,.docx" 
                        :multiple="true" 
                        maxSize="10"
                        text="Upload project plans, images, documents, or any relevant files"
                        :existingMedia="$project->media"
                    />
                </div>
            </div>
            
            <div class="wizard-actions">
                <button type="button" class="btn btn-outline" id="prevBtn" onclick="changeStep(-1)" style="display: none;">Previous</button>
                <button type="button" class="btn btn-primary" id="nextBtn" onclick="changeStep(1)">Next</button>
                <button type="submit" class="btn btn-primary" id="submitBtn" style="display: none;">
                    <span class="btn-text">Update Project</span>
                </button>
                <a href="{{ route('client.projects.show', $project) }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
.wizard-container {
    max-width: 800px;
    margin: 0 auto;
}

.wizard-steps {
    display: flex;
    justify-content: center;
    margin-bottom: 2rem;
    position: relative;
}

.wizard-steps::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 25%;
    right: 25%;
    height: 2px;
    background: var(--light);
    z-index: 1;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
    background: white;
    padding: 0 1rem;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--light);
    color: var(--dark);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    margin-bottom: 0.5rem;
    transition: all 0.3s ease;
}

.step.active .step-number {
    background: var(--primary);
    color: white;
}

.step.completed .step-number {
    background: var(--success);
    color: white;
}

.step-title {
    font-size: 0.875rem;
    color: var(--dark);
    text-align: center;
}

.wizard-step {
    display: none;
    animation: fadeIn 0.3s ease;
}

.wizard-step.active {
    display: block;
}

.wizard-step h3 {
    color: var(--primary);
    margin-bottom: 1.5rem;
    text-align: center;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: var(--primary);
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid var(--light);
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: var(--secondary);
}

.wizard-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 2rem;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateX(20px); }
    to { opacity: 1; transform: translateX(0); }
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .wizard-steps {
        flex-direction: column;
        gap: 1rem;
    }
    
    .wizard-steps::before {
        display: none;
    }
}
</style>
@endpush

@push('scripts')
<script>
let currentStep = 1;
const totalSteps = 3;

function changeStep(direction) {
    const newStep = currentStep + direction;
    
    if (newStep < 1 || newStep > totalSteps) return;
    
    // Validate current step before proceeding
    if (direction > 0 && !validateStep(currentStep)) {
        return;
    }
    
    // Hide current step
    document.querySelector(`.wizard-step[data-step="${currentStep}"]`).classList.remove('active');
    document.querySelector(`.step[data-step="${currentStep}"]`).classList.remove('active');
    document.querySelector(`.step[data-step="${currentStep}"]`).classList.add('completed');
    
    // Show new step
    currentStep = newStep;
    document.querySelector(`.wizard-step[data-step="${currentStep}"]`).classList.add('active');
    document.querySelector(`.step[data-step="${currentStep}"]`).classList.add('active');
    
    // Update buttons
    updateButtons();
}

function validateStep(step) {
    const stepElement = document.querySelector(`.wizard-step[data-step="${step}"]`);
    const requiredFields = stepElement.querySelectorAll('[required]');
    
    for (let field of requiredFields) {
        if (!field.value.trim()) {
            field.focus();
            showNotification(`Please fill in all required fields in this step`, 'error');
            return false;
        }
    }
    
    // Validate Quill editor content for step 1
    if (step === 1) {
        if (window.client_project_edit_description_editor) {
            const content = window.client_project_edit_description_editor.root.innerHTML.trim();
            if (!content || content === '<p><br></p>') {
                showNotification('Please provide a project description', 'error');
                return false;
            }
        }
    }
    
    // Additional validation for step 2 (dates)
    if (step === 2) {
        const startDate = document.querySelector('[name="start_date"]').value;
        const endDate = document.querySelector('[name="end_date"]').value;
        
        if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
            showNotification('End date must be after start date', 'error');
            return false;
        }
    }
    
    return true;
}

function updateButtons() {
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    
    prevBtn.style.display = currentStep > 1 ? 'inline-block' : 'none';
    nextBtn.style.display = currentStep < totalSteps ? 'inline-block' : 'none';
    submitBtn.style.display = currentStep === totalSteps ? 'inline-block' : 'none';
}

async function handleProjectUpdate(event) {
    event.preventDefault();
    
    if (!validateStep(currentStep)) return;
    
    const form = event.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('#submitBtn');
    
    setLoading(submitBtn, true, 'Updating...');
    
    try {
        const response = await fetch('{{ route("client.projects.update", $project) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Project updated successfully', 'success');
            if (result.redirect) {
                setTimeout(() => window.location.href = result.redirect, 1000);
            }
        } else {
            if (result.errors) {
                const errorMessages = Object.values(result.errors).flat();
                showNotification(errorMessages.join('<br>'), 'error');
            } else {
                showNotification(result.message || 'Failed to update project', 'error');
            }
        }
    } catch (error) {
        showNotification('An error occurred', 'error');
    } finally {
        setLoading(submitBtn, false, 'Update Project');
    }
}

function setLoading(button, loading, text) {
    const btnText = button.querySelector('.btn-text');
    if (loading) {
        button.disabled = true;
        btnText.innerHTML = `<div class="spinner"></div> ${text}`;
    } else {
        button.disabled = false;
        btnText.textContent = text;
    }
}

// Set minimum date for start_date and load Quill content
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.querySelector('[name="start_date"]');
    const endDateInput = document.querySelector('[name="end_date"]');
    
    startDateInput.addEventListener('change', function() {
        endDateInput.min = this.value;
    });
    
    // Load existing content into Quill editor
    setTimeout(() => {
        if (window.client_project_edit_description_editor) {
            const existingContent = `{!! addslashes($project->description ?? '') !!}`;
            if (existingContent) {
                window.client_project_edit_description_editor.root.innerHTML = existingContent;
            }
        }
    }, 1000);
});
</script>
@endpush
@endsection