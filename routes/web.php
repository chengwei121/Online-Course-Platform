<?php

use App\Http\Controllers\Client\CourseController;
use App\Http\Controllers\Client\EnrollmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Client\AssignmentController;
use App\Models\Course;
use App\Http\Controllers\Client\LessonController;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\CourseReview;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Cache clearing route
Route::get('/clear-cache', function() {
    Artisan::call('optimize:clear');
    return "All caches cleared successfully! <br><a href='/client/courses'>Go to courses</a>";
})->name('clear-cache');

// Home Route (Welcome Page) - Optimized with caching
Route::get('/', function () {
    // Cache welcome page data for 2 hours (7200 seconds) - increased from 15 min
    $welcomeData = Cache::remember('welcome_page_data_v2', 7200, function () {
        return [
            // Optimized: Only load essential fields
            'featuredCourses' => Course::with(['teacher:id,name', 'category:id,name'])
                ->select('id', 'title', 'slug', 'price', 'thumbnail', 'teacher_id', 'category_id', 'average_rating', 'created_at')
                ->where('status', 'published')
                ->latest()
                ->take(6)
                ->get(),
            
            // Cache stats separately with longer duration
            'stats' => Cache::remember('welcome_stats_v2', 3600, function() {
                return [
                    'total_courses' => Course::where('status', 'published')->count(),
                    'total_students' => Student::count(),
                    'total_instructors' => Teacher::where('status', 'active')->count(),
                    'success_rate' => 95,
                ];
            }),
            
            // Optimized: Removed heavy withCount, load lighter version
            'trendingCourses' => Cache::remember('trending_courses_v2', 3600, function() {
                return Course::with(['category:id,name'])
                    ->select('id', 'title', 'slug', 'price', 'thumbnail', 'category_id', 'average_rating', 'created_at')
                    ->where('status', 'published')
                    ->where('average_rating', '>=', 4)
                    ->orderByDesc('average_rating')
                    ->orderByDesc('created_at')
                    ->take(8)
                    ->get();
            }),
            
            'testimonials' => Cache::remember('testimonials_v2', 3600, function() {
                return CourseReview::with(['user:id,name', 'course:id,title'])
                    ->select('id', 'user_id', 'course_id', 'rating', 'comment', 'created_at')
                    ->whereIn('rating', [4, 5])
                    ->whereNotNull('comment')
                    ->where('comment', '!=', '')
                    ->latest()
                    ->take(3)
                    ->get();
            }),
            
            // Optimized: Simplified instructors query
            'instructors' => Cache::remember('top_instructors_v2', 3600, function() {
                return Teacher::select('id', 'name', 'bio', 'profile_picture')
                    ->where('status', 'active')
                    ->take(3)
                    ->get();
            }),
        ];
    });
    
    return view('welcome', $welcomeData);
})->name('welcome');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    
    // Password Reset Routes
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Teacher Management
    Route::resource('teachers', App\Http\Controllers\Admin\TeacherController::class);
    Route::patch('teachers/{teacher}/toggle-status', [App\Http\Controllers\Admin\TeacherController::class, 'toggleStatus'])->name('teachers.toggle-status');
    
    // Course Management (View only - no editing)
    Route::get('courses', [App\Http\Controllers\Admin\CourseController::class, 'index'])->name('courses.index');
    Route::get('courses/{course}', [App\Http\Controllers\Admin\CourseController::class, 'show'])->name('courses.show');
    Route::delete('courses/{course}', [App\Http\Controllers\Admin\CourseController::class, 'destroy'])->name('courses.destroy');
    Route::patch('courses/{course}/toggle-status', [App\Http\Controllers\Admin\CourseController::class, 'toggleStatus'])->name('courses.toggle-status');
    
    // Category Management
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
    
    // Payment Management
    Route::get('payments', [App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/{enrollment}', [App\Http\Controllers\Admin\PaymentController::class, 'show'])->name('payments.show');
    Route::patch('payments/{enrollment}/status', [App\Http\Controllers\Admin\PaymentController::class, 'updateStatus'])->name('payments.update-status');
    Route::get('payments-statistics', [App\Http\Controllers\Admin\PaymentController::class, 'statistics'])->name('payments.statistics');
    Route::get('payments-statistics-export', [App\Http\Controllers\Admin\PaymentController::class, 'exportStatistics'])->name('payments.statistics.export');
    Route::get('payments-export', [App\Http\Controllers\Admin\PaymentController::class, 'export'])->name('payments.export');
    
    // Payment AJAX endpoints
    Route::get('payments-realtime-stats', [App\Http\Controllers\Admin\PaymentController::class, 'getRealtimeStats'])->name('payments.realtime-stats');
    Route::get('payments-list-ajax', [App\Http\Controllers\Admin\PaymentController::class, 'getPaymentsList'])->name('payments.list-ajax');
    
    // Client Management - Admin can only view, edit, and see enrollments (no create/delete)
    Route::get('clients', [App\Http\Controllers\Admin\ClientController::class, 'index'])->name('clients.index');
    Route::get('clients/{client}', [App\Http\Controllers\Admin\ClientController::class, 'show'])->name('clients.show');
    Route::get('clients/{client}/edit', [App\Http\Controllers\Admin\ClientController::class, 'edit'])->name('clients.edit');
    Route::put('clients/{client}', [App\Http\Controllers\Admin\ClientController::class, 'update'])->name('clients.update');
    Route::patch('clients/{client}', [App\Http\Controllers\Admin\ClientController::class, 'update'])->name('clients.patch');
    Route::get('clients/{client}/enrollments', [App\Http\Controllers\Admin\ClientController::class, 'enrollments'])->name('clients.enrollments');
    Route::get('clients/{client}/activities', [App\Http\Controllers\Admin\ClientController::class, 'activities'])->name('clients.activities');
    
    // User Management (Legacy compatibility route)
    Route::get('users', [App\Http\Controllers\Admin\ClientController::class, 'index'])->name('users.index');
    
    // Administrator Management
    Route::resource('admins', App\Http\Controllers\Admin\AdminController::class);
    Route::patch('admins/{admin}/toggle-verification', [App\Http\Controllers\Admin\AdminController::class, 'toggleVerification'])->name('admins.toggle-verification');
    
    // Notification Management
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('create', [App\Http\Controllers\Admin\NotificationController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\NotificationController::class, 'store'])->name('store');
        Route::post('test', [App\Http\Controllers\Admin\NotificationController::class, 'sendTestNotification'])->name('test');
    });
    
    // Email Testing
    Route::prefix('emails')->name('emails.')->group(function () {
        Route::get('test', [App\Http\Controllers\Admin\EmailTestController::class, 'index'])->name('test');
        Route::post('test/send', [App\Http\Controllers\Admin\EmailTestController::class, 'sendTest'])->name('test.send');
        Route::get('test/preview', [App\Http\Controllers\Admin\EmailTestController::class, 'preview'])->name('test.preview');
    });
    
    // Toast Notifications Demo & Testing
    Route::get('toast-demo', function () {
        return view('admin.toast-demo');
    })->name('toast.demo');
    
    Route::get('toast-test/{type}', function ($type) {
        switch ($type) {
            case 'success':
                return redirect()->route('admin.toast.demo')
                    ->with('success', 'This is a success message! Your operation completed successfully.');
            case 'error':
                return redirect()->route('admin.toast.demo')
                    ->with('error', 'This is an error message! Something went wrong with your request.');
            case 'warning':
                return redirect()->route('admin.toast.demo')
                    ->with('warning', 'This is a warning message! Please review your actions carefully.');
            case 'info':
                return redirect()->route('admin.toast.demo')
                    ->with('info', 'This is an info message! Here is some useful information for you.');
            case 'multiple':
                session()->flash('success', 'First success message!');
                session()->flash('info', 'An informational message.');
                session()->flash('warning', 'A warning message to consider.');
                return redirect()->route('admin.toast.demo');
            default:
                return redirect()->route('admin.toast.demo');
        }
    })->name('toast.test');
});

