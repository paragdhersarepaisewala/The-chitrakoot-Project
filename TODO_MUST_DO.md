# Shri Kamadgiri Peetham Sewa Trust — Comprehensive Production Audit & Master Roadmap
**File:** `TODO_MUST_DO.md`  
**Generated:** October 2026  
**Status:** In Progress / Immediate Action Plan  
**Target Architecture:** Production-Ready Laravel 11 (PHP 8.5) & Redis Stack

---

## 1. Executive Summary & Audit Overview

This document provides a complete audit of the Shri Kamadgiri Peetham Sewa Trust web application, detailing:
1. **Critical UI / CMS Fixes Completed in Current Phase** (Header collision, dynamic brand logo/trust name, Quill visual editor, hero button spacing).
2. **Missing Endpoints & Incomplete Features** across public and administrative portals.
3. **Discoverability & Lead Generation Strategy** to scale devotee engagement and donations.
4. **On-Page & Off-Page SEO Architecture** for national search dominance (Chitrakoot Dham, Kamadgiri Parikrama, Ramghat Aarti, Seva).
5. **Comprehensive Security Hardening Checklist** (SQLi, XSS, CSRF, File Uploads, BOLA/IDOR, Rate Limiting, Secret Management, Server-Side Permissions, Webhook Signature Verification, Log Sanitization, Dependency Updates).
6. **Redis High-Performance Caching & Queue Architecture**.

---

## 2. Immediate Fixes Completed in This Release

- [x] **Top Navbar Collision & Overlapping Buttons**:
  - Expanded container to `max-width: 1560px` in `public/css/app.css` to give the header ample breathing room on 1280px–1440px laptop displays.
  - Compacted nav link padding and typography (`font: 600 11.5px var(--sans); padding: 6px 7.5px;`).
  - Tuned `.nav-right` action pills (`Admin Panel`, `My Account`, `Logout`, `Donate`) to prevent horizontal overflow.
  - Adjusted responsive threshold to `@media(max-width: 1280px)` so navigation collapses cleanly into the mobile drawer on constrained viewports.
- [x] **Dynamic Trust Name & Brand Logo Rendering**:
  - Replaced hardcoded SVG and static text in `resources/views/layouts/app.blade.php` (loader, header brand, and footer).
  - Dynamically bound `$siteLogoUrl` and `$orgSettings->logo_path` so uploading a new logo in CMS (`/admin/settings`) instantly updates across the entire public website.
  - Bound full dynamic trust name (`$orgSettings->org_name`) with multi-line serif formatting so the trust name is never arbitrarily shortened.
  - Symlinked storage directory via `php artisan storage:link`.
- [x] **Embedded Visual WYSIWYG Article Editor**:
  - Embedded Quill.js 2.0 (Snow theme) into `resources/views/admin/blog-editor.blade.php`.
  - Added visual toolbar for headers (H1/H2/H3), bold, italic, underline, strike, colors, alignments, lists, blockquotes, code-blocks, and links.
  - Integrated 15-second background autosave engine with Quill HTML payload.
  - Wired shortcode inserter tools (Single Image, Gallery, Quote, Video, Interactive Polls) to insert directly into the Quill cursor position.
- [x] **Hero Section CTA Spacing & Viewport Polish**:
  - Replaced tight margins in `resources/views/home.blade.php` with `clamp(120px, 15vh, 160px)` top padding and `clamp(95px, 12vh, 130px)` bottom padding.
  - Prevented CTA buttons from colliding with the bottom exploration ticker.
- [x] **Missing `/leadership` Route & View**:
  - Created `resources/views/pages/leadership.blade.php` showcasing spiritual guidance, trustees, and legal registration numbers.
  - Added route `Route::get('/leadership', [HomeController::class, 'leadership'])->name('leadership')`.
