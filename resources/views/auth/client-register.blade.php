@extends('layouts.landing')

@section('title', 'Create Account - Denip Investments Ltd')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">
@endpush

@section('content')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: white; padding: 10rem 1rem 2rem;">
    <div style="background: white; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.1); padding: 1.5rem; width: 100%; max-width: 400px;">
        <div style="margin-bottom: 1rem;">
            <a href="{{ route('landing.index') }}" style="color: var(--primary); text-decoration: none; font-size: 0.9rem;">
                <i class="fas fa-arrow-left"></i> Back to Home
            </a>
        </div>
        <div style="text-align: center; margin-bottom: 1rem;">
            <h1 style="color: #2c3e50; font-size: 1.5rem; font-weight: 700; margin-bottom: 0.3rem;">Create Account</h1>
            <p style="color: #34495e; opacity: 0.7; font-size: 0.85rem;">Join us to manage your projects</p>
        </div>

        <div id="alertContainer"></div>

        <form id="registerForm">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.8rem; margin-bottom: 0.8rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.2rem; color: #34495e; font-weight: 600; font-size: 0.8rem;">First Name</label>
                    <input type="text" name="first_name" required value="{{ old('first_name') }}"
                           style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.9rem;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.2rem; color: #34495e; font-weight: 600; font-size: 0.8rem;">Last Name</label>
                    <input type="text" name="last_name" required value="{{ old('last_name') }}"
                           style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.9rem;">
                </div>
            </div>

            <div style="margin-bottom: 0.8rem;">
                <label style="display: block; margin-bottom: 0.2rem; color: #34495e; font-weight: 600; font-size: 0.8rem;">Email Address</label>
                <input type="email" name="email" required value="{{ old('email') }}"
                       style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.9rem;">
            </div>

            <div style="margin-bottom: 0.8rem;">
                <label style="display: block; margin-bottom: 0.2rem; color: #34495e; font-weight: 600; font-size: 0.8rem;">Phone Number</label>
                <input type="tel" id="phone" name="phone" required value="{{ old('phone') }}"
                       style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.9rem;">
            </div>

            <div style="margin-bottom: 0.8rem;">
                <label style="display: block; margin-bottom: 0.2rem; color: #34495e; font-weight: 600; font-size: 0.8rem;">Company Name</label>
                <input type="text" name="company" required value="{{ old('company') }}"
                       style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.9rem;">
            </div>

            <div style="margin-bottom: 0.8rem;">
                <label style="display: block; margin-bottom: 0.2rem; color: #34495e; font-weight: 600; font-size: 0.8rem;">Password</label>
                <div style="position: relative;">
                    <input type="password" name="password" id="password" required minlength="8"
                           style="width: 100%; padding: 0.5rem 2.5rem 0.5rem 0.5rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.9rem;">
                    <button type="button" onclick="togglePassword('password')" style="position: absolute; right: 0.5rem; top: 50%; transform: translateY(-50%); background: none; border: none; color: #666; cursor: pointer; padding: 0.25rem;">
                        <i id="password-eye" class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.2rem; color: #34495e; font-weight: 600; font-size: 0.8rem;">Confirm Password</label>
                <div style="position: relative;">
                    <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8"
                           style="width: 100%; padding: 0.5rem 2.5rem 0.5rem 0.5rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.9rem;">
                    <button type="button" onclick="togglePassword('password_confirmation')" style="position: absolute; right: 0.5rem; top: 50%; transform: translateY(-50%); background: none; border: none; color: #666; cursor: pointer; padding: 0.25rem;">
                        <i id="password_confirmation-eye" class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" id="registerBtn" class="btn btn-primary" style="width: 100%; padding: 0.7rem; font-size: 0.9rem; margin-bottom: 0.8rem; background: #f39c12; border: none; border-radius: 6px; color: white; font-weight: 600;">
                <span id="registerBtnText">Create Account</span>
                <i id="registerSpinner" class="fas fa-spinner fa-spin" style="display: none; margin-left: 0.5rem;"></i>
            </button>
        </form>

        <div style="text-align: center; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e0e0e0;">
            <p style="color: var(--dark); opacity: 0.7;">Already have an account? <a href="{{ route('client.login') }}" style="color: var(--secondary); text-decoration: none; font-weight: 600;">Sign In</a></p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
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

const phoneInput = window.intlTelInput(document.querySelector("#phone"), {
    initialCountry: "ke",
    preferredCountries: ["ke", "ug", "tz", "rw"],
    utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
});

// Restore form values from localStorage
const form = document.getElementById('registerForm');
const inputs = form.querySelectorAll('input[type="text"], input[type="email"]');
inputs.forEach(input => {
    const saved = localStorage.getItem('register_' + input.name);
    if (saved && !input.value) input.value = saved;
});

// Save form values to localStorage on input
inputs.forEach(input => {
    input.addEventListener('input', () => {
        localStorage.setItem('register_' + input.name, input.value);
    });
});

document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('registerBtn');
    const btnText = document.getElementById('registerBtnText');
    const spinner = document.getElementById('registerSpinner');
    const alertContainer = document.getElementById('alertContainer');
    
    btn.disabled = true;
    btnText.textContent = 'Creating Account...';
    spinner.style.display = 'inline-block';
    alertContainer.innerHTML = '';
    
    const formData = new FormData(this);
    formData.set('phone', phoneInput.getNumber());
    formData.set('country', phoneInput.getSelectedCountryData().iso2);
    
    try {
        const response = await fetch('{{ route("client.register.submit") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            btnText.textContent = 'Success!';
            btn.style.background = '#4CAF50';
            
            alertContainer.innerHTML = `
                <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                    <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>
                    Account created! Redirecting to email verification...
                </div>
            `;
            
            // Clear saved form data on success
            inputs.forEach(input => {
                localStorage.removeItem('register_' + input.name);
            });
            
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1000);
        } else {
            let errorHtml = '<div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;"><i class="fas fa-exclamation-triangle" style="margin-right: 0.5rem;"></i>';
            
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
            <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                <i class="fas fa-exclamation-triangle" style="margin-right: 0.5rem;"></i>
                An error occurred. Please try again.
            </div>
        `;
    } finally {
        btn.disabled = false;
        btnText.textContent = 'Create Account';
        spinner.style.display = 'none';
        btn.style.background = '';
    }
});
</script>
@endsection