@extends('layouts.app')

@section('title', 'Kamadgiri Stay & Dharamshala | Atithi Vishram Griha Chitrakoot | ' . ($orgSettings->org_name ?? 'श्री कामदगिरि पीठम् सेवा ट्रस्ट'))
@section('meta_description', 'Book clean & peaceful Kamadgiri stay at Atithi Vishram Griha in Chitrakoot. Pilgrim rooms near Kamadgiri Parvat & Temple with AC, Non-AC & satvik food.')

@section('content')

  <!-- Hero Header -->
  <section class="section"
    style="padding-top: 140px; padding-bottom: 60px; background: linear-gradient(180deg, #FAF4ED 0%, #FBF7F0 100%); color: #2B1A12; position: relative; overflow: hidden; border-bottom: 1px solid rgba(180, 83, 9, 0.15);">
    <div
      style="position: absolute; inset: 0; background-image: url('{{ asset('uploads/kamadgiri-temple-crest-emblem-1792267739.webp') }}'); background-position: center; background-size: cover; opacity: 0.06; filter: blur(3px);">
    </div>
    <div class="container" style="position: relative; z-index: 2; text-align: center; max-width: 880px;">

      <!-- Breadcrumbs -->
      <nav aria-label="Breadcrumb"
        style="display: inline-flex; align-items: center; gap: 8px; font-size: 12px; color: #7A4B29; margin-bottom: 16px; background: rgba(255,255,255,0.7); padding: 4px 14px; border-radius: 9999px; border: 1px solid rgba(180,83,9,0.15);">
        <a href="{{ route('home') }}" style="color: inherit; text-decoration: none;">होम</a>
        <span>&rsaquo;</span>
        <a href="{{ route('services.index') }}" style="color: inherit; text-decoration: none;">मंदिर सेवाएं</a>
        <span>&rsaquo;</span>
        <span style="color: #2D6A4F; font-weight: 700;">कामदगिरि अतिथि विश्राम गृह (Kamadgiri Stay)</span>
      </nav>

      <h1 style="font: 700 clamp(28px, 4vw, 48px) var(--dev); color: #2B1A12; margin: 12px 0 16px; line-height: 1.25;">
        Kamadgiri Stay & Dharamshala | अतिथि विश्राम गृह (प्रवास पूछताछ)
      </h1>

      <p style="font: 400 16.5px/1.8 var(--dev); color: #7A4B29; margin: 0 auto; max-width: 740px;">
        पवित्र कामदगिरि पर्वत परिक्रमा एवं कामतानाथ मंदिर दर्शन हेतु पधारने वाले श्रद्धालुओं के लिए शांतिपूर्ण, सात्विक एवं स्वच्छ
        वातावरण में श्रेष्ठ Kamadgiri Stay एवं धर्मशाला प्रवास व्यवस्था।
      </p>

    </div>
  </section>

  <!-- Main Form & Information Section -->
  <section class="section" style="background: var(--paper, #FAF4ED); padding: 60px 0 80px;">
    <div class="container" style="max-width: 1100px;">

      @if(session('success'))
        <div
          style="background: #edf7ee; border: 1.5px solid #81c784; border-radius: 16px; padding: 24px 28px; margin-bottom: 36px; box-shadow: 0 4px 20px rgba(46,125,50,0.08);">
          <div style="display: flex; align-items: flex-start; gap: 14px;">
            <div
              style="width: 36px; height: 36px; border-radius: 50%; background: #2e7d32; color: #fff; display: grid; place-items: center; flex-shrink: 0; font-weight: 800;">
              ✓</div>
            <div>
              <h3 style="font: 700 19px var(--dev); color: #1b5e20; margin: 0 0 6px;">आपकी पूछताछ सफलतापूर्वक दर्ज हो गई है!
              </h3>
              <p style="font-size: 14px; line-height: 1.7; color: #2e7d32; margin: 0 0 10px;">
                {{ session('success') }}
              </p>
              @if(session('enquiry_number'))
                <div style="font-size: 13px; font-weight: 700; color: #1b5e20;">
                  पूछताछ संदर्भ संख्या: <span
                    style="font-family: monospace; font-size: 15px; background: rgba(46,125,50,0.15); padding: 2px 8px; border-radius: 6px;">#{{ session('enquiry_number') }}</span>
                </div>
              @endif
            </div>
          </div>
        </div>
      @endif

      @if($errors->any())
        <div
          style="background: #fdf2f2; border: 1.5px solid #f87171; border-radius: 16px; padding: 20px 24px; margin-bottom: 30px;">
          <h4 style="color: #b91c1c; font-weight: 700; margin: 0 0 8px; font-size: 15px;">कृपया फॉर्म में निम्नलिखित
            त्रुटियां सुधारें:</h4>
          <ul style="margin: 0; padding-left: 20px; font-size: 13.5px; color: #dc2626; line-height: 1.6;">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 36px; align-items: start;">

        <!-- Left Column: The Enquiry Form -->
        <div
          style="background: #FFFDFC; border: 1.5px solid rgba(180,83,9,0.2); border-radius: 22px; padding: 36px 32px; box-shadow: 0 8px 30px rgba(0,0,0,0.03);">

          <div style="margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid rgba(180,83,9,0.14);">
            <h2 style="font: 700 22px var(--dev); color: #2B1A12; margin: 0 0 6px;">
              तीर्थ प्रवास पूछताछ प्रपत्र (Stay Enquiry Form)
            </h2>
            <p style="font-size: 13.5px; color: #7A4B29; margin: 0;">
              कृपया अपने आगमन व प्रवास का विवरण ध्यानपूर्वक भरें।
            </p>
          </div>

          <form action="{{ route('services.atithiVishram.submit') }}" method="POST">
            @csrf

            <!-- Devotee Name & Phone -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
              <div>
                <label style="display: block; font-size: 12.5px; font-weight: 700; color: #2B1A12; margin-bottom: 6px;">
                  भक्त / मुख्य यात्री का नाम <span style="color: #dc2626;">*</span>
                </label>
                <input type="text" name="devotee_name" value="{{ old('devotee_name', auth()->user()?->name) }}" required
                  placeholder="उदा. पं. रमेश कुमार शर्मा"
                  style="width: 100%; padding: 11px 14px; border: 1.5px solid rgba(180,83,9,0.25); border-radius: 10px; font-size: 14px; background: #FAF7F2; box-sizing: border-box;">
              </div>

              <div>
                <label style="display: block; font-size: 12.5px; font-weight: 700; color: #2B1A12; margin-bottom: 6px;">
                  मोबाइल / व्हाट्सएप नंबर <span style="color: #dc2626;">*</span>
                </label>
                <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="उदा. 98XXXXXXXX"
                  style="width: 100%; padding: 11px 14px; border: 1.5px solid rgba(180,83,9,0.25); border-radius: 10px; font-size: 14px; background: #FAF7F2; box-sizing: border-box;">
              </div>
            </div>

            <!-- Email & City/State -->
            <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 18px; margin-bottom: 18px;">
              <div>
                <label style="display: block; font-size: 12.5px; font-weight: 700; color: #2B1A12; margin-bottom: 6px;">
                  ईमेल पता (पावती हेतु) <span style="color: #dc2626;">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" required
                  placeholder="उदा. devotee@example.com"
                  style="width: 100%; padding: 11px 14px; border: 1.5px solid rgba(180,83,9,0.25); border-radius: 10px; font-size: 14px; background: #FAF7F2; box-sizing: border-box;">
              </div>

              <div>
                <label style="display: block; font-size: 12.5px; font-weight: 700; color: #2B1A12; margin-bottom: 6px;">
                  नगर एवं राज्य (City, State)
                </label>
                <input type="text" name="city" value="{{ old('city') }}" placeholder="उदा. वाराणसी, उ.प्र."
                  style="width: 100%; padding: 11px 14px; border: 1.5px solid rgba(180,83,9,0.25); border-radius: 10px; font-size: 14px; background: #FAF7F2; box-sizing: border-box;">
              </div>
            </div>

            <!-- Check-in & Check-out Dates -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
              <div>
                <label style="display: block; font-size: 12.5px; font-weight: 700; color: #2B1A12; margin-bottom: 6px;">
                  आगमन तिथि (Check-in Date) <span style="color: #dc2626;">*</span>
                </label>
                <input type="date" name="check_in_date" value="{{ old('check_in_date', date('Y-m-d')) }}"
                  min="{{ date('Y-m-d') }}" required
                  style="width: 100%; padding: 11px 14px; border: 1.5px solid rgba(180,83,9,0.25); border-radius: 10px; font-size: 14px; background: #FAF7F2; box-sizing: border-box;">
              </div>

              <div>
                <label style="display: block; font-size: 12.5px; font-weight: 700; color: #2B1A12; margin-bottom: 6px;">
                  प्रस्थान तिथि (Check-out Date) <span style="color: #dc2626;">*</span>
                </label>
                <input type="date" name="check_out_date"
                  value="{{ old('check_out_date', date('Y-m-d', strtotime('+1 day'))) }}" min="{{ date('Y-m-d') }}"
                  required
                  style="width: 100%; padding: 11px 14px; border: 1.5px solid rgba(180,83,9,0.25); border-radius: 10px; font-size: 14px; background: #FAF7F2; box-sizing: border-box;">
              </div>
            </div>

            <!-- Devotees count & Rooms count -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
              <div>
                <label style="display: block; font-size: 12.5px; font-weight: 700; color: #2B1A12; margin-bottom: 6px;">
                  श्रद्धालुओं की संख्या <span style="color: #dc2626;">*</span>
                </label>
                <input type="number" name="number_of_devotees" value="{{ old('number_of_devotees', 2) }}" min="1"
                  max="100" required
                  style="width: 100%; padding: 11px 14px; border: 1.5px solid rgba(180,83,9,0.25); border-radius: 10px; font-size: 14px; background: #FAF7F2; box-sizing: border-box;">
              </div>

              <div>
                <label style="display: block; font-size: 12.5px; font-weight: 700; color: #2B1A12; margin-bottom: 6px;">
                  आवश्यक कक्ष संख्या (Rooms) <span style="color: #dc2626;">*</span>
                </label>
                <input type="number" name="number_of_rooms" value="{{ old('number_of_rooms', 1) }}" min="1" max="20"
                  required
                  style="width: 100%; padding: 11px 14px; border: 1.5px solid rgba(180,83,9,0.25); border-radius: 10px; font-size: 14px; background: #FAF7F2; box-sizing: border-box;">
              </div>
            </div>

            <!-- Room Preference & Purpose of stay -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
              <div>
                <label style="display: block; font-size: 12.5px; font-weight: 700; color: #2B1A12; margin-bottom: 6px;">
                  कक्ष प्राथमिकता (Room Preference) <span style="color: #dc2626;">*</span>
                </label>
                <select name="room_preference" required
                  style="width: 100%; padding: 11px 14px; border: 1.5px solid rgba(180,83,9,0.25); border-radius: 10px; font-size: 14px; background: #FAF7F2; box-sizing: border-box;">
                  <option value="standard" {{ old('room_preference') == 'standard' ? 'selected' : '' }}>साधारण कक्ष
                    (Standard Non-AC Room)</option>
                  <option value="ac_room" {{ old('room_preference') == 'ac_room' ? 'selected' : '' }}>वातानुकूलित कक्ष (AC
                    Room)</option>
                  <option value="family_suite" {{ old('room_preference') == 'family_suite' ? 'selected' : '' }}>पारिवारिक
                    कक्ष (Family Suite)</option>
                  <option value="dormitory" {{ old('room_preference') == 'dormitory' ? 'selected' : '' }}>सामूहिक विश्राम
                    गृह (Dormitory)</option>
                </select>
              </div>

              <div>
                <label style="display: block; font-size: 12.5px; font-weight: 700; color: #2B1A12; margin-bottom: 6px;">
                  प्रवास उद्देश्य (Purpose of Stay) <span style="color: #dc2626;">*</span>
                </label>
                <select name="purpose_of_stay" required
                  style="width: 100%; padding: 11px 14px; border: 1.5px solid rgba(180,83,9,0.25); border-radius: 10px; font-size: 14px; background: #FAF7F2; box-sizing: border-box;">
                  <option value="darshan_parikrama" {{ old('purpose_of_stay') == 'darshan_parikrama' ? 'selected' : '' }}>
                    कामतानाथ दर्शन एवं परिक्रमा</option>
                  <option value="family_pilgrimage" {{ old('purpose_of_stay') == 'family_pilgrimage' ? 'selected' : '' }}>
                    सपरिवार चित्रकूट तीर्थ यात्रा</option>
                  <option value="sadhana_meditation" {{ old('purpose_of_stay') == 'sadhana_meditation' ? 'selected' : '' }}>
                    साधना, स्वाध्याय एवं मौन जप</option>
                  <option value="seva_volunteer" {{ old('purpose_of_stay') == 'seva_volunteer' ? 'selected' : '' }}>ट्रस्ट
                    आश्रम सेवा (Volunteering)</option>
                  <option value="senior_citizen_yatra" {{ old('purpose_of_stay') == 'senior_citizen_yatra' ? 'selected' : '' }}>वरिष्ठ नागरिक तीर्थ यात्रा</option>
                  <option value="other" {{ old('purpose_of_stay') == 'other' ? 'selected' : '' }}>अन्य पावन उद्देश्य
                  </option>
                </select>
              </div>
            </div>

            <!-- ID Proof Type -->
            <div style="margin-bottom: 18px;">
              <label style="display: block; font-size: 12.5px; font-weight: 700; color: #2B1A12; margin-bottom: 6px;">
                पहचान पत्र का प्रकार (आगमन पर मूल प्रति अनिवार्य है)
              </label>
              <select name="id_proof_type"
                style="width: 100%; padding: 11px 14px; border: 1.5px solid rgba(180,83,9,0.25); border-radius: 10px; font-size: 14px; background: #FAF7F2; box-sizing: border-box;">
                <option value="aadhaar">आधार कार्ड (Aadhaar Card)</option>
                <option value="voter_id">मतदाता पहचान पत्र (Voter ID)</option>
                <option value="driving_license">ड्राइविंग लाइसेंस (Driving License)</option>
                <option value="passport">पासपोर्ट (Passport)</option>
              </select>
            </div>

            <!-- Special Requests -->
            <div style="margin-bottom: 24px;">
              <label style="display: block; font-size: 12.5px; font-weight: 700; color: #2B1A12; margin-bottom: 6px;">
                विशेष आवश्यकताएं अथवा संदेश (वैकल्पिक)
              </label>
              <textarea name="special_requests" rows="3"
                placeholder="उदा. वृद्धजनों हेतु भूतल कक्ष की आवश्यकता, विशेष भोजन व्यवस्था आदि..."
                style="width: 100%; padding: 11px 14px; border: 1.5px solid rgba(180,83,9,0.25); border-radius: 10px; font-size: 14px; background: #FAF7F2; box-sizing: border-box; font-family: inherit;">{{ old('special_requests') }}</textarea>
            </div>

            <button type="submit" class="btn primary"
              style="width: 100%; padding: 14px; font-size: 15px; font-weight: 700; border-radius: 12px; background: #2D6A4F; border-color: #2D6A4F; cursor: pointer; box-shadow: 0 4px 14px rgba(45,106,79,0.25);">
              Submit Stay Enquiry &rarr;
            </button>
          </form>

        </div>

        <!-- Right Column: Guidelines & Ashram Rules -->
        <div style="display: flex; flex-direction: column; gap: 24px;">

          <!-- Box: Guidelines -->
          <div
            style="background: #FFFDFC; border: 1.5px solid rgba(45,106,79,0.25); border-radius: 20px; padding: 28px 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
              <div
                style="width: 38px; height: 38px; border-radius: 10px; background: rgba(45,106,79,0.12); display: grid; place-items: center; color: #2D6A4F;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                </svg>
              </div>
              <h3 style="font: 700 18px var(--dev); color: #1B4332; margin: 0;">आश्रम प्रवास नियम व मर्यादा</h3>
            </div>

            <ul style="margin: 0; padding-left: 20px; font-size: 13.5px; line-height: 1.85; color: #40534C;">
              <li><strong>पहचान पत्र:</strong> चेक-इन के समय सभी वयस्क यात्रियों का सरकार मान्य मूल पहचान पत्र प्रस्तुत
                करना अनिवार्य है।</li>
              <li><strong>सात्विक आचरण:</strong> परिसर में मदिरा, धूम्रपान, मांसाहार एवं अभद्र आचरण सर्वथा निषिद्ध एवं
                दंडनीय है।</li>
              <li><strong>स्वच्छता एवं शांति:</strong> आश्रम में शांति एवं स्वच्छता बनाए रखें। तेज ध्वनि एवं अशांति की
                अनुमति नहीं है।</li>
              <li><strong>चेक-इन/चेक-आउट:</strong> सामान्यतः चेक-इन समय दोपहर 12:00 बजे एवं चेक-आउट प्रातः 10:00 बजे है।
              </li>
              <li><strong>अग्रिम पूछताछ:</strong> यह एक पूछताछ प्रपत्र है। कक्ष उपलब्धता एवं आश्रम स्वीकृति पश्चात पुष्टि
                ईमेल/व्हाट्सएप द्वारा प्रेषित की जाएगी।</li>
            </ul>
          </div>

          <!-- Box: Facilities -->
          <div
            style="background: #FFFDFC; border: 1.5px solid rgba(180,83,9,0.2); border-radius: 20px; padding: 28px 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
            <h3 style="font: 700 18px var(--dev); color: #2B1A12; margin: 0 0 14px;">उपलब्ध विश्राम सुविधाएं</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 13px; color: #7A4B29;">
              <div style="display: flex; align-items: center; gap: 8px;">
                <span style="color: #2D6A4F; font-weight: 800;">✓</span> स्वच्छ पेयजल
              </div>
              <div style="display: flex; align-items: center; gap: 8px;">
                <span style="color: #2D6A4F; font-weight: 800;">✓</span> संलग्न स्नानागार
              </div>
              <div style="display: flex; align-items: center; gap: 8px;">
                <span style="color: #2D6A4F; font-weight: 800;">✓</span> सात्विक भोजनालय
              </div>
              <div style="display: flex; align-items: center; gap: 8px;">
                <span style="color: #2D6A4F; font-weight: 800;">✓</span> 24 घंटे सुरक्षा
              </div>
              <div style="display: flex; align-items: center; gap: 8px;">
                <span style="color: #2D6A4F; font-weight: 800;">✓</span> परिक्रमा पथ निकटता
              </div>
              <div style="display: flex; align-items: center; gap: 8px;">
                <span style="color: #2D6A4F; font-weight: 800;">✓</span> गर्म जल व्यवस्था
              </div>
            </div>
          </div>

          <!-- Box: Contact Office -->
          <div
            style="background: #FAF7F2; border: 1px dashed rgba(180,83,9,0.3); border-radius: 16px; padding: 20px; font-size: 13px; color: #7A4B29; line-height: 1.7;">
            <strong>तत्काल सहायता हेतु:</strong><br>
            मंदिर विश्राम व्यवस्था प्रकोष्ठ: <a href="tel:{{ $orgSettings->phone ?? '+919415000000' }}"
              style="color: #D95D0F; font-weight: 700; text-decoration: none;">{{ $orgSettings->phone ?? '+91 94150 00000' }}</a><br>
            ईमेल: <a href="mailto:{{ $orgSettings->email ?? 'info@kamadgiripeetham.org' }}"
              style="color: #D95D0F; font-weight: 700; text-decoration: none;">{{ $orgSettings->email ??
              'info@kamadgiripeetham.org' }}</a>
          </div>

        </div>

      </div>

      <!-- FAQ Section: Kamadgiri Stay & Dharamshala -->
      <div style="margin-top: 60px; background: #FFFDFC; border: 1.5px solid rgba(180,83,9,0.18); border-radius: 22px; padding: clamp(24px, 4vw, 36px); box-shadow: 0 8px 30px rgba(180,83,9,0.05);">
        <div style="text-align: center; margin-bottom: 28px;">
          <span style="font-size: 11px; font-weight: 800; color: #B45309; text-transform: uppercase; letter-spacing: 1.5px;">तीर्थयात्री सामान्य प्रश्न (Pilgrim FAQs)</span>
          <h2 style="font: 700 24px var(--dev); color: #2B1A12; margin: 6px 0 0;">Kamadgiri Stay & Dharamshala Accommodation FAQ</h2>
        </div>

        <div style="display: flex; flex-direction: column; gap: 16px;">
          <details style="background: #FAF6EE; border: 1px solid rgba(180,83,9,0.15); border-radius: 12px; padding: 14px 18px; cursor: pointer;" open>
            <summary style="font-weight: 700; font-size: 15px; color: #2B1A12;">What is the best Kamadgiri stay for pilgrims and families visiting Chitrakoot?</summary>
            <p style="font-size: 14px; line-height: 1.7; color: #7A4B29; margin: 10px 0 0;">
              Shri Kamadgiri Peetham Sewa Trust provides <strong>Atithi Vishram Griha</strong>, a peaceful, clean, and sattvic pilgrim stay located directly on Kamadgiri Parikrama Marg. It offers both AC and Non-AC family rooms, attached bathrooms, pure drinking water, and safe parking.
            </p>
          </details>

          <details style="background: #FAF6EE; border: 1px solid rgba(180,83,9,0.15); border-radius: 12px; padding: 14px 18px; cursor: pointer;">
            <summary style="font-weight: 700; font-size: 15px; color: #2B1A12;">How close is this stay to Kamadgiri Parvat and Kamtanath Temple?</summary>
            <p style="font-size: 14px; line-height: 1.7; color: #7A4B29; margin: 10px 0 0;">
              The Atithi Vishram Griha is situated right on the <strong>Kamadgiri Parikrama Marg</strong>, within comfortable walking distance to the sacred <strong>Kamadgiri Parvat</strong> and the Pratham Mukharbindu of <strong>Kamadgiri Temple (Kamtanath Ji)</strong>.
            </p>
          </details>

          <details style="background: #FAF6EE; border: 1px solid rgba(180,83,9,0.15); border-radius: 12px; padding: 14px 18px; cursor: pointer;">
            <summary style="font-weight: 700; font-size: 15px; color: #2B1A12;">How can devotees book or enquire about Kamadgiri stay?</summary>
            <p style="font-size: 14px; line-height: 1.7; color: #7A4B29; margin: 10px 0 0;">
              Devotees can easily submit the advance enquiry form above with their proposed check-in and check-out dates and room preference, or contact the Trust office helpline directly at {{ $orgSettings->phone ?? '+91 94150 00000' }}.
            </p>
          </details>
        </div>
      </div>

    </div>
  </section>

