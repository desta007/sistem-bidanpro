@extends('layouts.admin')

@section('title', 'Rekam Medis')
@section('page-title', 'Rekam Medis')
@section('page-subtitle', 'Daftar riwayat pemeriksaan pasien')

@push('styles')
    <style>
        .filter-bar {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .filter-bar select,
        .filter-bar input {
            padding: 10px 16px;
            border: 2px solid var(--gray-200);
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
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

        .records-table {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .records-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .records-table th {
            padding: 14px 20px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            color: var(--gray-500);
            text-transform: uppercase;
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
        }

        .records-table td {
            padding: 14px 20px;
            border-bottom: 1px solid var(--gray-100);
        }

        .records-table tr:hover {
            background: var(--gray-50);
        }

        .patient-cell a {
            font-weight: 600;
            color: var(--gray-800);
            text-decoration: none;
        }

        .patient-cell a:hover {
            color: var(--primary);
        }

        .type-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
        }

        .type-badge.anc {
            background: rgba(233, 30, 140, 0.1);
            color: var(--primary);
        }

        .type-badge.kb {
            background: rgba(59, 130, 246, 0.1);
            color: #3B82F6;
        }

        .type-badge.imunisasi {
            background: rgba(16, 185, 129, 0.1);
            color: #10B981;
        }

        .type-badge.umum {
            background: rgba(107, 114, 128, 0.1);
            color: #6B7280;
        }

        .type-badge.pnc {
            background: rgba(245, 158, 11, 0.1);
            color: #F59E0B;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(233, 30, 140, 0.1);
            color: var(--primary);
            text-decoration: none;
        }

        .action-btn:hover {
            background: var(--primary);
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--gray-500);
        }

        .pagination-wrapper {
            padding: 20px;
            background: var(--gray-50);
            display: flex;
            justify-content: center;
        }
    </style>
@endpush

@section('content')
    <form method="GET" class="filter-bar">
        <select name="type" onchange="this.form.submit()">
            <option value="">Semua Jenis</option>
            <option value="ANC" {{ request('type') === 'ANC' ? 'selected' : '' }}>ANC</option>
            <option value="PNC" {{ request('type') === 'PNC' ? 'selected' : '' }}>PNC</option>
            <option value="KB" {{ request('type') === 'KB' ? 'selected' : '' }}>KB</option>
            <option value="Imunisasi" {{ request('type') === 'Imunisasi' ? 'selected' : '' }}>Imunisasi</option>
            <option value="Umum" {{ request('type') === 'Umum' ? 'selected' : '' }}>Umum</option>
        </select>
        <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="Dari"
            onchange="this.form.submit()">
        <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="Sampai"
            onchange="this.form.submit()">
        <a href="{{ route('admin.medical-records.create') }}" class="btn btn-primary" style="margin-left: auto;">
            <i class="ri-add-line"></i> Pemeriksaan Baru
        </a>
    </form>

    <div class="records-table">
        @if($records->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Pasien</th>
                        <th>Jenis</th>
                        <th>Keluhan</th>
                        <th>Pemeriksa</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($records as $record)
                        <tr>
                            <td>{{ $record->exam_date->format('d M Y') }}</td>
                            <td class="patient-cell">
                                <a href="{{ route('admin.patients.show', $record->patient) }}">{{ $record->patient->name }}</a>
                            </td>
                            <td>
                                <span class="type-badge {{ strtolower($record->type) }}">{{ $record->type }}</span>
                            </td>
                            <td>{{ Str::limit($record->complaint, 40) ?? '-' }}</td>
                            <td>{{ $record->examiner?->name ?? '-' }}</td>
                            <td>
                                <a href="{{ route('admin.medical-records.show', $record) }}" class="action-btn" title="Detail">
                                    <i class="ri-eye-line"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($records->hasPages())
                <div class="pagination-wrapper">{{ $records->links() }}</div>
            @endif
        @else
            <div class="empty-state">
                <i class="ri-file-list-line" style="font-size: 48px; margin-bottom: 12px;"></i>
                <p>Belum ada rekam medis</p>
            </div>
        @endif
    </div>
@endsection