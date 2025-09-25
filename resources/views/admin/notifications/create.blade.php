@extends('layouts.admin')

@section('title', 'Send Notifications')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-bell text-primary me-2"></i>
            Send Notifications
        </h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Test Notification -->
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-vial me-2"></i>
                        Test Notification
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Send a test notification to all students to verify the system is working.</p>
                    <form action="{{ route('admin.notifications.test') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-paper-plane me-2"></i>
                            Send Test Notification
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Create Announcement -->
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-bullhorn me-2"></i>
                        Create Announcement
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.notifications.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                      id="message" name="message" rows="4" required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="target_audience" class="form-label">Target Audience <span class="text-danger">*</span></label>
                            <select class="form-select @error('target_audience') is-invalid @enderror" 
                                    id="target_audience" name="target_audience" required>
                                <option value="">Select Target Audience</option>
                                <option value="all_students" {{ old('target_audience') === 'all_students' ? 'selected' : '' }}>
                                    All Students
                                </option>
                                <option value="course_students" {{ old('target_audience') === 'course_students' ? 'selected' : '' }}>
                                    Students in Specific Course
                                </option>
                            </select>
                            @error('target_audience')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="course_selection" style="display: none;">
                            <label for="course_id" class="form-label">Select Course <span class="text-danger">*</span></label>
                            <select class="form-select @error('course_id') is-invalid @enderror" 
                                    id="course_id" name="course_id">
                                <option value="">Select Course</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('course_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                            <select class="form-select @error('priority') is-invalid @enderror" 
                                    id="priority" name="priority" required>
                                <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="normal" {{ old('priority', 'normal') === 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-bullhorn me-2"></i>
                            Send Announcement
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const targetAudienceSelect = document.getElementById('target_audience');
    const courseSelection = document.getElementById('course_selection');
    
    targetAudienceSelect.addEventListener('change', function() {
        if (this.value === 'course_students') {
            courseSelection.style.display = 'block';
            document.getElementById('course_id').required = true;
        } else {
            courseSelection.style.display = 'none';
            document.getElementById('course_id').required = false;
        }
    });
    
    // Check initial state
    if (targetAudienceSelect.value === 'course_students') {
        courseSelection.style.display = 'block';
        document.getElementById('course_id').required = true;
    }
});
</script>
@endpush
@endsection