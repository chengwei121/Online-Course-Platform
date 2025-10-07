@extends('layouts.admin')

@section('title', 'Edit Teacher')

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-user-edit me-2"></i>Edit Teacher
    </h1>
    <a href="{{ route('admin.teachers.show', $teacher) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>Back to Details
    </a>
</div>
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-lg border-0">
            <div class="card-header py-4 bg-light border-bottom">
                <h6 class="m-0 font-weight-bold text-secondary">
                    <i class="fas fa-user-circle me-2"></i>
                    Edit Teacher Information
                </h6>
            </div>
            <div class="card-body p-5">
                <form action="{{ route('admin.teachers.update', $teacher) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-3">
                        <!-- Personal Information -->
                        <div class="col-md-6">
                            <h5 class="mb-3 text-primary border-bottom pb-2">
                                <i class="fas fa-user me-2"></i>Personal Information
                            </h5>
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $teacher->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $teacher->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number (Malaysia) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">🇲🇾 +60</span>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $teacher->phone) }}" 
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
                                @if($teacher->profile_picture)
                                    <div class="mb-2">
                                        <img src="{{ Storage::url($teacher->profile_picture) }}" 
                                             class="rounded" width="100" height="100" alt="Current profile picture">
                                        <p class="text-muted small">Current profile picture</p>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('profile_picture') is-invalid @enderror" 
                                       id="profile_picture" name="profile_picture" accept="image/*">
                                @error('profile_picture')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Leave empty to keep current picture. Accepted formats: JPEG, PNG, JPG, GIF. Max size: 2MB</small>
                            </div>
                        </div>

                        <!-- Professional Information -->
                        <div class="col-md-6">
                            <h5 class="mb-3 text-primary">
                                <i class="fas fa-graduation-cap me-2"></i>Professional Information
                            </h5>
                            
                            <div class="mb-3">
                                <label for="qualification" class="form-label">Qualification <span class="text-danger">*</span></label>
                                <select class="form-select @error('qualification') is-invalid @enderror" 
                                        id="qualification" name="qualification" required>
                                    <option value="">Select Qualification</option>
                                    <option value="Certificate" {{ old('qualification', $teacher->qualification) === 'Certificate' ? 'selected' : '' }}>Certificate</option>
                                    <option value="Diploma" {{ old('qualification', $teacher->qualification) === 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                    <option value="Bachelor's Degree" {{ old('qualification', $teacher->qualification) === "Bachelor's Degree" ? 'selected' : '' }}>Bachelor's Degree</option>
                                    <option value="Master's Degree" {{ old('qualification', $teacher->qualification) === "Master's Degree" ? 'selected' : '' }}>Master's Degree</option>
                                    <option value="PhD/Doctorate" {{ old('qualification', $teacher->qualification) === 'PhD/Doctorate' ? 'selected' : '' }}>PhD/Doctorate</option>
                                    <option value="Professional Certification" {{ old('qualification', $teacher->qualification) === 'Professional Certification' ? 'selected' : '' }}>Professional Certification</option>
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
                                    <option value="Information Technology" {{ old('department', $teacher->department) === 'Information Technology' ? 'selected' : '' }}>Information Technology</option>
                                    <option value="Computer Science" {{ old('department', $teacher->department) === 'Computer Science' ? 'selected' : '' }}>Computer Science</option>
                                    <option value="Software Engineering" {{ old('department', $teacher->department) === 'Software Engineering' ? 'selected' : '' }}>Software Engineering</option>
                                    <option value="Information Systems" {{ old('department', $teacher->department) === 'Information Systems' ? 'selected' : '' }}>Information Systems</option>
                                    <option value="Computer Engineering" {{ old('department', $teacher->department) === 'Computer Engineering' ? 'selected' : '' }}>Computer Engineering</option>
                                    <option value="Cybersecurity" {{ old('department', $teacher->department) === 'Cybersecurity' ? 'selected' : '' }}>Cybersecurity</option>
                                    <option value="Data Science" {{ old('department', $teacher->department) === 'Data Science' ? 'selected' : '' }}>Data Science</option>
                                    <option value="Artificial Intelligence" {{ old('department', $teacher->department) === 'Artificial Intelligence' ? 'selected' : '' }}>Artificial Intelligence</option>
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
                                           id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate', $teacher->hourly_rate) }}" 
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
                                    <option value="active" {{ old('status', $teacher->status) === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $teacher->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Account Information -->
                    <hr>
                    <h5 class="mb-3 text-primary">
                        <i class="fas fa-key me-2"></i>Account Credentials
                    </h5>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Leave empty to keep current password</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>
                    </div>

                    <!-- Bio -->
                    <div class="mb-4">
                        <label for="bio" class="form-label">Biography</label>
                        <textarea class="form-control @error('bio') is-invalid @enderror" 
                                  id="bio" name="bio" rows="4" 
                                  placeholder="Brief description about the teacher...">{{ old('bio', $teacher->bio) }}</textarea>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.teachers.show', $teacher) }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i>Update Teacher
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Preview profile picture
    document.getElementById('profile_picture').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // You can add image preview functionality here
                console.log('New image selected:', file.name);
            };
            reader.readAsDataURL(file);
        }
    });

    // Malaysian phone number formatting and validation
    document.getElementById('phone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, ''); // Remove non-digits
        
        // Format as XX-XXX-XXXX
        if (value.length >= 2) {
            value = value.substring(0, 2) + '-' + value.substring(2);
        }
        if (value.length >= 6) {
            value = value.substring(0, 6) + '-' + value.substring(6, 10);
        }
        
        e.target.value = value;
        
        // Validation for Malaysian numbers
        const isValid = /^(1[0-9]|01[0-9])-[0-9]{3,4}-[0-9]{4}$/.test(value);
        
        if (value.length > 0 && !isValid && value.length >= 11) {
            e.target.setCustomValidity('Please enter a valid Malaysian phone number (e.g., 12-345-6789)');
        } else {
            e.target.setCustomValidity('');
        }
    });

    // Form validation before submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const phone = document.getElementById('phone').value;
        const phonePattern = /^(0[1-9][0-9]?|1[0-9])-[0-9]{3,4}-[0-9]{4}$/;
        
        if (phone && !phonePattern.test(phone)) {
            e.preventDefault();
            alert('Please enter a valid Malaysian phone number format: XX-XXX-XXXX');
            document.getElementById('phone').focus();
            return false;
        }
    });
</script>

<style>
/* Enhanced form styling */
.input-group-text {
    background-color: #f8f9fc;
    border-color: #d1d3e2;
    color: #5a5c69;
    font-weight: 500;
}

.form-select:focus, .form-control:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

/* Phone number specific styling */
#phone {
    font-family: 'Courier New', monospace;
    letter-spacing: 1px;
}

/* Flag emoji styling */
.input-group-text {
    font-size: 14px;
}

/* Custom validation styling */
.is-invalid {
    border-color: #e74a3b !important;
}

.invalid-feedback {
    color: #e74a3b;
    font-size: 0.875rem;
}

/* Form section headers */
h5.text-primary {
    border-bottom: 2px solid #4e73df;
    padding-bottom: 8px;
    margin-bottom: 20px !important;
}

/* Card styling improvements */
.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px 10px 0 0 !important;
}

/* Button improvements */
.btn-success {
    background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
    border: none;
    transition: all 0.2s ease;
}

.btn-success:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(28, 200, 138, 0.4);
}

.btn-secondary {
    transition: all 0.2s ease;
}

.btn-secondary:hover {
    transform: translateY(-1px);
}
</style>
@endpush
