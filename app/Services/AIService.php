<?php

namespace App\Services;

use App\Models\AIRequest;
use App\Services\Ai\AiConfig;
use App\Services\Ai\AiProviderFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AIService
{
    /**
     * Analyze resume and extract structured data.
     */
    public function analyzeResume(string $resumeText, string $jobDescription): array
    {
        if (! AiConfig::hasApiKey()) {
            return $this->localKeywordAnalysis($resumeText, $jobDescription);
        }

        try {
            $prompt = $this->buildResumePrompt($resumeText, $jobDescription);
            $text = $this->complete($prompt, 'resume_screening', ['temperature' => 0.3]);
            $parsed = $this->parseJsonFromText($text);
            if (! empty($parsed)) {
                return $this->normalizeResumeAnalysis($parsed);
            }
        } catch (\Throwable $e) {
            Log::warning('AI resume analysis failed, using local fallback: ' . $e->getMessage());
        }

        return $this->localKeywordAnalysis($resumeText, $jobDescription);
    }

    /**
     * Generate chatbot response.
     */
    public function chatbotResponse(string $userMessage, array $context = []): string
    {
        $prompt = $this->buildChatPrompt($userMessage, $context);

        return $this->complete($prompt, 'chatbot', ['temperature' => 0.6, 'max_tokens' => 1024]);
    }

    /**
     * Analyze performance reviews for insights.
     */
    public function analyzePerformance(array $reviewData): array
    {
        $prompt = $this->buildPerformancePrompt($reviewData);
        $text = $this->complete($prompt, 'performance_analysis', ['temperature' => 0.4]);

        return $this->parseJsonFromText($text);
    }

    /**
     * Check compliance issues.
     */
    public function checkCompliance(array $data): array
    {
        $prompt = $this->buildCompliancePrompt($data);
        $text = $this->complete($prompt, 'compliance_check', ['temperature' => 0.3]);

        return $this->parseJsonFromText($text);
    }

    /**
     * Draft candidate-facing email (interview invite / rejection polish).
     */
    public function draftCandidateEmail(string $type, array $payload): string
    {
        $prompt = $this->buildEmailDraftPrompt($type, $payload);

        try {
            return trim($this->complete($prompt, 'candidate_email', ['temperature' => 0.5, 'max_tokens' => 800]));
        } catch (\Throwable $e) {
            Log::warning('AI email draft failed: ' . $e->getMessage());

            return $this->fallbackEmailBody($type, $payload);
        }
    }

    protected function complete(string $prompt, string $feature, array $options = []): string
    {
        $provider = AiProviderFactory::make();
        $text = $provider->complete($prompt, $options);

        $this->trackUsage(
            $feature,
            (int) ((strlen($prompt) + strlen($text)) / 4),
            $provider->name(),
            $provider->model()
        );

        return $text;
    }

    protected function localKeywordAnalysis(string $resumeText, string $jobDescription): array
    {
        $words = str_word_count(strip_tags($jobDescription), 1);
        $keywords = array_filter($words, fn ($w) => strlen($w) > 4 && ctype_upper($w[0]));
        $keywords = array_unique(array_map('strtolower', $keywords));

        $resumeLower = strtolower($resumeText);
        $matches = [];

        foreach ($keywords as $keyword) {
            if (str_contains($resumeLower, $keyword)) {
                $matches[] = ucfirst($keyword);
            }
        }

        $totalKeywords = count($keywords);
        $matchCount = count($matches);
        $score = $totalKeywords > 0 ? min(100, round(($matchCount / $totalKeywords) * 100)) : 0;
        $score = (int) min(100, $score * 1.5);

        return [
            'match_score' => $score,
            'extracted_data' => [
                'skills' => $matches,
                'keywords' => $matches,
                'experience_years' => null,
            ],
            'strengths' => 'Matched keywords: ' . implode(', ', array_slice($matches, 0, 5)),
            'gaps' => 'Basic analysis only — configure an AI API key for full screening.',
            'recommendation' => $score > 70 ? 'interview' : ($score > 40 ? 'review' : 'reject'),
        ];
    }

    protected function normalizeResumeAnalysis(array $parsed): array
    {
        $score = (int) ($parsed['match_score'] ?? 0);
        $extracted = $parsed['extracted_data'] ?? [];
        if (! isset($extracted['keywords']) && isset($extracted['skills'])) {
            $extracted['keywords'] = $extracted['skills'];
        }

        return [
            'match_score' => max(0, min(100, $score)),
            'extracted_data' => $extracted,
            'strengths' => $parsed['strengths'] ?? '',
            'gaps' => $parsed['gaps'] ?? '',
            'recommendation' => $parsed['recommendation'] ?? 'review',
            'ai_feedback' => $parsed['ai_feedback'] ?? ($parsed['summary'] ?? null),
        ];
    }

    protected function buildResumePrompt(string $resumeText, string $jobDescription): string
    {
        $resumeText = mb_substr($resumeText, 0, 12000);
        $jobDescription = mb_substr($jobDescription, 0, 6000);

        return <<<PROMPT
You are an expert HR recruiter. Analyze the following resume against the job description and provide a structured assessment.

JOB DESCRIPTION:
{$jobDescription}

RESUME:
{$resumeText}

Please provide your analysis in the following JSON format ONLY (no markdown code blocks):
{
    "match_score": <0-100>,
    "extracted_data": {
        "skills": ["skill1", "skill2"],
        "keywords": ["keyword1"],
        "certifications": [],
        "experience_years": <number or null>,
        "education": ["degree1"],
        "previous_roles": ["role1"]
    },
    "strengths": "Brief summary of candidate strengths",
    "gaps": "Brief summary of gaps or concerns",
    "recommendation": "hire|interview|review|reject",
    "ai_feedback": "One paragraph for recruiters"
}

Be objective and focus on qualifications, not demographics.
PROMPT;
    }

    protected function buildChatPrompt(string $userMessage, array $context): string
    {
        $contextStr = ! empty($context) ? json_encode($context, JSON_PRETTY_PRINT) : 'No additional context';

        return <<<PROMPT
You are an AI HR assistant for a company. Answer the employee's question professionally and helpfully.

CONTEXT:
{$contextStr}

EMPLOYEE QUESTION:
{$userMessage}

Provide a clear, concise answer. If you don't know something, say so. Keep responses under 200 words.
PROMPT;
    }

    protected function buildPerformancePrompt(array $reviewData): string
    {
        $dataStr = json_encode($reviewData, JSON_PRETTY_PRINT);

        return <<<PROMPT
Analyze the following performance review data for patterns, bias, and insights.

DATA:
{$dataStr}

Provide analysis in JSON format ONLY (no markdown code blocks):
{
    "high_performers": ["employee_id1"],
    "bias_detected": true,
    "bias_details": "Description if any",
    "attrition_risk": ["employee_id1"],
    "recommendations": ["recommendation1"]
}
PROMPT;
    }

    protected function buildCompliancePrompt(array $data): string
    {
        $dataStr = json_encode($data, JSON_PRETTY_PRINT);

        return <<<PROMPT
Review the following HR data for labor law compliance issues (Nigerian labor laws where applicable).

DATA:
{$dataStr}

Provide findings in JSON format ONLY (no markdown code blocks):
{
    "compliant": true,
    "issues": ["issue1"],
    "severity": "low|medium|high",
    "recommendations": ["fix1"]
}
PROMPT;
    }

    protected function buildEmailDraftPrompt(string $type, array $payload): string
    {
        $json = json_encode($payload, JSON_PRETTY_PRINT);

        if ($type === 'interview') {
            return <<<PROMPT
Write a concise, professional interview invitation email body for a job candidate.
Use plain text paragraphs only (no subject line, no markdown).
Context JSON:
{$json}
PROMPT;
        }

        return <<<PROMPT
Write a concise, professional, respectful job application rejection email body for a candidate.
Be polite, avoid illegal or discriminatory reasons, keep under 150 words.
Use plain text paragraphs only (no subject line, no markdown).
Context JSON:
{$json}
PROMPT;
    }

    protected function fallbackEmailBody(string $type, array $payload): string
    {
        $name = $payload['candidate_name'] ?? 'Candidate';
        $title = $payload['job_title'] ?? 'the role';
        $company = $payload['company_name'] ?? config('app.name');

        if ($type === 'interview') {
            $when = $payload['scheduled_at'] ?? 'the scheduled time';
            $where = $payload['location_or_link'] ?? 'details to follow';

            return "Dear {$name},\n\nThank you for applying for {$title} at {$company}. We would like to invite you to an interview on {$when}. Location/link: {$where}.\n\nPlease reply to confirm your availability.\n\nBest regards,\n{$company} Recruiting";
        }

        return "Dear {$name},\n\nThank you for your interest in {$title} at {$company}. After careful review, we will not be moving forward with your application at this time.\n\nWe appreciate the time you invested and wish you success in your job search.\n\nBest regards,\n{$company} Recruiting";
    }

    protected function parseJsonFromText(string $text): array
    {
        $text = preg_replace('/^```json\s*|\s*```$/m', '', trim($text));
        if (preg_match('/\{[\s\S]*\}/', $text, $matches)) {
            $decoded = json_decode($matches[0], true);

            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }

    protected function trackUsage(string $feature, int $tokens, string $provider = '', string $model = ''): void
    {
        try {
            $cost = ($tokens / 1_000_000) * 0.5;
            AIRequest::create([
                'user_id' => Auth::id() ?? 1,
                'feature' => $feature,
                'tokens_used' => $tokens,
                'cost' => $cost,
                'request_summary' => trim("Feature: {$feature}; Provider: {$provider}; Model: {$model}"),
            ]);
        } catch (\Throwable $e) {
            Log::debug('AI usage tracking skipped: ' . $e->getMessage());
        }
    }
}
