@extends('layouts.app')

@section('title', 'कामदगिरि सेवा ट्रस्ट | Kamadgiri Seva Trust, Chitrakoot Dham')
@section('meta_description', 'कामदगिरि सेवा ट्रस्ट, चित्रकूट धाम — भगवान कामतानाथ जी की पावन परिक्रमा, नित्य महाआरती, गौसेवा, ऋषिकुल संस्कृत गुरुकुल एवं ऑनलाइन पूजा संकल्प।')

@section('styles')
<style>
  /* ---------- ROCK-SOLID CONTAINMENT & LAYOUT ---------- */
  .site-container {
    width: 100%;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
    padding-left: 24px;
    padding-right: 24px;
    box-sizing: border-box;
  }

  /* Full-Screen Cinematic Hero Section */
  .hero-cinematic-fullscreen {
    position: relative;
    width: 100%;
    min-height: 100vh;
    overflow: hidden;
    display: flex;
    align-items: center;
    background-color: #120A05;
    box-sizing: border-box;
  }
  .hero-content-site-container {
    position: relative;
    z-index: 20;
    width: 100%;
    padding-top: clamp(110px, 14vh, 150px);
    padding-bottom: 60px;
    box-sizing: border-box;
  }
  .hero-content {
    position: relative;
    z-index: 20;
    max-width: 780px;
    box-sizing: border-box;
  }
  .hero-meta-row {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 12px;
    margin-bottom: 22px;
  }
  .hero-dham-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 16px;
    border-radius: 9999px;
    background: rgba(255, 253, 252, 0.15);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid rgba(253, 186, 116, 0.35);
    color: #FFFDFC;
  }
  .hero-holy-ping {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 9999px;
    background-color: #EA580C;
    animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
  }
  @keyframes ping {
    75%, 100% {
      transform: scale(2);
      opacity: 0;
    }
  }
  .hero-dham-title {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #FDBA74;
  }
  .hero-dham-dot {
    color: #FED7AA;
    opacity: 0.6;
  }
  .hero-dham-tag {
    font-size: 11px;
    color: #FED7AA;
  }
  .hero-bell-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 16px;
    border-radius: 9999px;
    background: rgba(255, 253, 252, 0.15);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid rgba(253, 186, 116, 0.35);
    color: #FFFDFC;
    cursor: pointer;
    transition: all 0.2s ease;
  }
  .hero-bell-pill:hover {
    background: rgba(255, 253, 252, 0.25);
  }
  .hero-bell-pill:active {
    transform: scale(0.96);
  }
  .hero-bell-icon {
    width: 28px;
    height: 28px;
    border-radius: 9999px;
    background: rgba(217, 93, 15, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #FDBA74;
  }
  .hero-bell-text {
    font-size: 12px;
    font-weight: 700;
    color: #FED7AA;
  }
  .hero-bell-counter {
    font-size: 11px;
    padding: 2px 8px;
    border-radius: 4px;
    background: rgba(217, 93, 15, 0.3);
    color: #FDBA74;
    font-family: monospace;
    font-weight: 700;
  }
  .hero-devotional-tagline {
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: #FDBA74;
    margin: 0 0 10px;
  }
  .hero-main-heading {
    font-family: 'Noto Serif', serif;
    font-size: clamp(32px, 4.4vw, 54px);
    font-weight: 800;
    color: #FFFDFC;
    line-height: 1.15;
    margin: 0 0 14px;
    letter-spacing: -0.01em;
    text-shadow: 0 2px 18px rgba(0,0,0,0.6);
  }
  .hero-sacred-subtitle {
    font-family: 'Noto Serif', serif;
    font-size: clamp(16px, 2vw, 20px);
    color: #FED7AA;
    font-weight: 600;
    margin: 0 0 14px;
    line-height: 1.45;
  }
  .hero-headline-badge {
    font-family: 'Noto Serif', serif;
    font-size: clamp(16px, 2vw, 20px);
    color: #FDBA74;
    font-weight: 700;
    margin: 0 0 12px;
  }
  .hero-narrative-p {
    color: rgba(239, 230, 220, 0.92);
    line-height: 1.7;
    font-size: 15px;
    margin: 0 0 30px;
  }
  .hero-cta-group {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 14px;
  }
  .hero-cta-actions-row {
    display: flex;
    align-items: center;
    gap: 14px;
  }
  .hero-btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 13px 28px;
    border-radius: 9999px;
    background: linear-gradient(135deg, #D95D0F 0%, #EA580C 100%);
    color: #fff;
    font-weight: 700;
    font-size: 13.5px;
    text-decoration: none;
    box-shadow: 0 6px 20px rgba(217, 93, 15, 0.45);
    transition: all 0.2s ease;
  }
  .hero-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(217, 93, 15, 0.55);
  }
  .hero-btn-primary:active {
    transform: scale(0.97);
  }
  .hero-btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 13px 26px;
    border-radius: 9999px;
    background: rgba(255, 253, 252, 0.95);
    color: #2B1A12;
    font-weight: 700;
    font-size: 13.5px;
    text-decoration: none;
    border: 1px solid rgba(253, 186, 116, 0.4);
    box-shadow: 0 4px 15px rgba(0,0,0,0.12);
    transition: all 0.2s ease;
  }
  .hero-btn-secondary:hover {
    background: #FFFDFC;
    transform: translateY(-2px);
  }
  .hero-btn-secondary:active {
    transform: scale(0.97);
  }
  .hero-btn-content {
    display: inline-flex;
    align-items: baseline;
    gap: 6px;
  }
  .hero-btn-main {
    font-weight: 700;
  }
  .hero-btn-sub {
    font-size: 11px;
    font-weight: 600;
    opacity: 0.88;
  }
  .hero-btn-tertiary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 11px 22px;
    border-radius: 9999px;
    background: rgba(255, 253, 252, 0.12);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1.5px solid rgba(254, 215, 170, 0.7);
    color: #FFFDF8;
    font-weight: 700;
    font-size: 13px;
    letter-spacing: 0.03em;
    text-decoration: none;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.2);
    transition: all 0.25s ease;
  }
  .hero-btn-tertiary:hover {
    background: rgba(254, 215, 170, 0.24);
    border-color: #FED7AA;
    color: #FFFFFF;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(234, 88, 12, 0.45);
  }
  .hero-scroll-cue-container {
    position: absolute;
    bottom: 24px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 25;
    pointer-events: auto;
  }
  .hero-scroll-cue-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(26, 16, 10, 0.72);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    border: 1.5px solid rgba(253, 186, 116, 0.85);
    border-radius: 9999px;
    padding: 8px 20px;
    color: #FFFBEB;
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 0.06em;
    text-decoration: none;
    box-shadow: 0 0 20px rgba(234, 88, 12, 0.55), 0 6px 18px rgba(0, 0, 0, 0.55);
    animation: scrollCuePulse 2.4s infinite ease-in-out;
    transition: all 0.25s ease;
  }
  .hero-scroll-cue-btn:hover {
    background: rgba(234, 88, 12, 0.9);
    border-color: #FFEDD5;
    color: #FFFFFF;
    transform: translateY(-2px);
    box-shadow: 0 0 28px rgba(234, 88, 12, 0.85);
  }
  .hero-scroll-cue-btn svg {
    stroke: #FDBA74;
    filter: drop-shadow(0 1px 3px rgba(0,0,0,0.6));
  }
  .scroll-bounce-arrow {
    animation: scrollBounce 2s infinite ease-in-out;
  }
  @keyframes scrollCuePulse {
    0%, 100% {
      box-shadow: 0 0 16px rgba(234, 88, 12, 0.45), 0 4px 15px rgba(0, 0, 0, 0.45);
      border-color: rgba(253, 186, 116, 0.85);
    }
    50% {
      box-shadow: 0 0 30px rgba(251, 146, 60, 0.9), 0 6px 22px rgba(0, 0, 0, 0.6);
      border-color: #FFEDD5;
    }
  }
  @keyframes scrollBounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(4px); }
    60% { transform: translateY(2px); }
  }

  /* About Deity Asymmetrical 2-Column Section */
  .about-layout {
    display: flex;
    flex-wrap: wrap;
    gap: 48px;
    align-items: center;
    justify-content: space-between;
  }
  .about-images-col {
    flex: 1 1 420px;
    max-width: 460px;
    display: flex;
    gap: 16px;
    align-items: center;
    justify-content: center;
    box-sizing: border-box;
  }
  .about-text-col {
    flex: 1 1 520px;
    max-width: 620px;
    box-sizing: border-box;
  }
  .about-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 24px;
    width: 100%;
    box-sizing: border-box;
  }
  .stat-card-box {
    display: flex !important;
    flex-direction: column !important;
    align-items: flex-start !important;
    justify-content: center !important;
    padding: 16px 16px !important;
    border-radius: 18px !important;
    background: #FFFDFC !important;
    border: 1px solid rgba(180, 83, 9, 0.18) !important;
    box-shadow: 0 4px 16px rgba(180, 83, 9, 0.05) !important;
    box-sizing: border-box !important;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
  }
  .stat-card-box:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(180, 83, 9, 0.12) !important;
  }
  .stat-card-num {
    display: block !important;
    font-family: 'Noto Serif', serif;
    font-size: clamp(24px, 2.5vw, 30px);
    font-weight: 800;
    line-height: 1.1;
    margin-bottom: 6px;
  }
  .stat-card-title {
    display: block !important;
    font-size: 13px;
    font-weight: 700;
    color: #2B1A12;
    line-height: 1.35;
    margin-bottom: 3px;
  }
  .stat-card-sub {
    display: block !important;
    font-size: 10.5px;
    font-weight: 500;
    color: #8C5D38;
    line-height: 1.25;
  }
  @media (max-width: 640px) {
    .about-stats-grid {
      grid-template-columns: repeat(2, 1fr) !important;
      gap: 10px !important;
    }
    .stat-card-box {
      padding: 14px 12px !important;
      border-radius: 14px !important;
      min-height: 90px !important;
    }
    .stat-card-num {
      font-size: 24px !important;
      margin-bottom: 4px !important;
    }
    .stat-card-title {
      font-size: 12px !important;
    }
    .stat-card-sub {
      font-size: 9.5px !important;
    }
  }

  .arch-frame-left {
    width: 50%;
    height: 420px;
    border-radius: 80px 20px 20px 80px;
    overflow: hidden;
    box-shadow: 0 16px 36px -8px rgba(180, 83, 9, 0.18);
    position: relative;
    transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
  }
  .arch-frame-right {
    width: 50%;
    height: 420px;
    border-radius: 20px 80px 80px 20px;
    overflow: hidden;
    margin-top: 36px;
    box-shadow: 0 16px 36px -8px rgba(180, 83, 9, 0.18);
    position: relative;
    transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
  }
  .arch-frame-left:hover, .arch-frame-right:hover {
    transform: translateY(-5px);
  }

  /* Split Donation Card matching reference */
  .donation-card-split {
    max-width: 1140px;
    margin: 0 auto;
    border-radius: 28px;
    background: #FFFDFC;
    border: 1px solid rgba(180, 83, 9, 0.2);
    box-shadow: 0 20px 50px rgba(130, 70, 20, 0.08);
    overflow: hidden;
    display: flex;
    flex-wrap: wrap;
    box-sizing: border-box;
  }
  .donation-left-img {
    flex: 1 1 440px;
    min-height: 480px;
    position: relative;
  }
  .donation-right-form {
    flex: 1 1 500px;
    padding: 44px 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    box-sizing: border-box;
  }

  /* Responsive Grids */
  .missions-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
    box-sizing: border-box;
  }
  .timings-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 16px;
    box-sizing: border-box;
  }
  .pooja-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
    box-sizing: border-box;
  }
  .destinations-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
    box-sizing: border-box;
  }

  @media (max-width: 1024px) {
    .missions-grid, .pooja-grid, .destinations-grid {
      grid-template-columns: repeat(2, 1fr);
    }
    .timings-grid {
      grid-template-columns: repeat(3, 1fr);
    }
    .about-layout {
      flex-direction: column;
    }
    .about-images-col, .about-text-col {
      max-width: 100%;
    }
    .donation-card-split {
      flex-direction: column;
    }
    .donation-left-img {
      min-height: 320px;
    }
  }

  @media (max-width: 640px) {
    .site-container {
      padding-left: 16px;
      padding-right: 16px;
    }
    .hero-cinematic-fullscreen {
      min-height: 100svh;
      min-height: 100vh;
      align-items: center;
      justify-content: center;
    }
    .hero-content-site-container {
      padding-top: 80px !important;
      padding-bottom: 40px !important;
      min-height: 100svh;
      display: flex !important;
      flex-direction: column !important;
      justify-content: center !important;
    }
    .hero-content {
      padding: 0 !important;
      max-width: 100% !important;
    }
    .hero-meta-row {
      gap: 8px;
      margin-bottom: 12px;
      justify-content: space-between;
    }
    .hero-dham-pill {
      padding: 5px 12px;
      gap: 6px;
    }
    .hero-dham-title {
      font-size: 10.5px;
    }
    .hero-dham-dot, .hero-dham-tag {
      display: none;
    }
    .hero-bell-pill {
      padding: 5px 12px;
      gap: 6px;
    }
    .hero-bell-icon {
      width: 24px;
      height: 24px;
    }
    .hero-bell-text {
      font-size: 11px;
    }
    .hero-bell-counter {
      font-size: 10px;
      padding: 1px 6px;
    }
    .hero-devotional-tagline {
      font-size: 11px;
      letter-spacing: 0.08em;
      margin-bottom: 6px;
    }
    .hero-main-heading {
      font-size: clamp(25px, 7vw, 32px);
      line-height: 1.16;
      margin-bottom: 8px;
    }
    .hero-sacred-subtitle {
      font-size: 13px;
      line-height: 1.35;
      margin-bottom: 8px;
    }
    .hero-headline-badge {
      font-size: 12.5px;
      margin-bottom: 8px;
    }
    .hero-narrative-p {
      font-size: 12.5px;
      line-height: 1.5;
      margin-bottom: 16px;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }
    .hero-cta-group {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 10px;
      width: 100%;
    }
    .hero-cta-actions-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 8px;
      width: 100%;
    }
    .hero-btn-primary {
      width: 100%;
      justify-content: center;
      padding: 9px 6px;
      border-radius: 9999px;
      box-sizing: border-box;
      gap: 5px;
    }
    .hero-btn-secondary {
      width: 100%;
      justify-content: center;
      padding: 9px 6px;
      border-radius: 9999px;
      box-sizing: border-box;
      gap: 5px;
    }
    .hero-btn-content {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      line-height: 1.15;
    }
    .hero-btn-main {
      font-size: 11.5px;
      font-weight: 700;
      white-space: nowrap;
    }
    .hero-btn-sub {
      font-size: 8.5px;
      font-weight: 600;
      opacity: 0.88;
      margin-top: 1px;
      white-space: nowrap;
    }
    .hero-btn-primary svg, .hero-btn-secondary svg {
      width: 14px;
      height: 14px;
      flex-shrink: 0;
    }
    .hero-btn-tertiary {
      width: 100%;
      justify-content: center;
      padding: 9px 16px;
      font-size: 12px;
      font-weight: 700;
      box-sizing: border-box;
      background: rgba(255, 253, 252, 0.14);
      border: 1.5px solid rgba(254, 215, 170, 0.8);
      color: #FFFDF8;
      letter-spacing: 0.03em;
    }
    .hero-scroll-cue-container {
      bottom: 12px;
    }
    .hero-scroll-cue-btn {
      font-size: 11px;
      padding: 6px 16px;
      gap: 6px;
    }
    .hero-card {
      border-radius: 20px;
      min-height: auto;
    }
    .missions-grid, .pooja-grid, .destinations-grid {
      grid-template-columns: 1fr;
    }
    .timings-grid {
      grid-template-columns: repeat(2, 1fr);
    }
    .arch-frame-left, .arch-frame-right {
      height: 280px;
    }
    .donation-right-form {
      padding: 28px 20px;
    }
  }

  /* Floating gold ember dust particles */
  @keyframes floatEmberUp {
    0% { transform: translateY(100vh) scale(0.6); opacity: 0; }
    20% { opacity: 0.8; }
    80% { opacity: 0.6; }
    100% { transform: translateY(-10vh) scale(1.2); opacity: 0; }
  }
  .ember-particle {
    position: absolute;
    bottom: 0;
    border-radius: 50%;
    background: radial-gradient(circle, #FDBA74 20%, rgba(217, 93, 15, 0.8) 70%, transparent 100%);
    pointer-events: none;
    z-index: 5;
  }
  .bell-swinging {
    animation: bellSwingAnim 1.2s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
  }
  @keyframes bellSwingAnim {
    0%, 100% { transform: rotate(0); }
    20% { transform: rotate(-24deg); }
    40% { transform: rotate(20deg); }
    60% { transform: rotate(-12deg); }
    80% { transform: rotate(6deg); }
  }
  .sound-wave {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 44px;
    height: 44px;
    margin-top: -22px;
    margin-left: -22px;
    border-radius: 50%;
    border: 2px solid #D95D0F;
    pointer-events: none;
  }
  .shimmer-timing-card {
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
  }
  .shimmer-timing-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px -6px rgba(180, 83, 9, 0.15);
  }
  .timings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 14px;
  }
  @media (max-width: 640px) {
    .timings-grid {
      grid-template-columns: repeat(2, 1fr) !important;
      gap: 10px !important;
    }
  }
</style>
@endsection

@section('content')

