<?php

namespace App\Services\Ai;

use App\Models\Setting;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class AiConfig
{
    public static function providers(): array
    {
        return config('ai.providers', []);
    }

    public static function provider(): string
    {
        $p = Setting::get('ai_provider', config('ai.default_provider', 'gemini'));
        return array_key_exists($p, self::providers()) ? $p : 'gemini';
    }

    public static function apiKey(): string
    {
        $encrypted = Setting::get('ai_api_key');
        if (filled($encrypted)) {
            try {
                return Crypt::decryptString($encrypted);
            } catch (\Throwable $e) {
                Log::warning('Failed to decrypt AI API key from settings.');
            }
        }

        $provider = self::provider();
        return (string) (config("ai.providers.{$provider}.api_key")
            ?: config('services.gemini.api_key')
            ?: '');
    }

    public static function model(): string
    {
        $fromSettings = Setting::get('ai_model');
        if (filled($fromSettings)) {
            return $fromSettings;
        }

        $provider = self::provider();
        return (string) config("ai.providers.{$provider}.model", 'gemini-2.0-flash');
    }

    public static function baseUrl(): string
    {
        $fromSettings = Setting::get('ai_base_url');
        if (filled($fromSettings)) {
            return rtrim($fromSettings, '/');
        }

        $provider = self::provider();
        return rtrim((string) config("ai.providers.{$provider}.base_url", ''), '/');
    }

    public static function hasApiKey(): bool
    {
        return filled(self::apiKey());
    }

    public static function setEncryptedApiKey(?string $plain): void
    {
        if ($plain === null || $plain === '') {
            return;
        }
        Setting::set('ai_api_key', Crypt::encryptString($plain), 'secret');
    }

    public static function autoScreen(): bool
    {
        return Setting::get('ai_auto_screen', config('ai.ats.auto_screen') ? 'yes' : 'no') === 'yes';
    }

    public static function autoReject(): bool
    {
        return Setting::get('ai_auto_reject', config('ai.ats.auto_reject') ? 'yes' : 'no') === 'yes';
    }

    public static function autoRejectionEmail(): bool
    {
        return Setting::get('ai_auto_rejection_email', config('ai.ats.auto_rejection_email') ? 'yes' : 'no') === 'yes';
    }

    public static function autoInterviewEmail(): bool
    {
        return Setting::get('ai_auto_interview_email', config('ai.ats.auto_interview_email') ? 'yes' : 'no') === 'yes';
    }

    public static function interviewScoreThreshold(): int
    {
        return (int) Setting::get('ai_interview_score_threshold', config('ai.ats.interview_score_threshold', 70));
    }

    public static function rejectScoreThreshold(): int
    {
        return (int) Setting::get('ai_reject_score_threshold', config('ai.ats.reject_score_threshold', 40));
    }
}
