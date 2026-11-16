@extends('layouts.admin')

@section('title', 'Categories & Topics Manager | CMS Admin')
@section('header_title', 'Categories & Topics (Tags) Manager')
@section('header_subtitle', 'Organize articles, create and edit categories, manage topics and tags, and track article distributions')

@section('header_actions')
  <a href="{{ route('admin.blogs.create') }}" class="action-btn primary" style="font-weight: 800; letter-spacing: 0.05em;">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
    WRITE NEW ARTICLE &rarr;
  </a>
@endsection

@section('content')
<!-- Stats Grid -->
<div class="stats-grid" style="margin-bottom: 24px;">
  <div class="stat-card">
    <div class="stat-label">TOTAL CATEGORIES</div>
    <div class="stat-val" style="color: var(--crimson);">{{ $totalCategories }}</div>
    <div class="stat-sub">Active content channels</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">ARTICLES CATEGORIZED</div>
    <div class="stat-val" style="color: var(--deep);">{{ $totalArticlesCategorized }}</div>
    <div class="stat-sub">Stories & research published</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">UNIQUE TOPICS / TAGS</div>
    <div class="stat-val" style="color: #086c60;">{{ $totalTopics }}</div>
    <div class="stat-sub">Indexed keywords & subjects</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">ACTIVE CHANNELS</div>
    <div class="stat-val" style="color: #16a34a;">{{ $activeCategories }}</div>
    <div class="stat-sub">Live on public website</div>
  </div>
</div>

@if(session('success'))
  <div style="background: rgba(46,125,50,.12); color: #166534; padding: 14px 20px; border-radius: var(--radius); margin-bottom: 24px; border-left: 4px solid #166534; font-weight: 600; font-size: 13px;">
    {{ session('success') }}
  </div>
@endif

@if(session('info'))
  <div style="background: rgba(37,99,235,.12); color: #1e40af; padding: 14px 20px; border-radius: var(--radius); margin-bottom: 24px; border-left: 4px solid #1e40af; font-weight: 600; font-size: 13px;">
    {{ session('info') }}
  </div>
@endif

