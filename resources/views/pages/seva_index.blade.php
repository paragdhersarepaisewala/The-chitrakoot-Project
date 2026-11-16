@extends('layouts.app')

@section('title', 'हमारी सेवाएँ एवं प्रकल्प | श्री कामदगिरि पीठम् सेवा ट्रस्ट')
@section('meta_description', 'श्री कामदगिरि पीठम् सेवा ट्रस्ट, चित्रकूट धाम — वैदिक संस्कृत गुरुकुल, कामदगिरि गौशाला संरक्षण, अन्नक्षेत्र और सनातन संस्कृति संवर्धन प्रकल्प।')

@section('content')

  <!-- =========================================================================
       1. MOBILE EXPERIENCE (Dedicated Mobile Architecture: max-width: 768px)
       ========================================================================= -->
  <div class="seva-mobile-layout">
    
    <!-- Mobile Hero Header -->
    <div class="mobile-seva-hero">
      <div class="mobile-seva-tag">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
        श्री कामदगिरि सेवा प्रकल्प
      </div>
      <h2 class="mobile-seva-title">धर्म, सेवा और संस्कृति का संकल्प</h2>
      <p class="mobile-seva-sub">चित्रकूट धाम की पवित्र भूमि पर संचालित वैदिक शिक्षा, गौ संरक्षण एवं जनकल्याण सेवाएँ।</p>

      <!-- Horizontally Scrollable Impact Highlights -->
      <div class="mobile-metrics-scroll">
        <div class="mobile-metric-chip">
          <div class="metric-num">500+</div>
          <div class="metric-lbl">गुरुकुल बटुक छात्र</div>
        </div>
        <div class="mobile-metric-chip">
          <div class="metric-num">300+</div>
          <div class="metric-lbl">संरक्षित गौवंश</div>
        </div>
        <div class="mobile-metric-chip">
          <div class="metric-num">365 दिन</div>
          <div class="metric-lbl">अन्नक्षेत्र व दीपदान</div>
        </div>
      </div>

      <!-- Quick Category Filter Chips -->
      <div class="mobile-filter-chips">
        <button type="button" class="m-chip active" onclick="filterMobileSeva('all', this)">समस्त प्रकल्प</button>
        <button type="button" class="m-chip" onclick="filterMobileSeva('education', this)">गुरुकुल शिक्षा</button>
        <button type="button" class="m-chip" onclick="filterMobileSeva('seva', this)">गौसेवा</button>
        <button type="button" class="m-chip" onclick="filterMobileSeva('culture', this)">सांस्कृतिक</button>
      </div>
    </div>

    <!-- Mobile Seva Cards List (Full-Width, Stacked, Touch-Optimized) -->
    <div class="mobile-seva-container">
      @foreach($sevas as $seva)
        <article class="mobile-seva-card" data-category="{{ strtolower($seva->category) }}">
          
          <!-- 16:9 Aspect Ratio Media Banner -->
          <div class="mobile-card-media">
            <img src="{{ $seva->featured_image ?: asset('uploads/temple-test-photo-1792032209.webp') }}" alt="{{ $seva->title_hi }}" loading="lazy">
            <span class="mobile-category-badge">
              {{ $seva->category === 'education' ? 'गुरुकुल शिक्षा' : ($seva->category === 'seva' ? 'गौ संरक्षण' : 'सांस्कृतिक सेवा') }}
            </span>
            <span class="mobile-mission-num">MISSION #0{{ $loop->iteration }}</span>
          </div>

          <!-- Card Content Body -->
          <div class="mobile-card-body">
            <h2 class="mobile-card-title">{{ $seva->title_hi }}</h2>
            <div class="mobile-card-en">{{ $seva->title_en }}</div>
            
            <p class="mobile-card-desc">
              {{ $seva->summary_hi ?: Str::limit($seva->description_hi, 140) }}
            </p>

            <!-- Impact Highlights & Campaign Progress Box -->
            <div class="mobile-impact-box" style="padding: 12px 14px; background: rgba(180, 83, 9, 0.05); border-radius: 12px; margin-bottom: 14px;">
              <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 6px;">
                <span style="font-size: 11px; font-weight: 700; color: #166534;">₹{{ number_format($seva->raised_amount, 0) }} एकत्र</span>
                <span style="font-size: 11px; color: #7A4B29;">लक्ष्य: ₹{{ number_format($seva->target_amount, 0) }}</span>
              </div>
              <div style="height: 6px; background: rgba(180, 83, 9, 0.15); border-radius: 9999px; overflow: hidden; margin-bottom: 6px;">
                <div style="width: {{ $seva->progress_percent }}%; height: 100%; background: linear-gradient(90deg, #D95D0F, #16a34a); border-radius: 9999px;"></div>
              </div>
              <div style="display: flex; justify-content: space-between; font-size: 10.5px; color: #7A4B29; font-weight: 600;">
                <span>{{ $seva->progress_percent }}% पूर्ण</span>
                <span>{{ $seva->donors_count }} सहयोगी</span>
              </div>
            </div>

            <!-- Dual Mobile Touch CTA Buttons -->
            <div class="mobile-card-actions">
              <a href="{{ url('/seva/' . $seva->slug) }}" class="btn-m-details">
                विवरण पढ़ें &rarr;
              </a>
              <a href="{{ url('/donate?cause_id=' . ($seva->cause_id ?? '')) }}" class="btn-m-donate">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 21s-7-4.6-9-9.2C1.2 7.8 3.4 5 6.4 5c2 0 3.5 1.1 5.6 3.1C14.1 6.1 15.6 5 17.6 5 20.6 5 22.8 7.8 21 11.8 19 16.4 12 21 12 21z"/></svg>
                सहयोग करें
              </a>
            </div>

          </div>
        </article>
      @endforeach
    </div>

    <!-- Mobile Seva Volunteer Notice -->
    <div style="margin: 30px 16px 20px; background: #FFFDFC; border: 1px dashed rgba(180,83,9,0.35); border-radius: 16px; padding: 22px; text-align: center; box-shadow: 0 4px 18px rgba(130,70,20,0.05);">
      <h3 style="font-size: 16px; font-weight: 700; color: #2B1A12; margin: 0 0 8px;">क्या आप सेवा में समय देना चाहते हैं?</h3>
      <p style="font-size: 13px; color: #7A4B29; margin: 0 0 16px;">चित्रकूट धाम में शिक्षा, गौसेवा या तीर्थयात्री सेवा में स्वयंसेवक के रूप में सम्मिलित हों।</p>
      <a href="{{ url('/volunteer') }}" class="btn primary" style="display: inline-flex; font-size: 12.5px; font-weight: 800; padding: 11px 24px; border-radius: 9999px; text-decoration: none;">
        स्वयंसेवक पंजीकरण करें &rarr;
      </a>
    </div>

  </div>


  <!-- =========================================================================
       2. DESKTOP EXPERIENCE (Dedicated Desktop Architecture: min-width: 769px)
       ========================================================================= -->
  <div class="seva-desktop-layout">
    
    <section class="section" style="padding-top: 150px; padding-bottom: 90px; background: var(--paper); color: var(--ink);">
      <div class="container">
        
        <!-- Desktop Header with Trust Seal & Grand Typography -->
        <div class="section-head reveal" style="text-align: center; max-width: 860px; margin: 0 auto 60px;">
          <div class="eyebrow" style="color: #B45309; justify-content: center; letter-spacing: .22em; margin-bottom: 14px;">
            DIVINE SEVA & CHARITABLE MISSIONS
          </div>
          <h1 style="font: 700 clamp(38px, 4.5vw, 60px)/1.15 var(--dev); color: #2B1A12; margin-bottom: 16px;">
            धर्म, सेवा और संस्कृति का संकल्प
          </h1>
          <p style="color: #7A4B29; font-size: 17px; line-height: 1.8; max-width: 720px; margin: 0 auto;">
            श्री कामदगिरि पीठम् सेवा ट्रस्ट द्वारा संचालित निस्वार्थ सेवा प्रकल्प। आपकी एक छोटी सी सहभागिता निर्धन बालकों के भविष्य और गौमाता के संरक्षण में सहायक बनती है।
          </p>

          <!-- Desktop Category Filters -->
          <div style="display: inline-flex; gap: 8px; margin-top: 36px; background: #FFFDFC; padding: 6px; border-radius: 9999px; border: 1px solid rgba(180,83,9,0.2); box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
            <button type="button" class="desktop-filter-btn active" onclick="filterDesktopSeva('all', this)">समस्त प्रकल्प (All)</button>
            <button type="button" class="desktop-filter-btn" onclick="filterDesktopSeva('education', this)">गुरुकुल शिक्षा (Gurukul)</button>
            <button type="button" class="desktop-filter-btn" onclick="filterDesktopSeva('seva', this)">गौ संरक्षण (Gaushala)</button>
            <button type="button" class="desktop-filter-btn" onclick="filterDesktopSeva('culture', this)">धार्मिक आयोजन (Culture)</button>
          </div>
        </div>

        <!-- Desktop Magazine-Style Cards Grid -->
        <div class="desktop-seva-grid">
          @foreach($sevas as $seva)
            <div class="desktop-seva-card reveal" data-category="{{ strtolower($seva->category) }}">
              
              <!-- Card Image with Hover Zoom & Badges -->
              <div class="desktop-card-image-wrap">
                <img src="{{ $seva->featured_image ?: asset('uploads/temple-test-photo-1792032209.webp') }}" alt="{{ $seva->title_hi }}" loading="lazy">
                <span class="desktop-card-badge">
                  {{ $seva->category === 'education' ? 'वैदिक गुरुकुल' : ($seva->category === 'seva' ? 'गौसेवा प्रकल्प' : 'सांस्कृतिक अनुष्ठान') }}
                </span>
                <span class="desktop-mission-tag">MISSION #0{{ $loop->iteration }}</span>
              </div>

              <!-- Card Body Content -->
              <div class="desktop-card-body">
                <h2 class="desktop-card-title">{{ $seva->title_hi }}</h2>
                <div class="desktop-card-sub">{{ $seva->title_en }}</div>
                
                <p class="desktop-card-desc">
                  {{ Str::limit($seva->summary_hi ?: $seva->description_hi, 160) }}
                </p>

                <!-- Financial / Target Progress Box -->
                <div class="desktop-goal-box" style="margin-bottom: 18px;">
                  <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 6px;">
                    <div>
                      <span style="font-size: 11px; text-transform: uppercase; color: #aaa; letter-spacing: .08em; display: block;">एकत्र सहयोग</span>
                      <span style="font-size: 17px; font-weight: 800; color: var(--sand2);">₹{{ number_format($seva->raised_amount, 0) }}</span>
                    </div>
                    <div style="text-align: right;">
                      <span style="font-size: 11px; text-transform: uppercase; color: #aaa; letter-spacing: .08em; display: block;">अभियान लक्ष्य</span>
                      <span style="font-size: 14px; font-weight: 700; color: #ddd;">₹{{ number_format($seva->target_amount, 0) }}</span>
                    </div>
                  </div>
                  <div style="height: 6px; background: rgba(255,255,255,0.12); border-radius: 9999px; overflow: hidden; margin-bottom: 6px;">
                    <div style="width: {{ $seva->progress_percent }}%; height: 100%; background: linear-gradient(90deg, var(--sand), var(--sand2)); border-radius: 9999px;"></div>
                  </div>
                  <div style="display: flex; justify-content: space-between; font-size: 11px; color: #bbb;">
                    <span>{{ $seva->progress_percent }}% लक्ष्य पूर्ण</span>
                    <span>{{ $seva->donors_count }} श्रद्धावान सहयोगी</span>
                  </div>
                </div>

                <!-- Desktop Action Buttons -->
                <div class="desktop-card-actions">
                  <a href="{{ url('/seva/' . $seva->slug) }}" class="btn-d-details">
                    प्रकल्प विवरण पढ़ें &rarr;
                  </a>
                  <a href="{{ url('/donate?cause_id=' . ($seva->cause_id ?? '')) }}" class="btn-d-donate">
                    सहयोग करें
                  </a>
                </div>

              </div>
            </div>
          @endforeach
        </div>

      </div>
    </section>

  </div>

