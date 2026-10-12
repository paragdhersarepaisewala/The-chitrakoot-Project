@extends('layouts.app')

@section('title', 'सदस्य डैशबोर्ड एवं प्रोफाइल | श्री कामदगिरि पीठम् सेवा ट्रस्ट')

@section('content')

  <section class="section" style="padding-top: 150px; background: var(--paper); min-height: 80vh;">
    <div class="container" style="max-width: 1100px;">

      <!-- Header Card -->
      <div style="background: #fff; border: 1px solid var(--line); padding: 35px; border-radius: 8px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.04);">
        <div style="display: flex; align-items: center; gap: 20px;">
          @if($user->profile?->photo_path)
            <img src="{{ asset('storage/' . $user->profile->photo_path) }}" alt="{{ $user->name }}" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 2px solid var(--sand);">
          @else
            <div style="width: 80px; height: 80px; border-radius: 50%; background: var(--brown); color: #fff; font: 700 28px var(--sans); display: grid; place-items: center;">
              {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
          @endif
          <div>
            <span style="display: inline-block; background: var(--sand); color: #fff; padding: 4px 12px; font: 700 9px var(--sans); letter-spacing: .15em; text-transform: uppercase; border-radius: 4px;">
              {{ strtoupper($user->role) }} ACCOUNT
            </span>
            <h1 style="font: 600 28px/1.2 var(--dev); color: var(--deep); margin: 6px 0 2px;">प्रणाम, {{ $user->name }}</h1>
            <p style="font: 500 13px var(--sans); color: rgba(45,36,30,.6); margin: 0;">{{ $user->email }} · {{ $user->phone ?? 'संपर्क नंबर उपलब्ध नहीं' }}</p>
          </div>
        </div>

        <div style="display: flex; gap: 12px;">
          <a href="{{ url('/user/card') }}" class="btn primary" style="font-size: 10px; padding: 12px 20px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><line x1="7" y1="8" x2="17" y2="8"/><line x1="7" y1="12" x2="13" y2="12"/></svg>
            Download ID Card
          </a>
          <form action="{{ url('/logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn dark" style="font-size: 10px; padding: 12px 20px;">Logout</button>
          </form>
        </div>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">

        <!-- Personal Profile & Membership Card Summary -->
        <div style="display: flex; flex-direction: column; gap: 30px;">
          
          <div style="background: #fff; border: 1px solid var(--line); border-radius: 8px; padding: 28px; box-shadow: 0 10px 30px rgba(0,0,0,0.03);">
            <h3 style="font: 600 20px var(--dev); color: var(--brown); margin: 0 0 20px; border-bottom: 1px solid var(--line); padding-bottom: 10px;">
              व्यक्तिगत जानकारी (Profile Details)
            </h3>
            <div style="font: 400 14px/1.8 var(--dev); color: var(--ink);">
              <div style="margin-bottom: 10px;"><strong>लिंग (Gender):</strong> {{ ucfirst($user->profile?->gender ?? 'N/A') }}</div>
              <div style="margin-bottom: 10px;"><strong>जन्म तिथि (DOB):</strong> {{ $user->profile?->date_of_birth ? \Carbon\Carbon::parse($user->profile->date_of_birth)->format('d M Y') : 'N/A' }}</div>
              <div style="margin-bottom: 10px;"><strong>व्यवसाय (Profession):</strong> {{ $user->profile?->occupation ?? 'N/A' }}</div>
              <div style="margin-bottom: 10px;"><strong>जिला (District):</strong> {{ $user->profile?->district ?? 'N/A' }}</div>
              <div><strong>पता (Address):</strong> {{ $user->profile?->address_line1 ?? 'N/A' }}</div>
            </div>
          </div>

          <div style="background: #fff; border: 1px solid var(--line); border-radius: 8px; padding: 28px; box-shadow: 0 10px 30px rgba(0,0,0,0.03);">
            <h3 style="font: 600 20px var(--dev); color: var(--brown); margin: 0 0 15px;">सदस्यता कार्ड स्थिति</h3>
            <p style="font: 600 12px var(--sans); color: rgba(45,36,30,.6); margin-bottom: 15px;">
              ID: {{ $user->membership?->membership_id ?? 'SKP-SUPPORTER' }}
            </p>
            
            <div style="padding: 16px; background: var(--ivory); border-left: 4px solid var(--sand); border-radius: 4px; margin-bottom: 15px;">
              <div style="font: 700 10px var(--sans); letter-spacing: .1em; text-transform: uppercase; color: var(--brown);">Card Type</div>
              <div style="font: 600 18px var(--dev); color: var(--deep); margin-top: 4px;">
                @if(($user->membership?->status ?? '') === 'active')
                  Active Member Digital Card
                @elseif($user->donations->count() > 0)
                  Supporter Digital Card
                @else
                  Member Registration Card
                @endif
              </div>
            </div>

            <a href="{{ url('/user/card') }}" class="btn primary" style="width: 100%; justify-content: center; font-size: 10px; padding: 12px;">
              डिजिटल कार्ड देखें एवं प्रिंट करें →
            </a>
          </div>

        </div>

        <!-- Donation History & Receipts -->
        <div style="background: #fff; border: 1px solid var(--line); border-radius: 8px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.03);">
          <h3 style="font: 600 22px var(--dev); color: var(--brown); margin: 0 0 20px; border-bottom: 1px solid var(--line); padding-bottom: 12px;">
            दान एवं आधिकारिक 80G रसीद इतिहास (Donation Receipts)
          </h3>

          @forelse($user->donations as $donation)
            <div style="border-bottom: 1px solid var(--line); padding: 18px 0; display: flex; justify-content: space-between; align-items: center; gap: 15px; flex-wrap: wrap;">
              <div>
                <strong style="font: 700 18px var(--dev); color: var(--brown); display: block;">₹{{ number_format($donation->amount, 2) }}</strong>
                <span style="font: 500 14px var(--dev); color: var(--ink);">{{ $donation->cause?->title ?? 'सामान्य सेवा (General Seva)' }}</span>
                <div style="font: 500 11px var(--sans); color: rgba(45,36,30,.5); margin-top: 4px;">
                  दिनांक: {{ $donation->created_at->format('d M Y, h:i A') }} · Status: <span style="color: green; font-weight: 700;">{{ strtoupper($donation->status) }}</span>
                </div>
              </div>
              <div>
                @if($donation->receipt_number)
                  <a href="{{ url('/receipt/' . $donation->receipt_number) }}" target="_blank" class="btn dark" style="font-size: 9px; padding: 8px 14px;">
                    View Official Receipt
                  </a>
                @endif
              </div>
            </div>
          @empty
            <div style="text-align: center; padding: 40px 0;">
              <p style="font: 400 15px var(--dev); color: rgba(45,36,30,.6);">आपने अभी तक कोई ऑनलाइन दान नहीं दिया है।</p>
              <a href="{{ url('/donate') }}" class="btn primary" style="margin-top: 15px; font-size: 10px;">पहला दान करें (Donate Now)</a>
            </div>
          @endforelse
        </div>

      </div>

    </div>
  </section>

@endsection
