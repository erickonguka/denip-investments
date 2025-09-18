<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProposalController extends Controller
{
    public function index()
    {
        $user = \Auth::user();
        $client = $user->client;
        
        if (!$client) {
            $proposals = new \Illuminate\Pagination\LengthAwarePaginator(
                collect(),
                0,
                10,
                1,
                ['path' => request()->url()]
            );
        } else {
            $proposals = \App\Models\Proposal::where('client_id', $client->id)
                ->whereIn('status', ['sent', 'accepted', 'rejected'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }
        
        return view('client.proposals.index', compact('proposals'));
    }

    public function show($id)
    {
        $user = \Auth::user();
        $client = $user->client;
        
        $proposal = \App\Models\Proposal::where('id', $id)
            ->where('client_id', $client->id)
            ->whereIn('status', ['sent', 'accepted', 'rejected'])
            ->firstOrFail();
            
        return view('client.proposals.show', compact('proposal'));
    }
    
    public function updateStatus(Request $request, $id)
    {
        $user = \Auth::user();
        $client = $user->client;
        
        $validated = $request->validate([
            'status' => 'required|in:accepted,rejected'
        ]);
        
        $proposal = \App\Models\Proposal::where('id', $id)
            ->where('client_id', $client->id)
            ->where('status', 'sent')
            ->firstOrFail();
            
        $proposal->update(['status' => $validated['status']]);
        
        // If proposal is accepted, make project active
        if ($validated['status'] === 'accepted' && $proposal->project) {
            $proposal->project->update([
                'status' => 'active',
                'progress' => 5
            ]);
        }
        
        // Notify admin
        \Mail::send('emails.proposal-status', [
            'proposal' => $proposal,
            'client' => $client,
            'status' => $validated['status']
        ], function ($mail) use ($proposal, $validated) {
            $mail->to(config('mail.admin_email', 'admin@denip.com'))
                 ->subject('Proposal ' . ucfirst($validated['status']) . ' - ' . $proposal->title);
        });
        
        return response()->json(['success' => true]);
    }
}