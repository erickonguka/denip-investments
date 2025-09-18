<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            // General Settings
            ['key' => 'site_name', 'value' => 'Denip Investments Ltd', 'type' => 'string', 'group' => 'general'],
            ['key' => 'currency', 'value' => 'USD', 'type' => 'string', 'group' => 'general'],
            ['key' => 'currency_symbol', 'value' => '$', 'type' => 'string', 'group' => 'general'],
            ['key' => 'date_format', 'value' => 'Y-m-d', 'type' => 'string', 'group' => 'general'],

            ['key' => 'from_email', 'value' => 'admin@denipinvestments.com', 'type' => 'email', 'group' => 'general'],
            ['key' => 'from_name', 'value' => 'Denip Investments', 'type' => 'string', 'group' => 'general'],
            
            // Contact Information
            ['key' => 'company_phone', 'value' => '(254) 788 225 898', 'type' => 'string', 'group' => 'contact'],
            ['key' => 'company_email', 'value' => 'info@denipinvestments.com', 'type' => 'email', 'group' => 'contact'],
            ['key' => 'company_address', 'value' => 'Nairobi, Kenya', 'type' => 'string', 'group' => 'contact'],
            ['key' => 'company_po_box', 'value' => 'P.O. Box 12345-00100', 'type' => 'string', 'group' => 'contact'],
            ['key' => 'business_hours', 'value' => 'Monday - Friday: 8:00 AM - 6:00 PM, Saturday: 9:00 AM - 4:00 PM', 'type' => 'string', 'group' => 'contact'],
            ['key' => 'projects_email', 'value' => 'projects@denipinvestments.com', 'type' => 'email', 'group' => 'contact'],
            
            // Social Media
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/denipinvestments', 'type' => 'url', 'group' => 'social'],
            ['key' => 'twitter_url', 'value' => 'https://twitter.com/denipinvestments', 'type' => 'url', 'group' => 'social'],
            ['key' => 'linkedin_url', 'value' => 'https://linkedin.com/company/denipinvestments', 'type' => 'url', 'group' => 'social'],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/denipinvestments', 'type' => 'url', 'group' => 'social'],
            
            // Company Information
            ['key' => 'company_description', 'value' => 'Leading construction and infrastructure development company in Kenya, delivering excellence in residential, commercial, and infrastructure projects.', 'type' => 'text', 'group' => 'company'],
            ['key' => 'company_tagline', 'value' => 'Building Tomorrow\'s Infrastructure', 'type' => 'string', 'group' => 'company'],
            ['key' => 'company_founded', 'value' => '2010', 'type' => 'string', 'group' => 'company'],
            ['key' => 'company_employees', 'value' => '50+', 'type' => 'string', 'group' => 'company'],
            ['key' => 'projects_completed', 'value' => '200+', 'type' => 'string', 'group' => 'company'],
            
            // Document Prefixes
            ['key' => 'invoice_prefix', 'value' => 'INV-', 'type' => 'string', 'group' => 'documents'],
            ['key' => 'quotation_prefix', 'value' => 'QUO-', 'type' => 'string', 'group' => 'documents'],
            ['key' => 'proposal_prefix', 'value' => 'PROP-', 'type' => 'string', 'group' => 'documents'],
            
            // Document Validity
            ['key' => 'payment_terms_days', 'value' => '30', 'type' => 'number', 'group' => 'documents'],
            ['key' => 'quotation_validity_days', 'value' => '30', 'type' => 'number', 'group' => 'documents'],
            ['key' => 'proposal_validity_days', 'value' => '30', 'type' => 'number', 'group' => 'documents'],
            
            // Tax Settings
            ['key' => 'tax_rate', 'value' => '16.0', 'type' => 'number', 'group' => 'tax'],
            
            // Location
            ['key' => 'company_location', 'value' => '7557-40100 Kisumu', 'type' => 'string', 'group' => 'contact'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}