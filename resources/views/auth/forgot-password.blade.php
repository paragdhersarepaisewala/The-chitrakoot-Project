@extends('layouts.app')

@section('title', 'पासवर्ड पुनर्प्राप्ति · Forgot Password | श्री कामदगिरि पीठम् सेवा ट्रस्ट')
@section('meta_description', 'श्री कामदगिरि पीठम् सेवा ट्रस्ट भक्त पोर्टल पासवर्ड पुनर्प्राप्ति। अपने पंजीकृत ईमेल पर सुरक्षा कोड प्राप्त करें।')

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
        खाता सुरक्षा · ACCOUNT RECOVERY
      </div>
      <h1 style="font-family: var(--dev); font-size: 24px; font-weight: 700; color: #2B1A12; margin: 0 0 8px;">
        पासवर्ड पुनर्प्राप्ति
      </h1>
      <p style="font-size: 13px; color: rgba(43,26,18,0.7); margin: 0; font-family: var(--dev);">
        अपना पंजीकृत ईमेल प्रविष्ट करें। हम आपको 6-अंकीय सुरक्षा कोड भेजेंगे।
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

    <form action="{{ route('password.email') }}" method="POST">
      @csrf

      <div class="auth-input-group">
        <label class="auth-label">पंजीकृत ईमेल आईडी (Registered Email) *</label>
        <input type="email" name="email" value="{{ old('email') }}" required placeholder="your.email@example.com" class="auth-input">
      </div>

      <button type="submit" class="btn-auth-submit">
        <span>सुरक्षा कोड प्राप्त करें · Send Reset Code</span>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
      </button>
    </form>

    <div style="margin-top: 26px; padding-top: 20px; border-top: 1px solid rgba(180,83,9,0.18); text-align: center; font-size: 13.5px; color: rgba(43,26,18,0.75); font-family: var(--dev);">
      पासवर्ड याद आ गया? 
      <a href="{{ url('/login') }}" style="color: #B45309; font-weight: 700; text-decoration: none; margin-left: 4px;">
        लॉगिन करें (Sign In) &rarr;
      </a>
    </div>

  </div>
</section>

@endsection
