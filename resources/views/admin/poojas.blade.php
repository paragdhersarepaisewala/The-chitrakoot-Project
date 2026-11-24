@extends('layouts.admin')

@section('title', 'Pooja Services & Timetables | CMS Admin')
@section('header_title', 'Pooja Services & Timetables')
@section('header_subtitle', 'Manage sacred pooja offerings, daily booking timetables, Acharya assignments, and dakshina amounts')

@section('header_actions')
  <a href="{{ route('pooja.bookingForm') }}" target="_blank" class="action-btn" style="background: var(--sand); color: var(--deep); font-size: 11px; padding: 7px 14px;">
    <span>Public Booking Form</span> &nearr;
  </a>
  <button type="button" onclick="openSlotModal()" class="action-btn" style="background: #086c60; color: #fff; font-weight: 800; font-size: 11px; padding: 8px 16px;">
    + ADD MANDIR TIME SLOT
  </button>
  @if($orgSettings->is_pooja_packages_enabled ?? false)
    <button type="button" onclick="openPoojaModal()" class="action-btn primary" style="font-weight: 800; letter-spacing: 0.05em; padding: 8px 16px;">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
      ADD POOJA / HAWAN PACKAGE &rarr;
    </button>
  @endif
@endsection

@section('content')
<!-- Custom Page CSS -->
<style>
  .pooja-stat-card {
    background: #ffffff;
    border: 1px solid var(--line);
    border-radius: 16px;
    padding: 20px 22px;
    box-shadow: 0 4px 18px rgba(43, 35, 29, 0.04);
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all .25s var(--ease);
  }
  .pooja-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(43, 35, 29, 0.08);
    border-color: rgba(185, 133, 80, 0.4);
  }
  .pooja-stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: grid;
    place-items: center;
    flex-shrink: 0;
  }
  .filter-pill {
    padding: 6px 14px;
    border-radius: 9999px;
    font-size: 12px;
    font-weight: 700;
    border: 1px solid var(--line);
    background: #fff;
    color: var(--ink);
    cursor: pointer;
    transition: all .2s var(--ease);
  }
  .filter-pill.active, .filter-pill:hover {
    background: var(--deep);
    color: #fff;
    border-color: var(--deep);
  }
  .pooja-row:hover {
    background: rgba(250, 244, 237, 0.6) !important;
  }
  .modal-field-box {
    background: #FAF7F2;
    border: 1px solid var(--line);
    border-radius: 10px;
    padding: 12px 14px;
  }
</style>

<!-- Modern Stats Grid -->
<div class="stats-grid" style="margin-bottom: 26px;">
  <!-- Card 1: Active Services -->
  <div class="pooja-stat-card">
    <div class="pooja-stat-icon" style="background: rgba(217, 93, 15, 0.12); color: #D95D0F;">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2c-.3 0-.6.2-.7.5C10 5.4 8 8.6 8 12c0 2.2 1.8 4 4 4s4-1.8 4-4c0-3.4-2-6.6-3.3-9.5-.1-.3-.4-.5-.7-.5zm0 18c-3.3 0-6-2.7-6-6 0-3.8 2.5-7.7 4.3-10.4.5-.7 1.6-.7 2.1 0C14.2 6.3 16.7 10.2 16.7 14c0 3.3-2.7 6-6 6z"/></svg>
    </div>
    <div>
      <div class="stat-label">Active Pooja Offerings</div>
      <div class="stat-val" style="color: #D95D0F;">{{ $poojas->where('is_active', true)->count() }}</div>
      <div class="stat-sub">Available for online booking</div>
    </div>
  </div>

  <!-- Card 2: Total Bookings -->
  <div class="pooja-stat-card">
    <div class="pooja-stat-icon" style="background: rgba(180, 83, 9, 0.12); color: #B45309;">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
    </div>
    <div>
      <div class="stat-label">Devotee Sankalps</div>
      <div class="stat-val" style="color: var(--deep);">{{ $totalBookings }}</div>
      <div class="stat-sub">Total ceremonies registered</div>
    </div>
  </div>

  <!-- Card 3: Pending Acharya -->
  <div class="pooja-stat-card">
    <div class="pooja-stat-icon" style="background: rgba(234, 88, 12, 0.12); color: #EA580C;">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
    </div>
    <div>
      <div class="stat-label">Pending Verification</div>
      <div class="stat-val" style="color: #EA580C;">{{ $pendingBookings }}</div>
      <div class="stat-sub">Awaiting Acharya scheduling</div>
    </div>
  </div>

  <!-- Card 4: Confirmed Sevas -->
  <div class="pooja-stat-card">
    <div class="pooja-stat-icon" style="background: rgba(45, 106, 79, 0.12); color: #2D6A4F;">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    </div>
    <div>
      <div class="stat-label">Confirmed on Altar</div>
      <div class="stat-val" style="color: #2D6A4F;">{{ $confirmedBookings }}</div>
      <div class="stat-sub">Successfully offered & dispatched</div>
    </div>
  </div>
</div>

<!-- Feature Toggle Banner for Prebuilt Pooja Packages -->
<div class="card" style="padding: 18px 24px; margin-bottom: 24px; background: #ffffff; border: 1.5px solid var(--line); border-radius: 14px; box-shadow: 0 4px 18px rgba(0,0,0,0.03);">
  <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px;">
    <div style="display: flex; align-items: center; gap: 14px;">
      <div style="width: 44px; height: 44px; border-radius: 10px; background: {{ ($orgSettings->is_pooja_packages_enabled ?? false) ? 'rgba(22, 163, 74, 0.12)' : 'rgba(234, 88, 12, 0.12)' }}; color: {{ ($orgSettings->is_pooja_packages_enabled ?? false) ? '#16a34a' : '#ea580c' }}; display: grid; place-items: center; flex-shrink: 0;">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="9" y1="9" x2="15" y2="15"/><line x1="15" y1="9" x2="9" y2="15"/></svg>
      </div>
      <div>
        <div style="display: flex; align-items: center; gap: 10px;">
          <h3 style="font-size: 15px; font-weight: 800; color: var(--deep); margin: 0;">Prebuilt Pooja Packages Directory Feature</h3>
          @if($orgSettings->is_pooja_packages_enabled ?? false)
            <span class="badge" style="background: #dcfce7; color: #166534; border: 1px solid #86efac;">ENABLED (Active on /pooja)</span>
          @else
            <span class="badge" style="background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5;">DISABLED (/pooja redirects to /pooja/book)</span>
          @endif
        </div>
        <p style="font-size: 12px; color: #7A4B29; margin: 3px 0 0;">
          @if($orgSettings->is_pooja_packages_enabled ?? false)
            Devotees can browse prebuilt packages at /pooja. You can turn this off anytime to only keep direct date & slot bookings.
          @else
            The public /pooja packages catalog is deactivated. Anyone visiting /pooja is automatically redirected to the sacred Pooja & Hawan booking form. Aarti and Hawan timetables remain fully active.
          @endif
        </p>
      </div>
    </div>

    <form action="{{ route('admin.poojas.togglePackages') }}" method="POST" style="margin: 0;">
      @csrf
      <input type="hidden" name="is_pooja_packages_enabled" value="{{ ($orgSettings->is_pooja_packages_enabled ?? false) ? '0' : '1' }}">
      <button type="submit" class="action-btn {{ ($orgSettings->is_pooja_packages_enabled ?? false) ? '' : 'primary' }}" style="font-size: 11px; padding: 8px 18px;">
        @if($orgSettings->is_pooja_packages_enabled ?? false)
          Turn Off Prebuilt Packages &times;
        @else
          Turn On Prebuilt Packages &check;
        @endif
      </button>
    </form>
  </div>
</div>

@if(!($orgSettings->is_pooja_packages_enabled ?? false))
  <!-- ==========================================
       AARTI & HAWAN TIMETABLE (FEATURED ON TOP)
       ========================================== -->
<!-- ========================================================
     Mandir Aarti & Hawan Timetable Management Card (CMS)
     Devotees can add, change, remove, or edit timings for Aarti
     ======================================================== -->
@php
  $normalList = (!empty($aartiNormalSlots) && is_array($aartiNormalSlots)) ? $aartiNormalSlots : [
    ['name' => 'प्रातः मंगला आरती', 'time' => '05:00 AM', 'desc' => 'कामदगिरि मुख्य गर्भगृह', 'is_live' => false],
    ['name' => 'ठाकुर जी शृंगार व बालभोग', 'time' => '07:30 AM', 'desc' => 'कामदगिरि मंदिर', 'is_live' => false],
    ['name' => 'राजभोग महाआरती', 'time' => '12:00 PM', 'desc' => 'अन्नकूट एवं भोग अर्पण', 'is_live' => false],
    ['name' => 'श्री कामदगिरि संध्या महाआरती', 'time' => '06:30 PM', 'desc' => 'रामघाट मन्दाकिनी पावन तट', 'is_live' => true],
    ['name' => 'शयन आरती', 'time' => '09:00 PM', 'desc' => 'कपाट विश्राम', 'is_live' => false],
  ];

  $amavasyaList = (!empty($aartiAmavasyaSlots) && is_array($aartiAmavasyaSlots)) ? $aartiAmavasyaSlots : [
    ['name' => 'परिक्रमा मंगला आरती', 'time' => '03:30 AM', 'desc' => 'प्रातः विशेष कामदगिरि परिक्रमा', 'is_live' => false],
    ['name' => 'अखण्ड वैदिक हवन', 'time' => '08:00 AM – 11:30 AM', 'desc' => 'ऋषिकुल यज्ञशाला', 'is_live' => false],
    ['name' => 'मन्दाकिनी महा दीपोत्सव आरती', 'time' => '07:00 PM', 'desc' => 'रामघाट 11,000+ दीप प्रज्ज्वलन', 'is_live' => true],
    ['name' => 'अखण्ड रात्रि महापूजा व जागरण', 'time' => '10:30 PM', 'desc' => 'अमावस्या मध्यरात्रि विशेष अनुष्ठान', 'is_live' => false],
    ['name' => 'भोर आरती व कपाट विश्राम', 'time' => '04:00 AM', 'desc' => 'पर्व समापन एवं आशीर्वाद', 'is_live' => false],
  ];
@endphp

