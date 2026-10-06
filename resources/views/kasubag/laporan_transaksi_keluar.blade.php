<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIPANDU - Laporan Transaksi Keluar</title>
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
  .topbar-right { display: flex; align-items: center; gap: 16px; }
  .date-text { font-size: 13px; color: var(--text-muted); font-weight: 500; }
  
  .content { padding: 28px 32px; flex: 1; }

  /* PAGE HEADER */
  .page-top {
    display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;
  }
  .page-top h1 { font-size: 24px; font-weight: 800; color: var(--text); margin-bottom: 4px; display: flex; align-items: center; gap: 10px; }
  .page-top p { font-size: 13.5px; color: var(--text-muted); }
  .header-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

  .btn-action {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 9px 16px; border-radius: 10px; font-size: 13px; font-weight: 700;
    cursor: pointer; text-decoration: none; transition: all .2s; border: none;
  }
  .btn-pdf {
    background: linear-gradient(135deg, var(--danger), #DC2626);
    color: white; box-shadow: 0 4px 12px rgba(239,68,68,0.25);
  }
  .btn-pdf:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(239,68,68,0.35); }
  .btn-print {
    background: var(--surface); color: var(--text); border: 1px solid var(--border);
  }
  .btn-print:hover { background: #f1f5f9; }

  /* NAVIGATION TABS */
  .tabs-wrapper {
    display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;
  }
  .tabs-group {
    display: flex; gap: 6px; background: #E2E8F0; padding: 5px; border-radius: 12px;
  }
  .tab-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 700;
    text-decoration: none; color: var(--text-muted); transition: all .2s;
  }
  .tab-btn.active {
    background: var(--surface); color: var(--primary); box-shadow: 0 2px 8px rgba(0,0,0,0.06);
  }

  /* STATS CARDS */
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
  }
  .stat-card {
    background: var(--surface);
    border-radius: var(--radius);
    padding: 20px 22px;
    border: 1px solid var(--border);
    transition: transform .2s, box-shadow .2s;
  }
  .stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,.05); }
  .stat-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
  .stat-icon {
    width: 38px; height: 38px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center; font-size: 16px;
  }
  .stat-label-sm { font-size: 13px; font-weight: 600; color: var(--text-muted); }
  .stat-value { font-size: 26px; font-weight: 800; margin-bottom: 4px; }
  .stat-sub { font-size: 12px; color: var(--muted); }

  /* FILTER BAR */
  .filter-bar {
    background: var(--surface);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    padding: 18px 20px;
    margin-bottom: 24px;
  }
  .filter-row { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
  .filter-group { display: flex; align-items: center; gap: 6px; }
  .filter-label { font-size: 12px; font-weight: 600; color: var(--text-muted); }
  .filter-input {
    padding: 8px 12px; border-radius: 8px;
    border: 1.5px solid var(--border); background: var(--bg);
    font-family: inherit; font-size: 13px; color: var(--text);
  }
  .filter-input:focus { outline: none; border-color: var(--primary); }
  .filter-select {
    padding: 8px 12px; border-radius: 8px; border: 1.5px solid var(--border);
    background: var(--bg); font-family: inherit; font-size: 13px; cursor: pointer;
  }
  .btn-filter {
    background: var(--primary); color: #fff; border: none; padding: 8px 16px;
    border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;
    display: inline-flex; align-items: center; gap: 6px;
  }
  .btn-reset {
    background: var(--danger-light); color: var(--danger); border: 1px solid #FECACA;
    padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 600;
    text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
  }

  /* CHART CARD */
  .chart-card {
    background: var(--surface);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    padding: 22px 24px;
    margin-bottom: 24px;
  }
  .chart-title { font-size: 15px; font-weight: 700; color: var(--text); margin-bottom: 18px; display: flex; align-items: center; gap: 8px; }
  .chart-area { height: 180px; display: flex; align-items: flex-end; gap: 16px; margin-bottom: 8px; padding-top: 20px; }
  .chart-col { display: flex; flex-direction: column; align-items: center; flex: 1; gap: 8px; height: 100%; justify-content: flex-end; }
  .bar-wrap { width: 100%; max-width: 50px; display: flex; align-items: flex-end; height: 120px; }
  .bar {
    width: 100%; border-radius: 6px 6px 0 0;
    background: linear-gradient(180deg, var(--primary), var(--primary-dark));
    min-height: 4px; transition: opacity .2s;
  }
  .bar:hover { opacity: .85; }
  .bar-val { font-size: 11px; font-weight: 700; color: var(--primary-dark); }
  .bar-label { font-size: 11px; font-weight: 600; color: var(--text-muted); }

  /* TABLE */
  .table-card {
    background: var(--surface);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.02);
  }
  .table-toolbar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 22px; border-bottom: 1px solid var(--border); background: #fafcff;
  }
  .table-responsive { width: 100%; overflow-x: auto; }
  table { width: 100%; border-collapse: collapse; white-space: nowrap; }
  thead tr { background: #f8fafc; }
  th {
    padding: 12px 18px; text-align: left;
    font-size: 11px; font-weight: 700; color: var(--text-muted);
    letter-spacing: .5px; text-transform: uppercase; border-bottom: 1px solid var(--border);
  }
  td {
    padding: 13px 18px; font-size: 13px; color: var(--text);
    border-bottom: 1px solid var(--border); vertical-align: middle;
  }
  tbody tr:hover { background: #f9fafb; }
  .font-mono { font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace; font-size: 12px; }
  
  .badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; border-radius: 6px; font-size: 11.5px; font-weight: 600;
  }
  .badge-primary { background: var(--primary-light); color: var(--primary-dark); }
  .badge-emerald { background: var(--emerald-light); color: var(--emerald); }
  .badge-amber { background: var(--amber-light); color: var(--amber); }

  .progress-bar-bg { width: 100px; height: 8px; background: #e2e8f0; border-radius: 999px; overflow: hidden; display: inline-block; vertical-align: middle; margin-right: 6px; }
  .progress-bar-fill { height: 100%; background: var(--primary); border-radius: 999px; }

  .table-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 22px; border-top: 1px solid var(--border); font-size: 13px; color: var(--text-muted);
  }

  /* PRINT STYLES */
  @media print {
    .sidebar-wrapper, .topbar, .tabs-wrapper, .filter-bar, .header-actions, .table-footer { display: none !important; }
    .main { margin-left: 0 !important; width: 100% !important; padding: 0 !important; }
    .content { padding: 0 !important; }
    .table-card { border: none !important; box-shadow: none !important; }
    table { width: 100% !important; border: 1px solid #000 !important; }
    th, td { border: 1px solid #000 !important; }
    .print-header { display: block !important; margin-bottom: 20px; text-align: center; }
  }
  .print-header { display: none; }
</style>
</head>
<body>

@include('partials.sidebar')

<main class="main">
  <div class="topbar">
    <span class="topbar-title">
      <i class="fas fa-file-invoice" style="color:var(--primary)"></i> Laporan Transaksi Keluar
    </span>
    <div class="topbar-right">
      <span class="date-text">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}</span>
    </div>
  </div>

  <div class="content">
    
    <!-- PRINT HEADER ONLY -->
    <div class="print-header">
      <h2 style="font-size:18px; margin-bottom:4px;">BALAI PENJAMINAN MUTU PENDIDIKAN (BPMP) PROVINSI GORONTALO</h2>
      <h3 style="font-size:15px; margin-bottom:6px;">LAPORAN TRANSAKSI KELUAR ({{ strtoupper($sumber) }})</h3>
      <p style="font-size:12px;">Format: {{ $mode === 'keseluruhan' ? 'Rekapitulasi Keseluruhan' : 'Rincian Per Detail Barang' }} | Dicetak pada: {{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</p>
      <hr style="margin: 12px 0 20px; border: 1px solid #000;">
    </div>

    <!-- PAGE TOP -->
    <div class="page-top">
      <div>
        <h1>
          Laporan Transaksi Keluar
          <span class="badge {{ $mode === 'keseluruhan' ? 'badge-primary' : 'badge-emerald' }}" style="font-size:12px;">
            {{ $mode === 'keseluruhan' ? 'Rekap Keseluruhan' : 'Rincian Detail Barang' }}
          </span>
        </h1>
        <p>Laporan analisis pengeluaran barang persediaan dan aset tetap untuk Kasubag.</p>
      </div>

      <div class="header-actions">
        <a href="{{ route('kasubag.laporan-transaksi-keluar.pdf', request()->query()) }}" class="btn-action btn-pdf" target="_blank">
          <i class="fas fa-file-pdf"></i> Unduh PDF
        </a>
        <button type="button" class="btn-action btn-print" onclick="window.print()">
          <i class="fas fa-print"></i> Cetak
        </button>
      </div>
    </div>

    <!-- TABS NAVIGATION -->
    <div class="tabs-wrapper">
      <!-- 1. Mode Tab: Keseluruhan vs Detail Barang -->
      <div class="tabs-group">
        <a href="{{ route('kasubag.laporan-transaksi-keluar', array_merge(request()->query(), ['mode' => 'keseluruhan'])) }}" class="tab-btn {{ $mode === 'keseluruhan' ? 'active' : '' }}">
          <i class="fas fa-chart-pie"></i> Rekap Keseluruhan
        </a>
        <a href="{{ route('kasubag.laporan-transaksi-keluar', array_merge(request()->query(), ['mode' => 'detail'])) }}" class="tab-btn {{ $mode === 'detail' ? 'active' : '' }}">
          <i class="fas fa-list-ul"></i> Rincian Per Detail Barang
        </a>
      </div>

      <!-- 2. Sumber Tab: Persediaan vs Aset Tetap -->
      <div class="tabs-group">
        <a href="{{ route('kasubag.laporan-transaksi-keluar', array_merge(request()->query(), ['sumber' => 'persediaan'])) }}" class="tab-btn {{ $sumber === 'persediaan' ? 'active' : '' }}">
          <i class="fas fa-boxes"></i> Persediaan
        </a>
        <a href="{{ route('kasubag.laporan-transaksi-keluar', array_merge(request()->query(), ['sumber' => 'aset_tetap'])) }}" class="tab-btn {{ $sumber === 'aset_tetap' ? 'active' : '' }}">
          <i class="fas fa-cubes"></i> Aset Tetap
        </a>
      </div>
    </div>

    <!-- SUMMARY STATS -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-header">
          <span class="stat-label-sm">Total Transaksi Keluar</span>
          <div class="stat-icon" style="background:#EFF6FF;color:#3B82F6;"><i class="fas fa-exchange-alt"></i></div>
        </div>
        <div class="stat-value" style="color:#3B82F6;">{{ number_format($totalTransaksi, 0, ',', '.') }}</div>
        <div class="stat-sub">Sesuai filter aktif</div>
      </div>

      <div class="stat-card">
        <div class="stat-header">
          <span class="stat-label-sm">Total Volume / Kuantitas</span>
          <div class="stat-icon" style="background:#ECFDF5;color:#10B981;"><i class="fas fa-boxes"></i></div>
        </div>
        <div class="stat-value" style="color:#10B981;">{{ number_format($totalUnit, 0, ',', '.') }} <span style="font-size:14px;color:var(--muted);font-weight:500;">Unit</span></div>
        <div class="stat-sub">Barang yang dikeluarkan</div>
      </div>

      <div class="stat-card">
        <div class="stat-header">
          <span class="stat-label-sm">Total Nilai Akumulasi</span>
          <div class="stat-icon" style="background:#FFFBEB;color:#F59E0B;"><i class="fas fa-coins"></i></div>
        </div>
        <div class="stat-value" style="color:#D97706; font-size:24px;">Rp {{ number_format($totalNilai, 0, ',', '.') }}</div>
        <div class="stat-sub">Nilai rupiah pengeluaran</div>
      </div>
    </div>

    <!-- FILTER BAR -->
    <div class="filter-bar">
      <form method="GET" action="{{ route('kasubag.laporan-transaksi-keluar') }}">
        <input type="hidden" name="mode" value="{{ $mode }}">
        <input type="hidden" name="sumber" value="{{ $sumber }}">
        <div class="filter-row">
          <div class="filter-group">
            <span class="filter-label">Dari:</span>
            <input type="date" name="start_date" class="filter-input" value="{{ request('start_date') }}">
          </div>
          <div class="filter-group">
            <span class="filter-label">Sampai:</span>
            <input type="date" name="end_date" class="filter-input" value="{{ request('end_date') }}">
          </div>
          @if(isset($kategoriList) && count($kategoriList) > 0)
          <div class="filter-group">
            <select name="kategori" class="filter-select">
              <option value="">Semua {{ $sumber === 'aset_tetap' ? 'Merek' : 'Kategori' }}</option>
              @foreach($kategoriList as $kat)
                <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
              @endforeach
            </select>
          </div>
          @endif
          <div class="filter-group">
            <input type="text" name="search" class="filter-input" placeholder="Cari nama / kode barang..." value="{{ request('search') }}" style="width: 220px;">
          </div>
          <button type="submit" class="btn-filter">
            <i class="fas fa-filter"></i> Terapkan
          </button>
          @if(request()->hasAny(['start_date', 'end_date', 'kategori', 'search']))
            <a href="{{ route('kasubag.laporan-transaksi-keluar', ['mode' => $mode, 'sumber' => $sumber]) }}" class="btn-reset">
              <i class="fas fa-times"></i> Reset
            </a>
          @endif
        </div>
      </form>
    </div>

    @if($mode === 'keseluruhan')
      <!-- ======================================================== -->
      <!-- TAMPILAN 1: REKAPITULASI KESELURUHAN                    -->
      <!-- ======================================================== -->
      <!-- TABEL REKAPITULASI PER KATEGORI -->
      <div class="table-card">
        <div class="table-toolbar">
          <div>
            <h3 style="font-size:14px; font-weight:700; color:var(--text); margin-bottom:2px;">
              Rekapitulasi Pengeluaran Per {{ $sumber === 'aset_tetap' ? 'Merek / Golongan' : 'Kategori Barang' }}
            </h3>
            <span style="font-size:12px; color:var(--text-muted);">Akumulasi jumlah transaksi, volume barang, dan total nilai rupiah</span>
          </div>
          <span class="badge badge-primary">{{ count($rekapKategori) }} Kelompok</span>
        </div>

        <div class="table-responsive">
          <table>
            <thead>
              <tr>
                <th width="5%">No</th>
                <th>Nama {{ $sumber === 'aset_tetap' ? 'Merek / Golongan' : 'Kategori' }}</th>
                @if($sumber === 'persediaan')
                  <th width="15%">Kode Kategori</th>
                @endif
                <th width="15%">Frekuensi Transaksi</th>
                <th width="15%">Total Unit Keluar</th>
                <th width="18%">Total Nilai (Rp)</th>
                <th width="15%">Kontribusi Nilai</th>
              </tr>
            </thead>
            <tbody>
              @forelse($rekapKategori as $kat)
                @php
                  $kontribusi = $totalNilai > 0 ? ($kat->total_nominal / $totalNilai) * 100 : 0;
                @endphp
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td><strong>{{ $kat->nama_kategori }}</strong></td>
                  @if($sumber === 'persediaan')
                    <td class="font-mono">{{ $kat->kode_kategori ?? '-' }}</td>
                  @endif
                  <td>{{ number_format($kat->frekuensi, 0, ',', '.') }} Kali</td>
                  <td><strong>{{ number_format($kat->total_unit, 0, ',', '.') }}</strong> Unit</td>
                  <td class="font-mono" style="font-weight:700; color:#D97706;">Rp {{ number_format($kat->total_nominal, 0, ',', '.') }}</td>
                  <td>
                    <div class="progress-bar-bg">
                      <div class="progress-bar-fill" style="width: {{ $kontribusi }}%;"></div>
                    </div>
                    <span style="font-size:11px; font-weight:600; color:var(--text-muted);">{{ number_format($kontribusi, 1) }}%</span>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="{{ $sumber === 'persediaan' ? 7 : 6 }}" style="text-align:center; padding: 40px; color:var(--muted);">
                    Tidak ada data rekapitulasi pada filter yang dipilih.
                  </td>
                </tr>
              @endforelse
            </tbody>
            @if(count($rekapKategori) > 0)
              <tfoot>
                <tr style="background:#f8fafc; font-weight:700;">
                  <td colspan="{{ $sumber === 'persediaan' ? 3 : 2 }}" style="text-align:right;">TOTAL KESELURUHAN:</td>
                  <td>{{ number_format($rekapKategori->sum('frekuensi'), 0, ',', '.') }} Kali</td>
                  <td>{{ number_format($rekapKategori->sum('total_unit'), 0, ',', '.') }} Unit</td>
                  <td class="font-mono" style="color:#D97706;">Rp {{ number_format($rekapKategori->sum('total_nominal'), 0, ',', '.') }}</td>
                  <td>100.0%</td>
                </tr>
              </tfoot>
            @endif
          </table>
        </div>
      </div>

    @else
      <!-- ======================================================== -->
      <!-- TAMPILAN 2: RINCIAN PER DETAIL BARANG                    -->
      <!-- ======================================================== -->
      <div class="table-card">
        <div class="table-toolbar">
          <div>
            <h3 style="font-size:14px; font-weight:700; color:var(--text); margin-bottom:2px;">
              Rincian Transaksi Per Detail Barang ({{ $sumber === 'aset_tetap' ? 'Aset Tetap / BMN' : 'Persediaan' }})
            </h3>
            <span style="font-size:12px; color:var(--text-muted);">Data transaksi detail baris demi baris</span>
          </div>
          <span style="font-size:13px; font-weight:600; color:var(--text-muted);">
            @if($detailTransaksi instanceof \Illuminate\Pagination\LengthAwarePaginator)
              {{ $detailTransaksi->firstItem() ?? 0 }}–{{ $detailTransaksi->lastItem() ?? 0 }} dari {{ $detailTransaksi->total() }} transaksi
            @else
              {{ $detailTransaksi->count() }} transaksi
            @endif
          </span>
        </div>

        <div class="table-responsive">
          <table>
            <thead>
              @if($sumber === 'aset_tetap')
                <tr>
                  <th width="4%">No</th>
                  <th width="10%">Tanggal Keluar</th>
                  <th width="14%">Kode Barang</th>
                  <th width="8%">NUP</th>
                  <th>Nama Barang</th>
                  <th>Merek</th>
                  <th>Lokasi</th>
                  <th width="12%">Nilai Perolehan</th>
                  <th>No. SK</th>
                </tr>
              @else
                <tr>
                  <th width="4%">No</th>
                  <th width="10%">Tanggal Keluar</th>
                  <th width="14%">Kode Barang</th>
                  <th>Nama Barang</th>
                  <th>Kategori</th>
                  <th width="8%">Jml Keluar</th>
                  <th width="10%">Harga Satuan</th>
                  <th width="12%">Total Nilai</th>
                  <th>Keterangan</th>
                </tr>
              @endif
            </thead>
            <tbody>
              @forelse($detailTransaksi as $item)
                @if($sumber === 'aset_tetap')
                  <tr>
                    <td>{{ ($detailTransaksi instanceof \Illuminate\Pagination\LengthAwarePaginator) ? ($detailTransaksi->currentPage() - 1) * $detailTransaksi->perPage() + $loop->iteration : $loop->iteration }}</td>
                    <td><strong>{{ $item->tanggal_input ? \Carbon\Carbon::parse($item->tanggal_input)->format('d/m/Y') : '-' }}</strong></td>
                    <td class="font-mono"><strong>{{ $item->kode_barang ?? '-' }}</strong></td>
                    <td class="font-mono"><span class="badge badge-amber">{{ $item->nup ?? '-' }}</span></td>
                    <td><strong>{{ $item->nama_barang ?? '-' }}</strong></td>
                    <td>{{ $item->merek ?? '-' }}</td>
                    <td>{{ $item->lokasi ?? '-' }}</td>
                    <td class="font-mono" style="font-weight:700; color:#D97706;">Rp {{ number_format($item->nilai_perolehan ?? 0, 0, ',', '.') }}</td>
                    <td class="font-mono" style="font-size:11px;">{{ $item->nomor_sk ?? '-' }}</td>
                  </tr>
                @else
                  <tr>
                    <td>{{ ($detailTransaksi instanceof \Illuminate\Pagination\LengthAwarePaginator) ? ($detailTransaksi->currentPage() - 1) * $detailTransaksi->perPage() + $loop->iteration : $loop->iteration }}</td>
                    <td><strong>{{ $item->tanggal_input ? \Carbon\Carbon::parse($item->tanggal_input)->format('d/m/Y') : '-' }}</strong></td>
                    <td class="font-mono"><strong>{{ $item->kode_unik_barang ?? $item->kode_barang ?? '-' }}</strong></td>
                    <td><strong>{{ $item->nama_barang ?? '-' }}</strong></td>
                    <td><span class="badge badge-primary">{{ $item->kategori ?? '-' }}</span></td>
                    <td>
                      <strong style="color:var(--danger);">{{ number_format($item->jumlah_keluar ?? 0, 0, ',', '.') }}</strong>
                      <span style="font-size:11px; color:var(--muted);">{{ $item->satuan ?? 'Unit' }}</span>
                    </td>
                    <td class="font-mono">Rp {{ number_format($item->harga ?? 0, 0, ',', '.') }}</td>
                    <td class="font-mono" style="font-weight:700; color:#D97706;">Rp {{ number_format($item->total ?? 0, 0, ',', '.') }}</td>
                    <td style="font-size:12px; color:var(--text-muted); max-width:200px; overflow:hidden; text-overflow:ellipsis;">
                      {{ $item->keterangan ?? '-' }}
                    </td>
                  </tr>
                @endif
              @empty
                <tr>
                  <td colspan="{{ $sumber === 'aset_tetap' ? 9 : 9 }}" style="text-align:center; padding: 40px; color:var(--muted);">
                    Tidak ada rincian data transaksi keluar pada filter yang dipilih.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="table-footer">
          <div>
            @if($detailTransaksi instanceof \Illuminate\Pagination\LengthAwarePaginator)
              Halaman {{ $detailTransaksi->currentPage() }} dari {{ $detailTransaksi->lastPage() }}
            @endif
          </div>
          @if($detailTransaksi instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div>{{ $detailTransaksi->links() }}</div>
          @endif
        </div>
      </div>
    @endif

  </div>
</main>

</body>
</html>
