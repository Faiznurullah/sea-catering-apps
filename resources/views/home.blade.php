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
                    @if($subscription->paused_days_total > 0)
                    <p><strong>Total Paused Days:</strong> {{ $subscription->paused_days_total }} days</p>
                    <p><strong>Refund/Credit:</strong> Rp{{ number_format($subscription->refund_amount, 0, ',', '.') }}</p>
                    <p><strong>Adjusted Payment:</strong> Rp{{ number_format($subscription->getAdjustedMonthlyPayment(), 0, ',', '.') }}</p>
                    @endif
                    <p><strong>Status:</strong> 
                      <span class="status {{ $subscription->status }}">{{ ucfirst($subscription->status) }}</span>
                      @if($subscription->status === 'paused' && $subscription->pause_start_date && $subscription->pause_end_date)
                        <small>({{ $subscription->pause_start_date->format('d M') }} - {{ $subscription->pause_end_date->format('d M Y') }})</small>
                      @endif
                    </p>
                    @if($subscription->allergies)
                    <p><strong>Allergies:</strong> {{ $subscription->allergies }}</p>
                    @endif
                  </div>
                  <div class="subscription-actions">
                    @if($subscription->status === 'pending')
                      <span class="btn-warning">Pending Approval</span>
                    @elseif($subscription->status === 'active')
                       <form action="{{ route('subscription.pause', $subscription->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="pause-btn" 
                                onclick="return confirm('Are you sure you want to pause this subscription?')">
                          Pause Subscription
                        </button>
                      </form>
                    @elseif($subscription->status === 'paused')
                      <form action="{{ route('subscription.resume', $subscription->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="resume-btn" 
                                onclick="return confirm('Are you sure you want to resume this subscription?')">
                          Resume Subscription
                        </button>
                      </form>
                      @if($subscription->isCurrentlyPaused())
                        <small class="pause-info">Paused until {{ $subscription->pause_end_date->format('d M Y') }} 
                        ({{ $subscription->getRemainingPauseDays() }} days remaining)</small>
                      @endif
                    @elseif($subscription->status === 'inactive')
                      <form action="{{ route('subscription.updateStatus', $subscription->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="active">
                        <button type="submit" class="resume-btn" 
                                onclick="return confirm('Are you sure you want to reactivate this subscription?')">
                          Reactivate Subscription
                        </button>
                      </form>
                    @endif
                    
                    @if($subscription->status !== 'cancelled')
                    <form action="{{ route('subscription.updateStatus', $subscription->id) }}" method="POST" style="display: inline;">
                      @csrf
                      @method('PATCH')
                      <input type="hidden" name="status" value="cancelled">
                      <button type="submit" class="cancel-btn" 
                              onclick="return confirm('Are you sure you want to cancel this subscription? This action cannot be undone.')">
                        Cancel Subscription
                      </button>
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
  <div class="modal" id="pause-subscription-modal" style="display: none;">
    <div class="modal-content">
      <span class="close-modal">&times;</span>
      <h2>Pause Subscription</h2>
      <p>Select the date range during which you want to pause your subscription. You will receive a refund/credit for the paused period.</p>
      
      <form id="pause-form" method="POST">
        @csrf
        <div class="form-group">
          <label for="pause-start">Start Date <span class="required">*</span></label>
          <input type="date" id="pause-start" name="pause_start_date" required min="{{ date('Y-m-d') }}">
        </div>
        <div class="form-group">
          <label for="pause-end">End Date <span class="required">*</span></label>
          <input type="date" id="pause-end" name="pause_end_date" required>
        </div>
        <div class="form-group">
          <label for="pause-reason">Reason (Optional)</label>
          <textarea id="pause-reason" name="reason" rows="3" placeholder="Tell us why you're pausing your subscription..."></textarea>
        </div>
        
        <div id="pause-calculation" style="display: none;">
          <div class="calculation-box">
            <h4>Pause Calculation</h4>
            <p><strong>Pause Duration:</strong> <span id="pause-days">0</span> days</p>
            <p><strong>Estimated Refund:</strong> Rp <span id="pause-refund">0</span></p>
            <p class="note">The refund will be applied as credit to your account.</p>
          </div>
        </div>
        
        <div class="modal-actions">
          <button type="button" class="btn-secondary" id="cancel-pause">Cancel</button>
          <button type="submit" class="btn-primary" id="confirm-pause">Confirm Pause</button>
        </div>
      </form>
    </div>
  </div>
 
 
