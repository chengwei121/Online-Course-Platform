@extends('teacher.layouts.app')

@section('title', 'All Assignments')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">All Assignments</h2>
                    <p class="text-muted mb-0">Manage assignments across all your courses</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-start border-primary border-4 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-tasks fa-2x text-primary"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="text-muted mb-1">Total Assignments</h6>
                            <h3 class="mb-0">{{ $totalAssignments }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-start border-warning border-4 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock fa-2x text-warning"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="text-muted mb-1">Pending Review</h6>
                            <h3 class="mb-0">{{ $pendingSubmissions }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-start border-success border-4 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="text-muted mb-1">Total Graded</h6>
                            <h3 class="mb-0">{{ $gradedSubmissions }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-start border-info border-4 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-user-graduate fa-2x text-info"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="text-muted mb-1">Total Submissions</h6>
                            <h3 class="mb-0">{{ $totalSubmissions }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('teacher.assignments.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="course_id" class="form-label">Filter by Course</label>
                    <select name="course_id" id="course_id" class="form-select">
                        <option value="">All Courses</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label for="search" class="form-label">Search Assignments</label>
                    <input type="text" name="search" id="search" class="form-control" 
                           placeholder="Search by title..." value="{{ request('search') }}">
                </div>
                
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    @if(request('course_id') || request('search'))
                        <a href="{{ route('teacher.assignments.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i> Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Assignments by Course -->
    @if($assignmentsByCourse->count() > 0)
        @foreach($assignmentsByCourse as $courseTitle => $assignments)
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-book me-2"></i>{{ $courseTitle }}
                        <span class="badge bg-primary ms-2">{{ $assignments->count() }} Assignment(s)</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Assignment Title</th>
                                    <th>Lesson</th>
                                    <th>Type</th>
                                    <th>Due Date</th>
                                    <th>Points</th>
                                    <th>Submissions</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignments as $assignment)
                                    @php
                                        $submissionCount = $assignment->submissions->whereIn('status', ['submitted', 'graded'])->count();
                                        $gradedCount = $assignment->submissions->whereNotNull('score')->count();
                                        $pendingCount = $assignment->submissions->where('status', 'submitted')->whereNull('score')->count();
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong>{{ $assignment->title }}</strong>
                                            @if($assignment->due_date && $assignment->due_date < now())
                                                <span class="badge bg-danger ms-2">Overdue</span>
                                            @elseif($assignment->due_date && $assignment->due_date < now()->addDays(3))
                                                <span class="badge bg-warning ms-2">Due Soon</span>
                                            @endif
                                        </td>
                                        <td>{{ $assignment->lesson->title }}</td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ ucfirst($assignment->assignment_type) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($assignment->due_date)
                                                {{ $assignment->due_date->format('M d, Y') }}
                                                <br>
                                                <small class="text-muted">{{ $assignment->due_date->format('h:i A') }}</small>
                                            @else
                                                <span class="text-muted">No deadline</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $assignment->points }} pts</span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                <small>
                                                    <i class="fas fa-paper-plane text-primary"></i>
                                                    {{ $submissionCount }} submitted
                                                </small>
                                                @if($pendingCount > 0)
                                                    <small>
                                                        <i class="fas fa-clock text-warning"></i>
                                                        {{ $pendingCount }} pending
                                                    </small>
                                                @endif
                                                @if($gradedCount > 0)
                                                    <small>
                                                        <i class="fas fa-check text-success"></i>
                                                        {{ $gradedCount }} graded
                                                    </small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('teacher.assignments.submissions', $assignment) }}" 
                                                   class="btn btn-sm btn-success" title="View Submissions">
                                                    <i class="fas fa-users"></i>
                                                    @if($pendingCount > 0)
                                                        <span class="badge bg-warning text-dark">{{ $pendingCount }}</span>
                                                    @endif
                                                </a>
                                                <a href="{{ route('teacher.assignments.show', [$assignment->lesson->course, $assignment->lesson, $assignment]) }}" 
                                                   class="btn btn-sm btn-primary" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('teacher.assignments.edit', [$assignment->lesson->course, $assignment->lesson, $assignment]) }}" 
                                                   class="btn btn-sm btn-secondary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-tasks fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No Assignments Found</h4>
                <p class="text-muted">
                    @if(request('course_id') || request('search'))
                        No assignments match your filter criteria. Try adjusting your filters.
                    @else
                        You haven't created any assignments yet. Start by creating assignments in your lessons.
                    @endif
                </p>
                @if(!request('course_id') && !request('search'))
                    <a href="{{ route('teacher.courses.index') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-plus me-2"></i>Go to My Courses
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }
    
    .badge {
        font-size: 0.75rem;
    }
</style>
@endpush
