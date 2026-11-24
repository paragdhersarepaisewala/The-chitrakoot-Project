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
      grid-template-columns: repeat(2, 1fr) !important;
      gap: 10px !important;
      margin-bottom: 18px !important;
    }
    .stat-card {
      padding: 14px 12px !important;
      border-radius: 12px !important;
    }
    .stat-label {
      font-size: 9.5px !important;
      letter-spacing: .04em !important;
      margin-bottom: 4px !important;
    }
    .stat-val {
      font-size: 20px !important;
      line-height: 1.15 !important;
    }
    .stat-sub {
      font-size: 10px !important;
      margin-top: 4px !important;
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
      grid-template-columns: repeat(2, 1fr) !important;
      gap: 10px !important;
      margin-bottom: 20px !important;
    }
    .quick-cms-card {
      padding: 12px 10px !important;
      border-radius: 12px !important;
      flex-direction: column !important;
      align-items: flex-start !important;
      gap: 8px !important;
    }
    .quick-cms-icon {
      width: 34px !important;
      height: 34px !important;
      border-radius: 8px !important;
    }
    .quick-cms-title {
      font-size: 12px !important;
    }
    .quick-cms-desc {
      font-size: 10px !important;
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
        <span>{{ $donationsCount }} verified contributions</span>
      </div>
    </div>

    <div class="stat-card gold">
      <span class="stat-label">Pooja & Hawan Bookings</span>
      <div class="stat-val">{{ $poojaBookingsCount ?? 0 }}</div>
      <div class="stat-sub">
        <span style="color: #b45309; font-weight: 700;">{{ $poojaPendingCount ?? 0 }} pending action</span>
      </div>
    </div>

    <div class="stat-card teal">
      <span class="stat-label">Atithi Vishram (Stay)</span>
      <div class="stat-val">{{ $atithiCount ?? 0 }}</div>
      <div class="stat-sub">
        <span style="color: #047857; font-weight: 700;">{{ $atithiNewCount ?? 0 }} new stay enquiries</span>
      </div>
    </div>

    <div class="stat-card crimson">
      <span class="stat-label">Temple Hall Bookings</span>
      <div class="stat-val">{{ $templeBookingsCount ?? 0 }}</div>
      <div class="stat-sub">
        <span style="color: #b91c1c; font-weight: 700;">{{ $templeBookingsNewCount ?? 0 }} new event enquiries</span>
      </div>
    </div>
  </div>

  <!-- Quick CMS Jump Navigation Grid -->
  <div class="quick-cms-grid">
    <a href="{{ route('admin.atithiVishram') }}" class="quick-cms-card">
      <div class="quick-cms-icon" style="color: #047857;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18"/><path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"/><path d="M9 7h1"/><path d="M9 11h1"/><path d="M9 15h1"/><path d="M14 7h1"/><path d="M14 11h1"/><path d="M14 15h1"/></svg>
      </div>
      <div>
        <div class="quick-cms-title">Atithi Vishram</div>
        <div class="quick-cms-desc">{{ $atithiNewCount ?? 0 }} pending enquiries</div>
      </div>
    </a>

    <a href="{{ route('admin.templeBookings') }}" class="quick-cms-card">
      <div class="quick-cms-icon" style="color: #8F1719;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
      </div>
      <div>
        <div class="quick-cms-title">Temple Hall Bookings</div>
        <div class="quick-cms-desc">{{ $templeBookingsNewCount ?? 0 }} pending events</div>
      </div>
    </a>

    <a href="{{ route('admin.poojaBookings') }}" class="quick-cms-card">
      <div class="quick-cms-icon" style="color: #D95D0F;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
      </div>
      <div>
        <div class="quick-cms-title">Pooja Approvals</div>
        <div class="quick-cms-desc">Dakshina & quotations</div>
      </div>
    </a>

    <a href="{{ route('admin.settings') }}" class="quick-cms-card">
      <div class="quick-cms-icon">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
      </div>
      <div>
        <div class="quick-cms-title">Site Settings & SEO</div>
        <div class="quick-cms-desc">Logo, Favicon & Gateway</div>
      </div>
    </a>
  </div>

  <!-- Temple Services, Bookings & Enquiries Central Hub -->
  <div class="table-box" style="margin-bottom: 28px;">
    <div class="table-head-bar" style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
      <div>
        <span class="table-title" style="font-size: 15px; font-weight: 800; color: var(--deep); display: flex; align-items: center; gap: 8px;">
          <x-icon name="flame" size="16" /> All Temple Services, Bookings & Stay Enquiries Hub
        </span>
        <div style="font-size: 11px; color: rgba(43, 35, 29, 0.6); margin-top: 2px;">
          Live dashboard monitoring incoming orders, ritual bookings, accommodation requests, and hall proposals
        </div>
      </div>

      <!-- Hub Tabs -->
      <div style="display: inline-flex; gap: 6px; background: #eee; padding: 4px; border-radius: 9999px;">
        <button type="button" id="hub-tab-pooja" onclick="switchHubTab('pooja')" style="padding: 6px 14px; border-radius: 9999px; font-size: 11px; font-weight: 700; border: none; cursor: pointer; background: #D95D0F; color: #fff; transition: all 0.2s;">
          Pooja Bookings ({{ $poojaBookingsCount ?? 0 }})
        </button>
        <button type="button" id="hub-tab-atithi" onclick="switchHubTab('atithi')" style="padding: 6px 14px; border-radius: 9999px; font-size: 11px; font-weight: 700; border: none; cursor: pointer; background: transparent; color: var(--ink); transition: all 0.2s;">
          Atithi Vishram ({{ $atithiCount ?? 0 }})
        </button>
        <button type="button" id="hub-tab-temple" onclick="switchHubTab('temple')" style="padding: 6px 14px; border-radius: 9999px; font-size: 11px; font-weight: 700; border: none; cursor: pointer; background: transparent; color: var(--ink); transition: all 0.2s;">
          Hall Bookings ({{ $templeBookingsCount ?? 0 }})
        </button>
        <button type="button" id="hub-tab-donations" onclick="switchHubTab('donations')" style="padding: 6px 14px; border-radius: 9999px; font-size: 11px; font-weight: 700; border: none; cursor: pointer; background: transparent; color: var(--ink); transition: all 0.2s;">
          Seva Donations ({{ $donationsCount ?? 0 }})
        </button>
      </div>
    </div>

    <!-- Panel 1: Pooja Bookings -->
    <div id="hub-panel-pooja" style="overflow-x: auto; display: block;">
      <table class="data-table">
        <thead>
          <tr>
            <th>Booking #</th>
            <th>Devotee Name</th>
            <th>Ritual / Pooja</th>
            <th>Date & Time</th>
            <th>Dakshina / Status</th>
            <th style="text-align: right;">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentPoojaBookings as $pb)
            <tr>
              <td style="font-family: monospace; font-weight: 700; color: #D95D0F;">#{{ $pb->booking_number }}</td>
              <td>
                <strong>{{ $pb->devotee_name }}</strong>
                <div style="font-size: 10.5px; color: #888;">{{ $pb->phone }}</div>
              </td>
              <td>{{ $pb->getEffectiveRitualTitle() }}</td>
              <td>
                {{ optional($pb->booking_date)->format('d M Y') }}
                <div style="font-size: 10.5px; color: #888;">{{ $pb->time_slot }}</div>
              </td>
              <td>
                @if($pb->quotation_amount > 0)
                  <strong style="color: #b45309;">₹{{ number_format($pb->quotation_amount, 2) }}</strong>
                @else
                  <strong style="color: #2D6A4F;">₹{{ number_format($pb->amount, 2) }}</strong>
                @endif
                <span class="status-pill {{ $pb->status === 'confirmed' ? 'verified' : 'pending' }}" style="margin-left: 6px;">
                  {{ $pb->status }}
                </span>
              </td>
              <td style="text-align: right;">
                <a href="{{ route('admin.poojaBookings') }}" class="action-btn" style="padding: 4px 10px; font-size: 11px;">
                  Manage &rarr;
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="text-align: center; padding: 28px; color: #888;">No recent ritual bookings found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
      <div style="padding: 10px 18px; background: #FAF6F0; border-top: 1px solid var(--line); text-align: right;">
        <a href="{{ route('admin.poojaBookings') }}" style="font-size: 11.5px; font-weight: 700; color: #D95D0F; text-decoration: none;">
          View All Devotee Pooja Bookings &rarr;
        </a>
      </div>
    </div>

    <!-- Panel 2: Atithi Vishram Stay Enquiries -->
    <div id="hub-panel-atithi" style="overflow-x: auto; display: none;">
      <table class="data-table">
        <thead>
          <tr>
            <th>Enquiry #</th>
            <th>Devotee Name</th>
            <th>Check-in / Out</th>
            <th>Devotees & Room</th>
            <th>Purpose</th>
            <th>Status</th>
            <th style="text-align: right;">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentAtithiEnquiries as $ave)
            <tr>
              <td style="font-family: monospace; font-weight: 700; color: #047857;">#{{ $ave->enquiry_number }}</td>
              <td>
                <strong>{{ $ave->devotee_name }}</strong>
                <div style="font-size: 10.5px; color: #888;">{{ $ave->phone }}</div>
              </td>
              <td>
                {{ optional($ave->check_in_date)->format('d M') }} &mdash; {{ optional($ave->check_out_date)->format('d M Y') }}
              </td>
              <td>
                {{ $ave->number_of_devotees }} Guests &bull; {{ $ave->getRoomPreferenceLabel() }}
              </td>
              <td>{{ $ave->getPurposeLabel() }}</td>
              <td>
                <span class="status-pill {{ $ave->status === 'confirmed' ? 'verified' : 'pending' }}">
                  {{ $ave->status }}
                </span>
              </td>
              <td style="text-align: right;">
                <a href="{{ route('admin.atithiVishram') }}" class="action-btn" style="padding: 4px 10px; font-size: 11px;">
                  Review &rarr;
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" style="text-align: center; padding: 28px; color: #888;">No recent Atithi Vishram accommodation enquiries.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
      <div style="padding: 10px 18px; background: #FAF6F0; border-top: 1px solid var(--line); text-align: right;">
        <a href="{{ route('admin.atithiVishram') }}" style="font-size: 11.5px; font-weight: 700; color: #047857; text-decoration: none;">
          View All Atithi Vishram Stay Enquiries &rarr;
        </a>
      </div>
    </div>

    <!-- Panel 3: Temple Hall Bookings -->
    <div id="hub-panel-temple" style="overflow-x: auto; display: none;">
      <table class="data-table">
        <thead>
          <tr>
            <th>Proposal #</th>
            <th>Organizer Name</th>
            <th>Event Type</th>
            <th>Proposed Date</th>
            <th>Gathering</th>
            <th>Status</th>
            <th style="text-align: right;">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentTempleBookings as $tbe)
            <tr>
              <td style="font-family: monospace; font-weight: 700; color: #8F1719;">#{{ $tbe->enquiry_number }}</td>
              <td>
                <strong>{{ $tbe->devotee_name }}</strong>
                <div style="font-size: 10.5px; color: #888;">{{ $tbe->phone }}</div>
              </td>
              <td>
                <strong>{{ $tbe->getEventTypeLabel() }}</strong>
                @if($tbe->event_title)
                  <div style="font-size: 10.5px; color: #888;">"{{ $tbe->event_title }}"</div>
                @endif
              </td>
              <td>
                {{ optional($tbe->proposed_date)->format('d M Y') }} ({{ $tbe->duration_days }} days)
              </td>
              <td>{{ number_format($tbe->expected_gathering) }} Attendees</td>
              <td>
                <span class="status-pill {{ $tbe->status === 'approved' ? 'verified' : 'pending' }}">
                  {{ $tbe->status }}
                </span>
              </td>
              <td style="text-align: right;">
                <a href="{{ route('admin.templeBookings') }}" class="action-btn" style="padding: 4px 10px; font-size: 11px;">
                  Review &rarr;
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" style="text-align: center; padding: 28px; color: #888;">No recent temple hall booking proposals.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
      <div style="padding: 10px 18px; background: #FAF6F0; border-top: 1px solid var(--line); text-align: right;">
        <a href="{{ route('admin.templeBookings') }}" style="font-size: 11.5px; font-weight: 700; color: #8F1719; text-decoration: none;">
          View All Temple Hall & Event Bookings &rarr;
        </a>
      </div>
    </div>

    <!-- Panel 4: Seva Donations -->
    <div id="hub-panel-donations" style="overflow-x: auto; display: none;">
      <table class="data-table">
        <thead>
          <tr>
            <th>Receipt No</th>
            <th>Donor</th>
            <th>Amount</th>
            <th>Cause</th>
            <th>Status</th>
            <th style="text-align: right;">Receipt</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentDonations as $d)
            <tr>
              <td style="font-family: monospace; font-weight: 600;">{{ $d->receipt_number ?? 'Pending' }}</td>
              <td>{{ $d->donor_name ?: ($d->user?->name ?? 'Guest Supporter') }}</td>
              <td style="font-weight: 700; color: #1b5e20;">₹{{ number_format($d->amount, 2) }}</td>
              <td style="color: rgba(43, 35, 29, 0.7);">{{ $d->cause?->title ?? 'General Seva' }}</td>
              <td>
                <span class="status-pill {{ $d->status === 'verified' ? 'verified' : 'pending' }}">
                  {{ ucfirst($d->status) }}
                </span>
              </td>
              <td style="text-align: right;">
                @if($d->receipt_number)
                  <a href="{{ url('/receipt/'.$d->receipt_number) }}" target="_blank" class="action-btn" style="padding: 3px 8px; font-size: 11px;">
                    View &nearr;
                  </a>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="text-align: center; padding: 28px; color: #888;">No recent donations recorded.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
      <div style="padding: 10px 18px; background: #FAF6F0; border-top: 1px solid var(--line); text-align: right;">
        <a href="{{ route('admin.causes') }}" style="font-size: 11.5px; font-weight: 700; color: #166534; text-decoration: none;">
          View All Seva Campaigns & Donations &rarr;
        </a>
      </div>
    </div>
  </div>

  <script>
    function switchHubTab(tab) {
      const tabs = ['pooja', 'atithi', 'temple', 'donations'];
      tabs.forEach(t => {
        const btn = document.getElementById('hub-tab-' + t);
        const panel = document.getElementById('hub-panel-' + t);
        if (t === tab) {
          panel.style.display = 'block';
          btn.style.background = (t === 'pooja' ? '#D95D0F' : (t === 'atithi' ? '#047857' : (t === 'temple' ? '#8F1719' : '#166534')));
          btn.style.color = '#fff';
        } else {
          panel.style.display = 'none';
          btn.style.background = 'transparent';
          btn.style.color = 'var(--ink)';
        }
      });
    }
  </script>

  <!-- Content Split: Recent Donations & Audit Activity -->
  <div class="content-split-grid">
    
    <!-- Left: Recent Verified Donations -->
    <div class="table-box">
      <div class="table-head-bar" style="display: flex; justify-content: space-between; align-items: center; gap: 8px;">
        <span class="table-title">Recent Verified Donations</span>
        <div style="display: flex; align-items: center; gap: 12px;">
          <a href="{{ route('admin.donations.exportCsv') }}" style="display: inline-flex; align-items: center; gap: 5px; background: #166534; color: #ffffff; padding: 5px 12px; border-radius: 9999px; font-size: 11px; font-weight: 700; text-decoration: none; box-shadow: 0 2px 6px rgba(22,101,52,0.2);">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            <span>Export 80G CSV</span>
          </a>
          <a href="{{ route('admin.causes') }}" style="font-size: 11.5px; font-weight: 700; color: var(--sand);">View Causes &rarr;</a>
        </div>
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