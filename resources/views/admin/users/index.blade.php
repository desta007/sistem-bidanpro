@extends('layouts.admin')

@section('title', 'Kelola Staff')
@section('page-title', 'Kelola Staff')
@section('page-subtitle', 'Manajemen pengguna dan hak akses sistem')

@push('styles')
    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(233, 30, 140, 0.35);
        }

        .users-table {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .users-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .users-table th {
            padding: 16px 20px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
        }

        .users-table td {
            padding: 16px 20px;
            border-bottom: 1px solid var(--gray-100);
            vertical-align: middle;
        }

        .users-table tr:hover {
            background: var(--gray-50);
        }

        .user-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            object-fit: cover;
        }

        .user-info h4 {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 2px;
        }

        .user-info span {
            font-size: 12px;
            color: var(--gray-500);
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
        }

        .role-badge.bidan {
            background: rgba(233, 30, 140, 0.1);
            color: var(--primary);
        }

        .role-badge.staff {
            background: rgba(59, 130, 246, 0.1);
            color: #3B82F6;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .status-badge.active {
            background: rgba(16, 185, 129, 0.1);
            color: #10B981;
        }

        .status-badge.inactive {
            background: rgba(239, 68, 68, 0.1);
            color: #EF4444;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }

        .action-btn.edit {
            background: rgba(233, 30, 140, 0.1);
            color: var(--primary);
        }

        .action-btn.edit:hover {
            background: var(--primary);
            color: white;
        }

        .action-btn.delete {
            background: rgba(239, 68, 68, 0.1);
            color: #EF4444;
        }

        .action-btn.delete:hover {
            background: #EF4444;
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state-icon {
            width: 80px;
            height: 80px;
            background: var(--gray-100);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 32px;
            color: var(--gray-400);
        }

        .empty-state h3 {
            font-size: 16px;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 8px;
        }

        .empty-state p {
            font-size: 14px;
            color: var(--gray-500);
        }

        .pagination-wrapper {
            display: flex;
            justify-content: center;
            padding: 20px;
            background: var(--gray-50);
            border-top: 1px solid var(--gray-200);
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                gap: 16px;
                align-items: flex-start;
            }

            .users-table {
                overflow-x: auto;
            }

            .users-table table {
                min-width: 700px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div>
            <p style="color: var(--gray-500); font-size: 14px;">Total {{ $users->total() }} pengguna terdaftar</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="ri-user-add-line"></i>
            Tambah Staff
        </a>
    </div>

    <div class="users-table">
        @if($users->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>Role</th>
                        <th>No. Telepon</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=E91E8C&color=fff"
                                        alt="{{ $user->name }}" class="user-avatar">
                                    <div class="user-info">
                                        <h4>{{ $user->name }}</h4>
                                        <span>{{ $user->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="role-badge {{ $user->role }}">
                                    <i class="ri-{{ $user->role === 'bidan' ? 'nurse' : 'user' }}-line"></i>
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>{{ $user->phone ?? '-' }}</td>
                            <td>
                                <span class="status-badge {{ $user->is_active ? 'active' : 'inactive' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="action-btn edit" title="Edit">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan staff ini?');"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn delete" title="Nonaktifkan">
                                                <i class="ri-user-unfollow-line"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($users->hasPages())
                <div class="pagination-wrapper">
                    {{ $users->links() }}
                </div>
            @endif

        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="ri-user-line"></i>
                </div>
                <h3>Belum ada staff</h3>
                <p>Tambahkan staff pertama untuk memulai</p>
            </div>
        @endif
    </div>
@endsection