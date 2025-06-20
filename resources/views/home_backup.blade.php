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
                    
                    <!-- Show individual paused days if any -->
                    @if($subscription->pausedDays->where('type', 'per_day')->count() > 0)
                    <div class="individual-paused-days">
                      <h5>Individually Paused Days:</h5>
                      <div class="paused-days-grid">
                        @foreach($subscription->pausedDays->where('type', 'per_day')->sortBy('paused_date') as $pausedDay)
                          <div class="paused-day-item">
                            <span class="paused-date">{{ $pausedDay->paused_date->format('M d, Y') }}</span>
                            <span class="paused-day">{{ $pausedDay->paused_date->format('l') }}</span>
                            <span class="paused-refund">Rp{{ number_format($pausedDay->refund_amount, 0, ',', '.') }}</span>
                            @if($pausedDay->reason)
                              <small class="paused-reason">{{ $pausedDay->reason }}</small>
                            @endif
                          </div>
                        @endforeach
                      </div>
                    </div>
                    @endif
                    
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

  <!-- Pause Per Day Modal -->
  <div class="modal" id="pause-per-day-modal" style="display: none;">
    <div class="modal-content">
      <span class="close-modal" onclick="closePausePerDayModal()">&times;</span>
      <h2>Pause Subscription Per Day</h2>
      <p>Select specific days you want to pause your subscription. You will receive a refund/credit for each paused day.</p>
      
      <form id="pause-per-day-form" method="POST">
        @csrf
        <div class="form-group">
          <label for="pause-month">Select Month <span class="required">*</span></label>
          <select id="pause-month" name="pause_month" required class="form-select">
            <option value="">📅 Choose a month to pause...</option>
            <option value="{{ date('Y-m') }}">🗓️ {{ date('F Y') }} (This Month)</option>
            <option value="{{ date('Y-m', strtotime('+1 month')) }}">📅 {{ date('F Y', strtotime('+1 month')) }} (Next Month)</option>
            <option value="{{ date('Y-m', strtotime('+2 months')) }}">🗓️ {{ date('F Y', strtotime('+2 months')) }}</option>
          </select>
          <small class="form-note">Select the month you want to pause specific days for your subscription</small>
        </div>
        
        <div class="form-group" id="calendar-container" style="display: none;">
          <label>Select Days to Pause <span class="required">*</span></label>
          <div class="calendar-grid" id="calendar-grid">
            <!-- Calendar will be generated here -->
          </div>
          <small class="form-note">Click on days to toggle pause. Only delivery days are selectable.</small>
        </div>
        
        <div class="form-group">
          <label for="pause-per-day-reason">Reason (Optional)</label>
          <textarea id="pause-per-day-reason" name="reason" rows="3" 
                    placeholder="Tell us why you're pausing these days..."></textarea>
        </div>
        
        <div id="pause-per-day-calculation" style="display: none;">
          <div class="calculation-box">
            <h4>Pause Calculation</h4>
            <p><strong>Selected Days:</strong> <span id="selected-days-count">0</span> days</p>
            <p><strong>Estimated Refund:</strong> Rp <span id="per-day-refund">0</span></p>
            <div id="selected-days-list"></div>
            <p class="note">The refund will be applied as credit to your account.</p>
          </div>
        </div>
        
        <div class="modal-actions">
          <button type="button" class="btn-secondary" onclick="closePausePerDayModal()">Cancel</button>
          <button type="submit" class="btn-primary" id="confirm-per-day-pause">Confirm Pause</button>
        </div>
      </form>
    </div>
  </div>
 
 
