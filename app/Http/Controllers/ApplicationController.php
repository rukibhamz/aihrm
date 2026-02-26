<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\JobPosting;
use App\Models\ResumeAnalysis;
use App\Services\AIService;
use App\Notifications\ApplicationStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

class ApplicationController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

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
        $application = Application::create([
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
            $status = $this->determineStatus($analysis);
            $application->update([
                'ai_score' => $analysis['match_score'] ?? 0,
                'status' => $status,
            ]);

            // Notify candidate of initial screening result
            try {
                $application->notify(new ApplicationStatusChanged($application));
            } catch (\Exception $e) {
                \Log::error('Initial screening notification failed: ' . $e->getMessage());
            }
        }
    }

    protected function extractResumeText(string $path): string
    {
        $fullPath = Storage::disk('public')->path($path);
        
        if (!file_exists($fullPath)) {
            \Log::error("Resume file not found: " . $fullPath);
            return "Resume file not found.";
        }

        $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

        try {
            if ($extension === 'pdf') {
                $parser = new \Smalot\PdfParser\Parser();
                $pdf = $parser->parseFile($fullPath);
                return $pdf->getText();
            }
            
            if ($extension === 'docx') {
                $phpWord = \PhpOffice\PhpWord\IOFactory::load($fullPath);
                $text = '';
                foreach ($phpWord->getSections() as $section) {
                    foreach ($section->getElements() as $element) {
                        if (method_exists($element, 'getText')) {
                            $text .= $element->getText() . "\n";
                        }
                    }
                }
                return $text;
            }
            
            return "Resume content extraction for {$extension} is not yet supported. Please upload a PDF or DOCX file.";
        } catch (\Exception $e) {
            \Log::error('Resume Extraction Error: ' . $e->getMessage());
            return "Error extracting resume content: " . $e->getMessage();
        }
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
