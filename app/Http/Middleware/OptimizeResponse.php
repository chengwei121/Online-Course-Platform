<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OptimizeResponse
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only apply optimizations to successful responses
        if ($response->getStatusCode() === 200) {
            // Add performance headers
            $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            $response->headers->set('X-XSS-Protection', '1; mode=block');
            
            // Enable gzip compression
            if (!$response->headers->has('Content-Encoding') && 
                str_contains($request->header('Accept-Encoding', ''), 'gzip')) {
                $response->headers->set('Content-Encoding', 'gzip');
            }
            
            // Cache control for static assets
            if ($request->is('images/*') || $request->is('css/*') || $request->is('js/*')) {
                $response->headers->set('Cache-Control', 'public, max-age=31536000'); // 1 year
            }
            
            // Browser caching for API responses
            if ($request->is('api/*')) {
                $response->headers->set('Cache-Control', 'public, max-age=300'); // 5 minutes
            }
        }

        return $response;
    }
}