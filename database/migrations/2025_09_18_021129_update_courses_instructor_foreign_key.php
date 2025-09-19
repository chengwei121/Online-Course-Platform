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
            
            // Add new foreign key constraint to teachers table
            $table->foreign('instructor_id')->references('id')->on('teachers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Drop the teachers foreign key
            $table->dropForeign(['instructor_id']);
            
            // Restore the instructors foreign key
            $table->foreign('instructor_id')->references('id')->on('instructors')->onDelete('cascade');
        });
    }
};
