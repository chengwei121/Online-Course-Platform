<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->middleware('auth');
        $this->notificationService = $notificationService;
    }

    /**
     * Display notifications page
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = $user->customNotifications()->with('sender');
        
        // Filter by type if specified
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }
        
        // Filter by read status
        if ($request->has('status')) {
            if ($request->status === 'unread') {
                $query->unread();
            } elseif ($request->status === 'read') {
                $query->read();
            }
        }
        
        $notifications = $query->latest()->paginate(15);
        
        // Get notification types for filter
        $notificationTypes = $user->customNotifications()
            ->select('type')
            ->groupBy('type')
            ->pluck('type');
        
        return view('client.notifications.index', compact('notifications', 'notificationTypes'));
    }

    /**
     * Get notifications for AJAX (for dropdown/header)
     */
    public function getNotifications(Request $request)
    {
        $user = Auth::user();
        $notifications = $this->notificationService->getRecentNotifications($user, 5);
        $unreadCount = $this->notificationService->getUnreadCount($user);
        
        return response()->json([
            'notifications' => $notifications->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'time_ago' => $notification->time_ago,
                    'is_read' => $notification->is_read,
                    'icon' => $notification->icon,
                    'color' => $notification->color,
                    'action_url' => $notification->action_url,
                ];
            }),
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Notification $notification)
    {
        // Ensure user can only mark their own notifications
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $notification->markAsRead();
        
        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }
        
        // Redirect to action URL if available
        if ($notification->action_url) {
            return redirect($notification->action_url);
        }
        
        return back()->with('success', 'Notification marked as read');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $count = $this->notificationService->markAllAsRead(Auth::user());
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true, 
                'marked_count' => $count
            ]);
        }
        
        return back()->with('success', "Marked {$count} notifications as read");
    }

    /**
     * Delete a notification
     */
    public function destroy(Notification $notification)
    {
        // Ensure user can only delete their own notifications
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $notification->delete();
        
        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }
        
        return back()->with('success', 'Notification deleted');
    }

    /**
     * Get unread count (for AJAX)
     */
    public function getUnreadCount()
    {
        $count = $this->notificationService->getUnreadCount(Auth::user());
        return response()->json(['count' => $count]);
    }
}
