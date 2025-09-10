<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_teachers' => Teacher::count(),
            'active_teachers' => Teacher::where('status', 'active')->count(),
            'total_courses' => Course::count(),
            'published_courses' => Course::where('status', 'published')->count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_enrollments' => Enrollment::count(),
        ];

        $recentTeachers = Teacher::with('user')
            ->latest()
            ->take(5)
            ->get();

        $recentCourses = Course::with(['instructor', 'category'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentTeachers', 'recentCourses'));
    }
}
