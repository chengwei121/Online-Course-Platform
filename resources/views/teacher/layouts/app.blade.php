<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Teacher Dashboard') - {{ config('app.name') }}</title>
    
    <!-- Preload critical resources -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    
    <!-- Critical CSS inline for faster initial render -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" media="print" onload="this.media='all'">
    
    <!-- Tailwind CSS for content pages -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        .sidebar {
            min-height: 100vh;
            background: #2c3e50;
        }
        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 15px 20px;
            border-bottom: 1px solid #34495e;
        }
        .sidebar .nav-link:hover {
            background: #34495e;
            color: #fff;
        }
        .sidebar .nav-link.active {
            background: #3498db;
            color: #fff;
        }
        .nav-section-header {
            margin-top: 1rem;
        }
        .nav-section-header:first-child {
            margin-top: 0;
        }
        .nav-section-header small {
            color: #bdc3c7;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #34495e;
            margin-bottom: 0.5rem;
        }
        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        /* Additional styles for various components */
        .table-responsive {
            border-radius: 0.375rem;
            overflow: hidden;
        }
        
        .badge {
            font-size: 0.75em;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.175);
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <div class="d-flex flex-column h-100">
                    <div class="p-3 text-center border-bottom border-secondary">
                        <h5 class="text-white mb-0">
                            <i class="fas fa-chalkboard-teacher me-2"></i>
                            Teacher Panel
                        </h5>
                    </div>
                    <!-- Navigation -->
                    <ul class="nav flex-column flex-grow-1">
                        <!-- MAIN SECTION -->
                        <li class="nav-section-header">
                            <small class="text-white-50 text-uppercase fw-bold px-3 py-2 d-block">
                                <i class="fas fa-home me-2"></i>Main
                            </small>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}" 
                               href="{{ route('teacher.dashboard') }}">
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
                            <a class="nav-link {{ request()->routeIs('teacher.courses.*') ? 'active' : '' }}" 
                               href="{{ route('teacher.courses.index') }}">
                                <i class="fas fa-book me-2"></i>
                                My Courses
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('teacher.assignments.*') || request()->routeIs('teacher.submissions.*') ? 'active' : '' }}" 
                               href="{{ route('teacher.assignments.index') }}">
                                <i class="fas fa-tasks me-2"></i>
                                Assignments
                            </a>
                        </li>
                        
                        <!-- STUDENT MANAGEMENT SECTION -->
                        <li class="nav-section-header mt-3">
                            <small class="text-white-50 text-uppercase fw-bold px-3 py-2 d-block">
                                <i class="fas fa-users-cog me-2"></i>Student Management
                            </small>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('teacher.students.*') ? 'active' : '' }}" 
                               href="{{ route('teacher.students.index') }}">
                                <i class="fas fa-users me-2"></i>
                                Students
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('teacher.notifications.*') ? 'active' : '' }}" 
                               href="{{ route('teacher.notifications.index') }}">
                                <i class="fas fa-bell me-2"></i>
                                Notifications
                                @php
                                    $unreadCount = auth()->user()->customNotifications()->where('is_read', false)->count();
                                @endphp
                                @if($unreadCount > 0)
                                    <span class="badge bg-danger rounded-pill ms-2">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </a>
                        </li>
                        
                        <!-- SETTINGS SECTION -->
                        <li class="nav-section-header mt-3">
                            <small class="text-white-50 text-uppercase fw-bold px-3 py-2 d-block">
                                <i class="fas fa-cog me-2"></i>Settings
                            </small>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('teacher.profile.*') ? 'active' : '' }}" 
                               href="{{ route('teacher.profile.index') }}">
                                <i class="fas fa-user-circle me-2"></i>
                                My Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link border-0 bg-transparent text-white w-100 text-start">
                                    <i class="fas fa-sign-out-alt me-2"></i>
                                    Logout
                                </button>
                            </form>
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
                                <li><a class="dropdown-item" href="#">
                                    <i class="fas fa-user me-2"></i>Profile
                                </a></li>
                                <li><a class="dropdown-item" href="#">
                                    <i class="fas fa-cog me-2"></i>Settings
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('teacher.logout') }}">
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
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Top Navbar -->
                <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
                    <div class="container-fluid">
                        <span class="navbar-brand mb-0 h1">@yield('page-title', 'Dashboard')</span>
                        
                        <div class="navbar-nav ms-auto">
                            <span class="nav-link text-muted">
                                <i class="fas fa-calendar me-2"></i>
                                {{ now()->format('M d, Y') }}
                            </span>
                        </div>
                    </div>
                </nav>

                <!-- Page Content -->
                <div class="container-fluid py-4">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Load JavaScript asynchronously for better performance -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" defer></script>
    
    <!-- Performance optimizations -->
    <script>
        // Optimize page load with reduced reflows
        (function() {
            'use strict';
            
            // Minimize DOM access
            const ready = function(fn) {
                if (document.readyState !== 'loading') {
                    fn();
                } else {
                    document.addEventListener('DOMContentLoaded', fn);
                }
            };
            
            ready(function() {
                // Add CSS for sticky sidebar behavior
                if (!document.getElementById('sidebarStickyCSS')) {
                    const style = document.createElement('style');
                    style.id = 'sidebarStickyCSS';
                    style.textContent = `
                        .sidebar {
                            position: sticky !important;
                            top: 0 !important;
                            height: 100vh !important;
                            overflow: visible !important;
                            overflow-y: auto !important;
                        }
                        .sidebar .position-sticky {
                            height: auto !important;
                            overflow: visible !important;
                        }
                    `;
                    document.head.appendChild(style);
                }
                
                // Debounce scroll events for better performance
                let scrollTimer;
                window.addEventListener('scroll', function() {
                    if (scrollTimer) {
                        window.cancelAnimationFrame(scrollTimer);
                    }
                    scrollTimer = window.requestAnimationFrame(function() {
                        // Your scroll handling code here if needed
                    });
                }, { passive: true });
                
                // Auto-dismiss alerts after 5 seconds
                const alerts = document.querySelectorAll('.alert-dismissible');
                alerts.forEach(function(alert) {
                    setTimeout(function() {
                        const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                        bsAlert.close();
                    }, 5000);
                });
            });
            
            // Lazy load images if any
            if ('loading' in HTMLImageElement.prototype) {
                const images = document.querySelectorAll('img[loading="lazy"]');
                images.forEach(img => {
                    img.src = img.dataset.src || img.src;
                });
            } else {
                // Fallback for browsers that don't support lazy loading
                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/lazysizes@5.3.2/lazysizes.min.js';
                script.async = true;
                document.body.appendChild(script);
            }
        })();
    </script>
    
    @stack('scripts')
</body>
</html>
