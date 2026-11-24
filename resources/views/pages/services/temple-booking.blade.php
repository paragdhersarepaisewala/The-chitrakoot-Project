@extends('layouts.app')

@section('title', 'मंदिर परिसर व मंडप आरक्षण पूछताछ | ' . ($orgSettings->org_name ?? 'श्री कामदगिरि पीठम् सेवा ट्रस्ट'))
@section('meta_description', 'चित्रकूट धाम में विवाह, वैदिक महायज्ञ, श्रीराम कथा, श्रीमद्भागवत एवं महाप्रसाद भंडारे हेतु मंदिर परिसर व यज्ञ मंडप आरक्षण की अग्रिम पूछताछ।')

@section('styles')
  <style>
    .services-split-grid {
      display: grid;
      grid-template-columns: 1.5fr 1fr;
      gap: 36px;
      align-items: start;
    }

    .services-form-grid-2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 18px;
      margin-bottom: 18px;
    }

    @media (max-width: 860px) {
      .services-split-grid {
        grid-template-columns: 1fr !important;
        gap: 24px !important;
      }

      .services-form-grid-2 {
        grid-template-columns: 1fr !important;
        gap: 14px !important;
      }

      .service-form-card {
        padding: 22px 16px !important;
        border-radius: 16px !important;
      }

      .service-form-card input,
      .service-form-card select,
      .service-form-card textarea {
        font-size: 16px !important;
      }
    }
  </style>
@endsection

