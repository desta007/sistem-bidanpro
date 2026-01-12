@extends('frontend.layouts.app')

@section('title', 'Jadwal Kunjungan - BidanPRO')

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

    .appointments-list {
        padding: 16px;
    }

    .appointment-card {
        background: white;
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 12px;
        box-shadow: var(--shadow-sm);
        display: flex;
        gap: 14px;
    }

    .appointment-date {
        width: 56px;
        height: 56px;
        background: var(--primary-bg);
        border-radius: 14px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .appointment-day {
        font-size: 20px;
        font-weight: 700;
        color: var(--primary);
        line-height: 1;
    }

    .appointment-month {
        font-size: 11px;
        color: var(--primary);
        text-transform: uppercase;
    }

    .appointment-info {
        flex: 1;
    }

    .appointment-info h4 {
        font-size: 15px;
        font-weight: 600;
        color: var(--gray-800);
        margin-bottom: 4px;
    }

    .appointment-info p {
        font-size: 12px;
        color: var(--gray-500);
    }

    .appointment-status {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 600;
        align-self: flex-start;
    }

    .status-waiting { background: rgba(245, 158, 11, 0.1); color: #F59E0B; }
    .status-done { background: rgba(16, 185, 129, 0.1); color: #10B981; }
    .status-cancelled { background: rgba(239, 68, 68, 0.1); color: #EF4444; }

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
    <h1 class="page-title">Riwayat Kunjungan</h1>
</div>

<div class="appointments-list">
    @forelse($appointments as $apt)
    <div class="appointment-card">
        <div class="appointment-date">
            <span class="appointment-day">{{ $apt->queue_date->format('d') }}</span>
            <span class="appointment-month">{{ $apt->queue_date->format('M') }}</span>
        </div>
        <div class="appointment-info">
            <h4>{{ $apt->service_type }}</h4>
            <p>No. Antrean: {{ $apt->queue_number }}</p>
        </div>
        <span class="appointment-status status-{{ $apt->status }}">
            @switch($apt->status)
                @case('waiting') Menunggu @break
                @case('called') Dipanggil @break
                @case('examining') Diperiksa @break
                @case('done') Selesai @break
                @case('cancelled') Batal @break
            @endswitch
        </span>
    </div>
    @empty
    <div class="empty-state">
        <div class="empty-icon">📅</div>
        <p>Belum ada riwayat kunjungan</p>
    </div>
    @endforelse
</div>

@if($appointments->hasPages())
<div style="padding: 16px; display: flex; justify-content: center;">
    {{ $appointments->links() }}
</div>
@endif
@endsection
