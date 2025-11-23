<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\JobPosting;
use App\Models\ResumeAnalysis;
use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
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
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB max
        ]);

        // Store resume
        $resumePath = $request->file('resume')->store('resumes', 'public');

        // Create application
        $application = Application::create([
            'job_posting_id' => $job->id,
            'candidate_name' => $validated['candidate_name'],
            'candidate_email' => $validated['candidate_email'],
            'candidate_phone' => $validated['candidate_phone'] ?? null,
            'resume_path' => $resumePath,
            'status' => 'screening',
        ]);

        // Trigger AI screening asynchronously (or queue it)
        try {
            $this->screenResume($application, $job);
        } catch (\Exception $e) {
            // Log error but don't fail the application
            \Log::error('AI Screening failed: ' . $e->getMessage());
            $application->update(['status' => 'pending']);
        }

        return redirect()->route('applications.success')
            ->with('success', 'Application submitted successfully! We will review your resume and get back to you soon.');
    }

    public function success()
    {
        return view('applications.success');
    }

    protected function screenResume(Application $application, JobPosting $job)
    {
        // Extract text from resume (simplified - in production use a PDF parser)
        $resumeText = $this->extractResumeText($application->resume_path);

        // Get job requirements
        $jobDescription = $job->description . "\n\nRequirements:\n" . $job->requirements;

        // Call AI service
        $analysis = $this->aiService->analyzeResume($resumeText, $jobDescription);

        if (!empty($analysis)) {
            // Save analysis
            ResumeAnalysis::create([
                'application_id' => $application->id,
                'extracted_data' => $analysis['extracted_data'] ?? [],
                'match_score' => $analysis['match_score'] ?? 0,
                'ai_feedback' => $analysis['recommendation'] ?? '',
                'strengths' => $analysis['strengths'] ?? '',
                'gaps' => $analysis['gaps'] ?? '',
            ]);

            // Update application with score
            $application->update([
                'ai_score' => $analysis['match_score'] ?? 0,
                'status' => $this->determineStatus($analysis),
            ]);
        }
    }

    protected function extractResumeText(string $path): string
    {
        // Simplified text extraction
        // In production, use libraries like Smalot\PdfParser or similar
        $fullPath = Storage::disk('public')->path($path);
        
        // For now, return placeholder text
        // TODO: Implement actual PDF/DOCX parsing
        return "Resume content will be extracted here using a PDF parser library.";
    }

    protected function determineStatus(array $analysis): string
    {
        $score = $analysis['match_score'] ?? 0;
        $recommendation = $analysis['recommendation'] ?? '';

        if ($recommendation === 'hire' || $score >= 80) {
            return 'shortlisted';
        } elseif ($recommendation === 'interview' || $score >= 60) {
            return 'pending';
        } else {
            return 'rejected';
        }
    }
}
