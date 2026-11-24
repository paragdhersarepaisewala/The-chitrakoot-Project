@extends('layouts.app')

@section('title', 'Leadership & Trustees | ' . ($orgSettings->org_name ?? 'Shri Kamadgiri Peetham Sewa Trust'))
@section('meta_description', 'Meet the revered spiritual leadership, trustees, and advisory council guiding Shri Kamadgiri Peetham Sewa Trust, Chitrakoot Dham.')

@section('content')

  <section class="section" style="padding-top: 150px; background: var(--paper);">
    <div class="container">
      <div class="section-head reveal">
        <h1>मार्गदर्शक मण्डल एवं ट्रस्टी परिषद</h1>
        <p>परम पूज्य संतों के आशीर्वाद और समर्पित प्रबुद्ध जनों के मार्गदर्शन में संचालित सनातन सेवा यात्रा।</p>
      </div>

      @php
        $patron = $profiles->firstWhere('role_category', 'peethadhishwar') ?? $profiles->first();
        $otherProfiles = $profiles->where('id', '!=', $patron?->id);
      @endphp

      <!-- Spiritual Patron / Peethadhishwar -->
      @if($patron)
        <div
          style="background: #FFFDFC; border: 1px solid rgba(180,83,9,0.18); border-radius: 20px; padding: clamp(24px, 4vw, 44px); margin-bottom: 50px; box-shadow: 0 12px 35px rgba(130,70,20,0.05);"
          class="reveal">
          <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 40px; align-items: center;">
            <div
              style="position: relative; border-radius: 16px; overflow: hidden; box-shadow: 0 12px 30px rgba(0,0,0,0.1);">
              <img src="{{ $patron->photo_path ?: asset('assets/uploads/temple-lotus-logo.png') }}"
                alt="{{ $patron->name_hi }}" style="width: 100%; height: 320px; object-fit: cover; background: #FAF4ED;">
              <div
                style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(0deg, rgba(43,26,18,0.95) 0%, transparent 100%); padding: 14px; text-align: center;">
                <span
                  style="font-size: 11px; font-weight: 700; color: #FAF4ED; letter-spacing: .15em; text-transform: uppercase;">{{ $patron->title_hi ?: 'सर्वोच्च संरक्षक एवं पीठाधीश्वर' }}</span>
              </div>
            </div>
            <div>
              <span
                style="font-size: 11px; font-weight: 800; color: #D95D0F; letter-spacing: .15em; text-transform: uppercase;">Divine
                Spiritual Guidance</span>
              <h2 style="font: 700 clamp(24px, 3vw, 36px) var(--dev); color: #2B1A12; margin: 8px 0 14px;">
                {{ $patron->name_hi }}</h2>
              <p style="font: 400 15.5px/1.85 var(--dev); color: #7A4B29; margin-bottom: 18px;">
                {{ $patron->bio_hi ?: 'चित्रकूट की पावन मंदाकिनी और कामदगिरि की अखंड तपोनिष्ठा को आत्मसात कर महाराज श्री के दिव्य सान्निध्य में श्री कामदगिरि पीठम् सेवा ट्रस्ट द्वारा गौशाला, वैदिक गुरुकुल, नित्य अन्नक्षेत्र एवं धर्म-संस्कृति के पुनरुत्थान का पावन कार्य निरंतर गतिमान है।' }}
              </p>
              <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <span
                  style="background: rgba(180,83,9,0.1); color: #B45309; border: 1px solid rgba(180,83,9,0.22); padding: 5px 14px; border-radius: 9999px; font-size: 11.5px; font-weight: 700;">अध्यक्ष
                  — {{ $orgSettings->org_name ?? 'श्री कामदगिरि पीठम् सेवा ट्रस्ट' }}</span>
                <span
                  style="background: rgba(45,106,79,0.1); color: #2D6A4F; border: 1px solid rgba(45,106,79,0.22); padding: 5px 14px; border-radius: 9999px; font-size: 11.5px; font-weight: 700;">आध्यात्मिक
                  पथप्रदर्शक</span>
              </div>
            </div>
          </div>
        </div>
      @endif

      <!-- Board of Trustees Grid -->
      <div style="margin-top: 60px;">
        <div class="reveal" style="margin-bottom: 30px;">
          <h3 style="font: 700 24px var(--dev); color: #2B1A12; margin: 0 0 6px;">ट्रस्टी मण्डल एवं प्रबंध समिति</h3>
          <p style="font: 400 14.5px var(--dev); color: #7A4B29; margin: 0;">पारदर्शिता, सेवाभाव और वैधानिक निष्ठा के साथ
            ट्रस्ट के कार्यों का क्रियान्वयन।</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px;">
          @forelse($otherProfiles as $index => $profile)
            <div class="reveal delay-{{ $index % 3 }}"
              style="background: #FFFDFC; border: 1px solid rgba(180,83,9,0.18); border-radius: 20px; padding: 26px; box-shadow: 0 6px 20px rgba(130,70,20,0.04);">
              @if(!empty($profile->photo_path))
                <div
                  style="width: 60px; height: 60px; border-radius: 50%; overflow: hidden; margin-bottom: 16px; border: 2px solid rgba(180,83,9,0.3);">
                  <img src="{{ $profile->photo_path }}" alt="{{ $profile->name_hi }}"
                    style="width: 100%; height: 100%; object-fit: cover;">
                </div>
              @else
                <div
                  style="width: 50px; height: 50px; border-radius: 50%; background: rgba(180,83,9,0.12); display: grid; place-items: center; color: #B45309; margin-bottom: 16px;">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                  </svg>
                </div>
              @endif
              <h4 style="font: 700 18px var(--dev); color: #2B1A12; margin: 0 0 4px;">{{ $profile->name_hi }}</h4>
              <div
                style="font-size: 11.5px; color: #B45309; font-weight: 700; margin-bottom: 12px; text-transform: uppercase;">
                {{ $profile->title_hi }}</div>
              <p style="font: 400 13.5px/1.7 var(--dev); color: #7A4B29; margin: 0;">
                {{ $profile->bio_hi ?: 'ट्रस्ट के समस्त कानूनी, प्रशासनिक एवं सेवा प्रकल्पों के सुचारू निष्पादन के लिए समर्पित।' }}
              </p>
            </div>
          @empty
            <div class="reveal"
              style="background: #FFFDFC; border: 1px solid rgba(180,83,9,0.18); border-radius: 20px; padding: 26px; box-shadow: 0 6px 20px rgba(130,70,20,0.04);">
              <div
                style="width: 50px; height: 50px; border-radius: 50%; background: rgba(180,83,9,0.12); display: grid; place-items: center; color: #B45309; margin-bottom: 16px;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                  <circle cx="12" cy="7" r="4" />
                </svg>
              </div>
              <h4 style="font: 700 18px var(--dev); color: #2B1A12; margin: 0 0 4px;">मुख्य प्रबंध न्यासी</h4>
              <div
                style="font-size: 11.5px; color: #B45309; font-weight: 700; margin-bottom: 12px; text-transform: uppercase;">
                प्रबंधन एवं विधि संचालन</div>
              <p style="font: 400 13.5px/1.7 var(--dev); color: #7A4B29; margin: 0;">
                ट्रस्ट के समस्त कानूनी, प्रशासनिक एवं सेवा प्रकल्पों के सुचारू निष्पादन के लिए उत्तरदायी।
              </p>
            </div>

            <div class="reveal delay-1"
              style="background: #FFFDFC; border: 1px solid rgba(180,83,9,0.18); border-radius: 20px; padding: 26px; box-shadow: 0 6px 20px rgba(130,70,20,0.04);">
              <div
                style="width: 50px; height: 50px; border-radius: 50%; background: rgba(45,106,79,0.12); display: grid; place-items: center; color: #2D6A4F; margin-bottom: 16px;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                  <line x1="16" y1="2" x2="16" y2="6" />
                  <line x1="8" y1="2" x2="8" y2="6" />
                  <line x1="3" y1="10" x2="21" y2="10" />
                </svg>
              </div>
              <h4 style="font: 700 18px var(--dev); color: #2B1A12; margin: 0 0 4px;">गुरुकुल एवं सेवा प्रभारी</h4>
              <div
                style="font-size: 11.5px; color: #2D6A4F; font-weight: 700; margin-bottom: 12px; text-transform: uppercase;">
                शिक्षा एवं गौशाला प्रबंधन</div>
              <p style="font: 400 13.5px/1.7 var(--dev); color: #7A4B29; margin: 0;">
                वैदिक गुरुकुल के पठन-पाठन, बटुक दीक्षा, गौशाला गौसेवा एवं नित्य अन्नक्षेत्र का प्रत्यक्ष निरीक्षण।
              </p>
            </div>

            <div class="reveal delay-2"
              style="background: #FFFDFC; border: 1px solid rgba(180,83,9,0.18); border-radius: 20px; padding: 26px; box-shadow: 0 6px 20px rgba(130,70,20,0.04);">
              <div
                style="width: 50px; height: 50px; border-radius: 50%; background: rgba(217,93,15,0.12); display: grid; place-items: center; color: #D95D0F; margin-bottom: 16px;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                  <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                </svg>
              </div>
              <h4 style="font: 700 18px var(--dev); color: #2B1A12; margin: 0 0 4px;">वित्त एवं लेखा न्यासी</h4>
              <div
                style="font-size: 11.5px; color: #D95D0F; font-weight: 700; margin-bottom: 12px; text-transform: uppercase;">
                वित्त एवं ऑडिटिंग</div>
              <p style="font: 400 13.5px/1.7 var(--dev); color: #7A4B29; margin: 0;">
                दानदाताओं के अंशदान की 100% ऑडिटिंग,
                {{ ($orgSettings->is_80g_enabled ?? false) ? '80G टैक्स छूट रसीदें एवं' : 'आधिकारिक दान रसीदें एवं' }} बैंक
                खातों का पारदर्शी रखरखाव।
              </p>
            </div>
          @endforelse
        </div>
      </div>

    </div>

    </div>
  </section>

@endsection