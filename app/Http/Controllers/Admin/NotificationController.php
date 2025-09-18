<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

class NotificationController extends Controller
{
    public function index()
    {
        // Get recent activities and messages
        $activities = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        $messages = \App\Models\Message::with(['sender', 'recipient'])
            ->where(function($query) {
                $query->where('recipient_id', auth()->id())
                      ->orWhereNull('recipient_id'); // Include anonymous landing page messages
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        $allNotifications = collect();
        
        // Add activities
        foreach ($activities as $activity) {
            $prefix = '';
            if ($activity->user) {
                $isClient = $activity->user->isClient();
                $prefix = $isClient ? 'Client ' : '';
            }
            $allNotifications->push([
                'id' => 'activity_' . $activity->id,
                'content' => $prefix . ($activity->user->name ?? 'System') . ': ' . $activity->description,
                'time' => $activity->created_at->diffForHumans(),
                'created_at' => $activity->created_at
            ]);
        }
        
        // Add messages
        foreach ($messages as $message) {
            $senderName = $message->sender ? $message->sender->name : ($message->sender_name ?? 'Anonymous');
            $senderPrefix = '';
            if ($message->sender && $message->sender->isClient()) {
                $senderPrefix = 'Client ';
            } elseif (!$message->sender) {
                $senderPrefix = 'Landing Page: ';
            }
            $allNotifications->push([
                'id' => 'message_' . $message->id,
                'content' => $senderPrefix . $senderName . ' sent a message: ' . \Str::limit($message->subject, 30),
                'time' => $message->created_at->diffForHumans(),
                'created_at' => $message->created_at
            ]);
        }
        
        $notifications = $allNotifications->sortByDesc('created_at')->take(10)->values();
        
        return response()->json(['notifications' => $notifications]);
    }
    
    public function clear()
    {
        // Mark all unread messages as read for current user and anonymous messages
        \App\Models\Message::where(function($query) {
                $query->where('recipient_id', auth()->id())
                      ->orWhereNull('recipient_id'); // Include anonymous landing page messages
            })
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
            
        return response()->json(['success' => true]);
    }
    
    public function unreadCount()
    {
        // Count messages where admin is recipient OR messages without recipient (landing page messages)
        $count = \App\Models\Message::where(function($query) {
                $query->where('recipient_id', auth()->id())
                      ->orWhereNull('recipient_id'); // Include anonymous landing page messages
            })
            ->whereNull('read_at')
            ->count();
            
        return response()->json(['count' => $count]);
    }
}