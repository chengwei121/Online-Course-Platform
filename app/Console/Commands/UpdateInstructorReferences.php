<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class UpdateInstructorReferences extends Command
{
    protected $signature = 'db:update-instructor-references';
    protected $description = 'Update all instructor_id references to teacher_id in controllers';

    public function handle()
    {
        $this->info('🔧 Updating instructor_id references to teacher_id...');
        
        $controllers = [
            'app/Http/Controllers/Admin/DashboardController.php',
            'app/Http/Controllers/Admin/CourseController.php', 
            'app/Http/Controllers/Teacher/AssignmentController.php',
            'app/Http/Controllers/Teacher/CourseController.php',
            'app/Http/Controllers/Teacher/DashboardController.php',
            'app/Http/Controllers/Teacher/LessonController.php',
        ];
        
        foreach ($controllers as $controllerPath) {
            if (File::exists($controllerPath)) {
                $content = File::get($controllerPath);
                $originalContent = $content;
                
                // Replace instructor_id with teacher_id
                $content = str_replace('instructor_id', 'teacher_id', $content);
                
                if ($content !== $originalContent) {
                    File::put($controllerPath, $content);
                    $this->info("✅ Updated: {$controllerPath}");
                } else {
                    $this->line("⚪ No changes needed: {$controllerPath}");
                }
            } else {
                $this->warn("⚠️ File not found: {$controllerPath}");
            }
        }
        
        $this->info('🎉 All instructor_id references updated to teacher_id!');
        
        return 0;
    }
}
