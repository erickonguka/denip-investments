<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ActivityLogController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', ActivityLog::class);
        
        $activities = ActivityLog::with('user')
            ->latest()
            ->paginate(20);
            
        return view('activities.index', compact('activities'));
    }
    
    public function destroy(ActivityLog $activityLog)
    {
        $this->authorize('delete', $activityLog);
        
        $activityLog->delete();
        return response()->json(['success' => true, 'message' => 'Activity log deleted successfully']);
    }
}