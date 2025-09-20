<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Interview Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for the AI interview system.
    |
    */

    'max_questions' => env('INTERVIEW_MAX_QUESTIONS', 10),
    
    'default_duration' => env('INTERVIEW_DEFAULT_DURATION', 30), // minutes
    
    'question_types' => [
        'behavioral' => [
            'name' => 'Behavioral',
            'description' => 'Questions about past experiences and situations',
            'weight' => 0.3
        ],
        'technical' => [
            'name' => 'Technical',
            'description' => 'Technical questions related to job requirements and skills',
            'weight' => 0.4
        ],
        'situational' => [
            'name' => 'Situational',
            'description' => 'Hypothetical scenarios and problem-solving questions',
            'weight' => 0.2
        ],
        'company_culture' => [
            'name' => 'Company Culture',
            'description' => 'Questions about company fit and cultural alignment',
            'weight' => 0.1
        ]
    ],
    
    'scoring' => [
        'excellent' => ['min' => 8.5, 'max' => 10],
        'good' => ['min' => 7.0, 'max' => 8.4],
        'average' => ['min' => 5.5, 'max' => 6.9],
        'below_average' => ['min' => 3.0, 'max' => 5.4],
        'poor' => ['min' => 0, 'max' => 2.9]
    ],
    
    'ai_models' => [
        'question_generation' => env('OPENAI_QUESTION_MODEL', 'gpt-4'),
        'response_analysis' => env('OPENAI_ANALYSIS_MODEL', 'gpt-4'),
        'feedback_generation' => env('OPENAI_FEEDBACK_MODEL', 'gpt-4')
    ],
    
    'response_timeouts' => [
        'question_generation' => 30, // seconds
        'response_analysis' => 20,
        'feedback_generation' => 45
    ]
];