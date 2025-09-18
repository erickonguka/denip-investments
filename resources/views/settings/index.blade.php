@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<h1 class="page-title">System Settings</h1>
<p class="page-subtitle">Configure site-wide settings and preferences.</p>

<div style="background: var(--white); border-radius: 12px; padding: 2rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
    <form id="settingsForm">
        <div style="display: grid; gap: 2rem;">
            <!-- General Settings -->
            <div>
                <h3 style="font-size: 1.25rem; font-weight: bold; color: var(--deep-blue); margin-bottom: 1rem; border-bottom: 2px solid var(--yellow); padding-bottom: 0.5rem;">General Settings</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
                    <x-form-field 
                        label="Site Name" 
                        name="settings[site_name]" 
                        value="{{ ($settings['general'] ?? collect())->where('key', 'site_name')->first()->value ?? 'Denip Investments Ltd' }}" 
                        placeholder="Enter site name" 
                        :required="true" />
                    
                    <x-form-field 
                        label="Default Currency" 
                        name="settings[currency]" 
                        type="select"
                        :options="['KSH' => 'KSH (KSh)', 'USD' => 'USD ($)', 'UGX' => 'UGX (USh)', 'TZS' => 'TZS (TSh)', 'RWF' => 'RWF (RF)', 'ETB' => 'ETB (Br)', 'EUR' => 'EUR (€)', 'GBP' => 'GBP (£)']"
                        value="{{ ($settings['general'] ?? collect())->where('key', 'currency')->first()->value ?? 'KSH' }}" 
                        :required="true" />
                    
                    <x-form-field 
                        label="Currency Symbol" 
                        name="settings[currency_symbol]" 
                        value="{{ $settings['general']->where('key', 'currency_symbol')->first()->value ?? 'KSh' }}" 
                        placeholder="KSh" />
                    
                    <x-form-field 
                        label="Date Format" 
                        name="settings[date_format]" 
                        type="select"
                        :options="['Y-m-d' => 'YYYY-MM-DD', 'd/m/Y' => 'DD/MM/YYYY', 'm/d/Y' => 'MM/DD/YYYY']"
                        value="{{ $settings['general']->where('key', 'date_format')->first()->value ?? 'Y-m-d' }}" />
                </div>
            </div>

            <!-- Invoice Settings -->
            <div>
                <h3 style="font-size: 1.25rem; font-weight: bold; color: var(--deep-blue); margin-bottom: 1rem; border-bottom: 2px solid var(--yellow); padding-bottom: 0.5rem;">Invoice Settings</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
                    <x-form-field 
                        label="Invoice Prefix" 
                        name="settings[invoice_prefix]" 
                        value="{{ $settings['general']->where('key', 'invoice_prefix')->first()->value ?? 'INV-' }}" 
                        placeholder="INV-" />
                    
                    <x-form-field 
                        label="Tax Rate (%)" 
                        name="settings[tax_rate]" 
                        type="number"
                        step="0.01"
                        value="{{ $settings['general']->where('key', 'tax_rate')->first()->value ?? '0' }}" 
                        placeholder="0.00" />
                    
                    <x-form-field 
                        label="Payment Terms (Days)" 
                        name="settings[payment_terms]" 
                        type="number"
                        value="{{ ($settings['general'] ?? collect())->where('key', 'payment_terms')->first()->value ?? '30' }}" 
                        placeholder="30" />
                    
                    <x-form-field 
                        label="Invoice Footer" 
                        name="settings[invoice_footer]" 
                        type="textarea"
                        value="{{ ($settings['invoice'] ?? collect())->where('key', 'invoice_footer')->first()->value ?? 'Thank you for your business!' }}" 
                        placeholder="Enter default invoice footer" />
                </div>
            </div>

            <!-- Quotation Settings -->
            <div>
                <h3 style="font-size: 1.25rem; font-weight: bold; color: var(--deep-blue); margin-bottom: 1rem; border-bottom: 2px solid var(--yellow); padding-bottom: 0.5rem;">Quotation Settings</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
                    <x-form-field 
                        label="Quotation Prefix" 
                        name="settings[quotation_prefix]" 
                        value="{{ ($settings['quotation'] ?? collect())->where('key', 'quotation_prefix')->first()->value ?? 'QUO-' }}" 
                        placeholder="QUO-" />
                    
                    <x-form-field 
                        label="Valid For (Days)" 
                        name="settings[quotation_validity]" 
                        type="number"
                        value="{{ ($settings['quotation'] ?? collect())->where('key', 'quotation_validity')->first()->value ?? '30' }}" 
                        placeholder="30" />
                    
                    <x-form-field 
                        label="Default Terms" 
                        name="settings[quotation_terms]" 
                        type="textarea"
                        value="{{ ($settings['quotation'] ?? collect())->where('key', 'quotation_terms')->first()->value ?? 'This quotation is valid for 30 days from the date of issue.' }}" 
                        placeholder="Enter default quotation terms" />
                </div>
            </div>

            <!-- Proposal Settings -->
            <div>
                <h3 style="font-size: 1.25rem; font-weight: bold; color: var(--deep-blue); margin-bottom: 1rem; border-bottom: 2px solid var(--yellow); padding-bottom: 0.5rem;">Proposal Settings</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
                    <x-form-field 
                        label="Proposal Prefix" 
                        name="settings[proposal_prefix]" 
                        value="{{ ($settings['proposal'] ?? collect())->where('key', 'proposal_prefix')->first()->value ?? 'PROP-' }}" 
                        placeholder="PROP-" />
                    
                    <x-form-field 
                        label="Valid For (Days)" 
                        name="settings[proposal_validity]" 
                        type="number"
                        value="{{ ($settings['proposal'] ?? collect())->where('key', 'proposal_validity')->first()->value ?? '45' }}" 
                        placeholder="45" />
                    
                    <x-form-field 
                        label="Default Footer" 
                        name="settings[proposal_footer]" 
                        type="textarea"
                        value="{{ ($settings['proposal'] ?? collect())->where('key', 'proposal_footer')->first()->value ?? 'Thank you for considering our proposal. We look forward to working with you.' }}" 
                        placeholder="Enter default proposal footer" />
                </div>
            </div>

            <!-- Contact Information -->
            <div>
                <h3 style="font-size: 1.25rem; font-weight: bold; color: var(--deep-blue); margin-bottom: 1rem; border-bottom: 2px solid var(--yellow); padding-bottom: 0.5rem;">Contact Information</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
                    <x-form-field 
                        label="Company Phone" 
                        name="settings[company_phone]" 
                        value="{{ ($settings['contact'] ?? collect())->where('key', 'company_phone')->first()->value ?? '+254 700 000 000' }}" 
                        placeholder="Enter phone number" />
                    
                    <x-form-field 
                        label="Company Email" 
                        name="settings[company_email]" 
                        type="email"
                        value="{{ ($settings['contact'] ?? collect())->where('key', 'company_email')->first()->value ?? 'info@denipinvestments.com' }}" 
                        placeholder="Enter company email" />
                    
                    <x-form-field 
                        label="Company Address" 
                        name="settings[company_address]" 
                        value="{{ ($settings['contact'] ?? collect())->where('key', 'company_address')->first()->value ?? 'Nairobi, Kenya' }}" 
                        placeholder="Enter company address" />
                    
                    <x-form-field 
                        label="Location (Postal Code - City)" 
                        name="settings[company_location]" 
                        value="{{ ($settings['contact'] ?? collect())->where('key', 'company_location')->first()->value ?? '7557-40100 Kisumu' }}" 
                        placeholder="Enter location with postal code" />
                    
                    <x-form-field 
                        label="P.O. Box" 
                        name="settings[company_po_box]" 
                        value="{{ ($settings['contact'] ?? collect())->where('key', 'company_po_box')->first()->value ?? '' }}" 
                        placeholder="Enter P.O. Box" />
                    
                    <x-form-field 
                        label="Business Hours" 
                        name="settings[business_hours]" 
                        value="{{ ($settings['contact'] ?? collect())->where('key', 'business_hours')->first()->value ?? 'Mon - Fri: 8:00 AM - 6:00 PM' }}" 
                        placeholder="Enter business hours" />
                    
                    <x-form-field 
                        label="Map Embed URL" 
                        name="settings[map_embed_url]" 
                        type="textarea"
                        value="{{ ($settings['social'] ?? collect())->where('key', 'map_embed_url')->first()->value ?? '' }}" 
                        placeholder="Enter Google Maps embed iframe code" />
                </div>
            </div>
            
            <!-- Social Media -->
            <div>
                <h3 style="font-size: 1.25rem; font-weight: bold; color: var(--deep-blue); margin-bottom: 1rem; border-bottom: 2px solid var(--yellow); padding-bottom: 0.5rem;">Social Media</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
                    <x-form-field 
                        label="Facebook URL" 
                        name="settings[facebook_url]" 
                        type="url"
                        value="{{ ($settings['social'] ?? collect())->where('key', 'facebook_url')->first()->value ?? '' }}" 
                        placeholder="https://facebook.com/yourpage" />
                    
                    <x-form-field 
                        label="Twitter URL" 
                        name="settings[twitter_url]" 
                        type="url"
                        value="{{ ($settings['social'] ?? collect())->where('key', 'twitter_url')->first()->value ?? '' }}" 
                        placeholder="https://twitter.com/yourhandle" />
                    
                    <x-form-field 
                        label="LinkedIn URL" 
                        name="settings[linkedin_url]" 
                        type="url"
                        value="{{ ($settings['social'] ?? collect())->where('key', 'linkedin_url')->first()->value ?? '' }}" 
                        placeholder="https://linkedin.com/company/yourcompany" />
                    
                    <x-form-field 
                        label="Instagram URL" 
                        name="settings[instagram_url]" 
                        type="url"
                        value="{{ ($settings['social'] ?? collect())->where('key', 'instagram_url')->first()->value ?? '' }}" 
                        placeholder="https://instagram.com/yourhandle" />
                </div>
            </div>

            <!-- Email Settings -->
            <div>
                <h3 style="font-size: 1.25rem; font-weight: bold; color: var(--deep-blue); margin-bottom: 1rem; border-bottom: 2px solid var(--yellow); padding-bottom: 0.5rem;">Email Settings</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
                    <x-form-field 
                        label="From Email" 
                        name="settings[from_email]" 
                        type="email"
                        value="{{ $settings['general']->where('key', 'from_email')->first()->value ?? 'admin@denipinvestments.com' }}" 
                        placeholder="admin@denipinvestments.com" />
                    
                    <x-form-field 
                        label="From Name" 
                        name="settings[from_name]" 
                        value="{{ $settings['general']->where('key', 'from_name')->first()->value ?? 'Denip Investments' }}" 
                        placeholder="Denip Investments" />
                </div>
            </div>
        </div>
        
        <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--gray-200);">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                <span class="btn-text">Save Settings</span>
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('settingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btn = this.querySelector('button[type="submit"]');
    const btnText = btn.querySelector('.btn-text');
    
    btn.disabled = true;
    btnText.textContent = 'Saving...';
    
    const formData = new FormData(this);
    const data = { settings: {} };
    
    for (let [key, value] of formData.entries()) {
        if (key.startsWith('settings[') && key.endsWith(']')) {
            const settingKey = key.slice(9, -1); // Remove 'settings[' and ']'
            data.settings[settingKey] = value;
        } else {
            data[key] = value;
        }
    }
    
    fetch('{{ route("settings.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showNotification(data.message || 'Settings saved successfully!', 'success');
        } else {
            showNotification(data.message || 'Failed to save settings', 'error');
            if (data.errors) {
                console.error('Validation errors:', data.errors);
                // Show first validation error
                const firstError = Object.values(data.errors)[0][0];
                showNotification(firstError, 'error');
            }
        }
    })
    .catch(error => {
        console.error('Settings update error:', error);
        if (error.errors) {
            // Show validation errors
            const firstError = Object.values(error.errors)[0][0];
            showNotification(firstError, 'error');
        } else {
            showNotification(error.message || 'An error occurred while saving settings', 'error');
        }
    })
    .finally(() => {
        btn.disabled = false;
        btnText.textContent = 'Save Settings';
    });
});
</script>
@endpush