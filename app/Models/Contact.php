<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'read_at'
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * Mark contact as read
     */
    public function markAsRead()
    {
        $this->read_at = now();
        $this->save();
    }

    /**
     * Check if contact is read
     */
    public function isRead()
    {
        return !is_null($this->read_at);
    }

    /**
     * Scope for unread contacts
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope for read contacts
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }
}
