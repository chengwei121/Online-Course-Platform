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
            return asset('images/course-placeholder.svg');
        }

        $thumbnail = $this->getOriginal('thumbnail');

        // If it's already a full URL, extract just the filename and rebuild the URL properly
        if (filter_var($thumbnail, FILTER_VALIDATE_URL)) {
            // Extract the filename from the URL
            $filename = basename(parse_url($thumbnail, PHP_URL_PATH));
            
            // Check if the file exists in the courses directory
            if (Storage::disk('public')->exists('images/courses/' . $filename)) {
                return Storage::url('images/courses/' . $filename);
            }
            
            // If not found, return the placeholder
            return asset('images/course-placeholder.svg');
        }

        // Handle relative paths
        $thumbnailPath = $thumbnail;
        
        // Clean up the path - remove any existing storage/ prefix
        $thumbnailPath = str_replace('storage/', '', $thumbnailPath);
        
        // If the path doesn't start with images/courses, add it
        if (!str_starts_with($thumbnailPath, 'images/courses/')) {
            $thumbnailPath = 'images/courses/' . ltrim($thumbnailPath, '/');
        }

        // Check if file exists before returning URL
        if (Storage::disk('public')->exists($thumbnailPath)) {
            return Storage::url($thumbnailPath);
        }

        // Return placeholder if file doesn't exist
        return asset('images/course-placeholder.svg');
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
