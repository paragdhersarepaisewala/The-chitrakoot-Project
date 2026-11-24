<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'CMS Admin Portal | Shri Kamadgiri Trust')</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=Manrope:wght@400;500;600;700;800&family=Noto+Serif+Devanagari:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,600;0,700;0,800;1,500;1,600&display=swap" rel="stylesheet">

  <style>
    :root {
      --ivory: #f7f4ed;
      --paper: #ece5d8;
      --sand: #b98550;
      --sand2: #d4ad7d;
      --sand-hover: #c9935a;
      --brown: #593a28;
      --deep: #16100d;
      --sidebar-bg: #140e0b;
      --sidebar-active: #b98550;
      --sidebar-hover: rgba(185, 133, 80, 0.16);
      --ink: #2b231d;
      --white: #ffffff;
      --line: rgba(89, 58, 40, 0.14);
      --line-dark: rgba(255, 255, 255, 0.12);
      --crimson: #8f1719;
      --crimson-hover: #6e1012;
      --forest: #086c60;
      --gold: #f4a900;
      --serif: "Playfair Display", Georgia, serif;
      --dev: "Noto Serif Devanagari", serif;
      --sans: "Manrope", "DM Sans", Arial, sans-serif;
      --ease: cubic-bezier(.2, .78, .18, 1);
      --radius: 8px;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }
    body {
      background: var(--ivory);
      color: var(--ink);
      font-family: var(--sans);
      line-height: 1.5;
      min-height: 100vh;
      display: flex;
    }

    a { color: inherit; text-decoration: none; }
    button { font: inherit; cursor: pointer; }
    input, select, textarea { font-family: inherit; }

    /* Layout Wrapper */
    .admin-wrapper {
      display: flex;
      width: 100%;
      min-height: 100vh;
    }

    /* Sidebar */
    .admin-sidebar {
      width: 260px;
      min-width: 260px;
      background: var(--sidebar-bg);
      color: #f1ede4;
      display: flex;
      flex-direction: column;
      border-right: 1px solid rgba(185, 133, 80, 0.2);
      position: sticky;
      top: 0;
      height: 100vh;
      overflow-y: auto;
      z-index: 1000;
      scrollbar-width: thin;
      scrollbar-color: rgba(185,133,80,.3) transparent;
      transition: transform .3s var(--ease);
    }

    .sidebar-brand {
      padding: 20px 20px 18px;
      display: flex;
      align-items: center;
      gap: 12px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
      background: rgba(0,0,0,0.2);
    }
    .brand-logo-badge {
      width: 38px;
      height: 38px;
      border-radius: 8px;
      background: linear-gradient(135deg, var(--sand), var(--brown));
      display: grid;
      place-items: center;
      color: #fff;
      font-weight: 800;
      font-size: 18px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }
    .brand-title {
      font-size: 16px;
      font-weight: 700;
      font-family: var(--sans);
      color: #fff;
      letter-spacing: .02em;
      line-height: 1.2;
    }
    .brand-subtitle {
      font-size: 10px;
      color: var(--sand2);
      letter-spacing: .08em;
      text-transform: uppercase;
      font-family: var(--sans);
      margin-top: 2px;
    }

    .sidebar-nav {
      padding: 14px 10px 24px;
      display: flex;
      flex-direction: column;
      gap: 2px;
      flex: 1;
    }

    .nav-section-label {
      font-size: 10px;
      font-weight: 800;
      letter-spacing: .16em;
      text-transform: uppercase;
      color: rgba(255, 255, 255, 0.4);
      padding: 14px 14px 6px;
      margin-top: 4px;
    }

    .sidebar-link {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 9px 14px;
      border-radius: 6px;
      font-size: 12.5px;
      font-weight: 500;
      color: rgba(255, 255, 255, 0.78);
      transition: all .2s var(--ease);
      position: relative;
    }
    .sidebar-link svg {
      width: 17px;
      height: 17px;
      stroke-width: 1.8;
      stroke: currentColor;
      fill: none;
      opacity: 0.8;
      flex-shrink: 0;
    }
    .sidebar-link:hover {
      background: var(--sidebar-hover);
      color: #fff;
      transform: translateX(2px);
    }
    .sidebar-link.active {
      background: var(--sidebar-active);
      color: #120d09;
      font-weight: 700;
      box-shadow: 0 4px 14px rgba(185, 133, 80, 0.35);
    }
    .sidebar-link.active svg {
      opacity: 1;
      stroke: #120d09;
    }
    .sidebar-link .badge {
      margin-left: auto;
      background: var(--crimson);
      color: #fff;
      font-size: 9px;
      font-weight: 700;
      padding: 2px 7px;
      border-radius: 9999px;
    }
    .sidebar-link .badge.gold {
      background: rgba(244, 169, 0, 0.25);
      color: var(--gold);
      border: 1px solid rgba(244, 169, 0, 0.4);
    }

    .sidebar-footer {
      padding: 14px 12px;
      border-top: 1px solid rgba(255, 255, 255, 0.08);
      display: flex;
      flex-direction: column;
      gap: 6px;
      background: rgba(0,0,0,0.15);
    }

    /* Main Content Area */
    .admin-main {
      flex: 1;
      min-width: 0;
      display: flex;
      flex-direction: column;
      background: var(--ivory);
    }

    /* Top Masthead */
    .admin-masthead {
      background: #ffffff;
      border-bottom: 1px solid var(--line);
      padding: 14px 32px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 20px;
      position: sticky;
      top: 0;
      z-index: 900;
      box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }

    .masthead-title-wrap {
      display: flex;
      flex-direction: column;
    }
    .masthead-title {
      font-size: 21px;
      font-weight: 700;
      font-family: var(--dev);
      color: var(--deep);
      line-height: 1.2;
    }
    .masthead-sub {
      font-size: 11.5px;
      color: rgba(43, 35, 29, 0.65);
      margin-top: 2px;
    }

    .masthead-actions {
      display: flex;
      align-items: center;
      gap: 10px;
      flex-wrap: wrap;
    }

    /* Admin Real-Time Notification Center */
    .admin-notif-wrap {
      position: relative;
      display: inline-block;
    }
    .notif-bell-btn {
      position: relative;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 7px 12px;
      background: #ffffff;
      border: 1px solid var(--line);
      border-radius: 9999px;
      color: var(--ink);
      font-size: 11px;
      font-weight: 700;
      transition: all 0.2s ease;
    }
    .notif-bell-btn:hover {
      background: var(--paper);
      border-color: var(--sand);
    }
    .notif-bell-btn svg {
      width: 16px;
      height: 16px;
    }
    .notif-counter-pill {
      background: var(--crimson);
      color: #fff;
      font-size: 10px;
      font-weight: 800;
      padding: 1px 6px;
      border-radius: 9999px;
      min-width: 16px;
      text-align: center;
      line-height: 14px;
      animation: notifPillPulse 2s infinite ease-in-out;
    }
    @keyframes notifPillPulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.15); box-shadow: 0 0 10px rgba(183, 28, 28, 0.5); }
      100% { transform: scale(1); }
    }
    .notif-dropdown-panel {
      position: absolute;
      top: calc(100% + 10px);
      right: 0;
      width: 380px;
      max-width: 92vw;
      background: #ffffff;
      border: 1px solid var(--line);
      border-radius: 14px;
      box-shadow: 0 12px 36px rgba(0,0,0,0.12);
      z-index: 1000;
      display: none;
      flex-direction: column;
      overflow: hidden;
      animation: notifFadeIn 0.2s ease-out forwards;
    }
    @keyframes notifFadeIn {
      from { opacity: 0; transform: translateY(-8px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .notif-panel-header {
      padding: 14px 18px;
      background: #FAF6F0;
      border-bottom: 1px solid var(--line);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .notif-panel-title {
      font-size: 13px;
      font-weight: 800;
      color: var(--deep);
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .notif-mark-read-btn {
      background: none;
      border: none;
      color: var(--sand);
      font-size: 11px;
      font-weight: 700;
      cursor: pointer;
      text-decoration: underline;
    }
    .notif-list-scroll {
      max-height: 380px;
      overflow-y: auto;
      display: flex;
      flex-direction: column;
    }
    .notif-row-item {
      padding: 12px 16px;
      border-bottom: 1px solid rgba(0,0,0,0.05);
      display: flex;
      align-items: flex-start;
      gap: 12px;
      text-decoration: none;
      color: inherit;
      transition: background 0.15s ease;
      position: relative;
    }
    .notif-row-item:hover {
      background: #FDFBF7;
    }
    .notif-row-item.unread {
      background: rgba(217, 93, 15, 0.05);
    }
    .notif-row-item.unread::before {
      content: "";
      position: absolute;
      left: 6px;
      top: 18px;
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: var(--crimson);
    }
    .notif-item-icon {
      font-size: 18px;
      width: 32px;
      height: 32px;
      border-radius: 8px;
      background: rgba(180, 83, 9, 0.1);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .notif-item-content {
      flex: 1;
      min-width: 0;
    }
    .notif-item-title {
      font-size: 12.5px;
      font-weight: 700;
      color: var(--deep);
      line-height: 1.3;
      margin-bottom: 2px;
    }
    .notif-item-desc {
      font-size: 11px;
      color: rgba(43,35,29,0.75);
      line-height: 1.4;
      word-break: break-word;
    }
    .notif-item-time {
      font-size: 10px;
      color: rgba(43,35,29,0.5);
      margin-top: 4px;
    }
    .notif-panel-footer {
      padding: 10px;
      background: #FAF6F0;
      border-top: 1px solid var(--line);
      text-align: center;
      font-size: 11px;
      font-weight: 700;
      color: var(--sand);
    }

    /* Masthead Action Buttons */
    .action-btn {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 7px 14px;
      border-radius: 9999px;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: .05em;
      text-transform: uppercase;
      border: 1px solid var(--line);
      background: #ffffff;
      color: var(--ink);
      transition: all .2s var(--ease);
      white-space: nowrap;
    }
    .action-btn:hover {
      background: var(--paper);
      border-color: var(--sand);
      transform: translateY(-1px);
    }
    .action-btn.primary {
      background: var(--crimson);
      border-color: var(--crimson);
      color: #ffffff;
    }
    .action-btn.primary:hover {
      background: var(--crimson-hover);
      border-color: var(--crimson-hover);
    }
    .action-btn.gold {
      background: #f4a900;
      border-color: #f4a900;
      color: #1a120b;
    }
    .action-btn.gold:hover {
      background: #df9900;
    }
    .action-btn.teal {
      background: var(--forest);
      border-color: var(--forest);
      color: #fff;
    }
    .action-btn.teal:hover {
      background: #06544a;
    }
    .action-btn svg {
      width: 13px;
      height: 13px;
      stroke-width: 2.2;
    }

    .mobile-nav-toggle {
      display: none;
      background: none;
      border: 1px solid var(--line);
      padding: 8px;
      border-radius: 6px;
      color: var(--deep);
    }

    /* Page Content */
    .admin-body {
      padding: 28px 32px 60px;
      flex: 1;
    }

    /* Universal UI Components */
    .card {
      background: #ffffff;
      border: 1px solid var(--line);
      border-radius: var(--radius);
      box-shadow: 0 4px 18px rgba(0,0,0,0.03);
    }

    /* Universal Stats Grid */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 16px;
      margin-bottom: 24px;
    }
    .stat-card {
      background: #ffffff;
      border: 1px solid var(--line);
      border-radius: 14px;
      padding: 18px 22px;
      box-shadow: 0 4px 14px rgba(43, 35, 29, 0.04);
      transition: all .2s var(--ease);
      position: relative;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    .stat-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 22px rgba(43, 35, 29, 0.08);
      border-color: rgba(185, 133, 80, 0.4);
    }
    .stat-label {
      font-size: 10.5px;
      font-weight: 800;
      letter-spacing: .08em;
      text-transform: uppercase;
      color: rgba(43, 35, 29, 0.6);
      margin-bottom: 8px;
    }
    .stat-val {
      font-size: 28px;
      font-weight: 800;
      font-family: var(--sans);
      line-height: 1.1;
      margin-bottom: 6px;
      letter-spacing: -0.02em;
    }
    .stat-sub {
      font-size: 11.5px;
      color: rgba(43, 35, 29, 0.55);
      font-weight: 500;
    }

    /* Universal CMS Table Card */
    .cms-table-card {
      background: #ffffff;
      border: 1px solid var(--line);
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(43, 35, 29, 0.04);
      overflow: hidden;
    }
    .cms-table {
      width: 100%;
      border-collapse: collapse;
      text-align: left;
    }
    .cms-table thead tr {
      background: #faf8f5;
      border-bottom: 1px solid var(--line);
    }
    .cms-table th {
      padding: 13px 20px;
      font-size: 11px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: .06em;
      color: var(--brown);
    }
    .cms-table td {
      padding: 16px 20px;
      border-bottom: 1px solid var(--line);
      vertical-align: middle;
      font-size: 13px;
    }
    .cms-table tbody tr:last-child td {
      border-bottom: none;
    }
    .cms-table tbody tr:hover {
      background: rgba(247, 243, 237, 0.5);
    }

    /* Universal Form Elements */
    .form-group {
      margin-bottom: 16px;
    }
    .form-label {
      display: block;
      font-size: 11.5px;
      font-weight: 700;
      letter-spacing: .04em;
      text-transform: uppercase;
      color: var(--ink);
      margin-bottom: 6px;
    }
    .form-input {
      width: 100%;
      padding: 10px 14px;
      border-radius: 8px;
      border: 1.5px solid var(--line);
      background: #ffffff;
      color: var(--ink);
      font-size: 13px;
      outline: none;
      transition: all .2s var(--ease);
    }
    .form-input:focus {
      border-color: #b98550;
      box-shadow: 0 0 0 3px rgba(185, 133, 80, 0.15);
      background: #ffffff;
    }

    /* Badges */
    .badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 4px 10px;
      border-radius: 9999px;
      font-size: 10.5px;
      font-weight: 700;
      letter-spacing: .03em;
    }
    .badge.gold {
      background: rgba(244, 169, 0, 0.15);
      color: #92400e;
      border: 1px solid rgba(244, 169, 0, 0.3);
    }

    /* Toast Notification */
    .toast-box {
      position: fixed;
      bottom: 24px;
      right: 24px;
      z-index: 999999;
      display: flex;
      flex-direction: column;
      gap: 10px;
      pointer-events: none;
    }
    .toast {
      pointer-events: auto;
      background: #1c1511;
      color: #fff;
      border-left: 4px solid var(--sand);
      padding: 12px 20px;
      border-radius: 6px;
      font-size: 13px;
      font-weight: 500;
      box-shadow: 0 10px 30px rgba(0,0,0,0.25);
      animation: toastIn .3s var(--ease);
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .toast.success { border-left-color: #28a745; }
    .toast.error { border-left-color: var(--crimson); }
    @keyframes toastIn { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

    /* Responsive */
    @media (max-width: 992px) {
      .admin-sidebar {
        position: fixed;
        left: 0; top: 0; bottom: 0;
        transform: translateX(-100%);
        box-shadow: 10px 0 30px rgba(0,0,0,0.4);
      }
      .admin-sidebar.open {
        transform: translateX(0);
      }
      .sidebar-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(4px);
        z-index: 999;
      }
      .sidebar-backdrop.open { display: block; }
      .mobile-nav-toggle { display: block; }
      .admin-masthead { padding: 14px 20px; }
      .admin-body { padding: 20px 16px 40px; }
    }
  </style>
  @yield('styles')
</head>
<body>

<div class="admin-wrapper">
  <!-- Mobile Backdrop -->
  <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleAdminSidebar(false)"></div>

  <!-- Left Sidebar -->
  <aside class="admin-sidebar" id="adminSidebar">
    <!-- Brand -->
    <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
      <div class="brand-logo-badge">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2">
          <path d="M3 21h18M3 10h18M5 10v11M19 10v11M12 2l7 8H5l7-8z"/>
        </svg>
      </div>
      <div>
        <div class="brand-title">CMS Admin</div>
        <div class="brand-subtitle">Shri Kamadgiri Portal</div>
      </div>
    </a>

    <!-- Nav Items -->
    <nav class="sidebar-nav">
      <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
        <span>Dashboard</span>
      </a>

      <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        <span>Users & Roles Manager</span>
      </a>

      <div class="nav-section-label">EDITORIAL & NEWS</div>

      <a href="{{ route('admin.blogs') }}" class="sidebar-link {{ request()->routeIs('admin.blogs') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
        <span>Articles & News</span>
      </a>

      <a href="{{ route('admin.blogs.create') }}" class="sidebar-link {{ request()->routeIs('admin.blogs.create') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
        <span>Create New Article</span>
      </a>

      <a href="{{ route('admin.categories') }}" class="sidebar-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
        <span>Categories & Topics</span>
      </a>

      <a href="{{ route('admin.comments') }}" class="sidebar-link {{ request()->routeIs('admin.comments*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
        <span>Comments & Moderation</span>
      </a>

      <div class="nav-section-label">MEDIA & HERITAGE</div>

      <a href="{{ route('admin.mediaGallery') }}" class="sidebar-link {{ request()->routeIs('admin.mediaGallery') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
        <span>Media Gallery Manager</span>
      </a>

      <div class="nav-section-label">LAYOUT & PAGES</div>

      <a href="{{ route('admin.homepageLayout') }}" class="sidebar-link {{ request()->routeIs('admin.homepageLayout') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
        <span>Homepage Layout</span>
        <span class="badge gold" style="margin-left: auto;">Editor</span>
      </a>

      <a href="{{ route('admin.about') }}" class="sidebar-link {{ request()->routeIs('admin.about*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        <span>About Us Page CMS</span>
        <span class="badge gold" style="margin-left: auto;">Editor</span>
      </a>

      <a href="{{ route('admin.navigationMenu') }}" class="sidebar-link {{ request()->routeIs('admin.navigationMenu') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
        <span>Navigation Menu Editor</span>
      </a>

      <div class="nav-section-label">POOJA & SEVA SERVICES</div>

      <a href="{{ route('admin.poojas') }}" class="sidebar-link {{ request()->routeIs('admin.poojas*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
        <span>Pooja, Hawan & Time Slots</span>
      </a>

      <a href="{{ route('admin.poojaBookings') }}" class="sidebar-link {{ request()->routeIs('admin.poojaBookings*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
        <span>Devotee Booking Approvals</span>
      </a>

      <a href="{{ route('admin.atithiVishram') }}" class="sidebar-link {{ request()->routeIs('admin.atithiVishram*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><path d="M3 21h18"/><path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"/><path d="M9 7h1"/><path d="M9 11h1"/><path d="M9 15h1"/><path d="M14 7h1"/><path d="M14 11h1"/><path d="M14 15h1"/></svg>
        <span>Atithi Vishram (Stay)</span>
      </a>

      <a href="{{ route('admin.templeBookings') }}" class="sidebar-link {{ request()->routeIs('admin.templeBookings*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
        <span>Temple Hall Bookings</span>
      </a>

      <a href="{{ route('admin.volunteers') }}" class="sidebar-link {{ request()->routeIs('admin.volunteers*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        <span>Volunteer Applications</span>
      </a>

      <a href="{{ route('admin.leadership') }}" class="sidebar-link {{ request()->routeIs('admin.leadership*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        <span>Leadership & Trustees</span>
      </a>

      <a href="{{ route('admin.contactInquiries') }}" class="sidebar-link {{ request()->routeIs('admin.contactInquiries*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><rect width="20" height="16" x="2" y="4" rx="2"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path></svg>
        <span>Contact Inquiries</span>
      </a>

      <a href="{{ route('admin.subscribers') }}" class="sidebar-link {{ request()->routeIs('admin.subscribers*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
        <span>Newsletter Subscribers</span>
      </a>

      <a href="{{ route('admin.events') }}" class="sidebar-link {{ request()->routeIs('admin.events*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
        <span>Upcoming Events & Utsavs</span>
      </a>

      <div class="nav-section-label">DONATIONS & SEVA INITIATIVES</div>

      <a href="{{ route('admin.causes') }}" class="sidebar-link {{ request()->routeIs('admin.causes*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
        <span>Donation Campaigns</span>
        <span class="badge gold" style="margin-left: auto;">Causes</span>
      </a>

      <a href="{{ route('admin.sevas') }}" class="sidebar-link {{ request()->routeIs('admin.sevas*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
        <span>Seva Projects</span>
      </a>

      <a href="{{ route('admin.destinations') }}" class="sidebar-link {{ request()->routeIs('admin.destinations') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon></svg>
        <span>Sacred Places Showcase</span>
      </a>

      <div class="nav-section-label">SYSTEM & CONFIG</div>

      <a href="{{ route('admin.seoSettings') }}" class="sidebar-link {{ request()->routeIs('admin.seoSettings*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        <span>All Pages SEO Settings</span>
        <span class="badge gold" style="margin-left: auto;">SEO</span>
      </a>

      <a href="{{ route('admin.emailTemplates') }}" class="sidebar-link {{ request()->routeIs('admin.emailTemplates') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
        <span>Email SMTP & Templates</span>
      </a>

      <a href="{{ route('admin.settings') }}" class="sidebar-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
        <span>Site Settings & Favicon</span>
      </a>

      <button type="button" onclick="triggerCacheFlush()" class="sidebar-link" style="background: none; border: 0; width: 100%; text-align: left; cursor: pointer;">
        <svg viewBox="0 0 24 24"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
        <span>Flush Server Cache</span>
      </button>

      <a href="{{ url('/') }}" class="sidebar-link" style="color: var(--sand2);">
        <svg viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        <span>Back to Homepage</span>
      </a>
    </nav>

    <!-- Sidebar User / Profile Footer -->
    <div class="sidebar-footer">
      <div style="display: flex; align-items: center; justify-content: space-between;">
        <div style="font-size: 11px; color: rgba(255,255,255,0.6);">
          Logged in as <strong style="color: #fff;">{{ auth()->user()?->name ?? 'Admin' }}</strong>
        </div>
        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
          @csrf
          <button type="submit" style="background: none; border: 0; color: var(--crimson); font-size: 11px; font-weight: 700; cursor: pointer;">Logout</button>
        </form>
      </div>
    </div>
  </aside>

  <!-- Main Content Wrap -->
  <main class="admin-main">
    <!-- Top Masthead Bar -->
    <header class="admin-masthead">
      <div style="display: flex; align-items: center; gap: 14px;">
        <button type="button" class="mobile-nav-toggle" onclick="toggleAdminSidebar(true)" aria-label="Open Menu">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
        </button>
        <div class="masthead-title-wrap">
          <h1 class="masthead-title">@yield('header_title', 'Real-Time Analytics & CMS Overview')</h1>
          <span class="masthead-sub">@yield('header_subtitle', 'Live In-Dashboard Traffic Statistics & Audience Demographics')</span>
        </div>
      </div>

      <div class="masthead-actions">
        <!-- Real-Time Notification Bell & Dropdown -->
        <div class="admin-notif-wrap" id="adminNotifWrap">
          <button type="button" class="notif-bell-btn" id="adminNotifBtn" onclick="toggleAdminNotifDropdown()" aria-label="Portal Notifications">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
              <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
            </svg>
            <span>Alerts</span>
            <span class="notif-counter-pill" id="adminNotifBadge" style="display: none;">0</span>
          </button>

          <div class="notif-dropdown-panel" id="adminNotifPanel">
            <div class="notif-panel-header">
              <div class="notif-panel-title">
                <span>🔔 Portal Activity Stream</span>
                <span class="notif-counter-pill" id="adminNotifHeaderBadge" style="display: none;">0</span>
              </div>
              <button type="button" class="notif-mark-read-btn" onclick="markAdminNotifSeen()">Mark Seen</button>
            </div>
            <div class="notif-list-scroll" id="adminNotifList">
              <div style="padding: 30px 20px; text-align: center; color: #888; font-size: 12px;">
                Checking notifications...
              </div>
            </div>
            <div class="notif-panel-footer">
              <span>Auto-updating live activity center &bull; Real-time</span>
            </div>
          </div>
        </div>

        @yield('header_actions')
        <a href="{{ url('/feed.xml') }}" target="_blank" class="action-btn gold">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 11a9 9 0 0 1 9 9"></path><path d="M4 4a16 16 0 0 1 16 16"></path><circle cx="5" cy="19" r="1"></circle></svg>
          Live RSS Feed
        </a>
        <a href="{{ url('/sitemap.xml') }}" target="_blank" class="action-btn">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="18" height="18" rx="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
          Sitemap XML
        </a>
        <button type="button" onclick="triggerCacheFlush()" class="action-btn teal">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
          Flush Cache
        </button>
        <a href="{{ url('/') }}" target="_blank" class="action-btn">
          View Website &rarr;
        </a>
        <a href="{{ route('admin.blogs.create') }}" class="action-btn primary">
          + Create Article
        </a>
      </div>
    </header>

    <!-- Page Body -->
    <div class="admin-body">
      @if(session('success'))
        <div style="background: #e8f5e9; border-left: 4px solid #2e7d32; color: #1b5e20; padding: 14px 20px; border-radius: 6px; margin-bottom: 24px; font-size: 13.5px; font-weight: 600; display: flex; align-items: center; justify-content: space-between;">
          <span>{{ session('success') }}</span>
          <button type="button" onclick="this.parentElement.remove()" style="background:none; border:none; color:inherit; font-size:16px;">&times;</button>
        </div>
      @endif

      @if(session('error'))
        <div style="background: #ffebee; border-left: 4px solid var(--crimson); color: var(--crimson); padding: 14px 20px; border-radius: 6px; margin-bottom: 24px; font-size: 13.5px; font-weight: 600; display: flex; align-items: center; justify-content: space-between;">
          <span>{{ session('error') }}</span>
          <button type="button" onclick="this.parentElement.remove()" style="background:none; border:none; color:inherit; font-size:16px;">&times;</button>
        </div>
      @endif

      @if($errors->any())
        <div style="background: #fee2e2; border-left: 4px solid #dc2626; color: #991b1b; padding: 14px 20px; border-radius: 6px; margin-bottom: 24px; font-size: 13.5px; font-weight: 600;">
          <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
            <span>⚠️ कृपया निम्न त्रुटियों को सुधारें (Please correct the following errors):</span>
            <button type="button" onclick="this.parentElement.parentElement.remove()" style="background:none; border:none; color:inherit; font-size:16px; cursor:pointer;">&times;</button>
          </div>
          <ul style="margin: 4px 0 0 20px; font-size: 13px; font-weight: 500;">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @yield('content')
    </div>
  </main>
</div>

<!-- Floating Toast Notification Box -->
<div class="toast-box" id="toastContainer"></div>

<script>
  function toggleAdminSidebar(show) {
    const sb = document.getElementById('adminSidebar');
    const bd = document.getElementById('sidebarBackdrop');
    if (show) {
      sb.classList.add('open');
      bd.classList.add('open');
      document.body.style.overflow = 'hidden';
    } else {
      sb.classList.remove('open');
      bd.classList.remove('open');
      document.body.style.overflow = '';
    }
  }

  function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        ${type === 'success' ? '<polyline points="20 6 9 17 4 12"></polyline>' : '<circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line>'}
      </svg>
      <span>${message}</span>
    `;
    container.appendChild(toast);
    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateY(10px)';
      toast.style.transition = 'all 0.3s ease';
      setTimeout(() => toast.remove(), 300);
    }, 3500);
  }

  function triggerCacheFlush() {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    fetch("{{ route('admin.flushCache') }}", {
      method: "POST",
      headers: {
        "X-CSRF-TOKEN": token,
        "Accept": "application/json",
        "Content-Type": "application/json"
      }
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        showToast(data.message, 'success');
      } else {
        showToast(data.message || 'Cache flush failed', 'error');
      }
    })
    .catch(err => {
      showToast('Cache flush failed: ' + err.message, 'error');
    });
  }
  /* Real-Time Admin Notification Center Controller */
  let adminKnownNotificationIds = new Set();
  let adminNotifPollTimer = null;

  function toggleAdminNotifDropdown() {
    const panel = document.getElementById('adminNotifPanel');
    if (!panel) return;
    const isVisible = panel.style.display === 'flex';
    if (!isVisible) {
      panel.style.display = 'flex';
      markAdminNotifSeen();
    } else {
      panel.style.display = 'none';
    }
  }

  // Close panel on click outside
  document.addEventListener('click', function(e) {
    const wrap = document.getElementById('adminNotifWrap');
    const panel = document.getElementById('adminNotifPanel');
    if (wrap && panel && !wrap.contains(e.target)) {
      panel.style.display = 'none';
    }
  });

  function markAdminNotifSeen() {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const badge = document.getElementById('adminNotifBadge');
    const headerBadge = document.getElementById('adminNotifHeaderBadge');
    if (badge) badge.style.display = 'none';
    if (headerBadge) headerBadge.style.display = 'none';
    
    // Mark visually in list
    document.querySelectorAll('.notif-row-item.unread').forEach(el => el.classList.remove('unread'));

    fetch("{{ route('admin.notifications.markSeen') }}", {
      method: "POST",
      headers: {
        "X-CSRF-TOKEN": token,
        "Accept": "application/json",
        "Content-Type": "application/json"
      }
    }).catch(() => {});
  }

  function fetchAdminNotifications(isInitial = false) {
    fetch("{{ route('admin.notifications.poll') }}", {
      headers: { "Accept": "application/json" }
    })
    .then(r => r.json())
    .then(data => {
      const badge = document.getElementById('adminNotifBadge');
      const headerBadge = document.getElementById('adminNotifHeaderBadge');
      const list = document.getElementById('adminNotifList');

      if (badge && headerBadge) {
        if (data.unread_count > 0) {
          badge.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
          badge.style.display = 'inline-block';
          headerBadge.textContent = data.unread_count;
          headerBadge.style.display = 'inline-block';
        } else {
          badge.style.display = 'none';
          headerBadge.style.display = 'none';
        }
      }

      if (list && data.notifications) {
        if (data.notifications.length === 0) {
          list.innerHTML = '<div style="padding: 30px 20px; text-align: center; color: #888; font-size: 12px;">कोई नई सूचना उपलब्ध नहीं है (No notifications yet)</div>';
        } else {
          let html = '';
          data.notifications.forEach((item) => {
            const isUnread = item.is_unread;
            html += `
              <a href="${item.url}" class="notif-row-item ${isUnread ? 'unread' : ''}">
                <div class="notif-item-icon">${item.icon}</div>
                <div class="notif-item-content">
                  <div class="notif-item-title">${item.title}</div>
                  <div class="notif-item-desc">${item.description}</div>
                  <div class="notif-item-time">${item.time_ago}</div>
                </div>
              </a>
            `;

            // Detect newly arrived notifications during active session
            if (!isInitial && isUnread && !adminKnownNotificationIds.has(item.id)) {
              showToast(`${item.icon} ${item.title}: ${item.description}`, 'info');
            }
            adminKnownNotificationIds.add(item.id);
          });
          list.innerHTML = html;
        }
      }
    })
    .catch(() => {});
  }

  document.addEventListener('DOMContentLoaded', function() {
    fetchAdminNotifications(true);
    // Poll every 12 seconds for immediate real-time updates without page refresh
    adminNotifPollTimer = setInterval(() => fetchAdminNotifications(false), 12000);
  });
</script>
@include('admin.media-picker-modal')
@yield('scripts')
</body>
</html>
