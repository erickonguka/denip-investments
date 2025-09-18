<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $user = \Auth::user();
        $client = $user->client;
        
        if (!$client) {
            $invoices = new \Illuminate\Pagination\LengthAwarePaginator(
                collect(),
                0,
                10,
                1,
                ['path' => request()->url()]
            );
        } else {
            $invoices = \App\Models\Invoice::where('client_id', $client->id)
                ->whereIn('status', ['sent', 'paid', 'overdue'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }
        
        return view('client.invoices.index', compact('invoices'));
    }

    public function show($id)
    {
        $user = \Auth::user();
        $client = $user->client;
        
        $invoice = \App\Models\Invoice::where('id', $id)
            ->where('client_id', $client->id)
            ->whereIn('status', ['sent', 'paid', 'overdue'])
            ->firstOrFail();
            
        return view('client.invoices.show', compact('invoice'));
    }
    
    public function markPaid(Request $request, $id)
    {
        $user = \Auth::user();
        $client = $user->client;
        
        $invoice = \App\Models\Invoice::where('id', $id)
            ->where('client_id', $client->id)
            ->where('status', 'sent')
            ->firstOrFail();
            
        $invoice->update(['status' => 'paid']);
        
        // Notify admin
        \Mail::send('emails.invoice-paid', [
            'invoice' => $invoice,
            'client' => $client
        ], function ($mail) use ($invoice) {
            $mail->to(config('mail.admin_email', 'admin@denip.com'))
                 ->subject('Invoice Paid - #' . $invoice->invoice_number);
        });
        
        return response()->json(['success' => true]);
    }
}