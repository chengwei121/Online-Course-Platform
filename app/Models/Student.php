<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'avatar',
        'phone',
        'bio',
        'date_of_birth',
        'address',
        'status'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * Get the user that owns the student profile
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get enrollments for this student
     * Note: Enrollments are linked through the user_id in users table
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'user_id', 'user_id');
    }

    /**
     * Get all assignment submissions for this student
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class, 'student_id', 'id');
    }
}
