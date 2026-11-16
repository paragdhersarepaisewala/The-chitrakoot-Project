@extends('layouts.admin')

@section('title', 'Seva Projects CMS | CMS Admin')
@section('header_title', 'Seva Projects & Initiatives CMS')
@section('header_subtitle', 'Manage Gaushala, Gurukul, Bhandara, Annakshetra, and Environmental Welfare Initiatives')

@section('header_actions')
  <a href="{{ route('admin.causes') }}" class="action-btn" style="background: rgba(217, 93, 15, 0.12); color: #D95D0F; border: 1.5px solid rgba(217, 93, 15, 0.3); font-weight: 800; font-size: 11px; padding: 7px 14px;">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
    DONATION CAMPAIGNS &rarr;
  </a>
  <button type="button" onclick="openCreateCauseModal()" class="action-btn" style="background: #166534; color: #fff; font-weight: 800; font-size: 11px; padding: 8px 16px;">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
    + CREATE DONATION CAMPAIGN
  </button>
  <button type="button" onclick="openCreateSevaModal()" class="action-btn primary" style="font-weight: 800; letter-spacing: 0.05em; padding: 8px 16px;">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
    ADD NEW SEVA PROJECT &rarr;
  </button>
@endsection

@section('content')
<!-- Custom Page Styles -->
<style>
  .seva-stat-card {
    background: #ffffff;
    border: 1px solid var(--line);
    border-radius: 16px;
    padding: 20px 22px;
    box-shadow: 0 4px 18px rgba(43, 35, 29, 0.04);
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all .25s var(--ease);
  }
  .seva-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(43, 35, 29, 0.08);
    border-color: rgba(185, 133, 80, 0.4);
  }
  .seva-stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: grid;
    place-items: center;
    flex-shrink: 0;
  }
  .filter-pill {
    padding: 6px 14px;
    border-radius: 9999px;
    font-size: 12px;
    font-weight: 700;
    border: 1px solid var(--line);
    background: #fff;
    color: var(--ink);
    cursor: pointer;
    transition: all .2s var(--ease);
  }
  .filter-pill.active, .filter-pill:hover {
    background: var(--deep);
    color: #fff;
    border-color: var(--deep);
  }
  .seva-row:hover {
    background: rgba(250, 244, 237, 0.6) !important;
  }
</style>

<!-- Section Switcher Tabs -->
<div style="display: flex; gap: 10px; margin-bottom: 22px; flex-wrap: wrap;">
  <a href="{{ route('admin.sevas') }}" class="filter-pill active" style="font-size: 12px; padding: 8px 18px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
    <span>1. Seva Projects & Initiatives</span>
    <span style="background: rgba(255,255,255,0.25); color: #fff; font-size: 10px; padding: 2px 7px; border-radius: 9999px;">{{ $sevas->count() }}</span>
  </a>

  <a href="{{ route('admin.causes') }}" class="filter-pill" style="font-size: 12px; padding: 8px 18px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; background: #fff;">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
    <span>2. Donation Campaigns & Temple Funds</span>
    <span style="background: rgba(0,0,0,0.06); color: var(--deep); font-size: 10px; padding: 2px 7px; border-radius: 9999px;">{{ $causes->count() }}</span>
  </a>
</div>

