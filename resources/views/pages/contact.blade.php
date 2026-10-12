@extends('layouts.app')

@section('title', 'संपर्क करें | श्री कामदगिरि पीठम् सेवा ट्रस्ट')

@section('content')

  <section class="section" style="padding-top: 150px; background: var(--paper);">
    <div class="container" style="max-width: 900px;">
      <div class="section-head reveal">
        <div class="eyebrow">Contact & Office</div>
        <h2>संपर्क करें</h2>
        <p>चित्रकूट धाम स्थित ट्रस्ट कार्यालय का पता, फोन नंबर एवं संदेश प्रपत्र।</p>
      </div>

      @if(session('success'))
        <div style="background: #e8f5e9; color: #2e7d32; padding: 16px; margin-bottom: 25px; border-left: 4px solid #2e7d32; font: 500 14px var(--dev);">
          {{ session('success') }}
        </div>
      @endif

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
        <div class="reveal" style="background: var(--white); border: 1px solid var(--line); padding: 35px;">
          <h3 style="font: 500 22px var(--dev); color: var(--brown); margin-bottom: 20px;">ट्रस्ट कार्यालय पता</h3>
          <p style="font: 400 14px/1.8 var(--dev); color: rgba(45,36,30,.8);">
            <strong>श्री कामदगिरि पीठम् सेवा ट्रस्ट</strong><br>
            कामदगिरि परिक्रमा मार्ग, चित्रकूट धाम,<br>
            जिला सतना / बांदा, मध्य प्रदेश / उत्तर प्रदेश, भारत।
          </p>
          <p style="font: 500 14px var(--sans); margin-top: 20px; color: var(--brown);">
            📞 फोन: +91 94150 00000<br>
            ✉️ ईमेल: info@kamadgiripeetham.org
          </p>
        </div>

        <div class="reveal delay-1" style="background: var(--white); border: 1px solid var(--line); padding: 35px;">
          <form action="{{ url('/contact') }}" method="POST">
            @csrf
            <div style="margin-bottom: 20px;">
              <label style="display: block; font: 600 10px var(--sans); letter-spacing: .1em; text-transform: uppercase; margin-bottom: 8px;">आपका नाम (Full Name)</label>
              <input type="text" name="name" required style="width: 100%; padding: 12px; border: 1px solid var(--line); font: 500 13px var(--dev);">
            </div>
            <div style="margin-bottom: 20px;">
              <label style="display: block; font: 600 10px var(--sans); letter-spacing: .1em; text-transform: uppercase; margin-bottom: 8px;">ईमेल (Email Address)</label>
              <input type="email" name="email" required style="width: 100%; padding: 12px; border: 1px solid var(--line); font: 500 13px var(--dev);">
            </div>
            <div style="margin-bottom: 20px;">
              <label style="display: block; font: 600 10px var(--sans); letter-spacing: .1em; text-transform: uppercase; margin-bottom: 8px;">संदेश (Message)</label>
              <textarea name="message" rows="4" required style="width: 100%; padding: 12px; border: 1px solid var(--line); font: 500 13px var(--dev);"></textarea>
            </div>
            <button type="submit" class="btn primary" style="width: 100%; justify-content: center; font-size: 10px;">संदेश भेजें (Submit Message)</button>
          </form>
        </div>
      </div>

    </div>
  </section>

@endsection
