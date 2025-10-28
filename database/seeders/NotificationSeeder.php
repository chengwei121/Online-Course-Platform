<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $userId = 7; // Change this to your actual user ID if different
        
        DB::table('notifications')->insert([
            [
                'type' => 'grade',
                'title' => 'Assignment Graded',
                'message' => 'Your assignment "Introduction to PHP" has been graded. You received 95/100. Great work!',
                'user_id' => $userId,
                'is_read' => false,
                'priority' => 'high',
                'action_url' => '/client/assignments/1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'type' => 'assignment',
                'title' => 'New Assignment Posted',
                'message' => 'A new assignment "Laravel Basics" has been posted in your course.',
                'user_id' => $userId,
                'is_read' => false,
                'priority' => 'normal',
                'action_url' => '/client/assignments/2',
                'created_at' => Carbon::now()->subHours(2),
                'updated_at' => Carbon::now()->subHours(2),
            ],
            [
                'type' => 'course_update',
                'title' => 'Course Updated',
                'message' => 'New learning materials have been added to "Web Development Fundamentals".',
                'user_id' => $userId,
                'is_read' => true,
                'priority' => 'normal',
                'action_url' => '/client/courses/1',
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'type' => 'announcement',
                'title' => 'Platform Maintenance',
                'message' => 'The platform will undergo scheduled maintenance on Sunday from 2 AM to 4 AM.',
                'user_id' => $userId,
                'is_read' => false,
                'priority' => 'high',
                'action_url' => null,
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'type' => 'enrollment',
                'title' => 'Welcome to Your New Course!',
                'message' => 'You have successfully enrolled in "Advanced JavaScript". Start learning now!',
                'user_id' => $userId,
                'is_read' => true,
                'priority' => 'normal',
                'action_url' => '/client/courses/3',
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
        ]);
    }
}