<!-- Modern Stats Grid -->
<div class="stats-grid" style="margin-bottom: 26px;">
  <!-- Card 1: Total Seva -->
  <div class="seva-stat-card">
    <div class="seva-stat-icon" style="background: rgba(217, 93, 15, 0.12); color: #D95D0F;">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
    </div>
    <div>
      <div class="stat-label">Total Seva Initiatives</div>
      <div class="stat-val" style="color: #D95D0F;">{{ $sevas->count() }}</div>
      <div class="stat-sub">Spiritual & social welfare projects</div>
    </div>
  </div>

  <!-- Card 2: Published -->
  <div class="seva-stat-card">
    <div class="seva-stat-icon" style="background: rgba(22, 101, 52, 0.12); color: #166534;">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
    </div>
    <div>
      <div class="stat-label">Active & Published</div>
      <div class="stat-val" style="color: #166534;">{{ $sevas->where('is_published', true)->count() }}</div>
      <div class="stat-sub">Live on devotee portal</div>
    </div>
  </div>

  <!-- Card 3: Gaushala -->
  <div class="seva-stat-card">
    <div class="seva-stat-icon" style="background: rgba(45, 106, 79, 0.12); color: #2D6A4F;">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M19 5h-2V3H7v2H5c-1.1 0-2 .9-2 2v1c0 2.55 1.92 4.63 4.39 4.94A5.01 5.01 0 0 0 11 15.9V19H8v2h8v-2h-3v-3.1c1.98-.44 3.53-1.93 3.95-3.86C19.26 11.66 21 9.68 21 7.2V7c0-1.1-.9-2-2-2zM5 8V7h2v3.82C5.84 10.4 5 9.3 5 8zm14 0c0 1.3-.84 2.4-2 2.82V7h2v1z"/></svg>
    </div>
    <div>
      <div class="stat-label">Gaushala & Cow Care</div>
      <div class="stat-val" style="color: #2D6A4F;">{{ $sevas->where('category', 'gaushala')->count() }}</div>
      <div class="stat-sub">Nandi & Kamadhenu shelters</div>
    </div>
  </div>

  <!-- Card 4: Gurukul & Education -->
  <div class="seva-stat-card">
    <div class="seva-stat-icon" style="background: rgba(180, 83, 9, 0.12); color: #B45309;">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
    </div>
    <div>
      <div class="stat-label">Gurukul & Education</div>
      <div class="stat-val" style="color: #B45309;">{{ $sevas->where('category', 'education')->count() }}</div>
      <div class="stat-sub">Vedic & Sanskrit academics</div>
    </div>
  </div>
</div>

@if(session('success'))
  <div style="background: rgba(46,125,50,.12); color: #166534; padding: 14px 20px; border-radius: 12px; margin-bottom: 24px; border-left: 4px solid #166534; font-weight: 700; font-size: 13px; display: flex; align-items: center; gap: 10px;">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
    <span>{{ session('success') }}</span>
  </div>
@endif

<!-- Toolbar: Category Pills & Instant Search -->
<div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 14px; margin-bottom: 20px;">
  <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 8px;">
    <button type="button" class="filter-pill active" onclick="filterSevas('all', this)">All Initiatives ({{ $sevas->count() }})</button>
    <button type="button" class="filter-pill" onclick="filterSevas('gaushala', this)">Gaushala</button>
    <button type="button" class="filter-pill" onclick="filterSevas('education', this)">Education & Gurukul</button>
    <button type="button" class="filter-pill" onclick="filterSevas('annakshetra', this)">Annakshetra</button>
    <button type="button" class="filter-pill" onclick="filterSevas('culture', this)">Culture & Heritage</button>
    <button type="button" class="filter-pill" onclick="filterSevas('environment', this)">Environment & Ecology</button>
  </div>

  <div style="position: relative; width: 100%; max-width: 320px;">
    <input type="text" id="sevaSearchInput" onkeyup="searchSevas(this.value)" placeholder="Search initiatives or causes..." class="form-input" style="padding-left: 36px; padding-top: 8px; padding-bottom: 8px; font-size: 12.5px; border-radius: 9999px;">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: var(--brown); opacity: 0.6;"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
  </div>
</div>

