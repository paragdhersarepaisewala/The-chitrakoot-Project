@extends('layouts.admin')

@section('title', 'Leadership & Trustees Management | CMS Admin')
@section('header_title', 'Leadership & Trustees Management')
@section('header_subtitle', 'Manage spiritual patrons, governing trustees, advisors, and executive committee members')

@section('header_actions')
  <button type="button" onclick="openAddModal()" class="action-btn primary" style="font-size: 11px;">
    + Add Trustee / Patron
  </button>
  <a href="{{ route('leadership') }}" target="_blank" class="action-btn" style="background: #ffffff; color: var(--deep); font-size: 11px;">
    Open Public Leadership Page &nearr;
  </a>
@endsection

@section('content')

@if(session('success'))
  <div class="toast success" style="margin-bottom: 20px; position: static; animation: none;">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
    <span>{{ session('success') }}</span>
  </div>
@endif

@if($errors->any())
  <div class="toast error" style="margin-bottom: 20px; position: static; animation: none; background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5;">
    <ul style="margin: 0; padding-left: 20px;">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<!-- Stats Row -->
<div class="stats-grid" style="margin-bottom: 24px;">
  <div class="stat-card">
    <div class="stat-label">TOTAL PROFILES</div>
    <div class="stat-val" style="color: var(--deep);">{{ $totalCount }}</div>
    <div class="stat-sub">Active in governance registry</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">SPIRITUAL PATRONS</div>
    <div class="stat-val" style="color: #b45309;">{{ $patronCount }}</div>
    <div class="stat-sub">Peethadhishwar & Margdarshak</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">TRUSTEES & COMMITTEE</div>
    <div class="stat-val" style="color: #16a34a;">{{ $trusteeCount }}</div>
    <div class="stat-sub">Board members & advisors</div>
  </div>
</div>

<!-- Table Card -->
<div class="cms-table-card">
  <table class="cms-data-table">
    <thead>
      <tr>
        <th style="width: 70px;">Photo</th>
        <th>Name (Hindi & English)</th>
        <th>Designation / Title</th>
        <th style="width: 140px;">Category</th>
        <th style="width: 80px; text-align: center;">Order</th>
        <th style="width: 90px; text-align: center;">Status</th>
        <th style="text-align: right; width: 140px;">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($profiles as $profile)
        <tr>
          <td>
            <div style="width: 48px; height: 48px; border-radius: 50%; overflow: hidden; background: #FAF4ED; border: 1px solid rgba(180,83,9,0.2); display: flex; align-items: center; justify-content: center;">
              @if(!empty($profile->photo_path))
                <img src="{{ $profile->photo_path }}" alt="{{ $profile->name_hi }}" style="width: 100%; height: 100%; object-fit: cover;">
              @else
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#B45309" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
              @endif
            </div>
          </td>
          <td>
            <strong style="color: var(--deep); font-size: 14px;">{{ $profile->name_hi }}</strong>
            @if($profile->name_en)
              <div style="font-size: 12px; color: #888;">{{ $profile->name_en }}</div>
            @endif
          </td>
          <td>
            <div style="font-size: 13px; font-weight: 600; color: #B45309;">{{ $profile->title_hi }}</div>
            @if($profile->title_en)
              <div style="font-size: 11px; color: #888;">{{ $profile->title_en }}</div>
            @endif
          </td>
          <td>
            @if($profile->role_category === 'peethadhishwar')
              <span class="badge" style="background: #fef3c7; color: #b45309; border: 1px solid #fde68a;">Patron / Peethadhishwar</span>
            @elseif($profile->role_category === 'trustee')
              <span class="badge" style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd;">Trustee</span>
            @elseif($profile->role_category === 'advisor')
              <span class="badge" style="background: #f3e8ff; color: #7e22ce; border: 1px solid #e9d5ff;">Advisor</span>
            @else
              <span class="badge" style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1;">{{ ucfirst($profile->role_category) }}</span>
            @endif
          </td>
          <td style="text-align: center; font-weight: 600; color: var(--deep);">
            {{ $profile->sort_order }}
          </td>
          <td style="text-align: center;">
            @if($profile->is_active)
              <span class="badge" style="background: #dcfce7; color: #16a34a; border: 1px solid #86efac;">Active</span>
            @else
              <span class="badge" style="background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5;">Inactive</span>
            @endif
          </td>
          <td style="text-align: right; white-space: nowrap;">
            <div style="display: inline-flex; gap: 6px; align-items: center;">
              <!-- Edit Button -->
              <button type="button" class="action-btn" style="font-size: 11px; padding: 4px 10px;" onclick="openEditModal({{ json_encode($profile) }})">
                Edit
              </button>

              <!-- Delete Button -->
              <form action="{{ route('admin.leadership.delete', $profile->id) }}" method="POST" style="margin: 0; display: inline;" onsubmit="return confirm('Are you sure you want to delete this leadership profile?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="action-btn" style="color: #dc2626; border-color: #fca5a5; font-size: 11px; padding: 4px 8px;" title="Delete">
                  &times;
                </button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="7" style="text-align: center; padding: 40px; color: #888;">
            No leadership or trustee profiles found. Click "+ Add Trustee / Patron" to register trustees.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<!-- Add / Edit Modal -->
