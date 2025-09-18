<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\Request;

class ProposalController extends Controller
{
    public function index()
    {
        $proposals = Proposal::with(['client', 'project'])->latest()->paginate(15);
        $clients = Client::where('status', 'active')->get();
        $projects = Project::where('status', '!=', 'cancelled')->get();
        return view('proposals.index', compact('proposals', 'clients', 'projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'estimated_value' => 'nullable|numeric|min:0',
            'valid_until' => 'required|date|after:today',
        ]);

        $validated['proposal_number'] = $this->generateProposalNumber();
        $validated['valid_until'] = $validated['valid_until'] ?? now()->addDays(\App\Helpers\SettingsHelper::proposalValidity());

        $validated['status'] = 'draft'; // Default status
        $proposal = Proposal::create($validated);
        
        \App\Models\ActivityLog::log('created', $proposal, 'Proposal created: ' . $proposal->title);

        return response()->json(['success' => true, 'message' => 'Proposal created successfully']);
    }

    public function edit(Proposal $proposal)
    {
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $proposal
            ]);
        }
        
        return view('proposals.edit', compact('proposal'));
    }

    public function update(Request $request, Proposal $proposal)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'estimated_value' => 'nullable|numeric|min:0',
            'valid_until' => 'required|date',
            'status' => 'required|in:draft,sent,accepted,rejected',
        ]);

        $oldStatus = $proposal->status;
        $proposal->update($validated);
        
        // Send email notification if status changed to sent
        if ($oldStatus !== 'sent' && $validated['status'] === 'sent') {
            \Mail::send('emails.proposal-sent', [
                'proposal' => $proposal,
                'client' => $proposal->client
            ], function ($mail) use ($proposal) {
                $mail->to($proposal->client->email)
                     ->subject('New Proposal - ' . $proposal->title);
            });
        }

        return response()->json(['success' => true, 'message' => 'Proposal updated successfully']);
    }

    public function show(Proposal $proposal)
    {
        $proposal->load(['client', 'project']);
        return view('proposals.show', compact('proposal'));
    }

    public function destroy(Proposal $proposal)
    {
        $proposal->delete();
        return response()->json(['success' => true, 'message' => 'Proposal deleted successfully']);
    }

    private function generateProposalNumber(): string
    {
        $prefix = \App\Helpers\SettingsHelper::proposalPrefix();
        $year = date('Y');
        $lastProposal = Proposal::whereYear('created_at', $year)->latest()->first();
        $number = $lastProposal ? (int)substr($lastProposal->proposal_number, -3) + 1 : 1;
        
        return $prefix . $year . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
}