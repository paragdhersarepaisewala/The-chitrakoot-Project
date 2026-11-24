@extends('layouts.admin')

@section('title', 'Site Settings, Logo & SEO | CMS Admin')
@section('header_title', 'Site Settings, Logo & SEO')
@section('header_subtitle', 'Manage website identity, brand logo, favicon, Google indexing, and payment gateways')

@section('header_actions')
  <button type="button" onclick="document.getElementById('siteSettingsForm').submit()" class="action-btn primary" style="font-weight: 800; letter-spacing: 0.05em;">
    SAVE ALL SETTINGS &rarr;
  </button>
@endsection

@section('styles')
<style>
  .settings-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 28px;
    align-items: start;
  }
  @media (max-width: 1024px) {
    .settings-grid {
      grid-template-columns: 1fr;
    }
  }
  .settings-card {
    background: #ffffff;
    border: 1px solid var(--line);
    border-radius: var(--radius);
    padding: 28px 30px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.03);
  }
  .card-sec-head {
    font-size: 15px;
    font-weight: 700;
    color: var(--deep);
    border-bottom: 1px solid var(--line);
    padding-bottom: 12px;
    margin-bottom: 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .form-group {
    margin-bottom: 22px;
  }
  .form-label {
    display: block;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--brown);
    margin-bottom: 8px;
  }
  .form-subtext {
    font-size: 11px;
    color: rgba(43, 35, 29, 0.6);
    margin-top: 4px;
    display: block;
  }
  .form-control {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #d5cec2;
    border-radius: 6px;
    font-size: 13.5px;
    font-family: inherit;
    color: var(--ink);
    background: #fff;
    transition: border-color .2s, box-shadow .2s;
  }
  .form-control:focus {
    outline: none;
    border-color: var(--sand);
    box-shadow: 0 0 0 3px rgba(185, 133, 80, 0.15);
  }
  .media-input-deck {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 8px;
  }
  .media-thumb-preview {
    width: 48px;
    height: 48px;
    border-radius: 6px;
    border: 1px solid #d5cec2;
    background: #faf8f5;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    flex-shrink: 0;
  }
  .media-thumb-preview img {
    width: 100%;
    height: 100%;
    object-fit: contain;
  }
  .btn-reuse {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 9px 14px;
    border-radius: 6px;
    background: #086c60;
    color: #fff;
    border: none;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .06em;
    cursor: pointer;
    white-space: nowrap;
    transition: background .2s;
  }
  .btn-reuse:hover {
    background: #06544a;
  }
  .status-badge-box {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    border-radius: 6px;
    margin-bottom: 20px;
    font-size: 12.5px;
    font-weight: 600;
  }
  .status-badge-box.active {
    background: #e8f5e9;
    color: #1b5e20;
    border: 1px solid #c8e6c9;
  }
  .status-badge-box.test {
    background: #fff8e1;
    color: #b78103;
    border: 1px solid #ffe082;
  }
  .status-badge-box.none {
    background: #ffebee;
    color: #c62828;
    border: 1px solid #ffcdd2;
  }
</style>
@endsection

