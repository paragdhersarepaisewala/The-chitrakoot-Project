@extends('layouts.app')

@section('title', 'दान करें | श्री कामदगिरि पीठम् सेवा ट्रस्ट')

@section('styles')
  <style>
    .donate-card {
      background: #FFFDFC;
      border: 1px solid rgba(180, 83, 9, 0.18);
      padding: 44px;
      box-shadow: 0 16px 40px rgba(130, 70, 20, 0.07);
      border-radius: 20px;
    }

    .amount-btn {
      padding: 12px 8px;
      border: 1.5px solid rgba(180, 83, 9, 0.2);
      background: #FAF4ED;
      color: #2B1A12;
      font: 700 14px var(--sans);
      cursor: pointer;
      border-radius: 9999px;
      text-align: center;
      transition: all 0.2s ease;
    }

    .amount-btn:hover {
      border-color: #D95D0F;
      background: #FFFDFC;
      color: #D95D0F;
    }

    .amount-btn.active-amount {
      background: linear-gradient(135deg, #D95D0F 0%, #EA580C 100%) !important;
      color: #fff !important;
      border-color: #D95D0F !important;
      box-shadow: 0 4px 14px rgba(217, 93, 15, 0.35);
    }

    @media (max-width: 640px) {
      .donate-card {
        padding: 24px 18px !important;
        border-radius: 18px;
      }

      .donate-types-grid {
        grid-template-columns: 1fr !important;
        gap: 10px !important;
      }

      .donate-amounts-grid {
        grid-template-columns: repeat(3, 1fr) !important;
        gap: 8px !important;
      }

      .donate-amounts-grid button:last-child {
        grid-column: span 2;
      }
    }
  </style>
@endsection

@section('content')

  <section class="section" style="padding-top: 150px; background: var(--paper, #FAF4ED);">
    <div class="container" style="max-width: 900px;">

      <div class="section-head text-center" style="text-align: center; margin-bottom: 40px;">
        <h1 style="font: 700 clamp(34px, 4.5vw, 54px)/1.15 var(--dev); color: #2B1A12; margin-top: 12px;">अपनी सेवा का चयन करें और दान करें</h1>
        <p style="margin: 15px auto 0; font: 400 15px/1.8 var(--dev); max-width: 620px; color: #7A4B29;">
          गौसेवा, संस्कृत शिक्षा, धार्मिक आयोजनों और समाज कल्याण के हमारे पवित्र प्रकल्पों में आपका योगदान अत्यंत मूल्यवान
          है।
        </p>
      </div>

      <div class="donate-card">

        <form id="donationForm">
          @csrf

          <!-- Contribution Type -->
          <div style="margin-bottom: 26px;">
            <label
              style="display: block; font: 700 11px var(--sans); letter-spacing: .15em; text-transform: uppercase; margin-bottom: 10px; color: #B45309;">योगदान
              का प्रकार (Type)</label>
            <div class="donate-types-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
              <label
                style="display: flex; align-items: center; gap: 10px; padding: 14px 16px; border: 1.5px solid rgba(180,83,9,0.2); cursor: pointer; background: #FAF4ED; border-radius: 14px; font-weight: 600; color: #2B1A12;">
                <input type="radio" name="donation_type" value="cause_specific" checked onclick="toggleCauseSelect(true)"
                  style="accent-color: #D95D0F; width: auto;">
                <span style="font: 600 13.5px var(--dev);">विशेष कार्य हेतु दान (Cause Specific)</span>
              </label>
              <label
                style="display: flex; align-items: center; gap: 10px; padding: 14px 16px; border: 1.5px solid rgba(180,83,9,0.2); cursor: pointer; background: #FAF4ED; border-radius: 14px; font-weight: 600; color: #2B1A12;">
                <input type="radio" name="donation_type" value="monthly_sustaining" onclick="toggleCauseSelect(false)"
                  style="accent-color: #D95D0F; width: auto;">
                <span style="font: 600 13.5px var(--dev);">मासिक स्थायी सदस्यता (Monthly Sustaining)</span>
              </label>
            </div>
          </div>

          <!-- Select Cause -->
          <div id="causeWrapper" style="margin-bottom: 26px;">
            <label
              style="display: block; font: 700 11px var(--sans); letter-spacing: .15em; text-transform: uppercase; margin-bottom: 10px; color: #B45309;">प्रकल्प
              का चयन करें (Select Cause)</label>
            <select name="cause_id" id="cause_id"
              style="width: 100%; padding: 13px 16px; border: 1.5px solid rgba(180,83,9,0.22); font: 500 14px var(--dev); background: #FFFDFC; border-radius: 12px; color: #2B1A12;">
              <option value="">सामान्य सेवा (General Seva)</option>
              @foreach($causes as $cause)
                <option value="{{ $cause->id }}" {{ request('cause_id') == $cause->id ? 'selected' : '' }}>{{ $cause->title }}
                </option>
              @endforeach
            </select>
          </div>

          <!-- Preset Amounts -->
          <div style="margin-bottom: 26px;">
            <label
              style="display: block; font: 700 11px var(--sans); letter-spacing: .15em; text-transform: uppercase; margin-bottom: 10px; color: #B45309;">राशि
              का चयन करें (Select Amount)</label>
            @php
              $rawPresets = $orgSettings->donation_preset_amounts ?? '501,1100,2100,5100';
              $presets = array_values(array_filter(array_map('intval', explode(',', (string) $rawPresets)))) ?: [501, 1100, 2100, 5100];
              $firstPreset = $presets[0] ?? 501;
            @endphp
            <div class="donate-amounts-grid"
              style="display: grid; grid-template-columns: repeat({{ count($presets) + 1 }}, 1fr); gap: 10px; margin-bottom: 14px;">
              @foreach($presets as $pIndex => $pAmt)
                <button type="button" class="amount-btn {{ $pIndex === 0 ? 'active-amount' : '' }}"
                  onclick="setAmount({{ $pAmt }}, this)">₹{{ number_format($pAmt) }}</button>
              @endforeach
              <button type="button" class="amount-btn" onclick="setCustomAmount(this)" style="white-space: nowrap;">Custom
                Amount</button>
            </div>
            <input type="number" name="amount" id="customAmountInput" value="{{ $firstPreset }}" min="10" required
              style="width: 100%; padding: 14px 16px; border: 1.5px solid rgba(180,83,9,0.22); font: 700 18px var(--sans); border-radius: 12px; box-sizing: border-box; background: #FFFDFC; color: #D95D0F;"
              placeholder="राशि प्रविष्ट करें (Enter Amount in ₹)">
          </div>

          <!-- Donor Details -->
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
            <div>
              <label
                style="display: block; font: 700 11px var(--sans); letter-spacing: .15em; text-transform: uppercase; margin-bottom: 8px; color: #B45309;">दानदाता
                का नाम (Donor Name) *</label>
              <input type="text" name="donor_name" id="donor_name" value="{{ auth()->user()?->name }}" required
                style="width: 100%; padding: 13px 16px; border: 1.5px solid rgba(180,83,9,0.22); font: 500 14px var(--dev); border-radius: 12px; background: #FFFDFC; color: #2B1A12; box-sizing: border-box;"
                placeholder="आपका पूरा नाम (Full Name)">
            </div>
            <div>
              <label
                style="display: block; font: 700 11px var(--sans); letter-spacing: .15em; text-transform: uppercase; margin-bottom: 8px; color: #B45309;">ईमेल
                आईडी
                ({{ ($orgSettings->is_80g_enabled ?? false) ? 'Email for 80G Receipt' : 'Email for Donation Receipt' }})
                *</label>
              <input type="email" name="donor_email" id="donor_email" value="{{ auth()->user()?->email }}" required
                style="width: 100%; padding: 13px 16px; border: 1.5px solid rgba(180,83,9,0.22); font: 500 14px var(--sans); border-radius: 12px; background: #FFFDFC; color: #2B1A12; box-sizing: border-box;"
                placeholder="your.email@example.com">
            </div>
          </div>

          <div style="margin-bottom: 24px;">
            <label
              style="display: block; font: 700 11px var(--sans); letter-spacing: .15em; text-transform: uppercase; margin-bottom: 8px; color: #B45309;">मोबाइल
              नंबर (Mobile Phone) *</label>
            <input type="tel" name="donor_phone" id="donor_phone"
              value="{{ auth()->user()?->phone ?? auth()->user()?->profile?->phone }}" required minlength="10"
              maxlength="15" pattern="[0-9\+\-\s]{10,15}"
              style="width: 100%; padding: 13px 16px; border: 1.5px solid rgba(180,83,9,0.22); font: 500 14px var(--sans); border-radius: 12px; background: #FFFDFC; color: #2B1A12; box-sizing: border-box;"
              placeholder="+91 98765 43210">
            <div
              style="font-size: 11.5px; color: #7A6250; margin-top: 8px; line-height: 1.5; display: flex; align-items: center; gap: 6px;">
              <span style="color: #059669; font-weight: 700;">✓</span>
              {{ ($orgSettings->is_80g_enabled ?? false) ? 'आधिकारिक 80G दान पावती तुरंत इस ईमेल पर प्रेषित होगी।' : 'आधिकारिक डिजिटल दान पावती तुरंत इस ईमेल पर प्रेषित होगी।' }}
              बाद में इसी ईमेल से साइनअप करने पर यह दान स्वतः आपके प्रोफाइल डैशबोर्ड में जुड़ जाएगा।
            </div>
          </div>

          <button type="submit" class="btn primary"
            style="width: 100%; justify-content: center; padding: 16px; font-size: 14px; font-weight: 800; border-radius: 9999px; white-space: nowrap;">
            Proceed to Pay &rarr;
          </button>
        </form>

      </div>
    </div>
  </section>

@endsection

@push('scripts')
  <!-- Official Razorpay Checkout SDK -->
  <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
  <script>
    function toggleCauseSelect(show) {
      document.getElementById('causeWrapper').style.display = show ? 'block' : 'none';
    }

    function setAmount(amt, btn) {
      document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('active-amount'));
      if (btn) btn.classList.add('active-amount');
      document.getElementById('customAmountInput').value = amt;
    }

    function setCustomAmount(btn) {
      document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('active-amount'));
      if (btn) btn.classList.add('active-amount');
      const input = document.getElementById('customAmountInput');
      input.focus();
      input.select();
    }

    document.getElementById('donationForm').addEventListener('submit', async function (e) {
      e.preventDefault();
      const submitBtn = this.querySelector('button[type="submit"]');
      const originalBtnText = submitBtn.innerHTML;
      submitBtn.disabled = true;
      submitBtn.innerHTML = 'गेटवे से संपर्क हो रहा है... (Connecting to Razorpay Secure...)';

      const formData = new FormData(this);

      try {
        const response = await fetch("{{ url('/api/donations/create-order') }}", {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
          },
          body: formData
        });

        const res = await response.json();

        if (!res.success) {
          alert(res.message || 'भुगतान आदेश सृजित करने में त्रुटि। कृपया पुनः प्रयास करें।');
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalBtnText;
          return;
        }

        // Initialize authentic Razorpay Checkout instance
        const options = {
          key: res.key,
          amount: res.amount,
          currency: res.currency || 'INR',
          name: res.org_name || 'Shri Kamadgiri Peetham Sewa Trust',
          description: 'पवित्र सेवा दान (Charitable Contribution)',
          order_id: res.order_id,
          handler: async function (paymentResponse) {
            submitBtn.innerHTML = 'भुगतान सत्यापन हो रहा है... (Verifying Payment...)';
            try {
              const verifyRes = await fetch("{{ url('/api/donations/verify-payment') }}", {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                  donation_id: res.donation_id,
                  razorpay_order_id: paymentResponse.razorpay_order_id,
                  razorpay_payment_id: paymentResponse.razorpay_payment_id,
                  razorpay_signature: paymentResponse.razorpay_signature,
                  donor_name: document.getElementById('donor_name').value,
                  donor_email: document.getElementById('donor_email').value,
                  donor_phone: document.getElementById('donor_phone') ? document.getElementById('donor_phone').value : ''
                })
              });

              const vRes = await verifyRes.json();
              if (vRes.success) {
                window.location.href = vRes.receipt_url;
              } else {
                alert(vRes.message || 'भुगतान सत्यापन असफल रहा। कृपया सपोर्ट से संपर्क करें।');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
              }
            } catch (err) {
              alert('सत्यापन के समय तकनीकी त्रुटि: ' + err.message);
              submitBtn.disabled = false;
              submitBtn.innerHTML = originalBtnText;
            }
          },
          prefill: {
            name: document.getElementById('donor_name').value,
            email: document.getElementById('donor_email').value,
            contact: document.getElementById('donor_phone') ? document.getElementById('donor_phone').value : ''
          },
          theme: {
            color: '#b98550'
          },
          modal: {
            ondismiss: function () {
              submitBtn.disabled = false;
              submitBtn.innerHTML = originalBtnText;
            }
          }
        };

        const rzp = new Razorpay(options);
        rzp.on('payment.failed', function (failResp) {
          alert('भुगतान असफल: ' + (failResp.error.description || 'लेन-देन रद्द कर दिया गया'));
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalBtnText;
        });
        rzp.open();

      } catch (err) {
        alert("Error initiating checkout: " + err.message);
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
      }
    });
  </script>
@endpush