@endsection

@section('structured_data')
  @php
    $lodgingSchema = [
      '@context' => 'https://schema.org',
      '@graph' => [
        [
          '@type' => 'LodgingBusiness',
          '@id' => url('/services/atithi-vishram#lodging'),
          'name' => 'Kamadgiri Stay - Atithi Vishram Griha & Dharamshala',
          'alternateName' => ['कामदगिरि अतिथि विश्राम गृह', 'Kamadgiri Dharamshala', 'Chitrakoot Pilgrim Stay'],
          'description' => 'Clean, peaceful and sattvic pilgrim stay and dharamshala near sacred Kamadgiri Parvat and Kamtanath Temple in Chitrakoot Dham.',
          'url' => url('/services/atithi-vishram'),
          'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => 'Kamadgiri Parikrama Marg',
            'addressLocality' => 'Chitrakoot Dham',
            'addressCountry' => 'IN'
          ],
          'priceRange' => '₹',
          'telephone' => $orgSettings->phone ?? '+919415000000',
        ],
        [
          '@type' => 'FAQPage',
          '@id' => url('/services/atithi-vishram#faq'),
          'mainEntity' => [
            [
              '@type' => 'Question',
              'name' => 'What is the best Kamadgiri stay for pilgrims and families visiting Chitrakoot?',
              'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'Shri Kamadgiri Peetham Sewa Trust provides Atithi Vishram Griha, a peaceful, clean, and sattvic pilgrim stay located directly on Kamadgiri Parikrama Marg.'
              ]
            ],
            [
              '@type' => 'Question',
              'name' => 'How close is this stay to Kamadgiri Parvat and Kamtanath Temple?',
              'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'The Atithi Vishram Griha is situated right on the Kamadgiri Parikrama Marg, within comfortable walking distance to the sacred Kamadgiri Parvat and Kamadgiri Temple.'
              ]
            ],
            [
              '@type' => 'Question',
              'name' => 'How can devotees book or enquire about Kamadgiri stay?',
              'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'Devotees can easily submit the advance enquiry form online or contact the Trust office helpline directly for availability.'
              ]
            ]
          ]
        ]
      ]
    ];
  @endphp
  <script type="application/ld+json">
  {!! json_encode($lodgingSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
  </script>
@endsection