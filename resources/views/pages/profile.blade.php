@extends('layouts.template')
@section('title', 'Profile - CateringApp')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .alert {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 5px;
            z-index: 9999;
            max-width: 300px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            animation: slideIn 0.3s ease-out;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .form-group input[readonly] {
            background-color: #f8f9fa;
            cursor: not-allowed;
        }
        
        .form-group input:not([readonly]) {
            background-color: #fff;
        }
        
        .profile-role {
            color: #6c757d;
            font-weight: 500;
            margin: 2px 0;
        }
        
        .profile-points {
            color: #007bff;
            font-weight: 600;
            margin: 2px 0;
        }
        
        .status-badge.verified {
            background: #28a745;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
        }
        
        .status-badge.unverified {
            background: #ffc107;
            color: #212529;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
        }
        
        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }
          .btn-primary, .btn-secondary, .btn-warning, .btn-danger {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .btn-warning {
            background-color: #ffc107;
            color: #212529;
        }
        
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #0056b3;
        }
        
        .btn-secondary:hover {
            background-color: #545b62;
        }
        
        .btn-primary:disabled,
        .btn-secondary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        
        .modal.show {
            display: block;
        }
        
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            position: relative;
        }
        
        .close-modal {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 10px;
        }
        
        .close-modal:hover {
            color: #000;
        }
    </style>
@endsection
@section('content')

<!-- Profile Section -->
  <section class="profile-section">
    <div class="container">
      <div class="profile-container">
        <!-- Profile Header -->
        <div class="profile-header">
          <div class="profile-avatar-container">
            <div class="profile-avatar">
              @if($user->foto)
                <img src="{{ asset('storage/' . $user->foto) }}" alt="Profile Photo" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
              @else
                {{ strtoupper(substr($user->name, 0, 2)) }}
              @endif
            </div>
            <button class="change-avatar-btn" id="change-avatar-btn">📷</button>
            <input type="file" id="avatar-upload" accept="image/*" style="display: none;">
          </div>          <div class="profile-info">
            <h1>{{ $user->name }}</h1>
            <p class="profile-email">{{ $user->email }}</p>
            <p class="profile-role">{{ ucfirst($user->role) }}</p>
            @if($user->point > 0)
            <p class="profile-points">Points: {{ number_format($user->point) }}</p>
            @endif
            <div class="verification-status">
              <span class="status-badge {{ $user->email_verified_at ? 'verified' : 'unverified' }}" id="verification-badge">
                @if($user->email_verified_at)
                  ✓ Verified Account
                @else
                  ⚠ Unverified Account
                @endif
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
              <form id="account-form" class="profile-form" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <label for="name">Full Name</label>
                  <input type="text" id="name" name="name" value="{{ $user->name }}" readonly>
                </div>
                
                <div class="form-group">
                  <label for="email">Email Address</label>
                  <input type="email" id="email" name="email" value="{{ $user->email }}" readonly>
                </div>
                
                <div class="form-group">
                  <label for="phone">Phone Number</label>
                  <input type="tel" id="phone" name="phone" value="{{ $user->phone ?? '' }}" readonly>
                </div>
                
                <div class="form-group">
                  <label for="city">City</label>
                  <input type="text" id="city" name="city" value="{{ $user->city ?? '' }}" readonly>
                </div>
                
                <div class="form-group">
                  <label for="national">Nationality</label>
                  <input type="text" id="national" name="national" value="{{ $user->national ?? '' }}" readonly>
                </div>
                
                <div class="form-group" id="foto-group" style="display: none;">
                  <label for="foto">Profile Photo</label>
                  <input type="file" id="foto" name="foto" accept="image/*">
                  <small>Choose a new profile photo (optional)</small>
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
            <div class="card-content">              <!-- Email Verification -->
              <div class="security-item">
                <div class="security-info">
                  <h3>Email Verification</h3>
                  @if($user->email_verified_at)
                    <p>Your email address is verified and secure</p>
                  @else
                    <p>Your email address is not verified. Please verify to access all features.</p>
                  @endif
                </div>
                <div class="security-status">
                  @if($user->email_verified_at)
                    <span class="status-badge verified">Verified</span>
                  @else
                    <form method="POST" action="{{ route('verification.resend') }}" style="display: inline;">
                      @csrf
                      <button type="submit" class="btn-warning">Resend Verification</button>
                    </form>
                  @endif
                </div>
              </div>

              <!-- Password -->
              <div class="security-item">
                <div class="security-info">
                  <h3>Password</h3>
                  <p>Last changed 3 months ago</p>
                </div>
                <div class="security-actions">
                  <a href="/password/reset" class="btn-secondary">Change Password</a>
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
                
                
                <div class="form-actions">
                  <button type="submit" class="btn-primary">Save Preferences</button>
                </div>
              </form>
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
        @csrf
        <div class="form-group">
          <label for="current_password">Current Password</label>
          <div class="password-input">
            <input type="password" id="current_password" name="current_password" required>
            <button type="button" class="toggle-password">👁️</button>
          </div>
        </div>
        
        <div class="form-group">
          <label for="new_password">New Password</label>
          <div class="password-input">
            <input type="password" id="new_password" name="new_password" required>
            <button type="button" class="toggle-password">👁️</button>
          </div>
          <small class="password-requirements">Password must be at least 8 characters</small>
        </div>
        
        <div class="form-group">
          <label for="new_password_confirmation">Confirm New Password</label>
          <div class="password-input">
            <input type="password" id="new_password_confirmation" name="new_password_confirmation" required>
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