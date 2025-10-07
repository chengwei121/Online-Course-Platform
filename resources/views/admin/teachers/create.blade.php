@extends('layouts.admin')

@section('title', 'Add New Teacher')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-user-plus me-2"></i>Add New Teacher
    </h1>
    <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>Back to Teachers
    </a>
</div>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow border-0">
            <div class="card-header py-3 bg-secondary text-white border-bottom">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-user-circle me-2"></i>
                    Teacher Information
                </h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.teachers.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row g-3">
                        <!-- Personal Information -->
                        <div class="col-md-6">
                            <h5 class="mb-3 text-primary border-bottom pb-2">
                                <i class="fas fa-user me-2"></i>Personal Information
                            </h5>
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number (Malaysia) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">🇲🇾 +60</span>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}" 
                                           placeholder="12-345-6789" pattern="[0-9]{2,3}-[0-9]{3,4}-[0-9]{4}" 
                                           title="Please enter a valid Malaysian phone number (e.g., 12-345-6789)" required>
                                </div>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Format: XX-XXX-XXXX (without +60 prefix)</small>
                            </div>

                            <div class="mb-3">
                                <label for="profile_picture" class="form-label">Profile Picture</label>
                                <input type="file" class="form-control @error('profile_picture') is-invalid @enderror" 
                                       id="profile_picture" name="profile_picture" accept="image/*">
                                @error('profile_picture')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Accepted formats: JPEG, PNG, JPG, GIF. Max size: 2MB</small>
                            </div>
                        </div>

                        <!-- Professional Information -->
                        <div class="col-md-6">
                            <h5 class="mb-3 text-primary border-bottom pb-2">
                                <i class="fas fa-graduation-cap me-2"></i>Professional Information
                            </h5>
                            
                            <div class="mb-3">
                                <label for="qualification" class="form-label">Qualification <span class="text-danger">*</span></label>
                                <select class="form-select @error('qualification') is-invalid @enderror" 
                                        id="qualification" name="qualification" required>
                                    <option value="">Select Qualification</option>
                                    <option value="Certificate" {{ old('qualification') === 'Certificate' ? 'selected' : '' }}>Certificate</option>
                                    <option value="Diploma" {{ old('qualification') === 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                    <option value="Bachelor's Degree" {{ old('qualification') === "Bachelor's Degree" ? 'selected' : '' }}>Bachelor's Degree</option>
                                    <option value="Master's Degree" {{ old('qualification') === "Master's Degree" ? 'selected' : '' }}>Master's Degree</option>
                                    <option value="PhD/Doctorate" {{ old('qualification') === 'PhD/Doctorate' ? 'selected' : '' }}>PhD/Doctorate</option>
                                    <option value="Professional Certification" {{ old('qualification') === 'Professional Certification' ? 'selected' : '' }}>Professional Certification</option>
                                </select>
                                @error('qualification')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="department" class="form-label">Department <span class="text-danger">*</span></label>
                                <select class="form-select @error('department') is-invalid @enderror" 
                                        id="department" name="department" required>
                                    <option value="">Select Department</option>
                                    <option value="Information Technology" {{ old('department') === 'Information Technology' ? 'selected' : '' }}>Information Technology</option>
                                    <option value="Computer Science" {{ old('department') === 'Computer Science' ? 'selected' : '' }}>Computer Science</option>
                                    <option value="Software Engineering" {{ old('department') === 'Software Engineering' ? 'selected' : '' }}>Software Engineering</option>
                                    <option value="Information Systems" {{ old('department') === 'Information Systems' ? 'selected' : '' }}>Information Systems</option>
                                    <option value="Computer Engineering" {{ old('department') === 'Computer Engineering' ? 'selected' : '' }}>Computer Engineering</option>
                                    <option value="Cybersecurity" {{ old('department') === 'Cybersecurity' ? 'selected' : '' }}>Cybersecurity</option>
                                    <option value="Data Science" {{ old('department') === 'Data Science' ? 'selected' : '' }}>Data Science</option>
                                    <option value="Artificial Intelligence" {{ old('department') === 'Artificial Intelligence' ? 'selected' : '' }}>Artificial Intelligence</option>
                                </select>
                                @error('department')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="hourly_rate" class="form-label">Hourly Rate (RM)</label>
                                <div class="input-group">
                                    <span class="input-group-text">RM</span>
                                    <input type="number" class="form-control @error('hourly_rate') is-invalid @enderror" 
                                           id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate') }}" 
                                           min="0" step="0.01" placeholder="0.00">
                                </div>
                                @error('hourly_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Optional: Teaching rate in Malaysian Ringgit per hour</small>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Account Information -->
                    <hr class="my-4">
                    <h5 class="mb-3 text-primary border-bottom pb-2">
                        <i class="fas fa-key me-2"></i>Account Credentials
                    </h5>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>
                    </div>

                    <!-- Bio -->
                    <div class="mb-4">
                        <label for="bio" class="form-label">Biography</label>
                        <textarea class="form-control @error('bio') is-invalid @enderror" 
                                  id="bio" name="bio" rows="4" 
                                  placeholder="Brief description about the teacher...">{{ old('bio') }}</textarea>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Maximum 1000 characters</small>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i>Create Teacher Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Malaysian phone number formatting and validation
    document.getElementById('phone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        
        // Format as XX-XXX-XXXX or XXX-XXX-XXXX
        if (value.length >= 6) {
            if (value.length <= 7) {
                value = value.replace(/(\d{2})(\d{3})(\d+)/, '$1-$2-$3');
            } else if (value.length <= 8) {
                value = value.replace(/(\d{2})(\d{3})(\d{3})/, '$1-$2-$3');
            } else if (value.length <= 9) {
                value = value.replace(/(\d{2})(\d{3})(\d{4})/, '$1-$2-$3');
            } else if (value.length <= 10) {
                value = value.replace(/(\d{2})(\d{4})(\d{4})/, '$1-$2-$3');
            } else if (value.length === 11) {
                value = value.replace(/(\d{3})(\d{4})(\d{4})/, '$1-$2-$3');
            }
        } else if (value.length >= 3) {
            value = value.replace(/(\d{2})(\d+)/, '$1-$2');
        }
        
        e.target.value = value;
        
        // Validation
        const phonePattern = /^(0[1-9][0-9]?|1[0-9])-[0-9]{3,4}-[0-9]{4}$/;
        if (value && !phonePattern.test(value)) {
            e.target.setCustomValidity('Please enter a valid Malaysian phone number (e.g., 12-345-6789)');
        } else {
            e.target.setCustomValidity('');
        }
    });
    
    // Form submission validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const phone = document.getElementById('phone').value;
        const phonePattern = /^(0[1-9][0-9]?|1[0-9])-[0-9]{3,4}-[0-9]{4}$/;
        
        if (phone && !phonePattern.test(phone)) {
            e.preventDefault();
            alert('Please enter a valid Malaysian phone number format: XX-XXX-XXXX');
            document.getElementById('phone').focus();
            return false;
        }
        
        // Check if all required fields are filled
        const requiredFields = ['name', 'email', 'phone', 'qualification', 'department', 'password', 'password_confirmation', 'status'];
        for (let field of requiredFields) {
            const element = document.getElementById(field);
            if (!element.value.trim()) {
                e.preventDefault();
                alert(`Please fill in the ${field.replace('_', ' ')} field.`);
                element.focus();
                return false;
            }
        }
        
        // Check password confirmation
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Password and confirmation password do not match.');
            document.getElementById('password_confirmation').focus();
            return false;
        }
    });
});
</script>

<style>
/* Custom styling for the teacher form */
.card {
    border-radius: 10px;
}

.card-header {
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
}

.form-control:focus,
.form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.border-bottom {
    border-bottom: 2px solid #dee2e6 !important;
}

/* Phone number specific styling */
#phone {
    font-family: 'Courier New', monospace;
    letter-spacing: 1px;
}

.input-group-text {
    font-weight: 500;
}

/* Submit button styling */
.btn-success {
    background-color: #28a745;
    border-color: #28a745;
}

.btn-success:hover {
    background-color: #218838;
    border-color: #1e7e34;
}

/* Error styling */
.is-invalid {
    border-color: #dc3545;
}

.invalid-feedback {
    color: #dc3545;
    font-size: 0.875rem;
}

/* Helper text styling */
.form-text {
    font-size: 0.875rem;
}
</style>
@endsection