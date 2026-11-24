@extends('layouts.admin')

@section('title', 'Temple Hall & Event Booking Enquiries | CMS Admin')
@section('header_title', 'Temple Hall & Premises Booking Enquiries')
@section('header_subtitle', 'Review, approve, and manage premises booking requests for weddings, hawans, kathas, and bhandaras')

@section('header_actions')
  <a href="{{ route('services.templeBooking') }}" target="_blank" class="action-btn" style="background: #ffffff; color: var(--deep); font-size: 11px;">
    Open Public Booking Form &nearr;
  </a>
@endsection

@section('content')

@if(session('success'))
  <div class="toast success" style="margin-bottom: 20px; position: static; animation: none;">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
    <span>{{ session('success') }}</span>
  </div>
@endif

<!-- Stats Row -->
<div class="stats-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;">
  <div class="stat-card">
    <div class="stat-label">TOTAL PROPOSALS</div>
    <div class="stat-val" style="color: var(--deep);">{{ $totalCount }}</div>
    <div class="stat-sub">Event booking enquiries</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">NEW / PENDING</div>
    <div class="stat-val" style="color: #d97706;">{{ $newCount }}</div>
    <div class="stat-sub">Awaiting date check</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">APPROVED DATES</div>
    <div class="stat-val" style="color: #16a34a;">{{ $approvedCount }}</div>
    <div class="stat-sub">Dates sanctioned for event</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">UNAVAILABLE / REJECTED</div>
    <div class="stat-val" style="color: #dc2626;">{{ $rejectedCount }}</div>
    <div class="stat-sub">Clashing muhurat / full</div>
  </div>
</div>

<!-- Search & Filter Controls -->
<div class="cms-table-card" style="margin-bottom: 24px; padding: 18px 24px;">
  <form action="{{ route('admin.templeBookings') }}" method="GET" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
    <div style="flex: 1; min-width: 240px; position: relative;">
      <input type="text" name="q" value="{{ request('q') }}" class="form-input" placeholder="Search by organizer, phone, email, reference #, or city..." style="padding-left: 36px;">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#888" stroke-width="2" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%);"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    </div>

    <select name="event_type" class="form-input" style="width: 170px;" onchange="this.form.submit()">
      <option value="">All Event Types</option>
      <option value="shadi" {{ request('event_type') === 'shadi' ? 'selected' : '' }}>विवाह (Wedding)</option>
      <option value="hawan" {{ request('event_type') === 'hawan' ? 'selected' : '' }}>महायज्ञ / हवन</option>
      <option value="katha" {{ request('event_type') === 'katha' ? 'selected' : '' }}>कथा (Ram/Bhagwat)</option>
      <option value="pooja" {{ request('event_type') === 'pooja' ? 'selected' : '' }}>विशेष अनुष्ठान</option>
      <option value="bhandara" {{ request('event_type') === 'bhandara' ? 'selected' : '' }}>महाप्रसाद भंडारा</option>
      <option value="satsang" {{ request('event_type') === 'satsang' ? 'selected' : '' }}>सत्संग सम्मेलन</option>
    </select>

    <select name="status" class="form-input" style="width: 160px;" onchange="this.form.submit()">
      <option value="">All Statuses</option>
      <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
      <option value="contacted" {{ request('status') === 'contacted' ? 'selected' : '' }}>Contacted</option>
      <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Date Approved</option>
      <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Unavailable</option>
      <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
    </select>

    <button type="submit" class="action-btn primary" style="padding: 10px 18px;">Filter &rarr;</button>
    @if(request()->hasAny(['q', 'status', 'event_type']))
      <a href="{{ route('admin.templeBookings') }}" class="action-btn" style="color: #888;">Clear</a>
    @endif
  </form>
</div>

