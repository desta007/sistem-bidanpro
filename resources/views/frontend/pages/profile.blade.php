@extends('frontend.layouts.app')

@section('title', 'Profil - BidanPRO')

@push('styles')
    <style>
        .profile-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            padding: 32px 16px;
            text-align: center;
            color: white;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 32px;
            font-weight: 700;
        }

        .profile-name {
            font-size: 20px;
            font-weight: 700;
        }

        .profile-nik {
            font-size: 13px;
            opacity: 0.9;
            margin-top: 4px;
        }

        .form-section {
            padding: 20px 16px;
        }

        .form-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 16px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 8px;
        }

        .form-input,
        .form-textarea {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            font-size: 15px;
            font-family: inherit;
            outline: none;
            transition: all 0.2s;
        }

        .form-input:focus,
        .form-textarea:focus {
            border-color: var(--primary);
        }

        .form-input:disabled {
            background: var(--gray-50);
            color: var(--gray-500);
        }

        .submit-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .logout-section {
            padding: 0 16px 32px;
        }

        .logout-btn {
            width: 100%;
            padding: 16px;
            background: rgba(239, 68, 68, 0.1);
            color: #DC2626;
            border: none;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .form-error {
            color: var(--danger);
            font-size: 12px;
            margin-top: 6px;
        }
    </style>
@endpush

@section('content')
    <div class="profile-header">
        <div class="profile-avatar">{{ strtoupper(substr($patient->name, 0, 2)) }}</div>
        <h1 class="profile-name">{{ $patient->name }}</h1>
        <p class="profile-nik">NIK: {{ $patient->nik }}</p>
    </div>

    <form method="POST" action="{{ route('patient.profile.update') }}" class="form-section">
        @csrf

        <div class="form-card">
            <div class="form-group">
                <label class="form-label">NIK</label>
                <input type="text" class="form-input" value="{{ $patient->nik }}" disabled>
            </div>

            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-input" value="{{ old('name', $patient->name) }}" required>
                @error('name')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">No. HP</label>
                <input type="tel" name="phone" class="form-input" value="{{ old('phone', $patient->phone) }}" required>
                @error('phone')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Tanggal Lahir</label>
                <input type="date" name="birth_date" class="form-input"
                    value="{{ old('birth_date', $patient->birth_date?->format('Y-m-d')) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Alamat</label>
                <textarea name="address" class="form-textarea" rows="2">{{ old('address', $patient->address) }}</textarea>
            </div>
        </div>

        <button type="submit" class="submit-btn">
            <i class="ri-save-line"></i> Simpan Perubahan
        </button>
    </form>

    <div class="logout-section">
        <form method="POST" action="{{ route('patient.logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="ri-logout-box-r-line"></i> Keluar
            </button>
        </form>
    </div>
@endsection