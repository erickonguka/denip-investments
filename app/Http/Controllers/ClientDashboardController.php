<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Invoice;
use App\Models\Proposal;
use App\Http\Controllers\Client\ActivityController;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $client = $user->client;
        
        if (!$client) {
            $stats = ['projects' => 0, 'bookings' => 0, 'invoices' => 0, 'messages' => 0];
            $recentProjects = collect();
            $recentActivities = collect();
        } else {
            $stats = [
                'projects' => $client->projects->count(),
                'bookings' => $client->bookings->where('status', 'pending')->count(),
                'invoices' => $client->invoices()->whereIn('status', ['sent', 'paid', 'overdue'])->count(),
                'messages' => \App\Models\Message::where('recipient_id', $user->id)->whereNull('read_at')->count(),
            ];
            
            $recentProjects = $client->projects()->latest()->take(4)->get();
            $recentActivities = (new ActivityController())->recent();
        }
        
        return view('client.dashboard', compact('stats', 'recentProjects', 'recentActivities'));
    }
}