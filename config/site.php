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
    'primary_action' => ['label' => 'Consult', 'slug' => 'consultation'],

    'navigation' => [
        ['label' => 'Services', 'slug' => 'services'],
        ['label' => 'How it works', 'slug' => 'approach'],
        ['label' => 'Risk & calculators', 'slug' => 'ai-tools'],
        ['label' => 'Blog', 'route' => 'insights.index'],
        ['label' => 'About', 'slug' => 'about'],
    ],

    'footer' => [
        'InvestAI' => [
            ['label' => 'Services', 'slug' => 'services'],
            ['label' => 'How it works', 'slug' => 'approach'],
            ['label' => 'Risk & calculators', 'slug' => 'ai-tools'],
            ['label' => 'About InvestAI', 'slug' => 'about'],
        ],
        'Company' => [
            ['label' => 'About InvestAI', 'slug' => 'about'],
            ['label' => 'Contact', 'slug' => 'contact'],
        ],
        'Resources' => [
            ['label' => 'Blog', 'route' => 'insights.index'],
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
