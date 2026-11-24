@extends('layouts.app')

@section('title', 'नया पासवर्ड निर्धारित करें · Reset Password | श्री कामदगिरि पीठम् सेवा ट्रस्ट')
@section('meta_description', 'श्री कामदगिरि पीठम् सेवा ट्रस्ट भक्त पोर्टल पासवर्ड रीसेट।')

@section('content')

<style>
  .auth-portal-section {
    min-height: 100vh;
    padding-top: 150px;
    padding-bottom: 90px;
    background: linear-gradient(180deg, #FAF4ED 0%, #F5ECE0 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    box-sizing: border-box;
  }
  .auth-card-glass {
    background: #FFFDFC;
    border: 1.5px solid rgba(180, 83, 9, 0.2);
    border-radius: 20px;
    padding: clamp(30px, 5vw, 44px);
    width: 100%;
    max-width: 480px;
    box-shadow: 0 16px 45px rgba(130, 70, 20, 0.08);
    position: relative;
    z-index: 2;
    box-sizing: border-box;
  }
  .auth-input-group {
    margin-bottom: 20px;
  }
  .auth-label {
    display: block;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .06em;
    color: #78350F;
    margin-bottom: 8px;
    font-family: var(--dev);
  }
  .auth-input {
    width: 100%;
    padding: 13px 16px;
    background: #FAF4ED;
    border: 1.5px solid rgba(180, 83, 9, 0.22);
    border-radius: 12px;
    font-size: 14.5px;
    color: #2B1A12;
    box-sizing: border-box;
    transition: all .2s ease;
    font-family: var(--sans, sans-serif);
  }
  .auth-input:focus {
    outline: none;
    border-color: #D95D0F;
    background: #FFFDFC;
    box-shadow: 0 0 0 3px rgba(217, 93, 15, 0.15);
  }
  .btn-auth-submit {
    width: 100%;
    padding: 14px 24px;
    background: linear-gradient(135deg, #8f1719 0%, #b98550 100%);
    color: #ffffff;
    border: none;
    border-radius: 9999px;
    font-size: 13.5px;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    cursor: pointer;
    box-shadow: 0 8px 24px rgba(143, 23, 25, 0.22);
    transition: all .2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-family: var(--sans, sans-serif);
  }
  .btn-auth-submit:hover {
    box-shadow: 0 10px 30px rgba(143, 23, 25, 0.32);
    transform: translateY(-1px);
  }
  .btn-auth-submit:active {
    transform: scale(0.98);
  }

  @media (max-width: 640px) {
    .auth-portal-section {
      padding-top: 96px;
      padding-bottom: 60px;
      padding-left: 14px;
      padding-right: 14px;
      min-height: calc(100vh - 60px);
      align-items: flex-start;
    }
    .auth-card-glass {
      padding: 24px 18px;
      border-radius: 18px;
      box-shadow: 0 12px 32px rgba(130, 70, 20, 0.09);
    }
    .auth-input {
      font-size: 16px;
      padding: 13px 14px;
    }
  }
</style>

<section class="auth-portal-section">
  <div class="auth-card-glass">
    
    <div style="text-align: center; margin-bottom: 30px;">
      <div style="font-size: 11px; letter-spacing: 2px; text-transform: uppercase; color: #B45309; font-weight: 800; margin-bottom: 6px;">
        सत्यापन एवं नवीनीकरण · RESET CREDENTIALS
      </div>
      <h1 style="font-family: var(--dev); font-size: 24px; font-weight: 700; color: #2B1A12; margin: 0 0 8px;">
        नया पासवर्ड निर्धारित करें
      </h1>
      <p style="font-size: 13px; color: rgba(43,26,18,0.7); margin: 0; font-family: var(--dev);">
        ईमेल पर प्राप्त 6-अंकीय कोड प्रविष्ट कर नया सुरक्षित पासवर्ड बनाएं।
      </p>
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

    <form action="{{ route('password.update') }}" method="POST">
      @csrf

      <div class="auth-input-group">
        <label class="auth-label">पंजीकृत ईमेल आईडी (Email Address) *</label>
        <input type="email" name="email" value="{{ old('email', $email ?? '') }}" required placeholder="your.email@example.com" class="auth-input">
      </div>

      <div class="auth-input-group">
        <label class="auth-label">6-अंकीय सुरक्षा कोड (Verification OTP Code) *</label>
        <input type="text" name="otp" required maxlength="6" placeholder="••••••" autocomplete="one-time-code" class="auth-input" style="font-family: monospace; font-size: 20px; letter-spacing: 6px; text-align: center;">
      </div>

      <div class="auth-input-group">
        <label class="auth-label">नया पासवर्ड (New Password - Min 8 chars) *</label>
        <input type="password" name="password" required placeholder="••••••••" class="auth-input">
      </div>

      <div class="auth-input-group">
        <label class="auth-label">पासवर्ड की पुष्टि करें (Confirm Password) *</label>
        <input type="password" name="password_confirmation" required placeholder="••••••••" class="auth-input">
      </div>

      <button type="submit" class="btn-auth-submit">
        <span>पासवर्ड सहेजें · Save New Password</span>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
      </button>
    </form>

    <div style="margin-top: 26px; padding-top: 20px; border-top: 1px solid rgba(180,83,9,0.18); text-align: center; font-size: 13.5px; color: rgba(43,26,18,0.75);">
      <div style="margin-bottom: 12px;">
        <span>कोड नहीं मिला? (Didn't receive code?)</span>
        <a href="{{ route('password.request') }}{{ !empty($email) ? '?email='.urlencode($email) : '' }}" style="color: #B45309; font-weight: 700; text-decoration: underline; margin-left: 4px;">
          Resend Reset Code &rarr;
        </a>
      </div>
      <div>
        <a href="{{ url('/login') }}" style="color: #7A4B29; font-weight: 600; text-decoration: none;">
          &larr; Back to Login
        </a>
      </div>
    </div>

  </div>
</section>

@endsection
