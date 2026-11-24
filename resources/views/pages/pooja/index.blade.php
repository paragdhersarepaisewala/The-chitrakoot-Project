@extends('layouts.app')

@section('title', 'ऑनलाइन पूजा, अर्चना एवं संकल्प सेवा | ' . ($orgSettings->org_name ?? 'श्री कामदगिरि पीठम् सेवा ट्रस्ट'))
@section('meta_description', 'चित्रकूट कामतानाथ जी की पावन परिक्रमा संकल्प, एकादश रुद्राभिषेक, मंदाकिनी दीपदान एवं गौ-पूजन हेतु घर बैठे ऑनलाइन संकल्प बुक करें।')

@section('content')

  <!-- Hero Header -->
  <section class="section"
    style="padding-top: 150px; padding-bottom: 70px; background: linear-gradient(180deg, #FAF4ED 0%, #FBF7F0 100%); color: #2B1A12; position: relative; overflow: hidden; border-bottom: 1px solid rgba(180, 83, 9, 0.15);">
    <div
      style="position: absolute; inset: 0; background-image: url('{{ !empty($homeSettings['pooja_banner_url']) ? $homeSettings['pooja_banner_url'] : asset('uploads/temple-test-photo-1792032209.webp') }}'); background-position: center; background-size: cover; opacity: 0.08; filter: blur(3px);">
    </div>
    <div class="container" style="position: relative; z-index: 2; text-align: center; max-width: 860px;">
      <h1 style="font: 700 clamp(32px, 4.5vw, 56px) var(--dev); color: #2B1A12; margin: 16px 0 18px;">
        ऑनलाइन पूजा, अर्चना एवं पावन संकल्प सेवा
      </h1>
      <p style="font: 400 17px/1.8 var(--dev); color: #7A4B29; margin: 0 auto 30px;">
        चित्रकूट धाम के परम पावन तीर्थों पर वेदोक्त ब्राह्मणों द्वारा सविधि नाम-गोत्र से संकल्प, पूजन एवं अभिषेक अनुष्ठान।
      </p>
    </div>
  </section>

  <!-- Pooja Cards Grid -->
  <section class="section" style="background: var(--paper, #FAF4ED); padding: 80px 0;">
    <div class="container">

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 32px;">
        @foreach($poojas as $pooja)
          <div class="reveal"
            style="background: #FFFDFC; border: 1px solid rgba(180, 83, 9, 0.18); border-radius: 20px; overflow: hidden; box-shadow: 0 8px 30px rgba(130, 70, 20, 0.05); display: flex; flex-direction: column; transition: transform .3s ease, box-shadow .3s ease;">

            <div style="position: relative; height: 220px; overflow: hidden;">
              <img src="{{ $pooja->featured_image ?: asset('uploads/temple-test-photo-1792032209.webp') }}"
                alt="{{ $pooja->title_hi }}" style="width: 100%; height: 100%; object-fit: cover;">
              <div
                style="position: absolute; top: 14px; right: 14px; background: rgba(255, 253, 252, 0.95); backdrop-filter: blur(8px); border: 1px solid rgba(180, 83, 9, 0.25); color: #B45309; padding: 5px 12px; border-radius: 9999px; font-size: 11px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; box-shadow: 0 2px 6px rgba(0,0,0,0.05);">
                {{ $pooja->duration_minutes }} Min अनुष्ठान
              </div>
            </div>

            <div style="padding: 24px; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
              <div>
                <h2 style="font: 700 20px/1.3 var(--dev); color: #2B1A12; margin: 0 0 10px;">
                  <a href="{{ route('pooja.show', $pooja->slug) }}"
                    style="color: inherit; text-decoration: none;">{{ $pooja->title_hi }}</a>
                </h2>

                <p style="font: 400 14px/1.7 var(--dev); color: #7A4B29; margin-bottom: 16px;">
                  {{ $pooja->summary_hi }}
                </p>

                <div
                  style="background: #FAF4ED; border: 1px solid rgba(180,83,9,0.16); border-radius: 12px; padding: 10px 14px; margin-bottom: 18px; font-size: 12px; color: #7A4B29; display: flex; align-items: center; gap: 8px;">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#D95D0F" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12 6 12 12 16 14" />
                  </svg>
                  <span>समय: <strong>{{ $pooja->timings_display }}</strong></span>
                </div>

                @if(!empty($pooja->benefits_hi))
                  <div style="margin-bottom: 20px;">
                    <div
                      style="font-size: 11px; font-weight: 800; color: #D95D0F; letter-spacing: .08em; text-transform: uppercase; margin-bottom: 6px;">
                      अनुष्ठान फल व लाभ:</div>
                    <ul
                      style="margin: 0; padding-left: 18px; font-size: 13px; line-height: 1.6; color: #7A4B29; font-family: var(--dev);">
                      @foreach(array_slice($pooja->benefits_hi, 0, 3) as $benefit)
                        <li>{{ $benefit }}</li>
                      @endforeach
                    </ul>
                  </div>
                @endif
              </div>

              <div
                style="border-top: 1px solid rgba(180, 83, 9, 0.16); padding-top: 16px; display: flex; align-items: center; justify-content: space-between;">
                <div>
                  <span style="font-size: 10px; color: #7A4B29; text-transform: uppercase; display: block;">प्रस्तावित
                    सहयोग</span>
                  <strong
                    style="font-size: 20px; font-weight: 800; color: #D95D0F; font-family: var(--sans);">₹{{ number_format($pooja->suggested_donation_amount, 0) }}</strong>
                </div>

                <a href="{{ route('pooja.show', $pooja->slug) }}" class="btn primary"
                  style="padding: 10px 22px; font-size: 12px; border-radius: 9999px;">
                  Book Now &rarr;
                </a>
              </div>
            </div>

          </div>
        @endforeach
      </div>

    </div>
  </section>

@endsection