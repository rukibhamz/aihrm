<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'type',
        'pinned',
        'department_id',
        'author_id',
        'published_at',
    ];

    protected $casts = [
        'pinned' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Get the author of the announcement.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the department if announcement is department-specific.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the users who have read this announcement.
     */
    public function readBy()
    {
        return $this->belongsToMany(User::class, 'announcement_user')
            ->withPivot('read_at')
            ->withTimestamps();
    }

    /**
     * Scope for published announcements.
     */
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope for pinned announcements first.
     */
    public function scopePinnedFirst($query)
    {
        return $query->orderByDesc('pinned')->orderByDesc('published_at');
    }

    /**
     * Check if the announcement has been read by a user.
     */
    public function isReadBy(User $user): bool
    {
        return $this->readBy()->where('user_id', $user->id)->exists();
    }

    /**
     * Scope for announcements visible to a user.
     */
    public function scopeVisibleTo($query, User $user)
    {
        return $query->where(function ($q) use ($user) {
            $q->where('type', 'all')
                ->orWhere(function ($dq) use ($user) {
                    $dq->where('type', 'department')
                        ->where('department_id', $user->employee?->department_id);
                });
        });
    }
}
