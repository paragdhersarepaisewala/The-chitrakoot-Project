@extends('layouts.app')

@section('title', 'आगामी कार्यक्रम एवं उत्सव | श्री कामदगिरि पीठम् सेवा ट्रस्ट')

@section('content')

  <section class="section" style="padding-top: 150px; background: #30231b; color: #fff; min-height: 80vh;">
    <div class="container" style="max-width: 1000px;">
      <div class="section-head reveal">
        <div class="eyebrow" style="color: var(--sand2);">Upcoming Events</div>
        <h1 style="font: 500 clamp(40px, 5vw, 65px)/1.08 var(--dev); color: #fff;">आगामी कार्यक्रम एवं महोत्सव</h1>
        <p style="color: rgba(255,255,255,.6);">धार्मिक, सांस्कृतिक, शैक्षणिक एवं पर्यावरण सेवा आयोजनों की सूची।</p>
      </div>

      <div class="event-list reveal">
        @foreach($events as $event)
          <div class="event" style="padding: 30px 0;">
            <div class="date">
              <b>{{ $event->event_date->format('d') }}</b>
              {{ strtoupper($event->event_date->format('M Y')) }}
            </div>
            <div>
              <h3>{{ $event->title_hi }}</h3>
              <p style="font-size: 13px; color: rgba(255,255,255,.7);">स्थान: {{ $event->location_hi ?? 'चित्रकूट धाम' }}</p>
              <p style="font-size: 13px; color: rgba(255,255,255,.5); margin-top: 5px;">{{ $event->summary_hi }}</p>
            </div>
            <div>
              <a href="{{ url('/donate') }}" class="btn primary" style="padding: 10px 16px; font-size: 8px;">सहभागी बनें</a>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

@endsection
