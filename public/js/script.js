/**
 * BidanPRO Dashboard - JavaScript Interactivity
 */

document.addEventListener('DOMContentLoaded', function () {
    // Initialize components
    initSidebar();
    initSearch();
    initNotifications();
    updateGreeting();
    initQueueActions();
});

/**
 * Sidebar Toggle & Mobile Menu
 */
function initSidebar() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');

    // Create overlay for mobile
    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    document.body.appendChild(overlay);

    // Desktop toggle (collapse sidebar)
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.toggle('collapsed');
            document.querySelector('.main-content').classList.toggle('expanded');

            // Toggle icon
            const icon = sidebarToggle.querySelector('i');
            if (sidebar.classList.contains('collapsed')) {
                icon.classList.replace('ri-menu-fold-line', 'ri-menu-unfold-line');
            } else {
                icon.classList.replace('ri-menu-unfold-line', 'ri-menu-fold-line');
            }
        });
    }

    // Mobile menu toggle
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function () {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        });
    }

    // Close sidebar when clicking overlay
    overlay.addEventListener('click', function () {
        sidebar.classList.remove('open');
        overlay.classList.remove('active');
    });

    // Nav item click handler
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => {
        item.addEventListener('click', function () {
            navItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');

            // Close mobile sidebar after navigation
            if (window.innerWidth <= 1024) {
                sidebar.classList.remove('open');
                overlay.classList.remove('active');
            }
        });
    });
}

/**
 * Search Functionality
 */
