@extends('teacher.layouts.app')

@section('title', 'Notifications')

@push('styles')
<style>
    .notifications-page {
        padding: 2rem;
    }

    .page-header {
        margin-bottom: 2rem;
    }

    .page-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .notifications-actions {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .notification-card {
        background: white;
        border-radius: 6px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        transition: all 0.2s ease;
        border-left: 3px solid #e5e7eb;
    }

    .notification-card.unread {
        background: #f8fafc;
        border-left-color: #3b82f6;
    }

    .notification-card:hover {
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 0.5rem;
    }

    .notification-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        margin-right: 0.75rem;
        flex-shrink: 0;
    }

    .notification-icon.enrolled {
        background: #10b981;
        color: white;
    }

    .notification-content {
        flex: 1;
    }

    .notification-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }

    .notification-message {
        color: #6b7280;
        margin-bottom: 0.5rem;
        line-height: 1.4;
        font-size: 0.875rem;
    }

    .notification-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 0.75rem;
        margin-top: 0.75rem;
        padding-top: 0.75rem;
        border-top: 1px solid #f3f4f6;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .detail-item i {
        color: #9ca3af;
        width: 16px;
        font-size: 0.875rem;
    }

    .detail-label {
        font-size: 0.75rem;
        color: #9ca3af;
    }

    .detail-value {
        font-weight: 600;
        color: #1f2937;
        font-size: 0.875rem;
    }

    .notification-time {
        font-size: 0.75rem;
        color: #9ca3af;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        flex-shrink: 0;
    }

    .notification-time i {
        font-size: 0.7rem;
    }

    .notification-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.75rem;
    }

    .btn-action {
        padding: 0.375rem 0.75rem;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }

    .btn-view-course {
        background: #667eea;
        color: white;
    }

    .btn-view-course:hover {
        background: #5568d3;
        color: white;
    }

    .btn-delete {
        background: #fee2e2;
        color: #dc2626;
    }

    .btn-delete:hover {
        background: #fecaca;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 8px;
    }

    .empty-state i {
        font-size: 4rem;
        color: #d1d5db;
        margin-bottom: 1rem;
    }

    .empty-state h3 {
        color: #6b7280;
        margin-bottom: 0.5rem;
    }

    .badge-unread {
        background: #3b82f6;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .badge-amount {
        background: #10b981;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .payment-status {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .payment-status.completed {
        background: #d1fae5;
        color: #065f46;
    }

    .payment-status i {
        font-size: 0.7rem;
    }
</style>
@endpush

@section('content')
<div class="notifications-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1><i class="fas fa-bell me-2"></i>Notifications</h1>
                <p class="text-muted">Stay updated with your course enrollments</p>
            </div>
            @if($unreadCount > 0)
                <span class="badge-unread">{{ $unreadCount }} Unread</span>
            @endif
        </div>
    </div>

    <!-- Actions -->
    @if($notifications->count() > 0)
    <div class="notifications-actions">
        <form method="POST" action="{{ route('teacher.notifications.mark-all-read') }}">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-check-double me-2"></i>Mark All as Read
            </button>
        </form>
    </div>
    @endif

    <!-- Notifications List -->
    @if($notifications->count() > 0)
        @foreach($notifications as $notification)
            @php
                $isEnrollment = $notification->type === 'enrollment';
                $isAssignment = $notification->type === 'assignment_submission';
            @endphp
            <div class="notification-card {{ is_null($notification->read_at) ? 'unread' : '' }}">
                <div class="notification-header">
                    <div class="d-flex align-items-start w-100">
                        <div class="notification-icon {{ $isAssignment ? 'assignment' : 'enrolled' }}" style="{{ $isAssignment ? 'background: #f59e0b;' : '' }}">
                            <i class="fas {{ $isAssignment ? 'fa-file-alt' : 'fa-user-graduate' }}"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">
                                {{ $notification->title }}
                            </div>
                            <div class="notification-message">
                                {{ $notification->message }}
                            </div>

                            <!-- Notification Details -->
                            <div class="notification-details">
                                <div class="detail-item">
                                    <i class="fas fa-user"></i>
                                    <div>
                                        <div class="detail-label">Student</div>
                                        <div class="detail-value">{{ $notification->data['student_name'] ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-envelope"></i>
                                    <div>
                                        <div class="detail-label">Email</div>
                                        <div class="detail-value">{{ $notification->data['student_email'] ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-book"></i>
                                    <div>
                                        <div class="detail-label">Course</div>
                                        <div class="detail-value">{{ $notification->data['course_title'] ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                
                                @if($isEnrollment)
                                    <div class="detail-item">
                                        <i class="fas fa-dollar-sign"></i>
                                        <div>
                                            <div class="detail-label">Amount Paid</div>
                                            <div class="detail-value">
                                                <span class="badge-amount">RM {{ number_format($notification->data['amount_paid'] ?? 0, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-check-circle"></i>
                                        <div>
                                            <div class="detail-label">Payment Status</div>
                                            <div class="detail-value">
                                                <span class="payment-status completed">
                                                    <i class="fas fa-check"></i>
                                                    {{ ucfirst($notification->data['payment_status'] ?? 'completed') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-calendar"></i>
                                        <div>
                                            <div class="detail-label">Enrolled At</div>
                                            <div class="detail-value">{{ \Carbon\Carbon::parse($notification->data['enrolled_at'] ?? now())->format('M d, Y h:i A') }}</div>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($isAssignment)
                                    <div class="detail-item">
                                        <i class="fas fa-tasks"></i>
                                        <div>
                                            <div class="detail-label">Assignment</div>
                                            <div class="detail-value">{{ $notification->data['assignment_title'] ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-calendar-check"></i>
                                        <div>
                                            <div class="detail-label">Submitted At</div>
                                            <div class="detail-value">{{ \Carbon\Carbon::parse($notification->data['submitted_at'] ?? now())->format('M d, Y h:i A') }}</div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="notification-actions">
                                @if($isAssignment && isset($notification->data['submission_id']))
                                    <a href="{{ route('teacher.submissions.grade', $notification->data['submission_id']) }}" class="btn-action btn-view-course" style="background: #f59e0b;">
                                        <i class="fas fa-graduation-cap me-2"></i>Grade Submission
                                    </a>
                                @endif
                                
                                @if($isEnrollment && isset($notification->data['course_id']))
                                    <a href="{{ route('teacher.courses.show', $notification->data['course_id']) }}" class="btn-action btn-view-course">
                                        <i class="fas fa-eye me-2"></i>View Course
                                    </a>
                                @endif
                                
                                <form method="POST" action="{{ route('teacher.notifications.destroy', $notification->id) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" onclick="return confirm('Delete this notification?')">
                                        <i class="fas fa-trash me-2"></i>Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="notification-time">
                            <i class="fas fa-clock"></i>
                            {{ $notification->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Pagination -->
        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-bell-slash"></i>
            <h3>No notifications yet</h3>
            <p class="text-muted">You'll see notifications here when students enroll in your courses</p>
        </div>
    @endif
</div>
@endsection
