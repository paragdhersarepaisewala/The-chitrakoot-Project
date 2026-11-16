@extends('layouts.admin')

@section('title', 'Donation Campaigns Manager | CMS Admin')
@section('header_title', 'Donation Campaigns & Temple Funds Manager')
@section('header_subtitle', 'Manage sacred fund categories, target goals, active appeals, and donor contributions')

@section('header_actions')
  <a href="{{ route('admin.sevas') }}" class="action-btn" style="background: rgba(45, 106, 79, 0.12); color: #2D6A4F; border: 1px solid rgba(45, 106, 79, 0.25); font-size: 11px; padding: 7px 14px;">
    &larr; Seva Projects CMS
  </a>
  <a href="{{ route('donate') }}" target="_blank" class="action-btn" style="background: var(--sand); color: var(--deep); font-size: 11px; padding: 7px 14px;">
    View Public Donation Portal &nearr;
  </a>
@endsection

@section('content')
<!-- Section Switcher Tabs -->
<div style="display: flex; gap: 10px; margin-bottom: 22px; flex-wrap: wrap;">
  <a href="{{ route('admin.sevas') }}" class="filter-pill" style="font-size: 12px; padding: 8px 18px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; background: #fff;">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
    <span>1. Seva Projects & Initiatives</span>
    <span style="background: rgba(0,0,0,0.06); color: var(--deep); font-size: 10px; padding: 2px 7px; border-radius: 9999px;">{{ \App\Models\SevaProject::count() }}</span>
  </a>

  <a href="{{ route('admin.causes') }}" class="filter-pill active" style="font-size: 12px; padding: 8px 18px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
    <span>2. Donation Campaigns & Temple Funds</span>
    <span style="background: rgba(255,255,255,0.25); color: #fff; font-size: 10px; padding: 2px 7px; border-radius: 9999px;">{{ $causes->count() }}</span>
  </a>
</div>

@php
  $totalTarget = $causes->sum('target_amount');
  $totalRaised = \App\Models\Donation::where('status', 'verified')->sum('amount');
  $activeCausesCount = $causes->where('status', 'active')->count();
@endphp

<!-- Stats Grid -->
<div class="stats-grid" style="margin-bottom: 24px;">
  <div class="stat-card">
    <div class="stat-label">TOTAL CAMPAIGNS</div>
    <div class="stat-val" style="color: var(--crimson);">{{ $causes->count() }}</div>
    <div class="stat-sub">Spiritual & seva appeals</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">ACTIVE APPEALS</div>
    <div class="stat-val" style="color: #16a34a;">{{ $activeCausesCount }}</div>
    <div class="stat-sub">Accepting devotee donations</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">TOTAL TARGET GOAL</div>
    <div class="stat-val" style="color: var(--deep);">₹{{ number_format($totalTarget, 0) }}</div>
    <div class="stat-sub">Across all seva drives</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">TOTAL FUNDS RAISED</div>
    <div class="stat-val" style="color: #086c60;">₹{{ number_format($totalRaised, 0) }}</div>
    <div class="stat-sub">Verified Razorpay receipts</div>
  </div>
</div>

@if(session('success'))
  <div style="background: rgba(46,125,50,.12); color: #166534; padding: 14px 20px; border-radius: var(--radius); margin-bottom: 24px; border-left: 4px solid #166534; font-weight: 600; font-size: 13px;">
    {{ session('success') }}
  </div>
@endif

