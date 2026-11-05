<!DOCTYPE html>
<html lang="id">
<head>
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIPANDU - Barang Lainnya</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
  :root {
    --primary: #d97706;
    --primary-light: #fef3c7;
    --blue: #4F6FFF;
    --sidebar-w: 240px;
    --radius: 16px;
    --bg: #F8FAFC;
    --surface: #FFFFFF;
    --text: #1E293B;
    --muted: #64748B;
    --border: #E2E8F0;
    --success: #10B981;
    --danger: #EF4444;
    --warning: #F59E0B;
  }
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); display: flex; min-height: 100vh; }

  .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }

  .topbar {
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    padding: 0 28px; height: 56px;
    display: flex; align-items: center; justify-content: space-between;
    position: sticky; top: 0; z-index: 50;
  }
  .topbar-title { font-size: 16px; font-weight: 700; }
  .topbar-right { display: flex; align-items: center; gap: 16px; }
  .date-text { font-size: 13px; color: #64748B; font-weight: 500; }

  .content { padding: 28px; flex: 1; }

  .alert {
    padding: 14px 18px; border-radius: 10px; margin-bottom: 20px;
    display: flex; align-items: center; gap: 10px; font-weight: 600; font-size: 13.5px;
  }
  .alert-success { background: #ECFDF5; color: var(--success); border: 1px solid #BBF7D0; }
  .alert-danger { background: #FEF2F2; color: var(--danger); border: 1px solid #FECACA; }

  .page-top {
    display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 14px;
  }
  .page-top h1 { font-size: 22px; font-weight: 800; color: #b45309; display: flex; align-items: center; gap: 8px; }
  .page-top p { font-size: 13px; color: var(--muted); margin-top: 4px; }

  .btn-tambah {
    display: flex; align-items: center; gap: 7px;
    padding: 10px 20px; border-radius: 10px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white; font-size: 13.5px; font-weight: 700;
    font-family: inherit; border: none; cursor: pointer;
    box-shadow: 0 4px 14px rgba(245, 158, 11, .35);
    transition: all .2s;
  }
  .btn-tambah:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(245, 158, 11, .45); }

  /* STATS CARDS */
  .stats-grid {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;
  }
  .stat-card {
    background: #fff; border: 1px solid var(--border); border-radius: 14px; padding: 18px 20px;
    display: flex; align-items: center; gap: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);
  }
  .stat-icon {
    width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
  }
  .stat-num { font-size: 22px; font-weight: 800; color: #0f172a; line-height: 1.2; }
  .stat-lbl { font-size: 12.5px; color: var(--muted); font-weight: 600; margin-top: 2px; }

  /* TABLE CARD */
  .table-card {
    background: var(--surface);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
  }
  .table-toolbar {
    display: flex; align-items: center; gap: 12px;
    padding: 16px 20px; border-bottom: 1px solid var(--border);
  }
  .search-wrap {
    flex: 1; display: flex; align-items: center; gap: 8px;
    border: 1.5px solid var(--border); border-radius: 10px;
    padding: 8px 14px; background: #fff;
  }
  .search-wrap input {
    border: none; background: none; outline: none;
    font-family: inherit; font-size: 13.5px; color: var(--text); width: 100%;
  }

  table { width: 100%; border-collapse: collapse; }
  thead tr { background: #FFFBEB; }
  th {
    padding: 12px 18px; text-align: left;
    font-size: 11.5px; font-weight: 700; color: #92400e;
    letter-spacing: .5px; text-transform: uppercase;
    border-bottom: 1px solid var(--border); white-space: nowrap;
  }
  td {
    padding: 14px 18px; font-size: 13px; color: var(--text);
    border-bottom: 1px solid var(--border); white-space: nowrap;
  }
  tbody tr:hover { background: #FEFCE8; }

  .badge-kode {
    background: #F1F5F9; color: #475569; font-family: monospace; font-weight: 700;
    padding: 3px 8px; border-radius: 6px; font-size: 12px;
  }
  .badge-stok {
    display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 20px;
    font-size: 12px; font-weight: 700;
  }
  .badge-stok-aman { background: #ECFDF5; color: #059669; }
  .badge-stok-kritis { background: #FEF2F2; color: #DC2626; }

  .action-btns { display: flex; gap: 6px; }
  .btn-action {
    width: 32px; height: 32px; border-radius: 8px; border: 1px solid var(--border);
    background: #fff; display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: var(--muted); transition: all .15s;
  }
  .btn-action:hover { background: #FEF3C7; color: #D97706; border-color: #FCD34D; }
  .btn-action.danger:hover { background: #FEF2F2; color: #EF4444; border-color: #FECACA; }

  /* MODALS */
  .modal-overlay {
    position: fixed; inset: 0; background: rgba(15, 23, 42, 0.45);
    backdrop-filter: blur(4px); z-index: 1000;
    display: none; align-items: center; justify-content: center; padding: 20px;
  }
  .modal-overlay.show { display: flex; }
  .modal {
    background: #fff; border-radius: 16px; width: 100%; max-width: 600px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.12); overflow: hidden;
    animation: modalIn .2s ease-out;
  }
  @keyframes modalIn { from { opacity: 0; transform: translateY(12px) scale(.98); } to { opacity: 1; transform: translateY(0) scale(1); } }

  .form-group { margin-bottom: 14px; }
  .form-label { font-size: 12.5px; font-weight: 600; color: #334155; margin-bottom: 5px; display: block; }
  .form-input, .form-select {
    width: 100%; padding: 9px 12px; border-radius: 8px; border: 1px solid var(--border);
    font-family: inherit; font-size: 13px; outline: none; transition: border-color .15s; background: #fff;
  }
  .form-input:focus, .form-select:focus { border-color: var(--primary); }
  .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

  .btn {
    padding: 9px 18px; border-radius: 8px; font-size: 13px; font-weight: 700;
    font-family: inherit; border: none; cursor: pointer; transition: all .15s;
  }
  .btn-secondary { background: var(--bg); border: 1px solid var(--border); color: var(--muted); }
  .btn-secondary:hover { background: #e2e8f0; color: var(--text); }
  .btn-primary { background: var(--primary); color: #fff; }
  .btn-primary:hover { background: #b45309; }

  /* KODE UNIT CHIPS & APPROVAL */
  .mini-unit-wrap { display: flex; flex-wrap: wrap; gap: 4px; max-width: 240px; }
  .mini-unit {
    display: inline-flex; align-items: center; gap: 3px;
    padding: 2px 7px; border-radius: 6px; font-size: 11px; font-weight: 800;
    border: 1px solid transparent; cursor: pointer;
  }
  .mini-unit.tersedia { background: #ECFDF5; color: #059669; border-color: #A7F3D0; }
  .mini-unit.dipinjam { background: #FEF3C7; color: #B45309; border-color: #FDE68A; }
  .mini-unit.rusak, .mini-unit.hilang { background: #FEF2F2; color: #DC2626; border-color: #FECACA; }

  .approve-unit-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(115px, 1fr));
    gap: 8px;
    margin-top: 8px;
  }
  .approve-unit-card {
    position: relative;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    padding: 10px 8px; border-radius: 10px; border: 1.5px solid #CBD5E1;
    background: #fff; cursor: pointer; transition: all .15s; user-select: none; text-align: center;
  }
  .approve-unit-card input[type="checkbox"] { position: absolute; opacity: 0; pointer-events: none; }
  .approve-unit-card:hover:not(.disabled) { border-color: var(--primary); background: #FFFBEB; }
  .approve-unit-card.selected {
    border-color: #059669; background: #ECFDF5;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.18);
  }
  .approve-unit-card.disabled {
    background: #F1F5F9; border-color: #E2E8F0; color: #94A3B8; cursor: not-allowed; opacity: 0.8;
  }
  .form-control {
    width: 100%; padding: 9px 12px; border-radius: 8px; border: 1px solid var(--border);
    font-family: inherit; font-size: 13px; outline: none;
  }
  .form-control:focus { border-color: var(--primary); }
</style>
</head>
<body>

@include('partials.sidebar')

<main class="main">
  <div class="topbar">
    <span class="topbar-title">Menu Khusus: Barang Lainnya</span>
    <div class="topbar-right">
      <span class="date-text">{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
    </div>
  </div>

  <div class="content">

    @if(session('success'))
      <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
      </div>
    @endif

    @if($errors->any())
      <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle"></i>
        <span>{{ $errors->first() }}</span>
      </div>
    @endif

    <div class="page-top">
      <div>
        <h1>⚡ Barang Lainnya</h1>
        <p>Pengelolaan khusus peralatan listrik, steker, colokan, kabel roll, baterai, dan barang pendukung operasional.</p>
      </div>
      <div>
        <button type="button" onclick="openModal('modalTambahLainnya')" class="btn-tambah">
          <i class="fas fa-plus"></i> Tambah Barang Lainnya
        </button>
      </div>
    </div>

    <!-- TAB SWITCHER -->
    <div style="display:flex; gap:10px; margin-bottom:18px;">
      <button type="button" class="tab-btn active" id="tabBtnStok" onclick="switchMainTab('stok')" style="padding:10px 20px; border-radius:10px; font-weight:700; font-size:13.5px; border:none; cursor:pointer; background:var(--primary); color:#fff; display:flex; align-items:center; gap:8px; transition:all .2s;">
        <i class="fas fa-boxes"></i> <span>Data Stok Barang Lainnya</span>
      </button>
      <button type="button" class="tab-btn" id="tabBtnPinjam" onclick="switchMainTab('pinjam')" style="padding:10px 20px; border-radius:10px; font-weight:700; font-size:13.5px; border:1px solid var(--border); cursor:pointer; background:#fff; color:var(--muted); display:flex; align-items:center; gap:8px; transition:all .2s;">
        <i class="fas fa-hand-holding"></i> <span>Permohonan Peminjaman Pegawai</span>
        @if(($pendingPeminjamanCount ?? 0) > 0)
          <span style="background:#ef4444; color:#fff; padding:2px 8px; border-radius:10px; font-size:11px; font-weight:800;">{{ $pendingPeminjamanCount }}</span>
        @endif
      </button>
    </div>

    <!-- TABLE STOK BARANG LAINNYA -->
    <div id="paneStok" class="table-card">
      <div class="table-toolbar">
        <form method="GET" action="{{ route('adminpersediaan.barang-lainnya') }}" class="search-wrap">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="#94A3B8">
            <path d="M15.5 14h-.79l-.28-.27A6.47 6.47 0 0016 9.5 6.5 6.5 0 109.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
          </svg>
          <input type="text" name="search" placeholder="Cari nama barang, steker, kabel roll, baterai..." value="{{ request('search') }}">
        </form>
      </div>

      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Kode Unit Fisik</th>
            <th>Tanggal Masuk</th>
            <th>Satuan</th>
            <th>Stok Tersedia</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($barangLainnya as $idx => $item)
            @php
              $nama = strtoupper($item->nama_barang);
              $icon = '⚡';
              if (str_contains($nama, 'STEKER') || str_contains($nama, 'COLOKAN') || str_contains($nama, 'PLUG')) $icon = '🔌';
              elseif (str_contains($nama, 'KABEL') || str_contains($nama, 'ROLL')) $icon = '🌀';
              elseif (str_contains($nama, 'BATERAI')) $icon = '🔋';
              elseif (str_contains($nama, 'LAMPU')) $icon = '💡';
              elseif (str_contains($nama, 'ISOLASI') || str_contains($nama, 'LAKBAN')) $icon = '🩹';
              $itemUnits = $unitsByBarangId[$item->id] ?? [];
              $dipinjamCount = collect($itemUnits)->where('status', 'dipinjam')->count();
            @endphp
            <tr>
              <td><strong>{{ $barangLainnya->firstItem() + $idx }}</strong></td>
              <td><span class="badge-kode">{{ $item->kode_barang ?? ($item->kode_unik_barang ?? '-') }}</span></td>
              <td>
                <span style="font-size:15px; margin-right:4px;">{{ $icon }}</span>
                <strong>{{ $item->nama_barang }}</strong>
              </td>
              <td style="white-space: normal;">
                <div class="mini-unit-wrap" onclick='openDetail(@json($item))' title="Klik untuk kelola atau edit manual Kode Unit">
                  @forelse(array_slice($itemUnits, 0, 6) as $u)
                    <span class="mini-unit {{ $u['status'] }}"
                          title="Kode {{ $u['kode_unit'] }} • {{ $u['status'] === 'dipinjam' ? 'Dipinjam oleh ' . ($u['peminjam_nama'] ?? 'Pegawai') : ucfirst($u['status']) }}">
                      {{ $u['kode_unit'] }}
                    </span>
                  @empty
                    <span style="font-size:11.5px; color:var(--muted);">-</span>
                  @endforelse
                  @if(count($itemUnits) > 6)
                    <span class="mini-unit" style="background:#F1F5F9; color:#475569; border-color:#CBD5E1;">+{{ count($itemUnits) - 6 }}</span>
                  @endif
                </div>
                @if($dipinjamCount > 0)
                  <div style="font-size:11px; color:#b45309; font-weight:600; margin-top:3px;">
                    <i class="fas fa-hand-holding"></i> {{ $dipinjamCount }} unit sedang dipinjam
                  </div>
                @endif
              </td>
              <td>{{ $item->tanggal_masuk?->format('d/m/Y') ?? '-' }}</td>
              <td><span style="font-weight:600; text-transform:capitalize;">{{ $item->satuan }}</span></td>
              <td>
                <span class="badge-stok {{ $item->jumlah > 5 ? 'badge-stok-aman' : 'badge-stok-kritis' }}">
                  {{ number_format($item->jumlah) }} {{ $item->satuan }}
                </span>
              </td>
              <td>
                <div class="action-btns">
                  <button type="button" class="btn-action" style="width:auto; padding:0 10px; gap:5px; font-size:11.5px; font-weight:700; color:#b45309; background:#FFFBEB; border-color:#FDE68A;" title="Detail & Kelola Kode Unit" onclick='openDetail(@json($item))'>
                    <i class="fas fa-tags"></i> Unit
                  </button>
                  <button type="button" class="btn-action" title="Edit" onclick='openEdit(@json($item))'>
                    <i class="fas fa-edit"></i>
                  </button>
                  <button type="button" class="btn-action danger" title="Hapus" onclick="openDelete({{ $item->id }}, '{{ addslashes($item->nama_barang) }}')">
                    <i class="fas fa-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" style="text-align: center; padding: 40px; color: var(--muted);">
                <div style="font-size: 32px; margin-bottom: 8px;">⚡</div>
                <strong>Belum ada barang di kategori Barang Lainnya</strong>
                <p style="font-size: 12.5px; margin-top: 4px;">Klik tombol "Tambah Barang Lainnya" untuk mendaftarkan colokan, steker, atau perlengkapan kantor lainnya.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>

      <div style="padding: 16px 20px; border-top: 1px solid var(--border);">
        {{ $barangLainnya->links() }}
      </div>
    </div>

    <!-- PANE 2: TABLE PEMINJAMAN PEGAWAI -->
    <div id="panePinjam" class="table-card" style="display:none;">
      <div style="padding: 16px 20px; border-bottom: 1px solid var(--border); background: #FFFBEB; display: flex; align-items: center; justify-content: space-between;">
        <div style="font-weight: 700; color: #92400E; font-size: 14px; display: flex; align-items: center; gap: 8px;">
          <i class="fas fa-hand-holding"></i> Daftar Pengajuan Peminjaman Barang Lainnya oleh Pegawai
        </div>
        <span style="font-size: 12px; color: var(--muted);">Total: {{ $peminjamanList instanceof \Illuminate\Pagination\LengthAwarePaginator ? $peminjamanList->total() : count($peminjamanList) }} Pengajuan</span>
      </div>

      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Pegawai</th>
            <th>Nama Barang & Kode Unit</th>
            <th>Jumlah</th>
            <th>Tgl Pinjam</th>
            <th>Est. Kembali</th>
            <th>Keperluan</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($peminjamanList as $pIdx => $p)
            @php
              $approvePayload = [
                'id'                => $p->id,
                'barang_lainnya_id' => $p->barang_lainnya_id,
                'nama_barang'       => $p->nama_barang,
                'kode_barang'       => $p->kode_barang,
                'jumlah'            => $p->jumlah,
                'satuan'            => $p->satuan,
                'unit_ids'          => is_array($p->unit_ids) ? $p->unit_ids : [],
                'kode_unit'         => $p->kode_unit,
                'keperluan'         => $p->keperluan,
                'pemohon'           => $p->user?->name ?? 'Pegawai',
              ];
            @endphp
            <tr>
              <td><strong>{{ ($peminjamanList instanceof \Illuminate\Pagination\LengthAwarePaginator ? $peminjamanList->firstItem() : 1) + $pIdx }}</strong></td>
              <td>
                <div style="font-weight: 700;">{{ $p->user?->name ?? 'Pegawai' }}</div>
                <div style="font-size: 11px; color: var(--muted);">{{ $p->user?->email ?? '-' }}</div>
              </td>
              <td>
                <strong style="color: #b45309;">⚡ {{ $p->nama_barang }}</strong>
                <div style="font-size: 11px; color: var(--muted); display:flex; align-items:center; gap:6px; margin-top:3px; flex-wrap:wrap;">
                  <span>Kode: {{ $p->kode_barang ?? '-' }}</span>
                  @if(!empty($p->kode_unit))
                    <span style="background: {{ $p->status === 'pending' ? '#FEF3C7' : '#ECFDF5' }}; color: {{ $p->status === 'pending' ? '#B45309' : '#059669' }}; border: 1px solid {{ $p->status === 'pending' ? '#FDE68A' : '#A7F3D0' }}; padding: 1px 7px; border-radius: 6px; font-weight: 800;">
                      🏷️ {{ $p->status === 'pending' ? 'Pilihan: Kode ' . $p->kode_unit : 'Kode Unit: ' . $p->kode_unit }}
                    </span>
                  @endif
                </div>
              </td>
              <td><strong>{{ $p->jumlah }}</strong> {{ $p->satuan }}</td>
              <td>{{ $p->tanggal_pinjam?->format('d/m/Y') ?? '-' }}</td>
              <td>{{ $p->tanggal_kembali?->format('d/m/Y') ?? '-' }}</td>
              <td><span title="{{ $p->keperluan }}">{{ Str::limit($p->keperluan, 30) }}</span></td>
              <td>
                @if($p->status === 'pending')
                  <span class="badge-stok" style="background:#fef3c7; color:#b45309;"><i class="fas fa-clock"></i> Pending</span>
                @elseif($p->status === 'disetujui')
                  <span class="badge-stok" style="background:#ecfdf5; color:#059669;"><i class="fas fa-check"></i> Dipinjam</span>
                @elseif($p->status === 'proses_pengembalian')
                  <span class="badge-stok" style="background:#fff7ed; color:#c2410c;"><i class="fas fa-hourglass-half"></i> Verifikasi Kembali</span>
                @elseif($p->status === 'dikembalikan')
                  <span class="badge-stok" style="background:#eff6ff; color:#2563eb;"><i class="fas fa-undo"></i> Dikembalikan</span>
                @elseif($p->status === 'ditolak')
                  <span class="badge-stok" style="background:#fef2f2; color:#dc2626;"><i class="fas fa-times"></i> Ditolak</span>
                @else
                  <span class="badge-stok" style="background:#f1f5f9; color:#64748b;">{{ ucfirst($p->status) }}</span>
                @endif
              </td>
              <td>
                <div style="display: flex; gap: 6px;">
                  @if($p->status === 'pending')
                    <button type="button"
                            class="btn-action"
                            style="background: #ecfdf5; color: #059669; border-color: #a7f3d0; width: auto; padding: 0 10px; gap: 5px; font-size: 11.5px; font-weight: 700;"
                            title="Verifikasi Kode Unit & Setujui Peminjaman"
                            onclick='openApprovePinjam(@json($approvePayload))'>
                      <i class="fas fa-check"></i> Setujui
                    </button>
                    <button type="button" class="btn-action" style="background: #fef2f2; color: #dc2626; border-color: #fecaca;" title="Tolak Peminjaman" onclick="openRejectPinjam({{ $p->id }}, '{{ addslashes($p->nama_barang) }}', '{{ addslashes($p->user?->name ?? 'Pegawai') }}')">
                      <i class="fas fa-times"></i>
                    </button>
                  @elseif($p->status === 'disetujui')
                    <form method="POST" action="{{ route('adminpersediaan.barang-lainnya.peminjaman.return', $p->id) }}" onsubmit="return confirm('Tandai barang {{ addslashes($p->nama_barang) }}{{ $p->kode_unit ? ' (Kode Unit: ' . addslashes($p->kode_unit) . ')' : '' }} telah selesai dikembalikan ke stok?')">
                      @csrf
                      <button type="submit" class="btn-action" style="background: #eff6ff; color: #2563eb; border-color: #bfdbfe; font-size: 11px; padding: 4px 8px; width: auto; gap: 4px;" title="Tandai Dikembalikan">
                        <i class="fas fa-undo"></i> Kembali
                      </button>
                    </form>
                  @else
                    <span style="font-size: 12px; color: var(--muted);">-</span>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" style="text-align: center; padding: 40px; color: var(--muted);">
                <div style="font-size: 32px; margin-bottom: 8px;">📋</div>
                <strong>Belum ada permohonan peminjaman dari pegawai</strong>
                <p style="font-size: 12.5px; margin-top: 4px;">Ketika pegawai mengajukan pinjam barang lainnya (steker, kabel roll), data akan muncul di sini untuk Anda setujui.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>

      @if($peminjamanList instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div style="padding: 16px 20px; border-top: 1px solid var(--border);">
          {{ $peminjamanList->links() }}
        </div>
      @endif
    </div>

  </div>
</main>

{{-- MODAL TAMBAH BARANG LAINNYA --}}
<div id="modalTambahLainnya" class="modal-overlay">
  <div class="modal">
    <div style="padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
      <div style="display: flex; align-items: center; gap: 10px;">
        <div style="width: 38px; height: 38px; border-radius: 10px; background: #FEF3C7; color: #D97706; display: flex; align-items: center; justify-content: center; font-size: 18px;">
          ⚡
        </div>
        <div>
          <div style="font-size: 16px; font-weight: 800;">Tambah Barang Lainnya</div>
          <div style="font-size: 12px; color: var(--muted);">Input data barang pendukung operasional baru</div>
        </div>
      </div>
      <button type="button" onclick="closeModal('modalTambahLainnya')" class="btn-action">✕</button>
    </div>

    <form method="POST" action="{{ route('adminpersediaan.barang-lainnya.store') }}">
      @csrf
      <div style="padding: 20px 24px; max-height: 70vh; overflow-y: auto;">

        <div class="form-group">
          <label class="form-label">Nama Barang <span style="color:var(--danger)">*</span></label>
          <input type="text" name="nama_barang" id="inputNamaLainnya" class="form-input" placeholder="Contoh: STOPKONTAK KABEL ROLL 10 METER" required>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Satuan <span style="color:var(--danger)">*</span></label>
            <select name="satuan" id="inputSatuanLainnya" class="form-select" required>
              <option value="buah">Buah</option>
              <option value="unit">Unit</option>
              <option value="roll">Roll</option>
              <option value="pasang">Pasang</option>
              <option value="dos">Dos</option>
              <option value="set">Set</option>
              <option value="box">Box</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Jumlah Stok Masuk <span style="color:var(--danger)">*</span></label>
            <input type="number" name="jumlah" id="inputJumlahLainnya" class="form-input" min="1" value="5" required>
            <small style="color:#92400E; font-size:11px; display:block; margin-top:4px;">
              🏷️ Otomatis membuat Kode Unit huruf berurutan (A, B, C, dst.) yang dapat diedit manual.
            </small>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Tanggal Masuk <span style="color:var(--danger)">*</span></label>
          <input type="date" name="tanggal_masuk" class="form-input" value="{{ date('Y-m-d') }}" required>
        </div>

      </div>

      <div style="padding: 16px 24px; border-top: 1px solid var(--border); background: #FAFBFF; display: flex; justify-content: flex-end; gap: 10px;">
        <button type="button" onclick="closeModal('modalTambahLainnya')" class="btn btn-secondary">Batal</button>
        <button type="submit" class="btn btn-primary" style="background:linear-gradient(135deg,#f59e0b,#d97706);">Simpan Barang</button>
      </div>
    </form>
  </div>
</div>

{{-- MODAL DETAIL & KELOLA KODE UNIT --}}
<div id="modalDetail" class="modal-overlay">
  <div class="modal" style="max-width: 720px;">
    <div style="padding: 18px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; background:#FFFBEB;">
      <div style="display:flex; align-items:center; gap:10px;">
        <div style="width:38px; height:38px; border-radius:10px; background:#FEF3C7; color:#D97706; display:flex; align-items:center; justify-content:center; font-size:17px;">
          <i class="fas fa-tags"></i>
        </div>
        <div>
          <h3 style="font-size: 16px; font-weight: 800; color:#92400E;">Detail & Kelola Kode Unit Barang</h3>
          <div style="font-size: 12px; color: var(--muted);">Kode unit dibuat otomatis (A, B, C...) dan dapat Anda ubah secara manual</div>
        </div>
      </div>
      <button type="button" onclick="closeModal('modalDetail')" class="btn-action">✕</button>
    </div>
    <div style="padding: 20px 24px; font-size: 13.5px; max-height: 74vh; overflow-y: auto;" id="detailBody">
      <!-- Injected by JS -->
    </div>
    <div style="padding: 14px 24px; border-top: 1px solid var(--border); background: #FAFBFF; display: flex; justify-content: flex-end;">
      <button type="button" onclick="closeModal('modalDetail')" class="btn btn-secondary">Tutup</button>
    </div>
  </div>
</div>

{{-- MODAL EDIT BARANG LAINNYA --}}
<div id="modalEditLainnya" class="modal-overlay">
  <div class="modal">
    <div style="padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
      <div style="display: flex; align-items: center; gap: 10px;">
        <div style="width: 38px; height: 38px; border-radius: 10px; background: #FEF3C7; color: #D97706; display: flex; align-items: center; justify-content: center; font-size: 18px;">
          ✏️
        </div>
        <div>
          <div style="font-size: 16px; font-weight: 800;">Edit Barang Lainnya</div>
          <div style="font-size: 12px; color: var(--muted);">Perbarui informasi stok dan harga barang</div>
        </div>
      </div>
      <button type="button" onclick="closeModal('modalEditLainnya')" class="btn-action">✕</button>
    </div>

    <form id="formEditLainnya" method="POST">
      @csrf
      @method('PUT')
      <div style="padding: 20px 24px; max-height: 70vh; overflow-y: auto;">
        
        <div class="form-group">
          <label class="form-label">Nama Barang <span style="color:var(--danger)">*</span></label>
          <input type="text" name="nama_barang" id="editNamaLainnya" class="form-input" required>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Satuan <span style="color:var(--danger)">*</span></label>
            <select name="satuan" id="editSatuanLainnya" class="form-select" required>
              <option value="buah">Buah</option>
              <option value="unit">Unit</option>
              <option value="roll">Roll</option>
              <option value="pasang">Pasang</option>
              <option value="dos">Dos</option>
              <option value="set">Set</option>
              <option value="box">Box</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Jumlah Stok <span style="color:var(--danger)">*</span></label>
            <input type="number" name="jumlah" id="editJumlahLainnya" class="form-input" min="0" required>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Tanggal Masuk <span style="color:var(--danger)">*</span></label>
          <input type="date" name="tanggal_masuk" id="editTanggalLainnya" class="form-input" required>
        </div>

      </div>

      <div style="padding: 16px 24px; border-top: 1px solid var(--border); background: #FAFBFF; display: flex; justify-content: flex-end; gap: 10px;">
        <button type="button" onclick="closeModal('modalEditLainnya')" class="btn btn-secondary">Batal</button>
        <button type="submit" class="btn btn-primary" style="background:linear-gradient(135deg,#f59e0b,#d97706);">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

{{-- MODAL DELETE --}}
<div id="modalDelete" class="modal-overlay">
  <div class="modal" style="max-width: 440px;">
    <div style="padding: 24px; text-align: center;">
      <div style="width: 56px; height: 56px; border-radius: 16px; background: #FEF2F2; color: #DC2626; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 24px;">
        <i class="fas fa-trash"></i>
      </div>
      <h3 style="font-size: 18px; font-weight: 800; margin-bottom: 6px;">Hapus Barang?</h3>
      <p style="font-size: 13px; color: var(--muted); margin-bottom: 14px;">Barang <strong id="deleteNamaBarang">-</strong> akan dihapus permanen dari daftar Barang Lainnya.</p>
      <form id="formDelete" method="POST">
        @csrf
        @method('DELETE')
        <div style="display: flex; gap: 10px; justify-content: center;">
          <button type="button" onclick="closeModal('modalDelete')" class="btn btn-secondary">Batal</button>
          <button type="submit" class="btn btn-primary" style="background: #DC2626;">Ya, Hapus</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- MODAL TOLAK PEMINJAMAN --}}
<div id="modalRejectPinjam" class="modal-overlay">
  <div class="modal" style="max-width: 440px;">
    <div style="padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
      <div style="display: flex; align-items: center; gap: 10px;">
        <div style="width: 38px; height: 38px; border-radius: 10px; background: #FEF2F2; color: #DC2626; display: flex; align-items: center; justify-content: center; font-size: 18px;">
          ✕
        </div>
        <div>
          <div style="font-size: 16px; font-weight: 800; color: #DC2626;">Tolak Peminjaman</div>
          <div style="font-size: 12px; color: var(--muted);" id="rejectSubTitle">Berikan alasan penolakan</div>
        </div>
      </div>
      <button type="button" onclick="closeModal('modalRejectPinjam')" class="btn-action">✕</button>
    </div>

    <form method="POST" id="formRejectPinjam" action="">
      @csrf
      <div style="padding: 20px 24px;">
        <p style="font-size: 13.5px; color: var(--text); margin-bottom: 14px;">
          Anda akan menolak pengajuan peminjaman barang <strong id="rejectNamaBarang">-</strong> oleh <strong id="rejectNamaPegawai">-</strong>.
        </p>

        <div class="form-group">
          <label class="form-label">Alasan Penolakan <span style="color:red;">*</span></label>
          <textarea name="catatan_admin" class="form-control" rows="3" placeholder="Contoh: Stok sedang dialokasikan untuk kegiatan kantor lain..." required></textarea>
        </div>
      </div>

      <div style="padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px; background: #F8FAFC; border-radius: 0 0 16px 16px;">
        <button type="button" onclick="closeModal('modalRejectPinjam')" class="btn btn-secondary">Batal</button>
        <button type="submit" class="btn btn-primary" style="background: #dc2626;">Tolak Peminjaman</button>
      </div>
    </form>
  </div>
</div>

{{-- MODAL SETUJUI & KONFIRMASI KODE UNIT (OPSI 3 HYBRID) --}}
<div id="modalApprovePinjam" class="modal-overlay">
  <div class="modal" style="max-width: 620px;">
    <div style="padding: 18px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; background: #ECFDF5;">
      <div style="display: flex; align-items: center; gap: 10px;">
        <div style="width: 38px; height: 38px; border-radius: 10px; background: #D1FAE5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 18px;">
          <i class="fas fa-check-circle"></i>
        </div>
        <div>
          <div style="font-size: 16px; font-weight: 800; color: #065F46;">Setujui & Verifikasi Kode Unit</div>
          <div style="font-size: 12px; color: #047857;">Konfirmasi kode unit pilihan pegawai atau sesuaikan dengan fisik di gudang</div>
        </div>
      </div>
      <button type="button" onclick="closeModal('modalApprovePinjam')" class="btn-action">✕</button>
    </div>

    <form method="POST" id="formApprovePinjam" action="" onsubmit="return validateApproveUnits()">
      @csrf
      <div style="padding: 20px 24px; max-height: 72vh; overflow-y: auto;">
        <div style="background: #F8FAFC; border: 1px solid var(--border); border-radius: 12px; padding: 14px; margin-bottom: 16px;">
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 13px;">
            <div>
              <span style="color: var(--muted); font-size: 11px; text-transform: uppercase; font-weight: 700;">Pemohon</span>
              <div style="font-weight: 800; color: var(--text);" id="appPemohon">-</div>
            </div>
            <div>
              <span style="color: var(--muted); font-size: 11px; text-transform: uppercase; font-weight: 700;">Barang & Jumlah Diminta</span>
              <div style="font-weight: 800; color: #b45309;" id="appBarang">-</div>
            </div>
            <div>
              <span style="color: var(--muted); font-size: 11px; text-transform: uppercase; font-weight: 700;">Kode Unit Pilihan Pegawai</span>
              <div style="margin-top: 2px;" id="appPilihanPegawai">-</div>
            </div>
            <div>
              <span style="color: var(--muted); font-size: 11px; text-transform: uppercase; font-weight: 700;">Keperluan</span>
              <div style="font-size: 12.5px; color: var(--text);" id="appKeperluan">-</div>
            </div>
          </div>
        </div>

        <div id="approveConflictAlert" style="display: none; background: #FEF3C7; border: 1px solid #FDE68A; color: #92400E; padding: 10px 14px; border-radius: 10px; font-size: 12.5px; font-weight: 600; margin-bottom: 14px;">
          <i class="fas fa-exclamation-triangle"></i> Sebagian kode unit pilihan pegawai sedang tidak tersedia. Silakan pilih kode unit lain yang berstatus Tersedia di bawah ini.
        </div>

        <div class="form-group">
          <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
            <label class="form-label" style="margin-bottom: 0;">Pilih / Pastikan Kode Unit yang Diserahkan <span style="color: var(--danger);">*</span></label>
            <span id="approveSelectedSummary" style="font-size: 11.5px; font-weight: 800; background: #ECFDF5; color: #059669; border: 1px solid #A7F3D0; padding: 2px 10px; border-radius: 99px;">0 unit dipilih</span>
          </div>
          <div class="approve-unit-grid" id="approveUnitGrid"></div>
        </div>

        <div class="form-group" style="margin-bottom: 0;">
          <label class="form-label">Catatan Admin <span style="color: var(--muted); font-weight: 400;">(Opsional, misal jika kode unit diganti)</span></label>
          <input type="text" name="catatan_admin" class="form-input" placeholder="Contoh: Diserahkan Kode C karena Kode B sedang dipakai di ruang rapat...">
        </div>
      </div>

      <div style="padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px; background: #F8FAFC;">
        <button type="button" onclick="closeModal('modalApprovePinjam')" class="btn btn-secondary">Batal</button>
        <button type="submit" class="btn btn-primary" style="background: #059669;"><i class="fas fa-check"></i> Setujui & Serahkan Unit</button>
      </div>
    </form>
  </div>
</div>

<script>
const UNITS_BY_BARANG = @json($unitsByBarangId ?? []);
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

function escHtml(str) {
  return String(str ?? '').replace(/[&<>"']/g, s => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
  }[s]));
}

function openModal(id) {
  document.getElementById(id).classList.add('show');
}
function closeModal(id) {
  if (id) {
    document.getElementById(id).classList.remove('show');
  } else {
    document.querySelectorAll('.modal-overlay.show').forEach(m => m.classList.remove('show'));
  }
}

document.querySelectorAll('.modal-overlay').forEach(m => {
  m.addEventListener('click', e => { if (e.target === m) closeModal(); });
});

function formatRupiah(num) {
  return new Intl.NumberFormat('id-ID').format(num || 0);
}

let currentDetailItem = null;
let currentUnitSortAsc = true;

function sortUnitsNatural(list, asc = true) {
  return [...list].sort((a, b) => {
    const codeA = String(a.kode_unit ?? '').trim();
    const codeB = String(b.kode_unit ?? '').trim();
    const res = codeA.localeCompare(codeB, undefined, {
      numeric: true,
      sensitivity: 'base'
    });
    return asc ? res : -res;
  });
}

function toggleSortKodeUnit() {
  currentUnitSortAsc = !currentUnitSortAsc;
  if (currentDetailItem) {
    renderDetailModal(currentDetailItem, false);
  }
}

function openDetail(item) {
  currentDetailItem = item;
  currentUnitSortAsc = true;
  renderDetailModal(item, true);
}

function renderDetailModal(item, showModal = true) {
  const body = document.getElementById('detailBody');
  const rawUnits = UNITS_BY_BARANG[item.id] || [];

  // Selalu urutkan unit secara alami sesuai kode unit (1, 2, 3 ... 9, 10 / A, B, C...)
  const units = sortUnitsNatural(rawUnits, currentUnitSortAsc);
  const tersediaCount = rawUnits.filter(u => u.status === 'tersedia').length;
  const dipinjamCount = rawUnits.filter(u => u.status === 'dipinjam').length;

  const rowsHtml = units.length ? units.map((u, idx) => {
    const isBorrowed = u.status === 'dipinjam';
    let statusControl = '';

    if (isBorrowed) {
      statusControl = `
        <span style="display:inline-flex; align-items:center; gap:5px; padding:5px 10px; border-radius:8px; background:#FEF3C7; color:#B45309; font-size:11.5px; font-weight:700;">
          <i class="fas fa-hand-holding"></i> Dipinjam: ${escHtml(u.peminjam_nama || 'Pegawai')}
        </span>
      `;
    } else {
      statusControl = `
        <select name="status" class="form-select" style="padding:6px 8px; font-size:12px;">
          <option value="tersedia" ${u.status === 'tersedia' ? 'selected' : ''}>🟢 Tersedia</option>
          <option value="rusak" ${u.status === 'rusak' ? 'selected' : ''}>🟠 Rusak</option>
          <option value="hilang" ${u.status === 'hilang' ? 'selected' : ''}>🔴 Hilang</option>
        </select>
      `;
    }

    return `
      <tr>
        <td style="padding:8px 10px; font-size:12px; color:#64748B; font-weight:700; text-align:center;">${idx + 1}</td>
        <td style="padding:8px 10px;" colspan="3">
          <form method="POST" action="/adminpersediaan/barang-lainnya/unit/${u.id}" style="display:grid; grid-template-columns: 115px 165px 1fr auto; gap:8px; align-items:center;">
            <input type="hidden" name="_token" value="${escHtml(CSRF_TOKEN)}">
            <input type="hidden" name="_method" value="PUT">
            <input type="text" name="kode_unit" value="${escHtml(u.kode_unit)}" class="form-input" style="padding:6px 10px; font-weight:800; font-size:12.5px; background:#FEFCE8; border-color:#FDE047;" required title="Edit manual Kode Unit">
            <div>${statusControl}</div>
            <input type="text" name="keterangan" value="${escHtml(u.keterangan || '')}" class="form-input" style="padding:6px 10px; font-size:12px;" placeholder="Keterangan unit (opsional)">
            <div style="display:flex; gap:5px;">
              <button type="submit" class="btn btn-primary" style="padding:6px 11px; font-size:11.5px;" title="Simpan perubahan kode/status unit">
                <i class="fas fa-save"></i> Simpan
              </button>
              ${!isBorrowed ? `
                <button type="button" class="btn btn-secondary" style="padding:6px 9px; font-size:11.5px; color:#DC2626; border-color:#FECACA; background:#FEF2F2;" title="Hapus kode unit ini" onclick="hapusKodeUnit(${u.id}, '${escHtml(u.kode_unit)}')">
                  <i class="fas fa-trash"></i>
                </button>
              ` : ''}
            </div>
          </form>
        </td>
      </tr>
    `;
  }).join('') : `
    <tr>
      <td colspan="4" style="text-align:center; padding:20px; color:#64748B;">Belum ada kode unit pada barang ini.</td>
    </tr>
  `;

  body.innerHTML = `
    <div style="display:flex; flex-direction:column; gap:16px;">
      <div style="display:grid; grid-template-columns: repeat(4, 1fr); gap:10px; background:#F8FAFC; padding:12px 14px; border-radius:12px; border:1px solid #E2E8F0;">
        <div>
          <span style="color:#64748b; font-size:11px; font-weight:700; text-transform:uppercase;">Kode Barang</span>
          <div style="font-weight:800; margin-top:2px;">${escHtml(item.kode_barang || item.kode_unik_barang || '-')}</div>
        </div>
        <div>
          <span style="color:#64748b; font-size:11px; font-weight:700; text-transform:uppercase;">Nama Barang</span>
          <div style="font-weight:800; color:#d97706; margin-top:2px;">${escHtml(item.nama_barang)}</div>
        </div>
        <div>
          <span style="color:#64748b; font-size:11px; font-weight:700; text-transform:uppercase;">Tersedia</span>
          <div style="font-weight:800; color:#059669; margin-top:2px;">${tersediaCount} ${escHtml(item.satuan || 'unit')}</div>
        </div>
        <div>
          <span style="color:#64748b; font-size:11px; font-weight:700; text-transform:uppercase;">Sedang Dipinjam</span>
          <div style="font-weight:800; color:#b45309; margin-top:2px;">${dipinjamCount} ${escHtml(item.satuan || 'unit')}</div>
        </div>
      </div>

      <!-- Form Tambah 1 Kode Unit Baru -->
      <form method="POST" action="/adminpersediaan/barang-lainnya/${item.id}/unit" style="background:#FFFBEB; border:1px dashed #FCD34D; border-radius:10px; padding:10px 12px; display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
        <input type="hidden" name="_token" value="${escHtml(CSRF_TOKEN)}">
        <span style="font-size:12px; font-weight:800; color:#92400E;"><i class="fas fa-plus-circle"></i> Tambah Unit:</span>
        <input type="text" name="kode_unit" class="form-input" style="width:185px; padding:6px 10px; font-size:12px;" placeholder="Kosongkan = Huruf Otomatis">
        <input type="text" name="keterangan" class="form-input" style="flex:1; min-width:160px; padding:6px 10px; font-size:12px;" placeholder="Catatan unit (opsional)">
        <button type="submit" class="btn btn-primary" style="padding:7px 14px; font-size:12px;">+ Tambah Kode Unit</button>
      </form>

      <!-- Baris Header Indikator Urutan Kode Unit -->
      <div style="display:flex; justify-content:space-between; align-items:center; margin-top:2px;">
        <div style="font-size:12px; font-weight:700; color:#334155; display:flex; align-items:center; gap:6px;">
          <i class="fas fa-list-ol" style="color:#D97706;"></i>
          <span>Daftar Unit Fisik (${units.length} unit)</span>
        </div>
        <div style="display:flex; align-items:center; gap:6px;">
          <span style="font-size:11px; color:#059669; background:#ECFDF5; border:1px solid #A7F3D0; padding:2px 8px; border-radius:99px; font-weight:700;">
            <i class="fas fa-check-circle"></i> Berurutan sesuai Kode Unit (${currentUnitSortAsc ? 'Menaik: 1, 2, 3...' : 'Menurun'})
          </span>
          <button type="button" onclick="toggleSortKodeUnit()" class="btn btn-secondary" style="padding:3px 8px; font-size:11px; font-weight:700; display:inline-flex; align-items:center; gap:4px;" title="Balik urutan Kode Unit">
            <i class="fas fa-sort"></i> Balik Urutan
          </button>
        </div>
      </div>

      <!-- Tabel Daftar Kode Unit -->
      <div style="border:1px solid #E2E8F0; border-radius:12px; overflow:hidden;">
        <table style="width:100%; border-collapse:collapse;">
          <thead>
            <tr style="background:#F8FAFC;">
              <th style="padding:9px 10px; width:45px; text-align:center;">No</th>
              <th style="padding:9px 10px; width:130px; cursor:pointer; user-select:none;" onclick="toggleSortKodeUnit()" title="Klik untuk membalik urutan Kode Unit">
                <div style="display:inline-flex; align-items:center; gap:5px; color:#92400E; font-weight:800;">
                  <span>Kode Unit</span>
                  <i class="fas ${currentUnitSortAsc ? 'fa-sort-numeric-down' : 'fa-sort-numeric-up'}" style="font-size:12px; color:#D97706;"></i>
                </div>
              </th>
              <th style="padding:9px 10px; width:170px;">Status Unit</th>
              <th style="padding:9px 10px;">Keterangan & Aksi</th>
            </tr>
          </thead>
          <tbody>
            ${rowsHtml}
          </tbody>
        </table>
      </div>
    </div>

    <form id="hiddenDeleteUnitForm" method="POST" style="display:none;">
      <input type="hidden" name="_token" value="${escHtml(CSRF_TOKEN)}">
      <input type="hidden" name="_method" value="DELETE">
    </form>
  `;

  if (showModal) {
    openModal('modalDetail');
  }
}

function hapusKodeUnit(unitId, kodeUnit) {
  if (!confirm(`Hapus Kode Unit '${kodeUnit}' dari daftar barang ini?`)) return;
  const form = document.getElementById('hiddenDeleteUnitForm');
  if (form) {
    form.action = `/adminpersediaan/barang-lainnya/unit/${unitId}`;
    form.submit();
  }
}

function openApprovePinjam(payload) {
  document.getElementById('formApprovePinjam').action = `/adminpersediaan/barang-lainnya/peminjaman/${payload.id}/approve`;
  document.getElementById('appPemohon').textContent = payload.pemohon || 'Pegawai';
  document.getElementById('appBarang').textContent = `${payload.nama_barang} (${payload.jumlah} ${payload.satuan || 'unit'})`;
  document.getElementById('appKeperluan').textContent = payload.keperluan || '-';

  const pilihanEl = document.getElementById('appPilihanPegawai');
  if (payload.kode_unit) {
    pilihanEl.innerHTML = `<span style="background:#FEF3C7; color:#B45309; border:1px solid #FDE68A; padding:2px 9px; border-radius:6px; font-weight:800; font-size:12px;">🏷️ Kode ${escHtml(payload.kode_unit)}</span>`;
  } else {
    pilihanEl.innerHTML = `<span style="color:#64748B; font-size:12px;">Belum memilih kode spesifik</span>`;
  }

  const rawUnits = UNITS_BY_BARANG[payload.barang_lainnya_id] || [];
  const units = sortUnitsNatural(rawUnits, true);
  const requestedIds = (payload.unit_ids || []).map(Number);
  const grid = document.getElementById('approveUnitGrid');
  const conflictAlert = document.getElementById('approveConflictAlert');

  let hasConflict = false;
  requestedIds.forEach(reqId => {
    const found = units.find(u => Number(u.id) === reqId);
    if (!found || found.status !== 'tersedia') {
      hasConflict = true;
    }
  });

  // Jika pengajuan lama belum punya unit_ids, pre-select sejumlah payload.jumlah dari unit tersedia pertama
  let autoPickedIds = [];
  if (!requestedIds.length) {
    autoPickedIds = units.filter(u => u.status === 'tersedia').slice(0, Math.max(1, Number(payload.jumlah || 1))).map(u => Number(u.id));
  }

  conflictAlert.style.display = hasConflict ? 'block' : 'none';

  if (!units.length) {
    grid.innerHTML = '<div style="grid-column:1/-1; color:#DC2626; font-size:12.5px;">Tidak ada data unit untuk barang ini.</div>';
  } else {
    grid.innerHTML = units.map(u => {
      const uid = Number(u.id);
      const isAvailable = u.status === 'tersedia';
      const isRequestedByThis = requestedIds.includes(uid);
      const isPreselected = isAvailable && (isRequestedByThis || autoPickedIds.includes(uid));

      let badgeHtml = '';
      if (!isAvailable) {
        const label = u.status === 'dipinjam' ? `Dipinjam (${escHtml(u.peminjam_nama || 'Pegawai')})` : escHtml(u.status.toUpperCase());
        badgeHtml = `<span style="font-size:10px; background:#FEE2E2; color:#B91C1C; padding:1px 6px; border-radius:99px; font-weight:700; margin-top:3px;">${label}</span>`;
      } else if (isRequestedByThis) {
        badgeHtml = `<span style="font-size:10px; background:#FEF3C7; color:#B45309; padding:1px 6px; border-radius:99px; font-weight:800; margin-top:3px;">Pilihan Pegawai</span>`;
      } else {
        const otherPending = (u.pending_list || []).filter(p => Number(p.peminjaman_id) !== Number(payload.id));
        if (otherPending.length > 0) {
          badgeHtml = `<span style="font-size:10px; background:#FFEDD5; color:#C2410C; padding:1px 6px; border-radius:99px; font-weight:700; margin-top:3px;">Ada Pengajuan Lain</span>`;
        } else {
          badgeHtml = `<span style="font-size:10px; background:#DCFCE7; color:#15803D; padding:1px 6px; border-radius:99px; font-weight:700; margin-top:3px;">Tersedia</span>`;
        }
      }

      return `
        <label class="approve-unit-card ${isAvailable ? '' : 'disabled'} ${isPreselected ? 'selected' : ''}">
          <input type="checkbox"
                 name="unit_ids[]"
                 value="${uid}"
                 data-kode="${escHtml(u.kode_unit)}"
                 ${isAvailable ? '' : 'disabled'}
                 ${isPreselected ? 'checked' : ''}
                 onchange="onApproveUnitToggle(this)">
          <div style="font-size:13px; font-weight:800; color:${isAvailable ? '#0F172A' : '#94A3B8'}; display:flex; align-items:center; gap:5px;">
            <i class="fas ${isPreselected ? 'fa-check-circle' : (isAvailable ? 'fa-tag' : 'fa-lock')}" style="font-size:11px; color:${isPreselected ? '#059669' : 'inherit'};"></i>
            <span>Kode ${escHtml(u.kode_unit)}</span>
          </div>
          ${badgeHtml}
        </label>
      `;
    }).join('');
  }

  updateApproveSelectedSummary();
  openModal('modalApprovePinjam');
}

function onApproveUnitToggle(cb) {
  const card = cb.closest('.approve-unit-card');
  const icon = card ? card.querySelector('i') : null;
  if (cb.checked) {
    card.classList.add('selected');
    if (icon) {
      icon.className = 'fas fa-check-circle';
      icon.style.color = '#059669';
    }
  } else {
    card.classList.remove('selected');
    if (icon) {
      icon.className = 'fas fa-tag';
      icon.style.color = 'inherit';
    }
  }
  updateApproveSelectedSummary();
}

function updateApproveSelectedSummary() {
  const checked = Array.from(document.querySelectorAll('#approveUnitGrid input[type="checkbox"]:checked'));
  const el = document.getElementById('approveSelectedSummary');
  if (!checked.length) {
    el.textContent = 'Belum ada unit dipilih';
    el.style.background = '#FEF2F2';
    el.style.color = '#DC2626';
    el.style.borderColor = '#FECACA';
    return;
  }
  const codes = checked.map(c => c.dataset.kode);
  el.textContent = `Diserahkan (${codes.length}): Kode ${codes.join(', ')}`;
  el.style.background = '#ECFDF5';
  el.style.color = '#059669';
  el.style.borderColor = '#A7F3D0';
}

function validateApproveUnits() {
  const checked = document.querySelectorAll('#approveUnitGrid input[type="checkbox"]:checked');
  if (!checked.length) {
    alert('Silakan pilih minimal 1 Kode Unit yang akan diserahkan kepada pegawai!');
    return false;
  }
  return true;
}

function openEdit(item) {
  document.getElementById('formEditLainnya').action = `/adminpersediaan/barang-lainnya/${item.id}`;
  document.getElementById('editNamaLainnya').value = item.nama_barang;
  document.getElementById('editSatuanLainnya').value = (item.satuan || 'buah').toLowerCase();
  document.getElementById('editJumlahLainnya').value = item.jumlah;
  if (item.tanggal_masuk) {
    document.getElementById('editTanggalLainnya').value = item.tanggal_masuk.substring(0, 10);
  } else {
    document.getElementById('editTanggalLainnya').value = new Date().toISOString().substring(0, 10);
  }
  openModal('modalEditLainnya');
}

function openDelete(id, nama) {
  document.getElementById('deleteNamaBarang').innerText = nama;
  document.getElementById('formDelete').action = `/adminpersediaan/barang-lainnya/${id}`;
  openModal('modalDelete');
}

function openRejectPinjam(id, namaBarang, namaPegawai) {
  document.getElementById('formRejectPinjam').action = `/adminpersediaan/barang-lainnya/peminjaman/${id}/reject`;
  document.getElementById('rejectNamaBarang').innerText = namaBarang;
  document.getElementById('rejectNamaPegawai').innerText = namaPegawai;
  openModal('modalRejectPinjam');
}

function switchMainTab(tab) {
  const btnStok = document.getElementById('tabBtnStok');
  const btnPinjam = document.getElementById('tabBtnPinjam');
  const paneStok = document.getElementById('paneStok');
  const panePinjam = document.getElementById('panePinjam');

  if (tab === 'pinjam') {
    btnPinjam.style.background = 'var(--primary)';
    btnPinjam.style.color = '#fff';
    btnPinjam.style.border = 'none';

    btnStok.style.background = '#fff';
    btnStok.style.color = 'var(--muted)';
    btnStok.style.border = '1px solid var(--border)';

    paneStok.style.display = 'none';
    panePinjam.style.display = 'block';
  } else {
    btnStok.style.background = 'var(--primary)';
    btnStok.style.color = '#fff';
    btnStok.style.border = 'none';

    btnPinjam.style.background = '#fff';
    btnPinjam.style.color = 'var(--muted)';
    btnPinjam.style.border = '1px solid var(--border)';

    paneStok.style.display = 'block';
    panePinjam.style.display = 'none';
  }
}

// Cek jika halaman dibuka dengan peminjaman_page
if (window.location.search.includes('peminjaman_page') || window.location.search.includes('tab=pinjam')) {
  switchMainTab('pinjam');
}
</script>

</body>
</html>
