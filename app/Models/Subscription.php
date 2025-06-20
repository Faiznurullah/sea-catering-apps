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
        'end_date',
        'pause_start_date',
        'pause_end_date',
        'paused_days_total',
        'refund_amount',
        'admin_notes',
        'rejection_reason',
        'approved_at',
        'rejected_at'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'pause_start_date' => 'date',
        'pause_end_date' => 'date',
        'paused_days_total' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime'
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

    // Relationship dengan SubscriptionPausedDay
    public function pausedDays()
    {
        return $this->hasMany(SubscriptionPausedDay::class);
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

    // Method untuk pause subscription
    public function pauseSubscription($startDate, $endDate)
    {
        $pauseStart = \Carbon\Carbon::parse($startDate);
        $pauseEnd = \Carbon\Carbon::parse($endDate);
        
        // Calculate pause duration and refund using new accurate methods
        $pausedDays = $this->getPauseDuration($startDate, $endDate);
        $refundAmount = $this->calculatePauseRefund($startDate, $endDate);
        
        $this->update([
            'status' => 'paused',
            'pause_start_date' => $pauseStart,
            'pause_end_date' => $pauseEnd,
            'paused_days_total' => ($this->paused_days_total ?? 0) + $pausedDays,
            'refund_amount' => ($this->refund_amount ?? 0) + $refundAmount
        ]);
        
        return $refundAmount;
    }

    // Method untuk resume subscription
    public function resumeSubscription()
    {
        $this->update([
            'status' => 'active',
            'pause_start_date' => null,
            'pause_end_date' => null
        ]);
    }

    /**
     * Calculate daily rate based on subscription details
     */
    public function getDailyRate()
    {
        // Get number of delivery days per week
        $deliveryDaysCount = $this->deliveryDays->count();
        
        // Get number of meals per day
        $mealTypesCount = $this->subscriptionMeals->count();
        
        // Calculate daily meals delivered
        $dailyMealsDelivered = $deliveryDaysCount * $mealTypesCount;
        
        // Calculate weekly rate
        $weeklyRate = $dailyMealsDelivered * $this->mealPlan->price_per_meal;
        
        // Convert to daily rate (7 days per week)
        return $weeklyRate / 7;
    }

    /**
     * Calculate refund for specific pause duration
     */
    public function calculatePauseRefund($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        if ($end <= $start) {
            return 0;
        }
        
        $pauseDays = $start->diffInDays($end) + 1;
        $dailyRate = $this->getDailyRate();
        
        return $pauseDays * $dailyRate;
    }

    /**
     * Get pause duration in days
     */
    public function getPauseDuration($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        return $start->diffInDays($end) + 1;
    }

    /**
     * Check if subscription is currently in pause period
     */
    public function isCurrentlyPaused()
    {
        if ($this->status !== 'paused' || !$this->pause_start_date || !$this->pause_end_date) {
            return false;
        }
        
        $now = Carbon::now()->startOfDay();
        $start = Carbon::parse($this->pause_start_date)->startOfDay();
        $end = Carbon::parse($this->pause_end_date)->endOfDay();
        
        return $now->between($start, $end);
    }

    /**
     * Get remaining pause days
     */
    public function getRemainingPauseDays()
    {
        if (!$this->isCurrentlyPaused()) {
            return 0;
        }
        
        $now = Carbon::now()->startOfDay();
        $end = Carbon::parse($this->pause_end_date)->endOfDay();
        
        return $now->diffInDays($end) + 1;
    }

    /**
     * Calculate adjusted monthly payment after pause
     */
    public function getAdjustedMonthlyPayment()
    {
        if ($this->refund_amount <= 0) {
            return $this->total_price;
        }
        
        return $this->total_price - $this->refund_amount;
    }
}
