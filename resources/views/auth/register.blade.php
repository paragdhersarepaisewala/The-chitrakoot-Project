@extends('layouts.app')

@section('title', 'सदस्यता पंजीकरण · Devotee Registration | श्री कामदगिरि पीठम् सेवा ट्रस्ट')
@section('meta_description', 'श्री कामदगिरि पीठम् सेवा परिवार से जुड़ें। गौसेवा, गुरुकुल शिक्षा एवं चित्रकूट तपोभूमि सेवा में सहभागिता हेतु निःशुल्क पंजीकरण करें।')

@section('content')

<style>
  .register-portal-section {
    min-height: 100vh;
    padding-top: 150px;
    padding-bottom: 90px;
    background: linear-gradient(180deg, #FAF4ED 0%, #F5ECE0 100%);
    display: flex;
    align-items: center;
    box-sizing: border-box;
  }
  .register-grid {
    display: grid;
    grid-template-columns: 1fr 1.15fr;
    gap: 48px;
    align-items: center;
    width: 100%;
  }
  @media (max-width: 900px) {
    .register-grid {
      grid-template-columns: 1fr;
      gap: 36px;
    }
  }
  .register-card-glass {
    background: #FFFDFC;
    border: 1.5px solid rgba(180, 83, 9, 0.2);
    border-radius: 20px;
    padding: clamp(28px, 4vw, 40px);
    box-shadow: 0 16px 45px rgba(130, 70, 20, 0.08);
    box-sizing: border-box;
  }
  .reg-input-group {
    margin-bottom: 16px;
  }
  .reg-label {
    display: block;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .06em;
    color: #78350F;
    margin-bottom: 6px;
    font-family: var(--dev);
  }
  .reg-input {
    width: 100%;
    padding: 12px 14px;
    background: #FAF4ED;
    border: 1.5px solid rgba(180, 83, 9, 0.22);
    border-radius: 12px;
    font-size: 14.5px;
    color: #2B1A12;
    box-sizing: border-box;
    transition: all .2s ease;
    font-family: var(--sans, sans-serif);
  }
  .reg-input:focus {
    outline: none;
    border-color: #D95D0F;
    background: #FFFDFC;
    box-shadow: 0 0 0 3px rgba(217, 93, 15, 0.15);
  }
  .btn-reg-submit {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #D95D0F 0%, #B45309 100%);
    color: #FFFDFC;
    border: none;
    border-radius: 9999px;
    font-size: 14px;
    font-weight: 700;
    font-family: var(--dev);
    cursor: pointer;
    box-shadow: 0 10px 24px rgba(217, 93, 15, 0.28);
    transition: all .2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
  }
  .btn-reg-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 14px 28px rgba(217, 93, 15, 0.38);
    background: linear-gradient(135deg, #E86514 0%, #C25B0E 100%);
  }
  .btn-reg-submit:active {
    transform: scale(0.98);
  }
  .stepper-capsule {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 14px;
    border-radius: 9999px;
    background: #FAF4ED;
    border: 1px solid rgba(180, 83, 9, 0.2);
  }
  .stepper-capsule.active {
    background: rgba(180, 83, 9, 0.12);
    border-color: #B45309;
  }

  @media (max-width: 900px) {
    .register-portal-section {
      padding-top: 96px;
      padding-bottom: 60px;
      padding-left: 14px;
      padding-right: 14px;
      align-items: flex-start;
    }
    .register-grid {
      grid-template-columns: 1fr;
      gap: 16px;
    }
    .register-trust-col {
      text-align: center;
      margin-bottom: 6px;
    }
    .register-trust-col h1 {
      font-size: clamp(22px, 5.5vw, 28px) !important;
      margin: 0 0 10px !important;
    }
    .register-trust-col p {
      font-size: 13px !important;
      margin-bottom: 12px !important;
      line-height: 1.6 !important;
    }
    .register-trust-highlights {
      display: flex !important;
      flex-direction: row !important;
      flex-wrap: wrap !important;
      justify-content: center !important;
      gap: 8px !important;
      margin-bottom: 14px !important;
    }
    .register-trust-badge-pill {
      font-size: 11.5px !important;
      padding: 6px 12px !important;
      background: #FFFDF9 !important;
      border: 1px solid rgba(180, 83, 9, 0.22) !important;
      border-radius: 9999px !important;
      color: #78350F !important;
      font-family: var(--dev);
      font-weight: 700;
    }
    .register-card-glass {
      padding: 22px 16px;
      border-radius: 18px;
      box-shadow: 0 12px 32px rgba(130, 70, 20, 0.09);
    }
    .stepper-capsule {
      flex: 1;
      justify-content: center;
      padding: 7px 4px;
    }
    .stepper-capsule span {
      font-size: 10.5px !important;
      white-space: nowrap;
    }
    .reg-input {
      font-size: 16px; /* Prevents auto-zoom on iOS */
      padding: 13px 14px;
    }
  }
</style>

<section class="register-portal-section">
  <div class="container">
    <div class="register-grid">

      <!-- Left Column: Trust Benefits & Vision -->
      <div class="register-trust-col" style="color: #2B1A12;">
        <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(180,83,9,0.12); border: 1px solid rgba(180,83,9,0.25); padding: 5px 14px; border-radius: 9999px; margin-bottom: 14px;">
          <span style="font-size: 11px; font-weight: 800; letter-spacing: .16em; text-transform: uppercase; color: #B45309;">JOIN THE SACRED SANKALP</span>
        </div>

        <h1 style="font-size: clamp(26px, 3.8vw, 46px); font-weight: 700; font-family: var(--dev); line-height: 1.25; margin: 0 0 16px; color: #2B1A12;">
          सनातन संस्कृति एवं तपोभूमि सेवा का पावन संकल्प
        </h1>

        <p style="font-size: 15px; line-height: 1.8; color: rgba(43,26,18,0.85); margin-bottom: 24px; font-family: var(--dev);">
          श्री कामदगिरि पीठम् सेवा ट्रस्ट से जुड़कर गौसेवा, गुरुकुल शिक्षा, मंदाकिनी स्वच्छता और समाज कल्याण के प्रयासों में अपनी सहभागिता दर्ज करें।
        </p>

        <div class="register-trust-highlights" style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 26px;">
          <div class="register-trust-badge-pill" style="display: flex; align-items: center; gap: 12px; font-size: 14px; color: #2B1A12; font-family: var(--dev);">
            <div style="width: 26px; height: 26px; border-radius: 50%; background: rgba(180,83,9,0.15); display: grid; place-items: center; flex-shrink: 0;">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#B45309" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <span>गौवंश संरक्षण, नित्य अन्नक्षेत्र एवं चिकित्सा सेवा</span>
          </div>
          <div class="register-trust-badge-pill" style="display: flex; align-items: center; gap: 12px; font-size: 14px; color: #2B1A12; font-family: var(--dev);">
            <div style="width: 26px; height: 26px; border-radius: 50%; background: rgba(180,83,9,0.15); display: grid; place-items: center; flex-shrink: 0;">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#B45309" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <span>गुरुकुल वैदिक शिक्षा, संस्कृत संवर्धन एवं संस्कार शाला</span>
          </div>
          <div class="register-trust-badge-pill" style="display: flex; align-items: center; gap: 12px; font-size: 14px; color: #2B1A12; font-family: var(--dev);">
            <div style="width: 26px; height: 26px; border-radius: 50%; background: rgba(180,83,9,0.15); display: grid; place-items: center; flex-shrink: 0;">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#B45309" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <span>{{ ($orgSettings->is_80g_enabled ?? false) ? '80G आयकर छूट प्राप्त प्रमाणित डिजिटल दान रसीदें' : 'प्रमाणित डिजिटल दान पावती एवं रसीदें' }}</span>
          </div>
        </div>
      </div>

      <!-- Right Column: Registration Card -->
      <div class="register-card-glass">

        <!-- Stepper Indicator -->
        <div style="display: flex; gap: 10px; margin-bottom: 24px; justify-content: space-between; border-bottom: 1px solid rgba(180,83,9,0.18); padding-bottom: 16px;">
          <div class="stepper-capsule {{ $step == 1 ? 'active' : '' }}">
            <span style="font-size: 11.5px; font-weight: 800; color: {{ $step == 1 ? '#B45309' : '#888' }}; font-family: var(--dev);">1. विवरण</span>
          </div>
          <div class="stepper-capsule {{ $step == 2 ? 'active' : '' }}">
            <span style="font-size: 11.5px; font-weight: 800; color: {{ $step == 2 ? '#B45309' : '#888' }}; font-family: var(--dev);">2. सत्यापन (OTP)</span>
          </div>
          <div class="stepper-capsule {{ $step == 3 ? 'active' : '' }}">
            <span style="font-size: 11.5px; font-weight: 800; color: {{ $step == 3 ? '#B45309' : '#888' }}; font-family: var(--dev);">3. प्रोफाइल</span>
          </div>
        </div>

        @if(session('success'))
          <div style="background: rgba(46, 125, 50, 0.12); border: 1.5px solid #2e7d32; color: #1b5e20; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 13px; font-weight: 600;">
            {{ session('success') }}
          </div>
        @endif

        @if($errors->any())
          <div style="background: rgba(198, 40, 40, 0.1); border: 1.5px solid #c62828; color: #b71c1c; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 13px; font-weight: 600;">
            {{ $errors->first() }}
          </div>
        @endif

        <!-- STEP 1: Basic Account Registration -->
        @if($step == 1)
          <div style="margin-bottom: 20px;">
            <h2 style="font-size: 22px; font-weight: 700; font-family: var(--dev); color: #2B1A12; margin: 0 0 6px;">
              भक्त खाता पंजीकरण
            </h2>
            <p style="font-size: 13.5px; color: rgba(43,26,18,0.7); margin: 0; font-family: var(--dev);">
              कृपया बुनियादी विवरण भरकर अपना खाता बनाएं
            </p>
          </div>

          <form action="{{ url('/register/step-1') }}" method="POST">
            @csrf

            <div class="reg-input-group">
              <label class="reg-label">पूरा नाम (Full Name)</label>
              <input type="text" name="name" value="{{ old('name') }}" required placeholder="उदा. आदित्य शर्मा" class="reg-input">
            </div>

            <div class="reg-input-group">
              <label class="reg-label">ईमेल पता (Email Address)</label>
              <input type="email" name="email" value="{{ old('email') }}" required placeholder="aditya.sharma@example.com" class="reg-input">
            </div>

            <div class="reg-input-group">
              <label class="reg-label">जन्म तिथि (Date of Birth) — <span style="color: #B45309; font-weight: 800;">१८+ वर्ष अनिवार्य (18+ Years Required)</span></label>
              <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" max="{{ now()->subYears(18)->format('Y-m-d') }}" required class="reg-input">
              <span style="font-size: 11px; color: #7A4B29; margin-top: 4px; display: block;">पंजीकरण केवल १८ वर्ष या उससे अधिक आयु के भक्तों के लिए उपलब्ध है। (Must be 18+ to register).</span>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 22px;">
              <div>
                <label class="reg-label">पासवर्ड</label>
                <input type="password" name="password" required placeholder="न्यूनतम 8 अक्षर" class="reg-input">
              </div>
              <div>
                <label class="reg-label">पुष्टि करें</label>
                <input type="password" name="password_confirmation" required placeholder="पासवर्ड पुनः दर्ज करें" class="reg-input">
              </div>
            </div>

            <button type="submit" class="btn-reg-submit" style="white-space: nowrap;">
              <span>Create Account &rarr;</span>
            </button>
          </form>

          <div style="text-align: center; margin-top: 20px; font-size: 13.5px; color: rgba(43,26,18,0.75);">
            Already have an account? 
            <a href="{{ url('/login') }}" style="color: #B45309; font-weight: 700; text-decoration: none; margin-left: 4px;">
              Login &rarr;
            </a>
          </div>
        @endif

        <!-- STEP 2: Email OTP Verification -->
        @if($step == 2)
          <div style="margin-bottom: 20px;">
            <h2 style="font-size: 22px; font-weight: 700; font-family: var(--dev); color: #2B1A12; margin: 0 0 6px;">
              ईमेल सत्यापन (Verify OTP)
            </h2>
            <p style="font-size: 13.5px; color: rgba(43,26,18,0.7); margin: 0; font-family: var(--dev);">
              आपके ईमेल <strong>{{ $email ?? auth()->user()?->email }}</strong> पर 6 अंकों का कोड भेजा गया है
            </p>
          </div>

          <form action="{{ url('/register/verify-otp') }}" method="POST">
            @csrf

            <div style="margin-bottom: 24px;">
              <label class="reg-label" style="text-align: center; display: block;">6-DIGIT SECURITY OTP</label>
              <input type="text" name="otp" id="otpField" required maxlength="6" placeholder="••••••" autocomplete="one-time-code" style="width: 100%; padding: 14px; background: #FAF4ED; border: 1.5px solid #D95D0F; color: #D95D0F; font-size: 26px; font-weight: 800; letter-spacing: .4em; text-align: center; border-radius: 12px; box-sizing: border-box;">
            </div>

            <button type="submit" class="btn-reg-submit" style="margin-bottom: 6px; white-space: nowrap;">
              <span>Verify OTP &rarr;</span>
            </button>
          </form>

          <!-- Resend OTP Section with Cooldown Timer -->
          <div style="margin-top: 18px; padding-top: 16px; border-top: 1px dashed rgba(180,83,9,0.22); text-align: center;">
            <p style="font-size: 13px; color: rgba(43,26,18,0.7); margin: 0 0 8px;">
              कोड प्राप्त नहीं हुआ? (Didn't receive the OTP code?)
            </p>
            <form action="{{ route('register.resend_otp') }}" method="POST" id="resendOtpForm" style="display: inline-block;">
              @csrf
              <input type="hidden" name="email" value="{{ $email ?? auth()->user()?->email }}">
              <button type="submit" id="resendOtpBtn" style="background: rgba(180,83,9,0.08); border: 1px solid rgba(180,83,9,0.25); color: #B45309; font-weight: 700; font-size: 12.5px; border-radius: 9999px; padding: 7px 18px; cursor: pointer; transition: all .2s; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 4 23 10 17 10"></polyline><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path></svg>
                <span>Resend OTP Code</span>
              </button>
            </form>
            <div id="resendCooldownText" style="display: none; font-size: 12px; color: #9A3412; font-weight: 600; margin-top: 8px;">
              कृपया पुनः अनुरोध से पूर्व प्रतीक्षा करें: <span id="cooldownTimer" style="font-weight: 800; color: #B45309;">30</span> सेकंड
            </div>
          </div>

          <script>
            document.addEventListener('DOMContentLoaded', function() {
              const resendBtn = document.getElementById('resendOtpBtn');
              const resendForm = document.getElementById('resendOtpForm');
              const cooldownText = document.getElementById('resendCooldownText');
              const cooldownTimer = document.getElementById('cooldownTimer');

              if (resendForm && resendBtn && cooldownText) {
                const lastSent = sessionStorage.getItem('otp_resend_timestamp');
                const now = Math.floor(Date.now() / 1000);
                if (lastSent && (now - parseInt(lastSent, 10)) < 30) {
                  startCooldown(30 - (now - parseInt(lastSent, 10)));
                }

                resendForm.addEventListener('submit', function() {
                  sessionStorage.setItem('otp_resend_timestamp', Math.floor(Date.now() / 1000).toString());
                });

                function startCooldown(seconds) {
                  resendBtn.disabled = true;
                  resendBtn.style.opacity = '0.5';
                  resendBtn.style.cursor = 'not-allowed';
                  cooldownText.style.display = 'block';
                  cooldownTimer.innerText = seconds;

                  const interval = setInterval(function() {
                    seconds--;
                    if (seconds <= 0) {
                      clearInterval(interval);
                      resendBtn.disabled = false;
                      resendBtn.style.opacity = '1';
                      resendBtn.style.cursor = 'pointer';
                      cooldownText.style.display = 'none';
                      sessionStorage.removeItem('otp_resend_timestamp');
                    } else {
                      cooldownTimer.innerText = seconds;
                    }
                  }, 1000);
                }
              }
            });
          </script>
        @endif

        <!-- STEP 3: Profile Details -->
        @if($step == 3)
          <div style="margin-bottom: 20px;">
            <h2 style="font-size: 22px; font-weight: 700; font-family: var(--dev); color: #2B1A12; margin: 0 0 6px;">
              प्रोफाइल विवरण (Profile Details)
            </h2>
            <p style="font-size: 13.5px; color: rgba(43,26,18,0.7); margin: 0; font-family: var(--dev);">
              ऐच्छिक विवरण — आप इसे बाद में भी भर सकते हैं
            </p>
          </div>

          <form action="{{ url('/register/complete-profile') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
              <div>
                <label class="reg-label">मोबाइल नंबर</label>
                <input type="text" name="phone" placeholder="+91 98765 43210" class="reg-input">
              </div>
              <div>
                <label class="reg-label">शहर / नगर</label>
                <input type="text" name="city" placeholder="उदा. चित्रकूट" class="reg-input">
              </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 20px;">
              <div>
                <label class="reg-label">जिला (District)</label>
                <input type="text" name="district" placeholder="उदा. सतना / चित्रकूट" class="reg-input">
              </div>
              <div>
                <label class="reg-label">राज्य (State)</label>
                <input type="text" name="state" placeholder="उदा. उत्तर प्रदेश" class="reg-input">
              </div>
            </div>

            <button type="submit" class="btn-reg-submit" style="margin-bottom: 12px; white-space: nowrap;">
              <span>Complete Profile &rarr;</span>
            </button>

            <div style="text-align: center;">
              <a href="{{ url('/dashboard') }}" style="color: #78350F; font-size: 12.5px; text-decoration: underline;">
                Skip for Now &rarr;
              </a>
            </div>
          </form>
        @endif

      </div>

    </div>
  </div>
</section>

@endsection