<!-- Full-Width Seva Directory Table Card -->
<div class="cms-table-card">
  <div class="card-header" style="display: flex; align-items: center; justify-content: space-between; padding: 18px 24px; border-bottom: 1px solid var(--line); background: #FAF7F2;">
    <div style="display: flex; align-items: center; gap: 10px;">
      <span style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 8px; background: rgba(217, 93, 15, 0.12); color: #D95D0F;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
      </span>
      <div>
        <h2 style="font-size: 15px; font-weight: 800; color: var(--deep); margin: 0;">Existing Seva Initiatives Directory</h2>
        <span style="font-size: 11px; color: var(--brown);">Published social welfare & spiritual projects in Chitrakoot</span>
      </div>
    </div>
    <span id="sevaResultsBadge" style="font-size: 11.5px; font-weight: 700; color: var(--brown); background: #fff; padding: 4px 12px; border-radius: 9999px; border: 1px solid var(--line);">
      Showing {{ $sevas->count() }} projects
    </span>
  </div>

  <table class="cms-table" style="width: 100%; border-collapse: collapse;">
    <thead>
      <tr>
        <th style="width: 34%;">Project Details</th>
        <th style="width: 15%;">Category</th>
        <th style="width: 25%;">Campaign Goal & Raised</th>
        <th style="width: 12%;">Status</th>
        <th style="width: 14%; text-align: right;">Actions</th>
      </tr>
    </thead>
    <tbody id="sevasTableBody">
      @forelse($sevas as $s)
        <tr class="seva-row" data-name="{{ strtolower($s->title_hi . ' ' . $s->title_en . ' ' . $s->slug . ' ' . ($s->cause->title ?? '')) }}" data-category="{{ strtolower($s->category) }}">
          <td>
            <div style="display: flex; align-items: center; gap: 14px;">
              <div style="position: relative; width: 56px; height: 56px; border-radius: 10px; overflow: hidden; border: 1.5px solid var(--line); flex-shrink: 0; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                <img src="{{ $s->featured_image ?: '/images/chitrakoot-hero.webp' }}" onerror="this.src='/images/chitrakoot-hero.webp'" style="width: 100%; height: 100%; object-fit: cover;" alt="">
              </div>
              <div>
                <div style="display: flex; align-items: center; gap: 6px;">
                  <strong style="color: var(--deep); font-size: 14.5px; font-family: var(--dev); font-weight: 700; line-height: 1.3;">
                    {{ $s->title_hi }}
                  </strong>
                  @if($s->is_featured)
                    <span style="font-size: 10px; background: #fef3c7; color: #92400e; font-weight: 800; padding: 1px 6px; border-radius: 4px; border: 1px solid #fde68a;">⭐ FEATURED</span>
                  @endif
                </div>
                <span style="font-size: 12px; color: var(--brown); display: block; margin-top: 1px;">
                  {{ $s->title_en }}
                </span>
                <div style="display: flex; flex-wrap: wrap; gap: 6px; margin-top: 4px;">
                  <span style="font-size: 10px; color: #7A4B29; background: #FAF4ED; padding: 1px 7px; border-radius: 4px; font-family: monospace;">
                    /seva/{{ $s->slug }}
                  </span>
                  @if($s->cause)
                    <span style="font-size: 10px; color: #166534; background: #dcfce7; padding: 1px 8px; border-radius: 4px; font-weight: 700;">
                      Cause #{{ $s->cause->id }}: {{ $s->cause->title }}
                    </span>
                  @endif
                </div>
              </div>
            </div>
          </td>
          <td>
            @php
              $categoryStyles = [
                'gaushala' => ['bg' => '#dcfce7', 'color' => '#166534', 'name' => 'Gaushala'],
                'education' => ['bg' => '#fef3c7', 'color' => '#92400e', 'name' => 'Gurukul & Education'],
                'annakshetra' => ['bg' => '#ffedd5', 'color' => '#9a3412', 'name' => 'Annakshetra & Meals'],
                'culture' => ['bg' => '#f3e8ff', 'color' => '#6b21a8', 'name' => 'Culture & Heritage'],
                'environment' => ['bg' => '#d1fae5', 'color' => '#065f46', 'name' => 'Environment & Nature'],
              ];
              $catStyle = $categoryStyles[strtolower($s->category)] ?? ['bg' => '#FAF4ED', 'color' => '#B45309', 'name' => ucfirst($s->category)];
            @endphp
            <span class="badge" style="background: {{ $catStyle['bg'] }}; color: {{ $catStyle['color'] }}; font-weight: 700; font-size: 11px;">
              {{ $catStyle['name'] }}
            </span>
          </td>
          <td>
            <div>
              <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 4px;">
                <span style="font-size: 12px; font-weight: 800; color: #166534;">₹{{ number_format($s->raised_amount, 0) }}</span>
                <span style="font-size: 11px; color: #7A4B29;">Goal: ₹{{ number_format($s->target_amount, 0) }}</span>
              </div>
              <div style="height: 6px; background: rgba(180, 83, 9, 0.12); border-radius: 9999px; overflow: hidden; margin-bottom: 4px;">
                <div style="width: {{ $s->progress_percent }}%; height: 100%; background: linear-gradient(90deg, #D95D0F, #16a34a); border-radius: 9999px;"></div>
              </div>
              <div style="display: flex; justify-content: space-between; font-size: 10.5px; color: #7A4B29;">
                <span>{{ $s->progress_percent }}% Funded</span>
                <span>{{ $s->donors_count }} Donors</span>
              </div>
            </div>
          </td>
          <td>
            @if($s->is_published)
              <span class="badge" style="background: #dcfce7; color: #166534; border: 1px solid rgba(22, 101, 52, 0.25);">
                <span style="width: 6px; height: 6px; border-radius: 9999px; background: #16a34a;"></span>
                PUBLISHED
              </span>
            @else
              <span class="badge" style="background: #fee2e2; color: #991b1b; border: 1px solid rgba(153, 27, 27, 0.2);">
                DRAFT
              </span>
            @endif
          </td>
          <td style="text-align: right;">
            <div style="display: inline-flex; align-items: center; gap: 6px;">
              <button type="button" 
                      onclick='openEditSevaModal(@json($s))' 
                      class="action-btn" 
                      style="background: #FAF4ED; color: #B45309; border-color: rgba(180, 83, 9, 0.25); padding: 5px 12px; font-size: 11px;"
                      title="Edit Seva Project">
                Edit
              </button>
              <a href="{{ route('seva.show', $s->slug) }}" target="_blank" class="action-btn" style="padding: 5px 8px; font-size: 11px;" title="View public page">
                &nearr;
              </a>
              <form action="{{ url('/admin/sevas/' . $s->id) }}" method="POST" onsubmit="return confirm('Delete this seva project permanently?')" style="margin: 0; display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="action-btn" style="background: #fee2e2; color: #991b1b; border-color: rgba(153, 27, 27, 0.2); padding: 5px 8px; font-size: 11px;" title="Delete Seva">
                  &times;
                </button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="5" style="padding: 40px; text-align: center; color: var(--brown);">
            No seva projects recorded yet. Click "Add New Seva Project" above to create the first initiative.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<!-- ========================================================
     UNIVERSAL MODAL: ADD & EDIT SEVA PROJECT
     ======================================================== -->
