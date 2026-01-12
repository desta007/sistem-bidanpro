@extends('frontend.layouts.app')

@section('title', 'Detail Rekam Medis - BidanPRO')

@push('styles')
    <style>
        .record-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            padding: 24px 16px;
            color: white;
        }

        .record-type {
            display: inline-block;
            padding: 6px 14px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .record-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .record-date {
            font-size: 14px;
            opacity: 0.9;
        }

        .content-section {
            padding: 16px;
        }

        .info-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 16px;
            box-shadow: var(--shadow-sm);
        }

        .card-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-title i {
            color: var(--primary);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .info-item {
            text-align: center;
            padding: 12px;
            background: var(--gray-50);
            border-radius: 12px;
        }

        .info-item .value {
            font-size: 18px;
            font-weight: 700;
            color: var(--gray-900);
        }

        .info-item .label {
            font-size: 11px;
            color: var(--gray-500);
            margin-top: 4px;
        }

        .text-content {
            font-size: 14px;
            color: var(--gray-700);
            line-height: 1.7;
        }

        .text-content .label {
            font-size: 12px;
            font-weight: 600;
            color: var(--gray-500);
            margin-bottom: 4px;
        }

        .text-block {
            margin-bottom: 16px;
        }

        .text-block:last-child {
            margin-bottom: 0;
        }

        .action-buttons {
            padding: 0 16px 32px;
            display: flex;
            gap: 12px;
        }

        .btn {
            flex: 1;
            padding: 14px 20px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-secondary {
            background: var(--gray-100);
            color: var(--gray-700);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
        }
    </style>
@endpush

@section('content')
    <div class="record-header">
        <span class="record-type">{{ $record->type }}</span>
        <h1 class="record-title">Pemeriksaan {{ $record->type }}</h1>
        <p class="record-date"><i class="ri-calendar-line"></i> {{ $record->exam_date->format('d F Y') }}</p>
    </div>

    <div class="content-section">
        <!-- Vital Signs -->
        <div class="info-card">
            <h3 class="card-title"><i class="ri-heart-pulse-line"></i> Tanda Vital</h3>
            <div class="info-grid">
                <div class="info-item">
                    <div class="value">{{ $record->blood_pressure ?? '-' }}</div>
                    <div class="label">Tekanan Darah</div>
                </div>
                <div class="info-item">
                    <div class="value">{{ $record->weight ? $record->weight . ' kg' : '-' }}</div>
                    <div class="label">Berat Badan</div>
                </div>
                <div class="info-item">
                    <div class="value">{{ $record->temperature ? $record->temperature . '°C' : '-' }}</div>
                    <div class="label">Suhu</div>
                </div>
                <div class="info-item">
                    <div class="value">{{ $record->pulse ? $record->pulse . ' bpm' : '-' }}</div>
                    <div class="label">Nadi</div>
                </div>
            </div>
        </div>

        @if($record->type === 'ANC')
            <!-- ANC Data -->
            <div class="info-card">
                <h3 class="card-title"><i class="ri-heart-3-line"></i> Data Kehamilan</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="value">{{ $record->pregnancy_week ?? '-' }}</div>
                        <div class="label">Usia Kehamilan (minggu)</div>
                    </div>
                    <div class="info-item">
                        <div class="value">{{ $record->fetal_heart_rate ?? '-' }}</div>
                        <div class="label">DJJ (bpm)</div>
                    </div>
                    <div class="info-item">
                        <div class="value">{{ $record->hpl?->format('d/m/Y') ?? '-' }}</div>
                        <div class="label">HPL</div>
                    </div>
                    <div class="info-item">
                        <div class="value">{{ ucfirst($record->fetal_position) ?? '-' }}</div>
                        <div class="label">Posisi Janin</div>
                    </div>
                </div>
            </div>
        @endif

        @if($record->type === 'KB')
            <div class="info-card">
                <h3 class="card-title"><i class="ri-calendar-check-line"></i> Data KB</h3>
                <div class="text-content">
                    <div class="text-block">
                        <div class="label">Metode KB</div>
                        <div>{{ $record->kb_method ?? '-' }}</div>
                    </div>
                    <div class="text-block">
                        <div class="label">Kunjungan Berikutnya</div>
                        <div>{{ $record->kb_next_visit?->format('d F Y') ?? '-' }}</div>
                    </div>
                </div>
            </div>
        @endif

        @if($record->type === 'Imunisasi')
            <div class="info-card">
                <h3 class="card-title"><i class="ri-syringe-line"></i> Data Imunisasi</h3>
                <div class="text-content">
                    <div class="text-block">
                        <div class="label">Jenis Vaksin</div>
                        <div>{{ $record->vaccine_type ?? '-' }}</div>
                    </div>
                    <div class="text-block">
                        <div class="label">No. Batch</div>
                        <div>{{ $record->vaccine_batch ?? '-' }}</div>
                    </div>
                    <div class="text-block">
                        <div class="label">Jadwal Berikutnya</div>
                        <div>{{ $record->next_vaccine_date?->format('d F Y') ?? '-' }}</div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Diagnosis -->
        <div class="info-card">
            <h3 class="card-title"><i class="ri-file-text-line"></i> Diagnosis & Tindakan</h3>
            <div class="text-content">
                @if($record->complaint)
                    <div class="text-block">
                        <div class="label">Keluhan</div>
                        <div>{{ $record->complaint }}</div>
                    </div>
                @endif
                @if($record->diagnosis)
                    <div class="text-block">
                        <div class="label">Diagnosis</div>
                        <div>{{ $record->diagnosis }}</div>
                    </div>
                @endif
                @if($record->treatment)
                    <div class="text-block">
                        <div class="label">Tindakan</div>
                        <div>{{ $record->treatment }}</div>
                    </div>
                @endif
                @if($record->notes)
                    <div class="text-block">
                        <div class="label">Catatan</div>
                        <div>{{ $record->notes }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Examiner -->
        <div class="info-card">
            <h3 class="card-title"><i class="ri-nurse-line"></i> Pemeriksa</h3>
            <div class="text-content">
                {{ $record->examiner?->name ?? 'Bidan' }}
            </div>
        </div>
    </div>

    <div class="action-buttons">
        <a href="{{ route('patient.records') }}" class="btn btn-secondary">
            <i class="ri-arrow-left-line"></i> Kembali
        </a>
        <a href="https://wa.me/6281234567890?text=Halo, saya ingin konsultasi tentang hasil pemeriksaan tanggal {{ $record->exam_date->format('d/m/Y') }}"
            class="btn btn-primary">
            <i class="ri-whatsapp-line"></i> Konsultasi
        </a>
    </div>
@endsection