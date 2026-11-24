@extends('layouts.admin')

@section('title', 'Upcoming Events & Utsavs | CMS Admin')
@section('header_title', 'Upcoming Events & Religious Utsavs')
@section('header_subtitle', 'Create and manage religious festivals, aarti mahotsavs, bhajan sandhyas, and temple events displayed dynamically on the homepage')

@section('header_actions')
  <form action="{{ route('admin.events.autoArchive') }}" method="POST" style="display: inline-block;">
    @csrf
    <button type="submit" class="action-btn" style="background: #2B1A12; color: #fff; font-size: 11px; padding: 7px 14px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;">
      <x-icon name="zap" size="12" /> Auto-Expire Passed Events
    </button>
  </form>
  <a href="{{ url('/#events') }}" target="_blank" class="action-btn" style="background: #ffffff; color: var(--deep); font-size: 11px; padding: 7px 14px; box-shadow: 0 2px 6px rgba(0,0,0,0.05); text-decoration: none;">
    Preview Events on Homepage &nearr;
  </a>
@endsection

@section('content')

<!-- Dynamic Visibility Notice -->
<div class="cms-table-card" style="margin-bottom: 24px; padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; background: #FAF4ED; border: 1px solid rgba(180, 83, 9, 0.25); border-radius: 12px;">
  <div style="display: flex; align-items: center; gap: 12px;">
    <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(217, 93, 15, 0.15); color: #D95D0F; display: grid; place-items: center; flex-shrink: 0;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    </div>
    <div>
      <strong style="color: #2B1A12; font-size: 13.5px; display: block;">Dynamic Homepage Visibility & Auto-Archive Rule</strong>
      <span style="font-size: 11.5px; color: #7A4B29;">
        All past events whose scheduled date/time has passed are automatically expired and moved to the <strong>Archive</strong>. Active upcoming events display on Section 8 of the homepage; if 0 upcoming events remain, the homepage section hides automatically.
      </span>
    </div>
  </div>
  <span class="badge {{ $upcomingCount > 0 ? 'success' : 'warn' }}" style="padding: 6px 12px; font-size: 11px; border-radius: 9999px;">
    {{ $upcomingCount > 0 ? $upcomingCount . ' Upcoming Events Active' : '0 Upcoming (Section Hidden)' }}
  </span>
</div>

<!-- Stats Row -->
<div class="stats-grid" style="margin-bottom: 24px;">
  <div class="stat-card">
    <div class="stat-label">TOTAL EVENTS</div>
    <div class="stat-val" style="color: var(--deep);">{{ $totalCount }}</div>
    <div class="stat-sub">Recorded in calendar</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">UPCOMING / ACTIVE</div>
    <div class="stat-val" style="color: #16a34a;">{{ $upcomingCount }}</div>
    <div class="stat-sub">Live on website</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">ARCHIVED / EXPIRED</div>
    <div class="stat-val" style="color: #64748b;">{{ $archivedCount ?? $pastCount }}</div>
    <div class="stat-sub">Passed events in archive</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">PUBLISHED</div>
    <div class="stat-val" style="color: #d97706;">{{ $publishedCount }}</div>
    <div class="stat-sub">Publicly accessible</div>
  </div>
</div>

