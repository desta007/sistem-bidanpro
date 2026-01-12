@extends('frontend.layouts.app')

@section('title', 'Rekam Medis - BidanPRO')

@push('styles')
    <style>
        .page-header {
            background: white;
            padding: 20px 16px;
            border-bottom: 1px solid var(--gray-200);
        }

        .page-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--gray-900);
        }

        .page-subtitle {
            font-size: 13px;
            color: var(--gray-500);
            margin-top: 4px;
        }

        .records-list {
            padding: 16px;
        }

        .record-card {
            background: white;
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 12px;
            box-shadow: var(--shadow-sm);
            text-decoration: none;
            display: block;
        }

        .record-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .record-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .record-icon.anc {
            background: rgba(233, 30, 140, 0.1);
            color: var(--primary);
        }

        .record-icon.kb {
            background: rgba(59, 130, 246, 0.1);
            color: #3B82F6;
        }

        .record-icon.imunisasi {
            background: rgba(16, 185, 129, 0.1);
            color: #10B981;
        }

        .record-icon.umum {
            background: rgba(107, 114, 128, 0.1);
            color: #6B7280;
        }

        .record-icon.pnc {
            background: rgba(245, 158, 11, 0.1);
            color: #F59E0B;
        }

        .record-title h4 {
            font-size: 15px;
            font-weight: 600;
            color: var(--gray-800);
        }

        .record-title p {
            font-size: 12px;
            color: var(--gray-500);
        }

        .record-badge {
            margin-left: auto;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 600;
            background: var(--gray-100);
            color: var(--gray-600);
        }

        .record-summary {
            font-size: 13px;
            color: var(--gray-600);
            line-height: 1.5;
            padding-top: 12px;
            border-top: 1px solid var(--gray-100);
        }

        .record-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 12px;
        }

        .record-doctor {
            font-size: 12px;
            color: var(--gray-500);
        }

        .record-arrow {
            color: var(--primary);
            font-size: 18px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--gray-500);
        }

        .empty-icon {
            font-size: 48px;
            margin-bottom: 12px;
        }

        .pagination-wrapper {
            padding: 16px;
            display: flex;
            justify-content: center;
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <h1 class="page-title">Rekam Medis</h1>
        <p class="page-subtitle">Riwayat pemeriksaan kesehatan Anda</p>
    </div>

    <div class="records-list">
        @forelse($records as $record)
            <a href="{{ route('patient.records.show', $record) }}" class="record-card">
                <div class="record-header">
                    <div class="record-icon {{ strtolower($record->type) }}">
                        <i class="ri-stethoscope-line"></i>
                    </div>
                    <div class="record-title">
                        <h4>{{ $record->type }}</h4>
                        <p>{{ $record->exam_date->format('d M Y') }}</p>
                    </div>
                    <span class="record-badge">{{ $record->type }}</span>
                </div>
                @if($record->complaint)
                    <div class="record-summary">
                        <strong>Keluhan:</strong> {{ Str::limit($record->complaint, 80) }}
                    </div>
                @endif
                <div class="record-footer">
                    <span class="record-doctor"><i class="ri-nurse-line"></i> {{ $record->examiner?->name ?? 'Bidan' }}</span>
                    <i class="ri-arrow-right-s-line record-arrow"></i>
                </div>
            </a>
        @empty
            <div class="empty-state">
                <div class="empty-icon">📋</div>
                <p>Belum ada riwayat pemeriksaan</p>
            </div>
        @endforelse
    </div>

    @if($records->hasPages())
        <div class="pagination-wrapper">
            {{ $records->links() }}
        </div>
    @endif
@endsection