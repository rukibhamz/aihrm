<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the announcements.
     */
    public function index()
    {
        $announcements = Announcement::with(['author', 'department'])
            ->latest()
            ->paginate(10);

        return view('admin.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function create()
    {
        $departments = Department::all();
        return view('admin.announcements.create', compact('departments'));
    }

    /**
     * Store a newly created announcement in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:all,department',
            'department_id' => 'required_if:type,department|nullable|exists:departments,id',
            'pinned' => 'boolean',
            'publish_now' => 'boolean',
        ]);

        $announcement = new Announcement();
        $announcement->title = $validated['title'];
        $announcement->content = $validated['content'];
        $announcement->type = $validated['type'];
        $announcement->department_id = $validated['type'] === 'department' ? $validated['department_id'] : null;
        $announcement->pinned = $request->boolean('pinned');
        $announcement->author_id = Auth::id();

        if ($request->boolean('publish_now')) {
            $announcement->published_at = now();
        }

        $announcement->save();

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    /**
     * Show the form for editing the specified announcement.
     */
    public function edit(Announcement $announcement)
    {
        $departments = Department::all();
        return view('admin.announcements.edit', compact('announcement', 'departments'));
    }

    /**
     * Update the specified announcement in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:all,department',
            'department_id' => 'required_if:type,department|nullable|exists:departments,id',
            'pinned' => 'boolean',
        ]);

        $announcement->title = $validated['title'];
        $announcement->content = $validated['content'];
        $announcement->type = $validated['type'];
        $announcement->department_id = $validated['type'] === 'department' ? $validated['department_id'] : null;
        $announcement->pinned = $request->boolean('pinned');

        // If not published yet and user wants to publish now
        if (!$announcement->published_at && $request->boolean('publish_now')) {
            $announcement->published_at = now();
        }

        $announcement->save();

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    /**
     * Publish an announcement immediately.
     */
    public function publish(Announcement $announcement)
    {
        if (!$announcement->published_at) {
            $announcement->published_at = now();
            $announcement->save();
        }

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement published successfully.');
    }

    /**
     * Remove the specified announcement from storage.
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }
}
