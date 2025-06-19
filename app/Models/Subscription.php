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
        
        // Hitung jumlah hari yang di-pause
        $pausedDays = $pauseStart->diffInDays($pauseEnd) + 1;
        
        // Hitung total hari pengiriman per bulan berdasarkan delivery days
        $deliveryDaysPerWeek = $this->deliveryDays->count();
        $deliveryDaysPerMonth = $deliveryDaysPerWeek * 4.3; // 4.3 minggu per bulan
        
        // Hitung refund amount berdasarkan hari yang di-pause
        $dailyRate = $this->total_price / 30; // Asumsi 30 hari per bulan
        $refundAmount = $dailyRate * $pausedDays;
        
        $this->update([
            'status' => 'paused',
            'pause_start_date' => $pauseStart,
            'pause_end_date' => $pauseEnd,
            'paused_days_total' => $this->paused_days_total + $pausedDays,
            'refund_amount' => $this->refund_amount + $refundAmount
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

    // Method untuk mengecek apakah sedang dalam masa pause
    public function isCurrentlyPaused()
    {
        if ($this->status !== 'paused' || !$this->pause_start_date || !$this->pause_end_date) {
            return false;
        }
        
        $today = \Carbon\Carbon::now();
        return $today->between($this->pause_start_date, $this->pause_end_date);
    }

    // Method untuk menghitung remaining pause days
    public function getRemainingPauseDays()
    {
        if (!$this->isCurrentlyPaused()) {
            return 0;
        }
        
        $today = \Carbon\Carbon::now();
        return $today->diffInDays($this->pause_end_date);
    }

    // Method untuk menghitung adjusted monthly payment
    public function getAdjustedMonthlyPayment()
    {
        $basePayment = $this->total_price;
        $pauseRefund = $this->refund_amount;
        
        return $basePayment - $pauseRefund;
    }
}
