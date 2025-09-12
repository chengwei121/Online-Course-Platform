@extends('layouts.admin')

@section('title', 'Student Enrollments - ' . $client->name)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Student Enrollments</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.clients.index') }}">Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.clients.show', $client) }}">{{ $client->name }}</a></li>
                    <li class="breadcrumb-item active">Enrollments</li>
                </ol>
            </nav>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.clients.show', $client) }}" class="btn btn-info">
                <i class="fas fa-user"></i> View Profile
            </a>
            <a href="{{ route('admin.clients.activities', $client) }}" class="btn btn-success">
                <i class="fas fa-history"></i> View Activities
            </a>
        </div>
    </div>

    <!-- Student Info Card -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-2">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 60px; height: 60px;">
                        <span class="text-white h4 mb-0">{{ substr($client->name, 0, 2) }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="mb-1">{{ $client->name }}</h5>
                    <p class="text-muted mb-0">{{ $client->email }}</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="row text-center">
                        <div class="col-6">
                            <h5 class="text-primary mb-1">{{ $enrollments->total() }}</h5>
                            <small class="text-muted">Total Enrollments</small>
                        </div>
                        <div class="col-6">
                            <h5 class="text-success mb-1">
                                ${{ number_format($client->enrollments->sum(function($e) { 
                                    return $e->course->is_free ? 0 : $e->course->price; 
                                }), 2) }}
                            </h5>
                            <small class="text-muted">Total Spent</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enrollments -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-graduation-cap me-2"></i>
                Course Enrollments
            </h6>
            <div class="text-muted">
                <small>
                    Showing {{ $enrollments->firstItem() ?? 0 }}-{{ $enrollments->lastItem() ?? 0 }} of {{ $enrollments->total() }} enrollments
                </small>
            </div>
        </div>
        <div class="card-body p-0">
            @if($enrollments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Course</th>
                                <th>Instructor</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Progress</th>
                                <th>Enrolled Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enrollments as $enrollment)
                                @php
                                    $course = $enrollment->course;
                                    $totalLessons = $course->lessons->count();
                                    $completedLessons = $client->lessonProgress()
                                        ->whereIn('lesson_id', $course->lessons->pluck('id'))
                                        ->where('completed', true)
                                        ->count();
                                    $progressPercentage = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($course->thumbnail)
                                                <img src="{{ $course->thumbnail }}" 
                                                     class="rounded me-3" width="50" height="40" 
                                                     style="object-fit: cover;">
                                            @else
                                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center me-3" 
                                                     style="width: 50px; height: 40px;">
                                                    <i class="fas fa-book text-white"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-1">{{ $course->title }}</h6>
                                                <small class="text-muted">{{ Str::limit($course->description, 50) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $course->instructor->name ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $course->category->name ?? 'Uncategorized' }}</span>
                                    </td>
                                    <td>
                                        @if($course->is_free)
                                            <span class="badge bg-success">FREE</span>
                                        @else
                                            <span class="fw-bold text-primary">${{ number_format($course->price, 2) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress me-2" style="height: 6px; width: 60px;">
                                                <div class="progress-bar bg-{{ $progressPercentage == 100 ? 'success' : 'primary' }}" 
                                                     role="progressbar" style="width: {{ $progressPercentage }}%"></div>
                                            </div>
                                            <small class="text-muted">{{ $progressPercentage }}%</small>
                                        </div>
                                        <small class="text-muted">{{ $completedLessons }}/{{ $totalLessons }} lessons</small>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $enrollment->created_at->format('M d, Y') }}</span>
                                        <br>
                                        <small class="text-muted">{{ $enrollment->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        @if($progressPercentage == 100)
                                            <span class="badge bg-success">Completed</span>
                                        @elseif($progressPercentage > 0)
                                            <span class="badge bg-warning">In Progress</span>
                                        @else
                                            <span class="badge bg-secondary">Not Started</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($enrollments->hasPages())
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-0">
                                    Showing {{ $enrollments->firstItem() }} to {{ $enrollments->lastItem() }} 
                                    of {{ $enrollments->total() }} enrollments
                                </p>
                            </div>
                            <div>
                                {{ $enrollments->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                    <h5>No Enrollments Found</h5>
                    <p class="text-muted">This student hasn't enrolled in any courses yet.</p>
                    <a href="{{ route('admin.clients.show', $client) }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Back to Profile
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
.table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.875rem;
    color: #5a5c69;
}

.table td {
    vertical-align: middle;
    padding: 1rem 0.75rem;
}

.progress {
    background-color: #e9ecef;
}

.badge {
    font-size: 0.75rem;
}

.table-hover tbody tr:hover {
    background-color: #f8f9fc;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .table td, .table th {
        padding: 0.5rem 0.25rem;
    }
}
</style>
@endpush
@endsection
