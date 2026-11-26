<!-- Universal Media Picker Modal Component -->
<div id="universalMediaPickerModal" class="media-modal-backdrop"
  style="display: none; position: fixed; inset: 0; background: rgba(20, 14, 11, 0.75); backdrop-filter: blur(6px); z-index: 999999; align-items: center; justify-content: center; padding: 20px;">
  <div class="media-modal-dialog"
    style="background: #ffffff; border-radius: 12px; border: 1px solid rgba(185, 133, 80, 0.3); width: 100%; max-width: 960px; max-height: 90vh; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 25px 60px rgba(0,0,0,0.4);">

    <!-- Modal Header -->
    <div
      style="padding: 16px 24px; border-bottom: 1px solid rgba(89, 58, 40, 0.12); display: flex; align-items: center; justify-content: space-between; background: #faf8f5;">
      <div style="display: flex; align-items: center; gap: 10px;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--sand, #b98550)" stroke-width="2">
          <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
          <circle cx="8.5" cy="8.5" r="1.5"></circle>
          <polyline points="21 15 16 10 5 21"></polyline>
        </svg>
        <h3 style="font-size: 16px; font-weight: 700; color: #16100d; margin: 0; font-family: var(--sans, sans-serif);">
          Server Media Library & Reusable Photo Bank</h3>
      </div>
      <button type="button" onclick="closeUniversalMediaPicker()"
        style="background: none; border: none; font-size: 24px; line-height: 1; color: #888; cursor: pointer; padding: 4px 8px; border-radius: 4px;">&times;</button>
    </div>

    <!-- Modal Toolbar -->
    <div
      style="padding: 12px 24px; background: #fff; border-bottom: 1px solid rgba(89, 58, 40, 0.08); display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
      <div style="display: flex; gap: 8px;">
        <button type="button" class="picker-filter-btn active" data-type="all" onclick="filterPickerMedia('all', this)"
          style="padding: 6px 14px; border-radius: 9999px; font-size: 12px; font-weight: 700; border: 1px solid #ddd; background: #16100d; color: #fff; cursor: pointer;">All
          Media</button>
        <button type="button" class="picker-filter-btn" data-type="image" onclick="filterPickerMedia('image', this)"
          style="padding: 6px 14px; border-radius: 9999px; font-size: 12px; font-weight: 600; border: 1px solid #ddd; background: #f7f4ed; color: #333; cursor: pointer;">Photos
          & WebP</button>
        <button type="button" class="picker-filter-btn" data-type="video" onclick="filterPickerMedia('video', this)"
          style="padding: 6px 14px; border-radius: 9999px; font-size: 12px; font-weight: 600; border: 1px solid #ddd; background: #f7f4ed; color: #333; cursor: pointer;">Videos</button>
      </div>

      <div style="display: flex; align-items: center; gap: 10px;">
        <input type="text" id="pickerSearchInput" placeholder="Search filename..." oninput="debouncePickerSearch()"
          style="padding: 7px 12px; font-size: 12px; border: 1px solid #ccc; border-radius: 6px; width: 180px;">

        <label for="pickerDirectUpload"
          style="display: inline-flex; align-items: center; gap: 6px; padding: 7px 16px; border-radius: 9999px; background: var(--crimson, #8f1719); color: #fff; font-size: 12px; font-weight: 700; cursor: pointer;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
            <polyline points="17 8 12 3 7 8"></polyline>
            <line x1="12" y1="3" x2="12" y2="15"></line>
          </svg>
          Upload New
        </label>
        <input type="file" id="pickerDirectUpload" accept="image/*,video/*" style="display: none;"
          onchange="handlePickerFileUpload(this)">
      </div>
    </div>

    <!-- Modal Media Grid -->
    <div id="pickerMediaGrid"
      style="padding: 20px 24px; overflow-y: auto; flex: 1; display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 12px; background: #fdfcfb;">
      <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #888;">Loading media library...</div>
    </div>

    <!-- Modal Footer -->
    <div
      style="padding: 12px 24px; border-top: 1px solid rgba(89, 58, 40, 0.12); background: #faf8f5; display: flex; align-items: center; justify-content: space-between;">
      <div id="pickerSelectedInfo" style="font-size: 12px; color: #555;">No media selected</div>
      <div style="display: flex; gap: 10px;">
        <button type="button" onclick="closeUniversalMediaPicker()"
          style="padding: 8px 18px; border-radius: 9999px; font-size: 12px; font-weight: 600; border: 1px solid #ccc; background: #fff; cursor: pointer;">Cancel</button>
        <button type="button" id="pickerInsertBtn" onclick="confirmUniversalMediaSelection()" disabled
          style="padding: 8px 22px; border-radius: 9999px; font-size: 12px; font-weight: 700; border: none; background: #086c60; color: #fff; cursor: pointer; opacity: 0.5;">Select
          & Insert</button>
      </div>
    </div>

  </div>
