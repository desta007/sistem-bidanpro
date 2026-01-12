@extends('layouts.admin')

@section('title', $record ? 'Edit Rekam Medis' : 'Pemeriksaan Baru')
@section('page-title', $record ? 'Edit Rekam Medis' : 'Pemeriksaan Baru')
@section('page-subtitle', $patient ? 'Pasien: ' . $patient->name : 'Buat catatan pemeriksaan')

@push('styles')
    <style>
        .form-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
        }

        .form-card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
        }

        .form-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--gray-200);
        }

        .form-header h3 {
            font-size: 16px;
            font-weight: 600;
            color: var(--gray-800);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-header h3 i {
            color: var(--primary);
        }

        .form-body {
            padding: 24px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 6px;
        }

        .form-label .required {
            color: var(--danger);
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid var(--gray-200);
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            outline: none;
            transition: all 0.2s;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(233, 30, 140, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .form-row-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .type-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .type-tab {
            padding: 10px 18px;
            border: 2px solid var(--gray-200);
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            background: white;
        }

        .type-tab:hover {
            border-color: var(--primary);
        }

        .type-tab.active {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            border-color: transparent;
        }

        .type-section {
            display: none;
            padding: 20px;
            background: var(--gray-50);
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .type-section.active {
            display: block;
        }

        .patient-info-card {
            padding: 20px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border-radius: 12px;
            color: white;
            margin-bottom: 20px;
        }

        .patient-info-card h4 {
            font-size: 18px;
            margin-bottom: 8px;
        }

        .patient-info-card p {
            font-size: 13px;
            opacity: 0.9;
        }

        .form-footer {
            padding: 20px 24px;
            border-top: 1px solid var(--gray-200);
            display: flex;
            gap: 12px;
            justify-content: flex-end;
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
            .form-container {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {

            .form-row,
            .form-row-3 {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <form method="POST"
        action="{{ $record ? route('admin.medical-records.update', $record) : route('admin.medical-records.store') }}">
        @csrf
        @if($record) @method('PUT') @endif
        @if($queueId) <input type="hidden" name="queue_id" value="{{ $queueId }}"> @endif

        <div class="form-container">
            <!-- Main Form -->
            <div class="form-card">
                <div class="form-header">
                    <h3><i class="ri-stethoscope-line"></i> Data Pemeriksaan</h3>
                </div>
                <div class="form-body">
                    <!-- Basic Info -->
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Pasien <span class="required">*</span></label>
                            <select name="patient_id" class="form-select" required {{ $record ? 'disabled' : '' }}>
                                <option value="">Pilih pasien...</option>
                                @foreach($patients as $p)
                                    <option value="{{ $p->id }}" {{ ($selectedPatient ?? $record?->patient_id) == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }} ({{ $p->nik }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tanggal Periksa <span class="required">*</span></label>
                            <input type="date" name="exam_date" class="form-input"
                                value="{{ old('exam_date', $record?->exam_date?->format('Y-m-d') ?? date('Y-m-d')) }}"
                                required>
                        </div>
                    </div>

                    <!-- Type Selector -->
                    <label class="form-label">Jenis Pemeriksaan <span class="required">*</span></label>
                    <div class="type-tabs">
                        @foreach(['ANC' => 'ANC (Kehamilan)', 'KB' => 'KB', 'Imunisasi' => 'Imunisasi', 'Umum' => 'Umum', 'PNC' => 'PNC'] as $val => $label)
                            <label class="type-tab {{ ($type ?? 'Umum') === $val ? 'active' : '' }}">
                                <input type="radio" name="type" value="{{ $val }}" style="display: none;" {{ ($type ?? $record?->type ?? 'Umum') === $val ? 'checked' : '' }}
                                    onchange="showTypeSection('{{ strtolower($val) }}')">
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>

                    <!-- Vital Signs -->
                    <h4 style="font-size: 14px; font-weight: 600; color: var(--gray-700); margin: 20px 0 12px;">Tanda Vital
                    </h4>
                    <div class="form-row-3">
                        <div class="form-group">
                            <label class="form-label">Tekanan Darah (mmHg)</label>
                            <div style="display: flex; gap: 8px; align-items: center;">
                                <input type="number" name="blood_pressure_systolic" class="form-input"
                                    placeholder="Sistolik"
                                    value="{{ old('blood_pressure_systolic', $record?->blood_pressure_systolic) }}">
                                <span>/</span>
                                <input type="number" name="blood_pressure_diastolic" class="form-input"
                                    placeholder="Diastolik"
                                    value="{{ old('blood_pressure_diastolic', $record?->blood_pressure_diastolic) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Berat Badan (kg)</label>
                            <input type="number" step="0.1" name="weight" class="form-input"
                                value="{{ old('weight', $record?->weight) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tinggi Badan (cm)</label>
                            <input type="number" step="0.1" name="height" class="form-input"
                                value="{{ old('height', $record?->height) }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Suhu Tubuh (°C)</label>
                            <input type="number" step="0.1" name="temperature" class="form-input"
                                value="{{ old('temperature', $record?->temperature) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Denyut Nadi (bpm)</label>
                            <input type="number" name="pulse" class="form-input"
                                value="{{ old('pulse', $record?->pulse) }}">
                        </div>
                    </div>

                    <!-- ANC Section -->
                    <div class="type-section" id="section-anc">
                        <h4 style="font-size: 14px; font-weight: 600; margin-bottom: 12px;"><i
                                class="ri-heart-pulse-line"></i> Data Kehamilan (ANC)</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">HPHT (Hari Pertama Haid Terakhir)</label>
                                <input type="date" name="hpht" class="form-input"
                                    value="{{ old('hpht', $record?->hpht?->format('Y-m-d')) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Usia Kehamilan (minggu)</label>
                                <input type="number" name="pregnancy_week" class="form-input"
                                    value="{{ old('pregnancy_week', $record?->pregnancy_week) }}">
                            </div>
                        </div>
                        <div class="form-row-3">
                            <div class="form-group">
                                <label class="form-label">DJJ (Detak Jantung Janin)</label>
                                <input type="number" name="fetal_heart_rate" class="form-input"
                                    value="{{ old('fetal_heart_rate', $record?->fetal_heart_rate) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tinggi Fundus (cm)</label>
                                <input type="number" step="0.1" name="fundal_height" class="form-input"
                                    value="{{ old('fundal_height', $record?->fundal_height) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Posisi Janin</label>
                                <select name="fetal_position" class="form-select">
                                    <option value="">Pilih...</option>
                                    <option value="kepala" {{ old('fetal_position', $record?->fetal_position) === 'kepala' ? 'selected' : '' }}>Kepala</option>
                                    <option value="sungsang" {{ old('fetal_position', $record?->fetal_position) === 'sungsang' ? 'selected' : '' }}>Sungsang</option>
                                    <option value="lintang" {{ old('fetal_position', $record?->fetal_position) === 'lintang' ? 'selected' : '' }}>Lintang</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- KB Section -->
                    <div class="type-section" id="section-kb">
                        <h4 style="font-size: 14px; font-weight: 600; margin-bottom: 12px;"><i
                                class="ri-calendar-check-line"></i> Data KB</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Metode KB</label>
                                <select name="kb_method" class="form-select">
                                    <option value="">Pilih...</option>
                                    <option value="Suntik 1 Bulan" {{ old('kb_method', $record?->kb_method) === 'Suntik 1 Bulan' ? 'selected' : '' }}>Suntik 1 Bulan</option>
                                    <option value="Suntik 3 Bulan" {{ old('kb_method', $record?->kb_method) === 'Suntik 3 Bulan' ? 'selected' : '' }}>Suntik 3 Bulan</option>
                                    <option value="Pil" {{ old('kb_method', $record?->kb_method) === 'Pil' ? 'selected' : '' }}>Pil</option>
                                    <option value="IUD" {{ old('kb_method', $record?->kb_method) === 'IUD' ? 'selected' : '' }}>IUD</option>
                                    <option value="Implan" {{ old('kb_method', $record?->kb_method) === 'Implan' ? 'selected' : '' }}>Implan</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Jadwal Kunjungan Berikutnya</label>
                                <input type="date" name="kb_next_visit" class="form-input"
                                    value="{{ old('kb_next_visit', $record?->kb_next_visit?->format('Y-m-d')) }}">
                            </div>
                        </div>
                    </div>

                    <!-- Imunisasi Section -->
                    <div class="type-section" id="section-imunisasi">
                        <h4 style="font-size: 14px; font-weight: 600; margin-bottom: 12px;"><i class="ri-syringe-line"></i>
                            Data Imunisasi</h4>
                        <div class="form-row-3">
                            <div class="form-group">
                                <label class="form-label">Jenis Vaksin</label>
                                <select name="vaccine_type" class="form-select">
                                    <option value="">Pilih...</option>
                                    <option value="BCG">BCG</option>
                                    <option value="DPT">DPT</option>
                                    <option value="Polio">Polio</option>
                                    <option value="Hepatitis B">Hepatitis B</option>
                                    <option value="Campak">Campak</option>
                                    <option value="MR">MR</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">No. Batch Vaksin</label>
                                <input type="text" name="vaccine_batch" class="form-input"
                                    value="{{ old('vaccine_batch', $record?->vaccine_batch) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Jadwal Imunisasi Berikutnya</label>
                                <input type="date" name="next_vaccine_date" class="form-input"
                                    value="{{ old('next_vaccine_date', $record?->next_vaccine_date?->format('Y-m-d')) }}">
                            </div>
                        </div>
                    </div>

                    <!-- Diagnosis -->
                    <h4 style="font-size: 14px; font-weight: 600; color: var(--gray-700); margin: 20px 0 12px;">Anamnesa &
                        Diagnosa</h4>
                    <div class="form-group">
                        <label class="form-label">Keluhan</label>
                        <textarea name="complaint" class="form-textarea"
                            rows="2">{{ old('complaint', $record?->complaint) }}</textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Diagnosa</label>
                            <input type="text" name="diagnosis" class="form-input"
                                value="{{ old('diagnosis', $record?->diagnosis) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kode ICD-10</label>
                            <input type="text" name="icd_code" class="form-input"
                                value="{{ old('icd_code', $record?->icd_code) }}" placeholder="Z34.0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tindakan</label>
                        <textarea name="treatment" class="form-textarea"
                            rows="2">{{ old('treatment', $record?->treatment) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-textarea" rows="2">{{ old('notes', $record?->notes) }}</textarea>
                    </div>
                </div>
                <div class="form-footer">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary"><i class="ri-arrow-left-line"></i>
                        Kembali</a>
                    <button type="submit" class="btn btn-primary"><i class="ri-save-line"></i> Simpan</button>
                </div>
            </div>

            <!-- Sidebar -->
            <div>
                @if($patient)
                    <div class="form-card" style="margin-bottom: 20px;">
                        <div class="patient-info-card">
                            <h4>{{ $patient->name }}</h4>
                            <p>NIK: {{ $patient->nik }}</p>
                            <p>{{ $patient->gender === 'P' ? 'Perempuan' : 'Laki-laki' }} • {{ $patient->age ?? '-' }} tahun</p>
                        </div>
                        <div style="padding: 20px;">
                            <div style="font-size: 13px; color: var(--gray-600); margin-bottom: 8px;">
                                <strong>Golongan Darah:</strong> {{ $patient->blood_type ?? '-' }}
                            </div>
                            <div style="font-size: 13px; color: var(--gray-600); margin-bottom: 8px;">
                                <strong>BPJS:</strong> {{ $patient->bpjs_number ?? '-' }}
                            </div>
                            <div style="font-size: 13px; color: var(--gray-600);">
                                <strong>Alergi:</strong> {{ $patient->allergy ?? 'Tidak ada' }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        function showTypeSection(type) {
            document.querySelectorAll('.type-section').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.type-tab').forEach(el => el.classList.remove('active'));

            const section = document.getElementById('section-' + type);
            if (section) section.classList.add('active');

            event.target.closest('.type-tab').classList.add('active');
        }

        // Initialize on load
        document.addEventListener('DOMContentLoaded', function () {
            const checked = document.querySelector('input[name="type"]:checked');
            if (checked) showTypeSection(checked.value.toLowerCase());
        });
    </script>
@endpush