@extends('layouts.template')
@section('title', 'Terms of Service - CateringApp')
@section('css')
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<link rel="stylesheet" href="{{ asset('css/legal.css') }}">
@endsection
@section('content')

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>Terms of Service</h1>
        <p>Please read these terms and conditions carefully before using our service.</p>
    </div>
</section>

<!-- Terms of Service Content -->
<section class="legal-content">
    <div class="container">
        <div class="legal-container">
            <div class="legal-text">
                <div class="last-updated">
                    <strong>Last updated:</strong> June 27, 2025
                </div>

                <div class="section">
                    <h2>1. Acceptance of Terms</h2>
                    <p>By accessing and using the CateringApp service, you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.</p>
                </div>

                <div class="section">
                    <h2>2. Service Description</h2>
                    <p>CateringApp provides meal planning and delivery services. We offer various subscription plans including diet, protein, and royal meal plans to meet your nutritional needs.</p>
                </div>

                <div class="section">
                    <h2>3. User Accounts</h2>
                    <p>To use our services, you must:</p>
                    <ul>
                        <li>Create an account with accurate and complete information</li>
                        <li>Maintain the security of your password and account</li>
                        <li>Accept responsibility for all activities that occur under your account</li>
                        <li>Notify us immediately of any unauthorized use of your account</li>
                    </ul>
                </div>

                <div class="section">
                    <h2>4. Subscription Services</h2>
                    <p>Our subscription services include:</p>
                    <ul>
                        <li><strong>Diet Plan:</strong> Healthy meal options for weight management</li>
                        <li><strong>Protein Plan:</strong> High-protein meals for fitness enthusiasts</li>
                        <li><strong>Royal Plan:</strong> Premium gourmet meal selections</li>
                    </ul>
                    <p>Subscriptions are billed in advance and will automatically renew unless cancelled.</p>
                </div>

                <div class="section">
                    <h2>5. Payment Terms</h2>
                    <p>By subscribing to our services, you agree to:</p>
                    <ul>
                        <li>Pay all fees associated with your subscription plan</li>
                        <li>Provide accurate billing information</li>
                        <li>Update payment information as needed</li>
                        <li>Accept responsibility for all charges incurred under your account</li>
                    </ul>
                </div>

                <div class="section">
                    <h2>6. Delivery Policy</h2>
                    <p>We strive to deliver meals according to your selected delivery schedule. However:</p>
                    <ul>
                        <li>Delivery times are estimates and may vary due to traffic or weather conditions</li>
                        <li>You must provide accurate delivery address and be available to receive orders</li>
                        <li>We are not responsible for spoiled food due to delayed pickup by customers</li>
                        <li>Delivery areas are limited to our service zones</li>
                    </ul>
                </div>

                <div class="section">
                    <h2>7. Cancellation and Refunds</h2>
                    <p>You may cancel your subscription at any time. Cancellation policies:</p>
                    <ul>
                        <li>Cancellations must be made at least 24 hours before the next scheduled delivery</li>
                        <li>Refunds for unused portions of prepaid subscriptions will be processed within 7-10 business days</li>
                        <li>No refunds for consumed meals or services already rendered</li>
                        <li>Subscription pausing options are available for temporary holds</li>
                    </ul>
                </div>

                <div class="section">
                    <h2>8. Food Safety and Allergies</h2>
                    <p>Important food safety information:</p>
                    <ul>
                        <li>All meals are prepared in facilities that may process common allergens</li>
                        <li>You are responsible for informing us of any food allergies or dietary restrictions</li>
                        <li>We cannot guarantee complete allergen-free meals</li>
                        <li>Consume meals by the recommended date for optimal freshness and safety</li>
                    </ul>
                </div>

                <div class="section">
                    <h2>9. Limitation of Liability</h2>
                    <p>CateringApp's liability is limited to the fullest extent permitted by law. We are not liable for:</p>
                    <ul>
                        <li>Any indirect, incidental, or consequential damages</li>
                        <li>Loss of profits, data, or other intangible losses</li>
                        <li>Damages resulting from unauthorized access to your account</li>
                        <li>Service interruptions or technical difficulties</li>
                    </ul>
                </div>

                <div class="section">
                    <h2>10. Privacy Policy</h2>
                    <p>Your privacy is important to us. Please review our <a href="{{ route('privacy.policy') }}" class="legal-link">Privacy Policy</a>, which also governs your use of the service, to understand our practices.</p>
                </div>

                <div class="section">
                    <h2>11. Changes to Terms</h2>
                    <p>We reserve the right to modify these terms at any time. Changes will be effective immediately upon posting. Your continued use of the service constitutes acceptance of any changes.</p>
                </div>

                <div class="section">
                    <h2>12. Contact Information</h2>
                    <p>If you have any questions about these Terms of Service, please contact us:</p>
                    <ul>
                        <li><strong>Email:</strong> legal@seacatering.com</li>
                        <li><strong>Phone:</strong> 08123456789</li>
                        <li><strong>Address:</strong> Jl. Sudirman No. 123, Jakarta Pusat, 10220</li>
                    </ul>
                </div>

                <div class="section agreement-section">
                    <p><strong>By using CateringApp, you acknowledge that you have read, understood, and agree to be bound by these Terms of Service.</strong></p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
