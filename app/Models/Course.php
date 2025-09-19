<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price',
        'thumbnail',
        'slug',
        'instructor_id',
        'category_id',
        'status',
        'duration',
        'average_rating',
        'total_ratings',
        'learning_hours',
        'level',
        'skills_to_learn',
        'is_free'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'average_rating' => 'decimal:2',
        'skills_to_learn' => 'array',
        'is_free' => 'boolean'
    ];

    public function getThumbnailAttribute($value)
    {
        if (!$value) {
            return null;
        }

        // If it's already a full URL, return it
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        // Clean up the path by removing any URL prefixes and storage prefixes
        $value = preg_replace('#^https?://[^/]+/storage/#', '', $value);
        $value = str_replace('storage/', '', $value);
        
        // If the path doesn't start with images/courses, add it
        if (!str_starts_with($value, 'images/courses/')) {
            $value = 'images/courses/' . ltrim($value, '/');
        }

        // Return the URL using the asset helper
        return asset('storage/' . $value);
    }

    public function getThumbnailUrlAttribute()
    {
        if (!$this->thumbnail) {
            return asset('images/course-placeholder.jpg');
        }

        if (filter_var($this->thumbnail, FILTER_VALIDATE_URL)) {
            return $this->thumbnail;
        }

        return Storage::url($this->thumbnail);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'instructor_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    /**
     * Get the enrollments for the course.
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(CourseReview::class);
    }

    public function getEnrollmentCountAttribute()
    {
        return $this->enrollments()->count();
    }

    public function getLevelLabelAttribute()
    {
        return ucfirst($this->level);
    }
}
