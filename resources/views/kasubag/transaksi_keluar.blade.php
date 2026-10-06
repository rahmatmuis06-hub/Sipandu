<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIPANDU - Monitoring Transaksi Keluar</title>
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
  .header-actions { display: flex; align-items: center; gap: 12px; }

  .btn-laporan {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 18px; border-radius: 10px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white; font-size: 13px; font-weight: 700;
    text-decoration: none; box-shadow: 0 4px 12px rgba(79,70,229,0.25);
    transition: all .2s;
  }
  .btn-laporan:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(79,70,229,0.35); }

  /* SOURCE TABS */
  .source-tabs {
    display: flex; gap: 8px; background: #E2E8F0; padding: 5px; border-radius: 12px; width: fit-content; margin-bottom: 24px;
  }
  .source-tab {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 8px 18px; border-radius: 8px; font-size: 13px; font-weight: 700;
    text-decoration: none; color: var(--text-muted); transition: all .2s;
  }
  .source-tab.active {
    background: var(--surface); color: var(--primary); box-shadow: 0 2px 8px rgba(0,0,0,0.06);
  }

  /* STATS CARDS */
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
  .stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,.05); }
  .stat-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
  .stat-icon {
    width: 36px; height: 36px; border-radius: 10px;
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
    padding: 16px 20px;
    margin-bottom: 24px;
  }
  .filter-row { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }
  .filter-group { display: flex; align-items: center; gap: 8px; }
  .filter-input {
    padding: 9px 14px; border-radius: 10px;
    border: 1.5px solid var(--border); background: var(--bg);
    font-family: inherit; font-size: 13px; color: var(--text);
    transition: border-color .15s;
  }
  .filter-input:focus { outline: none; border-color: var(--primary); }
  .filter-select {
    padding: 9px 14px; border-radius: 10px; border: 1.5px solid var(--border);
    background: var(--bg); font-family: inherit; font-size: 13px; cursor: pointer;
  }
  .btn-filter {
    background: var(--primary); color: #fff; border: none; padding: 9px 16px;
    border-radius: 10px; font-size: 13px; font-weight: 600; cursor: pointer;
    display: inline-flex; align-items: center; gap: 6px;
  }
  .btn-reset {
    background: var(--danger-light); color: var(--danger); border: 1px solid #FECACA;
    padding: 9px 14px; border-radius: 10px; font-size: 13px; font-weight: 600;
    text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
  }

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

  .btn-detail {
    padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600;
    border: 1px solid var(--border); background: var(--surface); color: var(--text);
    cursor: pointer; transition: all .15s; display: inline-flex; align-items: center; gap: 4px;
  }
  .btn-detail:hover { background: var(--primary-light); color: var(--primary); border-color: #C7D2FE; }

  .table-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 22px; border-top: 1px solid var(--border); font-size: 13px; color: var(--text-muted);
  }

  /* MODAL */
  .modal-overlay {
    position: fixed; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(2px);
    display: none; align-items: center; justify-content: center; z-index: 1000;
  }
  .modal-overlay.active { display: flex; }
  .modal-card {
    background: #fff; border-radius: 16px; width: 90%; max-width: 580px; max-height: 90vh;
    overflow-y: auto; padding: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.2);
  }
  .modal-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px; border-bottom: 1px solid var(--border); padding-bottom: 12px; }
  .modal-title { font-size: 16px; font-weight: 700; color: var(--text); }
  .modal-close { background: none; border: none; font-size: 18px; color: var(--muted); cursor: pointer; }
  .detail-row { display: flex; padding: 8px 0; border-bottom: 1px dashed #f1f5f9; font-size: 13px; }
  .detail-label { width: 150px; color: var(--text-muted); font-weight: 600; }
  .detail-value { flex: 1; color: var(--text); font-weight: 500; }
</style>
</head>
<body>

@include('partials.sidebar')

<main class="main">
  <div class="topbar">
    <span class="topbar-title">
      <i class="fas fa-dolly" style="color:var(--primary)"></i> Monitoring Transaksi Keluar
    </span>
    <div class="topbar-right">
      <span class="date-text">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}</span>
    </div>
  </div>

  <div class="content">
    <div class="page-top">
      <div>
        <h1>Monitoring Transaksi Keluar</h1>
        <p>Pantau seluruh riwayat barang dan aset yang dikeluarkan secara berkala.</p>
      </div>
      <div class="header-actions">
        <a href="{{ route('kasubag.laporan-transaksi-keluar', ['sumber' => $sumber]) }}" class="btn-laporan">
          <i class="fas fa-file-invoice"></i> Buka Laporan Transaksi Keluar
        </a>
      </div>
    </div>

    <!-- SOURCE SWITCHER -->
    <div class="source-tabs">
      <a href="{{ route('kasubag.transaksi-keluar', ['sumber' => 'persediaan']) }}" class="source-tab {{ $sumber === 'persediaan' ? 'active' : '' }}">
        <i class="fas fa-boxes"></i> Persediaan (Barang Habis Pakai)
      </a>
      <a href="{{ route('kasubag.transaksi-keluar', ['sumber' => 'aset_tetap']) }}" class="source-tab {{ $sumber === 'aset_tetap' ? 'active' : '' }}">
        <i class="fas fa-cubes"></i> Aset Tetap (BMN)
      </a>
    </div>

    <!-- STATS CARDS -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-header">
          <span class="stat-label-sm">Total Transaksi Keluar</span>
          <div class="stat-icon" style="background:#EFF6FF;color:#3B82F6;"><i class="fas fa-exchange-alt"></i></div>
        </div>
        <div class="stat-value" style="color:#3B82F6;">{{ number_format($currentTotalTrx, 0, ',', '.') }}</div>
        <div class="stat-sub">Berdasarkan filter aktif</div>
      </div>

      <div class="stat-card">
        <div class="stat-header">
          <span class="stat-label-sm">Total Kuantitas / Volume</span>
          <div class="stat-icon" style="background:#ECFDF5;color:#10B981;"><i class="fas fa-box-open"></i></div>
        </div>
        <div class="stat-value" style="color:#10B981;">{{ number_format($currentTotalItem, 0, ',', '.') }} <span style="font-size:14px;color:var(--muted);font-weight:500;">Unit</span></div>
        <div class="stat-sub">Volume fisik keluar</div>
      </div>

      <div class="stat-card">
        <div class="stat-header">
          <span class="stat-label-sm">Total Akumulasi Nilai</span>
          <div class="stat-icon" style="background:#FFFBEB;color:#F59E0B;"><i class="fas fa-money-bill-wave"></i></div>
        </div>
        <div class="stat-value" style="color:#D97706;font-size:22px;">Rp {{ number_format($currentTotalNilai, 0, ',', '.') }}</div>
        <div class="stat-sub">Nilai rupiah pengeluaran</div>
      </div>

      <div class="stat-card">
        <div class="stat-header">
          <span class="stat-label-sm">Total Seluruh Transaksi</span>
          <div class="stat-icon" style="background:#EEF2FF;color:#4F46E5;"><i class="fas fa-database"></i></div>
        </div>
        <div class="stat-value" style="color:#4F46E5;">{{ number_format($totalSemuaTrx, 0, ',', '.') }}</div>
        <div class="stat-sub">Persediaan + Aset Tetap</div>
      </div>
    </div>

    <!-- FILTER BAR -->
    <div class="filter-bar">
      <form method="GET" action="{{ route('kasubag.transaksi-keluar') }}">
        <input type="hidden" name="sumber" value="{{ $sumber }}">
        <div class="filter-row">
          <div class="filter-group">
            <input type="text" name="search" class="filter-input" placeholder="Cari kode, nama barang, NUP..." value="{{ request('search') }}" style="width: 260px;">
          </div>
          <div class="filter-group">
            <input type="date" name="tanggal_input" class="filter-input" value="{{ request('tanggal_input') }}" title="Tanggal Pengeluaran">
          </div>
          @if(isset($kategoriList) && count($kategoriList) > 0)
          <div class="filter-group">
            <select name="kategori" class="filter-select" onchange="this.form.submit()">
              <option value="">Semua {{ $sumber === 'aset_tetap' ? 'Merek' : 'Kategori' }}</option>
              @foreach($kategoriList as $kat)
                <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
              @endforeach
            </select>
          </div>
          @endif
          <button type="submit" class="btn-filter">
            <i class="fas fa-search"></i> Cari
          </button>
          @if(request()->hasAny(['search', 'tanggal_input', 'kategori']))
            <a href="{{ route('kasubag.transaksi-keluar', ['sumber' => $sumber]) }}" class="btn-reset">
              <i class="fas fa-times"></i> Reset
            </a>
          @endif
        </div>
      </form>
    </div>

    <!-- TABLE -->
    <div class="table-card">
      <div class="table-toolbar">
        <span style="font-size:13px; font-weight:600; color:var(--text-muted);">
          @if($listData instanceof \Illuminate\Pagination\LengthAwarePaginator)
            Menampilkan {{ $listData->firstItem() ?? 0 }}–{{ $listData->lastItem() ?? 0 }} dari {{ $listData->total() }} data transaksi keluar
          @else
            {{ $listData->count() }} data
          @endif
        </span>
        <span class="badge {{ $sumber === 'persediaan' ? 'badge-primary' : 'badge-emerald' }}">
          {{ $sumber === 'persediaan' ? 'Barang Persediaan' : 'Aset Tetap / BMN' }}
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
                <th width="8%">Aksi</th>
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
                <th width="8%">Aksi</th>
              </tr>
            @endif
          </thead>
          <tbody>
            @forelse($listData as $item)
              @if($sumber === 'aset_tetap')
                <tr>
                  <td>{{ ($listData instanceof \Illuminate\Pagination\LengthAwarePaginator) ? ($listData->currentPage() - 1) * $listData->perPage() + $loop->iteration : $loop->iteration }}</td>
                  <td><strong>{{ $item->tanggal_input ? \Carbon\Carbon::parse($item->tanggal_input)->format('d/m/Y') : '-' }}</strong></td>
                  <td class="font-mono"><strong>{{ $item->kode_barang ?? '-' }}</strong></td>
                  <td class="font-mono"><span class="badge badge-amber">{{ $item->nup ?? '-' }}</span></td>
                  <td><strong>{{ $item->nama_barang ?? '-' }}</strong></td>
                  <td>{{ $item->merek ?? '-' }}</td>
                  <td>{{ $item->lokasi ?? '-' }}</td>
                  <td class="font-mono" style="font-weight:700; color:#D97706;">Rp {{ number_format($item->nilai_perolehan ?? 0, 0, ',', '.') }}</td>
                  <td class="font-mono" style="font-size:11px;">{{ $item->nomor_sk ?? '-' }}</td>
                  <td>
                    <button type="button" class="btn-detail" onclick="openDetailModalAset({{ json_encode($item) }})">
                      <i class="fas fa-eye"></i> Detail
                    </button>
                  </td>
                </tr>
              @else
                <tr>
                  <td>{{ ($listData instanceof \Illuminate\Pagination\LengthAwarePaginator) ? ($listData->currentPage() - 1) * $listData->perPage() + $loop->iteration : $loop->iteration }}</td>
                  <td><strong>{{ $item->tanggal_input ? \Carbon\Carbon::parse($item->tanggal_input)->format('d/m/Y') : '-' }}</strong></td>
                  <td class="font-mono"><strong>{{ $item->kode_unik_barang ?? $item->kode_barang ?? '-' }}</strong></td>
                  <td><strong>{{ $item->nama_barang ?? '-' }}</strong></td>
                  <td><span class="badge badge-primary">{{ $item->kategori ?? '-' }}</span></td>
                  <td>
                    <strong style="color:var(--danger);">{{ number_format($item->jumlah_keluar ?? 0, 0, ',', '.') }}</strong>
                    <span style="font-size:11px;color:var(--muted);">{{ $item->satuan ?? 'Unit' }}</span>
                  </td>
                  <td class="font-mono">Rp {{ number_format($item->harga ?? 0, 0, ',', '.') }}</td>
                  <td class="font-mono" style="font-weight:700; color:#D97706;">Rp {{ number_format($item->total ?? 0, 0, ',', '.') }}</td>
                  <td>
                    <button type="button" class="btn-detail" onclick="openDetailModalPersediaan({{ json_encode($item) }})">
                      <i class="fas fa-eye"></i> Detail
                    </button>
                  </td>
                </tr>
              @endif
            @empty
              <tr>
                <td colspan="{{ $sumber === 'aset_tetap' ? 10 : 9 }}" style="text-align:center; padding: 50px 20px; color:var(--muted);">
                  <i class="fas fa-inbox" style="font-size:36px; margin-bottom:12px; display:block; opacity:0.5;"></i>
                  Tidak ada data transaksi keluar pada filter yang dipilih.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="table-footer">
        <div>
          @if($listData instanceof \Illuminate\Pagination\LengthAwarePaginator)
            Halaman {{ $listData->currentPage() }} dari {{ $listData->lastPage() }}
          @endif
        </div>
        @if($listData instanceof \Illuminate\Pagination\LengthAwarePaginator)
          <div>{{ $listData->links() }}</div>
        @endif
      </div>
    </div>
  </div>
