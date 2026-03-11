<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PeerFeedbackController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Requests I have sent to others
        $sentRequests = \App\Models\PeerFeedbackRequest::with(['targetUser', 'responses'])
            ->where('requester_id', $userId)
            ->latest()
            ->get();

        // Requests I need to respond to
        $pendingReviews = \App\Models\PeerFeedbackRequest::with('requester')
            ->where('target_user_id', $userId)
            ->where('status', 'pending')
            ->latest()
            ->get();
            
        // Reviews I have completed
        $completedReviews = \App\Models\PeerFeedbackResponse::with('request.requester')
            ->where('reviewer_id', $userId)
            ->latest()
            ->get();

        $peers = \App\Models\User::where('id', '!=', $userId)->whereHas('roles', function($q) {
            $q->where('name', 'Employee');
        })->get();

        return view('employee.peer-feedback.index', compact('sentRequests', 'pendingReviews', 'completedReviews', 'peers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'target_user_id' => 'required|exists:users,id',
            'context' => 'nullable|string',
            'due_date' => 'required|date|after_or_equal:today',
        ]);

        if ($request->target_user_id == auth()->id()) {
            return back()->withErrors(['target_user_id' => 'You cannot request feedback from yourself.']);
        }

        \App\Models\PeerFeedbackRequest::create([
            'requester_id' => auth()->id(),
            'target_user_id' => $request->target_user_id,
            'context' => $request->context,
            'due_date' => $request->due_date,
            'status' => 'pending',
        ]);

        // In a real app, send notification here

        return redirect()->route('employee.peer-feedback.index')->with('success', 'Feedback request sent successfully.');
    }

    public function show(\App\Models\PeerFeedbackRequest $peerFeedbackRequest)
    {
        // Only target user or requester can view
        if ($peerFeedbackRequest->target_user_id !== auth()->id() && $peerFeedbackRequest->requester_id !== auth()->id()) {
            abort(403);
        }

        return view('employee.peer-feedback.show', compact('peerFeedbackRequest'));
    }

    public function submit(Request $request, \App\Models\PeerFeedbackRequest $peerFeedbackRequest)
    {
        if ($peerFeedbackRequest->target_user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'strengths' => 'required|string',
            'areas_for_improvement' => 'required|string',
            'collaboration_rating' => 'required|integer|min:1|max:5',
            'communication_rating' => 'required|integer|min:1|max:5',
            'additional_comments' => 'nullable|string',
        ]);

        \App\Models\PeerFeedbackResponse::create([
            'request_id' => $peerFeedbackRequest->id,
            'reviewer_id' => auth()->id(),
            'strengths' => $request->strengths,
            'areas_for_improvement' => $request->areas_for_improvement,
            'collaboration_rating' => $request->collaboration_rating,
            'communication_rating' => $request->communication_rating,
            'additional_comments' => $request->additional_comments,
        ]);

        $peerFeedbackRequest->update(['status' => 'completed']);

        // In a real app, send notification back to requester here

        return redirect()->route('employee.peer-feedback.index')->with('success', 'Feedback submitted successfully.');
    }
}
