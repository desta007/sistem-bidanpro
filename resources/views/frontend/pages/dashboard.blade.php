@extends('frontend.layouts.app')

@section('title', 'Dashboard - BidanPRO')

@push('styles')
    <style>
        .welcome-card {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            margin: 16px;
            padding: 24px;
            border-radius: 20px;
            color: white;
        }

        .welcome-greeting {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 4px;
        }

        .welcome-name {
            font-size: 22px;
            font-weight: 700;
        }

        .welcome-meta {
            display: flex;
            gap: 20px;
            margin-top: 16px;
            font-size: 12px;
            opacity: 0.9;
        }

        .welcome-meta i {
            margin-right: 4px;
        }

        .quick-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            padding: 0 16px 20px;
        }

        .stat-card {
            background: white;
            border-radius: 14px;
            padding: 16px;
            text-align: center;
            box-shadow: var(--shadow-sm);
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--gray-900);
        }

        .stat-value.warning {
            color: var(--warning);
        }

        .stat-label {
            font-size: 11px;
            color: var(--gray-500);
            margin-top: 4px;
        }

        .section {
            padding: 0 16px 24px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--gray-900);
        }

        .section-link {
            font-size: 13px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .appointment-card {
            background: white;
            border-radius: 14px;
            padding: 16px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 10px;
            display: flex;
            gap: 14px;
        }

        .appointment-date {
            width: 50px;
            height: 50px;
            background: var(--primary-bg);
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .appointment-day {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
            line-height: 1;
        }

        .appointment-month {
            font-size: 10px;
            color: var(--primary);
            text-transform: uppercase;
        }

        .appointment-info h4 {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 4px;
        }

        .appointment-info p {
            font-size: 12px;
            color: var(--gray-500);
        }

        .record-card {
            background: white;
            border-radius: 14px;
            padding: 14px 16px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .record-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .record-icon.anc {
            background: rgba(233, 30, 140, 0.1);
            color: var(--primary);
        }

        .record-icon.kb {
            background: rgba(59, 130, 246, 0.1);
            color: #3B82F6;
        }

        .record-icon.imunisasi {
            background: rgba(16, 185, 129, 0.1);
            color: #10B981;
        }

        .record-info {
            flex: 1;
        }

        .record-info h4 {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-800);
        }

        .record-info p {
            font-size: 12px;
            color: var(--gray-500);
        }

        .record-arrow {
            color: var(--gray-400);
            font-size: 20px;
        }

        .action-cards {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .action-card {
            background: white;
            border-radius: 14px;
            padding: 20px;
            text-align: center;
            text-decoration: none;
            box-shadow: var(--shadow-sm);
        }

        .action-card i {
            font-size: 28px;
            color: var(--primary);
            margin-bottom: 8px;
            display: block;
        }

        .action-card span {
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-700);
        }

        .empty-state {
            text-align: center;
            padding: 24px;
            color: var(--gray-500);
            font-size: 13px;
        }
    </style>
@endpush

@section('content')
    <div class="welcome-card">
        <p class="welcome-greeting">Selamat datang kembali 👋</p>
        <h1 class="welcome-name">{{ $patient->name }}</h1>
        <div class="welcome-meta">
            <span><i class="ri-id-card-line"></i> {{ $patient->nik }}</span>
            @if($patient->age)
                <span><i class="ri-calendar-line"></i> {{ $patient->age }} tahun</span>
            @endif
        </div>
    </div>

    <div class="quick-stats">
        <div class="stat-card">
            <div class="stat-value">{{ $upcomingAppointments->count() }}</div>
            <div class="stat-label">Jadwal</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $recentRecords->count() }}</div>
            <div class="stat-label">Rekam Medis</div>
        </div>
        <div class="stat-card">
            <div class="stat-value {{ $pendingInvoices > 0 ? 'warning' : '' }}">{{ $pendingInvoices }}</div>
            <div class="stat-label">Belum Bayar</div>
        </div>
    </div>

    @if($upcomingAppointments->count() > 0)
        <section class="section">
            <div class="section-header">
                <h2 class="section-title">Jadwal Mendatang</h2>
                <a href="{{ route('patient.appointments') }}" class="section-link">Lihat Semua</a>
            </div>
            @foreach($upcomingAppointments as $apt)
                <div class="appointment-card">
                    <div class="appointment-date">
                        <span class="appointment-day">{{ $apt->queue_date->format('d') }}</span>
                        <span class="appointment-month">{{ $apt->queue_date->format('M') }}</span>
                    </div>
                    <div class="appointment-info">
                        <h4>{{ $apt->service_type }}</h4>
                        <p>No. Antrean: {{ $apt->queue_number }}</p>
                    </div>
                </div>
            @endforeach
        </section>
    @endif

    <section class="section">
        <div class="section-header">
            <h2 class="section-title">Riwayat Pemeriksaan</h2>
            <a href="{{ route('patient.records') }}" class="section-link">Lihat Semua</a>
        </div>
        @forelse($recentRecords as $record)
            <a href="{{ route('patient.records.show', $record) }}" class="record-card" style="text-decoration: none;">
                <div class="record-icon {{ strtolower($record->type) }}">
                    <i class="ri-stethoscope-line"></i>
                </div>
                <div class="record-info">
                    <h4>{{ $record->type }}</h4>
                    <p>{{ $record->exam_date->format('d M Y') }}</p>
                </div>
                <i class="ri-arrow-right-s-line record-arrow"></i>
            </a>
        @empty
            <div class="empty-state">
                <p>Belum ada riwayat pemeriksaan</p>
            </div>
        @endforelse
    </section>

    <section class="section">
        <h2 class="section-title" style="margin-bottom: 12px;">Menu Lainnya</h2>
        <div class="action-cards">
            <a href="{{ route('patient.invoices') }}" class="action-card">
                <i class="ri-receipt-line"></i>
                <span>Riwayat Invoice</span>
            </a>
            <a href="{{ route('patient.profile') }}" class="action-card">
                <i class="ri-user-settings-line"></i>
                <span>Edit Profil</span>
            </a>
        </div>
    </section>
@endsection