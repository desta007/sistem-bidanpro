<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#E91E8C">
    <meta name="description" content="BidanPRO - Layanan kesehatan ibu dan anak terpercaya">
    <title>@yield('title', 'BidanPRO')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        :root {
            --primary: #E91E8C;
            --primary-light: #FF6BB3;
            --primary-dark: #C4167A;
            --primary-bg: #FDF2F8;
            --secondary: #7C3AED;
            --success: #10B981;
            --warning: #F59E0B;
            --danger: #EF4444;
            --gray-50: #F9FAFB;
            --gray-100: #F3F4F6;
            --gray-200: #E5E7EB;
            --gray-300: #D1D5DB;
            --gray-400: #9CA3AF;
            --gray-500: #6B7280;
            --gray-600: #4B5563;
            --gray-700: #374151;
            --gray-800: #1F2937;
            --gray-900: #111827;
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            --safe-bottom: env(safe-area-inset-bottom, 0px);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--gray-50);
            color: var(--gray-800);
            line-height: 1.6;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        /* Header */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow-sm);
            z-index: 100;
        }

        .header-logo {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .header-logo-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
        }

        .header-logo-text {
            font-size: 18px;
            font-weight: 700;
            color: var(--gray-900);
        }

        .header-logo-text span {
            color: var(--primary);
        }

        .header-actions {
            display: flex;
            gap: 8px;
        }

        .header-btn {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            border: none;
            background: var(--gray-100);
            color: var(--gray-600);
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            text-decoration: none;
        }

        .header-btn.primary {
            background: var(--primary);
            color: white;
        }

        /* Main Content */
        .main {
            padding-top: 64px;
            padding-bottom: calc(56px + var(--safe-bottom));
            min-height: 100vh;
        }

        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 4px 12px;
            padding-bottom: calc(4px + var(--safe-bottom));
            display: flex;
            justify-content: space-around;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.06);
            z-index: 100;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
            padding: 6px 12px;
            text-decoration: none;
            color: var(--gray-400);
            font-size: 9px;
            font-weight: 500;
            transition: all 0.2s;
            border-radius: 8px;
        }

        .nav-item i {
            font-size: 18px;
        }

        .nav-item.active {
            color: var(--primary);
            background: var(--primary-bg);
        }

        /* Container */
        .container {
            max-width: 480px;
            margin: 0 auto;
            padding: 0 16px;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 24px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            border: none;
            width: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(233, 30, 140, 0.3);
        }

        .btn-primary:active {
            transform: scale(0.98);
        }

        .btn-secondary {
            background: var(--gray-100);
            color: var(--gray-700);
        }

        .btn-outline {
            background: white;
            border: 2px solid var(--gray-200);
            color: var(--gray-700);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success) 0%, #34D399 100%);
            color: white;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 16px;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            font-size: 15px;
            font-family: inherit;
            outline: none;
            transition: all 0.2s;
            -webkit-appearance: none;
        }

        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(233, 30, 140, 0.1);
        }

        /* Alert */
        .alert {
            padding: 14px 16px;
            border-radius: 12px;
            font-size: 14px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #DC2626;
        }

        .alert-info {
            background: rgba(59, 130, 246, 0.1);
            color: #2563EB;
        }

        /* Section Title */
        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 16px;
        }

        /* Hide scrollbar */
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        @stack('styles')
    </style>
</head>

<body>
    <!-- Header -->
    <header class="header">
        <a href="{{ route('home') }}" class="header-logo">
            <div class="header-logo-icon">
                <i class="ri-heart-pulse-fill"></i>
            </div>
            <span class="header-logo-text">Bidan<span>PRO</span></span>
        </a>
        <div class="header-actions">
            @auth('patient')
                <a href="{{ route('patient.notifications') }}" class="header-btn">
                    <i class="ri-notification-3-line"></i>
                </a>
            @else
                <a href="{{ route('patient.login') }}" class="header-btn primary">
                    <i class="ri-user-line"></i>
                </a>
            @endauth
        </div>
    </header>

    <!-- Main Content -->
    <main class="main">
        @if(session('success'))
            <div class="container" style="padding-top: 16px;">
                <div class="alert alert-success">
                    <i class="ri-checkbox-circle-line"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container" style="padding-top: 16px;">
                <div class="alert alert-danger">
                    <i class="ri-error-warning-line"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Bottom Navigation -->
    @auth('patient')
        <nav class="bottom-nav">
            <a href="{{ route('home') }}" class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="ri-home-5-{{ request()->routeIs('home') ? 'fill' : 'line' }}"></i>
                <span>Beranda</span>
            </a>
            <a href="{{ route('patient.appointments') }}"
                class="nav-item {{ request()->routeIs('patient.appointments*') ? 'active' : '' }}">
                <i class="ri-calendar-{{ request()->routeIs('patient.appointments*') ? 'fill' : 'line' }}"></i>
                <span>Jadwal</span>
            </a>
            <a href="{{ route('patient.records') }}"
                class="nav-item {{ request()->routeIs('patient.records*') ? 'active' : '' }}">
                <i class="ri-file-list-3-{{ request()->routeIs('patient.records*') ? 'fill' : 'line' }}"></i>
                <span>Rekam Medis</span>
            </a>
            <a href="{{ route('patient.profile') }}"
                class="nav-item {{ request()->routeIs('patient.profile*') ? 'active' : '' }}">
                <i class="ri-user-{{ request()->routeIs('patient.profile*') ? 'fill' : 'line' }}"></i>
                <span>Profil</span>
            </a>
        </nav>
    @else
        <nav class="bottom-nav">
            <a href="{{ route('home') }}" class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="ri-home-5-{{ request()->routeIs('home') ? 'fill' : 'line' }}"></i>
                <span>Beranda</span>
            </a>
            <a href="{{ route('services') }}" class="nav-item {{ request()->routeIs('services') ? 'active' : '' }}">
                <i class="ri-stethoscope-line"></i>
                <span>Layanan</span>
            </a>
            <a href="{{ route('about') }}" class="nav-item {{ request()->routeIs('about') ? 'active' : '' }}">
                <i class="ri-information-line"></i>
                <span>Tentang</span>
            </a>
            <a href="{{ route('patient.login') }}"
                class="nav-item {{ request()->routeIs('patient.login') ? 'active' : '' }}">
                <i class="ri-user-line"></i>
                <span>Masuk</span>
            </a>
        </nav>
    @endauth

    <script>
        // Touch feedback for buttons
        document.querySelectorAll('.btn, .nav-item, .header-btn').forEach(el => {
            el.addEventListener('touchstart', function () {
                this.style.opacity = '0.8';
            });
            el.addEventListener('touchend', function () {
                this.style.opacity = '1';
            });
        });
    </script>
    @stack('scripts')
</body>

</html>