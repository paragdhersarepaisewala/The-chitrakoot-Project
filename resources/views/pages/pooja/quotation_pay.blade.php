@extends('layouts.app')

@section('title', 'पूजा व अनुष्ठान दक्षिणा कोटेशन | ' . ($orgSettings->org_name ?? 'श्री कामदगिरि पीठम्'))
@section('meta_description', 'श्री कामदगिरि पीठम् सेवा ट्रस्ट द्वारा जारी आधिकारिक पूजा व अनुष्ठान दक्षिणा पत्र एवं ऑनलाइन भुगतान।')

@section('content')
  <section
    style="background: radial-gradient(circle at 50% 20%, #FAF6EE 0%, #F3EDE0 100%); padding: 50px 20px 80px; min-height: 85vh;">
    <div style="max-width: 720px; margin: 0 auto;">

      <!-- Top Sanctuary Emblem -->
      <div style="text-align: center; margin-bottom: 26px;">
        <div
          style="display: inline-flex; align-items: center; justify-content: center; width: 72px; height: 72px; border-radius: 50%; background: #FAF4ED; border: 2.5px solid #B45309; color: #B45309; box-shadow: 0 10px 25px rgba(180,83,9,0.18); margin-bottom: 14px;">
          <svg width="36" height="36" viewBox="0 0 24 24" fill="currentColor">
            <path
              d="M12 2c-.3 0-.6.2-.7.5C10 5.4 8 8.6 8 12c0 2.2 1.8 4 4 4s4-1.8 4-4c0-3.4-2-6.6-3.3-9.5-.1-.3-.4-.5-.7-.5zm0 18c-3.3 0-6-2.7-6-6 0-3.8 2.5-7.7 4.3-10.4.5-.7 1.6-.7 2.1 0C14.2 6.3 16.7 10.2 16.7 14c0 3.3-2.7 6-6 6z" />
          </svg>
        </div>
        <span
          style="font-size: 11.5px; font-weight: 800; color: #B45309; letter-spacing: 1.5px; text-transform: uppercase; display: block;">श्री
          कामदगिरि पीठम् सेवा ट्रस्ट · चित्रकूट धाम</span>
        <h1
          style="font-family: 'Noto Serif', serif; font-size: clamp(24px, 2.8vw, 30px); font-weight: 800; color: #2B1A12; margin: 6px 0 4px;">
          पूजा व अनुष्ठान दक्षिणा कोटेशन
        </h1>
        <span style="font-size: 13px; color: #7A4B29; font-weight: 600;">Official Ritual Dakshina & Sacred Offering
          Quotation</span>
      </div>

      <!-- Quotation Card -->
      <div
        style="background: #ffffff; border-radius: 22px; box-shadow: 0 20px 48px rgba(43,26,18,0.1); border: 1.5px solid rgba(180,83,9,0.22); overflow: hidden;">

        <!-- Card Header -->
        <div
          style="background: linear-gradient(135deg, #2B1A12 0%, #4A2E1F 100%); color: #fff; padding: 22px 28px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
          <div>
            <span
              style="font-size: 10.5px; text-transform: uppercase; letter-spacing: 1px; color: #F59E0B; font-weight: 700; display: block;">संकल्प
              संदर्भ संख्या (Booking Ref)</span>
            <strong
              style="font-size: 19px; font-family: monospace; letter-spacing: 0.5px; color: #FFFDFC;">#{{ $booking->booking_number }}</strong>
          </div>
          <div style="text-align: right;">
            <span style="font-size: 10.5px; color: rgba(255,255,255,0.7); display: block;">प्रस्तावित अनुष्ठान तिथि व
              समय</span>
            <strong
              style="font-size: 14px; color: #FAF4ED;">{{ $booking->booking_date ? $booking->booking_date->format('d M Y') : 'निर्धारित तिथि' }}
              ({{ $booking->time_slot }})</strong>
          </div>
        </div>

        <div style="padding: 28px;">
          @if(session('error'))
            <div
              style="background: #FEE2E2; border-left: 4px solid #DC2626; color: #991B1B; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; font-weight: 600;">
              {{ session('error') }}
            </div>
          @endif

          @if($isExpired)
            <!-- Expired Notice -->
            <div id="expiredBanner"
              style="background: #FFF1F2; border: 1.5px solid #F43F5E; border-radius: 14px; padding: 24px; text-align: center; margin-bottom: 24px;">
              <span style="font-size: 34px; display: block; margin-bottom: 8px;">⏳</span>
              <h2 style="font-size: 18px; font-weight: 800; color: #9F1239; margin: 0 0 6px;">यह कोटेशन लिंक समाप्त (Expired) हो चुका है</h2>
              <p style="font-size: 13px; color: #881337; margin: 0 0 16px; line-height: 1.6;">
                वैदिक आचार्यों की समय-सारणी आरक्षण व्यवस्था के तहत यह दक्षिणा कोटेशन लिंक केवल ६ से १२ घंटे तक ही सक्रिय था।
                नया कोटेशन प्राप्त करने अथवा सहायता हेतु कृपया न्यास कार्यालय से संपर्क करें।
              </p>
              <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
                <a href="tel:{{ $orgSettings->org_phone ?? '+919415000000' }}"
                  style="display: inline-flex; align-items: center; gap: 6px; background: #B45309; color: #fff; padding: 10px 20px; border-radius: 9999px; font-size: 12.5px; font-weight: 800; text-decoration: none;">
                  📞 न्यास कार्यालय संपर्क करें
                </a>
                <a href="{{ route('pooja.bookingForm') }}"
                  style="display: inline-flex; align-items: center; gap: 6px; background: #fff; border: 1.5px solid #B45309; color: #B45309; padding: 9px 18px; border-radius: 9999px; font-size: 12.5px; font-weight: 800; text-decoration: none;">
                  नया संकल्प अनुरोध करें &rarr;
                </a>
              </div>
            </div>
          @else
            <!-- Active Notice with Live Countdown Timer (6 to 12 Hours Validity) -->
            <div id="countdownBox"
              style="background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%); border: 1.5px solid #F59E0B; border-radius: 14px; padding: 16px 20px; margin-bottom: 24px; box-shadow: 0 4px 14px rgba(245,158,11,0.12);">
              <div
                style="display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap; margin-bottom: 8px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                  <span style="font-size: 20px; animation: pulse 2s infinite;">⏳</span>
                  <div>
                    <strong style="font-size: 13.5px; color: #92400E; display: block;">पावन समय-सीमा (Validity Window: 6-12
                      Hours)</strong>
                    <span style="font-size: 11.5px; color: #B45309;">वैधता समाप्ति:
                      {{ $booking->quotation_expires_at ? $booking->quotation_expires_at->format('d M Y, h:i A') : '६ घंटे' }}</span>
                  </div>
                </div>

                <!-- Countdown Display -->
                <div id="countdownClock" style="display: flex; align-items: center; gap: 6px; font-family: monospace;">
                  <div
                    style="background: #92400E; color: #fff; font-weight: 800; font-size: 15px; padding: 4px 8px; border-radius: 6px; min-width: 32px; text-align: center;">
                    <span id="hoursLeft">--</span>
                    <span
                      style="font-size: 8px; display: block; font-family: sans-serif; text-transform: uppercase;">Hrs</span>
                  </div>
                  <span style="font-weight: 800; color: #92400E;">:</span>
                  <div
                    style="background: #92400E; color: #fff; font-weight: 800; font-size: 15px; padding: 4px 8px; border-radius: 6px; min-width: 32px; text-align: center;">
                    <span id="minsLeft">--</span>
                    <span
                      style="font-size: 8px; display: block; font-family: sans-serif; text-transform: uppercase;">Min</span>
                  </div>
                  <span style="font-weight: 800; color: #92400E;">:</span>
                  <div
                    style="background: #92400E; color: #fff; font-weight: 800; font-size: 15px; padding: 4px 8px; border-radius: 6px; min-width: 32px; text-align: center;">
                    <span id="secsLeft">--</span>
                    <span
                      style="font-size: 8px; display: block; font-family: sans-serif; text-transform: uppercase;">Sec</span>
                  </div>
                </div>
              </div>
              <p style="font-size: 11.5px; color: #78350F; margin: 0; line-height: 1.5;">
                * कृपया समय-सीमा समाप्त होने से पूर्व दक्षिणा अर्पित कर अपना पूजा स्लॉट सुनिश्चित करें। नियत समय बीतने पर यह
                स्लॉट अन्य यजमानों हेतु पुनः उपलब्ध हो जाता है।
              </p>
            </div>
          @endif

          <!-- Ritual Details Table -->
          <div
            style="background: #FAF7F2; border: 1px solid rgba(180,83,9,0.18); border-radius: 16px; padding: 22px; margin-bottom: 24px;">
            <h2
              style="font-family: 'Noto Serif', serif; font-size: 16.5px; font-weight: 800; color: #2B1A12; margin: 0 0 16px; border-bottom: 1.5px solid rgba(180,83,9,0.15); padding-bottom: 10px; display: flex; align-items: center; justify-content: space-between;">
              <span>अनुष्ठान एवं यजमान विवरण</span>
              <span
                style="font-size: 11px; font-weight: 700; color: #2D6A4F; background: rgba(45,106,79,0.1); padding: 3px 10px; border-radius: 9999px;">समीक्षा
                पूर्ण</span>
            </h2>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px 22px; font-size: 13.5px;">
              <div>
                <span style="color: #7A4B29; font-size: 11px; font-weight: 700; display: block;">वैदिक अनुष्ठान
                  (Ritual):</span>
                <strong style="color: #2B1A12; font-size: 14.5px;">{{ $booking->getEffectiveRitualTitle() }}</strong>
              </div>
              <div>
                <span style="color: #7A4B29; font-size: 11px; font-weight: 700; display: block;">यजमान / भक्त का
                  नाम:</span>
                <strong style="color: #2B1A12;">{{ $booking->devotee_name }}</strong>
              </div>
              <div>
                <span style="color: #7A4B29; font-size: 11px; font-weight: 700; display: block;">गोत्र / नक्षत्र:</span>
                <span style="color: #2B1A12; font-weight: 600;">{{ $booking->gotra ?: 'Not Specified' }}
                  @if($booking->nakshatra) ({{ $booking->nakshatra }}) @endif</span>
              </div>
              <div>
                <span style="color: #7A4B29; font-size: 11px; font-weight: 700; display: block;">मंदिर समय-स्लॉट:</span>
                <span style="color: #8F1719; font-weight: 700;">{{ $booking->time_slot }}</span>
              </div>
              @if($booking->sankalp_purpose)
                <div style="grid-column: span 2;">
                  <span style="color: #7A4B29; font-size: 11px; font-weight: 700; display: block;">विशेष संकल्प
                    प्रार्थना:</span>
                  <span
                    style="color: #2B1A12; font-style: italic; background: #fff; padding: 6px 12px; border-radius: 6px; border: 1px dashed rgba(180,83,9,0.2); display: block; margin-top: 2px;">
                    "{{ $booking->sankalp_purpose }}"
                  </span>
                </div>
              @endif
            </div>
          </div>

          <!-- Dakshina Amount Highlight -->
          <div
            style="background: linear-gradient(135deg, #FFF7ED 0%, #FFEDD5 100%); border: 2px dashed #B45309; border-radius: 18px; padding: 24px; text-align: center; margin-bottom: 26px; box-shadow: 0 4px 14px rgba(180,83,9,0.06);">
            <span
              style="font-size: 11.5px; font-weight: 800; color: #9A3412; letter-spacing: 1.2px; text-transform: uppercase; display: block; margin-bottom: 4px;">स्वीकृत
              दक्षिणा एवं सामग्री व्यवस्था</span>
            <div
              style="font-size: 38px; font-weight: 900; color: #8F1719; line-height: 1.1; font-family: 'Noto Serif', serif;">
              ₹{{ number_format((float) $booking->quotation_amount, 2) }}
            </div>
            @if($booking->quotation_notes)
              <div
                style="margin-top: 12px; font-size: 13px; color: #7A4B29; background: rgba(255,255,255,0.85); padding: 8px 16px; border-radius: 8px; display: inline-block; border: 1px solid rgba(180,83,9,0.18);">
                <strong style="color: #8F1719;">व्यवस्थापक संदेश:</strong> {{ $booking->quotation_notes }}
              </div>
            @endif
          </div>

          @if(!$isExpired)
            <!-- Payment Gateway Section -->
            <div id="paymentOptionsContainer">

              <!-- Primary Razorpay Gateway Button -->
              <form id="quotationPayForm"
                action="{{ route('pooja.quotation.verify', ['token' => $booking->quotation_token]) }}" method="POST">
                @csrf
                <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id" value="">
                <input type="hidden" name="razorpay_order_id" id="razorpay_order_id" value="">
                <input type="hidden" name="razorpay_signature" id="razorpay_signature" value="">

                <button type="button" id="payDakshinaBtn" onclick="initiateRazorpayPayment()"
                  style="width: 100%; background: linear-gradient(135deg, #D95D0F 0%, #EA580C 100%); color: #ffffff; padding: 18px 28px; border: none; border-radius: 9999px; font-size: 16px; font-weight: 800; cursor: pointer; box-shadow: 0 8px 24px rgba(217,93,15,0.38); transition: transform 0.2s, box-shadow 0.2s; display: flex; align-items: center; justify-content: center; gap: 10px; white-space: nowrap;">
                  <span>🔒 Pay Online (₹{{ number_format((float) $booking->quotation_amount, 0) }}) &rarr;</span>
                </button>
              </form>

              <!-- Supported Gateways & Logos -->
              <div style="text-align: center; margin: 16px 0 24px;">
                <span style="font-size: 11.5px; color: #7A4B29; font-weight: 700; display: block; margin-bottom: 8px;">
                  स्वीकृत डिजिटल भुगतान माध्यम (Supported Payment Modes)
                </span>
                <div style="display: flex; align-items: center; justify-content: center; gap: 10px; flex-wrap: wrap;">
                  <span
                    style="font-size: 11px; font-weight: 800; color: #2B1A12; background: #FAF4ED; padding: 4px 10px; border-radius: 6px; border: 1px solid rgba(180,83,9,0.18);">UPI
                    (GPay / PhonePe / Paytm / BHIM)</span>
                  <span
                    style="font-size: 11px; font-weight: 800; color: #2B1A12; background: #FAF4ED; padding: 4px 10px; border-radius: 6px; border: 1px solid rgba(180,83,9,0.18);">RuPay
                    / Debit & Credit Cards</span>
                  <span
                    style="font-size: 11px; font-weight: 800; color: #2B1A12; background: #FAF4ED; padding: 4px 10px; border-radius: 6px; border: 1px solid rgba(180,83,9,0.18);">NetBanking
                    (50+ Banks)</span>
                </div>
              </div>

              <!-- Trust & Security Badges (Razorpay Exclusively) -->
              <div
                style="text-align: center; margin-top: 24px; font-size: 11.5px; color: #7A4B29; display: flex; align-items: center; justify-content: center; gap: 20px; flex-wrap: wrap;">
                <span style="display: inline-flex; align-items: center; gap: 6px;">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#2D6A4F" stroke-width="2.5">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                  </svg>
                  256-Bit SSL Encrypted Razorpay Gateway
                </span>
                <span style="display: inline-flex; align-items: center; gap: 6px;">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#B45309" stroke-width="2.5">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" />
                  </svg>
                  त्वरित पुरोहित समय-सारणी आरक्षण
                </span>
                <span style="display: inline-flex; align-items: center; gap: 6px;">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#8F1719" stroke-width="2.5">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12 6 12 12 14 14" />
                  </svg>
                  अधिकृत डिजिटल संकल्प पावती
                </span>
              </div>

            </div>
          @endif

        </div>

      </div>

      <!-- Devotee Help Footer -->
      <div style="text-align: center; margin-top: 24px; font-size: 12.5px; color: #7A4B29;">
        किसी भी जिज्ञासा अथवा समय परिवर्तन हेतु संपर्क करें:
        <a href="tel:{{ $orgSettings->org_phone ?? '+919415000000' }}"
          style="color: #B45309; font-weight: 800; text-decoration: underline;">{{ $orgSettings->org_phone ?? '+91 94150 00000' }}</a>
        | <a href="mailto:{{ $orgSettings->org_email ?? 'info@kamadgiripeetham.org' }}"
          style="color: #B45309; font-weight: 800; text-decoration: underline;">{{ $orgSettings->org_email ??
          'info@kamadgiripeetham.org' }}</a>
      </div>

    </div>
  </section>

  <!-- Razorpay Checkout Integration -->
  <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
  <script>
    // 1. Live Countdown Timer for 6-12 Hour Expiration Window
    @if(!$isExpired && $booking->quotation_expires_at)
      const expirationTimestamp = new Date("{{ $booking->quotation_expires_at->toIso8601String() }}").getTime();

      function updateCountdown() {
        const now = new Date().getTime();
        const distance = expirationTimestamp - now;

        if (distance <= 0) {
          document.getElementById('countdownClock').innerHTML = "<span style='color: #DC2626; font-weight: 800; font-size: 13px;'>समाप्त (EXPIRED)</span>";
          const payBtn = document.getElementById('payDakshinaBtn');
          if (payBtn) {
            payBtn.disabled = true;
            payBtn.style.opacity = '0.5';
            payBtn.innerText = 'कोटेशन समय-सीमा समाप्त हो चुकी है';
          }
          return;
        }

        const hours = Math.floor(distance / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        const hEl = document.getElementById('hoursLeft');
        const mEl = document.getElementById('minsLeft');
        const sEl = document.getElementById('secsLeft');

        if (hEl) hEl.innerText = hours < 10 ? '0' + hours : hours;
        if (mEl) mEl.innerText = minutes < 10 ? '0' + minutes : minutes;
        if (sEl) sEl.innerText = seconds < 10 ? '0' + seconds : seconds;
      }

      updateCountdown();
      setInterval(updateCountdown, 1000);
    @endif

    // 2. Official Razorpay Payment Gateway Integration
    function initiateRazorpayPayment() {
      const btn = document.getElementById('payDakshinaBtn');
      const origHtml = btn.innerHTML;
      btn.disabled = true;
      btn.innerHTML = '<span>Please wait, connecting to secure gateway... (Creating Order)</span>';

      // Call Server-Side Order Generation
      fetch("{{ route('pooja.quotation.createOrder', ['token' => $booking->quotation_token]) }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
        .then(res => res.json())
        .then(data => {
          if (!data.success) {
            alert(data.message || 'गेटवे ऑर्डर सृजित करने में त्रुटि आई। कृपया पुनः प्रयास करें।');
            btn.disabled = false;
            btn.innerHTML = origHtml;
            return;
          }

          const razorpayKey = data.key;

          // Check if test placeholder (sandbox fallback)
          if (!razorpayKey || razorpayKey === 'rzp_test_placeholder') {
            document.getElementById('razorpay_payment_id').value = 'RZP-DEV-' + Date.now();
            document.getElementById('razorpay_order_id').value = data.order_id;
            document.getElementById('razorpay_signature').value = 'verified_test_sig';
            document.getElementById('quotationPayForm').submit();
            return;
          }

          const options = {
            key: razorpayKey,
            amount: data.amount,
            currency: "INR",
            name: "{{ $orgSettings->org_name ?? 'Shri Kamadgiri Peetham Sewa Trust' }}",
            description: "पूजा दक्षिणा: #{{ $booking->booking_number }} - {{ $booking->getEffectiveRitualTitle() }}",
            image: "{{ $orgSettings->logo_path ?: url('/favicon.ico') }}",
            order_id: data.order_id,
            prefill: {
              name: data.devotee_name,
              email: data.devotee_email,
              contact: data.devotee_phone
            },
            notes: {
              booking_number: data.booking_number,
              ritual_name: data.ritual_name
            },
            theme: {
              color: "#B45309"
            },
            handler: function (response) {
              btn.innerHTML = '<span>Verifying payment...</span>';
              document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
              document.getElementById('razorpay_order_id').value = response.razorpay_order_id || data.order_id;
              document.getElementById('razorpay_signature').value = response.razorpay_signature || 'verified';
              document.getElementById('quotationPayForm').submit();
            },
            modal: {
              ondismiss: function () {
                btn.disabled = false;
                btn.innerHTML = origHtml;
              }
            }
          };

          const rzp = new Razorpay(options);
          rzp.on('payment.failed', function (resp) {
            alert("भुगतान प्रक्रिया विफल रही: " + (resp.error.description || 'त्रुटि हुई।'));
            btn.disabled = false;
            btn.innerHTML = origHtml;
          });
          rzp.open();
        })
        .catch(err => {
          console.error(err);
          alert('नेटवर्क अथवा सर्वर कनेक्शन त्रुटि हुई। कृपया इंटरनेट कनेक्शन जांचें।');
          btn.disabled = false;
          btn.innerHTML = origHtml;
        });
    }
  </script>
@endsection