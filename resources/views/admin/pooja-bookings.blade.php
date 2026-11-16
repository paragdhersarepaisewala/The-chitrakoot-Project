@extends('layouts.admin')

@section('title', 'Devotee Pooja Bookings | CMS Admin')
@section('header_title', 'Devotee Pooja Bookings & Sankalps')
@section('header_subtitle', 'Review devotee sankalps, assigned acharyas, ceremony schedules, and payment receipts')

@section('content')
<!-- Stats Row -->
<div class="stats-grid" style="margin-bottom: 24px;">
  <div class="stat-card">
    <div class="stat-label">TOTAL SANKALPS</div>
    <div class="stat-val" style="color: var(--deep);">{{ $totalCount }}</div>
    <div class="stat-sub">Ceremonies placed by devotees</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">PENDING CONFIRMATION</div>
    <div class="stat-val" style="color: #d97706;">{{ $pendingCount }}</div>
    <div class="stat-sub">Awaiting Acharya scheduling</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">QUOTATION SENT</div>
    <div class="stat-val" style="color: #d97706;">{{ $quotedCount ?? 0 }}</div>
    <div class="stat-sub">Awaiting devotee payment</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">CONFIRMED SEVAS</div>
    <div class="stat-val" style="color: #2563eb;">{{ $confirmedCount }}</div>
    <div class="stat-sub">Ready for altar ritual</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">COMPLETED & CONCLUDED</div>
    <div class="stat-val" style="color: #16a34a;">{{ $completedCount }}</div>
    <div class="stat-sub">Ritual concluded with blessings</div>
  </div>
</div>

<!-- Filter Toolbar -->
<div class="cms-table-card" style="margin-bottom: 24px; padding: 16px 20px;">
  <form action="{{ route('admin.poojaBookings') }}" method="GET" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
    <input type="text" 
           name="q" 
           value="{{ request('q') }}" 
           placeholder="Search booking #, devotee name, phone, gotra..." 
           class="form-input" 
           style="flex: 1; min-width: 260px;">
    
    <select name="status" class="form-input" style="min-width: 160px;">
      <option value="">All Statuses</option>
      <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending (Inquiry)</option>
      <option value="quoted" {{ request('status') === 'quoted' ? 'selected' : '' }}>Quoted (Link Sent)</option>
      <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
      <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
      <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
    </select>

    <button type="submit" class="action-btn primary" style="padding: 10px 18px; font-weight: 700;">Filter</button>
    @if(request('q') || request('status'))
      <a href="{{ route('admin.poojaBookings') }}" class="action-btn" style="background: var(--sand); color: var(--deep); padding: 10px 14px;">Reset</a>
    @endif
  </form>
</div>

