@extends('layouts.admin')

@section('title', $user ? 'Edit Staff' : 'Tambah Staff')
@section('page-title', $user ? 'Edit Staff' : 'Tambah Staff Baru')
@section('page-subtitle', $user ? 'Perbarui data staff' : 'Tambahkan pengguna baru ke sistem')

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
        }

        .form-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: var(--gray-800);
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
        .form-select {
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

        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
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

        .password-hint {
            font-size: 12px;
            color: var(--gray-500);
            margin-top: 6px;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="form-card">
        <div class="form-header">
            <h3>{{ $user ? 'Edit Data Staff' : 'Form Tambah Staff' }}</h3>
        </div>

        <form method="POST" action="{{ $user ? route('admin.users.update', $user) : route('admin.users.store') }}">
            @csrf
            @if($user)
                @method('PUT')
            @endif

            <div class="form-body">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $user?->name) }}"
                        placeholder="Masukkan nama lengkap" required>
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Email <span class="required">*</span></label>
                        <input type="email" name="email" class="form-input" value="{{ old('email', $user?->email) }}"
                            placeholder="email@example.com" required>
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="phone" class="form-input" value="{{ old('phone', $user?->phone) }}"
                            placeholder="08xxxxxxxxxx">
                        @error('phone')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Role <span class="required">*</span></label>
                    <select name="role" class="form-select" required>
                        @foreach($roles as $value => $label)
                            <option value="{{ $value }}" {{ old('role', $user?->role) === $value ? 'selected' : '' }}>{{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Password {{ $user ? '' : '*' }}</label>
                        <input type="password" name="password" class="form-input"
                            placeholder="{{ $user ? 'Kosongkan jika tidak diubah' : 'Masukkan password' }}" {{ $user ? '' : 'required' }}>
                        @if($user)
                            <span class="password-hint">Kosongkan jika tidak ingin mengubah password</span>
                        @endif
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password {{ $user ? '' : '*' }}</label>
                        <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi password"
                            {{ $user ? '' : 'required' }}>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $user?->is_active ?? true) ? 'checked' : '' }}>
                        <label for="is_active">Aktifkan akun ini</label>
                    </div>
                </div>
            </div>

            <div class="form-footer">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line"></i>
                    Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="ri-save-line"></i>
                    {{ $user ? 'Simpan Perubahan' : 'Tambah Staff' }}
                </button>
            </div>
        </form>
    </div>
@endsection