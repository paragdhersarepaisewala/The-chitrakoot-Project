@extends('layouts.app')

@section('title', 'दान करें | श्री कामदगिरि पीठम् सेवा ट्रस्ट')

@section('content')

  <section class="section" style="padding-top: 150px; background: var(--paper);">
    <div class="container" style="max-width: 900px;">

      <div class="section-head text-center" style="text-align: center; margin-bottom: 40px;">
        <div class="eyebrow" style="justify-content: center;">Support Our Mission</div>
        <h1 style="font: 500 clamp(36px, 5vw, 56px)/1.1 var(--dev); margin-top: 10px;">अपनी सेवा का चयन करें और दान करें</h1>
        <p style="margin: 15px auto 0; font: 400 14px/1.8 var(--dev); max-width: 600px;">
          गौसेवा, संस्कृत शिक्षा, धार्मिक आयोजनों और समाज कल्याण के हमारे पवित्र प्रकल्पों में आपका योगदान अत्यंत मूल्यवान है।
        </p>
      </div>

      <div style="background: var(--white); border: 1px solid var(--line); padding: 40px; box-shadow: 0 15px 45px rgba(0,0,0,.06); border-radius: 8px;">

        <form id="donationForm">
          @csrf

          <!-- Contribution Type -->
          <div style="margin-bottom: 30px;">
            <label style="display: block; font: 600 11px var(--sans); letter-spacing: .15em; text-transform: uppercase; margin-bottom: 12px; color: var(--brown);">योगदान का प्रकार (Type)</label>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
              <label style="display: flex; align-items: center; gap: 10px; padding: 15px; border: 1px solid var(--line); cursor: pointer; background: var(--ivory); border-radius: 6px;">
                <input type="radio" name="donation_type" value="cause_specific" checked onclick="toggleCauseSelect(true)">
                <span style="font: 500 14px var(--dev);">विशेष कार्य हेतु दान (Cause Specific)</span>
              </label>
              <label style="display: flex; align-items: center; gap: 10px; padding: 15px; border: 1px solid var(--line); cursor: pointer; background: var(--ivory); border-radius: 6px;">
                <input type="radio" name="donation_type" value="monthly_sustaining" onclick="toggleCauseSelect(false)">
                <span style="font: 500 14px var(--dev);">मासिक स्थायी सदस्यता (Monthly Sustaining)</span>
              </label>
            </div>
          </div>

          <!-- Select Cause -->
          <div id="causeWrapper" style="margin-bottom: 30px;">
            <label style="display: block; font: 600 11px var(--sans); letter-spacing: .15em; text-transform: uppercase; margin-bottom: 12px; color: var(--brown);">प्रकल्प का चयन करें (Select Cause)</label>
            <select name="cause_id" id="cause_id" style="width: 100%; padding: 14px; border: 1px solid var(--line); font: 500 14px var(--dev); background: #fff; border-radius: 6px;">
              <option value="">सामान्य सेवा (General Seva)</option>
              @foreach($causes as $cause)
                <option value="{{ $cause->id }}">{{ $cause->title }}</option>
              @endforeach
            </select>
          </div>

          <!-- Preset Amounts -->
          <div style="margin-bottom: 30px;">
            <label style="display: block; font: 600 11px var(--sans); letter-spacing: .15em; text-transform: uppercase; margin-bottom: 12px; color: var(--brown);">राशि का चयन करें (Select Amount)</label>
            <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px; margin-bottom: 15px;">
              <button type="button" class="amount-btn" onclick="setAmount(501)" style="padding: 12px; border: 1px solid var(--line); background: var(--ivory); font: 600 14px var(--sans); cursor: pointer; border-radius: 4px;">₹501</button>
              <button type="button" class="amount-btn" onclick="setAmount(1100)" style="padding: 12px; border: 1px solid var(--line); background: var(--ivory); font: 600 14px var(--sans); cursor: pointer; border-radius: 4px;">₹1,100</button>
              <button type="button" class="amount-btn" onclick="setAmount(2100)" style="padding: 12px; border: 1px solid var(--line); background: var(--ivory); font: 600 14px var(--sans); cursor: pointer; border-radius: 4px;">₹2,100</button>
              <button type="button" class="amount-btn" onclick="setAmount(5100)" style="padding: 12px; border: 1px solid var(--line); background: var(--ivory); font: 600 14px var(--sans); cursor: pointer; border-radius: 4px;">₹5,100</button>
              <button type="button" class="amount-btn" onclick="setCustomAmount()" style="padding: 12px; border: 1px solid var(--line); background: var(--ivory); font: 600 14px var(--sans); cursor: pointer; border-radius: 4px;">अन्य (Custom)</button>
            </div>
            <input type="number" name="amount" id="customAmountInput" value="1100" min="10" required style="width: 100%; padding: 14px; border: 1px solid var(--line); font: 600 16px var(--sans); border-radius: 6px;" placeholder="राशि प्रविष्ट करें (Enter Amount in ₹)">
          </div>

          <!-- Donor Details -->
          <div style="margin-bottom: 30px;">
            <label style="display: block; font: 600 11px var(--sans); letter-spacing: .15em; text-transform: uppercase; margin-bottom: 12px; color: var(--brown);">दानदाता का नाम (Donor Name)</label>
            <input type="text" name="donor_name" id="donor_name" value="{{ auth()->user()?->name }}" required style="width: 100%; padding: 14px; border: 1px solid var(--line); font: 500 14px var(--dev); border-radius: 6px;" placeholder="आपका नाम (Full Name)">
          </div>

          <button type="submit" class="btn primary" style="width: 100%; justify-content: center; padding: 16px; font-size: 11px; border-radius: 6px;">
            सुरक्षित दान करें (Proceed to Pay via Razorpay) <svg viewBox="0 0 24 24"><path d="M5 12h13M13 6l6 6-6 6"/></svg>
          </button>
        </form>

      </div>
    </div>
  </section>

  <!-- Razorpay Test Gateway Spoof Modal -->
  <div id="razorpayMockModal" style="display: none; position: fixed; inset: 0; z-index: 999999; background: rgba(0,0,0,0.7); backdrop-filter: blur(8px); align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #fff; width: 100%; max-width: 440px; border-radius: 12px; overflow: hidden; box-shadow: 0 25px 60px rgba(0,0,0,0.3); font-family: sans-serif;">
      
      <!-- Razorpay Header -->
      <div style="background: #0c2340; color: #fff; padding: 20px 24px; display: flex; justify-content: space-between; align-items: center;">
        <div>
          <div style="font-size: 11px; font-weight: 700; letter-spacing: 0.1em; color: #00baf2;">RAZORPAY SECURE (TEST MODE)</div>
          <div style="font-size: 16px; font-weight: 600; margin-top: 4px;">Shri Kamadgiri Peetham</div>
        </div>
        <button onclick="closeRazorpayMock()" style="background: none; border: 0; color: #fff; font-size: 24px; cursor: pointer;">&times;</button>
      </div>

      <!-- Payment Body -->
      <div style="padding: 24px;">
        <div style="text-align: center; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px dashed #ddd;">
          <span style="font-size: 13px; color: #666; text-transform: uppercase;">Total Payable Amount</span>
          <h2 id="mockAmountDisplay" style="font-size: 32px; font-weight: 800; color: #0c2340; margin: 4px 0 0;">₹1,100</h2>
        </div>

        <div style="margin-bottom: 20px;">
          <div style="font-size: 12px; font-weight: 700; color: #555; text-transform: uppercase; margin-bottom: 10px;">Select Test Payment Method</div>
          <div style="display: flex; flex-direction: column; gap: 8px;">
            <label style="display: flex; align-items: center; gap: 10px; padding: 12px; border: 1px solid #00baf2; border-radius: 6px; background: #f0f9ff; cursor: pointer; font-size: 14px; font-weight: 600; color: #0c2340;">
              <input type="radio" name="mock_method" checked>
              <span>UPI / QR Code / NetBanking (Test Mode)</span>
            </label>
            <label style="display: flex; align-items: center; gap: 10px; padding: 12px; border: 1px solid #eee; border-radius: 6px; cursor: pointer; font-size: 14px; color: #666;">
              <input type="radio" name="mock_method">
              <span>Debit / Credit Card (Test Mode)</span>
            </label>
          </div>
        </div>

        <button id="confirmPayBtn" onclick="executeMockPayment()" style="width: 100%; background: #00baf2; color: #fff; border: 0; padding: 14px; border-radius: 6px; font-size: 15px; font-weight: 700; cursor: pointer; transition: background 0.2s;">
          Simulate Successful Payment →
        </button>

        <div style="text-align: center; margin-top: 15px; font-size: 11px; color: #888;">
          🔒 256-Bit SSL Encrypted · Test Mode Sandbox Active
        </div>
      </div>

    </div>
  </div>

