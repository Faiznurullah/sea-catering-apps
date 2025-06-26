@extends('layouts.template')
@section('title', 'CateringApp')
@section('css')
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection
@section('content')

  <!-- Hero Section -->
  <section class="hero">
    <div class="container">
      <div class="hero-content">
        <h1>SEA Catering</h1>
        <p class="slogan">Healthy Meals, Anytime, Anywhere</p>
        <p class="hero-text">Customizable healthy meal plans delivered across Indonesia</p>
        <a href="/subscription" class="btn-primary">Start Your Plan</a>
      </div>
    </div>
  </section>

  <!-- About Section -->
  <section class="about">
    <div class="container">
      <h2>Welcome to SEA Catering</h2>
      <div class="about-content">
        <div class="about-text">
          <p>SEA Catering is Indonesia's premier healthy meal delivery service, offering customizable meal plans that cater to your unique dietary needs and preferences.</p>
          <p>What started as a small business has quickly grown into a nationwide sensation, delivering nutritious and delicious meals to doorsteps across Indonesia.</p>
          <p>Our mission is simple: make healthy eating accessible, convenient, and enjoyable for everyone.</p>
        </div>
        <div class="about-image">
          <img src="{{ asset('/img/sea.png')}}" alt="SEA Catering meals">
        </div>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section class="features">
    <div class="container">
      <h2>Why Choose SEA Catering?</h2>
      <div class="features-grid">
        <div class="feature-card">
          <div class="feature-icon">🥗</div>
          <h3>Customizable Meals</h3>
          <p>Tailor your meals to match your dietary preferences, allergies, and nutritional goals.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">🚚</div>
          <h3>Nationwide Delivery</h3>
          <p>We deliver to major cities across Indonesia, bringing healthy meals right to your doorstep.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">📊</div>
          <h3>Nutritional Information</h3>
          <p>Detailed nutritional breakdown for each meal to help you track your health goals.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">🔄</div>
          <h3>Flexible Subscriptions</h3>
          <p>Choose the frequency, days, and meal types that work best for your lifestyle.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Plans Preview Section -->
  <section class="plans-preview">
    <div class="container">
      <h2>Our Meal Plans</h2>
      <div class="plans-grid">
        <div class="plan-card">
          <h3>Diet Plan</h3>
          <p class="price">Rp30.000 per meal</p>
          <p>Calorie-controlled meals designed to support weight management goals.</p>
          <a href="menu.html" class="btn-secondary">Learn More</a>
        </div>
        <div class="plan-card featured">
          <div class="featured-tag">Most Popular</div>
          <h3>Protein Plan</h3>
          <p class="price">Rp40.000 per meal</p>
          <p>High-protein meals perfect for active lifestyles and muscle recovery.</p>
          <a href="menu.html" class="btn-secondary">Learn More</a>
        </div>
        <div class="plan-card">
          <h3>Royal Plan</h3>
          <p class="price">Rp60.000 per meal</p>
          <p>Premium gourmet meals with exotic ingredients and chef-inspired recipes.</p>
          <a href="menu.html" class="btn-secondary">Learn More</a>
        </div>
      </div>
    </div>
  </section>
  <!-- Testimonials Preview -->
  <section class="testimonials">
    <div class="container">
      <h2>What Our Customers Say</h2>
      <div class="testimonial-slider swiper">
        <div class="swiper-wrapper">
          @forelse($testimonials as $testimonial)
            <div class="testimonial-card swiper-slide">
              <div class="rating">
                @for($i = 1; $i <= 5; $i++)
                  @if($i <= $testimonial->star)
                    ★
                  @else
                    ☆
                  @endif
                @endfor
              </div>
              <p class="testimonial-text">"{{ $testimonial->review }}"</p>
              <p class="customer-name">- {{ $testimonial->name }}</p>
            </div>
          @empty
            <!-- Fallback testimonials jika tidak ada data -->
            <div class="testimonial-card swiper-slide">
              <div class="rating">★★★★★</div>
              <p class="testimonial-text">"SEA Catering has transformed my busy workweeks. Healthy, delicious meals without the hassle of cooking!"</p>
              <p class="customer-name">- Anita S.</p>
            </div>
            <div class="testimonial-card swiper-slide">
              <div class="rating">★★★★★</div>
              <p class="testimonial-text">"The variety of meals keeps things interesting, and the nutritional information helps me stay on track with my fitness goals."</p>
              <p class="customer-name">- Budi W.</p>
            </div>
            <div class="testimonial-card swiper-slide">
              <div class="rating">★★★★☆</div>
              <p class="testimonial-text">"Great service and the meals are always fresh. I appreciate the flexibility to change my subscription when needed."</p>
              <p class="customer-name">- Diana P.</p>
            </div>
          @endforelse
        </div>
        <!-- Swiper pagination (dots) -->
        <div class="swiper-pagination testimonial-dots"></div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="cta">
    <div class="container">
      <h2>Ready to Start Your Healthy Meal Journey?</h2>
      <p>Join thousands of satisfied customers across Indonesia and experience the convenience of healthy, delicious meals delivered to your door.</p>
      <a href="/subscription" class="btn-primary">Subscribe Now</a>
    </div>
  </section>

@endsection
@section('javascript')
<script src="{{ asset('js/script.js') }}"></script>
<script>
// Initialize Swiper for testimonials
document.addEventListener("DOMContentLoaded", function() {
  const testimonialSwiper = new Swiper('.testimonial-slider', {
    // Optional parameters
    direction: 'horizontal',
    loop: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    effect: 'fade',
    fadeEffect: {
      crossFade: true
    },
    // Pagination
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
      renderBullet: function (index, className) {
        return '<span class="' + className + ' dot"></span>';
      },
    },
    // Navigation arrows (optional)
    // navigation: {
    //   nextEl: '.swiper-button-next',
    //   prevEl: '.swiper-button-prev',
    // },
  });
});
</script>
@endsection