@section('content')

  <!-- Hero Header -->
  <section class="section"
    style="padding-top: 140px; padding-bottom: 60px; background: linear-gradient(180deg, #FAF4ED 0%, #FBF7F0 100%); color: #2B1A12; position: relative; overflow: hidden; border-bottom: 1px solid rgba(180, 83, 9, 0.15);">
    <div
      style="position: absolute; inset: 0; background-image: url('{{ asset('uploads/kamadgiri-temple-crest-emblem-1792267739.webp') }}'); background-position: center; background-size: cover; opacity: 0.06; filter: blur(3px);">
    </div>
    <div class="container" style="position: relative; z-index: 2; text-align: center; max-width: 900px;">

      <!-- Breadcrumbs -->
      <nav aria-label="Breadcrumb"
        style="display: inline-flex; align-items: center; gap: 8px; font-size: 12px; color: #7A4B29; margin-bottom: 16px; background: rgba(255,255,255,0.7); padding: 4px 14px; border-radius: 9999px; border: 1px solid rgba(180,83,9,0.15);">
        <a href="{{ route('home') }}" style="color: inherit; text-decoration: none;">होम</a>
        <span>&rsaquo;</span>
        <a href="{{ route('services.index') }}" style="color: inherit; text-decoration: none;">मंदिर सेवाएं</a>
        <span>&rsaquo;</span>
        <span style="color: #8F1719; font-weight: 700;">परिसर आरक्षण पूछताछ</span>
      </nav>

      <h1 style="font: 700 clamp(28px, 4vw, 48px) var(--dev); color: #2B1A12; margin: 12px 0 16px; line-height: 1.25;">
        मंदिर परिसर एवं सभा मंडप आरक्षण पूछताछ
      </h1>

      <p style="font: 400 16.5px/1.8 var(--dev); color: #7A4B29; margin: 0 auto; max-width: 760px;">
        चित्रकूट धाम के पावन सानिध्य में वैदिक विवाह संस्कार, महायज्ञ, श्रीराम कथा, श्रीमद्भागवत कथा, विशेष अनुष्ठान एवं
        महाप्रसाद भंडारे हेतु परिसर उपलब्धता की अग्रिम पूछताछ।
      </p>

    </div>
  </section>

  <!-- Main Form & Information Section -->
  <section class="section" style="background: var(--paper, #FAF4ED); padding: 60px 0 80px;">
    <div class="container" style="max-width: 1100px;">

      <!-- Clear Disclaimer Alert -->
      <div
        style="background: #fff8e1; border: 1.5px solid #ffe082; border-radius: 16px; padding: 18px 24px; margin-bottom: 32px; display: flex; align-items: flex-start; gap: 14px; box-shadow: 0 4px 14px rgba(255,179,0,0.06);">
        <div
          style="width: 32px; height: 32px; border-radius: 50%; background: #f59e0b; color: #fff; display: grid; place-items: center; font-weight: 800; font-size: 16px; flex-shrink: 0;">
          ℹ</div>
        <div style="font-size: 13.5px; line-height: 1.7; color: #92400e;">
          <strong>महत्वपूर्ण सूचना (Notice):</strong> यह प्रपत्र मंदिर परिसर एवं मंडप आरक्षण की <strong>केवल पूर्व-पूछताछ (Booking Enquiry)</strong> है, अंतिम पुष्टि नहीं। ट्रस्ट व्यवस्थापक समिति शुभ मुहूर्त, मंदिर की मर्यादा,
          तिथियों की उपलब्धता एवं अपेक्षित व्यवस्थाओं की समीक्षा कर आपसे शीघ्र संपर्क करेगी।
        </div>
      </div>

      @if(session('success'))
        <div
          style="background: #edf7ee; border: 1.5px solid #81c784; border-radius: 16px; padding: 24px 28px; margin-bottom: 36px; box-shadow: 0 4px 20px rgba(46,125,50,0.08);">
          <div style="display: flex; align-items: flex-start; gap: 14px;">
            <div
              style="width: 36px; height: 36px; border-radius: 50%; background: #2e7d32; color: #fff; display: grid; place-items: center; flex-shrink: 0; font-weight: 800;">
              ✓</div>
            <div>
              <h3 style="font: 700 19px var(--dev); color: #1b5e20; margin: 0 0 6px;">आपका आयोजन प्रस्ताव सफलतापूर्वक
                प्राप्त हो गया है!</h3>
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

      <div class="services-split-grid">

        <!-- Left Column: The Enquiry Form -->
        <div class="service-form-card"
          style="background: #FFFDFC; border: 1.5px solid rgba(180,83,9,0.2); border-radius: 22px; padding: 36px 32px; box-shadow: 0 8px 30px rgba(0,0,0,0.03);">

          <div style="margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid rgba(180,83,9,0.14);">
            <h2 style="font: 700 22px var(--dev); color: #2B1A12; margin: 0 0 6px;">
              परिसर आरक्षण पूछताछ प्रपत्र (Premises Enquiry Form)
            </h2>
            <p style="font-size: 13.5px; color: #7A4B29; margin: 0;">
              कृपया अपने प्रस्तावित धार्मिक आयोजन का विवरण अंकित करें।
            </p>
          </div>

          <form action="{{ route('services.templeBooking.submit') }}" method="POST">
            @csrf

            <!-- Devotee Name & Phone -->
            <div class="services-form-grid-2">
              <div>
                <label style="display: block; font-size: 12.5px; font-weight: 700; color: #2B1A12; margin-bottom: 6px;">
                  आयोजक / यजमान का नाम <span style="color: #dc2626;">*</span>
                </label>
                <input type="text" name="devotee_name" value="{{ old('devotee_name', auth()->user()?->name) }}" required
                  placeholder="उदा. श्री सत्यनारायण तिवारी"
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
            <div class="services-form-grid-2">
              <div>
                <label style="display: block; font-size: 12.5px; font-weight: 700; color: #2B1A12; margin-bottom: 6px;">
                  ईमेल पता (सूचना व पावती हेतु) <span style="color: #dc2626;">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" required
                  placeholder="उदा. organizer@example.com"
                  style="width: 100%; padding: 11px 14px; border: 1.5px solid rgba(180,83,9,0.25); border-radius: 10px; font-size: 14px; background: #FAF7F2; box-sizing: border-box;">
              </div>

              <div>
                <label style="display: block; font-size: 12.5px; font-weight: 700; color: #2B1A12; margin-bottom: 6px;">
                  नगर एवं राज्य (City, State)
                </label>
                <input type="text" name="city" value="{{ old('city') }}" placeholder="उदा. प्रयागराज, उ.प्र."
                  style="width: 100%; padding: 11px 14px; border: 1.5px solid rgba(180,83,9,0.25); border-radius: 10px; font-size: 14px; background: #FAF7F2; box-sizing: border-box;">
              </div>
            </div>

            <!-- Event Type & Event Title -->
            <div class="services-form-grid-2">
              <div>
                <label style="display: block; font-size: 12.5px; font-weight: 700; color: #2B1A12; margin-bottom: 6px;">
                  आयोजन का प्रकार (Event Type) <span style="color: #dc2626;">*</span>
                </label>
                <select name="event_type" required
                  style="width: 100%; padding: 11px 14px; border: 1.5px solid rgba(180,83,9,0.25); border-radius: 10px; font-size: 14px; background: #FAF7F2; box-sizing: border-box;">
                  <option value="shadi" {{ old('event_type') == 'shadi' ? 'selected' : '' }}>विवाह संस्कार (Vedic Marriage /
                    Shadi)</option>
                  <option value="hawan" {{ old('event_type') == 'hawan' ? 'selected' : '' }}>वैदिक महायज्ञ एवं हवन
                    (Mahayagya & Hawan)</option>
                  <option value="katha" {{ old('event_type') == 'katha' ? 'selected' : '' }}>श्रीराम / श्रीमद्भागवत कथा
                    (Katha)</option>
                  <option value="pooja" {{ old('event_type') == 'pooja' ? 'selected' : '' }}>विशेष अनुष्ठान / महारुद्राभिषेक
                  </option>
                  <option value="bhandara" {{ old('event_type') == 'bhandara' ? 'selected' : '' }}>महाप्रसाद एवं अन्नक्षेत्र
                    भंडारा</option>
                  <option value="satsang" {{ old('event_type') == 'satsang' ? 'selected' : '' }}>सत्संग एवं धार्मिक सम्मेलन
                  </option>
                  <option value="mundan" {{ old('event_type') == 'mundan' ? 'selected' : '' }}>मुंडन / उपनयन संस्कार
                  </option>
                  <option value="other" {{ old('event_type') == 'other' ? 'selected' : '' }}>अन्य पावन धार्मिक कार्यक्रम
                  </option>
                </select>
              </div>

              <div>
                <label style="display: block; font-size: 12.5px; font-weight: 700; color: #2B1A12; margin-bottom: 6px;">
                  कार्यक्रम का शीर्षक (Event Title)
                </label>
                <input type="text" name="event_title" value="{{ old('event_title') }}"
                  placeholder="उदा. श्रीमद्भागवत कथा ज्ञान महायज्ञ"
                  style="width: 100%; padding: 11px 14px; border: 1.5px solid rgba(180,83,9,0.25); border-radius: 10px; font-size: 14px; background: #FAF7F2; box-sizing: border-box;">
              </div>
            </div>

            <!-- Proposed Date & Alternate Date -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
              <div>
                <label style="display: block; font-size: 12.5px; font-weight: 700; color: #2B1A12; margin-bottom: 6px;">
                  प्रस्तावित तिथि (Proposed Date) <span style="color: #dc2626;">*</span>
                </label>
                <input type="date" name="proposed_date" value="{{ old('proposed_date') }}" min="{{ date('Y-m-d') }}"
                  required
                  style="width: 100%; padding: 11px 14px; border: 1.5px solid rgba(180,83,9,0.25); border-radius: 10px; font-size: 14px; background: #FAF7F2; box-sizing: border-box;">
              </div>

              <div>
                <label style="display: block; font-size: 12.5px; font-weight: 700; color: #2B1A12; margin-bottom: 6px;">
                  वैकल्पिक तिथि (Alternate Date)
                </label>
                <input type="date" name="alternate_date" value="{{ old('alternate_date') }}" min="{{ date('Y-m-d') }}"
                  style="width: 100%; padding: 11px 14px; border: 1.5px solid rgba(180,83,9,0.25); border-radius: 10px; font-size: 14px; background: #FAF7F2; box-sizing: border-box;">
              </div>
            </div>

            <!-- Duration Days & Expected Gathering -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 20px;">
              <div>
                <label style="display: block; font-size: 12.5px; font-weight: 700; color: #2B1A12; margin-bottom: 6px;">
                  आयोजन की अवधि (दिनों में) <span style="color: #dc2626;">*</span>
                </label>
                <input type="number" name="duration_days" value="{{ old('duration_days', 1) }}" min="1" max="30" required
                  style="width: 100%; padding: 11px 14px; border: 1.5px solid rgba(180,83,9,0.25); border-radius: 10px; font-size: 14px; background: #FAF7F2; box-sizing: border-box;">
              </div>

              <div>
                <label style="display: block; font-size: 12.5px; font-weight: 700; color: #2B1A12; margin-bottom: 6px;">
                  अपेक्षित श्रद्धालुओं / अतिथियों की संख्या <span style="color: #dc2626;">*</span>
                </label>
                <input type="number" name="expected_gathering" value="{{ old('expected_gathering', 100) }}" min="5"
                  max="10000" required
                  style="width: 100%; padding: 11px 14px; border: 1.5px solid rgba(180,83,9,0.25); border-radius: 10px; font-size: 14px; background: #FAF7F2; box-sizing: border-box;">
              </div>
            </div>

            <!-- Checkbox Services Required -->
            <div
              style="background: #FAF7F2; border: 1px solid rgba(180,83,9,0.18); border-radius: 12px; padding: 16px; margin-bottom: 20px;">
              <span style="font-size: 12.5px; font-weight: 700; color: #2B1A12; display: block; margin-bottom: 10px;">
                अतिरिक्त व्यवस्था आवश्यकताएं (संबंधित विकल्पों का चयन करें):
              </span>
              <div style="display: flex; flex-direction: column; gap: 10px; font-size: 13.5px; color: #593a28;">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                  <input type="checkbox" name="requires_priest" value="1" {{ old('requires_priest', true) ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #8F1719;">
                  <span>वैदिक पुरोहित / आचार्यों की व्यवस्था चाहिए (Priest Coordination)</span>
                </label>
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                  <input type="checkbox" name="requires_catering" value="1" {{ old('requires_catering') ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #8F1719;">
                  <span>सात्विक महाप्रसाद / भोजन व्यवस्था परामर्श चाहिए (Temple Prasad Catering)</span>
                </label>
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                  <input type="checkbox" name="requires_accommodation" value="1" {{ old('requires_accommodation') ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #8F1719;">
                  <span>प्रमुख अतिथियों हेतु आश्रम कक्ष विश्राम व्यवस्था चाहिए (Guest Stay Coordination)</span>
                </label>
              </div>
            </div>

            <!-- Additional Requirements -->
            <div style="margin-bottom: 24px;">
              <label style="display: block; font-size: 12.5px; font-weight: 700; color: #2B1A12; margin-bottom: 6px;">
                विस्तृत विवरण अथवा विशेष व्यवस्थाएं (मंडप, ध्वनि विस्तारक, साज-सज्जा आदि)
              </label>
              <textarea name="additional_requirements" rows="3"
                placeholder="उदा. 7 दिवसीय कथा हेतु विशाल शामियाना एवं साउंड सिस्टम की व्यवस्था, यज्ञशाला कुंड आदि..."
                style="width: 100%; padding: 11px 14px; border: 1.5px solid rgba(180,83,9,0.25); border-radius: 10px; font-size: 14px; background: #FAF7F2; box-sizing: border-box; font-family: inherit;">{{ old('additional_requirements') }}</textarea>
            </div>

            <button type="submit" class="btn primary"
              style="width: 100%; padding: 14px; font-size: 15px; font-weight: 700; border-radius: 12px; background: #8F1719; border-color: #8F1719; cursor: pointer; box-shadow: 0 4px 14px rgba(143,23,25,0.25);">
              Submit Hall Booking Enquiry &rarr;
            </button>
          </form>

        </div>

        <!-- Right Column: Venue Info & Sanctity Guidelines -->
        <div style="display: flex; flex-direction: column; gap: 24px;">

          <!-- Box: Sanctity & Heritage -->
          <div
            style="background: #FFFDFC; border: 1.5px solid rgba(143,23,25,0.25); border-radius: 20px; padding: 28px 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
              <div
                style="width: 38px; height: 38px; border-radius: 10px; background: rgba(143,23,25,0.12); display: grid; place-items: center; color: #8F1719;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                </svg>
              </div>
              <h3 style="font: 700 18px var(--dev); color: #8F1719; margin: 0;">मंदिर परिसर मर्यादा व नियम</h3>
            </div>

            <ul style="margin: 0; padding-left: 20px; font-size: 13.5px; line-height: 1.85; color: #5C383A;">
              <li><strong>धार्मिक स्वरूप:</strong> परिसर केवल सनातन परंपरा सम्मत धार्मिक, आध्यात्मिक एवं वैदिक मांगलिक
                आयोजनों हेतु ही उपलब्ध कराया जाता है।</li>
              <li><strong>सात्विक भोजन:</strong> परिसर में केवल पूर्णतः सात्विक (लहसुन-प्याज रहित विशुद्ध शाकाहारी)
                महाप्रसाद भोजन ही ग्राह्य है।</li>
              <li><strong>निषिद्ध वस्तुएं:</strong> मदिरा, तंबाकू, धूम्रपान, आतिशबाजी एवं अशोभनीय डीजे ध्वनि सर्वथा वर्जित
                हैं।</li>
              <li><strong>आरक्षण प्रक्रिया:</strong> पूछताछ प्राप्त होने के उपरांत ट्रस्ट प्रबंधन समिति द्वारा आयोजन की
                स्वीकृति एवं सहयोग राशि का निर्धारण किया जाएगा।</li>
            </ul>
          </div>

          <!-- Box: Available Spaces -->
          <div
            style="background: #FFFDFC; border: 1.5px solid rgba(180,83,9,0.2); border-radius: 20px; padding: 28px 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
            <h3 style="font: 700 18px var(--dev); color: #2B1A12; margin: 0 0 14px;">उपलब्ध परिसर व मंडप</h3>
            <div style="display: flex; flex-direction: column; gap: 10px; font-size: 13.5px; color: #7A4B29;">
              <div><strong>1. मुख्य यज्ञशाला मंडप:</strong> 51 कुंडीय व एकादश कुंडीय वैदिक महायज्ञ हेतु।</div>
              <div><strong>2. श्रीराम कथा सभा मंडप:</strong> 500 से 2000 श्रद्धालुओं की बैठक क्षमता।</div>
              <div><strong>3. विवाह संस्कार वाटिका:</strong> पारम्परिक वैदिक विवाह हेतु पवित्र परिसर।</div>
              <div><strong>4. महाप्रसाद अन्नक्षेत्र हाल:</strong> सुव्यवस्थित पंगत भोजन व्यवस्था।</div>
            </div>
          </div>

          <!-- Box: Temple Office Contact -->
          <div
            style="background: #FAF7F2; border: 1px dashed rgba(180,83,9,0.3); border-radius: 16px; padding: 20px; font-size: 13px; color: #7A4B29; line-height: 1.7;">
            <strong>सीधे परामर्श हेतु कार्यालय संपर्क:</strong><br>
            मंदिर परिसर व्यवस्था प्रकोष्ठ: <a href="tel:{{ $orgSettings->phone ?? '+919415000000' }}"
              style="color: #8F1719; font-weight: 700; text-decoration: none;">{{ $orgSettings->phone ?? '+91 94150 00000' }}</a><br>
            ईमेल: <a href="mailto:{{ $orgSettings->email ?? 'info@kamadgiripeetham.org' }}"
              style="color: #8F1719; font-weight: 700; text-decoration: none;">{{ $orgSettings->email ??
              'info@kamadgiripeetham.org' }}</a>
          </div>

        </div>

      </div>

    </div>
  </section>

@endsection