// Dashboard API routes (required for production)
Route::get('admin/dashboard/chart-data', [App\Http\Controllers\Admin\DashboardController::class, 'getChartDataAjax'])
    ->middleware(['auth', 'admin'])
    ->name('admin.dashboard.chart-data');

Route::get('admin/dashboard/realtime-stats', [App\Http\Controllers\Admin\DashboardController::class, 'getRealtimeStats'])
    ->middleware(['auth', 'admin'])
    ->name('admin.dashboard.realtime-stats');

Route::get('admin/dashboard/performance-metrics', [App\Http\Controllers\Admin\DashboardController::class, 'getPerformanceMetricsAjax'])
    ->middleware(['auth', 'admin'])
    ->name('admin.dashboard.performance-metrics');

// Debug/Test routes (only available in local environment)
if (app()->environment('local', 'development')) {
    Route::get('admin/dashboard/test-loading', [App\Http\Controllers\Admin\DashboardController::class, 'testLoading'])
        ->middleware(['auth', 'admin'])
        ->name('admin.dashboard.test-loading');

    Route::get('admin/dashboard/test-chart', function() {
        $controller = new App\Http\Controllers\Admin\DashboardController();
        $reflection = new ReflectionClass($controller);
        $method = $reflection->getMethod('getOptimizedChartData');
        $method->setAccessible(true);
        
        $monthData = $method->invoke($controller, 'month');
        $yearData = $method->invoke($controller, 'year');
        $weekData = $method->invoke($controller, 'week');
        
        return response()->json([
            'month' => $monthData,
            'year' => $yearData,
            'week' => $weekData
        ]);
    })->middleware(['auth', 'admin']);
}

