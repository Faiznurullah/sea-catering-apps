<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Ambil subscription data untuk ditampilkan di home
        $subscriptions = Subscription::with(['mealPlan', 'subscriptionMeals', 'deliveryDays', 'pausedDays'])
            ->orderBy('created_at', 'desc')
            ->take(5) // Ambil 5 subscription terbaru
            ->get();

        return view('home', compact('subscriptions'));
    }    public function profile()
    {
        $user = Auth::user();
        return view('pages.profile', compact('user'));
    }

    /**
     * Update user profile
     */    public function updateProfile(Request $request)
    {
        $userId = Auth::id();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $userId,
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:255',
            'national' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'city' => $request->city,
                'national' => $request->national
            ];

            // Handle file upload
            if ($request->hasFile('foto')) {
                $user = User::find($userId);
                // Delete old photo if exists
                if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                    Storage::disk('public')->delete($user->foto);
                }
                
                $fotoPath = $request->file('foto')->store('profile-photos', 'public');
                $updateData['foto'] = $fotoPath;
            }

            // Update user data using User model
            User::where('id', $userId)->update($updateData);
            $updatedUser = User::find($userId);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!',
                'user' => $updatedUser
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating profile: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Change user password
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 422);
        }        try {
            User::where('id', $user->id)->update([
                'password' => Hash::make($request->new_password)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error changing password: ' . $e->getMessage()
            ], 500);
        }
    }

    public function admin()
    {
        // Untuk admin, tampilkan semua subscription
        $subscriptions = Subscription::with(['mealPlan', 'subscriptionMeals', 'deliveryDays', 'pausedDays'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.admin', compact('subscriptions'));
    }
}
