@extends('layouts.client')

@section('title', 'Confirm Payment - ' . $course->title)

@push('styles')
<style>
    /* Payment Confirmation Specific Styles */
    main {
        height: calc(100vh - 80px); /* Adjust for header height */
        overflow: hidden;
    }
    
    .container-fluid {
        height: 100%;
        display: flex;
        align-items: center;
    }
    
    .step-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        position: relative;
        z-index: 2;
    }
    
    .step-circle.completed {
        background: var(--success-color);
        color: white;
    }
    
    .step-circle.active {
        background: var(--primary-color);
        color: white;
    }
    
    .step-circle.pending {
        background: #e5e7eb;
        color: #9ca3af;
    }
    
    .step-line {
        height: 2px;
        background: #e5e7eb;
        flex: 1;
        position: relative;
        top: -1px;
    }
    

    
    .form-check-input {
        width: 1.2em;
        height: 1.2em;
        margin-top: 0.1em;
        cursor: pointer;
    }
    
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .form-check-input:focus {
        border-color: #86b7fe;
        outline: 0;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .form-check-label {
        cursor: pointer;
        margin-left: 0.5rem;
    }
    
    .summary-item {
        padding: 12px 0;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .summary-item:last-child {
        border-bottom: none;
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .security-badge {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 0.875rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .guarantee-badge {
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 0.875rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .course-thumbnail {
        border-radius: 12px;
        object-fit: cover;
        width: 100%;
        height: 200px;
    }
    
    @media (max-width: 768px) {
        .course-thumbnail {
            height: 150px;
        }
        
        .step-circle {
            width: 40px;
            height: 40px;
        }
        
        .step-circle i {
            font-size: 0.875rem;
        }
        
        .payment-method-card {
            padding: 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container px-4 py-4" style="margin-top: 100px;">
    <!-- Header -->
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <!-- Payment Confirmation Card -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i>
                        Confirm Your Purchase
                    </h5>
                </div>
                
                <div class="card-body p-5">
                    @if(session('payment_success'))
                        <!-- Success Message -->
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                                <div>
                                    <h5 class="alert-heading mb-1">Payment Successful!</h5>
                                    <p class="mb-0">{{ session('payment_success') }}</p>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        
                        <!-- Course Access Section -->
                        <div class="text-center mb-4">
                            <div class="bg-light rounded p-4">
                                <i class="fas fa-graduation-cap fa-3x text-primary mb-3"></i>
                                <h4 class="text-primary mb-2">Welcome to Your Course!</h4>
                                <p class="text-muted mb-3">You now have lifetime access to all course materials</p>
                                <a href="{{ route('client.courses.show', $course->slug) }}" 
                                   class="btn btn-primary btn-lg">
                                    <i class="fas fa-play-circle me-2"></i>Start Learning Now
                                </a>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Course Information -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <img src="{{ $course->thumbnail_url }}" 
                                 alt="{{ $course->title }}" 
                                 class="img-fluid rounded"
                                 style="height: 110px; width: 100%; object-fit: cover;">
                        </div>
                        <div class="col-md-8">
                            <h6 class="fw-bold mb-1">{{ $course->title }}</h6>
                            <p class="text-muted mb-2 small">{{ $course->category->name ?? 'Uncategorized' }}</p>
                            <div class="row g-2">
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user-tie text-primary me-2"></i>
                                        <span>{{ $course->instructor->name ?? 'Unknown Instructor' }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-play-circle text-primary me-2"></i>
                                        <span>{{ $course->lessons->count() }} Lessons</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Payment Summary -->
                    <div class="row mb-3">
                        <div class="col-md-5">
                            <div class="bg-light rounded p-2">
                                <small class="fw-bold d-block mb-1">Total Amount:</small>
                                <h5 class="text-primary mb-0">RM{{ number_format($course->price, 2) }}</h5>
                            </div>
                        </div>
                        
                        <div class="col-md-7">
                            <small class="fw-bold d-block mb-2">What's Included:</small>
                            <div class="row g-2">
                                <div class="col-6">
                                    <small><i class="fas fa-check text-success me-2"></i>Lifetime access</small>
                                </div>
                                <div class="col-6">
                                    <small><i class="fas fa-check text-success me-2"></i>{{ $course->lessons->count() }} lessons</small>
                                </div>
                                <div class="col-6">
                                    <small><i class="fas fa-check text-success me-2"></i>Assignments</small>
                                </div>
                                <div class="col-6">
                                    <small><i class="fas fa-check text-success me-2"></i>Certificate</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Payment Information -->
                    <div class="mb-3">
                        <small class="fw-bold d-block mb-2">Payment Method</small>
                        @if(session('course_purchased'))
                            <div class="alert alert-success py-2 mb-0 d-flex align-items-center">
                                <div class="me-2">
                                    <i class="fas fa-check-circle fa-lg text-success"></i>
                                </div>
                                <div>
                                    <small class="fw-bold d-block text-success">Payment Completed</small>
                                    <small class="text-muted">Successfully paid via PayPal</small>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-primary py-2 mb-0 d-flex align-items-center">
                                <div class="me-2">
                                    <i class="fab fa-paypal fa-lg text-primary"></i>
                                </div>
                                <div>
                                    <small class="fw-bold d-block">Pay with PayPal</small>
                                    <small class="text-muted">Secure payment - redirected to PayPal</small>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if(session('course_purchased'))
                        <!-- Already Purchased - Navigation Options -->
                        <div class="row align-items-center pt-2">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span class="text-success fw-bold">Course Purchased Successfully</span>
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="{{ route('client.courses.index') }}" 
                                   class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-list me-1"></i>Browse Courses
                                </a>
                                
                                <a href="{{ route('client.courses.show', $course->slug) }}" 
                                   class="btn btn-success">
                                    <i class="fas fa-play-circle me-1"></i>
                                    Access Course
                                </a>
                            </div>
                        </div>
                    @else
                        <!-- Terms and Buttons -->
                        <div class="row align-items-center pt-2">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" required>
                                    <label class="form-check-label small" for="terms">
                                        I agree to the <a href="#" class="text-primary">Terms</a> and <a href="#" class="text-primary">Privacy Policy</a>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="{{ route('client.courses.show', $course->slug) }}" 
                                   class="btn btn-outline-secondary btn-sm me-2">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                                
                                <form id="paymentForm" action="{{ route('client.paypal.pay', $course) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" 
                                            id="proceedPayment"
                                            class="btn btn-primary"
                                            data-amount="{{ number_format($course->price, 2) }}"
                                            disabled>
                                        <i class="fas fa-lock me-1"></i>
                                        Pay RM{{ number_format($course->price, 2) }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
}

.step-circle.completed {
    background-color: #28a745;
}

.step-circle.active {
    background-color: #007bff;
}

.step-circle.pending {
    background-color: #6c757d;
}

.step-line {
    height: 2px;
    width: 60px;
    background-color: #dee2e6;
}

/* Payment Loading Overlay */
.payment-loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.95);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(2px);
}

.payment-loading-content {
    text-align: center;
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    max-width: 400px;
    width: 90%;
}

.payment-loading-content .spinner-border {
    width: 3rem;
    height: 3rem;
}

/* Enhanced Payment Button */
#proceedPayment {
    position: relative;
    transition: all 0.3s ease;
    min-width: 180px;
}

#proceedPayment:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
}

#proceedPayment:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

/* Performance optimizations */
.course-thumbnail {
    will-change: transform;
    transition: transform 0.2s ease;
}

.card {
    backface-visibility: hidden;
    transform: translateZ(0);
}


</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const termsCheckbox = document.getElementById('terms');
    const proceedButton = document.getElementById('proceedPayment');
    const paymentForm = document.getElementById('paymentForm');
    
    // Pre-prepare payment data when page loads
    const courseId = {{ $course->id }};
    let paymentPrepared = false;
    let paypalReady = false;
    
    // Pre-warm PayPal connection in background
    setTimeout(() => {
        fetch(`{{ route('client.paypal.prepare', ':id') }}`.replace(':id', courseId), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                paymentPrepared = true;
                paypalReady = data.paypal_ready;
                console.log('Payment pre-warmed successfully');
                
                // Show ready indicator
                const readyIndicator = document.createElement('small');
                readyIndicator.className = 'text-success d-block mt-1';
                readyIndicator.innerHTML = '<i class="fas fa-check-circle me-1"></i>PayPal connection ready';
                proceedButton.parentNode.appendChild(readyIndicator);
            }
        })
        .catch(error => {
            console.log('Payment pre-warm failed:', error);
        });
    }, 500);
    
    // Only run payment form logic if elements exist (not purchased yet)
    if (termsCheckbox && proceedButton && paymentForm) {
        // Ensure button is initially disabled
        proceedButton.disabled = true;
        
        // Enable/disable proceed button based on terms acceptance
        termsCheckbox.addEventListener('change', function() {
            proceedButton.disabled = !this.checked;
            
            // Visual feedback
            if (this.checked) {
                proceedButton.classList.remove('btn-secondary');
                proceedButton.classList.add('btn-primary');
            } else {
                proceedButton.classList.remove('btn-primary');
                proceedButton.classList.add('btn-secondary');
            }
        });
        
        // Also handle click events on the checkbox
        termsCheckbox.addEventListener('click', function() {
            // Small delay to ensure the checked state is updated
            setTimeout(() => {
                proceedButton.disabled = !this.checked;
            }, 10);
        });
        
        // Form submission with enhanced loading state and performance feedback
        paymentForm.addEventListener('submit', function(e) {
            if (!termsCheckbox.checked) {
                e.preventDefault();
                alert('Please agree to the Terms and Privacy Policy to continue.');
                return false;
            }
            
            // Immediate UI feedback
            proceedButton.disabled = true;
            const originalText = proceedButton.innerHTML;
            
            if (paypalReady) {
                proceedButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Redirecting to PayPal...';
            } else {
                proceedButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Connecting to PayPal...';
            }
            
            // Add loading overlay
            const loadingOverlay = document.createElement('div');
            loadingOverlay.id = 'payment-loading';
            loadingOverlay.className = 'payment-loading-overlay';
            loadingOverlay.innerHTML = `
                <div class="payment-loading-content">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <h5>${paypalReady ? 'Redirecting to PayPal...' : 'Connecting to PayPal...'}</h5>
                    <p class="text-muted">Please wait while we ${paypalReady ? 'redirect you to' : 'connect to'} PayPal's secure servers.</p>
                    <div class="progress mt-3" style="height: 4px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                             role="progressbar" style="width: ${paypalReady ? '50' : '0'}%"></div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-shield-alt me-1"></i>
                            Secured by PayPal SSL encryption
                        </small>
                    </div>
                </div>
            `;
            document.body.appendChild(loadingOverlay);
            
            // Animate progress bar
            const progressBar = loadingOverlay.querySelector('.progress-bar');
            let progress = paypalReady ? 50 : 0;
            const progressInterval = setInterval(() => {
                progress += Math.random() * 15;
                if (progress > 95) progress = 95;
                progressBar.style.width = progress + '%';
            }, 200);
            
            // Clean up if page doesn't redirect in time
            const timeoutDuration = paypalReady ? 8000 : 12000;
            setTimeout(() => {
                clearInterval(progressInterval);
                if (document.getElementById('payment-loading')) {
                    document.body.removeChild(loadingOverlay);
                    proceedButton.disabled = false;
                    proceedButton.innerHTML = originalText;
                    
                    // Show error message
                    const errorMsg = document.createElement('div');
                    errorMsg.className = 'alert alert-warning mt-3';
                    errorMsg.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Connection timeout. Please try again.';
                    paymentForm.parentNode.insertBefore(errorMsg, paymentForm.nextSibling);
                    
                    setTimeout(() => errorMsg.remove(), 5000);
                }
            }, timeoutDuration);
        });
    }
    
    // Auto-dismiss success alert after 10 seconds
    const successAlert = document.querySelector('.alert-success');
    if (successAlert) {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(successAlert);
            bsAlert.close();
        }, 10000);
    }
    
    // Performance optimization: Preload critical resources
    const link1 = document.createElement('link');
    link1.rel = 'dns-prefetch';
    link1.href = '//www.paypal.com';
    document.head.appendChild(link1);
    
    const link2 = document.createElement('link');
    link2.rel = 'dns-prefetch';
    link2.href = '//api.sandbox.paypal.com';
    document.head.appendChild(link2);
});
</script>
@endpush
@endsection