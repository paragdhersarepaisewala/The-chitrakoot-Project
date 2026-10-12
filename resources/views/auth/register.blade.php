@extends('layouts.app')

@section('title', 'Become a Member — Step-by-Step Registration | Shri Kamadgiri Peetham')

@section('content')

  <!-- Full Width Background Registration Section -->
  <section class="section" style="padding-top: 150px; padding-bottom: 100px; background: linear-gradient(90deg, rgba(20, 14, 10, 0.94) 0%, rgba(20, 14, 10, 0.82) 45%, rgba(20, 14, 10, 0.94) 100%), url('https://images.unsplash.com/photo-1548013146-72479768bada?auto=format&fit=crop&w=2400&q=90') center/cover; min-height: 92vh; display: flex; align-items: center;">
    
    <div class="container">
      <div style="display: grid; grid-template-columns: 1fr 1.15fr; gap: 60px; align-items: center;">

        <!-- Left Column: Vision & Trust Message -->
        <div class="reveal">
          <div class="eyebrow" style="color: var(--sand2);">JOIN THE MISSION</div>
          <h1 style="font: 600 clamp(36px, 4.5vw, 58px)/1.12 var(--dev); color: #ffffff; margin: 16px 0 20px;">
            सनातन संस्कृति एवं तपोभूमि सेवा का पावन संकल्प
          </h1>

          <p style="font: 400 17px/1.85 var(--dev); color: rgba(255,255,255,.85); margin-bottom: 30px;">
            श्री कामदगिरि पीठम् सेवा ट्रस्ट से जुड़कर गौसेवा, गुरुकुल शिक्षा, मन्दाकिनी स्वच्छता और समाज कल्याण के प्रयासों में अपनी सहभागिता दर्ज करें।
          </p>

          <div style="display: flex; flex-direction: column; gap: 16px; font: 400 15px/1.7 var(--dev); color: rgba(255,255,255,.9);">
            <div style="display: flex; align-items: center; gap: 14px;">
              <div style="width: 36px; height: 36px; border: 1px solid var(--sand2); border-radius: 50%; display: grid; place-items: center; color: var(--sand2); flex-shrink: 0;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
              </div>
              <span>गौवंश संरक्षण एवं निःशुल्क अन्नक्षेत्र सेवा</span>
            </div>

            <div style="display: flex; align-items: center; gap: 14px;">
              <div style="width: 36px; height: 36px; border: 1px solid var(--sand2); border-radius: 50%; display: grid; place-items: center; color: var(--sand2); flex-shrink: 0;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
              </div>
              <span>संस्कृत विद्यालय एवं वैदिक संस्कारों का संवर्धन</span>
            </div>

            <div style="display: flex; align-items: center; gap: 14px;">
              <div style="width: 36px; height: 36px; border: 1px solid var(--sand2); border-radius: 50%; display: grid; place-items: center; color: var(--sand2); flex-shrink: 0;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
              </div>
              <span>डिजिटल रसीद एवं आधिकारिक सदस्यता / समर्थक कार्ड</span>
            </div>
          </div>
        </div>

        <!-- Right Column: Step-by-Step Registration Wizard Panel -->
        <div class="reveal delay-1" style="background: rgba(25, 19, 15, 0.95); border: 1px solid rgba(255,255,255,.16); padding: 40px; border-radius: 12px; box-shadow: 0 25px 60px rgba(0,0,0,.5); backdrop-filter: blur(20px);">

          <!-- Step Progress Indicator -->
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 1px solid rgba(255,255,255,.1); padding-bottom: 18px;">
            <div style="display: flex; align-items: center; gap: 8px; font: 700 11px var(--sans); letter-spacing: .1em; text-transform: uppercase;">
              <span style="width: 26px; height: 26px; border-radius: 50%; background: {{ $step == 1 ? 'var(--sand)' : 'rgba(255,255,255,.15)' }}; color: #fff; display: grid; place-items: center;">1</span>
              <span style="color: {{ $step == 1 ? 'var(--sand2)' : 'rgba(255,255,255,.5)' }};">ACCOUNT</span>
            </div>

            <div style="display: flex; align-items: center; gap: 8px; font: 700 11px var(--sans); letter-spacing: .1em; text-transform: uppercase;">
              <span style="width: 26px; height: 26px; border-radius: 50%; background: {{ $step == 2 ? 'var(--sand)' : 'rgba(255,255,255,.15)' }}; color: #fff; display: grid; place-items: center;">2</span>
              <span style="color: {{ $step == 2 ? 'var(--sand2)' : 'rgba(255,255,255,.5)' }};">OTP VERIFY</span>
            </div>

            <div style="display: flex; align-items: center; gap: 8px; font: 700 11px var(--sans); letter-spacing: .1em; text-transform: uppercase;">
              <span style="width: 26px; height: 26px; border-radius: 50%; background: {{ $step == 3 ? 'var(--sand)' : 'rgba(255,255,255,.15)' }}; color: #fff; display: grid; place-items: center;">3</span>
              <span style="color: {{ $step == 3 ? 'var(--sand2)' : 'rgba(255,255,255,.5)' }};">PROFILE</span>
            </div>
          </div>

          @if(session('success'))
            <div style="background: rgba(46,125,50,.25); color: #a5d6a7; padding: 12px 16px; margin-bottom: 20px; font: 500 13px var(--dev); border-left: 3px solid #81c784; border-radius: 4px;">
              {{ session('success') }}
            </div>
          @endif

          @if($errors->any())
            <div style="background: rgba(198,40,40,.25); color: #ef9a9a; padding: 12px 16px; margin-bottom: 20px; font: 500 13px var(--sans); border-left: 3px solid #ef5350; border-radius: 4px;">
              {{ $errors->first() }}
            </div>
          @endif

          <!-- STEP 1: Basic Account Registration -->
          @if($step == 1)
            <div style="margin-bottom: 20px;">
              <h2 style="font: 600 24px var(--dev); color: #fff; margin: 0 0 6px;">सदस्यता पंजीकरण (Create Account)</h2>
              <p style="font: 400 13px var(--sans); color: rgba(255,255,255,.6); margin: 0;">Step 1 of 3 — Basic Registration Details</p>
            </div>

            <form action="{{ url('/register/step-1') }}" method="POST">
              @csrf

              <div style="margin-bottom: 16px;">
                <label style="display: block; font: 700 11px var(--sans); letter-spacing: .1em; text-transform: uppercase; margin-bottom: 8px; color: var(--sand2);">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Enter your full name" style="width: 100%; padding: 12px; background: rgba(0,0,0,.4); border: 1px solid rgba(255,255,255,.2); color: #fff; font: 500 14px var(--dev); border-radius: 4px;">
              </div>

              <div style="margin-bottom: 16px;">
                <label style="display: block; font: 700 11px var(--sans); letter-spacing: .1em; text-transform: uppercase; margin-bottom: 8px; color: var(--sand2);">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="your.email@example.com" style="width: 100%; padding: 12px; background: rgba(0,0,0,.4); border: 1px solid rgba(255,255,255,.2); color: #fff; font: 500 14px var(--sans); border-radius: 4px;">
              </div>

              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 24px;">
                <div>
                  <label style="display: block; font: 700 11px var(--sans); letter-spacing: .1em; text-transform: uppercase; margin-bottom: 8px; color: var(--sand2);">Password</label>
                  <input type="password" name="password" required placeholder="Min 8 characters" style="width: 100%; padding: 12px; background: rgba(0,0,0,.4); border: 1px solid rgba(255,255,255,.2); color: #fff; font: 500 14px var(--sans); border-radius: 4px;">
                </div>
                <div>
                  <label style="display: block; font: 700 11px var(--sans); letter-spacing: .1em; text-transform: uppercase; margin-bottom: 8px; color: var(--sand2);">Confirm Password</label>
                  <input type="password" name="password_confirmation" required placeholder="Repeat password" style="width: 100%; padding: 12px; background: rgba(0,0,0,.4); border: 1px solid rgba(255,255,255,.2); color: #fff; font: 500 14px var(--sans); border-radius: 4px;">
                </div>
              </div>

              <button type="submit" class="btn primary" style="width: 100%; font-size: 11px; padding: 14px;">
                Proceed to Email Verification →
              </button>
            </form>

            <div style="text-align: center; margin-top: 20px; font: 500 13px var(--sans); color: rgba(255,255,255,.6);">
              Already have an account? <a href="{{ url('/login') }}" style="color: var(--sand2); font-weight: 700;">Login Here</a>
            </div>
          @endif

          <!-- STEP 2: Email OTP Verification -->
          @if($step == 2)
            <div style="margin-bottom: 20px;">
              <h2 style="font: 600 24px var(--dev); color: #fff; margin: 0 0 6px;">ईमेल सत्यापन (Verify OTP)</h2>
              <p style="font: 400 13px var(--sans); color: rgba(255,255,255,.7); margin: 0;">
                Enter the 6-digit OTP code sent to <strong>{{ $email ?? auth()->user()?->email }}</strong>
              </p>
            </div>

            <form action="{{ url('/register/verify-otp') }}" method="POST">
              @csrf

              <div style="margin-bottom: 24px;">
                <label style="display: block; font: 700 11px var(--sans); letter-spacing: .15em; text-transform: uppercase; margin-bottom: 8px; color: var(--sand2);">6-Digit OTP Code</label>
                <input type="text" name="otp" required maxlength="6" placeholder="849201" style="width: 100%; padding: 14px; background: rgba(0,0,0,.5); border: 1px solid var(--sand); color: var(--sand2); font: 700 24px var(--sans); letter-spacing: .4em; text-align: center; border-radius: 6px;">
              </div>

              <button type="submit" class="btn primary" style="width: 100%; font-size: 11px; padding: 14px;">
                Verify OTP & Continue →
              </button>
            </form>
          @endif

          <!-- STEP 3: Profile Completion (DOB, Gender, Address, Photo) -->
          @if($step == 3)
            <div style="margin-bottom: 20px;">
              <h2 style="font: 600 24px var(--dev); color: #fff; margin: 0 0 6px;">प्रोफाइल विवरण (Complete Profile)</h2>
              <p style="font: 400 13px var(--sans); color: rgba(255,255,255,.7); margin: 0;">
                Step 3 of 3 — Fill in your profile details & upload your photo for digital ID card.
              </p>
            </div>

            <form action="{{ url('/register/complete-profile') }}" method="POST" enctype="multipart/form-data">
              @csrf

              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                <div>
                  <label style="display: block; font: 700 10px var(--sans); letter-spacing: .1em; text-transform: uppercase; margin-bottom: 6px; color: var(--sand2);">Mobile Phone</label>
                  <input type="text" name="phone" placeholder="+91 98765 43210" style="width: 100%; padding: 10px; background: rgba(0,0,0,.4); border: 1px solid rgba(255,255,255,.2); color: #fff; font: 500 13px var(--sans); border-radius: 4px;">
                </div>
                <div>
                  <label style="display: block; font: 700 10px var(--sans); letter-spacing: .1em; text-transform: uppercase; margin-bottom: 6px; color: var(--sand2);">Date of Birth</label>
                  <input type="date" name="date_of_birth" style="width: 100%; padding: 10px; background: rgba(0,0,0,.4); border: 1px solid rgba(255,255,255,.2); color: #fff; font: 500 13px var(--sans); border-radius: 4px;">
                </div>
              </div>

              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                <div>
                  <label style="display: block; font: 700 10px var(--sans); letter-spacing: .1em; text-transform: uppercase; margin-bottom: 6px; color: var(--sand2);">Gender</label>
                  <select name="gender" style="width: 100%; padding: 10px; background: #231b15; border: 1px solid rgba(255,255,255,.2); color: #fff; font: 500 13px var(--sans); border-radius: 4px;">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                  </select>
                </div>
                <div>
                  <label style="display: block; font: 700 10px var(--sans); letter-spacing: .1em; text-transform: uppercase; margin-bottom: 6px; color: var(--sand2);">Profession / Occupation</label>
                  <input type="text" name="occupation" placeholder="e.g. Teacher, Business" style="width: 100%; padding: 10px; background: rgba(0,0,0,.4); border: 1px solid rgba(255,255,255,.2); color: #fff; font: 500 13px var(--dev); border-radius: 4px;">
                </div>
              </div>

              <div style="margin-bottom: 14px;">
                <label style="display: block; font: 700 10px var(--sans); letter-spacing: .1em; text-transform: uppercase; margin-bottom: 6px; color: var(--sand2);">Address</label>
                <textarea name="address" rows="2" placeholder="Full residential address" style="width: 100%; padding: 10px; background: rgba(0,0,0,.4); border: 1px solid rgba(255,255,255,.2); color: #fff; font: 500 13px var(--dev); border-radius: 4px;"></textarea>
              </div>

              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                <div>
                  <label style="display: block; font: 700 10px var(--sans); letter-spacing: .1em; text-transform: uppercase; margin-bottom: 6px; color: var(--sand2);">City / District</label>
                  <input type="text" name="district" placeholder="Chitrakoot / Satna" style="width: 100%; padding: 10px; background: rgba(0,0,0,.4); border: 1px solid rgba(255,255,255,.2); color: #fff; font: 500 13px var(--sans); border-radius: 4px;">
                </div>
                <div>
                  <label style="display: block; font: 700 10px var(--sans); letter-spacing: .1em; text-transform: uppercase; margin-bottom: 6px; color: var(--sand2);">State & Pincode</label>
                  <input type="text" name="state" placeholder="Madhya Pradesh / Uttar Pradesh" style="width: 100%; padding: 10px; background: rgba(0,0,0,.4); border: 1px solid rgba(255,255,255,.2); color: #fff; font: 500 13px var(--sans); border-radius: 4px;">
                </div>
              </div>

              <div style="margin-bottom: 24px;">
                <label style="display: block; font: 700 10px var(--sans); letter-spacing: .1em; text-transform: uppercase; margin-bottom: 6px; color: var(--sand2);">Profile Photo (For ID Card)</label>
                <input type="file" name="profile_photo" accept="image/*" style="width: 100%; padding: 8px; background: rgba(0,0,0,.4); border: 1px solid rgba(255,255,255,.2); color: rgba(255,255,255,.8); font: 500 12px var(--sans); border-radius: 4px;">
              </div>

              <button type="submit" class="btn primary" style="width: 100%; font-size: 11px; padding: 14px;">
                Complete Registration & Go to Dashboard →
              </button>
            </form>
          @endif

        </div>

      </div>
    </div>
  </section>

@endsection
