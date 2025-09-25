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
        Schema::table('students', function (Blueprint $table) {
            // Add user_id foreign key to link with users table
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            
            // Remove password column since authentication is handled by users table
            $table->dropColumn('password');
            
            // Remove remember_token since it's in users table
            if (Schema::hasColumn('students', 'remember_token')) {
                $table->dropColumn('remember_token');
            }
            
            // Add additional useful columns
            $table->date('date_of_birth')->nullable()->after('bio');
            $table->text('address')->nullable()->after('date_of_birth');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Remove added columns
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->dropColumn('date_of_birth');
            $table->dropColumn('address');
            
            // Add back removed columns
            $table->string('password');
            $table->rememberToken();
        });
    }
};
