<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 5);
        $page = (int) $request->get('page', 1);

        $user = auth()->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return NotificationResource::collection($notifications)
            ->additional([
                'meta' => [
                    'current_page' => $notifications->currentPage(),
                    'last_page' => $notifications->lastPage(),
                    'total' => $notifications->total(),
                    'per_page' => $notifications->perPage(),
                    'unread_count' => $user->unreadNotifications()->count(), // ← هنا
                ],
            ]);
    }

    public function show($id)
    {
        $user = auth()->user();
        $notification = $user->notifications()->where('id', $id)->firstOrFail();

        // اعمل read للإشعار
        if (! $notification->read_at) {
            $notification->markAsRead();
        }
        $resource = new NotificationResource($notification);
        $resource = json_decode(json_encode($resource));
        $modelUrl = $resource->model_url;

        return $modelUrl ? redirect($modelUrl) : back();
    }

    public function markRead(Request $request, $id)
    {
        // جلب الـ notification من خلال الـ auth user
        $notification = $request->user()->notifications()->find($id);

        if (! $notification) {
            return response()->json([
                'message' => 'Notification not found',
            ], 404);
        }

        if (! $notification->read_at) {
            $notification->markAsRead();
        }

        // جلب عدد الإشعارات غير المقروءة بعد التحديث
        $unreadCount = $request->user()->unreadNotifications()->count();

        return response()->json([
            'message' => 'Notification marked as read',
            'unread_count' => $unreadCount, // ✅ نرجع العدد هنا
        ]);
    }

    public function markAllRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return response()->json(['message' => 'All notifications marked as read']);
    }
}