<!-- Main 2-Column Layout -->
<div style="display: grid; grid-template-columns: 1fr 1.6fr; gap: 24px; margin-bottom: 30px;">
  
  <!-- Left Column: Add New Category & Topic Manager -->
  <div style="display: flex; flex-direction: column; gap: 24px;">
    
    <!-- Add Category Card -->
    <div class="cms-table-card" style="padding: 24px;">
      <h3 style="font-size: 16px; font-weight: 800; color: var(--deep); margin: 0 0 6px;">Add New Category</h3>
      <p style="font-size: 12px; color: var(--brown); margin: 0 0 20px;">Register an editorial topic channel for stories and reports.</p>

      <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div style="margin-bottom: 14px;">
          <label class="form-label">Category Name (Hindi) *</label>
          <input type="text" name="name_hi" required placeholder="e.g. वेद, उपनिषद एवं दर्शन" class="form-input" style="width: 100%; font-size: 14px; font-weight: 600;">
        </div>

        <div style="margin-bottom: 14px;">
          <label class="form-label">Category Name (English) *</label>
          <input type="text" name="name_en" required placeholder="e.g. Vedic Philosophy & Upanishads" class="form-input" style="width: 100%; font-size: 13px;">
        </div>

        <div style="margin-bottom: 14px;">
          <label class="form-label">URL Slug (Auto-generated if blank)</label>
          <input type="text" name="slug" placeholder="e.g. vedic-philosophy" class="form-input" style="width: 100%; font-family: monospace; font-size: 12px;">
        </div>

        <div style="margin-bottom: 14px;">
          <label class="form-label">Channel Color Theme</label>
          <div style="display: flex; gap: 10px; align-items: center;">
            <input type="color" name="color" id="cat_color_input" value="#8f1719" style="width: 44px; height: 38px; padding: 2px; border: 1px solid var(--line); border-radius: 6px; cursor: pointer; background: #fff;">
            <div style="display: flex; gap: 6px; flex-wrap: wrap;">
              <span onclick="document.getElementById('cat_color_input').value='#8f1719'" style="width: 22px; height: 22px; border-radius: 50%; background: #8f1719; cursor: pointer; display: inline-block;"></span>
              <span onclick="document.getElementById('cat_color_input').value='#086c60'" style="width: 22px; height: 22px; border-radius: 50%; background: #086c60; cursor: pointer; display: inline-block;"></span>
              <span onclick="document.getElementById('cat_color_input').value='#b87333'" style="width: 22px; height: 22px; border-radius: 50%; background: #b87333; cursor: pointer; display: inline-block;"></span>
              <span onclick="document.getElementById('cat_color_input').value='#f4a900'" style="width: 22px; height: 22px; border-radius: 50%; background: #f4a900; cursor: pointer; display: inline-block;"></span>
              <span onclick="document.getElementById('cat_color_input').value='#593a28'" style="width: 22px; height: 22px; border-radius: 50%; background: #593a28; cursor: pointer; display: inline-block;"></span>
              <span onclick="document.getElementById('cat_color_input').value='#1e3a8a'" style="width: 22px; height: 22px; border-radius: 50%; background: #1e3a8a; cursor: pointer; display: inline-block;"></span>
            </div>
          </div>
        </div>

        <div style="margin-bottom: 14px;">
          <label class="form-label">Brief Description</label>
          <textarea name="description" rows="2" placeholder="Brief summary of what this channel covers..." class="form-input" style="width: 100%; font-size: 12px;"></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 18px;">
          <div>
            <label class="form-label">Sort Order</label>
            <input type="number" name="sort_order" value="1" min="1" class="form-input" style="width: 100%;">
          </div>
          <div style="display: flex; align-items: center; margin-top: 22px;">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px; font-weight: 700; color: var(--deep);">
              <input type="checkbox" name="is_active" value="1" checked style="width: 16px; height: 16px; accent-color: var(--crimson);">
              <span>Active in Nav</span>
            </label>
          </div>
        </div>

        <button type="submit" class="action-btn primary" style="width: 100%; justify-content: center; padding: 10px; font-weight: 800;">
          SAVE CATEGORY &rarr;
        </button>
      </form>
    </div>

    <!-- Topic / Tag Rename & Cleanup Card -->
    <div class="cms-table-card" style="padding: 24px;">
      <h3 style="font-size: 16px; font-weight: 800; color: var(--deep); margin: 0 0 6px;">Rename or Merge Topic Tag</h3>
      <p style="font-size: 12px; color: var(--brown); margin: 0 0 16px;">Updates the tag string across all matching articles instantaneously.</p>

      <form action="{{ route('admin.topics.rename') }}" method="POST">
        @csrf
        <div style="margin-bottom: 12px;">
          <label class="form-label">Existing Topic Tag *</label>
          <input type="text" name="old_tag" id="rename_old_tag" required placeholder="e.g. रामघाट" class="form-input" style="width: 100%; font-size: 13px;">
        </div>

        <div style="margin-bottom: 16px;">
          <label class="form-label">New Replacement Tag Name *</label>
          <input type="text" name="new_tag" required placeholder="e.g. पवित्र रामघाट चित्रकूट" class="form-input" style="width: 100%; font-size: 13px;">
        </div>

        <button type="submit" class="action-btn teal" style="width: 100%; justify-content: center; padding: 9px; font-weight: 800;">
          RENAME / MERGE TOPIC TAG
        </button>
      </form>
    </div>

  </div>

  <!-- Right Column: Categories Table & Topic Tag Cloud -->
  <div style="display: flex; flex-direction: column; gap: 24px;">
    
    <!-- Existing Categories Table Card -->
    <div class="cms-table-card">
      <div style="padding: 18px 24px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center;">
        <div>
          <h3 style="font-size: 16px; font-weight: 800; color: var(--deep); margin: 0;">Editorial Categories ({{ $categories->count() }})</h3>
          <span style="font-size: 11px; color: var(--brown);">Click on any category name to inspect articles under it</span>
        </div>
        <a href="{{ route('admin.categories') }}" class="action-btn" style="background: var(--sand); font-size: 11px; padding: 5px 12px;">View All</a>
      </div>

      <table class="cms-table" style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: #faf8f5; border-bottom: 1px solid var(--line); text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--brown);">
            <th style="padding: 12px 20px;">Category & Slug</th>
            <th style="padding: 12px 16px;">Articles</th>
            <th style="padding: 12px 16px;">Order</th>
            <th style="padding: 12px 16px;">Status</th>
            <th style="padding: 12px 20px; text-align: right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($categories as $category)
            <tr style="border-bottom: 1px solid var(--line); {{ $selectedCategory == $category->slug ? 'background: rgba(244,169,0,0.08);' : '' }}">
              <td style="padding: 14px 20px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                  <span style="width: 12px; height: 12px; border-radius: 50%; background: {{ $category->color ?: '#8f1719' }}; flex-shrink: 0; box-shadow: 0 0 0 2px rgba(0,0,0,0.1);"></span>
                  <div>
                    <a href="{{ route('admin.categories', ['category' => $category->slug]) }}" style="font-weight: 800; font-size: 14px; color: var(--deep); text-decoration: none;">
                      {{ $category->name_hi }}
                    </a>
                    <div style="font-size: 11px; color: var(--brown); margin-top: 2px;">
                      {{ $category->name_en }} &bull; Slug: <code>{{ $category->slug }}</code>
                    </div>
                  </div>
                </div>
              </td>
              <td style="padding: 14px 16px;">
                <a href="{{ route('admin.categories', ['category' => $category->slug]) }}" class="badge gold" style="font-weight: 800; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                  {{ $category->post_count }} Posts &nearr;
                </a>
              </td>
              <td style="padding: 14px 16px; font-weight: 700; color: var(--brown); font-size: 12px;">
                #{{ $category->sort_order }}
              </td>
              <td style="padding: 14px 16px;">
                @if($category->is_active)
                  <span class="badge" style="background: #dcfce7; color: #166534; font-weight: 700; font-size: 10px;">ACTIVE</span>
                @else
                  <span class="badge" style="background: #fee2e2; color: #991b1b; font-weight: 700; font-size: 10px;">INACTIVE</span>
                @endif
              </td>
              <td style="padding: 14px 20px; text-align: right;">
                <div style="display: inline-flex; gap: 6px;">
                  <button type="button" 
                          onclick='editCategoryModal(@json($category))' 
                          class="action-btn" 
                          style="background: var(--sand); color: var(--deep); padding: 5px 10px; font-size: 11px;">
                    Edit
                  </button>
                  <button type="button" 
                          onclick='confirmDeleteCategory(@json($category))'
                          class="action-btn" 
                          style="background: #fee2e2; color: #991b1b; padding: 5px 10px; font-size: 11px;">
                    Delete
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" style="padding: 30px; text-align: center; color: var(--brown);">
                No categories registered yet.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Topics & Tags Cloud Card -->
    <div class="cms-table-card" style="padding: 24px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <div>
          <h3 style="font-size: 16px; font-weight: 800; color: var(--deep); margin: 0;">Indexed Topics & Tags ({{ count($topicCounts) }})</h3>
          <span style="font-size: 11px; color: var(--brown);">Click any topic tag to filter articles or click delete to remove it</span>
        </div>
      </div>

      <div style="display: flex; flex-wrap: wrap; gap: 8px;">
        @forelse($topicCounts as $tag => $count)
          <div style="display: inline-flex; align-items: center; background: {{ $selectedTopic == $tag ? 'var(--deep)' : '#fdfaf5' }}; border: 1px solid {{ $selectedTopic == $tag ? 'var(--deep)' : 'var(--line)' }}; border-radius: 9999px; padding: 4px 10px; font-size: 12px; gap: 6px;">
            <a href="{{ route('admin.categories', ['topic' => $tag]) }}" style="color: {{ $selectedTopic == $tag ? '#fff' : 'var(--deep)' }}; text-decoration: none; font-weight: 600;">
              #{{ $tag }}
            </a>
            <span style="background: {{ $selectedTopic == $tag ? 'rgba(255,255,255,0.2)' : 'var(--sand)' }}; color: {{ $selectedTopic == $tag ? '#fff' : 'var(--deep)' }}; border-radius: 9999px; font-size: 10px; padding: 1px 6px; font-weight: 800;">
              {{ $count }}
            </span>
            <button type="button" onclick="prefillRenameTag('{{ addslashes($tag) }}')" title="Rename this topic tag" style="background: none; border: 0; color: {{ $selectedTopic == $tag ? '#fff' : 'var(--brown)' }}; cursor: pointer; padding: 0 2px;">
              <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
            </button>
            <form action="{{ route('admin.topics.delete') }}" method="POST" onsubmit="return confirm('Remove tag \'{{ addslashes($tag) }}\' from all articles?')" style="margin: 0; display: inline;">
              @csrf
              <input type="hidden" name="tag" value="{{ $tag }}">
              <button type="submit" title="Delete tag from all articles" style="background: none; border: 0; color: #dc2626; cursor: pointer; padding: 0 2px; font-size: 13px; line-height: 1;">&times;</button>
            </form>
          </div>
        @empty
          <div style="color: var(--brown); font-size: 12px; padding: 10px;">
            No tags found in any articles yet. Add tags when composing articles in the Blog Editor.
          </div>
        @endforelse
      </div>
    </div>

  </div>

