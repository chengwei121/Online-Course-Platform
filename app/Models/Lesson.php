<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'video_url',
        'duration',
        'order',
        'content',
        'learning_objectives'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function previous()
    {
        return $this->course->lessons()
            ->where('order', '<', $this->order)
            ->orderBy('order', 'desc')
            ->first();
    }

    public function next()
    {
        return $this->course->lessons()
            ->where('order', '>', $this->order)
            ->orderBy('order', 'asc')
            ->first();
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Get the full video URL for display
     */
    public function getVideoUrlAttribute($value)
    {
        if (!$value) {
            return null;
        }

        // If it's already a full URL (external video), return as is
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        // If it's a relative path (uploaded video), return as is for now
        // The view will handle adding the storage URL
        return $value;
    }

    /**
     * Get the display URL for the video (for student viewing)
     */
    public function getDisplayVideoUrl()
    {
        if (!$this->video_url) {
            return null;
        }

        // If it's already a full URL (external video), return as is
        if (filter_var($this->video_url, FILTER_VALIDATE_URL)) {
            return $this->video_url;
        }

        // If it's a relative path (uploaded video), create full storage URL
        return asset('storage/' . $this->video_url);
    }
}
