<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MealPlan;

class MealPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mealPlans = [
            [
                'name' => 'Diet Plan',
                'description' => 'Plan makanan untuk diet sehat dengan kalori terkontrol',
                'price_per_meal' => 30000.00,
                'is_active' => true
            ],
            [
                'name' => 'Protein Plan', 
                'description' => 'Plan makanan tinggi protein untuk membangun massa otot',
                'price_per_meal' => 40000.00,
                'is_active' => true
            ],
            [
                'name' => 'Royal Plan',
                'description' => 'Plan makanan premium dengan menu eksklusif dan bahan berkualitas tinggi',
                'price_per_meal' => 60000.00,
                'is_active' => true
            ]
        ];

        foreach ($mealPlans as $plan) {
            MealPlan::create($plan);
        }
    }
}
