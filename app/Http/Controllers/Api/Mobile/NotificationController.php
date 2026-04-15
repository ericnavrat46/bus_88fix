<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * GET /api/notifications/{user_id}
     */
    public function index($user_id)
    {
        $notifications = Notification::where('user_id', $user_id)
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data'    => $notifications,
        ]);
    }

    /**
     * GET /api/notifications/{user_id}/unread-count
     */
    public function unreadCount($user_id)
    {
        $count = Notification::where('user_id', $user_id)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'success' => true,
            'count'   => $count,
        ]);
    }

    /**
     * POST /api/notifications/{user_id}/read-all
     */
    public function markAllAsRead($user_id)
    {
        Notification::where('user_id', $user_id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi ditandai sudah dibaca.',
        ]);
    }

    /**
     * POST /api/notifications/{id}/read
     */
    public function markAsRead($id)
    {
        $notif = Notification::findOrFail($id);
        $notif->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai sudah dibaca.',
        ]);
    }

    /**
     * DELETE /api/notifications/{id}
     */
    public function destroy($id)
    {
        $notif = Notification::findOrFail($id);
        $notif->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dihapus.',
        ]);
    }
}