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
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Simple Receipt Card -->
            <div class="receipt-card">
                <!-- Header -->
                <div class="receipt-header">
                    <div class="text-center">
                        <h2>E-learning Platform</h2>
                        <p>Payment Receipt</p>
                        <div class="receipt-status">
                            @if($enrollment->payment_status == 'completed')
                                <span class="status-badge paid">✓ PAID</span>
                            @elseif($enrollment->payment_status == 'pending')
                                <span class="status-badge pending">⏳ PENDING</span>
                            @else
                                <span class="status-badge failed">✗ FAILED</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Receipt Body -->
                <div class="receipt-body">
                    <!-- Basic Info -->
                    <div class="info-row">
                        <span>Receipt #:</span>
                        <span><strong>#{{ str_pad($enrollment->id, 6, '0', STR_PAD_LEFT) }}</strong></span>
                    </div>
                    <div class="info-row">
                        <span>Date:</span>
                        <span>{{ $enrollment->enrolled_at->format('M d, Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span>Student:</span>
                        <span>{{ $enrollment->user->name }}</span>
                    </div>
                    <div class="info-row">
                        <span>Email:</span>
                        <span>{{ $enrollment->user->email }}</span>
                    </div>

                    <!-- Course Info -->
                    <div class="course-section">
                        <h4>Course Purchased</h4>
                        <div class="course-item">
                            <div class="course-name">{{ $enrollment->course->title }}</div>
                            <div class="course-details">
                                {{ $enrollment->course->category->name }} • {{ ucfirst($enrollment->course->level) }} Level • {{ $enrollment->course->duration }} hours
                            </div>
                            <div class="course-price">RM{{ number_format($enrollment->amount_paid, 2) }}</div>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="total-section">
                        <div class="total-row">
                            <span>Total Amount:</span>
                            <span class="total-amount">RM{{ number_format($enrollment->amount_paid, 2) }}</span>
                        </div>
                    </div>

                    <!-- Status Info -->
                    <div class="status-section">
                        @if($enrollment->payment_status == 'completed')
                            <div class="success-message">
                                <i class="fas fa-check-circle"></i>
                                <span>Payment completed successfully. Course access is now active.</span>
                            </div>
                        @elseif($enrollment->payment_status == 'pending')
                            <div class="warning-message">
                                <i class="fas fa-clock"></i>
                                <span>Payment is being processed. You'll get access once confirmed.</span>
                            </div>
                        @else
                            <div class="error-message">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span>Payment failed. Please contact support for assistance.</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Footer -->
                <div class="receipt-footer">
                    <p>Thank you for your purchase!</p>
                    <p>Questions? Contact us at {{ config('mail.from.address') }}</p>
                </div>

                <!-- Action Buttons -->
                <div class="receipt-actions">
                    <button onclick="window.print()" class="btn-primary">
                        <i class="fas fa-print"></i> Print Receipt
                    </button>
                    <a href="{{ route('admin.emails.test') }}" class="btn-secondary">
                        <i class="fas fa-envelope"></i> Test Email
                    </a>
                    <a href="{{ route('admin.payments.index') }}" class="btn-outline">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Simple Admin Panel -->
        <div class="col-lg-4">
            @if($enrollment->payment_status !== 'completed')
            <div class="admin-card">
                <h5>Update Payment Status</h5>
                <form action="{{ route('admin.payments.update-status', $enrollment) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <select name="payment_status" class="form-control">
                            <option value="pending" {{ $enrollment->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ $enrollment->payment_status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="failed" {{ $enrollment->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" name="notes" class="form-control" placeholder="Notes (optional)">
                    </div>
                    <button type="submit" class="btn-primary">Update Status</button>
                </form>
            </div>
            @endif

            <div class="admin-card">
                <h5>Quick Actions</h5>
                <div class="quick-actions">
                    <a href="{{ route('admin.courses.show', $enrollment->course) }}" class="action-link">
                        <i class="fas fa-book"></i> View Course
                    </a>
                    <a href="{{ route('admin.clients.show', $enrollment->user) }}" class="action-link">
                        <i class="fas fa-user"></i> View Student
                    </a>
                    <a href="{{ route('admin.emails.test') }}" class="action-link">
                        <i class="fas fa-envelope"></i> Test Emails
                    </a>
                </div>
            </div>

            <div class="admin-card">
                <h5>Student Summary</h5>
                <div class="stat">
                    <span class="stat-number">{{ $userStats['total_purchases'] }}</span>
                    <span class="stat-label">Total Courses</span>
                </div>
                <div class="stat">
                    <span class="stat-number">RM{{ number_format($userStats['total_spent'], 2) }}</span>
                    <span class="stat-label">Total Spent</span>
                </div>
                <div class="stat">
                    <span class="stat-number">{{ $userStats['completed_courses'] }}</span>
                    <span class="stat-label">Completed</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Simple Receipt Card */
.receipt-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    margin-bottom: 20px;
    overflow: hidden;
    max-width: 100%;
}

/* Receipt Header */
.receipt-header {
    background: var(--sidebar-bg, linear-gradient(135deg, #64748b 0%, #475569 50%, #334155 100%));
    color: white;
    padding: 20px;
    text-align: center;
}

.receipt-header h2 {
    margin: 0 0 3px 0;
    font-size: 22px;
    font-weight: 600;
}

.receipt-header p {
    margin: 0 0 12px 0;
    opacity: 0.9;
    font-size: 14px;
}

/* Status Badge */
.status-badge {
    display: inline-block;
    padding: 6px 16px;
    border-radius: 20px;
    font-weight: bold;
    font-size: 12px;
}

.status-badge.paid {
    background: #10b981;
    color: white;
}

.status-badge.pending {
    background: #f59e0b;
    color: white;
}

.status-badge.failed {
    background: #ef4444;
    color: white;
}

/* Receipt Body */
.receipt-body {
    padding: 20px;
}

/* Info Rows */
.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #f3f4f6;
    font-size: 14px;
}

.info-row:last-child {
    border-bottom: none;
}

/* Course Section */
.course-section {
    margin: 20px 0;
    padding: 15px;
    background: #f8fafc;
    border-radius: 6px;
    border-left: 3px solid var(--sidebar-bg, #64748b);
}

.course-section h4 {
    margin: 0 0 10px 0;
    color: #374151;
    font-size: 16px;
    font-weight: 600;
}

.course-item {
    background: white;
    padding: 15px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.course-name {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 6px;
}

.course-details {
    color: #6b7280;
    font-size: 13px;
    margin-bottom: 8px;
}

.course-price {
    font-size: 18px;
    font-weight: bold;
    color: #059669;
    text-align: right;
}

/* Total Section */
.total-section {
    margin: 20px 0;
    padding: 15px;
    background: #ecfdf5;
    border-radius: 6px;
    border: 1px solid #d1fae5;
}

.total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 16px;
}

.total-amount {
    font-weight: bold;
    color: #059669;
    font-size: 20px;
}

/* Status Messages */
.status-section {
    margin: 20px 0;
    padding: 15px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.success-message {
    background: #ecfdf5;
    color: #065f46;
    border: 1px solid #d1fae5;
}

.warning-message {
    background: #fffbeb;
    color: #92400e;
    border: 1px solid #fed7aa;
}

.error-message {
    background: #fef2f2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.status-section i {
    font-size: 16px;
}

/* Receipt Footer */
.receipt-footer {
    padding: 15px 20px;
    background: #f9fafb;
    text-align: center;
    color: #6b7280;
    border-top: 1px solid #e5e7eb;
    font-size: 13px;
}

.receipt-footer p {
    margin: 3px 0;
}

/* Action Buttons */
.receipt-actions {
    padding: 15px 20px;
    text-align: center;
    gap: 8px;
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-primary {
    background: var(--sidebar-bg, #64748b);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 14px;
}

.btn-primary:hover {
    background: var(--sidebar-bg-hover, #475569);
    transform: translateY(-1px);
}

.btn-secondary {
    background: #6b7280;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s;
    font-size: 14px;
}

.btn-secondary:hover {
    background: #4b5563;
    color: white;
    text-decoration: none;
}

.btn-outline {
    background: transparent;
    color: #6b7280;
    border: 1px solid #d1d5db;
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s;
    font-size: 14px;
}

.btn-outline:hover {
    background: #f3f4f6;
    color: #374151;
    text-decoration: none;
}

/* Admin Cards */
.admin-card {
    background: white;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.admin-card h5 {
    margin: 0 0 15px 0;
    color: #374151;
    font-size: 16px;
    font-weight: 600;
}

/* Form Controls */
.form-group {
    margin-bottom: 15px;
}

.form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
}

/* Quick Actions */
.quick-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.action-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    color: #374151;
    text-decoration: none;
    border-radius: 6px;
    transition: all 0.2s;
}

.action-link:hover {
    background: #f3f4f6;
    color: #111827;
    text-decoration: none;
}

/* Stats */
.stat {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f3f4f6;
}

.stat:last-child {
    border-bottom: none;
}

.stat-number {
    font-weight: bold;
    font-size: 16px;
    color: #111827;
}

.stat-label {
    font-size: 14px;
    color: #6b7280;
}

/* Responsive */
@media (max-width: 768px) {
    .receipt-header,
    .receipt-body,
    .receipt-actions {
        padding: 15px;
    }
    
    .receipt-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .btn-primary,
    .btn-secondary,
    .btn-outline {
        width: 100%;
        justify-content: center;
        margin-bottom: 8px;
    }
    
    .course-section,
    .total-section,
    .status-section {
        padding: 12px;
        margin: 15px 0;
    }
}

/* Print Styles */
@media print {
    .receipt-actions,
    .col-lg-4,
    .admin-card {
        display: none !important;
    }
    
    .receipt-card {
        box-shadow: none;
        border: 1px solid #ccc;
    }
    
    .col-lg-8 {
        width: 100% !important;
        max-width: 100% !important;
    }
}
</style>
@endpush