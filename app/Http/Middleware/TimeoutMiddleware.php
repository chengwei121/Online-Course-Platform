<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TimeoutMiddleware
{
    /**
     * Handle an incoming request and set appropriate timeouts for heavy operations
     */
    public function handle(Request $request, Closure $next, $timeout = 300)
    {
        // Set execution time limit based on the route
        if ($request->is('admin/dashboard*')) {
            // Dashboard needs more time for data aggregation
            ini_set('max_execution_time', 300); // 5 minutes
            set_time_limit(300);
        } elseif ($request->is('admin/*/reports') || $request->is('admin/*/analytics')) {
            // Reports and analytics need extra time
            ini_set('max_execution_time', 240); // 4 minutes
            set_time_limit(240);
        } elseif ($request->is('admin/*')) {
            // Other admin pages get moderate timeout
            ini_set('max_execution_time', 180); // 3 minutes
            set_time_limit(180);
        } else {
            // Regular pages get standard timeout
            ini_set('max_execution_time', 120); // 2 minutes
            set_time_limit(120);
        }

        // Also increase memory limit for admin operations
        if ($request->is('admin/*')) {
            ini_set('memory_limit', '1024M'); // 1GB for admin operations
        }

        return $next($request);
    }
}
