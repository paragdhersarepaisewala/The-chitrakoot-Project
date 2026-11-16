@extends('layouts.admin')

@section('title', 'CMS Admin Dashboard | Shri Kamadgiri Portal')
@section('header_title', 'Real-Time Analytics & CMS Overview')
@section('header_subtitle', 'Live In-Dashboard Traffic Statistics & Trust Management Operations')

@section('styles')
<style>
  .dash-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 28px;
  }
  @media (max-width: 1200px) {
    .dash-stats-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }
  @media (max-width: 640px) {
    .dash-stats-grid {
      grid-template-columns: 1fr;
    }
  }
  .stat-card {
    background: #ffffff;
    border: 1px solid var(--line);
    border-radius: var(--radius);
    padding: 22px 24px;
    box-shadow: 0 4px 18px rgba(0,0,0,0.02);
    position: relative;
    overflow: hidden;
  }
  .stat-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--sand), var(--brown));
  }
  .stat-card.crimson::before { background: linear-gradient(90deg, var(--crimson), #b71c1c); }
  .stat-card.teal::before { background: linear-gradient(90deg, var(--forest), #004d40); }
  .stat-card.gold::before { background: linear-gradient(90deg, var(--gold), #ff8f00); }

  .stat-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: rgba(43, 35, 29, 0.65);
    display: block;
    margin-bottom: 6px;
  }
  .stat-val {
    font-size: 28px;
    font-weight: 800;
    font-family: var(--sans);
    color: var(--deep);
    line-height: 1.1;
  }
  .stat-sub {
    font-size: 11.5px;
    color: rgba(43, 35, 29, 0.55);
    margin-top: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .quick-cms-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 28px;
  }
  @media (max-width: 1024px) {
    .quick-cms-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }
  @media (max-width: 600px) {
    .quick-cms-grid {
      grid-template-columns: 1fr;
    }
  }
  .quick-cms-card {
    background: #ffffff;
    border: 1px solid var(--line);
    border-radius: var(--radius);
    padding: 16px 18px;
    display: flex;
    align-items: center;
    gap: 14px;
    transition: all .2s var(--ease);
    box-shadow: 0 2px 10px rgba(0,0,0,0.02);
  }
  .quick-cms-card:hover {
    border-color: var(--sand);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(185, 133, 80, 0.12);
  }
  .quick-cms-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: #faf8f5;
    border: 1px solid var(--line);
    display: grid;
    place-items: center;
    color: var(--brown);
    flex-shrink: 0;
  }
  .quick-cms-card:hover .quick-cms-icon {
    background: var(--sand);
    color: #fff;
    border-color: var(--sand);
  }
  .quick-cms-title {
    font-size: 13.5px;
    font-weight: 700;
    color: var(--deep);
  }
  .quick-cms-desc {
    font-size: 11px;
    color: rgba(43, 35, 29, 0.6);
    margin-top: 2px;
  }

  .content-split-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 24px;
  }
  @media (max-width: 1024px) {
    .content-split-grid {
      grid-template-columns: 1fr;
    }
  }
  .table-box {
    background: #ffffff;
    border: 1px solid var(--line);
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: 0 4px 18px rgba(0,0,0,0.02);
  }
  .table-head-bar {
    padding: 16px 22px;
    border-bottom: 1px solid var(--line);
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #faf8f5;
  }
  .table-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--deep);
  }
  .data-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12.5px;
  }
  .data-table th {
    text-align: left;
    padding: 10px 18px;
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: rgba(43, 35, 29, 0.55);
    border-bottom: 1px solid var(--line);
    background: #fff;
  }
  .data-table td {
    padding: 12px 18px;
    border-bottom: 1px solid rgba(89, 58, 40, 0.08);
    color: var(--ink);
  }
  .data-table tr:last-child td {
    border-bottom: none;
  }
  .data-table tr:hover td {
    background: #faf8f5;
  }
  .status-pill {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 9999px;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
  }
  .status-pill.verified { background: #e8f5e9; color: #1b5e20; }
  .status-pill.pending { background: #fff8e1; color: #b78103; }
</style>
@endsection

@section('content')

  <!-- Top Metrics Overview -->
  <div class="dash-stats-grid">
    <div class="stat-card">
      <span class="stat-label">Total Verified Donations</span>
      <div class="stat-val">₹{{ number_format($totalDonations, 2) }}</div>
      <div class="stat-sub">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
        <span>{{ $donationsCount }} verified transactions</span>
      </div>
    </div>

    <div class="stat-card teal">
      <span class="stat-label">Articles & Stories Published</span>
      <div class="stat-val">{{ $publishedPostsCount }}</div>
      <div class="stat-sub">
        <span>{{ $draftPostsCount }} drafts &middot; {{ number_format($totalViews) }} views</span>
      </div>
    </div>

    <div class="stat-card gold">
      <span class="stat-label">Registered Members</span>
      <div class="stat-val">{{ $usersCount }}</div>
      <div class="stat-sub">
        <span>Active Trust supporters & devotees</span>
      </div>
    </div>

    <div class="stat-card crimson">
      <span class="stat-label">Active Causes & Projects</span>
      <div class="stat-val">{{ $sevasCount + $destinationsCount }}</div>
      <div class="stat-sub">
        <span>{{ $sevasCount }} Seva &middot; {{ $destinationsCount }} Sacred Places</span>
      </div>
    </div>
  </div>

  <!-- Quick CMS Jump Navigation Grid -->
  <div class="quick-cms-grid">
    <a href="{{ route('admin.blogs.create') }}" class="quick-cms-card">
      <div class="quick-cms-icon">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
      </div>
      <div>
        <div class="quick-cms-title">Write New Article</div>
        <div class="quick-cms-desc">Shortcodes & widgets</div>
      </div>
    </a>

    <a href="{{ route('admin.homepageLayout') }}" class="quick-cms-card">
      <div class="quick-cms-icon">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
      </div>
      <div>
        <div class="quick-cms-title">Homepage Layout</div>
        <div class="quick-cms-desc">Video hero & textures</div>
      </div>
    </a>

    <a href="{{ route('admin.mediaGallery') }}" class="quick-cms-card">
      <div class="quick-cms-icon">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
      </div>
      <div>
        <div class="quick-cms-title">Media Gallery</div>
        <div class="quick-cms-desc">WebP upload & library</div>
      </div>
    </a>

    <a href="{{ route('admin.settings') }}" class="quick-cms-card">
      <div class="quick-cms-icon">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
      </div>
      <div>
        <div class="quick-cms-title">Site Settings & SEO</div>
        <div class="quick-cms-desc">Logo, Favicon & Razorpay</div>
      </div>
    </a>
  </div>

  <!-- Content Split: Recent Donations & Audit Activity -->
  <div class="content-split-grid">
    
    <!-- Left: Recent Verified Donations -->
    <div class="table-box">
      <div class="table-head-bar">
        <span class="table-title">Recent Verified Donations</span>
        <a href="{{ route('admin.causes') }}" style="font-size: 11.5px; font-weight: 700; color: var(--sand);">View All Causes &rarr;</a>
      </div>

      <div style="overflow-x: auto;">
        <table class="data-table">
          <thead>
            <tr>
              <th>Receipt No</th>
              <th>Donor</th>
              <th>Amount</th>
              <th>Cause</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentDonations as $d)
              <tr>
                <td style="font-family: monospace; font-weight: 600;">{{ $d->receipt_number ?? 'Pending' }}</td>
                <td>{{ $d->user?->name ?? 'Guest Supporter' }}</td>
                <td style="font-weight: 700; color: #1b5e20;">₹{{ number_format($d->amount, 2) }}</td>
                <td style="color: rgba(43, 35, 29, 0.7);">{{ $d->cause?->title ?? 'General Seva' }}</td>
                <td>
                  <span class="status-pill {{ $d->status === 'verified' ? 'verified' : 'pending' }}">
                    {{ ucfirst($d->status) }}
                  </span>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" style="text-align: center; padding: 30px; color: #888;">No recent donations recorded.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Right: Audit Activity Log Stream -->
    <div class="table-box">
      <div class="table-head-bar">
        <span class="table-title">Audit Activity Stream</span>
        <span style="font-size: 11px; color: #888;">Real-Time</span>
      </div>

      <div style="padding: 16px 20px; max-height: 480px; overflow-y: auto;">
        @forelse($activityLogs as $log)
          <div style="padding: 10px 0; border-bottom: 1px solid rgba(89, 58, 40, 0.08);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
              <strong style="font-size: 12px; color: var(--deep);">[{{ $log->module }}] {{ $log->action }}</strong>
              <span style="font-size: 10px; color: #888;">{{ $log->created_at->diffForHumans() }}</span>
            </div>
            <div style="font-size: 11.5px; color: rgba(43, 35, 29, 0.75); margin-top: 3px;">{{ $log->description }}</div>
            <div style="font-size: 10px; color: var(--sand); margin-top: 2px;">by {{ $log->user_name }}</div>
          </div>
        @empty
          <div style="text-align: center; padding: 30px; color: #888;">No activity logged yet.</div>
        @endforelse
      </div>
    </div>

  </div>

@endsection