<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPausedDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'paused_date',
        'daily_rate',
        'refund_amount',
        'reason',
        'type'
    ];

    protected $casts = [
        'paused_date' => 'date',
        'daily_rate' => 'decimal:2',
        'refund_amount' => 'decimal:2'
    ];

    /**
     * Relationship with Subscription
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
