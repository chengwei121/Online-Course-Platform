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
        // Add indexes only if they don't exist
        $this->addIndexIfNotExists('enrollments', ['user_id', 'course_id', 'payment_status'], 'idx_enrollments_payment_check');
        $this->addIndexIfNotExists('enrollments', ['payment_status', 'enrolled_at'], 'idx_enrollments_payment_date');
        $this->addIndexIfNotExists('enrollments', ['user_id', 'payment_status', 'enrolled_at'], 'idx_user_payments');
        $this->addIndexIfNotExists('courses', ['is_free', 'price'], 'idx_courses_pricing');
        $this->addIndexIfNotExists('courses', ['status', 'created_at'], 'idx_courses_status_date');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->dropIndexIfExists('enrollments', 'idx_enrollments_payment_check');
        $this->dropIndexIfExists('enrollments', 'idx_enrollments_payment_date');
        $this->dropIndexIfExists('enrollments', 'idx_user_payments');
        $this->dropIndexIfExists('courses', 'idx_courses_pricing');
        $this->dropIndexIfExists('courses', 'idx_courses_status_date');
    }

    private function addIndexIfNotExists($table, $columns, $indexName)
    {
        try {
            $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]);
            if (empty($indexes)) {
                Schema::table($table, function (Blueprint $table) use ($columns, $indexName) {
                    $table->index($columns, $indexName);
                });
            }
        } catch (Exception $e) {
            // Index might already exist, continue
        }
    }

    private function dropIndexIfExists($table, $indexName)
    {
        try {
            $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]);
            if (!empty($indexes)) {
                Schema::table($table, function (Blueprint $table) use ($indexName) {
                    $table->dropIndex($indexName);
                });
            }
        } catch (Exception $e) {
            // Index might not exist, continue
        }
    }
};