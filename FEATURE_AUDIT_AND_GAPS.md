# Feature Audit and Deployment Readiness

**Audit date:** 2026-11-16  
**Result:** Not ready for final production deployment

## Executive summary

The application has broad public, member, payment, pooja, and CMS functionality. The current PHPUnit suite passes, the frontend production bundle builds, and the tested admin navigation pages render. However, the audit found public pages that return HTTP 500, vulnerable Composer dependencies, and no working production database configuration. SMTP is intentionally deferred; payment processing and email delivery therefore remain unverified.

Do not treat passing tests as a production sign-off: several broken routes are not covered by the current suite.

## Feature inventory

The following features are present in the route map and application:

| Area | Features |
|---|---|
| Public trust site | Homepage, trust/about, leadership, seva projects, Chitrakoot places, events, blog, gallery, contact, privacy policy |
| Giving | Donation page, order creation and verification routes, payment webhook, printable receipt route |
| Pooja and seva | Pooja/service listings, booking form and confirmation, quotation/payment routes, volunteer applications |
| Member access | Login, registration and OTP steps, password recovery, dashboard, profile updates, member card, notifications |
| Community | Contact inquiries, newsletter subscriptions, blog comments and likes |
| Admin CMS | Users/roles, articles/categories/comments, media, homepage/about/navigation, poojas/slots/bookings, volunteers, leadership, inquiries/subscribers, events, causes/seva/destinations, SEO, email templates, site settings, notification center, cache controls |
| Search and discovery | Sitemap, robots.txt, RSS route, public content indexes and detail routes |

These are route/code-level features, not a claim that every end-to-end integration is production-ready. SMTP and live payment processing require external configuration and separate verification.

## Verification performed

Tests were run against the project's isolated in-memory SQLite test configuration. Browser smoke tests used a separate seeded demo SQLite database, not the configured MySQL database.

| Check | Result |
|---|---|
| `php artisan route:list` | 147 routes registered |
| `php artisan test --compact` | 113 tests passed, 646 assertions |
| Public page smoke checks | Core index pages, login/register, contact, donation, pooja, volunteer, privacy, robots and sitemap returned successfully |
| Public homepage links | 37 distinct same-origin links inspected; 3 event detail URLs returned 500 |
| Event detail routes | `/events/ram-navami-mahotsav-2027`, `/events/vriksharopan-abhiyan-2027`, and `/events/sanskrit-vidyalaya-varshikotsav-2027` each returned 500 |
| RSS | `/feed.xml` returned 500 |
| Admin navigation | After demo admin login, all 23 linked admin pages returned 200 |
| Mobile navigation | Open and close interactions succeeded |
| Public headings | 33 successful HTML pages in the homepage-link sweep each had exactly one `<h1>` |
| Sitemap | Valid XML, 33 URL entries |
| `composer validate --no-check-publish` | Valid |
| `npm run build` | Passed after restoring local npm packages; Vite emitted only an optional `fontaine` optimization warning |
| `composer audit` | Failed: 9 advisories affecting 6 packages |

The 113 passing tests do not cover the missing event-detail and RSS views found by HTTP checks. Not every mutation button was clicked manually; existing feature tests provide request-level coverage for many flows.

## Confirmed gaps and release blockers

### P0 — Broken public routes

1. The three event detail routes above return HTTP 500. The Laravel log reports `View [pages.events_show] not found`.
2. `/feed.xml` returns HTTP 500. The Laravel log reports `View [pages.feed] not found`.
3. Add the missing views (or change the routes to return the intended responses) and add feature tests that request each route. Re-run the direct URL sweep afterward.

### P0 — Dependency advisories

`composer audit` reports nine advisories. Affected packages include `laravel/framework`, `symfony/http-foundation`, `symfony/http-kernel`, `symfony/mime`, `symfony/routing`, and `symfony/yaml`. Installed versions include Laravel 13.35.0 and Symfony 7.4.19/7.4.20 components; the audit identifies affected ranges. Upgrade to patched compatible versions, review the lockfile diff, then rerun the full suite and audit before release.

No dependency versions were changed during this audit.

### P0 — Production runtime is not configured

- The working `.env` is configured for local development (`APP_ENV=local`, `APP_DEBUG=true`, `APP_URL=http://localhost:8000`).
- Its local MySQL connection at `127.0.0.1:3306` refused connections during the audit. This does not test or diagnose the Hostinger database.
- Database migrations against the intended production database were not verified.
- The browser checks used a separate demo database. They do not prove the configured MySQL schema or production data is ready.
- Production requires a provisioned database, successful forced migrations, a production URL/key, `APP_DEBUG=false`, and environment-specific session/cache/queue settings.

