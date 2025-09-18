@extends('layouts.app')

@section('title', 'User Details')

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <div class="user-profile">
            @if($user->profile_photo)
                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}" class="user-avatar">
            @else
                <div class="user-avatar-placeholder">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
            @endif
            <div class="user-info">
                <h1 class="page-title">{{ $user->name }}</h1>
                <p class="page-subtitle">{{ $user->roles->first()?->display_name ?? 'No Role Assigned' }}</p>
            </div>
        </div>
    </div>
    <div class="page-actions">
        <button class="btn btn-primary" onclick="editUser({{ $user->id }})">
            <i class="fas fa-edit"></i>
            <span class="btn-text">Edit User</span>
        </button>
        <a href="{{ route('users.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i>
            <span class="btn-text">Back to Users</span>
        </a>
    </div>
</div>

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2rem;
    gap: 1rem;
    width: 100%;
    max-width: 100%;
    overflow: hidden;
}

.page-header-content {
    flex: 1;
    min-width: 0;
    overflow: hidden;
}

.user-profile {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.user-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--primary-blue);
    flex-shrink: 0;
}

.user-avatar-placeholder {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--primary-blue);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 600;
    flex-shrink: 0;
}

.user-info {
    min-width: 0;
    flex: 1;
    overflow: hidden;
}

.page-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    align-items: center;
    flex-shrink: 0;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
    box-sizing: border-box;
}

