@extends('layouts.admin')

@section('title', 'Chitrakoot Sacred Places CMS | CMS Admin')
@section('header_title', 'Sacred Places Showcase & Heritage CMS')
@section('header_subtitle', 'Manage Chitrakoot Dham Holy Sites, Parikrama Tirtha, Mandakini Ghats & Spiritual Heritage')

@section('header_actions')
  <a href="{{ route('chitrakoot.index') }}" target="_blank" class="action-btn" style="background: var(--sand); color: var(--deep); font-size: 11px; padding: 7px 14px;">
    View Public Chitrakoot Guide &nearr;
  </a>
@endsection

@section('content')
<!-- Stats Grid -->
<div class="stats-grid" style="margin-bottom: 24px;">
  <div class="stat-card">
    <div class="stat-label">TOTAL SACRED PLACES</div>
    <div class="stat-val" style="color: var(--crimson);">{{ $destinations->count() }}</div>
    <div class="stat-sub">Holy tirtha spots in Chitrakoot</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">PUBLISHED & LIVE</div>
    <div class="stat-val" style="color: #16a34a;">{{ $destinations->where('is_published', true)->count() }}</div>
    <div class="stat-sub">Featured in pilgrim guides</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">DRAFT / ARCHIVED</div>
    <div class="stat-val" style="color: #d97706;">{{ $destinations->where('is_published', false)->count() }}</div>
    <div class="stat-sub">Pending content completion</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">PARIKRAMA & GHATS</div>
    <div class="stat-val" style="color: #086c60;">{{ $destinations->count() > 0 ? $destinations->count() : 4 }}</div>
    <div class="stat-sub">Kamadgiri & Mandakini circuit</div>
  </div>
</div>

@if(session('success'))
  <div style="background: rgba(46,125,50,.12); color: #166534; padding: 14px 20px; border-radius: var(--radius); margin-bottom: 24px; border-left: 4px solid #166534; font-weight: 600; font-size: 13px;">
    {{ session('success') }}
  </div>
@endif

