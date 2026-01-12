@extends('frontend.layouts.app')

@section('title', 'Tentang Kami - BidanPRO')

@push('styles')
    <style>
        .about-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            padding: 32px 16px;
            text-align: center;
            color: white;
        }

        .about-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 36px;
        }

        .about-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .about-subtitle {
            font-size: 14px;
            opacity: 0.9;
        }

        .content-section {
            padding: 24px 16px;
        }

        .about-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 16px;
        }

        .about-card h3 {
            font-size: 16px;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .about-card h3 i {
            color: var(--primary);
        }

        .about-card p {
            font-size: 14px;
            color: var(--gray-600);
            line-height: 1.7;
        }

        .values-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-top: 16px;
        }

        .value-item {
            text-align: center;
            padding: 16px;
            background: var(--gray-50);
            border-radius: 14px;
        }

        .value-item i {
            font-size: 28px;
            color: var(--primary);
            margin-bottom: 8px;
            display: block;
        }

        .value-item span {
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-700);
        }

        .team-section h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 16px;
            text-align: center;
        }

        .team-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            box-shadow: var(--shadow-sm);
        }

        .team-avatar {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 32px;
            color: white;
        }

        .team-name {
            font-size: 16px;
            font-weight: 700;
            color: var(--gray-900);
        }

        .team-role {
            font-size: 13px;
            color: var(--primary);
            margin-bottom: 8px;
        }

        .team-bio {
            font-size: 13px;
            color: var(--gray-500);
            line-height: 1.6;
        }

        .cta-section {
            padding: 0 16px 32px;
        }

        .cta-card {
            background: linear-gradient(135deg, #10B981 0%, #34D399 100%);
            border-radius: 20px;
            padding: 24px;
            text-align: center;
            color: white;
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
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: white;
            color: #10B981;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
        }
    </style>
@endpush

@section('content')
    <div class="about-header">
        <div class="about-icon"><i class="ri-heart-pulse-fill"></i></div>
        <h1 class="about-title">BidanPRO</h1>
        <p class="about-subtitle">Bidan Praktik Mandiri Terpercaya</p>
    </div>

    <div class="content-section">
        <div class="about-card">
            <h3><i class="ri-information-line"></i> Tentang Kami</h3>
            <p>BidanPRO adalah Bidan Praktik Mandiri (BPM) yang berdedikasi untuk memberikan layanan kesehatan ibu dan anak
                yang berkualitas, profesional, dan terjangkau. Kami berkomitmen untuk mendampingi ibu dan keluarga dalam
                setiap tahap kehamilan hingga masa nifas.</p>
        </div>

        <div class="about-card">
            <h3><i class="ri-heart-line"></i> Nilai Kami</h3>
            <div class="values-grid">
                <div class="value-item">
                    <i class="ri-shield-check-line"></i>
                    <span>Profesional</span>
                </div>
                <div class="value-item">
                    <i class="ri-heart-2-line"></i>
                    <span>Peduli</span>
                </div>
                <div class="value-item">
                    <i class="ri-hand-heart-line"></i>
                    <span>Ramah</span>
                </div>
                <div class="value-item">
                    <i class="ri-star-line"></i>
                    <span>Berkualitas</span>
                </div>
            </div>
        </div>

        <div class="team-section">
            <h3>Tim Kami</h3>
            <div class="team-card">
                <div class="team-avatar"><i class="ri-nurse-fill"></i></div>
                <h4 class="team-name">Bd. Siti Nurhaliza, Amd.Keb</h4>
                <p class="team-role">Bidan Praktik</p>
                <p class="team-bio">Berpengalaman lebih dari 10 tahun dalam pelayanan kesehatan ibu dan anak. Terdaftar di
                    IBI dengan STR aktif.</p>
            </div>
        </div>
    </div>

    <div class="cta-section">
        <div class="cta-card">
            <h3 class="cta-title">Butuh Layanan?</h3>
            <p class="cta-text">Hubungi kami untuk informasi lebih lanjut atau membuat janji</p>
            <a href="https://wa.me/6281234567890" class="cta-btn">
                <i class="ri-whatsapp-line"></i> Hubungi via WhatsApp
            </a>
        </div>
    </div>
@endsection