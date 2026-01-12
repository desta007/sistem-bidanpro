<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'link',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    /**
     * Get the user that owns the notification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope for read notifications.
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Mark the notification as read.
     */
    public function markAsRead(): void
    {
        if (is_null($this->read_at)) {
            $this->update(['read_at' => now()]);
        }
    }

    /**
     * Mark the notification as unread.
     */
    public function markAsUnread(): void
    {
        $this->update(['read_at' => null]);
    }

    /**
     * Check if notification is read.
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Get icon based on notification type.
     */
    public function getIconAttribute(): string
    {
        return match ($this->type) {
            'new_patient' => 'ri-user-add-line',
            'new_queue' => 'ri-calendar-schedule-line',
            'low_stock' => 'ri-error-warning-line',
            'payment_received' => 'ri-money-dollar-circle-line',
            'medical_record' => 'ri-file-list-3-line',
            default => 'ri-notification-3-line',
        };
    }

    /**
     * Get color based on notification type.
     */
    public function getColorAttribute(): string
    {
        return match ($this->type) {
            'new_patient' => 'primary',
            'new_queue' => 'info',
            'low_stock' => 'warning',
            'payment_received' => 'success',
            'medical_record' => 'accent',
            default => 'secondary',
        };
    }
}
