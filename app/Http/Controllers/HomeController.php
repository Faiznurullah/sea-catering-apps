<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;

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
    }

    public function profile()
    {
        return view('pages.profile');
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
