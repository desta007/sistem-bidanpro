@extends('layouts.admin')

@section('title', 'Laporan Bulanan')
@section('page-title', 'Laporan Bulanan')
@section('page-subtitle', 'Rekap bulan ' . \Carbon\Carbon::parse($month)->format('F Y'))

@push('styles')
    <style>
        .month-picker {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
            align-items: center;
        }

        .month-picker input {
            padding: 10px 16px;
            border: 2px solid var(--gray-200);
            border-radius: 10px;
            font-size: 14px;
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
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
            font-size: 24px;
            font-weight: 700;
            color: var(--gray-900);
        }

        .stat-mini .label {
            font-size: 12px;
            color: var(--gray-500);
            margin-top: 4px;
        }

        .report-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        .report-card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            padding: 24px;
        }

        .report-card h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .breakdown-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .breakdown-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            background: var(--gray-50);
            border-radius: 10px;
        }

        .breakdown-item .label {
            font-size: 14px;
            color: var(--gray-700);
        }

        .breakdown-item .value {
            font-size: 16px;
            font-weight: 600;
            color: var(--primary);
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
    <form method="GET" class="month-picker">
        <label style="font-weight: 500;">Bulan:</label>
        <input type="month" name="month" value="{{ $month }}" onchange="this.form.submit()">
        <a href="{{ route('admin.reports.export', ['type' => 'monthly', 'month' => $month]) }}" class="btn btn-secondary"
            style="margin-left: auto;">
            <i class="ri-download-line"></i> Export
        </a>
    </form>

    <div class="stats-row">
        <div class="stat-mini">
            <div class="value">{{ $stats['total_patients'] }}</div>
            <div class="label">Total Kunjungan</div>
        </div>
        <div class="stat-mini">
            <div class="value">{{ $stats['unique_patients'] }}</div>
            <div class="label">Pasien Unik</div>
        </div>
        <div class="stat-mini">
            <div class="value">{{ $stats['total_examinations'] }}</div>
            <div class="label">Pemeriksaan</div>
        </div>
        <div class="stat-mini">
            <div class="value">Rp {{ number_format($stats['total_revenue'] / 1000000, 1) }}jt</div>
            <div class="label">Pendapatan</div>
        </div>
    </div>

    <div class="report-grid">
        <div class="report-card">
            <h3>Breakdown Layanan</h3>
            <div class="breakdown-list">
                @forelse($serviceBreakdown as $service)
                    <div class="breakdown-item">
                        <span class="label">{{ $service->type }}</span>
                        <span class="value">{{ $service->count }}</span>
                    </div>
                @empty
                    <p style="color: var(--gray-500); text-align: center;">Tidak ada data</p>
                @endforelse
            </div>
        </div>

        <div class="report-card">
            <h3>Kunjungan Harian</h3>
            <div style="max-height: 300px; overflow-y: auto;">
                @forelse($dailyPatients as $day)
                    <div
                        style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--gray-100);">
                        <span
                            style="font-size: 13px; color: var(--gray-600);">{{ \Carbon\Carbon::parse($day->date)->format('d M') }}</span>
                        <span style="font-size: 13px; font-weight: 600; color: var(--gray-800);">{{ $day->count }} pasien</span>
                    </div>
                @empty
                    <p style="color: var(--gray-500); text-align: center;">Tidak ada data</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection