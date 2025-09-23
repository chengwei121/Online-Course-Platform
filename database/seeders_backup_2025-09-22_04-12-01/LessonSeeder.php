<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        $courses = Course::all();
        
        foreach ($courses as $course) {
            $lessons = [
                [
                    'title' => 'Introduction to Web Development',
                    'description' => 'Welcome to the course! In this lesson, we\'ll cover the basics of web development and what you\'ll learn throughout the course.',
                    'video_url' => 'https://www.youtube.com/embed/3JluqTojuME',
                    'duration' => 45,
                    'order' => 1,
                    'course_id' => $course->id
                ],
                [
                    'title' => 'Getting Started with HTML & CSS',
                    'description' => 'Learn the fundamentals of HTML and CSS, the building blocks of web development.',
                    'video_url' => 'https://www.youtube.com/embed/B-ytMSuwbf8',
                    'duration' => 60,
                    'order' => 2,
                    'course_id' => $course->id
                ],
                [
                    'title' => 'Advanced Web Development Concepts',
                    'description' => 'Dive into advanced web development concepts and best practices.',
                    'video_url' => 'https://www.youtube.com/embed/j6Ule7GXaRs',
                    'duration' => 75,
                    'order' => 3,
                    'course_id' => $course->id
                ],
                [
                    'title' => 'Practical Projects in ' . $course->title,
                    'description' => 'Apply what you\'ve learned by working on practical projects.',
                    'video_url' => 'https://example.com/videos/projects-' . $course->slug,
                    'duration' => 90,
                    'order' => 4,
                    'course_id' => $course->id
                ],
                [
                    'title' => 'Final Project and Course Wrap-up',
                    'description' => 'Complete your final project and review everything you\'ve learned.',
                    'video_url' => 'https://example.com/videos/final-' . $course->slug,
                    'duration' => 120,
                    'order' => 5,
                    'course_id' => $course->id
                ],
            ];

            foreach ($lessons as $lesson) {
                Lesson::create($lesson);
            }
        }
    }
} 