</div>

<script>
  let pickerTargetInput = null;
  let pickerTargetPreview = null;
  let pickerCustomCallback = null;
  let pickerSelectedMedia = null;
  let pickerSelectedList = [];
  let pickerIsMultiple = false;
  let pickerCurrentType = 'all';
  let pickerSearchTimer = null;

  function openUniversalMediaPicker(options = {}, previewArg = null) {
    if (typeof options === 'string') {
      const inputSelector = options.startsWith('#') || options.startsWith('.') ? options : '#' + options;
      const previewSelector = previewArg ? (previewArg.startsWith('#') || previewArg.startsWith('.') ? previewArg : '#' + previewArg) : null;
      options = {
        targetInput: inputSelector,
        targetPreview: previewSelector
      };
    }

    pickerTargetInput = options.targetInput ? document.querySelector(options.targetInput) : null;
    pickerTargetPreview = options.targetPreview ? document.querySelector(options.targetPreview) : null;
    pickerCustomCallback = options.callback || null;
    pickerIsMultiple = !!options.multiple;
    pickerSelectedMedia = null;
    pickerSelectedList = [];

    const insertBtn = document.getElementById('pickerInsertBtn');
    insertBtn.disabled = true;
    insertBtn.style.opacity = '0.5';
    insertBtn.textContent = pickerIsMultiple ? 'Select & Insert Images' : 'Select & Insert';
    document.getElementById('pickerSelectedInfo').textContent = pickerIsMultiple
      ? 'Click multiple images to select (multi-select enabled)'
      : 'Click on any media item to select';

    const modal = document.getElementById('universalMediaPickerModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';

    loadPickerMedia();
  }

  function closeUniversalMediaPicker() {
    const modal = document.getElementById('universalMediaPickerModal');
    modal.style.display = 'none';
    document.body.style.overflow = '';
  }

  function filterPickerMedia(type, btn) {
    pickerCurrentType = type;
    document.querySelectorAll('.picker-filter-btn').forEach(b => {
      b.style.background = '#f7f4ed';
      b.style.color = '#333';
      b.classList.remove('active');
    });
    btn.style.background = '#16100d';
    btn.style.color = '#fff';
    btn.classList.add('active');
    loadPickerMedia();
  }

  function debouncePickerSearch() {
    clearTimeout(pickerSearchTimer);
    pickerSearchTimer = setTimeout(() => {
      loadPickerMedia();
    }, 300);
  }

  function loadPickerMedia() {
    const grid = document.getElementById('pickerMediaGrid');
    const query = document.getElementById('pickerSearchInput').value.trim();
    grid.innerHTML = '<div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #888;">Fetching media...</div>';

    let url = `/admin/api/media-list?type=${pickerCurrentType}&q=${encodeURIComponent(query)}`;
    fetch(url)
      .then(r => r.json())
      .then(data => {
        if (!data.success || !data.items || data.items.length === 0) {
          grid.innerHTML = '<div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #888;">No media found. Click "Upload New" above to upload photos or videos.</div>';
          return;
        }

        grid.innerHTML = '';
        const escPickerHtml = (str) => String(str ?? '')
          .replace(/&/g, '&amp;')
          .replace(/</g, '&lt;')
          .replace(/>/g, '&gt;')
          .replace(/"/g, '&quot;')
          .replace(/'/g, '&#039;');

        data.items.forEach(item => {
          const card = document.createElement('div');
          card.className = 'picker-item-card';
          card.style.cssText = 'position: relative; border: 2px solid transparent; border-radius: 8px; overflow: hidden; background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.06); cursor: pointer; aspect-ratio: 1; display: flex; flex-direction: column; transition: all .2s ease;';

          const safeFileName = escPickerHtml(item.filename);
          const safeFileExt = escPickerHtml(String(item.filename || '').split('.').pop());
          const safeUrl = escPickerHtml(item.url);

          let previewHtml = '';
          if (item.file_type === 'video') {
            previewHtml = `<div style="flex:1; background: #16100d; display: grid; place-items: center; color: #fff;">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
            </div>`;
          } else {
            previewHtml = `<img src="${safeUrl}" alt="${safeFileName}" style="width: 100%; height: 100%; object-fit: cover;">`;
          }

          card.innerHTML = `
            <div style="flex: 1; overflow: hidden; position: relative;">
              ${previewHtml}
              <span style="position: absolute; top: 4px; left: 4px; background: rgba(0,0,0,0.7); color: #fff; font-size: 8px; font-weight: 700; padding: 2px 5px; border-radius: 3px; text-transform: uppercase;">${safeFileExt}</span>
              <div class="picker-check-badge" style="display: none; position: absolute; top: 4px; right: 4px; background: #086c60; color: #fff; width: 22px; height: 22px; border-radius: 50%; font-size: 11px; font-weight: 800; align-items: center; justify-content: center; box-shadow: 0 2px 6px rgba(0,0,0,0.3);">✓</div>
            </div>
            <div style="padding: 4px 6px; font-size: 10px; color: #444; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; background: #fff; border-top: 1px solid #eee;">
              ${safeFileName}
            </div>
          `;

          card.onclick = () => {
            const checkBadge = card.querySelector('.picker-check-badge');
            const insertBtn = document.getElementById('pickerInsertBtn');

            if (pickerIsMultiple) {
              const idx = pickerSelectedList.findIndex(i => i.id === item.id);
              if (idx > -1) {
                pickerSelectedList.splice(idx, 1);
                card.style.borderColor = 'transparent';
                card.style.boxShadow = '0 2px 8px rgba(0,0,0,0.06)';
                if (checkBadge) checkBadge.style.display = 'none';
              } else {
                pickerSelectedList.push(item);
                card.style.borderColor = '#086c60';
                card.style.boxShadow = '0 0 0 2px #086c60, 0 4px 12px rgba(8,108,96,0.2)';
                if (checkBadge) {
                  checkBadge.style.display = 'flex';
                  checkBadge.textContent = pickerSelectedList.length;
                }
              }

              if (pickerSelectedList.length > 0) {
                insertBtn.disabled = false;
                insertBtn.style.opacity = '1';
                insertBtn.textContent = `Insert ${pickerSelectedList.length} Selected Images`;
                document.getElementById('pickerSelectedInfo').textContent = `${pickerSelectedList.length} images selected`;
              } else {
                insertBtn.disabled = true;
                insertBtn.style.opacity = '0.5';
                insertBtn.textContent = 'Select & Insert Images';
                document.getElementById('pickerSelectedInfo').textContent = 'Click on images to select';
              }
            } else {
              document.querySelectorAll('.picker-item-card').forEach(c => {
                c.style.borderColor = 'transparent';
                c.style.boxShadow = '0 2px 8px rgba(0,0,0,0.06)';
                const b = c.querySelector('.picker-check-badge');
                if (b) b.style.display = 'none';
              });
              card.style.borderColor = '#086c60';
              card.style.boxShadow = '0 0 0 2px #086c60, 0 4px 12px rgba(8,108,96,0.2)';
              if (checkBadge) checkBadge.style.display = 'flex';

              pickerSelectedMedia = item;
              insertBtn.disabled = false;
              insertBtn.style.opacity = '1';
              insertBtn.textContent = 'Select & Insert';
              document.getElementById('pickerSelectedInfo').textContent = `Selected: ${item.filename} (${item.formatted_size})`;
            }
          };

          card.ondblclick = () => {
            if (!pickerIsMultiple) {
              pickerSelectedMedia = item;
              confirmUniversalMediaSelection();
            }
          };

          grid.appendChild(card);
        });
      })
      .catch(err => {
        grid.textContent = 'Failed to load media: ' + (err?.message || 'Unknown error');
      });
  }

  function confirmUniversalMediaSelection() {
    if (pickerIsMultiple) {
      if (pickerSelectedList.length === 0) return;

      const pathString = pickerSelectedList.map(i => i.relative_path || i.url).join(',');
      if (pickerTargetInput) {
        pickerTargetInput.value = pathString;
        pickerTargetInput.dispatchEvent(new Event('input', { bubbles: true }));
        pickerTargetInput.dispatchEvent(new Event('change', { bubbles: true }));
      }

      if (typeof pickerCustomCallback === 'function') {
        pickerCustomCallback(pickerSelectedList);
      }

      if (typeof showToast === 'function') {
        showToast(`Selected ${pickerSelectedList.length} images for gallery`, 'success');
      }

      closeUniversalMediaPicker();
      return;
    }

    if (!pickerSelectedMedia) return;

    if (pickerTargetInput) {
      pickerTargetInput.value = pickerSelectedMedia.relative_path || pickerSelectedMedia.url;
      pickerTargetInput.dispatchEvent(new Event('input', { bubbles: true }));
      pickerTargetInput.dispatchEvent(new Event('change', { bubbles: true }));
    }

    if (pickerTargetPreview) {
      if (pickerTargetPreview.tagName === 'IMG') {
        pickerTargetPreview.src = pickerSelectedMedia.url;
        pickerTargetPreview.style.display = 'block';
      } else if (pickerTargetPreview.tagName === 'VIDEO') {
        pickerTargetPreview.src = pickerSelectedMedia.url;
        pickerTargetPreview.style.display = 'block';
      }
    }

    if (typeof pickerCustomCallback === 'function') {
      pickerCustomCallback(pickerSelectedMedia);
    }

    if (typeof showToast === 'function') {
      showToast(`Selected ${pickerSelectedMedia.filename}`, 'success');
    }

    closeUniversalMediaPicker();
  }

  // Lightweight Client-Side WebP Converter on Upload (Only executes when file is chosen!)
  function handlePickerFileUpload(input) {
    if (!input.files || input.files.length === 0) return;
    const file = input.files[0];
    const isImage = file.type.startsWith('image/') && !file.type.includes('svg');

    document.getElementById('pickerSelectedInfo').textContent = 'Optimizing & uploading...';

    if (isImage && !file.type.includes('webp')) {
      // Lightweight client canvas conversion to high quality WebP
      const reader = new FileReader();
      reader.onload = function (e) {
        const img = new Image();
        img.onload = function () {
          const canvas = document.createElement('canvas');
          canvas.width = img.width;
          canvas.height = img.height;
          const ctx = canvas.getContext('2d');
          ctx.drawImage(img, 0, 0);

          canvas.toBlob(function (blob) {
            const webpName = file.name.replace(/\.[^/.]+$/, "") + ".webp";
            const webpFile = new File([blob || file], webpName, { type: 'image/webp' });
            sendPickerUpload(webpFile);
          }, 'image/webp', 0.85);
        };
        img.src = e.target.result;
      };
      reader.readAsDataURL(file);
    } else {
      sendPickerUpload(file);
    }
  }

  function sendPickerUpload(file) {
    const formData = new FormData();
    formData.append('file', file);
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/admin/media-gallery/upload', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': token,
        'Accept': 'application/json'
      },
      body: formData
    })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          if (typeof showToast === 'function') showToast('Media uploaded & converted to WebP successfully!', 'success');
          loadPickerMedia();
        } else {
          alert(data.message || 'Upload failed');
        }
      })
      .catch(err => {
        alert('Upload failed: ' + err.message);
      })
      .finally(() => {
        document.getElementById('pickerDirectUpload').value = '';
      });
  }
</script>