<!-- Bookings Table -->
<div class="cms-table-card">
  <table class="cms-table" style="width: 100%; border-collapse: collapse;">
    <thead>
      <tr style="background: #faf8f5; border-bottom: 1px solid var(--line); text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--brown);">
        <th style="padding: 14px 18px;">Booking Ref</th>
        <th style="padding: 14px 16px;">Devotee & Sankalp</th>
        <th style="padding: 14px 16px;">Pooja / Seva</th>
        <th style="padding: 14px 16px;">Ritual Date & Slot</th>
        <th style="padding: 14px 16px;">Dakshina</th>
        <th style="padding: 14px 16px;">Status</th>
        <th style="padding: 14px 18px; text-align: right;">Action</th>
      </tr>
    </thead>
    <tbody>
      @forelse($bookings as $booking)
        <tr style="border-bottom: 1px solid var(--line); font-size: 13px;">
          <td style="padding: 14px 18px;">
            <code style="font-weight: 700; color: var(--crimson);">#{{ $booking->booking_number }}</code>
            <div style="font-size: 11px; color: var(--brown);">{{ $booking->created_at->format('M d, Y h:i A') }}</div>
          </td>
          <td style="padding: 14px 16px;">
            <strong style="color: var(--deep); font-size: 14px; display: block;">{{ $booking->devotee_name }}</strong>
            <div style="font-size: 11px; color: var(--brown);">
              Gotra: <strong>{{ $booking->gotra ?: 'Not Specified' }}</strong>
              @if($booking->nakshatra) &bull; Nakshatra: {{ $booking->nakshatra }} @endif
            </div>
            <div style="font-size: 11px; color: var(--deep); display: flex; align-items: center; gap: 6px; margin-top: 2px;">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
              <span>{{ $booking->devotee_phone }}</span>
              <span>&bull;</span>
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
              <span>{{ $booking->devotee_email }}</span>
            </div>
            @if($booking->sankalp_purpose)
              <div style="font-size: 11px; font-style: italic; color: #666; margin-top: 2px;">
                "{{ Str::limit($booking->sankalp_purpose, 60) }}"
              </div>
            @endif
          </td>
          <td style="padding: 14px 16px;">
            <strong style="color: var(--deep);">{{ $booking->pooja?->name ?? 'Sacred Seva' }}</strong>
            <span class="badge gold" style="display: inline-block; font-size: 9px; margin-top: 4px;">SANKALP</span>
          </td>
          <td style="padding: 14px 16px; font-size: 12px; color: var(--deep);">
            <div style="display: flex; align-items: center; gap: 5px;">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
              <strong>{{ \Carbon\Carbon::parse($booking->booking_date ?? $booking->scheduled_date)->format('D, M d, Y') }}</strong>
            </div>
            <div style="font-size: 11px; color: var(--brown); display: flex; align-items: center; gap: 5px; margin-top: 2px;">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              <span>{{ $booking->time_slot ?? ($booking->scheduled_time_slot ?: 'Morning Slot') }}</span>
            </div>
          </td>
          <td style="padding: 14px 16px; font-weight: 700; color: var(--crimson);">
            @if($booking->payment_status === 'paid')
              ₹{{ number_format((float) ($booking->amount ?? $booking->amount_paid), 0) }}
              <div style="font-size: 10px; font-weight: 600; color: #16a34a;">PAID (Verified)</div>
            @elseif($booking->status === 'quoted')
              ₹{{ number_format((float) ($booking->quotation_amount ?: $booking->amount), 0) }}
              <div style="font-size: 10px; font-weight: 600; color: #d97706;">QUOTED (Link Active)</div>
            @else
              <span style="font-size: 12px; color: var(--brown);">To Negotiate</span>
              <div style="font-size: 10px; font-weight: 600; color: #92400e;">PENDING INQUIRY</div>
            @endif
          </td>
          <td style="padding: 14px 16px;">
            @php
              $bStatus = $booking->status ?? $booking->booking_status ?? 'pending';
              $badgeStyles = [
                'pending' => 'background: #fef3c7; color: #92400e; border: 1px solid rgba(146, 64, 14, 0.2);',
                'quoted' => 'background: #fff7ed; color: #c2410c; border: 1px solid rgba(194, 65, 12, 0.3);',
                'confirmed' => 'background: #dbeafe; color: #1e40af; border: 1px solid rgba(30, 64, 175, 0.2);',
                'completed' => 'background: #dcfce7; color: #166534; border: 1px solid rgba(22, 101, 52, 0.2);',
                'cancelled' => 'background: #fee2e2; color: #991b1b; border: 1px solid rgba(153, 27, 27, 0.2);',
              ];
            @endphp
            <span class="badge" style="{{ $badgeStyles[$bStatus] ?? '' }} font-weight: 800; font-size: 10px;">
              {{ strtoupper($bStatus) }}
            </span>
          </td>
          <td style="padding: 14px 18px; text-align: right;">
            <div style="display: inline-flex; align-items: center; gap: 6px; flex-wrap: wrap; justify-content: flex-end;">
              @if(in_array(($booking->status ?? $booking->booking_status), ['pending', 'quoted']))
                <button type="button" 
                        onclick='openQuotationModal(@json($booking))' 
                        class="action-btn" 
                        style="background: #b45309; color: #fff; font-size: 11px; font-weight: 800; padding: 6px 12px; border-radius: 6px;">
                  ⚡ {{ ($booking->status === 'quoted') ? 'Re-Quote' : 'Send Quotation' }}
                </button>
              @endif

              @if(($booking->status ?? $booking->booking_status) === 'pending')
                <form action="{{ route('admin.poojaBookings.confirm', $booking->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Confirm booking #{{ $booking->booking_number }} and send official confirmation email to {{ $booking->email }}?')">
                  @csrf
                  <button type="submit" class="action-btn" style="background: #059669; color: #fff; font-size: 11px; font-weight: 800; padding: 6px 12px; border-radius: 6px;">
                    ✓ Confirm
                  </button>
                </form>
              @endif

              <button type="button" 
                      onclick='openStatusModal(@json($booking))' 
                      class="action-btn" 
                      style="background: var(--sand); color: var(--deep); font-size: 11px; padding: 6px 12px;">
                Status &rarr;
              </button>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="7" style="padding: 36px; text-align: center; color: var(--brown);">
            No bookings found matching criteria.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>

  @if($bookings->hasPages())
    <div style="padding: 18px 24px; border-top: 1px solid var(--line);">
      {{ $bookings->links() }}
    </div>
  @endif