.btn-primary { background: var(--primary-blue); color: white; }
.btn-outline { background: transparent; color: var(--gray-700); border: 2px solid var(--gray-300); }

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .user-profile {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .user-avatar,
    .user-avatar-placeholder {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
    
    .page-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem;
        width: 100%;
    }
    
    .btn {
        padding: 0.75rem 0.5rem;
        font-size: 0.9rem;
        min-width: 0;
        justify-content: center;
    }
    
    div[style*="grid-template-columns: 1fr 1fr"] {
        display: block !important;
    }
    
    div[style*="grid-template-columns: 1fr 1fr"] > div {
        margin-bottom: 1rem;
    }
    
    div[style*="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr))"] {
        display: block !important;
    }
    
    div[style*="overflow-x: auto"] table {
        font-size: 0.9rem;
    }
    
    div[style*="overflow-x: auto"] th,
    div[style*="overflow-x: auto"] td {
        padding: 0.5rem !important;
        white-space: nowrap;
    }
}
</style>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
    <!-- User Information -->
    <div style="background: var(--white); border-radius: 12px; padding: 2rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
        <h3 style="color: var(--deep-blue); margin-bottom: 1.5rem; border-bottom: 2px solid var(--yellow); padding-bottom: 0.5rem;">
            User Information
        </h3>
        <div style="display: grid; gap: 1rem;">
            <div>
                <strong>Full Name:</strong> {{ $user->name }}
            </div>
            <div>
                <strong>Email:</strong> {{ $user->email }}
            </div>
            <div>
                <strong>Phone:</strong> {{ $user->phone ?? 'Not provided' }}
            </div>
            <div>
                <strong>Role:</strong>
                <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; background: var(--light-yellow); color: var(--dark-yellow);">
                    {{ $user->roles->first()?->display_name ?? 'No Role' }}
                </span>
            </div>
            <div>
                <strong>Status:</strong>
                <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; 
                    background: {{ $user->status === 'active' ? 'var(--light-yellow)' : '#fef2f2' }}; 
                    color: {{ $user->status === 'active' ? 'var(--dark-yellow)' : 'var(--error)' }};">
                    {{ ucfirst($user->status) }}
                </span>
            </div>
            <div>
                <strong>MFA Enabled:</strong>
                <i class="fas {{ $user->mfa_enabled ? 'fa-check' : 'fa-times' }}" style="color: {{ $user->mfa_enabled ? 'var(--success)' : 'var(--error)' }};"></i>
                {{ $user->mfa_enabled ? 'Yes' : 'No' }}
                @if(!$user->mfa_enabled && $user->id === auth()->id())
                <a href="{{ route('mfa.setup') }}" class="btn" style="background: var(--primary-blue); color: white; padding: 0.25rem 0.5rem; font-size: 0.8rem; margin-left: 0.5rem;">
                    Setup MFA
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Account Activity -->
    <div style="background: var(--white); border-radius: 12px; padding: 2rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
        <h3 style="color: var(--deep-blue); margin-bottom: 1.5rem; border-bottom: 2px solid var(--yellow); padding-bottom: 0.5rem;">
            Account Activity
        </h3>
        <div style="display: grid; gap: 1rem;">
            <div style="display: flex; justify-content: space-between;">
                <span>Last Login:</span>
                <strong>{{ $user->last_login_at?->format('M j, Y g:i A') ?? 'Never' }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>Account Created:</span>
                <strong>{{ $user->created_at->format('M j, Y') }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>Last Updated:</span>
                <strong>{{ $user->updated_at->format('M j, Y') }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>Active Sessions:</span>
                <strong>{{ $user->sessions->count() }}</strong>
            </div>
        </div>
    </div>
</div>

<!-- User Permissions -->
@if($user->roles->first())
<div style="background: var(--white); border-radius: 12px; padding: 2rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); margin-top: 2rem;">
    <h3 style="color: var(--deep-blue); margin-bottom: 1.5rem; border-bottom: 2px solid var(--yellow); padding-bottom: 0.5rem;">
        Role Permissions
    </h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
        @foreach($user->roles->first()->permissions->groupBy('module') as $module => $permissions)
        <div style="background: var(--gray-50); padding: 1rem; border-radius: 8px;">
            <h4 style="color: var(--deep-blue); margin-bottom: 0.5rem; text-transform: capitalize;">{{ $module }}</h4>
            <div style="display: grid; gap: 0.25rem; font-size: 0.9rem;">
                @foreach($permissions as $permission)
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-check" style="color: var(--success); font-size: 0.8rem;"></i>
                    <span>{{ $permission->display_name }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Active Sessions -->
@if($user->sessions->count() > 0)
<div style="background: var(--white); border-radius: 12px; padding: 2rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); margin-top: 2rem;">
    <h3 style="color: var(--deep-blue); margin-bottom: 1.5rem; border-bottom: 2px solid var(--yellow); padding-bottom: 0.5rem;">
        Active Sessions
    </h3>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: var(--gray-50);">
                    <th style="padding: 1rem; text-align: left; border-bottom: 1px solid var(--gray-200);">Device</th>
                    <th style="padding: 1rem; text-align: left; border-bottom: 1px solid var(--gray-200);">IP Address</th>
                    <th style="padding: 1rem; text-align: left; border-bottom: 1px solid var(--gray-200);">Last Activity</th>
                    <th style="padding: 1rem; text-align: left; border-bottom: 1px solid var(--gray-200);">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($user->sessions as $session)
                <tr>
                    <td style="padding: 1rem; border-bottom: 1px solid var(--gray-200);">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas {{ str_contains($session->user_agent, 'Mobile') ? 'fa-mobile-alt' : 'fa-desktop' }}" style="color: var(--gray-600);"></i>
                            <span>{{ $session->device_type ?? 'Unknown Device' }}</span>
                        </div>
                    </td>
                    <td style="padding: 1rem; border-bottom: 1px solid var(--gray-200);">{{ $session->ip_address }}</td>
                    <td style="padding: 1rem; border-bottom: 1px solid var(--gray-200);">{{ $session->last_activity->diffForHumans() }}</td>
                    <td style="padding: 1rem; border-bottom: 1px solid var(--gray-200);">
                        @if($session->is_current)
                        <span style="color: var(--success); font-size: 0.8rem;">Current Session</span>
                        @else
                        <button class="btn" style="background: var(--error); color: white; padding: 0.25rem 0.5rem; font-size: 0.8rem;" onclick="revokeSession('{{ $session->id }}')">
                            Revoke
                        </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<x-modal id="user-modal" title="Edit User">
    <form id="userForm">
        <x-form-field label="Full Name" name="name" :required="true" />
        <x-form-field label="Email" name="email" type="email" :required="true" />
        <x-form-field label="Phone" name="phone" type="tel" />
        <x-form-field label="Role" name="role_id" type="select" :required="true" 
            :options="$roles->pluck('display_name', 'id')->toArray()" />
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                <input type="checkbox" name="mfa_enabled" value="1" style="transform: scale(1.2);">
                <span style="font-weight: 600; color: var(--deep-blue);">Enable Multi-Factor Authentication</span>
            </label>
        </div>
        
        <x-form-field label="Status" name="status" type="select" 
            :options="['active' => 'Active', 'inactive' => 'Inactive']" />
        
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">
                <span class="btn-text">Update User</span>
            </button>
            <button type="button" class="btn" style="background: var(--gray-300); color: var(--gray-700);" onclick="closeModal('user-modal')">Cancel</button>
        </div>
    </form>
</x-modal>
@endsection

@push('scripts')
<script>
function editUser(userId) {
    fetch(`{{ route('users.index') }}/${userId}/edit`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            const form = document.getElementById('userForm');
            
            Object.keys(response.data).forEach(key => {
                const field = form.querySelector(`[name="${key}"]`);
                if (field) {
                    if (field.type === 'checkbox') {
                        field.checked = response.data[key];
                    } else {
                        field.value = response.data[key] || '';
                    }
                }
            });
            
            openModal('user-modal');
        }
    })
    .catch(error => console.error('Error:', error));
}

document.getElementById('userForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btn = this.querySelector('button[type="submit"]');
    const btnText = btn.querySelector('.btn-text');
    
    btn.disabled = true;
    btnText.textContent = 'Updating...';
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    fetch('{{ route("users.update", $user) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({...data, _method: 'PUT'})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            closeModal('user-modal');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('Failed to update user', 'error');
        }
    })
    .catch(() => {
        showNotification('An error occurred', 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btnText.textContent = 'Update User';
    });
});

function revokeSession(sessionId) {
    if (confirm('Are you sure you want to revoke this session?')) {
        fetch(`/sessions/${sessionId}/revoke`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Session revoked successfully', 'success');
                setTimeout(() => location.reload(), 1000);
            }
        })
        .catch(() => {
            showNotification('Failed to revoke session', 'error');
        });
    }
}
</script>
@endpush