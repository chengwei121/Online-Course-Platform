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
        Schema::table('assignment_submissions', function (Blueprint $table) {
            // Add student_id if not exists
            if (!Schema::hasColumn('assignment_submissions', 'student_id')) {
                $table->unsignedBigInteger('student_id')->nullable()->after('user_id');
                $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            }
            
            // Add status field if not exists
            if (!Schema::hasColumn('assignment_submissions', 'status')) {
                $table->string('status')->default('draft')->after('submission_file');
            }
            
            // Add grading fields if not exists
            if (!Schema::hasColumn('assignment_submissions', 'graded_at')) {
                $table->timestamp('graded_at')->nullable()->after('feedback');
            }
            
            if (!Schema::hasColumn('assignment_submissions', 'graded_by')) {
                $table->unsignedBigInteger('graded_by')->nullable()->after('graded_at');
                $table->foreign('graded_by')->references('id')->on('users')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('assignment_submissions', 'private_notes')) {
                $table->text('private_notes')->nullable()->after('graded_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignment_submissions', function (Blueprint $table) {
            // Drop foreign keys first
            if (Schema::hasColumn('assignment_submissions', 'student_id')) {
                $table->dropForeign(['student_id']);
                $table->dropColumn('student_id');
            }
            
            if (Schema::hasColumn('assignment_submissions', 'graded_by')) {
                $table->dropForeign(['graded_by']);
                $table->dropColumn('graded_by');
            }
            
            // Drop columns
            $columnsToCheck = ['status', 'graded_at', 'private_notes'];
            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('assignment_submissions', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
