<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CleanUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates exactly one admin, one teacher, and one student.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $this->command->info('🧹 Starting clean user seeding...');
            
            // Create Admin User
            $this->createAdmin();
            
            // Create Teacher User
            $this->createTeacher();
            
            // Create Student User
            $this->createStudent();
            
            $this->command->info('✅ Clean user seeding completed!');
        });
    }

    private function createAdmin()
    {
        $adminEmail = 'admin@onlinecourse.com';
        
        $admin = User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'System Administrator',
                'email' => $adminEmail,
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now()
            ]
        );
        
        $this->command->info('✅ Admin user: ' . $admin->name);
        $this->command->info('   📧 Email: ' . $adminEmail);
        $this->command->info('   🔐 Password: admin123');
    }

    private function createTeacher()
    {
        $teacherEmail = 'teacher@onlinecourse.com';
        
        // Create or update user
        $user = User::updateOrCreate(
            ['email' => $teacherEmail],
            [
                'name' => 'Dr. Sarah Chen',
                'email' => $teacherEmail,
                'password' => Hash::make('teacher123'),
                'role' => 'instructor',
                'email_verified_at' => now()
            ]
        );
        
        // Create or update teacher profile in teachers table
        Teacher::updateOrCreate(
            ['user_id' => $user->id],
            [
                'name' => 'Dr. Sarah Chen',
                'email' => $teacherEmail,
                'phone' => '+1-555-0101',
                'qualification' => 'Ph.D. in Computer Science',
                'bio' => 'Experienced educator with expertise in web development, data science, and software engineering. Passionate about making complex concepts accessible to students.',
                'department' => 'Computer Science',
                'date_of_birth' => '1985-05-15',
                'address' => '123 Education Street, Learning City, CA 90210',
                'hourly_rate' => 150.00,
                'status' => 'active'
            ]
        );
        
        $this->command->info('✅ Teacher user: ' . $user->name);
        $this->command->info('   📧 Email: ' . $teacherEmail);
        $this->command->info('   🔐 Password: teacher123');
    }

    private function createStudent()
    {
        $studentEmail = 'student@onlinecourse.com';
        
        // Create or update user
        $user = User::updateOrCreate(
            ['email' => $studentEmail],
            [
                'name' => 'John Doe',
                'email' => $studentEmail,
                'password' => Hash::make('student123'),
                'role' => 'student',
                'email_verified_at' => now()
            ]
        );
        
        // Create or update student profile in students table
        Student::updateOrCreate(
            ['user_id' => $user->id],
            [
                'name' => 'John Doe',
                'email' => $studentEmail,
                'phone' => '+1-555-0201',
                'bio' => 'Enthusiastic learner passionate about technology and personal development.',
                'date_of_birth' => '1995-03-15',
                'address' => '456 Student Lane, College Town, CA 90211',
                'status' => 'active'
            ]
        );
        
        $this->command->info('✅ Student user: ' . $user->name);
        $this->command->info('   📧 Email: ' . $studentEmail);
        $this->command->info('   🔐 Password: student123');
    }
}