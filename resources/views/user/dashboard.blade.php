@extends('layouts.app')

@section('title', 'सदस्य डैशबोर्ड एवं प्रोफाइल | श्री कामदगिरि पीठम् सेवा ट्रस्ट')

@section('styles')
  <style>
    /* Devotee Notification Center & Real-Time Alerts */
    .devotee-notif-wrap {
      position: relative;
      display: inline-block;
    }

    .devotee-notif-btn {
      position: relative;
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 9px 16px;
      background: #FFFDF9;
      border: 1.5px solid rgba(180, 83, 9, 0.35);
      border-radius: 9999px;
      color: #78350F;
      font-size: 12px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.2s ease;
      box-shadow: 0 2px 8px rgba(180, 83, 9, 0.08);
    }

    .devotee-notif-btn:hover {
      background: #FEF3C7;
      border-color: #B45309;
      transform: translateY(-1px);
    }

    .devotee-notif-badge {
      background: #DC2626;
      color: #fff;
      font-size: 10px;
      font-weight: 800;
      padding: 1px 6px;
      border-radius: 9999px;
      min-width: 16px;
      text-align: center;
      line-height: 14px;
      animation: devoteePillPulse 2s infinite ease-in-out;
    }

    @keyframes devoteePillPulse {
      0% {
        transform: scale(1);
      }

      50% {
        transform: scale(1.15);
        box-shadow: 0 0 10px rgba(220, 38, 38, 0.6);
      }

      100% {
        transform: scale(1);
      }
    }

    .devotee-notif-panel {
      position: absolute;
      top: calc(100% + 10px);
      left: 0;
      width: 360px;
      max-width: 90vw;
      background: #FFFDF9;
      border: 1.5px solid rgba(180, 83, 9, 0.3);
      border-radius: 14px;
      box-shadow: 0 16px 40px rgba(43, 26, 18, 0.18);
      z-index: 9999;
      display: none;
      flex-direction: column;
      overflow: hidden;
      animation: devoteeNotifFade 0.2s ease-out forwards;
    }

    @keyframes devoteeNotifFade {
      from {
        opacity: 0;
        transform: translateY(-8px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .devotee-notif-header {
      padding: 12px 16px;
      background: #FAF4ED;
      border-bottom: 1px solid rgba(180, 83, 9, 0.15);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .devotee-notif-list {
      max-height: 360px;
      overflow-y: auto;
      display: flex;
      flex-direction: column;
    }

    .devotee-notif-item {
      padding: 12px 14px;
      border-bottom: 1px solid rgba(180, 83, 9, 0.08);
      display: flex;
      align-items: flex-start;
      gap: 10px;
      text-decoration: none;
      color: inherit;
      transition: background 0.15s ease;
    }

    .devotee-notif-item:hover {
      background: #FEF3C7;
    }

    .devotee-notif-item.unread {
      background: rgba(245, 158, 11, 0.12);
    }

    .devotee-toast-container {
      position: fixed;
      bottom: 24px;
      right: 24px;
      z-index: 99999;
      display: flex;
      flex-direction: column;
      gap: 10px;
      max-width: 380px;
    }

    .devotee-live-toast {
      background: #FFFDF9;
      border: 2px solid #D97706;
      border-radius: 12px;
      padding: 14px 18px;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.18);
      display: flex;
      align-items: flex-start;
      gap: 12px;
      animation: toastPopIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }

    @keyframes toastPopIn {
      from {
        opacity: 0;
        transform: translateY(20px) scale(0.92);
      }

      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    /* Responsive Quotation Alert Banner */
    .quotation-alert-card {
      background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%);
      border: 2px solid #F59E0B;
      border-radius: 16px;
      padding: 18px 22px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      box-shadow: 0 8px 22px rgba(217, 119, 6, 0.16);
    }

    .quotation-alert-left {
      display: flex;
      align-items: center;
      gap: 14px;
      flex: 1;
      min-width: 0;
    }

    .quotation-alert-btn {
      background: #B45309;
      color: #fff !important;
      font-weight: 800;
      white-space: nowrap;
      padding: 11px 24px;
      border-radius: 9999px;
      text-decoration: none;
      box-shadow: 0 4px 14px rgba(180, 83, 9, 0.3);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      font-size: 13px;
      transition: all 0.2s ease;
      flex-shrink: 0;
    }

    .quotation-alert-btn:hover {
      background: #92400E;
      transform: translateY(-1px);
    }

    /* Devotee Mobile Segmented Navigation */
    .devotee-mobile-segment-wrap {
      display: none;
    }

    .modal-drag-handle {
      display: none;
    }

    /* Desktop Dashboard Master Architecture */
    .dashboard-grid {
      display: grid !important;
      grid-template-columns: 360px minmax(0, 1fr) !important;
      gap: 28px !important;
      align-items: start !important;
    }

    .dashboard-header-card {
      background: #FFFDF9 !important;
      border: 1.5px solid rgba(180, 83, 9, 0.22) !important;
      border-radius: 20px !important;
      padding: 26px 30px !important;
      margin-bottom: 28px !important;
      box-shadow: 0 12px 35px rgba(130, 70, 20, 0.05) !important;
      display: flex !important;
      flex-direction: column !important;
      gap: 20px !important;
    }

    .header-card-top {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 20px;
      width: 100%;
    }

    .dashboard-user-info {
      display: flex;
      align-items: center;
      gap: 20px;
    }

    .dashboard-avatar-medallion {
      width: 64px;
      height: 64px;
      border-radius: 50%;
      background: linear-gradient(135deg, #8F1719 0%, #B45309 100%);
      color: #FFFDF9;
      border: 3px solid #FEF3C7;
      box-shadow: 0 4px 14px rgba(143, 23, 25, 0.25);
      display: grid;
      place-items: center;
      font-size: 26px;
      font-weight: 700;
      font-family: var(--serif);
      flex-shrink: 0;
    }

    .dashboard-avatar-img {
      width: 64px;
      height: 64px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #FEF3C7;
      box-shadow: 0 4px 14px rgba(143, 23, 25, 0.25);
      flex-shrink: 0;
    }

    .dashboard-badge-row {
      display: flex;
      align-items: center;
      gap: 8px;
      flex-wrap: wrap;
      margin-bottom: 4px;
    }

    .dashboard-role-badge {
      background: rgba(180, 83, 9, 0.12);
      border: 1px solid rgba(180, 83, 9, 0.3);
      color: #92400E;
      font-size: 11px;
      font-weight: 800;
      letter-spacing: 0.05em;
      padding: 3px 10px;
      border-radius: 9999px;
    }

    .dashboard-devotee-pill {
      background: #FEF3C7;
      color: #78350F;
      font-size: 11px;
      font-weight: 700;
      padding: 3px 10px;
      border-radius: 9999px;
      font-family: var(--dev);
    }

    .dashboard-title {
      font: 700 clamp(22px, 2.5vw, 28px) var(--dev);
      color: #2B1A12;
      margin: 4px 0 6px;
      line-height: 1.25;
    }

    .dashboard-meta-row {
      display: flex;
      align-items: center;
      gap: 16px;
      flex-wrap: wrap;
      font-size: 13px;
      color: #6B7280;
    }

    .dashboard-meta-row span {
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .devotee-stats-bar {
      background: #FAF5EE;
      border: 1px solid rgba(180, 83, 9, 0.14);
      border-radius: 14px;
      padding: 14px 24px;
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 16px;
      align-items: center;
    }

    .stat-cell {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .stat-icon {
      font-size: 22px;
      line-height: 1;
      width: 40px;
      height: 40px;
      border-radius: 10px;
      background: rgba(180, 83, 9, 0.1);
      display: grid;
      place-items: center;
      flex-shrink: 0;
    }

    .stat-num {
      font: 700 16px var(--dev);
      color: #2B1A12;
      line-height: 1.2;
    }

    .stat-label {
      font-size: 11px;
      font-weight: 700;
      color: #78350F;
      margin-top: 2px;
    }

    .stat-divider {
      width: 1px;
      height: 36px;
      background: rgba(180, 83, 9, 0.15);
    }

    .sacred-card-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding-bottom: 14px;
      border-bottom: 1.5px solid rgba(180, 83, 9, 0.16);
      margin-bottom: 18px;
    }

    .sacred-title-group {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .sacred-title-badge {
      width: 34px;
      height: 34px;
      border-radius: 8px;
      background: rgba(180, 83, 9, 0.12);
      color: #92400E;
      display: grid;
      place-items: center;
      font-size: 17px;
      flex-shrink: 0;
    }

    .sacred-card-title {
      font: 700 17px var(--dev);
      color: #2B1A12;
      margin: 0;
      line-height: 1.2;
    }

    .sacred-card-subtitle {
      font-size: 11px;
      color: #78350F;
      font-weight: 600;
      display: block;
      margin-top: 2px;
    }

    .btn-micro-edit {
      background: rgba(180, 83, 9, 0.08);
      border: 1px solid rgba(180, 83, 9, 0.25);
      color: #B45309;
      border-radius: 9999px;
      padding: 5px 14px;
      font-size: 11.5px;
      font-weight: 700;
      cursor: pointer;
      white-space: nowrap;
      transition: all 0.2s ease;
    }

    .btn-micro-edit:hover {
      background: #FEF3C7;
      border-color: #B45309;
      transform: translateY(-1px);
    }

    .profile-details-list {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .profile-item {
      background: #FAF5EE;
      border: 1px solid rgba(180, 83, 9, 0.12);
      border-radius: 12px;
      padding: 10px 14px;
      display: flex;
      align-items: center;
      gap: 12px;
      transition: all 0.2s ease;
    }

    .profile-item:hover {
      background: #FEF3C7;
      border-color: rgba(180, 83, 9, 0.28);
      transform: translateY(-1px);
    }

    .profile-item-icon {
      width: 32px;
      height: 32px;
      border-radius: 8px;
      background: rgba(180, 83, 9, 0.1);
      display: grid;
      place-items: center;
      font-size: 15px;
      flex-shrink: 0;
    }

    .profile-item-content {
      flex: 1;
      min-width: 0;
    }

    .profile-item-content strong {
      font-size: 10.5px;
      color: #78350F;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      display: block;
      margin-bottom: 2px;
      line-height: 1.2;
    }

    .profile-item-content span {
      font-size: 13.5px;
      color: #2B1A12;
      font-weight: 600;
      line-height: 1.4;
      word-break: break-word;
      display: block;
    }

    .devotee-royal-card {
      background: linear-gradient(135deg, #241A14 0%, #3D2B1F 50%, #1A120D 100%) !important;
      border: 1.5px solid #D4B996 !important;
      border-radius: 18px !important;
      padding: 22px !important;
      color: #FFFFFF !important;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25) !important;
      position: relative;
      overflow: hidden;
    }

    .royal-card-inner {
      position: relative;
      z-index: 2;
    }

    .royal-card-top-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 16px;
    }

    .royal-tier-badge {
      background: #D4B996;
      color: #241A14;
      padding: 4px 10px;
      border-radius: 9999px;
      font: 800 9px var(--sans);
      letter-spacing: 0.12em;
      text-transform: uppercase;
    }

    .royal-trust-title {
      font: 600 14px var(--serif);
      color: #FFFFFF;
      line-height: 1.3;
    }

    .royal-trust-sub {
      font: 700 8px var(--sans);
      letter-spacing: 0.2em;
      color: #D4B996;
      margin-top: 2px;
      margin-bottom: 16px;
    }

    .royal-card-meta {
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
      border-top: 1px solid rgba(255, 255, 255, 0.15);
      padding-top: 14px;
    }

    .royal-devotee-name {
      font: 700 16px var(--dev);
      color: #FFFFFF;
    }

    .royal-devotee-id {
      font-size: 11px;
      color: #D4B996;
      margin-top: 2px;
      font-family: monospace;
      font-weight: 700;
    }

    .btn-royal-view {
      margin-top: 16px;
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(212, 185, 150, 0.4);
      color: #FFFFFF !important;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      padding: 11px 16px;
      border-radius: 10px;
      font-size: 12.5px;
      font-weight: 700;
      text-decoration: none;
      transition: all 0.2s ease;
    }

    .btn-royal-view:hover {
      background: #D4B996;
      color: #241A14 !important;
      transform: translateY(-1px);
    }

    .dashboard-tabs {
      display: flex;
      gap: 8px;
      border-bottom: 2px solid rgba(180, 83, 9, 0.12);
      margin-bottom: 24px;
      overflow-x: auto;
      padding-bottom: 8px;
      scrollbar-width: none;
    }

    .dashboard-tabs::-webkit-scrollbar {
      display: none;
    }

    .dash-tab-btn {
      padding: 9px 18px;
      border-radius: 9999px;
      border: 1.5px solid rgba(180, 83, 9, 0.2);
      background: #FAF5EE;
      font: 700 12.5px var(--dev);
      color: #78350F;
      cursor: pointer;
      transition: all 0.2s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      white-space: nowrap;
    }

    .dash-tab-btn:hover {
      background: #FEF3C7;
      border-color: #B45309;
      transform: translateY(-1px);
    }

    .dash-tab-btn.active-dash-tab {
      background: #8F1719 !important;
      color: #FFFFFF !important;
      border-color: #8F1719 !important;
      box-shadow: 0 4px 14px rgba(143, 23, 25, 0.25) !important;
    }

    .dash-tab-btn.active-dash-tab .dash-tab-count {
      background: rgba(255, 255, 255, 0.28);
      color: #FFFFFF;
    }

    .dash-tab-count {
      background: rgba(180, 83, 9, 0.14);
      color: #78350F;
      padding: 1px 7px;
      border-radius: 9999px;
      font-size: 11px;
      font-weight: 800;
    }

    .empty-sacred-state {
      padding: 38px 24px;
      text-align: center;
      background: #FAF5EE;
      border: 1.5px dashed rgba(180, 83, 9, 0.25);
      border-radius: 18px;
      margin: 10px 0;
    }

    .sacred-diya-halo {
      width: 64px;
      height: 64px;
      border-radius: 50%;
      background: #FEF3C7;
      border: 1.5px solid #F59E0B;
      display: grid;
      place-items: center;
      margin: 0 auto 16px;
      box-shadow: 0 0 20px rgba(245, 158, 11, 0.25);
    }

    .sacred-diya-icon {
      font-size: 28px;
      line-height: 1;
    }

    .empty-sacred-title {
      font: 700 20px var(--dev);
      color: #78350F;
      margin: 0 0 8px;
    }

    .empty-sacred-desc {
      font-size: 13.5px;
      color: #6B7280;
      max-width: 520px;
      margin: 0 auto 20px;
      line-height: 1.6;
    }

    .empty-quick-causes {
      display: flex;
      justify-content: center;
      gap: 12px;
      flex-wrap: wrap;
      margin-bottom: 22px;
    }

    .quick-cause-card {
      background: #FFFFFF;
      border: 1px solid rgba(180, 83, 9, 0.2);
      border-radius: 12px;
      padding: 9px 16px;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      color: #78350F;
      text-decoration: none;
      font-size: 12.5px;
      font-weight: 700;
      transition: all 0.2s ease;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    }

    .quick-cause-card:hover {
      background: #FEF3C7;
      border-color: #B45309;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(180, 83, 9, 0.15);
    }

    /* Mobile Dashboard Enhancements */
    @media (max-width: 860px) {
      .dashboard-grid {
        grid-template-columns: 1fr !important;
        gap: 18px !important;
      }

      .dashboard-tabs {
        display: none !important;
      }

      .dashboard-section {
        padding-top: 100px !important;
        padding-bottom: 120px !important;
      }

      .dashboard-card {
        padding: 18px 14px !important;
        border-radius: 16px !important;
        box-shadow: 0 4px 16px rgba(130, 70, 20, 0.05) !important;
      }

      .dashboard-header-card {
        padding: 18px 14px !important;
        border-radius: 16px !important;
      }

      .devotee-stats-bar {
        grid-template-columns: 1fr 1fr !important;
        gap: 10px !important;
        padding: 12px 14px !important;
      }

      .stat-divider {
        display: none !important;
      }

      .dashboard-main-content {
        padding: 18px 14px !important;
      }

      .dashboard-user-info {
        flex-direction: column !important;
        align-items: center !important;
        text-align: center !important;
        gap: 12px !important;
      }

      .dashboard-title {
        font-size: clamp(20px, 5.5vw, 24px) !important;
        margin: 4px 0 !important;
      }

      .dashboard-role-badge {
        margin: 0 auto 6px !important;
        display: inline-block !important;
      }

      .dashboard-meta-row {
        justify-content: center !important;
        font-size: 12px !important;
        gap: 10px !important;
      }

      .dashboard-meta {
        font-size: 13px !important;
        word-break: break-all !important;
      }

      .devotee-mobile-segment-wrap {
        display: flex !important;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 8px;
        margin-bottom: 18px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
      }

      .devotee-mobile-segment-wrap::-webkit-scrollbar {
        display: none;
      }

      .devotee-segment-btn {
        flex-shrink: 0;
        background: #FFFDF9;
        border: 1.5px solid rgba(180, 83, 9, 0.25);
        color: #78350F;
        padding: 9px 16px;
        border-radius: 9999px;
        font-size: 13px;
        font-weight: 700;
        font-family: var(--dev);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        -webkit-tap-highlight-color: transparent;
      }

      .devotee-segment-btn:active {
        transform: scale(0.96);
      }

      .devotee-segment-btn.active {
        background: #8F1719 !important;
        color: #ffffff !important;
        border-color: #8F1719 !important;
        box-shadow: 0 4px 12px rgba(143, 23, 25, 0.25);
      }

      .devotee-segment-count {
        background: rgba(180, 83, 9, 0.15);
        color: #78350F;
        padding: 1px 7px;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 800;
      }

      .devotee-segment-btn.active .devotee-segment-count {
        background: rgba(255, 255, 255, 0.28);
        color: #ffffff;
      }

      .devotee-notif-panel {
        position: fixed !important;
        top: 130px !important;
        left: 14px !important;
        right: 14px !important;
        width: auto !important;
        max-width: calc(100vw - 28px) !important;
        box-shadow: 0 20px 50px rgba(43, 26, 18, 0.35) !important;
      }

      .dashboard-actions {
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 10px !important;
        width: 100% !important;
        margin-top: 14px !important;
      }

      .dashboard-actions .dashboard-logout-form {
        display: contents !important;
      }

      .dashboard-actions .devotee-notif-wrap {
        width: 100% !important;
        display: block !important;
      }

      .dashboard-actions .devotee-notif-btn,
      .dashboard-actions .btn-card-dl,
      .dashboard-actions .btn-edit-profile,
      .dashboard-actions .btn-logout {
        width: 100% !important;
        min-height: 44px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 6px !important;
        text-align: center !important;
        box-sizing: border-box !important;
        margin: 0 !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
        transition: transform 0.15s ease !important;
      }

      .dashboard-actions .btn-card-dl:active,
      .dashboard-actions .btn-edit-profile:active,
      .dashboard-actions .btn-logout:active,
      .dashboard-actions .devotee-notif-btn:active {
        transform: scale(0.97) !important;
      }

      .quotation-alert-card {
        flex-direction: column !important;
        align-items: stretch !important;
        padding: 16px 14px !important;
        gap: 14px !important;
      }

      .quotation-alert-left {
        align-items: flex-start !important;
        gap: 10px !important;
      }

      .quotation-alert-btn {
        width: 100% !important;
        min-height: 44px !important;
        box-sizing: border-box !important;
        text-align: center !important;
        white-space: normal !important;
        padding: 12px 18px !important;
      }

      .profile-details-list {
        display: flex !important;
        flex-direction: column !important;
        gap: 10px !important;
      }

      .profile-item {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: flex-start !important;
        background: #FAF5EE !important;
        border: 1px solid rgba(180, 83, 9, 0.12) !important;
        border-radius: 12px !important;
        padding: 11px 14px !important;
        border-bottom: none !important;
        gap: 12px !important;
      }

      .profile-item-icon {
        display: flex !important;
        width: 34px !important;
        height: 34px !important;
        border-radius: 8px !important;
        background: rgba(180, 83, 9, 0.12) !important;
        color: #92400E !important;
        align-items: center !important;
        justify-content: center !important;
        flex-shrink: 0 !important;
        font-size: 16px !important;
      }

      .profile-item-content {
        flex: 1 !important;
        min-width: 0 !important;
      }

      .profile-item strong {
        font-size: 11px !important;
        color: #78350F !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.04em !important;
        display: block !important;
        margin-bottom: 2px !important;
        line-height: 1.2 !important;
      }

      .profile-item span {
        font-size: 13.5px !important;
        color: #2B1A12 !important;
        font-weight: 600 !important;
        text-align: left !important;
        word-break: break-word !important;
        line-height: 1.4 !important;
        display: block !important;
        width: 100% !important;
      }

      .donation-receipt-row {
        background: #FFFFFF !important;
        border: 1.5px solid rgba(180, 83, 9, 0.16) !important;
        border-radius: 14px !important;
        padding: 16px 14px !important;
        margin-bottom: 14px !important;
        box-shadow: 0 2px 8px rgba(43, 26, 18, 0.04) !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 12px !important;
      }

      .donation-receipt-row .donation-row-right {
        width: 100% !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 8px !important;
        margin-top: 4px !important;
      }

      .donation-receipt-row .btn-receipt-view,
      .donation-receipt-row .btn.primary {
        width: 100% !important;
        min-height: 42px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        border-radius: 8px !important;
        box-sizing: border-box !important;
      }

      #profileEditModal {
        align-items: flex-end !important;
        padding: 0 !important;
      }

      #profileEditModal > div {
        padding: 16px 18px 28px !important;
        border-radius: 22px 22px 0 0 !important;
        max-height: 88vh !important;
        width: 100% !important;
        box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.25) !important;
        margin: 0 !important;
      }

      .modal-drag-handle {
        display: block !important;
        width: 44px;
        height: 5px;
        background: #D1D5DB;
        border-radius: 9999px;
        margin: 0 auto 12px;
      }

      #profileEditModal input,
      #profileEditModal select,
      #profileEditModal textarea {
        font-size: 16px !important;
      }

      .profile-modal-grid {
        grid-template-columns: 1fr !important;
        gap: 12px !important;
      }

      .profile-modal-grid > div {
        grid-column: span 1 !important;
      }

      .profile-modal-actions {
        position: sticky;
        bottom: -28px;
        background: #FFFDF9;
        padding-top: 14px;
        padding-bottom: 14px;
        border-top: 1px solid rgba(180, 83, 9, 0.16);
        margin-top: 18px;
        z-index: 10;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
      }

      .devotee-toast-container {
        left: 14px !important;
        right: 14px !important;
        bottom: 80px !important;
        max-width: calc(100vw - 28px) !important;
      }
    }
  </style>
@endsection

@section('content')

  <section class="section dashboard-section" style="padding-top: 140px; background: var(--paper); min-height: 85vh;">
    <div class="container" style="max-width: 1220px;">

      <!-- Premium Sacred Devotee Masthead -->
      <div class="dashboard-header-card">
        <div class="header-card-top">
          <div class="dashboard-user-info">
            @if($user->profile?->photo_path)
              <img src="{{ asset('storage/' . $user->profile->photo_path) }}" alt="{{ $user->name }}"
                class="dashboard-avatar-img">
            @else
              <div class="dashboard-avatar-medallion">
                <span>{{ strtoupper(substr($user->name, 0, 1)) }}</span>
              </div>
            @endif
            <div class="dashboard-user-titles">
              <div class="dashboard-badge-row">
                <span class="dashboard-role-badge">
                  ✨ {{ strtoupper($user->role) }} ACCOUNT
                </span>
                <span class="dashboard-devotee-pill">
                  श्री कामदगिरि अनन्य भक्त
                </span>
              </div>
              <h1 class="dashboard-title">प्रणाम, {{ $user->name }}</h1>
              <div class="dashboard-meta-row">
                <span>
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                  </svg>
                  {{ $user->email }}
                </span>
                <span>
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                  </svg>
                  {{ $user->phone ?? 'संपर्क नंबर उपलब्ध नहीं' }}
                </span>
                <span>
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="16" rx="2"/>
                    <line x1="7" y1="8" x2="17" y2="8"/>
                    <line x1="7" y1="12" x2="13" y2="12"/>
                  </svg>
                  ID: {{ $user->membership?->membership_id ?? ('SKP-MEM-' . strtoupper(substr(md5($user->id . 'salt'), 0, 6))) }}
                </span>
              </div>
            </div>
          </div>

          <div class="dashboard-actions">
            <!-- Devotee Notification Center Bell & Dropdown -->
            <div class="devotee-notif-wrap" id="devoteeNotifWrap">
              <button type="button" class="devotee-notif-btn" id="devoteeNotifBtn" onclick="toggleDevoteeNotifDropdown()"
                aria-label="Devotee Notifications">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                  <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
                <span>सूचनाएं</span>
                <span class="devotee-notif-badge" id="devoteeNotifBadge" style="display: none;">0</span>
              </button>

              <div class="devotee-notif-panel" id="devoteeNotifPanel">
                <div class="devotee-notif-header">
                  <div style="font-size: 13px; font-weight: 800; color: #78350F; font-family: var(--dev);">
                    🔔 Notifications
                  </div>
                  <button type="button" onclick="markDevoteeNotifSeen()"
                    style="background: none; border: none; color: #B45309; font-size: 11px; font-weight: 700; cursor: pointer; text-decoration: underline;">
                    Mark as Read
                  </button>
                </div>
                <div class="devotee-notif-list" id="devoteeNotifList">
                  <div style="padding: 24px; text-align: center; color: #888; font-size: 12px;">
                    Checking updates...
                  </div>
                </div>
              </div>
            </div>

            <a href="{{ url('/user/card') }}" class="btn primary btn-card-dl">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="16" rx="2" />
                <line x1="7" y1="8" x2="17" y2="8" />
                <line x1="7" y1="12" x2="13" y2="12" />
              </svg>
              <span>पहचान पत्र (ID Card)</span>
            </a>
            <button type="button" onclick="openProfileModal()" class="btn btn-edit-profile"
              style="background: rgba(180, 83, 9, 0.08); color: #B45309; border: 1px solid rgba(180, 83, 9, 0.25);">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
              </svg>
              <span>संशोधन (Edit Profile)</span>
            </button>
            <form action="{{ url('/logout') }}" method="POST" class="dashboard-logout-form"
              style="margin: 0; display: inline;">
              @csrf
              <button type="submit" class="btn dark btn-logout">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                  <polyline points="16 17 21 12 16 7"/>
                  <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                <span>Logout</span>
              </button>
            </form>
          </div>
        </div>

        <!-- Devotee Quick Metric Highlights Bar -->
        <div class="devotee-stats-bar">
          <div class="stat-cell">
            <span class="stat-icon">🙏</span>
            <div>
              <div class="stat-num">₹{{ number_format($donations->where('status', 'verified')->sum('amount'), 0) }}</div>
              <div class="stat-label">समर्पित दान (Total Seva)</div>
            </div>
          </div>
          <div class="stat-divider"></div>
          <div class="stat-cell">
            <span class="stat-icon">🪔</span>
            <div>
              <div class="stat-num">{{ $poojaBookings->count() }}</div>
              <div class="stat-label">पूजा संकल्प (Poojas)</div>
            </div>
          </div>
          <div class="stat-divider"></div>
          <div class="stat-cell">
            <span class="stat-icon">🏨</span>
            <div>
              <div class="stat-num">{{ $atithiEnquiries->count() }}</div>
              <div class="stat-label">अतिथि विश्राम (Atithi)</div>
            </div>
          </div>
          <div class="stat-divider"></div>
          <div class="stat-cell">
            <span class="stat-icon">🪪</span>
            <div>
              <div class="stat-num" style="font-size: 14px; color: #8F1719;">
                @if(($user->membership?->status ?? '') === 'active')
                  स्वर्ण सक्रिय सदस्य
                @elseif($user->donations->count() > 0)
                  समर्थक भक्त
                @else
                  पंजीकृत सदस्य
                @endif
              </div>
              <div class="stat-label">सदस्यता स्थिति (Member Tier)</div>
            </div>
          </div>
        </div>
      </div>

      @if(session('success'))
        <div
          style="background: #ECFDF5; border: 1px solid #10B981; color: #065F46; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; font-size: 14px;">
          ✓ {{ session('success') }}
        </div>
      @endif
      @if($errors->any())
        <div
          style="background: #FEF2F2; border: 1px solid #EF4444; color: #991B1B; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
          <strong>कृपया त्रुटियां सुधारें:</strong>
          <ul style="margin: 6px 0 0 20px; padding: 0;">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <!-- Real-Time Quotation Dynamic Callout Banner -->
      <div id="devoteeLiveQuotationAlert" style="display: none; margin-bottom: 24px;"></div>

      <!-- Devotee Mobile Segmented Quick View Pills -->
      <div class="devotee-mobile-segment-wrap" id="devoteeMobileSegmentWrap">
        <button type="button" class="devotee-segment-btn active" data-target="profile" onclick="switchDevoteeMobileSection('profile', this)">
          <span>👤 प्रोफाइल</span>
        </button>
        <button type="button" class="devotee-segment-btn" data-target="tab-donations" onclick="switchDevoteeMobileSection('tab-donations', this)">
          <span>🙏 दान</span>
          <span class="devotee-segment-count">{{ $donations->count() }}</span>
        </button>
        <button type="button" class="devotee-segment-btn" data-target="tab-poojas" onclick="switchDevoteeMobileSection('tab-poojas', this)">
          <span>🪔 पूजा</span>
          <span class="devotee-segment-count">{{ $poojaBookings->count() }}</span>
        </button>
        <button type="button" class="devotee-segment-btn" data-target="tab-atithi" onclick="switchDevoteeMobileSection('tab-atithi', this)">
          <span>🏨 विश्राम</span>
          <span class="devotee-segment-count">{{ $atithiEnquiries->count() }}</span>
        </button>
        <button type="button" class="devotee-segment-btn" data-target="tab-temple-bookings" onclick="switchDevoteeMobileSection('tab-temple-bookings', this)">
          <span>🏛️ परिसर</span>
          <span class="devotee-segment-count">{{ $templeBookings->count() }}</span>
        </button>
        <button type="button" class="devotee-segment-btn" data-target="tab-volunteers" onclick="switchDevoteeMobileSection('tab-volunteers', this)">
          <span>🤝 सेवादार</span>
          <span class="devotee-segment-count">{{ $volunteerApplications->count() }}</span>
        </button>
      </div>

      <div class="dashboard-grid">

        <!-- Personal Profile & Membership Card Summary -->
        <div class="dashboard-sidebar" id="devoteeSidebarSection">

          <div class="dashboard-card">
            <div class="sacred-card-header">
              <div class="sacred-title-group">
                <div class="sacred-title-badge">👤</div>
                <div>
                  <h3 class="sacred-card-title">व्यक्तिगत विवरण</h3>
                  <span class="sacred-card-subtitle">Devotee Profile Details</span>
                </div>
              </div>
              <button type="button" onclick="openProfileModal()" class="btn-micro-edit">
                ✏️ Edit
              </button>
            </div>
            <div class="profile-details-list">
              <div class="profile-item">
                <div class="profile-item-icon">👤</div>
                <div class="profile-item-content">
                  <strong>नाम (Name):</strong>
                  <span>{{ $user->name }}</span>
                </div>
              </div>
              <div class="profile-item">
                <div class="profile-item-icon">📱</div>
                <div class="profile-item-content">
                  <strong>फोन (Phone):</strong>
                  <span>{{ $user->profile?->phone ?? $user->phone ?? 'N/A' }}</span>
                </div>
              </div>
              <div class="profile-item">
                <div class="profile-item-icon">🚻</div>
                <div class="profile-item-content">
                  <strong>लिंग (Gender):</strong>
                  <span>{{ ucfirst($user->profile?->gender ?? 'N/A') }}</span>
                </div>
              </div>
              <div class="profile-item">
                <div class="profile-item-icon">🎂</div>
                <div class="profile-item-content">
                  <strong>जन्म तिथि (DOB):</strong>
                  <span>{{ $user->profile?->date_of_birth ? \Carbon\Carbon::parse($user->profile->date_of_birth)->format('d M Y') : 'N/A' }}</span>
                </div>
              </div>
              <div class="profile-item">
                <div class="profile-item-icon">💼</div>
                <div class="profile-item-content">
                  <strong>व्यवसाय (Profession):</strong>
                  <span>{{ $user->profile?->occupation ?? 'N/A' }}</span>
                </div>
              </div>
              <div class="profile-item">
                <div class="profile-item-icon">📍</div>
                <div class="profile-item-content">
                  <strong>जिला / शहर (District/City):</strong>
                  <span>{{ $user->profile?->district ?? $user->profile?->city ?? 'N/A' }}</span>
                </div>
              </div>
              <div class="profile-item">
                <div class="profile-item-icon">🏠</div>
                <div class="profile-item-content">
                  <strong>पता (Address):</strong>
                  <span>{{ $user->profile?->address ?? 'N/A' }}</span>
                </div>
              </div>
            </div>

            <button type="button" onclick="openProfileModal()" class="btn primary"
              style="width: 100%; margin-top: 16px; justify-content: center; font-size: 13px; font-weight: 700; border-radius: 10px; padding: 11px;">
              ✏️ प्रोफाइल संशोधित करें (Edit Profile)
            </button>
          </div>

          <!-- Official Royal Devotee Pass Preview Card -->
          <div class="dashboard-card devotee-royal-card">
            <div class="royal-card-inner">
              <div class="royal-card-top-row">
                <div class="royal-chip-icon">
                  <svg width="28" height="22" viewBox="0 0 36 28" fill="none">
                    <rect width="36" height="28" rx="4" fill="#F59E0B" fill-opacity="0.25" stroke="#F59E0B" stroke-width="1.5"/>
                    <line x1="12" y1="0" x2="12" y2="28" stroke="#F59E0B" stroke-width="1.2"/>
                    <line x1="24" y1="0" x2="24" y2="28" stroke="#F59E0B" stroke-width="1.2"/>
                    <line x1="0" y1="14" x2="36" y2="14" stroke="#F59E0B" stroke-width="1.2"/>
                  </svg>
                </div>
                <span class="royal-tier-badge">
                  @if(($user->membership?->status ?? '') === 'active')
                    GOLD ACTIVE
                  @elseif($user->donations->count() > 0)
                    SUPPORTER PASS
                  @else
                    MEMBER PASS
                  @endif
                </span>
              </div>
              <div class="royal-trust-title">{{ $orgSettings->org_name ?? 'श्री कामदगिरि पीठम् सेवा ट्रस्ट' }}</div>
              <div class="royal-trust-sub">CHITRAKOOT DHAM · OFFICIAL DIGITAL ID</div>
              <div class="royal-card-meta">
                <div>
                  <div class="royal-devotee-name">{{ $user->name }}</div>
                  <div class="royal-devotee-id">ID: {{ $user->membership?->membership_id ?? ('SKP-MEM-' . strtoupper(substr(md5($user->id . 'salt'), 0, 6))) }}</div>
                </div>
                <div class="royal-qr-stub">
                  <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#D4B996" stroke-width="1.5">
                    <rect x="3" y="3" width="7" height="7"/>
                    <rect x="14" y="3" width="7" height="7"/>
                    <rect x="3" y="14" width="7" height="7"/>
                    <rect x="14" y="14" width="7" height="7"/>
                  </svg>
                </div>
              </div>
            </div>
            <a href="{{ url('/user/card') }}" class="btn-royal-view">
              <span>पहचान पत्र देखें (View Digital Card)</span>
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="5" y1="12" x2="19" y2="12"/>
                <polyline points="12 5 19 12 12 19"/>
              </svg>
            </a>
          </div>

        </div>

        <!-- Synchronized Devotee History (Donations, Poojas, Volunteering) -->
        <div class="dashboard-card dashboard-main-content" id="devoteeMainSection" style="padding: 24px 28px;">

          <!-- Sync Notice -->
          <div
            style="background: linear-gradient(135deg, #FFF8EE 0%, #FEF3C7 100%); border: 1px solid rgba(180, 83, 9, 0.22); border-radius: 12px; padding: 14px 18px; margin-bottom: 22px; display: flex; align-items: center; gap: 12px; font-size: 13px; color: #78350F; line-height: 1.6;">
            <span style="font-size: 22px; line-height: 1;">🛡️</span>
            <div>
              <strong>ईमेल संबद्धता सक्रिय (Guest History Synchronized):</strong> आपके ईमेल
              <code>{{ $user->email }}</code> से पूर्व में अतिथि के रूप में किए गए सभी सेवा दान, पूजा संकल्प एवं सेवादार
              आवेदन स्वतः आपके इस डैशबोर्ड से जोड़ दिए गए हैं।
            </div>
          </div>

          <!-- Section Switcher Tabs -->
          <div class="dashboard-tabs">
            <button type="button" class="dash-tab-btn active-dash-tab" onclick="switchDashTab('tab-donations', this)">
              <span>🙏 {{ ($orgSettings->is_80g_enabled ?? false) ? 'दान एवं 80G रसीदें' : 'दान एवं सेवा रसीदें' }}</span>
              <span class="dash-tab-count">{{ $donations->count() }}</span>
            </button>
            <button type="button" class="dash-tab-btn" onclick="switchDashTab('tab-poojas', this)">
              <span>🪔 पूजा व अनुष्ठान संकल्प</span>
              <span class="dash-tab-count">{{ $poojaBookings->count() }}</span>
            </button>
            <button type="button" class="dash-tab-btn" onclick="switchDashTab('tab-volunteers', this)">
              <span>🤝 सेवादार नामांकन</span>
              <span class="dash-tab-count">{{ $volunteerApplications->count() }}</span>
            </button>
            <button type="button" class="dash-tab-btn" onclick="switchDashTab('tab-atithi', this)">
              <span>🏨 अतिथि विश्राम</span>
              <span class="dash-tab-count">{{ $atithiEnquiries->count() }}</span>
            </button>
            <button type="button" class="dash-tab-btn" onclick="switchDashTab('tab-temple-bookings', this)">
              <span>🏛️ परिसर आरक्षण</span>
              <span class="dash-tab-count">{{ $templeBookings->count() }}</span>
            </button>
          </div>

          <!-- Tab 1: Donations & 80G Receipts -->
          <div id="tab-donations" class="dash-tab-content">
            <h3 class="dashboard-card-title"
              style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
              <span>{{ ($orgSettings->is_80g_enabled ?? false) ? 'दान एवं आधिकारिक 80G रसीद इतिहास' : 'दान एवं आधिकारिक रसीद इतिहास' }}</span>
              <a href="{{ url('/donate') }}" class="btn primary"
                style="font-size: 11.5px; padding: 7px 18px; border-radius: 9999px; white-space: nowrap;">+ Donate Now</a>
            </h3>

            @forelse($donations as $donation)
              <div class="donation-receipt-row">
                <div class="donation-row-left">
                  <div style="display: flex; align-items: center; gap: 10px;">
                    <strong class="donation-amount">₹{{ number_format($donation->amount, 2) }}</strong>
                    <span class="donation-status-pill"
                      style="background: {{ $donation->status === 'verified' ? 'rgba(16, 124, 65, 0.12)' : 'rgba(217, 119, 6, 0.12)' }}; color: {{ $donation->status === 'verified' ? '#059669' : '#D97706' }};">
                      {{ strtoupper($donation->status) }}
                    </span>
                  </div>
                  <span class="donation-cause-name" style="margin-top: 4px; display: inline-block;">
                    {{ $donation->cause?->title ?? ($donation->donation_type === 'monthly_sustaining' ? 'मासिक सेवा संकल्प (Monthly Sustaining)' : 'सामान्य सेवा (General Seva)') }}
                  </span>
                  <div class="donation-meta-info">
                    रसीद: <strong>{{ $donation->receipt_number ?? 'प्रक्रियाधीन' }}</strong> &bull; दिनांक:
                    {{ $donation->created_at->format('d M Y, h:i A') }}
                    @if($donation->donor_email && $donation->donor_email !== $user->email)
                      &bull; <span style="color: #92400E;">मूल अतिथि ईमेल: {{ $donation->donor_email }}</span>
                    @endif
                  </div>
                </div>
                <div class="donation-row-right">
                  @if($donation->receipt_number)
                    <a href="{{ url('/receipt/' . $donation->receipt_number) }}" target="_blank"
                      class="btn dark btn-receipt-view"
                      style="display: inline-flex; align-items: center; justify-content: center; gap: 6px; white-space: nowrap;">
                      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" y1="13" x2="8" y2="13" />
                        <line x1="16" y1="17" x2="8" y2="17" />
                      </svg>
                      View Receipt
                    </a>
                  @else
                    <span style="font-size: 11px; color: #9CA3AF;">रसीद शीघ्र उपलब्ध होगी</span>
                  @endif
                </div>
              </div>
            @empty
              <div class="empty-sacred-state">
                <div class="sacred-diya-halo">
                  <div class="sacred-diya-icon">🪔</div>
                </div>
                <h4 class="empty-sacred-title">श्री कामदगिरि धाम सेवा संकल्प</h4>
                <p class="empty-sacred-desc">
                  आपने इस खाते से अभी तक कोई ऑनलाइन दान दर्ज नहीं किया है। कामदगिरि पर्वत परिक्रमा एवं संत-सेवा में आपका प्रत्येक अंशदान धर्म-संस्कृति संरक्षण में समर्पित होता है।
                </p>
                <div class="empty-quick-causes">
                  <a href="{{ url('/donate') }}" class="quick-cause-card">
                    <span>🍲</span>
                    <span>साधु-संत अन्नक्षेत्र सेवा</span>
                  </a>
                  <a href="{{ url('/donate') }}" class="quick-cause-card">
                    <span>🐄</span>
                    <span>कामधेनु गौ सेवा</span>
                  </a>
                  <a href="{{ url('/donate') }}" class="quick-cause-card">
                    <span>🪔</span>
                    <span>नित्य दीपदान संकल्प</span>
                  </a>
                </div>
                <a href="{{ url('/donate') }}" class="btn primary"
                  style="font-size: 13px; padding: 12px 32px; border-radius: 9999px; white-space: nowrap; font-weight: 700; box-shadow: 0 6px 18px rgba(180, 83, 9, 0.25);">
                  🙏 अभी सेवा दान अर्पित करें (Donate Now)
                </a>
              </div>
            @endforelse
          </div>

          <!-- Tab 2: Pooja & Sankalp Bookings -->
          <div id="tab-poojas" class="dash-tab-content" style="display: none;">
            <h3 class="dashboard-card-title"
              style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
              <span>पूजा एवं अनुष्ठान संकल्प (Pooja & Rituals)</span>
              <a href="{{ url('/pooja/book') }}" class="btn primary"
                style="font-size: 11.5px; padding: 7px 18px; border-radius: 9999px; white-space: nowrap;">+ Book Pooja</a>
            </h3>

            @forelse($poojaBookings as $booking)
              <div class="donation-receipt-row"
                style="{{ $booking->isQuotationActive() ? 'border: 2px solid #D97706; background: #FFFDF9;' : '' }}">
                <div class="donation-row-left">
                  <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                    <strong
                      style="font: 700 16px var(--dev); color: #78350F;">{{ $booking->getEffectiveRitualTitle() }}</strong>

                    @if($booking->isQuotationActive())
                      <span class="donation-status-pill"
                        style="background: #FEF3C7; color: #B45309; border: 1px solid #F59E0B; font-weight: 800;">
                        ⚡ दक्षिणा कोटेशन प्राप्त (QUOTED)
                      </span>
                    @elseif($booking->status === 'confirmed')
                      <span class="donation-status-pill"
                        style="background: rgba(16, 124, 65, 0.12); color: #059669; font-weight: 800;">
                        ✓ अनुष्ठान पुष्ट (CONFIRMED)
                      </span>
                    @elseif($booking->isQuotationExpired())
                      <span class="donation-status-pill" style="background: #FEE2E2; color: #DC2626;">
                        कोटेशन समाप्त (EXPIRED)
                      </span>
                    @else
                      <span class="donation-status-pill" style="background: rgba(217, 119, 6, 0.12); color: #D97706;">
                        {{ strtoupper($booking->status) }} (समीक्षाधीन)
                      </span>
                    @endif
                  </div>

                  @if($booking->isQuotationActive())
                    <div
                      style="background: #FFF7ED; border: 1.5px dashed #D97706; border-radius: 8px; padding: 10px 14px; margin-top: 8px; font-size: 13px; color: #9A3412;">
                      <strong>स्वीकृत दक्षिणा:</strong> <span
                        style="font-size: 16px; font-weight: 800; color: #8F1719;">₹{{ number_format((float) $booking->quotation_amount, 2) }}</span>
                      &bull; <span style="font-size: 11.5px; color: #B45309;">⏳ वैधता:
                        {{ $booking->quotation_expires_at ? $booking->quotation_expires_at->diffForHumans() : 'सीमित समय' }}</span>
                      @if($booking->quotation_notes)
                        <div style="margin-top: 4px; font-size: 12px; color: #78350F;">{{ $booking->quotation_notes }}</div>
                      @endif
                    </div>
                  @endif

                  <div style="font-size: 13px; color: #4B5563; margin-top: 6px; line-height: 1.6;">
                    <strong>बुकिंग संदर्भ:</strong> <code
                      style="font-weight: 700; color: #9A3412;">{{ $booking->booking_number }}</code> &bull;
                    <strong>तिथि:</strong>
                    {{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') : 'N/A' }}
                    ({{ $booking->time_slot }})<br>
                    <strong>यजमान:</strong> {{ $booking->devotee_name }} &bull; <strong>गोत्र:</strong>
                    {{ $booking->gotra ?: 'Not Specified' }}
                    @if($booking->amount > 0)
                      &bull; <strong>प्राप्त दक्षिणा:</strong> ₹{{ number_format((float) $booking->amount, 2) }}
                    @endif
                    @if($booking->admin_notes)
                      <div
                        style="background: #F3F4F6; border-radius: 6px; padding: 6px 12px; margin-top: 6px; font-size: 12px; color: #374151;">
                        <strong>पुरोहित संदेश:</strong> {{ $booking->admin_notes }}
                      </div>
                    @endif
                  </div>
                </div>

                <div class="donation-row-right"
                  style="display: flex; flex-direction: column; gap: 8px; align-items: flex-end;">
                  @if($booking->isQuotationActive())
                    <a href="{{ $booking->getQuotationPaymentUrl() }}" class="btn primary"
                      style="font-size: 11px; padding: 9px 18px; border-radius: 8px; background: #B45309; color: #fff; font-weight: 800; text-decoration: none; box-shadow: 0 4px 12px rgba(180,83,9,0.25); white-space: nowrap;">
                      🔒 Pay Online (₹{{ number_format((float) $booking->quotation_amount, 0) }}) &rarr;
                    </a>
                  @endif

                  <a href="{{ route('pooja.confirmation', ['bookingNumber' => $booking->booking_number]) }}" target="_blank"
                    class="btn dark btn-receipt-view" style="font-size: 10.5px; padding: 8px 14px; white-space: nowrap;">
                    View Receipt &rarr;
                  </a>
                </div>
              </div>
            @empty
              <div class="empty-sacred-state">
                <div class="sacred-diya-halo">
                  <div class="sacred-diya-icon">🪔</div>
                </div>
                <h4 class="empty-sacred-title">पूजा एवं अनुष्ठान संकल्प</h4>
                <p class="empty-sacred-desc">
                  आपकी कोई सक्रिय पूजा अथवा अनुष्ठान संकल्प पंजीकृत नहीं है। कामदगिरि धाम में अपने नाम एवं गोत्र से विशेष पूजन, रुद्राभिषेक अथवा अखंड रामायण पाठ संकल्प लें।
                </p>
                <a href="{{ url('/pooja/book') }}" class="btn primary"
                  style="font-size: 13px; padding: 12px 30px; border-radius: 9999px; white-space: nowrap; font-weight: 700; box-shadow: 0 4px 14px rgba(180, 83, 9, 0.25);">
                  🪔 पूजा संकल्प बुक करें (Book Pooja)
                </a>
              </div>
            @endforelse
          </div>

          <!-- Tab 3: Volunteer Enrolments -->
          <div id="tab-volunteers" class="dash-tab-content" style="display: none;">
            <h3 class="dashboard-card-title"
              style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
              <span>सेवादार नामांकन एवं सेवा दल स्थिति</span>
              <a href="{{ url('/volunteer') }}" class="btn primary"
                style="font-size: 11.5px; padding: 7px 18px; border-radius: 9999px; white-space: nowrap;">+ Join Volunteer
                Seva</a>
            </h3>

            @forelse($volunteerApplications as $applicant)
              <div class="donation-receipt-row">
                <div class="donation-row-left">
                  <div style="display: flex; align-items: center; gap: 10px;">
                    <strong style="font: 700 16px var(--dev); color: #16100D;">सेवादार आवेदन
                      #VOL-{{ sprintf('%04d', $applicant->id) }}</strong>
                    <span class="donation-status-pill"
                      style="background: {{ $applicant->status === 'approved' ? 'rgba(16, 124, 65, 0.12)' : ($applicant->status === 'rejected' ? 'rgba(220, 38, 38, 0.12)' : 'rgba(217, 119, 6, 0.12)') }}; color: {{ $applicant->status === 'approved' ? '#059669' : ($applicant->status === 'rejected' ? '#DC2626' : '#D97706') }};">
                      @if($applicant->status === 'approved')
                        ✓ सेवादार दल स्वीकृत (APPROVED)
                      @elseif($applicant->status === 'rejected')
                        स्थगित / समीक्षा पूर्ण
                      @else
                        समीक्षाधीन (UNDER REVIEW)
                      @endif
                    </span>
                  </div>
                  <div style="font-size: 13px; color: #4B5563; margin-top: 5px; line-height: 1.6;">
                    <strong>चयनित सेवा क्षेत्र:</strong>
                    {{ is_array($applicant->seva_areas) ? implode(', ', $applicant->seva_areas) : ($applicant->seva_areas ?: 'सामान्य सेवा') }}<br>
                    <strong>उपलब्धता:</strong> {{ $applicant->availability ?: 'नियमित' }} &bull; <strong>स्थान:</strong>
                    {{ $applicant->city ?? 'चित्रकूट' }}, {{ $applicant->state ?? '' }} &bull; <strong>दिनांक:</strong>
                    {{ $applicant->created_at->format('d M Y') }}
                    @if($applicant->admin_notes)
                      <div
                        style="background: #F3F4F6; border-radius: 6px; padding: 6px 12px; margin-top: 6px; font-size: 12px; color: #374151;">
                        <strong>ट्रस्ट कार्यालय संदेश:</strong> {{ $applicant->admin_notes }}
                      </div>
                    @endif
                  </div>
                </div>
                <div class="donation-row-right">
                  <span style="font-size: 11px; font-weight: 700; color: #B45309;">
                    {{ $applicant->status === 'approved' ? 'सक्रिय सेवादार' : 'पंजीकृत आवेदन' }}
                  </span>
                </div>
              </div>
            @empty
              <div class="empty-sacred-state">
                <div class="sacred-diya-halo">
                  <div class="sacred-diya-icon">🤝</div>
                </div>
                <h4 class="empty-sacred-title">कामदगिरि सेवादार परिवार</h4>
                <p class="empty-sacred-desc">
                  आपने अभी तक सेवादार के रूप में पंजीकरण नहीं कराया है। कामदगिरि परिक्रमा मार्ग, अन्नक्षेत्र एवं मंदिर व्यवस्था में निस्वार्थ सेवा हेतु सेवा दल से जुड़ें।
                </p>
                <a href="{{ url('/volunteer') }}" class="btn primary"
                  style="font-size: 13px; padding: 12px 30px; border-radius: 9999px; white-space: nowrap; font-weight: 700; box-shadow: 0 4px 14px rgba(180, 83, 9, 0.25);">
                  🤝 सेवादार नामांकन करें (Join Volunteer Seva)
                </a>
              </div>
            @endforelse
          </div>

          <!-- Tab 4: Atithi Vishram (Stay Enquiries) -->
          <div id="tab-atithi" class="dash-tab-content" style="display: none;">
            <h3 class="dashboard-card-title"
              style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
              <span>अतिथि विश्राम गृह पूछताछ इतिहास (Guest Stay Enquiries)</span>
              <a href="{{ route('services.atithiVishram') }}" class="btn primary"
                style="font-size: 11.5px; padding: 7px 18px; border-radius: 9999px; white-space: nowrap; background: #2D6A4F; border-color: #2D6A4F;">+
                New Stay Enquiry</a>
            </h3>

            @forelse($atithiEnquiries as $enq)
              <div class="donation-receipt-row">
                <div class="donation-row-left">
                  <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 13.5px; font-weight: 700; color: #2B1A12;">
                      🏨 {{ $enq->getPurposeLabel() }}
                    </span>
                    @php $badge = $enq->getStatusBadge(); @endphp
                    <span class="badge"
                      style="padding: 3px 8px; border-radius: 9999px; font-size: 10px; font-weight: 700; border: 1px solid currentColor;">
                      {{ $badge['label'] }}
                    </span>
                  </div>
                  <div class="donation-meta-text" style="margin-top: 6px;">
                    <span>संदर्भ संख्या: <strong style="font-family: monospace;">#{{ $enq->enquiry_number }}</strong></span>
                    <span>&bull;</span>
                    <span>आगमन: <strong>{{ optional($enq->check_in_date)->format('d M Y') }}</strong> से
                      <strong>{{ optional($enq->check_out_date)->format('d M Y') }}</strong></span>
                    <span>&bull;</span>
                    <span>कक्ष: <strong>{{ $enq->getRoomPreferenceLabel() }}</strong> ({{ $enq->number_of_devotees }}
                      यात्री)</span>
                  </div>
                  @if($enq->admin_notes)
                    <div
                      style="font-size: 12px; color: #2D6A4F; margin-top: 6px; font-style: italic; background: rgba(45,106,79,0.06); padding: 6px 12px; border-radius: 6px;">
                      आश्रम संदेश: {{ $enq->admin_notes }}
                    </div>
                  @endif
                </div>
                <div class="donation-row-right">
                  <span style="font-size: 11.5px; font-weight: 700; color: #2D6A4F;">
                    {{ $enq->created_at->format('d M Y') }}
                  </span>
                </div>
              </div>
            @empty
              <div class="empty-sacred-state">
                <div class="sacred-diya-halo">
                  <div class="sacred-diya-icon">🏨</div>
                </div>
                <h4 class="empty-sacred-title">अतिथि विश्राम गृह सेवा</h4>
                <p class="empty-sacred-desc">
                  आपने अभी तक कामदगिरि पीठम् अतिथि विश्राम गृह में ठहरने की कोई पूछताछ दर्ज नहीं कराई है। तीर्थ यात्रा के दौरान सात्विक एवं शांत वातावरण में विश्राम की व्यवस्था उपलब्ध है।
                </p>
                <a href="{{ route('services.atithiVishram') }}" class="btn primary"
                  style="font-size: 13px; padding: 12px 30px; border-radius: 9999px; white-space: nowrap; font-weight: 700; background: #2D6A4F; border-color: #2D6A4F; box-shadow: 0 4px 14px rgba(45, 106, 79, 0.25);">
                  🏨 विश्राम गृह पूछताछ (Enquire for Stay)
                </a>
              </div>
            @endforelse
          </div>

          <!-- Tab 5: Temple Hall & Premises Bookings -->
          <div id="tab-temple-bookings" class="dash-tab-content" style="display: none;">
            <h3 class="dashboard-card-title"
              style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
              <span>मंदिर परिसर व आयोजन आरक्षण पूछताछ (Hall Booking Proposals)</span>
              <a href="{{ route('services.templeBooking') }}" class="btn primary"
                style="font-size: 11.5px; padding: 7px 18px; border-radius: 9999px; white-space: nowrap; background: #8F1719; border-color: #8F1719;">+
                New Hall Proposal</a>
            </h3>

            @forelse($templeBookings as $tb)
              <div class="donation-receipt-row">
                <div class="donation-row-left">
                  <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 13.5px; font-weight: 700; color: #2B1A12;">
                      🏛️ {{ $tb->getEventTypeLabel() }}
                      @if($tb->event_title) - "{{ $tb->event_title }}" @endif
                    </span>
                    @php $tbadge = $tb->getStatusBadge(); @endphp
                    <span class="badge"
                      style="padding: 3px 8px; border-radius: 9999px; font-size: 10px; font-weight: 700; border: 1px solid currentColor;">
                      {{ $tbadge['label'] }}
                    </span>
                  </div>
                  <div class="donation-meta-text" style="margin-top: 6px;">
                    <span>प्रस्ताव संदर्भ: <strong
                        style="font-family: monospace;">#{{ $tb->enquiry_number }}</strong></span>
                    <span>&bull;</span>
                    <span>प्रस्तावित तिथि: <strong>{{ optional($tb->proposed_date)->format('d M Y') }}</strong>
                      ({{ $tb->duration_days }} दिवस)</span>
                    <span>&bull;</span>
                    <span>अपेक्षित उपस्थिति: <strong>{{ number_format($tb->expected_gathering) }}</strong> श्रद्धालु</span>
                  </div>
                  @if($tb->admin_notes)
                    <div
                      style="font-size: 12px; color: #8F1719; margin-top: 6px; font-style: italic; background: rgba(143,23,25,0.06); padding: 6px 12px; border-radius: 6px;">
                      प्रबंधन संदेश: {{ $tb->admin_notes }}
                    </div>
                  @endif
                </div>
                <div class="donation-row-right">
                  <span style="font-size: 11.5px; font-weight: 700; color: #8F1719;">
                    {{ $tb->created_at->format('d M Y') }}
                  </span>
                </div>
              </div>
            @empty
              <div class="empty-sacred-state">
                <div class="sacred-diya-halo">
                  <div class="sacred-diya-icon">🏛️</div>
                </div>
                <h4 class="empty-sacred-title">मंदिर परिसर व मंडप आरक्षण</h4>
                <p class="empty-sacred-desc">
                  आपने मंदिर परिसर अथवा मंडप आरक्षण हेतु अभी तक कोई प्रस्ताव दर्ज नहीं कराया है। श्रीमद्भागवत कथा, संत समागम अथवा धार्मिक अनुष्ठान हेतु परिसर आरक्षण उपलब्ध है।
                </p>
                <a href="{{ route('services.templeBooking') }}" class="btn primary"
                  style="font-size: 13px; padding: 12px 30px; border-radius: 9999px; white-space: nowrap; font-weight: 700; background: #8F1719; border-color: #8F1719; box-shadow: 0 4px 14px rgba(143, 23, 25, 0.25);">
                  🏛️ परिसर आरक्षण प्रस्ताव (Hall Booking)
                </a>
              </div>
            @endforelse
          </div>

        </div>

        <script>
          function switchDashTab(tabId, btn) {
            document.querySelectorAll('.dash-tab-content').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.dash-tab-btn').forEach(b => {
              b.style.color = '';
              b.style.borderBottomColor = '';
              b.classList.remove('active-dash-tab');
            });
            const targetContent = document.getElementById(tabId);
            if (targetContent) targetContent.style.display = 'block';
            if (btn) {
              btn.classList.add('active-dash-tab');
            }

            // Sync with mobile segment pills
            document.querySelectorAll('.devotee-segment-btn').forEach(seg => {
              if (seg.getAttribute('data-target') === tabId) {
                seg.classList.add('active');
              } else {
                seg.classList.remove('active');
              }
            });
          }

          function switchDevoteeMobileSection(targetKey, btn) {
            document.querySelectorAll('.devotee-segment-btn').forEach(b => b.classList.remove('active'));
            if (btn) btn.classList.add('active');

            const sidebar = document.getElementById('devoteeSidebarSection');
            const mainContent = document.getElementById('devoteeMainSection');

            if (targetKey === 'profile') {
              if (sidebar) sidebar.style.display = 'flex';
              if (mainContent) mainContent.style.display = 'none';
              if (sidebar) sidebar.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else if (targetKey.startsWith('tab-')) {
              if (sidebar) sidebar.style.display = 'none';
              if (mainContent) mainContent.style.display = 'block';
              
              const desktopTabBtn = document.querySelector(`.dash-tab-btn[onclick*="${targetKey}"]`);
              switchDashTab(targetKey, desktopTabBtn);
              if (mainContent) mainContent.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
          }

          function checkDevoteeResponsiveLayout() {
            const sidebar = document.getElementById('devoteeSidebarSection');
            const mainContent = document.getElementById('devoteeMainSection');
            if (window.innerWidth <= 860) {
              const activeSeg = document.querySelector('.devotee-segment-btn.active');
              const targetKey = activeSeg ? activeSeg.getAttribute('data-target') : 'profile';
              if (targetKey === 'profile') {
                if (sidebar) sidebar.style.display = 'flex';
                if (mainContent) mainContent.style.display = 'none';
              } else if (targetKey && targetKey.startsWith('tab-')) {
                if (sidebar) sidebar.style.display = 'none';
                if (mainContent) mainContent.style.display = 'block';
              }
            } else {
              if (sidebar) sidebar.style.display = '';
              if (mainContent) mainContent.style.display = '';
            }
          }
          window.addEventListener('resize', checkDevoteeResponsiveLayout);
          document.addEventListener('DOMContentLoaded', checkDevoteeResponsiveLayout);

          function openProfileModal() {
            const modal = document.getElementById('profileEditModal');
            if (modal) modal.style.display = 'flex';
          }

          function closeProfileModal() {
            const modal = document.getElementById('profileEditModal');
            if (modal) modal.style.display = 'none';
          }
        </script>

      </div>

    </div>
  </section>

  <!-- Devotee Profile Edit Modal -->
  <div id="profileEditModal"
    style="display: none; position: fixed; inset: 0; z-index: 99999; background: rgba(22, 16, 13, 0.72); backdrop-filter: blur(6px); align-items: center; justify-content: center; padding: 20px;">
    <div
      style="background: #FFFDF9; border: 1px solid rgba(180, 83, 9, 0.28); border-radius: 18px; width: 100%; max-width: 620px; max-height: 90vh; overflow-y: auto; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35); padding: 28px 32px; position: relative;">

      <div class="modal-drag-handle"></div>

      <div
        style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(180, 83, 9, 0.16); padding-bottom: 14px; margin-bottom: 20px;">
        <div>
          <h3 style="font: 700 20px var(--dev); color: #8F1719; margin: 0 0 4px;">व्यक्तिगत जानकारी संशोधन</h3>
          <p style="font-size: 13px; color: #786457; margin: 0;">Update Profile & Supporter Information</p>
        </div>
        <button type="button" onclick="closeProfileModal()"
          style="background: #F3F4F6; border: none; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px; color: #4B5563; cursor: pointer; transition: all .2s;">&times;</button>
      </div>

      <form action="{{ route('profile.update') }}" method="POST">
        @csrf

        <div class="profile-modal-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">

          <div style="grid-column: span 2;">
            <label style="display: block; font: 700 13px var(--dev); color: #374151; margin-bottom: 6px;">पूरा नाम (Full
              Name) *</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
              style="width: 100%; padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 14px; background: #fff; box-sizing: border-box;">
          </div>

          <div>
            <label style="display: block; font: 700 13px var(--dev); color: #374151; margin-bottom: 6px;">फोन नंबर (Mobile
              Number)</label>
            <input type="tel" name="phone" value="{{ old('phone', $user->profile?->phone ?? $user->phone) }}"
              placeholder="10-अंकीय मोबाइल नंबर"
              style="width: 100%; padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 14px; background: #fff; box-sizing: border-box;">
          </div>

          <div>
            <label style="display: block; font: 700 13px var(--dev); color: #374151; margin-bottom: 6px;">लिंग
              (Gender)</label>
            @php $currentGender = strtolower($user->profile?->gender ?? ''); @endphp
            <select name="gender"
              style="width: 100%; padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 14px; background: #fff; box-sizing: border-box;">
              <option value="">चयन करें</option>
              <option value="male" {{ $currentGender === 'male' ? 'selected' : '' }}>पुरुष (Male)</option>
              <option value="female" {{ $currentGender === 'female' ? 'selected' : '' }}>महिला (Female)</option>
              <option value="other" {{ $currentGender === 'other' ? 'selected' : '' }}>अन्य (Other)</option>
            </select>
          </div>

          <div>
            <label style="display: block; font: 700 13px var(--dev); color: #374151; margin-bottom: 6px;">जन्म तिथि (Date
              of Birth)</label>
            <input type="date" name="date_of_birth"
              value="{{ old('date_of_birth', $user->profile?->date_of_birth ? \Carbon\Carbon::parse($user->profile->date_of_birth)->format('Y-m-d') : '') }}"
              style="width: 100%; padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 14px; background: #fff; box-sizing: border-box;">
          </div>

          <div>
            <label style="display: block; font: 700 13px var(--dev); color: #374151; margin-bottom: 6px;">व्यवसाय
              (Occupation)</label>
            <input type="text" name="occupation" value="{{ old('occupation', $user->profile?->occupation) }}"
              placeholder="जैसे: सेवा, व्यापार, विद्यार्थी"
              style="width: 100%; padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 14px; background: #fff; box-sizing: border-box;">
          </div>

          <div>
            <label style="display: block; font: 700 13px var(--dev); color: #374151; margin-bottom: 6px;">शहर / कस्बा
              (City/Town)</label>
            <input type="text" name="city" value="{{ old('city', $user->profile?->city) }}" placeholder="उदा. चित्रकूट"
              style="width: 100%; padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 14px; background: #fff; box-sizing: border-box;">
          </div>

          <div>
            <label style="display: block; font: 700 13px var(--dev); color: #374151; margin-bottom: 6px;">जिला
              (District)</label>
            <input type="text" name="district" value="{{ old('district', $user->profile?->district) }}"
              placeholder="उदा. सतना / चित्रकूट"
              style="width: 100%; padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 14px; background: #fff; box-sizing: border-box;">
          </div>

          <div>
            <label style="display: block; font: 700 13px var(--dev); color: #374151; margin-bottom: 6px;">राज्य
              (State)</label>
            <input type="text" name="state" value="{{ old('state', $user->profile?->state) }}"
              placeholder="उदा. मध्य प्रदेश / उत्तर प्रदेश"
              style="width: 100%; padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 14px; background: #fff; box-sizing: border-box;">
          </div>

          <div>
            <label style="display: block; font: 700 13px var(--dev); color: #374151; margin-bottom: 6px;">पिनकोड
              (Pincode)</label>
            <input type="text" name="pincode" value="{{ old('pincode', $user->profile?->pincode) }}"
              placeholder="6-अंकीय पिनकोड" maxlength="10"
              style="width: 100%; padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 14px; background: #fff; box-sizing: border-box;">
          </div>

          <div style="grid-column: span 2;">
            <label style="display: block; font: 700 13px var(--dev); color: #374151; margin-bottom: 6px;">स्थायी पता
              (Postal Address)</label>
            <textarea name="address" rows="2" placeholder="मकान नं., मार्ग, मोहल्ला"
              style="width: 100%; padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 14px; background: #fff; box-sizing: border-box; resize: vertical;">{{ old('address', $user->profile?->address) }}</textarea>
          </div>

        </div>

        <div class="profile-modal-actions"
          style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 16px; border-top: 1px solid rgba(180, 83, 9, 0.16);">
          <button type="button" onclick="closeProfileModal()"
            style="padding: 10px 20px; border-radius: 9999px; border: 1px solid #D1D5DB; background: #fff; color: #4B5563; font-weight: 700; font-size: 12.5px; cursor: pointer; white-space: nowrap;">
            रद्द करें (Cancel)
          </button>
          <button type="submit" class="btn primary"
            style="padding: 10px 24px; font-size: 12.5px; font-weight: 700; border-radius: 9999px; cursor: pointer; white-space: nowrap;">
            सहेजें (Save Changes)
          </button>
        </div>

      </form>
    </div>
  </div>

  <!-- Devotee Live Floating Toast Notification Box -->
  <div class="devotee-toast-container" id="devoteeToastContainer"></div>

  <script>
    /* Devotee Real-Time Notification Center & Instant Visual Feedback */
    let devoteeKnownNotificationIds = new Set();
    let devoteeNotifPollTimer = null;

    function playDevoteeNotificationChime() {
      try {
        const AudioCtx = window.AudioContext || window.webkitAudioContext;
        if (!AudioCtx) return;
        const ctx = new AudioCtx();
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.type = 'sine';
        osc.frequency.setValueAtTime(587.33, ctx.currentTime); // D5
        osc.frequency.exponentialRampToValueAtTime(880, ctx.currentTime + 0.15); // A5
        gain.gain.setValueAtTime(0.08, ctx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + 0.45);
        osc.connect(gain);
        gain.connect(ctx.destination);
        osc.start();
        osc.stop(ctx.currentTime + 0.5);
      } catch (e) { }
    }

    function showDevoteeToast(icon, title, desc, url = null) {
      playDevoteeNotificationChime();
      const container = document.getElementById('devoteeToastContainer');
      if (!container) return;

      const toast = document.createElement('div');
      toast.className = 'devotee-live-toast';
      toast.innerHTML = `
          <div style="font-size: 24px; line-height: 1;">${icon}</div>
          <div style="flex: 1; min-width: 0;">
            <div style="font: 700 13.5px var(--dev); color: #78350F; line-height: 1.3;">${title}</div>
            <div style="font-size: 12px; color: #4B5563; margin-top: 3px; line-height: 1.4;">${desc}</div>
            ${url ? `<a href="${url}" style="display: inline-block; margin-top: 6px; font-size: 11.5px; font-weight: 700; color: #B45309; text-decoration: underline;">विवरण देखें &rarr;</a>` : ''}
          </div>
          <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; color: #9CA3AF; font-size: 18px; cursor: pointer; padding: 0 4px; line-height: 1;">&times;</button>
        `;

      container.appendChild(toast);
      setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(10px)';
        toast.style.transition = 'all 0.3s ease';
        setTimeout(() => toast.remove(), 300);
      }, 7000);
    }

    function toggleDevoteeNotifDropdown() {
      const panel = document.getElementById('devoteeNotifPanel');
      if (!panel) return;
      const isVisible = panel.style.display === 'flex';
      if (!isVisible) {
        panel.style.display = 'flex';
        markDevoteeNotifSeen();
      } else {
        panel.style.display = 'none';
      }
    }

    document.addEventListener('click', function (e) {
      const wrap = document.getElementById('devoteeNotifWrap');
      const panel = document.getElementById('devoteeNotifPanel');
      if (wrap && panel && !wrap.contains(e.target)) {
        panel.style.display = 'none';
      }
    });

    function markDevoteeNotifSeen() {
      const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
      const badge = document.getElementById('devoteeNotifBadge');
      if (badge) badge.style.display = 'none';

      document.querySelectorAll('.devotee-notif-item.unread').forEach(el => el.classList.remove('unread'));

      fetch("{{ route('user.notifications.markSeen') }}", {
        method: "POST",
        headers: {
          "X-CSRF-TOKEN": token,
          "Accept": "application/json",
          "Content-Type": "application/json"
        }
      }).catch(() => { });
    }

    function fetchDevoteeNotifications(isInitial = false) {
      fetch("{{ route('user.notifications.poll') }}", {
        headers: { "Accept": "application/json" }
      })
        .then(r => r.json())
        .then(data => {
          const badge = document.getElementById('devoteeNotifBadge');
          const list = document.getElementById('devoteeNotifList');
          const callout = document.getElementById('devoteeLiveQuotationAlert');

          // 1. Update Badge
          if (badge) {
            if (data.unread_count > 0) {
              badge.textContent = data.unread_count;
              badge.style.display = 'inline-block';
            } else {
              badge.style.display = 'none';
            }
          }

          // 2. Dynamic Urgent Quotation Alert Banner
          let quotationItem = null;
          if (data.notifications) {
            quotationItem = data.notifications.find(n => n.type === 'quotation');
          }

          if (callout) {
            if (data.active_quotations_count > 0 && quotationItem) {
              callout.style.display = 'block';
              callout.innerHTML = `
                <div class="quotation-alert-card">
                  <div class="quotation-alert-left">
                    <div style="font-size: 28px; line-height: 1; flex-shrink: 0;">⚡</div>
                    <div style="flex: 1; min-width: 0;">
                      <div style="font: 700 16px var(--dev); color: #92400E; line-height: 1.3;">पूजा दक्षिणा कोटेशन उपलब्ध (Quotation Received & Ready for Payment)</div>
                      <div style="font-size: 13.5px; color: #78350F; margin-top: 4px; line-height: 1.4; word-break: break-word;">
                        ${quotationItem.description}
                      </div>
                    </div>
                  </div>
                  <a href="${quotationItem.url}" class="quotation-alert-btn">
                    <span>अभी दक्षिणा अर्पित करें</span>
                    <span>&rarr;</span>
                  </a>
                </div>
              `;
            } else {
              callout.style.display = 'none';
            }
          }

          // 3. Render Dropdown Items & Trigger Toast on New Events
          if (list && data.notifications) {
            if (data.notifications.length === 0) {
              list.innerHTML = '<div style="padding: 24px; text-align: center; color: #888; font-size: 12px;">आपकी कोई नई सूचना नहीं है (No new notifications)</div>';
            } else {
              let html = '';
              data.notifications.forEach((item) => {
                const isUnread = item.is_unread;
                html += `
                  <a href="${item.url}" class="devotee-notif-item ${isUnread ? 'unread' : ''}">
                    <div style="font-size: 20px; line-height: 1;">${item.icon}</div>
                    <div style="flex: 1; min-width: 0;">
                      <div style="font: 700 13px var(--dev); color: #78350F;">${item.title}</div>
                      <div style="font-size: 11.5px; color: #4B5563; line-height: 1.4; margin-top: 2px;">${item.description}</div>
                      <div style="font-size: 10px; color: #9CA3AF; margin-top: 4px;">${item.time_ago}</div>
                    </div>
                  </a>
                `;

                // If newly arrived in background poll, show toast feedback
                if (!isInitial && isUnread && !devoteeKnownNotificationIds.has(item.id)) {
                  showDevoteeToast(item.icon, item.title, item.description, item.url);
                }
                devoteeKnownNotificationIds.add(item.id);
              });
              list.innerHTML = html;
            }
          }
        })
        .catch(() => { });
    }

    document.addEventListener('DOMContentLoaded', function () {
      fetchDevoteeNotifications(true);
      // Live polling every 10 seconds for instant feedback
      devoteeNotifPollTimer = setInterval(() => fetchDevoteeNotifications(false), 10000);
    });
  </script>
@endsection