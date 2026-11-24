@extends('layouts.app')

@section('title', 'सदस्य प्रवेश · Devotee Login | श्री कामदगिरि पीठम् सेवा ट्रस्ट')
@section('meta_description', 'श्री कामदगिरि पीठम् सेवा ट्रस्ट के सदस्य एवं भक्त पोर्टल में प्रवेश करें। ऑनलाइन पूजा, गौसेवा दान एवं चित्रकूट तीर्थ यात्रा विवरण का अवलोकन करें।')

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
  .btn-auth-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 14px 28px rgba(217, 93, 15, 0.38);
    background: linear-gradient(135deg, #E86514 0%, #C25B0E 100%);
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
      font-size: 16px; /* Prevents auto-zoom on iOS */
      padding: 13px 14px;
    }
    .auth-header-emblem {
      width: 46px !important;
      height: 46px !important;
      margin-bottom: 8px !important;
    }
    .auth-title {
      font-size: 22px !important;
    }
    .auth-subtitle {
      font-size: 12.5px !important;
    }
  }
</style>

<section class="auth-portal-section">
  <div class="auth-card-glass">

    <!-- Temple Logo / Emblem Header -->
    <div style="text-align: center; margin-bottom: 24px;">
      <div class="auth-header-emblem" style="width: 54px; height: 54px; margin: 0 auto 12px; border-radius: 50%; background: linear-gradient(135deg, #D95D0F, #B45309); display: grid; place-items: center; box-shadow: 0 6px 20px rgba(217,93,15,0.3); transition: all .2s ease;">
        <svg width="24" height="24" viewBox="0 0 50 58" fill="none" stroke="#fff" stroke-width="2.5">
          <path d="M5 51V28L25 7l20 21v23"/>
          <path d="M13 51V31l12-12 12 12v20"/>
          <path d="M20 51V35h10v16"/>
          <path d="M2 52h46"/>
          <path d="M25 7V2"/>
        </svg>
      </div>
      <span style="font-size: 10.5px; font-weight: 800; letter-spacing: .2em; text-transform: uppercase; color: #B45309; display: block; margin-bottom: 2px;">
        DEVOTEE & MEMBER ACCESS
      </span>
      <h1 class="auth-title" style="font-size: 26px; font-weight: 700; font-family: var(--dev); color: #2B1A12; margin: 4px 0 2px;">
        भक्त व सदस्य प्रवेश
      </h1>
      <p class="auth-subtitle" style="font-size: 13.5px; color: rgba(43,26,18,0.7); margin: 0; font-family: var(--dev);">
        श्री कामदगिरि पीठम् सेवा ट्रस्ट · चित्रकूट धाम
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

    <form action="{{ url('/login') }}" method="POST">
      @csrf

      <div class="auth-input-group">
        <label class="auth-label">ईमेल पता (Email Address)</label>
        <input type="email" name="email" id="loginEmail" value="{{ old('email') }}" required placeholder="your.email@example.com" class="auth-input">
      </div>

      <div class="auth-input-group">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
          <label class="auth-label" style="margin-bottom: 0;">पासवर्ड (Password)</label>
          <a href="{{ route('password.request') }}" style="color: #B45309; text-decoration: none; font-size: 12px; font-weight: 700;">Forgot Password?</a>
        </div>
        <input type="password" name="password" id="loginPassword" required placeholder="••••••••" class="auth-input">
      </div>

      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; font-size: 13px; color: rgba(43,26,18,0.8);">
        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
          <input type="checkbox" name="remember" style="accent-color: #D95D0F; width: 16px; height: 16px;">
          <span>Remember me</span>
        </label>
      </div>

      <button type="submit" class="btn-auth-submit" style="white-space: nowrap;">
        <span>Sign In &rarr;</span>
      </button>
    </form>

    <div style="margin-top: 26px; padding-top: 20px; border-top: 1px solid rgba(180,83,9,0.18); text-align: center; font-size: 13.5px; color: rgba(43,26,18,0.75);">
      Don't have an account? 
      <a href="{{ url('/register') }}" style="color: #B45309; font-weight: 700; text-decoration: none; margin-left: 4px;">
        Register &rarr;
      </a>
    </div>

  </div>
</section>

@endsection