@section('content')
<form id="siteSettingsForm" action="{{ url('/admin/settings') }}" method="POST" enctype="multipart/form-data">
  @csrf

  @if(session('success'))
    <div style="background: #ecfdf5; border: 1.5px solid #10b981; color: #065f46; padding: 14px 20px; border-radius: 8px; margin-bottom: 24px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
      <span>{{ session('success') }}</span>
    </div>
  @endif

  @if($errors->any())
    <div style="background: #fef2f2; border: 1.5px solid #ef4444; color: #991b1b; padding: 14px 20px; border-radius: 8px; margin-bottom: 24px;">
      <strong style="display: block; margin-bottom: 6px; font-size: 13px;">Please correct the following errors:</strong>
      <ul style="margin: 0; padding-left: 20px; font-size: 12.5px;">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="settings-grid">
    
    <!-- LEFT COLUMN: General Site Identity & SEO -->
    <div class="settings-card">
      <div class="card-sec-head">
        <span>General Site Identity & SEO</span>
        <span style="font-size: 11px; font-weight: 600; color: #086c60; background: #e0f2f1; padding: 3px 8px; border-radius: 4px;">SSR & Metadata</span>
      </div>

      <!-- Website Brand Logo -->
      <div class="form-group">
        <label class="form-label">Website Brand Logo (Header & Footer Logo)</label>
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
          <input type="file" name="logo_file" id="logo_file_input" accept="image/png,image/webp,image/jpeg,image/svg+xml" class="form-control" style="padding: 6px 10px; font-size: 12px;">
        </div>
        <span class="form-subtext">Upload PNG, WebP, JPG, or SVG (Automatically optimized to WebP format)</span>

        <div class="media-input-deck">
          <div class="media-thumb-preview" id="logo_preview_wrap">
            @if($settings->logo_path)
              <img id="logo_preview" src="{{ str_starts_with($settings->logo_path, 'http') ? $settings->logo_path : asset($settings->logo_path) }}" alt="Site Logo">
            @else
              <img id="logo_preview" src="{{ asset('assets/uploads/temple-lotus-logo.png') }}" alt="Site Logo" onerror="this.style.display='none'">
            @endif
          </div>
          <input type="text" name="logo_path" id="logo_path" value="{{ old('logo_path', $settings->logo_path ?? 'assets/uploads/temple-lotus-logo.png') }}" class="form-control" placeholder="assets/uploads/logo.webp">
          <button type="button" class="btn-reuse" onclick="openUniversalMediaPicker('logo_path', 'logo_preview')">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
            REUSE
          </button>
        </div>
      </div>

      <!-- Website Favicon -->
      <div class="form-group">
        <label class="form-label">Website Favicon (Browser Tab Icon)</label>
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
          <input type="file" name="favicon_file" id="favicon_file_input" accept="image/x-icon,image/png,image/webp,image/svg+xml" class="form-control" style="padding: 6px 10px; font-size: 12px;">
        </div>
        <span class="form-subtext">Upload PNG, ICO, WebP, or SVG (Recommended 32x32 or 64x64 px)</span>

        <div class="media-input-deck">
          <div class="media-thumb-preview" id="favicon_preview_wrap">
            @if($settings->favicon_path)
              <img id="favicon_preview" src="{{ str_starts_with($settings->favicon_path, 'http') ? $settings->favicon_path : asset($settings->favicon_path) }}" alt="Favicon">
            @else
              <img id="favicon_preview" src="{{ asset('favicon.ico') }}" alt="Favicon" onerror="this.style.display='none'">
            @endif
          </div>
          <input type="text" name="favicon_path" id="favicon_path" value="{{ old('favicon_path', $settings->favicon_path ?? 'favicon.ico') }}" class="form-control" placeholder="favicon.ico or assets/uploads/favicon.webp">
          <button type="button" class="btn-reuse" onclick="openUniversalMediaPicker('favicon_path', 'favicon_preview')">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
            REUSE
          </button>
        </div>
      </div>

      <!-- Website Title / Site Name -->
      <div class="form-group">
        <label class="form-label">Website Title / Site Name</label>
        <input type="text" name="org_name" value="{{ old('org_name', $settings->org_name ?? 'Shri Kamadgiri Peetham Sewa Trust, Chitrakoot Dham') }}" class="form-control" required>
        <span class="form-subtext">Used as the core brand title across all SSR titles, schema.org, and meta tags.</span>
      </div>

      <!-- Site Tagline -->
      <div class="form-group">
        <label class="form-label">Site Tagline</label>
        <input type="text" name="site_tagline" value="{{ old('site_tagline', $settings->site_tagline ?? 'सनातन संस्कृति • वैदिक शिक्षा • गौसेवा • पर्यावरण संरक्षण') }}" class="form-control">
        <span class="form-subtext">Short motto displayed in headers, Open Graph snippets, and site branding.</span>
      </div>

      <!-- Main Meta Description -->
      <div class="form-group">
        <label class="form-label">Main Meta Description (For Google Search & SEO)</label>
        <textarea name="meta_description" id="metaDescriptionInput" rows="3" class="form-control" placeholder="Enter high-converting, keyword-rich snippet for Google search index...">{{ old('meta_description', $settings->meta_description ?? 'श्री कामदगिरि पीठम् सेवा ट्रस्ट, चित्रकूट धाम — सनातन संस्कृति, वैदिक शिक्षा, गौसेवा, पर्यावरण संरक्षण और समाज कल्याण हेतु समर्पित सेवा प्रकल्प।') }}</textarea>
        <div style="display: flex; justify-content: space-between; font-size: 11px; color: #888; margin-top: 4px;">
          <span>Recommended length: 140–160 characters</span>
          <span id="charCount">0 characters</span>
        </div>
      </div>

      <!-- Default Social Image URL (OpenGraph) -->
      <div class="form-group">
        <label class="form-label">Default Social Image URL (OpenGraph / Twitter Card)</label>
        <div class="media-input-deck">
          <div class="media-thumb-preview" id="og_preview_wrap">
            @if($settings->default_og_image)
              <img id="og_preview" src="{{ str_starts_with($settings->default_og_image, 'http') ? $settings->default_og_image : asset($settings->default_og_image) }}" alt="OG Image">
            @else
              <img id="og_preview" src="{{ asset('assets/uploads/temple-lotus-logo.png') }}" alt="OG Image" onerror="this.style.display='none'">
            @endif
          </div>
          <input type="text" name="default_og_image" id="default_og_image" value="{{ old('default_og_image', $settings->default_og_image ?? 'assets/uploads/temple-lotus-logo.png') }}" class="form-control" placeholder="assets/uploads/og-banner.webp">
          <button type="button" class="btn-reuse" onclick="openUniversalMediaPicker('default_og_image', 'og_preview')">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
            REUSE
          </button>
        </div>
        <span class="form-subtext">Appears when your website link is shared on WhatsApp, Facebook, Twitter, and LinkedIn (Recommended 1200x630 px).</span>
      </div>

      <!-- Google Analytics 4 Measurement ID -->
      <div class="form-group">
        <label class="form-label">Google Analytics 4 (GA4) Measurement ID</label>
        <input type="text" name="ga4_measurement_id" value="{{ old('ga4_measurement_id', $settings->ga4_measurement_id) }}" class="form-control" placeholder="e.g. G-95ZXG95YXW">
        <span class="form-subtext">Official Google Analytics 4 Property ID. Injected automatically into public layout.</span>
      </div>

      <!-- GA4 Custom Tracking Script / Tag (Optional) -->
      <div class="form-group">
        <label class="form-label">GA4 Custom Tracking Script / Tag (Optional)</label>
        <textarea name="ga4_custom_script" rows="3" class="form-control" style="font-family: monospace; font-size: 12px;" placeholder="&lt;!-- Global site tag (gtag.js) - Google Analytics --&gt;&#10;&lt;script async src=&quot;https://www.googletagmanager.com/gtag/js?id=...&quot;&gt;&lt;/script&gt;">{{ old('ga4_custom_script', $settings->ga4_custom_script) }}</textarea>
      </div>

      <!-- Google Search Console Verification Meta Tag -->
      <div class="form-group">
        <label class="form-label">Google Search Console Verification Tag / Content</label>
        <input type="text" name="google_site_verification" value="{{ old('google_site_verification', $settings->google_site_verification) }}" class="form-control" placeholder="e.g. google-site-verification token or code">
        <span class="form-subtext">Directly authorizes your property in Google Search Console for indexing & sitemap crawls.</span>
      </div>

      <!-- GA4 Looker Studio Embed URL -->
      <div class="form-group">
        <label class="form-label">GA4 Looker Studio Report Embed URL (Optional Dashboard Iframe)</label>
        <input type="url" name="ga4_report_url" value="{{ old('ga4_report_url', $settings->ga4_report_url) }}" class="form-control" placeholder="https://lookerstudio.google.com/embed/reporting/...">
      </div>

      <!-- Contact Email Address -->
      <div class="form-group">
        <label class="form-label">Public Contact Email Address</label>
        <input type="email" name="email" value="{{ old('email', $settings->email ?? 'info@kamadgiripeetham.org') }}" class="form-control" placeholder="info@kamadgiripeetham.org">
      </div>

    </div>

    <!-- RIGHT COLUMN: Razorpay Payment Gateway & Trust Compliance -->
    <div class="settings-card">
      <div class="card-sec-head">
        <span>Razorpay Payment Gateway & Credentials</span>
        <span style="font-size: 11px; font-weight: 700; color: #8f1719; background: #ffebee; padding: 3px 8px; border-radius: 4px;">Zero Spoofing Active</span>
      </div>

      <!-- Payment Gateway Status Banner -->
      @php
        $hasKeyId = !empty($settings->razorpay_key_id);
        $hasKeySecret = !empty($settings->razorpay_key_secret);
        $isLive = $hasKeyId && str_starts_with($settings->razorpay_key_id, 'rzp_live_');
        $isTest = $hasKeyId && str_starts_with($settings->razorpay_key_id, 'rzp_test_');
      @endphp

      @if($isLive && $hasKeySecret)
        <div class="status-badge-box active">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
          <div>
            <div><strong>Razorpay Live Mode Active</strong></div>
            <div style="font-size: 11px; opacity: 0.85;">Genuine production transactions and UPI payments are enabled.</div>
          </div>
        </div>
      @elseif($isTest && $hasKeySecret)
        <div class="status-badge-box test">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
          <div>
            <div><strong>Razorpay Official Test Sandbox Active</strong></div>
            <div style="font-size: 11px; opacity: 0.85;">Test Key ID and Secret configured with authentic Razorpay API & HMAC signature verification.</div>
          </div>
        </div>
      @else
        <div class="status-badge-box none">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
          <div>
            <div><strong>Payment Gateway Not Fully Configured</strong></div>
            <div style="font-size: 11px; opacity: 0.85;">Please input both Razorpay Key ID and Secret to accept donations.</div>
          </div>
        </div>
      @endif

      <!-- Razorpay Key ID -->
      <div class="form-group">
        <label class="form-label">Razorpay Key ID</label>
        <input type="text" name="razorpay_key_id" value="{{ old('razorpay_key_id', $settings->razorpay_key_id) }}" class="form-control" placeholder="rzp_test_... or rzp_live_..." required>
        <span class="form-subtext">Obtain this from your Razorpay Dashboard &rarr; Settings &rarr; API Keys.</span>
      </div>

      <!-- Razorpay Key Secret -->
      <div class="form-group">
        <label class="form-label">Razorpay Key Secret</label>
        <div style="position: relative;">
          <input type="password" name="razorpay_key_secret" id="razorpayKeySecret" value="{{ old('razorpay_key_secret', $settings->razorpay_key_secret) }}" class="form-control" placeholder="••••••••••••••••••••••••" style="padding-right: 40px;" required>
          <button type="button" onclick="toggleSecretVisibility()" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #888;">
            <svg id="eyeIcon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
          </button>
        </div>
        <span class="form-subtext">Used for cryptographically verifying Razorpay webhook & client HMAC SHA256 signatures.</span>
      </div>

      <div class="card-sec-head" style="margin-top: 32px;">
        <span>Receipt & 80G Tax Exemption Control</span>
      </div>

      <!-- 1-Click 80G Master Switch Card -->
      <div style="background: #FFFDF9; border: 1.5px solid {{ ($settings->is_80g_enabled ?? false) ? '#10B981' : '#E5E7EB' }}; border-radius: 14px; padding: 22px 24px; margin-bottom: 24px; box-shadow: 0 4px 14px rgba(0,0,0,0.03); transition: all .3s;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
          <div style="flex: 1; min-width: 260px;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
              <span style="display: inline-flex; color: #92400E;"><x-icon name="shield" size="18" /></span>
              <strong style="font-size: 15px; color: #16100D;">1-Click 80G Tax Exemption Status (Master Switch)</strong>
            </div>
            <p style="margin: 0; font-size: 13px; color: #6B7280; line-height: 1.6;">
              If your organization is not 80G certified or renewal is in progress, keep this switch Disabled.
              <span style="color: #B45309; font-weight: 600;">When disabled, all 80G tax exemption claims are instantly stripped from the website, donation forms, receipts, and emails</span>, issuing standard official contribution receipts without mandatory PAN requirements.
            </p>
          </div>
          <div style="display: flex; align-items: center; gap: 12px;">
            <span id="label-80g-mode" style="font-size: 12px; font-weight: 700; color: {{ ($settings->is_80g_enabled ?? false) ? '#059669' : '#6B7280' }};">
              {{ ($settings->is_80g_enabled ?? false) ? '80G ACTIVE' : '80G DISABLED' }}
            </span>
            <label style="position: relative; display: inline-block; width: 56px; height: 30px; margin: 0; cursor: pointer;">
              <input type="hidden" name="is_80g_enabled" value="0">
              <input type="checkbox" name="is_80g_enabled" id="is_80g_checkbox" value="1" {{ ($settings->is_80g_enabled ?? false) ? 'checked' : '' }} style="opacity: 0; width: 0; height: 0;" onchange="toggle80gUI(this.checked)">
              <span id="slider-80g" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: {{ ($settings->is_80g_enabled ?? false) ? '#10B981' : '#D1D5DB' }}; border-radius: 30px; transition: .3s;"></span>
              <span id="knob-80g" style="position: absolute; top: 3px; left: {{ ($settings->is_80g_enabled ?? false) ? '29px' : '4px' }}; width: 24px; height: 24px; border-radius: 50%; background: #ffffff; box-shadow: 0 2px 4px rgba(0,0,0,0.2); transition: .3s;"></span>
            </label>
          </div>
        </div>
        <div id="status-80g-desc" style="margin-top: 14px; padding-top: 12px; border-top: 1px dashed rgba(0,0,0,0.08); font-size: 12px; font-weight: 600; color: {{ ($settings->is_80g_enabled ?? false) ? '#047857' : '#B45309' }};">
          @if($settings->is_80g_enabled ?? false)
            ✓ ACTIVE: 80G tax exemption certificates and legal statutory tax notes are displayed on all donation pages, receipts, and emails.
          @else
            ✕ DISABLED: Non-80G mode active. All 80G claims are hidden across the website. Donors receive simple, official donation vouchers.
          @endif
        </div>
      </div>

      <script>
        function toggle80gUI(checked) {
          const slider = document.getElementById('slider-80g');
          const knob = document.getElementById('knob-80g');
          const label = document.getElementById('label-80g-mode');
          const desc = document.getElementById('status-80g-desc');
          if (checked) {
            slider.style.background = '#10B981';
            knob.style.left = '29px';
            label.innerText = '80G ACTIVE';
            label.style.color = '#059669';
            desc.innerHTML = '✓ ACTIVE: 80G tax exemption certificates and legal statutory tax notes will be displayed on all donation pages, receipts, and emails.';
            desc.style.color = '#047857';
          } else {
            slider.style.background = '#D1D5DB';
            knob.style.left = '4px';
            label.innerText = '80G DISABLED';
            label.style.color = '#6B7280';
            desc.innerHTML = '✕ DISABLED: Non-80G mode active. All 80G claims are hidden across the website. Donors receive simple, official donation vouchers.';
            desc.style.color = '#B45309';
          }
        }
      </script>

      <!-- Receipt Header Title -->
      <div class="form-group">
        <label class="form-label">Donation Receipt Header Title</label>
        <input type="text" name="receipt_header_title" value="{{ old('receipt_header_title', $settings->receipt_header_title ?? 'Shri Kamadgiri Peetham Sewa Trust — Donation Receipt') }}" class="form-control">
      </div>

      <!-- Receipt Legal Note -->
      <div class="form-group">
        <label class="form-label">Receipt Legal / 80G Tax Exemption Note</label>
        <textarea name="receipt_legal_note" rows="3" class="form-control" placeholder="All donations are eligible for 50% tax exemption under Section 80G of the Income Tax Act...">{{ old('receipt_legal_note', $settings->receipt_legal_note ?? 'All donations made to Shri Kamadgiri Peetham Sewa Trust are exempt under Section 80G of the Income Tax Act, 1961. Unique Registration No: AAATK1234F.') }}</textarea>
      </div>

      <div class="card-sec-head" style="margin-top: 32px;">
        <span>Trust Registration & Legal Entities</span>
      </div>

      <!-- Trust Registration & 80G No -->
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
        <div class="form-group">
          <label class="form-label">Registration No / 80G</label>
          <input type="text" name="reg_number" value="{{ old('reg_number', $settings->reg_number) }}" class="form-control" placeholder="e.g. TRUST/2021/80G/55">
        </div>

        <div class="form-group">
          <label class="form-label">PAN Number</label>
          <input type="text" name="pan_number" value="{{ old('pan_number', $settings->pan_number) }}" class="form-control" placeholder="e.g. AAATK1234F">
        </div>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
        <div class="form-group">
          <label class="form-label">GST Number (Optional)</label>
          <input type="text" name="gst_number" value="{{ old('gst_number', $settings->gst_number) }}" class="form-control" placeholder="e.g. 23AAATK1234F1Z5">
        </div>

        <div class="form-group">
          <label class="form-label">Contact Phone</label>
          <input type="text" name="phone" value="{{ old('phone', $settings->phone ?? '+91 94150 00000') }}" class="form-control" placeholder="+91 94150 00000">
        </div>
      </div>

      <!-- Office Address -->
      <div class="form-group">
        <label class="form-label">Official Physical Address</label>
        <textarea name="address" rows="3" class="form-control" placeholder="Kamadgiri Parikrama Marg, Chitrakoot Dham, MP / UP, India">{{ old('address', $settings->address ?? 'Kamadgiri Parikrama Marg, Chitrakoot Dham, Satna / Banda, India') }}</textarea>
      </div>

      <!-- Configurable Donation Preset Amounts -->
      <div class="card-sec-head" style="margin-top: 24px;">
        <span>Donation Checkout Presets</span>
      </div>
      <div class="form-group">
        <label class="form-label">Preset Donation Amounts (₹)</label>
        <input type="text" name="donation_preset_amounts" value="{{ old('donation_preset_amounts', $settings->donation_preset_amounts ?? '501,1100,2100,5100') }}" class="form-control" placeholder="501,1100,2100,5100">
        <span class="form-subtext">Comma-separated amount presets displayed as quick-select buttons on the donate page.</span>
      </div>

      <!-- Social Media & Community Channels -->
      <div class="card-sec-head" style="margin-top: 24px;">
        <span>Official Social Media & Community Links</span>
      </div>
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
        <div class="form-group">
          <label class="form-label">Instagram Channel URL</label>
          <input type="url" name="instagram_url" value="{{ old('instagram_url', $settings->instagram_url) }}" class="form-control" placeholder="https://instagram.com/kamadgiripeetham">
        </div>
        <div class="form-group">
          <label class="form-label">YouTube Official Channel</label>
          <input type="url" name="youtube_url" value="{{ old('youtube_url', $settings->youtube_url) }}" class="form-control" placeholder="https://youtube.com/@kamadgiripeetham">
        </div>
        <div class="form-group">
          <label class="form-label">Facebook Page URL</label>
          <input type="url" name="facebook_url" value="{{ old('facebook_url', $settings->facebook_url) }}" class="form-control" placeholder="https://facebook.com/kamadgiripeetham">
        </div>
        <div class="form-group">
          <label class="form-label">WhatsApp Channel / Group</label>
          <input type="url" name="whatsapp_url" value="{{ old('whatsapp_url', $settings->whatsapp_url) }}" class="form-control" placeholder="https://whatsapp.com/channel/...">
        </div>
      </div>

      <!-- Configurable Privacy Policy Content -->
      <div class="card-sec-head" style="margin-top: 24px;">
        <span>Custom Privacy Policy Content (Optional Override)</span>
      </div>
      <div class="form-group">
        <label class="form-label">Privacy Policy HTML / Content</label>
        <textarea name="privacy_policy_content" rows="6" class="form-control" style="font-size: 12.5px; line-height: 1.5;" placeholder="Leave blank to use the default statutory privacy policy, or enter custom HTML / paragraphs to override...">{{ old('privacy_policy_content', $settings->privacy_policy_content) }}</textarea>
        <span class="form-subtext">Allows legal trustees to update policy terms and compliance details directly via CMS without editing code.</span>
      </div>

    </div>

  </div>

  <!-- Bottom Action Bar -->
  <div style="margin-top: 28px; padding: 20px 24px; background: #ffffff; border: 1px solid var(--line); border-radius: var(--radius); display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 18px rgba(0,0,0,0.03);">
    <div style="font-size: 12px; color: rgba(43, 35, 29, 0.65); display: flex; align-items: center; gap: 6px;">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2"><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>
      <span>Changes saved here apply instantly across all public SSR pages, payment checkouts, and meta tags.</span>
    </div>
    <button type="submit" class="action-btn primary" style="padding: 12px 28px; font-size: 12px; font-weight: 800; letter-spacing: 0.05em;">
      SAVE ALL SETTINGS &rarr;
    </button>
  </div>
