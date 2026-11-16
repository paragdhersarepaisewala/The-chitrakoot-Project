@extends('layouts.admin')

@section('title', 'Email SMTP & Template Manager | CMS Admin')

@section('styles')
<style>
  .email-template-row {
    background: #ffffff;
    border: 1px solid var(--line, rgba(89, 58, 40, 0.14));
    border-radius: 8px;
    padding: 20px 24px;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    transition: all .2s ease;
    box-shadow: 0 2px 10px rgba(0,0,0,0.02);
  }
  .email-template-row:hover {
    border-color: var(--sand, #b98550);
    box-shadow: 0 6px 20px rgba(0,0,0,0.05);
    transform: translateY(-1px);
  }
  .tmpl-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--deep, #16100d);
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .tmpl-slug {
    font-size: 10px;
    font-family: monospace;
    background: rgba(185, 133, 80, 0.15);
    color: var(--sand, #b98550);
    padding: 2px 8px;
    border-radius: 9999px;
    font-weight: 600;
  }
  .tmpl-meta {
    font-size: 12.5px;
    color: rgba(43, 35, 29, 0.7);
    margin-top: 4px;
    line-height: 1.5;
  }
  .tmpl-tags {
    font-size: 11px;
    color: #888;
    margin-top: 3px;
    font-family: var(--sans, sans-serif);
  }
  .tmpl-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
  }
  .btn-preview {
    padding: 7px 18px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    border: 1px solid var(--line);
    background: #ffffff;
    color: var(--ink);
    cursor: pointer;
    transition: all .2s ease;
  }
  .btn-preview:hover {
    background: #f7f4ed;
    border-color: var(--sand);
  }
  .btn-edit {
    padding: 7px 20px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    border: none;
    background: var(--crimson, #8f1719);
    color: #ffffff;
    cursor: pointer;
    transition: all .2s ease;
  }
  .btn-edit:hover {
    background: #6e1012;
  }

  /* Modal Windows */
  .modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(20, 14, 11, 0.78);
    backdrop-filter: blur(5px);
    z-index: 99999;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }
  .modal-card {
    background: #ffffff;
    border-radius: 12px;
    width: 100%;
    max-width: 760px;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    box-shadow: 0 25px 60px rgba(0,0,0,0.4);
    border: 1px solid rgba(185,133,80,0.3);
  }
</style>
@endsection

@section('content')

<!-- Masthead Banner Matching Screenshot 3 -->
<div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; margin-bottom: 28px; padding-bottom: 20px; border-bottom: 1px solid var(--line);">
  <div>
    <h1 style="font-size: 26px; font-weight: 700; font-family: var(--dev, serif); color: var(--deep); margin: 0;">
      Email SMTP & Template Manager
    </h1>
    <p style="font-size: 12.5px; color: rgba(43,35,29,0.7); margin-top: 4px;">
      Configure outgoing SMTP credentials, customize luxury HTML notification designs, and preview live templates
    </p>
  </div>

  <div style="display: flex; gap: 10px;">
    <button type="button" onclick="openSmtpConfigModal()" class="action-btn" style="background: #16100d; color: #fff; font-size: 12px; padding: 9px 22px; border-radius: 9999px;">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
      CONFIGURE SMTP SERVER
    </button>
  </div>
</div>

<!-- Section Label -->
<div style="font-size: 11px; font-weight: 800; letter-spacing: .16em; text-transform: uppercase; color: rgba(43,35,29,0.6); margin-bottom: 16px;">
  REGISTERED NOTIFICATION EMAILS ({{ $templates->count() }})
</div>

<!-- List of Registered Notification Emails (Matching Screenshot 3) -->
<div class="email-templates-list">
  @forelse($templates as $tmpl)
    <div class="email-template-row">
      <div style="flex: 1; min-width: 0;">
        <div class="tmpl-title">
          <span>{{ $tmpl->name }}</span>
          <span class="tmpl-slug">{{ $tmpl->slug }}</span>
          @if(!$tmpl->is_active)
            <span style="font-size: 10px; color: #dc3545; background: #ffebee; padding: 2px 6px; border-radius: 4px;">Disabled</span>
          @endif
        </div>
        <div class="tmpl-meta">
          <strong>Subject:</strong> {{ $tmpl->subject }}
        </div>
        <div class="tmpl-tags">
          @php
            preg_match_all('/\{[a-z_0-9]+\}/i', $tmpl->subject . ' ' . $tmpl->content_html, $matches);
            $tags = array_unique($matches[0] ?? []);
          @endphp
          <strong>Tags:</strong> {{ implode(', ', $tags) ?: '{name}, {site_name}, {site_url}' }}
        </div>
      </div>

      <div class="tmpl-actions">
        <button type="button" onclick="previewTemplate({{ $tmpl->id }})" class="btn-preview">
          PREVIEW HTML
        </button>
        <button type="button" onclick="editTemplate({{ $tmpl->id }}, '{{ addslashes($tmpl->name) }}', '{{ addslashes($tmpl->subject) }}', {{ $tmpl->is_active ? 1 : 0 }})" class="btn-edit">
          EDIT TEMPLATE
        </button>
      </div>
    </div>
  @empty
    <div style="background: #fff; padding: 40px; text-align: center; border-radius: 8px; color: #888;">
      No email templates found.
    </div>
  @endforelse
</div>

<!-- 1. PREVIEW TEMPLATE MODAL -->
<div id="previewModal" class="modal-overlay">
  <div class="modal-card" style="max-width: 880px; height: 90vh;">
    <div style="padding: 14px 24px; border-bottom: 1px solid #eee; display: flex; align-items: center; justify-content: space-between; background: #faf8f5;">
      <div>
        <h3 id="previewModalTitle" style="font-size: 15px; font-weight: 700; margin: 0; color: #16100d;">Template Preview</h3>
        <span id="previewModalSubject" style="font-size: 12px; color: #777; display: block; margin-top: 2px;">Subject: ...</span>
      </div>
      <div style="display: flex; align-items: center; gap: 12px;">
        <div style="display: inline-flex; background: #eee; border-radius: 6px; padding: 2px;">
          <button type="button" onclick="setPreviewDevice('desktop')" id="btnDeviceDesktop" style="border: none; background: #fff; border-radius: 4px; padding: 4px 10px; font-size: 11px; font-weight: 700; cursor: pointer;">Desktop</button>
          <button type="button" onclick="setPreviewDevice('mobile')" id="btnDeviceMobile" style="border: none; background: transparent; border-radius: 4px; padding: 4px 10px; font-size: 11px; font-weight: 700; cursor: pointer; color: #666;">Mobile</button>
        </div>
        <button type="button" onclick="closePreviewModal()" style="background: none; border: none; font-size: 24px; color: #888; cursor: pointer;">&times;</button>
      </div>
    </div>

    <!-- Email Client Metadata Header Mockup -->
    <div style="padding: 10px 24px; background: #f0ede6; border-bottom: 1px solid #e5ded5; font-size: 12px; color: #555; display: flex; gap: 20px; flex-wrap: wrap;">
      <div><strong>From:</strong> Shri Kamadgiri Peetham &lt;seva@chitrakootpeetham.org&gt;</div>
      <div><strong>To:</strong> Devotee Member &lt;aditya.sharma@example.com&gt;</div>
    </div>

    <div id="previewViewportWrap" style="padding: 20px; overflow-y: auto; flex: 1; background: #e8e2d8; display: flex; justify-content: center; align-items: flex-start;">
      <iframe id="previewEmailIframe" style="width: 100%; max-width: 680px; height: 100%; min-height: 580px; border: 1px solid #dcd3c5; border-radius: 8px; background: #fff; box-shadow: 0 4px 20px rgba(0,0,0,0.08);"></iframe>
    </div>

    <div style="padding: 12px 24px; border-top: 1px solid #eee; display: flex; justify-content: flex-end; background: #faf8f5;">
      <button type="button" onclick="closePreviewModal()" class="action-btn">Close Preview</button>
    </div>
  </div>
</div>

<!-- 2. EDIT TEMPLATE MODAL -->
<div id="editTemplateModal" class="modal-overlay">
  <div class="modal-card" style="max-width: 820px;">
    <div style="padding: 16px 24px; border-bottom: 1px solid #eee; display: flex; align-items: center; justify-content: space-between; background: #faf8f5;">
      <h3 id="editModalTitle" style="font-size: 16px; font-weight: 700; margin: 0; color: #16100d;">Edit Email Notification Template</h3>
      <button type="button" onclick="closeEditModal()" style="background: none; border: none; font-size: 24px; color: #888; cursor: pointer;">&times;</button>
    </div>

    <form id="editTemplateForm" onsubmit="submitTemplateUpdate(event)" style="display: flex; flex-direction: column; flex: 1; overflow: hidden;">
      <input type="hidden" id="edit_template_id">

      <div style="padding: 24px; overflow-y: auto; flex: 1;">
        <div style="margin-bottom: 16px;">
          <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #555; margin-bottom: 6px;">EMAIL SUBJECT LINE</label>
          <input type="text" id="edit_subject" required style="width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px;">
        </div>

        <div style="margin-bottom: 16px;">
          <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #555; margin-bottom: 6px;">HTML TEMPLATE BODY</label>
          <textarea id="edit_content_html" rows="14" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 13px; font-family: monospace; line-height: 1.5;"></textarea>
          <span style="font-size: 11px; color: #777; margin-top: 4px; display: block;">You can use standard HTML inline styling and merge tags like {name}, {otp}, {amount}, {receipt_id}, {site_name}.</span>
        </div>

        <div style="display: flex; align-items: center; gap: 8px;">
          <input type="checkbox" id="edit_is_active" value="1">
          <label for="edit_is_active" style="font-size: 13px; font-weight: 600; color: #333;">Enable this notification email</label>
        </div>
      </div>

      <div style="padding: 14px 24px; border-top: 1px solid #eee; display: flex; align-items: center; justify-content: space-between; background: #faf8f5;">
        <button type="button" onclick="closeEditModal()" class="action-btn">Cancel</button>
        <button type="submit" class="action-btn primary" style="background: var(--crimson); border-color: var(--crimson);">Save Template Changes</button>
      </div>
    </form>
  </div>
</div>

<!-- 3. CONFIGURE SMTP SERVER MODAL -->
<div id="smtpConfigModal" class="modal-overlay">
  <div class="modal-card" style="max-width: 680px;">
    <div style="padding: 16px 24px; border-bottom: 1px solid #eee; display: flex; align-items: center; justify-content: space-between; background: #faf8f5;">
      <h3 style="font-size: 16px; font-weight: 700; margin: 0; color: #16100d;">Configure Outgoing SMTP Server</h3>
      <button type="button" onclick="closeSmtpConfigModal()" style="background: none; border: none; font-size: 24px; color: #888; cursor: pointer;">&times;</button>
    </div>

    <form action="{{ route('admin.updateSmtpConfig') }}" method="POST" style="padding: 24px; overflow-y: auto; flex: 1;">
      @csrf

      @if($settings->isSmtpConfigured())
        <div style="background: rgba(16, 185, 129, 0.08); border: 1.5px solid #10b981; color: #065f46; padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
          <span style="font-size: 18px;">🟢</span>
          <div>
            <strong>Live SMTP Server Active:</strong> Testing temporary OTP is disabled. Devotees receive authentic 6-digit OTP codes in their email inbox.
          </div>
        </div>
      @else
        <div style="background: rgba(245, 158, 11, 0.08); border: 1.5px dashed #d97706; color: #92400e; padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
          <span style="font-size: 18px;">🟡</span>
          <div>
            <strong>Testing Mode Active (Temporary OTP Bypass Enabled):</strong> Outgoing SMTP server is not yet attached. Temporary OTP bypass is active on registration. Saving valid SMTP credentials below will automatically remove testing OTP and enable live email dispatch.
          </div>
        </div>
      @endif

      <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px; margin-bottom: 16px;">
        <div>
          <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #555; margin-bottom: 6px;">SMTP HOST</label>
          <input type="text" name="smtp_host" value="{{ $settings->smtp_host ?? 'smtp.hostinger.com' }}" placeholder="smtp.hostinger.com / smtp.gmail.com" style="width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 13px;">
        </div>
        <div>
          <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #555; margin-bottom: 6px;">SMTP PORT</label>
          <input type="text" name="smtp_port" value="{{ $settings->smtp_port ?? '587' }}" placeholder="587 / 465" style="width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 13px;">
        </div>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
        <div>
          <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #555; margin-bottom: 6px;">SMTP USERNAME / EMAIL</label>
          <input type="text" name="smtp_username" value="{{ $settings->smtp_username ?? 'info@kamadgiripeetham.org' }}" style="width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 13px;">
        </div>
        <div>
          <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #555; margin-bottom: 6px;">SMTP PASSWORD</label>
          <input type="password" name="smtp_password" value="{{ $settings->smtp_password ?? '' }}" placeholder="••••••••••••" style="width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 13px;">
        </div>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
        <div>
          <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #555; margin-bottom: 6px;">ENCRYPTION</label>
          <select name="smtp_encryption" style="width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 13px;">
            <option value="tls" {{ ($settings->smtp_encryption ?? 'tls') === 'tls' ? 'selected' : '' }}>TLS (Port 587)</option>
            <option value="ssl" {{ ($settings->smtp_encryption ?? '') === 'ssl' ? 'selected' : '' }}>SSL (Port 465)</option>
            <option value="" {{ empty($settings->smtp_encryption) ? 'selected' : '' }}>None</option>
          </select>
        </div>
        <div>
          <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #555; margin-bottom: 6px;">FROM SENDER NAME</label>
          <input type="text" name="smtp_from_name" value="{{ $settings->smtp_from_name ?? 'Shri Kamadgiri Peetham Sewa Trust' }}" style="width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 13px;">
        </div>
      </div>

      <div style="margin-bottom: 24px;">
        <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #555; margin-bottom: 6px;">FROM EMAIL ADDRESS</label>
        <input type="email" name="smtp_from_address" value="{{ $settings->smtp_from_address ?? 'info@kamadgiripeetham.org' }}" style="width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 13px;">
      </div>

      <!-- Test SMTP Dispatcher -->
      <div style="background: #fdfaf5; border: 1px solid var(--sand); border-radius: 8px; padding: 16px; margin-bottom: 20px;">
        <strong style="font-size: 12px; color: #16100d; display: block; margin-bottom: 8px;">Verify SMTP Credentials with Live Test Email</strong>
        <div style="display: flex; gap: 8px;">
          <input type="email" id="testEmailRecipient" placeholder="Enter recipient email (e.g. your@email.com)" style="flex: 1; padding: 8px 12px; font-size: 12px; border: 1px solid #ccc; border-radius: 4px;">
          <button type="button" onclick="sendTestEmail()" class="action-btn teal" style="font-size: 11px; padding: 8px 16px;">SEND TEST EMAIL</button>
        </div>
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 10px;">
        <button type="button" onclick="closeSmtpConfigModal()" class="action-btn">Cancel</button>
        <button type="submit" class="action-btn primary" style="background: #086c60; border-color: #086c60;">SAVE SMTP CREDENTIALS</button>
      </div>
    </form>
  </div>
</div>

@endsection

@section('scripts')
<script>
  function openSmtpConfigModal() {
    document.getElementById('smtpConfigModal').style.display = 'flex';
  }
  function closeSmtpConfigModal() {
    document.getElementById('smtpConfigModal').style.display = 'none';
  }

  function setPreviewDevice(device) {
    const iframe = document.getElementById('previewEmailIframe');
    const btnDesktop = document.getElementById('btnDeviceDesktop');
    const btnMobile = document.getElementById('btnDeviceMobile');
    if (device === 'mobile') {
      iframe.style.maxWidth = '375px';
      btnMobile.style.background = '#fff';
      btnMobile.style.color = '#000';
      btnDesktop.style.background = 'transparent';
      btnDesktop.style.color = '#666';
    } else {
      iframe.style.maxWidth = '680px';
      btnDesktop.style.background = '#fff';
      btnDesktop.style.color = '#000';
      btnMobile.style.background = 'transparent';
      btnMobile.style.color = '#666';
    }
  }

  function previewTemplate(id) {
    const modal = document.getElementById('previewModal');
    modal.style.display = 'flex';
    setPreviewDevice('desktop');

    fetch(`/admin/email-templates/${id}/preview`)
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          document.getElementById('previewModalTitle').textContent = data.template.name;
          document.getElementById('previewModalSubject').textContent = 'Subject: ' + data.rendered_subject;
          const iframe = document.getElementById('previewEmailIframe');
          const doc = iframe.contentWindow.document;
          doc.open();
          doc.write(data.rendered_html);
          doc.close();
        } else {
          alert('Failed to render preview: ' + (data.message || 'Unknown error'));
        }
      })
      .catch(err => {
        alert('Preview error: ' + err.message);
      });
  }

  function closePreviewModal() {
    document.getElementById('previewModal').style.display = 'none';
  }

  function editTemplate(id, name, subject, isActive) {
    document.getElementById('edit_template_id').value = id;
    document.getElementById('editModalTitle').textContent = 'Edit: ' + name;
    document.getElementById('edit_subject').value = subject;
    document.getElementById('edit_is_active').checked = isActive === 1;

    // Fetch full HTML body
    fetch(`/admin/email-templates/${id}/preview`)
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          document.getElementById('edit_content_html').value = data.template.content_html;
          document.getElementById('editTemplateModal').style.display = 'flex';
        }
      });
  }

  function closeEditModal() {
    document.getElementById('editTemplateModal').style.display = 'none';
  }

  function submitTemplateUpdate(e) {
    e.preventDefault();
    const id = document.getElementById('edit_template_id').value;
    const subject = document.getElementById('edit_subject').value;
    const contentHtml = document.getElementById('edit_content_html').value;
    const isActive = document.getElementById('edit_is_active').checked ? 1 : 0;
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(`/admin/email-templates/${id}`, {
      method: 'PUT',
      headers: {
        'X-CSRF-TOKEN': token,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        subject: subject,
        content_html: contentHtml,
        is_active: isActive
      })
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        showToast(data.message, 'success');
        closeEditModal();
        setTimeout(() => location.reload(), 600);
      } else {
        alert(data.message || 'Error updating template');
      }
    })
    .catch(err => alert(err.message));
  }

  function sendTestEmail() {
    const email = document.getElementById('testEmailRecipient').value.trim();
    if (!email) {
      alert('Please enter a recipient email address.');
      return;
    }

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    fetch('/admin/email-templates/test-smtp', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': token,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({ test_email: email })
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        showToast(data.message, 'success');
      } else {
        alert(data.message);
      }
    })
    .catch(err => alert(err.message));
  }
</script>
@endsection