@endsection
@section('javascript')
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script>
        let currentSubscriptionId = null;
        let currentSubscriptionData = {};
        let selectedDays = [];
        let deliveryDays = [];

        function openPauseModal(subscriptionId) {
            console.log('Opening pause modal for subscription:', subscriptionId);
            currentSubscriptionId = subscriptionId;
            
            // Find subscription data from the subscription item
            const subscriptionItems = document.querySelectorAll('.subscription-item');
            console.log('Found subscription items:', subscriptionItems.length);
            let foundData = false;
            
            subscriptionItems.forEach(item => {
                const pauseBtn = item.querySelector('.pause-btn');
                console.log('Checking pause button:', pauseBtn);
                if (pauseBtn && pauseBtn.getAttribute('onclick').includes(subscriptionId)) {
                    console.log('Found matching pause button for subscription:', subscriptionId);
                    // Extract data from this subscription item
                    const details = item.querySelector('.subscription-details');
                    const priceText = details.children[5].textContent; // Monthly Total line
                    const mealTypesText = details.children[2].textContent; // Meal Types line
                    const deliveryDaysText = details.children[3].textContent; // Delivery Days line
                    
                    console.log('Price text:', priceText);
                    console.log('Meal types text:', mealTypesText);
                    console.log('Delivery days text:', deliveryDaysText);
                    
                    // Parse meal types count
                    const mealTypesMatch = mealTypesText.match(/:\s*(.+)/);
                    const mealTypes = mealTypesMatch ? mealTypesMatch[1].split(',').length : 1;
                    
                    // Parse delivery days count  
                    const deliveryDaysMatch = deliveryDaysText.match(/:\s*(.+)/);
                    const deliveryDays = deliveryDaysMatch ? deliveryDaysMatch[1].split(',').length : 1;
                    
                    // Parse total price
                    const totalPrice = parseInt(priceText.replace(/[^\d]/g, ''));
                    
                    currentSubscriptionData = {
                        id: subscriptionId,
                        totalPrice: totalPrice,
                        mealTypes: mealTypes,
                        deliveryDays: deliveryDays
                    };
                    
                    foundData = true;
                    console.log('Subscription data found:', currentSubscriptionData);
                }
            });
            
            if (!foundData) {
                console.error('Could not find subscription data for ID:', subscriptionId);
                currentSubscriptionData = {
                    id: subscriptionId,
                    totalPrice: 0,
                    mealTypes: 1,
                    deliveryDays: 1
                };
            }
            
            // Set form action
            const pauseForm = document.getElementById('pause-form');
            console.log('Pause form element:', pauseForm);
            if (pauseForm) {
                pauseForm.action = `/subscription/${subscriptionId}/pause`;
                console.log('Form action set to:', pauseForm.action);
            } else {
                console.error('Pause form not found!');
            }
            
            // Set minimum date for pause start (tomorrow)
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);
            document.getElementById('pause-start').min = tomorrow.toISOString().split('T')[0];
            
            // Reset calculation display
            document.getElementById('pause-calculation').style.display = 'none';
            const existingBreakdown = document.querySelector('.calculation-breakdown');
            if (existingBreakdown) {
                existingBreakdown.remove();
            }
            
            // Show modal
            const modal = document.getElementById('pause-subscription-modal');
            console.log('Modal element:', modal);
            if (modal) {
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';
                console.log('Modal should be visible now');
            } else {
                console.error('Modal element not found!');
            }
        }

        function closePauseModal() {
            document.getElementById('pause-subscription-modal').style.display = 'none';
            document.body.style.overflow = 'auto';
            
            // Reset form
            document.getElementById('pause-form').reset();
            document.getElementById('pause-calculation').style.display = 'none';
            
            // Remove breakdown
            const existingBreakdown = document.querySelector('.calculation-breakdown');
            if (existingBreakdown) {
                existingBreakdown.remove();
            }
        }

        function openPausePerDayModal(subscriptionId) {
            console.log('Opening pause per day modal for subscription:', subscriptionId);
            currentSubscriptionId = subscriptionId;
            selectedDays = [];
            
            // Reset dropdown to default state
            const monthSelect = document.getElementById('pause-month');
            if (monthSelect) {
                monthSelect.value = '';
                monthSelect.disabled = false;
            }
            
            // Find subscription data
            const subscriptionItems = document.querySelectorAll('.subscription-item');
            let foundData = false;
            
            subscriptionItems.forEach(item => {
                const pauseBtns = item.querySelectorAll('.pause-btn, .pause-per-day-btn');
                pauseBtns.forEach(btn => {
                    if (btn.getAttribute('onclick') && btn.getAttribute('onclick').includes(subscriptionId)) {
                        const details = item.querySelector('.subscription-details');
                        const priceText = details.children[5].textContent;
                        const mealTypesText = details.children[2].textContent;
                        const deliveryDaysText = details.children[3].textContent;
                        
                        // Parse delivery days
                        const deliveryDaysMatch = deliveryDaysText.match(/:\s*(.+)/);
                        if (deliveryDaysMatch) {
                            const dayNames = deliveryDaysMatch[1].split(',').map(day => day.trim().toLowerCase());
                            deliveryDays = dayNames.map(day => {
                                const dayMap = {
                                    'mon': 'monday', 'tue': 'tuesday', 'wed': 'wednesday',
                                    'thu': 'thursday', 'fri': 'friday', 'sat': 'saturday', 'sun': 'sunday'
                                };
                                return dayMap[day] || day;
                            });
                        }
                        
                        // Parse meal types and price
                        const mealTypesMatch = mealTypesText.match(/:\s*(.+)/);
                        const mealTypes = mealTypesMatch ? mealTypesMatch[1].split(',').length : 1;
                        const totalPrice = parseInt(priceText.replace(/[^\d]/g, ''));
                        
                        currentSubscriptionData = {
                            id: subscriptionId,
                            totalPrice: totalPrice,
                            mealTypes: mealTypes,
                            deliveryDays: deliveryDays.length
                        };
                        
                        foundData = true;
                        console.log('Subscription data found:', currentSubscriptionData);
                        console.log('Delivery days:', deliveryDays);
                    }
                });
            });
            
            if (!foundData) {
                console.error('Could not find subscription data for ID:', subscriptionId);
                return;
            }
            
            // Set form action
            document.getElementById('pause-per-day-form').action = `/subscription/${subscriptionId}/pause-per-day`;
            
            // Reset form and calculation
            document.getElementById('pause-per-day-form').reset();
            document.getElementById('pause-per-day-calculation').style.display = 'none';
            document.getElementById('calendar-container').style.display = 'none';
            
            // Show modal
            document.getElementById('pause-per-day-modal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closePausePerDayModal() {
            document.getElementById('pause-per-day-modal').style.display = 'none';
            document.body.style.overflow = 'auto';
            
            // Reset form and data
            document.getElementById('pause-per-day-form').reset();
            document.getElementById('pause-per-day-calculation').style.display = 'none';
            document.getElementById('calendar-container').style.display = 'none';
            selectedDays = [];
        }

        function generateCalendar(year, month) {
            const calendarGrid = document.getElementById('calendar-grid');
            calendarGrid.innerHTML = '';
            
            // Get paused days for this subscription (we'll fetch this from the subscription data)
            let pausedDaysForSubscription = [];
            
            // Find subscription data to get already paused days
            fetch(`/subscription/${currentSubscriptionId}/paused-days`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    pausedDaysForSubscription = data.paused_days;
                }
                buildCalendarDOM(year, month, pausedDaysForSubscription);
            })
            .catch(error => {
                console.error('Error fetching paused days:', error);
                buildCalendarDOM(year, month, []);
            });
        }

        function buildCalendarDOM(year, month, pausedDaysForSubscription) {
            const calendarGrid = document.getElementById('calendar-grid');
            calendarGrid.innerHTML = '';
            
            // Add day headers
            const dayHeaders = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            dayHeaders.forEach(day => {
                const dayElement = document.createElement('div');
                dayElement.className = 'calendar-day header';
                dayElement.textContent = day;
                calendarGrid.appendChild(dayElement);
            });
            
            // Get first day of month and number of days
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const daysInMonth = lastDay.getDate();
            const startingDayOfWeek = firstDay.getDay();
            
            // Add empty cells for days before month starts
            for (let i = 0; i < startingDayOfWeek; i++) {
                const emptyDay = document.createElement('div');
                emptyDay.className = 'calendar-day empty';
                calendarGrid.appendChild(emptyDay);
            }
            
            // Add days of the month
            const today = new Date();
            for (let day = 1; day <= daysInMonth; day++) {
                const dayElement = document.createElement('div');
                const currentDate = new Date(year, month, day);
                const dayOfWeek = currentDate.toLocaleDateString('en-US', { weekday: 'long' }).toLowerCase();
                const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                
                dayElement.className = 'calendar-day';
                dayElement.textContent = day;
                dayElement.dataset.date = dateString;
                
                // Check if this day is in the past
                if (currentDate < today.setHours(0, 0, 0, 0)) {
                    dayElement.classList.add('past');
                }
                // Check if this day is already paused
                else if (pausedDaysForSubscription.includes(dateString)) {
                    dayElement.classList.add('already-paused');
                    dayElement.title = 'This day is already paused';
                }
                // Check if this day is a delivery day
                else if (deliveryDays.includes(dayOfWeek)) {
                    dayElement.classList.add('delivery');
                    dayElement.addEventListener('click', () => toggleDaySelection(dayElement));
                } else {
                    dayElement.classList.add('non-delivery');
                }
                
                calendarGrid.appendChild(dayElement);
            }
            
            // Add legend
            const legendHtml = `
                <div class="calendar-legend">
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #e8f5e8; border-color: #28a745;"></div>
                        <span>Delivery Day</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #007bff;"></div>
                        <span>Selected</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #ffc107;"></div>
                        <span>Already Paused</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #f8f9fa;"></div>
                        <span>Non-delivery Day</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #e9ecef;"></div>
                        <span>Past Date</span>
                    </div>
                </div>
            `;
            
            const calendarContainer = document.getElementById('calendar-container');
            let existingLegend = calendarContainer.querySelector('.calendar-legend');
            if (existingLegend) {
                existingLegend.remove();
            }
            calendarContainer.insertAdjacentHTML('beforeend', legendHtml);
        }

        function toggleDaySelection(dayElement) {
            const date = dayElement.dataset.date;
            
            if (dayElement.classList.contains('selected')) {
                // Deselect
                dayElement.classList.remove('selected');
                selectedDays = selectedDays.filter(d => d !== date);
            } else {
                // Select
                dayElement.classList.add('selected');
                selectedDays.push(date);
            }
            
            updatePerDayCalculation();
        }

        function updatePerDayCalculation() {
            const selectedDaysCount = selectedDays.length;
            
            if (selectedDaysCount > 0) {
                // Calculate daily rate
                const dailyRate = currentSubscriptionData.totalPrice / 30; // Simple daily rate calculation
                const refundAmount = dailyRate * selectedDaysCount;
                
                // Update display
                document.getElementById('selected-days-count').textContent = selectedDaysCount;
                document.getElementById('per-day-refund').textContent = Math.round(refundAmount).toLocaleString('id-ID');
                
                // Update selected days list
                const selectedDaysList = document.getElementById('selected-days-list');
                selectedDaysList.innerHTML = '<strong>Selected Days:</strong><br>';
                
                selectedDays.sort().forEach(date => {
                    const dateObj = new Date(date);
                    const dayName = dateObj.toLocaleDateString('en-US', { weekday: 'short' });
                    const dayNum = dateObj.getDate();
                    
                    const dayItem = document.createElement('span');
                    dayItem.className = 'selected-day-item';
                    dayItem.textContent = `${dayName} ${dayNum}`;
                    selectedDaysList.appendChild(dayItem);
                });
                
                document.getElementById('pause-per-day-calculation').style.display = 'block';
            } else {
                document.getElementById('pause-per-day-calculation').style.display = 'none';
            }
        }

        // Fallback client-side calculation
        function fallbackCalculation(start, end) {
            console.log('Using fallback calculation...');
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            
            // Calculate daily rate based on delivery schedule
            const weeklyMeals = currentSubscriptionData.mealTypes * currentSubscriptionData.deliveryDays;
            const monthlyRate = currentSubscriptionData.totalPrice;
            const weeklyRate = monthlyRate / 4.3; // 4.3 weeks per month
            const dailyRate = weeklyRate / 7; // 7 days per week
            const refund = dailyRate * diffDays;
            
            document.getElementById('pause-days').textContent = diffDays;
            document.getElementById('pause-refund').textContent = Math.round(refund).toLocaleString('id-ID');
            document.getElementById('pause-calculation').style.display = 'block';
            
            const breakdownHtml = `
                <div class="calculation-breakdown">
                    <h5>Calculation Breakdown (Estimated):</h5>
                    <p><small>Delivery Days per Week: ${currentSubscriptionData.deliveryDays}</small></p>
                    <p><small>Meal Types per Day: ${currentSubscriptionData.mealTypes}</small></p>
                    <p><small>Daily Rate: Rp ${Math.round(dailyRate).toLocaleString('id-ID')}</small></p>
                    <p><small>Pause Duration: ${diffDays} days</small></p>
                    <p><small>Total Refund: ${diffDays} × Rp ${Math.round(dailyRate).toLocaleString('id-ID')} = Rp ${Math.round(refund).toLocaleString('id-ID')}</small></p>
                    <p style="margin-top: 10px; color: #ffc107; font-weight: 600;"><small>⚠ Estimated calculation (server calculation unavailable)</small></p>
                </div>
            `;
            
            const existingBreakdown = document.querySelector('.calculation-breakdown');
            if (existingBreakdown) {
                existingBreakdown.remove();
            }
            document.getElementById('pause-calculation').insertAdjacentHTML('beforeend', breakdownHtml);
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, setting up event listeners...');
            
            // Sidebar navigation
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
            
            // Modal close events
            const cancelBtn = document.getElementById('cancel-pause');
            const closeBtn = document.querySelector('.close-modal');
            
            if (cancelBtn) {
                cancelBtn.addEventListener('click', closePauseModal);
            }
            
            if (closeBtn) {
                closeBtn.addEventListener('click', closePauseModal);
            }
            
            // Click outside modal to close
            const modal = document.getElementById('pause-subscription-modal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closePauseModal();
                    }
                });
            }
            
            // Date change events for calculation
            const startDateInput = document.getElementById('pause-start');
            const endDateInput = document.getElementById('pause-end');
            
            if (startDateInput) {
                startDateInput.addEventListener('change', function() {
                    console.log('Start date changed:', this.value);
                    // Set minimum end date
                    const startDate = this.value;
                    if (startDate) {
                        const start = new Date(startDate);
                        const nextDay = new Date(start);
                        nextDay.setDate(nextDay.getDate() + 1);
                        endDateInput.min = nextDay.toISOString().split('T')[0];
                    }
                    calculatePauseRefund();
                });
            }
            
            if (endDateInput) {
                endDateInput.addEventListener('change', function() {
                    console.log('End date changed:', this.value);
                    calculatePauseRefund();
                });
            }
            
            // Month change event for per-day calendar
            const pauseMonthInput = document.getElementById('pause-month');
            if (pauseMonthInput) {
                pauseMonthInput.addEventListener('change', function() {
                    const selectedMonth = this.value;
                    console.log('Selected month:', selectedMonth);
                    
                    if (selectedMonth) {
                        const [year, month] = selectedMonth.split('-');
                        generateCalendar(parseInt(year), parseInt(month) - 1);
                        document.getElementById('calendar-container').style.display = 'block';
                        selectedDays = [];
                        updatePerDayCalculation();
                    } else {
                        document.getElementById('calendar-container').style.display = 'none';
                        selectedDays = [];
                        updatePerDayCalculation();
                    }
                });
            }
            
            // Form submission
            const pauseForm = document.getElementById('pause-form');
            if (pauseForm) {
                pauseForm.addEventListener('submit', function(e) {
                    console.log('Form submitted');
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
                    const confirmBtn = document.getElementById('confirm-pause');
                    if (confirmBtn) {
                        confirmBtn.disabled = true;
                        confirmBtn.textContent = 'Processing...';
                    }
                });
            }
            
            // Pause Per Day form submission
            const pausePerDayForm = document.getElementById('pause-per-day-form');
            if (pausePerDayForm) {
                pausePerDayForm.addEventListener('submit', function(e) {
                    console.log('Pause per day form submitted');
                    const selectedDays = document.querySelectorAll('#calendar-grid .selected');
                    
                    if (selectedDays.length === 0) {
                        e.preventDefault();
                        alert('Please select at least one day to pause.');
                        return;
                    }
                    
                    // Show loading state
                    const confirmBtn = document.getElementById('confirm-per-day-pause');
                    if (confirmBtn) {
                        confirmBtn.disabled = true;
                        confirmBtn.textContent = 'Processing...';
                    }
                });
            }
            
            console.log('Event listeners setup complete');
        });
        
        function showCalendar(month) {
            console.log('Showing calendar for month:', month);
            const calendarGrid = document.getElementById('calendar-grid');
            calendarGrid.innerHTML = ''; // Clear existing calendar
            
            const date = new Date(month + '-01');
            const year = date.getFullYear();
            const monthIndex = date.getMonth();
            
            // Get first day of the month
            const firstDay = new Date(year, monthIndex, 1);
            const lastDay = new Date(year, monthIndex + 1, 0);
            
            // Get delivery days in this month
            const deliveryDays = [];
            @foreach($subscriptions as $subscription)
              @foreach($subscription->deliveryDays as $day)
                deliveryDays.push('{{ $day->day_of_week }}');
              @endforeach
            @endforeach
            
            // Generate calendar
            let html = '';
            for (let d = 1; d <= lastDay.getDate(); d++) {
                const currentDate = new Date(year, monthIndex, d);
                const dayOfWeek = currentDate.toLocaleString('default', { weekday: 'long' });
                
                // Check if this is a delivery day
                const isDeliveryDay = deliveryDays.includes(dayOfWeek);
                
                html += `
                    <div class="calendar-cell ${isDeliveryDay ? 'delivery-day' : ''}" 
                         data-date="${currentDate.toISOString().split('T')[0]}" 
                         onclick="togglePauseDay(this)">
                      ${d}
                    </div>
                `;
            }
            
            calendarGrid.innerHTML = html;
            document.getElementById('calendar-container').style.display = 'block';
        }

        function togglePauseDay(cell) {
            console.log('Toggling pause for day:', cell.dataset.date);
            cell.classList.toggle('selected');
            
            updatePausePerDayCalculation();
        }

        function updatePausePerDayCalculation() {
            const selectedDays = document.querySelectorAll('#calendar-grid .selected');
            const count = selectedDays.length;
            const totalRefundElement = document.getElementById('per-day-refund');
            const selectedDaysList = document.getElementById('selected-days-list');
            
            selectedDaysList.innerHTML = ''; // Clear existing list
            
            if (count > 0) {
                // Calculate total refund
                const dailyRate = Math.round(currentSubscriptionData.totalPrice / (currentSubscriptionData.deliveryDays * 4.3));
                const refund = dailyRate * count;
                
                totalRefundElement.textContent = refund.toLocaleString('id-ID');
                
                // Show selected days
                selectedDays.forEach(day => {
                    const date = day.dataset.date;
                    const dayItem = document.createElement('div');
                    dayItem.textContent = new Date(date).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'numeric', day: 'numeric' });
                    selectedDaysList.appendChild(dayItem);
                });
                
                document.getElementById('pause-per-day-calculation').style.display = 'block';
            } else {
                totalRefundElement.textContent = '0';
                document.getElementById('pause-per-day-calculation').style.display = 'none';
            }
        }
    </script>
@endsection