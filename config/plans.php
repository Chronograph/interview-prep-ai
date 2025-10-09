<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Subscription Plans
    |--------------------------------------------------------------------------
    |
    | Define your subscription plans here. These should match the plans
    | you've created in your Stripe Dashboard.
    |
    */

    'plans' => [
        'free' => [
            'name' => 'Free',
            'description' => 'Perfect for getting started with interview preparation',
            'price' => 0,
            'price_id' => env('STRIPE_PRICE_FREE', ''),
            'features' => [
                '5 AI mock interviews per month',
                'Basic analytics',
                'Resume builder',
                'Standard cheat sheets',
                'Email support',
            ],
            'limits' => [
                'ai_interviews' => 5,
                'resumes' => 2,
                'cheat_sheets' => 10,
                'team_members' => 0,
            ],
        ],
        'basic' => [
            'name' => 'Basic',
            'description' => 'Great for active job seekers',
            'price' => 19,
            'price_id' => env('STRIPE_PRICE_BASIC', ''),
            'stripe_product' => 'basic',
            'features' => [
                '50 AI mock interviews per month',
                'Advanced analytics & insights',
                'Unlimited resumes',
                'Custom cheat sheets',
                'Priority email support',
                'Job application tracking',
                'Company research briefs',
            ],
            'limits' => [
                'ai_interviews' => 50,
                'resumes' => -1, // unlimited
                'cheat_sheets' => -1, // unlimited
                'team_members' => 0,
            ],
            'popular' => true,
        ],
        'pro' => [
            'name' => 'Pro',
            'description' => 'For serious interview preparation',
            'price' => 49,
            'price_id' => env('STRIPE_PRICE_PRO', ''),
            'stripe_product' => 'pro',
            'features' => [
                'Unlimited AI mock interviews',
                'Advanced analytics & insights',
                'Unlimited resumes with AI optimization',
                'Custom AI personas',
                'Priority support (24/7)',
                'Advanced job tracking',
                'Company research briefs',
                'Interview recording & playback',
                'Performance metrics',
                'Career coaching resources',
            ],
            'limits' => [
                'ai_interviews' => -1, // unlimited
                'resumes' => -1, // unlimited
                'cheat_sheets' => -1, // unlimited
                'team_members' => 5,
                'ai_personas' => -1, // unlimited
            ],
        ],
        'enterprise' => [
            'name' => 'Enterprise',
            'description' => 'For teams and organizations',
            'price' => 199,
            'price_id' => env('STRIPE_PRICE_ENTERPRISE', ''),
            'stripe_product' => 'enterprise',
            'features' => [
                'Everything in Pro',
                'Unlimited team members',
                'Custom branding',
                'Dedicated account manager',
                'Custom integrations',
                'Advanced team analytics',
                'SSO & SAML support',
                'Service Level Agreement (SLA)',
                'Training & onboarding',
            ],
            'limits' => [
                'ai_interviews' => -1, // unlimited
                'resumes' => -1, // unlimited
                'cheat_sheets' => -1, // unlimited
                'team_members' => -1, // unlimited
                'ai_personas' => -1, // unlimited
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Feature Flags
    |--------------------------------------------------------------------------
    |
    | Define which features require a subscription
    |
    */

    'features' => [
        'ai_interviews' => ['free', 'basic', 'pro', 'enterprise'],
        'resumes' => ['free', 'basic', 'pro', 'enterprise'],
        'cheat_sheets' => ['free', 'basic', 'pro', 'enterprise'],
        'teams' => ['pro', 'enterprise'],
        'ai_personas' => ['pro', 'enterprise'],
        'advanced_analytics' => ['basic', 'pro', 'enterprise'],
        'priority_support' => ['basic', 'pro', 'enterprise'],
    ],
];
