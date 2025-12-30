<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'sender_id',
        'recipient_id',
        'subject',
        'message',
        'is_read',
        'read_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'read_at' => 'datetime',
        ];
    }

    /**
     * Get the user who sent the message.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the user who received the message.
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * Scope a query to only include unread messages.
     */
    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope a query to only include messages sent by a specific user.
     */
    public function scopeSentBy(Builder $query, User $user): Builder
    {
        return $query->where('sender_id', $user->id);
    }

    /**
     * Scope a query to only include messages received by a specific user.
     */
    public function scopeReceivedBy(Builder $query, User $user): Builder
    {
        return $query->where('recipient_id', $user->id);
    }

    /**
     * Scope a query to only include messages between two users.
     */
    public function scopeConversationWith(Builder $query, User $user1, User $user2): Builder
    {
        return $query->where(function ($q) use ($user1, $user2) {
            $q->where(function ($subQ) use ($user1, $user2) {
                $subQ->where('sender_id', $user1->id)
                    ->where('recipient_id', $user2->id);
            })->orWhere(function ($subQ) use ($user1, $user2) {
                $subQ->where('sender_id', $user2->id)
                    ->where('recipient_id', $user1->id);
            });
        });
    }

    /**
     * Mark the message as read.
     */
    public function markAsRead(): bool
    {
        if ($this->is_read) {
            return true;
        }

        return $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }
}
