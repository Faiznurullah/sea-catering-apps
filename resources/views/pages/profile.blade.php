@extends('layouts.template')
@section('title', 'CateringApp')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection
@section('content')

<!-- Profile Section -->
  <section class="profile-section">
    <div class="container">
      <div class="profile-container">
        <!-- Profile Header -->
        <div class="profile-header">
          <div class="profile-avatar-container">
            <div class="profile-avatar">JD</div>
            <button class="change-avatar-btn" id="change-avatar-btn">📷</button>
            <input type="file" id="avatar-upload" accept="image/*" style="display: none;">
          </div>
          <div class="profile-info">
            <h1>John Doe</h1>
            <p class="profile-email">john.doe@example.com</p>
            <div class="verification-status">
              <span class="status-badge verified" id="verification-badge">
                ✓ Verified Account
              </span>
            </div>
          </div>
        </div>

        <!-- Profile Content -->
        <div class="profile-content">
          <!-- Account Information -->
          <div class="profile-card">
            <div class="card-header">
              <h2>Account Information</h2>
              <button class="btn-secondary edit-btn" id="edit-account-btn">Edit</button>
            </div>
            <div class="card-content">
              <form id="account-form" class="profile-form">
                <div class="form-row">
                  <div class="form-group">
                    <label for="first-name">First Name</label>
                    <input type="text" id="first-name" name="first-name" value="John" readonly>
                  </div>
                  <div class="form-group">
                    <label for="last-name">Last Name</label>
                    <input type="text" id="last-name" name="last-name" value="Doe" readonly>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="email">Email Address</label>
                  <input type="email" id="email" name="email" value="john.doe@example.com" readonly>
                </div>
                
                <div class="form-group">
                  <label for="phone">Phone Number</label>
                  <input type="tel" id="phone" name="phone" value="08123456789" readonly>
                </div>
                
                <div class="form-group">
                  <label for="address">Address</label>
                  <textarea id="address" name="address" rows="3" readonly>Jl. Sudirman No. 123, Jakarta Pusat, 10220</textarea>
                </div>
                
                <div class="form-group">
                  <label for="birth-date">Date of Birth</label>
                  <input type="date" id="birth-date" name="birth-date" value="1990-01-15" readonly>
                </div>
                
                <div class="form-actions" id="account-actions" style="display: none;">
                  <button type="button" class="btn-secondary" id="cancel-account-btn">Cancel</button>
                  <button type="submit" class="btn-primary">Save Changes</button>
                </div>
              </form>
            </div>
          </div>

          <!-- Security Settings -->
          <div class="profile-card">
            <div class="card-header">
              <h2>Security Settings</h2>
            </div>
            <div class="card-content">
              <!-- Email Verification -->
              <div class="security-item">
                <div class="security-info">
                  <h3>Email Verification</h3>
                  <p>Your email address is verified and secure</p>
                </div>
                <div class="security-status">
                  <span class="status-badge verified">Verified</span>
                </div>
              </div>

              <!-- Password -->
              <div class="security-item">
                <div class="security-info">
                  <h3>Password</h3>
                  <p>Last changed 3 months ago</p>
                </div>
                <div class="security-actions">
                  <button class="btn-secondary" id="change-password-btn">Change Password</button>
                </div>
              </div>

              <!-- Two-Factor Authentication -->
              <div class="security-item">
                <div class="security-info">
                  <h3>Two-Factor Authentication</h3>
                  <p>Add an extra layer of security to your account</p>
                </div>
                <div class="security-actions">
                  <button class="btn-secondary" id="enable-2fa-btn">Enable 2FA</button>
                </div>
              </div>

              <!-- Login Sessions -->
              <div class="security-item">
                <div class="security-info">
                  <h3>Active Sessions</h3>
                  <p>Manage your active login sessions</p>
                </div>
                <div class="security-actions">
                  <button class="btn-secondary" id="manage-sessions-btn">Manage Sessions</button>
                </div>
              </div>
            </div>
          </div>

          <!-- Preferences -->
          <div class="profile-card">
            <div class="card-header">
              <h2>Preferences</h2>
            </div>
            <div class="card-content">
              <form id="preferences-form" class="profile-form">
                <div class="form-group">
                  <label for="language">Language</label>
                  <select id="language" name="language">
                    <option value="en" selected>English</option>
                    <option value="id">Bahasa Indonesia</option>
                  </select>
                </div>
                
                <div class="form-group">
                  <label for="timezone">Timezone</label>
                  <select id="timezone" name="timezone">
                    <option value="Asia/Jakarta" selected>Asia/Jakarta (WIB)</option>
                    <option value="Asia/Makassar">Asia/Makassar (WITA)</option>
                    <option value="Asia/Jayapura">Asia/Jayapura (WIT)</option>
                  </select>
                </div>
                
                <div class="form-group">
                  <h3>Email Notifications</h3>
                  <div class="checkbox-group">
                    <div class="checkbox-item">
                      <input type="checkbox" id="email-orders" name="email-orders" checked>
                      <label for="email-orders">Order updates and delivery notifications</label>
                    </div>
                    <div class="checkbox-item">
                      <input type="checkbox" id="email-promotions" name="email-promotions" checked>
                      <label for="email-promotions">Promotions and special offers</label>
                    </div>
                    <div class="checkbox-item">
                      <input type="checkbox" id="email-newsletter" name="email-newsletter">
                      <label for="email-newsletter">Weekly newsletter</label>
                    </div>
                  </div>
                </div>
                
                <div class="form-group">
                  <h3>SMS Notifications</h3>
                  <div class="checkbox-group">
                    <div class="checkbox-item">
                      <input type="checkbox" id="sms-delivery" name="sms-delivery" checked>
                      <label for="sms-delivery">Delivery notifications</label>
                    </div>
                    <div class="checkbox-item">
                      <input type="checkbox" id="sms-reminders" name="sms-reminders">
                      <label for="sms-reminders">Payment reminders</label>
                    </div>
                  </div>
                </div>
                
                <div class="form-actions">
                  <button type="submit" class="btn-primary">Save Preferences</button>
                </div>
              </form>
            </div>
          </div>

          <!-- Account Actions -->
          <div class="profile-card danger-zone">
            <div class="card-header">
              <h2>Danger Zone</h2>
            </div>
            <div class="card-content">
              <div class="danger-item">
                <div class="danger-info">
                  <h3>Deactivate Account</h3>
                  <p>Temporarily disable your account. You can reactivate it anytime.</p>
                </div>
                <div class="danger-actions">
                  <button class="btn-warning" id="deactivate-account-btn">Deactivate</button>
                </div>
              </div>
              
              <div class="danger-item">
                <div class="danger-info">
                  <h3>Delete Account</h3>
                  <p>Permanently delete your account and all associated data. This action cannot be undone.</p>
                </div>
                <div class="danger-actions">
                  <button class="btn-danger" id="delete-account-btn">Delete Account</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Change Password Modal -->
  <div class="modal" id="change-password-modal">
    <div class="modal-content">
      <span class="close-modal">&times;</span>
      <h2>Change Password</h2>
      <form id="change-password-form">
        <div class="form-group">
          <label for="current-password">Current Password</label>
          <div class="password-input">
            <input type="password" id="current-password" name="current-password" required>
            <button type="button" class="toggle-password">👁️</button>
          </div>
        </div>
        
        <div class="form-group">
          <label for="new-password">New Password</label>
          <div class="password-input">
            <input type="password" id="new-password" name="new-password" required>
            <button type="button" class="toggle-password">👁️</button>
          </div>
          <small class="password-requirements">Password must be at least 8 characters and include uppercase, lowercase, number, and special character</small>
        </div>
        
        <div class="form-group">
          <label for="confirm-new-password">Confirm New Password</label>
          <div class="password-input">
            <input type="password" id="confirm-new-password" name="confirm-new-password" required>
            <button type="button" class="toggle-password">👁️</button>
          </div>
        </div>
        
        <div class="modal-actions">
          <button type="button" class="btn-secondary" id="cancel-password-change">Cancel</button>
          <button type="submit" class="btn-primary">Change Password</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Verification Modal -->
  <div class="modal" id="verification-modal">
    <div class="modal-content">
      <span class="close-modal">&times;</span>
      <h2>Verify Your Email</h2>
      <div class="verification-content">
        <div class="verification-icon">📧</div>
        <p>We've sent a verification link to your email address. Please check your inbox and click the link to verify your account.</p>
        <div class="modal-actions">
          <button type="button" class="btn-secondary" id="resend-verification">Resend Email</button>
          <button type="button" class="btn-primary" id="close-verification">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Delete Account Confirmation Modal -->
  <div class="modal" id="delete-account-modal">
    <div class="modal-content">
      <span class="close-modal">&times;</span>
      <h2>Delete Account</h2>
      <div class="delete-warning">
        <div class="warning-icon">⚠️</div>
        <p><strong>This action cannot be undone.</strong></p>
        <p>Deleting your account will permanently remove:</p>
        <ul>
          <li>Your profile and account information</li>
          <li>All subscription history</li>
          <li>Saved preferences and settings</li>
          <li>Any remaining account balance</li>
        </ul>
      </div>
      <form id="delete-account-form">
        <div class="form-group">
          <label for="delete-confirmation">Type "DELETE" to confirm</label>
          <input type="text" id="delete-confirmation" name="delete-confirmation" required>
        </div>
        <div class="form-group">
          <label for="delete-password">Enter your password</label>
          <div class="password-input">
            <input type="password" id="delete-password" name="delete-password" required>
            <button type="button" class="toggle-password">👁️</button>
          </div>
        </div>
        <div class="modal-actions">
          <button type="button" class="btn-secondary" id="cancel-delete">Cancel</button>
          <button type="submit" class="btn-danger">Delete My Account</button>
        </div>
      </form>
    </div>
  </div>

   
@endsection
@section('javascript')
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/profile.js') }}"></script>
@endsection