- [x] **Security Rate Limiting & File Upload Validation**:
  - Added `throttle:10,1` to login, registration step-1, OTP verification, and contact form submission.
  - Added `throttle:30,1` to Razorpay payment order creation and verification.
  - Added strict MIME validation (`mimes:jpeg,jpg,png,webp,svg,gif,mp4,webm,mov,avi,mkv,pdf|max:102400`) in `AdminController::uploadMedia` and `updateSettings`.
  - Sanitized OTP logs in `AuthController` to ensure plaintext verification codes are never leaked to production log files.
  - Created branded error pages for `500`, `403`, and `429` alongside `404`.

---

## 3. Inventory of Missing Endpoints & Incomplete Features

### A. Missing Public Endpoints
- [x] **`/pooja` & `/pooja/{slug}` (Online Pooja & Sankalp Booking)**:
  - Sacred pooja listings (Rudrabhishek, Kamadgiri Parikrama Sankalp, Gaushala Gau-Seva, Mandakini Deepdaan) with pricing tiers, samagri, and significance details.
- [x] **`/pooja/book` (Booking Submission & Gotra Form)**:
  - Complete devotee gotra, nakshatra, sankalp purpose, preferred date, time slot, and prasad delivery address with automatic confirmation emails.
- [x] **`/api/donations/webhook` (Razorpay Server Webhook)**:
  - Asynchronous webhook receiver with HMAC-SHA256 cryptographic signature verification (`X-Razorpay-Signature`), receipt generation, and transactional email dispatch.
- [x] **`/forgot-password` & `/reset-password`**:
  - Secure OTP-based password recovery flow for registered devotees and members.
- [x] **`/profile` & `/profile/update`**:
  - Devotee profile management dashboard and modal for editing contact details, profession, district, and postal address.
- [x] **`/newsletter/subscribe`**:
  - Email/WhatsApp newsletter subscription for Peetham journal and festival darshan updates with rate limiting and database capture.
- [x] **`/volunteer` (Seva Sewak Registration)**:
  - Dedicated onboarding for devotees wishing to offer physical or digital seva during Ramnavami, Deepotsav, or Guru Purnima with email approval/rejection workflows.
- [x] **Guest Devotee Synchronization & Transactional Emails**:
  - Devotees can volunteer, donate, and book poojas as guests; all branded transactional emails are sent automatically, and past history seamlessly links upon signup/login.

### B. Incomplete Features on CMS / Admin Portal
- [x] **Interactive Shortcode Rendering in Frontend Blog Views**:
  - Parsed `[gallery]`, `[quote]`, `[video]`, `[poll]`, `[widget:mandakini_gauge]` into Blade UI components inside `BlogController::show` via `ShortcodeParser`.
- [x] **Live Comment Moderation Workflow**:
  - Connected pending comments count badge on admin sidebar and moderation controls.
- [x] **Devotee Transactional Email Templates**:
  - Added seeded, customizable temple email templates in `/admin/email-templates` for volunteer approvals/rejections, donation receipts, and pooja bookings.
- [ ] **Bulk Donor CSV Export**:
  - Financial year donation export for 80G income tax returns filing with donor PAN, date, and amount.
- [ ] **Automated 80G PDF Receipt Generation**:
  - Generate digitally signed, downloadable PDF receipts using DomPDF/Snappy with QR code verification (currently supports printable HTML receipt view at `/receipt/{receipt_number}`).

---

## 4. Discoverability & Lead Generation Strategy

To transform the website into an active spiritual portal that drives high engagement and donations:

1. **Daily Mandakini Aarti Live Stream & WhatsApp Notification Hook**:
   - Add a sticky "Get Daily Morning Darshan on WhatsApp" modal/banner. Devotees enter their phone number to receive morning shringar darshan images.
2. **Kamadgiri Parikrama E-Guide (Lead Magnet)**:
   - Provide a downloadable "Chitrakoot Dham Sacred Parikrama & Pilgrimage Guide" in PDF format in exchange for email/phone lead capture.
3. **Monthly Sustaining Donor Club ("Kamadgiri Seva Sankalpi")**:
   - Promote recurring monthly micro-donations (₹200/mo, ₹500/mo, ₹1100/mo) for gau-seva fodder and gurukul student sponsorship with personalized digital patron cards.