<div id="leadershipModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.55); z-index: 1000; overflow-y: auto; padding: 40px 16px;">
  <div style="background: #ffffff; max-width: 650px; margin: 0 auto; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); overflow: hidden;">
    <div style="padding: 20px 24px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center; background: #fafaf9;">
      <h3 id="modalTitle" style="margin: 0; font-size: 16px; font-weight: 700; color: var(--deep);">Add Leadership Profile</h3>
      <button type="button" onclick="closeModal()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #888;">&times;</button>
    </div>

    <form id="leadershipForm" action="{{ route('admin.leadership.store') }}" method="POST" enctype="multipart/form-data" style="padding: 24px;">
      @csrf
      <input type="hidden" name="_method" id="formMethod" value="POST">

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
        <div>
          <label class="form-label" style="font-size: 12px; font-weight: 600;">Full Name (Hindi) *</label>
          <input type="text" name="name_hi" id="name_hi" required class="form-input" placeholder="e.g. पूज्य पीठाधीश्वर स्वामी जी">
        </div>
        <div>
          <label class="form-label" style="font-size: 12px; font-weight: 600;">Full Name (English)</label>
          <input type="text" name="name_en" id="name_en" class="form-input" placeholder="e.g. Pujya Peethadhishwar Swami Ji">
        </div>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
        <div>
          <label class="form-label" style="font-size: 12px; font-weight: 600;">Designation / Title (Hindi) *</label>
          <input type="text" name="title_hi" id="title_hi" required class="form-input" placeholder="e.g. सर्वोच्च संरक्षक एवं पीठाधीश्वर">
        </div>
        <div>
          <label class="form-label" style="font-size: 12px; font-weight: 600;">Designation / Title (English)</label>
          <input type="text" name="title_en" id="title_en" class="form-input" placeholder="e.g. Chief Patron & Peethadhishwar">
        </div>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
        <div>
          <label class="form-label" style="font-size: 12px; font-weight: 600;">Governance Role Category *</label>
          <select name="role_category" id="role_category" required class="form-input">
            <option value="peethadhishwar">Spiritual Patron (Peethadhishwar)</option>
            <option value="trustee">Trustee / Board Member</option>
            <option value="advisor">Advisory Council</option>
            <option value="committee">Executive Committee</option>
            <option value="other">Other</option>
          </select>
        </div>
        <div>
          <label class="form-label" style="font-size: 12px; font-weight: 600;">Sort Order (Display Sequence)</label>
          <input type="number" name="sort_order" id="sort_order" class="form-input" value="0">
        </div>
      </div>

      <div style="margin-bottom: 16px;">
        <label class="form-label" style="font-size: 12px; font-weight: 600;">Trustee Photo (URL or File Upload)</label>
        <div style="display: flex; gap: 12px; align-items: center; margin-bottom: 8px;">
          <input type="text" name="photo_path" id="photo_path" class="form-input" placeholder="/uploads/leader-1.jpg or image URL">
        </div>
        <input type="file" name="photo_file" accept="image/*" class="form-input" style="padding: 6px;">
        <div style="font-size: 11px; color: #888; margin-top: 4px;">Supports PNG, JPG, WebP, SVG (Max 10MB)</div>
      </div>

      <div style="margin-bottom: 16px;">
        <label class="form-label" style="font-size: 12px; font-weight: 600;">Brief Bio / Seva Description (Hindi)</label>
        <textarea name="bio_hi" id="bio_hi" rows="3" class="form-input" placeholder="Brief spiritual description, duties, or seva contribution in Trust..."></textarea>
      </div>

      <div style="margin-bottom: 20px;">
        <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
          <input type="checkbox" name="is_active" id="is_active" value="1" checked>
          <span>Active & Visible on Public Website</span>
        </label>
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid var(--line); padding-top: 16px;">
        <button type="button" onclick="closeModal()" class="action-btn">Cancel</button>
        <button type="submit" class="action-btn primary" id="submitBtn">Save Profile</button>
      </div>
    </form>
  </div>
</div>

<script>
  function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add Leadership Profile';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('leadershipForm').action = "{{ route('admin.leadership.store') }}";
    document.getElementById('leadershipForm').reset();
    document.getElementById('role_category').value = 'trustee';
    document.getElementById('sort_order').value = '0';
    document.getElementById('is_active').checked = true;
    document.getElementById('submitBtn').textContent = 'Create Profile';
    document.getElementById('leadershipModal').style.display = 'block';
  }

  function openEditModal(profile) {
    document.getElementById('modalTitle').textContent = 'Edit Profile: ' + profile.name_hi;
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('leadershipForm').action = "/admin/leadership/" + profile.id;
    document.getElementById('name_hi').value = profile.name_hi || '';
    document.getElementById('name_en').value = profile.name_en || '';
    document.getElementById('title_hi').value = profile.title_hi || '';
    document.getElementById('title_en').value = profile.title_en || '';
    document.getElementById('role_category').value = profile.role_category || 'trustee';
    document.getElementById('sort_order').value = profile.sort_order || 0;
    document.getElementById('photo_path').value = profile.photo_path || '';
    document.getElementById('bio_hi').value = profile.bio_hi || '';
    document.getElementById('is_active').checked = Boolean(profile.is_active);
    document.getElementById('submitBtn').textContent = 'Update Profile';
    document.getElementById('leadershipModal').style.display = 'block';
  }

  function closeModal() {
    document.getElementById('leadershipModal').style.display = 'none';
  }

  window.onclick = function(event) {
    var modal = document.getElementById('leadershipModal');
    if (event.target == modal) {
      closeModal();
    }
  }
</script>

@endsection
