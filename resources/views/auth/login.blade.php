<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Denip Investments</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/css/auth.css" rel="stylesheet">
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            <div class="logo">
                <svg viewBox="0 0 1605 502" xmlns="http://www.w3.org/2000/svg">
                    <g transform="matrix(1,0,0,1,-698.678,-1249.19)">
                        <g transform="matrix(21.3645,0,0,21.3645,-449.868,-765.599)">
                            <g transform="matrix(0.818597,0,0,0.818597,15.8776,15.9463)">
                                <path d="M46.277,108.659L68.821,108.617L68.821,103.451L50.495,103.451L46.277,108.659Z" fill="currentColor"/>
                                <path d="M52.076,100.89L78.037,100.89L78.015,111.2L68.778,111.2L68.778,116.366L83.05,116.366L83.05,100.955L77.818,95.724L56.607,95.724L52.076,100.89Z" fill="currentColor"/>
                                <path d="M56.979,111.178L66.019,111.178L66.019,116.235L53.145,116.235L56.979,111.178Z" fill="currentColor"/>
                                <path d="M98.009,100.966L95.199,104.371L89.697,104.371L89.697,106.94L97.215,106.94L94.458,110.222L89.759,110.222L89.759,112.745L97.978,112.745L95.139,116.366L85.595,116.336L85.595,100.95L98.009,100.966Z" fill="currentColor"/>
                                <path d="M100.408,116.344L100.408,100.89L104.173,100.89L110.893,109.471L110.893,100.89L115.118,100.89L115.118,116.333L111.233,116.333L104.403,108.156L104.403,116.351L100.408,116.344Z" fill="currentColor"/>
                                <path d="M118.049,116.332L118.03,116.351L118.038,100.89L122.186,100.89L122.186,111.039L118.049,116.332Z" fill="currentColor"/>
                                <path d="M125.096,100.89L132.805,100.89C132.805,100.89 138.022,100.668 138.029,106.227C138.035,111.421 133.548,111.847 133.548,111.847L129.399,111.847L129.399,116.351L125.096,116.351L125.096,100.89ZM132.127,109.129C133.845,109.129 134.839,108.021 134.839,106.303C134.839,104.585 133.845,103.476 132.127,103.476L129.367,103.476L129.367,109.129L132.127,109.129Z" fill="currentColor"/>
                            </g>
                        </g>
                    </g>
                </svg>
                Denip Investments
            </div>
            <div class="auth-subtitle">Admin Portal Access</div>
        </div>

        <div class="auth-body">
            <!-- Login Form -->
            <form id="loginForm" style="display: block;">
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-input" required>
                    <div class="error-message" id="emailError"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div style="position: relative;">
                        <input type="password" name="password" class="form-input" id="passwordInput" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('passwordInput', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="error-message" id="passwordError"></div>
                </div>

                <button type="submit" class="btn btn-primary" id="loginBtn">
                    <span class="btn-text">Sign In</span>
                </button>

                <div class="device-info">
                    <i class="fas fa-info-circle"></i>
                    New device login will send email notification
                </div>
            </form>

            <!-- MFA Form -->
            <form id="mfaForm" class="mfa-section">
                <div class="mfa-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 style="margin-bottom: 0.5rem; color: var(--deep-blue);">Two-Factor Authentication</h3>
                <p style="color: var(--gray-600); margin-bottom: 1.5rem; font-size: 0.9rem;">
                    Enter the 6-digit code from your authenticator app
                </p>

                <div class="form-group">
                    <input type="text" name="mfa_code" class="form-input" placeholder="000000" maxlength="6" style="text-align: center; font-size: 1.5rem; letter-spacing: 0.5rem;">
                    <div class="error-message" id="mfaError"></div>
                </div>

                <button type="submit" class="btn btn-primary" id="mfaBtn">
                    <span class="btn-text">Verify & Continue</span>
                </button>

                <div style="text-align: center; margin-top: 1rem;">
                    <button type="button" onclick="showLoginForm()" style="background: none; border: none; color: var(--primary-blue); cursor: pointer; font-size: 0.9rem;">
                        ← Back to Login
                    </button>
                </div>
            </form>

            <div class="forgot-password">
                <a href="#" onclick="showForgotPassword()">Forgot your password?</a>
            </div>
        </div>
    </div>

    <script>
        const loginForm = document.getElementById('loginForm');
        const mfaForm = document.getElementById('mfaForm');
        const loginBtn = document.getElementById('loginBtn');
        const mfaBtn = document.getElementById('mfaBtn');

        // Handle login form submission
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(loginForm);
            const data = Object.fromEntries(formData);
            
            setLoading(loginBtn, true);
            clearErrors();

            try {
                const response = await fetch('/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    console.log('Login result:', result);
                    if (result.requires_mfa_setup) {
                        console.log('Redirecting to MFA setup:', result.setup_url);
                        showMfaSetupPrompt(result.setup_url);
                    } else if (result.requires_mfa) {
                        showMfaForm();
                    } else {
                        window.location.href = result.redirect || '/dashboard';
                    }
                } else {
                    showErrors(result.errors);
                }
            } catch (error) {
                showErrors({ email: ['Too many login attempts. Please wait and try again.'] });
            } finally {
                setLoading(loginBtn, false);
            }
        });

        // Handle MFA form submission
        mfaForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(mfaForm);
            const loginData = new FormData(loginForm);
            
            const data = {
                email: loginData.get('email'),
                password: loginData.get('password'),
                mfa_code: formData.get('mfa_code')
            };
            
            setLoading(mfaBtn, true);
            clearErrors();

            try {
                const response = await fetch('/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    window.location.href = result.redirect || '/dashboard';
                } else {
                    showErrors(result.errors);
                }
            } catch (error) {
                showErrors({ mfa_code: ['Verification failed. Please try again.'] });
            } finally {
                setLoading(mfaBtn, false);
            }
        });

        function showMfaForm() {
            loginForm.style.display = 'none';
            mfaForm.style.display = 'block';
            mfaForm.querySelector('input[name="mfa_code"]').focus();
        }

        function showLoginForm() {
            mfaForm.style.display = 'none';
            loginForm.style.display = 'block';
            clearErrors();
        }

        function showMfaSetupPrompt(setupUrl) {
            console.log('Showing MFA setup prompt, redirecting to:', setupUrl);
            showNotification('MFA setup required. Redirecting...', 'info');
            setTimeout(() => {
                console.log('Redirecting now to:', setupUrl);
                window.location.href = setupUrl;
            }, 1000);
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

        function setLoading(button, loading) {
            const btnText = button.querySelector('.btn-text');
            if (loading) {
                button.disabled = true;
                btnText.innerHTML = '<div class="spinner"></div> Signing In...';
            } else {
                button.disabled = false;
                btnText.textContent = button === loginBtn ? 'Sign In' : 'Verify & Continue';
            }
        }

        function clearErrors() {
            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
            document.querySelectorAll('.form-input').forEach(el => el.classList.remove('error'));
        }

        function showErrors(errors) {
            Object.keys(errors).forEach(field => {
                const errorEl = document.getElementById(field + 'Error');
                const inputEl = document.querySelector(`[name="${field}"]`);
                
                if (errorEl && errors[field][0]) {
                    errorEl.textContent = errors[field][0];
                }
                if (inputEl) {
                    inputEl.classList.add('error');
                }
            });
        }

        function showForgotPassword() {
            window.location.href = '/forgot-password';
        }

        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Better error handling for throttle
        async function handleResponse(response) {
            if (response.status === 429) {
                throw new Error('Too many attempts');
            }
            return response.json();
        }

        // Auto-format MFA code input
        const mfaInput = document.querySelector('input[name="mfa_code"]');
        mfaInput.addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(/\D/g, '');
            if (e.target.value.length === 6) {
                mfaForm.dispatchEvent(new Event('submit'));
            }
        });
    </script>
</body>
</html>