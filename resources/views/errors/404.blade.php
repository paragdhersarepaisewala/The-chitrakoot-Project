@extends('layouts.app')

@section('title', '404 — पृष्ठ नहीं मिला | Shri Kamadgiri Peetham Sewa Trust')

@section('content')

  <section class="section" style="padding-top: 180px; padding-bottom: 120px; background: linear-gradient(180deg, #FAF4ED 0%, #F5ECE0 100%); color: #2B1A12; min-height: 85vh; display: flex; align-items: center; position: relative; overflow: hidden;">
    
    <!-- Ambient Background Glow -->
    <div style="position: absolute; width: 600px; height: 600px; background: radial-gradient(circle, rgba(217,93,15,0.08) 0%, rgba(250,244,237,0) 70%); top: 50%; left: 50%; transform: translate(-50%, -50%); pointer-events: none;"></div>

    <div class="container" style="max-width: 820px; text-align: center; position: relative; z-index: 2;">

      <!-- SVG Emblem -->
      <div style="width: 76px; height: 76px; margin: 0 auto 24px; border-radius: 50%; background: #FFFDFC; border: 1.5px solid rgba(180,83,9,0.2); display: grid; place-items: center; box-shadow: 0 10px 30px rgba(130,70,20,0.08);">
        <svg viewBox="0 0 50 58" style="width:36px; height:36px; fill:none; stroke:#D95D0F; stroke-width:2.2;">
          <path d="M5 51V28L25 7l20 21v23"/>
          <path d="M13 51V31l12-12 12 12v20"/>
          <path d="M20 51V35h10v16"/>
          <path d="M2 52h46"/>
          <path d="M25 7V2"/>
        </svg>
      </div>

      <div style="font: 800 12px var(--sans); letter-spacing: .25em; text-transform: uppercase; color: #B45309; margin-bottom: 12px;">
        ERROR 404 · PAGE NOT FOUND
      </div>

      <h1 style="font: 700 clamp(38px, 5.5vw, 68px)/1.15 var(--dev); color: #2B1A12; margin: 0 0 20px;">
        क्षमा करें, यह पृष्ठ उपलब्ध नहीं है
      </h1>

      <p style="font: 400 17px/1.8 var(--dev); color: rgba(43,26,18,0.75); max-width: 600px; margin: 0 auto 40px;">
        आप जिस पृष्ठ की खोज कर रहे हैं वह संभवतः हटा दिया गया है या उसका पता बदल गया है। कृपया मुख्य पृष्ठ पर लौटें या चित्रकूट धाम के पावन दर्शनीय स्थलों का अवलोकन करें।
      </p>

      <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
        <a href="{{ url('/') }}" class="btn primary" style="padding: 14px 32px; font-size: 13px; font-weight: 700; border-radius: 9999px; background: linear-gradient(135deg, #D95D0F 0%, #B45309 100%); color: #FFFDFC; border: none; box-shadow: 0 10px 24px rgba(217,93,15,0.28);">
          <svg viewBox="0 0 24 24" style="width:16px; height:16px; fill:none; stroke:currentColor; stroke-width:2.2;"><path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z"/></svg>
          Return Home
        </a>

        <a href="{{ url('/chitrakoot') }}" class="btn" style="padding: 14px 32px; font-size: 13px; font-weight: 700; border-radius: 9999px; background: #FFFDFC; border: 1.5px solid rgba(180,83,9,0.22); color: #78350F; box-shadow: 0 6px 18px rgba(130,70,20,0.05);">
          <svg viewBox="0 0 24 24" style="width:16px; height:16px; fill:none; stroke:currentColor; stroke-width:2.2;"><circle cx="11" cy="11" r="7"/><path d="m16.5 16.5 4 4"/></svg>
          Explore Chitrakoot
        </a>
      </div>

    </div>
  </section>

@endsection
