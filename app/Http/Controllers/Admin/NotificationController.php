<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Display a listing of all notifications.
     */
    public function index(Request $request)
    {
        $query = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('filter')) {
            if ($request->filter === 'unread') {
                $query->unread();
            } elseif ($request->filter === 'read') {
                $query->read();
            }
        }

        $notifications = $query->paginate(15);

        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Get unread notifications for dropdown (JSON).
     */
    public function getUnread(): JsonResponse
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->unread()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $unreadCount = Notification::where('user_id', auth()->id())
            ->unread()
            ->count();

        return response()->json([
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'link' => $notification->link,
                    'icon' => $notification->icon,
                    'color' => $notification->color,
                    'time' => $notification->created_at->diffForHumans(),
                    'read' => $notification->isRead(),
                ];
            }),
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(Notification $notification): JsonResponse
    {
        // Ensure the notification belongs to the authenticated user
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai sebagai dibaca',
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(): JsonResponse
    {
        Notification::where('user_id', auth()->id())
            ->unread()
            ->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi ditandai sebagai dibaca',
        ]);
    }

    /**
     * Remove the specified notification.
     */
    public function destroy(Notification $notification): JsonResponse
    {
        // Ensure the notification belongs to the authenticated user
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dihapus',
        ]);
    }
}
