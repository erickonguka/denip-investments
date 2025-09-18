<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'settings.site_name' => 'required|string|max:255',
                'settings.currency' => 'required|string|in:KSH,USD,UGX,TZS,RWF,ETB,EUR,GBP',
                'settings.currency_symbol' => 'required|string|max:10',
                'settings.date_format' => 'required|string|in:Y-m-d,d/m/Y,m/d/Y',
                'settings.invoice_prefix' => 'required|string|max:10',
                'settings.tax_rate' => 'required|numeric|min:0|max:100',
                'settings.payment_terms' => 'required|integer|min:1|max:365',
                'settings.invoice_footer' => 'nullable|string|max:1000',
                'settings.quotation_prefix' => 'required|string|max:10',
                'settings.quotation_validity' => 'required|integer|min:1|max:365',
                'settings.quotation_terms' => 'nullable|string|max:1000',
                'settings.proposal_prefix' => 'required|string|max:10',
                'settings.proposal_validity' => 'required|integer|min:1|max:365',
                'settings.proposal_footer' => 'nullable|string|max:1000',
                'settings.from_email' => 'required|email|max:255',
                'settings.from_name' => 'required|string|max:255',
                'settings.company_phone' => 'nullable|string|max:20',
                'settings.company_email' => 'nullable|email|max:255',
                'settings.company_address' => 'nullable|string|max:500',
                'settings.company_po_box' => 'nullable|string|max:100',
                'settings.business_hours' => 'nullable|string|max:255',
                'settings.facebook_url' => 'nullable|url|max:255',
                'settings.twitter_url' => 'nullable|url|max:255',
                'settings.linkedin_url' => 'nullable|url|max:255',
                'settings.instagram_url' => 'nullable|url|max:255',
                'settings.map_embed_url' => 'nullable|string|max:1000',
            ]);

            foreach ($validated['settings'] as $key => $value) {
                $group = 'general';
                if (str_starts_with($key, 'quotation_')) {
                    $group = 'quotation';
                } elseif (str_starts_with($key, 'proposal_')) {
                    $group = 'proposal';
                } elseif (str_starts_with($key, 'invoice_')) {
                    $group = 'invoice';
                } elseif (in_array($key, ['company_phone', 'company_email', 'company_address', 'company_po_box', 'business_hours'])) {
                    $group = 'contact';
                } elseif (str_contains($key, '_url') || $key === 'map_embed_url') {
                    $group = 'social';
                }
                
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value, 'group' => $group]
                );
            }
            
            \App\Models\ActivityLog::log('updated', null, 'System settings updated');

            return response()->json(['success' => true, 'message' => 'Settings updated successfully']);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving settings'
            ], 500);
        }
    }
}