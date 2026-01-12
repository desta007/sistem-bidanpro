@extends('frontend.layouts.app')

@section('title', 'Daftar - BidanPRO')

@push('styles')
    <style>
        .register-container {
            padding: 24px 16px 32px;
        }

        .register-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .register-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 32px;
            color: white;
        }

        .register-title {
            font-size: 22px;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 6px;
        }

        .register-subtitle {
            font-size: 13px;
            color: var(--gray-500);
        }

        .form-section {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 16px;
            box-shadow: var(--shadow-sm);
        }

        .section-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title i {
            color: var(--primary);
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

        .form-label .required {
            color: var(--danger);
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            font-size: 15px;
            font-family: inherit;
            outline: none;
            transition: all 0.2s;
            -webkit-appearance: none;
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
            gap: 12px;
        }

        .form-error {
            color: var(--danger);
            font-size: 12px;
            margin-top: 6px;
        }

        .gender-options {
            display: flex;
            gap: 12px;
        }

        .gender-option {
            flex: 1;
            padding: 14px;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .gender-option input {
            display: none;
        }

        .gender-option.selected {
            border-color: var(--primary);
            background: var(--primary-bg);
        }

        .gender-option i {
            font-size: 24px;
            color: var(--gray-400);
            margin-bottom: 4px;
            display: block;
        }

        .gender-option.selected i {
            color: var(--primary);
        }

        .gender-option span {
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-600);
        }

        .gender-option.selected span {
            color: var(--primary);
        }

        .register-btn {
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
            margin-top: 8px;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: var(--gray-600);
        }

        .login-link a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }
    </style>
@endpush

@section('content')
    <div class="register-container">
        <div class="register-header">
            <div class="register-icon">
                <i class="ri-user-add-line"></i>
            </div>
            <h1 class="register-title">Daftar Pasien Baru</h1>
            <p class="register-subtitle">Isi data diri untuk mendaftar</p>
        </div>

        <form method="POST" action="{{ route('patient.register.post') }}">
            @csrf

            <div class="form-section">
                <h3 class="section-title"><i class="ri-user-line"></i> Data Diri</h3>

                <div class="form-group">
                    <label class="form-label">NIK <span class="required">*</span></label>
                    <input type="text" name="nik" class="form-input" value="{{ old('nik') }}" placeholder="16 digit NIK"
                        maxlength="16" inputmode="numeric" required>
                    @error('nik')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" placeholder="Sesuai KTP"
                        required>
                    @error('name')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Jenis Kelamin <span class="required">*</span></label>
                    <div class="gender-options">
                        <label class="gender-option {{ old('gender') === 'P' ? 'selected' : '' }}"
                            onclick="selectGender(this, 'P')">
                            <input type="radio" name="gender" value="P" {{ old('gender') === 'P' ? 'checked' : '' }} required>
                            <i class="ri-women-line"></i>
                            <span>Perempuan</span>
                        </label>
                        <label class="gender-option {{ old('gender') === 'L' ? 'selected' : '' }}"
                            onclick="selectGender(this, 'L')">
                            <input type="radio" name="gender" value="L" {{ old('gender') === 'L' ? 'checked' : '' }}>
                            <i class="ri-men-line"></i>
                            <span>Laki-laki</span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="birth_date" class="form-input" value="{{ old('birth_date') }}">
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title"><i class="ri-phone-line"></i> Kontak</h3>

                <div class="form-group">
                    <label class="form-label">Nomor HP <span class="required">*</span></label>
                    <input type="tel" name="phone" class="form-input" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx"
                        required>
                    @error('phone')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Alamat</label>
                    <textarea name="address" class="form-textarea" rows="2"
                        placeholder="Alamat lengkap">{{ old('address') }}</textarea>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title"><i class="ri-lock-line"></i> Keamanan</h3>

                <div class="form-group">
                    <label class="form-label">Password <span class="required">*</span></label>
                    <input type="password" name="password" class="form-input" placeholder="Min. 6 karakter" required>
                    @error('password')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Password <span class="required">*</span></label>
                    <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi password" required>
                </div>
            </div>

            <button type="submit" class="register-btn">
                <i class="ri-check-line"></i>
                Daftar Sekarang
            </button>
        </form>

        <p class="login-link">
            Sudah punya akun? <a href="{{ route('patient.login') }}">Masuk di sini</a>
        </p>
    </div>
<div style="height: 80px;"></div>
@endsection

@push('scripts')
    <script>
        function selectGender(el, value) {
            document.querySelectorAll('.gender-option').forEach(opt => opt.classList.remove('selected'));
            el.classList.add('selected');
            el.querySelector('input').checked = true;
        }
    </script>
@endpush