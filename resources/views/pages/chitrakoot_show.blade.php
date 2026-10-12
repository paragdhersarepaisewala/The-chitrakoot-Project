@extends('layouts.app')

@section('title', $destination->name_hi . ' | चित्रकूट धाम दर्शन')

@section('content')

  <section class="section" style="padding-top: 150px; background: var(--paper);">
    <div class="container" style="max-width: 900px;">

      <div class="reveal" style="margin-bottom: 30px;">
        <a href="{{ url('/chitrakoot') }}" class="underlink" style="margin-bottom: 20px;">← सभी तीर्थ स्थल देखें</a>
        <div class="eyebrow">Chitrakoot Sacred Site</div>
        <h1 style="font: 500 clamp(38px, 5vw, 58px)/1.1 var(--dev); margin-top: 10px; color: var(--brown);">{{ $destination->name_hi }}</h1>
        <p style="font: 600 13px var(--sans); color: rgba(45,36,30,.6); margin-top: 5px;">{{ $destination->name_en }}</p>
      </div>

      <div class="reveal" style="margin-bottom: 40px;">
        <img src="{{ $destination->featured_image }}" alt="{{ $destination->name_en }}" style="max-height: 480px; object-fit: cover; border: 1px solid var(--line);">
      </div>

      <div class="reveal" style="font: 400 16px/2 var(--dev); color: rgba(45,36,30,.85); margin-bottom: 50px;">
        <h3 style="font: 500 24px var(--dev); color: var(--brown); margin-bottom: 15px;">पौराणिक एवं धार्मिक महत्त्व</h3>
        <p>{{ $destination->description_hi }}</p>
      </div>

    </div>
  </section>

@endsection
