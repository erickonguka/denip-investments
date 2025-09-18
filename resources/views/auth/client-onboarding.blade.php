@extends('layouts.landing')

@section('title', 'Partner with Us - Denip Investments Ltd')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">
    <style>
        .pac-container {
            z-index: 10000 !important;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            border: none;
        }

        .pac-item {
            padding: 12px 16px;
            border-bottom: 1px solid #f0f0f0;
        }

        .pac-item:hover {
            background: #f8f9fa;
        }
    </style>
@endpush

@section('content')
    <div
        style="min-height: 100vh; background: white; padding: 2rem 0;">
        <div style="max-width: 900px; margin: 0 auto; padding: 0 1rem;">

            <!-- Header -->
            <div style="text-align: center; margin-bottom: 3rem; margin-top: 4rem; color: #2C3E50;">
                <h1 style="font-size: 3rem; font-weight: 700; margin-bottom: 1rem;">Partner With Us</h1>
                <p style="font-size: 1.2rem; opacity: 0.7;">Join East Africa's leading construction company.</p>
            </div>

            <!-- Progress Bar -->
            <div style="background: #f8f9fa; border-radius: 50px; padding: 0.5rem; margin-bottom: 3rem; border: 1px solid #e0e0e0;">
                <div id="progressBar"
                    style="width: 25%; height: 8px; background: #F39C12; border-radius: 50px; transition: width 0.5s ease;">
                </div>
            </div>

            <!-- Step Indicators -->
            <div style="display: flex; justify-content: center; gap: 2rem; margin-bottom: 3rem;">
                <div class="step-indicator active" data-step="1">
                    <div class="step-circle">1</div>
                    <span>Project Details</span>
                </div>
                <div class="step-indicator" data-step="2">
                    <div class="step-circle">2</div>
                    <span>Company Info</span>
                </div>
                <div class="step-indicator" data-step="3">
                    <div class="step-circle">3</div>
                    <span>Contact Details</span>
                </div>
                <div class="step-indicator" data-step="4">
                    <div class="step-circle">4</div>
                    <span>Verification</span>
                </div>
            </div>

            <!-- Form Container -->
            <div
                style="background: white; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.2); padding: 2rem; position: relative; overflow: hidden; max-width: 700px; margin: 0 auto;">

                <div id="alertContainer"></div>

                <form id="onboardingForm">
                    @csrf

                    <!-- Step 1: Project Details -->
                    <div class="step-content active" data-step="1">
                        <div style="text-align: center; margin-bottom: 2rem;">
                            <div
                                style="width: 80px; height: 80px; background: var(--secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                                <i class="fas fa-building" style="font-size: 2rem; color: white;"></i>
                            </div>
                            <h2 style="color: var(--primary); font-size: 2rem; margin-bottom: 0.5rem;">Tell Us About Your
                                Project</h2>
                            <p style="color: #666; font-size: 1.1rem;">What construction project are you planning?</p>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                            <div class="form-group">
                                <label>Project Type</label>
                                <select name="project_type" required>
                                    <option value="">Select project type</option>
                                    <option value="residential">Residential Development</option>
                                    <option value="commercial">Commercial Building</option>
                                    <option value="industrial">Industrial Facility</option>
                                    <option value="infrastructure">Infrastructure Project</option>
                                    <option value="renovation">Renovation & Upgrade</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Project Scale</label>
                                <select name="project_scale" required>
                                    <option value="">Select project scale</option>
                                    <option value="small">Small Scale (Under KES 5M)</option>
                                    <option value="medium">Medium Scale (KES 5M - 50M)</option>
                                    <option value="large">Large Scale (KES 50M - 200M)</option>
                                    <option value="mega">Mega Project (Over KES 200M)</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 2rem; position: relative;">
                            <label>Project Location</label>
                            <input type="text" id="locationInput" name="project_location" required
                                placeholder="Start typing location...">
                            <div id="locationSuggestions"
                                style="position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); z-index: 1000; max-height: 200px; overflow-y: auto; display: none;">
                            </div>
                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">
                            <input type="hidden" name="formatted_address" id="formatted_address">
                            <input type="hidden" name="place_id" id="place_id">
                        </div>

                        <div class="form-group" style="margin-bottom: 2rem;">
                            <label>Project Timeline</label>
                            <select name="project_timeline" required>
                                <option value="">When do you plan to start?</option>
                                <option value="immediate">Immediate (Within 1 month)</option>
                                <option value="short">Short term (1-3 months)</option>
                                <option value="medium">Medium term (3-6 months)</option>
                                <option value="long">Long term (6+ months)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Project Description</label>
                            <textarea name="project_description" rows="4"
                                placeholder="Briefly describe your construction project, key requirements, and any specific needs..."></textarea>
                        </div>
                    </div>

                    <!-- Step 2: Company Information -->
                    <div class="step-content" data-step="2">
                        <div style="text-align: center; margin-bottom: 2rem;">
                            <div
                                style="width: 80px; height: 80px; background: var(--secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                                <i class="fas fa-industry" style="font-size: 2rem; color: white;"></i>
                            </div>
                            <h2 style="color: var(--primary); font-size: 2rem; margin-bottom: 0.5rem;">Company Information
                            </h2>
                            <p style="color: #666; font-size: 1.1rem;">Help us understand your organization</p>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                            <div class="form-group">
                                <label>Company/Organization Name</label>
                                <input type="text" name="company" required placeholder="Your company name">
                            </div>
                            <div class="form-group">
                                <label>Industry Sector</label>
                                <select name="industry" required>
                                    <option value="">Select industry</option>
                                    <option value="real-estate">Real Estate Development</option>
                                    <option value="manufacturing">Manufacturing</option>
                                    <option value="hospitality">Hospitality & Tourism</option>
                                    <option value="retail">Retail & Commercial</option>
                                    <option value="government">Government/Public Sector</option>
                                    <option value="education">Education</option>
                                    <option value="healthcare">Healthcare</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                            <div class="form-group">
                                <label>Company Size</label>
                                <select name="company_size" required>
                                    <option value="">Select company size</option>
                                    <option value="startup">Startup (1-10 employees)</option>
                                    <option value="small">Small (11-50 employees)</option>
                                    <option value="medium">Medium (51-200 employees)</option>
                                    <option value="large">Large (200+ employees)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Years in Business</label>
                                <select name="years_in_business" required>
                                    <option value="">Select experience</option>
                                    <option value="new">New Business (0-1 years)</option>
                                    <option value="emerging">Emerging (2-5 years)</option>
                                    <option value="established">Established (6-15 years)</option>
                                    <option value="mature">Mature (15+ years)</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Business Registration Number (Optional)</label>
                            <input type="text" name="registration_number" placeholder="Company registration number">
                        </div>
                    </div>

                    <!-- Step 3: Contact Details -->
                    <div class="step-content" data-step="3">
                        <div style="text-align: center; margin-bottom: 2rem;">
                            <div
                                style="width: 80px; height: 80px; background: var(--secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                                <i class="fas fa-user-tie" style="font-size: 2rem; color: white;"></i>
                            </div>
                            <h2 style="color: var(--primary); font-size: 2rem; margin-bottom: 0.5rem;">Your Contact
                                Information</h2>
                            <p style="color: #666; font-size: 1.1rem;">We'll use this to create your account and stay in
                                touch</p>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" name="first_name" required placeholder="Your first name">
                            </div>
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" name="last_name" required placeholder="Your last name">
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                            <div class="form-group">
                                <label>Job Title/Position</label>
                                <input type="text" name="job_title" required placeholder="e.g., Project Manager, CEO">
                            </div>
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" name="email" required placeholder="your.email@company.com">
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="tel" id="phone" name="phone" required>
                            </div>
                            <div class="form-group">
                                <label>Preferred Contact Method</label>
                                <select name="contact_preference" required>
                                    <option value="">Select preference</option>
                                    <option value="email">Email</option>
                                    <option value="phone">Phone Call</option>
                                    <option value="whatsapp">WhatsApp</option>
                                    <option value="meeting">In-Person Meeting</option>
                                </select>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" required minlength="8"
                                    placeholder="Create a secure password">
                            </div>
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input type="password" name="password_confirmation" required minlength="8"
                                    placeholder="Confirm your password">
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Verification -->
                    <div class="step-content" data-step="4">
                        <div style="text-align: center; margin-bottom: 2rem;">
                            <div
                                style="width: 80px; height: 80px; background: var(--secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                                <i class="fas fa-shield-check" style="font-size: 2rem; color: white;"></i>
                            </div>
                            <h2 style="color: var(--primary); font-size: 2rem; margin-bottom: 0.5rem;">Almost There!</h2>
                            <p style="color: #666; font-size: 1.1rem;">Review your information and complete registration
                            </p>
                        </div>

                        <div id="reviewContent"
                            style="background: #f8f9fa; padding: 2rem; border-radius: 12px; margin-bottom: 2rem;">
                            <!-- Review content will be populated by JavaScript -->
                        </div>

                        <div
                            style="background: #e8f4fd; border: 1px solid #bee5eb; border-radius: 8px; padding: 1.5rem; margin-bottom: 2rem;">
                            <h4 style="color: var(--primary); margin-bottom: 1rem;"><i class="fas fa-info-circle"
                                    style="margin-right: 0.5rem;"></i>What happens next?</h4>
                            <ul style="color: #666; line-height: 1.8; margin-left: 1rem;">
                                <li>We'll send a verification code to your email</li>
                                <li>Our team will review your project requirements</li>
                                <li>You'll receive a personalized consultation within 24 hours</li>
                                <li>Access your dedicated project dashboard</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 3rem;">
                        <button type="button" id="prevBtn" class="btn btn-outline" style="visibility: hidden;">
                            <i class="fas fa-arrow-left" style="margin-right: 0.5rem;"></i>
                            Previous
                        </button>
                        <div style="flex: 1;"></div>
                        <button type="button" id="nextBtn" class="btn btn-primary">
                            Next Step
                            <i class="fas fa-arrow-right" style="margin-left: 0.5rem;"></i>
                        </button>
                        <button type="submit" id="submitBtn" class="btn btn-primary" style="display: none;">
                            <span id="submitText">Complete Registration</span>
                            <i id="submitSpinner" class="fas fa-spinner fa-spin"
                                style="display: none; margin-left: 0.5rem;"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .step-indicator {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #6b7280;
            transition: all 0.3s ease;
        }

        .step-indicator.active {
            color: #2C3E50;
        }

        .step-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
            color: #6b7280;
        }

        .step-indicator.active .step-circle {
            background: #F39C12;
            color: white;
        }

        .step-content {
            display: none;
        }

        .step-content.active {
            display: block;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--primary);
            font-weight: 600;
            font-size: 0.95rem;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.875rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(243, 156, 18, 0.1);
        }

        @media (max-width: 768px) {
            .step-content div[style*="grid-template-columns"] {
                grid-template-columns: 1fr !important;
            }

            .step-indicator span {
                font-size: 0.8rem;
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
    <script>
        let currentStep = 1;
        const totalSteps = 4;
        const STORAGE_KEY = 'denip_onboarding_data';

        let phoneInput;
        let searchTimeout;

        // Load saved form data
        function loadFormData() {
            const saved = localStorage.getItem(STORAGE_KEY);
            if (saved) {
                const data = JSON.parse(saved);
                Object.keys(data).forEach(key => {
                    const field = document.querySelector(`[name="${key}"]`);
                    if (field && key !== 'password' && key !== 'password_confirmation') {
                        field.value = data[key];
                    }
                });
            }
        }

        // Save form data
        function saveFormData() {
            const formData = new FormData(document.getElementById('onboardingForm'));
            const data = {};
            for (let [key, value] of formData.entries()) {
                if (key !== 'password' && key !== 'password_confirmation') {
                    data[key] = value;
                }
            }
            localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
        }

        // Auto-save on input
        document.addEventListener('input', function(e) {
            if (e.target.form && e.target.form.id === 'onboardingForm') {
                saveFormData();
            }
        });

        document.addEventListener('change', function(e) {
            if (e.target.form && e.target.form.id === 'onboardingForm') {
                saveFormData();
            }
        });

        function initLocationSearch() {
            const locationInput = document.getElementById('locationInput');
            const suggestionsContainer = document.getElementById('locationSuggestions');

            locationInput.addEventListener('input', function() {
                const query = this.value.trim();

                clearTimeout(searchTimeout);

                if (query.length < 3) {
                    suggestionsContainer.style.display = 'none';
                    return;
                }

                searchTimeout = setTimeout(async () => {
                    try {
                        const response = await fetch(
                            `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&addressdetails=1&countrycodes=ke,ug,tz,rw&limit=5`
                        );
                        const data = await response.json();

                        displaySuggestions(data);
                    } catch (error) {
                        console.error('Nominatim search error:', error);
                    }
                }, 300);
            });

            function displaySuggestions(suggestions) {
                if (suggestions.length === 0) {
                    suggestionsContainer.style.display = 'none';
                    return;
                }

                suggestionsContainer.innerHTML = '';
                suggestions.forEach(suggestion => {
                    const item = document.createElement('div');
                    item.style.cssText = 'padding: 12px 16px; cursor: pointer; border-bottom: 1px solid #f0f0f0;';
                    item.innerHTML =
                        `<strong>${suggestion.display_name.split(',')[0]}</strong><br><small style="color: #666;">${suggestion.display_name}</small>`;

                    item.addEventListener('mouseenter', () => item.style.background = '#f8f9fa');
                    item.addEventListener('mouseleave', () => item.style.background = 'white');

                    item.addEventListener('click', () => {
                        locationInput.value = suggestion.display_name;
                        document.getElementById('latitude').value = suggestion.lat;
                        document.getElementById('longitude').value = suggestion.lon;
                        document.getElementById('formatted_address').value = suggestion.display_name;
                        document.getElementById('place_id').value = suggestion.place_id;

                        locationInput.style.borderColor = '#4CAF50';
                        locationInput.style.boxShadow = '0 0 0 3px rgba(76, 175, 80, 0.1)';
                        suggestionsContainer.style.display = 'none';
                    });

                    suggestionsContainer.appendChild(item);
                });

                suggestionsContainer.style.display = 'block';
            }

            document.addEventListener('click', function(e) {
                if (!locationInput.parentNode.contains(e.target)) {
                    suggestionsContainer.style.display = 'none';
                }
            });

            phoneInput = window.intlTelInput(document.querySelector("#phone"), {
                initialCountry: "ke",
                preferredCountries: ["ke", "ug", "tz", "rw"],
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
            });

            loadFormData();
            updateProgress();
        }

        initLocationSearch();

        function updateProgress() {
            const progress = (currentStep / totalSteps) * 100;
            document.getElementById('progressBar').style.width = progress + '%';

            document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
                if (index + 1 <= currentStep) {
                    indicator.classList.add('active');
                } else {
                    indicator.classList.remove('active');
                }
            });

            document.querySelectorAll('.step-content').forEach((content, index) => {
                if (index + 1 === currentStep) {
                    content.classList.add('active');
                } else {
                    content.classList.remove('active');
                }
            });

            document.getElementById('prevBtn').style.visibility = currentStep > 1 ? 'visible' : 'hidden';
            document.getElementById('nextBtn').style.display = currentStep < totalSteps ? 'block' : 'none';
            document.getElementById('submitBtn').style.display = currentStep === totalSteps ? 'block' : 'none';

            if (currentStep === 4) {
                populateReview();
            }
        }

        function validateStep(step) {
            const stepContent = document.querySelector(`.step-content[data-step="${step}"]`);
            const requiredFields = stepContent.querySelectorAll('[required]');
            const alertContainer = document.getElementById('alertContainer');
            let errors = [];

            // Clear previous errors
            alertContainer.innerHTML = '';

            for (let field of requiredFields) {
                if (!field.value.trim()) {
                    field.style.borderColor = '#e74c3c';
                    const label = field.closest('.form-group').querySelector('label').textContent;
                    errors.push(`${label} is required`);
                } else {
                    field.style.borderColor = '#e0e0e0';
                }
            }

            // Step-specific validations
            if (step === 1) {
                const locationInput = document.getElementById('locationInput');
                const latitude = document.getElementById('latitude').value;
                if (locationInput.value && !latitude) {
                    locationInput.style.borderColor = '#e74c3c';
                    errors.push('Please select a location from the suggestions');
                }
            }

            if (step === 3) {
                const email = document.querySelector('[name="email"]').value;
                const password = document.querySelector('[name="password"]').value;
                const confirmPassword = document.querySelector('[name="password_confirmation"]').value;

                // Email validation
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (email && !emailRegex.test(email)) {
                    document.querySelector('[name="email"]').style.borderColor = '#e74c3c';
                    errors.push('Please enter a valid email address');
                }

                // Password validation
                if (password && password.length < 8) {
                    document.querySelector('[name="password"]').style.borderColor = '#e74c3c';
                    errors.push('Password must be at least 8 characters long');
                }

                if (password !== confirmPassword) {
                    document.querySelector('[name="password_confirmation"]').style.borderColor = '#e74c3c';
                    errors.push('Passwords do not match');
                }
            }

            if (errors.length > 0) {
                let errorHtml =
                    '<div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #f5c6cb;"><i class="fas fa-exclamation-triangle" style="margin-right: 0.5rem;"></i><strong>Please fix the following errors:</strong><ul style="margin: 0.5rem 0 0 1rem;">';

                errors.forEach(error => {
                    errorHtml += `<li>${error}</li>`;
                });

                errorHtml += '</ul></div>';
                alertContainer.innerHTML = errorHtml;

                // Focus first error field
                const firstErrorField = stepContent.querySelector('[style*="border-color: rgb(231, 76, 60)"]');
                if (firstErrorField) {
                    firstErrorField.focus();
                }

                return false;
            }

            return true;
        }

        function populateReview() {
            const formData = new FormData(document.getElementById('onboardingForm'));
            const reviewContent = document.getElementById('reviewContent');

            reviewContent.innerHTML = `
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <div>
                <h4 style="color: var(--primary); margin-bottom: 1rem;">Project Information</h4>
                <p><strong>Type:</strong> ${formData.get('project_type')}</p>
                <p><strong>Scale:</strong> ${formData.get('project_scale')}</p>
                <p><strong>Location:</strong> ${formData.get('project_location')}</p>
                <p><strong>Timeline:</strong> ${formData.get('project_timeline')}</p>
            </div>
            <div>
                <h4 style="color: var(--primary); margin-bottom: 1rem;">Contact Information</h4>
                <p><strong>Name:</strong> ${formData.get('first_name')} ${formData.get('last_name')}</p>
                <p><strong>Company:</strong> ${formData.get('company')}</p>
                <p><strong>Email:</strong> ${formData.get('email')}</p>
                <p><strong>Phone:</strong> ${phoneInput.getNumber()}</p>
            </div>
        </div>
    `;
        }

        document.getElementById('nextBtn').addEventListener('click', function() {
            if (validateStep(currentStep)) {
                currentStep++;
                updateProgress();
            }
        });

        document.getElementById('prevBtn').addEventListener('click', function() {
            currentStep--;
            updateProgress();
        });

        document.getElementById('onboardingForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const btn = document.getElementById('submitBtn');
            const btnText = document.getElementById('submitText');
            const spinner = document.getElementById('submitSpinner');
            const alertContainer = document.getElementById('alertContainer');

            btn.disabled = true;
            btnText.textContent = 'Processing...';
            spinner.style.display = 'inline-block';
            alertContainer.innerHTML = '';

            const formData = new FormData(this);
            formData.set('phone', phoneInput.getNumber());
            formData.set('country', phoneInput.getSelectedCountryData().iso2);

            try {
                const response = await fetch('{{ route('client.onboarding.submit') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    btnText.textContent = 'Registration Complete!';
                    btn.style.background = '#4CAF50';
                    localStorage.removeItem(STORAGE_KEY);

                    alertContainer.innerHTML = `
                <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                    <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>
                    Welcome to Denip Investments! Redirecting to email verification...
                </div>
            `;

                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 2000);
                } else {
                    let errorHtml =
                        '<div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #f5c6cb;"><i class="fas fa-exclamation-triangle" style="margin-right: 0.5rem;"></i><strong>Registration failed:</strong><ul style="margin: 0.5rem 0 0 1rem;">';

                    if (data.errors) {
                        Object.values(data.errors).forEach(errorArray => {
                            errorArray.forEach(error => {
                                errorHtml += `<li>${error}</li>`;
                            });
                        });
                    } else {
                        errorHtml += '<li>An unexpected error occurred. Please try again.</li>';
                    }

                    errorHtml += '</ul></div>';
                    alertContainer.innerHTML = errorHtml;

                    // Scroll to top to show errors
                    document.querySelector('.step-content.active').scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            } catch (error) {
                console.error('Registration error:', error);
                alertContainer.innerHTML = `
            <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #f5c6cb;">
                <i class="fas fa-exclamation-triangle" style="margin-right: 0.5rem;"></i>
                <strong>Network Error:</strong> Unable to connect to server. Please check your internet connection and try again.
            </div>
        `;

                // Scroll to top to show errors
                document.querySelector('.step-content.active').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            } finally {
                btn.disabled = false;
                btnText.textContent = 'Complete Registration';
                spinner.style.display = 'none';
                btn.style.background = '';
            }
        });
    </script>
@endsection