<div class="cms-table-card" style="margin-top: 36px; border: 1.5px solid rgba(217, 93, 15, 0.35);">
  <div class="card-header" style="display: flex; align-items: center; justify-content: space-between; padding: 18px 24px; border-bottom: 1px solid var(--line); background: linear-gradient(to right, #FAF7F2, #FFF7ED); flex-wrap: wrap; gap: 12px;">
    <div style="display: flex; align-items: center; gap: 10px;">
      <span style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 10px; background: rgba(217, 93, 15, 0.15); color: #D95D0F;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg>
      </span>
      <div>
        <h2 style="font-size: 16px; font-weight: 800; color: var(--deep); margin: 0; font-family: var(--dev);">
          Mandir Daily Aarti & Hawan Timetable Management
        </h2>
        <span style="font-size: 11.5px; color: var(--brown);">
          Manage timings for Normal Days, Amavasya Mela Night, and daily Hawan strips displayed live to devotees.
        </span>
      </div>
    </div>

    <!-- Tab Buttons -->
    <div style="display: flex; gap: 8px;">
      <button type="button" id="tab-admin-normal" onclick="switchAdminAartiTab('normal')" class="filter-pill active" style="font-size: 11.5px; padding: 6px 14px;">
        1. Daily Aarti (Normal Days)
      </button>
      <button type="button" id="tab-admin-amavasya" onclick="switchAdminAartiTab('amavasya')" class="filter-pill" style="font-size: 11.5px; padding: 6px 14px;">
        2. Amavasya Melas
      </button>
      <button type="button" id="tab-admin-hawan" onclick="switchAdminAartiTab('hawan')" class="filter-pill" style="font-size: 11.5px; padding: 6px 14px;">
        3. Daily Vedic Hawan
      </button>
    </div>
  </div>

  <form action="{{ route('admin.poojas.updateAartiTimetable') }}" method="POST" style="padding: 24px;">
    @csrf

    <!-- PANE 1: Normal Days Aarti Slots -->
    <div id="pane-admin-normal" class="admin-aarti-pane">
      <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
        <div>
          <strong style="color: var(--deep); font-size: 14px; display: block;">Daily Routine Timetable (Normal Days)</strong>
          <span style="font-size: 11px; color: var(--brown);">These 5-6 primary altar aartis are displayed by default on the homepage timings strip.</span>
        </div>
        <button type="button" onclick="addNormalAartiRow()" class="action-btn" style="background: #FAF4ED; color: #B45309; border: 1.5px solid rgba(180,83,9,0.35); font-size: 11px; font-weight: 800; padding: 6px 14px;">
          + ADD NORMAL AARTI SLOT
        </button>
      </div>

      <div id="normalSlotsGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 14px; margin-bottom: 20px;">
        @foreach($normalList as $idx => $slot)
          <div class="normal-slot-card" style="background: #FAF7F2; border: 1.5px solid rgba(180, 83, 9, 0.2); border-radius: 12px; padding: 14px; position: relative;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
              <span class="slot-badge" style="font-size: 10.5px; font-weight: 800; color: #D95D0F; text-transform: uppercase;">SLOT #{{ $idx + 1 }}</span>
              <button type="button" onclick="removeSlotRow(this)" style="background: #fee2e2; border: 1px solid #f87171; color: #991b1b; font-size: 11px; font-weight: 700; border-radius: 4px; padding: 2px 8px; cursor: pointer;" title="Remove this slot">Remove &times;</button>
            </div>

            <label style="font-size: 10.5px; font-weight: 700; color: var(--deep); display: block; margin-bottom: 3px;">Aarti Name</label>
            <input type="text" name="aarti_normal_slots[{{ $idx }}][name]" value="{{ $slot['name'] ?? '' }}" class="form-input" style="font-size: 12.5px; margin-bottom: 8px; padding: 6px 8px;" placeholder="उदा. प्रातः मंगला आरती" required>

            <label style="font-size: 10.5px; font-weight: 700; color: var(--deep); display: block; margin-bottom: 3px;">Timing</label>
            <input type="text" name="aarti_normal_slots[{{ $idx }}][time]" value="{{ $slot['time'] ?? '' }}" class="form-input" style="font-size: 12.5px; margin-bottom: 8px; padding: 6px 8px;" placeholder="05:00 AM" required>

            <label style="font-size: 10.5px; font-weight: 700; color: var(--deep); display: block; margin-bottom: 3px;">Location / Description</label>
            <input type="text" name="aarti_normal_slots[{{ $idx }}][desc]" value="{{ $slot['desc'] ?? ($slot['description'] ?? '') }}" class="form-input" style="font-size: 12.5px; margin-bottom: 10px; padding: 6px 8px;" placeholder="कामदगिरि मुख्य गर्भगृह">

            <label style="display: flex; align-items: center; gap: 6px; font-size: 11px; color: #7A4B29; cursor: pointer; border-top: 1px dashed rgba(180,83,9,0.25); padding-top: 8px; margin: 0;">
              <input type="checkbox" name="aarti_normal_slots[{{ $idx }}][is_live]" value="1" {{ !empty($slot['is_live']) || !empty($slot['is_live_pulse']) ? 'checked' : '' }} style="accent-color: #D95D0F;">
              <span style="display: inline-flex; align-items: center; gap: 5px;"><span style="width: 8px; height: 8px; border-radius: 50%; background: #dc2626; display: inline-block;"></span>Live Pulse Highlight</span>
            </label>
          </div>
        @endforeach
      </div>
    </div>

    <!-- PANE 2: Amavasya Mela Aarti Slots -->
    <div id="pane-admin-amavasya" class="admin-aarti-pane" style="display: none;">
      <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
        <div>
          <strong style="color: var(--deep); font-size: 14px; display: block;">Amavasya Melas Schedule</strong>
          <span style="font-size: 11px; color: var(--brown);">Displayed when devotees toggle to the "अमावस्या एवं महापर्व" tab on the homepage strip.</span>
        </div>
        <button type="button" onclick="addAmavasyaAartiRow()" class="action-btn" style="background: #FAF4ED; color: #B45309; border: 1.5px solid rgba(180,83,9,0.35); font-size: 11px; font-weight: 800; padding: 6px 14px;">
          + ADD AMAVASYA AARTI SLOT
        </button>
      </div>

      <div id="amavasyaSlotsGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 14px; margin-bottom: 20px;">
        @foreach($amavasyaList as $idx => $slot)
          <div class="amavasya-slot-card" style="background: #FAF7F2; border: 1.5px solid rgba(180, 83, 9, 0.2); border-radius: 12px; padding: 14px; position: relative;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
              <span class="slot-badge" style="font-size: 10.5px; font-weight: 800; color: #B45309; text-transform: uppercase;">AMAVASYA SLOT #{{ $idx + 1 }}</span>
              <button type="button" onclick="removeSlotRow(this)" style="background: #fee2e2; border: 1px solid #f87171; color: #991b1b; font-size: 11px; font-weight: 700; border-radius: 4px; padding: 2px 8px; cursor: pointer;" title="Remove this slot">Remove &times;</button>
            </div>

            <label style="font-size: 10.5px; font-weight: 700; color: var(--deep); display: block; margin-bottom: 3px;">Aarti Name</label>
            <input type="text" name="aarti_amavasya_slots[{{ $idx }}][name]" value="{{ $slot['name'] ?? '' }}" class="form-input" style="font-size: 12.5px; margin-bottom: 8px; padding: 6px 8px;" placeholder="उदा. परिक्रमा मंगला आरती" required>

            <label style="font-size: 10.5px; font-weight: 700; color: var(--deep); display: block; margin-bottom: 3px;">Timing</label>
            <input type="text" name="aarti_amavasya_slots[{{ $idx }}][time]" value="{{ $slot['time'] ?? '' }}" class="form-input" style="font-size: 12.5px; margin-bottom: 8px; padding: 6px 8px;" placeholder="03:30 AM" required>

            <label style="font-size: 10.5px; font-weight: 700; color: var(--deep); display: block; margin-bottom: 3px;">Location / Description</label>
            <input type="text" name="aarti_amavasya_slots[{{ $idx }}][desc]" value="{{ $slot['desc'] ?? ($slot['description'] ?? '') }}" class="form-input" style="font-size: 12.5px; margin-bottom: 10px; padding: 6px 8px;" placeholder="प्रातः विशेष कामदगिरि परिक्रमा">

            <label style="display: flex; align-items: center; gap: 6px; font-size: 11px; color: #7A4B29; cursor: pointer; border-top: 1px dashed rgba(180,83,9,0.25); padding-top: 8px; margin: 0;">
              <input type="checkbox" name="aarti_amavasya_slots[{{ $idx }}][is_live]" value="1" {{ !empty($slot['is_live']) || !empty($slot['is_live_pulse']) ? 'checked' : '' }} style="accent-color: #D95D0F;">
              <span style="display: inline-flex; align-items: center; gap: 5px;"><span style="width: 8px; height: 8px; border-radius: 50%; background: #dc2626; display: inline-block;"></span>Live Pulse Highlight</span>
            </label>
          </div>
        @endforeach
      </div>
    </div>

    <!-- PANE 3: Hawan & Strip Configuration -->
    <div id="pane-admin-hawan" class="admin-aarti-pane" style="display: none;">
      <div style="background: #FFFDFC; border: 1px solid rgba(180, 83, 9, 0.2); border-radius: 12px; padding: 18px; margin-bottom: 20px;">
        <h4 style="font-size: 13.5px; font-weight: 700; color: var(--deep); margin: 0 0 14px;">
          Aarti Section Headers & Hawan Schedule Banner Parameters
        </h4>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 14px;">
          <div>
            <label class="form-label">AARTI SECTION MAIN TITLE</label>
            <input type="text" name="aarti_strip_title" value="{{ $aartiSettings['aarti_strip_title'] ?? 'नित्य आरती व दर्शन समय चक्र • Kamadgiri Dham Timings' }}" class="form-input">
          </div>
          <div>
            <label class="form-label">AARTI SECTION SUBTITLE</label>
            <input type="text" name="aarti_strip_subtitle" value="{{ $aartiSettings['aarti_strip_subtitle'] ?? 'चित्रकूट धाम में नित्य महाआरती व गर्भगृह दर्शन समय सारणी' }}" class="form-input">
          </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px;">
          <div>
            <label class="form-label">HAWAN BANNER TITLE</label>
            <input type="text" name="aarti_hawan_title" value="{{ $aartiSettings['aarti_hawan_title'] ?? 'दैनिक वैदिक हवन एवं महायज्ञ समय चक्र' }}" class="form-input">
          </div>
          <div>
            <label class="form-label">HAWAN TIMING</label>
            <input type="text" name="aarti_hawan_time" value="{{ $aartiSettings['aarti_hawan_time'] ?? ($aartiSettings['aarti_hawan_timing'] ?? '07:30 AM – 09:30 AM') }}" class="form-input">
          </div>
          <div>
            <label class="form-label">HAWAN LOCATION</label>
            <input type="text" name="aarti_hawan_place" value="{{ $aartiSettings['aarti_hawan_place'] ?? ($aartiSettings['aarti_hawan_location'] ?? 'ऋषिकुल यज्ञशाला व मन्दाकिनी तट') }}" class="form-input">
          </div>
          <div>
            <label class="form-label">HAWAN SUMMARY NOTE</label>
            <input type="text" name="aarti_hawan_desc" value="{{ $aartiSettings['aarti_hawan_desc'] ?? 'विश्व शान्ति, सर्वकल्याण एवं मनोकामना सिद्धि हेतु नित्य वैदिक आहुति।' }}" class="form-input">
          </div>
        </div>
      </div>
    </div>

    <!-- Submit Action Bar -->
    <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px solid var(--line); padding-top: 18px; flex-wrap: wrap; gap: 12px;">
      <span style="font-size: 11.5px; color: var(--brown);">
        Changes take effect immediately on the homepage timings strip and Amavasya schedule.
      </span>
      <button type="submit" class="action-btn primary" style="font-size: 12px; font-weight: 800; padding: 10px 24px; box-shadow: 0 4px 14px rgba(217, 93, 15, 0.35);">
        SAVE AARTI & HAWAN TIMETABLE &rarr;
      </button>
    </div>
  </form>
</div>

  <!-- ==========================================
       MANDIR DAILY TIME SLOTS (FEATURED NEXT)
       ========================================== -->
<!-- Mandir Daily Available Time Slots Directory Card -->
<div class="cms-table-card" style="margin-top: 36px;">
  <div class="card-header" style="display: flex; align-items: center; justify-content: space-between; padding: 18px 24px; border-bottom: 1px solid var(--line); background: #FAF7F2;">
    <div style="display: flex; align-items: center; gap: 10px;">
      <span style="display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 8px; background: rgba(8, 108, 96, 0.12); color: #086c60;">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
      </span>
      <div>
        <h2 style="font-size: 15px; font-weight: 800; color: var(--deep); margin: 0;">Mandir Daily Time Slots Management</h2>
        <span style="font-size: 11px; color: var(--brown);">Active slots are offered to devotees on the online booking form with 2-day advance restriction</span>
      </div>
    </div>
    <button type="button" onclick="openSlotModal()" class="action-btn" style="background: #086c60; color: #fff; font-size: 11px; font-weight: 800; padding: 7px 16px;">
      + NEW TIME SLOT
    </button>
  </div>

  <table class="cms-table" style="width: 100%; border-collapse: collapse;">
    <thead>
      <tr>
        <th style="width: 35%;">Slot Name & Description</th>
        <th style="width: 20%;">Daily Timing (Start - End)</th>
        <th style="width: 15%;">Applies To</th>
        <th style="width: 10%;">Max Devotees</th>
        <th style="width: 10%;">Status</th>
        <th style="width: 10%; text-align: right;">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($timeSlots as $slot)
        <tr style="transition: background .2s ease;">
          <td>
            <strong style="color: var(--deep); font-size: 13.5px; font-family: var(--dev); font-weight: 700;">{{ $slot->slot_name }}</strong>
          </td>
          <td>
            <div style="display: inline-flex; align-items: center; gap: 6px; background: #FAF7F2; border: 1px solid var(--line); padding: 4px 10px; border-radius: 8px; font-size: 12.5px; font-weight: 700; color: #B45309;">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
              <span>{{ $slot->formatted_range }}</span>
            </div>
          </td>
          <td>
            @if($slot->service_type === 'hawan')
              <span class="badge" style="background: #ffedd5; color: #c2410c; border: 1px solid rgba(194, 65, 12, 0.25);">Hawan / Yagya</span>
            @elseif($slot->service_type === 'pooja')
              <span class="badge" style="background: #fef3c7; color: #b45309; border: 1px solid rgba(180, 83, 9, 0.25);">Vedic Pooja</span>
            @else
              <span class="badge" style="background: #e0f2fe; color: #0369a1; border: 1px solid rgba(3, 105, 161, 0.25);">All Rituals</span>
            @endif
          </td>
          <td>
            <span style="font-weight: 700; font-size: 13px; color: var(--deep);">{{ $slot->max_capacity }} Devotees</span>
          </td>
          <td>
            @if($slot->is_active)
              <span class="badge" style="background: #dcfce7; color: #166534; border: 1px solid rgba(22, 101, 52, 0.25);">
                <span style="width: 6px; height: 6px; border-radius: 9999px; background: #16a34a;"></span>
                ACTIVE
              </span>
            @else
              <span class="badge" style="background: #fee2e2; color: #991b1b; border: 1px solid rgba(153, 27, 27, 0.2);">
                PAUSED
              </span>
            @endif
          </td>
          <td style="text-align: right;">
            <div style="display: inline-flex; align-items: center; gap: 6px;">
              <button type="button" onclick='editSlotModal(@json($slot))' class="action-btn" style="background: #FAF4ED; color: #B45309; border-color: rgba(180, 83, 9, 0.25); padding: 5px 12px; font-size: 11px;">
                Edit
              </button>
              <form action="{{ route('admin.poojaSlots.delete', $slot->id) }}" method="POST" onsubmit="return confirm('Delete this time slot? Devotees will no longer be able to book during this time.')" style="margin: 0; display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="action-btn" style="background: #fee2e2; color: #991b1b; border-color: rgba(153, 27, 27, 0.2); padding: 5px 8px; font-size: 11px;">
                  &times;
                </button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" style="padding: 40px; text-align: center; color: var(--brown);">
            No Mandir time slots found. Click "+ NEW TIME SLOT" to establish temple schedules.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

  <!-- ==========================================
       PREBUILT PACKAGES DIRECTORY (DEACTIVATED)
       ========================================== -->
  <details class="card" style="margin-top: 36px; padding: 18px 24px; border: 1.5px dashed var(--line); border-radius: 14px; background: #FAF7F2;">
    <summary style="font-size: 14px; font-weight: 800; color: var(--deep); cursor: pointer; display: flex; align-items: center; justify-content: space-between; user-select: none;">
      <div style="display: flex; align-items: center; gap: 10px;">
        <span style="display: inline-flex; color: #7A4B29;"><x-icon name="package" size="16" /></span>
        <span>Prebuilt Packages Directory (Currently Deactivated — Click to View/Edit {{ $poojas->count() }} Packages)</span>
      </div>
      <span class="badge" style="background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5;">Turned Off</span>
    </summary>
    <div style="margin-top: 20px;">
      <div style="background: #fff; padding: 12px 16px; border-radius: 8px; border: 1px solid var(--line); margin-bottom: 16px; font-size: 12px; color: #7A4B29;">
        <strong>Notice:</strong> The /pooja public page is disabled and redirects to the booking form. Devotee bookings made directly by date & time slot continue to function without packages. You can still modify or add packages below if you ever plan to turn this feature back on.
      </div>
