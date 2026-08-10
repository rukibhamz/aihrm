<?php

namespace App\Services\Ai\Providers;

use App\Contracts\AiProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class AnthropicProvider implements AiProviderInterface
{
    public function __construct(
        protected string $apiKey,
        protected string $model,
        protected string $baseUrl = 'https://api.anthropic.com',
        protected int $maxTokens = 4096
    ) {}

    public function name(): string
    {
        return 'anthropic';
    }

    public function model(): string
    {
        return $this->model;
    }

    public function complete(string $prompt, array $options = []): string
    {
        $url = rtrim($this->baseUrl, '/') . '/v1/messages';

        $response = Http::timeout(90)
            ->withHeaders([
                'x-api-key' => $this->apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])
            ->post($url, [
                'model' => $this->model,
                'max_tokens' => $options['max_tokens'] ?? $this->maxTokens,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

        if ($response->failed()) {
            Log::error('Anthropic API error', ['status' => $response->status(), 'body' => $response->body()]);
            throw new RuntimeException('Anthropic API request failed: ' . $response->body());
        }

        $data = $response->json();
        $parts = $data['content'] ?? [];
        $text = '';
        foreach ($parts as $part) {
            if (($part['type'] ?? '') === 'text') {
                $text .= $part['text'] ?? '';
            }
        }

        if ($text === '') {
            throw new RuntimeException('Empty response from Anthropic.');
        }

        return $text;
    }
}