function initSearch() {
    const searchInput = document.querySelector('.search-box input');
    const searchBox = document.querySelector('.search-box');

    if (searchInput && searchBox) {
        let searchTimeout;
        let searchDropdown = null;

        // Create search dropdown
        function createSearchDropdown() {
            if (searchDropdown) return searchDropdown;

            searchDropdown = document.createElement('div');
            searchDropdown.className = 'search-dropdown';
            searchDropdown.style.cssText = `
                position: absolute;
                top: calc(100% + 8px);
                left: 0;
                right: 0;
                background: white;
                border-radius: 12px;
                box-shadow: 0 10px 40px rgba(0,0,0,0.15);
                border: 1px solid #E5E7EB;
                z-index: 1000;
                display: none;
                overflow: hidden;
                max-height: 400px;
                overflow-y: auto;
            `;
            searchBox.style.position = 'relative';
            searchBox.appendChild(searchDropdown);

            return searchDropdown;
        }

        // Show loading state
        function showLoading() {
            const dropdown = createSearchDropdown();
            dropdown.innerHTML = `
                <div style="padding: 20px; text-align: center; color: #6B7280;">
                    <i class="ri-loader-4-line" style="animation: spin 1s linear infinite;"></i>
                    Mencari...
                </div>
            `;
            dropdown.style.display = 'block';
        }

        // Show results
        function showResults(data) {
            const dropdown = createSearchDropdown();

            if (data.total === 0) {
                dropdown.innerHTML = `
                    <div style="padding: 20px; text-align: center; color: #6B7280;">
                        <i class="ri-search-line" style="font-size: 24px; display: block; margin-bottom: 8px; opacity: 0.5;"></i>
                        Tidak ada hasil ditemukan
                    </div>
                `;
                dropdown.style.display = 'block';
                return;
            }

            let html = '';

            // Patients
            if (data.results.patients && data.results.patients.length > 0) {
                html += `<div style="padding: 8px 16px; background: #F9FAFB; color: #6B7280; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Pasien</div>`;
                data.results.patients.forEach(patient => {
                    html += `
                        <a href="${patient.url}" class="search-result-item" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-bottom: 1px solid #F3F4F6; cursor: pointer; text-decoration: none; color: inherit; transition: background 0.15s;">
                            <div style="width: 40px; height: 40px; background: rgba(233, 30, 140, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="ri-user-line" style="color: #E91E8C; font-size: 18px;"></i>
                            </div>
                            <div style="flex: 1; min-width: 0;">
                                <div style="font-weight: 600; color: #1F2937; font-size: 14px;">${patient.name}</div>
                                <div style="font-size: 12px; color: #6B7280; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${patient.description}</div>
                            </div>
                            <i class="ri-arrow-right-s-line" style="color: #9CA3AF;"></i>
                        </a>
                    `;
                });
            }

            // Medical Records
            if (data.results.medical_records && data.results.medical_records.length > 0) {
                html += `<div style="padding: 8px 16px; background: #F9FAFB; color: #6B7280; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Rekam Medis</div>`;
                data.results.medical_records.forEach(record => {
                    html += `
                        <a href="${record.url}" class="search-result-item" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-bottom: 1px solid #F3F4F6; cursor: pointer; text-decoration: none; color: inherit; transition: background 0.15s;">
                            <div style="width: 40px; height: 40px; background: rgba(0, 191, 165, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="ri-file-list-3-line" style="color: #00BFA5; font-size: 18px;"></i>
                            </div>
                            <div style="flex: 1; min-width: 0;">
                                <div style="font-weight: 600; color: #1F2937; font-size: 14px;">${record.name}</div>
                                <div style="font-size: 12px; color: #6B7280; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${record.description}</div>
                            </div>
                            <div style="font-size: 11px; color: #9CA3AF;">${record.date}</div>
                        </a>
                    `;
                });
            }

            dropdown.innerHTML = html;
            dropdown.style.display = 'block';

            // Add hover effects
            dropdown.querySelectorAll('.search-result-item').forEach(item => {
                item.addEventListener('mouseenter', () => item.style.background = '#F9FAFB');
                item.addEventListener('mouseleave', () => item.style.background = 'transparent');
            });
        }

        // Hide dropdown
        function hideDropdown() {
            if (searchDropdown) {
                searchDropdown.style.display = 'none';
            }
        }

        // Perform search
        async function performSearch(query) {
            showLoading();

            try {
                const response = await fetch(`/admin/search?q=${encodeURIComponent(query)}`);
                const data = await response.json();

                if (data.success) {
                    showResults(data);
                } else {
                    hideDropdown();
                }
            } catch (error) {
                console.error('Search error:', error);
                hideDropdown();
            }
        }

        searchInput.addEventListener('input', function (e) {
            const query = e.target.value.trim();

            clearTimeout(searchTimeout);

            if (query.length < 2) {
                hideDropdown();
                return;
            }

            searchTimeout = setTimeout(() => {
                performSearch(query);
            }, 300);
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function (e) {
            if (!searchBox.contains(e.target)) {
                hideDropdown();
            }
        });

        // Keyboard shortcut (Ctrl/Cmd + K)
        document.addEventListener('keydown', function (e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                searchInput.focus();
            }

            // Close on Escape
            if (e.key === 'Escape') {
                hideDropdown();
                searchInput.blur();
            }
        });

        // Hide dropdown on focus out if empty
        searchInput.addEventListener('blur', function (e) {
            // Small delay to allow clicking on results
            setTimeout(() => {
                if (!searchInput.value.trim()) {
                    hideDropdown();
                }
            }, 200);
        });
    }
}

/**
 * Notifications
 */
function initNotifications() {
    const notificationBtn = document.querySelector('.notification-btn');

    if (notificationBtn) {
        notificationBtn.addEventListener('click', function () {
            showNotificationPanel();
        });
    }
}

