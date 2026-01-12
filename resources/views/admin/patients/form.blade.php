@extends('layouts.admin')

@section('title', $patient ? 'Edit Pasien' : 'Pasien Baru')
@section('page-title', $patient ? 'Edit Data Pasien' : 'Tambah Pasien Baru')
@section('page-subtitle', $patient ? 'Perbarui data pasien' : 'Daftarkan pasien baru ke sistem')

@push('styles')
    <style>
        .form-card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            max-width: 800px;
        }

        .form-header {
            padding: 24px;
            border-bottom: 1px solid var(--gray-200);
        }

        .form-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: var(--gray-800);
        }

        .form-body {
            padding: 24px;
        }

        .form-section {
            margin-bottom: 32px;
        }

        .form-section:last-child {
            margin-bottom: 0;
        }

        .form-section-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--gray-200);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 8px;
        }

        .form-label .required {
            color: var(--danger);
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--gray-200);
            border-radius: 10px;
            font-size: 15px;
            font-family: inherit;
            outline: none;
            transition: all 0.2s;
        }

        .form-textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(233, 30, 140, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .form-row-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
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
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            border: none;
        }

        .btn-secondary {
            background: var(--gray-100);
            color: var(--gray-700);
        }

        .btn-secondary:hover {
            background: var(--gray-200);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(233, 30, 140, 0.35);
        }

        .error-message {
            color: var(--danger);
            font-size: 12px;
            margin-top: 6px;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-check input {
            width: 20px;
            height: 20px;
            accent-color: var(--primary);
        }

        .form-check label {
            font-size: 14px;
            color: var(--gray-700);
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
    <div class="form-card">
        <div class="form-header">
            <h3>{{ $patient ? 'Edit Data Pasien' : 'Form Pendaftaran Pasien' }}</h3>
        </div>

        <form method="POST"
            action="{{ $patient ? route('admin.patients.update', $patient) : route('admin.patients.store') }}">
            @csrf
            @if($patient)
                @method('PUT')
            @endif

            <div class="form-body">
                <!-- Data Identitas -->
                <div class="form-section">
                    <h4 class="form-section-title"><i class="ri-user-line"></i> Data Identitas</h4>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">NIK <span class="required">*</span></label>
                            <input type="text" name="nik" class="form-input" value="{{ old('nik', $patient?->nik) }}"
                                placeholder="16 digit NIK" maxlength="16" required>
                            @error('nik')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                            <input type="text" name="name" class="form-input" value="{{ old('name', $patient?->name) }}"
                                placeholder="Nama sesuai KTP" required>
                            @error('name')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row-3">
                        <div class="form-group">
                            <label class="form-label">Jenis Kelamin <span class="required">*</span></label>
                            <select name="gender" class="form-select" required>
                                <option value="P" {{ old('gender', $patient?->gender) === 'P' ? 'selected' : '' }}>Perempuan
                                </option>
                                <option value="L" {{ old('gender', $patient?->gender) === 'L' ? 'selected' : '' }}>Laki-laki
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" name="birth_place" class="form-input"
                                value="{{ old('birth_place', $patient?->birth_place) }}" placeholder="Kota kelahiran">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="birth_date" class="form-input"
                                value="{{ old('birth_date', $patient?->birth_date?->format('Y-m-d')) }}">
                        </div>
                    </div>
                </div>

                <!-- Data Kontak -->
                <div class="form-section">
                    <h4 class="form-section-title"><i class="ri-phone-line"></i> Data Kontak</h4>

                    <div class="form-group">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" class="form-textarea"
                            placeholder="Alamat lengkap">{{ old('address', $patient?->address) }}</textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="phone" class="form-input" value="{{ old('phone', $patient?->phone) }}"
                                placeholder="08xxxxxxxxxx">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nama Suami</label>
                            <input type="text" name="husband_name" class="form-input"
                                value="{{ old('husband_name', $patient?->husband_name) }}"
                                placeholder="Nama suami (jika ada)">
                        </div>
                    </div>
                </div>

                <!-- Data Medis -->
                <div class="form-section">
                    <h4 class="form-section-title"><i class="ri-heart-pulse-line"></i> Data Medis</h4>

                    <div class="form-row-3">
                        <div class="form-group">
                            <label class="form-label">Golongan Darah</label>
                            <select name="blood_type" class="form-select">
                                <option value="">Pilih...</option>
                                <option value="A" {{ old('blood_type', $patient?->blood_type) === 'A' ? 'selected' : '' }}>A
                                </option>
                                <option value="B" {{ old('blood_type', $patient?->blood_type) === 'B' ? 'selected' : '' }}>B
                                </option>
                                <option value="AB" {{ old('blood_type', $patient?->blood_type) === 'AB' ? 'selected' : '' }}>
                                    AB</option>
                                <option value="O" {{ old('blood_type', $patient?->blood_type) === 'O' ? 'selected' : '' }}>O
                                </option>
                            </select>
                        </div>

                        <div class="form-group" style="grid-column: span 2;">
                            <label class="form-label">No. BPJS</label>
                            <input type="text" name="bpjs_number" class="form-input"
                                value="{{ old('bpjs_number', $patient?->bpjs_number) }}" placeholder="Nomor kartu BPJS">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Riwayat Alergi</label>
                        <textarea name="allergy" class="form-textarea"
                            placeholder="Alergi obat, makanan, dll">{{ old('allergy', $patient?->allergy) }}</textarea>
                    </div>
                </div>

                @if($patient)
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $patient->is_active) ? 'checked' : '' }}>
                            <label for="is_active">Pasien aktif</label>
                        </div>
                    </div>
                @endif
            </div>

            <div class="form-footer">
                <a href="{{ route('admin.patients.index') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line"></i>
                    Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="ri-save-line"></i>
                    {{ $patient ? 'Simpan Perubahan' : 'Daftarkan Pasien' }}
                </button>
            </div>
        </form>
    </div>
@endsection