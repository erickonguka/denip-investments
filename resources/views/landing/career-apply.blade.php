@extends('layouts.landing')

@section('title', $seoData['title'])
@section('meta_description', $seoData['description'])
@section('meta_keywords', $seoData['keywords'])



@section('content')
<!-- Hero Section -->
<section style="background: linear-gradient(135deg, #2C3E50 0%, #34495e 100%); color: white; padding: 2rem 0;">
    <div class="container">
        <div style="margin-bottom: 2rem;">
            <a href="{{ route('landing.careers.show', $career->slug) }}" style="color: rgba(255,255,255,0.9); text-decoration: none; font-weight: 500; transition: all 0.3s ease;"
                onmouseover="this.style.color='white'; this.style.transform='translateX(-3px)'" 
                onmouseout="this.style.color='rgba(255,255,255,0.9)'; this.style.transform='translateX(0)'">
                <i class="fas fa-arrow-left"></i> Back to Job Details
            </a>
        </div>

        <div style="text-align: center; max-width: 800px; margin: 0 auto;">
            <span style="background: rgba(255,255,255,0.2); color: white; padding: 0.5rem 1.5rem; border-radius: 25px; font-size: 0.9rem; font-weight: 600; margin-bottom: 1.5rem; display: inline-block;">
                {{ ucfirst(str_replace('-', ' ', $career->type)) }} Position
            </span>
            <h1 style="font-size: clamp(2.5rem, 5vw, 4rem); margin-bottom: 1rem; font-weight: 800; font-family: 'Playfair Display', serif;">Apply for {{ $career->title }}</h1>
            <p style="font-size: 1.2rem; opacity: 0.9; margin-bottom: 2rem;"><i class="fas fa-map-marker-alt"></i> {{ $career->location }}</p>
            <div style="background: rgba(255,255,255,0.1); border-radius: 15px; padding: 1.5rem; backdrop-filter: blur(10px);">
                <p style="margin: 0; font-size: 1.1rem; opacity: 0.95;">Join Kenya's leading construction company and build your career with us</p>
            </div>
        </div>
    </div>
</section>

