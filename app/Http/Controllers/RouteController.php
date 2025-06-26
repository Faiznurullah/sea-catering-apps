<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MealPlan;
use App\Models\Subscription;
use App\Models\SubscriptionMeal;
use App\Models\SubscriptionDeliveryDay;
use App\Models\ExperienceUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RouteController extends Controller
{
     
    public function beranda()
    {
        // Ambil testimonials dari database ExperienceUser
        $testimonials = ExperienceUser::with('user')
            ->orderBy('star', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(6) // Ambil 6 testimonials terbaru dengan rating tertinggi
            ->get();
            
        return view('pages.beranda', compact('testimonials'));
    }

    public function menu()
    {
        return view('pages.menu');
    }

    public function subscription()
    {
        $mealPlans = MealPlan::active()->get();
        return view('pages.subscription', compact('mealPlans'));
    }

    public function storeSubscription(Request $request)
    {
        // Mapping plan value to meal_plan_id
        $planMapping = [
            'diet' => 1,
            'protein' => 2,
            'royal' => 3
        ];

        // Validasi input
        $validator = Validator::make($request->all(), [
            'full-name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'plan' => 'required|in:diet,protein,royal',
            'meal-type' => 'required|array|min:1',
            'meal-type.*' => 'in:breakfast,lunch,dinner',
            'delivery-day' => 'required|array|min:1',
            'delivery-day.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'allergies' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Ambil meal plan berdasarkan plan yang dipilih
            $mealPlanId = $planMapping[$request->input('plan')];
            $mealPlan = MealPlan::findOrFail($mealPlanId);
            
            // Hitung total harga
            $mealTypesCount = count($request->input('meal-type'));
            $deliveryDaysCount = count($request->input('delivery-day'));
            $totalPrice = Subscription::calculateTotalPrice(
                $mealPlan->price_per_meal,
                $mealTypesCount,
                $deliveryDaysCount
            );

            // Buat subscription
            $subscription = Subscription::create([
                'name' => $request->input('full-name'),
                'phone' => $request->input('phone'),
                'meal_plan_id' => $mealPlanId,
                'allergies' => $request->input('allergies'),
                'total_price' => $totalPrice,
                'status' => 'pending'
            ]);

            // Simpan meal types
            foreach ($request->input('meal-type') as $mealType) {
                SubscriptionMeal::create([
                    'subscription_id' => $subscription->id,
                    'meal_type' => $mealType
                ]);
            }

            // Simpan delivery days
            foreach ($request->input('delivery-day') as $deliveryDay) {
                SubscriptionDeliveryDay::create([
                    'subscription_id' => $subscription->id,
                    'day_of_week' => $deliveryDay
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'subscription_id' => $subscription->id,
                'message' => 'Subscription berhasil dibuat!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan subscription: ' . $e->getMessage()
            ], 500);
        }
    }

    public function contact()
    {
        return view('pages.contact');
    }

  
}
