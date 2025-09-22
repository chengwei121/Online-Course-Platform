<?php

use App\Http\Controllers\Client\CourseController;
use App\Http\Controllers\Client\EnrollmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Client\AssignmentController;
use App\Models\Course;
use App\Http\Controllers\Client\LessonController;
use App\Models\Instructor;
use Illuminate\Support\Facades\Artisan;

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

// Test Route
Route::get('/test-nav', function () {
    return view('test-nav');
})->name('test-nav');

Route::get('/test-layout', function () {
    return view('test-layout');
})->name('test-layout');

// Cache clearing route
Route::get('/clear-cache', function() {
    Artisan::call('optimize:clear');
    return "All caches cleared successfully! <br><a href='/client/courses'>Go to courses</a>";
})->name('clear-cache');

// Debug routes
Route::get('/test-admin-chart', function () {
    return response()->json([
        'test' => 'Admin chart endpoint working',
        'user' => auth()->check() ? auth()->user()->name : 'Not authenticated',
        'period' => request('period', 'month'),
        'labels' => ['Jan', 'Feb', 'Mar'],
        'enrollments' => [1, 2, 3],
        'courses' => [0, 1, 1],
        'teachers' => [0, 0, 1]
    ]);
})->middleware(['auth', 'admin'])->name('test-admin-chart');

// Home Route (Welcome Page)
Route::get('/', function () {
    $featuredCourses = Course::with(['instructor', 'category'])
        ->where('status', 'published')
        ->latest()
        ->take(6)
        ->get();
    
    $instructors = Instructor::select('id', 'name', 'title', 'bio', 'profile_picture')
        ->withCount('courses')
        ->orderByDesc('courses_count')
        ->take(3)
        ->get();

    $trendingCourses = Course::with(['category', 'instructor'])
        ->where('status', 'published')
        ->withCount('enrollments')
        ->orderBy('enrollments_count', 'desc')
        ->orderBy('average_rating', 'desc')
        ->take(6)
        ->get();
    
    return view('welcome', [
        'featuredCourses' => $featuredCourses,
        'instructors' => $instructors,
        'trendingCourses' => $trendingCourses
    ]);
})->name('welcome');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
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
    Route::get('payments-export', [App\Http\Controllers\Admin\PaymentController::class, 'export'])->name('payments.export');
    
    // Payment AJAX endpoints
    Route::get('payments-realtime-stats', [App\Http\Controllers\Admin\PaymentController::class, 'getRealtimeStats'])->name('payments.realtime-stats');
    Route::get('payments-list-ajax', [App\Http\Controllers\Admin\PaymentController::class, 'getPaymentsList'])->name('payments.list-ajax');
    
    // Client Management - Admin can only view, edit, and see enrollments (no create/delete)
    Route::get('clients', [App\Http\Controllers\Admin\ClientController::class, 'index'])->name('clients.index');
    Route::get('clients/{client}', [App\Http\Controllers\Admin\ClientController::class, 'show'])->name('clients.show');
    Route::get('clients/{client}/edit', [App\Http\Controllers\Admin\ClientController::class, 'edit'])->name('clients.edit');
    Route::put('clients/{client}', [App\Http\Controllers\Admin\ClientController::class, 'update'])->name('clients.update');
    Route::patch('clients/{client}', [App\Http\Controllers\Admin\ClientController::class, 'update'])->name('clients.update');
    Route::get('clients/{client}/enrollments', [App\Http\Controllers\Admin\ClientController::class, 'enrollments'])->name('clients.enrollments');
    Route::get('clients/{client}/activities', [App\Http\Controllers\Admin\ClientController::class, 'activities'])->name('clients.activities');
    
    // Administrator Management
    Route::resource('admins', App\Http\Controllers\Admin\AdminController::class)->except(['create', 'store']);
    Route::patch('admins/{admin}/toggle-verification', [App\Http\Controllers\Admin\AdminController::class, 'toggleVerification'])->name('admins.toggle-verification');
});

// Temporary chart data route outside middleware for debugging
Route::get('admin/dashboard/chart-data', [App\Http\Controllers\Admin\DashboardController::class, 'getChartDataAjax'])
    ->middleware(['auth', 'admin'])
    ->name('admin.dashboard.chart-data');

