<?php

return [
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
        'price' => 150000,
        'duration_days' => 90,
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