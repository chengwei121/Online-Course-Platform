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
        Schema::table('lessons', function (Blueprint $table) {
            $table->text('content')->nullable()->after('description');
            $table->text('learning_objectives')->nullable()->after('content');
        });

        Schema::table('assignments', function (Blueprint $table) {
            $table->enum('assignment_type', ['quiz', 'project', 'essay', 'coding', 'presentation'])
                  ->default('project')->after('points');
            $table->enum('difficulty_level', ['beginner', 'intermediate', 'advanced'])
                  ->default('beginner')->after('assignment_type');
            $table->integer('estimated_time')->nullable()->comment('Estimated time in minutes')->after('difficulty_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn(['content', 'learning_objectives']);
        });

        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn(['assignment_type', 'difficulty_level', 'estimated_time']);
        });
    }
};
