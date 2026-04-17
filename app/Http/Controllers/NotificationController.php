<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
   public function index()
    {
        $allNotifications = Auth::user()->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Filter by type - Updated to include admin invitation types
        $userNotifications = Auth::user()->notifications()
            ->whereIn('type', [
                'account_approved', 
                'account_rejected', 
                'user_registration',
                'admin_invitation',
                'admin_invitation_accepted',
                'admin_invitation_declined'
            ])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $postNotifications = Auth::user()->notifications()
            ->whereIn('type', ['post_approved', 'post_rejected', 'post_pending', 'post_edit_approved', 'post_edit_rejected', 'post_edit_pending'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $likeNotifications = Auth::user()->notifications()
            ->where('type', 'like')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $commentNotifications = Auth::user()->notifications()
            ->where('type', 'comment')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $idRequestNotifications = Auth::user()->notifications()
            ->whereIn('type', ['id_request', 'id_request_approved', 'id_request_rejected'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('notifications.index', compact(
            'allNotifications', 
            'userNotifications', 
            'postNotifications', 
            'likeNotifications', 
            'commentNotifications', 
            'idRequestNotifications'
        ));
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->update(['is_read' => true]);
        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead()
    {
        Auth::user()->notifications()->update(['is_read' => true]);
        return back()->with('success', 'All notifications marked as read.');
    }
}