@endsection

@push('styles')
<style>
  /* =========================================================================
     CSS ARCHITECTURE: DEDICATED SEPARATE DESKTOP & MOBILE STYLING
     ========================================================================= */

  /* 1. Viewport Visibility Rules (No squash, no hardcoded overlapping pixels) */
  .seva-mobile-layout {
    display: none;
  }
  .seva-desktop-layout {
    display: block;
  }

  @media (max-width: 768px) {
    .seva-mobile-layout {
      display: block !important;
      background: var(--paper, #FAF4ED);
      color: #2B1A12;
      padding-top: 86px;
      padding-bottom: 100px;
      min-height: 100vh;
    }
    .seva-desktop-layout {
      display: none !important;
    }
  }

  /* 2. Mobile Seva Styling */
  .mobile-seva-hero {
    padding: 24px 18px 20px;
    background: linear-gradient(180deg, #FAF4ED 0%, #FBF7F0 100%);
    border-bottom: 1px solid rgba(180, 83, 9, 0.15);
  }
  .mobile-seva-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #FFFDFC;
    border: 1px solid rgba(180, 83, 9, 0.22);
    color: #B45309;
    font-size: 11px;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 9999px;
    text-transform: uppercase;
    letter-spacing: .08em;
    margin-bottom: 12px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.03);
  }
  .mobile-seva-title {
    font-size: 26px;
    font-weight: 700;
    line-height: 1.25;
    color: #2B1A12;
    margin: 0 0 10px;
    font-family: var(--dev);
  }
  .mobile-seva-sub {
    font-size: 14px;
    line-height: 1.6;
    color: #7A4B29;
    margin: 0 0 18px;
    font-family: var(--dev);
  }

  /* Mobile Metrics Horizontal Carousel */
  .mobile-metrics-scroll {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    padding-bottom: 14px;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
  }
  .mobile-metrics-scroll::-webkit-scrollbar {
    display: none;
  }
  .mobile-metric-chip {
    flex: 0 0 auto;
    background: #FFFDFC;
    border: 1px solid rgba(180, 83, 9, 0.18);
    border-radius: 12px;
    padding: 10px 14px;
    min-width: 110px;
    box-shadow: 0 2px 8px rgba(130, 70, 20, 0.04);
  }
  .mobile-metric-chip .metric-num {
    font-size: 16px;
    font-weight: 800;
    color: #D95D0F;
    font-family: var(--sans);
  }
  .mobile-metric-chip .metric-lbl {
    font-size: 10.5px;
    color: #7A4B29;
    white-space: nowrap;
    font-weight: 500;
  }

  /* Mobile Filter Chips */
  .mobile-filter-chips {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    padding-top: 8px;
    padding-bottom: 4px;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
  }
  .mobile-filter-chips::-webkit-scrollbar {
    display: none;
  }
  .m-chip {
    flex: 0 0 auto;
    background: #FFFDFC;
    color: #7A4B29;
    border: 1px solid rgba(180, 83, 9, 0.2);
    padding: 8px 16px;
    border-radius: 9999px;
    font-size: 12.5px;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 1px 4px rgba(0,0,0,0.03);
    transition: all .2s ease;
  }
  .m-chip.active {
    background: linear-gradient(135deg, #D95D0F 0%, #EA580C 100%);
    color: #ffffff;
    border-color: #D95D0F;
    font-weight: 800;
    box-shadow: 0 4px 14px rgba(217, 93, 15, 0.3);
  }

  /* Mobile Seva Cards */
  .mobile-seva-container {
    padding: 20px 16px 0;
  }
  .mobile-seva-card {
    background: #FFFDFC;
    border: 1px solid rgba(180, 83, 9, 0.18);
    border-radius: 20px;
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 8px 24px rgba(130, 70, 20, 0.06);
  }
  .mobile-card-media {
    position: relative;
    width: 100%;
    aspect-ratio: 16 / 9;
    overflow: hidden;
    background: #f0e8df;
  }
  .mobile-card-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .mobile-category-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: rgba(255, 253, 252, 0.95);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(180, 83, 9, 0.25);
    color: #B45309;
    font-size: 11px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 9999px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
  }
  .mobile-mission-num {
    position: absolute;
    bottom: 12px;
    right: 12px;
    background: rgba(43, 26, 18, 0.85);
    color: #fff;
    font-size: 9.5px;
    font-weight: 700;
    letter-spacing: .1em;
    padding: 3px 8px;
    border-radius: 4px;
    text-transform: uppercase;
  }
  .mobile-card-body {
    padding: 20px 18px;
  }
  .mobile-card-title {
    font-size: 20px;
    font-weight: 700;
    line-height: 1.35;
    color: #2B1A12;
    margin: 0 0 4px;
    font-family: var(--dev);
  }
  .mobile-card-en {
    font-size: 12.5px;
    color: #B45309;
    font-weight: 600;
    margin-bottom: 12px;
    letter-spacing: 0.02em;
  }
  .mobile-card-desc {
    font-size: 14px;
    line-height: 1.65;
    color: #7A4B29;
    margin: 0 0 16px;
    font-family: var(--dev);
  }
  .mobile-impact-box {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #FAF4ED;
    border: 1px solid rgba(180, 83, 9, 0.16);
    border-radius: 12px;
    padding: 12px 14px;
    margin-bottom: 16px;
  }
  .mobile-impact-box .impact-label {
    display: block;
    font-size: 10px;
    color: #7A4B29;
    text-transform: uppercase;
    letter-spacing: .08em;
  }
  .mobile-impact-box .impact-val {
    font-size: 17px;
    font-weight: 800;
    color: #D95D0F;
  }
  .impact-badge-active {
    font-size: 11px;
    color: #2D6A4F;
    font-weight: 700;
  }
  .mobile-card-actions {
    display: flex;
    gap: 10px;
  }
  .btn-m-details {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #FFFDFC;
    color: #2B1A12;
    border: 1px solid rgba(180, 83, 9, 0.25);
    border-radius: 9999px;
    padding: 12px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    text-align: center;
  }
  .btn-m-donate {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    background: linear-gradient(135deg, #D95D0F 0%, #EA580C 100%);
    color: #ffffff;
    border: none;
    border-radius: 9999px;
    padding: 12px;
    font-size: 13px;
    font-weight: 800;
    text-decoration: none;
    text-align: center;
    box-shadow: 0 4px 14px rgba(217, 93, 15, 0.35);
  }

  /* 3. Desktop Seva Styling */
  .desktop-filter-btn {
    background: transparent;
    border: 0;
    color: #7A4B29;
    padding: 8px 20px;
    border-radius: 9999px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all .2s ease;
  }
  .desktop-filter-btn.active, .desktop-filter-btn:hover {
    background: linear-gradient(135deg, #D95D0F 0%, #EA580C 100%);
    color: #ffffff;
    font-weight: 800;
    box-shadow: 0 4px 14px rgba(217, 93, 15, 0.3);
  }
  .desktop-seva-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
    gap: 32px;
  }
  .desktop-seva-card {
    background: #FFFDFC;
    border: 1px solid rgba(180, 83, 9, 0.18);
    border-radius: 20px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: 0 8px 25px rgba(130, 70, 20, 0.05);
    transition: transform .3s ease, border-color .3s ease, box-shadow .3s ease;
  }
  .desktop-seva-card:hover {
    transform: translateY(-6px);
    border-color: rgba(217, 93, 15, 0.4);
    box-shadow: 0 20px 45px rgba(130, 70, 20, 0.12);
  }
  .desktop-card-image-wrap {
    position: relative;
    height: 250px;
    width: 100%;
    overflow: hidden;
  }
  .desktop-card-image-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .8s ease;
  }
  .desktop-seva-card:hover .desktop-card-image-wrap img {
    transform: scale(1.06);
  }
  .desktop-card-badge {
    position: absolute;
    top: 16px;
    left: 16px;
    background: rgba(255, 253, 252, 0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(180, 83, 9, 0.25);
    color: #B45309;
    font-size: 11.5px;
    font-weight: 700;
    padding: 5px 14px;
    border-radius: 9999px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
  }
  .desktop-mission-tag {
    position: absolute;
    bottom: 14px;
    right: 16px;
    background: rgba(43, 26, 18, 0.85);
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .12em;
    padding: 4px 10px;
    border-radius: 4px;
  }
  .desktop-card-body {
    padding: 28px;
    display: flex;
    flex-direction: column;
    flex: 1;
  }
  .desktop-card-title {
    font-size: 22px;
    font-weight: 700;
    color: #2B1A12;
    margin: 0 0 6px;
    line-height: 1.3;
    font-family: var(--dev);
  }
  .desktop-card-sub {
    font-size: 13px;
    color: #B45309;
    font-weight: 600;
    margin-bottom: 14px;
  }
  .desktop-card-desc {
    font-size: 14.5px;
    line-height: 1.7;
    color: #7A4B29;
    margin: 0 0 24px;
    flex: 1;
  }
  .desktop-goal-box {
    background: #FAF4ED;
    border: 1px solid rgba(180, 83, 9, 0.16);
    border-radius: 12px;
    padding: 14px 18px;
    margin-bottom: 22px;
  }
  .desktop-card-actions {
    display: flex;
    gap: 12px;
  }
  .btn-d-details {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #FFFDFC;
    color: #2B1A12;
    border: 1px solid rgba(180, 83, 9, 0.25);
    border-radius: 9999px;
    padding: 12px 18px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    transition: background .2s, color .2s;
  }
  .btn-d-details:hover {
    background: #FAF4ED;
    color: #D95D0F;
  }
  .btn-d-donate {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #D95D0F 0%, #EA580C 100%);
    color: #ffffff;
    border: none;
    border-radius: 9999px;
    padding: 12px 18px;
    font-size: 13px;
    font-weight: 800;
    text-decoration: none;
    box-shadow: 0 4px 16px rgba(217, 93, 15, 0.35);
    transition: transform .2s, box-shadow .2s;
  }
  .btn-d-donate:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(217, 93, 15, 0.45);
    color: #ffffff;
  }
</style>
@endpush

@push('scripts')
<script>
  function filterMobileSeva(cat, btn) {
    document.querySelectorAll('.m-chip').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    
    document.querySelectorAll('.mobile-seva-card').forEach(card => {
      const cardCat = card.getAttribute('data-category');
      if (cat === 'all' || cardCat === cat) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';
      }
    });
  }

  function filterDesktopSeva(cat, btn) {
    document.querySelectorAll('.desktop-filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    
    document.querySelectorAll('.desktop-seva-card').forEach(card => {
      const cardCat = card.getAttribute('data-category');
      if (cat === 'all' || cardCat === cat) {
        card.style.display = 'flex';
      } else {
        card.style.display = 'none';
      }
    });
  }
</script>
@endpush
