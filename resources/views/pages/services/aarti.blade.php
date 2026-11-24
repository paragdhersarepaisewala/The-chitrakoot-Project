@extends('layouts.app')

@section('title', 'नित्य महाआरती एवं दर्शन समय सारणी | ' . ($orgSettings->org_name ?? 'श्री कामदगिरि पीठम् सेवा ट्रस्ट'))
@section('meta_description', 'चित्रकूट कामदगिरि मुख्य गर्भगृह एवं रामघाट मंदाकिनी तट पर होने वाली नित्य मंगला, शृंगार, संध्या महाआरती एवं अमावस्या विशेष समय सारणी।')

@section('content')

  <!-- Hero Header -->
  <section class="section"
    style="padding-top: 140px; padding-bottom: 70px; background: linear-gradient(180deg, #FAF4ED 0%, #FBF7F0 100%); color: #2B1A12; position: relative; overflow: hidden; border-bottom: 1px solid rgba(180, 83, 9, 0.15);">
    <div
      style="position: absolute; inset: 0; background-image: url('{{ !empty($homeSettings['pooja_banner_url']) ? $homeSettings['pooja_banner_url'] : asset('uploads/temple-test-photo-1792032209.webp') }}'); background-position: center; background-size: cover; opacity: 0.08; filter: blur(3px);">
    </div>
    <div class="container" style="position: relative; z-index: 2; text-align: center; max-width: 880px;">

      <!-- Breadcrumbs -->
      <nav aria-label="Breadcrumb"
        style="display: inline-flex; align-items: center; gap: 8px; font-size: 12px; color: #7A4B29; margin-bottom: 16px; background: rgba(255,255,255,0.7); padding: 4px 14px; border-radius: 9999px; border: 1px solid rgba(180,83,9,0.15);">
        <a href="{{ route('home') }}" style="color: inherit; text-decoration: none;">होम</a>
        <span>&rsaquo;</span>
        <a href="{{ route('services.index') }}" style="color: inherit; text-decoration: none;">मंदिर सेवाएं</a>
        <span>&rsaquo;</span>
        <span style="color: #D95D0F; font-weight: 700;">आरती एवं दर्शन सारणी</span>
      </nav>

      <h1 style="font: 700 clamp(28px, 4vw, 48px) var(--dev); color: #2B1A12; margin: 12px 0 16px; line-height: 1.25;">
        नित्य महाआरती एवं गर्भगृह दर्शन समय सारणी
      </h1>

      <p style="font: 400 17px/1.8 var(--dev); color: #7A4B29; margin: 0 auto 28px; max-width: 760px;">
        श्री कामदगिरि मुख्य गर्भगृह एवं पावन मंदाकिनी तट रामघाट पर संपन्न होने वाली दैनिक महाआरती, बालभोग, राजभोग एवं
        विशेष पर्व आरतियों का शास्त्रसम्मत विवरण।
      </p>

      <!-- Tabs Toggle Button -->
      <div
        style="display: inline-flex; gap: 8px; background: #FAF4ED; padding: 6px; border-radius: 9999px; border: 1.5px solid rgba(180, 83, 9, 0.25); box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
        <button type="button" id="page-tab-normal" onclick="switchPageAartiTab('normal')"
          style="padding: 9px 24px; border-radius: 9999px; background: #D95D0F; color: #fff; font-size: 13px; font-weight: 700; border: none; cursor: pointer; transition: all 0.2s ease;">
          Regular Days
        </button>
        <button type="button" id="page-tab-amavasya" onclick="switchPageAartiTab('amavasya')"
          style="padding: 9px 24px; border-radius: 9999px; background: #FFFDFC; color: #7A4B29; font-size: 13px; font-weight: 700; border: 1px solid rgba(180, 83, 9, 0.2); cursor: pointer; transition: all 0.2s ease;">
          Amavasya & Festivals
        </button>
      </div>

    </div>
  </section>

  <!-- Timetable Content Grid -->
  <section class="section" style="background: var(--paper, #FAF4ED); padding: 70px 0;">
    <div class="container" style="max-width: 1000px;">

      <!-- Normal Schedule Cards -->
      <div id="page-aarti-normal" style="display: block;">
        <div style="text-align: center; margin-bottom: 30px;">
          <h2 style="font: 700 24px var(--dev); color: #2B1A12; margin: 0 0 8px;">दैनिक नियमित आरती चक्र (Daily Aarti
            Schedule)</h2>
          <p style="font-size: 14px; color: #7A4B29; margin: 0;">प्रत्येक सामान्य दिवस पर निर्धारित समय अनुसार दर्शन एवं
            आरती</p>
        </div>

        <div
          style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 40px;">
          @foreach($normalAartiSlots as $slot)
            @php
              $isLive = !empty($slot['is_live']);
              $slotDesc = $slot['desc'] ?? ($slot['description'] ?? 'कामदगिरि मुख्य गर्भगृह');
            @endphp
            <div
              style="background: #FFFDFC; border: 1.5px solid {{ $isLive ? 'rgba(217, 93, 15, 0.45)' : 'rgba(180, 83, 9, 0.18)' }}; border-radius: 18px; padding: 24px 20px; box-shadow: {{ $isLive ? '0 6px 20px rgba(217, 93, 15, 0.12)' : '0 2px 10px rgba(0,0,0,0.03)' }}; position: relative;">
              @if($isLive)
                <span
                  style="position: absolute; top: 14px; right: 14px; background: #D95D0F; color: #fff; font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 9999px; text-transform: uppercase;">
                  प्रमुख आरती
                </span>
              @endif
              <div style="font-size: 13px; font-weight: 700; color: #B45309; margin-bottom: 6px;">
                {{ $slot['name'] }}
              </div>
              <div style="font-size: 26px; font-weight: 800; color: #2B1A12; font-family: var(--sans); margin-bottom: 8px;">
                {{ $slot['time'] }}
              </div>
              <p style="font-size: 13px; color: #7A4B29; margin: 0; line-height: 1.5;">
                {{ $slotDesc }}
              </p>
            </div>
          @endforeach
        </div>
      </div>

      <!-- Amavasya Schedule Cards -->
      <div id="page-aarti-amavasya" style="display: none;">
        <div style="text-align: center; margin-bottom: 30px;">
          <h2 style="font: 700 24px var(--dev); color: #8F1719; margin: 0 0 8px;">अमावस्या एवं महापर्व विशेष समय चक्र
            (Special Festive Schedule)</h2>
          <p style="font-size: 14px; color: #7A4B29; margin: 0;">अमावस्या मेला, रामनवमी, दीपावली एवं विशेष पर्वों पर 24
            घंटे दर्शन एवं अखंड आरती</p>
        </div>

        <div
          style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 40px;">
          @foreach($amavasyaSlots as $slot)
            @php
              $isLive = !empty($slot['is_live']);
              $slotDesc = $slot['desc'] ?? ($slot['description'] ?? 'कामदगिरि परिक्रमा पथ');
            @endphp
            <div
              style="background: #FFFDFC; border: 1.5px solid {{ $isLive ? 'rgba(143, 23, 25, 0.45)' : 'rgba(180, 83, 9, 0.18)' }}; border-radius: 18px; padding: 24px 20px; box-shadow: 0 4px 14px rgba(0,0,0,0.04); position: relative;">
              @if($isLive)
                <span
                  style="position: absolute; top: 14px; right: 14px; background: #8F1719; color: #fff; font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 9999px; text-transform: uppercase;">
                  विशेष दीपोत्सव
                </span>
              @endif
              <div style="font-size: 13px; font-weight: 700; color: #8F1719; margin-bottom: 6px;">
                {{ $slot['name'] }}
              </div>
              <div style="font-size: 24px; font-weight: 800; color: #2B1A12; font-family: var(--sans); margin-bottom: 8px;">
                {{ $slot['time'] }}
              </div>
              <p style="font-size: 13px; color: #7A4B29; margin: 0; line-height: 1.5;">
                {{ $slotDesc }}
              </p>
            </div>
          @endforeach
        </div>
      </div>

      <!-- Darshan Guidelines Box -->
      <div
        style="background: #FFFDFC; border: 1px solid rgba(180,83,9,0.2); border-radius: 20px; padding: 32px 28px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
        <h3 style="font: 700 20px var(--dev); color: #2B1A12; margin: 0 0 16px;">
          तीर्थयात्रियों एवं भक्तों हेतु दर्शन मर्यादा व निर्देश
        </h3>
        <div
          style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px; font-size: 13.5px; line-height: 1.8; color: #593a28;">
          <div>
            <strong>1. वेशभूषा व मर्यादा:</strong> मंदिर एवं गर्भगृह परिसर में भारतीय पारंपरिक एवं शालीन परिधान में ही
            प्रवेश करें।
          </div>
          <div>
            <strong>2. आरती सहभागिता:</strong> मंगला एवं संध्या आरती के समय कम से कम 15 मिनट पूर्व स्थान ग्रहण करें।
          </div>
          <div>
            <strong>3. छायाचित्रण (Photography):</strong> मुख्य गर्भगृह के भीतर मोबाइल फोन एवं कैमरा का उपयोग प्रतिबंधित
            है।
          </div>
          <div>
            <strong>4. प्रसाद एवं दक्षिणा:</strong> केवल मंदिर कार्यालय द्वारा अधिकृत काउंटरों पर ही रसीद प्राप्त कर
            दक्षिणा अर्पित करें।
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- Quick Nav to other Services -->
  <section class="section" style="background: #FAF4ED; padding: 40px 0; border-top: 1px solid rgba(180,83,9,0.12);">
    <div class="container"
      style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h4 style="font: 700 18px var(--dev); color: #2B1A12; margin: 0 0 4px;">क्या आप ऑनलाइन पूजा संकल्प लेना चाहते हैं?
        </h4>
        <p style="font-size: 13.5px; color: #7A4B29; margin: 0;">घर बैठे वैदिक आचार्यों द्वारा नाम-गोत्र से पूजा संपन्न
          कराएं।</p>
      </div>
      <div style="display: flex; gap: 10px;">
        <a href="{{ route('pooja.bookingForm') }}" class="btn primary"
          style="padding: 10px 22px; font-size: 12.5px; border-radius: 9999px;">
          Book Pooja &rarr;
        </a>
        <a href="{{ route('services.index') }}" class="btn secondary"
          style="padding: 10px 20px; font-size: 12.5px; border-radius: 9999px;">
          View All Services &rarr;
        </a>
      </div>
    </div>
  </section>

  <script>
    function switchPageAartiTab(tab) {
      const normalDiv = document.getElementById('page-aarti-normal');
      const amavasyaDiv = document.getElementById('page-aarti-amavasya');
      const normalBtn = document.getElementById('page-tab-normal');
      const amavasyaBtn = document.getElementById('page-tab-amavasya');

      if (tab === 'amavasya') {
        normalDiv.style.display = 'none';
        amavasyaDiv.style.display = 'block';
        amavasyaBtn.style.background = '#8F1719';
        amavasyaBtn.style.color = '#fff';
        amavasyaBtn.style.border = 'none';
        normalBtn.style.background = '#FFFDFC';
        normalBtn.style.color = '#7A4B29';
        normalBtn.style.border = '1px solid rgba(180, 83, 9, 0.2)';
      } else {
        amavasyaDiv.style.display = 'none';
        normalDiv.style.display = 'block';
        normalBtn.style.background = '#D95D0F';
        normalBtn.style.color = '#fff';
        normalBtn.style.border = 'none';
        amavasyaBtn.style.background = '#FFFDFC';
        amavasyaBtn.style.color = '#7A4B29';
        amavasyaBtn.style.border = '1px solid rgba(180, 83, 9, 0.2)';
      }
    }
  </script>

@endsection