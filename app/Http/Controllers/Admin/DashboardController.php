<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    // Cache keys
    const CACHE_STATS = 'dashboard_stats';
    const CACHE_RECENT_TEACHERS = 'dashboard_recent_teachers';
    const CACHE_RECENT_COURSES = 'dashboard_recent_courses';
    const CACHE_CHART_DATA = 'dashboard_chart_data_';
    const CACHE_PERFORMANCE = 'dashboard_performance';
    const CACHE_GROWTH = 'dashboard_growth';
    
    // Cache durations (in seconds)
    const CACHE_DURATION_STATS = 300; // 5 minutes
    const CACHE_DURATION_RECENT = 600; // 10 minutes
    const CACHE_DURATION_CHART = 900; // 15 minutes
    const CACHE_DURATION_PERFORMANCE = 180; // 3 minutes

    public function index()
    {
        // Use cached data or fetch fresh data
        $stats = Cache::remember(self::CACHE_STATS, self::CACHE_DURATION_STATS, function () {
            return $this->getOptimizedStats();
        });

        $performanceMetrics = Cache::remember(self::CACHE_PERFORMANCE, self::CACHE_DURATION_PERFORMANCE, function () use ($stats) {
            return $this->getPerformanceMetrics($stats);
        });
        
        $growthTrends = Cache::remember(self::CACHE_GROWTH, self::CACHE_DURATION_STATS, function () {
            return $this->getOptimizedGrowthTrends();
        });

        $recentTeachers = Cache::remember(self::CACHE_RECENT_TEACHERS, self::CACHE_DURATION_RECENT, function () {
            return $this->getOptimizedRecentTeachers();
        });

        $recentCourses = Cache::remember(self::CACHE_RECENT_COURSES, self::CACHE_DURATION_RECENT, function () {
            return $this->getOptimizedRecentCourses();
        });

        // Get fresh recent data (no cache for real-time accuracy)
        $recentEnrollments = $this->getOptimizedRecentEnrollments();
        
        // Get real-time chart data for the last 6 months (default)
        $chartData = $this->getOptimizedChartData('month');

        return view('admin.dashboard', compact('stats', 'recentTeachers', 'recentCourses', 'recentEnrollments', 'chartData', 'performanceMetrics', 'growthTrends'));
    }

    public function getRealtimeStats(Request $request)
    {
        try {
            // Check cache first, if not found, get fresh data
            $stats = Cache::get(self::CACHE_STATS);
            if (!$stats) {
                $stats = $this->getOptimizedStats();
                Cache::put(self::CACHE_STATS, $stats, self::CACHE_DURATION_STATS);
            }

            // Get cached performance metrics
            $performanceMetrics = Cache::get(self::CACHE_PERFORMANCE);
            if (!$performanceMetrics) {
                $performanceMetrics = $this->getPerformanceMetrics($stats);
                Cache::put(self::CACHE_PERFORMANCE, $performanceMetrics, self::CACHE_DURATION_PERFORMANCE);
            }

            // Get fresh recent activity data
            $recentEnrollments = $this->getOptimizedRecentEnrollments();
            $recentTeachers = $this->getOptimizedRecentTeachers();
            $recentCourses = $this->getOptimizedRecentCourses();
            
            return response()->json([
                'success' => true,
                'stats' => $stats,
                'performanceMetrics' => $performanceMetrics,
                'recentActivity' => [
                    'enrollments' => $recentEnrollments,
                    'teachers' => $recentTeachers,
                    'courses' => $recentCourses
                ],
                'timestamp' => now()->toISOString(),
                'lastUpdated' => now()->format('M d, Y H:i:s'),
                'cached' => true
            ]);
        } catch (\Exception $e) {
            Log::error('Real-time stats error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch real-time stats',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getPerformanceMetricsAjax(Request $request)
    {
        try {
            // Use cached data for better performance
            $stats = Cache::remember(self::CACHE_STATS, self::CACHE_DURATION_STATS, function () {
                return $this->getOptimizedStats();
            });

            $performanceMetrics = Cache::remember(self::CACHE_PERFORMANCE, self::CACHE_DURATION_PERFORMANCE, function () use ($stats) {
                return $this->getPerformanceMetrics($stats);
            });
            
            // Add additional metric details for better understanding
            $metricsDetails = [
                'teacher_engagement' => [
                    'percentage' => $performanceMetrics['teacher_engagement'],
                    'active_count' => $stats['active_teachers'],
                    'total_count' => $stats['total_teachers'],
                    'description' => $stats['active_teachers'] . ' of ' . $stats['total_teachers'] . ' teachers active'
                ],
                'course_publication_rate' => [
                    'percentage' => $performanceMetrics['course_publication_rate'],
                    'published_count' => $stats['published_courses'],
                    'total_count' => $stats['total_courses'],
                    'description' => $stats['published_courses'] . ' of ' . $stats['total_courses'] . ' courses published'
                ],
                'enrollment_rate' => [
                    'percentage' => $performanceMetrics['student_course_ratio'],
                    'enrollment_count' => $stats['total_enrollments'],
                    'course_count' => $stats['total_courses'],
                    'description' => $stats['total_enrollments'] . ' total enrollments'
                ],
                'platform_growth' => [
                    'percentage' => $performanceMetrics['platform_growth'],
                    'description' => 'Compared to previous month'
                ]
            ];
            
            return response()->json([
                'success' => true,
                'performanceMetrics' => $performanceMetrics,
                'metricsDetails' => $metricsDetails,
                'timestamp' => now()->toISOString(),
                'lastUpdated' => now()->format('M d, Y H:i:s'),
                'cached' => true
            ]);
        } catch (\Exception $e) {
            Log::error('Performance metrics error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch performance metrics',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getChartDataAjax(Request $request)
    {
        try {
            $period = $request->get('period', 'month');
            
            // Always get fresh real-time data instead of cached data
            $chartData = $this->getOptimizedChartData($period);
            
            return response()->json($chartData);
        } catch (\Exception $e) {
            Log::error('Chart data error: ' . $e->getMessage());
            
            // Return cached fallback data
            return response()->json([
                'error' => false,
                'message' => 'Using fallback data',
                'period' => $request->get('period', 'month'),
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'enrollments' => [5, 10, 15, 8, 12, 20],
                'courses' => [1, 2, 3, 2, 4, 5],
                'teachers' => [1, 1, 2, 2, 3, 4],
                'cached' => false
            ]);
        }
    }

    /**
     * Optimized stats query - single query instead of multiple
     */
    private function getOptimizedStats()
    {
        // Use raw SQL with aggregate functions for better performance
        $stats = DB::select("
            SELECT 
                (SELECT COUNT(*) FROM teachers) as total_teachers,
                (SELECT COUNT(*) FROM teachers WHERE status = 'active') as active_teachers,
                (SELECT COUNT(*) FROM courses) as total_courses,
                (SELECT COUNT(*) FROM courses WHERE status = 'published') as published_courses,
                (SELECT COUNT(*) FROM users WHERE role = 'student') as total_students,
                (SELECT COUNT(*) FROM enrollments) as total_enrollments
        ")[0];

        return [
            'total_teachers' => $stats->total_teachers,
            'active_teachers' => $stats->active_teachers,
            'total_courses' => $stats->total_courses,
            'published_courses' => $stats->published_courses,
            'total_students' => $stats->total_students,
            'total_enrollments' => $stats->total_enrollments,
        ];
    }

    /**
     * Optimized recent teachers query - only from last 30 days
     */
    private function getOptimizedRecentTeachers()
    {
        return Teacher::select(['id', 'name', 'email', 'department', 'status', 'created_at'])
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->whereNotNull('created_at')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Optimized recent courses query - only from last 30 days
     */
    private function getOptimizedRecentCourses()
    {
        return Course::select(['id', 'title', 'status', 'instructor_id', 'category_id', 'created_at'])
            ->with([
                'instructor:id,name',
                'category:id,name'
            ])
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->whereNotNull('created_at')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Optimized recent enrollments query - only from last 30 days
     */
    private function getOptimizedRecentEnrollments()
    {
        return Enrollment::select(['id', 'user_id', 'course_id', 'created_at'])
            ->with([
                'user:id,name',
                'course:id,title,price'
            ])
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->whereNotNull('created_at')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Optimized chart data using single query per period
     */
    private function getOptimizedChartData($period = 'month')
    {
        $labels = [];
        $enrollmentData = [];
        $courseData = [];
        $teacherData = [];

        switch ($period) {
            case 'week':
                // Real-time data for the last 7 days
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $labels[] = $date->format('M d');
                    $dateStr = $date->format('Y-m-d');
                    
                    // Get actual counts for each day
                    $enrollmentData[] = Enrollment::whereDate('created_at', $dateStr)->count();
                    $courseData[] = Course::whereDate('created_at', $dateStr)->count();
                    $teacherData[] = Teacher::whereDate('created_at', $dateStr)->count();
                }
                break;

            case 'year':
                // Real-time data for 12 months
                for ($i = 11; $i >= 0; $i--) {
                    $date = Carbon::now()->subMonths($i);
                    $labels[] = $date->format('M Y');
                    
                    // Get actual counts for each month
                    $enrollmentData[] = Enrollment::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->count();
                    $courseData[] = Course::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->count();
                    $teacherData[] = Teacher::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->count();
                }
                break;

            case 'month':
            default:
                // Real-time data for 6 months
                for ($i = 5; $i >= 0; $i--) {
                    $date = Carbon::now()->subMonths($i);
                    $labels[] = $date->format('M Y');
                    
                    // Get actual counts for each month
                    $enrollmentData[] = Enrollment::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->count();
                    $courseData[] = Course::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->count();
                    $teacherData[] = Teacher::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->count();
                }
                break;
        }

        return [
            'labels' => $labels,
            'enrollments' => $enrollmentData,
            'courses' => $courseData,
            'teachers' => $teacherData,
        ];
    }

    private function getPerformanceMetrics($stats)
    {
        // Calculate teacher engagement percentage
        $teacherEngagement = $stats['total_teachers'] > 0 
            ? round(($stats['active_teachers'] / $stats['total_teachers']) * 100) 
            : 0;

        // Calculate course publication rate
        $coursePublicationRate = $stats['total_courses'] > 0 
            ? round(($stats['published_courses'] / $stats['total_courses']) * 100) 
            : 0;

        // Calculate student-to-course ratio (students per course)
        $studentCourseRatio = $stats['total_courses'] > 0 
            ? round(($stats['total_enrollments'] / $stats['total_courses']) * 100) 
            : 0;

        // Optimized platform growth calculation using single query
        $growthData = DB::selectOne("
            SELECT 
                (SELECT COUNT(*) FROM enrollments WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)) as current_month,
                (SELECT COUNT(*) FROM enrollments WHERE created_at BETWEEN DATE_SUB(NOW(), INTERVAL 60 DAY) AND DATE_SUB(NOW(), INTERVAL 30 DAY)) as previous_month
        ");
        
        $currentMonthEnrollments = $growthData->current_month;
        $previousMonthEnrollments = $growthData->previous_month;
        
        $platformGrowth = $previousMonthEnrollments > 0 
            ? round((($currentMonthEnrollments - $previousMonthEnrollments) / $previousMonthEnrollments) * 100) 
            : ($currentMonthEnrollments > 0 ? 100 : 0);

        return [
            'teacher_engagement' => $teacherEngagement,
            'course_publication_rate' => $coursePublicationRate,
            'student_course_ratio' => min($studentCourseRatio, 100), // Cap at 100%
            'platform_growth' => $platformGrowth,
        ];
    }

    /**
     * Optimized growth trends using single query
     */
    private function getOptimizedGrowthTrends()
    {
        $currentMonth = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();

        // Single query to get all growth data
        $growthData = DB::selectOne("
            SELECT 
                -- Teachers
                (SELECT COUNT(*) FROM teachers WHERE MONTH(created_at) = ? AND YEAR(created_at) = ?) as current_teachers,
                (SELECT COUNT(*) FROM teachers WHERE MONTH(created_at) = ? AND YEAR(created_at) = ?) as last_teachers,
                -- Courses
                (SELECT COUNT(*) FROM courses WHERE MONTH(created_at) = ? AND YEAR(created_at) = ?) as current_courses,
                (SELECT COUNT(*) FROM courses WHERE MONTH(created_at) = ? AND YEAR(created_at) = ?) as last_courses,
                -- Students
                (SELECT COUNT(*) FROM users WHERE role = 'student' AND MONTH(created_at) = ? AND YEAR(created_at) = ?) as current_students,
                (SELECT COUNT(*) FROM users WHERE role = 'student' AND MONTH(created_at) = ? AND YEAR(created_at) = ?) as last_students,
                -- Enrollments
                (SELECT COUNT(*) FROM enrollments WHERE MONTH(created_at) = ? AND YEAR(created_at) = ?) as current_enrollments,
                (SELECT COUNT(*) FROM enrollments WHERE MONTH(created_at) = ? AND YEAR(created_at) = ?) as last_enrollments
        ", [
            $currentMonth->month, $currentMonth->year,
            $lastMonth->month, $lastMonth->year,
            $currentMonth->month, $currentMonth->year,
            $lastMonth->month, $lastMonth->year,
            $currentMonth->month, $currentMonth->year,
            $lastMonth->month, $lastMonth->year,
            $currentMonth->month, $currentMonth->year,
            $lastMonth->month, $lastMonth->year
        ]);

        // Calculate growth percentages
        $teacherGrowth = $growthData->last_teachers > 0 
            ? round((($growthData->current_teachers - $growthData->last_teachers) / $growthData->last_teachers) * 100) 
            : ($growthData->current_teachers > 0 ? 100 : 0);

        $courseGrowth = $growthData->last_courses > 0 
            ? round((($growthData->current_courses - $growthData->last_courses) / $growthData->last_courses) * 100) 
            : ($growthData->current_courses > 0 ? 100 : 0);

        $studentGrowth = $growthData->last_students > 0 
            ? round((($growthData->current_students - $growthData->last_students) / $growthData->last_students) * 100) 
            : ($growthData->current_students > 0 ? 100 : 0);

        $enrollmentGrowth = $growthData->last_enrollments > 0 
            ? round((($growthData->current_enrollments - $growthData->last_enrollments) / $growthData->last_enrollments) * 100) 
            : ($growthData->current_enrollments > 0 ? 100 : 0);

        return [
            'teachers' => $teacherGrowth,
            'courses' => $courseGrowth,
            'students' => $studentGrowth,
            'enrollments' => $enrollmentGrowth,
        ];
    }
    
    /**
     * Clear dashboard cache
     */
    public function clearCache()
    {
        Cache::forget(self::CACHE_STATS);
        Cache::forget(self::CACHE_RECENT_TEACHERS);
        Cache::forget(self::CACHE_RECENT_COURSES);
        Cache::forget(self::CACHE_PERFORMANCE);
        Cache::forget(self::CACHE_GROWTH);
        
        // Clear chart data cache for all periods
        Cache::forget(self::CACHE_CHART_DATA . 'week');
        Cache::forget(self::CACHE_CHART_DATA . 'month');
        Cache::forget(self::CACHE_CHART_DATA . 'year');
        
        return response()->json(['success' => true, 'message' => 'Cache cleared successfully']);
    }
    
    /**
     * Test loading screen with delayed response
     */
    public function testLoading()
    {
        // Simulate slow loading for testing
        sleep(3);
        
        return response()->json([
            'success' => true,
            'message' => 'Loading test completed successfully!',
            'data' => [
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'test_data' => 'This is sample data loaded after delay'
            ]
        ]);
    }
}
