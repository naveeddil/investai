<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ResearchTipsSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            $this->article(
                'Main reasons to explain a fast business builder',
                'main-reasons-to-explain-fast-business-builder',
                'Fast growth is most useful when it is supported by evidence, cash discipline and repeatable operating systems.',
                <<<'BODY'
Fast business growth can look attractive, but speed is not a strategy by itself. A credible growth plan explains why demand should increase, how the business will serve that demand and what evidence would show that the plan is working. The strongest plans connect a clear customer problem to a repeatable way of creating value.

Start with the constraint

Identify the one constraint most likely to prevent progress: customer acquisition, conversion, delivery capacity, working capital, regulation or management attention. Solving the wrong constraint can increase activity without improving the business. Use customer interviews, sales data and operational records to test the diagnosis.

Track useful evidence

Monitor a small set of measures that connect activity to economics. These may include qualified enquiries, conversion rate, customer acquisition cost, gross margin, retention, cash collection time and capacity utilisation. Compare actual results with a written baseline instead of relying on impressions.

Protect cash while testing

Growth often consumes cash before it produces cash. Model a base case, a slower-sales case and a higher-cost case. State how much money each experiment can use, when it will be reviewed and what result would justify further investment. Small reversible tests usually reveal more than one large commitment.

Research tip

Keep a decision log containing the assumption, evidence, owner, review date and stop condition for every major growth initiative. This makes it easier to distinguish a weak idea from weak execution and prevents sunk costs from deciding what happens next.
BODY,
                'request-desk.jpg',
                'A desk prepared for evidence-led business planning',
                '2020/07/29/main-reasons-to-explain-fast-business-builder',
            ),
            $this->article(
                'Better business management starts with better decisions',
                'we-deal-with-best-business-managment',
                'A practical management rhythm turns goals into accountable decisions, measurable actions and timely learning.',
                <<<'BODY'
Good business management is less about producing more reports and more about making responsibilities, evidence and trade-offs visible. A useful operating rhythm connects long-term priorities with the decisions teams make each week.

Translate goals into operating questions

Replace broad ambitions such as “grow revenue” with questions that can be investigated: Which customer segment has the strongest retention? Which service produces a healthy contribution margin? Where does work wait longest? This creates a direct line between strategy and daily evidence.

Assign one accountable owner

Every material action should have one named owner, a due date and a definition of completion. Contributors can be many, but accountability should be clear. Review blocked actions early and distinguish a lack of capacity from a lack of clarity.

Use a balanced dashboard

Combine financial measures—cash runway, margin, receivables and forecast variance—with customer and operational measures such as retention, complaints, delivery time and quality. A single headline number rarely explains whether performance is sustainable.

Create a decision cadence

Weekly reviews should surface exceptions and immediate constraints. Monthly reviews should compare actual performance with the plan and revise assumptions. Quarterly reviews should ask whether priorities and resource allocation remain appropriate.

Research tip

Record the evidence available when a decision is made. Later, assess the quality of the process separately from the outcome. This reduces hindsight bias and helps management learn rather than merely explain results after the event.
BODY,
                'conversation-meeting.jpg',
                'A structured management discussion around a meeting table',
                '2020/07/29/we-deal-with-best-business-managment',
            ),
            $this->article(
                'A seven-step social media marketing strategy',
                '7-step-social-media-marketing-strategy',
                'Build an evidence-led social strategy around audience needs, useful content, responsible claims and measurable outcomes.',
                <<<'BODY'
Social media works best when it supports a defined business objective rather than becoming an endless publishing task. In financial and professional services, accuracy, trust and responsible communication matter as much as reach.

1. Define the outcome

Choose one primary objective for the campaign: awareness among a defined audience, qualified enquiries, event registrations or useful customer education. Select measures that reflect that objective rather than treating impressions as success.

2. Research the audience

Use customer questions, search behaviour, service enquiries and interviews to identify genuine information needs. Document what the audience already understands, what causes confusion and what action would be appropriate after reading.

3. Select channels deliberately

Choose platforms based on audience behaviour and content fit. It is usually better to maintain one credible channel consistently than several weak ones.

4. Build useful content themes

Create three or four recurring themes, such as research methods, risk awareness, practical checklists and market education. Each post should answer one clear question.

5. Apply an editorial and compliance check

Verify statistics against primary sources, date time-sensitive statements, disclose relevant interests and avoid promises about results. Keep evidence and approval records for material claims.

6. Publish, engage and listen

Use a sustainable calendar, respond professionally and turn recurring questions into future content. Never request sensitive financial details in public comments.

7. Measure and improve

Review qualified actions, saves, useful replies and website journeys alongside reach. Compare content themes over a meaningful period and change one variable at a time.

Research tip

Maintain a source sheet for each post with the original link, publication date, claim supported and date checked. This makes updates faster and reduces the risk of repeating an outdated figure.
BODY,
                'proposal-writing.jpg',
                'A documented seven-step marketing plan',
                '2020/07/29/7-step-social-media-marketing-strategy',
            ),
            $this->article(
                'Success is not final; failure is not fatal',
                'success-is-not-final-failure-is-not-fatal',
                'Separate the quality of a decision from one outcome, then use documented evidence to improve the next decision.',
                <<<'BODY'
One result rarely proves that a business or investment process is sound. A positive outcome can contain luck, while a disciplined decision can still produce a loss because uncertainty cannot be removed. Sustainable progress comes from learning without becoming overconfident after success or abandoning a good process after disappointment.

Review the decision as it was made

Return to the original objective, evidence, assumptions and alternatives. Ask whether the decision matched the available information and acceptable risk at that time. Avoid judging it only with facts that became known later.

Separate process, outcome and luck

Assess whether reliable sources were used, contrary evidence was considered, costs were understood and limits were defined. Then review the result. Naming the role of uncertainty produces a more honest explanation than labelling every gain as skill and every loss as failure.

Use review triggers

Define in advance what would require action: a broken assumption, a material change in finances, an unacceptable concentration or evidence that the original thesis no longer holds. Price movement alone may not explain what changed.

Improve one element at a time

After a review, identify the smallest useful process improvement—better source verification, clearer downside testing or a stronger approval step. Too many simultaneous changes make learning difficult.

Research tip

Write a short pre-mortem before important decisions: imagine the decision has failed and list plausible reasons. Revisit that list during the review. It often reveals risks that optimistic planning overlooks.
BODY,
                'reflected-architecture.jpg',
                'Reflections representing learning from success and failure',
                '2020/07/29/success-is-not-final-failure-is-not-fatal',
            ),
            $this->article(
                'Uplifting the market value of finance',
                'uplifting-the-market-value-of-finance',
                'Finance creates more value when it improves decisions, explains uncertainty and directs resources toward durable outcomes.',
                <<<'BODY'
Finance should do more than report what has already happened. Used well, it helps a business understand which activities create value, where cash is constrained and which risks deserve attention before resources are committed.

Connect value to operating drivers

Break headline performance into understandable drivers such as price, volume, retention, margin, working capital and capacity. This allows managers to see which changes are repeatable and which may be temporary.

Improve forecast transparency

Use ranges and scenarios instead of presenting one precise forecast as certain. Record the assumptions behind demand, costs, timing and financing. Compare forecasts with actual results and investigate systematic bias.

Make capital allocation explicit

For each significant proposal, state the expected benefit, cost, timing, downside case and alternative use of funds. Include non-financial constraints such as regulation, reputation, execution capacity and environmental or social impact where relevant.

Communicate uncertainty clearly

Decision-makers need to know which figures are observed, estimated or highly uncertain. Clear labels and sensitivity analysis are more valuable than false precision.

Research tip

Trace every important figure back to its source and calculation. Reconcile management measures with accounting records, disclose exclusions and compare like with like. Better provenance improves confidence in both the number and the decision built on it.
BODY,
                'financial-district.jpg',
                'A financial district representing value-led finance',
                '2020/07/29/uplifting-the-market-value-of-finance',
            ),
            $this->article(
                'Turn market headlines into better research questions',
                'turn-market-headlines-into-research-questions',
                'Use a repeatable source-and-evidence checklist before allowing urgent news to influence a long-term decision.',
                <<<'BODY'
A market headline is designed to attract attention; it is not a complete investment case. Before acting, translate the claim into questions that can be checked and connected to the original reason for holding an investment.

What is genuinely new?

Separate the event from the commentary around it. Identify the new data, decision or disclosure and note when it became public. Markets respond to the difference between reality and prior expectations, not simply whether news sounds positive or negative.

Which mechanism matters?

Ask how the development could affect cash flows, financing costs, valuation, liquidity or risk. Write the chain of cause and effect. If the chain is vague, the headline may not yet support a decision.

What was already expected?

Review earlier company guidance, analyst expectations and relevant market pricing where available. A dramatic announcement may confirm information that investors had already anticipated.

Which sources can verify it?

Prioritise regulatory filings, company accounts, central banks, national statistics agencies and other primary records. Note publication dates and later revisions. Commentary can help frame questions, but it should not replace source evidence.

What would disprove the interpretation?

Build at least one credible alternative explanation and identify the future evidence that would favour it. This reduces confirmation bias.

Research tip

Use a 24-hour research pause for non-urgent decisions. Add the claim, primary sources, possible portfolio impact and unanswered questions to a watchlist. Urgency often falls while the quality of the decision improves.
BODY,
                'request-desk.jpg',
                'A desk prepared for reviewing financial news and primary evidence',
                '2020/07/29/main-reasons-to-explain-fast-business-builder',
            ),
            $this->article(
                'Small investment fees can create a large long-term gap',
                'small-investment-fees-long-term-gap',
                'Compare every recurring charge in pounds as well as percentages, including the growth that deducted money can no longer earn.',
                <<<'BODY'
Investment fees reduce the amount that remains invested, and the effect compounds because deducted money can no longer participate in future growth. A difference that looks small as an annual percentage can become meaningful over a long holding period.

Find the full cost

List platform or custody charges, fund costs, advice fees, transaction charges, bid-offer spreads, foreign-exchange costs and applicable taxes. Some appear as explicit deductions; others are reflected in performance or dealing prices.

Convert percentages into pounds

Apply each percentage to a realistic balance and expected contribution pattern. Show the cost in the first year and over the intended period. Where charges use tiers or fixed amounts, calculate them rather than relying on a headline rate.

Compare like with like

Use the same starting amount, contributions, duration and assumed gross return for each option. Distinguish guaranteed charges from uncertain returns. The illustration is a comparison tool, not a forecast.

Test sensitivity

Compare lower and higher return assumptions, different holding periods and changes in contribution levels. Also test whether transaction frequency or currency conversion could materially alter the result.

Judge cost alongside value

The cheapest option is not automatically suitable. Service, diversification, tax treatment, liquidity and advice can have value. The useful question is whether each cost is transparent, understood and justified by the benefit received.

Research tip

Request the total annual cost in both pounds and percentage terms, then reconcile it with statements after a full year. Investigate unexplained differences and repeat the comparison when balances or services change.
BODY,
                'financial-district.jpg',
                'Financial buildings representing the long-term compounding effect of fees',
                '2023/12/29/transform-your-business-landscape-with-our-innovative-solutions',
            ),
            $this->article(
                'Seven questions to ask before taking more investment risk',
                'seven-questions-before-taking-more-investment-risk',
                'Test the purpose, time horizon, liquidity, loss capacity and portfolio fit before increasing investment risk.',
                <<<'BODY'
More risk is not automatically rewarded, and recent gains do not show whether a portfolio can withstand the next difficult period. Before increasing exposure, answer these seven questions in writing.

1. What goal does this money serve?

A defined purpose makes it possible to judge whether uncertainty is appropriate. Separate essential goals from desirable ones.

2. When might the money be needed?

Short or inflexible time horizons leave less opportunity to recover from a fall. Consider the earliest plausible withdrawal date, not only the preferred one.

3. Is there a separate liquidity reserve?

Unexpected spending should not force the sale of volatile assets at an unfavourable time. Review emergency cash and foreseeable commitments first.

4. What loss can the plan absorb?

Convert percentage falls into pounds and test whether essential objectives still remain achievable. Capacity for loss is different from emotional comfort with volatility.

5. Which risks already dominate?

Look through funds and accounts for concentration by issuer, sector, geography, currency and economic driver. Several holdings may depend on the same outcome.

6. What would trigger a review?

Define changes in goals, finances, evidence or portfolio structure that require attention. Avoid using normal price movement as the only trigger.

7. Which assumptions are evidence-based?

Distinguish primary evidence and long-term data from recent performance, social proof and forecasts. Write down uncertainty rather than hiding it behind a single score.

Research tip

Run at least three pound-based scenarios—a routine decline, a severe decline and a slow recovery. If any scenario would force an unacceptable change in plans, reconsider the amount or timing of the additional risk.
BODY,
                'conversation-meeting.jpg',
                'A structured conversation about investment risk and capacity for loss',
                '2020/07/29/secrets-your-business-mentor-wont-tell-you',
            ),
            $this->article(
                'How AI can improve investment research without replacing judgement',
                'how-ai-can-improve-investment-research',
                'Use AI to organise evidence and challenge assumptions, while preserving source verification, privacy and human accountability.',
                <<<'BODY'
AI can accelerate parts of investment research, but speed should not be confused with reliability. A sound workflow gives the technology bounded tasks, preserves links to source evidence and leaves consequential judgement with an accountable person.

Use AI for appropriate tasks

AI can organise notes, compare document structures, extract questions, classify recurring themes and draft alternative scenarios. It can also help explain technical language at different levels. These uses support research; they do not establish that a claim is true.

Require source provenance

Ask for the claim, original source, publication date and relevant passage. Open the source and verify it independently. Prefer company filings, audited reports, regulators, central banks and official statistics for material facts.

Challenge the first answer

Request the strongest opposing case, missing variables and evidence that would change the conclusion. Compare outputs from separate prompts and treat inconsistencies as research leads rather than choosing the most persuasive response.

Protect confidential information

Do not enter account numbers, personal financial records, unpublished business information or licensed material into an AI service without an approved data-handling basis. Minimise data and follow applicable privacy and security requirements.

Keep people accountable

A named reviewer should check facts, calculations, suitability boundaries and communication before publication or action. Record the model, date, task, sources and material corrections when AI meaningfully contributes.

Research tip

Use a four-column verification sheet: AI claim, primary source, human finding and confidence or limitation. Any material claim without a reliable source remains unverified and should not drive an investment decision.
BODY,
                'financial-district.jpg',
                'A financial district representing AI-assisted investment research with human oversight',
                '2023/12/30/transformative-business-innovation-with-our-cutting-edge-solutions',
            ),
        ];

        foreach ($articles as $index => $article) {
            Article::query()->updateOrCreate(['slug' => $article['slug']], [
                ...$article,
                'body' => $article['body']."\n\nThis article is general educational information, not personalised financial advice or a recommendation to buy or sell any investment.",
                'kind' => 'tip',
                'status' => 'published',
                'generated_by_ai' => false,
                'review_notes' => 'Expanded with practical research questions, evidence checks and decision controls.',
                'reviewed_at' => now(),
                'published_at' => Article::query()->where('slug', $article['slug'])->value('published_at') ?? now()->subDays($index),
            ]);
        }
    }

    private function article(string $title, string $slug, string $excerpt, string $body, string $image, string $alt, ?string $legacyPath): array
    {
        return [
            'title' => $title,
            'slug' => $slug,
            'excerpt' => $excerpt,
            'body' => $body,
            'source_url' => $legacyPath ? 'https://investai.business/'.$legacyPath.'/' : null,
            'featured_image' => 'images/editorial/'.$image,
            'featured_image_alt' => $alt,
        ];
    }
}
