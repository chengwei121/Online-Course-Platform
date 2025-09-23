<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixCourseImagesSeeder extends Seeder
{
    public function run()
    {
        $courses = DB::table('courses')->whereNotNull('thumbnail')->get();

        foreach ($courses as $course) {
            $thumbnail = $course->thumbnail;
            
            // Clean up the path
            $thumbnail = preg_replace('#^https?://[^/]+/storage/#', '', $thumbnail);
            $thumbnail = str_replace('storage/', '', $thumbnail);
            
            // Ensure proper path structure
            if (!str_starts_with($thumbnail, 'images/courses/')) {
                $thumbnail = 'images/courses/' . ltrim($thumbnail, '/');
            }

            // Update the database
            DB::table('courses')
                ->where('id', $course->id)
                ->update(['thumbnail' => $thumbnail]);
        }
    }
} 