<!-- Toolbar: Filter Pills & Instant Live Search -->
<div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 14px; margin-bottom: 20px;">
  <div style="display: flex; align-items: center; gap: 8px;">
    <button type="button" class="filter-pill active" onclick="filterByStatus('all', this)">All Offerings ({{ $poojas->count() }})</button>
    <button type="button" class="filter-pill" onclick="filterByStatus('active', this)">Active ({{ $poojas->where('is_active', true)->count() }})</button>
    <button type="button" class="filter-pill" onclick="filterByStatus('paused', this)">Paused ({{ $poojas->where('is_active', false)->count() }})</button>
  </div>

  <div style="position: relative; width: 100%; max-width: 340px;">
    <input type="text" id="poojaSearchInput" onkeyup="searchPoojas(this.value)" placeholder="Search poojas, timings, or slug..." class="form-input" style="padding-left: 36px; padding-top: 8px; padding-bottom: 8px; font-size: 12.5px; border-radius: 9999px;">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: var(--brown); opacity: 0.6;"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
  </div>
</div>

<!-- Table Card -->
<div class="cms-table-card">
  <div class="card-header" style="display: flex; align-items: center; justify-content: space-between; padding: 18px 24px; border-bottom: 1px solid var(--line); background: #FAF7F2;">
    <div style="display: flex; align-items: center; gap: 10px;">
      <span style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 8px; background: rgba(217, 93, 15, 0.12); color: #D95D0F;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2c-.3 0-.6.2-.7.5C10 5.4 8 8.6 8 12c0 2.2 1.8 4 4 4s4-1.8 4-4c0-3.4-2-6.6-3.3-9.5-.1-.3-.4-.5-.7-.5zm0 18c-3.3 0-6-2.7-6-6 0-3.8 2.5-7.7 4.3-10.4.5-.7 1.6-.7 2.1 0C14.2 6.3 16.7 10.2 16.7 14c0 3.3-2.7 6-6 6z"/></svg>
      </span>
      <div>
        <h2 style="font-size: 15px; font-weight: 800; color: var(--deep); margin: 0;">Sacred Offerings & Timetable Directory</h2>
        <span style="font-size: 11px; color: var(--brown);">Click 'Edit' to update pricing, duration, assigned Acharya, and time slots</span>
      </div>
    </div>
    <span id="resultsCountBadge" style="font-size: 11.5px; font-weight: 700; color: var(--brown); background: #fff; padding: 4px 12px; border-radius: 9999px; border: 1px solid var(--line);">
      Showing {{ $poojas->count() }} packages
    </span>
  </div>

  <table class="cms-table" style="width: 100%; border-collapse: collapse;">
    <thead>
      <tr>
        <th style="width: 32%;">Pooja / Seva Details</th>
        <th style="width: 14%;">Dakshina (₹)</th>
        <th style="width: 18%;">Duration & Priest</th>
        <th style="width: 16%;">Available Timings</th>
        <th style="width: 10%;">Status</th>
        <th style="width: 10%; text-align: right;">Actions</th>
      </tr>
    </thead>
    <tbody id="poojasTableBody">
      @forelse($poojas as $pooja)
        <tr class="pooja-row" data-name="{{ strtolower($pooja->name . ' ' . $pooja->slug . ' ' . ($pooja->tagline ?? '')) }}" data-status="{{ $pooja->is_active ? 'active' : 'paused' }}" style="transition: background .2s ease;">
          <td>
            <div style="display: flex; align-items: center; gap: 14px;">
              <div style="position: relative; width: 54px; height: 54px; border-radius: 10px; overflow: hidden; border: 1.5px solid var(--line); flex-shrink: 0; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                <img src="{{ $pooja->image_url ?: '/images/chitrakoot-hero.webp' }}" onerror="this.src='/images/chitrakoot-hero.webp'" style="width: 100%; height: 100%; object-fit: cover;" alt="">
              </div>
              <div>
                <strong style="color: var(--deep); font-size: 14px; font-family: var(--dev); font-weight: 700; display: block; line-height: 1.3;">
                  {{ $pooja->name }}
                </strong>
                <span style="font-size: 11.5px; color: var(--brown); display: block; margin-top: 2px;">
                  {{ $pooja->tagline ?: 'Sacred Vedic Sankalp' }}
                </span>
                <span style="display: inline-block; font-size: 10px; color: #7A4B29; background: #FAF4ED; padding: 1px 7px; border-radius: 4px; margin-top: 4px; font-family: monospace;">
                  /pooja/{{ $pooja->slug }}
                </span>
              </div>
            </div>
          </td>
          <td>
            <div style="display: inline-flex; align-items: baseline; gap: 4px; background: rgba(217, 93, 15, 0.08); padding: 4px 10px; border-radius: 8px; border: 1px solid rgba(217, 93, 15, 0.2);">
              <span style="font-size: 14.5px; font-weight: 800; color: #D95D0F;">₹{{ number_format($pooja->price, 0) }}</span>
            </div>
          </td>
          <td>
            <div style="display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 700; color: var(--deep); margin-bottom: 2px;">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
              <span>{{ $pooja->duration }}</span>
            </div>
            <span style="font-size: 11px; color: var(--brown); display: block;">
              {{ $pooja->priest_assigned }}
            </span>
          </td>
          <td>
            @if(!empty($pooja->available_timings))
              <div style="display: flex; flex-direction: column; gap: 4px;">
                @foreach(array_slice($pooja->available_timings, 0, 2) as $slot)
                  <span style="background: #FAF7F2; border: 1px solid var(--line); font-size: 10.5px; padding: 2px 7px; border-radius: 6px; display: inline-block; white-space: nowrap; font-weight: 600; color: #2B1A12;">
                    {{ $slot }}
                  </span>
                @endforeach
                @if(count($pooja->available_timings) > 2)
                  <span style="font-size: 10px; color: var(--brown); font-style: italic;">+{{ count($pooja->available_timings) - 2 }} more slots</span>
                @endif
              </div>
            @else
              <span style="color: #999; font-size: 11px;">Custom Slot</span>
            @endif
          </td>
          <td>
            @if($pooja->is_active)
              <span class="badge" style="background: #dcfce7; color: #166534; border: 1px solid rgba(22, 101, 52, 0.25);">
                <span style="width: 6px; height: 6px; border-radius: 9999px; background: #16a34a;"></span>
                ACTIVE
              </span>
            @else
              <span class="badge" style="background: #fee2e2; color: #991b1b; border: 1px solid rgba(153, 27, 27, 0.2);">
                PAUSED
              </span>
            @endif
          </td>
          <td style="text-align: right;">
            <div style="display: inline-flex; align-items: center; gap: 6px;">
              <button type="button" 
                      onclick='editPoojaModal(@json($pooja))' 
                      class="action-btn" 
                      style="background: #FAF4ED; color: #B45309; border-color: rgba(180, 83, 9, 0.25); padding: 5px 12px; font-size: 11px;"
                      title="Edit Pooja details">
                Edit
              </button>
              <a href="{{ route('pooja.show', $pooja->slug) }}" target="_blank" class="action-btn" style="padding: 5px 8px; font-size: 11px;" title="View public page">
                &nearr;
              </a>
              <form action="{{ route('admin.poojas.delete', $pooja->id) }}" method="POST" onsubmit="return confirm('Delete this Pooja service permanently? Devotee bookings referencing it will remain archived.')" style="margin: 0; display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="action-btn" style="background: #fee2e2; color: #991b1b; border-color: rgba(153, 27, 27, 0.2); padding: 5px 8px; font-size: 11px;" title="Delete Service">
                  &times;
                </button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" style="padding: 40px; text-align: center; color: var(--brown);">
            No Pooja services found. Click "Add New Pooja Service" to register the first ritual.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
    </div>
  </details>

@else
  <!-- ==========================================
       PREBUILT PACKAGES DIRECTORY (ACTIVE)
       ========================================== -->
<!-- Toolbar: Filter Pills & Instant Live Search -->
<div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 14px; margin-bottom: 20px;">
  <div style="display: flex; align-items: center; gap: 8px;">
    <button type="button" class="filter-pill active" onclick="filterByStatus('all', this)">All Offerings ({{ $poojas->count() }})</button>
    <button type="button" class="filter-pill" onclick="filterByStatus('active', this)">Active ({{ $poojas->where('is_active', true)->count() }})</button>
    <button type="button" class="filter-pill" onclick="filterByStatus('paused', this)">Paused ({{ $poojas->where('is_active', false)->count() }})</button>
  </div>

  <div style="position: relative; width: 100%; max-width: 340px;">
    <input type="text" id="poojaSearchInput" onkeyup="searchPoojas(this.value)" placeholder="Search poojas, timings, or slug..." class="form-input" style="padding-left: 36px; padding-top: 8px; padding-bottom: 8px; font-size: 12.5px; border-radius: 9999px;">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: var(--brown); opacity: 0.6;"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
  </div>
</div>

<!-- Table Card -->
<div class="cms-table-card">
  <div class="card-header" style="display: flex; align-items: center; justify-content: space-between; padding: 18px 24px; border-bottom: 1px solid var(--line); background: #FAF7F2;">
    <div style="display: flex; align-items: center; gap: 10px;">
      <span style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 8px; background: rgba(217, 93, 15, 0.12); color: #D95D0F;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2c-.3 0-.6.2-.7.5C10 5.4 8 8.6 8 12c0 2.2 1.8 4 4 4s4-1.8 4-4c0-3.4-2-6.6-3.3-9.5-.1-.3-.4-.5-.7-.5zm0 18c-3.3 0-6-2.7-6-6 0-3.8 2.5-7.7 4.3-10.4.5-.7 1.6-.7 2.1 0C14.2 6.3 16.7 10.2 16.7 14c0 3.3-2.7 6-6 6z"/></svg>
      </span>
      <div>
        <h2 style="font-size: 15px; font-weight: 800; color: var(--deep); margin: 0;">Sacred Offerings & Timetable Directory</h2>
        <span style="font-size: 11px; color: var(--brown);">Click 'Edit' to update pricing, duration, assigned Acharya, and time slots</span>
      </div>
    </div>
    <span id="resultsCountBadge" style="font-size: 11.5px; font-weight: 700; color: var(--brown); background: #fff; padding: 4px 12px; border-radius: 9999px; border: 1px solid var(--line);">
      Showing {{ $poojas->count() }} packages
    </span>
  </div>

  <table class="cms-table" style="width: 100%; border-collapse: collapse;">
    <thead>
      <tr>
        <th style="width: 32%;">Pooja / Seva Details</th>
        <th style="width: 14%;">Dakshina (₹)</th>
        <th style="width: 18%;">Duration & Priest</th>
        <th style="width: 16%;">Available Timings</th>
        <th style="width: 10%;">Status</th>
        <th style="width: 10%; text-align: right;">Actions</th>
      </tr>
    </thead>
    <tbody id="poojasTableBody">
      @forelse($poojas as $pooja)
        <tr class="pooja-row" data-name="{{ strtolower($pooja->name . ' ' . $pooja->slug . ' ' . ($pooja->tagline ?? '')) }}" data-status="{{ $pooja->is_active ? 'active' : 'paused' }}" style="transition: background .2s ease;">
          <td>
            <div style="display: flex; align-items: center; gap: 14px;">
              <div style="position: relative; width: 54px; height: 54px; border-radius: 10px; overflow: hidden; border: 1.5px solid var(--line); flex-shrink: 0; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                <img src="{{ $pooja->image_url ?: '/images/chitrakoot-hero.webp' }}" onerror="this.src='/images/chitrakoot-hero.webp'" style="width: 100%; height: 100%; object-fit: cover;" alt="">
              </div>
              <div>
                <strong style="color: var(--deep); font-size: 14px; font-family: var(--dev); font-weight: 700; display: block; line-height: 1.3;">
                  {{ $pooja->name }}
                </strong>
                <span style="font-size: 11.5px; color: var(--brown); display: block; margin-top: 2px;">
                  {{ $pooja->tagline ?: 'Sacred Vedic Sankalp' }}
                </span>
                <span style="display: inline-block; font-size: 10px; color: #7A4B29; background: #FAF4ED; padding: 1px 7px; border-radius: 4px; margin-top: 4px; font-family: monospace;">
                  /pooja/{{ $pooja->slug }}
                </span>
              </div>
            </div>
          </td>
          <td>
            <div style="display: inline-flex; align-items: baseline; gap: 4px; background: rgba(217, 93, 15, 0.08); padding: 4px 10px; border-radius: 8px; border: 1px solid rgba(217, 93, 15, 0.2);">
              <span style="font-size: 14.5px; font-weight: 800; color: #D95D0F;">₹{{ number_format($pooja->price, 0) }}</span>
            </div>
          </td>
          <td>
            <div style="display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 700; color: var(--deep); margin-bottom: 2px;">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
              <span>{{ $pooja->duration }}</span>
            </div>
            <span style="font-size: 11px; color: var(--brown); display: block;">
              {{ $pooja->priest_assigned }}
            </span>
          </td>
          <td>
            @if(!empty($pooja->available_timings))
              <div style="display: flex; flex-direction: column; gap: 4px;">
                @foreach(array_slice($pooja->available_timings, 0, 2) as $slot)
                  <span style="background: #FAF7F2; border: 1px solid var(--line); font-size: 10.5px; padding: 2px 7px; border-radius: 6px; display: inline-block; white-space: nowrap; font-weight: 600; color: #2B1A12;">
                    {{ $slot }}
                  </span>
                @endforeach
                @if(count($pooja->available_timings) > 2)
                  <span style="font-size: 10px; color: var(--brown); font-style: italic;">+{{ count($pooja->available_timings) - 2 }} more slots</span>
                @endif
              </div>
            @else
              <span style="color: #999; font-size: 11px;">Custom Slot</span>
            @endif
          </td>
          <td>
            @if($pooja->is_active)
              <span class="badge" style="background: #dcfce7; color: #166534; border: 1px solid rgba(22, 101, 52, 0.25);">
                <span style="width: 6px; height: 6px; border-radius: 9999px; background: #16a34a;"></span>
                ACTIVE
              </span>
            @else
              <span class="badge" style="background: #fee2e2; color: #991b1b; border: 1px solid rgba(153, 27, 27, 0.2);">
                PAUSED
              </span>
            @endif
          </td>
          <td style="text-align: right;">
            <div style="display: inline-flex; align-items: center; gap: 6px;">
              <button type="button" 
                      onclick='editPoojaModal(@json($pooja))' 
                      class="action-btn" 
                      style="background: #FAF4ED; color: #B45309; border-color: rgba(180, 83, 9, 0.25); padding: 5px 12px; font-size: 11px;"
                      title="Edit Pooja details">
                Edit
              </button>
              <a href="{{ route('pooja.show', $pooja->slug) }}" target="_blank" class="action-btn" style="padding: 5px 8px; font-size: 11px;" title="View public page">
                &nearr;
              </a>
              <form action="{{ route('admin.poojas.delete', $pooja->id) }}" method="POST" onsubmit="return confirm('Delete this Pooja service permanently? Devotee bookings referencing it will remain archived.')" style="margin: 0; display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="action-btn" style="background: #fee2e2; color: #991b1b; border-color: rgba(153, 27, 27, 0.2); padding: 5px 8px; font-size: 11px;" title="Delete Service">
                  &times;
                </button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" style="padding: 40px; text-align: center; color: var(--brown);">
            No Pooja services found. Click "Add New Pooja Service" to register the first ritual.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

  <!-- ==========================================
       AARTI & HAWAN TIMETABLE
       ========================================== -->