// Real-time stats route
Route::get('admin/dashboard/realtime-stats', [App\Http\Controllers\Admin\DashboardController::class, 'getRealtimeStats'])
    ->middleware(['auth', 'admin'])
    ->name('admin.dashboard.realtime-stats');

// Performance metrics route
Route::get('admin/dashboard/performance-metrics', [App\Http\Controllers\Admin\DashboardController::class, 'getPerformanceMetricsAjax'])
    ->middleware(['auth', 'admin'])
    ->name('admin.dashboard.performance-metrics');

// Test loading screen route
Route::get('admin/dashboard/test-loading', [App\Http\Controllers\Admin\DashboardController::class, 'testLoading'])
    ->middleware(['auth', 'admin'])
    ->name('admin.dashboard.test-loading');

// Teacher Routes (Authentication handled by admin)
Route::prefix('teacher')->name('teacher.')->middleware(['auth', App\Http\Middleware\TeacherMiddleware::class])->group(function () {
    Route::get('dashboard', [App\Http\Controllers\Teacher\DashboardController::class, 'index'])->name('dashboard');
    
    // Course management
    Route::get('courses/by-category', [App\Http\Controllers\Teacher\CourseController::class, 'getCoursesByCategory'])->name('courses.by-category');
    Route::resource('courses', App\Http\Controllers\Teacher\CourseController::class);
    Route::post('courses/{course}/toggle-status', [App\Http\Controllers\Teacher\CourseController::class, 'toggleStatus'])->name('courses.toggle-status');
    
    // Lesson management
    Route::resource('courses.lessons', App\Http\Controllers\Teacher\LessonController::class)->except(['index', 'store']);
    Route::get('courses/{course}/lessons', [App\Http\Controllers\Teacher\LessonController::class, 'index'])->name('courses.lessons.index');
    Route::post('courses/{course}/lessons', [App\Http\Controllers\Teacher\LessonController::class, 'store'])->name('courses.lessons.store')->middleware('upload.size');
    Route::post('courses/{course}/lessons/reorder', [App\Http\Controllers\Teacher\LessonController::class, 'reorder'])->name('courses.lessons.reorder');
    
    // Assignment management
    Route::resource('courses.lessons.assignments', App\Http\Controllers\Teacher\AssignmentController::class)->except(['index']);
    Route::get('courses/{course}/lessons/{lesson}/assignments/create', [App\Http\Controllers\Teacher\AssignmentController::class, 'create'])->name('assignments.create');
    Route::post('courses/{course}/lessons/{lesson}/assignments', [App\Http\Controllers\Teacher\AssignmentController::class, 'store'])->name('assignments.store');
    Route::get('courses/{course}/lessons/{lesson}/assignments/{assignment}', [App\Http\Controllers\Teacher\AssignmentController::class, 'show'])->name('assignments.show');
    Route::get('courses/{course}/lessons/{lesson}/assignments/{assignment}/edit', [App\Http\Controllers\Teacher\AssignmentController::class, 'edit'])->name('assignments.edit');
    Route::put('courses/{course}/lessons/{lesson}/assignments/{assignment}', [App\Http\Controllers\Teacher\AssignmentController::class, 'update'])->name('assignments.update');
    Route::delete('courses/{course}/lessons/{lesson}/assignments/{assignment}', [App\Http\Controllers\Teacher\AssignmentController::class, 'destroy'])->name('assignments.destroy');
    
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

        // Course Learning Routes
        Route::get('courses/{course:slug}/learn/{lesson?}', [CourseController::class, 'learn'])->name('courses.learn');
        Route::post('courses/{course:slug}/lessons/{lesson}/progress', [CourseController::class, 'updateProgress'])->name('lessons.progress');
        Route::post('courses/{course:slug}/lessons/{lesson}/upload-video', [CourseController::class, 'uploadVideo'])->name('courses.upload-video');

        // Assignment Routes
        Route::get('assignments/{assignment}', [AssignmentController::class, 'show'])->name('assignments.show');
        Route::post('assignments/{assignment}/submit', [AssignmentController::class, 'submit'])->name('assignments.submit');
        Route::get('assignments/{assignment}/submissions', [AssignmentController::class, 'submissions'])->name('assignments.submissions');

        // Lesson routes
        Route::get('/lessons/{lesson}', [LessonController::class, 'show'])->name('lessons.show');
        Route::post('/lessons/{lesson}/upload-video', [LessonController::class, 'uploadVideo'])->name('lessons.upload-video');
    });
});