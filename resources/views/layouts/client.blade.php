<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="LearnHub - Transform Your Future Through Quality Education">
    <title>@yield('title') - LearnHub</title>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Playfair+Display:wght@700&family=Montserrat:wght@700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- AOS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <style>
        [x-cloak] { 
            display: none !important; 
        }
        
        :root {
            --primary: #4F46E5;
            --primary-dark: #4338CA;
            --secondary: #0EA5E9;
            --accent: #F59E0B;
            --success: #10B981;
            --danger: #EF4444;
            --warning: #F59E0B;
            --info: #3B82F6;
        }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #f6f7ff 0%, #ffffff 100%);
        }

        /* Logo and Title Styles */
        .brand-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: -0.5px;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logo-icon {
            width: 2.5rem;
            height: 2.5rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px rgba(79, 70, 229, 0.2);
            transition: all 0.3s ease;
        }

        .logo-icon:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 12px rgba(79, 70, 229, 0.3);
        }

        .logo-icon svg {
            width: 1.25rem;
            height: 1.25rem;
            color: white;
        }

        /* Modern Glassmorphism Header */
        .header-wrapper {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(229, 231, 235, 0.5);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 50;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .header-wrapper.scrolled {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid rgba(229, 231, 235, 0.8);
        }

        /* Logo Animation */
        .logo-wrapper {
            position: relative;
            overflow: hidden;
        }

        .logo-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.2),
                transparent
            );
            transition: 0.5s;
        }

        .logo-wrapper:hover::before {
            left: 100%;
        }

        .logo-icon {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            position: relative;
            overflow: hidden;
        }

        .logo-icon::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
            transform: rotate(45deg);
            transition: 0.5s;
        }

        .logo-icon:hover::after {
            transform: rotate(45deg) translate(50%, 50%);
        }

        /* Navigation Links */
        .nav-link {
            position: relative;
            color: #1F2937;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.75rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            z-index: 1;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(-50%);
            z-index: -1;
        }

        .nav-link:hover::before {
            width: 100%;
        }

        .nav-link:hover {
            color: var(--primary);
            transform: translateY(-1px);
        }

        .nav-link.active {
            color: var(--primary);
            background: rgba(79, 70, 229, 0.1);
            box-shadow: 0 2px 4px rgba(79, 70, 229, 0.1);
        }

        .nav-link.active::before {
            width: 100%;
        }

        /* Search Bar */
        .search-wrapper {
            position: relative;
            width: 100%;
            max-width: 24rem;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            border-radius: 1rem;
            border: 2px solid #E5E7EB;
            background: rgba(249, 250, 251, 0.8);
            color: #1F2937;
            font-size: 0.875rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(4px);
        }

        .search-input:focus {
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            transform: translateY(-1px);
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .search-input:focus + .search-icon {
            color: var(--primary);
            transform: translateY(-50%) scale(1.1);
        }

        /* User Menu */
        .user-button {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.75rem;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 0.75rem;
            transition: all 0.2s ease;
        }

        .user-button:hover {
            background: rgba(79, 70, 229, 0.08);
        }

        .user-avatar {
            width: 2rem;
            height: 2rem;
            border-radius: 0.5rem;
            object-fit: cover;
        }

        .user-menu {
            position: absolute;
            right: 0;
            top: calc(100% + 0.5rem);
            width: 240px;
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid rgba(229, 231, 235, 0.5);
            z-index: 50;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.2s ease;
        }

        .user-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        /* Notification Badge */
        .notification-badge {
            position: absolute;
            top: -0.25rem;
            right: -0.25rem;
            padding: 0.25rem 0.5rem;
            background: var(--danger);
            color: white;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 9999px;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4);
            }
            70% {
                box-shadow: 0 0 0 6px rgba(239, 68, 68, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
            }
        }

        /* Mobile Menu Button */
        .mobile-menu-button {
            position: relative;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.75rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .mobile-menu-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(79, 70, 229, 0.1),
                transparent
            );
            transition: 0.5s;
        }

        .mobile-menu-button:hover::before {
            left: 100%;
        }

        .mobile-menu-button:hover {
            background: rgba(79, 70, 229, 0.08);
            transform: translateY(-1px);
        }

        /* Mobile Menu */
        .mobile-menu {
            position: fixed;
            top: 4rem;
            left: 1rem;
            right: 1rem;
            background: rgba(255, 255, 255, 0.95);
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(229, 231, 235, 0.5);
            opacity: 0;
            transform: scale(0.95) translateY(-10px);
            transition: all 0.2s ease;
            visibility: hidden;
            backdrop-filter: blur(12px);
        }

        .mobile-menu.show {
            opacity: 1;
            transform: scale(1) translateY(0);
            visibility: visible;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(79, 70, 229, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(79, 70, 229, 0.3);
        }

        /* Cards */
        .card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: 1px solid rgba(229, 231, 235, 0.5);
            backdrop-filter: blur(4px);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px rgba(0, 0, 0, 0.1);
        }

        /* Progress Bar */
        .progress-bar {
            height: 0.5rem;
            border-radius: 9999px;
            background: #E5E7EB;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            transition: width 0.3s ease;
        }

        /* Alerts */
        .alert {
            border-radius: 1rem;
            padding: 1rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            animation: slideIn 0.3s ease;
            backdrop-filter: blur(4px);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Footer Styles */
        .footer-wrapper {
            background: linear-gradient(180deg, #2d3748 0%, #1a202c 100%);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            position: relative;
        }

        .footer-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(79, 70, 229, 0.05) 0%, rgba(14, 165, 233, 0.05) 100%);
            pointer-events: none;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            position: relative;
        }

        .footer-logo-icon {
            width: 2.5rem;
            height: 2.5rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .footer-logo-text {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #ffffff, #e2e8f0);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .footer-description {
            color: #cbd5e1;
            line-height: 1.625;
            font-size: 0.9375rem;
        }

        .footer-heading {
            color: #f8fafc;
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 1.25rem;
            position: relative;
            display: inline-block;
        }

        .footer-heading::after {
            content: '';
            position: absolute;
            bottom: -0.5rem;
            left: 0;
            width: 2rem;
            height: 2px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 1px;
            box-shadow: 0 0 8px rgba(79, 70, 229, 0.3);
        }

        .footer-link {
            color: #cbd5e1;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.375rem 0;
        }

        .footer-link:hover {
            color: #f8fafc;
            transform: translateX(4px);
        }

        .footer-link i {
            font-size: 0.75rem;
            color: var(--primary);
            opacity: 0;
            transform: translateX(-8px);
            transition: all 0.3s ease;
        }

        .footer-link:hover i {
            opacity: 1;
            transform: translateX(0);
        }

        .social-links {
            display: flex;
            gap: 1rem;
        }

        .social-link {
            width: 2.25rem;
            height: 2.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.75rem;
            background: rgba(255, 255, 255, 0.08);
            color: #cbd5e1;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(4px);
        }

        .social-link::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            top: 100%;
            left: 0;
            transition: all 0.3s ease;
            opacity: 0.9;
        }

        .social-link:hover::before {
            top: 0;
        }

        .social-link i {
            position: relative;
            z-index: 1;
            transition: all 0.3s ease;
        }

        .social-link:hover i {
            color: white;
            transform: scale(1.2);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding-top: 1.5rem;
            margin-top: 3rem;
            position: relative;
        }

        .footer-bottom p {
            color: #cbd5e1;
        }

        .footer-bottom-link {
            color: #cbd5e1;
            transition: all 0.2s ease;
            font-size: 0.875rem;
            position: relative;
        }

        .footer-bottom-link:hover {
            color: #f8fafc;
        }

        .footer-bottom-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 1px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            transition: all 0.3s ease;
            opacity: 0.8;
        }

        .footer-bottom-link:hover::after {
            width: 100%;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Selection */
        ::selection {
            background: rgba(79, 70, 229, 0.2);
            color: var(--primary);
        }

        /* Custom animations */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        /* Custom styles */
        .hero-pattern {
            background-color: #f8fafc;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%234f46e5' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        /* Responsive navigation */
        .mobile-menu {
            transition: transform 0.3s ease-in-out;
        }

        .mobile-menu.hidden {
            transform: translateX(-100%);
        }

        /* Card hover effects */
        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        /* Header Styles */
        .header-wrapper {
            @apply fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-lg border-b border-gray-200/80;
            transition: all 0.3s ease;
        }

        .header-wrapper.scrolled {
            @apply bg-white/95 shadow-sm;
        }

        /* Logo Styles */
        .logo-container {
            @apply flex items-center space-x-2 transition duration-200 hover:opacity-80;
        }

        .logo-icon {
            @apply w-8 h-8 lg:w-10 lg:h-10 flex items-center justify-center rounded-xl bg-gradient-to-r from-primary to-secondary text-white shadow-lg shadow-primary/20;
        }

        .brand-title {
            @apply font-bold bg-clip-text text-transparent bg-gradient-to-r from-primary to-secondary;
        }

        /* Navigation Links */
        .nav-link {
            @apply relative py-2 px-3 rounded-lg font-medium text-gray-700 hover:text-primary hover:bg-gray-50 transition duration-200;
        }

        .nav-link.active {
            @apply text-primary bg-primary-50;
        }

        /* User Menu */
        .user-button {
            @apply flex items-center space-x-2 py-1.5 px-2 rounded-lg hover:bg-gray-50 transition duration-200;
        }

        .user-menu {
            @apply absolute right-0 mt-2 w-48 rounded-xl bg-white shadow-lg ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 transform opacity-0 scale-95 invisible;
            transition: all 0.2s ease;
        }

        .user-menu.show {
            @apply opacity-100 scale-100 visible;
        }

        /* Notification Badge */
        .notification-badge {
            @apply absolute -top-1 -right-1 flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full;
        }

        /* Mobile Menu Animation */
        .mobile-menu-enter {
            @apply transition ease-out duration-200;
        }

        .mobile-menu-enter-from {
            @apply opacity-0 -translate-y-1;
        }

        .mobile-menu-enter-to {
            @apply opacity-100 translate-y-0;
        }

        .mobile-menu-leave {
            @apply transition ease-in duration-150;
        }

        .mobile-menu-leave-from {
            @apply opacity-100 translate-y-0;
        }

        .mobile-menu-leave-to {
            @apply opacity-0 -translate-y-1;
        }

        /* User Menu Animation */
        .user-menu-enter {
            animation: user-menu-in 0.2s ease-out;
        }

        .user-menu-leave {
            animation: user-menu-out 0.15s ease-in;
        }

        @keyframes user-menu-in {
            0% {
                opacity: 0;
                transform: scale(0.95) translateY(-10px);
            }
            100% {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        @keyframes user-menu-out {
            0% {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
            100% {
                opacity: 0;
                transform: scale(0.95) translateY(-10px);
            }
        }

        /* User Menu Button Hover Effect */
        .user-menu-button {
            position: relative;
            overflow: hidden;
        }

        .user-menu-button::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(79, 70, 229, 0.1) 0%, transparent 50%);
            transform: translate(-50%, -50%) scale(0);
            transition: transform 0.3s ease;
        }

        .user-menu-button:hover::after {
            transform: translate(-50%, -50%) scale(2);
        }

        /* User Avatar Ring Animation */
        @keyframes avatar-ring-pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.4);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(79, 70, 229, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(79, 70, 229, 0);
            }
        }

        .avatar-ring {
            animation: avatar-ring-pulse 2s infinite;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen flex flex-col" 
      x-data="{ 
          mobileMenuOpen: false, 
          userMenuOpen: false,
          scrolled: false,
          init() {
              window.addEventListener('scroll', () => {
                  this.scrolled = window.pageYOffset > 20
              });
              AOS.init({
                  duration: 800,
                  easing: 'ease-in-out',
                  once: true
              });
          }
      }">
    <!-- Header -->
    <header class="header-wrapper" :class="{ 'scrolled': scrolled }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-20">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('welcome') }}" class="logo-container">
                        <div class="logo-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <h1 class="brand-title text-xl lg:text-2xl">LearnHub</h1>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden lg:flex items-center space-x-6">
                    @auth
                        <a href="{{ route('client.courses.index') }}" 
                           class="nav-link text-sm lg:text-base {{ request()->routeIs('client.courses.*') && !request()->routeIs('client.courses.learn') ? 'active' : '' }}">
                            <i class="fas fa-graduation-cap mr-2"></i>
                            Courses
                        </a>
                        <a href="{{ route('client.enrollments.index') }}" 
                           class="nav-link text-sm lg:text-base {{ request()->routeIs('client.enrollments.*') ? 'active' : '' }}">
                            <i class="fas fa-bookmark mr-2"></i>
                            My Learning
                        </a>
                    @endauth
                </nav>

                <!-- Right Section -->
                <div class="flex items-center space-x-4">
                    @auth
                        <!-- Notifications -->
                        <div class="relative hidden sm:block">
                            <button class="p-2 rounded-xl hover:bg-gray-100 transition relative group">
                                <i class="fas fa-bell text-gray-600 group-hover:text-primary transition-colors"></i>
                                <span class="notification-badge">2</span>
                            </button>
                        </div>

                        <!-- User Menu -->
                        <div class="relative hidden sm:block" x-data="{ open: false }">
                            <button @click="open = !open"
                                    @keydown.escape.window="open = false"
                                    class="flex items-center gap-3 p-1.5 pl-2 pr-3 rounded-xl hover:bg-gray-50 active:bg-gray-100 transition-all duration-200">
                                <div class="relative">
                                    <img class="w-9 h-9 rounded-lg object-cover ring-2 ring-white" 
                                         src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random" 
                                         alt="{{ auth()->user()->name }}">
                                    <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></div>
                                </div>
                                <div class="hidden sm:block text-left">
                                    <p class="text-sm font-semibold text-gray-800 leading-none mb-0.5">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500 leading-none">Student</p>
                                </div>
                                <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200 hidden sm:block"
                                   :class="{ 'transform rotate-180': open }"></i>
                            </button>

                            <div x-show="open"
                                 x-cloak
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-3 w-72 origin-top-right">
                                <div class="rounded-2xl bg-white shadow-lg ring-1 ring-black ring-opacity-5 overflow-hidden">
                                    <!-- User Info -->
                                    <div class="p-4 bg-white border-b">
                                        <div class="flex items-center gap-4">
                                            <img class="w-12 h-12 rounded-lg border border-gray-200" 
                                                 src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random" 
                                                 alt="{{ auth()->user()->name }}">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-gray-900 font-semibold truncate">{{ auth()->user()->name }}</p>
                                                <p class="text-gray-500 text-sm truncate">{{ auth()->user()->email }}</p>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 mt-1">
                                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                                    Active Student
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Menu Items -->
                                    <div class="p-2">
                                        <div class="grid grid-cols-2 gap-2 mb-3">
                                            <a href="{{ route('client.enrollments.index') }}" 
                                               class="flex flex-col items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                                <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-100 text-blue-600 mb-1">
                                                    <i class="fas fa-bookmark text-sm"></i>
                                                </div>
                                                <span class="text-xs font-medium text-gray-700">My Learning</span>
                                            </a>
                                            <a href="#" 
                                               class="flex flex-col items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                                <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 mb-1">
                                                    <i class="fas fa-certificate text-sm"></i>
                                                </div>
                                                <span class="text-xs font-medium text-gray-700">Certificates</span>
                                            </a>
                                        </div>

                                        <div class="space-y-1">
                                            @if(auth()->user()->isAdmin())
                                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-purple-50 transition-colors">
                                                    <div class="w-7 h-7 flex items-center justify-center rounded-lg bg-purple-100 text-purple-600">
                                                        <i class="fas fa-shield-alt text-sm"></i>
                                                    </div>
                                                    <span class="text-sm font-medium text-purple-700">Admin Panel</span>
                                                </a>
                                            @endif
                                            <a href="#" class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-gray-50 transition-colors">
                                                <div class="w-7 h-7 flex items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                                                    <i class="fas fa-user-circle text-sm"></i>
                                                </div>
                                                <span class="text-sm font-medium text-gray-700">Profile Settings</span>
                                            </a>
                                            <a href="#" class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-gray-50 transition-colors">
                                                <div class="w-7 h-7 flex items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                                                    <i class="fas fa-bell text-sm"></i>
                                                </div>
                                                <span class="text-sm font-medium text-gray-700">Notifications</span>
                                                <span class="ml-auto px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">3</span>
                                            </a>
                                            <a href="#" class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-gray-50 transition-colors">
                                                <div class="w-7 h-7 flex items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                                                    <i class="fas fa-cog text-sm"></i>
                                                </div>
                                                <span class="text-sm font-medium text-gray-700">Settings</span>
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Logout -->
                                    <div class="p-2 border-t border-gray-100">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" 
                                                    class="flex items-center gap-3 w-full p-2.5 rounded-lg hover:bg-gray-50 text-left transition-colors">
                                                <div class="w-7 h-7 flex items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                                                    <i class="fas fa-sign-out-alt text-sm"></i>
                                                </div>
                                                <span class="text-sm font-medium text-gray-700">Sign out</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="hidden sm:flex items-center space-x-4">
                            <a href="{{ route('login') }}" 
                               class="nav-link text-sm lg:text-base">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Login
                            </a>
                            <a href="{{ route('register') }}" 
                               class="btn-primary text-sm lg:text-base">
                                <i class="fas fa-user-plus mr-2"></i>
                                Register
                            </a>
                        </div>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <button type="button"
                            @click="mobileMenuOpen = !mobileMenuOpen"
                            class="inline-flex items-center justify-center p-2 rounded-lg text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary lg:hidden">
                        <span class="sr-only">Open main menu</span>
                        <i class="fas fa-bars h-6 w-6" x-show="!mobileMenuOpen"></i>
                        <i class="fas fa-times h-6 w-6" x-show="mobileMenuOpen" x-cloak></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="lg:hidden"
             x-show="mobileMenuOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-1"
             x-cloak>
            <div class="pt-2 pb-3 space-y-1 px-4 sm:px-6">
                <a href="{{ route('client.courses.index') }}"
                   class="block py-2 px-3 text-base font-medium rounded-lg transition {{ request()->routeIs('client.courses.*') && !request()->routeIs('client.courses.learn') ? 'bg-primary-50 text-primary' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-graduation-cap mr-2"></i>
                    Courses
                </a>
                <a href="{{ route('client.enrollments.index') }}"
                   class="block py-2 px-3 text-base font-medium rounded-lg transition {{ request()->routeIs('client.enrollments.*') ? 'bg-primary-50 text-primary' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-bookmark mr-2"></i>
                    My Learning
                </a>
            </div>

            @auth
                <div class="pt-4 pb-3 border-t border-gray-200">
                    <div class="flex items-center px-4 sm:px-6">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-lg" 
                                 src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random" 
                                 alt="{{ auth()->user()->name }}">
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium text-gray-800">{{ auth()->user()->name }}</div>
                            <div class="text-sm font-medium text-gray-500">{{ auth()->user()->email }}</div>
                        </div>
                        <button class="ml-auto flex-shrink-0 p-2 rounded-lg text-gray-500 hover:bg-gray-100">
                            <i class="fas fa-bell w-6 h-6"></i>
                            <span class="notification-badge">2</span>
                        </button>
                    </div>
                    <div class="mt-3 space-y-1 px-4 sm:px-6">
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}"
                               class="block py-2 px-3 text-base font-medium text-purple-700 hover:bg-purple-50 rounded-lg transition">
                                <i class="fas fa-shield-alt mr-2"></i>
                                Admin Panel
                            </a>
                        @endif
                        <a href="#"
                           class="block py-2 px-3 text-base font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition">
                            <i class="fas fa-cog mr-2"></i>
                            Settings
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full text-left py-2 px-3 text-base font-medium text-red-600 hover:bg-red-50 rounded-lg transition">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Sign out
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="pt-4 pb-3 border-t border-gray-200">
                    <div class="px-4 sm:px-6 space-y-2">
                        <a href="{{ route('login') }}"
                           class="block w-full py-2.5 px-4 text-center text-base font-medium text-primary bg-primary-50 hover:bg-primary-100 rounded-lg transition">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                           class="block w-full py-2.5 px-4 text-center text-base font-medium text-white bg-primary hover:bg-primary-dark rounded-lg transition">
                            <i class="fas fa-user-plus mr-2"></i>
                            Register
                        </a>
                    </div>
                </div>
            @endauth
        </div>
    </header>

    <!-- Main Content -->
    <main>
            @if(session('success'))
                <div class="alert bg-green-50 border-l-4 border-green-500 mb-6" 
                     x-data="{ show: true }" 
                     x-show="show"
                     x-init="setTimeout(() => show = false, 5000)">
                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                    <div class="flex-1">
                        <h3 class="text-green-800 font-medium">Success</h3>
                        <p class="text-green-700 text-sm">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="text-green-500 hover:text-green-700">
                        <i class="fas fa-times"></i>
                    </button>
            @endif

            @if(session('error'))
                <div class="alert bg-red-50 border-l-4 border-red-500 mb-6"
                     x-data="{ show: true }" 
                     x-show="show"
                     x-init="setTimeout(() => show = false, 5000)">
                    <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                    <div class="flex-1">
                        <h3 class="text-red-800 font-medium">Error</h3>
                        <p class="text-red-700 text-sm">{{ session('error') }}</p>
                    </div>
                    <button @click="show = false" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer-wrapper">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <!-- Company Info -->
                <div class="col-span-1 md:col-span-2">
                    <div class="footer-logo mb-6">
                        <div class="footer-logo-icon">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <span class="footer-logo-text">LearnHub</span>
                    </div>
                    <p class="footer-description mb-6">
                        Empowering learners worldwide with quality education and professional development opportunities.
                        Join our community and transform your future today.
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-link" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="footer-heading">Quick Links</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('client.courses.index') }}" class="footer-link">
                                <i class="fas fa-chevron-right"></i>
                                All Courses
                            </a>
                        </li>
                        <li>
                            <a href="#" class="footer-link">
                                <i class="fas fa-chevron-right"></i>
                                About Us
                            </a>
                        </li>
                        <li>
                            <a href="#" class="footer-link">
                                <i class="fas fa-chevron-right"></i>
                                Contact
                            </a>
                        </li>
                        <li>
                            <a href="#" class="footer-link">
                                <i class="fas fa-chevron-right"></i>
                                Blog
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h3 class="footer-heading">Support</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="#" class="footer-link">
                                <i class="fas fa-chevron-right"></i>
                                Help Center
                            </a>
                        </li>
                        <li>
                            <a href="#" class="footer-link">
                                <i class="fas fa-chevron-right"></i>
                                Terms of Service
                            </a>
                        </li>
                        <li>
                            <a href="#" class="footer-link">
                                <i class="fas fa-chevron-right"></i>
                                Privacy Policy
                            </a>
                        </li>
                        <li>
                            <a href="#" class="footer-link">
                                <i class="fas fa-chevron-right"></i>
                                FAQ
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-500 text-sm">
                        &copy; {{ date('Y') }} LearnHub. All rights reserved.
                    </p>
                    <div class="mt-4 md:mt-0 flex gap-6">
                        <a href="#" class="footer-bottom-link">Privacy Policy</a>
                        <a href="#" class="footer-bottom-link">Terms of Service</a>
                        <a href="#" class="footer-bottom-link">Cookie Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
    </script>
</body>
</html> 