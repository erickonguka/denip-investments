<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Denip Investments</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/css/auth.css" rel="stylesheet">
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            <div class="logo">Denip Investments</div>
            <div style="opacity: 0.9; font-size: 0.9rem;">Password Recovery</div>
        </div>

        <div class="auth-body">
            <form id="forgotForm">
                <div style="text-align: center; margin-bottom: 1.5rem;">
                    <div style="width: 80px; height: 80px; background: #fef3c7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 2rem; color: var(--primary-blue);">
                        <i class="fas fa-key"></i>
                    </div>
                    <h3 style="color: var(--deep-blue); margin-bottom: 0.5rem;">Forgot Password?</h3>
                    <p style="color: var(--gray-600); font-size: 0.9rem;">
                        Enter your email address and we'll send you a link to reset your password.
                    </p>
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-input" required>
                    <div class="error-message" id="emailError"></div>
                    <div class="success-message" id="successMessage"></div>
                </div>

                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <span class="btn-text">Send Reset Link</span>
                </button>
            </form>

            <div class="back-link">
                <a href="{{ route('login') }}">← Back to Login</a>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('forgotForm');
        const submitBtn = document.getElementById('submitBtn');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(form);
            const data = Object.fromEntries(formData);
            
            setLoading(true);
            clearMessages();

            try {
                const response = await fetch('/forgot-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    document.getElementById('successMessage').textContent = result.message;
                    form.reset();
                } else {
                    showErrors(result.errors);
                }
            } catch (error) {
                showErrors({ email: ['Failed to send reset link. Please try again.'] });
            } finally {
                setLoading(false);
            }
        });

        function setLoading(loading) {
            const btnText = submitBtn.querySelector('.btn-text');
            if (loading) {
                submitBtn.disabled = true;
                btnText.innerHTML = '<div class="spinner"></div> Sending...';
            } else {
                submitBtn.disabled = false;
                btnText.textContent = 'Send Reset Link';
            }
        }

        function clearMessages() {
            document.getElementById('emailError').textContent = '';
            document.getElementById('successMessage').textContent = '';
            document.querySelector('input[name="email"]').classList.remove('error');
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
    </script>
</body>
</html>