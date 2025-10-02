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
        // Add performance indexes for authentication
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->index('role', 'users_role_index');
                $table->index('created_at', 'users_created_at_index');
                $table->index('updated_at', 'users_updated_at_index');
                $table->index('email_verified_at', 'users_email_verified_at_index');
            });
        } catch (\Exception $e) {
            // Indexes might already exist, continue
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex('users_role_index');
                $table->dropIndex('users_created_at_index');
                $table->dropIndex('users_updated_at_index');
                $table->dropIndex('users_email_verified_at_index');
            });
        } catch (\Exception $e) {
            // Indexes might not exist, continue
        }
    }
};