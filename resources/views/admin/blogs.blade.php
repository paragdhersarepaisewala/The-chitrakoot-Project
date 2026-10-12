@extends('layouts.app')

@section('title', 'Blog Article Manager | Admin Portal')

@section('content')

  <section class="section" style="padding-top: 140px; background: var(--paper); min-height: 80vh;">
    <div class="container">

      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
          <span style="font: 700 10px var(--sans); letter-spacing: .2em; text-transform: uppercase; color: var(--sand);">CMS ADMIN</span>
          <h1 style="font: 600 32px var(--dev); color: var(--deep); margin: 4px 0 0;">Blog & Article Manager</h1>
        </div>
        <a href="{{ url('/admin') }}" class="btn dark">← Back to Admin Dashboard</a>
      </div>

      @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 6px; margin-bottom: 25px; font: 500 14px var(--sans);">
          {{ session('success') }}
        </div>
      @endif

      <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">

        <!-- Create Article Form -->
        <div style="background: #fff; border: 1px solid var(--line); border-radius: 8px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.04);">
          <h3 style="font: 600 20px var(--dev); color: var(--brown); margin: 0 0 20px; border-bottom: 1px solid var(--line); padding-bottom: 10px;">
            Publish New Article
          </h3>

          <form action="{{ url('/admin/blogs') }}" method="POST">
            @csrf
            <div style="margin-bottom: 16px;">
              <label style="display: block; font: 600 11px var(--sans); letter-spacing: .12em; text-transform: uppercase; margin-bottom: 6px;">Title (Hindi)</label>
              <input type="text" name="title_hi" required style="width: 100%; padding: 10px; border: 1px solid var(--line); font: 500 14px var(--dev);" placeholder="लेख का शीर्षक">
            </div>

            <div style="margin-bottom: 16px;">
              <label style="display: block; font: 600 11px var(--sans); letter-spacing: .12em; text-transform: uppercase; margin-bottom: 6px;">Category</label>
              <select name="category" required style="width: 100%; padding: 10px; border: 1px solid var(--line); font: 500 14px var(--sans); background: #fff;">
                <option value="Spiritual">Spiritual (आध्यात्मिक)</option>
                <option value="Gaushala">Gaushala (गौ सेवा)</option>
                <option value="Education">Education (शिक्षा)</option>
                <option value="Culture">Culture (संस्कृति)</option>
                <option value="News">News (समाचार)</option>
              </select>
            </div>

            <div style="margin-bottom: 16px;">
              <label style="display: block; font: 600 11px var(--sans); letter-spacing: .12em; text-transform: uppercase; margin-bottom: 6px;">Excerpt (संक्षेप)</label>
              <textarea name="excerpt" rows="2" required style="width: 100%; padding: 10px; border: 1px solid var(--line); font: 400 13px var(--dev);" placeholder="लेख का मुख्य सारांश..."></textarea>
            </div>

            <div style="margin-bottom: 16px;">
              <label style="display: block; font: 600 11px var(--sans); letter-spacing: .12em; text-transform: uppercase; margin-bottom: 6px;">Full Content (Hindi)</label>
              <textarea name="content_hi" rows="6" required style="width: 100%; padding: 10px; border: 1px solid var(--line); font: 400 14px/1.6 var(--dev);" placeholder="लेख का संपूर्ण विवरण..."></textarea>
            </div>

            <div style="margin-bottom: 16px;">
              <label style="display: block; font: 600 11px var(--sans); letter-spacing: .12em; text-transform: uppercase; margin-bottom: 6px;">Featured Image URL</label>
              <input type="url" name="featured_image" required value="https://images.unsplash.com/photo-1548013146-72479768bada?auto=format&fit=crop&w=1200&q=80" style="width: 100%; padding: 10px; border: 1px solid var(--line); font: 500 13px var(--sans);">
            </div>

            <div style="margin-bottom: 20px;">
              <label style="display: block; font: 600 11px var(--sans); letter-spacing: .12em; text-transform: uppercase; margin-bottom: 6px;">Author Name</label>
              <input type="text" name="author_name" value="श्री कामदगिरि पीठम्" style="width: 100%; padding: 10px; border: 1px solid var(--line); font: 500 13px var(--dev);">
            </div>

            <button type="submit" class="btn primary" style="width: 100%; justify-content: center; font-size: 10px;">
              Publish Article
            </button>
          </form>
        </div>

        <!-- Published Articles Table -->
        <div style="background: #fff; border: 1px solid var(--line); border-radius: 8px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.04);">
          <h3 style="font: 600 20px var(--dev); color: var(--brown); margin: 0 0 20px; border-bottom: 1px solid var(--line); padding-bottom: 10px;">
            Published Articles ({{ $posts->count() }})
          </h3>

          <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font: 400 14px var(--sans);">
              <thead>
                <tr style="background: var(--ivory); border-bottom: 2px solid var(--line); text-align: left; font-size: 11px; text-transform: uppercase;">
                  <th style="padding: 12px;">Article Title</th>
                  <th style="padding: 12px;">Category</th>
                  <th style="padding: 12px;">Likes / Views</th>
                  <th style="padding: 12px;">Actions</th>
                </tr>
              </thead>
              <tbody>
                @forelse($posts as $post)
                  <tr style="border-bottom: 1px solid var(--line);">
                    <td style="padding: 14px; font-family: var(--dev); font-weight: 600;">
                      <a href="{{ url('/blog/' . $post->slug) }}" target="_blank" style="color: var(--brown); text-decoration: underline;">
                        {{ $post->title_hi }}
                      </a>
                    </td>
                    <td style="padding: 14px; font-size: 12px;">{{ $post->category }}</td>
                    <td style="padding: 14px; font-size: 12px;">{{ $post->likes_count }} / {{ $post->views_count }}</td>
                    <td style="padding: 14px;">
                      <form action="{{ url('/admin/blogs/' . $post->id) }}" method="POST" onsubmit="return confirm('Delete article?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: #dc3545; color: #fff; border: 0; padding: 6px 12px; border-radius: 4px; font-size: 11px; cursor: pointer;">Delete</button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" style="padding: 20px; text-align: center; color: #666;">No blog posts created yet.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

      </div>

    </div>
  </section>

@endsection
