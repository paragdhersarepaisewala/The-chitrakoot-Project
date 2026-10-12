@extends('layouts.app')

@section('title', 'सदस्य लॉगिन | श्री कामदगिरि पीठम् सेवा ट्रस्ट')

@section('content')

  <section class="section" style="padding-top: 150px; background: var(--paper); min-height: 80vh;">
    <div class="container" style="max-width: 480px;">

      <div style="background: #fff; border: 1px solid var(--line); padding: 40px; box-shadow: 0 15px 45px rgba(0,0,0,.06);">

        <div style="text-align: center; margin-bottom: 30px;">
          <div class="eyebrow" style="justify-content: center;">Member Login</div>
          <h1 style="font: 500 32px var(--dev); color: var(--brown); margin-top: 10px;">सदस्य लॉगिन</h1>
        </div>

        @if($errors->any())
          <div style="background: #ffebee; color: #c62828; padding: 12px; margin-bottom: 20px; font: 500 13px var(--dev); border-left: 3px solid #c62828;">
            {{ $errors->first() }}
          </div>
        @endif

        <form action="{{ url('/login') }}" method="POST">
          @csrf

          <div style="margin-bottom: 20px;">
            <label style="display: block; font: 600 10px var(--sans); letter-spacing: .1em; text-transform: uppercase; margin-bottom: 8px;">ईमेल (Email Address)</label>
            <input type="email" name="email" value="{{ old('email') }}" required style="width: 100%; padding: 12px; border: 1px solid var(--line); font: 500 14px var(--dev);">
          </div>

          <div style="margin-bottom: 20px;">
            <label style="display: block; font: 600 10px var(--sans); letter-spacing: .1em; text-transform: uppercase; margin-bottom: 8px;">पासवर्ड (Password)</label>
            <input type="password" name="password" required style="width: 100%; padding: 12px; border: 1px solid var(--line); font: 500 14px var(--dev);">
          </div>

          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <label style="display: flex; align-items: center; gap: 8px; font: 500 12px var(--sans); cursor: pointer;">
              <input type="checkbox" name="remember"> Remember Me
            </label>
          </div>

          <button type="submit" class="btn primary" style="width: 100%; justify-content: center; font-size: 10px; padding: 14px;">
            लॉगिन करें (Login)
          </button>
        </form>

        <div style="text-align: center; margin-top: 25px; font: 500 12px var(--dev); color: rgba(45,36,30,.7);">
          खाता नहीं है? <a href="{{ url('/register') }}" style="color: var(--brown); font-weight: 700;">सदस्यता पंजीकरण करें (Register)</a>
        </div>

      </div>

    </div>
  </section>

@endsection
