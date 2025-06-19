@extends('layouts.template')
@section('title', 'CateringApp')
@section('css')
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<link rel="stylesheet" href="{{ asset('css/subscription.css') }}">
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection
@section('content')


  <!-- Page Header -->
  <section class="page-header">
    <div class="container">
      <h1>Subscribe to SEA Catering</h1>
      <p>Customize your meal plan and enjoy healthy, delicious meals delivered to your door</p>
    </div>
  </section>

  <!-- Subscription Form Section -->
  <section class="subscription-form-section">
    <div class="container">
      <div class="form-container">
        <h2>Create Your Subscription</h2>
        <form id="subscription-form" class="subscription-form">
          <!-- Personal Information -->
          <div class="form-section">
            <h3>Personal Information</h3>
            <div class="form-group">
              <label for="full-name">Full Name <span class="required">*</span></label>
              <input type="text" id="full-name" name="full-name" required>
            </div>
            <div class="form-group">
              <label for="phone">Active Phone Number <span class="required">*</span></label>
              <input type="tel" id="phone" name="phone" placeholder="e.g., 08123456789" required>
              <small class="form-hint">We'll use this for payment and order updates</small>
            </div>
          </div>

          <!-- Plan Selection -->
          <div class="form-section">
            <h3>Plan Selection <span class="required">*</span></h3>
            <div class="plan-options">
              <div class="plan-option">
                <input type="radio" id="diet-plan" name="plan" value="diet" data-price="30000" required>
                <label for="diet-plan">
                  <div class="plan-option-content">
                    <h4>Diet Plan</h4>
                    <p class="plan-price">Rp30.000 per meal</p>
                    <p>Calorie-controlled meals for weight management</p>
                  </div>
                </label>
              </div>
              <div class="plan-option">
                <input type="radio" id="protein-plan" name="plan" value="protein" data-price="40000">
                <label for="protein-plan">
                  <div class="plan-option-content">
                    <h4>Protein Plan</h4>
                    <p class="plan-price">Rp40.000 per meal</p>
                    <p>High-protein meals for active lifestyles</p>
                  </div>
                </label>
              </div>
              <div class="plan-option">
                <input type="radio" id="royal-plan" name="plan" value="royal" data-price="60000">
                <label for="royal-plan">
                  <div class="plan-option-content">
                    <h4>Royal Plan</h4>
                    <p class="plan-price">Rp60.000 per meal</p>
                    <p>Premium gourmet meals with exotic ingredients</p>
                  </div>
                </label>
              </div>
            </div>
          </div>

          <!-- Meal Types -->
          <div class="form-section">
            <h3>Meal Types <span class="required">*</span></h3>
            <p class="section-description">Select at least one meal type</p>
            <div class="checkbox-group">
              <div class="checkbox-option">
                <input type="checkbox" id="breakfast" name="meal-type" value="breakfast" class="meal-type-checkbox">
                <label for="breakfast">Breakfast</label>
              </div>
              <div class="checkbox-option">
                <input type="checkbox" id="lunch" name="meal-type" value="lunch" class="meal-type-checkbox">
                <label for="lunch">Lunch</label>
              </div>
              <div class="checkbox-option">
                <input type="checkbox" id="dinner" name="meal-type" value="dinner" class="meal-type-checkbox">
                <label for="dinner">Dinner</label>
              </div>
            </div>
            <div id="meal-type-error" class="error-message"></div>
          </div>

          <!-- Delivery Days -->
          <div class="form-section">
            <h3>Delivery Days <span class="required">*</span></h3>
            <p class="section-description">Select the days you want your meals delivered</p>
            <div class="checkbox-group days-group">
              <div class="checkbox-option">
                <input type="checkbox" id="monday" name="delivery-day" value="monday" class="delivery-day-checkbox">
                <label for="monday">Monday</label>
              </div>
              <div class="checkbox-option">
                <input type="checkbox" id="tuesday" name="delivery-day" value="tuesday" class="delivery-day-checkbox">
                <label for="tuesday">Tuesday</label>
              </div>
              <div class="checkbox-option">
                <input type="checkbox" id="wednesday" name="delivery-day" value="wednesday" class="delivery-day-checkbox">
                <label for="wednesday">Wednesday</label>
              </div>
              <div class="checkbox-option">
                <input type="checkbox" id="thursday" name="delivery-day" value="thursday" class="delivery-day-checkbox">
                <label for="thursday">Thursday</label>
              </div>
              <div class="checkbox-option">
                <input type="checkbox" id="friday" name="delivery-day" value="friday" class="delivery-day-checkbox">
                <label for="friday">Friday</label>
              </div>
              <div class="checkbox-option">
                <input type="checkbox" id="saturday" name="delivery-day" value="saturday" class="delivery-day-checkbox">
                <label for="saturday">Saturday</label>
              </div>
              <div class="checkbox-option">
                <input type="checkbox" id="sunday" name="delivery-day" value="sunday" class="delivery-day-checkbox">
                <label for="sunday">Sunday</label>
              </div>
            </div>
            <div id="delivery-day-error" class="error-message"></div>
          </div>

          <!-- Allergies -->
          <div class="form-section">
            <h3>Allergies or Dietary Restrictions</h3>
            <div class="form-group">
              <textarea id="allergies" name="allergies" rows="3" placeholder="List any allergies or dietary restrictions here..."></textarea>
            </div>
          </div>

          <!-- Price Summary -->
          <div class="form-section price-summary">
            <h3>Subscription Summary</h3>
            <div class="summary-item">
              <span>Plan:</span>
              <span id="summary-plan">-</span>
            </div>
            <div class="summary-item">
              <span>Meal Types:</span>
              <span id="summary-meal-types">-</span>
            </div>
            <div class="summary-item">
              <span>Delivery Days:</span>
              <span id="summary-delivery-days">-</span>
            </div>
            <div class="summary-item total">
              <span>Monthly Total:</span>
              <span id="summary-total">Rp0</span>
            </div>
            <p class="price-note">Price calculated for 4.3 weeks per month</p>
          </div>

          <button type="submit" class="btn-primary submit-btn">Subscribe Now</button>
        </form>
      </div>
    </div>
  </section>

  <!-- How It Works Section -->
  <section class="how-it-works">
    <div class="container">
      <h2>How It Works</h2>
      <div class="steps-container">
        <div class="step">
          <div class="step-number">1</div>
          <h3>Choose Your Plan</h3>
          <p>Select from our Diet, Protein, or Royal meal plans based on your nutritional needs and preferences.</p>
        </div>
        <div class="step">
          <div class="step-number">2</div>
          <h3>Customize Your Subscription</h3>
          <p>Pick which meals you want (breakfast, lunch, dinner) and which days you want them delivered.</p>
        </div>
        <div class="step">
          <div class="step-number">3</div>
          <h3>Enjoy Fresh Meals</h3>
          <p>Receive your freshly prepared meals on your selected days, ready to heat and enjoy.</p>
        </div>
        <div class="step">
          <div class="step-number">4</div>
          <h3>Manage Your Subscription</h3>
          <p>Easily pause, modify, or cancel your subscription through your account dashboard.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ Section -->
  <section class="faq-section">
    <div class="container">
      <h2>Frequently Asked Questions</h2>
      <div class="faq-container">        <div class="faq-item">
          <div class="faq-question">
            <h3>How far in advance do I need to order?</h3>
            <span class="faq-toggle">+</span>
          </div>
          <div class="faq-answer">
            <p>We require orders to be placed at least 48 hours in advance to ensure we can source the freshest ingredients and prepare your meals with care.</p>
          </div>
        </div>
        <div class="faq-item">
          <div class="faq-question">
            <h3>Can I change my meal plan after subscribing?</h3>
            <span class="faq-toggle">+</span>
          </div>
          <div class="faq-answer">
            <p>Yes, you can change your meal plan, delivery days, or meal types at any time through your account dashboard. Changes will take effect in your next billing cycle.</p>
          </div>
        </div>
        <div class="faq-item">
          <div class="faq-question">
            <h3>How are the meals delivered?</h3>
            <span class="faq-toggle">+</span>
          </div>
          <div class="faq-answer">
            <p>Meals are delivered in insulated packaging to ensure freshness. Depending on your location, we use our own delivery fleet or trusted delivery partners.</p>
          </div>
        </div>
        <div class="faq-item">
          <div class="faq-question">
            <h3>Can I pause my subscription?</h3>
            <span class="faq-toggle">+</span>
          </div>
          <div class="faq-answer">
            <p>Yes, you can pause your subscription for up to 4 weeks at a time through your account dashboard. No charges will be applied during the pause period.</p>
          </div>
        </div>
        <div class="faq-item">
          <div class="faq-question">
            <h3>What cities do you deliver to?</h3>
            <span class="faq-toggle">+</span>
          </div>
          <div class="faq-answer">
            <p>We currently deliver to major cities across Indonesia, including Jakarta, Surabaya, Bandung, Medan, Semarang, Makassar, and Denpasar. We're constantly expanding our delivery areas.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Success Modal -->
  <div id="subscription-success-modal" class="modal" style="display: none;">
    <div class="modal-content">
      <span class="close-modal">&times;</span>
      <div class="success-content">
        <div class="success-icon">✓</div>
        <h2>Subscription Successful!</h2>
        <p>Thank you for subscribing to SEA Catering. Your subscription has been created successfully.</p>
        
        <div class="subscription-details">
          <h3>Your Subscription Details:</h3>
          <p><strong>Plan:</strong> <span id="modal-plan">-</span></p>
          <p><strong>Meal Types:</strong> <span id="modal-meal-types">-</span></p>
          <p><strong>Delivery Days:</strong> <span id="modal-delivery-days">-</span></p>
          <p><strong>Monthly Total:</strong> <span id="modal-total">Rp0</span></p>
        </div>
        
        <button id="close-success-modal" class="btn-primary">Continue</button>
      </div>
    </div>
  </div>


@endsection
@section('javascript')
    <script src="{{ asset('js/scripts.js') }}"></script> 
    <script src="{{ asset('js/subscription.js') }}"></script> 
@endsection