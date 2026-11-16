@extends('layouts.admin')

@section('title', 'Comments & Moderation | CMS Admin')
@section('header_title', 'Comments & Moderation')
@section('header_subtitle', 'Review, Approve, Moderate and Manage Reader Comments')

@section('content')
<div>
  <!-- Header / Title -->
  <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 28px; padding-bottom: 20px; border-bottom: 1px solid var(--line);">
    <div>
      <span style="font: 700 10px var(--sans); letter-spacing: .2em; text-transform: uppercase; color: var(--sand);">READER ENGAGEMENT</span>
      <h1 style="font: 700 26px var(--dev); color: var(--deep); margin: 4px 0 0;">Comments & Discussion Moderation</h1>
      <p style="font-size: 13px; color: rgba(43,35,29,0.7); margin-top: 4px;">
        Inspect comments submitted on pilgrimage articles, approve dharmik questions and filter spam.
      </p>
    </div>
  </div>

  @if(session('success'))
    <div style="background: #e8f5e9; border: 1px solid #a5d6a7; color: #1b5e20; padding: 14px 18px; border-radius: 8px; margin-bottom: 24px; font-size: 14px; display: flex; align-items: center; gap: 10px;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
      {{ session('success') }}
    </div>
  @endif

  <!-- Overview Metrics -->
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 18px; margin-bottom: 28px;">
    <a href="{{ route('admin.comments') }}" style="text-decoration: none; color: inherit;">
      <div style="background: #fff; border: 1px solid {{ $status === 'all' ? 'var(--crimson)' : 'var(--line)' }}; border-radius: 10px; padding: 20px; transition: transform .2s ease;">
        <div style="font-size: 11px; text-transform: uppercase; letter-spacing: .08em; color: #888; font-weight: 700;">Total Comments</div>
        <div style="font-size: 28px; font-weight: 800; color: var(--deep); margin-top: 6px;">{{ $totalComments }}</div>
      </div>
    </a>
    <a href="{{ route('admin.comments', ['status' => 'approved']) }}" style="text-decoration: none; color: inherit;">
      <div style="background: #fff; border: 1px solid {{ $status === 'approved' ? '#2e7d32' : 'var(--line)' }}; border-radius: 10px; padding: 20px; transition: transform .2s ease;">
        <div style="font-size: 11px; text-transform: uppercase; letter-spacing: .08em; color: #2e7d32; font-weight: 700;">Approved & Visible</div>
        <div style="font-size: 28px; font-weight: 800; color: #2e7d32; margin-top: 6px;">{{ $approvedComments }}</div>
      </div>
    </a>
    <a href="{{ route('admin.comments', ['status' => 'pending']) }}" style="text-decoration: none; color: inherit;">
      <div style="background: #fff; border: 1px solid {{ $status === 'pending' ? '#e65100' : 'var(--line)' }}; border-radius: 10px; padding: 20px; transition: transform .2s ease;">
        <div style="font-size: 11px; text-transform: uppercase; letter-spacing: .08em; color: #e65100; font-weight: 700;">Pending Moderation</div>
        <div style="font-size: 28px; font-weight: 800; color: #e65100; margin-top: 6px;">{{ $pendingComments }}</div>
      </div>
    </a>
  </div>

  <!-- Search & Filter Bar -->
  <div style="background: #fff; border: 1px solid var(--line); border-radius: 10px; padding: 18px 24px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
    <form action="{{ route('admin.comments') }}" method="GET" style="display: flex; gap: 10px; flex: 1; max-width: 520px;">
      <input type="hidden" name="status" value="{{ $status }}">
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by name, email or message..." style="width: 100%; padding: 9px 14px; border: 1px solid var(--line); border-radius: 6px; font-size: 13.5px;">
      <button type="submit" class="action-btn primary" style="background: var(--sand); border-color: var(--sand); color: #fff; padding: 0 18px; font-size: 12px;">Search</button>
      @if(request('q'))
        <a href="{{ route('admin.comments', ['status' => $status]) }}" class="action-btn" style="padding: 9px 14px; font-size: 12px; text-decoration: none;">Clear</a>
      @endif
    </form>

    <div style="display: flex; gap: 8px;">
      <a href="{{ route('admin.comments') }}" class="action-btn {{ $status === 'all' ? 'primary' : '' }}" style="font-size: 12px; text-decoration: none;">All ({{ $totalComments }})</a>
      <a href="{{ route('admin.comments', ['status' => 'approved']) }}" class="action-btn {{ $status === 'approved' ? 'teal' : '' }}" style="font-size: 12px; text-decoration: none;">Approved ({{ $approvedComments }})</a>
      <a href="{{ route('admin.comments', ['status' => 'pending']) }}" class="action-btn {{ $status === 'pending' ? 'gold' : '' }}" style="font-size: 12px; text-decoration: none;">Pending ({{ $pendingComments }})</a>
    </div>
  </div>

  <!-- Comments Table -->
  <div style="background: #fff; border: 1px solid var(--line); border-radius: 10px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; font: 400 14px var(--sans);">
        <thead>
          <tr style="background: #faf8f5; border-bottom: 2px solid var(--line); text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: .06em; color: #555;">
            <th style="padding: 14px 16px; width: 220px;">Commenter</th>
            <th style="padding: 14px 16px;">Comment & Message</th>
            <th style="padding: 14px 16px; width: 200px;">Article</th>
            <th style="padding: 14px 16px; width: 110px;">Status</th>
            <th style="padding: 14px 16px; width: 110px;">Date</th>
            <th style="padding: 14px 16px; width: 180px; text-align: right;">Moderation</th>
          </tr>
        </thead>
        <tbody>
          @forelse($comments as $item)
            <tr style="border-bottom: 1px solid rgba(89,58,40,0.12);">
              <!-- Commenter -->
              <td style="padding: 16px; vertical-align: top;">
                <div style="display: flex; align-items: center; gap: 10px;">
                  <div style="width: 36px; height: 36px; border-radius: 50%; background: #e0d5c1; color: #593a28; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; flex-shrink: 0;">
                    {{ strtoupper(substr($item->name ?: 'U', 0, 1)) }}
                  </div>
                  <div>
                    <div style="font-weight: 700; color: var(--deep); font-size: 13.5px;">{{ $item->name ?: 'Anonymous' }}</div>
                    @if($item->email)
                      <div style="font-size: 11px; color: #777;">{{ $item->email }}</div>
                    @endif
                  </div>
                </div>
              </td>

              <!-- Comment Message -->
              <td style="padding: 16px; vertical-align: top;">
                <div style="background: #faf9f7; border: 1px solid rgba(89,58,40,0.1); border-radius: 6px; padding: 12px 14px; font-size: 13.5px; line-height: 1.5; color: #222; font-family: var(--dev);">
                  {{ $item->comment }}
                </div>
              </td>

              <!-- Article Reference -->
              <td style="padding: 16px; vertical-align: top; font-size: 12.5px;">
                @if($item->post)
                  <a href="{{ url('/blog/' . $item->post->slug) }}" target="_blank" style="color: var(--crimson); text-decoration: none; font-weight: 600; display: block; line-height: 1.3;" title="View Article on Live Site">
                    {{ Str::limit($item->post->title_hi, 45) }} &nearr;
                  </a>
                @else
                  <span style="color: #999;">General Post / Deleted</span>
                @endif
              </td>

              <!-- Status -->
              <td style="padding: 16px; vertical-align: top;">
                @if($item->is_approved)
                  <span style="background: #e8f5e9; color: #2e7d32; font-size: 10.5px; font-weight: 800; padding: 4px 8px; border-radius: 4px; display: inline-block; text-transform: uppercase;">
                    Approved
                  </span>
                @else
                  <span style="background: #fff3e0; color: #e65100; font-size: 10.5px; font-weight: 800; padding: 4px 8px; border-radius: 4px; display: inline-block; text-transform: uppercase;">
                    Pending
                  </span>
                @endif
              </td>

              <!-- Date -->
              <td style="padding: 16px; vertical-align: top; font-size: 12px; color: #777;">
                {{ $item->created_at ? $item->created_at->format('d M, Y') : '—' }}
                <div style="font-size: 10px; color: #aaa;">{{ $item->created_at ? $item->created_at->format('h:i A') : '' }}</div>
              </td>

              <!-- Actions -->
              <td style="padding: 16px; vertical-align: top; text-align: right;">
                <div style="display: inline-flex; align-items: center; gap: 6px; flex-wrap: wrap; justify-content: flex-end;">
                  @if(! $item->is_approved)
                    <form action="{{ route('admin.approveComment', $item->id) }}" method="POST" style="display: inline;">
                      @csrf
                      <button type="submit" class="action-btn teal" style="padding: 5px 10px; font-size: 11px;" title="Approve & Publish to Public Article">
                        Approve
                      </button>
                    </form>
                  @else
                    <form action="{{ route('admin.rejectComment', $item->id) }}" method="POST" style="display: inline;">
                      @csrf
                      <button type="submit" class="action-btn" style="padding: 5px 10px; font-size: 11px; color: #e65100; border-color: rgba(230,81,0,0.3);" title="Hide from Public View">
                        Unapprove
                      </button>
                    </form>
                  @endif

                  <form action="{{ route('admin.deleteComment', $item->id) }}" method="POST" onsubmit="return confirm('Permanently delete this comment?');" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-btn" style="padding: 5px 10px; font-size: 11px; color: #dc3545; border-color: rgba(220,53,69,0.3);" title="Delete Comment">
                      Delete
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="padding: 48px 20px; text-align: center; color: #888;">
                <div style="font-size: 16px; font-weight: 600; margin-bottom: 6px;">No Comments Found</div>
                <p style="font-size: 13px; color: #aaa; margin: 0;">No comments matching the current filter status.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    @if($comments->hasPages())
      <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--line);">
        {{ $comments->links() }}
      </div>
    @endif
  </div>
</div>
@endsection
