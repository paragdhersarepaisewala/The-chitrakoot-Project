@extends('layouts.app')

@section('title', $post->title_hi . ' | श्री कामदगिरि पीठम् सेवा ट्रस्ट')

@section('content')

  <article class="section" style="padding-top: 150px; background: var(--ivory);">
    <div class="container" style="max-width: 860px;">

      @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px 20px; border-radius: 6px; margin-bottom: 30px; font: 500 14px var(--dev);">
          {{ session('success') }}
        </div>
      @endif

      <!-- Article Header -->
      <div style="text-align: center; margin-bottom: 40px;">
        <span style="background: var(--sand); color: #fff; padding: 5px 14px; font: 700 11px var(--sans); letter-spacing: .15em; text-transform: uppercase; border-radius: 4px; display: inline-block; margin-bottom: 16px;">
          {{ $post->category }}
        </span>
        <h1 style="font: 600 clamp(32px, 4.5vw, 54px)/1.2 var(--dev); color: var(--deep); margin: 0 0 20px;">
          {{ $post->title_hi }}
        </h1>
        <div style="font: 600 12px var(--sans); color: rgba(43,35,29,.6); display: flex; align-items: center; justify-content: center; gap: 20px;">
          <span>लेखक: {{ $post->author_name }}</span>
          <span>·</span>
          <span>दिनांक: {{ $post->created_at->format('d F Y') }}</span>
          <span>·</span>
          <span>{{ $post->views_count }} Views</span>
        </div>
      </div>

      <!-- Featured Image -->
      <div style="margin-bottom: 45px; border-radius: 12px; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,0.12);">
        <img src="{{ $post->featured_image ?? 'https://images.unsplash.com/photo-1548013146-72479768bada?auto=format&fit=crop&w=1600&q=90' }}" alt="{{ $post->title_hi }}" style="width: 100%; max-height: 500px; object-fit: cover;">
      </div>

      <!-- Article Content -->
      <div style="font: 400 18px/2 var(--dev); color: var(--ink); background: #fff; border: 1px solid var(--line); padding: 50px; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,.03);">
        <p style="font-size: 20px; font-weight: 500; color: var(--brown); margin-bottom: 30px; border-left: 4px solid var(--sand); padding-left: 20px;">
          {{ $post->excerpt }}
        </p>

        {!! nl2br(e($post->content_hi)) !!}

        <!-- Interactive Like & Share Bar -->
        <div style="margin-top: 50px; padding-top: 30px; border-top: 1px solid var(--line); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
          <button id="likeBtn" onclick="likePost({{ $post->id }})" class="btn primary" style="gap: 10px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
            <span id="likesCounter">{{ $post->likes_count }}</span> Likes
          </button>

          <a href="{{ url('/blog') }}" class="btn dark">
            Back to All Articles
          </a>
        </div>
      </div>

      <!-- Comments Section -->
      <section style="margin-top: 60px;">
        <h3 style="font: 600 28px var(--dev); color: var(--deep); margin-bottom: 30px;">
          विचार एवं प्रतिक्रियाएं (Comments & Thoughts - {{ $comments->count() }})
        </h3>

        <!-- Comment Form -->
        <div style="background: #fff; border: 1px solid var(--line); padding: 35px; border-radius: 8px; margin-bottom: 40px;">
          <h4 style="font: 600 18px var(--dev); margin: 0 0 20px; color: var(--brown);">अपना विचार व्यक्त करें (Leave a Comment)</h4>
          <form action="{{ url('/blog/' . $post->id . '/comment') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
              <div>
                <label style="display: block; font: 600 11px var(--sans); letter-spacing: .15em; text-transform: uppercase; margin-bottom: 8px;">नाम (Name)</label>
                <input type="text" name="name" value="{{ auth()->user()?->name }}" required style="width: 100%; padding: 12px; border: 1px solid var(--line); font: 500 14px var(--dev);" placeholder="आपका नाम">
              </div>
              <div>
                <label style="display: block; font: 600 11px var(--sans); letter-spacing: .15em; text-transform: uppercase; margin-bottom: 8px;">ईमेल (Email - ऐच्छिक)</label>
                <input type="email" name="email" value="{{ auth()->user()?->email }}" style="width: 100%; padding: 12px; border: 1px solid var(--line); font: 500 14px var(--sans);" placeholder="your@email.com">
              </div>
            </div>
            <div style="margin-bottom: 20px;">
              <label style="display: block; font: 600 11px var(--sans); letter-spacing: .15em; text-transform: uppercase; margin-bottom: 8px;">टिप्पणी / विचार (Your Comment)</label>
              <textarea name="comment" rows="4" required style="width: 100%; padding: 12px; border: 1px solid var(--line); font: 400 15px/1.6 var(--dev);" placeholder="लेख पर अपनी प्रतिक्रिया यहाँ लिखें..."></textarea>
            </div>
            <button type="submit" class="btn primary" style="padding: 14px 28px; font-size: 11px;">
              टिप्पणी भेजें (Post Comment) <svg viewBox="0 0 24 24"><path d="M5 12h13M13 6l6 6-6 6"/></svg>
            </button>
          </form>
        </div>

        <!-- Approved Comments List -->
        <div style="display: flex; flex-direction: column; gap: 20px;">
          @forelse($comments as $comm)
            <div style="background: #fff; border: 1px solid var(--line); padding: 24px; border-radius: 8px;">
              <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <b style="font: 600 16px var(--dev); color: var(--deep);">{{ $comm->name }}</b>
                <span style="font: 500 11px var(--sans); color: rgba(43,35,29,.5);">{{ $comm->created_at->diffForHumans() }}</span>
              </div>
              <p style="font: 400 15px/1.7 var(--dev); color: rgba(43,35,29,.85); margin: 0;">
                {{ $comm->comment }}
              </p>
            </div>
          @empty
            <p style="color: rgba(43,35,29,.6); font: 400 15px var(--dev);">अभी तक कोई टिप्पणी नहीं है। पहली टिप्पणी करें!</p>
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
