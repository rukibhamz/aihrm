<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
// use App\Mail\ApplicationStatusChanged; // To be created

class ApplicationController extends Controller
{
    public function index()
    {
        // Default list view if needed, but we focus on Kanban
        $applications = Application::with('jobPosting')->latest()->paginate(20);
        return view('admin.recruitment.index', compact('applications'));
    }

    public function kanban(Request $request)
    {
        $jobId = $request->get('job_posting_id');
        
        $query = Application::with('jobPosting');
        
        if ($jobId) {
            $query->where('job_posting_id', $jobId);
        }

        $applications = $query->get();

        // Group by status
        $board = [
            'applied' => $applications->where('status', 'applied'),
            'screening' => $applications->where('status', 'screening'),
            'interview' => $applications->where('status', 'interview'),
            'offer' => $applications->where('status', 'offer'),
            'hired' => $applications->where('status', 'hired'),
            'rejected' => $applications->where('status', 'rejected'),
        ];

        $jobs = JobPosting::where('status', 'open')->get();

        return view('admin.recruitment.kanban', compact('board', 'jobs', 'jobId'));
    }

    public function updateStatus(Request $request, Application $application)
    {
        $request->validate([
            'status' => 'required|in:applied,screening,interview,offer,hired,rejected',
        ]);

        $oldStatus = $application->status;
        $newStatus = $request->status;
        
        $application->update(['status' => $newStatus]);

        // Send Email Notification if status changed
        if ($oldStatus !== $newStatus) {
            try {
                $application->notify(new \App\Notifications\ApplicationStatusChanged($application));
            } catch (\Exception $e) {
                // Log and continue, don't fail the request
                \Illuminate\Support\Facades\Log::error('Failed to send status update email: ' . $e->getMessage());
            }
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Status updated successfully']);
        }

        return redirect()->back()->with('success', 'Candidate status updated.');
    }
}
