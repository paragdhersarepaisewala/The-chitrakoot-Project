@extends('layouts.app')

@section('title', 'पूजा एवं हवन ऑनलाइन संकल्प आरक्षण | ' . ($orgSettings->org_name ?? 'श्री कामदगिरि पीठम् सेवा ट्रस्ट'))
@section('meta_description', 'चित्रकूट कामतानाथ जी के पावन सानिध्य में वैदिक पूजा, महायज्ञ एवं अनुष्ठान संकल्प अनुरोध करें। संस्थान द्वारा संपर्क कर दक्षिणा एवं व्यवस्था सुनिश्चित की जाएगी।')

@section('content')

  <style>
    .booking-portal-wrap {
      max-width: 960px;
      margin: 0 auto;
      padding: 0 16px;
    }

    .booking-stepper-card {
      background: #FFFDFC;
      border: 1.5px solid rgba(180, 83, 9, 0.18);
      border-radius: 20px;
      padding: 30px 34px;
      box-shadow: 0 8px 32px rgba(130, 70, 20, 0.05);
      margin-bottom: 26px;
      width: 100%;
      max-width: 100%;
      box-sizing: border-box;
      overflow: hidden;
    }

    .step-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-size: 11px;
      font-weight: 800;
      letter-spacing: .14em;
      text-transform: uppercase;
      color: #B45309;
      background: rgba(180, 83, 9, 0.08);
      padding: 5px 14px;
      border-radius: 9999px;
      margin-bottom: 10px;
    }

    .ritual-option-card {
      border: 1.5px solid rgba(180, 83, 9, 0.2);
      background: #ffffff;
      border-radius: 14px;
      padding: 16px 18px;
      cursor: pointer;
      transition: all .25s ease;
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .ritual-option-card:hover {
      transform: translateY(-2px);
      border-color: #D95D0F;
      box-shadow: 0 8px 24px rgba(217, 93, 15, 0.1);
    }

    .ritual-option-card.active-ritual {
      border-color: #D95D0F;
      background: #FFF9F3;
      box-shadow: 0 0 0 2px #D95D0F;
    }

    .slot-card {
      border: 1.5px solid rgba(180, 83, 9, 0.2);
      background: #ffffff;
      border-radius: 12px;
      padding: 14px 16px;
      cursor: pointer;
      transition: all .2s ease;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .slot-card:hover:not(.slot-disabled) {
      border-color: #086c60;
      background: #F2FAF8;
    }

    .slot-card.active-slot {
      border-color: #086c60;
      background: #E6F5F2;
      box-shadow: 0 0 0 2px #086c60;
    }

    .slot-card.slot-disabled {
      opacity: 0.45;
      background: #F5EFEB;
      border-color: #D6CBC2;
      cursor: not-allowed;
      filter: grayscale(40%);
    }

    .form-label-sacred {
      display: block;
      font: 700 11.5px var(--sans);
      letter-spacing: .12em;
      text-transform: uppercase;
      color: #B45309;
      margin-bottom: 8px;
    }

    .form-input-sacred {
      width: 100% !important;
      max-width: 100% !important;
      min-width: 0 !important;
      padding: 13px 16px;
      border: 1.5px solid rgba(180, 83, 9, 0.24);
      font: 500 14px var(--dev);
      border-radius: 12px;
      background: #FFFDFC;
      color: #2B1A12;
      box-sizing: border-box !important;
      transition: border-color .2s;
      -webkit-appearance: none;
      -moz-appearance: none;
      appearance: none;
    }

    .form-input-sacred:focus {
      outline: none;
      border-color: #D95D0F;
      background: #fff;
    }

    .form-input-sacred::-webkit-date-and-time-value {
      text-align: left;
    }

    @media (max-width: 768px) {
      .booking-portal-wrap {
        padding: 0 12px !important;
      }

      .booking-stepper-card {
        padding: 20px 14px !important;
        border-radius: 16px !important;
        margin-bottom: 18px !important;
      }

      .rituals-grid {
        grid-template-columns: 1fr !important;
      }

      .slots-grid {
        grid-template-columns: 1fr !important;
      }

      .slot-card {
        padding: 12px 14px !important;
        gap: 10px !important;
      }

      .details-row {
        grid-template-columns: 1fr !important;
      }
    }
  </style>

  <!-- Hero Section -->
  <section class="section"
    style="padding-top: 130px; padding-bottom: 45px; background: linear-gradient(180deg, #FAF4ED 0%, #FDFBF7 100%); color: #2B1A12; text-align: center; border-bottom: 1px solid rgba(180,83,9,0.12);">
    <div class="booking-portal-wrap">
      <h1 style="font: 700 clamp(28px, 4vw, 46px)/1.2 var(--dev); color: #2B1A12; margin: 0 0 12px;">
        पूजा एवं हवन ऑनलाइन संकल्प आरक्षण
      </h1>
      <p style="font: 400 15.5px/1.8 var(--dev); color: #7A4B29; max-width: 720px; margin: 0 auto;">
        चित्रकूट धाम के पवित्र तीर्थ पर भगवान कामतानाथ जी के सानिध्य में वैदिक आचार्यों द्वारा अनुष्ठान, महायज्ञ व महाआरती
        संकल्प कराएं। अपना संकल्प अनुरोध प्रेषित करें, न्यास के आचार्य आपसे फोन पर संपर्क कर दक्षिणा व संपूर्ण व्यवस्था
        सुनिश्चित करेंगे।
      </p>

      <!-- 2-Day Advance Booking Notice Banner -->
      <div
        style="background: rgba(180,83,9,0.08); border: 1px solid rgba(180,83,9,0.25); border-radius: 12px; padding: 12px 20px; max-width: 700px; margin: 22px auto 0; font-size: 13px; color: #7A4B29; display: flex; align-items: center; justify-content: center; gap: 8px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#D95D0F" stroke-width="2.5">
          <circle cx="12" cy="12" r="10" />
          <polyline points="12 6 12 12 16 14" />
        </svg>
        <span><strong>२ दिन पूर्व अग्रिम आरक्षण नियम:</strong> वैदिक व्यवस्था एवं सामग्री प्रबंध हेतु सभी पूजा व हवन स्लॉट
          कम से कम २ दिन पूर्व ही आरक्षित किए जा सकते हैं (आज और कल की तिथियां स्वतः अनुपलब्ध हैं)।</span>
      </div>
    </div>
  </section>

  <!-- Form Body Section -->
  <section class="section" style="background: var(--paper, #FAF4ED); padding: 45px 0 85px;">
    <div class="booking-portal-wrap">

      @if(session('success'))
        <div
          style="background: #ECFDF5; border: 1.5px solid #10B981; color: #065F46; padding: 18px 22px; border-radius: 14px; margin-bottom: 26px; font-weight: 600; display: flex; align-items: center; gap: 12px;">
          <span style="font-size: 20px;">✓</span>
          <div>{{ session('success') }}</div>
        </div>
      @endif

      @if($errors->any())
        <div
          style="background: #FEF2F2; border: 1.5px solid #EF4444; color: #991B1B; padding: 18px 22px; border-radius: 14px; margin-bottom: 26px;">
          <strong style="display: block; margin-bottom: 6px;">कृपया प्रपत्र में निम्नलिखित त्रुटियां सुधारें:</strong>
          <ul style="margin: 0; padding-left: 20px; font-size: 13.5px; line-height: 1.6;">
            @foreach($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form id="sacredBookingForm" action="{{ route('pooja.book') }}" method="POST">
        @csrf

        <input type="hidden" name="ritual_name" id="selected_ritual_name"
          value="{{ old('ritual_name', 'श्री कामतानाथ जी एकादश रुद्राभिषेक') }}">
        <input type="hidden" name="time_slot" id="selected_time_slot"
          value="{{ old('time_slot', 'प्रातः मंगला दर्शन स्लॉट (05:00 AM - 07:00 AM)') }}" required>

        <!-- STEP 1: Select Sacred Ritual -->
        <div class="booking-stepper-card">
          <div class="step-badge">चरण १ · STEP 1</div>
          <h2 style="font: 700 21px var(--dev); color: #2B1A12; margin: 0 0 6px;">
            वैदिक पूजा अथवा हवन व महायज्ञ अनुष्ठान का चयन करें (Select Sacred Ritual & Hawan)
          </h2>
          <p style="font-size: 13.5px; color: #7A4B29; margin: 0 0 18px;">
            नीचे दिए गए मुख्य अनुष्ठानों में से चयन करें अथवा अपना विशेष मनोरथ दर्ज करें:
          </p>

          @php
            $ritualsList = [
              [
                'name' => 'श्री कामतानाथ जी एकादश रुद्राभिषेक',
                'svg' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="M6 4c0 4 2.5 7 6 7s6-3 6-7"/><path d="M6 4h12"/><circle cx="12" cy="11" r="2"/></svg>',
                'color' => '#D95D0F',
                'bg' => 'rgba(217,93,15,0.1)',
                'desc' => 'भगवान शिव एवं कामतानाथ जी का ११ ब्राह्मणों द्वारा मंदाकिनी जल, दूध व पंचामृत से महारुद्राभिषेक'
              ],
              [
                'name' => 'श्री नवग्रह शांति महायज्ञ एवं वैदिक हवन',
                'svg' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/><path d="M4 21h16"/><path d="M6 18h12"/></svg>',
                'color' => '#EA580C',
                'bg' => 'rgba(234,88,12,0.1)',
                'desc' => 'समस्त ग्रह दोषों की शांति, परिवार में सुख-समृद्धि एवं कार्य सिद्धि हेतु वैदिक समिधा महायज्ञ'
              ],
              [
                'name' => 'अखंड श्री हनुमान चालीसा व सुंदरकांड पाठ',
                'svg' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 2v20"/><path d="M4 4h14l-4 5 4 5H4"/></svg>',
                'color' => '#B45309',
                'bg' => 'rgba(180,83,9,0.1)',
                'desc' => 'संकटमोचन श्री हनुमान जी का सिंदूर चोला अर्पण, १०८ सुंदरकांड पाठ व अखंड संकीर्तन'
              ],
              [
                'name' => 'दिव्य मंदाकिनी 108 दीपदान एवं महाआरती',
                'svg' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3c.5 1.5 2 2.5 2 4a2 2 0 0 1-4 0c0-1.5 1.5-2.5 2-4z"/><path d="M4 14c1 4 4 6 8 6s7-2 8-6H4z"/><line x1="2" y1="20" x2="22" y2="20"/></svg>',
                'color' => '#D97706',
                'bg' => 'rgba(217,119,6,0.1)',
                'desc' => 'रामघाट पर पावन मंदाकिनी नदी में १०८ दीप प्रज्वलन, गंगा आरती व पितृ शांति प्रार्थना'
              ],
              [
                'name' => 'कामधेनु गौमाता नित्य सेवा व विशेष गौ-पूजन',
                'svg' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/><circle cx="12" cy="11" r="3"/><path d="M7 6c-1-2-3-2-4-2"/><path d="M17 6c1-2 3-2 4-2"/></svg>',
                'color' => '#166534',
                'bg' => 'rgba(22,101,52,0.1)',
                'desc' => 'कामदगिरि सुरभि गौशाला में देशी गौमाताओं का तिलक, गोग्रास अर्पण एवं गौ-संरक्षण संकल्प'
              ],
              [
                'name' => 'श्री सत्यनारायण व्रत कथा एवं हवन',
                'svg' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/><line x1="9" y1="7" x2="16" y2="7"/><line x1="9" y1="11" x2="14" y2="11"/></svg>',
                'color' => '#854D0E',
                'bg' => 'rgba(133,77,14,0.1)',
                'desc' => 'भगवान नारायण के पावन प्राकट्य की पंचअध्यायी कथा, चरणामृत वितरण एवं पूर्णाहुति हवन'
              ],
              [
                'name' => 'महामृत्युंजय जाप एवं विशेष शांति अनुष्ठान',
                'svg' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="8"/><circle cx="12" cy="4" r="1.5"/><circle cx="17.6" cy="6.4" r="1.5"/><circle cx="20" cy="12" r="1.5"/><circle cx="17.6" cy="17.6" r="1.5"/><circle cx="12" cy="20" r="1.5"/><circle cx="6.4" cy="17.6" r="1.5"/><circle cx="4" cy="12" r="1.5"/><circle cx="6.4" cy="6.4" r="1.5"/><path d="M12 20v3"/></svg>',
                'color' => '#9333EA',
                'bg' => 'rgba(147,51,234,0.1)',
                'desc' => 'दीर्घायु, आरोग्य एवं गंभीर व्याधि निवारण हेतु सवा लाख महामृत्युंजय मंत्र जाप अनुष्ठान'
              ],
            ];
            $currentRitual = old('ritual_name', 'श्री कामतानाथ जी एकादश रुद्राभिषेक');
          @endphp

          <div class="rituals-grid"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 14px; margin-bottom: 16px;">
            @foreach($ritualsList as $r)
              <div class="ritual-option-card {{ $currentRitual === $r['name'] ? 'active-ritual' : '' }}"
                onclick="selectRitual('{{ $r['name'] }}', this)">
                <div
                  style="width: 44px; height: 44px; border-radius: 12px; background: {{ $r['bg'] }}; color: {{ $r['color'] }}; display: grid; place-items: center; flex-shrink: 0; border: 1px solid {{ $r['color'] }}33;">
                  {!! $r['svg'] !!}
                </div>
                <div style="flex: 1;">
                  <strong
                    style="display: block; font: 700 14px var(--dev); color: #2B1A12; margin-bottom: 2px;">{{ $r['name'] }}</strong>
                  <span style="display: block; font-size: 11.5px; color: #7A4B29; line-height: 1.4;">{{ $r['desc'] }}</span>
                </div>
              </div>
            @endforeach
          </div>

          <!-- Custom Ritual Name Input -->
          <div
            style="background: #FAF7F2; border: 1px dashed rgba(180,83,9,0.3); border-radius: 12px; padding: 14px 18px;">
            <label class="form-label-sacred" style="margin-bottom: 6px;">अन्य कोई विशेष अनुष्ठान / Custom Ritual
              Name:</label>
            <input type="text" id="custom_ritual_input" oninput="setCustomRitual(this.value)"
              placeholder="यदि उपरोक्त सूची के अतिरिक्त कोई अन्य पूजा करानी हो, तो यहाँ लिखें..."
              class="form-input-sacred" style="font-size: 13px; padding: 10px 14px;">
          </div>
        </div>

        <!-- STEP 2: Date & Available Altar Slot -->
        <div class="booking-stepper-card">
          <div class="step-badge">चरण २ · STEP 2</div>
          <h2 style="font: 700 21px var(--dev); color: #2B1A12; margin: 0 0 6px;">
            तिथि एवं समय-सारणी स्लॉट चुनें (Date & Altar Time Slot)
          </h2>
          <p style="font-size: 13.5px; color: #7A4B29; margin: 0 0 20px;">
            कम से कम २ दिन पूर्व की आगामी तिथि चुनें (आज और कल की तिथियां स्वतः ब्लॉक हैं)। उपलब्ध मंदिर समय-स्लॉट:
          </p>

          <!-- Date Picker -->
          <div style="margin-bottom: 24px; width: 100%; max-width: 360px; box-sizing: border-box;">
            <label class="form-label-sacred">अनुष्ठान की पावन तिथि (Ritual Date) *</label>
            <input type="date" name="booking_date" id="booking_date_picker" value="{{ old('booking_date', $minDate) }}"
              min="{{ $minDate }}" required class="form-input-sacred" onchange="validateSelectedDate(this.value)"
              style="font-weight: 700; font-size: 16px; color: #2B1A12; width: 100%; max-width: 100%; box-sizing: border-box;">
          </div>

          <!-- Slots Grid -->
          <label class="form-label-sacred">उपलब्ध मंदिर समय-स्लॉट (Available Time Slot) *</label>

          @php
            $defaultSlots = [
              ['name' => 'प्रातः मंगला दर्शन स्लॉट', 'time' => '05:00 AM - 07:00 AM', 'desc' => 'मंगला आरती व सूर्योदय काल'],
              ['name' => 'मध्याह्न भोग एवं अर्चन स्लॉट', 'time' => '11:00 AM - 01:00 PM', 'desc' => 'राजभोग आरती एवं मध्याह्न यज्ञ'],
              ['name' => 'संध्या मंदाकिनी आरती स्लॉट', 'time' => '05:30 PM - 07:30 PM', 'desc' => 'संध्या आरती एवं दीपदान'],
              ['name' => 'विशेष रात्रि अनुष्ठान स्लॉट', 'time' => '08:30 PM - 10:30 PM', 'desc' => 'शयन आरती व विशेष शांति जाप'],
            ];
            $currentSlot = old('time_slot', 'प्रातः मंगला दर्शन स्लॉट (05:00 AM - 07:00 AM)');
          @endphp

          <div class="slots-grid"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 14px;">
            @foreach($defaultSlots as $s)
              @php $fullSlotStr = $s['name'] . ' (' . $s['time'] . ')'; @endphp
              <div class="slot-card {{ $currentSlot === $fullSlotStr ? 'active-slot' : '' }}"
                onclick="selectSlot('{{ $fullSlotStr }}', this)">
                <div>
                  <strong style="display: block; font: 700 13.5px var(--dev); color: #2B1A12;">{{ $s['name'] }}</strong>
                  <span style="font-size: 12px; color: #086c60; font-weight: 700;">{{ $s['time'] }}</span>
                  <span style="display: block; font-size: 11px; color: #7A4B29; margin-top: 2px;">{{ $s['desc'] }}</span>
                </div>
                <span class="slot-badge"
                  style="font-size: 10.5px; font-weight: 800; padding: 4px 10px; border-radius: 9999px; background: {{ $currentSlot === $fullSlotStr ? '#086c60' : '#FAF4ED' }}; color: {{ $currentSlot === $fullSlotStr ? '#fff' : '#B45309' }};">
                  {{ $currentSlot === $fullSlotStr ? '✓ चयनित' : 'चुनें' }}
                </span>
              </div>
            @endforeach
          </div>
        </div>

        <!-- STEP 3: Devotee & Sankalp Details -->
        <div class="booking-stepper-card">
          <div class="step-badge">चरण ३ · STEP 3</div>
          <h2 style="font: 700 21px var(--dev); color: #2B1A12; margin: 0 0 6px;">
            यजमान एवं संकल्प विवरण (Devotee & Sankalp Details)
          </h2>
          <p style="font-size: 13.5px; color: #7A4B29; margin: 0 0 20px;">
            वैदिक संकल्प में यजमान का नाम, गोत्र एवं फोन नंबर अनिवार्य रूप से उपयोग किया जाता है:
          </p>

          <!-- Devotee Name & Phone -->
          <div class="details-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
            <div>
              <label class="form-label-sacred">यजमान / मुख्य भक्त का नाम (Full Name) *</label>
              <input type="text" name="devotee_name" id="devotee_name"
                value="{{ old('devotee_name', auth()->user()?->name) }}" required placeholder="उदा: आलोक कुमार शर्मा"
                class="form-input-sacred">
            </div>
            <div>
              <label class="form-label-sacred">संपर्क मोबाइल नंबर (Mobile WhatsApp Number) *</label>
              <input type="tel" name="phone" id="devotee_phone"
                value="{{ old('phone', auth()->user()?->phone ?? auth()->user()?->profile?->phone ?? auth()->user()?->poojaBookings()->whereNotNull('phone')->latest()->value('phone')) }}"
                required placeholder="उदा: 9876543210" class="form-input-sacred">
            </div>
          </div>

          <!-- Email & Gotra -->
          <div class="details-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
            <div>
              <label class="form-label-sacred">ईमेल पता (Email Address for Quotation & Receipt) *</label>
              <input type="email" name="email" id="devotee_email" value="{{ old('email', auth()->user()?->email) }}"
                required placeholder="कोटेशन एवं रसीद प्राप्ति हेतु ईमेल" class="form-input-sacred">
            </div>
            <div>
              <label class="form-label-sacred">यजमान का गोत्र (Gotra - यदि ज्ञात हो)</label>
              <input type="text" name="gotra" id="devotee_gotra"
                value="{{ old('gotra', auth()->user()?->profile?->gotra ?? auth()->user()?->poojaBookings()->whereNotNull('gotra')->latest()->value('gotra')) }}"
                placeholder="उदा: भारद्वाज, कश्यप, वशिष्ठ (यदि ज्ञात हो)" class="form-input-sacred">
            </div>
          </div>

          <!-- Nakshatra & Purpose -->
          <div class="details-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
            <div>
              <label class="form-label-sacred">नक्षत्र अथवा जन्म राशि (Nakshatra / Rashi)</label>
              <input type="text" name="nakshatra" id="devotee_nakshatra"
                value="{{ old('nakshatra', auth()->user()?->profile?->nakshatra ?? auth()->user()?->poojaBookings()->whereNotNull('nakshatra')->latest()->value('nakshatra')) }}"
                placeholder="उदा: रोहिणी, पुष्य, मकर, कुम्भ..." class="form-input-sacred">
            </div>
            <div>
              <label class="form-label-sacred">संकल्प का पावन मनोरथ / उद्देश्य (Sankalp Purpose)</label>
              <input type="text" name="sankalp_purpose" id="sankalp_purpose" value="{{ old('sankalp_purpose') }}"
                placeholder="उदा: परिवार का सर्वकल्याण, स्वास्थ्य, कार्य सिद्धि..." class="form-input-sacred">
            </div>
          </div>

          <div>
            <label class="form-label-sacred">विशेष टिप्पणी अथवा व्यवस्था संदेश (Special Notes / Requests)</label>
            <textarea name="admin_notes" id="devotee_notes" rows="2"
              placeholder="यदि कोई विशेष आवश्यकता या व्यवस्था संबंधी आग्रह हो, तो यहाँ लिखें..."
              class="form-input-sacred">{{ old('admin_notes') }}</textarea>
          </div>
        </div>

        <!-- Trust Policy Alert & Submission CTA -->
        <div
          style="background: #FFFDFC; border: 1.5px solid rgba(180,83,9,0.24); border-radius: 20px; padding: 26px 30px; box-shadow: 0 6px 24px rgba(130,70,20,0.06);">
          <div style="display: flex; gap: 14px; align-items: flex-start; margin-bottom: 22px;">
            <div
              style="width: 40px; height: 40px; border-radius: 12px; background: rgba(217,93,15,0.12); color: #D95D0F; display: grid; place-items: center; flex-shrink: 0;">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="m16 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z" />
                <path d="m2 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z" />
                <path d="M7 21h10" />
                <path d="M12 3v18" />
                <path d="M3 7h2c2 0 5-1 7-2 2 1 5 2 7 2h2" />
              </svg>
            </div>
            <div style="font-size: 13px; color: #7A4B29; line-height: 1.7;">
              <strong>पारंपरिक दक्षिणा एवं व्यवस्था नियम:</strong> आपका संकल्प अनुरोध दर्ज होते ही न्यास के आचार्य /
              व्यवस्थापक आपके मोबाइल नंबर पर संपर्क करेंगे। वैदिक सामग्री, समिधा एवं आचार्यों की उपस्थिति सुनिश्चित कर
              परस्पर सहमति से दक्षिणा तय की जाएगी। इसके उपरांत आपके <strong>ईमेल एवं प्रोफाइल डैशबोर्ड पर आधिकारिक दक्षिणा
                कोटेशन व सुरक्षित रेज़रपे भुगतान लिंक</strong> प्रेषित किया जाएगा।
            </div>
          </div>

          <button type="submit" id="submitBookingBtn" class="btn primary"
            style="width: 100%; justify-content: center; padding: 16px; font-size: 15px; font-weight: 800; border-radius: 9999px; box-shadow: 0 6px 20px rgba(180,83,9,0.25); display: flex; align-items: center; justify-content: center; gap: 8px; white-space: nowrap;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
              stroke-linecap="round" stroke-linejoin="round">
              <path
                d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
            </svg>
            <span>Submit Ritual Request &rarr;</span>
          </button>
        </div>

      </form>
    </div>
  </section>

@endsection

@push('scripts')
  <script>
    function selectRitual(name, card) {
      document.querySelectorAll('.ritual-option-card').forEach(c => c.classList.remove('active-ritual'));
      card.classList.add('active-ritual');
      document.getElementById('selected_ritual_name').value = name;
      document.getElementById('custom_ritual_input').value = '';
    }

    function setCustomRitual(val) {
      if (val.trim().length > 0) {
        document.querySelectorAll('.ritual-option-card').forEach(c => c.classList.remove('active-ritual'));
        document.getElementById('selected_ritual_name').value = val.trim();
      }
    }

    function selectSlot(fullSlotStr, card) {
      document.querySelectorAll('.slot-card').forEach(c => {
        c.classList.remove('active-slot');
        const b = c.querySelector('.slot-badge');
        if (b) {
          b.innerText = 'Select';
          b.style.background = '#FAF4ED';
          b.style.color = '#B45309';
        }
      });

      card.classList.add('active-slot');
      const badge = card.querySelector('.slot-badge');
      if (badge) {
        badge.innerText = '✓ Selected';
        badge.style.background = '#086c60';
        badge.style.color = '#fff';
      }

      document.getElementById('selected_time_slot').value = fullSlotStr;
    }

    function validateSelectedDate(dateVal) {
      if (!dateVal) return;
      const minD = "{{ $minDate }}";
      if (dateVal < minD) {
        alert('पूजा एवं हवन का आरक्षण कम से कम 2 दिन पूर्व अनिवार्य है। आज और कल की तिथियां उपलब्ध नहीं हैं।');
        document.getElementById('booking_date_picker').value = minD;
      }
    }
  </script>
@endpush