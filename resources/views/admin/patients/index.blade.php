@extends('layouts.admin')

@section('title', 'Daftar Pasien')
@section('page-title', 'Daftar Pasien')
@section('page-subtitle', 'Kelola data pasien klinik')

@push('styles')
    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .filters-bar {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .search-input-lg {
            position: relative;
            min-width: 300px;
        }

        .search-input-lg i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            font-size: 18px;
        }

        .search-input-lg input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            font-size: 15px;
            font-family: inherit;
            outline: none;
            transition: all 0.2s;
        }

        .search-input-lg input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(233, 30, 140, 0.1);
        }

        .filter-select {
            padding: 14px 40px 14px 16px;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            font-size: 14px;
            font-family: inherit;
            background: white url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e") no-repeat right 12px center;
            background-size: 20px;
            cursor: pointer;
            outline: none;
            -webkit-appearance: none;
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
            transition: all 0.2s;
            text-decoration: none;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(233, 30, 140, 0.35);
        }

        .patients-table {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .patients-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .patients-table th {
            padding: 16px 20px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
        }

        .patients-table td {
            padding: 16px 20px;
            border-bottom: 1px solid var(--gray-100);
            vertical-align: middle;
        }

        .patients-table tr:hover {
            background: var(--gray-50);
        }

        .patient-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .patient-avatar {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            object-fit: cover;
            background: var(--primary-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-weight: 600;
            font-size: 16px;
        }

        .patient-details h4 {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 2px;
        }

        .patient-details h4 a {
            color: inherit;
            text-decoration: none;
        }

        .patient-details h4 a:hover {
            color: var(--primary);
        }

        .patient-details span {
            font-size: 12px;
            color: var(--gray-500);
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .status-badge.active {
            background: rgba(16, 185, 129, 0.1);
            color: #10B981;
        }

        .status-badge.inactive {
            background: rgba(239, 68, 68, 0.1);
            color: #EF4444;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }

        .action-btn.view {
            background: rgba(59, 130, 246, 0.1);
            color: #3B82F6;
        }

        .action-btn.view:hover {
            background: #3B82F6;
            color: white;
        }

        .action-btn.edit {
            background: rgba(233, 30, 140, 0.1);
            color: var(--primary);
        }

        .action-btn.edit:hover {
            background: var(--primary);
            color: white;
        }

        .action-btn.exam {
            background: rgba(16, 185, 129, 0.1);
            color: #10B981;
        }

        .action-btn.exam:hover {
            background: #10B981;
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state-icon {
            width: 80px;
            height: 80px;
            background: var(--gray-100);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 32px;
            color: var(--gray-400);
        }

        .pagination-wrapper {
            display: flex;
            justify-content: center;
            padding: 20px;
            background: var(--gray-50);
            border-top: 1px solid var(--gray-200);
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .filters-bar {
                width: 100%;
            }

            .search-input-lg {
                min-width: 100%;
            }

            .patients-table {
                overflow-x: auto;
            }

            .patients-table table {
                min-width: 800px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <form method="GET" class="filters-bar">
            <div class="search-input-lg">
                <i class="ri-search-line"></i>
                <input type="text" name="search" placeholder="Cari NIK, nama, atau telepon..."
                    value="{{ request('search') }}">
            </div>
            <select name="status" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </form>
        <a href="{{ route('admin.patients.create') }}" class="btn btn-primary">
            <i class="ri-user-add-line"></i>
            Pasien Baru
        </a>
    </div>

    <div class="patients-table">
        @if($patients->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Pasien</th>
                        <th>NIK</th>
                        <th>Telepon</th>
                        <th>Usia</th>
                        <th>BPJS</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patients as $patient)
                        <tr>
                            <td>
                                <div class="patient-cell">
                                    <div class="patient-avatar">
                                        {{ strtoupper(substr($patient->name, 0, 2)) }}
                                    </div>
                                    <div class="patient-details">
                                        <h4><a href="{{ route('admin.patients.show', $patient) }}">{{ $patient->name }}</a></h4>
                                        <span>{{ $patient->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td><code
                                    style="background: var(--gray-100); padding: 4px 8px; border-radius: 4px; font-size: 12px;">{{ $patient->nik }}</code>
                            </td>
                            <td>{{ $patient->phone ?? '-' }}</td>
                            <td>{{ $patient->age ? $patient->age . ' th' : '-' }}</td>
                            <td>{{ $patient->bpjs_number ? 'Ya' : '-' }}</td>
                            <td>
                                <span class="status-badge {{ $patient->is_active ? 'active' : 'inactive' }}">
                                    {{ $patient->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.patients.show', $patient) }}" class="action-btn view"
                                        title="Lihat Detail">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <a href="{{ route('admin.patients.edit', $patient) }}" class="action-btn edit" title="Edit">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    <a href="{{ route('admin.medical-records.create', ['patient_id' => $patient->id]) }}"
                                        class="action-btn exam" title="Pemeriksaan">
                                        <i class="ri-stethoscope-line"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($patients->hasPages())
                <div class="pagination-wrapper">
                    {{ $patients->links() }}
                </div>
            @endif

        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="ri-user-line"></i>
                </div>
                <h3 style="font-size: 16px; font-weight: 600; color: var(--gray-700); margin-bottom: 8px;">Belum ada pasien</h3>
                <p style="font-size: 14px; color: var(--gray-500);">Tambahkan pasien pertama untuk memulai</p>
            </div>
        @endif
    </div>
@endsection