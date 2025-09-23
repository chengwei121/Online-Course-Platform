<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'student')->get();
        $courses = Course::all();

        foreach ($users as $user) {
            // Each user enrolls in 2-5 random courses
            $randomCourses = $courses->random(rand(2, 5));

            foreach ($randomCourses as $course) {
                // Use firstOrCreate to prevent duplicate enrollments
                Enrollment::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'course_id' => $course->id,
                    ],
                    [
                        'enrolled_at' => now()->subDays(rand(1, 30)),
                        'completed_at' => rand(0, 1) ? now()->subDays(rand(1, 7)) : null,
                        'status' => rand(0, 1) ? 'completed' : 'in_progress',
                        'amount_paid' => $course->price,
                        'payment_status' => 'completed'
                    ]
                );
            }
        }
    }
} 