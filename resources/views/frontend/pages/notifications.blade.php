@extends('frontend.layouts.app')

@section('title', 'Notifikasi - BidanPRO')

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

        .notifications-list {
            padding: 16px;
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
    </style>
@endpush

@section('content')
    <div class="page-header">
        <h1 class="page-title">Notifikasi</h1>
    </div>

    <div class="notifications-list">
        <div class="empty-state">
            <div class="empty-icon">🔔</div>
            <p>Belum ada notifikasi</p>
        </div>
    </div>
@endsection