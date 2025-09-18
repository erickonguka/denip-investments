@props(['id' => 'account-modal'])

<x-modal id="{{ $id }}" title="Account Settings" size="default">
    <form id="accountForm" onsubmit="handleAccountUpdate(event)">
        <x-form-field 
            label="Full Name" 
            name="name" 
            type="text" 
            :required="true" 
            :value="auth()->user()->name ?? ''"
        />
        
        <x-form-field 
            label="Email Address" 
            name="email" 
            type="email" 
            :required="true" 
            :value="auth()->user()->email ?? ''"
        />
        
        <x-form-field 
            label="Phone Number" 
            name="phone" 
            type="tel" 
            :value="auth()->user()->phone ?? ''"
        />
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">Profile Photo</label>
            @if(auth()->user()->profile_photo)
            <div style="margin-bottom: 1rem;">
                <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile Photo" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid var(--gray-200);">
            </div>
            @endif
            <x-upload-dropbox name="profile_photo" accept="image/*" :multiple="false" maxSize="5" text="Drop your profile photo here or click to upload" :existingMedia="auth()->user()->profile_photo" />
        </div>

        <!-- MFA Section -->
        <div style="border-top: 1px solid var(--gray-200); padding-top: 1.5rem; margin-top: 1.5rem;">
            <h4 style="color: var(--deep-blue); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-shield-alt"></i>
                Two-Factor Authentication
            </h4>
            
            @if(auth()->user()->mfa_enabled ?? false)
                <div style="background: var(--light-yellow); padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--deep-blue);">
                        <i class="fas fa-check-circle"></i>
                        <strong>MFA is enabled</strong>
                    </div>
                    <p style="margin: 0.5rem 0 0 0; font-size: 0.9rem; color: var(--gray-600);">
                        Your account is protected with two-factor authentication
                    </p>
                </div>
                <button type="button" onclick="disableMfa()" class="btn" style="background: var(--error); color: var(--white); width: auto; padding: 0.5rem 1rem;">
                    <i class="fas fa-times"></i>
                    Disable MFA
                </button>
            @else
                <div style="background: var(--gray-50); padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                    <p style="margin: 0; font-size: 0.9rem; color: var(--gray-600);">
                        Enhance your account security by enabling two-factor authentication
                    </p>
                </div>
                <button type="button" onclick="enableMfa()" class="btn btn-secondary" style="width: auto; padding: 0.5rem 1rem;">
                    <i class="fas fa-shield-alt"></i>
                    Enable MFA
                </button>
            @endif
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary" style="flex: 1;">
                <span class="btn-text">Update Account</span>
            </button>
            <button type="button" onclick="closeModal('{{ $id }}')" class="btn" style="background: var(--gray-300); color: var(--gray-700); flex: 1;">
                Cancel
            </button>
        </div>
    </form>
</x-modal>

<!-- MFA Setup Modal -->
<x-modal id="mfa-setup-modal" title="Enable Two-Factor Authentication" size="default">
    <div id="mfaSetupContent">
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <div style="width: 80px; height: 80px; background: var(--light-yellow); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 2rem; color: var(--primary-blue);">
                <i class="fas fa-qrcode"></i>
            </div>
            <h4 style="color: var(--deep-blue); margin-bottom: 0.5rem;">Scan QR Code</h4>
            <p style="color: var(--gray-600); font-size: 0.9rem;">
                Use your authenticator app to scan this QR code
            </p>
        </div>

        <div id="qrCodeContainer" style="text-align: center; margin: 1.5rem 0;">
            <!-- QR Code will be inserted here -->
        </div>

        <div style="background: var(--gray-50); padding: 1rem; border-radius: 8px; margin: 1rem 0;">
            <p style="font-size: 0.875rem; color: var(--gray-600); margin: 0;">
                <strong>Manual Entry:</strong> If you can't scan the QR code, enter this secret key manually:
            </p>
            <code id="secretKey" style="display: block; margin-top: 0.5rem; padding: 0.5rem; background: var(--white); border-radius: 4px; font-family: monospace; word-break: break-all;"></code>
        </div>

        <form id="mfaConfirmForm" onsubmit="confirmMfa(event)">
            <x-form-field 
                label="Verification Code" 
                name="mfa_code" 
                type="text" 
                placeholder="000000"
                :required="true"
            />
            
            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <span class="btn-text">Verify & Enable</span>
                </button>
                <button type="button" onclick="closeModal('mfa-setup-modal')" class="btn" style="background: var(--gray-300); color: var(--gray-700); flex: 1;">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</x-modal>