<div style="display: grid; grid-template-columns: 1fr 1.6fr; gap: 24px; align-items: start;">

  <!-- Add New Cause Card -->
  <div class="cms-table-card" style="padding: 26px;">
    <h3 style="font-size: 16px; font-weight: 800; color: var(--deep); margin: 0 0 6px;">Create New Donation Campaign</h3>
    <p style="font-size: 12px; color: var(--brown); margin: 0 0 20px;">Launch a funding appeal for gaushala, bhandara, or temple reconstruction.</p>

    <form action="{{ url('/admin/causes') }}" method="POST">
      @csrf

      <div style="margin-bottom: 14px;">
        <label class="form-label">Cause Title (Hindi / English) *</label>
        <input type="text" name="title" required placeholder="e.g. Gaushala & Kamadgiri Protection Fund" class="form-input" style="width: 100%; font-size: 14px; font-weight: 600;">
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
        <div>
          <label class="form-label">Target Amount (₹) *</label>
          <input type="number" name="target_amount" required min="100" step="100" value="500000" class="form-input" style="width: 100%; font-weight: 700; color: var(--crimson);">
        </div>
        <div>
          <label class="form-label">Campaign Status</label>
          <select name="status" class="form-input" style="width: 100%;">
            <option value="active" selected>Active</option>
            <option value="paused">Paused</option>
            <option value="completed">Completed</option>
          </select>
        </div>
      </div>

      <div style="margin-bottom: 14px;">
        <label class="form-label">Description of Purpose & Utilization *</label>
        <textarea name="description" rows="4" required placeholder="Detailed description of cause objectives, fund utilization, and seva impact..." class="form-input" style="width: 100%; font-size: 12.5px; line-height: 1.5;"></textarea>
      </div>

      <div style="margin-bottom: 22px;">
        <label class="form-label">Banner Image URL *</label>
        <div style="display: flex; gap: 8px;">
          <input type="text" name="image_path" id="cause_add_img" required value="/uploads/temple-test-photo-1792032209.webp" placeholder="/uploads/..." class="form-input" style="flex: 1; font-size: 12px;">
          <button type="button" onclick="openUniversalMediaPicker({ targetInput: '#cause_add_img' })" class="action-btn teal" style="padding: 7px 12px; font-size: 10px;">REUSE</button>
        </div>
      </div>

      <button type="submit" class="action-btn primary" style="width: 100%; justify-content: center; padding: 11px; font-weight: 800; font-size: 12px;">
        CREATE DONATION CAMPAIGN &rarr;
      </button>
    </form>
  </div>

  <!-- Existing Causes Table Card -->
  <div class="cms-table-card">
    <div style="padding: 18px 24px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center;">
      <div>
        <h3 style="font-size: 16px; font-weight: 800; color: var(--deep); margin: 0;">Active Donation Campaigns ({{ $causes->count() }})</h3>
        <span style="font-size: 11px; color: var(--brown);">Track fundraising progress and donor participation</span>
      </div>
    </div>

    <table class="cms-table" style="width: 100%; border-collapse: collapse;">
      <thead>
        <tr style="background: #faf8f5; border-bottom: 1px solid var(--line); text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--brown);">
          <th style="padding: 12px 20px;">Campaign Details</th>
          <th style="padding: 12px 16px;">Fundraising Progress</th>
          <th style="padding: 12px 16px;">Status</th>
          <th style="padding: 12px 20px; text-align: right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($causes as $cause)
          @php
            $raised = method_exists($cause, 'raisedAmount') ? $cause->raisedAmount() : 0;
            $target = $cause->target_amount > 0 ? $cause->target_amount : 1;
            $percent = min(100, round(($raised / $target) * 100));
          @endphp
          <tr style="border-bottom: 1px solid var(--line);">
            <td style="padding: 14px 20px;">
              <div style="display: flex; align-items: center; gap: 12px;">
                <img src="{{ $cause->image_path ?: '/images/chitrakoot-hero.webp' }}" style="width: 48px; height: 48px; border-radius: 6px; object-fit: cover; border: 1px solid var(--line); flex-shrink: 0;" alt="">
                <div>
                  <strong style="color: var(--deep); font-size: 14px; display: block;">{{ $cause->title }}</strong>
                  <span style="font-size: 11px; color: var(--brown);">Target: <strong style="color: var(--crimson);">₹{{ number_format($cause->target_amount, 0) }}</strong> &bull; Slug: <code>{{ $cause->slug }}</code></span>
                </div>
              </div>
            </td>
            <td style="padding: 14px 16px;">
              <div style="font-size: 11px; font-weight: 700; color: var(--deep); margin-bottom: 4px; display: flex; justify-content: space-between;">
                <span>₹{{ number_format($raised, 0) }} raised</span>
                <span>{{ $percent }}%</span>
              </div>
              <div style="width: 100%; min-width: 120px; height: 6px; background: #e5e7eb; border-radius: 9999px; overflow: hidden;">
                <div style="width: {{ $percent }}%; height: 100%; background: linear-gradient(90deg, #086c60, #16a34a); border-radius: 9999px;"></div>
              </div>
            </td>
            <td style="padding: 14px 16px;">
              @if($cause->status === 'active')
                <span class="badge" style="background: #dcfce7; color: #166534; font-weight: 700; font-size: 10px;">ACTIVE</span>
              @elseif($cause->status === 'completed')
                <span class="badge" style="background: #dbeafe; color: #1e40af; font-weight: 700; font-size: 10px;">COMPLETED</span>
              @else
                <span class="badge" style="background: #fee2e2; color: #991b1b; font-weight: 700; font-size: 10px;">PAUSED</span>
              @endif
            </td>
            <td style="padding: 14px 20px; text-align: right;">
              <div style="display: inline-flex; gap: 6px;">
                <button type="button" 
                        onclick='editCauseModal(@json($cause))' 
                        class="action-btn" 
                        style="background: var(--sand); color: var(--deep); padding: 5px 10px; font-size: 11px;">
                  Edit
                </button>
                <form action="{{ url('/admin/causes/' . $cause->id) }}" method="POST" onsubmit="return confirm('Delete this donation cause permanently? Devotee payment records referencing it will remain archived.')" style="margin: 0; display: inline;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="action-btn" style="background: #fee2e2; color: #991b1b; padding: 5px 10px; font-size: 11px;">
                    Delete
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" style="padding: 30px; text-align: center; color: var(--brown);">
              No donation campaigns registered yet.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</div>