<!-- ========================================================
     Mandir Aarti & Hawan Timetable Management Card (CMS)
     Devotees can add, change, remove, or edit timings for Aarti
     ======================================================== -->
@php
  $normalList = (!empty($aartiNormalSlots) && is_array($aartiNormalSlots)) ? $aartiNormalSlots : [
    ['name' => 'प्रातः मंगला आरती', 'time' => '05:00 AM', 'desc' => 'कामदगिरि मुख्य गर्भगृह', 'is_live' => false],
    ['name' => 'ठाकुर जी शृंगार व बालभोग', 'time' => '07:30 AM', 'desc' => 'कामदगिरि मंदिर', 'is_live' => false],
    ['name' => 'राजभोग महाआरती', 'time' => '12:00 PM', 'desc' => 'अन्नकूट एवं भोग अर्पण', 'is_live' => false],
    ['name' => 'श्री कामदगिरि संध्या महाआरती', 'time' => '06:30 PM', 'desc' => 'रामघाट मन्दाकिनी पावन तट', 'is_live' => true],
    ['name' => 'शयन आरती', 'time' => '09:00 PM', 'desc' => 'कपाट विश्राम', 'is_live' => false],
  ];

  $amavasyaList = (!empty($aartiAmavasyaSlots) && is_array($aartiAmavasyaSlots)) ? $aartiAmavasyaSlots : [
    ['name' => 'परिक्रमा मंगला आरती', 'time' => '03:30 AM', 'desc' => 'प्रातः विशेष कामदगिरि परिक्रमा', 'is_live' => false],
    ['name' => 'अखण्ड वैदिक हवन', 'time' => '08:00 AM – 11:30 AM', 'desc' => 'ऋषिकुल यज्ञशाला', 'is_live' => false],
    ['name' => 'मन्दाकिनी महा दीपोत्सव आरती', 'time' => '07:00 PM', 'desc' => 'रामघाट 11,000+ दीप प्रज्ज्वलन', 'is_live' => true],
    ['name' => 'अखण्ड रात्रि महापूजा व जागरण', 'time' => '10:30 PM', 'desc' => 'अमावस्या मध्यरात्रि विशेष अनुष्ठान', 'is_live' => false],
    ['name' => 'भोर आरती व कपाट विश्राम', 'time' => '04:00 AM', 'desc' => 'पर्व समापन एवं आशीर्वाद', 'is_live' => false],
  ];
@endphp

<div class="cms-table-card" style="margin-top: 36px; border: 1.5px solid rgba(217, 93, 15, 0.35);">
  <div class="card-header" style="display: flex; align-items: center; justify-content: space-between; padding: 18px 24px; border-bottom: 1px solid var(--line); background: linear-gradient(to right, #FAF7F2, #FFF7ED); flex-wrap: wrap; gap: 12px;">
    <div style="display: flex; align-items: center; gap: 10px;">
      <span style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 10px; background: rgba(217, 93, 15, 0.15); color: #D95D0F;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg>
      </span>
      <div>
        <h2 style="font-size: 16px; font-weight: 800; color: var(--deep); margin: 0; font-family: var(--dev);">
          Mandir Daily Aarti & Hawan Timetable Management
        </h2>
        <span style="font-size: 11.5px; color: var(--brown);">
          Manage timings for Normal Days, Amavasya Mela Night, and daily Hawan strips displayed live to devotees.
        </span>
      </div>
    </div>

    <!-- Tab Buttons -->
    <div style="display: flex; gap: 8px;">
      <button type="button" id="tab-admin-normal" onclick="switchAdminAartiTab('normal')" class="filter-pill active" style="font-size: 11.5px; padding: 6px 14px;">
        1. Daily Aarti (Normal Days)
      </button>
      <button type="button" id="tab-admin-amavasya" onclick="switchAdminAartiTab('amavasya')" class="filter-pill" style="font-size: 11.5px; padding: 6px 14px;">
        2. Amavasya Melas
      </button>
      <button type="button" id="tab-admin-hawan" onclick="switchAdminAartiTab('hawan')" class="filter-pill" style="font-size: 11.5px; padding: 6px 14px;">
        3. Daily Vedic Hawan
      </button>
    </div>
  </div>

  <form action="{{ route('admin.poojas.updateAartiTimetable') }}" method="POST" style="padding: 24px;">
    @csrf

    <!-- PANE 1: Normal Days Aarti Slots -->
    <div id="pane-admin-normal" class="admin-aarti-pane">
      <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
        <div>
          <strong style="color: var(--deep); font-size: 14px; display: block;">Daily Routine Timetable (Normal Days)</strong>
          <span style="font-size: 11px; color: var(--brown);">These 5-6 primary altar aartis are displayed by default on the homepage timings strip.</span>
        </div>
        <button type="button" onclick="addNormalAartiRow()" class="action-btn" style="background: #FAF4ED; color: #B45309; border: 1.5px solid rgba(180,83,9,0.35); font-size: 11px; font-weight: 800; padding: 6px 14px;">
          + ADD NORMAL AARTI SLOT
        </button>
      </div>

      <div id="normalSlotsGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 14px; margin-bottom: 20px;">
        @foreach($normalList as $idx => $slot)
          <div class="normal-slot-card" style="background: #FAF7F2; border: 1.5px solid rgba(180, 83, 9, 0.2); border-radius: 12px; padding: 14px; position: relative;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
              <span class="slot-badge" style="font-size: 10.5px; font-weight: 800; color: #D95D0F; text-transform: uppercase;">SLOT #{{ $idx + 1 }}</span>
              <button type="button" onclick="removeSlotRow(this)" style="background: #fee2e2; border: 1px solid #f87171; color: #991b1b; font-size: 11px; font-weight: 700; border-radius: 4px; padding: 2px 8px; cursor: pointer;" title="Remove this slot">Remove &times;</button>
            </div>

            <label style="font-size: 10.5px; font-weight: 700; color: var(--deep); display: block; margin-bottom: 3px;">Aarti Name</label>
            <input type="text" name="aarti_normal_slots[{{ $idx }}][name]" value="{{ $slot['name'] ?? '' }}" class="form-input" style="font-size: 12.5px; margin-bottom: 8px; padding: 6px 8px;" placeholder="उदा. प्रातः मंगला आरती" required>

            <label style="font-size: 10.5px; font-weight: 700; color: var(--deep); display: block; margin-bottom: 3px;">Timing</label>
            <input type="text" name="aarti_normal_slots[{{ $idx }}][time]" value="{{ $slot['time'] ?? '' }}" class="form-input" style="font-size: 12.5px; margin-bottom: 8px; padding: 6px 8px;" placeholder="05:00 AM" required>

            <label style="font-size: 10.5px; font-weight: 700; color: var(--deep); display: block; margin-bottom: 3px;">Location / Description</label>
            <input type="text" name="aarti_normal_slots[{{ $idx }}][desc]" value="{{ $slot['desc'] ?? ($slot['description'] ?? '') }}" class="form-input" style="font-size: 12.5px; margin-bottom: 10px; padding: 6px 8px;" placeholder="कामदगिरि मुख्य गर्भगृह">

            <label style="display: flex; align-items: center; gap: 6px; font-size: 11px; color: #7A4B29; cursor: pointer; border-top: 1px dashed rgba(180,83,9,0.25); padding-top: 8px; margin: 0;">
              <input type="checkbox" name="aarti_normal_slots[{{ $idx }}][is_live]" value="1" {{ !empty($slot['is_live']) || !empty($slot['is_live_pulse']) ? 'checked' : '' }} style="accent-color: #D95D0F;">
              <span style="display: inline-flex; align-items: center; gap: 5px;"><span style="width: 8px; height: 8px; border-radius: 50%; background: #dc2626; display: inline-block;"></span>Live Pulse Highlight</span>
            </label>
          </div>
        @endforeach
      </div>
    </div>

    <!-- PANE 2: Amavasya Mela Aarti Slots -->
    <div id="pane-admin-amavasya" class="admin-aarti-pane" style="display: none;">
      <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
        <div>
          <strong style="color: var(--deep); font-size: 14px; display: block;">Amavasya Melas Schedule</strong>
          <span style="font-size: 11px; color: var(--brown);">Displayed when devotees toggle to the "अमावस्या एवं महापर्व" tab on the homepage strip.</span>
        </div>
        <button type="button" onclick="addAmavasyaAartiRow()" class="action-btn" style="background: #FAF4ED; color: #B45309; border: 1.5px solid rgba(180,83,9,0.35); font-size: 11px; font-weight: 800; padding: 6px 14px;">
          + ADD AMAVASYA AARTI SLOT
        </button>
      </div>

      <div id="amavasyaSlotsGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 14px; margin-bottom: 20px;">
        @foreach($amavasyaList as $idx => $slot)
          <div class="amavasya-slot-card" style="background: #FAF7F2; border: 1.5px solid rgba(180, 83, 9, 0.2); border-radius: 12px; padding: 14px; position: relative;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
              <span class="slot-badge" style="font-size: 10.5px; font-weight: 800; color: #B45309; text-transform: uppercase;">AMAVASYA SLOT #{{ $idx + 1 }}</span>
              <button type="button" onclick="removeSlotRow(this)" style="background: #fee2e2; border: 1px solid #f87171; color: #991b1b; font-size: 11px; font-weight: 700; border-radius: 4px; padding: 2px 8px; cursor: pointer;" title="Remove this slot">Remove &times;</button>
            </div>

            <label style="font-size: 10.5px; font-weight: 700; color: var(--deep); display: block; margin-bottom: 3px;">Aarti Name</label>
            <input type="text" name="aarti_amavasya_slots[{{ $idx }}][name]" value="{{ $slot['name'] ?? '' }}" class="form-input" style="font-size: 12.5px; margin-bottom: 8px; padding: 6px 8px;" placeholder="उदा. परिक्रमा मंगला आरती" required>

            <label style="font-size: 10.5px; font-weight: 700; color: var(--deep); display: block; margin-bottom: 3px;">Timing</label>
            <input type="text" name="aarti_amavasya_slots[{{ $idx }}][time]" value="{{ $slot['time'] ?? '' }}" class="form-input" style="font-size: 12.5px; margin-bottom: 8px; padding: 6px 8px;" placeholder="03:30 AM" required>

            <label style="font-size: 10.5px; font-weight: 700; color: var(--deep); display: block; margin-bottom: 3px;">Location / Description</label>
            <input type="text" name="aarti_amavasya_slots[{{ $idx }}][desc]" value="{{ $slot['desc'] ?? ($slot['description'] ?? '') }}" class="form-input" style="font-size: 12.5px; margin-bottom: 10px; padding: 6px 8px;" placeholder="प्रातः विशेष कामदगिरि परिक्रमा">

            <label style="display: flex; align-items: center; gap: 6px; font-size: 11px; color: #7A4B29; cursor: pointer; border-top: 1px dashed rgba(180,83,9,0.25); padding-top: 8px; margin: 0;">
              <input type="checkbox" name="aarti_amavasya_slots[{{ $idx }}][is_live]" value="1" {{ !empty($slot['is_live']) || !empty($slot['is_live_pulse']) ? 'checked' : '' }} style="accent-color: #D95D0F;">
              <span style="display: inline-flex; align-items: center; gap: 5px;"><span style="width: 8px; height: 8px; border-radius: 50%; background: #dc2626; display: inline-block;"></span>Live Pulse Highlight</span>
            </label>
          </div>
        @endforeach
      </div>
    </div>

    <!-- PANE 3: Hawan & Strip Configuration -->
    <div id="pane-admin-hawan" class="admin-aarti-pane" style="display: none;">
      <div style="background: #FFFDFC; border: 1px solid rgba(180, 83, 9, 0.2); border-radius: 12px; padding: 18px; margin-bottom: 20px;">
        <h4 style="font-size: 13.5px; font-weight: 700; color: var(--deep); margin: 0 0 14px;">
          Aarti Section Headers & Hawan Schedule Banner Parameters
        </h4>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 14px;">
          <div>
            <label class="form-label">AARTI SECTION MAIN TITLE</label>
            <input type="text" name="aarti_strip_title" value="{{ $aartiSettings['aarti_strip_title'] ?? 'नित्य आरती व दर्शन समय चक्र • Kamadgiri Dham Timings' }}" class="form-input">
          </div>
          <div>
            <label class="form-label">AARTI SECTION SUBTITLE</label>
            <input type="text" name="aarti_strip_subtitle" value="{{ $aartiSettings['aarti_strip_subtitle'] ?? 'चित्रकूट धाम में नित्य महाआरती व गर्भगृह दर्शन समय सारणी' }}" class="form-input">
          </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px;">
          <div>
            <label class="form-label">HAWAN BANNER TITLE</label>
            <input type="text" name="aarti_hawan_title" value="{{ $aartiSettings['aarti_hawan_title'] ?? 'दैनिक वैदिक हवन एवं महायज्ञ समय चक्र' }}" class="form-input">
          </div>
          <div>
            <label class="form-label">HAWAN TIMING</label>
            <input type="text" name="aarti_hawan_time" value="{{ $aartiSettings['aarti_hawan_time'] ?? ($aartiSettings['aarti_hawan_timing'] ?? '07:30 AM – 09:30 AM') }}" class="form-input">
          </div>
          <div>
            <label class="form-label">HAWAN LOCATION</label>
            <input type="text" name="aarti_hawan_place" value="{{ $aartiSettings['aarti_hawan_place'] ?? ($aartiSettings['aarti_hawan_location'] ?? 'ऋषिकुल यज्ञशाला व मन्दाकिनी तट') }}" class="form-input">
          </div>
          <div>
            <label class="form-label">HAWAN SUMMARY NOTE</label>
            <input type="text" name="aarti_hawan_desc" value="{{ $aartiSettings['aarti_hawan_desc'] ?? 'विश्व शान्ति, सर्वकल्याण एवं मनोकामना सिद्धि हेतु नित्य वैदिक आहुति।' }}" class="form-input">
          </div>
        </div>
      </div>
    </div>

    <!-- Submit Action Bar -->
    <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px solid var(--line); padding-top: 18px; flex-wrap: wrap; gap: 12px;">
      <span style="font-size: 11.5px; color: var(--brown);">
        Changes take effect immediately on the homepage timings strip and Amavasya schedule.
      </span>
      <button type="submit" class="action-btn primary" style="font-size: 12px; font-weight: 800; padding: 10px 24px; box-shadow: 0 4px 14px rgba(217, 93, 15, 0.35);">
        SAVE AARTI & HAWAN TIMETABLE &rarr;
      </button>
    </div>
  </form>
