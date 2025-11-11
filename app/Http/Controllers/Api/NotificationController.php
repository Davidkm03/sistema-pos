<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Display a listing of the user's notifications.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Notification::forUser(auth()->id())
            ->orderBy('created_at', 'desc');

        // Filtros opcionales
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('read')) {
            if ($request->boolean('read')) {
                $query->read();
            } else {
                $query->unread();
            }
        }

        $perPage = $request->get('per_page', 20);
        $notifications = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $notifications,
            'unread_count' => Notification::getUnreadCountForUser(auth()->id()),
        ]);
    }

    /**
     * Get unread notifications count.
     */
    public function unreadCount(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'unread_count' => Notification::getUnreadCountForUser(auth()->id()),
        ]);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(string $id): JsonResponse
    {
        $notification = Notification::find($id);

        if (!$notification || $notification->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Notificación no encontrada',
            ], 404);
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notificación marcada como leída',
            'unread_count' => Notification::getUnreadCountForUser(auth()->id()),
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(): JsonResponse
    {
        Notification::forUser(auth()->id())
            ->unread()
            ->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Todas las notificaciones marcadas como leídas',
            'unread_count' => 0,
        ]);
    }

    /**
     * Delete a notification.
     */
    public function destroy(string $id): JsonResponse
    {
        $notification = Notification::find($id);

        if (!$notification || $notification->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Notificación no encontrada',
            ], 404);
        }

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notificación eliminada',
            'unread_count' => Notification::getUnreadCountForUser(auth()->id()),
        ]);
    }

    /**
     * Get notification types available.
     */
    public function types(): JsonResponse
    {
        $types = [
            'stock_low' => 'Stock Bajo',
            'sale_completed' => 'Venta Completada',
            'large_sale' => 'Venta Grande',
            'system_error' => 'Error del Sistema',
            'new_quote' => 'Nueva Cotización',
            'quote_converted' => 'Cotización Convertida',
            'goal_achieved' => 'Meta Alcanzada',
        ];

        return response()->json([
            'success' => true,
            'types' => $types,
        ]);
    }
}