### Hostinger shared hosting deployment notes

The target is Hostinger shared hosting with SSH access and a MySQL-compatible database; the exact MySQL/MariaDB version and database host are not yet known. These items must be confirmed in hPanel before deployment:

1. Select PHP 8.3 or later (the project requires PHP `^8.3`) and confirm the Laravel-required PHP extensions are enabled.
2. Create the production database and database user in hPanel. Use the exact database hostname, name, username, and password Hostinger supplies; do not assume the host is `127.0.0.1`.
3. Keep the Laravel application and `.env` outside the publicly served directory. Configure the domain document root to Laravel's `public` directory where the plan permits it. Verify that only public assets and `public/index.php` are web-accessible.
4. Build frontend assets before upload (`npm ci && npm run build` once a lockfile is committed), then install production Composer dependencies over SSH (`composer install --no-dev --optimize-autoloader`).
5. Configure production environment values over SSH/hPanel, including `APP_ENV=production`, `APP_DEBUG=false`, the HTTPS `APP_URL`, a private `APP_KEY`, and the Hostinger MySQL settings. Never commit or publish `.env`.
6. Run migrations against the Hostinger database with `php artisan migrate --force`; verify the resulting schema and a real request before directing traffic.
7. Ensure `storage` and `bootstrap/cache` are writable by the PHP process. Verify `php artisan storage:link` and media upload/read permissions. Shared-hosting storage normally resides on the account filesystem, but still needs backups and quota checks.
8. Confirm the plan's process/cron limits before relying on Laravel queue workers or scheduled tasks. Test any required scheduled command using Hostinger's cron facility.

The current local MySQL refusal is not a reason to run migrations against the incomplete bundled SQLite file. Do not copy the demo SQLite database into production.

### P0 — Production credentials and integrations

- The seeded administrator credential is a documented default. Replace it or remove the seeded account before exposing a production admin panel.
- SMTP is intentionally pending. Until configured, OTP, password-reset, and notification email delivery cannot be considered working. Test delivery and failure handling after configuration.
- Payment routes exist, but real/test gateway credentials and webhook delivery were not exercised in this audit. Verify a complete test-mode payment and webhook before enabling live donations.

### P1 — Persistent uploads and reproducible frontend builds

- The default filesystem disk is local (`config/filesystems.php`). On Hostinger shared hosting this will use account storage, but write permissions, storage-link behavior, account quotas, and backups still need verification.
- `package-lock.json` is absent. The build passed after resolving semver ranges locally, but dependency resolution is not pinned for repeatable CI/deploy builds. Add and commit a lockfile, then use `npm ci`.

### Existing roadmap items to confirm

The existing [TODO_MUST_DO.md](./TODO_MUST_DO.md) still lists bulk donor CSV export and automated 80G PDF receipts as incomplete. It also flags structured-data SEO, WebP/image optimization, and object-level authorization review as follow-up work. Revalidate these items against current requirements before launch; the roadmap itself contains older version/architecture references.

SMTP being deferred is accepted for now, but it remains a launch dependency for email-based user flows.

## Theme-change preparation

The site already has a CSS custom-property foundation in [public/css/app.css](./public/css/app.css), including semantic color variables. A named theme switcher or alternate theme was not verified. For a future redesign:

1. A `theme-change` branch now points to the current commit. The checkout remains on `master` because the worktree already contains extensive uncommitted changes; they were not moved or committed.
2. Consolidate remaining hardcoded colors into semantic CSS variables before changing the visual palette.
3. Keep the existing Blade structure and behavior stable while iterating on tokens and component styles.
4. Recheck desktop/mobile layouts, contrast, keyboard interactions, and reduced-motion behavior before merging.

## Sign-off criteria

Before calling the site deployment-ready:

- Resolve the event detail and RSS 500s and add regression tests.
- Upgrade Composer packages to patched versions and pass `composer audit`.
- Provision and verify the production database, run migrations, and configure production environment values.
- Rotate/remove default admin credentials.
- Configure persistent media storage.
- Pin frontend dependencies with a committed lockfile and pass `npm ci && npm run build`.
- After SMTP setup, test registration OTP, reset-password, and notification email flows.
- After payment test credentials are available, verify order creation, signature verification, webhook delivery, and receipt behavior in test mode.