<!-- Add Event Card -->
<div class="card" style="margin-bottom: 28px; padding: 24px; border-radius: 14px;">
  <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px; border-bottom: 1px solid rgba(180, 83, 9, 0.15); padding-bottom: 12px; flex-wrap: wrap; gap: 10px;">
    <div>
      <h3 style="font-family: 'Noto Serif', serif; font-size: 16px; font-weight: 700; color: #2B1A12; margin: 0 0 2px;">
        + Schedule New Religious Event / Utsav
      </h3>
      <span style="font-size: 12px; color: #7A4B29;">This event will be displayed on the homepage upcoming events strip.</span>
    </div>
  </div>

  <!-- Quick Utsav Presets Bar -->
  <div style="background: #FFF7ED; border: 1.5px solid rgba(217,93,15,0.25); border-radius: 12px; padding: 12px 16px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
    <div style="display: flex; align-items: center; gap: 8px;">
      <span style="display: inline-flex; color: #9A3412;"><x-icon name="zap" size="16" /></span>
      <div>
        <span style="font-size: 12.5px; font-weight: 800; color: #9A3412; display: block;">Quick Presets from Utsav Directory:</span>
        <span style="font-size: 11px; color: #7A4B29;">Click any authentic Chitrakoot festival below to instantly auto-fill all event fields</span>
      </div>
    </div>
    <select id="eventPresetSelect" onchange="applyEventPreset(this.value)" class="form-select" style="font-size: 12px; font-weight: 700; padding: 8px 14px; min-width: 320px; background: #fff; border-color: rgba(217,93,15,0.35); color: #2B1A12; border-radius: 8px;">
      <option value="">-- Choose Sacred Festival / Utsav Preset --</option>
      <option value="ramnavami">1. Shri Ram Navami Janmotsav & Maha Aarti</option>
      <option value="deepotsav">2. Deepawali Deepotsav & 108 Mandakini Maha Aarti</option>
      <option value="sharadpurnima">3. Sharad Purnima Kamadgiri Akhand Parikrama</option>
      <option value="shivratri">4. Mahashivratri Rudrabhishek & Bhajan Sandhya</option>
      <option value="hanumanjayanti">5. Shri Hanuman Jayanti Janmotsav & Sundarkand</option>
      <option value="gopashtami">6. Gopashtami Kamadgiri Gau Pujan Mahotsav</option>
      <option value="makarsankranti">7. Makar Sankranti Mandakini Maha Snan & Til Daan</option>
    </select>
  </div>

  <form id="createEventForm" action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; margin-bottom: 16px;">
      <div>
        <label class="form-label" style="display: flex; justify-content: space-between;">
          <span>EVENT TITLE (HINDI) *</span>
          @error('title_hi')<span style="color: #dc2626; font-size: 11px;">{{ $message }}</span>@enderror
        </label>
        <input type="text" id="create_title_hi" name="title_hi" value="{{ old('title_hi') }}" placeholder="e.g. श्री राम नवमी जन्मोत्सव एवं महाआरती" class="form-input" style="{{ $errors->has('title_hi') ? 'border-color: #ef4444;' : '' }}">
      </div>
      <div>
        <label class="form-label">EVENT TITLE (ENGLISH)</label>
        <input type="text" id="create_title_en" name="title_en" value="{{ old('title_en') }}" placeholder="e.g. Ram Navami Mahotsav & Deepotsav" class="form-input">
      </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 16px;">
      <div>
        <label class="form-label" style="display: flex; justify-content: space-between;">
          <span>EVENT DATE & TIME *</span>
          @error('event_date')<span style="color: #dc2626; font-size: 11px;">{{ $message }}</span>@enderror
        </label>
        <input type="datetime-local" id="create_event_date" name="event_date" required class="form-input" value="{{ old('event_date', now()->addDays(7)->format('Y-m-d\TH:i')) }}" style="{{ $errors->has('event_date') ? 'border-color: #ef4444;' : '' }}">
      </div>
      <div>
        <label class="form-label" style="display: flex; justify-content: space-between;">
          <span>END DATE & TIME (OPTIONAL)</span>
          @error('event_end_date')<span style="color: #dc2626; font-size: 11px;">{{ $message }}</span>@enderror
        </label>
        <input type="datetime-local" id="create_event_end_date" name="event_end_date" value="{{ old('event_end_date') }}" class="form-input" style="{{ $errors->has('event_end_date') ? 'border-color: #ef4444;' : '' }}">
      </div>
      <div>
        <label class="form-label">LOCATION (HINDI)</label>
        <input type="text" id="create_location_hi" name="location_hi" value="{{ old('location_hi', 'रामघाट एवं कामदगिरि पीठम्, चित्रकूट') }}" placeholder="रामघाट एवं कामदगिरि पीठम्, चित्रकूट" class="form-input">
      </div>
      <div>
        <label class="form-label">LOCATION (ENGLISH)</label>
        <input type="text" id="create_location_en" name="location_en" value="{{ old('location_en', 'Ramghat, Chitrakoot Dham') }}" placeholder="Ramghat, Chitrakoot Dham" class="form-input">
      </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
      <div>
        <label class="form-label">SHORT SUMMARY (HINDI)</label>
        <textarea id="create_summary_hi" name="summary_hi" rows="2" placeholder="Brief summary in Hindi (e.g. विशेष पुष्प वृष्टि, महाभोग एवं 108 दीपदान)" class="form-textarea">{{ old('summary_hi') }}</textarea>
      </div>
      <div>
        <label class="form-label">DETAILED DESCRIPTION (HINDI)</label>
        <textarea id="create_description_hi" name="description_hi" rows="2" placeholder="Detailed program outline in Hindi..." class="form-textarea">{{ old('description_hi') }}</textarea>
      </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 18px;">
      <div>
        <label class="form-label">FEATURED IMAGE URL</label>
        <div style="display: flex; gap: 8px;">
          <input type="text" id="create_featured_image" name="featured_image" value="{{ old('featured_image') }}" placeholder="https://... or /images/..." class="form-input">
          <button type="button" onclick="openUniversalMediaPicker({ targetInput: '#create_featured_image' })" class="action-btn teal" style="padding: 7px 12px; font-size: 11px; white-space: nowrap;"><x-icon name="image" size="12" /> Reuse</button>
        </div>
      </div>
      <div>
        <label class="form-label">OR UPLOAD BANNER PHOTO</label>
        <input type="file" name="featured_image_file" accept="image/*" class="form-input" style="padding: 6px;">
      </div>
      <div>
        <label class="form-label">DEVOTEE REGISTRATION LINK (OPTIONAL)</label>
        <input type="text" id="create_registration_url" name="registration_url" value="{{ old('registration_url') }}" placeholder="/volunteer or https://..." class="form-input">
      </div>
    </div>

    <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px solid rgba(180,83,9,0.15); padding-top: 14px;">
      <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer;">
        <input type="checkbox" name="is_published" value="1" {{ old('is_published', '1') ? 'checked' : '' }} style="accent-color: #D95D0F; width: 17px; height: 17px;">
        <span style="font-size: 13px; font-weight: 700; color: #2B1A12;">Publish Immediately on Homepage</span>
      </label>

      <button type="submit" class="action-btn primary" style="background: #B45309; padding: 10px 22px; font-size: 13px; font-weight: 700;">
        + Save & Schedule Event
      </button>
    </div>
  </form>
