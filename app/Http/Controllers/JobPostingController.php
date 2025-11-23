<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobPostingController extends Controller
{
    public function index()
    {
        $jobs = JobPosting::with('creator')
            ->latest()
            ->paginate(15);
        
        return view('jobs.index', compact('jobs'));
    }

    public function create()
    {
        return view('jobs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'department' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:open,closed,draft',
        ]);

        $validated['created_by'] = Auth::id();

        JobPosting::create($validated);

        return redirect()->route('jobs.index')->with('success', 'Job posting created successfully.');
    }

    public function show(JobPosting $job)
    {
        $job->load(['applications.resumeAnalysis']);
        
        // Sort applications by AI score
        $applications = $job->applications()
            ->orderByDesc('ai_score')
            ->get();
        
        return view('jobs.show', compact('job', 'applications'));
    }

    public function edit(JobPosting $job)
    {
        return view('jobs.edit', compact('job'));
    }

    public function update(Request $request, JobPosting $job)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'department' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:open,closed,draft',
        ]);

        $job->update($validated);

        return redirect()->route('jobs.show', $job)->with('success', 'Job posting updated successfully.');
    }

    public function destroy(JobPosting $job)
    {
        $job->delete();
        return redirect()->route('jobs.index')->with('success', 'Job posting deleted successfully.');
    }
}
