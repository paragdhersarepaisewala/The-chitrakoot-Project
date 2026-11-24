@extends('layouts.admin')

@section('title', 'Homepage Layout & Dynamic Sections Editor | CMS Admin')

@section('styles')
  <style>
    .editor-section-card {
      background: #ffffff;
      border: 1px solid rgba(180, 83, 9, 0.16);
      border-radius: 16px;
      padding: 28px;
      margin-bottom: 28px;
      box-shadow: 0 4px 20px rgba(130, 70, 20, 0.04);
      position: relative;
      transition: all 0.2s ease;
    }

    .editor-section-card.section-disabled {
      opacity: 0.65;
      background: #fbf9f6;
      border-style: dashed;
    }

    .section-badge-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding-bottom: 16px;
      margin-bottom: 24px;
      border-bottom: 2px solid #D95D0F;
      flex-wrap: wrap;
      gap: 12px;
    }

    .section-badge-title {
      font-size: 16px;
      font-weight: 800;
      color: #2B1A12;
      letter-spacing: .02em;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .section-toggle-label {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: #FAF4ED;
      padding: 6px 14px;
      border-radius: 9999px;
      border: 1px solid rgba(180, 83, 9, 0.25);
      cursor: pointer;
      font-size: 12px;
      font-weight: 700;
      color: #2B1A12;
      user-select: none;
    }

    .section-toggle-label input[type="checkbox"] {
      width: 17px;
      height: 17px;
      accent-color: #D95D0F;
      cursor: pointer;
    }

    .form-label {
      display: block;
      font-size: 11px;
      font-weight: 800;
      letter-spacing: .12em;
      text-transform: uppercase;
      color: #7A4B29;
      margin-bottom: 8px;
    }

    .form-label-sub {
      font-size: 10px;
      font-weight: 500;
      text-transform: none;
      color: #7A4B29;
      opacity: 0.8;
      margin-top: 3px;
      display: block;
    }

    .form-input,
    .form-select,
    .form-textarea {
      width: 100%;
      padding: 11px 14px;
      border: 1px solid rgba(180, 83, 9, 0.2);
      border-radius: 10px;
      font-size: 13.5px;
      background: #FAF4ED;
      color: #2B1A12;
      transition: all .2s ease;
      box-sizing: border-box;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
      outline: none;
      border-color: #D95D0F;
      background: #ffffff;
      box-shadow: 0 0 0 3px rgba(217, 93, 15, 0.16);
    }

    .media-preview-box {
      border-radius: 12px;
      overflow: hidden;
      background: #1c1511;
      border: 1px solid rgba(180, 83, 9, 0.2);
      position: relative;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
    }

    .media-preview-box img,
    .media-preview-box video {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .btn-capsule {
      padding: 10px 24px;
      border-radius: 9999px;
      font-size: 13px;
      font-weight: 700;
      cursor: pointer;
      border: none;
      transition: all .2s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .btn-save-sticky {
      position: sticky;
      bottom: 24px;
      z-index: 100;
      background: rgba(43, 26, 18, 0.95);
      backdrop-filter: blur(12px);
      border: 1px solid rgba(180, 83, 9, 0.35);
      border-radius: 9999px;
      padding: 12px 28px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
      margin-top: 30px;
    }

    .section-nav-quick {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-bottom: 24px;
      padding: 14px;
      background: #FAF4ED;
      border-radius: 12px;
      border: 1px solid rgba(180, 83, 9, 0.18);
    }

    .section-nav-pill {
      padding: 6px 12px;
      border-radius: 9999px;
      background: #ffffff;
      border: 1px solid rgba(180, 83, 9, 0.2);
      color: #2B1A12;
      font-size: 11.5px;
      font-weight: 700;
      text-decoration: none;
      transition: all 0.2s;
    }

    .section-nav-pill:hover {
      background: #D95D0F;
      color: #ffffff;
      border-color: #D95D0F;
    }
  </style>
@endsection

@section('content')

  <!-- Masthead Banner -->
  <div
    style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid rgba(180, 83, 9, 0.16);">
    <div>
      <h1 style="font-size: 26px; font-weight: 700; font-family: 'Noto Serif', serif; color: #2B1A12; margin: 0;">
        Homepage Layout &amp; Components Editor — Dynamic Sections & Minute Details
      </h1>
      <p style="font-size: 13px; color: #7A4B29; margin-top: 4px;">
        Complete control over every minute detail, image, text, toggle, timing, hawan packages, missions, and donation
        settings.
      </p>
    </div>

    <div style="display: flex; gap: 10px; align-items: center;">
      <a href="{{ url('/') }}" target="_blank" class="action-btn"
        style="background: linear-gradient(to right, #D95D0F, #EA580C); color: #fff; border: none; padding: 10px 20px; border-radius: 9999px; font-weight: 700; text-decoration: none; font-size: 13px;">
        &larr; OPEN LIVE HOMEPAGE
      </a>
    </div>
  </div>

  <!-- Quick Navigation Bar -->
  <div class="section-nav-quick">
    <span
      style="font-size: 11px; font-weight: 800; color: #B45309; text-transform: uppercase; letter-spacing: 0.08em; display: flex; align-items: center; margin-right: 6px;">Jump
      to:</span>
    <a href="#sec-hero" class="section-nav-pill">1. Hero Sanctum</a>
    <a href="#sec-aarti" class="section-nav-pill">2. Aarti & Hawan</a>
    <a href="#sec-about" class="section-nav-pill">3. Trust About & Heritage</a>
    <a href="#sec-env" class="section-nav-pill" style="border-color: rgba(45, 106, 79, 0.4); color: #2D6A4F;">3B.
      Environmental Campaign</a>
    <a href="#sec-missions" class="section-nav-pill">4. Dharma Missions</a>
    <a href="#sec-donation" class="section-nav-pill">5. Donate & Giving Hub</a>
    <a href="#sec-pooja" class="section-nav-pill">6. Online Pooja & Sankalp</a>
    <a href="#sec-places" class="section-nav-pill">7. Sacred Places</a>
    <a href="#sec-events" class="section-nav-pill">8. Events & Broadcast</a>
    <a href="#sec-custom" class="section-nav-pill">9. Extra Showcase Section</a>
  </div>

  <form action="{{ route('admin.updateHomepageLayout') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="is_layout_form" value="1">

    @php
      $selectedPoojaIds = json_decode($settings['selected_pooja_ids'] ?? '[]', true) ?: [];
      $selectedSevaIds = json_decode($settings['selected_seva_ids'] ?? '[]', true) ?: [];
      $selectedDestIds = json_decode($settings['selected_destination_ids'] ?? '[]', true) ?: [];
      $selectedCauseIds = json_decode($settings['selected_cause_ids'] ?? '[]', true) ?: [];
    @endphp

    <!-- ==========================================
         SECTION 1: HERO SECTION
         ========================================== -->
    <div class="editor-section-card {{ ($settings['section_hero_enabled'] ?? '1') === '0' ? 'section-disabled' : '' }}"
      id="sec-hero">
      <div class="section-badge-header">
        <div class="section-badge-title">
          <span>1. Hero Section (Hero Cinematic Media, Temple Sanctum Viewport, Video, Chime & Narrative)</span>
        </div>
        <label class="section-toggle-label">
          <input type="checkbox" name="section_hero_enabled" value="1" {{ ($settings['section_hero_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
          <span>Section Active on Homepage</span>
        </label>
      </div>

      <!-- Row 1: Text Fields -->
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 22px;">
        <div>
          <label class="form-label">
            HERO EYEBROW KICKER
            <span class="form-label-sub">Displayed in gold badge above headline (e.g. तपोभूमि चित्रकूट धाम)</span>
          </label>
          <input type="text" name="hero_eyebrow" value="{{ $settings['hero_eyebrow'] ?? 'तपोभूमि चित्रकूट धाम' }}"
            class="form-input">
        </div>

        <div>
          <label class="form-label">
            MAIN HEADLINE (HINDI / ENGLISH)
            <span class="form-label-sub">Primary prominent page headline</span>
          </label>
          <input type="text" name="hero_headline"
            value="{{ $settings['hero_headline'] ?? 'आस्था, संस्कृति और चित्रकूट का पावन गौरव' }}" class="form-input"
            style="font-family: 'Noto Serif', serif; font-weight: 700;">
        </div>
      </div>

      <!-- Row 2: Subtitle & Narrative & Bell Chime -->
      <div style="display: grid; grid-template-columns: 1fr 1fr 180px; gap: 20px; margin-bottom: 28px;">
        <div>
          <label class="form-label">
            HERO SUBTITLE / TAGLINE
            <span class="form-label-sub">Refined serif text</span>
          </label>
          <textarea name="hero_subtitle" rows="3"
            class="form-textarea">{{ $settings['hero_subtitle'] ?? 'मनोकामना सिद्धि प्रदाता श्री कामदगिरि प्रभु' }}</textarea>
        </div>

        <div>
          <label class="form-label">
            NARRATIVE STORY PARAGRAPH
            <span class="form-label-sub">Editorial story introducing the holy land of Chitrakoot</span>
          </label>
          <textarea name="hero_story" rows="3"
            class="form-textarea">{{ $settings['hero_story'] ?? 'मंदाकिनी की शांत धार, कामदगिरि के पावन शिखर और युगों-युगों से प्रवाहित तपोभूमि की दिव्यता — अनुभव करें श्रद्धा और समता की पावन अनुभूति।' }}</textarea>
        </div>

        <div>
          <label class="form-label">
            TEMPLE BELL CHIME (घंटा ध्वनि) & AUDIO SOUND
            <span class="form-label-sub">Configurable chime audio (MP3/WAV/OGG) and counter</span>
          </label>
          <div style="display: flex; gap: 12px; margin-bottom: 8px;">
            <div style="width: 130px; flex-shrink: 0;">
              <label
                style="font-size: 11px; color: #7A4B29; font-weight: 700; margin-bottom: 3px; display: block;">Default
                Count:</label>
              <input type="number" name="bell_default_count" value="{{ $settings['bell_default_count'] ?? 108 }}"
                class="form-input" style="font-size: 15px; font-weight: 700;">
            </div>
            <div style="flex: 1;">
              <label style="font-size: 11px; color: #7A4B29; font-weight: 700; margin-bottom: 3px; display: block;">Audio
                URL / Path:</label>
              <input type="text" name="hero_bell_audio_url" value="{{ $settings['hero_bell_audio_url'] ?? '' }}"
                class="form-input" placeholder="Audio URL or upload below">
            </div>
          </div>
          <div>
            <label style="font-size: 11px; color: #7A4B29; font-weight: 700; margin-bottom: 3px; display: block;">Upload
              Bell Audio File (.mp3, .wav, .ogg):</label>
            <input type="file" name="hero_bell_audio_file" accept="audio/*,.mp3,.wav,.ogg,.m4a" class="form-input"
              style="background: #fff; padding: 7px 10px;">
          </div>
          @if(!empty($settings['hero_bell_audio_url']))
            <div
              style="margin-top: 8px; display: flex; align-items: center; gap: 10px; padding: 8px 12px; background: rgba(217, 93, 15, 0.08); border-radius: 8px; border: 1px solid rgba(217, 93, 15, 0.2);">
              <span style="font-size: 11.5px; font-weight: 700; color: #92400E;">Preview:</span>
              <audio controls style="height: 30px; max-width: 260px;">
                <source
                  src="{{ str_starts_with($settings['hero_bell_audio_url'], 'http') ? $settings['hero_bell_audio_url'] : asset($settings['hero_bell_audio_url']) }}">
              </audio>
            </div>
          @else
            <div style="margin-top: 6px; font-size: 11px; color: #7A4B29; font-style: italic;">
              * Automatic fallback: High-fidelity Web Audio API temple brass bell chime will synthesize if no file is
              uploaded.
            </div>
          @endif
        </div>
      </div>

      <!-- Row 3: Action Buttons -->
      <div
        style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 28px; padding: 18px; border-radius: 12px; background: #FAF4ED; border: 1px solid rgba(180, 83, 9, 0.2);">
        <div>
          <label class="form-label">PRIMARY BUTTON TEXT & LINK</label>
          <input type="text" name="hero_primary_btn_text"
            value="{{ $settings['hero_primary_btn_text'] ?? 'दान एवं सहयोग' }}" class="form-input"
            style="margin-bottom: 8px;">
          <input type="text" name="hero_primary_btn_link" value="{{ $settings['hero_primary_btn_link'] ?? '/donate' }}"
            class="form-input" placeholder="e.g. /donate">
        </div>

        <div>
          <label class="form-label">SECONDARY BUTTON TEXT & LINK</label>
          <input type="text" name="hero_secondary_btn_text"
            value="{{ $settings['hero_secondary_btn_text'] ?? 'पावन दर्शन' }}" class="form-input"
            style="margin-bottom: 8px;">
          <input type="text" name="hero_secondary_btn_link"
            value="{{ $settings['hero_secondary_btn_link'] ?? '/chitrakoot' }}" class="form-input"
            placeholder="e.g. /chitrakoot">
        </div>

        <div>
          <label class="form-label">THIRD BUTTON TEXT & LINK</label>
          <input type="text" name="hero_third_btn_text" value="{{ $settings['hero_third_btn_text'] ?? 'पूजा संकल्प' }}"
            class="form-input" style="margin-bottom: 8px;">
          <input type="text" name="hero_third_btn_link" value="{{ $settings['hero_third_btn_link'] ?? '/pooja' }}"
            class="form-input" placeholder="e.g. /pooja">
        </div>
      </div>

      <!-- Row 4: Background Video & Photo Uploads -->
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
        <div style="padding: 16px; background: #FAF4ED; border-radius: 12px; border: 1px solid rgba(180, 83, 9, 0.2);">
          <label class="form-label">CINEMATIC BACKGROUND VIDEO</label>
          <div style="display: flex; gap: 8px; margin-bottom: 8px;">
            <input type="text" name="hero_video_url" id="hero_video_url" value="{{ $settings['hero_video_url'] ?? '' }}"
              class="form-input" placeholder="Video URL or select from library">
            <button type="button" onclick="openUniversalMediaPicker({ targetInput: '#hero_video_url' })"
              class="action-btn teal" style="padding: 7px 12px; font-size: 11px; white-space: nowrap;"><x-icon name="image" size="12" /> Reuse</button>
          </div>
          <label style="font-size: 11px; color: #7A4B29; font-weight: 700; margin-bottom: 4px; display: block;">Upload
            Video File (MP4/WebM):</label>
          <input type="file" name="hero_video_file" accept="video/mp4,video/webm" class="form-input"
            style="background: #fff; padding: 8px;">
        </div>

        <div style="padding: 16px; background: #FAF4ED; border-radius: 12px; border: 1px solid rgba(180, 83, 9, 0.2);">
          <label class="form-label">BACKGROUND POSTER / FALLBACK IMAGE</label>
          <div style="display: flex; gap: 8px; margin-bottom: 8px;">
            <input type="text" name="hero_photo_url" id="hero_photo_url" value="{{ $settings['hero_photo_url'] ?? '' }}"
              class="form-input" placeholder="Image URL or pick from gallery">
            <button type="button" onclick="openUniversalMediaPicker({ targetInput: '#hero_photo_url' })"
              class="action-btn teal" style="padding: 7px 12px; font-size: 11px; white-space: nowrap;"><x-icon name="image" size="12" /> Reuse</button>
          </div>
          <label style="font-size: 11px; color: #7A4B29; font-weight: 700; margin-bottom: 4px; display: block;">Upload
            Poster File (JPG/PNG/WebP):</label>
          <input type="file" name="hero_photo_file" accept="image/*" class="form-input"
            style="background: #fff; padding: 8px;">
        </div>
      </div>
    </div>

    <!-- ==========================================
         SECTION 2: AARTI & HAWAN TIMINGS STRIP
         ========================================== -->
    <div class="editor-section-card {{ ($settings['section_aarti_enabled'] ?? '1') === '0' ? 'section-disabled' : '' }}"
      id="sec-aarti">
      <div class="section-badge-header">
        <div class="section-badge-title">
          <span>2. Daily Aarti, Hawan & Temple Timings Schedule</span>
        </div>
        <label class="section-toggle-label">
          <input type="checkbox" name="section_aarti_enabled" value="1" {{ ($settings['section_aarti_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
          <span>Section Active on Homepage</span>
        </label>
      </div>

      <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 20px;">
        <div>
          <label class="form-label">AARTI STRIP MAIN TITLE</label>
          <input type="text" name="aarti_strip_title"
            value="{{ $settings['aarti_strip_title'] ?? 'नित्य आरती व दर्शन समय चक्र • Kamadgiri Dham Timings' }}"
            class="form-input" style="font-family: 'Noto Serif', serif; font-weight: 700;">
        </div>
        <div>
          <label class="form-label">AARTI STRIP SUBTITLE / TAG</label>
          <input type="text" name="aarti_strip_subtitle"
            value="{{ $settings['aarti_strip_subtitle'] ?? 'चित्रकूट धाम में नित्य महाआरती व गर्भगृह दर्शन समय सारणी' }}"
            class="form-input">
        </div>
      </div>

      @php
        $rawSlots = $settings['aarti_slots_json'] ?? '';
        $existingAartiSlots = [];
        if (!empty($rawSlots)) {
          $existingAartiSlots = json_decode($rawSlots, true) ?: [];
        }
        if (empty($existingAartiSlots)) {
          $existingAartiSlots = [
            ['name' => $settings['aarti_1_name'] ?? 'प्रातः मंगला आरती', 'time' => $settings['aarti_1_time'] ?? '05:00 AM', 'desc' => $settings['aarti_1_desc'] ?? 'कामदगिरि गर्भगृह', 'is_live_pulse' => false],
            ['name' => $settings['aarti_2_name'] ?? 'शृंगार व बालभोग', 'time' => $settings['aarti_2_time'] ?? '08:30 AM', 'desc' => $settings['aarti_2_desc'] ?? 'कामदगिरि मंदिर', 'is_live_pulse' => false],
            ['name' => $settings['aarti_3_name'] ?? 'राजभोग महाआरती', 'time' => $settings['aarti_3_time'] ?? '12:00 PM', 'desc' => $settings['aarti_3_desc'] ?? 'अन्नकूट व भोग अर्पण', 'is_live_pulse' => false],
            ['name' => $settings['aarti_4_name'] ?? 'संध्या महाआरती', 'time' => $settings['aarti_4_time'] ?? '06:30 PM', 'desc' => $settings['aarti_4_desc'] ?? 'रामघाट मंदाकिनी तट', 'is_live_pulse' => true],
            ['name' => $settings['aarti_5_name'] ?? 'शयन आरती', 'time' => $settings['aarti_5_time'] ?? '09:00 PM', 'desc' => $settings['aarti_5_desc'] ?? 'कपाट विश्राम', 'is_live_pulse' => false],
            ['name' => $settings['aarti_6_name'] ?? 'नित्य वैदिक हवन', 'time' => $settings['aarti_6_time'] ?? '07:30 AM', 'desc' => $settings['aarti_6_desc'] ?? 'यज्ञशाला चित्रकूट', 'is_live_pulse' => false],
          ];
        }
      @endphp

      <div
        style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; flex-wrap: wrap; gap: 8px;">
        <div>
          <strong style="color: #2B1A12; font-size: 13.5px; display: block;">Dynamic Aarti & Temple Timings Slots</strong>
          <span style="font-size: 11px; color: #7A4B29;">Devotees see these cards in order on the homepage strip. Add new
            slots or remove unwanted slots as needed.</span>
        </div>
        <button type="button" onclick="addNewAartiSlot()" class="action-btn"
          style="background: #FAF4ED; color: #B45309; border: 1.5px solid rgba(180,83,9,0.35); font-size: 11.5px; font-weight: 700; padding: 7px 14px; cursor: pointer;">
          + Add New Time Slot (नया स्लॉट जोड़ें)
        </button>
      </div>

      <!-- Dynamic Aarti Time Slots Grid -->
      <div id="aartiSlotsContainer"
        style="display: grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 14px; margin-bottom: 24px;">
        @foreach($existingAartiSlots as $idx => $slot)
          <div class="aarti-slot-card"
            style="background: #FAF4ED; border: 1px solid {{ !empty($slot['is_live_pulse']) ? 'rgba(217, 93, 15, 0.45)' : 'rgba(180, 83, 9, 0.2)' }}; border-radius: 12px; padding: 12px; display: flex; flex-direction: column; justify-content: space-between; position: relative;">
            <div>
              <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                <span class="slot-idx-label"
                  style="font-size: 10.5px; font-weight: 800; color: #D95D0F; text-transform: uppercase;">SLOT
                  #{{ $idx + 1 }}</span>
                <button type="button" onclick="removeAartiSlot(this)"
                  style="background: #fee2e2; border: 1px solid #f87171; color: #991b1b; font-size: 11px; font-weight: 700; border-radius: 4px; padding: 2px 7px; cursor: pointer;"
                  title="Remove this slot">Remove &times;</button>
              </div>

              <label class="form-label" style="font-size: 10px; margin-bottom: 2px;">NAME (नाम)</label>
              <input type="text" name="aarti_slots[{{ $idx }}][name]" value="{{ $slot['name'] ?? '' }}"
                class="form-input slot-name-input" style="margin-bottom: 6px; font-size: 12px; padding: 6px 8px;"
                placeholder="उदा: प्रातः मंगला आरती">

              <label class="form-label" style="font-size: 10px; margin-bottom: 2px;">TIME (समय)</label>
              <input type="text" name="aarti_slots[{{ $idx }}][time]" value="{{ $slot['time'] ?? '' }}"
                class="form-input slot-time-input" style="margin-bottom: 6px; font-size: 12px; padding: 6px 8px;"
                placeholder="05:00 AM">

              <label class="form-label" style="font-size: 10px; margin-bottom: 2px;">LOCATION / DESC (स्थान / विवरण)</label>
              <input type="text" name="aarti_slots[{{ $idx }}][desc]" value="{{ $slot['desc'] ?? '' }}"
                class="form-input slot-desc-input" style="margin-bottom: 8px; font-size: 12px; padding: 6px 8px;"
                placeholder="कामदगिरि गर्भगृह">
            </div>

            <label
              style="display: flex; align-items: center; gap: 6px; font-size: 11px; color: #7A4B29; cursor: pointer; border-top: 1px dashed rgba(180,83,9,0.2); padding-top: 6px; margin: 0;">
              <input type="checkbox" name="aarti_slots[{{ $idx }}][is_live_pulse]" value="1" {{ !empty($slot['is_live_pulse']) ? 'checked' : '' }} class="slot-pulse-input" style="accent-color: #D95D0F;">
              <span>Live Pulse Highlight</span>
            </label>
          </div>
        @endforeach
      </div>

      @php
        $rawAmavasya = $settings['aarti_amavasya_slots_json'] ?? '';
        $existingAmavasyaSlots = [];
        if (!empty($rawAmavasya)) {
          $existingAmavasyaSlots = json_decode($rawAmavasya, true) ?: [];
        }
        if (empty($existingAmavasyaSlots)) {
          $existingAmavasyaSlots = [
            ['name' => 'परिक्रमा मंगला आरती', 'time' => '03:30 AM', 'desc' => 'प्रातः विशेष कामदगिरि परिक्रमा', 'is_live_pulse' => false],
            ['name' => 'अखण्ड वैदिक हवन', 'time' => '08:00 AM – 11:30 AM', 'desc' => 'ऋषिकुल यज्ञशाला', 'is_live_pulse' => false],
            ['name' => 'मन्दाकिनी महा दीपोत्सव आरती', 'time' => '07:00 PM', 'desc' => 'रामघाट 11,000+ दीप प्रज्ज्वलन', 'is_live_pulse' => true],
            ['name' => 'अखण्ड रात्रि महापूजा व जागरण', 'time' => '10:30 PM', 'desc' => 'अमावस्या मध्यरात्रि विशेष अनुष्ठान', 'is_live_pulse' => false],
            ['name' => 'भोर आरती व कपाट विश्राम', 'time' => '04:00 AM', 'desc' => 'पर्व समापन एवं आशीर्वाद', 'is_live_pulse' => false],
          ];
        }
      @endphp

      <div
        style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; flex-wrap: wrap; gap: 8px; border-top: 1px dashed rgba(180,83,9,0.25); padding-top: 18px;">
        <div>
          <strong style="color: #2B1A12; font-size: 13.5px; display: block;">अमावस्या एवं महापर्व समय चक्र (Amavasya Melas
            Schedule)</strong>
          <span style="font-size: 11px; color: #7A4B29;">Devotees see these slots when they toggle the Amavasya tab on the
            homepage.</span>
        </div>
        <button type="button" onclick="addNewAmavasyaSlot()" class="action-btn"
          style="background: #FAF4ED; color: #B45309; border: 1.5px solid rgba(180,83,9,0.35); font-size: 11.5px; font-weight: 700; padding: 7px 14px; cursor: pointer;">
          + Add Amavasya Slot (अमावस्या स्लॉट जोड़ें)
        </button>
      </div>

      <!-- Dynamic Amavasya Slots Grid -->
      <div id="amavasyaSlotsContainer"
        style="display: grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 14px; margin-bottom: 24px;">
        @foreach($existingAmavasyaSlots as $idx => $slot)
          <div class="amavasya-slot-card"
            style="background: #FAF4ED; border: 1px solid {{ !empty($slot['is_live_pulse']) || !empty($slot['is_live']) ? 'rgba(217, 93, 15, 0.45)' : 'rgba(180, 83, 9, 0.2)' }}; border-radius: 12px; padding: 12px; display: flex; flex-direction: column; justify-content: space-between; position: relative;">
            <div>
              <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                <span class="amavasya-slot-idx-label"
                  style="font-size: 10.5px; font-weight: 800; color: #B45309; text-transform: uppercase;">AMAVASYA
                  #{{ $idx + 1 }}</span>
                <button type="button" onclick="removeAmavasyaSlot(this)"
                  style="background: #fee2e2; border: 1px solid #f87171; color: #991b1b; font-size: 11px; font-weight: 700; border-radius: 4px; padding: 2px 7px; cursor: pointer;"
                  title="Remove this slot">Remove &times;</button>
              </div>

              <label class="form-label" style="font-size: 10px; margin-bottom: 2px;">NAME (नाम)</label>
              <input type="text" name="aarti_amavasya_slots[{{ $idx }}][name]" value="{{ $slot['name'] ?? '' }}"
                class="form-input amavasya-name-input" style="margin-bottom: 6px; font-size: 12px; padding: 6px 8px;"
                placeholder="उदा: परिक्रमा मंगला आरती">

              <label class="form-label" style="font-size: 10px; margin-bottom: 2px;">TIME (समय)</label>
              <input type="text" name="aarti_amavasya_slots[{{ $idx }}][time]" value="{{ $slot['time'] ?? '' }}"
                class="form-input amavasya-time-input" style="margin-bottom: 6px; font-size: 12px; padding: 6px 8px;"
                placeholder="03:30 AM">

              <label class="form-label" style="font-size: 10px; margin-bottom: 2px;">LOCATION / DESC (स्थान / विवरण)</label>
              <input type="text" name="aarti_amavasya_slots[{{ $idx }}][desc]"
                value="{{ $slot['desc'] ?? ($slot['description'] ?? '') }}" class="form-input amavasya-desc-input"
                style="margin-bottom: 8px; font-size: 12px; padding: 6px 8px;" placeholder="प्रातः विशेष कामदगिरि परिक्रमा">
            </div>

            <label
              style="display: flex; align-items: center; gap: 6px; font-size: 11px; color: #7A4B29; cursor: pointer; border-top: 1px dashed rgba(180,83,9,0.2); padding-top: 6px; margin: 0;">
              <input type="checkbox" name="aarti_amavasya_slots[{{ $idx }}][is_live_pulse]" value="1" {{ !empty($slot['is_live_pulse']) || !empty($slot['is_live']) ? 'checked' : '' }} class="amavasya-pulse-input"
                style="accent-color: #D95D0F;">
              <span>Live Pulse Highlight</span>
            </label>
          </div>
        @endforeach
      </div>

      <!-- Hawan Pooja Schedule & Dynamic CMS Packages Integration Box -->
      <div style="background: #FFFDFC; border: 1.5px solid rgba(180, 83, 9, 0.25); border-radius: 14px; padding: 20px;">
        <div
          style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; flex-wrap: wrap; gap: 10px;">
          <div>
            <strong style="color: #2B1A12; font-size: 14px;">Special Hawan & Pooja Packages Integration</strong>
            <span style="font-size: 11px; color: #7A4B29; display: block;">Feature hawan timings and link directly to
              active packages created in CMS Pooja menu.</span>
          </div>
          <label class="section-toggle-label" style="background: #FAF4ED;">
            <input type="checkbox" name="aarti_show_pooja_packages" value="1" {{ ($settings['aarti_show_pooja_packages'] ?? '1') === '1' ? 'checked' : '' }}>
            <span>Show Dynamic Hawan Packages in Timings Strip</span>
          </label>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px;">
          <div>
            <label class="form-label">HAWAN SCHEDULE TITLE</label>
            <input type="text" name="aarti_hawan_title"
              value="{{ $settings['aarti_hawan_title'] ?? 'दैनिक नवग्रह व रुद्र महाहवन' }}" class="form-input">
          </div>
          <div>
            <label class="form-label">HAWAN TIMING</label>
            <input type="text" name="aarti_hawan_time"
              value="{{ $settings['aarti_hawan_time'] ?? 'नित्य प्रातः 07:30 से 09:00' }}" class="form-input">
          </div>
          <div>
            <label class="form-label">HAWAN LOCATION</label>
            <input type="text" name="aarti_hawan_place"
              value="{{ $settings['aarti_hawan_place'] ?? 'श्री कामदगिरि मुख्य यज्ञशाला' }}" class="form-input">
          </div>
          <div>
            <label class="form-label">HAWAN SUMMARY NOTE</label>
            <input type="text" name="aarti_hawan_desc"
              value="{{ $settings['aarti_hawan_desc'] ?? 'वैदिक बटुकों एवं आचार्यों द्वारा यजमानों के नाम-गोत्र से आहुतियां' }}"
              class="form-input">
          </div>
        </div>
      </div>
    </div>

    <!-- ==========================================
         SECTION 3: ABOUT DEITY & TRUST HERITAGE
         ========================================== -->
    <div class="editor-section-card {{ ($settings['section_about_enabled'] ?? '1') === '0' ? 'section-disabled' : '' }}"
      id="sec-about">
      <div class="section-badge-header">
        <div class="section-badge-title">
          <span>3. About the Deity & Sacred Peetham Trust Heritage Section</span>
        </div>
        <label class="section-toggle-label">
          <input type="checkbox" name="section_about_enabled" value="1" {{ ($settings['section_about_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
          <span>Section Active on Homepage</span>
        </label>
      </div>

      <!-- Row 1: Eyebrow, Tagline & Main Heading -->
      <div style="display: grid; grid-template-columns: 1fr 1fr 2fr; gap: 20px; margin-bottom: 20px;">
        <div>
          <label class="form-label">SECTION EYEBROW</label>
          <input type="text" name="trust_eyebrow"
            value="{{ $settings['trust_eyebrow'] ?? 'कामदगिरि महिमा एवं सेवा संकल्प' }}" class="form-input">
        </div>
        <div>
          <label class="form-label">SECTION TAGLINE</label>
          <input type="text" name="trust_tagline"
            value="{{ $settings['trust_tagline'] ?? 'श्री कामतानाथ प्रभु पावन धाम' }}" class="form-input">
        </div>
        <div>
          <label class="form-label">MAIN HEADING</label>
          <input type="text" name="trust_heading"
            value="{{ $settings['trust_heading'] ?? 'भगवान श्री कामतानाथ जी एवं ट्रस्ट की पावन सेवा यात्रा' }}"
            class="form-input" style="font-family: 'Noto Serif', serif; font-weight: 700;">
        </div>
      </div>

      <!-- Row 2: Lead & Story Paragraphs -->
      <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 24px;">
        <div>
          <label class="form-label">LEAD / OPENING PARAGRAPH</label>
          <textarea name="trust_lead" rows="4"
            class="form-textarea">{{ $settings['trust_lead'] ?? 'चित्रकूट की तपोभूमि पर कामदगिरि पर्वत साक्षात् भगवान श्री कामतानाथ जी का पावन विग्रह स्वरूप है — जो श्रद्धापूर्वक शरणागत सभी भक्तों की मनोकामनाओं के परम सिद्धि प्रदाता हैं।' }}</textarea>
        </div>
        <div>
          <label class="form-label">HISTORICAL NARRATIVE (TRETA YUGA)</label>
          <textarea name="trust_desc" rows="4"
            class="form-textarea">{{ $settings['trust_desc'] ?? 'त्रेतायुग के वनवास काल में मर्यादा पुरुषोत्तम भगवान श्रीराम, माता जानकी एवं लक्ष्मण जी ने इसी पावन तपोवन में साढ़े ग्यारह वर्ष व्यतीत कर धर्म की प्रतिष्ठा की थी। कामदगिरि पर्वत की परिक्रमा से समस्त कष्टों का शमन होता है।' }}</textarea>
        </div>
        <div>
          <label class="form-label">TRUST MISSION PARAGRAPH</label>
          <textarea name="trust_p2" rows="4"
            class="form-textarea">{{ $settings['trust_p2'] ?? 'श्री कामदगिरि पीठम् सेवा ट्रस्ट इसी अनादि पावन परंपरा का संवाहक बनकर निःशुल्क ऋषिकुल वेद विद्यालय, कामदगिरि गोशाला, नित्य अन्नक्षेत्र भंडारा एवं तीर्थ परिक्रमा पथ संवर्धन में निरंतर समर्पित है।' }}</textarea>
        </div>
      </div>

      <!-- Row 3: Arched Photos (Upload + URL + Caption) -->
      <div
        style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px; padding: 18px; border-radius: 12px; background: #FAF4ED; border: 1px solid rgba(180, 83, 9, 0.2);">
        <div>
          <label class="form-label">ARCHED PHOTO 1 (LEFT SANCTUM)</label>
          <div style="display: flex; gap: 8px; margin-bottom: 8px;">
            <input type="text" name="about_photo_1_url" id="about_photo_1_url"
              value="{{ $settings['about_photo_1_url'] ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuCfOUqcsYwKNWdOv3-IySqrpdeo9-aBjdqcY15-uvLwf_VoWoEx-U1nvKGS2-jtfSlovihdEg3_b1s0-lbKpDDcIFSus-7fFE753BIRrO8WI8kMYj2_0FFT4qNEAK0772GxDZOYKJnZEZe3t4cHWEvQLZv7iMxOf44ldS-_6w2cjcFU41obdfY4R9BsY_utkY-FWJc4v6XbcYWLHkEU3NnjPGlX-TGbJZHFRE6vpNhYwmMofphi33u4' }}"
              class="form-input" placeholder="Image URL">
            <button type="button" onclick="openUniversalMediaPicker({ targetInput: '#about_photo_1_url' })"
              class="action-btn teal" style="padding: 7px 12px; font-size: 11px; white-space: nowrap;"><x-icon name="image" size="12" /> Reuse</button>
          </div>
          <label style="font-size: 11px; color: #7A4B29; font-weight: 700; margin-bottom: 4px; display: block;">Or Upload
            Photo 1 File:</label>
          <input type="file" name="about_photo_1_file" accept="image/*" class="form-input"
            style="background: #fff; padding: 8px; margin-bottom: 8px;">
          <input type="text" name="about_photo_1_caption"
            value="{{ $settings['about_photo_1_caption'] ?? 'कामदगिरि गर्भगृह' }}" class="form-input"
            placeholder="Photo Caption">
        </div>

        <div>
          <label class="form-label">ARCHED PHOTO 2 (RIGHT PARIKRAMA SHIKHAR)</label>
          <div style="display: flex; gap: 8px; margin-bottom: 8px;">
            <input type="text" name="about_photo_2_url" id="about_photo_2_url"
              value="{{ $settings['about_photo_2_url'] ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuCGizC1EIA1jnKlpQ1SWBzKrHG_OgZkQe6sDax_wnuZtIhFWtfWt-6m1jnBTWhz5Jji0AOrt9xr8W41pZkn-cZaOkuTyo2yeOHwrD92V6audP3fJcrCspCJNsquLRugs1Igok9LRE_4xwn9hZ6UVHI8f_hQJMjVOuIxA9CF87Om-bfNQNfGcQEsRylsOnCo7SMcSgmrXG2xf7A_uXu2kYkWLuPWiLaz4ltFV-UADzwzoIoVFdYetNmu' }}"
              class="form-input" placeholder="Image URL">
            <button type="button" onclick="openUniversalMediaPicker({ targetInput: '#about_photo_2_url' })"
              class="action-btn teal" style="padding: 7px 12px; font-size: 11px; white-space: nowrap;"><x-icon name="image" size="12" /> Reuse</button>
          </div>
          <label style="font-size: 11px; color: #7A4B29; font-weight: 700; margin-bottom: 4px; display: block;">Or Upload
            Photo 2 File:</label>
          <input type="file" name="about_photo_2_file" accept="image/*" class="form-input"
            style="background: #fff; padding: 8px; margin-bottom: 8px;">
          <input type="text" name="about_photo_2_caption"
            value="{{ $settings['about_photo_2_caption'] ?? 'पावन परिक्रमा शिखर' }}" class="form-input"
            placeholder="Photo Caption">
        </div>
      </div>

      <!-- Row 4: 4 Detailed Impact Stats -->
      <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 20px;">
        <div style="background: #FAF4ED; border: 1px solid rgba(180, 83, 9, 0.2); border-radius: 12px; padding: 12px;">
          <label class="form-label" style="color: #D95D0F;">STAT 1 (YEARS)</label>
          <input type="text" name="stat_years" value="{{ $settings['stat_years'] ?? '25+' }}" class="form-input"
            style="font-weight: 800; margin-bottom: 6px;">
          <input type="text" name="stat_years_title" value="{{ $settings['stat_years_title'] ?? 'अखंड सेवा साधना' }}"
            class="form-input" style="margin-bottom: 6px;" placeholder="Title (HI)">
          <input type="text" name="stat_years_sub" value="{{ $settings['stat_years_sub'] ?? 'Years of Dedicated Seva' }}"
            class="form-input" placeholder="Subtitle (EN)">
        </div>

        <div style="background: #FAF4ED; border: 1px solid rgba(180, 83, 9, 0.2); border-radius: 12px; padding: 12px;">
          <label class="form-label" style="color: #B45309;">STAT 2 (STUDENTS)</label>
          <input type="text" name="stat_students" value="{{ $settings['stat_students'] ?? '500+' }}" class="form-input"
            style="font-weight: 800; margin-bottom: 6px;">
          <input type="text" name="stat_students_title"
            value="{{ $settings['stat_students_title'] ?? 'ऋषिकुल बटुक छात्र' }}" class="form-input"
            style="margin-bottom: 6px;" placeholder="Title (HI)">
          <input type="text" name="stat_students_sub"
            value="{{ $settings['stat_students_sub'] ?? 'Vedic Gurukul Scholars' }}" class="form-input"
            placeholder="Subtitle (EN)">
        </div>

        <div style="background: #FAF4ED; border: 1px solid rgba(45, 106, 79, 0.25); border-radius: 12px; padding: 12px;">
          <label class="form-label" style="color: #2D6A4F;">STAT 3 (COWS)</label>
          <input type="text" name="stat_cows" value="{{ $settings['stat_cows'] ?? '250+' }}" class="form-input"
            style="font-weight: 800; margin-bottom: 6px;">
          <input type="text" name="stat_cows_title" value="{{ $settings['stat_cows_title'] ?? 'संरक्षित गोवंश सेवा' }}"
            class="form-input" style="margin-bottom: 6px;" placeholder="Title (HI)">
          <input type="text" name="stat_cows_sub" value="{{ $settings['stat_cows_sub'] ?? 'Protected Sacred Cows' }}"
            class="form-input" placeholder="Subtitle (EN)">
        </div>

        <div style="background: #FAF4ED; border: 1px solid rgba(217, 93, 15, 0.25); border-radius: 12px; padding: 12px;">
          <label class="form-label" style="color: #EA580C;">STAT 4 (TREES)</label>
          <input type="text" name="stat_trees" value="{{ $settings['stat_trees'] ?? '10,000+' }}" class="form-input"
            style="font-weight: 800; margin-bottom: 6px;">
          <input type="text" name="stat_trees_title"
            value="{{ $settings['stat_trees_title'] ?? 'तीर्थ हरित वृक्षारोपण' }}" class="form-input"
            style="margin-bottom: 6px;" placeholder="Title (HI)">
          <input type="text" name="stat_trees_sub" value="{{ $settings['stat_trees_sub'] ?? 'Sacred Trees Planted' }}"
            class="form-input" placeholder="Subtitle (EN)">
        </div>
      </div>

      <!-- CTA Button -->
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
        <div>
          <label class="form-label">READ MORE BUTTON TEXT</label>
          <input type="text" name="about_btn_text"
            value="{{ $settings['about_btn_text'] ?? 'श्री कामदगिरि धाम व ट्रस्ट का संपूर्ण इतिहास जानें' }}"
            class="form-input">
        </div>
        <div>
          <label class="form-label">READ MORE BUTTON LINK</label>
          <input type="text" name="about_btn_link" value="{{ $settings['about_btn_link'] ?? '/about' }}"
            class="form-input">
        </div>
      </div>
    </div>

    <!-- ==========================================
         SECTION 3B: ENVIRONMENTAL CAMPAIGN (पर्यावरण बचाओ अभियान)
         ========================================== -->
    <div class="editor-section-card {{ ($settings['section_env_enabled'] ?? '1') === '0' ? 'section-disabled' : '' }}"
      id="sec-env" style="border-left: 4px solid #2D6A4F;">
      <div class="section-badge-header">
        <div class="section-badge-title">
          <span style="color: #2D6A4F;">3B. Environmental Preservation Campaign (पर्यावरण बचाओ अभियान — स्वच्छ व संरक्षित
            चित्रकूट)</span>
        </div>
        <label class="section-toggle-label"
          style="background: rgba(45, 106, 79, 0.1); border-color: rgba(45, 106, 79, 0.3);">
          <input type="checkbox" name="section_env_enabled" value="1" {{ ($settings['section_env_enabled'] ?? '1') === '1' ? 'checked' : '' }} style="accent-color: #2D6A4F;">
          <span style="color: #2D6A4F; font-weight: 700;">Card Active in About Section</span>
        </label>
      </div>

      <!-- Row 1: Badge, Title & Subtitle -->
      <div style="display: grid; grid-template-columns: 1fr 2fr 1fr; gap: 20px; margin-bottom: 20px;">
        <div>
          <label class="form-label">SECTION BADGE / TAG</label>
          <input type="text" name="env_section_badge"
            value="{{ $settings['env_section_badge'] ?? 'पर्यावरण संरक्षण संकल्प • Eco-Dharma' }}" class="form-input">
        </div>
        <div>
          <label class="form-label">HEADLINE / TITLE</label>
          <input type="text" name="env_section_title"
            value="{{ $settings['env_section_title'] ?? 'पर्यावरण बचाओ अभियान — स्वच्छ व संरक्षित चित्रकूट' }}"
            class="form-input" style="font-family: 'Noto Serif', serif; font-weight: 700;">
        </div>
        <div>
          <label class="form-label">SUBTITLE / TAGLINE (OPTIONAL)</label>
          <input type="text" name="env_section_subtitle"
            value="{{ $settings['env_section_subtitle'] ?? 'पवित्र तपोभूमि की प्राकृतिक शुद्धता एवं सनातन पर्यावरण का संरक्षण' }}"
            class="form-input">
        </div>
      </div>

      <!-- Row 2: Campaign Image URL + Upload -->
      <div
        style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 22px; padding: 18px; border-radius: 12px; background: #F4FBF7; border: 1px solid rgba(45, 106, 79, 0.2);">
        <div>
          <label class="form-label">CAMPAIGN FEATURE IMAGE URL</label>
          <div style="display: flex; gap: 8px; margin-bottom: 8px;">
            <input type="text" name="env_section_image_url" id="env_section_image_url"
              value="{{ $settings['env_section_image_url'] ?? 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=1200&q=80' }}"
              class="form-input" placeholder="https://...">
            <button type="button" onclick="openUniversalMediaPicker({ targetInput: '#env_section_image_url' })"
              class="action-btn teal" style="padding: 7px 12px; font-size: 11px; white-space: nowrap;"><x-icon name="image" size="12" /> Reuse</button>
          </div>
          <span class="form-label-sub">High quality image showing Kamadgiri nature, Mandakini river or tree
            plantation.</span>
        </div>

        <div>
          <label class="form-label">OR UPLOAD CAMPAIGN IMAGE FILE</label>
          <input type="file" name="env_section_image_file" accept="image/*" class="form-input"
            style="background: #fff; padding: 8px;">
          <span class="form-label-sub">Uploads directly to public/uploads/cms and overrides URL.</span>
        </div>
      </div>

      <!-- Row 3: Narrative Description -->
      <div style="margin-bottom: 22px;">
        <label class="form-label">CAMPAIGN NARRATIVE &amp; VISION STATEMENT</label>
        <textarea name="env_section_desc" rows="3"
          class="form-textarea">{{ $settings['env_section_desc'] ?? 'भगवान श्रीराम की तपोभूमि चित्रकूट को स्वच्छ, प्रदूषण-मुक्त और हरियाली से परिपूर्ण बनाए रखना हमारा परम ध्येय है। ट्रस्ट पावन मंदाकिनी नदी की स्वच्छता, प्लास्टिक-मुक्त कामदगिरि परिक्रमा और १०,०००+ पवित्र वृक्षारोपण के माध्यम से चित्रकूट के मूल आध्यात्मिक व प्राकृतिक स्वरूप के संरक्षण हेतु अनवरत कार्यरत है।' }}</textarea>
      </div>

      <!-- Row 4: 3 Core Environmental Mission Pills -->
      <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 20px;">
        <div style="background: #FAF4ED; border: 1px solid rgba(45, 106, 79, 0.25); border-radius: 12px; padding: 12px;">
          <label class="form-label" style="color: #2D6A4F;">PILLAR 1 (MANDAKINI PURITY)</label>
          <input type="text" name="env_pillar_1" value="{{ $settings['env_pillar_1'] ?? 'अविरल-निर्मल मंदाकिनी' }}"
            class="form-input" style="font-weight: 700;">
        </div>

        <div style="background: #FAF4ED; border: 1px solid rgba(45, 106, 79, 0.25); border-radius: 12px; padding: 12px;">
          <label class="form-label" style="color: #2D6A4F;">PILLAR 2 (PLASTIC-FREE PARIKRAMA)</label>
          <input type="text" name="env_pillar_2"
            value="{{ $settings['env_pillar_2'] ?? 'प्लास्टिक-मुक्त कामदगिरि परिक्रमा' }}" class="form-input"
            style="font-weight: 700;">
        </div>

        <div style="background: #FAF4ED; border: 1px solid rgba(45, 106, 79, 0.25); border-radius: 12px; padding: 12px;">
          <label class="form-label" style="color: #2D6A4F;">PILLAR 3 (AFFORESTATION)</label>
          <input type="text" name="env_pillar_3" value="{{ $settings['env_pillar_3'] ?? '१०,०००+ देववृक्ष रोपण' }}"
            class="form-input" style="font-weight: 700;">
        </div>
      </div>

      <!-- Row 5: Action Link -->
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
        <div>
          <label class="form-label">ACTION LINK TEXT</label>
          <input type="text" name="env_btn_text"
            value="{{ $settings['env_btn_text'] ?? 'पर्यावरण सेवा संकल्प से जुड़ें' }}" class="form-input">
        </div>
        <div>
          <label class="form-label">ACTION LINK URL</label>
          <input type="text" name="env_btn_link" value="{{ $settings['env_btn_link'] ?? '/about#environment' }}"
            class="form-input">
        </div>
      </div>
    </div>

    <!-- ==========================================
         SECTION 4: CORE DHARMA MISSIONS
         ========================================== -->
    <div
      class="editor-section-card {{ ($settings['section_missions_enabled'] ?? '1') === '0' ? 'section-disabled' : '' }}"
      id="sec-missions">
      <div class="section-badge-header">
        <div class="section-badge-title">
          <span>4. Core Dharma Missions (Seva Pillars & Dynamic Projects)</span>
        </div>
        <label class="section-toggle-label">
          <input type="checkbox" name="section_missions_enabled" value="1" {{ ($settings['section_missions_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
          <span>Section Active on Homepage</span>
        </label>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr 2fr; gap: 20px; margin-bottom: 20px;">
        <div>
          <label class="form-label">MISSIONS EYEBROW</label>
          <input type="text" name="seva_section_eyebrow"
            value="{{ $settings['seva_section_eyebrow'] ?? '॥ प्रमुख सेवा संकल्प ॥ • Core Dharma Missions' }}"
            class="form-input">
        </div>
        <div>
          <label class="form-label">MISSIONS TITLE</label>
          <input type="text" name="seva_section_title"
            value="{{ $settings['seva_section_title'] ?? 'ट्रस्ट के मुख्य सेवा प्रकल्प — सनातन धर्म व समाज सेवा' }}"
            class="form-input" style="font-family: 'Noto Serif', serif; font-weight: 700;">
        </div>
        <div>
          <label class="form-label">MISSIONS SUBTITLE / NARRATIVE</label>
          <textarea name="seva_section_subtitle" rows="2"
            class="form-textarea">{{ $settings['seva_section_subtitle'] ?? 'चित्रकूट धाम की पावन परिधि में सनातन संस्कृति, वैदिक शिक्षा, गौवंश संरक्षण और दीन-दुखियों की सेवा हेतु निरंतर संचालित चार मुख्य सेवा स्तंभ।' }}</textarea>
        </div>
      </div>

      <!-- 4 Customizable Mission Pillars -->
      <strong style="color: #2B1A12; font-size: 13px; display: block; margin-bottom: 12px;">4 Homepage Mission Cards
        Configuration</strong>
      <div
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <!-- Pillar 1 -->
        <div style="background: #FAF4ED; border: 1px solid rgba(180, 83, 9, 0.2); border-radius: 12px; padding: 14px;">
          <label class="form-label" style="color: #D95D0F;">PILLAR 1: GURUKUL</label>
          <input type="text" name="mission_1_title" value="{{ $settings['mission_1_title'] ?? 'ऋषिकुल संस्कृत गुरुकुल' }}"
            class="form-input" style="margin-bottom: 6px;" placeholder="Title">
          <input type="text" name="mission_1_tag" value="{{ $settings['mission_1_tag'] ?? 'निःशुल्क शिक्षा' }}"
            class="form-input" style="margin-bottom: 6px;" placeholder="Badge Tag">
          <textarea name="mission_1_desc" rows="2" class="form-textarea" style="margin-bottom: 6px;"
            placeholder="Description">{{ $settings['mission_1_desc'] ?? 'बटुकों को निःशुल्क वेद-वेदांग, व्याकरण, कर्मकांड एवं सनातन संस्कार शिक्षा प्रदान करना।' }}</textarea>
          <input type="text" name="mission_1_link" value="{{ $settings['mission_1_link'] ?? '/seva/sanskrit-gurukul' }}"
            class="form-input" placeholder="Link URL">
        </div>

        <!-- Pillar 2 -->
        <div style="background: #FAF4ED; border: 1px solid rgba(45, 106, 79, 0.25); border-radius: 12px; padding: 14px;">
          <label class="form-label" style="color: #2D6A4F;">PILLAR 2: GAUSHALA</label>
          <input type="text" name="mission_2_title"
            value="{{ $settings['mission_2_title'] ?? 'कामदगिरि गौशाला (गौ सेवा)' }}" class="form-input"
            style="margin-bottom: 6px;" placeholder="Title">
          <input type="text" name="mission_2_tag" value="{{ $settings['mission_2_tag'] ?? '250+ गौवंश' }}"
            class="form-input" style="margin-bottom: 6px;" placeholder="Badge Tag">
          <textarea name="mission_2_desc" rows="2" class="form-textarea" style="margin-bottom: 6px;"
            placeholder="Description">{{ $settings['mission_2_desc'] ?? 'निराश्रित व वृद्ध गौवंश की नित्य सेवा, औषधीय चिकित्सा एवं पोषक हरा चारा व्यवस्था।' }}</textarea>
          <input type="text" name="mission_2_link"
            value="{{ $settings['mission_2_link'] ?? '/seva/gaushala-protection' }}" class="form-input"
            placeholder="Link URL">
        </div>

        <!-- Pillar 3 -->
        <div style="background: #FAF4ED; border: 1px solid rgba(180, 83, 9, 0.2); border-radius: 12px; padding: 14px;">
          <label class="form-label" style="color: #B45309;">PILLAR 3: ANNAKSHETRA</label>
          <input type="text" name="mission_3_title" value="{{ $settings['mission_3_title'] ?? 'नित्य अन्नक्षेत्र सेवा' }}"
            class="form-input" style="margin-bottom: 6px;" placeholder="Title">
          <input type="text" name="mission_3_tag" value="{{ $settings['mission_3_tag'] ?? 'नित्य भंडारा' }}"
            class="form-input" style="margin-bottom: 6px;" placeholder="Badge Tag">
          <textarea name="mission_3_desc" rows="2" class="form-textarea" style="margin-bottom: 6px;"
            placeholder="Description">{{ $settings['mission_3_desc'] ?? 'कामदगिरि परिक्रमार्थियों, संतों एवं निर्धनों को नित्य शुद्ध सात्विक महाप्रसाद वितरण।' }}</textarea>
          <input type="text" name="mission_3_link" value="{{ $settings['mission_3_link'] ?? '/seva' }}" class="form-input"
            placeholder="Link URL">
        </div>

        <!-- Pillar 4 -->
        <div style="background: #FAF4ED; border: 1px solid rgba(180, 83, 9, 0.2); border-radius: 12px; padding: 14px;">
          <label class="form-label" style="color: #D95D0F;">PILLAR 4: PILGRIMAGE & CULTURE</label>
          <input type="text" name="mission_4_title" value="{{ $settings['mission_4_title'] ?? 'तीर्थ व संस्कृति सेवा' }}"
            class="form-input" style="margin-bottom: 6px;" placeholder="Title">
          <input type="text" name="mission_4_tag" value="{{ $settings['mission_4_tag'] ?? 'तीर्थ संवर्धन' }}"
            class="form-input" style="margin-bottom: 6px;" placeholder="Badge Tag">
          <textarea name="mission_4_desc" rows="2" class="form-textarea" style="margin-bottom: 6px;"
            placeholder="Description">{{ $settings['mission_4_desc'] ?? 'कामदगिरि परिक्रमा पथ संवर्धन, मंदाकिनी दीपदान, स्वच्छता व धार्मिक उत्सव।' }}</textarea>
          <input type="text" name="mission_4_link" value="{{ $settings['mission_4_link'] ?? '/chitrakoot' }}"
            class="form-input" placeholder="Link URL">
        </div>
      </div>

      <!-- Granular Seva Projects Picker from DB -->
      <div>
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
          <div>
            <label class="form-label" style="color: #D95D0F; font-size: 12px; margin: 0;">SELECT ADDITIONAL DYNAMIC SEVA
              CARDS FROM DATABASE</label>
            <span class="form-label-sub">Pick published Seva projects to feature on the homepage.</span>
          </div>
          <a href="{{ route('admin.sevas') }}" target="_blank" class="action-btn"
            style="background: #B45309; color: #fff; font-size: 11px; padding: 6px 12px; border-radius: 6px; text-decoration: none;">
            Manage Sevas CMS &nearr;
          </a>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 14px;">
          @foreach($allSevas as $seva)
            <label
              style="display: flex; align-items: start; gap: 10px; background: #FAF4ED; border: 1px solid rgba(180, 83, 9, 0.2); border-radius: 10px; padding: 12px; cursor: pointer;">
              <input type="checkbox" name="selected_seva_ids[]" value="{{ $seva->id }}" {{ in_array($seva->id, $selectedSevaIds) ? 'checked' : '' }}
                style="margin-top: 3px; accent-color: #D95D0F; width: 16px; height: 16px;">
              <div>
                <strong style="font-size: 13px; color: #2B1A12; display: block;">{{ $seva->title }}</strong>
                <span style="font-size: 11px; color: #7A4B29;">Target: ₹{{ number_format($seva->target_amount, 0) }}</span>
              </div>
            </label>
          @endforeach
        </div>
      </div>
    </div>

    <!-- ==========================================
         SECTION 5: SACRED CONTRIBUTION & GIVING HUB
         ========================================== -->
    <div
      class="editor-section-card {{ ($settings['section_donation_enabled'] ?? '1') === '0' ? 'section-disabled' : '' }}"
      id="sec-donation">
      <div class="section-badge-header">
        <div class="section-badge-title">
          <span>5. Devotional Contribution & Giving Hub (Split Card & Razorpay Form)</span>
        </div>
        <label class="section-toggle-label">
          <input type="checkbox" name="section_donation_enabled" value="1" {{ ($settings['section_donation_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
          <span>Section Active on Homepage</span>
        </label>
      </div>

      <!-- Row 1: Texts -->
      <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px; margin-bottom: 20px;">
        <div>
          <label class="form-label">DONATION EYEBROW</label>
          <input type="text" name="donation_eyebrow"
            value="{{ $settings['donation_eyebrow'] ?? '॥ पावन सेवा समर्पण ॥ • Devotional Contribution' }}"
            class="form-input">
        </div>
        <div>
          <label class="form-label">DONATION MAIN HEADING</label>
          <input type="text" name="donation_heading"
            value="{{ $settings['donation_heading'] ?? 'प्रत्येक सहयोग पावन है — सेवा में सहभागिता करें' }}"
            class="form-input" style="font-family: 'Noto Serif', serif; font-weight: 700;">
        </div>
      </div>

      <div style="margin-bottom: 20px;">
        <label class="form-label">DONATION DESCRIPTION / 80G NOTE</label>
        <textarea name="donation_desc" rows="3"
          class="form-textarea">{{ $settings['donation_desc'] ?? 'भगवान कामतानाथ जी की पावन छत्रछाया में आपका सहयोग वेद-विद्या के संरक्षण, असहाय गोवंश के पोषण और नित्य अन्नदान में निरंतर जीवन संवारता है। १००% आयकर छूट (80G) सहित तत्काल डिजिटल रसीद प्राप्त करें।' }}</textarea>
      </div>

      <!-- Row 2: Replaceable Featured Image & Badges -->
      <div
        style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 22px; padding: 18px; border-radius: 12px; background: #FAF4ED; border: 1px solid rgba(180, 83, 9, 0.2);">
        <div>
          <label class="form-label">REPLACEABLE LEFT FEATURED TEMPLE IMAGE</label>
          <div style="display: flex; gap: 8px; margin-bottom: 8px;">
            <input type="text" name="donation_image_url" id="donation_image_url"
              value="{{ $settings['donation_image_url'] ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuDc5W4bxgqR7HwAkOkbyOQx3S8U_-YOP5i98bycRrLoNDpDgfUIRM80LfXXdlRd4-ogFPAXEfuralih2lOEp5APoROVdM0ki3US5w-TP_pLrjrc0rnNVPuBTq3RNH0piJfNBIkktuSzelW5DaiHO2Y7DwhSM0repTTFyUnTwMJIBibE_oFO3L8d8Ucee_SyHrgUdz67sBy3L5-aPQeqWQ7hclmRTeOMAOZjnQFC_vFfbWqLkAzzwrCE' }}"
              class="form-input" placeholder="Image URL">
            <button type="button" onclick="openUniversalMediaPicker({ targetInput: '#donation_image_url' })"
              class="action-btn teal" style="padding: 7px 12px; font-size: 11px; white-space: nowrap;"><x-icon name="image" size="12" /> Reuse</button>
          </div>
          <label style="font-size: 11px; color: #7A4B29; font-weight: 700; margin-bottom: 4px; display: block;">Or Upload
            Replacement Image File:</label>
          <input type="file" name="donation_image_file" accept="image/*" class="form-input"
            style="background: #fff; padding: 8px;">
        </div>

        <div>
          <label class="form-label">80G TAX BADGES & BUTTON LINK</label>
          <input type="text" name="donation_badge_1"
            value="{{ $settings['donation_badge_1'] ?? '100% Tax Benefit 80G Certified' }}" class="form-input"
            style="margin-bottom: 8px;" placeholder="Badge 1">
          <input type="text" name="donation_badge_2"
            value="{{ $settings['donation_badge_2'] ?? 'Instant Digital 80G Tax Receipt' }}" class="form-input"
            style="margin-bottom: 8px;" placeholder="Badge 2">
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
            <input type="text" name="donation_btn_text"
              value="{{ $settings['donation_btn_text'] ?? 'दान करें / Donate' }}" class="form-input"
              placeholder="Button Text">
            <input type="text" name="donation_btn_link" value="{{ $settings['donation_btn_link'] ?? '/donate' }}"
              class="form-input" placeholder="Button Link">
          </div>
        </div>
      </div>

      <!-- Row 3: Preset Amount Chips -->
      <div
        style="padding: 16px; border-radius: 12px; background: #FFFDFC; border: 1px solid rgba(180, 83, 9, 0.2); margin-bottom: 20px;">
        <strong style="color: #2B1A12; font-size: 13px; display: block; margin-bottom: 12px;">Donation Calculator Preset
          Chips (₹)</strong>
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px;">
          <div>
            <label class="form-label">CHIP 1 (₹)</label>
            <input type="number" name="donation_preset_1" value="{{ $settings['donation_preset_1'] ?? 501 }}"
              class="form-input" style="font-weight: 700;">
          </div>
          <div>
            <label class="form-label">CHIP 2 (₹)</label>
            <input type="number" name="donation_preset_2" value="{{ $settings['donation_preset_2'] ?? 1100 }}"
              class="form-input" style="font-weight: 700;">
          </div>
          <div>
            <label class="form-label">CHIP 3 (₹)</label>
            <input type="number" name="donation_preset_3" value="{{ $settings['donation_preset_3'] ?? 2100 }}"
              class="form-input" style="font-weight: 700;">
          </div>
          <div>
            <label class="form-label">CHIP 4 (₹)</label>
            <input type="number" name="donation_preset_4" value="{{ $settings['donation_preset_4'] ?? 5100 }}"
              class="form-input" style="font-weight: 700;">
          </div>
        </div>
      </div>
    </div>

    <!-- ==========================================
         SECTION 6: ONLINE POOJA & SACRED SANKALP
         ========================================== -->
    <div class="editor-section-card {{ ($settings['section_pooja_enabled'] ?? '1') === '0' ? 'section-disabled' : '' }}"
      id="sec-pooja">
      <div class="section-badge-header">
        <div class="section-badge-title">
          <span>6. Online Pooja & Sacred Sankalp Sanctuary</span>
        </div>
        <label class="section-toggle-label">
          <input type="checkbox" name="section_pooja_enabled" value="1" {{ ($settings['section_pooja_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
          <span>Section Active on Homepage</span>
        </label>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr 2fr; gap: 20px; margin-bottom: 16px;">
        <div>
          <label class="form-label">SECTION EYEBROW</label>
          <input type="text" name="pooja_section_eyebrow"
            value="{{ $settings['pooja_section_eyebrow'] ?? 'Sacred Sankalp & Seva' }}" class="form-input">
        </div>
        <div>
          <label class="form-label">TAGLINE / BADGE</label>
          <input type="text" name="pooja_section_tagline"
            value="{{ $settings['pooja_section_tagline'] ?? 'ऑनलाइन पूजा व दिव्य संकल्प' }}" class="form-input">
        </div>
        <div>
          <label class="form-label">SECTION HEADLINE</label>
          <input type="text" name="pooja_section_title"
            value="{{ $settings['pooja_section_title'] ?? 'घर बैठे चित्रकूट धाम में पूजा व महाआरती का पावन संकल्प लें' }}"
            class="form-input" style="font-family: 'Noto Serif', serif; font-weight: 700;">
        </div>
      </div>

      <div style="margin-bottom: 22px;">
        <label class="form-label">SECTION DESCRIPTION</label>
        <textarea name="pooja_section_desc" rows="2"
          class="form-textarea">{{ $settings['pooja_section_desc'] ?? 'श्री कामदगिरि मंदिर एवं पवित्र मंदाकिनी तट पर आपके व आपके परिवार के नाम एवं गोत्र से वैदिक आचार्यों द्वारा संपन्न कराई जाने वाली नित्य पूजा। पावन प्रसाद आपके घर डाक द्वारा भेजा जाएगा।' }}</textarea>
      </div>

      <!-- Granular Pooja Packages Selection from DB -->
      <div style="margin-bottom: 24px;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
          <div>
            <label class="form-label" style="color: #D95D0F; font-size: 12px; margin: 0;">SELECT EXACT POOJA & HAWAN
              PACKAGES TO FEATURE ON HOMEPAGE</label>
            <span class="form-label-sub">Check which packages appear on the homepage (both under Aarti and in Pooja
              Sanctuary). If none are checked, the packages section will be completely hidden.</span>
          </div>
          <a href="{{ route('admin.poojas') }}" target="_blank" class="action-btn"
            style="background: #B45309; color: #fff; font-size: 11px; padding: 6px 12px; border-radius: 6px; text-decoration: none;">
            Manage Poojas & Hawan Packages &nearr;
          </a>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 14px;">
          @foreach($allPoojas as $pooja)
            <label
              style="display: flex; align-items: start; gap: 10px; background: #FAF4ED; border: 1px solid rgba(180, 83, 9, 0.2); border-radius: 10px; padding: 12px; cursor: pointer;">
              <input type="checkbox" name="selected_pooja_ids[]" value="{{ $pooja->id }}" {{ in_array($pooja->id, $selectedPoojaIds) ? 'checked' : '' }}
                style="margin-top: 3px; accent-color: #D95D0F; width: 16px; height: 16px;">
              <div>
                <strong style="font-size: 13px; color: #2B1A12; display: block;">{{ $pooja->name }}</strong>
                <span style="font-size: 11px; color: #7A4B29;">₹{{ number_format($pooja->price, 0) }} •
                  {{ $pooja->duration_minutes ?? 45 }} min</span>
              </div>
            </label>
          @endforeach
        </div>
      </div>

      <!-- 3-Step Process Ribbon Editor -->
      <div style="padding: 18px; border-radius: 12px; background: #FAF4ED; border: 1px solid rgba(180, 83, 9, 0.2);">
        <div style="margin-bottom: 12px;">
          <label class="form-label">PROCESS RIBBON HEADING</label>
          <input type="text" name="process_ribbon_heading"
            value="{{ $settings['process_ribbon_heading'] ?? 'घर बैठे पूजा संकल्प की ३ पावन चरण प्रक्रिया' }}"
            class="form-input">
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 14px;">
          <div>
            <label class="form-label">STEP 01 TITLE & DESC</label>
            <input type="text" name="process_step_1_title"
              value="{{ $settings['process_step_1_title'] ?? 'नाम एवं गोत्र से संकल्प' }}" class="form-input"
              style="margin-bottom: 6px;">
            <input type="text" name="process_step_1_desc"
              value="{{ $settings['process_step_1_desc'] ?? 'यजमान का नाम, गोत्र और विशेष प्रार्थना विवरण ऑनलाइन दर्ज करें।' }}"
              class="form-input" style="font-size: 12px;">
          </div>

          <div>
            <label class="form-label">STEP 02 TITLE & DESC</label>
            <input type="text" name="process_step_2_title"
              value="{{ $settings['process_step_2_title'] ?? 'वैदिक आचार्यों द्वारा अनुष्ठान' }}" class="form-input"
              style="margin-bottom: 6px;">
            <input type="text" name="process_step_2_desc"
              value="{{ $settings['process_step_2_desc'] ?? 'कामदगिरि गर्भगृह अथवा रामघाट पर मंत्रोच्चार सहित विधिवत पूजा और दीपदान।' }}"
              class="form-input" style="font-size: 12px;">
          </div>

          <div>
            <label class="form-label">STEP 03 TITLE & DESC</label>
            <input type="text" name="process_step_3_title"
              value="{{ $settings['process_step_3_title'] ?? 'संकल्प पूर्णाहुति व पावन आशीर्वाद' }}" class="form-input"
              style="margin-bottom: 6px;">
            <input type="text" name="process_step_3_desc"
              value="{{ $settings['process_step_3_desc'] ?? 'सविधि अनुष्ठान उपरांत यजमान को पूजन संपन्नता की पुष्टि एवं पावन आशीर्वाद।' }}"
              class="form-input" style="font-size: 12px;">
          </div>
        </div>
      </div>
    </div>

    <!-- ==========================================
         SECTION 7: SACRED PLACES & ATTRACTIONS
         ========================================== -->
    <div class="editor-section-card {{ ($settings['section_places_enabled'] ?? '1') === '0' ? 'section-disabled' : '' }}"
      id="sec-places">
      <div class="section-badge-header">
        <div class="section-badge-title">
          <span>7. Sacred Places & Chitrakoot Attractions</span>
        </div>
        <label class="section-toggle-label">
          <input type="checkbox" name="section_places_enabled" value="1" {{ ($settings['section_places_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
          <span>Section Active on Homepage</span>
        </label>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px; margin-bottom: 16px;">
        <div>
          <label class="form-label">PLACES SECTION EYEBROW</label>
          <input type="text" name="places_section_eyebrow"
            value="{{ $settings['places_section_eyebrow'] ?? 'Divine Pilgrimage • तीर्थ चित्रकूट दर्शन' }}"
            class="form-input">
        </div>
        <div>
          <label class="form-label">SACRED PLACES TITLE</label>
          <input type="text" name="places_section_title"
            value="{{ $settings['places_section_title'] ?? 'जहाँ हर पग एक कथा है' }}" class="form-input"
            style="font-family: 'Noto Serif', serif; font-weight: 700;">
        </div>
      </div>

      <div style="margin-bottom: 22px;">
        <label class="form-label">SACRED PLACES SUBTITLE / NARRATIVE</label>
        <textarea name="places_section_subtitle" rows="2"
          class="form-textarea">{{ $settings['places_section_subtitle'] ?? 'चित्रकूट भारत की उन पावनतम भूमियों में से है जहाँ प्रकृति, रामायण इतिहास और अध्यात्म एकाकार होते हैं।' }}</textarea>
      </div>

      <!-- Granular Destination Cards Picker -->
      <div>
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
          <div>
            <label class="form-label" style="color: #D95D0F; font-size: 12px; margin: 0;">CHOOSE EXACT DESTINATION CARDS
              TO DISPLAY ON HOMEPAGE</label>
            <span class="form-label-sub">Select which pilgrimage spots appear on homepage.</span>
          </div>
          <a href="{{ route('admin.destinations') }}" target="_blank" class="action-btn"
            style="background: #B45309; color: #fff; font-size: 11px; padding: 6px 12px; border-radius: 6px; text-decoration: none;">
            Manage Places CMS &nearr;
          </a>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 14px;">
          @foreach($allDestinations as $dest)
            <label
              style="display: flex; align-items: start; gap: 10px; background: #FAF4ED; border: 1px solid rgba(180, 83, 9, 0.2); border-radius: 10px; padding: 12px; cursor: pointer;">
              <input type="checkbox" name="selected_destination_ids[]" value="{{ $dest->id }}" {{ in_array($dest->id, $selectedDestIds) ? 'checked' : '' }}
                style="margin-top: 3px; accent-color: #D95D0F; width: 16px; height: 16px;">
              <div>
                <strong
                  style="font-size: 13px; color: #2B1A12; display: block;">{{ $dest->name_hi ?: $dest->name_en }}</strong>
                <span style="font-size: 11px; color: #7A4B29;">{{ $dest->tagline_hi ?? 'पावन तीर्थ' }}</span>
              </div>
            </label>
          @endforeach
        </div>
      </div>
    </div>

    <!-- ==========================================
         SECTION 8: UPCOMING EVENTS & BROADCAST
         ========================================== -->
    <div class="editor-section-card {{ ($settings['section_events_enabled'] ?? '1') === '0' ? 'section-disabled' : '' }}"
      id="sec-events">
      <div class="section-badge-header">
        <div class="section-badge-title">
          <span>8. Upcoming Utsavs & Daily WhatsApp Darshan Broadcast</span>
        </div>
        <label class="section-toggle-label">
          <input type="checkbox" name="section_events_enabled" value="1" {{ ($settings['section_events_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
          <span>Section Active on Homepage</span>
        </label>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px; margin-bottom: 16px;">
        <div>
          <label class="form-label">EVENTS SECTION EYEBROW</label>
          <input type="text" name="events_section_eyebrow"
            value="{{ $settings['events_section_eyebrow'] ?? 'Upcoming Events' }}" class="form-input">
        </div>
        <div>
          <label class="form-label">EVENTS SECTION TITLE</label>
          <input type="text" name="events_section_title"
            value="{{ $settings['events_section_title'] ?? 'आगामी धार्मिक कार्यक्रम एवं उत्सव' }}" class="form-input"
            style="font-family: 'Noto Serif', serif; font-weight: 700;">
        </div>
      </div>

      <div style="margin-bottom: 22px;">
        <label class="form-label">EVENTS SECTION SUBTITLE</label>
        <textarea name="events_section_subtitle" rows="2"
          class="form-textarea">{{ $settings['events_section_subtitle'] ?? 'धार्मिक, सांस्कृतिक और सामाजिक गतिविधियों से सपरिवार जुड़ें।' }}</textarea>
      </div>

      <!-- Dynamic Upcoming Events Notifier & Live Status Box -->
      <div
        style="background: #FFFDFC; border: 1.5px solid rgba(180, 83, 9, 0.25); border-radius: 14px; padding: 18px; margin-bottom: 20px;">
        <div
          style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; flex-wrap: wrap; gap: 10px;">
          <div style="display: flex; align-items: center; gap: 10px;">
            <div
              style="width: 32px; height: 32px; border-radius: 8px; background: rgba(217, 93, 15, 0.15); color: #D95D0F; display: grid; place-items: center;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
              </svg>
            </div>
            <div>
              <strong style="color: #2B1A12; font-size: 13.5px; display: block;">Upcoming Events Dynamic Notifier</strong>
              <span style="font-size: 11px; color: #7A4B29;">
                Currently <strong>{{ $upcomingEvents->count() }} active upcoming event(s)</strong> scheduled in database.
              </span>
            </div>
          </div>
          <div style="display: flex; gap: 8px;">
            <a href="{{ route('admin.events') }}" class="action-btn primary"
              style="background: #B45309; font-size: 11px; padding: 6px 14px; text-decoration: none;">
              Manage Events CMS &nearr;
            </a>
          </div>
        </div>

        <div
          style="padding: 10px 14px; border-radius: 8px; background: #FAF4ED; font-size: 11.5px; color: #92400E; margin-bottom: 14px; border-left: 3px solid #D95D0F;">
          <strong>Dynamic Homepage Rule:</strong> If there are 0 upcoming events, this section will automatically be
          hidden on the homepage so no empty or outdated cards are displayed to devotees.
        </div>

        @if($upcomingEvents->count() > 0)
          <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 10px;">
            @foreach($upcomingEvents as $ev)
              <div
                style="padding: 10px 12px; border-radius: 8px; background: #FDFBF8; border: 1px solid rgba(180, 83, 9, 0.15); display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                <div>
                  <strong style="font-size: 12px; color: #2B1A12; display: block;">{{ $ev->title_hi }}</strong>
                  <span style="font-size: 10.5px; color: #B45309;">{{ $ev->event_date->format('d M Y, h:i A') }} •
                    {{ $ev->location_hi ?? 'चित्रकूट धाम' }}</span>
                </div>
                <a href="{{ route('admin.events') }}"
                  style="font-size: 10.5px; color: #D95D0F; text-decoration: none; font-weight: 700;">Edit &rarr;</a>
              </div>
            @endforeach
          </div>
        @else
          <div
            style="padding: 14px; border-radius: 8px; background: #FFF1F2; border: 1px dashed #FECDD3; text-align: center; font-size: 11.5px; color: #991B1B;">
            No upcoming events scheduled right now. The events section is currently <strong>auto-hidden</strong> on the live
            homepage.
          </div>
        @endif
      </div>

      <div
        style="padding: 18px; border-radius: 12px; background: #FAF4ED; border: 1px solid rgba(45, 106, 79, 0.25); margin-bottom: 20px;">
        <strong style="color: #2D6A4F; font-size: 13px; display: block; margin-bottom: 12px;">Daily WhatsApp Broadcast
          Banner</strong>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
          <div>
            <label class="form-label">BANNER HEADLINE</label>
            <input type="text" name="whatsapp_broadcast_title"
              value="{{ $settings['whatsapp_broadcast_title'] ?? 'कामदगिरि धाम के पावन उत्सव, पंचांग व आश्रम समाचार बुलेटिन' }}"
              class="form-input">
          </div>
          <div>
            <label class="form-label">BANNER DESCRIPTION</label>
            <input type="text" name="whatsapp_broadcast_desc"
              value="{{ $settings['whatsapp_broadcast_desc'] ?? 'पावन तिथियां, आगामी धार्मिक उत्सव, नित्य पंचांग, और सेवा प्रकल्प सूचनाएं निःशुल्क प्राप्त करने के लिए अपना व्हाट्सएप नंबर जोड़ें।' }}"
              class="form-input">
          </div>
        </div>
      </div>

      <!-- Volunteer Portal Availability Toggle -->
      <div
        style="background: #FFFDFC; border: 1px solid rgba(180, 83, 9, 0.2); border-radius: 12px; padding: 16px; display: flex; align-items: center; justify-content: space-between;">
        <div>
          <strong style="color: #2B1A12; font-size: 13px; display: block;">Volunteer Seva Portal Availability</strong>
          <span style="font-size: 11px; color: #7A4B29;">Control whether devotees can enroll as volunteers for temple
            events & seva drives.</span>
        </div>
        <label class="section-toggle-label">
          <input type="checkbox" name="volunteer_registration_enabled" value="1" {{ ($settings['volunteer_registration_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
          <span>Accept Registrations</span>
        </label>
      </div>
    </div>

    <!-- ==========================================
         SECTION 9: EXTRA SHOWCASE / REPEATABLE SECTION
         ========================================== -->
    <div class="editor-section-card {{ ($settings['section_custom_enabled'] ?? '0') === '0' ? 'section-disabled' : '' }}"
      id="sec-custom">
      <div class="section-badge-header">
        <div class="section-badge-title">
          <span>9. Additional Custom Showcase Section (Special Festivity / Announcement / Seva Drive)</span>
        </div>
        <label class="section-toggle-label">
          <input type="checkbox" name="section_custom_enabled" value="1" {{ ($settings['section_custom_enabled'] ?? '0') === '1' ? 'checked' : '' }}>
          <span>Section Active on Homepage</span>
        </label>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px; margin-bottom: 16px;">
        <div>
          <label class="form-label">CUSTOM SECTION EYEBROW</label>
          <input type="text" name="custom_section_eyebrow"
            value="{{ $settings['custom_section_eyebrow'] ?? '॥ विशेष आयोजन व उत्सव ॥ • Special Sanctum Drive' }}"
            class="form-input">
        </div>
        <div>
          <label class="form-label">CUSTOM SECTION TITLE</label>
          <input type="text" name="custom_section_title"
            value="{{ $settings['custom_section_title'] ?? 'श्री कामदगिरि परिक्रमा पथ दीपदान एवं महाउत्सव' }}"
            class="form-input" style="font-family: 'Noto Serif', serif; font-weight: 700;">
        </div>
      </div>

      <div style="margin-bottom: 20px;">
        <label class="form-label">CUSTOM SECTION NARRATIVE</label>
        <textarea name="custom_section_desc" rows="2"
          class="form-textarea">{{ $settings['custom_section_desc'] ?? 'कार्तिक पूर्णिमा एवं सोमवती अमावस्या पर आयोजित होने वाले दिव्य दीपोत्सव में भाग लें और पुण्य के भागी बनें।' }}</textarea>
      </div>

      <div
        style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; padding: 18px; border-radius: 12px; background: #FAF4ED; border: 1px solid rgba(180, 83, 9, 0.2);">
        <div>
          <label class="form-label">SHOWCASE IMAGE URL OR UPLOAD</label>
          <div style="display: flex; gap: 8px; margin-bottom: 8px;">
            <input type="text" name="custom_section_image_url" id="custom_section_image_url"
              value="{{ $settings['custom_section_image_url'] ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuD_10PzMJvMK88XcmC2AB8m_aU1hgj9DFHBTrIyWQmqHlJR5eacLXqVggzhxSsUul4eeWmpkQZ4xrl-Hkk1M1xFkw65-2vvIV2HX7SH-9M1U6RKBSGsTVQEWqdyip-ercdU9diCPfb733xWaBC8Ipc6ERxlGl9PawntmnSCRLQ-xitKW9UtdPeI5elR1uc2lH9gzAmw2l_vl_OsCCxVLkDx590Cm6bzBZ8QCnigPFXkLhpvyujXMW6q' }}"
              class="form-input" placeholder="Image URL">
            <button type="button" onclick="openUniversalMediaPicker({ targetInput: '#custom_section_image_url' })"
              class="action-btn teal" style="padding: 7px 12px; font-size: 11px; white-space: nowrap;"><x-icon name="image" size="12" /> Reuse</button>
          </div>
          <label style="font-size: 11px; color: #7A4B29; font-weight: 700; margin-bottom: 4px; display: block;">Or Upload
            File:</label>
          <input type="file" name="custom_section_image_file" accept="image/*" class="form-input"
            style="background: #fff; padding: 8px;">
        </div>

        <div>
          <label class="form-label">BADGE & ACTION BUTTON</label>
          <input type="text" name="custom_section_badge"
            value="{{ $settings['custom_section_badge'] ?? 'विशेष पावन उत्सव' }}" class="form-input"
            style="margin-bottom: 8px;" placeholder="Badge Tag">
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
            <input type="text" name="custom_section_btn_text"
              value="{{ $settings['custom_section_btn_text'] ?? 'उत्सव विवरण देखें' }}" class="form-input"
              placeholder="Button Text">
            <input type="text" name="custom_section_btn_link"
              value="{{ $settings['custom_section_btn_link'] ?? '/events' }}" class="form-input"
              placeholder="Button Link">
          </div>
        </div>
      </div>

      <!-- 3 Highlight Bullets -->
      <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px;">
        <div>
          <label class="form-label">HIGHLIGHT POINT 1</label>
          <input type="text" name="custom_section_highlight_1"
            value="{{ $settings['custom_section_highlight_1'] ?? 'लाखों दीपों से जगमगाता मंदाकिनी तट' }}"
            class="form-input">
        </div>
        <div>
          <label class="form-label">HIGHLIGHT POINT 2</label>
          <input type="text" name="custom_section_highlight_2"
            value="{{ $settings['custom_section_highlight_2'] ?? 'अखंड राम संकीर्तन एवं संत समागम' }}" class="form-input">
        </div>
        <div>
          <label class="form-label">HIGHLIGHT POINT 3</label>
          <input type="text" name="custom_section_highlight_3"
            value="{{ $settings['custom_section_highlight_3'] ?? 'ऑनलाइन संकल्प एवं महाप्रसाद सेवा' }}"
            class="form-input">
        </div>
      </div>
    </div>

    <!-- STICKY ACTION BAR -->
    <div class="btn-save-sticky">
      <div style="color: #fff; font-size: 13px;">
        <strong style="color: #D4B996;">Live CMS Sync:</strong> Changes reflect immediately on homepage upon saving.
      </div>
      <button type="submit" class="btn-capsule"
        style="background: linear-gradient(to right, #D95D0F, #EA580C); color: #fff; font-size: 14px; padding: 12px 32px; box-shadow: 0 4px 18px rgba(217, 93, 15, 0.4);">
        SAVE ALL HOMEPAGE SECTIONS &rarr;
      </button>
    </div>

  </form>

  <script>
    function addNewAartiSlot() {
      const container = document.getElementById('aartiSlotsContainer');
      const count = container.querySelectorAll('.aarti-slot-card').length;
      const idx = count;

      const div = document.createElement('div');
      div.className = 'aarti-slot-card';
      div.style.cssText = 'background: #FAF4ED; border: 1px solid rgba(180, 83, 9, 0.2); border-radius: 12px; padding: 12px; display: flex; flex-direction: column; justify-content: space-between; position: relative;';

      div.innerHTML = `
      <div>
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
          <span class="slot-idx-label" style="font-size: 10.5px; font-weight: 800; color: #D95D0F; text-transform: uppercase;">SLOT #${idx + 1}</span>
          <button type="button" onclick="removeAartiSlot(this)" style="background: #fee2e2; border: 1px solid #f87171; color: #991b1b; font-size: 11px; font-weight: 700; border-radius: 4px; padding: 2px 7px; cursor: pointer;" title="Remove this slot">Remove &times;</button>
        </div>
        <label class="form-label" style="font-size: 10px; margin-bottom: 2px;">NAME (नाम)</label>
        <input type="text" name="aarti_slots[${idx}][name]" value="" class="form-input slot-name-input" style="margin-bottom: 6px; font-size: 12px; padding: 6px 8px;" placeholder="उदा: विशेष आरती">
        <label class="form-label" style="font-size: 10px; margin-bottom: 2px;">TIME (समय)</label>
        <input type="text" name="aarti_slots[${idx}][time]" value="" class="form-input slot-time-input" style="margin-bottom: 6px; font-size: 12px; padding: 6px 8px;" placeholder="07:00 PM">
        <label class="form-label" style="font-size: 10px; margin-bottom: 2px;">LOCATION / DESC (स्थान / विवरण)</label>
        <input type="text" name="aarti_slots[${idx}][desc]" value="" class="form-input slot-desc-input" style="margin-bottom: 8px; font-size: 12px; padding: 6px 8px;" placeholder="कामदगिरि परिक्रमा मार्ग">
      </div>
      <label style="display: flex; align-items: center; gap: 6px; font-size: 11px; color: #7A4B29; cursor: pointer; border-top: 1px dashed rgba(180,83,9,0.2); padding-top: 6px; margin: 0;">
        <input type="checkbox" name="aarti_slots[${idx}][is_live_pulse]" value="1" class="slot-pulse-input" style="accent-color: #D95D0F;">
        <span>Live Pulse Highlight</span>
      </label>
    `;
      container.appendChild(div);
      reindexAartiSlots();
    }

    function removeAartiSlot(btn) {
      const card = btn.closest('.aarti-slot-card');
      if (card) {
        card.remove();
        reindexAartiSlots();
      }
    }

    function reindexAartiSlots() {
      const cards = document.querySelectorAll('#aartiSlotsContainer .aarti-slot-card');
      cards.forEach((card, i) => {
        const label = card.querySelector('.slot-idx-label');
        if (label) label.textContent = 'SLOT #' + (i + 1);
        const nameInput = card.querySelector('.slot-name-input');
        if (nameInput) nameInput.name = `aarti_slots[${i}][name]`;
        const timeInput = card.querySelector('.slot-time-input');
        if (timeInput) timeInput.name = `aarti_slots[${i}][time]`;
        const descInput = card.querySelector('.slot-desc-input');
        if (descInput) descInput.name = `aarti_slots[${i}][desc]`;
        const pulseInput = card.querySelector('.slot-pulse-input');
        if (pulseInput) pulseInput.name = `aarti_slots[${i}][is_live_pulse]`;
      });
    }

    function addNewAmavasyaSlot() {
      const container = document.getElementById('amavasyaSlotsContainer');
      const count = container.querySelectorAll('.amavasya-slot-card').length;
      const idx = count;

      const div = document.createElement('div');
      div.className = 'amavasya-slot-card';
      div.style.cssText = 'background: #FAF4ED; border: 1px solid rgba(180, 83, 9, 0.2); border-radius: 12px; padding: 12px; display: flex; flex-direction: column; justify-content: space-between; position: relative;';

      div.innerHTML = `
      <div>
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
          <span class="amavasya-slot-idx-label" style="font-size: 10.5px; font-weight: 800; color: #B45309; text-transform: uppercase;">AMAVASYA #${idx + 1}</span>
          <button type="button" onclick="removeAmavasyaSlot(this)" style="background: #fee2e2; border: 1px solid #f87171; color: #991b1b; font-size: 11px; font-weight: 700; border-radius: 4px; padding: 2px 7px; cursor: pointer;" title="Remove this slot">Remove &times;</button>
        </div>
        <label class="form-label" style="font-size: 10px; margin-bottom: 2px;">NAME (नाम)</label>
        <input type="text" name="aarti_amavasya_slots[${idx}][name]" value="" class="form-input amavasya-name-input" style="margin-bottom: 6px; font-size: 12px; padding: 6px 8px;" placeholder="उदा: परिक्रमा मंगला आरती">
        <label class="form-label" style="font-size: 10px; margin-bottom: 2px;">TIME (समय)</label>
        <input type="text" name="aarti_amavasya_slots[${idx}][time]" value="" class="form-input amavasya-time-input" style="margin-bottom: 6px; font-size: 12px; padding: 6px 8px;" placeholder="03:30 AM">
        <label class="form-label" style="font-size: 10px; margin-bottom: 2px;">LOCATION / DESC (स्थान / विवरण)</label>
        <input type="text" name="aarti_amavasya_slots[${idx}][desc]" value="" class="form-input amavasya-desc-input" style="margin-bottom: 8px; font-size: 12px; padding: 6px 8px;" placeholder="प्रातः विशेष कामदगिरि परिक्रमा">
      </div>
      <label style="display: flex; align-items: center; gap: 6px; font-size: 11px; color: #7A4B29; cursor: pointer; border-top: 1px dashed rgba(180,83,9,0.2); padding-top: 6px; margin: 0;">
        <input type="checkbox" name="aarti_amavasya_slots[${idx}][is_live_pulse]" value="1" class="amavasya-pulse-input" style="accent-color: #D95D0F;">
        <span>Live Pulse Highlight</span>
      </label>
    `;
      container.appendChild(div);
      reindexAmavasyaSlots();
    }

    function removeAmavasyaSlot(btn) {
      const card = btn.closest('.amavasya-slot-card');
      if (card) {
        card.remove();
        reindexAmavasyaSlots();
      }
    }

    function reindexAmavasyaSlots() {
      const cards = document.querySelectorAll('#amavasyaSlotsContainer .amavasya-slot-card');
      cards.forEach((card, i) => {
        const label = card.querySelector('.amavasya-slot-idx-label');
        if (label) label.textContent = 'AMAVASYA #' + (i + 1);
        const nameInput = card.querySelector('.amavasya-name-input');
        if (nameInput) nameInput.name = `aarti_amavasya_slots[${i}][name]`;
        const timeInput = card.querySelector('.amavasya-time-input');
        if (timeInput) timeInput.name = `aarti_amavasya_slots[${i}][time]`;
        const descInput = card.querySelector('.amavasya-desc-input');
        if (descInput) descInput.name = `aarti_amavasya_slots[${i}][desc]`;
        const pulseInput = card.querySelector('.amavasya-pulse-input');
        if (pulseInput) pulseInput.name = `aarti_amavasya_slots[${i}][is_live_pulse]`;
      });
    }
  </script>

@endsection