@extends('layouts.app')

@section('title', 'समस्त मंदिर सेवाएं एवं सुविधा केंद्र | ' . ($orgSettings->org_name ?? 'श्री कामदगिरि पीठम् सेवा ट्रस्ट'))
@section('meta_description', 'चित्रकूट धाम में वैदिक पूजा, नित्य महाआरती, तीर्थ यात्री अतिथि विश्राम गृह, एवं धार्मिक आयोजनों हेतु मंदिर परिसर आरक्षण पूछताछ केंद्र।')

@section('content')

  <!-- Hero Header -->
  <section class="section"
    style="padding-top: 140px; padding-bottom: 70px; background: linear-gradient(180deg, #FAF4ED 0%, #FBF7F0 100%); color: #2B1A12; position: relative; overflow: hidden; border-bottom: 1px solid rgba(180, 83, 9, 0.15);">
    <div
      style="position: absolute; inset: 0; background-image: url('{{ !empty($homeSettings['pooja_banner_url']) ? $homeSettings['pooja_banner_url'] : asset('uploads/temple-test-photo-1792032209.webp') }}'); background-position: center; background-size: cover; opacity: 0.07; filter: blur(3px);">
    </div>
    <div class="container" style="position: relative; z-index: 2; text-align: center; max-width: 900px;">

      <!-- Breadcrumbs -->
      <nav aria-label="Breadcrumb"
        style="display: inline-flex; align-items: center; gap: 8px; font-size: 12px; color: #7A4B29; margin-bottom: 16px; background: rgba(255,255,255,0.7); padding: 4px 14px; border-radius: 9999px; border: 1px solid rgba(180,83,9,0.15);">
        <a href="{{ route('home') }}" style="color: inherit; text-decoration: none;">होम</a>
        <span>&rsaquo;</span>
        <span style="color: #D95D0F; font-weight: 700;">मंदिर सेवाएं</span>
      </nav>

      <h1 style="font: 700 clamp(30px, 4.2vw, 52px) var(--dev); color: #2B1A12; margin: 12px 0 16px; line-height: 1.25;">
        समस्त मंदिर सेवाएं एवं तीर्थ सुविधा केंद्र
      </h1>

      <p style="font: 400 17px/1.8 var(--dev); color: #7A4B29; margin: 0 auto 28px; max-width: 780px;">
        परम पावन चित्रकूट कामदगिरि धाम में वैदिक पूजा-अनुष्ठान, नित्य महाआरती दर्शन, तीर्थयात्री अतिथि विश्राम गृह एवं
        मांगलिक धार्मिक आयोजनों हेतु मंदिर परिसर आरक्षण की संपूर्ण व्यवस्था।
      </p>

      <!-- Quick Action Buttons -->
      <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
        <a href="{{ route('pooja.bookingForm') }}" class="btn primary"
          style="padding: 12px 26px; border-radius: 9999px; font-weight: 700; font-size: 13.5px; box-shadow: 0 4px 14px rgba(217,93,15,0.25);">
          Book Pooja &rarr;
        </a>
        <a href="{{ route('services.atithiVishram') }}" class="btn secondary"
          style="padding: 12px 24px; border-radius: 9999px; font-weight: 700; font-size: 13.5px; background: #FFFDFC; color: #92400E; border: 1.5px solid rgba(180,83,9,0.25);">
          Enquire Stay &rarr;
        </a>
        <a href="{{ route('services.templeBooking') }}" class="btn secondary"
          style="padding: 12px 24px; border-radius: 9999px; font-weight: 700; font-size: 13.5px; background: #FFFDFC; color: #92400E; border: 1.5px solid rgba(180,83,9,0.25);">
          Enquire Hall Booking &rarr;
        </a>
      </div>

    </div>
  </section>

  <!-- 4 Core Service Pillars Grid -->
  <section class="section" style="background: var(--paper, #FAF4ED); padding: 70px 0 50px;">
    <div class="container">

      <div style="text-align: center; max-width: 760px; margin: 0 auto 50px;">
        <h2 style="font: 700 clamp(24px, 3vw, 36px) var(--dev); color: #2B1A12; margin: 0 0 12px;">
          भक्तों के कल्याण व सुविधा हेतु समर्पित सेवा प्रकल्प
        </h2>
        <p style="font-size: 15px; line-height: 1.7; color: #7A4B29; margin: 0;">
          अपनी आस्था और आवश्यकतानुसार उपयुक्त सेवा का चयन करें अथवा ऑनलाइन पूछताछ दर्ज करें।
        </p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 26px;">

        <!-- Service Card 1: Pooja & Hawan -->
        <div class="reveal"
          style="background: #FFFDFC; border: 1.5px solid rgba(180, 83, 9, 0.2); border-radius: 20px; padding: 32px 26px; box-shadow: 0 8px 25px rgba(130, 70, 20, 0.05); display: flex; flex-direction: column; justify-content: space-between; transition: transform .3s ease, box-shadow .3s ease;">
          <div>
            <div
              style="width: 54px; height: 54px; border-radius: 14px; background: rgba(217,93,15,0.12); display: grid; place-items: center; color: #D95D0F; margin-bottom: 20px;">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 2c1.5 3 4 5 4 9a6 6 0 1 1-12 0c0-4 2.5-6 4-9 1 2 2 3 4 0z" />
              </svg>
            </div>
            <div
              style="display: inline-block; padding: 4px 10px; border-radius: 9999px; background: rgba(217,93,15,0.08); color: #C2410C; font-size: 11px; font-weight: 700; margin-bottom: 12px;">
              वैदिक अनुष्ठान</div>
            <h3 style="font: 700 22px var(--dev); color: #2B1A12; margin: 0 0 10px;">
              वैदिक पूजा, हवन एवं संकल्प
            </h3>
            <p style="font-size: 14px; line-height: 1.7; color: #7A4B29; margin-bottom: 20px;">
              कामदगिरि परिक्रमा संकल्प, एकादश रुद्राभिषेक, नवग्रह शांति एवं महामृत्युंजय जाप वैदिक विद्वानों द्वारा सविधि
              संपन्न कराए जाते हैं।
            </p>
            <ul style="margin: 0 0 24px; padding-left: 20px; font-size: 13px; line-height: 1.8; color: #593a28;">
              <li>नाम-गोत्र सहित सविधि ऑनलाइन संकल्प</li>
              <li>घर बैठे पावन प्रसाद एवं विभूति प्राप्ति</li>
              <li>वैदिक आचार्यों द्वारा शास्त्रोक्त विधि</li>
            </ul>
          </div>
          <div
            style="border-top: 1px solid rgba(180,83,9,0.14); padding-top: 18px; display: flex; align-items: center; justify-content: space-between; gap: 10px;">
            <a href="{{ route('pooja.bookingForm') }}" class="btn primary"
              style="font-size: 12px; padding: 9px 18px; border-radius: 9999px;">Book Slot &rarr;</a>
            <a href="{{ route('pooja.index') }}"
              style="font-size: 12px; font-weight: 700; color: #B45309; text-decoration: none;">View All Poojas &rarr;</a>
          </div>
        </div>

        <!-- Service Card 2: Nitya Aarti & Darshan -->
        <div class="reveal"
          style="background: #FFFDFC; border: 1.5px solid rgba(180, 83, 9, 0.2); border-radius: 20px; padding: 32px 26px; box-shadow: 0 8px 25px rgba(130, 70, 20, 0.05); display: flex; flex-direction: column; justify-content: space-between; transition: transform .3s ease, box-shadow .3s ease;">
          <div>
            <div
              style="width: 54px; height: 54px; border-radius: 14px; background: rgba(180,83,9,0.12); display: grid; place-items: center; color: #B45309; margin-bottom: 20px;">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10" />
                <polyline points="12 6 12 12 16 14" />
              </svg>
            </div>
            <div
              style="display: inline-block; padding: 4px 10px; border-radius: 9999px; background: rgba(180,83,9,0.08); color: #B45309; font-size: 11px; font-weight: 700; margin-bottom: 12px;">
              नित्य समय चक्र</div>
            <h3 style="font: 700 22px var(--dev); color: #2B1A12; margin: 0 0 10px;">
              नित्य आरती व दर्शन सारणी
            </h3>
            <p style="font-size: 14px; line-height: 1.7; color: #7A4B29; margin-bottom: 20px;">
              प्रातः मंगला आरती से लेकर शयन आरती तक, तथा रामघाट पर संध्या मंदाकिनी महादीपोत्सव आरती का संपूर्ण प्रामाणिक
              समय चक्र।
            </p>
            <ul style="margin: 0 0 24px; padding-left: 20px; font-size: 13px; line-height: 1.8; color: #593a28;">
              <li>प्रातः मंगला (05:00 AM) व शृंगार आरती</li>
              <li>रामघाट मंदाकिनी संध्या महाआरती (06:30 PM)</li>
              <li>अमावस्या एवं पर्व विशेष का अखंड समय चक्र</li>
            </ul>
          </div>
          <div
            style="border-top: 1px solid rgba(180,83,9,0.14); padding-top: 18px; display: flex; align-items: center; justify-content: space-between; gap: 10px;">
            <a href="{{ route('services.aarti') }}" class="btn primary"
              style="font-size: 12px; padding: 9px 18px; border-radius: 9999px;">View Timetable &rarr;</a>
            <a href="#aarti-preview"
              style="font-size: 12px; font-weight: 700; color: #B45309; text-decoration: none;">Quick Preview &darr;</a>
          </div>
        </div>

        <!-- Service Card 3: Atithi Vishram (Guest Stay) -->
        <div class="reveal"
          style="background: #FFFDFC; border: 1.5px solid rgba(180, 83, 9, 0.2); border-radius: 20px; padding: 32px 26px; box-shadow: 0 8px 25px rgba(130, 70, 20, 0.05); display: flex; flex-direction: column; justify-content: space-between; transition: transform .3s ease, box-shadow .3s ease;">
          <div>
            <div
              style="width: 54px; height: 54px; border-radius: 14px; background: rgba(45,106,79,0.12); display: grid; place-items: center; color: #2D6A4F; margin-bottom: 20px;">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 21h18" />
                <path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16" />
                <path d="M9 7h1" />
                <path d="M9 11h1" />
                <path d="M9 15h1" />
                <path d="M14 7h1" />
                <path d="M14 11h1" />
                <path d="M14 15h1" />
              </svg>
            </div>
            <div
              style="display: inline-block; padding: 4px 10px; border-radius: 9999px; background: rgba(45,106,79,0.08); color: #2D6A4F; font-size: 11px; font-weight: 700; margin-bottom: 12px;">
              आश्रम प्रवास सुविधा</div>
            <h3 style="font: 700 22px var(--dev); color: #2B1A12; margin: 0 0 10px;">
              अतिथि विश्राम गृह (प्रवास पूछताछ)
            </h3>
            <p style="font-size: 14px; line-height: 1.7; color: #7A4B29; margin-bottom: 20px;">
              चित्रकूट धाम में तीर्थ दर्शन, मौन साधना अथवा परिक्रमा हेतु पधारने वाले श्रद्धालुओं के लिए शांत, स्वच्छ एवं
              सात्विक अतिथि विश्राम व्यवस्था।
            </p>
            <ul style="margin: 0 0 24px; padding-left: 20px; font-size: 13px; line-height: 1.8; color: #593a28;">
              <li>साधारण एवं वातानुकूलित (AC) परिवार कक्ष</li>
              <li>सुरक्षित, पवित्र एवं आध्यात्मिक वातावरण</li>
              <li>सात्विक आश्रम भोजन एवं अन्नक्षेत्र सुविधा</li>
            </ul>
          </div>
          <div
            style="border-top: 1px solid rgba(180,83,9,0.14); padding-top: 18px; display: flex; align-items: center; justify-content: space-between; gap: 10px;">
            <a href="{{ route('services.atithiVishram') }}" class="btn primary"
              style="background: #2D6A4F; border-color: #2D6A4F; font-size: 12px; padding: 9px 18px; border-radius: 9999px;">Enquire
              Now &rarr;</a>
            <span style="font-size: 11.5px; color: #2D6A4F; font-weight: 700;">Advance Enquiry</span>
          </div>
        </div>

        <!-- Service Card 4: Temple Hall & Premises Booking -->
        <div class="reveal"
          style="background: #FFFDFC; border: 1.5px solid rgba(180, 83, 9, 0.2); border-radius: 20px; padding: 32px 26px; box-shadow: 0 8px 25px rgba(130, 70, 20, 0.05); display: flex; flex-direction: column; justify-content: space-between; transition: transform .3s ease, box-shadow .3s ease;">
          <div>
            <div
              style="width: 54px; height: 54px; border-radius: 14px; background: rgba(143,23,25,0.12); display: grid; place-items: center; color: #8F1719; margin-bottom: 20px;">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                <polyline points="2 17 12 22 22 17"></polyline>
                <polyline points="2 12 12 17 22 12"></polyline>
              </svg>
            </div>
            <div
              style="display: inline-block; padding: 4px 10px; border-radius: 9999px; background: rgba(143,23,25,0.08); color: #8F1719; font-size: 11px; font-weight: 700; margin-bottom: 12px;">
              आयोजन स्थल आरक्षण</div>
            <h3 style="font: 700 22px var(--dev); color: #2B1A12; margin: 0 0 10px;">
              मंदिर परिसर व मंडप आरक्षण
            </h3>
            <p style="font-size: 14px; line-height: 1.7; color: #7A4B29; margin-bottom: 20px;">
              विवाह संस्कार, महायज्ञ, श्रीराम कथा, श्रीमद्भागवत कथा एवं अन्नदान भंडारे हेतु मंदिर परिसर व यज्ञ मंडप आरक्षण
              की अग्रिम पूछताछ।
            </p>
            <ul style="margin: 0 0 24px; padding-left: 20px; font-size: 13px; line-height: 1.8; color: #593a28;">
              <li>विवाह, यज्ञ, कथा एवं विशाल भंडारा व्यवस्था</li>
              <li>वैदिक पुरोहित, ध्वनि एवं बैठक प्रबंध परामर्श</li>
              <li>मर्यादित एवं शास्त्रसम्मत पवित्र वातावरण</li>
            </ul>
          </div>
          <div
            style="border-top: 1px solid rgba(180,83,9,0.14); padding-top: 18px; display: flex; align-items: center; justify-content: space-between; gap: 10px;">
            <a href="{{ route('services.templeBooking') }}" class="btn primary"
              style="background: #8F1719; border-color: #8F1719; font-size: 12px; padding: 9px 18px; border-radius: 9999px;">Enquire
              Now &rarr;</a>
            <span style="font-size: 11.5px; color: #8F1719; font-weight: 700;">Enquiry Only</span>
          </div>
        </div>

      </div>

    </div>
  </section>

  <!-- Detailed Section 1: Sacred Pooja & Hawan Rituals Preview -->
  <section class="section" style="background: #FBF7F0; padding: 70px 0; border-top: 1px solid rgba(180,83,9,0.12);">
    <div class="container">

      <div
        style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px; flex-wrap: wrap; gap: 20px;">
        <div>
          <h2 style="font: 700 clamp(24px, 3vw, 36px) var(--dev); color: #2B1A12; margin: 0;">
            चित्रकूट धाम के प्रमुख वैदिक अनुष्ठान
          </h2>
        </div>
        <div style="display: flex; gap: 10px;">
          <a href="{{ route('pooja.bookingForm') }}" class="btn primary"
            style="padding: 10px 22px; font-size: 12.5px; border-radius: 9999px;">
            Book Slot &rarr;
          </a>
          <a href="{{ route('pooja.index') }}" class="btn secondary"
            style="padding: 10px 20px; font-size: 12.5px; border-radius: 9999px;">
            View All Poojas &rarr;
          </a>
        </div>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(310px, 1fr)); gap: 28px;">
        @foreach($poojas->take(3) as $pooja)
          <div class="reveal"
            style="background: #FFFDFC; border: 1px solid rgba(180, 83, 9, 0.18); border-radius: 18px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.03); display: flex; flex-direction: column;">
            <div style="position: relative; height: 200px; overflow: hidden;">
              <img src="{{ $pooja->featured_image ?: asset('uploads/temple-test-photo-1792032209.webp') }}"
                alt="{{ $pooja->title_hi }}" style="width: 100%; height: 100%; object-fit: cover;">
              <div
                style="position: absolute; top: 12px; right: 12px; background: rgba(255, 253, 252, 0.95); backdrop-filter: blur(8px); border: 1px solid rgba(180, 83, 9, 0.25); color: #B45309; padding: 4px 10px; border-radius: 9999px; font-size: 11px; font-weight: 700;">
                {{ $pooja->duration_minutes }} Min अनुष्ठान
              </div>
            </div>
            <div style="padding: 22px; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
              <div>
                <h3 style="font: 700 19px/1.3 var(--dev); color: #2B1A12; margin: 0 0 10px;">
                  <a href="{{ route('pooja.show', $pooja->slug) }}"
                    style="color: inherit; text-decoration: none;">{{ $pooja->title_hi }}</a>
                </h3>
                <p style="font-size: 13.5px; line-height: 1.6; color: #7A4B29; margin-bottom: 16px;">
                  {{ $pooja->summary_hi }}
                </p>
              </div>
              <div
                style="border-top: 1px solid rgba(180, 83, 9, 0.14); padding-top: 14px; display: flex; align-items: center; justify-content: space-between;">
                <div>
                  <span style="font-size: 10px; color: #7A4B29; text-transform: uppercase; display: block;">प्रस्तावित
                    दक्षिणा</span>
                  <strong
                    style="font-size: 18px; font-weight: 800; color: #D95D0F; font-family: var(--sans);">₹{{ number_format($pooja->suggested_donation_amount, 0) }}</strong>
                </div>
                <a href="{{ route('pooja.show', $pooja->slug) }}" class="btn primary"
                  style="padding: 8px 18px; font-size: 11.5px; border-radius: 9999px;">
                  Book Now &rarr;
                </a>
              </div>
            </div>
          </div>
        @endforeach
      </div>

    </div>
  </section>

  <!-- Detailed Section 2: Daily Aarti Schedule Preview -->
  <section class="section" id="aarti-preview"
    style="background: #FAF4ED; padding: 70px 0; border-top: 1px solid rgba(180,83,9,0.12);">
    <div class="container">

      <div style="text-align: center; max-width: 760px; margin: 0 auto 36px;">
        <h2 style="font: 700 clamp(24px, 3vw, 36px) var(--dev); color: #2B1A12; margin: 0 0 10px;">
          चित्रकूट धाम में नित्य महाआरती एवं दर्शन समय
        </h2>
        <p style="font-size: 15px; color: #7A4B29; margin: 0;">
          पावन कामदगिरि गर्भगृह एवं रामघाट मंदाकिनी तट पर होने वाली प्रमुख आरतियों का नित्य चक्र।
        </p>
      </div>

      <div
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 14px; margin-bottom: 30px;">
        @foreach($normalAartiSlots as $slot)
          @php
            $isLive = !empty($slot['is_live']);
            $slotDesc = $slot['desc'] ?? ($slot['description'] ?? 'कामदगिरि मुख्य गर्भगृह');
          @endphp
          <div
            style="padding: 18px 14px; border-radius: 16px; background: {{ $isLive ? 'linear-gradient(135deg, #FFFDFC, #FAF4ED)' : '#FFFDFC' }}; border: 1.5px solid {{ $isLive ? 'rgba(217, 93, 15, 0.45)' : 'rgba(180, 83, 9, 0.18)' }}; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; position: relative; box-shadow: {{ $isLive ? '0 4px 14px rgba(217, 93, 15, 0.14)' : '0 1px 4px rgba(0,0,0,0.03)' }};">
            @if($isLive)
              <div
                style="position: absolute; top: 10px; right: 10px; background: #D95D0F; color: #fff; font-size: 9px; font-weight: 800; padding: 1px 6px; border-radius: 9999px; text-transform: uppercase;">
                मुख्य आरती
              </div>
            @endif
            <span
              style="font-size: 12px; font-weight: 700; color: {{ $isLive ? '#D95D0F' : '#B45309' }}; margin-bottom: 6px;">
              {{ $slot['name'] }}
            </span>
            <span style="font-size: 20px; font-weight: 800; color: #2B1A12; margin-bottom: 6px; font-family: var(--sans);">
              {{ $slot['time'] }}
            </span>
            <span style="font-size: 11px; color: #7A4B29; font-weight: 500;">
              {{ $slotDesc }}
            </span>
          </div>
        @endforeach
      </div>

      <div style="text-align: center;">
        <a href="{{ route('services.aarti') }}" class="btn secondary"
          style="padding: 10px 24px; font-size: 13px; border-radius: 9999px;">
          विस्तृत अमावस्या एवं महापर्व समय सारणी देखें &rarr;
        </a>
      </div>

    </div>
  </section>

  <!-- Detailed Section 3: Dual CTA for Atithi Vishram & Temple Booking -->
  <section class="section" style="background: #FFFDFC; padding: 70px 0; border-top: 1px solid rgba(180,83,9,0.12);">
    <div class="container">

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 32px;">

        <!-- Box 1: Atithi Vishram Spotlight -->
        <div
          style="background: linear-gradient(135deg, #FAF7F2 0%, #F5EFEB 100%); border: 1.5px solid rgba(45,106,79,0.25); border-radius: 22px; padding: 36px 30px; position: relative; overflow: hidden;">
          <h3 style="font: 700 24px var(--dev); color: #1B4332; margin: 0 0 12px;">
            अतिथि विश्राम गृह में प्रवास पूछताछ
          </h3>
          <p style="font-size: 14.5px; line-height: 1.8; color: #40534C; margin-bottom: 22px;">
            चित्रकूट में परिक्रमा, दर्शन एवं साधना के लिए आने वाले भक्तजनों एवं परिवारों के लिए स्वच्छ, सुरक्षित एवं
            सात्विक वातावरण में ठहरने की व्यवस्था उपलब्ध है।
          </p>
          <div
            style="background: #ffffff; border: 1px solid rgba(45,106,79,0.18); border-radius: 12px; padding: 16px; margin-bottom: 24px; font-size: 13px; line-height: 1.7; color: #2D6A4F;">
            <strong>प्रमुख सुविधाएं:</strong> स्वच्छ स्नानागार, 24 घंटे जल आपूर्ति, आश्रम सुरक्षा, सात्विक भोजनालय,
            परिक्रमा पथ से सुगम निकटता।
          </div>
          <a href="{{ route('services.atithiVishram') }}" class="btn primary"
            style="background: #2D6A4F; border-color: #2D6A4F; padding: 12px 26px; border-radius: 9999px; font-size: 13px; font-weight: 700;">
            Enquire for Stay &rarr;
          </a>
        </div>

        <!-- Box 2: Temple Booking Spotlight -->
        <div
          style="background: linear-gradient(135deg, #FAF5F2 0%, #F8EBE7 100%); border: 1.5px solid rgba(143,23,25,0.25); border-radius: 22px; padding: 36px 30px; position: relative; overflow: hidden;">
          <h3 style="font: 700 24px var(--dev); color: #8F1719; margin: 0 0 12px;">
            धार्मिक आयोजनों हेतु मंदिर परिसर आरक्षण
          </h3>
          <p style="font-size: 14.5px; line-height: 1.8; color: #5C383A; margin-bottom: 22px;">
            विवाह संस्कार, वैदिक महायज्ञ, अखंड रामायण पाठ, श्रीमद्भागवत कथा एवं महाप्रसाद भंडारा हेतु मंदिर परिसर एवं यज्ञ
            मंडप आरक्षण की अग्रिम पूछताछ।
          </p>
          <div
            style="background: #ffffff; border: 1px solid rgba(143,23,25,0.18); border-radius: 12px; padding: 16px; margin-bottom: 24px; font-size: 13px; line-height: 1.7; color: #8F1719;">
            <strong>ध्यान दें:</strong> यह आरक्षण पूर्व-पूछताछ (Booking Enquiry) है। ट्रस्ट समिति शुभ तिथि, मर्यादा एवं
            उपलब्धता की समीक्षा कर आपसे संपर्क करेगी।
          </div>
          <a href="{{ route('services.templeBooking') }}" class="btn primary"
            style="background: #8F1719; border-color: #8F1719; padding: 12px 26px; border-radius: 9999px; font-size: 13px; font-weight: 700;">
            Enquire for Hall &rarr;
          </a>
        </div>

      </div>

    </div>
  </section>

  <!-- Help & Contact Assistance Banner -->
  <section class="section" style="background: #FAF4ED; padding: 50px 0; border-top: 1px solid rgba(180,83,9,0.15);">
    <div class="container" style="text-align: center; max-width: 720px;">
      <h3 style="font: 700 22px var(--dev); color: #2B1A12; margin: 0 0 10px;">
        किसी भी सेवा अथवा पूछताछ हेतु सहायता चाहिए?
      </h3>
      <p style="font-size: 14.5px; color: #7A4B29; margin: 0 0 20px;">
        हमारे मंदिर सेवा कार्यालय प्रतिनिधि से सीधे संपर्क करें।
      </p>
      <div style="display: flex; gap: 14px; justify-content: center; align-items: center; flex-wrap: wrap;">
        <a href="tel:{{ $orgSettings->phone ?? '+919415000000' }}" class="btn secondary"
          style="padding: 10px 22px; border-radius: 9999px; font-size: 13px; font-weight: 700;">
          📞 {{ $orgSettings->phone ?? '+91 94150 00000' }}
        </a>
        <a href="{{ route('contact') }}" class="btn primary"
          style="padding: 10px 22px; border-radius: 9999px; font-size: 13px; font-weight: 700;">
          Contact Us &rarr;
        </a>
      </div>
    </div>
  </section>

@endsection