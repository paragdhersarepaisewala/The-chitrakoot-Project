# AGENT.md — Shri Kamadgiri Peetham Sewa Trust, Chitrakoot Dham

## 1. Purpose

Build a premium, production-oriented Laravel website and digital service platform for **Shri Kamadgiri Peetham Sewa Trust, Chitrakoot Dham**. It combines the public website, seva/project information, Chitrakoot guide, news/events/media, paid monthly membership, cause-specific donations, receipts, volunteer registration, and an advanced CMS for non-technical Trust staff.

This is a working application, not a static website. Anything staff need to update must be manageable through the CMS.

## 2. Agreed Decisions

Treat these as settled requirements unless a real implementation blocker arises.

- **Visual direction:** premium, cinematic, established, trustworthy, heritage-led.
- **Palette:** sandstone, ivory, dark brown, charcoal.
- **Aesthetic:** restrained sophistication inspired by Apple/Rolex-level polish, adapted to Indian heritage; do not imitate their branding.
- **Homepage:** full-width cinematic hero, concise sections, premium loader, scroll-based reveals, subtle parallax, subtle grain/noise, sophisticated transitions.
- **Icons:** SVG only. Never use emoji as interface icons.
- **Languages:** Hindi and English only. Hindi is the default; Sanskrit is not a website language requirement.
- **Copy:** Hindi for important cultural/editorial content; English is fine for common interface labels such as Home, Login, Email, Phone, Password, Forgot Password, Submit, and Dashboard.
- **Navigation:** desktop dropdowns; mobile app-style bottom navigation for Home, Seva, Explore, Events, Donate, plus a full-screen menu for secondary links.
- **Homepage content:** concise previews with separate detail pages.
- **Initial featured projects:** Gaushala, Sanskrit Vidyalaya, religious events.
- **Leadership:** separate page/module for Peethadhishwar, trustees, administrators, and volunteers.
- **Donations:** Razorpay; donor chooses the cause on the donation page; configurable preset amounts ₹501, ₹1,100, ₹2,100, ₹5,100, and custom amount.
- **Receipts:** instant download and email after verified payment.
- **Membership:** paid monthly sustaining membership.
- **Admin:** role-based accounts and CMS usable by non-technical Trust staff.
- **Email:** SMTP configuration and editable email templates.
- **Hosting:** Hostinger shared hosting with PHP and MySQL. Browser-side JavaScript is allowed; production must not require a Node.js server.
- **SEO:** semantic HTML, image alt text, metadata, sitemap, robots.txt, appropriate structured data, and crawl-friendly public content.
- **Error handling:** custom 404 and appropriate error pages.
- **Unknown Trust details:** official logo, contact details, registration/legal information, leadership biographies, verified impact statistics, bank details, social URLs, event dates, and credentials will be supplied later. Keep them configurable; never invent them.

## 3. Stack and Hosting Constraints

Preferred stack:
- Laravel 11.x, subject to the version already installed.
- PHP 8.3+ where supported by the host.
- MySQL in production.
- Blade templates, custom CSS, vanilla JavaScript.
- Composer-managed PHP packages.
- Razorpay payments.
- SMTP email.
- Laravel storage with Hostinger-compatible deployment configuration.

First inspect the current application and use its existing framework version and conventions. Do not upgrade or replace working infrastructure without a clear reason.

Node.js may be used locally for static asset building if the project already uses it; it must not be required as a production runtime service. Do not add React, Next.js, a separate Node backend, MongoDB, Redis, Docker, Kubernetes, microservices, queues, or other infrastructure without a concrete requirement and hosting compatibility.

## 4. First Action: Audit Before Editing

Before implementation:
1. Inspect the existing Laravel/PHP versions, routes, controllers, models, migrations, Blade views, assets, authentication, payment integration, email system, and admin features.
2. Run the app using the existing development setup. Do not start duplicate servers if one is already running.
3. Inspect `kamadgiri_peetham_homepage.html`. It is the approved visual reference, not production code to paste blindly.
4. Identify existing working functionality and gaps.
5. Report the implementation plan and any material risks before major schema or architecture changes.

Preserve working features. Avoid unnecessary rewrites, duplicated services, speculative migrations, and unrelated changes.

## 5. Public Website and Navigation

The menu should be CMS-configurable and can include:

- Home
- About: About the Trust, Vision & Mission, Objectives, Leadership
- Seva: Gaushala, Sanskrit Vidyalaya, Religious Events, Environment, Sant Seva, Annakshetra, Health Camps
- Chitrakoot: About Chitrakoot, Kamadgiri, Sacred Places, Visitor Information
- Media: Gallery, Videos, News, Publications
- Events
- Join: Become a Member, Volunteer, Member Login
- Contact
- Donate

Build dedicated, indexable pages for substantive content. Do not force every topic onto the homepage.

## 6. Homepage and Design System

Rebuild `kamadgiri_peetham_homepage.html` as maintainable Blade templates/components with CMS-backed content.

Suggested homepage order:
1. Cinematic hero.
2. Trust introduction.
3. Impact/statistics.
4. Seva preview.
5. Chitrakoot introduction.
6. Selected sacred destinations.
7. Upcoming events/news preview.
8. Donation CTA.
9. Footer.

Requirements:
- Full-width hero with configurable image/video support, headline, subtitle, kicker, buttons, and multiple slides.
- Responsive image handling and readable contrast.
- Scroll-based reveals, subtle parallax, restrained micro-interactions, and a premium but non-blocking loader.
- Respect `prefers-reduced-motion`.
- No excessive copy, generic SaaS cards, excessive gradients, excessive rounded corners, emoji icons, or gratuitous religious ornamentation.
- Keep animations polished without compromising performance or accessibility.

### Homepage CMS
Staff must be able to edit hero slides, desktop/mobile imagery, headlines, descriptions, buttons, visibility, section ordering, statistics, featured projects/destinations/events, and donation CTA content. Store editable content in appropriate database entities; do not hard-code it in Blade.

## 7. CMS Modules

Build a coherent CMS for non-technical staff, with draft/published/hidden states, ordering, validation, and confirmation for destructive actions.

Required modules:
- Admin dashboard and activity summary.
- Pages, menus, and homepage blocks.
- Hero slides.
- About, objectives, vision, mission.
- Seva/projects.
- Chitrakoot and destinations.
- Events.
- News/notices/articles.
- Photo/video galleries and albums.
- Leadership profiles.
- Users and membership.
- Donations, causes, payments, and receipts.
- Email templates and SMTP testing.
- Organization/site settings.
- SEO metadata.
- Social links.
- Activity/audit logs.

Content records should support relevant fields such as Hindi/English title and content, slug, description, image, alt text, SEO fields, author/editor, timestamps, publish status, and sort order. Use relational tables for core entities; JSON may be used for flexible block configuration when appropriate, not as one giant database for all content.

## 8. Seva / Projects

Initial featured projects:
1. Gaushala.
2. Sanskrit Vidyalaya.
3. Religious events.

Additional configurable projects may include environment/tree planting, Sant Seva, Annakshetra, health camps, and other Trust initiatives.

Each project should support bilingual content, slug, featured image, gallery/video, impact information, related donation cause, volunteer CTA, SEO fields, ordering, and publish status. Do not fabricate project outcomes or impact statistics.

## 9. Chitrakoot and Destinations

Initial destinations:
- Kamadgiri Parikrama.
- Ramghat.
- Bharat Milap.
- Sphatik Shila.
- Janki Kund.
- Gupt Godavari.
- Sati Anusuya Ashram.
- Hanuman Dhara.
- Bharat Koop.
- Valmiki Ashram.

Each destination needs a CMS-managed detail page with Hindi/English name, slug, short and detailed description, featured image/gallery, verified map URL/location, visitor information, religious/historical significance, SEO metadata, publish state, and ordering.

Do not hard-code the destination list in templates. Verify factual and visitor information before publishing it.

## 10. News, Events, Media, Leadership

**News:** support news, notices, announcements, articles, and project updates with bilingual fields, category, featured image, author, publication date, slug, SEO, and publish state.

**Events:** support bilingual title/description, date/time, location, image, related project, registration URL if applicable, and publish state. Upcoming-event lists must use event dates and must not label past events as upcoming.

**Gallery:** support uploads, albums, video links, YouTube embeds, captions, alt text, event/project associations, visibility, and ordering. Use lazy loading and responsive media.

**Social:** manage profile URLs for Facebook, Instagram, YouTube, X, WhatsApp Channel, and Telegram. Do not rely on fragile live-feed embeds unless later required.

**Leadership:** create a separate module/page for Peethadhishwar, trustees, office bearers, administrators, and volunteers. Profiles may include name, title, photograph, biography, ordering, and visibility. Use placeholders until verified details are supplied; never invent people or biographies.