<div id="sevaProjectModal" style="display: none; position: fixed; inset: 0; background: rgba(22, 16, 13, 0.75); backdrop-filter: blur(8px); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
  <div style="background: #ffffff; width: 100%; max-width: 740px; max-height: 92vh; overflow-y: auto; border-radius: 20px; box-shadow: 0 25px 60px rgba(0,0,0,0.35); border: 1.5px solid var(--line);">
    
    <!-- Modal Header -->
    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--line); padding: 20px 28px; background: #FAF7F2; border-top-left-radius: 20px; border-top-right-radius: 20px;">
      <div style="display: flex; align-items: center; gap: 10px;">
        <span style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; background: rgba(45, 106, 79, 0.12); color: #2D6A4F;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
        </span>
        <div>
          <h2 id="sevaModalTitle" style="font-size: 16px; font-weight: 800; color: var(--deep); margin: 0; font-family: var(--dev);">Add New Seva Project</h2>
          <span style="font-size: 11px; color: var(--brown);">Register spiritual and social welfare initiatives</span>
        </div>
      </div>
      <button type="button" onclick="closeSevaProjectModal()" style="background: none; border: 0; font-size: 26px; color: var(--brown); cursor: pointer; line-height: 1;">&times;</button>
    </div>

    <!-- Modal Form -->
    <form id="sevaProjectForm" method="POST" style="padding: 24px 28px;">
      @csrf
      <input type="hidden" name="_method" id="seva_form_method" value="POST">

      <!-- Titles (Hindi & English) -->
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
        <div class="form-group" style="margin: 0;">
          <label class="form-label">Title (Hindi) *</label>
          <input type="text" name="title_hi" id="seva_title_hi" required placeholder="e.g. Kamadgiri Gaushala Seva Project" class="form-input" style="font-weight: 700; font-size: 14px;">
        </div>
        <div class="form-group" style="margin: 0;">
          <label class="form-label">Title (English) *</label>
          <input type="text" name="title_en" id="seva_title_en" required placeholder="e.g. Gaushala & Sacred Cow Protection" class="form-input" style="font-size: 13px;">
        </div>
      </div>

      <!-- Category & Cause Link -->
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
        <div class="form-group" style="margin: 0;">
          <label class="form-label">Category *</label>
          <select name="category" id="seva_category" required class="form-input">
            <option value="gaushala">Gaushala</option>
            <option value="education">Education & Gurukul</option>
            <option value="annakshetra">Annakshetra & Bhandara</option>
            <option value="culture">Culture & Festivals</option>
            <option value="environment">Environment & Ecology</option>
          </select>
        </div>
        <div class="form-group" style="margin: 0;">
          <label class="form-label">Link Donation Cause</label>
          <select name="cause_id" id="seva_cause_id" class="form-input">
            <option value="">-- Auto-create / Link Cause --</option>
            @foreach($causes as $cause)
              <option value="{{ $cause->id }}">{{ $cause->title }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <!-- Campaign Target & Featured Toggle -->
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px;">
        <div class="form-group" style="margin: 0;">
          <label class="form-label">Donation Campaign Target (₹) *</label>
          <input type="number" name="target_amount" id="seva_target_amount" value="500000" min="1000" step="500" required placeholder="500000" class="form-input">
        </div>
        <div class="form-group" style="margin: 0; display: flex; align-items: center; padding-top: 22px;">
          <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 12.5px; font-weight: 700; color: var(--deep);">
            <input type="checkbox" name="is_featured" id="seva_is_featured" value="1" style="width: 17px; height: 17px; accent-color: #D95D0F;">
            <span>⭐ Feature on Public Homepage</span>
          </label>
        </div>
      </div>

      <!-- Summary / Tagline -->
      <div class="form-group" style="margin-bottom: 16px;">
        <label class="form-label">Summary / Tagline (Hindi) *</label>
        <textarea name="summary_hi" id="seva_summary_hi" rows="2" required placeholder="Brief summary displayed on cards and homepage..." class="form-input" style="font-size: 12.5px; line-height: 1.5;"></textarea>
      </div>

      <!-- Full Narrative Description -->
      <div class="form-group" style="margin-bottom: 16px;">
        <label class="form-label">Full Description (Hindi) *</label>
        <textarea name="description_hi" id="seva_description_hi" rows="4" required placeholder="Detailed description of seva objectives, activities, and spiritual impact..." class="form-input" style="font-size: 12.5px; line-height: 1.6;"></textarea>
      </div>

      <!-- Featured Image with Live Preview -->
      <div style="margin-bottom: 20px; background: #FAF7F2; border: 1px solid var(--line); border-radius: 12px; padding: 14px;">
        <label class="form-label" style="margin-bottom: 8px;">Featured Project Image *</label>
        <div style="display: flex; gap: 14px; align-items: center;">
          <div style="width: 56px; height: 56px; border-radius: 10px; overflow: hidden; border: 1px solid var(--line); background: #fff; flex-shrink: 0;">
            <img id="seva_img_preview" src="/images/chitrakoot-hero.webp" onerror="this.src='/images/chitrakoot-hero.webp'" style="width: 100%; height: 100%; object-fit: cover;" alt="">
          </div>
          <div style="flex: 1;">
            <div style="display: flex; gap: 8px;">
              <input type="text" name="featured_image" id="seva_featured_img" oninput="updateSevaImgPreview(this.value)" required placeholder="/uploads/... or https://..." class="form-input" style="font-size: 12px;">
              <button type="button" onclick="openUniversalMediaPicker({ targetInput: '#seva_featured_img', onSelect: (url) => updateSevaImgPreview(url) })" class="action-btn teal" style="padding: 7px 14px; font-size: 11px;">
                BROWSE GALLERY
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Publish Checkbox -->
      <div style="margin-bottom: 24px;">
        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 13px; font-weight: 700; color: var(--deep);">
          <input type="checkbox" name="is_published" id="seva_published" value="1" checked style="width: 18px; height: 18px; accent-color: #D95D0F;">
          <span>Publish Immediately on Public Website</span>
        </label>
      </div>

      <!-- Modal Footer -->
      <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid var(--line); padding-top: 16px;">
        <button type="button" onclick="closeSevaProjectModal()" class="action-btn" style="background: var(--sand); color: var(--deep); padding: 9px 18px;">Cancel</button>
        <button type="submit" id="sevaSubmitBtn" class="action-btn primary" style="font-weight: 800; padding: 9px 24px;">SAVE SEVA CAMPAIGN &rarr;</button>
      </div>
    </form>

  </div>
</div>

<!-- ========================================================
     MODAL: CREATE DONATION CAMPAIGN (CAUSE)
     ======================================================== -->
<div id="createCauseModal" style="display: none; position: fixed; inset: 0; background: rgba(22, 16, 13, 0.75); backdrop-filter: blur(8px); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
  <div style="background: #ffffff; width: 100%; max-width: 640px; max-height: 92vh; overflow-y: auto; border-radius: 20px; box-shadow: 0 25px 60px rgba(0,0,0,0.35); border: 1.5px solid var(--line);">
    
    <!-- Modal Header -->
    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--line); padding: 20px 28px; background: linear-gradient(to right, #FAF7F2, #FFF7ED); border-top-left-radius: 20px; border-top-right-radius: 20px;">
      <div style="display: flex; align-items: center; gap: 10px;">
        <span style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 10px; background: rgba(22, 101, 52, 0.12); color: #166534;">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
        </span>
        <div>
          <h2 style="font-size: 16px; font-weight: 800; color: var(--deep); margin: 0; font-family: var(--dev);">Create New Donation Campaign</h2>
          <span style="font-size: 11.5px; color: var(--brown);">Launch an appeal accepting devotee contributions through Razorpay</span>
        </div>
      </div>
      <button type="button" onclick="closeCreateCauseModal()" style="background: none; border: 0; font-size: 26px; color: var(--brown); cursor: pointer; line-height: 1;">&times;</button>
    </div>

    <!-- Modal Form -->
    <form action="{{ url('/admin/causes') }}" method="POST" style="padding: 24px 28px;">
      @csrf

      <div class="form-group" style="margin-bottom: 16px;">
        <label class="form-label">Campaign Title (Hindi) *</label>
        <input type="text" name="title" required placeholder="e.g. Gaushala & Kamadgiri Protection Fund" class="form-input" style="font-weight: 700; font-size: 14px;">
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px;">
        <div class="form-group" style="margin: 0;">
          <label class="form-label">Target Goal Amount (₹) *</label>
          <input type="number" name="target_amount" required min="100" step="100" value="500000" class="form-input" style="font-weight: 800; color: #166534;">
        </div>
        <div class="form-group" style="margin: 0;">
          <label class="form-label">Campaign Status</label>
          <select name="status" class="form-input">
            <option value="active" selected>Active</option>
            <option value="paused">Paused</option>
            <option value="completed">Completed</option>
          </select>
        </div>
      </div>

      <div class="form-group" style="margin-bottom: 16px;">
        <label class="form-label">Purpose & Utilization Description (Hindi) *</label>
        <textarea name="description" rows="4" required placeholder="Detailed description of campaign goals and fund utilization..." class="form-input" style="font-size: 12.5px; line-height: 1.5;"></textarea>
      </div>

      <div class="form-group" style="margin-bottom: 22px;">
        <label class="form-label">Banner Image URL *</label>
        <div style="display: flex; gap: 8px;">
          <input type="text" name="image_path" id="cause_modal_img" required value="/uploads/temple-test-photo-1792032209.webp" placeholder="/uploads/..." class="form-input" style="flex: 1; font-size: 12px;">
          <button type="button" onclick="openUniversalMediaPicker({ targetInput: '#cause_modal_img' })" class="action-btn" style="background: #FAF4ED; color: #B45309; border: 1px solid rgba(180,83,9,0.3); font-size: 11px; padding: 7px 12px;">
            PICK IMAGE
          </button>
        </div>
      </div>

      <div style="display: flex; gap: 10px; justify-content: flex-end; border-top: 1px solid var(--line); padding-top: 18px;">
        <button type="button" onclick="closeCreateCauseModal()" class="action-btn" style="padding: 9px 18px; font-size: 12px;">
          CANCEL
        </button>
        <button type="submit" class="action-btn" style="background: #166534; color: #fff; font-weight: 800; font-size: 12px; padding: 10px 24px; box-shadow: 0 4px 14px rgba(22, 101, 52, 0.3);">
          CREATE CAMPAIGN &rarr;
        </button>
      </div>
    </form>
  </div>
</div>

@include('admin.media-picker-modal')

<script>
  function openCreateCauseModal() {
    document.getElementById('createCauseModal').style.display = 'flex';
  }
  function closeCreateCauseModal() {
    document.getElementById('createCauseModal').style.display = 'none';
  }

  function updateSevaImgPreview(url) {
    const preview = document.getElementById('seva_img_preview');
    if (preview) {
      preview.src = url || '/images/chitrakoot-hero.webp';
    }
  }

  function openCreateSevaModal() {
    document.getElementById('sevaModalTitle').textContent = 'Add New Seva Campaign';
    document.getElementById('sevaSubmitBtn').textContent = 'SAVE SEVA CAMPAIGN →';
    document.getElementById('sevaProjectForm').action = '/admin/sevas';
    document.getElementById('seva_form_method').value = 'POST';
    document.getElementById('sevaProjectForm').reset();
    document.getElementById('seva_target_amount').value = '500000';
    document.getElementById('seva_is_featured').checked = false;
    updateSevaImgPreview('/images/chitrakoot-hero.webp');
    document.getElementById('sevaProjectModal').style.display = 'flex';
  }

  function openEditSevaModal(seva) {
    document.getElementById('sevaModalTitle').textContent = 'Edit Seva Campaign: ' + (seva.title_en || seva.title_hi);
    document.getElementById('sevaSubmitBtn').textContent = 'UPDATE SEVA CAMPAIGN →';
    document.getElementById('sevaProjectForm').action = '/admin/sevas/' + seva.id;
    document.getElementById('seva_form_method').value = 'PUT';
    
    document.getElementById('seva_title_hi').value = seva.title_hi || '';
    document.getElementById('seva_title_en').value = seva.title_en || '';
    document.getElementById('seva_category').value = seva.category || 'gaushala';
    document.getElementById('seva_cause_id').value = seva.cause_id || '';
    document.getElementById('seva_target_amount').value = seva.target_amount || '500000';
    document.getElementById('seva_is_featured').checked = !!seva.is_featured;
    document.getElementById('seva_summary_hi').value = seva.summary_hi || '';
    document.getElementById('seva_description_hi').value = seva.description_hi || '';
    document.getElementById('seva_featured_img').value = seva.featured_image || '';
    updateSevaImgPreview(seva.featured_image);
    
    document.getElementById('seva_published').checked = !!seva.is_published;
    document.getElementById('sevaProjectModal').style.display = 'flex';
  }

  function closeSevaProjectModal() {
    document.getElementById('sevaProjectModal').style.display = 'none';
  }

  // Client-Side Live Search
  function searchSevas(query) {
    const q = query.toLowerCase().trim();
    const rows = document.querySelectorAll('#sevasTableBody tr.seva-row');
    let visibleCount = 0;
    rows.forEach(row => {
      const text = row.getAttribute('data-name') || '';
      if (text.includes(q)) {
        row.style.display = '';
        visibleCount++;
      } else {
        row.style.display = 'none';
      }
    });
    updateSevaCountBadge(visibleCount);
  }

  // Category Filter
  function filterSevas(category, btn) {
    document.querySelectorAll('.filter-pill').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const rows = document.querySelectorAll('#sevasTableBody tr.seva-row');
    let visibleCount = 0;
    rows.forEach(row => {
      const rowCat = row.getAttribute('data-category');
      if (category === 'all' || rowCat === category) {
        row.style.display = '';
        visibleCount++;
      } else {
        row.style.display = 'none';
      }
    });
    updateSevaCountBadge(visibleCount);
  }

  function updateSevaCountBadge(count) {
    const badge = document.getElementById('sevaResultsBadge');
    if (badge) {
      badge.textContent = 'Showing ' + count + ' projects';
    }
  }
</script>
@endsection