<div style="display: grid; grid-template-columns: 1fr 1.6fr; gap: 24px; align-items: start;">

  <!-- Add New Destination Card -->
  <div class="cms-table-card" style="padding: 26px;">
    <h3 style="font-size: 16px; font-weight: 800; color: var(--deep); margin: 0 0 6px;">Add New Sacred Spot</h3>
    <p style="font-size: 12px; color: var(--brown); margin: 0 0 20px;">Register a holy destination or tirtha spot with spiritual significance and photos.</p>

    <form action="{{ url('/admin/destinations') }}" method="POST">
      @csrf

      <div style="margin-bottom: 14px;">
        <label class="form-label">Name (Hindi) *</label>
        <input type="text" name="name_hi" required placeholder="e.g. कामदगिरि परिक्रमा एवं कामतानाथ" class="form-input" style="width: 100%; font-size: 14px; font-weight: 600;">
      </div>

      <div style="margin-bottom: 14px;">
        <label class="form-label">Name (English) *</label>
        <input type="text" name="name_en" required placeholder="e.g. Kamadgiri Parikrama & Kamtanath" class="form-input" style="width: 100%; font-size: 13px;">
      </div>

      <div style="margin-bottom: 14px;">
        <label class="form-label">Spiritual Significance / Tagline (Hindi) *</label>
        <textarea name="tagline_hi" rows="2" required placeholder="e.g. 5-km sacred parikrama path in Hindi..." class="form-input" style="width: 100%; font-size: 12.5px;"></textarea>
      </div>

      <div style="margin-bottom: 14px;">
        <label class="form-label">Full Tirtha Description (Hindi) *</label>
        <textarea name="description_hi" rows="4" required placeholder="History, puranic lore, darshan timings and parikrama guidelines in Hindi..." class="form-input" style="width: 100%; font-size: 12.5px; line-height: 1.5;"></textarea>
      </div>

      <div style="margin-bottom: 16px;">
        <label class="form-label">Featured Image URL *</label>
        <div style="display: flex; gap: 8px;">
          <input type="text" name="featured_image" id="dest_add_img" required placeholder="/uploads/destination.webp or image path" class="form-input" style="flex: 1; font-size: 12px;">
          <button type="button" onclick="openUniversalMediaPicker({ targetInput: '#dest_add_img' })" class="action-btn teal" style="padding: 7px 12px; font-size: 10px;">REUSE</button>
        </div>
      </div>

      <div style="margin-bottom: 22px;">
        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px; font-weight: 700; color: var(--deep);">
          <input type="checkbox" name="is_published" value="1" checked style="width: 16px; height: 16px; accent-color: var(--crimson);">
          <span>Publish Immediately on Pilgrim Guide</span>
        </label>
      </div>

      <button type="submit" class="action-btn primary" style="width: 100%; justify-content: center; padding: 11px; font-weight: 800; font-size: 12px;">
        SAVE SACRED SPOT &rarr;
      </button>
    </form>
  </div>

  <!-- Existing Sacred Spots Directory Table Card -->
  <div class="cms-table-card">
    <div style="padding: 18px 24px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center;">
      <div>
        <h3 style="font-size: 16px; font-weight: 800; color: var(--deep); margin: 0;">Chitrakoot Sacred Places ({{ $destinations->count() }})</h3>
        <span style="font-size: 11px; color: var(--brown);">Holy shrines, parikrama hills, and Ramayan heritage locations</span>
      </div>
    </div>

    <table class="cms-table" style="width: 100%; border-collapse: collapse;">
      <thead>
        <tr style="background: #faf8f5; border-bottom: 1px solid var(--line); text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--brown);">
          <th style="padding: 12px 20px;">Sacred Spot</th>
          <th style="padding: 12px 16px;">Significance</th>
          <th style="padding: 12px 16px;">Status</th>
          <th style="padding: 12px 20px; text-align: right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($destinations as $d)
          <tr style="border-bottom: 1px solid var(--line);">
            <td style="padding: 14px 20px;">
              <div style="display: flex; align-items: center; gap: 12px;">
                <img src="{{ $d->featured_image ?: '/images/chitrakoot-hero.webp' }}" style="width: 48px; height: 48px; border-radius: 6px; object-fit: cover; border: 1px solid var(--line); flex-shrink: 0;" alt="">
                <div>
                  <strong style="color: var(--deep); font-size: 14px; display: block;">{{ $d->name_hi }}</strong>
                  <span style="font-size: 11px; color: var(--brown);">{{ $d->name_en }} &bull; Slug: <code>{{ $d->slug }}</code></span>
                </div>
              </div>
            </td>
            <td style="padding: 14px 16px; font-size: 12px; color: var(--deep); max-width: 200px;">
              {{ Str::limit($d->tagline_hi, 55) }}
            </td>
            <td style="padding: 14px 16px;">
              @if($d->is_published)
                <span class="badge" style="background: #dcfce7; color: #166534; font-weight: 700; font-size: 10px;">PUBLISHED</span>
              @else
                <span class="badge" style="background: #fee2e2; color: #991b1b; font-weight: 700; font-size: 10px;">DRAFT</span>
              @endif
            </td>
            <td style="padding: 14px 20px; text-align: right;">
              <div style="display: inline-flex; gap: 6px;">
                <button type="button" 
                        onclick='editDestinationModal(@json($d))' 
                        class="action-btn" 
                        style="background: var(--sand); color: var(--deep); padding: 5px 10px; font-size: 11px;">
                  Edit
                </button>
                <a href="{{ route('chitrakoot.show', $d->slug) }}" target="_blank" class="action-btn" style="font-size: 11px; padding: 5px 8px;" title="View public page">
                  &nearr;
                </a>
                <form action="{{ url('/admin/destinations/' . $d->id) }}" method="POST" onsubmit="return confirm('Delete this sacred destination permanently?')" style="margin: 0; display: inline;">
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
              No sacred places recorded yet.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</div>

