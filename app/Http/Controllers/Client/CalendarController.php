<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class CalendarController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $client = $user->client;
        
        $events = [];
        
        if ($client) {
            foreach ($client->projects as $project) {
                if ($project->start_date) {
                    $events[] = [
                        'title' => $project->title . ' - Start',
                        'start' => $project->start_date->format('Y-m-d'),
                        'color' => '#28a745',
                        'type' => 'start'
                    ];
                }
                
                if ($project->end_date) {
                    $events[] = [
                        'title' => $project->title . ' - End',
                        'start' => $project->end_date->format('Y-m-d'),
                        'color' => '#dc3545',
                        'type' => 'end'
                    ];
                }
            }
            
            if ($client->bookings) {
                foreach ($client->bookings as $booking) {
                    $colors = [
                        'pending' => '#ffc107',
                        'approved' => '#28a745',
                        'rejected' => '#dc3545'
                    ];
                    $events[] = [
                        'title' => 'Booking - ' . ucfirst($booking->status),
                        'start' => $booking->date->format('Y-m-d'),
                        'color' => $colors[$booking->status] ?? '#6c757d',
                        'type' => 'booking'
                    ];
                }
            }
        }
        
        return view('client.calendar.index', compact('events'));
    }
    
    public function book()
    {
        $user = Auth::user();
        $client = $user->client;
        
        if (!$client) {
            return response()->json(['success' => false, 'message' => 'Client not found']);
        }
        
        $date = request('date');
        
        if (!$date || strtotime($date) < strtotime('today')) {
            return response()->json(['success' => false, 'message' => 'Invalid date']);
        }
        
        $existingBooking = \App\Models\Booking::where('client_id', $client->id)
            ->where('date', $date)
            ->first();
            
        if ($existingBooking) {
            return response()->json(['success' => false, 'message' => 'You already have a booking for this date']);
        }
        
        \App\Models\Booking::create([
            'client_id' => $client->id,
            'user_id' => $user->id,
            'date' => $date,
            'status' => 'pending'
        ]);
        
        return response()->json(['success' => true]);
    }
    
    public function bookings()
    {
        $user = Auth::user();
        $client = $user->client;
        
        if (!$client) {
            $bookings = new LengthAwarePaginator([], 0, 10);
        } else {
            $bookings = $client->bookings()->orderBy('date', 'desc')->paginate(10);
        }
        
        return view('client.calendar.bookings', compact('bookings'));
    }
}