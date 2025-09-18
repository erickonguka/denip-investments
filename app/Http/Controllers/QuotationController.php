<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Client;
use App\Models\Project;
use App\Models\Invoice;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    public function index()
    {
        $quotations = Quotation::with(['client', 'project'])->latest()->paginate(15);
        $clients = Client::where('status', 'active')->get();
        $projects = Project::where('status', '!=', 'cancelled')->get();
        return view('quotations.index', compact('quotations', 'clients', 'projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'valid_until' => 'required|date|after:today',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $quotation = Quotation::create([
            'quotation_number' => $this->generateQuotationNumber(),
            'client_id' => $validated['client_id'],
            'project_id' => $validated['project_id'] ?? null,
            'valid_until' => $validated['valid_until'] ?? now()->addDays(\App\Helpers\SettingsHelper::quotationValidity()),
            'notes' => $validated['notes'],
        ]);

        foreach ($validated['items'] as $item) {
            QuotationItem::create([
                'quotation_id' => $quotation->id,
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        $quotation->calculateTotals();
        
        \App\Models\ActivityLog::log('created', $quotation, 'Quotation created: ' . $quotation->quotation_number);

        return response()->json(['success' => true, 'message' => 'Quotation created successfully']);
    }

    public function edit(Quotation $quotation)
    {
        if (request()->wantsJson()) {
            $quotation->load('items');
            return response()->json([
                'success' => true,
                'data' => $quotation
            ]);
        }
        
        return view('quotations.edit', compact('quotation'));
    }

    public function update(Request $request, Quotation $quotation)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'valid_until' => 'required|date',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,expired,converted,cancelled',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $quotation->update([
            'client_id' => $validated['client_id'],
            'project_id' => $validated['project_id'] ?? null,
            'valid_until' => $validated['valid_until'],
            'notes' => $validated['notes'],
            'status' => $validated['status'],
        ]);

        $quotation->items()->delete();
        
        foreach ($validated['items'] as $item) {
            QuotationItem::create([
                'quotation_id' => $quotation->id,
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        $quotation->calculateTotals();

        return response()->json(['success' => true, 'message' => 'Quotation updated successfully']);
    }

    public function show(Quotation $quotation)
    {
        $quotation->load(['client', 'project', 'items']);
        return view('quotations.show', compact('quotation'));
    }

    public function convertToInvoice(Quotation $quotation)
    {
        if ($quotation->status !== 'active') {
            return response()->json(['success' => false, 'message' => 'Only active quotations can be converted']);
        }

        $invoice = Invoice::create([
            'invoice_number' => $this->generateInvoiceNumber(),
            'client_id' => $quotation->client_id,
            'project_id' => $quotation->project_id,
            'issue_date' => now(),
            'due_date' => now()->addDays(30),
            'tax_rate' => 0,
            'notes' => 'Converted from quotation: ' . $quotation->quotation_number,
            'status' => 'draft',
        ]);

        foreach ($quotation->items as $item) {
            \App\Models\InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => $item->description,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]);
        }

        $invoice->calculateTotals();
        $quotation->update(['status' => 'converted']);
        
        \App\Models\ActivityLog::log('converted', $quotation, 'Quotation ' . $quotation->quotation_number . ' converted to invoice ' . $invoice->invoice_number);

        return response()->json(['success' => true, 'message' => 'Quotation converted to invoice successfully']);
    }

    public function destroy(Quotation $quotation)
    {
        $quotation->delete();
        return response()->json(['success' => true, 'message' => 'Quotation deleted successfully']);
    }

    private function generateInvoiceNumber(): string
    {
        $prefix = \App\Helpers\SettingsHelper::invoicePrefix();
        $year = date('Y');
        $lastInvoice = \App\Models\Invoice::whereYear('created_at', $year)->latest()->first();
        $number = $lastInvoice ? (int)substr($lastInvoice->invoice_number, -3) + 1 : 1;
        
        return $prefix . $year . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    private function generateQuotationNumber(): string
    {
        $prefix = \App\Helpers\SettingsHelper::quotationPrefix();
        $year = date('Y');
        $lastQuotation = Quotation::whereYear('created_at', $year)->latest()->first();
        $number = $lastQuotation ? (int)substr($lastQuotation->quotation_number, -3) + 1 : 1;
        
        return $prefix . $year . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
}