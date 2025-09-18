<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $client = $user->client;
        
        if (!$client) {
            $activities = new \Illuminate\Pagination\LengthAwarePaginator(
                collect(),
                0,
                15,
                1,
                ['path' => request()->url()]
            );
        } else {
            $activities = ActivityLog::with('user')
                ->where(function($query) use ($client, $user) {
                    $query->where('user_id', $user->id)
                          ->orWhere(function($q) use ($client) {
                              $q->where('model_type', 'App\Models\Project')
                                ->whereIn('model_id', $client->projects->pluck('id'))
                                ->orWhere('model_type', 'App\Models\Invoice')
                                ->whereIn('model_id', $client->invoices->pluck('id'))
                                ->orWhere('model_type', 'App\Models\Proposal')
                                ->whereIn('model_id', $client->proposals->pluck('id'));
                          });
                })
                ->orderBy('created_at', 'desc')
                ->paginate(5);
        }
        
        return view('client.activities.index', compact('activities'));
    }
    
    public function recent()
    {
        $user = Auth::user();
        $client = $user->client;
        
        if (!$client) {
            return collect();
        }
        
        return ActivityLog::with('user')
            ->where(function($query) use ($client, $user) {
                $query->where('user_id', $user->id)
                      ->orWhere(function($q) use ($client) {
                          $q->where('model_type', 'App\Models\Project')
                            ->whereIn('model_id', $client->projects->pluck('id'))
                            ->orWhere('model_type', 'App\Models\Invoice')
                            ->whereIn('model_id', $client->invoices->pluck('id'))
                            ->orWhere('model_type', 'App\Models\Proposal')
                            ->whereIn('model_id', $client->proposals->pluck('id'));
                      });
            })
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
    }
}