@extends('teacher.layouts.app')

@section('title', 'Teacher Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $totalCourses }}</h4>
                        <p class="mb-0">Total Courses</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-book fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $totalStudents }}</h4>
                        <p class="mb-0">Total Students</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $totalAssignments }}</h4>
                        <p class="mb-0">Assignments</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clipboard-list fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $pendingSubmissions->count() }}</h4>
                        <p class="mb-0">Pending Reviews</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Courses -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Courses</h5>
                <a href="{{ route('teacher.courses.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>New Course
                </a>
            </div>
            <div class="card-body">
                @if($courses->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Course Title</th>
                                    <th>Students</th>
                                    <th>Lessons</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($courses as $course)
                                <tr>
                                    <td>
                                        <strong>{{ $course->title }}</strong>
                                        <br>
                                        <small class="text-muted">${{ number_format($course->price, 2) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $course->enrollments_count }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $course->lessons_count }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $course->status === 'published' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($course->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('teacher.courses.show', $course) }}" 
                                           class="btn btn-sm btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('teacher.courses.edit', $course) }}" 
                                           class="btn btn-sm btn-outline-secondary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-book fa-3x text-muted mb-3"></i>
                        <h5>No courses yet</h5>
                        <p class="text-muted">Start by creating your first course!</p>
                        <a href="{{ route('teacher.courses.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Create Course
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Enrollments</h5>
            </div>
            <div class="card-body">
                @if($recentEnrollments->count() > 0)
                    @foreach($recentEnrollments as $enrollment)
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <i class="fas fa-user-circle fa-2x text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <strong>{{ $enrollment->user->name }}</strong>
                            <br>
                            <small class="text-muted">{{ $enrollment->course->title }}</small>
                            <br>
                            <small class="text-muted">{{ $enrollment->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-users fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No recent enrollments</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Pending Submissions -->
        @if($pendingSubmissions->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Pending Reviews</h5>
            </div>
            <div class="card-body">
                @foreach($pendingSubmissions->take(5) as $submission)
                <div class="d-flex align-items-center mb-3">
                    <div class="me-3">
                        <i class="fas fa-file-alt fa-2x text-warning"></i>
                    </div>
                    <div class="flex-grow-1">
                        <strong>{{ $submission->user->name }}</strong>
                        <br>
                        <small class="text-muted">{{ $submission->assignment->title }}</small>
                        <br>
                        <small class="text-muted">{{ $submission->created_at->diffForHumans() }}</small>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Welcome Message -->
@if($teacher)
<div class="row">
    <div class="col-12">
        <div class="card bg-light">
            <div class="card-body">
                <h4>Welcome back, {{ $teacher->name }}!</h4>
                <p class="mb-0">Here's what's happening with your courses today.</p>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
