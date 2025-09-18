<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\CareerApplication;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
            'project' => 'nullable|string|max:255'
        ]);

        if (auth()->check()) {
            return redirect()->route(auth()->user()->isClient() ? 'client.messages.create' : 'admin.messages.index');
        }

        // Create message to admin
        $admin = User::whereHas('roles', function($q) {
            $q->where('name', 'super_admin');
        })->first();

        if (!$admin) {
            $admin = User::whereHas('roles', function($q) {
                $q->where('name', 'admin');
            })->first();
        }

        if ($admin) {
            $subject = $validated['project'] ? 'Project Inquiry: ' . $validated['project'] : 'Contact Form Submission';
            
            Message::create([
                'sender_id' => null,
                'recipient_id' => $admin->id,
                'subject' => $subject,
                'body' => "Name: {$validated['name']}\nEmail: {$validated['email']}\nPhone: " . ($validated['phone'] ?? 'Not provided') . "\n\nMessage:\n{$validated['message']}",
                'sender_name' => $validated['name'],
                'sender_email' => $validated['email'],
                'is_read' => false
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Message sent successfully']);
    }

    public function submitQuote(Request $request)
    {
        $validated = $request->validate([
            'project_type' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'budget' => 'nullable|string|max:255',
            'timeline' => 'nullable|string|max:255',
            'description' => 'required|string'
        ]);

        if (auth()->check()) {
            return redirect()->route(auth()->user()->isClient() ? 'client.messages.create' : 'admin.messages.index');
        }

        // Create message to admin
        $admin = User::whereHas('roles', function($q) {
            $q->where('name', 'super_admin');
        })->first();

        if (!$admin) {
            $admin = User::whereHas('roles', function($q) {
                $q->where('name', 'admin');
            })->first();
        }

        if ($admin) {
            Message::create([
                'sender_id' => null,
                'recipient_id' => $admin->id,
                'subject' => 'Quote Request: ' . $validated['project_type'],
                'body' => "Project Type: {$validated['project_type']}\nLocation: {$validated['location']}\nBudget: " . ($validated['budget'] ?? 'Not specified') . "\nTimeline: " . ($validated['timeline'] ?? 'Not specified') . "\n\nDescription:\n{$validated['description']}",
                'sender_name' => 'Quote Request',
                'sender_email' => null,
                'is_read' => false
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Quote request sent successfully']);
    }
    
    public function submitApplication(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'cover_letter' => 'required|string',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'career_id' => 'required|integer|exists:careers,id'
        ]);

        // Store resume file
        $resumePath = $request->file('resume')->store('applications', 'public');

        // Create career application record
        CareerApplication::create([
            'career_id' => $validated['career_id'],
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'cover_letter' => $validated['cover_letter'],
            'resume_path' => $resumePath,
            'status' => 'pending'
        ]);

        return response()->json(['success' => true, 'message' => 'Application submitted successfully']);
    }
}