</div>

  <!-- ==========================================
       MANDIR DAILY TIME SLOTS
       ========================================== -->
<!-- Mandir Daily Available Time Slots Directory Card -->
<div class="cms-table-card" style="margin-top: 36px;">
  <div class="card-header" style="display: flex; align-items: center; justify-content: space-between; padding: 18px 24px; border-bottom: 1px solid var(--line); background: #FAF7F2;">
    <div style="display: flex; align-items: center; gap: 10px;">
      <span style="display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 8px; background: rgba(8, 108, 96, 0.12); color: #086c60;">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
      </span>
      <div>
        <h2 style="font-size: 15px; font-weight: 800; color: var(--deep); margin: 0;">Mandir Daily Time Slots Management</h2>
        <span style="font-size: 11px; color: var(--brown);">Active slots are offered to devotees on the online booking form with 2-day advance restriction</span>
      </div>
    </div>
    <button type="button" onclick="openSlotModal()" class="action-btn" style="background: #086c60; color: #fff; font-size: 11px; font-weight: 800; padding: 7px 16px;">
      + NEW TIME SLOT
    </button>
  </div>

  <table class="cms-table" style="width: 100%; border-collapse: collapse;">
    <thead>
      <tr>
        <th style="width: 35%;">Slot Name & Description</th>
        <th style="width: 20%;">Daily Timing (Start - End)</th>
        <th style="width: 15%;">Applies To</th>
        <th style="width: 10%;">Max Devotees</th>
        <th style="width: 10%;">Status</th>
        <th style="width: 10%; text-align: right;">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($timeSlots as $slot)
        <tr style="transition: background .2s ease;">
          <td>
            <strong style="color: var(--deep); font-size: 13.5px; font-family: var(--dev); font-weight: 700;">{{ $slot->slot_name }}</strong>
          </td>
          <td>
            <div style="display: inline-flex; align-items: center; gap: 6px; background: #FAF7F2; border: 1px solid var(--line); padding: 4px 10px; border-radius: 8px; font-size: 12.5px; font-weight: 700; color: #B45309;">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
              <span>{{ $slot->formatted_range }}</span>
            </div>
          </td>
          <td>
            @if($slot->service_type === 'hawan')
              <span class="badge" style="background: #ffedd5; color: #c2410c; border: 1px solid rgba(194, 65, 12, 0.25);">Hawan / Yagya</span>
            @elseif($slot->service_type === 'pooja')
              <span class="badge" style="background: #fef3c7; color: #b45309; border: 1px solid rgba(180, 83, 9, 0.25);">Vedic Pooja</span>
            @else
              <span class="badge" style="background: #e0f2fe; color: #0369a1; border: 1px solid rgba(3, 105, 161, 0.25);">All Rituals</span>
            @endif
          </td>
          <td>
            <span style="font-weight: 700; font-size: 13px; color: var(--deep);">{{ $slot->max_capacity }} Devotees</span>
          </td>
          <td>
            @if($slot->is_active)
              <span class="badge" style="background: #dcfce7; color: #166534; border: 1px solid rgba(22, 101, 52, 0.25);">
                <span style="width: 6px; height: 6px; border-radius: 9999px; background: #16a34a;"></span>
                ACTIVE
              </span>
            @else
              <span class="badge" style="background: #fee2e2; color: #991b1b; border: 1px solid rgba(153, 27, 27, 0.2);">
                PAUSED
              </span>
            @endif
          </td>
          <td style="text-align: right;">
            <div style="display: inline-flex; align-items: center; gap: 6px;">
              <button type="button" onclick='editSlotModal(@json($slot))' class="action-btn" style="background: #FAF4ED; color: #B45309; border-color: rgba(180, 83, 9, 0.25); padding: 5px 12px; font-size: 11px;">
                Edit
              </button>
              <form action="{{ route('admin.poojaSlots.delete', $slot->id) }}" method="POST" onsubmit="return confirm('Delete this time slot? Devotees will no longer be able to book during this time.')" style="margin: 0; display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="action-btn" style="background: #fee2e2; color: #991b1b; border-color: rgba(153, 27, 27, 0.2); padding: 5px 8px; font-size: 11px;">
                  &times;
                </button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" style="padding: 40px; text-align: center; color: var(--brown);">
            No Mandir time slots found. Click "+ NEW TIME SLOT" to establish temple schedules.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

@endif

<!-- Add / Edit Pooja Package Modal -->
<div id="poojaModal" style="display: none; position: fixed; inset: 0; background: rgba(22, 16, 13, 0.75); backdrop-filter: blur(8px); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
  <div style="background: #ffffff; width: 100%; max-width: 820px; max-height: 92vh; overflow-y: auto; border-radius: 20px; box-shadow: 0 25px 60px rgba(0,0,0,0.35); border: 1.5px solid var(--line);">
    
    <!-- Modal Header -->
    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--line); padding: 20px 28px; background: #FAF7F2; border-top-left-radius: 20px; border-top-right-radius: 20px;">
      <div style="display: flex; align-items: center; gap: 10px;">
        <span style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; background: rgba(217, 93, 15, 0.12); color: #D95D0F;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2c-.3 0-.6.2-.7.5C10 5.4 8 8.6 8 12c0 2.2 1.8 4 4 4s4-1.8 4-4c0-3.4-2-6.6-3.3-9.5-.1-.3-.4-.5-.7-.5zm0 18c-3.3 0-6-2.7-6-6 0-3.8 2.5-7.7 4.3-10.4.5-.7 1.6-.7 2.1 0C14.2 6.3 16.7 10.2 16.7 14c0 3.3-2.7 6-6 6z"/></svg>
        </span>
        <div>
          <h2 id="modalTitle" style="font-size: 16px; font-weight: 800; color: var(--deep); margin: 0; font-family: var(--dev);">Add Sacred Pooja Service</h2>
          <span style="font-size: 11px; color: var(--brown);">Enter authentic ritual details for online sankalp</span>
        </div>
      </div>
      <button type="button" onclick="closePoojaModal()" style="background: none; border: 0; font-size: 26px; color: var(--brown); cursor: pointer; line-height: 1;">&times;</button>
    </div>

    <!-- Modal Form -->
    <form id="poojaForm" action="{{ route('admin.poojas.store') }}" method="POST" style="padding: 24px 28px;">
      @csrf
      <input type="hidden" name="id" id="pooja_id" value="">

      <!-- Preset Bar from Sacred Offerings Directory -->
      <div style="background: #FFF7ED; border: 1.5px solid rgba(217,93,15,0.25); border-radius: 12px; padding: 12px 16px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
        <div style="display: flex; align-items: center; gap: 8px;">
          <span style="display: inline-flex; color: #9A3412;"><x-icon name="zap" size="16" /></span>
          <div>
            <span style="font-size: 12.5px; font-weight: 800; color: #9A3412; display: block;">Quick Presets from Sacred Offerings Directory:</span>
            <span style="font-size: 11px; color: #7A4B29;">Select any authentic offering to auto-fill all ritual parameters</span>
          </div>
        </div>
        <select id="sacredOfferingPresetSelect" onchange="applySacredOfferingPreset(this.value)" class="form-select" style="font-size: 12px; font-weight: 700; padding: 8px 14px; min-width: 300px; background: #fff; border-color: rgba(217,93,15,0.35); color: #2B1A12;">
          <option value="">-- Choose Sacred Offering Preset --</option>
          <option value="parikrama">1. श्री कामदगिरि अखंड परिक्रमा संकल्प (₹501)</option>
          <option value="deepdan">2. दिव्य मंदाकिनी दीपदान एवं महाआरती संकल्प (₹1100)</option>
          <option value="rudrabhishek">3. श्री कामतानाथ जी एकादश रुद्राभिषेक (₹2100)</option>
          <option value="gauseva">4. कामधेनु गौमाता नित्य सेवा व गौ-पूजन (₹751)</option>
          <option value="navgrah">5. श्री नवग्रह शांति महायज्ञ एवं वैदिक हवन (₹2100)</option>
          <option value="hanuman">6. श्री हनुमान चालीसा अखंड पाठ व चोला समर्पण (₹1100)</option>
          <option value="mahamrityunjaya">7. महामृत्युंजय जाप एवं शांति अनुष्ठान (₹3100)</option>
          <option value="satyanarayan">8. श्री सत्यनारायण कथा एवं हवन (₹1100)</option>
        </select>
      </div>

      <!-- Row 1: Name, Category & Dakshina -->
      <div style="display: grid; grid-template-columns: 2fr 1.2fr 1.1fr; gap: 14px; margin-bottom: 16px;">
        <div class="form-group" style="margin: 0;">
          <label class="form-label">Package Name (Hindi / English) *</label>
          <input type="text" name="name" id="pooja_name" required placeholder="उदा: श्री नवग्रह शांति महायज्ञ" class="form-input" style="font-weight: 700; font-size: 14px;">
        </div>
        <div class="form-group" style="margin: 0;">
          <label class="form-label">Category / Type *</label>
          <select name="service_type" id="pooja_service_type" class="form-select" style="font-weight: 700;">
            <option value="pooja">1. Vedic Pooja (पूजा)</option>
            <option value="hawan">2. Hawan & Yagya (हवन / महायज्ञ)</option>
            <option value="anushthan">3. Special Anushthan (विशेष अनुष्ठान)</option>
          </select>
        </div>
        <div class="form-group" style="margin: 0;">
          <label class="form-label">Dakshina / Price (₹) *</label>
          <div style="position: relative;">
            <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); font-weight: 800; color: #D95D0F;">₹</span>
            <input type="number" name="price" id="pooja_price" required min="0" step="1" placeholder="501" class="form-input" style="padding-left: 28px; font-weight: 800; color: #D95D0F; font-size: 14px;">
          </div>
        </div>
      </div>

      <!-- Row 2: Subtitle / Tagline & Duration -->
      <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px; margin-bottom: 16px;">
        <div class="form-group" style="margin: 0;">
          <label class="form-label">Sub-Headline / Tagline</label>
          <input type="text" name="tagline" id="pooja_tagline" placeholder="उदा: मनोकामना सिद्धि व कष्ट निवारण महापूजन" class="form-input">
        </div>
        <div class="form-group" style="margin: 0;">
          <label class="form-label">Duration</label>
          <input type="text" name="duration" id="pooja_duration" value="45 Mins" placeholder="45 Mins" class="form-input">
        </div>
      </div>

      <!-- Row 3: Assigned Priest & Location -->
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
        <div class="form-group" style="margin: 0;">
          <label class="form-label">Assigned Priest / Acharya</label>
          <input type="text" name="priest_assigned" id="pooja_priest" value="Senior Mandir Acharya" class="form-input">
        </div>
        <div class="form-group" style="margin: 0;">
          <label class="form-label">Ceremony Location</label>
          <input type="text" name="location" id="pooja_location" value="Ramghat Mandapam & Kamadgiri, Chitrakoot" class="form-input">
        </div>
      </div>

      <!-- Row 4: Detailed Description -->
      <div class="form-group" style="margin-bottom: 16px;">
        <label class="form-label">Detailed Description of Ritual & Significance *</label>
        <textarea name="description" id="pooja_description" rows="3" required placeholder="पूजन की धार्मिक महत्ता, सविधि अनुष्ठान और संकल्प विधान की जानकारी..." class="form-input" style="font-size: 12.5px; line-height: 1.6;"></textarea>
      </div>

      <!-- Row 5: Samagri Included -->
      <div class="form-group" style="margin-bottom: 16px;">
        <label class="form-label">Vedic Samagri Included (Samidha, Ghee, Roli, Akshat, etc.)</label>
        <input type="text" name="samagri_included" id="pooja_samagri" placeholder="उदा: शुद्ध देशी घी, नवग्रह समिधा, हवन कुण्ड, रोली, अक्षत, धूप, पंचमेवा व रक्षासूत्र न्यास द्वारा समर्पित" class="form-input" style="font-size: 12px;">
      </div>

      <!-- Row 6: Available Timings -->
      <div class="form-group" style="margin-bottom: 16px;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px;">
          <label class="form-label" style="margin: 0;">Daily Available Time Slots (One per line)</label>
          <span style="font-size: 11px; color: var(--brown);">e.g. 07:00 AM - 08:30 AM</span>
        </div>
        <textarea name="timings_raw" id="pooja_timings" rows="2" placeholder="07:00 AM - 08:30 AM&#10;11:00 AM - 12:30 PM&#10;05:30 PM - 07:00 PM" class="form-input" style="font-family: monospace; font-size: 12px;"></textarea>
      </div>

      <!-- Row 7: Benefits & Inclusions -->
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
        <div class="form-group" style="margin: 0;">
          <label class="form-label">Spiritual Benefits (One per line)</label>
          <textarea name="benefits_raw" id="pooja_benefits" rows="3" placeholder="नवग्रह शांति व पारिवारिक सौहार्द&#10;आरोग्य लाभ व सर्वकल्याण&#10;मनोकामना सिद्धि आशीर्वाद" class="form-input" style="font-size: 12px;"></textarea>
        </div>
        <div class="form-group" style="margin: 0;">
          <label class="form-label">What's Included in Ritual (One per line)</label>
          <textarea name="includes_raw" id="pooja_includes" rows="3" placeholder="संपूर्ण वैदिक सामग्री व पुष्प&#10;यजमान नाम-गोत्र से व्यक्तिगत संकल्प&#10;वैदिक आचार्यों द्वारा विधिपूर्वक पूर्णाहुति" class="form-input" style="font-size: 12px;"></textarea>
        </div>
      </div>

      <!-- Row 8: Featured Image & Live Preview -->
      <div style="margin-bottom: 20px; background: #FAF7F2; border: 1px solid var(--line); border-radius: 12px; padding: 14px;">
        <label class="form-label" style="margin-bottom: 8px;">Featured Offering Image</label>
        <div style="display: flex; gap: 14px; align-items: center;">
          <div style="width: 54px; height: 54px; border-radius: 10px; overflow: hidden; border: 1px solid var(--line); background: #fff; flex-shrink: 0;">
            <img id="pooja_img_preview" src="/images/chitrakoot-hero.webp" onerror="this.src='/images/chitrakoot-hero.webp'" style="width: 100%; height: 100%; object-fit: cover;" alt="">
          </div>
          <div style="flex: 1;">
            <div style="display: flex; gap: 8px;">
              <input type="text" name="image_url" id="pooja_image" oninput="updateImagePreview(this.value)" placeholder="/images/chitrakoot-hero.webp" class="form-input" style="font-size: 12px;">
              <button type="button" onclick="openUniversalMediaPicker({ targetInput: '#pooja_image', onSelect: (url) => updateImagePreview(url) })" class="action-btn teal" style="padding: 7px 14px; font-size: 11px;">
                BROWSE GALLERY
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Status Checkbox -->
      <div style="margin-bottom: 24px;">
        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 13px; font-weight: 700; color: var(--deep);">
          <input type="checkbox" name="is_active" id="pooja_active" value="1" checked style="width: 18px; height: 18px; accent-color: #D95D0F;">
          <span>Active for Online Booking on Public Website</span>
        </label>
      </div>

      <!-- Modal Footer -->
      <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid var(--line); padding-top: 16px;">
        <button type="button" onclick="closePoojaModal()" class="action-btn" style="background: var(--sand); color: var(--deep); padding: 9px 18px;">Cancel</button>
        <button type="submit" class="action-btn primary" style="font-weight: 800; padding: 9px 24px;">SAVE POOJA OFFERING &rarr;</button>
      </div>
    </form>

  </div>
