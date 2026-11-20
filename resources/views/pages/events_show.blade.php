@extends('layouts.app')

@section('title', ($event->title_hi ?: $event->title_en) . ' | श्री कामदगिरि पीठम् सेवा ट्रस्ट')
@section('meta_description', Str::limit($event->summary_hi ?: ($event->description_hi ?: $event->title_hi), 155))

@section('content')

  <style>
    .event-show-container {
      max-width: 920px;
      margin: 0 auto;
      padding: 0 20px;
    }
    .event-meta-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-size: 11px;
      font-weight: 800;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      padding: 4px 12px;
      border-radius: 9999px;
    }
    .event-info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 16px;
      margin-bottom: 30px;
    }
    .event-info-box {
      background: #FFFDFC;
      border: 1px solid rgba(180, 83, 9, 0.18);
      border-radius: 16px;
      padding: 18px 20px;
      box-shadow: 0 4px 16px rgba(130, 70, 20, 0.04);
    }
    @media (max-width: 768px) {
      .event-show-container {
        padding: 0 16px;
      }
      .event-info-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>

  <section class="section" style="padding-top: 140px; padding-bottom: 80px; background: var(--paper, #FAF4ED); min-height: 85vh;">
    <div class="event-show-container">

      <!-- Breadcrumbs & Navigation -->
      <div class="reveal" style="margin-bottom: 24px;">
        <a href="{{ url('/events') }}" style="font-size: 12.5px; font-weight: 700; color: #B45309; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 16px;">
          &larr; समस्त आगामी एवं संपन्न कार्यक्रम देखें
        </a>
        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 10px; margin-bottom: 10px;">
          @if ($event->is_archived)
            <span class="event-meta-badge" style="color: #6B7280; background: #F3F4F6; border: 1px solid rgba(107, 114, 128, 0.3);">
              सम्पन्न कार्यक्रम (Archived)
            </span>
          @else
            <span class="event-meta-badge" style="color: #166534; background: #DCFCE7; border: 1px solid rgba(22, 101, 52, 0.25);">
              आगामी पावन उत्सव (Upcoming)
            </span>
          @endif
          <span style="font-size: 12px; color: #7A4B29; font-weight: 600;">
            • श्री कामदगिरि पीठम् उत्सव
          </span>
        </div>

        <!-- Strict single H1 -->
        <h1 style="font-family: 'Noto Serif', serif; font-size: clamp(26px, 4vw, 42px); font-weight: 800; line-height: 1.25; color: #2B1A12; margin: 0 0 6px;">
          {{ $event->title_hi }}
        </h1>
        @if ($event->title_en)
          <p style="font-size: 14px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: #B45309; margin: 0 0 18px;">
            {{ $event->title_en }}
          </p>
        @endif
      </div>

      <!-- Featured Image Banner -->
      <div class="reveal" style="margin-bottom: 28px;">
        @php
          $eventImage = $event->featured_image;
          if (empty($eventImage)) {
            $eventImage = '/uploads/kamadgiri-temple-crest-emblem-2-1792223701.webp';
          }
        @endphp
        <img src="{{ $eventImage }}" alt="{{ $event->title_en ?: $event->title_hi }}" style="max-height: 460px; width: 100%; object-fit: cover; border-radius: 20px; border: 1px solid rgba(180,83,9,0.18); box-shadow: 0 16px 40px rgba(130,70,20,0.08);" loading="eager">
      </div>

      <!-- Event Key Details Grid -->
      <div class="reveal event-info-grid">
        <!-- Date & Time Box -->
        <div class="event-info-box">
          <div style="display: flex; align-items: center; gap: 8px; color: #D95D0F; margin-bottom: 6px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em;">तिथि एवं पावन समय</span>
          </div>
          <div style="font-size: 16px; font-weight: 800; color: #2B1A12;">
            {{ $event->event_date ? $event->event_date->translatedFormat('d F Y (l)') : 'शीघ्र घोषित होगी' }}
          </div>
          @if ($event->event_date)
            <div style="font-size: 13px; color: #7A4B29; font-weight: 600; margin-top: 2px;">
              {{ $event->event_date->format('h:i A') }}
              @if ($event->event_end_date)
                — {{ $event->event_end_date->format('h:i A') }}
              @endif
            </div>
          @endif
        </div>

        <!-- Location Box -->
        <div class="event-info-box">
          <div style="display: flex; align-items: center; gap: 8px; color: #D95D0F; margin-bottom: 6px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em;">आयोजन स्थल</span>
          </div>
          <div style="font-size: 15px; font-weight: 800; color: #2B1A12;">
            {{ $event->location_hi ?: ($event->location_en ?: 'कामदगिरि पीठम्, चित्रकूट') }}
          </div>
          @if ($event->location_en)
            <div style="font-size: 12.5px; color: #7A4B29; font-weight: 500; margin-top: 2px;">
              {{ $event->location_en }}
            </div>
          @endif
        </div>

        <!-- Participation / RSVP Box -->
        <div class="event-info-box">
          <div style="display: flex; align-items: center; gap: 8px; color: #D95D0F; margin-bottom: 6px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em;">सहभागिता एवं सेवा</span>
          </div>
          <div style="font-size: 14px; font-weight: 700; color: #166534;">
            समस्त श्रद्धालु एवं भक्त आमंत्रित हैं
          </div>
          <div style="font-size: 12px; color: #7A4B29; margin-top: 2px;">
            निःशुल्क प्रवेश एवं महाप्रसाद व्यवस्था
          </div>
        </div>
      </div>

      <!-- Narrative Description Body -->
      <div class="reveal" style="background: #FFFDFC; border: 1px solid rgba(180,83,9,0.18); border-radius: 20px; padding: clamp(24px, 4vw, 40px); box-shadow: 0 10px 30px rgba(130,70,20,0.05); font-size: 15.5px; line-height: 1.9; color: #4A3326; margin-bottom: 36px;">
        <h2 style="font-family: 'Noto Serif', serif; font-size: 21px; font-weight: 700; color: #2B1A12; margin: 0 0 14px;">
          कार्यक्रम का पावन विवरण एवं महात्म्य
        </h2>
        @if ($event->summary_hi)
          <div style="font-size: 16px; font-weight: 600; color: #B45309; margin-bottom: 18px; padding-left: 14px; border-left: 3px solid #D95D0F;">
            {{ $event->summary_hi }}
          </div>
        @endif

        <div style="white-space: pre-line;">
          {!! nl2br(e($event->description_hi ?: ($event->description_en ?: $event->summary_hi))) !!}
        </div>

        @if ($event->registration_url)
          <div style="margin-top: 28px; padding-top: 20px; border-top: 1px dashed rgba(180,83,9,0.2);">
            <a href="{{ $event->registration_url }}" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 28px; border-radius: 9999px; background: linear-gradient(135deg, #D95D0F, #EA580C); color: #fff; font-size: 13.5px; font-weight: 800; text-decoration: none; box-shadow: 0 4px 16px rgba(217, 93, 15, 0.35);">
              <span>ऑनलाइन पंजीकरण करें (Register Online)</span>
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6M15 3h6v6M10 14L21 3"/></svg>
            </a>
          </div>
        @endif
      </div>

      <!-- Action & Seva Banner -->
      <div class="reveal" style="background: linear-gradient(135deg, #FAF4ED 0%, #FFFDFC 100%); border: 1.5px solid rgba(180,83,9,0.22); border-radius: 20px; padding: clamp(28px, 4vw, 40px); box-shadow: 0 12px 35px rgba(130,70,20,0.06); text-align: center;">
        <div style="display: inline-flex; align-items: center; justify-content: center; gap: 6px; color: #B45309; font-size: 11px; font-weight: 800; letter-spacing: 0.12em; text-transform: uppercase; margin-bottom: 10px;">
          <span>॥ सेवा एवं अन्नक्षेत्र सहयोग ॥</span>
        </div>
        <h3 style="font-family: 'Noto Serif', serif; font-size: clamp(20px, 3vw, 28px); font-weight: 700; color: #2B1A12; margin: 0 0 10px;">
          उत्सव एवं अन्नक्षेत्र सेवा में अपना पावन सहयोग अर्पण करें
        </h3>
        <p style="font-size: 14px; color: #7A4B29; max-width: 600px; margin: 0 auto 20px; line-height: 1.6;">
          श्री कामदगिरि पीठम् द्वारा आयोजित कार्यक्रमों में भक्तों के लिए आवास, भंडारा एवं वैदिक अनुष्ठान का आयोजन किया जाता है।
        </p>
        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 12px;">
          <a href="{{ url('/donate') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 30px; font-size: 13.5px; font-weight: 800; border-radius: 9999px; background: linear-gradient(135deg, #D95D0F, #EA580C); color: #fff; text-decoration: none; box-shadow: 0 4px 18px rgba(217, 93, 15, 0.35);">
            <span>सहयोग दान करें</span>
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </a>
          <a href="{{ url('/volunteer') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 26px; font-size: 13.5px; font-weight: 700; border-radius: 9999px; background: #fff; color: #B45309; border: 1.5px solid rgba(180,83,9,0.3); text-decoration: none;">
            <span>स्वयंसेवक बनें (Volunteer)</span>
          </a>
        </div>
      </div>

    </div>
  </section>

@endsection
