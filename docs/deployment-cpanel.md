# cPanel staging and production deployment

## Compatibility

The reported HostNext environment is compatible: Apache 2.4, PHP 8.3 or 8.4, PHP-FPM, and MariaDB 10.6 satisfy the application requirements. Composer dependency resolution is pinned to PHP 8.3 compatibility so the uploaded release does not accidentally include packages that require a newer PHP 8.4 patch release. Node.js is used only on the development computer to compile CSS and JavaScript; it is not required on the server.

Enable these PHP extensions for the subdomain: `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `filter`, `iconv`, `intl`, `json`, `mbstring`, `openssl`, `pcre`, `pdo`, `pdo_mysql`, `session`, `tokenizer`, and `xml`. The screenshot confirms several of them, but check the complete list in cPanel.

## Recommended staging layout

Create `staging.investai.business` in cPanel and set its document root directly to the release's `public` directory, for example:

```text
/home/CPANEL_USER/investai-staging/public
```

Keep the existing WordPress document root for `investai.business` unchanged. Protect the staging subdomain with cPanel **Directory Privacy**, issue an SSL certificate, and create a separate MariaDB database and database user. Never copy the live WordPress database into this application.

If cPanel will not accept a document root outside `public_html`, use a directory such as `public_html/staging` for the Laravel `public` files and keep the rest of the application in `/home/CPANEL_USER/investai-staging`. The two paths in the deployed `index.php` must then point to that private application's `vendor/autoload.php` and `bootstrap/app.php`.

## Create and upload a staging release

On the development computer:

```powershell
.\scripts\build-assets.ps1
.\scripts\test.ps1
.\scripts\package-staging.ps1
```

Upload `artifacts/investai-staging.zip` to `/home/CPANEL_USER`, extract it, and rename the extracted directory to `investai-staging`. The archive includes `vendor` and compiled `public/build` files, so Composer and Node.js do not need to run on shared hosting.

Copy `.env.staging.example` to `.env`, replace every placeholder, and do not leave the example password in place. If Terminal is available, run these commands from the application directory:

```bash
php artisan key:generate --force
php artisan migrate --seed --force
php artisan storage:link
php artisan optimize
php artisan filament:optimize
php artisan make:filament-user
php artisan content:import-wordpress
```

If plain `php` is not PHP 8.4, use its full cPanel path (commonly `/opt/cpanel/ea-php84/root/usr/bin/php`) for every command.

### HostNext accounts without Terminal

Use **cPanel → Cron Jobs** to run each non-interactive setup command once. Add one command at a time with a temporary every-minute schedule, wait for it to complete, check `storage/logs/laravel.log`, and immediately remove that cron entry before adding the next command. Replace `CPANEL_USER` with the actual account name:

```cron
cd /home/CPANEL_USER/investai-staging && /opt/cpanel/ea-php84/root/usr/bin/php artisan migrate --seed --force >> storage/logs/deploy.log 2>&1
cd /home/CPANEL_USER/investai-staging && /opt/cpanel/ea-php84/root/usr/bin/php artisan storage:link >> storage/logs/deploy.log 2>&1
cd /home/CPANEL_USER/investai-staging && /opt/cpanel/ea-php84/root/usr/bin/php artisan content:import-wordpress >> storage/logs/deploy.log 2>&1
cd /home/CPANEL_USER/investai-staging && /opt/cpanel/ea-php84/root/usr/bin/php artisan optimize >> storage/logs/deploy.log 2>&1
cd /home/CPANEL_USER/investai-staging && /opt/cpanel/ea-php84/root/usr/bin/php artisan filament:optimize >> storage/logs/deploy.log 2>&1
```

The import deliberately stores former WordPress posts as **unpublished legacy drafts** for editorial review. It never publishes them. Keep the existing administrator account when updating an established staging installation; `make:filament-user` is only needed for a first installation and requires an interactive terminal.

Ensure `storage` and `bootstrap/cache` are writable by the account's PHP-FPM process. Do not use permissions `777`; `755` for directories and `644` for files are the usual starting point, with writable Laravel directories adjusted for HostNext's account ownership.

## Deploying from GitHub (optional)

`.github/workflows/deploy-staging.yml` builds the release in GitHub Actions and copies it to the server over SSH with `rsync`. It runs only when started from **Actions → Deploy to staging → Run workflow**. It never touches the server's `.env` or `storage/`.

Set up once:

1. On the development computer create a dedicated key without a passphrase: `ssh-keygen -t ed25519 -f investai-deploy -N ""`.
2. In cPanel → **SSH Access → Manage SSH Keys → Import Key**, import only the **public** key (`investai-deploy.pub`), then **Manage → Authorize** it.
3. In the GitHub repository create an environment named `staging` with these secrets: `STAGING_SSH_HOST`, `STAGING_SSH_PORT`, `STAGING_SSH_USER`, `STAGING_SSH_KEY` (the private key file's contents) and `STAGING_SSH_KNOWN_HOSTS` (the output of `ssh-keyscan -p PORT HOST`). Add the variable `STAGING_PATH` (for example `/home/investai/staging.investai.business`) and optionally `STAGING_PHP_BIN`.
4. Never commit the private key or `.env`. Delete the local private key file once it is stored as a secret.

## Updating an existing staging installation with the consultancy content

Upload and extract the new release over the existing application directory (keep the server's `.env`), then run these from the application directory (or as one-off cron entries, as above):

```bash
php artisan migrate --force
php artisan db:seed --class=ConsultancyContentSeeder --force
php artisan optimize:clear
php artisan optimize
php artisan filament:optimize
```

The risk calculator's "With AI" mode is off until `ANTHROPIC_API_KEY` is set in the server's `.env` (optionally `ANTHROPIC_MODEL`, default `claude-opus-5-5`). Run `php artisan config:clear` and then `php artisan optimize` after changing it. Do not commit the key. Each visitor is limited to 3 explanations a minute and 30 a day.

`ConsultancyContentSeeder` resets the homepage, About, Contact, Consultation and the new consultancy pages (services, industries, approach, working with us, platform, AI tools and FAQ) to the repository content. Export or note any editorial changes to those pages before running it. Do not run `migrate --seed` on an established installation for the same reason.

## Staging verification

- `https://staging.investai.business/up` returns `200 OK`.
- The home, About, Solutions, Technology, Contact, Privacy, Terms, and disclaimer pages load over HTTPS.
- Insights, illustrative case studies, and Consultation pages load; no draft article appears publicly.
- Services, the four service-line pages, Industries, Approach, Working with us, Platform, AI tools, and FAQ load; the readiness check and estimator work, and `/sitemap.xml` lists only published pages.
- `/admin/login` works and unauthenticated users cannot open protected admin URLs.
- An editor can update and publish a test page.
- Contact, waitlist, and consultation forms save exactly one database record and reject invalid input.
- Page source references `/build/assets/...`; it must not reference a Vite development server.
- A missing URL returns `404`, and `APP_DEBUG=false` prevents stack traces.
- cPanel Directory Privacy blocks visitors who do not have the staging password.

## Production release later

Do not replace WordPress until staging is accepted and a rollback backup exists. For cutover: back up the WordPress files and database, take a fresh InvestAI database/media backup, change `APP_ENV=production` and `APP_URL=https://investai.business`, point the primary domain at Laravel's `public` directory, run migrations and caches, then repeat the verification checklist.

Keep this scheduler cron entry enabled. Every Monday it creates an editorial brief as a private draft; it cannot publish automatically and requires human review in `/admin`:

```cron
* * * * * cd /home/CPANEL_USER/investai-staging && /opt/cpanel/ea-php84/root/usr/bin/php artisan schedule:run >/dev/null 2>&1
```