</div>

<!-- Filtered Articles Section (if selected) -->
@if($filteredArticles)
  <div class="cms-table-card" style="margin-bottom: 30px;">
    <div style="padding: 18px 24px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center; background: #fffdfa;">
      <div>
        <h3 style="font-size: 16px; font-weight: 800; color: var(--deep); margin: 0;">
          Articles in 
          @if($selectedCategory)
            Category: <span style="color: var(--crimson);">{{ $selectedCategory }}</span>
          @elseif($selectedTopic)
            Topic: <span style="color: #086c60;">#{{ $selectedTopic }}</span>
          @endif
          ({{ $filteredArticles->total() }})
        </h3>
        <span style="font-size: 11px; color: var(--brown);">Click "Edit Article" to open this article directly in the rich visual composer</span>
      </div>
      <a href="{{ route('admin.categories') }}" class="action-btn" style="background: var(--sand); color: var(--deep); font-size: 11px;">
        &times; Clear Filter
      </a>
    </div>

    <table class="cms-table" style="width: 100%; border-collapse: collapse;">
      <thead>
        <tr style="background: #faf8f5; border-bottom: 1px solid var(--line); text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--brown);">
          <th style="padding: 12px 20px;">Article Title</th>
          <th style="padding: 12px 16px;">Category</th>
          <th style="padding: 12px 16px;">Tags</th>
          <th style="padding: 12px 16px;">Status</th>
          <th style="padding: 12px 20px; text-align: right;">Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($filteredArticles as $art)
          <tr style="border-bottom: 1px solid var(--line);">
            <td style="padding: 14px 20px;">
              <strong style="color: var(--deep); font-size: 14px; display: block;">{{ $art->title_hi }}</strong>
              <span style="font-size: 11px; color: var(--brown);">{{ $art->title_en }} &bull; Published: {{ $art->created_at->format('M d, Y') }}</span>
            </td>
            <td style="padding: 14px 16px;">
              <span class="badge gold" style="font-size: 10px;">{{ $art->category ?: 'Spiritual' }}</span>
            </td>
            <td style="padding: 14px 16px; font-size: 11px; color: var(--brown);">
              {{ Str::limit($art->tags ?: '—', 40) }}
            </td>
            <td style="padding: 14px 16px;">
              @if($art->status === 'published')
                <span class="badge" style="background: #dcfce7; color: #166534; font-weight: 700; font-size: 10px;">PUBLISHED</span>
              @else
                <span class="badge" style="background: #fef3c7; color: #92400e; font-weight: 700; font-size: 10px;">DRAFT</span>
              @endif
            </td>
            <td style="padding: 14px 20px; text-align: right;">
              <div style="display: inline-flex; gap: 6px;">
                <a href="{{ route('admin.blogs.edit', $art->id) }}" class="action-btn" style="background: var(--sand); color: var(--deep); padding: 5px 10px; font-size: 11px;">
                  Edit Article &rarr;
                </a>
                <a href="{{ route('blog.show', $art->slug) }}" target="_blank" class="action-btn" style="font-size: 11px; padding: 5px 8px;">
                  &nearr;
                </a>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" style="padding: 30px; text-align: center; color: var(--brown);">
              No articles match this criteria.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>

    @if($filteredArticles->hasPages())
      <div style="padding: 16px 24px; border-top: 1px solid var(--line);">
        {{ $filteredArticles->links() }}
      </div>
    @endif
  </div>
