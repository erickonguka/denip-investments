<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFController extends Controller
{
    private function configurePdf()
    {
        return Pdf::setOptions([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
            'dpi' => 150,
            'defaultPaperSize' => 'A4',
            'chroot' => [public_path(), storage_path('app/public')],
        ]);
    }

    public function downloadInvoice($id)
    {
        $user = \Auth::user();
        $client = $user->client;
        
        $invoice = Invoice::where('id', $id)
            ->where('client_id', $client->id)
            ->whereIn('status', ['sent', 'paid', 'overdue'])
            ->firstOrFail();
            
        $invoice->load(['client', 'project', 'items']);
        
        $pdf = $this->configurePdf()->loadView('pdf.invoice', compact('invoice'));
        
        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }
    
    public function downloadProposal($id)
    {
        $user = \Auth::user();
        $client = $user->client;
        
        $proposal = Proposal::where('id', $id)
            ->where('client_id', $client->id)
            ->whereIn('status', ['sent', 'accepted', 'rejected'])
            ->firstOrFail();
            
        $proposal->load(['client', 'project']);
        
        $pdf = $this->configurePdf()->loadView('pdf.proposal', compact('proposal'));
        
        return $pdf->download('proposal-' . $proposal->proposal_number . '.pdf');
    }
    
    public function viewInvoice($id)
    {
        $user = \Auth::user();
        $client = $user->client;
        
        $invoice = Invoice::where('id', $id)
            ->where('client_id', $client->id)
            ->whereIn('status', ['sent', 'paid', 'overdue'])
            ->firstOrFail();
            
        $invoice->load(['client', 'project', 'items']);
        
        $pdf = $this->configurePdf()->loadView('pdf.invoice', compact('invoice'));
        
        return $pdf->stream('invoice-' . $invoice->invoice_number . '.pdf');
    }
    
    public function viewProposal($id)
    {
        $user = \Auth::user();
        $client = $user->client;
        
        $proposal = Proposal::where('id', $id)
            ->where('client_id', $client->id)
            ->whereIn('status', ['sent', 'accepted', 'rejected'])
            ->firstOrFail();
            
        $proposal->load(['client', 'project']);
        
        $pdf = $this->configurePdf()->loadView('pdf.proposal', compact('proposal'));
        
        return $pdf->stream('proposal-' . $proposal->proposal_number . '.pdf');
    }
}