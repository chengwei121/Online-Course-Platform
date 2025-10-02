<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes for better performance on teacher dashboard queries
        Schema::table('courses', function (Blueprint $table) {
            $table->index(['teacher_id', 'status']);
            $table->index(['teacher_id', 'created_at']);
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->index(['course_id', 'user_id']);
            $table->index(['course_id', 'created_at']);
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->index(['course_id']);
        });

        Schema::table('assignments', function (Blueprint $table) {
            $table->index(['lesson_id']);
        });

        Schema::table('assignment_submissions', function (Blueprint $table) {
            $table->index(['assignment_id', 'submitted_at']);
            $table->index(['assignment_id', 'score']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex(['teacher_id', 'status']);
            $table->dropIndex(['teacher_id', 'created_at']);
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropIndex(['course_id', 'user_id']);
            $table->dropIndex(['course_id', 'created_at']);
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->dropIndex(['course_id']);
        });

        Schema::table('assignments', function (Blueprint $table) {
            $table->dropIndex(['lesson_id']);
        });

        Schema::table('assignment_submissions', function (Blueprint $table) {
            $table->dropIndex(['assignment_id', 'submitted_at']);
            $table->dropIndex(['assignment_id', 'score']);
        });
    }
};