</main>

<!-- MODAL DETAIL -->
<div class="modal-overlay" id="detailModal" onclick="closeModal(event)">
  <div class="modal-card" onclick="event.stopPropagation()">
    <div class="modal-header">
      <h3 class="modal-title" id="modalTitle">Detail Transaksi Keluar</h3>
      <button class="modal-close" onclick="closeModal()">&times;</button>
    </div>
    <div id="modalBody"></div>
  </div>
</div>

<script>
function openDetailModalPersediaan(item) {
  document.getElementById('modalTitle').innerText = 'Detail Transaksi Keluar (Persediaan)';
  let html = `
    <div class="detail-row"><div class="detail-label">Tanggal Keluar</div><div class="detail-value">${item.tanggal_input || '-'}</div></div>
    <div class="detail-row"><div class="detail-label">Kode Barang</div><div class="detail-value font-mono"><b>${item.kode_barang || '-'}</b></div></div>
    <div class="detail-row"><div class="detail-label">Nama Barang</div><div class="detail-value"><b>${item.nama_barang || '-'}</b></div></div>
    <div class="detail-row"><div class="detail-label">Kategori</div><div class="detail-value">${item.kategori || '-'} (${item.kode_kategori || '-'})</div></div>
    <div class="detail-row"><div class="detail-label">Jumlah Keluar</div><div class="detail-value"><b>${item.jumlah_keluar || 0} ${item.satuan || 'Unit'}</b></div></div>
    <div class="detail-row"><div class="detail-label">Harga Satuan</div><div class="detail-value font-mono">Rp ${Number(item.harga || 0).toLocaleString('id-ID')}</div></div>
    <div class="detail-row"><div class="detail-label">Total Nilai</div><div class="detail-value font-mono" style="color:#D97706; font-weight:700;">Rp ${Number(item.total || 0).toLocaleString('id-ID')}</div></div>
    <div class="detail-row"><div class="detail-label">Keterangan</div><div class="detail-value">${item.keterangan || '-'}</div></div>
  `;
  document.getElementById('modalBody').innerHTML = html;
  document.getElementById('detailModal').classList.add('active');
}

