@extends('layouts.admin')

@section('title', 'Kohort ' . ($type === 'ibu' ? 'Ibu' : 'Bayi'))
@section('page-title', 'Laporan Kohort ' . ($type === 'ibu' ? 'Ibu' : 'Bayi'))
@section('page-subtitle', 'Data untuk Dinas Kesehatan - ' . \Carbon\Carbon::parse($month)->format('F Y'))

@push('styles')
    <style>
        .filter-bar {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-bar select,
        .filter-bar input {
            padding: 10px 16px;
            border: 2px solid var(--gray-200);
            border-radius: 10px;
            font-size: 14px;
        }

        .cohort-table {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            padding: 14px 16px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            color: var(--gray-500);
            text-transform: uppercase;
            background: var(--gray-50);
        }

        td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--gray-100);
            font-size: 13px;
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

        .btn-secondary {
            background: var(--gray-100);
            color: var(--gray-700);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 60px;
            color: var(--gray-500);
        }
    </style>
@endpush

@section('content')
    <form method="GET" class="filter-bar">
        <select name="type" onchange="this.form.submit()">
            <option value="ibu" {{ $type === 'ibu' ? 'selected' : '' }}>Kohort Ibu (ANC)</option>
            <option value="bayi" {{ $type === 'bayi' ? 'selected' : '' }}>Kohort Bayi (Imunisasi)</option>
        </select>
        <input type="month" name="month" value="{{ $month }}" onchange="this.form.submit()">
        <a href="{{ route('admin.reports.export', ['type' => 'cohort-' . $type, 'month' => $month]) }}"
            class="btn btn-secondary" style="margin-left: auto;">
            <i class="ri-download-line"></i> Export Excel
        </a>
    </form>

    <div class="cohort-table">
        @if($records->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama Pasien</th>
                        <th>NIK</th>
                        @if($type === 'ibu')
                            <th>HPHT</th>
                            <th>HPL</th>
                            <th>UK</th>
                            <th>TD</th>
                            <th>BB</th>
                            <th>DJJ</th>
                        @else
                            <th>Jenis Vaksin</th>
                            <th>No. Batch</th>
                            <th>Jadwal Berikutnya</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($records as $index => $record)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $record->exam_date->format('d/m/Y') }}</td>
                            <td>{{ $record->patient->name }}</td>
                            <td>{{ $record->patient->nik }}</td>
                            @if($type === 'ibu')
                                <td>{{ $record->hpht?->format('d/m/Y') ?? '-' }}</td>
                                <td>{{ $record->hpl?->format('d/m/Y') ?? '-' }}</td>
                                <td>{{ $record->pregnancy_week ?? '-' }}</td>
                                <td>{{ $record->blood_pressure ?? '-' }}</td>
                                <td>{{ $record->weight ?? '-' }}</td>
                                <td>{{ $record->fetal_heart_rate ?? '-' }}</td>
                            @else
                                <td>{{ $record->vaccine_type ?? '-' }}</td>
                                <td>{{ $record->vaccine_batch ?? '-' }}</td>
                                <td>{{ $record->next_vaccine_date?->format('d/m/Y') ?? '-' }}</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <i class="ri-file-chart-line" style="font-size: 48px; margin-bottom: 12px;"></i>
                <p>Tidak ada data untuk periode ini</p>
            </div>
        @endif
    </div>
@endsection