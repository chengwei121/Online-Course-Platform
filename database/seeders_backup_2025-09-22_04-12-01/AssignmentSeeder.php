<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Lesson;
use Illuminate\Database\Seeder;

class AssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $lessons = Lesson::all();
        
        foreach ($lessons as $lesson) {
            $assignments = [
                [
                    'title' => 'Practice Exercise',
                    'description' => 'Complete the practice exercises to reinforce your understanding of the concepts covered in this lesson.',
                    'instructions' => '1. Review the lesson materials\n2. Complete the practice exercises\n3. Submit your work for review',
                    'due_date' => now()->addDays(7),
                    'points' => 50,
                ],
                [
                    'title' => 'Project Assignment',
                    'description' => 'Apply what you\'ve learned by working on a practical project.',
                    'instructions' => '1. Choose a project from the provided options\n2. Implement the required features\n3. Document your work\n4. Submit your project',
                    'due_date' => now()->addDays(14),
                    'points' => 100,
                ],
            ];

            foreach ($assignments as $assignment) {
                $assignment['lesson_id'] = $lesson->id;
                Assignment::create($assignment);
            }
        }
    }
} 