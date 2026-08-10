<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Interview;
use App\Models\ResumeAnalysis;
use App\Models\Setting;
use App\Notifications\ApplicationStatusChanged;
use App\Notifications\InterviewInvitation;
use App\Services\Ai\AiConfig;
use Illuminate\Support\Facades\Log;

class AtsAutomationService
{
    public function __construct(
        protected AIService $aiService,
        protected ResumeTextExtractor $extractor
    ) {}

    /**
     * Full screen pipeline: extract → AI analyze → save → optional auto-actions.
     */
    public function screenApplication(Application $application): void
    {
        $application->loadMissing('jobPosting');

        if (! $application->jobPosting) {
            Log::warning("ScreenApplication: missing job for application {$application->id}");

            return;
        }

        $job = $application->jobPosting;
        $description = trim(($job->description ?? '') . "\n\n" . ($job->requirements ?? ''));

        try {
            $resumeText = $this->extractor->extractFromStoragePath($application->resume_path);
        } catch (\Throwable $e) {
            Log::error("Resume extract failed for application {$application->id}: " . $e->getMessage());
            $resumeText = '';
        }

        if ($resumeText === '') {
            $resumeText = implode("\n", array_filter([
                $application->cover_letter,
                $application->motivation,
                $application->current_job_title,
                $application->years_of_experience,
            ]));
        }

        $analysis = $this->aiService->analyzeResume($resumeText ?: 'No resume text available.', $description ?: $job->title);

        $score = (int) ($analysis['match_score'] ?? 0);
        $application->update([
            'ai_score' => $score,
            'status' => in_array($application->status, ['pending', 'applied', ''], true)
                ? 'screening'
                : $application->status,
        ]);

        ResumeAnalysis::updateOrCreate(
            ['application_id' => $application->id],
            [
                'extracted_data' => $analysis['extracted_data'] ?? [],
                'match_score' => $score,
                'ai_feedback' => $analysis['ai_feedback'] ?? ($analysis['strengths'] ?? null),
                'strengths' => is_string($analysis['strengths'] ?? null) ? $analysis['strengths'] : null,
                'gaps' => is_string($analysis['gaps'] ?? null) ? $analysis['gaps'] : null,
            ]
        );

        $this->applyAutomationRules($application->fresh(['jobPosting']), $analysis);
    }

    protected function applyAutomationRules(Application $application, array $analysis): void
    {
        $score = (int) ($analysis['match_score'] ?? $application->ai_score ?? 0);
        $recommendation = strtolower((string) ($analysis['recommendation'] ?? ''));

        if (AiConfig::autoReject()
            && ($score < AiConfig::rejectScoreThreshold() || $recommendation === 'reject')
            && ! in_array($application->status, ['hired', 'offer', 'interview'], true)
        ) {
            $application->update(['status' => 'rejected']);
            if (AiConfig::autoRejectionEmail()) {
                $this->sendRejectionEmail($application->fresh(['jobPosting']));
            }

            return;
        }

        // High scores stay in screening for recruiter review (flag via ai_score / recommendation).
        if ($score >= AiConfig::interviewScoreThreshold()
            && in_array($recommendation, ['interview', 'hire'], true)
            && $application->status === 'screening'
        ) {
            // Optional: leave as screening; notes could be extended later.
        }
    }

    public function sendRejectionEmail(Application $application, bool $useAiDraft = true): void
    {
        try {
            $body = null;
            if ($useAiDraft && AiConfig::hasApiKey()) {
                $body = $this->aiService->draftCandidateEmail('rejection', [
                    'candidate_name' => $application->candidate_name,
                    'job_title' => $application->jobPosting->title ?? 'the role',
                    'company_name' => Setting::get('company_name', config('app.name')),
                ]);
            }

            $application->notify(new ApplicationStatusChanged($application, $body));
        } catch (\Throwable $e) {
            Log::error('Rejection email failed: ' . $e->getMessage());
        }
    }

    public function sendInterviewInvitation(Application $application, Interview $interview, bool $useAiDraft = true): void
    {
        if (! AiConfig::autoInterviewEmail()) {
            return;
        }

        try {
            $application->loadMissing('jobPosting');
            $interview->loadMissing('interviewer');

            $body = null;
            if ($useAiDraft && AiConfig::hasApiKey()) {
                $body = $this->aiService->draftCandidateEmail('interview', [
                    'candidate_name' => $application->candidate_name,
                    'job_title' => $application->jobPosting->title ?? 'the role',
                    'company_name' => Setting::get('company_name', config('app.name')),
                    'scheduled_at' => optional($interview->scheduled_at)->format('l, F j, Y g:i A'),
                    'location_or_link' => $interview->location_or_link,
                    'type' => $interview->type,
                ]);
            }

            $application->notify(new InterviewInvitation($application, $interview, $body));
        } catch (\Throwable $e) {
            Log::error('Interview invitation email failed: ' . $e->getMessage());
        }
    }
}
