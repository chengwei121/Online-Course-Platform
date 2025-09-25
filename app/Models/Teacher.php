<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'qualification',
        'bio',
        'profile_picture',
        'department',
        'date_of_birth',
        'address',
        'hourly_rate',
        'status'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'hourly_rate' => 'decimal:2'
    ];

    /**
     * Get the user that owns the teacher profile
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the courses for the teacher
     */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    /**
     * Get the assignments for the teacher
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Get full name attribute
     */
    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    /**
     * Check if teacher is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
