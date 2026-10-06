<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Data Gedung - Admin Sarana Prasarana</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{
  --primary:#2563eb;--primary-soft:#dbeafe;--primary-dark:#1d4ed8;
  --teal:#0d9488;--teal-soft:#ccfbf1;
  --amber:#d97706;--amber-soft:#fef3c7;
  --rose:#e11d48;--rose-soft:#ffe4e6;
  --sidebar-bg:#fff;
  --body-bg:#f1f5f9;
  --surface:#fff;
  --border:#e2e8f0;--border2:#cbd5e1;
  --txt:#0f172a;--muted:#64748b;--hint:#94a3b8;
  --sidebar-width:240px;
  --r:10px;--rL:14px;
}
body{font-family:'DM Sans',sans-serif;background:var(--body-bg);color:var(--txt);display:flex;min-height:100vh}


/* ─── MAIN ─── */
.main{margin-left:var(--sidebar-width);flex:1;display:flex;flex-direction:column;min-height:100vh;width:calc(100% - var(--sidebar-width))}

/* ─── TOPBAR ─── */
.topbar{
  background:var(--surface);border-bottom:1px solid var(--border);
  padding:0 28px;height:64px;display:flex;align-items:center;justify-content:space-between;
  position:sticky;top:0;z-index:50;box-shadow:0 1px 4px rgba(0,0,0,.05);flex-shrink:0;
}
.topbar-title{font-size:16px;font-weight:700;letter-spacing:-.3px}
.topbar-right{display:flex;align-items:center;gap:14px}
.notif-btn{
  width:38px;height:38px;border-radius:10px;border:1px solid var(--border);
  background:var(--surface);display:flex;align-items:center;justify-content:center;
  cursor:pointer;position:relative;transition:all .2s;
}
.notif-btn:hover{background:var(--primary-soft);border-color:var(--primary)}
.notif-dot{width:8px;height:8px;background:#ef4444;border-radius:50%;position:absolute;top:6px;right:6px;border:2px solid #fff}
.date-text{font-size:12px;color:var(--muted);font-weight:500;white-space:nowrap}
.btn-keluar{
  display:flex;align-items:center;gap:6px;
  padding:8px 14px;border-radius:10px;border:1px solid var(--border);
  background:var(--surface);color:var(--muted);
  font-size:12px;font-weight:600;font-family:inherit;cursor:pointer;transition:all .2s;
}
.btn-keluar:hover{background:#fef2f2;color:#ef4444;border-color:#ef4444}

/* ─── CONTENT ─── */
.content{padding:28px;flex:1}

/* ─── HEADER ─── */
.page-hdr{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:22px}
.page-hdr h1{font-size:22px;font-weight:700;letter-spacing:-.5px}
.page-hdr p{font-size:13px;color:var(--muted);margin-top:2px}
.btn-add{
  display:flex;align-items:center;gap:7px;
  background:linear-gradient(135deg,var(--primary),var(--primary-dark));
  color:#fff;border:none;border-radius:var(--r);
  padding:10px 18px;font-size:13px;font-weight:600;
  cursor:pointer;font-family:inherit;
  transition:all .2s;
  box-shadow:0 4px 12px rgba(37,99,235,.3);
  white-space:nowrap;
}
.btn-add:hover{transform:translateY(-2px);box-shadow:0 6px 16px rgba(37,99,235,.4)}
.btn-add:active{transform:translateY(0)}
.btn-add svg{width:15px;height:15px;flex-shrink:0}



/* ─── STATS ─── */
.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:22px}
.stat{
  background:var(--surface);border:0.5px solid var(--border);
  border-radius:var(--rL);padding:16px 18px;
  display:flex;align-items:center;gap:14px;
}
.stat-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.si-blue{background:var(--primary-soft)} .si-teal{background:var(--teal-soft)} .si-amber{background:var(--amber-soft)} .si-rose{background:var(--rose-soft)}
.stat-icon svg{width:20px;height:20px}
.sib svg{color:var(--primary)} .sit svg{color:var(--teal)} .sia svg{color:var(--amber)} .sir svg{color:var(--rose)}
.stat-info label{font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:var(--muted)}
.stat-info strong{font-size:24px;font-weight:700;display:block;line-height:1.1;letter-spacing:-.8px}
.sib .stat-info strong{color:var(--primary)} .sit .stat-info strong{color:var(--teal)} .sia .stat-info strong{color:var(--amber)} .sir .stat-info strong{color:var(--rose)}

/* ─── TOOLBAR ─── */
.toolbar{display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:16px}
.search-box{position:relative;flex:1;min-width:200px}
.search-box svg{position:absolute;left:11px;top:50%;transform:translateY(-50%);width:15px;height:15px;color:var(--hint);pointer-events:none}
.search-box input{
  width:100%;padding:10px 12px 10px 36px;
  border:0.5px solid var(--border2);border-radius:var(--r);
  font-size:13px;font-family:inherit;background:var(--surface);color:var(--txt);
  outline:none;transition:border-color .15s,box-shadow .15s;
}
.search-box input:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(37,99,235,.1)}
.search-box input::placeholder{color:var(--hint)}
.filter-select{
  padding:10px 14px;border:0.5px solid var(--border2);border-radius:var(--r);
  font-size:13px;font-family:inherit;background:var(--surface);color:var(--txt);
  outline:none;cursor:pointer;transition:border-color .15s;
}
.filter-select:focus{border-color:var(--primary)}

