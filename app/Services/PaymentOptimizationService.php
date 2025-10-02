<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PaymentOptimizationService
{
    /**
     * Pre-validate payment eligibility
     */
    public function preValidatePayment($courseId)
    {
        $user = Auth::user();
        $cacheKey = "payment_validation_{$user->id}_{$courseId}";
        
        return Cache::remember($cacheKey, 300, function() use ($user, $courseId) {
            // Check if course exists and get essential data
            $course = Course::select('id', 'title', 'price', 'is_free', 'teacher_id', 'category_id')
                ->where('id', $courseId)
                ->first();
                
            if (!$course) {
                return ['valid' => false, 'error' => 'Course not found'];
            }
            
            if ($course->is_free) {
                return ['valid' => false, 'error' => 'This is a free course'];
            }
            
            // Check enrollment status
            $isEnrolled = Enrollment::where('user_id', $user->id)
                ->where('course_id', $courseId)
                ->where('payment_status', 'completed')
                ->exists();
                
            if ($isEnrolled) {
                return ['valid' => false, 'error' => 'Already enrolled'];
            }
            
            return [
                'valid' => true,
                'course_id' => $course->id,
                'title' => $course->title,
                'price' => $course->price
            ];
        });
    }
    
    /**
     * Pre-warm PayPal authentication
     */
    public function preWarmPayPal()
    {
        try {
            $paypalService = app(PayPalService::class);
            // This will cache the access token
            $reflection = new \ReflectionClass($paypalService);
            $method = $reflection->getMethod('getAccessToken');
            $method->setAccessible(true);
            $method->invoke($paypalService);
            
            return true;
        } catch (\Exception $e) {
            \Log::error('PayPal pre-warm failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get optimized course data for payment confirmation
     */
    public function getOptimizedCourseData($courseId)
    {
        return Course::select('id', 'title', 'description', 'price', 'thumbnail', 'slug', 'teacher_id', 'category_id')
            ->with([
                'instructor' => function($query) {
                    $query->select('id', 'name');
                },
                'category' => function($query) {
                    $query->select('id', 'name');
                }
            ])
            ->withCount('lessons')
            ->find($courseId);
    }
    
    /**
     * Clear payment validation cache
     */
    public function clearValidationCache($courseId, $userId = null)
    {
        $userId = $userId ?: Auth::id();
        Cache::forget("payment_validation_{$userId}_{$courseId}");
    }
}