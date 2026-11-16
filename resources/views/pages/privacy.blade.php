@extends('layouts.app')

@section('title', 'गोपनीयता नीति (Privacy Policy) | श्री कामदगिरि पीठम् सेवा ट्रस्ट')
@section('meta_description', 'श्री कामदगिरि पीठम् सेवा ट्रस्ट, चित्रकूट धाम की गोपनीयता नीति — दानदाता विवरण, व्यक्तिगत सुरक्षा, Razorpay डेटा संरक्षण और कुकीज दिशानिर्देश।')

@section('content')

  <section class="section" style="padding-top: 150px; background: var(--paper); min-height: 85vh;">
    <div class="container" style="max-width: 900px;">
      
      <!-- Breadcrumbs -->
      <nav aria-label="Breadcrumb" style="margin-bottom: 24px; font: 600 11px var(--sans); letter-spacing: .08em; text-transform: uppercase;">
        <a href="{{ url('/') }}" style="color: var(--sand); text-decoration: none;">होम (Home)</a>
        <span style="margin: 0 8px; color: rgba(43,35,29,0.4);">&rarr;</span>
        <span style="color: var(--brown);">गोपनीयता नीति (Privacy Policy)</span>
      </nav>

      <div class="section-head reveal" style="text-align: left; margin-bottom: 35px;">
        <div class="eyebrow">Trust Privacy & Data Protection</div>
        <h1 style="font: 500 clamp(32px, 4vw, 48px)/1.2 var(--dev); color: var(--deep); margin-top: 10px;">
          गोपनीयता नीति (Privacy Policy)
        </h1>
        <p style="font: 400 14px/1.8 var(--dev); color: rgba(43,35,29,0.7); margin-top: 8px;">
          अंतिम अद्यतन: {{ date('F d, Y') }} &middot; श्री कामदगिरि पीठम् सेवा ट्रस्ट, चित्रकूट धाम
        </p>
      </div>

      <article class="reveal" style="background: #FFFDFC; border: 1.5px solid rgba(180,83,9,0.18); padding: clamp(24px, 4vw, 44px); border-radius: 20px; box-shadow: 0 14px 40px rgba(130,70,20,0.06); font: 400 15px/1.9 var(--dev); color: #2B1A12;">
        
        @if(!empty($orgSettings->privacy_policy_content))
          {!! $orgSettings->privacy_policy_content !!}
        @else
          <h2 style="font: 700 22px var(--dev); color: #2B1A12; margin: 0 0 12px;">१. परिचय (Introduction)</h2>
          <p>
            {{ $orgSettings->org_name ?? 'श्री कामदगिरि पीठम् सेवा ट्रस्ट' }} (चित्रकूट धाम) आपकी गोपनीयता का पूर्ण सम्मान करता है। यह नीति यह स्पष्ट करती है कि हमारी वेबसाइट (वेब पोर्टल) पर आपके द्वारा प्रदान की गई व्यक्तिगत जानकारी का संग्रहण, उपयोग एवं संरक्षण किस प्रकार किया जाता है।
          </p>

          <h2 style="font: 700 22px var(--dev); color: #2B1A12; margin: 28px 0 12px;">२. एकत्रित की जाने वाली जानकारी (Information We Collect)</h2>
          <p>
            जब आप हमारी वेबसाइट पर दान करते हैं, संपर्क फ़ॉर्म भरते हैं अथवा सदस्यता हेतु पंजीकरण करते हैं, तो हम निम्नलिखित विवरण प्राप्त कर सकते हैं:
          </p>
          <ul style="padding-left: 24px; margin-top: 8px;">
            <li>नाम एवं संपर्क विवरण (ईमेल पता, दूरभाष संख्या)।</li>
            <li>पत्राचार का पता (यदि {{ ($orgSettings->is_80g_enabled ?? false) ? '80G दान रसीद' : 'दान रसीद' }} की भौतिक प्रति हेतु आवश्यक हो)।</li>
            @if($orgSettings->is_80g_enabled ?? false)
              <li>आयकर 80G छूट रसीद जनरेशन हेतु पैन (PAN) नंबर (वैकल्पिक/आवश्यकतानुसार)।</li>
            @endif
            <li>तकनीकी डेटा जैसे ब्राउज़र प्रकार, आईपी पता और पृष्ठ दृश्यावलोकन (Google Analytics 4 के माध्यम से अनामित रूप में)।</li>
          </ul>

          <h2 style="font: 700 22px var(--dev); color: #2B1A12; margin: 28px 0 12px;">३. Razorpay ऑनलाइन भुगतान एवं सुरक्षा (Payment Gateway Security)</h2>
          <p>
            हम किसी भी दानदाता के डेबिट कार्ड, क्रेडिट कार्ड नंबर अथवा बैंक खाता विवरण को अपने सर्वर पर कभी भी संचित (Store) नहीं करते हैं। सभी ऑनलाइन वित्तीय लेन-देन भारतीय रिजर्व बैंक (RBI) द्वारा अधिकृत <strong>Razorpay Payment Gateway</strong> के 256-Bit SSL एन्क्रिप्टेड सिक्योर चैनल के माध्यम से प्रत्यक्ष रूप से संसाधित होते हैं।
          </p>

          <h2 style="font: 700 22px var(--dev); color: #2B1A12; margin: 28px 0 12px;">४. जानकारी का उपयोग (How We Use Your Data)</h2>
          <p>
            एकत्रित की गई जानकारी का उपयोग केवल निम्नलिखित पवित्र उद्देश्यों हेतु किया जाता है:
          </p>
          <ul style="padding-left: 24px; margin-top: 8px;">
            <li>दान रसीद जनरेट करने और डिजिटल रसीद ईमेल प्रेषित करने हेतु।</li>
            <li>गौसेवा, अन्नक्षेत्र और गुरुकुल सेवा प्रकल्पों की प्रगति सूचना साझा करने हेतु।</li>
            <li>उपयोगकर्ता के अनुरोध पर संपर्क संदेशों का उत्तर देने हेतु।</li>
            <li>वेबसाइट के प्रदर्शन और सुरक्षा में निरंतर सुधार करने हेतु।</li>
          </ul>

          <h2 style="font: 700 22px var(--dev); color: #2B1A12; margin: 28px 0 12px;">५. विज्ञापनों से पूर्ण मुक्ति (No Commercial Advertising)</h2>
          <p>
            {{ $orgSettings->org_name ?? 'श्री कामदगिरि पीठम् सेवा ट्रस्ट' }} एक पूर्णतः धार्मिक, सांस्कृतिक एवं धर्मार्थ ट्रस्ट है। हमारी वेबसाइट पर Google AdSense अथवा किसी भी प्रकार के तृतीय-पक्ष वाणिज्यिक विज्ञापनों का पूर्णतः निषेध है। आपकी जानकारी किसी भी विपणन एजेंसी को न तो बेची जाती है और न ही साझा की जाती है।
          </p>

          <h2 style="font: 700 22px var(--dev); color: #2B1A12; margin: 28px 0 12px;">६. संपर्क एवं शिकायत निवारण (Contact Us)</h2>
          <p>
            यदि इस गोपनीयता नीति अथवा आपके डेटा से संबंधित कोई प्रश्न या सुझाव हो, तो कृपया ट्रस्ट कार्यालय से संपर्क करें:
          </p>
          <div style="background: #FAF4ED; border: 1px solid rgba(180,83,9,0.2); border-left: 4px solid #D95D0F; padding: 18px 22px; margin-top: 14px; border-radius: 14px; font-family: var(--sans); font-size: 13.5px; color: #2B1A12;">
            <strong style="color: #78350F;">{{ $orgSettings->org_name ?? 'श्री कामदगिरि पीठम् सेवा ट्रस्ट' }}</strong><br>
            {{ $orgSettings->address ?? 'कामदगिरि परिक्रमा मार्ग, चित्रकूट धाम, मध्य प्रदेश / उत्तर प्रदेश, भारत' }}<br>
            ईमेल: <a href="mailto:{{ $orgSettings->email ?? 'info@kamadgiripeetham.org' }}" style="color: #B45309; font-weight: 600;">{{ $orgSettings->email ?? 'info@kamadgiripeetham.org' }}</a><br>
            फोन: {{ $orgSettings->phone ?? '+91 94150 00000' }}
          </div>
        @endif

      </article>

    </div>
  </section>

@endsection
