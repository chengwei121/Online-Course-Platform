<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to add performance indexes
     */
    public function up(): void
    {
        // Add indexes to courses table for teacher queries
        try {
            Schema::table('courses', function (Blueprint $table) {
                $table->index('teacher_id', 'courses_teacher_id_index');
            });
        } catch (\Exception $e) {
            // Index already exists, continue
        }
        
        try {
            Schema::table('courses', function (Blueprint $table) {
                $table->index('status', 'courses_status_index');
            });
        } catch (\Exception $e) {
            // Index already exists, continue
        }
        
        try {
            Schema::table('courses', function (Blueprint $table) {
                $table->index('created_at', 'courses_created_at_index');
            });
        } catch (\Exception $e) {
            // Index already exists, continue
        }

        // Add indexes to enrollments table
        $enrollmentIndexes = [
            ['course_id', 'enrollments_course_id_index'],
            ['user_id', 'enrollments_user_id_index'],
            ['status', 'enrollments_status_index'],
            ['created_at', 'enrollments_created_at_index'],
        ];
        
        foreach ($enrollmentIndexes as [$column, $indexName]) {
            try {
                Schema::table('enrollments', function (Blueprint $table) use ($column, $indexName) {
                    $table->index($column, $indexName);
                });
            } catch (\Exception $e) {}
        }
        
        // Composite index
        try {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->index(['course_id', 'user_id'], 'enrollments_course_user_index');
            });
        } catch (\Exception $e) {}

        // Add indexes to lessons table
        try {
            Schema::table('lessons', function (Blueprint $table) {
                $table->index('course_id', 'lessons_course_id_index');
            });
        } catch (\Exception $e) {}

        // Add indexes to assignments table
        try {
            Schema::table('assignments', function (Blueprint $table) {
                $table->index('lesson_id', 'assignments_lesson_id_index');
            });
        } catch (\Exception $e) {}

        // Add indexes to assignment_submissions table
        $submissionIndexes = [
            ['assignment_id', 'assignment_submissions_assignment_id_index'],
            ['user_id', 'assignment_submissions_user_id_index'],
            ['submitted_at', 'assignment_submissions_submitted_at_index'],
            ['score', 'assignment_submissions_score_index'],
        ];
        
        foreach ($submissionIndexes as [$column, $indexName]) {
            try {
                Schema::table('assignment_submissions', function (Blueprint $table) use ($column, $indexName) {
                    $table->index($column, $indexName);
                });
            } catch (\Exception $e) {}
        }

        // Add indexes to users table
        $userIndexes = [
            ['role', 'users_role_index'],
            ['email', 'users_email_index'],
            ['created_at', 'users_created_at_index'],
        ];
        
        foreach ($userIndexes as [$column, $indexName]) {
            try {
                Schema::table('users', function (Blueprint $table) use ($column, $indexName) {
                    $table->index($column, $indexName);
                });
            } catch (\Exception $e) {}
        }

        // Add indexes to lesson_progress table if it exists
        if (Schema::hasTable('lesson_progress')) {
            $progressIndexes = [
                ['user_id', 'lesson_progress_user_id_index'],
                ['lesson_id', 'lesson_progress_lesson_id_index'],
                ['completed', 'lesson_progress_completed_index'],
            ];
            
            foreach ($progressIndexes as [$column, $indexName]) {
                try {
                    Schema::table('lesson_progress', function (Blueprint $table) use ($column, $indexName) {
                        $table->index($column, $indexName);
                    });
                } catch (\Exception $e) {}
            }
        }
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        // Drop indexes - wrapped in try-catch in case they don't exist
        $indexes = [
            ['courses', ['courses_teacher_id_index', 'courses_status_index', 'courses_created_at_index']],
            ['enrollments', ['enrollments_course_id_index', 'enrollments_user_id_index', 'enrollments_status_index', 'enrollments_created_at_index', 'enrollments_course_user_index']],
            ['lessons', ['lessons_course_id_index']],
            ['assignments', ['assignments_lesson_id_index']],
            ['assignment_submissions', ['assignment_submissions_assignment_id_index', 'assignment_submissions_user_id_index', 'assignment_submissions_submitted_at_index', 'assignment_submissions_score_index']],
            ['users', ['users_role_index', 'users_email_index', 'users_created_at_index']],
        ];
        
        foreach ($indexes as [$table, $tableIndexes]) {
            foreach ($tableIndexes as $indexName) {
                try {
                    Schema::table($table, function (Blueprint $table) use ($indexName) {
                        $table->dropIndex($indexName);
                    });
                } catch (\Exception $e) {
                    // Index doesn't exist, continue
                }
            }
        }
        
        // Drop lesson_progress indexes if table exists
        if (Schema::hasTable('lesson_progress')) {
            $progressIndexes = ['lesson_progress_user_id_index', 'lesson_progress_lesson_id_index', 'lesson_progress_completed_index'];
            foreach ($progressIndexes as $indexName) {
                try {
                    Schema::table('lesson_progress', function (Blueprint $table) use ($indexName) {
                        $table->dropIndex($indexName);
                    });
                } catch (\Exception $e) {}
            }
        }
    }
};