<!-- Application Section -->
<section style="padding: 4rem 0; background: #f8fafc;">
    <div class="container">
        <div class="apply-layout">
            <!-- Application Form -->
            <div style="background: white; border-radius: 25px; padding: 3rem; box-shadow: 0 20px 60px rgba(0,0,0,0.1); border: 1px solid #e2e8f0;">
                <div style="text-align: center; margin-bottom: 3rem;">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--primary), #e67e22); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: white; font-size: 2rem; box-shadow: 0 10px 30px rgba(243,156,18,0.3);">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h2 style="color: var(--secondary); margin-bottom: 0.5rem; font-size: 2rem; font-weight: 700; font-family: 'Playfair Display', serif;">Application Form</h2>
                    <p style="color: #6b7280; font-size: 1.1rem;">Tell us about yourself and why you're perfect for this role</p>
                </div>
                
                <form id="applicationForm">
                    <div class="form-grid" style="margin-bottom: 1.5rem;">
                        <div class="form-group">
                            <label for="first_name" style="color: var(--secondary); font-weight: 600; margin-bottom: 0.5rem; display: block;">First Name *</label>
                            <input type="text" id="first_name" name="first_name" required 
                                style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc;"
                                onfocus="this.style.borderColor='var(--primary)'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(243,156,18,0.1)'" 
                                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'">
                        </div>
                        <div class="form-group">
                            <label for="last_name" style="color: var(--secondary); font-weight: 600; margin-bottom: 0.5rem; display: block;">Last Name *</label>
                            <input type="text" id="last_name" name="last_name" required 
                                style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc;"
                                onfocus="this.style.borderColor='var(--primary)'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(243,156,18,0.1)'" 
                                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'">
                        </div>
                    </div>

                    <div class="form-grid" style="margin-bottom: 1.5rem;">
                        <div class="form-group">
                            <label for="email" style="color: var(--secondary); font-weight: 600; margin-bottom: 0.5rem; display: block;">Email Address *</label>
                            <input type="email" id="email" name="email" required 
                                style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc;"
                                onfocus="this.style.borderColor='var(--primary)'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(243,156,18,0.1)'" 
                                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'">
                        </div>
                        <div class="form-group">
                            <label for="phone" style="color: var(--secondary); font-weight: 600; margin-bottom: 0.5rem; display: block;">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" required 
                                style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc;"
                                onfocus="this.style.borderColor='var(--primary)'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(243,156,18,0.1)'" 
                                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'">
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label for="experience" style="color: var(--secondary); font-weight: 600; margin-bottom: 0.5rem; display: block;">Years of Experience</label>
                        <select id="experience" name="experience" 
                            style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc;"
                            onfocus="this.style.borderColor='var(--primary)'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(243,156,18,0.1)'" 
                            onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'">
                            <option value="">Select experience level</option>
                            <option value="0-1">0-1 years (Entry Level)</option>
                            <option value="2-3">2-3 years (Junior)</option>
                            <option value="4-5">4-5 years (Mid-Level)</option>
                            <option value="6-10">6-10 years (Senior)</option>
                            <option value="10+">10+ years (Expert)</option>
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 2rem;">
                        <label for="cover_letter" style="color: var(--secondary); font-weight: 600; margin-bottom: 0.5rem; display: block;">Cover Letter *</label>
                        <textarea id="cover_letter" name="cover_letter" rows="6" placeholder="Tell us why you're interested in this position and what makes you a great fit for our team..." required 
                            style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 1rem; resize: vertical; transition: all 0.3s ease; background: #f8fafc; font-family: inherit;"
                            onfocus="this.style.borderColor='var(--primary)'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(243,156,18,0.1)'" 
                            onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'"></textarea>
                    </div>

                    <div class="form-group" style="margin-bottom: 2rem;">
                        <label style="color: var(--secondary); font-weight: 600; margin-bottom: 1rem; display: block;">Resume/CV *</label>
                        <div class="file-upload" onclick="document.getElementById('resume').click()" 
                            style="border: 2px dashed #e2e8f0; border-radius: 15px; padding: 3rem 2rem; text-align: center; cursor: pointer; transition: all 0.3s ease; background: #f8fafc;"
                            onmouseover="this.style.borderColor='var(--primary)'; this.style.background='rgba(243,156,18,0.05)'" 
                            onmouseout="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'">
                            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary), #e67e22); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 1.5rem;">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <h4 style="color: var(--secondary); margin-bottom: 0.5rem; font-weight: 600;">Upload Your Resume</h4>
                            <p style="color: #6b7280; margin-bottom: 0.5rem;">Click to browse or drag and drop your file here</p>
                            <p style="font-size: 0.9rem; color: #9ca3af;">PDF, DOC, DOCX (Max 5MB)</p>
                            <input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx" style="display: none;" required>
                        </div>
                        <div id="file-name" style="margin-top: 1rem; font-size: 0.9rem; color: var(--primary); font-weight: 600;"></div>
                    </div>

                    <input type="hidden" name="position" value="{{ $career->title }}">
                    <input type="hidden" name="career_id" value="{{ $career->id }}">

                    <button type="submit" class="btn" 
                        style="width: 100%; padding: 1.25rem 2rem; font-size: 1.1rem; font-weight: 700; background: linear-gradient(135deg, var(--primary), #e67e22); color: white; border: none; border-radius: 15px; transition: all 0.3s ease; box-shadow: 0 8px 25px rgba(243,156,18,0.3);"
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 12px 35px rgba(243,156,18,0.4)'" 
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 25px rgba(243,156,18,0.3)'">
                        <i class="fas fa-paper-plane"></i> Submit Application
                    </button>
                </form>
            </div>

            <!-- Sidebar -->
            <div style="position: sticky; top: 100px;">
                <!-- Application Tips -->
                <div style="background: white; border-radius: 20px; padding: 2.5rem; box-shadow: 0 15px 50px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; margin-bottom: 2rem;">
                    <div style="text-align: center; margin-bottom: 2rem;">
                        <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 1.5rem;">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <h3 style="color: var(--secondary); margin-bottom: 0.5rem; font-size: 1.4rem; font-weight: 700;">Application Tips</h3>
                        <p style="color: #6b7280; font-size: 0.9rem;">Make your application stand out</p>
                    </div>
                    
                    <div style="space-y: 1.5rem;">
                        <div style="padding: 1.5rem; background: #f0fdf4; border-radius: 12px; border-left: 4px solid #10b981; margin-bottom: 1.5rem;">
                            <h4 style="color: #065f46; margin-bottom: 0.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-file-alt" style="color: #10b981;"></i> Resume
                            </h4>
                            <p style="font-size: 0.9rem; color: #047857; margin: 0;">Highlight relevant construction experience and technical skills</p>
                        </div>
                        
                        <div style="padding: 1.5rem; background: #eff6ff; border-radius: 12px; border-left: 4px solid #3b82f6; margin-bottom: 1.5rem;">
                            <h4 style="color: #1e40af; margin-bottom: 0.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-pen-fancy" style="color: #3b82f6;"></i> Cover Letter
                            </h4>
                            <p style="font-size: 0.9rem; color: #1d4ed8; margin: 0;">Show passion for construction and explain your career goals</p>
                        </div>
                        
                        <div style="padding: 1.5rem; background: #fef3c7; border-radius: 12px; border-left: 4px solid #f59e0b; margin-bottom: 1.5rem;">
                            <h4 style="color: #92400e; margin-bottom: 0.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-phone" style="color: #f59e0b;"></i> Contact Info
                            </h4>
                            <p style="font-size: 0.9rem; color: #b45309; margin: 0;">Ensure all contact details are accurate and professional</p>
                        </div>
                    </div>
                </div>

                <!-- Process Timeline -->
                <div style="background: linear-gradient(135deg, var(--secondary), #34495e); border-radius: 20px; padding: 2.5rem; color: white; text-align: center;">
                    <div style="margin-bottom: 2rem;">
                        <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 1.5rem;">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3 style="margin-bottom: 0.5rem; font-size: 1.3rem; font-weight: 700;">What Happens Next?</h3>
                        <p style="opacity: 0.9; font-size: 0.9rem;">Our hiring process timeline</p>
                    </div>
                    
                    <div style="text-align: left; space-y: 1rem;">
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <div style="width: 30px; height: 30px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 600;">1</div>
                            <div>
                                <h5 style="margin: 0; font-weight: 600;">Application Review</h5>
                                <p style="margin: 0; font-size: 0.8rem; opacity: 0.8;">1-2 business days</p>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <div style="width: 30px; height: 30px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 600;">2</div>
                            <div>
                                <h5 style="margin: 0; font-weight: 600;">Initial Interview</h5>
                                <p style="margin: 0; font-size: 0.8rem; opacity: 0.8;">3-5 business days</p>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 30px; height: 30px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 600;">3</div>
                            <div>
                                <h5 style="margin: 0; font-weight: 600;">Final Decision</h5>
                                <p style="margin: 0; font-size: 0.8rem; opacity: 0.8;">5-7 business days</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>




