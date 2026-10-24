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
    'primary_action' => ['label' => 'Join the waitlist', 'slug' => 'platform', 'fragment' => 'waitlist'],

    'navigation' => [
        ['label' => 'Product', 'slug' => 'platform'],
        ['label' => 'How it works', 'slug' => 'approach'],
        ['label' => 'Risk & calculators', 'slug' => 'ai-tools'],
        ['label' => 'Insights', 'route' => 'insights.index'],
        ['label' => 'About', 'slug' => 'about'],
    ],

    'footer' => [
        'InvestAI' => [
            ['label' => 'The product', 'slug' => 'platform'],
            ['label' => 'How it works', 'slug' => 'approach'],
            ['label' => 'Risk & calculators', 'slug' => 'ai-tools'],
            ['label' => 'About InvestAI', 'slug' => 'about'],
        ],
        'Company' => [
            ['label' => 'About InvestAI', 'slug' => 'about'],
            ['label' => 'Contact', 'slug' => 'contact'],
        ],
        'Resources' => [
            ['label' => 'Insights', 'route' => 'insights.index'],
            ['label' => 'FAQ', 'slug' => 'faq'],
            ['label' => 'Confidentiality', 'slug' => 'confidentiality'],
        ],
    ],

    'legal' => [
        ['label' => 'Privacy', 'slug' => 'privacy'],
        ['label' => 'Terms', 'slug' => 'terms'],
        ['label' => 'Financial disclaimer', 'slug' => 'financial-disclaimer'],
    ],
];