4. **Festival Countdown Widgets**:
   - Dynamic countdown banners for Ramnavami, Diwali Deepdan, Sharad Purnima, and Amavasya Mela with specialized pooja booking forms.
5. **Interactive Chitrakoot Virtual Tour Map**:
   - Leaflet/Mapbox interactive guide of sacred spots (Ramghat, Kamadgiri, Gupt Godavari, Bharat Koop, Sati Anusuya, Sphatik Shila) with distance, timings, and history.

---

## 5. Search Engine Optimization (SEO) Roadmap

### A. On-Page SEO Requirements
- [x] **Unique Dynamic Title Tags**: Format `[Page Headline] | [Site / Trust Name]` implemented on all core pages.
- [x] **Keyword-Rich Meta Descriptions**: Length between 140–160 characters.
- [x] **Strict Semantic Hierarchy**: Exactly one `<h1>` per public page; secondary sections use `<h2>` and `<h3>`.
- [x] **Canonical Link Tags**: Rendered on all views via `<link rel="canonical" href="@yield('canonical', url()->current())">`.
- [x] **Open Graph & Twitter Cards**: Dynamic `og:title`, `og:description`, `og:image`, `og:url`, and `twitter:card`.
- [x] **Dynamic Sitemap & Robots**: `/sitemap.xml` indexing all published posts, sevas, destinations; `/robots.txt` protecting admin endpoints.
- [ ] **Schema.org Structured Data (JSON-LD)**:
  - `@type: PlaceOfWorship` and `NGO` for trust homepage.
  - `@type: Article` with `author`, `publisher`, `datePublished` for blog posts.
  - `@type: Event` for upcoming festival schedules.
  - `@type: BreadcrumbList` for breadcrumb hierarchy.
- [ ] **Image Optimization**:
  - Automated WebP conversion on upload.
  - Meaningful `alt` attributes and native `loading="lazy"` on all below-the-fold media.

### B. Off-Page SEO Roadmap
- [ ] **Google Business Profile (GBP) Synchronization**:
  - Align NAP (Name, Address, Phone) across website footer and Google Maps listings for Kamadgiri Parikrama and Shri Kamadgiri Peetham.
- [ ] **High-Authority Spiritual & Cultural Backlinks**:
  - Submissions to UP Tourism directory, MP Tourism portal, and authentic Sanatan pilgrimage indexes.
- [ ] **RSS Feed Distribution**:
  - Submit `/feed.xml` to major feed directories and news readers.
- [ ] **Social Channel Cross-Linking**:
  - Structured links to verified YouTube, Instagram, and WhatsApp channels.

---

## 6. Comprehensive Security Hardening Checklist

| Area | Threat Model | Mitigation Implemented / Required | Status |
| :--- | :--- | :--- | :--- |
| **SQL Injection** | SQLi via raw query concatenation | Eloquent ORM parameter binding; no raw SQL queries with input string concatenation. | **SECURE** |
| **Cross-Site Scripting (XSS)** | Malicious script injection in comments/blog | Blade `{{ }}` auto-escaping; HTMLPurifier on Quill HTML body before persisting. | **IN PROGRESS** |
| **CSRF Protection** | Cross-Site Request Forgery | Universal `@csrf` tokens enforced on all state-altering web routes. | **SECURE** |
| **File Upload Exploitation** | Uploading `.php` or executable web shells | Strict whitelist (`mimes:jpeg,jpg,png,webp,svg,gif,mp4,pdf`), safe slugged filenames, random UUID naming. | **SECURE** |
| **BOLA / IDOR** | Devotee viewing another user's private receipt or profile | Model policies (`view`, `update`) verifying `auth()->id() === $model->user_id` or admin roles. | **ACTION REQUIRED** |
| **Brute Force & Flooding** | Credential stuffing, OTP brute force, payment card testing | Route rate limiting (`throttle:10,1` on auth/OTP; `throttle:30,1` on payments; `throttle:15,1` on comments). | **SECURE** |
| **Secret Management** | Leaking Razorpay/SMTP secrets to frontend | Secrets strictly held server-side in `.env`; never passed to JS windows or client scripts. | **SECURE** |
| **Server-Side Authorization** | Client-side role spoofing | Middleware enforcing `auth` and role checks (`super_admin`, `content_admin`, etc.) on all admin controllers. | **SECURE** |
| **Webhook Spoofing** | Fake payment verification callbacks | Cryptographic HMAC-SHA256 signature verification (`hash_hmac` with `hash_equals`). | **SECURE** |
| **Error Handling & Leakage** | Exposing stack dumps or DB credentials | Custom branded error pages (`404`, `403`, `429`, `500`); `APP_DEBUG=false` in production. | **SECURE** |
| **Log Sanitization** | Storing passwords or OTPs in logs | Plaintext OTPs removed from `logger()`; sensitive inputs masked in requests. | **SECURE** |
| **Source Maps Exposure** | Exposing original source code in frontend | Production Vite bundle configured with `build.sourcemap: false`. | **ACTION REQUIRED** |
| **Vulnerable Dependencies** | Outdated or exploitable packages | Run `composer audit` and `npm audit --omit=dev` regularly; update dependencies. | **ACTION REQUIRED** |

