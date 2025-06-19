@extends('layouts.template')
@section('title', 'CateringApp')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection
@section('content')
 
  <section class="auth-section">
    <div class="container">
      <div class="verify-container">
        <div class="verify-card">
          <div class="card-header">
            <h2>Verify Your Email Address</h2>
          </div>
          
          <div class="card-body">
            <!-- Success message (hidden by default) -->
            <div class="alert alert-success" id="resent-message" style="display: none;">
              A fresh verification link has been sent to your email address.
            </div>
            
            <div class="verify-content">
              <div class="verify-icon">📧</div>
              <p class="verify-text">
                Before proceeding, please check your email for a verification link.
              </p>
              <p class="verify-subtext">
                If you did not receive the email, 
                 <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                <button type="button" class="btn-link" id="resend-verification">click here to request another</button>.
                 </form>
              </p>
              
              <div class="verify-actions">
                <a href="/login" class="btn-secondary">Back to Login</a>
                <a href="/dashboard" class="btn-primary">Continue to Dashboard</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


@endsection
@section('javascript')
<script src="{{ asset('js/scripts.js') }}"></script>
<script src="{{ asset('js/verify-email.js') }}"></script>
@endsection 