<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\MealPlan;
use App\Models\SubscriptionMeal;
use App\Models\SubscriptionDeliveryDay;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    /**
     * Display the subscription form
     */
    public function index()
    {
        $mealPlans = MealPlan::active()->get();
        return view('subscription.index', compact('mealPlans'));
    }

    /**
     * Store a new subscription
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'meal_plan_id' => 'required|exists:meal_plans,id',
            'meal_types' => 'required|array|min:1',
            'meal_types.*' => 'in:breakfast,lunch,dinner',
            'delivery_days' => 'required|array|min:1',
            'delivery_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'allergies' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Ambil meal plan untuk perhitungan harga
            $mealPlan = MealPlan::findOrFail($request->meal_plan_id);
            
            // Hitung total harga
            $mealTypesCount = count($request->meal_types);
            $deliveryDaysCount = count($request->delivery_days);
            $totalPrice = Subscription::calculateTotalPrice(
                $mealPlan->price_per_meal,
                $mealTypesCount,
                $deliveryDaysCount
            );

            // Buat subscription
            $subscription = Subscription::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'meal_plan_id' => $request->meal_plan_id,
                'allergies' => $request->allergies,
                'total_price' => $totalPrice,
                'status' => 'pending'
            ]);

            // Simpan meal types
            foreach ($request->meal_types as $mealType) {
                SubscriptionMeal::create([
                    'subscription_id' => $subscription->id,
                    'meal_type' => $mealType
                ]);
            }

            // Simpan delivery days
            foreach ($request->delivery_days as $deliveryDay) {
                SubscriptionDeliveryDay::create([
                    'subscription_id' => $subscription->id,
                    'day_of_week' => $deliveryDay
                ]);
            }

            DB::commit();

            return redirect()->route('subscription.success')
                ->with('success', 'Subscription berhasil dibuat!')
                ->with('subscription_id', $subscription->id);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan subscription: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display subscription success page
     */
    public function success()
    {
        return view('subscription.success');
    }

    /**
     * Display subscription management for home
     */
    public function manage()
    {
        $subscriptions = Subscription::with(['mealPlan', 'subscriptionMeals', 'deliveryDays'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('home.subscriptions', compact('subscriptions'));
    }

    /**
     * Show subscription details
     */
    public function show($id)
    {
        $subscription = Subscription::with(['mealPlan', 'subscriptionMeals', 'deliveryDays'])
            ->findOrFail($id);

        return view('subscription.show', compact('subscription'));
    }

    /**
     * Update subscription status
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:active,inactive,pending,cancelled'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $subscription = Subscription::findOrFail($id);
        $subscription->update(['status' => $request->status]);

        return redirect()->back()
            ->with('success', 'Status subscription berhasil diupdate!');
    }
}
