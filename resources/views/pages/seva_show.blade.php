@extends('layouts.app')

@section('title', $seva->title_hi . ' | श्री कामदगिरि पीठम् सेवा ट्रस्ट')

@section('content')

  <section class="section" style="padding-top: 150px; background: var(--paper);">
    <div class="container" style="max-width: 900px;">

      <div class="reveal" style="margin-bottom: 30px;">
        <a href="{{ url('/seva') }}" class="underlink" style="margin-bottom: 20px;">← सभी सेवाएँ देखें</a>
        <div class="eyebrow">{{ strtoupper($seva->category) }}</div>
        <h1 style="font: 500 clamp(38px, 5vw, 58px)/1.1 var(--dev); margin-top: 10px; color: var(--brown);">{{ $seva->title_hi }}</h1>
        <p style="font: 600 13px var(--sans); color: rgba(45,36,30,.6); margin-top: 5px;">{{ $seva->title_en }}</p>
      </div>

      <div class="reveal" style="margin-bottom: 40px;">
        <img src="{{ $seva->featured_image }}" alt="{{ $seva->title_en }}" style="max-height: 480px; object-fit: cover; border: 1px solid var(--line);">
      </div>

      <div class="reveal" style="font: 400 16px/2 var(--dev); color: rgba(45,36,30,.85); margin-bottom: 50px;">
        <p>{{ $seva->description_hi }}</p>
      </div>

      <div class="donation-box reveal" style="grid-template-columns: 1fr;">
        <div class="donation-copy" style="padding: 50px;">
          <div class="eyebrow">Support {{ $seva->title_en }}</div>
          <h2>इस सेवा प्रकल्प में सहभागी बनें</h2>
          <p>{{ $seva->summary_hi }}</p>
          <a class="btn primary" href="{{ url('/donate?cause_id=' . $seva->cause_id) }}">ऑनलाइन दान करें <svg viewBox="0 0 24 24"><path d="M5 12h13M13 6l6 6-6 6"/></svg></a>
        </div>
      </div>

    </div>
  </section>

@endsection
