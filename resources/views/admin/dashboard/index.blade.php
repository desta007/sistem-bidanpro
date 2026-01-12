@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Selamat Datang, ' . auth()->user()->name)
@section('page-subtitle', 'Ringkasan aktivitas klinik Anda hari ini')

@section('content')
<!-- Stats Cards -->
<section class="stats-section">
    <div class="stats-grid">
        <div class="stat-card stat-primary">
            <div class="stat-icon">
                <i class="ri-group-line"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value">{{ $stats['patients_today'] }}</span>
                <span class="stat-label">Pasien Hari Ini</span>
            </div>
        </div>
        <div class="stat-card stat-secondary">
            <div class="stat-icon">
                <i class="ri-user-heart-line"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value">{{ number_format($stats['total_patients']) }}</span>
                <span class="stat-label">Total Pasien</span>
            </div>
        </div>
        <div class="stat-card stat-accent">
            <div class="stat-icon">
                <i class="ri-money-dollar-circle-line"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value">Rp {{ number_format($stats['revenue_today'], 0, ',', '.') }}</span>
                <span class="stat-label">Pendapatan Hari Ini</span>
            </div>
        </div>
        <div class="stat-card stat-success">
            <div class="stat-icon">
                <i class="ri-file-list-3-line"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value">{{ $stats['pending_invoices'] }}</span>
                <span class="stat-label">Invoice Pending</span>
            </div>
        </div>
    </div>
</section>

<!-- Quick Actions -->
<section class="quick-actions">
    <h3 class="section-title">Aksi Cepat</h3>
    <div class="actions-grid">
        <a href="{{ route('admin.patients.create') }}" class="action-btn">
            <div class="action-icon">
                <i class="ri-user-add-line"></i>
            </div>
            <span>Pasien Baru</span>
        </a>
        <a href="{{ route('admin.queues.create') }}" class="action-btn">
            <div class="action-icon">
                <i class="ri-add-circle-line"></i>
            </div>
            <span>Tambah Antrean</span>
        </a>
        <a href="{{ route('admin.medical-records.create') }}" class="action-btn">
            <div class="action-icon">
                <i class="ri-stethoscope-line"></i>
            </div>
            <span>Pemeriksaan</span>
        </a>
        <a href="{{ route('admin.billing.create') }}" class="action-btn">
            <div class="action-icon">
                <i class="ri-receipt-line"></i>
            </div>
            <span>Invoice Baru</span>
        </a>
    </div>
</section>

<!-- Main Grid -->
<div class="main-grid">
    <!-- Today's Queue -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="ri-calendar-check-line"></i>
                Antrean Hari Ini
            </h3>
            <span class="queue-count">{{ $queues->count() }} pasien</span>
        </div>
        <div class="card-body">
            @if($queues->count() > 0)
            <div class="queue-list">
                @foreach($queues as $queue)
                <div class="queue-item {{ $queue->status === 'examining' ? 'active' : '' }}">
                    <span class="queue-number">{{ $queue->queue_number }}</span>
                    <div class="queue-info">
                        <span class="queue-name">{{ $queue->patient->name }}</span>
                        <span class="queue-service">{{ $queue->service_type }}</span>
                    </div>
                    <span class="queue-status {{ $queue->status }}">
                        @switch($queue->status)
                            @case('waiting')
                                Menunggu
                                @break
                            @case('called')
                                Dipanggil
                                @break
                            @case('examining')
                                Diperiksa
                                @break
                            @case('done')
                                Selesai
                                @break
                            @default
                                {{ ucfirst($queue->status) }}
                        @endswitch
                    </span>
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-state" style="text-align: center; padding: 40px 20px; color: #9CA3AF;">
                <i class="ri-calendar-line" style="font-size: 48px; margin-bottom: 12px;"></i>
                <p>Belum ada pasien dalam antrean</p>
            </div>
            @endif
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.queues.index') }}" class="btn-link" style="color: #E91E8C; font-weight: 600; font-size: 14px;">
                Lihat Semua Antrean <i class="ri-arrow-right-line"></i>
            </a>
        </div>
    </div>

    <!-- Upcoming Deliveries (HPL) -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="ri-calendar-heart-line"></i>
                HPL Terdekat
            </h3>
        </div>
        <div class="card-body">
            @if($upcomingDeliveries->count() > 0)
            <div class="schedule-list">
                @foreach($upcomingDeliveries as $record)
                <div class="schedule-item">
                    <div class="schedule-time">
                        <span class="time">{{ $record->hpl->format('d M') }}</span>
                    </div>
                    <div class="schedule-indicator"></div>
                    <div class="schedule-content" style="flex: 1;">
                        <div style="font-weight: 600; color: #374151; font-size: 14px;">{{ $record->patient->name }}</div>
                        <div style="font-size: 12px; color: #9CA3AF;">
                            Usia Kehamilan: {{ $record->pregnancy_week ?? '-' }} minggu
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-state" style="text-align: center; padding: 40px 20px; color: #9CA3AF;">
                <i class="ri-heart-pulse-line" style="font-size: 48px; margin-bottom: 12px;"></i>
                <p>Tidak ada HPL terdekat</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Second Row -->
<div class="main-grid" style="margin-top: 24px;">
    <!-- Low Stock Alert -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="ri-error-warning-line" style="color: #F59E0B;"></i>
                Stok Menipis
            </h3>
        </div>
        <div class="card-body">
            @if($lowStockItems->count() > 0)
            <div style="display: flex; flex-direction: column; gap: 12px;">
                @foreach($lowStockItems as $item)
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background: #FEF3C7; border-radius: 10px;">
                    <div>
                        <div style="font-weight: 600; color: #374151; font-size: 14px;">{{ $item->name }}</div>
                        <div style="font-size: 12px; color: #9CA3AF;">{{ $item->code }}</div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-weight: 700; color: #F59E0B;">{{ $item->stock }} {{ $item->unit }}</div>
                        <div style="font-size: 12px; color: #9CA3AF;">Min: {{ $item->min_stock }}</div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-state" style="text-align: center; padding: 40px 20px; color: #9CA3AF;">
                <i class="ri-checkbox-circle-line" style="font-size: 48px; margin-bottom: 12px; color: #10B981;"></i>
                <p>Semua stok dalam kondisi aman</p>
            </div>
            @endif
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.inventory.index') }}" class="btn-link" style="color: #E91E8C; font-weight: 600; font-size: 14px;">
                Kelola Inventaris <i class="ri-arrow-right-line"></i>
            </a>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="ri-history-line"></i>
                Aktivitas Terbaru
            </h3>
        </div>
        <div class="card-body">
            @if($recentRecords->count() > 0)
            <div style="display: flex; flex-direction: column; gap: 12px;">
                @foreach($recentRecords as $record)
                <div style="display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid #F3F4F6;">
                    <div style="width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; background: rgba(233, 30, 140, 0.1); color: #E91E8C;">
                        <i class="ri-stethoscope-line"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: 600; color: #374151; font-size: 14px;">{{ $record->patient->name }}</div>
                        <div style="font-size: 12px; color: #9CA3AF;">{{ $record->type }} - {{ $record->exam_date->format('d M Y') }}</div>
                    </div>
                    <span style="padding: 4px 10px; background: rgba(233, 30, 140, 0.1); color: #E91E8C; border-radius: 20px; font-size: 11px; font-weight: 600;">
                        {{ $record->type }}
                    </span>
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-state" style="text-align: center; padding: 40px 20px; color: #9CA3AF;">
                <i class="ri-file-list-line" style="font-size: 48px; margin-bottom: 12px;"></i>
                <p>Belum ada aktivitas</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
