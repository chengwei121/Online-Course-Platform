<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Enrollment;

class LessonProgressSeeder extends Seeder
{
    public function run(): void
    {
        // 获取所有用户和他们的课程注册
        $users = User::all();
        
        foreach ($users as $user) {
            // 获取用户注册的所有课程
            $enrollments = Enrollment::where('user_id', $user->id)->get();
            
            foreach ($enrollments as $enrollment) {
                // 获取课程的所有课程
                $lessons = $enrollment->course->lessons;
                
                // 为每个课程创建进度记录
                foreach ($lessons as $lesson) {
                    // 随机决定是否完成这个课程
                    $completed = rand(0, 1) == 1;
                    
                    LessonProgress::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'lesson_id' => $lesson->id,
                        ],
                        [
                            'completed' => $completed,
                            'completed_at' => $completed ? now() : null,
                            'video_progress' => $completed ? ($lesson->duration * 60) : rand(0, $lesson->duration * 60)
                        ]
                    );
                }
            }
        }
    }
} 