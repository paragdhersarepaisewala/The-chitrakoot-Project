@extends('layouts.admin')

@section('title', 'Articles & News Manager | CMS Admin')
@section('header_title', 'Articles & News Manager')
@section('header_subtitle', 'Curate, Edit and Manage Pilgrimage Articles & News')

@section('content')
<div>
  <!-- Header / Actions Bar -->
  <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 28px; padding-bottom: 20px; border-bottom: 1px solid var(--line);">
    <div>
      <span style="font: 700 10px var(--sans); letter-spacing: .2em; text-transform: uppercase; color: var(--sand);">EDITORIAL & NEWSROOM</span>
      <h1 style="font: 700 26px var(--dev); color: var(--deep); margin: 4px 0 0;">Articles & News Manager</h1>
      <p style="font-size: 13px; color: rgba(43,35,29,0.7); margin-top: 4px;">
        View, edit, and organize all published articles, essays, and news updates.
      </p>
    </div>
    <div>
      <a href="{{ route('admin.blogs.create') }}" class="action-btn primary" style="background: var(--crimson); color: #fff; border-color: var(--crimson); padding: 10px 22px; font-weight: 700; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        Create New Article
      </a>
    </div>
  </div>

  @if(session('success'))
    <div style="background: #e8f5e9; border: 1px solid #a5d6a7; color: #1b5e20; padding: 14px 18px; border-radius: 8px; margin-bottom: 24px; font-size: 14px; display: flex; align-items: center; gap: 10px;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
      {{ session('success') }}
    </div>
  @endif

  <!-- Quick Overview Stats -->
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 18px; margin-bottom: 28px;">
    <div style="background: #fff; border: 1px solid var(--line); border-radius: 10px; padding: 20px;">
      <div style="font-size: 11px; text-transform: uppercase; letter-spacing: .08em; color: #888; font-weight: 700;">Total Articles</div>
      <div style="font-size: 28px; font-weight: 800; color: var(--deep); margin-top: 6px;">{{ $posts->count() }}</div>
    </div>
    <div style="background: #fff; border: 1px solid var(--line); border-radius: 10px; padding: 20px;">
      <div style="font-size: 11px; text-transform: uppercase; letter-spacing: .08em; color: #888; font-weight: 700;">Total Devotee Views</div>
      <div style="font-size: 28px; font-weight: 800; color: var(--crimson); margin-top: 6px;">{{ number_format($posts->sum('views_count')) }}</div>
    </div>
    <div style="background: #fff; border: 1px solid var(--line); border-radius: 10px; padding: 20px;">
      <div style="font-size: 11px; text-transform: uppercase; letter-spacing: .08em; color: #888; font-weight: 700;">Appreciations (Likes)</div>
      <div style="font-size: 28px; font-weight: 800; color: var(--sand); margin-top: 6px;">{{ number_format($posts->sum('likes_count')) }}</div>
    </div>
  </div>

  <!-- Articles Table Container -->
  <div style="background: #fff; border: 1px solid var(--line); border-radius: 10px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
      <h3 style="font: 600 18px var(--sans); color: var(--deep); margin: 0;">
        Existing Articles & News ({{ $posts->count() }})
      </h3>
      <span style="font-size: 12px; color: #777;">
        Showing all registered publications
      </span>
    </div>

    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; font: 400 14px var(--sans);">
        <thead>
          <tr style="background: #faf8f5; border-bottom: 2px solid var(--line); text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: .06em; color: #555;">
            <th style="padding: 14px 16px;">Article & Headline</th>
            <th style="padding: 14px 16px;">Category</th>
            <th style="padding: 14px 16px;">Author</th>
            <th style="padding: 14px 16px;">Performance</th>
            <th style="padding: 14px 16px;">Date</th>
            <th style="padding: 14px 16px; text-align: right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($posts as $post)
            <tr style="border-bottom: 1px solid rgba(89,58,40,0.12); transition: background .15s ease;">
              <td style="padding: 16px;">
                <div style="display: flex; align-items: center; gap: 14px;">
                  @if($post->featured_image)
                    <img src="{{ $post->featured_image }}" alt="{{ $post->title_hi }}" style="width: 54px; height: 54px; object-fit: cover; border-radius: 6px; border: 1px solid rgba(89,58,40,0.15); flex-shrink: 0;">
                  @endif
                  <div>
                    <a href="{{ url('/blog/' . $post->slug) }}" target="_blank" style="font-weight: 700; color: var(--deep); text-decoration: none; font-size: 14.5px; font-family: var(--dev); display: block; line-height: 1.4;">
                      {{ $post->title_hi }}
                    </a>
                    @if($post->excerpt)
                      <span style="font-size: 12px; color: #777; display: block; margin-top: 3px; max-width: 420px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ $post->excerpt }}
                      </span>
                    @endif
                  </div>
                </div>
              </td>
              <td style="padding: 16px;">
                <span style="background: rgba(185,133,80,0.12); color: #8c5d2b; padding: 4px 10px; border-radius: 9999px; font-size: 11px; font-weight: 700; text-transform: uppercase;">
                  {{ $post->category ?? 'General' }}
                </span>
              </td>
              <td style="padding: 16px; font-size: 12.5px; color: #555;">
                {{ $post->author_name ?? 'Shri Kamadgiri' }}
              </td>
              <td style="padding: 16px; font-size: 12px; color: #444;">
                <div style="display: flex; gap: 12px;">
                  <span title="Views" style="display: inline-flex; align-items: center; gap: 4px;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    {{ $post->views_count }}
                  </span>
                  <span title="Likes" style="display: inline-flex; align-items: center; gap: 4px; color: #c62828;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    {{ $post->likes_count }}
                  </span>
                </div>
              </td>
              <td style="padding: 16px; font-size: 12px; color: #777;">
                {{ $post->created_at ? $post->created_at->format('d M, Y') : '—' }}
              </td>
              <td style="padding: 16px; text-align: right;">
                <div style="display: inline-flex; align-items: center; gap: 8px;">
                  <!-- View Live -->
                  <a href="{{ url('/blog/' . $post->slug) }}" target="_blank" class="action-btn" style="padding: 5px 10px; font-size: 11px; text-decoration: none;" title="View Live Article">
                    Live &nearr;
                  </a>

                  <!-- Edit Article -->
                  <a href="{{ route('admin.blogs.edit', $post->id) }}" class="action-btn teal" style="padding: 5px 12px; font-size: 11px; text-decoration: none;" title="Edit Article Content">
                    Edit
                  </a>

                  <!-- Delete Article -->
                  <form action="{{ route('admin.blogs.delete', $post->id) }}" method="POST" onsubmit="return confirm('Permanently delete article: {{ addslashes($post->title_hi) }}?');" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-btn" style="padding: 5px 10px; font-size: 11px; color: #dc3545; border-color: rgba(220,53,69,0.3);" title="Delete Article">
                      Delete
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="padding: 40px 20px; text-align: center; color: #888;">
                <div style="margin-bottom: 12px; font-size: 16px; font-weight: 600;">No articles found</div>
                <p style="font-size: 13px; color: #aaa; margin-bottom: 16px;">Begin writing your first pilgrimage article using our rich editor.</p>
                <a href="{{ route('admin.blogs.create') }}" class="action-btn primary" style="background: var(--crimson); color: #fff;">
                  + Write First Article
                </a>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection