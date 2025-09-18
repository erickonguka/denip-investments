<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::with(['projects', 'invoices'])->latest()->paginate(15);
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'phone' => 'nullable|string|max:20',
            'type' => 'required|in:corporate,individual',
            'address' => 'nullable|string',
        ]);

        Client::create($validated);

        return response()->json(['success' => true, 'message' => 'Client created successfully']);
    }

    public function show(Client $client)
    {
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $client->load(['projects', 'invoices', 'proposals', 'quotations'])
            ]);
        }
        
        $client->load(['projects', 'invoices', 'proposals', 'quotations', 'user']);
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return response()->json([
            'success' => true,
            'data' => $client
        ]);
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'phone' => 'nullable|string|max:20',
            'type' => 'required|in:corporate,individual',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            // Additional fields for self-registered clients
            'job_title' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:100',
            'company_size' => 'nullable|string|max:50',
            'years_in_business' => 'nullable|string|max:50',
            'registration_number' => 'nullable|string|max:100',
            'contact_preference' => 'nullable|string|max:50',
            'country' => 'nullable|string|size:2',
        ]);

        // Update client basic info
        $clientData = collect($validated)->only(['name', 'company', 'email', 'phone', 'type', 'address', 'status'])->toArray();
        $client->update($clientData);
        
        // Update associated user data if exists (self-registered clients)
        if ($client->user) {
            $userData = collect($validated)->only([
                'company', 'job_title', 'industry', 'company_size', 
                'years_in_business', 'registration_number', 'contact_preference', 'country'
            ])->filter()->toArray();
            
            if (!empty($userData)) {
                $client->user->update($userData);
            }
        }

        \App\Models\ActivityLog::log('updated', $client, 'Client updated: ' . $client->name);

        return response()->json(['success' => true, 'message' => 'Client updated successfully']);
    }

    public function destroy(Client $client)
    {
        \App\Models\ActivityLog::log('deleted', $client, 'Client deleted: ' . $client->name);
        
        $client->delete();
        return response()->json(['success' => true, 'message' => 'Client deleted successfully']);
    }

    public function getProjects(Client $client)
    {
        $projects = $client->projects()->select('id', 'title')->get();
        return response()->json($projects);
    }
}