<!-- Edit Destination Modal -->
<div id="editDestinationModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
  <div style="background: #ffffff; width: 100%; max-width: 650px; max-height: 90vh; overflow-y: auto; border-radius: var(--radius); padding: 28px; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--line); padding-bottom: 12px; margin-bottom: 18px;">
      <h3 id="editDestModalTitle" style="font-size: 16px; font-weight: 800; color: var(--deep); margin: 0;">Edit Sacred Spot</h3>
      <button type="button" onclick="closeEditDestinationModal()" style="background: none; border: 0; font-size: 24px; color: var(--brown); cursor: pointer;">&times;</button>
    </div>

    <form id="editDestinationForm" method="POST">
      @csrf
      @method('PUT')

      <div style="margin-bottom: 14px;">
        <label class="form-label">Name (Hindi) *</label>
        <input type="text" name="name_hi" id="edit_dest_name_hi" required class="form-input" style="width: 100%; font-size: 14px; font-weight: 700;">
      </div>

      <div style="margin-bottom: 14px;">
        <label class="form-label">Name (English) *</label>
        <input type="text" name="name_en" id="edit_dest_name_en" required class="form-input" style="width: 100%; font-size: 13px;">
      </div>

      <div style="margin-bottom: 14px;">
        <label class="form-label">Spiritual Significance / Tagline (Hindi) *</label>
        <textarea name="tagline_hi" id="edit_dest_tagline_hi" rows="2" required class="form-input" style="width: 100%; font-size: 12.5px;"></textarea>
      </div>

      <div style="margin-bottom: 14px;">
        <label class="form-label">Full Tirtha Description (Hindi) *</label>
        <textarea name="description_hi" id="edit_dest_description_hi" rows="4" required class="form-input" style="width: 100%; font-size: 12.5px; line-height: 1.5;"></textarea>
      </div>

      <div style="margin-bottom: 16px;">
        <label class="form-label">Featured Image URL *</label>
        <div style="display: flex; gap: 8px;">
          <input type="text" name="featured_image" id="edit_dest_img" required class="form-input" style="flex: 1; font-size: 12px;">
          <button type="button" onclick="openUniversalMediaPicker({ targetInput: '#edit_dest_img' })" class="action-btn teal" style="padding: 7px 12px; font-size: 10px;">REUSE</button>
        </div>
      </div>

      <div style="margin-bottom: 22px;">
        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px; font-weight: 700; color: var(--deep);">
          <input type="checkbox" name="is_published" id="edit_dest_published" value="1" style="width: 16px; height: 16px; accent-color: var(--crimson);">
          <span>Published Live on Public Portal</span>
        </label>
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 8px; border-top: 1px solid var(--line); padding-top: 16px;">
        <button type="button" onclick="closeEditDestinationModal()" class="action-btn" style="background: var(--sand); color: var(--deep);">Cancel</button>
        <button type="submit" class="action-btn primary" style="font-weight: 800;">UPDATE SACRED PLACE &rarr;</button>
      </div>
    </form>
  </div>
</div>

@include('admin.media-picker-modal')

<script>
  function editDestinationModal(dest) {
    document.getElementById('editDestModalTitle').textContent = 'Edit: ' + dest.name_en;
    document.getElementById('editDestinationForm').action = '/admin/destinations/' + dest.id;
    document.getElementById('edit_dest_name_hi').value = dest.name_hi || '';
    document.getElementById('edit_dest_name_en').value = dest.name_en || '';
    document.getElementById('edit_dest_tagline_hi').value = dest.tagline_hi || '';
    document.getElementById('edit_dest_description_hi').value = dest.description_hi || '';
    document.getElementById('edit_dest_img').value = dest.featured_image || '';
    document.getElementById('edit_dest_published').checked = !!dest.is_published;
    document.getElementById('editDestinationModal').style.display = 'flex';
  }

  function closeEditDestinationModal() {
    document.getElementById('editDestinationModal').style.display = 'none';
  }
</script>
@endsection