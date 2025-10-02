@extends('layouts.admin')

@section('title', 'Payment Details')

@section('header')
    <h1 class="h2">
        <i class="fas fa-receipt me-3"></i>
        Payment Details #{{ $enrollment->id }}
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Back to Payments
            </a>
            <a href="{{ route('admin.courses.show', $enrollment->course) }}" class="btn btn-sm btn-outline-info">
                <i class="fas fa-book me-1"></i>
                View Course
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Payment Information -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header modern-grey-header text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-credit-card me-2"></i>
                        Payment Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Payment ID</label>
                                <div class="h5">#{{ $enrollment->id }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Amount Paid</label>
                                <div class="h4 text-success">
                                    RM{{ number_format($enrollment->amount_paid, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Payment Status</label>
                                <div>
                                    @if($enrollment->payment_status == 'completed')
                                        <span class="badge bg-success fs-6">
                                            <i class="fas fa-check me-1"></i>
                                            Completed
                                        </span>
                                    @elseif($enrollment->payment_status == 'pending')
                                        <span class="badge bg-warning fs-6">
                                            <i class="fas fa-clock me-1"></i>
                                            Pending
                                        </span>
                                    @else
                                        <span class="badge bg-danger fs-6">
                                            <i class="fas fa-times me-1"></i>
                                            Failed
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Enrollment Date</label>
                                <div class="h6">
                                    {{ $enrollment->enrolled_at->format('M d, Y H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Course Completion</label>
                                <div>
                                    @if($enrollment->completed_at)
                                        <span class="badge bg-success">
                                            <i class="fas fa-trophy me-1"></i>
                                            Completed on {{ $enrollment->completed_at->format('M d, Y') }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-clock me-1"></i>
                                            In Progress
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($enrollment->payment_status !== 'completed')
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Update Payment Status
                            </h6>
                            <form action="{{ route('admin.payments.update-status', $enrollment) }}" method="POST" class="row g-3">
                                @csrf
                                @method('PATCH')
                                <div class="col-md-4">
                                    <select name="payment_status" class="form-select" required>
                                        <option value="pending" {{ $enrollment->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="completed" {{ $enrollment->payment_status == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="failed" {{ $enrollment->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="notes" class="form-control" placeholder="Add notes (optional)">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>
                                        Update
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Student Information -->
            <div class="card shadow mb-4">
                <div class="card-header modern-grey-header text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-user me-2"></i>
                        Student Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Student Name</label>
                                <div class="h6">{{ $enrollment->user->name }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Email Address</label>
                                <div class="h6">{{ $enrollment->user->email }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label text-muted">Total Purchases</label>
                                <div class="h6 text-primary">{{ $userStats['total_purchases'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label text-muted">Total Spent</label>
                                <div class="h6 text-success">RM{{ number_format($userStats['total_spent'], 2) }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label text-muted">Completed Courses</label>
                                <div class="h6 text-info">{{ $userStats['completed_courses'] }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <a href="{{ route('admin.clients.show', $enrollment->user) }}" class="btn btn-outline-primary">
                                <i class="fas fa-user me-1"></i>
                                View Student Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Information -->
            <div class="card shadow">
                <div class="card-header modern-grey-header text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-book me-2"></i>
                        Course Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            @if($enrollment->course->thumbnail)
                                <img src="{{ $enrollment->course->thumbnail }}" 
                                     alt="{{ $enrollment->course->title }}" 
                                     class="img-fluid rounded">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 120px;">
                                    <i class="fas fa-book fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <h5>{{ $enrollment->course->title }}</h5>
                            <p class="text-muted">{{ Str::limit($enrollment->course->description, 200) }}</p>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <strong>Instructor:</strong> {{ $enrollment->course->instructor->name }}<br>
                                        <strong>Category:</strong> {{ $enrollment->course->category->name }}<br>
                                        <strong>Level:</strong> {{ ucfirst($enrollment->course->level) }}
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <strong>Duration:</strong> {{ $enrollment->course->duration }} hours<br>
                                        <strong>Rating:</strong> 
                                        @if($enrollment->course->average_rating)
                                            {{ number_format($enrollment->course->average_rating, 1) }}/5
                                            <span class="text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $enrollment->course->average_rating)
                                                        <i class="fas fa-star"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                            </span>
                                        @else
                                            No ratings yet
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header modern-grey-header text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-bolt me-2"></i>
                        Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.courses.show', $enrollment->course) }}" class="btn btn-outline-info">
                            <i class="fas fa-book me-2"></i>
                            View Course Details
                        </a>
                        <a href="{{ route('admin.clients.show', $enrollment->user) }}" class="btn btn-outline-primary">
                            <i class="fas fa-user me-2"></i>
                            View Student Profile
                        </a>
                        <a href="{{ route('admin.clients.enrollments', $enrollment->user) }}" class="btn btn-outline-success">
                            <i class="fas fa-list me-2"></i>
                            Student's All Enrollments
                        </a>
                    </div>
                </div>
            </div>

            <!-- Related Payments -->
            @if($relatedPayments->count() > 0)
            <div class="card shadow">
                <div class="card-header modern-grey-header text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-history me-2"></i>
                        Student's Other Purchases
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($relatedPayments as $relatedPayment)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">{{ Str::limit($relatedPayment->course->title, 25) }}</div>
                                    <small class="text-muted">{{ $relatedPayment->enrolled_at->format('M d, Y') }}</small>
                                </div>
                                <div class="text-end">
                                    <small class="text-success fw-bold">${{ number_format($relatedPayment->amount_paid, 2) }}</small><br>
                                    @if($relatedPayment->payment_status == 'completed')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($relatedPayment->payment_status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-danger">Failed</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Modern Grey Card Header */
.modern-grey-header {
    background: linear-gradient(135deg, #64748b 0%, #475569 50%, #334155 100%);
    border-bottom: 3px solid #1e293b;
}

.card {
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
}

.badge.fs-6 {
    font-size: 0.9rem !important;
}

.form-label {
    font-weight: 600;
    margin-bottom: 0.25rem;
}
</style>
@endpush