@extends('frontend.layouts.app')

@section('title', 'Antrean Hari Ini - BidanPRO')

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        padding: 24px 16px;
        color: white;
        text-align: center;
    }

    .page-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .current-number {
        font-size: 64px;
        font-weight: 800;
        line-height: 1;
    }

    .current-label {
        font-size: 13px;
        opacity: 0.9;
        margin-top: 8px;
    }

    .queue-stats {
        display: flex;
        justify-content: center;
        gap: 24px;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid rgba(255,255,255,0.2);
    }

    .stat-item {
        text-align: center;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 700;
    }

    .stat-label {
        font-size: 11px;
        opacity: 0.8;
    }

    .queue-section {
        padding: 20px 16px;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--gray-900);
    }

    .refresh-btn {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        background: var(--gray-100);
        border: none;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 600;
        color: var(--gray-600);
        cursor: pointer;
    }

    .queue-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .queue-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 16px;
        background: white;
        border-radius: 14px;
        box-shadow: var(--shadow-sm);
    }

    .queue-item.active {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: white;
    }

    .queue-number {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: var(--gray-100);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: 700;
        color: var(--gray-700);
    }

    .queue-item.active .queue-number {
        background: rgba(255,255,255,0.2);
        color: white;
    }

    .queue-info {
        flex: 1;
    }

    .queue-name {
        font-size: 14px;
        font-weight: 600;
    }

    .queue-service {
        font-size: 12px;
        opacity: 0.7;
    }

    .queue-status {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 600;
    }

    .queue-status.waiting {
        background: rgba(245, 158, 11, 0.1);
        color: #F59E0B;
    }

    .queue-status.called {
        background: rgba(16, 185, 129, 0.1);
        color: #10B981;
    }

    .queue-item.active .queue-status {
        background: rgba(255,255,255,0.2);
        color: white;
    }

    .empty-queue {
        text-align: center;
        padding: 40px 20px;
        color: var(--gray-500);
    }

    .empty-icon {
        font-size: 48px;
        margin-bottom: 12px;
    }

    .info-card {
        margin: 0 16px 24px;
        padding: 16px;
        background: rgba(59, 130, 246, 0.1);
        border-radius: 14px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .info-card i {
        font-size: 20px;
        color: #3B82F6;
        margin-top: 2px;
    }

    .info-card p {
        font-size: 13px;
        color: #1D4ED8;
        line-height: 1.5;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="page-title">Antrean Hari Ini</div>
    <div class="current-number">{{ str_pad($currentNumber, 2, '0', STR_PAD_LEFT) }}</div>
    <div class="current-label">Nomor Sedang Dilayani</div>
    
    <div class="queue-stats">
        <div class="stat-item">
            <div class="stat-value">{{ $waitingCount }}</div>
            <div class="stat-label">Menunggu</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $queues->count() }}</div>
            <div class="stat-label">Total</div>
        </div>
    </div>
</div>

<div class="info-card">
    <i class="ri-information-line"></i>
    <p>Antrean diperbarui otomatis. Harap datang 15 menit sebelum nomor Anda dipanggil.</p>
</div>

<section class="queue-section">
    <div class="section-header">
        <h2 class="section-title">Daftar Antrean</h2>
        <button class="refresh-btn" onclick="location.reload()">
            <i class="ri-refresh-line"></i> Refresh
        </button>
    </div>

    <div class="queue-list">
        @forelse($queues as $queue)
        <div class="queue-item {{ in_array($queue->status, ['called', 'examining']) ? 'active' : '' }}">
            <div class="queue-number">{{ str_pad($queue->queue_number, 2, '0', STR_PAD_LEFT) }}</div>
            <div class="queue-info">
                <div class="queue-name">{{ $queue->patient->name }}</div>
                <div class="queue-service">{{ $queue->service_type }}</div>
            </div>
            <span class="queue-status {{ $queue->status }}">
                @switch($queue->status)
                    @case('waiting') Menunggu @break
                    @case('called') Dipanggil @break
                    @case('examining') Diperiksa @break
                @endswitch
            </span>
        </div>
        @empty
        <div class="empty-queue">
            <div class="empty-icon">📋</div>
            <p>Belum ada antrean hari ini</p>
        </div>
        @endforelse
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Auto refresh every 30 seconds
    setTimeout(() => location.reload(), 30000);
</script>
@endpush
