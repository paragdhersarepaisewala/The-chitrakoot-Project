@extends('layouts.app')

@section('title', $post->title_hi . ' | श्री कामदगिरि पीठम् सेवा ट्रस्ट')

@section('content')

  <article class="section" style="padding-top: 150px; background: var(--paper, #FAF4ED); min-height: 85vh;">
    <div class="container" style="max-width: 860px;">

      @if(session('success'))
        <div style="background: #e8f5e9; color: #2e7d32; padding: 16px 20px; border-radius: 12px; margin-bottom: 30px; font: 600 14px var(--dev); border-left: 4px solid #2e7d32;">
          {{ session('success') }}
        </div>
      @endif

      <!-- Article Header -->
      <div style="text-align: center; margin-bottom: 40px;">
        <span style="background: #FFFDFC; border: 1px solid rgba(180,83,9,0.22); color: #B45309; padding: 5px 16px; font: 700 11px var(--sans); letter-spacing: .12em; text-transform: uppercase; border-radius: 9999px; display: inline-block; margin-bottom: 16px; box-shadow: 0 2px 6px rgba(0,0,0,0.04);">
          {{ $post->category }}
        </span>
        <h1 style="font: 700 clamp(32px, 4.5vw, 52px)/1.2 var(--dev); color: #2B1A12; margin: 0 0 18px;">
          {{ $post->title_hi }}
        </h1>
        <div style="font: 600 12.5px var(--sans); color: #7A4B29; display: flex; align-items: center; justify-content: center; gap: 16px; flex-wrap: wrap;">
          <span>लेखक: <strong>{{ $post->author_name }}</strong></span>
          <span>·</span>
          <span>दिनांक: {{ $post->created_at->format('d F Y') }}</span>
          <span>·</span>
          <span>{{ $post->views_count }} Views</span>
        </div>
      </div>

      <!-- Featured Image -->
      <div style="margin-bottom: 45px; border-radius: 20px; overflow: hidden; box-shadow: 0 16px 40px rgba(130,70,20,0.08); border: 1px solid rgba(180,83,9,0.18);">
        <img src="{{ $post->featured_image ?: asset('uploads/kamadgiri-temple-crest-emblem-1792267739.webp') }}" alt="{{ $post->title_hi }}" style="width: 100%; max-height: 500px; object-fit: cover;">
      </div>

      <!-- Article Content -->
      <div style="font: 400 17.5px/2 var(--dev); color: #2B1A12; background: #FFFDFC; border: 1px solid rgba(180,83,9,0.18); padding: clamp(28px, 4vw, 50px); border-radius: 20px; box-shadow: 0 10px 30px rgba(130,70,20,0.05);">
        <p style="font-size: 19px; font-weight: 600; color: #2B1A12; margin-bottom: 30px; border-left: 4px solid #D95D0F; padding-left: 20px; line-height: 1.8;">
          {{ $post->excerpt }}
        </p>

        {!! $post->parsed_content !!}

        <!-- Interactive Like & Share Bar -->
        <div style="margin-top: 50px; padding-top: 26px; border-top: 1px solid rgba(180,83,9,0.15); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
          <button id="likeBtn" onclick="likePost({{ $post->id }})" class="btn primary" style="gap: 10px; border-radius: 9999px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
            </svg>
            <span id="likesCounter">{{ $post->likes_count }}</span> Likes
          </button>

          <a href="{{ url('/blog') }}" class="btn" style="border-radius: 9999px; color: #2B1A12;">
            &larr; Back to All Articles
          </a>
        </div>
      </div>

      <!-- Comments Section -->
      <section style="margin-top: 60px;">
        <h3 style="font: 700 26px var(--dev); color: #2B1A12; margin-bottom: 24px;">
          विचार एवं प्रतिक्रियाएं (Comments & Thoughts - {{ $comments->count() }})
        </h3>

        <!-- Comment Form -->
        <div style="background: #FFFDFC; border: 1px solid rgba(180,83,9,0.18); padding: clamp(24px, 4vw, 36px); border-radius: 20px; margin-bottom: 36px; box-shadow: 0 10px 30px rgba(130,70,20,0.05);">
          <h4 style="font: 700 18px var(--dev); margin: 0 0 18px; color: #2B1A12;">अपना विचार व्यक्त करें (Leave a Comment)</h4>
          <form action="{{ url('/blog/' . $post->id . '/comment') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px; margin-bottom: 16px;">
              <div>
                <label style="display: block; font: 700 11px var(--sans); letter-spacing: .12em; text-transform: uppercase; margin-bottom: 8px; color: #B45309;">नाम (Name) *</label>
                <input type="text" name="name" value="{{ auth()->user()?->name }}" required style="width: 100%; padding: 13px 16px; border: 1.5px solid rgba(180,83,9,0.22); border-radius: 12px; font: 500 14px var(--dev); background: #FFFDFC; box-sizing: border-box; color: #2B1A12;" placeholder="आपका नाम">
              </div>
              <div>
                <label style="display: block; font: 700 11px var(--sans); letter-spacing: .12em; text-transform: uppercase; margin-bottom: 8px; color: #B45309;">ईमेल (Email - ऐच्छिक)</label>
                <input type="email" name="email" value="{{ auth()->user()?->email }}" style="width: 100%; padding: 13px 16px; border: 1.5px solid rgba(180,83,9,0.22); border-radius: 12px; font: 500 14px var(--sans); background: #FFFDFC; box-sizing: border-box; color: #2B1A12;" placeholder="your@email.com">
              </div>
            </div>
            <div style="margin-bottom: 20px;">
              <label style="display: block; font: 700 11px var(--sans); letter-spacing: .12em; text-transform: uppercase; margin-bottom: 8px; color: #B45309;">टिप्पणी / विचार (Your Comment) *</label>
              <textarea name="comment" rows="4" required style="width: 100%; padding: 13px 16px; border: 1.5px solid rgba(180,83,9,0.22); border-radius: 12px; font: 400 15px/1.7 var(--dev); background: #FFFDFC; box-sizing: border-box; color: #2B1A12;" placeholder="लेख पर अपनी प्रतिक्रिया यहाँ लिखें..."></textarea>
            </div>
            <button type="submit" class="btn primary" style="padding: 13px 28px; font-size: 12px; font-weight: 800; border-radius: 9999px;">
              टिप्पणी भेजें (Post Comment) &rarr;
            </button>
          </form>
        </div>

        <!-- Approved Comments List -->
        <div style="display: flex; flex-direction: column; gap: 16px;">
          @forelse($comments as $comm)
            <div style="background: #FFFDFC; border: 1px solid rgba(180,83,9,0.18); padding: 22px 26px; border-radius: 18px; box-shadow: 0 4px 16px rgba(130,70,20,0.04);">
              <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <b style="font: 700 16px var(--dev); color: #2B1A12;">{{ $comm->name }}</b>
                <span style="font: 600 11px var(--sans); color: #7A4B29;">{{ $comm->created_at->diffForHumans() }}</span>
              </div>
              <p style="font: 400 15px/1.7 var(--dev); color: #7A4B29; margin: 0;">
                {{ $comm->comment }}
              </p>
            </div>
          @empty
            <p style="color: #7A4B29; font: 500 15px var(--dev);">अभी तक कोई टिप्पणी नहीं है। पहली टिप्पणी करें!</p>
          @endforelse
        </div>

      </section>

    </div>
  </article>

@endsection

@push('scripts')
  <script>
    async function likePost(postId) {
      try {
        const res = await fetch("{{ url('/blog') }}/" + postId + "/like", {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
          }
        });
        const data = await res.json();
        if (data.success) {
          document.getElementById('likesCounter').innerText = data.likes_count;
          document.getElementById('likeBtn').style.background = 'var(--brown)';
        }
      } catch (err) {
        console.error(err);
      }
    }
  </script>
@endpush