</div>

<!-- Events Table & Filter -->
<div class="cms-table-card">
  <div style="padding: 16px 20px; border-bottom: 1px solid var(--line); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
    <div style="display: flex; align-items: center; gap: 10px;">
      <h3 style="font-size: 15px; font-weight: 800; color: var(--deep); margin: 0;">
        All Scheduled Events & Utsavs
      </h3>
      <span class="badge" style="background: #FAF4ED; color: #B45309; font-size: 11px;">{{ $events->total() }}</span>
    </div>

    <!-- Filter & Search -->
    <form action="{{ route('admin.events') }}" method="GET" style="display: flex; align-items: center; gap: 10px; margin: 0;">
      <div style="display: flex; border: 1px solid var(--line); border-radius: 8px; overflow: hidden; background: #fff;">
        <a href="{{ route('admin.events', ['status' => 'all', 'q' => $q]) }}" style="padding: 6px 12px; font-size: 11.5px; text-decoration: none; font-weight: 600; {{ $status === 'all' ? 'background: #B45309; color: #fff;' : 'color: var(--deep);' }}">All ({{ $totalCount }})</a>
        <a href="{{ route('admin.events', ['status' => 'upcoming', 'q' => $q]) }}" style="padding: 6px 12px; font-size: 11.5px; text-decoration: none; font-weight: 600; {{ $status === 'upcoming' ? 'background: #B45309; color: #fff;' : 'color: var(--deep);' }}">Upcoming ({{ $upcomingCount }})</a>
        <a href="{{ route('admin.events', ['status' => 'archived', 'q' => $q]) }}" style="padding: 6px 12px; font-size: 11.5px; text-decoration: none; font-weight: 600; {{ $status === 'archived' || $status === 'past' ? 'background: #B45309; color: #fff;' : 'color: var(--deep);' }}">Archived ({{ $archivedCount ?? $pastCount }})</a>
        <a href="{{ route('admin.events', ['status' => 'draft', 'q' => $q]) }}" style="padding: 6px 12px; font-size: 11.5px; text-decoration: none; font-weight: 600; {{ $status === 'draft' ? 'background: #B45309; color: #fff;' : 'color: var(--deep);' }}">Draft</a>
      </div>

      <input type="text" name="q" value="{{ $q }}" placeholder="Search events..." class="form-input" style="padding: 6px 10px; font-size: 12px; width: 170px;">
      <button type="submit" class="action-btn" style="padding: 6px 12px; font-size: 11px;">Search</button>
    </form>
  </div>

  <table class="cms-table">
    <thead>
      <tr>
        <th style="width: 140px;">Date & Time</th>
        <th>Event Details</th>
        <th>Location</th>
        <th style="text-align: center;">Website Status</th>
        <th style="text-align: right; width: 200px;">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($events as $event)
        @php
          $isArchived = $event->is_archived || $event->isExpired();
        @endphp
        <tr>
          <td>
            <div style="display: flex; align-items: center; gap: 8px;">
              <div style="width: 44px; height: 48px; border-radius: 8px; background: #FAF4ED; color: {{ $isArchived ? '#64748B' : '#D95D0F' }}; display: flex; flex-direction: column; align-items: center; justify-content: center; border: 1px solid {{ $isArchived ? 'rgba(100,116,139,0.3)' : 'rgba(217, 93, 15, 0.3)' }}; font-weight: 800; line-height: 1.1;">
                <span style="font-size: 16px;">{{ $event->event_date->format('d') }}</span>
                <span style="font-size: 9px; text-transform: uppercase;">{{ $event->event_date->format('M') }}</span>
              </div>
              <div>
                <span style="font-size: 11.5px; font-weight: 700; color: #2B1A12; display: block;">{{ $event->event_date->format('Y') }}</span>
                <span style="font-size: 10.5px; color: #7A4B29;">{{ $event->event_date->format('h:i A') }}</span>
              </div>
            </div>
          </td>
          <td>
            <div style="display: flex; align-items: center; gap: 10px;">
              @if($event->featured_image)
                <img src="{{ $event->featured_image }}" alt="" style="width: 44px; height: 44px; border-radius: 8px; object-fit: cover; border: 1px solid rgba(180, 83, 9, 0.2);">
              @endif
              <div>
                <strong style="color: #2B1A12; font-size: 13.5px; display: block;">{{ $event->title_hi }}</strong>
                @if($event->title_en && $event->title_en !== $event->title_hi)
                  <span style="font-size: 11.5px; color: #7A4B29; display: block;">{{ $event->title_en }}</span>
                @endif
                @if($event->summary_hi)
                  <span style="font-size: 11px; color: #8C6A53; display: block; margin-top: 2px;">{{ Str::limit($event->summary_hi, 70) }}</span>
                @endif
              </div>
            </div>
          </td>
          <td>
            <span style="font-size: 12px; color: #2B1A12; font-weight: 600; display: block;">{{ $event->location_hi ?? 'चित्रकूट धाम' }}</span>
            <span style="font-size: 10.5px; color: #7A4B29;">{{ $event->location_en ?? 'Chitrakoot' }}</span>
          </td>
          <td style="text-align: center;">
            @if(! $event->is_published)
              <span class="badge" style="background: #e2e8f0; color: #475569; font-size: 10px;">DRAFT</span>
            @elseif($isArchived)
              <span class="badge" style="background: #f1f5f9; color: #475569; font-size: 10px; font-weight: 700; border: 1px solid #cbd5e1;">ARCHIVED</span>
            @else
              <span class="badge" style="background: #dcfce7; color: #166534; font-size: 10px; font-weight: 700;">UPCOMING • LIVE</span>
            @endif
          </td>
          <td style="text-align: right;">
            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 6px;">
              <form action="{{ route('admin.events.toggleArchive', $event->id) }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="action-btn" title="{{ $event->is_archived ? 'Restore to Active' : 'Move to Archive' }}" style="padding: 5px 9px; font-size: 10.5px; {{ $event->is_archived ? 'background: #eff6ff; color: #1e40af; border-color: #93c5fd;' : 'background: #f8fafc; color: #475569;' }}">
                  {{ $event->is_archived ? 'Restore' : 'Archive' }}
                </button>
              </form>

              <button type="button" onclick="openEditEventModal({{ json_encode($event) }})" class="action-btn" style="padding: 5px 10px; font-size: 11px;">
                Edit
              </button>

              <form action="{{ route('admin.events.delete', $event->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this event?');" style="margin: 0;">
                @csrf
                @method('DELETE')
                <button type="submit" class="action-btn" style="background: #fee2e2; color: #991b1b; padding: 5px 10px; font-size: 11px; border: 1px solid #f87171;">
                  Delete
                </button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="5" style="text-align: center; padding: 36px; color: #7A4B29;">
            <div style="font-size: 14px; font-weight: 600; margin-bottom: 4px;">No events found in this category.</div>
            <div style="font-size: 12px; color: #A18269;">Schedule an upcoming festival or utsav using the form above.</div>
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>

  @if($events->hasPages())
    <div style="padding: 14px 20px; border-top: 1px solid var(--line);">
      {{ $events->links() }}
    </div>
  @endif
