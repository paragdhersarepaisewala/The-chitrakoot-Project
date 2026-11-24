@extends('layouts.app')

@php
  $isKamadgiri = ($destination->slug === 'kamadgiri-parikrama');
  $pageTitle = $isKamadgiri ? 'Kamadgiri Parvat & Temple Parikrama Guide | चित्रकूट कामदगिरि धाम' : ($destination->name_hi . ' (' . $destination->name_en . ') | चित्रकूट धाम दर्शन');
  $metaDesc = $isKamadgiri ? 'Complete guide to Kamadgiri Parvat & Kamadgiri Temple in Chitrakoot. 5 km holy parikrama route, Kamtanath Mukharbindu darshan timings, history, map & rituals.' : ($destination->summary_hi ?? $destination->description_hi);
@endphp

@section('title', $pageTitle)
@section('meta_description', Str::limit(strip_tags($metaDesc), 160))

@section('content')

  <section class="section" style="padding-top: 150px; background: var(--paper, #FAF4ED); min-height: 85vh;">
    <div class="container" style="max-width: 900px;">

      <div class="reveal" style="margin-bottom: 30px;">
        <nav aria-label="Breadcrumb" style="font: 700 12.5px var(--sans); color: #B45309; margin-bottom: 16px;">
          <a href="{{ url('/') }}" style="color: inherit; text-decoration: none;">होम</a> &rsaquo;
          <a href="{{ url('/chitrakoot') }}" style="color: inherit; text-decoration: none;">तीर्थ दर्शन</a> &rsaquo;
          <span style="color: #2B1A12;">{{ $destination->name_hi }}</span>
        </nav>
        <h1 style="font: 700 clamp(36px, 5vw, 56px)/1.15 var(--dev); margin-top: 12px; color: #2B1A12;">
          {{ $destination->name_hi }}
        </h1>
        <p
          style="font: 700 13px var(--sans); letter-spacing: .12em; text-transform: uppercase; color: #B45309; margin-top: 6px;">
          {{ $destination->name_en }} &middot; {{ $isKamadgiri ? 'Kamadgiri Parvat & Temple' : 'Chitrakoot Dham' }}
        </p>
      </div>

      <div class="reveal" style="margin-bottom: 36px;">
        <img src="{{ $destination->featured_image }}" alt="{{ $isKamadgiri ? 'Kamadgiri Parvat and Kamadgiri Temple Chitrakoot' : $destination->name_en }}"
          style="max-height: 480px; width: 100%; object-fit: cover; border-radius: 20px; border: 1px solid rgba(180,83,9,0.18); box-shadow: 0 16px 40px rgba(130,70,20,0.08);">
      </div>

      @if($isKamadgiri)
        <!-- Key Highlights Grid for Kamadgiri -->
        <div class="reveal" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 36px;">
          <div style="background: #FFFDFC; border: 1px solid rgba(180,83,9,0.18); border-radius: 16px; padding: 18px 20px; text-align: center;">
            <span style="display: inline-flex; justify-content: center; margin-bottom: 6px; color: #B45309;"><x-icon name="mountain" size="24" /></span>
            <strong style="font-size: 14px; color: #2B1A12; display: block;">पवित्र पर्वत (Parvat)</strong>
            <span style="font-size: 12.5px; color: #7A4B29;">Kamadgiri Parvat</span>
          </div>
          <div style="background: #FFFDFC; border: 1px solid rgba(180,83,9,0.18); border-radius: 16px; padding: 18px 20px; text-align: center;">
            <span style="display: inline-flex; justify-content: center; margin-bottom: 6px; color: #B45309;"><x-icon name="landmark" size="24" /></span>
            <strong style="font-size: 14px; color: #2B1A12; display: block;">प्रधान मंदिर (Temple)</strong>
            <span style="font-size: 12.5px; color: #7A4B29;">Kamadgiri Temple (कामतानाथ)</span>
          </div>
          <div style="background: #FFFDFC; border: 1px solid rgba(180,83,9,0.18); border-radius: 16px; padding: 18px 20px; text-align: center;">
            <span style="display: inline-flex; justify-content: center; margin-bottom: 6px; color: #B45309;"><x-icon name="map-pin" size="24" /></span>
            <strong style="font-size: 14px; color: #2B1A12; display: block;">परिक्रमा दूरी (Parikrama)</strong>
            <span style="font-size: 12.5px; color: #7A4B29;">5 Kilometers (५ किमी)</span>
          </div>
          <div style="background: #FFFDFC; border: 1px solid rgba(180,83,9,0.18); border-radius: 16px; padding: 18px 20px; text-align: center;">
            <span style="display: inline-flex; justify-content: center; margin-bottom: 6px; color: #B45309;"><x-icon name="building" size="24" /></span>
            <strong style="font-size: 14px; color: #2B1A12; display: block;">तीर्थ प्रवास (Stay)</strong>
            <a href="{{ url('/services/atithi-vishram') }}" style="font-size: 12.5px; color: #D95D0F; font-weight: 700; text-decoration: none;">Kamadgiri Stay &rarr;</a>
          </div>
        </div>
      @endif

      <div class="reveal"
        style="background: #FFFDFC; border: 1px solid rgba(180,83,9,0.18); border-radius: 20px; padding: clamp(24px, 4vw, 40px); box-shadow: 0 10px 30px rgba(130,70,20,0.05); font: 400 16px/2 var(--dev); color: #7A4B29; margin-bottom: 40px;">
        <h2 style="font: 700 24px var(--dev); color: #2B1A12; margin: 0 0 16px;">
          {{ $isKamadgiri ? 'Kamadgiri Parvat & Temple पौराणिक एवं आध्यात्मिक महात्म्य' : 'पौराणिक एवं धार्मिक महत्त्व' }}
        </h2>
        <p style="margin: 0 0 16px;">{{ $destination->description_hi }}</p>
        @if($isKamadgiri)
          <p style="margin: 0 0 16px;">
            कामदगिरि पर्वत (Kamadgiri Parvat) को भगवान श्री कामतानाथ जी का साक्षात् विग्रह माना जाता है। संस्कृत में 'कामद' का अर्थ है समस्त मनोकामनाओं की पूर्ति करने वाला। त्रेतायुग में मर्यादा पुरुषोत्तम भगवान श्रीराम, माता सीता एवं भ्राता लक्ष्मण ने अपने 14 वर्षों के वनवास काल में से साढ़े ग्यारह वर्ष इसी पर्वत के पावन सानिध्य में व्यतीत किए थे।
          </p>
          <p style="margin: 0;">
            कामदगिरि मंदिर (Kamadgiri Temple) के चार प्रमुख मुखारविंद हैं — प्रथम मुखारविंद, द्वितीय मुखारविंद, तृतीय मुखारविंद एवं चतुर्थ मुखारविंद। श्रद्धालु नित्य प्रातः एवं सायं इस 5 किलोमीटर के पावन परिक्रमा मार्ग में श्रद्धापूर्वक पदयात्रा कर अपने मनोरथों की सिद्धि की प्रार्थना करते हैं।
          </p>
        @endif
      </div>

      @if($isKamadgiri)
        <!-- FAQ Section: Kamadgiri Parvat & Kamadgiri Temple -->
        <div class="reveal" style="background: #FFFDFC; border: 1.5px solid rgba(180,83,9,0.18); border-radius: 20px; padding: clamp(24px, 4vw, 36px); box-shadow: 0 8px 30px rgba(180,83,9,0.05); margin-bottom: 50px;">
          <div style="text-align: center; margin-bottom: 24px;">
            <span style="font-size: 11px; font-weight: 800; color: #B45309; text-transform: uppercase; letter-spacing: 1.5px;">तीर्थ मार्गदर्शिका (Pilgrim Guide)</span>
            <h2 style="font: 700 22px var(--dev); color: #2B1A12; margin: 6px 0 0;">Kamadgiri Parvat & Temple FAQs</h2>
          </div>

          <div style="display: flex; flex-direction: column; gap: 14px;">
            <details style="background: #FAF6EE; border: 1px solid rgba(180,83,9,0.15); border-radius: 12px; padding: 14px 18px; cursor: pointer;" open>
              <summary style="font-weight: 700; font-size: 15px; color: #2B1A12;">What is the significance of Kamadgiri Parvat in Chitrakoot?</summary>
              <p style="font-size: 14px; line-height: 1.7; color: #7A4B29; margin: 10px 0 0;">
                Kamadgiri Parvat is the holy hill regarded as the physical embodiment of Lord Kamtanath. Circumambulating (Parikrama) the 5 km path around the hill is believed to fulfill all spiritual and worldly aspirations.
              </p>
            </details>

            <details style="background: #FAF6EE; border: 1px solid rgba(180,83,9,0.15); border-radius: 12px; padding: 14px 18px; cursor: pointer;">
              <summary style="font-weight: 700; font-size: 15px; color: #2B1A12;">What are the daily timings for Kamadgiri Temple darshan and aarti?</summary>
              <p style="font-size: 14px; line-height: 1.7; color: #7A4B29; margin: 10px 0 0;">
                The Kamadgiri Temple is open throughout the day for parikrama pilgrims. Mangala aarti begins at 05:00 AM, Bhog aarti at 12:00 PM, and Sandhya Maha Aarti at 07:00 PM.
              </p>
            </details>

            <details style="background: #FAF6EE; border: 1px solid rgba(180,83,9,0.15); border-radius: 12px; padding: 14px 18px; cursor: pointer;">
              <summary style="font-weight: 700; font-size: 15px; color: #2B1A12;">Where can pilgrims find clean stay near Kamadgiri Parvat?</summary>
              <p style="font-size: 14px; line-height: 1.7; color: #7A4B29; margin: 10px 0 0;">
                Pilgrims can book clean, peaceful, and family-friendly Kamadgiri stay at <strong>Atithi Vishram Griha</strong> run by Shri Kamadgiri Peetham Sewa Trust, located directly on Kamadgiri Parikrama Marg.
              </p>
            </details>
          </div>
        </div>
      @endif

    </div>
  </section>