/* ─── TABLE ─── */
.table-card{background:var(--surface);border:0.5px solid var(--border);border-radius:var(--rL);overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.04)}
table{width:100%;border-collapse:collapse}
thead th{
  font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;
  padding:12px 16px;text-align:left;background:#f8fafc;border-bottom:0.5px solid var(--border);
}
tbody td{padding:14px 16px;font-size:13px;border-bottom:0.5px solid var(--border);vertical-align:middle}
tbody tr:last-child td{border-bottom:none}
tbody tr:hover{background:#fafbff;transition:background .15s}

/* PAGINATION */
  .table-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 20px;
    border-top: 1px solid var(--border);
    font-size: 13px; color: var(--muted);
  }

.foto-thumb{
  width:40px;height:40px;border-radius:9px;background:#f1f5f9;
  display:flex;align-items:center;justify-content:center;
  border:0.5px solid var(--border);overflow:hidden;
}
.foto-thumb img{width:100%;height:100%;object-fit:cover}
.foto-thumb svg{width:18px;height:18px;color:var(--hint)}
.gedung-name{font-weight:600;color:var(--txt);display:block}
.gedung-fac{font-size:11px;color:var(--muted);display:block;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}

/* badges */
.badge{display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:700;white-space:nowrap}
.b-green{background:#dcfce7;color:#166534}
.b-amber{background:#fef3c7;color:#92400e}
.b-rose{background:#ffe4e6;color:#9f1239}
.b-blue{background:#dbeafe;color:#1e40af}
.b-slate{background:#f1f5f9;color:#475569}

/* action buttons */
.acts{display:flex;gap:6px}
.act{
  width:32px;height:32px;border-radius:8px;
  display:flex;align-items:center;justify-content:center;
  cursor:pointer;border:0.5px solid var(--border);
  background:var(--surface);transition:all .15s;flex-shrink:0;
}
.act svg{width:14px;height:14px}
.act-eye{color:var(--primary);border-color:#bfdbfe;background:var(--primary-soft)}
.act-eye:hover{background:var(--primary);color:#fff;transform:translateY(-1px)}
.act-edit{color:var(--amber);border-color:#fde68a;background:var(--amber-soft)}
.act-edit:hover{background:var(--amber);color:#fff;transform:translateY(-1px)}
.act-del{color:var(--rose);border-color:#fecdd3;background:var(--rose-soft)}
.act-del:hover{background:var(--rose);color:#fff;transform:translateY(-1px)}

/* ─── OVERLAY ─── */
.overlay{
  position:fixed;inset:0;background:rgba(15,23,42,.5);
  backdrop-filter:blur(4px);display:none;align-items:center;justify-content:center;
  z-index:1000;padding:16px;
}
.overlay.open{display:flex;animation:fadeIn .2s ease}
@keyframes fadeIn{from{opacity:0}to{opacity:1}}

/* ─── MODAL ─── */
.modal{
  background:var(--surface);border-radius:16px;
  width:520px;max-width:100%;max-height:90vh;
  overflow-y:auto;border:0.5px solid var(--border);
  box-shadow:0 24px 64px rgba(0,0,0,.15);
  animation:slideUp .25s ease;
}
@keyframes slideUp{from{opacity:0;transform:translateY(18px)}to{opacity:1;transform:translateY(0)}}

.modal-head{
  display:flex;align-items:center;justify-content:space-between;
  padding:20px 24px 16px;border-bottom:0.5px solid var(--border);
  position:sticky;top:0;background:var(--surface);z-index:2;
}
.modal-head-icon{
  width:38px;height:38px;border-radius:10px;
  display:flex;align-items:center;justify-content:center;
  margin-right:10px;flex-shrink:0;
}
.mhi-blue{background:var(--primary-soft)} .mhi-amber{background:var(--amber-soft)} .mhi-rose{background:var(--rose-soft)} .mhi-teal{background:var(--teal-soft)}
.modal-head-icon svg{width:18px;height:18px}
.mhi-blue svg{color:var(--primary)} .mhi-amber svg{color:var(--amber)} .mhi-rose svg{color:var(--rose)} .mhi-teal svg{color:var(--teal)}
.modal-head-left{display:flex;align-items:center}
.modal-head h3{font-size:15px;font-weight:700;letter-spacing:-.3px}
.modal-head p{font-size:12px;color:var(--muted);margin-top:1px}
.modal-close{
  width:32px;height:32px;border-radius:8px;border:0.5px solid var(--border);
  background:var(--surface);display:flex;align-items:center;justify-content:center;
  cursor:pointer;color:var(--muted);transition:all .15s;flex-shrink:0;
}
.modal-close:hover{background:#f1f5f9;color:var(--txt)}
.modal-close svg{width:14px;height:14px}
.modal-body{padding:22px 24px}
.modal-foot{
  display:flex;justify-content:flex-end;gap:8px;
  padding:14px 24px 20px;border-top:0.5px solid var(--border);
}

/* ─── FORM ─── */
.frow{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.fg{margin-bottom:16px}
.fg:last-child{margin-bottom:0}
.fg label{
  font-size:11px;font-weight:700;color:var(--muted);
  display:block;margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em;
}
.fg input,.fg select,.fg textarea{
  width:100%;padding:10px 13px;
  border:0.5px solid var(--border2);border-radius:var(--r);
  font-size:13px;font-family:inherit;background:var(--surface);color:var(--txt);
  outline:none;transition:border-color .15s,box-shadow .15s;
}
.fg input:focus,.fg select:focus,.fg textarea:focus{
  border-color:var(--primary);box-shadow:0 0 0 3px rgba(37,99,235,.1);
}
.fg textarea{resize:vertical;min-height:80px;line-height:1.6}
.inp-prefix{display:flex;align-items:stretch}
.inp-prefix span{
  padding:10px 11px;background:#f8fafc;
  border:0.5px solid var(--border2);border-right:none;
  border-radius:var(--r) 0 0 var(--r);
  font-size:12px;font-weight:700;color:var(--muted);
  display:flex;align-items:center;
}
.inp-prefix input{border-radius:0 var(--r) var(--r) 0;flex:1;min-width:0}
.req{color:var(--rose);margin-left:2px}
.fg small{font-size:11px;color:var(--hint);margin-top:4px;display:block}

/* ─── BUTTONS ─── */
.btn-sec{
  padding:10px 18px;border:0.5px solid var(--border2);border-radius:var(--r);
  background:var(--surface);font-size:13px;font-weight:600;
  font-family:inherit;color:var(--muted);cursor:pointer;transition:all .15s;
}
.btn-sec:hover{background:#f8fafc;border-color:var(--txt);color:var(--txt)}
.btn-prim{
  padding:10px 20px;background:linear-gradient(135deg,var(--primary),var(--primary-dark));
  color:#fff;border:none;border-radius:var(--r);
  font-size:13px;font-weight:700;font-family:inherit;cursor:pointer;
  transition:all .2s;box-shadow:0 4px 10px rgba(37,99,235,.25);
}
.btn-prim:hover{transform:translateY(-1px);box-shadow:0 6px 14px rgba(37,99,235,.35)}
.btn-warn{
  padding:10px 20px;background:var(--amber);color:#fff;border:none;border-radius:var(--r);
  font-size:13px;font-weight:700;font-family:inherit;cursor:pointer;
  transition:all .2s;box-shadow:0 4px 10px rgba(217,119,6,.25);
}
.btn-warn:hover{background:#b45309;transform:translateY(-1px)}
.btn-danger{
  padding:10px 20px;background:var(--rose);color:#fff;border:none;border-radius:var(--r);
  font-size:13px;font-weight:700;font-family:inherit;cursor:pointer;
  transition:all .2s;box-shadow:0 4px 10px rgba(225,29,72,.25);
}
.btn-danger:hover{background:#be123c;transform:translateY(-1px)}

/* ─── DETAIL MODAL ─── */
.det-photo{
  width:100%;height:156px;background:linear-gradient(135deg,#dbeafe,#ede9fe);
  border-radius:var(--r);display:flex;align-items:center;justify-content:center;
  margin-bottom:16px;border:0.5px solid var(--border);
}
.det-photo svg{width:52px;height:52px;color:#94a3b8}
.det-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px}
.det-item{background:#f8fafc;border-radius:var(--r);padding:12px;border:0.5px solid var(--border)}
.det-item label{font-size:10px;font-weight:700;color:var(--hint);text-transform:uppercase;letter-spacing:.06em;display:block;margin-bottom:3px}
.det-item strong{font-size:13px;font-weight:600;color:var(--txt);display:block}
.section-lbl{font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px}
.det-fac{
  background:#f8fafc;border-radius:var(--r);padding:12px;
  font-size:13px;color:var(--muted);border:0.5px solid var(--border);line-height:1.6;
}

/* ─── DELETE CONFIRM ─── */
.del-icon{width:60px;height:60px;background:var(--rose-soft);border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px}
.del-icon svg{width:28px;height:28px;color:var(--rose)}
.del-txt{text-align:center}
.del-txt h4{font-size:17px;font-weight:700;margin-bottom:8px;letter-spacing:-.3px}
.del-txt p{font-size:13px;color:var(--muted);line-height:1.6}
.del-txt strong{color:var(--txt)}

/* ─── EMPTY STATE ─── */
.empty-state{text-align:center;padding:60px 20px}
.empty-state svg{width:60px;height:60px;color:var(--border2);margin:0 auto 16px;display:block}
.empty-state h4{font-size:16px;font-weight:600;color:var(--muted);margin-bottom:6px}
.empty-state p{font-size:13px;color:var(--hint)}

/* ─── TOAST ─── */
.toast{
  position:fixed;bottom:24px;right:24px;
  background:#0f172a;color:#fff;
  padding:12px 18px;border-radius:12px;
  font-size:13px;font-weight:500;z-index:2000;
  display:none;align-items:center;gap:10px;
  box-shadow:0 8px 24px rgba(0,0,0,.2);
}
.toast.show{display:flex;animation:slideUp .3s ease}
.toast-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0}

/* ─── SCROLLBAR ─── */
::-webkit-scrollbar{width:5px;height:5px}
::-webkit-scrollbar-track{background:transparent}
::-webkit-scrollbar-thumb{background:var(--border2);border-radius:3px}
::-webkit-scrollbar-thumb:hover{background:#94a3b8}

/* ─── RESPONSIVE ─── */
@media(max-width:900px){
  .stats{grid-template-columns:repeat(2,1fr)}
}
@media(max-width:768px){
  :root{--sidebar-width:0px}
  .sidebar{transform:translateX(-100%);width:240px}
  .main{margin-left:0;width:100%}
  .topbar{padding:0 16px}
  .content{padding:16px}
  .frow{grid-template-columns:1fr}
  .stats{grid-template-columns:repeat(2,1fr)}
  .modal{width:95vw}
}
@media(max-width:480px){
  .stats{grid-template-columns:1fr}
}
</style>
</head>
<body>

 @include('partials.sidebar')


<!-- ═══════════════════════════════ MAIN ═══════════════════════════════ -->
<main class="main">

  <!-- TOPBAR -->
  <div class="topbar">
    <span class="topbar-title">Data Gedung</span>
    <div class="topbar-right">
      {{-- <div class="notif-btn">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="#64748b"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
        <span class="notif-dot"></span>
      </div> --}}
     <span class="date-text">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}</span>
      <button class="btn-keluar">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5-5-5zm-5 11H5V5h7V3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h7v-2z"/></svg>
        Keluar
      </button>
    </div>
  </div>

  <!-- CONTENT -->
  <div class="content">

    <!-- PAGE HEADER -->
    <div class="page-hdr">
      <div>
        <h1>Data Gedung</h1>
      </div>
      <button class="btn-add" onclick="openModal('tambah')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Gedung
      </button>
    </div>

    @if(session('success'))
    <div style="background: rgba(13,148,136,0.1); color: var(--teal); padding: 12px 16px; border-radius: var(--r); margin-bottom: 18px; font-size: 13px; border: 1px solid rgba(13,148,136,0.25); display: flex; align-items: center; gap: 8px;">
      <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
      {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div style="background: rgba(225,29,72,0.1); color: var(--rose); padding: 12px 16px; border-radius: var(--r); margin-bottom: 18px; font-size: 13px; border: 1px solid rgba(225,29,72,0.25); display: flex; align-items: center; gap: 8px;">
      <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>
      {{ session('error') }}
    </div>
    @endif

    <!-- STATS -->
    <div class="stats">
      <div class="stat sib">
        <div class="stat-icon si-blue">
          <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z"/></svg>
        </div>
        <div class="stat-info"><label>Total Gedung</label><strong id="s-total">{{ $stats['total'] ?? 0 }}</strong></div>
      </div>
      <div class="stat sit">
        <div class="stat-icon si-teal">
          <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
        </div>
        <div class="stat-info"><label>Tersedia</label><strong id="s-avail">{{ $stats['tersedia'] ?? 0 }}</strong></div>
      </div>
      <div class="stat sia">
        <div class="stat-icon si-amber">
          <svg viewBox="0 0 24 24" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
        </div>
        <div class="stat-info"><label>Sedang Dipakai</label><strong id="s-used">{{ $stats['dipakai'] ?? 0 }}</strong></div>
      </div>
      <div class="stat sir">
        <div class="stat-icon si-rose">
          <svg viewBox="0 0 24 24" fill="currentColor"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>
        </div>
        <div class="stat-info"><label>Perlu Perhatian</label><strong id="s-warn">{{ $stats['renovasi'] ?? 0 }}</strong></div>
      </div>
    </div>

    <!-- TOOLBAR -->
    <div class="toolbar">
      <div class="search-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input type="text" placeholder="Cari nama gedung atau lokasi…" id="searchInput" oninput="filterTable()">
      </div>
      <select class="filter-select" id="statusFilter" onchange="filterTable()">
        <option value="">Semua Status</option>
        <option value="Tersedia">Tersedia</option>
        <option value="Sedang Dipakai">Sedang Dipakai</option>
        <option value="Renovasi">Renovasi</option>
        <option value="Perlu Perbaikan">Perlu Perbaikan</option>
      </select>
      <select class="filter-select" id="kategoriFilter" onchange="filterTable()">
        <option value="">Semua Kategori</option>
        @foreach(\App\Models\Gedung::kategoriOptions() as $kVal => $kLabel)
          <option value="{{ $kVal }}">{{ $kLabel }}</option>
        @endforeach
      </select>
    </div>

    <!-- TABLE -->
    <div class="table-card">
      <table>
        <thead>
          <tr>
            <th style="width:44px">No</th>
            <th style="width:100px">Foto</th>
            <th style="width:140px">Nama Gedung</th>
            <th style="width:100px">Lokasi</th>
            <th style="width:100px">Kapasitas</th>
            <th style="width:120px">Tarif Sewa</th>
            <th style="width:80px">Luas</th>
            <th style="width:110px">Status</th>
            <th style="width:110px">Kategori</th>
            <th style="width:110px">Aksi</th>
          </tr>
        </thead>
        <tbody id="tBody">
          @isset($gedung)
            @forelse($gedung as $index => $item)
            <tr
              data-nama="{{ strtolower($item->nama_gedung) }}"
              data-lokasi="{{ strtolower($item->lokasi) }}"
              data-status="{{ $item->ketersediaan }}"
              data-kategori="{{ $item->kategori }}"
            >
              <td style="color:var(--hint);font-family:'DM Mono',monospace;font-size:12px">
                {{ str_pad($gedung->firstItem() + $index, 2, '0', STR_PAD_LEFT) }}
              </td>
              <td>
                <div class="foto-thumb">
                  @if($item->foto_path)
                    <img src="{{ $item->foto_path }}" alt="{{ $item->nama_gedung }}">
                  @else
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z"/></svg>
                  @endif
                </div>
              </td>
              <td>
                <span class="gedung-name">{{ $item->nama_gedung }}</span>
                <span class="gedung-fac">{{ Str::limit($item->fasilitas ?? '', 50) }}</span>
              </td>
              <td style="color:var(--muted);font-size:12px">{{ $item->lokasi }}</td>
              <td><span class="badge b-blue">{{ number_format($item->kapasitas) }} org</span></td>
              <td style="font-weight:600;font-size:12px">{{ $item->tarif_sewa_format }}</td>
              <td style="color:var(--muted);font-size:12px">{{ $item->luas_bangunan }} m²</td>
              <td>{!! $item->ketersediaan_badge !!}</td>
              <td>
                @if($item->kategori)
                  <span class="badge b-blue">
                    <i class="fas fa-{{ $item->icon }}"></i>
                    {{ \App\Models\Gedung::kategoriOptions()[$item->kategori] ?? $item->kategori }}
                  </span>
                @else
                  <span class="badge b-slate">—</span>
                @endif
              </td>
              <td>
                <div class="acts">
                  <button class="act act-eye" title="Detail" onclick="openDetailAjax({{ $item->id }})">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                  </button>
                  <button class="act act-edit" title="Edit" onclick="openEditAjax({{ $item->id }})">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                  </button>
                  <button class="act act-del" title="Hapus" onclick="openHapus({{ $item->id }}, '{{ addslashes($item->nama_gedung) }}')">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                  </button>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="10">
                <div class="empty-state">
                  <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z"/></svg>
                  <h4>Belum ada data gedung</h4>
                  <p>Klik "Tambah Gedung" untuk menambahkan data pertama</p>
                </div>
              </td>
            </tr>
            @endforelse
          @else
          <tr>
            <td colspan="10">
              <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z"/></svg>
                <h4>Belum ada data gedung</h4>
                <p>Loading data...</p>
              </div>
            </td>
          </tr>
          @endisset
        </tbody>
      </table>
    </div>

    
    {{-- ✅ PAGINATION --}}
    @if($gedung->hasPages())
    <div class="table-footer">
        <span>Menampilkan {{ ($gedung->currentPage() - 1) * $gedung->perPage() + 1 }}–{{ min($gedung->total(), $gedung->currentPage() * $gedung->perPage()) }} dari {{ $gedung->total() }} data</span>
        <div class="pagination">
          @if($gedung->onFirstPage())
            <button class="page-btn" disabled style="opacity:0.5; cursor:not-allowed;"><i class="fas fa-chevron-left"></i></button>
          @else
            <a href="{{ $gedung->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>
          @endif

          @foreach($gedung->getUrlRange(max(1, $gedung->currentPage() - 2), min($gedung->lastPage(), $gedung->currentPage() + 2)) as $page => $url)
            <a href="{{ $url }}" class="page-btn {{ $gedung->currentPage() == $page ? 'active' : '' }}">{{ $page }}</a>
          @endforeach

          @if($gedung->hasMorePages())
            <a href="{{ $gedung->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>
          @else
            <button class="page-btn" disabled style="opacity:0.5; cursor:not-allowed;"><i class="fas fa-chevron-right"></i></button>
          @endif
        </div>
      </div>
      @endif
    <div class="table-footer">
        <span>Menampilkan {{ $gedung->firstItem() ?? 0 }}–{{ $gedung->lastItem() ?? 0 }} dari {{ $gedung->total() }} data</span>
        <div class="pagination">
          {{ $gedung->appends(request()->query())->links() }}
        </div>
    </div>
 </div><!-- /content -->
</main>

<!-- ═══════════════════════════════ MODALS ═══════════════════════════════ -->

<!-- ── TAMBAH ── -->
<div class="overlay" id="ov-tambah">
  <div class="modal">
    <div class="modal-head">
      <div class="modal-head-left">
        <div class="modal-head-icon mhi-blue">
          <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
        </div>
        <div>
          <h3>Tambah Gedung Baru</h3>
          <p>Isi form berikut untuk menambah data gedung</p>
        </div>
      </div>
      <button class="modal-close" onclick="closeModal('tambah')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <form id="formTambah" action="{{ route('adminsarpras.data-gedung.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="frow">
          <div class="fg">
            <label>Nama Gedung<span class="req">*</span></label>
            <input type="text" name="nama_gedung" id="t-nama" placeholder="Gedung Serba Guna A" required>
          </div>
          <div class="fg">
            <label>Kategori <span class="req">*</span></label>
            <select name="kategori" id="t-kategori" required>
              @foreach(\App\Models\Gedung::kategoriOptions() as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
              @endforeach
            </select>
          </div>
          <div class="fg">
            <label>Lokasi<span class="req">*</span></label>
            <input type="text" name="lokasi" id="t-lokasi" placeholder="Kampus Utara Blok B" required>
          </div>
          <div class="fg">
            <label>Luas Bangunan<span class="req">*</span></label>
            <input type="text" name="luas_bangunan" id="t-luas" placeholder="500 m²" required>
          </div>
          <div class="fg">
            <label>Kapasitas (orang)<span class="req">*</span></label>
            <input type="number" name="kapasitas" id="t-kap" placeholder="200" min="1" required>
          </div>
          <div class="fg">
            <label>Tarif Sewa / Hari<span class="req">*</span></label>
            <div class="inp-prefix">
              <span>Rp</span>
              <input type="number" name="tarif_sewa" id="t-tarif" placeholder="1500000" min="0" required>
            </div>
          </div>
          <div class="fg">
            <label>Status<span class="req">*</span></label>
            <select name="ketersediaan" id="t-status" required>
              <option value="Tersedia">Tersedia</option>
              <option value="Sedang Dipakai">Sedang Dipakai</option>
              <option value="Renovasi">Renovasi</option>
              <option value="Perlu Perbaikan">Perlu Perbaikan</option>
            </select>
          </div>
        </div>
        <div class="fg">
          <label>Foto Gedung</label>
          <input type="file" name="foto_url" accept="image/*">
          <small>Format JPG/PNG, maks. 2MB</small>
        </div>
        <div class="fg">
          <label>Fasilitas</label>
          <textarea name="fasilitas" id="t-fasilitas" placeholder="AC, Proyektor, Toilet, Parkir, Wi-Fi…"></textarea>
        </div>
        <div class="modal-foot" style="border-top:none;padding:0;margin-top:4px">
          <button type="button" class="btn-sec" onclick="closeModal('tambah')">Batal</button>
          <button type="submit" class="btn-prim">Simpan Gedung</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ── EDIT ── -->
<div class="overlay" id="ov-edit">
  <div class="modal">
    <div class="modal-head">
      <div class="modal-head-left">
        <div class="modal-head-icon mhi-amber">
          <svg viewBox="0 0 24 24" fill="currentColor"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
        </div>
        <div>
          <h3>Edit Gedung</h3>
          <p>Perbarui informasi data gedung</p>
        </div>
      </div>
      <button class="modal-close" onclick="closeModal('edit')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <form id="formEdit" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="id" id="e-id">
        <div class="frow">
          <div class="fg">
            <label>Nama Gedung<span class="req">*</span></label>
            <input type="text" name="nama_gedung" id="e-nama" required>
          </div>
          <div class="fg">
            <label>Kategori <span class="req">*</span>
            <select name="kategori" id="e-kategori" required>
              @foreach(\App\Models\Gedung::kategoriOptions() as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
              @endforeach
            </select></label>
          </div>
          <div class="fg">
            <label>Lokasi<span class="req">*</span></label>
            <input type="text" name="lokasi" id="e-lokasi" required>
          </div>
          <div class="fg">
            <label>Luas Bangunan<span class="req">*</span></label>
            <input type="text" name="luas_bangunan" id="e-luas" required>
          </div>
          <div class="fg">
            <label>Kapasitas (orang)<span class="req">*</span></label>
            <input type="number" name="kapasitas" id="e-kap" min="1" required>
          </div>
          <div class="fg">
            <label>Tarif Sewa / Hari<span class="req">*</span></label>
            <div class="inp-prefix">
              <span>Rp</span>
              <input type="number" name="tarif_sewa" id="e-tarif" min="0" required>
            </div>
          </div>
          <div class="fg">
            <label>Status<span class="req">*</span></label>
            <select name="ketersediaan" id="e-status" required>
              <option value="Tersedia">Tersedia</option>
              <option value="Sedang Dipakai">Sedang Dipakai</option>
              <option value="Renovasi">Renovasi</option>
              <option value="Perlu Perbaikan">Perlu Perbaikan</option>
            </select>
          </div>
        </div>
        <div class="fg">
          <label>Foto Gedung (kosongkan jika tidak diubah)</label>
          <input type="file" name="foto_url" accept="image/*">
          <small>Format JPG/PNG, maks. 2MB</small>
        </div>
        <div class="fg">
          <label>Fasilitas</label>
          <textarea name="fasilitas" id="e-fasilitas"></textarea>
        </div>
        <div class="modal-foot" style="border-top:none;padding:0;margin-top:4px">
          <button type="button" class="btn-sec" onclick="closeModal('edit')">Batal</button>
          <button type="submit" class="btn-warn">Update Gedung</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ── DETAIL ── -->
<div class="overlay" id="ov-detail">
  <div class="modal" style="width:480px">
    <div class="modal-head">
      <div class="modal-head-left">
        <div class="modal-head-icon mhi-teal">
          <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
        </div>
        <div>
          <h3 id="det-title">Detail Gedung</h3>
          <p>Informasi lengkap gedung</p>
        </div>
      </div>
      <button class="modal-close" onclick="closeModal('detail')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <div class="modal-body" id="detailBody">
      <!-- filled via JS/AJAX -->
    </div>
    <div class="modal-foot">
      <button class="btn-sec" onclick="closeModal('detail')">Tutup</button>
    </div>
  </div>
</div>

<!-- ── HAPUS ── -->
<div class="overlay" id="ov-hapus">
  <div class="modal" style="width:400px">
    <div class="modal-head">
      <div class="modal-head-left">
        <div class="modal-head-icon mhi-rose">
          <svg viewBox="0 0 24 24" fill="currentColor"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
        </div>
        <div>
          <h3>Konfirmasi Hapus</h3>
          <p>Tindakan ini tidak dapat dibatalkan</p>
        </div>
      </div>
      <button class="modal-close" onclick="closeModal('hapus')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <div class="del-icon">
        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
      </div>
      <div class="del-txt">
        <h4>Hapus Gedung?</h4>
        <p>Apakah Anda yakin ingin menghapus<br><strong id="del-name"></strong>?<br>Data yang terhapus tidak bisa dikembalikan.</p>
      </div>
    </div>
    <div class="modal-foot">
      <button class="btn-sec" onclick="closeModal('hapus')">Batal</button>
      <form id="formHapus" method="POST" style="display:inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-danger">Ya, Hapus</button>
      </form>
    </div>
  </div>
</div>

<!-- TOAST -->
<div class="toast" id="toast">
  <div class="toast-dot" id="toast-dot"></div>
  <span id="toast-msg"></span>
</div>

<!-- ═══════════════════════════════ SCRIPTS ═══════════════════════════════ -->
<script>
/* ── Date ── */
(function(){
  const d=new Date();
  const days=['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
  const months=['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
  document.getElementById('currentDate').textContent=`${days[d.getDay()]}, ${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`;
})();

/* ── Modal helpers ── */
function openModal(type){
  const el=document.getElementById('ov-'+type);
  if(el){el.classList.add('open');document.body.style.overflow='hidden';}
}
function closeModal(type){
  const el=document.getElementById('ov-'+type);
  if(el){el.classList.remove('open');document.body.style.overflow='';}
}

/* Close on overlay click */
document.querySelectorAll('.overlay').forEach(function(ov){
  ov.addEventListener('click',function(e){
    if(e.target===ov){
      var t=ov.id.replace('ov-','');
      closeModal(t);
    }
  });
});

/* Close on Escape */
document.addEventListener('keydown',function(e){
  if(e.key==='Escape'){
    document.querySelectorAll('.overlay.open').forEach(function(ov){
      var t=ov.id.replace('ov-','');
      closeModal(t);
    });
  }
});

/* ── Toast ── */
function showToast(msg,type){
  var t=document.getElementById('toast');
  var d=document.getElementById('toast-dot');
  var m=document.getElementById('toast-msg');
  d.style.background=(type==='green')?'#4ade80':'#f87171';
  m.textContent=msg;
  t.classList.add('show');
  setTimeout(function(){t.classList.remove('show');},3500);
}

/* ── Client-side search/filter (works when data is server-rendered) ── */
function filterTable(){
  var q=document.getElementById('searchInput').value.toLowerCase();
  var s=document.getElementById('statusFilter').value;
  var k=document.getElementById('kategoriFilter') ? document.getElementById('kategoriFilter').value : '';
  var rows=document.querySelectorAll('#tBody tr[data-nama]');
  rows.forEach(function(row){
    var nama=row.getAttribute('data-nama')||'';
    var lokasi=row.getAttribute('data-lokasi')||'';
    var status=row.getAttribute('data-status')||'';
    var kategori=row.getAttribute('data-kategori')||'';
    var matchQ=!q||(nama.includes(q)||lokasi.includes(q));
    var matchS=!s||(status===s);
    var matchK=!k||(kategori===k);
    row.style.display=(matchQ&&matchS&&matchK)?'':'none';
  });
}

/* ── Status badge helper ── */
function statusBadge(s){
  var map={
    'Tersedia':['b-green','Tersedia'],
    'Sedang Dipakai':['b-amber','Dipakai'],
    'Renovasi':['b-blue','Renovasi'],
    'Perlu Perbaikan':['b-rose','Perlu Perhatian']
  };
  var pair=map[s]||['b-slate',s];
  return '<span class="badge '+pair[0]+'">'+pair[1]+'</span>';
}

/* ── DETAIL AJAX ── */
function openDetailAjax(id) {
  console.log('Loading gedung ID:', id);
  fetch(`/adminsarpras/gedung/${id}`, {
    headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}
  })
  .then(r=>r.json())
  .then(data=>{
    console.log('Data:',data);
    document.getElementById('det-title').textContent=data.nama_gedung||'Data tidak ditemukan';
    document.getElementById('detailBody').innerHTML=`
      <div class="det-photo">${data.foto_url?`<img src="${data.foto_url}" alt="${data.nama_gedung}" style="width:100%;height:100%;object-fit:cover;">`:`<svg viewBox="0 0 24 24" fill="currentColor"><path d="M21 15v-6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2zm-9-1a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm1-9h-2v2h2V5z"/></svg>`}</div>
      <div class="det-grid">
        <div class="det-item"><label>LOKASI</label><strong>${data.lokasi||'-'}</strong></div>
        <div class="det-item"><label>KATEGORI</label><strong>${data.kategori||'-'}</strong></div>
        <div class="det-item"><label>KAPASITAS</label><strong>${data.kapasitas?parseInt(data.kapasitas).toLocaleString()+' orang':'-'}</strong></div>
        <div class="det-item"><label>TARIF SEWA</label><strong>${data.tarif_sewa?'Rp '+parseInt(data.tarif_sewa).toLocaleString():'-'}</strong></div>
        <div class="det-item"><label>LUAS</label><strong>${data.luas_bangunan||'-'} m²</strong></div>
        <div class="det-item"><label>STATUS</label><strong>${data.ketersediaan||'-'}</strong></div>
        <div class="det-item"><label>DITAMBAHKAN</label><strong>${data.created_at?new Date(data.created_at).toLocaleDateString('id-ID'):'-'}</strong></div>
      </div>${data.fasilitas?`<div><div class="section-lbl">FASILITAS</div><div class="det-fac">${data.fasilitas}</div></div>`:''}`;
    openModal('detail');
  })
  .catch(e=>{
    console.error('Error:',e);
    showToast('Gagal load detail: '+e.message,'red');
  });
}

/* ── EDIT via AJAX ── */
function openEditAjax(id){
  fetch('/adminsarpras/gedung/'+id, {
    headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}
  })
  .then(function(r){return r.json();})
  .then(function(data){
    document.getElementById('e-id').value=data.id;
    document.getElementById('e-nama').value=data.nama_gedung;
    if (document.getElementById('e-kategori')) {
      document.getElementById('e-kategori').value=data.kategori||'';
    }
    document.getElementById('e-lokasi').value=data.lokasi;
    document.getElementById('e-luas').value=data.luas_bangunan;
    document.getElementById('e-kap').value=data.kapasitas;
    document.getElementById('e-tarif').value=data.tarif_sewa;
    document.getElementById('e-status').value=data.ketersediaan;
    document.getElementById('e-fasilitas').value=data.fasilitas||'';
    document.getElementById('formEdit').action='/adminsarpras/gedung/'+data.id;
    openModal('edit');
  })
  .catch(function(){showToast('Gagal memuat data gedung.','red');});
}

function openHapus(id, nama) {
  document.getElementById('del-name').textContent = nama;
  document.getElementById('formHapus').action = '/adminsarpras/gedung/' + id;
  openModal('hapus');
}

/* ── Flash message from Laravel session ── */
(function(){
  @if(session('success'))
    showToast("{{ session('success') }}",'green');
  @endif
  @if(session('error'))
    showToast("{{ session('error') }}",'red');
  @endif
})();

/* ── AJAX form submit untuk Tambah (opsional, bisa pakai biasa) ── */
document.getElementById('formTambah').addEventListener('submit',function(e){
  /* Biarkan form submit biasa ke Laravel route.
     Uncomment kode di bawah jika ingin AJAX tanpa reload: */
  /*
  e.preventDefault();
  var formData=new FormData(this);
  fetch(this.action,{method:'POST',body:formData,headers:{'X-Requested-With':'XMLHttpRequest'}})
    .then(function(r){return r.json();})
    .then(function(data){
      if(data.success){closeModal('tambah');location.reload();}
      else{showToast(data.message||'Terjadi kesalahan.','red');}
    })
    .catch(function(){showToast('Gagal menyimpan data.','red');});
  */
});

document.getElementById('formEdit').addEventListener('submit',function(e){
  /* Biarkan form submit biasa. Uncomment untuk AJAX:
  e.preventDefault();
  var formData=new FormData(this);
  formData.append('_method','PUT');
  fetch(this.action,{method:'POST',body:formData,headers:{'X-Requested-With':'XMLHttpRequest'}})
    .then(function(r){return r.json();})
    .then(function(data){
      if(data.success){closeModal('edit');location.reload();}
      else{showToast(data.message||'Terjadi kesalahan.','red');}
    })
    .catch(function(){showToast('Gagal update data.','red');});
  */
});
</script>

</body>
</html>