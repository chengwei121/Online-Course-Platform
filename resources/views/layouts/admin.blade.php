<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Admin Panel') - {{ config('app.name', 'Online Course Platform') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Admin Custom CSS -->
    <link href="{{ asset('css/admin-custom.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body>
    <!-- Toast Notifications Container (Fixed Top Right) -->
    <div class="toast-container">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show toast-alert" role="alert">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show toast-alert" role="alert">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show toast-alert" role="alert">
                <i class="fas fa-exclamation-triangle"></i>
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info alert-dismissible fade show toast-alert" role="alert">
                <i class="fas fa-info-circle"></i>
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show toast-alert" role="alert">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Validation Errors:</strong>
                <ul class="mb-0 mt-2 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <!-- Logo -->
                    <div class="text-center mb-4">
                        <h4 class="text-white">
                            <i class="fas fa-shield-alt me-2"></i>
                            Admin Panel
                        </h4>
                    </div>
                    
                    <!-- Navigation -->
                    <ul class="nav flex-column">
                        <!-- MAIN SECTION -->
                        <li class="nav-section-header">
                            <small class="text-white-50 text-uppercase fw-bold px-3 py-2 d-block">
                                <i class="fas fa-home me-2"></i>Main
                            </small>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                               href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        
                        <!-- CONTENT MANAGEMENT SECTION -->
                        <li class="nav-section-header mt-3">
                            <small class="text-white-50 text-uppercase fw-bold px-3 py-2 d-block">
                                <i class="fas fa-layer-group me-2"></i>Content Management
                            </small>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}" 
                               href="{{ route('admin.courses.index') }}">
                                <i class="fas fa-book me-2"></i>
                                Courses
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" 
                               href="{{ route('admin.categories.index') }}">
                                <i class="fas fa-folder me-2"></i>
                                Categories
                            </a>
                        </li>
                        
                        <!-- FINANCIAL MANAGEMENT SECTION -->
                        <li class="nav-section-header mt-3">
                            <small class="text-white-50 text-uppercase fw-bold px-3 py-2 d-block">
                                <i class="fas fa-coins me-2"></i>Financial Management
                            </small>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}" 
                               href="{{ route('admin.payments.index') }}">
                                <i class="fas fa-credit-card me-2"></i>
                                Payments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.payments.statistics') ? 'active' : '' }}" 
                               href="{{ route('admin.payments.statistics') }}">
                                <i class="fas fa-chart-line me-2"></i>
                                Revenue Analytics
                            </a>
                        </li>
                        
                        <!-- USER MANAGEMENT SECTION -->
                        <li class="nav-section-header mt-3">
                            <small class="text-white-50 text-uppercase fw-bold px-3 py-2 d-block">
                                <i class="fas fa-users-cog me-2"></i>User Management
                            </small>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}" 
                               href="{{ route('admin.teachers.index') }}">
                                <i class="fas fa-chalkboard-teacher me-2"></i>
                                Teachers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}" 
                               href="{{ route('admin.clients.index') }}">
                                <i class="fas fa-user-graduate me-2"></i>
                                Students
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}" 
                               href="{{ route('admin.admins.index') }}">
                                <i class="fas fa-user-shield me-2"></i>
                                Administrators
                            </a>
                        </li>
                        
                        <!-- SYSTEM TOOLS SECTION -->
                        <li class="nav-section-header mt-3">
                            <small class="text-white-50 text-uppercase fw-bold px-3 py-2 d-block">
                                <i class="fas fa-tools me-2"></i>System Tools
                            </small>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.emails.*') ? 'active' : '' }}" 
                               href="{{ route('admin.emails.test') }}">
                                <i class="fas fa-envelope-open-text me-2"></i>
                                Email Testing
                            </a>
                        </li>
                    </ul>
                    
                    <!-- User Actions -->
                    <div class="mt-auto pt-4">
                        <hr class="text-white-50">
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" role="button" 
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-2"></i>
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <!-- Top Navigation -->
                <nav class="navbar navbar-expand-lg navbar-light bg-light mb-2">
                    <div class="container-fluid">
                        <button class="btn btn-outline-secondary d-md-none" type="button" 
                                onclick="toggleSidebar()">
                            <i class="fas fa-bars"></i>
                        </button>
                        
                        <div class="d-flex align-items-center ms-auto">
                            <span class="text-muted me-3">
                                <i class="fas fa-calendar me-1"></i>
                                {{ now()->format('M d, Y') }}
                            </span>
                            <a href="{{ route('welcome') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-globe me-1"></i>
                                View Site
                            </a>
                        </div>
                    </div>
                </nav>

                <!-- Page Content -->
                <div class="container-fluid">

                    <!-- Page Header -->
                    @hasSection('header')
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-2 pb-2 mb-2 border-bottom">
                            @yield('header')
                        </div>
                    @endif

                    <!-- Main Content -->
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Admin Custom JS -->
    <script>
        // Toggle sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('show');
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar');
            const toggleBtn = document.querySelector('[onclick="toggleSidebar()"]');
            
            if (window.innerWidth <= 767.98) {
                if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.querySelector('.sidebar');
            if (window.innerWidth > 767.98) {
                sidebar.classList.remove('show');
            }
        });
        
        // AJAX Loading System for Dynamic Content
        function showAjaxLoading(container) {
            const element = document.querySelector(container);
            if (element && !element.querySelector('.ajax-loading-overlay')) {
                element.style.position = 'relative';
                const overlay = document.createElement('div');
                overlay.className = 'ajax-loading-overlay';
                overlay.innerHTML = `
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">Loading data...</small>
                        </div>
                    </div>
                `;
                element.appendChild(overlay);
            }
        }
        
        function hideAjaxLoading(container) {
            const element = document.querySelector(container);
            if (element) {
                const overlay = element.querySelector('.ajax-loading-overlay');
                if (overlay) {
                    overlay.remove();
                }
            }
        }
        
        // Enhanced AJAX with automatic loading states
        function performAjaxRequest(url, options = {}) {
            const defaultOptions = {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                loadingContainer: null,
                timeout: 10000
            };
            
            const settings = { ...defaultOptions, ...options };
            
            // Show loading if container specified
            if (settings.loadingContainer) {
                showAjaxLoading(settings.loadingContainer);
            }
            
            // Create timeout promise
            const timeoutPromise = new Promise((_, reject) => {
                setTimeout(() => reject(new Error('Request timeout')), settings.timeout);
            });
            
            // Create fetch promise
            const fetchPromise = fetch(url, {
                method: settings.method,
                headers: settings.headers,
                body: settings.body,
                credentials: 'same-origin'
            });
            
            return Promise.race([fetchPromise, timeoutPromise])
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .catch(error => {
                    console.error('AJAX request failed:', error);
                    
                    // Show error message
                    if (settings.loadingContainer) {
                        const container = document.querySelector(settings.loadingContainer);
                        if (container) {
                            const errorDiv = document.createElement('div');
                            errorDiv.className = 'alert alert-danger';
                            errorDiv.innerHTML = `
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Failed to load data. 
                                <button class="btn btn-sm btn-outline-danger ms-2" onclick="location.reload()">
                                    <i class="fas fa-redo me-1"></i>Refresh Page
                                </button>
                            `;
                            container.innerHTML = '';
                            container.appendChild(errorDiv);
                        }
                    }
                    
                    throw error;
                })
                .finally(() => {
                    // Hide loading
                    if (settings.loadingContainer) {
                        hideAjaxLoading(settings.loadingContainer);
                    }
                });
        }
        
        // Global error handler for uncaught promise rejections
        window.addEventListener('unhandledrejection', function(event) {
            console.error('Unhandled promise rejection:', event.reason);
            
            // Show global error notification
            showErrorNotification('An unexpected error occurred. Please refresh the page.');
            
            // Prevent default browser error message
            event.preventDefault();
        });
        
        // Error notification system
        function showErrorNotification(message, duration = 5000) {
            const notification = document.createElement('div');
            notification.className = 'alert alert-danger alert-dismissible fade show position-fixed';
            notification.style.cssText = `
                top: 20px;
                right: 20px;
                z-index: 9999;
                max-width: 400px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            `;
            notification.innerHTML = `
                <i class="fas fa-exclamation-triangle me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(notification);
            
            // Auto remove after duration
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, duration);
        }
        
        // Success notification system
        function showSuccessNotification(message, duration = 3000) {
            const notification = document.createElement('div');
            notification.className = 'alert alert-success alert-dismissible fade show position-fixed';
            notification.style.cssText = `
                top: 20px;
                right: 20px;
                z-index: 9999;
                max-width: 400px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            `;
            notification.innerHTML = `
                <i class="fas fa-check-circle me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, duration);
        }
        
        // Make functions globally available
        window.showAjaxLoading = showAjaxLoading;
        window.hideAjaxLoading = hideAjaxLoading;
        window.performAjaxRequest = performAjaxRequest;
        window.showErrorNotification = showErrorNotification;
        window.showSuccessNotification = showSuccessNotification;
        
        // Auto-close toast alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const toastAlerts = document.querySelectorAll('.toast-alert');
            toastAlerts.forEach(function(alert) {
                // Auto-close after 5 seconds
                setTimeout(function() {
                    if (alert && alert.parentNode) {
                        // Use Bootstrap's dismiss method
                        const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                        bsAlert.close();
                    }
                }, 5000);
            });
            
            // Keep legacy alerts (non-toast) for backward compatibility
            const flashMessages = document.querySelectorAll('.alert:not(.position-fixed):not(.toast-alert)');
            flashMessages.forEach(function(alert) {
                // Add a progress bar to show remaining time
                const progressBar = document.createElement('div');
                progressBar.style.cssText = `
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    height: 3px;
                    background-color: rgba(255,255,255,0.7);
                    width: 100%;
                    transform-origin: left;
                    animation: alertProgress 5s linear forwards;
                `;
                
                // Add CSS for the progress animation
                if (!document.getElementById('alertProgressCSS')) {
                    const style = document.createElement('style');
                    style.id = 'alertProgressCSS';
                    style.textContent = `
                        @keyframes alertProgress {
                            from { transform: scaleX(1); }
                            to { transform: scaleX(0); }
                        }
                        .alert { position: relative; overflow: hidden; }
                        .sidebar {
                            position: sticky !important;
                            top: 0 !important;
                            height: 100vh !important;
                            overflow: visible !important;
                            overflow-y: visible !important;
                        }
                        .sidebar .position-sticky {
                            height: auto !important;
                            overflow: visible !important;
                        }
                    `;
                    document.head.appendChild(style);
                }
                
                alert.appendChild(progressBar);
                
                // Auto-close after 5 seconds
                setTimeout(function() {
                    if (alert.parentNode) {
                        // Fade out effect
                        alert.style.transition = 'opacity 0.5s ease-out';
                        alert.style.opacity = '0';
                        
                        setTimeout(function() {
                            if (alert.parentNode) {
                                alert.remove();
                            }
                        }, 500);
                    }
                }, 5000);
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
