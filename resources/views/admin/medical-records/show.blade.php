@extends('layouts.admin')

@section('title', 'Detail Rekam Medis')
@section('page-title', 'Detail Rekam Medis')
@section('page-subtitle', 'Pemeriksaan ' . $record->type . ' - ' . $record->exam_date->format('d M Y'))

@push('styles')
    <style>
        .record-header {
            display: flex;
            gap: 24px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .record-type-badge {
            width: 80px;
            height: 80px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: white;
            flex-shrink: 0;
        }

        .record-type-badge.anc {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        }

        .record-type-badge.kb {
            background: linear-gradient(135deg, #3B82F6 0%, #60A5FA 100%);
        }

        .record-type-badge.imunisasi {
            background: linear-gradient(135deg, #10B981 0%, #34D399 100%);
        }

        .record-type-badge.umum {
            background: linear-gradient(135deg, #6B7280 0%, #9CA3AF 100%);
        }

        .record-type-badge.pnc {
            background: linear-gradient(135deg, #F59E0B 0%, #FBBF24 100%);
        }

        .record-info h2 {
            font-size: 24px;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 8px;
        }

        .record-meta {
            display: flex;
            gap: 20px;
            color: var(--gray-600);
            font-size: 14px;
        }

        .record-meta i {
            color: var(--primary);
            margin-right: 6px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }

        .info-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: var(--shadow-sm);
        }

        .info-card-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-card-title i {
            color: var(--primary);
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid var(--gray-100);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: var(--gray-500);
            font-size: 13px;
        }

        .info-value {
            color: var(--gray-800);
            font-size: 13px;
            font-weight: 500;
        }

        .diagnosis-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: var(--shadow-sm);
        }

        .diagnosis-section {
            margin-bottom: 20px;
        }

        .diagnosis-section:last-child {
            margin-bottom: 0;
        }

        .diagnosis-section h4 {
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-600);
            margin-bottom: 8px;
        }

        .diagnosis-section p {
            font-size: 14px;
            color: var(--gray-800);
            line-height: 1.6;
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

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
        }

        @media (max-width: 1024px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="record-header">
        <div class="record-type-badge {{ strtolower($record->type) }}">
            <i class="ri-stethoscope-line"></i>
        </div>
        <div class="record-info">
            <h2>Pemeriksaan {{ $record->type }}</h2>
            <div class="record-meta">
                <span><i class="ri-calendar-line"></i>{{ $record->exam_date->format('d M Y') }}</span>
                <span><i class="ri-user-line"></i>{{ $record->patient->name }}</span>
                <span><i class="ri-nurse-line"></i>{{ $record->examiner?->name ?? 'N/A' }}</span>
            </div>
        </div>
        <div style="margin-left: auto; display: flex; gap: 12px;">
            <a href="{{ route('admin.medical-records.edit', $record) }}" class="btn btn-secondary">
                <i class="ri-pencil-line"></i> Edit
            </a>
            <a href="{{ route('admin.billing.create', ['medical_record_id' => $record->id]) }}" class="btn btn-primary">
                <i class="ri-receipt-line"></i> Buat Invoice
            </a>
        </div>
    </div>

    <div class="info-grid">
        <!-- Vital Signs -->
        <div class="info-card">
            <h4 class="info-card-title"><i class="ri-heart-pulse-line"></i> Tanda Vital</h4>
            <div class="info-row">
                <span class="info-label">Tekanan Darah</span>
                <span class="info-value">{{ $record->blood_pressure ?? '-' }} mmHg</span>
            </div>
            <div class="info-row">
                <span class="info-label">Berat Badan</span>
                <span class="info-value">{{ $record->weight ? $record->weight . ' kg' : '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tinggi Badan</span>
                <span class="info-value">{{ $record->height ? $record->height . ' cm' : '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Suhu</span>
                <span class="info-value">{{ $record->temperature ? $record->temperature . ' °C' : '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Denyut Nadi</span>
                <span class="info-value">{{ $record->pulse ? $record->pulse . ' bpm' : '-' }}</span>
            </div>
        </div>

        @if($record->type === 'ANC')
            <!-- ANC Data -->
            <div class="info-card">
                <h4 class="info-card-title"><i class="ri-calendar-heart-line"></i> Data Kehamilan</h4>
                <div class="info-row">
                    <span class="info-label">HPHT</span>
                    <span class="info-value">{{ $record->hpht?->format('d M Y') ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">HPL</span>
                    <span class="info-value">{{ $record->hpl?->format('d M Y') ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Usia Kehamilan</span>
                    <span class="info-value">{{ $record->pregnancy_week ? $record->pregnancy_week . ' minggu' : '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">DJJ</span>
                    <span class="info-value">{{ $record->fetal_heart_rate ? $record->fetal_heart_rate . ' bpm' : '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tinggi Fundus</span>
                    <span class="info-value">{{ $record->fundal_height ? $record->fundal_height . ' cm' : '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Posisi Janin</span>
                    <span class="info-value">{{ ucfirst($record->fetal_position) ?? '-' }}</span>
                </div>
            </div>
        @endif

        @if($record->type === 'KB')
            <div class="info-card">
                <h4 class="info-card-title"><i class="ri-calendar-check-line"></i> Data KB</h4>
                <div class="info-row">
                    <span class="info-label">Metode KB</span>
                    <span class="info-value">{{ $record->kb_method ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Kunjungan Berikutnya</span>
                    <span class="info-value">{{ $record->kb_next_visit?->format('d M Y') ?? '-' }}</span>
                </div>
            </div>
        @endif

        @if($record->type === 'Imunisasi')
            <div class="info-card">
                <h4 class="info-card-title"><i class="ri-syringe-line"></i> Data Imunisasi</h4>
                <div class="info-row">
                    <span class="info-label">Jenis Vaksin</span>
                    <span class="info-value">{{ $record->vaccine_type ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">No. Batch</span>
                    <span class="info-value">{{ $record->vaccine_batch ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Imunisasi Berikutnya</span>
                    <span class="info-value">{{ $record->next_vaccine_date?->format('d M Y') ?? '-' }}</span>
                </div>
            </div>
        @endif

        <!-- Patient Info -->
        <div class="info-card">
            <h4 class="info-card-title"><i class="ri-user-line"></i> Data Pasien</h4>
            <div class="info-row">
                <span class="info-label">Nama</span>
                <span class="info-value">{{ $record->patient->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">NIK</span>
                <span class="info-value">{{ $record->patient->nik }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Usia</span>
                <span class="info-value">{{ $record->patient->age ?? '-' }} tahun</span>
            </div>
            <div class="info-row">
                <span class="info-label">Gol. Darah</span>
                <span class="info-value">{{ $record->patient->blood_type ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Alergi</span>
                <span class="info-value">{{ $record->patient->allergy ?? 'Tidak ada' }}</span>
            </div>
        </div>
    </div>

    <!-- Diagnosis -->
    <div class="diagnosis-card">
        <h4 class="info-card-title"><i class="ri-file-text-line"></i> Anamnesa & Diagnosa</h4>

        <div class="diagnosis-section">
            <h4>Keluhan</h4>
            <p>{{ $record->complaint ?? 'Tidak ada keluhan' }}</p>
        </div>

        <div class="diagnosis-section">
            <h4>Diagnosa</h4>
            <p>{{ $record->diagnosis ?? '-' }} @if($record->icd_code) <code
                style="background: var(--gray-100); padding: 2px 6px; border-radius: 4px; font-size: 12px;">({{ $record->icd_code }})</code>
            @endif</p>
        </div>

        <div class="diagnosis-section">
            <h4>Tindakan</h4>
            <p>{{ $record->treatment ?? '-' }}</p>
        </div>

        @if($record->notes)
            <div class="diagnosis-section">
                <h4>Catatan</h4>
                <p>{{ $record->notes }}</p>
            </div>
        @endif
    </div>
@endsection