<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Provider
    |--------------------------------------------------------------------------
    |
    | This option controls the default AI provider that will be used by your
    | application. This provider will be used when no specific provider is
    | specified when using the Prism facade.
    |
    */

    'default' => env('PRISM_DEFAULT_PROVIDER', 'anthropic'),

    /*
    |--------------------------------------------------------------------------
    | Providers
    |--------------------------------------------------------------------------
    |
    | Here you may configure the AI providers for your application. You may
    | use multiple providers and switch between them as needed.
    |
    */

    'providers' => [
        'lmstudio' => [
            'driver' => 'openai',
            'url' => env('PRISM_LMSTUDIO_URL', 'http://localhost:1234/v1'),
            'api_key' => env('PRISM_LMSTUDIO_API_KEY', 'lm-studio'),
            'model' => env('PRISM_LMSTUDIO_MODEL', 'phi-3.1-mini-4k-instruct'),
        ],

        'openai' => [
            'driver' => 'openai',
            'url' => env('PRISM_OPENAI_URL', 'https://api.openai.com/v1'),
            'api_key' => env('PRISM_OPENAI_API_KEY'),
        ],

        'anthropic' => [
            'driver' => 'anthropic',
            'api_key' => env('PRISM_ANTHROPIC_API_KEY'),
            'version' => '2023-06-01',
        ],

        'ollama' => [
            'driver' => 'ollama',
            'url' => env('PRISM_OLLAMA_URL', 'http://localhost:11434'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Models
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default models for each provider. These will
    | be used when no specific model is provided in your Prism calls.
    |
    */

    'models' => [
        'lmstudio' => env('PRISM_LMSTUDIO_MODEL', 'local-model'),
        'openai' => env('PRISM_OPENAI_MODEL', 'gpt-4'),
        'anthropic' => env('PRISM_ANTHROPIC_MODEL', 'claude-3-haiku-20240307'),
        'ollama' => env('PRISM_OLLAMA_MODEL', 'llama3.2'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Request Options
    |--------------------------------------------------------------------------
    |
    | Here you may configure default request options that will be applied
    | to all Prism requests. These can be overridden on a per-request basis.
    |
    */

    'request_options' => [
        'timeout' => env('PRISM_REQUEST_TIMEOUT', 60), // Increased timeout for web APIs
        'max_tokens' => env('PRISM_MAX_TOKENS', 2000),
        'temperature' => env('PRISM_TEMPERATURE', 0.7),
        'connect_timeout' => env('PRISM_CONNECT_TIMEOUT', 10), // Connection timeout
    ],
];
