<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use App\Models\Invoice;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_clients' => Client::count(),
            'active_projects' => Project::where('status', 'active')->count(),
            'pending_invoices_amount' => Invoice::where('status', 'sent')->sum('total'),
            'proposals_in_progress' => Proposal::where('status', 'sent')->count(),
            'pending_bookings' => \App\Models\Booking::where('status', 'pending')->count(),
            'approved_bookings' => \App\Models\Booking::where('status', 'approved')->count(),
        ];

        $recent_activities = \App\Models\ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Data for modals
        $clients = Client::where('status', 'active')->get();
        $projects = Project::where('status', 'active')->get();
        $users = User::all();
        $categories = Category::where('is_active', true)->get();
        return view('dashboard.index', compact('stats', 'recent_activities', 'clients', 'projects', 'categories', 'users'));
    }
}