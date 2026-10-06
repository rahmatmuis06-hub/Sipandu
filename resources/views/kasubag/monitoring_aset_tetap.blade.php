<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIPANDU - Monitoring Barang & Aset Tetap</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
  :root {
    --primary: #4F46E5;
    --primary-light: #EEF2FF;
    --primary-dark: #3730A3;
    --danger: #EF4444;
    --danger-light: #FEF2F2;
    --emerald: #10B981;
    --emerald-light: #ECFDF5;
    --amber: #F59E0B;
    --amber-light: #FFFBEB;
    --blue: #3B82F6;
    --blue-light: #EFF6FF;
    --radius: 16px;
    --bg: #F8FAFC;
    --surface: #FFFFFF;
    --text: #0F172A;
    --text-muted: #64748B;
    --muted: #94A3B8;
    --border: #E2E8F0;
  }
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; }

  .main { margin-left: 256px; flex: 1; display: flex; flex-direction: column; min-height: 100vh; }

  .topbar {
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    padding: 0 32px;
    height: 60px;
    display: flex; align-items: center; justify-content: space-between;
    position: sticky; top: 0; z-index: 50;
  }
  .topbar-title { font-size: 16px; font-weight: 700; color: var(--text); display: flex; align-items: center; gap: 10px; }
  .date-text { font-size: 13px; color: var(--text-muted); font-weight: 500; }
  
  .content { padding: 28px 32px; flex: 1; }

  .page-top {
    display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;
  }
  .page-top h1 { font-size: 24px; font-weight: 800; color: var(--text); margin-bottom: 4px; display: flex; align-items: center; gap: 10px; }
  .page-top p { font-size: 13.5px; color: var(--text-muted); }

  .header-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
  .btn-secondary-link {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 16px; border-radius: 10px;
    background: var(--surface); border: 1.5px solid var(--border);
    color: var(--text); text-decoration: none; font-size: 13px; font-weight: 600;
    transition: all 0.2s;
  }
  .btn-secondary-link:hover { border-color: var(--primary); color: var(--primary); background: var(--primary-light); }

  /* TABS */
  .nav-tabs {
    display: flex; gap: 8px; margin-bottom: 20px; border-bottom: 2px solid var(--border); padding-bottom: 2px;
  }
  .nav-tab {
    padding: 10px 20px; font-size: 13.5px; font-weight: 700;
    color: var(--text-muted); text-decoration: none; border-radius: 10px 10px 0 0;
    border-bottom: 3px solid transparent; display: inline-flex; align-items: center; gap: 8px;
    transition: all 0.2s;
  }
  .nav-tab:hover { color: var(--primary); }
  .nav-tab.active {
    color: var(--primary); border-bottom-color: var(--primary); background: var(--surface);
  }

  /* STATS CARDS */
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
  }
  .stat-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 20px;
    display: flex; align-items: center; gap: 16px;
    transition: transform 0.2s, box-shadow 0.2s;
  }
  .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.05); }
  .stat-icon {
    width: 48px; height: 48px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
  }
  .stat-info .val { font-size: 22px; font-weight: 800; color: var(--text); line-height: 1.2; }
  .stat-info .lbl { font-size: 12.5px; color: var(--text-muted); font-weight: 500; margin-top: 2px; }

  /* FILTER CARD */
  .filter-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 18px 20px;
    margin-bottom: 20px;
  }
  .filter-form {
    display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
  }
  .search-box {
    flex: 1; min-width: 220px;
    display: flex; align-items: center; gap: 10px;
    background: var(--bg); border: 1px solid var(--border);
    border-radius: 10px; padding: 9px 14px;
  }
  .search-box input { border: none; background: transparent; outline: none; font-size: 13px; width: 100%; color: var(--text); font-family: inherit; }
  .filter-select {
    padding: 9px 14px; border: 1px solid var(--border); border-radius: 10px;
    background: var(--bg); font-size: 13px; color: var(--text); outline: none;
    font-family: inherit;
  }
  .btn-filter {
    padding: 9px 18px; border-radius: 10px; border: none;
    background: var(--primary); color: #fff; font-size: 13px; font-weight: 600;
    cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
    transition: background 0.2s;
  }
  .btn-filter:hover { background: var(--primary-dark); }
  .btn-reset {
    padding: 9px 14px; border-radius: 10px; border: 1px solid var(--border);
    background: var(--surface); color: var(--text-muted); font-size: 13px; font-weight: 600;
    cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
  }
  .btn-reset:hover { background: var(--bg); color: var(--text); }

  /* TABLE CARD */
  .table-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
  }
  table { width: 100%; border-collapse: collapse; }
  thead tr { background: #FAF5FF; }
  th {
    padding: 14px 16px; text-align: left; font-size: 11.5px;
    font-weight: 700; color: var(--primary); text-transform: uppercase;
    letter-spacing: 0.5px; border-bottom: 1px solid var(--border);
  }
  td {
    padding: 14px 16px; font-size: 13px;
    border-bottom: 1px solid var(--border); vertical-align: middle;
  }
  tbody tr:hover { background: #FAFAFC; }

  /* BADGES */
  .status-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;
  }
  .nup-badge {
    background: #F1F5F9; color: #475569; font-weight: 700; font-family: monospace;
    padding: 3px 8px; border-radius: 6px; font-size: 12px; display: inline-block;
  }

  .kondisi-baik { background: #ecfdf5; color: #10b981; }
  .kondisi-rusak-ringan { background: #fffbeb; color: #f59e0b; }
  .kondisi-rusak-berat { background: #fef2f2; color: #ef4444; }

  .badge-disetujui { background: var(--emerald-light); color: var(--emerald); }
  .badge-pending { background: var(--amber-light); color: var(--amber); }
  .badge-ditolak { background: var(--danger-light); color: var(--danger); }
  .badge-dikembalikan { background: var(--blue-light); color: var(--blue); }

  .btn-detail {
    padding: 6px 12px; border-radius: 8px; border: 1px solid var(--border);
    background: var(--surface); color: var(--text); font-size: 12px; font-weight: 600;
    cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
    transition: all 0.2s; text-decoration: none;
  }
  .btn-detail:hover { background: var(--primary-light); color: var(--primary); border-color: var(--primary); }

  /* MODAL DETAIL */
  .modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px);
    z-index: 1000; align-items: center; justify-content: center; padding: 20px;
  }
  .modal-overlay.show { display: flex; }
  .modal-box {
    background: #fff; width: 100%; max-width: 600px;
    border-radius: 18px; padding: 26px; box-shadow: 0 20px 40px rgba(0,0,0,0.15);
  }
  .modal-header {
    display: flex; justify-content: space-between; align-items: center;
    padding-bottom: 16px; border-bottom: 1px solid var(--border); margin-bottom: 18px;
  }
  .modal-title { font-size: 17px; font-weight: 800; color: var(--text); }
  .btn-close { background: none; border: none; font-size: 18px; color: var(--muted); cursor: pointer; }
  .detail-grid { display: grid; grid-template-columns: 140px 1fr; gap: 10px; font-size: 13px; }
  .detail-grid .lbl { color: var(--text-muted); font-weight: 600; }
  .detail-grid .val { color: var(--text); font-weight: 500; }

  @media(max-width: 1024px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
  @media(max-width: 768px) { .main { margin-left: 0; } .stats-grid { grid-template-columns: 1fr; } .content { padding: 20px 16px; } }
</style>
</head>
<body>

@include('partials.sidebar')

<main class="main">
  <div class="topbar">
    <div class="topbar-title">
      <i class="fas fa-boxes-stacked" style="color:var(--primary)"></i>
      Monitoring Barang & Aset Tetap
    </div>
    <div class="date-text">
      <i class="fas fa-calendar-alt" style="margin-right: 6px;"></i>
      {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
    </div>
  </div>

  <div class="content">
    <div class="page-top">
      <div>
        <h1><i class="fas fa-cubes" style="color:var(--primary);"></i> Monitoring Barang & Aset Tetap</h1>
        <p>Pantau data master BMN, status ketersediaan, NUP, riwayat peminjaman barang, serta Berita Acara (BAST).</p>
      </div>
      <div class="header-actions">
        <a href="{{ route('kasubag.persetujuan-peminjaman-barang') }}" class="btn-secondary-link">
          <i class="fas fa-check-square"></i> Persetujuan Peminjaman Barang &rarr;
        </a>
        <a href="{{ route('kasubag.transaksi-keluar', ['sumber' => 'aset_tetap']) }}" class="btn-secondary-link">
          <i class="fas fa-dolly"></i> Transaksi Keluar Aset &rarr;
        </a>
      </div>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon" style="background:var(--blue-light); color:var(--blue);"><i class="fas fa-database"></i></div>
        <div class="stat-info">
          <div class="val">{{ number_format($stats['total'], 0, ',', '.') }}</div>
          <div class="lbl">Total Master Aset</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:var(--emerald-light); color:var(--emerald);"><i class="fas fa-circle-check"></i></div>
        <div class="stat-info">
          <div class="val" style="color:var(--emerald);">{{ number_format($stats['tersedia'], 0, ',', '.') }}</div>
          <div class="lbl">Aset Tersedia</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:var(--amber-light); color:var(--amber);"><i class="fas fa-hand-holding"></i></div>
        <div class="stat-info">
          <div class="val" style="color:var(--amber);">{{ number_format($stats['dipinjam'], 0, ',', '.') }}</div>
          <div class="lbl">Sedang Dipinjam</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:var(--primary-light); color:var(--primary);"><i class="fas fa-file-contract"></i></div>
        <div class="stat-info">
          <div class="val" style="color:var(--primary);">{{ number_format($stats['peminjamanTotal'] ?? 0, 0, ',', '.') }}</div>
          <div class="lbl">Total Peminjaman BMN</div>
        </div>
      </div>
    </div>

    <!-- TABS -->
    <div class="nav-tabs">
      <a href="{{ route('kasubag.monitoring-aset-tetap', ['tab' => 'master']) }}" class="nav-tab {{ ($tab ?? 'master') === 'master' ? 'active' : '' }}">
        <i class="fas fa-cubes"></i> Master Data Aset & NUP
      </a>
      <a href="{{ route('kasubag.monitoring-aset-tetap', ['tab' => 'peminjaman']) }}" class="nav-tab {{ ($tab ?? 'master') === 'peminjaman' ? 'active' : '' }}">
        <i class="fas fa-file-contract"></i> Peminjaman Barang & Berita Acara (BAST)
      </a>
    </div>

    @if(($tab ?? 'master') === 'master')
      <!-- FILTER TOOLBAR MASTER -->
      <div class="filter-card">
        <form action="{{ route('kasubag.monitoring-aset-tetap') }}" method="GET" class="filter-form">
          <input type="hidden" name="tab" value="master">
          <div class="search-box">
            <i class="fas fa-search" style="color:var(--muted)"></i>
            <input type="text" name="search" placeholder="Cari kode barang, NUP, nama barang, merek, lokasi..." value="{{ request('search') }}">
          </div>

          <select name="kategori" class="filter-select">
            <option value="">Semua Kategori</option>
            @foreach($kategoriList as $kat)
              <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
            @endforeach
          </select>

          <select name="kondisi" class="filter-select">
            <option value="">Semua Kondisi</option>
            <option value="baik" {{ request('kondisi') == 'baik' ? 'selected' : '' }}>🟢 Baik</option>
            <option value="rusak ringan" {{ request('kondisi') == 'rusak ringan' ? 'selected' : '' }}>🟡 Rusak Ringan</option>
            <option value="rusak berat" {{ request('kondisi') == 'rusak berat' ? 'selected' : '' }}>🔴 Rusak Berat</option>
          </select>

          <select name="status" class="filter-select">
            <option value="">Semua Status</option>
            <option value="Tersedia" {{ request('status') == 'Tersedia' ? 'selected' : '' }}>🟢 Tersedia</option>
            <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>🔵 Dipinjam</option>
            <option value="Keluar" {{ request('status') == 'Keluar' ? 'selected' : '' }}>🟡 Keluar</option>
            <option value="Rusak" {{ request('status') == 'Rusak' ? 'selected' : '' }}>🔴 Rusak</option>
          </select>

          <button type="submit" class="btn-filter"><i class="fas fa-filter"></i> Filter</button>
          @if(request()->anyFilled(['search', 'kategori', 'kondisi', 'status']))
            <a href="{{ route('kasubag.monitoring-aset-tetap', ['tab' => 'master']) }}" class="btn-reset"><i class="fas fa-rotate-left"></i> Reset</a>
          @endif
        </form>
      </div>

      <!-- TABLE MASTER ASET -->
      <div class="table-card">
        <table>
          <thead>
            <tr>
              <th style="width: 50px;">No</th>
              <th>Kode Barang</th>
              <th>NUP</th>
              <th>Nama Barang</th>
              <th>Merek</th>
              <th>Kategori</th>
              <th>Kondisi</th>
              <th>Lokasi</th>
              <th>Jumlah</th>
              <th>Status</th>
              <th style="text-align: center;">Detail</th>
            </tr>
          </thead>
          <tbody>
            @forelse($asetTetap as $index => $item)
              <tr>
                <td><strong>{{ $asetTetap->firstItem() + $index }}</strong></td>
                <td><strong>{{ $item->kode_barang ?? '-' }}</strong></td>
                <td><span class="nup-badge">{{ $item->nup ?? '-' }}</span></td>
                <td>{{ $item->nama_barang ?? '-' }}</td>
                <td>{{ $item->merek ?? '-' }}</td>
                <td>{{ $item->kategori ?? '-' }}</td>
                <td>
                  @php $knd = strtolower($item->kondisi ?? 'baik'); @endphp
                  <span class="status-badge kondisi-{{ str_replace(' ', '-', $knd) }}">
                    {{ ucwords($knd) }}
                  </span>
                </td>
                <td>{{ $item->lokasi ?? '-' }}</td>
                <td><strong>{{ $item->jumlah ?? 0 }}</strong> Unit</td>
                <td>
                  @php
                    $st = $item->status ?? 'Tersedia';
                    $colors = [
                      'Tersedia' => ['bg' => '#ECFDF5', 'text' => '#10B981', 'icon' => '🟢'],
                      'Dipinjam' => ['bg' => '#DBEAFE', 'text' => '#3B82F6', 'icon' => '🔵'],
                      'Keluar' => ['bg' => '#FEF3C7', 'text' => '#F59E0B', 'icon' => '🟡'],
                      'Rusak' => ['bg' => '#FEF2F2', 'text' => '#EF4444', 'icon' => '🔴'],
                    ];
                    $c = $colors[$st] ?? $colors['Tersedia'];
                  @endphp
                  <span class="status-badge" style="background: {{ $c['bg'] }}; color: {{ $c['text'] }};">
                    {{ $c['icon'] }} {{ $st }}
                  </span>
                </td>
                <td style="text-align: center;">
                  <button type="button" class="btn-detail" onclick="openAsetModal({{ json_encode($item) }})">
                    <i class="fas fa-eye"></i> Detail
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="11" style="text-align:center; padding: 48px; color:var(--muted);">
                  <i class="fas fa-box-open" style="font-size:32px; margin-bottom:12px; display:block; opacity:0.4;"></i>
                  Tidak ada data aset tetap yang ditemukan.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>

        @if($asetTetap->hasPages())
          <div style="padding: 16px 20px; border-top: 1px solid var(--border);">
            {{ $asetTetap->links() }}
          </div>
        @endif
      </div>

    @else
      <!-- TAB 2: PEMINJAMAN BARANG & BERITA ACARA -->
      <div class="filter-card">
        <form action="{{ route('kasubag.monitoring-aset-tetap') }}" method="GET" class="filter-form">
          <input type="hidden" name="tab" value="peminjaman">
          <div class="search-box">
            <i class="fas fa-search" style="color:var(--muted)"></i>
            <input type="text" name="search" placeholder="Cari nama peminjam, barang, NUP, kode barang..." value="{{ request('search') }}">
          </div>

          <button type="submit" class="btn-filter"><i class="fas fa-filter"></i> Cari</button>
          @if(request()->filled('search'))
            <a href="{{ route('kasubag.monitoring-aset-tetap', ['tab' => 'peminjaman']) }}" class="btn-reset"><i class="fas fa-rotate-left"></i> Reset</a>
          @endif
        </form>
      </div>

      <div class="table-card">
        <table>
          <thead>
            <tr>
              <th style="width: 50px;">No</th>
              <th>Tgl Pengajuan</th>
              <th>Barang (Kode & NUP)</th>
              <th>Pemohon</th>
              <th>Jadwal Pinjam</th>
              <th>Jumlah</th>
              <th>Peruntukan</th>
              <th>Status</th>
              <th>Berita Acara (BAST)</th>
            </tr>
          </thead>
          <tbody>
            @forelse($peminjamanBarang as $index => $pinjam)
              <tr>
                <td><strong>{{ $peminjamanBarang->firstItem() + $index }}</strong></td>
                <td>{{ $pinjam->created_at?->format('d/m/Y') ?? '-' }}</td>
                <td>
                  <strong style="color:var(--text); display:block;">{{ $pinjam->nama_barang }}</strong>
                  <span style="font-size:11px; color:var(--text-muted);">
                    {{ $pinjam->kode_barang }} / NUP: <span class="nup-badge">{{ $pinjam->nup ?? '-' }}</span>
                  </span>
                </td>
                <td>
                  <strong style="color:var(--text); display:block;">{{ $pinjam->user->name ?? 'Pegawai' }}</strong>
                  <span style="font-size:11px; color:var(--text-muted);">{{ $pinjam->user->nip ?? '-' }}</span>
                </td>
                <td>
                  <div style="font-size:12px; font-weight:600;">
                    {{ \Carbon\Carbon::parse($pinjam->tanggal_peminjaman)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($pinjam->tanggal_pengembalian)->format('d/m/Y') }}
                  </div>
                </td>
                <td><strong>{{ $pinjam->jumlah }}</strong> Unit</td>
                <td><span style="font-size:12px;">{{ Str::limit($pinjam->deskripsi_peruntukan, 40) }}</span></td>
                <td>
                  @if($pinjam->status == 'disetujui')
                    <span class="badge badge-disetujui"><i class="fas fa-check-circle"></i> Disetujui</span>
                  @elseif($pinjam->status == 'dikembalikan')
                    <span class="badge badge-dikembalikan"><i class="fas fa-circle-check"></i> Selesai</span>
                  @elseif($pinjam->status == 'ditolak')
                    <span class="badge badge-ditolak"><i class="fas fa-times-circle"></i> Ditolak</span>
                  @elseif($pinjam->status == 'diteruskan_kasubag')
                    <span class="badge badge-pending"><i class="fas fa-clock"></i> Diteruskan ke Kasubag</span>
                  @else
                    <span class="badge badge-pending"><i class="fas fa-clock"></i> {{ ucfirst($pinjam->status) }}</span>
                  @endif
                </td>
                <td>
                  @if(in_array($pinjam->status, ['disetujui', 'dikembalikan']))
                    <div style="display:flex; flex-direction:column; gap:4px;">
                      <form action="{{ route('adminasettetap.peminjaman-barang.print', $pinjam->id) }}" method="GET" target="_blank" style="display:flex; gap:4px; align-items:center;">
                        <input type="date" name="tanggal_surat" value="{{ now()->format('Y-m-d') }}" required title="Tanggal surat" style="padding:4px 6px; border:1px solid var(--border); border-radius:6px; font-size:11px; max-width:115px;">
                        <button type="submit" class="btn-detail" style="color:var(--primary); font-size:11px; padding:4px 8px; white-space:nowrap;" title="Generate & Cetak BAST Barang">
                          <i class="fas fa-file-pdf"></i> Cetak BAST
                        </button>
                      </form>
                      @if(!empty($pinjam->surat_bast_path))
                        <a href="{{ asset('storage/' . $pinjam->surat_bast_path) }}" target="_blank" class="btn-detail" style="color:var(--emerald); font-size:11px; padding:4px 8px; text-decoration:none;" title="Lihat BAST Terunggah">
                          <i class="fas fa-file-circle-check"></i> BAST Fisik
                        </a>
                      @endif
                    </div>
                  @else
                    <span style="font-size:11.5px; color:var(--muted); font-style:italic;">Belum tersedia</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="9" style="text-align:center; padding: 48px; color:var(--muted);">
                  <i class="fas fa-inbox" style="font-size:32px; margin-bottom:12px; display:block; opacity:0.4;"></i>
                  Belum ada riwayat peminjaman barang aset tetap.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>

        @if($peminjamanBarang->hasPages())
          <div style="padding: 16px 20px; border-top: 1px solid var(--border);">
            {{ $peminjamanBarang->links() }}
          </div>
        @endif
      </div>
    @endif

  </div>
</main>

<!-- MODAL DETAIL ASET -->
<div class="modal-overlay" id="modalAset">
  <div class="modal-box">
    <div class="modal-header">
      <div class="modal-title"><i class="fas fa-cube" style="color:var(--primary); margin-right:6px;"></i> Detail Aset Tetap</div>
      <button type="button" class="btn-close" onclick="closeAsetModal()">&times;</button>
    </div>

    <div class="detail-grid">
      <div class="lbl">Kode Barang</div><div class="val" id="detKode">-</div>
      <div class="lbl">NUP</div><div class="val" id="detNup">-</div>
      <div class="lbl">Nama Barang</div><div class="val" id="detNama">-</div>
      <div class="lbl">Merek</div><div class="val" id="detMerek">-</div>
      <div class="lbl">Kategori</div><div class="val" id="detKategori">-</div>
      <div class="lbl">Kondisi</div><div class="val" id="detKondisi">-</div>
      <div class="lbl">Jumlah / Satuan</div><div class="val" id="detJumlah">-</div>
      <div class="lbl">Status</div><div class="val" id="detStatus">-</div>
      <div class="lbl">Lokasi</div><div class="val" id="detLokasi">-</div>
      <div class="lbl">Nilai Perolehan</div><div class="val" id="detNilai">-</div>
      <div class="lbl">Tgl Input / Perolehan</div><div class="val" id="detTgl">-</div>
    </div>

    <div style="margin-top:24px; text-align:right;">
      <button type="button" class="btn-filter" onclick="closeAsetModal()">Tutup</button>
    </div>
  </div>
</div>

<script>
  function openAsetModal(item) {
    document.getElementById('detKode').innerText = item.kode_barang || '-';
    document.getElementById('detNup').innerHTML = '<span class="nup-badge">' + (item.nup || '-') + '</span>';
    document.getElementById('detNama').innerText = item.nama_barang || '-';
    document.getElementById('detMerek').innerText = item.merek || '-';
    document.getElementById('detKategori').innerText = item.kategori || '-';
    document.getElementById('detKondisi').innerText = item.kondisi ? item.kondisi.toUpperCase() : '-';
    document.getElementById('detJumlah').innerText = (item.jumlah || 0) + ' Unit';
    document.getElementById('detStatus').innerText = item.status || 'Tersedia';
    document.getElementById('detLokasi').innerText = item.lokasi || '-';
    document.getElementById('detNilai').innerText = item.nilai_perolehan ? 'Rp ' + Number(item.nilai_perolehan).toLocaleString('id-ID') : '-';
    document.getElementById('detTgl').innerText = item.tanggal_input ? new Date(item.tanggal_input).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'}) : '-';

    document.getElementById('modalAset').classList.add('show');
  }

  function closeAsetModal() {
    document.getElementById('modalAset').classList.remove('show');
  }

  document.getElementById('modalAset').addEventListener('click', function(e) {
    if(e.target === this) closeAsetModal();
  });
</script>
</body>
</html>
