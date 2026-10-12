@extends('layouts.app')

@section('title', 'दान रसीद | श्री कामदगिरि पीठम् सेवा ट्रस्ट')

@section('content')

  <style>
    @media print {
      header, footer, .mobile-bar, .mobile-menu, .loader, .ticker-bar, .btn, .underlink, .no-print, nav {
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
      #printableReceipt {
        border: 2px solid #593a28 !important;
        box-shadow: none !important;
        padding: 40px !important;
        margin: 0 auto !important;
        max-width: 750px !important;
        width: 100% !important;
        page-break-inside: avoid !important;
      }
      @page {
        size: A4 portrait;
        margin: 12mm;
      }
    }
  </style>

  <section class="section" style="padding-top: 150px; background: var(--paper); min-height: 80vh;">
    <div class="container" style="max-width: 800px;">

      <div id="printableReceipt" class="receipt-box" style="background: #fff; border: 2px solid var(--brown); padding: 50px; position: relative; box-shadow: 0 20px 60px rgba(0,0,0,.1); border-radius: 6px;">

        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid var(--brown); padding-bottom: 25px; margin-bottom: 30px;">
          <div>
            <h1 style="font: 600 22px var(--serif); color: var(--brown); margin: 0;">{{ $orgSettings->org_name }}</h1>
            <p style="font: 500 12px var(--sans); color: rgba(45,36,30,.7); margin: 5px 0 0;">चित्रकूट धाम, भारत (CHITRAKOOT DHAM)</p>
            <p style="font: 500 11px var(--sans); color: rgba(45,36,30,.5); margin: 3px 0 0;">पंजीकरण संख्या: {{ $orgSettings->reg_number ?? 'REG/CKT/80G' }}</p>
          </div>
          <div style="text-align: right;">
            <span style="display: inline-block; background: var(--sand); color: #fff; padding: 6px 14px; font: 600 10px var(--sans); letter-spacing: .15em; text-transform: uppercase; border-radius: 4px;">आधिकारिक रसीद (OFFICIAL RECEIPT)</span>
            <p style="font: 600 13px var(--sans); margin-top: 10px; color: var(--deep);">रसीद सं: {{ $receipt->receipt_number }}</p>
          </div>
        </div>

        <!-- Receipt Details -->
        <table style="width: 100%; border-collapse: collapse; font: 400 14px var(--dev); margin-bottom: 35px;">
          <tr>
            <td style="padding: 12px 0; border-bottom: 1px solid var(--line); color: rgba(45,36,30,.6); width: 38%;">दानदाता का नाम (Donor Name):</td>
            <td style="padding: 12px 0; border-bottom: 1px solid var(--line); font-weight: 600;">{{ $receipt->donor_name }}</td>
          </tr>
          <tr>
            <td style="padding: 12px 0; border-bottom: 1px solid var(--line); color: rgba(45,36,30,.6);">दान राशि (Donated Amount):</td>
            <td style="padding: 12px 0; border-bottom: 1px solid var(--line); font-weight: 700; font-size: 18px; color: var(--brown);">₹{{ number_format($receipt->amount, 2) }}</td>
          </tr>
          <tr>
            <td style="padding: 12px 0; border-bottom: 1px solid var(--line); color: rgba(45,36,30,.6);">सेवा/प्रकल्प का नाम (Cause / Purpose):</td>
            <td style="padding: 12px 0; border-bottom: 1px solid var(--line); font-weight: 500;">{{ $receipt->cause_title }}</td>
          </tr>
          <tr>
            <td style="padding: 12px 0; border-bottom: 1px solid var(--line); color: rgba(45,36,30,.6);">भुगतान तिथि (Payment Date):</td>
            <td style="padding: 12px 0; border-bottom: 1px solid var(--line);">{{ $receipt->issued_at->format('d F Y, h:i A') }}</td>
          </tr>
          <tr>
            <td style="padding: 12px 0; border-bottom: 1px solid var(--line); color: rgba(45,36,30,.6);">भुगतान स्थिति (Status):</td>
            <td style="padding: 12px 0; border-bottom: 1px solid var(--line); color: #2e7d32; font-weight: 600;">सत्यापित एवं स्वीकृत (Verified & Received)</td>
          </tr>
        </table>

        <div style="background: var(--ivory); padding: 20px; font: 400 12px/1.7 var(--dev); color: rgba(45,36,30,.8); border-left: 4px solid var(--sand); margin-bottom: 35px; border-radius: 4px;">
          {{ $orgSettings->receipt_legal_note ?? 'आयकर अधिनियम की धारा 80G के अंतर्गत सभी दान कर-मुक्त हैं। श्री कामदगिरि पीठम् सेवा ट्रस्ट आपके पावन सहयोग हेतु हृदय से आभारी है।' }}
        </div>

        <!-- Footer signatures & Action -->
        <div style="display: flex; justify-content: space-between; align-items: flex-end;">
          <div class="no-print">
            <button onclick="window.print()" class="btn primary" style="padding: 10px 20px; font-size: 9px; cursor: pointer;">
              रसीद प्रिंट / डाउनलोड करें (Print Receipt)
            </button>
            <a href="{{ url('/') }}" class="btn" style="padding: 10px 20px; font-size: 9px; color: var(--ink); border-color: var(--line);">
              मुख्य पृष्ठ पर जाएँ (Home)
            </a>
          </div>
          <div style="text-align: center;">
            <p style="font: 600 11px var(--serif); color: var(--brown); margin: 0 0 40px;">कृते श्री कामदगिरि पीठम् सेवा ट्रस्ट</p>
            <p style="font: 600 10px var(--sans); border-top: 1px solid var(--line); padding-top: 5px; color: rgba(45,36,30,.6);">अधिकृत हस्ताक्षरकर्ता (Authorized Signatory)</p>
          </div>
        </div>

      </div>

    </div>
  </section>

@endsection