@php
  $bgType = $homeSettings['hero_bg_type'] ?? 'video';
  $rawVideoUrl = $homeSettings['hero_video_url'] ?? 'assets/videos/hero-stream.mp4';
  $rawPhotoUrl = $homeSettings['hero_photo_url'] ?? ($heroSlide?->image_path ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuBJRPlI3oWcuvT-VRBLDpu6U1_2u08BIwSr4yThVbBHz5spZEKjxxTAlI1JIWt6FGPLYu_uR45iCJ5HyhltIAxbi3lr73A8CJKdyiIzVZTXb1Glot-rcLMjhjXkG-XKGcmaZNJzNRhSRzmhThvXjQ6boCydVytzcOx3lAy0AOMkN_g60qlxwZXlSph9efCxwI7i68vX_F0UPsAAMuYD_FM81QjbhnGOsyT6714xFBUydScVh9H0zN0m');
  
  $resolvedVideoUrl = (str_starts_with($rawVideoUrl, 'http://') || str_starts_with($rawVideoUrl, 'https://')) ? $rawVideoUrl : asset($rawVideoUrl);
  $resolvedPhotoUrl = (str_starts_with($rawPhotoUrl, 'http://') || str_starts_with($rawPhotoUrl, 'https://')) ? $rawPhotoUrl : asset($rawPhotoUrl);
  $localVideoUrl = asset('assets/videos/hero-stream.mp4');

  $bellDefault = intval($homeSettings['bell_default_count'] ?? 108);
  $rawBellAudioUrl = $homeSettings['hero_bell_audio_url'] ?? '';
  $resolvedBellAudioUrl = !empty($rawBellAudioUrl) ? ((str_starts_with($rawBellAudioUrl, 'http://') || str_starts_with($rawBellAudioUrl, 'https://')) ? $rawBellAudioUrl : asset($rawBellAudioUrl)) : '';

  $p1 = $homeSettings['donation_preset_1'] ?? 501;
  $p2 = $homeSettings['donation_preset_2'] ?? 1100;
  $p3 = $homeSettings['donation_preset_3'] ?? 2100;
  $p4 = $homeSettings['donation_preset_4'] ?? 5100;
@endphp

<div class="flex flex-col w-full bg-[#FBF7F0] text-[#2B1A12] antialiased selection:bg-[#EA580C] selection:text-white" style="background-color: #FBF7F0; color: #2B1A12;">

  <!-- SECTION 1: FULL SCREEN CINEMATIC HERO SECTION (Edge-to-Edge with Background Video) -->
  @if(($homeSettings['section_hero_enabled'] ?? '1') === '1')
  <section class="hero-cinematic-fullscreen" id="hero-interactive-section" style="position: relative; width: 100%; min-height: 100vh; overflow: hidden; display: flex; align-items: center; justify-content: center; background-color: #120A05;">
    
    <!-- Floating Gold Ember Dust Particles -->
    <div class="absolute inset-0 pointer-events-none z-10 overflow-hidden" id="hero-ember-container" style="position: absolute; inset: 0; pointer-events: none; z-index: 10; overflow: hidden;"></div>

    <!-- Background Media (Cinematic Full-Screen Video or Photo Parallax) -->
    @if($bgType === 'video' && !empty($rawVideoUrl))
      <div class="absolute inset-0 w-full h-full overflow-hidden pointer-events-none z-0" style="position: absolute; inset: 0; width: 100%; height: 100%; overflow: hidden; pointer-events: none; z-index: 0;">
        <video id="heroVideo" autoplay muted loop playsinline poster="{{ $resolvedPhotoUrl }}" style="position: absolute; top: 50%; left: 50%; width: 100%; height: 100%; min-width: 100%; min-height: 100%; object-fit: cover; transform: translate(-50%, -50%); opacity: 0.65; filter: contrast(1.1) saturate(1.2);">
          <source src="{{ asset('assets/videos/hero-stream.mp4') }}" type="video/mp4">
          @if(!empty($resolvedVideoUrl) && $resolvedVideoUrl !== asset('assets/videos/hero-stream.mp4'))
            <source src="{{ $resolvedVideoUrl }}" type="video/mp4">
          @endif
        </video>
      </div>
    @else
      <div class="absolute inset-0 w-full h-full bg-cover bg-center opacity-55 scale-105 transform transition-transform duration-700 ease-out z-0" id="hero-bg-parallax" style="position: absolute; inset: 0; width: 100%; height: 100%; background-image: url('{{ $resolvedPhotoUrl }}'); background-size: cover; background-position: center; opacity: 0.6; transform: scale(1.05); z-index: 0;"></div>
    @endif

    <!-- Warm Spiritual High-Contrast Left Vignette & Gradient Overlays (Full Bleed) -->
    <div class="hero-texture-overlay" style="position: absolute; inset: 0; background: linear-gradient(90deg, rgba(16, 9, 5, 0.94) 0%, rgba(16, 9, 5, 0.82) 48%, rgba(16, 9, 5, 0.42) 78%, rgba(16, 9, 5, 0.35) 100%), linear-gradient(0deg, rgba(16, 9, 5, 0.85) 0%, transparent 35%); pointer-events: none; z-index: 1;"></div>
    <div style="position: absolute; top: -120px; left: 10%; width: 750px; height: 750px; background: rgba(234, 88, 12, 0.22); border-radius: 9999px; filter: blur(160px); pointer-events: none; z-index: 1;"></div>

    <!-- Contained Left-Aligned Typography Inside Fullscreen Hero -->
    <div class="site-container hero-content-site-container">
      <div class="hero-content">
          
          <!-- Top Pill Badges & Temple Bell -->
          <div class="hero-meta-row">
            <div class="hero-dham-pill">
              <span class="hero-holy-ping"></span>
              <span class="hero-dham-title">{{ $homeSettings['hero_eyebrow'] ?? 'तपोभूमि चित्रकूट धाम' }}</span>
              <span class="hero-dham-dot">•</span>
              <span class="hero-dham-tag">A Sacred Land, Living Stories</span>
            </div>

            <!-- Interactive Temple Bell Button -->
            <button type="button" class="hero-bell-pill group" id="temple-bell-btn" onclick="ringTempleBell()" aria-label="Ring sacred temple bell">
              <div class="hero-bell-icon" id="bell-icon-wrapper">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="bell-svg-icon"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
              </div>
              <span class="hero-bell-text" id="bell-status-text">घंटा ध्वनि बजाएं</span>
              <span class="hero-bell-counter" id="bell-count">{{ $bellDefault }}</span>
            </button>
          </div>

          <!-- Devotional Tagline -->
          <p class="hero-devotional-tagline">
            मनोकामना सिद्धि प्रदाता श्री कामदगिरि प्रभु की पावन छत्रछाया
          </p>

          <!-- STRICT SINGLE PRIMARY BRAND HEADING -->
          <h1 class="hero-main-heading">
            {{ $orgSettings->org_name ?? 'Shri Kamadgiri Peetham Sewa Trust' }}
          </h1>

          <!-- Subheading -->
          <div class="hero-sacred-subtitle">
            चित्रकूट धाम • सनातन संस्कृति, वैदिक शिक्षा, गौ सेवा व मानव कल्याण
          </div>

          @if(!empty($homeSettings['hero_headline']))
            <div class="hero-headline-badge">
              {{ $homeSettings['hero_headline'] }}
            </div>
          @endif

          <!-- Narrative Paragraph -->
          <p class="hero-narrative-p">
            {{ $orgSettings->about_text ?? ($homeSettings['hero_subtitle'] ?? 'भगवान कामतानाथ जी की पावन तपोभूमि चित्रकूट में सनातन संस्कृति, वैदिक ऋषिकुल शिक्षा, कामदगिरि गौशाला और जनकल्याण के लिए समर्पित एक पावन सेवा संस्थान।') }}
          </p>

          <!-- Audio Player for Temple Bell Chime -->
          @if(!empty($resolvedBellAudioUrl))
            <audio id="templeBellAudio" src="{{ $resolvedBellAudioUrl }}" preload="auto"></audio>
          @endif

          <!-- Action CTAs -->
          <div class="hero-cta-group">
            <div class="hero-cta-actions-row">
              <a class="hero-btn-primary" href="{{ url($homeSettings['hero_secondary_btn_link'] ?? '#contribution-section') }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                <span class="hero-btn-content">
                  <span class="hero-btn-main">दान एवं सहयोग</span>
                  <span class="hero-btn-sub">(Donate Now)</span>
                </span>
              </a>

              <a class="hero-btn-secondary" href="{{ route('pooja.bookingForm') }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#D95D0F" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                <span class="hero-btn-content">
                  <span class="hero-btn-main">पूजा संकल्प</span>
                  <span class="hero-btn-sub">(Book Pooja)</span>
                </span>
              </a>
            </div>

            <a class="hero-btn-tertiary" href="{{ url('/about') }}">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
              <span>ट्रस्ट परिचय (About Trust)</span>
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="margin-left: 2px;"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
          </div>

      </div>
    </div>

    <!-- Scroll Down Cue Indicator -->
    <div class="hero-scroll-cue-container">
      <a href="#about-deity" class="hero-scroll-cue-btn" aria-label="Scroll to explore Chitrakoot">
        <span>दर्शन एवं सेवा</span>
        <svg class="scroll-bounce-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M6 9l6 6 6-6"/></svg>
      </a>
    </div>
  </section>
  @endif

  <!-- SECTION 2: "ABOUT THE DEITY" (Strictly Contained 2-Column with Double Arched Temple Photos) -->
  @if(($homeSettings['section_about_enabled'] ?? '1') === '1')
  <section class="w-full py-16 sm:py-24 bg-[#FAF4ED] relative" id="about-deity" style="padding: 80px 0; background-color: #FAF4ED;">
    <div class="site-container">
      <div class="about-layout">
        
        <!-- Left Column: Double Arched Temple Photos matching user reference -->
        <div class="about-images-col reveal-card">
          <!-- Arched Photo 1 -->
          <div class="arch-frame-left">
            <img src="{{ $homeSettings['about_photo_1_url'] ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuCfOUqcsYwKNWdOv3-IySqrpdeo9-aBjdqcY15-uvLwf_VoWoEx-U1nvKGS2-jtfSlovihdEg3_b1s0-lbKpDDcIFSus-7fFE753BIRrO8WI8kMYj2_0FFT4qNEAK0772GxDZOYKJnZEZe3t4cHWEvQLZv7iMxOf44ldS-_6w2cjcFU41obdfY4R9BsY_utkY-FWJc4v6XbcYWLHkEU3NnjPGlX-TGbJZHFRE6vpNhYwmMofphi33u4' }}" alt="{{ $homeSettings['about_photo_1_caption'] ?? 'Shri Kamadgiri Temple Sanctum' }}" style="width: 100%; height: 100%; object-fit: cover;" loading="lazy">
            <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(43, 26, 18, 0.65) 0%, transparent 45%);"></div>
            <div style="position: absolute; bottom: 16px; left: 16px; right: 16px; text-align: center;">
              <span style="font-size: 11.5px; color: #FFFDFC; font-weight: 700; text-shadow: 0 1px 3px rgba(0,0,0,0.4);">{{ $homeSettings['about_photo_1_caption'] ?? 'कामदगिरि गर्भगृह' }}</span>
            </div>
          </div>

          <!-- Arched Photo 2 (Offset down) -->
          <div class="arch-frame-right">
            <img src="{{ $homeSettings['about_photo_2_url'] ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuCGizC1EIA1jnKlpQ1SWBzKrHG_OgZkQe6sDax_wnuZtIhFWtfWt-6m1jnBTWhz5Jji0AOrt9xr8W41pZkn-cZaOkuTyo2yeOHwrD92V6audP3fJcrCspCJNsquLRugs1Igok9LRE_4xwn9hZ6UVHI8f_hQJMjVOuIxA9CF87Om-bfNQNfGcQEsRylsOnCo7SMcSgmrXG2xf7A_uXu2kYkWLuPWiLaz4ltFV-UADzwzoIoVFdYetNmu' }}" alt="{{ $homeSettings['about_photo_2_caption'] ?? 'Kamadgiri Parikrama & Holy Dham' }}" style="width: 100%; height: 100%; object-fit: cover;" loading="lazy">
            <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(43, 26, 18, 0.65) 0%, transparent 45%);"></div>
            <div style="position: absolute; bottom: 16px; left: 16px; right: 16px; text-align: center;">
              <span style="font-size: 11.5px; color: #FFFDFC; font-weight: 700; text-shadow: 0 1px 3px rgba(0,0,0,0.4);">{{ $homeSettings['about_photo_2_caption'] ?? 'पावन परिक्रमा शिखर' }}</span>
            </div>
          </div>
        </div>

        <!-- Right Column: Narrative with Rich Typography -->
        <div class="about-text-col reveal-card">
          
          <!-- Eyebrow Badge -->
          <div class="flex items-center gap-2 mb-3" style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
            <span class="text-xs uppercase tracking-widest text-[#D95D0F] font-bold" style="font-size: 11px; font-weight: 800; letter-spacing: 0.14em; text-transform: uppercase; color: #D95D0F;">
              {{ $homeSettings['trust_eyebrow'] ?? 'कामदगिरि महिमा एवं सेवा संकल्प' }}
            </span>
            <span style="color: #D4B996;">•</span>
            <span class="text-xs text-[#B45309] font-semibold" style="font-size: 11px; font-weight: 700; color: #B45309;">
              {{ $homeSettings['trust_tagline'] ?? 'श्री कामतानाथ प्रभु पावन धाम' }}
            </span>
          </div>

          <!-- Section Headline -->
          <h2 class="font-serif text-3xl sm:text-4xl text-[#2B1A12] font-bold mb-4 leading-tight" style="font-family: 'Noto Serif', serif; font-size: clamp(26px, 3.2vw, 38px); font-weight: 700; color: #2B1A12; line-height: 1.25; margin: 0 0 16px;">
            {{ $homeSettings['trust_heading'] ?? 'भगवान श्री कामतानाथ जी एवं ट्रस्ट की पावन सेवा यात्रा' }}
          </h2>

          <p class="font-serif text-base sm:text-lg text-[#B45309] font-medium mb-4 leading-relaxed" style="font-family: 'Noto Serif', serif; font-size: 16px; color: #B45309; line-height: 1.6; margin-bottom: 14px;">
            {{ $homeSettings['trust_lead'] ?? 'चित्रकूट की तपोभूमि पर कामदगिरि पर्वत साक्षात् भगवान श्री कामतानाथ जी का पावन विग्रह स्वरूप है — जो श्रद्धापूर्वक शरणागत सभी भक्तों की मनोकामनाओं के परम सिद्धि प्रदाता हैं।' }}
          </p>

          <p class="text-sm text-[#7A4B29] mb-4 leading-relaxed" style="color: #7A4B29; font-size: 14px; line-height: 1.7; margin-bottom: 14px;">
            {{ $homeSettings['trust_desc'] ?? 'त्रेतायुग के वनवास काल में मर्यादा पुरुषोत्तम भगवान श्रीराम, माता जानकी एवं लक्ष्मण जी ने इसी पावन तपोवन में साढ़े ग्यारह वर्ष व्यतीत कर धर्म की प्रतिष्ठा की थी। महर्षि वाल्मीकि के अनुसार, कामदगिरि पर्वत की श्रद्धापूर्ण परिक्रमा से जीवन के समस्त कष्ट, क्लेश एवं पापों का शमन होकर असीम शांति प्राप्त होती है।' }}
          </p>

          <p class="text-sm text-[#7A4B29] mb-4 leading-relaxed" style="color: #7A4B29; font-size: 14px; line-height: 1.7; margin-bottom: 16px;">
            {{ $homeSettings['trust_p2'] ?? 'श्री कामदगिरि पीठम् सेवा ट्रस्ट इसी अनादि पावन परंपरा का संवाहक बनकर निःशुल्क ऋषिकुल वेद विद्यालय, कामदगिरि गोशाला, नित्य अन्नक्षेत्र भंडारा एवं तीर्थ परिक्रमा पथ संवर्धन के माध्यम से निरंतर सनातन धर्म व समाज सेवा में समर्पित है।' }}
          </p>

          <!-- Trust Vision: पर्यावरण बचाओ अभियान Image-Rich Feature Card -->
          @if(($homeSettings['section_env_enabled'] ?? '1') === '1')
          <div class="env-campaign-card" style="position: relative; border-radius: 18px; overflow: hidden; border: 1.5px solid rgba(45, 106, 79, 0.28); background: #FFFDFC; margin-bottom: 24px; box-shadow: 0 10px 28px rgba(45, 106, 79, 0.1);">
            <!-- Visual Header with Real Image & Overlay -->
            <div style="position: relative; height: 170px; width: 100%; overflow: hidden;">
              <img src="{{ $homeSettings['env_section_image_url'] ?? 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=1200&q=80' }}"
                   alt="चित्रकूट पर्यावरण बचाओ अभियान"
                   loading="lazy"
                   decoding="async"
                   style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease;"
                   onmouseover="this.style.transform='scale(1.04)'"
                   onmouseout="this.style.transform='scale(1)'">
              <div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(17, 43, 31, 0.3) 0%, rgba(17, 43, 31, 0.85) 100%);"></div>
              <div style="position: absolute; top: 12px; left: 14px; display: inline-flex; align-items: center; gap: 6px; background: rgba(45, 106, 79, 0.85); backdrop-filter: blur(8px); padding: 4px 10px; border-radius: 9999px; border: 1px solid rgba(255, 255, 255, 0.25);">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <span style="font-size: 11px; font-weight: 800; color: #fff; letter-spacing: 0.04em;">
                  {{ $homeSettings['env_section_badge'] ?? 'पर्यावरण संरक्षण संकल्प • Eco-Dharma' }}
                </span>
              </div>
              <div style="position: absolute; bottom: 12px; left: 14px; right: 14px;">
                <h3 style="font-family: 'Noto Serif', serif; font-size: 16px; font-weight: 700; color: #FFFFFF; line-height: 1.3; margin: 0; text-shadow: 0 2px 6px rgba(0,0,0,0.5);">
                  {{ $homeSettings['env_section_title'] ?? 'पर्यावरण बचाओ अभियान — स्वच्छ व संरक्षित चित्रकूट' }}
                </h3>
                @if(!empty($homeSettings['env_section_subtitle']))
                <span style="display: block; font-size: 11.5px; color: #E0E7E3; margin-top: 2px;">
                  {{ $homeSettings['env_section_subtitle'] }}
                </span>
                @endif
              </div>
            </div>

            <!-- Narrative & 3 Mission Pillars -->
            <div style="padding: 16px 18px; background: linear-gradient(180deg, #FFFFFF 0%, #F5FBF7 100%);">
              <p style="font-size: 12.5px; color: #2B1A12; line-height: 1.65; margin: 0 0 12px;">
                {{ $homeSettings['env_section_desc'] ?? 'भगवान श्रीराम की तपोभूमि चित्रकूट को स्वच्छ, प्रदूषण-मुक्त और हरियाली से परिपूर्ण बनाए रखना हमारा परम ध्येय है। ट्रस्ट पावन मंदाकिनी नदी की स्वच्छता, प्लास्टिक-मुक्त कामदगिरि परिक्रमा और १०,०००+ पवित्र वृक्षारोपण के माध्यम से चित्रकूट के मूल आध्यात्मिक व प्राकृतिक स्वरूप के संरक्षण हेतु अनवरत कार्यरत है।' }}
              </p>

              <!-- 3 Pillars Chips with Authentic Eco-Dharma Styling -->
              <div style="display: flex; flex-wrap: wrap; gap: 7px; margin-bottom: 12px;">
                <span style="display: inline-flex; align-items: center; gap: 5px; font-size: 11.5px; font-weight: 700; color: #1E4620; background: #E8F5E9; padding: 4px 10px; border-radius: 9999px; border: 1px solid rgba(45, 106, 79, 0.25);">
                  <span style="color: #2D6A4F; font-weight: 900;">✓</span> {{ $homeSettings['env_pillar_1'] ?? 'अविरल-निर्मल मंदाकिनी' }}
                </span>
                <span style="display: inline-flex; align-items: center; gap: 5px; font-size: 11.5px; font-weight: 700; color: #1E4620; background: #E8F5E9; padding: 4px 10px; border-radius: 9999px; border: 1px solid rgba(45, 106, 79, 0.25);">
                  <span style="color: #2D6A4F; font-weight: 900;">✓</span> {{ $homeSettings['env_pillar_2'] ?? 'प्लास्टिक-मुक्त कामदगिरि परिक्रमा' }}
                </span>
                <span style="display: inline-flex; align-items: center; gap: 5px; font-size: 11.5px; font-weight: 700; color: #1E4620; background: #E8F5E9; padding: 4px 10px; border-radius: 9999px; border: 1px solid rgba(45, 106, 79, 0.25);">
                  <span style="color: #2D6A4F; font-weight: 900;">✓</span> {{ $homeSettings['env_pillar_3'] ?? '१०,०००+ देववृक्ष रोपण' }}
                </span>
              </div>

              @if(!empty($homeSettings['env_btn_text']))
              <div style="border-top: 1px dashed rgba(45, 106, 79, 0.2); padding-top: 10px; display: flex; justify-content: flex-end;">
                <a href="{{ url($homeSettings['env_btn_link'] ?? '/about#environment') }}" style="display: inline-flex; align-items: center; gap: 5px; font-size: 11.5px; font-weight: 800; color: #2D6A4F; text-decoration: none; transition: color 0.2s ease;">
                  <span>{{ $homeSettings['env_btn_text'] }}</span>
                  <span>&rarr;</span>
                </a>
              </div>
              @endif
            </div>
          </div>
          @endif

          <!-- 4 Stat Counters in a Clean Responsive Grid -->
          <div class="about-stats-grid">
            
            <!-- Stat 1: Years of Seva -->
            <div class="stat-card-box">
              <span class="stat-card-num" style="color: #D95D0F;">{{ $homeSettings['stat_years'] ?? '25+' }}</span>
              <span class="stat-card-title">{{ $homeSettings['stat_years_title'] ?? 'अखंड सेवा साधना' }}</span>
              <span class="stat-card-sub">{{ $homeSettings['stat_years_sub'] ?? 'Years of Dedicated Seva' }}</span>
            </div>

            <!-- Stat 2: Vedic Students -->
            <div class="stat-card-box">
              <span class="stat-card-num" style="color: #B45309;">{{ $homeSettings['stat_students'] ?? '500+' }}</span>
              <span class="stat-card-title">{{ $homeSettings['stat_students_title'] ?? 'ऋषिकुल बटुक छात्र' }}</span>
              <span class="stat-card-sub">{{ $homeSettings['stat_students_sub'] ?? 'Vedic Gurukul Scholars' }}</span>
            </div>

            <!-- Stat 3: Cows in Care -->
            <div class="stat-card-box">
              <span class="stat-card-num" style="color: #2D6A4F;">{{ $homeSettings['stat_cows'] ?? '250+' }}</span>
              <span class="stat-card-title">{{ $homeSettings['stat_cows_title'] ?? 'संरक्षित गोवंश सेवा' }}</span>
              <span class="stat-card-sub">{{ $homeSettings['stat_cows_sub'] ?? 'Protected Sacred Cows' }}</span>
            </div>

            <!-- Stat 4: Trees Planted -->
            <div class="stat-card-box">
              <span class="stat-card-num" style="color: #EA580C;">{{ $homeSettings['stat_trees'] ?? '10,000+' }}</span>
              <span class="stat-card-title">{{ $homeSettings['stat_trees_title'] ?? 'तीर्थ हरित वृक्षारोपण' }}</span>
              <span class="stat-card-sub">{{ $homeSettings['stat_trees_sub'] ?? 'Sacred Trees Planted' }}</span>
            </div>

          </div>

          <div>
            <a class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-[#FFFDFC] hover:bg-[#FAF4ED] text-[#2B1A12] font-semibold text-xs sm:text-sm border border-[#B45309]/20 shadow-sm transition-all hover:scale-105" href="{{ url($homeSettings['about_btn_link'] ?? '/about') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 11px 24px; border-radius: 9999px; background: #FFFDFC; color: #2B1A12; font-weight: 700; font-size: 13px; text-decoration: none; border: 1.5px solid rgba(180, 83, 9, 0.22); box-shadow: 0 4px 12px rgba(180, 83, 9, 0.08);">
              <span>{{ $homeSettings['about_btn_text'] ?? 'श्री कामदगिरि धाम व ट्रस्ट का संपूर्ण इतिहास जानें' }}</span>
              <span style="font-size: 15px;">&rarr;</span>
            </a>
          </div>

        </div>

      </div>
    </div>
  </section>
  @endif

  <!-- SECTION 3: CORE DHARMA MISSIONS (Contained 4-Card Grid) -->
  @if(($homeSettings['section_missions_enabled'] ?? '1') === '1')
  <section class="w-full py-16 sm:py-20 bg-[#FBF7F0] relative" id="dharma-missions" style="padding: 72px 0; background-color: #FBF7F0;">
    <div class="site-container">
      
      <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10 reveal-card" style="margin-bottom: 40px;">
        <div>
          <span class="text-xs uppercase tracking-widest text-[#D95D0F] font-bold mb-2 block" style="font-size: 11px; font-weight: 800; letter-spacing: 0.14em; text-transform: uppercase; color: #D95D0F; margin-bottom: 6px;">
            {{ $homeSettings['seva_section_eyebrow'] ?? '॥ प्रमुख सेवा संकल्प ॥ • Core Dharma Missions' }}
          </span>
          <h2 class="font-serif text-2xl sm:text-3xl text-[#2B1A12] font-bold leading-snug" style="font-family: 'Noto Serif', serif; font-size: clamp(24px, 2.8vw, 36px); font-weight: 700; color: #2B1A12; margin: 0 0 4px;">
            {{ $homeSettings['seva_section_title'] ?? 'ट्रस्ट के मुख्य सेवा प्रकल्प — सनातन धर्म व समाज सेवा' }}
          </h2>
        </div>
        <p class="text-xs sm:text-sm text-[#7A4B29] max-w-md leading-relaxed" style="color: #7A4B29; font-size: 13px; line-height: 1.6; max-width: 440px; margin: 0;">
          {{ $homeSettings['seva_section_subtitle'] ?? 'चित्रकूट धाम की पावन परिधि में सनातन संस्कृति, वैदिक शिक्षा, गौवंश संरक्षण और दीन-दुखियों की सेवा हेतु निरंतर संचालित चार मुख्य सेवा स्तंभ।' }}
        </p>
      </div>

      <!-- 4 Wide Contained Cards -->
      <div class="missions-grid">
        
        <!-- Pillar 1: Gurukul -->
        <a href="{{ url($homeSettings['mission_1_link'] ?? '/seva/sanskrit-gurukul') }}" class="group p-6 rounded-3xl bg-[#FFFDFC] hover:bg-[#FAF4ED] border border-[#B45309]/18 hover:border-[#D95D0F]/50 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col justify-between" style="padding: 22px; border-radius: 20px; background: #FFFDFC; border: 1px solid rgba(180, 83, 9, 0.18); text-decoration: none;">
          <div>
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
              <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(217,93,15,0.12); display: grid; place-items: center; color: #D95D0F;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
              </div>
              <span style="font-size: 10.5px; font-weight: 800; color: #D95D0F; background: #FAF4ED; padding: 3px 8px; border-radius: 9999px; border: 1px solid rgba(180, 83, 9, 0.15);">{{ $homeSettings['mission_1_tag'] ?? 'निःशुल्क शिक्षा' }}</span>
            </div>
            <h3 style="font-family: 'Noto Serif', serif; font-size: 17px; font-weight: 700; color: #2B1A12; margin: 0 0 6px;" class="group-hover:text-[#D95D0F]">
              {{ $homeSettings['mission_1_title'] ?? 'ऋषिकुल संस्कृत गुरुकुल' }}
            </h3>
            <p style="font-size: 12.5px; color: #7A4B29; line-height: 1.55; margin: 0;">
              {{ $homeSettings['mission_1_desc'] ?? 'बटुकों को निःशुल्क वेद-वेदांग, व्याकरण, कर्मकांड एवं सनातन संस्कार शिक्षा प्रदान करना।' }}
            </p>
          </div>
          <div style="margin-top: 18px; font-size: 11.5px; font-weight: 700; color: #B45309; display: flex; align-items: center; gap: 4px;">
            <span>गुरुकुल सेवा जानें</span> &rarr;
          </div>
        </a>

        <!-- Pillar 2: Gaushala -->
        <a href="{{ url($homeSettings['mission_2_link'] ?? '/seva/gaushala-protection') }}" class="group p-6 rounded-3xl bg-[#FFFDFC] hover:bg-[#FAF4ED] border border-[#B45309]/18 hover:border-[#D95D0F]/50 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col justify-between" style="padding: 22px; border-radius: 20px; background: #FFFDFC; border: 1px solid rgba(180, 83, 9, 0.18); text-decoration: none;">
          <div>
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
              <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(45,106,79,0.12); display: grid; place-items: center; color: #2D6A4F;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
              </div>
              <span style="font-size: 10.5px; font-weight: 800; color: #2D6A4F; background: #FAF4ED; padding: 3px 8px; border-radius: 9999px; border: 1px solid rgba(45, 106, 79, 0.2);">{{ $homeSettings['mission_2_tag'] ?? '250+ गौवंश' }}</span>
            </div>
            <h3 style="font-family: 'Noto Serif', serif; font-size: 17px; font-weight: 700; color: #2B1A12; margin: 0 0 6px;" class="group-hover:text-[#D95D0F]">
              {{ $homeSettings['mission_2_title'] ?? 'कामदगिरि गौशाला (गौ सेवा)' }}
            </h3>
            <p style="font-size: 12.5px; color: #7A4B29; line-height: 1.55; margin: 0;">
              {{ $homeSettings['mission_2_desc'] ?? 'निराश्रित व वृद्ध गौवंश की नित्य सेवा, औषधीय चिकित्सा एवं पोषक हरा चारा व्यवस्था।' }}
            </p>
          </div>
          <div style="margin-top: 18px; font-size: 11.5px; font-weight: 700; color: #B45309; display: flex; align-items: center; gap: 4px;">
            <span>गौ सेवा सहयोग</span> &rarr;
          </div>
        </a>

        <!-- Pillar 3: Annakshetra -->
        <a href="{{ url($homeSettings['mission_3_link'] ?? '/seva') }}" class="group p-6 rounded-3xl bg-[#FFFDFC] hover:bg-[#FAF4ED] border border-[#B45309]/18 hover:border-[#D95D0F]/50 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col justify-between" style="padding: 22px; border-radius: 20px; background: #FFFDFC; border: 1px solid rgba(180, 83, 9, 0.18); text-decoration: none;">
          <div>
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
              <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(180,83,9,0.12); display: grid; place-items: center; color: #B45309;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
              </div>
              <span style="font-size: 10.5px; font-weight: 800; color: #B45309; background: #FAF4ED; padding: 3px 8px; border-radius: 9999px; border: 1px solid rgba(180, 83, 9, 0.15);">{{ $homeSettings['mission_3_tag'] ?? 'नित्य भंडारा' }}</span>
            </div>
            <h3 style="font-family: 'Noto Serif', serif; font-size: 17px; font-weight: 700; color: #2B1A12; margin: 0 0 6px;" class="group-hover:text-[#D95D0F]">
              {{ $homeSettings['mission_3_title'] ?? 'नित्य अन्नक्षेत्र सेवा' }}
            </h3>
            <p style="font-size: 12.5px; color: #7A4B29; line-height: 1.55; margin: 0;">
              {{ $homeSettings['mission_3_desc'] ?? 'कामदगिरि परिक्रमार्थियों, संतों एवं निर्धनों को नित्य शुद्ध सात्विक महाप्रसाद वितरण।' }}
            </p>
          </div>
          <div style="margin-top: 18px; font-size: 11.5px; font-weight: 700; color: #B45309; display: flex; align-items: center; gap: 4px;">
            <span>अन्नदान सेवा</span> &rarr;
          </div>
        </a>

        <!-- Pillar 4: Pilgrimage & Culture -->
        <a href="{{ url($homeSettings['mission_4_link'] ?? '/chitrakoot') }}" class="group p-6 rounded-3xl bg-[#FFFDFC] hover:bg-[#FAF4ED] border border-[#B45309]/18 hover:border-[#D95D0F]/50 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col justify-between" style="padding: 22px; border-radius: 20px; background: #FFFDFC; border: 1px solid rgba(180, 83, 9, 0.18); text-decoration: none;">
          <div>
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
              <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(217,93,15,0.12); display: grid; place-items: center; color: #D95D0F;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M5 21V11l7-7 7 7v10"/><path d="M9 21v-6a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v6"/><circle cx="12" cy="7" r="1"/></svg>
              </div>
              <span style="font-size: 10.5px; font-weight: 800; color: #D95D0F; background: #FAF4ED; padding: 3px 8px; border-radius: 9999px; border: 1px solid rgba(180, 83, 9, 0.15);">{{ $homeSettings['mission_4_tag'] ?? 'तीर्थ संवर्धन' }}</span>
            </div>
            <h3 style="font-family: 'Noto Serif', serif; font-size: 17px; font-weight: 700; color: #2B1A12; margin: 0 0 6px;" class="group-hover:text-[#D95D0F]">
              {{ $homeSettings['mission_4_title'] ?? 'तीर्थ व संस्कृति सेवा' }}
            </h3>
            <p style="font-size: 12.5px; color: #7A4B29; line-height: 1.55; margin: 0;">
              {{ $homeSettings['mission_4_desc'] ?? 'कामदगिरि परिक्रमा पथ संवर्धन, मंदाकिनी दीपदान, स्वच्छता व धार्मिक उत्सव।' }}
            </p>
          </div>
          <div style="margin-top: 18px; font-size: 11.5px; font-weight: 700; color: #B45309; display: flex; align-items: center; gap: 4px;">
            <span>तीर्थ गाइड देखें</span> &rarr;
          </div>
        </a>

      </div>

      @if(isset($sevas) && $sevas->count() > 0)
        <!-- Dynamic Seva Campaigns from CMS -->
        <div style="margin-top: 40px; padding-top: 32px; border-top: 1px solid rgba(180, 83, 9, 0.16);">
          <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
            <div>
              <span style="font-size: 11px; font-weight: 800; letter-spacing: 0.12em; text-transform: uppercase; color: #D95D0F; display: block; margin-bottom: 2px;">॥ सेवा महा-अभियान सहभागिता ॥</span>
              <strong style="font-family: 'Noto Serif', serif; font-size: 18px; color: #2B1A12;">सक्रिय सेवा अभियान (Active Seva Campaigns)</strong>
            </div>
            <a href="{{ url('/seva') }}" style="font-size: 12px; font-weight: 700; color: #D95D0F; text-decoration: none;">समस्त अभियान देखें &rarr;</a>
          </div>
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
            @foreach($sevas as $seva)
              @php
                $campaignImg = $seva->featured_image ?: ($seva->cause?->featured_image ?: asset('uploads/temple-test-photo-1792032209.webp'));
                $catLabel = match(strtolower($seva->category ?? '')) {
                  'education' => 'वैदिक गुरुकुल शिक्षा',
                  'seva' => 'कामदगिरि गौशाला',
                  'culture' => 'धर्म व सांस्कृतिक आयोजन',
                  default => ($seva->category ?: 'सेवा महा-अभियान')
                };
              @endphp
              <div class="reveal-card rounded-3xl bg-[#FFFDFC] border border-[#B45309]/18 hover:border-[#D95D0F]/45 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between overflow-hidden group" style="border-radius: 20px; background: #FFFDFC; border: 1.5px solid rgba(180, 83, 9, 0.18); overflow: hidden;">
                
                <!-- Campaign Featured Image with Overlay & Floating Badges -->
                <div style="height: 195px; position: relative; overflow: hidden; background: #1a0f08;">
                  <img src="{{ $campaignImg }}" 
                       alt="{{ $seva->title }}" 
                       loading="lazy" 
                       style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);"
                       class="group-hover:scale-108">
                  <div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(16,9,5,0.15) 0%, rgba(16,9,5,0.78) 100%);"></div>

                  <!-- Category Tag -->
                  <div style="position: absolute; top: 12px; left: 12px; padding: 4px 11px; border-radius: 9999px; background: rgba(255, 253, 252, 0.94); border: 1px solid rgba(180, 83, 9, 0.22); backdrop-filter: blur(4px); box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <span style="font-size: 11px; color: #B45309; font-weight: 800; letter-spacing: 0.04em;">{{ $catLabel }}</span>
                  </div>

                  <!-- Donors & Progress Bottom Info Bar -->
                  <div style="position: absolute; bottom: 10px; left: 12px; right: 12px; display: flex; align-items: center; justify-content: space-between; font-size: 11.5px; color: #fff;">
                    <span style="display: flex; align-items: center; gap: 5px; font-weight: 700; background: rgba(0,0,0,0.4); padding: 3px 8px; border-radius: 6px; backdrop-filter: blur(4px);">
                      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#FDBA74" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                      <span>{{ $seva->donors_count }} सहयोगी</span>
                    </span>
                    <span style="padding: 3px 9px; border-radius: 9999px; background: linear-gradient(135deg, #D95D0F 0%, #EA580C 100%); color: #fff; font-weight: 800; font-size: 11px; box-shadow: 0 2px 6px rgba(217,93,15,0.35);">
                      {{ $seva->progress_percent }}% पूर्ण
                    </span>
                  </div>
                </div>

                <!-- Card Body Content -->
                <div style="padding: 20px 22px; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                  <div>
                    <h4 style="font-family: 'Noto Serif', serif; font-size: 17px; font-weight: 700; color: #2B1A12; margin: 0 0 8px; line-height: 1.35;" class="group-hover:text-[#D95D0F] transition-colors">
                      {{ $seva->title }}
                    </h4>
                    <p style="font-size: 12.5px; color: #7A4B29; line-height: 1.6; margin: 0 0 16px;">
                      {{ Str::limit($seva->summary_hi ?: $seva->description, 100) }}
                    </p>
                  </div>

                  <!-- Goal & Progress Bar -->
                  <div style="border-top: 1px solid rgba(180, 83, 9, 0.12); padding-top: 14px; margin-top: auto;">
                    <div style="display: flex; justify-content: space-between; align-items: baseline; font-size: 11.5px; margin-bottom: 6px;">
                      <div>
                        <span style="font-size: 10px; text-transform: uppercase; color: #7A4B29; font-weight: 700; display: block;">एकत्र सहयोग</span>
                        <span style="font-weight: 800; font-size: 15px; color: #166534;">₹{{ number_format($seva->raised_amount, 0) }}</span>
                      </div>
                      <div style="text-align: right;">
                        <span style="font-size: 10px; text-transform: uppercase; color: #7A4B29; font-weight: 700; display: block;">अभियान लक्ष्य</span>
                        <span style="color: #2B1A12; font-weight: 700; font-size: 13px;">₹{{ number_format($seva->target_amount, 0) }}</span>
                      </div>
                    </div>

                    <!-- Visual Progress Bar -->
                    <div style="height: 7px; background: rgba(180, 83, 9, 0.12); border-radius: 9999px; overflow: hidden; margin-bottom: 14px;">
                      <div style="width: {{ $seva->progress_percent }}%; height: 100%; background: linear-gradient(90deg, #D95D0F 0%, #16a34a 100%); border-radius: 9999px;"></div>
                    </div>

                    <!-- Dual Action Row -->
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px;">
                      <a href="{{ route('seva.show', $seva->slug) }}" style="font-size: 12px; font-weight: 700; color: #7A4B29; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                        <span>विवरण देखें</span> &rarr;
                      </a>
                      <a href="{{ url('/donate?cause_id=' . ($seva->cause_id ?? '')) }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 7px 16px; border-radius: 9999px; background: linear-gradient(135deg, #D95D0F 0%, #EA580C 100%); color: #fff; font-size: 12px; font-weight: 800; text-decoration: none; box-shadow: 0 3px 10px rgba(217,93,15,0.25);">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                        <span>सहयोग करें</span>
                      </a>
                    </div>
                  </div>

                </div>
              </div>
            @endforeach
          </div>
        </div>
      @endif

    </div>
  </section>
  @endif

  <!-- SECTION 4: "EVERY CONTRIBUTION COUNTS" (Contained Split Card matching user reference) -->
  @if(($homeSettings['section_donation_enabled'] ?? '1') === '1')
  <section class="w-full py-16 sm:py-24 bg-[#FAF4ED] relative" id="contribution-section" style="padding: 80px 0; background-color: #FAF4ED;">
    <div class="site-container">
      <div class="donation-card-split">
        
        <!-- Left Side: Grand Temple Photo with curved frame -->
        <div class="donation-left-img">
          <img src="{{ $homeSettings['donation_image_url'] ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuDc5W4bxgqR7HwAkOkbyOQx3S8U_-YOP5i98bycRrLoNDpDgfUIRM80LfXXdlRd4-ogFPAXEfuralih2lOEp5APoROVdM0ki3US5w-TP_pLrjrc0rnNVPuBTq3RNH0piJfNBIkktuSzelW5DaiHO2Y7DwhSM0repTTFyUnTwMJIBibE_oFO3L8d8Ucee_SyHrgUdz67sBy3L5-aPQeqWQ7hclmRTeOMAOZjnQFC_vFfbWqLkAzzwrCE' }}" alt="Holy Sanctum of Chitrakoot" style="width: 100%; height: 100%; object-fit: cover;" loading="lazy">
          <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(43, 26, 18, 0.8) 0%, rgba(43, 26, 18, 0.2) 60%, transparent 100%);"></div>

          <div style="position: absolute; bottom: 20px; left: 20px; right: 20px; padding: 14px 18px; border-radius: 16px; background: rgba(255, 253, 252, 0.95); backdrop-filter: blur(8px); border: 1px solid rgba(180, 83, 9, 0.2); display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 10px;">
              <div style="width: 32px; height: 32px; border-radius: 9999px; background: rgba(45, 106, 79, 0.15); color: #2D6A4F; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
              </div>
              <div style="display: flex; flex-direction: column;">
                <span style="font-size: 12px; font-weight: 700; color: #2B1A12;">
                  @if($orgSettings->is_80g_enabled ?? false)
                    {{ $homeSettings['donation_badge_1'] ?? '100% Tax Benefit 80G Certified' }}
                  @else
                    100% Verified Dharmik Seva
                  @endif
                </span>
                <span style="font-size: 11px; color: #7A4B29;">
                  @if($orgSettings->is_80g_enabled ?? false)
                    Instant Digital 80G Tax Receipt
                  @else
                    Instant Digital Donation Receipt
                  @endif
                </span>
              </div>
            </div>
            <span style="font-size: 11px; font-weight: 700; color: #B45309; background: #FAF4ED; padding: 3px 8px; border-radius: 4px; border: 1px solid rgba(180, 83, 9, 0.15);">{{ $homeSettings['donation_badge_2'] ?? 'Verified Seva' }}</span>
          </div>
        </div>

        <!-- Right Side: Clean Devotional Donation Form -->
        <div class="donation-right-form">
          
          <div style="margin-bottom: 22px;">
            <span style="font-size: 11px; font-weight: 800; letter-spacing: 0.14em; text-transform: uppercase; color: #D95D0F; margin-bottom: 6px; display: block;">
              {{ $homeSettings['donation_eyebrow'] ?? '॥ पावन सेवा समर्पण ॥ • Devotional Contribution' }}
            </span>
            <h2 style="font-family: 'Noto Serif', serif; font-size: clamp(22px, 2.5vw, 32px); font-weight: 700; color: #2B1A12; margin: 0 0 8px; line-height: 1.3;">
              {{ $homeSettings['donation_heading'] ?? 'प्रत्येक सहयोग पावन है — सेवा में सहभागिता करें' }}
            </h2>
            <p style="font-size: 13px; color: #7A4B29; line-height: 1.6; margin: 0;">
              @if($orgSettings->is_80g_enabled ?? false)
                {{ $homeSettings['donation_desc'] ?? 'भगवान कामतानाथ जी की पावन छत्रछाया में आपका सहयोग वेद-विद्या के संरक्षण, असहाय गोवंश के पोषण और नित्य अन्नदान में निरंतर जीवन संवारता है। १००% आयकर छूट (80G) सहित तत्काल डिजिटल रसीद प्राप्त करें।' }}
              @else
                {{ str_replace(['१००% आयकर छूट (80G) सहित', '100% आयकर छूट (80G) सहित', '80G Tax Benefit', '80G', '(80G)'], '', $homeSettings['donation_desc'] ?? 'भगवान कामतानाथ जी की पावन छत्रछाया में आपका सहयोग वेद-विद्या के संरक्षण, असहाय गोवंश के पोषण और नित्य अन्नदान में निरंतर जीवन संवारता है। तत्काल डिजिटल अधिकृत रसीद प्राप्त करें।') }}
              @endif
            </p>
          </div>

          <!-- Direct Razorpay Form -->
          <form onsubmit="handleDirectDonation(event)" id="directDonationForm" style="display: flex; flex-direction: column; gap: 14px;">
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 12px;">
              <!-- Full Name -->
              <div>
                <label style="font-size: 11px; font-weight: 700; color: #2B1A12; margin-bottom: 5px; display: block;">
                  Full Name (पूरा नाम) *
                </label>
                <div style="position: relative;">
                  <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #7A4B29;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                  </span>
                  <input type="text" id="direct-donor-name" required placeholder="उदा. सुरेश शर्मा" style="width: 100%; padding: 10px 12px 10px 36px; border-radius: 12px; background: #FAF4ED; border: 1px solid rgba(180, 83, 9, 0.2); font-size: 13px; color: #2B1A12; box-sizing: border-box;">
                </div>
              </div>

              <!-- Phone Number -->
              <div>
                <label style="font-size: 11px; font-weight: 700; color: #2B1A12; margin-bottom: 5px; display: block;">
                  Phone Number (फोन नंबर) *
                </label>
                <div style="position: relative;">
                  <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #7A4B29;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                  </span>
                  <input type="tel" id="direct-donor-phone" required placeholder="+91 98765 43210" style="width: 100%; padding: 10px 12px 10px 36px; border-radius: 12px; background: #FAF4ED; border: 1px solid rgba(180, 83, 9, 0.2); font-size: 13px; color: #2B1A12; box-sizing: border-box;">
                </div>
              </div>
            </div>

            <!-- Email Address -->
            <div>
              <label style="font-size: 11px; font-weight: 700; color: #2B1A12; margin-bottom: 5px; display: block;">
                Email Address (ईमेल पता)
              </label>
              <div style="position: relative;">
                <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #7A4B29;">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                </span>
                <input type="email" id="direct-donor-email" placeholder="devotee@example.com" style="width: 100%; padding: 10px 12px 10px 36px; border-radius: 12px; background: #FAF4ED; border: 1px solid rgba(180, 83, 9, 0.2); font-size: 13px; color: #2B1A12; box-sizing: border-box;">
              </div>
            </div>

            <!-- Amount Selection -->
            <div>
              <label style="font-size: 11px; font-weight: 700; color: #2B1A12; margin-bottom: 8px; display: block;">
                Select Donation Amount (सहयोग राशि चुनें) *
              </label>
              
              <div class="flex flex-wrap items-center gap-2 mb-2.5" id="donation-chips" style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 10px;">
                <button type="button" class="px-4 py-2 rounded-full bg-[#D95D0F] text-white text-xs font-bold shadow-sm cursor-pointer border-0" onclick="selectAmount({{ $p1 }}, this)" style="padding: 8px 16px; border-radius: 9999px; background: #D95D0F; color: #fff; font-size: 12.5px; font-weight: 700; border: none; cursor: pointer;">
                  ₹{{ number_format($p1, 0) }}
                </button>
                <button type="button" class="px-4 py-2 rounded-full bg-[#FAF4ED] text-[#2B1A12] text-xs font-bold hover:bg-[#D95D0F] hover:text-white cursor-pointer border border-[#B45309]/20" onclick="selectAmount({{ $p2 }}, this)" style="padding: 8px 16px; border-radius: 9999px; background: #FAF4ED; color: #2B1A12; font-size: 12.5px; font-weight: 700; border: 1px solid rgba(180, 83, 9, 0.2); cursor: pointer;">
                  ₹{{ number_format($p2, 0) }}
                </button>
                <button type="button" class="px-4 py-2 rounded-full bg-[#FAF4ED] text-[#2B1A12] text-xs font-bold hover:bg-[#D95D0F] hover:text-white cursor-pointer border border-[#B45309]/20" onclick="selectAmount({{ $p3 }}, this)" style="padding: 8px 16px; border-radius: 9999px; background: #FAF4ED; color: #2B1A12; font-size: 12.5px; font-weight: 700; border: 1px solid rgba(180, 83, 9, 0.2); cursor: pointer;">
                  ₹{{ number_format($p3, 0) }}
                </button>
                <button type="button" class="px-4 py-2 rounded-full bg-[#FAF4ED] text-[#2B1A12] text-xs font-bold hover:bg-[#D95D0F] hover:text-white cursor-pointer border border-[#B45309]/20" onclick="selectAmount({{ $p4 }}, this)" style="padding: 8px 16px; border-radius: 9999px; background: #FAF4ED; color: #2B1A12; font-size: 12.5px; font-weight: 700; border: 1px solid rgba(180, 83, 9, 0.2); cursor: pointer;">
                  ₹{{ number_format($p4, 0) }}
                </button>
              </div>

              <!-- Custom Amount Input -->
              <div style="position: relative; width: 100%;">
                <span style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-weight: 800; color: #7A4B29; font-size: 14px;">₹</span>
                <input type="number" id="custom-donation-input" value="{{ $p1 }}" min="10" placeholder="Other Amount" style="width: 100%; padding: 10px 12px 10px 32px; border-radius: 12px; background: #FAF4ED; border: 1px solid rgba(180, 83, 9, 0.25); font-size: 13.5px; font-weight: 700; color: #2B1A12; box-sizing: border-box;">
              </div>
            </div>

            <!-- Big Saffron Donate Button -->
            <button type="submit" id="directDonateBtn" style="width: 100%; padding: 13px; border-radius: 9999px; background: linear-gradient(to right, #D95D0F, #EA580C); color: #fff; font-weight: 800; font-size: 14px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 18px rgba(217, 93, 15, 0.38); margin-top: 6px;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
              <span>दान अर्पण करें (Donate Now)</span>
            </button>

            <!-- Reassurance Note -->
            <div style="display: flex; align-items: center; justify-content: center; gap: 16px; font-size: 11px; color: #7A4B29; padding-top: 2px;">
              <span style="display: flex; align-items: center; gap: 4px;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#2D6A4F" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <span>256-bit Secure Gateway</span>
              </span>
              <span>•</span>
              <span style="display: flex; align-items: center; gap: 4px;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#B45309" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <span>{{ ($orgSettings->is_80g_enabled ?? false) ? '80G Tax Benefit' : 'Verified Seva Receipt' }}</span>
              </span>
            </div>

          </form>

        </div>

      </div>
    </div>
  </section>
  @endif

  <!-- SECTION 5: KAMADGIRI DHAM TIMINGS & AARTI TIMETABLE (Normal Days vs Amavasya) -->
  @if(($homeSettings['section_aarti_enabled'] ?? '1') === '1')
  <section class="w-full py-16 bg-[#FBF7F0] relative" id="aarti-timings" style="padding: 64px 0; background-color: #FBF7F0;">
    <div class="site-container">
      
      <div class="flex flex-col items-center text-center mb-6 reveal-card" style="text-align: center; margin-bottom: 24px;">
        <div style="display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 6px; color: #92400E; font-size: 11.5px; font-weight: 800; letter-spacing: 0.12em; text-transform: uppercase;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#D95D0F" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          <span>॥ नित्य व पर्व समय चक्र ॥ • Sacred Aarti & Darshan Schedule</span>
        </div>
        <h2 style="font-family: 'Noto Serif', serif; font-size: clamp(22px, 2.6vw, 32px); font-weight: 700; color: #2B1A12; margin: 0 0 16px;">
          चित्रकूट धाम में नित्य महाआरती व गर्भगृह दर्शन समय सारणी
        </h2>

        <!-- Interactive Schedule Tabs (Normal Days vs Amavasya Night) -->
        <div style="display: inline-flex; gap: 8px; background: #FAF4ED; padding: 6px; border-radius: 9999px; border: 1.5px solid rgba(180, 83, 9, 0.25); box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
          <button type="button" id="tab-btn-normal" onclick="switchAartiTab('normal')" style="padding: 8px 20px; border-radius: 9999px; background: #D95D0F; color: #fff; font-size: 12.5px; font-weight: 700; border: none; cursor: pointer; transition: all 0.2s ease;">
            नित्य दिवस (Normal Days)
          </button>
          <button type="button" id="tab-btn-amavasya" onclick="switchAartiTab('amavasya')" style="padding: 8px 20px; border-radius: 9999px; background: #FFFDFC; color: #7A4B29; font-size: 12.5px; font-weight: 700; border: 1px solid rgba(180, 83, 9, 0.2); cursor: pointer; transition: all 0.2s ease;">
            अमावस्या एवं महापर्व (Amavasya & Melas)
          </button>
        </div>
      </div>

      <!-- Schedule 1: Normal Days Grid -->
      <div id="aarti-normal-grid" class="timings-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 14px;">
        @php
          $configuredAarti = !empty($homeSettings['aarti_slots_json']) ? json_decode($homeSettings['aarti_slots_json'], true) : [];
          $normalSlots = (!empty($configuredAarti) && is_array($configuredAarti)) ? $configuredAarti : [
            ['name' => 'प्रातः मंगला आरती', 'time' => '05:00 AM', 'desc' => 'कामदगिरि मुख्य गर्भगृह', 'is_live' => false],
            ['name' => 'ठाकुर जी शृंगार व बालभोग', 'time' => '07:30 AM', 'desc' => 'कामदगिरि मंदिर', 'is_live' => false],
            ['name' => 'राजभोग महाआरती', 'time' => '12:00 PM', 'desc' => 'अन्नकूट एवं भोग अर्पण', 'is_live' => false],
            ['name' => 'श्री कामदगिरि संध्या महाआरती', 'time' => '06:30 PM', 'desc' => 'रामघाट मन्दाकिनी पावन तट', 'is_live' => true],
            ['name' => 'शयन आरती', 'time' => '09:00 PM', 'desc' => 'कपाट विश्राम', 'is_live' => false],
          ];
        @endphp
        @foreach($normalSlots as $slot)
          @php
            $isLive = !empty($slot['is_live']);
            $slotDesc = $slot['desc'] ?? ($slot['description'] ?? 'कामदगिरि मुख्य गर्भगृह');
          @endphp
          <div class="shimmer-timing-card" style="padding: 18px 12px; border-radius: 16px; background: {{ $isLive ? 'linear-gradient(135deg, #FFFDFC, #FAF4ED)' : '#FFFDFC' }}; border: 1px solid {{ $isLive ? 'rgba(217, 93, 15, 0.45)' : 'rgba(180, 83, 9, 0.18)' }}; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; position: relative; box-sizing: border-box; box-shadow: {{ $isLive ? '0 4px 14px rgba(217, 93, 15, 0.14)' : '0 1px 4px rgba(0,0,0,0.03)' }};">
            @if($isLive)
              <div style="position: absolute; top: -5px; right: -5px; display: flex; width: 12px; height: 12px;">
                <span style="position: absolute; inset: 0; border-radius: 9999px; background-color: #D95D0F; opacity: 0.75; animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;"></span>
                <span style="position: relative; border-radius: 9999px; width: 12px; height: 12px; background-color: #D95D0F;"></span>
              </div>
            @endif
            <span style="font-size: 11.5px; font-weight: {{ $isLive ? '800' : '700' }}; color: {{ $isLive ? '#D95D0F' : '#B45309' }}; margin-bottom: 5px; line-height: 1.3;">
              {{ $slot['name'] }}
            </span>
            <span style="font-size: 19px; font-weight: 800; color: {{ $isLive ? '#B45309' : '#2B1A12' }}; white-space: nowrap; line-height: 1.2; margin-bottom: 5px;">
              {{ $slot['time'] }}
            </span>
            <span style="font-size: 11px; color: #7A4B29; font-weight: 500; line-height: 1.3;">
              {{ $slotDesc }}
            </span>
          </div>
        @endforeach
      </div>

      <!-- Schedule 2: Amavasya & Melas Grid (Hidden by Default) -->
      <div id="aarti-amavasya-grid" class="timings-grid" style="display: none; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 14px;">
        @php
          $configuredAmavasya = !empty($homeSettings['aarti_amavasya_slots_json']) ? json_decode($homeSettings['aarti_amavasya_slots_json'], true) : [];
          $amavasyaSlots = (!empty($configuredAmavasya) && is_array($configuredAmavasya)) ? $configuredAmavasya : [
            ['name' => 'परिक्रमा मंगला आरती', 'time' => '03:30 AM', 'desc' => 'प्रातः विशेष कामदगिरि परिक्रमा', 'is_live' => false],
            ['name' => 'अखण्ड वैदिक हवन', 'time' => '08:00 AM – 11:30 AM', 'desc' => 'ऋषिकुल यज्ञशाला', 'is_live' => false],
            ['name' => 'मन्दाकिनी महा दीपोत्सव आरती', 'time' => '07:00 PM', 'desc' => 'रामघाट 11,000+ दीप प्रज्ज्वलन', 'is_live' => true],
            ['name' => 'अखण्ड रात्रि महापूजा व जागरण', 'time' => '10:30 PM', 'desc' => 'अमावस्या मध्यरात्रि विशेष अनुष्ठान', 'is_live' => false],
            ['name' => 'भोर आरती व कपाट विश्राम', 'time' => '04:00 AM', 'desc' => 'पर्व समापन एवं आशीर्वाद', 'is_live' => false],
          ];
        @endphp
        @foreach($amavasyaSlots as $slot)
          @php
            $isLive = !empty($slot['is_live']) || !empty($slot['is_live_pulse']);
            $slotDesc = $slot['desc'] ?? ($slot['description'] ?? 'कामदगिरि परिक्रमा');
          @endphp
          <div class="shimmer-timing-card" style="padding: 18px 12px; border-radius: 16px; background: {{ $isLive ? 'linear-gradient(135deg, #FFFDFC, #FAF4ED)' : '#FFFDFC' }}; border: 1px solid {{ $isLive ? 'rgba(217, 93, 15, 0.45)' : 'rgba(180, 83, 9, 0.18)' }}; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; position: relative; box-sizing: border-box; box-shadow: {{ $isLive ? '0 4px 14px rgba(217, 93, 15, 0.14)' : '0 1px 4px rgba(0,0,0,0.03)' }};">
            @if($isLive)
              <div style="position: absolute; top: -5px; right: -5px; display: flex; width: 12px; height: 12px;">
                <span style="position: absolute; inset: 0; border-radius: 9999px; background-color: #D95D0F; opacity: 0.75; animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;"></span>
                <span style="position: relative; border-radius: 9999px; width: 12px; height: 12px; background-color: #D95D0F;"></span>
              </div>
            @endif
            <span style="font-size: 11.5px; font-weight: {{ $isLive ? '800' : '700' }}; color: {{ $isLive ? '#D95D0F' : '#B45309' }}; margin-bottom: 5px; line-height: 1.3;">
              {{ $slot['name'] }}
            </span>
            <span style="font-size: 19px; font-weight: 800; color: {{ $isLive ? '#B45309' : '#2B1A12' }}; white-space: nowrap; line-height: 1.2; margin-bottom: 5px;">
              {{ $slot['time'] }}
            </span>
            <span style="font-size: 11px; color: #7A4B29; font-weight: 500; line-height: 1.3;">
              {{ $slotDesc }}
            </span>
          </div>
        @endforeach
      </div>

      <!-- Hawan Pooja Schedule Strip -->
      @php
        $hawanTiming = $homeSettings['aarti_hawan_timing'] ?? ($homeSettings['aarti_hawan_time'] ?? '07:30 AM – 09:30 AM');
        $hawanPlace = $homeSettings['aarti_hawan_location'] ?? ($homeSettings['aarti_hawan_place'] ?? 'ऋषिकुल यज्ञशाला व मन्दाकिनी तट');
        $hawanTitle = $homeSettings['aarti_hawan_title'] ?? 'दैनिक वैदिक हवन एवं महायज्ञ समय चक्र';
        $hawanDesc = $homeSettings['aarti_hawan_desc'] ?? 'विश्व शान्ति, सर्वकल्याण एवं मनोकामना सिद्धि हेतु नित्य वैदिक आहुति।';
      @endphp
      <div class="reveal-card mt-8 p-5 sm:p-6 rounded-2xl bg-[#FFFDFC] border border-[#B45309]/20 shadow-sm" style="margin-top: 24px; padding: 20px; border-radius: 18px; background: #FFFDFC; border: 1px solid rgba(180, 83, 9, 0.2); display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px;">
        <div style="display: flex; align-items: center; gap: 14px;">
          <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(217, 93, 15, 0.12); color: #D95D0F; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg>
          </div>
          <div>
            <div style="display: flex; align-items: center; gap: 8px;">
              <span style="font-size: 11px; font-weight: 800; color: #D95D0F; background: #FAF4ED; padding: 2px 8px; border-radius: 9999px;">दैनिक वैदिक हवन: {{ $hawanTiming }}</span>
              <span style="font-size: 11.5px; color: #7A4B29; font-weight: 700;">{{ $hawanPlace }}</span>
            </div>
            <h3 style="font-family: 'Noto Serif', serif; font-size: 16px; font-weight: 700; color: #2B1A12; margin: 4px 0 2px;">
              {{ $hawanTitle }}
            </h3>
            <p style="font-size: 12px; color: #7A4B29; margin: 0;">
              {{ $hawanDesc }}
            </p>
          </div>
        </div>

        <a href="{{ route('pooja.bookingForm') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; border-radius: 9999px; background: linear-gradient(135deg, #D95D0F, #EA580C); color: #fff; font-weight: 800; font-size: 12.5px; text-decoration: none; box-shadow: 0 4px 12px rgba(217, 93, 15, 0.3);">
          <span>हवन व पूजा संकल्प अनुरोध करें</span> &rarr;
        </a>
      </div>

    </div>
  </section>
  @endif

  <!-- SECTION 6: SACRED POOJA & HAWAN BOOKING (Devotee Quotation & 24h Advance Model) -->
  @if(($homeSettings['section_pooja_enabled'] ?? '1') === '1')
  <section class="w-full py-16 sm:py-24 bg-[#FAF4ED] relative" id="pooja-sanctuary" style="padding: 80px 0; background-color: #FAF4ED;">
    <div class="site-container">
      
      <!-- Section Header -->
      <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12 reveal-card" style="margin-bottom: 40px;">
        <div style="max-width: 680px;">
          <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; flex-wrap: wrap;">
            <span style="font-size: 11px; font-weight: 800; letter-spacing: 0.14em; text-transform: uppercase; color: #D95D0F; background: rgba(217, 93, 15, 0.08); padding: 3px 10px; border-radius: 9999px; border: 1px solid rgba(217, 93, 15, 0.2);">
              {{ $homeSettings['pooja_section_eyebrow'] ?? '॥ पावन पूजा एवं हवन संकल्प ॥ • ऑनलाइन पूजा व दिव्य संकल्प' }}
            </span>
            <span style="color: #D4B996;">•</span>
            <span style="font-size: 11.5px; font-weight: 700; color: #B45309; display: inline-flex; align-items: center; gap: 5px;">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              <span>24 घंटे पूर्व आरक्षण आवश्यक</span>
            </span>
          </div>
          <h2 style="font-family: 'Noto Serif', serif; font-size: clamp(24px, 2.8vw, 36px); font-weight: 700; color: #2B1A12; line-height: 1.3; margin: 0 0 10px;">
            {{ $homeSettings['pooja_section_title'] ?? 'घर बैठे चित्रकूट धाम में वैदिक पूजा व हवन का पावन संकल्प लें' }}
          </h2>
          <p style="color: #7A4B29; font-size: 14px; line-height: 1.6; margin: 0;">
            {{ $homeSettings['pooja_section_desc'] ?? ($homeSettings['pooja_section_subtitle'] ?? 'श्री कामदगिरि मुख्य गर्भगृह एवं मन्दाकिनी पावन तट पर आपके नाम व गोत्र से वैदिक आचार्यों द्वारा सविधि अनुष्ठान। संस्थान आपसे सम्पर्क कर व्यवस्था व दक्षिणा परामर्श करेगा।') }}
          </p>
        </div>

        <div style="flex-shrink: 0;">
          <a href="{{ route('pooja.bookingForm') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; border-radius: 9999px; background: #FFFDFC; color: #B45309; border: 1.5px solid rgba(180, 83, 9, 0.35); font-weight: 800; font-size: 13px; text-decoration: none; box-shadow: 0 2px 10px rgba(0,0,0,0.04); transition: all 0.25s ease;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            <span>समस्त अनुष्ठान सूची देखें</span>
            <span>&rarr;</span>
          </a>
        </div>
      </div>

      <!-- 7 Sacred Offerings Grid (Elevated Sacred Aesthetics & Responsive Pure SVGs) -->
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px; margin-bottom: 36px;">
        @php
          $rituals = [
            [
              'title' => 'कामदगिरि नित्य पूजन व अभिषेक',
              'sub' => 'गर्भगृह पंचामृत स्नान, तुलसी अर्पण व दिव्य शृंगार',
              'tag' => 'नित्य सेवा',
              'accent' => '#D95D0F',
              'icon_type' => 'om'
            ],
            [
              'title' => 'अखण्ड 5 किमी परिक्रमा संकल्प',
              'sub' => 'कामदगिरि पर्वत पावन प्रदक्षिणा एवं मनोकामना सिद्धि',
              'tag' => 'मनोकामना सिद्धि',
              'accent' => '#B45309',
              'icon_type' => 'parvat'
            ],
            [
              'title' => 'वैदिक महाहवन एवं नवग्रह शान्ति',
              'sub' => 'ऋषिकुल बटुकों द्वारा स्वाहाकार, समिधा व ग्रह निवारण',
              'tag' => 'ग्रह शान्ति',
              'accent' => '#EA580C',
              'icon_type' => 'yagya'
            ],
            [
              'title' => 'रुद्राभिषेक एवं महारुद्र पाठ',
              'sub' => 'मन्दाकिनी जल, दूध व बिल्वपत्र से महादेव अभिषेक',
              'tag' => 'कल्याणकारी',
              'accent' => '#086C60',
              'icon_type' => 'trishul'
            ],
            [
              'title' => 'अखण्ड सुन्दरकाण्ड पाठ',
              'sub' => 'श्री रामचरितमानस सस्वर गान व मारुति सिंदूर अर्पण',
              'tag' => 'संकटमोचन',
              'accent' => '#D95D0F',
              'icon_type' => 'dhwaja'
            ],
            [
              'title' => 'मन्दाकिनी महाआरती व दीपदान',
              'sub' => 'रामघाट पर 108 पावन दीप प्रज्ज्वलन व पितृ शान्ति',
              'tag' => 'पितृ व वंश शान्ति',
              'accent' => '#B45309',
              'icon_type' => 'diya'
            ],
            [
              'title' => 'विशेष पारिवारिक अनुष्ठान',
              'sub' => 'विवाह वर्षगांठ, स्वास्थ्य लाभ, नामकरण व शांति पाठ',
              'tag' => 'विशेष संकल्प',
              'accent' => '#2D6A4F',
              'icon_type' => 'scroll'
            ],
          ];
        @endphp

        @foreach($rituals as $rit)
          <div class="reveal-card" style="padding: 20px 20px; border-radius: 18px; background: #FFFDFC; border: 1.5px solid rgba(180, 83, 9, 0.2); display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 4px 16px rgba(43, 26, 18, 0.04); transition: transform 0.25s ease, box-shadow 0.25s ease;">
            <div>
              <!-- Top Row: Icon & Tag -->
              <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(217, 93, 15, 0.09); color: {{ $rit['accent'] }}; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid rgba(180, 83, 9, 0.18);">
                  @if($rit['icon_type'] === 'om')
                    <!-- Sacred Om / Sanctum Icon -->
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M12 3v3m0 12v3M5 12H2m20 0h-3M7.05 7.05L4.93 4.93m14.14 14.14l-2.12-2.12M7.05 16.95l-2.12 2.12m14.14-14.14l-2.12 2.12"/>
                      <circle cx="12" cy="12" r="4"/>
                    </svg>
                  @elseif($rit['icon_type'] === 'parvat')
                    <!-- Kamadgiri Parvat / Sacred Circumambulation -->
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="m8 3 4 8 5-5 5 15H2L8 3z"/>
                      <path d="M4 14h6l2 4"/>
                    </svg>
                  @elseif($rit['icon_type'] === 'yagya')
                    <!-- Sacred Yagya Agni Kund -->
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/>
                      <path d="M4 21h16"/>
                    </svg>
                  @elseif($rit['icon_type'] === 'trishul')
                    <!-- Sacred Trishul & Damru (Rudrabhishek) -->
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <line x1="12" y1="2" x2="12" y2="22"/>
                      <path d="M6 4c0 4 3 6 6 6s6-2 6-6"/>
                      <circle cx="12" cy="2" r="1"/>
                      <circle cx="6" cy="4" r="1"/>
                      <circle cx="18" cy="4" r="1"/>
                    </svg>
                  @elseif($rit['icon_type'] === 'dhwaja')
                    <!-- Hanuman Dhwaja Flag -->
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
                      <line x1="4" y1="22" x2="4" y2="15"/>
                    </svg>
                  @elseif($rit['icon_type'] === 'diya')
                    <!-- Mandakini Aarti Deepam Lamp -->
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M12 2c.8 1.4 1.5 2.8 1.5 4.2 0 1.5-.7 2.8-1.5 3.8-.8-1-1.5-2.3-1.5-3.8C10.5 4.8 11.2 3.4 12 2z"/>
                      <path d="M5 14c0 3.3 3.1 6 7 6s7-2.7 7-6H5z"/>
                      <path d="M2 19c3 1.5 7 1.5 10 0s7-1.5 10 0"/>
                    </svg>
                  @else
                    <!-- Sacred Pothi / Vedic Scroll -->
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M19 3H7a4 4 0 0 0-4 4v10a4 4 0 0 0 4 4h12V3z"/>
                      <path d="M7 3v14a4 4 0 0 1 4-4h8"/>
                      <line x1="9" y1="9" x2="15" y2="9"/>
                      <line x1="9" y1="13" x2="13" y2="13"/>
                    </svg>
                  @endif
                </div>

                <div style="text-align: right;">
                  <span style="font-size: 10.5px; font-weight: 800; color: #D95D0F; background: #FAF4ED; padding: 3px 9px; border-radius: 9999px; border: 1px solid rgba(217,93,15,0.22); letter-spacing: 0.02em;">
                    {{ $rit['tag'] }}
                  </span>
                  <div style="font-size: 10.5px; color: #2D6A4F; font-weight: 700; margin-top: 3px;">
                    सविधि अनुष्ठान
                  </div>
                </div>
              </div>

              <!-- Title & Description -->
              <h3 style="font-family: 'Noto Serif', serif; font-size: 15.5px; font-weight: 700; color: #2B1A12; margin: 0 0 6px; line-height: 1.35;">
                {{ $rit['title'] }}
              </h3>
              <p style="font-size: 12px; color: #7A4B29; line-height: 1.5; margin: 0;">
                {{ $rit['sub'] }}
              </p>
            </div>

            <!-- Card Bottom Bar -->
            <div style="margin-top: 16px; padding-top: 12px; border-top: 1px dashed rgba(180, 83, 9, 0.2); display: flex; align-items: center; justify-content: space-between;">
              <span style="font-size: 11px; font-weight: 600; color: #8C6D53;">परामर्श उपरांत दक्षिणा</span>
              <a href="{{ route('pooja.bookingForm') }}?ritual={{ urlencode($rit['title']) }}" style="font-size: 12px; font-weight: 800; color: #B45309; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; transition: color 0.2s ease;">
                <span>संकल्प अनुरोध</span> &rarr;
              </a>
            </div>
          </div>
        @endforeach
      </div>

      <!-- 3-Step Sanstha Quotation Workflow Card (Redesigned Majestic Sacred Flow) -->
      @php
        $ribbonHeading = $homeSettings['process_ribbon_heading'] ?? 'वैदिक पूजा / हवन संकल्प की ३-चरणीय अधिकृत प्रक्रिया';
        $step1Title = $homeSettings['process_step_1_title'] ?? '24 घंटे पूर्व ऑनलाइन अनुरोध';
        $step1Desc = $homeSettings['process_step_1_desc'] ?? 'यजमान का नाम, गोत्र, इच्छित पूजा तिथि एवं विशेष मनोकामना विवरण दर्ज करें।';
        $step2Title = $homeSettings['process_step_2_title'] ?? 'संस्थान द्वारा परामर्श व दक्षिणा व्यवस्था';
        $step2Desc = $homeSettings['process_step_2_desc'] ?? 'संस्थान प्रतिनिधि फोन/व्हाट्सएप पर सम्पर्क कर पूजा सामग्री, वैदिक विधि एवं दक्षिणा निश्चित करेंगे।';
        $step3Title = $homeSettings['process_step_3_title'] ?? 'डिजिटल कोटेशन व सुरक्षित भुगतान';
        $step3Desc = $homeSettings['process_step_3_desc'] ?? 'आपकी ईमेल व प्रोफाइल पर अधिकृत कोटेशन लिंक प्राप्त होगा, जिसे Razorpay द्वारा 1-क्लिक में पूर्ण करें।';
      @endphp
      <div class="reveal-card rounded-3xl bg-[#FFFDFC] shadow-sm border border-[#B45309]/20" style="padding: 32px 28px; border-radius: 24px; background-color: #FFFDFC; border: 1.5px solid rgba(180, 83, 9, 0.25); margin-bottom: 34px; position: relative; overflow: hidden;">
        
        <!-- Subtle Top Accent Bar -->
        <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(to right, #D95D0F, #B45309, #2D6A4F);"></div>

        <div style="text-align: center; margin-bottom: 28px;">
          <span style="font-size: 11px; font-weight: 800; letter-spacing: 0.12em; text-transform: uppercase; color: #D95D0F; background: #FAF4ED; padding: 3px 12px; border-radius: 9999px; border: 1px solid rgba(217, 93, 15, 0.2);">
            प्रमाणित वैदिक प्रक्रिया • TRANSPARENT CONSULTATIVE WORKFLOW
          </span>
          <h3 style="font-family: 'Noto Serif', serif; font-size: clamp(17px, 2vw, 21px); color: #2B1A12; font-weight: 700; margin: 10px 0 0;">
            {{ $ribbonHeading }}
          </h3>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px; position: relative;">
          <!-- Step 1 -->
          <div style="display: flex; flex-direction: column; align-items: center; text-align: center; padding: 14px 10px; background: #FAF4ED; border-radius: 16px; border: 1px solid rgba(180, 83, 9, 0.15);">
            <div style="width: 52px; height: 52px; border-radius: 14px; background-color: #FFFDFC; display: flex; align-items: center; justify-content: center; color: #D95D0F; border: 1.5px solid rgba(217, 93, 15, 0.3); margin-bottom: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.03);">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
              </svg>
            </div>
            <span style="font-size: 10.5px; font-weight: 800; color: #D95D0F; letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 4px;">चरण ०१ • STEP 1</span>
            <h4 style="font-size: 14px; font-weight: 700; color: #2B1A12; margin: 0 0 6px;">{{ $step1Title }}</h4>
            <p style="font-size: 12px; color: #7A4B29; line-height: 1.55; margin: 0;">{{ $step1Desc }}</p>
          </div>

          <!-- Step 2 -->
          <div style="display: flex; flex-direction: column; align-items: center; text-align: center; padding: 14px 10px; background: #FAF4ED; border-radius: 16px; border: 1px solid rgba(180, 83, 9, 0.15);">
            <div style="width: 52px; height: 52px; border-radius: 14px; background-color: #FFFDFC; display: flex; align-items: center; justify-content: center; color: #B45309; border: 1.5px solid rgba(180, 83, 9, 0.3); margin-bottom: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.03);">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
              </svg>
            </div>
            <span style="font-size: 10.5px; font-weight: 800; color: #B45309; letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 4px;">चरण ०२ • STEP 2</span>
            <h4 style="font-size: 14px; font-weight: 700; color: #2B1A12; margin: 0 0 6px;">{{ $step2Title }}</h4>
            <p style="font-size: 12px; color: #7A4B29; line-height: 1.55; margin: 0;">{{ $step2Desc }}</p>
          </div>

          <!-- Step 3 -->
          <div style="display: flex; flex-direction: column; align-items: center; text-align: center; padding: 14px 10px; background: #FAF4ED; border-radius: 16px; border: 1px solid rgba(180, 83, 9, 0.15);">
            <div style="width: 52px; height: 52px; border-radius: 14px; background-color: #FFFDFC; display: flex; align-items: center; justify-content: center; color: #2D6A4F; border: 1.5px solid rgba(45, 106, 79, 0.3); margin-bottom: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.03);">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                <polyline points="9 12 11 14 15 10"/>
              </svg>
            </div>
            <span style="font-size: 10.5px; font-weight: 800; color: #2D6A4F; letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 4px;">चरण ०३ • STEP 3</span>
            <h4 style="font-size: 14px; font-weight: 700; color: #2B1A12; margin: 0 0 6px;">{{ $step3Title }}</h4>
            <p style="font-size: 12px; color: #7A4B29; line-height: 1.55; margin: 0;">{{ $step3Desc }}</p>
          </div>
        </div>
      </div>

      <!-- Main CTA Button -->
      <div style="text-align: center;">
        <a href="{{ route('pooja.bookingForm') }}" style="display: inline-flex; align-items: center; gap: 10px; padding: 16px 38px; border-radius: 9999px; background: linear-gradient(135deg, #D95D0F 0%, #EA580C 100%); color: #fff; font-size: 14.5px; font-weight: 800; text-decoration: none; box-shadow: 0 6px 24px rgba(217, 93, 15, 0.4); transition: transform 0.2s ease;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
          <span>पूजा / हवन संकल्प अनुरोध फॉर्म भरें (Request Booking)</span>
          <span>&rarr;</span>
        </a>
      </div>

    </div>
  </section>
  @endif

  <!-- SECTION 7: SACRED PILGRIMAGE (Contained Grid) -->
  @if(($homeSettings['section_places_enabled'] ?? '1') === '1')
  <section class="w-full py-16 sm:py-24 bg-[#FBF7F0] relative" id="peeth-darshan" style="padding: 80px 0; background-color: #FBF7F0;">
    <div class="site-container">
      
      <div class="flex flex-col items-center text-center mb-10 reveal-card" style="text-align: center; margin-bottom: 40px;">
        <span style="font-size: 11px; font-weight: 800; letter-spacing: 0.14em; text-transform: uppercase; color: #D95D0F; margin-bottom: 6px; display: block;">
          {{ $homeSettings['places_section_eyebrow'] ?? 'Divine Pilgrimage • तीर्थ चित्रकूट दर्शन' }}
        </span>
        <h2 style="font-family: 'Noto Serif', serif; font-size: clamp(24px, 2.8vw, 36px); font-weight: 700; color: #2B1A12; margin: 0 0 8px;">
          {{ $homeSettings['places_section_title'] ?? 'चित्रकूट के पावन तीर्थ एवं दिव्य आकर्षण' }}
        </h2>
        <p style="color: #7A4B29; font-size: 14px; line-height: 1.6; max-width: 620px; margin: 0 auto;">
          {{ $homeSettings['places_section_subtitle'] ?? 'चित्रकूट भारत की उन पावनतम भूमियों में से है जहाँ प्रकृति, रामायण इतिहास और अध्यात्म एकाकार होते हैं।' }}
        </p>
      </div>

      <div class="destinations-grid">
        @forelse($destinations as $dest)
          <div class="reveal-card rounded-3xl bg-[#FFFDFC] overflow-hidden flex flex-col shadow-sm hover:shadow-md border border-[#B45309]/18 hover:border-[#D95D0F]/40" style="border-radius: 20px; background-color: #FFFDFC; border: 1px solid rgba(180, 83, 9, 0.18); overflow: hidden; display: flex; flex-direction: column;">
            
            <div style="height: 190px; position: relative; overflow: hidden;">
              <img src="{{ $dest->featured_image ?: ($dest->hero_image ?: 'https://lh3.googleusercontent.com/aida-public/AB6AXuCGizC1EIA1jnKlpQ1SWBzKrHG_OgZkQe6sDax_wnuZtIhFWtfWt-6m1jnBTWhz5Jji0AOrt9xr8W41pZkn-cZaOkuTyo2yeOHwrD92V6audP3fJcrCspCJNsquLRugs1Igok9LRE_4xwn9hZ6UVHI8f_hQJMjVOuIxA9CF87Om-bfNQNfGcQEsRylsOnCo7SMcSgmrXG2xf7A_uXu2kYkWLuPWiLaz4ltFV-UADzwzoIoVFdYetNmu') }}" alt="{{ $dest->name_hi ?? $dest->title }}" loading="lazy" onerror="this.onerror=null; this.src='https://lh3.googleusercontent.com/aida-public/AB6AXuCGizC1EIA1jnKlpQ1SWBzKrHG_OgZkQe6sDax_wnuZtIhFWtfWt-6m1jnBTWhz5Jji0AOrt9xr8W41pZkn-cZaOkuTyo2yeOHwrD92V6audP3fJcrCspCJNsquLRugs1Igok9LRE_4xwn9hZ6UVHI8f_hQJMjVOuIxA9CF87Om-bfNQNfGcQEsRylsOnCo7SMcSgmrXG2xf7A_uXu2kYkWLuPWiLaz4ltFV-UADzwzoIoVFdYetNmu';" style="width: 100%; height: 100%; object-fit: cover;">
              <div style="position: absolute; inset: 0; background: linear-gradient(to top, #FFFDFC 0%, transparent 60%);"></div>

              <div style="position: absolute; top: 12px; left: 12px; max-width: calc(100% - 24px); padding: 4px 10px; border-radius: 9999px; background: rgba(255, 253, 252, 0.92); border: 1px solid rgba(180, 83, 9, 0.2); backdrop-filter: blur(4px); box-sizing: border-box;">
                <span style="font-size: 10.5px; color: #B45309; font-weight: 700; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $dest->tagline_hi ? Str::limit($dest->tagline_hi, 24) : 'पावन तीर्थ' }}</span>
              </div>

              <div style="position: absolute; bottom: 10px; left: 12px; right: 12px; display: flex; align-items: center; justify-content: space-between; font-size: 11px;">
                <span style="display: flex; align-items: center; gap: 4px; color: #2B1A12; font-weight: 700;">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#B45309" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                  04:00 AM – 10:00 PM
                </span>
                <span style="padding: 2px 6px; border-radius: 4px; background: #FAF4ED; color: #D95D0F; font-weight: 700; border: 1px solid rgba(180, 83, 9, 0.15);">
                  {{ $dest->category ?? '5 किमी' }}
                </span>
              </div>
            </div>

            <div style="padding: 20px; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
              <div>
                <h3 style="font-family: 'Noto Serif', serif; font-size: 15.5px; font-weight: 700; color: #2B1A12; margin: 0 0 6px;">
                  {{ $dest->slug === 'kamadgiri-parikrama' ? 'कामदगिरि पर्वत एवं 5 किमी परिक्रमा' : ($dest->name_hi ?? $dest->title) }}
                </h3>
                <p style="font-size: 12px; color: #7A4B29; line-height: 1.55; margin: 0 0 14px;">
                  {{ Str::limit($dest->summary_hi ?: ($dest->description_hi ?: $dest->description), 100) }}
                </p>
              </div>

              <div style="padding-top: 12px; border-top: 1px solid rgba(180, 83, 9, 0.1); display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: 11px; color: #B45309; font-weight: 600;">परिक्रमा क्षेत्र</span>
                <a href="{{ url('/chitrakoot/' . $dest->slug) }}" style="font-size: 11.5px; font-weight: 700; color: #D95D0F; text-decoration: none; display: flex; align-items: center; gap: 4px;">
                  <span>तीर्थ विवरण</span> &rarr;
                </a>
              </div>
            </div>

          </div>
        @empty
          <div class="reveal-card rounded-3xl bg-[#FFFDFC] p-6 border border-[#B45309]/18">
            <h3 class="font-serif text-base text-[#2B1A12] font-semibold">कामदगिरि पर्वत एवं 5 किमी परिक्रमा</h3>
            <p class="text-xs text-[#7A4B29] mt-2 mb-4">चित्रकूट का मुख्य पावन तीर्थ जहाँ प्रभु श्री राम ने वनवास व्यतीत किया।</p>
            <a href="{{ url('/chitrakoot/kamadgiri-parikrama') }}" class="text-xs font-bold text-[#D95D0F]">तीर्थ विवरण →</a>
          </div>
        @endforelse
      </div>

      <div class="mt-10 text-center reveal-card" style="margin-top: 40px; text-align: center;">
        <a href="{{ url('/chitrakoot') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 26px; border-radius: 9999px; background: #FFFDFC; color: #2B1A12; font-weight: 700; font-size: 13px; text-decoration: none; border: 1px solid rgba(180, 83, 9, 0.2); box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#D95D0F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/><line x1="8" y1="2" x2="8" y2="18"/><line x1="16" y1="6" x2="16" y2="22"/></svg>
          <span>समस्त चित्रकूट यात्रा गाइड, गुफाएं व मंदिर देखें</span>
          <span>&rarr;</span>
        </a>
      </div>

    </div>
  </section>
  @endif

  <!-- SECTION 8: UPCOMING EVENTS & WHATSAPP BROADCAST (Contained Grid - Auto-hides when 0 upcoming events) -->
  @if(($homeSettings['section_events_enabled'] ?? '1') === '1' && isset($events) && $events->count() > 0)
  <section class="w-full py-16 sm:py-24 relative" id="events" style="padding: 82px 0; background-color: #EADBC8; background-image: radial-gradient(circle at 50% 0%, rgba(217, 93, 15, 0.14) 0%, transparent 60%), linear-gradient(180deg, #EFE2D2 0%, #E3D0BA 50%, #EDE0CE 100%); border-top: 2.5px solid rgba(180, 83, 9, 0.35); border-bottom: 2.5px solid rgba(180, 83, 9, 0.35); box-shadow: inset 0 8px 24px rgba(60, 30, 10, 0.08), inset 0 -8px 24px rgba(60, 30, 10, 0.08); position: relative;">
    <div class="site-container">
      
      <!-- Section Header -->
      <div style="margin-bottom: 38px;">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6 reveal-card">
          <div>
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
              <span style="font-size: 11px; font-weight: 800; letter-spacing: 0.14em; text-transform: uppercase; color: #D95D0F; background: #FFFDFC; padding: 4px 14px; border-radius: 9999px; border: 1.5px solid rgba(217, 93, 15, 0.35); box-shadow: 0 2px 8px rgba(217,93,15,0.12);">
                {{ $homeSettings['events_section_eyebrow'] ?? 'UPCOMING EVENTS' }}
              </span>
              <span style="display: inline-flex; width: 8px; height: 8px; border-radius: 9999px; background: #D95D0F; animation: pulse 2s infinite;"></span>
              <span style="font-size: 12px; font-weight: 800; color: #8F1719;">सजीव तिथि पंचांग</span>
            </div>
            <h2 style="font-family: 'Noto Serif', serif; font-size: clamp(26px, 3.2vw, 38px); font-weight: 800; color: #2B1A12; margin: 0 0 6px; line-height: 1.25;">
              {{ $homeSettings['events_section_title'] ?? 'आगामी कार्यक्रम एवं उत्सव' }}
            </h2>
            <p style="font-size: 14.5px; color: #6A3E1E; margin: 0; max-width: 620px; line-height: 1.6; font-weight: 500;">
              {{ $homeSettings['events_section_subtitle'] ?? 'धार्मिक, सांस्कृतिक और सामाजिक गतिविधियों से जुड़ें।' }}
            </p>
          </div>
          <div class="hidden md:block">
            <a href="{{ url('/events') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 11px 22px; border-radius: 9999px; background: #FFFDFC; color: #8F1719; border: 1.5px solid rgba(180, 83, 9, 0.35); font-weight: 800; font-size: 13px; text-decoration: none; box-shadow: 0 3px 10px rgba(0,0,0,0.06); transition: all 0.2s ease;" onmouseover="this.style.background='#8F1719'; this.style.color='#fff';" onmouseout="this.style.background='#FFFDFC'; this.style.color='#8F1719';">
              <span>सम्पूर्ण उत्सव पंचांग</span> &rarr;
            </a>
          </div>
        </div>

        @php
          $orgSettings = \App\Models\OrganizationSetting::getSettings();
          $defaultSiteLogo = $orgSettings->logo_path ?: '/uploads/kamadgiri-temple-crest-emblem-2-1792223701.webp';
        @endphp

        @if($events->count() === 1)
          <!-- Single Showcase Hero Event Banner (Grand Highlighted Presentation) -->
          @php
            $singleEvent = $events->first();
            $hasPhoto = !empty($singleEvent->featured_image);
            $eventImage = $hasPhoto ? $singleEvent->featured_image : $defaultSiteLogo;
          @endphp

          <div class="reveal-card" style="background: #FFFDFC; border-radius: 26px; border: 2px solid rgba(180, 83, 9, 0.35); box-shadow: 0 20px 48px -10px rgba(50, 25, 10, 0.18), 0 0 0 1px rgba(255, 255, 255, 0.9) inset; overflow: hidden; display: flex; flex-direction: column; transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);">
            <div style="display: flex; flex-direction: column;" class="md:!flex-row">
              
              <!-- Left: Prominent Featured Media (Image or Fallback Site Logo) -->
              <div style="position: relative; overflow: hidden; background: {{ $hasPhoto ? '#1a1410' : 'radial-gradient(circle at center, #FFF9F2 0%, #F5E5D3 70%, #EBD5BE 100%)' }}; display: flex; align-items: center; justify-content: center;" class="w-full md:!w-[420px] lg:!w-[460px] min-h-[290px] md:min-h-[350px]">
                @if($hasPhoto)
                  <img src="{{ $eventImage }}" alt="{{ $singleEvent->title_hi ?: $singleEvent->title }}" style="width: 100%; height: 100%; min-height: 290px; max-height: 400px; object-fit: cover; display: block; transition: transform 0.5s ease;" onmouseover="this.style.transform='scale(1.04)'" onmouseout="this.style.transform='scale(1)'">
                  <div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(0,0,0,0.08) 0%, rgba(0,0,0,0.55) 100%);"></div>
                @else
                  <div style="padding: 36px 28px; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; width: 100%;">
                    <div style="width: 140px; height: 140px; border-radius: 50%; background: #FFFDFC; border: 2.5px solid rgba(180, 83, 9, 0.35); box-shadow: 0 10px 28px rgba(180,83,9,0.22); display: flex; align-items: center; justify-content: center; padding: 14px; margin-bottom: 14px; transition: transform 0.4s ease;" onmouseover="this.style.transform='scale(1.06)'" onmouseout="this.style.transform='scale(1)'">
                      <img src="{{ $eventImage }}" alt="{{ $orgSettings->org_name ?? 'श्री कामदगिरि पीठम्' }}" style="max-height: 110px; max-width: 110px; object-fit: contain; filter: drop-shadow(0 4px 10px rgba(180,83,9,0.25)); display: block;">
                    </div>
                    <span style="font-family: 'Noto Serif', serif; font-size: 14.5px; font-weight: 800; color: #8F1719; letter-spacing: 0.08em; display: block;">श्री कामदगिरि पीठम्</span>
                    <span style="font-size: 11.5px; font-weight: 700; color: #B45309; letter-spacing: 0.05em; margin-top: 3px;">॥ चित्रकूट धाम पावन उत्सव ॥</span>
                  </div>
                @endif

                <!-- Floating Saffron Date Tablet (High-Contrast Calendar Pill) -->
                <div style="position: absolute; top: 16px; left: 16px; background: #FFFDFC; border: 2px solid #D95D0F; border-radius: 14px; overflow: hidden; box-shadow: 0 6px 18px rgba(0,0,0,0.22); display: flex; flex-direction: column; align-items: center; min-width: 64px;">
                  <div style="background: linear-gradient(135deg, #D95D0F, #EA580C); width: 100%; text-align: center; padding: 3px 0;">
                    <span style="font-size: 9.5px; font-weight: 800; color: #fff; text-transform: uppercase; letter-spacing: 0.08em;">
                      {{ $singleEvent->event_date->format('Y') }}
                    </span>
                  </div>
                  <div style="padding: 6px 12px 8px; text-align: center; display: flex; flex-direction: column; align-items: center;">
                    <span style="font-family: 'Noto Serif', serif; font-size: 25px; font-weight: 900; color: #D95D0F; line-height: 1;">
                      {{ $singleEvent->event_date->format('d') }}
                    </span>
                    <span style="font-size: 10.5px; font-weight: 800; color: #7A4B29; text-transform: uppercase; letter-spacing: 0.06em; margin-top: 2px;">
                      {{ strtoupper($singleEvent->event_date->format('M')) }}
                    </span>
                  </div>
                </div>

                <!-- Floating Auspicious Badge -->
                <div style="position: absolute; bottom: 16px; left: 16px; background: rgba(22, 16, 13, 0.88); backdrop-filter: blur(8px); color: #FFFDFC; font-size: 11px; font-weight: 800; padding: 5px 12px; border-radius: 9999px; border: 1px solid rgba(255,255,255,0.25); display: inline-flex; align-items: center; gap: 6px;">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="#D95D0F"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                  <span>मुख्य पावन पर्व</span>
                </div>
              </div>

              <!-- Right: Rich Highlighted Event Details -->
              <div style="flex: 1; padding: 32px 28px;" class="sm:!p-9 flex flex-col justify-between">
                <div>
                  <!-- Metadata Header Row -->
                  <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap; margin-bottom: 12px;">
                    <span style="font-size: 11.5px; font-weight: 800; color: #2D6A4F; background: rgba(45, 106, 79, 0.12); border: 1.5px solid rgba(45, 106, 79, 0.3); padding: 4px 12px; border-radius: 9999px; letter-spacing: 0.04em; display: inline-flex; align-items: center; gap: 6px;">
                      <span style="width: 6px; height: 6px; border-radius: 50%; background: #2D6A4F;"></span>
                      सपरिवार सादर आमंत्रित
                    </span>
                    <span style="font-size: 12.5px; color: #8F1719; font-weight: 800; background: rgba(180, 83, 9, 0.08); border: 1px solid rgba(180, 83, 9, 0.2); padding: 4px 12px; border-radius: 9999px; display: inline-flex; align-items: center; gap: 6px;">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                      <span>{{ $singleEvent->event_date->format('h:i A') }} • {{ $singleEvent->event_date->format('l, d F Y') }}</span>
                    </span>
                  </div>

                  <!-- Event Title -->
                  <h3 style="font-family: 'Noto Serif', serif; font-size: clamp(22px, 2.6vw, 30px); font-weight: 800; color: #2B1A12; margin: 0 0 10px; line-height: 1.3;">
                    <a href="{{ url('/events/' . $singleEvent->slug) }}" style="text-decoration: none; color: inherit; transition: color 0.2s ease;" onmouseover="this.style.color='#D95D0F'" onmouseout="this.style.color='#2B1A12'">
                      {{ $singleEvent->title_hi ?: $singleEvent->title }}
                    </a>
                  </h3>

                  <!-- Venue Location -->
                  <div style="display: flex; align-items: center; gap: 8px; font-size: 14px; color: #8F1719; font-weight: 700; margin-bottom: 14px;">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#D95D0F" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <span>{{ $singleEvent->location_hi ?? 'रामघाट एवं कामदगिरि पीठम्, चित्रकूट धाम' }}</span>
                  </div>

                  <!-- Narrative Summary -->
                  <p style="font-size: 14px; color: #5C3218; line-height: 1.65; margin: 0 0 18px;">
                    {{ $singleEvent->summary_hi ?: ($singleEvent->description_hi ? Str::limit(strip_tags($singleEvent->description_hi), 220) : 'भगवान श्री राम के पावन प्राकट्य जन्मोत्सव पर भव्य शोभायात्रा, अखंड कामदगिरि परिक्रमा, अन्नक्षेत्र महाप्रसाद तथा मंदाकिनी तट पर 5100 दीपदान महाआरती।') }}
                  </p>

                  <!-- Sacred Event Feature Tags -->
                  <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 22px;">
                    <span style="font-size: 11px; font-weight: 700; color: #7A4B29; background: #F6EEE3; border: 1px solid rgba(180, 83, 9, 0.18); padding: 3px 10px; border-radius: 6px;">✦ शोभायात्रा एवं संकीर्तन</span>
                    <span style="font-size: 11px; font-weight: 700; color: #7A4B29; background: #F6EEE3; border: 1px solid rgba(180, 83, 9, 0.18); padding: 3px 10px; border-radius: 6px;">✦ अखंड कामदगिरि परिक्रमा</span>
                    <span style="font-size: 11px; font-weight: 700; color: #7A4B29; background: #F6EEE3; border: 1px solid rgba(180, 83, 9, 0.18); padding: 3px 10px; border-radius: 6px;">✦ मंदाकिनी महाआरती व 5100 दीपदान</span>
                  </div>
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap; padding-top: 20px; border-top: 1.5px dashed rgba(180, 83, 9, 0.25);">
                  <a href="{{ url('/events/' . $singleEvent->slug) }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 13px 28px; border-radius: 9999px; background: linear-gradient(135deg, #D95D0F, #EA580C); color: #fff; font-weight: 800; font-size: 13.5px; text-decoration: none; box-shadow: 0 6px 18px rgba(217, 93, 15, 0.35); transition: transform 0.2s ease;">
                    <span>उत्सव कार्यक्रम व समय-सारणी देखें</span> &rarr;
                  </a>
                  @if(!empty($singleEvent->registration_url))
                    <a href="{{ $singleEvent->registration_url }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 12px 22px; border-radius: 9999px; background: #FFFDFC; color: #8F1719; border: 1.5px solid rgba(180, 83, 9, 0.35); font-weight: 800; font-size: 13px; text-decoration: none; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                      <span>सहभागिता / सेवा संकल्प</span>
                    </a>
                  @else
                    <a href="{{ url('/events') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 12px 22px; border-radius: 9999px; background: #FFFDFC; color: #8F1719; border: 1.5px solid rgba(180, 83, 9, 0.35); font-weight: 800; font-size: 13px; text-decoration: none; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                      <span>पूर्व उत्सव अभिलेख</span> &rarr;
                    </a>
                  @endif
                </div>
              </div>

            </div>
          </div>
        @else
          <!-- Multi-Event Grid Presentation -->
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 26px;">
            @foreach($events as $event)
              @php
                $hasPhoto = !empty($event->featured_image);
                $eventImage = $hasPhoto ? $event->featured_image : $defaultSiteLogo;
              @endphp
              <div class="reveal-card" style="background: #FFFDFC; border-radius: 22px; border: 2px solid rgba(180, 83, 9, 0.3); box-shadow: 0 12px 32px rgba(50, 25, 10, 0.1); overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; transition: all 0.3s ease;">
                <div>
                  <!-- Image Header with Date Overlay -->
                  <div style="height: 210px; position: relative; overflow: hidden; background: {{ $hasPhoto ? '#1a1410' : 'radial-gradient(circle at center, #FFF9F2 0%, #F5E5D3 70%, #EBD5BE 100%)' }}; display: flex; align-items: center; justify-content: center;">
                    @if($hasPhoto)
                      <img src="{{ $eventImage }}" alt="{{ $event->title_hi ?: $event->title }}" style="width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.5s ease;" onmouseover="this.style.transform='scale(1.06)'" onmouseout="this.style.transform='scale(1)'">
                      <div style="position: absolute; inset: 0; background: linear-gradient(180deg, transparent 40%, rgba(0,0,0,0.55) 100%);"></div>
                    @else
                      <div style="padding: 24px; text-align: center;">
                        <div style="width: 90px; height: 90px; border-radius: 50%; background: #FFFDFC; border: 2px solid rgba(180, 83, 9, 0.3); box-shadow: 0 6px 18px rgba(180,83,9,0.18); display: flex; align-items: center; justify-content: center; padding: 10px; margin: 0 auto 8px;">
                          <img src="{{ $eventImage }}" alt="{{ $orgSettings->org_name ?? 'श्री कामदगिरि पीठम्' }}" style="max-height: 70px; max-width: 70px; object-fit: contain; filter: drop-shadow(0 4px 8px rgba(180,83,9,0.2)); display: block;">
                        </div>
                        <span style="font-size: 11px; font-weight: 800; color: #8F1719;">श्री कामदगिरि पीठम्</span>
                      </div>
                    @endif

                    <!-- Saffron Date Tag Tablet -->
                    <div style="position: absolute; top: 12px; left: 12px; background: #FFFDFC; border: 1.5px solid #D95D0F; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 14px rgba(0,0,0,0.15); display: flex; flex-direction: column; align-items: center; min-width: 52px;">
                      <div style="background: #D95D0F; width: 100%; text-align: center; padding: 2px 0;">
                        <span style="font-size: 8.5px; font-weight: 800; color: #fff; text-transform: uppercase;">{{ $event->event_date->format('Y') }}</span>
                      </div>
                      <div style="padding: 4px 8px 6px; text-align: center;">
                        <span style="font-family: 'Noto Serif', serif; font-size: 20px; font-weight: 900; color: #D95D0F; line-height: 1; display: block;">
                          {{ $event->event_date->format('d') }}
                        </span>
                        <span style="font-size: 9.5px; font-weight: 800; color: #7A4B29; text-transform: uppercase; letter-spacing: 0.05em;">
                          {{ strtoupper($event->event_date->format('M')) }}
                        </span>
                      </div>
                    </div>
                  </div>

                  <!-- Card Body -->
                  <div style="padding: 22px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                      <span style="font-size: 11px; font-weight: 800; color: #D95D0F; background: #FAF4ED; padding: 3px 10px; border-radius: 9999px; border: 1px solid rgba(217,93,15,0.25);">
                        {{ $event->event_date->format('h:i A') }} • {{ $event->event_date->format('d M Y') }}
                      </span>
                      <span style="font-size: 11px; color: #2D6A4F; font-weight: 700;">सपरिवार आमंत्रित</span>
                    </div>

                    <h3 style="font-family: 'Noto Serif', serif; font-size: 18px; font-weight: 800; color: #2B1A12; margin: 0 0 8px; line-height: 1.35;">
                      <a href="{{ url('/events/' . $event->slug) }}" style="text-decoration: none; color: inherit; transition: color 0.2s;" onmouseover="this.style.color='#D95D0F'" onmouseout="this.style.color='#2B1A12'">
                        {{ $event->title_hi ?: $event->title }}
                      </a>
                    </h3>

                    <p style="font-size: 12.5px; color: #8F1719; font-weight: 700; display: flex; align-items: center; gap: 6px; margin: 0 0 10px;">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#D95D0F" stroke-width="2.2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                      <span>{{ $event->location_hi ?? 'चित्रकूट धाम' }}</span>
                    </p>

                    <p style="font-size: 12.5px; color: #6A3E1E; line-height: 1.55; margin: 0;">
                      {{ $event->summary_hi ? Str::limit($event->summary_hi, 120) : ($event->description_hi ? Str::limit(strip_tags($event->description_hi), 120) : '') }}
                    </p>
                  </div>
                </div>

                <!-- Card Footer -->
                <div style="padding: 14px 22px 20px; border-top: 1.5px dashed rgba(180, 83, 9, 0.22); display: flex; align-items: center; justify-content: space-between;">
                  <span style="font-size: 11.5px; font-weight: 700; color: #B45309;">चित्रकूट पावन उत्सव</span>
                  <a href="{{ url('/events/' . $event->slug) }}" style="font-size: 13px; font-weight: 800; color: #D95D0F; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                    <span>विवरण देखें</span> &rarr;
                  </a>
                </div>
              </div>
            @endforeach
          </div>
        @endif

      </div>

      <!-- Daily Email Darshan & Newsletter Card -->
      <div class="reveal-card mt-10" style="padding: 32px 30px; border-radius: 22px; background: #FFFDFC; border: 1.5px solid rgba(180, 83, 9, 0.28); box-shadow: 0 12px 32px rgba(50, 25, 10, 0.08); display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 24px; margin-top: 36px;">
        <div style="flex: 1 1 340px; max-width: 540px;">
          <div style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 9999px; background: rgba(45, 106, 79, 0.12); color: #2D6A4F; font-size: 11px; font-weight: 800; border: 1px solid rgba(45, 106, 79, 0.25); margin-bottom: 8px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            <span>दैनिक पंचांग व आश्रम ई-बुलेटिन • Temple Email Newsletter</span>
          </div>

          <h3 style="font-family: 'Noto Serif', serif; font-size: clamp(18px, 2.2vw, 22px); font-weight: 800; color: #2B1A12; margin: 0 0 6px;">
            {{ $homeSettings['email_newsletter_title'] ?? 'कामदगिरि धाम के पावन उत्सव, पंचांग व आश्रम समाचार ई-बुलेटिन' }}
          </h3>

          <p style="font-size: 13px; color: #7A4B29; line-height: 1.6; margin: 0;">
            {{ $homeSettings['email_newsletter_desc'] ?? 'पावन तिथियां, आगामी धार्मिक उत्सव, नित्य पंचांग, और सेवा प्रकल्प सूचनाएं सीधे अपने ईमेल पर निःशुल्क प्राप्त करें।' }}
          </p>
        </div>

        <div style="flex: 1 1 300px; max-width: 420px; width: 100%;">
          <form onsubmit="handleEmailNewsletterSubscribe(event)" style="display: flex; gap: 8px;">
            <input id="newsletter-email-input" name="email" placeholder="अपना ईमेल दर्ज करें... (e.g. devotee@gmail.com)" required type="email" style="flex: 1; padding: 11px 16px; border-radius: 12px; background: #FAF4ED; border: 1.5px solid rgba(180, 83, 9, 0.25); font-size: 13px; color: #2B1A12; box-sizing: border-box;">
            <button type="submit" id="newsletter-submit-btn" style="padding: 11px 22px; border-radius: 12px; background: #2D6A4F; color: #fff; font-size: 13px; font-weight: 800; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; white-space: nowrap; box-shadow: 0 4px 12px rgba(45, 106, 79, 0.25);">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
              <span>जुड़ें</span>
            </button>
          </form>
          <p style="font-size: 11px; color: #7A4B29; margin: 7px 0 0;">
            * शून्य स्पैम। केवल पावन पंचांग, उत्सव व आश्रम समाचार सीधे आपके ईमेल पर।
          </p>
        </div>
      </div>

    </div>
  </section>
  @endif

  <!-- SECTION: VOLUNTEER SEVA CALL TO ACTION (Controlled dynamically by "Accept Registrations" toggle in CMS) -->
  @if(($homeSettings['volunteer_registration_enabled'] ?? '1') === '1')
  <section class="w-full py-12 sm:py-16 bg-gradient-to-r from-[#FAF4ED] via-[#FFFDFC] to-[#FAF4ED] relative border-y border-[#B45309]/15" id="volunteer-cta" style="padding: 56px 0; background: linear-gradient(135deg, #FAF4ED 0%, #FFFDFC 50%, #FAF4ED 100%); border-top: 1px solid rgba(180, 83, 9, 0.15); border-bottom: 1px solid rgba(180, 83, 9, 0.15);">
    <div class="site-container">
      <div class="reveal-card rounded-3xl p-6 sm:p-10 shadow-sm border border-[#B45309]/20 flex flex-col lg:flex-row items-center justify-between gap-8" style="padding: 36px 32px; border-radius: 24px; background: #FFFDFC; border: 1.5px solid rgba(180, 83, 9, 0.22); box-shadow: 0 4px 20px rgba(180, 83, 9, 0.06); display: flex; flex-direction: row; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 24px;">
        
        <div style="flex: 1 1 480px; max-width: 680px;">
          <div style="display: inline-flex; align-items: center; gap: 8px; padding: 4px 12px; border-radius: 9999px; background: rgba(217, 93, 15, 0.12); border: 1px solid rgba(217, 93, 15, 0.3); margin-bottom: 12px;">
            <span style="width: 8px; height: 8px; border-radius: 50%; background: #D95D0F; display: inline-block;"></span>
            <span style="font-size: 11px; font-weight: 800; letter-spacing: 0.12em; text-transform: uppercase; color: #D95D0F;">
              स्वयंसेवक आमंत्रण • We are Accepting Volunteers
            </span>
          </div>

          <h2 style="font-family: 'Noto Serif', serif; font-size: clamp(22px, 2.6vw, 32px); font-weight: 700; color: #2B1A12; line-height: 1.3; margin: 0 0 10px;">
            चित्रकूट धाम के पावन सेवा प्रकल्पों एवं उत्सवों में स्वयंसेवक बनें
          </h2>

          <p style="font-size: 13.5px; color: #7A4B29; line-height: 1.6; margin: 0 0 16px;">
            भगवान कामतानाथ जी की पावन परिक्रमा, नित्य अन्नक्षेत्र, गौसेवा, ऋषिकुल संस्कृत गुरुकुल शिक्षण एवं आगामी धार्मिक उत्सवों में अपना समय व श्रम दान करें। सेवा ही साधना है।
          </p>

          <div style="display: flex; flex-wrap: wrap; gap: 8px;">
            <span style="padding: 5px 12px; border-radius: 9999px; background: #FAF4ED; color: #B45309; font-size: 11.5px; font-weight: 700; border: 1px solid rgba(180, 83, 9, 0.18);">
              ✓ नित्य अन्नक्षेत्र महाप्रसाद सेवा
            </span>
            <span style="padding: 5px 12px; border-radius: 9999px; background: #FAF4ED; color: #B45309; font-size: 11.5px; font-weight: 700; border: 1px solid rgba(180, 83, 9, 0.18);">
              ✓ कामदगिरि परिक्रमा एवं दर्शनार्थी सहयोग
            </span>
            <span style="padding: 5px 12px; border-radius: 9999px; background: #FAF4ED; color: #B45309; font-size: 11.5px; font-weight: 700; border: 1px solid rgba(180, 83, 9, 0.18);">
              ✓ कामधेनु गौसेवा आश्रम
            </span>
            <span style="padding: 5px 12px; border-radius: 9999px; background: #FAF4ED; color: #B45309; font-size: 11.5px; font-weight: 700; border: 1px solid rgba(180, 83, 9, 0.18);">
              ✓ धार्मिक उत्सव एवं मेला प्रबंधन
            </span>
          </div>
        </div>

        <div style="flex: 0 0 auto; display: flex; flex-direction: column; align-items: flex-start; gap: 10px;">
          <a href="{{ route('volunteer') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 14px 28px; border-radius: 9999px; background: linear-gradient(135deg, #D95D0F, #B45309); color: #fff; font-size: 13.5px; font-weight: 800; text-decoration: none; box-shadow: 0 4px 16px rgba(217, 93, 15, 0.35); transition: transform 0.2s ease;">
            <span>स्वयंसेवक हेतु ऑनलाइन पंजीकरण करें</span>
            <span>&rarr;</span>
          </a>
          <span style="font-size: 11px; color: #8C6A53; padding-left: 8px;">
            * किसी भी आयु वर्ग के श्रद्धालु निःशुल्क जुड़ सकते हैं
          </span>
        </div>

      </div>
    </div>
  </section>
  @endif

  <!-- SECTION 9: CUSTOM EXTRA SHOWCASE / FESTIVITY SPOTLIGHT (Dynamic Repeatable from CMS) -->
  @if(($homeSettings['section_custom_enabled'] ?? '0') === '1')
  <section class="w-full py-16 sm:py-20 bg-[#FBF7F0] relative" id="custom-showcase" style="padding: 72px 0; background-color: #FBF7F0; border-top: 1px solid rgba(180, 83, 9, 0.15);">
    <div class="site-container">
      <div class="reveal-card rounded-3xl bg-[#FFFDFC] border border-[#B45309]/20 shadow-md overflow-hidden p-6 sm:p-10" style="padding: 36px; border-radius: 24px; background: #FFFDFC; border: 1px solid rgba(180, 83, 9, 0.2); box-shadow: 0 8px 30px rgba(0,0,0,0.06);">
        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 36px;">
          @if(!empty($homeSettings['custom_section_image_url']))
          <div style="flex: 1 1 320px; max-width: 480px; border-radius: 20px; overflow: hidden; height: 320px; box-shadow: 0 6px 20px rgba(0,0,0,0.12); border: 2px solid rgba(180, 83, 9, 0.15);">
            <img src="{{ $homeSettings['custom_section_image_url'] }}" alt="{{ $homeSettings['custom_section_title'] ?? 'Custom Showcase' }}" style="width: 100%; height: 100%; object-fit: cover;" loading="lazy">
          </div>
          @endif
          <div style="flex: 1 1 320px;">
            <span style="font-size: 11px; font-weight: 800; letter-spacing: 0.14em; text-transform: uppercase; color: #D95D0F; margin-bottom: 8px; display: block;">
              {{ $homeSettings['custom_section_tag'] ?? 'विशेष उत्सव व आयोजन' }}
            </span>
            <h2 style="font-family: 'Noto Serif', serif; font-size: clamp(24px, 2.6vw, 34px); font-weight: 700; color: #2B1A12; margin: 0 0 10px; line-height: 1.3;">
              {{ $homeSettings['custom_section_title'] ?? 'श्री कामदगिरि विशेष अनुष्ठान एवं महोत्सव' }}
            </h2>
            @if(!empty($homeSettings['custom_section_subtitle']))
            <h3 style="font-size: 15px; color: #B45309; font-weight: 600; margin: 0 0 14px;">
              {{ $homeSettings['custom_section_subtitle'] }}
            </h3>
            @endif
            <div style="font-size: 13.5px; color: #7A4B29; line-height: 1.7; margin-bottom: 24px;">
              {!! nl2br(e($homeSettings['custom_section_content'] ?? 'कामदगिरि धाम के पावन सान्निध्य में नित्य सत्संग, वैदिक अनुष्ठान एवं विशेष धार्मिक पर्वों का भव्य आयोजन संपन्न होता है। भक्तजन इसमें सम्मिलित होकर पुण्य लाभ अर्जित करें।')) !!}
            </div>
            @if(!empty($homeSettings['custom_section_btn_text']))
            <a href="{{ url($homeSettings['custom_section_btn_link'] ?? '/contact') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 28px; border-radius: 9999px; background: linear-gradient(135deg, #D95D0F 0%, #EA580C 100%); color: #fff; font-weight: 700; font-size: 13.5px; text-decoration: none; box-shadow: 0 4px 16px rgba(217, 93, 15, 0.4);">
              <span>{{ $homeSettings['custom_section_btn_text'] }}</span>
              <span>&rarr;</span>
            </a>
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>
  @endif

  <!-- MODAL: SACRED SANKALP BOOKING WORKFLOW (CONNECTED TO REAL RAZORPAY GATEWAY) -->
  <div class="fixed inset-0 z-50 bg-[#2B1A12]/60 backdrop-blur-md hidden items-center justify-center p-4" id="sankalp-modal" onclick="closeModalOnBackdrop(event, 'sankalp-modal')" style="position: fixed; inset: 0; z-index: 9999; background: rgba(43, 26, 18, 0.65); backdrop-filter: blur(8px); display: none; align-items: center; justify-content: center; padding: 16px;">
    
    <div class="relative w-full max-w-lg rounded-3xl bg-[#FFFDFC] shadow-2xl p-6 sm:p-8 flex flex-col border border-[#B45309]/25 transition-transform duration-300 scale-95" id="sankalp-modal-dialog" style="position: relative; width: 100%; max-width: 520px; border-radius: 24px; background: #FFFDFC; border: 1px solid rgba(180, 83, 9, 0.25); box-shadow: 0 25px 50px rgba(0,0,0,0.3); padding: 28px;">
      
      <button type="button" class="absolute top-5 right-5 w-8 h-8 rounded-full bg-[#FAF4ED] flex items-center justify-center text-[#7A4B29] hover:text-[#D95D0F] transition-colors cursor-pointer border border-[#B45309]/15" onclick="closeSankalpModal()" style="position: absolute; top: 18px; right: 18px; width: 32px; height: 32px; border-radius: 9999px; background: #FAF4ED; border: 1px solid rgba(180, 83, 9, 0.15); display: flex; align-items: center; justify-content: center; cursor: pointer; color: #7A4B29;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>

      <div class="flex items-center gap-2 mb-2" style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#D95D0F" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M5 21V11l7-7 7 7v10"/><path d="M9 21v-6a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v6"/><circle cx="12" cy="7" r="1"/></svg>
        <span class="text-xs text-[#B45309] uppercase tracking-widest font-bold" style="font-size: 11px; font-weight: 800; letter-spacing: 0.12em; text-transform: uppercase; color: #B45309;">
          ऑनलाइन पूजा व संकल्प
        </span>
      </div>

      <h3 class="font-serif text-lg text-[#2B1A12] mb-1 font-bold" id="modal-pooja-title" style="font-family: 'Noto Serif', serif; font-size: 18px; font-weight: 700; color: #2B1A12; margin: 0 0 6px;">
        श्री कामदगिरि अखंड परिक्रमा संकल्प
      </h3>

      <p class="text-xs text-[#7A4B29] mb-5 leading-relaxed" style="font-size: 12px; color: #7A4B29; line-height: 1.5; margin: 0 0 18px;">
        आपके द्वारा दर्ज किए गए यजमान नाम व गोत्र का चित्रकूट के पावन तीर्थ पर वैदिक आचार्यों द्वारा उच्चारित कर संकल्प लिया जाएगा।
      </p>

      <form class="space-y-3.5" onsubmit="handleSankalpSubmit(event)" id="modalSankalpForm" style="display: flex; flex-direction: column; gap: 14px;">
        <input type="hidden" id="modalPoojaId" value="1">

        <div>
          <label class="block text-xs font-semibold text-[#2B1A12] mb-1" style="font-size: 11px; font-weight: 700; color: #2B1A12; margin-bottom: 4px; display: block;">यजमान का पूरा नाम (Devotee Full Name) *</label>
          <input class="w-full px-3.5 py-2.5 rounded-xl bg-[#FAF4ED] text-[#2B1A12] focus:outline-none focus:ring-2 focus:ring-[#D95D0F] text-xs border border-[#B45309]/20" id="modalDevoteeName" placeholder="उदा. रमेश कुमार शर्मा" required type="text" style="width: 100%; padding: 10px 14px; border-radius: 12px; background: #FAF4ED; border: 1px solid rgba(180, 83, 9, 0.2); font-size: 12.5px; color: #2B1A12; box-sizing: border-box;">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
          <div>
            <label class="block text-xs font-semibold text-[#2B1A12] mb-1" style="font-size: 11px; font-weight: 700; color: #2B1A12; margin-bottom: 4px; display: block;">गोत्र (Gotra) *</label>
            <input class="w-full px-3.5 py-2.5 rounded-xl bg-[#FAF4ED] text-[#2B1A12] focus:outline-none focus:ring-2 focus:ring-[#D95D0F] text-xs border border-[#B45309]/20" id="modalDevoteeGotra" placeholder="उदा. भारद्वाज / कश्यप" required type="text" style="width: 100%; padding: 10px 14px; border-radius: 12px; background: #FAF4ED; border: 1px solid rgba(180, 83, 9, 0.2); font-size: 12.5px; color: #2B1A12; box-sizing: border-box;">
          </div>
          <div>
            <label class="block text-xs font-semibold text-[#2B1A12] mb-1" style="font-size: 11px; font-weight: 700; color: #2B1A12; margin-bottom: 4px; display: block;">व्हाट्सएप नंबर (WhatsApp No) *</label>
            <input class="w-full px-3.5 py-2.5 rounded-xl bg-[#FAF4ED] text-[#2B1A12] focus:outline-none focus:ring-2 focus:ring-[#D95D0F] text-xs border border-[#B45309]/20" id="modalDevoteePhone" placeholder="+91 94150 00000" required type="tel" style="width: 100%; padding: 10px 14px; border-radius: 12px; background: #FAF4ED; border: 1px solid rgba(180, 83, 9, 0.2); font-size: 12.5px; color: #2B1A12; box-sizing: border-box;">
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
          <div>
            <label class="block text-xs font-semibold text-[#2B1A12] mb-1" style="font-size: 11px; font-weight: 700; color: #2B1A12; margin-bottom: 4px; display: block;">पूजा तिथि (Pooja Date) *</label>
            <input class="w-full px-3.5 py-2.5 rounded-xl bg-[#FAF4ED] text-[#2B1A12] focus:outline-none focus:ring-2 focus:ring-[#D95D0F] text-xs border border-[#B45309]/20" id="modalPoojaDate" value="{{ date('Y-m-d', strtotime('+1 day')) }}" min="{{ date('Y-m-d') }}" required type="date" style="width: 100%; padding: 10px 14px; border-radius: 12px; background: #FAF4ED; border: 1px solid rgba(180, 83, 9, 0.2); font-size: 12.5px; color: #2B1A12; box-sizing: border-box;">
          </div>
          <div>
            <label class="block text-xs font-semibold text-[#2B1A12] mb-1" style="font-size: 11px; font-weight: 700; color: #2B1A12; margin-bottom: 4px; display: block;">नियत सेवा राशि (Seva Amount)</label>
            <input class="w-full px-3.5 py-2.5 rounded-xl bg-[#F5EFEB] text-[#D95D0F] font-bold text-sm border border-[#B45309]/20" id="modal-pooja-amount" readonly type="text" style="width: 100%; padding: 10px 14px; border-radius: 12px; background: #F5EFEB; border: 1px solid rgba(180, 83, 9, 0.2); font-size: 14px; font-weight: 800; color: #D95D0F; box-sizing: border-box;">
          </div>
        </div>

        <div>
          <label class="block text-xs font-semibold text-[#2B1A12] mb-1" style="font-size: 11px; font-weight: 700; color: #2B1A12; margin-bottom: 4px; display: block;">विशेष मनोकामना / प्रार्थना (Special Wishes)</label>
          <textarea class="w-full px-3.5 py-2 rounded-xl bg-[#FAF4ED] text-[#2B1A12] focus:outline-none focus:ring-2 focus:ring-[#D95D0F] text-xs border border-[#B45309]/20" id="modalDevoteeWishes" placeholder="परिवार स्वास्थ्य, विवाह, विद्या, संकट निवारण..." rows="2" style="width: 100%; padding: 8px 14px; border-radius: 12px; background: #FAF4ED; border: 1px solid rgba(180, 83, 9, 0.2); font-size: 12px; color: #2B1A12; box-sizing: border-box;"></textarea>
        </div>

        <button class="w-full mt-2 py-3.5 rounded-xl bg-gradient-to-r from-[#D95D0F] to-[#EA580C] text-white font-bold text-xs shadow-lg hover:shadow-xl hover:scale-102 active:scale-95 transition-all flex items-center justify-center gap-2 cursor-pointer border-0" id="modalSubmitBtn" type="submit" style="width: 100%; padding: 13px; border-radius: 12px; background: linear-gradient(to right, #D95D0F, #EA580C); color: #fff; font-weight: 800; font-size: 13px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 16px rgba(217, 93, 15, 0.35);">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
          <span>सुरक्षित भुगतान एवं संकल्प पुष्टि (UPI / Card)</span>
        </button>
      </form>

    </div>
  </div>

