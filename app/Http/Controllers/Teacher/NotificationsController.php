<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    /**
     * Display all notifications for the teacher
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Get all notifications (from Notifiable trait)
        $notifications = $user->notifications()->paginate(15);
        
        // Get unread count (from Notifiable trait)
        $unreadCount = $user->unreadNotifications()->count();
        
        return view('teacher.notifications.index', compact('notifications', 'unreadCount'));
    }
    
    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $notification = $user->notifications()->find($id);
        
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 404);
    }
    
    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();
        
        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
    
    /**
     * Delete a notification
     */
    public function destroy($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $notification = $user->notifications()->find($id);
        
        if ($notification) {
            $notification->delete();
            return redirect()->back()->with('success', 'Notification deleted.');
        }
        
        return redirect()->back()->with('error', 'Notification not found.');
    }
}
