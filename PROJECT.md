# InvestAI Website and Management Portal

This repository implements the InvestAI public website and the Phase 1 website management portal described in the approved master specification.

## Product boundaries

- Public website: `investai.business`
- Website management portal: `investai.business/admin`
- Future investor platform: `app.investai.business`, with separate accounts, roles, permissions, and application boundaries

The CMS is a core Phase 1 requirement. Public pages render only published database content. Draft content is available only through authenticated administration and preview workflows.

## Approved deployment architecture

- PHP 8.3 or newer; PHP 8.4 is selected for `investai.business`
- Laravel 13
- Filament 5 administration panel
- MariaDB 10.6
- Apache with PHP-FPM on HostNext cPanel shared hosting
- Compiled frontend assets; Node.js is not required in production

## Required Phase 1 capabilities

- Secure administrator authentication, password reset, sessions, account status, and server-side RBAC
- Super Admin, Admin, Editor, and Viewer roles
- Dashboard, page management, controlled block builder, navigation, media, SEO, redirects, enquiries, waitlist, users, settings, version history, and immutable audit records
- Draft, preview, publish, unpublish, archive, and protected-system-page workflows
- CMS-managed homepage and legal pages
- Public sitemap containing published pages only
- Server-validated uploads and public forms
- Database backup and tested restore procedure
- Authorization, security, accessibility, and production-build verification

## Core content protections

The homepage, Privacy Policy, Terms, and Financial Disclaimer are system pages. They may be edited and versioned but must not be permanently deleted through normal administration.

## Implementation sequence

1. Foundation, design system, database, authentication, and authorization
2. CMS dashboard, pages, page builder, drafts, versions, and preview
3. Navigation, media, settings, SEO, redirects, and public rendering
4. Contact, waitlist, consent, exports, audit logging, and administrator management
5. Migration, accessibility, security review, backup/restore exercise, and cPanel deployment

The original supplied specification remains authoritative where it contains more detail than this repository summary.
