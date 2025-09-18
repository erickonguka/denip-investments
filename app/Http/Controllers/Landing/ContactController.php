<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;

class ContactController extends Controller
{
    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'project_type' => 'nullable|string|max:100',
            'message' => 'required|string|max:2000'
        ]);

        try {
            Message::create([
                'sender_name' => $validated['name'],
                'sender_email' => $validated['email'],
                'subject' => 'Contact Form: ' . ($validated['project_type'] ?? 'General Inquiry'),
                'body' => $validated['message'] . "\n\nPhone: " . ($validated['phone'] ?? 'Not provided'),
                'sender_id' => null,
                'recipient_id' => null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thank you for your message. We will get back to you soon!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message. Please try again.'
            ], 500);
        }
    }

    public function submitQuote(Request $request)
    {
        $validated = $request->validate([
            'project_type' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'budget' => 'required|string|max:100',
            'description' => 'required|string|max:2000',
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20'
        ]);

        try {
            $messageBody = "Quote Request Details:\n\n";
            $messageBody .= "Project Type: " . $validated['project_type'] . "\n";
            $messageBody .= "Location: " . $validated['location'] . "\n";
            $messageBody .= "Budget: " . $validated['budget'] . "\n";
            $messageBody .= "Description: " . $validated['description'] . "\n\n";
            
            if ($validated['name']) $messageBody .= "Contact Name: " . $validated['name'] . "\n";
            if ($validated['email']) $messageBody .= "Email: " . $validated['email'] . "\n";
            if ($validated['phone']) $messageBody .= "Phone: " . $validated['phone'] . "\n";

            Message::create([
                'sender_name' => $validated['name'] ?? 'Anonymous',
                'sender_email' => $validated['email'] ?? null,
                'subject' => 'Quote Request: ' . $validated['project_type'],
                'body' => $messageBody,
                'sender_id' => null,
                'recipient_id' => null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Quote request submitted successfully. We will contact you soon!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit quote request. Please try again.'
            ], 500);
        }
    }
}