function openDetailModalAset(item) {
  document.getElementById('modalTitle').innerText = 'Detail Transaksi Keluar (Aset Tetap / BMN)';
  let html = `
    <div class="detail-row"><div class="detail-label">Tanggal Keluar</div><div class="detail-value">${item.tanggal_input || '-'}</div></div>
    <div class="detail-row"><div class="detail-label">Kode Barang</div><div class="detail-value font-mono"><b>${item.kode_barang || '-'}</b></div></div>
    <div class="detail-row"><div class="detail-label">NUP</div><div class="detail-value font-mono">${item.nup || '-'}</div></div>
    <div class="detail-row"><div class="detail-label">Nama Barang</div><div class="detail-value"><b>${item.nama_barang || '-'}</b></div></div>
    <div class="detail-row"><div class="detail-label">Merek</div><div class="detail-value">${item.merek || '-'}</div></div>
    <div class="detail-row"><div class="detail-label">Lokasi Asal</div><div class="detail-value">${item.lokasi || '-'}</div></div>
    <div class="detail-row"><div class="detail-label">Nilai Perolehan</div><div class="detail-value font-mono" style="color:#D97706; font-weight:700;">Rp ${Number(item.nilai_perolehan || 0).toLocaleString('id-ID')}</div></div>
    <div class="detail-row"><div class="detail-label">Nomor SK</div><div class="detail-value">${item.nomor_sk || '-'}</div></div>
    <div class="detail-row"><div class="detail-label">Tanggal SK</div><div class="detail-value">${item.tanggal_sk || '-'}</div></div>
    <div class="detail-row"><div class="detail-label">Keterangan</div><div class="detail-value">${item.keterangan || '-'}</div></div>
  `;
  document.getElementById('modalBody').innerHTML = html;
  document.getElementById('detailModal').classList.add('active');
}

function closeModal(event) {
  if (!event || event.target.id === 'detailModal' || event.target.classList.contains('modal-close')) {
    document.getElementById('detailModal').classList.remove('active');
  }
}
</script>

</body>
</html>
