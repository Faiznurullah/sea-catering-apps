@extends('layouts.template')
@section('title', 'CateringApp')
@section('css')
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<link rel="stylesheet" href="{{ asset('css/contact.css') }}">
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection
@section('content')

  <!-- Page Header -->
  <section class="page-header">
    <div class="container">
      <h1>Contact Us</h1>
      <p>We'd love to hear from you! Get in touch with our team.</p>
    </div>
  </section>

  <!-- Contact Section -->
  <section class="contact-section">
    <div class="container">
      <div class="contact-container">
        <div class="contact-info">
          <h2>Get In Touch</h2>
          <p>Have questions about our meal plans, delivery areas, or anything else? Our team is here to help!</p>
          
          <div class="info-item">
            <div class="info-icon">📞</div>
            <div class="info-content">
              <h3>Phone</h3>
              <p>08123456789</p>
              <p class="info-note">Monday to Friday, 8am to 8pm</p>
            </div>
          </div>
          
          <div class="info-item">
            <div class="info-icon">✉️</div>
            <div class="info-content">
              <h3>Email</h3>
              <p>info@seacatering.com</p>
              <p class="info-note">We'll respond within 24 hours</p>
            </div>
          </div>
          
          <div class="info-item">
            <div class="info-icon">🏢</div>
            <div class="info-content">
              <h3>Office</h3>
              <p>Jl. Sudirman No. 123</p>
              <p>Jakarta Pusat, 10220</p>
            </div>
          </div>
          
          <div class="social-links">
            <h3>Follow Us</h3>
            <div class="social-icons">
              <a href="#" class="social-icon">FB</a>
              <a href="#" class="social-icon">IG</a>
              <a href="#" class="social-icon">TW</a>
              <a href="#" class="social-icon">YT</a>
            </div>
          </div>
        </div>        <div class="contact-form-container">
          <h2>Send Us a Message</h2>
          
          @if(session('success'))
            <div class="alert alert-success">
              {{ session('success') }}
            </div>
          @endif
          
          @if(session('error'))
            <div class="alert alert-danger">
              {{ session('error') }}
            </div>
          @endif
          
          <form id="contact-form" class="contact-form" method="POST" action="{{ route('contact.store') }}">
            @csrf
            <div class="form-group">
              <label for="name">Your Name</label>
              <input type="text" id="name" name="name" value="{{ old('name') }}" required>
              @error('name')
                <div class="error-message">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="form-group">
              <label for="email">Email Address</label>
              <input type="email" id="email" name="email" value="{{ old('email') }}" required>
              @error('email')
                <div class="error-message">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="form-group">
              <label for="phone">Phone Number</label>
              <input type="tel" id="phone" name="phone" value="{{ old('phone') }}">
              @error('phone')
                <div class="error-message">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="form-group">
              <label for="subject">Subject</label>
              <select id="subject" name="subject" required>
                <option value="">Select a subject</option>
                <option value="subscription" {{ old('subject') == 'subscription' ? 'selected' : '' }}>Subscription Inquiry</option>
                <option value="delivery" {{ old('subject') == 'delivery' ? 'selected' : '' }}>Delivery Question</option>
                <option value="menu" {{ old('subject') == 'menu' ? 'selected' : '' }}>Menu Information</option>
                <option value="feedback" {{ old('subject') == 'feedback' ? 'selected' : '' }}>Feedback</option>
                <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>Other</option>
              </select>
              @error('subject')
                <div class="error-message">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="form-group">
              <label for="message">Your Message</label>
              <textarea id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
              @error('message')
                <div class="error-message">{{ $message }}</div>
              @enderror
            </div>
            
            <button type="submit" class="btn-primary">Send Message</button>
          </form>
        </div>
      </div>
    </div>
  </section> 

@endsection
@section('javascript')
<script src="{{ asset('js/scripts.js') }}"></script>
<script src="{{ asset('js/contact.js') }}"></script>
@endsection 