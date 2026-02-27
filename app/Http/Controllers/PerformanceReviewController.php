<?php

namespace App\Http\Controllers;

use App\Models\PerformanceReview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerformanceReviewController extends Controller
{
    public function index()
    {
        // Show reviews where user is reviewee OR reviewer
        $reviews = PerformanceReview::with(['reviewee', 'reviewer'])
            ->where('reviewee_id', Auth::id())
            ->orWhere('reviewer_id', Auth::id())
            ->latest()
            ->paginate(10);
            
        return view('performance.reviews.index', compact('reviews'));
    }

    public function create()
    {
        // Manager can start a review for their team
        $employees = \App\Models\Employee::where('manager_id', Auth::id())->with('user')->get();
        return view('performance.reviews.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reviewee_id' => 'required|exists:users,id',
            'period' => 'required|string|max:255',
            'type' => 'required|in:manager,peer',
        ]);

        PerformanceReview::create([
            'reviewee_id' => $validated['reviewee_id'],
            'reviewer_id' => Auth::id(),
            'period' => $validated['period'],
            'type' => $validated['type'],
            'status' => 'draft',
        ]);

        return redirect()->route('performance.reviews.index')->with('success', 'Review cycle started.');
    }

    public function show(PerformanceReview $review)
    {
        if ($review->reviewee_id !== Auth::id() && $review->reviewer_id !== Auth::id()) {
            abort(403);
        }
        
        return view('performance.reviews.show', compact('review'));
    }

    public function update(Request $request, PerformanceReview $review)
    {
        if ($review->reviewer_id !== Auth::id()) {
            abort(403, 'Only the reviewer can submit the evaluation.');
        }

        $validated = $request->validate([
            'content' => 'required|array',
            'status' => 'required|in:draft,submitted,completed',
        ]);

        $review->update([
            'content' => $validated['content'],
            'status' => $validated['status'],
        ]);
        
        return back()->with('success', 'Review updated successfully.');
    }
}
