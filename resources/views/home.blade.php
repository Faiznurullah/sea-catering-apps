@extends('layouts.template')
@section('title', 'CateringApp')
@section('css')
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection
@section('content')


 <!-- Dashboard Section -->
  <section class="dashboard-section">
    <div class="container">
      <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="dashboard-sidebar">
          <div class="user-info">
            <div class="user-avatar">JD</div>
            <div class="user-details">
              <h3>{{ Auth::user()->name }}</h3>
              <p>{{ Auth::user()->email }}</p>
            </div>
          </div>  
          
          <ul class="sidebar-menu">
            <li class="active"><a href="#subscriptions">My Subscriptions</a></li>
            <li><a href="#payment-history">Payment History</a></li>
            <li><a href="#account-settings">Account Settings</a></li> 
          </ul>
        </div>
        
        <!-- Main Content -->
        <div class="dashboard-content">
          <h1>My Dashboard</h1>
          
          <!-- Active Subscriptions -->
          <div class="dashboard-card" id="subscriptions">
            <div class="card-header">
              <h2>My Subscriptions</h2>
              <a href="{{ route('subscription') }}" class="btn-primary mt-5">Create New Subscription</a>
            </div>
            <div class="card-content">
              @if($subscriptions->count() > 0)
                @foreach($subscriptions as $subscription)
                <div class="subscription-item">
                  <div class="subscription-details">
                    <h3>{{ $subscription->mealPlan->name }}</h3>
                    <p><strong>Customer:</strong> {{ $subscription->name }}</p>
                    <p><strong>Phone:</strong> {{ $subscription->phone }}</p>
                    <p><strong>Meal Types:</strong> 
                      @foreach($subscription->subscriptionMeals as $meal)
                        {{ ucfirst($meal->meal_type) }}@if(!$loop->last), @endif
                      @endforeach
                    </p>
                    <p><strong>Delivery Days:</strong> 
                      @foreach($subscription->deliveryDays as $day)
                        {{ ucfirst(substr($day->day_of_week, 0, 3)) }}@if(!$loop->last), @endif
                      @endforeach
                    </p>
                    <p><strong>Monthly Total:</strong> Rp{{ number_format($subscription->total_price, 0, ',', '.') }}</p>
                    <p><strong>Status:</strong> 
                      <span class="status {{ $subscription->status }}">{{ ucfirst($subscription->status) }}</span>
                    </p>
                    @if($subscription->allergies)
                    <p><strong>Allergies:</strong> {{ $subscription->allergies }}</p>
                    @endif
                  </div>
                  <div class="subscription-actions">
                    @if($subscription->status === 'pending')
                      <span class="btn-warning">Pending Approval</span>
                    @elseif($subscription->status === 'active')
                      <form action="{{ route('subscription.updateStatus', $subscription->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="inactive">
                        <button type="submit" class="pause-btn" onclick="return confirm('Are you sure you want to pause this subscription?')">Pause Subscription</button>
                      </form>
                    @elseif($subscription->status === 'inactive')
                      <form action="{{ route('subscription.updateStatus', $subscription->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="active">
                        <button type="submit" class="resume-btn" onclick="return confirm('Are you sure you want to resume this subscription?')">Resume Subscription</button>
                      </form>
                    @endif
                    
                    @if($subscription->status !== 'cancelled')
                    <form action="{{ route('subscription.updateStatus', $subscription->id) }}" method="POST" style="display: inline;">
                      @csrf
                      @method('PATCH')
                      <input type="hidden" name="status" value="cancelled">
                      <button type="submit" class="cancel-btn" onclick="return confirm('Are you sure you want to cancel this subscription?')">Cancel Subscription</button>
                    </form>
                    @endif
                  </div>
                </div>
                @endforeach
                
                @if($subscriptions->count() >= 5)
                <div class="subscription-item">
                  <p><a href="{{ route('subscription.manage') }}" class="btn-primary">View All Subscriptions</a></p>
                </div>
                @endif
              @else
                <div class="subscription-item">
                  <div class="subscription-details">
                    <h3>No Subscriptions Found</h3>
                    <p>You don't have any active subscriptions yet.</p>
                  </div>
                  <div class="subscription-actions">
                    <a href="{{ route('subscription') }}" class="btn-primary">Create Your First Subscription</a>
                  </div>
                </div>
              @endif
            </div>
          </div>
          
         
          

        </div>
      </div>
    </div>
  </section>

  <!-- Pause Subscription Modal -->
  <div class="modal" id="pause-subscription-modal">
    <div class="modal-content">
      <span class="close-modal">&times;</span>
      <h2>Pause Subscription</h2>
      <p>Select the date range during which you want to pause your subscription:</p>
      <form id="pause-form">
        <div class="form-group">
          <label for="pause-start">Start Date</label>
          <input type="date" id="pause-start" name="pause-start" required>
        </div>
        <div class="form-group">
          <label for="pause-end">End Date</label>
          <input type="date" id="pause-end" name="pause-end" required>
        </div>
        <p class="modal-note">No charges will be applied during the pause period.</p>
        <div class="modal-actions">
          <button type="button" class="btn-secondary" id="cancel-pause">Cancel</button>
          <button type="submit" class="btn-primary">Confirm Pause</button>
        </div>
      </form>
    </div>
  </div>
 
 
@endsection
@section('javascript')
    <script src="{{ asset('js/scripts.js') }}"></script> 
    <script src="{{ asset('js/dashboard.js') }}"></script> 
@endsection