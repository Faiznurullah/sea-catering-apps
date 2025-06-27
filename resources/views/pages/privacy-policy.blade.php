@extends('layouts.template')
@section('title', 'Privacy Policy - CateringApp')
@section('css')
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<link rel="stylesheet" href="{{ asset('css/legal.css') }}">
@endsection
@section('content')

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>Privacy Policy</h1>
        <p>Your privacy is important to us. This policy explains how we collect, use, and protect your information.</p>
    </div>
</section>

<!-- Privacy Policy Content -->
<section class="legal-content">
    <div class="container">
        <div class="legal-container">
            <div class="legal-text">
                <div class="last-updated">
                    <strong>Last updated:</strong> June 27, 2025
                </div>

                <div class="section">
                    <h2>1. Information We Collect</h2>
                    <p>We collect information you provide directly to us, such as when you:</p>
                    <ul>
                        <li>Create an account or subscribe to our services</li>
                        <li>Place orders or make payments</li>
                        <li>Contact our customer support</li>
                        <li>Participate in surveys or promotional activities</li>
                        <li>Leave reviews or testimonials</li>
                    </ul>
                    
                    <h3>Personal Information</h3>
                    <p>This may include:</p>
                    <ul>
                        <li>Name, email address, and phone number</li>
                        <li>Delivery address and billing information</li>
                        <li>Payment method details</li>
                        <li>Dietary preferences and food allergies</li>
                        <li>Order history and preferences</li>
                    </ul>
                </div>

                <div class="section">
                    <h2>2. How We Use Your Information</h2>
                    <p>We use the information we collect to:</p>
                    <ul>
                        <li>Provide, maintain, and improve our meal delivery services</li>
                        <li>Process orders and handle payments</li>
                        <li>Send you confirmations, updates, and customer support messages</li>
                        <li>Personalize your experience and meal recommendations</li>
                        <li>Communicate about new features, offers, and promotions</li>
                        <li>Analyze usage patterns to improve our services</li>
                        <li>Ensure food safety and handle dietary requirements</li>
                        <li>Comply with legal obligations and protect our rights</li>
                    </ul>
                </div>

                <div class="section">
                    <h2>3. Information Sharing and Disclosure</h2>
                    <p>We do not sell, trade, or otherwise transfer your personal information to third parties, except in the following circumstances:</p>
                    
                    <h3>Service Providers</h3>
                    <p>We may share information with trusted third-party service providers who assist us in:</p>
                    <ul>
                        <li>Payment processing</li>
                        <li>Delivery services</li>
                        <li>Customer support</li>
                        <li>Data analysis and marketing</li>
                        <li>Technology services and hosting</li>
                    </ul>
                    
                    <h3>Legal Requirements</h3>
                    <p>We may disclose your information when required by law or to:</p>
                    <ul>
                        <li>Comply with legal processes or government requests</li>
                        <li>Protect our rights, property, or safety</li>
                        <li>Investigate potential violations of our terms</li>
                        <li>Prevent fraud or security issues</li>
                    </ul>
                </div>

                <div class="section">
                    <h2>4. Data Security</h2>
                    <p>We implement appropriate security measures to protect your personal information:</p>
                    <ul>
                        <li>Encryption of sensitive data during transmission</li>
                        <li>Secure storage systems with access controls</li>
                        <li>Regular security assessments and updates</li>
                        <li>Employee training on data protection</li>
                        <li>Compliance with industry security standards</li>
                    </ul>
                    <p>However, no method of transmission over the internet is 100% secure, and we cannot guarantee absolute security.</p>
                </div>

                <div class="section">
                    <h2>5. Cookies and Tracking Technologies</h2>
                    <p>We use cookies and similar tracking technologies to:</p>
                    <ul>
                        <li>Remember your preferences and login information</li>
                        <li>Analyze website traffic and usage patterns</li>
                        <li>Personalize content and advertisements</li>
                        <li>Improve our website functionality</li>
                    </ul>
                    <p>You can control cookies through your browser settings, but disabling them may affect some website features.</p>
                </div>

                <div class="section">
                    <h2>6. Your Rights and Choices</h2>
                    <p>You have the following rights regarding your personal information:</p>
                    
                    <h3>Access and Updates</h3>
                    <ul>
                        <li>View and update your account information</li>
                        <li>Request a copy of your personal data</li>
                        <li>Correct inaccurate information</li>
                    </ul>
                    
                    <h3>Communication Preferences</h3>
                    <ul>
                        <li>Opt out of promotional emails</li>
                        <li>Manage notification settings</li>
                        <li>Choose communication channels</li>
                    </ul>
                    
                    <h3>Account Deletion</h3>
                    <ul>
                        <li>Request deletion of your account and data</li>
                        <li>Note: Some information may be retained for legal or business purposes</li>
                    </ul>
                </div>

                <div class="section">
                    <h2>7. Data Retention</h2>
                    <p>We retain your personal information for as long as necessary to:</p>
                    <ul>
                        <li>Provide our services to you</li>
                        <li>Comply with legal obligations</li>
                        <li>Resolve disputes and enforce agreements</li>
                        <li>Improve our services and customer experience</li>
                    </ul>
                    <p>When information is no longer needed, we securely delete or anonymize it.</p>
                </div>

                <div class="section">
                    <h2>8. Third-Party Links</h2>
                    <p>Our website may contain links to third-party websites or services. We are not responsible for the privacy practices of these external sites. We encourage you to read their privacy policies before providing any personal information.</p>
                </div>

                <div class="section">
                    <h2>9. Children's Privacy</h2>
                    <p>Our services are not intended for children under 13 years of age. We do not knowingly collect personal information from children under 13. If we discover that we have collected such information, we will delete it immediately.</p>
                </div>

                <div class="section">
                    <h2>10. International Data Transfers</h2>
                    <p>Your information may be transferred to and processed in countries other than your own. We ensure that such transfers comply with applicable data protection laws and provide adequate protection for your personal information.</p>
                </div>

                <div class="section">
                    <h2>11. Changes to This Privacy Policy</h2>
                    <p>We may update this privacy policy from time to time. We will notify you of any material changes by:</p>
                    <ul>
                        <li>Posting the updated policy on our website</li>
                        <li>Sending you an email notification</li>
                        <li>Displaying a notice on our platform</li>
                    </ul>
                    <p>Your continued use of our services after any changes constitutes acceptance of the updated policy.</p>
                </div>

                <div class="section">
                    <h2>12. Contact Us</h2>
                    <p>If you have any questions, concerns, or requests regarding this privacy policy or our data practices, please contact us:</p>
                    <ul>
                        <li><strong>Email:</strong> privacy@seacatering.com</li>
                        <li><strong>Phone:</strong> 08123456789</li>
                        <li><strong>Address:</strong> Jl. Sudirman No. 123, Jakarta Pusat, 10220</li>
                        <li><strong>Data Protection Officer:</strong> dpo@seacatering.com</li>
                    </ul>
                </div>

                <div class="section agreement-section">
                    <p><strong>By using CateringApp, you acknowledge that you have read and understood this Privacy Policy and agree to our collection, use, and disclosure of your information as described herein.</strong></p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