@endsection

@push('scripts')
<script>
  let activeDonationId = null;

  function toggleCauseSelect(show) {
    document.getElementById('causeWrapper').style.display = show ? 'block' : 'none';
  }

  function setAmount(amt) {
    document.getElementById('customAmountInput').value = amt;
  }

  function setCustomAmount() {
    document.getElementById('customAmountInput').focus();
  }

  function closeRazorpayMock() {
    document.getElementById('razorpayMockModal').style.display = 'none';
  }

  document.getElementById('donationForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const amountVal = document.getElementById('customAmountInput').value;

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

      if (res.success) {
        activeDonationId = res.donation_id;
        document.getElementById('mockAmountDisplay').innerText = "₹" + parseInt(amountVal).toLocaleString('en-IN');
        document.getElementById('razorpayMockModal').style.display = 'flex';
      }
    } catch (err) {
      alert("Error initiating checkout. Please try again.");
    }
  });

  async function executeMockPayment() {
    const payBtn = document.getElementById('confirmPayBtn');
    payBtn.innerText = "Processing Payment...";
    payBtn.disabled = true;

    try {
      const verifyRes = await fetch("{{ url('/api/donations/verify-payment') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
          donation_id: activeDonationId,
          razorpay_payment_id: 'pay_spoofed_' + Date.now(),
          razorpay_signature: 'sig_spoofed_verified',
          donor_name: document.getElementById('donor_name').value
        })
      });

      const vRes = await verifyRes.json();
      if (vRes.success) {
        window.location.href = vRes.receipt_url;
      }
    } catch (err) {
      alert("Payment verification failed.");
      payBtn.innerText = "Simulate Successful Payment →";
      payBtn.disabled = false;
    }
  }
</script>
@endpush
