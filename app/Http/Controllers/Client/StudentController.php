<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\CourseReview;

class StudentController extends Controller
{
    /**
     * Display student's payment history
     */
    public function payments()
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Get all enrollments with course and payment details
        $enrollments = $user->enrollments()
            ->with(['course.instructor', 'course.category'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate total amount spent
        $totalSpent = $enrollments->where('payment_status', 'completed')->sum('amount_paid');
        
        // Count courses by status
        $completedCourses = $enrollments->where('payment_status', 'completed')->count();
        $pendingPayments = $enrollments->where('payment_status', 'pending')->count();

        return view('client.students.payments', compact(
            'enrollments', 
            'totalSpent', 
            'completedCourses', 
            'pendingPayments'
        ));
    }

    /**
     * Download payment slip/receipt
     */
    public function downloadPaymentSlip($enrollmentId)
    {
        /** @var User $user */
        $user = Auth::user();
        
        $enrollment = Enrollment::with(['course.instructor', 'user'])
            ->where('id', $enrollmentId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Return HTML view for now (can be converted to PDF later)
        return view('client.students.payment-slip', compact('enrollment'));
    }

    /**
     * Display student's reviews
     */
    public function reviews()
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Get all reviews with course details
        $reviews = $user->courseReviews()
            ->with(['course.instructor', 'course.category'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate review statistics
        $totalReviews = $reviews->count();
        $averageRating = $reviews->avg('rating');
        $fiveStarReviews = $reviews->where('rating', 5)->count();

        return view('client.students.reviews', compact(
            'reviews', 
            'totalReviews', 
            'averageRating', 
            'fiveStarReviews'
        ));
    }
}