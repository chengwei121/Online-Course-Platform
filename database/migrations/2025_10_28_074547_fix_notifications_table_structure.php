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
        // Drop the existing Laravel notifications table
        Schema::dropIfExists('notifications');
        
        // Create custom notifications table
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'course_update', 'assignment', 'announcement', 'enrollment', 'grade', etc.
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Additional data like course_id, assignment_id, etc.
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Who receives this notification
            $table->foreignId('sender_id')->nullable()->constrained('users')->onDelete('set null'); // Who sent this (teacher/admin)
            $table->string('action_url')->nullable(); // URL to redirect when clicked
            $table->boolean('is_read')->default(false);
            $table->enum('priority', ['low', 'normal', 'high'])->default('normal');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['user_id', 'is_read']);
            $table->index(['type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
        
        // Recreate Laravel's default notifications table
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }
};
