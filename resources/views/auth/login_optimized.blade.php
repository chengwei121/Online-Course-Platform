@extends('layouts.client')

@section('title', 'Login')

@push('styles')
<style>
    /* Optimized login page styles */
    .login-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
    }
    
    .login-card {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }
    
    .login-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
    }
    
    .input-group {
        position: relative;
        margin-bottom: 1.5rem;
    }
    
    .form-input {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 0.75rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #fff;
    }
    
    .form-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        transform: translateY(-1px);
    }
    
    .form-input.error {
        border-color: #ef4444;
        background-color: #fef2f2;
    }
    
    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .login-btn {
        width: 100%;
        padding: 0.875rem 1.5rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 0.75rem;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .login-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    }
    
    .login-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }
    
    .btn-loading {
        background: #9ca3af;
    }
    
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
    
    .remember-checkbox {
        appearance: none;
        width: 1.25rem;
        height: 1.25rem;
        border: 2px solid #d1d5db;
        border-radius: 0.375rem;
        position: relative;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .remember-checkbox:checked {
        background: #667eea;
        border-color: #667eea;
    }
    
    .remember-checkbox:checked::after {
        content: '✓';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 0.875rem;
        font-weight: bold;
    }
    
    .register-link {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        color: #667eea;
        border: 2px solid #667eea;
        transition: all 0.3s ease;
    }
    
    .register-link:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        transform: translateY(-2px);
    }
    
    /* Performance optimizations */
    .gpu-accelerated {
        transform: translateZ(0);
        backface-visibility: hidden;
        perspective: 1000px;
    }
    
    /* Mobile optimizations */
    @media (max-width: 640px) {
        .login-card {
            margin: 1rem;
            padding: 2rem 1.5rem;
        }
        
        .form-input {
            font-size: 16px; /* Prevent zoom on iOS */
        }
    }
    
    /* Reduce motion for accessibility */
    @media (prefers-reduced-motion: reduce) {
        * {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }
</style>
@endpush

@section('content')
<div class="login-container flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <!-- Header -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-white mb-2">
                Welcome Back
            </h2>
            <p class="text-indigo-100">
                Sign in to continue your learning journey
            </p>
        </div>

        <!-- Login Card -->
        <div class="login-card rounded-2xl p-8 gpu-accelerated">
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
            <form id="loginForm" class="space-y-6" action="{{ route('login') }}" method="POST">
                @csrf

                <!-- Email Field -->
                <div class="input-group">
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
                <div class="input-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="relative">
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            autocomplete="current-password" 
                            required
                            class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                            placeholder="Enter your password">
                        
                        <!-- Password Toggle -->
                        <button type="button" id="togglePassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
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

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input 
                            id="remember_me" 
                            name="remember" 
                            type="checkbox"
                            class="remember-checkbox">
                        <label for="remember_me" class="ml-3 text-sm text-gray-700 font-medium">
                            Remember me
                        </label>
                    </div>
                    
                    @if($canResetPassword ?? true)
                        <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" id="loginBtn" class="login-btn">
                        <span id="loginBtnText">Sign In</span>
                        <span id="loginBtnLoading" class="hidden">
                            <span class="loading-spinner"></span>
                            Signing in...
                        </span>
                    </button>
                </div>
            </form>

            <!-- Divider -->
            <div class="mt-8">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500 font-medium">
                            Don't have an account?
                        </span>
                    </div>
                </div>

                <!-- Register Link -->
                <div class="mt-6">
                    <a href="{{ route('register') }}" class="register-link w-full flex justify-center py-2.5 px-4 rounded-lg text-sm font-semibold transition-all duration-300">
                        Create Account
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Performance optimizations
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
    
    // Enhanced form submission with AJAX
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
                
                // Submit via fetch for better performance
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Show success state
                    loginBtnText.textContent = 'Success!';
                    loginBtn.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
                    
                    // Redirect after short delay
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 500);
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const input = form.querySelector(`[name="${field}"]`);
                            if (input) {
                                showFieldError(input, data.errors[field][0]);
                            }
                        });
                    } else if (data.message) {
                        showFieldError(emailInput, data.message);
                    }
                    
                    setLoadingState(false);
                    isSubmitting = false;
                }
            } catch (error) {
                console.error('Login error:', error);
                showFieldError(emailInput, 'Connection error. Please try again.');
                setLoadingState(false);
                isSubmitting = false;
            }
        });
    }
    
    function setLoadingState(loading) {
        if (loading) {
            loginBtn.disabled = true;
            loginBtn.classList.add('btn-loading');
            loginBtnText.classList.add('hidden');
            loginBtnLoading.classList.remove('hidden');
        } else {
            loginBtn.disabled = false;
            loginBtn.classList.remove('btn-loading');
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
        
        // Insert after input or input container
        const container = input.closest('.input-group') || input.parentNode;
        container.appendChild(errorDiv);
    }
    
    // Auto-focus first empty field
    if (emailInput && !emailInput.value) {
        emailInput.focus();
    } else if (passwordInput && !passwordInput.value) {
        passwordInput.focus();
    }
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Enter key to submit (if not already focused on submit button)
        if (e.key === 'Enter' && document.activeElement !== loginBtn) {
            e.preventDefault();
            form.dispatchEvent(new Event('submit'));
        }
    });
});
</script>
@endpush

@endsection