@endsection
@section('javascript')
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script>
        let currentSubscriptionId = null;
        let currentSubscriptionPrice = 0;

        function openPauseModal(subscriptionId) {
            currentSubscriptionId = subscriptionId;
            
            // Get subscription price from the DOM
            const subscriptionItem = document.querySelector(`[data-subscription-id="${subscriptionId}"]`);
            if (subscriptionItem) {
                const priceText = subscriptionItem.querySelector('.subscription-details p:nth-child(4)').textContent;
                currentSubscriptionPrice = parseInt(priceText.replace(/[^\d]/g, ''));
            }
            
            // Set form action
            document.getElementById('pause-form').action = `/subscription/${subscriptionId}/pause`;
            
            // Set minimum date for pause start
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);
            document.getElementById('pause-start').min = tomorrow.toISOString().split('T')[0];
            
            // Show modal
            document.getElementById('pause-subscription-modal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closePauseModal() {
            document.getElementById('pause-subscription-modal').style.display = 'none';
            document.body.style.overflow = 'auto';
            
            // Reset form
            document.getElementById('pause-form').reset();
            document.getElementById('pause-calculation').style.display = 'none';
        }

        function calculatePauseRefund() {
            const startDate = document.getElementById('pause-start').value;
            const endDate = document.getElementById('pause-end').value;
            
            if (startDate && endDate && currentSubscriptionPrice > 0) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                
                if (end > start) {
                    const diffTime = Math.abs(end - start);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                    
                    // Calculate refund (daily rate * paused days)
                    const dailyRate = currentSubscriptionPrice / 30; // Assuming 30 days per month
                    const refund = dailyRate * diffDays;
                    
                    // Update display
                    document.getElementById('pause-days').textContent = diffDays;
                    document.getElementById('pause-refund').textContent = Math.round(refund).toLocaleString('id-ID');
                    document.getElementById('pause-calculation').style.display = 'block';
                } else {
                    document.getElementById('pause-calculation').style.display = 'none';
                }
            } else {
                document.getElementById('pause-calculation').style.display = 'none';
            }
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Close modal events
            document.getElementById('cancel-pause').addEventListener('click', closePauseModal);
            document.querySelector('.close-modal').addEventListener('click', closePauseModal);
            
            // Click outside modal to close
            document.getElementById('pause-subscription-modal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closePauseModal();
                }
            });
            
            // Date change events for calculation
            document.getElementById('pause-start').addEventListener('change', function() {
                // Set minimum end date
                const startDate = this.value;
                if (startDate) {
                    const start = new Date(startDate);
                    const nextDay = new Date(start);
                    nextDay.setDate(nextDay.getDate() + 1);
                    document.getElementById('pause-end').min = nextDay.toISOString().split('T')[0];
                }
                calculatePauseRefund();
            });
            
            document.getElementById('pause-end').addEventListener('change', calculatePauseRefund);
            
            // Form submission
            document.getElementById('pause-form').addEventListener('submit', function(e) {
                const startDate = document.getElementById('pause-start').value;
                const endDate = document.getElementById('pause-end').value;
                
                if (!startDate || !endDate) {
                    e.preventDefault();
                    alert('Please select both start and end dates.');
                    return;
                }
                
                const start = new Date(startDate);
                const end = new Date(endDate);
                
                if (end <= start) {
                    e.preventDefault();
                    alert('End date must be after start date.');
                    return;
                }
                
                // Show loading state
                document.getElementById('confirm-pause').disabled = true;
                document.getElementById('confirm-pause').textContent = 'Processing...';
            });
        });

        // Add data attribute to subscription items for easier identification
        document.addEventListener('DOMContentLoaded', function() {
            const subscriptionItems = document.querySelectorAll('.subscription-item');
            subscriptionItems.forEach((item, index) => {
                // This would need to be populated with actual subscription IDs from PHP
                // For now, we'll use a simple counter
                if (item.querySelector('.pause-btn')) {
                    const pauseBtn = item.querySelector('.pause-btn');
                    const onclick = pauseBtn.getAttribute('onclick');
                    if (onclick) {
                        const subscriptionId = onclick.match(/\d+/)[0];
                        item.setAttribute('data-subscription-id', subscriptionId);
                    }
                }
            });
        });
    </script>
@endsection