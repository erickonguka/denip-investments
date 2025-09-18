<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientDocumentController extends Controller
{
    public function getDocuments(Client $client)
    {
        $documents = [];
        
        // Get invoices
        foreach ($client->invoices as $invoice) {
            $documents[] = [
                'id' => $invoice->id,
                'type' => 'invoice',
                'title' => 'Invoice ' . $invoice->invoice_number,
                'date' => $invoice->issue_date->format('M j, Y'),
                'icon' => 'file-invoice'
            ];
        }
        
        // Get quotations
        foreach ($client->quotations as $quotation) {
            $documents[] = [
                'id' => $quotation->id,
                'type' => 'quotation',
                'title' => 'Quotation ' . $quotation->quotation_number,
                'date' => $quotation->created_at->format('M j, Y'),
                'icon' => 'calculator'
            ];
        }
        
        // Get proposals
        foreach ($client->proposals as $proposal) {
            $documents[] = [
                'id' => $proposal->id,
                'type' => 'proposal',
                'title' => 'Proposal: ' . $proposal->title,
                'date' => $proposal->created_at->format('M j, Y'),
                'icon' => 'file-contract'
            ];
        }
        
        return response()->json(['documents' => $documents]);
    }
}