</div>

<!-- Edit Event Modal -->
<div id="editEventModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.55); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 20px; box-sizing: border-box;">
  <div style="background: #FFFDFC; width: 100%; max-width: 640px; border-radius: 16px; border: 1px solid rgba(180, 83, 9, 0.3); box-shadow: 0 20px 40px rgba(0,0,0,0.2); max-height: 90vh; overflow-y: auto; padding: 24px; box-sizing: border-box;">
    
    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(180, 83, 9, 0.15); padding-bottom: 12px; margin-bottom: 16px;">
      <h3 style="font-family: 'Noto Serif', serif; font-size: 17px; font-weight: 700; color: #2B1A12; margin: 0;">
        Edit Event / Utsav Details
      </h3>
      <button type="button" onclick="closeEditEventModal()" style="background: none; border: none; font-size: 22px; cursor: pointer; color: #7A4B29;">&times;</button>
    </div>

    <form id="editEventForm" action="" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div style="margin-bottom: 14px;">
        <label class="form-label">EVENT TITLE (HINDI) *</label>
        <input type="text" id="edit_title_hi" name="title_hi" required class="form-input">
      </div>

      <div style="margin-bottom: 14px;">
        <label class="form-label">EVENT TITLE (ENGLISH)</label>
        <input type="text" id="edit_title_en" name="title_en" class="form-input">
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
        <div>
          <label class="form-label">EVENT DATE & TIME *</label>
          <input type="datetime-local" id="edit_event_date" name="event_date" required class="form-input">
        </div>
        <div>
          <label class="form-label">END DATE (OPTIONAL)</label>
          <input type="datetime-local" id="edit_event_end_date" name="event_end_date" class="form-input">
        </div>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
        <div>
          <label class="form-label">LOCATION (HINDI)</label>
          <input type="text" id="edit_location_hi" name="location_hi" class="form-input">
        </div>
        <div>
          <label class="form-label">LOCATION (ENGLISH)</label>
          <input type="text" id="edit_location_en" name="location_en" class="form-input">
        </div>
      </div>

      <div style="margin-bottom: 14px;">
        <label class="form-label">SUMMARY (HINDI)</label>
        <textarea id="edit_summary_hi" name="summary_hi" rows="2" class="form-textarea"></textarea>
      </div>

      <div style="margin-bottom: 14px;">
        <label class="form-label">FEATURED IMAGE URL</label>
        <div style="display: flex; gap: 8px; margin-bottom: 6px;">
          <input type="text" id="edit_featured_image" name="featured_image" class="form-input">
          <button type="button" onclick="openUniversalMediaPicker({ targetInput: '#edit_featured_image' })" class="action-btn teal" style="padding: 7px 12px; font-size: 11px; white-space: nowrap;"><x-icon name="image" size="12" /> Reuse</button>
        </div>
        <label class="form-label" style="font-size: 10px; color: #7A4B29;">OR UPLOAD REPLACEMENT IMAGE</label>
        <input type="file" name="featured_image_file" accept="image/*" class="form-input" style="padding: 6px;">
      </div>

      <div style="margin-bottom: 18px;">
        <label class="form-label">REGISTRATION / VOLUNTEER LINK</label>
        <input type="text" id="edit_registration_url" name="registration_url" class="form-input">
      </div>

      <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px solid rgba(180, 83, 9, 0.15); padding-top: 14px;">
        <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer;">
          <input type="checkbox" id="edit_is_published" name="is_published" value="1" style="accent-color: #D95D0F; width: 17px; height: 17px;">
          <span style="font-size: 13px; font-weight: 700; color: #2B1A12;">Published & Active on Homepage</span>
        </label>

        <div style="display: flex; gap: 10px;">
          <button type="button" onclick="closeEditEventModal()" class="action-btn" style="padding: 8px 16px;">Cancel</button>
          <button type="submit" class="action-btn primary" style="background: #B45309; padding: 8px 18px;">Update Event</button>
        </div>
      </div>
    </form>

  </div>