</form>
@endsection

@section('scripts')
<script>
  function toggleSecretVisibility() {
    const input = document.getElementById('razorpayKeySecret');
    if (input.type === 'password') {
      input.type = 'text';
    } else {
      input.type = 'password';
    }
  }

  // Live Meta Description character counter
  const metaInput = document.getElementById('metaDescriptionInput');
  const charCounter = document.getElementById('charCount');
  if (metaInput && charCounter) {
    function updateCounter() {
      charCounter.textContent = `${metaInput.value.length} characters`;
    }
    metaInput.addEventListener('input', updateCounter);
    updateCounter();
  }

  // Live file upload preview listeners
  const logoFileInput = document.getElementById('logo_file_input');
  if (logoFileInput) {
    logoFileInput.addEventListener('change', function() {
      if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const preview = document.getElementById('logo_preview');
          if (preview) {
            preview.src = e.target.result;
            preview.style.display = 'block';
          }
        };
        reader.readAsDataURL(this.files[0]);
      }
    });
  }

  const faviconFileInput = document.getElementById('favicon_file_input');
  if (faviconFileInput) {
    faviconFileInput.addEventListener('change', function() {
      if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const preview = document.getElementById('favicon_preview');
          if (preview) {
            preview.src = e.target.result;
            preview.style.display = 'block';
          }
        };
        reader.readAsDataURL(this.files[0]);
      }
    });
  }

  // Live text input changes to update preview immediately
  const logoPathInput = document.getElementById('logo_path');
  if (logoPathInput) {
    logoPathInput.addEventListener('input', function() {
      const preview = document.getElementById('logo_preview');
      if (preview && this.value.trim()) {
        const val = this.value.trim();
        preview.src = val.startsWith('http') || val.startsWith('/') ? val : '/' + val;
        preview.style.display = 'block';
      }
    });
  }

  const faviconPathInput = document.getElementById('favicon_path');
  if (faviconPathInput) {
    faviconPathInput.addEventListener('input', function() {
      const preview = document.getElementById('favicon_preview');
      if (preview && this.value.trim()) {
        const val = this.value.trim();
        preview.src = val.startsWith('http') || val.startsWith('/') ? val : '/' + val;
        preview.style.display = 'block';
      }
    });
  }
</script>
@endsection