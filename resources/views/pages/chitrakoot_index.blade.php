@extends('layouts.app')

@section('title', 'चित्रकूट धाम दर्शन | श्री कामदगिरि पीठम् सेवा ट्रस्ट')

@section('content')

  <section class="section" style="padding-top: 150px; background: var(--paper, #FAF4ED); min-height: 85vh;">
    <div class="container">
      <div class="section-head reveal" style="text-align: center; margin-bottom: 50px;">
        <h1 style="font: 700 clamp(36px, 4.5vw, 56px)/1.15 var(--dev); color: #2B1A12; margin: 16px 0 16px;">
          चित्रकूट धाम — पावन तीर्थ एवं दर्शनीय स्थल
        </h1>
        <p style="color: #7A4B29; font-size: 16px; line-height: 1.8; max-width: 680px; margin: 0 auto;">
          प्रभु श्री राम की तपोभूमि चित्रकूट के पवित्र पर्वत, नदी तट एवं ऐतिहासिक आश्रमों की विस्तृत जानकारी।
        </p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 32px;">
        @foreach($destinations as $dest)
          <a href="{{ url('/chitrakoot/' . $dest->slug) }}" class="reveal"
            style="background: #FFFDFC; border: 1px solid rgba(180,83,9,0.18); border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(130,70,20,0.05); display: flex; flex-direction: column; text-decoration: none; transition: transform .3s ease, box-shadow .3s ease;"
            onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 20px 40px rgba(130,70,20,0.12)';"
            onmouseout="this.style.transform='none'; this.style.boxShadow='0 10px 30px rgba(130,70,20,0.05)';">
            <div style="height: 230px; width: 100%; overflow: hidden; background: #FAF4ED;">
              <img src="{{ $dest->featured_image }}" alt="{{ $dest->name_en }}"
                style="width: 100%; height: 100%; object-fit: cover; transition: transform .5s ease;"
                onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
            </div>
            <div style="padding: 24px; flex: 1; display: flex; flex-direction: column;">
              <b
                style="font: 700 22px var(--dev); color: #2B1A12; display: block; margin-bottom: 4px;">{{ $dest->name_hi }}</b>
              <span
                style="font: 700 11px var(--sans); letter-spacing: .12em; text-transform: uppercase; color: #B45309; margin-bottom: 12px; display: block;">{{ $dest->name_en }}</span>
              <p style="font: 400 14px/1.7 var(--dev); color: #7A4B29; margin: 0 0 18px; flex: 1;">{{ $dest->tagline_hi }}
              </p>
              <div style="font: 700 12px var(--sans); color: #D95D0F; display: flex; align-items: center; gap: 6px;">
                View Details &rarr;
              </div>
            </div>
          </a>
        @endforeach
      </div>
    </div>
  </section>

@endsection