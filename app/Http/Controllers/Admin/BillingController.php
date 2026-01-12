<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Inventory;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['patient', 'cashier']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('invoice_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('invoice_date', '<=', $request->date_to);
        }

        $invoices = $query->latest('invoice_date')->paginate(15)->withQueryString();

        $stats = [
            'total_today' => Invoice::whereDate('invoice_date', today())->sum('total'),
            'paid_today' => Invoice::whereDate('invoice_date', today())->where('status', 'paid')->sum('total'),
            'pending' => Invoice::where('status', 'pending')->count(),
        ];

        return view('admin.billing.index', compact('invoices', 'stats'));
    }

    public function create(Request $request)
    {
        $patients = Patient::where('is_active', true)->orderBy('name')->get();
        $services = Service::where('is_active', true)->get();
        $inventories = Inventory::where('is_active', true)->where('stock', '>', 0)->get();

        $medicalRecord = $request->get('medical_record_id')
            ? MedicalRecord::with('patient')->find($request->medical_record_id)
            : null;

        return view('admin.billing.form', [
            'invoice' => null,
            'patients' => $patients,
            'services' => $services,
            'inventories' => $inventories,
            'medicalRecord' => $medicalRecord,
            'invoiceNumber' => Invoice::generateNumber(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'medical_record_id' => 'nullable|exists:medical_records,id',
            'invoice_date' => 'required|date',
            'discount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,transfer,bpjs',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.type' => 'required|in:service,product',
            'items.*.id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $subtotal = 0;
        $itemsData = [];

        foreach ($validated['items'] as $item) {
            if ($item['type'] === 'service') {
                $service = Service::find($item['id']);
                $itemsData[] = [
                    'service_id' => $service->id,
                    'item_type' => 'service',
                    'item_name' => $service->name,
                    'quantity' => $item['quantity'],
                    'unit_price' => $service->price,
                    'subtotal' => $service->price * $item['quantity'],
                ];
                $subtotal += $service->price * $item['quantity'];
            } else {
                $inventory = Inventory::find($item['id']);
                $itemsData[] = [
                    'inventory_id' => $inventory->id,
                    'item_type' => 'product',
                    'item_name' => $inventory->name,
                    'quantity' => $item['quantity'],
                    'unit_price' => $inventory->sell_price,
                    'subtotal' => $inventory->sell_price * $item['quantity'],
                ];
                $subtotal += $inventory->sell_price * $item['quantity'];

                // Reduce stock
                $inventory->decrement('stock', $item['quantity']);
            }
        }

        $discount = $validated['discount'] ?? 0;
        $total = $subtotal - $discount;

        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateNumber(),
            'patient_id' => $validated['patient_id'],
            'medical_record_id' => $validated['medical_record_id'],
            'user_id' => auth()->id(),
            'invoice_date' => $validated['invoice_date'],
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
            'paid_amount' => 0,
            'payment_method' => $validated['payment_method'],
            'status' => 'pending',
            'notes' => $validated['notes'],
        ]);

        foreach ($itemsData as $itemData) {
            $invoice->items()->create($itemData);
        }

        return redirect()->route('admin.billing.show', $invoice)
            ->with('success', 'Invoice berhasil dibuat.');
    }

    public function show(Invoice $billing)
    {
        $billing->load(['patient', 'items', 'cashier', 'medicalRecord']);

        return view('admin.billing.show', ['invoice' => $billing]);
    }

    public function markAsPaid(Invoice $invoice)
    {
        $invoice->update([
            'status' => 'paid',
            'paid_amount' => $invoice->total,
        ]);

        return back()->with('success', 'Pembayaran berhasil dicatat.');
    }

    public function print(Invoice $invoice)
    {
        $invoice->load(['patient', 'items', 'cashier']);

        return view('admin.billing.print', compact('invoice'));
    }
}
