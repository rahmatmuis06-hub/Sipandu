<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>SIPANDU - Data Aset Tetap</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  :root {
    --blue: #4F6FFF;
    --sidebar-w: 240px;
    --radius: 16px;
    --bg: #F4F6FB;
    --surface: #FFFFFF;
    --text: #1E293B;
    --muted: #94A3B8;
    --border: #E8EDF5;
    --success: #10B981;
    --danger: #EF4444;
    --warning: #F59E0B;
  }
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); display: flex; min-height: 100vh; }

  /* SIDEBAR */
  .sidebar {
    width: var(--sidebar-w);
    background: var(--surface);
    border-right: 1px solid var(--border);
    display: flex;
    flex-direction: column;
    position: fixed;
    top: 0; left: 0; bottom: 0;
    z-index: 100;
  }
  .sidebar-logo {
    display: flex; align-items: center; gap: 10px;
    padding: 20px 20px 16px;
    border-bottom: 1px solid var(--border);
  }
  .logo-icon {
    width: 38px; height: 38px; border-radius: 10px;
    background: linear-gradient(135deg, var(--blue), #7C3AED);
    display: flex; align-items: center; justify-content: center;
  }
  .logo-icon svg { width: 20px; height: 20px; fill: white; }
  .logo-text strong { font-size: 15px; font-weight: 800; color: var(--text); display: block; }
  .logo-text span { font-size: 10px; color: var(--muted); font-weight: 600; letter-spacing: 1px; }

  .sidebar-section { padding: 16px 16px 4px; }
  .sidebar-label { font-size: 10px; font-weight: 700; color: var(--muted); letter-spacing: 1.5px; text-transform: uppercase; padding: 0 4px 8px; }

  .role-badge {
    background: linear-gradient(135deg, #EEF2FF, #E0E7FF);
    border: 1px solid #C7D2FE;
    border-radius: 10px;
    padding: 8px 12px;
    font-size: 12px; font-weight: 700; color: var(--blue);
    margin: 0 16px 16px;
  }

  .nav { flex: 1; overflow-y: auto; padding: 4px 12px; }
  .nav-item {
    display: flex; align-items: center; gap: 10px;
    padding: 9px 12px;
    border-radius: 10px;
    font-size: 13.5px; font-weight: 500;
    color: #64748B;
    cursor: pointer; transition: all .15s;
    margin-bottom: 2px;
  }
  .nav-item:hover { background: var(--bg); color: var(--text); }
  .nav-item.active { background: linear-gradient(135deg, #EEF2FF, #E0E7FF); color: var(--blue); font-weight: 700; }
  .nav-item svg { width: 17px; height: 17px; flex-shrink: 0; }
  .nav-item .chevron { margin-left: auto; width: 14px; height: 14px; }

  .sidebar-footer { border-top: 1px solid var(--border); padding: 14px 16px; }
  .user-info { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
  .user-avatar {
    width: 34px; height: 34px; border-radius: 50%;
    background: linear-gradient(135deg, var(--blue), #7C3AED);
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 13px; font-weight: 700;
  }
  .user-detail strong { font-size: 13px; font-weight: 700; display: block; }
  .user-detail span { font-size: 11px; color: var(--muted); }
  .btn-logout {
    width: 100%; display: flex; align-items: center; justify-content: center; gap: 6px;
    padding: 8px; border-radius: 8px;
    border: 1px solid var(--border);
    background: transparent; color: #64748B;
    font-size: 13px; font-weight: 600; font-family: inherit;
    cursor: pointer; transition: all .15s;
  }
  .btn-logout:hover { background: #FEF2F2; color: #EF4444; border-color: #FECACA; }

  /* MAIN */
  .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }

  .topbar {
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    padding: 0 28px;
    height: 56px;
    display: flex; align-items: center; justify-content: space-between;
    position: sticky; top: 0; z-index: 50;
  }
  .topbar-title { font-size: 16px; font-weight: 700; }
  .topbar-right { display: flex; align-items: center; gap: 16px; }
  .notif-btn {
    width: 36px; height: 36px; border-radius: 50%; border: 1px solid var(--border);
    background: var(--surface); display: flex; align-items: center; justify-content: center;
    cursor: pointer; position: relative;
  }
  .notif-dot { width: 8px; height: 8px; background: #EF4444; border-radius: 50%; position: absolute; top: 6px; right: 6px; border: 2px solid white; }
  .date-text { font-size: 13px; color: #64748B; font-weight: 500; }
  .btn-keluar {
    display: flex; align-items: center; gap: 6px;
    padding: 7px 14px; border-radius: 8px;
    border: 1px solid var(--border);
    background: var(--surface); color: #64748B;
    font-size: 13px; font-weight: 600; font-family: inherit; cursor: pointer; transition: all .15s;
  }
  .btn-keluar:hover { background: #FEF2F2; color: #EF4444; }

  .content { padding: 28px; flex: 1; }

  /* ALERT */
  .alert {
    padding: 14px 18px; border-radius: 10px; margin-bottom: 20px;
    display: flex; align-items: center; gap: 10px; font-weight: 600;
  }
  .alert-success { background: #ECFDF5; color: var(--success); border: 1px solid #BBF7D0; }
  .alert-danger { background: #FEF2F2; color: var(--danger); border: 1px solid #FECACA; }

  /* PAGE HEADER */
  .page-top {
    display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 20px;
  }
  .page-top h1 { font-size: 22px; font-weight: 800; color: var(--blue); margin-bottom: 4px; }
  .page-top p { font-size: 13px; color: var(--muted); }
  .btn-tambah {
    display: flex; align-items: center; gap: 7px;
    padding: 10px 18px; border-radius: 10px;
    background: linear-gradient(135deg, var(--blue), #7C3AED);
    color: white; font-size: 13.5px; font-weight: 700;
    font-family: inherit; border: none; cursor: pointer;
    box-shadow: 0 4px 14px rgba(79,111,255,.35);
    transition: all .2s;
  }
  .btn-tambah:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(79,111,255,.45); }

  /* TABLE CARD */
  .table-card {
    background: var(--surface);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    overflow: hidden;
  }

  .table-toolbar {
    display: flex; align-items: center; gap: 12px;
    padding: 16px 20px;
    border-bottom: 1px solid var(--border);
  }
  .search-wrap {
    flex: 1; display: flex; align-items: center; gap: 8px;
    border: 1.5px solid var(--border); border-radius: 10px;
    padding: 8px 14px; background: var(--bg);
    transition: border-color .15s;
  }
  .search-wrap:focus-within { border-color: var(--blue); }
  .search-wrap input {
    border: none; background: none; outline: none;
    font-family: inherit; font-size: 13.5px; color: var(--text); width: 100%;
  }
  .search-wrap input::placeholder { color: var(--muted); }

  .filter-select {
    padding: 8px 14px; border-radius: 10px;
    border: 1.5px solid var(--border); background: var(--bg);
    font-family: inherit; font-size: 13px; color: var(--text);
    cursor: pointer; outline: none;
  }
  .filter-select:focus { border-color: var(--blue); }

  table { width: 100%; border-collapse: collapse; }
  thead tr { background: #F8FAFF; }
  th {
    padding: 12px 20px;
    text-align: left;
    font-size: 11px; font-weight: 700;
    color: var(--blue); letter-spacing: .8px; text-transform: uppercase;
    border-bottom: 1px solid var(--border);
  }
  td {
    padding: 14px 20px;
    font-size: 13.5px; color: var(--text);
    border-bottom: 1px solid var(--border);
  }
  
  tr:last-child td { border-bottom: none; }
  tbody tr { transition: background .15s; }
  tbody tr:hover { background: #F8FAFF; }

  /* ACTION BUTTONS */
  td:last-child { white-space: nowrap; }
  .action-btn {
    width: 36px; height: 36px; border-radius: 8px;
    border: 1px solid var(--border); background: var(--surface);
    display: inline-flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all .15s; margin-left: 4px;
  }
  .action-btn:hover { background: #EEF2FF; border-color: var(--blue); transform: translateY(-1px); }
  .action-btn.danger:hover { background: #FEF2F2; border-color: var(--danger); }
  .action-btn svg { width: 16px; height: 16px; }

  /* MODAL */
  .modal-overlay {
    position: fixed; top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,.5); display: flex; align-items: center; justify-content: center;
    z-index: 1000; opacity: 0; visibility: hidden; transition: all .2s;
  }
  .modal-overlay.show { opacity: 1; visibility: visible; }
  .modal {
    background: var(--surface); border-radius: var(--radius); padding: 28px;
    max-width: 500px; width: 90%; max-height: 90vh; overflow-y: auto;
    transform: scale(.9) translateY(-20px); transition: all .2s;
  }
  .modal-overlay.show .modal { transform: scale(1) translateY(0); }
  .form-group { margin-bottom: 20px; }
  .form-label { display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px; }
  .form-input, .form-select {
    width: 100%; padding: 12px 16px; border-radius: 10px;
    border: 1.5px solid var(--border); background: var(--bg);
    font-family: inherit; font-size: 14px; transition: all .15s;
  }
  .form-input:focus, .form-select:focus {
    outline: none; border-color: var(--blue); box-shadow: 0 0 0 3px rgba(79,111,255,.1);
  }
  .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
  .btn {
    padding: 12px 24px; border-radius: 10px; font-weight: 700; font-size: 14px;
    border: none; cursor: pointer; transition: all .2s; font-family: inherit;
  }
  .btn-primary {
    background: linear-gradient(135deg, var(--blue), #7C3AED);
    color: white; box-shadow: 0 4px 14px rgba(79,111,255,.35);
  }
  .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(79,111,255,.45); }
  .btn-cancel {
    background: var(--bg); color: var(--text); border: 1.5px solid var(--border);
  }
  .btn-danger {
    background: var(--danger); color: white;
  }
  .btn-danger:hover { background: #DC2626; }

  /* PAGINATION */
  .table-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 20px;
    border-top: 1px solid var(--border);
    font-size: 13px; color: var(--muted);
  }
  .pagination { display: flex; align-items: center; gap: 6px; }

  @media (max-width: 768px) {
    .main { margin-left: 0; }
    .sidebar { transform: translateX(-100%); }
    .table-toolbar { flex-direction: column; align-items: stretch; gap: 12px; }
    .form-row { grid-template-columns: 1fr; }
    .page-top { flex-direction: column; gap: 16px; align-items: stretch; }
  }
</style>
</head>
<body>

{{-- SIDEBAR --}}
@include('partials.sidebar')

<main class="main">
  <div class="topbar">
    <span class="topbar-title">Transaksi Masuk Aset Tetap</span>
    <div class="topbar-right">
      {{-- <div class="notif-btn">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="#64748B"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
        <span class="notif-dot"></span>
      </div> --}}
      <span class="date-text">{{ now()->translatedFormat('l, d F Y') }}</span>
<form method="POST" action="{{ route('logout', [], false) }}" style="display:inline; margin:0;">
@csrf
<button type="submit" class="btn-keluar">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5-5-5zm-5 11H5V5h7V3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h7v-2z"/></svg>
        Keluar
      </button>
</form>
    </div>
  </div>

  <div class="content">
    {{-- ALERT --}}
    @if(session('success'))
    <div class="alert alert-success">
      <i class="fas fa-check-circle"></i>
      {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger">
      <i class="fas fa-exclamation-triangle"></i>
      <ul style="padding-left: 1rem; margin: 0;">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif

    <div class="page-top">
      <div>
        <h1>Transaksi Masuk Aset Tetap</h1>
        <p>Menampilkan {{ $transaksi->firstItem() ?? 0 }}–{{ $transaksi->lastItem() ?? 0 }} dari {{ $transaksi->total() }} data</p>
      </div>
      <button onclick="openModal('createModal')" class="btn-tambah">
        <i class="fas fa-plus"></i>
        Tambah Transaksi & Aset Baru
      </button>
    </div>

    <div class="table-card">
      <div class="table-toolbar">
        <form method="GET" class="search-wrap" style="max-width: 400px;">
          <i class="fas fa-search" style="color: var(--muted);"></i>
          <input type="text" name="search" placeholder="Cari kode barang, NUP, nama..." 
                 value="{{ request('search') }}">
        </form>
        <div class="filter-group">
          <select name="kondisi" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Kondisi</option>
            @foreach($kondisiOptions as $kondisi)
            <option value="{{ $kondisi }}" {{ request('kondisi') == $kondisi ? 'selected' : '' }}>
              {{ ucwords(str_replace('_', ' ', $kondisi)) }}
            </option>
            @endforeach
          </select>
        </div>
      </div>

      <div style="overflow-x: auto;">
        <table>
          <thead>
            <tr>
              <th>No</th>
              <th>Tanggal Input</th>
              <th>Kode Barang</th>
              <th>NUP</th>
              <th>Nama Barang</th>
              <th>Merek</th>
              <th>Kategori</th>
              <th>Tanggal Perolehan</th>
              <th>Nilai Perolehan</th>
              <th>Kondisi</th>
              <th>Lokasi</th>
              <th>Jumlah</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($transaksi as $index => $item)
            <tr>
              <td><strong>{{ $transaksi->firstItem() + $index }}</strong></td>
              <td>{{ $item?->tanggal_input_formatted }}</td>
              <td>{{ $item->kode_barang}}</td>
              <td>{{ $item->nup}}</td>
              <td>{{ $item->nama_barang}}</td>
              <td>{{ $item->merek ?? '-' }}</td>
              <td>{{ $item->kategori}}</td>
              <td>{{ $item->tanggal_perolehan_formatted}}</td>
              <td class="nilai">{{ $item->nilai_format}}</td>
              <td>
                <span style="padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; background: rgba({{ $item->kondisi_badge['color'] == 'success' ? '16,185,129' : ($item->kondisi_badge['color'] == 'warning' ? '245,158,11' : '239,68,68') }}, 0.1); color: var(--{{ $item->kondisi_badge['color'] }});">
                  <i class="fas {{ $item->kondisi_badge['icon'] }} me-1"></i>
                  {{ $item->kondisi_badge['text'] }}
                </span>
              </td>
              <td>{{ $item->lokasi }}</td>
              <td><strong>{{ $item->jumlah }}</strong></td>
              <td>
                <div class="action-buttons">
                    <button onclick="openDetail({{ $item->id }})" class="action-btn" title="Detail">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                      </svg>
                    </button>
                    <button onclick="openEdit({{ $item->id }})" class="action-btn" title="Edit">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                      </svg>
                    </button>
                    <button onclick="confirmDelete({{ $item->id }})" class="action-btn danger" title="Hapus">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                      </svg>
                    </button>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="13" style="text-align: center; padding: 40px;">
                <div style="color: var(--muted); margin-bottom: 12px;">Belum ada data transaksi masuk aset tetap</div>
                <button onclick="openModal('createModal')" class="btn-tambah" style="margin: 0 auto;">
                  Tambah transaksi pertama
                </button>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="table-footer">
        <span>{{ $transaksi->total() }} data tersedia</span>
        <div class="pagination">
          {{ $transaksi->appends(request()->query())->links() }}
        </div>
      </div>
    </div>
  </div>
</main>

{{-- MODAL CREATE --}}
<div id="createModal" class="modal-overlay">
  <div class="modal" style="max-width:620px; padding:0; display:flex; flex-direction:column; max-height:92vh; overflow:hidden; border-radius:20px; box-shadow:0 25px 60px rgba(0,0,0,.18),0 8px 24px rgba(79,111,255,.12);">
    
    {{-- HEADER --}}
    <div style="padding:22px 28px 18px; border-bottom:1px solid var(--border); background:linear-gradient(135deg,#F8FAFF,#EEF2FF); flex-shrink:0;">
      <div style="display:flex; align-items:center; justify-content:space-between;">
        <div style="display:flex; align-items:center; gap:12px;">
          <div style="width:40px; height:40px; border-radius:12px; background:linear-gradient(135deg,var(--blue),#7C3AED); display:flex; align-items:center; justify-content:center; box-shadow:0 4px 12px rgba(79,111,255,.35); flex-shrink:0;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="white"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
          </div>
          <div>
            <div style="font-size:17px; font-weight:800; color:var(--text);">Tambah Transaksi Masuk</div>
            <div style="font-size:12px; color:var(--muted); margin-top:2px; font-weight:500;">Pencatatan akan otomatis ditambahkan ke Master Aset Tetap</div>
          </div>
        </div>
        <button onclick="closeModal('createModal')" style="width:32px; height:32px; border-radius:8px; border:1.5px solid var(--border); background:var(--surface); display:flex; align-items:center; justify-content:center; cursor:pointer; color:var(--muted); transition:all .15s;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
        </button>
      </div>
    </div>

    {{-- FORM BODY --}}
    <form id="createForm" method="POST" action="{{ route('adminasettetap.transaksi-masuk.store') }}" style="display:flex; flex-direction:column; flex:1; overflow:hidden;">
      @csrf
      <div style="padding:20px 28px; overflow-y:auto; flex:1;">
        
        <div style="font-size:10.5px; font-weight:700; color:var(--blue); letter-spacing:1.2px; text-transform:uppercase; padding-bottom:10px; border-bottom:1.5px solid var(--border); margin-bottom:16px;">
          <span style="background:linear-gradient(135deg,#EEF2FF,#E0E7FF); border-radius:4px; padding:2px 8px;">Identifikasi Transaksi</span>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Tanggal Input <span style="color:var(--danger);">*</span></label>
            <input type="date" name="tanggal_input" class="form-input" value="{{ now()->format('Y-m-d') }}" required>
          </div>
          <div class="form-group">
            <label class="form-label">Kode Barang <span style="color:var(--danger);">*</span></label>
            <input type="text" name="kode_barang" class="form-input" placeholder="Contoh: 3.06.01.01.001" required>
          </div>
        </div>

        <div style="font-size:10.5px; font-weight:700; color:var(--blue); letter-spacing:1.2px; text-transform:uppercase; padding-bottom:10px; border-bottom:1.5px solid var(--border); margin-bottom:16px; margin-top:4px;">
          <span style="background:linear-gradient(135deg,#EEF2FF,#E0E7FF); border-radius:4px; padding:2px 8px;">Data Barang</span>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">NUP <span style="color:var(--danger);">*</span></label>
            <input type="text" name="nup" class="form-input" placeholder="Nomor Urut Pendaftaran" required>
          </div>
          <div class="form-group">
            <label class="form-label">Jumlah <span style="color:var(--danger);">*</span></label>
            <input type="number" name="jumlah" class="form-input" min="1" placeholder="1" required>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Nama Barang <span style="color:var(--danger);">*</span></label>
            <input type="text" name="nama_barang" class="form-input" placeholder="Nama lengkap aset" required>
          </div>
          <div class="form-group">
            <label class="form-label">Merek <span style="color:var(--muted); font-weight:500; font-size:11px;">(opsional)</span></label>
            <input type="text" name="merek" class="form-input" placeholder="Merek / brand">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Kategori <span style="color:var(--danger);">*</span></label>
            <input type="text" name="kategori" class="form-input" placeholder="Kategori Aset" required>
          </div>
          <div class="form-group">
            <label class="form-label">Lokasi <span style="color:var(--danger);">*</span></label>
            <input type="text" name="lokasi" class="form-input" placeholder="Ruangan penempatan" required>
          </div>
        </div>
        <div class="form-group">
            <label class="form-label">Kondisi <span style="color:var(--danger);">*</span></label>
            <select name="kondisi" class="form-select" required>
              <option value="">Pilih Kondisi</option>
              @foreach($kondisiOptions as $kondisi)
              <option value="{{ $kondisi }}">{{ ucwords(str_replace('_', ' ', $kondisi)) }}</option>
              @endforeach
            </select>
        </div>

        <div style="font-size:10.5px; font-weight:700; color:var(--blue); letter-spacing:1.2px; text-transform:uppercase; padding-bottom:10px; border-bottom:1.5px solid var(--border); margin-bottom:16px; margin-top:4px;">
          <span style="background:linear-gradient(135deg,#EEF2FF,#E0E7FF); border-radius:4px; padding:2px 8px;">Data Perolehan</span>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Tanggal Perolehan</label>
            <input type="date" name="tanggal_perolehan" class="form-input">
          </div>
          <div class="form-group">
            <label class="form-label">Nilai Perolehan</label>
            <div style="display:flex; align-items:center; border:1.5px solid var(--border); border-radius:10px; overflow:hidden; background:var(--bg);">
              <span style="padding:10px 12px; font-size:12.5px; font-weight:700; color:var(--muted); border-right:1.5px solid var(--border); background:#F0F4FF;">Rp</span>
              <input type="number" name="nilai_perolehan" step="0.01" min="0" placeholder="0" style="flex:1; border:none; background:transparent; padding:10px 14px; font-family:inherit; outline:none;">
            </div>
          </div>
        </div>
      </div>

      {{-- FOOTER --}}
      <div style="padding:16px 28px; border-top:1px solid var(--border); background:#FAFBFF; display:flex; align-items:center; justify-content:space-between; flex-shrink:0;">
        <div style="font-size:12px; color:var(--muted);">Kolom bertanda <strong style="color:var(--danger);">*</strong> wajib diisi</div>
        <div style="display:flex; gap:10px;">
          <button type="button" class="btn btn-cancel" onclick="closeModal('createModal')">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Data Baru</button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- MODAL DETAIL --}}
<div id="detailModal" class="modal-overlay">
  <div class="modal" style="max-width: 550px;">
    <div style="padding: 28px;">
      <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 12px;">
          <div style="width: 44px; height: 44px; border-radius: 12px; background: linear-gradient(135deg, #3B82F6, #8B5CF6); display: flex; align-items: center; justify-content: center;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="white">
              <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
            </svg>
          </div>
          <div>
            <h3 style="font-size: 20px; font-weight: 800; color: var(--text); margin: 0;">Detail Transaksi</h3>
            <p style="font-size: 13px; color: var(--muted); margin: 4px 0 0;">Lihat detail lengkap transaksi</p>
          </div>
        </div>
        <button onclick="closeModal('detailModal')" style="width: 36px; height: 36px; border-radius: 10px; border: 1px solid var(--border); background: var(--surface); display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--muted);">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
        </button>
      </div>
      <div id="detailContent" style="padding: 8px 0;"></div>
      <div style="display: flex; justify-content: flex-end; margin-top: 24px;">
        <button onclick="closeModal('detailModal')" class="btn btn-cancel">Tutup</button>
      </div>
    </div>
  </div>
</div>

{{-- MODAL EDIT --}}
<div id="editModal" class="modal-overlay">
  <div class="modal" style="max-width: 620px; padding: 0; display: flex; flex-direction: column; max-height: 92vh; border-radius: 20px;">
    <div style="padding: 24px 28px 20px; border-bottom: 1px solid var(--border); background: linear-gradient(135deg, #F8FAFF, #EEF2FF);">
      <div style="display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 12px;">
          <div style="width: 40px; height: 40px; border-radius: 12px; background: linear-gradient(135deg, #F59E0B, #FBBF24); display: flex; align-items: center; justify-content: center;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="white"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
          </div>
          <div>
            <h3 style="font-size: 18px; font-weight: 800; color: var(--text); margin: 0;">Edit Transaksi Masuk</h3>
            <p style="font-size: 12px; color: var(--warning); margin: 2px 0 0; font-weight: 600;">Data akan diperbarui pada Master Aset Tetap juga!</p>
          </div>
        </div>
        <button onclick="closeModal('editModal')" style="width: 36px; height: 36px; border-radius: 10px; border: 1px solid var(--border); background: var(--surface); display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--muted);">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
        </button>
      </div>
    </div>

    <form id="editForm" method="POST" style="display: flex; flex-direction: column; flex: 1; overflow: hidden;">
      @csrf @method('PUT')
      <div style="padding: 24px 28px; overflow-y: auto; flex: 1;">
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Tanggal Input <span style="color: var(--danger);">*</span></label>
            <input type="date" name="tanggal_input" id="editTanggalInput" class="form-input" required>
          </div>
          <div class="form-group">
            <label class="form-label">Kode Barang <span style="color: var(--danger);">*</span></label>
            <input type="text" name="kode_barang" id="editKodeBarang" class="form-input" required>
          </div>
        </div>
        
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">NUP <span style="color: var(--danger);">*</span></label>
            <input type="text" name="nup" id="editNup" class="form-input" required>
          </div>
          <div class="form-group">
            <label class="form-label">Jumlah <span style="color: var(--danger);">*</span></label>
            <input type="number" name="jumlah" id="editJumlah" class="form-input" min="1" required>
          </div>
        </div>
        
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Nama Barang <span style="color: var(--danger);">*</span></label>
            <input type="text" name="nama_barang" id="editNamaBarang" class="form-input" required>
          </div>
          <div class="form-group">
            <label class="form-label">Merek</label>
            <input type="text" name="merek" id="editMerek" class="form-input">
          </div>
        </div>
        
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Kategori <span style="color: var(--danger);">*</span></label>
            <input type="text" name="kategori" id="editKategori" class="form-input" required>
          </div>
          <div class="form-group">
            <label class="form-label">Kondisi <span style="color: var(--danger);">*</span></label>
            <select name="kondisi" id="editKondisi" class="form-select" required>
              <option value="">Pilih Kondisi</option>
              @foreach($kondisiOptions as $kondisi)
              <option value="{{ $kondisi }}">{{ ucwords(str_replace('_', ' ', $kondisi)) }}</option>
              @endforeach
            </select>
          </div>
        </div>
        
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Lokasi <span style="color: var(--danger);">*</span></label>
            <input type="text" name="lokasi" id="editLokasi" class="form-input" required>
          </div>
          <div class="form-group">
            <label class="form-label">Tanggal Perolehan</label>
            <input type="date" name="tanggal_perolehan" id="editTanggalPerolehan" class="form-input">
          </div>
        </div>
        
        <div class="form-group">
          <label class="form-label">Nilai Perolehan</label>
          <div style="position: relative;">
            <span style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); font-size: 14px; font-weight: 600; color: var(--muted);">Rp</span>
            <input type="number" name="nilai_perolehan" id="editNilaiPerolehan" class="form-input" step="0.01" min="0" style="padding-left: 48px;">
          </div>
        </div>
      </div>

      <div style="padding: 20px 28px; border-top: 1px solid var(--border); background: #FAFBFF; display: flex; gap: 12px; justify-content: flex-end;">
        <button type="button" class="btn btn-cancel" onclick="closeModal('editModal')">Batal</button>
        <button type="submit" class="btn btn-primary">Update Data (Sinkron Master)</button>
      </div>
    </form>
  </div>
</div>

{{-- MODAL DELETE --}}
<div id="deleteModal" class="modal-overlay">
  <div class="modal" style="max-width: 450px;">
    <div style="padding: 32px 28px 24px;">
      <div style="display: flex; align-items: center; justify-content: center; gap: 16px; margin-bottom: 20px;">
        <div style="width: 56px; height: 56px; border-radius: 16px; background: linear-gradient(135deg, #FEF2F2, #FECACA); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="#EF4444">
            <path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/>
          </svg>
        </div>
        <div>
          <h3 style="font-size: 20px; font-weight: 800; color: var(--danger); margin: 0 0 8px;">Batalkan Transaksi?</h3>
          <p style="font-size: 13.5px; color: var(--text); margin: 0; line-height: 1.5;">
            <b>PERHATIAN:</b> Membatalkan transaksi ini juga akan <b>menghapus barang tersebut dari Master Data Aset Tetap</b> secara permanen!
          </p>
        </div>
      </div>
      <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 28px;">
        <button onclick="closeModal('deleteModal')" class="btn btn-cancel">Kembali</button>
        <form id="deleteForm" method="POST" style="display: contents;">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-danger" style="padding: 12px 24px;">Ya, Hapus Semua</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
// Data Transaksi yang Di-Inject langsung dari Laravel via JSON 
const dataTransaksi = @json($transaksi->items());

function openModal(modalId) {
  document.getElementById(modalId).classList.add('show');
  document.body.style.overflow = 'hidden';
}

function closeModal(modalId = null) {
  if (!modalId) {
    document.querySelectorAll('.modal-overlay.show').forEach(modal => {
      modal.classList.remove('show');
    });
  } else {
    document.getElementById(modalId).classList.remove('show');
  }
  document.body.style.overflow = 'auto';
}

function openDetail(id) {
  const item = dataTransaksi.find(x => x.id === id);
  if (item) {
    document.getElementById('detailContent').innerHTML = `
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
        <div>
          <div style="font-size: 13px; color: var(--muted); font-weight: 600; margin-bottom: 12px;">Identifikasi</div>
          <div style="display: flex; flex-direction: column; gap: 16px;">
            <div><strong>Kode Barang:</strong> <span style="color: var(--blue); font-weight: 700;">${item.kode_barang}</span></div>
            <div><strong>NUP:</strong> ${item.nup}</div>
            <div><strong>Tanggal Input:</strong> ${item.tanggal_input_formatted || '-'}</div>
          </div>
        </div>
        <div>
          <div style="font-size: 13px; color: var(--muted); font-weight: 600; margin-bottom: 12px;">Data Barang</div>
          <div style="display: flex; flex-direction: column; gap: 16px;">
            <div><strong>Nama Barang:</strong> ${item.nama_barang}</div>
            <div><strong>Merek:</strong> ${item.merek || '-'}</div>
            <div><strong>Kategori:</strong> ${item.kategori}</div>
            <div><strong>Jumlah:</strong> <span style="color: var(--blue); font-weight: 700;">${item.jumlah}</span></div>
          </div>
        </div>
      </div>
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
        <div>
          <div style="font-size: 13px; color: var(--muted); font-weight: 600; margin-bottom: 12px;">Lokasi & Kondisi</div>
          <div style="display: flex; flex-direction: column; gap: 16px;">
            <div><strong>Lokasi:</strong> ${item.lokasi}</div>
            <div><strong>Kondisi:</strong> 
              <span style="margin-left: 8px; font-weight: 700; font-size: 12px; color: var(--${item.kondisi_badge.color}); text-transform: uppercase;">
                ${item.kondisi_badge.text}
              </span>
            </div>
          </div>
        </div>
        <div>
          <div style="font-size: 13px; color: var(--muted); font-weight: 600; margin-bottom: 12px;">Perolehan</div>
          <div style="display: flex; flex-direction: column; gap: 16px;">
            <div><strong>Tanggal Perolehan:</strong> ${item.tanggal_perolehan_formatted || '-'}</div>
            <div><strong>Nilai Perolehan:</strong> <span style="color: var(--success); font-weight: 700;">${item.nilai_format || '-'}</span></div>
          </div>
        </div>
      </div>
    `;
    openModal('detailModal');
  }
}

function openEdit(id) {
  const item = dataTransaksi.find(x => x.id === id);
  if (item) {
    // Fungsi ekstraksi YYYY-MM-DD aman dari data string
    const extractDate = (dateString) => dateString ? dateString.substring(0, 10) : '';

    document.getElementById('editTanggalInput').value = extractDate(item.tanggal_input);
    document.getElementById('editKodeBarang').value = item.kode_barang;
    document.getElementById('editNup').value = item.nup;
    document.getElementById('editNamaBarang').value = item.nama_barang;
    document.getElementById('editMerek').value = item.merek || '';
    document.getElementById('editKategori').value = item.kategori;
    document.getElementById('editKondisi').value = item.kondisi;
    document.getElementById('editJumlah').value = item.jumlah;
    document.getElementById('editLokasi').value = item.lokasi;
    document.getElementById('editTanggalPerolehan').value = extractDate(item.tanggal_perolehan);
    document.getElementById('editNilaiPerolehan').value = item.nilai_perolehan || '';

    document.getElementById('editForm').action = `/adminasettetap/transaksi-masuk/${id}`;
    openModal('editModal');
  }
}

function confirmDelete(id) {
  document.getElementById('deleteForm').action = `/adminasettetap/transaksi-masuk/${id}`;
  openModal('deleteModal');
}

document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.modal-overlay').forEach(modal => {
    modal.addEventListener('click', function(e) {
      if (e.target === this) closeModal();
    });
  });

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
  });
});
</script>

</body>
</html>
