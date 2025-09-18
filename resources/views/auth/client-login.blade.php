@extends('layouts.landing')

@section('title', 'Client Login - Denip Investments Ltd')

@section('content')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: white; padding: 2rem 1rem;">
    <div style="background: white; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.1); padding: 3rem; width: 100%; max-width: 400px;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <h1 style="color: var(--primary); font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">Client Login</h1>
            <p style="color: var(--dark); opacity: 0.7;">Access your project dashboard</p>
        </div>

        <div id="alertContainer"></div>

        <form id="loginForm">
            @csrf
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--dark); font-weight: 600;">Email Address</label>
                <input type="email" name="email" required 
                       style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s ease;"
                       onfocus="this.style.borderColor='var(--secondary)'" 
                       onblur="this.style.borderColor='#e0e0e0'">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--dark); font-weight: 600;">Password</label>
                <div style="position: relative;">
                    <input type="password" name="password" id="password" required 
                           style="width: 100%; padding: 0.75rem 3rem 0.75rem 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s ease;"
                           onfocus="this.style.borderColor='var(--secondary)'" 
                           onblur="this.style.borderColor='#e0e0e0'">
                    <button type="button" onclick="togglePassword('password')" style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: none; border: none; color: #666; cursor: pointer; padding: 0.25rem;">
                        <i id="password-eye" class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div id="mfaField" style="margin-bottom: 2rem; display: none;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--dark); font-weight: 600;">Verification Code</label>
                <input type="text" name="mfa_code" maxlength="6" placeholder="000000"
                       style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s ease; text-align: center; letter-spacing: 0.2em;"
                       onfocus="this.style.borderColor='var(--secondary)'" 
                       onblur="this.style.borderColor='#e0e0e0'">
                <small style="color: var(--dark); opacity: 0.7; font-size: 0.85rem;">Enter the 6-digit code from your authenticator app</small>
            </div>

            <button type="submit" id="loginBtn" class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1rem; margin-bottom: 1rem;">
                <span id="loginBtnText">Sign In</span>
                <i id="loginSpinner" class="fas fa-spinner fa-spin" style="display: none; margin-left: 0.5rem;"></i>
            </button>
        </form>

        <div style="text-align: center; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e0e0e0;">
            <p style="color: var(--dark); opacity: 0.7; margin-bottom: 1rem;">Don't have an account? <a href="{{ route('client.register') }}" style="color: var(--secondary); text-decoration: none; font-weight: 600;">Sign up here</a></p>
            <p><a href="{{ route('landing.index') }}#contact" style="color: var(--secondary); text-decoration: none; font-weight: 600;">Need help? Contact Us</a></p>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const eye = document.getElementById(fieldId + '-eye');
    
    if (field.type === 'password') {
        field.type = 'text';
        eye.className = 'fas fa-eye-slash';
    } else {
        field.type = 'password';
        eye.className = 'fas fa-eye';
    }
}

document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('loginBtn');
    const btnText = document.getElementById('loginBtnText');
    const spinner = document.getElementById('loginSpinner');
    const alertContainer = document.getElementById('alertContainer');
    
    // Show loading state
    btn.disabled = true;
    btnText.textContent = 'Signing In...';
    spinner.style.display = 'inline-block';
    
    // Clear previous alerts
    alertContainer.innerHTML = '';
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('{{ route("client.login.submit") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            if (data.requires_mfa) {
                // Show MFA field
                document.getElementById('mfaField').style.display = 'block';
                document.querySelector('input[name="mfa_code"]').focus();
                
                alertContainer.innerHTML = `
                    <div style="background: #fff3cd; color: #856404; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #ffeaa7;">
                        <i class="fas fa-shield-alt" style="margin-right: 0.5rem;"></i>
                        Please enter your verification code to continue.
                    </div>
                `;
            } else {
                btnText.textContent = 'Success!';
                btn.style.background = '#4CAF50';
                
                // Show success message
                alertContainer.innerHTML = `
                    <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #c3e6cb;">
                        <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>
                        Login successful! Redirecting...
                    </div>
                `;
                
                setTimeout(() => {
                    window.location.href = data.redirect || '{{ route("dashboard") }}';
                }, 1000);
            }
        } else {
            // Show error messages
            let errorHtml = '<div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #f5c6cb;"><i class="fas fa-exclamation-triangle" style="margin-right: 0.5rem;"></i>';
            
            if (data.errors) {
                Object.values(data.errors).forEach(errorArray => {
                    errorArray.forEach(error => {
                        errorHtml += error + '<br>';
                    });
                });
            }
            
            errorHtml += '</div>';
            alertContainer.innerHTML = errorHtml;
        }
    } catch (error) {
        alertContainer.innerHTML = `
            <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #f5c6cb;">
                <i class="fas fa-exclamation-triangle" style="margin-right: 0.5rem;"></i>
                An error occurred. Please try again.
            </div>
        `;
    } finally {
        // Reset button state
        btn.disabled = false;
        btnText.textContent = 'Sign In';
        spinner.style.display = 'none';
        btn.style.background = '';
    }
});
</script>
@endsection