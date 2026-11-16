@extends('layouts.app')

@section('title', 'आगामी कार्यक्रम एवं उत्सव | श्री कामदगिरि पीठम् सेवा ट्रस्ट')
@section('meta_description', 'श्री कामदगिरि पीठम् सेवा ट्रस्ट द्वारा आयोजित आगामी धार्मिक अनुष्ठान, राम नवमी, दीपदान एवं सांस्कृतिक कार्यक्रमों की पावन सूची।')

@section('content')

  <style>
    .events-container {
      max-width: 1000px;
      margin: 0 auto;
      padding: 0 16px;
    }
    .event-card {
      background: #FFFDFC;
      border: 1px solid rgba(180,83,9,0.18);
      border-radius: 20px;
      padding: 26px 30px;
      display: grid;
      grid-template-columns: 120px 1fr auto;
      align-items: center;
      gap: 24px;
      box-shadow: 0 6px 20px rgba(130,70,20,0.04);
      transition: transform .25s ease, box-shadow .25s ease;
    }
    .event-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 28px rgba(180,83,9,0.1);
      border-color: rgba(180,83,9,0.32);
    }
    .event-date-col {
      text-align: center;
      border-right: 1px solid rgba(180,83,9,0.16);
      padding-right: 20px;
      flex-shrink: 0;
    }
    .event-content-col {
      min-width: 0;
    }
    .event-action-col {
      flex-shrink: 0;
    }
    .event-cta-btn {
      padding: 11px 24px;
      font-size: 12px;
      font-weight: 700;
      border-radius: 9999px;
      white-space: nowrap;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
    }

    .d-mobile-only {
      display: none;
    }
    .d-desktop-only {
      display: flex;
    }

    /* Mobile Responsive Layout (< 768px) */
    @media (max-width: 768px) {
      .d-mobile-only {
        display: inline-flex !important;
      }
      .d-desktop-only {
        display: none !important;
      }
      .event-card {
        display: flex;
        flex-direction: column;
        align-items: stretch;
        gap: 16px;
        padding: 20px 18px;
        border-radius: 16px;
      }
      .event-date-col {
        border-right: none;
        border-bottom: 1px solid rgba(180,83,9,0.12);
        padding-right: 0;
        padding-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        text-align: left;
      }
      .event-date-badge {
        display: inline-flex;
        align-items: baseline;
        gap: 8px;
      }
      .event-content-col h3 {
        font-size: 19px !important;
        margin: 0 0 8px !important;
      }
      .event-action-col {
        width: 100%;
        margin-top: 4px;
      }
      .event-cta-btn {
        width: 100%;
        padding: 13px 20px;
        font-size: 13px;
        text-align: center;
      }
    }
  </style>

  <section class="section" style="padding-top: 140px; padding-bottom: 70px; background: var(--paper, #FAF4ED); color: var(--ink, #2B1A12); min-height: 80vh;">
    <div class="events-container">
      <div class="section-head reveal" style="text-align: center; margin-bottom: 30px;">
        <div class="eyebrow" style="color: #B45309; justify-content: center; margin-bottom: 10px;">
          {{ ($tab ?? 'upcoming') === 'archive' ? 'Temple Events Archive' : 'Upcoming Religious Events' }}
        </div>
        <h1 style="font: 700 clamp(32px, 4vw, 52px)/1.2 var(--dev); color: #2B1A12; margin: 0 0 14px;">
          {{ ($tab ?? 'upcoming') === 'archive' ? 'उत्सव अभिलेख एवं संपन्न कार्यक्रम' : 'आगामी कार्यक्रम एवं महोत्सव' }}
        </h1>
        <p style="color: #7A4B29; font-size: 16px; line-height: 1.8; max-width: 680px; margin: 0 auto;">
          {{ ($tab ?? 'upcoming') === 'archive' ? 'चित्रकूट धाम में पूर्व में सफलतापूर्वक संपन्न हुए धार्मिक उत्सवों, महाआरती एवं सेवा अभियानों का पावन संकलन।' : 'धार्मिक, सांस्कृतिक, शैक्षणिक एवं पर्यावरण सेवा आयोजनों की पावन शृंखला।' }}
        </p>

        <!-- Events Tab Switcher (Upcoming vs Archive) -->
        <div style="display: inline-flex; align-items: center; gap: 8px; background: #FFFDFC; padding: 6px; border-radius: 9999px; border: 1.5px solid rgba(180,83,9,0.22); margin-top: 24px; box-shadow: 0 4px 14px rgba(180,83,9,0.06);">
          <a href="{{ route('events.index', ['tab' => 'upcoming']) }}" 
             style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 22px; border-radius: 9999px; font-size: 13px; font-weight: 700; text-decoration: none; transition: all .2s ease; {{ ($tab ?? 'upcoming') !== 'archive' ? 'background: #D95D0F; color: #ffffff; box-shadow: 0 2px 8px rgba(217,93,15,0.3);' : 'color: #7A4B29;' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            <span>आगामी कार्यक्रम</span>
            <span style="font-size: 11px; padding: 2px 7px; border-radius: 9999px; {{ ($tab ?? 'upcoming') !== 'archive' ? 'background: rgba(255,255,255,0.25); color: #fff;' : 'background: rgba(180,83,9,0.1); color: #B45309;' }}">
              {{ $upcomingCount ?? 0 }}
            </span>
          </a>

          <a href="{{ route('events.index', ['tab' => 'archive']) }}" 
             style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 22px; border-radius: 9999px; font-size: 13px; font-weight: 700; text-decoration: none; transition: all .2s ease; {{ ($tab ?? 'upcoming') === 'archive' ? 'background: #2B1A12; color: #ffffff; box-shadow: 0 2px 8px rgba(43,26,18,0.3);' : 'color: #7A4B29;' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 8v13H3V8"/><path d="M1 3h22v5H1z"/><path d="M10 12h4"/></svg>
            <span>उत्सव अभिलेख (Archive)</span>
            <span style="font-size: 11px; padding: 2px 7px; border-radius: 9999px; {{ ($tab ?? 'upcoming') === 'archive' ? 'background: rgba(255,255,255,0.25); color: #fff;' : 'background: rgba(180,83,9,0.1); color: #B45309;' }}">
              {{ $archivedCount ?? 0 }}
            </span>
          </a>
        </div>
      </div>

      <div class="reveal" style="display: flex; flex-direction: column; gap: 18px;">
        @forelse($events as $event)
          @php
            $isArchived = $event->is_archived || $event->isExpired();
          @endphp
          <div class="event-card" style="{{ $isArchived ? 'opacity: 0.94; background: #FDFBF8; border-color: rgba(140,120,105,0.2);' : '' }}">
            <!-- Date Display -->
            <div class="event-date-col">
              <div class="event-date-badge">
                <b style="display: block; color: {{ $isArchived ? '#64748B' : '#D95D0F' }}; font: 700 38px var(--serif); line-height: 1;">{{ $event->event_date->format('d') }}</b>
                <span style="font-size: 11px; font-weight: 800; letter-spacing: .12em; color: {{ $isArchived ? '#64748B' : '#7A4B29' }}; text-transform: uppercase;">{{ strtoupper($event->event_date->format('M Y')) }}</span>
              </div>
              <!-- Mobile Location Pill -->
              <span class="d-mobile-only" style="font-size: 11.5px; color: #B45309; font-weight: 700; background: rgba(180,83,9,0.08); padding: 4px 10px; border-radius: 9999px;">
                {{ $event->location_hi ?? 'चित्रकूट धाम' }}
              </span>
            </div>

            <!-- Content Details -->
            <div class="event-content-col">
              <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 6px;">
                @if($isArchived)
                  <span style="background: rgba(100,116,139,0.12); color: #475569; font-size: 11px; font-weight: 800; letter-spacing: .06em; padding: 3px 10px; border-radius: 9999px; display: inline-flex; align-items: center; gap: 4px;">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    संपन्न एवं संगृहीत (Archived)
                  </span>
                @else
                  <span style="background: rgba(22,163,74,0.12); color: #166534; font-size: 11px; font-weight: 800; letter-spacing: .06em; padding: 3px 10px; border-radius: 9999px; display: inline-flex; align-items: center; gap: 4px;">
                    ● आगामी (Upcoming)
                  </span>
                @endif

                <span class="d-desktop-only" style="font-size: 12.5px; color: #B45309; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                  <span>स्थान: {{ $event->location_hi ?? 'चित्रकूट धाम' }}</span>
                </span>
              </div>

              <h3 style="font: 700 20px/1.3 var(--dev); color: #2B1A12; margin: 0 0 6px;">{{ $event->title_hi }}</h3>
              <p style="font-size: 14px; color: #7A4B29; margin: 0; line-height: 1.65;">{{ $event->summary_hi }}</p>
            </div>

            <!-- Action Button -->
            <div class="event-action-col">
              @if($isArchived)
                <a href="{{ url('/gallery') }}" class="btn secondary event-cta-btn" style="border: 1.5px solid rgba(100,116,139,0.3); color: #334155; background: #fff;">
                  <span>स्मृति चित्र दीर्घा</span>
                  <span style="font-size: 14px;">&rarr;</span>
                </a>
              @else
                <a href="{{ url('/donate') }}" class="btn primary event-cta-btn">
                  <span>सहभागी बनें</span>
                  <span style="font-size: 14px;">&rarr;</span>
                </a>
              @endif
            </div>
          </div>
        @empty
          <div style="text-align: center; padding: 60px 20px; background: #fff; border-radius: 16px; border: 1px dashed rgba(180,83,9,0.3);">
            @if(($tab ?? 'upcoming') === 'archive')
              <p style="font-size: 16px; color: #7A4B29; margin: 0 0 12px;">वर्तमान में कोई पुराना कार्यक्रम अभिलेख में उपलब्ध नहीं है।</p>
              <a href="{{ route('events.index', ['tab' => 'upcoming']) }}" class="btn primary" style="font-size: 13px; padding: 8px 18px; border-radius: 9999px;">
                आगामी कार्यक्रम देखें &rarr;
              </a>
            @else
              <p style="font-size: 16px; color: #7A4B29; margin: 0 0 12px;">वर्तमान में कोई आगामी कार्यक्रम सक्रिय नहीं है। तिथि बीतने के कारण पूर्व उत्सव अभिलेख में स्थानांतरित कर दिए गए हैं।</p>
              <a href="{{ route('events.index', ['tab' => 'archive']) }}" class="btn secondary" style="font-size: 13px; padding: 8px 18px; border-radius: 9999px; border: 1.5px solid rgba(180,83,9,0.3); color: #7A4B29;">
                संपन्न उत्सवों का अभिलेख (Archive) देखें &rarr;
              </a>
            @endif
          </div>
        @endforelse

        @if($events->hasPages())
          <div style="margin-top: 24px; display: flex; justify-content: center;">
            {{ $events->links() }}
          </div>
        @endif
      </div>
    </div>
  </section>

@endsection
