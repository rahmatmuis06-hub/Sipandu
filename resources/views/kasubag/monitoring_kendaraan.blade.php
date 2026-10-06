<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIPANDU - Monitoring Kendaraan Dinas</title>
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

  .btn-primary-link {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 18px; border-radius: 10px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: #fff; text-decoration: none; font-size: 13px; font-weight: 600;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
    transition: all 0.2s;
  }
  .btn-primary-link:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(79, 70, 229, 0.35); }

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

  /* ACTIVE LOANS CARD */
  .active-loans-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 22px;
    margin-bottom: 24px;
  }
  .section-title {
    font-size: 16px; font-weight: 700; color: var(--text);
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 16px;
  }
  .active-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 14px;
  }
  .active-item {
    background: #FAFAFC; border: 1.5px solid #EEF2F6;
    border-radius: 12px; padding: 16px;
    display: flex; flex-direction: column; gap: 8px;
    position: relative; border-left: 4px solid var(--blue);
  }
  .active-item-top { display: flex; justify-content: space-between; align-items: center; }
  .active-car { font-size: 14px; font-weight: 700; color: var(--text); }
  .active-badge { font-size: 10.5px; font-weight: 700; padding: 3px 8px; border-radius: 12px; background: var(--blue-light); color: var(--blue); }
  .active-user { font-size: 12.5px; color: var(--text-muted); }
  .active-date { font-size: 11.5px; color: var(--text); font-weight: 600; display: flex; align-items: center; gap: 6px; }

  /* FILTER CARD */
  .filter-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 18px 20px;
    margin-bottom: 20px;
  }
  .filter-form { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
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
    display: inline-flex; align-items: center; gap: 4px;
    padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;
  }
  .badge-disetujui { background: var(--emerald-light); color: var(--emerald); }
  .badge-pending { background: var(--amber-light); color: var(--amber); }
  .badge-ditolak { background: var(--danger-light); color: var(--danger); }
  .badge-dikembalikan { background: var(--blue-light); color: var(--blue); }

  .btn-detail {
    padding: 6px 12px; border-radius: 8px; border: 1px solid var(--border);
    background: var(--surface); color: var(--text); font-size: 12px; font-weight: 600;
    cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
    transition: all 0.2s;
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
      <i class="fas fa-car-side" style="color:var(--primary)"></i>
      Monitoring Kendaraan Dinas
    </div>
    <div class="date-text">
      <i class="fas fa-calendar-alt" style="margin-right: 6px;"></i>
      {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
    </div>
  </div>

  <div class="content">
    <div class="page-top">
      <div>
        <h1><i class="fas fa-car" style="color:var(--primary);"></i> Monitoring Kendaraan Dinas</h1>
        <p>Pantau seluruh operasional armada kendaraan dinas, peminjaman aktif, peruntukan dinas, dan riwayat pemakaian.</p>
      </div>
      <a href="{{ route('kasubag.persetujuan-peminjaman-kendaraan') }}" class="btn-primary-link">
        <i class="fas fa-check-to-slot"></i> Buka Persetujuan Kendaraan &rarr;
      </a>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon" style="background:var(--blue-light); color:var(--blue);"><i class="fas fa-car"></i></div>
        <div class="stat-info">
          <div class="val">{{ number_format($stats['totalUnit'], 0, ',', '.') }}</div>
          <div class="lbl">Total Unit Terdaftar</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:var(--amber-light); color:var(--amber);"><i class="fas fa-key"></i></div>
        <div class="stat-info">
          <div class="val" style="color:var(--amber);">{{ number_format($stats['sedangDipinjam'], 0, ',', '.') }}</div>
          <div class="lbl">Sedang Digunakan</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:var(--primary-light); color:var(--primary);"><i class="fas fa-clock"></i></div>
        <div class="stat-info">
          <div class="val" style="color:var(--primary);">{{ number_format($stats['menunggu'], 0, ',', '.') }}</div>
          <div class="lbl">Menunggu Verifikasi</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:var(--emerald-light); color:var(--emerald);"><i class="fas fa-flag-checkered"></i></div>
        <div class="stat-info">
          <div class="val" style="color:var(--emerald);">{{ number_format($stats['selesai'], 0, ',', '.') }}</div>
          <div class="lbl">Selesai / Dikembalikan</div>
        </div>
      </div>
    </div>

    <!-- KENDARAAN SEDANG DIGUNAKAN (ACTIVE LOANS) -->
    <div class="active-loans-card">
      <div class="section-title">
        <span><i class="fas fa-route" style="color:var(--primary); margin-right:8px;"></i> Kendaraan Sedang Digunakan Saat Ini</span>
        <span style="font-size:12px; color:var(--text-muted); font-weight:500;">{{ $peminjamanAktif->count() }} Kendaraan Aktif</span>
      </div>

      @if($peminjamanAktif->count() > 0)
        <div class="active-grid">
          @foreach($peminjamanAktif as $akt)
            <div class="active-item">
              <div class="active-item-top">
                <span class="active-car">{{ $akt->nama_barang ?? ($akt->merek ?? 'Kendaraan Dinas') }}</span>
                <span class="active-badge"><i class="fas fa-circle-dot"></i> Dipinjam</span>
              </div>
              <div class="active-user">
                <i class="fas fa-user" style="color:var(--muted); margin-right:4px;"></i>
                <strong>{{ $akt->user->name ?? 'Pegawai' }}</strong>
              </div>
              <div style="font-size:12px; color:var(--text-muted);">
                <i class="fas fa-bullseye" style="color:var(--muted); margin-right:4px;"></i>
                {{ Str::limit($akt->deskripsi_peruntukan ?? 'Keperluan Dinas', 36) }}
              </div>
              <div class="active-date">
                <i class="fas fa-calendar-check" style="color:var(--blue);"></i>
                Kembali: {{ $akt->tanggal_pengembalian?->format('d M Y') ?? '-' }}
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div style="text-align:center; padding:24px; color:var(--muted); font-size:13px;">
          <i class="fas fa-square-parking" style="font-size:28px; margin-bottom:8px; display:block; opacity:0.4;"></i>
          Seluruh unit kendaraan dinas saat ini sedang berada di kantor (Tersedia).
        </div>
      @endif
    </div>

    <!-- FILTER TOOLBAR -->
    <div class="filter-card">
      <form action="{{ route('kasubag.monitoring-kendaraan') }}" method="GET" class="filter-form">
        <div class="search-box">
          <i class="fas fa-search" style="color:var(--muted)"></i>
          <input type="text" name="search" placeholder="Cari kendaraan, pemohon, tujuan dinas..." value="{{ request('search') }}">
        </div>

        <select name="status" class="filter-select">
          <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua Status</option>
          <option value="diteruskan_kasubag" {{ request('status') == 'diteruskan_kasubag' ? 'selected' : '' }}>⏳ Menunggu Kasubag</option>
          <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>✅ Disetujui</option>
          <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>🔵 Dikembalikan</option>
          <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
        </select>

        <input type="date" name="start_date" class="filter-date" value="{{ request('start_date') }}" title="Tanggal Pinjam Dari">
        <span style="font-size:12px; color:var(--muted);">s/d</span>
        <input type="date" name="end_date" class="filter-date" value="{{ request('end_date') }}" title="Tanggal Pinjam Sampai">

        <button type="submit" class="btn-filter"><i class="fas fa-filter"></i> Filter</button>
        @if(request()->anyFilled(['search', 'status', 'start_date', 'end_date']))
          <a href="{{ route('kasubag.monitoring-kendaraan') }}" class="btn-reset"><i class="fas fa-rotate-left"></i> Reset</a>
        @endif
      </form>
    </div>

    <!-- TABLE RIWAYAT PEMINJAMAN -->
    <div class="table-card">
      <table>
        <thead>
          <tr>
            <th style="width: 50px;">No</th>
            <th>Tgl Pengajuan</th>
            <th>Kendaraan Dinas</th>
            <th>Pemohon</th>
            <th>Jadwal Peminjaman</th>
            <th>Peruntukan / Tujuan</th>
            <th>Status</th>
            <th>Berita Acara (BAST)</th>
            <th style="text-align: center;">Detail</th>
          </tr>
        </thead>
        <tbody>
          @forelse($riwayatPeminjaman as $index => $item)
            <tr>
              <td><strong>{{ $riwayatPeminjaman->firstItem() + $index }}</strong></td>
              <td>{{ $item->created_at?->format('d/m/Y') ?? '-' }}</td>
              <td>
                <strong style="color:var(--text); display:block;">{{ $item->nama_barang ?? ($item->merek ?? 'Kendaraan Dinas') }}</strong>
                <span style="font-size:11px; color:var(--text-muted);">
                  {{ $item->nomor_polisi_saat_pinjam ?? ($item->kode_barang ?? '-') }}
                </span>
              </td>
              <td>
                <strong style="color:var(--text); display:block;">{{ $item->user->name ?? 'Pegawai' }}</strong>
                <span style="font-size:11px; color:var(--text-muted);">{{ $item->user->nip ?? '-' }}</span>
              </td>
              <td>
                <div style="font-size:12.5px; font-weight:600; color:var(--text);">
                  {{ $item->tanggal_peminjaman?->format('d/m/Y') ?? '-' }} s/d {{ $item->tanggal_pengembalian?->format('d/m/Y') ?? '-' }}
                </div>
              </td>
              <td>
                <span style="font-size:12.5px; color:var(--text);">
                  {{ Str::limit($item->deskripsi_peruntukan ?? '-', 45) }}
                </span>
              </td>
              <td>
                @if($item->status == 'disetujui')
                  <span class="badge badge-disetujui"><i class="fas fa-check-circle"></i> Disetujui</span>
                @elseif($item->status == 'dikembalikan')
                  <span class="badge badge-dikembalikan"><i class="fas fa-circle-check"></i> Selesai</span>
                @elseif($item->status == 'ditolak')
                  <span class="badge badge-ditolak"><i class="fas fa-times-circle"></i> Ditolak</span>
                @elseif($item->status == 'diteruskan_kasubag')
                  <span class="badge badge-pending"><i class="fas fa-clock"></i> Diteruskan ke Kasubag</span>
                @else
                  <span class="badge badge-pending"><i class="fas fa-clock"></i> {{ ucfirst($item->status) }}</span>
                @endif
              </td>
              <td>
                @if(in_array($item->status, ['disetujui', 'dikembalikan']))
                  <div style="display:flex; flex-direction:column; gap:4px;">
                    <form action="{{ route('adminasettetap.peminjaman-kendaraan.print', $item->id) }}" method="GET" target="_blank" style="display:flex; gap:4px; align-items:center;">
                      <input type="date" name="tanggal_surat" value="{{ now()->format('Y-m-d') }}" required title="Tanggal surat BAST" style="padding:4px 6px; border:1px solid var(--border); border-radius:6px; font-size:11px; max-width:115px;">
                      <button type="submit" class="btn-detail" style="color:var(--primary); font-size:11px; padding:4px 8px; white-space:nowrap;" title="Generate & Cetak Berita Acara Kendaraan">
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
                <button type="button" class="btn-detail" onclick="openKendaraanModal({{ json_encode($item) }})">
                  <i class="fas fa-eye"></i> Detail
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" style="text-align:center; padding: 48px; color:var(--muted);">
                <i class="fas fa-car-tunnel" style="font-size:32px; margin-bottom:12px; display:block; opacity:0.4;"></i>
                Tidak ada data peminjaman kendaraan yang ditemukan.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>

      @if($riwayatPeminjaman->hasPages())
        <div style="padding: 16px 20px; border-top: 1px solid var(--border);">
          {{ $riwayatPeminjaman->links() }}
        </div>
      @endif
    </div>
  </div>
</main>

<!-- MODAL DETAIL KENDARAAN -->
<div class="modal-overlay" id="modalKendaraan">
  <div class="modal-box">
    <div class="modal-header">
      <div class="modal-title"><i class="fas fa-car-side" style="color:var(--primary); margin-right:6px;"></i> Detail Peminjaman Kendaraan</div>
      <button type="button" class="btn-close" onclick="closeKendaraanModal()">&times;</button>
    </div>

    <div class="detail-grid">
      <div class="lbl">Kendaraan</div><div class="val" id="detCar">-</div>
      <div class="lbl">Nomor Polisi</div><div class="val" id="detNopol">-</div>
      <div class="lbl">Pemohon</div><div class="val" id="detUser">-</div>
      <div class="lbl">Tgl Pinjam</div><div class="val" id="detTglPinjam">-</div>
      <div class="lbl">Tgl Kembali</div><div class="val" id="detTglKembali">-</div>
      <div class="lbl">Tujuan / Keperluan</div><div class="val" id="detPeruntukan">-</div>
      <div class="lbl">Status</div><div class="val" id="detStatus">-</div>
      <div class="lbl">Catatan / Komentar</div><div class="val" id="detKomentar">-</div>
      <div class="lbl">Berita Acara</div><div class="val" id="detBastBtn">-</div>
    </div>

    <div style="margin-top:24px; text-align:right;">
      <button type="button" class="btn-filter" onclick="closeKendaraanModal()">Tutup</button>
    </div>
  </div>
</div>

<script>
  function openKendaraanModal(item) {
    document.getElementById('detCar').innerText = item.nama_barang || item.merek || 'Kendaraan Dinas';
    document.getElementById('detNopol').innerText = item.nomor_polisi_saat_pinjam || item.kode_barang || '-';
    document.getElementById('detUser').innerText = item.user ? item.user.name + ' (' + (item.user.nip || 'NIP -') + ')' : 'Pegawai';
    document.getElementById('detTglPinjam').innerText = item.tanggal_peminjaman ? new Date(item.tanggal_peminjaman).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'}) : '-';
    document.getElementById('detTglKembali').innerText = item.tanggal_pengembalian ? new Date(item.tanggal_pengembalian).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'}) : '-';
    document.getElementById('detPeruntukan').innerText = item.deskripsi_peruntukan || '-';
    document.getElementById('detStatus').innerText = item.status ? item.status.toUpperCase() : '-';
    document.getElementById('detKomentar').innerText = item.komentar || '-';

    let bastHtml = '<span style="color:var(--muted); font-size:12px;">Belum tersedia</span>';
    if(['disetujui', 'dikembalikan'].includes(item.status)) {
      bastHtml = `<div style="display:flex; gap:8px;">
        <a href="/adminasettetap/peminjaman-kendaraan/${item.id}/print?tanggal_surat=${new Date().toISOString().split('T')[0]}" target="_blank" class="btn-detail" style="color:var(--primary); font-size:12px;">
          <i class="fas fa-file-pdf"></i> Cetak BAST
        </a>`;
      if(item.surat_bast_path) {
        bastHtml += `<a href="/storage/${item.surat_bast_path}" target="_blank" class="btn-detail" style="color:var(--emerald); font-size:12px;">
          <i class="fas fa-file-circle-check"></i> BAST Fisik
        </a>`;
      }
      bastHtml += `</div>`;
    }
    document.getElementById('detBastBtn').innerHTML = bastHtml;

    document.getElementById('modalKendaraan').classList.add('show');
  }

  function closeKendaraanModal() {
    document.getElementById('modalKendaraan').classList.remove('show');
  }

  document.getElementById('modalKendaraan').addEventListener('click', function(e) {
    if(e.target === this) closeKendaraanModal();
  });
</script>
</body>
</html>
