@extends('layouts.admin')

@section('title', 'Volunteer Seva Applications | CMS Admin')
@section('header_title', 'Volunteer Seva Applications')
@section('header_subtitle', 'Manage youth & devotee volunteer enrollment for temple seva, gaushala, education, and festival events')

@section('header_actions')
  <form action="{{ route('admin.volunteers.toggle') }}" method="POST" style="margin: 0;">
    @csrf
    @if($volunteerRegistrationEnabled)
      <button type="submit" class="action-btn" style="background: #fee2e2; color: #991b1b; font-weight: 700; border: 1px solid #f87171;">
        <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #dc2626; margin-right: 6px;"></span>
        PAUSE REGISTRATIONS
      </button>
    @else
      <button type="submit" class="action-btn primary" style="background: #16a34a; font-weight: 700;">
        <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #ffffff; margin-right: 6px;"></span>
        ENABLE REGISTRATIONS &rarr;
      </button>
    @endif
  </form>
@endsection

@section('content')
<!-- Toggle Alert Bar -->
<div class="cms-table-card" style="margin-bottom: 24px; padding: 18px 24px; display: flex; align-items: center; justify-content: space-between; background: {{ $volunteerRegistrationEnabled ? '#f0fdf4' : '#fff1f2' }}; border-color: {{ $volunteerRegistrationEnabled ? '#bbf7d0' : '#fecdd3' }};">
  <div style="display: flex; align-items: center; gap: 14px;">
    <div style="width: 32px; height: 32px; border-radius: 50%; background: {{ $volunteerRegistrationEnabled ? '#bbf7d0' : '#fecdd3' }}; display: grid; place-items: center;">
      @if($volunteerRegistrationEnabled)
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
      @else
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#e11d48" stroke-width="2.5"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
      @endif
    </div>
    <div>
      <h3 style="font-size: 15px; font-weight: 800; color: var(--deep); margin: 0 0 2px;">
        Public Volunteer Registration Portal: {{ $volunteerRegistrationEnabled ? 'ONLINE & ACCEPTING DEVOTEES' : 'TEMPORARILY PAUSED' }}
      </h3>
      <span style="font-size: 12px; color: var(--brown);">
        {{ $volunteerRegistrationEnabled ? 'Devotees can browse /volunteer and submit registration forms to join seva drives.' : 'Registration form is closed with a respectful notification advising devotees to stay tuned for future drives.' }}
      </span>
    </div>
  </div>
  <a href="{{ route('volunteer') }}" target="_blank" class="action-btn" style="background: #ffffff; color: var(--deep); font-size: 11px; padding: 6px 12px; box-shadow: 0 2px 6px rgba(0,0,0,0.05);">
    View Public Portal &nearr;
  </a>
</div>

<!-- Stats Row -->
<div class="stats-grid" style="margin-bottom: 24px;">
  <div class="stat-card">
    <div class="stat-label">TOTAL CANDIDATES</div>
    <div class="stat-val" style="color: var(--deep);">{{ $totalApplications }}</div>
    <div class="stat-sub">Devotees enrolled</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">NEW / PENDING REVIEW</div>
    <div class="stat-val" style="color: #d97706;">{{ $pendingCount }}</div>
    <div class="stat-sub">Awaiting initial call</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">REVIEWED & INTERVIEWED</div>
    <div class="stat-val" style="color: #2563eb;">{{ $reviewedCount }}</div>
    <div class="stat-sub">In orientation queue</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">APPROVED SEVAKS</div>
    <div class="stat-val" style="color: #16a34a;">{{ $approvedCount }}</div>
    <div class="stat-sub">Active on ground</div>
  </div>
</div>

