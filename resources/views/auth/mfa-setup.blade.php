@extends('layouts.auth')

@section('title', 'MFA Setup')
@section('subtitle', 'Security Configuration')

@section('content')
<div style="text-align: center; margin-bottom: 2rem;">
    <div style="width: 80px; height: 80px; background: var(--yellow); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 2rem; color: var(--deep-blue);">
        <i class="fas fa-shield-alt"></i>
    </div>
    <h2 style="color: var(--deep-blue); margin-bottom: 0.5rem;">Setup Multi-Factor Authentication</h2>
    <p style="color: var(--gray-600);">Secure your account with an authenticator app</p>
</div>
            <div id="step1" class="mfa-step">
                <h3 style="color: var(--deep-blue); margin-bottom: 1rem;">Step 1: Install Authenticator App</h3>
                <p style="margin-bottom: 1rem; color: var(--gray-600);">Download and install one of these authenticator apps:</p>
                <div style="display: grid; gap: 0.5rem; margin-bottom: 2rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fab fa-google" style="color: #4285f4;"></i>
                        <span>Google Authenticator</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fab fa-microsoft" style="color: #00a1f1;"></i>
                        <span>Microsoft Authenticator</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-key" style="color: var(--primary-blue);"></i>
                        <span>Authy</span>
                    </div>
                </div>
                <button class="btn btn-primary" onclick="showStep2()" style="width: 100%;">
                    I have installed an app
                </button>
            </div>

            <div id="step2" class="mfa-step" style="display: none;">
                <h3 style="color: var(--deep-blue); margin-bottom: 1rem;">Step 2: Scan QR Code</h3>
                <div style="text-align: center; margin-bottom: 2rem;">
                    <div id="qrcode" style="display: inline-block; padding: 1rem; background: var(--white); border: 1px solid var(--gray-200); border-radius: 8px;"></div>
                </div>
                <p style="margin-bottom: 1rem; color: var(--gray-600); text-align: center;">
                    Can't scan? Enter this code manually:
                </p>
                <div style="background: var(--gray-50); padding: 1rem; border-radius: 8px; text-align: center; font-family: monospace; margin-bottom: 2rem;">
                    <span id="secret-key">{{ $secret }}</span>
                </div>
                <button class="btn btn-primary" onclick="showStep3()" style="width: 100%;">
                    I have added the account
                </button>
            </div>

            <div id="step3" class="mfa-step" style="display: none;">
                <h3 style="color: var(--deep-blue); margin-bottom: 1rem;">Step 3: Verify Setup</h3>
                <p style="margin-bottom: 1rem; color: var(--gray-600);">
                    Enter the 6-digit code from your authenticator app:
                </p>
                <form id="mfaVerifyForm">
                    <div style="margin-bottom: 1.5rem;">
                        <input type="text" name="code" placeholder="000000" maxlength="6" 
                               style="width: 100%; padding: 1rem; border: 2px solid var(--gray-300); border-radius: 8px; text-align: center; font-size: 1.5rem; font-family: monospace;" required>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <span class="btn-text">Verify & Enable MFA</span>
                    </button>
                    <button type="button" class="btn" style="width: 100%; margin-top: 1rem; background: transparent; color: var(--gray-600); border: 1px solid var(--gray-300);" onclick="cancelMfaSetup()">
                        Cancel Setup
                    </button>
                </form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.js"></script>
<script>
function showStep2() {
    document.getElementById('step1').style.display = 'none';
    document.getElementById('step2').style.display = 'block';
    
    // Generate QR code
    const qrContainer = document.getElementById('qrcode');
    const qrData = 'otpauth://totp/{{ config("app.name") }}:{{ $user->email }}?secret={{ $secret }}&issuer={{ config("app.name") }}';
    
    try {
        const qr = qrcode(0, 'M');
        qr.addData(qrData);
        qr.make();
        
        qrContainer.innerHTML = qr.createImgTag(4, 8);
        
        // Style the generated image
        const img = qrContainer.querySelector('img');
        if (img) {
            img.style.border = '1px solid var(--gray-300)';
            img.style.borderRadius = '8px';
        }
    } catch (error) {
        console.error('QR generation error:', error);
        qrContainer.innerHTML = '<div style="padding: 2rem; text-align: center; color: var(--error);">QR Code unavailable. Use manual entry below.</div>';
    }
}

function showStep3() {
    document.getElementById('step2').style.display = 'none';
    document.getElementById('step3').style.display = 'block';
}

document.getElementById('mfaVerifyForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btn = this.querySelector('button[type="submit"]');
    const btnText = btn.querySelector('.btn-text');
    const code = this.querySelector('input[name="code"]').value;
    
    btn.disabled = true;
    btnText.textContent = 'Verifying...';
    
    fetch('{{ route("mfa.confirm") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            secret: '{{ $secret }}',
            code: code
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('MFA enabled successfully!', 'success');
            setTimeout(() => {
                window.location.href = '{{ route("dashboard") }}';
            }, 1500);
        } else {
            showNotification(data.message || 'Invalid code. Please try again.', 'error');
        }
    })
    .catch(() => {
        showNotification('An error occurred. Please try again.', 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btnText.textContent = 'Verify & Enable MFA';
    });
});

function cancelMfaSetup() {
    fetch('/logout', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }).then(() => {
        window.location.href = '/login';
    });
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        background: ${type === 'error' ? '#ef4444' : type === 'success' ? '#10b981' : '#3b82f6'};
        color: white;
        border-radius: 8px;
        z-index: 10000;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        max-width: 400px;
    `;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (document.body.contains(notification)) {
            document.body.removeChild(notification);
        }
    }, 5000);
}
</script>
@endpush