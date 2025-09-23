<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Clean seeding with exactly one admin, one teacher, and one student.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting database seeding...');
        
        $this->call([
            // Clean user seeding (1 admin, 1 teacher, 1 student)
            CleanUserSeeder::class,
            
            // Categories for courses
            CategorySeeder::class,
            
            // Courses and related data
            CourseSeeder::class,
            LessonSeeder::class,
            
            // Optional: Assignments and enrollments
            // AssignmentSeeder::class,
            // EnrollmentSeeder::class,
            // LessonProgressSeeder::class,
            
            // Fix course images if needed
            FixCourseImagesSeeder::class,
        ]);
        
        $this->command->info('🎉 Database seeding completed successfully!');
        $this->command->info('');
        $this->command->info('📋 Login Credentials:');
        $this->command->info('👨‍💼 Admin: admin@onlinecourse.com / admin123');
        $this->command->info('👩‍🏫 Teacher: teacher@onlinecourse.com / teacher123');
        $this->command->info('👨‍🎓 Student: student@onlinecourse.com / student123');
        $this->command->info('');
    }
}