</div>

<!-- Send Quotation Modal -->
<div id="quotationModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
  <div style="background: #ffffff; width: 100%; max-width: 520px; border-radius: 14px; padding: 28px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); border: 1.5px solid rgba(180,83,9,0.3);">
    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--line); padding-bottom: 12px; margin-bottom: 18px;">
      <h3 style="font-size: 16px; font-weight: 800; color: #B45309; margin: 0;">⚡ Send Official Dakshina Quotation</h3>
      <button type="button" onclick="closeQuotationModal()" style="background: none; border: 0; font-size: 24px; color: var(--brown); cursor: pointer;">&times;</button>
    </div>

    <form id="quotationForm" method="POST">
      @csrf
      <div style="margin-bottom: 14px; font-size: 13px; color: var(--deep); background: #FAF7F2; padding: 10px 14px; border-radius: 8px;">
        Devotee: <strong id="quoteDevotee"></strong> &bull; Ref: <code id="quoteRef"></code><br>
        Email: <span id="quoteEmail"></span>
      </div>

      <div class="form-group" style="margin-bottom: 14px;">
        <label class="form-label">Agreed Dakshina Amount (₹) *</label>
        <input type="number" step="1" min="1" max="99999999" name="quotation_amount" id="quoteAmount" required placeholder="e.g. 2100" class="form-input" style="font-size: 16px; font-weight: 800; color: #8F1719;">
      </div>

      <div class="form-group" style="margin-bottom: 14px;">
        <label class="form-label" style="display: flex; justify-content: space-between; align-items: center;">
          <span>Payment Link Validity / Expiration (Max 12 Hours) *</span>
          <span style="font-size: 11px; color: #B45309; font-weight: 700;">Max 12 Hours</span>
        </label>
        <select name="quotation_hours" id="quoteHours" class="form-input" style="font-weight: 600;">
          <option value="6" selected>⚡ 6 Hours (Recommended Standard)</option>
          <option value="12">⏳ 12 Hours (Maximum Window)</option>
          <option value="3">🔥 3 Hours (Urgent Slot)</option>
          <option value="1">🚨 1 Hour (Immediate Reservation)</option>
        </select>
        <p style="font-size: 11px; color: #7A4B29; margin: 4px 0 0;">
          * Under temple scheduling guidelines, quotation payment links remain valid for 6 to a maximum of 12 hours.
        </p>
      </div>

      <div class="form-group" style="margin-bottom: 20px;">
        <label class="form-label">Arrangement & Samagri Notes for Devotee</label>
        <textarea name="quotation_notes" id="quoteNotes" rows="3" placeholder="e.g. Ekadash Rudrabhishek with 108 Belpatra archana, fruit prasad, and sacred altar arrangement." class="form-input" style="font-size: 12px;"></textarea>
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 8px;">
        <button type="button" onclick="closeQuotationModal()" class="action-btn" style="background: var(--sand); color: var(--deep);">Cancel</button>
        <button type="submit" class="action-btn primary" style="background: #B45309; font-weight: 800;">
          ✉️ Send Quotation & Payment Link &rarr;
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Status Update Modal -->
<div id="statusModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
  <div style="background: #ffffff; width: 100%; max-width: 500px; border-radius: var(--radius); padding: 28px; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--line); padding-bottom: 12px; margin-bottom: 18px;">
      <h3 style="font-size: 16px; font-weight: 800; color: var(--deep); margin: 0;">Update Booking Status</h3>
      <button type="button" onclick="closeStatusModal()" style="background: none; border: 0; font-size: 24px; color: var(--brown); cursor: pointer;">&times;</button>
    </div>

    <form id="statusForm" method="POST">
      @csrf
      <div style="margin-bottom: 14px; font-size: 13px; color: var(--deep);">
        Devotee: <strong id="modalDevotee"></strong> &bull; Ref: <code id="modalRef"></code>
      </div>

      <div class="form-group" style="margin-bottom: 16px;">
        <label class="form-label">Booking Status *</label>
        <select name="booking_status" id="modalStatusSelect" class="form-input" style="width: 100%; font-weight: 700;">
          <option value="pending">Pending (Awaiting Contact / Scheduling)</option>
          <option value="contacted">Contacted (Spoken with Devotee)</option>
          <option value="quoted">Quoted (Payment Link Sent)</option>
          <option value="confirmed">Confirmed (Dakshina Received / Altar Scheduled)</option>
          <option value="completed">Completed (Ritual Concluded with Blessings)</option>
          <option value="cancelled">Cancelled (Declined or Devotee Request)</option>
        </select>
      </div>

      <div class="form-group" style="margin-bottom: 20px;">
        <label class="form-label">Internal Priest / Admin Notes</label>
        <textarea name="admin_notes" id="modalNotes" rows="3" placeholder="e.g. Sankalp completed by Acharya Sharma on Ramghat. Blessings communicated to devotee." class="form-input" style="width: 100%; font-size: 12px;"></textarea>
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 8px;">
        <button type="button" onclick="closeStatusModal()" class="action-btn" style="background: var(--sand); color: var(--deep);">Cancel</button>
        <button type="submit" class="action-btn primary" style="font-weight: 800;">SAVE STATUS &rarr;</button>
      </div>
    </form>
  </div>
