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
        </div>
        
        <div class="contact-form-container">
          <h2>Send Us a Message</h2>
          <form id="contact-form" class="contact-form">
            <div class="form-group">
              <label for="name">Your Name</label>
              <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
              <label for="email">Email Address</label>
              <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
              <label for="phone">Phone Number</label>
              <input type="tel" id="phone" name="phone">
            </div>
            
            <div class="form-group">
              <label for="subject">Subject</label>
              <select id="subject" name="subject" required>
                <option value="">Select a subject</option>
                <option value="subscription">Subscription Inquiry</option>
                <option value="delivery">Delivery Question</option>
                <option value="menu">Menu Information</option>
                <option value="feedback">Feedback</option>
                <option value="other">Other</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="message">Your Message</label>
              <textarea id="message" name="message" rows="5" required></textarea>
            </div>
            
            <button type="submit" class="btn-primary">Send Message</button>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- Map Section -->
  <section class="map-section">
    <div class="container">
      <h2>Find Us</h2>
      <div class="map-container">
        <!-- Placeholder for map -->
        <div class="map-placeholder">
          <p>Map Placeholder</p>
          <p>In a real application, this would be an interactive map showing our location</p>
        </div>
      </div>
    </div>
  </section>

@endsection
@section('javascript')
<script src="{{ asset('js/scripts.js') }}"></script>
<script src="{{ asset('js/contact.js') }}"></script>
@endsection 