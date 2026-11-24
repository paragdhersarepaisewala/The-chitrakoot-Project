@extends('layouts.admin')

@section('title', 'All Pages SEO & Featured Social Images | CMS Admin')
@section('header_title', 'All Pages SEO & Meta Manager')
@section('header_subtitle', 'Configure individual page titles, meta descriptions, focus keywords, canonical tags, and stunning 3D RAEA Open Graph featured images across all major website pages.')

@section('header_actions')
  <button type="button" onclick="document.getElementById('seoSettingsForm').submit()" class="action-btn primary" style="font-weight: 800; letter-spacing: 0.05em;">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
    SAVE ALL SEO & SOCIAL METADATA &rarr;
  </button>
@endsection

@section('styles')
<style>
  .seo-container {
    display: grid;
    grid-template-columns: 290px 1fr;
    gap: 28px;
    align-items: start;
  }
  @media (max-width: 960px) {
    .seo-container {
      grid-template-columns: 1fr;
    }
  }
  .page-nav-list {
    background: #ffffff;
    border: 1px solid var(--line);
    border-radius: var(--radius);
    padding: 12px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.03);
    position: sticky;
    top: 90px;
    max-height: calc(100vh - 120px);
    overflow-y: auto;
  }
  .page-nav-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 14px;
    border-radius: 6px;
    color: var(--brown);
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    margin-bottom: 4px;
    transition: all 0.2s ease;
    cursor: pointer;
  }
  .page-nav-item:hover {
    background: var(--sand);
    color: var(--deep);
  }
  .page-nav-item.active {
    background: var(--deep);
    color: #ffffff;
  }
  .page-nav-item .url-badge {
    font-size: 10px;
    font-family: monospace;
    opacity: 0.7;
  }
  .seo-pane {
    display: none;
  }
  .seo-pane.active {
    display: block;
  }
  .seo-card {
    background: #ffffff;
    border: 1px solid var(--line);
    border-radius: var(--radius);
    padding: 28px 32px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    margin-bottom: 24px;
  }
  .serp-box {
    background: #f8f9fa;
    border: 1px solid #dfe1e5;
    border-radius: 8px;
    padding: 16px 20px;
    margin-bottom: 24px;
    font-family: Arial, sans-serif;
  }
  .serp-url {
    font-size: 12px;
    color: #202124;
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 4px;
  }
  .serp-title {
    font-size: 18px;
    color: #1a0dab;
    cursor: pointer;
    line-height: 1.3;
    margin-bottom: 4px;
    font-weight: 400;
  }
  .serp-snippet {
    font-size: 13px;
    color: #4d5156;
    line-height: 1.4;
  }
  .counter-badge {
    font-size: 11px;
    font-weight: 600;
    margin-left: auto;
  }
  .counter-badge.good { color: #16a34a; }
  .counter-badge.warning { color: #d97706; }
  .counter-badge.danger { color: #dc2626; }

  /* Social Share / OG Card Studio */
  .social-card-studio {
    background: #17110d;
    border: 1px solid #3d281a;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    color: #fef3c7;
  }
  .og-preview-frame {
    width: 100%;
    aspect-ratio: 1200 / 630;
    background: #090503;
    border-radius: 8px;
    overflow: hidden;
    position: relative;
    border: 1px solid #573a25;
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .og-preview-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
  }
  .og-preview-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #a89485;
    padding: 20px;
    text-align: center;
  }
  .og-overlay-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: rgba(18, 11, 8, 0.85);
    backdrop-filter: blur(4px);
    border: 1px solid rgba(245, 158, 11, 0.4);
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    color: #fbbf24;
    letter-spacing: 0.05em;
    display: flex;
    align-items: center;
    gap: 6px;
    z-index: 5;
  }
  .og-action-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 12px;
    margin-top: 16px;
  }
  .og-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 9px 14px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    border: none;
    transition: all 0.2s ease;
    text-decoration: none;
  }
  .og-btn-raea {
    background: linear-gradient(135deg, #b45309 0%, #d97706 100%);
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(217, 119, 6, 0.3);
  }
  .og-btn-raea:hover {
    background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
    transform: translateY(-1px);
  }
  .og-btn-upload {
    background: #292524;
    color: #f5f5f4;
    border: 1px solid #44403c;
  }
  .og-btn-upload:hover {
    background: #3c3633;
    border-color: #78716c;
  }
  .og-btn-media {
    background: #0f766e;
    color: #ffffff;
  }
  .og-btn-media:hover {
    background: #14b8a6;
  }
  .og-btn-remove {
    background: rgba(220, 38, 38, 0.15);
    color: #f87171;
    border: 1px solid rgba(220, 38, 38, 0.4);
  }
  .og-btn-remove:hover {
    background: #dc2626;
    color: #ffffff;
  }
</style>
@endsection

@section('content')
<form id="seoSettingsForm" action="{{ route('admin.updateSeoSettings') }}" method="POST" enctype="multipart/form-data">
  @csrf

  <div class="seo-container">
    <!-- Sidebar Navigation for Major Pages -->
    <aside class="page-nav-list">
      <div style="font-size: 11px; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; color: var(--gold); padding: 8px 12px 12px;">
        Major Website Pages ({{ count($availablePages) }})
      </div>
      @foreach($availablePages as $key => $page)
        @php
          $entry = $pageSeos->get($key);
          $hasOgImage = !empty($entry?->og_image);
        @endphp
        <div class="page-nav-item {{ $loop->first ? 'active' : '' }}" onclick="switchSeoTab('{{ $key }}', this)" id="tab-nav-{{ $key }}">
          <div>
            <div>{{ $page['title'] }}</div>
            <div class="url-badge">{{ $page['url'] }}</div>
          </div>
          <div style="display: flex; align-items: center; gap: 4px;">
            @if($hasOgImage)
              <span style="font-size: 11px;" title="Custom/RAEA Social Image Active">🖼️</span>
            @endif
            @if(!empty($entry->meta_title) && !empty($entry->meta_description))
              <span style="display: inline-flex; align-items: center;" title="SEO Configured">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              </span>
            @else
              <span style="font-size: 10px; color: #d97706;" title="Using Defaults">Default</span>
            @endif
          </div>
        </div>
      @endforeach
    </aside>

    <!-- Main Content Panes for Each Page -->
    <main>
      @foreach($availablePages as $key => $page)
        @php
          $entry = $pageSeos->get($key);
          $defaultTitle = $page['title'] . ' | ' . ($orgSettings->org_name ?? 'Shri Kamadgiri Peetham Sewa Trust');
          $defaultDesc = 'Explore ' . $page['title'] . ' at Shri Kamadgiri Peetham Sewa Trust, Chitrakoot Dham. Spiritual guidance, Vedic preservation, and sacred heritage.';
          $currentTitle = $entry?->meta_title ?? $defaultTitle;
          $currentDesc = $entry?->meta_description ?? $defaultDesc;
          $currentUrl = $entry?->canonical_url ?: url($page['url']);
          $raeaImage = $page['raea_image'] ?? ('/images/og/og-' . str_replace('_', '-', $key) . '.png');
          $activeOgImage = $entry?->og_image ?: $raeaImage;
          $hasCustomImage = !empty($entry?->og_image);
        @endphp

        <div class="seo-pane {{ $loop->first ? 'active' : '' }}" id="pane-{{ $key }}">
          <div class="seo-card">
            <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--line); padding-bottom: 14px; margin-bottom: 22px;">
              <div>
                <h2 style="font-size: 20px; font-weight: 800; color: var(--deep); margin: 0 0 4px;">{{ $page['title'] }}</h2>
                <span style="font-size: 12px; color: var(--brown);">Target URL: <code style="background: var(--sand); padding: 2px 6px; border-radius: 4px;">{{ $page['url'] }}</code></span>
              </div>
              <div style="display: flex; gap: 8px;">
                <a href="{{ url($page['url']) }}" target="_blank" class="action-btn" style="font-size: 11px; padding: 6px 12px; background: var(--sand); color: var(--deep);">
                  View Live Page &nearr;
                </a>
              </div>
            </div>

            <!-- ========================================================
                 FEATURED SOCIAL SHARE / OPEN GRAPH IMAGE STUDIO
                 ======================================================== -->
            <div class="social-card-studio">
              <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
                <div>
                  <h3 style="font-size: 15px; font-weight: 800; color: #fbbf24; margin: 0 0 2px; display: flex; align-items: center; gap: 8px;">
                    <span>✨ Social Share Featured Image (Open Graph / Twitter Card)</span>
                  </h3>
                  <span style="font-size: 12px; color: #d6d3d1;">
                    Display dimensions: <strong>1200 &times; 630 px</strong> (Standard for WhatsApp, Facebook, Twitter, LinkedIn & iMessage).
                  </span>
                </div>
                <div id="og-badge-status-{{ $key }}" style="font-size: 11px; padding: 4px 10px; border-radius: 999px; font-weight: 700; background: rgba(245, 158, 11, 0.2); color: #fde68a; border: 1px solid rgba(245, 158, 11, 0.4);">
                  {{ $hasCustomImage ? '🎨 Custom Image Active' : '🌟 3D RAEA Render Active' }}
                </div>
              </div>

              <!-- Live Card Preview -->
              <div class="og-preview-frame">
                <div class="og-overlay-badge">
                  <span>🚩</span>
                  <span id="og-preview-badge-text-{{ $key }}">{{ $page['title'] }} • Social Card</span>
                </div>
                <img src="{{ $activeOgImage ? asset(ltrim($activeOgImage, '/')) : asset('assets/uploads/temple-lotus-logo.png') }}"
                     id="og-preview-img-{{ $key }}"
                     class="og-preview-img"
                     alt="{{ $page['title'] }} Featured Social Share Image"
                     onerror="this.src='{{ asset('assets/uploads/temple-lotus-logo.png') }}';">
              </div>

              <!-- Image Configuration Actions -->
              <div class="og-action-grid">
                <!-- 1. Use 3D RAEA Graphic -->
                <button type="button"
                        class="og-btn og-btn-raea"
                        onclick="applyRaeaGraphic('{{ $key }}', '{{ $raeaImage }}')"
                        title="Reset to high-fidelity 3D RAEA composition">
                  <span>✨ 3D RAEA Graphic</span>
                </button>

                <!-- 2. Upload Custom Image -->
                <label class="og-btn og-btn-upload" style="margin: 0; cursor: pointer;">
                  <span>📤 Upload Image</span>
                  <input type="file"
                         name="pages[{{ $key }}][og_image_file]"
                         id="og-file-input-{{ $key }}"
                         accept="image/png,image/jpeg,image/webp,image/svg+xml"
                         style="display: none;"
                         onchange="handleOgFileUpload(this, '{{ $key }}')">
                </label>

                <!-- 3. Reuse from Media Gallery -->
                <button type="button"
                        class="og-btn og-btn-media"
                        onclick="openUniversalMediaPicker({ targetInput: '#og-image-input-{{ $key }}', onSelect: function(url) { updateOgPreview('{{ $key }}', url); } })">
                  <span>🖼️ Media Gallery</span>
                </button>

                <!-- 4. Remove Image -->
                <button type="button"
                        class="og-btn og-btn-remove"
                        onclick="removeOgImage('{{ $key }}')"
                        title="Remove featured image and fallback to default">
                  <span>🗑️ Remove Image</span>
                </button>
              </div>

              <!-- Direct Image URL / Path Input & Removal Flag -->
              <div style="margin-top: 14px;">
                <label class="form-label" style="font-size: 11px; color: #d6d3d1; margin-bottom: 4px; display: block;">
                  Image Asset Path or External URL
                </label>
                <div style="display: flex; gap: 8px;">
                  <input type="text"
                         name="pages[{{ $key }}][og_image_url]"
                         id="og-image-input-{{ $key }}"
                         value="{{ $entry?->og_image ?? $raeaImage }}"
                         class="form-input"
                         style="flex: 1; background: #292524; color: #f5f5f4; border-color: #44403c; font-size: 13px;"
                         placeholder="/images/og/og-{{ str_replace('_', '-', $key) }}.png or https://..."
                         oninput="updateOgPreview('{{ $key }}', this.value)">
                  
                  <!-- Instant AJAX Save Button -->
                  <button type="button"
                          onclick="ajaxSaveFeaturedImage('{{ $key }}')"
                          class="action-btn primary"
                          style="font-size: 11px; padding: 7px 14px; white-space: nowrap;">
                    ⚡ Save Image Now
                  </button>
                </div>
                <!-- Hidden remove flag -->
                <input type="hidden" name="pages[{{ $key }}][remove_og_image]" id="remove-og-flag-{{ $key }}" value="0">
              </div>
            </div>

            <!-- ========================================================
                 GOOGLE SEARCH RESULT SNIPPET (SERP) SIMULATION
                 ======================================================== -->
            <div style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--brown); margin-bottom: 8px;">
              Google Search Result Snippet (Real-Time SERP Simulation)
            </div>
            <div class="serp-box">
              <div class="serp-url">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="#5f6368"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
                <span>{{ url('/') }} &rsaquo; {{ ltrim($page['url'], '/') }}</span>
              </div>
              <div class="serp-title" id="serp-title-{{ $key }}">{{ $currentTitle }}</div>
              <div class="serp-snippet" id="serp-desc-{{ $key }}">{{ $currentDesc }}</div>
            </div>

            <input type="hidden" name="pages[{{ $key }}][page_title]" value="{{ $page['title'] }}">

            <!-- Meta Title -->
            <div class="form-group" style="margin-bottom: 20px;">
              <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
                <label class="form-label" style="margin: 0;">Page Meta Title (Title Tag)</label>
                <span class="counter-badge" id="title-counter-{{ $key }}">{{ mb_strlen($currentTitle) }} / 60 Chars</span>
              </div>
              <input type="text"
                     name="pages[{{ $key }}][meta_title]"
                     id="meta-title-input-{{ $key }}"
                     value="{{ $entry?->meta_title ?? $defaultTitle }}"
                     class="form-input"
                     style="width: 100%; font-size: 14px; font-weight: 600;"
                     oninput="updateSerpPreview('{{ $key }}')">
              <span class="form-subtext">Recommended between 50 and 60 characters for maximum search visibility without truncation.</span>
            </div>

            <!-- Meta Description -->
            <div class="form-group" style="margin-bottom: 20px;">
              <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
                <label class="form-label" style="margin: 0;">Meta Description</label>
                <span class="counter-badge" id="desc-counter-{{ $key }}">{{ mb_strlen($currentDesc) }} / 160 Chars</span>
              </div>
              <textarea name="pages[{{ $key }}][meta_description]"
                        id="meta-desc-input-{{ $key }}"
                        rows="3"
                        class="form-input"
                        style="width: 100%; line-height: 1.5; font-size: 13px;"
                        oninput="updateSerpPreview('{{ $key }}')">{{ $entry?->meta_description ?? $defaultDesc }}</textarea>
              <span class="form-subtext">Optimal length is 140–160 characters. Provide an engaging call-to-action summary.</span>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
              <!-- Keywords -->
              <div class="form-group">
                <label class="form-label">Focus & Meta Keywords</label>
                <input type="text"
                       name="pages[{{ $key }}][keywords]"
                       value="{{ $entry?->meta_keywords ?? ($entry?->keywords ?? '') }}"
                       placeholder="e.g. Chitrakoot, Seva Trust, Ramghat, Vedic Education"
                       class="form-input"
                       style="width: 100%;">
                <span class="form-subtext">Comma-separated focus terms for discovery.</span>
              </div>

              <!-- Canonical URL -->
              <div class="form-group">
                <label class="form-label">Canonical URL Tag</label>
                <input type="url"
                       name="pages[{{ $key }}][canonical_url]"
                       value="{{ $entry?->canonical_url }}"
                       placeholder="{{ url($page['url']) }}"
                       class="form-input"
                       style="width: 100%;">
                <span class="form-subtext">Leave empty to default to {{ url($page['url']) }}.</span>
              </div>
            </div>

            <div style="margin-bottom: 10px;">
              <!-- Robots Tag -->
              <div class="form-group">
                <label class="form-label">Search Crawlers (Robots Tag)</label>
                <select name="pages[{{ $key }}][robots]" class="form-input" style="width: 100%; max-width: 320px;">
                  <option value="index, follow" {{ ($entry?->robots ?? 'index, follow') === 'index, follow' ? 'selected' : '' }}>index, follow (Recommended)</option>
                  <option value="noindex, follow" {{ ($entry?->robots) === 'noindex, follow' ? 'selected' : '' }}>noindex, follow</option>
                  <option value="noindex, nofollow" {{ ($entry?->robots) === 'noindex, nofollow' ? 'selected' : '' }}>noindex, nofollow</option>
                </select>
                <span class="form-subtext">Instructions for search engine crawlers.</span>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </main>
  </div>
