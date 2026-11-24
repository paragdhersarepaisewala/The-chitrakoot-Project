@extends('layouts.app')

@section('title', 'संकल्प एवं पूजा बुकिंग पावती #' . $booking->booking_number . ' | ' . ($orgSettings->org_name ?? 'श्री कामदगिरि पीठम् सेवा ट्रस्ट'))
@section('meta_description', 'श्री कामदगिरि पीठम् सेवा ट्रस्ट आधिकारिक पावन पूजा व संकल्प पंजीकरण पावती प्रमाण-पत्र #' . $booking->booking_number . '।')

@section('content')

<style>
  .pooja-receipt-wrapper {
    padding-top: 130px;
    padding-bottom: 120px;
    background: #FAF6F0;
    background-image: radial-gradient(circle at 50% 12%, rgba(217, 93, 15, 0.08) 0%, transparent 65%);
    min-height: 90vh;
  }

  .pooja-voucher-card {
    background: #ffffff;
    border: 2.5px solid #C48B47;
    outline: 1px solid rgba(143, 23, 25, 0.35);
    outline-offset: -7px;
    border-radius: 16px;
    padding: clamp(20px, 4.5vw, 42px);
    box-shadow: 0 16px 45px rgba(43, 26, 18, 0.08);
    position: relative;
    max-width: 780px;
    margin: 0 auto;
    box-sizing: border-box;
    overflow: hidden;
  }

  .voucher-watermark {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(-25deg);
    font-size: clamp(48px, 10vw, 84px);
    font-weight: 900;
    font-family: var(--dev, serif);
    color: rgba(220, 38, 38, 0.09);
    letter-spacing: 0.15em;
    pointer-events: none;
    user-select: none;
    z-index: 1;
    white-space: nowrap;
  }

  .voucher-header {
    text-align: center;
    border-bottom: 2px dashed rgba(196, 139, 71, 0.4);
    padding-bottom: 20px;
    margin-bottom: 24px;
    position: relative;
    z-index: 2;
  }

  .voucher-emblem {
    width: 64px;
    height: 64px;
    margin: 0 auto 12px;
    border-radius: 50%;
    background: linear-gradient(135deg, rgba(217, 93, 15, 0.12), rgba(180, 83, 9, 0.2));
    border: 2px solid #C48B47;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
  }

  .voucher-title {
    font: 700 clamp(22px, 3.4vw, 30px)/1.2 var(--dev, serif);
    color: #2B1A12;
    margin: 6px 0 4px;
  }

  .voucher-org-name {
    font-size: 12px;
    font-weight: 800;
    color: #B45309;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    display: block;
    margin-bottom: 4px;
  }

  .voucher-meta-strip {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #FAF5EE;
    border: 1px solid rgba(196, 139, 71, 0.3);
    border-radius: 10px;
    padding: 10px 16px;
    margin-top: 14px;
    flex-wrap: wrap;
    gap: 8px;
    font-size: 13px;
  }

  .voucher-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 9999px;
    font-size: 11.5px;
    font-weight: 800;
    letter-spacing: 0.04em;
    text-transform: uppercase;
  }
  .voucher-status-badge.confirmed {
    background: #DEF7EC;
    color: #03543F;
    border: 1px solid #31C48D;
  }
  .voucher-status-badge.cancelled {
    background: #FDE8E8;
    color: #9B1C1C;
    border: 1px solid #F98080;
  }
  .voucher-status-badge.pending {
    background: #FEF08A;
    color: #713F12;
    border: 1px solid #FACC15;
  }
  .voucher-status-badge.quoted {
    background: #FEF3C7;
    color: #92400E;
    border: 1px solid #F59E0B;
  }

  .voucher-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13.5px;
    margin-bottom: 22px;
    position: relative;
    z-index: 2;
  }

  .voucher-table tr {
    border-bottom: 1px solid rgba(196, 139, 71, 0.18);
  }

  .voucher-table td {
    padding: 10px 14px;
    vertical-align: middle;
  }

  .voucher-label-cell {
    color: #7A4B29;
    font-weight: 700;
    width: 36%;
    font-size: 12.5px;
    background: rgba(250, 245, 238, 0.5);
  }

  .voucher-val-cell {
    color: #2B1A12;
    font-weight: 700;
  }

  .voucher-val-cell.highlight {
    color: #8F1719;
    font-size: 14.5px;
  }

  .voucher-instructions-box {
    background: #FFFDF9;
    border: 1px dashed #C48B47;
    border-radius: 10px;
    padding: 14px 18px;
    margin-bottom: 24px;
    font-size: 12.5px;
    line-height: 1.65;
    color: #6B4A31;
    position: relative;
    z-index: 2;
  }

  .voucher-footer-seal {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 2px dashed rgba(196, 139, 71, 0.4);
    padding-top: 16px;
    margin-top: 10px;
    flex-wrap: wrap;
    gap: 12px;
    position: relative;
    z-index: 2;
  }

  .voucher-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
    align-items: center;
    margin-top: 28px;
    flex-wrap: wrap;
  }

  .btn-voucher-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 11px 22px;
    border-radius: 9999px;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.2s ease;
    white-space: nowrap;
    border: 1px solid transparent;
    cursor: pointer;
    box-sizing: border-box;
  }

  .btn-voucher-action.primary {
    background: linear-gradient(135deg, #D95D0F, #B45309);
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(217, 93, 15, 0.25);
  }
  .btn-voucher-action.primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(217, 93, 15, 0.35);
  }

  .btn-voucher-action.outline {
    background: #ffffff;
    color: #7A4B29;
    border-color: rgba(180, 83, 9, 0.3);
  }
  .btn-voucher-action.outline:hover {
    background: #FAF4ED;
    border-color: #B45309;
    color: #2B1A12;
  }

  .btn-voucher-action.disabled {
    background: #F3F4F6;
    color: #9CA3AF;
    border-color: #E5E7EB;
    cursor: not-allowed;
    box-shadow: none;
    pointer-events: none;
  }

  /* Responsive Mobile Tweaks */
  @media (max-width: 640px) {
    .pooja-receipt-wrapper {
      padding-top: 96px;
      padding-bottom: 110px;
    }
    .pooja-voucher-card {
      padding: 18px 14px;
      border-radius: 12px;
    }
    .voucher-table {
      font-size: 12.5px;
    }
    .voucher-label-cell {
      width: 42%;
      padding: 8px 10px;
      font-size: 11.5px;
    }
    .voucher-val-cell {
      padding: 8px 10px;
    }
    .voucher-meta-strip {
      flex-direction: column;
      align-items: flex-start;
      padding: 10px 12px;
    }
    .voucher-actions {
      flex-direction: column;
      width: 100%;
    }
    .btn-voucher-action {
      width: 100%;
      padding: 12px 18px;
    }
  }

  /* STRICT PRINT STYLES - Guaranteed Clean 1-Page A4 Receipt */
  @media print {
    header,
    footer,
    nav,
    #header,
    .mobile-bar,
    .mobile-menu,
    .loader,
    .ticker-bar,
    .voucher-actions,
    .no-print,
    .alert-banner {
      display: none !important;
    }

    body, html {
      background: #ffffff !important;
      color: #000000 !important;
      margin: 0 !important;
      padding: 0 !important;
      font-size: 11pt !important;
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
    }

    .pooja-receipt-wrapper {
      padding: 0 !important;
      margin: 0 !important;
      background: none !important;
      min-height: auto !important;
    }

    .container {
      width: 100% !important;
      max-width: 100% !important;
      padding: 0 !important;
      margin: 0 !important;
    }

    .pooja-voucher-card {
      border: 2px solid #92400E !important;
      outline: none !important;
      border-radius: 0 !important;
      box-shadow: none !important;
      padding: 16px 20px !important;
      margin: 0 auto !important;
      max-width: 100% !important;
      page-break-inside: avoid !important;
      break-inside: avoid !important;
    }

    .voucher-table td {
      padding: 7px 10px !important;
    }

    @page {
      size: A4 portrait;
      margin: 10mm;
    }
  }
