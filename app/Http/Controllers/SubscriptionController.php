<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\MealPlan;
use App\Models\SubscriptionMeal;
use App\Models\SubscriptionDeliveryDay;
use App\Models\SubscriptionPausedDay;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

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
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator);
        }

        try {
            $subscription = Subscription::findOrFail($id);
            $subscription->update(['status' => $request->status]);

            $message = 'Status subscription berhasil diupdate!';
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }

            return redirect()->back()->with('success', $message);
            
        } catch (\Exception $e) {
            $message = 'Error updating subscription status: ' . $e->getMessage();
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 500);
            }
            return redirect()->back()->with('error', $message);
        }
    }
    }

    /**
     * Pause subscription
     */
    public function pauseSubscription(Request $request, $id)
    {
        // For simple AJAX requests (both admin and user), use default pause
        if ($request->expectsJson()) {
            try {
                $subscription = Subscription::findOrFail($id);
                
                if ($subscription->status !== 'active') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Only active subscriptions can be paused.'
                    ], 422);
                }

                // Use default dates (immediate pause for 1 month)
                $pauseStartDate = now()->format('Y-m-d');
                $pauseEndDate = now()->addMonth()->format('Y-m-d');
                
                $refundAmount = $subscription->pauseSubscription($pauseStartDate, $pauseEndDate);

                return response()->json([
                    'success' => true,
                    'message' => "Subscription paused successfully! Refund amount: Rp " . number_format($refundAmount, 0, ',', '.'),
                    'subscription' => $subscription->fresh()->load(['mealPlan', 'subscriptionMeals', 'deliveryDays'])
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error pausing subscription: ' . $e->getMessage()
                ], 500);
            }
        }

        // Regular form submission requires validation
        $validator = Validator::make($request->all(), [
            'pause_start_date' => 'required|date|after_or_equal:today',
            'pause_end_date' => 'required|date|after:pause_start_date',
            'reason' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            // Check if request expects JSON (AJAX)
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

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

    /**
     * Calculate pause refund preview (AJAX endpoint)
     */
    public function calculatePausePreview(Request $request, $id)
    {
        try {
            $subscription = Subscription::with(['mealPlan', 'subscriptionMeals', 'deliveryDays'])
                ->findOrFail($id);
            
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            
            if (!$startDate || !$endDate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Start date and end date are required'
                ], 422);
            }
            
            $pauseDays = $subscription->getPauseDuration($startDate, $endDate);
            $refundAmount = $subscription->calculatePauseRefund($startDate, $endDate);
            $dailyRate = $subscription->getDailyRate();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'pause_days' => $pauseDays,
                    'refund_amount' => $refundAmount,
                    'daily_rate' => $dailyRate,
                    'delivery_days' => $subscription->deliveryDays->count(),
                    'meal_types' => $subscription->subscriptionMeals->count(),
                    'formatted_refund' => number_format($refundAmount, 0, ',', '.'),
                    'formatted_daily_rate' => number_format($dailyRate, 0, ',', '.')
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error calculating pause preview: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Pause subscription per day
     */
    public function pausePerDay(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'pause_month' => 'required|string',
            'selected_days' => 'required|array|min:1',
            'selected_days.*' => 'required|date',
            'reason' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            
            $subscription = Subscription::findOrFail($id);
            
            if ($subscription->status !== 'active') {
                return redirect()->back()->with('error', 'Only active subscriptions can be paused.');
            }

            $selectedDays = $request->selected_days;
            
            // Validate that selected days are in the future
            $today = Carbon::now()->startOfDay();
            $validDays = array_filter($selectedDays, function($date) use ($today) {
                return Carbon::parse($date)->startOfDay() >= $today;
            });

            if (empty($validDays)) {
                return redirect()->back()->with('error', 'Please select valid future dates.');
            }

            // Validate that selected days are actual delivery days
            $deliveryDaysOfWeek = $subscription->deliveryDays->pluck('day_of_week')->toArray();
            $invalidDays = [];
            
            foreach ($validDays as $date) {
                $dayOfWeek = Carbon::parse($date)->format('l'); // Full day name
                $dayOfWeekLower = strtolower($dayOfWeek);
                
                if (!in_array($dayOfWeekLower, $deliveryDaysOfWeek)) {
                    $invalidDays[] = $date;
                }
            }

            if (!empty($invalidDays)) {
                return redirect()->back()->with('error', 'Some selected days are not delivery days for your subscription.');
            }

            // Check if any of the days are already paused
            $alreadyPausedDays = SubscriptionPausedDay::where('subscription_id', $subscription->id)
                ->whereIn('paused_date', $validDays)
                ->pluck('paused_date')
                ->toArray();

            if (!empty($alreadyPausedDays)) {
                $formattedDates = array_map(function($date) {
                    return Carbon::parse($date)->format('M d, Y');
                }, $alreadyPausedDays);
                return redirect()->back()->with('error', 'Some days are already paused: ' . implode(', ', $formattedDates));
            }

            // Calculate refund for each day and store records
            $dailyRate = $subscription->getDailyRate();
            $totalRefund = 0;

            foreach ($validDays as $date) {
                $pausedDay = SubscriptionPausedDay::create([
                    'subscription_id' => $subscription->id,
                    'paused_date' => $date,
                    'daily_rate' => $dailyRate,
                    'refund_amount' => $dailyRate,
                    'reason' => $request->reason,
                    'type' => 'per_day'
                ]);
                
                $totalRefund += $dailyRate;
            }

            // Update subscription totals
            $subscription->update([
                'paused_days_total' => ($subscription->paused_days_total ?? 0) + count($validDays),
                'refund_amount' => ($subscription->refund_amount ?? 0) + $totalRefund
            ]);

            DB::commit();

            $message = "Subscription paused for " . count($validDays) . " selected days! Total refund: Rp " . number_format($totalRefund, 0, ',', '.');
            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error pausing subscription: ' . $e->getMessage());
        }
    }

    /**
     * Get paused days for a subscription
     */
    public function getPausedDays($id)
    {
        try {
            $subscription = Subscription::findOrFail($id);
            
            $pausedDays = SubscriptionPausedDay::where('subscription_id', $subscription->id)
                ->pluck('paused_date')
                ->map(function($date) {
                    return Carbon::parse($date)->format('Y-m-d');
                })
                ->toArray();

            return response()->json([
                'success' => true,
                'paused_days' => $pausedDays
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching paused days: ' . $e->getMessage()
            ], 500);
        }
    }
}
