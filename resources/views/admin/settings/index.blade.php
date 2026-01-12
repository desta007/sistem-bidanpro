@extends('layouts.admin')

@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan')
@section('page-subtitle', 'Konfigurasi sistem dan klinik')

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

        .form-input,
        .form-textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--gray-200);
            border-radius: 10px;
            font-size: 15px;
        }

        .form-input:focus,
        .form-textarea:focus {
            border-color: var(--primary);
            outline: none;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
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
            <a href="{{ route('admin.settings.index') }}" class="active"><i class="ri-hospital-line"></i> Data Klinik</a>
            <a href="{{ route('admin.settings.profile') }}"><i class="ri-user-line"></i> Profil Saya</a>
        </nav>

        <div class="settings-card">
            <div class="settings-header">
                <h3>Data Klinik</h3>
            </div>
            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf
                <div class="settings-body">
                    <div class="form-group">
                        <label class="form-label">Nama Klinik</label>
                        <input type="text" name="clinic_name" class="form-input" value="{{ $settings['clinic_name'] }}"
                            placeholder="Bidan Praktik Mandiri ...">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Alamat</label>
                        <textarea name="clinic_address" class="form-textarea" rows="2"
                            placeholder="Alamat lengkap klinik">{{ $settings['clinic_address'] }}</textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="clinic_phone" class="form-input"
                                value="{{ $settings['clinic_phone'] }}" placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="clinic_email" class="form-input"
                                value="{{ $settings['clinic_email'] }}" placeholder="email@klinik.com">
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