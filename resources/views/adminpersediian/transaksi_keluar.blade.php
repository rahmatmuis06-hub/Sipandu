<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIPANDU - Transaksi Keluar</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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

  /* MAIN */
  .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }

  /* TOPBAR */
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
  .alert-danger  { background: #FEF2F2; color: var(--danger);  border: 1px solid #FECACA; }

  /* PAGE HEADER */
  .page-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 20px; }
  .page-top h1 { font-size: 22px; font-weight: 800; color: var(--blue); margin-bottom: 4px; }
  .page-top p  { font-size: 13px; color: var(--muted); }

  .btn-tambah {
    display: flex; align-items: center; gap: 7px;
    padding: 10px 18px; border-radius: 10px;
    background: linear-gradient(135deg, var(--blue), #7C3AED);
    color: white; font-size: 13.5px; font-weight: 700;
    font-family: inherit; border: none; cursor: pointer;
    box-shadow: 0 4px 14px rgba(79,111,255,.35); transition: all .2s;
  }
  .btn-tambah:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(79,111,255,.45); }

  /* TABLE CARD */
  .table-card { background: var(--surface); border-radius: var(--radius); border: 1px solid var(--border); overflow: hidden; }

  .table-toolbar {
    display: flex; align-items: center; gap: 12px;
    padding: 16px 20px; border-bottom: 1px solid var(--border); flex-wrap: wrap;
  }
  .search-wrap {
    flex: 1; display: flex; align-items: center; gap: 8px;
    border: 1.5px solid var(--border); border-radius: 10px;
    padding: 8px 14px; background: var(--bg); transition: border-color .15s; min-width: 200px;
  }
  .search-wrap:focus-within { border-color: var(--blue); }
  .search-wrap input { border: none; background: none; outline: none; font-family: inherit; font-size: 13.5px; color: var(--text); width: 100%; }
  .search-wrap input::placeholder { color: var(--muted); }

  .filter-select {
    padding: 8px 14px; border-radius: 10px;
    border: 1.5px solid var(--border); background: var(--bg);
    font-family: inherit; font-size: 13px; color: var(--text); cursor: pointer; outline: none;
  }
  .filter-select:focus { border-color: var(--blue); }

  table { width: 100%; border-collapse: collapse; }
  thead tr { background: #F8FAFF; }
  th {
    padding: 12px 20px; text-align: left;
    font-size: 11px; font-weight: 700; color: var(--blue);
    letter-spacing: .8px; text-transform: uppercase; border-bottom: 1px solid var(--border);
  }
  td { padding: 14px 20px; font-size: 13.5px; color: var(--text); border-bottom: 1px solid var(--border); }
  tr:last-child td { border-bottom: none; }
  tbody tr { transition: background .15s; }
  tbody tr:hover { background: #F8FAFF; }

  .font-mono { font-family: 'Monaco', 'Menlo', monospace; }
  .text-red-600 { color: var(--danger) !important; }
  .text-lg { font-size: 16px; }

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

  .table-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 20px; border-top: 1px solid var(--border);
    font-size: 13px; color: var(--muted);
  }

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

  .btn {
    padding: 12px 24px; border-radius: 10px; font-weight: 700; font-size: 14px;
    border: none; cursor: pointer; transition: all .2s; font-family: inherit;
    display: inline-flex; align-items: center; gap: 8px;
  }
  .btn-cancel { background: var(--bg); color: var(--text); border: 1.5px solid var(--border); }

  /* FORM */
  .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
  .form-group { display: flex; flex-direction: column; margin-bottom: 0; }
  .form-label { font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 8px; }
  .form-input, .form-textarea, .form-select {
    width: 100%; padding: 14px 16px; border-radius: 12px;
    border: 2px solid var(--border); background: var(--bg);
    font-family: inherit; font-size: 14px; transition: all .2s; box-sizing: border-box; color: var(--text);
  }
  .form-input:focus, .form-textarea:focus, .form-select:focus {
    outline: none; border-color: var(--blue);
    box-shadow: 0 0 0 4px rgba(79,111,255,0.1); background: white;
  }
  .form-textarea { resize: vertical; min-height: 90px; }
  .error-text { font-size: 12px; color: var(--danger); margin-top: 6px; font-weight: 500; }

  .section-label { font-size: 10.5px; font-weight: 700; color: var(--blue); letter-spacing: 1.2px; text-transform: uppercase; padding-bottom: 10px; border-bottom: 1.5px solid var(--border); margin-bottom: 24px; }
  .section-label-inner { background: linear-gradient(135deg,#EEF2FF,#E0E7FF); border-radius: 4px; padding: 4px 10px; }
  .section-label-inner.green { background: linear-gradient(135deg,#ECFDF5,#D1FAE5); color: var(--success); }
  .section-label-inner.red   { background: linear-gradient(135deg,#FEF2F2,#FEE2E2); color: var(--danger); }

  /* Custom Styling Select2 Untuk Persediaan */
  .select2-container .select2-selection--single {
      height: 46px !important; 
      border: 2px solid var(--border) !important;
      border-radius: 12px !important;
      background: var(--bg) !important;
      display: flex;
      align-items: center;
  }
  .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: 44px !important;
      right: 12px !important;
  }
  .select2-container--default .select2-selection--single .select2-selection__rendered {
      color: var(--text) !important;
      font-size: 14px !important;
      font-family: inherit;
      padding-left: 16px !important;
  }
  .select2-container--open {
      z-index: 9999 !important;
  }

  @media (max-width: 768px) {
    .main { margin-left: 0; }
    .table-toolbar { flex-direction: column; align-items: stretch; }
    .form-row { grid-template-columns: 1fr; }
    .page-top { flex-direction: column; gap: 16px; align-items: stretch; }
    .table-footer { flex-direction: column; gap: 12px; text-align: center; }
  }
</style>
</head>
<body>

{{-- SIDEBAR --}}
@include('partials.sidebar')

<main class="main">

  {{-- TOPBAR --}}
  <div class="topbar">
    <span class="topbar-title">Transaksi Keluar</span>
    <div class="topbar-right">
      {{-- <div class="notif-btn">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="#64748B"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
        <span class="notif-dot"></span>
      </div> --}}
      <span class="date-text">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}</span>
      <button class="btn-keluar">Keluar</button>
    </div>
  </div>

  <div class="content">

    {{-- ALERT --}}
    @if(session('success'))
    <div class="alert alert-success">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
      {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
      @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
    </div>
    @endif

    {{-- PAGE HEADER --}}
    <div class="page-top">
      <div>
        <h1>Transaksi Keluar</h1>
        <p>{{ $transaksi->total() }} data ditemukan</p>
      </div>
      <button onclick="openModal('createModal')" class="btn-tambah">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
        Tambah Baru
      </button>
    </div>

    {{-- TABLE --}}
    <div class="table-card">
      <div class="table-toolbar">
        <form method="GET" action="{{ route('adminpersediaan.transaksi-keluar') }}" class="search-wrap">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="#94A3B8">
            <path d="M15.5 14h-.79l-.28-.27A6.47 6.47 0 0016 9.5 6.5 6.5 0 109.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
          </svg>
          <input type="text" name="search" placeholder="Cari kode barang, nama barang..." value="{{ request('search') }}">
        </form>

        <form method="GET" action="{{ route('adminpersediaan.transaksi-keluar') }}" style="display:flex; gap:12px;">
          <input type="hidden" name="search" value="{{ request('search') }}">
          <input type="date" name="tanggal_input" class="filter-select" value="{{ request('tanggal_input') }}">
          <select name="kode_kategori" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Kode Kategori</option>
            @foreach(\App\Models\TransaksiKeluarPersediaan::distinct()->orderBy('kode_kategori')->pluck('kode_kategori')->toArray() as $kodeKategori)
              <option value="{{ $kodeKategori }}" {{ request('kode_kategori') == $kodeKategori ? 'selected' : '' }}>{{ $kodeKategori }}</option>
            @endforeach
          </select>
        </form>
      </div>

      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Tanggal Input</th>
            <th>Kode Kategori</th>
            <th>Kategori</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Jumlah Keluar</th>
            <th>Satuan</th>
            <th>Harga</th>
            <th>Total</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($transaksi as $index => $item)
          <tr data-id="{{ $item->id }}"
              data-tanggal_input="{{ $item->tanggal_input ? $item->tanggal_input->format('Y-m-d') : '' }}"
              data-kode_kategori="{{ $item->kode_kategori }}"
              data-kategori="{{ $item->kategori }}"
              data-kode_barang="{{ $item->kode_barang }}"
              data-nama_barang="{{ $item->nama_barang }}"
              data-jumlah_keluar="{{ $item->jumlah_keluar }}"
              data-satuan="{{ $item->satuan }}"
              data-harga="{{ $item->harga }}"
              data-total="{{ $item->total }}">
              <td><strong>{{ $transaksi->firstItem() + $loop->index }}</strong></td>
              <td>{{ $item->tanggal_input_format ?? $item->tanggal_input }}</td>
              <td>{{ $item->kode_kategori }}</td>
              <td>{{ $item->kategori }}</td>
              <td><strong>{{ $item->kode_barang }}</strong></td>
              <td>{{ Str::limit($item->nama_barang, 25) }}</td>
              <td><strong class="text-lg text-red-600">{{ number_format($item->jumlah_keluar) }}</strong></td>
              <td>{{ $item->satuan }}</td>
              <td class="font-mono">{{ isset($item->harga_format) ? $item->harga_format : 'Rp ' . number_format($item->harga) }}</td>
              <td class="font-mono font-semibold text-red-600">{{ isset($item->total_format) ? $item->total_format : 'Rp ' . number_format($item->total) }}</td>
              <td>
                <button onclick="openDetail({{ $item->id }})" class="action-btn" title="Detail">
                  <svg viewBox="0 0 24 24" fill="#4F6FFF"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                </button>
                <button onclick="openEdit({{ $item->id }})" class="action-btn" title="Edit">
                  <svg viewBox="0 0 24 24" fill="#10B981"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm18-11.5c0-.41-.17-.79-.44-1.06l-2.25-2.25a1.5 1.5 0 0 0-2.12 0l-1.83 1.83 3.75 3.75 1.83-1.83c.27-.27.44-.65.44-1.06z"/></svg>
                </button>
                <button onclick="confirmDelete({{ $item->id }})" class="action-btn danger" title="Hapus">
                  <svg viewBox="0 0 24 24" fill="#EF4444"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                </button>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="12" style="text-align:center; padding:60px; color:var(--muted);">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="currentColor" style="margin:0 auto 16px; opacity:.4; display:block;">
                  <path d="M7 18c0 1.1.9 2 2 2h6c1.1 0 2-.9 2-2V8H7v10zm2-8h6v6h-6v-6zm0-2V4h6v4h-6zM5 8V5h2V3H5V1H3v2H1v1.99L3 8h2z"/>
                </svg>
                <div style="font-size:15px; font-weight:700; margin-bottom:8px;">Belum ada transaksi keluar</div>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>

        <div class="table-footer">
          <span>Menampilkan {{ $transaksi->firstItem() ?? 0 }}–{{ $transaksi->lastItem() ?? 0 }} dari {{ $transaksi->total() }} data</span>
          <div>{{ $transaksi->appends(request()->query())->links() }}</div>
        </div>
      </div>
    </div>
  </main>

{{-- ===================== MODAL CREATE ===================== --}}
<div id="createModal" class="modal-overlay">
  <div class="modal" style="max-width:640px; padding:0; display:flex; flex-direction:column; max-height:92vh; overflow:hidden; border-radius:20px; box-shadow:0 25px 60px rgba(0,0,0,.18),0 8px 24px rgba(79,111,255,.12);">

    {{-- Header --}}
    <div style="padding:22px 28px 18px; border-bottom:1px solid var(--border); background:linear-gradient(135deg,#F8FAFF,#EEF2FF); flex-shrink:0;">
      <div style="display:flex; align-items:center; justify-content:space-between;">
        <div style="display:flex; align-items:center; gap:12px;">
          <div style="width:40px; height:40px; border-radius:12px; background:linear-gradient(135deg,var(--blue),#7C3AED); display:flex; align-items:center; justify-content:center; box-shadow:0 4px 12px rgba(79,111,255,.35); flex-shrink:0;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="white"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
          </div>
          <div>
            <div style="font-size:17px; font-weight:800; color:var(--text);">Tambah Transaksi Keluar</div>
            <div style="font-size:12px; color:var(--muted); margin-top:2px; font-weight:500;">Isi data transaksi keluar barang persediaan</div>
          </div>
        </div>
        <button onclick="closeModal('createModal')" style="width:32px; height:32px; border-radius:8px; border:1.5px solid var(--border); background:var(--surface); display:flex; align-items:center; justify-content:center; cursor:pointer; color:var(--muted); transition:all .15s;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
        </button>
      </div>
    </div>

    <form id="createForm" method="POST" action="{{ route('adminpersediaan.transaksi-keluar.store') }}" style="display:flex; flex-direction:column; flex:1; overflow:hidden;">
      @csrf
      <div style="padding:28px; overflow-y:auto; flex:1;">

        {{-- Seksi Pilih Barang Master --}}
        <div class="section-label" style="margin-bottom:24px;">
          <span class="section-label-inner green">Data Barang Master</span>
        </div>
        <div class="form-row">
          <div class="form-group" style="grid-column: 1 / -1;">
            <label class="form-label">Pilih Barang Persediaan <span style="color:var(--danger);">*</span></label>
            <select id="create_select_barang" class="form-select" required>
              <option value="">-- Pilih Kode / Nama Barang --</option>
              @foreach($masterPersediaan as $item)
                <option value="{{ $item['kode_barang'] }}" 
                        data-nama="{{ $item['nama_barang'] }}"
                        data-kodekat="{{ $item['kode_kategori'] }}"
                        data-kat="{{ $item['kategori'] }}"
                        data-satuan="{{ $item['satuan'] }}"
                        data-harga="{{ $item['harga_satuan'] }}"
                        data-harga-total="{{ $item['harga_total'] ?? 0 }}"
                        data-multi-harga="{{ !empty($item['multi_harga']) ? '1' : '0' }}"
                        data-stok="{{ $item['stok_tersedia'] ?? 0 }}"
                        data-batches="{{ json_encode($item['batches'] ?? []) }}">
                  {{ $item['kode_barang'] }} - {{ $item['nama_barang'] }} (Stok: {{ $item['stok_tersedia'] ?? 0 }})
                </option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Kode Barang</label>
            <input type="text" name="kode_barang" id="create_kode_barang" class="form-input" readonly style="background: #F1F5F9; color: #64748B;" placeholder="Otomatis">
          </div>
          <div class="form-group">
            <label class="form-label">Nama Barang</label>
            <input type="text" name="nama_barang" id="create_nama_barang" class="form-input" readonly style="background: #F1F5F9; color: #64748B;" placeholder="Otomatis">
          </div>
        </div>

        {{-- Seksi Kategori --}}
        <div class="section-label" style="margin-top:8px; margin-bottom:24px;">
          <span class="section-label-inner">Data Kategori</span>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Kode Kategori</label>
            <input type="text" name="kode_kategori" id="create_kode_kategori" class="form-input" readonly style="background: #F1F5F9; color: #64748B;" placeholder="Otomatis">
          </div>
          <div class="form-group">
            <label class="form-label">Nama Kategori</label>
            <input type="text" name="kategori" id="create_kategori" class="form-input" readonly style="background: #F1F5F9; color: #64748B;" placeholder="Otomatis">
          </div>
        </div>

        {{-- Seksi Transaksi --}}
        <div class="section-label" style="margin-top:8px; margin-bottom:24px;">
          <span class="section-label-inner red">Data Transaksi</span>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Tanggal Input <span style="color:var(--danger);">*</span></label>
            <input type="date" name="tanggal_input" id="create_tanggal_input" class="form-input" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Jumlah Keluar <span style="color:var(--danger);">*</span></label>
            <input type="number" name="jumlah_keluar" id="create_jumlah_keluar" class="form-input" min="1" placeholder="0" required oninput="calculateTotal('create')">
          </div>
          <div class="form-group">
            <label class="form-label">Satuan</label>
            <input type="text" name="satuan" id="create_satuan" class="form-input" readonly style="background: #F1F5F9; color: #64748B;" placeholder="Otomatis">
          </div>
          <div class="form-group">
            <label class="form-label">Harga Satuan (Rp)</label>
            <div style="display:flex; align-items:center; border:2px solid var(--border); border-radius:12px; overflow:hidden; background:#F1F5F9;">
              <span style="padding:12px 14px; font-size:13px; font-weight:700; color:var(--muted); border-right:2px solid var(--border); background:#E2E8F0; white-space:nowrap;">Rp</span>
              <input type="number" name="harga" id="create_harga" readonly
                style="border:none; outline:none; width:100%; padding:14px 16px; font-family:inherit; font-size:14px; background:#F1F5F9; color: #64748B;">
            </div>
          </div>
        </div>

        {{-- Total --}}
        <div style="display:flex; align-items:center; border:2px solid #FECACA; border-radius:12px; overflow:hidden; background:#FFF5F5;">
          <span style="padding:12px 14px; font-size:13px; font-weight:700; color:var(--danger); border-right:2px solid #FECACA; background:#FEF2F2; white-space:nowrap;">{{ !empty($fifoEnabled) ? 'Total (FIFO)' : 'Total' }}</span>
          <input type="text" id="create_total_display" readonly
            style="border:none; outline:none; width:100%; padding:14px 16px; font-family:inherit; font-size:18px; font-weight:800; color:var(--danger); background:#FFF5F5;" value="Rp 0">
        </div>
        <div id="create_fifo_breakdown" style="display:none; margin-top:10px; padding:10px 14px; border-radius:10px; background:#EEF2FF; border:1px solid #C7D2FE; color:#3730A3; font-size:12px; line-height:1.5;"></div>

      </div>

      {{-- Footer --}}
      <div style="padding:20px 28px; border-top:1px solid var(--border); background:#FAFBFF; display:flex; align-items:center; justify-content:flex-end; gap:12px; flex-shrink:0;">
        <div style="font-size:12px; color:var(--muted); flex:1;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="var(--muted)" style="vertical-align:middle; margin-right:4px;"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
          Kolom bertanda <strong style="color:var(--danger);">*</strong> wajib diisi
        </div>
        <button type="button" onclick="closeModal('createModal')" class="btn" style="background:var(--bg); color:var(--text); border:1.5px solid var(--border); padding:12px 24px;">Batal</button>
        <button type="submit" class="btn" style="background:linear-gradient(135deg,var(--blue),#7C3AED); color:white; box-shadow:0 4px 14px rgba(79,111,255,.35); padding:12px 28px;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="white" style="margin-right:6px; vertical-align:middle;"><path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/></svg>
          Simpan Transaksi
        </button>
      </div>
    </form>
  </div>
</div>

{{-- ===================== MODAL DETAIL ===================== --}}
<div id="detailModal" class="modal-overlay">
  <div class="modal" style="max-width:580px;">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; padding-bottom:16px; border-bottom:1px solid var(--border);">
      <div style="display:flex; align-items:center; gap:12px;">
        <div style="width:44px; height:44px; border-radius:12px; background:linear-gradient(135deg,#EEF2FF,#E0E7FF); display:flex; align-items:center; justify-content:center;">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="var(--blue)"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
        </div>
        <div>
          <h3 style="font-size:18px; font-weight:800; color:var(--text); margin:0;">Detail Transaksi Keluar</h3>
          <p id="detailSubtitle" style="font-size:13px; color:var(--muted); margin:4px 0 0 0;"></p>
        </div>
      </div>
      <button onclick="closeModal('detailModal')" style="width:36px; height:36px; border-radius:10px; border:1.5px solid var(--border); background:var(--surface); display:flex; align-items:center; justify-content:center; cursor:pointer;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="var(--muted)"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
      </button>
    </div>

    <div id="detailContent"></div>

    <div style="margin-top:24px; padding-top:20px; border-top:1px solid var(--border); display:flex; justify-content:flex-end;">
      <button onclick="closeModal('detailModal')" class="btn" style="background:var(--bg); color:var(--text); border:1.5px solid var(--border); padding:12px 24px;">Tutup</button>
    </div>
  </div>
</div>

{{-- ===================== MODAL EDIT ===================== --}}
<div id="editModal" class="modal-overlay">
  <div class="modal" style="max-width:640px; padding:0; display:flex; flex-direction:column; max-height:92vh; overflow:hidden; border-radius:20px; box-shadow:0 25px 60px rgba(0,0,0,.18);">

    {{-- Header --}}
    <div style="padding:22px 28px 18px; border-bottom:1px solid var(--border); background:linear-gradient(135deg,#FEF3C7,#FEF2F2); flex-shrink:0;">
      <div style="display:flex; align-items:center; justify-content:space-between;">
        <div style="display:flex; align-items:center; gap:12px;">
          <div style="width:40px; height:40px; border-radius:12px; background:linear-gradient(135deg,var(--warning),#F59E0B); display:flex; align-items:center; justify-content:center;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="white"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25z"/></svg>
          </div>
          <div>
            <div style="font-size:17px; font-weight:800; color:var(--text);">Edit Transaksi Keluar</div>
            <div id="editSubtitle" style="font-size:12px; color:var(--muted); margin-top:2px; font-weight:500;"></div>
          </div>
        </div>
        <button onclick="closeModal('editModal')" style="width:32px; height:32px; border-radius:8px; border:1.5px solid var(--border); background:var(--surface); display:flex; align-items:center; justify-content:center; cursor:pointer;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="var(--muted)"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
        </button>
      </div>
    </div>

    <form id="editForm" method="POST" style="display:flex; flex-direction:column; flex:1; overflow:hidden;">
      @csrf
      @method('PUT')
      <div style="padding:28px; overflow-y:auto; flex:1;">
        <input type="hidden" name="id" id="edit_id">

        <div class="section-label" style="margin-bottom:24px;">
          <span class="section-label-inner">Data Kategori</span>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Kode Kategori <span style="color:var(--danger);">*</span></label>
            <input type="text" name="kode_kategori" id="edit_kode_kategori" class="form-input" maxlength="20" required>
          </div>
          <div class="form-group">
            <label class="form-label">Nama Kategori <span style="color:var(--danger);">*</span></label>
            <input type="text" name="kategori" id="edit_kategori" class="form-input" required>
          </div>
        </div>

        <div class="section-label" style="margin-top:8px; margin-bottom:24px;">
          <span class="section-label-inner green">Data Barang</span>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Kode Barang <span style="color:var(--danger);">*</span></label>
            <input type="text" name="kode_barang" id="edit_kode_barang" class="form-input" maxlength="50" required>
          </div>
          <div class="form-group">
            <label class="form-label">Nama Barang <span style="color:var(--danger);">*</span></label>
            <input type="text" name="nama_barang" id="edit_nama_barang" class="form-input" required>
          </div>
        </div>

        <div class="section-label" style="margin-top:8px; margin-bottom:24px;">
          <span class="section-label-inner red">Data Transaksi</span>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Tanggal Input <span style="color:var(--danger);">*</span></label>
            <input type="date" name="tanggal_input" id="edit_tanggal_input" class="form-input" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Jumlah Keluar <span style="color:var(--danger);">*</span></label>
            <input type="number" name="jumlah_keluar" id="edit_jumlah_keluar" class="form-input" min="1" required oninput="calculateTotal('edit')">
          </div>
          <div class="form-group">
            <label class="form-label">Satuan <span style="color:var(--danger);">*</span></label>
            <input type="text" name="satuan" id="edit_satuan" class="form-input" required>
          </div>
          <div class="form-group">
            <label class="form-label">Harga Satuan (Rp) <span style="color:var(--danger);">*</span></label>
            <div style="display:flex; align-items:center; border:2px solid var(--border); border-radius:12px; overflow:hidden; background:var(--bg);">
              <span style="padding:12px 14px; font-size:13px; font-weight:700; color:var(--muted); border-right:2px solid var(--border); background:#F8FAFF; white-space:nowrap;">Rp</span>
              <input type="number" name="harga" id="edit_harga" min="0" required oninput="calculateTotal('edit')"
                style="border:none; outline:none; width:100%; padding:14px 16px; font-family:inherit; font-size:14px; background:var(--bg);">
            </div>
          </div>
        </div>
        {{-- Total --}}
        <div style="display:flex; align-items:center; border:2px solid #FECACA; border-radius:12px; overflow:hidden; background:#FFF5F5;">
          <span style="padding:12px 14px; font-size:13px; font-weight:700; color:var(--danger); border-right:2px solid #FECACA; background:#FEF2F2; white-space:nowrap;">Total</span>
          <input type="text" id="edit_total_display" readonly
            style="border:none; outline:none; width:100%; padding:14px 16px; font-family:inherit; font-size:18px; font-weight:800; color:var(--danger); background:#FFF5F5;" value="Rp 0">
        </div>
      </div>

      <div style="padding:20px 28px; border-top:1px solid var(--border); background:#FAFBFF; display:flex; align-items:center; justify-content:flex-end; gap:12px; flex-shrink:0;">
        <button type="button" onclick="closeModal('editModal')" class="btn" style="background:var(--bg); color:var(--text); border:1.5px solid var(--border); padding:12px 24px;">Batal</button>
        <button type="submit" class="btn" style="background:linear-gradient(135deg,var(--warning),#F59E0B); color:white; box-shadow:0 4px 14px rgba(245,158,11,.35); padding:12px 28px;">
          Update Transaksi
        </button>
      </div>
    </form>
  </div>
</div>

{{-- ===================== MODAL DELETE ===================== --}}
<div id="deleteModal" class="modal-overlay">
  <div class="modal" style="max-width:480px;">
    <div style="text-align:center; margin-bottom:24px;">
      <div style="width:72px; height:72px; border-radius:20px; background:linear-gradient(135deg,#FEF2F2,#FEE2E2); margin:0 auto 20px; display:flex; align-items:center; justify-content:center;">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="#EF4444"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
      </div>
      <h3 style="font-size:20px; font-weight:800; color:var(--text); margin-bottom:8px;">Hapus Transaksi Keluar?</h3>
      <p style="color:var(--muted); font-size:14px; line-height:1.6; max-width:360px; margin:0 auto;">
        Data transaksi ini akan dihapus permanen dan tidak dapat dipulihkan kembali.
      </p>
      <p id="deleteTitle" style="font-weight:700; color:var(--danger); margin-top:12px; font-size:15px;"></p>
    </div>

    <form id="deleteForm" method="POST">
      @csrf @method('DELETE')
      <div style="display:flex; gap:12px; justify-content:flex-end;">
        <button type="button" onclick="closeModal('deleteModal')" class="btn" style="background:var(--bg); color:var(--text); border:1.5px solid var(--border); padding:14px 28px;">Batal</button>
        <button type="submit" class="btn" style="background:linear-gradient(135deg,var(--danger),#DC2626); color:white; padding:14px 28px; box-shadow:0 4px 14px rgba(239,68,68,.35);">
          Hapus Permanen
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  const IS_FIFO_ENABLED = @json($fifoEnabled ?? false);

  function formatRupiah(angka) {
    return 'Rp ' + new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0 }).format(angka);
  }

  function calculateTotal(prefix) {
    const jumlah = parseInt(document.getElementById(prefix + '_jumlah_keluar').value) || 0;
    const harga  = parseFloat(document.getElementById(prefix + '_harga').value) || 0;

    if (IS_FIFO_ENABLED && prefix === 'create') {
      const select = document.getElementById('create_select_barang');
      const selectedOption = select ? select.options[select.selectedIndex] : null;
      const breakdownEl = document.getElementById('create_fifo_breakdown');
      let batches = [];
      if (selectedOption && selectedOption.getAttribute('data-batches')) {
        try {
          batches = JSON.parse(selectedOption.getAttribute('data-batches') || '[]');
        } catch (e) {
          batches = [];
        }
      }

      if (batches.length > 0 && jumlah > 0) {
        let sisa = jumlah;
        let totalFifo = 0;
        const parts = [];
        const satuan = selectedOption.getAttribute('data-satuan') || '';

        batches.forEach((b) => {
          if (sisa <= 0) return;
          const bSisa = parseInt(b.jumlah_sisa) || 0;
          if (bSisa <= 0) return;
          const ambil = Math.min(sisa, bSisa);
          const bHarga = parseFloat(b.harga_satuan) || 0;
          totalFifo += ambil * bHarga;
          sisa -= ambil;
          parts.push(`<strong>${ambil} ${satuan}</strong> × ${formatRupiah(bHarga)}`);
        });

        if (sisa > 0) {
          totalFifo += sisa * harga;
          parts.push(`<strong>${sisa} ${satuan}</strong> × ${formatRupiah(harga)}`);
        }

        document.getElementById('create_total_display').value = formatRupiah(Math.round(totalFifo));
        document.getElementById('create_harga').value = Math.round(totalFifo / jumlah);

        if (breakdownEl) {
          if (batches.length > 1 || parts.length > 1) {
            breakdownEl.style.display = 'block';
            breakdownEl.innerHTML = `🏷️ <strong>Simulasi FIFO Otomatis:</strong> ${parts.join(' + ')}`;
          } else {
            breakdownEl.style.display = 'none';
          }
        }
        return;
      } else if (breakdownEl) {
        if (batches.length > 1) {
          const satuan = selectedOption ? (selectedOption.getAttribute('data-satuan') || '') : '';
          const summary = batches.map(b => `${b.jumlah_sisa} ${satuan} @ ${formatRupiah(b.harga_satuan)}`).join(' • ');
          breakdownEl.style.display = 'block';
          breakdownEl.innerHTML = `📦 <strong>Stok Batch Aktif (FIFO):</strong> ${summary}`;
        } else {
          breakdownEl.style.display = 'none';
        }
      }
    } else if (!IS_FIFO_ENABLED && prefix === 'create') {
      const select = document.getElementById('create_select_barang');
      const selectedOption = select ? select.options[select.selectedIndex] : null;
      const breakdownEl = document.getElementById('create_fifo_breakdown');
      const stokTersedia = selectedOption ? (parseInt(selectedOption.getAttribute('data-stok')) || 0) : 0;
      const hargaTotalPool = selectedOption ? (parseFloat(selectedOption.getAttribute('data-harga-total')) || 0) : 0;
      const isMultiHarga = selectedOption && selectedOption.getAttribute('data-multi-harga') === '1';
      const satuan = selectedOption ? (selectedOption.getAttribute('data-satuan') || '') : '';

      if (breakdownEl) {
        if (isMultiHarga && stokTersedia > 0 && hargaTotalPool > 0) {
          breakdownEl.style.display = 'block';
          breakdownEl.innerHTML = `💡 <strong>Harga Rata-Rata Campuran:</strong> Dihitung proporsional dari total nilai persediaan (${formatRupiah(Math.round(hargaTotalPool))} ÷ ${stokTersedia.toLocaleString('id-ID')} ${satuan}).`;
        } else {
          breakdownEl.style.display = 'none';
        }
      }

      if (stokTersedia > 0 && hargaTotalPool > 0 && jumlah > 0) {
        const totalProporsional = (jumlah >= stokTersedia)
          ? Math.round(hargaTotalPool)
          : Math.round((hargaTotalPool * jumlah) / stokTersedia);
        document.getElementById('create_total_display').value = formatRupiah(totalProporsional);
        document.getElementById('create_harga').value = Math.round(totalProporsional / jumlah);
        return;
      }
    }

    document.getElementById(prefix + '_total_display').value = formatRupiah(Math.round(jumlah * harga));
  }

  function autofillMasterData(kodeBarang) {
    const select = document.getElementById('create_select_barang');
    const selectedOption = select.options[select.selectedIndex];

    if (!kodeBarang) {
      document.getElementById('create_kode_barang').value = '';
      document.getElementById('create_nama_barang').value = '';
      document.getElementById('create_kode_kategori').value = '';
      document.getElementById('create_kategori').value = '';
      document.getElementById('create_harga').value = 0;
      document.getElementById('create_jumlah_keluar').max = '';
      document.getElementById('create_jumlah_keluar').placeholder = '0';
      document.getElementById('create_satuan').value = '';
      calculateTotal('create');
      return;
    }

    const namaBarang   = selectedOption.getAttribute('data-nama');
    const kodeKategori = selectedOption.getAttribute('data-kodekat');
    const kategori     = selectedOption.getAttribute('data-kat');
    const hargaSatuan  = selectedOption.getAttribute('data-harga');
    const stokTersedia = selectedOption.getAttribute('data-stok'); 
    const satuan       = selectedOption.getAttribute('data-satuan');


    document.getElementById('create_kode_barang').value = kodeBarang;
    document.getElementById('create_nama_barang').value = namaBarang;
    document.getElementById('create_kode_kategori').value = kodeKategori;
    document.getElementById('create_kategori').value = kategori;
    document.getElementById('create_satuan').value = satuan;
    document.getElementById('create_harga').value = Math.round(parseFloat(hargaSatuan));
    
    const inputJumlah = document.getElementById('create_jumlah_keluar');
    inputJumlah.max = stokTersedia;
    inputJumlah.placeholder = `Maksimal ${stokTersedia}`;

    calculateTotal('create');
  }

  function openModal(id) {
    document.getElementById(id).classList.add('show');
    document.body.style.overflow = 'hidden';
  }

  function closeModal(id = null) {
    document.querySelectorAll('.modal-overlay.show').forEach(m => m.classList.remove('show'));
    document.body.style.overflow = '';
  }

  document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', e => { if (e.target === overlay) closeModal(); });
  });

  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

  // Inisialisasi Select2 Persediaan
  $(document).ready(function() {
      $('#create_select_barang').select2({
          dropdownParent: $('#createModal'), // Mencegah dropdown tertutup overlay modal custom
          placeholder: "-- Pilih Kode / Nama Barang --",
          width: '100%'
      });

      // Hubungkan Select2 dengan fungsi autofill (menggantikan onchange bawaan select tag)
      $('#create_select_barang').on('change', function() {
          autofillMasterData(this.value);
      });
  });

  // Pemicu Klik Tambah Baru
  document.querySelector('[onclick="openModal(\'createModal\')"]').addEventListener('click', () => {
    document.getElementById('createForm').reset();
    
    // Reset ulang Select2 ke state awal (null)
    $('#create_select_barang').val(null).trigger('change.select2'); 
    
    document.getElementById('create_tanggal_input').value = new Date().toISOString().split('T')[0];
    document.getElementById('create_total_display').value = 'Rp 0';
    const breakdownEl = document.getElementById('create_fifo_breakdown');
    if (breakdownEl) {
      breakdownEl.style.display = 'none';
      breakdownEl.innerHTML = '';
    }
    
    document.getElementById('create_kode_barang').value = '';
    document.getElementById('create_nama_barang').value = '';
    document.getElementById('create_kode_kategori').value = '';
    document.getElementById('create_kategori').value = '';
    document.getElementById('create_jumlah_keluar').placeholder = '0';
    document.getElementById('create_jumlah_keluar').max = '';
    document.getElementById('create_satuan').value = '';
  });

  // ——— DETAIL ———
  function openDetail(id) {
    const row = document.querySelector(`tr[data-id="${id}"]`);
    if (!row) return;
    const d = {
        tanggal: row.dataset.tanggal_input,
        kode_kategori: row.dataset.kode_kategori,
        kategori: row.dataset.kategori,
        kode_barang: row.dataset.kode_barang,
        nama_barang: row.dataset.nama_barang,
        jumlah: parseInt(row.dataset.jumlah_keluar) || 0,
        harga: parseFloat(row.dataset.harga) || 0,
        total: parseFloat(row.dataset.total) || 0,
        satuan: row.dataset.satuan,
    };

    document.getElementById('detailSubtitle').textContent = `Detail Transaksi`; 
    document.getElementById('detailContent').innerHTML = `
      <div style="display:flex; gap:16px; align-items:center; margin-bottom:20px;">
        <div style="padding:8px 14px; background:#EEF2FF; border-radius:8px; font-weight:800; color:var(--blue); font-size:13px;">${d.kode_kategori}</div>
        <div style="font-weight:600; color:var(--text);">${d.kategori}</div>
      </div>
      <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
        <div style="background:var(--bg); border-radius:10px; padding:16px;">
          <div style="font-size:11px; color:var(--muted); font-weight:600; margin-bottom:6px; text-transform:uppercase; letter-spacing:.5px;">Kode Barang</div>
          <div style="font-size:16px; font-weight:800; color:var(--text); font-family:monospace;">${d.kode_barang}</div>
        </div>
        <div style="background:var(--bg); border-radius:10px; padding:16px;">
          <div style="font-size:11px; color:var(--muted); font-weight:600; margin-bottom:6px; text-transform:uppercase; letter-spacing:.5px;">Nama Barang</div>
          <div style="font-size:15px; font-weight:700; color:var(--text);">${d.nama_barang}</div>
        </div>
        <div style="background:var(--bg); border-radius:10px; padding:16px;">
          <div style="font-size:11px; color:var(--muted); font-weight:600; margin-bottom:6px; text-transform:uppercase; letter-spacing:.5px;">Tanggal Input</div>
          <div style="font-size:15px; font-weight:700; color:var(--text);">${d.tanggal}</div>
        </div>
        <div style="background:var(--bg); border-radius:10px; padding:16px;">
          <div style="font-size:11px; color:var(--muted); font-weight:600; margin-bottom:6px; text-transform:uppercase; letter-spacing:.5px;">Jumlah Keluar</div>
          <div style="font-size:20px; font-weight:800; color:var(--danger);">${d.jumlah.toLocaleString('id-ID')}</div>
        </div>
        <div style="background:var(--bg); border-radius:10px; padding:16px;">
          <div style="font-size:11px; color:var(--muted); font-weight:600; margin-bottom:6px; text-transform:uppercase; letter-spacing:.5px;">Satuan</div>
          <div style="font-size:15px; font-weight:700; color:var(--text);">${d.satuan}</div>
        </div>
        <div style="background:var(--bg); border-radius:10px; padding:16px;">
          <div style="font-size:11px; color:var(--muted); font-weight:600; margin-bottom:6px; text-transform:uppercase; letter-spacing:.5px;">Harga Satuan</div>
          <div style="font-size:15px; font-weight:700; color:var(--text);">${formatRupiah(d.harga)}</div>
        </div>
        <div style="background:linear-gradient(135deg,#FEF2F2,#FEE2E2); border-radius:10px; padding:16px; border:1.5px solid #FECACA;">
          <div style="font-size:11px; color:var(--danger); font-weight:700; margin-bottom:6px; text-transform:uppercase; letter-spacing:.5px;">Total</div>
          <div style="font-size:22px; font-weight:800; color:var(--danger);">${formatRupiah(d.total)}</div>
        </div>
       
      </div>
    `;
    openModal('detailModal');
  }

  // ——— EDIT ———
  function openEdit(id) {
    const row = document.querySelector(`tr[data-id="${id}"]`);
    if (!row) return;
    const d = {
        id: id,
        tanggal_input: row.dataset.tanggal_input,
        kode_kategori: row.dataset.kode_kategori,
        kategori: row.dataset.kategori,
        kode_barang: row.dataset.kode_barang,
        nama_barang: row.dataset.nama_barang,
        jumlah_keluar: row.dataset.jumlah_keluar,
        harga: Math.round(parseFloat(row.dataset.harga) || 0),
        satuan: row.dataset.satuan,
    };

    document.getElementById('edit_id').value = d.id;
    document.getElementById('edit_tanggal_input').value = d.tanggal_input;
    document.getElementById('edit_kode_kategori').value = d.kode_kategori;
    document.getElementById('edit_kategori').value = d.kategori;
    document.getElementById('edit_kode_barang').value = d.kode_barang;
    document.getElementById('edit_nama_barang').value = d.nama_barang;
    document.getElementById('edit_jumlah_keluar').value = d.jumlah_keluar;
    document.getElementById('edit_harga').value = d.harga;
    document.getElementById('edit_satuan').value = d.satuan;
    
    document.getElementById('editSubtitle').textContent = `Kode Barang: ${d.kode_barang}`;
    document.getElementById('editForm').action = `{{ route('adminpersediaan.transaksi-keluar.update', ':id') }}`.replace(':id', d.id);

    calculateTotal('edit');
    openModal('editModal');
  }

  // ——— DELETE ———
  function confirmDelete(id) {
    const row = document.querySelector(`tr[data-id="${id}"]`);
    if (!row) return;
    document.getElementById('deleteTitle').textContent = `No: — ${row.dataset.nama_barang}`;
    document.getElementById('deleteForm').action = `{{ route('adminpersediaan.transaksi-keluar.destroy', ':id') }}`.replace(':id', id);
    openModal('deleteModal');
  }
</script>

</body>
</html>