</div>

<script>
const EVENT_PRESETS = {
  ramnavami: {
    title_hi: 'श्री राम नवमी जन्मोत्सव एवं महाआरती',
    title_en: 'Ram Navami Mahotsav & Deepotsav',
    location_hi: 'रामघाट एवं कामदगिरि पीठम्, चित्रकूट',
    location_en: 'Ramghat & Kamadgiri Peetham, Chitrakoot Dham',
    summary_hi: 'विशेष पुष्प वृष्टि, 108 मंदाकिनी महाआरती, बधाई गान एवं महाप्रसाद वितरण।',
    description_hi: 'मर्यादा पुरुषोत्तम भगवान श्री राम के पावन प्राकट्य उत्सव पर भव्य शोभायात्रा, कामदगिरि अखंड परिक्रमा तथा मंदाकिनी पावन तट पर 5100 दीप प्रज्वलन।',
    featured_image: '/images/ram-ghat-aarti.webp',
    registration_url: '/volunteer',
    offset_days: 7,
    hour: 18,
    minute: 0
  },
  deepotsav: {
    title_hi: 'दीपावली दीपोत्सव एवं 108 मंदाकिनी महाआरती',
    title_en: 'Deepawali Deepotsav & Mandakini Maha Aarti',
    location_hi: 'रामघाट, मंदाकिनी पावन तट, चित्रकूट',
    location_en: 'Ramghat, Holy Mandakini Banks, Chitrakoot',
    summary_hi: '21,000 पावन दीपदान, भव्य गंगा-मंदाकिनी महाआरती एवं संत-भक्त समागम।',
    description_hi: 'भगवान श्री राम के वनवास पश्चात चित्रकूट वास एवं पावन स्मृति में संपूर्ण मंदाकिनी तट पर भव्य दीपदान, भजन संध्या व लेज़र आरती।',
    featured_image: '/images/chitrakoot-hero.webp',
    registration_url: '/volunteer',
    offset_days: 14,
    hour: 18,
    minute: 30
  },
  sharadpurnima: {
    title_hi: 'शरद पूर्णिमा कामदगिरि अखंड परिक्रमा',
    title_en: 'Sharad Purnima Kamadgiri Akhand Parikrama',
    location_hi: 'कामदगिरि प्रथम मुखारविंद, चित्रकूट',
    location_en: 'Kamadgiri Pratham Mukharvind, Chitrakoot',
    summary_hi: 'अमृतमयी चंद्र-किरणों में दिव्य परिक्रमा, खीर महाप्रसाद वितरण एवं भजन संध्या।',
    description_hi: 'शरद पूर्णिमा की पावन रजनी में कामदगिरि पर्वत की 5 किमी पंचकोसीय अखंड परिक्रमा तथा अमृतोपम महाप्रसाद वितरण।',
    featured_image: '/images/kamadgiri-mountain.webp',
    registration_url: '/volunteer',
    offset_days: 21,
    hour: 19,
    minute: 0
  },
  shivratri: {
    title_hi: 'महाशिवरात्रि रुद्राभिषेक एवं भजन संध्या',
    title_en: 'Mahashivratri Rudrabhishek & Bhajan Sandhya',
    location_hi: 'कोटितीर्थ व कामदगिरि शिव मंदिर, चित्रकूट',
    location_en: 'Kotitirth & Kamadgiri Shiva Temple, Chitrakoot',
    summary_hi: 'एकादश रुद्राभिषेक, अखंड महामृत्युंजय जाप एवं रात्रि जागरण भजन संध्या।',
    description_hi: 'भगवान शिव के पावन पर्व पर वैदिक ब्राह्मणों द्वारा मंदाकिनी जल से निरंतर महाभिषेक, बिल्वपत्र अर्चन एवं फलाहार वितरण।',
    featured_image: '/images/chitrakoot-hero.webp',
    registration_url: '/volunteer',
    offset_days: 30,
    hour: 6,
    minute: 0
  },
  hanumanjayanti: {
    title_hi: 'श्री हनुमान जयंती जन्मोत्सव एवं सुंदरकांड',
    title_en: 'Shri Hanuman Jayanti Mahotsav & Sundarkand',
    location_hi: 'हनुमान धारा एवं कामदगिरि, चित्रकूट',
    location_en: 'Hanuman Dhara & Kamadgiri, Chitrakoot',
    summary_hi: '108 सुंदरकांड पाठ, सिंदूर चोला अर्पण एवं विशाल भंडारा।',
    description_hi: 'संकटमोचन श्री हनुमान जी के प्राकट्य दिवस पर हनुमान धारा की पावन सीढ़ियों पर संकीर्तन एवं अखंड प्रसाद वितरण।',
    featured_image: '/images/hanuman-dhara.webp',
    registration_url: '/volunteer',
    offset_days: 45,
    hour: 8,
    minute: 0
  },
  gopashtami: {
    title_hi: 'गोपाष्टमी कामदगिरि गौ-पूजन महोत्सव',
    title_en: 'Gopashtami Kamadgiri Gau-Pujan Mahotsav',
    location_hi: 'कामदगिरि सुरभि गौशाला, चित्रकूट',
    location_en: 'Kamadgiri Surabhi Gaushala, Chitrakoot',
    summary_hi: '500+ देशी गौमाताओं का तिलक-पूजन, गोग्रास अर्पण एवं गौ-संरक्षण संकल्प।',
    description_hi: 'कार्तिक शुक्ल अष्टमी को श्री कामदगिरि गौशाला में विशेष गौ-आरती, हरा चारा व गुड़ वितरण।',
    featured_image: '/images/chitrakoot-hero.webp',
    registration_url: '/volunteer',
    offset_days: 60,
    hour: 9,
    minute: 0
  },
  makarsankranti: {
    title_hi: 'मकर संक्रांति मंदाकिनी महास्नान व तिल-दान',
    title_en: 'Makar Sankranti Mandakini Mahasnan & Til Daan',
    location_hi: 'रामघाट एवं राघव प्रयाग संगम, चित्रकूट',
    location_en: 'Ramghat & Raghav Prayag, Chitrakoot',
    summary_hi: 'पावन मंदाकिनी में पुण्य स्नान, सूर्य नारायण अर्घ्य एवं खिचड़ी-तिल महाप्रसाद।',
    description_hi: 'सूर्य के उत्तरायण प्रवेश पर हजारों श्रद्धालुओं का पावन संगम स्नान, गोदान व निर्धन नारायण सेवा।',
    featured_image: '/images/ram-ghat-aarti.webp',
    registration_url: '/volunteer',
    offset_days: 75,
    hour: 6,
    minute: 30
  }
};

