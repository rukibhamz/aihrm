<?php

namespace App\Services\Ai\Providers;

use App\Contracts\AiProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class GeminiProvider implements AiProviderInterface
{
    public function __construct(
        protected string $apiKey,
        protected string $model,
        protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models'
    ) {}

    public function name(): string
    {
        return 'gemini';
    }

    public function model(): string
    {
        return $this->model;
    }

    public function complete(string $prompt, array $options = []): string
    {
        $base = rtrim($this->baseUrl, '/');
        $url = "{$base}/{$this->model}:generateContent?key={$this->apiKey}";

        $response = Http::withoutVerifying()
            ->timeout(90)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($url, [
                'contents' => [
                    ['parts' => [['text' => $prompt]]],
                ],
                'generationConfig' => [
                    'temperature' => $options['temperature'] ?? 0.7,
                    'maxOutputTokens' => $options['max_tokens'] ?? 2048,
                ],
            ]);

        if ($response->failed()) {
            Log::error('Gemini API error', ['status' => $response->status(), 'body' => $response->body()]);
            throw new RuntimeException('Gemini API request failed: ' . $response->body());
        }

        $data = $response->json();

        return $data['candidates'][0]['content']['parts'][0]['text']
            ?? throw new RuntimeException('Empty response from Gemini.');
    }
}
