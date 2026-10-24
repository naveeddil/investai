# Consultancy website update: status and handoff

Last updated: 2026-10-21. This records what was built, how to run and deploy it, and what is still open, so work can resume without the original conversation.

## Goal

Reposition the InvestAI website (https://staging.investai.business, later https://investai.business) as a **business and analytics consultancy**, using the business plan (`Reference/Invest AI Ltd - V1.6.pdf`, confidential, not committed) and ideas from the old WordPress site. The InvestAI investment platform is presented as a product in development.

## What was built

**Design and structure**
- New header with dropdown menus and a footer with link columns, driven by [config/site.php](../config/site.php). Three themes (Midnight, Cobalt, Ivory) still work.
- Block renderer split into partials under `resources/views/pages/blocks/`. New block types: `service_grid`, `metrics`, `comparison`, `timeline`, `faq` (with FAQPage structured data), `latest_insights`, `tool_stack`, `callout`, `waitlist_form`, `readiness_assessment`, `savings_estimator`, `risk_calculator`. `hero`, `feature_cards`, `split_content`, `process` and `cta` gained options.
- Inline SVG icon component `<x-glyph>` (not `<x-icon>`, which Filament's icon package already owns) and a dashboard illustration component.
- All new blocks are registered in the Filament page builder: [PageForm.php](../app/Filament/Resources/Pages/Schemas/PageForm.php).

**Pages** (seeded by [ConsultancyContentSeeder](../database/seeders/ConsultancyContentSeeder.php))
home, about, contact, consultation, services, business-strategy, data-analytics, ai-advisory, financial-analytics, industries, approach, engagement-models, confidentiality, platform, ai-tools, faq. The older pages (solutions, technology, case-studies, privacy, terms, financial-disclaimer) remain in `CoreContentSeeder`.

**Tools** on `/ai-tools`
1. Analytics and AI readiness check (browser only).
2. Reporting time estimator (browser only).
3. Financial risk calculator, **without AI** (browser, transparent maths, [RiskEstimator](../app/Services/RiskEstimator.php)) and **with AI** (server calls the Anthropic Messages API via [RiskExplainer](../app/Services/RiskExplainer.php); endpoint `POST /tools/risk-explanation`, limited to 3 a minute and 30 a day per IP). The AI mode is **off unless `ANTHROPIC_API_KEY` is set** in the server `.env`. Only percentages, horizon and tolerance are sent, with explicit visitor consent.

**Other**
- `/sitemap.xml`; consultation and contact forms accept a `consulting` topic; the insight article image path bug is fixed.
- Tests: 48 passing (`.\scripts\test.ps1`). Style: `vendor\bin\pint --dirty --format agent`.

## Content decisions to keep

- No testimonials, client counts, performance claims or invented statistics. The homepage figures describe working practice only.
- The site never claims FCA authorisation. The platform is described as in development, and regulated services only "once the required authorisations are in place".
- Left out of the public site on purpose: salaries, forecasts, funding, competitor comparisons, market-size figures and the founder's biography. The team section stays empty until names, roles and photos are approved.
- The business plan names GPT-3.5; the site says "large language models".

## Run it locally (Windows)

```powershell
# if scripts are blocked:
Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass
.\scripts\serve.ps1 -SkipMigrate        # http://127.0.0.1:8000
.\scripts\build-assets.ps1              # after CSS or JS changes
.\scripts\php-command.ps1 artisan db:seed --class=ConsultancyContentSeeder --force   # reset the seeded pages locally
```

`php` and `node` are not on the PATH; the scripts use the portable copies in `.tools`.

## Hosting facts (cPanel, HostNext, Pakistan)

- cPanel user `investai`, home `/home/investai`. Staging document root is `/staging.investai.business/public`, so the app lives in `/home/investai/staging.investai.business`. Staging runs PHP 8.4. The live WordPress site is `public_html` on the main domain (do not touch until cutover).
- `app.investai.business` exists and inherits PHP 8.1 (reserved for the future investor platform).
- Available in cPanel: SSH Access, Git Version Control, Cron Jobs, MultiPHP Manager, File Manager. There is no Terminal icon. One FTP account allowed.
- **SSH and similar ports are not reachable from outside Pakistan** (timeouts or refusals without a Pakistan VPN). cPanel itself and the website are reachable. Consequently GitHub Actions cannot SSH to the server.
- SSH port and `rsync` availability have **not been confirmed**. Check with the VPN on:
  `ssh -i "$HOME\.ssh\investai-deploy" -p 22 investai@investai.business "php -v; which rsync"`
- A local key `~\.ssh\investai-deploy` exists. It still needs to be imported and authorised in cPanel (SSH Access, Manage SSH Keys, Import Key, public part only). cPanel currently lists a key named `vsc`, not this one.

## Current staging state

The new release files were uploaded (new header and menu are live), but the **database still has the old pages**, so new URLs return 404 and the homepage shows old content. To finish, run the seeder on the server (see "Next steps").

## Next steps

1. **Seed staging** (VPN on, over SSH, or with a one-off cron entry; commands in [deployment-cpanel.md](deployment-cpanel.md)):
   `php artisan db:seed --class=ConsultancyContentSeeder --force`, then `optimize:clear`, `optimize`, `filament:optimize`. This overwrites admin edits to the seeded pages.
2. Verify the pages listed in the deployment doc, and `/sitemap.xml`.
3. Protect staging with cPanel Directory Privacy and turn on Force HTTPS for the staging domain (currently Off).
4. Choose the deployment route (GitHub Actions cannot reach SSH):
   - A. GitHub builds a release zip, upload via File Manager, run commands via Cron Jobs.
   - B. GitHub pushes a built `staging-release` branch; cPanel Git Version Control pulls it (server connects outward).
   - C. Deploy from the laptop with the Pakistan VPN (SSH, `scp` or `rsync` through WSL).
   - D. Ask HostNext support to allow SSH from outside Pakistan.
   `.github/workflows/deploy-staging.yml` is an SSH-based workflow that only works if D succeeds; remove or replace it once a route is chosen.
5. Create the private GitHub repo. There are no commits yet. Before the first commit check `git status`: `.gitignore` now excludes `Reference/`, `.env`, `vendor`, `public/build`, `artifacts` and `.tools`. Decide whether `AGENTS.md`, `CLAUDE.md`, `.claude`, `.agents` and `.mcp.json` belong in the repo.
6. To enable AI explanations: add `ANTHROPIC_API_KEY` to the server `.env`, then `config:clear` and `optimize`. Update the Privacy Policy to mention that the optional AI feature sends the entered percentages to an AI provider. Choose the model (`ANTHROPIC_MODEL`, default `claude-opus-5-5`; a cheaper model reduces cost). The live API call has not been tested.
7. Legal and content review before launch: the confidentiality page, privacy, terms and financial disclaimer wording; the toolset list (Python, R, Power BI and so on), the "tool-agnostic" and confidentiality commitments, and the Industries list are assumptions to confirm.
8. Optional later: a real AI assistant or chat, a team page once profiles are approved, case studies once real ones exist, Cloudflare or similar in front of staging.

## Decisions pending from the owner

- Deployment route (A, B, C or D above).
- Whether to switch the AI call to Anthropic's official PHP SDK (`anthropic-ai/sdk`); currently Laravel's HTTP client is used because the project rules say not to add dependencies without approval.
- Which model to use for the AI explanation, and the monthly cost ceiling.
