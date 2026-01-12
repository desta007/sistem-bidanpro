@extends('layouts.admin')

@section('title', 'Inventaris')
@section('page-title', 'Inventaris')
@section('page-subtitle', 'Kelola stok obat dan alat kesehatan')

@push('styles')
    <style>
        .stats-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }

        .stat-card-mini {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--shadow-sm);
        }

        .stat-card-mini .label {
            font-size: 13px;
            color: var(--gray-500);
            margin-bottom: 4px;
        }

        .stat-card-mini .value {
            font-size: 24px;
            font-weight: 700;
            color: var(--gray-900);
        }

        .stat-card-mini .value.warning {
            color: #F59E0B;
        }

        .stat-card-mini .value.danger {
            color: #EF4444;
        }

        .filter-bar {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .filter-bar input,
        .filter-bar select {
            padding: 10px 16px;
            border: 2px solid var(--gray-200);
            border-radius: 10px;
            font-size: 14px;
        }

        .search-input {
            min-width: 250px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
        }

        .btn-warning {
            background: #F59E0B;
            color: white;
        }

        .inventory-table {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .inventory-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .inventory-table th {
            padding: 14px 20px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            color: var(--gray-500);
            text-transform: uppercase;
            background: var(--gray-50);
        }

        .inventory-table td {
            padding: 14px 20px;
            border-bottom: 1px solid var(--gray-100);
        }

        .inventory-table tr:hover {
            background: var(--gray-50);
        }

        .item-name {
            font-weight: 600;
            color: var(--gray-800);
        }

        .item-code {
            font-size: 12px;
            color: var(--gray-500);
        }

        .category-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
        }

        .category-badge.obat {
            background: rgba(233, 30, 140, 0.1);
            color: var(--primary);
        }

        .category-badge.alkes {
            background: rgba(59, 130, 246, 0.1);
            color: #3B82F6;
        }

        .category-badge.vitamin {
            background: rgba(16, 185, 129, 0.1);
            color: #10B981;
        }

        .stock-cell {
            font-weight: 600;
        }

        .stock-cell.low {
            color: #F59E0B;
        }

        .stock-cell.out {
            color: #EF4444;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            margin-right: 4px;
        }

        .action-btn.view {
            background: rgba(59, 130, 246, 0.1);
            color: #3B82F6;
        }

        .action-btn.edit {
            background: rgba(233, 30, 140, 0.1);
            color: var(--primary);
        }

        .action-btn:hover {
            opacity: 0.8;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--gray-500);
        }
    </style>
@endpush

@section('content')
    <div class="stats-row">
        <div class="stat-card-mini">
            <div class="label">Total Item</div>
            <div class="value">{{ $stats['total'] }}</div>
        </div>
        <div class="stat-card-mini">
            <div class="label">Stok Menipis</div>
            <div class="value warning">{{ $stats['low_stock'] }}</div>
        </div>
        <div class="stat-card-mini">
            <div class="label">Kadaluarsa</div>
            <div class="value danger">{{ $stats['expired'] }}</div>
        </div>
    </div>

    <form method="GET" class="filter-bar">
        <input type="text" name="search" class="search-input" placeholder="Cari kode atau nama..."
            value="{{ request('search') }}">
        <select name="category" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            <option value="obat" {{ request('category') === 'obat' ? 'selected' : '' }}>Obat</option>
            <option value="alkes" {{ request('category') === 'alkes' ? 'selected' : '' }}>Alkes</option>
            <option value="vitamin" {{ request('category') === 'vitamin' ? 'selected' : '' }}>Vitamin</option>
            <option value="lainnya" {{ request('category') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
        </select>
        @if($stats['low_stock'] > 0)
            <a href="{{ route('admin.inventory.index', ['low_stock' => 1]) }}" class="btn btn-warning">
                <i class="ri-alert-line"></i> Lihat Stok Menipis
            </a>
        @endif
        <a href="{{ route('admin.inventory.create') }}" class="btn btn-primary" style="margin-left: auto;">
            <i class="ri-add-line"></i> Tambah Item
        </a>
    </form>

    <div class="inventory-table">
        @if($inventories->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Harga Jual</th>
                        <th>Expired</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inventories as $item)
                        <tr>
                            <td>
                                <div class="item-name">{{ $item->name }}</div>
                                <div class="item-code">{{ $item->code }}</div>
                            </td>
                            <td><span class="category-badge {{ $item->category }}">{{ ucfirst($item->category) }}</span></td>
                            <td>
                                <span class="stock-cell {{ $item->stock <= 0 ? 'out' : ($item->isLowStock() ? 'low' : '') }}">
                                    {{ $item->stock }} {{ $item->unit }}
                                </span>
                                @if($item->isLowStock())
                                    <i class="ri-alert-line" style="color: #F59E0B; margin-left: 4px;"></i>
                                @endif
                            </td>
                            <td>Rp {{ number_format($item->sell_price, 0, ',', '.') }}</td>
                            <td>
                                @if($item->expired_date)
                                    <span style="color: {{ $item->isExpired() ? '#EF4444' : 'var(--gray-600)' }}">
                                        {{ $item->expired_date->format('d M Y') }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.inventory.show', $item) }}" class="action-btn view" title="Detail"><i
                                        class="ri-eye-line"></i></a>
                                <a href="{{ route('admin.inventory.edit', $item) }}" class="action-btn edit" title="Edit"><i
                                        class="ri-pencil-line"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($inventories->hasPages())
                <div style="padding: 20px; background: var(--gray-50); display: flex; justify-content: center;">
                    {{ $inventories->links() }}</div>
            @endif
        @else
            <div class="empty-state">
                <i class="ri-medicine-bottle-line" style="font-size: 48px; margin-bottom: 12px;"></i>
                <p>Belum ada item inventaris</p>
            </div>
        @endif
    </div>
@endsection