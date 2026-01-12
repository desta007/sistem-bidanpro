@extends('frontend.layouts.app')

@section('title', 'BidanPRO - Layanan Kesehatan Ibu & Anak')

@push('styles')
    <style>
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            padding: 32px 16px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -20%;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-title {
            font-size: 26px;
            font-weight: 800;
            line-height: 1.3;
            margin-bottom: 12px;
        }

        .hero-subtitle {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .hero-cta {
            display: flex;
            gap: 12px;
        }

        .hero-cta .btn {
            flex: 1;
            padding: 12px 16px;
            font-size: 13px;
        }

        .hero-cta .btn-white {
            background: white;
            color: var(--primary);
        }

        .hero-cta .btn-outline-white {
            background: transparent;
            border: 2px solid rgba(255, 255, 255, 0.5);
            color: white;
        }

        /* Quick Actions */
        .quick-actions {
            padding: 20px 16px;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }

        .action-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            padding: 16px 8px;
            background: white;
            border-radius: 16px;
            text-decoration: none;
            box-shadow: var(--shadow-sm);
            transition: all 0.2s;
        }

        .action-item:active {
            transform: scale(0.95);
        }

        .action-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .action-icon.pink {
            background: rgba(233, 30, 140, 0.1);
            color: var(--primary);
        }

        .action-icon.blue {
            background: rgba(59, 130, 246, 0.1);
            color: #3B82F6;
        }

        .action-icon.green {
            background: rgba(16, 185, 129, 0.1);
            color: #10B981;
        }

        .action-icon.purple {
            background: rgba(124, 58, 237, 0.1);
            color: #7C3AED;
        }

        .action-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--gray-700);
            text-align: center;
        }

        /* Services Section */
        .services-section {
            padding: 24px 16px;
        }

        .service-card {
            display: flex;
            gap: 16px;
            padding: 16px;
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 12px;
            text-decoration: none;
        }

        .service-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        .service-content {
            flex: 1;
        }

        .service-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 4px;
        }

        .service-desc {
            font-size: 12px;
            color: var(--gray-500);
            line-height: 1.5;
        }

        .service-price {
            font-size: 14px;
            font-weight: 700;
            color: var(--primary);
            margin-top: 8px;
        }

        /* Info Cards */
        .info-section {
            padding: 0 16px 24px;
        }

        .info-card {
            background: linear-gradient(135deg, #3B82F6 0%, #60A5FA 100%);
            border-radius: 20px;
            padding: 24px;
            color: white;
            margin-bottom: 16px;
        }

        .info-card-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .info-card-text {
            font-size: 13px;
            opacity: 0.9;
            margin-bottom: 16px;
        }

        .info-card .btn {
            background: white;
            color: #3B82F6;
            padding: 10px 20px;
            font-size: 13px;
            width: auto;
        }

        /* Hours Card */
        .hours-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: var(--shadow-sm);
        }

        .hours-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .hours-icon {
            width: 44px;
            height: 44px;
            background: rgba(16, 185, 129, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--success);
            font-size: 20px;
        }

        .hours-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--gray-900);
        }

        .hours-status {
            font-size: 12px;
            color: var(--success);
            font-weight: 600;
        }

        .hours-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .hours-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
        }

        .hours-row .day {
            color: var(--gray-600);
        }

        .hours-row .time {
            font-weight: 600;
            color: var(--gray-800);
        }

        /* Contact Section */
        .contact-section {
            padding: 0 16px 32px;
        }

        .contact-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: var(--shadow-sm);
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 0;
            border-bottom: 1px solid var(--gray-100);
        }

        .contact-item:last-child {
            border-bottom: none;
        }

        .contact-icon {
            width: 44px;
            height: 44px;
            background: var(--gray-100);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-600);
            font-size: 18px;
        }

        .contact-text {
            flex: 1;
        }

        .contact-label {
            font-size: 11px;
            color: var(--gray-500);
        }

        .contact-value {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-800);
        }

        .contact-action {
            width: 36px;
            height: 36px;
            background: var(--primary-bg);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            text-decoration: none;
        }
    </style>
@endpush

