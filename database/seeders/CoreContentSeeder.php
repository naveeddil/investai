<?php

namespace Database\Seeders;

use App\Enums\PageStatus;
use App\Models\Navigation;
use App\Models\Page;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class CoreContentSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->pages() as $slug => $data) {
            Page::updateOrCreate(['slug' => $slug], [
                ...$data,
                'blocks' => collect($data['blocks'])->map(fn (array $block): array => [
                    'type' => $block['type'],
                    'data' => Arr::except($block, 'type'),
                ])->all(),
                'status' => PageStatus::Published,
                'is_system_page' => in_array($slug, ['home', 'privacy', 'terms', 'financial-disclaimer'], true),
                'deletion_protected' => in_array($slug, ['home', 'privacy', 'terms', 'financial-disclaimer'], true),
                'index_by_search_engines' => true,
                'robots_follow' => true,
                'published_at' => now(),
            ]);
        }

        Navigation::updateOrCreate(['key' => 'header'], ['name' => 'Header Navigation']);
        Navigation::updateOrCreate(['key' => 'footer'], ['name' => 'Footer Navigation']);
        SiteSetting::updateOrCreate(['key' => 'appearance.theme'], [
            'group' => 'appearance',
            'value' => 'ivory',
            'type' => 'string',
        ]);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function pages(): array
    {
        return [
            'solutions' => [
                'title' => 'Solutions', 'description' => 'The investment research experiences InvestAI is exploring.',
                'blocks' => [
                    ['type' => 'hero', 'eyebrow' => 'Solutions in development', 'heading' => 'Research that works harder for the question.', 'text' => 'InvestAI is exploring tools that help turn fragmented investment information into a coherent, reviewable research process.', 'image' => '/images/editorial/financial-district.jpg', 'image_alt' => 'Modern financial district skyline', 'background' => 'dark'],
                    ['type' => 'feature_cards', 'heading' => 'Designed around real research needs.', 'intro' => 'The following areas describe our product direction, not currently available investment services.', 'items' => [['heading' => 'Research workspace', 'text' => 'Bring sources, notes, questions and competing viewpoints into one structured context.', 'image' => '/images/editorial/reflected-architecture.jpg', 'image_alt' => 'Connected city structures representing a research workspace'], ['heading' => 'AI-assisted exploration', 'text' => 'Use AI to organise material, identify gaps and explore scenarios with visible limitations.', 'image' => '/images/editorial/glass-architecture.jpg', 'image_alt' => 'Geometric network representing structured analysis'], ['heading' => 'Decision record', 'text' => 'Capture assumptions, evidence and changes in thinking so decisions can be reviewed over time.', 'image' => '/images/editorial/financial-district.jpg', 'image_alt' => 'Financial district representing investment decisions']], 'background' => 'light'],
                    ['type' => 'split_content', 'eyebrow' => 'For individual investors', 'heading' => 'A calmer way to examine an idea.', 'text' => 'Instead of starting with a prediction, begin with the goal, time horizon, risks and evidence. The proposed experience will help users organise questions, compare sources and record why a view changed—without pretending uncertainty can be eliminated.', 'image' => '/images/editorial/reflected-architecture.jpg', 'image_alt' => 'Reflected architecture suggesting alternative viewpoints', 'image_side' => 'right', 'points' => [['text' => 'Clarify the purpose and time horizon'], ['text' => 'Identify the assumptions carrying the most risk'], ['text' => 'Compare supporting and contrary evidence'], ['text' => 'Keep education separate from personalised advice']], 'background' => 'surface'],
                    ['type' => 'process', 'eyebrow' => 'Built for review', 'heading' => 'A transparent research trail.', 'steps' => [['heading' => 'Question', 'text' => 'Begin with a defined investment question rather than an open-ended stream of content.'], ['heading' => 'Evidence', 'text' => 'Connect claims to sources and distinguish observed facts from interpretation.'], ['heading' => 'Reflection', 'text' => 'Revisit the original thesis as new information changes the balance of evidence.']], 'background' => 'surface'],
                    ['type' => 'cta', 'heading' => 'Tell us where investment research breaks down for you.', 'text' => 'Your perspective can help us prioritise the problems worth solving.', 'button_label' => 'Share your perspective', 'button_url' => '/contact', 'background' => 'dark'],
                ],
            ],
            'technology' => [
                'title' => 'Technology', 'description' => 'Our approach to responsible and explainable investment technology.',
                'blocks' => [
                    ['type' => 'hero', 'eyebrow' => 'Technology direction', 'heading' => 'Useful intelligence needs visible guardrails.', 'text' => 'Our technology direction prioritises explainability, source awareness, privacy and human control over novelty for its own sake.', 'image' => '/images/editorial/glass-architecture.jpg', 'image_alt' => 'Precise geometric structure of a glass roof', 'background' => 'dark'],
                    ['type' => 'split_content', 'eyebrow' => 'Responsible foundations', 'heading' => 'Architecture for trust, not just speed.', 'text' => 'AI can accelerate exploration, but fluent output is not evidence. InvestAI is being designed to keep source material, assumptions and model limitations close to the resulting insight.', 'image' => '/images/editorial/reflected-architecture.jpg', 'image_alt' => 'Reflective modern building representing transparent systems', 'image_side' => 'right', 'points' => [['text' => 'Source-aware research workflows'], ['text' => 'Human review at consequential steps'], ['text' => 'Privacy and access controls by design'], ['text' => 'Clear separation of information and advice']], 'background' => 'surface'],
                    ['type' => 'feature_cards', 'heading' => 'What responsible means in practice.', 'items' => [['heading' => 'Explainable context', 'text' => 'Show the evidence and assumptions that shaped an output.', 'image' => '/images/editorial/glass-architecture.jpg', 'image_alt' => 'Visible structure representing explainability'], ['heading' => 'Measured confidence', 'text' => 'Avoid presenting uncertain or incomplete information as settled fact.', 'image' => '/images/editorial/reflected-architecture.jpg', 'image_alt' => 'Distorted reflections representing uncertainty'], ['heading' => 'Controlled access', 'text' => 'Protect personal and proprietary information with appropriate boundaries.', 'image' => '/images/editorial/financial-district.jpg', 'image_alt' => 'Financial centre representing controlled information']], 'background' => 'light'],
                    ['type' => 'process', 'eyebrow' => 'Human-in-the-loop', 'heading' => 'AI assists; people remain accountable.', 'steps' => [['heading' => 'Retrieve', 'text' => 'Bring relevant, permitted source material into the research context.'], ['heading' => 'Interrogate', 'text' => 'Use models to organise questions and surface gaps—not to manufacture certainty.'], ['heading' => 'Review', 'text' => 'A person verifies sources, limitations and suitability before relying on an output.']], 'background' => 'surface'],
                    ['type' => 'cta', 'heading' => 'Building in AI, research or responsible finance?', 'text' => 'We are open to conversations with people working on aligned problems.', 'button_label' => 'Connect with InvestAI', 'button_url' => '/contact', 'background' => 'dark'],
                ],
            ],
            'case-studies' => [
                'title' => 'Research Scenarios', 'description' => 'Illustrative scenarios showing how disciplined research could support investment decisions.',
                'blocks' => [
                    ['type' => 'hero', 'eyebrow' => 'Illustrative research scenarios', 'heading' => 'Show the method without inventing the outcome.', 'text' => 'These scenarios demonstrate how InvestAI’s research principles could be applied. They are educational examples—not client engagements, testimonials or performance claims.', 'image' => '/images/editorial/financial-district.jpg', 'image_alt' => 'Financial district skyline representing investment research contexts', 'background' => 'dark'],
                    ['type' => 'feature_cards', 'heading' => 'Three questions worth structuring.', 'intro' => 'Each scenario begins with a decision and makes the uncertainty visible.', 'items' => [['heading' => 'Testing an investment thesis', 'text' => 'Separate the thesis into assumptions, identify disconfirming evidence and define what would change the view.', 'image' => '/images/editorial/glass-architecture.jpg', 'image_alt' => 'Structural framework representing thesis testing'], ['heading' => 'Comparing competing narratives', 'text' => 'Map where market explanations agree, where they conflict and which claims depend on unverifiable forecasts.', 'image' => '/images/editorial/reflected-architecture.jpg', 'image_alt' => 'Multiple reflections representing competing narratives'], ['heading' => 'Reviewing a changed environment', 'text' => 'Revisit the evidence after a material event without allowing the latest headline to replace the original decision framework.', 'image' => '/images/editorial/financial-district.jpg', 'image_alt' => 'Changing financial environment']], 'background' => 'light'],
                    ['type' => 'split_content', 'eyebrow' => 'Worked example', 'heading' => 'What would need to be true?', 'text' => 'Consider a hypothetical company expected to benefit from sustained demand. A disciplined review would separate reported results from expectations, identify reliance on pricing or market share, examine balance-sheet resilience and write down evidence that would weaken the thesis.', 'image' => '/images/editorial/glass-architecture.jpg', 'image_alt' => 'Ordered architectural lines representing a structured research example', 'image_side' => 'left', 'points' => [['text' => 'Observation: what is supported by current evidence?'], ['text' => 'Inference: what interpretation connects the facts?'], ['text' => 'Forecast: which future outcome remains uncertain?'], ['text' => 'Review trigger: what evidence would change the conclusion?']], 'background' => 'surface'],
                    ['type' => 'process', 'eyebrow' => 'Scenario method', 'heading' => 'A repeatable path from question to review.', 'steps' => [['heading' => 'Define', 'text' => 'State the decision, horizon and constraints before gathering more information.'], ['heading' => 'Challenge', 'text' => 'Seek contrary evidence, alternative explanations and material risks.'], ['heading' => 'Document', 'text' => 'Record the reasoning and signals that would justify revisiting the conclusion.']], 'background' => 'surface'],
                    ['type' => 'cta', 'heading' => 'Have a research workflow worth exploring?', 'text' => 'Discuss the process with us without sharing confidential or sensitive financial information.', 'button_label' => 'Request a consultation', 'button_url' => '/consultation', 'background' => 'dark'],
                ],
            ],
            'privacy' => ['title' => 'Privacy Policy', 'description' => 'How InvestAI handles personal information.', 'page_type' => 'legal', 'blocks' => [['type' => 'rich_text', 'heading' => 'Privacy Policy', 'text' => 'This policy is being prepared for formal legal review before public launch. Until then, please avoid submitting sensitive personal or financial information through this website.', 'background' => 'light']]],
            'terms' => ['title' => 'Terms', 'description' => 'Terms for using the InvestAI website.', 'page_type' => 'legal', 'blocks' => [['type' => 'rich_text', 'heading' => 'Terms of Use', 'text' => 'These terms are being prepared for formal legal review before public launch. Website content may change as InvestAI develops.', 'background' => 'light']]],
            'financial-disclaimer' => ['title' => 'Financial Disclaimer', 'description' => 'Important financial information.', 'page_type' => 'legal', 'blocks' => [['type' => 'rich_text', 'heading' => 'Financial Disclaimer', 'text' => 'InvestAI website content is provided for general informational purposes only. It is not investment, financial, legal or tax advice, and it is not a recommendation or offer to buy or sell any security or financial product. Independent professional advice should be obtained before making financial decisions. This wording requires formal legal review before launch.', 'background' => 'light']]],
        ];
    }
}