## 11. Authentication and Accounts

Provide secure registration, email verification/OTP where configured, login, logout, Remember Me, forgot password, password reset, profile management, and a user dashboard.

Use Laravel password hashing and authorization. Validate on the server, rate-limit login/OTP/reset actions, and expire tokens/OTPs. Use session settings that work on the existing host; do not depend on unavailable services.

The dashboard should show relevant account details, membership status, donation history, receipts, and security settings.

## 12. Membership Business Rules

Membership is a paid monthly sustaining membership. Centralize status/streak calculations in a service; do not duplicate the logic across controllers, tasks, and views.

Agreed definitions:
- **Registered User:** has an account.
- **Supporter:** has at least one verified donation.
- **Active Member:** has qualifying sustaining contributions of ₹1,000 or more per month for three consecutive months.
- Qualifying monthly sustaining contributions count toward the membership streak.
- Ordinary cause-specific donations do not automatically count toward the membership streak.
- Missed qualifying months affect active status consistently.

Keep payment and membership history auditable. Do not silently alter past successful payments or history. If existing code implements these rules, inspect it and verify it matches before changing it.

## 13. Donations and Razorpay

The donor selects a cause and amount on the donation page. Initial suggested amounts are ₹501, ₹1,100, ₹2,100, ₹5,100, and custom; these amounts and causes must be configurable.

Possible causes:
- General Seva.
- Gaushala.
- Sanskrit Vidyalaya.
- Religious events.
- Environment.
- Annakshetra.
- Health camps.

### Payment flow
1. Validate cause and amount server-side.
2. Create the Razorpay order on the server.
3. Open Razorpay Checkout.
4. Verify payment signature server-side.
5. Verify webhooks using the webhook secret.
6. Finalize payment only after trusted server-side verification.
7. Generate receipt.
8. Send receipt/confirmation email.
9. Record payment and notification states.

A client-side success callback alone is never proof of payment. Webhook handling must be idempotent to prevent duplicate donations/receipts. Store donor, cause, amount, order/payment references, status, timestamps, receipt number, and relevant audit information. Never silently delete or alter successful financial records; handle refunds/corrections explicitly.

Membership contributions and cause-specific donations must remain separate in the data model and business logic.

## 14. Receipts

After verified payment, provide immediate download and email delivery. A receipt should support Trust name/logo, donor name, amount, cause or contribution type, date, transaction reference, unique receipt number, payment method, and authorized signature if configured.

Registration number, PAN, 12A/80G details, bank details, and other legal facts must be configurable and included only when supplied and verified. Never fabricate them.

## 15. Email and SMTP

Use SMTP configuration stored securely. Never expose credentials to public pages or unauthorized staff.

Email templates must be editable in the CMS with subject, HTML content, preview, test-send, enabled/disabled state, and supported variables.

Provide templates for registration/email verification OTP, password reset, welcome email, membership confirmation, membership contribution confirmation, donation confirmation/receipt, admin notifications, contact form notification, and relevant volunteer/member workflow notifications.

Keep delivery/rendering in a service. A failed email must not undo a verified payment. Record delivery attempts/status where practical.

## 16. Roles, Permissions, and Audit

Use role-based authorization, not just an `is_admin` flag.

Minimum roles:
- Super Admin.
- Content Admin.
- Membership Admin.
- Donation Admin.
- Media Editor.
- Viewer/Staff.

Permissions must prevent unauthorized staff from changing payment secrets, SMTP credentials, roles, or financial records. Audit sensitive actions such as role changes, payment-status corrections, settings changes, and content publication/deletion.

## 17. Organization Settings

Provide secure configurable settings for organization names in Hindi/English, logo/favicon, address, phone, email, WhatsApp, social URLs, registration/legal details, bank/UPI details, Razorpay configuration, SMTP configuration, default language, footer/legal text, authorized signature, map URL, and SEO defaults.

Do not invent missing details or expose secrets through public views. Restrict sensitive settings by role.

## 18. SEO and Error Pages

Implement semantic HTML, correct heading hierarchy, page titles/descriptions, canonical URLs, Open Graph metadata, stable slugs, alt text, sitemap, `robots.txt`, breadcrumbs where suitable, and factual structured data such as Organization, WebSite, Article, Event, and BreadcrumbList where appropriate.

