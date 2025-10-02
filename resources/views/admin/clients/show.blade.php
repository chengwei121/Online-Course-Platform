@extends('layouts.admin')

@section('title', 'Student Details - ' . $client->name)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Student Details</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.clients.index') }}">Students</a></li>
                    <li class="breadcrumb-item active">{{ $client->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.clients.edit', $client) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit Student
            </a>
            <a href="{{ route('admin.clients.activities', $client) }}" class="btn btn-info">
                <i class="fas fa-history"></i> View Activities
            </a>
            <a href="{{ route('admin.clients.enrollments', $client) }}" class="btn btn-success">
                <i class="fas fa-graduation-cap"></i> View Enrollments
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Student Information -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Student Information</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 80px; height: 80px;">
                            <span class="text-white h2 mb-0">{{ substr($client->name, 0, 2) }}</span>
                        </div>
                        <h5 class="font-weight-bold">{{ $client->name }}</h5>
                        <p class="text-muted">{{ $client->email }}</p>
                        <span class="badge bg-success">Active Student</span>
                    </div>

                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary">{{ $stats['total_enrollments'] }}</h4>
                                <small class="text-muted">Total Enrollments</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success">RM{{ number_format($stats['total_spent'], 2) }}</h4>
                            <small class="text-muted">Total Spent</small>
                        </div>
                    </div>

                    <hr>

                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h5 class="text-warning">{{ $stats['completed_courses'] }}</h5>
                                <small class="text-muted">Completed</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h5 class="text-info">{{ $stats['in_progress_courses'] }}</h5>
                            <small class="text-muted">In Progress</small>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <strong>Student ID:</strong> #{{ $client->id }}
                            </div>
                            <div class="mb-3">
                                <strong>Join Date:</strong> {{ $stats['join_date'] }}
                            </div>
                            <div class="mb-3">
                                <strong>Last Activity:</strong> {{ $stats['last_activity'] }}
                            </div>
                            <div class="mb-3">
                                <strong>Email Verified:</strong> 
                                @if($client->email_verified_at)
                                    <span class="badge bg-success">Verified</span>
                                @else
                                    <span class="badge bg-warning">Not Verified</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Progress & Activities -->
        <div class="col-xl-8 col-lg-7">
            <!-- Recent Enrollments -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Enrollments</h6>
                    <a href="{{ route('admin.clients.enrollments', $client) }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($client->enrollments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    @foreach($client->enrollments->take(5) as $enrollment)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($enrollment->course->thumbnail)
                                                        <img src="{{ $enrollment->course->thumbnail }}" 
                                                             class="rounded me-3" width="50" height="40" 
                                                             style="object-fit: cover;">
                                                    @else
                                                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center me-3" 
                                                             style="width: 50px; height: 40px;">
                                                            <i class="fas fa-book text-white"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-1">{{ $enrollment->course->title }}</h6>
                                                        <small class="text-muted">
                                                            by {{ $enrollment->course->instructor->name ?? 'Unknown' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <div class="mb-1">
                                                    @if($enrollment->course->is_free)
                                                        <span class="badge bg-success">FREE</span>
                                                    @else
                                                        <span class="badge bg-primary">RM{{ number_format($enrollment->course->price, 2) }}</span>
                                                    @endif
                                                </div>
                                                <small class="text-muted">{{ $enrollment->created_at->format('M d, Y') }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                            <h6>No Enrollments Yet</h6>
                            <p class="text-muted">This student hasn't enrolled in any courses.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Learning Progress Summary -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Learning Progress Summary</h6>
                </div>
                <div class="card-body">
                    @if($client->enrollments->count() > 0)
                        @foreach($client->enrollments->take(3) as $enrollment)
                            @php
                                $course = $enrollment->course;
                                $totalLessons = $course->lessons->count();
                                $completedLessons = $client->lessonProgress()
                                    ->whereIn('lesson_id', $course->lessons->pluck('id'))
                                    ->where('completed', true)
                                    ->count();
                                $progressPercentage = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
                            @endphp
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">{{ $course->title }}</h6>
                                    <span class="text-muted">{{ $progressPercentage }}%</span>
                                </div>
                                <div class="progress mb-2" style="height: 8px;">
                                    <div class="progress-bar" role="progressbar" 
                                         style="width: {{ $progressPercentage }}%"
                                         aria-valuenow="{{ $progressPercentage }}" 
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted">
                                    {{ $completedLessons }} of {{ $totalLessons }} lessons completed
                                </small>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                            <h6>No Progress Data</h6>
                            <p class="text-muted">No learning progress to display.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-6 mb-3">
                            <a href="{{ route('admin.clients.edit', $client) }}" class="btn btn-warning w-100">
                                <i class="fas fa-edit mb-2"></i><br>
                                <small>Edit Profile</small>
                            </a>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <a href="{{ route('admin.clients.enrollments', $client) }}" class="btn btn-success w-100">
                                <i class="fas fa-graduation-cap mb-2"></i><br>
                                <small>View Enrollments</small>
                            </a>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <a href="{{ route('admin.clients.activities', $client) }}" class="btn btn-info w-100">
                                <i class="fas fa-history mb-2"></i><br>
                                <small>View Activities</small>
                            </a>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary w-100">
                                <i class="fas fa-arrow-left mb-2"></i><br>
                                <small>Back to List</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
