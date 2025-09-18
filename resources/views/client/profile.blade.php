@extends('layouts.client')

@section('title', 'Profile - Denip Investments Ltd')
@section('page-title', 'My Profile')

@section('content')
<div class="dashboard-header">
    <h1>My Profile</h1>
    <p>Manage your account information and settings</p>
</div>

<div class="profile-container">
    <div class="profile-grid">
        <!-- Profile Information -->
        <div class="profile-section">
            <div class="section-header">
                <h2>Profile Information</h2>
                <button onclick="openModal('profile-modal')" class="btn btn-primary">
                    <i class="fas fa-edit"></i>
                    Edit Profile
                </button>
            </div>
            
            <div class="profile-info">
                <div class="profile-avatar">
                    @if(auth()->user()->profile_photo)
                        <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile Photo">
                    @else
                        <div class="avatar-placeholder">
                            {{ strtoupper(substr(auth()->user()->first_name, 0, 1) . substr(auth()->user()->last_name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                
                <div class="profile-details">
                    <div class="detail-item">
                        <label>Full Name</label>
                        <span>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
                    </div>
                    
                    <div class="detail-item">
                        <label>Email Address</label>
                        <span>{{ auth()->user()->email }}</span>
                    </div>
                    
                    <div class="detail-item">
                        <label>Phone Number</label>
                        <span>{{ auth()->user()->phone ?? 'Not provided' }}</span>
                    </div>
                    
                    <div class="detail-item">
                        <label>Company</label>
                        <span>{{ auth()->user()->company ?? 'Not provided' }}</span>
                    </div>
                    
                    <div class="detail-item">
                        <label>Job Title</label>
                        <span>{{ auth()->user()->job_title ?? 'Not provided' }}</span>
                    </div>
                    
                    <div class="detail-item">
                        <label>Industry</label>
                        <span>{{ auth()->user()->industry ?? 'Not provided' }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Account Security -->
        <div class="profile-section">
            <div class="section-header">
                <h2>Account Security</h2>
            </div>
            
            <div class="security-info">
                <div class="security-item">
                    <div class="security-icon">
                        <i class="fas fa-key"></i>
                    </div>
                    <div class="security-content">
                        <h4>Password</h4>
                        <p>Last updated: {{ auth()->user()->updated_at->format('M j, Y') }}</p>
                        <button onclick="openModal('password-modal')" class="btn btn-outline btn-sm">Change Password</button>
                    </div>
                </div>
                
                <div class="security-item">
                    <div class="security-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="security-content">
                        <h4>Two-Factor Authentication</h4>
                        @if(auth()->user()->mfa_enabled ?? false)
                            <p class="text-success">Enabled - Your account is protected</p>
                            <button onclick="disableMfa()" class="btn btn-outline btn-sm">Disable MFA</button>
                        @else
                            <p class="text-warning">Disabled - Consider enabling for better security</p>
                            <button onclick="enableMfa()" class="btn btn-primary btn-sm">Enable MFA</button>
                        @endif
                    </div>
                </div>
                
                <div class="security-item">
                    <div class="security-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="security-content">
                        <h4>Last Login</h4>
                        <p>{{ auth()->user()->last_login_at ? auth()->user()->last_login_at->format('M j, Y g:i A') : 'Never' }}</p>
                        <small class="text-muted">IP: {{ auth()->user()->last_login_ip ?? 'Unknown' }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Contact Preferences -->
    <div class="profile-section">
        <div class="section-header">
            <h2>Contact Preferences</h2>
        </div>
        
        <div class="preferences-grid">
            <div class="preference-item">
                <label>Preferred Contact Method</label>
                <span>{{ auth()->user()->contact_preference ?? 'Not specified' }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Profile Edit Modal -->
<x-modal id="profile-modal" title="Edit Profile" size="large">
    <form id="profileForm" onsubmit="handleProfileUpdate(event)">
        <!-- Personal Information -->
        <div class="form-section">
            <h3 class="section-title">Personal Information</h3>
            <div class="form-grid">
                <x-form-field 
                    label="First Name" 
                    name="first_name" 
                    type="text" 
                    :required="true" 
                    :value="auth()->user()->first_name ?? ''"
                />
                
                <x-form-field 
                    label="Last Name" 
                    name="last_name" 
                    type="text" 
                    :required="true" 
                    :value="auth()->user()->last_name ?? ''"
                />
                
                <x-form-field 
                    label="Email Address" 
                    name="email" 
                    type="email" 
                    :required="true" 
                    :value="auth()->user()->email ?? ''"
                />
                
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--primary);">Phone Number</label>
                    <input type="tel" id="phone" name="phone" 
                           value="{{ auth()->user()->phone ?? '' }}"
                           style="width: 100%; padding: 0.75rem; border: 2px solid var(--secondary); border-radius: 8px; font-size: 1rem;">
                </div>
            </div>
        </div>
        
        <!-- Company Information -->
        <div class="form-section">
            <h3 class="section-title">Company Information</h3>
            <div class="form-grid">
                <x-form-field 
                    label="Company" 
                    name="company" 
                    type="text" 
                    :value="auth()->user()->company ?? ''"
                />
                
                <x-form-field 
                    label="Job Title" 
                    name="job_title" 
                    type="text" 
                    :value="auth()->user()->job_title ?? ''"
                />
                
                <x-form-field 
                    label="Industry" 
                    name="industry" 
                    type="select" 
                    :options="[
                        'real-estate' => 'Real Estate Development',
                        'manufacturing' => 'Manufacturing',
                        'hospitality' => 'Hospitality & Tourism',
                        'retail' => 'Retail & Commercial',
                        'government' => 'Government/Public Sector',
                        'education' => 'Education',
                        'healthcare' => 'Healthcare',
                        'other' => 'Other'
                    ]"
                    :value="auth()->user()->industry ?? ''"
                />
                
                <x-form-field 
                    label="Company Size" 
                    name="company_size" 
                    type="select" 
                    :options="[
                        'startup' => 'Startup (1-10 employees)',
                        'small' => 'Small (11-50 employees)',
                        'medium' => 'Medium (51-200 employees)',
                        'large' => 'Large (200+ employees)'
                    ]"
                    :value="auth()->user()->company_size ?? ''"
                />
                
                <x-form-field 
                    label="Years in Business" 
                    name="years_in_business" 
                    type="select" 
                    :options="[
                        'new' => 'New Business (0-1 years)',
                        'emerging' => 'Emerging (2-5 years)',
                        'established' => 'Established (6-15 years)',
                        'mature' => 'Mature (15+ years)'
                    ]"
                    :value="auth()->user()->years_in_business ?? ''"
                />
                
                <x-form-field 
                    label="Registration Number" 
                    name="registration_number" 
                    type="text" 
                    :value="auth()->user()->registration_number ?? ''"
                />
            </div>
        </div>
        
        <!-- Contact Preferences -->
        <div class="form-section">
            <h3 class="section-title">Contact Preferences</h3>
            <div class="form-grid">
                <x-form-field 
                    label="Preferred Contact Method" 
                    name="contact_preference" 
                    type="select" 
                    :options="[
                        'email' => 'Email',
                        'phone' => 'Phone Call',
                        'whatsapp' => 'WhatsApp',
                        'meeting' => 'In-Person Meeting'
                    ]"
                    :value="auth()->user()->contact_preference ?? ''"
                />
            </div>
        </div>
        
        <!-- Profile Photo -->
        <div class="form-section">
            <h3 class="section-title">Profile Photo</h3>
            <div style="margin-bottom: 1.5rem;">
                <x-upload-dropbox 
                    name="profile_photo" 
                    accept="image/*" 
                    :multiple="false" 
                    maxSize="5" 
                    text="Drop your profile photo here or click to upload" 
                    :existingMedia="auth()->user()->profile_photo" 
                />
            </div>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary" style="flex: 1;">
                <span class="btn-text">Update Profile</span>
            </button>
            <button type="button" onclick="closeModal('profile-modal')" class="btn btn-outline" style="flex: 1;">
                Cancel
            </button>
        </div>
    </form>
</x-modal>

<!-- Password Change Modal -->
<x-modal id="password-modal" title="Change Password" size="default">
    <form id="passwordForm" onsubmit="handlePasswordChange(event)">
        <x-form-field 
            label="Current Password" 
            name="current_password" 
            type="password" 
            :required="true"
        />
        
        <x-form-field 
            label="New Password" 
            name="password" 
            type="password" 
            :required="true"
        />
        
        <x-form-field 
            label="Confirm New Password" 
            name="password_confirmation" 
            type="password" 
            :required="true"
        />

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary" style="flex: 1;">
                <span class="btn-text">Change Password</span>
            </button>
            <button type="button" onclick="closeModal('password-modal')" class="btn btn-outline" style="flex: 1;">
                Cancel
            </button>
        </div>
    </form>
</x-modal>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">
<style>
.profile-container {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.profile-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
}

.profile-section {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px var(--shadow);
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

.profile-info {
    display: flex;
    gap: 2rem;
    align-items: flex-start;
}

.profile-avatar {
    flex-shrink: 0;
}

.profile-avatar img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid var(--light);
}

.avatar-placeholder {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: var(--secondary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 600;
    color: white;
}

.profile-details {
    flex: 1;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.detail-item label {
    display: block;
    font-weight: 600;
    color: var(--primary);
    margin-bottom: 0.25rem;
    font-size: 0.9rem;
}

.detail-item span {
    color: var(--dark);
    font-size: 1rem;
}

.security-info {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.security-item {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
}

.security-icon {
    width: 40px;
    height: 40px;
    background: var(--light);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    flex-shrink: 0;
}

.security-content h4 {
    color: var(--primary);
    margin: 0 0 0.25rem 0;
    font-size: 1rem;
}

.security-content p {
    margin: 0 0 0.5rem 0;
    color: var(--dark);
    font-size: 0.9rem;
}

.text-success {
    color: #28a745 !important;
}

.text-warning {
    color: #ffc107 !important;
}

.text-muted {
    color: #6c757d !important;
    font-size: 0.8rem;
}

.preferences-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.preference-item {
    padding: 1rem;
    background: var(--light);
    border-radius: 8px;
}

.preference-item label {
    display: block;
    font-weight: 600;
    color: var(--primary);
    margin-bottom: 0.25rem;
    font-size: 0.9rem;
}

.preference-item span {
    color: var(--dark);
    font-size: 1rem;
}

.form-section {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--light);
}

.form-section:last-of-type {
    border-bottom: none;
    margin-bottom: 1rem;
}

.section-title {
    color: var(--primary);
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0 0 1rem 0;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--secondary);
    display: inline-block;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

#profile-modal input,
#profile-modal select,
#profile-modal textarea,
#password-modal input,
#password-modal select,
#password-modal textarea {
    border: 2px solid var(--secondary) !important;
    border-radius: 8px;
}

#profile-modal input:focus,
#profile-modal select:focus,
#profile-modal textarea:focus,
#password-modal input:focus,
#password-modal select:focus,
#password-modal textarea:focus {
    border-color: var(--secondary) !important;
    box-shadow: 0 0 0 3px rgba(243, 156, 18, 0.1) !important;
}

@media (max-width: 768px) {
    .dashboard-header {
        text-align: center;
        margin-bottom: 1.5rem;
    }
    
    .dashboard-header h1 {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }
    
    .profile-container {
        gap: 1.5rem;
    }
    
    .profile-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .profile-section {
        padding: 1.5rem 1rem;
        margin: 0 -0.5rem;
    }
    
    .section-header {
        flex-direction: column;
        gap: 1rem;
        align-items: center;
        text-align: center;
        margin-bottom: 1.5rem;
    }
    
    .section-header h2 {
        font-size: 1.1rem;
    }
    
    .profile-info {
        flex-direction: column;
        text-align: center;
        gap: 1.5rem;
        align-items: center;
    }
    
    .profile-avatar {
        margin-bottom: 0.5rem;
    }
    
    .profile-avatar img,
    .avatar-placeholder {
        width: 100px;
        height: 100px;
        font-size: 1.8rem;
    }
    
    .profile-details {
        grid-template-columns: 1fr;
        gap: 1.25rem;
        width: 100%;
        max-width: none;
    }
    
    .detail-item {
        text-align: left;
        padding: 0.75rem;
        background: var(--light);
        border-radius: 8px;
    }
    
    .security-info {
        gap: 1.25rem;
    }
    
    .security-item {
        padding: 1rem;
        background: var(--light);
        border-radius: 8px;
        text-align: left;
    }
    
    .security-icon {
        margin-bottom: 0.75rem;
    }
    
    .form-section {
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
    }
    
    .section-title {
        font-size: 1rem;
        margin-bottom: 0.75rem;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .preferences-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .preference-item {
        padding: 1rem;
        text-align: left;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
        padding: 0.875rem 1rem;
    }
    
    .btn-sm {
        padding: 0.625rem 1rem;
        font-size: 0.9rem;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
<script>
let phoneInput;

document.addEventListener('DOMContentLoaded', function() {
    const phoneField = document.querySelector('#phone');
    if (phoneField) {
        phoneInput = window.intlTelInput(phoneField, {
            initialCountry: "ke",
            preferredCountries: ["ke", "ug", "tz", "rw"],
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
        });
    }
});

<script>
async function handleProfileUpdate(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // Update phone data if phoneInput exists
    if (phoneInput) {
        formData.set('phone', phoneInput.getNumber());
        formData.set('country', phoneInput.getSelectedCountryData().iso2);
    }
    
    setLoading(submitBtn, true, 'Updating...');
    
    try {
        const response = await fetch('{{ route("client.profile.update") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            closeModal('profile-modal');
            showNotification('Profile updated successfully', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('Failed to update profile', 'error');
        }
    } catch (error) {
        showNotification('An error occurred', 'error');
    } finally {
        setLoading(submitBtn, false, 'Update Profile');
    }
}

async function handlePasswordChange(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    const submitBtn = form.querySelector('button[type="submit"]');
    
    setLoading(submitBtn, true, 'Changing...');
    
    try {
        const response = await fetch('{{ route("client.password.change") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            closeModal('password-modal');
            showNotification('Password changed successfully', 'success');
            form.reset();
        } else {
            showNotification(result.message || 'Failed to change password', 'error');
        }
    } catch (error) {
        showNotification('An error occurred', 'error');
    } finally {
        setLoading(submitBtn, false, 'Change Password');
    }
}
</script>
@endpush
@endsection