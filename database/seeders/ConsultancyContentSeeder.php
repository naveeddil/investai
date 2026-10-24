<?php

namespace Database\Seeders;

use App\Enums\PageStatus;
use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

/**
 * Seeds the business and analytics consultancy pages: the homepage, the
 * company pages, the four service lines, resources and the platform page.
 *
 * Re-running this seeder resets these pages to the source content below, so
 * run it only when CMS edits to these slugs may be overwritten.
 */
class ConsultancyContentSeeder extends Seeder
{
    private const DISTRICT = '/images/editorial/financial-district.jpg';

    private const GLASS = '/images/editorial/glass-architecture.jpg';

    private const REFLECTED = '/images/editorial/reflected-architecture.jpg';

    public function run(): void
    {
        foreach ($this->pages() as $slug => $data) {
            $isSystemPage = $slug === 'home';

            Page::updateOrCreate(['slug' => $slug], [
                ...$data,
                'blocks' => collect($data['blocks'])->map(fn (array $block): array => [
                    'type' => $block['type'],
                    'data' => Arr::except($block, 'type'),
                ])->all(),
                'status' => PageStatus::Published,
                'is_system_page' => $isSystemPage,
                'deletion_protected' => $isSystemPage,
                'index_by_search_engines' => true,
                'robots_follow' => true,
                'published_at' => now(),
            ]);
        }
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function pages(): array
    {
        return [
            'home' => $this->home(),
            ...$this->serviceLines(),
            ...$this->companyPages(),
            ...$this->resourcePages(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function home(): array
    {
        return [
            'title' => 'InvestAI',
            'description' => 'Business and analytics consultancy: strategy, data, governed AI and financial analytics for clearer decisions.',
            'page_type' => 'home',
            'seo_title' => 'InvestAI | Business & analytics consultancy',
            'seo_description' => 'InvestAI helps organisations turn data into clear, defensible decisions through business strategy, analytics, governed AI and financial analytics.',
            'blocks' => [
                ['type' => 'hero', 'eyebrow' => 'Business & analytics consultancy', 'heading' => 'Decisions you can defend. Analytics you can trust.', 'text' => 'InvestAI helps finance-led organisations turn data into clear decisions — combining business strategy, analytics engineering and responsibly governed AI, with people accountable at every step.', 'button_label' => 'Book a consultation', 'button_url' => '/consultation', 'secondary_label' => 'Explore our services', 'secondary_url' => '/services', 'visual' => 'dashboard', 'highlights' => [['text' => 'Strategy'], ['text' => 'Data & analytics'], ['text' => 'Governed AI'], ['text' => 'Financial insight']], 'background' => 'dark'],
                ['type' => 'metrics', 'items' => [['value' => '4', 'label' => 'Integrated service lines, from strategy to financial analytics'], ['value' => '4', 'label' => 'Stages from diagnosis to embedded change'], ['value' => '100%', 'label' => 'Of AI-assisted output reviewed by a person before use'], ['value' => '0', 'label' => 'Black-box recommendations — assumptions are always shown']], 'background' => 'surface'],
                ['type' => 'service_grid', 'eyebrow' => 'What we do', 'heading' => 'Four connected ways to improve how you decide.', 'intro' => 'Each service line stands alone, and they are designed to work together — from the question a leadership team is asking to the numbers that answer it.', 'items' => [
                    ['icon' => 'briefcase', 'heading' => 'Business Strategy & Performance', 'text' => 'Clarify priorities, define the KPIs that matter and build business cases with assumptions everyone can see.', 'link_label' => 'Explore strategy', 'link_url' => '/business-strategy'],
                    ['icon' => 'chart', 'heading' => 'Data & Analytics', 'text' => 'Create one trusted source of truth, then turn it into dashboards, forecasts and decision-ready reporting.', 'link_label' => 'Explore analytics', 'link_url' => '/data-analytics'],
                    ['icon' => 'sparkles', 'heading' => 'AI Strategy & Automation', 'text' => 'Identify where AI genuinely helps, pilot it safely and automate routine work with human review built in.', 'link_label' => 'Explore AI', 'link_url' => '/ai-advisory'],
                    ['icon' => 'banknote', 'heading' => 'Financial & Investment Analytics', 'text' => 'Strengthen FP&A, performance and risk reporting, and bring discipline to investment research processes.', 'link_label' => 'Explore financial analytics', 'link_url' => '/financial-analytics'],
                ], 'background' => 'light'],
                ['type' => 'split_content', 'eyebrow' => 'Why InvestAI', 'heading' => 'Finance-first thinking, applied to data and AI.', 'text' => 'Many analytics and AI projects fail not because the technology is weak, but because the decision, the owner or the definition of success was never clear. We start there — and keep the reasoning visible all the way through.', 'image' => self::DISTRICT, 'image_alt' => 'Financial district skyline representing finance-led analysis', 'image_side' => 'right', 'points' => [['text' => 'Analysis anchored in how money, risk and performance really work'], ['text' => 'Plain-English findings tied to a recommended decision'], ['text' => 'Tool-agnostic recommendations chosen to fit your environment'], ['text' => 'People remain accountable for every consequential judgement'], ['text' => 'Confidentiality and data protection considered from the first conversation']], 'link_label' => 'See how we work', 'link_url' => '/approach', 'background' => 'surface'],
                ['type' => 'comparison', 'eyebrow' => 'A different starting point', 'heading' => 'Why analytics projects stall — and how we avoid it.', 'left_label' => 'Typical approach', 'right_label' => 'The InvestAI approach', 'rows' => [
                    ['topic' => 'Starting point', 'left' => 'Begin with a tool or whatever data happens to be available.', 'right' => 'Begin with the decision, the person who owns it and the question that matters.'],
                    ['topic' => 'Assumptions', 'left' => 'Buried in spreadsheets or model code.', 'right' => 'Written down, challenged and visible to everyone who relies on them.'],
                    ['topic' => 'Use of AI', 'left' => 'Isolated experiments with no clear owner or controls.', 'right' => 'Prioritised use cases, human review and documented limitations.'],
                    ['topic' => 'Delivery', 'left' => 'A dashboard handed over at the end of the project.', 'right' => 'Working increments, shared ownership and training so your team can run it.'],
                    ['topic' => 'Communication', 'left' => 'Technical output that few people can act on.', 'right' => 'Short, plain-English findings that lead to a recommended decision.'],
                ], 'background' => 'light'],
                ['type' => 'service_grid', 'eyebrow' => 'Who we help', 'heading' => 'Sectors where a finance-led lens helps most.', 'intro' => 'Our finance-led lens is useful wherever money, accountability and evidence meet.', 'items' => [
                    ['icon' => 'landmark', 'heading' => 'Financial services & wealth', 'text' => 'Advisers, platforms and asset-focused teams needing clearer performance, cost and risk reporting.'],
                    ['icon' => 'cpu', 'heading' => 'Fintech & start-ups', 'text' => 'Founders building data-informed products, unit economics and investor-ready metrics.'],
                    ['icon' => 'building', 'heading' => 'SMEs & scale-ups', 'text' => 'Growing businesses replacing spreadsheet sprawl with reliable management information.'],
                    ['icon' => 'globe', 'heading' => 'Non-profit & public sector', 'text' => 'Organisations that must evidence value, control and compliance to funders and stakeholders.'],
                ], 'background' => 'dark'],
                ['type' => 'process', 'eyebrow' => 'How we work', 'heading' => 'A clear path from question to embedded change.', 'steps' => [
                    ['heading' => 'Diagnose', 'text' => 'Understand the decision, the people involved and the current state of data, process and controls.'],
                    ['heading' => 'Design', 'text' => 'Agree the target approach, measures of success and the simplest solution that achieves them.'],
                    ['heading' => 'Deliver', 'text' => 'Build and test in working increments, with assumptions and limitations documented as we go.'],
                    ['heading' => 'Embed', 'text' => 'Hand over with training, documentation and governance so improvements continue after we leave.'],
                ], 'background' => 'surface'],
                ['type' => 'service_grid', 'eyebrow' => 'Start with something useful', 'heading' => 'Self-service tools to start the conversation.', 'intro' => 'Quick, private ways to see where you stand before you speak to anyone. The checks run in your browser.', 'items' => [
                    ['icon' => 'clipboard', 'heading' => 'Analytics & AI readiness check', 'text' => 'Eight questions across strategy, data, governance and automation, with tailored next steps.', 'link_label' => 'Take the check', 'link_url' => '/ai-tools#readiness'],
                    ['icon' => 'calculator', 'heading' => 'Reporting time estimator', 'text' => 'See what recurring manual reporting costs in hours and pounds — and what automation could free up.', 'link_label' => 'Open the estimator', 'link_url' => '/ai-tools#estimator'],
                    ['icon' => 'scale', 'heading' => 'Financial risk calculator', 'text' => 'See how a mix of assets could behave in a bad year — with or without an AI-written explanation.', 'link_label' => 'Try the calculator', 'link_url' => '/ai-tools#risk'],
                    ['icon' => 'document', 'heading' => 'Weekly insights', 'text' => 'Plain-English perspectives on research habits, risk and disciplined decision-making.', 'link_label' => 'Read insights', 'link_url' => '/insights'],
                ], 'background' => 'light'],
                ['type' => 'split_content', 'eyebrow' => 'Also in development', 'heading' => 'The InvestAI platform: AI-assisted investment research.', 'text' => 'Alongside consulting, we are building a platform that helps people understand their goals, risk preferences and evidence before they invest. It is in development, will be introduced in stages, and will launch only with the appropriate regulatory permissions in place.', 'image' => self::REFLECTED, 'image_alt' => 'Reflective architecture representing multiple perspectives on a decision', 'image_side' => 'left', 'points' => [['text' => 'Personal profile built from goals, horizon and risk preferences'], ['text' => 'Transparent reasoning and visible assumptions'], ['text' => 'Education first — not a substitute for regulated advice']], 'link_label' => 'See the platform roadmap', 'link_url' => '/platform', 'background' => 'dark'],
                ['type' => 'latest_insights', 'eyebrow' => 'Insights', 'heading' => 'Fresh thinking, reviewed by a person.', 'intro' => 'Educational perspectives on research habits, risk awareness and long-term decision-making.', 'background' => 'light'],
                ['type' => 'callout', 'icon' => 'lock', 'heading' => 'Confidentiality comes first.', 'text' => 'We agree confidentiality terms before any information is shared, work with the minimum data necessary, restrict access to named people and agree how AI tools may be used with your data.', 'link_label' => 'Read about confidentiality and data protection', 'link_url' => '/confidentiality', 'background' => 'surface'],
                ['type' => 'faq', 'eyebrow' => 'Questions', 'heading' => 'Good questions to ask any consultancy.', 'open_first' => true, 'items' => [
                    ['question' => 'What does InvestAI do?', 'answer' => 'InvestAI is a business and analytics consultancy. We help organisations clarify decisions, build trusted data and reporting, apply AI responsibly and understand financial performance. We are also developing an AI-assisted investment research platform.'],
                    ['question' => 'Do you provide investment advice?', 'answer' => 'No. Our consulting work is analytical and advisory on business and data matters. Nothing on this website is investment, financial, legal or tax advice. Any regulated activity would be offered only once the required authorisations are in place.'],
                    ['question' => 'How do you use AI in your own work?', 'answer' => 'AI helps us draft, summarise and explore options faster. Every output is reviewed by a person, sources are checked, and we agree with you in advance which information may be processed by which tools.'],
                    ['question' => 'How does an engagement start?', 'answer' => 'With a conversation, followed by a short diagnostic that defines scope, success measures and fees in writing before wider work begins.'],
                    ['question' => 'How do you handle confidential data?', 'answer' => 'We agree confidentiality terms before data is shared, use the minimum data necessary and discuss security requirements at the outset.'],
                ], 'background' => 'dark'],
                ['type' => 'cta', 'heading' => 'Make your next decision a well-evidenced one.', 'text' => 'Tell us what you are trying to decide. We will help you work out what evidence, analysis and AI support would genuinely help.', 'button_label' => 'Book a consultation', 'button_url' => '/consultation', 'secondary_label' => 'Send an enquiry', 'secondary_url' => '/contact', 'background' => 'surface'],
            ],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function serviceLines(): array
    {
        return [
            'services' => [
                'title' => 'Services', 'navigation_label' => 'Services', 'description' => 'Business strategy, data and analytics, AI and automation, and financial analytics consulting.',
                'seo_title' => 'Services | Business & analytics consulting | InvestAI',
                'seo_description' => 'Four connected consulting services: business strategy and performance, data and analytics, AI strategy and automation, and financial and investment analytics.',
                'blocks' => [
                    ['type' => 'hero', 'size' => 'compact', 'eyebrow' => 'Services', 'heading' => 'Consulting built around the decision, not the dashboard.', 'text' => 'Strategy, analytics, AI and financial insight — delivered by people who understand both the numbers and the organisation behind them.', 'button_label' => 'Book a consultation', 'button_url' => '/consultation', 'secondary_label' => 'Check your readiness', 'secondary_url' => '/ai-tools#readiness', 'visual' => 'dashboard', 'background' => 'dark'],
                    ['type' => 'service_grid', 'eyebrow' => 'Service lines', 'heading' => 'Choose one, or combine them.', 'intro' => 'Most clients begin with a single question and find that strategy, data, AI and finance are connected. We scope accordingly.', 'items' => [
                        ['icon' => 'briefcase', 'heading' => 'Business Strategy & Performance', 'text' => 'Priorities, operating model, KPI frameworks, business cases and transformation roadmaps.', 'link_label' => 'View service', 'link_url' => '/business-strategy'],
                        ['icon' => 'chart', 'heading' => 'Data & Analytics', 'text' => 'Data strategy, trusted metrics, dashboards, forecasting and analytics enablement.', 'link_label' => 'View service', 'link_url' => '/data-analytics'],
                        ['icon' => 'sparkles', 'heading' => 'AI Strategy & Automation', 'text' => 'Opportunity assessment, governed pilots, workflow automation and responsible-AI controls.', 'link_label' => 'View service', 'link_url' => '/ai-advisory'],
                        ['icon' => 'banknote', 'heading' => 'Financial & Investment Analytics', 'text' => 'FP&A, management information, performance and risk reporting, and research process design.', 'link_label' => 'View service', 'link_url' => '/financial-analytics'],
                    ], 'background' => 'light'],
                    ['type' => 'split_content', 'eyebrow' => 'Typical engagements', 'heading' => 'Where clients usually ask for help.', 'text' => 'These examples show the kind of problem we are brought in to solve. Every engagement is scoped to your situation.', 'image' => self::GLASS, 'image_alt' => 'Structured glass architecture representing organised analysis', 'image_side' => 'left', 'points' => [['text' => 'Redesigning management reporting so leaders see one version of the truth'], ['text' => 'Defining a KPI framework and the data needed to track it'], ['text' => 'Building forecasts and scenario models for budgeting and planning'], ['text' => 'Prioritising AI use cases and running safe, measured pilots'], ['text' => 'Automating repetitive finance and operations reporting'], ['text' => 'Improving fee, cost and performance transparency'], ['text' => 'Establishing data governance, ownership and controls']], 'background' => 'surface'],
                    ['type' => 'tool_stack', 'eyebrow' => 'Toolset', 'heading' => 'We fit your environment, not the other way round.', 'intro' => 'Typical categories of tools we work with. We recommend what suits your skills, budget and security requirements.', 'groups' => [['label' => 'Analysis & modelling', 'items' => 'Excel, Python, R, SQL'], ['label' => 'Dashboards & BI', 'items' => 'Power BI, Tableau, Looker Studio'], ['label' => 'Data platforms', 'items' => 'Cloud warehouses, ETL / ELT, APIs'], ['label' => 'AI & automation', 'items' => 'LLM assistants, workflow automation, document processing']], 'background' => 'dark'],
                    ['type' => 'process', 'eyebrow' => 'How we engage', 'heading' => 'Four stages, one consistent method.', 'steps' => [['heading' => 'Diagnose', 'text' => 'Clarify the decision, stakeholders and current state.'], ['heading' => 'Design', 'text' => 'Agree the approach, measures and simplest workable solution.'], ['heading' => 'Deliver', 'text' => 'Build, test and document in working increments.'], ['heading' => 'Embed', 'text' => 'Train, hand over and set up governance to sustain change.']], 'background' => 'surface'],
                    ['type' => 'cta', 'heading' => 'Not sure which service fits?', 'text' => 'Take the five-minute readiness check, or talk to us about the decision you are facing.', 'button_label' => 'Book a consultation', 'button_url' => '/consultation', 'secondary_label' => 'Take the readiness check', 'secondary_url' => '/ai-tools#readiness', 'background' => 'dark'],
                ],
            ],
            'business-strategy' => [
                'title' => 'Business Strategy & Performance', 'navigation_label' => 'Business Strategy', 'description' => 'Priorities, KPIs, business cases and transformation roadmaps built on visible assumptions.',
                'seo_title' => 'Business Strategy & Performance consulting | InvestAI',
                'seo_description' => 'Clarify priorities, define KPIs, build business cases and plan transformation with assumptions everyone can see.',
                'blocks' => [
                    ['type' => 'hero', 'size' => 'compact', 'eyebrow' => 'Business Strategy & Performance', 'heading' => 'Clear priorities. Measurable progress.', 'text' => 'We help leadership teams agree what matters most, measure it properly and make investment decisions with the reasoning on the table.', 'button_label' => 'Discuss your priorities', 'button_url' => '/consultation', 'image' => self::DISTRICT, 'image_alt' => 'City skyline representing strategic direction', 'background' => 'dark'],
                    ['type' => 'service_grid', 'eyebrow' => 'Capabilities', 'heading' => 'What we help with.', 'items' => [
                        ['icon' => 'target', 'heading' => 'Strategy & priorities', 'text' => 'Translate ambition into a small number of priorities, owners and trade-offs.'],
                        ['icon' => 'trend', 'heading' => 'KPI & performance frameworks', 'text' => 'Define metrics with clear owners, definitions and targets, and link them to decisions.'],
                        ['icon' => 'scale', 'heading' => 'Business cases & investment appraisal', 'text' => 'Compare options with explicit assumptions, scenarios and review triggers.'],
                        ['icon' => 'layers', 'heading' => 'Operating model & process review', 'text' => 'Find where roles, handoffs or tools create delay, rework or risk.'],
                        ['icon' => 'flag', 'heading' => 'Transformation roadmaps', 'text' => 'Sequence initiatives realistically and set up lightweight governance to track delivery.'],
                        ['icon' => 'shield', 'heading' => 'Risk & controls', 'text' => 'Identify the risks that matter and the evidence that controls are working.'],
                    ], 'background' => 'light'],
                    ['type' => 'split_content', 'eyebrow' => 'What you receive', 'heading' => 'Outputs you can use the next morning.', 'text' => 'We favour short, practical deliverables over long reports. Typical outputs are tailored to the engagement.', 'image' => self::GLASS, 'image_alt' => 'Ordered glass structure representing a clear framework', 'image_side' => 'right', 'points' => [['text' => 'A one-page statement of priorities, owners and success measures'], ['text' => 'A KPI dictionary with definitions, sources and owners'], ['text' => 'Business cases with assumptions, scenarios and sensitivities'], ['text' => 'A phased roadmap with dependencies and decision points']], 'background' => 'surface'],
                    ['type' => 'process', 'eyebrow' => 'Example engagement', 'heading' => 'From a vague ambition to a decision.', 'steps' => [['heading' => 'Frame', 'text' => 'Define the decision, constraints and what a good outcome looks like.'], ['heading' => 'Evidence', 'text' => 'Gather the data and perspectives needed, noting what is unknown.'], ['heading' => 'Options', 'text' => 'Compare realistic options with explicit assumptions and risks.'], ['heading' => 'Decide & track', 'text' => 'Agree the decision, owners and the measures that will show whether it worked.']], 'background' => 'dark'],
                    ['type' => 'cta', 'heading' => 'Facing a decision that needs better evidence?', 'text' => 'Share the context and we will suggest a sensible first step.', 'button_label' => 'Book a consultation', 'button_url' => '/consultation', 'secondary_label' => 'All services', 'secondary_url' => '/services', 'background' => 'surface'],
                ],
            ],
            'data-analytics' => [
                'title' => 'Data & Analytics', 'navigation_label' => 'Data & Analytics', 'description' => 'Trusted data, dashboards, forecasting and analytics enablement.',
                'seo_title' => 'Data & Analytics consulting | InvestAI',
                'seo_description' => 'Build one trusted source of truth, then turn it into dashboards, forecasts and decision-ready reporting your team can run.',
                'blocks' => [
                    ['type' => 'hero', 'size' => 'compact', 'eyebrow' => 'Data & Analytics', 'heading' => 'One trusted version of the numbers.', 'text' => 'We replace scattered spreadsheets and competing definitions with reliable data, clear reporting and forecasts your team understands.', 'button_label' => 'Talk about your data', 'button_url' => '/consultation', 'visual' => 'dashboard', 'background' => 'dark'],
                    ['type' => 'service_grid', 'eyebrow' => 'Capabilities', 'heading' => 'From raw data to confident decisions.', 'items' => [
                        ['icon' => 'database', 'heading' => 'Data strategy & architecture', 'text' => 'Decide what data you need, where it should live and how it should flow.'],
                        ['icon' => 'chart', 'heading' => 'Dashboards & self-service BI', 'text' => 'Design reporting people actually use, with agreed definitions and drill-down.'],
                        ['icon' => 'workflow', 'heading' => 'Data modelling & pipelines', 'text' => 'Build repeatable, tested pipelines that refresh on schedule.'],
                        ['icon' => 'trend', 'heading' => 'Forecasting & predictive models', 'text' => 'Develop forecasts with transparent assumptions and honest measures of uncertainty.'],
                        ['icon' => 'shield', 'heading' => 'Data quality & governance', 'text' => 'Set ownership, standards and monitoring so trust is maintained.'],
                        ['icon' => 'users', 'heading' => 'Analytics enablement', 'text' => 'Train teams and document solutions so you are not dependent on us.'],
                    ], 'background' => 'light'],
                    ['type' => 'split_content', 'eyebrow' => 'The common problem', 'heading' => 'From dashboard sprawl to a single trusted view.', 'text' => 'When every team has its own spreadsheet and definition of “revenue”, meetings become arguments about the numbers. We start by agreeing definitions and sources, then automate the path from source to report.', 'image' => self::REFLECTED, 'image_alt' => 'Reflections illustrating multiple versions of the same data', 'image_side' => 'left', 'points' => [['text' => 'Agree metric definitions and owners'], ['text' => 'Consolidate sources and automate refresh'], ['text' => 'Design reports around the decisions they inform'], ['text' => 'Monitor quality so errors are caught early']], 'background' => 'surface'],
                    ['type' => 'tool_stack', 'eyebrow' => 'Toolset', 'heading' => 'Pragmatic technology choices.', 'groups' => [['label' => 'Analysis', 'items' => 'Python, R, SQL, Excel'], ['label' => 'Reporting', 'items' => 'Power BI, Tableau, Looker Studio'], ['label' => 'Data movement', 'items' => 'ETL / ELT, APIs, scheduled jobs'], ['label' => 'Platforms', 'items' => 'Cloud databases and warehouses']], 'background' => 'dark'],
                    ['type' => 'cta', 'heading' => 'Ready to trust your numbers?', 'text' => 'Start with a short diagnostic of your reporting, data sources and definitions.', 'button_label' => 'Book a consultation', 'button_url' => '/consultation', 'secondary_label' => 'Reporting time estimator', 'secondary_url' => '/ai-tools#estimator', 'background' => 'surface'],
                ],
            ],
            'ai-advisory' => [
                'title' => 'AI Strategy & Automation', 'navigation_label' => 'AI & Automation', 'description' => 'Practical, governed use of AI and automation, with people accountable for outcomes.',
                'seo_title' => 'AI Strategy & Automation consulting | InvestAI',
                'seo_description' => 'Identify where AI genuinely helps, pilot it safely with human review and automate routine work responsibly.',
                'blocks' => [
                    ['type' => 'hero', 'size' => 'compact', 'eyebrow' => 'AI Strategy & Automation', 'heading' => 'AI that earns its place.', 'text' => 'We help you find the few use cases where AI saves real time or improves quality, pilot them safely and build the controls that keep people accountable.', 'button_label' => 'Explore AI opportunities', 'button_url' => '/consultation', 'secondary_label' => 'Take the readiness check', 'secondary_url' => '/ai-tools#readiness', 'image' => self::GLASS, 'image_alt' => 'Geometric structure representing organised intelligence', 'background' => 'dark'],
                    ['type' => 'service_grid', 'eyebrow' => 'Capabilities', 'heading' => 'Practical, not theoretical.', 'items' => [
                        ['icon' => 'lightbulb', 'heading' => 'AI opportunity assessment', 'text' => 'Map processes, shortlist use cases and rank them by value, feasibility and risk.'],
                        ['icon' => 'sparkles', 'heading' => 'LLM-assisted workflows', 'text' => 'Draft, summarise, classify and answer questions over approved internal content.'],
                        ['icon' => 'workflow', 'heading' => 'Process automation', 'text' => 'Automate repeatable reporting, reconciliation and document handling.'],
                        ['icon' => 'shield', 'heading' => 'Responsible-AI governance', 'text' => 'Policies, human-review points, logging and data-protection controls.'],
                        ['icon' => 'database', 'heading' => 'Knowledge bases & retrieval', 'text' => 'Organise trusted source material so AI answers can be traced and checked.'],
                        ['icon' => 'flag', 'heading' => 'Pilot to production', 'text' => 'Measure the value of a pilot honestly, then scale only what works.'],
                    ], 'background' => 'light'],
                    ['type' => 'comparison', 'eyebrow' => 'Human in the loop', 'heading' => 'AI assists. People decide.', 'intro' => 'We design every workflow so it is clear what AI is doing and where a person must review.', 'topic_label' => 'Task type', 'left_label' => 'AI can help with', 'right_label' => 'People remain accountable for', 'rows' => [
                        ['topic' => 'Information', 'left' => 'Drafting, summarising and organising large volumes of material.', 'right' => 'Verifying sources and confirming nothing material has been missed or invented.'],
                        ['topic' => 'Patterns', 'left' => 'Surfacing trends, anomalies and relationships in data.', 'right' => 'Interpreting causes, context and what the pattern means for the decision.'],
                        ['topic' => 'Scenarios', 'left' => 'Generating and comparing alternative scenarios quickly.', 'right' => 'Choosing the assumptions and accepting the risk of acting on them.'],
                        ['topic' => 'Routine work', 'left' => 'Reconciliations, formatting, classification and first-pass checks.', 'right' => 'Handling exceptions, approvals and judgement calls.'],
                    ], 'background' => 'surface'],
                    ['type' => 'callout', 'icon' => 'lock', 'heading' => 'Your information stays under your control.', 'text' => 'We agree in advance which data may be processed, by which tools, with what retention and access controls. Confidential or personal information is never placed in public AI tools without an agreed, appropriate arrangement.', 'link_label' => 'Read about our technology principles', 'link_url' => '/technology', 'background' => 'dark'],
                    ['type' => 'cta', 'heading' => 'Where could AI help in your organisation?', 'text' => 'Start with a short opportunity assessment that ranks use cases by value and risk.', 'button_label' => 'Book a consultation', 'button_url' => '/consultation', 'secondary_label' => 'Reporting time estimator', 'secondary_url' => '/ai-tools#estimator', 'background' => 'surface'],
                ],
            ],
            'financial-analytics' => [
                'title' => 'Financial & Investment Analytics', 'navigation_label' => 'Financial Analytics', 'description' => 'FP&A, management information, performance and risk analysis, and disciplined investment research processes.',
                'seo_title' => 'Financial & Investment Analytics consulting | InvestAI',
                'seo_description' => 'Strengthen FP&A, management information, performance and risk reporting, and bring discipline to investment research processes.',
                'blocks' => [
                    ['type' => 'hero', 'size' => 'compact', 'eyebrow' => 'Financial & Investment Analytics', 'heading' => 'Financial insight leaders can act on.', 'text' => 'From budgeting and forecasting to performance, cost and risk analysis, we turn financial data into clear, well-reasoned management information.', 'button_label' => 'Discuss your reporting', 'button_url' => '/consultation', 'image' => self::DISTRICT, 'image_alt' => 'Financial district representing financial analysis', 'background' => 'dark'],
                    ['type' => 'service_grid', 'eyebrow' => 'Capabilities', 'heading' => 'Finance at the centre of the analysis.', 'items' => [
                        ['icon' => 'banknote', 'heading' => 'FP&A support', 'text' => 'Budgeting, rolling forecasts, variance analysis and board-ready commentary.'],
                        ['icon' => 'document', 'heading' => 'Management information packs', 'text' => 'Concise monthly packs that lead with what changed and why.'],
                        ['icon' => 'trend', 'heading' => 'Performance & attribution reporting', 'text' => 'Understand where returns, margins or outcomes came from.'],
                        ['icon' => 'scale', 'heading' => 'Cost & fee transparency', 'text' => 'Break down total costs so charges can be understood and challenged.'],
                        ['icon' => 'shield', 'heading' => 'Risk & scenario analysis', 'text' => 'Stress assumptions and show the range of plausible outcomes.'],
                        ['icon' => 'search', 'heading' => 'Investment research process design', 'text' => 'Structure thesis, evidence and review triggers so decisions can be audited.'],
                    ], 'background' => 'light'],
                    ['type' => 'split_content', 'eyebrow' => 'Research discipline', 'heading' => 'Make the reasoning behind a decision reviewable.', 'text' => 'Whether you manage a budget, a programme or a portfolio, the quality of a decision depends on the quality of the reasoning behind it. We help teams document assumptions, test the opposing case and decide in advance what evidence would change their mind.', 'image' => self::REFLECTED, 'image_alt' => 'Reflections representing the opposing case', 'image_side' => 'left', 'points' => [['text' => 'State the thesis, horizon and constraints'], ['text' => 'Separate observation, inference and forecast'], ['text' => 'Seek disconfirming evidence deliberately'], ['text' => 'Define review triggers before you need them']], 'link_label' => 'See illustrative research scenarios', 'link_url' => '/case-studies', 'background' => 'surface'],
                    ['type' => 'callout', 'icon' => 'scale', 'heading' => 'Analysis and education — not regulated advice.', 'text' => 'Our financial analytics work supports organisations’ own decisions. We do not recommend that any person buy or sell a financial product, and nothing here is personalised investment advice.', 'link_label' => 'Read the financial disclaimer', 'link_url' => '/financial-disclaimer', 'background' => 'dark'],
                    ['type' => 'cta', 'heading' => 'Want clearer financial reporting?', 'text' => 'Share what your reports look like today and we will suggest practical improvements.', 'button_label' => 'Book a consultation', 'button_url' => '/consultation', 'secondary_label' => 'Try the risk calculator', 'secondary_url' => '/ai-tools#risk', 'background' => 'surface'],
                ],
            ],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function companyPages(): array
    {
        return [
            'about' => [
                'title' => 'About InvestAI', 'description' => 'Our purpose, values and perspective.',
                'seo_title' => 'About InvestAI | Business & analytics consultancy',
                'seo_description' => 'Our purpose, core values and how we combine finance expertise, analytics and responsible AI.',
                'blocks' => [
                    ['type' => 'hero', 'size' => 'compact', 'eyebrow' => 'About InvestAI', 'heading' => 'Intelligence with purpose.', 'text' => 'We are a business and analytics consultancy built on finance expertise, evidence and responsible use of AI — and we are developing an AI-assisted investment research platform.', 'image' => self::REFLECTED, 'image_alt' => 'Contemporary reflective architecture beside a canal', 'background' => 'dark'],
                    ['type' => 'split_content', 'eyebrow' => 'Our purpose', 'heading' => 'Make good decisions easier to reach — and easier to explain.', 'text' => "Access to data has grown faster than people’s ability to evaluate it. Our purpose is to make decisions better structured and more understandable, while respecting the limits of models, data and prediction.\n\nWe are developing the company carefully, and will describe capabilities only as they become available.", 'image' => self::DISTRICT, 'image_alt' => 'Financial district representing evidence-led decisions', 'image_side' => 'right', 'points' => [['text' => 'Finance-led: grounded in how money and risk work'], ['text' => 'Evidence-led: assumptions stated, uncertainty shown'], ['text' => 'Human-led: AI assists, people decide']], 'background' => 'surface'],
                    ['type' => 'feature_cards', 'heading' => 'Our core values.', 'intro' => 'These values shape how we work with clients, with each other and with technology.', 'items' => [
                        ['icon' => 'shield', 'heading' => 'Integrity', 'text' => 'Commitment, honesty, open-mindedness, trust and personal responsibility in everything we do.'],
                        ['icon' => 'target', 'heading' => 'Goals-oriented', 'text' => 'Accountability, determination, empowerment, growth and hard work towards outcomes that matter.'],
                        ['icon' => 'leaf', 'heading' => 'Building a better world', 'text' => 'Innovation, creativity, sustainability and a focus on lasting impact.'],
                        ['icon' => 'users', 'heading' => 'Team first', 'text' => 'Teamwork, diversity, courage, curiosity and leadership at every level.'],
                    ], 'background' => 'light'],
                    ['type' => 'feature_cards', 'heading' => 'How we intend to work.', 'intro' => 'Principles that guide product decisions, research practices and relationships.', 'items' => [
                        ['icon' => 'eye', 'heading' => 'Useful transparency', 'text' => 'Explain the reasoning and limitations that matter to a decision.'],
                        ['icon' => 'search', 'heading' => 'Intellectual honesty', 'text' => 'Separate evidence from interpretation and say what remains uncertain.'],
                        ['icon' => 'lock', 'heading' => 'Careful with data', 'text' => 'Use the minimum data necessary and protect it properly.'],
                    ], 'background' => 'surface'],
                    ['type' => 'team', 'eyebrow' => 'People', 'heading' => 'Meet the team behind InvestAI.', 'text' => 'Profiles are published only after names, roles, biographies and photographs have been verified and approved.', 'background' => 'light'],
                    ['type' => 'cta', 'heading' => 'Interested in working together?', 'text' => 'We welcome conversations with organisations, collaborators and partners.', 'button_label' => 'Talk with us', 'button_url' => '/contact', 'secondary_label' => 'Our approach', 'secondary_url' => '/approach', 'background' => 'dark'],
                ],
            ],
            'industries' => [
                'title' => 'Industries', 'description' => 'Sectors where finance-led analytics and governed AI make a difference.',
                'seo_title' => 'Industries we serve | InvestAI',
                'seo_description' => 'Finance-led analytics and governed AI for financial services, fintech, SMEs, non-profit and public-sector organisations.',
                'blocks' => [
                    ['type' => 'hero', 'size' => 'compact', 'eyebrow' => 'Industries', 'heading' => 'Different sectors, the same need for clear evidence.', 'text' => 'We work where money, accountability and judgement meet — and adapt our approach to each sector’s regulation, data and culture.', 'button_label' => 'Discuss your sector', 'button_url' => '/consultation', 'image' => self::DISTRICT, 'image_alt' => 'City skyline representing diverse industries', 'background' => 'dark'],
                    ['type' => 'service_grid', 'eyebrow' => 'Sectors', 'heading' => 'Where we can help most.', 'items' => [
                        ['icon' => 'landmark', 'heading' => 'Financial services & wealth', 'text' => 'Typical needs: performance and attribution reporting, fee and cost transparency, research process discipline and data governance.'],
                        ['icon' => 'cpu', 'heading' => 'Fintech & start-ups', 'text' => 'Typical needs: unit economics, investor-ready KPIs, data foundations for product decisions and responsible use of AI.'],
                        ['icon' => 'building', 'heading' => 'SMEs & scale-ups', 'text' => 'Typical needs: reliable management information, cash and margin forecasting, and automation of recurring reporting.'],
                        ['icon' => 'globe', 'heading' => 'Non-profit & development', 'text' => 'Typical needs: donor and grant reporting, programme performance analytics, and evidence of value for money and control.'],
                        ['icon' => 'users', 'heading' => 'Public sector & health', 'text' => 'Typical needs: budget monitoring, performance dashboards, data quality and transparent, auditable analysis.'],
                        ['icon' => 'briefcase', 'heading' => 'Professional services', 'text' => 'Typical needs: utilisation and profitability analytics, pipeline forecasting and knowledge management with AI.'],
                    ], 'background' => 'light'],
                    ['type' => 'split_content', 'eyebrow' => 'What carries across', 'heading' => 'A finance lens makes analytics more useful.', 'text' => 'Whatever the sector, leaders need to know what changed, why, what it costs and what to do next. Our finance-led approach keeps analysis tied to those questions.', 'image' => self::GLASS, 'image_alt' => 'Structured glass representing clarity across sectors', 'image_side' => 'left', 'points' => [['text' => 'Budgets, costs and performance explained together'], ['text' => 'Controls and audit trails designed in'], ['text' => 'Plain-English reporting for non-technical audiences'], ['text' => 'Respect for sector-specific regulation and data sensitivity']], 'background' => 'surface'],
                    ['type' => 'cta', 'heading' => 'Don’t see your sector?', 'text' => 'Tell us about your context. If we are not the right fit, we will say so.', 'button_label' => 'Contact us', 'button_url' => '/contact', 'secondary_label' => 'Book a consultation', 'secondary_url' => '/consultation', 'background' => 'dark'],
                ],
            ],
            'approach' => [
                'title' => 'Our Approach', 'description' => 'A clear, evidence-led method from diagnosis to embedded change.',
                'seo_title' => 'Our approach | InvestAI',
                'seo_description' => 'Diagnose, design, deliver and embed: our evidence-led method for analytics, AI and business consulting engagements.',
                'blocks' => [
                    ['type' => 'hero', 'size' => 'compact', 'eyebrow' => 'Our approach', 'heading' => 'A disciplined loop from question to lasting change.', 'text' => 'The same four stages shape every engagement, whether it lasts two weeks or two quarters.', 'button_label' => 'Start a conversation', 'button_url' => '/consultation', 'image' => self::GLASS, 'image_alt' => 'Geometric glass roof representing a structured method', 'background' => 'dark'],
                    ['type' => 'process', 'eyebrow' => 'The method', 'heading' => 'Diagnose, design, deliver, embed.', 'steps' => [
                        ['heading' => 'Diagnose', 'text' => 'Understand the decision, the people who own it and the current state of data, process and controls.'],
                        ['heading' => 'Design', 'text' => 'Agree the target approach, measures of success and the simplest solution that will achieve them.'],
                        ['heading' => 'Deliver', 'text' => 'Build and test in working increments, documenting assumptions and limitations as we go.'],
                        ['heading' => 'Embed', 'text' => 'Hand over with training, documentation and governance so improvements are sustained.'],
                    ], 'background' => 'surface'],
                    ['type' => 'feature_cards', 'heading' => 'Principles behind every engagement.', 'items' => [
                        ['icon' => 'search', 'heading' => 'Evidence over opinion', 'text' => 'Findings are tied to sources, and what we do not know is stated plainly.'],
                        ['icon' => 'chat', 'heading' => 'Plain-English communication', 'text' => 'We write for decision-makers, not for other analysts.'],
                        ['icon' => 'users', 'heading' => 'Human accountability', 'text' => 'AI assists; named people review and own consequential judgements.'],
                        ['icon' => 'lock', 'heading' => 'Data protection by design', 'text' => 'Confidentiality terms, minimum data and access controls from day one.'],
                    ], 'background' => 'light'],
                    ['type' => 'timeline', 'eyebrow' => 'Indicative timeline', 'heading' => 'What a typical first 12 weeks can look like.', 'items' => [
                        ['phase' => 'Weeks 1–2', 'icon' => 'search', 'heading' => 'Discovery', 'text' => 'Interviews, data and process review, agreement of scope and success measures.', 'status' => 'none'],
                        ['phase' => 'Weeks 3–6', 'icon' => 'layers', 'heading' => 'Design & prototype', 'text' => 'Options, target design and a first working prototype for feedback.', 'status' => 'none'],
                        ['phase' => 'Weeks 7–10', 'icon' => 'cpu', 'heading' => 'Build & validate', 'text' => 'Develop, test with real users and reconcile results to source.', 'status' => 'none'],
                        ['phase' => 'Weeks 11–12', 'icon' => 'users', 'heading' => 'Handover', 'text' => 'Training, documentation, governance and a plan for what comes next.', 'status' => 'none'],
                    ], 'note' => 'Indicative only. Timing depends on scope, data availability and stakeholder access, and is agreed in writing before work begins.', 'background' => 'surface'],
                    ['type' => 'callout', 'icon' => 'shield', 'heading' => 'Governance and data protection.', 'text' => 'We agree confidentiality terms before data is shared, work with the minimum data needed, and document where information is processed. Where AI tools are used, we agree the permitted tools and the human-review steps in advance.', 'link_label' => 'Confidentiality and data protection', 'link_url' => '/confidentiality', 'background' => 'dark'],
                    ['type' => 'cta', 'heading' => 'See whether our approach fits your situation.', 'text' => 'A first conversation costs nothing and helps clarify whether and how we can help.', 'button_label' => 'Book a consultation', 'button_url' => '/consultation', 'secondary_label' => 'Working with us', 'secondary_url' => '/engagement-models', 'background' => 'light'],
                ],
            ],
            'confidentiality' => [
                'title' => 'Confidentiality & Data Protection', 'navigation_label' => 'Confidentiality', 'description' => 'How InvestAI protects client information: confidentiality terms first, minimum data, controlled access and governed use of AI.',
                'seo_title' => 'Confidentiality & data protection | InvestAI',
                'seo_description' => 'Confidentiality terms before data is shared, minimum data, controlled access, agreed retention and governed use of AI tools.',
                'blocks' => [
                    ['type' => 'hero', 'size' => 'compact', 'eyebrow' => 'Confidentiality & data protection', 'heading' => 'Your information stays yours.', 'text' => 'Financial, operational and personal data is often the most sensitive thing an organisation holds. We treat confidentiality as a condition of the work, not an afterthought.', 'button_label' => 'Discuss your requirements', 'button_url' => '/consultation', 'image' => self::GLASS, 'image_alt' => 'Transparent but structured glass representing controlled access', 'background' => 'dark'],
                    ['type' => 'feature_cards', 'heading' => 'Our commitments.', 'intro' => 'These describe how we intend to work. The binding terms for any engagement are set out in the written agreement.', 'items' => [
                        ['icon' => 'document', 'heading' => 'Confidentiality terms first', 'text' => 'We agree confidentiality terms before any non-public information is shared with us.'],
                        ['icon' => 'layers', 'heading' => 'Minimum data necessary', 'text' => 'We ask only for the data needed for the task, and can often work with anonymised or sample data.'],
                        ['icon' => 'users', 'heading' => 'Need-to-know access', 'text' => 'Access is limited to the named people working on your engagement and removed when it ends.'],
                        ['icon' => 'sparkles', 'heading' => 'Governed use of AI', 'text' => 'Confidential or personal information is not entered into public AI tools. Permitted tools and settings are agreed with you in advance.'],
                        ['icon' => 'clock', 'heading' => 'Agreed retention', 'text' => 'We agree how long data is kept and how it is returned or securely deleted at the end of the work.'],
                        ['icon' => 'shield', 'heading' => 'Appropriate safeguards', 'text' => 'We apply technical and organisational measures proportionate to the sensitivity of the information.'],
                    ], 'background' => 'light'],
                    ['type' => 'process', 'eyebrow' => 'In practice', 'heading' => 'How we handle your information.', 'steps' => [
                        ['heading' => 'Before we start', 'text' => 'Sign confidentiality terms, agree what data is needed, where it may be stored and which tools may process it.'],
                        ['heading' => 'During the work', 'text' => 'Work with minimum data, restrict access to named people, keep an audit trail of where information goes and review AI output before use.'],
                        ['heading' => 'When we finish', 'text' => 'Return or securely delete data as agreed, remove access and confirm in writing.'],
                    ], 'background' => 'surface'],
                    ['type' => 'split_content', 'eyebrow' => 'Using this website', 'heading' => 'Please keep sensitive details out of web forms.', 'text' => 'Our contact, consultation and waitlist forms are for introductions only. Please do not include confidential business information, personal financial details or passwords. When an engagement begins, we will agree a secure way to share anything sensitive.', 'image' => self::REFLECTED, 'image_alt' => 'Reflective architecture representing careful handling of information', 'image_side' => 'right', 'points' => [['text' => 'Share context, not confidential data, in a first enquiry'], ['text' => 'Use the secure channel we agree for any files'], ['text' => 'Ask us if you are unsure what is appropriate to send']], 'link_label' => 'Read the Privacy Policy', 'link_url' => '/privacy', 'background' => 'dark'],
                    ['type' => 'callout', 'icon' => 'scale', 'heading' => 'Personal data and legal terms.', 'text' => 'Where we handle personal data, we aim to do so in line with applicable data protection law and our Privacy Policy. This page is a summary of our approach and does not replace the confidentiality and data-processing terms of a signed agreement.', 'link_label' => 'Privacy Policy', 'link_url' => '/privacy', 'background' => 'surface'],
                    ['type' => 'faq', 'eyebrow' => 'Confidentiality FAQ', 'heading' => 'Questions clients ask us.', 'items' => [
                        ['question' => 'Will you sign our non-disclosure agreement?', 'answer' => 'Yes, we are happy to review and sign a reasonable NDA, or provide our own, before any information is shared.'],
                        ['question' => 'Can you work with anonymised or sample data?', 'answer' => 'Often, yes. Starting with anonymised or sample data reduces risk and is a good way to prove an approach before using live data.'],
                        ['question' => 'Do you put our data into AI tools?', 'answer' => 'Only where it has been agreed in advance, using approved tools and settings. Confidential or personal information is not entered into public AI tools.'],
                        ['question' => 'What happens to our data when the engagement ends?', 'answer' => 'We return or securely delete it as agreed, remove access and confirm in writing.'],
                        ['question' => 'Who will see our information?', 'answer' => 'Only the named people working on your engagement. Any subcontractors or tool providers are agreed with you in advance.'],
                    ], 'background' => 'light'],
                    ['type' => 'cta', 'heading' => 'Have specific confidentiality or security requirements?', 'text' => 'Tell us what your organisation requires and we will confirm how we can meet it before any data is shared.', 'button_label' => 'Book a consultation', 'button_url' => '/consultation', 'secondary_label' => 'Contact us', 'secondary_url' => '/contact', 'background' => 'dark'],
                ],
            ],
            'engagement-models' => [
                'title' => 'Working With Us', 'navigation_label' => 'Working with us', 'description' => 'Flexible engagement models, from a short diagnostic to ongoing advisory support.',
                'seo_title' => 'Working with us | Engagement models | InvestAI',
                'seo_description' => 'Choose a diagnostic sprint, a defined project, an advisory retainer or a managed analytics service. Scope and fees agreed in writing first.',
                'blocks' => [
                    ['type' => 'hero', 'size' => 'compact', 'eyebrow' => 'Working with us', 'heading' => 'Engagement models that fit the problem.', 'text' => 'From a short diagnostic to ongoing support, we agree scope, deliverables and fees in writing before work begins.', 'button_label' => 'Book a consultation', 'button_url' => '/consultation', 'image' => self::REFLECTED, 'image_alt' => 'Reflective architecture representing flexible options', 'background' => 'dark'],
                    ['type' => 'service_grid', 'eyebrow' => 'Models', 'heading' => 'Four ways to work together.', 'items' => [
                        ['icon' => 'search', 'heading' => 'Diagnostic sprint', 'text' => 'A short, fixed-scope review that clarifies the problem, options and a recommended next step.'],
                        ['icon' => 'briefcase', 'heading' => 'Project delivery', 'text' => 'A defined scope with agreed deliverables, milestones and acceptance criteria.'],
                        ['icon' => 'chat', 'heading' => 'Advisory retainer', 'text' => 'Regular access to senior input for leaders and teams as questions arise.'],
                        ['icon' => 'workflow', 'heading' => 'Managed analytics', 'text' => 'We run and improve recurring reporting and pipelines, with a planned route to transfer in-house.'],
                    ], 'background' => 'light'],
                    ['type' => 'comparison', 'eyebrow' => 'Choosing a model', 'heading' => 'Which one is right for you?', 'topic_label' => 'Situation', 'left_label' => 'You might be…', 'right_label' => 'A good starting point', 'rows' => [
                        ['topic' => 'Early and unsure', 'left' => 'Aware of a problem but unclear on cause, scope or solution.', 'right' => 'Diagnostic sprint.'],
                        ['topic' => 'Clear scope', 'left' => 'Know what you need built or improved and by when.', 'right' => 'Project delivery.'],
                        ['topic' => 'Ongoing questions', 'left' => 'Have capable teams but want senior sounding-board support.', 'right' => 'Advisory retainer.'],
                        ['topic' => 'Limited capacity', 'left' => 'Need reporting maintained while you build internal skills.', 'right' => 'Managed analytics.'],
                    ], 'background' => 'surface'],
                    ['type' => 'faq', 'eyebrow' => 'Commercials', 'heading' => 'How fees and scoping work.', 'items' => [
                        ['question' => 'How are fees determined?', 'answer' => 'Fees depend on scope, complexity and the people involved. We provide a written proposal with deliverables and fees before work starts, so there are no surprises.'],
                        ['question' => 'Can we start small?', 'answer' => 'Yes. Many clients begin with a diagnostic sprint and decide on next steps with a clear picture of what is involved.'],
                        ['question' => 'Who owns the work?', 'answer' => 'Ownership and licensing of deliverables are set out in the engagement agreement. Our aim is for you to be able to run what we build.'],
                    ], 'background' => 'dark'],
                    ['type' => 'cta', 'heading' => 'Tell us where you are starting from.', 'text' => 'We will recommend the lightest engagement that gets you a useful answer.', 'button_label' => 'Book a consultation', 'button_url' => '/consultation', 'background' => 'surface'],
                ],
            ],
            'contact' => [
                'title' => 'Contact', 'description' => 'Contact the InvestAI team.',
                'blocks' => [
                    ['type' => 'contact_form', 'heading' => 'Start a thoughtful conversation.', 'text' => 'Whether you lead a finance team, run an organisation, advise clients or build technology, tell us what brought you to InvestAI. Please do not include confidential or sensitive financial information in this form.', 'background' => 'light'],
                    ['type' => 'process', 'eyebrow' => 'What happens next', 'heading' => 'A simple, respectful first step.', 'steps' => [
                        ['heading' => 'We read your message', 'text' => 'A person on our team reviews every enquiry.'],
                        ['heading' => 'A short introductory call', 'text' => 'If it looks like a fit, we arrange a call to understand the decision you are facing.'],
                        ['heading' => 'A clear recommendation', 'text' => 'We suggest a sensible next step — which may not involve us — in writing.'],
                    ], 'background' => 'dark'],
                ],
            ],
            'consultation' => [
                'title' => 'Request a Consultation', 'description' => 'Request a callback or introductory conversation with InvestAI.',
                'blocks' => [
                    ['type' => 'consultation_form', 'heading' => 'Let’s find the right conversation.', 'text' => 'Share the topic you would like to discuss and your preferred way to be contacted. This is an introductory conversation about business and analytics consulting — not personalised investment advice.', 'background' => 'light'],
                    ['type' => 'process', 'eyebrow' => 'What to expect', 'heading' => 'From request to recommendation.', 'steps' => [
                        ['heading' => 'Request', 'text' => 'Tell us the topic and how and when to reach you.', 'image' => '/images/editorial/request-desk.jpg', 'image_alt' => 'Open notebook, phone and laptop on a desk'],
                        ['heading' => 'Conversation', 'text' => 'A short, no-obligation call to understand your situation.', 'image' => '/images/editorial/conversation-meeting.jpg', 'image_alt' => 'Two people talking across a table with notes and a tablet'],
                        ['heading' => 'Proposal', 'text' => 'If appropriate, a written proposal with scope, deliverables and fees.', 'image' => '/images/editorial/proposal-writing.jpg', 'image_alt' => 'Fountain pen writing on lined paper'],
                    ], 'background' => 'dark'],
                ],
            ],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function resourcePages(): array
    {
        return [
            'platform' => [
                'title' => 'The InvestAI Platform', 'navigation_label' => 'Platform', 'description' => 'An AI-assisted investment research platform in development, built around personal goals, transparent reasoning and human control.',
                'seo_title' => 'The InvestAI platform | AI-assisted investment research',
                'seo_description' => 'Our AI-assisted investment research platform is in development: personal profiling, transparent reasoning and a staged roadmap.',
                'blocks' => [
                    ['type' => 'hero', 'size' => 'compact', 'eyebrow' => 'Platform in development', 'heading' => 'Investment research built around you.', 'text' => 'We are building a platform that helps people understand their goals, risk preferences and the evidence behind an idea — with the reasoning visible and the decision left to them.', 'button_label' => 'Join the waitlist', 'button_url' => '#waitlist', 'secondary_label' => 'See the roadmap', 'secondary_url' => '#roadmap', 'visual' => 'dashboard', 'background' => 'dark'],
                    ['type' => 'callout', 'icon' => 'scale', 'heading' => 'Please note: the platform is not yet available.', 'text' => 'Everything on this page describes our intended direction, not a currently available service. InvestAI is not currently offering investment advice or managing investments. Any regulated activity will be offered only once the required authorisations are in place. Capital is at risk when investing.', 'link_label' => 'Read the financial disclaimer', 'link_url' => '/financial-disclaimer', 'background' => 'surface'],
                    ['type' => 'comparison', 'eyebrow' => 'Why a new approach', 'heading' => 'Making good guidance easier to reach.', 'intro' => 'Professional advice is valuable, but cost, access and clarity can be barriers — particularly for people with modest sums to invest.', 'topic_label' => 'Challenge', 'left_label' => 'Today, for many people', 'right_label' => 'What we aim to improve', 'rows' => [
                        ['topic' => 'Cost', 'left' => 'Advice and management fees can be hard to justify on smaller portfolios.', 'right' => 'Lower-cost, online access to structured research and education.'],
                        ['topic' => 'Access', 'left' => 'Advisers are not always nearby or available when questions arise.', 'right' => 'Guidance available from any device, at any time.'],
                        ['topic' => 'Clarity', 'left' => 'Fees, assumptions and potential conflicts are not always easy to see.', 'right' => 'Transparent reasoning, visible assumptions and clear disclosure.'],
                        ['topic' => 'Consistency', 'left' => 'Quality and approach vary between advisers.', 'right' => 'A documented, consistently applied method — tested for bias and reviewed by people.'],
                        ['topic' => 'Control', 'left' => 'Some people want more involvement than they are offered.', 'right' => 'You choose how involved to be and how you receive updates.'],
                    ], 'background' => 'light'],
                    ['type' => 'feature_cards', 'heading' => 'Starting with a conversation about you.', 'intro' => 'The platform is designed to begin with a short profile, so that research is framed around your circumstances.', 'items' => [
                        ['icon' => 'target', 'heading' => 'Goals & timeline', 'text' => 'What you are saving or investing for, and when you may need the money.'],
                        ['icon' => 'scale', 'heading' => 'Risk tolerance', 'text' => 'How much fluctuation you could accept, and whether you prefer safety or higher potential returns.'],
                        ['icon' => 'banknote', 'heading' => 'Financial context', 'text' => 'Income and expense patterns, obligations and tax considerations.'],
                        ['icon' => 'leaf', 'heading' => 'Values & preferences', 'text' => 'Interest in sustainable or socially responsible approaches, and active or passive styles.'],
                        ['icon' => 'lightbulb', 'heading' => 'Experience', 'text' => 'Your familiarity with investing, so explanations are pitched at the right level.'],
                        ['icon' => 'chat', 'heading' => 'Involvement & updates', 'text' => 'How hands-on you want to be and how you prefer to receive information.'],
                    ], 'background' => 'surface'],
                    ['type' => 'split_content', 'eyebrow' => 'Responsible by design', 'heading' => 'AI that explains itself.', 'text' => 'We use large language models to organise information and explore scenarios, not to claim certainty. Outputs are meant to be traceable to sources, accompanied by stated limitations, and reviewed by people before they are relied upon.', 'image' => self::GLASS, 'image_alt' => 'Transparent architecture representing explainable AI', 'image_side' => 'right', 'points' => [['text' => 'Sources and assumptions shown alongside conclusions'], ['text' => 'Human oversight and testing for bias and error'], ['text' => 'We will not hold client money in our own accounts as part of our initial plans'], ['text' => 'Education first; regulated services only with permission']], 'link_label' => 'Our technology principles', 'link_url' => '/technology', 'background' => 'dark'],
                    ['type' => 'timeline', 'eyebrow' => 'Roadmap', 'heading' => 'Four phases, delivered carefully.', 'intro' => 'We are building in stages so that each step can be tested, learned from and, where required, authorised before moving on.', 'items' => [
                        ['phase' => 'Phase 1', 'icon' => 'search', 'heading' => 'Research & MVP', 'text' => 'Market research and analysis, then development of a minimum viable product to test feasibility and core features.', 'status' => 'active'],
                        ['phase' => 'Phase 2', 'icon' => 'users', 'heading' => 'Beta testing', 'text' => 'A small group of testers uses the platform and gives structured feedback on function and experience.', 'status' => 'planned'],
                        ['phase' => 'Phase 3', 'icon' => 'flag', 'heading' => 'Public launch', 'text' => 'Launch with a high-quality experience and continuous improvement, subject to regulatory readiness.', 'status' => 'planned'],
                        ['phase' => 'Phase 4', 'icon' => 'globe', 'heading' => 'Expansion', 'text' => 'New markets, new features and, over time, proprietary models tailored to our users’ needs.', 'status' => 'planned'],
                    ], 'note' => 'The roadmap is indicative and may change as we learn. Dates are not committed.', 'background' => 'light'],
                    ['type' => 'waitlist_form', 'eyebrow' => 'Early access', 'heading' => 'Be among the first to hear.', 'text' => 'Join the waitlist for development updates and, in due course, an invitation to beta testing. We will only use your details for this purpose.', 'background' => 'surface'],
                    ['type' => 'faq', 'eyebrow' => 'Platform FAQ', 'heading' => 'What people ask about the platform.', 'items' => [
                        ['question' => 'Is the platform live?', 'answer' => 'No. It is in development. This page describes our intended direction, and no investment service is currently available.'],
                        ['question' => 'Will it give me personal investment advice?', 'answer' => 'We will provide personalised advice only if and when we hold the appropriate authorisation. Until then, any content is general education and information.'],
                        ['question' => 'Will you hold my money?', 'answer' => 'Our initial plans do not involve holding client investment funds in our own accounts.'],
                        ['question' => 'Which AI does it use?', 'answer' => 'We use large language models and will choose and test providers on accuracy, security and suitability, moving to newer models only when they are demonstrably better.'],
                    ], 'background' => 'dark'],
                ],
            ],
            'ai-tools' => [
                'title' => 'AI & Analytics Tools', 'navigation_label' => 'AI tools', 'description' => 'Free, private self-service tools: an analytics and AI readiness check and a reporting time estimator.',
                'seo_title' => 'AI & analytics tools | Readiness check and estimator | InvestAI',
                'seo_description' => 'Check your analytics and AI readiness and estimate the time spent on manual reporting. Free, private and run in your browser.',
                'blocks' => [
                    ['type' => 'hero', 'size' => 'compact', 'eyebrow' => 'Self-service tools', 'heading' => 'See where you stand in minutes.', 'text' => 'Three quick tools to help you think about analytics, AI, automation and financial risk. They run in your browser and nothing you enter is sent to us; the one optional AI feature asks for your consent first.', 'button_label' => 'Take the readiness check', 'button_url' => '#readiness', 'secondary_label' => 'Try the risk calculator', 'secondary_url' => '#risk', 'visual' => 'dashboard', 'background' => 'dark'],
                    ['type' => 'readiness_assessment', 'eyebrow' => 'Tool 1', 'heading' => 'Analytics and AI readiness check', 'intro' => 'Eight questions across strategy, data, governance and automation. You will see an overall level, a breakdown and suggested next steps.', 'background' => 'surface'],
                    ['type' => 'savings_estimator', 'eyebrow' => 'Tool 2', 'heading' => 'Reporting time estimator', 'intro' => 'Estimate what recurring manual reporting costs today and what a realistic share of automation could free up.', 'background' => 'light'],
                    ['type' => 'risk_calculator', 'eyebrow' => 'Tool 3', 'heading' => 'Financial risk calculator', 'intro' => 'Explore how a mix of assets could behave in a good year and a bad one. Use it without AI for transparent arithmetic, or switch on AI for a plain-English explanation of the same results.', 'background' => 'surface'],
                    ['type' => 'callout', 'icon' => 'shield', 'heading' => 'How these tools work.', 'text' => 'The readiness check, savings estimator and the “without AI” risk calculator use simple, transparent rules and arithmetic that run in your browser and send nothing anywhere. Only if you choose “With AI” and tick the consent box are the percentages you entered (never your name, contact details or amount) sent to an external AI service to write the explanation. Results are indicative and intended to start a conversation, not to replace professional assessment.', 'background' => 'dark'],
                    ['type' => 'cta', 'heading' => 'Want help interpreting your results?', 'text' => 'We can walk through them with you and suggest a practical first step.', 'button_label' => 'Book a consultation', 'button_url' => '/consultation', 'secondary_label' => 'View services', 'secondary_url' => '/services', 'background' => 'surface'],
                ],
            ],
            'faq' => [
                'title' => 'Frequently Asked Questions', 'navigation_label' => 'FAQ', 'description' => 'Answers about our consulting, use of AI, data protection and the InvestAI platform.',
                'seo_title' => 'FAQ | InvestAI',
                'seo_description' => 'Answers to common questions about InvestAI’s consulting services, use of AI, data protection and platform.',
                'blocks' => [
                    ['type' => 'hero', 'size' => 'compact', 'eyebrow' => 'FAQ', 'heading' => 'Questions, answered plainly.', 'text' => 'If you cannot find what you need here, we are happy to talk.', 'button_label' => 'Ask us a question', 'button_url' => '/contact', 'background' => 'dark'],
                    ['type' => 'faq', 'eyebrow' => 'Consulting', 'heading' => 'About our services.', 'open_first' => true, 'items' => [
                        ['question' => 'What kinds of organisations do you work with?', 'answer' => 'Financial services firms, fintechs, growing businesses, non-profits and public-sector bodies — typically organisations where finance, accountability and evidence are central to decision-making.'],
                        ['question' => 'Do you only work on finance problems?', 'answer' => 'Our lens is finance-led, but the work often covers operations, performance and data more broadly. We will tell you if a problem is outside our strengths.'],
                        ['question' => 'How long do engagements last?', 'answer' => 'From a two-week diagnostic to multi-month projects or ongoing support. We agree the scope and timeline in writing before starting.'],
                        ['question' => 'Do you build things, or only advise?', 'answer' => 'Both. We design and build analytics, dashboards, models and automations, and we train your team to run them.'],
                    ], 'background' => 'surface'],
                    ['type' => 'faq', 'eyebrow' => 'AI & data', 'heading' => 'About AI and your information.', 'items' => [
                        ['question' => 'How do you use AI?', 'answer' => 'To draft, summarise, classify and explore options faster. All AI-assisted output is reviewed by a person before it is used or shared.'],
                        ['question' => 'Will you put our confidential data into public AI tools?', 'answer' => 'No, not without an agreed, appropriate arrangement. We agree permitted tools, data handling and retention with you in advance.'],
                        ['question' => 'Can AI make mistakes?', 'answer' => 'Yes. Language models can produce fluent but incorrect statements. That is why we require source checking, human review and clear statements of limitations.'],
                        ['question' => 'How is my data protected?', 'answer' => 'We sign confidentiality terms before receiving data, use the minimum necessary, restrict access and agree where data may be stored and processed.'],
                    ], 'background' => 'light'],
                    ['type' => 'faq', 'eyebrow' => 'Platform & regulation', 'heading' => 'About the InvestAI platform.', 'items' => [
                        ['question' => 'Are you regulated to give investment advice?', 'answer' => 'Our consulting work is not regulated investment advice. The platform is in development, and any regulated activity will be offered only once the required authorisations are in place.'],
                        ['question' => 'Can I invest through InvestAI today?', 'answer' => 'No. There is currently no investment service. You can join the waitlist for updates.'],
                        ['question' => 'Is anything on this site a recommendation?', 'answer' => 'No. Content is general information and education, not a recommendation or offer to buy or sell any financial product.'],
                    ], 'background' => 'dark'],
                    ['type' => 'cta', 'heading' => 'Still have a question?', 'text' => 'We are happy to talk it through.', 'button_label' => 'Contact us', 'button_url' => '/contact', 'secondary_label' => 'Book a consultation', 'secondary_url' => '/consultation', 'background' => 'surface'],
                ],
            ],
        ];
    }
}
