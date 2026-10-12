@extends('layouts.app')

@section('title', 'संगठन एवं भुगतान सेटिंग्स | CMS Admin')

@section('content')

  <section class="section" style="padding-top: 140px; background: #1a1411; color: #fff; min-height: 90vh;">
    <div class="container" style="max-width: 800px;">

      <div style="margin-bottom: 30px;">
        <a href="{{ url('/admin') }}" style="color: var(--sand2); font: 600 10px var(--sans); letter-spacing: .15em; text-transform: uppercase;">← Return to Admin Dashboard</a>
        <h1 style="font: 500 32px var(--dev); margin-top: 10px; color: #fff;">संगठन, Razorpay एवं SMTP सेटिंग्स</h1>
      </div>

      @if(session('success'))
        <div style="background: rgba(46,125,50,.2); color: #81c784; padding: 15px; margin-bottom: 25px; border-left: 4px solid #81c784; font: 500 14px var(--dev);">
          {{ session('success') }}
        </div>
      @endif

      <form action="{{ url('/admin/settings') }}" method="POST" style="background: rgba(255,255,255,.03); border: 1px solid var(--darkline); padding: 35px; border-radius: 8px;">
        @csrf

        <h3 style="font: 500 20px var(--dev); color: var(--sand2); margin-bottom: 20px; border-bottom: 1px solid var(--darkline); padding-bottom: 10px;">१. संगठन विवरण (Organization Info)</h3>

        <div style="margin-bottom: 20px;">
          <label style="display: block; font: 600 10px var(--sans); letter-spacing: .1em; margin-bottom: 8px;">संस्था का नाम (Organization Name)</label>
          <input type="text" name="org_name" value="{{ old('org_name', $settings->org_name) }}" style="width: 100%; padding: 12px; background: #261e19; border: 1px solid var(--darkline); color: #fff; font: 500 14px var(--dev);">
        </div>

        <div style="margin-bottom: 20px;">
          <label style="display: block; font: 600 10px var(--sans); letter-spacing: .1em; margin-bottom: 8px;">कार्यालय का पता (Office Address)</label>
          <textarea name="address" rows="3" style="width: 100%; padding: 12px; background: #261e19; border: 1px solid var(--darkline); color: #fff; font: 500 14px var(--dev);">{{ old('address', $settings->address) }}</textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
          <div>
            <label style="display: block; font: 600 10px var(--sans); letter-spacing: .1em; margin-bottom: 8px;">पंजीकरण संख्या (Registration No / 80G)</label>
            <input type="text" name="reg_number" value="{{ old('reg_number', $settings->reg_number) }}" style="width: 100%; padding: 12px; background: #261e19; border: 1px solid var(--darkline); color: #fff; font: 500 14px var(--dev);">
          </div>
          <div>
            <label style="display: block; font: 600 10px var(--sans); letter-spacing: .1em; margin-bottom: 8px;">संपर्क फोन (Phone Number)</label>
            <input type="text" name="phone" value="{{ old('phone', $settings->phone) }}" style="width: 100%; padding: 12px; background: #261e19; border: 1px solid var(--darkline); color: #fff; font: 500 14px var(--dev);">
          </div>
        </div>

        <h3 style="font: 500 20px var(--dev); color: var(--sand2); margin: 30px 0 20px; border-bottom: 1px solid var(--darkline); padding-bottom: 10px;">२. Razorpay गेटवे कुंजियाँ (Razorpay Credentials)</h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
          <div>
            <label style="display: block; font: 600 10px var(--sans); letter-spacing: .1em; margin-bottom: 8px;">Razorpay Key ID</label>
            <input type="text" name="razorpay_key_id" value="{{ old('razorpay_key_id', $settings->razorpay_key_id ?? 'rzp_test_mock_key_id') }}" placeholder="rzp_test_xxxx" style="width: 100%; padding: 12px; background: #261e19; border: 1px solid var(--darkline); color: #fff; font: 500 13px var(--sans);">
          </div>
          <div>
            <label style="display: block; font: 600 10px var(--sans); letter-spacing: .1em; margin-bottom: 8px;">Razorpay Key Secret</label>
            <input type="password" name="razorpay_key_secret" value="{{ old('razorpay_key_secret', $settings->razorpay_key_secret ?? 'mock_secret_key_123') }}" placeholder="••••••••••••" style="width: 100%; padding: 12px; background: #261e19; border: 1px solid var(--darkline); color: #fff; font: 500 13px var(--sans);">
          </div>
        </div>

        <h3 style="font: 500 20px var(--dev); color: var(--sand2); margin: 30px 0 20px; border-bottom: 1px solid var(--darkline); padding-bottom: 10px;">३. SMTP ई-मेल कॉन्फ़िगरेशन (SMTP Email Settings)</h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
          <div>
            <label style="display: block; font: 600 10px var(--sans); letter-spacing: .1em; margin-bottom: 8px;">SMTP Host Server</label>
            <input type="text" name="smtp_host" value="{{ old('smtp_host', $settings->smtp_host ?? 'sandbox.smtp.mailtrap.io') }}" placeholder="e.g. smtp.mailtrap.io" style="width: 100%; padding: 12px; background: #261e19; border: 1px solid var(--darkline); color: #fff; font: 500 13px var(--sans);">
          </div>
          <div>
            <label style="display: block; font: 600 10px var(--sans); letter-spacing: .1em; margin-bottom: 8px;">SMTP Port</label>
            <input type="number" name="smtp_port" value="{{ old('smtp_port', $settings->smtp_port ?? 2525) }}" placeholder="e.g. 587 or 2525" style="width: 100%; padding: 12px; background: #261e19; border: 1px solid var(--darkline); color: #fff; font: 500 13px var(--sans);">
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
          <div>
            <label style="display: block; font: 600 10px var(--sans); letter-spacing: .1em; margin-bottom: 8px;">SMTP Username</label>
            <input type="text" name="smtp_username" value="{{ old('smtp_username', $settings->smtp_username ?? 'test_smtp_user') }}" placeholder="SMTP Username" style="width: 100%; padding: 12px; background: #261e19; border: 1px solid var(--darkline); color: #fff; font: 500 13px var(--sans);">
          </div>
          <div>
            <label style="display: block; font: 600 10px var(--sans); letter-spacing: .1em; margin-bottom: 8px;">SMTP Password</label>
            <input type="password" name="smtp_password" value="{{ old('smtp_password', $settings->smtp_password ?? 'test_smtp_pass') }}" placeholder="SMTP Password" style="width: 100%; padding: 12px; background: #261e19; border: 1px solid var(--darkline); color: #fff; font: 500 13px var(--sans);">
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
          <div>
            <label style="display: block; font: 600 10px var(--sans); letter-spacing: .1em; margin-bottom: 8px;">From Email Address</label>
            <input type="email" name="smtp_from_address" value="{{ old('smtp_from_address', $settings->smtp_from_address ?? 'noreply@kamadgiripeetham.org') }}" placeholder="noreply@kamadgiripeetham.org" style="width: 100%; padding: 12px; background: #261e19; border: 1px solid var(--darkline); color: #fff; font: 500 13px var(--sans);">
          </div>
          <div>
            <label style="display: block; font: 600 10px var(--sans); letter-spacing: .1em; margin-bottom: 8px;">From Sender Name</label>
            <input type="text" name="smtp_from_name" value="{{ old('smtp_from_name', $settings->smtp_from_name ?? 'Shri Kamadgiri Peetham Sewa Trust') }}" placeholder="Shri Kamadgiri Peetham Sewa Trust" style="width: 100%; padding: 12px; background: #261e19; border: 1px solid var(--darkline); color: #fff; font: 500 13px var(--dev);">
          </div>
        </div>

        <button type="submit" class="btn primary" style="width: 100%; justify-content: center; font-size: 10px; padding: 14px; margin-top: 20px;">
          सेटिंग्स सुरक्षित रूप से सहेजें (Save Settings)
        </button>
      </form>

    </div>
  </section>

@endsection
