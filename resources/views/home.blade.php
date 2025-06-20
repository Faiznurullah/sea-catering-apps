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
            <li><a href="#pause-history">Pause History</a></li>
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
                      <button type="button" class="pause-btn" 
                              onclick="pauseSubscription({{ $subscription->id }})">
                        Pause Subscription
                      </button>
                    @elseif($subscription->status === 'paused')
                      <button type="button" class="resume-btn" 
                              onclick="resumeSubscription({{ $subscription->id }})">
                        Resume Subscription
                      </button>
                      @if($subscription->isCurrentlyPaused())
                        <small class="pause-info">Paused until {{ $subscription->pause_end_date->format('d M Y') }} 
                        ({{ $subscription->getRemainingPauseDays() }} days remaining)</small>
                      @endif
                    @elseif($subscription->status === 'inactive')
                      <button type="button" class="resume-btn" 
                              onclick="reactivateSubscription({{ $subscription->id }})">
                        Reactivate Subscription
                      </button>
                    @endif
                    
                    @if($subscription->status !== 'cancelled')
                      <button type="button" class="cancel-btn" 
                              onclick="cancelSubscription({{ $subscription->id }})">
                        Cancel Subscription
                      </button>
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
          
          <!-- Pause History -->
          <div class="dashboard-card" id="pause-history">
            <div class="card-header">
              <h2>Pause History</h2>
            </div>
            <div class="card-content">
              @if($subscriptions->where('paused_days_total', '>', 0)->count() > 0)
                @foreach($subscriptions->where('paused_days_total', '>', 0) as $subscription)
                <div class="pause-history-item">
                  <div class="pause-details">
                    <h4>{{ $subscription->mealPlan->name }} - {{ $subscription->name }}</h4>
                    <div class="pause-stats">
                      <div class="stat-item">
                        <span class="stat-label">Total Paused Days:</span>
                        <span class="stat-value">{{ $subscription->paused_days_total }} days</span>
                      </div>
                      <div class="stat-item">
                        <span class="stat-label">Total Credits Earned:</span>
                        <span class="stat-value">Rp{{ number_format($subscription->refund_amount, 0, ',', '.') }}</span>
                      </div>
                      @if($subscription->status === 'paused' && $subscription->pause_start_date && $subscription->pause_end_date)
                      <div class="stat-item">
                        <span class="stat-label">Current Pause:</span>
                        <span class="stat-value">{{ $subscription->pause_start_date->format('d M') }} - {{ $subscription->pause_end_date->format('d M Y') }}</span>
                      </div>
                      @endif
                    </div>
                    <div class="pause-impact">
                      <p><strong>Monthly Payment Impact:</strong></p>
                      <p>Original: Rp{{ number_format($subscription->total_price, 0, ',', '.') }}</p>
                      <p>Adjusted: Rp{{ number_format($subscription->getAdjustedMonthlyPayment(), 0, ',', '.') }}</p>
                      <p class="savings">Savings: Rp{{ number_format($subscription->refund_amount, 0, ',', '.') }}</p>
                    </div>
                  </div>
                </div>
                @endforeach
              @else
                <div class="pause-history-item">
                  <div class="pause-details">
                    <h4>No Pause History</h4>
                    <p>You haven't paused any subscriptions yet. When you do, the history will appear here.</p>
                  </div>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
 
@endsection
@section('javascript')
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script>
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Show notification function
        function showNotification(message, type = 'success') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.innerHTML = `
                <span>${message}</span>
                <button class="close-notification">&times;</button>
            `;
            
            // Add to page
            document.body.appendChild(notification);
            
            // Show notification
            setTimeout(() => {
                notification.classList.add('show');
            }, 100);
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    if (notification.parentNode) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }, 5000);
            
            // Close button handler
            notification.querySelector('.close-notification').addEventListener('click', () => {
                notification.classList.remove('show');
                setTimeout(() => {
                    if (notification.parentNode) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            });
        }

        // Make AJAX request helper
        function makeRequest(url, method, data = null) {
            const options = {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            };

            if (data) {
                options.body = JSON.stringify(data);
            }

            return fetch(url, options);
        }

        // Pause subscription function (simple like admin)
        function pauseSubscription(subscriptionId) {
            if (!confirm('Are you sure you want to pause this subscription for 1 month? You will receive a refund for the paused period.')) {
                return;
            }
            
            makeRequest(`/subscription/${subscriptionId}/pause`, 'POST')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    // Reload page to show updated status
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    showNotification(data.message || 'Error pausing subscription', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error pausing subscription', 'error');
            });
        }

        // Resume subscription function
        function resumeSubscription(subscriptionId) {
            if (!confirm('Are you sure you want to resume this subscription?')) {
                return;
            }
            
            makeRequest(`/subscription/${subscriptionId}/resume`, 'POST')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    // Reload page to show updated status
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    showNotification(data.message || 'Error resuming subscription', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error resuming subscription', 'error');
            });
        }

        // Reactivate subscription function
        function reactivateSubscription(subscriptionId) {
            if (!confirm('Are you sure you want to reactivate this subscription?')) {
                return;
            }
            
            makeRequest(`/subscription/${subscriptionId}/status`, 'PATCH', {
                status: 'active'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Subscription reactivated successfully!', 'success');
                    // Reload page to show updated status
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    showNotification(data.message || 'Error reactivating subscription', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error reactivating subscription', 'error');
            });
        }

        // Cancel subscription function
        function cancelSubscription(subscriptionId) {
            if (!confirm('Are you sure you want to cancel this subscription? This action cannot be undone.')) {
                return;
            }
            
            makeRequest(`/subscription/${subscriptionId}/status`, 'PATCH', {
                status: 'cancelled'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Subscription cancelled successfully!', 'success');
                    // Reload page to show updated status
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    showNotification(data.message || 'Error cancelling subscription', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error cancelling subscription', 'error');
            });
        }

        // Sidebar navigation
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarLinks = document.querySelectorAll('.sidebar-menu a');
            
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Remove active class from all links
                    sidebarLinks.forEach(l => l.parentElement.classList.remove('active'));
                    
                    // Add active class to clicked link
                    this.parentElement.classList.add('active');
                    
                    // Get target section
                    const targetId = this.getAttribute('href').substring(1);
                    const targetSection = document.getElementById(targetId);
                    
                    if (targetSection) {
                        // Smooth scroll to section
                        targetSection.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });
    </script>
@endsection
