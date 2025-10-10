@extends('layouts.admin')

@section('titl                        
                    <div class="row g-2">
                        <!-- Personal Information -->
                        <div class="col-md-6">
                            <h6 class="mb-2 text-primary border-bottom pb-2">
                                <i class="fas fa-user me-2"></i>Personal Information
                            </h6>
                            
                            <div class="mb-2">       <div class="row g-2">
                        <!-- Personal Information -->
                        <div class="col-12">
                            <h6 class="mb-2 text-primary border-bottom pb-2">
                                <i class="fas fa-user me-2"></i>Personal Information
                            </h6>dit Teacher')

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
    <div class="col-lg-11 col-xl-10">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header py-3 border-0">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="header-icon-wrapper me-3">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <div>
                            <h5 class="m-0 text-white fw-bold">Edit Teacher Information</h5>
                            <small class="text-white-50">Update teacher profile and credentials</small>
                        </div>
                    </div>
                    <div class="header-badge">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                </div>
            </div>
            <div class="card-body p-3">
                <form action="{{ route('admin.teachers.update', $teacher) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-2">
                        <!-- Personal Information -->
                        <div class="col-md-6">
                            <h5 class="mb-3 text-primary border-bottom pb-2">
                                <i class="fas fa-user me-2"></i>Personal Information
                            </h5>
                            
                            <div class="mb-2">
                                <label for="name" class="form-label form-label-sm">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $teacher->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-2">
                                <label for="email" class="form-label form-label-sm">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $teacher->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-2">
                                <label for="phone" class="form-label form-label-sm">Phone Number (Malaysia) <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
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

                            <div class="mb-2">
                                <label for="profile_picture" class="form-label form-label-sm">Profile Picture</label>
                                @if($teacher->profile_picture)
                                    <div class="mb-2">
                                        <img src="{{ Storage::url($teacher->profile_picture) }}" 
                                             class="rounded" width="100" height="100" alt="Current profile picture">
                                        <p class="text-muted small">Current profile picture</p>
                                    </div>
                                @endif
                                <input type="file" class="form-control form-control-sm @error('profile_picture') is-invalid @enderror" 
                                       id="profile_picture" name="profile_picture" accept="image/*">
                                @error('profile_picture')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Max size: 2MB</small>
                            </div>
                        </div>

                        <!-- Professional Information -->
                        <div class="col-md-6">
                            <h6 class="mb-2 text-primary border-bottom pb-2">
                                <i class="fas fa-graduation-cap me-2"></i>Professional Information
                            </h6>
                            
                            <div class="mb-2">
                                <label for="qualification" class="form-label form-label-sm">Qualification <span class="text-danger">*</span></label>
                                <select class="form-select form-select-sm @error('qualification') is-invalid @enderror" 
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

                            <div class="mb-2">
                                <label for="department" class="form-label form-label-sm">Department <span class="text-danger">*</span></label>
                                <select class="form-select form-select-sm @error('department') is-invalid @enderror" 
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

                            <div class="mb-2">
                                <label for="hourly_rate" class="form-label form-label-sm">Hourly Rate (RM)</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">RM</span>
                                    <input type="number" class="form-control @error('hourly_rate') is-invalid @enderror" 
                                           id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate', $teacher->hourly_rate) }}" 
                                           min="0" step="0.01" placeholder="0.00">
                                </div>
                                @error('hourly_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-2">
                                <label for="status" class="form-label form-label-sm">Status <span class="text-danger">*</span></label>
                                <select class="form-select form-select-sm @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="active" {{ old('status', $teacher->status) === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $teacher->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    
                        <!-- Account Information -->
                        <div class="col-md-6">
                            <h6 class="mb-2 text-primary border-bottom pb-2">
                                <i class="fas fa-key me-2"></i>Account Credentials
                            </h6>
                            
                            <div class="mb-2">
                                <label for="password" class="form-label form-label-sm">New Password</label>
                                <input type="password" class="form-control form-control-sm @error('password') is-invalid @enderror" 
                                       id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Leave empty to keep current</small>
                            </div>
                            
                            <div class="mb-2">
                                <label for="password_confirmation" class="form-label form-label-sm">Confirm Password</label>
                                <input type="password" class="form-control form-control-sm" 
                                       id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>

                        <!-- Bio -->
                        <div class="col-md-6">
                            <h6 class="mb-2 text-primary border-bottom pb-2">
                                <i class="fas fa-file-alt me-2"></i>Biography
                            </h6>
                            
                            <div class="mb-2">
                                <label for="bio" class="form-label form-label-sm">About</label>
                                <textarea class="form-control form-control-sm @error('bio') is-invalid @enderror" 
                                          id="bio" name="bio" rows="7" 
                                          placeholder="Brief description about the teacher...">{{ old('bio', $teacher->bio) }}</textarea>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('admin.teachers.show', $teacher) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-times me-1"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-success btn-sm">
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
// Use DOMContentLoaded for better performance
document.addEventListener('DOMContentLoaded', function() {
    // Phone number formatting (lightweight)
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) value = value.substring(0, 2) + '-' + value.substring(2);
            if (value.length >= 6) value = value.substring(0, 6) + '-' + value.substring(6, 10);
            e.target.value = value;
        });
    }
    
    // Form submit validation
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const btn = e.target.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Updating...';
        });
    }
});
</script>

<style>
/* Enhanced Card Header Design - Matching Sidebar */
.card {
    border-radius: 12px !important;
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
    border: none;
    position: relative;
    overflow: hidden;
}

.card-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, transparent 100%);
    pointer-events: none;
}

.card-header::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, #48bb78 0%, #4299e1 50%, #f59e0b 100%);
}

.header-icon-wrapper {
    width: 45px;
    height: 45px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.header-icon-wrapper i {
    font-size: 20px;
    color: #48bb78;
}

.header-badge {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid rgba(255, 255, 255, 0.2);
}

.header-badge i {
    font-size: 24px;
    color: rgba(255, 255, 255, 0.3);
}

/* Form Styling */
.input-group-text {
    background-color: #f8f9fc;
    border-color: #d1d3e2;
    color: #5a5c69;
}

.form-select:focus, .form-control:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

#phone {
    font-family: 'Courier New', monospace;
}

.is-invalid {
    border-color: #e74a3b !important;
}

h5.text-primary {
    border-bottom: 2px solid #4e73df;
    padding-bottom: 8px;
}

.btn-success:hover, .btn-secondary:hover {
    transform: translateY(-1px);
    transition: all 0.2s ease;
}

.card-body {
    background: #ffffff;
}
</style>
@endpush
