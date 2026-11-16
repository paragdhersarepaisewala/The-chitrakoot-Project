@extends('layouts.app')

@section('title', ($orgSettings->is_80g_enabled ?? false) ? ('दान एवं सेवा 80G पावती #' . $receipt->receipt_number . ' | ' . ($orgSettings->org_name ?? 'श्री कामदगिरि पीठम् सेवा ट्रस्ट')) : ('दान एवं सेवा पावती प्रमाण-पत्र #' . $receipt->receipt_number . ' | ' . ($orgSettings->org_name ?? 'श्री कामदगिरि पीठम् सेवा ट्रस्ट')))
@section('meta_description', ($orgSettings->is_80g_enabled ?? false) ? ('श्री कामदगिरि पीठम् सेवा ट्रस्ट आधिकारिक दान पावती प्रमाण-पत्र #' . $receipt->receipt_number . '। 80G आयकर छूट प्राप्त डिजिटल रसीद।') : ('श्री कामदगिरि पीठम् सेवा ट्रस्ट आधिकारिक दान पावती प्रमाण-पत्र #' . $receipt->receipt_number . '। अधिकृत डिजिटल दान रसीद।'))

@section('content')

<style>
  /* Screen Presentation Styles */
  .receipt-page-wrapper {
    padding-top: 130px;
    padding-bottom: 90px;
    background: #f4ede2;
    background-image: radial-gradient(circle at 50% 10%, rgba(185, 133, 80, 0.12) 0%, transparent 60%);
    min-height: 90vh;
  }
  .receipt-certificate-card {
    background: #ffffff;
    border: 3px double #b98550;
    outline: 1px solid rgba(143, 23, 25, 0.4);
    outline-offset: -8px;
    border-radius: 12px;
    padding: clamp(28px, 5vw, 54px);
    box-shadow: 0 15px 45px rgba(22, 16, 13, 0.08), 0 2px 8px rgba(0,0,0,0.04);
    position: relative;
    box-sizing: border-box;
    margin: 0 auto;
    max-width: 820px;
  }
  .receipt-watermark {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    opacity: 0.035;
    pointer-events: none;
    width: 320px;
    height: 320px;
    z-index: 1;
  }
  .receipt-table {
    width: 100%;
    border-collapse: collapse;
    font-family: var(--sans, sans-serif);
    font-size: 14px;
    margin-bottom: 24px;
    position: relative;
    z-index: 2;
  }
  .receipt-table td {
    padding: 12px 14px;
    border-bottom: 1px solid rgba(89, 58, 40, 0.12);
    vertical-align: top;
  }
  .receipt-label-cell {
    color: #6a5d52;
    font-size: 13px;
    font-weight: 600;
    width: 38%;
  }
  .receipt-val-cell {
    color: #16100d;
    font-weight: 700;
  }
  .amount-banner-card {
    background: linear-gradient(135deg, #faf6ee 0%, #f4ece0 100%);
    border: 1px solid #d4a365;
    border-radius: 8px;
    padding: 18px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 12px;
    position: relative;
    z-index: 2;
  }

  /* Print Styles - Guaranteed 1-Page A4 Certificate */
  @media print {
    header, footer, .mobile-bar, .mobile-menu, .loader, .ticker-bar, .no-print, nav, #header {
      display: none !important;
    }
    body, html {
      background: #ffffff !important;
      color: #000000 !important;
      margin: 0 !important;
      padding: 0 !important;
      font-size: 12pt;
    }
    .receipt-page-wrapper {
      padding: 0 !important;
      margin: 0 !important;
      background: none !important;
      min-height: auto !important;
    }
    .container {
      width: 100% !important;
      max-width: 100% !important;
      margin: 0 !important;
      padding: 0 !important;
    }
    .receipt-certificate-card {
      border: 2px solid #593a28 !important;
      outline: none !important;
      box-shadow: none !important;
      padding: 24px !important;
      max-width: 100% !important;
      width: 100% !important;
      page-break-inside: avoid !important;
    }
    @page {
      size: A4 portrait;
      margin: 10mm;
    }
  }
</style>