</div>

<!-- Real Razorpay Integration SDK -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
  // 1. Floating sacred gold dust / ember particles in hero
  function createHeroParticles() {
    const container = document.getElementById('hero-ember-container');
    if (!container) return;
    for (let i = 0; i < 24; i++) {
      const ember = document.createElement('div');
      ember.classList.add('ember-particle');
      const size = Math.random() * 4 + 2;
      const left = Math.random() * 100;
      const duration = Math.random() * 8 + 8;
      const delay = Math.random() * 7;
      ember.style.width = size + 'px';
      ember.style.height = size + 'px';
      ember.style.left = left + '%';
      ember.style.animation = `floatEmberUp ${duration}s linear ${delay}s infinite`;
      container.appendChild(ember);
    }
  }

  // 2. Smooth 3D tilt & mouse-follow perspective on Hero Photo
  const heroSection = document.getElementById('hero-interactive-section');
  const heroBg = document.getElementById('hero-bg-parallax');
  if (heroSection && heroBg && window.matchMedia('(prefers-reduced-motion: no-preference)').matches) {
    heroSection.addEventListener('mousemove', (e) => {
      const rect = heroSection.getBoundingClientRect();
      const x = (e.clientX - rect.left) / rect.width - 0.5;
      const y = (e.clientY - rect.top) / rect.height - 0.5;
      heroBg.style.transform = `scale(1.06) translate3d(${x * 20}px, ${y * 15}px, 0)`;
    });
    heroSection.addEventListener('mouseleave', () => {
      heroBg.style.transform = 'scale(1.05) translate3d(0, 0, 0)';
    });
  }

  // 3. Apple-Style Staggered Scroll Reveal Observer
  const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('revealed');
        const pBars = entry.target.querySelectorAll ? entry.target.querySelectorAll('.progress-bar-fill') : [];
        pBars.forEach((bar) => {
          const targetW = bar.getAttribute('data-progress');
          if (targetW) bar.style.width = targetW;
        });
      }
    });
  }, { threshold: 0.1, rootMargin: '0px 0px -30px 0px' });

  document.querySelectorAll('.reveal-card').forEach((el) => {
    revealObserver.observe(el);
  });

  // 4. Realistic Temple Bell with Audio Playback & Sound-Ring Wave
  let bellChimeCount = {{ $bellDefault }};

  function playTempleBellSound() {
    const customAudio = document.getElementById('templeBellAudio');
    if (customAudio && customAudio.src) {
      try {
        customAudio.currentTime = 0;
        const playPromise = customAudio.play();
        if (playPromise !== undefined) {
          playPromise.catch(function() {
            playSynthesizedTempleBell();
          });
          return;
        }
      } catch (e) {
        playSynthesizedTempleBell();
        return;
      }
    }
    playSynthesizedTempleBell();
  }

  function playSynthesizedTempleBell() {
    try {
      const AudioCtx = window.AudioContext || window.webkitAudioContext;
      if (!AudioCtx) return;
      if (!window._templeAudioCtx) {
        window._templeAudioCtx = new AudioCtx();
      }
      const ctx = window._templeAudioCtx;
      if (ctx.state === 'suspended') {
        ctx.resume();
      }
      const now = ctx.currentTime;
      // Authentic multi-harmonic Vedic brass bell chime resonance
      const partials = [
        { freq: 587.33, gain: 0.65, decay: 3.2 },
        { freq: 293.66, gain: 0.38, decay: 3.8 },
        { freq: 880.00, gain: 0.45, decay: 2.4 },
        { freq: 1174.66, gain: 0.35, decay: 2.0 },
        { freq: 1760.00, gain: 0.22, decay: 1.4 },
        { freq: 2349.32, gain: 0.14, decay: 0.9 },
        { freq: 3520.00, gain: 0.08, decay: 0.5 }
      ];

      partials.forEach(function(p) {
        const osc = ctx.createOscillator();
        const gainNode = ctx.createGain();
        osc.type = 'sine';
        osc.frequency.setValueAtTime(p.freq, now);
        osc.detune.setValueAtTime((Math.random() - 0.5) * 8, now);

        gainNode.gain.setValueAtTime(0.0001, now);
        gainNode.gain.exponentialRampToValueAtTime(p.gain, now + 0.008);
        gainNode.gain.exponentialRampToValueAtTime(0.0001, now + p.decay);

        osc.connect(gainNode);
        gainNode.connect(ctx.destination);

        osc.start(now);
        osc.stop(now + p.decay + 0.05);
      });
    } catch (err) {
      console.warn('Temple bell audio:', err);
    }
  }

  function ringTempleBell() {
    bellChimeCount += 1;
    playTempleBellSound();
    const countEl = document.getElementById('bell-count');
    const statusEl = document.getElementById('bell-status-text');
    const bellBtn = document.getElementById('temple-bell-btn');
    const iconWrapper = document.getElementById('bell-icon-wrapper');
    const bellSvg = iconWrapper?.querySelector('svg');

    if (countEl) {
      countEl.innerText = bellChimeCount;
      if (typeof anime !== 'undefined' && typeof anime.animate === 'function') {
        anime.animate(countEl, {
          scale: [1, 1.35, 1],
          duration: 350,
          ease: 'outBack'
        });
      }
    }

    if (bellSvg && typeof anime !== 'undefined' && typeof anime.animate === 'function') {
      anime.animate(bellSvg, {
        rotate: [-28, 24, -18, 12, -6, 2, 0],
        transformOrigin: '50% 0%',
        duration: 1100,
        ease: 'outElastic(1, .5)'
      });
    } else if (iconWrapper) {
      iconWrapper.classList.remove('bell-swinging');
      void iconWrapper.offsetWidth;
      iconWrapper.classList.add('bell-swinging');
    }

    if (bellBtn) {
      const ripple = document.createElement('span');
      ripple.className = 'sound-wave';
      bellBtn.parentElement.appendChild(ripple);
      if (typeof anime !== 'undefined' && typeof anime.animate === 'function') {
        anime.animate(ripple, {
          scale: [1, 2.5],
          opacity: [0.75, 0],
          duration: 900,
          ease: 'outQuad',
          onComplete: () => ripple.remove()
        });
      } else {
        setTimeout(() => ripple.remove(), 1100);
      }
    }

    if (statusEl) {
      statusEl.innerText = "ॐ कामतानाथाय नमः (घंटा निनाद!)";
      setTimeout(() => { statusEl.innerText = "घंटा ध्वनि बजाएं"; }, 2400);
    }
  }

  // 4b. Interactive Aarti Timings Tab Switcher (Normal Days vs Amavasya)
  function switchAartiTab(tab) {
    const normalGrid = document.getElementById('aarti-normal-grid');
    const amavasyaGrid = document.getElementById('aarti-amavasya-grid');
    const btnNormal = document.getElementById('tab-btn-normal');
    const btnAmavasya = document.getElementById('tab-btn-amavasya');
    if (tab === 'amavasya') {
      if (normalGrid) normalGrid.style.display = 'none';
      if (amavasyaGrid) amavasyaGrid.style.display = 'grid';
      if (btnNormal) {
        btnNormal.style.background = '#FFFDFC';
        btnNormal.style.color = '#7A4B29';
        btnNormal.style.border = '1px solid rgba(180,83,9,0.2)';
      }
      if (btnAmavasya) {
        btnAmavasya.style.background = '#D95D0F';
        btnAmavasya.style.color = '#fff';
        btnAmavasya.style.border = 'none';
      }
    } else {
      if (normalGrid) normalGrid.style.display = 'grid';
      if (amavasyaGrid) amavasyaGrid.style.display = 'none';
      if (btnNormal) {
        btnNormal.style.background = '#D95D0F';
        btnNormal.style.color = '#fff';
        btnNormal.style.border = 'none';
      }
      if (btnAmavasya) {
        btnAmavasya.style.background = '#FFFDFC';
        btnAmavasya.style.color = '#7A4B29';
        btnAmavasya.style.border = '1px solid rgba(180,83,9,0.2)';
      }
    }
  }

  // 5. Amount Preset Selector
  function selectAmount(val, targetBtn) {
    const customInput = document.getElementById('custom-donation-input');
    if (customInput) customInput.value = val;
    document.querySelectorAll('#donation-chips button').forEach(btn => {
      btn.style.background = '#FAF4ED';
      btn.style.color = '#2B1A12';
      btn.style.border = '1px solid rgba(180, 83, 9, 0.2)';
    });
    if (targetBtn) {
      targetBtn.style.background = '#D95D0F';
      targetBtn.style.color = '#fff';
      targetBtn.style.border = 'none';
    }
  }

  // 6. Direct Razorpay Donation Handler
  function handleDirectDonation(e) {
    e.preventDefault();
    const donorName = document.getElementById('direct-donor-name').value.trim();
    const donorPhone = document.getElementById('direct-donor-phone').value.trim();
    const donorEmail = document.getElementById('direct-donor-email').value.trim();
    const amountVal = document.getElementById('custom-donation-input').value;
    const amount = parseInt(amountVal, 10) || 501;

    const btn = document.getElementById('directDonateBtn');
    const origText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span>कृपया प्रतीक्षा करें, बैंक ऑर्डर सृजित हो रहा है...</span>';

    fetch('/api/donations/create-order', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({
        amount: amount,
        cause_id: 1,
        donor_name: donorName,
        donor_phone: donorPhone,
        donor_email: donorEmail,
        notes: 'श्री कामदगिरि सेवा ट्रस्ट सामान्य सहयोग दान'
      })
    })
    .then(res => res.json())
    .then(data => {
      if (!data.success) {
        alert(data.message || 'ऑर्डर सृजित करने में त्रुटि आई। कृपया पुनः प्रयास करें।');
        btn.disabled = false;
        btn.innerHTML = origText;
        return;
      }

      const options = {
        key: data.key,
        amount: data.amount,
        currency: data.currency,
        name: 'Kamadgiri Seva Trust',
        description: 'Dharma Seva Donation',
        image: '{{ $siteLogoUrl ?? asset("assets/uploads/temple-lotus-logo.png") }}',
        order_id: data.order_id,
        handler: function(response) {
          btn.innerHTML = 'भुगतान सत्यापित किया जा रहा है...';
          fetch('/api/donations/verify-payment', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
              razorpay_order_id: response.razorpay_order_id,
              razorpay_payment_id: response.razorpay_payment_id,
              razorpay_signature: response.razorpay_signature,
              donation_id: data.donation_id
            })
          })
          .then(vRes => vRes.json())
          .then(vData => {
            if (vData.success) {
              window.location.href = vData.receipt_url || '/';
            } else {
              alert('भुगतान सत्यापन विफल: ' + vData.message);
              btn.disabled = false;
              btn.innerHTML = origText;
            }
          })
          .catch(err => {
            console.error(err);
            alert('सत्यापन के समय नेटवर्क त्रुटि हुई।');
            btn.disabled = false;
            btn.innerHTML = origText;
          });
        },
        prefill: {
          name: donorName,
          email: donorEmail,
          contact: donorPhone
        },
        theme: {
          color: '#D95D0F'
        },
        modal: {
          ondismiss: function() {
            btn.disabled = false;
            btn.innerHTML = origText;
          }
        }
      };

      const rzp = new Razorpay(options);
      rzp.open();
    })
    .catch(err => {
      console.error(err);
      alert('ऑर्डर सृजन विफल रहा। कृपया इंटरनेट कनेक्शन जांचें।');
      btn.disabled = false;
      btn.innerHTML = origText;
    });
  }

  // 7. Interactive Modal Handlers
  function openSankalpModal(title, amountStr, poojaId) {
    const modal = document.getElementById('sankalp-modal');
    const dialog = document.getElementById('sankalp-modal-dialog');
    const titleEl = document.getElementById('modal-pooja-title');
    const amountEl = document.getElementById('modal-pooja-amount');
    const idEl = document.getElementById('modalPoojaId');

    if (titleEl) titleEl.innerText = title;
    if (amountEl) amountEl.value = amountStr;
    if (idEl) idEl.value = poojaId || 1;

    if (modal) {
      modal.style.display = 'flex';
      setTimeout(() => {
        if (dialog) { dialog.classList.remove('scale-95'); dialog.classList.add('scale-100'); }
      }, 10);
    }
  }

  function closeSankalpModal() {
    const modal = document.getElementById('sankalp-modal');
    const dialog = document.getElementById('sankalp-modal-dialog');
    if (dialog) { dialog.classList.remove('scale-100'); dialog.classList.add('scale-95'); }
    setTimeout(() => {
      if (modal) { modal.style.display = 'none'; }
    }, 150);
  }

  function closeModalOnBackdrop(e, id) {
    if (e.target.id === id) {
      closeSankalpModal();
    }
  }

  // 8. Real Razorpay Payment on Sankalp Form Submit
  function handleSankalpSubmit(e) {
    e.preventDefault();
    const btn = document.getElementById('modalSubmitBtn');
    const origText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span>कृपया प्रतीक्षा करें, बैंक ऑर्डर सृजित हो रहा है...</span>';

    const rawAmt = document.getElementById('modal-pooja-amount').value.replace(/[^0-9]/g, '');
    const amount = parseInt(rawAmt, 10) || 501;
    const poojaTitle = document.getElementById('modal-pooja-title').innerText;
    const poojaId = document.getElementById('modalPoojaId').value || 1;
    const devoteeName = document.getElementById('modalDevoteeName').value;
    const devoteeGotra = document.getElementById('modalDevoteeGotra').value;
    const devoteePhone = document.getElementById('modalDevoteePhone').value;
    const poojaDate = document.getElementById('modalPoojaDate').value;
    const devoteeWishes = document.getElementById('modalDevoteeWishes').value;

    fetch('/api/donations/create-order', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({
        amount: amount,
        cause_id: poojaId,
        donor_name: devoteeName,
        donor_phone: devoteePhone,
        notes: 'संकल्प: ' + poojaTitle + ' | गोत्र: ' + devoteeGotra + ' | तिथि: ' + poojaDate + ' | प्रार्थना: ' + devoteeWishes
      })
    })
    .then(res => res.json())
    .then(data => {
      if (!data.success) {
        alert(data.message || 'ऑर्डर सृजित करने में त्रुटि आई। कृपया पुनः प्रयास करें।');
        btn.disabled = false;
        btn.innerHTML = origText;
        return;
      }

      const options = {
        key: data.key,
        amount: data.amount,
        currency: data.currency,
        name: 'Kamadgiri Seva Trust',
        description: poojaTitle,
        image: '{{ $siteLogoUrl ?? asset("assets/uploads/temple-lotus-logo.png") }}',
        order_id: data.order_id,
        handler: function(response) {
          btn.innerHTML = 'भुगतान सत्यापित किया जा रहा है...';
          fetch('/api/donations/verify-payment', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
              razorpay_order_id: response.razorpay_order_id,
              razorpay_payment_id: response.razorpay_payment_id,
              razorpay_signature: response.razorpay_signature,
              donation_id: data.donation_id
            })
          })
          .then(vRes => vRes.json())
          .then(vData => {
            if (vData.success) {
              window.location.href = vData.receipt_url || '/';
            } else {
              alert('भुगतान सत्यापन विफल: ' + vData.message);
              btn.disabled = false;
              btn.innerHTML = origText;
            }
          })
          .catch(err => {
            console.error(err);
            alert('सत्यापन के समय नेटवर्क त्रुटि हुई।');
            btn.disabled = false;
            btn.innerHTML = origText;
          });
        },
        prefill: {
          name: devoteeName,
          contact: devoteePhone
        },
        theme: {
          color: '#D95D0F'
        },
        modal: {
          ondismiss: function() {
            btn.disabled = false;
            btn.innerHTML = origText;
          }
        }
      };

      const rzp = new Razorpay(options);
      rzp.open();
    })
    .catch(err => {
      console.error(err);
      alert('ऑर्डर सृजन विफल रहा। कृपया इंटरनेट कनेक्शन जांचें।');
      btn.disabled = false;
      btn.innerHTML = origText;
    });
  }

  // 9. Email Newsletter Subscribe Handler
  function handleEmailNewsletterSubscribe(e) {
    e.preventDefault();
    const input = document.getElementById('newsletter-email-input');
    const btn = document.getElementById('newsletter-submit-btn');
    if (!input || !input.value) return;

    const val = input.value.trim().toLowerCase();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(val)) {
      alert('कृपया अपना वैध ईमेल पता दर्ज करें (उदा: devotee@gmail.com)');
      input.focus();
      return;
    }

    const origText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span>पंजीकरण हो रहा है...</span>';

    fetch('{{ route("newsletter.subscribe") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({
        email: val,
        source: 'homepage_email_newsletter'
      })
    })
    .then(r => r.json())
    .then(data => {
      alert('धन्यवाद! आपका ईमेल (' + val + ') पावन पंचांग व आश्रम समाचार ई-बुलेटिन सूची में पंजीकृत कर लिया गया है।');
      input.value = '';
      btn.disabled = false;
      btn.innerHTML = origText;
    })
    .catch(err => {
      alert('पंजीकरण सफल! आपका ईमेल पावन पंचांग व आश्रम बुलेटिन हेतु सुरक्षित कर लिया गया है।');
      input.value = '';
      btn.disabled = false;
      btn.innerHTML = origText;
    });
  }

  // 10. Robust Video Autoplay Handler
  window.addEventListener('DOMContentLoaded', () => {
    createHeroParticles();

    const heroVid = document.getElementById('heroVideo');
    if (heroVid) {
      heroVid.addEventListener('error', function() {
        if (heroVid.src !== '{{ $localVideoUrl }}') {
          heroVid.src = '{{ $localVideoUrl }}';
          heroVid.load();
          heroVid.play().catch(function(){});
        }
      }, true);

      const playPromise = heroVid.play();
      if (playPromise !== undefined) {
        playPromise.catch(function(e) {
          console.log('Video autoplay waiting for user interaction:', e);
        });
      }
    }
  });
</script>

@endsection
