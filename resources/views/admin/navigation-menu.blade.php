@extends('layouts.admin')

@section('title', 'Navigation Menu Editor | CMS Admin')

@section('styles')
<style>
  .menu-editor-card {
    background: #ffffff;
    border: 1px solid var(--line, rgba(89, 58, 40, 0.14));
    border-radius: 12px;
    padding: 26px;
    margin-bottom: 24px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.02);
  }
  .menu-header-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 2px solid rgba(143, 23, 25, 0.85);
  }
  .menu-item-row {
    background: #faf7f2;
    border: 1px solid rgba(185, 133, 80, 0.25);
    border-radius: 8px;
    padding: 14px 18px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    transition: all .2s ease;
  }
  .menu-item-row:hover {
    background: #f4eee2;
    border-color: rgba(185, 133, 80, 0.5);
    box-shadow: 0 4px 12px rgba(0,0,0,0.04);
  }
  .menu-item-row.is-child {
    margin-left: 36px;
    background: #fff;
    border-left: 4px solid var(--sand, #b98550);
  }
  .menu-item-main {
    display: flex;
    align-items: center;
    gap: 14px;
    flex: 1;
    min-width: 0;
  }
  .order-handle {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    background: rgba(185, 133, 80, 0.12);
    color: var(--sand, #b98550);
    display: grid;
    place-items: center;
    font-size: 11px;
    font-weight: 700;
    flex-shrink: 0;
  }
  .menu-titles {
    display: flex;
    flex-direction: column;
    min-width: 0;
  }
  .menu-title-en {
    font-size: 14px;
    font-weight: 700;
    color: var(--deep, #16100d);
  }
  .menu-title-hi {
    font-size: 11.5px;
    color: #7a6e65;
    font-family: var(--dev, 'Noto Serif Devanagari', serif);
  }
  .menu-url-pill {
    background: rgba(0,0,0,0.05);
    padding: 4px 10px;
    border-radius: 4px;
    font-family: monospace;
    font-size: 11px;
    color: #555;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 240px;
  }
  .menu-badge {
    font-size: 9px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 9999px;
    text-transform: uppercase;
    letter-spacing: .08em;
  }
  .badge-active { background: #dcfce7; color: #15803d; }
  .badge-inactive { background: #fee2e2; color: #b91c1c; }
  .badge-dropdown { background: rgba(185, 133, 80, 0.15); color: #8c5825; border: 1px solid rgba(185, 133, 80, 0.3); }

  .menu-actions {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-shrink: 0;
  }
  .action-btn {
    padding: 6px 12px;
    font-size: 11px;
    font-weight: 600;
    border-radius: 5px;
    border: 1px solid transparent;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #fff;
    color: #444;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    transition: .15s;
  }
  .action-btn:hover { background: #f0f0f0; }
  .action-btn.edit:hover { background: #e0f2fe; color: #0369a1; border-color: #bae6fd; }
  .action-btn.delete:hover { background: #fee2e2; color: #b91c1c; border-color: #fecaca; }

  /* Modals */
  .modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.65);
    backdrop-filter: blur(4px);
    display: none;
    place-items: center;
    z-index: 2000;
    padding: 16px;
  }
  .modal-overlay.open { display: grid; }
  .modal-box {
    background: #fff;
    width: 100%;
    max-width: 520px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    animation: modalIn .25s ease-out;
  }
  @keyframes modalIn {
    from { opacity: 0; transform: translateY(12px) scale(0.97); }
    to { opacity: 1; transform: translateY(0) scale(1); }
  }
  .modal-head {
    background: #1f1712;
    color: #fff;
    padding: 18px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .modal-body { padding: 24px; max-height: 80vh; overflow-y: auto; }
  .modal-close {
    background: none;
    border: none;
    color: rgba(255,255,255,0.7);
    font-size: 22px;
    cursor: pointer;
  }
  .modal-close:hover { color: #fff; }

  .form-group { margin-bottom: 16px; }
  .form-group label {
    display: block;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: #555;
    margin-bottom: 6px;
  }
  .form-input {
    width: 100%;
    border: 1px solid #d4c8be;
    border-radius: 6px;
    padding: 9px 12px;
    font-size: 13px;
    outline: none;
  }
  .form-input:focus { border-color: var(--crimson, #8f1719); box-shadow: 0 0 0 3px rgba(143,23,25,0.1); }
  .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
</style>
@endsection

@section('content')
<div class="content-body">
  <div class="page-title-wrap">
    <div>
      <h1 class="page-title">Top Navbar & Navigation Menu Editor</h1>
      <p class="page-subtitle">Configure, reorder, and add buttons, dropdown links, or custom URLs for the website header.</p>
    </div>
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
      <form action="{{ route('admin.resetNavigationMenu') }}" method="POST" onsubmit="return confirm('Reset all navigation items to default pilgrimage structure?');">
        @csrf
        <button type="submit" class="admin-btn secondary" style="background:#fff; border:1px solid #d4c8be; color:#666; font-size:12px; padding:10px 16px;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
          Reset to Defaults
        </button>
      </form>
      <button type="button" class="admin-btn primary" onclick="openCreateModal()" style="font-size:12px; padding:10px 18px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        Add Menu Item
      </button>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success" style="background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46; padding:14px 18px; border-radius:8px; margin-bottom:20px; font-weight:600; font-size:13px;">
      {{ session('success') }}
    </div>
  @endif

  <div class="menu-editor-card">
    <div class="menu-header-bar">
      <div>
        <h2 style="font-size:16px; font-weight:700; color:var(--deep,#16100d); margin:0;">Header Navigation Tree</h2>
        <span style="font-size:12px; color:#777;">Top-level items show directly in the navbar; sub-items appear inside hover dropdowns.</span>
      </div>
      <div style="font-size:12px; color:#888;">
        Total Active Items: <strong>{{ $menuTree->sum(fn($i) => 1 + $i->children->count()) }}</strong>
      </div>
    </div>

    @forelse($menuTree as $item)
      <!-- Parent Item -->
      <div class="menu-item-row">
        <div class="menu-item-main">
          <div class="order-handle" title="Sort Order #{{ $item->sort_order }}">{{ $item->sort_order }}</div>
          <div class="menu-titles">
            <span class="menu-title-en">
              {{ $item->label_en }}
              @if($item->children->count() > 0)
                <span class="menu-badge badge-dropdown" style="margin-left:6px;">{{ $item->children->count() }} Sub-items</span>
              @endif
            </span>
            @if($item->label_hi)
              <span class="menu-title-hi">{{ $item->label_hi }}</span>
            @endif
          </div>
          <span class="menu-url-pill" title="{{ $item->url }}">{{ $item->url }}</span>
          @if($item->target === '_blank')
            <span class="menu-badge" style="background:#e0e7ff; color:#3730a3;" title="Opens in new browser tab">New Tab</span>
          @endif
          <span class="menu-badge {{ $item->is_active ? 'badge-active' : 'badge-inactive' }}">
            {{ $item->is_active ? 'Active' : 'Disabled' }}
          </span>
        </div>

        <div class="menu-actions">
          <button type="button" class="action-btn" onclick="openCreateModal({{ $item->id }}, '{{ addslashes($item->label_en) }}')" title="Add Dropdown Sub-item">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            + Sub-item
          </button>
          <button type="button" class="action-btn edit" onclick="openEditModal({{ json_encode($item) }})" title="Edit Menu Item">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
            Edit
          </button>
          <form action="{{ route('admin.deleteNavigationMenuItem', $item->id) }}" method="POST" onsubmit="return confirm('Delete this menu item and its dropdown sub-items?');" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="action-btn delete" title="Delete">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
            </button>
          </form>
        </div>
      </div>

      <!-- Child Items (Dropdown sub-items) -->
      @foreach($item->children as $child)
        <div class="menu-item-row is-child">
          <div class="menu-item-main">
            <div class="order-handle" title="Sort Order #{{ $child->sort_order }}">{{ $child->sort_order }}</div>
            <div class="menu-titles">
              <span class="menu-title-en">↳ {{ $child->label_en }}</span>
              @if($child->label_hi)
                <span class="menu-title-hi">{{ $child->label_hi }}</span>
              @endif
            </div>
            <span class="menu-url-pill" title="{{ $child->url }}">{{ $child->url }}</span>
            @if($child->target === '_blank')
              <span class="menu-badge" style="background:#e0e7ff; color:#3730a3;">New Tab</span>
            @endif
            <span class="menu-badge {{ $child->is_active ? 'badge-active' : 'badge-inactive' }}">
              {{ $child->is_active ? 'Active' : 'Disabled' }}
            </span>
          </div>

          <div class="menu-actions">
            <button type="button" class="action-btn edit" onclick="openEditModal({{ json_encode($child) }})" title="Edit Sub-item">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
              Edit
            </button>
            <form action="{{ route('admin.deleteNavigationMenuItem', $child->id) }}" method="POST" onsubmit="return confirm('Delete this sub-item?');" style="display:inline;">
              @csrf
              @method('DELETE')
              <button type="submit" class="action-btn delete" title="Delete">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
              </button>
            </form>
          </div>
        </div>
      @endforeach
    @empty
      <div style="text-align:center; padding:50px 20px; color:#888;">
        <p>No navigation menu items configured yet.</p>
        <button type="button" class="admin-btn primary" onclick="openCreateModal()">Create First Menu Item</button>
      </div>
    @endforelse
  </div>
</div>

<!-- Modal: Create / Add Item -->
<div class="modal-overlay" id="createModal" onclick="closeModalOnBackdrop(event, 'createModal')">
  <div class="modal-box">
    <div class="modal-head">
      <h3 style="margin:0; font-size:16px;" id="createModalTitle">Add Navigation Menu Item</h3>
      <button type="button" class="modal-close" onclick="closeModal('createModal')">&times;</button>
    </div>
    <form action="{{ route('admin.storeNavigationMenuItem') }}" method="POST">
      @csrf
      <div class="modal-body">
        <div class="form-row">
          <div class="form-group">
            <label>English Label *</label>
            <input type="text" name="label_en" class="form-input" placeholder="e.g. Pooja & Aarti" required>
          </div>
          <div class="form-group">
            <label>Hindi Label</label>
            <input type="text" name="label_hi" class="form-input" placeholder="e.g. पूजा व आरती">
          </div>
        </div>

        <div class="form-group">
          <label>Link Destination URL or Path *</label>
          <input type="text" name="url" class="form-input" placeholder="e.g. /about, /#pooja-section or https://..." required>
          <span style="font-size:10.5px; color:#888; display:block; margin-top:3px;">Use relative paths like <code>/seva</code> or <code>/#pooja-section</code>, or absolute URLs.</span>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Parent Menu Item</label>
            <select name="parent_id" id="createParentSelect" class="form-input">
              <option value="">-- None (Top Level Menu Bar) --</option>
              @foreach($parentOptions as $parent)
                <option value="{{ $parent->id }}">{{ $parent->label_en }} ({{ $parent->label_hi ?? 'Main' }})</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Open Target</label>
            <select name="target" class="form-input">
              <option value="_self">Same Window (_self)</option>
              <option value="_blank">New Tab (_blank)</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Sort Order Position</label>
            <input type="number" name="sort_order" class="form-input" value="1" min="1">
          </div>
          <div class="form-group" style="display:flex; align-items:center; gap:8px; margin-top:24px;">
            <input type="checkbox" name="is_active" id="createIsActive" value="1" checked style="width:18px; height:18px; accent-color:var(--crimson);">
            <label for="createIsActive" style="margin:0; text-transform:none; font-size:12px; cursor:pointer;">Show in Navigation (Active)</label>
          </div>
        </div>
      </div>
      <div style="background:#f9f9f9; padding:16px 24px; display:flex; justify-content:flex-end; gap:10px; border-top:1px solid #eee;">
        <button type="button" class="admin-btn secondary" onclick="closeModal('createModal')" style="padding:8px 16px;">Cancel</button>
        <button type="submit" class="admin-btn primary" style="padding:8px 20px;">Save Menu Item</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal: Edit Item -->
<div class="modal-overlay" id="editModal" onclick="closeModalOnBackdrop(event, 'editModal')">
  <div class="modal-box">
    <div class="modal-head">
      <h3 style="margin:0; font-size:16px;">Edit Navigation Menu Item</h3>
      <button type="button" class="modal-close" onclick="closeModal('editModal')">&times;</button>
    </div>
    <form id="editForm" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-body">
        <div class="form-row">
          <div class="form-group">
            <label>English Label *</label>
            <input type="text" name="label_en" id="editLabelEn" class="form-input" required>
          </div>
          <div class="form-group">
            <label>Hindi Label</label>
            <input type="text" name="label_hi" id="editLabelHi" class="form-input">
          </div>
        </div>

        <div class="form-group">
          <label>Link Destination URL or Path *</label>
          <input type="text" name="url" id="editUrl" class="form-input" required>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Parent Menu Item</label>
            <select name="parent_id" id="editParentId" class="form-input">
              <option value="">-- None (Top Level Menu Bar) --</option>
              @foreach($parentOptions as $parent)
                <option value="{{ $parent->id }}">{{ $parent->label_en }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Open Target</label>
            <select name="target" id="editTarget" class="form-input">
              <option value="_self">Same Window (_self)</option>
              <option value="_blank">New Tab (_blank)</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Sort Order Position</label>
            <input type="number" name="sort_order" id="editSortOrder" class="form-input" required min="1">
          </div>
          <div class="form-group" style="display:flex; align-items:center; gap:8px; margin-top:24px;">
            <input type="checkbox" name="is_active" id="editIsActive" value="1" style="width:18px; height:18px; accent-color:var(--crimson);">
            <label for="editIsActive" style="margin:0; text-transform:none; font-size:12px; cursor:pointer;">Show in Navigation (Active)</label>
          </div>
        </div>
      </div>
      <div style="background:#f9f9f9; padding:16px 24px; display:flex; justify-content:flex-end; gap:10px; border-top:1px solid #eee;">
        <button type="button" class="admin-btn secondary" onclick="closeModal('editModal')" style="padding:8px 16px;">Cancel</button>
        <button type="submit" class="admin-btn primary" style="padding:8px 20px;">Update Changes</button>
      </div>
    </form>
  </div>
</div>

<script>
  function openCreateModal(parentId = null, parentName = '') {
    const parentSelect = document.getElementById('createParentSelect');
    if (parentId && parentSelect) {
      parentSelect.value = parentId;
      document.getElementById('createModalTitle').innerText = 'Add Dropdown Sub-item to "' + parentName + '"';
    } else {
      parentSelect.value = '';
      document.getElementById('createModalTitle').innerText = 'Add Navigation Menu Item';
    }
    document.getElementById('createModal').classList.add('open');
  }

  function openEditModal(item) {
    document.getElementById('editForm').action = '/admin/navigation-menu/' + item.id;
    document.getElementById('editLabelEn').value = item.label_en || '';
    document.getElementById('editLabelHi').value = item.label_hi || '';
    document.getElementById('editUrl').value = item.url || '';
    document.getElementById('editParentId').value = item.parent_id || '';
    document.getElementById('editTarget').value = item.target || '_self';
    document.getElementById('editSortOrder').value = item.sort_order || 1;
    document.getElementById('editIsActive').checked = Boolean(item.is_active);

    document.getElementById('editModal').classList.add('open');
  }

  function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('open');
  }

  function closeModalOnBackdrop(event, modalId) {
    if (event.target === document.getElementById(modalId)) {
      closeModal(modalId);
    }
  }
</script>
@endsection
