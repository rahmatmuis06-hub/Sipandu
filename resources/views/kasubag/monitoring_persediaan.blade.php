<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIPANDU - Monitoring Permintaan Persediaan</title>
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
  .filter-select, .filter-date {
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
    padding: 14px 18px; text-align: left; font-size: 11.5px;
    font-weight: 700; color: var(--primary); text-transform: uppercase;
    letter-spacing: 0.5px; border-bottom: 1px solid var(--border);
  }
  td {
    padding: 14px 18px; font-size: 13px;
    border-bottom: 1px solid var(--border); vertical-align: middle;
  }
  tbody tr:hover { background: #FAFAFC; }

  /* BADGES */
  .badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;
  }
  .badge-disetujui { background: var(--emerald-light); color: var(--emerald); }
  .badge-pending { background: var(--amber-light); color: var(--amber); }
  .badge-ditolak { background: var(--danger-light); color: var(--danger); }

  .btn-detail {
    padding: 6px 12px; border-radius: 8px; border: 1px solid var(--border);
    background: var(--surface); color: var(--text); font-size: 12px; font-weight: 600;
    cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
    transition: all 0.2s;
  }
  .btn-detail:hover { background: var(--primary-light); color: var(--primary); border-color: var(--primary); }

  /* MODAL */
  .modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px);
    z-index: 1000; align-items: center; justify-content: center; padding: 20px;
  }
  .modal-overlay.show { display: flex; }
  .modal-box {
    background: #fff; width: 100%; max-width: 650px;
    border-radius: 18px; padding: 26px; box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    max-height: 90vh; overflow-y: auto;
  }
  .modal-header {
    display: flex; justify-content: space-between; align-items: center;
    padding-bottom: 16px; border-bottom: 1px solid var(--border); margin-bottom: 18px;
  }
  .modal-title { font-size: 17px; font-weight: 800; color: var(--text); }
  .btn-close {
    background: none; border: none; font-size: 18px; color: var(--muted); cursor: pointer;
  }
  .btn-close:hover { color: var(--text); }
  .detail-row {
    display: grid; grid-template-columns: 140px 1fr; gap: 8px; margin-bottom: 12px; font-size: 13px;
  }
  .detail-row .lbl { color: var(--text-muted); font-weight: 600; }
  .detail-row .val { color: var(--text); }
  .items-table {
    width: 100%; border-collapse: collapse; margin-top: 14px;
    border: 1px solid var(--border); border-radius: 10px; overflow: hidden;
  }
  .items-table th { background: var(--bg); color: var(--text-muted); font-size: 11px; padding: 10px 14px; border-bottom: 1px solid var(--border); }
  .items-table td { padding: 10px 14px; font-size: 12.5px; border-bottom: 1px solid var(--border); }

  @media(max-width: 1024px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
  }
  @media(max-width: 768px) {
    .main { margin-left: 0; }
    .stats-grid { grid-template-columns: 1fr; }
    .content { padding: 20px 16px; }
  }
</style>
</head>
<body>

@include('partials.sidebar')