<!-- Edit Cause Modal -->
<div id="editCauseModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
  <div style="background: #ffffff; width: 100%; max-width: 600px; max-height: 90vh; overflow-y: auto; border-radius: var(--radius); padding: 28px; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--line); padding-bottom: 12px; margin-bottom: 18px;">
      <h3 id="editCauseModalTitle" style="font-size: 16px; font-weight: 800; color: var(--deep); margin: 0;">Edit Donation Campaign</h3>
      <button type="button" onclick="closeEditCauseModal()" style="background: none; border: 0; font-size: 24px; color: var(--brown); cursor: pointer;">&times;</button>
    </div>

    <form id="editCauseForm" method="POST">
      @csrf
      @method('PUT')

      <div style="margin-bottom: 14px;">
        <label class="form-label">Cause Title (Hindi) *</label>
        <input type="text" name="title" id="edit_cause_title" required class="form-input" style="width: 100%; font-size: 14px; font-weight: 700;">
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
        <div>
          <label class="form-label">Target Amount (₹) *</label>
          <input type="number" name="target_amount" id="edit_cause_target" required min="100" class="form-input" style="width: 100%; font-weight: 700; color: var(--crimson);">
        </div>
        <div>
          <label class="form-label">Campaign Status *</label>
          <select name="status" id="edit_cause_status" class="form-input" style="width: 100%;">
            <option value="active">Active</option>
            <option value="paused">Paused</option>
            <option value="completed">Completed</option>
          </select>
        </div>
      </div>

      <div style="margin-bottom: 14px;">
        <label class="form-label">Description of Purpose & Utilization *</label>
        <textarea name="description" id="edit_cause_desc" rows="4" required class="form-input" style="width: 100%; font-size: 12.5px; line-height: 1.5;"></textarea>
      </div>

      <div style="margin-bottom: 20px;">
        <label class="form-label">Banner Image URL *</label>
        <div style="display: flex; gap: 8px;">
          <input type="text" name="image_path" id="edit_cause_img" required class="form-input" style="flex: 1; font-size: 12px;">
          <button type="button" onclick="openUniversalMediaPicker({ targetInput: '#edit_cause_img' })" class="action-btn teal" style="padding: 7px 12px; font-size: 10px;">REUSE</button>
        </div>
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 8px; border-top: 1px solid var(--line); padding-top: 16px;">
        <button type="button" onclick="closeEditCauseModal()" class="action-btn" style="background: var(--sand); color: var(--deep);">Cancel</button>
        <button type="submit" class="action-btn primary" style="font-weight: 800;">UPDATE CAMPAIGN &rarr;</button>
      </div>
    </form>
  </div>
</div>

@include('admin.media-picker-modal')

<script>
  function editCauseModal(cause) {
    document.getElementById('editCauseModalTitle').textContent = 'Edit: ' + cause.title;
    document.getElementById('editCauseForm').action = '/admin/causes/' + cause.id;
    document.getElementById('edit_cause_title').value = cause.title || '';
    document.getElementById('edit_cause_target').value = cause.target_amount || 500000;
    document.getElementById('edit_cause_status').value = cause.status || 'active';
    document.getElementById('edit_cause_desc').value = cause.description || '';
    document.getElementById('edit_cause_img').value = cause.image_path || '';
    document.getElementById('editCauseModal').style.display = 'flex';
  }

  function closeEditCauseModal() {
    document.getElementById('editCauseModal').style.display = 'none';
  }
</script>
@endsection