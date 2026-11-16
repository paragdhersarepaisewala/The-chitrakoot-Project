@extends('layouts.app')

@section('title', 'लेख एवं विचार (Blog) | श्री कामदगिरि पीठम् सेवा ट्रस्ट')

@section('content')

  <section class="section" style="padding-top: 150px; background: var(--paper, #FAF4ED); min-height: 85vh;">
    <div class="container">

      <div class="section-head text-center" style="text-align: center; margin-bottom: 50px;">
        <div class="eyebrow" style="justify-content: center; color: #B45309;">Articles & Reflections</div>
        <h1 style="font: 700 clamp(36px, 4.5vw, 56px)/1.15 var(--dev); color: #2B1A12; margin-top: 14px;">
          सनातन संस्कृति, सेवा एवं आध्यात्मिक विचार
        </h1>
        <p style="margin: 15px auto 0; font: 400 16px/1.8 var(--dev); max-width: 650px; color: #7A4B29;">
          चित्रकूट की पावन परंपरा, गौ संरक्षण, गुरुकुल शिक्षा और परमार्थिक जीवन शैली पर आधारित प्रेरणादायी लेख।
        </p>
      </div>

      <!-- Categories Filter Tabs -->
      <div style="display: flex; justify-content: center; gap: 10px; margin-bottom: 45px; flex-wrap: wrap;">
        <a href="{{ url('/blog') }}" class="btn {{ !request('category') || request('category') == 'all' ? 'primary' : '' }}" style="padding: 10px 22px; font-size: 12px; border-radius: 9999px;">
          All Articles
        </a>
        @foreach($categories as $cat)
          <a href="{{ url('/blog?category=' . $cat) }}" class="btn {{ request('category') == $cat ? 'primary' : '' }}" style="padding: 10px 22px; font-size: 12px; border-radius: 9999px;">
            {{ $cat }}
          </a>
        @endforeach
      </div>

      <!-- Magazine Grid Layout -->
      <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 32px;">
        @forelse($posts as $post)
          <article class="reveal" style="background: #FFFDFC; border: 1px solid rgba(180,83,9,0.18); border-radius: 20px; overflow: hidden; display: flex; flex-direction: column; box-shadow: 0 10px 30px rgba(130,70,20,0.05); transition: transform .3s ease, box-shadow .3s ease;" onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 20px 40px rgba(130,70,20,0.12)'" onmouseout="this.style.transform='none'; this.style.boxShadow='0 10px 30px rgba(130,70,20,0.05)'">
            <div style="height: 220px; position: relative; overflow: hidden; background: #FAF4ED;">
              <img src="{{ $post->featured_image ?: asset('uploads/temple-test-photo-1792032209.webp') }}" alt="{{ $post->title_hi }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform .5s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
              <span style="position: absolute; top: 14px; left: 14px; background: rgba(255,253,252,0.95); backdrop-filter: blur(8px); border: 1px solid rgba(180,83,9,0.22); color: #B45309; padding: 4px 12px; font: 700 11px var(--sans); letter-spacing: .08em; text-transform: uppercase; border-radius: 9999px; box-shadow: 0 2px 6px rgba(0,0,0,0.05);">
                {{ $post->category }}
              </span>
            </div>

            <div style="padding: 26px; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
              <div>
                <div style="font: 600 11.5px var(--sans); color: #B45309; letter-spacing: .08em; margin-bottom: 8px;">
                  {{ $post->author_name }} · {{ $post->created_at->format('M d, Y') }}
                </div>
                <h3 style="font: 700 20px/1.3 var(--dev); color: #2B1A12; margin: 0 0 10px;">
                  <a href="{{ url('/blog/' . $post->slug) }}" style="color: inherit; text-decoration: none;">
                    {{ $post->title_hi }}
                  </a>
                </h3>
                <p style="font: 400 14px/1.7 var(--dev); color: #7A4B29; margin-bottom: 20px;">
                  {{ Str::limit($post->excerpt, 120) }}
                </p>
              </div>

              <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px solid rgba(180,83,9,0.15); padding-top: 16px; margin-top: 10px;">
                <span style="font: 600 12px var(--sans); color: #7A4B29; display: flex; align-items: center; gap: 6px;">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="#D95D0F" stroke="#D95D0F" stroke-width="1"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                  {{ $post->likes_count }} Likes
                </span>
                <a href="{{ url('/blog/' . $post->slug) }}" style="font: 700 12.5px var(--sans); color: #D95D0F; text-decoration: none; display: flex; align-items: center; gap: 4px;">
                  Read Article &rarr;
                </a>
              </div>
            </div>
          </article>
        @empty
          <div style="grid-column: 1/-1; text-align: center; padding: 60px 0; color: #7A4B29; font: 500 16px var(--dev);">
            कोई लेख नहीं मिला। (No articles found in this category.)
          </div>
        @endforelse
      </div>

      <div style="margin-top: 50px; display: flex; justify-content: center;">
        {{ $posts->links() }}
      </div>

    </div>
  </section>

@endsection
