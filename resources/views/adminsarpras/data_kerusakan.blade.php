<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Kerusakan - Admin Sarana Prasarana</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  /* ===== RESET & BASE ===== */
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --primary:       #3b6bda;
    --primary-d:     #2f55b8;
    --primary-light: #eef2ff;
    --bg:            #f1f5fb;
    --surface:       #ffffff;
    --border:        #e2e8f0;
    --text-primary:  #0f172a;
    --text-secondary:#475569;
    --text-tertiary: #94a3b8;
    --success:       #10B981;
    --danger:        #EF4444;
    --shadow-sm: 0 1px 3px rgba(15,23,42,.07), 0 1px 2px rgba(15,23,42,.04);
    --shadow-md: 0 4px 16px rgba(15,23,42,.09);
    --radius:    12px;
  }

  html, body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: var(--bg);
    color: var(--text-primary);
    font-size: 14px;
    line-height: 1.5;
    min-height: 100vh;
  }

  /* ===== LAYOUT — sidebar already handled by partials.sidebar ===== */
  .main {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
  }

  /* ===== TOPBAR ===== */
  .topbar {
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    padding: 0 28px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    z-index: 40;
    box-shadow: var(--shadow-sm);
  }
  .topbar-title {
    font-size: 17px;
    font-weight: 800;
    color: var(--text-primary);
    letter-spacing: -.03em;
  }
  .topbar-right {
    display: flex;
    align-items: center;
    gap: 12px;
  }
  .notif-btn {
    width: 36px; height: 36px;
    border-radius: 10px;
    border: 1px solid var(--border);
    background: var(--surface);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    position: relative;
    transition: background .15s;
  }
  .notif-btn:hover { background: var(--bg); }
  .notif-dot {
    position: absolute; top: 8px; right: 8px;
    width: 7px; height: 7px;
    background: #ef4444;
    border-radius: 50%;
    border: 1.5px solid var(--surface);
  }
  .date-text {
    font-size: 12.5px;
    color: var(--text-secondary);
    background: var(--bg);
    padding: 5px 13px;
    border-radius: 20px;
    border: 1px solid var(--border);
    font-weight: 500;
  }
  .btn-keluar {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 15px;
    border-radius: 9px;
    border: 1px solid #fecaca;
    background: #fef2f2;
    color: #ef4444;
    font-family: inherit;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: all .15s;
  }
  .btn-keluar:hover { background: #ef4444; color: #fff; border-color: #ef4444; }

  /* ===== CONTENT ===== */
  .content {
    padding: 28px;
    flex: 1;
  }

  /* ─── HEADER ─── */
  .page-hdr{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:22px}
  .page-hdr h1{font-size:22px;font-weight:700;letter-spacing:-.5px}
  .page-hdr p{font-size:13px;color:var(--text-tertiary);margin-top:2px}

  /* ===== ALERT (Format Persediaan) ===== */
  .alert {
    padding: 14px 18px; border-radius: 10px; margin-bottom: 20px;
    display: flex; align-items: center; gap: 10px; font-weight: 600;
  }
  .alert-success { background: #ECFDF5; color: var(--success); border: 1px solid #BBF7D0; }
  .alert-danger { background: #FEF2F2; color: var(--danger); border: 1px solid #FECACA; }

  /* ===== TOOLBAR ===== */
  .toolbar{display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:16px}
  .search-box{position:relative;flex:1;min-width:200px}
  .search-box svg{position:absolute;left:11px;top:50%;transform:translateY(-50%);width:15px;height:15px;color:var(--text-tertiary);pointer-events:none}
  .search-box input{
    width:100%;padding:10px 12px 10px 36px;
    border:0.5px solid var(--border);border-radius:10px;
    font-size:13px;font-family:inherit;background:var(--surface);color:var(--text-primary);
    outline:none;transition:border-color .15s,box-shadow .15s;
  }
  .search-box input:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(59,107,218,.1)}
  .search-box input::placeholder{color:var(--text-tertiary)}
  .filter-select{
    padding:10px 14px;border:0.5px solid var(--border);border-radius:10px;
    font-size:13px;font-family:inherit;background:var(--surface);color:var(--text-primary);
    outline:none;cursor:pointer;transition:border-color .15s;
  }
  .filter-select:focus{border-color:var(--primary)}

  .btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 20px;
    background: var(--primary);
    color: #fff;
    border: none;
    border-radius: var(--radius);
    font-family: inherit;
    font-size: 13.5px;
    font-weight: 700;
    cursor: pointer;
    transition: background .15s;
    white-space: nowrap;
    box-shadow: 0 2px 8px rgba(59,107,218,.25);
  }
  .btn-primary:hover { background: var(--primary-d); }

  /* ===== TABLE CARD ===== */
  .table-card {
    background: var(--surface);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
  }
  /* PAGINATION */
  .table-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 20px;
    border-top: 1px solid var(--border);
    font-size: 13px; color: var(--text-tertiary);
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }

  thead th {
    background: #f8faff;
    padding: 11px 16px;
    text-align: left;
    font-size: 11px;
    font-weight: 700;
    color: var(--text-tertiary);
    text-transform: uppercase;
    letter-spacing: .07em;
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
  }

  tbody tr {
    border-bottom: 1px solid var(--border);
    transition: background .12s;
  }
  tbody tr:last-child { border-bottom: none; }
  tbody tr:hover { background: #f8faff; }

  tbody td {
    padding: 11px 16px;
    font-size: 13.5px;
    color: var(--text-primary);
    vertical-align: middle;
  }

  /* row number dimmed */
  tbody td:first-child {
    color: var(--text-tertiary);
    font-weight: 600;
    font-size: 12.5px;
  }

  /* date col */
  td[style*="white-space:nowrap"] {
    color: var(--text-secondary);
  }

  /* ===== STATUS BADGES ===== */
  .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 3px 10px 3px 7px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
  }
  .status-badge::before {
    content: '';
    display: inline-block;
    width: 6px; height: 6px;
    border-radius: 50%;
    flex-shrink: 0;
  }
  .badge-baik         { background:#ecfdf5; color:#10b981; border:1px solid #a7f3d0; }
  .badge-baik::before         { background:#10b981; }
  .badge-rusak-ringan { background:#fffbeb; color:#f59e0b; border:1px solid #fde68a; }
  .badge-rusak-ringan::before { background:#f59e0b; }
  .badge-rusak-sedang { background:#fff7ed; color:#f97316; border:1px solid #fed7aa; }
  .badge-rusak-sedang::before { background:#f97316; }
  .badge-rusak-berat  { background:#fef2f2; color:#ef4444; border:1px solid #fecaca; }
  .badge-rusak-berat::before  { background:#ef4444; }
  .badge-hancur       { background:#fff1f2; color:#e11d48; border:1px solid #fda4af; }
  .badge-hancur::before       { background:#e11d48; }

  /* ===== KODE BARANG MONOSPACE PILL ===== */
  tbody td span[style*="monospace"] {
    font-size: 12px !important;
    background: var(--bg) !important;
    border: 1px solid var(--border) !important;
    color: var(--text-secondary) !important;
    padding: 3px 9px !important;
    border-radius: 6px !important;
    font-weight: 600 !important;
  }

  /* ===== ACTION BUTTONS ===== */
  .action-btns {
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .act-btn {
    width: 30px; height: 30px;
    display: flex; align-items: center; justify-content: center;
    border-radius: 7px;
    border: 1px solid;
    cursor: pointer;
    background: transparent;
    transition: all .15s;
  }
  .act-btn.view  { border-color: #bfdbfe; color: var(--primary); }
  .act-btn.view:hover  { background: var(--primary-light); }
  
  .act-btn[style*="#10b981"] {
    border-color: #a7f3d0 !important;
    color: #10b981 !important;
    background: #ecfdf5 !important;
  }
  .act-btn[style*="#10b981"]:hover {
    background: #bbf7d0 !important;
  }
  .act-btn.del   { border-color: #fecaca; color: #ef4444; }
  .act-btn.del:hover   { background: #fef2f2; }
  .act-btn.photo { border-color: #e0e7ff; color: #6366f1; }
  .act-btn.photo:hover { background: #eef2ff; }

  /* ===== PHOTO THUMBNAIL ===== */
  .photo-thumbnail {
    width: 36px; height: 36px;
    border-radius: 8px;
    object-fit: cover;
    border: 1px solid var(--border);
    cursor: pointer;
    transition: opacity .15s, transform .15s;
    display: block;
  }
  .photo-thumbnail:hover { opacity: .85; transform: scale(1.06); }

  .photo-empty-icon {
    width: 36px; height: 36px;
    border-radius: 8px;
    border: 1.5px dashed var(--border);
    display: flex; align-items: center; justify-content: center;
    color: var(--text-tertiary);
    background: var(--bg);
  }

  /* ===== EMPTY STATE ===== */
  .empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 64px 24px;
    color: var(--text-tertiary);
    text-align: center;
  }
  .empty-state h3 {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-secondary);
  }
  .empty-state p { font-size: 13.5px; }

  /* ===== MODAL OVERLAY ===== */
  .modal-overlay {
    position: fixed; inset: 0;
    background: rgba(15,23,42,.42);
    backdrop-filter: blur(3px);
    display: none; align-items: center; justify-content: center;
    z-index: 200; padding: 20px;
  }
  .modal-overlay.open { display: flex; }

  .modal {
    background: var(--surface);
    border-radius: 18px;
    width: 520px; max-width: 100%;
    max-height: 92vh; overflow-y: auto;
    box-shadow: 0 24px 64px rgba(0,0,0,.18);
    animation: modalIn .2s ease;
  }
  @keyframes modalIn {
    from { opacity:0; transform: translateY(12px) scale(.98); }
    to   { opacity:1; transform: translateY(0) scale(1); }
  }

  .modal-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 20px 24px 16px;
    border-bottom: 1px solid var(--border);
    position: sticky; top: 0; background: var(--surface); z-index: 1;
    border-radius: 18px 18px 0 0;
  }
  .modal-title { font-size: 15px; font-weight: 800; color: var(--text-primary); }

  .close-btn {
    width: 32px; height: 32px;
    border-radius: 8px;
    border: 1px solid var(--border);
    background: var(--bg);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: 18px; line-height: 1;
    color: var(--text-secondary); transition: all .15s;
  }
  .close-btn:hover { background: var(--border); color: var(--text-primary); }

  /* ===== FORM ===== */
  form { padding: 20px 24px; }

  .form-group { margin-bottom: 15px; }
  .form-label {
    font-size: 11px; font-weight: 700;
    color: var(--text-tertiary);
    text-transform: uppercase; letter-spacing: .06em;
    margin-bottom: 6px; display: block;
  }
  .form-input, .form-select, .form-textarea {
    width: 100%; padding: 9px 13px;
    border: 1px solid var(--border);
    border-radius: 10px;
    font-family: inherit; font-size: 13.5px;
    outline: none; transition: border .15s, box-shadow .15s;
    background: #fff; color: var(--text-primary);
  }
  .form-input:focus, .form-select:focus, .form-textarea:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(59,107,218,.10);
  }
  .form-textarea { min-height: 80px; resize: vertical; }
  .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

  /* File upload */
  .file-input-wrapper { position: relative; }
  .file-input-wrapper input[type=file] { position: absolute; left: -9999px; }
  .file-input-label {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    padding: 11px 13px;
    border: 1.5px dashed var(--border);
    border-radius: 10px; cursor: pointer;
    font-size: 13px; color: var(--text-secondary);
    transition: all .15s;
    background: var(--bg);
  }
  .file-input-label:hover {
    border-color: var(--primary);
    color: var(--primary);
    background: var(--primary-light);
  }
  .photo-preview {
    width: 80px; height: 80px; object-fit: cover;
    border-radius: 10px; margin-top: 10px;
    border: 1px solid var(--border); display: none;
  }

  /* Modal footer */
  .modal-footer {
    display: flex; justify-content: flex-end; gap: 10px;
    padding: 15px 24px;
    border-top: 1px solid var(--border);
    background: var(--bg); border-radius: 0 0 18px 18px;
  }
  .btn-cancel {
    padding: 9px 18px;
    border: 1px solid var(--border); border-radius: 10px;
    font-family: inherit; font-size: 13.5px; font-weight: 500;
    cursor: pointer; background: #fff; color: var(--text-secondary);
    transition: all .15s;
  }
  .btn-cancel:hover { background: var(--bg); }
  .btn-save {
    padding: 9px 22px;
    background: var(--primary); color: #fff;
    border: none; border-radius: 10px;
    font-family: inherit; font-size: 13.5px; font-weight: 700;
    cursor: pointer; transition: background .15s;
    box-shadow: 0 2px 8px rgba(59,107,218,.25);
  }
  .btn-save:hover { background: var(--primary-d); }

  /* ===== DETAIL MODAL ===== */
  .detail-body { padding: 8px 24px 4px; }
  .detail-row {
    display: flex; gap: 16px; align-items: flex-start;
    padding: 12px 0; border-bottom: 1px solid var(--border);
    font-size: 13.5px;
  }
  .detail-row:last-child { border-bottom: none; }
  .detail-label {
    font-size: 11px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .06em; color: var(--text-tertiary);
    min-width: 120px; padding-top: 2px; flex-shrink: 0;
  }
  .detail-value { color: var(--text-primary); flex: 1; line-height: 1.6; }
  .detail-photo {
    width: 100%; max-height: 200px; object-fit: cover;
    border-radius: 10px; margin-top: 4px;
    border: 1px solid var(--border);
  }

  /* ===== POPUP PILIH BARANG ===== */
  .modal-picker {
    width: 860px !important;
    max-width: 95vw !important;
  }
  .asset-picker-card {
    background: linear-gradient(135deg, #eef4ff 0%, #f0fdf4 100%);
    border: 1.5px dashed #93c5fd;
    border-radius: 12px;
    padding: 12px 16px;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
  }
  .picker-card-left {
    display: flex;
    align-items: center;
    gap: 12px;
  }
  .picker-card-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: var(--primary);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  .picker-card-title {
    font-size: 13px;
    font-weight: 700;
    color: #1e3a8a;
  }
  .picker-card-subtitle {
    font-size: 11.5px;
    color: #64748b;
  }
  .btn-picker-trigger {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    background: var(--primary);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-family: inherit;
    font-size: 12.5px;
    font-weight: 700;
    cursor: pointer;
    white-space: nowrap;
    transition: all .15s;
    box-shadow: 0 2px 6px rgba(59,107,218,.25);
  }
  .btn-picker-trigger:hover {
    background: var(--primary-d);
    transform: translateY(-1px);
  }
  .quick-link-picker {
    font-size: 11px;
    font-weight: 600;
    color: var(--primary);
    background: #eef2ff;
    border: 1px solid #bfdbfe;
    border-radius: 6px;
    padding: 2px 8px;
    cursor: pointer;
    text-decoration: none;
    transition: all .15s;
    display: inline-flex;
    align-items: center;
    gap: 4px;
  }
  .quick-link-picker:hover {
    background: var(--primary);
    color: #fff;
    border-color: var(--primary);
  }

  .picker-body {
    padding: 16px 20px 20px;
  }
  .picker-search-bar {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 14px;
    flex-wrap: wrap;
  }
  .picker-search-input-wrap {
    position: relative;
    flex: 1;
    min-width: 260px;
  }
  .picker-search-input-wrap svg {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-tertiary);
    pointer-events: none;
  }
  .picker-search-input-wrap input {
    width: 100%;
    padding: 10px 36px 10px 36px;
    border: 1px solid var(--border);
    border-radius: 10px;
    font-size: 13px;
    font-family: inherit;
    outline: none;
    transition: border .15s, box-shadow .15s;
    box-sizing: border-box;
  }
  .picker-search-input-wrap input:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(59,107,218,.12);
  }
  .picker-search-clear {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: transparent;
    border: none;
    font-size: 18px;
    color: var(--text-tertiary);
    cursor: pointer;
    line-height: 1;
    padding: 2px;
  }
  .picker-search-clear:hover { color: var(--text-primary); }

  .picker-loader {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: var(--primary);
    font-weight: 600;
  }
  .spinner-small {
    width: 14px;
    height: 14px;
    border: 2px solid rgba(59,107,218,.25);
    border-top-color: var(--primary);
    border-radius: 50%;
    animation: spinSmall .6s linear infinite;
  }
  @keyframes spinSmall { to { transform: rotate(360deg); } }

  .picker-table-wrap {
    max-height: 380px;
    overflow-y: auto;
    border: 1px solid var(--border);
    border-radius: 10px;
    background: #fff;
  }
  .picker-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
  }
  .picker-table thead th {
    background: #f8faff;
    position: sticky;
    top: 0;
    z-index: 2;
    padding: 10px 14px;
    font-size: 11px;
    font-weight: 700;
    color: var(--text-tertiary);
    text-transform: uppercase;
    letter-spacing: .06em;
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
  }
  .picker-table tbody td {
    padding: 10px 14px;
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
  }
  .picker-table tbody tr:last-child td { border-bottom: none; }
  .picker-table tbody tr:hover {
    background: #f8faff;
  }
  .picker-tag {
    display: inline-block;
    font-size: 11px;
    font-weight: 600;
    color: #475569;
    background: #f1f5fb;
    padding: 2px 7px;
    border-radius: 4px;
    margin-top: 3px;
  }
  .btn-choose {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 6px 12px;
    background: #10b981;
    color: #fff;
    border: none;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 700;
    font-family: inherit;
    cursor: pointer;
    transition: all .15s;
    box-shadow: 0 1px 3px rgba(16,185,129,.2);
    white-space: nowrap;
  }
  .btn-choose:hover {
    background: #059669;
    transform: translateY(-1px);
  }
  .btn-choose.disabled {
    background: #e2e8f0;
    color: #94a3b8;
    cursor: not-allowed;
    box-shadow: none;
    transform: none;
  }

  .picker-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 14px;
    gap: 12px;
    flex-wrap: wrap;
  }
  .picker-count {
    font-size: 12.5px;
    color: var(--text-tertiary);
  }
  .picker-pagination {
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .picker-page-btn {
    padding: 5px 11px;
    border: 1px solid var(--border);
    border-radius: 6px;
    background: #fff;
    color: var(--text-primary);
    font-size: 12px;
    font-family: inherit;
    font-weight: 600;
    cursor: pointer;
    transition: all .15s;
  }
  .picker-page-btn:hover:not(:disabled) {
    background: var(--primary-light);
    border-color: var(--primary);
    color: var(--primary);
  }
  .picker-page-btn:disabled {
    opacity: .4;
    cursor: not-allowed;
  }
  .picker-page-btn.active {
    background: var(--primary);
    border-color: var(--primary);
    color: #fff;
  }

  /* Flash Highlight Animation */
  @keyframes fieldHighlight {
    0% { background-color: #d1fae5; border-color: #10b981; }
    50% { background-color: #ecfdf5; border-color: #34d399; }
    100% { background-color: #fff; border-color: var(--border); }
  }
  .field-highlight {
    animation: fieldHighlight 1.8s ease forwards;
  }

  /* Toast Notification */
  .picker-toast {
    position: fixed;
    bottom: 24px;
    right: 24px;
    background: #0f172a;
    color: #fff;
    padding: 12px 20px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    font-weight: 600;
    box-shadow: 0 10px 25px rgba(0,0,0,.2);
    z-index: 999;
    opacity: 0;
    transform: translateY(20px);
    transition: all .25s ease;
    pointer-events: none;
  }
  .picker-toast.show {
    opacity: 1;
    transform: translateY(0);
  }
</style>
</head>
<body>

@include('partials.sidebar')

<main class="main">
  <div class="topbar">
    <span class="topbar-title">Data Kerusakan</span>
    <div class="topbar-right">
      {{-- <div class="notif-btn">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="#64748B">
          <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
        </svg>
        <span class="notif-dot"></span>
      </div> --}}
      <span class="date-text">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}</span>
<form method="POST" action="{{ route('logout', [], false) }}" style="display:inline; margin:0;">
@csrf
<button type="submit" class="btn-keluar">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
          <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5-5-5zm-5 11H5V5h7V3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h7v-2z"/>
        </svg>
        Keluar
      </button>
</form>
    </div>
  </div>

  <div class="content">

    {{-- ▼▼▼ BLOK ALERT BERHASIL DIPERBARUI ▼▼▼ --}}
    @if(session('success'))
    <div class="alert alert-success">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
      </svg>
      {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
      </svg>
      @foreach($errors->all() as $error)
        <div>{{ $error }}</div>
      @endforeach
    </div>
    @endif
    {{-- ▲▲▲ BATAS BLOK ALERT ▲▲▲ --}}

    <div class="page-hdr">
      <div>
        <h1>Data Kerusakan</h1>
        <p>Kelola data barang yang mengalami kerusakan</p>
      </div>
      <button class="btn-primary" onclick="openModal('create')">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
          <path d="M12 5v14m-7-7h14"/>
        </svg>
        Tambah Data
      </button>
    </div>

    {{-- TOOLBAR --}}
    <div class="toolbar">
      <div class="search-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input type="text" placeholder="Cari nama barang atau kode…" id="searchInput" oninput="filterTable()">
      </div>
      <select class="filter-select" id="statusFilter" onchange="filterTable()">
        <option value="">Semua Status</option>
        <option value="Baik">Baik</option>
        <option value="Rusak Berat">Rusak Berat</option>
        <option value="Rusak Ringan">Rusak Ringan</option>
      </select>
      
    </div>

    {{-- TABLE --}}
    <div class="table-card">
      @if($kerusakans->count() > 0)
      <table id="kerusakanTable">
        <thead>
          <tr>
            <th style="width:48px;">No</th>
            <th>Tanggal</th>
            <th>Nama Barang</th>
            <th>Kode Barang</th>
            <th style="width:64px;">NUP</th>
            <th>Kondisi</th>
            <th style="width:64px;">Foto</th>
            <th>Lokasi</th>
            <th style="width:110px;">Aksi</th>
          </tr>
        </thead>
        <tbody id="tableBody">
          @foreach($kerusakans as $kerusakan)
          <tr data-id="{{ $kerusakan->id }}"
              data-kode="{{ strtolower($kerusakan->kode_barang) }}"
              data-nama="{{ strtolower($kerusakan->nama_barang) }}">
            <td>{{ $loop->iteration }}</td>
            <td style="white-space:nowrap;color:var(--text-secondary);">
              {{ \Carbon\Carbon::parse($kerusakan->tanggal_input)->format('d/m/Y') }}
            </td>
            <td style="font-weight:500;">{{ $kerusakan->nama_barang }}</td>
            <td>
              <span style="font-family:monospace;font-size:12px;font-weight:700;
                           background:var(--bg);padding:3px 9px;border-radius:6px;
                           border:1px solid var(--border);color:var(--text-secondary);">
                {{ $kerusakan->kode_barang }}
              </span>
            </td>
            <td style="color:var(--text-secondary);">{{ $kerusakan->nup }}</td>
            <td>
              @php
                $kondisiClass = match($kerusakan->kondisi) {
                  'Baik'         => 'badge-baik',
                  'Rusak Ringan' => 'badge-rusak-ringan',
                  'Rusak Berat'  => 'badge-rusak-berat',
                  default        => 'badge-baik'
                };
              @endphp
              <span class="status-badge {{ $kondisiClass }}">{{ $kerusakan->kondisi }}</span>
            </td>
            <td>
              @if($kerusakan->foto)
                @if(file_exists(public_path('storage/' . $kerusakan->foto)))
                  <img src="{{ asset('storage/' . $kerusakan->foto) }}"
                       alt="Foto {{ $kerusakan->nama_barang }}"
                       class="photo-thumbnail"
                       onclick="viewPhoto('{{ asset('storage/' . $kerusakan->foto) }}')"
                       onerror="this.style.display='none'">
                @else
                  <button class="act-btn photo" title="Lihat Foto"
                          onclick="viewPhoto('{{ asset('storage/' . $kerusakan->foto) }}')">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                      <rect x="3" y="3" width="18" height="18" rx="2"/>
                      <circle cx="8.5" cy="8.5" r="1.5"/>
                      <path d="m21 15-5-5L5 21"/>
                    </svg>
                  </button>
                @endif
              @else
                <div class="photo-empty-icon" title="Tidak ada foto">
                  <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                    <circle cx="8.5" cy="8.5" r="1.5"/>
                    <path d="m21 15-5-5L5 21"/>
                  </svg>
                </div>
              @endif
            </td>
            <td style="color:var(--text-secondary);">{{ $kerusakan->lokasi }}</td>
            <td>
              <div class="action-btns">
                <a href="{{ route('adminsarpras.kerusakan.riwayat', $kerusakan) }}" title="Riwayat kerusakan dan perbaikan" style="padding:6px;color:#2256c7;">Riwayat</a>
                <button class="act-btn view" title="Lihat Detail"
                        onclick="openModal('view', {{ $kerusakan->id }})">
                  <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                  </svg>
                </button>
                <button class="act-btn" title="Edit"
                        style="color:#10b981;border-color:#a7f3d0;background:#ecfdf5;"
                        onclick="openModal('edit', {{ $kerusakan->id }})">
                  <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                  </svg>
                </button>
                <button class="act-btn del" title="Hapus"
                        onclick="confirmDelete({{ $kerusakan->id }})">
                  <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6l-1 14H6L5 6m5 0V4h4v2"/>
                  </svg>
                </button>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      @else
      <div class="empty-state">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.2" width="52" height="52">
          <path d="M9 19v-6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2zm0 0V9a2 2 0 0 0 2-2h2a2 2 0 0 1 2 2v10m-6 0a2 2 0 0 0 2 2h.01"/>
        </svg>
        <h3>Belum ada data kerusakan</h3>
        <p>Tambahkan data kerusakan barang pertama Anda</p>
      </div>
      @endif
    </div>

    {{-- PAGINATION --}}
    <div class="table-footer">
        <span>Menampilkan {{ $kerusakans->firstItem() ?? 0 }}–{{ $kerusakans->lastItem() ?? 0 }} dari {{ $kerusakans->total() }} data</span>
        <div class="pagination">
          {{ $kerusakans->appends(request()->query())->links() }}
        </div>
      </div>

  </div>{{-- end .content --}}
</main>

{{-- ============================================================ --}}
{{--  MODAL TAMBAH / EDIT                                         --}}
{{-- ============================================================ --}}
<div class="modal-overlay" id="crudModal">
  <div class="modal">
    <div class="modal-header">
      <h3 class="modal-title" id="modalTitle">Tambah Data Kerusakan</h3>
      <button class="close-btn" onclick="closeModal()" aria-label="Tutup">&times;</button>
    </div>

    <form id="crudForm" method="POST" action="{{ route('adminsarpras.kerusakan.store') }}" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="id" id="kerusakanId">

      <div class="form-row">
        <div class="form-group">
          <label class="form-label" for="tanggal_input">Tanggal Input</label>
          <input type="date" name="tanggal_input" id="tanggal_input"
                 class="form-input" required>
        </div>
        <div class="form-group">
          <label class="form-label" for="kondisi">Kondisi</label>
          <select name="kondisi" id="kondisi" class="form-select" required>
            <option value="">Pilih kondisi</option>
            <option value="Baik">Baik</option>
            <option value="Rusak Ringan">Rusak Ringan</option>
            <option value="Rusak Berat">Rusak Berat</option>
          </select>
        </div>
      </div>

      {{-- BANNER PILIH DARI MASTER ASET --}}
      <div class="asset-picker-card">
        <div class="picker-card-left">
          <div class="picker-card-icon">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
          </div>
          <div>
            <div class="picker-card-title">Pilih dari Master Data Aset</div>
            <div class="picker-card-subtitle">Isi otomatis nama, kode, NUP, & lokasi</div>
          </div>
        </div>
        <button type="button" class="btn-picker-trigger" onclick="openPilihBarangModal()">
          <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.3">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
          </svg>
          Pilih Barang
        </button>
      </div>

      <div class="form-group">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
          <label class="form-label" for="nama_barang" style="margin-bottom:0;">Nama Barang</label>
          <button type="button" onclick="openPilihBarangModal()" class="quick-link-picker" title="Pilih dari daftar aset">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            Cari di Aset
          </button>
        </div>
        <input type="text" name="nama_barang" id="nama_barang"
               class="form-input" placeholder="Contoh: Meja Belajar" required>
      </div>

      <div class="form-row">
        <div class="form-group">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
            <label class="form-label" for="kode_barang" style="margin-bottom:0;">Kode Barang</label>
          </div>
          <input type="text" name="kode_barang" id="kode_barang"
                 class="form-input" placeholder="MB-0012" required>
        </div>
        <div class="form-group">
          <label class="form-label" for="nup">NUP</label>
          <input type="text" name="nup" id="nup"
                 class="form-input" placeholder="001" required>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label" for="lokasi">Lokasi</label>
        <input type="text" name="lokasi" id="lokasi"
               class="form-input" placeholder="Contoh: Ruang Kelas 3A" required>
      </div>

      <div class="form-group">
        <label class="form-label" for="deskripsi">
          Deskripsi <span style="font-weight:400;text-transform:none;letter-spacing:0;color:var(--text-tertiary)">(opsional)</span>
        </label>
        <textarea name="deskripsi" id="deskripsi"
                  class="form-input form-textarea"
                  placeholder="Keterangan kondisi kerusakan..."></textarea>
      </div>

      <div class="form-group">
        <label class="form-label">Foto</label>
        <div class="file-input-wrapper">
          <input type="file" name="foto" id="foto" accept="image/*">
          <label for="foto" class="file-input-label">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <rect x="3" y="3" width="18" height="18" rx="2"/>
              <circle cx="8.5" cy="8.5" r="1.5"/>
              <path d="m21 15-5-5L5 21"/>
            </svg>
            Pilih foto (JPG, PNG — maks 2 MB)
          </label>
        </div>
        <img id="photoPreview" class="photo-preview" alt="Preview foto">
      </div>

      <div class="modal-footer" id="modalFooter">
        <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
        <button type="submit" class="btn-save" id="submitBtn">Simpan Data</button>
      </div>
    </form>
  </div>
</div>

{{-- ============================================================ --}}
{{--  MODAL DETAIL                                                --}}
{{-- ============================================================ --}}
<div class="modal-overlay" id="detailModal">
  <div class="modal">
    <div class="modal-header">
      <h3 class="modal-title">Detail Data Kerusakan</h3>
      <button class="close-btn" onclick="closeDetailModal()" aria-label="Tutup">&times;</button>
    </div>

    <div class="detail-body" id="detailContent">
      <div class="detail-row">
        <span class="detail-label">Tanggal Input</span>
        <span class="detail-value" id="detailTanggal"></span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Nama Barang</span>
        <span class="detail-value" id="detailNama"></span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Kode Barang</span>
        <span class="detail-value" id="detailKode"
              style="font-family:monospace;font-weight:700;font-size:13px;"></span>
      </div>
      <div class="detail-row">
        <span class="detail-label">NUP</span>
        <span class="detail-value" id="detailNup"></span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Kondisi</span>
        <span class="detail-value" id="detailKondisi"></span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Lokasi</span>
        <span class="detail-value" id="detailLokasi"></span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Deskripsi</span>
        <span class="detail-value" id="detailDeskripsi"
              style="color:var(--text-secondary);"></span>
      </div>
      <div class="detail-row" id="detailFotoContainer"></div>
    </div>

    <div class="modal-footer">
      <button type="button" class="btn-cancel" onclick="closeDetailModal()">Tutup</button>
    </div>
  </div>
</div>

{{-- ============================================================ --}}
{{--  MODAL POPUP PILIH BARANG (DARI MASTER ASET TETAP)            --}}
{{-- ============================================================ --}}
<div class="modal-overlay" id="pilihBarangModal" style="z-index: 350;">
  <div class="modal modal-picker">
    <div class="modal-header">
      <div>
        <h3 class="modal-title" style="display:flex;align-items:center;gap:8px;">
          <svg width="19" height="19" fill="none" stroke="var(--primary)" viewBox="0 0 24 24" stroke-width="2.2">
            <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
          </svg>
          Pilih Barang dari Data Aset
        </h3>
        <p style="font-size:12px;color:var(--text-tertiary);margin-top:2px;">
          Cari dan klik tombol "Pilih" untuk mengisi otomatis formulir kerusakan
        </p>
      </div>
      <button class="close-btn" onclick="closePilihBarangModal()" aria-label="Tutup">&times;</button>
    </div>

    <div class="picker-body">
      {{-- Search bar & Loader --}}
      <div class="picker-search-bar">
        <div class="picker-search-input-wrap">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
          </svg>
          <input type="text" id="pickerSearchInput" placeholder="Cari nama barang, kode barang, NUP, merek, atau lokasi..." oninput="handlePickerSearch(this.value)">
          <button type="button" class="picker-search-clear" id="pickerSearchClear" onclick="clearPickerSearch()" style="display:none;" title="Hapus pencarian">&times;</button>
        </div>
        <div id="pickerLoading" class="picker-loader" style="display:none;">
          <div class="spinner-small"></div>
          <span>Mencari data...</span>
        </div>
      </div>

      {{-- Table --}}
      <div class="picker-table-wrap">
        <table class="picker-table">
          <thead>
            <tr>
              <th style="width: 42px; text-align:center;">No</th>
              <th style="width: 130px;">Kode Barang</th>
              <th style="width: 65px;">NUP</th>
              <th>Nama Barang</th>
              <th>Lokasi</th>
              <th style="width: 95px; text-align: center;">Aksi</th>
            </tr>
          </thead>
          <tbody id="pickerTableBody">
            {{-- Data dimasukkan secara dinamis via JavaScript --}}
          </tbody>
        </table>
      </div>

      {{-- Footer & Pagination --}}
      <div class="picker-footer">
        <div class="picker-count" id="pickerCountText">Memuat data barang...</div>
        <div class="picker-pagination" id="pickerPagination"></div>
      </div>
    </div>
  </div>
</div>

{{-- TOAST NOTIFIKASI PILIH BARANG --}}
<div class="picker-toast" id="pickerToast">
  <svg width="18" height="18" fill="none" stroke="#10b981" viewBox="0 0 24 24" stroke-width="2.5">
    <path d="M20 6L9 17l-5-5"/>
  </svg>
  <span id="pickerToastText">Barang berhasil dipilih!</span>
</div>

{{-- ============================================================ --}}
{{--  JAVASCRIPT                                                  --}}
{{-- ============================================================ --}}
<script>
let currentKerusakanId = null;
let isEditMode = false;

/* ---------- Search / Filter ---------- */
function filterTable() {
  const q = document.getElementById('searchInput').value.toLowerCase().trim();
  document.querySelectorAll('#tableBody tr').forEach(row => {
    const kode = row.dataset.kode || '';
    const nama = row.dataset.nama || '';
    row.style.display = (!q || kode.includes(q) || nama.includes(q)) ? '' : 'none';
  });
}

/* ---------- Open CRUD Modal ---------- */
function openModal(action, id = null) {
  const modal   = document.getElementById('crudModal');
  const title   = document.getElementById('modalTitle');
  const form    = document.getElementById('crudForm');
  const preview = document.getElementById('photoPreview');

  form.reset();
  preview.style.display = 'none';

  if (action === 'create') {
    title.textContent = 'Tambah Data Kerusakan';
    form.action = '{{ route("adminsarpras.kerusakan.store") }}';
    document.getElementById('submitBtn').textContent = 'Simpan Data';
    isEditMode = false;

  } else if (action === 'edit' && id) {
    currentKerusakanId = id;
    title.textContent = 'Edit Data Kerusakan';
    form.action = '{{ route("adminsarpras.kerusakan.update.ajax", ":id") }}'.replace(':id', id);
    document.getElementById('kerusakanId').value = id;
    document.getElementById('submitBtn').textContent = 'Update Data';
    loadKerusakanData(id);
    isEditMode = true;

  } else if (action === 'view' && id) {
    openDetailModal(id);
    return;
  }

  modal.classList.add('open');
}

function closeModal() {
  document.getElementById('crudModal').classList.remove('open');
}

/* ---------- Load data into edit form ---------- */
function loadKerusakanData(id) {
  fetch(`/adminsarpras/data-kerusakan/${id}/edit`)
    .then(r => r.json())
    .then(data => {
      document.getElementById('tanggal_input').value = data.tanggal_input;
      document.getElementById('nama_barang').value   = data.nama_barang;
      document.getElementById('kode_barang').value   = data.kode_barang;
      document.getElementById('nup').value           = data.nup;
      document.getElementById('kondisi').value       = data.kondisi;
      document.getElementById('lokasi').value        = data.lokasi;
      document.getElementById('deskripsi').value     = data.deskripsi || '';

      if (data.foto) {
        const p = document.getElementById('photoPreview');
        p.src = data.foto_url;
        p.style.display = 'block';
      }
    })
    .catch(err => console.error('Load error:', err));
}

/* ---------- Detail Modal ---------- */
function openDetailModal(id) {
  fetch(`/adminsarpras/data-kerusakan/${id}`)
    .then(r => r.json())
    .then(data => {
      document.getElementById('detailTanggal').textContent =
        new Date(data.tanggal_input).toLocaleDateString('id-ID', {
          day: '2-digit', month: 'long', year: 'numeric'
        });
      document.getElementById('detailNama').textContent    = data.nama_barang;
      document.getElementById('detailKode').textContent    = data.kode_barang;
      document.getElementById('detailNup').textContent     = data.nup;
      document.getElementById('detailKondisi').innerHTML   =
        `<span class="status-badge ${getKondisiClass(data.kondisi)}">${data.kondisi}</span>`;
      document.getElementById('detailLokasi').textContent  = data.lokasi;
      document.getElementById('detailDeskripsi').textContent = data.deskripsi || '—';

      const fotoEl = document.getElementById('detailFotoContainer');
      fotoEl.innerHTML = data.foto
        ? `<span class="detail-label">Foto</span>
           <span class="detail-value">
             <img src="${data.foto_url}" class="detail-photo" alt="Foto kerusakan">
           </span>`
        : `<span class="detail-label">Foto</span>
           <span class="detail-value" style="color:var(--text-secondary)">Tidak ada foto</span>`;

      document.getElementById('detailModal').classList.add('open');
    })
    .catch(err => console.error('Detail error:', err));
}

function closeDetailModal() {
  document.getElementById('detailModal').classList.remove('open');
}

function getKondisiClass(kondisi) {
  const map = {
    'Baik'         : 'badge-baik',
    'Rusak Ringan' : 'badge-rusak-ringan',
    'Rusak Berat'  : 'badge-rusak-berat',
  };
  return map[kondisi] || 'badge-rusak-sedang';
}

/* ---------- Delete ---------- */
function confirmDelete(id) {
  if (!confirm('Yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.')) return;
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = `/adminsarpras/data-kerusakan/${id}`;
  form.innerHTML = `@csrf @method('DELETE')`;
  document.body.appendChild(form);
  form.submit();
}

/* ---------- View photo ---------- */
function viewPhoto(url) {
  window.open(url, '_blank');
}

/* ---------- Photo preview ---------- */
document.getElementById('foto').addEventListener('change', function (e) {
  const file = e.target.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = e => {
    const p = document.getElementById('photoPreview');
    p.src = e.target.result;
    p.style.display = 'block';
  };
  reader.readAsDataURL(file);
});


/* ---------- Auto-hide alerts ---------- */
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.alert').forEach(el => {
    setTimeout(() => {
      el.style.transition = 'opacity .4s';
      el.style.opacity = '0';
      setTimeout(() => el.remove(), 400);
    }, 5000);
  });
});

/* ---------- Close modal on backdrop click ---------- */
document.getElementById('crudModal').addEventListener('click', function (e) {
  if (e.target === this) closeModal();
});
document.getElementById('detailModal').addEventListener('click', function (e) {
  if (e.target === this) closeDetailModal();
});

/* ============================================================ */
/*  POPUP PILIH BARANG (ASET TETAP) LOGIC                       */
/* ============================================================ */
let pickerSearchTimer = null;
let pickerCurrentPage = 1;
let pickerCurrentQuery = '';
let pickerDataLoaded = false;

function openPilihBarangModal() {
  const modal = document.getElementById('pilihBarangModal');
  modal.classList.add('open');

  const searchInput = document.getElementById('pickerSearchInput');
  setTimeout(() => { searchInput.focus(); }, 100);

  if (!pickerDataLoaded || searchInput.value.trim() !== '') {
    fetchBarangList(1);
  }
}

function closePilihBarangModal() {
  document.getElementById('pilihBarangModal').classList.remove('open');
}

function handlePickerSearch(val) {
  const clearBtn = document.getElementById('pickerSearchClear');
  clearBtn.style.display = val ? 'block' : 'none';

  clearTimeout(pickerSearchTimer);
  pickerSearchTimer = setTimeout(() => {
    pickerCurrentQuery = val.trim();
    fetchBarangList(1);
  }, 300);
}

function clearPickerSearch() {
  const input = document.getElementById('pickerSearchInput');
  input.value = '';
  document.getElementById('pickerSearchClear').style.display = 'none';
  pickerCurrentQuery = '';
  fetchBarangList(1);
  input.focus();
}

function fetchBarangList(page = 1) {
  pickerCurrentPage = page;
  const loader = document.getElementById('pickerLoading');
  const tbody  = document.getElementById('pickerTableBody');
  const countEl= document.getElementById('pickerCountText');
  const pagEl  = document.getElementById('pickerPagination');

  loader.style.display = 'flex';

  const url = `{{ route('adminsarpras.kerusakan.pilih-barang') }}?q=${encodeURIComponent(pickerCurrentQuery)}&page=${page}`;

  fetch(url)
    .then(res => {
      if (!res.ok) throw new Error('Network error');
      return res.json();
    })
    .then(data => {
      loader.style.display = 'none';
      pickerDataLoaded = true;

      const items = data.items || [];
      const total = data.total || 0;
      const lastPage = data.last_page || 1;
      const startNum = ((page - 1) * data.per_page) + 1;
      const endNum = Math.min(page * data.per_page, total);

      if (total === 0) {
        tbody.innerHTML = `
          <tr>
            <td colspan="6" style="text-align: center; padding: 40px 16px; color: var(--text-tertiary);">
              <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom:8px; display:inline-block;">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
              </svg>
              <div style="font-weight: 600; color: var(--text-secondary); font-size: 14px;">Barang tidak ditemukan</div>
              <div style="font-size: 12px; margin-top: 4px;">Coba gunakan kata kunci lain atau periksa kembali ejaan.</div>
            </td>
          </tr>
        `;
        countEl.textContent = 'Tidak ada barang yang cocok';
        pagEl.innerHTML = '';
        return;
      }

      countEl.textContent = `Menampilkan ${startNum}–${endNum} dari ${total.toLocaleString('id-ID')} barang aset`;

      // Render rows
      let html = '';
      items.forEach((item, index) => {
        const rowNum = startNum + index;
        const itemJson = JSON.stringify(item).replace(/"/g, '&quot;');
        const badgeSudahAda = item.sudah_ada 
          ? `<span style="font-size:10px; background:#fef3c7; color:#b45309; border:1px solid #fde68a; padding:1px 6px; border-radius:4px; margin-left:6px; font-weight:600;">Sudah Dicatat</span>` 
          : '';

        html += `
          <tr>
            <td style="text-align: center; color: var(--text-tertiary); font-weight: 600;">${rowNum}</td>
            <td>
              <span style="font-family: monospace; font-size: 11.5px; font-weight: 700; background: var(--bg); padding: 3px 7px; border-radius: 6px; border: 1px solid var(--border); color: var(--text-secondary); display: inline-block;">
                ${escapeHtml(item.kode_barang)}
              </span>
            </td>
            <td style="color: var(--text-secondary); font-weight: 600; font-size: 12.5px;">${item.nup || '—'}</td>
            <td>
              <div style="font-weight: 600; color: var(--text-primary); font-size: 13.5px;">
                ${escapeHtml(item.nama_barang)}
                ${badgeSudahAda}
              </div>
              <div style="display: flex; gap: 6px; align-items: center; margin-top: 3px; flex-wrap: wrap;">
                ${item.merek ? `<span class="picker-tag">Merek: ${escapeHtml(item.merek)}</span>` : ''}
                ${item.kategori ? `<span class="picker-tag" style="background:#eef2ff; color:#3b6bda;">${escapeHtml(item.kategori)}</span>` : ''}
              </div>
            </td>
            <td style="color: var(--text-secondary); font-size: 12.5px;">
              ${item.lokasi ? `<span style="display:flex;align-items:center;gap:4px;"><svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg>${escapeHtml(item.lokasi)}</span>` : '—'}
            </td>
            <td style="text-align: center;">
              <button type="button" class="btn-choose" onclick='pilihBarangItem(${itemJson})' title="Pilih barang ini">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                Pilih
              </button>
            </td>
          </tr>
        `;
      });
      tbody.innerHTML = html;

      // Render pagination
      renderPickerPagination(page, lastPage);
    })
    .catch(err => {
      loader.style.display = 'none';
      tbody.innerHTML = `
        <tr>
          <td colspan="6" style="text-align: center; padding: 25px; color: var(--danger);">
            Gagal memuat data barang. Silakan periksa koneksi atau coba lagi.
          </td>
        </tr>
      `;
      countEl.textContent = 'Gagal memuat data';
      pagEl.innerHTML = '';
      console.error(err);
    });
}

function renderPickerPagination(current, last) {
  const pagEl = document.getElementById('pickerPagination');
  if (last <= 1) {
    pagEl.innerHTML = '';
    return;
  }

  let html = '';
  html += `<button type="button" class="picker-page-btn" ${current === 1 ? 'disabled' : ''} onclick="fetchBarangList(${current - 1})">&laquo; Prev</button>`;

  // Tampilkan max 5 halaman
  let start = Math.max(1, current - 2);
  let end = Math.min(last, start + 4);
  if (end - start < 4) start = Math.max(1, end - 4);

  for (let p = start; p <= end; p++) {
    html += `<button type="button" class="picker-page-btn ${p === current ? 'active' : ''}" onclick="fetchBarangList(${p})">${p}</button>`;
  }

  html += `<button type="button" class="picker-page-btn" ${current === last ? 'disabled' : ''} onclick="fetchBarangList(${current + 1})">Next &raquo;</button>`;
  pagEl.innerHTML = html;
}

function pilihBarangItem(item) {
  // Masukkan nilai ke form
  const inputNama = document.getElementById('nama_barang');
  const inputKode = document.getElementById('kode_barang');
  const inputNup  = document.getElementById('nup');
  const inputLok  = document.getElementById('lokasi');

  inputNama.value = item.nama_barang || '';
  inputKode.value = item.kode_barang || '';
  inputNup.value  = item.nup || '';
  inputLok.value  = item.lokasi || '';

  // Efek highlight
  [inputNama, inputKode, inputNup, inputLok].forEach(el => {
    el.classList.remove('field-highlight');
    void el.offsetWidth; // trigger reflow
    el.classList.add('field-highlight');
  });

  closePilihBarangModal();
  showPickerToast(`Barang "${item.nama_barang}" berhasil dipilih!`);
}

function showPickerToast(msg) {
  const toast = document.getElementById('pickerToast');
  const toastText = document.getElementById('pickerToastText');
  toastText.textContent = msg;
  toast.classList.add('show');
  setTimeout(() => {
    toast.classList.remove('show');
  }, 3200);
}

function escapeHtml(str) {
  if (!str) return '';
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}

/* Close picker modal on backdrop click */
document.getElementById('pilihBarangModal').addEventListener('click', function (e) {
  if (e.target === this) closePilihBarangModal();
});
</script>

</body>
</html>
