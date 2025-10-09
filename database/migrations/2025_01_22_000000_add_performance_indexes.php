<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations - Add performance indexes to improve query speed
     */
    public function up(): void
    {
        // Courses table indexes
        $this->addIndexIfNotExists('courses', 'status', 'courses_status_index');
        $this->addIndexIfNotExists('courses', 'slug', 'courses_slug_index');
        $this->addIndexIfNotExists('courses', 'teacher_id', 'courses_teacher_id_index');
        $this->addIndexIfNotExists('courses', 'category_id', 'courses_category_id_index');
        $this->addCompositeIndexIfNotExists('courses', ['status', 'created_at'], 'courses_status_created_at_index');

        // Enrollments table indexes
        $this->addIndexIfNotExists('enrollments', 'user_id', 'enrollments_user_id_index');
        $this->addIndexIfNotExists('enrollments', 'course_id', 'enrollments_course_id_index');
        $this->addIndexIfNotExists('enrollments', 'payment_status', 'enrollments_payment_status_index');
        $this->addCompositeIndexIfNotExists('enrollments', ['user_id', 'course_id'], 'enrollments_user_course_index');

        // Course Reviews table indexes
        $this->addIndexIfNotExists('course_reviews', 'course_id', 'course_reviews_course_id_index');
        $this->addIndexIfNotExists('course_reviews', 'user_id', 'course_reviews_user_id_index');
        $this->addIndexIfNotExists('course_reviews', 'rating', 'course_reviews_rating_index');
        $this->addCompositeIndexIfNotExists('course_reviews', ['course_id', 'user_id'], 'course_reviews_course_user_index');

        // Lessons table indexes
        $this->addIndexIfNotExists('lessons', 'course_id', 'lessons_course_id_index');
        $this->addCompositeIndexIfNotExists('lessons', ['course_id', 'order'], 'lessons_course_order_index');

        // Lesson Progress table indexes
        $this->addIndexIfNotExists('lesson_progress', 'user_id', 'lesson_progress_user_id_index');
        $this->addIndexIfNotExists('lesson_progress', 'lesson_id', 'lesson_progress_lesson_id_index');
        $this->addIndexIfNotExists('lesson_progress', 'completed', 'lesson_progress_completed_index');
        $this->addCompositeIndexIfNotExists('lesson_progress', ['user_id', 'lesson_id'], 'lesson_progress_user_lesson_index');

        // Teachers table indexes
        $this->addIndexIfNotExists('teachers', 'status', 'teachers_status_index');
        $this->addIndexIfNotExists('teachers', 'user_id', 'teachers_user_id_index');

        // Students table indexes
        $this->addIndexIfNotExists('students', 'user_id', 'students_user_id_index');
    }
    
    /**
     * Add index if it doesn't exist
     */
    private function addIndexIfNotExists(string $table, string $column, string $indexName): void
    {
        if (!$this->indexExists($table, $indexName)) {
            Schema::table($table, function (Blueprint $blueprint) use ($column, $indexName) {
                $blueprint->index($column, $indexName);
            });
        }
    }
    
    /**
     * Add composite index if it doesn't exist
     */
    private function addCompositeIndexIfNotExists(string $table, array $columns, string $indexName): void
    {
        if (!$this->indexExists($table, $indexName)) {
            Schema::table($table, function (Blueprint $blueprint) use ($columns, $indexName) {
                $blueprint->index($columns, $indexName);
            });
        }
    }
    
    /**
     * Check if index exists
     */
    private function indexExists(string $table, string $indexName): bool
    {
        $indexes = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
        return count($indexes) > 0;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop courses indexes
        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex('courses_status_index');
            $table->dropIndex('courses_slug_index');
            $table->dropIndex('courses_teacher_id_index');
            $table->dropIndex('courses_category_id_index');
            $table->dropIndex('courses_status_created_at_index');
        });

        // Drop enrollments indexes
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropIndex('enrollments_user_id_index');
            $table->dropIndex('enrollments_course_id_index');
            $table->dropIndex('enrollments_payment_status_index');
            $table->dropIndex('enrollments_user_course_index');
        });

        // Drop course_reviews indexes
        Schema::table('course_reviews', function (Blueprint $table) {
            $table->dropIndex('course_reviews_course_id_index');
            $table->dropIndex('course_reviews_user_id_index');
            $table->dropIndex('course_reviews_rating_index');
            $table->dropIndex('course_reviews_course_user_index');
        });

        // Drop lessons indexes
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropIndex('lessons_course_id_index');
            $table->dropIndex('lessons_course_order_index');
        });

        // Drop lesson_progress indexes
        Schema::table('lesson_progress', function (Blueprint $table) {
            $table->dropIndex('lesson_progress_user_id_index');
            $table->dropIndex('lesson_progress_lesson_id_index');
            $table->dropIndex('lesson_progress_completed_index');
            $table->dropIndex('lesson_progress_user_lesson_index');
        });

        // Drop teachers indexes
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropIndex('teachers_status_index');
            $table->dropIndex('teachers_user_id_index');
        });

        // Drop students indexes
        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex('students_user_id_index');
        });
    }
};
