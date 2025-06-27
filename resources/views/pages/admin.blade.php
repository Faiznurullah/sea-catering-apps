@extends('layouts.template')
@section('title', 'CateringApp')
@section('css')
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}"> 
@endsection
@section('content')

  <!-- Admin Dashboard Section -->
  <section class="admin-section">
    <div class="container">
      <div class="admin-container">
        <!-- Sidebar -->
        <div class="admin-sidebar">
          <div class="user-info">
            <div class="user-avatar admin-avatar">A</div>
            <div class="user-details">
              <h3>{{ Auth::user()->name }}</h3>
              <p>{{ Auth::user()->email }}</p>
            </div>
          </div>
            <ul class="sidebar-menu">
            <li class="active"><a href="#dashboard">Dashboard</a></li>
            <li><a href="#subscriptions">Subscriptions</a></li>
            <li><a href="#plan-distribution">Plan Distribution</a></li>  
            <li><a href="#customers">Customers</a></li>
            <li><a href="#experience-users">Experience Users</a></li>
            <li><a href="#contacts">Contacts</a></li>
          </ul>
        </div>
        
        <!-- Main Content -->
        <div class="admin-content">
          <div class="admin-header">
            <h1>Admin Dashboard</h1>
            <div class="date-range-selector">
              <label for="date-range">Date Range:</label>
              <select id="date-range">
                <option value="7">Last 7 days</option>
                <option value="30" selected>Last 30 days</option>
                <option value="90">Last 90 days</option>
                <option value="custom">Custom Range</option>
              </select>
            </div>
          </div>
            <!-- Stats Cards -->
          <div class="stats-grid">
            <div class="stat-card">
              <div class="stat-icon new-subscriptions-icon">📈</div>
              <div class="stat-content">
                <h3>New Subscriptions</h3>
                <p class="stat-value">{{ $subscriptions->where('created_at', '>=', now()->subDays(30))->count() }}</p>
                <p class="stat-change positive">This month</p>
              </div>
            </div>
            
            <div class="stat-card">
              <div class="stat-icon mrr-icon">💰</div>
              <div class="stat-content">
                <h3>Monthly Recurring Revenue</h3>
                <p class="stat-value">Rp {{ number_format($subscriptions->where('status', 'active')->sum('total_price'), 0, ',', '.') }}</p>
                <p class="stat-change positive">Active subscriptions</p>
              </div>
            </div>
            
            <div class="stat-card">
              <div class="stat-icon reactivations-icon">🔄</div>
              <div class="stat-content">
                <h3>Pending Approvals</h3>
                <p class="stat-value">{{ $subscriptions->where('status', 'pending')->count() }}</p>
                <p class="stat-change {{ $subscriptions->where('status', 'pending')->count() > 0 ? 'warning' : 'positive' }}">Needs attention</p>
              </div>
            </div>
            
            <div class="stat-card">
              <div class="stat-icon active-subs-icon">👥</div>
              <div class="stat-content">
                <h3>Active Subscriptions</h3>
                <p class="stat-value">{{ $subscriptions->where('status', 'active')->count() }}</p>
                <p class="stat-change positive">Currently active</p>
              </div>
            </div>
          </div> 

            <!-- Recent Subscriptions -->
          <div class="admin-card" id="subscriptions">
            <div class="card-header">
              <h2>Subscription Management</h2>
              <div class="card-header-actions">                <select id="status-filter" onchange="filterSubscriptions(this.value)">
                  <option value="all">All Status</option>
                  <option value="pending">Pending Approval</option>
                  <option value="active">Active</option>
                  <option value="paused">Paused</option>
                  <option value="cancelled">Cancelled</option>
                  <option value="rejected">Rejected</option>
                </select>
              </div>
            </div>
            <div class="card-content">
              @if($subscriptions->count() > 0)
              <div class="table-responsive">
                <table class="admin-table">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Customer</th>
                      <th>Plan</th>
                      <th>Meal Types</th>
                      <th>Delivery Days</th>
                      <th>Monthly Value</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>                    @foreach($subscriptions as $subscription)
                    <tr class="subscription-row" data-status="{{ $subscription->status }}" data-subscription-id="{{ $subscription->id }}">
                      <td>#{{ $subscription->id }}</td>
                      <td>
                        <div class="customer-info">
                          <strong>{{ $subscription->name }}</strong>
                          <small>{{ $subscription->phone }}</small>
                        </div>
                      </td>
                      <td>{{ $subscription->mealPlan->name }}</td>
                      <td>
                        <div class="meal-types">
                          @foreach($subscription->subscriptionMeals as $meal)
                            <span class="meal-tag">{{ ucfirst($meal->meal_type) }}</span>
                          @endforeach
                        </div>
                      </td>
                      <td>
                        <div class="delivery-days">
                          @foreach($subscription->deliveryDays as $day)
                            <span class="day-tag">{{ ucfirst(substr($day->day_of_week, 0, 3)) }}</span>
                          @endforeach
                        </div>
                      </td>
                      <td>
                        <strong>Rp{{ number_format($subscription->total_price, 0, ',', '.') }}</strong>
                        @if($subscription->refund_amount > 0)
                          <br><small class="refund-info">Credit: Rp{{ number_format($subscription->refund_amount, 0, ',', '.') }}</small>
                        @endif
                      </td>
                      <td>
                        <span class="status {{ $subscription->status }}">{{ ucfirst($subscription->status) }}</span>
                        @if($subscription->status === 'paused' && $subscription->pause_end_date)
                          <br><small>Until {{ $subscription->pause_end_date->format('d M Y') }}</small>
                        @endif
                      </td>
                      <td>
                        <div class="table-actions">
                          @if($subscription->status === 'pending')
                            <button class="action-btn approve-btn" 
                                    onclick="approveSubscription({{ $subscription->id }})" 
                                    title="Approve">✅</button>
                            <button class="action-btn reject-btn" 
                                    onclick="rejectSubscription({{ $subscription->id }})" 
                                    title="Reject">❌</button>
                          @endif
                          
                          @if($subscription->status === 'active')
                            <button class="action-btn pause-btn" 
                                    onclick="pauseSubscription({{ $subscription->id }})" 
                                    title="Pause">⏸️</button>
                          @endif
                          
                          @if($subscription->status === 'paused')
                            <button class="action-btn resume-btn" 
                                    onclick="resumeSubscription({{ $subscription->id }})" 
                                    title="Resume">▶️</button>
                          @endif
                          
                          <button class="action-btn view-btn" 
                                  onclick="viewSubscription({{ $subscription->id }})" 
                                  title="View Details">👁️</button>
                          
                          @if($subscription->allergies)
                            <button class="action-btn allergy-btn" 
                                    onclick="viewAllergies('{{ addslashes($subscription->allergies) }}')" 
                                    title="View Allergies">🚨</button>
                          @endif
                        </div>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              
              <!-- Pagination -->
              <div class="pagination-wrapper">
                {{ $subscriptions->links() }}
              </div>
              @else
              <div class="empty-state">
                <h3>No Subscriptions Found</h3>
                <p>No subscription data available in the system.</p>
              </div>
              @endif
            </div>
          </div>
                      <td>
                        <div class="table-actions">
                          <button class="action-btn view-btn" title="View Details">👁️</button>
                          <button class="action-btn edit-btn" title="Edit">✏️</button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          
          <!-- Plan Distribution -->
          <div class="admin-card" id="plan-distribution">
            <div class="card-header">
              <h2>Plan Distribution</h2>
              <div class="card-header-actions">
                <span class="badge">{{ $totalActiveSubscriptions }} Active Subscriptions</span>
              </div>
            </div>
            <div class="card-content">
              @if($totalActiveSubscriptions > 0)
              <div class="chart-container">
                <div class="pie-chart-placeholder">
                  <canvas id="planDistributionChart" width="200" height="200"></canvas>
                  <div class="pie-legend">
                    @foreach($planStats as $plan)
                      @php
                        $planClass = strtolower(str_replace(' ', '-', $plan['name']));
                        if (strpos(strtolower($plan['name']), 'diet') !== false) {
                          $planClass = 'diet';
                        } elseif (strpos(strtolower($plan['name']), 'protein') !== false) {
                          $planClass = 'protein';
                        } elseif (strpos(strtolower($plan['name']), 'royal') !== false) {
                          $planClass = 'royal';
                        }
                      @endphp
                      <div class="legend-item">
                        <span class="legend-color {{ $planClass }}"></span>
                        <span>{{ $plan['name'] }} ({{ $plan['percentage'] }}% - {{ $plan['count'] }} subs)</span>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
              @else
              <div class="empty-state">
                <div class="empty-icon">📊</div>
                <h3>No Active Subscriptions</h3>
                <p>No active subscriptions to display in the plan distribution chart.</p>
              </div>
              @endif
            </div>
          </div>

          <!-- Plan Stats Data for JavaScript -->
          <script>
            window.planStatsData = @json($planStats ?? []);
            window.totalActiveSubscriptions = {{ $totalActiveSubscriptions ?? 0 }};
          </script>

          <!-- Customers Section -->
          <div class="admin-card" id="customers">
            <div class="card-header">
              <h2>Customer Management</h2>
              <div class="card-header-actions">
                <span class="customer-count">Total: {{ $customers->count() }} customers</span>
              </div>
            </div>
            <div class="card-content">
              @if($customers->count() > 0)
              <div class="table-responsive">
                <table class="admin-table">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Customer Info</th>
                      <th>Contact</th>
                      <th>Location</th> 
                      <th>Points</th>
                      <th>Registration Date</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($customers as $customer)
                    <tr class="customer-row">
                      <td>#{{ $customer->id }}</td>
                      <td>
                        <div class="customer-info">
                          <div class="customer-avatar">
                            @if($customer->foto)
                              <img src="{{ asset('storage/' . $customer->foto) }}" alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                            @else
                              <div style="width: 40px; height: 40px; border-radius: 50%; background: #007bff; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                {{ strtoupper(substr($customer->name, 0, 2)) }}
                              </div>
                            @endif
                          </div>
                          <div class="customer-details">
                            <strong>{{ $customer->name }}</strong>
                            <small>{{ $customer->email }}</small>
                          </div>
                        </div>
                      </td>
                      <td>
                        <div class="contact-info">
                          @if($customer->phone)
                            <div>📞 {{ $customer->phone }}</div>
                          @else
                            <span class="text-muted">No phone</span>
                          @endif
                        </div>
                      </td>
                      <td>
                        <div class="location-info">
                          @if($customer->city)
                            <div>🏙️ {{ $customer->city }}</div>
                          @endif
                          @if($customer->national)
                            <div>🌍 {{ $customer->national }}</div>
                          @endif
                          @if(!$customer->city && !$customer->national)
                            <span class="text-muted">Not provided</span>
                          @endif
                        </div>
                      </td> 
                      <td>
                        <div class="points-info">
                          <span class="points-badge">{{ number_format($customer->point ?? 0) }} pts</span>
                        </div>
                      </td>
                      <td>
                        <div class="date-info">
                          <div>{{ $customer->created_at->format('d M Y') }}</div>
                          <small class="text-muted">{{ $customer->created_at->diffForHumans() }}</small>
                        </div>
                      </td>
                      <td>
                        <div class="status-info">
                          @if($customer->email_verified_at)
                            <span class="status-badge verified">✓ Verified</span>
                          @else
                            <span class="status-badge unverified">⚠ Unverified</span>
                          @endif
                        </div>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              @else
              <div class="empty-state">
                <div class="empty-icon">👥</div>
                <h3>No Customers Yet</h3>
                <p>No customers have registered yet.</p>
              </div>
              @endif
            </div>          </div>

          <!-- Experience Users Table -->
          <div class="admin-card" id="experience-users">
            <div class="card-header">
              <h2>Experience Users</h2>
              <div class="card-header-actions">
                <span class="badge">{{ $experienceUsers->count() }} Total Reviews</span>
              </div>
            </div>
            <div class="card-content">
              @if($experienceUsers->count() > 0)
              <div class="table-responsive">
                <table class="admin-table">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>User</th>
                      <th>Name</th>
                      <th>Review</th>
                      <th>Rating</th>
                      <th>Date Created</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($experienceUsers as $experience)
                    <tr>
                      <td>#{{ $experience->id }}</td>
                      <td>
                        <div class="customer-info">
                          @if($experience->user)
                            <strong>{{ $experience->user->name }}</strong>
                            <small>{{ $experience->user->email }}</small>
                          @else
                            <span class="text-muted">User not found</span>
                          @endif
                        </div>
                      </td>
                      <td>
                        <strong>{{ $experience->name }}</strong>
                      </td>
                      <td>
                        <div class="review-text">
                          {{ Str::limit($experience->review, 100) }}
                          @if(strlen($experience->review) > 100)
                            <button class="btn-link" onclick="showFullReview('{{ addslashes($experience->review) }}')">Read more</button>
                          @endif
                        </div>
                      </td>
                      <td>
                        <div class="rating-display">
                          @for($i = 1; $i <= 5; $i++)
                            <span class="star {{ $i <= $experience->star ? 'filled' : '' }}">⭐</span>
                          @endfor
                          <span class="rating-value">({{ $experience->star }}/5)</span>
                        </div>
                      </td>
                      <td>
                        <div class="date-info">
                          <div>{{ $experience->created_at->format('d M Y') }}</div>
                          <small class="text-muted">{{ $experience->created_at->diffForHumans() }}</small>
                        </div>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              @else
              <div class="empty-state">
                <div class="empty-icon">⭐</div>
                <h3>No Experience Reviews Yet</h3>
                <p>No user experiences have been submitted yet.</p>
              </div>
              @endif
            </div>
          </div>

          <!-- Contacts Table -->
          <div class="admin-card" id="contacts">
            <div class="card-header">
              <h2>Contact Messages</h2>
              <div class="card-header-actions">
                <span class="badge">{{ $contacts->whereNull('read_at')->count() }} Unread</span>
                <span class="badge">{{ $contacts->count() }} Total</span>
              </div>
            </div>
            <div class="card-content">
              @if($contacts->count() > 0)
              <div class="table-responsive">
                <table class="admin-table">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Contact Info</th>
                      <th>Subject</th>
                      <th>Message</th>
                      <th>Status</th>
                      <th>Date</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>                    @foreach($contacts as $contact)
                    <tr class="{{ $contact->read_at ? 'read' : 'unread' }}" data-contact-id="{{ $contact->id }}">
                      <td>#{{ $contact->id }}</td>
                      <td>
                        <strong>{{ $contact->name }}</strong>
                      </td>
                      <td>
                        <div class="contact-details">
                          <div>📧 {{ $contact->email }}</div>
                          @if($contact->phone)
                            <div>📞 {{ $contact->phone }}</div>
                          @endif
                        </div>
                      </td>
                      <td>
                        <strong>{{ $contact->subject }}</strong>
                      </td>
                      <td>
                        <div class="message-preview">
                          {{ Str::limit($contact->message, 100) }}
                          @if(strlen($contact->message) > 100)
                            <button class="btn-link" onclick="showFullMessage('{{ addslashes($contact->message) }}', '{{ $contact->subject }}')">Read more</button>
                          @endif
                        </div>
                      </td>
                      <td>
                        @if($contact->read_at)
                          <span class="status read">✓ Read</span>
                          <br><small class="text-muted">{{ $contact->read_at->format('d M Y H:i') }}</small>
                        @else
                          <span class="status unread">● Unread</span>
                        @endif
                      </td>
                      <td>
                        <div class="date-info">
                          <div>{{ $contact->created_at->format('d M Y') }}</div>
                          <small class="text-muted">{{ $contact->created_at->diffForHumans() }}</small>
                        </div>
                      </td>
                      <td>
                        <div class="table-actions">
                          @if(!$contact->read_at)
                            <button class="action-btn mark-read-btn" 
                                    onclick="markAsRead({{ $contact->id }})" 
                                    title="Mark as Read">✓</button>
                          @endif
                          <button class="action-btn view-btn" 
                                  onclick="showFullMessage('{{ addslashes($contact->message) }}', '{{ $contact->subject }}')" 
                                  title="View Full Message">👁️</button>
                        </div>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              @else
              <div class="empty-state">
                <div class="empty-icon">📮</div>
                <h3>No Contact Messages</h3>
                <p>No contact messages have been received yet.</p>
              </div>
              @endif
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>
  <!-- Custom Date Range Modal -->
  <div class="modal" id="date-range-modal">
    <div class="modal-content">
      <span class="close-modal">&times;</span>
      <h2>Select Custom Date Range</h2>
      <form id="date-range-form">
        <div class="form-group">
          <label for="start-date">Start Date</label>
          <input type="date" id="start-date" name="start-date" required>
        </div>
        <div class="form-group">
          <label for="end-date">End Date</label>
          <input type="date" id="end-date" name="end-date" required>
        </div>
        <div class="modal-actions">
          <button type="button" class="btn-secondary" id="cancel-date-range">Cancel</button>
          <button type="submit" class="btn-primary">Apply</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Subscription Detail Modal -->
  <div class="modal" id="subscription-detail-modal" style="display: none;">
    <div class="modal-content modal-large">
      <span class="close-modal">&times;</span>
      <h2>Subscription Details</h2>
      <div id="subscription-detail-content">
        <!-- Content will be loaded via AJAX -->
      </div>
    </div>
  </div>

  <!-- Approve Subscription Modal -->
  <div class="modal" id="approve-subscription-modal" style="display: none;">
    <div class="modal-content">
      <span class="close-modal">&times;</span>
      <h2>Approve Subscription</h2>
      <p>Are you sure you want to approve this subscription?</p>
      <div class="subscription-summary" id="approve-subscription-summary">
        <!-- Will be populated via JavaScript -->
      </div>
      <form id="approve-form" method="POST">
        @csrf
        @method('PATCH')
        <div class="form-group">
          <label for="approve-notes">Admin Notes (Optional)</label>
          <textarea id="approve-notes" name="admin_notes" rows="3" placeholder="Add any notes about this approval..."></textarea>
        </div>
        <div class="modal-actions">
          <button type="button" class="btn-secondary" onclick="closeModal('approve-subscription-modal')">Cancel</button>
          <button type="submit" class="btn-success">Approve Subscription</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Reject Subscription Modal -->
  <div class="modal" id="reject-subscription-modal" style="display: none;">
    <div class="modal-content">
      <span class="close-modal">&times;</span>
      <h2>Reject Subscription</h2>
      <p class="warning-text">Are you sure you want to reject this subscription? This action cannot be undone.</p>
      <form id="reject-form" method="POST">
        @csrf
        @method('PATCH')
        <div class="form-group">
          <label for="reject-reason">Reason for Rejection <span class="required">*</span></label>
          <textarea id="reject-reason" name="rejection_reason" rows="3" required placeholder="Please provide a reason for rejection..."></textarea>
        </div>
        <div class="modal-actions">
          <button type="button" class="btn-secondary" onclick="closeModal('reject-subscription-modal')">Cancel</button>
          <button type="submit" class="btn-danger">Reject Subscription</button>
        </div>
      </form>
    </div>
  </div>
  <!-- Allergies Modal -->
  <div class="modal" id="allergies-modal" style="display: none;">
    <div class="modal-content">
      <span class="close-modal">&times;</span>
      <h2>Customer Allergies & Dietary Restrictions</h2>
      <div class="allergy-content">
        <div class="allergy-icon">🚨</div>
        <div id="allergy-text"></div>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn-primary" onclick="closeModal('allergies-modal')">Close</button>
      </div>
    </div>
  </div>

  <!-- Full Review Modal -->
  <div class="modal" id="full-review-modal" style="display: none;">
    <div class="modal-content">
      <span class="close-modal">&times;</span>
      <h2>Full Review</h2>
      <div class="review-content">
        <div id="full-review-text"></div>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn-primary" onclick="closeModal('full-review-modal')">Close</button>
      </div>
    </div>
  </div>

  <!-- Full Message Modal -->
  <div class="modal" id="full-message-modal" style="display: none;">
    <div class="modal-content">
      <span class="close-modal">&times;</span>
      <h2 id="message-subject">Contact Message</h2>
      <div class="message-content">
        <div id="full-message-text"></div>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn-primary" onclick="closeModal('full-message-modal')">Close</button>
      </div>
    </div>
  </div>

