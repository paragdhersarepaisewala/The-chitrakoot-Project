@extends('layouts.app')

@section('title', 'ट्रस्ट के बारे में | श्री कामदगिरि पीठम् सेवा ट्रस्ट')

@section('content')

  <section class="section" style="padding-top: 150px; background: var(--paper);">
    <div class="container">
      <div class="section-head reveal">
        <div class="eyebrow">About Our Trust</div>
        <h2>श्री कामदगिरि पीठम् सेवा ट्रस्ट<br>चित्रकूट धाम</h2>
        <p>सनातन धर्म, शिक्षा, गौ सेवा, पर्यावरण संरक्षण और समाज कल्याण हेतु समर्पित एक पावन संस्था।</p>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; margin-bottom: 80px;">
        <div class="reveal">
          <h3 style="font: 500 28px var(--dev); color: var(--brown); margin-bottom: 20px;">हमारा विज़न (Vision) & मिशन (Mission)</h3>
          <p style="font: 400 15px/1.9 var(--dev); color: rgba(45,36,30,.8);">
            चित्रकूट की पावन तपोभूमि पर स्थित श्री कामदगिरि पीठम् सेवा ट्रस्ट का मूल उद्देश्य प्राचीन वैदिक संस्कृति एवं परम्पराओं को अक्षुण्ण रखते हुए आधुनिक युग में निस्वार्थ सेवा के माध्यम से समाज का सर्वांगीण विकास करना है।
          </p>
          <ul style="font: 400 14px/2 var(--dev); color: rgba(45,36,30,.8); padding-left: 20px; margin-top: 20px;">
            <li>गौवंश का संरक्षण, संवर्धन एवं सुव्यवस्थित गौशाला का संचालन।</li>
            <li>संस्कृत विद्यालय एवं गुरुकुल के माध्यम से बालकों को निःशुल्क वेद एवं शास्त्र शिक्षा।</li>
            <li>परिक्रमा मार्ग पर संतों, भक्तों एवं तीर्थयात्रियों हेतु नित्य अन्नक्षेत्र।</li>
            <li>पर्यावरण संरक्षण हेतु व्यापक वृक्षारोपण एवं मन्दाकिनी स्वच्छता अभियान।</li>
          </ul>
        </div>
        <div class="reveal delay-1">
          <img src="https://images.unsplash.com/photo-1582510003544-4d00b7f74220?auto=format&fit=crop&w=1000&q=85" alt="Peetham Heritage" style="border: 1px solid var(--line); box-shadow: 0 20px 50px rgba(0,0,0,.08);">
        </div>
      </div>
    </div>
  </section>

@endsection
