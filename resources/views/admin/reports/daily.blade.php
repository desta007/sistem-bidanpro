@extends('layouts.admin')

@section('title', 'Laporan Harian')
@section('page-title', 'Laporan Harian')
@section('page-subtitle', 'Detail aktivitas tanggal ' . \Carbon\Carbon::parse($date)->format('d M Y'))

@push('styles')
    <style>
        .date-picker {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
            align-items: center;
        }

        .date-picker input {
            padding: 10px 16px;
            border: 2px solid var(--gray-200);
            border-radius: 10px;
            font-size: 14px;
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }

        .stat-mini {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--shadow-sm);
            text-align: center;
        }

        .stat-mini .value {
            font-size: 28px;
            font-weight: 700;
            color: var(--gray-900);
        }

        .stat-mini .label {
            font-size: 13px;
            color: var(--gray-500);
            margin-top: 4px;
        }

        .section-card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 24px;
            overflow: hidden;
        }

        .section-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--gray-200);
        }

        .section-header h3 {
            font-size: 16px;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            padding: 12px 20px;
            text-align: left;
            font-size: 12px;
            color: var(--gray-500);
            background: var(--gray-50);
        }

        td {
            padding: 12px 20px;
            border-bottom: 1px solid var(--gray-100);
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
    </style>
@endpush

@section('content')
    <form method="GET" class="date-picker">
        <label style="font-weight: 500;">Tanggal:</label>
        <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()">
        <a href="{{ route('admin.reports.export', ['type' => 'daily', 'date' => $date]) }}" class="btn btn-secondary"
            style="margin-left: auto;">
            <i class="ri-download-line"></i> Export
        </a>
    </form>

    <div class="stats-row">
        <div class="stat-mini">
            <div class="value">{{ $stats['total_patients'] }}</div>
            <div class="label">Total Pasien</div>
        </div>
        <div class="stat-mini">
            <div class="value">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
            <div class="label">Pendapatan</div>
        </div>
        <div class="stat-mini">
            <div class="value">Rp {{ number_format($stats['pending_payment'], 0, ',', '.') }}</div>
            <div class="label">Belum Dibayar</div>
        </div>
    </div>

    <div class="section-card">
        <div class="section-header">
            <h3>Daftar Kunjungan ({{ $queues->count() }})</h3>
        </div>
        @if($queues->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pasien</th>
                        <th>Layanan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($queues as $queue)
                        <tr>
                            <td>{{ $queue->queue_number }}</td>
                            <td>{{ $queue->patient->name }}</td>
                            <td>{{ $queue->service_type }}</td>
                            <td>{{ ucfirst($queue->status) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="text-align: center; padding: 40px; color: var(--gray-500);">Tidak ada kunjungan</div>
        @endif
    </div>

    <div class="section-card">
        <div class="section-header">
            <h3>Transaksi Invoice ({{ $invoices->count() }})</h3>
        </div>
        @if($invoices->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>No. Invoice</th>
                        <th>Pasien</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->invoice_number }}</td>
                            <td>{{ $invoice->patient->name }}</td>
                            <td>Rp {{ number_format($invoice->total, 0, ',', '.') }}</td>
                            <td>{{ $invoice->status === 'paid' ? 'Lunas' : 'Pending' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="text-align: center; padding: 40px; color: var(--gray-500);">Tidak ada transaksi</div>
        @endif
    </div>
@endsection