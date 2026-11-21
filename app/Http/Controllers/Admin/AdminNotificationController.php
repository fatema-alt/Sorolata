<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Services\Admin\AdminNotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminNotificationController extends Controller
{
    public function __construct(
        protected AdminNotificationService $notificationService
    ) {}

    /**
     * Display a listing of admin notifications with filtering
     */
    public function index(Request $request): View
    {
        $filterType = $request->query('type', 'all');
        $filterStatus = $request->query('status', 'all');

        $query = AdminNotification::latest();

        if ($filterType && $filterType !== 'all') {
            $query->where('type', $filterType);
        }

        if ($filterStatus === 'unread') {
            $query->where('is_read', false);
        } elseif ($filterStatus === 'read') {
            $query->where('is_read', true);
        }

        $notifications = $query->paginate(20)->withQueryString();

        $unreadCount = $this->notificationService->getUnreadCount();
        $totalCount = AdminNotification::count();

        // Count by type for badges
        $typeCounts = [
            'all' => $totalCount,
            'order' => AdminNotification::where('type', 'order')->count(),
            'payment' => AdminNotification::where('type', 'payment')->count(),
            'cancellation' => AdminNotification::where('type', 'cancellation')->count(),
            'stock' => AdminNotification::where('type', 'stock')->count(),
            'review' => AdminNotification::where('type', 'review')->count(),
        ];

        return view('admin.notifications.index', compact(
            'notifications',
            'unreadCount',
            'totalCount',
            'typeCounts',
            'filterType',
            'filterStatus'
        ));
    }

    /**
     * Mark a single notification as read and optionally redirect to its action URL
     */
    public function markAsRead(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $notification = AdminNotification::findOrFail($id);
        $notification->markAsRead();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read.',
            ]);
        }

        if ($request->boolean('redirect') && !empty($notification->action_url)) {
            return redirect($notification->action_url);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request): JsonResponse|RedirectResponse
    {
        $updated = $this->notificationService->markAllAsRead();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'count' => $updated,
                'message' => 'All notifications marked as read.',
            ]);
        }

        return back()->with('success', "{$updated} notification(s) marked as read.");
    }

    /**
     * Delete a notification
     */
    public function destroy(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $deleted = $this->notificationService->delete($id);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => $deleted,
                'message' => 'Notification deleted successfully.',
            ]);
        }

        return back()->with('success', 'Notification deleted successfully.');
    }

    /**
     * Polling endpoint for real-time header bell updates
     */
    public function unreadCount(): JsonResponse
    {
        $count = $this->notificationService->getUnreadCount();
        $latest = $this->notificationService->getLatest(6)->map(function (AdminNotification $n) {
            return [
                'id' => $n->id,
                'type' => $n->type,
                'type_label' => $n->getTypeLabel(),
                'type_color' => $n->getTypeColor(),
                'title' => $n->title,
                'message' => $n->message,
                'action_url' => $n->action_url,
                'is_read' => $n->is_read,
                'time_ago' => $n->created_at?->diffForHumans() ?? 'recently',
            ];
        });

        return response()->json([
            'count' => $count,
            'notifications' => $latest,
        ]);
    }
}
