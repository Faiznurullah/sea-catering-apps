<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'meal_plan_id',
        'allergies',
        'total_price',
        'status',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    // Relationship dengan MealPlan
    public function mealPlan()
    {
        return $this->belongsTo(MealPlan::class);
    }

    // Relationship dengan SubscriptionMeal
    public function subscriptionMeals()
    {
        return $this->hasMany(SubscriptionMeal::class);
    }

    // Relationship dengan SubscriptionDeliveryDay
    public function deliveryDays()
    {
        return $this->hasMany(SubscriptionDeliveryDay::class);
    }

    // Accessor untuk mendapatkan meal types dalam array
    public function getMealTypesAttribute()
    {
        return $this->subscriptionMeals->pluck('meal_type')->toArray();
    }

    // Accessor untuk mendapatkan delivery days dalam array
    public function getDeliveryDaysListAttribute()
    {
        return $this->deliveryDays->pluck('day_of_week')->toArray();
    }

    // Method untuk menghitung total harga
    public static function calculateTotalPrice($planPrice, $mealTypesCount, $deliveryDaysCount)
    {
        return $planPrice * $mealTypesCount * $deliveryDaysCount * 4.3;
    }

    // Scope untuk subscription yang aktif
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope untuk subscription berdasarkan status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