<!-- Filter Toolbar -->
<div class="cms-table-card" style="margin-bottom: 24px; padding: 16px 20px;">
  <form action="{{ route('admin.volunteers') }}" method="GET" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
    <input type="text" 
           name="q" 
           value="{{ request('q') }}" 
           placeholder="Search applicant name, email, phone, city, skills..." 
           class="form-input" 
           style="flex: 1; min-width: 260px;">
    
    <select name="status" class="form-input" style="min-width: 160px;">
      <option value="">All Statuses</option>
      <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
      <option value="reviewed" {{ request('status') === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
      <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
      <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
    </select>

    <button type="submit" class="action-btn primary" style="padding: 10px 18px; font-weight: 700;">Filter</button>
    @if(request('q') || request('status'))
      <a href="{{ route('admin.volunteers') }}" class="action-btn" style="background: var(--sand); color: var(--deep); padding: 10px 14px;">Reset</a>
    @endif
  </form>
</div>

<!-- Table Card -->
<div class="cms-table-card">
  <table class="cms-table" style="width: 100%; border-collapse: collapse;">
    <thead>
      <tr style="background: #faf8f5; border-bottom: 1px solid var(--line); text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--brown);">
        <th style="padding: 14px 18px;">Applicant</th>
        <th style="padding: 14px 16px;">Location & Age</th>
        <th style="padding: 14px 16px;">Seva Area & Availability</th>
        <th style="padding: 14px 16px;">Skills / Profession</th>
        <th style="padding: 14px 16px;">Status</th>
        <th style="padding: 14px 18px; text-align: right;">Action</th>
      </tr>
    </thead>
    <tbody>
      @forelse($applications as $app)
        <tr style="border-bottom: 1px solid var(--line); font-size: 13px;">
          <td style="padding: 14px 18px;">
            <div style="font-size: 11px; color: var(--deep); display: flex; align-items: center; gap: 6px; margin-top: 2px;">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
              <span>{{ $app->phone }}</span>
              <span>&bull;</span>
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
              <span>{{ $app->email }}</span>
            </div>
            <div style="font-size: 10px; color: var(--brown);">
              Applied: {{ $app->created_at->format('M d, Y') }}
            </div>
          </td>
          <td style="padding: 14px 16px; font-size: 12px; color: var(--deep);">
            <div>{{ $app->city ?: 'Chitrakoot' }}{{ $app->state ? ', ' . $app->state : '' }}</div>
            <div style="font-size: 11px; color: var(--brown);">Age: {{ $app->age ?: 'Adult' }} &bull; {{ $app->occupation ?: 'Professional' }}</div>
          </td>
          <td style="padding: 14px 16px; font-size: 12px;">
            <span class="badge gold" style="display: inline-block; font-size: 10px; margin-bottom: 4px;">{{ $app->preferred_seva ?: 'Temple & Cultural Seva' }}</span>
            <div style="font-size: 11px; color: var(--brown);">{{ $app->availability ?: 'Weekends & Festivals' }}</div>
          </td>
          <td style="padding: 14px 16px; font-size: 12px; color: var(--deep);">
            {{ Str::limit($app->skills ?: 'General Assistance', 40) }}
            @if($app->experience)
              <div style="font-size: 10px; color: #666; font-style: italic;">Exp: {{ Str::limit($app->experience, 30) }}</div>
            @endif
          </td>
          <td style="padding: 14px 16px;">
            @php
              $badgeStyles = [
                'pending' => 'background: #fef3c7; color: #92400e;',
                'reviewed' => 'background: #dbeafe; color: #1e40af;',
                'approved' => 'background: #dcfce7; color: #166534;',
                'rejected' => 'background: #fee2e2; color: #991b1b;',
              ];
            @endphp
            <span class="badge" style="{{ $badgeStyles[$app->status] ?? '' }} font-weight: 800; font-size: 10px;">
              {{ strtoupper($app->status) }}
            </span>
          </td>
          <td style="padding: 14px 18px; text-align: right;">
            <button type="button" 
                    onclick='openVolunteerModal(@json($app))' 
                    class="action-btn" 
                    style="background: var(--sand); color: var(--deep); font-size: 11px; padding: 6px 12px;">
              Manage &rarr;
            </button>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" style="padding: 36px; text-align: center; color: var(--brown);">
            No volunteer applications found.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>

  @if($applications->hasPages())
    <div style="padding: 18px 24px; border-top: 1px solid var(--line);">
      {{ $applications->links() }}
    </div>
  @endif
</div>

<!-- Volunteer Manage Modal -->
<div id="volunteerModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
  <div style="background: #ffffff; width: 100%; max-width: 520px; border-radius: var(--radius); padding: 28px; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--line); padding-bottom: 12px; margin-bottom: 18px;">
      <h3 style="font-size: 16px; font-weight: 800; color: var(--deep); margin: 0;">Review Volunteer Application</h3>
      <button type="button" onclick="closeVolunteerModal()" style="background: none; border: 0; font-size: 24px; color: var(--brown); cursor: pointer;">&times;</button>
    </div>

    <form id="volunteerForm" method="POST">
      @csrf
      <div style="margin-bottom: 14px; font-size: 13px; color: var(--deep);">
        Applicant: <strong id="vName"></strong> &bull; <span id="vContact"></span>
      </div>

      <div style="background: var(--sand); padding: 12px; border-radius: 6px; font-size: 12px; margin-bottom: 16px;">
        <div><strong>Seva Interest:</strong> <span id="vSeva"></span></div>
        <div><strong>Availability:</strong> <span id="vAvail"></span></div>
        <div><strong>Skills / Notes:</strong> <span id="vSkills"></span></div>
      </div>

      <div class="form-group" style="margin-bottom: 16px;">
        <label class="form-label">Application Status *</label>
        <select name="status" id="vStatusSelect" class="form-input" style="width: 100%; font-weight: 700;">
          <option value="pending">Pending (Awaiting Verification)</option>
          <option value="reviewed">Reviewed (Interviewed & Profile Checked)</option>
          <option value="approved">Approved (Assigned to Seva Team)</option>
          <option value="rejected">Rejected</option>
        </select>
      </div>

      <div class="form-group" style="margin-bottom: 20px;">
        <label class="form-label">Admin / Coordinator Notes</label>
        <textarea name="admin_notes" id="vNotes" rows="3" placeholder="e.g. Speaks Hindi & English. Assigned to Ramghat Cleanliness & Pilgrim Assistance drive." class="form-input" style="width: 100%; font-size: 12px;"></textarea>
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 8px;">
        <button type="button" onclick="closeVolunteerModal()" class="action-btn" style="background: var(--sand); color: var(--deep);">Cancel</button>
        <button type="submit" class="action-btn primary" style="font-weight: 800;">UPDATE APPLICATION &rarr;</button>
      </div>
    </form>
  </div>
</div>

<script>
  function openVolunteerModal(app) {
    document.getElementById('vName').textContent = app.full_name;
    document.getElementById('vContact').textContent = app.phone + ' | ' + app.email;
    document.getElementById('vSeva').textContent = app.preferred_seva || 'Temple Seva';
    document.getElementById('vAvail').textContent = app.availability || 'Weekends';
    document.getElementById('vSkills').textContent = app.skills || 'None stated';
    document.getElementById('vStatusSelect').value = app.status;
    document.getElementById('vNotes').value = app.admin_notes || '';
    document.getElementById('volunteerForm').action = '/admin/volunteers/' + app.id + '/status';
    document.getElementById('volunteerModal').style.display = 'flex';
  }

  function closeVolunteerModal() {
    document.getElementById('volunteerModal').style.display = 'none';
  }
</script>
@endsection
