@extends('layouts.admin')

@section('title', 'Atithi Vishram (Guest Stay) Enquiries | CMS Admin')
@section('header_title', 'Atithi Vishram (Guest Stay) Enquiries')
@section('header_subtitle', 'Review, approve, and manage pilgrim accommodation and room requests in the temple premises')

@section('header_actions')
  <a href="{{ route('services.atithiVishram') }}" target="_blank" class="action-btn" style="background: #ffffff; color: var(--deep); font-size: 11px;">
    Open Public Stay Form &nearr;
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
    <div class="stat-label">TOTAL ENQUIRIES</div>
    <div class="stat-val" style="color: var(--deep);">{{ $totalCount }}</div>
    <div class="stat-sub">Pilgrim stay requests</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">NEW / PENDING</div>
    <div class="stat-val" style="color: #d97706;">{{ $newCount }}</div>
    <div class="stat-sub">Awaiting ashram review</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">CONFIRMED STAYS</div>
    <div class="stat-val" style="color: #16a34a;">{{ $confirmedCount }}</div>
    <div class="stat-sub">Stays approved & scheduled</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">CANCELLED / DECLINED</div>
    <div class="stat-val" style="color: #dc2626;">{{ $cancelledCount }}</div>
    <div class="stat-sub">Full capacity / unavailable</div>
  </div>
</div>

<!-- Search & Filter Controls -->
<div class="cms-table-card" style="margin-bottom: 24px; padding: 18px 24px;">
  <form action="{{ route('admin.atithiVishram') }}" method="GET" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
    <div style="flex: 1; min-width: 260px; position: relative;">
      <input type="text" name="q" value="{{ request('q') }}" class="form-input" placeholder="Search by name, phone, email, reference #, or city..." style="padding-left: 36px;">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#888" stroke-width="2" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%);"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    </div>

    <select name="status" class="form-input" style="width: 170px;" onchange="this.form.submit()">
      <option value="">All Statuses</option>
      <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New / Unread</option>
      <option value="in_review" {{ request('status') === 'in_review' ? 'selected' : '' }}>In Review</option>
      <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed / Approved</option>
      <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled / Declined</option>
      <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed Stay</option>
    </select>

    <button type="submit" class="action-btn primary" style="padding: 10px 18px;">Filter &rarr;</button>
    @if(request()->hasAny(['q', 'status']))
      <a href="{{ route('admin.atithiVishram') }}" class="action-btn" style="color: #888;">Clear</a>
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
          <th>Devotee Details</th>
          <th>Check-in & Out</th>
          <th>Devotees & Room</th>
          <th>Purpose & Details</th>
          <th style="width: 120px;">Status</th>
          <th style="text-align: right; width: 180px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($enquiries as $enq)
          <tr style="{{ !$enq->is_read ? 'background: rgba(217,93,15,0.03); font-weight: 600;' : '' }}">
            <td>
              <strong style="color: #B45309; font-size: 13px; font-family: monospace;">#{{ $enq->enquiry_number }}</strong>
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
              <div style="font-size: 12px; color: #16a34a; font-weight: 700;">In: {{ optional($enq->check_in_date)->format('d M Y') }}</div>
              <div style="font-size: 12px; color: #dc2626; font-weight: 700;">Out: {{ optional($enq->check_out_date)->format('d M Y') }}</div>
              @php
                $nights = $enq->check_in_date && $enq->check_out_date ? $enq->check_in_date->diffInDays($enq->check_out_date) : 1;
              @endphp
              <div style="font-size: 11px; color: #888; margin-top: 2px;">{{ max(1, $nights) }} Day(s) Stay</div>
            </td>
            <td>
              <div style="font-size: 12.5px; font-weight: 700; color: var(--deep);">
                {{ $enq->number_of_devotees }} Devotee(s) &bull; {{ $enq->number_of_rooms }} Room(s)
              </div>
              <div style="font-size: 11px; color: #B45309; margin-top: 2px;">
                {{ $enq->getRoomPreferenceLabel() }}
              </div>
            </td>
            <td style="max-width: 260px;">
              <div style="font-size: 12px; font-weight: 600; color: var(--deep);">{{ $enq->getPurposeLabel() }}</div>
              @if($enq->special_requests)
                <div style="font-size: 11px; color: #666; margin-top: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $enq->special_requests }}">
                  Req: {{ $enq->special_requests }}
                </div>
              @endif
              @if($enq->admin_notes)
                <div style="font-size: 11px; color: #2D6A4F; margin-top: 4px; font-style: italic;">
                  Admin: {{ $enq->admin_notes }}
                </div>
              @endif
            </td>
            <td>
              @php
                $badge = $enq->getStatusBadge();
              @endphp
              <span class="badge" style="display: inline-block; padding: 4px 10px; border-radius: 9999px; font-size: 10px; font-weight: 700; text-transform: uppercase; border: 1px solid currentColor;">
                {{ $enq->status }}
              </span>
            </td>
            <td style="text-align: right; white-space: nowrap;">
              <div style="display: inline-flex; gap: 6px; align-items: center;">
                
                <!-- Quick Status Update Form -->
                <form action="{{ route('admin.atithiVishram.status', $enq->id) }}" method="POST" style="margin: 0; display: inline-flex; gap: 4px;">
                  @csrf
                  <select name="status" class="form-input" style="padding: 4px 8px; font-size: 11px; height: auto;" onchange="this.form.submit()">
                    <option value="new" {{ $enq->status === 'new' ? 'selected' : '' }}>New</option>
                    <option value="in_review" {{ $enq->status === 'in_review' ? 'selected' : '' }}>In Review</option>
                    <option value="confirmed" {{ $enq->status === 'confirmed' ? 'selected' : '' }}>Confirm & Email</option>
                    <option value="cancelled" {{ $enq->status === 'cancelled' ? 'selected' : '' }}>Decline</option>
                    <option value="completed" {{ $enq->status === 'completed' ? 'selected' : '' }}>Completed</option>
                  </select>
                </form>

                <!-- Mailto Button -->
                <a href="mailto:{{ $enq->email }}?subject=Shri Kamadgiri Peetham Atithi Vishram Enquiry #{{ $enq->enquiry_number }}" class="action-btn" style="padding: 5px 8px; font-size: 11px;" title="Send Direct Email">
                  ✉
                </a>

                <!-- Delete Button -->
                <form action="{{ route('admin.atithiVishram.delete', $enq->id) }}" method="POST" style="margin: 0; display: inline;" onsubmit="return confirm('Delete enquiry #{{ $enq->enquiry_number }} permanently?');">
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
            <td colspan="7" style="text-align: center; padding: 40px; color: #888;">
              No Atithi Vishram accommodation enquiries found.
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
