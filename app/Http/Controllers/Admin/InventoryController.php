<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Inventory::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->get('low_stock')) {
            $query->whereColumn('stock', '<=', 'min_stock');
        }

        $inventories = $query->orderBy('name')->paginate(15)->withQueryString();

        $stats = [
            'total' => Inventory::where('is_active', true)->count(),
            'low_stock' => Inventory::where('is_active', true)->whereColumn('stock', '<=', 'min_stock')->count(),
            'expired' => Inventory::where('is_active', true)->whereDate('expired_date', '<', today())->count(),
        ];

        return view('admin.inventory.index', compact('inventories', 'stats'));
    }

    public function create()
    {
        return view('admin.inventory.form', ['inventory' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:inventories,code',
            'name' => 'required|string|max:255',
            'category' => 'required|in:obat,alkes,vitamin,lainnya',
            'unit' => 'required|string|max:50',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'buy_price' => 'nullable|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'batch_number' => 'nullable|string|max:100',
            'expired_date' => 'nullable|date',
        ]);

        $inventory = Inventory::create($validated);

        // Log initial stock
        if ($validated['stock'] > 0) {
            InventoryTransaction::create([
                'inventory_id' => $inventory->id,
                'user_id' => auth()->id(),
                'type' => 'in',
                'quantity' => $validated['stock'],
                'stock_before' => 0,
                'stock_after' => $validated['stock'],
                'notes' => 'Stok awal',
            ]);
        }

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Item inventaris berhasil ditambahkan.');
    }

    public function show(Inventory $inventory)
    {
        $transactions = $inventory->transactions()
            ->with('user')
            ->latest()
            ->limit(20)
            ->get();

        return view('admin.inventory.show', compact('inventory', 'transactions'));
    }

    public function edit(Inventory $inventory)
    {
        return view('admin.inventory.form', compact('inventory'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:inventories,code,' . $inventory->id,
            'name' => 'required|string|max:255',
            'category' => 'required|in:obat,alkes,vitamin,lainnya',
            'unit' => 'required|string|max:50',
            'min_stock' => 'required|integer|min:0',
            'buy_price' => 'nullable|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'batch_number' => 'nullable|string|max:100',
            'expired_date' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $inventory->update($validated);

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Data inventaris berhasil diperbarui.');
    }

    public function addStock(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $stockBefore = $inventory->stock;
        $inventory->increment('stock', $validated['quantity']);

        InventoryTransaction::create([
            'inventory_id' => $inventory->id,
            'user_id' => auth()->id(),
            'type' => 'in',
            'quantity' => $validated['quantity'],
            'stock_before' => $stockBefore,
            'stock_after' => $inventory->stock,
            'notes' => $validated['notes'] ?? 'Penambahan stok',
        ]);

        return back()->with('success', 'Stok berhasil ditambahkan.');
    }

    public function reduceStock(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $inventory->stock,
            'notes' => 'nullable|string',
        ]);

        $stockBefore = $inventory->stock;
        $inventory->decrement('stock', $validated['quantity']);

        InventoryTransaction::create([
            'inventory_id' => $inventory->id,
            'user_id' => auth()->id(),
            'type' => 'out',
            'quantity' => $validated['quantity'],
            'stock_before' => $stockBefore,
            'stock_after' => $inventory->stock,
            'notes' => $validated['notes'] ?? 'Pengurangan stok',
        ]);

        return back()->with('success', 'Stok berhasil dikurangi.');
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->update(['is_active' => false]);

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Item berhasil dinonaktifkan.');
    }
}
