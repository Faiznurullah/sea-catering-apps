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
              <h3>John Doe</h3>
              <p>john.doe@example.com</p>
            </div>
          </div>  
          
          <ul class="sidebar-menu">
            <li class="active"><a href="#subscriptions">My Subscriptions</a></li>
            <li><a href="#delivery-schedule">Delivery Schedule</a></li>
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
              <h2>Active Subscriptions</h2>
            </div>
            <div class="card-content">
              <div class="subscription-item">
                <div class="subscription-details">
                  <h3>Protein Plan</h3>
                  <p><strong>Meal Types:</strong> Breakfast, Dinner</p>
                  <p><strong>Delivery Days:</strong> Monday, Wednesday, Friday</p>
                  <p><strong>Monthly Total:</strong> Rp1.032.000</p>
                  <p><strong>Status:</strong> <span class="status active">Active</span></p>
                </div>
                <div class="subscription-actions">
                  <button class="btn-secondary pause-btn">Pause Subscription</button>
                  <button class="btn-danger cancel-btn">Cancel Subscription</button>
                </div>
              </div>
              
              <div class="subscription-item">
                <div class="subscription-details">
                  <h3>Diet Plan</h3>
                  <p><strong>Meal Types:</strong> Lunch</p>
                  <p><strong>Delivery Days:</strong> Monday to Friday</p>
                  <p><strong>Monthly Total:</strong> Rp645.000</p>
                  <p><strong>Status:</strong> <span class="status paused">Paused until 15 Jul 2025</span></p>
                </div>
                <div class="subscription-actions">
                  <button class="btn-secondary resume-btn">Resume Subscription</button>
                  <button class="btn-danger cancel-btn">Cancel Subscription</button>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Delivery Schedule -->
          <div class="dashboard-card" id="delivery-schedule">
            <div class="card-header">
              <h2>Upcoming Deliveries</h2>
            </div>
            <div class="card-content">
              <div class="calendar">
                <div class="calendar-header">
                  <button class="calendar-nav prev">◀</button>
                  <h3>July 2025</h3>
                  <button class="calendar-nav next">▶</button>
                </div>
                <div class="calendar-grid">
                  <div class="calendar-day-header">Mon</div>
                  <div class="calendar-day-header">Tue</div>
                  <div class="calendar-day-header">Wed</div>
                  <div class="calendar-day-header">Thu</div>
                  <div class="calendar-day-header">Fri</div>
                  <div class="calendar-day-header">Sat</div>
                  <div class="calendar-day-header">Sun</div>
                  
                  <!-- Calendar days would be dynamically generated in a real app -->
                  <div class="calendar-day">1</div>
                  <div class="calendar-day">2</div>
                  <div class="calendar-day">3</div>
                  <div class="calendar-day">4</div>
                  <div class="calendar-day">5</div>
                  <div class="calendar-day">6</div>
                  <div class="calendar-day">7</div>
                  
                  <div class="calendar-day">8</div>
                  <div class="calendar-day">9</div>
                  <div class="calendar-day">10</div>
                  <div class="calendar-day has-delivery" data-tooltip="Protein Plan: Breakfast, Dinner">11</div>
                  <div class="calendar-day">12</div>
                  <div class="calendar-day has-delivery" data-tooltip="Protein Plan: Breakfast, Dinner">13</div>
                  <div class="calendar-day">14</div>
                  
                  <div class="calendar-day has-delivery" data-tooltip="Protein Plan: Breakfast, Dinner">15</div>
                  <div class="calendar-day">16</div>
                  <div class="calendar-day has-delivery" data-tooltip="Protein Plan: Breakfast, Dinner">17</div>
                  <div class="calendar-day">18</div>
                  <div class="calendar-day has-delivery" data-tooltip="Protein Plan: Breakfast, Dinner">19</div>
                  <div class="calendar-day">20</div>
                  <div class="calendar-day">21</div>
                  
                  <div class="calendar-day has-delivery" data-tooltip="Protein Plan: Breakfast, Dinner">22</div>
                  <div class="calendar-day">23</div>
                  <div class="calendar-day has-delivery" data-tooltip="Protein Plan: Breakfast, Dinner">24</div>
                  <div class="calendar-day">25</div>
                  <div class="calendar-day has-delivery" data-tooltip="Protein Plan: Breakfast, Dinner">26</div>
                  <div class="calendar-day">27</div>
                  <div class="calendar-day">28</div>
                  
                  <div class="calendar-day has-delivery" data-tooltip="Protein Plan: Breakfast, Dinner">29</div>
                  <div class="calendar-day">30</div>
                  <div class="calendar-day has-delivery" data-tooltip="Protein Plan: Breakfast, Dinner">31</div>
                  <div class="calendar-day next-month">1</div>
                  <div class="calendar-day next-month">2</div>
                  <div class="calendar-day next-month">3</div>
                  <div class="calendar-day next-month">4</div>
                </div>
              </div>
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

  <!-- Cancel Subscription Modal -->
  <div class="modal" id="cancel-subscription-modal">
    <div class="modal-content">
      <span class="close-modal">&times;</span>
      <h2>Cancel Subscription</h2>
      <p>Are you sure you want to cancel your subscription? This action cannot be undone.</p>
      <form id="cancel-form">
        <div class="form-group">
          <label for="cancel-reason">Reason for Cancellation (Optional)</label>
          <select id="cancel-reason" name="cancel-reason">
            <option value="">Select a reason</option>
            <option value="too-expensive">Too expensive</option>
            <option value="quality-issues">Quality issues</option>
            <option value="delivery-issues">Delivery issues</option>
            <option value="dietary-change">Change in dietary needs</option>
            <option value="other">Other</option>
          </select>
        </div>
        <div class="form-group" id="other-reason-group" style="display: none;">
          <label for="other-reason">Please specify</label>
          <textarea id="other-reason" name="other-reason" rows="3"></textarea>
        </div>
        <div class="modal-actions">
          <button type="button" class="btn-secondary" id="cancel-cancellation">No, Keep Subscription</button>
          <button type="submit" class="btn-danger">Yes, Cancel Subscription</button>
        </div>
      </form>
    </div>
  </div>

 
@endsection
@section('javascript')
    <script src="{{ asset('js/scripts.js') }}"></script> 
    <script src="{{ asset('js/dashboard.js') }}"></script> 
@endsection