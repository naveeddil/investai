<?php

namespace Database\Seeders;

use App\Enums\PageStatus;
use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ProductWebsiteSeeder extends Seeder
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
                'is_system_page' => $slug === 'home',
                'deletion_protected' => $slug === 'home',
                'index_by_search_engines' => true,
                'robots_follow' => true,
                'published_at' => now(),
            ]);
        }
    }

    private function pages(): array
    {
        return [
            'home' => [
                'title' => 'InvestAI',
                'description' => 'AI-assisted investment research designed around your goals, time horizon and tolerance for risk.',
                'page_type' => 'home',
                'seo_title' => 'InvestAI | Invest smarter with transparent AI-assisted research',
                'seo_description' => 'Explore investment goals, risk and long-term scenarios with transparent AI-assisted research developed through nearly three years of work.',
                'blocks' => [
                    ['type' => 'hero', 'eyebrow' => 'Invest smarter with AI', 'heading' => 'Investment research that starts with you.', 'text' => 'InvestAI is building an accessible platform that brings together your goals, time horizon, experience and tolerance for loss—then uses data and AI to explain investment research in plain English.', 'button_label' => 'Explore the product', 'button_url' => '/platform', 'secondary_label' => 'Try the calculators', 'secondary_url' => '/ai-tools', 'visual' => 'dashboard', 'highlights' => [['text' => 'Personal goals'], ['text' => 'Risk-aware scenarios'], ['text' => 'Transparent reasoning'], ['text' => 'Human oversight']], 'background' => 'dark'],
                    ['type' => 'callout', 'icon' => 'scale', 'heading' => 'Research and education—not investment advice.', 'text' => 'InvestAI has been developed over nearly three years. It is not currently offering personalised investment advice, arranging investments or managing client money. The tools and content on this website are educational illustrations. Capital is at risk when investing.', 'link_label' => 'Read the financial disclaimer', 'link_url' => '/financial-disclaimer', 'background' => 'surface'],
                    ['type' => 'feature_cards', 'heading' => 'A clearer path from goals to research.', 'intro' => 'The product concept follows the business plan: understand the person first, analyse evidence second, and keep the reasoning visible.', 'items' => [
                        ['icon' => 'target', 'heading' => 'Build your profile', 'text' => 'Capture goals, timeline, income and commitments, experience, tax context and preferences.'],
                        ['icon' => 'scale', 'heading' => 'Understand risk', 'text' => 'Separate willingness to take risk from the financial capacity to withstand a loss.'],
                        ['icon' => 'search', 'heading' => 'Explore the evidence', 'text' => 'Use structured research and scenario analysis to understand possible outcomes and limitations.'],
                        ['icon' => 'chat', 'heading' => 'Stay informed', 'text' => 'Receive plain-English explanations and review prompts while keeping control of the decision.'],
                    ], 'background' => 'light'],
                    ['type' => 'service_grid', 'eyebrow' => 'Explore now', 'heading' => 'Put InvestAI’s research approach to work.', 'items' => [
                        ['icon' => 'scale', 'heading' => 'Portfolio risk analysis', 'text' => 'Stress-test an illustrative asset mix against your stated tolerance for a bad year.', 'link_label' => 'Analyse risk', 'link_url' => '/ai-tools#risk'],
                        ['icon' => 'calculator', 'heading' => 'Growth & fee calculator', 'text' => 'See how contributions, assumed returns and annual charges compound in pounds.', 'link_label' => 'Run a scenario', 'link_url' => '/ai-tools#growth-calculator'],
                        ['icon' => 'document', 'heading' => 'Insights', 'text' => 'Read educational perspectives on risk, evidence and disciplined investing.', 'link_label' => 'Read insights', 'link_url' => '/insights'],
                    ], 'background' => 'surface'],
                    ['type' => 'timeline', 'eyebrow' => 'Product evolution', 'heading' => 'Nearly three years of disciplined development.', 'items' => [
                        ['phase' => 'Foundation', 'icon' => 'search', 'heading' => 'Research & product model', 'text' => 'Define the market need, investor profile and transparent research approach.', 'status' => 'none'],
                        ['phase' => 'Validation', 'icon' => 'users', 'heading' => 'Testing & refinement', 'text' => 'Test functionality, assumptions and the user experience through structured feedback.', 'status' => 'none'],
                        ['phase' => 'Current focus', 'icon' => 'flag', 'heading' => 'Practical tools & consultation', 'text' => 'Make risk analysis, fee illustrations and research conversations accessible today.', 'status' => 'active'],
                        ['phase' => 'Continuing', 'icon' => 'globe', 'heading' => 'Responsible expansion', 'text' => 'Extend capabilities and markets in step with evidence and regulatory permissions.', 'status' => 'planned'],
                    ], 'note' => 'InvestAI continues to evolve as research, technology and regulatory requirements change.', 'background' => 'dark'],
                    ['type' => 'latest_insights', 'eyebrow' => 'Insights', 'heading' => 'Research habits for better decisions.', 'intro' => 'Educational perspectives on evidence, risk and long-term investing.', 'background' => 'light'],
                    ['type' => 'cta', 'heading' => 'Discuss your investment research needs.', 'text' => 'Arrange a consultation to explore your goals, risk questions and how InvestAI may support your research process.', 'button_label' => 'Consult with InvestAI', 'button_url' => '/consultation?topic=platform', 'secondary_label' => 'About InvestAI', 'secondary_url' => '/about', 'background' => 'surface'],
                ],
            ],
            'platform' => [
                'title' => 'The InvestAI Product', 'description' => 'The product vision, personal profile, safeguards and roadmap for InvestAI.',
                'seo_title' => 'The product | InvestAI', 'seo_description' => 'See how InvestAI plans to make investment research more personal, accessible and transparent.',
                'blocks' => [
                    ['type' => 'hero', 'size' => 'compact', 'eyebrow' => 'Nearly three years of focused development', 'heading' => 'Personalised investment research, explained clearly.', 'text' => 'InvestAI combines a detailed investor profile with data analysis and AI-assisted explanations, helping users explore investment questions without hiding assumptions or uncertainty.', 'button_label' => 'Request a consultation', 'button_url' => '/consultation?topic=platform', 'secondary_label' => 'Try the tools', 'secondary_url' => '/ai-tools', 'visual' => 'dashboard', 'background' => 'dark'],
                    ['type' => 'feature_cards', 'heading' => 'Your profile shapes the research.', 'intro' => 'The source business plan identifies the information needed to frame useful, responsible analysis.', 'items' => [
                        ['icon' => 'target', 'heading' => 'Goals & timeline', 'text' => 'What the money is for and when it may be needed.'], ['icon' => 'scale', 'heading' => 'Risk & loss tolerance', 'text' => 'Comfort with market swings and ability to absorb loss.'], ['icon' => 'banknote', 'heading' => 'Financial context', 'text' => 'Income, expenses, liabilities, tax context and other obligations.'], ['icon' => 'document', 'heading' => 'Experience', 'text' => 'Existing knowledge and products used, so explanations fit the user.'], ['icon' => 'leaf', 'heading' => 'Values & preferences', 'text' => 'Sustainable investing and active or passive preferences.'], ['icon' => 'chat', 'heading' => 'Control & updates', 'text' => 'Preferred involvement and how information should be delivered.'],
                    ], 'background' => 'light'],
                    ['type' => 'comparison', 'eyebrow' => 'The opportunity', 'heading' => 'Designed to reduce common barriers.', 'topic_label' => 'Barrier', 'left_label' => 'What can make investing difficult', 'right_label' => 'What InvestAI provides', 'rows' => [
                        ['topic' => 'Access', 'left' => 'Traditional support can be unavailable or uneconomic for modest portfolios.', 'right' => 'Online access from any device, designed for a broad audience.'], ['topic' => 'Clarity', 'left' => 'Fees, assumptions and conflicts can be hard to understand.', 'right' => 'Visible assumptions, charges and limitations in plain language.'], ['topic' => 'Consistency', 'left' => 'The quality and method of guidance can vary.', 'right' => 'A documented process with repeatable checks and human oversight.'], ['topic' => 'Control', 'left' => 'Users may not understand why a conclusion was reached.', 'right' => 'Traceable reasoning and user control over the final decision.'],
                    ], 'background' => 'surface'],
                    ['type' => 'callout', 'icon' => 'shield', 'heading' => 'Regulatory clarity remains essential.', 'text' => 'Any regulated UK service will be offered only with the required permissions. InvestAI does not currently hold client investment funds, and the scope of every consultation is made clear at the outset.', 'link_label' => 'Financial disclaimer', 'link_url' => '/financial-disclaimer', 'background' => 'dark'],
                    ['type' => 'consultation_form', 'heading' => 'Consult with InvestAI.', 'text' => 'Tell us what you would like to understand—your goals, tolerance for risk, a portfolio scenario or the research process. Please do not include account numbers, passwords or other sensitive financial information.', 'background' => 'surface'],
                ],
            ],
            'approach' => [
                'title' => 'How InvestAI Works', 'description' => 'How the planned InvestAI journey moves from personal profile to transparent, risk-aware research.',
                'seo_title' => 'How it works | InvestAI', 'seo_description' => 'See the planned InvestAI journey from goals and risk profile to evidence, scenarios and ongoing review.',
                'blocks' => [
                    ['type' => 'hero', 'size' => 'compact', 'eyebrow' => 'How it works', 'heading' => 'Profile first. Evidence next. Decision stays yours.', 'text' => 'InvestAI is designed to gather relevant context, analyse information at scale and explain the result—without pretending that markets are predictable or risk can be removed.', 'button_label' => 'Try the risk tools', 'button_url' => '/ai-tools', 'background' => 'dark'],
                    ['type' => 'process', 'eyebrow' => 'The InvestAI journey', 'heading' => 'Four steps to a better-informed decision.', 'steps' => [
                        ['heading' => 'Understand you', 'text' => 'Capture goals, horizon, finances, experience, values and preferences.'], ['heading' => 'Assess risk', 'text' => 'Compare tolerance for loss with the risk in an illustrative portfolio.'], ['heading' => 'Analyse evidence', 'text' => 'Review financial data, research and market information, with sources and assumptions visible.'], ['heading' => 'Explain & review', 'text' => 'Present findings in plain English and revisit them as circumstances or evidence change.'],
                    ], 'background' => 'surface'],
                    ['type' => 'feature_cards', 'heading' => 'Responsible by design.', 'items' => [
                        ['icon' => 'search', 'heading' => 'Traceable evidence', 'text' => 'Conclusions should link back to sources and declared assumptions.'], ['icon' => 'users', 'heading' => 'Human oversight', 'text' => 'AI assists with analysis and explanation; people remain accountable.'], ['icon' => 'shield', 'heading' => 'Risk made visible', 'text' => 'Downside scenarios and model limitations sit alongside potential returns.'], ['icon' => 'lock', 'heading' => 'Data minimisation', 'text' => 'Collect only what is necessary and protect sensitive personal information.'],
                    ], 'background' => 'light'],
                    ['type' => 'cta', 'heading' => 'Explore the numbers yourself.', 'text' => 'Use the educational calculators to test a portfolio mix and see the long-term impact of fees.', 'button_label' => 'Open risk & calculators', 'button_url' => '/ai-tools', 'background' => 'dark'],
                ],
            ],
            'ai-tools' => [
                'title' => 'Risk & Investment Calculators', 'description' => 'Free educational tools for portfolio risk, investment growth and the long-term impact of fees—all in pounds.',
                'seo_title' => 'Risk analysis & investment calculators | InvestAI', 'seo_description' => 'Explore portfolio downside, investment growth and fee impact with transparent, pound-denominated educational calculators.',
                'blocks' => [
                    ['type' => 'hero', 'size' => 'compact', 'eyebrow' => 'Educational tools', 'heading' => 'Make risk and compounding easier to see.', 'text' => 'Run transparent illustrations in pounds. The calculators use simplified assumptions, run in your browser and do not provide a recommendation.', 'button_label' => 'Analyse portfolio risk', 'button_url' => '#risk', 'secondary_label' => 'Calculate growth & fees', 'secondary_url' => '#growth-calculator', 'visual' => 'dashboard', 'background' => 'dark'],
                    ['type' => 'feature_cards', 'heading' => 'Risk is more than a score.', 'intro' => 'A useful assessment considers several parts of your circumstances before any investment conclusion.', 'items' => [
                        ['icon' => 'target', 'heading' => 'Goal risk', 'text' => 'What happens if the money is not available when the goal arrives?'], ['icon' => 'clock', 'heading' => 'Time-horizon risk', 'text' => 'Shorter horizons leave less time to recover from a market fall.'], ['icon' => 'banknote', 'heading' => 'Capacity for loss', 'text' => 'How much loss your finances can withstand—not just how much feels comfortable.'], ['icon' => 'scale', 'heading' => 'Portfolio risk', 'text' => 'Concentration, volatility and how assets may move together under stress.'],
                    ], 'background' => 'light'],
                    ['type' => 'risk_calculator', 'eyebrow' => 'Tool 1', 'heading' => 'Portfolio risk & loss analysis', 'intro' => 'Test an illustrative asset mix against your time horizon and the largest one-year fall you believe you could tolerate.', 'background' => 'surface'],
                    ['type' => 'investment_calculator', 'eyebrow' => 'Tool 2', 'heading' => 'Investment growth & fee calculator', 'intro' => 'See how a starting amount, monthly contributions, assumed return and annual charges could compound over time.', 'background' => 'light'],
                    ['type' => 'callout', 'icon' => 'shield', 'heading' => 'Important limitations.', 'text' => 'These are simplified educational models, not forecasts or personalised advice. They do not include inflation, tax, product rules, liquidity needs or all market risks. Actual returns can be lower and capital can be lost.', 'link_label' => 'Read the full disclaimer', 'link_url' => '/financial-disclaimer', 'background' => 'dark'],
                ],
            ],
            'faq' => [
                'title' => 'Frequently Asked Questions', 'description' => 'Answers about the InvestAI product, tools, AI, risk and regulatory status.',
                'seo_title' => 'FAQ | InvestAI', 'seo_description' => 'Answers about InvestAI, its planned product, educational calculators, use of AI and regulatory status.',
                'blocks' => [
                    ['type' => 'hero', 'size' => 'compact', 'eyebrow' => 'FAQ', 'heading' => 'Clear answers about InvestAI.', 'text' => 'What exists today, what is planned and what the educational tools can—and cannot—tell you.', 'button_label' => 'Explore the product', 'button_url' => '/platform', 'background' => 'dark'],
                    ['type' => 'faq', 'eyebrow' => 'Product & tools', 'heading' => 'The essentials.', 'open_first' => true, 'items' => [
                        ['question' => 'How established is InvestAI?', 'answer' => 'InvestAI reflects nearly three years of product, finance and research work. The website provides established educational tools and explains the service approach; regulated services are offered only where the required permissions are in place.'],
                        ['question' => 'Does InvestAI currently provide investment advice?', 'answer' => 'InvestAI currently provides educational information and research-oriented consultations, not regulated personalised investment advice, investment arranging or client-money management.'],
                        ['question' => 'What do the calculators show?', 'answer' => 'They illustrate portfolio volatility and downside scenarios, and how contributions, assumed returns and fees can compound. They are simplified educational models, not forecasts or recommendations.'],
                        ['question' => 'Why are all values shown in pounds?', 'answer' => 'The initial product and business plan are UK-focused, so the website uses pounds sterling throughout its monetary illustrations.'],
                        ['question' => 'How will AI be used?', 'answer' => 'The plan is to use AI to organise and analyse information and explain research in plain English. Sources, assumptions and limitations should remain visible, with human oversight for consequential decisions.'],
                        ['question' => 'Will InvestAI hold client money?', 'answer' => 'The initial plan does not involve InvestAI holding client investment funds. Product scope may evolve only with appropriate safeguards and regulatory permissions.'],
                    ], 'background' => 'surface'],
                    ['type' => 'cta', 'heading' => 'Still have a question?', 'text' => 'Contact us without including sensitive personal or financial information.', 'button_label' => 'Contact us', 'button_url' => '/contact', 'background' => 'dark'],
                ],
            ],
            'about' => [
                'title' => 'About InvestAI', 'description' => 'The purpose, values and founder behind InvestAI.',
                'seo_title' => 'About | InvestAI', 'seo_description' => 'InvestAI was founded by finance professional Fahim Ahmed to make investment research more accessible, personal and transparent.',
                'blocks' => [
                    ['type' => 'hero', 'size' => 'compact', 'eyebrow' => 'About InvestAI', 'heading' => 'Finance experience meets responsible technology.', 'text' => 'InvestAI was founded to make structured investment research easier to access and understand, using AI as an analytical assistant while keeping transparency, integrity and personal responsibility at the centre.', 'button_label' => 'See the product vision', 'button_url' => '/platform', 'background' => 'dark'],
                    ['type' => 'split_content', 'eyebrow' => 'Founder', 'heading' => 'Fahim Ahmed', 'text' => 'Fahim brings more than 15 years of finance experience, including roles with the World Health Organization, Save the Children, Catholic Relief Services and the Anti-Narcotics Force. His background includes commerce, an MBA and internal-audit certification.', 'image' => '/images/editorial/financial-district.jpg', 'image_alt' => 'Financial district representing the founder’s finance background', 'image_side' => 'right', 'points' => [['text' => 'Finance, budgeting and operational leadership'], ['text' => 'Experience across international and public-interest organisations'], ['text' => 'Focus on accessible, evidence-led financial decision-making']], 'background' => 'surface'],
                    ['type' => 'feature_cards', 'heading' => 'Values that guide the build.', 'items' => [
                        ['icon' => 'shield', 'heading' => 'Integrity', 'text' => 'Honesty, trust, commitment and personal responsibility.'], ['icon' => 'target', 'heading' => 'Accountability', 'text' => 'Goals, ownership and measurable progress.'], ['icon' => 'lightbulb', 'heading' => 'Innovation', 'text' => 'Creativity used to make finance more accessible and useful.'], ['icon' => 'users', 'heading' => 'People first', 'text' => 'Teamwork, diversity, courage, curiosity and leadership.'],
                    ], 'background' => 'light'],
                    ['type' => 'cta', 'heading' => 'Interested in InvestAI?', 'text' => 'Arrange a consultation about the research approach, tools or a potential partnership.', 'button_label' => 'Consult with InvestAI', 'button_url' => '/consultation?topic=platform', 'secondary_label' => 'Contact us', 'secondary_url' => '/contact', 'background' => 'dark'],
                ],
            ],
        ];
    }
}
