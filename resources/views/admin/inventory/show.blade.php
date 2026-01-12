@extends('layouts.admin')

@section('title', 'Detail Item')
@section('page-title', $inventory->name)
@section('page-subtitle', 'Kode: ' . $inventory->code)

@push('styles')
    <style>
        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 24px;
        }

        .info-card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            padding: 24px;
        }

        .info-card h4 {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 16px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid var(--gray-100);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: var(--gray-500);
            font-size: 13px;
        }

        .info-value {
            color: var(--gray-800);
            font-size: 13px;
            font-weight: 500;
        }

        .stock-card {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border-radius: 16px;
            padding: 24px;
            color: white;
            text-align: center;
        }

        .stock-card .stock-value {
            font-size: 48px;
            font-weight: 700;
        }

        .stock-card .stock-label {
            font-size: 14px;
            opacity: 0.9;
        }

        .stock-actions {
            display: flex;
            gap: 12px;
            margin-top: 16px;
            justify-content: center;
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

        .btn-white {
            background: white;
            color: var(--primary);
        }

        .btn-secondary {
            background: var(--gray-100);
            color: var(--gray-700);
        }

        .transactions-card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .transactions-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--gray-200);
        }

        .transactions-header h3 {
            font-size: 16px;
            font-weight: 600;
        }

        .transactions-list {
            padding: 0;
        }

        .transaction-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 14px 24px;
            border-bottom: 1px solid var(--gray-100);
        }

        .transaction-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .transaction-icon.in {
            background: rgba(16, 185, 129, 0.1);
            color: #10B981;
        }

        .transaction-icon.out {
            background: rgba(239, 68, 68, 0.1);
            color: #EF4444;
        }

        .transaction-info {
            flex: 1;
        }

        .transaction-info h5 {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-800);
        }

        .transaction-info span {
            font-size: 12px;
            color: var(--gray-500);
        }

        .transaction-qty {
            font-weight: 600;
        }

        .transaction-qty.in {
            color: #10B981;
        }

        .transaction-qty.out {
            color: #EF4444;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 16px;
            padding: 24px;
            width: 400px;
            max-width: 90%;
        }

        .modal-header {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid var(--gray-200);
            border-radius: 8px;
            font-size: 14px;
        }

        .modal-footer {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 20px;
        }
    </style>
@endpush

@section('content')
    <div class="detail-grid">
        <div>
            <div class="info-card">
                <h4><i class="ri-medicine-bottle-line" style="color: var(--primary);"></i> Informasi Item</h4>
                <div class="info-row"><span class="info-label">Kode</span><span
                        class="info-value">{{ $inventory->code }}</span></div>
                <div class="info-row"><span class="info-label">Nama</span><span
                        class="info-value">{{ $inventory->name }}</span></div>
                <div class="info-row"><span class="info-label">Kategori</span><span
                        class="info-value">{{ ucfirst($inventory->category) }}</span></div>
                <div class="info-row"><span class="info-label">Satuan</span><span
                        class="info-value">{{ $inventory->unit }}</span></div>
                <div class="info-row"><span class="info-label">Stok Minimum</span><span
                        class="info-value">{{ $inventory->min_stock }}</span></div>
                <div class="info-row"><span class="info-label">Harga Beli</span><span class="info-value">Rp
                        {{ number_format($inventory->buy_price, 0, ',', '.') }}</span></div>
                <div class="info-row"><span class="info-label">Harga Jual</span><span class="info-value">Rp
                        {{ number_format($inventory->sell_price, 0, ',', '.') }}</span></div>
                <div class="info-row"><span class="info-label">No. Batch</span><span
                        class="info-value">{{ $inventory->batch_number ?? '-' }}</span></div>
                <div class="info-row"><span class="info-label">Expired</span><span
                        class="info-value">{{ $inventory->expired_date?->format('d M Y') ?? '-' }}</span></div>
            </div>
        </div>
        <div>
            <div class="stock-card">
                <div class="stock-value">{{ $inventory->stock }}</div>
                <div class="stock-label">{{ $inventory->unit }} tersedia</div>
                @if($inventory->isLowStock())
                    <div
                        style="margin-top: 10px; padding: 6px 12px; background: rgba(255,255,255,0.2); border-radius: 20px; font-size: 12px;">
                        ⚠️ Stok menipis
                    </div>
                @endif
                <div class="stock-actions">
                    <button class="btn btn-white" onclick="openModal('addStock')"><i class="ri-add-line"></i>
                        Tambah</button>
                    <button class="btn btn-white" onclick="openModal('reduceStock')"><i class="ri-subtract-line"></i>
                        Kurangi</button>
                </div>
            </div>
            <div style="margin-top: 16px;">
                <a href="{{ route('admin.inventory.edit', $inventory) }}" class="btn btn-secondary"
                    style="width: 100%; justify-content: center;">
                    <i class="ri-pencil-line"></i> Edit Item
                </a>
            </div>
        </div>
    </div>

    <div class="transactions-card">
        <div class="transactions-header">
            <h3>Riwayat Transaksi Stok</h3>
        </div>
        <div class="transactions-list">
            @forelse($transactions as $trans)
                <div class="transaction-item">
                    <div class="transaction-icon {{ $trans->type }}">
                        <i class="ri-{{ $trans->type === 'in' ? 'add' : 'subtract' }}-line"></i>
                    </div>
                    <div class="transaction-info">
                        <h5>{{ $trans->notes ?? ($trans->type === 'in' ? 'Penambahan' : 'Pengurangan') }}</h5>
                        <span>{{ $trans->created_at->format('d M Y H:i') }} • {{ $trans->user?->name ?? 'System' }}</span>
                    </div>
                    <span class="transaction-qty {{ $trans->type }}">
                        {{ $trans->type === 'in' ? '+' : '-' }}{{ $trans->quantity }}
                    </span>
                </div>
            @empty
                <div style="text-align: center; padding: 40px; color: var(--gray-500);">
                    Belum ada transaksi
                </div>
            @endforelse
        </div>
    </div>

    <!-- Add Stock Modal -->
    <div class="modal" id="addStockModal">
        <div class="modal-content">
            <div class="modal-header">Tambah Stok</div>
            <form method="POST" action="{{ route('admin.inventory.add-stock', $inventory) }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Jumlah</label>
                    <input type="number" name="quantity" class="form-input" min="1" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Catatan</label>
                    <input type="text" name="notes" class="form-input" placeholder="Pembelian, dll">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addStock')">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reduce Stock Modal -->
    <div class="modal" id="reduceStockModal">
        <div class="modal-content">
            <div class="modal-header">Kurangi Stok</div>
            <form method="POST" action="{{ route('admin.inventory.reduce-stock', $inventory) }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Jumlah (max: {{ $inventory->stock }})</label>
                    <input type="number" name="quantity" class="form-input" min="1" max="{{ $inventory->stock }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Catatan</label>
                    <input type="text" name="notes" class="form-input" placeholder="Rusak, expired, dll">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('reduceStock')">Batal</button>
                    <button type="submit" class="btn btn-primary">Kurangi</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function openModal(type) { document.getElementById(type + 'Modal').classList.add('active'); }
        function closeModal(type) { document.getElementById(type + 'Modal').classList.remove('active'); }
    </script>
@endpush