function applyEventPreset(key) {
  if (!key || !EVENT_PRESETS[key]) return;
  const p = EVENT_PRESETS[key];
  document.getElementById('create_title_hi').value = p.title_hi;
  document.getElementById('create_title_en').value = p.title_en;
  document.getElementById('create_location_hi').value = p.location_hi;
  document.getElementById('create_location_en').value = p.location_en;
  document.getElementById('create_summary_hi').value = p.summary_hi;
  document.getElementById('create_description_hi').value = p.description_hi;
  document.getElementById('create_featured_image').value = p.featured_image;
  document.getElementById('create_registration_url').value = p.registration_url;

  const now = new Date();
  now.setDate(now.getDate() + (p.offset_days || 7));
  now.setHours(p.hour || 18, p.minute || 0, 0, 0);
  const pad = n => String(n).padStart(2, '0');
  const iso = now.getFullYear() + '-' + pad(now.getMonth() + 1) + '-' + pad(now.getDate()) + 'T' + pad(now.getHours()) + ':' + pad(now.getMinutes());
  document.getElementById('create_event_date').value = iso;
}

function openEditEventModal(event) {
  const modal = document.getElementById('editEventModal');
  const form = document.getElementById('editEventForm');
  form.action = '/admin/events/' + event.id;

  document.getElementById('edit_title_hi').value = event.title_hi || '';
  document.getElementById('edit_title_en').value = event.title_en || '';
  
  if (event.event_date) {
    const d = new Date(event.event_date);
    const pad = n => String(n).padStart(2, '0');
    const localIso = d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate()) + 'T' + pad(d.getHours()) + ':' + pad(d.getMinutes());
    document.getElementById('edit_event_date').value = localIso;
  }
  if (event.event_end_date) {
    const d = new Date(event.event_end_date);
    const pad = n => String(n).padStart(2, '0');
    const localIso = d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate()) + 'T' + pad(d.getHours()) + ':' + pad(d.getMinutes());
    document.getElementById('edit_event_end_date').value = localIso;
  } else {
    document.getElementById('edit_event_end_date').value = '';
  }

  document.getElementById('edit_location_hi').value = event.location_hi || '';
  document.getElementById('edit_location_en').value = event.location_en || '';
  document.getElementById('edit_summary_hi').value = event.summary_hi || '';
  document.getElementById('edit_featured_image').value = event.featured_image || '';
  document.getElementById('edit_registration_url').value = event.registration_url || '';
  document.getElementById('edit_is_published').checked = !!event.is_published;

  modal.style.display = 'flex';
}

function closeEditEventModal() {
  document.getElementById('editEventModal').style.display = 'none';
}
</script>

@endsection
