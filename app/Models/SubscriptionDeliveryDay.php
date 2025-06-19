<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionDeliveryDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'day_of_week'
    ];

    // Relationship dengan Subscription
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    // Konstanta untuk hari dalam seminggu
    const DAYS_OF_WEEK = [
        'monday' => 'Monday',
        'tuesday' => 'Tuesday',
        'wednesday' => 'Wednesday',
        'thursday' => 'Thursday',
        'friday' => 'Friday',
        'saturday' => 'Saturday',
        'sunday' => 'Sunday'
    ];

    // Method untuk mendapatkan label hari
    public function getDayLabel()
    {
        return self::DAYS_OF_WEEK[$this->day_of_week] ?? $this->day_of_week;
    }
}