Provide a truthful, maintainable `aiagents.txt` or equivalent only if useful; do not make unsupported claims about crawler behavior. Private dashboards, admin routes, and personal records must not be indexable.

Create a branded 404 page with Return Home and Explore Chitrakoot actions. Also provide appropriate 403, 419, 429, and 500 pages. Never expose stack traces, secrets, SQL details, or internal exception messages in production.

## 19. Security and Privacy

Implement CSRF protection, server-side validation, authorization policies/middleware, secure password hashing/session settings, rate limiting for login/OTP/reset/contact forms, token expiration, Razorpay signature and webhook verification, idempotent payment processing, safe upload validation, output escaping, audit logs, and production-safe exception handling.

Validate actual file MIME/content, size, and safe filename; do not trust extension alone. Never trust client-side roles, hidden fields, or client-side payment status. Protect personal and financial information.

## 20. Hostinger Deployment and Schema Changes

Production target is Laravel/PHP + MySQL on Hostinger shared hosting.

- Keep deployment compatible with Apache/PHP shared hosting.
- Do not depend on a Node server, Redis, Docker, or always-running workers.
- Use normal Laravel migrations as the primary schema-change method whenever possible.
- If the deployment workflow cannot run Artisan migrations, any runtime recovery for essential tables/columns must be carefully guarded, idempotent, minimal, and logged.
- Never run expensive or unsafe schema changes on every request.
- Check storage permissions and broken `public/storage` links; use a safe fallback only if required.
- Keep `.env`, Razorpay secrets, SMTP passwords, and private uploads out of Git.
- Set `APP_DEBUG=false` in production, require HTTPS, and document backup/deployment steps.

## 21. Code Quality, Performance, Accessibility

Keep business logic in services, validation in Form Requests where useful, authorization in policies/middleware, and presentation in Blade/components. Avoid giant controllers, duplicated business rules, unvalidated mass assignment, and N+1 queries.

Optimize image sizes, use responsive images, lazy-load below-fold media, use a mobile fallback for hero video, defer non-critical JavaScript, paginate large lists, add useful indexes, and avoid unnecessary dependencies.

Use semantic HTML, keyboard-accessible navigation, visible focus states, accessible labels, meaningful alt text, adequate contrast, and reduced-motion support. Test mobile layouts for overflow and touch usability.

## 22. Implementation Workflow

Work incrementally; do not attempt a huge unreviewed rewrite.

1. Audit the existing app and report what exists and what is missing.
2. Establish design tokens, typography, layouts, and reusable components.
3. Convert the approved homepage into Blade and make content CMS-backed.
4. Build public content modules.
5. Implement/verify authentication and account flows.
6. Implement/verify membership rules.
7. Implement donation causes, Razorpay verification, webhooks, and receipts.
8. Implement SMTP and editable email templates.
9. Complete CMS roles/permissions and administration.
10. Finish SEO, accessibility, performance, security, and deployment checks.

After each meaningful change, run relevant tests, inspect routes and validation, check browser behavior where tooling is available, review Laravel logs, fix regressions, and summarize changes/tests/issues. Never claim a feature is complete without checking it.

## 23. First Deliverable

The first task is an audit, not a wholesale rewrite.

Read this document, inspect the existing Laravel application, and inspect `kamadgiri_peetham_homepage.html`. Report:
- Existing framework/application status.
- Working functionality to preserve.
- Missing requirements.
- Required schema changes.
- Frontend/CMS changes.
- Payment/email/security risks.
- Recommended implementation order.

Then begin with the design system and homepage conversion while preserving the existing app and its working features.

## 24. Non-Negotiable Rules

1. This is a Laravel application, not a static-only site.
2. Inspect existing code before modifying it.
3. Use this document as the project requirements and the homepage HTML as the visual reference.
4. Keep updateable public content CMS-managed.
5. Support Hindi and English only; do not add Sanskrit as a language option.
6. Use SVG icons, never emoji icons.
7. Keep membership and cause-specific donations logically separate.
8. Verify payments server-side and process webhooks idempotently.
9. Generate receipts only after verified payment.
10. Never fabricate Trust facts, statistics, legal claims, event dates, or credentials.
11. Protect personal data and financial records.
12. Make the CMS usable by non-technical Trust staff.
13. Keep production compatible with Hostinger shared hosting.
14. Respect accessibility, performance, and reduced-motion preferences.
15. Explain genuine blockers; otherwise make conservative, documented assumptions and continue.
