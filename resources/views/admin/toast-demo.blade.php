@extends('layouts.admin')

@section('title', 'Toast Notifications Demo')

@section('content')
<div class="container-fluid px-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-bell me-2"></i>Toast Notifications Demo
        </h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
        </a>
    </div>

    <!-- Info Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>About Toast Notifications:</strong> All success, error, warning, and info messages now appear as beautiful toast notifications in the top-right corner. They auto-dismiss after 5 seconds but you can close them manually or hover to pause the timer.
            </div>
        </div>
    </div>

    <!-- Demo Buttons -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-vial me-2"></i>Test Toast Notifications
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Click the buttons below to see different types of toast notifications:</p>
                    
                    <div class="d-grid gap-3">
                        <form action="{{ route('admin.toast.test', ['type' => 'success']) }}" method="GET">
                            <button type="submit" class="btn btn-success w-100 btn-lg">
                                <i class="fas fa-check-circle me-2"></i>Show Success Toast
                            </button>
                        </form>

                        <form action="{{ route('admin.toast.test', ['type' => 'error']) }}" method="GET">
                            <button type="submit" class="btn btn-danger w-100 btn-lg">
                                <i class="fas fa-exclamation-circle me-2"></i>Show Error Toast
                            </button>
                        </form>

                        <form action="{{ route('admin.toast.test', ['type' => 'warning']) }}" method="GET">
                            <button type="submit" class="btn btn-warning w-100 btn-lg">
                                <i class="fas fa-exclamation-triangle me-2"></i>Show Warning Toast
                            </button>
                        </form>

                        <form action="{{ route('admin.toast.test', ['type' => 'info']) }}" method="GET">
                            <button type="submit" class="btn btn-info w-100 btn-lg">
                                <i class="fas fa-info-circle me-2"></i>Show Info Toast
                            </button>
                        </form>

                        <form action="{{ route('admin.toast.test', ['type' => 'multiple']) }}" method="GET">
                            <button type="submit" class="btn btn-primary w-100 btn-lg">
                                <i class="fas fa-layer-group me-2"></i>Show Multiple Toasts
                            </button>
                        </form>

                        <button type="button" class="btn btn-secondary w-100 btn-lg" onclick="triggerJavaScriptToast()">
                            <i class="fas fa-code me-2"></i>Trigger via JavaScript
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-check-double me-2"></i>Features
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>No Layout Shift:</strong> Fixed positioning prevents content from jumping
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Auto-Dismiss:</strong> Automatically closes after 5 seconds
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Progress Bar:</strong> Visual indicator showing remaining time
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Pause on Hover:</strong> Hover over toast to pause auto-close timer
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Smooth Animations:</strong> Beautiful slide-in and fade-out effects
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Stack Support:</strong> Multiple toasts stack vertically
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Responsive:</strong> Adapts perfectly to mobile devices
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Manual Close:</strong> Click the × button to close anytime
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-code me-2"></i>Usage in Controllers
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-2">Use session flash messages in your controllers:</p>
                    <pre class="bg-dark text-light p-3 rounded"><code>// Success
return redirect()->back()
    ->with('success', 'Operation successful!');

// Error
return redirect()->back()
    ->with('error', 'Something went wrong!');

// Warning
return redirect()->back()
    ->with('warning', 'Please be careful!');

// Info
return redirect()->back()
    ->with('info', 'Here is some info!');</code></pre>
                </div>
            </div>
        </div>
    </div>

    <!-- Color Reference -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-palette me-2"></i>Toast Color Reference
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="p-3 text-center rounded" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
                                <i class="fas fa-check-circle fa-2x mb-2"></i>
                                <h6 class="mb-0">Success</h6>
                                <small>Green Gradient</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="p-3 text-center rounded" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white;">
                                <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                                <h6 class="mb-0">Error</h6>
                                <small>Red Gradient</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="p-3 text-center rounded" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white;">
                                <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                <h6 class="mb-0">Warning</h6>
                                <small>Orange Gradient</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="p-3 text-center rounded" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white;">
                                <i class="fas fa-info-circle fa-2x mb-2"></i>
                                <h6 class="mb-0">Info</h6>
                                <small>Blue Gradient</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function triggerJavaScriptToast() {
    // You can use the global functions from admin.blade.php
    if (typeof showSuccessNotification === 'function') {
        showSuccessNotification('This toast was triggered via JavaScript!', 5000);
    } else {
        alert('JavaScript toast functions are available in the layout!');
    }
}
</script>
@endpush
