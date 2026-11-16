@extends('layouts.admin')

@section('title', 'Newsletter Subscribers | CMS Admin')
@section('header_title', 'Newsletter Subscribers')
@section('header_subtitle', 'Manage devotees subscribed to spiritual wisdom, temple aarti updates, and monthly publications')

@section('header_actions')
  <div style="font-size: 13px; font-weight: 700; color: var(--deep); background: #ffffff; padding: 8px 16px; border-radius: 6px; border: 1px solid var(--line);">
    Active Subscribers: <span style="color: #16a34a;">{{ $totalSubscribers }}</span>
  </div>
@endsection

@section('content')
<!-- Filter Bar -->
<div class="cms-table-card" style="margin-bottom: 24px; padding: 16px 20px;">
  <form action="{{ route('admin.subscribers') }}" method="GET" style="display: flex; gap: 12px; align-items: center;">
    <input type="text" 
           name="q" 
           value="{{ request('q') }}" 
           placeholder="Search subscriber email address..." 
           class="form-input" 
           style="flex: 1; max-width: 400px;">
    
    <button type="submit" class="action-btn primary" style="padding: 10px 18px; font-weight: 700;">Filter</button>
    @if(request('q'))
      <a href="{{ route('admin.subscribers') }}" class="action-btn" style="background: var(--sand); color: var(--deep); padding: 10px 14px;">Reset</a>
    @endif
  </form>
</div>

<!-- Table Card -->
<div class="cms-table-card">
  <table class="cms-table" style="width: 100%; border-collapse: collapse;">
    <thead>
      <tr style="background: #faf8f5; border-bottom: 1px solid var(--line); text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--brown);">
        <th style="padding: 14px 20px;">#</th>
        <th style="padding: 14px 20px;">Email Address</th>
        <th style="padding: 14px 20px;">Subscribed From</th>
        <th style="padding: 14px 20px;">Date Joined</th>
        <th style="padding: 14px 20px;">Status</th>
      </tr>
    </thead>
    <tbody>
      @forelse($subscribers as $sub)
        <tr style="border-bottom: 1px solid var(--line); font-size: 13px;">
          <td style="padding: 14px 20px; color: var(--brown); font-size: 11px;">
            {{ $loop->iteration }}
          </td>
          <td style="padding: 14px 20px; font-weight: 600; color: var(--deep); display: flex; align-items: center; gap: 8px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
            <span>{{ $sub->email }}</span>
          </td>
          <td style="padding: 14px 20px; font-size: 12px; color: var(--brown);">
            <code style="background: var(--sand); padding: 2px 6px; border-radius: 4px;">{{ $sub->source ?: 'website-footer' }}</code>
          </td>
          <td style="padding: 14px 20px; font-size: 12px; color: var(--deep);">
            {{ $sub->created_at->format('M d, Y h:i A') }}
          </td>
          <td style="padding: 14px 20px;">
            @if($sub->is_active)
              <span class="badge" style="background: #dcfce7; color: #166534; font-weight: 700; font-size: 10px;">ACTIVE</span>
            @else
              <span class="badge" style="background: #fee2e2; color: #991b1b; font-weight: 700; font-size: 10px;">UNSUBSCRIBED</span>
            @endif
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="5" style="padding: 36px; text-align: center; color: var(--brown);">
            No subscribers found.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>

  @if($subscribers->hasPages())
    <div style="padding: 18px 24px; border-top: 1px solid var(--line);">
      {{ $subscribers->links() }}
    </div>
  @endif
</div>
@endsection
