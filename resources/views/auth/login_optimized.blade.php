@extends('layouts.client')

@section('title', 'Login')

@push('styles')
<style>
    /* Login page styles with light grey background */
    .login-container {
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 50%, #e5e7eb 100%);
        min-height: 100vh;
        padding: 5rem 1rem 2rem;
        position: relative;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        box-sizing: border-box;
        overflow-x: hidden;
        padding-top: 12vh;
    }
    
    .login-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(156, 163, 175, 0.02) 0%, rgba(209, 213, 219, 0.03) 100%);
        pointer-events: none;
    }
    
    /* Subtle dot pattern */
    .login-container::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: radial-gradient(circle at 2px 2px, rgba(156, 163, 175, 0.06) 1px, transparent 0);
        background-size: 40px 40px;
        pointer-events: none;
        opacity: 0.4;
    }
    
    .login-wrapper {
        max-width: 450px;
        width: 100%;
        position: relative;
        z-index: 1;
        margin: 3rem auto 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
    }

    
    /* Login card */
    .login-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 1rem;
        padding: 1.75rem 2.25rem;
        box-shadow: 
            0 8px 25px rgba(45, 55, 72, 0.15),
            0 4px 10px rgba(26, 32, 44, 0.1);
        border: 1px solid rgba(45, 55, 72, 0.1);
        transition: none;
        width: 100%;
        max-width: 420px;
        position: relative;
        overflow: hidden;
        margin: 0 auto;
        box-sizing: border-box;
    }

    
    .login-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, 
            transparent, 
            #2d3748 20%,
            #1a202c 50%,
            #2d3748 80%,
            transparent);
        opacity: 1;
    }
    
    .login-card::after {
        display: none;
    }
    
    /* Logo and title inside card */
    .card-header {
        text-align: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(229, 231, 235, 0.3);
        position: relative;
    }
    
    .card-header::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 2px;
        background: linear-gradient(90deg, 
            #2d3748, 
            #1a202c);
        border-radius: 1px;
    }
    
    .card-logo {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
        border-radius: 0.625rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.75rem;
        box-shadow: 
            0 4px 12px rgba(45, 55, 72, 0.3),
            0 2px 6px rgba(26, 32, 44, 0.2);
        transition: none;
        position: relative;
        overflow: hidden;
    }
    
    .card-logo::before {
        display: none;
    }

    
    .card-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        letter-spacing: -0.03em;
        line-height: 1.2;
        background: linear-gradient(135deg, #1f2937 0%, #374151 50%, #4b5563 100%);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        position: relative;
    }
    
    .card-title::after {
        content: attr(data-text);
        position: absolute;
        top: 0;
        left: 0;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
    }
    
    /* Form styles */
    .form-group {
        margin-bottom: 1rem;
        position: relative;
    }
    
    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.375rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-family: 'Inter', sans-serif;
    }
    
    .form-input {
        width: 100%;
        padding: 0.875rem 1.25rem;
        border: 2px solid #e5e7eb;
        border-radius: 0.75rem;
        font-size: 1rem;
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(8px);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        color: #374151;
        font-family: inherit;
        position: relative;
        z-index: 1;
    }
    
    .form-input::placeholder {
        color: #9ca3af;
        transition: color 0.3s ease;
    }
    
    .form-input:focus {
        outline: none;
        border-color: #2d3748;
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 
            0 0 0 3px rgba(45, 55, 72, 0.15),
            0 4px 8px -2px rgba(26, 32, 44, 0.1);
    }
    
    .form-input:focus::placeholder {
        color: #d1d5db;
    }
    
    .form-input.error {
        border-color: #ef4444;
        background-color: #fef2f2;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }
    
    /* Password input with icon */
    .password-wrapper {
        position: relative;
    }
    
    .form-input.has-icon {
        padding-right: 3rem;
    }
    
    .password-toggle {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        padding: 0.5rem;
        color: #9ca3af;
        transition: color 0.2s ease;
        border-radius: 0.375rem;
    }
    
    .password-toggle:hover {
        color: #374151;
        background-color: #f3f4f6;
    }
    
    .password-toggle:focus {
        outline: none;
        color: #374151;
    }
    
    /* Submit button */
    .submit-btn {
        width: 100%;
        padding: 0.875rem 1.5rem;
        background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
        color: white;
        border: none;
        border-radius: 0.625rem;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: background 0.2s ease;
        position: relative;
        overflow: hidden;
        box-shadow: 
            0 4px 12px rgba(45, 55, 72, 0.3),
            0 2px 6px rgba(26, 32, 44, 0.2);
        font-family: 'Inter', sans-serif;
        letter-spacing: 0.025em;
        margin-top: 0.25rem;
    }
    
    .submit-btn::before {
        display: none;
    }
    
    .submit-btn:hover:not(:disabled) {
        background: linear-gradient(135deg, #1a252f 0%, #2d3748 100%);
    }

    
    .submit-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }
    
    /* Loading spinner */
    .loading-spinner {
        display: inline-block;
        width: 1.25rem;
        height: 1.25rem;
        border: 2px solid transparent;
        border-top: 2px solid #ffffff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-right: 0.5rem;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Remember me section */
    .remember-section {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 1rem 0;
    }
    
    .remember-wrapper {
        display: flex;
        align-items: center;
    }
    
    .custom-checkbox {
        appearance: none;
        width: 1.25rem;
        height: 1.25rem;
        border: 2px solid #d1d5db;
        border-radius: 0.375rem;
        position: relative;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-right: 0.75rem;
    }
    
    .custom-checkbox:checked {
        background: #374151;
        border-color: #374151;
    }
    
    .custom-checkbox:checked::after {
        content: '✓';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 0.875rem;
        font-weight: bold;
    }
    
    .checkbox-label {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 500;
        cursor: pointer;
    }
    
    .forgot-link {
        font-size: 0.875rem;
        color: #6b7280;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s ease;
    }
    
    .forgot-link:hover {
        color: #374151;
        text-decoration: underline;
    }
    
    /* Divider */
    .divider {
        margin: 1.25rem 0;
        position: relative;
        text-align: center;
    }
    
    .divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: #e5e7eb;
        z-index: 1;
    }
    
    .divider-text {
        background: rgba(255, 255, 255, 0.95);
        color: #9ca3af;
        font-size: 0.875rem;
        padding: 0 1rem;
        font-weight: 500;
        position: relative;
        z-index: 2;
        display: inline-block;
    }
    
    /* Register link */
    .register-btn {
        width: 100%;
        padding: 0.875rem 1.5rem;
        background: rgba(255, 255, 255, 0.8);
        color: #2d3748;
        border: 2px solid #2d3748;
        border-radius: 0.625rem;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-family: 'Inter', sans-serif;
        letter-spacing: 0.025em;
        position: relative;
        overflow: hidden;
    }
    
    .register-btn::before {
        display: none;
    }
    
    .register-btn:hover {
        background: #2d3748;
        color: white;
        text-decoration: none;
    }
    
    /* Error messages */
    .error-message {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        opacity: 0;
        transform: translateY(-10px);
        animation: slideIn 0.3s ease forwards;
    }
    
    @keyframes slideIn {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .success-message {
        color: #10b981;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
    }
    
    /* Responsive design */
    @media (max-width: 640px) {
        .login-container {
            padding: 3rem 0.75rem 1rem;
            min-height: 100vh;
            align-items: flex-start;
            padding-top: 10vh;
        }
        
        .login-wrapper {
            justify-content: flex-start;
            max-width: 100%;
            min-height: auto;
            margin: 2rem auto 0;
        }
        
        .login-card {
            padding: 1.5rem 1.75rem;
            max-width: 100%;
            margin: 0;
            border-radius: 1rem;
        }
        
        .form-input {
            font-size: 16px; /* Prevent zoom on iOS */
            padding: 0.875rem 1rem;
        }
        
        .card-header {
            margin-bottom: 1rem;
            padding-bottom: 1rem;
        }
        
        .card-logo {
            width: 36px;
            height: 36px;
            margin-bottom: 0.75rem;
        }
        
        .card-title {
            font-size: 1.375rem;
        }
        
        .submit-btn, .register-btn {
            padding: 0.875rem 1.25rem;
        }
        
        .form-group {
            margin-bottom: 0.875rem;
        }
        
        .remember-section {
            margin: 0.75rem 0;
        }
        
        .divider {
            margin: 1rem 0;
        }
    }
    
    @media (min-width: 641px) and (max-width: 1024px) {
        .login-container {
            padding: 4rem 1.5rem 2rem;
            padding-top: 10vh;
        }
        
        .login-wrapper {
            max-width: 430px;
            margin: 2.5rem auto 0;
        }
        
        .card-logo {
            width: 38px;
            height: 38px;
        }
        
        .card-title {
            font-size: 1.25rem;
        }
        
        .login-card {
            padding: 1.75rem 2rem;
            max-width: 400px;
        }
    }
    
    @media (min-width: 1025px) {
        .login-card {
            max-width: 420px;
        }
        
        .login-wrapper {
            margin: 3.5rem auto 0;
        }
    }

</style>
@endpush

@section('content')
<div class="login-container">
    <div class="login-wrapper">
        <!-- Login Card -->
        <div class="login-card">
            <!-- Card Header with Logo -->
            <div class="card-header">
                <div class="card-logo">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h2 class="card-title" data-text="Login">Login</h2>
            </div>
            
            <!-- Status Messages -->
            @if(session('status'))
                <div class="success-message mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            <!-- Login Form -->
            <form id="loginForm" action="{{ route('login') }}" method="POST">
                @csrf

                <!-- Email Field -->
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        autocomplete="email" 
                        required
                        class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                        value="{{ old('email') }}"
                        placeholder="Enter your email address">
                    
                    @error('email')
                        <div class="error-message">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-wrapper">
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            autocomplete="current-password" 
                            required
                            class="form-input has-icon {{ $errors->has('password') ? 'error' : '' }}"
                            placeholder="Enter your password">
                        
                        <!-- Password Toggle -->
                        <button type="button" id="togglePassword" class="password-toggle">
                            <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    
                    @error('password')
                        <div class="error-message">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="remember-section">
                    <div class="remember-wrapper">
                        <input 
                            id="remember_me" 
                            name="remember" 
                            type="checkbox"
                            class="custom-checkbox">
                        <label for="remember_me" class="checkbox-label">
                            Remember me
                        </label>
                    </div>
                    
                    @if($canResetPassword ?? true)
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit" id="loginBtn" class="submit-btn">
                    <span id="loginBtnText">Sign In</span>
                    <span id="loginBtnLoading" class="hidden">
                        <span class="loading-spinner"></span>
                        Signing in...
                    </span>
                </button>
            </form>

            <!-- Divider -->
            <div class="divider">
                <span class="divider-text">Don't have an account?</span>
            </div>

            <!-- Register Link -->
            <a href="{{ route('register') }}" class="register-btn">
                Create Account
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');
    const loginBtnText = document.getElementById('loginBtnText');
    const loginBtnLoading = document.getElementById('loginBtnLoading');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const togglePasswordBtn = document.getElementById('togglePassword');
    
    let isSubmitting = false;
    
    // Password visibility toggle
    if (togglePasswordBtn) {
        togglePasswordBtn.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Update icon
            const eyeIcon = document.getElementById('eyeIcon');
            if (type === 'text') {
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.142 4.142M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                `;
            } else {
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
            }
        });
    }
    
    // Real-time validation
    const validateEmail = (email) => {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    };
    
    // Clear errors on input
    [emailInput, passwordInput].forEach(input => {
        if (input) {
            input.addEventListener('input', function() {
                this.classList.remove('error');
                const errorMsg = this.parentNode.querySelector('.error-message');
                if (errorMsg) {
                    errorMsg.remove();
                }
            });
        }
    });
    
    // Enhanced form submission
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (isSubmitting) return;
            
            // Basic client-side validation
            let hasErrors = false;
            
            if (!emailInput.value.trim()) {
                showFieldError(emailInput, 'Email is required');
                hasErrors = true;
            } else if (!validateEmail(emailInput.value.trim())) {
                showFieldError(emailInput, 'Please enter a valid email address');
                hasErrors = true;
            }
            
            if (!passwordInput.value.trim()) {
                showFieldError(passwordInput, 'Password is required');
                hasErrors = true;
            } else if (passwordInput.value.length < 6) {
                showFieldError(passwordInput, 'Password must be at least 6 characters');
                hasErrors = true;
            }
            
            if (hasErrors) return;
            
            // Start loading state
            setLoadingState(true);
            isSubmitting = true;
            
            try {
                // Prepare form data
                const formData = new FormData(form);
                
                // Debug: Log what we're sending
                console.log('Login attempt with:', {
                    email: formData.get('email'),
                    password: formData.get('password') ? '[HIDDEN]' : 'MISSING',
                    csrf: formData.get('_token') ? 'Present' : 'MISSING'
                });
                
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    signal: AbortSignal.timeout(10000) // 10 second timeout
                });
                
                // Handle all responses, including 422 validation errors
                const data = await response.json();
                
                // Debug: Log the response
                console.log('Server response:', {
                    status: response.status,
                    ok: response.ok,
                    data: data
                });
                
                // Enhanced debugging for 422 errors
                if (response.status === 422) {
                    console.log('422 Validation Error Details:', {
                        hasErrors: !!data.errors,
                        errors: data.errors,
                        hasMessage: !!data.message,
                        message: data.message,
                        fullData: data
                    });
                }
                
                if (response.ok && (data.success || response.redirected)) {
                    // Show success state
                    loginBtnText.textContent = 'Success!';
                    loginBtn.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
                    
                    // Redirect immediately for better UX
                    window.location.href = data.redirect || '/teacher/dashboard';
                } else {
                    // Handle validation errors or other errors
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const input = form.querySelector(`[name="${field}"]`);
                            if (input) {
                                showFieldError(input, data.errors[field][0]);
                            }
                        });
                    } else if (data.message) {
                        showFieldError(emailInput, data.message);
                    } else {
                        // Generic error message
                        showFieldError(emailInput, 'Login failed. Please check your credentials and try again.');
                    }
                    
                    setLoadingState(false);
                    isSubmitting = false;
                }
            } catch (error) {
                console.error('Login error:', error);
                console.error('Error details:', {
                    message: error.message,
                    stack: error.stack
                });
                
                // Show user-friendly error message
                showFieldError(emailInput, 'Connection error. Please check your internet connection and try again.');
                setLoadingState(false);
                isSubmitting = false;
            }
        });
    }
    
    function setLoadingState(loading) {
        if (loading) {
            loginBtn.disabled = true;
            loginBtnText.classList.add('hidden');
            loginBtnLoading.classList.remove('hidden');
        } else {
            loginBtn.disabled = false;
            loginBtnText.classList.remove('hidden');
            loginBtnLoading.classList.add('hidden');
        }
    }
    
    function showFieldError(input, message) {
        // Remove existing error
        const existingError = input.parentNode.querySelector('.error-message');
        if (existingError) {
            existingError.remove();
        }
        
        // Add error class
        input.classList.add('error');
        
        // Create error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.innerHTML = `
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            ${message}
        `;
        
        // Insert after input container
        const container = input.closest('.form-group') || input.parentNode;
        container.appendChild(errorDiv);
    }
    
    // Auto-focus first empty field
    if (emailInput && !emailInput.value) {
        emailInput.focus();
    } else if (passwordInput && !passwordInput.value) {
        passwordInput.focus();
    }
});
</script>
@endpush

@endsection
