@extends('layouts.app')

@section('title', 'संपर्क करें | श्री कामदगिरि पीठम् सेवा ट्रस्ट')

@section('content')

  <section class="section" style="padding-top: 150px; background: var(--paper, #FAF4ED); min-height: 85vh;">
    <div class="container" style="max-width: 960px;">
      <div class="section-head reveal" style="text-align: center; margin-bottom: 45px;">
        <h1 style="font: 700 clamp(34px, 4.5vw, 54px)/1.15 var(--dev); color: #2B1A12; margin-top: 14px;">संपर्क करें एवं
          ट्रस्ट कार्यालय</h1>
        <p style="color: #7A4B29; font-size: 16px; margin: 10px auto 0; max-width: 600px;">चित्रकूट धाम स्थित ट्रस्ट
          कार्यालय का पता, फोन नंबर एवं पावन संदेश प्रपत्र।</p>
      </div>

      @if(session('success'))
        <div
          style="background: #e8f5e9; color: #2e7d32; padding: 16px 20px; margin-bottom: 30px; border-radius: 12px; border-left: 4px solid #2e7d32; font: 600 14px var(--dev);">
          {{ session('success') }}
        </div>
      @endif

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 32px;">
        <div class="reveal"
          style="background: #FFFDFC; border: 1px solid rgba(180,83,9,0.18); border-radius: 20px; padding: 36px; box-shadow: 0 10px 30px rgba(130,70,20,0.05);">
          <h2 style="font: 700 22px var(--dev); color: #2B1A12; margin: 0 0 16px;">ट्रस्ट कार्यालय पता</h2>
          <p style="font: 400 15px/1.85 var(--dev); color: #7A4B29; margin-bottom: 24px;">
            <strong
              style="color: #2B1A12; font-size: 16px;">{{ $orgSettings->org_name ?? 'श्री कामदगिरि पीठम् सेवा ट्रस्ट' }}</strong><br>
            {!! nl2br(e($orgSettings->address ?? 'कामदगिरि परिक्रमा मार्ग, चित्रकूट धाम, मध्य प्रदेश / उत्तर प्रदेश, भारत।')) !!}
          </p>
          <div
            style="background: #FAF4ED; border: 1px solid rgba(180,83,9,0.16); border-radius: 14px; padding: 18px; color: #7A4B29; font: 500 14px var(--sans); line-height: 1.8;">
            <div style="margin-bottom: 8px; display: flex; align-items: center; gap: 8px;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#D95D0F" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path
                  d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
              </svg>
              <span><strong>फोन:</strong> <a
                  href="tel:{{ preg_replace('/[^0-9+]/', '', $orgSettings->phone ?? '+919415000000') }}"
                  style="color: inherit; text-decoration: none;">{{ $orgSettings->phone ?? '+91 94150 00000' }}</a></span>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#D95D0F" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <rect width="20" height="16" x="2" y="4" rx="2" />
                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
              </svg>
              <span><strong>ईमेल:</strong> <a href="mailto:{{ $orgSettings->email ?? 'info@kamadgiripeetham.org' }}"
                  style="color: inherit; text-decoration: none;">{{ $orgSettings->email ?? 'info@kamadgiripeetham.org'
                  }}</a></span>
            </div>
          </div>
        </div>

        <div class="reveal delay-1"
          style="background: #FFFDFC; border: 1px solid rgba(180,83,9,0.18); border-radius: 20px; padding: 36px; box-shadow: 0 10px 30px rgba(130,70,20,0.05);">
          <form action="{{ url('/contact') }}" method="POST">
            @csrf
            <div style="margin-bottom: 16px;">
              <label
                style="display: block; font: 700 11px var(--sans); letter-spacing: .12em; text-transform: uppercase; margin-bottom: 8px; color: #B45309;">आपका
                नाम (Full Name) *</label>
              <input type="text" name="name" value="{{ auth()->user()?->name }}" required
                style="width: 100%; padding: 13px 16px; border: 1.5px solid rgba(180,83,9,0.22); border-radius: 12px; font: 500 14px var(--dev); background: #FFFDFC; box-sizing: border-box; color: #2B1A12;">
            </div>
            <div
              style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; margin-bottom: 16px;">
              <div>
                <label
                  style="display: block; font: 700 11px var(--sans); letter-spacing: .12em; text-transform: uppercase; margin-bottom: 8px; color: #B45309;">ईमेल
                  (Email) *</label>
                <input type="email" name="email" value="{{ auth()->user()?->email }}" required
                  style="width: 100%; padding: 13px 16px; border: 1.5px solid rgba(180,83,9,0.22); border-radius: 12px; font: 500 14px var(--sans); background: #FFFDFC; box-sizing: border-box; color: #2B1A12;">
              </div>
              <div>
                <label
                  style="display: block; font: 700 11px var(--sans); letter-spacing: .12em; text-transform: uppercase; margin-bottom: 8px; color: #B45309;">दूरभाष
                  (Phone)</label>
                <input type="text" name="phone"
                  style="width: 100%; padding: 13px 16px; border: 1.5px solid rgba(180,83,9,0.22); border-radius: 12px; font: 500 14px var(--sans); background: #FFFDFC; box-sizing: border-box; color: #2B1A12;"
                  placeholder="+91 ...">
              </div>
            </div>
            <div style="margin-bottom: 22px;">
              <label
                style="display: block; font: 700 11px var(--sans); letter-spacing: .12em; text-transform: uppercase; margin-bottom: 8px; color: #B45309;">संदेश
                (Message) *</label>
              <textarea name="message" rows="4" required
                style="width: 100%; padding: 13px 16px; border: 1.5px solid rgba(180,83,9,0.22); border-radius: 12px; font: 400 15px/1.8 var(--dev); background: #FFFDFC; box-sizing: border-box; color: #2B1A12;"></textarea>
            </div>
            <button type="submit" class="btn primary"
              style="width: 100%; justify-content: center; font-size: 13px; font-weight: 800; border-radius: 9999px; padding: 14px; white-space: nowrap;">Submit
              Message &rarr;</button>
          </form>
        </div>
      </div>

    </div>
  </section>

@endsection