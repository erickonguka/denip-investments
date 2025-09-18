<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Client;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_id' => 'required|integer',
            'recipient_type' => 'required|string',
            'emails' => 'required|array|max:12',
            'emails.*' => 'email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'attachments' => 'array'
        ]);

        try {
            // Send email to multiple recipients
            Mail::send('emails.message', [
                'subject' => $validated['subject'],
                'messageContent' => $validated['message']
            ], function ($mail) use ($validated) {
                $mail->to($validated['emails'])
                     ->subject($validated['subject'])
                     ->from(config('mail.from.address'), config('mail.from.name'));
                
                // Add attachments
                if (isset($validated['attachments'])) {
                    foreach ($validated['attachments'] as $attachment) {
                        [$type, $id] = explode('-', $attachment);
                        $this->attachDocument($mail, $type, $id);
                    }
                }
            });

            // Log activity
            \App\Models\ActivityLog::log('sent_message', null, 'Message sent to ' . count($validated['emails']) . ' recipients');

            return response()->json(['success' => true, 'message' => 'Message sent successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to send message'], 500);
        }
    }
    
    private function attachDocument($mail, $type, $id)
    {
        try {
            switch ($type) {
                case 'invoice':
                    $invoice = \App\Models\Invoice::find($id);
                    if ($invoice) {
                        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', compact('invoice'));
                        $mail->attachData($pdf->output(), 'invoice-' . $invoice->invoice_number . '.pdf');
                    }
                    break;
                case 'quotation':
                    $quotation = \App\Models\Quotation::find($id);
                    if ($quotation) {
                        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.quotation', compact('quotation'));
                        $mail->attachData($pdf->output(), 'quotation-' . $quotation->quotation_number . '.pdf');
                    }
                    break;
                case 'proposal':
                    $proposal = \App\Models\Proposal::find($id);
                    if ($proposal) {
                        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.proposal', compact('proposal'));
                        $mail->attachData($pdf->output(), 'proposal-' . $proposal->proposal_number . '.pdf');
                    }
                    break;
            }
        } catch (\Exception $e) {
            // Log error but don't fail email send
        }
    }
}