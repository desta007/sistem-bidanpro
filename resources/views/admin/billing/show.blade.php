@extends('layouts.admin')

@section('title', 'Detail Invoice')
@section('page-title', 'Invoice ' . $invoice->invoice_number)
@section('page-subtitle', 'Tanggal: ' . $invoice->invoice_date->format('d M Y'))

@push('styles')
    <style>
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .invoice-title h2 {
            font-size: 24px;
            font-weight: 700;
            color: var(--gray-900);
        }

        .invoice-title p {
            color: var(--gray-500);
        }

        .invoice-actions {
            display: flex;
            gap: 12px;
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

        .btn-secondary {
            background: var(--gray-100);
            color: var(--gray-700);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
        }

        .btn-success {
            background: #10B981;
            color: white;
        }

        .invoice-card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .invoice-section {
            padding: 24px;
            border-bottom: 1px solid var(--gray-100);
        }

        .invoice-section:last-child {
            border-bottom: none;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .info-block h4 {
            font-size: 12px;
            color: var(--gray-500);
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .info-block p {
            font-size: 14px;
            color: var(--gray-800);
            font-weight: 500;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .items-table th {
            padding: 12px 16px;
            text-align: left;
            font-size: 12px;
            color: var(--gray-500);
            background: var(--gray-50);
        }

        .items-table td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--gray-100);
        }

        .items-table .amount {
            text-align: right;
        }

        .summary-section {
            background: var(--gray-50);
            padding: 20px 24px;
        }

        .summary-row {
            display: flex;
            justify-content: flex-end;
            gap: 40px;
            padding: 8px 0;
        }

        .summary-row .label {
            color: var(--gray-600);
            min-width: 100px;
        }

        .summary-row .value {
            font-weight: 600;
            color: var(--gray-800);
            min-width: 150px;
            text-align: right;
        }

        .summary-row.total {
            font-size: 18px;
            border-top: 2px solid var(--gray-300);
            padding-top: 12px;
            margin-top: 8px;
        }

        .summary-row.total .value {
            color: var(--primary);
        }

        .status-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        .status-badge.paid {
            background: rgba(16, 185, 129, 0.1);
            color: #10B981;
        }

        .status-badge.pending {
            background: rgba(245, 158, 11, 0.1);
            color: #F59E0B;
        }

        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="invoice-header">
        <div class="invoice-title">
            <h2>{{ $invoice->invoice_number }}</h2>
            <p>
                <span
                    class="status-badge {{ $invoice->status }}">{{ $invoice->status === 'paid' ? 'LUNAS' : 'PENDING' }}</span>
            </p>
        </div>
        <div class="invoice-actions">
            <a href="{{ route('admin.billing.print', $invoice) }}" class="btn btn-secondary" target="_blank">
                <i class="ri-printer-line"></i> Cetak
            </a>
            @if($invoice->status !== 'paid')
                <form method="POST" action="{{ route('admin.billing.pay', $invoice) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success" onclick="return confirm('Tandai invoice ini sebagai lunas?')">
                        <i class="ri-checkbox-circle-line"></i> Tandai Lunas
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="invoice-card">
        <div class="invoice-section">
            <div class="info-grid">
                <div class="info-block">
                    <h4>Pasien</h4>
                    <p>{{ $invoice->patient->name }}</p>
                    <p style="font-weight: 400; color: var(--gray-500);">{{ $invoice->patient->phone ?? '-' }}</p>
                </div>
                <div class="info-block">
                    <h4>Tanggal Invoice</h4>
                    <p>{{ $invoice->invoice_date->format('d M Y') }}</p>
                </div>
                <div class="info-block">
                    <h4>Metode Pembayaran</h4>
                    <p>{{ ucfirst($invoice->payment_method) }}</p>
                </div>
            </div>
        </div>

        <div class="invoice-section">
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Jenis</th>
                        <th class="amount">Qty</th>
                        <th class="amount">Harga</th>
                        <th class="amount">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $item)
                        <tr>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->item_type === 'service' ? 'Layanan' : 'Produk' }}</td>
                            <td class="amount">{{ $item->quantity }}</td>
                            <td class="amount">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td class="amount">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="summary-section">
            <div class="summary-row">
                <span class="label">Subtotal</span>
                <span class="value">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($invoice->discount > 0)
                <div class="summary-row">
                    <span class="label">Diskon</span>
                    <span class="value">- Rp {{ number_format($invoice->discount, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="summary-row total">
                <span class="label">Total</span>
                <span class="value">Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
            </div>
        </div>

        @if($invoice->notes)
            <div class="invoice-section">
                <h4 style="font-size: 13px; color: var(--gray-500); margin-bottom: 8px;">Catatan</h4>
                <p style="color: var(--gray-700);">{{ $invoice->notes }}</p>
            </div>
        @endif
    </div>
@endsection