@endif

<!-- Edit Category Modal -->
<div id="editCategoryModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
  <div style="background: #ffffff; width: 100%; max-width: 520px; border-radius: var(--radius); padding: 28px; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--line); padding-bottom: 12px; margin-bottom: 18px;">
      <h3 id="editCategoryModalTitle" style="font-size: 16px; font-weight: 800; color: var(--deep); margin: 0;">Edit Category</h3>
      <button type="button" onclick="closeEditCategoryModal()" style="background: none; border: 0; font-size: 24px; color: var(--brown); cursor: pointer;">&times;</button>
    </div>

    <form id="editCategoryForm" method="POST">
      @csrf
      @method('PUT')

      <div style="margin-bottom: 14px;">
        <label class="form-label">Category Name (Hindi) *</label>
        <input type="text" name="name_hi" id="edit_cat_name_hi" required class="form-input" style="width: 100%; font-size: 14px; font-weight: 700;">
      </div>

      <div style="margin-bottom: 14px;">
        <label class="form-label">Category Name (English) *</label>
        <input type="text" name="name_en" id="edit_cat_name_en" required class="form-input" style="width: 100%; font-size: 13px;">
      </div>

      <div style="margin-bottom: 14px;">
        <label class="form-label">URL Slug</label>
        <input type="text" name="slug" id="edit_cat_slug" required class="form-input" style="width: 100%; font-family: monospace; font-size: 12px;">
      </div>

      <div style="margin-bottom: 14px;">
        <label class="form-label">Channel Color Theme</label>
        <input type="color" name="color" id="edit_cat_color" style="width: 50px; height: 36px; padding: 2px; border: 1px solid var(--line); border-radius: 6px; cursor: pointer; background: #fff;">
      </div>

      <div style="margin-bottom: 14px;">
        <label class="form-label">Brief Description</label>
        <textarea name="description" id="edit_cat_desc" rows="2" class="form-input" style="width: 100%; font-size: 12px;"></textarea>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px;">
        <div>
          <label class="form-label">Sort Order</label>
          <input type="number" name="sort_order" id="edit_cat_order" class="form-input" style="width: 100%;">
        </div>
        <div style="display: flex; align-items: center; margin-top: 22px;">
          <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px; font-weight: 700; color: var(--deep);">
            <input type="checkbox" name="is_active" id="edit_cat_active" value="1" style="width: 16px; height: 16px; accent-color: var(--crimson);">
            <span>Active in Nav</span>
          </label>
        </div>
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 8px;">
        <button type="button" onclick="closeEditCategoryModal()" class="action-btn" style="background: var(--sand); color: var(--deep);">Cancel</button>
        <button type="submit" class="action-btn primary" style="font-weight: 800;">UPDATE CATEGORY &rarr;</button>
      </div>
    </form>
  </div>
