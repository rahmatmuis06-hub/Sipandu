<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIPANDU - Laporan Transaksi Keluar</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  :root {
    --danger: #EF4444;
    --danger-light: #FEF2F2;
    --blue: #4F6FFF;
    --amber: #F59E0B;
    --purple: #8B5CF6;
    --radius: 16px;
    --bg: #F4F6FB;
    --surface: #FFFFFF;
    --text: #1E293B;
    --text-muted: #64748B;
    --muted: #94A3B8;
    --border: #E8EDF5;
  }
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; }

  .main { margin-left: 240px; flex: 1; display: flex; flex-direction: column; min-height: 100vh; }

  .topbar {
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    padding: 0 28px;
    height: 56px;
    display: flex; align-items: center; justify-content: space-between;
    position: sticky; top: 0; z-index: 50;
  }
  .topbar-title { font-size: 16px; font-weight: 700; color: var(--text); }
  .topbar-right { display: flex; align-items: center; gap: 16px; }
  .notif-btn {
    width: 36px; height: 36px; border-radius: 50%; border: 1px solid var(--border);
    background: var(--surface); display: flex; align-items: center; justify-content: center;
    cursor: pointer; position: relative;
  }
  .notif-dot { width: 8px; height: 8px; background: var(--danger); border-radius: 50%; position: absolute; top: 6px; right: 6px; border: 2px solid white; }
  .date-text { font-size: 13px; color: var(--text-muted); font-weight: 500; }
  .btn-keluar {
    display: flex; align-items: center; gap: 6px;
    padding: 7px 14px; border-radius: 8px;
    border: 1px solid var(--border);
    background: var(--surface); color: var(--text-muted);
    font-size: 13px; font-weight: 600; font-family: inherit; cursor: pointer; transition: all .15s;
  }
  .btn-keluar:hover { background: var(--bg); }

  .content { padding: 28px; flex: 1; }

  /* PAGE HEADER */
  .page-top {
    display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px;
  }
  .page-top h1 { font-size: 22px; font-weight: 800; color: var(--danger); margin-bottom: 4px; }
  .page-top p { font-size: 13px; color: var(--muted); }
  .btn-unduh {
    display: flex; align-items: center; gap: 7px;
    padding: 10px 18px; border-radius: 10px;
    background: linear-gradient(135deg, var(--danger), #DC2626);
    color: white; font-size: 13.5px; font-weight: 700;
    font-family: inherit; border: none; cursor: pointer; text-decoration: none;
    box-shadow: 0 4px 14px rgba(239,68,68,.3);
    transition: all .2s;
  }
  .btn-unduh:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(239,68,68,.4); }

  /* FILTER BAR */
  .filter-bar {
    background: var(--surface);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    padding: 20px;
    margin-bottom: 24px;
  }
  .filter-row { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
  .filter-group { display: flex; align-items: center; gap: 8px; }
  .filter-input {
    padding: 10px 14px; border-radius: 10px;
    border: 1.5px solid var(--border); background: var(--bg);
    font-family: inherit; font-size: 13px; color: var(--text);
    transition: border-color .15s;
  }
  .filter-input:focus { outline: none; border-color: var(--blue); }
  .filter-select { padding: 10px 14px; border-radius: 10px; border: 1.5px solid var(--border); background: var(--bg); font-family: inherit; font-size: 13px; cursor: pointer; }

  /* STAT CARDS */
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
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
  .stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,.06); }
  .stat-header { display: flex; align-items: center; gap: 8px; margin-bottom: 12px; }
  .stat-icon {
    width: 32px; height: 32px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
  }
  .stat-label-sm { font-size: 13px; font-weight: 600; color: #64748B; }
  .stat-value { font-size: 30px; font-weight: 800; margin-bottom: 4px; }
  .stat-sub { font-size: 12px; color: var(--muted); }

  .ic-danger { background: var(--danger-light); }
  .ic-danger svg { fill: var(--danger); }
  .ic-amber { background: #FEF3C7; }
  .ic-amber svg { fill: var(--amber); }
  .ic-blue { background: #EFF6FF; }
  .ic-blue svg { fill: var(--blue); }

  /* CHART */
  .chart-card {
    background: linear-gradient(135deg, var(--danger-light) 0%, #FFF5F5 100%);
    border-radius: var(--radius);
    border: 1px solid #FECACA;
    padding: 24px;
    margin-bottom: 24px;
  }
  .chart-title { font-size: 16px; font-weight: 700; color: var(--text); margin-bottom: 20px; }
  .chart-area { height: 200px; display: flex; align-items: flex-end; gap: 8px; margin-bottom: 8px; position: relative; }
  .chart-col { display: flex; flex-direction: column; align-items: center; flex: 1; gap: 6px; }
  .bar-wrap { flex: 1; display: flex; align-items: flex-end; width: 100%; }
  .bar {
    width: 100%; border-radius: 6px 6px 0 0;
    background: linear-gradient(180deg, var(--danger), #B91C1C);
    min-height: 4px;
    transition: opacity .2s;
  }
  .bar:hover { opacity: .8; }
  .bar-val { font-size: 11px; font-weight: 700; color: #7F1D1D; }
  .bar-label { font-size: 10px; color: #6B7280; }

  /* TABLE */
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
  
  .table-responsive {
      width: 100%;
      overflow-x: auto;
  }
  
  table { width: 100%; border-collapse: collapse; white-space: nowrap; }
  thead tr { background: var(--danger-light); }
  th {
    padding: 13px 20px;
    text-align: left;
    font-size: 11px; font-weight: 700;
    color: var(--danger); letter-spacing: .8px; text-transform: uppercase;
    border-bottom: 1.5px solid #FECACA;
  }
  td {
    padding: 14px 20px;
    font-size: 13.5px; color: var(--text);
    border-bottom: 1px solid var(--border);
  }
  tr:last-child td { border-bottom: none; }
  tbody tr:hover { background: #fafafa; }

  .font-mono { font-family: 'Monaco', monospace; }
  .text-danger { color: var(--danger) !important; font-weight: 700; }
  .text-muted { color: var(--muted); }

  .table-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 20px;
    border-top: 1px solid var(--border);
    font-size: 13px; color: var(--muted);
  }
</style>
</head>
<body>

@include('partials.sidebar')

<main class="main">
<div class="topbar">
  <span class="topbar-title">Laporan Transaksi Keluar</span>
  <div class="topbar-right">
    <span class="date-text">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}</span>
    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
      @csrf
      <button type="submit" class="btn-keluar">Keluar</button>
    </form>
  </div>
</div>

<div class="content">
  <div class="page-top">
  <div>
    <h1>Laporan Transaksi Keluar</h1>
    <p>Berikut Daftar data transaksi Keluar Persediaan</p>
  </div>
  <a href="{{ route('adminpersediaan.laporan-transaksi-keluar.pdf', request()->query()) }}" class="btn-unduh" target="_blank">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="white">
      <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/>
    </svg>
    Unduh PDF
  </a>
</div>

  {{-- FILTER --}}
  <div class="filter-bar">
    <form method="GET" action="{{ route('adminpersediaan.laporan-transaksi-keluar') }}">
      <div class="filter-row">
        <div class="filter-group">
          <input type="text" name="search" class="filter-input" placeholder="Cari kode barang, nama barang, no transaksi..." value="{{ request('search') }}" style="width: 280px;">
        </div>
        <div class="filter-group">
          <input type="date" name="tanggal_input" class="filter-input" value="{{ request('tanggal_input') }}">
        </div>
        <div class="filter-group">
          <select name="kode_kategori" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            @foreach(\App\Models\TransaksiKeluarPersediaan::distinct()->orderBy('kode_kategori')->pluck('kode_kategori')->toArray() as $kategori)
              <option value="{{ $kategori }}" {{ request('kode_kategori') == $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
            @endforeach
          </select>
        </div>
        @if(request()->hasAny(['search', 'tanggal_input', 'kode_kategori']))
          <a href="{{ route('adminpersediaan.laporan-transaksi-keluar') }}" class="filter-input" style="background:#FEF2F2;color:#EF4444;text-decoration:none;border-color:#FECACA;font-weight:600;">Reset</a>
        @endif
      </div>
    </form>
  </div>

  {{-- STATS CARDS --}}
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-header">
        <div class="stat-icon ic-danger">
          <svg width="16" height="16" viewBox="0 0 24 24"><path d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2zM9 14H7v-2h2v2zm4 0h-2v-2h2v2zm4 0h-2v-2h2v2zm0-4H7V8h10v2z"/></svg>
        </div>
        <span class="stat-label-sm">Total Transaksi Keluar</span>
      </div>
      <!-- Diubah dari $stats['total_transaksi'] menjadi $totalTransaksi -->
      <div class="stat-value text-danger">{{ number_format($totalTransaksi ?? 0, 0, ',', '.') }}</div>
      <div class="stat-sub">Data sesuai filter</div>
    </div>
    <div class="stat-card">
      <div class="stat-header">
        <div class="stat-icon ic-amber">
          <svg width="16" height="16" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
        </div>
        <span class="stat-label-sm">Total Nilai Keluar</span>
      </div>
      <!-- Diubah dari $stats['total_nilai'] menjadi $totalNilai -->
      <div class="stat-value" style="font-size:24px; color:var(--amber);">Rp {{ number_format($totalNilai ?? 0, 0, ',', '.') }}</div>
      <div class="stat-sub">Data sesuai filter</div>
    </div>
    <div class="stat-card">
      <div class="stat-header">
        <div class="stat-icon ic-blue">
          <svg width="16" height="16" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/></svg>
        </div>
        <span class="stat-label-sm">Total Item Keluar</span>
      </div>
      <!-- Diubah dari $stats['total_item'] menjadi $totalItem -->
      <div class="stat-value" style="color:var(--blue);">{{ number_format($totalItem ?? 0, 0, ',', '.') }}</div>
      <div class="stat-sub">Semua Satuan</div>
    </div>
  </div>

  {{-- TABLE --}}
    <div class="table-card">
      <div class="table-toolbar">
        <span style="font-size:13px;color:var(--muted);font-weight:600;">
          @if($transaksi instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {{ $transaksi->firstItem() ?? 0 }}–{{ $transaksi->lastItem() ?? 0 }} dari {{ $transaksi->total() }} data
          @else
            {{ $transaksi->count() }} data
          @endif
        </span>
      </div>
      
      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th width="5%">No</th>
              <th width="10%">Tanggal</th>
              <th width="10%">Kode Kategori</th>
              <th width="15%">Kategori</th>
              <th width="12%">Kode Barang</th>
              <th width="20%">Nama Barang</th>
              <th width="8%">Jml Keluar</th>
              <th width="10%">Harga</th>
              <th width="10%">Total</th>
            </tr>
          </thead>
          <tbody>
            @forelse($transaksi as $index => $item)
            <tr>
              <td>{{ ($transaksi instanceof \Illuminate\Pagination\LengthAwarePaginator) ? ($transaksi->currentPage() - 1) * $transaksi->perPage() + $loop->iteration : $loop->iteration }}</td>
              <td><strong>{{ \Carbon\Carbon::parse($item->tanggal_input)->format('d/m/Y') }}</strong></td>
              <td>{{ $item->kode_kategori ?? '-' }}</td>
              <td>{{ $item->kategori ?? '-' }}</td>
              <td class="font-mono"><strong>{{ $item->kode_barang ?? '-' }}</strong></td>
              <td>{{ Str::limit($item->nama_barang ?? '-', 35) }}</td>
              <!-- Diubah: Menambahkan pemanggilan relasi satuan ke persediaan ($item->persediaan->satuan) -->
              <td class="text-danger">
                  <strong>{{ number_format($item->jumlah_keluar ?? 0, 0, ',', '.') }} {{ $item->satuan }}</strong>
              </td>
              <td class="font-mono">Rp {{ number_format($item->harga ?? 0, 0, ',', '.') }}</td>
              <td class="font-mono text-danger"><strong>Rp {{ number_format($item->total ?? 0, 0, ',', '.') }}</strong></td>
            </tr>
            @empty
            <tr>
              <td colspan="10" style="text-align:center; padding:60px; color:var(--muted);">
                Belum ada data transaksi keluar pada filter ini.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="table-footer">
        <span style="font-size:13px;color:var(--muted);font-weight:600;">
          @if($transaksi instanceof \Illuminate\Pagination\LengthAwarePaginator)
            Menampilkan {{ $transaksi->firstItem() ?? 0 }}–{{ $transaksi->lastItem() ?? 0 }} dari {{ $transaksi->total() }} data
          @else
            {{ $transaksi->count() }} data lengkap
          @endif
        </span>
        
        @if($transaksi instanceof \Illuminate\Pagination\LengthAwarePaginator)
          <div>{{ $transaksi->appends(request()->query())->links() }}</div>
        @endif
      </div>
    </div>
  </div>
</main>

</body>
</html>