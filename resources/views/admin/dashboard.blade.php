@extends('layouts.app')

@section('title', 'CMS Super Admin Portal | Shri Kamadgiri Peetham')

@section('content')

  <section class="section" style="padding-top: 140px; background: #1a1411; color: #fff; min-height: 90vh;">
    <div class="container">

      <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--darkline); padding-bottom: 20px; margin-bottom: 30px;">
        <div>
          <span style="color: var(--sand2); font: 700 11px var(--sans); letter-spacing: .2em; text-transform: uppercase;">SUPER ADMIN CMS CONTROL CENTER</span>
          <h1 style="font: 500 32px var(--dev); margin: 5px 0 0;">ट्रस्ट प्रबंध पोर्टल (CMS Admin Control)</h1>
        </div>
        <div style="display: flex; gap: 10px;">
          <a href="{{ url('/admin/settings') }}" class="btn" style="padding: 10px 16px; font-size: 9px;">Settings & API Credentials</a>
          <a href="{{ url('/dashboard') }}" class="btn primary" style="padding: 10px 16px; font-size: 9px;">My Account</a>
        </div>
      </div>

      <!-- Quick CMS Navigation Grid -->
      <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 35px;">
        <a href="{{ url('/admin/blogs') }}" style="background: rgba(185,133,80,.15); border: 1px solid var(--sand); padding: 20px; border-radius: 6px; display: block; color: #fff;">
          <h4 style="margin: 0; font-size: 16px; color: var(--sand2);">📝 Blog & Articles CMS</h4>
          <p style="margin: 6px 0 0; font-size: 12px; color: rgba(255,255,255,.7);">Publish articles, manage comments & likes</p>
        </a>

        <a href="{{ url('/admin/causes') }}" style="background: rgba(185,133,80,.15); border: 1px solid var(--sand); padding: 20px; border-radius: 6px; display: block; color: #fff;">
          <h4 style="margin: 0; font-size: 16px; color: var(--sand2);">❤️ Donation Causes CMS</h4>
          <p style="margin: 6px 0 0; font-size: 12px; color: rgba(255,255,255,.7);">Create & manage specific donation funds</p>
        </a>

        <a href="{{ url('/admin/gallery') }}" style="background: rgba(185,133,80,.15); border: 1px solid var(--sand); padding: 20px; border-radius: 6px; display: block; color: #fff;">
          <h4 style="margin: 0; font-size: 16px; color: var(--sand2);">🖼️ Magazine Gallery CMS</h4>
          <p style="margin: 6px 0 0; font-size: 12px; color: rgba(255,255,255,.7);">Upload heritage & seva photos</p>
        </a>

        <a href="{{ url('/admin/sevas') }}" style="background: rgba(185,133,80,.15); border: 1px solid var(--sand); padding: 20px; border-radius: 6px; display: block; color: #fff;">
          <h4 style="margin: 0; font-size: 16px; color: var(--sand2);">🛠️ Seva Projects CMS</h4>
          <p style="margin: 6px 0 0; font-size: 12px; color: rgba(255,255,255,.7);">Manage Gaushala, Gurukul & Events</p>
        </a>

        <a href="{{ url('/admin/destinations') }}" style="background: rgba(185,133,80,.15); border: 1px solid var(--sand); padding: 20px; border-radius: 6px; display: block; color: #fff;">
          <h4 style="margin: 0; font-size: 16px; color: var(--sand2);">🚩 Sacred Places CMS</h4>
          <p style="margin: 6px 0 0; font-size: 12px; color: rgba(255,255,255,.7);">Manage Kamadgiri, Ramghat, etc.</p>
        </a>

        <a href="{{ url('/admin/users') }}" style="background: rgba(185,133,80,.15); border: 1px solid var(--sand); padding: 20px; border-radius: 6px; display: block; color: #fff;">
          <h4 style="margin: 0; font-size: 16px; color: var(--sand2);">👥 User & Role Permissions</h4>
          <p style="margin: 6px 0 0; font-size: 12px; color: rgba(255,255,255,.7);">Assign Super Admin & Staff roles</p>
        </a>
      </div>

      <!-- Stats Grid -->
      <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 40px;">
        <div style="background: rgba(255,255,255,.04); border: 1px solid var(--darkline); padding: 25px;">
          <span style="font: 700 9px var(--sans); letter-spacing: .15em; text-transform: uppercase; color: var(--sand2);">Total Verified Donations</span>
          <b style="display: block; font: 600 30px var(--serif); color: #fff; margin-top: 8px;">₹{{ number_format($totalDonations, 2) }}</b>
        </div>
        <div style="background: rgba(255,255,255,.04); border: 1px solid var(--darkline); padding: 25px;">
          <span style="font: 700 9px var(--sans); letter-spacing: .15em; text-transform: uppercase; color: var(--sand2);">Verified Transactions</span>
          <b style="display: block; font: 600 30px var(--serif); color: #fff; margin-top: 8px;">{{ $donationsCount }}</b>
        </div>
        <div style="background: rgba(255,255,255,.04); border: 1px solid var(--darkline); padding: 25px;">
          <span style="font: 700 9px var(--sans); letter-spacing: .15em; text-transform: uppercase; color: var(--sand2);">Registered Members</span>
          <b style="display: block; font: 600 30px var(--serif); color: #fff; margin-top: 8px;">{{ $usersCount }}</b>
        </div>
        <div style="background: rgba(255,255,255,.04); border: 1px solid var(--darkline); padding: 25px;">
          <span style="font: 700 9px var(--sans); letter-spacing: .15em; text-transform: uppercase; color: var(--sand2);">CMS Content Entities</span>
          <b style="display: block; font: 600 30px var(--serif); color: #fff; margin-top: 8px;">{{ $sevasCount + $destinationsCount }}</b>
        </div>
      </div>

      <!-- Recent Transactions & Audit Log Grid -->
      <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 30px;">

        <div style="background: rgba(255,255,255,.03); border: 1px solid var(--darkline); padding: 30px;">
          <h3 style="font: 600 20px var(--dev); color: var(--sand2); margin-bottom: 20px;">Recent Verified Donations</h3>

          <table style="width: 100%; border-collapse: collapse; font: 400 13px var(--dev);">
            <thead>
              <tr style="border-bottom: 1px solid var(--darkline); text-align: left; color: rgba(255,255,255,.5); font-size: 10px; font-family: var(--sans);">
                <th style="padding: 10px;">RECEIPT NO</th>
                <th style="padding: 10px;">DONOR NAME</th>
                <th style="padding: 10px;">AMOUNT</th>
                <th style="padding: 10px;">STATUS</th>
              </tr>
            </thead>
            <tbody>
              @forelse($recentDonations as $d)
                <tr style="border-bottom: 1px solid rgba(255,255,255,.05);">
                  <td style="padding: 12px 10px; font-family: var(--sans);">{{ $d->receipt_number ?? 'Pending' }}</td>
                  <td style="padding: 12px 10px;">{{ $d->user?->name ?? 'Guest Supporter' }}</td>
                  <td style="padding: 12px 10px; font-weight: 700; color: var(--sand2);">₹{{ number_format($d->amount, 2) }}</td>
                  <td style="padding: 12px 10px; color: #81c784; font-family: var(--sans);">{{ ucfirst($d->status) }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" style="padding: 20px 10px; color: rgba(255,255,255,.4);">No recent transactions recorded.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div style="background: rgba(255,255,255,.03); border: 1px solid var(--darkline); padding: 30px;">
          <h3 style="font: 600 20px var(--dev); color: var(--sand2); margin-bottom: 20px;">Audit Activity Stream</h3>

          <div style="font: 400 12px var(--sans);">
            @forelse($activityLogs as $log)
              <div style="border-bottom: 1px solid rgba(255,255,255,.05); padding: 10px 0;">
                <div style="color: #fff; font-weight: 600;">[{{ $log->module }}] {{ $log->action }}</div>
                <div style="color: rgba(255,255,255,.6); font-size: 11px;">{{ $log->description }}</div>
                <div style="color: rgba(255,255,255,.4); font-size: 9px; margin-top: 2px;">{{ $log->created_at->diffForHumans() }} · {{ $log->user_name }}</div>
              </div>
            @empty
              <p style="color: rgba(255,255,255,.4);">No audit log entries recorded yet.</p>
            @endforelse
          </div>
        </div>

      </div>

    </div>
  </section>

@endsection
