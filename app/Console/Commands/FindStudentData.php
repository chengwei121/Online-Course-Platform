<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class FindStudentData extends Command
{
    protected $signature = 'db:find-student';
    protected $description = 'Find where student@onlinecourse.com data is stored';

    public function handle()
    {
        $studentEmail = 'student@onlinecourse.com';
        
        $this->info('🔍 Searching for student@onlinecourse.com in database...');
        $this->line(str_repeat('=', 60));
        
        // Check users table
        $this->info('👤 USERS TABLE:');
        $user = User::where('email', $studentEmail)->first();
        if ($user) {
            $this->line("  ✅ Found in users table:");
            $this->line("     ID: {$user->id}");
            $this->line("     Name: {$user->name}");
            $this->line("     Email: {$user->email}");
            $this->line("     Role: {$user->role}");
            $this->line("     Created: {$user->created_at}");
        } else {
            $this->error("  ❌ NOT found in users table");
        }
        
        $this->line('');
        
        // Check students table
        $this->info('🎓 STUDENTS TABLE:');
        $students = Student::where('email', $studentEmail)->get();
        if ($students->count() > 0) {
            foreach ($students as $student) {
                $this->line("  ✅ Found in students table:");
                $this->line("     ID: {$student->id}");
                $this->line("     Name: {$student->name}");
                $this->line("     Email: {$student->email}");
                $this->line("     User ID: {$student->user_id}");
                $this->line("     Phone: " . ($student->phone ?? 'N/A'));
                $this->line("     Status: {$student->status}");
            }
        } else {
            $this->warn("  ⚠️ NOT found in students table");
        }
        
        // Check for students linked by user_id
        if ($user) {
            $this->line('');
            $this->info('🔗 STUDENTS LINKED BY USER_ID:');
            $linkedStudents = Student::where('user_id', $user->id)->get();
            if ($linkedStudents->count() > 0) {
                foreach ($linkedStudents as $student) {
                    $this->line("  ✅ Found linked student:");
                    $this->line("     Student ID: {$student->id}");
                    $this->line("     Student Name: {$student->name}");
                    $this->line("     Student Email: {$student->email}");
                    $this->line("     User ID: {$student->user_id}");
                }
            } else {
                $this->error("  ❌ No students linked by user_id");
            }
        }
        
        // Show all students for comparison
        $this->line('');
        $this->info('📋 ALL STUDENTS IN DATABASE:');
        $allStudents = Student::all();
        if ($allStudents->count() > 0) {
            foreach ($allStudents as $student) {
                $this->line("  - ID: {$student->id} | Name: {$student->name} | Email: {$student->email} | User ID: " . ($student->user_id ?? 'NULL'));
            }
        } else {
            $this->warn("  ⚠️ No students found in students table");
        }
        
        return 0;
    }
}
