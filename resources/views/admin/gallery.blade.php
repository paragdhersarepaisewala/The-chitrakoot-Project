@extends('layouts.admin')

@section('title', 'Gallery Photo Manager | CMS Admin')
@section('header_title', 'Magazine Gallery Photos')
@section('header_subtitle', 'Upload and curate photo magazine albums and exhibitions')

@section('content')
  <div>

      @if(session('success'))
        <div
          style="background: #d4edda; color: #155724; padding: 15px; border-radius: 6px; margin-bottom: 25px; font: 500 14px var(--sans);">
          {{ session('success') }}
        </div>
      @endif

      <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">

        <!-- Upload Photo Form -->
        <div
          style="background: #fff; border: 1px solid var(--line); border-radius: 8px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.04);">
          <h3
            style="font: 600 20px var(--dev); color: var(--brown); margin: 0 0 20px; border-bottom: 1px solid var(--line); padding-bottom: 10px;">
            Post Photo to Gallery
          </h3>

          <form action="{{ url('/admin/gallery') }}" method="POST">
            @csrf
            <div style="margin-bottom: 16px;">
              <label
                style="display: block; font: 600 11px var(--sans); letter-spacing: .12em; text-transform: uppercase; margin-bottom: 6px;">Caption
                (Hindi)</label>
              <input type="text" name="caption_hi" required
                style="width: 100%; padding: 10px; border: 1px solid var(--line); font: 500 14px var(--dev);"
                placeholder="Image caption or title in Hindi">
            </div>

            <div style="margin-bottom: 16px;">
              <label
                style="display: block; font: 600 11px var(--sans); letter-spacing: .12em; text-transform: uppercase; margin-bottom: 6px;">Category
                Tag (English)</label>
              <input type="text" name="caption_en" required value="Heritage"
                style="width: 100%; padding: 10px; border: 1px solid var(--line); font: 500 13px var(--sans);"
                placeholder="e.g. Ramghat, Kamadgiri, Gaushala">
            </div>

            <div style="margin-bottom: 20px;">
              <label
                style="display: block; font: 600 11px var(--sans); letter-spacing: .12em; text-transform: uppercase; margin-bottom: 6px;">Image
                URL</label>
              <input type="text" name="image_url" required
                value="/uploads/temple-test-photo-1792032209.webp"
                style="width: 100%; padding: 10px; border: 1px solid var(--line); font: 500 13px var(--sans);">
            </div>

            <button type="submit" class="btn primary" style="width: 100%; justify-content: center; font-size: 10px;">
              Upload Photo
            </button>
          </form>
        </div>

        <!-- Gallery Grid Manager -->
        <div
          style="background: #fff; border: 1px solid var(--line); border-radius: 8px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.04);">
          <h3
            style="font: 600 20px var(--dev); color: var(--brown); margin: 0 0 20px; border-bottom: 1px solid var(--line); padding-bottom: 10px;">
            Current Gallery Photos ({{ $items->count() }})
          </h3>

          <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 15px;">
            @forelse($items as $item)
              <div
                style="border: 1px solid var(--line); border-radius: 6px; overflow: hidden; position: relative; background: #140d0a;">
                <img src="{{ $item->media_path }}" alt="{{ $item->caption_hi }}"
                  style="width: 100%; height: 130px; object-fit: cover;">
                <div style="padding: 10px; font-size: 12px;">
                  <b
                    style="font-family: var(--dev); display: block; color: var(--deep);">{{ Str::limit($item->caption_hi, 30) }}</b>
                  <span style="font-size: 10px; color: #888;">{{ $item->caption_en }}</span>

                  <form action="{{ url('/admin/gallery/' . $item->id) }}" method="POST" style="margin-top: 8px;"
                    onsubmit="return confirm('Delete photo?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                      style="width: 100%; background: #dc3545; color: #fff; border: 0; padding: 4px; border-radius: 3px; font-size: 10px; cursor: pointer;">Delete</button>
                  </form>
                </div>
              </div>
            @empty
              <p style="color: #666; font-size: 14px;">No gallery items uploaded yet.</p>
            @endforelse
          </div>
        </div>

      </div>

    </div>
  </div>
@endsection