# InvestAI website and CMS

The Phase 1 InvestAI public website and `/admin` content-management portal use Laravel, Filament, Tailwind CSS, and MariaDB. Frontend assets are compiled locally, so Node.js is not required on the shared hosting server.

## Run locally on Windows

From PowerShell in this project directory:

```powershell
.\scripts\serve.ps1
```

Open <http://127.0.0.1:8000>. The script creates the local SQLite database if necessary, runs safe outstanding migrations and seeders, and starts Laravel's development server. It does not erase existing data.

Create a local administrator when needed:

```powershell
.\scripts\php-command.ps1 @('artisan', 'make:filament-user')
```

Then sign in at <http://127.0.0.1:8000/admin>.

Run the automated test suite with:

```powershell
.\scripts\test.ps1
```

## Prepare a cPanel staging upload

Build the frontend after visual changes, then create the upload archive:

```powershell
.\scripts\build-assets.ps1
.\scripts\package-staging.ps1
```

The resulting `artifacts\investai-staging.zip` contains PHP dependencies and compiled frontend assets, but no `.env`, local database, Node modules, tests, or development tools. Follow [the cPanel staging guide](docs/deployment-cpanel.md) before uploading it.

The Insights workflow creates weekly **drafts**, never automatic investment advice. Editors must verify claims, record review, and deliberately publish. Legacy WordPress entries are likewise imported as private drafts for assessment.

## Project documentation

- [Master project specification](PROJECT.md)
- [cPanel staging and production deployment](docs/deployment-cpanel.md)
- [Backup and restore](docs/backup-restore.md)
# investai
