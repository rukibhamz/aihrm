<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\JobPosting;
use App\Notifications\ApplicationStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function updateStatus(Request $request, Application $application)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,shortlisted,rejected',
        ]);

        $application->update($validated);

        // Notify the candidate
        try {
            $application->notify(new ApplicationStatusChanged($application));
        } catch (\Exception $e) {
            \Log::error('Failed to notify candidate: ' . $e->getMessage());
        }

        return back()->with('success', 'Application status updated and candidate notified.');
    }

    public function create(JobPosting $job)
    {
        return view('applications.create', compact('job'));
    }

    public function store(Request $request, JobPosting $job)
    {
        $validated = $request->validate([
            'candidate_name' => 'required|string|max:255',
            'candidate_email' => 'required|email|max:255',
            'candidate_phone' => 'nullable|string|max:20',
            'current_city' => 'nullable|string|max:255',
            'current_job_title' => 'nullable|string|max:255',
            'expected_salary' => 'nullable|string|max:255',
            'notice_period' => 'nullable|string|max:255',
            'years_of_experience' => 'nullable|string|max:255',
            'cover_letter' => 'nullable|string',
            'motivation' => 'nullable|string',
            'linkedin_url' => 'nullable|url|max:255',
            'portfolio_url' => 'nullable|url|max:255',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB max
        ]);

        // Store resume
        $resumePath = $request->file('resume')->store('resumes', 'public');

        // Create application
        Application::create([
            'job_posting_id' => $job->id,
            'candidate_name' => $validated['candidate_name'],
            'candidate_email' => $validated['candidate_email'],
            'candidate_phone' => $validated['candidate_phone'] ?? null,
            'current_city' => $validated['current_city'] ?? null,
            'current_job_title' => $validated['current_job_title'] ?? null,
            'expected_salary' => $validated['expected_salary'] ?? null,
            'notice_period' => $validated['notice_period'] ?? null,
            'years_of_experience' => $validated['years_of_experience'] ?? null,
            'cover_letter' => $validated['cover_letter'] ?? null,
            'motivation' => $validated['motivation'] ?? null,
            'linkedin_url' => $validated['linkedin_url'] ?? null,
            'portfolio_url' => $validated['portfolio_url'] ?? null,
            'resume_path' => $resumePath,
            'status' => 'pending',
        ]);

        return redirect()->route('applications.success')
            ->with('success', 'Application submitted successfully! We will review your resume and get back to you soon.');
    }

    public function success()
    {
        return view('applications.success');
    }
}
