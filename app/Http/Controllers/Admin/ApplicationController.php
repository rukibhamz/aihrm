<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ScreenApplicationJob;
use App\Models\Application;
use App\Models\JobPosting;
use App\Notifications\ApplicationStatusChanged;
use App\Services\Ai\AiConfig;
use App\Services\AtsAutomationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        
        $query = Application::with(['jobPosting', 'resumeAnalysis']);
        
        if ($jobId) {
            $query->where('job_posting_id', $jobId);
        }

        $applications = $query->get();

        // Group by status
        $board = [
            'applied' => $applications->whereIn('status', ['applied', 'pending']),
            'screening' => $applications->where('status', 'screening'),
            'interview' => $applications->where('status', 'interview'),
            'offer' => $applications->where('status', 'offer'),
            'hired' => $applications->where('status', 'hired'),
            'rejected' => $applications->where('status', 'rejected'),
        ];

        $jobs = JobPosting::where('status', 'open')->get();

        return view('admin.recruitment.kanban', compact('board', 'jobs', 'jobId'));
    }

    public function show(Application $application)
    {
        $application->load('jobPosting', 'interviews.interviewer', 'interviews.scorecard', 'resumeAnalysis');
        $interviewers = \App\Models\User::role(['Admin', 'Manager', 'HR'])->get();
        return view('admin.recruitment.show', compact('application', 'interviewers'));
    }

    public function updateStatus(Request $request, Application $application)
    {
        $request->validate([
            'status' => 'required|in:applied,screening,interview,offer,hired,rejected',
        ]);

        $oldStatus = $application->status;
        $newStatus = $request->status;
        
        $application->update(['status' => $newStatus]);

        if ($oldStatus !== $newStatus) {
            try {
                $application->loadMissing('jobPosting');
                if ($newStatus === 'rejected' && AiConfig::autoRejectionEmail()) {
                    app(AtsAutomationService::class)->sendRejectionEmail($application);
                } elseif ($newStatus !== 'rejected') {
                    $application->notify(new ApplicationStatusChanged($application));
                }
            } catch (\Exception $e) {
                Log::error('Failed to send status update email: ' . $e->getMessage());
            }
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Status updated successfully']);
        }

        return redirect()->back()->with('success', 'Candidate status updated.');
    }

    /**
     * Re-run AI screening for an application.
     */
    public function rescreen(Application $application)
    {
        ScreenApplicationJob::dispatch($application->id);

        return back()->with('success', 'AI screening queued for this application.');
    }
}
