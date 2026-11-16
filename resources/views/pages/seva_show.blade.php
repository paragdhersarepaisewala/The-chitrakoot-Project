@extends('layouts.app')

@section('title', $seva->title_hi . ' | श्री कामदगिरि पीठम् सेवा ट्रस्ट')
@section('meta_description', Str::limit($seva->summary_hi ?: $seva->description_hi, 155))

@section('content')

  <section class="section" style="padding-top: 140px; padding-bottom: 80px; background: var(--paper, #FAF4ED); min-height: 85vh;">
    <div class="container" style="max-width: 920px; margin: 0 auto; padding: 0 20px;">

      <!-- Breadcrumb & Title -->
      <div class="reveal" style="margin-bottom: 24px;">
        <a href="{{ url('/seva') }}" style="font-size: 12.5px; font-weight: 700; color: #B45309; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 16px;">
          &larr; समस्त सेवा महा-अभियान देखें
        </a>
        <div style="display: flex; align-items: center; gap: 8px;">
          <span style="font-size: 11px; font-weight: 800; letter-spacing: 0.12em; text-transform: uppercase; color: #D95D0F; background: #FAF4ED; padding: 3px 10px; border-radius: 9999px; border: 1px solid rgba(217,93,15,0.25);">
            {{ strtoupper($seva->category) }}
          </span>
          <span style="font-size: 11px; color: #7A4B29;">• चित्रकूट धाम सेवा प्रकल्प</span>
        </div>
        <h1 style="font-family: 'Noto Serif', serif; font-size: clamp(28px, 4.2vw, 46px); font-weight: 800; line-height: 1.25; margin-top: 10px; color: #2B1A12;">
          {{ $seva->title_hi }}
        </h1>
        <p style="font-size: 13px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: #B45309; margin-top: 4px;">
          {{ $seva->title_en }}
        </p>
      </div>

      <!-- Featured Image Banner -->
      <div class="reveal" style="margin-bottom: 30px;">
        <img src="{{ $seva->featured_image }}" alt="{{ $seva->title_en }}" style="max-height: 480px; width: 100%; object-fit: cover; border-radius: 20px; border: 1px solid rgba(180,83,9,0.18); box-shadow: 0 16px 40px rgba(130,70,20,0.08);" loading="eager">
      </div>

      <!-- Campaign Financial Progress Card -->
      <div class="reveal" style="background: #FFFDFC; border: 1.5px solid rgba(180,83,9,0.25); border-radius: 20px; padding: clamp(20px, 3.5vw, 32px); box-shadow: 0 10px 30px rgba(130,70,20,0.06); margin-bottom: 32px;">
        <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-end; gap: 12px; margin-bottom: 12px;">
          <div>
            <span style="font-size: 11px; text-transform: uppercase; font-weight: 800; letter-spacing: 0.1em; color: #D95D0F; display: block; margin-bottom: 4px;">
              ॥ सेवा महा-अभियान सहयोग प्रगति ॥
            </span>
            <div style="display: flex; align-items: baseline; gap: 8px;">
              <span style="font-size: 28px; font-weight: 800; color: #166534;">₹{{ number_format($seva->raised_amount, 0) }}</span>
              <span style="font-size: 13px; color: #7A4B29; font-weight: 600;">एकत्र सहयोग</span>
            </div>
          </div>
          <div style="text-align: right;">
            <span style="font-size: 11px; color: #7A4B29; font-weight: 700; display: block; margin-bottom: 2px;">अभियान कुल लक्ष्य</span>
            <span style="font-size: 20px; font-weight: 800; color: #2B1A12;">₹{{ number_format($seva->target_amount, 0) }}</span>
          </div>
        </div>

        <!-- Progress Bar -->
        <div style="height: 10px; background: rgba(180, 83, 9, 0.12); border-radius: 9999px; overflow: hidden; margin-bottom: 12px;">
          <div style="width: {{ $seva->progress_percent }}%; height: 100%; background: linear-gradient(90deg, #D95D0F, #16a34a); border-radius: 9999px; transition: width 0.6s ease;"></div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #7A4B29; font-weight: 700; margin-bottom: 18px;">
          <span>{{ $seva->progress_percent }}% लक्ष्य पूर्ण</span>
          <span>{{ $seva->donors_count }} श्रद्धालु सहयोगी दानदाता</span>
        </div>

        <!-- 1-Click Contribute Button -->
        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 14px; border-top: 1px dashed rgba(180, 83, 9, 0.2); padding-top: 16px;">
          <div style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: #2D6A4F; font-weight: 700;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <span>100% सुरक्षित व अधिकृत दान रसीद प्राप्त करें</span>
          </div>
          <a href="{{ url('/donate?cause_id=' . ($seva->cause_id ?? '')) }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 28px; border-radius: 9999px; background: linear-gradient(135deg, #D95D0F, #EA580C); color: #fff; font-size: 13.5px; font-weight: 800; text-decoration: none; box-shadow: 0 4px 16px rgba(217, 93, 15, 0.35);">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
            <span>इस सेवा में सहयोग करें (Contribute Now)</span>
          </a>
        </div>
      </div>

      <!-- Narrative Description Body -->
      <div class="reveal" style="background: #FFFDFC; border: 1px solid rgba(180,83,9,0.18); border-radius: 20px; padding: clamp(24px, 4vw, 40px); box-shadow: 0 10px 30px rgba(130,70,20,0.05); font-size: 15px; line-height: 1.9; color: #4A3326; margin-bottom: 36px;">
        <h2 style="font-family: 'Noto Serif', serif; font-size: 20px; font-weight: 700; color: #2B1A12; margin: 0 0 14px;">
          प्रकल्प का पावन उद्देश्य एवं आध्यात्मिक महत्व
        </h2>
        <div style="white-space: pre-line;">{!! nl2br(e($seva->description_hi)) !!}</div>
      </div>

      <!-- Bottom Reassurance Banner -->
      <div class="reveal" style="background: linear-gradient(135deg, #FAF4ED 0%, #FFFDFC 100%); border: 1.5px solid rgba(180,83,9,0.22); border-radius: 20px; padding: clamp(28px, 4vw, 40px); box-shadow: 0 12px 35px rgba(130,70,20,0.06); text-align: center;">
        <div style="display: inline-flex; align-items: center; justify-content: center; gap: 6px; color: #B45309; font-size: 11px; font-weight: 800; letter-spacing: 0.12em; text-transform: uppercase; margin-bottom: 10px;">
          <span>॥ सर्वे भवन्तु सुखिनः ॥</span>
        </div>
        <h3 style="font-family: 'Noto Serif', serif; font-size: clamp(22px, 3vw, 30px); font-weight: 700; color: #2B1A12; margin: 0 0 10px;">
          आपका प्रत्येक सहयोग चित्रकूट धाम की सेवा परंपरा को सुदृढ़ बनाता है
        </h3>
        <p style="font-size: 14px; color: #7A4B29; max-width: 620px; margin: 0 auto 20px; line-height: 1.6;">
          {{ $seva->summary_hi }}
        </p>
        <a href="{{ url('/donate?cause_id=' . ($seva->cause_id ?? '')) }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 13px 32px; font-size: 14px; font-weight: 800; border-radius: 9999px; background: linear-gradient(135deg, #D95D0F, #EA580C); color: #fff; text-decoration: none; box-shadow: 0 4px 18px rgba(217, 93, 15, 0.35);">
          <span>सहयोग अर्पण करें</span>
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
      </div>

    </div>
  </section>

@endsection
