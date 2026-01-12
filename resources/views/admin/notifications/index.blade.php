@extends('layouts.admin')

@section('title', 'Notifikasi')

@section('page-title', 'Notifikasi')
@section('page-subtitle', 'Kelola notifikasi Anda')

@push('styles')
    <style>
        .notifications-page {
            max-width: 800px;
        }

        .filter-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
            background: var(--white);
            padding: 6px;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
        }

        .filter-tab {
            padding: 10px 20px;
            border-radius: var(--radius-md);
            font-size: 13px;
            font-weight: 500;
            color: var(--gray-600);
            transition: all var(--transition-fast);
            text-decoration: none;
        }

        .filter-tab:hover {
            background: var(--gray-100);
            color: var(--gray-800);
        }

        .filter-tab.active {
            background: var(--primary);
            color: white;
        }

        .notification-card {
            background: var(--white);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .notification-card-header {
            padding: 16px 24px;
            border-bottom: 1px solid var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .notification-card-title {
            font-size: 15px;
            font-weight: 600;
            color: var(--gray-800);
        }

        .notification-actions-bar {
            display: flex;
            gap: 12px;
        }

        .action-btn-small {
            padding: 8px 16px;
            border-radius: var(--radius-md);
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all var(--transition-fast);
        }

        .action-btn-small.primary {
            background: var(--primary-bg);
            color: var(--primary);
        }

        .action-btn-small.primary:hover {
            background: var(--primary);
            color: white;
        }

        .notification-list-full {
            padding: 0;
        }

        .notification-item-full {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            padding: 20px 24px;
            border-bottom: 1px solid var(--gray-50);
            transition: background var(--transition-fast);
        }

        .notification-item-full:hover {
            background: var(--gray-50);
        }

        .notification-item-full.unread {
            background: var(--primary-bg);
        }

        .notification-item-full.unread:hover {
            background: rgba(233, 30, 140, 0.12);
        }

        .notification-icon-large {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .notification-content-full {
            flex: 1;
            min-width: 0;
        }

        .notification-title-full {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 4px;
        }

        .notification-message-full {
            font-size: 13px;
            color: var(--gray-600);
            line-height: 1.5;
            margin-bottom: 8px;
        }

        .notification-meta {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .notification-time-full {
            font-size: 12px;
            color: var(--gray-400);
        }

        .notification-status {
            padding: 2px 8px;
            border-radius: var(--radius-full);
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .notification-status.unread {
            background: var(--warning-bg);
            color: var(--warning);
        }

        .notification-status.read {
            background: var(--gray-100);
            color: var(--gray-500);
        }

        .notification-item-actions {
            display: flex;
            gap: 8px;
        }

        .notification-action-btn {
            width: 32px;
            height: 32px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: var(--gray-400);
            transition: all var(--transition-fast);
        }

        .notification-action-btn:hover {
            background: var(--gray-100);
            color: var(--gray-700);
        }

        .notification-action-btn.delete:hover {
            background: var(--danger-bg);
            color: var(--danger);
        }

        .empty-notifications {
            padding: 60px 20px;
            text-align: center;
            color: var(--gray-400);
        }

        .empty-notifications i {
            font-size: 48px;
            margin-bottom: 16px;
            display: block;
        }

        .empty-notifications p {
            font-size: 14px;
        }

        .pagination-wrapper {
            padding: 20px 24px;
            border-top: 1px solid var(--gray-100);
        }
    </style>
@endpush

@section('content')
    <div class="notifications-page">
        <div class="filter-tabs">
            <a href="{{ route('admin.notifications.index') }}" class="filter-tab {{ !request('filter') ? 'active' : '' }}">
                Semua
            </a>
            <a href="{{ route('admin.notifications.index', ['filter' => 'unread']) }}"
                class="filter-tab {{ request('filter') === 'unread' ? 'active' : '' }}">
                Belum Dibaca
            </a>
            <a href="{{ route('admin.notifications.index', ['filter' => 'read']) }}"
                class="filter-tab {{ request('filter') === 'read' ? 'active' : '' }}">
                Sudah Dibaca
            </a>
        </div>

        <div class="notification-card">
            <div class="notification-card-header">
                <span class="notification-card-title">
                    {{ $notifications->total() }} Notifikasi
                </span>
                <div class="notification-actions-bar">
                    <button type="button" class="action-btn-small primary" id="markAllReadBtnPage">
                        <i class="ri-check-double-line"></i> Tandai Semua Dibaca
                    </button>
                </div>
            </div>

            <div class="notification-list-full">
                @forelse($notifications as $notification)
                    <div class="notification-item-full {{ !$notification->isRead() ? 'unread' : '' }}"
                        data-id="{{ $notification->id }}">
                        <div class="notification-icon-large {{ $notification->color }}">
                            <i class="{{ $notification->icon }}"></i>
                        </div>
                        <div class="notification-content-full">
                            <div class="notification-title-full">{{ $notification->title }}</div>
                            <div class="notification-message-full">{{ $notification->message }}</div>
                            <div class="notification-meta">
                                <span class="notification-time-full">
                                    <i class="ri-time-line"></i> {{ $notification->created_at->diffForHumans() }}
                                </span>
                                <span class="notification-status {{ $notification->isRead() ? 'read' : 'unread' }}">
                                    {{ $notification->isRead() ? 'Dibaca' : 'Belum Dibaca' }}
                                </span>
                            </div>
                        </div>
                        <div class="notification-item-actions">
                            @if (!$notification->isRead())
                                <button type="button" class="notification-action-btn mark-read-btn"
                                    data-id="{{ $notification->id }}" title="Tandai Dibaca">
                                    <i class="ri-check-line"></i>
                                </button>
                            @endif
                            <button type="button" class="notification-action-btn delete delete-btn"
                                data-id="{{ $notification->id }}" title="Hapus">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="empty-notifications">
                        <i class="ri-notification-off-line"></i>
                        <p>Tidak ada notifikasi</p>
                    </div>
                @endforelse
            </div>

            @if ($notifications->hasPages())
                <div class="pagination-wrapper">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Mark single notification as read
            $('.mark-read-btn').on('click', function () {
                const id = $(this).data('id');
                const item = $(this).closest('.notification-item-full');

                $.ajax({
                    url: `/admin/notifications/${id}/read`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function () {
                        item.removeClass('unread');
                        item.find('.notification-status').removeClass('unread').addClass('read').text(
                            'Dibaca');
                        item.find('.mark-read-btn').remove();
                    }
                });
            });

            // Delete notification
            $('.delete-btn').on('click', function () {
                if (!confirm('Hapus notifikasi ini?')) return;

                const id = $(this).data('id');
                const item = $(this).closest('.notification-item-full');

                $.ajax({
                    url: `/admin/notifications/${id}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function () {
                        item.fadeOut(300, function () {
                            $(this).remove();
                        });
                    }
                });
            });

            // Mark all as read
            $('#markAllReadBtnPage').on('click', function () {
                $.ajax({
                    url: '/admin/notifications/read-all',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function () {
                        $('.notification-item-full').removeClass('unread');
                        $('.notification-status').removeClass('unread').addClass('read').text('Dibaca');
                        $('.mark-read-btn').remove();
                    }
                });
            });
        });
    </script>
@endpush