<main class="main">
  <div class="topbar">
    <div class="topbar-title">
      <i class="fas fa-boxes" style="color:var(--primary)"></i>
      Monitoring Permintaan Persediaan
    </div>
    <div class="date-text">
      <i class="fas fa-calendar-alt" style="margin-right: 6px;"></i>
      {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
    </div>
  </div>

  <div class="content">
    <div class="page-top">
      <div>
        <h1><i class="fas fa-chart-line" style="color:var(--primary);"></i> Monitoring Permintaan Persediaan</h1>
        <p>Pantau seluruh pengajuan barang persediaan pegawai beserta keputusan persetujuan dari Admin Persediaan secara real-time.</p>
      </div>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon" style="background:var(--blue-light); color:var(--blue);"><i class="fas fa-file-invoice"></i></div>
        <div class="stat-info">
          <div class="val">{{ number_format($stats['total'], 0, ',', '.') }}</div>
          <div class="lbl">Total Pengajuan</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:var(--emerald-light); color:var(--emerald);"><i class="fas fa-check-circle"></i></div>
        <div class="stat-info">
          <div class="val" style="color:var(--emerald);">{{ number_format($stats['disetujui'], 0, ',', '.') }}</div>
          <div class="lbl">Disetujui Admin</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:var(--amber-light); color:var(--amber);"><i class="fas fa-clock"></i></div>
        <div class="stat-info">
          <div class="val" style="color:var(--amber);">{{ number_format($stats['pending'], 0, ',', '.') }}</div>
          <div class="lbl">Menunggu / Diproses</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:var(--danger-light); color:var(--danger);"><i class="fas fa-times-circle"></i></div>
        <div class="stat-info">
          <div class="val" style="color:var(--danger);">{{ number_format($stats['ditolak'], 0, ',', '.') }}</div>
          <div class="lbl">Ditolak</div>
        </div>
      </div>
    </div>

    <!-- FILTER TOOLBAR -->
    <div class="filter-card">
      <form action="{{ route('kasubag.monitoring-persediaan') }}" method="GET" class="filter-form">
        <div class="search-box">
          <i class="fas fa-search" style="color:var(--muted)"></i>
          <input type="text" name="search" placeholder="Cari nama pemohon, barang, atau peruntukan..." value="{{ request('search') }}">
        </div>

        <select name="status" class="filter-select">
          <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua Status</option>
          <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Menunggu / Diproses</option>
          <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>✅ Disetujui</option>
          <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
        </select>

        <input type="date" name="start_date" class="filter-date" value="{{ request('start_date') }}" title="Tanggal Mulai">
        <span style="font-size:12px; color:var(--muted);">s/d</span>
        <input type="date" name="end_date" class="filter-date" value="{{ request('end_date') }}" title="Tanggal Selesai">

        <button type="submit" class="btn-filter"><i class="fas fa-filter"></i> Filter</button>
        @if(request()->anyFilled(['search', 'status', 'start_date', 'end_date']))
          <a href="{{ route('kasubag.monitoring-persediaan') }}" class="btn-reset"><i class="fas fa-rotate-left"></i> Reset</a>
        @endif
      </form>
    </div>

    <!-- TABLE -->
    <div class="table-card">
      <table>
        <thead>
          <tr>
            <th style="width: 50px;">No</th>
            <th>Tgl Pengajuan</th>
            <th>Pemohon</th>
            <th>Barang Diminta</th>
            <th>Jumlah Diminta</th>
            <th>Disetujui</th>
            <th>Tgl Penerimaan</th>
            <th>Status</th>
            <th>Berita Acara (BAST)</th>
            <th style="text-align: center;">Detail</th>
          </tr>
        </thead>
        <tbody>
          @forelse($permintaan as $index => $item)
            <tr>
              <td><strong>{{ $permintaan->firstItem() + $index }}</strong></td>
              <td>{{ $item->tanggal_permintaan?->format('d/m/Y') ?? ($item->created_at?->format('d/m/Y') ?? '-') }}</td>
              <td>
                <strong style="color:var(--text); display:block;">{{ $item->user->name ?? $item->nama_lengkap ?? 'Pegawai' }}</strong>
                <span style="font-size:11px; color:var(--text-muted);">{{ $item->user->nip ?? ($item->user->unit_kerja ?? '-') }}</span>
              </td>
              <td>
                @if($item->items && $item->items->count() > 0)
                  <strong style="color:var(--primary);">{{ $item->items->count() }} Macam Barang</strong>
                  <span style="display:block; font-size:11.5px; color:var(--text-muted);">
                    {{ Str::limit($item->items->pluck('nama_barang')->filter()->join(', '), 40) }}
                  </span>
                @else
                  <strong>{{ $item->nama_barang ?? ($item->persediaan->nama_barang ?? '-') }}</strong>
                @endif
              </td>
              <td>
                @if($item->items && $item->items->count() > 0)
                  <strong>{{ $item->items->sum('jumlah_diminta') }}</strong>
                @else
                  <strong>{{ $item->jumlah_diminta ?? 0 }}</strong> {{ $item->satuan ?? ($item->persediaan->satuan ?? '') }}
                @endif
              </td>
              <td>
                @if(in_array($item->status, ['disetujui', 'disetujui_kasubag']))
                  @if($item->items && $item->items->count() > 0)
                    <strong style="color:var(--emerald);">{{ $item->items->sum('jumlah_disetujui') }}</strong>
                  @else
                    <strong style="color:var(--emerald);">{{ $item->jumlah_disetujui ?? $item->jumlah_diminta }}</strong>
                  @endif
                @else
                  <span style="color:var(--muted);">-</span>
                @endif
              </td>
              <td>
                {{ $item->tanggal_penerimaan?->format('d/m/Y') ?? '-' }}
              </td>
              <td>
                @if(in_array($item->status, ['disetujui', 'disetujui_kasubag']))
                  <span class="badge badge-disetujui"><i class="fas fa-check-circle"></i> Disetujui</span>
                @elseif(in_array($item->status, ['ditolak', 'ditolak_kasubag']))
                  <span class="badge badge-ditolak"><i class="fas fa-times-circle"></i> Ditolak</span>
                @else
                  <span class="badge badge-pending"><i class="fas fa-clock"></i> Diproses</span>
                @endif
              </td>
              <td>
                @if(in_array($item->status, ['disetujui', 'disetujui_kasubag']))
                  <div style="display:flex; flex-direction:column; gap:4px;">
                    <form action="{{ route('adminpersediaan.surat-permintaan', $item->id) }}" method="GET" target="_blank" style="display:flex; gap:4px; align-items:center;">
                      <input type="date" name="tanggal_surat" value="{{ optional($item->tanggal_penerimaan)->format('Y-m-d') ?? now()->format('Y-m-d') }}" required title="Tanggal surat BAST" style="padding:4px 6px; border:1px solid var(--border); border-radius:6px; font-size:11px; max-width:115px;">
                      <button type="submit" class="btn-detail" style="color:var(--primary); font-size:11px; padding:4px 8px; white-space:nowrap;" title="Cetak Berita Acara / Surat BAST">
                        <i class="fas fa-file-pdf"></i> Cetak BAST
                      </button>
                    </form>
                    @if(!empty($item->surat_bast_path))
                      <a href="{{ asset('storage/' . $item->surat_bast_path) }}" target="_blank" class="btn-detail" style="color:var(--emerald); font-size:11px; padding:4px 8px; text-decoration:none;" title="Lihat BAST Terunggah">
                        <i class="fas fa-file-circle-check"></i> BAST Fisik
                      </a>
                    @endif
                  </div>
                @else
                  <span style="font-size:11.5px; color:var(--muted); font-style:italic;">Belum tersedia</span>
                @endif
              </td>
              <td style="text-align: center;">
                <button type="button" class="btn-detail" onclick="openDetailModal({{ $item->id }})">
                  <i class="fas fa-eye"></i> Detail
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="10" style="text-align:center; padding: 48px; color:var(--muted);">
                <i class="fas fa-box-open" style="font-size:32px; margin-bottom:12px; display:block; opacity:0.4;"></i>
                Tidak ada data permintaan persediaan yang sesuai filter.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>

      @if($permintaan->hasPages())
        <div style="padding: 16px 20px; border-top: 1px solid var(--border);">
          {{ $permintaan->links() }}
        </div>
      @endif
    </div>
  </div>
</main>

<!-- MODAL DETAIL -->
<div class="modal-overlay" id="modalDetail">
  <div class="modal-box">
    <div class="modal-header">
      <div class="modal-title"><i class="fas fa-info-circle" style="color:var(--primary); margin-right:6px;"></i> Detail Permintaan Persediaan</div>
      <button type="button" class="btn-close" onclick="closeDetailModal()">&times;</button>
    </div>
    
    <div id="modalLoading" style="text-align:center; padding:30px; color:var(--muted);">
      <i class="fas fa-circle-notch fa-spin" style="font-size:24px;"></i> Memuat detail...
    </div>

    <div id="modalContent" style="display:none;">
      <div class="detail-row"><div class="lbl">Tgl Pengajuan</div><div class="val" id="detTgl">-</div></div>
      <div class="detail-row"><div class="lbl">Nama Pemohon</div><div class="val" id="detPemohon">-</div></div>
      <div class="detail-row"><div class="lbl">Unit Kerja / NIP</div><div class="val" id="detUnit">-</div></div>
      <div class="detail-row"><div class="lbl">Peruntukan</div><div class="val" id="detTujuan">-</div></div>
      <div class="detail-row"><div class="lbl">Status</div><div class="val" id="detStatus">-</div></div>
      <div class="detail-row"><div class="lbl">Tgl Penerimaan</div><div class="val" id="detTglTerima">-</div></div>

      <div style="margin-top:16px;">
        <strong style="font-size:13px; color:var(--text);">Rincian Barang yang Diminta:</strong>
        <table class="items-table">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama Barang</th>
              <th>Diminta</th>
              <th>Disetujui</th>
              <th>Satuan</th>
            </tr>
          </thead>
          <tbody id="detItemsBody">
          </tbody>
        </table>
      </div>

      <div id="detBastSection" style="margin-top:18px; padding:14px; background:var(--bg); border:1px solid var(--border); border-radius:10px; display:flex; justify-content:space-between; align-items:center;">
        <div>
          <strong style="font-size:13px; display:block; color:var(--text);">Berita Acara (BAST)</strong>
          <span style="font-size:11.5px; color:var(--text-muted);">Dokumen serah terima barang persediaan</span>
        </div>
        <div style="display:flex; gap:8px;" id="detBastLinks">
          <!-- Dinamis diisi JS -->
        </div>
      </div>

      <div style="margin-top:20px; text-align:right;">
        <button type="button" class="btn-filter" onclick="closeDetailModal()">Tutup</button>
      </div>
    </div>
  </div>
</div>

<script>
  function openDetailModal(id) {
    const modal = document.getElementById('modalDetail');
    const loading = document.getElementById('modalLoading');
    const content = document.getElementById('modalContent');
    
    modal.classList.add('show');
    loading.style.display = 'block';
    content.style.display = 'none';

    fetch(`/kasubag/monitoring-persediaan/${id}/json`)
      .then(res => res.json())
      .then(res => {
        if(res.success && res.data) {
          const d = res.data;
          document.getElementById('detTgl').innerText = d.tanggal_permintaan ? new Date(d.tanggal_permintaan).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'}) : '-';
          document.getElementById('detPemohon').innerText = d.user ? d.user.name : (d.nama_lengkap || '-');
          document.getElementById('detUnit').innerText = (d.user && d.user.nip ? 'NIP: ' + d.user.nip : '') + (d.user && d.user.unit_kerja ? ' (' + d.user.unit_kerja + ')' : '');
          document.getElementById('detTujuan').innerText = d.tujuan_penggunaan || '-';
          
          let stBadge = '<span class="badge badge-pending">⏳ Diproses</span>';
          if(['disetujui', 'disetujui_kasubag'].includes(d.status)) {
            stBadge = '<span class="badge badge-disetujui">✅ Disetujui</span>';
          } else if(['ditolak', 'ditolak_kasubag'].includes(d.status)) {
            stBadge = '<span class="badge badge-ditolak">❌ Ditolak</span>';
          }
          document.getElementById('detStatus').innerHTML = stBadge;
          document.getElementById('detTglTerima').innerText = d.tanggal_penerimaan ? new Date(d.tanggal_penerimaan).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'}) : '-';

          const tbody = document.getElementById('detItemsBody');
          tbody.innerHTML = '';

          if(d.items && d.items.length > 0) {
            d.items.forEach((it, idx) => {
              const row = `<tr>
                <td>${idx + 1}</td>
                <td><strong>${it.nama_barang || (it.persediaan ? it.persediaan.nama_barang : '-')}</strong></td>
                <td>${it.jumlah_diminta || 0}</td>
                <td><strong style="color:var(--emerald);">${it.jumlah_disetujui ?? it.jumlah_diminta ?? 0}</strong></td>
                <td>${it.satuan || (it.persediaan ? it.persediaan.satuan : '-')}</td>
              </tr>`;
              tbody.insertAdjacentHTML('beforeend', row);
            });
          } else {
            const row = `<tr>
              <td>1</td>
              <td><strong>${d.nama_barang || (d.persediaan ? d.persediaan.nama_barang : '-')}</strong></td>
              <td>${d.jumlah_diminta || 0}</td>
              <td><strong style="color:var(--emerald);">${d.jumlah_disetujui ?? d.jumlah_diminta ?? 0}</strong></td>
              <td>${d.satuan || (d.persediaan ? d.persediaan.satuan : '-')}</td>
            </tr>`;
            tbody.insertAdjacentHTML('beforeend', row);
          }

          // Tampilkan tombol BAST jika disetujui
          const bastLinks = document.getElementById('detBastLinks');
          const bastSection = document.getElementById('detBastSection');
          bastLinks.innerHTML = '';

          if(['disetujui', 'disetujui_kasubag'].includes(d.status)) {
            bastSection.style.display = 'flex';
            let tglSuratVal = d.tanggal_penerimaan ? d.tanggal_penerimaan.split('T')[0] : new Date().toISOString().split('T')[0];
            bastLinks.innerHTML += `<a href="/adminpersediaan/surat/${d.id}?tanggal_surat=${tglSuratVal}" target="_blank" class="btn-detail" style="color:var(--primary); font-size:12px;">
              <i class="fas fa-file-pdf"></i> Cetak BAST
            </a>`;
            if(d.surat_bast_path) {
              bastLinks.innerHTML += `<a href="/storage/${d.surat_bast_path}" target="_blank" class="btn-detail" style="color:var(--emerald); font-size:12px;">
                <i class="fas fa-file-circle-check"></i> BAST Fisik
              </a>`;
            }
          } else {
            bastSection.style.display = 'none';
          }

          loading.style.display = 'none';
          content.style.display = 'block';
        }
      })
      .catch(err => {
        loading.innerHTML = '<span style="color:var(--danger)">Gagal memuat data detail.</span>';
      });
  }

  function closeDetailModal() {
    document.getElementById('modalDetail').classList.remove('show');
  }

  document.getElementById('modalDetail').addEventListener('click', function(e) {
    if(e.target === this) closeDetailModal();
  });
</script>
</body>
</html>