@endsection

@if($isKamadgiri)
  @section('structured_data')
    @php
      $kamadgiriSchema = [
        '@context' => 'https://schema.org',
        '@graph' => [
          [
            '@type' => ['PlaceOfWorship', 'TouristAttraction'],
            '@id' => url('/chitrakoot/kamadgiri-parikrama#temple'),
            'name' => 'Kamadgiri Temple & Sacred Parvat',
            'alternateName' => ['कामदगिरि पर्वत', 'कामदगिरि मंदिर', 'श्री कामतानाथ मंदिर', 'Kamtanath Temple Chitrakoot'],
            'description' => 'The divine wish-fulfilling Kamadgiri Parvat and ancient Kamadgiri Temple with a 5 km circumambulation path in Chitrakoot Dham.',
            'url' => url('/chitrakoot/kamadgiri-parikrama'),
            'image' => $destination->featured_image,
            'address' => [
              '@type' => 'PostalAddress',
              'streetAddress' => 'Kamadgiri Parikrama Marg',
              'addressLocality' => 'Chitrakoot Dham',
              'addressCountry' => 'IN'
            ],
          ],
          [
            '@type' => 'FAQPage',
            '@id' => url('/chitrakoot/kamadgiri-parikrama#faq'),
            'mainEntity' => [
              [
                '@type' => 'Question',
                'name' => 'What is the significance of Kamadgiri Parvat in Chitrakoot?',
                'acceptedAnswer' => [
                  '@type' => 'Answer',
                  'text' => 'Kamadgiri Parvat is the holy hill regarded as the physical embodiment of Lord Kamtanath. Circumambulating the 5 km path around the hill is believed to fulfill all spiritual and worldly aspirations.'
                ]
              ],
              [
                '@type' => 'Question',
                'name' => 'What are the daily timings for Kamadgiri Temple darshan and aarti?',
                'acceptedAnswer' => [
                  '@type' => 'Answer',
                  'text' => 'The Kamadgiri Temple is open throughout the day for parikrama pilgrims. Mangala aarti begins at 05:00 AM, Bhog aarti at 12:00 PM, and Sandhya Maha Aarti at 07:00 PM.'
                ]
              ],
              [
                '@type' => 'Question',
                'name' => 'Where can pilgrims find clean stay near Kamadgiri Parvat?',
                'acceptedAnswer' => [
                  '@type' => 'Answer',
                  'text' => 'Pilgrims can book clean, peaceful, and family-friendly Kamadgiri stay at Atithi Vishram Griha run by Shri Kamadgiri Peetham Sewa Trust, located directly on Kamadgiri Parikrama Marg.'
                ]
              ]
            ]
          ]
        ]
      ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($kamadgiriSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
  @endsection
@endif