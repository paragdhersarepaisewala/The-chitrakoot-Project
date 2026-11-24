<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>
    {{ !empty($currentSeo->meta_title) ? $currentSeo->meta_title : View::yieldContent('title', ($orgSettings->org_name ?? 'Shri Kamadgiri Peetham Sewa Trust') . ' | Chitrakoot Dham') }}
  </title>
  <meta name="description"
    content="{{ !empty($currentSeo->meta_description) ? $currentSeo->meta_description : View::yieldContent('meta_description', ($siteMetaDescription ?? 'श्री कामदगिरि पीठम् सेवा ट्रस्ट, चित्रकूट धाम — सनातन संस्कृति, वैदिक शिक्षा, गौसेवा और पर्यावरण संरक्षण।')) }}">
  @if(!empty($currentSeo->meta_keywords) || !empty($currentSeo->keywords))
    <meta name="keywords" content="{{ $currentSeo->meta_keywords ?? $currentSeo->keywords }}">
  @endif
  <meta name="robots" content="{{ !empty($currentSeo->robots) ? $currentSeo->robots : 'index, follow' }}">
  <meta name="theme-color" content="#1c1511">

  <!-- Canonical Tag -->
  <link rel="canonical"
    href="@yield('canonical', (!empty($currentSeo->canonical_url) ? $currentSeo->canonical_url : url()->current()))">

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{ $siteFavicon ?? asset('favicon.ico') }}">

  <!-- Open Graph / Facebook / WhatsApp -->
  <meta property="og:type" content="@yield('og_type', 'website')">
  <meta property="og:url"
    content="@yield('canonical', (!empty($currentSeo->canonical_url) ? $currentSeo->canonical_url : url()->current()))">
  <meta property="og:title"
    content="@yield('og_title', View::yieldContent('title', (!empty($currentSeo->meta_title) ? $currentSeo->meta_title : ($orgSettings->org_name ?? 'Shri Kamadgiri Peetham Sewa Trust') . ' | Chitrakoot Dham')))">
  <meta property="og:description"
    content="@yield('og_description', View::yieldContent('meta_description', (!empty($currentSeo->meta_description) ? $currentSeo->meta_description : ($siteMetaDescription ?? 'श्री कामदगिरि पीठम् सेवा ट्रस्ट, चित्रकूट धाम'))))">
  <meta property="og:image"
    content="@yield('og_image', (!empty($currentSeo->og_image_url) ? $currentSeo->og_image_url : ($siteDefaultOgImage ?? asset('assets/uploads/temple-lotus-logo.png'))))">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:image:alt"
    content="@yield('og_title', View::yieldContent('title', (!empty($currentSeo->meta_title) ? $currentSeo->meta_title : ($orgSettings->org_name ?? 'Shri Kamadgiri Peetham Sewa Trust') . ' | Chitrakoot Dham')))">
  <meta property="og:site_name" content="{{ $orgSettings->org_name ?? 'Shri Kamadgiri Peetham Sewa Trust' }}">

  <!-- Twitter Cards -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title"
    content="@yield('og_title', View::yieldContent('title', (!empty($currentSeo->meta_title) ? $currentSeo->meta_title : ($orgSettings->org_name ?? 'Shri Kamadgiri Peetham Sewa Trust') . ' | Chitrakoot Dham')))">
  <meta name="twitter:description"
    content="@yield('og_description', View::yieldContent('meta_description', (!empty($currentSeo->meta_description) ? $currentSeo->meta_description : ($siteMetaDescription ?? 'श्री कामदगिरि पीठम् सेवा ट्रस्ट, चित्रकूट धाम'))))">
  <meta name="twitter:image"
    content="@yield('og_image', (!empty($currentSeo->og_image_url) ? $currentSeo->og_image_url : ($siteDefaultOgImage ?? asset('assets/uploads/temple-lotus-logo.png'))))">

  <!-- Google Search Console Verification -->
  @if(!empty($googleSiteVerification))
    <meta name="google-site-verification" content="{{ str_replace('google-site-verification=', '', $googleSiteVerification) }}">
  @endif

  <!-- Google Analytics 4 (GA4) -->
  @if(!empty($ga4Id))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga4Id }}"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag() { dataLayer.push(arguments); }
      gtag('js', new Date());
      gtag('config', '{{ $ga4Id }}');
    </script>
  @endif

  @if(!empty($ga4Script))
    {!! $ga4Script !!}
  @endif

  <!-- Schema.org Organization & WebSite JSON-LD -->
  @php
    $orgSchema = [
      '@context' => 'https://schema.org',
      '@graph' => [
        [
          '@type' => 'Organization',
          '@id' => url('/#organization'),
          'name' => $orgSettings->org_name ?? 'Shri Kamadgiri Peetham Sewa Trust',
          'alternateName' => [
            'Kamadgiri Trust',
            'कामदगिरि सेवा ट्रस्ट',
            'Shri Kamadgiri Peetham',
            'Kamadgiri Peetham',
            'Kamadgiri Sewa Trust'
          ],
          'url' => url('/'),
          'logo' => $siteLogoUrl ?? asset('assets/uploads/temple-lotus-logo.png'),
          'description' => $siteMetaDescription ?? 'सनातन संस्कृति, शिक्षा, गौसेवा, पर्यावरण और मानव कल्याण के लिए समर्पित सेवा परिवार।',
          'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => $orgSettings->address ?? 'Kamadgiri Parikrama Marg',
            'addressLocality' => 'Chitrakoot Dham',
            'addressCountry' => 'IN',
          ],
          'contactPoint' => [
            '@type' => 'ContactPoint',
            'telephone' => $orgSettings->phone ?? '+919415000000',
            'email' => $orgSettings->email ?? 'info@kamadgiripeetham.org',
            'contactType' => 'customer service',
          ],
        ],
        [
          '@type' => 'WebSite',
          '@id' => url('/#website'),
          'url' => url('/'),
          'name' => 'Kamadgiri Trust - Official Portal',
          'alternateName' => [
            'Kamadgiri',
            'Kamadgiri Temple',
            'Kamadgiri Parvat',
            'Kamadgiri Stay',
            'कामदगिरि चित्रकूट'
          ],
          'publisher' => [
            '@id' => url('/#organization')
          ],
        ]
      ]
    ];
  @endphp
  <script type="application/ld+json">
  {!! json_encode($orgSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
  </script>
  @yield('structured_data')

  <!-- Fonts & Material Symbols (Optimized for Fast Page Load) -->
  <link rel="dns-prefetch" href="//fonts.googleapis.com">
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Noto+Serif:ital,wght@0,400;0,600;0,700;1,400&family=Noto+Serif+Devanagari:wght@400;600;700;800&display=swap"
    media="print" onload="this.media='all'">
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
    media="print" onload="this.media='all'">
  <noscript>
    <link rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Noto+Serif:ital,wght@0,400;0,600;0,700;1,400&family=Noto+Serif+Devanagari:wght@400;600;700;800&display=swap">
    <link rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
  </noscript>

  <link rel="stylesheet"
    href="{{ asset('css/app.css') }}?v={{ file_exists(public_path('css/app.css')) ? filemtime(public_path('css/app.css')) : time() }}">
  @yield('styles')
  @stack('styles')
</head>

<body class="loading">

  <!-- Entrance loader -->
  <div class="loader" id="loader" aria-label="Loading">
    <div class="loader-inner">
      <div class="loader-mark-wrap">
        <div class="loader-halo"></div>
        <div class="loader-mark">
          @if(!empty($orgSettings->logo_path))
            <img class="loader-logo-img" src="{{ $siteLogoUrl }}" alt="{{ $orgSettings->org_name ?? 'Logo' }}">
          @else
            <svg class="loader-logo-svg" viewBox="0 0 50 58">
              <path d="M5 51V28L25 7l20 21v23" />
              <path d="M13 51V31l12-12 12 12v20" />
              <path d="M20 51V35h10v16" />
              <path d="M2 52h46" />
              <path d="M25 7V2" />
            </svg>
          @endif
        </div>
      </div>
      <div class="loader-name">{{ $orgSettings->org_name ?? 'Shri Kamadgiri Peetham Sewa Trust' }}</div>
      <div class="loader-sub">{{ $siteTagline ?? 'Sewa Trust • Chitrakoot Dham' }}</div>
      <div class="loader-line"><span></span></div>
    </div>
  </div>

  <!-- Header -->
  <header id="header" class="{{ request()->is('/') ? 'header-transparent' : '' }}">
    <div class="container nav">
      <a class="brand" href="{{ url('/') }}">
        <div class="brand-mark">
          @if(!empty($orgSettings->logo_path))
            <img class="brand-logo-img" src="{{ $siteLogoUrl }}" alt="{{ $orgSettings->org_name ?? 'Logo' }}"
              style="max-height: 44px; width: auto; object-fit: contain;">
          @else
            <svg viewBox="0 0 50 58">
              <path d="M5 51V28L25 7l20 21v23" />
              <path d="M13 51V31l12-12 12 12v20" />
              <path d="M20 51V35h10v16" />
              <path d="M2 52h46" />
              <path d="M25 7V2" />
            </svg>
          @endif
        </div>
        <div class="brand-name">
          <span class="brand-title">{{ $orgSettings->org_name ?? 'Shri Kamadgiri Peetham Sewa Trust' }}</span>
          <span class="brand-sub">{{ $siteTagline ?? 'Sewa Trust • Chitrakoot Dham' }}</span>
        </div>
      </a>

      <!-- Mobile Center Website Name Branding -->
      <a class="mobile-center-brand" href="{{ url('/') }}" aria-label="श्री कामदगिरि पीठम सेवा न्यास">
        <span class="mobile-brand-title">श्री कामदगिरि पीठम सेवा न्यास</span>
        <span class="mobile-brand-sub">चित्रकूट धाम • तपोभूमि</span>
      </a>

      <!-- Desktop Navigation -->
      <nav class="nav-links">
        @if(isset($headerNavItems) && $headerNavItems->count() > 0)
          @foreach($headerNavItems as $navItem)
            @if($navItem->children->count() > 0)
              <div class="nav-item">
                <button class="nav-link {{ request()->is(ltrim($navItem->url, '/') . '*') ? 'active' : '' }}">
                  {{ $navItem->label_en }} <svg class="chevron" viewBox="0 0 10 10">
                    <path d="M2 3.5 5 6.5 8 3.5" fill="none" stroke="currentColor" />
                  </svg>
                </button>
                <div class="dropdown">
                  @foreach($navItem->children as $subItem)
                    <a href="{{ $subItem->resolved_url }}" target="{{ $subItem->target }}">{{ $subItem->label_en }}</a>
                  @endforeach
                </div>
              </div>
            @else
              <a class="nav-link {{ (request()->is('/') && $navItem->url === '/') || (request()->is(ltrim($navItem->url, '/') . '*') && $navItem->url !== '/') ? 'active' : '' }}"
                href="{{ $navItem->resolved_url }}" target="{{ $navItem->target }}">{{ $navItem->label_en }}</a>
            @endif
          @endforeach
        @else
          <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
          <div class="nav-item">
            <button class="nav-link {{ request()->is('about*') || request()->is('leadership*') ? 'active' : '' }}">
              About <svg class="chevron" viewBox="0 0 10 10">
                <path d="M2 3.5 5 6.5 8 3.5" fill="none" stroke="currentColor" />
              </svg>
            </button>
            <div class="dropdown">
              <a href="{{ url('/about') }}">About the Trust</a>
              <a href="{{ url('/leadership') }}">Leadership & Trustees</a>
            </div>
          </div>
          <div class="nav-item">
            <button class="nav-link {{ request()->is('seva*') ? 'active' : '' }}">
              Seva <svg class="chevron" viewBox="0 0 10 10">
                <path d="M2 3.5 5 6.5 8 3.5" fill="none" stroke="currentColor" />
              </svg>
            </button>
            <div class="dropdown">
              <a href="{{ url('/seva/sanskrit-gurukul') }}">Vedic Sanskrit Vidyalaya</a>
              <a href="{{ url('/seva/gaushala-protection') }}">Gaushala & Cow Care</a>
              <a href="{{ url('/seva') }}">All Seva Projects</a>
            </div>
          </div>
          <div class="nav-item">
            <button class="nav-link {{ request()->is('chitrakoot*') ? 'active' : '' }}">
              Chitrakoot <svg class="chevron" viewBox="0 0 10 10">
                <path d="M2 3.5 5 6.5 8 3.5" fill="none" stroke="currentColor" />
              </svg>
            </button>
            <div class="dropdown">
              <a href="{{ url('/chitrakoot') }}">Sacred Places Guide</a>
              <a href="{{ url('/chitrakoot/kamadgiri-parikrama') }}">Kamadgiri Parikrama</a>
              <a href="{{ url('/chitrakoot/ramghat') }}">Ramghat</a>
            </div>
          </div>
          <div class="nav-item">
            <a class="nav-link {{ request()->is('services*') || request()->is('pooja*') ? 'active' : '' }}"
              href="{{ route('services.index') }}">
              Services <svg class="chevron" viewBox="0 0 10 10">
                <path d="M2 3.5 5 6.5 8 3.5" fill="none" stroke="currentColor" />
              </svg>
            </a>
            <div class="dropdown">
              <a href="{{ route('services.index') }}" style="font-weight: 700; color: #D95D0F;">All Services Overview</a>
              <a href="{{ route('pooja.bookingForm') }}">Book Pooja / Hawan Slot</a>
              <a href="{{ route('pooja.index') }}">All Pooja & Archana Services</a>
              <a href="{{ route('services.aarti') }}">Daily Aarti & Darshan Schedule</a>
              <a href="{{ route('services.atithiVishram') }}">Guest House Stay Enquiry</a>
              <a href="{{ route('services.templeBooking') }}">Premises Booking Enquiry</a>
            </div>
          </div>
          <a class="nav-link {{ request()->is('events*') ? 'active' : '' }}" href="{{ url('/events') }}">Events</a>
          <a class="nav-link {{ request()->is('blog*') ? 'active' : '' }}" href="{{ url('/blog') }}">Blog</a>
          <a class="nav-link {{ request()->is('gallery*') ? 'active' : '' }}" href="{{ url('/gallery') }}">Gallery</a>
          <a class="nav-link {{ request()->is('contact*') ? 'active' : '' }}" href="{{ url('/contact') }}">Contact</a>
        @endif
      </nav>

      <!-- Desktop Right Header Controls -->
      <div class="nav-right">
        @auth
          @if(auth()->user()->hasRole('super_admin', 'content_admin', 'donation_admin', 'membership_admin', 'media_editor'))
            <a class="btn-admin-header" href="{{ url('/admin') }}">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7" />
                <rect x="14" y="3" width="7" height="7" />
                <rect x="14" y="14" width="7" height="7" />
                <rect x="3" y="14" width="7" height="7" />
              </svg>
              Admin Panel
            </a>
          @endif

          <a class="user-pill-header" href="{{ url('/dashboard') }}" title="My Account">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              style="margin-right: 6px;">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
              <circle cx="12" cy="7" r="4" />
            </svg>
            <span>My Account</span>
          </a>

          <form action="{{ url('/logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="logout-btn-header" title="Logout">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                <polyline points="16 17 21 12 16 7" />
                <line x1="21" y1="12" x2="9" y2="12" />
              </svg>
            </button>
          </form>
        @else
          <a class="nav-link nav-auth-desktop nav-login-link" href="{{ url('/login') }}">Login</a>
          <a class="nav-link nav-auth-desktop nav-register-link" href="{{ url('/register') }}">Register</a>
        @endauth

        <a class="donate-btn" href="{{ url('/donate') }}">Donate</a>
        <button class="mobile-toggle" id="openMenu" aria-label="Open menu"><svg viewBox="0 0 24 24">
            <path d="M4 7h16M4 12h16M4 17h16" />
          </svg></button>
      </div>
    </div>
  </header>

  <!-- Fullscreen Mobile Menu Overlay -->
  <!-- Fullscreen Mobile Menu Overlay -->
  <!-- Backdrop Overlay for Mobile Drawer -->
  <div class="mobile-menu-backdrop" id="mobileMenuBackdrop"></div>

  <!-- Animated Subdivided Mobile Menu Drawer -->
  <div class="mobile-menu" id="mobileMenu" role="dialog" aria-modal="true" aria-label="Mobile Navigation Menu">
    <!-- Top Header inside Menu -->
    <div
      style="display: flex; align-items: center; justify-content: space-between; padding-bottom: 16px; margin-bottom: 16px; border-bottom: 1px solid rgba(180,83,9,0.18);">
      <div style="display: flex; align-items: center; gap: 10px;">
        <div
          style="width: 32px; height: 32px; border-radius: 8px; background: rgba(217,93,15,0.12); display: grid; place-items: center; color: #D95D0F;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 51V28L25 7l20 21v23" />
            <path d="M13 51V31l12-12 12 12v20" />
            <path d="M20 51V35h10v16" />
            <path d="M2 52h46" />
            <path d="M25 7V2" />
          </svg>
        </div>
        <div>
          <span
            style="color: #92400E; font-weight: 800; font-size: 13px; letter-spacing: 0.06em; text-transform: uppercase; display: block; line-height: 1.2;">
            {{ $orgSettings->org_name ?? 'Shri Kamadgiri Peetham' }}
          </span>
          <span style="font-size: 10.5px; color: #7A4B29; font-weight: 600;">चित्रकूट धाम • सेवा ट्रस्ट</span>
        </div>
      </div>
      <button class="mobile-menu-close" id="closeMenu" aria-label="Close Mobile Navigation">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
          stroke-linecap="round" stroke-linejoin="round">
          <line x1="18" y1="6" x2="6" y2="18" />
          <line x1="6" y1="6" x2="18" y2="18" />
        </svg>
      </button>
    </div>

    <!-- Quick Navigation Pill Bar (Top 4 shortcuts) -->
    <div class="menu-quick-grid">
      <a href="{{ url('/') }}" class="menu-quick-chip">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
          <polyline points="9 22 9 12 15 12 15 22" />
        </svg>
        <span>होम</span>
      </a>
      <a href="{{ url('/#aarti-timings') }}" class="menu-quick-chip">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10" />
          <polyline points="12 6 12 12 16 14" />
        </svg>
        <span>आरती समय</span>
      </a>
      <a href="{{ url('/chitrakoot/kamadgiri-parikrama') }}" class="menu-quick-chip">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polygon points="12 2 2 22 22 22 12 2" />
        </svg>
        <span>परिक्रमा</span>
      </a>
      <a href="{{ url('/contact') }}" class="menu-quick-chip">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path
            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
        </svg>
        <span>संपर्क</span>
      </a>
    </div>

    <!-- Submenu Accordion Container -->
    <div class="menu-accordion-container" id="menuAccordions">

      <!-- SUBMENU 1: ABOUT TRUST & HERITAGE -->
      <div class="menu-accordion-card">
        <button type="button" class="menu-accordion-header" onclick="toggleMenuAccordion(this)" aria-expanded="false">
          <div class="menu-accordion-title-wrap">
            <div class="menu-accordion-icon">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 21h18" />
                <path d="M5 21V11l7-7 7 7v10" />
                <path d="M9 21v-6a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v6" />
                <circle cx="12" cy="7" r="1" />
              </svg>
            </div>
            <div>
              <span class="menu-accordion-label">ट्रस्ट परिचय</span>
              <span class="menu-accordion-sub">About the Trust & Heritage</span>
            </div>
          </div>
          <div class="menu-accordion-meta">
            <span class="menu-item-count-badge">3 उप-खंड</span>
            <svg class="accordion-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <polyline points="6 9 12 15 18 9" />
            </svg>
          </div>
        </button>
        <div class="menu-accordion-body">
          <a href="{{ url('/about') }}" class="menu-sub-link">
            <span>ट्रस्ट के विषय में एवं इतिहास</span>
            <span class="menu-sub-link-sub">About Trust &rarr;</span>
          </a>
          <a href="{{ url('/leadership') }}" class="menu-sub-link">
            <span>संरक्षक व न्यासी मंडल</span>
            <span class="menu-sub-link-sub">Leadership & Trustees &rarr;</span>
          </a>
          <a href="{{ url('/#about-deity') }}" class="menu-sub-link">
            <span>कामदगिरि महिमा व उद्देश्य</span>
            <span class="menu-sub-link-sub">Sanctum Heritage &rarr;</span>
          </a>
        </div>
      </div>

      <!-- SUBMENU 2: SEVA & GURUKUL MISSIONS -->
      <div class="menu-accordion-card">
        <button type="button" class="menu-accordion-header" onclick="toggleMenuAccordion(this)" aria-expanded="false">
          <div class="menu-accordion-title-wrap">
            <div class="menu-accordion-icon" style="color: #2D6A4F; background: rgba(45,106,79,0.1);">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
              </svg>
            </div>
            <div>
              <span class="menu-accordion-label">सेवा व गुरुकुल</span>
              <span class="menu-accordion-sub">Seva Missions & Sanskrit Gurukul</span>
            </div>
          </div>
          <div class="menu-accordion-meta">
            <span class="menu-item-count-badge" style="color: #2D6A4F;">4 प्रकल्प</span>
            <svg class="accordion-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <polyline points="6 9 12 15 18 9" />
            </svg>
          </div>
        </button>
        <div class="menu-accordion-body">
          <a href="{{ url('/seva/sanskrit-gurukul') }}" class="menu-sub-link">
            <span>ऋषिकुल संस्कृत गुरुकुल एवं विद्यालय</span>
            <span class="menu-sub-link-sub">Gurukul &rarr;</span>
          </a>
          <a href="{{ url('/seva/gaushala-protection') }}" class="menu-sub-link">
            <span>कामदगिरि गौशाला (गौ संरक्षण सेवा)</span>
            <span class="menu-sub-link-sub">Gaushala &rarr;</span>
          </a>
          <a href="{{ url('/seva/religious-events') }}" class="menu-sub-link">
            <span>धार्मिक एवं सांस्कृतिक सेवा प्रकल्प</span>
            <span class="menu-sub-link-sub">Cultural &rarr;</span>
          </a>
          <a href="{{ url('/seva') }}" class="menu-sub-link">
            <span>समस्त सेवा प्रकल्प एवं अन्नक्षेत्र</span>
            <span class="menu-sub-link-sub">All Sevas &rarr;</span>
          </a>
        </div>
      </div>

      <!-- SUBMENU 3: CHITRAKOOT SACRED DHAM -->
      <div class="menu-accordion-card">
        <button type="button" class="menu-accordion-header" onclick="toggleMenuAccordion(this)" aria-expanded="false">
          <div class="menu-accordion-title-wrap">
            <div class="menu-accordion-icon" style="color: #B45309; background: rgba(180,83,9,0.1);">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M8 3l4 8 5-5 5 15H2L8 3z" />
              </svg>
            </div>
            <div>
              <span class="menu-accordion-label">चित्रकूट दर्शन</span>
              <span class="menu-accordion-sub">Sacred Pilgrimage & Tirth Guide</span>
            </div>
          </div>
          <div class="menu-accordion-meta">
            <span class="menu-item-count-badge">3 तीर्थ</span>
            <svg class="accordion-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <polyline points="6 9 12 15 18 9" />
            </svg>
          </div>
        </button>
        <div class="menu-accordion-body">
          <a href="{{ url('/chitrakoot') }}" class="menu-sub-link">
            <span>समस्त तीर्थ स्थल दर्शिका</span>
            <span class="menu-sub-link-sub">Places Guide &rarr;</span>
          </a>
          <a href="{{ url('/chitrakoot/kamadgiri-parikrama') }}" class="menu-sub-link">
            <span>कामदगिरि परिक्रमा पथ एवं मंदिर</span>
            <span class="menu-sub-link-sub">Parikrama &rarr;</span>
          </a>
          <a href="{{ url('/chitrakoot/ramghat') }}" class="menu-sub-link">
            <span>रामघाट एवं पवित्र मंदाकिनी तट</span>
            <span class="menu-sub-link-sub">Ramghat &rarr;</span>
          </a>
        </div>
      </div>

      <!-- SUBMENU 4: TEMPLE SERVICES & DARSHAN -->
      <div class="menu-accordion-card">
        <button type="button" class="menu-accordion-header" onclick="toggleMenuAccordion(this)" aria-expanded="false">
          <div class="menu-accordion-title-wrap">
            <div class="menu-accordion-icon" style="color: #EA580C; background: rgba(234,88,12,0.1);">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 2c1.5 3 4 5 4 9a6 6 0 1 1-12 0c0-4 2.5-6 4-9 1 2 2 3 4 0z" />
              </svg>
            </div>
            <div>
              <span class="menu-accordion-label">मंदिर सेवाएं व दर्शन</span>
              <span class="menu-accordion-sub">Temple Services, Stay & Pooja</span>
            </div>
          </div>
          <div class="menu-accordion-meta">
            <span class="menu-item-count-badge" style="color: #EA580C;">5 सेवाएं</span>
            <svg class="accordion-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <polyline points="6 9 12 15 18 9" />
            </svg>
          </div>
        </button>
        <div class="menu-accordion-body">
          <a href="{{ route('services.index') }}" class="menu-sub-link"
            style="background: rgba(234,88,12,0.06); border-radius: 6px; font-weight: 700;">
            <span style="color: #C2410C;">★ समस्त मंदिर सेवाएं एवं सुविधा केंद्र</span>
            <span class="menu-sub-link-sub">All Services &rarr;</span>
          </a>
          <a href="{{ route('pooja.bookingForm') }}" class="menu-sub-link">
            <span>पूजा व हवन ऑनलाइन आरक्षण (स्लॉट बुक करें)</span>
            <span class="menu-sub-link-sub">Book Pooja &rarr;</span>
          </a>
          <a href="{{ route('services.aarti') }}" class="menu-sub-link">
            <span>नित्य महाआरती व दर्शन समय सारणी</span>
            <span class="menu-sub-link-sub">Aarti Timetable &rarr;</span>
          </a>
          <a href="{{ route('services.atithiVishram') }}" class="menu-sub-link">
            <span>अतिथि विश्राम गृह पूछताछ (तीर्थ प्रवास)</span>
            <span class="menu-sub-link-sub">Atithi Stay &rarr;</span>
          </a>
          <a href="{{ route('services.templeBooking') }}" class="menu-sub-link">
            <span>मंदिर परिसर व आयोजन आरक्षण पूछताछ (विवाह, हवन, कथा)</span>
            <span class="menu-sub-link-sub">Event Booking &rarr;</span>
          </a>
        </div>
      </div>

      <!-- SUBMENU 5: MEDIA, EVENTS & COMMUNITY -->
      <div class="menu-accordion-card">
        <button type="button" class="menu-accordion-header" onclick="toggleMenuAccordion(this)" aria-expanded="false">
          <div class="menu-accordion-title-wrap">
            <div class="menu-accordion-icon" style="color: #7A4B29; background: rgba(122,75,41,0.1);">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                <line x1="16" y1="2" x2="16" y2="6" />
                <line x1="8" y1="2" x2="8" y2="6" />
                <line x1="3" y1="10" x2="21" y2="10" />
              </svg>
            </div>
            <div>
              <span class="menu-accordion-label">उत्सव, साहित्य व दीर्घा</span>
              <span class="menu-accordion-sub">Events, Blog, Gallery & Volunteers</span>
            </div>
          </div>
          <div class="menu-accordion-meta">
            <span class="menu-item-count-badge">4 अनुभाग</span>
            <svg class="accordion-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <polyline points="6 9 12 15 18 9" />
            </svg>
          </div>
        </button>
        <div class="menu-accordion-body">
          <a href="{{ url('/events') }}" class="menu-sub-link">
            <span>आगामी धार्मिक उत्सव व कार्यक्रम</span>
            <span class="menu-sub-link-sub">Events &rarr;</span>
          </a>
          <a href="{{ url('/blog') }}" class="menu-sub-link">
            <span>धार्मिक समाचार, आलेख व कथा साहित्य</span>
            <span class="menu-sub-link-sub">Spiritual Blog &rarr;</span>
          </a>
          <a href="{{ url('/gallery') }}" class="menu-sub-link">
            <span>चित्र एवं वीडियो दीर्घा</span>
            <span class="menu-sub-link-sub">Gallery &rarr;</span>
          </a>
          <a href="{{ url('/volunteer') }}" class="menu-sub-link">
            <span>स्वयंसेवक पंजीयन (Volunteer Seva)</span>
            <span class="menu-sub-link-sub">Volunteer &rarr;</span>
          </a>
        </div>
      </div>

      <!-- DIRECT LINK CARD: CONTACT -->
      <a href="{{ url('/contact') }}" class="menu-direct-card">
        <div class="menu-accordion-title-wrap">
          <div class="menu-accordion-icon">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
              <circle cx="12" cy="10" r="3" />
            </svg>
          </div>
          <div>
            <span class="menu-accordion-label">मार्गदर्शन एवं संपर्क</span>
            <span class="menu-accordion-sub">Contact Us & Temple Office</span>
          </div>
        </div>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#D95D0F" stroke-width="2.2">
          <path d="M5 12h14M12 5l7 7-7 7" />
        </svg>
      </a>

    </div>

    <!-- High-Prominence Donation CTA Button inside Mobile Menu -->
    <div style="margin: 20px 0 16px;">
      <a href="{{ url('/donate') }}"
        style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; background: linear-gradient(135deg, #D95D0F 0%, #EA580C 100%); color: #ffffff; font-weight: 800; font-size: 15px; padding: 13px 18px; border-radius: 9999px; text-decoration: none; box-shadow: 0 4px 18px rgba(217,93,15,0.35); transition: transform 0.2s;"
        onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <path
            d="M12 21s-7-4.6-9-9.2C1.2 7.8 3.4 5 6.4 5c2 0 3.5 1.1 5.6 3.1C14.1 6.1 15.6 5 17.6 5 20.6 5 22.8 7.8 21 11.8 19 16.4 12 21 12 21z" />
        </svg>
        <span>दान एवं सहयोग (Donate Now) &rarr;</span>
      </a>
    </div>

    <!-- Account & User Auth Section at Bottom -->
    <div style="margin-top: 20px; padding-top: 18px; border-top: 1px solid rgba(180,83,9,0.18);">
      @auth
        <div
          style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px; padding: 12px 14px; background: #FFFDFC; border: 1px solid rgba(180,83,9,0.2); border-radius: 12px;">
          <div
            style="width: 38px; height: 38px; border-radius: 50%; background: #FAF4ED; border: 1px solid rgba(180,83,9,0.25); color: #D95D0F; display: grid; place-items: center; font-weight: 800; font-size: 15px;">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
          </div>
          <div style="flex: 1; overflow: hidden;">
            <div
              style="font-weight: 700; font-size: 14px; color: #2B1A12; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
              {{ auth()->user()->name }}</div>
            <div style="font-size: 11px; color: #7A4B29;">{{ auth()->user()->email }}</div>
          </div>
        </div>

        <a href="{{ url('/dashboard') }}"
          style="display: flex; align-items: center; gap: 10px; padding: 12px 0; color: #2B1A12; font-size: 14px; font-weight: 600; text-decoration: none; border-bottom: 1px solid rgba(180,83,9,0.12);">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
            <circle cx="12" cy="7" r="4" />
          </svg>
          My Account & Receipts (मेरा खाता व रसीदें)
        </a>

        @if(auth()->user()->hasRole('super_admin', 'content_admin', 'donation_admin', 'membership_admin', 'media_editor'))
          <a href="{{ url('/admin') }}"
            style="display: flex; align-items: center; gap: 10px; padding: 12px 0; color: #B45309; font-size: 14px; font-weight: 700; text-decoration: none; border-bottom: 1px solid rgba(180,83,9,0.12);">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="3" width="7" height="7" />
              <rect x="14" y="3" width="7" height="7" />
              <rect x="14" y="14" width="7" height="7" />
              <rect x="3" y="14" width="7" height="7" />
            </svg>
            CMS Admin Panel (एडमिन डैशबोर्ड)
          </a>
        @endif

        <form action="{{ url('/logout') }}" method="POST" style="margin-top: 14px;">
          @csrf
          <button type="submit"
            style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; background: rgba(220,53,69,0.08); border: 1px solid rgba(220,53,69,0.3); color: #dc3545; font-size: 14px; padding: 11px 16px; cursor: pointer; border-radius: 9999px; font-weight: 700;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
              <polyline points="16 17 21 12 16 7" />
              <line x1="21" y1="12" x2="9" y2="12" />
            </svg>
            Logout (लॉगआउट)
          </button>
        </form>
      @else
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
          <a href="{{ url('/login') }}"
            style="display: flex; align-items: center; justify-content: center; padding: 12px; background: #FFFDFC; color: #2B1A12; font-size: 14px; font-weight: 700; text-decoration: none; border: 1.5px solid rgba(180,83,9,0.25); border-radius: 9999px; text-align: center; box-shadow: 0 2px 6px rgba(0,0,0,0.03);">
            Login (लॉगिन)
          </a>
          <a href="{{ url('/register') }}"
            style="display: flex; align-items: center; justify-content: center; padding: 12px; background: linear-gradient(135deg, #D95D0F 0%, #EA580C 100%); color: #ffffff; font-size: 14px; font-weight: 700; text-decoration: none; border-radius: 9999px; text-align: center; box-shadow: 0 4px 14px rgba(217,93,15,0.3);">
            Register (पंजीकरण)
          </a>
        </div>
      @endauth
    </div>
  </div>

  <main id="main-content">
    @yield('content')
  </main>

  <!-- Footer -->
  <footer id="contact">
    <div class="container">

      <!-- Newsletter Subscription Banner -->
      <div class="footer-newsletter-banner">
        <div class="newsletter-content">
          <h3 class="newsletter-title">आध्यात्मिक प्रेरणा एवं ट्रस्ट सेवा संदेश</h3>
          <p class="newsletter-desc">नित्य कामदगिरि दर्शन, आगामी उत्सव, और वेद-वेदांग प्रकाशन सीधे अपने ईमेल पर प्राप्त
            करें।</p>
        </div>
        <form action="{{ route('newsletter.subscribe') }}" method="POST" class="newsletter-form">
          @csrf
          <input type="email" name="email" required placeholder="Enter your email..." class="newsletter-input">
          <button type="submit" class="newsletter-btn" style="white-space: nowrap;">
            Subscribe &rarr;
          </button>
        </form>
      </div>

      <div class="footer-grid">
        <div class="footer-brand">
          <div class="brand">
            <div class="brand-mark">
              @if(!empty($orgSettings->logo_path))
                <img class="brand-logo-img" src="{{ $siteLogoUrl }}" alt="{{ $orgSettings->org_name ?? 'Logo' }}"
                  style="max-height: 44px; width: auto; object-fit: contain;">
              @else
                <svg viewBox="0 0 50 58">
                  <path d="M5 51V28L25 7l20 21v23" />
                  <path d="M13 51V31l12-12 12 12v20" />
                  <path d="M20 51V35h10v16" />
                  <path d="M2 52h46" />
                  <path d="M25 7V2" />
                </svg>
              @endif
            </div>
            <div class="brand-name">
              <span class="brand-title">{{ $orgSettings->org_name ?? 'Shri Kamadgiri Peetham Sewa Trust' }}</span>
              <span class="brand-sub">{{ $siteTagline ?? 'Sewa Trust • Chitrakoot Dham' }}</span>
            </div>
          </div>
          <p>सनातन संस्कृति, शिक्षा, गौसेवा, पर्यावरण और मानव कल्याण के लिए समर्पित सेवा परिवार।</p>
        </div>
        <div>
          <div class="footer-title">Explore</div>
          <div class="footer-links">
            <a href="{{ url('/about') }}">About Trust</a>
            <a href="{{ url('/pooja') }}">Pooja & Aarti Booking</a>
            <a href="{{ url('/volunteer') }}">Volunteer Seva</a>
            <a href="{{ url('/seva') }}">Seva Projects</a>
            <a href="{{ url('/chitrakoot') }}">Chitrakoot Guide</a>
            <a href="{{ url('/events') }}">Events</a>
          </div>
        </div>
        <div>
          <div class="footer-title">Connect</div>
          <div class="footer-links">
            <a href="{{ $orgSettings->instagram_url ?? 'https://instagram.com' }}" target="_blank"
              rel="noopener">Instagram</a>
            <a href="{{ $orgSettings->youtube_url ?? 'https://youtube.com' }}" target="_blank"
              rel="noopener">YouTube</a>
            <a href="{{ $orgSettings->facebook_url ?? 'https://facebook.com' }}" target="_blank"
              rel="noopener">Facebook</a>
            <a href="{{ $orgSettings->whatsapp_url ?? 'https://whatsapp.com' }}" target="_blank" rel="noopener">WhatsApp
              Channel</a>
          </div>
        </div>
        <div>
          <div class="footer-title">Contact</div>
          <div class="footer-links">
            <a
              href="{{ url('/contact') }}">{{ $orgSettings->address ?? 'Kamadgiri Parikrama Marg, Chitrakoot Dham' }}</a>
            <a
              href="tel:{{ preg_replace('/[^0-9+]/', '', $orgSettings->phone ?? '+919415000000') }}">{{ $orgSettings->phone ?? '+91 94150 00000' }}</a>
            <a href="mailto:{{ $orgSettings->email ?? 'info@kamadgiripeetham.org' }}">{{ $orgSettings->email ??
              'info@kamadgiripeetham.org' }}</a>
            <a href="{{ url('/donate') }}">Support the Trust</a>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <span>© {{ date('Y') }} {{ $orgSettings->org_name ?? 'Shri Kamadgiri Peetham Sewa Trust' }}. All rights
          reserved.</span>
        <span><a href="{{ url('/privacy-policy') }}" style="color: inherit; text-decoration: underline;">Privacy
            Policy</a> &middot; <a href="{{ url('/about') }}" style="color: inherit;">Trust Information</a> &middot; <a
            href="{{ url('/sitemap.xml') }}" style="color: inherit;">Sitemap</a></span>
      </div>
    </div>
  </footer>

  <!-- Mobile Bottom App Bar -->
  <nav class="mobile-bar {{ request()->is('/') ? 'mobile-bar-hero-hidden' : '' }}"
    aria-label="Mobile Bottom Navigation">
    <a class="{{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
      <svg viewBox="0 0 24 24">
        <path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z" />
      </svg>Home
    </a>
    <a class="{{ request()->is('seva*') ? 'active' : '' }}" href="{{ url('/seva') }}">
      <svg viewBox="0 0 24 24">
        <path d="M12 3 4 7v5c0 5 3.4 8 8 9 4.6-1 8-4 8-9V7z" />
      </svg>Seva
    </a>
    <a class="{{ request()->is('pooja*') ? 'active' : '' }}" href="{{ url('/pooja') }}">
      <svg viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="5" />
        <path
          d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42" />
      </svg>Pooja
    </a>
    <a class="donate-mobile {{ request()->is('donate*') ? 'active' : '' }}" href="{{ url('/donate') }}">
      <svg viewBox="0 0 24 24">
        <path
          d="M12 21s-7-4.6-9-9.2C1.2 7.8 3.4 5 6.4 5c2 0 3.5 1.1 5.6 3.1C14.1 6.1 15.6 5 17.6 5 20.6 5 22.8 7.8 21 11.8 19 16.4 12 21 12 21z" />
      </svg>Donate
    </a>
    <a class="{{ request()->is('dashboard*', 'login*', 'register*', 'account*', 'user*') ? 'active' : '' }}"
      href="{{ auth()->check() ? url('/dashboard') : url('/login') }}">
      <svg viewBox="0 0 24 24">
        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
        <circle cx="12" cy="7" r="4" />
      </svg>Profile
    </a>
  </nav>

  <script>
    const loader = document.getElementById('loader');
    const dismissLoader = () => {
      if (loader && !loader.classList.contains('done')) {
        loader.classList.add('done');
        document.body.classList.remove('loading');
      }
    };
    window.addEventListener('load', () => setTimeout(dismissLoader, 300));
    document.addEventListener('DOMContentLoaded', () => setTimeout(dismissLoader, 800));
    setTimeout(dismissLoader, 1800);

    const header = document.getElementById('header');
    const menu = document.getElementById('mobileMenu');
    const backdrop = document.getElementById('mobileMenuBackdrop');
    const openMenu = document.getElementById('openMenu');
    const closeMenu = document.getElementById('closeMenu');
    const hideMenu = () => {
      menu?.classList.remove('open');
      backdrop?.classList.remove('open');
      document.body.classList.remove('menu-open');
    };

    openMenu?.addEventListener('click', () => {
      menu?.classList.add('open');
      backdrop?.classList.add('open');
      document.body.classList.add('menu-open');
    });
    closeMenu?.addEventListener('click', hideMenu);
    backdrop?.addEventListener('click', hideMenu);
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && menu?.classList.contains('open')) hideMenu();
    });
    menu?.querySelectorAll('a').forEach(a => a.addEventListener('click', hideMenu));

    window.toggleMenuAccordion = function (btn) {
      const card = btn.closest('.menu-accordion-card');
      if (!card) return;
      const wasActive = card.classList.contains('active');

      // Close sibling accordions for clean accordion UX
      document.querySelectorAll('.menu-accordion-card.active').forEach(c => {
        if (c !== card) {
          c.classList.remove('active');
          c.querySelector('.menu-accordion-header')?.setAttribute('aria-expanded', 'false');
        }
      });

      if (!wasActive) {
        card.classList.add('active');
        btn.setAttribute('aria-expanded', 'true');
        if (typeof anime !== 'undefined' && anime.animate) {
          const links = card.querySelectorAll('.menu-sub-link');
          if (links.length > 0) {
            anime.animate(links, {
              translateX: [-10, 0],
              opacity: [0, 1],
              delay: anime.stagger(30),
              duration: 320,
              ease: 'outQuad'
            });
          }
        }
      } else {
        card.classList.remove('active');
        btn.setAttribute('aria-expanded', 'false');
      }
    };

    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: .1, rootMargin: '0px 0px -40px 0px' });
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

    /* 3D Temple Door Opening Scroll Observer */
    const doorSection = document.getElementById('doorReveal');
    if (doorSection) {
      const doorObserver = new IntersectionObserver(entries => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            doorSection.classList.add('open');
          }
        });
      }, { threshold: 0.3 });
      doorObserver.observe(doorSection);
    }

    /* Unified Navigation Scroll Controller: Transparent on Hero, Rising Bottom Bar, Auto-Hide on Scroll */
    (function initNavigationScroll() {
      const headerEl = document.getElementById('header');
      const mobileBar = document.querySelector('.mobile-bar');
      const heroSection = document.getElementById('hero-interactive-section');
      const hasHero = !!heroSection;

      let lastScrollY = Math.max(0, window.pageYOffset || document.documentElement.scrollTop);
      let ticking = false;
      const threshold = 8;
      const heroThreshold = 220; // Distance scrolled before bottom bar rises on hero pages

      function updateNavbars() {
        const currentScrollY = Math.max(0, window.pageYOffset || document.documentElement.scrollTop);
        const diff = currentScrollY - lastScrollY;

        // 1. Transparent Header on Hero vs Solid Header on Scroll
        if (hasHero) {
          if (currentScrollY < 60) {
            headerEl?.classList.add('header-transparent');
            headerEl?.classList.remove('scrolled');
          } else {
            headerEl?.classList.remove('header-transparent');
            headerEl?.classList.add('scrolled');
          }

          // 2. Bottom Mobile Bar: Hidden while on Hero fold, rises only after scrolling down
          if (currentScrollY < heroThreshold) {
            mobileBar?.classList.add('mobile-bar-hero-hidden');
          } else {
            mobileBar?.classList.remove('mobile-bar-hero-hidden');
          }
        } else {
          // Standard pages without full hero section
          if (currentScrollY > 40) {
            headerEl?.classList.add('scrolled');
          } else {
            headerEl?.classList.remove('scrolled');
          }
        }

        // 3. Auto-Hide Bottom & Top Navbar on Scroll Down, Reveal on Scroll Up
        if (diff > threshold && currentScrollY > 70) {
          // Scrolling DOWN -> auto-hide both top header and bottom bar
          headerEl?.classList.add('header-hidden');
          mobileBar?.classList.add('nav-hidden');
        } else if (diff < -threshold) {
          // Scrolling UP -> reveal top header; reveal bottom bar only if past hero threshold
          headerEl?.classList.remove('header-hidden');
          if (!hasHero || currentScrollY >= heroThreshold) {
            mobileBar?.classList.remove('nav-hidden');
          }
        }

        // 4. At the very top fold: always keep header visible (transparent on hero), bottom bar hidden on hero
        if (currentScrollY <= 40) {
          headerEl?.classList.remove('header-hidden');
          if (hasHero) {
            headerEl?.classList.add('header-transparent');
            headerEl?.classList.remove('scrolled');
            mobileBar?.classList.add('mobile-bar-hero-hidden');
          }
        }

        lastScrollY = currentScrollY;
        ticking = false;
      }

      // Initial execution
      updateNavbars();

      window.addEventListener('scroll', () => {
        if (!ticking) {
          window.requestAnimationFrame(updateNavbars);
          ticking = true;
        }
      }, { passive: true });
    })();
  </script>

  <!-- Anime.js v4 Library -->
  <script src="https://cdn.jsdelivr.net/npm/animejs/dist/bundles/anime.umd.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      if (typeof anime === 'undefined') return;
      const { animate, stagger } = anime;

      // 1. Subtle Sacred Float on Header Brand Logo Emblem
      const brandLogo = document.querySelector('.brand-logo svg, .logo-icon svg');
      if (brandLogo) {
        animate(brandLogo, {
          translateY: [-2, 2],
          duration: 3000,
          loop: true,
          alternate: true,
          ease: 'inOutSine'
        });
      }

      // 2. Mobile Bottom App Bar Entrance & Micro-Interactions
      const mobileNavItems = document.querySelectorAll('.mobile-bar a');
      if (mobileNavItems.length > 0) {
        animate(mobileNavItems, {
          translateY: [14, 0],
          opacity: [0, 1],
          delay: stagger(40, { start: 180 }),
          duration: 500,
          ease: 'outQuad'
        });

        mobileNavItems.forEach(item => {
          item.addEventListener('click', () => {
            animate(item.querySelector('svg') || item, {
              scale: [1, 0.8, 1.12, 1],
              duration: 400,
              ease: 'outElastic(1, .6)'
            });
          });
        });
      }

      // 3. Staggered Scroll-Triggered Reveal for Cards & Sections
      const revealCards = document.querySelectorAll(
        '.mobile-seva-card, .pooja-booking-card, .pooja-detail-card, .destination-card, ' +
        '.event-card, .blog-card, .gallery-item, .dashboard-card, .leadership-card'
      );

      if (revealCards.length > 0 && 'IntersectionObserver' in window) {
        const cardObserver = new IntersectionObserver((entries) => {
          const visibleTargets = [];
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              visibleTargets.push(entry.target);
              cardObserver.unobserve(entry.target);
            }
          });

          if (visibleTargets.length > 0) {
            animate(visibleTargets, {
              translateY: [22, 0],
              opacity: [0, 1],
              delay: stagger(65),
              duration: 650,
              ease: 'outCubic'
            });
          }
        }, { threshold: 0.1, rootMargin: '0px 0px -20px 0px' });

        revealCards.forEach(card => cardObserver.observe(card));
      }

      // 4. Diya / Sacred Aura Glow Pulse on Primary CTA Buttons
      const primaryCtas = document.querySelectorAll('.btn.primary, .btn-pooja-submit, .btn-auth-submit');
      if (primaryCtas.length > 0) {
        animate(primaryCtas, {
          boxShadow: [
            '0 8px 24px rgba(217,93,15,0.22)',
            '0 12px 32px rgba(217,93,15,0.40)',
            '0 8px 24px rgba(217,93,15,0.22)'
          ],
          duration: 2800,
          loop: true,
          ease: 'inOutSine'
        });
      }

      // 5. Tactile Micro-Interactions on Interactive Buttons & Pills
      const interactiveElements = document.querySelectorAll(
        '.btn, .amount-btn, .filter-chip, .stepper-capsule, .mobile-category-chip, .seva-tag'
      );
      interactiveElements.forEach(el => {
        el.addEventListener('mouseenter', () => {
          animate(el, { translateY: -2, duration: 200, ease: 'outQuad' });
        });
        el.addEventListener('mouseleave', () => {
          animate(el, { translateY: 0, duration: 250, ease: 'outQuad' });
        });
        el.addEventListener('mousedown', () => {
          animate(el, { scale: 0.95, duration: 120, ease: 'outQuad' });
        });
        el.addEventListener('mouseup', () => {
          animate(el, { scale: 1, duration: 280, ease: 'outElastic(1, .6)' });
        });
      });

      // 6. Smooth Number Counter Animation for Stat Metrics
      const counterElements = document.querySelectorAll(
        '.stat-number, .metric-number, .stat-val, .counter-value, [data-counter]'
      );
      if (counterElements.length > 0 && 'IntersectionObserver' in window) {
        const counterObserver = new IntersectionObserver((entries) => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              const el = entry.target;
              const rawText = el.innerText.trim();
              const match = rawText.match(/([0-9,]+)/);
              if (match) {
                const targetNumber = parseInt(match[1].replace(/,/g, ''), 10);
                if (!isNaN(targetNumber) && targetNumber > 0) {
                  const prefix = rawText.substring(0, match.index);
                  const suffix = rawText.substring(match.index + match[0].length);
                  const counterObj = { val: 0 };
                  animate(counterObj, {
                    val: targetNumber,
                    duration: 1600,
                    ease: 'outExpo',
                    onUpdate: () => {
                      el.innerText = prefix + Math.round(counterObj.val).toLocaleString() + suffix;
                    }
                  });
                }
              }
              counterObserver.unobserve(el);
            }
          });
        }, { threshold: 0.2 });

        counterElements.forEach(c => counterObserver.observe(c));
      }

      // 7. Mobile Drawer Stagger Entrance on Open
      const mobileMenuEl = document.getElementById('mobileMenu');
      const openMenuBtn = document.getElementById('openMenu');
      if (mobileMenuEl && openMenuBtn) {
        openMenuBtn.addEventListener('click', () => {
          const quickChips = mobileMenuEl.querySelectorAll('.menu-quick-chip');
          if (quickChips.length > 0) {
            animate(quickChips, {
              translateY: [12, 0],
              opacity: [0, 1],
              delay: stagger(35, { start: 120 }),
              duration: 380,
              ease: 'outQuad'
            });
          }

          const menuCards = mobileMenuEl.querySelectorAll('.menu-accordion-card, .menu-direct-card');
          if (menuCards.length > 0) {
            animate(menuCards, {
              translateX: [20, 0],
              opacity: [0, 1],
              delay: stagger(40, { start: 180 }),
              duration: 420,
              ease: 'outQuad'
            });
          }
        });
      }
    });
  </script>

  <!-- Global Facebook-style Magazine Image Lightbox Modal -->
  <div id="fbGalleryLightbox" class="fb-lightbox" role="dialog" aria-modal="true"
    aria-label="Image Fullscreen Lightbox">
    <div class="fb-lb-header">
      <span class="fb-lb-counter" id="fbLbCounter">1 / 1</span>
      <button type="button" class="fb-lb-close" onclick="closeFbGallery()" aria-label="Close Lightbox">&times;</button>
    </div>
    <button type="button" class="fb-lb-nav-btn fb-lb-prev" id="fbLbPrev" onclick="navigateFbGallery(-1)"
      aria-label="Previous Photo">&#8249;</button>
    <div class="fb-lb-content" onclick="event.stopPropagation()">
      <img id="fbLbImg" class="fb-lb-image" src="" alt="Full Screen Photo">
      <div id="fbLbCaption"
        style="color: #FDBA74; font-family: var(--dev, serif); font-size: 14px; margin-top: 14px; text-align: center; font-style: italic;">
      </div>
    </div>
    <button type="button" class="fb-lb-nav-btn fb-lb-next" id="fbLbNext" onclick="navigateFbGallery(1)"
      aria-label="Next Photo">&#8250;</button>
  </div>

  <script>
    let activeGalleryImages = [];
    let activeGalleryIndex = 0;
    let activeGalleryCaption = '';

    function openFbGallery(galleryId, initialIndex) {
      const container = document.getElementById(galleryId);
      if (!container) return;
      try {
        const rawJson = container.getAttribute('data-gallery-images');
        activeGalleryImages = JSON.parse(rawJson);
      } catch (e) {
        activeGalleryImages = [];
      }
      if (!activeGalleryImages.length) return;

      const parentWrap = container.closest('.fb-mosaic-wrapper');
      const captionEl = parentWrap ? parentWrap.querySelector('.fb-mosaic-caption') : null;
      activeGalleryCaption = captionEl ? captionEl.innerText : '';

      activeGalleryIndex = initialIndex >= 0 && initialIndex < activeGalleryImages.length ? initialIndex : 0;
      updateFbGalleryView();

      const modal = document.getElementById('fbGalleryLightbox');
      if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
      }
    }

    function openSingleImageLightbox(src, caption) {
      activeGalleryImages = [src];
      activeGalleryIndex = 0;
      activeGalleryCaption = caption || '';
      updateFbGalleryView();

      const modal = document.getElementById('fbGalleryLightbox');
      if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
      }
    }

    function updateFbGalleryView() {
      if (!activeGalleryImages.length) return;
      const img = document.getElementById('fbLbImg');
      const counter = document.getElementById('fbLbCounter');
      const caption = document.getElementById('fbLbCaption');
      const prevBtn = document.getElementById('fbLbPrev');
      const nextBtn = document.getElementById('fbLbNext');

      if (img) {
        img.src = activeGalleryImages[activeGalleryIndex];
      }
      if (counter) {
        counter.textContent = (activeGalleryIndex + 1) + ' / ' + activeGalleryImages.length;
      }
      if (caption) {
        caption.textContent = activeGalleryCaption || '';
        caption.style.display = activeGalleryCaption ? 'block' : 'none';
      }

      if (prevBtn && nextBtn) {
        if (activeGalleryImages.length <= 1) {
          prevBtn.style.display = 'none';
          nextBtn.style.display = 'none';
        } else {
          prevBtn.style.display = 'grid';
          nextBtn.style.display = 'grid';
        }
      }
    }

    function navigateFbGallery(dir) {
      if (!activeGalleryImages.length) return;
      activeGalleryIndex = (activeGalleryIndex + dir + activeGalleryImages.length) % activeGalleryImages.length;
      updateFbGalleryView();
    }

    function closeFbGallery() {
      const modal = document.getElementById('fbGalleryLightbox');
      if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
      }
    }

    document.addEventListener('keydown', function (e) {
      const modal = document.getElementById('fbGalleryLightbox');
      if (!modal || !modal.classList.contains('active')) return;

      if (e.key === 'Escape') {
        closeFbGallery();
      } else if (e.key === 'ArrowLeft') {
        navigateFbGallery(-1);
      } else if (e.key === 'ArrowRight') {
        navigateFbGallery(1);
      }
    });

    document.addEventListener('DOMContentLoaded', function () {
      const modal = document.getElementById('fbGalleryLightbox');
      if (modal) {
        modal.addEventListener('click', function (e) {
          if (e.target === modal) {
            closeFbGallery();
          }
        });

        let touchStartX = 0;
        let touchEndX = 0;
        modal.addEventListener('touchstart', function (e) {
          if (e.changedTouches && e.changedTouches[0]) {
            touchStartX = e.changedTouches[0].screenX;
          }
        }, { passive: true });
        modal.addEventListener('touchend', function (e) {
          if (e.changedTouches && e.changedTouches[0]) {
            touchEndX = e.changedTouches[0].screenX;
            const diff = touchEndX - touchStartX;
            if (Math.abs(diff) > 40) {
              if (diff > 0) {
                navigateFbGallery(-1);
              } else {
                navigateFbGallery(1);
              }
            }
          }
        }, { passive: true });
      }
    });
  </script>
  @stack('scripts')
</body>

</html>