@extends('frontend.layouts.app')

@section('title', 'Invoice - BidanPRO')

@push('styles')
    <style>
        .page-header {
            background: white;
            padding: 20px 16px;
            border-bottom: 1px solid var(--gray-200);
        }

        .page-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--gray-900);
        }

        .invoices-list {
            padding: 16px;
        }

        .invoice-card {
            background: white;
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 12px;
            box-shadow: var(--shadow-sm);
            text-decoration: none;
            display: block;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .invoice-number {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-800);
        }

        .invoice-status {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
        }

        .status-paid {
            background: rgba(16, 185, 129, 0.1);
            color: #10B981;
        }

        .status-pending {
            background: rgba(245, 158, 11, 0.1);
            color: #F59E0B;
        }

        .invoice-body {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .invoice-date {
            font-size: 12px;
            color: var(--gray-500);
        }

        .invoice-total {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--gray-500);
        }

        .empty-icon {
            font-size: 48px;
            margin-bottom: 12px;
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <h1 class="page-title">Riwayat Invoice</h1>
    </div>

    <div class="invoices-list">
        @forelse($invoices as $invoice)
            <a href="{{ route('patient.invoices.show', $invoice) }}" class="invoice-card">
                <div class="invoice-header">
                    <span class="invoice-number">{{ $invoice->invoice_number }}</span>
                    <span class="invoice-status status-{{ $invoice->status }}">
                        {{ $invoice->status === 'paid' ? 'Lunas' : 'Belum Bayar' }}
                    </span>
                </div>
                <div class="invoice-body">
                    <span class="invoice-date">{{ $invoice->invoice_date->format('d M Y') }}</span>
                    <span class="invoice-total">Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
                </div>
            </a>
        @empty
            <div class="empty-state">
                <div class="empty-icon">🧾</div>
                <p>Belum ada invoice</p>
            </div>
        @endforelse
    </div>

    @if($invoices->hasPages())
        <div style="padding: 16px; display: flex; justify-content: center;">
            {{ $invoices->links() }}
        </div>
    @endif
@endsection