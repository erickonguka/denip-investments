@extends('layouts.landing')

@section('title', 'Verify Email - Denip Investments Ltd')

@section('content')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--primary) 0%, var(--dark) 100%); padding: 2rem 1rem;">
    <div style="background: white; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.1); padding: 3rem; width: 100%; max-width: 500px;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <div style="width: 80px; height: 80px; background: var(--secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                <i class="fas fa-envelope" style="font-size: 2rem; color: white;"></i>
            </div>
            <h1 style="color: var(--primary); font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">Verify Your Email</h1>
            <p style="color: var(--dark); opacity: 0.7;">We've sent a 6-digit code to <strong>{{ $email }}</strong></p>
        </div>

        <div id="alertContainer"></div>

        <form id="verifyForm">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            
            <div style="margin-bottom: 2rem;">
                <label style="display: block; margin-bottom: 1rem; color: var(--dark); font-weight: 600; text-align: center;">Enter Verification Code</label>
                <div style="display: flex; gap: 0.5rem; justify-content: center; margin-bottom: 1rem;">
                    <input type="text" maxlength="1" class="code-input" style="width: 50px; height: 50px; text-align: center; font-size: 1.5rem; font-weight: 700; border: 2px solid #e0e0e0; border-radius: 8px;">
                    <input type="text" maxlength="1" class="code-input" style="width: 50px; height: 50px; text-align: center; font-size: 1.5rem; font-weight: 700; border: 2px solid #e0e0e0; border-radius: 8px;">
                    <input type="text" maxlength="1" class="code-input" style="width: 50px; height: 50px; text-align: center; font-size: 1.5rem; font-weight: 700; border: 2px solid #e0e0e0; border-radius: 8px;">
                    <input type="text" maxlength="1" class="code-input" style="width: 50px; height: 50px; text-align: center; font-size: 1.5rem; font-weight: 700; border: 2px solid #e0e0e0; border-radius: 8px;">
                    <input type="text" maxlength="1" class="code-input" style="width: 50px; height: 50px; text-align: center; font-size: 1.5rem; font-weight: 700; border: 2px solid #e0e0e0; border-radius: 8px;">
                    <input type="text" maxlength="1" class="code-input" style="width: 50px; height: 50px; text-align: center; font-size: 1.5rem; font-weight: 700; border: 2px solid #e0e0e0; border-radius: 8px;">
                </div>
                <input type="hidden" name="code" id="fullCode">
            </div>

            <button type="submit" id="verifyBtn" class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1rem; margin-bottom: 1rem;">
                <span id="verifyBtnText">Verify Email</span>
                <i id="verifySpinner" class="fas fa-spinner fa-spin" style="display: none; margin-left: 0.5rem;"></i>
            </button>
        </form>

        <div style="text-align: center; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e0e0e0;">
            <p style="color: var(--dark); opacity: 0.7; margin-bottom: 1rem;">Didn't receive the code?</p>
            <button id="resendBtn" style="background: none; border: none; color: var(--secondary); font-weight: 600; cursor: pointer; text-decoration: underline;">
                Resend Code
            </button>
        </div>
    </div>
</div>

<script>
const codeInputs = document.querySelectorAll('.code-input');
const fullCodeInput = document.getElementById('fullCode');

codeInputs.forEach((input, index) => {
    input.addEventListener('input', function(e) {
        if (e.target.value.length === 1) {
            if (index < codeInputs.length - 1) {
                codeInputs[index + 1].focus();
            }
        }
        updateFullCode();
    });

    input.addEventListener('keydown', function(e) {
        if (e.key === 'Backspace' && e.target.value === '' && index > 0) {
            codeInputs[index - 1].focus();
        }
    });

    input.addEventListener('paste', function(e) {
        e.preventDefault();
        const paste = e.clipboardData.getData('text');
        if (paste.length === 6 && /^\d+$/.test(paste)) {
            paste.split('').forEach((char, i) => {
                if (codeInputs[i]) {
                    codeInputs[i].value = char;
                }
            });
            updateFullCode();
        }
    });
});

function updateFullCode() {
    const code = Array.from(codeInputs).map(input => input.value).join('');
    fullCodeInput.value = code;
}

document.getElementById('verifyForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('verifyBtn');
    const btnText = document.getElementById('verifyBtnText');
    const spinner = document.getElementById('verifySpinner');
    const alertContainer = document.getElementById('alertContainer');
    
    btn.disabled = true;
    btnText.textContent = 'Verifying...';
    spinner.style.display = 'inline-block';
    alertContainer.innerHTML = '';
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('{{ route("client.verify.submit") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            btnText.textContent = 'Verified!';
            btn.style.background = '#4CAF50';
            
            alertContainer.innerHTML = `
                <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                    <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>
                    Email verified successfully! Redirecting to dashboard...
                </div>
            `;
            
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
            
            // Clear inputs on error
            codeInputs.forEach(input => input.value = '');
            codeInputs[0].focus();
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
        btnText.textContent = 'Verify Email';
        spinner.style.display = 'none';
        btn.style.background = '';
    }
});

document.getElementById('resendBtn').addEventListener('click', async function() {
    const btn = this;
    btn.disabled = true;
    btn.textContent = 'Sending...';
    
    try {
        const response = await fetch('{{ route("client.verify.resend") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ email: '{{ $email }}' })
        });
        
        const data = await response.json();
        
        if (data.success) {
            btn.textContent = 'Code Sent!';
            btn.style.color = '#4CAF50';
        } else {
            btn.textContent = 'Failed to Send';
            btn.style.color = '#e74c3c';
        }
    } catch (error) {
        btn.textContent = 'Failed to Send';
        btn.style.color = '#e74c3c';
    }
    
    setTimeout(() => {
        btn.disabled = false;
        btn.textContent = 'Resend Code';
        btn.style.color = '';
    }, 3000);
});

// Auto-focus first input
codeInputs[0].focus();
</script>
@endsection