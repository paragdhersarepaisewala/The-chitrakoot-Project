@extends('layouts.admin')

@section('title', 'Users & Roles Manager | CMS Admin')
@section('header_title', 'Users & Roles Manager')
@section('header_subtitle', 'Assign Super Admin, Content Editor, and Staff Access Privileges')

@section('content')
  <div>

      @if(session('success'))
        <div
          style="background: rgba(46,125,50,.2); color: #81c784; padding: 15px; margin-bottom: 25px; border-left: 4px solid #81c784; font: 500 14px var(--sans);">
          {{ session('success') }}
        </div>
      @endif

      <div
        style="background: rgba(255,255,255,.04); border: 1px solid var(--darkline); padding: 30px; border-radius: 6px;">
        <h3 style="font: 600 20px var(--dev); color: var(--sand2); margin-bottom: 20px;">All Registered Users & Admin
          Roles ({{ $users->count() }})</h3>

        <table style="width: 100%; border-collapse: collapse; font: 400 13px var(--sans);">
          <thead>
            <tr
              style="border-bottom: 1px solid var(--darkline); text-align: left; color: rgba(255,255,255,.5); font-size: 10px; text-transform: uppercase;">
              <th style="padding: 12px;">User Name</th>
              <th style="padding: 12px;">Email Address</th>
              <th style="padding: 12px;">Assigned Role</th>
              <th style="padding: 12px;">Approval Status</th>
              <th style="padding: 12px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($users as $u)
              <tr style="border-bottom: 1px solid rgba(255,255,255,.05);">
                <td style="padding: 14px 12px;">
                  <strong style="color: #fff; font-size: 15px;">{{ $u->name }}</strong>
                </td>
                <td style="padding: 14px 12px; color: rgba(255,255,255,.7);">{{ $u->email }}</td>

                <form action="{{ url('/admin/users/' . $u->id . '/role') }}" method="POST">
                  @csrf
                  <td style="padding: 14px 12px;">
                    <select name="role"
                      style="padding: 6px 10px; background: #261e19; border: 1px solid var(--darkline); color: #fff; font-size: 12px;">
                      <option value="super_admin" {{ $u->role === 'super_admin' ? 'selected' : '' }}>Super Admin (Full
                        Control)</option>
                      <option value="content_admin" {{ $u->role === 'content_admin' ? 'selected' : '' }}>Content Admin (CMS)
                      </option>
                      <option value="donation_admin" {{ $u->role === 'donation_admin' ? 'selected' : '' }}>Donation Admin
                      </option>
                      <option value="membership_admin" {{ $u->role === 'membership_admin' ? 'selected' : '' }}>Membership
                        Admin</option>
                      <option value="media_editor" {{ $u->role === 'media_editor' ? 'selected' : '' }}>Media Editor</option>
                      <option value="viewer" {{ $u->role === 'viewer' ? 'selected' : '' }}>Viewer Staff</option>
                      <option value="donor" {{ $u->role === 'donor' ? 'selected' : '' }}>Registered Donor</option>
                      <option value="member" {{ $u->role === 'member' ? 'selected' : '' }}>Active Member</option>
                    </select>
                  </td>

                  <td style="padding: 14px 12px;">
                    <select name="approval_status"
                      style="padding: 6px 10px; background: #261e19; border: 1px solid var(--darkline); color: #fff; font-size: 12px;">
                      <option value="approved" {{ $u->approval_status === 'approved' ? 'selected' : '' }}>Approved</option>
                      <option value="pending" {{ $u->approval_status === 'pending' ? 'selected' : '' }}>Pending</option>
                      <option value="rejected" {{ $u->approval_status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                  </td>

                  <td style="padding: 14px 12px;">
                    <button type="submit" class="btn primary" style="padding: 6px 14px; font-size: 9px;">Save Role</button>
                  </td>
                </form>

              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

    </div>
  </div>
@endsection