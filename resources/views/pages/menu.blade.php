@extends('layouts.template')
@section('title', 'CateringApp')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection
@section('content')

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Our Meal Plans</h1>
            <p>Explore our customizable meal plans designed to meet your nutritional needs</p>
        </div>
    </section>

    <!-- Meal Plans Section -->
    <section class="meal-plans">
        <div class="container">
            <div class="plan-tabs">
                <button class="plan-tab active" data-plan="diet">Diet Plan</button>
                <button class="plan-tab" data-plan="protein">Protein Plan</button>
                <button class="plan-tab" data-plan="royal">Royal Plan</button>
            </div>

            <div class="plan-content active" id="diet-plan">
                <div class="plan-header">
                    <h2>Diet Plan</h2>
                    <p class="price">Rp30.000 per meal</p>
                </div>
                <div class="plan-description">
                    <p>Our Diet Plan is designed for those looking to manage their weight while enjoying delicious,
                        satisfying meals. Each meal is carefully portioned and nutritionally balanced to support your weight
                        management goals.</p>
                    <ul class="plan-features">
                        <li>Calorie-controlled portions (400-600 calories per meal)</li>
                        <li>Balanced macronutrients for optimal nutrition</li>
                        <li>Low in refined carbohydrates and added sugars</li>
                        <li>High in fiber to keep you feeling full longer</li>
                        <li>Variety of flavors to prevent diet fatigue</li>
                    </ul>
                    <button class="btn-primary see-details" data-plan="diet">See More Details</button>
                </div>
                <div class="meal-grid">
                    <div class="meal-card">
                        <div class="meal-image">
                            <img src="{{ asset('/img/fry-chicken.webp') }}" alt="Grilled Chicken Salad">
                        </div>
                        <h3>Grilled Chicken Salad</h3>
                        <p>Fresh mixed greens with grilled chicken breast, cherry tomatoes, cucumber, and light vinaigrette.
                        </p>
                        <p class="meal-calories">450 calories</p>
                    </div>
                    <div class="meal-card">
                        <div class="meal-image">
                            <img src="{{ asset('/img/fresh-vegetable.webp') }}" alt="Vegetable Stir Fry">
                        </div>
                        <h3>Vegetable Stir Fry</h3>
                        <p>Colorful vegetables stir-fried with tofu in a light soy-ginger sauce, served with brown rice.</p>
                        <p class="meal-calories">520 calories</p>
                    </div>
                    <div class="meal-card">
                        <div class="meal-image">
                            <img src="{{ asset('/img/fried-fish.webp') }}" alt="Baked Fish with Quinoa">
                        </div>
                        <h3>Baked Fish with Quinoa</h3>
                        <p>Herb-crusted white fish fillet with lemon, served with quinoa and steamed broccoli.</p>
                        <p class="meal-calories">480 calories</p>
                    </div>
                </div>
            </div>

            <div class="plan-content" id="protein-plan">
                <div class="plan-header">
                    <h2>Protein Plan</h2>
                    <p class="price">Rp40.000 per meal</p>
                </div>
                <div class="plan-description">
                    <p>Our Protein Plan is perfect for active individuals, fitness enthusiasts, and those looking to build
                        or maintain muscle mass. Each meal is rich in high-quality protein while maintaining a balanced
                        nutritional profile.</p>
                    <ul class="plan-features">
                        <li>High protein content (30-40g per meal)</li>
                        <li>Balanced with complex carbohydrates for energy</li>
                        <li>Includes healthy fats for optimal hormone production</li>
                        <li>Perfect for pre or post-workout nutrition</li>
                        <li>Supports muscle recovery and growth</li>
                    </ul>
                    <button class="btn-primary see-details" data-plan="protein">See More Details</button>
                </div>
                <div class="meal-grid">
                    <div class="meal-card">
                        <div class="meal-image">
                            <img src="{{ asset('/img/fry-meat.webp') }}" alt="Grilled Steak">
                        </div>
                        <h3>Grilled Steak</h3>
                        <p>Lean beef steak with sweet potato mash and sautéed green beans.</p>
                        <p class="meal-calories">650 calories | 35g protein</p>
                    </div>
                    <div class="meal-card">
                        <div class="meal-image">
                            <img src="{{ asset('/img/salmon-bowl.webp') }}" alt="Salmon Power Bowl">
                        </div>
                        <h3>Salmon Power Bowl</h3>
                        <p>Grilled salmon fillet with brown rice, avocado, edamame, and sesame seeds.</p>
                        <p class="meal-calories">620 calories | 32g protein</p>
                    </div>
                    <div class="meal-card">
                        <div class="meal-image">
                            <img src="{{ asset('/img/salads-quinoa.webp') }}" alt="Chicken Quinoa Bowl">
                        </div>
                        <h3>Chicken Quinoa Bowl</h3>
                        <p>Grilled chicken breast with quinoa, roasted vegetables, and tahini dressing.</p>
                        <p class="meal-calories">580 calories | 38g protein</p>
                    </div>
                </div>
            </div>

            <div class="plan-content" id="royal-plan">
                <div class="plan-header">
                    <h2>Royal Plan</h2>
                    <p class="price">Rp60.000 per meal</p>
                </div>
                <div class="plan-description">
                    <p>Our premium Royal Plan offers gourmet meals prepared with exotic ingredients and chef-inspired
                        recipes. Experience restaurant-quality dining at home with our most luxurious meal plan.</p>
                    <ul class="plan-features">
                        <li>Premium ingredients including specialty meats and seafood</li>
                        <li>Globally-inspired recipes from renowned culinary traditions</li>
                        <li>Artfully presented meals with complex flavor profiles</li>
                        <li>Includes occasional indulgent elements while maintaining nutritional balance</li>
                        <li>Perfect for special occasions or treating yourself</li>
                    </ul>
                    <button class="btn-primary see-details" data-plan="royal">See More Details</button>
                </div>
                <div class="meal-grid">
                    <div class="meal-card">
                        <div class="meal-image">
                            <img src="{{ asset('/img/seared-scallops.webp') }}" alt="Seared Scallops">
                        </div>
                        <h3>Seared Scallops</h3>
                        <p>Pan-seared scallops with saffron risotto and asparagus tips.</p>
                        <p class="meal-calories">580 calories</p>
                    </div>
                    <div class="meal-card">
                        <div class="meal-image">
                            <img src="{{ asset('/img/beef-wellington.webp') }}" alt="Beef Wellington">
                        </div>
                        <h3>Beef Wellington</h3>
                        <p>Individual beef wellington with truffle mashed potatoes and glazed carrots.</p>
                        <p class="meal-calories">720 calories</p>
                    </div>
                    <div class="meal-card">
                        <div class="meal-image">
                            <img src="{{ asset('/img/lobster-pasta.webp') }}" alt="Lobster Pasta">
                        </div>
                        <h3>Lobster Pasta</h3>
                        <p>Fresh pasta with lobster chunks in a light cream sauce with herbs.</p>
                        <p class="meal-calories">650 calories</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Plan Details Modal -->
    <div class="modal" id="plan-details-modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div id="modal-diet" class="modal-plan-content">
                <h2>Diet Plan Details</h2>
                <div class="modal-plan-info">
                    <div class="modal-section">
                        <h3>Nutritional Information</h3>
                        <ul>
                            <li>Calories: 400-600 per meal</li>
                            <li>Protein: 25-30g per meal</li>
                            <li>Carbs: 30-45g per meal (primarily complex carbs)</li>
                            <li>Fat: 15-20g per meal (primarily healthy fats)</li>
                            <li>Fiber: 8-12g per meal</li>
                        </ul>
                    </div>
                    <div class="modal-section">
                        <h3>Ideal For</h3>
                        <ul>
                            <li>Weight management goals</li>
                            <li>Those looking to improve overall health</li>
                            <li>People with sedentary to moderately active lifestyles</li>
                            <li>Those who want portion-controlled meals</li>
                        </ul>
                    </div>
                    <div class="modal-section">
                        <h3>Sample Weekly Menu</h3>
                        <p><strong>Monday:</strong> Grilled Chicken Salad</p>
                        <p><strong>Tuesday:</strong> Vegetable Stir Fry with Tofu</p>
                        <p><strong>Wednesday:</strong> Baked Fish with Quinoa</p>
                        <p><strong>Thursday:</strong> Turkey and Vegetable Wrap</p>
                        <p><strong>Friday:</strong> Lentil Soup with Side Salad</p>
                        <p><strong>Saturday:</strong> Egg White Frittata with Vegetables</p>
                        <p><strong>Sunday:</strong> Grilled Vegetable and Chicken Bowl</p>
                    </div>
                </div>
                <a href="/subscription" class="btn-primary">Subscribe to Diet Plan</a>
            </div>

            <div id="modal-protein" class="modal-plan-content">
                <h2>Protein Plan Details</h2>
                <div class="modal-plan-info">
                    <div class="modal-section">
                        <h3>Nutritional Information</h3>
                        <ul>
                            <li>Calories: 550-700 per meal</li>
                            <li>Protein: 30-40g per meal</li>
                            <li>Carbs: 40-60g per meal</li>
                            <li>Fat: 20-25g per meal</li>
                            <li>Fiber: 6-10g per meal</li>
                        </ul>
                    </div>
                    <div class="modal-section">
                        <h3>Ideal For</h3>
                        <ul>
                            <li>Active individuals and fitness enthusiasts</li>
                            <li>Those looking to build or maintain muscle mass</li>
                            <li>Athletes and regular gym-goers</li>
                            <li>People with higher caloric needs</li>
                        </ul>
                    </div>
                    <div class="modal-section">
                        <h3>Sample Weekly Menu</h3>
                        <p><strong>Monday:</strong> Grilled Steak with Sweet Potato</p>
                        <p><strong>Tuesday:</strong> Salmon Power Bowl</p>
                        <p><strong>Wednesday:</strong> Chicken Quinoa Bowl</p>
                        <p><strong>Thursday:</strong> Turkey and Black Bean Burrito Bowl</p>
                        <p><strong>Friday:</strong> Tuna Steak with Brown Rice</p>
                        <p><strong>Saturday:</strong> Protein-Packed Breakfast Bowl</p>
                        <p><strong>Sunday:</strong> Beef and Vegetable Stir Fry</p>
                    </div>
                </div>
                <a href="/subscription" class="btn-primary">Subscribe to Protein Plan</a>
            </div>

            <div id="modal-royal" class="modal-plan-content">
                <h2>Royal Plan Details</h2>
                <div class="modal-plan-info">
                    <div class="modal-section">
                        <h3>Nutritional Information</h3>
                        <ul>
                            <li>Calories: 550-750 per meal</li>
                            <li>Protein: 25-35g per meal</li>
                            <li>Carbs: 40-60g per meal</li>
                            <li>Fat: 25-35g per meal (includes some indulgent elements)</li>
                            <li>Fiber: 6-10g per meal</li>
                        </ul>
                    </div>
                    <div class="modal-section">
                        <h3>Ideal For</h3>
                        <ul>
                            <li>Food enthusiasts and gourmands</li>
                            <li>Those looking for restaurant-quality meals at home</li>
                            <li>Special occasions and celebrations</li>
                            <li>People who appreciate culinary artistry</li>
                        </ul>
                    </div>
                    <div class="modal-section">
                        <h3>Sample Weekly Menu</h3>
                        <p><strong>Monday:</strong> Seared Scallops with Saffron Risotto</p>
                        <p><strong>Tuesday:</strong> Individual Beef Wellington</p>
                        <p><strong>Wednesday:</strong> Lobster Pasta</p>
                        <p><strong>Thursday:</strong> Duck Breast with Cherry Reduction</p>
                        <p><strong>Friday:</strong> Miso-Glazed Black Cod</p>
                        <p><strong>Saturday:</strong> Rack of Lamb with Herb Crust</p>
                        <p><strong>Sunday:</strong> Truffle Mushroom Risotto</p>
                    </div>
                </div>
                <a href="/subscription" class="btn-primary">Subscribe to Royal Plan</a>
            </div>
        </div>
    </div>

    <!-- Testimonials Section -->
    <section class="testimonials">
        <div class="container">
            <h2>Customer Reviews</h2>
            <div class="testimonial-slider">
                <div class="testimonial-card active">
                    <div class="rating">★★★★★</div>
                    <p class="testimonial-text">"The Diet Plan has helped me lose 5kg in just two months! The meals are
                        delicious and I never feel like I'm on a diet."</p>
                    <p class="customer-name">- Anita S.</p>
                </div>
                <div class="testimonial-card">
                    <div class="rating">★★★★★</div>
                    <p class="testimonial-text">"As an athlete, the Protein Plan gives me exactly what I need to recover
                        after training. Great flavors and perfect portions."</p>
                    <p class="customer-name">- Budi W.</p>
                </div>
                <div class="testimonial-card">
                    <div class="rating">★★★★☆</div>
                    <p class="testimonial-text">"The Royal Plan is like having a personal chef! The meals are exquisite and
                        make every dinner feel special."</p>
                    <p class="customer-name">- Diana P.</p>
                </div>
            </div>
            <div class="testimonial-dots">
                <span class="dot active"></span>
                <span class="dot"></span>
                <span class="dot"></span>
            </div>
        </div>
    </section>

    <!-- Add Testimonial Section -->
    <section class="add-testimonial">
        <div class="container">
            <h2>Share Your Experience</h2>      
            
            <!-- Make Session Sweat Alert -->
            @if(session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('experience.store') }}" class="testimonial-form">
                @csrf 
                 
                <div class="form-group">
                    <label for="customer-name">Your Name</label>
                    <input type="text" id="customer-name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="review-message">Your Review</label>
                    <textarea id="review-message" name="review" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label>Rating</label>
                    <div class="rating-select">
                        <input type="radio" id="star5" name="rating" value="5" required>
                        <label for="star5">★</label>
                        <input type="radio" id="star4" name="rating" value="4">
                        <label for="star4">★</label>
                        <input type="radio" id="star3" name="rating" value="3">
                        <label for="star3">★</label>
                        <input type="radio" id="star2" name="rating" value="2">
                        <label for="star2">★</label>
                        <input type="radio" id="star1" name="rating" value="1">
                        <label for="star1">★</label>
                    </div>
                </div>
                <button type="submit" class="btn-primary">Submit Review</button>
            </form>

        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <h2>Ready to Start Your Healthy Meal Journey?</h2>
            <p>Choose the plan that fits your lifestyle and nutritional needs.</p>
            <a href="/subscription" class="btn-primary">Subscribe Now</a>
        </div>
    </section>

@endsection
@section('javascript')
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/menu.js') }}"></script> 

    
@endsection
