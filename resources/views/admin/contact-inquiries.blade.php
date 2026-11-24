@extends('layouts.admin')

@section('title', 'Contact Inquiries & Devotee Messages | CMS Admin')
@section('header_title', 'Contact Inquiries & Devotee Messages')
@section('header_subtitle', 'Review and respond to messages, inquiries, and blessing requests submitted via the public contact portal')

@section('header_actions')
  <a href="{{ route('contact') }}" target="_blank" class="action-btn"
    style="background: #ffffff; color: var(--deep); font-size: 11px;">
    Open Public Contact Page &nearr;
  </a>
@endsection

@section('content')

  @if(session('success'))
    <div class="toast success" style="margin-bottom: 20px; position: static; animation: none;">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <polyline points="20 6 9 17 4 12" />
      </svg>
      <span>{{ session('success') }}</span>
    </div>
  @endif

  <!-- Stats Row -->
  <div class="stats-grid" style="margin-bottom: 24px;">
    <div class="stat-card">
      <div class="stat-label">TOTAL INQUIRIES</div>
      <div class="stat-val" style="color: var(--deep);">{{ $totalCount }}</div>
      <div class="stat-sub">Devotee messages received</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">NEW / UNREAD</div>
      <div class="stat-val" style="color: #dc2626;">{{ $newCount }}</div>
      <div class="stat-sub">Pending office response</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">REVIEWED & READ</div>
      <div class="stat-val" style="color: #16a34a;">{{ $readCount }}</div>
      <div class="stat-sub">Handled by trust staff</div>
    </div>
  </div>

  <!-- Search & Filter Controls -->
  <div class="cms-table-card" style="margin-bottom: 24px; padding: 18px 24px;">
    <form action="{{ route('admin.contactInquiries') }}" method="GET"
      style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
      <div style="flex: 1; min-width: 260px; position: relative;">
        <input type="text" name="q" value="{{ request('q') }}" class="form-input"
          placeholder="Search by name, email, phone, or message text..." style="padding-left: 36px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#888" stroke-width="2"
          style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%);">
          <circle cx="11" cy="11" r="8" />
          <line x1="21" y1="21" x2="16.65" y2="16.65" />
        </svg>
      </div>

      <select name="status" class="form-input" style="width: 170px;" onchange="this.form.submit()">
        <option value="">All Statuses</option>
        <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New / Unread</option>
        <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
        <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>Replied</option>
      </select>

      <button type="submit" class="action-btn primary" style="padding: 10px 18px;">Filter &rarr;</button>
      @if(request()->hasAny(['q', 'status']))
        <a href="{{ route('admin.contactInquiries') }}" class="action-btn" style="color: #888;">Clear</a>
      @endif
    </form>
  </div>

  <!-- Table Card -->
  <div class="cms-table-card">
    <table class="cms-data-table">
      <thead>
        <tr>
          <th style="width: 140px;">Date & Time</th>
          <th>Devotee Details</th>
          <th>Phone & Email</th>
          <th>Message Content</th>
          <th style="width: 100px;">Status</th>
          <th style="text-align: right; width: 150px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($inquiries as $inq)
          <tr style="{{ !$inq->is_read ? 'background: rgba(217,93,15,0.03); font-weight: 600;' : '' }}">
            <td style="font-size: 12px; color: var(--brown);">
              {{ $inq->created_at->format('d M Y, h:i A') }}
              <br>
              <span style="font-size: 11px; opacity: 0.75;">{{ $inq->created_at->diffForHumans() }}</span>
            </td>
            <td>
              <strong style="color: var(--deep); font-size: 14px;">{{ $inq->name }}</strong>
              @if($inq->subject)
                <div style="font-size: 11px; color: #B45309;">Sub: {{ $inq->subject }}</div>
              @endif
            </td>
            <td style="font-size: 12px;">
              @if($inq->phone)
                <div>
                  <a href="tel:{{ $inq->phone }}" style="color: var(--deep); text-decoration: none;">
                    <strong>{{ $inq->phone }}</strong>
                  </a>
                </div>
              @endif
              <div>
                <a href="mailto:{{ $inq->email }}" style="color: #B45309; text-decoration: none;">
                  {{ $inq->email }}
                </a>
              </div>
            </td>
            <td style="max-width: 380px;">
              <p style="margin: 0; font-size: 13px; line-height: 1.6; color: var(--deep); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"
                title="{{ $inq->message }}">
                {{ $inq->message }}
              </p>
            </td>
            <td>
              @if($inq->status === 'new')
                <span class="badge" style="background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5;">NEW</span>
              @elseif($inq->status === 'replied')
                <span class="badge" style="background: #dcfce7; color: #16a34a; border: 1px solid #86efac;">REPLIED</span>
              @else
                <span class="badge" style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1;">READ</span>
              @endif
            </td>
            <td style="text-align: right; white-space: nowrap;">
              <div style="display: inline-flex; gap: 6px; align-items: center;">
                <!-- Toggle Status Button -->
                <form action="{{ route('admin.contactInquiries.status', $inq->id) }}" method="POST"
                  style="margin: 0; display: inline;">
                  @csrf
                  <button type="submit" class="action-btn" title="Toggle Read / Replied"
                    style="font-size: 10px; padding: 4px 8px;">
                    @if($inq->status === 'new')
                      Mark Read
                    @elseif($inq->status === 'read')
                      Mark Replied
                    @else
                      Mark Read
                    @endif
                  </button>
                </form>

                <!-- Reply via Email Modal Trigger -->
                <button type="button"
                  onclick="openReplyModal({{ $inq->id }}, '{{ addslashes($inq->name) }}', '{{ addslashes($inq->email) }}', '{{ addslashes($inq->subject ?: 'General Inquiry') }}')"
                  class="action-btn primary"
                  style="font-size: 10px; padding: 4px 10px; background: #086c60; border-color: #086c60;"
                  title="Send Official Email Reply">
                  Email Reply
                </button>

                <!-- Delete Button -->
                <form action="{{ route('admin.contactInquiries.delete', $inq->id) }}" method="POST"
                  style="margin: 0; display: inline;"
                  onsubmit="return confirm('Are you sure you want to permanently delete this contact inquiry?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="action-btn"
                    style="color: #dc2626; border-color: #fca5a5; font-size: 10px; padding: 4px 8px;" title="Delete">
                    &times;
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" style="text-align: center; padding: 40px; color: #888;">
              No contact inquiries found matching criteria.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>

    @if($inquiries->hasPages())
      <div style="padding: 16px 20px; border-top: 1px solid var(--line);">
        {{ $inquiries->links() }}
      </div>
    @endif
  </div>

  <!-- Reply to Devotee Modal -->
  <div id="replyInquiryModal"
    style="position: fixed; inset: 0; background: rgba(20,14,11,0.78); backdrop-filter: blur(5px); z-index: 99999; display: none; align-items: center; justify-content: center; padding: 20px;">
    <div
      style="background: #ffffff; border-radius: 12px; width: 100%; max-width: 600px; box-shadow: 0 25px 60px rgba(0,0,0,0.4); border: 1px solid rgba(185,133,80,0.3); overflow: hidden;">
      <div
        style="padding: 16px 24px; border-bottom: 1px solid #eee; display: flex; align-items: center; justify-content: space-between; background: #faf8f5;">
        <div>
          <h3 id="replyModalTitle" style="font-size: 16px; font-weight: 700; margin: 0; color: #16100d;">Send Official
            Email Reply</h3>
          <span id="replyModalSubtitle" style="font-size: 12px; color: #777; display: block; margin-top: 2px;"></span>
        </div>
        <button type="button" onclick="closeReplyModal()"
          style="background: none; border: none; font-size: 24px; color: #888; cursor: pointer;">&times;</button>
      </div>

      <form id="replyInquiryForm" method="POST" style="padding: 24px;">
        @csrf
        <div style="margin-bottom: 16px;">
          <label
            style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #555; margin-bottom: 6px;">OFFICIAL
            RESPONSE MESSAGE (WILL BE SENT TO DEVOTEE'S EMAIL)</label>
          <textarea name="reply_message" rows="6" required placeholder="Type your response to the devotee here..."
            style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 13.5px; font-family: var(--sans); line-height: 1.6;"></textarea>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 10px; padding-top: 10px; border-top: 1px solid #eee;">
          <button type="button" onclick="closeReplyModal()" class="action-btn">Cancel</button>
          <button type="submit" class="action-btn primary"
            style="background: #086c60; border-color: #086c60; font-weight: 700;">SEND EMAIL RESPONSE &rarr;</button>
        </div>
      </form>
    </div>
  </div>

@endsection

@section('scripts')
  <script>
    function openReplyModal(id, name, email, subject) {
      document.getElementById('replyInquiryForm').action = `/admin/contact-inquiries/${id}/reply`;
      document.getElementById('replyModalTitle').textContent = `Reply to: ${name}`;
      document.getElementById('replyModalSubtitle').textContent = `To: ${email} | Sub: ${subject}`;
      document.getElementById('replyInquiryModal').style.display = 'flex';
    }
    function closeReplyModal() {
      document.getElementById('replyInquiryModal').style.display = 'none';
    }
  </script>
@endsection