<style>
.apply-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 4rem;
    align-items: start;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

@media (max-width: 768px) {
    .apply-layout {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .apply-layout > div:last-child {
        position: static !important;
        order: 1;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .container {
        padding: 0 1rem;
    }
    
    section {
        padding: 2rem 0 !important;
    }
    
    div[style*="border-radius: 25px"] {
        border-radius: 15px !important;
        padding: 2rem !important;
    }
}
</style>

<script>
// File upload handler
document.getElementById('resume').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const fileNameDiv = document.getElementById('file-name');
    
    if (file) {
        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        fileNameDiv.innerHTML = `
            <div style="display: flex; align-items: center; gap: 0.5rem; padding: 1rem; background: #f0fdf4; border-radius: 8px; border: 1px solid #bbf7d0;">
                <i class="fas fa-file-alt" style="color: #10b981;"></i>
                <div>
                    <div style="font-weight: 600; color: #065f46;">${file.name}</div>
                    <div style="font-size: 0.8rem; color: #047857;">${fileSize} MB</div>
                </div>
                <i class="fas fa-check-circle" style="color: #10b981; margin-left: auto;"></i>
            </div>
        `;
    }
});

// Form submission handler
document.getElementById('applicationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btn = this.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting Application...';
    btn.disabled = true;
    btn.style.background = '#6b7280';
    
    // Simulate form submission
    setTimeout(() => {
        btn.innerHTML = '<i class="fas fa-check"></i> Application Submitted!';
        btn.style.background = '#10b981';
        
        // Show success message
        const successDiv = document.createElement('div');
        successDiv.style.cssText = 'position: fixed; top: 100px; right: 20px; background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46; padding: 1.5rem 2rem; border-radius: 12px; box-shadow: 0 10px 30px rgba(16,185,129,0.3); z-index: 1000; border-left: 4px solid #10b981;';
        successDiv.innerHTML = '<i class="fas fa-check-circle"></i> Application submitted successfully! We\'ll be in touch soon.';
        document.body.appendChild(successDiv);
        
        setTimeout(() => {
            successDiv.remove();
        }, 5000);
        
    }, 2000);
});
</script>

@endsection