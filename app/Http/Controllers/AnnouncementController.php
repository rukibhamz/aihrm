<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of announcements for the current user.
     */
    public function index()
    {
        $user = Auth::user();
        
        $announcements = Announcement::published()
            ->visibleTo($user)
            ->pinnedFirst()
            ->with('author')
            ->paginate(10);

        // Mark unread count
        $unreadCount = Announcement::published()
            ->visibleTo($user)
            ->whereDoesntHave('readBy', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->count();

        return view('announcements.index', compact('announcements', 'unreadCount'));
    }

    /**
     * Display a single announcement.
     */
    public function show(Announcement $announcement)
    {
        $user = Auth::user();

        // Check if user can view this announcement
        if ($announcement->type === 'department' && 
            $announcement->department_id !== $user->employee?->department_id) {
            abort(403, 'You do not have access to this announcement.');
        }

        // Mark as read
        if (!$announcement->isReadBy($user)) {
            $announcement->readBy()->attach($user->id, ['read_at' => now()]);
        }

        return view('announcements.show', compact('announcement'));
    }

    /**
     * Mark an announcement as read via AJAX.
     */
    public function markAsRead(Announcement $announcement)
    {
        $user = Auth::user();

        if (!$announcement->isReadBy($user)) {
            $announcement->readBy()->attach($user->id, ['read_at' => now()]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Get unread announcements count for polling.
     */
    public function unreadCount()
    {
        $user = Auth::user();

        $count = Announcement::published()
            ->visibleTo($user)
            ->whereDoesntHave('readBy', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->count();

        return response()->json(['count' => $count]);
    }
}