</div>

<script>
  function openQuotationModal(booking) {
    document.getElementById('quoteDevotee').textContent = booking.devotee_name;
    document.getElementById('quoteRef').textContent = '#' + booking.booking_number;
    document.getElementById('quoteEmail').textContent = booking.email || 'No email provided';
    document.getElementById('quoteAmount').value = booking.quotation_amount || booking.amount || '';
    document.getElementById('quoteNotes').value = booking.quotation_notes || '';
    document.getElementById('quotationForm').action = '/admin/pooja-bookings/' + booking.id + '/quotation';
    document.getElementById('quotationModal').style.display = 'flex';
  }

  function closeQuotationModal() {
    document.getElementById('quotationModal').style.display = 'none';
  }

  function openStatusModal(booking) {
    document.getElementById('modalDevotee').textContent = booking.devotee_name;
    document.getElementById('modalRef').textContent = '#' + booking.booking_number;
    document.getElementById('modalStatusSelect').value = booking.status || booking.booking_status || 'pending';
    document.getElementById('modalNotes').value = booking.admin_notes || '';
    document.getElementById('statusForm').action = '/admin/pooja-bookings/' + booking.id + '/status';
    document.getElementById('statusModal').style.display = 'flex';
  }

  function closeStatusModal() {
    document.getElementById('statusModal').style.display = 'none';
  }
</script>
@endsection
