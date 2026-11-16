@extends('layouts.app')

@section('title', 'सदस्य डैशबोर्ड एवं प्रोफाइल | श्री कामदगिरि पीठम् सेवा ट्रस्ट')

@section('styles')
<style>
  /* Devotee Notification Center & Real-Time Alerts */
  .devotee-notif-wrap {
    position: relative;
    display: inline-block;
  }
  .devotee-notif-btn {
    position: relative;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 16px;
    background: #FFFDF9;
    border: 1.5px solid rgba(180, 83, 9, 0.35);
    border-radius: 9999px;
    color: #78350F;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 2px 8px rgba(180, 83, 9, 0.08);
  }
  .devotee-notif-btn:hover {
    background: #FEF3C7;
    border-color: #B45309;
    transform: translateY(-1px);
  }
  .devotee-notif-badge {
    background: #DC2626;
    color: #fff;
    font-size: 10px;
    font-weight: 800;
    padding: 1px 6px;
    border-radius: 9999px;
    min-width: 16px;
    text-align: center;
    line-height: 14px;
    animation: devoteePillPulse 2s infinite ease-in-out;
  }
  @keyframes devoteePillPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.15); box-shadow: 0 0 10px rgba(220, 38, 38, 0.6); }
    100% { transform: scale(1); }
  }
  .devotee-notif-panel {
    position: absolute;
    top: calc(100% + 10px);
    left: 0;
    width: 360px;
    max-width: 90vw;
    background: #FFFDF9;
    border: 1.5px solid rgba(180, 83, 9, 0.3);
    border-radius: 14px;
    box-shadow: 0 16px 40px rgba(43, 26, 18, 0.18);
    z-index: 9999;
    display: none;
    flex-direction: column;
    overflow: hidden;
    animation: devoteeNotifFade 0.2s ease-out forwards;
  }
  @keyframes devoteeNotifFade {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
  }
  .devotee-notif-header {
    padding: 12px 16px;
    background: #FAF4ED;
    border-bottom: 1px solid rgba(180, 83, 9, 0.15);
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .devotee-notif-list {
    max-height: 360px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
  }
  .devotee-notif-item {
    padding: 12px 14px;
    border-bottom: 1px solid rgba(180, 83, 9, 0.08);
    display: flex;
    align-items: flex-start;
    gap: 10px;
    text-decoration: none;
    color: inherit;
    transition: background 0.15s ease;
  }
  .devotee-notif-item:hover {
    background: #FEF3C7;
  }
  .devotee-notif-item.unread {
    background: rgba(245, 158, 11, 0.12);
  }
  .devotee-toast-container {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 99999;
    display: flex;
    flex-direction: column;
    gap: 10px;
    max-width: 380px;
  }
  .devotee-live-toast {
    background: #FFFDF9;
    border: 2px solid #D97706;
    border-radius: 12px;
    padding: 14px 18px;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.18);
    display: flex;
    align-items: flex-start;
    gap: 12px;
    animation: toastPopIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
  }
  @keyframes toastPopIn {
    from { opacity: 0; transform: translateY(20px) scale(0.92); }
    to { opacity: 1; transform: translateY(0) scale(1); }
  }

  /* Responsive Quotation Alert Banner */
  .quotation-alert-card {
    background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%);
    border: 2px solid #F59E0B;
    border-radius: 16px;
    padding: 18px 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    box-shadow: 0 8px 22px rgba(217, 119, 6, 0.16);
  }
  .quotation-alert-left {
    display: flex;
    align-items: center;
    gap: 14px;
    flex: 1;
    min-width: 0;
  }
  .quotation-alert-btn {
    background: #B45309;
    color: #fff !important;
    font-weight: 800;
    white-space: nowrap;
    padding: 11px 24px;
    border-radius: 9999px;
    text-decoration: none;
    box-shadow: 0 4px 14px rgba(180,83,9,0.3);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-size: 13px;
    transition: all 0.2s ease;
    flex-shrink: 0;
  }
  .quotation-alert-btn:hover {
    background: #92400E;
    transform: translateY(-1px);
  }

  /* Mobile Dashboard Enhancements */
  @media (max-width: 768px) {
    .dashboard-section {
      padding-top: 100px !important;
      padding-bottom: 120px !important;
    }
    .devotee-notif-panel {
      position: fixed !important;
      top: 130px !important;
      left: 14px !important;
      right: 14px !important;
      width: auto !important;
      max-width: calc(100vw - 28px) !important;
      box-shadow: 0 20px 50px rgba(43, 26, 18, 0.35) !important;
    }
    .dashboard-actions {
      display: grid !important;
      grid-template-columns: 1fr 1fr !important;
      gap: 10px !important;
      width: 100% !important;
      margin-top: 14px !important;
    }
    .dashboard-actions .devotee-notif-wrap {
      width: 100% !important;
      display: block !important;
    }
    .dashboard-actions .devotee-notif-btn,
    .dashboard-actions .btn-card-dl,
    .dashboard-actions .btn-edit-profile,
    .dashboard-actions .dashboard-logout-form,
    .dashboard-actions .btn-logout {
      width: 100% !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      text-align: center !important;
      box-sizing: border-box !important;
      margin: 0 !important;
    }
    .dashboard-actions .btn-logout {
      width: 100% !important;
    }
    .quotation-alert-card {
      flex-direction: column !important;
      align-items: stretch !important;
      padding: 16px 14px !important;
      gap: 14px !important;
    }
    .quotation-alert-left {
      align-items: flex-start !important;
      gap: 10px !important;
    }
    .quotation-alert-btn {
      width: 100% !important;
      box-sizing: border-box !important;
      text-align: center !important;
      white-space: normal !important;
      padding: 12px 18px !important;
    }
    .profile-details-list {
      display: flex !important;
      flex-direction: column !important;
      gap: 8px !important;
    }
    .profile-item {
      display: flex !important;
      flex-direction: column !important;
      align-items: flex-start !important;
      justify-content: flex-start !important;
      border-bottom: 1px dashed rgba(180, 83, 9, 0.18) !important;
      padding-bottom: 8px !important;
      gap: 2px !important;
    }
    .profile-item strong {
      font-size: 11.5px !important;
      color: #92400E !important;
      font-weight: 700 !important;
      text-transform: uppercase !important;
      letter-spacing: 0.04em !important;
      display: block !important;
    }
    .profile-item span {
      font-size: 14px !important;
      color: #2B1A12 !important;
      font-weight: 600 !important;
      text-align: left !important;
      word-break: break-word !important;
      line-height: 1.45 !important;
      display: block !important;
      width: 100% !important;
    }
    #profileEditModal {
      padding: 10px !important;
    }
    #profileEditModal > div {
      padding: 20px 14px !important;
      border-radius: 14px !important;
      max-height: 94vh !important;
    }
    .profile-modal-grid {
      grid-template-columns: 1fr !important;
      gap: 12px !important;
    }
    .profile-modal-grid > div {
      grid-column: span 1 !important;
    }
    .devotee-toast-container {
      left: 14px !important;
      right: 14px !important;
      bottom: 80px !important;
      max-width: calc(100vw - 28px) !important;
    }
  }
