@extends('layouts.admin')

@section('title', 'Tambah Antrean')
@section('page-title', 'Tambah Antrean Baru')
@section('page-subtitle', 'Daftarkan pasien ke antrean hari ini')

@push('styles')
    <style>
        .form-card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            max-width: 600px;
        }

        .form-header {
            padding: 24px;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .form-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: var(--gray-800);
        }

        .queue-number-display {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: 700;
        }

        .form-body {
            padding: 24px;
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

        .form-input:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(233, 30, 140, 0.1);
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

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
        }

        .error-message {
            color: var(--danger);
            font-size: 12px;
            margin-top: 6px;
        }
    </style>
@endpush

@section('content')
    <div class="form-card">
        <div class="form-header">
            <h3>Form Tambah Antrean</h3>
            <div class="queue-number-display">{{ $nextNumber }}</div>
        </div>

        <form method="POST" action="{{ route('admin.queues.store') }}">
            @csrf

            <div class="form-body">
                <div class="form-group">
                    <label class="form-label">Pasien <span class="required">*</span></label>
                    <select name="patient_id" class="form-select" required>
                        <option value="">Pilih pasien...</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ $selectedPatient == $patient->id ? 'selected' : '' }}>
                                {{ $patient->name }} ({{ $patient->nik }})
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Jenis Layanan <span class="required">*</span></label>
                    <select name="service_type" class="form-select" required>
                        <option value="ANC">ANC (Pemeriksaan Kehamilan)</option>
                        <option value="PNC">PNC (Pasca Melahirkan)</option>
                        <option value="KB">KB (Keluarga Berencana)</option>
                        <option value="Imunisasi">Imunisasi</option>
                        <option value="Umum" selected>Pemeriksaan Umum</option>
                    </select>
                    @error('service_type')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-textarea" placeholder="Catatan tambahan (opsional)"
                        style="min-height: 80px;">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="form-footer">
                <a href="{{ route('admin.queues.index') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line"></i>
                    Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="ri-add-circle-line"></i>
                    Tambah ke Antrean
                </button>
            </div>
        </form>
    </div>
@endsection