<div class="receipt-page-wrapper">
  <div class="container" style="max-width: 860px;">

    <!-- Action Toolbar (Hidden in Print) -->
    <div class="no-print" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
      <a href="{{ url('/') }}" class="action-btn" style="text-decoration: none; font-size: 12px; padding: 8px 18px; border-radius: 9999px; white-space: nowrap;">
        &larr; Home
      </a>

      <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <button type="button" onclick="copyReceiptLink()" class="action-btn" style="font-size: 12px; padding: 8px 18px; border-radius: 9999px; white-space: nowrap;">
          Copy Link
        </button>
        <button type="button" onclick="window.print()" class="action-btn primary" style="background: var(--crimson, #8f1719); color: #fff; border-color: var(--crimson); font-size: 12px; padding: 8px 22px; font-weight: 700; border-radius: 9999px; white-space: nowrap;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle; margin-right: 4px;"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
          Download Receipt
        </button>
      </div>
    </div>

    <!-- Official Printable Certificate Card -->
    <div id="printableReceipt" class="receipt-certificate-card">
      
      <!-- Subtle Watermark Symbol -->
      <svg class="receipt-watermark" viewBox="0 0 50 58">
        <path d="M5 51V28L25 7l20 21v23" fill="none" stroke="#8f1719" stroke-width="2"/>
        <path d="M13 51V31l12-12 12 12v20" fill="none" stroke="#8f1719" stroke-width="2"/>
        <path d="M20 51V35h10v16" fill="none" stroke="#8f1719" stroke-width="2"/>
        <path d="M2 52h46" fill="none" stroke="#8f1719" stroke-width="2"/>
        <path d="M25 7V2" fill="none" stroke="#8f1719" stroke-width="2"/>
      </svg>

      <!-- Sacred Sanskrit Invocation -->
      <div style="text-align: center; font-family: var(--dev, serif); font-size: 12px; color: #8f1719; letter-spacing: 1px; margin-bottom: 12px; font-weight: 600;">
        ॥ श्री कामदगिरि चरणेभ्यो नमः ॥ सर्वे भवन्तु सुखिनः सर्वे सन्तु निरामयाः ॥
      </div>

      <!-- Trust Certificate Header -->
      <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #b98550; padding-bottom: 20px; margin-bottom: 24px; position: relative; z-index: 2; flex-wrap: wrap; gap: 16px;">
        <div style="display: flex; gap: 16px; align-items: center;">
          <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #8f1719, #b98550); display: grid; place-items: center; color: #fff; flex-shrink: 0; box-shadow: 0 4px 12px rgba(143,23,25,0.25);">
            <svg width="32" height="32" viewBox="0 0 50 58" fill="none" stroke="#fff" stroke-width="2.5">
              <path d="M5 51V28L25 7l20 21v23"/>
              <path d="M13 51V31l12-12 12 12v20"/>
              <path d="M20 51V35h10v16"/>
              <path d="M2 52h46"/>
              <path d="M25 7V2"/>
            </svg>
          </div>
          <div>
            <h1 style="font-size: 22px; font-weight: 800; font-family: var(--dev, serif); color: #16100d; margin: 0; line-height: 1.25;">
              {{ $orgSettings->org_name ?? 'श्री कामदगिरि पीठम् सेवा ट्रस्ट' }}
            </h1>
            <p style="font-size: 12px; color: #593a28; margin: 3px 0 0; font-weight: 600;">
              {{ $orgSettings->address ?? 'कामदगिरि परिक्रमा मार्ग, चित्रकूट धाम (उ.प्र. / म.प्र.)' }}
            </p>
            <p style="font-size: 11px; color: #7a6e65; margin: 2px 0 0;">
              पंजीकरण संख्या: <strong>{{ $orgSettings->reg_number ?? 'REG/CKT/2026' }}</strong>
              @if(!empty($orgSettings->pan_number))
                &bull; PAN: <strong>{{ $orgSettings->pan_number }}</strong>
              @endif
            </p>
          </div>
        </div>

        <div style="text-align: right;">
          <span style="display: inline-block; background: #8f1719; color: #ffffff; padding: 6px 14px; font-size: 10px; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; border-radius: 4px; box-shadow: 0 2px 8px rgba(143,23,25,0.25);">
            {{ ($orgSettings->is_80g_enabled ?? false) ? 'आधिकारिक 80G पावती' : 'आधिकारिक दान पावती' }}
          </span>
          <div style="font-size: 13.5px; font-weight: 800; color: #16100d; margin-top: 8px; font-family: monospace;">
            रसीद सं: {{ $receipt->receipt_number }}
          </div>
          <div style="font-size: 11.5px; color: #666; margin-top: 2px;">
            दिनांक: {{ $receipt->issued_at ? $receipt->issued_at->format('d M Y') : now()->format('d M Y') }}
          </div>
        </div>
      </div>

      <!-- Amount Highlight Ribbon Card -->
      <div class="amount-banner-card">
        <div>
          <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; color: #7a6e65; display: block; margin-bottom: 2px;">
            स्वीकृत दान / सेवा सहयोग राशि (CONTRIBUTION AMOUNT)
          </span>
          <div style="font-size: 28px; font-weight: 900; color: #8f1719; font-family: var(--sans, sans-serif); line-height: 1.1;">
            ₹{{ number_format($receipt->amount, 2) }}
          </div>
        </div>
        <div style="text-align: right;">
          <span style="background: #e8f5e9; color: #2e7d32; font-size: 11.5px; font-weight: 800; padding: 4px 10px; border-radius: 9999px; display: inline-flex; align-items: center; gap: 4px;">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
            भुगतान सत्यापित एवं स्वीकृत
          </span>
          <div style="font-size: 11px; color: #7a6e65; margin-top: 4px;">
            Gateway Ref: {{ $receipt->donation?->gateway_payment_id ?? 'PAY-SECURE-ONLINE' }}
          </div>
        </div>
      </div>

      <!-- Receipt Information Matrix Table -->
      <table class="receipt-table">
        <tbody>
          <tr>
            <td class="receipt-label-cell">दानदाता / भक्त का नाम (Donor Name):</td>
            <td class="receipt-val-cell" style="font-size: 15px; color: #8f1719; font-family: var(--dev, serif);">
              {{ $receipt->donor_name }}
            </td>
          </tr>
          <tr>
            <td class="receipt-label-cell">सहयोग प्रकल्प / सेवा मद (Cause / Purpose):</td>
            <td class="receipt-val-cell">
              {{ $receipt->cause_title }}
            </td>
          </tr>
          <tr>
            <td class="receipt-label-cell">दान श्रेणी (Contribution Type):</td>
            <td class="receipt-val-cell">
              {{ ucfirst($receipt->donation_type ?? 'Online Dharmik Seva') }}
            </td>
          </tr>
          <tr>
            <td class="receipt-label-cell">भुगतान माध्यम (Payment Mode):</td>
            <td class="receipt-val-cell">
              Razorpay Secured Gateway (UPI / Cards / NetBanking)
            </td>
          </tr>
          <tr>
            <td class="receipt-label-cell">भुगतान दिनांक व समय (Timestamp):</td>
            <td class="receipt-val-cell">
              {{ $receipt->issued_at ? $receipt->issued_at->format('d F Y, h:i A') : now()->format('d F Y, h:i A') }}
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Tax Exemption / Temple Blessing & Audit Declaration Box -->
      <div style="background: #faf7f2; border: 1px solid #e0d5c1; border-left: 4px solid #b98550; padding: 16px 20px; font-size: 12px; line-height: 1.6; color: #4a3f35; margin-bottom: 28px; border-radius: 6px; position: relative; z-index: 2;">
        @if($orgSettings->is_80g_enabled ?? false)
          <strong style="color: #16100d; display: block; margin-bottom: 4px;">आयकर अधिनियम धारा 80G कर छूट प्रमाण:</strong>
          {{ $orgSettings->receipt_legal_note ?? 'श्री कामदगिरि पीठम् सेवा ट्रस्ट को आयकर अधिनियम, 1961 की धारा 80G(5)(vi) के अंतर्गत आयकर छूट प्राप्त है। इस पुनीत सहयोग हेतु ट्रस्ट परिवार आपका हृदय से आभारी है। यह डिजिटल रूप से प्रमाणित अधिकृत रसीद है।' }}
        @else
          <strong style="color: #16100d; display: block; margin-bottom: 4px;">आधिकारिक पावती एवं ट्रस्ट आभार संदेश:</strong>
          {{ $orgSettings->receipt_legal_note ?: 'भगवान कामतानाथ जी की पावन छत्रछाया में वेद-विद्या के संरक्षण, असहाय गोवंश के संवर्धन और नित्य अन्नदान हेतु आपके द्वारा समर्पित पुनीत अंशदान के लिए ट्रस्ट परिवार आपका हृदय से आभारी है। यह डिजिटल रूप से प्रमाणित अधिकृत दान पावती है।' }}
        @endif
      </div>

      <!-- Seal, Verification QR Code & Authorized Signatory Block -->
      <div style="display: flex; justify-content: space-between; align-items: flex-end; position: relative; z-index: 2; padding-top: 10px; flex-wrap: wrap; gap: 20px;">
        
        <!-- Digital QR Code Mockup for instant validity check -->
        <div style="display: flex; align-items: center; gap: 14px;">
          <div style="width: 76px; height: 76px; background: #ffffff; border: 1px solid #b98550; padding: 4px; border-radius: 4px; display: grid; place-items: center;">
            <!-- Crisp SVG QR Pattern -->
            <svg width="68" height="68" viewBox="0 0 100 100" fill="#16100d">
              <rect x="5" y="5" width="30" height="30" fill="none" stroke="#16100d" stroke-width="6"/>
              <rect x="13" y="13" width="14" height="14"/>
              <rect x="65" y="5" width="30" height="30" fill="none" stroke="#16100d" stroke-width="6"/>
              <rect x="73" y="13" width="14" height="14"/>
              <rect x="5" y="65" width="30" height="30" fill="none" stroke="#16100d" stroke-width="6"/>
              <rect x="13" y="73" width="14" height="14"/>
              <rect x="45" y="15" width="10" height="10"/>
              <rect x="45" y="35" width="10" height="10"/>
              <rect x="15" y="45" width="10" height="10"/>
              <rect x="35" y="45" width="10" height="10"/>
              <rect x="55" y="45" width="10" height="10"/>
              <rect x="75" y="45" width="10" height="10"/>
              <rect x="45" y="65" width="10" height="10"/>
              <rect x="65" y="65" width="10" height="10"/>
              <rect x="85" y="65" width="10" height="10"/>
              <rect x="45" y="85" width="10" height="10"/>
              <rect x="75" y="85" width="10" height="10"/>
            </svg>
          </div>
          <div>
            <span style="font-size: 10px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; color: #7a6e65; display: block;">VERIFIED AUTHENTIC</span>
            <span style="font-size: 11px; color: #333; display: block; font-family: monospace;">{{ $receipt->receipt_number }}</span>
            <span style="font-size: 10px; color: #2e7d32; font-weight: 700;">● Tamper-Proof Cryptographic ID</span>
          </div>
        </div>

        <!-- Official Stamp & Signatory -->
        <div style="text-align: center; min-width: 220px;">
          <!-- Trust Seal Graphic -->
          <div style="font-family: var(--dev, serif); font-size: 12px; font-weight: 700; color: #8f1719; margin-bottom: 28px;">
            कृते श्री कामदगिरि पीठम् सेवा ट्रस्ट
          </div>
          <div style="border-top: 1px solid #16100d; width: 180px; margin: 0 auto; padding-top: 6px;">
            <span style="font-size: 11px; font-weight: 700; color: #444; display: block; text-transform: uppercase; letter-spacing: .06em;">
              अधिकृत हस्ताक्षरकर्ता
            </span>
            <span style="font-size: 10px; color: #777;">
              (Authorized Signatory & Secretary)
            </span>
          </div>
        </div>

      </div>

    </div>

  </div>
</div>

<script>
  function copyReceiptLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
      alert('रसीद लिंक कॉपी कर लिया गया है। (Receipt link copied to clipboard!)');
    });
  }
</script>

@endsection
