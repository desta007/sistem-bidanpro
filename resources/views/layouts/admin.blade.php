<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - BidanPRO</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    @stack('styles')
</head>

<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <div class="logo-icon">
                    <i class="ri-heart-pulse-fill"></i>
                </div>
                <span class="logo-text">BidanPRO</span>
            </div>
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="ri-menu-fold-line"></i>
            </button>
        </div>

        <nav class="sidebar-nav">
            <ul class="nav-list">
                <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
                        <i class="ri-dashboard-3-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.patients*') ? 'active' : '' }}">
                    <a href="{{ route('admin.patients.index') }}" class="nav-link">
                        <i class="ri-group-line"></i>
                        <span>Pasien</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.medical-records*') ? 'active' : '' }}">
                    <a href="{{ route('admin.medical-records.index') }}" class="nav-link">
                        <i class="ri-file-list-3-line"></i>
                        <span>Rekam Medis</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.queues*') ? 'active' : '' }}">
                    <a href="{{ route('admin.queues.index') }}" class="nav-link">
                        <i class="ri-calendar-schedule-line"></i>
                        <span>Antrean</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.billing*') ? 'active' : '' }}">
                    <a href="{{ route('admin.billing.index') }}" class="nav-link">
                        <i class="ri-money-dollar-circle-line"></i>
                        <span>Kasir</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.inventory*') ? 'active' : '' }}">
                    <a href="{{ route('admin.inventory.index') }}" class="nav-link">
                        <i class="ri-medicine-bottle-line"></i>
                        <span>Inventaris</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                    <a href="{{ route('admin.reports.index') }}" class="nav-link">
                        <i class="ri-pie-chart-line"></i>
                        <span>Laporan</span>
                    </a>
                </li>
            </ul>

            <div class="nav-divider"></div>

            <ul class="nav-list">
                @if(auth()->user()->canManageStaff())
                    <li class="nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                        <a href="{{ route('admin.users.index') }}" class="nav-link">
                            <i class="ri-user-settings-line"></i>
                            <span>Kelola Staff</span>
                        </a>
                    </li>
                @endif
                <li class="nav-item {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                    <a href="{{ route('admin.settings.index') }}" class="nav-link">
                        <i class="ri-settings-3-line"></i>
                        <span>Pengaturan</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=E91E8C&color=fff"
                        alt="User">
                </div>
                <div class="user-details">
                    <span class="user-name">{{ auth()->user()->name }}</span>
                    <span class="user-role">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout-btn" title="Logout">
                        <i class="ri-logout-box-r-line"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div class="header-left">
                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <i class="ri-menu-line"></i>
                </button>
                <div class="greeting">
                    <h1>@yield('page-title', 'Dashboard')</h1>
                    <p>@yield('page-subtitle', date('l, d F Y'))</p>
                </div>
            </div>
            <div class="header-right">
                <div class="search-box">
                    <i class="ri-search-line"></i>
                    <input type="text" placeholder="Cari pasien, rekam medis...">
                </div>
                <div class="notification-wrapper">
                    <button class="icon-btn" id="notificationBtn" type="button">
                        <i class="ri-notification-3-line"></i>
                        <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
                    </button>
                    <div class="notification-dropdown" id="notificationDropdown">
                        <div class="notification-header">
                            <h4>Notifikasi</h4>
                            <button type="button" id="markAllReadBtn" class="mark-all-btn">Tandai Semua Dibaca</button>
                        </div>
                        <div class="notification-list" id="notificationList">
                            <div class="notification-loading">
                                <i class="ri-loader-4-line"></i> Memuat...
                            </div>
                        </div>
                        <div class="notification-footer">
                            <a href="{{ route('admin.notifications.index') }}">Lihat Semua Notifikasi</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="dashboard-content">
            @if(session('success'))
                <div class="alert alert-success"
                    style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 10px; padding: 12px 16px; margin-bottom: 20px; color: #10B981; display: flex; align-items: center; gap: 10px;">
                    <i class="ri-checkbox-circle-line"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger"
                    style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 10px; padding: 12px 16px; margin-bottom: 20px; color: #EF4444; display: flex; align-items: center; gap: 10px;">
                    <i class="ri-error-warning-line"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <script>
        $(document).ready(function () {
            const notificationBtn = $('#notificationBtn');
            const notificationDropdown = $('#notificationDropdown');
            const notificationBadge = $('#notificationBadge');
            const notificationList = $('#notificationList');
            const markAllReadBtn = $('#markAllReadBtn');
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Toggle dropdown
            notificationBtn.on('click', function (e) {
                e.stopPropagation();
                notificationDropdown.toggleClass('show');
                if (notificationDropdown.hasClass('show')) {
                    loadNotifications();
                }
            });

            // Close dropdown when clicking outside
            $(document).on('click', function (e) {
                if (!$(e.target).closest('.notification-wrapper').length) {
                    notificationDropdown.removeClass('show');
                }
            });

            // Load notifications
            function loadNotifications() {
                notificationList.html('<div class="notification-loading"><i class="ri-loader-4-line"></i> Memuat...</div>');

                $.ajax({
                    url: '{{ route("admin.notifications.unread") }}',
                    method: 'GET',
                    success: function (response) {
                        renderNotifications(response.notifications);
                        updateBadge(response.unread_count);
                    },
                    error: function () {
                        notificationList.html('<div class="notification-empty"><i class="ri-error-warning-line"></i><p>Gagal memuat notifikasi</p></div>');
                    }
                });
            }

            // Render notifications in dropdown
            function renderNotifications(notifications) {
                if (notifications.length === 0) {
                    notificationList.html('<div class="notification-empty"><i class="ri-notification-off-line"></i><p>Tidak ada notifikasi baru</p></div>');
                    return;
                }

                let html = '';
                notifications.forEach(function (notif) {
                    const unreadClass = notif.read ? '' : 'unread';
                    html += `
                        <div class="notification-item ${unreadClass}" data-id="${notif.id}" data-link="${notif.link || ''}">
                            <div class="notification-icon ${notif.color}">
                                <i class="${notif.icon}"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-title">${notif.title}</div>
                                <div class="notification-message">${notif.message}</div>
                                <div class="notification-time">${notif.time}</div>
                            </div>
                        </div>
                    `;
                });
                notificationList.html(html);
            }

            // Update badge count
            function updateBadge(count) {
                if (count > 0) {
                    notificationBadge.text(count > 99 ? '99+' : count).show();
                } else {
                    notificationBadge.hide();
                }
            }

            // Click on notification item
            notificationList.on('click', '.notification-item', function () {
                const id = $(this).data('id');
                const link = $(this).data('link');

                // Mark as read
                $.ajax({
                    url: `/admin/notifications/${id}/read`,
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    success: function () {
                        if (link) {
                            window.location.href = link;
                        } else {
                            loadNotifications();
                        }
                    }
                });
            });

            // Mark all as read
            markAllReadBtn.on('click', function (e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ route("admin.notifications.read-all") }}',
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    success: function () {
                        notificationList.find('.notification-item').removeClass('unread');
                        updateBadge(0);
                    }
                });
            });

            // Initial load on page load (for badge count)
            $.ajax({
                url: '{{ route("admin.notifications.unread") }}',
                method: 'GET',
                success: function (response) {
                    updateBadge(response.unread_count);
                }
            });
        });
    </script>
    @stack('scripts')
</body>

</html>