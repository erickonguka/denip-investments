@extends('layouts.landing')

@section('title', 'Login - Denip Investments Ltd')

@section('content')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--primary) 0%, var(--dark) 100%); padding: 2rem 1rem;">
    <div style="background: white; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.1); padding: 3rem; width: 100%; max-width: 400px;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <h1 style="color: var(--primary); font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">Welcome Back</h1>
            <p style="color: var(--dark); opacity: 0.7;">Sign in to your account</p>
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

            <div style="margin-bottom: 2rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--dark); font-weight: 600;">Password</label>
                <input type="password" name="password" required 
                       style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s ease;"
                       onfocus="this.style.borderColor='var(--secondary)'" 
                       onblur="this.style.borderColor='#e0e0e0'">
            </div>

            <button type="submit" id="loginBtn" class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1rem; margin-bottom: 1rem;">
                <span id="loginBtnText">Sign In</span>
                <i id="loginSpinner" class="fas fa-spinner fa-spin" style="display: none; margin-left: 0.5rem;"></i>
            </button>
        </form>

        <div style="text-align: center; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e0e0e0;">
            <p style="color: var(--dark); opacity: 0.7; margin-bottom: 1rem;">Don't have an account? <a href="{{ route('client.register.default') }}" style="color: var(--secondary); text-decoration: none; font-weight: 600;">Sign up here</a></p>
            <p><a href="{{ route('password.request') }}" style="color: var(--secondary); text-decoration: none; font-weight: 600;">Forgot your password?</a></p>
        </div>
    </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('loginBtn');
    const btnText = document.getElementById('loginBtnText');
    const spinner = document.getElementById('loginSpinner');
    const alertContainer = document.getElementById('alertContainer');
    
    btn.disabled = true;
    btnText.textContent = 'Signing In...';
    spinner.style.display = 'inline-block';
    alertContainer.innerHTML = '';
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('{{ route("unified.login.submit") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            btnText.textContent = 'Success!';
            btn.style.background = '#4CAF50';
            
            alertContainer.innerHTML = `
                <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #c3e6cb;">
                    <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>
                    Login successful! Redirecting...
                </div>
            `;
            
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1000);
        } else {
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
        btn.disabled = false;
        btnText.textContent = 'Sign In';
        spinner.style.display = 'none';
        btn.style.background = '';
    }
});
</script>
@endsection