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
    
    // Course Management
    Route::resource('courses', App\Http\Controllers\Admin\CourseController::class);
    Route::patch('courses/{course}/toggle-status', [App\Http\Controllers\Admin\CourseController::class, 'toggleStatus'])->name('courses.toggle-status');
    
    // Client Management
    Route::resource('clients', App\Http\Controllers\Admin\ClientController::class);
    Route::get('clients/{client}/enrollments', [App\Http\Controllers\Admin\ClientController::class, 'enrollments'])->name('clients.enrollments');
});

// Teacher Routes (Authentication handled by admin)
Route::prefix('teacher')->name('teacher.')->middleware(['auth', App\Http\Middleware\TeacherMiddleware::class])->group(function () {
    Route::get('dashboard', [App\Http\Controllers\Teacher\DashboardController::class, 'index'])->name('dashboard');
    
    // Course management
    Route::resource('courses', App\Http\Controllers\Teacher\CourseController::class);
    Route::post('courses/{course}/toggle-status', [App\Http\Controllers\Teacher\CourseController::class, 'toggleStatus'])->name('courses.toggle-status');
    
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