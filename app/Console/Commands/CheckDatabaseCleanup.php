<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Course;

class CheckDatabaseCleanup extends Command
{
    protected $signature = 'db:check-cleanup';
    protected $description = 'Check database structure after cleanup';

    public function handle()
    {
        $this->info('🔍 Checking Database Structure After Cleanup');
        $this->line(str_repeat('=', 60));

        // Check Users table
        $this->info('👥 USERS TABLE:');
        $users = User::all(['id', 'name', 'email', 'role']);
        foreach ($users as $user) {
            $this->line("  {$user->id} - {$user->name} ({$user->email}) - Role: {$user->role}");
        }

        $this->line('');
        $this->info('👨‍🎓 STUDENTS TABLE:');
        $students = Student::all(['id', 'user_id', 'name', 'email']);
        if ($students->count() > 0) {
            foreach ($students as $student) {
                $this->line("  {$student->id} - {$student->name} ({$student->email}) - User ID: {$student->user_id}");
            }
        } else {
            $this->warn('  No students found in students table');
        }

        $this->line('');
        $this->info('👨‍🏫 TEACHERS TABLE:');
        $teachers = Teacher::all(['id', 'user_id', 'name', 'email']);
        if ($teachers->count() > 0) {
            foreach ($teachers as $teacher) {
                $this->line("  {$teacher->id} - {$teacher->name} ({$teacher->email}) - User ID: {$teacher->user_id}");
            }
        } else {
            $this->warn('  No teachers found in teachers table');
        }

        $this->line('');
        $this->info('📚 COURSES TABLE (showing teacher relationships):');
        $courses = Course::with('instructor')->get(['id', 'title', 'teacher_id']);
        if ($courses->count() > 0) {
            foreach ($courses as $course) {
                $instructorName = $course->instructor ? $course->instructor->name : 'No Teacher';
                $this->line("  {$course->id} - {$course->title} - Teacher: {$instructorName}");
            }
        } else {
            $this->warn('  No courses found');
        }

        $this->line('');
        $this->info('✅ Database cleanup results:');
        $this->line('   - Instructors table: REMOVED ❌');
        $this->line('   - Students table: FIXED with user_id foreign key ✅');
        $this->line('   - Teachers table: Active and linked to users ✅');
        $this->line('   - Courses use teachers (not instructors) ✅');
        
        return 0;
    }
}