</div>

<!-- Add / Edit Mandir Time Slot Modal -->
<div id="slotModal" style="display: none; position: fixed; inset: 0; background: rgba(22, 16, 13, 0.75); backdrop-filter: blur(8px); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
  <div style="background: #ffffff; width: 100%; max-width: 580px; max-height: 92vh; overflow-y: auto; border-radius: 20px; box-shadow: 0 25px 60px rgba(0,0,0,0.35); border: 1.5px solid var(--line);">
    
    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--line); padding: 18px 24px; background: #FAF7F2; border-top-left-radius: 20px; border-top-right-radius: 20px;">
      <div style="display: flex; align-items: center; gap: 10px;">
        <span style="display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 8px; background: rgba(8, 108, 96, 0.12); color: #086c60;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        </span>
        <h2 id="slotModalTitle" style="font-size: 16px; font-weight: 800; color: var(--deep); margin: 0; font-family: var(--dev);">Add Mandir Time Slot</h2>
      </div>
      <button type="button" onclick="closeSlotModal()" style="background: none; border: 0; font-size: 24px; color: var(--brown); cursor: pointer; line-height: 1;">&times;</button>
    </div>

    <form id="slotForm" action="{{ route('admin.poojaSlots.store') }}" method="POST" style="padding: 22px 24px;">
      @csrf
      <input type="hidden" name="id" id="slot_id" value="">

      <!-- Preset Bar from Mandir Timetable Directory -->
      <div style="background: #F0FDF4; border: 1.5px solid rgba(22,101,52,0.25); border-radius: 12px; padding: 12px 16px; margin-bottom: 18px; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
        <div style="display: flex; align-items: center; gap: 8px;">
          <span style="display: inline-flex; color: #166534;"><x-icon name="clock" size="16" /></span>
          <div>
            <span style="font-size: 12.5px; font-weight: 800; color: #166534; display: block;">Quick Presets from Mandir Timetable:</span>
            <span style="font-size: 11px; color: #2D6A4F;">Select authentic temple schedule to auto-fill times</span>
          </div>
        </div>
        <select id="mandirSlotPresetSelect" onchange="applyMandirSlotPreset(this.value)" class="form-select" style="font-size: 12px; font-weight: 700; padding: 7px 12px; min-width: 270px; background: #fff; border-color: rgba(22,101,52,0.35); color: #166534;">
          <option value="">-- Choose Timetable Preset --</option>
          <option value="mangala">1. प्रातः मंगला आरती व महायज्ञ (06:30 - 08:00 AM)</option>
          <option value="rudra">2. वैदिक पूजन व रुद्राभिषेक (08:30 - 10:30 AM)</option>
          <option value="madhyahna">3. मध्याह्न भोग व परिक्रमा (11:00 - 12:30 PM)</option>
          <option value="aprahna">4. अपराह्न नवग्रह शांति हवन (03:30 - 05:30 PM)</option>
          <option value="sandhya">5. सायंकालीन महाआरती व दीपदान (06:30 - 08:00 PM)</option>
          <option value="shayan">6. शयन आरती एवं शांति प्रार्थना (08:30 - 09:15 PM)</option>
        </select>
      </div>

      <div class="form-group" style="margin-bottom: 14px;">
        <label class="form-label">Time Slot Name (e.g. प्रातः कालीन मंगला आरती व महायज्ञ) *</label>
        <input type="text" name="slot_name" id="slot_name" required placeholder="प्रातः कालीन महाआरती एवं मंगला हवन" class="form-input" style="font-weight: 700;">
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
        <div class="form-group" style="margin: 0;">
          <label class="form-label">Start Time (24h format HH:MM) *</label>
          <input type="text" name="start_time" id="slot_start" required pattern="^([01]?[0-9]|2[0-3]):[0-5][0-9]$" placeholder="06:30" class="form-input">
        </div>
        <div class="form-group" style="margin: 0;">
          <label class="form-label">End Time (24h format HH:MM) *</label>
          <input type="text" name="end_time" id="slot_end" required pattern="^([01]?[0-9]|2[0-3]):[0-5][0-9]$" placeholder="08:00" class="form-input">
        </div>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 18px;">
        <div class="form-group" style="margin: 0;">
          <label class="form-label">Applies To *</label>
          <select name="service_type" id="slot_service_type" class="form-select">
            <option value="all">All (Pooja & Hawan)</option>
            <option value="pooja">Pooja Only</option>
            <option value="hawan">Hawan Only</option>
          </select>
        </div>
        <div class="form-group" style="margin: 0;">
          <label class="form-label">Max Devotee Capacity</label>
          <input type="number" name="max_capacity" id="slot_capacity" value="5" min="1" max="50" class="form-input">
        </div>
      </div>

      <div style="margin-bottom: 20px;">
        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 13px; font-weight: 700; color: var(--deep);">
          <input type="checkbox" name="is_active" id="slot_active" value="1" checked style="width: 18px; height: 18px; accent-color: #086c60;">
          <span>Active & Visible in Devotee Booking Form</span>
        </label>
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid var(--line); padding-top: 14px;">
        <button type="button" onclick="closeSlotModal()" class="action-btn" style="background: var(--sand); color: var(--deep); padding: 8px 16px;">Cancel</button>
        <button type="submit" class="action-btn" style="background: #086c60; color: #fff; font-weight: 800; padding: 8px 20px;">SAVE TIME SLOT &rarr;</button>
      </div>
    </form>

  </div>
</div>

@include('admin.media-picker-modal')

