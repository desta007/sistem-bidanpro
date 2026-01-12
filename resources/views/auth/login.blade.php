<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BidanPRO</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #E91E8C;
            --primary-light: #FF5CAD;
            --primary-dark: #C4157A;
            --gray-100: #F3F4F6;
            --gray-200: #E5E7EB;
            --gray-400: #9CA3AF;
            --gray-500: #6B7280;
            --gray-700: #374151;
            --gray-900: #111827;
            --danger: #EF4444;
        }

        html,
        body {
            height: 100%;
        }

        body {
            font-family: 'Inter', sans-serif;
            display: flex;
            min-height: 100vh;
        }

        /* Brand Section */
        .brand-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            position: relative;
            overflow: hidden;
            color: white;
        }

        .brand-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .brand-content {
            position: relative;
            z-index: 1;
            text-align: center;
            max-width: 400px;
        }

        .brand-logo {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 40px;
        }

        .brand-title {
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 16px;
            letter-spacing: -1px;
        }

        .brand-subtitle {
            font-size: 18px;
            opacity: 0.9;
            line-height: 1.6;
            margin-bottom: 48px;
        }

        .brand-features {
            display: flex;
            flex-direction: column;
            gap: 20px;
            text-align: left;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
        }

        .feature-icon {
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .feature-text h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .feature-text p {
            font-size: 13px;
            opacity: 0.8;
        }

        /* Login Section */
        .login-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            background: white;
        }

        .login-container {
            max-width: 400px;
            margin: 0 auto;
            width: 100%;
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 8px;
        }

        .login-header p {
            font-size: 15px;
            color: var(--gray-500);
        }

        .login-form {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-label {
            font-size: 14px;
            font-weight: 500;
            color: var(--gray-700);
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper i {
            position: absolute;
            left: 16px;
            color: var(--gray-400);
            font-size: 20px;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            font-size: 15px;
            font-family: inherit;
            outline: none;
            transition: all 0.2s;
        }

        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(233, 30, 140, 0.1);
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            background: none;
            border: none;
            color: var(--gray-400);
            cursor: pointer;
            font-size: 20px;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkbox-wrapper input {
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
        }

        .checkbox-wrapper label {
            font-size: 14px;
            color: var(--gray-700);
        }

        .forgot-link {
            font-size: 14px;
            color: var(--primary);
            font-weight: 500;
            text-decoration: none;
        }

        .login-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            cursor: pointer;
            transition: all 0.2s;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(233, 30, 140, 0.35);
        }

        .error-message {
            display: flex;
            padding: 12px 16px;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 10px;
            color: var(--danger);
            font-size: 14px;
            align-items: center;
            gap: 10px;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .brand-section {
                display: none;
            }

            .login-section {
                flex: 1;
            }
        }

        @media (max-width: 480px) {
            .login-section {
                padding: 30px 20px;
            }

            .form-options {
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>
    <!-- Brand Section -->
    <section class="brand-section">
        <div class="brand-content">
            <div class="brand-logo">
                <i class="ri-heart-pulse-fill"></i>
            </div>
            <h1 class="brand-title">BidanPRO</h1>
            <p class="brand-subtitle">Platform manajemen praktik bidan mandiri terlengkap dengan integrasi digital</p>

            <div class="brand-features">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="ri-file-list-3-line"></i>
                    </div>
                    <div class="feature-text">
                        <h3>Rekam Medis Digital</h3>
                        <p>Kelola data pasien dan rekam medis dengan mudah</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="ri-calendar-check-line"></i>
                    </div>
                    <div class="feature-text">
                        <h3>Jadwal & Antrean</h3>
                        <p>Atur jadwal praktek dan antrean pasien otomatis</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="ri-line-chart-line"></i>
                    </div>
                    <div class="feature-text">
                        <h3>Laporan Kohort</h3>
                        <p>Generate laporan Dinkes dalam format standar</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Login Section -->
    <section class="login-section">
        <div class="login-container">
            <div class="login-header">
                <h2>Selamat Datang!</h2>
                <p>Silakan masuk ke akun Anda</p>
            </div>

            @if ($errors->any())
                <div class="error-message">
                    <i class="ri-error-warning-line"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
                <br>
            @endif

            <form class="login-form" method="POST" action="{{ route('login.post') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <div class="input-wrapper">
                        <i class="ri-mail-line"></i>
                        <input type="email" name="email" class="form-input" placeholder="Masukkan email Anda"
                            value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-wrapper">
                        <i class="ri-lock-line"></i>
                        <input type="password" name="password" id="password" class="form-input"
                            placeholder="Masukkan password" required>
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="ri-eye-off-line" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="form-options">
                    <div class="checkbox-wrapper">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Ingat saya</label>
                    </div>
                </div>

                <button type="submit" class="login-btn">Masuk</button>
            </form>
        </div>
    </section>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('ri-eye-off-line');
                eyeIcon.classList.add('ri-eye-line');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('ri-eye-line');
                eyeIcon.classList.add('ri-eye-off-line');
            }
        }
    </script>
</body>

</html>