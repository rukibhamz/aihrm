<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default provider (overridden by System Settings)
    |--------------------------------------------------------------------------
    */
    'default_provider' => env('AI_PROVIDER', 'gemini'),

    'providers' => [
        'gemini' => [
            'label' => 'Google Gemini',
            'api_key' => env('GEMINI_API_KEY'),
            'model' => env('GEMINI_MODEL', 'gemini-2.0-flash'),
            'base_url' => 'https://generativelanguage.googleapis.com/v1beta/models/',
        ],
        'openai' => [
            'label' => 'OpenAI',
            'api_key' => env('OPENAI_API_KEY'),
            'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
            'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),
        ],
        'anthropic' => [
            'label' => 'Anthropic Claude',
            'api_key' => env('ANTHROPIC_API_KEY'),
            'model' => env('ANTHROPIC_MODEL', 'claude-3-5-sonnet-20241022'),
            'base_url' => env('ANTHROPIC_BASE_URL', 'https://api.anthropic.com'),
            'max_tokens' => (int) env('ANTHROPIC_MAX_TOKENS', 4096),
        ],
        'openai_compatible' => [
            'label' => 'Custom (OpenAI-compatible)',
            'api_key' => env('AI_CUSTOM_API_KEY'),
            'model' => env('AI_CUSTOM_MODEL', 'gpt-4o-mini'),
            'base_url' => env('AI_CUSTOM_BASE_URL', 'https://api.openai.com/v1'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | ATS automation defaults (overridden by settings)
    |--------------------------------------------------------------------------
    */
    'ats' => [
        'auto_screen' => true,
        'auto_reject' => false,
        'auto_rejection_email' => true,
        'auto_interview_email' => true,
        'interview_score_threshold' => 70,
        'reject_score_threshold' => 40,
    ],

];
