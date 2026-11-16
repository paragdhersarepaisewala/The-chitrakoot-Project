@extends('layouts.admin')

@section('title', 'All Pages SEO Settings | CMS Admin')
@section('header_title', 'All Pages SEO & Meta Manager')
@section('header_subtitle', 'Configure individual page titles, meta descriptions, focus keywords, canonical tags, and Open Graph previews across all 13 core website sections.')

@section('header_actions')
  <button type="button" onclick="document.getElementById('seoSettingsForm').submit()" class="action-btn primary" style="font-weight: 800; letter-spacing: 0.05em;">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
    SAVE ALL SEO METADATA &rarr;
  </button>
@endsection

@section('styles')
<style>
  .seo-container {
    display: grid;
    grid-template-columns: 280px 1fr;
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
</style>
@endsection

@section('content')
<form id="seoSettingsForm" action="{{ route('admin.updateSeoSettings') }}" method="POST">
  @csrf

  <div class="seo-container">
    <!-- Sidebar Navigation for Pages -->
    <aside class="page-nav-list">
      <div style="font-size: 11px; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; color: var(--gold); padding: 8px 12px 12px;">
        Core Website Pages (13)
      </div>
      @foreach($availablePages as $key => $page)
        @php
          $entry = $pageSeos->get($key);
        @endphp
        <div class="page-nav-item {{ $loop->first ? 'active' : '' }}" onclick="switchSeoTab('{{ $key }}', this)" id="tab-nav-{{ $key }}">
          <div>
            <div>{{ $page['title'] }}</div>
            <div class="url-badge">{{ $page['url'] }}</div>
          </div>
          @if(!empty($entry->meta_title) && !empty($entry->meta_description))
            <span style="display: inline-flex; align-items: center;" title="SEO Configured">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </span>
          @else
            <span style="font-size: 10px; color: #d97706;" title="Using Defaults">Default</span>
          @endif
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
        @endphp

        <div class="seo-pane {{ $loop->first ? 'active' : '' }}" id="pane-{{ $key }}">
          <div class="seo-card">
            <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--line); padding-bottom: 14px; margin-bottom: 22px;">
              <div>
                <h2 style="font-size: 20px; font-weight: 800; color: var(--deep); margin: 0 0 4px;">{{ $page['title'] }}</h2>
                <span style="font-size: 12px; color: var(--brown);">Target URL: <code style="background: var(--sand); padding: 2px 6px; border-radius: 4px;">{{ $page['url'] }}</code></span>
              </div>
              <a href="{{ url($page['url']) }}" target="_blank" class="action-btn" style="font-size: 11px; padding: 6px 12px; background: var(--sand); color: var(--deep);">
                View Live Page &nearr;
              </a>
            </div>

            <!-- Google Search Snippet Live Preview -->
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
                       value="{{ $entry?->keywords }}" 
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

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 20px;">
              <!-- OG Image URL -->
              <div class="form-group">
                <label class="form-label">Social Share / Open Graph Image URL</label>
                <div style="display: flex; gap: 8px;">
                  <input type="text" 
                         name="pages[{{ $key }}][og_image_url]" 
                         id="og-image-{{ $key }}"
                         value="{{ $entry?->og_image_url }}" 
                         placeholder="/images/chitrakoot-hero.webp or https://..." 
                         class="form-input" 
                         style="flex: 1;">
                  <button type="button" onclick="openUniversalMediaPicker({ targetInput: '#og-image-{{ $key }}' })" class="action-btn teal" style="padding: 8px 12px; font-size: 11px; white-space: nowrap;">
                    🖼️ Reuse Image
                  </button>
                </div>
                <span class="form-subtext">High-res 1200x630px image displayed when shared on WhatsApp, Facebook, or Twitter.</span>
              </div>

              <!-- Robots Tag -->
              <div class="form-group">
                <label class="form-label">Search Crawlers (Robots Tag)</label>
                <select name="pages[{{ $key }}][robots]" class="form-input" style="width: 100%;">
                  <option value="index, follow" {{ ($entry?->robots ?? 'index, follow') === 'index, follow' ? 'selected' : '' }}>index, follow (Recommended)</option>
                  <option value="noindex, follow" {{ ($entry?->robots) === 'noindex, follow' ? 'selected' : '' }}>noindex, follow</option>
                  <option value="noindex, nofollow" {{ ($entry?->robots) === 'noindex, nofollow' ? 'selected' : '' }}>noindex, nofollow</option>
                </select>
                <span class="form-subtext">Instructions for Googlebot.</span>
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
</script>
@endsection
