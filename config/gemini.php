<?php

declare(strict_types=1);

$apiKeys = array_values(array_filter(array_map(
    'trim',
    explode(',', (string) env('GEMINI_API_KEYS', ''))
)));
$models = array_values(array_filter(array_map(
    'trim',
    explode(',', (string) env('GEMINI_MODELS', ''))
)));

return [

    /*
    |--------------------------------------------------------------------------
    | Gemini API keys
    |--------------------------------------------------------------------------
    |
    | Keys are tried in the order they are listed in GEMINI_API_KEYS. For
    | every key all models below are tried in order. A key/model pair that
    | hits a quota error is skipped until its cooldown expires.
    |
    */

    'api_keys' => $apiKeys,

    // The google-gemini-php/laravel package still registers a default
    // client, so keep a valid single key for it.
    'api_key' => $apiKeys[0] ?? env('GEMINI_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Model fallback order
    |--------------------------------------------------------------------------
    |
    | Prioritise models with the highest free-tier RPD. As of the current
    | free tier:
    |   gemini-3.5-flash-lite  500 RPD
    |   gemini-3.1-flash-lite  500 RPD
    |   gemini-2.5-flash        20 RPD
    |   gemini-2.5-flash-lite   20 RPD
    |   gemma-4-31b-it       14.4K RPD
    |   gemma-4-26b-a4b-it   14.4K RPD
    |
    | Gemma models are reasoning models: they ignore JSON output and answer
    | slowly, so they are placed last and handled by a separate prompt and
    | parser in GeminiClientPool.
    |
    | The list can be overridden with GEMINI_MODELS (comma separated).
    |
    */

    'models' => $models ?: [
        'gemini-3.5-flash-lite',
        'gemini-3.1-flash-lite',
        'gemini-3.1-flash-lite-preview',
        'gemini-flash-lite-latest',
        'gemini-3.8-flash',
        'gemini-3.6-flash',
        'gemini-2.5-flash',
        'gemini-2.5-flash-lite',
        'gemma-4-31b-it',
        'gemma-4-26b-a4b-it',
    ],

    /*
    |--------------------------------------------------------------------------
    | Gemini Base URL
    |--------------------------------------------------------------------------
    */

    'base_url' => env('GEMINI_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta'),

    /*
    |--------------------------------------------------------------------------
    | Request settings
    |--------------------------------------------------------------------------
    */

    'request_timeout' => (int) env('GEMINI_REQUEST_TIMEOUT', 20),
    'max_attempts' => (int) env('GEMINI_MAX_ATTEMPTS', 12),
    'max_total_seconds' => (int) env('GEMINI_MAX_TOTAL_SECONDS', 20),

    // Gemma fallback settings. Gemma 4 is a reasoning model and can take
    // 30–60 seconds, so it has a separate timeout and budget.
    'gemma_timeout' => (int) env('GEMINI_GEMMA_TIMEOUT', 60),
    'gemma_max_output_tokens' => (int) env('GEMINI_GEMMA_MAX_OUTPUT_TOKENS', 768),
    'gemma_max_attempts' => (int) env('GEMINI_GEMMA_MAX_ATTEMPTS', 24),
    'gemma_max_total_seconds' => (int) env('GEMINI_GEMMA_MAX_TOTAL_SECONDS', 90),
    'gemma_retry_attempts' => (int) env('GEMINI_GEMMA_RETRY_ATTEMPTS', 1),
    'gemma_retry_delay_ms' => (int) env('GEMINI_GEMMA_RETRY_DELAY_MS', 1000),

    // If every provider fails, store the ad instead of dropping it.
    'fail_open' => filter_var(env('GEMINI_FAIL_OPEN', true), FILTER_VALIDATE_BOOL),

    // Reuse classification results for reposted messages.
    'cache_ttl_days' => (int) env('GEMINI_CACHE_TTL_DAYS', 30),
];
