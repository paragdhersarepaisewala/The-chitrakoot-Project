@extends('layouts.app')

@section('title', '404 — पृष्ठ नहीं मिला | Shri Kamadgiri Peetham Sewa Trust')

@section('content')

  <section class="section" style="padding-top: 180px; padding-bottom: 120px; background: #1a130e; color: #fff; min-height: 85vh; display: flex; align-items: center; position: relative; overflow: hidden;">
    
    <!-- Ambient Background Glow -->
    <div style="position: absolute; width: 600px; height: 600px; background: radial-gradient(circle, rgba(185,133,80,0.18) 0%, rgba(0,0,0,0) 70%); top: 50%; left: 50%; transform: translate(-50%, -50%); pointer-events: none;"></div>

    <div class="container" style="max-width: 820px; text-align: center; position: relative; z-index: 2;">

      <!-- SVG Emblem -->
      <div style="width: 72px; height: 84px; margin: 0 auto 24px;">
        <svg viewBox="0 0 50 58" style="width:100%; height:100%; fill:none; stroke:var(--sand2); stroke-width:1.5;">
          <path d="M5 51V28L25 7l20 21v23"/>
          <path d="M13 51V31l12-12 12 12v20"/>
          <path d="M20 51V35h10v16"/>
          <path d="M2 52h46"/>
          <path d="M25 7V2"/>
        </svg>
      </div>

      <div style="font: 700 12px var(--sans); letter-spacing: .3em; text-transform: uppercase; color: var(--sand2); margin-bottom: 12px;">
        ERROR 404 · PAGE NOT FOUND
      </div>

      <h1 style="font: 600 clamp(42px, 6vw, 76px)/1.1 var(--dev); color: #ffffff; margin: 0 0 20px;">
        क्षमा करें, यह पृष्ठ उपलब्ध नहीं है
      </h1>

      <p style="font: 400 18px/1.8 var(--dev); color: rgba(255,255,255,.75); max-width: 600px; margin: 0 auto 40px;">
        आप जिस पृष्ठ की खोज कर रहे हैं वह संभवतः हटा दिया गया है या उसका पता बदल गया है। कृपया मुख्य पृष्ठ पर लौटें या चित्रकूट धाम के पावन दर्शनीय स्थलों का अवलोकन करें।
      </p>

      <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
        <a href="{{ url('/') }}" class="btn primary" style="padding: 16px 32px; font-size: 11px;">
          <svg viewBox="0 0 24 24" style="width:16px; height:16px; fill:none; stroke:currentColor; stroke-width:2;"><path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z"/></svg>
          Return Home
        </a>

        <a href="{{ url('/chitrakoot') }}" class="btn" style="padding: 16px 32px; font-size: 11px; border-color: rgba(255,255,255,.3); color: #fff;">
          <svg viewBox="0 0 24 24" style="width:16px; height:16px; fill:none; stroke:currentColor; stroke-width:2;"><circle cx="11" cy="11" r="7"/><path d="m16.5 16.5 4 4"/></svg>
          Explore Chitrakoot
        </a>
      </div>

    </div>
  </section>

@endsection
