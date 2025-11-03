<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VideoStreamController extends Controller
{
    public function stream(Request $request, $path)
    {
        // Decode the path
        $videoPath = base64_decode($path);
        
        // Security: Ensure the path is within videos directory
        if (!str_starts_with($videoPath, 'videos/')) {
            abort(404);
        }

        // Check if file exists in storage
        if (!Storage::disk('public')->exists($videoPath)) {
            abort(404, 'Video not found');
        }

        $fullPath = Storage::disk('public')->path($videoPath);
        $fileSize = filesize($fullPath);
        $mimeType = Storage::disk('public')->mimeType($videoPath);

        // Get range header
        $range = $request->header('Range');
        
        if ($range) {
            // Parse range header
            list($start, $end) = $this->parseRange($range, $fileSize);
            
            // Create streaming response with range support
            $stream = function() use ($fullPath, $start, $end) {
                $file = fopen($fullPath, 'rb');
                fseek($file, $start);
                
                $buffer = 8192; // 8KB chunks
                $bytesToRead = $end - $start + 1;
                
                while (!feof($file) && $bytesToRead > 0) {
                    $chunkSize = min($buffer, $bytesToRead);
                    echo fread($file, $chunkSize);
                    flush();
                    $bytesToRead -= $chunkSize;
                }
                
                fclose($file);
            };

            return response()->stream($stream, 206, [
                'Content-Type' => $mimeType,
                'Content-Length' => $end - $start + 1,
                'Content-Range' => "bytes {$start}-{$end}/{$fileSize}",
                'Accept-Ranges' => 'bytes',
                'Cache-Control' => 'public, max-age=31536000',
            ]);
        }

        // No range requested, serve entire file
        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Content-Length' => $fileSize,
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }

    private function parseRange($range, $fileSize)
    {
        // Range format: bytes=start-end
        $range = str_replace('bytes=', '', $range);
        $parts = explode('-', $range);
        
        $start = intval($parts[0]);
        $end = isset($parts[1]) && $parts[1] !== '' ? intval($parts[1]) : $fileSize - 1;
        
        // Validate range
        if ($start > $end || $start < 0 || $end >= $fileSize) {
            $start = 0;
            $end = $fileSize - 1;
        }
        
        return [$start, $end];
    }
}
