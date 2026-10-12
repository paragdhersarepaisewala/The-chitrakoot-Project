@extends('layouts.app')

@section('title', 'Chitrakoot Destinations CMS | Admin')

@section('content')

  <section class="section" style="padding-top: 140px; background: #18120e; color: #fff; min-height: 90vh;">
    <div class="container">

      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
          <a href="{{ url('/admin') }}" style="color: var(--sand2); font: 700 11px var(--sans); letter-spacing: .15em; text-transform: uppercase;">← Return to Dashboard</a>
          <h1 style="font: 500 32px var(--dev); margin-top: 8px;">Chitrakoot Sacred Places CMS</h1>
        </div>
      </div>

      @if(session('success'))
        <div style="background: rgba(46,125,50,.2); color: #81c784; padding: 15px; margin-bottom: 25px; border-left: 4px solid #81c784; font: 500 14px var(--sans);">
          {{ session('success') }}
        </div>
      @endif

      <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">

        <!-- Add New Destination Form -->
        <div style="background: rgba(255,255,255,.04); border: 1px solid var(--darkline); padding: 30px; border-radius: 6px;">
          <h3 style="font: 600 20px var(--dev); color: var(--sand2); margin-bottom: 20px;">Add New Sacred Spot</h3>

          <form action="{{ url('/admin/destinations') }}" method="POST">
            @csrf

            <div style="margin-bottom: 15px;">
              <label style="display: block; font: 700 10px var(--sans); letter-spacing: .1em; margin-bottom: 6px;">Name (Hindi)</label>
              <input type="text" name="name_hi" required placeholder="उदा: कामदगिरि परिक्रमा" style="width: 100%; padding: 10px; background: #261e19; border: 1px solid var(--darkline); color: #fff; font: 500 14px var(--dev);">
            </div>

            <div style="margin-bottom: 15px;">
              <label style="display: block; font: 700 10px var(--sans); letter-spacing: .1em; margin-bottom: 6px;">Name (English)</label>
              <input type="text" name="name_en" required placeholder="e.g. Kamadgiri Parikrama" style="width: 100%; padding: 10px; background: #261e19; border: 1px solid var(--darkline); color: #fff; font: 500 13px var(--sans);">
            </div>

            <div style="margin-bottom: 15px;">
              <label style="display: block; font: 700 10px var(--sans); letter-spacing: .1em; margin-bottom: 6px;">Tagline (Hindi)</label>
              <textarea name="tagline_hi" rows="2" required style="width: 100%; padding: 10px; background: #261e19; border: 1px solid var(--darkline); color: #fff; font: 500 13px var(--dev);"></textarea>
            </div>

            <div style="margin-bottom: 15px;">
              <label style="display: block; font: 700 10px var(--sans); letter-spacing: .1em; margin-bottom: 6px;">Full Description (Hindi)</label>
              <textarea name="description_hi" rows="4" required style="width: 100%; padding: 10px; background: #261e19; border: 1px solid var(--darkline); color: #fff; font: 500 13px var(--dev);"></textarea>
            </div>

            <div style="margin-bottom: 15px;">
              <label style="display: block; font: 700 10px var(--sans); letter-spacing: .1em; margin-bottom: 6px;">Featured Image URL</label>
              <input type="text" name="featured_image" required placeholder="https://images.unsplash.com/..." style="width: 100%; padding: 10px; background: #261e19; border: 1px solid var(--darkline); color: #fff; font: 500 12px var(--sans);">
            </div>

            <div style="margin-bottom: 20px;">
              <label style="display: flex; align-items: center; gap: 8px; font: 600 12px var(--sans); cursor: pointer;">
                <input type="checkbox" name="is_published" value="1" checked> Publish Immediately
              </label>
            </div>

            <button type="submit" class="btn primary" style="width: 100%; font-size: 10px; padding: 12px;">Save Destination</button>
          </form>
        </div>

        <!-- Destination List Table -->
        <div style="background: rgba(255,255,255,.04); border: 1px solid var(--darkline); padding: 30px; border-radius: 6px;">
          <h3 style="font: 600 20px var(--dev); color: var(--sand2); margin-bottom: 20px;">Chitrakoot Places List ({{ $destinations->count() }})</h3>

          <table style="width: 100%; border-collapse: collapse; font: 400 13px var(--dev);">
            <thead>
              <tr style="border-bottom: 1px solid var(--darkline); text-align: left; color: rgba(255,255,255,.5); font-size: 10px;">
                <th style="padding: 10px;">Name (Hindi / English)</th>
                <th style="padding: 10px;">Status</th>
                <th style="padding: 10px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($destinations as $d)
                <tr style="border-bottom: 1px solid rgba(255,255,255,.05);">
                  <td style="padding: 12px 10px;">
                    <strong style="color: #fff; font-size: 15px; display: block;">{{ $d->name_hi }}</strong>
                    <span style="color: rgba(255,255,255,.6); font-size: 11px; font-family: var(--sans);">{{ $d->name_en }}</span>
                  </td>
                  <td style="padding: 12px 10px; font-family: var(--sans); font-size: 11px;">
                    <span style="color: {{ $d->is_published ? '#81c784' : '#e57373' }};">{{ $d->is_published ? 'Published' : 'Draft' }}</span>
                  </td>
                  <td style="padding: 12px 10px;">
                    <form action="{{ url('/admin/destinations/' . $d->id) }}" method="POST" onsubmit="return confirm('Delete this place?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" style="background: #c62828; color: #fff; border: 0; padding: 4px 10px; font-size: 10px; border-radius: 3px; cursor: pointer;">Delete</button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

      </div>

    </div>
  </section>

@endsection
