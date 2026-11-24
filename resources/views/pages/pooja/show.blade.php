@extends('layouts.app')

@section('title', $pooja->title_hi . ' — ऑनलाइन संकल्प बुकिंग | ' . ($orgSettings->org_name ?? 'श्री कामदगिरि पीठम् सेवा ट्रस्ट'))
@section('meta_description', $pooja->summary_hi ?: 'चित्रकूट कामदगिरि धाम पर पावन संकल्प व पूजा बुकिंग।')

@section('content')

  <section class="section pooja-show-section">
    <div class="container" style="max-width: 1100px;">

      <div style="margin-bottom: 20px;">
        <a href="{{ route('pooja.index') }}"
          style="font-size: 12px; font-weight: 700; color: var(--sand); text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
          &larr; वापस सभी पूजा सूची पर जाएँ
        </a>
      </div>

      <div class="pooja-show-grid">

        <!-- LEFT / TOP: Pooja Details -->
        <div class="pooja-details-col">
          <div class="pooja-media-banner">
            <img src="{{ $pooja->featured_image ?: asset('uploads/temple-test-photo-1792032209.webp') }}"
              alt="{{ $pooja->title_hi }}" loading="lazy">
          </div>

          <div class="pooja-detail-card">
            <h1 style="font: 700 clamp(24px, 3.5vw, 38px)/1.2 var(--dev); color: var(--deep); margin: 0 0 16px;">
              {{ $pooja->title_hi }}
            </h1>

            <div class="pooja-badge-row">
              <span class="badge-duration">
                अवधि: {{ $pooja->duration_minutes }} मिनट
              </span>
              <span class="badge-timing">
                समय: {{ $pooja->timings_display }}
              </span>
            </div>

            <div style="font: 400 15.5px/1.85 var(--dev); color: rgba(43,35,29,0.85); margin-bottom: 24px;">
              <p style="font-size: 17px; font-weight: 600; color: var(--brown); margin-bottom: 16px;">
                {{ $pooja->summary_hi }}
              </p>
              {!! nl2br(e($pooja->description_hi)) !!}
            </div>

            @if(!empty($pooja->benefits_hi))
              <div
                style="background: #fbf9f5; border: 1px solid rgba(185,133,80,0.25); border-radius: 10px; padding: 20px;">
                <h3 style="font: 700 15.5px var(--dev); color: var(--deep); margin: 0 0 10px;">
                  अनुष्ठान का विशेष पुण्य फल एवं लाभ:
                </h3>
                <ul style="margin: 0; padding-left: 20px; font: 400 14px/1.8 var(--dev); color: #444;">
                  @foreach($pooja->benefits_hi as $b)
                    <li>{{ $b }}</li>
                  @endforeach
                </ul>
              </div>
            @endif
          </div>
        </div>

        <!-- RIGHT / BOTTOM: Devotee Booking Form Card -->
        <div class="pooja-booking-col">
          <div class="pooja-booking-card">
            <div style="border-bottom: 1px solid var(--line); padding-bottom: 14px; margin-bottom: 18px;">
              <span
                style="font-size: 11px; font-weight: 800; color: var(--crimson); letter-spacing: .12em; text-transform: uppercase; display: block; margin-bottom: 4px;">Sankalp
                Registration</span>
              <h2 style="font: 700 21px var(--dev); color: var(--deep); margin: 0;">संकल्प एवं बुकिंग फॉर्म</h2>
            </div>

            @if(session('error'))
              <div
                style="background: #f8d7da; color: #721c24; padding: 12px 16px; border-radius: 6px; font-size: 13px; margin-bottom: 16px;">
                {{ session('error') }}
              </div>
            @endif

            <form action="{{ route('pooja.book') }}" method="POST">
              @csrf
              <input type="hidden" name="pooja_service_id" value="{{ $pooja->id }}">

              <div style="text-align: center; margin-bottom: 16px;">
                <a href="{{ route('pooja.bookingForm', ['package' => $pooja->slug]) }}"
                  style="display: inline-block; background: #FAF4ED; border: 1px solid rgba(180,83,9,0.25); color: #B45309; font-size: 11.5px; font-weight: 700; padding: 6px 14px; border-radius: 9999px; text-decoration: none;">
                  Select Time Slot on Detailed Form &rarr;
                </a>
              </div>

              <div style="margin-bottom: 14px;">
                <label
                  style="display: block; font-size: 11px; font-weight: 800; color: #444; letter-spacing: .08em; text-transform: uppercase; margin-bottom: 6px;">
                  यजमान / भक्त का नाम *
                </label>
                <input type="text" name="devotee_name" value="{{ old('devotee_name', auth()->user()->name ?? '') }}"
                  required placeholder="पूरा नाम दर्ज करें" class="form-input"
                  style="padding: 11px 13px; font-size: 13.5px; border-radius: 6px; border: 1px solid #ccc; width: 100%; box-sizing: border-box;">
              </div>

              <div class="pooja-form-grid-2">
                <div>
                  <label
                    style="display: block; font-size: 11px; font-weight: 800; color: #444; letter-spacing: .08em; text-transform: uppercase; margin-bottom: 6px;">
                    मोबाइल नंबर *
                  </label>
                  <input type="tel" name="phone"
                    value="{{ old('phone', auth()->user()?->phone ?? auth()->user()?->profile?->phone) }}" required
                    minlength="10" maxlength="15" pattern="[0-9\+\-\s]{10,15}" placeholder="10-अंकीय मोबाइल"
                    class="form-input"
                    style="padding: 11px 13px; font-size: 13.5px; border-radius: 6px; border: 1px solid #ccc; width: 100%; box-sizing: border-box;">
                </div>
                <div>
                  <label
                    style="display: block; font-size: 11px; font-weight: 800; color: #444; letter-spacing: .08em; text-transform: uppercase; margin-bottom: 6px;">
                    ईमेल (पुष्टिकरण हेतु) *
                  </label>
                  <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required
                    placeholder="email@address.com" class="form-input"
                    style="padding: 11px 13px; font-size: 13.5px; border-radius: 6px; border: 1px solid #ccc; width: 100%; box-sizing: border-box;">
                </div>
              </div>

              <div class="pooja-form-grid-2">
                <div>
                  <label
                    style="display: block; font-size: 11px; font-weight: 800; color: #444; letter-spacing: .08em; text-transform: uppercase; margin-bottom: 6px;">
                    गोत्र (Gotra)
                  </label>
                  <input type="text" name="gotra" value="{{ old('gotra') }}" placeholder="उदा. कश्यप, भारद्वाज"
                    class="form-input"
                    style="padding: 11px 13px; font-size: 13.5px; border-radius: 6px; border: 1px solid #ccc; width: 100%; box-sizing: border-box;">
                </div>
                <div>
                  <label
                    style="display: block; font-size: 11px; font-weight: 800; color: #444; letter-spacing: .08em; text-transform: uppercase; margin-bottom: 6px;">
                    नक्षत्र / राशि
                  </label>
                  <input type="text" name="nakshatra" value="{{ old('nakshatra') }}" placeholder="उदा. रोहिणी, मेष"
                    class="form-input"
                    style="padding: 11px 13px; font-size: 13.5px; border-radius: 6px; border: 1px solid #ccc; width: 100%; box-sizing: border-box;">
                </div>
              </div>

              <div style="margin-bottom: 14px;">
                <label
                  style="display: block; font-size: 11px; font-weight: 800; color: #444; letter-spacing: .08em; text-transform: uppercase; margin-bottom: 6px;">
                  संकल्प का उद्देश्य (Purpose)
                </label>
                <input type="text" name="sankalp_purpose" value="{{ old('sankalp_purpose') }}"
                  placeholder="उदा. परिवार कल्याण, उत्तम स्वास्थ्य, बाधा निवारण" class="form-input"
                  style="padding: 11px 13px; font-size: 13.5px; border-radius: 6px; border: 1px solid #ccc; width: 100%; box-sizing: border-box;">
              </div>

              <div class="pooja-form-grid-date">
                <div>
                  <label
                    style="display: block; font-size: 11px; font-weight: 800; color: #444; letter-spacing: .08em; text-transform: uppercase; margin-bottom: 6px;">
                    अनुष्ठान तिथि (24h पूर्व) *
                  </label>
                  <input type="date" name="booking_date"
                    value="{{ old('booking_date', date('Y-m-d', strtotime('+1 day'))) }}"
                    min="{{ date('Y-m-d', strtotime('+1 day')) }}" required class="form-input"
                    style="padding: 11px 13px; font-size: 13.5px; border-radius: 6px; border: 1px solid #ccc; width: 100%; box-sizing: border-box;">
                </div>
                <div>
                  <label
                    style="display: block; font-size: 11px; font-weight: 800; color: #444; letter-spacing: .08em; text-transform: uppercase; margin-bottom: 6px;">
                    समय स्लॉट *
                  </label>
                  <select name="time_slot" class="form-select"
                    style="padding: 11px 13px; font-size: 13px; border-radius: 6px; border: 1px solid #ccc; width: 100%; box-sizing: border-box;">
                    <option value="प्रातः कालीन मंगला आरती व महायज्ञ (06:30 - 08:00 AM)">प्रातः मंगला आरती व हवन (06:30 -
                      08:00 AM)</option>
                    <option value="प्रातः कालीन विशेष वैदिक पूजन व रुद्राभिषेक (08:30 - 10:30 AM)">वैदिक पूजन व
                      रुद्राभिषेक (08:30 - 10:30 AM)</option>
                    <option value="मध्याह्न भोग, परिक्रमा एवं कामदगिरि संकल्प (11:00 - 12:30 PM)">मध्याह्न भोग व परिक्रमा
                      (11:00 - 12:30 PM)</option>
                    <option value="अपराह्न नवग्रह शांति हवन व गायत्री महायज्ञ (15:30 - 17:30 PM)">अपराह्न नवग्रह शांति हवन
                      (03:30 - 05:30 PM)</option>
                    <option value="सायंकालीन मंदाकिनी दीपदान, महाआरती व पूर्णाहुति (18:30 - 20:00 PM)" selected>सायंकालीन
                      महाआरती व दीपदान (06:30 - 08:00 PM)</option>
                  </select>
                </div>
              </div>

              <input type="hidden" name="prasad_address" value="">

              <div
                style="background: #faf8f5; border: 1px solid rgba(185,133,80,0.3); border-radius: 8px; padding: 14px; margin-bottom: 18px; display: flex; align-items: center; justify-content: space-between;">
                <div>
                  <span style="font-size: 11px; color: #666; text-transform: uppercase; display: block;">प्रस्तावित सहयोग
                    राशि:</span>
                  <strong
                    style="font-size: 22px; color: var(--crimson); font-family: var(--sans);">₹{{ number_format($pooja->suggested_donation_amount, 0) }}</strong>
                </div>
                <input type="hidden" name="amount" value="{{ $pooja->suggested_donation_amount }}">
              </div>

              <button type="submit" class="btn-pooja-submit">
                Book Pooja Now &rarr;
              </button>
            </form>
          </div>
        </div>

      </div>

    </div>
  </section>

@endsection