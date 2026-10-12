@extends('layouts.app')

@section('title', 'श्री कामदगिरि पीठम् सेवा ट्रस्ट | चित्रकूट धाम')

@section('content')

  <!-- HERO SECTION -->
  <section class="hero" id="home">
    <div class="hero-media" style="background-image: linear-gradient(90deg,rgba(17,12,9,.88) 0%,rgba(17,12,9,.6) 30%,rgba(17,12,9,.18) 68%,rgba(17,12,9,.16)), linear-gradient(0deg,rgba(12,8,6,.74) 0%,rgba(12,8,6,.08) 54%,rgba(12,8,6,.28)), url('{{ asset($heroSlide?->image_path ?? 'https://images.unsplash.com/photo-1548013146-72479768bada?auto=format&fit=crop&w=2400&q=90') }}');"></div>
    <div class="hero-content">
      <div class="hero-kicker reveal">A Sacred Legacy · A Living Mission</div>
      <h1 class="display reveal delay-1">{!! nl2br(e($heroSlide?->title ?? 'सेवा से समृद्धि\nसंस्कार से सशक्त\nप्रकृति से सुरक्षित')) !!}</h1>
      <p class="hero-desc reveal delay-2">
        {{ $heroSlide?->subtitle ?? 'श्री कामदगिरि पीठम् सेवा ट्रस्ट — सनातन संस्कृति, शिक्षा, गौसेवा, पर्यावरण संरक्षण और मानव कल्याण के लिए समर्पित एक सेवा परिवार।' }}
      </p>
      <div class="hero-actions reveal delay-3">
        <a class="btn primary" href="{{ url('/seva') }}">हमारी सेवाएँ <svg viewBox="0 0 24 24"><path d="M5 12h13M13 6l6 6-6 6"/></svg></a>
        <a class="btn" href="{{ url('/donate') }}">दान करें <svg viewBox="0 0 24 24"><path d="M5 12h13M13 6l6 6-6 6"/></svg></a>
      </div>
    </div>
    <div class="hero-bottom">
      <div class="hero-caption">CHITRAKOOT DHAM<strong>Kamadgiri · Mandakini · Seva</strong></div>
      <div class="scroll-cue">Scroll to explore</div>
      <div class="hero-index">01 / 04</div>
    </div>
  </section>

  <!-- TRUST INTRODUCTION SECTION -->
  <section class="trust section">
    <div class="container trust-grid">
      <div class="trust-copy reveal">
        <div class="eyebrow">About Our Trust</div>
        <h2>चित्रकूट की पवित्र भूमि पर<br>सेवा का सतत संकल्प</h2>
        <p>
          श्री कामदगिरि पीठम् सेवा ट्रस्ट का उद्देश्य सनातन परंपराओं,
          वैदिक एवं संस्कृत शिक्षा, गौ संरक्षण, पर्यावरण और समाज सेवा
          के माध्यम से एक अधिक करुणामय और संस्कारित समाज की दिशा में कार्य करना है।
        </p>
        <a class="underlink" href="{{ url('/about') }}">Discover Our Story <svg viewBox="0 0 24 24"><path d="M5 12h13M13 6l6 6-6 6"/></svg></a>
      </div>

      <div class="trust-visual reveal delay-2">
        <img src="https://images.unsplash.com/photo-1582510003544-4d00b7f74220?auto=format&fit=crop&w=1000&q=85" alt="Indian temple heritage architecture">
        <div class="stats">
          <div class="stat"><b data-count="25">25+</b><span>Years of Service</span></div>
          <div class="stat"><b data-count="500">500+</b><span>Students</span></div>
          <div class="stat"><b data-count="250">250+</b><span>Cows in Care</span></div>
          <div class="stat"><b data-count="10000">10K+</b><span>Trees Planted</span></div>
        </div>
      </div>
    </div>
  </section>

  <!-- SEVA PROJECTS PREVIEW -->
  <section class="seva section" id="seva">
    <div class="container">
      <div class="section-head reveal">
        <div class="eyebrow">Our Seva</div>
        <h2>धर्म, सेवा और प्रकृति के<br>लिए समर्पित</h2>
        <p>हमारे प्रमुख प्रकल्पों की एक झलक। प्रत्येक सेवा की विस्तृत जानकारी उसके समर्पित पृष्ठ पर उपलब्ध होगी।</p>
      </div>

      <div class="seva-grid">
        @forelse($sevas as $index => $seva)
          <a class="seva-card reveal {{ $index > 0 ? 'delay-'.$index : '' }}" href="{{ url('/seva/' . $seva->slug) }}">
            <img src="{{ $seva->featured_image }}" alt="{{ $seva->title_en }}">
            <div class="seva-info">
              <div class="seva-num">0{{ $index + 1 }} / {{ strtoupper($seva->category) }}</div>
              <h3>{{ $seva->title_hi }}</h3>
              <p>{{ $seva->summary_hi }}</p>
              <span class="arrow"><svg viewBox="0 0 24 24"><path d="M5 12h13M13 6l6 6-6 6"/></svg></span>
            </div>
          </a>
        @empty
          <div class="seva-card reveal">
            <img src="https://images.unsplash.com/photo-1560493676-04071c5f467b?auto=format&fit=crop&w=1500&q=85" alt="Gaushala">
            <div class="seva-info">
              <div class="seva-num">01 / GAUSHALA</div>
              <h3>गौ सेवा, गौ संरक्षण</h3>
              <p>गौवंश के लिए देखभाल, आश्रय और सेवा — करुणा को कर्म में बदलने का हमारा संकल्प।</p>
              <span class="arrow"><svg viewBox="0 0 24 24"><path d="M5 12h13M13 6l6 6-6 6"/></svg></span>
            </div>
          </div>
        @endforelse
      </div>
    </div>
  </section>

  <!-- CHITRAKOOT GUIDE SECTION -->
  <section class="chitrakoot section">
    <div class="container chitra-grid">
      <img class="chitra-img reveal" src="https://images.unsplash.com/photo-1600100397608-f0107a2d0e2f?auto=format&fit=crop&w=1300&q=85" alt="Sacred landscape of Chitrakoot">
      <div class="chitra-copy reveal delay-1">
        <div class="eyebrow">Chitrakoot Dham</div>
        <h2>जहाँ हर पग<br>एक कथा है</h2>
        <p>चित्रकूट भारत की उन पवित्र भूमियों में से है जहाँ प्रकृति, इतिहास और आध्यात्मिक परंपरा एक साथ अनुभव होती है। कामदगिरि की परिक्रमा से रामघाट की संध्या तक, यहाँ की यात्रा अपने आप में एक अनुभव है।</p>
        <a class="underlink" href="{{ url('/chitrakoot') }}">Explore Chitrakoot <svg viewBox="0 0 24 24"><path d="M5 12h13M13 6l6 6-6 6"/></svg></a>

        <div class="places">
          @foreach($destinations as $dest)
            <a class="place" href="{{ url('/chitrakoot/' . $dest->slug) }}">
              <img src="{{ $dest->featured_image }}" alt="{{ $dest->name_en }}">
              <div class="place-copy">
                <b>{{ $dest->name_hi }}</b>
                <span>{{ $dest->name_en }}</span>
              </div>
            </a>
          @endforeach
        </div>
      </div>
    </div>
  </section>

  <!-- UPCOMING EVENTS SECTION -->
  <section class="events section" id="events">
    <div class="events-bg"></div>
    <div class="container events-inner">
      <div class="section-head reveal">
        <div class="eyebrow">Upcoming Events</div>
        <h2>आगामी कार्यक्रम</h2>
        <p>धार्मिक, सांस्कृतिक और सामाजिक गतिविधियों से जुड़ें।</p>
      </div>

      <div class="event-list reveal">
        @forelse($events as $event)
          <a class="event" href="{{ url('/events/' . $event->slug) }}">
            <div class="date">
              <b>{{ $event->event_date->format('d') }}</b>
              {{ strtoupper($event->event_date->format('M Y')) }}
            </div>
            <div>
              <h3>{{ $event->title_hi }}</h3>
              <p>{{ $event->location_hi ?? 'चित्रकूट धाम' }}</p>
            </div>
            <span class="arrow"><svg viewBox="0 0 24 24"><path d="M5 12h13M13 6l6 6-6 6"/></svg></span>
          </a>
        @empty
          <p style="color:rgba(255,255,255,.6);">कोई आगामी कार्यक्रम नहीं मिला।</p>
        @endforelse
      </div>
    </div>
  </section>

  <!-- DONATION CTA SECTION -->
  <section class="donation" id="donate">
    <div class="container">
      <div class="donation-box reveal">
        <div class="donation-photo"></div>
        <div class="donation-copy">
          <div class="eyebrow">Support the Mission</div>
          <h2>आपकी सहभागिता<br>एक सेवा बन सकती है</h2>
          <p>गौसेवा, संस्कृत शिक्षा, धार्मिक आयोजनों और समाज कल्याण के हमारे प्रयासों में सहभागी बनें। अपनी इच्छित सेवा का चयन कर ऑनलाइन दान करें।</p>
          <a class="btn primary" href="{{ url('/donate') }}">Donate Now <svg viewBox="0 0 24 24"><path d="M5 12h13M13 6l6 6-6 6"/></svg></a>
        </div>
      </div>
    </div>
  </section>

@endsection
