@extends('layouts.app')

@section('title', $destination->name_hi . ' | चित्रकूट धाम दर्शन')

@section('content')

  <section class="section" style="padding-top: 150px; background: var(--paper, #FAF4ED); min-height: 85vh;">
    <div class="container" style="max-width: 900px;">

      <div class="reveal" style="margin-bottom: 30px;">
        <a href="{{ url('/chitrakoot') }}" style="font: 700 12.5px var(--sans); color: #B45309; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 20px;">
          &larr; सभी तीर्थ स्थल देखें
        </a>
        <div class="eyebrow" style="color: #B45309;">Chitrakoot Sacred Site</div>
        <h1 style="font: 700 clamp(36px, 5vw, 56px)/1.15 var(--dev); margin-top: 12px; color: #2B1A12;">{{ $destination->name_hi }}</h1>
        <p style="font: 700 12px var(--sans); letter-spacing: .12em; text-transform: uppercase; color: #B45309; margin-top: 6px;">{{ $destination->name_en }}</p>
      </div>

      <div class="reveal" style="margin-bottom: 40px;">
        <img src="{{ $destination->featured_image }}" alt="{{ $destination->name_en }}" style="max-height: 480px; width: 100%; object-fit: cover; border-radius: 20px; border: 1px solid rgba(180,83,9,0.18); box-shadow: 0 16px 40px rgba(130,70,20,0.08);">
      </div>

      <div class="reveal" style="background: #FFFDFC; border: 1px solid rgba(180,83,9,0.18); border-radius: 20px; padding: clamp(24px, 4vw, 40px); box-shadow: 0 10px 30px rgba(130,70,20,0.05); font: 400 16px/2 var(--dev); color: #7A4B29; margin-bottom: 50px;">
        <h2 style="font: 700 24px var(--dev); color: #2B1A12; margin: 0 0 16px;">पौराणिक एवं धार्मिक महत्त्व</h2>
        <p style="margin: 0;">{{ $destination->description_hi }}</p>
      </div>

    </div>
  </section>

@endsection