// Teacher Routes (Authentication handled by admin)
Route::prefix('teacher')->name('teacher.')->middleware(['auth', App\Http\Middleware\TeacherMiddleware::class])->group(function () {
    Route::get('dashboard', [App\Http\Controllers\Teacher\DashboardController::class, 'index'])->name('dashboard');
    
    // Course management
    Route::get('courses/by-category', [App\Http\Controllers\Teacher\CourseController::class, 'getCoursesByCategory'])->name('courses.by-category');
    Route::resource('courses', App\Http\Controllers\Teacher\CourseController::class);
    Route::post('courses/{course}/toggle-status', [App\Http\Controllers\Teacher\CourseController::class, 'toggleStatus'])->name('courses.toggle-status');
    
    // Lesson management
    Route::resource('courses.lessons', App\Http\Controllers\Teacher\LessonController::class)->except(['index', 'store', 'create']);
    Route::get('courses/{course}/lessons', [App\Http\Controllers\Teacher\LessonController::class, 'index'])->name('courses.lessons.index');
    Route::post('courses/{course}/lessons', [App\Http\Controllers\Teacher\LessonController::class, 'store'])->name('courses.lessons.store')->middleware('upload.size');
    Route::post('courses/{course}/lessons/reorder', [App\Http\Controllers\Teacher\LessonController::class, 'reorder'])->name('courses.lessons.reorder');
    
    // Assignment management
    Route::get('courses/{course}/lessons/{lesson}/assignments/create', [App\Http\Controllers\Teacher\AssignmentController::class, 'create'])->name('assignments.create');
    Route::post('courses/{course}/lessons/{lesson}/assignments', [App\Http\Controllers\Teacher\AssignmentController::class, 'store'])->name('assignments.store');
    Route::get('courses/{course}/lessons/{lesson}/assignments/{assignment}', [App\Http\Controllers\Teacher\AssignmentController::class, 'show'])->name('assignments.show');
    Route::get('courses/{course}/lessons/{lesson}/assignments/{assignment}/edit', [App\Http\Controllers\Teacher\AssignmentController::class, 'edit'])->name('assignments.edit');
    Route::put('courses/{course}/lessons/{lesson}/assignments/{assignment}', [App\Http\Controllers\Teacher\AssignmentController::class, 'update'])->name('assignments.update');
    Route::delete('courses/{course}/lessons/{lesson}/assignments/{assignment}', [App\Http\Controllers\Teacher\AssignmentController::class, 'destroy'])->name('assignments.destroy');
    
    // Students management
    Route::get('students', [App\Http\Controllers\Teacher\StudentsController::class, 'index'])->name('students.index');
    Route::get('students/{student}', [App\Http\Controllers\Teacher\StudentsController::class, 'show'])->name('students.show');
    
    // Notifications
    Route::get('notifications', [App\Http\Controllers\Teacher\NotificationsController::class, 'index'])->name('notifications.index');
    Route::post('notifications/mark-all-read', [App\Http\Controllers\Teacher\NotificationsController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('notifications/{id}', [App\Http\Controllers\Teacher\NotificationsController::class, 'destroy'])->name('notifications.destroy');
    
    // Profile management
    Route::get('profile', [App\Http\Controllers\Teacher\ProfileController::class, 'index'])->name('profile.index');
    Route::put('profile', [App\Http\Controllers\Teacher\ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('profile/password', [App\Http\Controllers\Teacher\ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::delete('profile/picture', [App\Http\Controllers\Teacher\ProfileController::class, 'removeProfilePicture'])->name('profile.remove-picture');
    
    // Teacher logout
    Route::post('logout', [App\Http\Controllers\Teacher\AuthController::class, 'logout'])->name('logout');
});

// Client Routes
Route::prefix('client')->name('client.')->group(function () {
    // Public Routes
    Route::get('courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('courses/search', [CourseController::class, 'search'])->name('courses.search');
    Route::get('courses/{slug}', [CourseController::class, 'show'])->name('courses.show');

    // Home after login
    Route::get('/', fn() => redirect()->route('client.courses.index'))
        ->middleware('auth')
        ->name('home');

    // All Client Routes require authentication
    Route::middleware('auth')->group(function () {
        // Enrollment Routes
        Route::post('courses/{course}/enroll', [EnrollmentController::class, 'store'])->name('enrollments.store');
        Route::get('my-courses', [EnrollmentController::class, 'index'])->name('enrollments.index');

        // Student Dashboard Routes
        Route::get('my-payments', [App\Http\Controllers\Client\StudentController::class, 'payments'])->name('payments.index');
        Route::get('my-reviews', [App\Http\Controllers\Client\StudentController::class, 'reviews'])->name('reviews.index');

        // Notification Routes
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [App\Http\Controllers\Client\NotificationController::class, 'index'])->name('index');
            Route::get('api/get', [App\Http\Controllers\Client\NotificationController::class, 'getNotifications'])->name('get');
            Route::get('api/count', [App\Http\Controllers\Client\NotificationController::class, 'getUnreadCount'])->name('count');
            Route::post('{notification}/read', [App\Http\Controllers\Client\NotificationController::class, 'markAsRead'])->name('read');
            Route::post('mark-all-read', [App\Http\Controllers\Client\NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
            Route::delete('{notification}', [App\Http\Controllers\Client\NotificationController::class, 'destroy'])->name('destroy');
        });

        // Course Learning Routes
        Route::get('courses/{course:slug}/learn/{lesson?}', [CourseController::class, 'learn'])->name('courses.learn');
        Route::post('courses/{course:slug}/lessons/{lesson}/progress', [CourseController::class, 'updateProgress'])->name('lessons.progress');
        Route::post('courses/{course:slug}/lessons/{lesson}/upload-video', [CourseController::class, 'uploadVideo'])->name('courses.upload-video');

        // Course Review Routes
        Route::post('courses/{course}/review', [CourseController::class, 'storeReview'])->name('courses.review');

        // Assignment Routes
        Route::get('assignments/{assignment}', [AssignmentController::class, 'show'])->name('assignments.show');
        Route::post('assignments/{assignment}/submit', [AssignmentController::class, 'submit'])->name('assignments.submit');
        Route::get('assignments/{assignment}/submissions', [AssignmentController::class, 'submissions'])->name('assignments.submissions');

        // Lesson routes
        Route::get('/lessons/{lesson}', [LessonController::class, 'show'])->name('lessons.show');
        Route::post('/lessons/{lesson}/upload-video', [LessonController::class, 'uploadVideo'])->name('lessons.upload-video');

        // PayPal Payment Routes
        Route::prefix('paypal')->name('paypal.')->group(function () {
            Route::get('confirm/{course}', [App\Http\Controllers\PayPalController::class, 'confirm'])->name('confirm');
            Route::post('pay/{course}', [App\Http\Controllers\PayPalController::class, 'createPayment'])->name('pay');
            Route::get('success', [App\Http\Controllers\PayPalController::class, 'success'])->name('success');
            Route::get('cancel', [App\Http\Controllers\PayPalController::class, 'cancel'])->name('cancel');
            Route::get('status/{payment}', [App\Http\Controllers\PayPalController::class, 'status'])->name('status');
            Route::post('prepare/{course}', [App\Http\Controllers\PayPalController::class, 'preparePayment'])->name('prepare');
        });
    });
});