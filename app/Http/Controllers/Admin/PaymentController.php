<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Enrollment::with(['user', 'course', 'course.instructor'])
            ->whereHas('course', function($q) {
                $q->where('is_free', false);
            });
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('course', function($courseQuery) use ($search) {
                    $courseQuery->where('title', 'like', "%{$search}%");
                });
            });
        }
        
        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }
        
        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->where('enrolled_at', '>=', Carbon::parse($request->date_from)->startOfDay());
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->where('enrolled_at', '<=', Carbon::parse($request->date_to)->endOfDay());
        }
        
        // Filter by amount range
        if ($request->has('amount_min') && $request->amount_min) {
            $query->where('amount_paid', '>=', $request->amount_min);
        }
        
        if ($request->has('amount_max') && $request->amount_max) {
            $query->where('amount_paid', '<=', $request->amount_max);
        }
        
        $payments = $query->orderBy('enrolled_at', 'desc')->paginate(15);
        
        // Calculate statistics
        $stats = $this->calculatePaymentStats($request);
        
        return view('admin.payments.index', compact('payments', 'stats'));
    }
    
    public function show(Enrollment $enrollment)
    {
        $enrollment->load(['user', 'course', 'course.instructor', 'course.category']);
        
        // Ensure this is a paid course enrollment
        if ($enrollment->course->is_free) {
            return redirect()->route('admin.payments.index')
                ->with('error', 'This enrollment is for a free course.');
        }
        
        // Get related information
        $relatedPayments = Enrollment::with(['course'])
            ->where('user_id', $enrollment->user_id)
            ->where('id', '!=', $enrollment->id)
            ->whereHas('course', function($q) {
                $q->where('is_free', false);
            })
            ->orderBy('enrolled_at', 'desc')
            ->limit(5)
            ->get();
        
        $userStats = [
            'total_purchases' => Enrollment::where('user_id', $enrollment->user_id)
                ->whereHas('course', function($q) {
                    $q->where('is_free', false);
                })
                ->count(),
            'total_spent' => Enrollment::where('user_id', $enrollment->user_id)
                ->whereHas('course', function($q) {
                    $q->where('is_free', false);
                })
                ->sum('amount_paid'),
            'completed_courses' => Enrollment::where('user_id', $enrollment->user_id)
                ->whereNotNull('completed_at')
                ->count()
        ];
        
        return view('admin.payments.show', compact('enrollment', 'relatedPayments', 'userStats'));
    }
    
    public function updateStatus(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,completed,failed',
            'notes' => 'nullable|string|max:500'
        ]);
        
        $oldStatus = $enrollment->payment_status;
        $enrollment->update([
            'payment_status' => $request->payment_status
        ]);
        
        // Log the status change (you can expand this to include audit logging)
        $message = "Payment status updated from '{$oldStatus}' to '{$request->payment_status}'";
        if ($request->notes) {
            $message .= ". Notes: {$request->notes}";
        }
        
        return redirect()->back()->with('success', $message);
    }
    
    public function statistics(Request $request)
    {
        $period = $request->get('period', 'all'); // all, custom
        
        // Calculate date range based on period or custom dates
        if ($period === 'custom' && $request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->get('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->get('end_date'))->endOfDay();
            $dateRange = [$startDate, $endDate];
        } else {
            // Default: show all data (from earliest to latest enrollment)
            $earliestEnrollment = Enrollment::whereHas('course', function($q) {
                    $q->where('is_free', false);
                })->min('enrolled_at');
            
            $latestEnrollment = Enrollment::whereHas('course', function($q) {
                    $q->where('is_free', false);
                })->max('enrolled_at');
                
            if ($earliestEnrollment && $latestEnrollment) {
                $dateRange = [
                    Carbon::parse($earliestEnrollment)->startOfDay(),
                    Carbon::parse($latestEnrollment)->endOfDay()
                ];
            } else {
                // No data available, use current month as fallback
                $dateRange = [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];
            }
        }
        
        // Revenue statistics
        $revenueStats = $this->getRevenueStats($dateRange, $period);
        
        // Top courses by revenue
        $topCourses = Enrollment::with(['course'])
            ->whereHas('course', function($q) {
                $q->where('is_free', false);
            })
            ->whereBetween('enrolled_at', $dateRange)
            ->where('payment_status', 'completed')
            ->selectRaw('course_id, COUNT(*) as enrollments, SUM(amount_paid) as revenue')
            ->groupBy('course_id')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();
        
        // Top users by spending
        $topUsers = Enrollment::with(['user'])
            ->whereHas('course', function($q) {
                $q->where('is_free', false);
            })
            ->whereBetween('enrolled_at', $dateRange)
            ->where('payment_status', 'completed')
            ->selectRaw('user_id, COUNT(*) as purchases, SUM(amount_paid) as total_spent')
            ->groupBy('user_id')
            ->orderByDesc('total_spent')
            ->limit(10)
            ->get();
        
        return view('admin.payments.statistics', compact(
            'revenueStats', 'topCourses', 'topUsers', 'period'
        ));
    }
    
    public function export(Request $request)
    {
        $query = Enrollment::with(['user', 'course', 'course.instructor'])
            ->whereHas('course', function($q) {
                $q->where('is_free', false);
            });
        
        // Apply same filters as index
        if ($request->has('payment_status') && $request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }
        
        if ($request->has('date_from') && $request->date_from) {
            $query->where('enrolled_at', '>=', Carbon::parse($request->date_from)->startOfDay());
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->where('enrolled_at', '<=', Carbon::parse($request->date_to)->endOfDay());
        }
        
        $payments = $query->orderBy('enrolled_at', 'desc')->get();
        
        $filename = 'payments-export-' . now()->format('Y-m-d-His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'Payment ID',
                'Student Name',
                'Student Email',
                'Course Title',
                'Instructor',
                'Amount Paid',
                'Payment Status',
                'Enrollment Date',
                'Completion Date'
            ]);
            
            // CSV Data
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->id,
                    $payment->user->name,
                    $payment->user->email,
                    $payment->course->title,
                    $payment->course->instructor->name,
                    $payment->amount_paid,
                    ucfirst($payment->payment_status),
                    $payment->enrolled_at->format('Y-m-d H:i:s'),
                    $payment->completed_at ? $payment->completed_at->format('Y-m-d H:i:s') : 'Not completed'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Get real-time payment statistics for AJAX updates
     */
    public function getRealtimeStats(Request $request)
    {
        $stats = $this->calculatePaymentStats($request);
        
        return response()->json([
            'success' => true,
            'stats' => $stats,
            'timestamp' => now()->format('H:i:s')
        ]);
    }
    
    /**
     * Get paginated payments for AJAX updates
     */
    public function getPaymentsList(Request $request)
    {
        $query = Enrollment::with(['user', 'course', 'course.instructor'])
            ->whereHas('course', function($q) {
                $q->where('is_free', false);
            });
        
        // Apply filters
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('course', function($courseQuery) use ($search) {
                    $courseQuery->where('title', 'like', "%{$search}%");
                });
            });
        }
        
        if ($request->has('payment_status') && $request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }
        
        if ($request->has('date_from') && $request->date_from) {
            $query->where('enrolled_at', '>=', Carbon::parse($request->date_from)->startOfDay());
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->where('enrolled_at', '<=', Carbon::parse($request->date_to)->endOfDay());
        }
        
        if ($request->has('amount_min') && $request->amount_min) {
            $query->where('amount_paid', '>=', $request->amount_min);
        }
        
        if ($request->has('amount_max') && $request->amount_max) {
            $query->where('amount_paid', '<=', $request->amount_max);
        }
        
        $payments = $query->orderBy('enrolled_at', 'desc')->paginate(15);
        
        return response()->json([
            'success' => true,
            'payments' => $payments->items(),
            'pagination' => [
                'current_page' => $payments->currentPage(),
                'last_page' => $payments->lastPage(),
                'per_page' => $payments->perPage(),
                'total' => $payments->total(),
                'from' => $payments->firstItem(),
                'to' => $payments->lastItem(),
                'has_more_pages' => $payments->hasMorePages(),
                'links' => $payments->appends($request->query())->links()->render()
            ],
            'timestamp' => now()->format('H:i:s')
        ]);
    }
    
    private function calculatePaymentStats($request = null)
    {
        $query = Enrollment::whereHas('course', function($q) {
            $q->where('is_free', false);
        });
        
        // Apply filters if provided
        if ($request) {
            if ($request->has('date_from') && $request->date_from) {
                $query->where('enrolled_at', '>=', Carbon::parse($request->date_from)->startOfDay());
            }
            
            if ($request->has('date_to') && $request->date_to) {
                $query->where('enrolled_at', '<=', Carbon::parse($request->date_to)->endOfDay());
            }
        }
        
        $totalPayments = $query->count();
        $totalRevenue = $query->where('payment_status', 'completed')->sum('amount_paid');
        $pendingPayments = $query->where('payment_status', 'pending')->count();
        $failedPayments = $query->where('payment_status', 'failed')->count();
        $averageOrderValue = $totalPayments > 0 ? $query->avg('amount_paid') : 0;
        
        // Calculate this month's revenue
        $thisMonthRevenue = Enrollment::whereHas('course', function($q) {
                $q->where('is_free', false);
            })
            ->where('payment_status', 'completed')
            ->whereBetween('enrolled_at', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])
            ->sum('amount_paid');
        
        // Calculate last month's revenue for comparison
        $lastMonthRevenue = Enrollment::whereHas('course', function($q) {
                $q->where('is_free', false);
            })
            ->where('payment_status', 'completed')
            ->whereBetween('enrolled_at', [
                Carbon::now()->subMonth()->startOfMonth(),
                Carbon::now()->subMonth()->endOfMonth()
            ])
            ->sum('amount_paid');
        
        $revenueGrowth = $lastMonthRevenue > 0 
            ? (($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 
            : 0;
        
        return [
            'total_payments' => $totalPayments,
            'total_revenue' => $totalRevenue,
            'pending_payments' => $pendingPayments,
            'failed_payments' => $failedPayments,
            'average_order_value' => $averageOrderValue,
            'this_month_revenue' => $thisMonthRevenue,
            'revenue_growth' => $revenueGrowth
        ];
    }
    
    private function getDateRange($period)
    {
        switch ($period) {
            case 'day':
                return [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()];
            case 'week':
                return [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
            case 'month':
                return [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];
            case 'year':
                return [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()];
            default:
                return [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];
        }
    }
    
    private function getRevenueStats($dateRange, $period)
    {
        $query = Enrollment::whereHas('course', function($q) {
                $q->where('is_free', false);
            })
            ->where('payment_status', 'completed')
            ->whereBetween('enrolled_at', $dateRange);

        switch ($period) {
            case 'all':
                // For showing all data, determine best grouping based on total range
                $daysDiff = Carbon::parse($dateRange[0])->diffInDays(Carbon::parse($dateRange[1]));
                
                if ($daysDiff <= 31) {
                    // Less than 31 days - group by day
                    return $query->selectRaw('DATE(enrolled_at) as date, SUM(amount_paid) as revenue, COUNT(*) as transactions')
                        ->groupByRaw('DATE(enrolled_at)')
                        ->orderBy('date')
                        ->get()
                        ->map(function($item) {
                            $item->period = Carbon::parse($item->date)->format('M j'); // e.g., "Sep 18"
                            return $item;
                        });
                } else {
                    // More than 31 days - group by month
                    return $query->selectRaw('YEAR(enrolled_at) as year, MONTH(enrolled_at) as month, SUM(amount_paid) as revenue, COUNT(*) as transactions')
                        ->groupByRaw('YEAR(enrolled_at), MONTH(enrolled_at)')
                        ->orderByRaw('YEAR(enrolled_at), MONTH(enrolled_at)')
                        ->get()
                        ->map(function($item) {
                            $item->period = Carbon::createFromDate($item->year, $item->month, 1)->format('M Y'); // e.g., "Sep 2025"
                            return $item;
                        });
                }
                
            case 'day':
                // Group by hour for today
                return $query->selectRaw('HOUR(enrolled_at) as period, SUM(amount_paid) as revenue, COUNT(*) as transactions')
                    ->groupByRaw('HOUR(enrolled_at)')
                    ->orderBy('period')
                    ->get();
                    
            case 'week':
                // Group by day for this week
                return $query->selectRaw('DATE(enrolled_at) as date, SUM(amount_paid) as revenue, COUNT(*) as transactions')
                    ->groupByRaw('DATE(enrolled_at)')
                    ->orderBy('date')
                    ->get()
                    ->map(function($item) {
                        $item->period = Carbon::parse($item->date)->format('l'); // Day name
                        return $item;
                    });
                    
            case 'month':
                // Group by day for this month
                return $query->selectRaw('DATE(enrolled_at) as date, SUM(amount_paid) as revenue, COUNT(*) as transactions')
                    ->groupByRaw('DATE(enrolled_at)')
                    ->orderBy('date')
                    ->get()
                    ->map(function($item) {
                        $item->period = Carbon::parse($item->date)->format('M j'); // e.g., "Sep 18"
                        return $item;
                    });
                    
            case 'custom':
                // For custom date ranges, determine best grouping based on range length
                $daysDiff = Carbon::parse($dateRange[0])->diffInDays(Carbon::parse($dateRange[1]));
                
                if ($daysDiff <= 1) {
                    // Less than 1 day - group by hour
                    return $query->selectRaw('HOUR(enrolled_at) as period, SUM(amount_paid) as revenue, COUNT(*) as transactions')
                        ->groupByRaw('HOUR(enrolled_at)')
                        ->orderBy('period')
                        ->get();
                } elseif ($daysDiff <= 31) {
                    // Less than 31 days - group by day and fill missing days
                    $results = $query->selectRaw('DATE(enrolled_at) as date, SUM(amount_paid) as revenue, COUNT(*) as transactions')
                        ->groupByRaw('DATE(enrolled_at)')
                        ->orderBy('date')
                        ->get()
                        ->keyBy('date');
                    
                    // Fill missing days with zero values
                    $filledData = collect();
                    $currentDate = Carbon::parse($dateRange[0]);
                    $endDate = Carbon::parse($dateRange[1]);
                    
                    while ($currentDate->lte($endDate)) {
                        $dateKey = $currentDate->format('Y-m-d');
                        $existing = $results->get($dateKey);
                        
                        if ($existing) {
                            $existing->period = $currentDate->format('M j');
                            $filledData->push($existing);
                        } else {
                            $filledData->push((object) [
                                'date' => $dateKey,
                                'revenue' => 0,
                                'transactions' => 0,
                                'period' => $currentDate->format('M j')
                            ]);
                        }
                        $currentDate->addDay();
                    }
                    
                    return $filledData;
                } else {
                    // More than 31 days - group by month and fill missing months
                    $results = $query->selectRaw('YEAR(enrolled_at) as year, MONTH(enrolled_at) as month, SUM(amount_paid) as revenue, COUNT(*) as transactions')
                        ->groupByRaw('YEAR(enrolled_at), MONTH(enrolled_at)')
                        ->orderByRaw('YEAR(enrolled_at), MONTH(enrolled_at)')
                        ->get()
                        ->keyBy(function($item) {
                            return $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);
                        });
                    
                    // Fill missing months with zero values
                    $filledData = collect();
                    $currentDate = Carbon::parse($dateRange[0])->startOfMonth();
                    $endDate = Carbon::parse($dateRange[1])->endOfMonth();
                    
                    while ($currentDate->lte($endDate)) {
                        $monthKey = $currentDate->format('Y-m');
                        $existing = $results->get($monthKey);
                        
                        if ($existing) {
                            $existing->period = $currentDate->format('M Y');
                            $filledData->push($existing);
                        } else {
                            $filledData->push((object) [
                                'year' => $currentDate->year,
                                'month' => $currentDate->month,
                                'revenue' => 0,
                                'transactions' => 0,
                                'period' => $currentDate->format('M Y')
                            ]);
                        }
                        $currentDate->addMonth();
                    }
                    
                    return $filledData;
                }
                    
            case 'year':
                // Group by month for this year
                return $query->selectRaw('MONTH(enrolled_at) as period, SUM(amount_paid) as revenue, COUNT(*) as transactions')
                    ->groupByRaw('MONTH(enrolled_at)')
                    ->orderBy('period')
                    ->get();
                    
            default:
                return $query->selectRaw('DATE(enrolled_at) as period, SUM(amount_paid) as revenue, COUNT(*) as transactions')
                    ->groupByRaw('DATE(enrolled_at)')
                    ->orderBy('period')
                    ->get();
        }
    }
}