<script>
  // --- SACRED OFFERINGS & MANDIR TIMETABLE PRESET DIRECTORIES ---
  const SACRED_OFFERING_PRESETS = {
    parikrama: {
      name: "श्री कामदगिरि अखंड परिक्रमा संकल्प एवं पूजन",
      tagline: "मनोकामना सिद्धि व कष्ट निवारण महापूजन",
      service_type: "pooja",
      price: 501,
      duration: "45 Mins",
      priest: "श्री कामदगिरि पीठम् के वरिष्ठ आचार्य",
      description: "चित्रकूट स्थित पवित्र कामदगिरि पर्वत की 5 किमी पावन परिक्रमा मार्ग पर यजमान के नाम-गोत्र से वैदिक आचार्यों द्वारा सविधि संकल्प, पूजन, रक्षासूत्र बंधन एवं परिक्रमा पूर्णाहुति।",
      samagri: "हवन समिधा, देशी गाय का शुद्ध घी, रोली, अक्षत, पीत पुष्प, कपूर, मौली, तुलसी दल एवं पंचामृत",
      timings: "06:30 AM - 08:00 AM\n11:00 AM - 12:30 PM\n05:30 PM - 07:00 PM",
      benefits: "परिवार में सुख-शांति एवं समृद्धि का वास\nसर्व बाधा निवारण एवं मनोकामना पूर्ति\nकामदगिरि भगवान का पावन संरक्षण",
      includes: "यजमान के नाम-गोत्र से व्यक्तिगत संकल्प\nसंपूर्ण वैदिक पूजन व अभिषेक सामग्री\nडिजिटल संकल्प पत्र व पुजारी आशीर्वाद संदेश",
      image: "https://lh3.googleusercontent.com/aida-public/AB6AXuD_10PzMJvMK88XcmC2AB8m_aU1hgj9DFHBTrIyWQmqHlJR5eacLXqVggzhxSsUul4eeWmpkQZ4xrl-Hkk1M1xFkw65-2vvIV2HX7SH-9M1U6RKBSGsTVQEWqdyip-ercdU9diCPfb733xWaBC8Ipc6ERxlGl9PawntmnSCRLQ-xitKW9UtdPeI5elR1uc2lH9gzAmw2l_vl_OsCCxVLkDx590Cm6bzBZ8QCnigPFXkLhpvyujXMW6q"
    },
    deepdan: {
      name: "दिव्य मंदाकिनी दीपदान एवं महाआरती संकल्प",
      tagline: "रामघाट मंदाकिनी तट पर 108 दीप प्रज्वलन व गंगा आरती",
      service_type: "pooja",
      price: 1100,
      duration: "30 Mins",
      priest: "रामघाट तीर्थ पुरोहित मंडल",
      description: "भगवान श्री राम और माता सती अनुसूया की पावन मंदाकिनी नदी के रामघाट पर संध्याकाल में यजमान के नाम-गोत्र से 108 पावन दीपदान, वैदिक स्तुति एवं भव्य महाआरती संकल्प।",
      samagri: "108 मिट्टी के दीपक, तिल का तेल, शुद्ध घी, कमल पुष्प, इत्र, गुलाल, कपूर एवं धूपबत्ती",
      timings: "06:30 PM - 08:00 PM",
      benefits: "पितृ तृप्ति एवं कुल की आध्यात्मिक उन्नति\nमानसिक शांति एवं नकारात्मक ऊर्जा का शमन\nमाता मंदाकिनी व प्रभु श्री राम की असीम कृपा",
      includes: "108 पावन दीपदान व पुष्प अर्पण\nवेदिक ब्राह्मणों द्वारा नाम-गोत्र से संकल्प\nसंध्या महाआरती में विशेष अर्चन",
      image: "https://lh3.googleusercontent.com/aida-public/AB6AXuCfOUqcsYwKNWdOv3-IySqrpdeo9-aBjdqcY15-uvLwf_VoWoEx-U1nvKGS2-jtfSlovihdEg3_b1s0-lbKpDDcIFSus-7fFE753BIRrO8WI8kMYj2_0FFT4qNEAK0772GxDZOYKJnZEZe3t4cHWEvQLZv7iMxOf44ldS-_6w2cjcFU41obdfY4R9BsY_utkY-FWJc4v6XbcYWLHkEU3NnjPGlX-TGbJZHFRE6vpNhYwmMofphi33u4"
    },
    rudrabhishek: {
      name: "श्री कामतानाथ जी एकादश रुद्राभिषेक महापूजन",
      tagline: "कामदगिरि शिव लिंग पर एकादश द्रव्यों से महारुद्राभिषेक",
      service_type: "pooja",
      price: 2100,
      duration: "90 Mins",
      priest: "वेदमूर्ति यजुर्वेदी ब्राह्मण",
      description: "चित्रकूट धाम के प्राचीन शिवालय में वेदोक्त नमक-चमक स्तोत्र एवं रुद्राष्टाध्यायी पाठ सहित गाय के दुग्ध, मधु, गन्ने के रस व गंगाजल से एकादश द्रव्यों द्वारा महारुद्राभिषेक।",
      samagri: "गाय का कच्चा दूध, दही, मधु, घृत, शर्करा, गंगाजल, बेलपत्र, भस्म, धतूरा, श्वेत चन्दन",
      timings: "08:30 AM - 10:30 AM\n04:00 PM - 06:00 PM",
      benefits: "ग्रह दोष, कालसर्प एवं रोग-कष्टों से मुक्ति\nआय-व्यापार में उन्नति एवं आरोग्य लाभ\nभगवान भोलेनाथ व कामतानाथ जी का अखंड आशीर्वाद",
      includes: "एकादश द्रव्यों द्वारा रुद्राभिषेक\nसंपूर्ण वेदोक्त रुद्राष्टाध्यायी पाठ\nयजमान के नाम-गोत्र से विशिष्ट संकल्प",
      image: "https://lh3.googleusercontent.com/aida-public/AB6AXuCGizC1EIA1jnKlpQ1SWBzKrHG_OgZkQe6sDax_wnuZtIhFWtfWt-6m1jnBTWhz5Jji0AOrt9xr8W41pZkn-cZaOkuTyo2yeOHwrD92V6audP3fJcrCspCJNsquLRugs1Igok9LRE_4xwn9hZ6UVHI8f_hQJMjVOuIxA9CF87Om-bfNQNfGcQEsRylsOnCo7SMcSgmrXG2xf7A_uXu2kYkWLuPWiLaz4ltFV-UADzwzoIoVFdYetNmu"
    },
    gauseva: {
      name: "कामधेनु गौमाता नित्य सेवा व गौ-पूजन संकल्प",
      tagline: "कामदगिरि गौशाला में 33 कोटि देवी-देवताओं की साक्षात सेवा",
      service_type: "pooja",
      price: 751,
      duration: "30 Mins",
      priest: "गौशाला सेवा प्रमुख व आचार्य",
      description: "कामदगिरि पीठम् द्वारा संचालित पावन गौशाला में देशी गिर एवं साहीवाल गौमाताओं को हरा चारा, गुड़, चोकर, चना दलिया समर्पण तथा वेदोक्त गौ-सूक्त पाठ सहित सविधि पूजन।",
      samagri: "हरा चारा 50 किग्रा, शुद्ध गुड़, लापसी, गंध, अक्षत, पुष्पमाला व आरती",
      timings: "07:00 AM - 08:30 AM\n04:30 PM - 06:00 PM",
      benefits: "सर्व पापों का शमन एवं अक्षय पुण्य लाभ\nसंतान सुख एवं पारिवारिक सौभाग्य वृद्धि\nसमस्त तीर्थों का फल गौमाता के दर्शन व सेवा से प्राप्त",
      includes: "यजमान के नाम से गौमाता को चारा व गुड़ अर्पण\nवेदोक्त गौ-पूजन एवं प्रदक्षिणा\nगौ सेवा प्रमाण पत्र व आशीर्वाद",
      image: "https://images.unsplash.com/photo-1548013146-72479768bada?auto=format&fit=crop&w=1200&q=85"
    },
    navgrah: {
      name: "श्री नवग्रह शांति महायज्ञ एवं वैदिक हवन",
      tagline: "सूर्य आदि नवग्रहों की शांति व ग्रह दोष निवारण महायज्ञ",
      service_type: "hawan",
      price: 2100,
      duration: "120 Mins",
      priest: "ज्योतिष व कर्मकांड विशेषज्ञ आचार्य",
      description: "कुंडली के अनिष्ट ग्रहों की शांति, साढ़ेसाती, ढैय्या एवं राहु-केतु दोष निवारण हेतु 9 ग्रहों की विशेष समिधाओं से 1008 वैदिक आहुतियों द्वारा नवग्रह शांति महाहवन।",
      samagri: "अर्क, पलाश, खदिर, अपामार्ग, पीपल, गूलर, शमी, दूर्वा, कुशा समिधा, नवग्रह वस्त्र व हविष्य",
      timings: "08:30 AM - 10:30 AM\n03:30 PM - 05:30 PM",
      benefits: "अनिष्ट ग्रह पीड़ा से तत्काल मुक्ति\nकार्य क्षेत्र एवं व्यापार में आ रही रुकावटों का समाधान\nमानसिक संतुलन, शांति व दीर्घायु",
      includes: "नवग्रह मंडल स्थापन एवं पूजन\n1008 वैदिक मंत्र आहुति\nयजमान नाम-गोत्र से पूर्णाहुति",
      image: "https://lh3.googleusercontent.com/aida-public/AB6AXuD_10PzMJvMK88XcmC2AB8m_aU1hgj9DFHBTrIyWQmqHlJR5eacLXqVggzhxSsUul4eeWmpkQZ4xrl-Hkk1M1xFkw65-2vvIV2HX7SH-9M1U6RKBSGsTVQEWqdyip-ercdU9diCPfb733xWaBC8Ipc6ERxlGl9PawntmnSCRLQ-xitKW9UtdPeI5elR1uc2lH9gzAmw2l_vl_OsCCxVLkDx590Cm6bzBZ8QCnigPFXkLhpvyujXMW6q"
    },
    hanuman: {
      name: "श्री हनुमान चालीसा अखंड पाठ व चोला समर्पण",
      tagline: "संकट मोचन हनुमान जी को सिंदूर चोला व 108 पाठ",
      service_type: "pooja",
      price: 1100,
      duration: "60 Mins",
      priest: "हनुमान धारा तीर्थ आचार्य",
      description: "चित्रकूट धाम के पावन हनुमान धारा एवं कामदगिरि प्रांगण में संकटमोचन महावीर हनुमान जी को चमेली के तेल व सिंदूर का दिव्य चोला, जनेऊ, बूंदी भोग एवं 108 श्री हनुमान चालीसा पाठ।",
      samagri: "शुद्ध सिंदूर, चमेली तेल, चांदी का वर्क, जनेऊ, तुलसी दल, बूंदी के लड्डू व लाल लंगोट",
      timings: "07:30 AM - 09:00 AM\n05:00 PM - 06:30 PM",
      benefits: "भय, संकट, शत्रु बाधा एवं भूत-प्रेत पीड़ा से मुक्ति\nसाहस, पराक्रम एवं आत्मबल में अभूतपूर्व वृद्धि\nशनि दोष व साढ़ेसाती का प्रभाव शांत",
      includes: "हनुमान जी को दिव्य सिंदूर चोला अर्पण\n108 अखंड श्री हनुमान चालीसा पाठ\nयजमान के नाम से विशेष भोग",
      image: "https://lh3.googleusercontent.com/aida-public/AB6AXuCfOUqcsYwKNWdOv3-IySqrpdeo9-aBjdqcY15-uvLwf_VoWoEx-U1nvKGS2-jtfSlovihdEg3_b1s0-lbKpDDcIFSus-7fFE753BIRrO8WI8kMYj2_0FFT4qNEAK0772GxDZOYKJnZEZe3t4cHWEvQLZv7iMxOf44ldS-_6w2cjcFU41obdfY4R9BsY_utkY-FWJc4v6XbcYWLHkEU3NnjPGlX-TGbJZHFRE6vpNhYwmMofphi33u4"
    },
    mahamrityunjaya: {
      name: "महामृत्युंजय जाप एवं शांति अनुष्ठान",
      tagline: "अकाल मृत्यु भय निवारण व उत्तम स्वास्थ्य महायज्ञ",
      service_type: "anushthan",
      price: 3100,
      duration: "150 Mins",
      priest: "चार वैदिक पुरोहितों की टोली",
      description: "गंभीर रोग, अकाल मृत्यु भय, दुर्घटना एवं असाध्य कष्टों के निवारण हेतु 4 वेदोक्त आचार्यों द्वारा 5100 महामृत्युंजय मंत्र जप एवं गिलोय, दुर्वा व घृत द्वारा दशांश हवन।",
      samagri: "गिलोय, बेलपत्र, दुर्वा, काले तिल, शुद्ध घी, खीर, गुग्गुल, पंचामृत एवं यज्ञ समिधा",
      timings: "08:00 AM - 10:30 AM\n03:00 PM - 05:30 PM",
      benefits: "दीर्घायु, उत्तम स्वास्थ्य एवं असाध्य रोगों से रक्षा\nअकाल मृत्यु, भय एवं तनाव से मुक्ति\nपरिवार का महाकाल के सुरक्षा चक्र में वास",
      includes: "5100 वेदोक्त महामृत्युंजय संपुट जप\nदशांश वैदिक हवन व भस्म समर्पण\nचार वेदी पुरोहितों द्वारा विशेष संकल्प",
      image: "https://lh3.googleusercontent.com/aida-public/AB6AXuCGizC1EIA1jnKlpQ1SWBzKrHG_OgZkQe6sDax_wnuZtIhFWtfWt-6m1jnBTWhz5Jji0AOrt9xr8W41pZkn-cZaOkuTyo2yeOHwrD92V6audP3fJcrCspCJNsquLRugs1Igok9LRE_4xwn9hZ6UVHI8f_hQJMjVOuIxA9CF87Om-bfNQNfGcQEsRylsOnCo7SMcSgmrXG2xf7A_uXu2kYkWLuPWiLaz4ltFV-UADzwzoIoVFdYetNmu"
    },
    satyanarayan: {
      name: "श्री सत्यनारायण कथा एवं हवन",
      tagline: "पंच अध्याय कथा, पंजीरी भोग एवं परिवार कल्याण हवन",
      service_type: "pooja",
      price: 1100,
      duration: "75 Mins",
      priest: "भागवत व पुराण मर्मज्ञ आचार्य",
      description: "स्कंद पुराणोक्त श्री सत्यनारायण भगवान की पंच अध्याय कथा, शालिग्राम शिला अभिषेक, पंजीरी-पंचामृत भोग एवं मनोकामना पूर्ति हेतु हवन।",
      samagri: "केले के खंभे, शालिग्राम पूजन सामग्री, गेहूं का आटा, पंजीरी, चरणामृत, मौसमी फल व पंचमेवा",
      timings: "09:00 AM - 10:30 AM\n04:00 PM - 05:30 PM",
      benefits: "गृह क्लेश की समाप्ति व पारिवारिक प्रेम वृद्धि\nसत्य व धर्म की स्थापना एवं आर्थिक समृद्धि\nविवाह व संतान संबंधी कार्यों में सफलता",
      includes: "पंच अध्याय वेदोक्त कथा वाचन\nशालिग्राम जी का विशेष पूजन\nहवन एवं पंजीरी-चरणामृत भोग",
      image: "https://lh3.googleusercontent.com/aida-public/AB6AXuD_10PzMJvMK88XcmC2AB8m_aU1hgj9DFHBTrIyWQmqHlJR5eacLXqVggzhxSsUul4eeWmpkQZ4xrl-Hkk1M1xFkw65-2vvIV2HX7SH-9M1U6RKBSGsTVQEWqdyip-ercdU9diCPfb733xWaBC8Ipc6ERxlGl9PawntmnSCRLQ-xitKW9UtdPeI5elR1uc2lH9gzAmw2l_vl_OsCCxVLkDx590Cm6bzBZ8QCnigPFXkLhpvyujXMW6q"
    }
  };

  const MANDIR_TIMETABLE_PRESETS = {
    mangala: {
      name: "प्रातः कालीन मंगला आरती व महायज्ञ (Morning Mangala Aarti & Yagya)",
      start: "06:30",
      end: "08:00",
      type: "all",
      capacity: 10
    },
    rudra: {
      name: "प्रातः कालीन विशेष वैदिक पूजन व रुद्राभिषेक (Morning Vedic Pooja)",
      start: "08:30",
      end: "10:30",
      type: "pooja",
      capacity: 8
    },
    madhyahna: {
      name: "मध्याह्न भोग, परिक्रमा एवं कामदगिरि संकल्प (Midday Bhog & Parikrama)",
      start: "11:00",
      end: "12:30",
      type: "all",
      capacity: 12
    },
    aprahna: {
      name: "अपराह्न नवग्रह शांति हवन व गायत्री महायज्ञ (Afternoon Hawan & Yagya)",
      start: "15:30",
      end: "17:30",
      type: "hawan",
      capacity: 6
    },
    sandhya: {
      name: "सायंकालीन मंदाकिनी दीपदान, महाआरती व पूर्णाहुति (Evening Maha Aarti)",
      start: "18:30",
      end: "20:00",
      type: "all",
      capacity: 15
    },
    shayan: {
      name: "शयन आरती, वेद पाठ एवं शांति प्रार्थना (Night Shayan Aarti)",
      start: "20:30",
      end: "21:15",
      type: "pooja",
      capacity: 5
    }
  };

  function applySacredOfferingPreset(key) {
    if (!key || !SACRED_OFFERING_PRESETS[key]) return;
    const p = SACRED_OFFERING_PRESETS[key];
    document.getElementById('pooja_name').value = p.name;
    document.getElementById('pooja_tagline').value = p.tagline;
    document.getElementById('pooja_service_type').value = p.service_type;
    document.getElementById('pooja_price').value = p.price;
    document.getElementById('pooja_duration').value = p.duration;
    document.getElementById('pooja_priest').value = p.priest;
    document.getElementById('pooja_description').value = p.description;
    document.getElementById('pooja_samagri').value = p.samagri;
    document.getElementById('pooja_timings').value = p.timings;
    document.getElementById('pooja_benefits').value = p.benefits;
    document.getElementById('pooja_includes').value = p.includes;
    document.getElementById('pooja_image').value = p.image;
    updateImagePreview(p.image);
  }

  function applyMandirSlotPreset(key) {
    if (!key || !MANDIR_TIMETABLE_PRESETS[key]) return;
    const s = MANDIR_TIMETABLE_PRESETS[key];
    document.getElementById('slot_name').value = s.name;
    document.getElementById('slot_start').value = s.start;
    document.getElementById('slot_end').value = s.end;
    document.getElementById('slot_service_type').value = s.type;
    document.getElementById('slot_capacity').value = s.capacity;
  }

  function updateImagePreview(url) {
    const preview = document.getElementById('pooja_img_preview');
    if (preview) {
      preview.src = url || '/images/chitrakoot-hero.webp';
    }
  }

  // --- TIME SLOT MODAL HANDLERS ---
  function openSlotModal() {
    document.getElementById('slotModalTitle').textContent = 'Add Mandir Time Slot';
    document.getElementById('slotForm').reset();
    document.getElementById('slot_id').value = '';
    document.getElementById('slot_service_type').value = 'all';
    document.getElementById('slot_capacity').value = 5;
    document.getElementById('slot_active').checked = true;
    const slotPresetSel = document.getElementById('mandirSlotPresetSelect');
    if (slotPresetSel) slotPresetSel.value = '';
    document.getElementById('slotModal').style.display = 'flex';
  }

  function closeSlotModal() {
    document.getElementById('slotModal').style.display = 'none';
  }

  function editSlotModal(slot) {
    document.getElementById('slotModalTitle').textContent = 'Edit Mandir Time Slot';
    document.getElementById('slot_id').value = slot.id;
    document.getElementById('slot_name').value = slot.slot_name || '';
    document.getElementById('slot_start').value = slot.start_time || '06:30';
    document.getElementById('slot_end').value = slot.end_time || '08:00';
    document.getElementById('slot_service_type').value = slot.service_type || 'all';
    document.getElementById('slot_capacity').value = slot.max_capacity || 5;
    document.getElementById('slot_active').checked = !!slot.is_active;
    const slotPresetSel = document.getElementById('mandirSlotPresetSelect');
    if (slotPresetSel) slotPresetSel.value = '';
    document.getElementById('slotModal').style.display = 'flex';
  }

  // --- POOJA & HAWAN PACKAGE MODAL HANDLERS ---
  function openPoojaModal() {
    document.getElementById('modalTitle').textContent = 'Add Sacred Pooja / Hawan Package';
    document.getElementById('poojaForm').reset();
    document.getElementById('pooja_id').value = '';
    document.getElementById('pooja_service_type').value = 'pooja';
    document.getElementById('pooja_samagri').value = '';
    const presetSel = document.getElementById('sacredOfferingPresetSelect');
    if (presetSel) presetSel.value = '';
    updateImagePreview('/images/chitrakoot-hero.webp');
    document.getElementById('poojaModal').style.display = 'flex';
  }

  function closePoojaModal() {
    document.getElementById('poojaModal').style.display = 'none';
  }

  function editPoojaModal(pooja) {
    // Graceful fallback for title and properties to avoid 'undefined'
    const name = pooja.name || pooja.title_hi || pooja.title_en || 'Pooja Offering';
    const price = pooja.price !== undefined ? pooja.price : (pooja.suggested_donation_amount || 0);
    const tagline = pooja.tagline || pooja.summary_hi || '';
    const desc = pooja.description || pooja.description_hi || '';
    const duration = pooja.duration || (pooja.duration_minutes ? pooja.duration_minutes + ' Mins' : '45 Mins');
    const priest = pooja.priest_assigned || 'Senior Mandir Acharya';
    const location = pooja.location || 'Ramghat Mandapam & Kamadgiri, Chitrakoot';
    const imageUrl = pooja.image_url || pooja.featured_image || '/images/chitrakoot-hero.webp';

    document.getElementById('modalTitle').textContent = 'Edit Package: ' + name;
    document.getElementById('pooja_id').value = pooja.id;
    document.getElementById('pooja_name').value = name;
    document.getElementById('pooja_price').value = price;
    document.getElementById('pooja_service_type').value = pooja.service_type || 'pooja';
    document.getElementById('pooja_samagri').value = pooja.samagri_included || '';
    document.getElementById('pooja_tagline').value = tagline;
    document.getElementById('pooja_description').value = desc;
    document.getElementById('pooja_duration').value = duration;
    document.getElementById('pooja_priest').value = priest;
    document.getElementById('pooja_location').value = location;
    document.getElementById('pooja_image').value = imageUrl;
    updateImagePreview(imageUrl);
    
    if (pooja.available_timings && Array.isArray(pooja.available_timings)) {
      document.getElementById('pooja_timings').value = pooja.available_timings.join('\n');
    } else if (pooja.timings_display) {
      document.getElementById('pooja_timings').value = pooja.timings_display.replace(/,\s*/g, '\n');
    } else {
      document.getElementById('pooja_timings').value = '';
    }

    if (pooja.benefits && Array.isArray(pooja.benefits)) {
      document.getElementById('pooja_benefits').value = pooja.benefits.join('\n');
    } else if (pooja.benefits_hi && Array.isArray(pooja.benefits_hi)) {
      document.getElementById('pooja_benefits').value = pooja.benefits_hi.join('\n');
    } else {
      document.getElementById('pooja_benefits').value = '';
    }

    if (pooja.includes && Array.isArray(pooja.includes)) {
      document.getElementById('pooja_includes').value = pooja.includes.join('\n');
    } else {
      document.getElementById('pooja_includes').value = '';
    }

    document.getElementById('pooja_active').checked = !!pooja.is_active;
    document.getElementById('poojaModal').style.display = 'flex';
  }

  // Client-Side Search
  function searchPoojas(query) {
    const q = query.toLowerCase().trim();
    const rows = document.querySelectorAll('#poojasTableBody tr.pooja-row');
    let visibleCount = 0;
    rows.forEach(row => {
      const text = row.getAttribute('data-name') || '';
      if (text.includes(q)) {
        row.style.display = '';
        visibleCount++;
      } else {
        row.style.display = 'none';
      }
    });
    updateCountBadge(visibleCount);
  }

  // Status Filter
  function filterByStatus(status, btn) {
    document.querySelectorAll('.filter-pill').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const rows = document.querySelectorAll('#poojasTableBody tr.pooja-row');
    let visibleCount = 0;
    rows.forEach(row => {
      const rowStatus = row.getAttribute('data-status');
      if (status === 'all' || rowStatus === status) {
        row.style.display = '';
        visibleCount++;
      } else {
        row.style.display = 'none';
      }
    });
    updateCountBadge(visibleCount);
  }

  function updateCountBadge(count) {
    const badge = document.getElementById('resultsCountBadge');
    if (badge) {
      badge.textContent = 'Showing ' + count + ' packages';
    }
  }

  // Admin Aarti Tab Switcher
  function switchAdminAartiTab(tab) {
    document.querySelectorAll('.admin-aarti-pane').forEach(p => p.style.display = 'none');
    document.getElementById('tab-admin-normal').classList.remove('active');
    document.getElementById('tab-admin-amavasya').classList.remove('active');
    document.getElementById('tab-admin-hawan').classList.remove('active');

    if (tab === 'normal') {
      document.getElementById('pane-admin-normal').style.display = 'block';
      document.getElementById('tab-admin-normal').classList.add('active');
    } else if (tab === 'amavasya') {
      document.getElementById('pane-admin-amavasya').style.display = 'block';
      document.getElementById('tab-admin-amavasya').classList.add('active');
    } else {
      document.getElementById('pane-admin-hawan').style.display = 'block';
      document.getElementById('tab-admin-hawan').classList.add('active');
    }
  }

  // Remove Slot Row
  function removeSlotRow(btn) {
    const card = btn.closest('.normal-slot-card, .amavasya-slot-card');
    if (card) {
      card.remove();
    }
  }

  // Add Normal Aarti Slot Row
  function addNormalAartiRow() {
    const container = document.getElementById('normalSlotsGrid');
    const count = container.querySelectorAll('.normal-slot-card').length;
    const newIdx = count + Date.now();
    const card = document.createElement('div');
    card.className = 'normal-slot-card';
    card.style = 'background: #FAF7F2; border: 1.5px solid rgba(180, 83, 9, 0.2); border-radius: 12px; padding: 14px; position: relative;';
    card.innerHTML = `
      <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
        <span class="slot-badge" style="font-size: 10.5px; font-weight: 800; color: #D95D0F; text-transform: uppercase;">NEW SLOT #${count + 1}</span>
        <button type="button" onclick="removeSlotRow(this)" style="background: #fee2e2; border: 1px solid #f87171; color: #991b1b; font-size: 11px; font-weight: 700; border-radius: 4px; padding: 2px 8px; cursor: pointer;">Remove &times;</button>
      </div>
      <label style="font-size: 10.5px; font-weight: 700; color: var(--deep); display: block; margin-bottom: 3px;">Aarti Name</label>
      <input type="text" name="aarti_normal_slots[${newIdx}][name]" value="" class="form-input" style="font-size: 12.5px; margin-bottom: 8px; padding: 6px 8px;" placeholder="उदा. प्रातः मंगला आरती" required>
      <label style="font-size: 10.5px; font-weight: 700; color: var(--deep); display: block; margin-bottom: 3px;">Timing</label>
      <input type="text" name="aarti_normal_slots[${newIdx}][time]" value="" class="form-input" style="font-size: 12.5px; margin-bottom: 8px; padding: 6px 8px;" placeholder="06:00 AM" required>
      <label style="font-size: 10.5px; font-weight: 700; color: var(--deep); display: block; margin-bottom: 3px;">Location / Description</label>
      <input type="text" name="aarti_normal_slots[${newIdx}][desc]" value="" class="form-input" style="font-size: 12.5px; margin-bottom: 10px; padding: 6px 8px;" placeholder="कामदगिरि मुख्य गर्भगृह">
      <label style="display: flex; align-items: center; gap: 6px; font-size: 11px; color: #7A4B29; cursor: pointer; border-top: 1px dashed rgba(180,83,9,0.25); padding-top: 8px; margin: 0;">
        <input type="checkbox" name="aarti_normal_slots[${newIdx}][is_live]" value="1" style="accent-color: #D95D0F;">
        <span style="display: inline-flex; align-items: center; gap: 5px;"><span style="width: 8px; height: 8px; border-radius: 50%; background: #dc2626; display: inline-block;"></span>Live Pulse Highlight</span>
      </label>
    `;
    container.appendChild(card);
  }

  // Add Amavasya Aarti Slot Row
  function addAmavasyaAartiRow() {
    const container = document.getElementById('amavasyaSlotsGrid');
    const count = container.querySelectorAll('.amavasya-slot-card').length;
    const newIdx = count + Date.now();
    const card = document.createElement('div');
    card.className = 'amavasya-slot-card';
    card.style = 'background: #FAF7F2; border: 1.5px solid rgba(180, 83, 9, 0.2); border-radius: 12px; padding: 14px; position: relative;';
    card.innerHTML = `
      <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
        <span class="slot-badge" style="font-size: 10.5px; font-weight: 800; color: #B45309; text-transform: uppercase;">NEW AMAVASYA SLOT #${count + 1}</span>
        <button type="button" onclick="removeSlotRow(this)" style="background: #fee2e2; border: 1px solid #f87171; color: #991b1b; font-size: 11px; font-weight: 700; border-radius: 4px; padding: 2px 8px; cursor: pointer;">Remove &times;</button>
      </div>
      <label style="font-size: 10.5px; font-weight: 700; color: var(--deep); display: block; margin-bottom: 3px;">Aarti Name</label>
      <input type="text" name="aarti_amavasya_slots[${newIdx}][name]" value="" class="form-input" style="font-size: 12.5px; margin-bottom: 8px; padding: 6px 8px;" placeholder="उदा. परिक्रमा मंगला आरती" required>
      <label style="font-size: 10.5px; font-weight: 700; color: var(--deep); display: block; margin-bottom: 3px;">Timing</label>
      <input type="text" name="aarti_amavasya_slots[${newIdx}][time]" value="" class="form-input" style="font-size: 12.5px; margin-bottom: 8px; padding: 6px 8px;" placeholder="04:00 AM" required>
      <label style="font-size: 10.5px; font-weight: 700; color: var(--deep); display: block; margin-bottom: 3px;">Location / Description</label>
      <input type="text" name="aarti_amavasya_slots[${newIdx}][desc]" value="" class="form-input" style="font-size: 12.5px; margin-bottom: 10px; padding: 6px 8px;" placeholder="प्रातः विशेष कामदगिरि परिक्रमा">
      <label style="display: flex; align-items: center; gap: 6px; font-size: 11px; color: #7A4B29; cursor: pointer; border-top: 1px dashed rgba(180,83,9,0.25); padding-top: 8px; margin: 0;">
        <input type="checkbox" name="aarti_amavasya_slots[${newIdx}][is_live]" value="1" style="accent-color: #D95D0F;">
        <span style="display: inline-flex; align-items: center; gap: 5px;"><span style="width: 8px; height: 8px; border-radius: 50%; background: #dc2626; display: inline-block;"></span>Live Pulse Highlight</span>
      </label>
    `;
    container.appendChild(card);
  }
</script>
@endsection