</style>
@endsection

@section('content')

  <section class="section dashboard-section" style="padding-top: 140px; background: var(--paper); min-height: 85vh;">
    <div class="container" style="max-width: 1100px;">

      <!-- Header Card -->
      <div class="dashboard-header-card">
        <div class="dashboard-user-info">
          @if($user->profile?->photo_path)
            <img src="{{ asset('storage/' . $user->profile->photo_path) }}" alt="{{ $user->name }}" class="dashboard-avatar">
          @else
            <div class="dashboard-avatar-placeholder">
              {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
          @endif
          <div>
            <span class="dashboard-role-badge">
              {{ strtoupper($user->role) }} ACCOUNT
            </span>
            <h1 class="dashboard-title">प्रणाम, {{ $user->name }}</h1>
            <p class="dashboard-meta">{{ $user->email }} · {{ $user->phone ?? 'संपर्क नंबर उपलब्ध नहीं' }}</p>
          </div>
        </div>

        <div class="dashboard-actions">
          <!-- Devotee Notification Center Bell & Dropdown -->
          <div class="devotee-notif-wrap" id="devoteeNotifWrap">
            <button type="button" class="devotee-notif-btn" id="devoteeNotifBtn" onclick="toggleDevoteeNotifDropdown()" aria-label="Devotee Notifications">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
              </svg>
              <span>Notifications</span>
              <span class="devotee-notif-badge" id="devoteeNotifBadge" style="display: none;">0</span>
            </button>

            <div class="devotee-notif-panel" id="devoteeNotifPanel">
              <div class="devotee-notif-header">
                <div style="font-size: 13px; font-weight: 800; color: #78350F; font-family: var(--dev);">
                  🔔 Notifications
                </div>
                <button type="button" onclick="markDevoteeNotifSeen()" style="background: none; border: none; color: #B45309; font-size: 11px; font-weight: 700; cursor: pointer; text-decoration: underline;">
                  Mark as Read
                </button>
              </div>
              <div class="devotee-notif-list" id="devoteeNotifList">
                <div style="padding: 24px; text-align: center; color: #888; font-size: 12px;">
                  Checking updates...
                </div>
              </div>
            </div>
          </div>

          <a href="{{ url('/user/card') }}" class="btn primary btn-card-dl">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><line x1="7" y1="8" x2="17" y2="8"/><line x1="7" y1="12" x2="13" y2="12"/></svg>
            <span>ID Card</span>
          </a>
          <button type="button" onclick="openProfileModal()" class="btn btn-edit-profile" style="background: rgba(180, 83, 9, 0.08); color: #B45309; border: 1px solid rgba(180, 83, 9, 0.25);">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            <span>Edit Profile</span>
          </button>
          <form action="{{ url('/logout') }}" method="POST" class="dashboard-logout-form" style="margin: 0; display: inline;">
            @csrf
            <button type="submit" class="btn dark btn-logout">Logout</button>
          </form>
        </div>
      </div>

      @if(session('success'))
        <div style="background: #ECFDF5; border: 1px solid #10B981; color: #065F46; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; font-size: 14px;">
          ✓ {{ session('success') }}
        </div>
      @endif
      @if($errors->any())
        <div style="background: #FEF2F2; border: 1px solid #EF4444; color: #991B1B; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
          <strong>कृपया त्रुटियां सुधारें:</strong>
          <ul style="margin: 6px 0 0 20px; padding: 0;">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <!-- Real-Time Quotation Dynamic Callout Banner -->
      <div id="devoteeLiveQuotationAlert" style="display: none; margin-bottom: 24px;"></div>

      <div class="dashboard-grid">

        <!-- Personal Profile & Membership Card Summary -->
        <div class="dashboard-sidebar">
          
          <div class="dashboard-card">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(180, 83, 9, 0.18); padding-bottom: 12px; margin-bottom: 18px;">
              <h3 class="dashboard-card-title" style="margin: 0; border: none; padding: 0;">
                व्यक्तिगत जानकारी (Profile Details)
              </h3>
              <button type="button" onclick="openProfileModal()" style="background: rgba(180, 83, 9, 0.1); border: 1px solid rgba(180, 83, 9, 0.25); color: #B45309; border-radius: 9999px; padding: 5px 14px; font-size: 11.5px; font-weight: 700; cursor: pointer; white-space: nowrap;">
                Edit Profile
              </button>
            </div>
            <div class="profile-details-list">
              <div class="profile-item"><strong>नाम (Name):</strong> <span>{{ $user->name }}</span></div>
              <div class="profile-item"><strong>फोन (Phone):</strong> <span>{{ $user->profile?->phone ?? $user->phone ?? 'N/A' }}</span></div>
              <div class="profile-item"><strong>लिंग (Gender):</strong> <span>{{ ucfirst($user->profile?->gender ?? 'N/A') }}</span></div>
              <div class="profile-item"><strong>जन्म तिथि (DOB):</strong> <span>{{ $user->profile?->date_of_birth ? \Carbon\Carbon::parse($user->profile->date_of_birth)->format('d M Y') : 'N/A' }}</span></div>
              <div class="profile-item"><strong>व्यवसाय (Profession):</strong> <span>{{ $user->profile?->occupation ?? 'N/A' }}</span></div>
              <div class="profile-item"><strong>जिला (District):</strong> <span>{{ $user->profile?->district ?? 'N/A' }}</span></div>
              <div class="profile-item"><strong>पता (Address):</strong> <span>{{ $user->profile?->address ?? 'N/A' }}</span></div>
            </div>
          </div>

          <div class="dashboard-card">
            <h3 class="dashboard-card-title">सदस्यता कार्ड स्थिति</h3>
            <p class="membership-id-text">
              ID: {{ $user->membership?->membership_id ?? 'SKP-SUPPORTER' }}
            </p>
            
            <div class="membership-type-banner">
              <div class="m-card-label">Card Type</div>
              <div class="m-card-val">
                @if(($user->membership?->status ?? '') === 'active')
                  Active Member Digital Card
                @elseif($user->donations->count() > 0)
                  Supporter Digital Card
                @else
                  Member Registration Card
                @endif
              </div>
            </div>

            <a href="{{ url('/user/card') }}" class="btn primary" style="width: 100%; justify-content: center; font-size: 12px; padding: 12px; border-radius: 9999px; font-weight: 700; white-space: nowrap;">
              View ID Card &rarr;
            </a>
          </div>

        </div>

        <!-- Synchronized Devotee History (Donations, Poojas, Volunteering) -->
        <div class="dashboard-card dashboard-main-content" style="padding: 24px 28px;">
          
          <!-- Sync Notice -->
          <div style="background: #FFF8EE; border: 1px solid rgba(180, 83, 9, 0.22); border-radius: 12px; padding: 14px 18px; margin-bottom: 22px; display: flex; align-items: center; gap: 12px; font-size: 13px; color: #78350F; line-height: 1.6;">
            <span style="font-size: 20px;">🛡️</span>
            <div>
              <strong>ईमेल संबद्धता सक्रिय (Guest History Synchronized):</strong> आपके ईमेल <code>{{ $user->email }}</code> से पूर्व में अतिथि के रूप में किए गए सभी सेवा दान, पूजा संकल्प एवं सेवादार आवेदन स्वतः आपके इस डैशबोर्ड से जोड़ दिए गए हैं।
            </div>
          </div>

          <!-- Section Switcher Tabs -->
          <div class="dashboard-tabs" style="display: flex; gap: 10px; border-bottom: 2px solid rgba(180, 83, 9, 0.14); margin-bottom: 24px; overflow-x: auto; padding-bottom: 2px;">
            <button type="button" class="dash-tab-btn active-dash-tab" onclick="switchDashTab('tab-donations', this)" style="padding: 10px 18px; border: none; background: transparent; font: 700 13.5px var(--dev); color: #B45309; cursor: pointer; border-bottom: 3px solid #B45309; margin-bottom: -4px; transition: all .2s; white-space: nowrap;">
              {{ ($orgSettings->is_80g_enabled ?? false) ? 'दान एवं 80G रसीदें' : 'दान एवं सेवा रसीदें' }} ({{ $donations->count() }})
            </button>
            <button type="button" class="dash-tab-btn" onclick="switchDashTab('tab-poojas', this)" style="padding: 10px 18px; border: none; background: transparent; font: 700 13.5px var(--dev); color: #786457; cursor: pointer; border-bottom: 3px solid transparent; margin-bottom: -4px; transition: all .2s; white-space: nowrap;">
              पूजा व अनुष्ठान संकल्प ({{ $poojaBookings->count() }})
            </button>
            <button type="button" class="dash-tab-btn" onclick="switchDashTab('tab-volunteers', this)" style="padding: 10px 18px; border: none; background: transparent; font: 700 13.5px var(--dev); color: #786457; cursor: pointer; border-bottom: 3px solid transparent; margin-bottom: -4px; transition: all .2s; white-space: nowrap;">
              सेवादार नामांकन ({{ $volunteerApplications->count() }})
            </button>
          </div>

          <!-- Tab 1: Donations & 80G Receipts -->
          <div id="tab-donations" class="dash-tab-content">
            <h3 class="dashboard-card-title" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
              <span>{{ ($orgSettings->is_80g_enabled ?? false) ? 'दान एवं आधिकारिक 80G रसीद इतिहास' : 'दान एवं आधिकारिक रसीद इतिहास' }}</span>
              <a href="{{ url('/donate') }}" class="btn primary" style="font-size: 11.5px; padding: 7px 18px; border-radius: 9999px; white-space: nowrap;">+ Donate Now</a>
            </h3>

            @forelse($donations as $donation)
              <div class="donation-receipt-row">
                <div class="donation-row-left">
                  <div style="display: flex; align-items: center; gap: 10px;">
                    <strong class="donation-amount">₹{{ number_format($donation->amount, 2) }}</strong>
                    <span class="donation-status-pill" style="background: {{ $donation->status === 'verified' ? 'rgba(16, 124, 65, 0.12)' : 'rgba(217, 119, 6, 0.12)' }}; color: {{ $donation->status === 'verified' ? '#059669' : '#D97706' }};">
                      {{ strtoupper($donation->status) }}
                    </span>
                  </div>
                  <span class="donation-cause-name" style="margin-top: 4px; display: inline-block;">
                    {{ $donation->cause?->title ?? ($donation->donation_type === 'monthly_sustaining' ? 'मासिक सेवा संकल्प (Monthly Sustaining)' : 'सामान्य सेवा (General Seva)') }}
                  </span>
                  <div class="donation-meta-info">
                    रसीद: <strong>{{ $donation->receipt_number ?? 'प्रक्रियाधीन' }}</strong> &bull; दिनांक: {{ $donation->created_at->format('d M Y, h:i A') }}
                    @if($donation->donor_email && $donation->donor_email !== $user->email)
                      &bull; <span style="color: #92400E;">मूल अतिथि ईमेल: {{ $donation->donor_email }}</span>
                    @endif
                  </div>
                </div>
                <div class="donation-row-right">
                  @if($donation->receipt_number)
                    <a href="{{ url('/receipt/' . $donation->receipt_number) }}" target="_blank" class="btn dark btn-receipt-view" style="display: inline-flex; align-items: center; justify-content: center; gap: 6px; white-space: nowrap;">
                      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                      View Receipt
                    </a>
                  @else
                    <span style="font-size: 11px; color: #9CA3AF;">रसीद शीघ्र उपलब्ध होगी</span>
                  @endif
                </div>
              </div>
            @empty
              <div class="empty-donations-state" style="padding: 40px 20px;">
                <p style="color: #6B7280; font-size: 14px;">आपने अभी तक कोई ऑनलाइन दान नहीं दिया है।</p>
                <a href="{{ url('/donate') }}" class="btn primary" style="margin-top: 15px; font-size: 12px; padding: 11px 26px; white-space: nowrap;">Donate Now</a>
              </div>
            @endforelse
          </div>

          <!-- Tab 2: Pooja & Sankalp Bookings -->
          <div id="tab-poojas" class="dash-tab-content" style="display: none;">
            <h3 class="dashboard-card-title" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
              <span>पूजा एवं अनुष्ठान संकल्प (Pooja & Rituals)</span>
              <a href="{{ url('/pooja/book') }}" class="btn primary" style="font-size: 11.5px; padding: 7px 18px; border-radius: 9999px; white-space: nowrap;">+ Book Pooja</a>
            </h3>

            @forelse($poojaBookings as $booking)
              <div class="donation-receipt-row" style="{{ $booking->isQuotationActive() ? 'border: 2px solid #D97706; background: #FFFDF9;' : '' }}">
                <div class="donation-row-left">
                  <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                    <strong style="font: 700 16px var(--dev); color: #78350F;">{{ $booking->getEffectiveRitualTitle() }}</strong>
                    
                    @if($booking->isQuotationActive())
                      <span class="donation-status-pill" style="background: #FEF3C7; color: #B45309; border: 1px solid #F59E0B; font-weight: 800;">
                        ⚡ दक्षिणा कोटेशन प्राप्त (QUOTED)
                      </span>
                    @elseif($booking->status === 'confirmed')
                      <span class="donation-status-pill" style="background: rgba(16, 124, 65, 0.12); color: #059669; font-weight: 800;">
                        ✓ अनुष्ठान पुष्ट (CONFIRMED)
                      </span>
                    @elseif($booking->isQuotationExpired())
                      <span class="donation-status-pill" style="background: #FEE2E2; color: #DC2626;">
                        कोटेशन समाप्त (EXPIRED)
                      </span>
                    @else
                      <span class="donation-status-pill" style="background: rgba(217, 119, 6, 0.12); color: #D97706;">
                        {{ strtoupper($booking->status) }} (समीक्षाधीन)
                      </span>
                    @endif
                  </div>

                  @if($booking->isQuotationActive())
                    <div style="background: #FFF7ED; border: 1.5px dashed #D97706; border-radius: 8px; padding: 10px 14px; margin-top: 8px; font-size: 13px; color: #9A3412;">
                      <strong>स्वीकृत दक्षिणा:</strong> <span style="font-size: 16px; font-weight: 800; color: #8F1719;">₹{{ number_format((float) $booking->quotation_amount, 2) }}</span> 
                      &bull; <span style="font-size: 11.5px; color: #B45309;">⏳ वैधता: {{ $booking->quotation_expires_at ? $booking->quotation_expires_at->diffForHumans() : 'सीमित समय' }}</span>
                      @if($booking->quotation_notes)
                        <div style="margin-top: 4px; font-size: 12px; color: #78350F;">{{ $booking->quotation_notes }}</div>
                      @endif
                    </div>
                  @endif

                  <div style="font-size: 13px; color: #4B5563; margin-top: 6px; line-height: 1.6;">
                    <strong>बुकिंग संदर्भ:</strong> <code style="font-weight: 700; color: #9A3412;">{{ $booking->booking_number }}</code> &bull; 
                    <strong>तिथि:</strong> {{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') : 'N/A' }} ({{ $booking->time_slot }})<br>
                    <strong>यजमान:</strong> {{ $booking->devotee_name }} &bull; <strong>गोत्र:</strong> {{ $booking->gotra ?: 'Not Specified' }}
                    @if($booking->amount > 0)
                      &bull; <strong>प्राप्त दक्षिणा:</strong> ₹{{ number_format((float) $booking->amount, 2) }}
                    @endif
                    @if($booking->admin_notes)
                      <div style="background: #F3F4F6; border-radius: 6px; padding: 6px 12px; margin-top: 6px; font-size: 12px; color: #374151;">
                        <strong>पुरोहित संदेश:</strong> {{ $booking->admin_notes }}
                      </div>
                    @endif
                  </div>
                </div>

                <div class="donation-row-right" style="display: flex; flex-direction: column; gap: 8px; align-items: flex-end;">
                  @if($booking->isQuotationActive())
                    <a href="{{ $booking->getQuotationPaymentUrl() }}" class="btn primary" style="font-size: 11px; padding: 9px 18px; border-radius: 8px; background: #B45309; color: #fff; font-weight: 800; text-decoration: none; box-shadow: 0 4px 12px rgba(180,83,9,0.25); white-space: nowrap;">
                      🔒 Pay Online (₹{{ number_format((float) $booking->quotation_amount, 0) }}) &rarr;
                    </a>
                  @endif

                  <a href="{{ route('pooja.confirmation', ['bookingNumber' => $booking->booking_number]) }}" target="_blank" class="btn dark btn-receipt-view" style="font-size: 10.5px; padding: 8px 14px; white-space: nowrap;">
                    View Receipt &rarr;
                  </a>
                </div>
              </div>
            @empty
              <div class="empty-donations-state" style="padding: 40px 20px;">
                <p style="color: #6B7280; font-size: 14px;">आपकी कोई सक्रिय पूजा अथवा अनुष्ठान संकल्प पंजीकृत नहीं है।</p>
                <a href="{{ url('/pooja/book') }}" class="btn primary" style="margin-top: 15px; font-size: 12px; padding: 11px 26px; white-space: nowrap;">Book Pooja</a>
              </div>
            @endforelse
          </div>

          <!-- Tab 3: Volunteer Enrolments -->
          <div id="tab-volunteers" class="dash-tab-content" style="display: none;">
            <h3 class="dashboard-card-title" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
              <span>सेवादार नामांकन एवं सेवा दल स्थिति</span>
              <a href="{{ url('/volunteer') }}" class="btn primary" style="font-size: 11.5px; padding: 7px 18px; border-radius: 9999px; white-space: nowrap;">+ Join Volunteer Seva</a>
            </h3>

            @forelse($volunteerApplications as $applicant)
              <div class="donation-receipt-row">
                <div class="donation-row-left">
                  <div style="display: flex; align-items: center; gap: 10px;">
                    <strong style="font: 700 16px var(--dev); color: #16100D;">सेवादार आवेदन #VOL-{{ sprintf('%04d', $applicant->id) }}</strong>
                    <span class="donation-status-pill" style="background: {{ $applicant->status === 'approved' ? 'rgba(16, 124, 65, 0.12)' : ($applicant->status === 'rejected' ? 'rgba(220, 38, 38, 0.12)' : 'rgba(217, 119, 6, 0.12)') }}; color: {{ $applicant->status === 'approved' ? '#059669' : ($applicant->status === 'rejected' ? '#DC2626' : '#D97706') }};">
                      @if($applicant->status === 'approved')
                        ✓ सेवादार दल स्वीकृत (APPROVED)
                      @elseif($applicant->status === 'rejected')
                        स्थगित / समीक्षा पूर्ण
                      @else
                        समीक्षाधीन (UNDER REVIEW)
                      @endif
                    </span>
                  </div>
                  <div style="font-size: 13px; color: #4B5563; margin-top: 5px; line-height: 1.6;">
                    <strong>चयनित सेवा क्षेत्र:</strong> {{ is_array($applicant->seva_areas) ? implode(', ', $applicant->seva_areas) : ($applicant->seva_areas ?: 'सामान्य सेवा') }}<br>
                    <strong>उपलब्धता:</strong> {{ $applicant->availability ?: 'नियमित' }} &bull; <strong>स्थान:</strong> {{ $applicant->city ?? 'चित्रकूट' }}, {{ $applicant->state ?? '' }} &bull; <strong>दिनांक:</strong> {{ $applicant->created_at->format('d M Y') }}
                    @if($applicant->admin_notes)
                      <div style="background: #F3F4F6; border-radius: 6px; padding: 6px 12px; margin-top: 6px; font-size: 12px; color: #374151;">
                        <strong>ट्रस्ट कार्यालय संदेश:</strong> {{ $applicant->admin_notes }}
                      </div>
                    @endif
                  </div>
                </div>
                <div class="donation-row-right">
                  <span style="font-size: 11px; font-weight: 700; color: #B45309;">
                    {{ $applicant->status === 'approved' ? 'सक्रिय सेवादार' : 'पंजीकृत आवेदन' }}
                  </span>
                </div>
              </div>
            @empty
              <div class="empty-donations-state" style="padding: 40px 20px;">
                <p style="color: #6B7280; font-size: 14px;">आपने अभी तक सेवादार के रूप में पंजीकरण नहीं कराया है।</p>
                <a href="{{ url('/volunteer') }}" class="btn primary" style="margin-top: 15px; font-size: 12px; padding: 11px 26px; white-space: nowrap;">Join Volunteer Seva</a>
              </div>
            @endforelse
          </div>

        </div>

        <script>
          function switchDashTab(tabId, btn) {
            document.querySelectorAll('.dash-tab-content').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.dash-tab-btn').forEach(b => {
              b.style.color = '#786457';
              b.style.borderBottomColor = 'transparent';
              b.classList.remove('active-dash-tab');
            });
            document.getElementById(tabId).style.display = 'block';
            btn.style.color = '#B45309';
            btn.style.borderBottomColor = '#B45309';
            btn.classList.add('active-dash-tab');
          }

          function openProfileModal() {
            const modal = document.getElementById('profileEditModal');
            if (modal) modal.style.display = 'flex';
          }

          function closeProfileModal() {
            const modal = document.getElementById('profileEditModal');
            if (modal) modal.style.display = 'none';
          }
        </script>

      </div>

    </div>
  </section>

  <!-- Devotee Profile Edit Modal -->
  <div id="profileEditModal" style="display: none; position: fixed; inset: 0; z-index: 99999; background: rgba(22, 16, 13, 0.72); backdrop-filter: blur(6px); align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #FFFDF9; border: 1px solid rgba(180, 83, 9, 0.28); border-radius: 18px; width: 100%; max-width: 620px; max-height: 90vh; overflow-y: auto; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35); padding: 28px 32px; position: relative;">
      
      <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(180, 83, 9, 0.16); padding-bottom: 14px; margin-bottom: 20px;">
        <div>
          <h3 style="font: 700 20px var(--dev); color: #8F1719; margin: 0 0 4px;">व्यक्तिगत जानकारी संशोधन</h3>
          <p style="font-size: 13px; color: #786457; margin: 0;">Update Profile & Supporter Information</p>
        </div>
        <button type="button" onclick="closeProfileModal()" style="background: #F3F4F6; border: none; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px; color: #4B5563; cursor: pointer; transition: all .2s;">&times;</button>
      </div>

      <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        
        <div class="profile-modal-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
          
          <div style="grid-column: span 2;">
            <label style="display: block; font: 700 13px var(--dev); color: #374151; margin-bottom: 6px;">पूरा नाम (Full Name) *</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required style="width: 100%; padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 14px; background: #fff; box-sizing: border-box;">
          </div>

          <div>
            <label style="display: block; font: 700 13px var(--dev); color: #374151; margin-bottom: 6px;">फोन नंबर (Mobile Number)</label>
            <input type="tel" name="phone" value="{{ old('phone', $user->profile?->phone ?? $user->phone) }}" placeholder="10-अंकीय मोबाइल नंबर" style="width: 100%; padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 14px; background: #fff; box-sizing: border-box;">
          </div>

          <div>
            <label style="display: block; font: 700 13px var(--dev); color: #374151; margin-bottom: 6px;">लिंग (Gender)</label>
            @php $currentGender = strtolower($user->profile?->gender ?? ''); @endphp
            <select name="gender" style="width: 100%; padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 14px; background: #fff; box-sizing: border-box;">
              <option value="">चयन करें</option>
              <option value="male" {{ $currentGender === 'male' ? 'selected' : '' }}>पुरुष (Male)</option>
              <option value="female" {{ $currentGender === 'female' ? 'selected' : '' }}>महिला (Female)</option>
              <option value="other" {{ $currentGender === 'other' ? 'selected' : '' }}>अन्य (Other)</option>
            </select>
          </div>

          <div>
            <label style="display: block; font: 700 13px var(--dev); color: #374151; margin-bottom: 6px;">जन्म तिथि (Date of Birth)</label>
            <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->profile?->date_of_birth ? \Carbon\Carbon::parse($user->profile->date_of_birth)->format('Y-m-d') : '') }}" style="width: 100%; padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 14px; background: #fff; box-sizing: border-box;">
          </div>

          <div>
            <label style="display: block; font: 700 13px var(--dev); color: #374151; margin-bottom: 6px;">व्यवसाय (Occupation)</label>
            <input type="text" name="occupation" value="{{ old('occupation', $user->profile?->occupation) }}" placeholder="जैसे: सेवा, व्यापार, विद्यार्थी" style="width: 100%; padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 14px; background: #fff; box-sizing: border-box;">
          </div>

          <div>
            <label style="display: block; font: 700 13px var(--dev); color: #374151; margin-bottom: 6px;">शहर / कस्बा (City/Town)</label>
            <input type="text" name="city" value="{{ old('city', $user->profile?->city) }}" placeholder="उदा. चित्रकूट" style="width: 100%; padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 14px; background: #fff; box-sizing: border-box;">
          </div>

          <div>
            <label style="display: block; font: 700 13px var(--dev); color: #374151; margin-bottom: 6px;">जिला (District)</label>
            <input type="text" name="district" value="{{ old('district', $user->profile?->district) }}" placeholder="उदा. सतना / चित्रकूट" style="width: 100%; padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 14px; background: #fff; box-sizing: border-box;">
          </div>

          <div>
            <label style="display: block; font: 700 13px var(--dev); color: #374151; margin-bottom: 6px;">राज्य (State)</label>
            <input type="text" name="state" value="{{ old('state', $user->profile?->state) }}" placeholder="उदा. मध्य प्रदेश / उत्तर प्रदेश" style="width: 100%; padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 14px; background: #fff; box-sizing: border-box;">
          </div>

          <div>
            <label style="display: block; font: 700 13px var(--dev); color: #374151; margin-bottom: 6px;">पिनकोड (Pincode)</label>
            <input type="text" name="pincode" value="{{ old('pincode', $user->profile?->pincode) }}" placeholder="6-अंकीय पिनकोड" maxlength="10" style="width: 100%; padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 14px; background: #fff; box-sizing: border-box;">
          </div>

          <div style="grid-column: span 2;">
            <label style="display: block; font: 700 13px var(--dev); color: #374151; margin-bottom: 6px;">स्थायी पता (Postal Address)</label>
            <textarea name="address" rows="2" placeholder="मकान नं., मार्ग, मोहल्ला" style="width: 100%; padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 14px; background: #fff; box-sizing: border-box; resize: vertical;">{{ old('address', $user->profile?->address) }}</textarea>
          </div>

        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 16px; border-top: 1px solid rgba(180, 83, 9, 0.16);">
          <button type="button" onclick="closeProfileModal()" style="padding: 10px 20px; border-radius: 9999px; border: 1px solid #D1D5DB; background: #fff; color: #4B5563; font-weight: 700; font-size: 12.5px; cursor: pointer; white-space: nowrap;">
            Cancel
          </button>
          <button type="submit" class="btn primary" style="padding: 10px 24px; font-size: 12.5px; font-weight: 700; border-radius: 9999px; cursor: pointer; white-space: nowrap;">
            Save Changes
          </button>
        </div>

      </form>
    </div>
  </div>

  <!-- Devotee Live Floating Toast Notification Box -->
  <div class="devotee-toast-container" id="devoteeToastContainer"></div>

  <script>
    /* Devotee Real-Time Notification Center & Instant Visual Feedback */
    let devoteeKnownNotificationIds = new Set();
    let devoteeNotifPollTimer = null;

    function playDevoteeNotificationChime() {
      try {
        const AudioCtx = window.AudioContext || window.webkitAudioContext;
        if (!AudioCtx) return;
        const ctx = new AudioCtx();
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.type = 'sine';
        osc.frequency.setValueAtTime(587.33, ctx.currentTime); // D5
        osc.frequency.exponentialRampToValueAtTime(880, ctx.currentTime + 0.15); // A5
        gain.gain.setValueAtTime(0.08, ctx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + 0.45);
        osc.connect(gain);
        gain.connect(ctx.destination);
        osc.start();
        osc.stop(ctx.currentTime + 0.5);
      } catch (e) {}
    }

    function showDevoteeToast(icon, title, desc, url = null) {
      playDevoteeNotificationChime();
      const container = document.getElementById('devoteeToastContainer');
      if (!container) return;

      const toast = document.createElement('div');
      toast.className = 'devotee-live-toast';
      toast.innerHTML = `
        <div style="font-size: 24px; line-height: 1;">${icon}</div>
        <div style="flex: 1; min-width: 0;">
          <div style="font: 700 13.5px var(--dev); color: #78350F; line-height: 1.3;">${title}</div>
          <div style="font-size: 12px; color: #4B5563; margin-top: 3px; line-height: 1.4;">${desc}</div>
          ${url ? `<a href="${url}" style="display: inline-block; margin-top: 6px; font-size: 11.5px; font-weight: 700; color: #B45309; text-decoration: underline;">विवरण देखें &rarr;</a>` : ''}
        </div>
        <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; color: #9CA3AF; font-size: 18px; cursor: pointer; padding: 0 4px; line-height: 1;">&times;</button>
      `;

      container.appendChild(toast);
      setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(10px)';
        toast.style.transition = 'all 0.3s ease';
        setTimeout(() => toast.remove(), 300);
      }, 7000);
    }

    function toggleDevoteeNotifDropdown() {
      const panel = document.getElementById('devoteeNotifPanel');
      if (!panel) return;
      const isVisible = panel.style.display === 'flex';
      if (!isVisible) {
        panel.style.display = 'flex';
        markDevoteeNotifSeen();
      } else {
        panel.style.display = 'none';
      }
    }

    document.addEventListener('click', function(e) {
      const wrap = document.getElementById('devoteeNotifWrap');
      const panel = document.getElementById('devoteeNotifPanel');
      if (wrap && panel && !wrap.contains(e.target)) {
        panel.style.display = 'none';
      }
    });

    function markDevoteeNotifSeen() {
      const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
      const badge = document.getElementById('devoteeNotifBadge');
      if (badge) badge.style.display = 'none';

      document.querySelectorAll('.devotee-notif-item.unread').forEach(el => el.classList.remove('unread'));

      fetch("{{ route('user.notifications.markSeen') }}", {
        method: "POST",
        headers: {
          "X-CSRF-TOKEN": token,
          "Accept": "application/json",
          "Content-Type": "application/json"
        }
      }).catch(() => {});
    }

    function fetchDevoteeNotifications(isInitial = false) {
      fetch("{{ route('user.notifications.poll') }}", {
        headers: { "Accept": "application/json" }
      })
      .then(r => r.json())
      .then(data => {
        const badge = document.getElementById('devoteeNotifBadge');
        const list = document.getElementById('devoteeNotifList');
        const callout = document.getElementById('devoteeLiveQuotationAlert');

        // 1. Update Badge
        if (badge) {
          if (data.unread_count > 0) {
            badge.textContent = data.unread_count;
            badge.style.display = 'inline-block';
          } else {
            badge.style.display = 'none';
          }
        }

        // 2. Dynamic Urgent Quotation Alert Banner
        let quotationItem = null;
        if (data.notifications) {
          quotationItem = data.notifications.find(n => n.type === 'quotation');
        }

        if (callout) {
          if (data.active_quotations_count > 0 && quotationItem) {
            callout.style.display = 'block';
            callout.innerHTML = `
              <div class="quotation-alert-card">
                <div class="quotation-alert-left">
                  <div style="font-size: 28px; line-height: 1; flex-shrink: 0;">⚡</div>
                  <div style="flex: 1; min-width: 0;">
                    <div style="font: 700 16px var(--dev); color: #92400E; line-height: 1.3;">पूजा दक्षिणा कोटेशन उपलब्ध (Quotation Received & Ready for Payment)</div>
                    <div style="font-size: 13.5px; color: #78350F; margin-top: 4px; line-height: 1.4; word-break: break-word;">
                      ${quotationItem.description}
                    </div>
                  </div>
                </div>
                <a href="${quotationItem.url}" class="quotation-alert-btn">
                  <span>अभी दक्षिणा अर्पित करें</span>
                  <span>&rarr;</span>
                </a>
              </div>
            `;
          } else {
            callout.style.display = 'none';
          }
        }

        // 3. Render Dropdown Items & Trigger Toast on New Events
        if (list && data.notifications) {
          if (data.notifications.length === 0) {
            list.innerHTML = '<div style="padding: 24px; text-align: center; color: #888; font-size: 12px;">आपकी कोई नई सूचना नहीं है (No new notifications)</div>';
          } else {
            let html = '';
            data.notifications.forEach((item) => {
              const isUnread = item.is_unread;
              html += `
                <a href="${item.url}" class="devotee-notif-item ${isUnread ? 'unread' : ''}">
                  <div style="font-size: 20px; line-height: 1;">${item.icon}</div>
                  <div style="flex: 1; min-width: 0;">
                    <div style="font: 700 13px var(--dev); color: #78350F;">${item.title}</div>
                    <div style="font-size: 11.5px; color: #4B5563; line-height: 1.4; margin-top: 2px;">${item.description}</div>
                    <div style="font-size: 10px; color: #9CA3AF; margin-top: 4px;">${item.time_ago}</div>
                  </div>
                </a>
              `;

              // If newly arrived in background poll, show toast feedback
              if (!isInitial && isUnread && !devoteeKnownNotificationIds.has(item.id)) {
                showDevoteeToast(item.icon, item.title, item.description, item.url);
              }
              devoteeKnownNotificationIds.add(item.id);
            });
            list.innerHTML = html;
          }
        }
      })
      .catch(() => {});
    }

    document.addEventListener('DOMContentLoaded', function() {
      fetchDevoteeNotifications(true);
      // Live polling every 10 seconds for instant feedback
      devoteeNotifPollTimer = setInterval(() => fetchDevoteeNotifications(false), 10000);
    });
  </script>
@endsection
