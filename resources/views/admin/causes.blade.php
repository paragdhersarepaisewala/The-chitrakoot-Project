@extends('layouts.app')

@section('title', 'Donation Causes Manager | Admin Portal')

@section('content')

  <section class="section" style="padding-top: 140px; background: var(--paper); min-height: 80vh;">
    <div class="container">

      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
          <span style="font: 700 10px var(--sans); letter-spacing: .2em; text-transform: uppercase; color: var(--sand);">CMS ADMIN</span>
          <h1 style="font: 600 32px var(--dev); color: var(--deep); margin: 4px 0 0;">Donation Causes Manager</h1>
        </div>
        <a href="{{ url('/admin') }}" class="btn dark">← Back to Admin Dashboard</a>
      </div>

      @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 6px; margin-bottom: 25px; font: 500 14px var(--sans);">
          {{ session('success') }}
        </div>
      @endif

      <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">

        <!-- Create Cause Form -->
        <div style="background: #fff; border: 1px solid var(--line); border-radius: 8px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.04);">
          <h3 style="font: 600 20px var(--dev); color: var(--brown); margin: 0 0 20px; border-bottom: 1px solid var(--line); padding-bottom: 10px;">
            Create New Donation Cause
          </h3>

          <form action="{{ url('/admin/causes') }}" method="POST">
            @csrf
            <div style="margin-bottom: 16px;">
              <label style="display: block; font: 600 11px var(--sans); letter-spacing: .12em; text-transform: uppercase; margin-bottom: 6px;">Cause Title (Hindi)</label>
              <input type="text" name="title" required style="width: 100%; padding: 10px; border: 1px solid var(--line); font: 500 14px var(--dev);" placeholder="जैसे: गौ सेवा एवं चिकित्सा फंड">
            </div>

            <div style="margin-bottom: 16px;">
              <label style="display: block; font: 600 11px var(--sans); letter-spacing: .12em; text-transform: uppercase; margin-bottom: 6px;">Target Amount (₹)</label>
              <input type="number" name="target_amount" required value="500000" style="width: 100%; padding: 10px; border: 1px solid var(--line); font: 600 14px var(--sans);">
            </div>

            <div style="margin-bottom: 16px;">
              <label style="display: block; font: 600 11px var(--sans); letter-spacing: .12em; text-transform: uppercase; margin-bottom: 6px;">Description (Hindi)</label>
              <textarea name="description" rows="4" required style="width: 100%; padding: 10px; border: 1px solid var(--line); font: 400 14px var(--dev);" placeholder="प्रकल्प का उद्देश्य एवं उपयोग..."></textarea>
            </div>

            <div style="margin-bottom: 20px;">
              <label style="display: block; font: 600 11px var(--sans); letter-spacing: .12em; text-transform: uppercase; margin-bottom: 6px;">Image URL</label>
              <input type="url" name="image_path" required value="https://images.unsplash.com/photo-1560493676-04071c5f467b?auto=format&fit=crop&w=1200&q=80" style="width: 100%; padding: 10px; border: 1px solid var(--line); font: 500 13px var(--sans);">
            </div>

            <button type="submit" class="btn primary" style="width: 100%; justify-content: center; font-size: 10px;">
              Save Donation Cause
            </button>
          </form>
        </div>

        <!-- Causes List -->
        <div style="background: #fff; border: 1px solid var(--line); border-radius: 8px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.04);">
          <h3 style="font: 600 20px var(--dev); color: var(--brown); margin: 0 0 20px; border-bottom: 1px solid var(--line); padding-bottom: 10px;">
            Active Donation Causes ({{ $causes->count() }})
          </h3>

          <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font: 400 14px var(--sans);">
              <thead>
                <tr style="background: var(--ivory); border-bottom: 2px solid var(--line); text-align: left; font-size: 11px; text-transform: uppercase;">
                  <th style="padding: 12px;">Cause Name</th>
                  <th style="padding: 12px;">Target Amount</th>
                  <th style="padding: 12px;">Status</th>
                  <th style="padding: 12px;">Actions</th>
                </tr>
              </thead>
              <tbody>
                @forelse($causes as $cause)
                  <tr style="border-bottom: 1px solid var(--line);">
                    <td style="padding: 14px; font-family: var(--dev); font-weight: 600;">{{ $cause->title }}</td>
                    <td style="padding: 14px; font-weight: 700;">₹{{ number_format($cause->target_amount, 2) }}</td>
                    <td style="padding: 14px;"><span style="color: green; font-weight: 700;">{{ strtoupper($cause->status) }}</span></td>
                    <td style="padding: 14px;">
                      <form action="{{ url('/admin/causes/' . $cause->id) }}" method="POST" onsubmit="return confirm('Delete cause?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: #dc3545; color: #fff; border: 0; padding: 6px 12px; border-radius: 4px; font-size: 11px; cursor: pointer;">Delete</button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" style="padding: 20px; text-align: center; color: #666;">No causes found.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

      </div>

    </div>
  </section>

@endsection
