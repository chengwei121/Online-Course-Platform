@extends('layouts.admin')

@section('title', 'Add New Administrator')

@section('content')
<div class="container-fluid px-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-plus me-2"></i>Add New Administrator
        </h1>
        <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Administrators
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <!-- Create Administrator Form -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-shield me-2"></i>Administrator Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note:</strong> Fill in all required fields to create a new administrator account. The new administrator will have full access to the admin panel.
                    </div>

                    <form action="{{ route('admin.admins.store') }}" method="POST" id="createAdminForm">
                        @csrf

                        <h6 class="text-dark mb-3">
                            <i class="fas fa-user me-2"></i>Basic Information
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user me-1"></i>Full Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       placeholder="Enter full name"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Enter the administrator's full name</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>Email Address <span class="text-danger">*</span>
                                </label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       placeholder="admin@example.com"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">This will be used for login</small>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h6 class="text-dark mb-3">
                            <i class="fas fa-lock me-2"></i>Password Settings
                        </h6>

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Security:</strong> Choose a strong password with at least 8 characters, including uppercase, lowercase, numbers, and special characters.
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-key me-1"></i>Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Enter password"
                                           autocomplete="new-password"
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">Minimum 8 characters</small>
                                <div id="passwordStrength" class="mt-2"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">
                                    <i class="fas fa-key me-1"></i>Confirm Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           placeholder="Confirm password"
                                           autocomplete="new-password"
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <small class="form-text text-muted">Re-enter the password</small>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-user-plus me-1"></i>Create Administrator
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
@endsection

@push('scripts')
<script>
// Toggle password visibility
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordField = document.getElementById('password');
    const icon = this.querySelector('i');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});

document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
    const passwordField = document.getElementById('password_confirmation');
    const icon = this.querySelector('i');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});

// Password strength checker
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthDiv = document.getElementById('passwordStrength');
    
    if (password.length === 0) {
        strengthDiv.innerHTML = '';
        return;
    }
    
    let strength = 0;
    let strengthText = '';
    let strengthClass = '';
    
    // Check password strength
    if (password.length >= 8) strength++;
    if (password.length >= 12) strength++;
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
    if (/\d/.test(password)) strength++;
    if (/[^a-zA-Z\d]/.test(password)) strength++;
    
    if (strength <= 2) {
        strengthText = 'Weak';
        strengthClass = 'text-danger';
    } else if (strength <= 3) {
        strengthText = 'Medium';
        strengthClass = 'text-warning';
    } else {
        strengthText = 'Strong';
        strengthClass = 'text-success';
    }
    
    strengthDiv.innerHTML = `<small class="${strengthClass}">Password Strength: <strong>${strengthText}</strong></small>`;
});

// Form validation
document.getElementById('createAdminForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const passwordConfirm = document.getElementById('password_confirmation').value;
    
    if (password !== passwordConfirm) {
        e.preventDefault();
        
        const confirmField = document.getElementById('password_confirmation');
        confirmField.classList.add('is-invalid');
        
        // Remove existing feedback
        const existingFeedback = confirmField.parentNode.querySelector('.invalid-feedback');
        if (existingFeedback) {
            existingFeedback.remove();
        }
        
        // Add new feedback
        const feedback = document.createElement('div');
        feedback.className = 'invalid-feedback d-block';
        feedback.textContent = 'Password confirmation does not match.';
        confirmField.parentNode.appendChild(feedback);
        
        // Scroll to error
        confirmField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        // Remove error styling after user starts typing
        confirmField.addEventListener('input', function() {
            this.classList.remove('is-invalid');
            const feedback = this.parentNode.querySelector('.invalid-feedback');
            if (feedback) feedback.remove();
        }, { once: true });
        
        return false;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Creating...';
});

// Real-time password confirmation validation
document.getElementById('password_confirmation').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    
    if (confirmPassword.length > 0) {
        if (password === confirmPassword) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        } else {
            this.classList.remove('is-valid');
            this.classList.add('is-invalid');
        }
    } else {
        this.classList.remove('is-valid', 'is-invalid');
    }
});
</script>

<style>
/* Card styling */
.card {
    border: none;
    border-radius: 10px;
    transition: box-shadow 0.2s ease;
}

.card:hover {
    box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.15) !important;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.card-header.bg-warning {
    background: linear-gradient(135deg, #f6c23e 0%, #f4b41a 100%) !important;
}

/* Form improvements */
.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.form-control.is-valid {
    border-color: #1cc88a;
}

.form-control.is-invalid {
    border-color: #e74a3b;
}

/* Button styling */
.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    transition: all 0.2s ease;
}

.btn-primary:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-primary:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

/* Alert styling */
.alert-info {
    border-left: 4px solid #36b9cc;
    background-color: rgba(54, 185, 204, 0.1);
    border-color: rgba(54, 185, 204, 0.2);
}

.alert-warning {
    border-left: 4px solid #f6c23e;
    background-color: rgba(246, 194, 62, 0.1);
    border-color: rgba(246, 194, 62, 0.2);
}

/* Switch styling */
.form-check-input:checked {
    background-color: #1cc88a;
    border-color: #1cc88a;
}

/* Input group button styling */
.input-group .btn-outline-secondary {
    border-color: #d1d3e2;
}

.input-group .btn-outline-secondary:hover {
    background-color: #f8f9fc;
    border-color: #d1d3e2;
    color: #858796;
}

/* Border styling for special cards */
.border-left-warning {
    border-left: 4px solid #f6c23e !important;
}

/* List styling */
ul li {
    padding: 0.25rem 0;
}

/* Required field indicator */
.text-danger {
    font-weight: bold;
}

/* Small text styling */
.form-text {
    font-size: 0.875rem;
}
</style>
@endpush
