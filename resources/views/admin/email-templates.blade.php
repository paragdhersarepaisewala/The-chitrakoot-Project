@extends('layouts.admin')

@section('title', 'Email SMTP & Template Manager | CMS Admin')

@section('styles')
  <style>
    .email-cms-wrap {
      display: flex;
      flex-direction: column;
      gap: 24px;
    }

    /* Stats Cards Grid */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 16px;
    }

    .stat-card {
      background: #ffffff;
      border: 1px solid var(--line, rgba(89, 58, 40, 0.14));
      border-radius: 10px;
      padding: 18px 20px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      transition: all .2s ease;
    }

    .stat-card:hover {
      border-color: var(--sand, #b98550);
      transform: translateY(-2px);
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
    }

    .stat-label {
      font-size: 11px;
      font-weight: 800;
      letter-spacing: .08em;
      text-transform: uppercase;
      color: #8c7e73;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .stat-value {
      font-size: 24px;
      font-weight: 800;
      color: var(--deep, #16100d);
      font-family: var(--sans);
      margin-top: 8px;
    }

    .stat-sub {
      font-size: 12px;
      color: #6a5d52;
      margin-top: 4px;
    }

    /* Master Killswitch Banner */
    .killswitch-banner {
      border-radius: 10px;
      padding: 16px 22px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 16px;
      transition: all .3s ease;
    }

    .killswitch-active {
      background: #f0fdf4;
      border: 1.5px solid #86efac;
      color: #14532d;
    }

    .killswitch-stopped {
      background: #fef2f2;
      border: 1.5px solid #fca5a5;
      color: #7f1d1d;
    }

    /* Navigation Tabs */
    .cms-tabs {
      display: flex;
      gap: 8px;
      border-bottom: 1.5px solid var(--line);
      padding-bottom: 0;
      overflow-x: auto;
    }

    .cms-tab-btn {
      padding: 10px 18px;
      font-size: 13px;
      font-weight: 700;
      border: none;
      background: transparent;
      color: #6a5d52;
      cursor: pointer;
      border-bottom: 3px solid transparent;
      margin-bottom: -1.5px;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: all .2s ease;
      white-space: nowrap;
    }

    .cms-tab-btn:hover {
      color: var(--deep);
    }

    .cms-tab-btn.active {
      color: var(--crimson, #8f1719);
      border-bottom-color: var(--crimson, #8f1719);
    }

    .cms-tab-badge {
      font-size: 10px;
      padding: 2px 7px;
      border-radius: 9999px;
      background: rgba(0, 0, 0, 0.06);
      color: inherit;
      font-weight: 800;
    }

    /* Category Filter Bar */
    .category-filter-bar {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
      align-items: center;
    }

    .cat-pill {
      padding: 6px 14px;
      border-radius: 9999px;
      font-size: 12px;
      font-weight: 700;
      border: 1px solid var(--line);
      background: #ffffff;
      color: var(--ink);
      cursor: pointer;
      text-decoration: none;
      transition: all .2s ease;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .cat-pill:hover,
    .cat-pill.active {
      background: var(--deep);
      color: #ffffff;
      border-color: var(--deep);
    }

    /* Template Rows */
    .tmpl-card {
      background: #ffffff;
      border: 1px solid var(--line);
      border-radius: 10px;
      padding: 20px 24px;
      margin-bottom: 14px;
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 20px;
      transition: all .2s ease;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    }

    .tmpl-card:hover {
      border-color: var(--sand);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
    }

    .tmpl-card.disabled-tmpl {
      background: #faf8f5;
      opacity: 0.78;
      border-left: 4px solid #dc2626;
    }

    .tmpl-title {
      font-size: 15.5px;
      font-weight: 700;
      color: var(--deep);
      display: flex;
      align-items: center;
      gap: 10px;
      flex-wrap: wrap;
    }

    .tmpl-cat-badge {
      font-size: 10px;
      font-weight: 800;
      letter-spacing: .05em;
      text-transform: uppercase;
      padding: 3px 9px;
      border-radius: 9999px;
      background: #f4ede4;
      color: #593a28;
    }

    .tmpl-slug {
      font-size: 10.5px;
      font-family: monospace;
      background: rgba(185, 133, 80, 0.12);
      color: var(--sand);
      padding: 2px 8px;
      border-radius: 4px;
      font-weight: 600;
    }

    .tmpl-desc {
      font-size: 12.5px;
      color: #6a5d52;
      margin-top: 5px;
      line-height: 1.5;
    }

    .tmpl-subject-preview {
      font-size: 13px;
      color: #2b231d;
      margin-top: 6px;
      background: #faf7f2;
      padding: 6px 12px;
      border-radius: 6px;
      border: 1px solid #ede7dc;
      display: inline-block;
      max-width: 100%;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .tmpl-tags-wrap {
      font-size: 11px;
      color: #8c7e73;
      margin-top: 8px;
      display: flex;
      flex-wrap: wrap;
      gap: 6px;
      align-items: center;
    }

    .tag-chip {
      background: #f1ede4;
      padding: 2px 6px;
      border-radius: 3px;
      font-family: monospace;
      font-size: 10.5px;
      color: #4b3d32;
      cursor: pointer;
    }

    .tag-chip:hover {
      background: #e5ded3;
      color: #16100d;
    }

    /* Actions Buttons */
    .tmpl-actions-wrap {
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      gap: 10px;
      flex-shrink: 0;
    }

    .btn-action-group {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .btn-tmpl-preview {
      padding: 7px 16px;
      border-radius: 9999px;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: .04em;
      text-transform: uppercase;
      border: 1px solid var(--line);
      background: #ffffff;
      color: var(--ink);
      cursor: pointer;
      transition: all .2s ease;
    }

    .btn-tmpl-preview:hover {
      background: #f7f4ed;
      border-color: var(--sand);
    }

    .btn-tmpl-edit {
      padding: 7px 18px;
      border-radius: 9999px;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: .04em;
      text-transform: uppercase;
      border: none;
      background: var(--crimson);
      color: #ffffff;
      cursor: pointer;
      transition: all .2s ease;
    }

    .btn-tmpl-edit:hover {
      background: #6e1012;
    }

    /* Toggle Switch */
    .toggle-switch {
      position: relative;
      display: inline-block;
      width: 44px;
      height: 24px;
    }

    .toggle-switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }

    .toggle-slider {
      position: absolute;
      cursor: pointer;
      inset: 0;
      background-color: #d1d5db;
      transition: .3s;
      border-radius: 24px;
    }

    .toggle-slider:before {
      position: absolute;
      content: "";
      height: 18px;
      width: 18px;
      left: 3px;
      bottom: 3px;
      background-color: white;
      transition: .3s;
      border-radius: 50%;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }

    input:checked+.toggle-slider {
      background-color: #16a34a;
    }

    input:checked+.toggle-slider:before {
      transform: translateX(20px);
    }

    /* Testing Suite Box */
    .suite-card {
      background: #ffffff;
      border: 1px solid var(--line);
      border-radius: 12px;
      padding: 24px 28px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
      margin-bottom: 24px;
    }

    .suite-card-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;
      padding-bottom: 14px;
      border-bottom: 1px solid var(--line);
    }

    .diag-step-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 12px 16px;
      border-radius: 8px;
      margin-bottom: 8px;
      background: #faf8f5;
      font-size: 13px;
    }

    .diag-step-passed {
      border-left: 4px solid #16a34a;
      background: #f0fdf4;
    }

    .diag-step-failed {
      border-left: 4px solid #dc2626;
      background: #fef2f2;
    }

    /* Modals */
    .modal-overlay {
      position: fixed;
      inset: 0;
      background: rgba(20, 14, 11, 0.78);
      backdrop-filter: blur(5px);
      z-index: 99999;
      display: none;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .modal-card {
      background: #ffffff;
      border-radius: 12px;
      width: 100%;
      max-width: 820px;
      max-height: 90vh;
      display: flex;
      flex-direction: column;
      overflow: hidden;
      box-shadow: 0 25px 60px rgba(0, 0, 0, 0.4);
      border: 1px solid rgba(185, 133, 80, 0.3);
    }
  </style>
@endsection

@section('content')

  <div class="email-cms-wrap">

    <!-- 1. Masthead Header -->
    <div
      style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; padding-bottom: 20px; border-bottom: 1px solid var(--line);">
      <div>
        <div
          style="font-size: 11px; font-weight: 800; letter-spacing: .15em; text-transform: uppercase; color: var(--sand); margin-bottom: 4px;">
          COMMUNICATIONS & TRANSACTIONAL NOTIFICATIONS CMS
        </div>
        <h1 style="font-size: 26px; font-weight: 700; font-family: var(--dev, serif); color: var(--deep); margin: 0;">
          Email SMTP & Template Manager
        </h1>
        <p style="font-size: 13px; color: rgba(43,35,29,0.7); margin-top: 4px;">
          Configure live outgoing SMTP server, manage automated cause & receipt templates, pause/stop dispatches, and run
          full test suites.
        </p>
      </div>

      <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
        <button type="button" onclick="openCreateTemplateModal()" class="action-btn"
          style="background: var(--crimson); color: #fff; font-size: 12px; padding: 9px 20px; border-radius: 9999px; border: none; font-weight: 700;">
          + CREATE EMAIL TEMPLATE
        </button>
        <button type="button" onclick="openSmtpConfigModal()" class="action-btn"
          style="background: #16100d; color: #fff; font-size: 12px; padding: 9px 22px; border-radius: 9999px; border: none; font-weight: 700;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            style="margin-right: 4px; vertical-align: -2px;">
            <circle cx="12" cy="12" r="3"></circle>
            <path
              d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
            </path>
          </svg>
          SMTP CREDENTIALS
        </button>
      </div>
    </div>

    <!-- 2. Master Killswitch Status Bar -->
    <div class="killswitch-banner {{ $stats['is_sending_enabled'] ? 'killswitch-active' : 'killswitch-stopped' }}">
      <div style="display: flex; align-items: center; gap: 14px;">
        <span
          style="width: 14px; height: 14px; border-radius: 50%; background: {{ $stats['is_sending_enabled'] ? '#16a34a' : '#dc2626' }}; display: inline-block; flex-shrink: 0;"></span>
        <div>
          <div style="font-size: 14px; font-weight: 800;">
            {{ $stats['is_sending_enabled'] ? 'AUTOMATIC EMAIL SERVICES: ACTIVE & RUNNING' : 'AUTOMATIC EMAIL SERVICES: PAUSED / STOPPED' }}
          </div>
          <div style="font-size: 12px; opacity: 0.85; margin-top: 2px;">
            {{ $stats['is_sending_enabled']
    ? 'All transactional receipts, OTPs, booking confirmations, and volunteer notices are being automatically dispatched.'
    : 'Master Killswitch is engaged. All automated outgoing email dispatches are safely paused and recorded in audit log without sending.' 
              }}
          </div>
        </div>
      </div>

      <div>
        <button type="button" onclick="toggleGlobalEmailService()" class="action-btn"
          style="font-size: 12px; font-weight: 800; padding: 8px 18px; border-radius: 9999px; cursor: pointer; {{ $stats['is_sending_enabled'] ? 'background: #dc2626; color: #fff; border: none;' : 'background: #16a34a; color: #fff; border: none;' }}">
          {{ $stats['is_sending_enabled'] ? 'PAUSE / STOP ALL EMAIL SENDING' : 'RESUME AUTOMATIC EMAIL DISPATCH' }}
        </button>
      </div>
    </div>

    <!-- 3. Key Metric Stat Cards -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-label">
          <span>Registered Templates</span>
          <x-icon name="file-text" size="15" />
        </div>
        <div class="stat-value">{{ $stats['total_templates'] }}</div>
        <div class="stat-sub">{{ $stats['active_templates'] }} Active &middot; {{ $stats['disabled_templates'] }} Disabled
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-label">
          <span>SMTP Server Health</span>
          <x-icon name="zap" size="15" />
        </div>
        <div class="stat-value"
          style="font-size: 17px; font-weight: 800; color: {{ $stats['is_smtp_configured'] ? '#16a34a' : '#d97706' }};">
          {{ $stats['is_smtp_configured'] ? 'Live SMTP Attached' : 'SMTP Not Configured' }}
        </div>
        <div class="stat-sub">Host: {{ $settings->smtp_host ?: 'Not Set' }} (Port {{ $settings->smtp_port ?: '587' }})
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-label">
          <span>Outbox Deliveries</span>
          <x-icon name="inbox" size="15" />
        </div>
        <div class="stat-value">{{ $stats['logs_total'] }}</div>
        <div class="stat-sub">{{ $stats['logs_sent'] }} Live Sent &middot; {{ $stats['logs_simulated'] }} Simulated</div>
      </div>

      <div class="stat-card">
        <div class="stat-label">
          <span>Dispatches Stopped / Failed</span>
          <x-icon name="shield" size="15" />
        </div>
        <div class="stat-value" style="color: {{ $stats['logs_failed'] > 0 ? '#dc2626' : '#2b231d' }};">
          {{ $stats['logs_stopped'] }} / {{ $stats['logs_failed'] }}
        </div>
        <div class="stat-sub">{{ $stats['logs_stopped'] }} Intercepted by Killswitch &middot; {{ $stats['logs_failed'] }}
          Failed</div>
      </div>
    </div>

    <!-- 4. Section Navigation Tabs -->
    <div class="cms-tabs">
      <button type="button" onclick="switchMainTab('templates')" id="tabBtnTemplates" class="cms-tab-btn active">
        <span>Templates Manager (REGISTERED NOTIFICATION EMAILS)</span>
        <span class="cms-tab-badge">{{ $templates->count() }}</span>
      </button>
      <button type="button" onclick="switchMainTab('smtp-suite')" id="tabBtnSmtpSuite" class="cms-tab-btn">
        <span>Full SMTP Testing Suite</span>
        <span class="cms-tab-badge" style="background: #e0f2fe; color: #0369a1;">Live Suite</span>
      </button>
      <button type="button" onclick="switchMainTab('logs')" id="tabBtnLogs" class="cms-tab-btn">
        <span>Delivery Audit Logs</span>
        <span class="cms-tab-badge">{{ $recentLogs->count() }}</span>
      </button>
    </div>

    <!-- ======================================================== -->
    <!-- TAB 1: TEMPLATES MANAGER                                  -->
    <!-- ======================================================== -->
    <div id="tabContentTemplates">
      <!-- Category Filter Pills & Search -->
      <div
        style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; margin-bottom: 20px;">
        <div class="category-filter-bar">
          <a href="{{ route('admin.emailTemplates', ['category' => 'all']) }}"
            class="cat-pill {{ ($category === 'all' || empty($category)) ? 'active' : '' }}">
            All Causes & Triggers ({{ $stats['total_templates'] }})
          </a>
          <a href="{{ route('admin.emailTemplates', ['category' => 'donations']) }}"
            class="cat-pill {{ $category === 'donations' ? 'active' : '' }}"
            style="display: inline-flex; align-items: center; gap: 5px;">
            <x-icon name="heart-handshake" size="13" /> Donations & Receipts
          </a>
          <a href="{{ route('admin.emailTemplates', ['category' => 'services']) }}"
            class="cat-pill {{ $category === 'services' ? 'active' : '' }}"
            style="display: inline-flex; align-items: center; gap: 5px;">
            <x-icon name="flame" size="13" /> Pooja & Services
          </a>
          <a href="{{ route('admin.emailTemplates', ['category' => 'auth']) }}"
            class="cat-pill {{ $category === 'auth' ? 'active' : '' }}"
            style="display: inline-flex; align-items: center; gap: 5px;">
            <x-icon name="lock" size="13" /> Accounts & OTP
          </a>
          <a href="{{ route('admin.emailTemplates', ['category' => 'volunteers']) }}"
            class="cat-pill {{ $category === 'volunteers' ? 'active' : '' }}"
            style="display: inline-flex; align-items: center; gap: 5px;">
            <x-icon name="users" size="13" /> Seva Dal & Volunteers
          </a>
          <a href="{{ route('admin.emailTemplates', ['category' => 'inquiries']) }}"
            class="cat-pill {{ $category === 'inquiries' ? 'active' : '' }}"
            style="display: inline-flex; align-items: center; gap: 5px;">
            <x-icon name="mail" size="13" /> Contact Inquiries
          </a>
          <a href="{{ route('admin.emailTemplates', ['category' => 'newsletter']) }}"
            class="cat-pill {{ $category === 'newsletter' ? 'active' : '' }}"
            style="display: inline-flex; align-items: center; gap: 5px;">
            <x-icon name="file-text" size="13" /> Newsletter & Journal
          </a>
        </div>

        <form action="{{ route('admin.emailTemplates') }}" method="GET" style="display: flex; gap: 8px;">
          @if($category && $category !== 'all')
            <input type="hidden" name="category" value="{{ $category }}">
          @endif
          <input type="text" name="search" value="{{ $search }}" placeholder="Search template or slug..."
            style="padding: 7px 14px; font-size: 12.5px; border: 1px solid var(--line); border-radius: 9999px; width: 220px;">
          <button type="submit" class="action-btn"
            style="padding: 7px 14px; font-size: 12px; border-radius: 9999px;">Search</button>
        </form>
      </div>

      <!-- Templates List -->
      <div class="email-templates-list">
        @forelse($templates as $tmpl)
          <div class="tmpl-card {{ !$tmpl->is_active ? 'disabled-tmpl' : '' }}" id="template-card-{{ $tmpl->id }}">
            <div style="flex: 1; min-width: 0;">
              <div class="tmpl-title">
                <span>{{ $tmpl->name }}</span>
                <span class="tmpl-cat-badge" style="color: {{ $tmpl->getCategoryColor() }};">
                  {{ $tmpl->getCategoryLabel() }}
                </span>
                <span class="tmpl-slug">{{ $tmpl->slug }}</span>
                @if(!$tmpl->is_system)
                  <span
                    style="font-size: 10px; background: #e0e7ff; color: #3730a3; padding: 2px 7px; border-radius: 9999px; font-weight: 700;">Custom
                    Template</span>
                @endif
                @if(!$tmpl->is_active)
                  <span
                    style="font-size: 10px; color: #dc2626; background: #fee2e2; padding: 2px 8px; border-radius: 4px; font-weight: 800;">DISPATCH
                    PAUSED</span>
                @endif
              </div>

              @if($tmpl->description)
                <div class="tmpl-desc">{{ $tmpl->description }}</div>
              @endif

              <div class="tmpl-subject-preview">
                <strong>Subject:</strong> {{ $tmpl->subject }}
              </div>

              <div class="tmpl-tags-wrap">
                <strong>Available Merge Tags:</strong>
                @php
                  preg_match_all('/\{[a-z_0-9]+\}/i', $tmpl->subject . ' ' . $tmpl->content_html, $matches);
                  $tags = array_unique($matches[0] ?? []);
                  if (empty($tags)) {
                    $tags = ['{name}', '{site_name}', '{site_url}', '{date}'];
                  }
                @endphp
                @foreach(array_slice($tags, 0, 8) as $tag)
                  <span class="tag-chip" title="Click to copy"
                    onclick="navigator.clipboard.writeText('{{ $tag }}'); showToast('Copied {{ $tag }}', 'info');">{{ $tag }}</span>
                @endforeach
                @if(count($tags) > 8)
                  <span style="font-size: 10px; color: #888;">+{{ count($tags) - 8 }} more</span>
                @endif
              </div>
            </div>

            <div class="tmpl-actions-wrap">
              <!-- Active Toggle Switch -->
              <div style="display: flex; align-items: center; gap: 8px;">
                <span style="font-size: 11px; font-weight: 700; color: {{ $tmpl->is_active ? '#16a34a' : '#dc2626' }};">
                  {{ $tmpl->is_active ? 'Active' : 'Stopped' }}
                </span>
                <label class="toggle-switch">
                  <input type="checkbox" onchange="toggleTemplateActive({{ $tmpl->id }}, this)" {{ $tmpl->is_active ? 'checked' : '' }}>
                  <span class="toggle-slider"></span>
                </label>
              </div>

              <!-- Action Buttons -->
              <div class="btn-action-group">
                <button type="button" onclick="previewTemplate({{ $tmpl->id }})" class="btn-tmpl-preview">
                  Preview HTML
                </button>
                <button type="button"
                  onclick="editTemplate({{ $tmpl->id }}, '{{ addslashes($tmpl->name) }}', '{{ addslashes($tmpl->subject) }}', '{{ $tmpl->category }}', '{{ addslashes($tmpl->description ?? '') }}', {{ $tmpl->is_active ? 1 : 0 }})"
                  class="btn-tmpl-edit">
                  Edit Template
                </button>
                <button type="button" onclick="testSpecificTemplate('{{ $tmpl->slug }}')" title="Run test in testing suite"
                  class="btn-tmpl-preview" style="color: #0369a1; border-color: #bae6fd; background: #f0f9ff;">
                  Test Live
                </button>
                @if(!$tmpl->is_system)
                  <button type="button" onclick="deleteCustomTemplate({{ $tmpl->id }}, '{{ addslashes($tmpl->name) }}')"
                    title="Delete custom template"
                    style="background: none; border: none; color: #dc2626; cursor: pointer; padding: 6px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <polyline points="3 6 5 6 21 6"></polyline>
                      <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                  </button>
                @endif
              </div>
            </div>
          </div>
        @empty
          <div
            style="background: #fff; padding: 50px 20px; text-align: center; border-radius: 10px; color: #888; border: 1px dashed var(--line);">
            <div style="display: inline-flex; margin-bottom: 10px; color: #9CA3AF;"><x-icon name="inbox" size="32" /></div>
            <div style="font-size: 15px; font-weight: 700; color: #16100d;">No email templates found matching your criteria.
            </div>
            <p style="font-size: 12.5px; color: #777; margin-top: 4px;">Try selecting another category or click "+ Create
              Email Template".</p>
          </div>
        @endforelse
      </div>
    </div>

    <!-- ======================================================== -->
    <!-- TAB 2: FULL EMAIL TESTING SUITE                           -->
    <!-- ======================================================== -->
    <div id="tabContentSmtpSuite" style="display: none;">
      <!-- 1. Deep Live Connection Diagnostics -->
      <div class="suite-card">
        <div class="suite-card-header">
          <div>
            <h2 style="font-size: 17px; font-weight: 800; color: var(--deep); margin: 0;">
              1. Live SMTP Connection & Authentication Diagnostics
            </h2>
            <p style="font-size: 12.5px; color: #6a5d52; margin-top: 4px;">
              Executes real-time DNS resolution, TCP port socket connect, SMTP EHLO handshake, STARTTLS tunnel
              negotiation, and AUTH LOGIN verification.
            </p>
          </div>
          <button type="button" onclick="runSmtpDiagnostics()" id="btnRunDiagnostics" class="action-btn primary"
            style="background: #086c60; border-color: #086c60; font-size: 12px; padding: 9px 22px; border-radius: 9999px;">
            RUN DIAGNOSTIC CHECK
          </button>
        </div>

        <div id="diagResultsContainer" style="display: none;">
          <div id="diagOverallBadge"
            style="padding: 12px 18px; border-radius: 8px; margin-bottom: 16px; font-size: 13.5px; font-weight: 700;">
          </div>
          <div id="diagStepsList"></div>
        </div>

        <div id="diagInitialPlaceholder"
          style="background: #faf8f5; border: 1px dashed #dcd3c5; border-radius: 8px; padding: 28px; text-align: center; color: #777;">
          <div style="display: inline-flex; margin-bottom: 8px; color: #9CA3AF;"><x-icon name="zap" size="28" /></div>
          <div style="font-size: 13.5px; font-weight: 700; color: #16100d;">No diagnostic check run yet for this session.
          </div>
          <div style="font-size: 12px; color: #888; margin-top: 4px;">
            Click "Run Diagnostic Check" above to test connection to
            <strong>{{ $settings->smtp_host ?: 'smtp.hostinger.com' }}:{{ $settings->smtp_port ?: '587' }}</strong>.
          </div>
        </div>
      </div>

      <!-- 2. Interactive Test Email Sender -->
      <div class="suite-card">
        <div class="suite-card-header">
          <div>
            <h2 style="font-size: 17px; font-weight: 800; color: var(--deep); margin: 0;">
              2. Live Test Email Dispatcher
            </h2>
            <p style="font-size: 12.5px; color: #6a5d52; margin-top: 4px;">
              Send a live verification test email to any inbox using any cause-specific receipt, welcome OTP, or pooja
              confirmation template.
            </p>
          </div>
        </div>

        <form id="liveTestEmailForm" onsubmit="dispatchLiveTestEmail(event)">
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
            <div>
              <label
                style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #555; margin-bottom: 6px;">RECIPIENT
                EMAIL ADDRESS</label>
              <input type="email" id="testEmailRecipientInput" required placeholder="e.g. your.personal@gmail.com"
                value="{{ auth()->user()->email }}"
                style="width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 13px;">
            </div>
            <div>
              <label
                style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #555; margin-bottom: 6px;">CHOOSE
                TEMPLATE / CAUSE TO DISPATCH</label>
              <select id="testEmailTemplateSelect"
                style="width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 13px;">
                <optgroup label="Donations & Receipts">
                  <option value="donation_receipt">General 80G Donation Receipt</option>
                  <option value="donation_cause_gaushala">गौ सेवा एवं गौ संरक्षण पावती (Gaushala Dedicated)</option>
                  <option value="donation_cause_gurukul">गुरुकुल एवं संस्कृत विद्यालय पावती (Gurukul Dedicated)</option>
                  <option value="donation_cause_annakshetra">अन्नक्षेत्र एवं भंडारा सेवा पावती (Annakshetra Dedicated)
                  </option>
                  <option value="monthly_donation_active">मासिक संरक्षक सदस्यता पावती (Monthly Patron)</option>
                  <option value="monthly_due_reminder">मासिक सेवा संकल्प स्मरण पत्र (Due Reminder)</option>
                  <option value="donation_failed">सहयोग भुगतान असफल सहायता पत्र (Payment Failed)</option>
                </optgroup>
                <optgroup label="Pooja & Temple Services">
                  <option value="pooja_booking_received">पूजा संकल्प अनुरोध पावती (Pooja Received)</option>
                  <option value="pooja_quotation">पूजा दक्षिणा कोटेशन पत्र (Pooja Quotation)</option>
                  <option value="pooja_booking_confirmed">पूजा व अनुष्ठान पुष्टि पत्र (Pooja Confirmed)</option>
                  <option value="pooja_booking_completed">पूजा अनुष्ठान संपन्न व प्रसाद आशीष पत्र (Completed)</option>
                  <option value="atithi_vishram_received">अतिथि विश्राम गृह पूछताछ पावती (Guest Stay)</option>
                  <option value="temple_booking_received">मंदिर परिसर आयोजन आरक्षण पावती (Hall Booking)</option>
                </optgroup>
                <optgroup label="Accounts & Volunteers">
                  <option value="login_otp">पंजीकरण एवं लॉगिन ओटीपी कोड (Login OTP)</option>
                  <option value="welcome_registration">सफल पंजीकरण स्वागत पत्र (Welcome Registration)</option>
                  <option value="volunteer_received">सेवादार पंजीकरण आवेदन पावती (Volunteer Received)</option>
                  <option value="volunteer_approved">सेवादार आवेदन स्वीकृत एवं दल प्रवेश (Volunteer Approved)</option>
                  <option value="contact_inquiry_received">संपर्क पूछताछ पावती (Contact Acknowledgment)</option>
                  <option value="newsletter_welcome">न्यूज़लेटर सदस्यता पुष्टि व स्वागत (Newsletter Welcome)</option>
                </optgroup>
              </select>
            </div>
          </div>

          <div
            style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; padding-top: 10px; border-top: 1px solid #eee;">
            <div style="font-size: 12px; color: #777;">
              Dispatched via <strong>{{ $settings->smtp_from_address ?: 'info@kamadgiripeetham.org' }}</strong>
              ({{ $settings->smtp_from_name ?: 'Shri Kamadgiri Peetham' }}).
            </div>
            <button type="submit" id="btnDispatchTest" class="action-btn primary"
              style="background: var(--crimson); border-color: var(--crimson); font-size: 12px; padding: 10px 24px; border-radius: 9999px; font-weight: 700;">
              DISPATCH LIVE TEST EMAIL
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- ======================================================== -->
    <!-- TAB 3: OUTBOX DELIVERY AUDIT LOGS                         -->
    <!-- ======================================================== -->
    <div id="tabContentLogs" style="display: none;">
      <div class="suite-card">
        <div class="suite-card-header">
          <div>
            <h2 style="font-size: 17px; font-weight: 800; color: var(--deep); margin: 0;">
              Email Transmission Audit Trail & History
            </h2>
            <p style="font-size: 12.5px; color: #6a5d52; margin-top: 4px;">
              Audited history of recent automatic dispatches, simulated dev tests, and killswitch-intercepted emails.
            </p>
          </div>
          <button type="button" onclick="clearAllDeliveryLogs()" class="action-btn"
            style="color: #dc2626; border-color: #fca5a5; background: #fff; font-size: 11px; padding: 6px 16px; border-radius: 9999px;">
            CLEAR OUTBOX LOGS
          </button>
        </div>

        <div style="overflow-x: auto;">
          <table width="100%" border="0" cellpadding="0" cellspacing="0"
            style="border-collapse: collapse; font-size: 13px;">
            <thead>
              <tr style="background: #faf7f2; border-bottom: 1.5px solid var(--line); text-align: left;">
                <th
                  style="padding: 10px 14px; font-weight: 800; font-size: 11px; text-transform: uppercase; color: #6a5d52;">
                  Timestamp</th>
                <th
                  style="padding: 10px 14px; font-weight: 800; font-size: 11px; text-transform: uppercase; color: #6a5d52;">
                  Recipient</th>
                <th
                  style="padding: 10px 14px; font-weight: 800; font-size: 11px; text-transform: uppercase; color: #6a5d52;">
                  Template / Slug</th>
                <th
                  style="padding: 10px 14px; font-weight: 800; font-size: 11px; text-transform: uppercase; color: #6a5d52;">
                  Subject</th>
                <th
                  style="padding: 10px 14px; font-weight: 800; font-size: 11px; text-transform: uppercase; color: #6a5d52;">
                  Status</th>
              </tr>
            </thead>
            <tbody>
              @forelse($recentLogs as $log)
                @php $badge = $log->getStatusBadge(); @endphp
                <tr style="border-bottom: 1px solid #f0ede6;">
                  <td
                    style="padding: 12px 14px; font-family: monospace; font-size: 11.5px; color: #666; white-space: nowrap;">
                    {{ $log->created_at ? $log->created_at->format('d M, h:i A') : 'N/A' }}
                  </td>
                  <td style="padding: 12px 14px;">
                    <div style="font-weight: 700; color: #16100d;">{{ $log->recipient_name ?: 'Devotee' }}</div>
                    <div style="font-size: 11.5px; color: #777;">{{ $log->recipient_email }}</div>
                  </td>
                  <td style="padding: 12px 14px;">
                    <span class="tmpl-slug">{{ $log->template_slug ?: 'custom' }}</span>
                  </td>
                  <td
                    style="padding: 12px 14px; color: #333; max-width: 260px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                    {{ $log->subject }}
                  </td>
                  <td style="padding: 12px 14px;">
                    <span
                      style="display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 800; background: {{ $badge['bg'] }}; color: {{ $badge['color'] }}; border: 1px solid {{ $badge['border'] }};">
                      <span>{{ $badge['icon'] }}</span>
                      <span>{{ $badge['label'] }}</span>
                    </span>
                    @if($log->error_message)
                      <div
                        style="font-size: 10.5px; color: #b91c1c; margin-top: 4px; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                        title="{{ $log->error_message }}">
                        {{ $log->error_message }}
                      </div>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" style="padding: 30px; text-align: center; color: #999;">
                    No delivery logs recorded yet. Run a test email or trigger a transaction to generate logs.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>

  <!-- ======================================================== -->
  <!-- MODAL 1: PREVIEW TEMPLATE MODAL                           -->
  <!-- ======================================================== -->
  <div id="previewModal" class="modal-overlay">
    <div class="modal-card" style="max-width: 900px; height: 92vh;">
      <div
        style="padding: 14px 24px; border-bottom: 1px solid #eee; display: flex; align-items: center; justify-content: space-between; background: #faf8f5;">
        <div>
          <h3 id="previewModalTitle" style="font-size: 15px; font-weight: 700; margin: 0; color: #16100d;">Template
            Preview</h3>
          <span id="previewModalSubject" style="font-size: 12px; color: #777; display: block; margin-top: 2px;">Subject:
            ...</span>
        </div>
        <div style="display: flex; align-items: center; gap: 12px;">
          <div style="display: inline-flex; background: #eee; border-radius: 6px; padding: 2px;">
            <button type="button" onclick="setPreviewDevice('desktop')" id="btnDeviceDesktop"
              style="border: none; background: #fff; border-radius: 4px; padding: 4px 10px; font-size: 11px; font-weight: 700; cursor: pointer;">Desktop
              (680px)</button>
            <button type="button" onclick="setPreviewDevice('mobile')" id="btnDeviceMobile"
              style="border: none; background: transparent; border-radius: 4px; padding: 4px 10px; font-size: 11px; font-weight: 700; cursor: pointer; color: #666;">Mobile
              (375px)</button>
          </div>
          <button type="button" onclick="closePreviewModal()"
            style="background: none; border: none; font-size: 24px; color: #888; cursor: pointer;">&times;</button>
        </div>
      </div>

      <!-- Email Client Metadata Header Mockup -->
      <div
        style="padding: 10px 24px; background: #f0ede6; border-bottom: 1px solid #e5ded5; font-size: 12px; color: #555; display: flex; gap: 20px; flex-wrap: wrap;">
        <div><strong>From:</strong> {{ $settings->smtp_from_name ?: 'Shri Kamadgiri Peetham' }} &lt;{{
          $settings->smtp_from_address ?: 'info@kamadgiripeetham.org' }}&gt;</div>
        <div><strong>To:</strong> Devotee &lt;aditya.sharma@example.com&gt;</div>
        <div><strong>Security:</strong> Signed by Kamadgiri Trust (TLS 1.3)</div>
      </div>

      <div id="previewViewportWrap"
        style="padding: 20px; overflow-y: auto; flex: 1; background: #e8e2d8; display: flex; justify-content: center; align-items: flex-start;">
        <iframe id="previewEmailIframe"
          style="width: 100%; max-width: 680px; height: 100%; min-height: 580px; border: 1px solid #dcd3c5; border-radius: 8px; background: #fff; box-shadow: 0 4px 20px rgba(0,0,0,0.08);"></iframe>
      </div>

      <div
        style="padding: 12px 24px; border-top: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; background: #faf8f5;">
        <button type="button" onclick="copyTemplateSource()" class="action-btn" style="font-size: 11px;">Copy HTML
          Source</button>
        <button type="button" onclick="closePreviewModal()" class="action-btn">Close Preview</button>
      </div>
    </div>
  </div>

  <!-- ======================================================== -->
  <!-- MODAL 2: EDIT TEMPLATE MODAL                             -->
  <!-- ======================================================== -->
  <div id="editTemplateModal" class="modal-overlay">
    <div class="modal-card" style="max-width: 860px;">
      <div
        style="padding: 16px 24px; border-bottom: 1px solid #eee; display: flex; align-items: center; justify-content: space-between; background: #faf8f5;">
        <h3 id="editModalTitle" style="font-size: 16px; font-weight: 700; margin: 0; color: #16100d;">Edit Email
          Notification Template</h3>
        <button type="button" onclick="closeEditModal()"
          style="background: none; border: none; font-size: 24px; color: #888; cursor: pointer;">&times;</button>
      </div>

      <form id="editTemplateForm" onsubmit="submitTemplateUpdate(event)"
        style="display: flex; flex-direction: column; flex: 1; overflow: hidden;">
        <input type="hidden" id="edit_template_id">

        <div style="padding: 24px; overflow-y: auto; flex: 1;">
          <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px; margin-bottom: 16px;">
            <div>
              <label
                style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #555; margin-bottom: 6px;">TEMPLATE
                DISPLAY NAME</label>
              <input type="text" id="edit_name" required
                style="width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 13.5px;">
            </div>
            <div>
              <label
                style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #555; margin-bottom: 6px;">CATEGORY</label>
              <select id="edit_category"
                style="width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 13px;">
                <option value="donations">Donations & Receipts</option>
                <option value="services">Pooja & Temple Services</option>
                <option value="auth">Accounts & Security</option>
                <option value="volunteers">Seva Dal & Volunteers</option>
                <option value="inquiries">Inquiries & Messages</option>
                <option value="newsletter">Newsletter & Broadcast</option>
                <option value="general">General System</option>
              </select>
            </div>
          </div>

          <div style="margin-bottom: 16px;">
            <label
              style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #555; margin-bottom: 6px;">EMAIL
              SUBJECT LINE</label>
            <input type="text" id="edit_subject" required
              style="width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px;">
          </div>

          <div style="margin-bottom: 16px;">
            <label
              style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #555; margin-bottom: 6px;">TRIGGER
              DESCRIPTION</label>
            <input type="text" id="edit_description"
              placeholder="Brief note on when this email is automatically triggered..."
              style="width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 13px;">
          </div>

          <div style="margin-bottom: 16px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
              <label style="font-size: 11px; font-weight: 800; text-transform: uppercase; color: #555;">HTML TEMPLATE
                BODY</label>
              <span style="font-size: 11px; color: #888;">Standard responsive HTML table layout</span>
            </div>
            <textarea id="edit_content_html" rows="15" required
              style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 12.5px; font-family: monospace; line-height: 1.5;"></textarea>
            <span style="font-size: 11.5px; color: #777; margin-top: 5px; display: block;">
              Supported merge placeholders: <code>{name}</code>, <code>{email}</code>, <code>{amount}</code>,
              <code>{cause}</code>, <code>{receipt_id}</code>, <code>{receipt_url}</code>, <code>{otp}</code>,
              <code>{date}</code>, <code>{site_name}</code>, <code>{site_url}</code>.
            </span>
          </div>

          <div style="display: flex; align-items: center; gap: 8px;">
            <input type="checkbox" id="edit_is_active" value="1">
            <label for="edit_is_active" style="font-size: 13px; font-weight: 600; color: #333;">Enable automatic sending
              for this notification template</label>
          </div>
        </div>

        <div
          style="padding: 14px 24px; border-top: 1px solid #eee; display: flex; align-items: center; justify-content: space-between; background: #faf8f5;">
          <button type="button" onclick="closeEditModal()" class="action-btn">Cancel</button>
          <button type="submit" class="action-btn primary"
            style="background: var(--crimson); border-color: var(--crimson);">Save Template Changes</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ======================================================== -->
  <!-- MODAL 3: CREATE CUSTOM TEMPLATE MODAL                     -->
  <!-- ======================================================== -->
  <div id="createTemplateModal" class="modal-overlay">
    <div class="modal-card" style="max-width: 860px;">
      <div
        style="padding: 16px 24px; border-bottom: 1px solid #eee; display: flex; align-items: center; justify-content: space-between; background: #faf8f5;">
        <h3 style="font-size: 16px; font-weight: 700; margin: 0; color: #16100d;">Create New Email Notification Template
        </h3>
        <button type="button" onclick="closeCreateTemplateModal()"
          style="background: none; border: none; font-size: 24px; color: #888; cursor: pointer;">&times;</button>
      </div>

      <form id="createTemplateForm" onsubmit="submitTemplateCreate(event)"
        style="display: flex; flex-direction: column; flex: 1; overflow: hidden;">
        <div style="padding: 24px; overflow-y: auto; flex: 1;">
          <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px; margin-bottom: 16px;">
            <div>
              <label
                style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #555; margin-bottom: 6px;">TEMPLATE
                NAME</label>
              <input type="text" id="create_name" required placeholder="e.g. Navratri Special Seva Receipt"
                oninput="autoGenerateSlug(this.value)"
                style="width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 13.5px;">
            </div>
            <div>
              <label
                style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #555; margin-bottom: 6px;">CATEGORY</label>
              <select id="create_category"
                style="width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 13px;">
                <option value="donations">Donations & Receipts</option>
                <option value="services">Pooja & Temple Services</option>
                <option value="auth">Accounts & Security</option>
                <option value="volunteers">Seva Dal & Volunteers</option>
                <option value="inquiries">Inquiries & Messages</option>
                <option value="newsletter">Newsletter & Broadcast</option>
                <option value="general" selected>General System</option>
              </select>
            </div>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
            <div>
              <label
                style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #555; margin-bottom: 6px;">UNIQUE
                SLUG CODE</label>
              <input type="text" id="create_slug" required placeholder="e.g. navratri_special_seva"
                style="width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 13px; font-family: monospace;">
            </div>
            <div>
              <label
                style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #555; margin-bottom: 6px;">TRIGGER
                DESCRIPTION</label>
              <input type="text" id="create_description" placeholder="Brief note on when this email is triggered"
                style="width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 13px;">
            </div>
          </div>

          <div style="margin-bottom: 16px;">
            <label
              style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #555; margin-bottom: 6px;">EMAIL
              SUBJECT LINE</label>
            <input type="text" id="create_subject" required placeholder="Subject line for devotee inbox"
              style="width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px;">
          </div>

          <div style="margin-bottom: 16px;">
            <label
              style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #555; margin-bottom: 6px;">HTML
              TEMPLATE BODY</label>
            <textarea id="create_content_html" rows="12" required placeholder="Enter HTML coded email..."
              style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 12.5px; font-family: monospace; line-height: 1.5;"></textarea>
          </div>

          <div style="display: flex; align-items: center; gap: 8px;">
            <input type="checkbox" id="create_is_active" value="1" checked>
            <label for="create_is_active" style="font-size: 13px; font-weight: 600; color: #333;">Activate this email
              template immediately</label>
          </div>
        </div>

        <div
          style="padding: 14px 24px; border-top: 1px solid #eee; display: flex; align-items: center; justify-content: space-between; background: #faf8f5;">
          <button type="button" onclick="closeCreateTemplateModal()" class="action-btn">Cancel</button>
          <button type="submit" class="action-btn primary"
            style="background: var(--crimson); border-color: var(--crimson);">Create Template</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ======================================================== -->
  <!-- MODAL 4: CONFIGURE SMTP CREDENTIALS MODAL                 -->
  <!-- ======================================================== -->
  <div id="smtpConfigModal" class="modal-overlay">
    <div class="modal-card" style="max-width: 700px;">
      <div
        style="padding: 16px 24px; border-bottom: 1px solid #eee; display: flex; align-items: center; justify-content: space-between; background: #faf8f5;">
        <div>
          <h3 style="font-size: 16px; font-weight: 700; margin: 0; color: #16100d;">Outgoing SMTP Server Credentials</h3>
          <span style="font-size: 12px; color: #777;">Used by Laravel to dispatch authentic emails & OTPs</span>
        </div>
        <button type="button" onclick="closeSmtpConfigModal()"
          style="background: none; border: none; font-size: 24px; color: #888; cursor: pointer;">&times;</button>
      </div>

      <form id="smtpConfigForm" onsubmit="submitSmtpConfig(event)" style="padding: 24px; overflow-y: auto; flex: 1;">
        @if($settings->isSmtpConfigured())
          <div
            style="background: #f0fdf4; border: 1.5px solid #86efac; color: #166534; padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <span
              style="width: 12px; height: 12px; border-radius: 50%; background: #16a34a; display: inline-block; flex-shrink: 0;"></span>
            <div>
              <strong>Live SMTP Server Active:</strong> Outgoing emails, verification OTP codes, and donation receipts are
              delivered directly to devotees' inboxes.
            </div>
          </div>
        @else
          <div
            style="background: #fffbeb; border: 1.5px dashed #f59e0b; color: #92400e; padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <span
              style="width: 12px; height: 12px; border-radius: 50%; background: #d97706; display: inline-block; flex-shrink: 0;"></span>
            <div>
              <strong>Outgoing SMTP Not Configured:</strong> Outgoing SMTP credentials are not yet saved. Saving valid SMTP
              credentials below activates live email delivery.
            </div>
          </div>
        @endif

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px; margin-bottom: 16px;">
          <div>
            <label
              style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #555; margin-bottom: 6px;">SMTP
              HOST</label>
            <input type="text" name="smtp_host" id="cfg_smtp_host"
              value="{{ $settings->smtp_host ?: 'smtp.hostinger.com' }}" placeholder="smtp.hostinger.com / smtp.gmail.com"
              required
              style="width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 13px;">
          </div>
          <div>
            <label
              style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #555; margin-bottom: 6px;">SMTP
              PORT</label>
            <input type="number" name="smtp_port" id="cfg_smtp_port" value="{{ $settings->smtp_port ?: '587' }}"
              placeholder="587 / 465" required
              style="width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 13px;">
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
          <div>
            <label
              style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #555; margin-bottom: 6px;">SMTP
              USERNAME / EMAIL</label>
            <input type="text" name="smtp_username" id="cfg_smtp_username"
              value="{{ $settings->smtp_username ?: 'info@kamadgiripeetham.org' }}" required
              style="width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 13px;">
          </div>
          <div>
            <label
              style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #555; margin-bottom: 6px;">SMTP
              PASSWORD / APP KEY</label>
            <input type="password" name="smtp_password" id="cfg_smtp_password"
              placeholder="{{ $settings->smtp_password ? '•••••••••••• (Leave blank to keep current)' : 'Enter SMTP password' }}"
              style="width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 13px;">
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
          <div>
            <label
              style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #555; margin-bottom: 6px;">ENCRYPTION</label>
            <select name="smtp_encryption" id="cfg_smtp_encryption"
              style="width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 13px;">
              <option value="tls" {{ ($settings->smtp_encryption ?? 'tls') === 'tls' ? 'selected' : '' }}>TLS (Recommended
                for Port 587)</option>
              <option value="ssl" {{ ($settings->smtp_encryption ?? '') === 'ssl' ? 'selected' : '' }}>SSL (Recommended for
                Port 465)</option>
              <option value="" {{ empty($settings->smtp_encryption) ? 'selected' : '' }}>None (Unencrypted)</option>
            </select>
          </div>
          <div>
            <label
              style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #555; margin-bottom: 6px;">FROM
              SENDER NAME</label>
            <input type="text" name="smtp_from_name" id="cfg_smtp_from_name"
              value="{{ $settings->smtp_from_name ?: 'Shri Kamadgiri Peetham Sewa Trust' }}" required
              style="width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 13px;">
          </div>
        </div>

        <div style="margin-bottom: 24px;">
          <label
            style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #555; margin-bottom: 6px;">FROM
            EMAIL ADDRESS</label>
          <input type="email" name="smtp_from_address" id="cfg_smtp_from_address"
            value="{{ $settings->smtp_from_address ?: 'info@kamadgiripeetham.org' }}" required
            style="width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 13px;">
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 10px; padding-top: 14px; border-top: 1px solid #eee;">
          <button type="button" onclick="closeSmtpConfigModal()" class="action-btn">Cancel</button>
          <button type="submit" class="action-btn primary" style="background: #086c60; border-color: #086c60;">SAVE SMTP
            CREDENTIALS</button>
        </div>
      </form>
    </div>
  </div>

@endsection

@section('scripts')
  <script>
    let currentPreviewHtml = '';

    // Tab switching
    function switchMainTab(tab) {
      document.getElementById('tabContentTemplates').style.display = tab === 'templates' ? 'block' : 'none';
      document.getElementById('tabContentSmtpSuite').style.display = tab === 'smtp-suite' ? 'block' : 'none';
      document.getElementById('tabContentLogs').style.display = tab === 'logs' ? 'block' : 'none';

      document.getElementById('tabBtnTemplates').classList.toggle('active', tab === 'templates');
      document.getElementById('tabBtnSmtpSuite').classList.toggle('active', tab === 'smtp-suite');
      document.getElementById('tabBtnLogs').classList.toggle('active', tab === 'logs');
    }

    // Master Killswitch
    function toggleGlobalEmailService() {
      if (!confirm('Are you sure you want to toggle the global email sending service?')) return;

      const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      fetch('{{ route("admin.toggleEmailSending") }}', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': token,
          'Accept': 'application/json'
        }
      })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            showToast(data.message, data.is_enabled ? 'success' : 'warning');
            setTimeout(() => location.reload(), 800);
          } else {
            alert(data.message || 'Error toggling email service');
          }
        })
        .catch(err => alert(err.message));
    }

    // Individual Template Toggle
    function toggleTemplateActive(id, checkbox) {
      const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      fetch(`/admin/email-templates/${id}/toggle`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': token,
          'Accept': 'application/json'
        }
      })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            showToast(data.message, data.is_active ? 'success' : 'info');
            const card = document.getElementById(`template-card-${id}`);
            if (card) {
              card.classList.toggle('disabled-tmpl', !data.is_active);
            }
          } else {
            checkbox.checked = !checkbox.checked;
            alert(data.message || 'Error toggling template status');
          }
        })
        .catch(err => {
          checkbox.checked = !checkbox.checked;
          alert(err.message);
        });
    }

    // Preview Template
    function previewTemplate(id) {
      const modal = document.getElementById('previewModal');
      modal.style.display = 'flex';
      setPreviewDevice('desktop');

      fetch(`/admin/email-templates/${id}/preview`)
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            document.getElementById('previewModalTitle').textContent = data.template.name;
            document.getElementById('previewModalSubject').textContent = 'Subject: ' + data.rendered_subject;
            currentPreviewHtml = data.rendered_html;

            const iframe = document.getElementById('previewEmailIframe');
            const doc = iframe.contentWindow.document;
            doc.open();
            doc.write(data.rendered_html);
            doc.close();
          } else {
            alert('Failed to render preview: ' + (data.message || 'Unknown error'));
          }
        })
        .catch(err => alert('Preview error: ' + err.message));
    }

    function setPreviewDevice(device) {
      const iframe = document.getElementById('previewEmailIframe');
      const btnDesktop = document.getElementById('btnDeviceDesktop');
      const btnMobile = document.getElementById('btnDeviceMobile');
      if (device === 'mobile') {
        iframe.style.maxWidth = '375px';
        btnMobile.style.background = '#fff';
        btnMobile.style.color = '#000';
        btnDesktop.style.background = 'transparent';
        btnDesktop.style.color = '#666';
      } else {
        iframe.style.maxWidth = '680px';
        btnDesktop.style.background = '#fff';
        btnDesktop.style.color = '#000';
        btnMobile.style.background = 'transparent';
        btnMobile.style.color = '#666';
      }
    }

    function copyTemplateSource() {
      if (!currentPreviewHtml) return;
      navigator.clipboard.writeText(currentPreviewHtml);
      showToast('Template HTML source copied to clipboard!', 'success');
    }

    function closePreviewModal() {
      document.getElementById('previewModal').style.display = 'none';
    }

    // Edit Template
    function editTemplate(id, name, subject, category, description, isActive) {
      document.getElementById('edit_template_id').value = id;
      document.getElementById('editModalTitle').textContent = 'Edit: ' + name;
      document.getElementById('edit_name').value = name;
      document.getElementById('edit_subject').value = subject;
      document.getElementById('edit_category').value = category || 'general';
      document.getElementById('edit_description').value = description || '';
      document.getElementById('edit_is_active').checked = isActive === 1;

      fetch(`/admin/email-templates/${id}/preview`)
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            document.getElementById('edit_content_html').value = data.template.content_html;
            document.getElementById('editTemplateModal').style.display = 'flex';
          }
        });
    }

    function closeEditModal() {
      document.getElementById('editTemplateModal').style.display = 'none';
    }

    function submitTemplateUpdate(e) {
      e.preventDefault();
      const id = document.getElementById('edit_template_id').value;
      const name = document.getElementById('edit_name').value;
      const category = document.getElementById('edit_category').value;
      const description = document.getElementById('edit_description').value;
      const subject = document.getElementById('edit_subject').value;
      const contentHtml = document.getElementById('edit_content_html').value;
      const isActive = document.getElementById('edit_is_active').checked ? 1 : 0;
      const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      fetch(`/admin/email-templates/${id}`, {
        method: 'PUT',
        headers: {
          'X-CSRF-TOKEN': token,
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          name: name,
          category: category,
          description: description,
          subject: subject,
          content_html: contentHtml,
          is_active: isActive
        })
      })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            showToast(data.message, 'success');
            closeEditModal();
            setTimeout(() => location.reload(), 600);
          } else {
            alert(data.message || 'Error updating template');
          }
        })
        .catch(err => alert(err.message));
    }

    // Create Template
    function openCreateTemplateModal() {
      document.getElementById('createTemplateModal').style.display = 'flex';
    }
    function closeCreateTemplateModal() {
      document.getElementById('createTemplateModal').style.display = 'none';
    }

    function autoGenerateSlug(val) {
      const slug = val.toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/^_+|_+$/g, '');
      document.getElementById('create_slug').value = slug;
    }

    function submitTemplateCreate(e) {
      e.preventDefault();
      const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      const payload = {
        name: document.getElementById('create_name').value,
        slug: document.getElementById('create_slug').value,
        category: document.getElementById('create_category').value,
        description: document.getElementById('create_description').value,
        subject: document.getElementById('create_subject').value,
        content_html: document.getElementById('create_content_html').value,
        is_active: document.getElementById('create_is_active').checked ? 1 : 0
      };

      fetch('{{ route("admin.storeEmailTemplate") }}', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': token,
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
      })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            showToast(data.message, 'success');
            closeCreateTemplateModal();
            setTimeout(() => location.reload(), 600);
          } else {
            alert(data.message || 'Error creating template');
          }
        })
        .catch(err => alert(err.message));
    }

    // Delete Template
    function deleteCustomTemplate(id, name) {
      if (!confirm(`Are you sure you want to permanently remove custom template '${name}'?`)) return;

      const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      fetch(`/admin/email-templates/${id}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': token,
          'Accept': 'application/json'
        }
      })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 600);
          } else {
            alert(data.message || 'Failed to delete template');
          }
        })
        .catch(err => alert(err.message));
    }

    // SMTP Config Modal
    function openSmtpConfigModal() {
      document.getElementById('smtpConfigModal').style.display = 'flex';
    }
    function closeSmtpConfigModal() {
      document.getElementById('smtpConfigModal').style.display = 'none';
    }

    function submitSmtpConfig(e) {
      e.preventDefault();
      const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      const form = document.getElementById('smtpConfigForm');
      const formData = new FormData(form);

      fetch('{{ route("admin.updateSmtpConfig") }}', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': token,
          'Accept': 'application/json'
        },
        body: formData
      })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            showToast(data.message, 'success');
            closeSmtpConfigModal();
            setTimeout(() => location.reload(), 600);
          } else {
            alert(data.message || 'Error saving SMTP credentials');
          }
        })
        .catch(err => alert(err.message));
    }

    // Test Specific Template shortcut from list
    function testSpecificTemplate(slug) {
      switchMainTab('smtp-suite');
      document.getElementById('testEmailTemplateSelect').value = slug;
      document.getElementById('liveTestEmailForm').scrollIntoView({ behavior: 'smooth' });
    }

    // Run SMTP Diagnostics
    function runSmtpDiagnostics() {
      const btn = document.getElementById('btnRunDiagnostics');
      btn.disabled = true;
      btn.innerHTML = '<span class="spinner" style="display:inline-block;width:12px;height:12px;border:2px solid #fff;border-top-color:transparent;border-radius:50%;animation:spin .8s linear infinite;margin-right:6px;vertical-align:-2px;"></span> Running Checks...';

      const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      fetch('{{ route("admin.testSmtpConnection") }}', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': token,
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({})
      })
        .then(r => r.json())
        .then(data => {
          btn.disabled = false;
          btn.innerHTML = 'RUN DIAGNOSTIC CHECK';

          document.getElementById('diagInitialPlaceholder').style.display = 'none';
          const container = document.getElementById('diagResultsContainer');
          container.style.display = 'block';

          const escDiagHtml = (str) => String(str ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

          const badge = document.getElementById('diagOverallBadge');
          if (data.success) {
            badge.style.background = '#f0fdf4';
            badge.style.color = '#166534';
            badge.style.border = '1px solid #86efac';
            badge.textContent = data.message;
          } else {
            badge.style.background = '#fef2f2';
            badge.style.color = '#991b1b';
            badge.style.border = '1px solid #fca5a5';
            badge.textContent = data.message;
          }

          const stepsList = document.getElementById('diagStepsList');
          stepsList.innerHTML = '';

          if (data.steps && data.steps.length) {
            data.steps.forEach(s => {
              const isPassed = s.status === 'passed';
              const div = document.createElement('div');
              div.className = `diag-step-row ${isPassed ? 'diag-step-passed' : 'diag-step-failed'}`;
              div.innerHTML = `
                <div>
                  <div style="font-weight: 700; color: ${isPassed ? '#166534' : '#991b1b'}; display: flex; align-items: center; gap: 8px;">
                    <span>${isPassed ? '✓' : '✕'}</span>
                    <span>${escDiagHtml(s.step)}</span>
                  </div>
                  <div style="font-size: 12px; color: #555; margin-top: 2px;">${escDiagHtml(s.detail)}</div>
                </div>
                <div style="font-family: monospace; font-size: 11px; color: #777;">${s.time_ms ? escDiagHtml(s.time_ms) + 'ms' : ''}</div>
              `;
              stepsList.appendChild(div);
            });
          }
        })
        .catch(err => {
          btn.disabled = false;
          btn.innerHTML = 'RUN DIAGNOSTIC CHECK';
          alert('Diagnostics error: ' + err.message);
        });
    }

    // Dispatch Live Test Email
    function dispatchLiveTestEmail(e) {
      e.preventDefault();
      const email = document.getElementById('testEmailRecipientInput').value.trim();
      const templateSlug = document.getElementById('testEmailTemplateSelect').value;
      const btn = document.getElementById('btnDispatchTest');

      if (!email) {
        alert('Please enter a recipient email.');
        return;
      }

      btn.disabled = true;
      btn.innerHTML = '<span class="spinner" style="display:inline-block;width:12px;height:12px;border:2px solid #fff;border-top-color:transparent;border-radius:50%;animation:spin .8s linear infinite;margin-right:6px;vertical-align:-2px;"></span> Dispatched...';

      const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      fetch('{{ route("admin.testSmtpConnection") }}', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': token,
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          test_email: email,
          template_slug: templateSlug
        })
      })
        .then(r => r.json())
        .then(data => {
          btn.disabled = false;
          btn.innerHTML = 'DISPATCH LIVE TEST EMAIL';

          if (data.success) {
            showToast(`Test email successfully sent to ${email}!`, 'success');
          } else {
            alert(data.message || 'Test email dispatch failed.');
          }
        })
        .catch(err => {
          btn.disabled = false;
          btn.innerHTML = 'DISPATCH LIVE TEST EMAIL';
          alert('Dispatch error: ' + err.message);
        });
    }

    // Clear Delivery Logs
    function clearAllDeliveryLogs() {
      if (!confirm('Are you sure you want to clear all delivery audit logs?')) return;

      const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      fetch('{{ route("admin.clearEmailLogs") }}', {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': token,
          'Accept': 'application/json'
        }
      })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 600);
          }
        });
    }
  </script>
  <style>
    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }
  </style>
@endsection