function showNotificationPanel() {
    // Create notification dropdown
    const existingPanel = document.querySelector('.notification-panel');
    if (existingPanel) {
        existingPanel.remove();
        return;
    }

    const panel = document.createElement('div');
    panel.className = 'notification-panel';
    panel.innerHTML = `
        <div class="notification-header">
            <h4>Notifikasi</h4>
            <button class="mark-read-btn">Tandai dibaca</button>
        </div>
        <div class="notification-list">
            <div class="notification-item unread">
                <div class="notification-icon urgent">
                    <i class="ri-alarm-warning-line"></i>
                </div>
                <div class="notification-content">
                    <p class="notification-text">Stok Vitamin A hampir habis</p>
                    <span class="notification-time">5 menit lalu</span>
                </div>
            </div>
            <div class="notification-item unread">
                <div class="notification-icon info">
                    <i class="ri-calendar-check-line"></i>
                </div>
                <div class="notification-content">
                    <p class="notification-text">Jadwal kontrol Ibu Rina besok jam 09:00</p>
                    <span class="notification-time">10 menit lalu</span>
                </div>
            </div>
            <div class="notification-item">
                <div class="notification-icon success">
                    <i class="ri-check-double-line"></i>
                </div>
                <div class="notification-content">
                    <p class="notification-text">Klaim BPJS bulan ini sudah diproses</p>
                    <span class="notification-time">1 jam lalu</span>
                </div>
            </div>
        </div>
        <div class="notification-footer">
            <a href="#">Lihat semua notifikasi</a>
        </div>
    `;

    // Add panel styles
    panel.style.cssText = `
        position: absolute;
        top: 100%;
        right: 0;
        width: 360px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        z-index: 1000;
        margin-top: 8px;
        overflow: hidden;
    `;

    const notificationBtn = document.querySelector('.notification-btn');
    notificationBtn.style.position = 'relative';
    notificationBtn.appendChild(panel);

    // Close when clicking outside
    document.addEventListener('click', function closePanel(e) {
        if (!notificationBtn.contains(e.target)) {
            panel.remove();
            document.removeEventListener('click', closePanel);
        }
    });
}

/**
 * Update Greeting based on time
 */
function updateGreeting() {
    const greetingEl = document.querySelector('.greeting h1');
    if (!greetingEl) return;

    const hour = new Date().getHours();
    let greeting;

    if (hour >= 5 && hour < 12) {
        greeting = 'Selamat Pagi';
    } else if (hour >= 12 && hour < 15) {
        greeting = 'Selamat Siang';
    } else if (hour >= 15 && hour < 18) {
        greeting = 'Selamat Sore';
    } else {
        greeting = 'Selamat Malam';
    }

    // Extract name from current greeting
    const currentText = greetingEl.textContent;
    const nameMatch = currentText.match(/Bidan \w+/);
    const name = nameMatch ? nameMatch[0] : 'Bidan';

    greetingEl.textContent = `${greeting}, ${name}! 👋`;

    // Update date
    const dateEl = document.querySelector('.greeting p');
    if (dateEl) {
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        const today = new Date().toLocaleDateString('id-ID', options);
        dateEl.textContent = today;
    }
}

/**
 * Queue Actions
 */
function initQueueActions() {
    const queueItems = document.querySelectorAll('.queue-item');

    queueItems.forEach(item => {
        item.addEventListener('click', function () {
            // Show patient action modal
            const patientName = this.querySelector('.queue-name').textContent;
            const service = this.querySelector('.queue-service').textContent;

            showPatientActionModal(patientName, service);
        });
    });
}

