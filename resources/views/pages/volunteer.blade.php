@extends('layouts.app')

@section('title', 'सेवादार एवं स्वयंसेवक पंजीकरण | ' . ($orgSettings->org_name ?? 'श्री कामदगिरि पीठम् सेवा ट्रस्ट'))
@section('meta_description', 'चित्रकूट धाम में सनातन धर्म, गौसेवा, गुरुकुल एवं तीर्थयात्री सेवा में अपना अमूल्य समय व कौशल समर्पित करने हेतु स्वयंसेवक पंजीकरण करें।')

@section('content')

  <section class="section" style="padding-top: 150px; background: var(--ivory); padding-bottom: 90px;">
    <div class="container" style="max-width: 820px;">

      <div class="section-head reveal" style="text-align: center; margin-bottom: 40px;">
        <div class="eyebrow" style="justify-content: center; color: var(--sand);">VOLUNTEER & SEVA SEWAK</div>
        <h1 style="font: 700 clamp(32px, 4.5vw, 54px) var(--dev); color: var(--deep); margin: 14px 0 16px;">
          सेवादार / स्वयंसेवक पंजीकरण
        </h1>
        <p style="font: 400 16px/1.8 var(--dev); color: rgba(43,35,29,0.8); max-width: 650px; margin: 0 auto;">
          "सेवा परमो धर्मः" — चित्रकूट की पवित्र तपोभूमि पर भगवान श्रीराम के सेवा प्रकल्पों में अपने समय, श्रम एवं ज्ञान का योगदान दें।
        </p>
      </div>

      @if(session('success'))
        <div style="background: #d4edda; color: #155724; border-left: 4px solid #28a745; padding: 16px 20px; border-radius: 6px; font-size: 14px; font-weight: 600; margin-bottom: 30px; font-family: var(--dev);">
          {{ session('success') }}
        </div>
      @endif

      @if(session('error'))
        <div style="background: #f8d7da; color: #721c24; border-left: 4px solid #dc3545; padding: 16px 20px; border-radius: 6px; font-size: 14px; font-weight: 600; margin-bottom: 30px; font-family: var(--dev);">
          {{ session('error') }}
        </div>
      @endif

      @if(! $isEnabled)
        <!-- Registration Disabled Notice -->
        <div style="background: #FFFDFC; border: 1.5px solid rgba(180,83,9,0.2); border-radius: 20px; padding: 48px; text-align: center; box-shadow: 0 12px 35px rgba(130,70,20,0.06);">
          <div style="width: 60px; height: 60px; border-radius: 50%; background: rgba(180,83,9,0.12); color: #B45309; display: inline-grid; place-items: center; margin-bottom: 18px;">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          </div>
          <h2 style="font: 700 24px var(--dev); color: #2B1A12; margin: 0 0 12px;">वर्तमान में नए पंजीकरण स्थगित हैं</h2>
          <p style="font: 400 15px/1.8 var(--dev); color: rgba(43,26,18,0.8); max-width: 500px; margin: 0 auto 24px;">
            इस सत्र के लिए सेवादार स्वयंसेवक पंजीकरण का लक्ष्य पूर्ण हो चुका है। आगामी पर्वों एवं आयोजनों हेतु पंजीकरण शीघ्र पुनः प्रारंभ किए जाएंगे।
          </p>
          <a href="{{ url('/contact') }}" class="btn primary" style="border-radius: 9999px; padding: 12px 28px; font-size: 13px; font-weight: 700; background: linear-gradient(135deg, #D95D0F, #B45309); color: #FFFDFC; border: none; box-shadow: 0 8px 20px rgba(217,93,15,0.25);">
            संपर्क कार्यालय से जानकारी प्राप्त करें &rarr;
          </a>
        </div>
      @else
        <!-- Volunteer Application Form Card -->
        <div style="background: #FFFDFC; border: 1.5px solid rgba(180,83,9,0.18); border-radius: 20px; padding: clamp(24px, 4vw, 44px); box-shadow: 0 14px 40px rgba(130,70,20,0.06);">
          
          <form action="{{ route('volunteer.submit') }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 20px;">
              <div>
                <label class="form-label" style="font-size: 12px; font-weight: 700; color: #78350F; letter-spacing: .06em; margin-bottom: 8px; display: block; font-family: var(--dev);">
                  पूरा नाम (Full Name) *
                </label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required placeholder="उदा. राहुल शर्मा" class="form-input" style="padding: 12px 16px; border: 1.5px solid rgba(180,83,9,0.22); border-radius: 12px; width: 100%; font-size: 14.5px; background: #FAF4ED; color: #2B1A12; font-family: var(--dev); box-sizing: border-box;">
              </div>

              <div>
                <label class="form-label" style="font-size: 12px; font-weight: 700; color: #78350F; letter-spacing: .06em; margin-bottom: 8px; display: block; font-family: var(--dev);">
                  मोबाइल नंबर (WhatsApp Active) *
                </label>
                <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="10-अंकीय मोबाइल" class="form-input" style="padding: 12px 16px; border: 1.5px solid rgba(180,83,9,0.22); border-radius: 12px; width: 100%; font-size: 14.5px; background: #FAF4ED; color: #2B1A12; font-family: var(--dev); box-sizing: border-box;">
              </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 20px;">
              <div>
                <label class="form-label" style="font-size: 12px; font-weight: 700; color: #78350F; letter-spacing: .06em; margin-bottom: 8px; display: block; font-family: var(--dev);">
                  ईमेल पता (Email) *
                </label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required placeholder="email@address.com" class="form-input" style="padding: 12px 16px; border: 1.5px solid rgba(180,83,9,0.22); border-radius: 12px; width: 100%; font-size: 14.5px; background: #FAF4ED; color: #2B1A12; box-sizing: border-box;">
              </div>

              <div>
                <label class="form-label" style="font-size: 12px; font-weight: 700; color: #78350F; letter-spacing: .06em; margin-bottom: 8px; display: block; font-family: var(--dev);">
                  व्यवसाय / पेशा (Occupation)
                </label>
                <input type="text" name="occupation" value="{{ old('occupation') }}" placeholder="उदा. छात्र, शिक्षक, इंजीनियर, व्यापारी" class="form-input" style="padding: 12px 16px; border: 1.5px solid rgba(180,83,9,0.22); border-radius: 12px; width: 100%; font-size: 14.5px; background: #FAF4ED; color: #2B1A12; font-family: var(--dev); box-sizing: border-box;">
              </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 24px;">
              <div>
                <label class="form-label" style="font-size: 12px; font-weight: 700; color: #78350F; letter-spacing: .06em; margin-bottom: 8px; display: block; font-family: var(--dev);">
                  शहर (City)
                </label>
                <input type="text" name="city" value="{{ old('city') }}" placeholder="उदा. प्रयागराज, लखनऊ, चित्रकूट" class="form-input" style="padding: 12px 16px; border: 1.5px solid rgba(180,83,9,0.22); border-radius: 12px; width: 100%; font-size: 14.5px; background: #FAF4ED; color: #2B1A12; font-family: var(--dev); box-sizing: border-box;">
              </div>

              <div>
                <label class="form-label" style="font-size: 12px; font-weight: 700; color: #78350F; letter-spacing: .06em; margin-bottom: 8px; display: block; font-family: var(--dev);">
                  राज्य (State)
                </label>
                <input type="text" name="state" value="{{ old('state') }}" placeholder="उदा. उत्तर प्रदेश, मध्य प्रदेश" class="form-input" style="padding: 12px 16px; border: 1.5px solid rgba(180,83,9,0.22); border-radius: 12px; width: 100%; font-size: 14.5px; background: #FAF4ED; color: #2B1A12; font-family: var(--dev); box-sizing: border-box;">
              </div>
            </div>

            <div style="margin-bottom: 24px;">
              <label class="form-label" style="font-size: 12px; font-weight: 700; color: #78350F; letter-spacing: .06em; margin-bottom: 10px; display: block; font-family: var(--dev);">
                सेवा क्षेत्र (किन प्रकल्पों में सेवा देना चाहते हैं? चुनें)
              </label>
              
              <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px; background: #FAF4ED; border: 1px solid rgba(180,83,9,0.2); border-radius: 14px; padding: 18px;">
                <label style="display: flex; align-items: center; gap: 10px; font-size: 14px; color: #2B1A12; font-family: var(--dev); cursor: pointer;">
                  <input type="checkbox" name="seva_areas[]" value="गौशाला गौसेवा व संरक्षण" checked style="accent-color: #D95D0F; width: 16px; height: 16px;">
                  <span>गौशाला गौसेवा व संरक्षण</span>
                </label>
                <label style="display: flex; align-items: center; gap: 10px; font-size: 14px; color: #2B1A12; font-family: var(--dev); cursor: pointer;">
                  <input type="checkbox" name="seva_areas[]" value="अन्नक्षेत्र एवं भंडारा सेवा" checked style="accent-color: #D95D0F; width: 16px; height: 16px;">
                  <span>अन्नक्षेत्र एवं भंडारा सेवा</span>
                </label>
                <label style="display: flex; align-items: center; gap: 10px; font-size: 14px; color: #2B1A12; font-family: var(--dev); cursor: pointer;">
                  <input type="checkbox" name="seva_areas[]" value="वैदिक गुरुकुल एवं बाल शिक्षा" style="accent-color: #D95D0F; width: 16px; height: 16px;">
                  <span>वैदिक गुरुकुल एवं बाल शिक्षा</span>
                </label>
                <label style="display: flex; align-items: center; gap: 10px; font-size: 14px; color: #2B1A12; font-family: var(--dev); cursor: pointer;">
                  <input type="checkbox" name="seva_areas[]" value="तीर्थ परिक्रमा मार्ग स्वच्छता व पर्यावरण" style="accent-color: #D95D0F; width: 16px; height: 16px;">
                  <span>परिक्रमा मार्ग व मंदाकिनी स्वच्छता</span>
                </label>
                <label style="display: flex; align-items: center; gap: 10px; font-size: 14px; color: #2B1A12; font-family: var(--dev); cursor: pointer;">
                  <input type="checkbox" name="seva_areas[]" value="उत्सव एवं मेला प्रबंधन" style="accent-color: #D95D0F; width: 16px; height: 16px;">
                  <span>उत्सव एवं मेला प्रबंधन</span>
                </label>
                <label style="display: flex; align-items: center; gap: 10px; font-size: 14px; color: #2B1A12; font-family: var(--dev); cursor: pointer;">
                  <input type="checkbox" name="seva_areas[]" value="डिजिटल, मीडिया एवं प्रचार-प्रसार" style="accent-color: #D95D0F; width: 16px; height: 16px;">
                  <span>डिजिटल मीडिया एवं लेखन</span>
                </label>
              </div>
            </div>

            <div style="margin-bottom: 24px;">
              <label class="form-label" style="font-size: 12px; font-weight: 700; color: #78350F; letter-spacing: .06em; margin-bottom: 8px; display: block; font-family: var(--dev);">
                समय उपलब्धता (Availability)
              </label>
              <select name="availability" class="form-select" style="padding: 12px 16px; border: 1.5px solid rgba(180,83,9,0.22); border-radius: 12px; width: 100%; font-size: 14.5px; background: #FAF4ED; color: #2B1A12; font-family: var(--dev); box-sizing: border-box;">
                <option value="सप्ताहांत (शनिवार / रविवार)">सप्ताहांत (शनिवार / रविवार)</option>
                <option value="माह में 3 से 7 दिन">माह में 3 से 7 दिन</option>
                <option value="प्रमुख पर्वों एवं मेलों में (रामनवमी, दीपोत्सव)">प्रमुख पर्वों एवं मेलों में (रामनवमी, दीपोत्सव)</option>
                <option value="नियमित ऑनलाइन / रिमोट सेवा">नियमित ऑनलाइन / रिमोट सेवा</option>
                <option value="पूर्णकालिक समर्पित सेवादार (चित्रकूट आश्रम)">पूर्णकालिक समर्पित सेवादार (चित्रकूट आश्रम)</option>
              </select>
            </div>

            <div style="margin-bottom: 28px;">
              <label class="form-label" style="font-size: 12px; font-weight: 700; color: #78350F; letter-spacing: .06em; margin-bottom: 8px; display: block; font-family: var(--dev);">
                पूर्व अनुभव या विशेष कौशल (वैकल्पिक)
              </label>
              <textarea name="experience_notes" rows="3" placeholder="अपने सेवा अनुभव, संस्कृत ज्ञान, कंप्यूटर कौशल या अन्य विशेषता के बारे में लिखें..." class="form-textarea" style="padding: 12px 16px; border: 1.5px solid rgba(180,83,9,0.22); border-radius: 12px; width: 100%; font-size: 14.5px; background: #FAF4ED; color: #2B1A12; font-family: var(--dev); box-sizing: border-box;">{{ old('experience_notes') }}</textarea>
            </div>

            <button type="submit" class="btn primary" style="width: 100%; justify-content: center; padding: 15px; font-size: 15px; font-weight: 700; border-radius: 9999px; background: linear-gradient(135deg, #D95D0F 0%, #B45309 100%); color: #FFFDFC; border: none; cursor: pointer; box-shadow: 0 10px 24px rgba(217,93,15,0.28); transition: all .3s ease; white-space: nowrap;">
              Submit Volunteer Application &rarr;
            </button>
          </form>

        </div>
      @endif

    </div>
  </section>

@endsection
