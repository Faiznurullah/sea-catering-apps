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

    /**
     * Pause subscription
     */
   public function pauseSubscription(Request $request, $id)
{
    try {
        $subscription = Subscription::findOrFail($id);
       
        if ($subscription->status !== 'active') {
            $message = 'Only active subscriptions can be paused.';
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 422);
            }
            return redirect()->back()->with('error', $message);
        }

        // Check if this is a simple form submission (like your HTML form)
        $isSimpleFormSubmission = !$request->expectsJson() && 
                                 !$request->has('pause_start_date') && 
                                 !$request->has('pause_end_date');

        if ($isSimpleFormSubmission) {
            // For simple form submissions, use default dates (immediate pause for 1 month)
            $pauseStartDate = now()->format('Y-m-d');
            $pauseEndDate = now()->addMonth()->format('Y-m-d');
            
            $refundAmount = $subscription->pauseSubscription($pauseStartDate, $pauseEndDate);
            
            $message = "Subscription paused successfully! Refund amount: Rp " . number_format($refundAmount, 0, ',', '.');
            return redirect()->back()->with('success', $message);
        }
        
        // For AJAX requests without validation data
        if ($request->expectsJson() && !$request->has('pause_start_date')) {
            $pauseStartDate = now()->format('Y-m-d');
            $pauseEndDate = now()->addMonth()->format('Y-m-d');
           
            $refundAmount = $subscription->pauseSubscription($pauseStartDate, $pauseEndDate);
            
            return response()->json([
                'success' => true,
                'message' => "Subscription paused successfully! Refund amount: Rp " . number_format($refundAmount, 0, ',', '.'),
                'subscription' => $subscription->fresh()->load(['mealPlan', 'subscriptionMeals', 'deliveryDays'])
            ]);
        }

        // For requests with custom pause dates - validate input
        $validator = Validator::make($request->all(), [
            'pause_start_date' => 'required|date|after_or_equal:today',
            'pause_end_date' => 'required|date|after:pause_start_date',
            'reason' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Process pause with custom dates
        $refundAmount = $subscription->pauseSubscription(
            $request->pause_start_date,
            $request->pause_end_date
        );

        $message = "Subscription paused successfully! Refund amount: Rp " . number_format($refundAmount, 0, ',', '.');
       
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'refund_amount' => $refundAmount
            ]);
        }

        return redirect()->back()->with('success', $message);

    } catch (\Exception $e) {
        $message = 'Error pausing subscription: ' . $e->getMessage();
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], 500);
        }
        
        return redirect()->back()->with('error', $message);
    }
}

    /**
     * Resume subscription
     */
    public function resumeSubscription(Request $request, $id)
    {
        try {
            $subscription = Subscription::findOrFail($id);
            
            if ($subscription->status !== 'paused') {
                $message = 'Only paused subscriptions can be resumed.';
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 422);
                }
                return redirect()->back()->with('error', $message);
            }

            $subscription->resumeSubscription();

            $message = 'Subscription resumed successfully!';
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'subscription' => $subscription->fresh()->load(['mealPlan', 'subscriptionMeals', 'deliveryDays'])
                ]);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            $message = 'Error resuming subscription: ' . $e->getMessage();
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 500);
            }
            return redirect()->back()->with('error', $message);
        }
    }

    /**
     * Approve subscription (Admin only)
     */
    public function approve(Request $request, $id)
    {
        try {
            $subscription = Subscription::findOrFail($id);
            
            if ($subscription->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending subscriptions can be approved.'
                ], 422);
            }

            $subscription->update([
                'status' => 'active',
                'admin_notes' => $request->input('admin_notes'),
                'approved_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Subscription approved successfully!',
                'subscription' => $subscription->load(['mealPlan', 'subscriptionMeals', 'deliveryDays'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error approving subscription: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject subscription (Admin only)
     */
    public function reject(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $subscription = Subscription::findOrFail($id);
            
            if ($subscription->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending subscriptions can be rejected.'
                ], 422);
            }

            $subscription->update([
                'status' => 'rejected',
                'rejection_reason' => $request->input('rejection_reason'),
                'rejected_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Subscription rejected successfully!',
                'subscription' => $subscription->load(['mealPlan', 'subscriptionMeals', 'deliveryDays'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error rejecting subscription: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get subscription details for admin view
     */
    public function getDetails($id)
    {
        try {
            $subscription = Subscription::with(['mealPlan', 'subscriptionMeals', 'deliveryDays'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'subscription' => $subscription
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Subscription not found.'
            ], 404);
        }
    }
}
