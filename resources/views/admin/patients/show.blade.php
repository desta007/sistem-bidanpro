@extends('layouts.admin')

@section('title', 'Detail Pasien')
@section('page-title', $patient->name)
@section('page-subtitle', 'NIK: ' . $patient->nik)

@push('styles')
    <style>
        .patient-header {
            display: flex;
            gap: 24px;
            margin-bottom: 24px;
        }

        .patient-avatar-lg {
            width: 120px;
            height: 120px;
            border-radius: 20px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .patient-info-header {
            flex: 1;
        }

        .patient-info-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 8px;
        }

        .patient-meta {
            display: flex;
            gap: 24px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--gray-600);
            font-size: 14px;
        }

        .meta-item i {
            color: var(--primary);
        }

        .patient-actions {
            display: flex;
            gap: 12px;
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
            transition: all 0.2s;
            text-decoration: none;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
        }

        .btn-secondary {
            background: var(--gray-100);
            color: var(--gray-700);
        }

        .btn-success {
            background: linear-gradient(135deg, #10B981 0%, #34D399 100%);
            color: white;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
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
            text-align: right;
        }

        .records-section {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .records-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .records-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--gray-800);
        }

        .records-list {
            padding: 0;
        }

        .record-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 24px;
            border-bottom: 1px solid var(--gray-100);
            transition: background 0.2s;
        }

        .record-item:hover {
            background: var(--gray-50);
        }

        .record-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
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

        .record-icon.umum {
            background: rgba(107, 114, 128, 0.1);
            color: #6B7280;
        }

        .record-info {
            flex: 1;
        }

        .record-info h4 {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 2px;
        }

        .record-info span {
            font-size: 12px;
            color: var(--gray-500);
        }

        .record-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .empty-list {
            text-align: center;
            padding: 40px 20px;
            color: var(--gray-500);
        }

        @media (max-width: 1024px) {
            .info-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .patient-header {
                flex-direction: column;
                text-align: center;
            }

            .patient-avatar-lg {
                margin: 0 auto;
            }

            .patient-meta {
                justify-content: center;
            }

            .patient-actions {
                justify-content: center;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="patient-header">
        <div class="patient-avatar-lg">
            {{ strtoupper(substr($patient->name, 0, 2)) }}
        </div>
        <div class="patient-info-header">
            <h2>{{ $patient->name }}</h2>
            <div class="patient-meta">
                <div class="meta-item">
                    <i class="ri-id-card-line"></i>
                    <span>{{ $patient->nik }}</span>
                </div>
                @if($patient->phone)
                    <div class="meta-item">
                        <i class="ri-phone-line"></i>
                        <span>{{ $patient->phone }}</span>
                    </div>
                @endif
                @if($patient->age)
                    <div class="meta-item">
                        <i class="ri-calendar-line"></i>
                        <span>{{ $patient->age }} tahun</span>
                    </div>
                @endif
                <div class="meta-item">
                    <i class="ri-user-line"></i>
                    <span>{{ $patient->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                </div>
            </div>
            <div class="patient-actions">
                <a href="{{ route('admin.patients.edit', $patient) }}" class="btn btn-secondary">
                    <i class="ri-pencil-line"></i> Edit Data
                </a>
                <a href="{{ route('admin.medical-records.create', ['patient_id' => $patient->id]) }}"
                    class="btn btn-success">
                    <i class="ri-stethoscope-line"></i> Pemeriksaan Baru
                </a>
                <a href="{{ route('admin.queues.create', ['patient_id' => $patient->id]) }}" class="btn btn-primary">
                    <i class="ri-add-circle-line"></i> Tambah Antrean
                </a>
            </div>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-card">
            <h4 class="info-card-title"><i class="ri-user-line"></i> Data Pribadi</h4>
            <div class="info-row">
                <span class="info-label">Tempat Lahir</span>
                <span class="info-value">{{ $patient->birth_place ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal Lahir</span>
                <span class="info-value">{{ $patient->birth_date?->format('d M Y') ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Nama Suami</span>
                <span class="info-value">{{ $patient->husband_name ?? '-' }}</span>
            </div>
        </div>

        <div class="info-card">
            <h4 class="info-card-title"><i class="ri-map-pin-line"></i> Alamat & Kontak</h4>
            <div class="info-row">
                <span class="info-label">Alamat</span>
                <span class="info-value" style="max-width: 200px;">{{ $patient->address ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Telepon</span>
                <span class="info-value">{{ $patient->phone ?? '-' }}</span>
            </div>
        </div>

        <div class="info-card">
            <h4 class="info-card-title"><i class="ri-heart-pulse-line"></i> Data Medis</h4>
            <div class="info-row">
                <span class="info-label">Golongan Darah</span>
                <span class="info-value">{{ $patient->blood_type ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">No. BPJS</span>
                <span class="info-value">{{ $patient->bpjs_number ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Alergi</span>
                <span class="info-value">{{ $patient->allergy ?? 'Tidak ada' }}</span>
            </div>
        </div>
    </div>

    <div class="records-section">
        <div class="records-header">
            <h3 class="records-title">Riwayat Pemeriksaan Terakhir</h3>
            <a href="{{ route('admin.medical-records.index', ['patient_id' => $patient->id]) }}" class="btn btn-secondary"
                style="padding: 8px 16px; font-size: 13px;">
                Lihat Semua
            </a>
        </div>
        <div class="records-list">
            @forelse($patient->medicalRecords as $record)
                <div class="record-item">
                    <div class="record-icon {{ strtolower($record->type) }}">
                        <i class="ri-stethoscope-line"></i>
                    </div>
                    <div class="record-info">
                        <h4>{{ $record->type }}</h4>
                        <span>{{ $record->exam_date->format('d M Y') }} • {{ $record->examiner?->name ?? 'N/A' }}</span>
                    </div>
                    <span class="record-badge" style="background: rgba(233, 30, 140, 0.1); color: var(--primary);">
                        {{ $record->type }}
                    </span>
                </div>
            @empty
                <div class="empty-list">
                    <i class="ri-file-list-line" style="font-size: 32px; margin-bottom: 8px;"></i>
                    <p>Belum ada riwayat pemeriksaan</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection