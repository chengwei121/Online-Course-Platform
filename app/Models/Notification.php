<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'message',
        'data',
        'user_id',
        'sender_id',
        'action_url',
        'is_read',
        'priority',
        'read_at'
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'high');
    }

    // Methods
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    public function markAsUnread()
    {
        $this->update([
            'is_read' => false,
            'read_at' => null
        ]);
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getIconAttribute()
    {
        return match($this->type) {
            'course_update' => 'fas fa-book',
            'assignment' => 'fas fa-tasks',
            'announcement' => 'fas fa-bullhorn',
            'enrollment' => 'fas fa-user-plus',
            'grade' => 'fas fa-graduation-cap',
            'reminder' => 'fas fa-bell',
            'system' => 'fas fa-cog',
            default => 'fas fa-info-circle'
        };
    }

    public function getColorAttribute()
    {
        return match($this->priority) {
            'high' => 'text-danger',
            'normal' => 'text-primary',
            'low' => 'text-secondary',
            default => 'text-primary'
        };
    }
}