@section('content')
    <!-- Hero -->
    <section class="hero">
        <div class="hero-content">
            <h1 class="hero-title">Selamat Datang di<br>BidanPRO 👋</h1>
            <p class="hero-subtitle">Layanan kesehatan ibu dan anak yang profesional, ramah, dan terpercaya.</p>
            <div class="hero-cta">
                <a href="{{ route('patient.register') }}" class="btn btn-white">Daftar Sekarang</a>
                <a href="tel:08123456789" class="btn btn-outline-white"><i class="ri-phone-line"></i> Hubungi</a>
            </div>
        </div>
    </section>

    <!-- Quick Actions -->
    <section class="quick-actions">
        <div class="actions-grid">
            <a href="{{ route('patient.register') }}" class="action-item">
                <div class="action-icon pink"><i class="ri-user-add-line"></i></div>
                <span class="action-label">Daftar</span>
            </a>
            <a href="{{ route('services') }}" class="action-item">
                <div class="action-icon blue"><i class="ri-stethoscope-line"></i></div>
                <span class="action-label">Layanan</span>
            </a>
            <a href="{{ route('patient.queue') }}" class="action-item">
                <div class="action-icon green"><i class="ri-time-line"></i></div>
                <span class="action-label">Antrean</span>
            </a>
            <a href="https://wa.me/6281234567890" class="action-item">
                <div class="action-icon purple"><i class="ri-whatsapp-line"></i></div>
                <span class="action-label">WhatsApp</span>
            </a>
        </div>
    </section>

    <!-- Services -->
    <section class="services-section">
        <h2 class="section-title">Layanan Kami</h2>

        <a href="{{ route('services') }}" class="service-card">
            <div class="service-icon" style="background: rgba(233, 30, 140, 0.1); color: var(--primary);">
                <i class="ri-heart-pulse-line"></i>
            </div>
            <div class="service-content">
                <h3 class="service-title">Pemeriksaan Kehamilan (ANC)</h3>
                <p class="service-desc">Pemeriksaan rutin kehamilan dengan USG, cek tekanan darah, dan konsultasi.</p>
                <span class="service-price">Mulai Rp 150.000</span>
            </div>
        </a>

        <a href="{{ route('services') }}" class="service-card">
            <div class="service-icon" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6;">
                <i class="ri-calendar-check-line"></i>
            </div>
            <div class="service-content">
                <h3 class="service-title">Keluarga Berencana (KB)</h3>
                <p class="service-desc">Suntik KB, IUD, implant, dan konsultasi program KB.</p>
                <span class="service-price">Mulai Rp 50.000</span>
            </div>
        </a>

        <a href="{{ route('services') }}" class="service-card">
            <div class="service-icon" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">
                <i class="ri-syringe-line"></i>
            </div>
            <div class="service-content">
                <h3 class="service-title">Imunisasi</h3>
                <p class="service-desc">Imunisasi lengkap untuk bayi dan anak sesuai jadwal.</p>
                <span class="service-price">Mulai Rp 50.000</span>
            </div>
        </a>
    </section>

    <!-- Info Card -->
    <section class="info-section">
        <div class="info-card">
            <h3 class="info-card-title">📱 Daftar Online</h3>
            <p class="info-card-text">Daftar sebagai pasien untuk melihat riwayat pemeriksaan, jadwal kunjungan, dan invoice
                Anda.</p>
            <a href="{{ route('patient.register') }}" class="btn">Daftar Gratis</a>
        </div>
    </section>

    <!-- Hours -->
    <section class="info-section">
        <h2 class="section-title">Jam Operasional</h2>
        <div class="hours-card">
            <div class="hours-header">
                <div class="hours-icon"><i class="ri-time-line"></i></div>
                <div>
                    <div class="hours-title">Jam Buka</div>
                    <div class="hours-status">● Buka Sekarang</div>
                </div>
            </div>
            <div class="hours-list">
                <div class="hours-row">
                    <span class="day">Senin - Jumat</span>
                    <span class="time">08:00 - 20:00</span>
                </div>
                <div class="hours-row">
                    <span class="day">Sabtu</span>
                    <span class="time">08:00 - 15:00</span>
                </div>
                <div class="hours-row">
                    <span class="day">Minggu</span>
                    <span class="time">Tutup</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact -->
    <section class="contact-section">
        <h2 class="section-title">Hubungi Kami</h2>
        <div class="contact-card">
            <div class="contact-item">
                <div class="contact-icon"><i class="ri-phone-line"></i></div>
                <div class="contact-text">
                    <div class="contact-label">Telepon</div>
                    <div class="contact-value">08123456789</div>
                </div>
                <a href="tel:08123456789" class="contact-action"><i class="ri-phone-fill"></i></a>
            </div>
            <div class="contact-item">
                <div class="contact-icon"><i class="ri-whatsapp-line"></i></div>
                <div class="contact-text">
                    <div class="contact-label">WhatsApp</div>
                    <div class="contact-value">08123456789</div>
                </div>
                <a href="https://wa.me/6281234567890" class="contact-action"><i class="ri-whatsapp-fill"></i></a>
            </div>
            <div class="contact-item">
                <div class="contact-icon"><i class="ri-map-pin-line"></i></div>
                <div class="contact-text">
                    <div class="contact-label">Alamat</div>
                    <div class="contact-value">Jl. Contoh No. 123, Kota</div>
                </div>
                <a href="https://maps.google.com" class="contact-action"><i class="ri-arrow-right-up-line"></i></a>
            </div>
        </div>
    </section>
@endsection