<!-- MFA Disable Modal -->
<x-modal id="mfa-disable-modal" title="Disable Two-Factor Authentication" size="default">
    <div style="text-align: center; margin-bottom: 1.5rem;">
        <div style="width: 80px; height: 80px; background: #fee2e2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 2rem; color: var(--error);">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <p style="color: var(--gray-600);">
            This will reduce your account security. Please confirm by entering your password and current MFA code.
        </p>
    </div>

    <form id="mfaDisableForm" onsubmit="confirmDisableMfa(event)">
        <x-form-field 
            label="Current Password" 
            name="password" 
            type="password" 
            :required="true"
        />
        
        <x-form-field 
            label="MFA Code" 
            name="mfa_code" 
            type="text" 
            placeholder="000000"
            :required="true"
        />
        
        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn" style="background: var(--error); color: var(--white); flex: 1;">
                <span class="btn-text">Disable MFA</span>
            </button>
            <button type="button" onclick="closeModal('mfa-disable-modal')" class="btn" style="background: var(--gray-300); color: var(--gray-700); flex: 1;">
                Cancel
            </button>
        </div>
    </form>
</x-modal>

@push('scripts')
<script>
async function handleAccountUpdate(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    formData.append('_method', 'PUT');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    setLoading(submitBtn, true, 'Updating...');
    
    try {
        const response = await fetch('/account/update', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            closeModal('account-modal');
            showNotification('Account updated successfully', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('Failed to update account', 'error');
        }
    } catch (error) {
        showNotification('An error occurred', 'error');
    } finally {
        setLoading(submitBtn, false, 'Update Account');
    }
}

async function enableMfa() {
    try {
        const response = await fetch('/mfa/enable', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            document.getElementById('qrCodeContainer').innerHTML = `<img src="${result.qr_code}" alt="QR Code" style="max-width: 200px;">`;
            document.getElementById('secretKey').textContent = result.secret;
            closeModal('account-modal');
            openModal('mfa-setup-modal');
        }
    } catch (error) {
        showNotification('Failed to generate MFA setup', 'error');
    }
}

async function confirmMfa(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    const submitBtn = form.querySelector('button[type="submit"]');
    
    setLoading(submitBtn, true, 'Verifying...');
    
    try {
        const response = await fetch('/mfa/confirm', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            closeModal('mfa-setup-modal');
            showNotification('MFA enabled successfully', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('Invalid verification code', 'error');
        }
    } catch (error) {
        showNotification('Verification failed', 'error');
    } finally {
        setLoading(submitBtn, false, 'Verify & Enable');
    }
}

function disableMfa() {
    closeModal('account-modal');
    openModal('mfa-disable-modal');
}

async function confirmDisableMfa(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    const submitBtn = form.querySelector('button[type="submit"]');
    
    setLoading(submitBtn, true, 'Disabling...');
    
    try {
        const response = await fetch('/mfa/disable', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            closeModal('mfa-disable-modal');
            showNotification('MFA disabled successfully', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            if (result.errors.password) {
                showNotification('Invalid password', 'error');
            } else if (result.errors.mfa_code) {
                showNotification('Invalid MFA code', 'error');
            }
        }
    } catch (error) {
        showNotification('Failed to disable MFA', 'error');
    } finally {
        setLoading(submitBtn, false, 'Disable MFA');
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
</script>
@endpush