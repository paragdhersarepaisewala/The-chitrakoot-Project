@extends('layouts.app')

@section('title', 'Digital ID Card | Shri Kamadgiri Peetham Sewa Trust')

@section('content')

  <style>
    @media print {
      header, footer, .mobile-bar, .mobile-menu, .loader, .ticker-bar, .btn, .underlink, .no-print, nav, h1, a {
        display: none !important;
      }
      body, html {
        background: #fff !important;
        color: #000 !important;
        margin: 0 !important;
        padding: 0 !important;
      }
      .section {
        padding: 0 !important;
        margin: 0 !important;
        background: #fff !important;
        min-height: auto !important;
      }
      .container {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
      }
      #printableCard {
        margin: 20px auto !important;
        box-shadow: none !important;
        page-break-inside: avoid !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
      }
      @page {
        size: A4 portrait;
        margin: 12mm;
      }
    }

    @media (max-width: 640px) {
      .card-section-wrap {
        padding-top: 96px !important;
        padding-bottom: 50px !important;
        padding-left: 14px !important;
        padding-right: 14px !important;
      }
      .card-title-h1 {
        font-size: clamp(20px, 5.5vw, 26px) !important;
        line-height: 1.3 !important;
      }
      #printableCard {
        padding: 20px 16px !important;
        border-radius: 14px !important;
        margin-bottom: 22px !important;
      }
      .card-header-row {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 14px !important;
        padding-bottom: 16px !important;
        margin-bottom: 18px !important;
      }
      .card-header-badge {
        align-self: flex-start !important;
      }
      .card-user-row {
        gap: 14px !important;
        margin-bottom: 18px !important;
      }
      .card-photo-box {
        width: 72px !important;
        height: 90px !important;
      }
      .card-user-name {
        font-size: 18px !important;
      }
      .card-footer-row {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 10px !important;
        padding-top: 14px !important;
      }
      .card-footer-right {
        text-align: left !important;
      }
      .card-actions-wrap {
        flex-direction: column !important;
        gap: 10px !important;
        width: 100% !important;
      }
      .card-actions-wrap .btn {
        width: 100% !important;
        min-height: 44px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        box-sizing: border-box !important;
      }
    }
  </style>

  <section class="section card-section-wrap" style="padding-top: 140px; background: var(--paper); min-height: 85vh; display: flex; align-items: center;">
    <div class="container" style="max-width: 800px;">

      @if($cardType === 'none')
        <!-- NO CARD ISSUED YET -->
        <div style="background: #fff; border: 1px solid var(--line); padding: 50px; text-align: center; border-radius: 8px; box-shadow: 0 15px 40px rgba(0,0,0,.06);">
          <div style="width: 64px; height: 64px; border: 2px solid var(--sand); border-radius: 50%; display: grid; place-items: center; margin: 0 auto 20px; color: var(--brown);">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><line x1="7" y1="8" x2="17" y2="8"/><line x1="7" y1="12" x2="13" y2="12"/></svg>
          </div>
          <h2 style="font: 600 28px var(--dev); color: var(--deep); margin: 0 0 10px;">कार्ड अभी जारी नहीं हुआ है (No ID Card Issued Yet)</h2>
          <p style="font: 400 15px/1.8 var(--dev); color: rgba(43,35,29,.75); max-width: 500px; margin: 0 auto 30px;">
            पहला दान अथवा योगदान देने पर आपको आधिकारिक **Supporter Card** प्रदान किया जाएगा। ३ लगातार माह तक नियमित मासिक दान पर **Active Member Card** प्राप्त होता है।
          </p>
          <a href="{{ url('/donate') }}" class="btn primary" style="padding: 12px 28px; font-size: 12px; border-radius: 9999px; white-space: nowrap;">
            Donate Now
          </a>
        </div>
      @else

        <div style="text-align: center; margin-bottom: 26px;">
          <a href="{{ url('/dashboard') }}" class="underlink" style="font-size: 11px; margin-bottom: 10px; display: inline-block;">← Return to My Account</a>
          <h1 class="card-title-h1" style="font: 600 clamp(20px, 5.5vw, 30px) var(--dev); color: var(--deep); margin: 5px 0 0;">
            {{ $cardType === 'membership' ? 'आधिकारिक सदस्यता कार्ड (Active Member Card)' : 'आधिकारिक समर्थक कार्ड (Supporter Card)' }}
          </h1>
        </div>

        <!-- DIGITAL ID CARD CONTAINER -->
        <div id="printableCard" style="position: relative; width: 100%; max-width: 540px; margin: 0 auto 30px; background: {{ $cardType === 'membership' ? 'linear-gradient(135deg, #241a14 0%, #3a2b21 50%, #1a120d 100%)' : 'linear-gradient(135deg, #2d241d 0%, #44372e 100%)' }}; border: 2px solid {{ $cardType === 'membership' ? 'var(--sand2)' : 'rgba(255,255,255,.3)' }}; border-radius: 16px; padding: 35px; color: #fff; box-shadow: 0 25px 60px rgba(0,0,0,.3); overflow: hidden;">
          
          <!-- Watermark Logo Pattern -->
          <div style="position: absolute; right: -40px; bottom: -40px; opacity: 0.06; width: 280px; height: 300px; pointer-events: none;">
            <svg viewBox="0 0 50 58" style="width:100%; height:100%; fill:none; stroke:#fff; stroke-width:1;">
              <path d="M5 51V28L25 7l20 21v23"/><path d="M13 51V31l12-12 12 12v20"/><path d="M20 51V35h10v16"/><path d="M2 52h46"/><path d="M25 7V2"/>
            </svg>
          </div>

          <!-- Card Header -->
          <div class="card-header-row" style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px solid rgba(255,255,255,.15); padding-bottom: 20px; margin-bottom: 25px;">
            <div style="display: flex; align-items: center; gap: 12px;">
              <div style="width: 36px; height: 42px;">
                <svg viewBox="0 0 50 58" style="width:100%; height:100%; fill:none; stroke:var(--sand2); stroke-width:1.5;">
                  <path d="M5 51V28L25 7l20 21v23"/><path d="M13 51V31l12-12 12 12v20"/><path d="M20 51V35h10v16"/><path d="M2 52h46"/><path d="M25 7V2"/>
                </svg>
              </div>
              <div>
                <h3 style="font: 600 15px/1.2 var(--serif); color: #fff; margin: 0;">{{ $orgSettings->org_name }}</h3>
                <span style="font: 700 7px var(--sans); letter-spacing: .25em; color: var(--sand2); display: block; margin-top: 3px;">CHITRAKOOT DHAM</span>
              </div>
            </div>

            <!-- Card Badge -->
            <div class="card-header-badge" style="background: {{ $cardType === 'membership' ? 'var(--sand)' : 'rgba(255,255,255,.12)' }}; color: #fff; padding: 6px 14px; border-radius: 20px; font: 700 9px var(--sans); letter-spacing: .15em; text-transform: uppercase;">
              {{ $cardType === 'membership' ? 'GOLD ACTIVE MEMBER' : 'SUPPORTER' }}
            </div>
          </div>

          <!-- Card Body -->
          <div class="card-user-row" style="display: flex; gap: 25px; align-items: center; margin-bottom: 25px;">
            <!-- Profile Photo -->
            <div class="card-photo-box" style="width: 90px; height: 110px; border-radius: 8px; overflow: hidden; border: 2px solid var(--sand2); background: rgba(0,0,0,.3); flex-shrink: 0;">
              @if($user->profile?->profile_photo_path)
                <img src="{{ asset($user->profile->profile_photo_path) }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
              @else
                <div style="width: 100%; height: 100%; display: grid; place-items: center; color: rgba(255,255,255,.4); font: 700 24px var(--sans);">
                  {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
              @endif
            </div>

            <!-- Profile Info -->
            <div>
              <h2 class="card-user-name" style="font: 600 22px var(--dev); color: #fff; margin: 0 0 6px;">{{ $user->name }}</h2>
              <p style="font: 500 12px var(--sans); color: var(--sand2); margin: 0 0 10px;">ID: {{ $user->membership?->membership_id ?? ('SKP-SUP-' . $user->id) }}</p>

              <div style="font: 400 12px/1.6 var(--sans); color: rgba(255,255,255,.75);">
                @if($user->profile?->city || $user->profile?->state)
                  <div>Location: {{ $user->profile?->district ?? $user->profile?->city }}, {{ $user->profile?->state }}</div>
                @endif
                @if($user->profile?->occupation)
                  <div>Profession: {{ $user->profile->occupation }}</div>
                @endif
                <div>Joined: {{ $user->created_at->format('M Y') }}</div>
              </div>
            </div>
          </div>

          <!-- Card Footer -->
          <div class="card-footer-row" style="display: flex; justify-content: space-between; align-items: flex-end; border-top: 1px solid rgba(255,255,255,.15); padding-top: 18px; font: 500 10px var(--sans); color: rgba(255,255,255,.6);">
            <div>
              <span>Verified Status: </span>
              <strong style="color: #81c784;">OFFICIAL SEWA TRUST DONOR</strong>
            </div>
            <div class="card-footer-right" style="text-align: right;">
              <span style="display: block; font: 600 9px var(--serif); color: var(--sand2);">Shri Kamadgiri Peetham Sewa Trust</span>
              <span>Chitrakoot Dham, India</span>
            </div>
          </div>

        </div>

        <!-- Download Actions -->
        <div class="card-actions-wrap" style="text-align: center; display: flex; justify-content: center; gap: 14px; margin-top: 20px;">
          <button onclick="window.print()" class="btn primary" style="padding: 14px 28px; font-size: 11px; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Print / Download Card
          </button>
          <a href="{{ url('/dashboard') }}" class="btn dark" style="padding: 14px 24px; font-size: 11px; display: inline-flex; align-items: center; justify-content: center;">
            &larr; Return to Dashboard
          </a>
        </div>

      @endif

    </div>
  </section>

@endsection
