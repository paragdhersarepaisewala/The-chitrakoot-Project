@extends('layouts.app')

@section('title', 'लेख एवं विचार (Blog) | श्री कामदगिरि पीठम् सेवा ट्रस्ट')

@section('content')

  <section class="section" style="padding-top: 150px; background: var(--ivory);">
    <div class="container">

      <div class="section-head text-center" style="text-align: center; margin-bottom: 50px;">
        <div class="eyebrow" style="justify-content: center;">Articles & Reflections</div>
        <h1 style="font: 600 clamp(36px, 5vw, 62px)/1.15 var(--dev); color: var(--deep); margin-top: 10px;">
          सनातन संस्कृति, सेवा एवं आध्यात्मिक विचार
        </h1>
        <p style="margin: 15px auto 0; font: 400 17px/1.8 var(--dev); max-width: 650px; color: rgba(43,35,29,.8);">
          चित्रकूट की पावन परंपरा, गौ संरक्षण, गुरुकुल शिक्षा और परमार्थिक जीवन शैली पर आधारित प्रेरणादायी लेख।
        </p>
      </div>

      <!-- Categories Filter Tabs -->
      <div style="display: flex; justify-content: center; gap: 12px; margin-bottom: 45px; flex-wrap: wrap;">
        <a href="{{ url('/blog') }}" class="btn {{ !request('category') || request('category') == 'all' ? 'primary' : 'dark' }}" style="padding: 10px 20px; font-size: 11px;">
          All Articles
        </a>
        @foreach($categories as $cat)
          <a href="{{ url('/blog?category=' . $cat) }}" class="btn {{ request('category') == $cat ? 'primary' : 'dark' }}" style="padding: 10px 20px; font-size: 11px;">
            {{ $cat }}
          </a>
        @endforeach
      </div>

      <!-- Magazine Grid Layout -->
      <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 30px;">
        @forelse($posts as $post)
          <article style="background: #fff; border: 1px solid var(--line); border-radius: 8px; overflow: hidden; display: flex; flex-direction: column; transition: transform .3s, box-shadow .3s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 15px 35px rgba(0,0,0,0.08)'" onmouseout="this.style.transform='none'; this.style.boxShadow='none'">
            <div style="height: 220px; position: relative; overflow: hidden; background: #281e17;">
              <img src="{{ $post->featured_image ?? 'https://images.unsplash.com/photo-1548013146-72479768bada?auto=format&fit=crop&w=800&q=80' }}" alt="{{ $post->title_hi }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform .5s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
              <span style="position: absolute; top: 15px; left: 15px; background: var(--sand); color: #fff; padding: 4px 12px; font: 700 10px var(--sans); letter-spacing: .1em; text-transform: uppercase; border-radius: 4px;">
                {{ $post->category }}
              </span>
            </div>

            <div style="padding: 24px; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
              <div>
                <div style="font: 600 11px var(--sans); color: var(--sand); letter-spacing: .12em; margin-bottom: 8px;">
                  {{ $post->author_name }} · {{ $post->created_at->format('M d, Y') }}
                </div>
                <h3 style="font: 600 22px/1.3 var(--dev); color: var(--deep); margin: 0 0 12px;">
                  <a href="{{ url('/blog/' . $post->slug) }}" style="color: inherit; text-decoration: none;">
                    {{ $post->title_hi }}
                  </a>
                </h3>
                <p style="font: 400 14px/1.7 var(--dev); color: rgba(43,35,29,.78); margin-bottom: 20px;">
                  {{ Str::limit($post->excerpt, 120) }}
                </p>
              </div>

              <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px solid var(--line); padding-top: 15px; margin-top: 15px;">
                <span style="font: 600 12px var(--sans); color: rgba(43,35,29,.6); display: flex; align-items: center; gap: 6px;">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                  {{ $post->likes_count }} Likes
                </span>
                <a href="{{ url('/blog/' . $post->slug) }}" class="underlink" style="margin: 0; font-size: 11px;">
                  Read Article <svg viewBox="0 0 24 24"><path d="M5 12h13M13 6l6 6-6 6"/></svg>
                </a>
              </div>
            </div>
          </article>
        @empty
          <div style="grid-column: 1/-1; text-align: center; padding: 60px 0; color: rgba(43,35,29,.6); font: 500 16px var(--dev);">
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