</style>

<section class="section pooja-receipt-wrapper">
  <div class="container" style="max-width: 820px;">

    <!-- User Feedback Banner -->
    @if(session('success'))
      <div class="alert-banner no-print" style="background: #DEF7EC; border-left: 4px solid #31C48D; color: #03543F; padding: 12px 18px; border-radius: 8px; font-size: 13.5px; font-weight: 600; margin-bottom: 20px;">
        ✓ {{ session('success') }}
      </div>
    @endif

    <!-- Status Alerts -->
    @if($booking->status === 'cancelled')
      <div class="alert-banner no-print" style="background: #FDE8E8; border-left: 4px solid #E02424; color: #9B1C1C; padding: 14px 18px; border-radius: 8px; font-size: 13.5px; font-weight: 600; margin-bottom: 20px;">
        ✕ <strong>बुकिंग निरस्त (Booking Cancelled):</strong> यह पूजा बुकिंग निरस्त कर दी गई है। यह पावती अब मंदिर दर्शन, पूजन अथवा अनुष्ठान हेतु मान्य नहीं है।
      </div>
    @elseif($booking->status === 'pending')
      <div class="alert-banner no-print" style="background: #FEF08A; border-left: 4px solid #CA8A04; color: #713F12; padding: 14px 18px; border-radius: 8px; font-size: 13.5px; font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
        <x-icon name="clock" size="16" /> <span><strong>अनुरोध समीक्षाधीन (Request Pending):</strong> आपका संकल्प अनुरोध प्राप्त हुआ है। व्यवस्थापक द्वारा दक्षिणा एवं व्यवस्था पुष्ट होने पर अंतिम रसीद सक्रिय होगी।</span>
      </div>
    @elseif($booking->status === 'confirmed')
      <div class="alert-banner no-print" style="background: #DEF7EC; border-left: 4px solid #059669; color: #065F46; padding: 12px 18px; border-radius: 8px; font-size: 13.5px; font-weight: 600; margin-bottom: 20px;">
        ✓ <strong>बुकिंग स्वीकृत एवं पुष्ट (Booking Confirmed):</strong> आपका पूजा व अनुष्ठान संकल्प मंदिर कार्यालय द्वारा आधिकारिक रूप से पुष्ट कर दिया गया है।
      </div>
    @endif

    <!-- Sacred Pooja Voucher Card -->
    <div class="pooja-voucher-card">

      @if($booking->status === 'cancelled')
        <div class="voucher-watermark">निरस्त / CANCELLED</div>
      @endif

      <!-- Header Section -->
      <div class="voucher-header">
        <div class="voucher-emblem">
          @if(!empty($orgSettings->logo_path))
            <img src="{{ asset('storage/' . $orgSettings->logo_path) }}" alt="Peetham Logo" style="width: 100%; height: 100%; object-fit: contain;">
          @else
            <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#D95D0F" stroke-width="2"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
          @endif
        </div>
        <span class="voucher-org-name">{{ $orgSettings->org_name ?? 'Shri Kamadgiri Peetham Sewa Trust' }}</span>
        <div style="font-size: 11px; color: #7A4B29; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 4px;">
          चित्रकूट धाम &bull; तीर्थ क्षेत्र &bull; तपोभूमि
        </div>
        <h1 class="voucher-title">पावन पूजा व संकल्प पंजीकरण पावती</h1>
        <div style="font-size: 11px; color: #6B7280;">Sacred Pooja & Vedic Ritual Booking Voucher</div>

        <!-- Reference & Status Bar -->
        <div class="voucher-meta-strip">
          <div>
            <span style="color: #7A4B29; font-size: 11px; font-weight: 700; text-transform: uppercase;">बुकिंग संख्या (Booking ID):</span>
            <strong style="color: #8F1719; font-family: monospace; font-size: 14.5px; margin-left: 4px;">{{ $booking->booking_number }}</strong>
          </div>
          <div>
            <span style="color: #7A4B29; font-size: 11px; font-weight: 700; text-transform: uppercase;">स्थिति (Status):</span>
            @if($booking->status === 'confirmed')
              <span class="voucher-status-badge confirmed">✓ Confirmed</span>
            @elseif($booking->status === 'cancelled')
              <span class="voucher-status-badge cancelled">✕ Cancelled</span>
            @elseif($booking->status === 'quoted')
              <span class="voucher-status-badge quoted" style="display: inline-flex; align-items: center; gap: 4px;"><x-icon name="zap" size="12" /> Quoted</span>
            @else
              <span class="voucher-status-badge pending" style="display: inline-flex; align-items: center; gap: 4px;"><x-icon name="clock" size="12" /> Pending</span>
            @endif
          </div>
        </div>
      </div>

      <!-- Devotee & Ritual Details Table -->
      <table class="voucher-table">
        <tbody>
          <tr>
            <td class="voucher-label-cell">अनुष्ठान का नाम (Ritual)</td>
            <td class="voucher-val-cell highlight">{{ $booking->getEffectiveRitualTitle() }}</td>
          </tr>
          <tr>
            <td class="voucher-label-cell">यजमान / मुख्य भक्त (Devotee)</td>
            <td class="voucher-val-cell">{{ $booking->devotee_name }}</td>
          </tr>
          <tr>
            <td class="voucher-label-cell">अनुष्ठान तिथि (Scheduled Date)</td>
            <td class="voucher-val-cell">
              {{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('d M, Y (l)') : 'N/A' }}
            </td>
          </tr>
          <tr>
            <td class="voucher-label-cell">मंदिर समय-स्लॉट (Time Slot)</td>
            <td class="voucher-val-cell" style="color: #9A3412;">{{ $booking->time_slot }}</td>
          </tr>
          <tr>
            <td class="voucher-label-cell">गोत्र / नक्षत्र (Gotra / Nakshatra)</td>
            <td class="voucher-val-cell">
              @if(!empty($booking->gotra) && !empty($booking->nakshatra))
                {{ $booking->gotra }} &bull; {{ $booking->nakshatra }}
              @elseif(!empty($booking->gotra))
                {{ $booking->gotra }}
              @elseif(!empty($booking->nakshatra))
                {{ $booking->nakshatra }}
              @else
                <span style="color: #6B7280; font-weight: normal;">Not Specified</span>
              @endif
            </td>
          </tr>
          @if($booking->sankalp_purpose)
            <tr>
              <td class="voucher-label-cell">पावन संकल्प मनोरथ (Purpose)</td>
              <td class="voucher-val-cell" style="font-weight: 600; color: #4A2E1F;">{{ $booking->sankalp_purpose }}</td>
            </tr>
          @endif
          <tr>
            <td class="voucher-label-cell">संपर्क फोन (Devotee Phone)</td>
            <td class="voucher-val-cell">{{ $booking->phone }}</td>
          </tr>
          @if($booking->email)
            <tr>
              <td class="voucher-label-cell">ईमेल पता (Devotee Email)</td>
              <td class="voucher-val-cell">{{ $booking->email }}</td>
            </tr>
          @endif
          <tr>
            <td class="voucher-label-cell">दक्षिणा स्थिति (Dakshina Status)</td>
            <td class="voucher-val-cell">
              @if($booking->amount > 0)
                <span style="color: #059669; font-weight: 800;">₹{{ number_format((float) $booking->amount, 2) }}</span>
                <span style="font-size: 11px; color: #059669; font-weight: 700;">({{ strtoupper($booking->payment_status ?? 'PAID') }})</span>
              @elseif($booking->isQuotationActive())
                <span style="color: #D97706; font-weight: 800;">कोटेशन: ₹{{ number_format((float) $booking->quotation_amount, 2) }}</span>
                <span style="font-size: 11px; color: #D97706;">(प्रतीक्षित)</span>
              @else
                <span style="color: #7A4B29; font-size: 12px;">न्यास कार्यालय द्वारा संपर्क किया जाएगा</span>
              @endif
            </td>
          </tr>
          @if($booking->admin_notes)
            <tr>
              <td class="voucher-label-cell">व्यवस्थापक निर्देश (Priest Notes)</td>
              <td class="voucher-val-cell" style="font-size: 12px; color: #4B5563;">{{ $booking->admin_notes }}</td>
            </tr>
          @endif
        </tbody>
      </table>

      <!-- Instructions Box -->
      <div class="voucher-instructions-box">
        <strong>महत्वपूर्ण निर्देश:</strong> कृपया निर्धारित अनुष्ठान समय से 15 मिनट पूर्व मंदिर कार्यालय अथवा संपर्क स्थान पर उपस्थित रहें। यदि आप व्यक्तिगत रूप से उपस्थित नहीं हो सकते, तो वैदिक आचार्यों द्वारा आपके नाम व संकल्प से विधिवत पूजन संपन्न कर प्रसाद आपके पते पर प्रेषित किया जाएगा।
      </div>

      <!-- Seal & Authentication Footer -->
      <div class="voucher-footer-seal">
        <div style="font-size: 11px; color: #7A4B29; line-height: 1.5;">
          <strong>संपर्क सूत्र:</strong> {{ $orgSettings->phone ?? '+91 94150 00000' }} &bull; {{ $orgSettings->email ?? 'info@kamadgiripeetham.org' }}<br>
          कामदगिरि परिक्रमा मार्ग, चित्रकूट धाम, मध्य प्रदेश / उत्तर प्रदेश
        </div>
        <div style="text-align: right; font-size: 11px; color: #7A4B29;">
          <div style="font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: #8F1719;">अधिकृत सेवा न्यास कार्यालय</div>
          <div>Authorized Temple Office Seal</div>
        </div>
      </div>

    </div>

    <!-- Screen Action Buttons (Hidden when printing) -->
    <div class="voucher-actions no-print">
      @if($booking->status === 'confirmed')
        <button type="button" onclick="window.print()" class="btn-voucher-action primary">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
          Download Receipt
        </button>
      @elseif($booking->status === 'cancelled')
        <button type="button" class="btn-voucher-action disabled" title="Cancelled bookings cannot generate an active receipt">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
          Receipt Cancelled (Invalid)
        </button>
      @elseif($booking->isQuotationActive())
        <a href="{{ $booking->getQuotationPaymentUrl() }}" class="btn-voucher-action primary" style="background: #B45309; display: inline-flex; align-items: center; gap: 6px;">
          <x-icon name="lock" size="13" /> Pay Online (₹{{ number_format((float) $booking->quotation_amount, 0) }}) &rarr;
        </a>
        <button type="button" onclick="window.print()" class="btn-voucher-action outline">
          Print Quotation
        </button>
      @else
        <button type="button" onclick="window.print()" class="btn-voucher-action outline">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
          Print Acknowledgment
        </button>
      @endif

      @auth
        <a href="{{ url('/dashboard') }}" class="btn-voucher-action outline">
          Devotee Dashboard
        </a>
      @endauth

      <a href="{{ route('pooja.bookingForm') }}" class="btn-voucher-action outline">
        Book Another Pooja &rarr;
      </a>
    </div>

  </div>
</section>

@endsection
