@extends('layouts.admin')

@section('title', 'Antrean Pasien')
@section('page-title', 'Antrean Pasien')
@section('page-subtitle', 'Kelola antrean pasien hari ini')

@push('styles')
<style>
    .queue-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .date-picker {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .date-picker input {
        padding: 10px 16px;
        border: 2px solid var(--gray-200);
        border-radius: 10px;
        font-size: 14px;
        font-family: inherit;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        border: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: white;
    }

    .btn-sm {
        padding: 8px 14px;
        font-size: 13px;
    }

    .btn-success { background: #10B981; color: white; }
    .btn-warning { background: #F59E0B; color: white; }
    .btn-info { background: #3B82F6; color: white; }
    .btn-secondary { background: var(--gray-200); color: var(--gray-700); }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-mini {
        background: white;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        box-shadow: var(--shadow-sm);
    }

    .stat-mini .number {
        font-size: 32px;
        font-weight: 700;
        color: var(--gray-900);
    }

    .stat-mini .label {
        font-size: 13px;
        color: var(--gray-500);
        margin-top: 4px;
    }

    .stat-mini.waiting .number { color: #F59E0B; }
    .stat-mini.examining .number { color: #3B82F6; }
    .stat-mini.done .number { color: #10B981; }

    .queue-card {
        background: white;
        border-radius: 16px;
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .queue-list {
        padding: 0;
    }

    .queue-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px 24px;
        border-bottom: 1px solid var(--gray-100);
        transition: all 0.2s;
    }

    .queue-item:hover {
        background: var(--gray-50);
    }

    .queue-item.active {
        background: rgba(59, 130, 246, 0.05);
        border-left: 4px solid #3B82F6;
    }

    .queue-item.called {
        background: rgba(16, 185, 129, 0.05);
        border-left: 4px solid #10B981;
    }

    .queue-number-badge {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: 700;
        background: var(--gray-100);
        color: var(--gray-700);
    }

    .queue-item.called .queue-number-badge {
        background: #10B981;
        color: white;
    }

    .queue-item.examining .queue-number-badge {
        background: #3B82F6;
        color: white;
    }

    .queue-patient {
        flex: 1;
    }

    .queue-patient h4 {
        font-size: 15px;
        font-weight: 600;
        color: var(--gray-800);
        margin-bottom: 4px;
    }

    .queue-patient span {
        font-size: 13px;
        color: var(--gray-500);
    }

    .queue-service-badge {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        background: rgba(233, 30, 140, 0.1);
        color: var(--primary);
    }

    .queue-status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .queue-status-badge.waiting { background: rgba(245, 158, 11, 0.1); color: #F59E0B; }
    .queue-status-badge.called { background: rgba(16, 185, 129, 0.1); color: #10B981; }
    .queue-status-badge.examining { background: rgba(59, 130, 246, 0.1); color: #3B82F6; }
    .queue-status-badge.done { background: rgba(107, 114, 128, 0.1); color: #6B7280; }

    .queue-actions {
        display: flex;
        gap: 8px;
    }

    .empty-queue {
        text-align: center;
        padding: 60px 20px;
        color: var(--gray-500);
    }

    @media (max-width: 768px) {
        .stats-row {
            grid-template-columns: repeat(2, 1fr);
        }

        .queue-item {
            flex-wrap: wrap;
        }

        .queue-actions {
            width: 100%;
            justify-content: flex-end;
            margin-top: 12px;
        }
    }
</style>
@endpush

@section('content')
<div class="queue-header">
    <form method="GET" class="date-picker">
        <label style="font-weight: 500; color: var(--gray-700);">Tanggal:</label>
        <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()">
    </form>
    <a href="{{ route('admin.queues.create') }}" class="btn btn-primary">
        <i class="ri-add-circle-line"></i>
        Tambah Antrean
    </a>
</div>

<div class="stats-row">
    <div class="stat-mini">
        <div class="number">{{ $stats['total'] }}</div>
        <div class="label">Total</div>
    </div>
    <div class="stat-mini waiting">
        <div class="number">{{ $stats['waiting'] }}</div>
        <div class="label">Menunggu</div>
    </div>
    <div class="stat-mini examining">
        <div class="number">{{ $stats['examining'] }}</div>
        <div class="label">Diperiksa</div>
    </div>
    <div class="stat-mini done">
        <div class="number">{{ $stats['done'] }}</div>
        <div class="label">Selesai</div>
    </div>
</div>

<div class="queue-card">
    <div class="queue-list">
        @forelse($queues as $queue)
        <div class="queue-item {{ $queue->status }}">
            <div class="queue-number-badge">{{ $queue->queue_number }}</div>
            <div class="queue-patient">
                <h4>{{ $queue->patient->name }}</h4>
                <span>NIK: {{ $queue->patient->nik }}</span>
            </div>
            <span class="queue-service-badge">{{ $queue->service_type }}</span>
            <span class="queue-status-badge {{ $queue->status }}">
                @switch($queue->status)
                    @case('waiting') Menunggu @break
                    @case('called') Dipanggil @break
                    @case('examining') Diperiksa @break
                    @case('done') Selesai @break
                    @default {{ ucfirst($queue->status) }}
                @endswitch
            </span>
            <div class="queue-actions">
                @if($queue->status === 'waiting')
                    <form method="POST" action="{{ route('admin.queues.call', $queue) }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm" title="Panggil">
                            <i class="ri-volume-up-line"></i>
                        </button>
                    </form>
                @endif
                @if($queue->status === 'called')
                    <a href="{{ route('admin.queues.show', $queue) }}" class="btn btn-info btn-sm" title="Periksa">
                        <i class="ri-stethoscope-line"></i>
                    </a>
                @endif
                @if($queue->status === 'examining')
                    <form method="POST" action="{{ route('admin.queues.finish', $queue) }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm" title="Selesai">
                            <i class="ri-check-line"></i> Selesai
                        </button>
                    </form>
                @endif
                @if(in_array($queue->status, ['waiting', 'called']))
                    <form method="POST" action="{{ route('admin.queues.destroy', $queue) }}" style="display: inline;" onsubmit="return confirm('Batalkan antrean ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-secondary btn-sm" title="Batal">
                            <i class="ri-close-line"></i>
                        </button>
                    </form>
                @endif
            </div>
        </div>
        @empty
        <div class="empty-queue">
            <i class="ri-calendar-line" style="font-size: 48px; margin-bottom: 12px;"></i>
            <p>Belum ada antrean untuk tanggal ini</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