<!-- Table Card -->
<div class="cms-table-card">
  <div style="overflow-x: auto;">
    <table class="cms-data-table" style="width: 100%; border-collapse: collapse;">
      <thead>
        <tr>
          <th style="width: 130px;">Enquiry Ref</th>
          <th>Organizer Details</th>
          <th>Event Type & Title</th>
          <th>Proposed Dates</th>
          <th>Gathering & Needs</th>
          <th>Special Notes</th>
          <th style="width: 120px;">Status</th>
          <th style="text-align: right; width: 180px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($enquiries as $enq)
          <tr style="{{ !$enq->is_read ? 'background: rgba(143,23,25,0.03); font-weight: 600;' : '' }}">
            <td>
              <strong style="color: #8F1719; font-size: 13px; font-family: monospace;">#{{ $enq->enquiry_number }}</strong>
              <div style="font-size: 11px; color: #888; margin-top: 4px;">{{ $enq->created_at->format('d M Y') }}</div>
            </td>
            <td>
              <strong style="color: var(--deep); font-size: 14px;">{{ $enq->devotee_name }}</strong>
              <div style="font-size: 12px; margin-top: 2px;">
                <a href="tel:{{ $enq->phone }}" style="color: var(--deep); text-decoration: none;">📞 {{ $enq->phone }}</a>
              </div>
              <div style="font-size: 11px; color: #B45309;">
                <a href="mailto:{{ $enq->email }}" style="color: inherit; text-decoration: none;">✉ {{ $enq->email }}</a>
              </div>
              @if($enq->city)
                <div style="font-size: 10.5px; color: #888;">📍 {{ $enq->city }}</div>
              @endif
            </td>
            <td>
              <div style="font-size: 13px; font-weight: 700; color: #8F1719;">
                {{ $enq->getEventTypeLabel() }}
              </div>
              @if($enq->event_title)
                <div style="font-size: 11.5px; color: var(--deep); margin-top: 3px;">
                  "{{ $enq->event_title }}"
                </div>
              @endif
            </td>
            <td>
              <div style="font-size: 12.5px; color: #16a34a; font-weight: 700;">
                {{ optional($enq->proposed_date)->format('d M Y') }}
              </div>
              <div style="font-size: 11px; color: #888; margin-top: 2px;">
                {{ $enq->duration_days }} Day(s) Event
              </div>
              @if($enq->alternate_date)
                <div style="font-size: 10.5px; color: #b45309; margin-top: 2px;">
                  Alt: {{ optional($enq->alternate_date)->format('d M Y') }}
                </div>
              @endif
            </td>
            <td>
              <div style="font-size: 12.5px; font-weight: 700; color: var(--deep);">
                {{ number_format($enq->expected_gathering) }} Guests
              </div>
              <div style="font-size: 10.5px; color: #666; margin-top: 3px; display: flex; flex-direction: column; gap: 2px;">
                @if($enq->requires_priest)
                  <span>✓ पुरोहित व्यवस्था</span>
                @endif
                @if($enq->requires_catering)
                  <span>✓ महाप्रसाद परामर्श</span>
                @endif
                @if($enq->requires_accommodation)
                  <span>✓ आश्रम कक्ष आवश्यकता</span>
                @endif
              </div>
            </td>
            <td style="max-width: 240px;">
              @if($enq->additional_requirements)
                <div style="font-size: 11.5px; color: #555; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" title="{{ $enq->additional_requirements }}">
                  {{ $enq->additional_requirements }}
                </div>
              @endif
              @if($enq->admin_notes)
                <div style="font-size: 11px; color: #8F1719; margin-top: 4px; font-style: italic;">
                  Admin: {{ $enq->admin_notes }}
                </div>
              @endif
            </td>
            <td>
              <span class="badge" style="display: inline-block; padding: 4px 10px; border-radius: 9999px; font-size: 10px; font-weight: 700; text-transform: uppercase; border: 1px solid currentColor;">
                {{ $enq->status }}
              </span>
            </td>
            <td style="text-align: right; white-space: nowrap;">
              <div style="display: inline-flex; gap: 6px; align-items: center;">
                
                <!-- Quick Status Update Form -->
                <form action="{{ route('admin.templeBookings.status', $enq->id) }}" method="POST" style="margin: 0; display: inline-flex; gap: 4px;">
                  @csrf
                  <select name="status" class="form-input" style="padding: 4px 8px; font-size: 11px; height: auto;" onchange="this.form.submit()">
                    <option value="new" {{ $enq->status === 'new' ? 'selected' : '' }}>New</option>
                    <option value="contacted" {{ $enq->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                    <option value="approved" {{ $enq->status === 'approved' ? 'selected' : '' }}>Approve Date</option>
                    <option value="rejected" {{ $enq->status === 'rejected' ? 'selected' : '' }}>Decline</option>
                    <option value="completed" {{ $enq->status === 'completed' ? 'selected' : '' }}>Completed</option>
                  </select>
                </form>

                <!-- Mailto Button -->
                <a href="mailto:{{ $enq->email }}?subject=Shri Kamadgiri Peetham Temple Booking Enquiry #{{ $enq->enquiry_number }}" class="action-btn" style="padding: 5px 8px; font-size: 11px;" title="Send Direct Email">
                  ✉
                </a>

                <!-- Delete Button -->
                <form action="{{ route('admin.templeBookings.delete', $enq->id) }}" method="POST" style="margin: 0; display: inline;" onsubmit="return confirm('Delete booking enquiry #{{ $enq->enquiry_number }} permanently?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="action-btn" style="color: #dc2626; padding: 5px 8px; font-size: 11px;" title="Delete Record">
                    ✕
                  </button>
                </form>

              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" style="text-align: center; padding: 40px; color: #888;">
              No temple hall or event booking enquiries found.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($enquiries->hasPages())
    <div style="padding: 16px 20px; border-top: 1px solid var(--line);">
      {{ $enquiries->links() }}
    </div>
  @endif
</div>

@endsection