</div>

<!-- Delete Category Modal -->
<div id="deleteCategoryModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
  <div style="background: #ffffff; width: 100%; max-width: 480px; border-radius: var(--radius); padding: 28px; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--line); padding-bottom: 12px; margin-bottom: 16px;">
      <h3 style="font-size: 16px; font-weight: 800; color: #dc2626; margin: 0;">Delete Category</h3>
      <button type="button" onclick="closeDeleteCategoryModal()" style="background: none; border: 0; font-size: 24px; color: var(--brown); cursor: pointer;">&times;</button>
    </div>

    <form id="deleteCategoryForm" method="POST">
      @csrf
      @method('DELETE')

      <p style="font-size: 13px; color: var(--deep); line-height: 1.5; margin-bottom: 16px;">
        Are you sure you want to delete category <strong id="deleteCatName"></strong>?
      </p>

      <div class="form-group" style="margin-bottom: 20px;">
        <label class="form-label">Reassign existing articles to:</label>
        <select name="reassign_to" class="form-input" style="width: 100%;">
          @foreach($categories as $c)
            <option value="{{ $c->slug }}">{{ $c->name_hi }} ({{ $c->name_en }})</option>
          @endforeach
        </select>
        <span style="font-size: 11px; color: var(--brown); display: block; margin-top: 4px;">Articles will not be deleted, only their category pointer will update.</span>
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 8px;">
        <button type="button" onclick="closeDeleteCategoryModal()" class="action-btn" style="background: var(--sand); color: var(--deep);">Cancel</button>
        <button type="submit" class="action-btn" style="background: #dc2626; color: #fff; font-weight: 800;">PERMANENTLY DELETE &rarr;</button>
      </div>
    </form>
  </div>
</div>

<script>
  function editCategoryModal(category) {
    document.getElementById('editCategoryModalTitle').textContent = 'Edit: ' + category.name_en;
    document.getElementById('editCategoryForm').action = '/admin/categories/' + category.id;
    document.getElementById('edit_cat_name_hi').value = category.name_hi || '';
    document.getElementById('edit_cat_name_en').value = category.name_en || '';
    document.getElementById('edit_cat_slug').value = category.slug || '';
    document.getElementById('edit_cat_color').value = category.color || '#8f1719';
    document.getElementById('edit_cat_desc').value = category.description || '';
    document.getElementById('edit_cat_order').value = category.sort_order || 1;
    document.getElementById('edit_cat_active').checked = !!category.is_active;
    document.getElementById('editCategoryModal').style.display = 'flex';
  }

  function closeEditCategoryModal() {
    document.getElementById('editCategoryModal').style.display = 'none';
  }

  function confirmDeleteCategory(category) {
    document.getElementById('deleteCatName').textContent = category.name_en + ' (' + category.name_hi + ')';
    document.getElementById('deleteCategoryForm').action = '/admin/categories/' + category.id;
    document.getElementById('deleteCategoryModal').style.display = 'flex';
  }

  function closeDeleteCategoryModal() {
    document.getElementById('deleteCategoryModal').style.display = 'none';
  }

  function prefillRenameTag(tag) {
    document.getElementById('rename_old_tag').value = tag;
    document.getElementById('rename_old_tag').focus();
    window.scrollTo({ top: 300, behavior: 'smooth' });
  }
</script>
@endsection
