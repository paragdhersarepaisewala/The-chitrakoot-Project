@extends('layouts.admin')

@section('title', ($post->exists ? 'Edit Article: ' . $post->title_hi : 'Write New Article') . ' | CMS Admin')

@section('styles')
<!-- Quill.js WYSIWYG Editor Styles -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<style>
  .ql-toolbar.ql-snow {
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
    border-color: rgba(89, 58, 40, 0.18);
    background: #fbf9f6;
    font-family: var(--sans);
    padding: 10px 14px;
  }
  .ql-container.ql-snow {
    border-bottom-left-radius: 8px;
    border-bottom-right-radius: 8px;
    border-color: rgba(89, 58, 40, 0.18);
    font-family: var(--dev), "Playfair Display", Georgia, serif;
    font-size: 16px;
    line-height: 1.8;
    background: #ffffff;
    min-height: 420px;
  }
  .ql-editor {
    min-height: 420px;
    padding: 20px 24px;
    font-size: 16px;
    line-height: 1.8;
    color: #1a1410;
  }
  .ql-editor.ql-blank::before {
    font-style: normal;
    color: rgba(43, 35, 29, 0.45);
    font-family: var(--dev), Georgia, serif;
  }
  .editor-layout-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 28px;
    align-items: start;
  }
  .form-section-card {
    background: #ffffff;
    border: 1px solid var(--line, rgba(89, 58, 40, 0.14));
    border-radius: 10px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 4px 18px rgba(0,0,0,0.02);
  }
  .form-label {
    display: block;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: rgba(43, 35, 29, 0.7);
    margin-bottom: 8px;
    font-family: var(--sans, sans-serif);
  }
  .form-input, .form-select, .form-textarea {
    width: 100%;
    padding: 11px 14px;
    border: 1px solid rgba(89, 58, 40, 0.18);
    border-radius: 6px;
    font-size: 14px;
    background: #ffffff;
    color: #16100d;
    transition: border-color .2s ease, box-shadow .2s ease;
  }
  .form-input:focus, .form-select:focus, .form-textarea:focus {
    outline: none;
    border-color: var(--sand, #b98550);
    box-shadow: 0 0 0 3px rgba(185, 133, 80, 0.18);
  }
  .inserter-deck {
    background: #fbf9f6;
    border: 1px solid rgba(185, 133, 80, 0.25);
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 24px;
  }
  .inserter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 16px;
    margin-top: 14px;
  }
  .inserter-card {
    background: #ffffff;
    border: 1px solid rgba(89, 58, 40, 0.12);
    border-radius: 8px;
    padding: 16px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 0 2px 8px rgba(0,0,0,0.02);
  }
  .inserter-card-title {
    font-size: 12px;
    font-weight: 800;
    letter-spacing: .05em;
    color: #16100d;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .btn-insert {
    width: 100%;
    padding: 9px 14px;
    border-radius: 9999px;
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: .04em;
    text-transform: uppercase;
    border: none;
    cursor: pointer;
    transition: all .2s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    margin-top: 10px;
  }
  .btn-insert-red { background: var(--crimson, #8f1719); color: #fff; }
  .btn-insert-red:hover { background: #6e1012; }
  .btn-insert-green { background: #086c60; color: #fff; }
  .btn-insert-green:hover { background: #064c43; }
  .btn-insert-dark { background: #16100d; color: #fff; }
  .btn-insert-dark:hover { background: #2c211c; }
  .btn-insert-gold { background: #f4a900; color: #16100d; font-weight: 800; }
  .btn-insert-gold:hover { background: #d49300; }

  @media (max-width: 1080px) {
    .editor-layout-grid {
      grid-template-columns: 1fr;
    }
  }
</style>
@endsection

@section('content')

<!-- Header Action Strip (Matching Screenshot 1) -->
<div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid var(--line);">
  <div>
    <div style="display: flex; align-items: center; gap: 12px;">
      <h1 style="font-size: 26px; font-weight: 700; font-family: var(--dev, serif); color: var(--deep); margin: 0;">
        {{ $post->exists ? 'Edit Article: ' . $post->title_hi : 'Write New Article' }}
      </h1>
      <span id="autosaveIndicator" style="display: inline-flex; align-items: center; gap: 6px; font-size: 11px; font-weight: 600; color: #086c60; background: rgba(8,108,96,0.1); padding: 3px 10px; border-radius: 9999px;">
        <span style="width: 7px; height: 7px; border-radius: 50%; background: #086c60; display: inline-block;"></span>
        Server Autosave Enabled (Every 15s)
      </span>
    </div>
    <p style="font-size: 12px; color: rgba(43,35,29,0.65); margin-top: 4px;">
      Create and publish articles with auto-categories, tags, media shortcodes, and Quill/Markdown editor
    </p>
  </div>

  <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
    <a href="{{ url('/') }}" target="_blank" class="action-btn" style="background: #8f1719; color: #fff; border-color: #8f1719;">
      &larr; BACK TO HOMEPAGE
    </a>
    <a href="{{ route('admin.blogs') }}" class="action-btn">
      BACK TO ARTICLES
    </a>
    <button type="button" onclick="document.getElementById('postEditorForm').submit()" class="action-btn primary" style="background: #086c60; border-color: #086c60; font-size: 12px; padding: 9px 20px;">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
      {{ $post->exists ? 'UPDATE ARTICLE NOW' : 'PUBLISH ARTICLE NOW' }}
    </button>
  </div>
</div>

<form id="postEditorForm" action="{{ $post->exists ? route('admin.blogs.update', $post->id) : route('admin.blogs.store') }}" method="POST">
  @csrf
  @if($post->exists)
    @method('PUT')
  @endif

  <input type="hidden" name="post_id" id="hidden_post_id" value="{{ $post->id ?? '' }}">

  <div class="editor-layout-grid">
    
    <!-- LEFT MAIN COLUMN (Article Content & Widget Inserter) -->
    <div class="editor-main-col">
      
      <!-- Primary Fields Card -->
      <div class="form-section-card">
        <div style="margin-bottom: 20px;">
          <label class="form-label">ARTICLE TITLE (HINDI / ENGLISH)</label>
          <input type="text" name="title_hi" id="article_title" value="{{ old('title_hi', $post->title_hi) }}" required
            placeholder="Title / शीर्षक दर्ज करें..." class="form-input" style="font-size: 17px; font-weight: 600; font-family: var(--dev);">
        </div>

        <div style="margin-bottom: 20px;">
          <label class="form-label">URL SLUG (AUTO-GENERATED IF EMPTY)</label>
          <input type="text" name="slug" id="article_slug" value="{{ old('slug', $post->slug) }}"
            placeholder="english-article-slug" class="form-input" style="font-family: monospace; font-size: 13px; color: #555;">
        </div>

        <div>
          <label class="form-label">SUMMARY / EXCERPT</label>
          <textarea name="excerpt" id="article_excerpt" rows="3" class="form-textarea"
            placeholder="Write a short summary (लेख का संक्षिप्त सारांश)...">{{ old('excerpt', $post->excerpt) }}</textarea>
        </div>
      </div>

      <!-- IN-ARTICLE IMAGE & MULTI-GALLERY INSERTER TOOL (MATCHING SCREENSHOT 1) -->
      <div class="inserter-deck">
        <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(185,133,80,0.2); padding-bottom: 10px;">
          <div style="display: flex; align-items: center; gap: 8px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--sand)" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
            <strong style="font-size: 13px; color: #16100d; font-family: var(--sans);">In-Article Image & Multi-Gallery Inserter Tool</strong>
          </div>
          <span style="font-size: 11px; color: #7a6e65;">Directly insert server photos & shortcodes into article body</span>
        </div>

        <div class="inserter-grid">
          
          <!-- 1. Single Inline Image -->
          <div class="inserter-card">
            <div>
              <div class="inserter-card-title">
                <span>1. Single Inline Image</span>
                <span style="font-size: 10px; color: #777;">Upload / Pick</span>
              </div>

              <div style="display: flex; gap: 6px; margin-bottom: 10px;">
                <input type="text" id="ins_img_src" placeholder="Image Server Path" class="form-input" style="font-size: 12px; padding: 7px 10px;">
                <button type="button" onclick="openUniversalMediaPicker({ targetInput: '#ins_img_src' })" class="action-btn" style="padding: 6px 12px; font-size: 10px; background: #16100d; color: #fff;">PICK</button>
              </div>

              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 10px;">
                <select id="ins_img_width" class="form-select" style="font-size: 11px; padding: 6px 8px;">
                  <option value="100%">Full Width (100%)</option>
                  <option value="75%">Large (75%)</option>
                  <option value="50%" selected>Medium (50%)</option>
                  <option value="33%">Small (33%)</option>
                </select>
                <select id="ins_img_align" class="form-select" style="font-size: 11px; padding: 6px 8px;">
                  <option value="center" selected>Align Center</option>
                  <option value="left">Align Left</option>
                  <option value="right">Align Right</option>
                </select>
              </div>

              <input type="text" id="ins_img_caption" placeholder="Caption (Hindi / English)" class="form-input" style="font-size: 11.5px; padding: 6px 8px; margin-bottom: 8px;">
              <input type="text" id="ins_img_alt" placeholder="Alt Text (SEO)" class="form-input" style="font-size: 11.5px; padding: 6px 8px;">
            </div>

            <button type="button" onclick="insertSingleImageShortcode()" class="btn-insert btn-insert-red">
              + INSERT IMAGE
            </button>
          </div>

          <!-- 2. Multi-Image Gallery -->
          <div class="inserter-card">
            <div>
              <div class="inserter-card-title">
                <span>2. Multi-Image Gallery</span>
                <span style="font-size: 10px; color: #777;">Magazine Style (Multi-select)</span>
              </div>

              <div style="display: flex; gap: 6px; margin-bottom: 10px;">
                <input type="text" id="ins_gal_images" placeholder="Image paths (comma separated)" class="form-input" style="font-size: 12px; padding: 7px 10px;">
                <button type="button" onclick="openUniversalMediaPicker({ multiple: true, callback: (selected) => { const el = document.getElementById('ins_gal_images'); const newPaths = Array.isArray(selected) ? selected.map(i => i.relative_path || i.url).join(',') : (selected.relative_path || selected.url || selected); el.value = el.value ? el.value + ',' + newPaths : newPaths; } })" class="action-btn" style="padding: 6px 12px; font-size: 10px; background: #086c60; color: #fff; white-space: nowrap;">+ SELECT IMAGES</button>
              </div>

              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 10px;">
                <select id="ins_gal_cols" class="form-select" style="font-size: 11px; padding: 6px 8px;">
                  <option value="2" selected>2 Columns</option>
                  <option value="3">3 Columns</option>
                  <option value="4">4 Columns</option>
                </select>
                <input type="text" id="ins_gal_caption" placeholder="Gallery Caption" class="form-input" style="font-size: 11.5px; padding: 6px 8px;">
              </div>
            </div>

            <button type="button" onclick="insertGalleryShortcode()" class="btn-insert btn-insert-green">
              + INSERT GALLERY
            </button>
          </div>

          <!-- 3. Editorial Pull-Quote / विचार -->
          <div class="inserter-card">
            <div>
              <div class="inserter-card-title">
                <span>3. Editorial Pull-Quote / विचार</span>
                <span style="font-size: 10px; color: #777;">Typography</span>
              </div>

              <textarea id="ins_quote_text" rows="2" placeholder="Enter memorable quote / विचार..." class="form-textarea" style="font-size: 12px; margin-bottom: 8px;"></textarea>
              
              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                <input type="text" id="ins_quote_speaker" placeholder="Speaker / वक्ता" class="form-input" style="font-size: 11.5px; padding: 6px 8px;">
                <input type="text" id="ins_quote_role" placeholder="Designation / पद" class="form-input" style="font-size: 11.5px; padding: 6px 8px;">
              </div>
            </div>

            <button type="button" onclick="insertQuoteShortcode()" class="btn-insert btn-insert-red">
              + INSERT QUOTE INTO ARTICLE
            </button>
          </div>

          <!-- 4. Video & Audio Player -->
          <div class="inserter-card">
            <div>
              <div class="inserter-card-title">
                <span>4. Video & Audio Player</span>
                <span style="font-size: 10px; color: #777;">YouTube / MP4</span>
              </div>

              <div style="display: flex; gap: 6px; margin-bottom: 10px;">
                <input type="text" id="ins_video_url" placeholder="YouTube URL or MP4/WebM Link" class="form-input" style="font-size: 12px; padding: 7px 10px;">
                <button type="button" onclick="openUniversalMediaPicker({ targetInput: '#ins_video_url' })" class="action-btn" style="padding: 6px 12px; font-size: 10px; background: #16100d; color: #fff;">PICK</button>
              </div>

              <input type="text" id="ins_video_caption" placeholder="Video Title / Caption" class="form-input" style="font-size: 11.5px; padding: 6px 8px;">
            </div>

            <button type="button" onclick="insertVideoShortcode()" class="btn-insert btn-insert-dark">
              + INSERT VIDEO/AUDIO PLAYER
            </button>
          </div>

          <!-- 5. Interactive Polls & Widgets -->
          <div class="inserter-card">
            <div>
              <div class="inserter-card-title">
                <span>5. Interactive Polls & Widgets</span>
                <span style="font-size: 10px; color: #777;">Engagement</span>
              </div>

              <select id="ins_widget_type" onchange="toggleWidgetTypeFields(this.value)" class="form-select" style="font-size: 11.5px; margin-bottom: 8px;">
                <option value="poll">1. Readers Public Opinion Poll</option>
                <option value="mandakini_gauge">2. Ramghat Mandakini Water Level Gauge</option>
                <option value="membership">3. Monthly Patron Member Promotion Card</option>
              </select>

              <div id="poll_fields_wrapper">
                <input type="text" id="ins_poll_q" placeholder="Poll Question: e.g. Should Ramghat be 100% Eco-Zone?" class="form-input" style="font-size: 11.5px; padding: 6px 8px; margin-bottom: 8px;">
                
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
                  <span style="font-size: 10px; font-weight: 800; letter-spacing: .08em; color: #7a4b29; text-transform: uppercase;">Poll Options</span>
                  <button type="button" onclick="addPollOptionField()" style="background: none; border: none; color: #086c60; font-weight: 800; cursor: pointer; font-size: 10.5px;">+ ADD OPTION</button>
                </div>
                <div id="poll_options_container" style="display: flex; flex-direction: column; gap: 6px; margin-bottom: 10px;">
                  <div class="poll-opt-row" style="display: flex; gap: 4px; align-items: center;">
                    <span style="font-size: 11px; font-weight: 700; color: #8f1719; width: 16px;">A.</span>
                    <input type="text" class="form-input ins-poll-opt" placeholder="Option A text" value="हाँ, पूर्ण समर्थन" style="font-size: 11px; padding: 5px 8px; flex: 1;">
                  </div>
                  <div class="poll-opt-row" style="display: flex; gap: 4px; align-items: center;">
                    <span style="font-size: 11px; font-weight: 700; color: #8f1719; width: 16px;">B.</span>
                    <input type="text" class="form-input ins-poll-opt" placeholder="Option B text" value="नहीं, अन्य व्यवस्था जरूरी" style="font-size: 11px; padding: 5px 8px; flex: 1;">
                  </div>
                </div>
              </div>
            </div>

            <button type="button" onclick="insertWidgetShortcode()" class="btn-insert btn-insert-red">
              + INSERT POLL / WIDGET
            </button>
          </div>

        </div>
      </div>

      <!-- Article Content Body (Visual Rich Text Editor) -->
      <div class="form-section-card">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
          <div>
            <label class="form-label" style="margin: 0;">ARTICLE CONTENT (VISUAL RICH TEXT WYSIWYG EDITOR)</label>
            <span style="font-size: 11px; color: #7a6e65;">Compose formatted text, headings, quotes, and insert media widgets below</span>
          </div>
          <div style="display: flex; gap: 8px;">
            <button type="button" onclick="toggleShortcodeCheatSheet()" class="action-btn gold" style="padding: 5px 12px; font-size: 11px;">Shortcodes Guide</button>
          </div>
        </div>

        <!-- Embedded Visual Quill WYSIWYG Editor Container -->
        <div id="quillEditor"></div>
        <textarea name="content_hi" id="content_hi" style="display:none;" required>{{ old('content_hi', $post->content_hi) }}</textarea>

        <div id="shortcodeCheatSheet" style="display: none; background: #fdfaf5; border: 1px dashed var(--sand); border-radius: 6px; padding: 14px; margin-top: 14px; font-size: 12px;">
          <strong>Supported Shortcodes Reference:</strong>
          <ul style="margin: 8px 0 0 18px; line-height: 1.6; color: #555;">
            <li><code>[image src="/uploads/pic.webp" width="50%" align="center" caption="विवरण"]</code></li>
            <li><code>[gallery images="/uploads/1.webp,/uploads/2.webp" cols="2" caption="गैलरी"]</code></li>
            <li><code>[quote speaker="स्वामी जी" designation="पीठाधीश्वर"]पवित्र विचार...[/quote]</code></li>
            <li><code>[video src="https://youtube.com/watch?v=..." caption="आरती दर्शन"]</code></li>
            <li><code>[widget:opinion_poll question="प्रश्न?" opt1="हाँ" opt2="नहीं"]</code></li>
            <li><code>[widget:mandakini_gauge]</code> (Live Ramghat River Level)</li>
            <li><code>[campaign title="Become a Patron Member" amount="₹400/mo"]</code></li>
          </ul>
        </div>
      </div>

    </div>

    <!-- RIGHT SIDEBAR (Publish Controls, Category, Featured Image) -->
    <div class="editor-side-col">
      
      <!-- Publish Controls (Matching Screenshot 1) -->
      <div class="form-section-card">
        <h3 style="font-size: 13px; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; color: #16100d; margin: 0 0 18px; padding-bottom: 10px; border-bottom: 1px solid rgba(89,58,40,0.12);">
          Publish Controls
        </h3>

        <div style="margin-bottom: 16px;">
          <label class="form-label">STATUS</label>
          <select name="status" class="form-select">
            <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Published (प्रकाशित)</option>
            <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Draft (प्रारूप)</option>
          </select>
        </div>

        <div style="margin-bottom: 16px;">
          <label class="form-label">SELECT EXISTING CATEGORY</label>
          <select name="category" class="form-select">
            <option value="">-- Choose Category --</option>
            @foreach($categories as $cat)
              @php
                $slug = is_object($cat) ? $cat->slug : $cat;
                $label = is_object($cat) ? ($cat->name_hi . ($cat->name_en ? ' (' . $cat->name_en . ')' : '')) : $cat;
              @endphp
              <option value="{{ $slug }}" {{ old('category', $post->category) == $slug ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
        </div>

        <div style="margin-bottom: 16px;">
          <label class="form-label">OR TYPE NEW CATEGORY (AUTO-CREATES)</label>
          <input type="text" name="category_new" placeholder="eg. Special Reports, Wildlife..." class="form-input" style="font-size: 13px;">
        </div>

        <div style="margin-bottom: 16px;">
          <label class="form-label">VIDEO URL (YOUTUBE EMBED / MP4 LINK)</label>
          <div style="display: flex; gap: 6px;">
            <input type="text" name="video_url" id="sidebar_video_url" value="{{ old('video_url', $post->video_url) }}" placeholder="https://www.youtube.com/watch?v=..." class="form-input" style="font-size: 12px;">
            <button type="button" onclick="openUniversalMediaPicker({ targetInput: '#sidebar_video_url' })" class="action-btn teal" style="padding: 6px 12px; font-size: 10px;">REUSE</button>
          </div>
          <span style="font-size: 10.5px; color: #777; display: block; margin-top: 4px;">If provided, this video will feature in hero/spotlight.</span>
        </div>

        <div style="margin-bottom: 20px;">
          <label class="form-label">TAGS (COMMA SEPARATED - AUTO CREATES)</label>
          <input type="text" name="tags" value="{{ old('tags', $post->tags) }}" placeholder="eg. चित्रकूट, रामघाट, मंदिर, महोबा" class="form-input" style="font-size: 13px;">
        </div>

        <button type="submit" id="btnSubmitBlog" class="action-btn primary" style="width: 100%; padding: 12px; font-size: 13px; font-weight: 800; border-radius: 9999px; justify-content: center; background: #086c60; border-color: #086c60;">
          {{ $post->exists ? 'UPDATE ARTICLE' : 'PUBLISH ARTICLE' }}
        </button>
      </div>

      <!-- Featured Image Upload (Matching Screenshot 1) -->
      <div class="form-section-card">
        <h3 style="font-size: 13px; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; color: #16100d; margin: 0 0 18px; padding-bottom: 10px; border-bottom: 1px solid rgba(89,58,40,0.12);">
          Featured Image Upload
        </h3>

        <div style="margin-bottom: 14px;">
          <label class="form-label">FEATURED IMAGE URL / PATH</label>
          <input type="text" name="featured_image" id="featured_image" value="{{ old('featured_image', $post->featured_image) }}" placeholder="/uploads/..." class="form-input" style="font-size: 12px; margin-bottom: 8px;">

          <!-- Image Preview -->
          <div style="border-radius: 8px; overflow: hidden; background: #eee; aspect-ratio: 16/9; display: grid; place-items: center; margin-bottom: 10px; border: 1px solid #ddd;">
            <img id="featured_image_preview" src="{{ $post->featured_image ?: 'https://images.unsplash.com/photo-1548013146-72479768bada?auto=format&fit=crop&w=800&q=80' }}" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">
          </div>

          <button type="button" onclick="openUniversalMediaPicker({ targetInput: '#featured_image', targetPreview: '#featured_image_preview' })" class="action-btn teal" style="width: 100%; justify-content: center; padding: 9px; font-size: 11.5px; border-radius: 9999px;">
            REUSE FROM SERVER LIBRARY
          </button>
        </div>

        <div style="margin-bottom: 14px;">
          <label class="form-label">IMAGE CAPTION</label>
          <input type="text" name="featured_image_caption" value="{{ old('featured_image_caption', $post->featured_image_caption) }}" placeholder="Caption describing the photo..." class="form-input" style="font-size: 12px;">
        </div>

        <div>
          <label class="form-label">FEATURED IMAGE ALT TEXT (ACCESSIBILITY & SEO)</label>
          <input type="text" name="featured_image_alt" id="featured_image_alt" value="{{ old('featured_image_alt', $post->featured_image_alt) }}" placeholder="Descriptive image alt text..." class="form-input" style="font-size: 12px;">
        </div>
      </div>

      <!-- Real-Time SEO & Readability Checker Card (Matching RankMath / Yoast) -->
      <div class="form-section-card" id="seoCheckerCard" style="border-left: 4px solid var(--sand, #b98550);">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; padding-bottom: 10px; border-bottom: 1px solid rgba(89,58,40,0.12);">
          <h3 style="font-size: 13px; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; color: #16100d; margin: 0;">
            Live SEO Checker
          </h3>
          <span id="seoScoreBadge" style="background: #e2e8f0; color: #475569; font-size: 11px; font-weight: 800; padding: 4px 10px; border-radius: 9999px;">
            0 / 100
          </span>
        </div>

        <div style="margin-bottom: 14px;">
          <div style="height: 6px; background: #eee; border-radius: 9999px; overflow: hidden; margin-bottom: 6px;">
            <div id="seoScoreBar" style="width: 0%; height: 100%; background: #e2e8f0; transition: width .3s ease, background-color .3s ease;"></div>
          </div>
          <div id="seoVerdictText" style="font-size: 11.5px; font-weight: 700; color: #7a6e65;">Enter a focus keyword to evaluate search optimization</div>
        </div>

        <div style="margin-bottom: 16px;">
          <label class="form-label">FOCUS KEYWORD (मुख्य खोज शब्द)</label>
          <input type="text" name="focus_keyword" id="focus_keyword" value="{{ old('focus_keyword', $post->focus_keyword) }}" placeholder="e.g. कामदगिरि, चित्रकूट, गौसेवा" class="form-input" style="font-size: 13px; font-weight: 600;">
          <input type="hidden" name="seo_score" id="seo_score" value="{{ old('seo_score', $post->seo_score ?? 0) }}">
        </div>

        <!-- Live Real-Time Criteria Checklist -->
        <div style="display: flex; flex-direction: column; gap: 8px; font-size: 12px; color: #444;">
          <div id="seo_check_keyword" style="display: flex; align-items: center; gap: 8px;">
            <span class="seo-dot" style="width: 8px; height: 8px; border-radius: 50%; background: #cbd5e1; display: inline-block;"></span>
            <span>Focus keyword defined</span>
          </div>
          <div id="seo_check_title" style="display: flex; align-items: center; gap: 8px;">
            <span class="seo-dot" style="width: 8px; height: 8px; border-radius: 50%; background: #cbd5e1; display: inline-block;"></span>
            <span>Keyword appears in Article Title</span>
          </div>
          <div id="seo_check_slug" style="display: flex; align-items: center; gap: 8px;">
            <span class="seo-dot" style="width: 8px; height: 8px; border-radius: 50%; background: #cbd5e1; display: inline-block;"></span>
            <span>Keyword in URL slug</span>
          </div>
          <div id="seo_check_excerpt" style="display: flex; align-items: center; gap: 8px;">
            <span class="seo-dot" style="width: 8px; height: 8px; border-radius: 50%; background: #cbd5e1; display: inline-block;"></span>
            <span>Keyword in Meta Summary / Excerpt</span>
          </div>
          <div id="seo_check_content" style="display: flex; align-items: center; gap: 8px;">
            <span class="seo-dot" style="width: 8px; height: 8px; border-radius: 50%; background: #cbd5e1; display: inline-block;"></span>
            <span>Keyword appears in Article Body</span>
          </div>
          <div id="seo_check_length" style="display: flex; align-items: center; gap: 8px;">
            <span class="seo-dot" style="width: 8px; height: 8px; border-radius: 50%; background: #cbd5e1; display: inline-block;"></span>
            <span id="seo_word_count_label">Content length (0 words / target 300+)</span>
          </div>
          <div id="seo_check_headings" style="display: flex; align-items: center; gap: 8px;">
            <span class="seo-dot" style="width: 8px; height: 8px; border-radius: 50%; background: #cbd5e1; display: inline-block;"></span>
            <span>Subheadings (H2 / H3) included</span>
          </div>
          <div id="seo_check_media" style="display: flex; align-items: center; gap: 8px;">
            <span class="seo-dot" style="width: 8px; height: 8px; border-radius: 50%; background: #cbd5e1; display: inline-block;"></span>
            <span>Featured image & alt text configured</span>
          </div>
        </div>
      </div>

    </div>

  </div>
</form>

@endsection

@section('scripts')
<!-- Quill.js WYSIWYG Editor Script -->
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
  // Initialize Embedded Visual Quill Editor
  const quill = new Quill('#quillEditor', {
    theme: 'snow',
    placeholder: 'यहाँ आलेख का विस्तृत विवरण लिखें... शीर्ष टूलबार से फॉर्मेट करें अथवा ऊपर दिए गए टूल्स से चित्र, गैलरी, उद्धरण व वीडियो शामिल करें।',
    modules: {
      toolbar: [
        [{ 'header': [1, 2, 3, false] }],
        ['bold', 'italic', 'underline', 'strike'],
        [{ 'color': [] }, { 'background': [] }],
        [{ 'align': [] }],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        ['blockquote', 'code-block'],
        ['link', 'clean']
      ]
    }
  });

  const hiddenContent = document.getElementById('content_hi');
  if (hiddenContent && hiddenContent.value.trim().length > 0) {
    quill.root.innerHTML = hiddenContent.value;
  }

  // Real-time synchronization to hidden form field
  quill.on('text-change', function() {
    if (hiddenContent) {
      hiddenContent.value = quill.root.innerHTML;
    }
  });

  // Ensure form submit has updated HTML payload & prevent double click submission
  const mainBlogForm = document.querySelector('form[action*="blogs"]');
  if (mainBlogForm) {
    mainBlogForm.addEventListener('submit', function(e) {
      if (hiddenContent) {
        hiddenContent.value = quill.root.innerHTML;
      }
      const submitBtn = document.getElementById('btnSubmitBlog');
      if (submitBtn) {
        if (submitBtn.dataset.submitting === '1') {
          e.preventDefault();
          return false;
        }
        submitBtn.dataset.submitting = '1';
        submitBtn.style.opacity = '0.7';
        submitBtn.style.pointerEvents = 'none';
        submitBtn.innerText = 'कृपया प्रतीक्षा करें... (Saving...)';
      }
    });
  }

  // Slug Auto-Generation
  const titleInput = document.getElementById('article_title');
  const slugInput = document.getElementById('article_slug');

  if (titleInput && slugInput && !slugInput.value) {
    titleInput.addEventListener('blur', function() {
      if (!slugInput.value && this.value) {
        const slug = this.value
          .toLowerCase()
          .replace(/[^\w\s-]/g, '')
          .trim()
          .replace(/[\s_-]+/g, '-');
        slugInput.value = slug || 'article-' + Date.now();
      }
    });
  }

  // Insert Shortcode or Media Snippet at Cursor in Quill Editor
  function insertAtCursor(textareaDummy, text) {
    const range = quill.getSelection(true);
    const index = range ? range.index : quill.getLength();
    quill.insertText(index, text + '\n', 'user');
    quill.setSelection(index + text.length + 1);
    if (hiddenContent) {
      hiddenContent.value = quill.root.innerHTML;
    }
  }

  function insertSingleImageShortcode() {
    const src = document.getElementById('ins_img_src').value.trim();
    if (!src) {
      alert('कृपया पहले इमेज का पाथ या लाइब्रेरी से इमेज चुनें।');
      return;
    }
    const width = document.getElementById('ins_img_width').value;
    const align = document.getElementById('ins_img_align').value;
    const caption = document.getElementById('ins_img_caption').value.trim();
    const alt = document.getElementById('ins_img_alt').value.trim();

    const shortcode = `\n[image src="${src}" width="${width}" align="${align}" caption="${caption}" alt="${alt}"]\n`;
    insertAtCursor(null, shortcode);
    showToast('Single Inline Image inserted into article', 'success');
  }

  function insertGalleryShortcode() {
    const images = document.getElementById('ins_gal_images').value.trim();
    if (!images) {
      alert('कृपया गैलरी के लिए कम से कम 1 इमेज चुनें।');
      return;
    }
    const cols = document.getElementById('ins_gal_cols').value;
    const caption = document.getElementById('ins_gal_caption').value.trim();

    const shortcode = `\n[gallery images="${images}" cols="${cols}" caption="${caption}"]\n`;
    insertAtCursor(null, shortcode);
    showToast('Multi-Image Magazine Gallery inserted', 'success');
  }

  function insertQuoteShortcode() {
    const quote = document.getElementById('ins_quote_text').value.trim();
    if (!quote) {
      alert('कृपया कोटेशन टेक्स्ट दर्ज करें।');
      return;
    }
    const speaker = document.getElementById('ins_quote_speaker').value.trim();
    const role = document.getElementById('ins_quote_role').value.trim();

    const shortcode = `\n[quote speaker="${speaker}" designation="${role}"]${quote}[/quote]\n`;
    insertAtCursor(null, shortcode);
    showToast('Editorial Pull-Quote inserted', 'success');
  }

  function insertVideoShortcode() {
    const src = document.getElementById('ins_video_url').value.trim();
    if (!src) {
      alert('कृपया वीडियो का URL दर्ज करें।');
      return;
    }
    const caption = document.getElementById('ins_video_caption').value.trim();
    const shortcode = `\n[video src="${src}" caption="${caption}"]\n`;
    insertAtCursor(null, shortcode);
    showToast('Video Player inserted into article', 'success');
  }

  function toggleWidgetTypeFields(type) {
    const pollWrap = document.getElementById('poll_fields_wrapper');
    if (pollWrap) {
      pollWrap.style.display = type === 'poll' ? 'block' : 'none';
    }
  }

  function addPollOptionField() {
    const container = document.getElementById('poll_options_container');
    if (!container) return;
    const currentCount = container.querySelectorAll('.poll-opt-row').length;
    if (currentCount >= 8) {
      alert('अधिकतम 8 विकल्प जोड़े जा सकते हैं।');
      return;
    }
    const letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
    const letter = letters[currentCount] || String.fromCharCode(65 + currentCount);

    const row = document.createElement('div');
    row.className = 'poll-opt-row';
    row.style.cssText = 'display: flex; gap: 4px; align-items: center;';
    row.innerHTML = `
      <span style="font-size: 11px; font-weight: 700; color: #8f1719; width: 16px;">${letter}.</span>
      <input type="text" class="form-input ins-poll-opt" placeholder="Option ${letter} text" style="font-size: 11px; padding: 5px 8px; flex: 1;">
      <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; color: #dc2626; font-size: 14px; cursor: pointer; padding: 0 4px;" title="Remove option">&times;</button>
    `;
    container.appendChild(row);
  }

  function insertWidgetShortcode() {
    const type = document.getElementById('ins_widget_type').value;
    let shortcode = '';
    if (type === 'poll') {
      const q = document.getElementById('ins_poll_q').value.trim() || 'Should Ramghat Parikrama Path be designated 100% Eco-Zone?';
      const optInputs = document.querySelectorAll('.ins-poll-opt');
      let optAttrs = [];
      let optIdx = 1;
      optInputs.forEach(inp => {
        const val = inp.value.trim();
        if (val) {
          const sanitized = val.replace(/"/g, '&quot;');
          optAttrs.push(`opt${optIdx}="${sanitized}"`);
          optIdx++;
        }
      });
      if (optAttrs.length === 0) {
        optAttrs = ['opt1="हाँ, पूर्ण समर्थन"', 'opt2="नहीं, अन्य व्यवस्था जरूरी"'];
      }
      shortcode = `\n[poll question="${q.replace(/"/g, '&quot;')}" ${optAttrs.join(' ')}]\n`;
    } else if (type === 'mandakini_gauge') {
      shortcode = `\n[widget:mandakini_gauge]\n`;
    } else if (type === 'membership') {
      shortcode = `\n[campaign title="Become a Monthly Patron Member" amount="₹400/month"]\n`;
    }
    insertAtCursor(null, shortcode);
    showToast('Interactive Widget inserted into article', 'success');
  }

  function toggleShortcodeCheatSheet() {
    const el = document.getElementById('shortcodeCheatSheet');
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
  }

  // 15-SECOND SERVER AUTOSAVE ENGINE
  let autosaveTimer = setInterval(triggerAutosave, 15000);

  function triggerAutosave() {
    const title = document.getElementById('article_title').value.trim();
    const content = quill ? quill.root.innerHTML : document.getElementById('content_hi').value.trim();
    if (!title && (!content || content === '<p><br></p>')) return;

    if (hiddenContent) {
      hiddenContent.value = content;
    }

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const postId = document.getElementById('hidden_post_id').value;
    const excerpt = document.getElementById('article_excerpt').value;
    const featImg = document.getElementById('featured_image').value;

    const payload = new FormData();
    payload.append('post_id', postId);
    payload.append('title_hi', title);
    payload.append('content_hi', content);
    payload.append('excerpt', excerpt);
    payload.append('featured_image', featImg);

    fetch("{{ route('admin.blogs.autosave') }}", {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': token,
        'Accept': 'application/json'
      },
      body: payload
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        if (!postId && data.post_id) {
          document.getElementById('hidden_post_id').value = data.post_id;
        }
        const indicator = document.getElementById('autosaveIndicator');
        if (indicator) {
          indicator.innerHTML = `<span style="width: 7px; height: 7px; border-radius: 50%; background: #086c60; display: inline-block;"></span> Draft auto-saved at ${data.saved_at}`;
        }
      }
    })
    .catch(console.error);
  }

  // LIVE REAL-TIME SEO & READABILITY CHECKER (RankMath / Yoast Engine)
  function calculateSeoScore() {
    const keyword = (document.getElementById('focus_keyword')?.value || '').trim().toLowerCase();
    const title = (document.getElementById('article_title')?.value || '').trim().toLowerCase();
    const slug = (document.getElementById('article_slug')?.value || '').trim().toLowerCase();
    const excerpt = (document.getElementById('article_excerpt')?.value || '').trim().toLowerCase();
    const htmlContent = quill ? quill.root.innerHTML : (document.getElementById('content_hi')?.value || '');
    const textContent = quill ? quill.getText().trim() : htmlContent.replace(/<[^>]*>/g, '').trim();
    const contentLower = textContent.toLowerCase();
    const altText = (document.getElementById('featured_image_alt')?.value || '').trim();

    // Word count calculation
    const words = textContent.length > 0 ? textContent.split(/\s+/).filter(w => w.length > 0) : [];
    const wordCount = words.length;

    function setCheck(id, passed, labelOverride) {
      const el = document.getElementById(id);
      if (!el) return;
      const dot = el.querySelector('.seo-dot');
      if (dot) {
        dot.style.background = passed ? '#10b981' : '#cbd5e1';
      }
      if (labelOverride) {
        const textSpan = el.querySelector('span:last-child');
        if (textSpan) textSpan.innerText = labelOverride;
      }
    }

    if (!keyword) {
      setCheck('seo_check_keyword', false);
      setCheck('seo_check_title', false);
      setCheck('seo_check_slug', false);
      setCheck('seo_check_excerpt', false);
      setCheck('seo_check_content', false);
      setCheck('seo_check_length', false, `Content length (${wordCount} words / target 300+)`);
      setCheck('seo_check_headings', false);
      setCheck('seo_check_media', false);

      updateBadge(0, 'Enter focus keyword to evaluate SEO', '#e2e8f0', '#64748b');
      return;
    }

    let score = 0;

    // 1. Keyword Defined (+10)
    score += 10;
    setCheck('seo_check_keyword', true);

    // 2. Keyword in Title (+20)
    const inTitle = title.includes(keyword);
    if (inTitle) score += 20;
    setCheck('seo_check_title', inTitle);

    // 3. Keyword in Slug (+15)
    const normalizedKeyword = keyword.replace(/\s+/g, '-');
    const inSlug = slug.includes(normalizedKeyword) || slug.includes(keyword) || (title.includes(keyword) && slug.length > 3);
    if (inSlug) score += 15;
    setCheck('seo_check_slug', inSlug);

    // 4. Keyword in Excerpt (+15)
    const inExcerpt = excerpt.includes(keyword);
    if (inExcerpt) score += 15;
    setCheck('seo_check_excerpt', inExcerpt);

    // 5. Keyword in Content (+15)
    const inContent = contentLower.includes(keyword);
    if (inContent) score += 15;
    setCheck('seo_check_content', inContent);

    // 6. Word Count (+10)
    const lengthPassed = wordCount >= 200;
    if (lengthPassed) score += 10;
    setCheck('seo_check_length', lengthPassed, `Content length (${wordCount} words / target 300+)`);

    // 7. Headings included (+8)
    const hasHeadings = htmlContent.includes('<h2') || htmlContent.includes('<h3') || htmlContent.includes('##');
    if (hasHeadings) score += 8;
    setCheck('seo_check_headings', hasHeadings);

    // 8. Featured image & alt text (+7)
    const featImg = (document.getElementById('featured_image')?.value || '').trim();
    const mediaPassed = featImg.length > 0 && altText.length > 3;
    if (mediaPassed) score += 7;
    setCheck('seo_check_media', mediaPassed);

    score = Math.min(100, score);

    let verdict = 'Needs Improvement / सुधार आवश्यक';
    let color = '#ef4444';
    let textColor = '#fff';
    if (score >= 80) {
      verdict = 'Excellent / उत्कृष्ट SEO अनुकूलित';
      color = '#10b981';
    } else if (score >= 50) {
      verdict = 'Good / संतोषजनक (Good)';
      color = '#f59e0b';
    }

    updateBadge(score, verdict, color, textColor);
  }

  function updateBadge(score, verdict, color, textColor) {
    const badge = document.getElementById('seoScoreBadge');
    const bar = document.getElementById('seoScoreBar');
    const text = document.getElementById('seoVerdictText');
    const hiddenScore = document.getElementById('seo_score');

    if (badge) {
      badge.innerText = `${score} / 100`;
      badge.style.background = color;
      badge.style.color = textColor || '#fff';
    }
    if (bar) {
      bar.style.width = `${score}%`;
      bar.style.background = color;
    }
    if (text) {
      text.innerText = verdict;
      text.style.color = color === '#e2e8f0' ? '#7a6e65' : color;
    }
    if (hiddenScore) {
      hiddenScore.value = score;
    }
  }

  // Hook into input events
  ['article_title', 'article_slug', 'article_excerpt', 'focus_keyword', 'featured_image_alt', 'featured_image'].forEach(id => {
    document.getElementById(id)?.addEventListener('input', calculateSeoScore);
    document.getElementById(id)?.addEventListener('change', calculateSeoScore);
  });
  if (typeof quill !== 'undefined') {
    quill.on('text-change', calculateSeoScore);
  }
  setTimeout(calculateSeoScore, 600);
</script>
@endsection
