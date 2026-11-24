@extends('layouts.app')

@section('title', 'चित्र दीर्घा (Magazine Photo Gallery) | श्री कामदगिरि पीठम् सेवा ट्रस्ट')

@section('content')

  <section class="section" style="padding-top: 150px; background: var(--paper, #FAF4ED); min-height: 85vh;">
    <div class="container">

      <div class="section-head text-center" style="text-align: center; margin-bottom: 50px;">
        <h1 style="font: 700 clamp(36px, 4.5vw, 56px)/1.15 var(--dev); color: #2B1A12; margin-top: 14px;">
          चित्रकूट धाम की पवित्र छटा एवं सेवा दीर्घा
        </h1>
        <p style="margin: 15px auto 0; font: 400 16px/1.8 var(--dev); max-width: 650px; color: #7A4B29;">
          कामदगिरि परिक्रमा, मंदाकिनी तट, गौ संवर्धन एवं आश्रम के दिव्य क्षणों का सचित्र संग्रह।
        </p>
      </div>

      <!-- Category Tabs -->
      <div style="display: flex; justify-content: center; gap: 10px; margin-bottom: 45px; flex-wrap: wrap;">
        <a href="{{ url('/gallery') }}"
          class="btn {{ !request('category') || request('category') == 'all' ? 'primary' : '' }}"
          style="padding: 10px 22px; font-size: 12px; border-radius: 9999px;">
          All Photos
        </a>
        @foreach($categories as $cat)
          <a href="{{ url('/gallery?category=' . $cat) }}" class="btn {{ request('category') == $cat ? 'primary' : '' }}"
            style="padding: 10px 22px; font-size: 12px; border-radius: 9999px;">
            {{ $cat }}
          </a>
        @endforeach
      </div>

      <!-- Magazine Masonry Grid Layout -->
      <div style="columns: 3 320px; column-gap: 24px;">
        @forelse($items as $item)
          <div
            style="break-inside: avoid; margin-bottom: 24px; position: relative; border-radius: 20px; overflow: hidden; background: #FFFDFC; border: 1px solid rgba(180,83,9,0.18); cursor: pointer; box-shadow: 0 10px 30px rgba(130,70,20,0.06); transition: transform .3s ease, box-shadow .3s ease;"
            onclick="openLightbox('{{ $item->media_path }}', '{{ e($item->caption_hi) }}')"
            onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 18px 40px rgba(130,70,20,0.12)';"
            onmouseout="this.style.transform='none'; this.style.boxShadow='0 10px 30px rgba(130,70,20,0.06)';">
            <img src="{{ $item->media_path }}" alt="{{ $item->caption_hi }}"
              style="width: 100%; display: block; object-fit: cover;">
            <div
              style="position: absolute; inset: 0; background: linear-gradient(0deg, rgba(43,26,18,0.92) 0%, rgba(43,26,18,0) 65%); opacity: 0; transition: opacity .3s; display: flex; align-items: flex-end; padding: 22px;"
              onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'">
              <div style="color: #fff;">
                <span style="font: 700 10px var(--sans); letter-spacing: .15em; color: #FAF4ED; text-transform: uppercase;">
                  {{ $item->caption_en }}
                </span>
                <h4
                  style="font: 700 17px/1.3 var(--dev); margin: 6px 0 0; text-shadow: 0 2px 10px rgba(0,0,0,0.5); color: #ffffff;">
                  {{ $item->caption_hi }}
                </h4>
              </div>
            </div>
          </div>
        @empty
          <div style="text-align: center; padding: 60px 0; color: #7A4B29; font: 500 16px var(--dev);">
            कोई चित्र उपलब्ध नहीं है। (No gallery items found.)
          </div>
        @endforelse
      </div>

      <div style="margin-top: 50px; display: flex; justify-content: center;">
        {{ $items->links() }}
      </div>

    </div>
  </section>

  <!-- Lightbox Modal -->
  <div id="lightboxModal"
    style="display: none; position: fixed; inset: 0; z-index: 99999; background: rgba(0,0,0,0.92); backdrop-filter: blur(10px); align-items: center; justify-content: center; padding: 30px;"
    onclick="closeLightbox()">
    <div style="position: relative; max-width: 1000px; width: 100%; text-align: center;"
      onclick="event.stopPropagation()">
      <button onclick="closeLightbox()"
        style="position: absolute; top: -50px; right: 0; background: none; border: 0; color: #fff; font-size: 36px; cursor: pointer;">&times;</button>
      <img id="lightboxImg" src=""
        style="max-width: 100%; max-height: 80vh; border-radius: 8px; box-shadow: 0 25px 60px rgba(0,0,0,0.5);">
      <h3 id="lightboxCaption" style="color: #fff; font: 600 20px var(--dev); margin-top: 20px;"></h3>
    </div>
  </div>

@endsection

@push('scripts')
  <script>
    function openLightbox(src, caption) {
      document.getElementById('lightboxImg').src = src;
      document.getElementById('lightboxCaption').innerText = caption;
      document.getElementById('lightboxModal').style.display = 'flex';
    }

    function closeLightbox() {
      document.getElementById('lightboxModal').style.display = 'none';
    }
  </script>
@endpush