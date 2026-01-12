@extends('layouts.admin')

@section('title', 'Laporan')
@section('page-title', 'Laporan')
@section('page-subtitle', 'Ringkasan dan analisis data klinik')

@push('styles')
<style>
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px; }
    .stat-card { background: white; border-radius: 16px; padding: 24px; box-shadow: var(--shadow-sm); }
    .stat-card .label { font-size: 13px; color: var(--gray-500); margin-bottom: 8px; }
    .stat-card .value { font-size: 28px; font-weight: 700; color: var(--gray-900); }
    .stat-card .sub { font-size: 12px; color: var(--gray-500); margin-top: 4px; }

    .report-links { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 24px; }
    .report-link { background: white; border-radius: 16px; padding: 24px; box-shadow: var(--shadow-sm); text-decoration: none; transition: all 0.2s; display: flex; align-items: center; gap: 16px; }
    .report-link:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }
    .report-link-icon { width: 56px; height: 56px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 24px; }
    .report-link-icon.daily { background: rgba(233, 30, 140, 0.1); color: var(--primary); }
    .report-link-icon.monthly { background: rgba(59, 130, 246, 0.1); color: #3B82F6; }
    .report-link-icon.cohort { background: rgba(16, 185, 129, 0.1); color: #10B981; }
    .report-link-text h4 { font-size: 16px; font-weight: 600; color: var(--gray-800); margin-bottom: 4px; }
    .report-link-text p { font-size: 13px; color: var(--gray-500); }

    .charts-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; }
    .chart-card { background: white; border-radius: 16px; box-shadow: var(--shadow-sm); padding: 24px; }
    .chart-card h3 { font-size: 16px; font-weight: 600; color: var(--gray-800); margin-bottom: 20px; }

    .bar-chart { display: flex; align-items: flex-end; gap: 12px; height: 200px; }
    .bar-item { flex: 1; display: flex; flex-direction: column; align-items: center; }
    .bar { width: 100%; background: linear-gradient(180deg, var(--primary) 0%, var(--primary-light) 100%); border-radius: 6px 6px 0 0; transition: height 0.3s; }
    .bar-label { font-size: 11px; color: var(--gray-500); margin-top: 8px; }
    .bar-value { font-size: 11px; font-weight: 600; color: var(--gray-700); margin-bottom: 4px; }

    .pie-chart { display: flex; flex-direction: column; gap: 12px; }
    .pie-item { display: flex; align-items: center; gap: 12px; }
    .pie-color { width: 12px; height: 12px; border-radius: 4px; }
    .pie-label { flex: 1; font-size: 13px; color: var(--gray-700); }
    .pie-value { font-size: 13px; font-weight: 600; color: var(--gray-800); }

    @media (max-width: 1024px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .report-links { grid-template-columns: 1fr; }
        .charts-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="label">Pasien Hari Ini</div>
        <div class="value">{{ $stats['patients_today'] }}</div>
    </div>
    <div class="stat-card">
        <div class="label">Pasien Bulan Ini</div>
        <div class="value">{{ $stats['patients_month'] }}</div>
    </div>
    <div class="stat-card">
        <div class="label">Pendapatan Hari Ini</div>
        <div class="value">Rp {{ number_format($stats['revenue_today'], 0, ',', '.') }}</div>
    </div>
    <div class="stat-card">
        <div class="label">Pendapatan Bulan Ini</div>
        <div class="value">Rp {{ number_format($stats['revenue_month'], 0, ',', '.') }}</div>
    </div>
</div>

<div class="report-links">
    <a href="{{ route('admin.reports.daily') }}" class="report-link">
        <div class="report-link-icon daily"><i class="ri-calendar-line"></i></div>
        <div class="report-link-text">
            <h4>Laporan Harian</h4>
            <p>Detail kunjungan dan pendapatan per hari</p>
        </div>
    </a>
    <a href="{{ route('admin.reports.monthly') }}" class="report-link">
        <div class="report-link-icon monthly"><i class="ri-calendar-2-line"></i></div>
        <div class="report-link-text">
            <h4>Laporan Bulanan</h4>
            <p>Rekap aktivitas bulanan</p>
        </div>
    </a>
    <a href="{{ route('admin.reports.cohort') }}" class="report-link">
        <div class="report-link-icon cohort"><i class="ri-file-chart-line"></i></div>
        <div class="report-link-text">
            <h4>Kohort Ibu & Bayi</h4>
            <p>Laporan untuk Dinas Kesehatan</p>
        </div>
    </a>
</div>

<div class="charts-grid">
    <div class="chart-card">
        <h3>Tren Kunjungan 6 Bulan Terakhir</h3>
        <div class="bar-chart">
            @php $maxPatients = max(array_column($monthlyPatients, 'count')) ?: 1; @endphp
            @foreach($monthlyPatients as $data)
            <div class="bar-item">
                <div class="bar-value">{{ $data['count'] }}</div>
                <div class="bar" style="height: {{ ($data['count'] / $maxPatients) * 160 }}px;"></div>
                <div class="bar-label">{{ $data['month'] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="chart-card">
        <h3>Distribusi Layanan</h3>
        <div class="pie-chart">
            @php 
                $colors = ['ANC' => '#E91E8C', 'KB' => '#3B82F6', 'Imunisasi' => '#10B981', 'Umum' => '#6B7280', 'PNC' => '#F59E0B'];
                $totalServices = array_sum($serviceDistribution) ?: 1;
            @endphp
            @foreach($serviceDistribution as $service => $count)
            <div class="pie-item">
                <div class="pie-color" style="background: {{ $colors[$service] ?? '#9CA3AF' }};"></div>
                <span class="pie-label">{{ $service }}</span>
                <span class="pie-value">{{ $count }} ({{ round($count / $totalServices * 100) }}%)</span>
            </div>
            @endforeach
            @if(empty($serviceDistribution))
            <p style="color: var(--gray-500); font-size: 13px;">Belum ada data</p>
            @endif
        </div>
    </div>
</div>
@endsection
