<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionMeal extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'meal_type'
    ];

    // Relationship dengan Subscription
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    // Konstanta untuk meal types
    const MEAL_TYPES = [
        'breakfast' => 'Breakfast',
        'lunch' => 'Lunch', 
        'dinner' => 'Dinner'
    ];

    // Method untuk mendapatkan label meal type
    public function getMealTypeLabel()
    {
        return self::MEAL_TYPES[$this->meal_type] ?? $this->meal_type;
    }
}
