<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price_per_meal',
        'is_active'
    ];

    protected $casts = [
        'price_per_meal' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // Relationship dengan Subscription
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    // Scope untuk meal plan yang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
