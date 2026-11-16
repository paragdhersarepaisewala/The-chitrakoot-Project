@extends('layouts.admin')

@section('title', 'Server Media Gallery Manager | CMS Admin')

@section('styles')
<style>
  .media-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
    gap: 16px;
    margin-top: 24px;
  }
  .media-card {
    background: #ffffff;
    border: 1px solid var(--line, rgba(89, 58, 40, 0.16));
    border-radius: 8px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
    position: relative;
  }
  .media-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    border-color: var(--sand, #b98550);
  }
  .media-thumb-wrap {
    aspect-ratio: 16 / 10;
    background: #140e0b;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .media-thumb-wrap img, .media-thumb-wrap video {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .media-select-chk {
    position: absolute;
    top: 10px;
    left: 10px;
    width: 18px;
    height: 18px;
    accent-color: var(--crimson, #8f1719);
    cursor: pointer;
    z-index: 10;
  }
  .media-type-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(16, 24, 18, 0.85);
    color: #4ade80;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .08em;
    padding: 3px 7px;
    border-radius: 4px;
    text-transform: uppercase;
    backdrop-filter: blur(4px);
    z-index: 10;
  }
  .media-type-badge.video {
    background: rgba(143, 23, 25, 0.88);
    color: #fff;
  }
  .media-info {
    padding: 12px 14px 8px;
    border-bottom: 1px solid rgba(89, 58, 40, 0.08);
  }
  .media-filename {
    font-size: 11.5px;
    font-weight: 600;
    color: #16100d;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: block;
  }
  .media-filesize {
    font-size: 10.5px;
    color: #777;
    margin-top: 3px;
  }
  .media-card-actions {
    padding: 10px 14px 14px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    background: #faf8f5;
  }
  .btn-copy {
    width: 100%;
    padding: 6px 10px;
    border-radius: 4px;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    border: none;
    background: #1c1511;
    color: #ffffff;
    cursor: pointer;
    transition: all .2s ease;
  }
  .btn-copy:hover {
    background: var(--sand, #b98550);
  }
  .btn-copy-gold {
    background: #8f1719;
    color: #ffffff;
  }
  .btn-copy-gold:hover {
    background: #6e1012;
  }
  .btn-del-link {
    background: none;
    border: none;
    color: var(--crimson, #8f1719);
    font-size: 10px;
    font-weight: 600;
    cursor: pointer;
    text-align: center;
    padding: 2px 0;
  }
  .btn-del-link:hover {
    text-decoration: underline;
  }

  .filter-pill {
    padding: 8px 18px;
    border-radius: 9999px;
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: .05em;
    text-transform: uppercase;
    border: 1px solid var(--line);
    background: #ffffff;
    color: var(--ink);
    cursor: pointer;
    text-decoration: none;
    transition: all .2s ease;
  }
  .filter-pill.active {
    background: var(--crimson, #8f1719);
    border-color: var(--crimson, #8f1719);
    color: #ffffff;
  }
</style>
@endsection

@section('content')

<!-- Masthead Banner Matching Screenshot 4 -->
<div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid var(--line);">
  <div>
    <h1 style="font-size: 26px; font-weight: 700; font-family: var(--dev, serif); color: var(--deep); margin: 0;">
      Server Media Gallery Manager
    </h1>
    <p style="font-size: 12.5px; color: rgba(43,35,29,0.7); margin-top: 4px;">
      Manage photos, WebP images, and HD video reels for articles, web stories, and site backgrounds
    </p>
  </div>
</div>

<!-- Upload New Media Box Matching Screenshot 4 -->
<div style="background: #ffffff; border: 1px solid var(--line); border-radius: 10px; padding: 24px; margin-bottom: 28px; box-shadow: 0 4px 18px rgba(0,0,0,0.02);">
  <strong style="display: block; font-size: 13px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; color: #16100d; margin-bottom: 12px;">
    Upload New Media File (Photos & Video Reels)
  </strong>

  <form id="mediaUploadForm" action="{{ route('admin.uploadMedia') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
      <div style="flex: 1; min-width: 260px;">
        <input type="file" name="file" id="mediaUploadInput" required accept="image/*,video/*"
          style="width: 100%; padding: 10px 14px; border: 1px solid rgba(89,58,40,0.2); border-radius: 6px; font-size: 13px; background: #faf8f5;">
      </div>
      <button type="submit" id="uploadSubmitBtn" class="action-btn primary" style="background: var(--crimson, #8f1719); border-color: var(--crimson, #8f1719); padding: 11px 24px; font-size: 12px; border-radius: 6px;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
        UPLOAD MEDIA FILE
      </button>
    </div>
  </form>

  <div style="margin-top: 14px; font-size: 11px; color: #7a6e65; line-height: 1.6;">
    <div>&bull; <strong>Images (JPG, PNG, WebP)</strong> are automatically converted to high quality WebP format with zero visual loss.</div>
    <div>&bull; <strong>Videos (MP4, WebM, MOV)</strong> are saved for Web Stories, Reels, Backgrounds, and Video Spotlight.</div>
  </div>
</div>

<!-- Controls Strip (Filter Tabs & Batch Actions Matching Screenshot 4) -->
<div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; margin-bottom: 20px;">
  
  <!-- Tabs -->
  <div style="display: flex; gap: 8px;">
    <a href="{{ route('admin.mediaGallery', ['tab' => 'all']) }}" class="filter-pill {{ $tab === 'all' ? 'active' : '' }}">
      ALL FILES ({{ $totalCount }})
    </a>
    <a href="{{ route('admin.mediaGallery', ['tab' => 'photos']) }}" class="filter-pill {{ $tab === 'photos' ? 'active' : '' }}">
      PHOTOS & WEBP ({{ $photosCount }})
    </a>
    <a href="{{ route('admin.mediaGallery', ['tab' => 'videos']) }}" class="filter-pill {{ $tab === 'videos' ? 'active' : '' }}">
      VIDEOS & REELS ({{ $videosCount }})
    </a>
  </div>

  <!-- Batch Controls -->
  <div style="display: flex; align-items: center; gap: 10px;">
    <button type="button" onclick="selectAllCards(true)" class="action-btn" style="padding: 6px 14px; font-size: 11px; background: #16100d; color: #fff;">
      SELECT ALL
    </button>
    <button type="button" onclick="selectAllCards(false)" class="action-btn" style="padding: 6px 14px; font-size: 11px;">
      DESELECT ALL
    </button>
    <span id="selectedCountBadge" style="font-size: 11px; color: #777;">0 selected</span>
    <button type="button" onclick="deleteBatchSelected()" class="action-btn primary" style="background: var(--crimson); border-color: var(--crimson); padding: 6px 14px; font-size: 11px;">
      DELETE SELECTED
    </button>
  </div>

</div>

<!-- Media Cards Grid Matching Screenshot 4 -->
<div class="media-grid">
  @forelse($media as $item)
    <div class="media-card" id="card_{{ $item->id }}">
      <div>
        <div class="media-thumb-wrap">
          <input type="checkbox" class="media-select-chk" value="{{ $item->id }}" onchange="updateSelectedCount()">

          @php
            $ext = strtoupper(pathinfo($item->filename, PATHINFO_EXTENSION));
          @endphp

          @if($item->file_type === 'video')
            <span class="media-type-badge video">VIDEO {{ $ext }}</span>
            <video src="{{ $item->url }}" preload="metadata" style="width: 100%; height: 100%; object-fit: cover;"></video>
            <div style="position: absolute; inset: 0; display: grid; place-items: center; background: rgba(0,0,0,0.3); pointer-events: none;">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="#fff" stroke="none"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
            </div>
          @else
            <span class="media-type-badge">{{ $ext }}</span>
            <img src="{{ $item->url }}" alt="{{ $item->filename }}" loading="lazy">
          @endif
        </div>

        <div class="media-info">
          <span class="media-filename" title="{{ $item->filename }}">{{ $item->filename }}</span>
          <div class="media-filesize">
            Size: {{ $item->formatted_size }} &bull; {{ $item->created_at->format('M d, Y') }}
          </div>
        </div>
      </div>

      <div class="media-card-actions">
        <button type="button" onclick="copyToClipboard('{{ $item->file_path }}', 'Relative path copied!')" class="btn-copy">
          COPY RELATIVE PATH
        </button>
        <button type="button" onclick="copyToClipboard('{{ $item->url }}', 'Full URL copied!')" class="btn-copy">
          COPY FULL URL
        </button>
        @if($item->file_type === 'video')
          <button type="button" onclick="copyToClipboard('<video src=&quot;{{ $item->url }}&quot; autoplay muted loop playsinline></video>', 'Video HTML code copied!')" class="btn-copy btn-copy-gold">
            COPY VIDEO HTML CODE
          </button>
        @endif
        <form action="{{ route('admin.deleteMedia', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this media file permanently?')" style="margin: 0; text-align: center;">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn-del-link">Delete File</button>
        </form>
      </div>
    </div>
  @empty
    <div style="grid-column: 1 / -1; background: #ffffff; padding: 60px 20px; text-align: center; border-radius: 8px; border: 1px dashed var(--line); color: #888;">
      <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="1.5" style="margin-bottom: 12px;"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
      <div style="font-size: 15px; font-weight: 600; color: #444;">No media files found</div>
      <p style="font-size: 12px; color: #888; margin-top: 4px;">Upload images or videos above to populate the server gallery.</p>
    </div>
  @endforelse
</div>

<!-- Pagination Links -->
<div style="margin-top: 32px; display: flex; justify-content: center;">
  {{ $media->appends(['tab' => $tab])->links() }}
</div>

@endsection

@section('scripts')
<script>
  // Copy to clipboard helper with toast
  function copyToClipboard(text, msg) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(text).then(() => {
        showToast(msg, 'success');
      });
    } else {
      const ta = document.createElement('textarea');
      ta.value = text;
      document.body.appendChild(ta);
      ta.select();
      document.execCommand('copy');
      ta.remove();
      showToast(msg, 'success');
    }
  }

  // Batch Select & Delete
  function selectAllCards(select) {
    document.querySelectorAll('.media-select-chk').forEach(chk => {
      chk.checked = select;
    });
    updateSelectedCount();
  }

  function updateSelectedCount() {
    const checked = document.querySelectorAll('.media-select-chk:checked').length;
    document.getElementById('selectedCountBadge').textContent = `${checked} selected`;
  }

  function deleteBatchSelected() {
    const checkedBoxes = Array.from(document.querySelectorAll('.media-select-chk:checked'));
    if (checkedBoxes.length === 0) {
      alert('Please select at least one media item to delete.');
      return;
    }

    if (!confirm(`Are you sure you want to delete ${checkedBoxes.length} media files permanently?`)) {
      return;
    }

    const ids = checkedBoxes.map(cb => cb.value);
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch("{{ route('admin.batchDeleteMedia') }}", {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': token,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({ ids: ids })
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        showToast(`Successfully deleted ${data.deleted_count} media files`, 'success');
        ids.forEach(id => {
          const el = document.getElementById(`card_${id}`);
          if (el) el.remove();
        });
        updateSelectedCount();
      } else {
        alert('Batch delete failed.');
      }
    })
    .catch(err => alert(err.message));
  }

  // LIGHTWEIGHT CLIENT CANVAS WEBP AUTO-CONVERTER
  // Runs ONLY on upload file selection - Zero impact on other pages!
  const uploadInput = document.getElementById('mediaUploadInput');
  const uploadForm = document.getElementById('mediaUploadForm');
  const uploadBtn = document.getElementById('uploadSubmitBtn');

  if (uploadForm && uploadInput) {
    uploadForm.addEventListener('submit', function(e) {
      const file = uploadInput.files[0];
      if (!file) return;

      const isImage = file.type.startsWith('image/') && !file.type.includes('svg');
      
      // If it's already WebP or is a video, proceed directly
      if (!isImage || file.type.includes('webp')) {
        uploadBtn.disabled = true;
        uploadBtn.innerHTML = 'Uploading...';
        return; // standard submit
      }

      // Convert image to WebP using native browser canvas
      e.preventDefault();
      uploadBtn.disabled = true;
      uploadBtn.innerHTML = 'Converting to WebP & Uploading...';

      const reader = new FileReader();
      reader.onload = function(evt) {
        const img = new Image();
        img.onload = function() {
          const canvas = document.createElement('canvas');
          canvas.width = img.width;
          canvas.height = img.height;
          const ctx = canvas.getContext('2d');
          ctx.drawImage(img, 0, 0);

          canvas.toBlob(function(blob) {
            const webpName = file.name.replace(/\.[^/.]+$/, "") + ".webp";
            const webpFile = new File([blob || file], webpName, { type: 'image/webp' });

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(webpFile);
            uploadInput.files = dataTransfer.files;

            uploadForm.submit();
          }, 'image/webp', 0.85);
        };
        img.src = evt.target.result;
      };
      reader.readAsDataURL(file);
    });
  }
</script>
@endsection
