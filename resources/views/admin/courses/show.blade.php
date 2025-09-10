@extends('layouts.admin')

@section('title', 'Course Details - ' . $course->title)

@section('content')
<div class="container-fluid">
    <!-- Course Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body position-relative">
                    <!-- Status Badge -->
                    <div class="position-absolute top-0 end-0 m-3">
                        @if($course->status == 'published')
                            <span class="badge bg-success fs-6 px-3 py-2">
                                <i class="fas fa-check me-2"></i>Published
                            </span>
                        @elseif($course->status == 'draft')
                            <span class="badge bg-warning fs-6 px-3 py-2">
                                <i class="fas fa-edit me-2"></i>Draft
                            </span>
                        @else
                            <span class="badge bg-secondary fs-6 px-3 py-2">
                                <i class="fas fa-archive me-2"></i>Archived
                            </span>
                        @endif
                    </div>

                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb" class="mb-3">
                        <ol class="breadcrumb text-white-50">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}" class="text-white-50 text-decoration-none">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.courses.index') }}" class="text-white-50 text-decoration-none">Courses</a>
                            </li>
                            <li class="breadcrumb-item active text-white" aria-current="page">
                                {{ Str::limit($course->title, 30) }}
                            </li>
                        </ol>
                    </nav>

                    <!-- Course Title and Info -->
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="display-5 fw-bold mb-3">{{ $course->title }}</h1>
                            <p class="lead mb-3">{{ $course->description }}</p>
                            <div class="d-flex flex-wrap gap-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user me-2"></i>
                                    <span>{{ $course->instructor->name }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-tag me-2"></i>
                                    <span>{{ $course->category->name }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-signal me-2"></i>
                                    <span>{{ $course->level_label }}</span>
                                </div>
                                @if($course->learning_hours)
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clock me-2"></i>
                                    <span>{{ $course->learning_hours }} hours</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-light btn-lg">
                                    <i class="fas fa-edit me-2"></i>Edit Course
                                </a>
                                <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-light btn-lg">
                                    <i class="fas fa-arrow-left me-2"></i>Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Course Overview -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Course Overview
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <img src="{{ $course->thumbnail ?? asset('images/course-placeholder.jpg') }}" 
                                 alt="{{ $course->title }}" 
                                 class="img-fluid rounded shadow">
                        </div>
                        <div class="col-md-7">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <h6 class="text-muted">Price</h6>
                                    @if($course->is_free)
                                        <span class="badge bg-success fs-6">FREE</span>
                                    @else
                                        <span class="h5 text-success">${{ number_format($course->price, 2) }}</span>
                                    @endif
                                </div>
                                <div class="col-6">
                                    <h6 class="text-muted">Duration</h6>
                                    <span>{{ $course->duration ?? 'Not specified' }}</span>
                                </div>
                            </div>

                            @if($course->skills_to_learn)
                            <div class="mb-3">
                                <h6 class="text-muted">Skills You'll Learn</h6>
                                @foreach($course->skills_to_learn as $skill)
                                    <span class="badge bg-primary me-1 mb-1">{{ $skill }}</span>
                                @endforeach
                            </div>
                            @endif

                            <div class="mb-3">
                                <h6 class="text-muted">Average Rating</h6>
                                @if($course->average_rating > 0)
                                    <div class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $course->average_rating)
                                                <i class="fas fa-star"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                        <span class="ms-2 text-muted">
                                            ({{ number_format($course->average_rating, 1) }}/5 from {{ $stats['total_reviews'] }} reviews)
                                        </span>
                                    </div>
                                @else
                                    <span class="text-muted">No ratings yet</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Lessons -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-play-circle me-2"></i>Course Content
                    </h5>
                    <span class="badge bg-primary">{{ $stats['total_lessons'] }} lessons</span>
                </div>
                <div class="card-body">
                    @if($course->lessons->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($course->lessons as $index => $lesson)
                                <div class="list-group-item">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 30px; height: 30px; min-width: 30px;">
                                            <span class="fw-bold small">{{ $index + 1 }}</span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-bold">{{ $lesson->title }}</h6>
                                            <p class="mb-1 text-muted small">{{ Str::limit($lesson->description, 120) }}</p>
                                            <div class="d-flex align-items-center gap-3">
                                                @if($lesson->duration)
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock me-1"></i>{{ $lesson->duration }}
                                                    </small>
                                                @endif
                                                <div>
                                                    @if($lesson->video_path)
                                                        <span class="badge bg-success">Video</span>
                                                    @endif
                                                    @if($lesson->content)
                                                        <span class="badge bg-info">Content</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-play-circle display-4 text-muted mb-3"></i>
                            <h5 class="text-muted">No lessons yet</h5>
                            <p class="text-muted">This course doesn't have any lessons yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Reviews -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-star me-2"></i>Student Reviews
                    </h5>
                    <span class="badge bg-warning">{{ $stats['total_reviews'] }} reviews</span>
                </div>
                <div class="card-body">
                    @if($course->reviews->count() > 0)
                        @foreach($course->reviews->take(5) as $review)
                            <div class="border-start border-4 border-primary ps-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 40px; height: 40px;">
                                            <span class="text-white fw-bold">{{ substr($review->user->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $review->user->name }}</h6>
                                            <div class="text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                        <i class="fas fa-star"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-0">{{ $review->comment }}</p>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-star display-4 text-muted mb-3"></i>
                            <h5 class="text-muted">No reviews yet</h5>
                            <p class="text-muted">This course hasn't received any reviews yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Course Statistics -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Course Analytics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="card bg-success text-white h-100">
                                <div class="card-body text-center">
                                    <h3 class="card-title">{{ $stats['total_enrollments'] }}</h3>
                                    <p class="card-text small">Students</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card bg-warning text-white h-100">
                                <div class="card-body text-center">
                                    <h3 class="card-title">${{ number_format($stats['revenue'], 0) }}</h3>
                                    <p class="card-text small">Revenue</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card bg-info text-white h-100">
                                <div class="card-body text-center">
                                    <h3 class="card-title">{{ $stats['total_lessons'] }}</h3>
                                    <p class="card-text small">Lessons</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card bg-danger text-white h-100">
                                <div class="card-body text-center">
                                    <h3 class="card-title">{{ $stats['completion_rate'] }}%</h3>
                                    <p class="card-text small">Completion</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Actions -->
            <div class="card bg-success text-white mb-4">
                <div class="card-header border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cogs me-2"></i>Course Management
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <form method="POST" action="{{ route('admin.courses.toggle-status', $course) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-light w-100">
                                <i class="fas fa-{{ $course->status == 'published' ? 'pause' : 'play' }} me-2"></i>
                                {{ $course->status == 'published' ? 'Unpublish Course' : 'Publish Course' }}
                            </button>
                        </form>
                        
                        <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-outline-light">
                            <i class="fas fa-edit me-2"></i>Edit Course Details
                        </a>
                        
                        @if($course->enrollments->count() == 0)
                            <button type="button" class="btn btn-outline-light" onclick="confirmDelete()">
                                <i class="fas fa-trash me-2"></i>Delete Course
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Course Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Course Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted mb-1">Created</h6>
                        <span>{{ $course->created_at->format('M d, Y \a\t H:i') }}</span>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted mb-1">Last Updated</h6>
                        <span>{{ $course->updated_at->format('M d, Y \a\t H:i') }}</span>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted mb-1">Course Slug</h6>
                        <code class="bg-light p-1 rounded">{{ $course->slug }}</code>
                    </div>
                    <div class="mb-0">
                        <h6 class="text-muted mb-1">Course ID</h6>
                        <code class="bg-light p-1 rounded">#{{ $course->id }}</code>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
@if($course->enrollments->count() == 0)
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirm Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="fas fa-trash-alt display-4 text-danger mb-3"></i>
                    <h4>Delete "{{ $course->title }}"?</h4>
                </div>
                <div class="alert alert-warning" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone and will permanently remove all course data, including lessons and content.
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Delete Forever
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete() {
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush
@endif
@endsection