</form>

<script>
  function switchSeoTab(key, el) {
    document.querySelectorAll('.page-nav-item').forEach(item => item.classList.remove('active'));
    document.querySelectorAll('.seo-pane').forEach(pane => pane.classList.remove('active'));

    if (el) el.classList.add('active');
    const targetPane = document.getElementById('pane-' + key);
    if (targetPane) targetPane.classList.add('active');
  }

  function updateSerpPreview(key) {
    const titleVal = document.getElementById('meta-title-input-' + key).value;
    const descVal = document.getElementById('meta-desc-input-' + key).value;

    const serpTitle = document.getElementById('serp-title-' + key);
    const serpDesc = document.getElementById('serp-desc-' + key);
    const titleCounter = document.getElementById('title-counter-' + key);
    const descCounter = document.getElementById('desc-counter-' + key);

    if (serpTitle) serpTitle.textContent = titleVal || 'Page Title';
    if (serpDesc) serpDesc.textContent = descVal || 'Page Description';

    const tLen = titleVal.length;
    if (titleCounter) {
      titleCounter.textContent = `${tLen} / 60 Chars`;
      titleCounter.className = 'counter-badge ' + (tLen > 65 ? 'danger' : (tLen >= 45 && tLen <= 60 ? 'good' : 'warning'));
    }

    const dLen = descVal.length;
    if (descCounter) {
      descCounter.textContent = `${dLen} / 160 Chars`;
      descCounter.className = 'counter-badge ' + (dLen > 165 ? 'danger' : (dLen >= 130 && dLen <= 160 ? 'good' : 'warning'));
    }
  }

  function updateOgPreview(key, url) {
    const previewImg = document.getElementById('og-preview-img-' + key);
    const inputEl = document.getElementById('og-image-input-' + key);
    const badgeStatus = document.getElementById('og-badge-status-' + key);
    const removeFlag = document.getElementById('remove-og-flag-' + key);

    if (removeFlag) removeFlag.value = '0';
    if (inputEl && inputEl.value !== url) inputEl.value = url;

    if (previewImg) {
      if (!url || url.trim() === '') {
        previewImg.src = '{{ asset("assets/uploads/temple-lotus-logo.png") }}';
        if (badgeStatus) {
          badgeStatus.textContent = '⚪ No Image (Default Fallback)';
          badgeStatus.style.borderColor = 'rgba(239, 68, 68, 0.4)';
          badgeStatus.style.color = '#fca5a5';
        }
      } else {
        const fullSrc = (url.startsWith('http://') || url.startsWith('https://')) ? url : '{{ url('/') }}/' + url.replace(/^\/+/, '');
        previewImg.src = fullSrc;
        if (badgeStatus) {
          if (url.includes('/og/og-')) {
            badgeStatus.textContent = '🌟 3D RAEA Render Active';
            badgeStatus.style.borderColor = 'rgba(245, 158, 11, 0.4)';
            badgeStatus.style.color = '#fde68a';
          } else {
            badgeStatus.textContent = '🎨 Custom Image Active';
            badgeStatus.style.borderColor = 'rgba(16, 185, 129, 0.4)';
            badgeStatus.style.color = '#a7f3d0';
          }
        }
      }
    }
  }

  function applyRaeaGraphic(key, raeaPath) {
    updateOgPreview(key, raeaPath);
    if (typeof showToast === 'function') {
      showToast('Restored 3D RAEA Graphic for ' + key, 'success');
    }
  }

  function handleOgFileUpload(input, key) {
    if (input.files && input.files[0]) {
      const file = input.files[0];
      const reader = new FileReader();
      reader.onload = function(e) {
        const previewImg = document.getElementById('og-preview-img-' + key);
        if (previewImg) previewImg.src = e.target.result;

        const badgeStatus = document.getElementById('og-badge-status-' + key);
        if (badgeStatus) {
          badgeStatus.textContent = '📤 New File Selected (Save to upload)';
          badgeStatus.style.borderColor = 'rgba(59, 130, 246, 0.5)';
          badgeStatus.style.color = '#bfdbfe';
        }

        const inputEl = document.getElementById('og-image-input-' + key);
        if (inputEl) inputEl.value = file.name;

        const removeFlag = document.getElementById('remove-og-flag-' + key);
        if (removeFlag) removeFlag.value = '0';

        if (typeof showToast === 'function') {
          showToast('Image selected: ' + file.name + '. Click "Save" to finalize.', 'info');
        }
      };
      reader.readAsDataURL(file);
    }
  }

  function removeOgImage(key) {
    const inputEl = document.getElementById('og-image-input-' + key);
    const fileInput = document.getElementById('og-file-input-' + key);
    const removeFlag = document.getElementById('remove-og-flag-' + key);
    const previewImg = document.getElementById('og-preview-img-' + key);
    const badgeStatus = document.getElementById('og-badge-status-' + key);

    if (inputEl) inputEl.value = '';
    if (fileInput) fileInput.value = '';
    if (removeFlag) removeFlag.value = '1';

    if (previewImg) {
      previewImg.src = '{{ asset("assets/uploads/temple-lotus-logo.png") }}';
    }

    if (badgeStatus) {
      badgeStatus.textContent = '🗑️ Image Removed (Default Fallback)';
      badgeStatus.style.borderColor = 'rgba(239, 68, 68, 0.5)';
      badgeStatus.style.color = '#fca5a5';
    }

    if (typeof showToast === 'function') {
      showToast('Featured image cleared for ' + key + '. Click "Save" to persist.', 'info');
    }
  }

  function ajaxSaveFeaturedImage(key) {
    const inputEl = document.getElementById('og-image-input-' + key);
    const fileInput = document.getElementById('og-file-input-' + key);
    const removeFlag = document.getElementById('remove-og-flag-' + key);

    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('page_key', key);

    if (removeFlag && removeFlag.value === '1') {
      formData.append('action', 'remove');
    } else if (fileInput && fileInput.files && fileInput.files[0]) {
      formData.append('action', 'upload');
      formData.append('image_file', fileInput.files[0]);
    } else if (inputEl && inputEl.value.includes('/images/og/og-')) {
      formData.append('action', 'reset_raea');
    } else if (inputEl && inputEl.value.trim() !== '') {
      formData.append('action', 'set_url');
      formData.append('image_url', inputEl.value.trim());
    } else {
      formData.append('action', 'remove');
    }

    fetch('{{ route("admin.updatePageOgImage") }}', {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
      },
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        if (typeof showToast === 'function') {
          showToast(data.message || 'Image updated successfully!', 'success');
        }
        if (data.og_image) {
          updateOgPreview(key, data.og_image);
        } else {
          removeOgImage(key);
        }
      } else {
        if (typeof showToast === 'function') {
          showToast(data.message || 'Error updating image', 'error');
        }
      }
    })
    .catch(err => {
      if (typeof showToast === 'function') {
        showToast('Network error: ' + err.message, 'error');
      }
    });
  }
</script>
@endsection
