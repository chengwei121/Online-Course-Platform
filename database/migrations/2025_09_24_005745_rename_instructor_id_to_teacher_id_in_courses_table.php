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
        Schema::table('courses', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['instructor_id']);
            
            // Rename the column from instructor_id to teacher_id
            $table->renameColumn('instructor_id', 'teacher_id');
            
            // Add the new foreign key constraint
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Drop the new foreign key constraint
            $table->dropForeign(['teacher_id']);
            
            // Rename the column back to instructor_id
            $table->renameColumn('teacher_id', 'instructor_id');
            
            // Add back the old foreign key constraint
            $table->foreign('instructor_id')->references('id')->on('teachers')->onDelete('cascade');
        });
    }
};
