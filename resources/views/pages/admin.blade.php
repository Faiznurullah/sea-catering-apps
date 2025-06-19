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
              <h3>Admin</h3>
              <p>admin@seacatering.com</p>
            </div>
          </div>
          
          <ul class="sidebar-menu">
            <li class="active"><a href="#dashboard">Dashboard</a></li>
            <li><a href="#subscriptions">Subscriptions</a></li>
            <li><a href="#customers">Customers</a></li>
            <li><a href="#deliveries">Deliveries</a></li>
            <li><a href="#reports">Reports</a></li>
            <li><a href="#settings">Settings</a></li>
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
                <p class="stat-value">128</p>
                <p class="stat-change positive">+12% from last period</p>
              </div>
            </div>
            
            <div class="stat-card">
              <div class="stat-icon mrr-icon">💰</div>
              <div class="stat-content">
                <h3>Monthly Recurring Revenue</h3>
                <p class="stat-value">Rp 87,450,000</p>
                <p class="stat-change positive">+8% from last period</p>
              </div>
            </div>
            
            <div class="stat-card">
              <div class="stat-icon reactivations-icon">🔄</div>
              <div class="stat-content">
                <h3>Reactivations</h3>
                <p class="stat-value">24</p>
                <p class="stat-change positive">+15% from last period</p>
              </div>
            </div>
            
            <div class="stat-card">
              <div class="stat-icon active-subs-icon">👥</div>
              <div class="stat-content">
                <h3>Active Subscriptions</h3>
                <p class="stat-value">1,245</p>
                <p class="stat-change positive">+5% from last period</p>
              </div>
            </div>
          </div>
          
          <!-- Subscription Growth Chart -->
          <div class="admin-card">
            <div class="card-header">
              <h2>Subscription Growth</h2>
            </div>
            <div class="card-content">
              <div class="chart-container">
                <div class="chart-placeholder">
                  <div class="chart-bars">
                    <div class="chart-bar" style="height: 30%;" data-tooltip="Jan: 850"></div>
                    <div class="chart-bar" style="height: 40%;" data-tooltip="Feb: 920"></div>
                    <div class="chart-bar" style="height: 45%;" data-tooltip="Mar: 950"></div>
                    <div class="chart-bar" style="height: 55%;" data-tooltip="Apr: 1020"></div>
                    <div class="chart-bar" style="height: 60%;" data-tooltip="May: 1080"></div>
                    <div class="chart-bar" style="height: 70%;" data-tooltip="Jun: 1150"></div>
                    <div class="chart-bar active" style="height: 80%;" data-tooltip="Jul: 1245"></div>
                  </div>
                  <div class="chart-labels">
                    <span>Jan</span>
                    <span>Feb</span>
                    <span>Mar</span>
                    <span>Apr</span>
                    <span>May</span>
                    <span>Jun</span>
                    <span>Jul</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Recent Subscriptions -->
          <div class="admin-card" id="subscriptions">
            <div class="card-header">
              <h2>Recent Subscriptions</h2>
              <a href="#" class="view-all">View All</a>
            </div>
            <div class="card-content">
              <div class="table-responsive">
                <table class="admin-table">
                  <thead>
                    <tr>
                      <th>Customer</th>
                      <th>Plan</th>
                      <th>Meal Types</th>
                      <th>Delivery Days</th>
                      <th>Monthly Value</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>John Doe</td>
                      <td>Protein Plan</td>
                      <td>Breakfast, Dinner</td>
                      <td>Mon, Wed, Fri</td>
                      <td>Rp1,032,000</td>
                      <td><span class="status active">Active</span></td>
                      <td>
                        <div class="table-actions">
                          <button class="action-btn view-btn" title="View Details">👁️</button>
                          <button class="action-btn edit-btn" title="Edit">✏️</button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>Jane Smith</td>
                      <td>Diet Plan</td>
                      <td>Lunch</td>
                      <td>Mon-Fri</td>
                      <td>Rp645,000</td>
                      <td><span class="status paused">Paused</span></td>
                      <td>
                        <div class="table-actions">
                          <button class="action-btn view-btn" title="View Details">👁️</button>
                          <button class="action-btn edit-btn" title="Edit">✏️</button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>Robert Johnson</td>
                      <td>Royal Plan</td>
                      <td>Breakfast, Lunch, Dinner</td>
                      <td>Mon-Sun</td>
                      <td>Rp5,418,000</td>
                      <td><span class="status active">Active</span></td>
                      <td>
                        <div class="table-actions">
                          <button class="action-btn view-btn" title="View Details">👁️</button>
                          <button class="action-btn edit-btn" title="Edit">✏️</button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>Sarah Williams</td>
                      <td>Protein Plan</td>
                      <td>Breakfast, Lunch</td>
                      <td>Mon, Wed, Fri</td>
                      <td>Rp1,032,000</td>
                      <td><span class="status active">Active</span></td>
                      <td>
                        <div class="table-actions">
                          <button class="action-btn view-btn" title="View Details">👁️</button>
                          <button class="action-btn edit-btn" title="Edit">✏️</button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>Michael Brown</td>
                      <td>Diet Plan</td>
                      <td>Dinner</td>
                      <td>Mon-Fri</td>
                      <td>Rp645,000</td>
                      <td><span class="status cancelled">Cancelled</span></td>
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
          <div class="admin-card">
            <div class="card-header">
              <h2>Plan Distribution</h2>
            </div>
            <div class="card-content">
              <div class="chart-container">
                <div class="pie-chart-placeholder">
                  <div class="pie-chart">
                    <div class="pie-segment diet" style="--percentage: 35%;"></div>
                    <div class="pie-segment protein" style="--percentage: 45%;"></div>
                    <div class="pie-segment royal" style="--percentage: 20%;"></div>
                  </div>
                  <div class="pie-legend">
                    <div class="legend-item">
                      <span class="legend-color diet"></span>
                      <span>Diet Plan (35%)</span>
                    </div>
                    <div class="legend-item">
                      <span class="legend-color protein"></span>
                      <span>Protein Plan (45%)</span>
                    </div>
                    <div class="legend-item">
                      <span class="legend-color royal"></span>
                      <span>Royal Plan (20%)</span>
                    </div>
                  </div>
                </div>
              </div>
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

@endsection
@section('javascript')
<script src="{{ asset('js/scripts.js') }}"></script>
<script src="{{ asset('js/admin.js') }}"></script>
@endsection 