@extends('frontend.layouts.app')

@section('title', 'Detail Invoice - BidanPRO')

@push('styles')
    <style>
        .invoice-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            padding: 24px 16px;
            color: white;
            text-align: center;
        }

        .invoice-number {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 8px;
        }

        .invoice-total {
            font-size: 32px;
            font-weight: 800;
        }

        .invoice-status {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 12px;
        }

        .status-paid {
            background: rgba(255, 255, 255, 0.2);
        }

        .status-pending {
            background: rgba(245, 158, 11, 0.3);
        }

        .content-section {
            padding: 16px;
        }

        .info-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 16px;
            box-shadow: var(--shadow-sm);
        }

        .card-title {
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
            font-size: 13px;
            color: var(--gray-500);
        }

        .info-value {
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-800);
        }

        .items-list {
            margin-top: 8px;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid var(--gray-100);
        }

        .item-name {
            font-size: 14px;
            color: var(--gray-800);
        }

        .item-qty {
            font-size: 12px;
            color: var(--gray-500);
        }

        .item-price {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-800);
        }

        .totals-section {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 2px dashed var(--gray-200);
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }

        .total-row.grand {
            font-size: 16px;
            font-weight: 700;
            color: var(--gray-900);
            padding-top: 12px;
            margin-top: 8px;
            border-top: 2px solid var(--gray-300);
        }

        .action-buttons {
            padding: 0 16px 32px;
            display: flex;
            gap: 12px;
        }

        .btn {
            flex: 1;
            padding: 14px 20px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-secondary {
            background: var(--gray-100);
            color: var(--gray-700);
        }

        .btn-success {
            background: linear-gradient(135deg, #10B981 0%, #34D399 100%);
            color: white;
        }
    </style>
@endpush

@section('content')
    <div class="invoice-header">
        <p class="invoice-number">{{ $invoice->invoice_number }}</p>
        <h1 class="invoice-total">Rp {{ number_format($invoice->total, 0, ',', '.') }}</h1>
        <span class="invoice-status status-{{ $invoice->status }}">
            {{ $invoice->status === 'paid' ? '✓ LUNAS' : '⏳ BELUM BAYAR' }}
        </span>
    </div>

    <div class="content-section">
        <div class="info-card">
            <h3 class="card-title">Detail Invoice</h3>
            <div class="info-row">
                <span class="info-label">Tanggal</span>
                <span class="info-value">{{ $invoice->invoice_date->format('d M Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Metode Pembayaran</span>
                <span class="info-value">{{ ucfirst($invoice->payment_method) }}</span>
            </div>
        </div>

        <div class="info-card">
            <h3 class="card-title">Item</h3>
            <div class="items-list">
                @foreach($invoice->items as $item)
                    <div class="item-row">
                        <div>
                            <div class="item-name">{{ $item->item_name }}</div>
                            <div class="item-qty">{{ $item->quantity }} x Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                            </div>
                        </div>
                        <span class="item-price">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>

            <div class="totals-section">
                <div class="total-row">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($invoice->discount > 0)
                    <div class="total-row">
                        <span>Diskon</span>
                        <span>- Rp {{ number_format($invoice->discount, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="total-row grand">
                    <span>Total</span>
                    <span>Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="action-buttons">
        <a href="{{ route('patient.invoices') }}" class="btn btn-secondary">
            <i class="ri-arrow-left-line"></i> Kembali
        </a>
        @if($invoice->status === 'pending')
            <a href="https://wa.me/6281234567890?text=Halo, saya ingin membayar invoice {{ $invoice->invoice_number }}"
                class="btn btn-success">
                <i class="ri-whatsapp-line"></i> Bayar via WA
            </a>
        @endif
    </div>
@endsection