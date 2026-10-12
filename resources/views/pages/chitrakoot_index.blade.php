@extends('layouts.app')

@section('title', 'चित्रकूट धाम दर्शन | श्री कामदगिरि पीठम् सेवा ट्रस्ट')

@section('content')

  <section class="section" style="padding-top: 150px; background: var(--paper);">
    <div class="container">
      <div class="section-head reveal">
        <div class="eyebrow">Chitrakoot Sacred Places</div>
        <h2>चित्रकूट धाम — पावन तीर्थ एवं दर्शनीय स्थल</h2>
        <p>प्रभु श्री राम की तपोभूमि चित्रकूट के पवित्र पर्वत, नदी तट एवं ऐतिहासिक आश्रमों की विस्तृत जानकारी।</p>
      </div>

      <div class="places" style="grid-template-columns: repeat(3, 1fr); gap: 25px;">
        @foreach($destinations as $dest)
          <a class="place" href="{{ url('/chitrakoot/' . $dest->slug) }}" style="border: 1px solid var(--line);">
            <img src="{{ $dest->featured_image }}" alt="{{ $dest->name_en }}" style="height: 220px;">
            <div class="place-copy" style="padding: 20px;">
              <b style="font-size: 20px; color: var(--brown);">{{ $dest->name_hi }}</b>
              <span style="font-size: 9px; margin-top: 4px; color: rgba(45,36,30,.6);">{{ $dest->name_en }}</span>
              <p style="font: 400 13px/1.7 var(--dev); margin-top: 10px; color: rgba(45,36,30,.75);">{{ $dest->tagline_hi }}</p>
            </div>
          </a>
        @endforeach
      </div>
    </div>
  </section>

@endsection
