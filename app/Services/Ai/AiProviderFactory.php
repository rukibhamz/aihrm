<?php

namespace App\Services\Ai;

use App\Contracts\AiProviderInterface;
use App\Services\Ai\Providers\AnthropicProvider;
use App\Services\Ai\Providers\GeminiProvider;
use App\Services\Ai\Providers\OpenAiCompatibleProvider;
use RuntimeException;

class AiProviderFactory
{
    public static function make(?string $provider = null): AiProviderInterface
    {
        $provider = $provider ?: AiConfig::provider();
        $apiKey = AiConfig::apiKey();
        $model = AiConfig::model();
        $baseUrl = AiConfig::baseUrl();

        if (! filled($apiKey)) {
            throw new RuntimeException(
                'No AI API key configured. Add a key under System Settings → AI & ATS, or set the provider env var.'
            );
        }

        return match ($provider) {
            'gemini' => new GeminiProvider(
                $apiKey,
                $model,
                $baseUrl ?: 'https://generativelanguage.googleapis.com/v1beta/models'
            ),
            'openai' => new OpenAiCompatibleProvider(
                $apiKey,
                $model,
                $baseUrl ?: 'https://api.openai.com/v1',
                'openai'
            ),
            'anthropic' => new AnthropicProvider(
                $apiKey,
                $model,
                $baseUrl ?: 'https://api.anthropic.com',
                (int) config('ai.providers.anthropic.max_tokens', 4096)
            ),
            'openai_compatible' => new OpenAiCompatibleProvider(
                $apiKey,
                $model,
                $baseUrl ?: 'https://api.openai.com/v1',
                'openai_compatible'
            ),
            default => throw new RuntimeException("Unsupported AI provider: {$provider}"),
        };
    }
}
