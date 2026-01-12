@extends('layouts.admin')

@section('title', 'Kasir & Billing')
@section('page-title', 'Kasir & Billing')
@section('page-subtitle', 'Kelola invoice dan pembayaran')

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

        .stat-card-mini .value.success {
            color: #10B981;
        }

        .stat-card-mini .value.warning {
            color: #F59E0B;
        }

        .filter-bar {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .filter-bar select,
        .filter-bar input {
            padding: 10px 16px;
            border: 2px solid var(--gray-200);
            border-radius: 10px;
            font-size: 14px;
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

        .invoices-table {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .invoices-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoices-table th {
            padding: 14px 20px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            color: var(--gray-500);
            text-transform: uppercase;
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
        }

        .invoices-table td {
            padding: 14px 20px;
            border-bottom: 1px solid var(--gray-100);
        }

        .invoices-table tr:hover {
            background: var(--gray-50);
        }

        .invoice-number {
            font-weight: 600;
            color: var(--gray-800);
        }

        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
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

        .status-badge.cancelled {
            background: rgba(239, 68, 68, 0.1);
            color: #EF4444;
        }

        .amount {
            font-weight: 600;
            color: var(--gray-800);
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(233, 30, 140, 0.1);
            color: var(--primary);
            text-decoration: none;
        }

        .action-btn:hover {
            background: var(--primary);
            color: white;
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
            <div class="label">Total Hari Ini</div>
            <div class="value">Rp {{ number_format($stats['total_today'], 0, ',', '.') }}</div>
        </div>
        <div class="stat-card-mini">
            <div class="label">Sudah Dibayar</div>
            <div class="value success">Rp {{ number_format($stats['paid_today'], 0, ',', '.') }}</div>
        </div>
        <div class="stat-card-mini">
            <div class="label">Invoice Pending</div>
            <div class="value warning">{{ $stats['pending'] }}</div>
        </div>
    </div>

    <form method="GET" class="filter-bar">
        <select name="status" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Lunas</option>
        </select>
        <input type="date" name="date_from" value="{{ request('date_from') }}" onchange="this.form.submit()">
        <input type="date" name="date_to" value="{{ request('date_to') }}" onchange="this.form.submit()">
        <a href="{{ route('admin.billing.create') }}" class="btn btn-primary" style="margin-left: auto;">
            <i class="ri-add-line"></i> Invoice Baru
        </a>
    </form>

    <div class="invoices-table">
        @if($invoices->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>No. Invoice</th>
                        <th>Tanggal</th>
                        <th>Pasien</th>
                        <th>Total</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                        <tr>
                            <td class="invoice-number">{{ $invoice->invoice_number }}</td>
                            <td>{{ $invoice->invoice_date->format('d M Y') }}</td>
                            <td>{{ $invoice->patient->name }}</td>
                            <td class="amount">Rp {{ number_format($invoice->total, 0, ',', '.') }}</td>
                            <td>{{ ucfirst($invoice->payment_method) }}</td>
                            <td>
                                <span class="status-badge {{ $invoice->status }}">
                                    {{ $invoice->status === 'paid' ? 'Lunas' : 'Pending' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.billing.show', $invoice) }}" class="action-btn" title="Detail">
                                    <i class="ri-eye-line"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($invoices->hasPages())
                <div style="padding: 20px; background: var(--gray-50); display: flex; justify-content: center;">
                    {{ $invoices->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <i class="ri-receipt-line" style="font-size: 48px; margin-bottom: 12px;"></i>
                <p>Belum ada invoice</p>
            </div>
        @endif
    </div>
@endsection