@endsection
@section('javascript')
<script src="{{ asset('js/scripts.js') }}"></script>
<script src="{{ asset('js/admin-subscription.js') }}"></script>
<script>
// Plan Distribution Pie Chart
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('planDistributionChart');
    if (canvas && window.planStatsData && window.planStatsData.length > 0) {
        const ctx = canvas.getContext('2d');
        const data = window.planStatsData;
        
        // Define colors for different plans
        const colors = {
            'diet': '#3498db',
            'protein': '#2ecc71', 
            'royal': '#f1c40f',
            'default': ['#e74c3c', '#9b59b6', '#e67e22', '#1abc9c', '#34495e']
        };
        
        // Calculate angles
        let currentAngle = -Math.PI / 2; // Start from top
        const centerX = canvas.width / 2;
        const centerY = canvas.height / 2;
        const radius = Math.min(centerX, centerY) - 10;
        
        // Clear canvas
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        // Draw pie slices
        data.forEach((plan, index) => {
            const sliceAngle = (plan.percentage / 100) * 2 * Math.PI;
            
            // Determine color
            let color;
            const planName = plan.name.toLowerCase();
            if (planName.includes('diet')) {
                color = colors.diet;
            } else if (planName.includes('protein')) {
                color = colors.protein;
            } else if (planName.includes('royal')) {
                color = colors.royal;
            } else {
                color = colors.default[index % colors.default.length];
            }
            
            // Draw slice
            ctx.beginPath();
            ctx.moveTo(centerX, centerY);
            ctx.arc(centerX, centerY, radius, currentAngle, currentAngle + sliceAngle);
            ctx.closePath();
            ctx.fillStyle = color;
            ctx.fill();
            
            // Draw border
            ctx.strokeStyle = '#ffffff';
            ctx.lineWidth = 2;
            ctx.stroke();
            
            currentAngle += sliceAngle;
        });
        
        // Draw center circle for donut effect (optional)
        ctx.beginPath();
        ctx.arc(centerX, centerY, 30, 0, 2 * Math.PI);
        ctx.fillStyle = '#ffffff';
        ctx.fill();
        ctx.strokeStyle = '#ddd';
        ctx.lineWidth = 1;
        ctx.stroke();
    }
});
</script>
@endsection