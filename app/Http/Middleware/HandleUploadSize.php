<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HandleUploadSize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if request exceeds post_max_size
        $maxPostSize = $this->parseSize(ini_get('post_max_size'));
        $contentLength = $request->server('CONTENT_LENGTH');

        if ($contentLength && $contentLength > $maxPostSize) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'File too large for upload. Please use the Video URL option instead.',
                    'max_size' => ini_get('post_max_size'),
                    'received_size' => $this->formatBytes($contentLength),
                    'suggestion' => 'Upload your video to YouTube, Google Drive, or Dropbox and use the Video URL option.'
                ], 413);
            }

            return back()->withErrors([
                'video' => 'File too large for upload. Maximum size: ' . ini_get('post_max_size') . '. Please use the Video URL option instead.'
            ])->withInput();
        }

        return $next($request);
    }

    /**
     * Parse size string to bytes
     */
    private function parseSize($size)
    {
        $size = trim($size);
        $last = strtolower($size[strlen($size) - 1]);
        $size = (int) $size;

        switch ($last) {
            case 'g':
                $size *= 1024;
            case 'm':
                $size *= 1024;
            case 'k':
                $size *= 1024;
        }

        return $size;
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}