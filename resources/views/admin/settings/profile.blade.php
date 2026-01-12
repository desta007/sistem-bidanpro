@extends('layouts.admin')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')
@section('page-subtitle', 'Kelola informasi akun Anda')

@push('styles')
    <style>
        .settings-grid {
            display: grid;
            grid-template-columns: 250px 1fr;
            gap: 24px;
        }

        .settings-nav {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            padding: 16px;
            height: fit-content;
        }

        .settings-nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 10px;
            text-decoration: none;
            color: var(--gray-700);
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .settings-nav a:hover {
            background: var(--gray-50);
        }

        .settings-nav a.active {
            background: rgba(233, 30, 140, 0.1);
            color: var(--primary);
        }

        .settings-nav a i {
            font-size: 18px;
        }

        .settings-card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
        }

        .settings-header {
            padding: 24px;
            border-bottom: 1px solid var(--gray-200);
        }

        .settings-header h3 {
            font-size: 18px;
            font-weight: 600;
        }

        .settings-body {
            padding: 24px;
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 24px;
            padding-bottom: 24px;
            border-bottom: 1px solid var(--gray-200);
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            font-weight: 700;
        }

        .profile-info h4 {
            font-size: 20px;
            font-weight: 600;
            color: var(--gray-800);
        }

        .profile-info p {
            font-size: 14px;
            color: var(--gray-500);
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

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--gray-200);
            border-radius: 10px;
            font-size: 15px;
        }

        .form-input:focus {
            border-color: var(--primary);
            outline: none;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .section-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-700);
            margin: 24px 0 16px;
            padding-top: 24px;
            border-top: 1px solid var(--gray-200);
        }

        .settings-footer {
            padding: 20px 24px;
            border-top: 1px solid var(--gray-200);
            display: flex;
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
            text-decoration: none;
            border: none;
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

        @media (max-width: 768px) {
            .settings-grid {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="settings-grid">
        <nav class="settings-nav">
            <a href="{{ route('admin.settings.index') }}"><i class="ri-hospital-line"></i> Data Klinik</a>
            <a href="{{ route('admin.settings.profile') }}" class="active"><i class="ri-user-line"></i> Profil Saya</a>
        </nav>

        <div class="settings-card">
            <div class="settings-header">
                <h3>Profil Saya</h3>
            </div>
            <form method="POST" action="{{ route('admin.settings.profile.update') }}">
                @csrf
                <div class="settings-body">
                    <div class="profile-header">
                        <div class="profile-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                        <div class="profile-info">
                            <h4>{{ $user->name }}</h4>
                            <p>{{ ucfirst($user->role) }} • {{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}"
                                required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-input" value="{{ old('email', $user->email) }}"
                                required>
                            @error('email')<span class="error-message">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="phone" class="form-input" value="{{ old('phone', $user->phone) }}">
                    </div>

                    <h4 class="section-title">Ubah Password</h4>
                    <p style="font-size: 13px; color: var(--gray-500); margin-bottom: 16px;">Kosongkan jika tidak ingin
                        mengubah password</p>

                    <div class="form-group">
                        <label class="form-label">Password Saat Ini</label>
                        <input type="password" name="current_password" class="form-input">
                        @error('current_password')<span class="error-message">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="new_password" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" class="form-input">
                        </div>
                    </div>
                </div>
                <div class="settings-footer">
                    <button type="submit" class="btn btn-primary"><i class="ri-save-line"></i> Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection