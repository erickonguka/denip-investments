<?php

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        return \App\Models\Setting::get($key, $default);
    }
}

if (!function_exists('company_info')) {
    function company_info($key = null)
    {
        $info = [
            'name' => setting('site_name', 'Denip Investments Ltd'),
            'phone' => setting('company_phone', '(254) 788 225 898'),
            'email' => setting('company_email', 'info@denipinvestments.com'),
            'address' => setting('company_address', 'Nairobi, Kenya'),
            'po_box' => setting('company_po_box', 'P.O. Box 12345-00100'),
            'hours' => setting('business_hours', 'Monday - Friday: 8:00 AM - 6:00 PM'),
            'tagline' => setting('company_tagline', 'Building Tomorrow\'s Infrastructure'),
            'description' => setting('company_description', 'Leading construction company in Kenya'),
        ];

        return $key ? ($info[$key] ?? null) : $info;
    }
}

if (!function_exists('social_links')) {
    function social_links()
    {
        return [
            'facebook' => setting('facebook_url'),
            'twitter' => setting('twitter_url'),
            'linkedin' => setting('linkedin_url'),
            'instagram' => setting('instagram_url'),
        ];
    }
}