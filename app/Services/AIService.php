<?php

namespace App\Services;

use Anthropic\Anthropic;
use App\Models\AIRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected $client;
    protected $model;
    protected $maxTokens;

    public function __construct()
    {
        $this->client = Anthropic::factory()
            ->withApiKey(config('services.anthropic.api_key'))
            ->make();
        
        $this->model = config('services.anthropic.model', 'claude-3-5-sonnet-20241022');
        $this->maxTokens = config('services.anthropic.max_tokens', 4096);
    }

    /**
     * Analyze resume and extract structured data
     */
    public function analyzeResume(string $resumeText, string $jobDescription): array
    {
        $prompt = $this->buildResumePrompt($resumeText, $jobDescription);
        
        $response = $this->sendRequest($prompt, 'resume_screening');
        
        return $this->parseResumeResponse($response);
    }

    /**
     * Generate chatbot response
     */
    public function chatbotResponse(string $userMessage, array $context = []): string
    {
        $prompt = $this->buildChatPrompt($userMessage, $context);
        
        $response = $this->sendRequest($prompt, 'chatbot');
        
        return $response['content'][0]['text'] ?? 'I apologize, but I could not generate a response.';
    }

    /**
     * Analyze performance reviews for insights
     */
    public function analyzePerformance(array $reviewData): array
    {
        $prompt = $this->buildPerformancePrompt($reviewData);
        
        $response = $this->sendRequest($prompt, 'performance_analysis');
        
        return $this->parsePerformanceResponse($response);
    }

    /**
     * Check compliance issues
     */
    public function checkCompliance(array $data): array
    {
        $prompt = $this->buildCompliancePrompt($data);
        
        $response = $this->sendRequest($prompt, 'compliance_check');
        
        return $this->parseComplianceResponse($response);
    }

    /**
     * Send request to Claude API
     */
    protected function sendRequest(string $prompt, string $feature): array
    {
        try {
            $response = $this->client->messages()->create([
                'model' => $this->model,
                'max_tokens' => $this->maxTokens,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
            ]);

            // Track usage
            $this->trackUsage($feature, $response->usage->inputTokens + $response->usage->outputTokens);

            return $response->toArray();
        } catch (\Exception $e) {
            Log::error('AI Service Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Build resume analysis prompt
     */
    protected function buildResumePrompt(string $resumeText, string $jobDescription): string
    {
        return <<<PROMPT
You are an expert HR recruiter. Analyze the following resume against the job description and provide a structured assessment.

JOB DESCRIPTION:
{$jobDescription}

RESUME:
{$resumeText}

Please provide your analysis in the following JSON format:
{
    "match_score": <0-100>,
    "extracted_data": {
        "skills": ["skill1", "skill2"],
        "experience_years": <number>,
        "education": ["degree1", "degree2"],
        "previous_roles": ["role1", "role2"]
    },
    "strengths": "Brief summary of candidate strengths",
    "gaps": "Brief summary of gaps or concerns",
    "recommendation": "hire|interview|reject"
}

Be objective and focus on qualifications, not demographics.
PROMPT;
    }

    /**
     * Build chatbot prompt
     */
    protected function buildChatPrompt(string $userMessage, array $context): string
    {
        $contextStr = !empty($context) ? json_encode($context, JSON_PRETTY_PRINT) : 'No additional context';
        
        return <<<PROMPT
You are an AI HR assistant for a company. Answer the employee's question professionally and helpfully.

CONTEXT:
{$contextStr}

EMPLOYEE QUESTION:
{$userMessage}

Provide a clear, concise answer. If you don't know something, say so. Keep responses under 200 words.
PROMPT;
    }

    /**
     * Build performance analysis prompt
     */
    protected function buildPerformancePrompt(array $reviewData): string
    {
        $dataStr = json_encode($reviewData, JSON_PRETTY_PRINT);
        
        return <<<PROMPT
Analyze the following performance review data for patterns, bias, and insights.

DATA:
{$dataStr}

Provide analysis in JSON format:
{
    "high_performers": ["employee_id1", "employee_id2"],
    "bias_detected": true/false,
    "bias_details": "Description if any",
    "attrition_risk": ["employee_id1"],
    "recommendations": ["recommendation1", "recommendation2"]
}
PROMPT;
    }

    /**
     * Build compliance check prompt
     */
    protected function buildCompliancePrompt(array $data): string
    {
        $dataStr = json_encode($data, JSON_PRETTY_PRINT);
        
        return <<<PROMPT
Review the following HR data for labor law compliance issues (Nigerian labor laws).

DATA:
{$dataStr}

Provide findings in JSON format:
{
    "compliant": true/false,
    "issues": ["issue1", "issue2"],
    "severity": "low|medium|high",
    "recommendations": ["fix1", "fix2"]
}
PROMPT;
    }

    /**
     * Parse resume analysis response
     */
    protected function parseResumeResponse(array $response): array
    {
        $text = $response['content'][0]['text'] ?? '{}';
        
        // Extract JSON from response
        if (preg_match('/\{[\s\S]*\}/', $text, $matches)) {
            return json_decode($matches[0], true) ?? [];
        }
        
        return [];
    }

    /**
     * Parse performance analysis response
     */
    protected function parsePerformanceResponse(array $response): array
    {
        return $this->parseResumeResponse($response);
    }

    /**
     * Parse compliance check response
     */
    protected function parseComplianceResponse(array $response): array
    {
        return $this->parseResumeResponse($response);
    }

    /**
     * Track AI usage for billing/monitoring
     */
    protected function trackUsage(string $feature, int $tokens): void
    {
        // Approximate cost: $3 per million tokens for Claude 3.5 Sonnet
        $cost = ($tokens / 1000000) * 3;

        AIRequest::create([
            'user_id' => Auth::id() ?? 1,
            'feature' => $feature,
            'tokens_used' => $tokens,
            'cost' => $cost,
            'request_summary' => "Feature: {$feature}",
        ]);
    }
}
