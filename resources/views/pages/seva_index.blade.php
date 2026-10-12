@extends('layouts.app')

@section('title', 'हमारी सेवाएँ एवं प्रकल्प | श्री कामदगिरि पीठम् सेवा ट्रस्ट')

@section('content')

  <section class="section" style="padding-top: 150px; background: var(--deep); color: #fff;">
    <div class="container">
      <div class="section-head reveal">
        <div class="eyebrow" style="color: var(--sand2);">Our Seva Projects</div>
        <h1 style="font: 500 clamp(40px, 5vw, 65px)/1.08 var(--dev); color: #fff;">धर्म, सेवा और संस्कृति का संकल्प</h1>
        <p style="color: rgba(255,255,255,.6);">श्री कामदगिरि पीठम् सेवा ट्रस्ट द्वारा संचालित पावन प्रकल्पों की विस्तृत सूची।</p>
      </div>

      <div class="seva-grid" style="grid-template-columns: repeat(3, 1fr); gap: 20px;">
        @foreach($sevas as $seva)
          <a class="seva-card reveal" href="{{ url('/seva/' . $seva->slug) }}">
            <img src="{{ $seva->featured_image }}" alt="{{ $seva->title_en }}">
            <div class="seva-info">
              <div class="seva-num">SEVA / {{ strtoupper($seva->category) }}</div>
              <h3>{{ $seva->title_hi }}</h3>
              <p>{{ $seva->summary_hi }}</p>
              <span class="arrow"><svg viewBox="0 0 24 24"><path d="M5 12h13M13 6l6 6-6 6"/></svg></span>
            </div>
          </a>
        @endforeach
      </div>
    </div>
  </section>

@endsection
