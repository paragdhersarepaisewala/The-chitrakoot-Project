<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="श्री कामदगिरि पीठम् सेवा ट्रस्ट, चित्रकूट धाम — सनातन संस्कृति, वैदिक शिक्षा, गौसेवा और पर्यावरण संरक्षण।">
  <meta name="robots" content="index, follow">
  <meta name="theme-color" content="#1c1511">
  <title>@yield('title', 'Shri Kamadgiri Peetham Sewa Trust | Chitrakoot Dham')</title>

  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  @stack('styles')
</head>

<body class="loading">

  <!-- Entrance loader -->
  <div class="loader" id="loader" aria-label="Loading">
    <div class="loader-inner">
      <div class="loader-mark">
        <svg viewBox="0 0 50 58">
          <path d="M5 51V28L25 7l20 21v23"/>
          <path d="M13 51V31l12-12 12 12v20"/>
          <path d="M20 51V35h10v16"/>
          <path d="M2 52h46"/>
          <path d="M25 7V2"/>
        </svg>
      </div>
      <div class="loader-name">Shri Kamadgiri Peetham<br>Sewa Trust</div>
      <div class="loader-sub">CHITRAKOOT DHAM</div>
      <div class="loader-line"><span></span></div>
    </div>
  </div>

  <!-- Header -->
  <header id="header">
    <div class="container nav">
      <a class="brand" href="{{ url('/') }}">
        <div class="brand-mark">
          <svg viewBox="0 0 50 58">
            <path d="M5 51V28L25 7l20 21v23"/><path d="M13 51V31l12-12 12 12v20"/>
            <path d="M20 51V35h10v16"/><path d="M2 52h46"/><path d="M25 7V2"/>
          </svg>
        </div>
        <div class="brand-name">Shri Kamadgiri Peetham<br>Sewa Trust<small>CHITRAKOOT DHAM</small></div>
      </a>

      <!-- Desktop Navigation -->
      <nav class="nav-links">
        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>

        <div class="nav-item">
          <button class="nav-link {{ request()->is('about*') || request()->is('leadership*') ? 'active' : '' }}">
            About <svg class="chevron" viewBox="0 0 10 10"><path d="M2 3.5 5 6.5 8 3.5" fill="none" stroke="currentColor"/></svg>
          </button>
          <div class="dropdown">
            <a href="{{ url('/about') }}">About the Trust</a>
            <a href="{{ url('/leadership') }}">Leadership & Trustees</a>
          </div>
        </div>

        <div class="nav-item">
          <button class="nav-link {{ request()->is('seva*') ? 'active' : '' }}">
            Seva <svg class="chevron" viewBox="0 0 10 10"><path d="M2 3.5 5 6.5 8 3.5" fill="none" stroke="currentColor"/></svg>
          </button>
          <div class="dropdown">
            <a href="{{ url('/seva/gaushala-protection') }}">Gaushala & Cow Care</a>
            <a href="{{ url('/seva/sanskrit-gurukul') }}">Sanskrit Vidyalaya</a>
            <a href="{{ url('/seva/religious-events') }}">Religious Events</a>
            <a href="{{ url('/seva') }}">All Seva Projects</a>
          </div>
        </div>

        <div class="nav-item">
          <button class="nav-link {{ request()->is('chitrakoot*') ? 'active' : '' }}">
            Chitrakoot <svg class="chevron" viewBox="0 0 10 10"><path d="M2 3.5 5 6.5 8 3.5" fill="none" stroke="currentColor"/></svg>
          </button>
          <div class="dropdown">
            <a href="{{ url('/chitrakoot') }}">Sacred Places Guide</a>
            <a href="{{ url('/chitrakoot/kamadgiri-parikrama') }}">Kamadgiri Parikrama</a>
            <a href="{{ url('/chitrakoot/ramghat') }}">Ramghat</a>
          </div>
        </div>

        <a class="nav-link {{ request()->is('events*') ? 'active' : '' }}" href="{{ url('/events') }}">Events</a>
        <a class="nav-link {{ request()->is('blog*') ? 'active' : '' }}" href="{{ url('/blog') }}">Blog</a>
        <a class="nav-link {{ request()->is('gallery*') ? 'active' : '' }}" href="{{ url('/gallery') }}">Gallery</a>

        @guest
          <div class="nav-item">
            <button class="nav-link">
              Join <svg class="chevron" viewBox="0 0 10 10"><path d="M2 3.5 5 6.5 8 3.5" fill="none" stroke="currentColor"/></svg>
            </button>
            <div class="dropdown">
              <a href="{{ url('/register') }}">Become a Member</a>
              <a href="{{ url('/login') }}">Member Login</a>
            </div>
          </div>
        @endguest

        <a class="nav-link {{ request()->is('contact*') ? 'active' : '' }}" href="{{ url('/contact') }}">Contact</a>
      </nav>

      <!-- Desktop Right Header Controls -->
      <div class="nav-right">
        @auth
          @if(auth()->user()->hasRole('super_admin', 'content_admin', 'donation_admin', 'membership_admin', 'media_editor'))
            <a class="btn-admin-header" href="{{ url('/admin') }}">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
              Admin Panel
            </a>
          @endif

          <a class="user-pill-header" href="{{ url('/dashboard') }}" title="My Account">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <span>My Account</span>
          </a>

          <form action="{{ url('/logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="logout-btn-header" title="Logout">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            </button>
          </form>
        @else
          <a class="nav-link" href="{{ url('/login') }}" style="font-weight:700; color:#fff;">Login</a>
          <a class="nav-link" href="{{ url('/register') }}" style="font-weight:700; color:var(--sand2);">Register</a>
        @endauth

        <a class="donate-btn" href="{{ url('/donate') }}">Donate</a>
        <button class="mobile-toggle" id="openMenu" aria-label="Open menu"><svg viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h16"/></svg></button>
      </div>
    </div>
  </header>

  <!-- Fullscreen Mobile Menu Overlay -->
  <div class="mobile-menu" id="mobileMenu">
    <button class="mobile-menu-close" id="closeMenu">×</button>
    <a href="{{ url('/') }}">Home</a>
    <details>
      <summary>About</summary>
      <div>
        <a href="{{ url('/about') }}">About the Trust</a>
        <a href="{{ url('/leadership') }}">Leadership & Trustees</a>
      </div>
    </details>
    <details>
      <summary>Seva</summary>
      <div>
        <a href="{{ url('/seva/gaushala-protection') }}">Gaushala & Cow Care</a>
        <a href="{{ url('/seva/sanskrit-gurukul') }}">Sanskrit Vidyalaya</a>
        <a href="{{ url('/seva/religious-events') }}">Religious Events</a>
        <a href="{{ url('/seva') }}">All Seva Projects</a>
      </div>
    </details>
    <details>
      <summary>Chitrakoot</summary>
      <div>
        <a href="{{ url('/chitrakoot') }}">Sacred Places Guide</a>
        <a href="{{ url('/chitrakoot/kamadgiri-parikrama') }}">Kamadgiri Parikrama</a>
        <a href="{{ url('/chitrakoot/ramghat') }}">Ramghat</a>
      </div>
    </details>
    <a href="{{ url('/events') }}">Events</a>
    <a href="{{ url('/blog') }}">Blog</a>
    <a href="{{ url('/gallery') }}">Magazine Gallery</a>

    @auth
      <a href="{{ url('/dashboard') }}">My Account & Receipts</a>
      @if(auth()->user()->hasRole('super_admin', 'content_admin', 'donation_admin', 'membership_admin', 'media_editor'))
        <a href="{{ url('/admin') }}" style="color: var(--sand2); font-weight: 700;">Admin Panel</a>
      @endif
      <form action="{{ url('/logout') }}" method="POST" style="margin-top: 15px;">
        @csrf
        <button type="submit" style="background:none; border:1px solid rgba(255,255,255,.3); color:#fff; font-size:14px; padding:12px 20px; cursor:pointer; width:100%; border-radius:4px; font-weight:600;">Logout</button>
      </form>
    @else
      <a href="{{ url('/login') }}">Login</a>
      <a href="{{ url('/register') }}">Register Now</a>
    @endauth

    <a href="{{ url('/contact') }}">Contact</a>
    <a href="{{ url('/donate') }}" style="color:#d4ad7d; font-weight:700;">Donate</a>
  </div>

  <main id="main-content">
    @yield('content')
  </main>

  <!-- Footer -->
  <footer id="contact">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-brand">
          <div class="brand">
            <div class="brand-mark"><svg viewBox="0 0 50 58"><path d="M5 51V28L25 7l20 21v23"/><path d="M13 51V31l12-12 12 12v20"/><path d="M20 51V35h10v16"/><path d="M2 52h46"/><path d="M25 7V2"/></svg></div>
            <div class="brand-name">Shri Kamadgiri Peetham<br>Sewa Trust<small>CHITRAKOOT DHAM</small></div>
          </div>
          <p>सनातन संस्कृति, शिक्षा, गौसेवा, पर्यावरण और मानव कल्याण के लिए समर्पित सेवा परिवार।</p>
        </div>
        <div>
          <div class="footer-title">Explore</div>
          <div class="footer-links">
            <a href="{{ url('/about') }}">About Trust</a>
            <a href="{{ url('/seva') }}">Seva Projects</a>
            <a href="{{ url('/chitrakoot') }}">Chitrakoot Guide</a>
            <a href="{{ url('/events') }}">Events</a>
          </div>
        </div>
        <div>
          <div class="footer-title">Connect</div>
          <div class="footer-links">
            <a href="https://instagram.com" target="_blank">Instagram</a>
            <a href="https://youtube.com" target="_blank">YouTube</a>
            <a href="https://facebook.com" target="_blank">Facebook</a>
            <a href="https://whatsapp.com" target="_blank">WhatsApp Channel</a>
          </div>
        </div>
        <div>
          <div class="footer-title">Contact</div>
          <div class="footer-links">
            <a href="#">Kamadgiri Parikrama Marg, Chitrakoot Dham</a>
            <a href="tel:+919415000000">+91 94150 00000</a>
            <a href="mailto:info@kamadgiripeetham.org">info@kamadgiripeetham.org</a>
            <a href="{{ url('/donate') }}">Support the Trust</a>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <span>© {{ date('Y') }} Shri Kamadgiri Peetham Sewa Trust. All rights reserved.</span>
        <span>Privacy · Terms · Trust Information</span>
      </div>
    </div>
  </footer>

  <!-- Mobile Bottom App Bar -->
  <nav class="mobile-bar">
    <a class="{{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
      <svg viewBox="0 0 24 24"><path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z"/></svg>Home
    </a>
    <a class="{{ request()->is('seva*') ? 'active' : '' }}" href="{{ url('/seva') }}">
      <svg viewBox="0 0 24 24"><path d="M12 3 4 7v5c0 5 3.4 8 8 9 4.6-1 8-4 8-9V7z"/></svg>Seva
    </a>
    <button id="mobileExplore">
      <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m16.5 16.5 4 4"/></svg>Explore
    </button>
    <a class="{{ request()->is('events*') ? 'active' : '' }}" href="{{ url('/events') }}">
      <svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="1"/><path d="M8 3v4M16 3v4M3 10h18"/></svg>Events
    </a>
    <a class="donate-mobile {{ request()->is('donate*') ? 'active' : '' }}" href="{{ url('/donate') }}">
      <svg viewBox="0 0 24 24"><path d="M12 21s-7-4.6-9-9.2C1.2 7.8 3.4 5 6.4 5c2 0 3.5 1.1 5.6 3.1C14.1 6.1 15.6 5 17.6 5 20.6 5 22.8 7.8 21 11.8 19 16.4 12 21 12 21z"/></svg>Donate
    </a>
  </nav>

  <script>
    const loader = document.getElementById('loader');
    window.addEventListener('load', () => {
      setTimeout(() => {
        loader?.classList.add('done');
        document.body.classList.remove('loading');
      }, 700);
    });

    const header = document.getElementById('header');
    const onScroll = () => header?.classList.toggle('scrolled', window.scrollY > 40);
    window.addEventListener('scroll', onScroll, {passive:true});
    onScroll();

    const menu = document.getElementById('mobileMenu');
    const openMenu = document.getElementById('openMenu');
    const closeMenu = document.getElementById('closeMenu');
    const mobileExplore = document.getElementById('mobileExplore');
    const hideMenu = () => { menu?.classList.remove('open'); document.body.classList.remove('menu-open'); };
    
    openMenu?.addEventListener('click', () => { menu?.classList.add('open'); document.body.classList.add('menu-open'); });
    closeMenu?.addEventListener('click', hideMenu);
    mobileExplore?.addEventListener('click', () => { menu?.classList.add('open'); document.body.classList.add('menu-open'); });
    menu?.querySelectorAll('a').forEach(a => a.addEventListener('click', hideMenu));

    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if(entry.isIntersecting){
          entry.target.classList.add('visible');
          observer.unobserve(entry.target);
        }
      });
    }, {threshold:.1, rootMargin:'0px 0px -40px 0px'});
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
  </script>
  @stack('scripts')
</body>
</html>
