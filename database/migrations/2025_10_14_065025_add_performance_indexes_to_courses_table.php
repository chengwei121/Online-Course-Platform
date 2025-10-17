<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw SQL to add indexes if they don't exist
        DB::statement('CREATE INDEX IF NOT EXISTS courses_average_rating_index ON courses(average_rating)');
        DB::statement('CREATE INDEX IF NOT EXISTS courses_created_at_index ON courses(created_at)');
        DB::statement('CREATE INDEX IF NOT EXISTS courses_status_average_rating_index ON courses(status, average_rating)');
        DB::statement('CREATE INDEX IF NOT EXISTS courses_status_created_at_index ON courses(status, created_at)');

        // Add indexes to course_reviews table for testimonials
        if (Schema::hasTable('course_reviews')) {
            DB::statement('CREATE INDEX IF NOT EXISTS course_reviews_rating_index ON course_reviews(rating)');
            DB::statement('CREATE INDEX IF NOT EXISTS course_reviews_created_at_index ON course_reviews(created_at)');
        }

        // Add indexes to teachers table
        if (Schema::hasTable('teachers')) {
            DB::statement('CREATE INDEX IF NOT EXISTS teachers_status_index ON teachers(status)');
        }

        // Add indexes to enrollments table if needed
        if (Schema::hasTable('enrollments')) {
            DB::statement('CREATE INDEX IF NOT EXISTS enrollments_course_id_index ON enrollments(course_id)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['average_rating']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['status', 'average_rating']);
            $table->dropIndex(['status', 'created_at']);
        });

        Schema::table('course_reviews', function (Blueprint $table) {
            $table->dropIndex(['rating']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        if (Schema::hasTable('enrollments')) {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->dropIndex(['course_id']);
            });
        }
    }
};
