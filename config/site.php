<?php

/*
|--------------------------------------------------------------------------
| Public website navigation
|--------------------------------------------------------------------------
|
| Each entry links to either a published CMS page ("slug") or a named route
| ("route"). Entries with "children" render as a dropdown on desktop and a
| nested list on mobile. The parent link remains a normal link.
|
*/

return [
    'primary_action' => ['label' => 'Book a consultation', 'slug' => 'consultation'],

    'navigation' => [
        [
            'label' => 'Services',
            'slug' => 'services',
            'children' => [
                ['label' => 'Business Strategy & Performance', 'slug' => 'business-strategy', 'icon' => 'briefcase', 'text' => 'Clear priorities, KPIs and business cases.'],
                ['label' => 'Data & Analytics', 'slug' => 'data-analytics', 'icon' => 'chart', 'text' => 'Trusted data, dashboards and forecasts.'],
                ['label' => 'AI Strategy & Automation', 'slug' => 'ai-advisory', 'icon' => 'sparkles', 'text' => 'Practical, governed use of AI.'],
                ['label' => 'Financial & Investment Analytics', 'slug' => 'financial-analytics', 'icon' => 'banknote', 'text' => 'FP&A, performance and risk insight.'],
            ],
        ],
        ['label' => 'Industries', 'slug' => 'industries'],
        ['label' => 'Approach', 'slug' => 'approach'],
        ['label' => 'Platform', 'slug' => 'platform'],
        [
            'label' => 'Resources',
            'slug' => 'ai-tools',
            'children' => [
                ['label' => 'AI-assisted tools', 'slug' => 'ai-tools', 'icon' => 'calculator', 'text' => 'Readiness check, estimator and risk calculator.'],
                ['label' => 'Insights', 'route' => 'insights.index', 'icon' => 'document', 'text' => 'Weekly, human-reviewed perspectives.'],
                ['label' => 'Research scenarios', 'slug' => 'case-studies', 'icon' => 'search', 'text' => 'Illustrative worked examples.'],
                ['label' => 'FAQ', 'slug' => 'faq', 'icon' => 'chat', 'text' => 'Answers to common questions.'],
            ],
        ],
        ['label' => 'About', 'slug' => 'about'],
    ],

    'footer' => [
        'Services' => [
            ['label' => 'All services', 'slug' => 'services'],
            ['label' => 'Business Strategy & Performance', 'slug' => 'business-strategy'],
            ['label' => 'Data & Analytics', 'slug' => 'data-analytics'],
            ['label' => 'AI Strategy & Automation', 'slug' => 'ai-advisory'],
            ['label' => 'Financial & Investment Analytics', 'slug' => 'financial-analytics'],
        ],
        'Company' => [
            ['label' => 'About InvestAI', 'slug' => 'about'],
            ['label' => 'Industries', 'slug' => 'industries'],
            ['label' => 'Our approach', 'slug' => 'approach'],
            ['label' => 'Working with us', 'slug' => 'engagement-models'],
            ['label' => 'Confidentiality', 'slug' => 'confidentiality'],
            ['label' => 'Contact', 'slug' => 'contact'],
        ],
        'Platform & resources' => [
            ['label' => 'InvestAI platform', 'slug' => 'platform'],
            ['label' => 'AI-assisted tools', 'slug' => 'ai-tools'],
            ['label' => 'Insights', 'route' => 'insights.index'],
            ['label' => 'Technology & responsible AI', 'slug' => 'technology'],
            ['label' => 'FAQ', 'slug' => 'faq'],
        ],
    ],

    'legal' => [
        ['label' => 'Privacy', 'slug' => 'privacy'],
        ['label' => 'Terms', 'slug' => 'terms'],
        ['label' => 'Financial disclaimer', 'slug' => 'financial-disclaimer'],
    ],
];