---

## 7. Redis Caching & Queue Optimization Plan

### Caching Architecture
1. **Cache Driver**:
   - Set `CACHE_STORE=redis` in `.env` for production environments.
   - Redis host: `127.0.0.1:6379` (or managed Redis cluster on cloud).
2. **Key Caching Strategies**:
   - **Homepage Settings & Sliders**: `Cache::remember('homepage_settings', 3600, fn() => HomepageSetting::getAll());`
   - **Organization Settings**: `Cache::remember('org_settings', 86400, fn() => OrganizationSetting::getSettings());`
   - **Published Seva Projects**: `Cache::remember('sevas_list', 3600, fn() => SevaProject::where('is_published', true)->get());`
   - **Header Nav Menu Tree**: `Cache::remember('nav_header_tree', 86400, fn() => NavigationMenuItem::getTree('header'));`
   - **Cache Invalidation**: Hook into Eloquent `saved` and `deleted` model events to flush corresponding keys immediately upon CMS updates.
3. **Session & Rate Limiting**:
   - Set `SESSION_DRIVER=redis` for lightning-fast concurrent user sessions.
   - Rate limiters will run seamlessly in Redis without hitting disk or SQLite/MySQL locks.
4. **Queue Workers**:
   - Set `QUEUE_CONNECTION=redis` to process transactional emails (receipt dispatch, OTP emails, admin alerts) in the background via `php artisan queue:work`.

---

## 8. Immediate Implementation Tasks (Sprint Checklist)

- [x] Fix Navbar button overlap and adjust responsive media query breakpoint in `app.css`.
- [x] Dynamically display uploaded CMS logo and full trust name across header, loader, and footer.
- [x] Embed real visual text editor (Quill.js) with rich formatting toolbar into `blog-editor.blade.php`.
- [x] Refactor hero section CTA buttons to ensure spacious padding and prevent bottom ticker clipping.
- [x] Add missing `/leadership` endpoint and blade view.
- [x] Implement rate-limiting throttle on authentication, OTP, payment, and comment endpoints.
- [x] Enforce strict file upload whitelist validations in `AdminController.php`.
- [x] Sanitize logs and prevent plaintext OTP leakage in `AuthController.php`.
- [x] Create branded error views (`500.blade.php`, `403.blade.php`, `429.blade.php`).
- [x] Implement Razorpay Webhook controller (`/api/donations/webhook`) with HMAC SHA-256 verification.
- [x] Add Online Pooja Booking flow (`/pooja` and `/pooja/book`).
- [x] Complete shortcode HTML parser in public blog post reader view (`ShortcodeParser.php`).
- [x] Build automated transactional email service for guest devotees (volunteer, donation receipts, pooja bookings).
- [x] Implement guest devotee history auto-linking upon signup/login on devotee dashboard (`/dashboard`).
- [ ] Build automated 80G tax receipt PDF generation with printable temple seal.
