<?php

namespace App\Services\Ai\Providers;

use App\Contracts\AiProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * Works with OpenAI and any OpenAI-compatible chat completions API
 * (Groq, DeepSeek, Azure OpenAI proxy, Ollama OpenAI shim, etc.).
 */
class OpenAiCompatibleProvider implements AiProviderInterface
{
    public function __construct(
        protected string $apiKey,
        protected string $model,
        protected string $baseUrl = 'https://api.openai.com/v1',
        protected string $driverName = 'openai'
    ) {}

    public function name(): string
    {
        return $this->driverName;
    }

    public function model(): string
    {
        return $this->model;
    }

    public function complete(string $prompt, array $options = []): string
    {
        $base = rtrim($this->baseUrl, '/');
        $url = str_ends_with($base, '/v1')
            ? "{$base}/chat/completions"
            : "{$base}/v1/chat/completions";

        // If custom base already includes full path ending in chat/completions, use as-is.
        if (str_contains($base, 'chat/completions')) {
            $url = $base;
        }

        $response = Http::timeout(90)
            ->withToken($this->apiKey)
            ->acceptJson()
            ->post($url, [
                'model' => $this->model,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => $options['temperature'] ?? 0.7,
                'max_tokens' => $options['max_tokens'] ?? 2048,
            ]);

        if ($response->failed()) {
            Log::error('OpenAI-compatible API error', [
                'driver' => $this->driverName,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new RuntimeException('AI API request failed: ' . $response->body());
        }

        $data = $response->json();
        $text = $data['choices'][0]['message']['content'] ?? null;

        if (! is_string($text) || $text === '') {
            throw new RuntimeException('Empty response from AI provider.');
        }

        return $text;
    }
}
