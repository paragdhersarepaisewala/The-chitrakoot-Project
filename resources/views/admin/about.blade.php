@extends('layouts.admin')

@section('title', 'About Us Page Editor | CMS Admin')
@section('header_title', 'About Us Page CMS Editor')
@section('header_subtitle', 'Manage hero prelude, narrative history, environmental mission pillars, and impact stats')

@section('header_actions')
  <a href="{{ route('about') }}" target="_blank" class="action-btn" style="background: #ffffff; color: var(--deep); font-size: 11px;">
    Open Public About Page &nearr;
  </a>
@endsection

@section('content')

@if(session('success'))
  <div class="toast success" style="margin-bottom: 20px; position: static; animation: none;">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
    <span>{{ session('success') }}</span>
  </div>
@endif

@if($errors->any())
  <div class="toast error" style="margin-bottom: 20px; position: static; animation: none; background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5;">
    <ul style="margin: 0; padding-left: 20px;">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<form action="{{ route('admin.updateAbout') }}" method="POST" enctype="multipart/form-data">
  @csrf

  <!-- Section 1: Hero & Trust Narrative -->
  <div class="card" style="padding: 24px 28px; margin-bottom: 24px;">
    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--line); padding-bottom: 14px; margin-bottom: 20px;">
      <div>
        <h2 style="font-size: 16px; font-weight: 800; color: var(--deep); margin: 0;">1. Hero & Trust Narrative History</h2>
        <p style="font-size: 12px; color: #7A4B29; margin: 4px 0 0;">Top introductory section of the About Us page</p>
      </div>
      <span class="badge gold">Public /about</span>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 18px;">
      <div>
        <label class="form-label">Trust Subtitle / Tagline</label>
        <input type="text" name="trust_tagline" value="{{ $settings['trust_tagline'] ?? 'चित्रकूट धाम — तपोभूमि सेवा एवं संरक्षण' }}" class="form-input">
        <span style="font-size: 11px; color: #888;">Displayed below the trust name in the hero headline</span>
      </div>
      <div>
        <label class="form-label">Section Eyebrow Tag</label>
        <input type="text" name="trust_eyebrow" value="{{ $settings['trust_eyebrow'] ?? 'अनादि तपोभूमि की सेवा परंपरा' }}" class="form-input">
        <span style="font-size: 11px; color: #888;">Small badge above the main narrative block</span>
      </div>
    </div>

    <div class="form-group">
      <label class="form-label">Hero Lead Description</label>
      <textarea name="trust_lead" rows="2" class="form-input">{{ $settings['trust_lead'] ?? 'भगवान श्रीराम की पावन तपोस्थली चित्रकूट के दिव्य आध्यात्मिक गौरव, वैदिक संस्कृति और कामदगिरि पर्वत व पावन मंदाकिनी के पर्यावरणीय संरक्षण हेतु समर्पित एक निष्काम सेवाभावी न्यास।' }}</textarea>
    </div>

    <div class="form-group">
      <label class="form-label">Main Narrative Headline</label>
      <input type="text" name="trust_heading" value="{{ $settings['trust_heading'] ?? 'धर्म, संस्कृति और प्रकृति की त्रिवेणी' }}" class="form-input">
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 18px;">
      <div>
        <label class="form-label">Narrative Paragraph 1 (Sacred Heritage)</label>
        <textarea name="trust_desc" rows="4" class="form-input">{{ $settings['trust_desc'] ?? 'त्रेतायुग के वनवास काल में मर्यादा पुरुषोत्तम भगवान श्रीराम, माता जानकी एवं लक्ष्मण जी ने इसी पावन तपोवन में साढ़े ग्यारह वर्ष व्यतीत कर धर्म की प्रतिष्ठा की थी। महर्षि वाल्मीकि के अनुसार, कामदगिरि पर्वत साक्षात् भगवान श्री कामतानाथ जी का विग्रह स्वरूप है जिसकी पावन परिक्रमा से जीवन के समस्त कष्टों का निवारण होता है।' }}</textarea>
      </div>
      <div>
        <label class="form-label">Narrative Paragraph 2 (Trust Mission)</label>
        <textarea name="trust_p2" rows="4" class="form-input">{{ $settings['trust_p2'] ?? 'श्री कामदगिरि पीठम् सेवा ट्रस्ट इसी अनादि पावन परंपरा का संवाहक बनकर सनातन धर्म, वैदिक शिक्षा, असहाय गोवंश के पोषण, नित्य अन्नक्षेत्र एवं चित्रकूट की पवित्रता व पर्यावरण के संरक्षण में अहर्निश समर्पित है।' }}</textarea>
      </div>
    </div>

    <!-- Sacred Photo Upload / URL -->
    <div style="background: #faf8f5; border: 1px dashed var(--sand); border-radius: 12px; padding: 18px; margin-top: 10px;">
      <label class="form-label" style="color: var(--brown);">About Page Sacred Photo / Banner</label>
      <div style="display: grid; grid-template-columns: 120px 1fr; gap: 18px; align-items: center;">
        <div style="width: 120px; height: 80px; border-radius: 8px; overflow: hidden; background: #eee; border: 1px solid var(--line);">
          <img src="{{ !empty($settings['about_photo_1_url']) ? $settings['about_photo_1_url'] : ($orgSettings->hero_banner_path ?: asset('uploads/kamadgiri-temple-crest-emblem-1792267739.webp')) }}" alt="About Photo" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <div>
          <div style="display: flex; gap: 8px; margin-bottom: 8px;">
            <input type="text" name="about_photo_url" id="about_photo_url" value="{{ $settings['about_photo_1_url'] ?? '' }}" placeholder="Enter image URL or select from library" class="form-input">
            <button type="button" onclick="openUniversalMediaPicker({ targetInput: '#about_photo_url' })" class="action-btn teal" style="padding: 7px 12px; font-size: 11px; white-space: nowrap;"><x-icon name="image" size="12" /> Reuse</button>
          </div>
          <input type="file" name="about_photo_file" accept="image/*" class="form-input" style="padding: 6px;">
          <span style="font-size: 11px; color: #888;">Supports PNG, JPG, WebP, SVG (Max 10MB). Overrides current photo if uploaded.</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Section 2: Environmental Mission (पर्यावरण बचाओ अभियान - 4 Pillars) -->
  <div class="card" style="padding: 24px 28px; margin-bottom: 24px;">
    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--line); padding-bottom: 14px; margin-bottom: 20px;">
      <div>
        <h2 style="font-size: 16px; font-weight: 800; color: var(--deep); margin: 0;">2. Environmental Campaign & 4 Mission Pillars</h2>
        <p style="font-size: 12px; color: #7A4B29; margin: 4px 0 0;">Green and ecological preservation mission for Chitrakoot</p>
      </div>
      <span class="badge" style="background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0;">Ecology</span>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 18px;">
      <div>
        <label class="form-label">Mission Eyebrow Badge</label>
        <input type="text" name="about_mission_eyebrow" value="{{ $settings['about_mission_eyebrow'] ?? '॥ ट्रस्ट का सर्वोच्च विज़न ॥ • Environmental Preservation Mission' }}" class="form-input">
      </div>
      <div>
        <label class="form-label">Mission Heading</label>
        <input type="text" name="about_mission_heading" value="{{ $settings['about_mission_heading'] ?? 'पर्यावरण बचाओ अभियान: स्वच्छ, हरित एवं संरक्षित चित्रकूट' }}" class="form-input">
      </div>
    </div>

    <div class="form-group">
      <label class="form-label">Mission Lead Quote / Statement</label>
      <textarea name="about_mission_quote" rows="2" class="form-input">{{ $settings['about_mission_quote'] ?? '"प्रकृति संरक्षण ही सच्ची राम-सेवा है।" — चित्रकूट केवल एक तीर्थ नहीं, अपितु भगवान श्रीराम का सजल तपोवन है। ट्रस्ट का परम ध्येय और संकल्प है कि चित्रकूट के प्राचीन वनों, अमृतमयी मंदाकिनी और पावन कामदगिरि पर्वत की पवित्रता व जैव-विविधता को आधुनिक प्रदूषण से सुरक्षित रखा जाए।' }}</textarea>
    </div>

    <!-- 4 Pillars Grid -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
      
      <!-- Pillar 1 -->
      <div style="background: #fafaf9; border: 1px solid var(--line); border-radius: 12px; padding: 18px;">
        <span class="badge" style="background: #e0f2fe; color: #0369a1; margin-bottom: 10px;">Pillar 1: Mandakini Cleanliness</span>
        <div class="form-group">
          <label class="form-label">Pillar Title</label>
          <input type="text" name="mission_1_title" value="{{ $settings['mission_1_title'] ?? 'मंदाकिनी नदी स्वच्छता व अविरलता' }}" class="form-input">
        </div>
        <div class="form-group">
          <label class="form-label">Pillar Feature Image URL</label>
          <div style="display: flex; gap: 8px;">
            <input type="text" name="mission_1_image_url" id="mission_1_image_url" value="{{ $settings['mission_1_image_url'] ?? asset('images/ram-ghat-aarti.webp') }}" class="form-input" placeholder="https://...">
            <button type="button" onclick="openUniversalMediaPicker({ targetInput: '#mission_1_image_url' })" class="action-btn teal" style="padding: 7px 12px; font-size: 11px; white-space: nowrap;"><x-icon name="image" size="12" /> Reuse</button>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Or Upload Pillar 1 Photo</label>
          <input type="file" name="mission_1_image_file" accept="image/*" class="form-input" style="padding: 6px;">
        </div>
        <div class="form-group">
          <label class="form-label">Pillar Description</label>
          <textarea name="mission_1_desc" rows="3" class="form-input">{{ $settings['mission_1_desc'] ?? 'रामघाट, राघव प्रयाग एवं स्फटिक शिला पर दैनिक स्वच्छता अभियान। केवल मिट्टी के दीयों एवं प्राकृतिक पुष्पों के अर्पण की प्रेरणा, तथा नदी में प्लास्टिक व अजैविक अपशिष्ट विसर्जन पर पूर्ण रोक।' }}</textarea>
        </div>
        <div class="form-group" style="margin-bottom: 0;">
          <label class="form-label">Tag / Highlight</label>
          <input type="text" name="mission_1_tag" value="{{ $settings['mission_1_tag'] ?? 'नित्य घाट स्वच्छता सेनानी दल' }}" class="form-input">
        </div>
      </div>

      <!-- Pillar 2 -->
      <div style="background: #fafaf9; border: 1px solid var(--line); border-radius: 12px; padding: 18px;">
        <span class="badge" style="background: #ffedd5; color: #9a3412; margin-bottom: 10px;">Pillar 2: Plastic-Free Parikrama</span>
        <div class="form-group">
          <label class="form-label">Pillar Title</label>
          <input type="text" name="mission_2_title" value="{{ $settings['mission_2_title'] ?? 'प्लास्टिक-मुक्त कामदगिरि परिक्रमा' }}" class="form-input">
        </div>
        <div class="form-group">
          <label class="form-label">Pillar Feature Image URL</label>
          <div style="display: flex; gap: 8px;">
            <input type="text" name="mission_2_image_url" id="mission_2_image_url" value="{{ $settings['mission_2_image_url'] ?? asset('images/kamadgiri-mountain.webp') }}" class="form-input" placeholder="https://...">
            <button type="button" onclick="openUniversalMediaPicker({ targetInput: '#mission_2_image_url' })" class="action-btn teal" style="padding: 7px 12px; font-size: 11px; white-space: nowrap;"><x-icon name="image" size="12" /> Reuse</button>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Or Upload Pillar 2 Photo</label>
          <input type="file" name="mission_2_image_file" accept="image/*" class="form-input" style="padding: 6px;">
        </div>
        <div class="form-group">
          <label class="form-label">Pillar Description</label>
          <textarea name="mission_2_desc" rows="3" class="form-input">{{ $settings['mission_2_desc'] ?? '५ किलोमीटर लंबे पावन परिक्रमा मार्ग को \'सिंगल-यूज प्लास्टिक मुक्त\' क्षेत्र बनाने का संकल्प। पर्यावरण-अनुकूल अपशिष्ट पात्र (Dustbins) स्थापना, कपड़े के थैलों का वितरण एवं प्लास्टिक विकल्प जागरूकता।' }}</textarea>
        </div>
        <div class="form-group" style="margin-bottom: 0;">
          <label class="form-label">Tag / Highlight</label>
          <input type="text" name="mission_2_tag" value="{{ $settings['mission_2_tag'] ?? 'शून्य प्लास्टिक परिक्रमा संकल्प' }}" class="form-input">
        </div>
      </div>

      <!-- Pillar 3 -->
      <div style="background: #fafaf9; border: 1px solid var(--line); border-radius: 12px; padding: 18px;">
        <span class="badge" style="background: #dcfce7; color: #166534; margin-bottom: 10px;">Pillar 3: Agro-Forestry & Trees</span>
        <div class="form-group">
          <label class="form-label">Pillar Title</label>
          <input type="text" name="mission_3_title" value="{{ $settings['mission_3_title'] ?? '१०,०००+ देववृक्ष रोपण व हरितिमा' }}" class="form-input">
        </div>
        <div class="form-group">
          <label class="form-label">Pillar Feature Image URL</label>
          <div style="display: flex; gap: 8px;">
            <input type="text" name="mission_3_image_url" id="mission_3_image_url" value="{{ $settings['mission_3_image_url'] ?? 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=800&q=80' }}" class="form-input" placeholder="https://...">
            <button type="button" onclick="openUniversalMediaPicker({ targetInput: '#mission_3_image_url' })" class="action-btn teal" style="padding: 7px 12px; font-size: 11px; white-space: nowrap;"><x-icon name="image" size="12" /> Reuse</button>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Or Upload Pillar 3 Photo</label>
          <input type="file" name="mission_3_image_file" accept="image/*" class="form-input" style="padding: 6px;">
        </div>
        <div class="form-group">
          <label class="form-label">Pillar Description</label>
          <textarea name="mission_3_desc" rows="3" class="form-input">{{ $settings['mission_3_desc'] ?? 'रामायण कालीन पावन वृक्षों—पीपल, बरगद (वट), नीम, आँवला, कदंब, बेलपत्र एवं तुलसी का सघन रोपण। पर्वत श्रृंखलाओं का हरित आवरण बढ़ाकर भू-क्षरण रोकना एवं प्राकृतिक वन्यजीव आवास संवर्धन।' }}</textarea>
        </div>
        <div class="form-group" style="margin-bottom: 0;">
          <label class="form-label">Tag / Highlight</label>
          <input type="text" name="mission_3_tag" value="{{ $settings['mission_3_tag'] ?? '१०,०००+ वृक्ष रोपित एवं संरक्षित' }}" class="form-input">
        </div>
      </div>

      <!-- Pillar 4 -->
      <div style="background: #fafaf9; border: 1px solid var(--line); border-radius: 12px; padding: 18px;">
        <span class="badge" style="background: #fef3c7; color: #92400e; margin-bottom: 10px;">Pillar 4: Organic Gaushala Ecology</span>
        <div class="form-group">
          <label class="form-label">Pillar Title</label>
          <input type="text" name="mission_4_title" value="{{ $settings['mission_4_title'] ?? 'गौमय जैविक खाद व शून्य रसायन' }}" class="form-input">
        </div>
        <div class="form-group">
          <label class="form-label">Pillar Feature Image URL</label>
          <div style="display: flex; gap: 8px;">
            <input type="text" name="mission_4_image_url" id="mission_4_image_url" value="{{ $settings['mission_4_image_url'] ?? 'https://images.unsplash.com/photo-1570042225831-d98fa7577f1e?auto=format&fit=crop&w=800&q=80' }}" class="form-input" placeholder="https://...">
            <button type="button" onclick="openUniversalMediaPicker({ targetInput: '#mission_4_image_url' })" class="action-btn teal" style="padding: 7px 12px; font-size: 11px; white-space: nowrap;"><x-icon name="image" size="12" /> Reuse</button>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Or Upload Pillar 4 Photo</label>
          <input type="file" name="mission_4_image_file" accept="image/*" class="form-input" style="padding: 6px;">
        </div>
        <div class="form-group">
          <label class="form-label">Pillar Description</label>
          <textarea name="mission_4_desc" rows="3" class="form-input">{{ $settings['mission_4_desc'] ?? 'कामदगिरि गौशाला के २५०+ गोवंश के गोमय व गोमूत्र से \'जीवामृत\' व उच्च गुणवत्ता युक्त जैविक खाद का निर्माण। चित्रकूट के उपवनों को रासायनिक खादों व कीटनाशकों से मुक्त रखने का प्राकृतिक तंत्र।' }}</textarea>
        </div>
        <div class="form-group" style="margin-bottom: 0;">
          <label class="form-label">Tag / Highlight</label>
          <input type="text" name="mission_4_tag" value="{{ $settings['mission_4_tag'] ?? '१००% प्राकृतिक चक्रीय पर्यावरण' }}" class="form-input">
        </div>
      </div>

    </div>
  </div>

  <!-- Section 3: Impact Statistics & Pilgrim Pledge Banner -->
  <div class="card" style="padding: 24px 28px; margin-bottom: 24px;">
    <div style="border-bottom: 1px solid var(--line); padding-bottom: 14px; margin-bottom: 20px;">
      <h2 style="font-size: 16px; font-weight: 800; color: var(--deep); margin: 0;">3. Impact Statistics & Pilgrim Pledge Banner</h2>
      <p style="font-size: 12px; color: #7A4B29; margin: 4px 0 0;">Manage statistics counters and pilgrim call-to-action banner</p>
    </div>

    <!-- 4 Stats -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;">
      <div style="background: #fafaf9; border: 1px solid var(--line); border-radius: 10px; padding: 14px;">
        <label class="form-label">Years of Seva</label>
        <input type="text" name="stat_years" value="{{ $settings['stat_years'] ?? '२५+' }}" class="form-input" style="font-weight: 700; margin-bottom: 6px;">
        <input type="text" name="stat_years_title" value="{{ $settings['stat_years_title'] ?? 'अखंड सेवा परंपरा' }}" class="form-input" style="font-size: 11px;">
      </div>
      <div style="background: #fafaf9; border: 1px solid var(--line); border-radius: 10px; padding: 14px;">
        <label class="form-label">Planted Trees</label>
        <input type="text" name="stat_trees" value="{{ $settings['stat_trees'] ?? '१०,०००+' }}" class="form-input" style="font-weight: 700; margin-bottom: 6px;">
        <input type="text" name="stat_trees_title" value="{{ $settings['stat_trees_title'] ?? 'रोपित देववृक्ष' }}" class="form-input" style="font-size: 11px;">
      </div>
      <div style="background: #fafaf9; border: 1px solid var(--line); border-radius: 10px; padding: 14px;">
        <label class="form-label">Protected Cows</label>
        <input type="text" name="stat_cows" value="{{ $settings['stat_cows'] ?? '२५०+' }}" class="form-input" style="font-weight: 700; margin-bottom: 6px;">
        <input type="text" name="stat_cows_title" value="{{ $settings['stat_cows_title'] ?? 'संरक्षित गोवंश' }}" class="form-input" style="font-size: 11px;">
      </div>
      <div style="background: #fafaf9; border: 1px solid var(--line); border-radius: 10px; padding: 14px;">
        <label class="form-label">Gurukul Students</label>
        <input type="text" name="stat_students" value="{{ $settings['stat_students'] ?? '५००+' }}" class="form-input" style="font-weight: 700; margin-bottom: 6px;">
        <input type="text" name="stat_students_title" value="{{ $settings['stat_students_title'] ?? 'ऋषिकुल बटुक छात्र' }}" class="form-input" style="font-size: 11px;">
      </div>
    </div>

    <!-- Pilgrim Pledge Banner -->
    <div style="background: #fafaf9; border: 1px solid var(--line); border-radius: 12px; padding: 18px;">
      <span class="badge" style="background: #d1fae5; color: #065f46; margin-bottom: 12px;">★ Pilgrim's Devotional Pledge Banner</span>
      <div class="form-group">
        <label class="form-label">Pledge Headline</label>
        <input type="text" name="pledge_title" value="{{ $settings['pledge_title'] ?? 'चित्रकूट आएं तो श्रद्धा लाएं, प्लास्टिक न फैलाएं — प्रकृति को स्वच्छ व संरक्षित रखें' }}" class="form-input">
      </div>
      <div class="form-group" style="margin-bottom: 0;">
        <label class="form-label">Pledge Statement Description</label>
        <textarea name="pledge_desc" rows="3" class="form-input">{{ $settings['pledge_desc'] ?? 'भगवान श्रीराम की इस तपोभूमि की पवित्रता हम सभी का पावन दायित्व है। मंदाकिनी नदी में केवल प्रार्थना अर्पित करें, कचरा नहीं। कामदगिरि परिक्रमा मार्ग को कूड़ा-मुक्त रखें। अपने प्रत्येक दर्शन को पर्यावरण संरक्षण का संकल्प बनाएं।' }}</textarea>
      </div>
    </div>
  </div>

  <!-- Sticky Save Bar -->
  <div style="display: flex; justify-content: flex-end; gap: 14px; position: sticky; bottom: 20px; z-index: 100; background: rgba(255,255,255,0.92); backdrop-filter: blur(8px); padding: 14px 24px; border-radius: 12px; border: 1px solid var(--line); box-shadow: 0 8px 30px rgba(0,0,0,0.12);">
    <a href="{{ route('about') }}" target="_blank" class="action-btn">Preview Public Page &nearr;</a>
    <button type="submit" class="action-btn primary" style="padding: 10px 28px; font-size: 12px;">Save About Page Content</button>
  </div>
</form>

@endsection
