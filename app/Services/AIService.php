<?php

namespace App\Services;

use App\Models\AIRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected $apiKey;
    protected $model;
    protected $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
        $this->model = config('services.gemini.model', 'gemini-2.0-flash');
    }

    /**
     * Analyze resume and extract structured data
     */
    public function analyzeResume(string $resumeText, string $jobDescription): array
    {
        // Fallback to basic keyword matching if no API key is set
        if (empty($this->apiKey)) {
            return $this->localKeywordAnalysis($resumeText, $jobDescription);
        }

        try {
            $prompt = $this->buildResumePrompt($resumeText, $jobDescription);
            $response = $this->sendRequest($prompt, 'resume_screening');
            return $this->parseResumeResponse($response);
        } catch (\Exception $e) {
            Log::warning('AI Service failed, falling back to local analysis: ' . $e->getMessage());
            return $this->localKeywordAnalysis($resumeText, $jobDescription);
        }
    }

    /**
     * Basic local keyword analysis fallback
     */
    protected function localKeywordAnalysis(string $resumeText, string $jobDescription): array
    {
        // Extract potential keywords from Job Description (simple capitalization/length heuristic)
        // In a real app, you'd use a predefined skills database
        $words = str_word_count(strip_tags($jobDescription), 1);
        $keywords = array_filter($words, fn($w) => strlen($w) > 4 && ctype_upper($w[0]));
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
        
        // Boost score slightly as simple keyword matching is strict
        $score = min(100, $score * 1.5); 

        return [
            'match_score' => (int) $score,
            'extracted_data' => [
                'skills' => $matches,
                'experience_years' => 'N/A', // Cannot determine reliably without AI
            ],
            'strengths' => "Matched keywords: " . implode(', ', array_slice($matches, 0, 5)),
            'gaps' => "Basic analysis only - review full resume manually.",
            'recommendation' => $score > 70 ? 'interview' : ($score > 40 ? 'review' : 'reject')
        ];
    }

    /**
     * Generate chatbot response
     */
    public function chatbotResponse(string $userMessage, array $context = []): string
    {
        $prompt = $this->buildChatPrompt($userMessage, $context);
        
        $response = $this->sendRequest($prompt, 'chatbot');
        
        return $this->extractTextFromResponse($response);
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
     * Send request to Gemini API
     */
    protected function sendRequest(string $prompt, string $feature): array
    {
        try {
            $url = "{$this->baseUrl}{$this->model}:generateContent?key={$this->apiKey}";
            
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])->post($url, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'maxOutputTokens' => 2048,
                    ]
                ]);

            if ($response->failed()) {
                Log::error('Gemini API Error Status: ' . $response->status());
                Log::error('Gemini API Error Body: ' . $response->body());
                throw new \Exception('Failed to communicate with AI service: ' . $response->body());
            }

            $data = $response->json();
            
            // Track usage (Gemini doesn't always return token usage in simple responses, estimating)
            // 1 token ~= 4 chars
            $inputTokens = strlen($prompt) / 4;
            $outputTokens = strlen($this->extractTextFromResponse($data)) / 4;
            
            $this->trackUsage($feature, (int)($inputTokens + $outputTokens));

            return $data;
        } catch (\Exception $e) {
            Log::error('AI Service Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Extract text from Gemini response
     */
    protected function extractTextFromResponse(array $response): string
    {
        return $response['candidates'][0]['content']['parts'][0]['text'] ?? 'I apologize, but I could not generate a response.';
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

Please provide your analysis in the following JSON format ONLY (no markdown code blocks):
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

Provide analysis in JSON format ONLY (no markdown code blocks):
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

Provide findings in JSON format ONLY (no markdown code blocks):
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
        $text = $this->extractTextFromResponse($response);
        
        // Clean up markdown code blocks if present
        $text = preg_replace('/^```json\s*|\s*```$/', '', $text);
        
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
        // Gemini Pro is currently free (within limits) or low cost
        // Estimating cost for tracking purposes (approx $0.50 per million input chars)
        $cost = ($tokens / 1000000) * 0.5;

        AIRequest::create([
            'user_id' => Auth::id() ?? 1,
            'feature' => $feature,
            'tokens_used' => $tokens,
            'cost' => $cost,
            'request_summary' => "Feature: {$feature}",
        ]);
    }
}
