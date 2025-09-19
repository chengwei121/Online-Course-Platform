<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Instructor;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MigrateInstructorsToTeachers extends Command
{
    protected $signature = 'migrate:instructors-to-teachers';
    protected $description = 'Migrate instructors data to teachers table and update course relationships';

    public function handle()
    {
        $this->info('Starting migration of instructors to teachers...');

        DB::beginTransaction();

        try {
            // Step 1: Check if foreign key exists and drop it
            $this->info('Checking foreign key constraints...');
            
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'courses' 
                AND COLUMN_NAME = 'instructor_id' 
                AND CONSTRAINT_NAME != 'PRIMARY'
            ");
            
            foreach ($foreignKeys as $fk) {
                $this->line("Dropping foreign key: {$fk->CONSTRAINT_NAME}");
                DB::statement("ALTER TABLE courses DROP FOREIGN KEY {$fk->CONSTRAINT_NAME}");
            }

            $instructors = Instructor::all();
            $this->info("Found {$instructors->count()} instructors to migrate");

            $instructorToTeacherMap = [];

            foreach ($instructors as $instructor) {
                $this->line("Processing instructor: {$instructor->name}");

                // Check if user already exists with this email
                $user = User::where('email', $instructor->email)->first();
                
                if (!$user) {
                    // Create new user
                    $user = User::create([
                        'name' => $instructor->name,
                        'email' => $instructor->email,
                        'password' => $instructor->password ?? Hash::make('password123'), // Default password
                        'role' => 'instructor'
                    ]);
                    $this->line("  Created new user: {$user->name}");
                } else {
                    // Update existing user role if needed
                    if ($user->role !== 'instructor') {
                        $user->update(['role' => 'instructor']);
                        $this->line("  Updated existing user role: {$user->name}");
                    } else {
                        $this->line("  User already exists: {$user->name}");
                    }
                }

                // Check if teacher profile already exists
                $teacher = Teacher::where('user_id', $user->id)->first();
                
                if (!$teacher) {
                    // Create teacher profile
                    $teacher = Teacher::create([
                        'user_id' => $user->id,
                        'name' => $instructor->name,
                        'email' => $instructor->email,
                        'phone' => $instructor->phone ?? null,
                        'qualification' => null,
                        'bio' => $instructor->bio ?? null,
                        'profile_picture' => $instructor->profile_picture ?? null,
                        'department' => null,
                        'date_of_birth' => null,
                        'address' => null,
                        'hourly_rate' => null,
                        'status' => $instructor->status ?? 'active'
                    ]);
                    $this->line("  Created teacher profile for: {$teacher->name}");
                } else {
                    $this->line("  Teacher profile already exists: {$teacher->name}");
                }

                // Store mapping for later course updates
                $instructorToTeacherMap[$instructor->id] = $teacher->id;
            }

            // Step 2: Update all courses to use teacher IDs
            $this->info('Updating course instructor references...');
            foreach ($instructorToTeacherMap as $instructorId => $teacherId) {
                $coursesUpdated = Course::where('instructor_id', $instructorId)
                    ->update(['instructor_id' => $teacherId]);
                
                if ($coursesUpdated > 0) {
                    $this->line("  Updated {$coursesUpdated} courses from instructor {$instructorId} to teacher {$teacherId}");
                }
            }

            // Step 3: Add foreign key constraint to teachers table
            $this->info('Adding new foreign key constraint to teachers table...');
            DB::statement('ALTER TABLE courses ADD CONSTRAINT courses_instructor_id_foreign FOREIGN KEY (instructor_id) REFERENCES teachers(id) ON DELETE CASCADE');

            $this->info('Migration completed successfully!');
            $this->newLine();
            
            // Show summary
            $this->table(['Type', 'Count'], [
                ['Users (instructors)', User::where('role', 'instructor')->count()],
                ['Teachers', Teacher::count()],
                ['Courses', Course::count()],
            ]);

            $this->newLine();
            if ($this->confirm('Do you want to delete the instructors table data? This action cannot be undone.', false)) {
                Instructor::truncate();
                $this->info('✅ Instructors table data has been deleted.');
            } else {
                $this->warn('⚠️  Instructors table data was kept. You can delete it manually later.');
            }

            DB::commit();
            $this->success('🎉 Migration completed successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            $this->error('❌ Migration failed: ' . $e->getMessage());
            $this->line('Stack trace: ' . $e->getTraceAsString());
            return 1;
        }

        return 0;
    }
}