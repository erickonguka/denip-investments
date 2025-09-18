<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['client', 'project'])->latest()->paginate(15);
        $clients = Client::where('status', 'active')->get();
        $projects = Project::where('status', '!=', 'cancelled')->get();
        return view('invoices.index', compact('invoices', 'clients', 'projects'));
    }

    public function create()
    {
        $clients = Client::where('status', 'active')->get();
        $projects = Project::where('status', '!=', 'cancelled')->get();
        $invoiceNumber = $this->generateInvoiceNumber();
        
        return view('invoices.create', compact('clients', 'projects', 'invoiceNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'issue_date' => 'nullable|date',
            'due_date' => 'required|date',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string',
            'status' => 'nullable|string|in:draft,sent,paid,overdue',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $invoice = Invoice::create([
            'invoice_number' => $this->generateInvoiceNumber(),
            'client_id' => $validated['client_id'],
            'project_id' => $validated['project_id'] ?? null,
            'issue_date' => $validated['issue_date'] ?? now(),
            'due_date' => $validated['due_date'] ?? now()->addDays(\App\Helpers\SettingsHelper::paymentTerms()),
            'tax_rate' => $validated['tax_rate'] ?? \App\Helpers\SettingsHelper::taxRate(),
            'notes' => $validated['notes'],
            'status' => $validated['status'] ?? 'draft',
        ]);

        foreach ($validated['items'] as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        $invoice->calculateTotals();
        
        \App\Models\ActivityLog::log('created', $invoice, 'Invoice created: ' . $invoice->invoice_number);

        return response()->json(['success' => true, 'message' => 'Invoice created successfully']);
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['client', 'project', 'items']);
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        if (request()->wantsJson()) {
            $invoice->load('items');
            return response()->json([
                'success' => true,
                'data' => $invoice
            ]);
        }
        
        $clients = Client::where('status', 'active')->get();
        $projects = Project::where('status', '!=', 'cancelled')->get();
        $invoice->load(['items']);
        
        return view('invoices.edit', compact('invoice', 'clients', 'projects'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'due_date' => 'required|date',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string',
            'status' => 'required|in:draft,sent,paid,overdue,cancelled',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $invoice->update([
            'client_id' => $validated['client_id'],
            'project_id' => $validated['project_id'] ?? null,
            'due_date' => $validated['due_date'],
            'tax_rate' => $validated['tax_rate'] ?? 0,
            'notes' => $validated['notes'],
            'status' => $validated['status'],
        ]);

        // Delete existing items and create new ones
        $invoice->items()->delete();
        
        foreach ($validated['items'] as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        $invoice->calculateTotals();

        return response()->json(['success' => true, 'message' => 'Invoice updated successfully']);
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return response()->json(['success' => true, 'message' => 'Invoice deleted successfully']);
    }

    private function generateInvoiceNumber(): string
    {
        $prefix = \App\Helpers\SettingsHelper::invoicePrefix();
        $year = date('Y');
        $lastInvoice = Invoice::whereYear('created_at', $year)->latest()->first();
        $number = $lastInvoice ? (int)substr($lastInvoice->invoice_number, -3) + 1 : 1;
        
        return $prefix . $year . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
}