@extends('frontend.layouts.app')

@section('title', 'Masuk - BidanPRO')

@push('styles')
    <style>
        .login-container {
            padding: 24px 16px;
            padding-bottom: 140px;
            min-height: calc(100vh - 64px);
            display: flex;
            flex-direction: column;
        }

        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 36px;
            color: white;
        }

        .login-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 8px;
        }

        .login-subtitle {
            font-size: 14px;
            color: var(--gray-500);
        }

        .login-form {
            flex: 1;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 8px;
        }

        .input-group {
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            color: var(--gray-400);
        }

        .input-group input {
            padding-left: 48px;
        }

        .form-input {
            width: 100%;
            padding: 16px;
            border: 2px solid var(--gray-200);
            border-radius: 14px;
            font-size: 16px;
            font-family: inherit;
            outline: none;
            transition: all 0.2s;
        }

        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(233, 30, 140, 0.1);
        }

        .form-error {
            color: var(--danger);
            font-size: 12px;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .login-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .login-btn:active {
            transform: scale(0.98);
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 24px 0;
        }

        .divider-line {
            flex: 1;
            height: 1px;
            background: var(--gray-200);
        }

        .divider-text {
            font-size: 12px;
            color: var(--gray-400);
        }

        .register-link {
            text-align: center;
            padding: 20px;
            background: var(--gray-50);
            border-radius: 14px;
        }

        .register-link p {
            font-size: 14px;
            color: var(--gray-600);
            margin-bottom: 12px;
        }

        .register-link a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }

        .help-text {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 60px;
            font-size: 13px;
            color: var(--gray-500);
        }

        .help-text a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
    <div class="login-container">
        <div class="login-header">
            <div class="login-icon">
                <i class="ri-user-heart-line"></i>
            </div>
            <h1 class="login-title">Masuk</h1>
            <p class="login-subtitle">Masuk untuk melihat riwayat pemeriksaan Anda</p>
        </div>

        <form method="POST" action="{{ route('patient.login.post') }}" class="login-form">
            @csrf

            <div class="form-group">
                <label class="form-label">Nomor HP</label>
                <div class="input-group">
                    <i class="ri-phone-line"></i>
                    <input type="tel" name="phone" class="form-input" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx"
                        required>
                </div>
                @error('phone')
                    <span class="form-error"><i class="ri-error-warning-line"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <i class="ri-lock-line"></i>
                    <input type="password" name="password" class="form-input" placeholder="Masukkan password" required>
                </div>
                @error('password')
                    <span class="form-error"><i class="ri-error-warning-line"></i> {{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="login-btn">
                <i class="ri-login-circle-line"></i>
                Masuk
            </button>
        </form>

        <div class="divider">
            <span class="divider-line"></span>
            <span class="divider-text">atau</span>
            <span class="divider-line"></span>
        </div>

        <div class="register-link">
            <p>Belum punya akun?</p>
            <a href="{{ route('patient.register') }}">
                <i class="ri-user-add-line"></i>
                Daftar Sebagai Pasien Baru
            </a>
        </div>

        <p class="help-text">
            Butuh bantuan? <a href="https://wa.me/6281234567890">Hubungi via WhatsApp</a>
        </p>
    </div>
@endsection