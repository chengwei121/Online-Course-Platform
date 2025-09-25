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
        // Drop the instructors table as we're using teachers table instead
        Schema::dropIfExists('instructors');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate instructors table if needed to rollback
        Schema::create('instructors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->text('bio')->nullable();
            $table->string('profile_picture')->nullable();
            $table->timestamps();
        });
    }
};