function showPatientActionModal(patientName, service) {
    // Create modal backdrop
    const modalBackdrop = document.createElement('div');
    modalBackdrop.className = 'modal-backdrop';
    modalBackdrop.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    `;

    // Create modal
    const modal = document.createElement('div');
    modal.className = 'patient-modal';
    modal.style.cssText = `
        background: white;
        border-radius: 16px;
        padding: 24px;
        width: 90%;
        max-width: 400px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    `;

    modal.innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 20px;">
            <div>
                <h3 style="font-size: 18px; font-weight: 600; color: #1F2937; margin-bottom: 4px;">${patientName}</h3>
                <p style="font-size: 14px; color: #6B7280;">${service}</p>
            </div>
            <button class="close-modal" style="width: 32px; height: 32px; border-radius: 8px; border: none; background: #F3F4F6; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                <i class="ri-close-line" style="font-size: 20px; color: #6B7280;"></i>
            </button>
        </div>
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <button class="action-option" style="display: flex; align-items: center; gap: 12px; padding: 14px 16px; border: 1px solid #E5E7EB; border-radius: 10px; background: white; cursor: pointer; transition: all 0.15s;">
                <div style="width: 40px; height: 40px; background: rgba(233, 30, 140, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="ri-stethoscope-line" style="font-size: 20px; color: #E91E8C;"></i>
                </div>
                <div style="text-align: left;">
                    <span style="display: block; font-size: 14px; font-weight: 600; color: #1F2937;">Mulai Pemeriksaan</span>
                    <span style="font-size: 12px; color: #6B7280;">Buka form rekam medis</span>
                </div>
            </button>
            <button class="action-option" style="display: flex; align-items: center; gap: 12px; padding: 14px 16px; border: 1px solid #E5E7EB; border-radius: 10px; background: white; cursor: pointer; transition: all 0.15s;">
                <div style="width: 40px; height: 40px; background: rgba(0, 191, 165, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="ri-user-line" style="font-size: 20px; color: #00BFA5;"></i>
                </div>
                <div style="text-align: left;">
                    <span style="display: block; font-size: 14px; font-weight: 600; color: #1F2937;">Lihat Profil Pasien</span>
                    <span style="font-size: 12px; color: #6B7280;">Riwayat kunjungan & rekam medis</span>
                </div>
            </button>
            <button class="action-option" style="display: flex; align-items: center; gap: 12px; padding: 14px 16px; border: 1px solid #E5E7EB; border-radius: 10px; background: white; cursor: pointer; transition: all 0.15s;">
                <div style="width: 40px; height: 40px; background: rgba(59, 130, 246, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="ri-hospital-line" style="font-size: 20px; color: #3B82F6;"></i>
                </div>
                <div style="text-align: left;">
                    <span style="display: block; font-size: 14px; font-weight: 600; color: #1F2937;">Verifikasi BPJS</span>
                    <span style="font-size: 12px; color: #6B7280;">Cek status kepesertaan BPJS</span>
                </div>
            </button>
        </div>
    `;

    modalBackdrop.appendChild(modal);
    document.body.appendChild(modalBackdrop);

    // Add hover effect to action options
    const actionOptions = modal.querySelectorAll('.action-option');
    actionOptions.forEach(option => {
        option.addEventListener('mouseenter', function () {
            this.style.borderColor = '#E91E8C';
            this.style.background = 'rgba(233, 30, 140, 0.02)';
        });
        option.addEventListener('mouseleave', function () {
            this.style.borderColor = '#E5E7EB';
            this.style.background = 'white';
        });
    });

    // Close modal
    const closeBtn = modal.querySelector('.close-modal');
    closeBtn.addEventListener('click', () => modalBackdrop.remove());
    modalBackdrop.addEventListener('click', (e) => {
        if (e.target === modalBackdrop) modalBackdrop.remove();
    });

    // Escape key to close
    document.addEventListener('keydown', function escHandler(e) {
        if (e.key === 'Escape') {
            modalBackdrop.remove();
            document.removeEventListener('keydown', escHandler);
        }
    });
}

/**
 * Stats Counter Animation
 */
function animateCounter(element, target, duration = 1000) {
    const start = 0;
    const increment = target / (duration / 16);
    let current = start;

    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            element.textContent = formatNumber(target);
            clearInterval(timer);
        } else {
            element.textContent = formatNumber(Math.floor(current));
        }
    }, 16);
}

function formatNumber(num) {
    if (num >= 1000000) {
        return (num / 1000000).toFixed(1) + 'M';
    } else if (num >= 1000) {
        return (num / 1000).toFixed(1) + 'K';
    }
    return num.toString();
}

/**
 * Quick Action Button Handlers
 */
document.querySelectorAll('.action-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const actionText = this.querySelector('span').textContent;

        switch (actionText) {
            case 'Pasien Baru':
                // Navigate to patient registration
                console.log('Opening patient registration form...');
                break;
            case 'Pemeriksaan':
                // Navigate to examination
                console.log('Opening examination form...');
                break;
            case 'Klaim BPJS':
                // Navigate to BPJS claims
                console.log('Opening BPJS claims...');
                break;
            case 'Cetak Laporan':
                // Open report generator
                console.log('Opening report generator...');
                break;
        }
    });
});

/**
 * Real-time Clock (optional)
 */
function updateClock() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit'
    });

    const clockEl = document.getElementById('current-time');
    if (clockEl) {
        clockEl.textContent = timeString;
    }
}

// Update clock every minute
setInterval(updateClock, 60000);

/**
 * Dark Mode Toggle (optional)
 */
function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
}

// Check for saved dark mode preference
if (localStorage.getItem('darkMode') === 'true') {
    document.body.classList.add('dark-mode');
}
