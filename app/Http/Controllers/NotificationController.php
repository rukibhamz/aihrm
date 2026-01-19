<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(15);
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    }

    public function poll()
    {
        $notification = Auth::user()->unreadNotifications()->latest()->first();
        
        if ($notification) {
            return response()->json([
                'id' => $notification->id,
                'message' => $notification->data['message'] ?? 'New update available',
                'type' => $notification->data['type'] ?? 'info',
            ]);
        }
        
        return response()->json(null);
    }
}
