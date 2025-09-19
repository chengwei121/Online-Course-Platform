<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'title',
        'description',
        'instructions',
        'due_date',
        'points',
        'assignment_type',
        'difficulty_level',
        'estimated_time'
    ];

    protected $casts = [
        'due_date' => 'datetime'
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
} 