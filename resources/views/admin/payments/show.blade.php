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
                        <div class="company-logo" style="font-size: 32px; margin-bottom: 5px;">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
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
                    <!-- Basic Info Section -->
                    <div class="info-section">
                        <h3 class="section-title">Receipt Information</h3>
                        <div class="info-row">
                            <span class="info-label">Receipt #:</span>
                            <span class="info-value"><strong>#{{ str_pad($enrollment->id, 6, '0', STR_PAD_LEFT) }}</strong></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Transaction Date:</span>
                            <span class="info-value">{{ $enrollment->enrolled_at->format('F d, Y') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Transaction Time:</span>
                            <span class="info-value">{{ $enrollment->enrolled_at->format('h:i A') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Payment Method:</span>
                            <span class="info-value">{{ ucfirst($enrollment->payment_method ?? 'PayPal') }}</span>
                        </div>
                    </div>

                    <!-- Student Info Section -->
                    <div class="info-section" style="margin-top: 20px;">
                        <h3 class="section-title">Student Information</h3>
                        <div class="info-row">
                            <span class="info-label">Student Name:</span>
                            <span class="info-value">{{ $enrollment->user->name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Email Address:</span>
                            <span class="info-value">{{ $enrollment->user->email }}</span>
                        </div>
                    </div>

                    <!-- Course Info -->
                    <div class="course-section">
                        <h3 class="section-title">Course Details</h3>
                        <div class="course-item">
                            <div class="course-header">
                                <div class="course-icon">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div class="course-info">
                                    <div class="course-name">{{ $enrollment->course->title }}</div>
                                    <div class="course-meta">
                                        <span><i class="fas fa-folder"></i> {{ $enrollment->course->category->name }}</span>
                                        <span><i class="fas fa-signal"></i> {{ ucfirst($enrollment->course->level) }}</span>
                                        <span><i class="fas fa-clock"></i> {{ $enrollment->course->duration }} hours</span>
                                    </div>
                                </div>
                            </div>
                            <div class="course-pricing">
                                <div class="price-label">Amount</div>
                                <div class="course-price">RM{{ number_format($enrollment->amount_paid, 2) }}</div>
                            </div>
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
                    <div class="footer-content">
                        <div class="thank-you">
                            <i class="fas fa-heart"></i>
                            <strong>Thank you for your purchase!</strong>
                        </div>
                        <div class="footer-info">
                            <div class="footer-item">
                                <i class="fas fa-envelope"></i>
                                <span>{{ config('mail.from.address', 'support@elearning.com') }}</span>
                            </div>
                            <div class="footer-item">
                                <i class="fas fa-globe"></i>
                                <span>www.elearning-platform.com</span>
                            </div>
                            <div class="footer-item">
                                <i class="fas fa-phone"></i>
                                <span>+60 12-345 6789</span>
                            </div>
                        </div>
                        <div class="footer-note">
                            <small>This is a computer-generated receipt and does not require a physical signature.</small>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="receipt-actions">
                    <button onclick="window.print()" class="btn-primary">
                        <i class="fas fa-print"></i> Print Receipt
                    </button>
                    <button onclick="downloadPDF(event)" class="btn-secondary">
                        <i class="fas fa-file-pdf"></i> Download PDF
                    </button>
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

/* Section Titles */
.section-title {
    font-size: 16px;
    font-weight: 600;
    color: #374151;
    margin: 0 0 12px 0;
    padding-bottom: 8px;
    border-bottom: 2px solid #e5e7eb;
}

/* Info Section */
.info-section {
    margin-bottom: 20px;
}

/* Info Rows */
.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #f3f4f6;
    font-size: 14px;
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    color: #6b7280;
    font-weight: 500;
    flex: 0 0 180px;
}

.info-value {
    color: #111827;
    font-weight: 400;
    text-align: right;
    flex: 1;
}

/* Course Section */
.course-section {
    margin: 25px 0;
    padding: 0;
}

.course-item {
    background: #f8fafc;
    padding: 20px;
    border-radius: 8px;
    border: 2px solid #e5e7eb;
}

.course-header {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e5e7eb;
}

.course-icon {
    flex-shrink: 0;
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #64748b 0%, #475569 100%);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 22px;
}

.course-info {
    flex: 1;
}

.course-name {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 8px;
    line-height: 1.4;
}

.course-meta {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    font-size: 13px;
    color: #6b7280;
}

.course-meta span {
    display: flex;
    align-items: center;
    gap: 5px;
}

.course-meta i {
    font-size: 12px;
}

.course-pricing {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background: white;
    border-radius: 6px;
    border: 1px dashed #d1d5db;
}

.price-label {
    font-size: 14px;
    color: #6b7280;
    font-weight: 500;
}

.course-price {
    font-size: 24px;
    font-weight: bold;
    color: #059669;
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
    padding: 20px;
    background: #f9fafb;
    border-top: 2px solid #e5e7eb;
}

.footer-content {
    text-align: center;
}

.thank-you {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 16px;
    color: #111827;
    margin-bottom: 15px;
}

.thank-you i {
    color: #ef4444;
    font-size: 18px;
}

.footer-info {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 12px;
}

.footer-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: #6b7280;
}

.footer-item i {
    color: #9ca3af;
    font-size: 12px;
}

.footer-note {
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px dashed #d1d5db;
}

.footer-note small {
    color: #9ca3af;
    font-size: 11px;
    font-style: italic;
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
    /* Hide non-printable elements */
    body * {
        visibility: hidden;
    }
    
    .receipt-card,
    .receipt-card * {
        visibility: visible;
    }
    
    .receipt-actions,
    .col-lg-4,
    .admin-card,
    .navbar,
    .sidebar,
    header,
    .btn-toolbar,
    nav {
        display: none !important;
    }
    
    /* Reset page layout */
    body {
        margin: 0;
        padding: 0;
        background: white !important;
    }
    
    .container-fluid,
    .row,
    .col-lg-8 {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    /* Position receipt card */
    .receipt-card {
        position: absolute;
        left: 0;
        top: 0;
        width: 210mm; /* A4 width */
        max-width: 100%;
        margin: 0 auto;
        box-shadow: none !important;
        border: none !important;
        page-break-after: avoid;
        page-break-inside: avoid;
    }
    
    /* Optimize header for print */
    .receipt-header {
        background: #2c3e50 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        color: white !important;
        padding: 15px !important;
        page-break-inside: avoid;
    }
    
    .receipt-header h2 {
        font-size: 22px !important;
        margin-bottom: 3px !important;
    }
    
    .receipt-header p {
        font-size: 13px !important;
        margin-bottom: 8px !important;
    }
    
    /* Status badge print optimization */
    .status-badge {
        padding: 5px 15px !important;
        font-size: 12px !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    .status-badge.paid {
        background: #10b981 !important;
        color: white !important;
    }
    
    .status-badge.pending {
        background: #f59e0b !important;
        color: white !important;
    }
    
    .status-badge.failed {
        background: #ef4444 !important;
        color: white !important;
    }
    
    /* Optimize body for print */
    .receipt-body {
        padding: 15px 20px !important;
        page-break-inside: avoid;
    }
    
    /* Section titles */
    .section-title {
        font-size: 14px !important;
        font-weight: bold !important;
        color: #000 !important;
        margin: 0 0 8px 0 !important;
        padding-bottom: 6px !important;
        border-bottom: 1px solid #333 !important;
    }
    
    .info-section {
        margin-bottom: 12px !important;
        page-break-inside: avoid;
    }
    
    /* Info rows */
    .info-row {
        padding: 6px 0 !important;
        font-size: 12px !important;
        border-bottom: 1px solid #ddd !important;
    }
    
    .info-row strong {
        font-weight: bold !important;
    }
    
    .info-label {
        color: #333 !important;
        font-weight: 600 !important;
    }
    
    .info-value {
        color: #000 !important;
        font-weight: 500 !important;
    }
    
    /* Company logo in print */
    .company-logo {
        font-size: 28px !important;
        margin-bottom: 5px !important;
    }
    
    /* Course section optimization */
    .course-section {
        margin: 12px 0 !important;
        padding: 0 !important;
        page-break-inside: avoid;
    }
    
    .course-item {
        background: #f5f5f5 !important;
        padding: 12px !important;
        border: 1px solid #333 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    .course-header {
        display: flex !important;
        gap: 10px !important;
        margin-bottom: 10px !important;
        padding-bottom: 10px !important;
        border-bottom: 1px solid #999 !important;
        page-break-inside: avoid;
    }
    
    .course-icon {
        width: 40px !important;
        height: 40px !important;
        background: #2c3e50 !important;
        color: white !important;
        font-size: 18px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 6px !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    .course-info {
        flex: 1 !important;
    }
    
    .course-name {
        font-size: 14px !important;
        font-weight: bold !important;
        color: #000 !important;
        margin-bottom: 6px !important;
        line-height: 1.3 !important;
    }
    
    .course-meta {
        display: flex !important;
        gap: 10px !important;
        font-size: 11px !important;
        color: #333 !important;
    }
    
    .course-meta span {
        display: inline-flex !important;
        align-items: center !important;
        gap: 4px !important;
    }
    
    .course-pricing {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        padding: 10px !important;
        background: white !important;
        border: 1px dashed #666 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    .price-label {
        font-size: 13px !important;
        color: #333 !important;
        font-weight: 600 !important;
    }
    
    .course-price {
        font-size: 20px !important;
        font-weight: bold !important;
        color: #059669 !important;
    }
    
    /* Total section */
    .total-section {
        margin: 12px 0 !important;
        padding: 12px !important;
        background: #e8f5e9 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        border: 1px solid #4caf50 !important;
        page-break-inside: avoid;
    }
    
    .total-row {
        font-size: 15px !important;
    }
    
    .total-amount {
        font-size: 20px !important;
        font-weight: bold !important;
        color: #059669 !important;
    }
    
    /* Status section */
    .status-section {
        margin: 10px 0 !important;
        padding: 10px !important;
        page-break-inside: avoid;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        font-size: 12px !important;
    }
    
    .success-message {
        background: #e8f5e9 !important;
        color: #2e7d32 !important;
        border: 2px solid #4caf50 !important;
    }
    
    .warning-message {
        background: #fff8e1 !important;
        color: #f57c00 !important;
        border: 2px solid #ffb74d !important;
    }
    
    .error-message {
        background: #ffebee !important;
        color: #c62828 !important;
        border: 2px solid #ef5350 !important;
    }
    
    .status-section i {
        font-size: 14px !important;
    }
    
    /* Footer optimization */
    .receipt-footer {
        padding: 12px !important;
        background: #f5f5f5 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        border-top: 1px solid #333 !important;
        page-break-inside: avoid;
    }
    
    .footer-content {
        text-align: center !important;
    }
    
    .thank-you {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 6px !important;
        font-size: 13px !important;
        color: #000 !important;
        margin-bottom: 8px !important;
        font-weight: bold !important;
    }
    
    .thank-you i {
        color: #e74c3c !important;
        font-size: 14px !important;
    }
    
    .footer-info {
        display: flex !important;
        justify-content: center !important;
        gap: 15px !important;
        margin-bottom: 8px !important;
    }
    
    .footer-item {
        display: flex !important;
        align-items: center !important;
        gap: 5px !important;
        font-size: 10px !important;
        color: #333 !important;
    }
    
    .footer-item i {
        color: #666 !important;
        font-size: 10px !important;
    }
    
    .footer-note {
        margin-top: 8px !important;
        padding-top: 8px !important;
        border-top: 1px dashed #999 !important;
    }
    
    .footer-note small {
        color: #666 !important;
        font-size: 9px !important;
    }
    
    /* Page settings */
    @page {
        size: A4;
        margin: 0.3cm 0.5cm;
    }
    
    /* Ensure proper spacing */
    * {
        box-shadow: none !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
function downloadPDF(event) {
    if (event) {
        event.preventDefault();
    }
    
    // Create modal overlay
    const overlay = document.createElement('div');
    overlay.className = 'overlay-temp';
    overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        backdrop-filter: blur(4px);
        animation: fadeIn 0.2s ease-out;
    `;
    
    const modal = document.createElement('div');
    modal.style.cssText = `
        background: white;
        border-radius: 16px;
        max-width: 540px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        animation: slideUp 0.3s ease-out;
    `;
    
    modal.innerHTML = `
        <div style="text-align: center; padding: 40px 30px 30px 30px;">
            <div style="display: inline-flex; align-items: center; justify-content: center; width: 80px; height: 80px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 50%; margin-bottom: 24px; box-shadow: 0 8px 24px rgba(59, 130, 246, 0.3);">
                <i class="fas fa-info-circle" style="font-size: 40px; color: white;"></i>
            </div>
            
            <h3 style="margin: 0 0 12px 0; font-size: 28px; font-weight: 600; color: #111827;">Save as PDF</h3>
            
            <p style="color: #6b7280; font-size: 15px; line-height: 1.6; margin: 0 0 30px 0;">
                Follow these simple steps to download your receipt
            </p>
            
            <div style="background: #f8fafc; border-radius: 12px; padding: 24px; text-align: left; margin-bottom: 8px; border: 2px solid #e5e7eb;">
                <div style="display: flex; align-items: start; margin-bottom: 20px;">
                    <div style="flex-shrink: 0; width: 32px; height: 32px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 16px; font-weight: bold; color: white; font-size: 16px;">1</div>
                    <div style="flex: 1; padding-top: 4px;">
                        <p style="margin: 0; color: #111827; font-size: 15px; font-weight: 500; line-height: 1.6;">
                            In the print dialog, select <strong>"Save as PDF"</strong> or <strong>"Microsoft Print to PDF"</strong> as the destination
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; align-items: start; margin-bottom: 20px;">
                    <div style="flex-shrink: 0; width: 32px; height: 32px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 16px; font-weight: bold; color: white; font-size: 16px;">2</div>
                    <div style="flex: 1; padding-top: 4px;">
                        <p style="margin: 0; color: #111827; font-size: 15px; font-weight: 500; line-height: 1.6;">
                            Click <strong>"Save"</strong> or <strong>"Print"</strong> button
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; align-items: start;">
                    <div style="flex-shrink: 0; width: 32px; height: 32px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 16px; font-weight: bold; color: white; font-size: 16px;">3</div>
                    <div style="flex: 1; padding-top: 4px;">
                        <p style="margin: 0; color: #111827; font-size: 15px; font-weight: 500; line-height: 1.6;">
                            Choose where to save your receipt on your computer
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <div style="background: #f9fafb; border-top: 1px solid #e5e7eb; padding: 20px 30px; display: flex; justify-content: flex-end; gap: 12px; border-radius: 0 0 16px 16px;">
            <button onclick="this.closest('.overlay-temp').remove()" 
                    class="modal-btn-cancel"
                    style="background: white; color: #374151; border: 1px solid #d1d5db; padding: 10px 24px; border-radius: 8px; cursor: pointer; font-size: 15px; font-weight: 500; transition: all 0.2s;">
                Cancel
            </button>
            <button onclick="this.closest('.overlay-temp').remove(); setTimeout(() => window.print(), 100);" 
                    class="modal-btn-primary"
                    style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border: none; padding: 10px 28px; border-radius: 8px; cursor: pointer; font-size: 15px; font-weight: 500; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); transition: all 0.2s;">
                <i class="fas fa-file-pdf"></i>
                Continue to Download
            </button>
        </div>
    `;
    
    overlay.appendChild(modal);
    document.body.appendChild(overlay);
    
    // Add animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { 
                opacity: 0;
                transform: translateY(20px) scale(0.95);
            }
            to { 
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .modal-btn-cancel:hover {
            background: #f3f4f6 !important;
            border-color: #9ca3af !important;
            transform: translateY(-1px);
        }
        
        .modal-btn-primary:hover {
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4) !important;
            transform: translateY(-2px);
        }
        
        .modal-btn-cancel:active,
        .modal-btn-primary:active {
            transform: translateY(0) !important;
        }
    `;
    document.head.appendChild(style);
    
    // Close on overlay click
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
            overlay.style.animation = 'fadeOut 0.2s ease-in';
            setTimeout(() => overlay.remove(), 200);
        }
    });
    
    // Close on Escape key
    const escHandler = function(e) {
        if (e.key === 'Escape') {
            overlay.style.animation = 'fadeOut 0.2s ease-in';
            setTimeout(() => overlay.remove(), 200);
            document.removeEventListener('keydown', escHandler);
        }
    };
    document.addEventListener('keydown', escHandler);
}
</script>
@endpush