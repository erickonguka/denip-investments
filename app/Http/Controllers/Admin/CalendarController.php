<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Booking;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        $events = [];
        
        // Add all projects
        $projects = Project::with('client')->get();
        foreach ($projects as $project) {
            if ($project->start_date) {
                $events[] = [
                    'title' => $project->title . ' - Start (' . $project->client->name . ')',
                    'start' => $project->start_date->format('Y-m-d'),
                    'color' => '#007bff',
                    'extendedProps' => [
                        'type' => 'project_start',
                        'id' => $project->id
                    ]
                ];
            }
            
            if ($project->end_date) {
                $events[] = [
                    'title' => $project->title . ' - End (' . $project->client->name . ')',
                    'start' => $project->end_date->format('Y-m-d'),
                    'color' => '#6f42c1',
                    'extendedProps' => [
                        'type' => 'project_end',
                        'id' => $project->id
                    ]
                ];
            }
        }
        
        // Add all bookings
        $bookings = Booking::with(['client', 'user'])->get();
        foreach ($bookings as $booking) {
            $colors = [
                'pending' => '#ffc107',
                'approved' => '#28a745',
                'rejected' => '#dc3545'
            ];
            $events[] = [
                'title' => 'Booking - ' . $booking->client->name . ' (' . ucfirst($booking->status) . ')',
                'start' => $booking->date->format('Y-m-d'),
                'color' => $colors[$booking->status] ?? '#6c757d',
                'extendedProps' => [
                    'type' => 'booking',
                    'id' => $booking->id
                ]
            ];
        }
        
        return view('admin.calendar.index', compact('events'));
    }
    
    public function updateBooking(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'notes' => 'nullable|string'
        ]);
        
        $booking->update($validated);
        
        // Send email notification
        \Mail::send('emails.booking-status', [
            'booking' => $booking,
            'status' => $validated['status']
        ], function ($mail) use ($booking, $validated) {
            $mail->to($booking->user->email)
                 ->subject('Booking ' . ucfirst($validated['status']) . ' - Denip Investments');
        });
        
        // Log activity
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'description' => ucfirst($validated['status']) . ' booking for ' . $booking->client->name,
            'model_type' => 'App\Models\Booking',
            'model_id' => $booking->id,
            'ip_address' => request()->ip()
        ]);
        
        return response()->json(['success' => true]);
    }
    
    public function rescheduleBooking(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today'
        ]);
        
        $booking->update([
            'date' => $validated['date'],
            'status' => 'pending'
        ]);
        
        // Send email notification
        \Mail::send('emails.booking-reschedule', [
            'booking' => $booking,
            'newDate' => $validated['date']
        ], function ($mail) use ($booking, $validated) {
            $mail->to($booking->user->email)
                 ->subject('Booking Rescheduled - Denip Investments');
        });
        
        // Log activity
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'description' => 'Rescheduled booking for ' . $booking->client->name . ' to ' . $validated['date'],
            'model_type' => 'App\Models\Booking',
            'model_id' => $booking->id,
            'ip_address' => request()->ip()
        ]);
        
        return response()->json(['success' => true]);
    }
    
    public function bookings()
    {
        $bookings = Booking::with(['client', 'user'])
            ->orderBy('date', 'desc')
            ->paginate(10);
            
        return view('admin.calendar.bookings', compact('bookings'));
    }
    
    public function getBooking(Booking $booking)
    {
        $booking->load(['client', 'user']);
        return response()->json($booking);
    }
}