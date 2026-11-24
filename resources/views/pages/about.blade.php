@extends('layouts.app')

@section('title', 'ट्रस्ट के बारे में | श्री कामदगिरि पीठम् सेवा ट्रस्ट')
@section('meta_description', 'श्री कामदगिरि पीठम् सेवा ट्रस्ट चित्रकूट धाम: पर्यावरण बचाओ अभियान, मंदाकिनी नदी स्वच्छता, गौसेवा, निःशुल्क गुरुकुल और संरक्षित चित्रकूट का पावन संकल्प।')

@section('content')

  <!-- HERO & PRELUDE SECTION -->
  <section class="section"
    style="padding-top: 140px; padding-bottom: 50px; background: linear-gradient(180deg, #FAF4ED 0%, #FFFDFC 100%);">
    <div class="site-container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">

      <!-- Breadcrumb -->
      <nav aria-label="Breadcrumb"
        style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: #7A4B29; margin-bottom: 24px;">
        <a href="{{ url('/') }}" style="color: #B45309; text-decoration: none; font-weight: 600;">मुख्य पृष्ठ (Home)</a>
        <span>/</span>
        <span style="color: #2B1A12; font-weight: 700;">ट्रस्ट के बारे में (About Trust)</span>
      </nav>

      <div style="text-align: center; max-width: 900px; margin: 0 auto 50px;">
        @if(!empty($homeSettings['trust_eyebrow']))
          <div class="sr-only" style="display: none;">{{ $homeSettings['trust_eyebrow'] }}</div>
        @endif
        <h1
          style="font-family: 'Noto Serif', serif; font-size: clamp(30px, 4vw, 48px); font-weight: 700; color: #2B1A12; line-height: 1.25; margin: 0 0 16px;">
          {{ $orgSettings->org_name ?? 'श्री कामदगिरि पीठम् सेवा ट्रस्ट' }}<br>
          <span
            style="color: #B45309; font-size: 0.85em;">{{ !empty($homeSettings['trust_tagline']) ? $homeSettings['trust_tagline'] : 'चित्रकूट धाम — तपोभूमि सेवा एवं संरक्षण' }}</span>
        </h1>

        <p style="font-size: 16px; line-height: 1.8; color: #7A4B29; margin: 0 auto; max-width: 780px;">
          {{ !empty($homeSettings['trust_lead']) ? $homeSettings['trust_lead'] : 'भगवान श्रीराम की पावन तपोस्थली चित्रकूट के दिव्य आध्यात्मिक गौरव, वैदिक संस्कृति और कामदगिरि पर्वत व पावन मंदाकिनी के पर्यावरणीय संरक्षण हेतु समर्पित एक निष्काम सेवाभावी न्यास।' }}
        </p>
      </div>

      <!-- Trust Intro Grid: Narrative & Sacred Image -->
      <div
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 40px; align-items: center; margin-bottom: 60px;">
        <div
          style="background: #FFFDFC; border: 1.5px solid rgba(180, 83, 9, 0.2); border-radius: 24px; padding: clamp(24px, 4vw, 36px); box-shadow: 0 8px 30px rgba(180, 83, 9, 0.06);">
          <h2
            style="font-family: 'Noto Serif', serif; font-size: 24px; font-weight: 700; color: #2B1A12; margin: 0 0 16px; line-height: 1.35;">
            {{ !empty($homeSettings['trust_heading']) ? $homeSettings['trust_heading'] : 'धर्म, संस्कृति और प्रकृति की त्रिवेणी' }}
          </h2>
          <p style="font-size: 14.5px; line-height: 1.8; color: #7A4B29; margin-bottom: 14px;">
            {{ !empty($homeSettings['trust_desc']) ? $homeSettings['trust_desc'] : 'त्रेतायुग के वनवास काल में मर्यादा पुरुषोत्तम भगवान श्रीराम, माता जानकी एवं लक्ष्मण जी ने इसी पावन तपोवन में साढ़े ग्यारह वर्ष व्यतीत कर धर्म की प्रतिष्ठा की थी। महर्षि वाल्मीकि के अनुसार, कामदगिरि पर्वत साक्षात् भगवान श्री कामतानाथ जी का विग्रह स्वरूप है जिसकी पावन परिक्रमा से जीवन के समस्त कष्टों का निवारण होता है।' }}
          </p>
          <p style="font-size: 14.5px; line-height: 1.8; color: #7A4B29; margin-bottom: 20px;">
            {{ !empty($homeSettings['trust_p2']) ? $homeSettings['trust_p2'] : 'श्री कामदगिरि पीठम् सेवा ट्रस्ट इसी अनादि पावन परंपरा का संवाहक बनकर सनातन धर्म, वैदिक शिक्षा, असहाय गोवंश के पोषण, नित्य अन्नक्षेत्र एवं चित्रकूट की पवित्रता व पर्यावरण के संरक्षण में अहर्निश समर्पित है।' }}
          </p>
          <div style="display: flex; flex-wrap: wrap; gap: 10px;">
            <span
              style="font-size: 11.5px; font-weight: 700; color: #B45309; background: #FAF4ED; padding: 4px 12px; border-radius: 9999px; border: 1px solid rgba(180, 83, 9, 0.2);">
              ✓ पंजीकृत धार्मिक व जन-कल्याणकारी न्यास
            </span>
            @if($orgSettings->is_80g_enabled ?? false)
              <span
                style="font-size: 11.5px; font-weight: 700; color: #2D6A4F; background: #FAF4ED; padding: 4px 12px; border-radius: 9999px; border: 1px solid rgba(45, 106, 79, 0.25);">
                ✓ १००% आयकर छूट (80G प्रमाणित)
              </span>
            @else
              <span
                style="font-size: 11.5px; font-weight: 700; color: #2D6A4F; background: #FAF4ED; padding: 4px 12px; border-radius: 9999px; border: 1px solid rgba(45, 106, 79, 0.25);">
                ✓ १००% पारदर्शी सेवा संकल्प
              </span>
            @endif
          </div>
        </div>

        <div style="position: relative;">
          <div
            style="border-radius: 24px; overflow: hidden; border: 2px solid rgba(180, 83, 9, 0.22); box-shadow: 0 16px 40px rgba(180, 83, 9, 0.12);">
            <img
              src="{{ !empty($homeSettings['about_photo_1_url']) ? $homeSettings['about_photo_1_url'] : ($orgSettings->hero_banner_path ?: asset('uploads/temple-test-photo-1792032209.webp')) }}"
              alt="{{ $orgSettings->org_name ?? 'श्री कामदगिरि पीठम् सेवा ट्रस्ट चित्रकूट धाम' }}" loading="lazy"
              style="width: 100%; height: 380px; object-fit: cover; display: block;">
          </div>
          <div
            style="position: absolute; bottom: -20px; right: 20px; background: #FAF4ED; border: 1.5px solid rgba(180, 83, 9, 0.3); border-radius: 16px; padding: 12px 20px; box-shadow: 0 8px 24px rgba(43, 26, 18, 0.1);">
            <span
              style="font-family: 'Noto Serif', serif; font-size: 18px; font-weight: 800; color: #D95D0F; display: block;">{{ !empty($homeSettings['stat_years']) ? $homeSettings['stat_years'] : '२५+' }}
              वर्ष</span>
            <span
              style="font-size: 11.5px; font-weight: 700; color: #7A4B29;">{{ !empty($homeSettings['stat_years_title']) ? $homeSettings['stat_years_title'] : 'अखंड सेवा व संरक्षण साधना' }}</span>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- ========================================================
         GRAND FEATURE SECTION: पर्यावरण बचाओ अभियान (TRUST VISION)
         ======================================================== -->
  <section class="section" style="padding: 70px 0; background: #F4EFE6; position: relative;">
    <div class="site-container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">

      <!-- Section Title & Vision Headline -->
      <div style="text-align: center; max-width: 860px; margin: 0 auto 50px;">
        <span style="font-size: 11.5px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: #B45309; display: block; margin-bottom: 8px;">॥ हमारा विज़न (Trust Vision) एवं सेवा संकल्प ॥</span>
        <h2
          style="font-family: 'Noto Serif', serif; font-size: clamp(26px, 3.4vw, 40px); font-weight: 700; color: #2B1A12; line-height: 1.3; margin: 0 0 16px;">
          {{ $homeSettings['about_mission_heading'] ?? 'पर्यावरण बचाओ अभियान: स्वच्छ, हरित एवं संरक्षित चित्रकूट' }}
        </h2>

        <p style="font-size: 15px; line-height: 1.8; color: #5C381E; margin: 0 auto;">
          {{ $homeSettings['about_mission_quote'] ?? '"प्रकृति संरक्षण ही सच्ची राम-सेवा है।" — चित्रकूट केवल एक तीर्थ नहीं, अपितु भगवान श्रीराम का सजल तपोवन है। ट्रस्ट का परम ध्येय और संकल्प है कि चित्रकूट के प्राचीन वनों, अमृतमयी मंदाकिनी और पावन कामदगिरि पर्वत की पवित्रता व जैव-विविधता को आधुनिक प्रदूषण से सुरक्षित रखा जाए।' }}
        </p>
      </div>

      <!-- 4 Pillars of the Environmental Campaign Grid -->
      <div
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(270px, 1fr)); gap: 24px; margin-bottom: 50px;">

        <!-- Pillar 1: Mandakini River Cleanliness -->
        <div
          style="background: #FFFDFC; border: 1.5px solid rgba(45, 106, 79, 0.25); border-radius: 20px; overflow: hidden; box-shadow: 0 4px 18px rgba(45, 106, 79, 0.06); display: flex; flex-direction: column; justify-content: space-between;">
          <div>
            <div style="height: 160px; width: 100%; position: relative; overflow: hidden;">
              <img src="{{ $homeSettings['mission_1_image_url'] ?? asset('images/ram-ghat-aarti.webp') }}"
                alt="{{ $homeSettings['mission_1_title'] ?? 'मंदाकिनी नदी स्वच्छता' }}" loading="lazy"
                style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;"
                onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
              <div
                style="position: absolute; top: 12px; left: 12px; background: rgba(45, 106, 79, 0.85); backdrop-filter: blur(6px); color: #fff; font-size: 10.5px; font-weight: 800; padding: 3px 10px; border-radius: 9999px;">
                अभियान स्तंभ १
              </div>
            </div>
            <div style="padding: 20px 22px 14px;">
              <h3
                style="font-family: 'Noto Serif', serif; font-size: 17.5px; font-weight: 700; color: #2B1A12; margin: 0 0 8px;">
                {{ $homeSettings['mission_1_title'] ?? 'मंदाकिनी नदी स्वच्छता व अविरलता' }}
              </h3>
              <p style="font-size: 13px; line-height: 1.65; color: #7A4B29; margin: 0;">
                {{ $homeSettings['mission_1_desc'] ?? 'रामघाट, राघव प्रयाग एवं स्फटिक शिला पर दैनिक स्वच्छता अभियान। केवल मिट्टी के दीयों एवं प्राकृतिक पुष्पों के अर्पण की प्रेरणा, तथा नदी में प्लास्टिक व अजैविक अपशिष्ट विसर्जन पर पूर्ण रोक।' }}
              </p>
            </div>
          </div>
          <div
            style="padding: 10px 22px 18px; font-size: 11.5px; font-weight: 700; color: #2D6A4F; border-top: 1px dashed rgba(45, 106, 79, 0.2); margin: 0 4px;">
            ✓ {{ $homeSettings['mission_1_tag'] ?? 'नित्य घाट स्वच्छता सेनानी दल' }}
          </div>
        </div>

        <!-- Pillar 2: Plastic-Free Kamadgiri Parikrama -->
        <div
          style="background: #FFFDFC; border: 1.5px solid rgba(217, 93, 15, 0.25); border-radius: 20px; overflow: hidden; box-shadow: 0 4px 18px rgba(217, 93, 15, 0.06); display: flex; flex-direction: column; justify-content: space-between;">
          <div>
            <div style="height: 160px; width: 100%; position: relative; overflow: hidden;">
              <img src="{{ $homeSettings['mission_2_image_url'] ?? asset('images/kamadgiri-mountain.webp') }}"
                alt="{{ $homeSettings['mission_2_title'] ?? 'प्लास्टिक-मुक्त कामदगिरि परिक्रमा' }}" loading="lazy"
                style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;"
                onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
              <div
                style="position: absolute; top: 12px; left: 12px; background: rgba(217, 93, 15, 0.85); backdrop-filter: blur(6px); color: #fff; font-size: 10.5px; font-weight: 800; padding: 3px 10px; border-radius: 9999px;">
                अभियान स्तंभ २
              </div>
            </div>
            <div style="padding: 20px 22px 14px;">
              <h3
                style="font-family: 'Noto Serif', serif; font-size: 17.5px; font-weight: 700; color: #2B1A12; margin: 0 0 8px;">
                {{ $homeSettings['mission_2_title'] ?? 'प्लास्टिक-मुक्त कामदगिरि परिक्रमा' }}
              </h3>
              <p style="font-size: 13px; line-height: 1.65; color: #7A4B29; margin: 0;">
                {{ $homeSettings['mission_2_desc'] ?? '५ किलोमीटर लंबे पावन परिक्रमा मार्ग को \'सिंगल-यूज प्लास्टिक मुक्त\' क्षेत्र बनाने का संकल्प। पर्यावरण-अनुकूल अपशिष्ट पात्र (Dustbins) स्थापना, कपड़े के थैलों का वितरण एवं प्लास्टिक विकल्प जागरूकता।' }}
              </p>
            </div>
          </div>
          <div
            style="padding: 10px 22px 18px; font-size: 11.5px; font-weight: 700; color: #D95D0F; border-top: 1px dashed rgba(217, 93, 15, 0.2); margin: 0 4px;">
            ✓ {{ $homeSettings['mission_2_tag'] ?? 'शून्य प्लास्टिक परिक्रमा संकल्प' }}
          </div>
        </div>

        <!-- Pillar 3: 10,000+ Sacred Agro-Forestry Drive -->
        <div
          style="background: #FFFDFC; border: 1.5px solid rgba(45, 106, 79, 0.25); border-radius: 20px; overflow: hidden; box-shadow: 0 4px 18px rgba(45, 106, 79, 0.06); display: flex; flex-direction: column; justify-content: space-between;">
          <div>
            <div style="height: 160px; width: 100%; position: relative; overflow: hidden;">
              <img
                src="{{ $homeSettings['mission_3_image_url'] ?? 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=800&q=80' }}"
                alt="{{ $homeSettings['mission_3_title'] ?? '१०,०००+ देववृक्ष रोपण' }}" loading="lazy"
                style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;"
                onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
              <div
                style="position: absolute; top: 12px; left: 12px; background: rgba(45, 106, 79, 0.85); backdrop-filter: blur(6px); color: #fff; font-size: 10.5px; font-weight: 800; padding: 3px 10px; border-radius: 9999px;">
                अभियान स्तंभ ३
              </div>
            </div>
            <div style="padding: 20px 22px 14px;">
              <h3
                style="font-family: 'Noto Serif', serif; font-size: 17.5px; font-weight: 700; color: #2B1A12; margin: 0 0 8px;">
                {{ $homeSettings['mission_3_title'] ?? '१०,०००+ देववृक्ष रोपण व हरितिमा' }}
              </h3>
              <p style="font-size: 13px; line-height: 1.65; color: #7A4B29; margin: 0;">
                {{ $homeSettings['mission_3_desc'] ?? 'रामायण कालीन पावन वृक्षों—पीपल, बरगद (वट), नीम, आँवला, कदंब, बेलपत्र एवं तुलसी का सघन रोपण। पर्वत श्रृंखलाओं का हरित आवरण बढ़ाकर भू-क्षरण रोकना एवं प्राकृतिक वन्यजीव आवास संवर्धन।' }}
              </p>
            </div>
          </div>
          <div
            style="padding: 10px 22px 18px; font-size: 11.5px; font-weight: 700; color: #2D6A4F; border-top: 1px dashed rgba(45, 106, 79, 0.2); margin: 0 4px;">
            ✓ {{ $homeSettings['mission_3_tag'] ?? '१०,०००+ वृक्ष रोपित एवं संरक्षित' }}
          </div>
        </div>

        <!-- Pillar 4: Gaushala Circular Ecology & Organic Compost -->
        <div
          style="background: #FFFDFC; border: 1.5px solid rgba(180, 83, 9, 0.25); border-radius: 20px; overflow: hidden; box-shadow: 0 4px 18px rgba(180, 83, 9, 0.06); display: flex; flex-direction: column; justify-content: space-between;">
          <div>
            <div style="height: 160px; width: 100%; position: relative; overflow: hidden;">
              <img
                src="{{ $homeSettings['mission_4_image_url'] ?? 'https://images.unsplash.com/photo-1570042225831-d98fa7577f1e?auto=format&fit=crop&w=800&q=80' }}"
                alt="{{ $homeSettings['mission_4_title'] ?? 'गौमय जैविक खाद' }}" loading="lazy"
                style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;"
                onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
              <div
                style="position: absolute; top: 12px; left: 12px; background: rgba(180, 83, 9, 0.85); backdrop-filter: blur(6px); color: #fff; font-size: 10.5px; font-weight: 800; padding: 3px 10px; border-radius: 9999px;">
                अभियान स्तंभ ४
              </div>
            </div>
            <div style="padding: 20px 22px 14px;">
              <h3
                style="font-family: 'Noto Serif', serif; font-size: 17.5px; font-weight: 700; color: #2B1A12; margin: 0 0 8px;">
                {{ $homeSettings['mission_4_title'] ?? 'गौमय जैविक खाद व शून्य रसायन' }}
              </h3>
              <p style="font-size: 13px; line-height: 1.65; color: #7A4B29; margin: 0;">
                {{ $homeSettings['mission_4_desc'] ?? 'कामदगिरि गौशाला के २५०+ गोवंश के गोमय व गोमूत्र से \'जीवामृत\' व उच्च गुणवत्ता युक्त जैविक खाद का निर्माण। चित्रकूट के उपवनों को रासायनिक खादों व कीटनाशकों से मुक्त रखने का प्राकृतिक तंत्र।' }}
              </p>
            </div>
          </div>
          <div
            style="padding: 10px 22px 18px; font-size: 11.5px; font-weight: 700; color: #B45309; border-top: 1px dashed rgba(180, 83, 9, 0.2); margin: 0 4px;">
            ✓ {{ $homeSettings['mission_4_tag'] ?? '१००% प्राकृतिक चक्रीय पर्यावरण' }}
          </div>
        </div>

      </div>

      <!-- Devotional Pilgrim's Pledge Banner -->
      <div
        style="background: linear-gradient(135deg, #2D6A4F 0%, #1B4332 100%); border-radius: 20px; padding: clamp(24px, 4vw, 36px); color: #fff; box-shadow: 0 12px 36px rgba(45, 106, 79, 0.25);">
        <div
          style="display: flex; flex-direction: column; md:flex-row; gap: 24px; align-items: center; justify-content: space-between;">
          <div style="max-width: 760px;">
            <div
              style="display: inline-flex; align-items: center; gap: 6px; background: rgba(255, 255, 255, 0.15); padding: 4px 12px; border-radius: 9999px; font-size: 11px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 12px;">
              ★ तीर्थयात्री पर्यावरण एवं स्वच्छता संकल्प (Pilgrim Pledge)
            </div>
            <h3
              style="font-family: 'Noto Serif', serif; font-size: clamp(20px, 2.5vw, 26px); font-weight: 700; color: #FFFDFC; margin: 0 0 10px; line-height: 1.35;">
              {{ $homeSettings['pledge_title'] ?? '"चित्रकूट आएं तो श्रद्धा लाएं, प्लास्टिक न फैलाएं — प्रकृति को स्वच्छ व संरक्षित रखें"' }}
            </h3>
            <p style="font-size: 13.5px; line-height: 1.7; color: rgba(255, 255, 255, 0.9); margin: 0;">
              {{ $homeSettings['pledge_desc'] ?? 'भगवान श्रीराम की इस तपोभूमि की पवित्रता हम सभी का पावन दायित्व है। मंदाकिनी नदी में केवल प्रार्थना अर्पित करें, कचरा नहीं। कामदगिरि परिक्रमा मार्ग को कूड़ा-मुक्त रखें। अपने प्रत्येक दर्शन को पर्यावरण संरक्षण का संकल्प बनाएं।' }}
            </p>
          </div>

          <div style="display: flex; flex-direction: column; gap: 10px; flex-shrink: 0; width: 100%; max-width: 260px;">
            <a href="{{ url('/donate?cause=environment') }}"
              style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: #FAF4ED; color: #1B4332; font-weight: 800; font-size: 13px; padding: 12px 20px; border-radius: 9999px; text-decoration: none; box-shadow: 0 4px 14px rgba(0,0,0,0.15); transition: transform 0.2s ease;">
              <span>पर्यावरण अभियान में सहयोग करें</span> &rarr;
            </a>
            <a href="{{ url('/contact') }}"
              style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: rgba(255,255,255,0.12); color: #FFFDFC; font-weight: 700; font-size: 12px; padding: 10px 16px; border-radius: 9999px; text-decoration: none; border: 1px solid rgba(255,255,255,0.25);">
              <span>स्वच्छता स्वयंसेवक बनें</span>
            </a>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- ========================================================
         CORE DHARMA PILLARS: SEVA WORK SHOWCASE (PHOTOS FROM CAUSES)
         ======================================================== -->
  <section class="section" style="padding: 70px 0; background: #FFFDFC;" id="trust-work">
    <div class="site-container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">

      <div style="text-align: center; max-width: 840px; margin: 0 auto 50px;">
        <span
          style="font-size: 11px; font-weight: 800; letter-spacing: 0.14em; text-transform: uppercase; color: #D95D0F; display: block; margin-bottom: 8px;">
          ॥ प्रत्यक्ष सेवा कार्य दर्शन ॥ • Trust Seva Work in Action
        </span>
        <h2
          style="font-family: 'Noto Serif', serif; font-size: clamp(26px, 3.2vw, 36px); font-weight: 700; color: #2B1A12; margin: 0 0 12px;">
          ट्रस्ट द्वारा संचालित पावन सेवा प्रकल्प — जो हम कर रहे हैं
        </h2>
        <p style="font-size: 14.5px; color: #7A4B29; line-height: 1.7; margin: 0;">
          भक्तों एवं दानदाताओं के पावन सहयोग से चित्रकूट धाम में संचालित वास्तविक सेवा कार्य—निःशुल्क गुरुकुल, गौसंरक्षण
          एवं नित्य अन्नक्षेत्र का सचित्र विवरण।
        </p>
      </div>

      <!-- Real Seva Work Grid (Directly Utilizing Cause & Seva Project Images) -->
      <div
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px; margin-bottom: 50px;">

        @if(isset($sevas) && $sevas->isNotEmpty())
          @foreach($sevas as $seva)
            @php
              $photo = $seva->featured_image ?: ($seva->cause?->image_path ?: 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?auto=format&fit=crop&w=1200&q=85');
              $accentColor = '#B45309';
              $badgeText = 'सक्रिय सेवा कार्य';
              $tagline = 'चित्रकूट तपोभूमि सेवा';
              if (str_contains($seva->slug, 'cow') || str_contains($seva->slug, 'gaushala')) {
                $accentColor = '#2D6A4F';
                $badgeText = 'गौ सेवा व संवर्धन';
                $tagline = '२५०+ संरक्षित गोवंश';
              } elseif (str_contains($seva->slug, 'gurukul') || str_contains($seva->slug, 'sanskrit')) {
                $accentColor = '#D95D0F';
                $badgeText = 'निःशुल्क वेद शिक्षा';
                $tagline = '५००+ बटुक छात्र';
              } elseif (str_contains($seva->slug, 'event') || str_contains($seva->slug, 'anna')) {
                $accentColor = '#B45309';
                $badgeText = 'अखंड अन्नक्षेत्र भंडारा';
                $tagline = 'साधु-संत व तीर्थयात्री सेवा';
              }
            @endphp
            <div
              style="background: #FFFDFC; border: 1.5px solid rgba(180, 83, 9, 0.2); border-radius: 20px; overflow: hidden; box-shadow: 0 8px 24px rgba(43, 26, 18, 0.06); display: flex; flex-direction: column; justify-content: space-between; transition: transform 0.3s ease, box-shadow 0.3s ease;"
              onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 14px 32px rgba(43, 26, 18, 0.12)';"
              onmouseout="this.style.transform='none'; this.style.boxShadow='0 8px 24px rgba(43, 26, 18, 0.06)';">
              <div>
                <!-- Seva Authentic Photo with Zoom Effect -->
                <div style="position: relative; height: 210px; width: 100%; overflow: hidden; background: #FAF4ED;">
                  <img src="{{ $photo }}" alt="{{ $seva->title }}" loading="lazy"
                    style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease;"
                    onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                  <div
                    style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(0,0,0,0.15) 0%, rgba(0,0,0,0.6) 100%);">
                  </div>

                  <div
                    style="position: absolute; top: 12px; left: 14px; background: rgba(255,255,255,0.92); backdrop-filter: blur(8px); padding: 4px 12px; border-radius: 9999px; font-size: 11px; font-weight: 800; color: {{ $accentColor }};">
                    {{ $badgeText }}
                  </div>

                  <div
                    style="position: absolute; bottom: 12px; left: 14px; right: 14px; display: flex; align-items: center; justify-content: space-between; color: #fff; font-size: 12px; font-weight: 700;">
                    <span>✓ {{ $tagline }}</span>
                    <span style="background: rgba(0,0,0,0.4); padding: 2px 8px; border-radius: 6px;">चित्रकूट धाम</span>
                  </div>
                </div>

                <!-- Seva Content -->
                <div style="padding: 22px 24px 16px;">
                  <h3
                    style="font-family: 'Noto Serif', serif; font-size: 19px; font-weight: 700; color: #2B1A12; margin: 0 0 10px; line-height: 1.35;">
                    {{ $seva->title }}
                  </h3>
                  <p style="font-size: 13.5px; line-height: 1.7; color: #7A4B29; margin: 0 0 16px;">
                    {{ $seva->summary_hi ?: Str::limit(strip_tags($seva->description), 160) }}
                  </p>
                </div>
              </div>

              <!-- Action Link -->
              <div
                style="padding: 14px 24px 20px; border-top: 1px dashed rgba(180, 83, 9, 0.2); display: flex; align-items: center; justify-content: space-between;">
                <a href="{{ url('/seva/' . $seva->slug) }}"
                  style="display: inline-flex; align-items: center; gap: 6px; font-size: 12.5px; font-weight: 800; color: {{ $accentColor }}; text-decoration: none;">
                  <span>सेवा कार्य संपूर्ण विवरण</span> &rarr;
                </a>
                <a href="{{ url('/donate?cause=' . $seva->slug) }}"
                  style="display: inline-flex; align-items: center; gap: 4px; font-size: 12px; font-weight: 700; color: #fff; background: {{ $accentColor }}; padding: 6px 14px; border-radius: 9999px; text-decoration: none;">
                  <span>सहयोग करें</span>
                </a>
              </div>
            </div>
          @endforeach
        @else
          <!-- Fallback 1: Gurukul -->
          <div
            style="background: #FFFDFC; border: 1.5px solid rgba(217, 93, 15, 0.25); border-radius: 20px; overflow: hidden; box-shadow: 0 8px 24px rgba(43, 26, 18, 0.06); display: flex; flex-direction: column; justify-content: space-between;">
            <div>
              <div style="height: 210px; width: 100%; position: relative; overflow: hidden;">
                <img src="https://images.unsplash.com/photo-1542810634-71277d95dcbb?auto=format&fit=crop&w=1200&q=85"
                  alt="ऋषिकुल संस्कृत गुरुकुल" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
                <div
                  style="position: absolute; top: 12px; left: 14px; background: rgba(255,255,255,0.92); padding: 4px 12px; border-radius: 9999px; font-size: 11px; font-weight: 800; color: #D95D0F;">
                  निःशुल्क वेद शिक्षा</div>
              </div>
              <div style="padding: 22px 24px 16px;">
                <h3
                  style="font-family: 'Noto Serif', serif; font-size: 19px; font-weight: 700; color: #2B1A12; margin: 0 0 10px;">
                  ऋषिकुल संस्कृत गुरुकुल व वेद पाठशाला
                </h3>
                <p style="font-size: 13.5px; line-height: 1.7; color: #7A4B29; margin: 0;">
                  अखिल भारतीय स्तर पर बटुकों को चारों वेदों, उपनिषदों, व्याकरण, कर्मकांड एवं सनातन संस्कारों की निःशुल्क
                  आवासीय शिक्षा प्रदान की जाती है।
                </p>
              </div>
            </div>
            <div
              style="padding: 14px 24px 20px; border-top: 1px dashed rgba(180, 83, 9, 0.2); display: flex; align-items: center; justify-content: space-between;">
              <a href="{{ url('/seva/sanskrit-gurukul') }}"
                style="font-size: 12.5px; font-weight: 800; color: #D95D0F; text-decoration: none;">View Details &rarr;</a>
              <a href="{{ url('/donate?cause=sanskrit-vidyalaya') }}"
                style="font-size: 12px; font-weight: 700; color: #fff; background: #D95D0F; padding: 6px 14px; border-radius: 9999px; text-decoration: none;">Donate
                Now</a>
            </div>
          </div>

          <!-- Fallback 2: Gaushala -->
          <div
            style="background: #FFFDFC; border: 1.5px solid rgba(45, 106, 79, 0.25); border-radius: 20px; overflow: hidden; box-shadow: 0 8px 24px rgba(43, 26, 18, 0.06); display: flex; flex-direction: column; justify-content: space-between;">
            <div>
              <div style="height: 210px; width: 100%; position: relative; overflow: hidden;">
                <img src="https://images.unsplash.com/photo-1560493676-04071c5f467b?auto=format&fit=crop&w=1500&q=85"
                  alt="कामदगिरि गौशाला" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
                <div
                  style="position: absolute; top: 12px; left: 14px; background: rgba(255,255,255,0.92); padding: 4px 12px; border-radius: 9999px; font-size: 11px; font-weight: 800; color: #2D6A4F;">
                  गौ संरक्षण</div>
              </div>
              <div style="padding: 22px 24px 16px;">
                <h3
                  style="font-family: 'Noto Serif', serif; font-size: 19px; font-weight: 700; color: #2B1A12; margin: 0 0 10px;">
                  कामदगिरि गौशाला (गौ सेवा एवं संवर्धन)
                </h3>
                <p style="font-size: 13.5px; line-height: 1.7; color: #7A4B29; margin: 0;">
                  निराश्रित, वृद्ध, रोगग्रस्त एवं असहाय गोवंश की नित्य श्रद्धापूर्ण सेवा, नियमित पशु-चिकित्सा एवं पोषक हरा
                  चारा व्यवस्था।
                </p>
              </div>
            </div>
            <div
              style="padding: 14px 24px 20px; border-top: 1px dashed rgba(180, 83, 9, 0.2); display: flex; align-items: center; justify-content: space-between;">
              <a href="{{ url('/seva/gaushala-protection') }}"
                style="font-size: 12.5px; font-weight: 800; color: #2D6A4F; text-decoration: none;">View Details &rarr;</a>
              <a href="{{ url('/donate?cause=gaushala-seva') }}"
                style="font-size: 12px; font-weight: 700; color: #fff; background: #2D6A4F; padding: 6px 14px; border-radius: 9999px; text-decoration: none;">Donate
                Now</a>
            </div>
          </div>

          <!-- Fallback 3: Annakshetra -->
          <div
            style="background: #FFFDFC; border: 1.5px solid rgba(180, 83, 9, 0.25); border-radius: 20px; overflow: hidden; box-shadow: 0 8px 24px rgba(43, 26, 18, 0.06); display: flex; flex-direction: column; justify-content: space-between;">
            <div>
              <div style="height: 210px; width: 100%; position: relative; overflow: hidden;">
                <img src="https://images.unsplash.com/photo-1604608672516-f1b9e1e3b7aa?auto=format&fit=crop&w=1200&q=85"
                  alt="नित्य अखंड अन्नक्षेत्र" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
                <div
                  style="position: absolute; top: 12px; left: 14px; background: rgba(255,255,255,0.92); padding: 4px 12px; border-radius: 9999px; font-size: 11px; font-weight: 800; color: #B45309;">
                  अखंड अन्नदान</div>
              </div>
              <div style="padding: 22px 24px 16px;">
                <h3
                  style="font-family: 'Noto Serif', serif; font-size: 19px; font-weight: 700; color: #2B1A12; margin: 0 0 10px;">
                  नित्य अखंड अन्नक्षेत्र सेवा
                </h3>
                <p style="font-size: 13.5px; line-height: 1.7; color: #7A4B29; margin: 0;">
                  चित्रकूट धाम आने वाले संतों, तपस्वियों, पदयात्रियों एवं दीन-दुखियों को दोनों समय शुद्ध सात्विक महाप्रसाद
                  वितरण।
                </p>
              </div>
            </div>
            <div
              style="padding: 14px 24px 20px; border-top: 1px dashed rgba(180, 83, 9, 0.2); display: flex; align-items: center; justify-content: space-between;">
              <a href="{{ url('/seva') }}"
                style="font-size: 12.5px; font-weight: 800; color: #B45309; text-decoration: none;">View Details &rarr;</a>
              <a href="{{ url('/donate?cause=religious-events-annakshetra') }}"
                style="font-size: 12px; font-weight: 700; color: #fff; background: #B45309; padding: 6px 14px; border-radius: 9999px; text-decoration: none;">Donate
                Now</a>
            </div>
          </div>
        @endif

      </div>

      <!-- Trust Visual Seva Gallery Strip (Trust Work in Action) -->
      <div
        style="background: #FAF4ED; border: 1.5px solid rgba(180, 83, 9, 0.22); border-radius: 20px; padding: 24px; margin-bottom: 50px;">
        <div
          style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px; flex-wrap: wrap; gap: 10px;">
          <div>
            <h4 style="font-family: 'Noto Serif', serif; font-size: 17px; font-weight: 700; color: #2B1A12; margin: 0;">
              चित्रकूट तपोभूमि में सेवा साधना की प्रत्यक्ष झलकियां
            </h4>
            <span style="font-size: 12px; color: #7A4B29;">गोशाला पोषण, वेद पाठ, अन्नक्षेत्र एवं तीर्थ स्वच्छता की
              वास्तविक छवियां</span>
          </div>
          <a href="{{ url('/gallery') }}"
            style="display: inline-flex; align-items: center; gap: 4px; font-size: 12px; font-weight: 700; color: #D95D0F; text-decoration: none;">
            <span>View Gallery</span> &rarr;
          </a>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px;">
          <div
            style="height: 140px; border-radius: 12px; overflow: hidden; position: relative; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <img src="https://images.unsplash.com/photo-1560493676-04071c5f467b?auto=format&fit=crop&w=800&q=80"
              alt="कामदगिरि गौशाला सेवा" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
            <span
              style="position: absolute; bottom: 8px; left: 10px; background: rgba(0,0,0,0.6); color: #fff; font-size: 10.5px; padding: 2px 8px; border-radius: 4px; font-weight: 600;">गौशाला
              सेवा</span>
          </div>
          <div
            style="height: 140px; border-radius: 12px; overflow: hidden; position: relative; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <img src="https://images.unsplash.com/photo-1542810634-71277d95dcbb?auto=format&fit=crop&w=800&q=80"
              alt="ऋषिकुल बटुक वेद पाठ" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
            <span
              style="position: absolute; bottom: 8px; left: 10px; background: rgba(0,0,0,0.6); color: #fff; font-size: 10.5px; padding: 2px 8px; border-radius: 4px; font-weight: 600;">गुरुकुल
              बटुक</span>
          </div>
          <div
            style="height: 140px; border-radius: 12px; overflow: hidden; position: relative; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <img src="https://images.unsplash.com/photo-1604608672516-f1b9e1e3b7aa?auto=format&fit=crop&w=800&q=80"
              alt="अन्नक्षेत्र महाप्रसाद" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
            <span
              style="position: absolute; bottom: 8px; left: 10px; background: rgba(0,0,0,0.6); color: #fff; font-size: 10.5px; padding: 2px 8px; border-radius: 4px; font-weight: 600;">नित्य
              अन्नक्षेत्र</span>
          </div>
          <div
            style="height: 140px; border-radius: 12px; overflow: hidden; position: relative; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <img src="{{ asset('images/ram-ghat-aarti.webp') }}" alt="रामघाट मंदाकिनी स्वच्छता" loading="lazy"
              style="width: 100%; height: 100%; object-fit: cover;">
            <span
              style="position: absolute; bottom: 8px; left: 10px; background: rgba(0,0,0,0.6); color: #fff; font-size: 10.5px; padding: 2px 8px; border-radius: 4px; font-weight: 600;">मंदाकिनी
              स्वच्छता</span>
          </div>
        </div>
      </div>

      <!-- Trust Stats Grid -->
      <div
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; padding: 24px; border-radius: 20px; background: #FAF4ED; border: 1.5px solid rgba(180, 83, 9, 0.2); margin-bottom: 50px;">
        <div style="text-align: center; padding: 12px;">
          <span
            style="font-family: 'Noto Serif', serif; font-size: 32px; font-weight: 800; color: #D95D0F; display: block;">{{ !empty($homeSettings['stat_years']) ? $homeSettings['stat_years'] : '२५+' }}
            वर्ष</span>
          <span
            style="font-size: 12px; font-weight: 700; color: #2B1A12;">{{ !empty($homeSettings['stat_years_title']) ? $homeSettings['stat_years_title'] : 'अखंड सेवा परंपरा' }}</span>
        </div>
        <div style="text-align: center; padding: 12px;">
          <span
            style="font-family: 'Noto Serif', serif; font-size: 32px; font-weight: 800; color: #2D6A4F; display: block;">{{ !empty($homeSettings['stat_trees']) ? $homeSettings['stat_trees'] : '१०,०००+' }}</span>
          <span
            style="font-size: 12px; font-weight: 700; color: #2B1A12;">{{ !empty($homeSettings['stat_trees_title']) ? $homeSettings['stat_trees_title'] : 'रोपित देववृक्ष' }}</span>
        </div>
        <div style="text-align: center; padding: 12px;">
          <span
            style="font-family: 'Noto Serif', serif; font-size: 32px; font-weight: 800; color: #B45309; display: block;">{{ !empty($homeSettings['stat_cows']) ? $homeSettings['stat_cows'] : '२५०+' }}</span>
          <span
            style="font-size: 12px; font-weight: 700; color: #2B1A12;">{{ !empty($homeSettings['stat_cows_title']) ? $homeSettings['stat_cows_title'] : 'संरक्षित गोवंश' }}</span>
        </div>
        <div style="text-align: center; padding: 12px;">
          <span
            style="font-family: 'Noto Serif', serif; font-size: 32px; font-weight: 800; color: #EA580C; display: block;">{{ !empty($homeSettings['stat_students']) ? $homeSettings['stat_students'] : '५००+' }}</span>
          <span
            style="font-size: 12px; font-weight: 700; color: #2B1A12;">{{ !empty($homeSettings['stat_students_title']) ? $homeSettings['stat_students_title'] : 'ऋषिकुल बटुक छात्र' }}</span>
        </div>
      </div>

      <!-- Bottom Navigation & CTA Buttons -->
      <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 16px;">
        <a href="{{ url('/donate') }}"
          style="display: inline-flex; align-items: center; gap: 8px; background: #D95D0F; color: #fff; font-weight: 700; font-size: 13px; padding: 12px 28px; border-radius: 9999px; text-decoration: none; box-shadow: 0 4px 14px rgba(217, 93, 15, 0.25);">
          <span>Donate Now</span> &rarr;
        </a>
        <a href="{{ url('/chitrakoot') }}"
          style="display: inline-flex; align-items: center; gap: 8px; background: #FFFDFC; color: #2B1A12; font-weight: 700; font-size: 13px; padding: 12px 24px; border-radius: 9999px; text-decoration: none; border: 1.5px solid rgba(180, 83, 9, 0.25);">
          <span>Explore Sacred Places &rarr;</span>
        </a>
        <a href="{{ url('/contact') }}"
          style="display: inline-flex; align-items: center; gap: 8px; background: #FAF4ED; color: #B45309; font-weight: 700; font-size: 13px; padding: 12px 24px; border-radius: 9999px; text-decoration: none; border: 1.5px solid rgba(180, 83, 9, 0.2);">
          <span>Contact Us &rarr;</span>
        </a>
      </div>

    </div>
  </section>

@endsection