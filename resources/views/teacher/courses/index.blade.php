@extends('teacher.layouts.app')

@section('title', 'My Courses')
@section('page-title', 'My Courses')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>My Courses</h2>
    <a href="{{ route('teacher.courses.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>Create New Course
    </a>
</div>

@if($courses->count() > 0)
    <div class="row">
        @foreach($courses as $course)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                @if($course->thumbnail)
                    <img src="{{ asset('storage/' . $course->thumbnail) }}" class="card-img-top" alt="{{ $course->title }}" style="height: 200px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="fas fa-book fa-3x text-muted"></i>
                    </div>
                @endif
                
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $course->title }}</h5>
                    <p class="card-text flex-grow-1">{{ Str::limit($course->description, 100) }}</p>
                    
                    <div class="mb-3">
                        <div class="row text-center">
                            <div class="col-4">
                                <small class="text-muted">Students</small>
                                <br>
                                <span class="badge bg-primary">{{ $course->enrollments_count }}</span>
                            </div>
                            <div class="col-4">
                                <small class="text-muted">Lessons</small>
                                <br>
                                <span class="badge bg-info">{{ $course->lessons_count }}</span>
                            </div>
                            <div class="col-4">
                                <small class="text-muted">Price</small>
                                <br>
                                <span class="badge bg-success">${{ number_format($course->price, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <span class="badge bg-{{ $course->status === 'published' ? 'success' : 'secondary' }}">
                            {{ ucfirst($course->status) }}
                        </span>
                        <span class="badge bg-light text-dark">{{ ucfirst($course->difficulty_level) }}</span>
                    </div>
                </div>
                
                <div class="card-footer bg-transparent">
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('teacher.courses.show', $course) }}" 
                           class="btn btn-outline-primary btn-sm" title="View Details">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('teacher.courses.edit', $course) }}" 
                           class="btn btn-outline-secondary btn-sm" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('teacher.courses.toggle-status', $course) }}" class="d-inline">
                            @csrf
                            <button type="submit" 
                                    class="btn btn-outline-{{ $course->status === 'published' ? 'warning' : 'success' }} btn-sm" 
                                    title="{{ $course->status === 'published' ? 'Unpublish' : 'Publish' }}">
                                <i class="fas fa-{{ $course->status === 'published' ? 'pause' : 'play' }}"></i>
                            </button>
                        </form>
                        <button type="button" class="btn btn-outline-danger btn-sm" 
                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $course->id }}" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div class="modal fade" id="deleteModal{{ $course->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Course</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete the course "{{ $course->title }}"? This action cannot be undone.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form method="POST" action="{{ route('teacher.courses.destroy', $course) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $courses->links() }}
    </div>
@else
    <div class="text-center py-5">
        <i class="fas fa-book fa-4x text-muted mb-3"></i>
        <h3>No courses yet</h3>
        <p class="text-muted mb-4">Start building your online course library by creating your first course.</p>
        <a href="{{ route('teacher.courses.create') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-plus me-2"></i>Create Your First Course
        </a>
    </div>
@endif
@endsection
