<?php

return [
    'free' => [
        'name' => 'Basic (Free)',
        'price' => 0,
        'duration_days' => null, // permanent
        'features' => [
            'max_drafts' => 3,
            'max_publications' => 5,
            'basic_statistics' => true,
            'collaboration' => false,
            'monetization' => false,
            'full_statistics' => false,
            'download_statistics' => false,
            'priority_support' => false,
            'consultation' => false,
        ],
        'description' => 'Paket gratis untuk memulai'
    ],
    'pro' => [
        'name' => 'Pro',
        'price' => 1000,
        'duration_days' => 30,
        'features' => [
            'max_drafts' => 15,
            'max_publications' => 30,
            'basic_statistics' => true,
            'collaboration' => true,
            'monetization' => true,
            'full_statistics' => true,
            'download_statistics' => false,
            'priority_support' => false,
            'consultation' => false,
        ],
        'description' => 'Untuk penulis aktif dan kreator'
    ],
    'premium' => [
        'name' => 'Premium',
        'price' => 2000,
        'duration_days' => 30,
        'features' => [
            'max_drafts' => null, // unlimited
            'max_publications' => null, // unlimited
            'basic_statistics' => true,
            'collaboration' => true,
            'monetization' => true,
            'full_statistics' => true,
            'download_statistics' => true,
            'priority_support' => true,
            'consultation' => true,
        ],
        'description' => 'Untuk kreator profesional dan bisnis'
    ]
];