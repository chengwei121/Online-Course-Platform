<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Helper function to safely create index
        $createIndexSafely = function($table, $columns, $indexName) {
            try {
                $columnList = is_array($columns) ? implode(',', $columns) : $columns;
                DB::statement("CREATE INDEX {$indexName} ON {$table}({$columnList})");
            } catch (\Exception $e) {
                // Index might already exist, continue
            }
        };

        // Create indexes safely
        $createIndexSafely('teachers', 'status', 'teachers_status_index');
        $createIndexSafely('teachers', 'created_at', 'teachers_created_at_index');
        $createIndexSafely('teachers', ['status', 'created_at'], 'teachers_status_created_at_index');
        
        $createIndexSafely('courses', 'status', 'courses_status_index');
        $createIndexSafely('courses', 'created_at', 'courses_created_at_index');
        $createIndexSafely('courses', ['status', 'created_at'], 'courses_status_created_at_index');
        
        $createIndexSafely('users', 'role', 'users_role_index');
        $createIndexSafely('users', 'created_at', 'users_created_at_index');
        $createIndexSafely('users', ['role', 'created_at'], 'users_role_created_at_index');
        
        $createIndexSafely('enrollments', 'created_at', 'enrollments_created_at_index');
        $createIndexSafely('enrollments', 'payment_status', 'enrollments_payment_status_index');
        $createIndexSafely('enrollments', ['created_at', 'user_id'], 'enrollments_created_at_user_id_index');
        $createIndexSafely('enrollments', ['created_at', 'course_id'], 'enrollments_created_at_course_id_index');
        $createIndexSafely('enrollments', ['payment_status', 'created_at'], 'enrollments_payment_status_created_at_index');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Helper function to safely drop index
        $dropIndexSafely = function($table, $indexName) {
            try {
                DB::statement("DROP INDEX {$indexName} ON {$table}");
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
        };

        // Drop indexes safely
        $dropIndexSafely('teachers', 'teachers_status_index');
        $dropIndexSafely('teachers', 'teachers_created_at_index');
        $dropIndexSafely('teachers', 'teachers_status_created_at_index');
        
        $dropIndexSafely('courses', 'courses_status_index');
        $dropIndexSafely('courses', 'courses_created_at_index');
        $dropIndexSafely('courses', 'courses_status_created_at_index');
        
        $dropIndexSafely('users', 'users_role_index');
        $dropIndexSafely('users', 'users_created_at_index');
        $dropIndexSafely('users', 'users_role_created_at_index');
        
        $dropIndexSafely('enrollments', 'enrollments_created_at_index');
        $dropIndexSafely('enrollments', 'enrollments_payment_status_index');
        $dropIndexSafely('enrollments', 'enrollments_created_at_user_id_index');
        $dropIndexSafely('enrollments', 'enrollments_created_at_course_id_index');
        $dropIndexSafely('enrollments', 'enrollments_payment_status_created_at_index');
    }
};
