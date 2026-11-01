<!DOCTYPE html>
<html lang="id">
<head>
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
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

  .btn-filter {
    display: flex; align-items: center; gap: 6px;
    padding: 8px 14px; border-radius: 10px;
    border: 1.5px solid var(--border); background: var(--bg);
    font-family: inherit; font-size: 13px; color: #64748B; cursor: pointer;
    transition: all .15s;
  }
  .btn-filter:hover { border-color: var(--blue); color: var(--blue); }

  table { width: 100%; border-collapse: collapse; }
  thead tr { background: #F8FAFF; }
  th {
    padding: 12px 20px;
    text-align: left;
    font-size: 11px; font-weight: 700;
    color: var(--blue); letter-spacing: .8px; text-transform: uppercase;
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
  }
  td {
    padding: 14px 20px;
    font-size: 13.5px; color: var(--text);
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
  }
  
  tr:last-child td { border-bottom: none; }
  tbody tr { transition: background .15s; }
  tbody tr:hover { background: #F8FAFF; }

  /* STATUS BADGE */
  .status-badge {
    padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .5px;
  }
  .status-baikk { background: #ECFDF5; color: var(--success); }
  .status-rusak { background: #FEF2F2; color: var(--danger); }
  .status-perawatan { background: #FEF3C7; color: var(--warning); }

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

  /* TOMBOL IMPORT & HEADER ACTIONS */
  .header-actions {
    display: flex; gap: 12px; align-items: center;
  }
  .btn-import {
    display: flex; align-items: center; gap: 7px;
    padding: 10px 18px; border-radius: 10px;
    background: var(--surface);
    color: var(--blue); font-size: 13.5px; font-weight: 700;
    font-family: inherit; border: 1.5px solid var(--blue); cursor: pointer;
    transition: all .2s;
  }
  .btn-import:hover { background: #EEF2FF; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(79,111,255,.15); }
  .btn-import svg { width: 16px; height: 16px; fill: currentColor; }
  
  .upload-area {
    border: 2px dashed var(--border); padding: 32px 20px; 
    text-align: center; border-radius: 12px; background: #F8FAFF; 
    margin-bottom: 12px; transition: border-color 0.2s;
  }
  .upload-area:hover { border-color: var(--blue); }

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
  .modal-title { font-size: 20px; font-weight: 800; color: var(--text); margin-bottom: 20px; }
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
  .btn-group { display: flex; gap: 12px; justify-content: flex-end; }

  /* PAGINATION */
  .table-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 20px;
    border-top: 1px solid var(--border);
    font-size: 13px; color: var(--muted);
  }
  .pagination { display: flex; align-items: center; gap: 6px; }
  .page-btn {
    padding: 6px 12px; border-radius: 8px;
    border: 1px solid var(--border); background: var(--surface);
    font-family: inherit; font-size: 13px; color: #64748B;
    cursor: pointer; transition: all .15s;
  }
  .page-btn:hover:not(.active) { border-color: var(--blue); color: var(--blue); }
  .page-btn.active {
    background: var(--blue); border-color: var(--blue);
    color: white; font-weight: 700;
  }

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
    <span class="topbar-title">Data Persediaan</span>
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
      <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
      </svg>
      {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
      </svg>
      {{ session('error') }}
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

    <div class="page-top">
      <div>
        <h1>Data Persediaan</h1>
        <p>{{ $persediaan->total() }} data ditemukan</p>
      </div>
      <div class="header-actions">
        <button onclick="openModal('importModal')" class="btn-import">
          <svg viewBox="0 0 24 24">
            <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/>
          </svg>
          Import Excel
        </button>
        <button onclick="openModal('createModal')" class="btn-tambah">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="white">
            <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
          </svg>
          Tambah Baru
        </button>
      </div>
    </div>

    <div class="table-card">
      <div class="table-toolbar">
        <form method="GET" action="{{ route('adminpersediaan.data-persediaan') }}" class="search-wrap">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="#94A3B8">
            <path d="M15.5 14h-.79l-.28-.27A6.47 6.47 0 0016 9.5 6.5 6.5 0 109.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
          </svg>
          <input type="text" name="search" placeholder="Cari nama atau kode unik barang..." value="{{ request('search') }}">
        </form>
        
        <form method="GET" action="{{ route('adminpersediaan.data-persediaan') }}" style="display: flex; gap: 12px;">
          <input type="hidden" name="search" value="{{ request('search') }}">
          <select name="kategori" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            @foreach(\App\Models\Persediaan::select('kode_kategori', 'kategori')->distinct()->orderBy('kategori')->get() as $kategori)
              <option value="{{ $kategori->kode_kategori }}" {{ request('kategori') == $kategori->kode_kategori ? 'selected' : '' }}>
                {{ $kategori->kategori }}
              </option>
            @endforeach
          </select>
        </form>
      </div>

      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Kode Unik Barang</th>
            <th>Kategori</th>
            <th>Nama Barang</th>
            <th>Tanggal Perolehan</th>
            <th>Harga Satuan</th>
            <th>Harga Total</th>
            <th>Jumlah</th>
            <th>Satuan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($persediaan as $index => $item)
          @php
            $batchesList = $item->relationLoaded('batches') ? $item->batches : collect([]);
            $batchesPayload = $batchesList->map(function ($b) {
                $hargaB = (float) ($b->getRawOriginal('harga_satuan') ?? $b->harga_satuan ?? 0);
                $sisaB = (int) ($b->jumlah_sisa ?? 0);
                return [
                    'id'            => $b->id,
                    'tanggal_masuk' => $b->tanggal_masuk ? $b->tanggal_masuk->format('d/m/Y') : '-',
                    'harga_satuan'  => $hargaB,
                    'jumlah_awal'   => (int) ($b->jumlah_awal ?? 0),
                    'jumlah_sisa'   => $sisaB,
                    'sumber'        => $b->sumber ?? 'stok_awal',
                    'subtotal_sisa' => $hargaB * $sisaB,
                ];
            })->values()->all();

            $activeBatchesCol = collect($batchesPayload)->where('jumlah_sisa', '>', 0);
            $distinctActivePrices = $activeBatchesCol->pluck('harga_satuan')->map(fn($p) => (string) round((float) $p, 2))->unique()->count();
            $distinctAllPrices = collect($batchesPayload)->pluck('harga_satuan')->map(fn($p) => (string) round((float) $p, 2))->unique()->count();
            $totalBatchesCount = count($batchesPayload);
            $rawHargaTotal = $item->getRawOriginal('harga_total') ?? $item->harga_total ?? ($item->harga_satuan * $item->jumlah);
          @endphp
          <tr data-id="{{ $item->id }}"
            data-kode-kategori="{{ $item->kode_kategori }}"
            data-kategori="{{ $item->kategori }}"
            data-kode-barang="{{ $item->kode_barang }}"
            data-kode-unik-barang="{{ $item->kode_unik_barang }}"
            data-nama-barang="{{ $item->nama_barang }}"
            data-tanggal-masuk="{{ $item->tanggal_masuk->format('Y-m-d') }}" {{-- Format standar input date --}}
            data-harga-satuan="{{ $item->getRawOriginal('harga_satuan') ?? $item->harga_satuan }}" {{-- Nilai mentah: 10000 --}}
            data-harga-total="{{ $rawHargaTotal }}" {{-- Nilai mentah total akurat dari seluruh batch --}}
            data-jumlah="{{ $item->jumlah }}"
            data-satuan="{{ $item->satuan }}"
            data-multi-batch="{{ $totalBatchesCount > 1 ? '1' : '0' }}"
            data-batches="{{ json_encode($batchesPayload) }}">
            <td><strong>{{ $persediaan->firstItem() + $loop->index }}</strong></td>
            <td><strong>{{ $item->kode_unik_barang }}</strong></td>
            <td>{{ $item->kategori }}</td>
            <td>{{ Str::limit($item->nama_barang, 30) }}</td>
            <td>{{ $item->tanggal_masuk->format('d/m/Y') }}</td>
            <td class="font-mono">
              <div>{{ $item->harga_satuan_format }}</div>
              @if($distinctActivePrices > 1)
                <button type="button" onclick="openDetail({{ $item->id }})" title="Klik untuk melihat daftar variasi harga barang ini"
                  style="margin-top:5px; display:inline-flex; align-items:center; gap:4px; padding:3px 9px; border-radius:999px; border:1px solid #C7D2FE; background:linear-gradient(135deg,#EEF2FF,#E0E7FF); color:#4338CA; font-family:'Plus Jakarta Sans',sans-serif; font-size:10.5px; font-weight:700; cursor:pointer; white-space:nowrap; transition:all .15s;">
                  🏷️ {{ $distinctActivePrices }} Harga • Lihat
                </button>
              @elseif($distinctAllPrices > 1 || $totalBatchesCount > 1)
                <button type="button" onclick="openDetail({{ $item->id }})" title="Klik untuk melihat riwayat batch harga masuk"
                  style="margin-top:5px; display:inline-flex; align-items:center; gap:4px; padding:3px 9px; border-radius:999px; border:1px solid #E2E8F0; background:#F8FAFC; color:#475569; font-family:'Plus Jakarta Sans',sans-serif; font-size:10.5px; font-weight:600; cursor:pointer; white-space:nowrap; transition:all .15s;">
                  📋 {{ $totalBatchesCount }} Batch • Lihat
                </button>
              @endif
            </td>
            <td class="font-mono font-semibold text-green-600">{{ $item->harga_total_format }}</td>
            <td><strong class="text-lg">{{ number_format($item->jumlah) }}</strong></td>
            <td>{{ $item->satuan }}</td>
            <td>
              <a href="javascript:void(0)" onclick="openDetail({{ $item->id }})" class="action-btn" title="Detail & List Harga">
                <svg viewBox="0 0 24 24" fill="#4F6FFF">
                  <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                </svg>
              </a>
              <a href="javascript:void(0)" onclick="openEdit({{ $item->id }})" class="action-btn edit" title="Edit">
                <svg viewBox="0 0 24 24" fill="#10B981">
                  <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm18-11.5c0-.41-.17-.79-.44-1.06l-2.25-2.25a1.5 1.5 0 0 0-2.12 0l-1.83 1.83 3.75 3.75 1.83-1.83c.27-.27.44-.65.44-1.06z"/>
                </svg>
              </a>
              <a href="javascript:void(0)" onclick="confirmDelete({{ $item->id }})" class="action-btn delete" title="Hapus">
                <svg viewBox="0 0 24 24" fill="#EF4444">
                  <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                </svg>
              </a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="10" style="text-align: center; padding: 60px; color: var(--muted);">
              <svg width="64" height="64" viewBox="0 0 24 24" fill="currentColor" style="margin: 0 auto 16px; opacity: 0.5;">
                <path d="M7 18c0 1.1.9 2 2 2h6c1.1 0 2-.9 2-2V8H7v10zm2-8h6v6h-6v-6zm0-2V4h6v4h-6zM5 8V5h2V3H5V1H3v2H1v1.99L3 8h2zm16 6V8c0-1.1-.9-2-2-2h-1v2h1v2h1v1h-1v2h1c1.1 0 2-.9 2-2z"/>
              </svg>
              <div style="font-size: 16px; font-weight: 600; margin-bottom: 12px;">Belum ada data persediaan</div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>

      <div class="table-footer">
        <span>Menampilkan {{ $persediaan->firstItem() ?? 0 }}–{{ $persediaan->lastItem() ?? 0 }} dari {{ $persediaan->total() }} data</span>
        <div class="pagination">
          {{ $persediaan->appends(request()->query())->links() }}
        </div>
      </div>
    </div>
  </div>
</main>

{{-- MODAL TAMBAH (MULTI-ITEM SUPPORT) --}}
<div id="createModal" class="modal-overlay">
  <div class="modal" style="max-width:860px; padding:0; display:flex; flex-direction:column; max-height:92vh; overflow:hidden; border-radius:20px; box-shadow:0 25px 60px rgba(0,0,0,.18),0 8px 24px rgba(79,111,255,.12);">
    <div style="padding:20px 28px 16px; border-bottom:1px solid var(--border); background:linear-gradient(135deg,#F8FAFF,#EEF2FF); flex-shrink:0;">
      <div style="display:flex; align-items:center; justify-content:space-between;">
        <div style="display:flex; align-items:center; gap:12px;">
          <div style="width:40px; height:40px; border-radius:12px; background:linear-gradient(135deg,var(--blue),#7C3AED); display:flex; align-items:center; justify-content:center; box-shadow:0 4px 12px rgba(79,111,255,.35); flex-shrink:0;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="white"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
          </div>
          <div>
            <div style="font-size:17px; font-weight:800; color:var(--text);">Tambah Data Persediaan</div>
            <div style="font-size:12px; color:var(--muted); margin-top:2px; font-weight:500;">Tambahkan satu atau banyak barang persediaan sekaligus</div>
          </div>
        </div>
        <button type="button" onclick="closeModal('createModal')" style="width:32px; height:32px; border-radius:8px; border:1.5px solid var(--border); background:var(--surface); display:flex; align-items:center; justify-content:center; cursor:pointer; color:var(--muted); transition:all .15s;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
        </button>
      </div>
    </div>

    <form id="createForm" method="POST" action="{{ route('adminpersediaan.data-persediaan.store') }}" style="display:flex; flex-direction:column; flex:1; overflow:hidden;">
      @csrf
      <div style="padding:24px 28px; overflow-y:auto; flex:1;">
        
        <div id="persediaanItemsContainer" style="display: flex; flex-direction: column; gap: 18px;">
          <!-- Item 1 -->
          <div class="persediaan-item-card" style="border:1.5px solid var(--border); border-radius:14px; padding:18px; background:#FAFBFD; position:relative;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; padding-bottom:10px; border-bottom:1px solid var(--border);">
              <span style="font-size:13px; font-weight:700; color:var(--blue);">📦 Data Barang #<span class="p-item-num">1</span></span>
              <button type="button" class="btn-hapus-p-item" onclick="hapusBarisPersediaan(this)" style="display:none; background:#FEE2E2; color:#DC2626; border:none; border-radius:6px; padding:4px 10px; font-size:11px; font-weight:700; cursor:pointer;">
                <i class="fas fa-trash"></i> Hapus Baris
              </button>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Kode Kategori <span style="color:var(--danger);">*</span></label>
                <input type="text" name="items[0][kode_kategori]" class="form-input" placeholder="Contoh: 1010301003" required>
              </div>
              <div class="form-group">
                <label class="form-label">Kode Barang <span style="color:var(--danger);">*</span></label>
                <input type="text" name="items[0][kode_barang]" class="form-input" placeholder="Contoh: 000001" required>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Nama Kategori <span style="color:var(--danger);">*</span></label>
                <input type="text" name="items[0][kategori]" class="form-input" placeholder="Alat Tulis Kantor, Kertas, dll" required>
              </div>
              <div class="form-group">
                <label class="form-label">Nama Barang <span style="color:var(--danger);">*</span></label>
                <input type="text" name="items[0][nama_barang]" class="form-input" placeholder="Nama lengkap barang persediaan..." required>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Tanggal Masuk <span style="color:var(--danger);">*</span></label>
                <input type="date" name="items[0][tanggal_masuk]" class="form-input" value="{{ date('Y-m-d') }}" required>
              </div>
              <div class="form-group">
                <label class="form-label">Satuan <span style="color:var(--danger);">*</span></label>
                <select name="items[0][satuan]" class="form-select" required>
                  <option value="">Pilih satuan</option>
                  <option value="buah">Buah</option>
                  <option value="rim">Rim</option>
                  <option value="lusin">Lusin</option>
                  <option value="dos">Dos</option>
                  <option value="paket">Paket</option>
                  <option value="pak">Pak</option>
                  <option value="unit">Unit</option>
                  <option value="set">Set</option>
                  <option value="karton">Karton</option>
                  <option value="box">Box</option>
                </select>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Harga Satuan <span style="color:var(--danger);">*</span></label>
                <div style="display:flex; align-items:center; border:1.5px solid var(--border); border-radius:10px; overflow:hidden; background:var(--bg);">
                  <span style="padding:10px 12px; font-size:13px; font-weight:700; color:var(--muted); border-right:1.5px solid var(--border); background:#F8FAFF; white-space:nowrap;">Rp</span>
                  <input type="text" name="items[0][harga_satuan]" class="form-input form-input-price p-harga-input" placeholder="0" required style="border-left:none; border-radius:0 10px 10px 0; width:100%; background:var(--bg);" oninput="hitungSubtotalPersediaan(this)">
                </div>
              </div>
              <div class="form-group">
                <label class="form-label">Jumlah <span style="color:var(--danger);">*</span></label>
                <input type="number" name="items[0][jumlah]" min="1" class="form-input p-jumlah-input" value="1" placeholder="1" required oninput="hitungSubtotalPersediaan(this)">
              </div>
            </div>

            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label">Subtotal Harga</label>
              <div style="display:flex; align-items:center; border:1.5px solid var(--border); border-radius:10px; overflow:hidden; background:#F0FDF4;">
                <span style="padding:10px 12px; font-size:13px; font-weight:700; color:var(--success); border-right:1.5px solid var(--border); background:#ECFDF5; white-space:nowrap;">Rp</span>
                <input type="text" class="p-subtotal-output" readonly style="border:none; background:transparent; color:var(--success); font-weight:700; width:100%;" value="0">
              </div>
            </div>
          </div>
        </div>

        <!-- Tombol Tambah Baris Barang -->
        <div style="margin-top: 18px;">
          <button type="button" onclick="tambahBarisPersediaan()" class="btn" style="background:#EEF2FF; color:var(--blue); border:1.5px dashed #A5B4FC; width:100%; padding:12px; font-weight:700; border-radius:10px; display:flex; align-items:center; justify-content:center; gap:8px;">
            <i class="fas fa-plus-circle"></i> + Tambah Baris Barang Persediaan
          </button>
        </div>
      </div>

      <div style="padding:18px 28px; border-top:1px solid var(--border); background:#FAFBFF; display:flex; align-items:center; justify-content:flex-end; gap:12px; flex-shrink:0;">
        <button type="button" onclick="closeModal('createModal')" class="btn" style="background:var(--bg); color:var(--text); border:1.5px solid var(--border); padding:12px 24px;">
          Batal
        </button>
        <button type="submit" class="btn" style="background:linear-gradient(135deg,var(--blue),#7C3AED); color:white; box-shadow:0 4px 14px rgba(79,111,255,.35); padding:12px 28px;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="white" style="margin-right:6px; vertical-align:middle;">
            <path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1 .89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/>
          </svg>
          Simpan Semua Persediaan
        </button>
      </div>
    </form>
  </div>
</div>

{{-- MODAL DETAIL --}}
<div id="detailModal" class="modal-overlay">
  <div class="modal" style="max-width:760px; max-height:90vh; display:flex; flex-direction:column;">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; padding-bottom:16px; border-bottom:1px solid var(--border); flex-shrink:0;">
      <div style="display:flex; align-items:center; gap:12px;">
        <div style="width:44px; height:44px; border-radius:12px; background:linear-gradient(135deg,#EEF2FF,#E0E7FF); display:flex; align-items:center; justify-content:center;">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="var(--blue)"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
        </div>
        <div>
          <h3 style="font-size:18px; font-weight:800; color:var(--text); margin:0;">Detail & Daftar Harga Persediaan</h3>
          <p id="detailTitle" style="font-size:13px; color:var(--muted); margin:4px 0 0 0;"></p>
        </div>
      </div>
      <button onclick="closeModal('detailModal')" style="width:36px; height:36px; border-radius:10px; border:1.5px solid var(--border); background:var(--surface); display:flex; align-items:center; justify-content:center; cursor:pointer;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="var(--muted)"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
      </button>
    </div>

    <div id="detailContent" style="overflow-y:auto; flex:1; padding-right:4px;"></div>

    <div style="margin-top:20px; padding-top:16px; border-top:1px solid var(--border); display:flex; justify-content:flex-end; flex-shrink:0;">
      <button onclick="closeModal('detailModal')" class="btn" style="background:var(--bg); color:var(--text); border:1.5px solid var(--border); padding:12px 24px;">
        Tutup
      </button>
    </div>
  </div>
</div>

{{-- MODAL IMPORT EXCEL --}}
<div id="importModal" class="modal-overlay">
  <div class="modal" style="max-width: 480px;">
    <h2 class="modal-title">Import Data Persediaan</h2>
    <form action="{{ route('adminpersediaan.data-persediaan.import') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="form-group">
        <label class="form-label">Pilih File Excel/CSV <span style="color:var(--danger);">*</span></label>
        <div class="upload-area">
          <svg width="40" height="40" viewBox="0 0 24 24" fill="var(--blue)" style="opacity: 0.7; margin: 0 auto 12px;">
            <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
          </svg>
          <input type="file" name="file_excel" accept=".xlsx, .xls, .csv" class="form-input" style="background: white; padding: 10px;" required>
        </div>
        <p style="font-size: 12.5px; color: var(--muted); line-height: 1.6;">
          Unggah file dengan ekstensi <strong>.xlsx</strong> atau <strong>.csv</strong>. Pastikan format kolom sesuai dengan template.
          <br>
          <a href="{{ route('adminpersediaan.data-persediaan.template') }}" style="color: var(--blue); text-decoration: none; font-weight: 700; display: inline-block; margin-top: 6px;">
            ↓ Unduh Template Excel
          </a>
        </p>
      </div>
      <div class="btn-group" style="margin-top: 24px; justify-content: flex-end; display: flex; gap: 12px;">
        <button type="button" class="btn" style="background:var(--bg); color:var(--text); border:1.5px solid var(--border); padding:12px 24px;" onclick="closeModal('importModal')">Batal</button>
        <button type="submit" class="btn" style="background:linear-gradient(135deg,var(--blue),#7C3AED); color:white; padding:12px 24px;" onclick="this.innerHTML='Mengunggah...';">Mulai Import</button>
      </div>
    </form>
  </div>
</div>

{{-- MODAL EDIT --}}
<div id="editModal" class="modal-overlay">
  <div class="modal" style="max-width:620px; padding:0;">
    <div style="padding:22px 28px 18px; border-bottom:1px solid var(--border); background:linear-gradient(135deg,#FEF3C7,#FEF2F2); flex-shrink:0;">
      <div style="display:flex; align-items:center; justify-content:space-between;">
        <div style="display:flex; align-items:center; gap:12px;">
          <div style="width:40px; height:40px; border-radius:12px; background:linear-gradient(135deg,var(--warning),#F59E0B); display:flex; align-items:center; justify-content:center;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="white"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25z"/></svg>
          </div>
          <div>
            <div style="font-size:17px; font-weight:800; color:var(--text);">Edit Persediaan</div>
            <div id="editTitle" style="font-size:12px; color:var(--muted); margin-top:2px; font-weight:500;"></div>
          </div>
        </div>
        <button onclick="closeModal('editModal')" style="width:32px; height:32px; border-radius:8px; border:1.5px solid var(--border); background:var(--surface); display:flex; align-items:center; justify-content:center; cursor:pointer;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="var(--muted)"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
        </button>
      </div>
    </div>

    <form id="editForm" method="POST" style="display:flex; flex-direction:column; flex:1;">
      @csrf
      @method('PUT')
      <div style="padding:28px; overflow-y:auto; flex:1;">
        <input type="hidden" name="id" id="editId">

        <div id="editMultiBatchNotice" style="display:none; margin-bottom:18px; padding:12px 14px; border-radius:10px; background:#EEF2FF; border:1px solid #C7D2FE; color:#3730A3; font-size:12.5px; line-height:1.5;">
          <strong>ℹ️ Info Multi-Harga:</strong> Barang ini memiliki beberapa batch harga masuk. Mengubah identitas barang (nama/kategori/satuan) <strong>tidak akan merusak</strong> rincian harga tiap batch.
        </div>
        
        <div style="font-size:10.5px; font-weight:700; color:var(--blue); letter-spacing:1.2px; text-transform:uppercase; padding-bottom:10px; border-bottom:1.5px solid var(--border); margin-bottom:24px;">
          <span style="background:linear-gradient(135deg,#EEF2FF,#E0E7FF); border-radius:4px; padding:4px 10px;">Identitas Barang</span>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Kode Unik Barang <span style="color:var(--danger);">*</span></label>
            <input type="text" name="kode_unik_barang" id="editKodeUnikBarang" class="form-input" maxlength="100" required>
          </div>
          <div class="form-group">
            <label class="form-label">Nama Kategori <span style="color:var(--danger);">*</span></label>
            <input type="text" name="kategori" id="editKategori" class="form-input" maxlength="100" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Nama Barang <span style="color:var(--danger);">*</span></label>
            <input type="text" name="nama_barang" id="editNamaBarang" class="form-input" maxlength="200" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Tanggal Masuk <span style="color:var(--danger);">*</span></label>
            <input type="date" name="tanggal_masuk" id="editTanggalMasuk" class="form-input" required>
          </div>
          <div class="form-group">
            <label class="form-label">Harga Satuan <span style="color:var(--danger);">*</span></label>
            <div style="display:flex; align-items:center; border:1.5px solid var(--border); border-radius:10px; overflow:hidden; background:var(--bg);">
              <span style="padding:12px 14px; font-size:13px; font-weight:700; color:var(--muted); border-right:1.5px solid var(--border); background:#F8FAFF; white-space:nowrap;">Rp</span>
              <input type="text" name="harga_satuan" id="editHargaSatuan" placeholder="0" required style="border-left:none; border-radius:0 10px 10px 0;" class="form-input-price">
            </div>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Jumlah <span style="color:var(--danger);">*</span></label>
            <input type="number" name="jumlah" id="editJumlah" min="1" class="form-input" placeholder="1" required onchange="calculateTotalEdit()">
          </div>
          <div class="form-group">
            <label class="form-label">Satuan <span style="color:var(--danger);">*</span></label>
            <select name="satuan" id="editSatuan" class="form-select" required>
              <option value="">Pilih satuan</option>
              <option value="lusin">Lusin</option>
              <option value="rim">Rim</option>
              <option value="buah">Buah</option>
              <option value="dos">Dos</option>
              <option value="paket">Paket</option>
              <option value="pak">Pak</option>
              <option value="unit">Unit</option>
              <option value="set">Set</option>
              <option value="karton">Karton</option>
              <option value="box">Box</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Harga Total</label>
            <div style="display:flex; align-items:center; border:1.5px solid var(--border); border-radius:10px; overflow:hidden; background:var(--bg);">
              <span style="padding:12px 14px; font-size:13px; font-weight:700; color:var(--success); border-right:1.5px solid var(--border); background:#ECFDF5; white-space:nowrap;">Rp</span>
              <input type="text" id="editHargaTotal" readonly style="border-left:none; border-radius:0 10px 10px 0; background:#F0FDF4; color:var(--success); font-weight:600; width:100%;" value="0">
            </div>
          </div>
        </div>
      </div>
      
      <div style="padding:20px 28px; border-top:1px solid var(--border); background:#FAFBFF; display:flex; align-items:center; justify-content:flex-end; gap:12px;">
        <button type="button" onclick="closeModal('editModal')" class="btn" style="background:var(--bg); color:var(--text); border:1.5px solid var(--border); padding:12px 24px;">
          Batal
        </button>
        <button type="submit" class="btn" style="background:linear-gradient(135deg,var(--warning),#F59E0B); color:white; box-shadow:0 4px 14px rgba(245,158,11,.35); padding:12px 28px;">
          Update Data
        </button>
      </div>
    </form>
  </div>
</div>

{{-- MODAL DELETE --}}
<div id="deleteModal" class="modal-overlay">
  <div class="modal" style="max-width:480px;">
    <div style="text-align:center; margin-bottom:24px;">
      <div style="width:72px; height:72px; border-radius:20px; background:linear-gradient(135deg,#FEF2F2,#FEE2E2); margin:0 auto 20px; display:flex; align-items:center; justify-content:center;">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="#EF4444">
          <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
        </svg>
      </div>
      <h3 style="font-size:20px; font-weight:800; color:var(--text); margin-bottom:8px;">Hapus Data Persediaan?</h3>
      <p style="color:var(--muted); font-size:14px; line-height:1.6; max-width:380px; margin:0 auto;">
        Data persediaan ini akan dihapus permanen dan tidak dapat dipulihkan kembali. Pastikan Anda sudah memeriksa data dengan teliti.
      </p>
      <p id="deleteTitle" style="font-weight:600; color:var(--danger); margin-top:12px;"></p>
    </div>
    
    <form id="deleteForm" method="POST">
      @csrf 
      @method('DELETE')
      <div style="display:flex; gap:12px; justify-content:flex-end;">
        <button type="button" onclick="closeModal('deleteModal')" class="btn" style="background:var(--bg); color:var(--text); border:1.5px solid var(--border); padding:14px 28px;">
          Batal
        </button>
        <button type="submit" class="btn" style="background:linear-gradient(135deg,var(--danger),#DC2626); color:white; padding:14px 28px; box-shadow:0 4px 14px rgba(239,68,68,.35);">
          Hapus Permanen
        </button>
      </div>
    </form>
  </div>
</div>

<script>
// Close modal & ESC key
document.querySelectorAll('.modal-overlay').forEach(modal => {
  modal.addEventListener('click', e => {
    if (e.target === modal) closeModal();
  });
});

document.addEventListener('keydown', e => {
  if (e.key === 'Escape') closeModal();
});

function openModal(modalId) {
  document.getElementById(modalId).classList.add('show');
  document.body.style.overflow = 'hidden';
}

function closeModal(modalId = null) {
  document.querySelectorAll('.modal-overlay.show').forEach(modal => {
    modal.classList.remove('show');
  });
  document.body.style.overflow = '';
  if (modalId === 'editModal') document.getElementById('editForm').reset();
}

// 🔥 UTILITY FUNCTIONS
function getRawNumber(selector) {
  const input = typeof selector === 'string' ? document.querySelector(selector) : selector;
  if (!input) return 0;
  // Membersihkan semua karakter kecuali angka
  const clean = String(input.value || '').replace(/[^\d]/g, '');
  return clean ? parseFloat(clean) : 0;
}

function formatCurrency(number) {
  return new Intl.NumberFormat('id-ID', {
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(number || 0);
}

function parseDatasetNumber(value) {
  return parseFloat(String(value || '0').replace(/[^\d.]/g, '')) || 0;
}

// Format input rupiah saat user mengetik secara real-time
function handlePriceInput(input) {
  const oldVal = input.value || '';
  const cursorPos = input.selectionStart;
  const oldLength = oldVal.length;

  const raw = getRawNumber(input);
  if (raw === 0 && (oldVal === '' || oldVal === '0')) {
    input.value = '';
    return;
  }

  const formatted = raw > 0 ? formatCurrency(raw) : '';
  input.value = formatted;

  // Menjaga posisi kursor agar tetap nyaman saat mengetik angka panjang
  if (cursorPos !== null) {
    const diff = formatted.length - oldLength;
    const newPos = Math.max(0, cursorPos + diff);
    input.setSelectionRange(newPos, newPos);
  }
}

// 🔥 CALCULATE
function calculateTotalEdit() {
  const hargaSatuanRaw = getRawNumber('#editHargaSatuan');
  const jumlahRaw = parseFloat(document.getElementById('editJumlah')?.value) || 0;
  const total = hargaSatuanRaw * jumlahRaw;
  const el = document.getElementById('editHargaTotal');
  if (el) el.value = formatCurrency(total);
}

// 🔥 EVENT HANDLERS
document.addEventListener('input', function(e) {
  if (e.target.classList.contains('form-input-price')) {
    handlePriceInput(e.target);
    if (e.target.closest('.persediaan-item-card')) {
      hitungSubtotalPersediaan(e.target);
    } else if (e.target.id === 'editHargaSatuan') {
      calculateTotalEdit();
    }
  }

  if (e.target.classList.contains('p-jumlah-input')) {
    hitungSubtotalPersediaan(e.target);
  }

  if (e.target.id === 'editJumlah') {
    calculateTotalEdit();
  }
}, true);

document.addEventListener('blur', function(e) {
  if (e.target.classList.contains('form-input-price')) {
    const rawValue = getRawNumber(e.target);
    e.target.value = rawValue > 0 ? formatCurrency(rawValue) : '';
    if (e.target.closest('.persediaan-item-card')) {
      hitungSubtotalPersediaan(e.target);
    } else if (e.target.id === 'editHargaSatuan') {
      calculateTotalEdit();
    }
  }
}, true);

// 🔥 DETAIL MODAL - MULTI-HARGA BATCH FIFO
function formatSumberBatch(sumber) {
  switch (sumber) {
    case 'transaksi_masuk': return { label: 'Transaksi Masuk', bg: '#EEF2FF', color: '#4338CA' };
    case 'import':          return { label: 'Import Excel', bg: '#ECFDF5', color: '#047857' };
    case 'penyesuaian':     return { label: 'Penyesuaian', bg: '#FEF3C7', color: '#B45309' };
    default:                return { label: 'Stok Awal', bg: '#F1F5F9', color: '#475569' };
  }
}

function escapeHtmlSafe(str) {
  return String(str ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

function populateDetailModal(data) {
  document.getElementById('detailTitle').textContent = `Kode: ${data.kode_unik_barang} • ${data.nama_barang}`;
  
  const formattedHargaSatuan = formatCurrency(parseDatasetNumber(data.harga_satuan));
  const formattedHargaTotal = formatCurrency(parseDatasetNumber(data.harga_total));
  const satuanLabel = escapeHtmlSafe(data.satuan || '');
  const batches = Array.isArray(data.batches) ? data.batches : [];

  let firstActiveFound = false;
  let batchRowsHtml = '';

  if (batches.length > 0) {
    batches.forEach((b, idx) => {
      const sisa = parseInt(b.jumlah_sisa) || 0;
      const awal = parseInt(b.jumlah_awal) || 0;
      const harga = parseFloat(b.harga_satuan) || 0;
      const subtotal = sisa * harga;
      const isNextFifo = (!firstActiveFound && sisa > 0);
      if (isNextFifo) firstActiveFound = true;

      const sumberInfo = formatSumberBatch(b.sumber);
      const rowBg = sisa === 0 ? '#F8FAFC' : (isNextFifo ? '#F0FDF4' : '#FFFFFF');
      const rowOpacity = sisa === 0 ? '0.68' : '1';

      batchRowsHtml += `
        <tr style="background:${rowBg}; opacity:${rowOpacity}; border-bottom:1px solid #E2E8F0;">
          <td style="padding:10px 12px; font-size:12.5px; font-weight:700; color:var(--text); white-space:nowrap;">
            #${idx + 1}
            ${isNextFifo ? `<div style="margin-top:3px;"><span style="display:inline-block; padding:2px 7px; border-radius:999px; background:#DCFCE7; color:#15803D; font-size:10px; font-weight:800;">Dipakai Duluan (FIFO)</span></div>` : ''}
          </td>
          <td style="padding:10px 12px; font-size:12.5px; white-space:nowrap;">
            <div style="font-weight:600; color:var(--text);">${escapeHtmlSafe(b.tanggal_masuk || '-')}</div>
            <span style="display:inline-block; margin-top:3px; padding:2px 7px; border-radius:6px; background:${sumberInfo.bg}; color:${sumberInfo.color}; font-size:10.5px; font-weight:700;">${sumberInfo.label}</span>
          </td>
          <td style="padding:10px 12px; font-size:13px; font-weight:700; color:var(--text); text-align:right; font-family:monospace; white-space:nowrap;">
            Rp ${formatCurrency(harga)}
          </td>
          <td style="padding:10px 12px; font-size:12.5px; color:var(--muted); text-align:center; white-space:nowrap;">
            ${awal.toLocaleString('id-ID')} ${satuanLabel}
          </td>
          <td style="padding:10px 12px; font-size:13px; text-align:center; white-space:nowrap;">
            ${sisa > 0
              ? `<span style="display:inline-block; padding:3px 9px; border-radius:999px; background:#ECFDF5; color:#059669; font-weight:800;">${sisa.toLocaleString('id-ID')} ${satuanLabel}</span>`
              : `<span style="display:inline-block; padding:3px 9px; border-radius:999px; background:#F1F5F9; color:#94A3B8; font-weight:700; font-size:11.5px;">Habis (0)</span>`
            }
          </td>
          <td style="padding:10px 12px; font-size:13px; font-weight:700; color:${sisa > 0 ? 'var(--blue)' : '#94A3B8'}; text-align:right; font-family:monospace; white-space:nowrap;">
            Rp ${formatCurrency(subtotal)}
          </td>
        </tr>
      `;
    });
  } else {
    batchRowsHtml = `
      <tr>
        <td colspan="6" style="padding:18px; text-align:center; color:var(--muted); font-size:13px;">
          1 Harga Aktif: Rp ${formattedHargaSatuan} (${parseInt(data.jumlah || 0).toLocaleString('id-ID')} ${satuanLabel})
        </td>
      </tr>
    `;
  }

  document.getElementById('detailContent').innerHTML = `
    <div style="display:flex; flex-direction:column; gap:18px; width: 100%;">
      <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:16px;">
        <div>
          <div style="font-size:11.5px; color:var(--muted); font-weight:600; margin-bottom:4px;">Kode Unik Barang</div>
          <div style="font-size:15px; font-weight:800; color:var(--text);">${escapeHtmlSafe(data.kode_unik_barang)}</div>
        </div>
        <div>
          <div style="font-size:11.5px; color:var(--muted); font-weight:600; margin-bottom:4px;">Kategori</div>
          <div style="font-size:14px; font-weight:700; color:var(--text); line-height:1.3;">${escapeHtmlSafe(data.kategori)}</div>
        </div>
        <div>
          <div style="font-size:11.5px; color:var(--muted); font-weight:600; margin-bottom:4px;">Nama Barang</div>
          <div style="font-size:14px; font-weight:700; color:var(--text); line-height:1.3;">${escapeHtmlSafe(data.nama_barang)}</div>
        </div>
      </div>
      
      <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(150px,1fr)); gap:14px; padding:16px 18px; background:#F8FAFF; border-radius:12px; border:1px solid var(--border);">
        <div>
          <div style="font-size:11.5px; color:var(--muted); margin-bottom:4px;">Tanggal Masuk Terakhir</div>
          <div style="font-weight:700; font-size:13.5px;">${data.tanggal_masuk ? new Date(data.tanggal_masuk).toLocaleDateString('id-ID', {day:'2-digit', month:'2-digit', year:'numeric'}) : '-'}</div>
        </div>
        <div>
          <div style="font-size:11.5px; color:var(--muted); margin-bottom:4px;">Total Sisa Stok</div>
          <div style="font-weight:800; font-size:19px; color:var(--success);">${parseInt(data.jumlah || 0).toLocaleString('id-ID')} <span style="font-size:12.5px; font-weight:600; color:var(--muted); text-transform:capitalize;">${satuanLabel}</span></div>
        </div>
        <div>
          <div style="font-size:11.5px; color:var(--muted); margin-bottom:4px;">Harga Terbaru</div>
          <div style="font-weight:700; font-size:14px; color:var(--text);">Rp ${formattedHargaSatuan}</div>
        </div>
        <div>
          <div style="font-size:11.5px; color:var(--muted); margin-bottom:4px;">Total Nilai Persediaan</div>
          <div style="font-weight:800; font-size:16px; color:var(--blue); background:linear-gradient(135deg,#EEF2FF,#E0E7FF); padding:6px 10px; border-radius:8px; display:inline-block;">
            Rp ${formattedHargaTotal}
          </div>
        </div>
      </div>

      <div style="border:1.5px solid var(--border); border-radius:14px; overflow:hidden; background:#FFFFFF;">
        <div style="padding:12px 16px; background:linear-gradient(135deg,#EEF2FF,#F8FAFF); border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px;">
          <div style="font-size:13px; font-weight:800; color:var(--text); display:flex; align-items:center; gap:6px;">
            🏷️ Daftar List Harga & Sisa Stok per Batch Masuk
          </div>
          <span style="font-size:11px; font-weight:700; color:#4338CA; background:#E0E7FF; padding:3px 10px; border-radius:999px;">
            ${batches.length} Batch Tercatat • Metode FIFO
          </span>
        </div>
        <div style="overflow-x:auto;">
          <table style="width:100%; border-collapse:collapse;">
            <thead>
              <tr style="background:#F8FAFC; border-bottom:1.5px solid #E2E8F0;">
                <th style="padding:10px 12px; text-align:left; font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase;">Batch</th>
                <th style="padding:10px 12px; text-align:left; font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase;">Tgl Masuk</th>
                <th style="padding:10px 12px; text-align:right; font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase;">Harga Satuan</th>
                <th style="padding:10px 12px; text-align:center; font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase;">Masuk Awal</th>
                <th style="padding:10px 12px; text-align:center; font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase;">Sisa Stok</th>
                <th style="padding:10px 12px; text-align:right; font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase;">Subtotal Sisa</th>
              </tr>
            </thead>
            <tbody>
              ${batchRowsHtml}
            </tbody>
            <tfoot>
              <tr style="background:#F8FAFF; border-top:2px solid #CBD5E1;">
                <td colspan="4" style="padding:11px 12px; font-size:12.5px; font-weight:800; color:var(--text); text-align:right;">
                  TOTAL AKUMULASI AKTIF
                </td>
                <td style="padding:11px 12px; font-size:13px; font-weight:800; color:var(--success); text-align:center; white-space:nowrap;">
                  ${parseInt(data.jumlah || 0).toLocaleString('id-ID')} ${satuanLabel}
                </td>
                <td style="padding:11px 12px; font-size:14px; font-weight:800; color:var(--blue); text-align:right; font-family:monospace; white-space:nowrap;">
                  Rp ${formattedHargaTotal}
                </td>
              </tr>
            </tfoot>
          </table>
        </div>
        <div style="padding:10px 16px; background:#FAFBFF; border-top:1px solid #E2E8F0; font-size:11.5px; color:var(--muted); line-height:1.5;">
          💡 <strong>Catatan FIFO:</strong> Saat barang keluar (melalui Transaksi Keluar maupun Permintaan Persediaan), stok otomatis dipotong dari batch yang masuk lebih awal hingga habis sebelum beralih ke harga berikutnya.
        </div>
      </div>
    </div>
  `;
}

// 🔥 OPEN MODALS
function openDetail(id) {
  const row = document.querySelector(`tr[data-id="${id}"]`);
  if (!row) return alert('Data tidak ditemukan!');

  let parsedBatches = [];
  try {
    parsedBatches = JSON.parse(row.dataset.batches || '[]');
  } catch (e) {
    parsedBatches = [];
  }
  
  const persediaan = {
    id: row.dataset.id,
    kode_kategori: row.dataset.kodeKategori || row.dataset.kode_kategori || '',
    kode_unik_barang: row.dataset.kodeUnikBarang || '',
    kategori: row.dataset.kategori || '',
    kode_barang: row.dataset.kodeBarang || row.dataset.kode_barang || '',
    nama_barang: row.dataset.namaBarang || row.dataset.nama_barang || '',
    harga_satuan: row.dataset.hargaSatuan || row.dataset.harga_satuan || '0',
    harga_total: row.dataset.hargaTotal || row.dataset.harga_total || '0',
    jumlah: row.dataset.jumlah || '0',
    satuan: row.dataset.satuan || '',
    tanggal_masuk: row.dataset.tanggalMasuk || row.dataset.tanggal_masuk || '',
    batches: parsedBatches
  };
  
  populateDetailModal(persediaan);
  openModal('detailModal');
}

function openEdit(id) {
  refreshCsrfToken();
  const row = document.querySelector(`tr[data-id="${id}"]`);
  if (!row) return alert('Data tidak ditemukan!');

  const persediaan = {
    id: row.dataset.id,
    kode_kategori: row.dataset.kodeKategori || row.dataset.kode_kategori || '',
    kode_unik_barang: row.dataset.kodeUnikBarang || '',
    kategori: row.dataset.kategori || '',
    kode_barang: row.dataset.kodeBarang || row.dataset.kode_barang || '',
    nama_barang: row.dataset.namaBarang || row.dataset.nama_barang || '',
    tanggal_masuk: row.dataset.tanggalMasuk || row.dataset.tanggal_masuk || '',
    harga_satuan: row.dataset.hargaSatuan || row.dataset.harga_satuan || '0',
    jumlah: row.dataset.jumlah || '0',
    satuan: row.dataset.satuan || '',
    harga_total: row.dataset.hargaTotal || row.dataset.harga_total || '0',
    multi_batch: row.dataset.multiBatch === '1'
  };

  const form = document.getElementById('editForm');
  form.action = `{{ route('adminpersediaan.data-persediaan.update', ':id') }}`.replace(':id', id);

  populateEditModal(persediaan);
  openModal('editModal');
}

function refreshCsrfToken() {
  const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  document.querySelectorAll('input[name="_token"]').forEach(el => {
    el.value = token;
  });
}

function confirmDelete(id) {
  refreshCsrfToken();
  const row = document.querySelector(`tr[data-id="${id}"]`);
  if (!row) return alert('Data tidak ditemukan!');
  
  const kode_barang = row.dataset.kodeUnikBarang || '';
  const nama_barang = row.dataset.namaBarang || row.dataset.nama_barang || '';
  
  document.getElementById('deleteTitle').textContent = `Kode: ${kode_barang} - ${nama_barang}`;
  document.getElementById('deleteForm').action = `{{ route('adminpersediaan.data-persediaan.destroy', ':id') }}`.replace(':id', id);
  openModal('deleteModal');
}

function populateEditModal(data) {
  document.getElementById('editId').value = data.id;
  document.getElementById('editKodeUnikBarang').value = data.kode_unik_barang;
  document.getElementById('editKategori').value = data.kategori;
  document.getElementById('editNamaBarang').value = data.nama_barang;
  document.getElementById('editTanggalMasuk').value = data.tanggal_masuk;
  document.getElementById('editHargaSatuan').value = formatCurrency(parseDatasetNumber(data.harga_satuan));
  document.getElementById('editJumlah').value = data.jumlah;
  document.getElementById('editSatuan').value = data.satuan;
  document.getElementById('editHargaTotal').value = formatCurrency(parseDatasetNumber(data.harga_total));
  document.getElementById('editTitle').textContent = `Kode Unik: ${data.kode_unik_barang}`;
  const noticeEl = document.getElementById('editMultiBatchNotice');
  if (noticeEl) {
    noticeEl.style.display = data.multi_batch ? 'block' : 'none';
  }
  if (!data.multi_batch) {
    calculateTotalEdit();
  }
}

// 🔥 MULTI-ITEM PERSEDIAAN FUNCTIONS
let persediaanItemCount = 1;

function hitungSubtotalPersediaan(input) {
  const card = input.closest('.persediaan-item-card');
  if (!card) return;
  const harga = getRawNumber(card.querySelector('.p-harga-input'));
  const jumlah = parseInt(card.querySelector('.p-jumlah-input')?.value) || 0;
  const output = card.querySelector('.p-subtotal-output');
  if (output) {
    output.value = formatCurrency(harga * jumlah);
  }
}

function tambahBarisPersediaan() {
  const container = document.getElementById('persediaanItemsContainer');
  const newIndex = persediaanItemCount;
  persediaanItemCount++;

  const div = document.createElement('div');
  div.className = 'persediaan-item-card';
  div.style.cssText = 'border:1.5px solid var(--border); border-radius:14px; padding:18px; background:#FAFBFD; position:relative;';
  div.innerHTML = `
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; padding-bottom:10px; border-bottom:1px solid var(--border);">
      <span style="font-size:13px; font-weight:700; color:var(--blue);">📦 Data Barang #<span class="p-item-num">${newIndex + 1}</span></span>
      <button type="button" class="btn-hapus-p-item" onclick="hapusBarisPersediaan(this)" style="background:#FEE2E2; color:#DC2626; border:none; border-radius:6px; padding:4px 10px; font-size:11px; font-weight:700; cursor:pointer;">
        <i class="fas fa-trash"></i> Hapus Baris
      </button>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label class="form-label">Kode Kategori <span style="color:var(--danger);">*</span></label>
        <input type="text" name="items[${newIndex}][kode_kategori]" class="form-input" placeholder="Contoh: 1010301003" required>
      </div>
      <div class="form-group">
        <label class="form-label">Kode Barang <span style="color:var(--danger);">*</span></label>
        <input type="text" name="items[${newIndex}][kode_barang]" class="form-input" placeholder="Contoh: 000001" required>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label class="form-label">Nama Kategori <span style="color:var(--danger);">*</span></label>
        <input type="text" name="items[${newIndex}][kategori]" class="form-input" placeholder="Alat Tulis Kantor, Kertas, dll" required>
      </div>
      <div class="form-group">
        <label class="form-label">Nama Barang <span style="color:var(--danger);">*</span></label>
        <input type="text" name="items[${newIndex}][nama_barang]" class="form-input" placeholder="Nama lengkap barang persediaan..." required>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label class="form-label">Tanggal Masuk <span style="color:var(--danger);">*</span></label>
        <input type="date" name="items[${newIndex}][tanggal_masuk]" class="form-input" value="{{ date('Y-m-d') }}" required>
      </div>
      <div class="form-group">
        <label class="form-label">Satuan <span style="color:var(--danger);">*</span></label>
        <select name="items[${newIndex}][satuan]" class="form-select" required>
          <option value="">Pilih satuan</option>
          <option value="buah">Buah</option>
          <option value="rim">Rim</option>
          <option value="lusin">Lusin</option>
          <option value="dos">Dos</option>
          <option value="paket">Paket</option>
          <option value="pak">Pak</option>
          <option value="unit">Unit</option>
          <option value="set">Set</option>
          <option value="karton">Karton</option>
          <option value="box">Box</option>
        </select>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label class="form-label">Harga Satuan <span style="color:var(--danger);">*</span></label>
        <div style="display:flex; align-items:center; border:1.5px solid var(--border); border-radius:10px; overflow:hidden; background:var(--bg);">
          <span style="padding:10px 12px; font-size:13px; font-weight:700; color:var(--muted); border-right:1.5px solid var(--border); background:#F8FAFF; white-space:nowrap;">Rp</span>
          <input type="text" name="items[${newIndex}][harga_satuan]" class="form-input form-input-price p-harga-input" placeholder="0" required style="border-left:none; border-radius:0 10px 10px 0; width:100%; background:var(--bg);" oninput="hitungSubtotalPersediaan(this)">
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Jumlah <span style="color:var(--danger);">*</span></label>
        <input type="number" name="items[${newIndex}][jumlah]" min="1" class="form-input p-jumlah-input" value="1" placeholder="1" required oninput="hitungSubtotalPersediaan(this)">
      </div>
    </div>

    <div class="form-group" style="margin-bottom:0;">
      <label class="form-label">Subtotal Harga</label>
      <div style="display:flex; align-items:center; border:1.5px solid var(--border); border-radius:10px; overflow:hidden; background:#F0FDF4;">
        <span style="padding:10px 12px; font-size:13px; font-weight:700; color:var(--success); border-right:1.5px solid var(--border); background:#ECFDF5; white-space:nowrap;">Rp</span>
        <input type="text" class="p-subtotal-output" readonly style="border:none; background:transparent; color:var(--success); font-weight:700; width:100%;" value="0">
      </div>
    </div>
  `;
  container.appendChild(div);
  updatePersediaanItemNumbers();
}

function hapusBarisPersediaan(btn) {
  const card = btn.closest('.persediaan-item-card');
  card.remove();
  updatePersediaanItemNumbers();
}

function updatePersediaanItemNumbers() {
  const cards = document.querySelectorAll('#persediaanItemsContainer .persediaan-item-card');
  cards.forEach((card, i) => {
    const numSpan = card.querySelector('.p-item-num');
    if (numSpan) numSpan.textContent = i + 1;
    const delBtn = card.querySelector('.btn-hapus-p-item');
    if (delBtn) {
      delBtn.style.display = (cards.length > 1) ? 'inline-block' : 'none';
    }
  });
}

// 🔥 INIT
document.addEventListener('DOMContentLoaded', function() {
  if (typeof calculateTotal === 'function') calculateTotal();
});
</script>

<style>
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
.form-group { display: flex; flex-direction: column; }
.form-label { font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 8px; }
.form-input { 
  width: 100%; padding: 14px 16px; border-radius: 12px;
  border: 2px solid var(--border); background: var(--bg);
  font-family: inherit; font-size: 14px; transition: all .2s;
  box-sizing: border-box; min-width: 0; white-space: nowrap;
}
.form-input:focus { 
  outline: none; border-color: var(--blue); 
  box-shadow: 0 0 0 4px rgba(79,111,255,0.1); background: white;
}
.error-text { font-size: 12px; color: var(--danger); margin-top: 6px; font-weight: 500; }
.modal-overlay.show { opacity: 1; visibility: visible; }
.modal-overlay.show .modal { transform: scale(1) translateY(0); }
@media (max-width: 768px) {
  .form-row { grid-template-columns: 1fr; }
  .main { margin-left: 0; }
}
</style>
</body>
</html>
