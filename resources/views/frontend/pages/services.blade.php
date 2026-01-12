@extends('frontend.layouts.app')

@section('title', 'Layanan - BidanPRO')

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        padding: 24px 16px;
        color: white;
    }

    .page-title {
        font-size: 22px;
        font-weight: 700;
    }

    .page-subtitle {
        font-size: 13px;
        opacity: 0.9;
        margin-top: 4px;
    }

    .category-section {
        padding: 20px 16px 0;
    }

    .category-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 16px;
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 12px;
    }

    .category-badge {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        background: var(--primary-bg);
        color: var(--primary);
    }

    .service-list {
        padding: 0 16px 24px;
    }

    .service-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px;
        background: white;
        border-radius: 14px;
        box-shadow: var(--shadow-sm);
        margin-bottom: 10px;
    }

    .service-info h4 {
        font-size: 14px;
        font-weight: 600;
        color: var(--gray-800);
        margin-bottom: 4px;
    }

    .service-info p {
        font-size: 12px;
        color: var(--gray-500);
    }

    .service-price-tag {
        text-align: right;
    }

    .service-price-tag .price {
        font-size: 15px;
        font-weight: 700;
        color: var(--primary);
    }

    .service-price-tag .unit {
        font-size: 11px;
        color: var(--gray-500);
    }

    .cta-section {
        padding: 0 16px 32px;
    }

    .cta-card {
        background: linear-gradient(135deg, #10B981 0%, #34D399 100%);
        border-radius: 20px;
        padding: 24px;
        color: white;
        text-align: center;
    }

    .cta-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .cta-text {
        font-size: 13px;
        opacity: 0.9;
        margin-bottom: 16px;
    }

    .cta-btn {
        background: white;
        color: #10B981;
        padding: 12px 24px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">Layanan Kami</h1>
    <p class="page-subtitle">Layanan kesehatan ibu dan anak berkualitas</p>
</div>

@foreach($services as $category => $items)
<section class="category-section">
    <h2 class="category-title">
        @switch($category)
            @case('ANC') <i class="ri-heart-pulse-line" style="color: var(--primary);"></i> @break
            @case('KB') <i class="ri-calendar-check-line" style="color: #3B82F6;"></i> @break
            @case('Imunisasi') <i class="ri-syringe-line" style="color: #10B981;"></i> @break
            @default <i class="ri-stethoscope-line" style="color: var(--gray-600);"></i>
        @endswitch
        {{ $category }}
        <span class="category-badge">{{ $items->count() }} layanan</span>
    </h2>
</section>
<div class="service-list">
    @foreach($items as $service)
    <div class="service-item">
        <div class="service-info">
            <h4>{{ $service->name }}</h4>
            <p>{{ $service->description ?? 'Layanan ' . $service->category }}</p>
        </div>
        <div class="service-price-tag">
            <div class="price">Rp {{ number_format($service->price, 0, ',', '.') }}</div>
        </div>
    </div>
    @endforeach
</div>
@endforeach

<section class="cta-section">
    <div class="cta-card">
        <h3 class="cta-title">Butuh Konsultasi?</h3>
        <p class="cta-text">Hubungi kami via WhatsApp untuk berkonsultasi atau membuat janji</p>
        <a href="https://wa.me/6281234567890" class="cta-btn">
            <i class="ri-whatsapp-line"></i> Chat WhatsApp
        </a>
    </div>
</section>
@endsection
