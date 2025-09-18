<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\Proposal;
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

    public function downloadInvoice(Invoice $invoice)
    {
        $pdf = $this->configurePdf()->loadView('pdf.invoice', compact('invoice'));
        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }

    public function downloadQuotation(Quotation $quotation)
    {
        $pdf = $this->configurePdf()->loadView('pdf.quotation', compact('quotation'));
        return $pdf->download('quotation-' . $quotation->quotation_number . '.pdf');
    }

    public function downloadProposal(Proposal $proposal)
    {
        $pdf = $this->configurePdf()->loadView('pdf.proposal', compact('proposal'));
        return $pdf->download('proposal-' . $proposal->proposal_number . '.pdf');
    }

    public function downloadProject(\App\Models\Project $project)
    {
        $pdf = $this->configurePdf()->loadView('pdf.project', compact('project'));
        return $pdf->download('project-' . str_replace(' ', '-', strtolower($project->title)) . '.pdf');
    }
}