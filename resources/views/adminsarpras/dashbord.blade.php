<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - Admin Sarana Prasarana</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
  :root {
    --primary: #4361ee;
    --primary-light: #eef0fd;
    --success: #2ec4b6;
    --success-light: #e8faf9;
    --warning: #f4a261;
    --warning-light: #fff4ec;
    --danger: #e63946;
    --danger-light: #fdecea;
    --sidebar-bg: #fff;
    --body-bg: #f0f2f9;
    --text-primary: #1a1f36;
    --text-secondary: #6b7280;
    --border: #e5e7eb;
    --card-bg: #fff;
    --sidebar-width: 240px;
  }
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--body-bg); color: var(--text-primary); display: flex; min-height: 100vh; }

  /* ── MAIN CONTENT ── */
  .main { margin-left: 256px; flex: 1; padding: 0 32px 32px; }

  /* TOP BAR */
  .topbar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 20px 0 24px;
    position: sticky; top: 0; z-index: 50;
    background: var(--body-bg);
  }
  .topbar-title { font-family: 'Space Grotesk', sans-serif; font-size: 22px; font-weight: 700; color: var(--text-primary); }
  .topbar-right { display: flex; align-items: center; gap: 12px; }
  .topbar-date {
    font-size: 13px; color: var(--text-secondary); font-weight: 500;
    background: var(--card-bg);
    border: 1px solid var(--border);
    padding: 8px 14px; border-radius: 10px;
    display: flex; align-items: center; gap: 6px;
  }
  .notif-btn {
    width: 40px; height: 40px;
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 10px;
    display: grid; place-items: center;
    cursor: pointer; position: relative;
    color: var(--text-secondary);
    transition: all .2s;
  }
  .notif-btn:hover { border-color: var(--primary); color: var(--primary); }
  .notif-dot {
    position: absolute; top: 8px; right: 8px;
    width: 7px; height: 7px;
    background: var(--danger);
    border-radius: 50%;
    border: 1.5px solid var(--card-bg);
  }

  /* HERO BANNER */
  .hero {
    background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #0891b2 100%);
    border-radius: 20px;
    padding: 32px 36px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
    margin-bottom: 28px;
    box-shadow: 0 8px 32px rgba(37,99,235,0.28);
  }
  .hero::before {
    content: '';
    position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  }
  .hero-blob {
    position: absolute;
    width: 300px; height: 300px;
    border-radius: 50%;
    background: rgba(255,255,255,0.05);
    right: -60px; top: -80px;
    pointer-events: none;
  }
  .hero-blob2 {
    position: absolute;
    width: 180px; height: 180px;
    border-radius: 50%;
    background: rgba(255,255,255,0.04);
    right: 120px; bottom: -60px;
    pointer-events: none;
  }
  .hero-left { position: relative; z-index: 2; }
  .hero-greeting { font-size: 13px; color: rgba(255,255,255,0.7); font-weight: 500; margin-bottom: 6px; letter-spacing: .5px; }
  .hero-title { font-family: 'Space Grotesk', sans-serif; font-size: 28px; font-weight: 800; color: #fff; margin-bottom: 8px; }
  .hero-sub { font-size: 14px; color: rgba(255,255,255,0.75); max-width: 380px; line-height: 1.6; }
  .hero-right { position: relative; z-index: 2; text-align: right; }
  .hero-inst { font-size: 12px; color: rgba(255,255,255,0.65); margin-bottom: 4px; }
  .hero-nip { font-size: 13px; color: rgba(255,255,255,0.85); font-weight: 600; margin-bottom: 14px; }
  .hero-btn {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,0.18);
    backdrop-filter: blur(8px);
    border: 1.5px solid rgba(255,255,255,0.25);
    color: #fff; font-size: 13px; font-weight: 600;
    padding: 10px 20px; border-radius: 10px;
    cursor: pointer; transition: all .2s;
    text-decoration: none;
  }
  .hero-btn:hover { background: rgba(255,255,255,0.28); }

  /* Stat Cards */
  .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 24px; }
  .stat-card {
    background: var(--card-bg); border-radius: 16px;
    padding: 20px; border: 1px solid var(--border);
    position: relative; overflow: hidden;
    transition: transform .2s, box-shadow .2s;
  }
  .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.06); }
  .stat-icon {
    width: 44px; height: 44px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center; margin-bottom: 16px;
  }
  .stat-card .badge {
    position: absolute; top: 16px; right: 16px;
    font-size: 11px; font-weight: 700; padding: 2px 8px;
    border-radius: 20px;
  }
  .badge-blue { background: #e0e7ff; color: var(--primary); }
  .badge-green { background: var(--success-light); color: var(--success); }
  .badge-orange { background: var(--warning-light); color: var(--warning); }
  .badge-red { background: var(--danger-light); color: var(--danger); }
  .stat-value { font-size: 32px; font-weight: 800; color: var(--text-primary); line-height: 1; margin-bottom: 4px; }
  .stat-label { font-size: 13px; color: var(--text-secondary); font-weight: 500; }
  .stat-sub { font-size: 11px; color: var(--text-secondary); margin-top: 4px; }

  /* Grid bottom */
  .bottom-grid { display: grid; grid-template-columns: 1fr 360px; gap: 20px; margin-bottom: 24px; }

  /* Chart Card */
  .card {
    background: var(--card-bg); border-radius: 16px;
    border: 1px solid var(--border); padding: 24px;
  }
  .card-title { font-size: 15px; font-weight: 700; margin-bottom: 20px; color: var(--text-primary); display: flex; align-items: center; gap: 8px; }
  .card-title i { color: var(--primary); font-size: 14px; }

  /* Bar Chart */
  .chart-wrap { display: flex; align-items: flex-end; gap: 6px; height: 180px; }
  .bar-group { display: flex; flex-direction: column; align-items: center; gap: 6px; flex: 1; }
  .bar {
    width: 100%; border-radius: 6px 6px 0 0;
    background: linear-gradient(180deg, #4361ee 0%, #3a0ca3 100%);
    transition: all .3s ease;
    cursor: pointer;
    position: relative;
    min-height: 4px;
  }
  .bar:hover { opacity: 0.85; transform: scaleY(1.02); }
  .bar-label { font-size: 11px; color: var(--text-secondary); font-weight: 500; }
  .bar-count {
    font-size: 10px; font-weight: 700; color: var(--primary);
    margin-bottom: 2px;
  }

  /* Activity */
  .activity-list { display: flex; flex-direction: column; gap: 14px; }
  .activity-item { display: flex; align-items: flex-start; gap: 12px; padding: 8px 0; border-bottom: 1px solid #f3f4f6; }
  .activity-item:last-child { border-bottom: none; }
  .act-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
  .act-text { flex: 1; }
  .act-text p { font-size: 13px; font-weight: 600; color: var(--text-primary); line-height: 1.4; }
  .act-text .act-by { font-size: 11px; color: var(--primary); font-weight: 600; }
  .act-text span { font-size: 11px; color: var(--text-secondary); }

  /* Table */
  .table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
  .see-all { font-size: 13px; color: var(--primary); font-weight: 600; cursor: pointer; text-decoration: none; display: flex; align-items: center; gap: 4px; }
  .see-all:hover { text-decoration: underline; }
  table { width: 100%; border-collapse: collapse; }
  thead th {
    font-size: 12px; font-weight: 600; color: var(--text-secondary);
    padding: 10px 12px; text-align: left; border-bottom: 1px solid var(--border);
    text-transform: uppercase; letter-spacing: .05em; background: #fafbff;
  }
  tbody td { padding: 14px 12px; font-size: 13px; border-bottom: 1px solid var(--border); }
  tbody tr:last-child td { border-bottom: none; }
  tbody tr:hover { background: #fafbff; }
  .status-badge {
    display: inline-flex; padding: 4px 12px; border-radius: 20px;
    font-size: 12px; font-weight: 600; align-items: center; gap: 4px;
  }
  .status-approved { background: var(--success-light); color: var(--success); }
  .status-pending { background: var(--warning-light); color: var(--warning); }
  .status-rejected { background: var(--danger-light); color: var(--danger); }
  .status-review { background: #e0e7ff; color: var(--primary); }

  .peminjam-cell { display: flex; flex-direction: column; }
  .peminjam-cell strong { font-weight: 600; color: var(--text-primary); }
  .peminjam-cell small { font-size: 11px; color: var(--text-secondary); }

  /* Quick Stats Row */
  .quick-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px; }
  .quick-stat {
    background: var(--card-bg); border-radius: 14px; padding: 18px 20px;
    border: 1px solid var(--border); display: flex; align-items: center; gap: 14px;
  }
  .qs-icon { width: 42px; height: 42px; border-radius: 11px; display: grid; place-items: center; font-size: 16px; flex-shrink: 0; }
  .qs-info { flex: 1; }
  .qs-label { font-size: 11px; color: var(--text-secondary); font-weight: 600; text-transform: uppercase; letter-spacing: .4px; }
  .qs-value { font-size: 20px; font-weight: 800; color: var(--text-primary); margin-top: 2px; }

  /* Empty state */
  .empty-state { text-align: center; padding: 40px 20px; color: var(--text-secondary); }
  .empty-state i { font-size: 40px; opacity: 0.3; margin-bottom: 10px; display: block; }

  /* Animations */
  @keyframes fadeUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
  .animate { animation: fadeUp .5s ease both; }
  .d1 { animation-delay: .05s; } .d2 { animation-delay: .1s; } .d3 { animation-delay: .15s; } .d4 { animation-delay: .2s; }

  @media (max-width: 1024px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
    .bottom-grid { grid-template-columns: 1fr; }
    .quick-stats { grid-template-columns: 1fr; }
  }
  @media (max-width: 768px) {
    .main { margin-left: 0; padding: 0 16px 32px; padding-top: 60px; }
    .hero { flex-direction: column; text-align: center; gap: 20px; }
    .hero-right { text-align: center; }
    .stats-grid { grid-template-columns: 1fr 1fr; }
    .topbar { flex-direction: column; align-items: flex-start; gap: 8px; }
    .card { overflow-x: auto; }
    table { min-width: 500px; }
  }
  @media (max-width: 640px) {
    .stats-grid { grid-template-columns: 1fr; }
  }
</style>
</head>
<body>

@include('partials.sidebar')

<!-- MAIN -->
<main class="main">
  <!-- TOPBAR -->
  <div class="topbar">
    <div class="topbar-title">Dashboard Admin Sarpras</div>
    <div class="topbar-right">
      <div class="topbar-date">
        <i class="fas fa-calendar" style="color:var(--primary)"></i>
        {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
      </div>
      <div class="notif-btn">
        <i class="fas fa-bell"></i>
        @if($peminjamanPending > 0)
          <div class="notif-dot"></div>
        @endif
      </div>
    </div>
  </div>

  <!-- HERO BANNER -->
  <div class="hero animate d1">
    <div class="hero-blob"></div>
    <div class="hero-blob2"></div>
    <div class="hero-left">
      <div class="hero-greeting">👋 Selamat datang kembali!</div>
      <div class="hero-title">Halo, {{ $user->name ?? 'Admin Sarana dan Prasarana' }}!</div>
      <div class="hero-sub">
        Sebagai Admin Sarana dan Prasarana, Anda dapat mengelola data gedung, ruangan, dan peminjaman fasilitas di BPMP Provinsi Gorontalo.
      </div>
    </div>
    <div class="hero-right">
      <div class="hero-inst">Instansi: BPMP Provinsi Gorontalo</div>
      <div class="hero-nip">NIP: {{ $user->nip ?? '-' }}</div>
      <a href="{{ route('adminsarpras.pengaturan-akun') }}" class="hero-btn">
        <i class="fas fa-gear"></i> Pengaturan Akun
      </a>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="stats-grid animate d2">
    <div class="stat-card">
      <div class="stat-icon" style="background:#eef0fd">
        <i class="fas fa-building" style="color:#4361ee;font-size:20px"></i>
      </div>
      @if($gedungBaruBulanIni > 0)
        <div class="badge badge-blue">+{{ $gedungBaruBulanIni }}</div>
      @endif
      <div class="stat-value">{{ $totalGedung }}</div>
      <div class="stat-label">Total Gedung</div>
      <div class="stat-sub">{{ $gedungTersedia }} tersedia</div>
    </div>
    <div class="stat-card">
      <div class="stat-icon" style="background:#fdecea">
        <i class="fas fa-exclamation-triangle" style="color:#e63946;font-size:20px"></i>
      </div>
      <div class="stat-value">{{ $perluPerbaikan }}</div>
      <div class="stat-label">Perlu Perbaikan</div>
      <div class="stat-sub">{{ $rusakBerat }} rusak berat · {{ $rusakRingan }} rusak ringan</div>
    </div>
  </div>

  <!-- Quick Stats Row -->
  <div class="quick-stats animate d2">
    <div class="quick-stat">
      <div class="qs-icon" style="background:#fff4ec;color:#f4a261">
        <i class="fas fa-hourglass-half"></i>
      </div>
      <div class="qs-info">
        <div class="qs-label">Pending</div>
        <div class="qs-value">{{ $peminjamanPending }}</div>
      </div>
    </div>
    <div class="quick-stat">
      <div class="qs-icon" style="background:#e0e7ff;color:#4361ee">
        <i class="fas fa-paper-plane"></i>
      </div>
      <div class="qs-info">
        <div class="qs-label">Dalam Review Kasubag</div>
        <div class="qs-value">{{ $peminjamanDalamReview }}</div>
      </div>
    </div>
    <div class="quick-stat">
      <div class="qs-icon" style="background:#e8faf9;color:#2ec4b6">
        <i class="fas fa-money-bill-wave"></i>
      </div>
      <div class="qs-info">
        <div class="qs-label">Total Pendapatan</div>
        <div class="qs-value" style="font-size:17px">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
      </div>
    </div>
  </div>


  <!-- Recent Table -->
  <div class="card animate d4">
    <div class="table-header">
      <span class="card-title" style="margin-bottom:0"><i class="fas fa-list-check"></i> Peminjaman Terbaru</span>
      <a class="see-all" href="{{ route('adminsarpras.daftar-peminjaman') }}">Lihat Semua <i class="fas fa-arrow-right" style="font-size:11px"></i></a>
    </div>
    <table>
      <thead>
        <tr>
          <th>Peminjam</th>
          <th>Fasilitas</th>
          <th>Tanggal Pinjam</th>
          <th>Total Bayar</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse($peminjamanTerbaru as $item)
          <tr>
            <td>
              <div class="peminjam-cell">
                <strong>{{ $item->nama_lengkap }}</strong>
                <small>{{ $item->instansi_lembaga }}</small>
              </div>
            </td>
            <td>{{ $item->nama_fasilitas ?? $item->gedung?->nama_gedung ?? '-' }}</td>
            <td>{{ $item->tanggal_pinjam?->locale('id')->isoFormat('D MMM YYYY') }}</td>
            <td style="font-weight:600">Rp {{ number_format($item->total_pembayaran, 0, ',', '.') }}</td>
            <td>
              @switch($item->status)
                @case('disetujui')
                @case('disetujui_kasubag')
                  <span class="status-badge status-approved"><i class="fas fa-check-circle" style="font-size:10px"></i> {{ ucfirst(str_replace('_', ' ', $item->status)) }}</span>
                  @break
                @case('pending')
                  <span class="status-badge status-pending"><i class="fas fa-clock" style="font-size:10px"></i> Pending</span>
                  @break
                @case('dalam_review')
                  <span class="status-badge status-review"><i class="fas fa-paper-plane" style="font-size:10px"></i> Dalam Review</span>
                  @break
                @case('ditolak')
                  <span class="status-badge status-rejected"><i class="fas fa-times-circle" style="font-size:10px"></i> Ditolak</span>
                  @break
                @default
                  <span class="status-badge status-pending">{{ ucfirst($item->status) }}</span>
              @endswitch
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5">
              <div class="empty-state">
                <i class="fas fa-clipboard-list"></i>
                <div style="font-size:14px;font-weight:600;margin-bottom:4px">Belum ada data peminjaman</div>
                <div style="font-size:12px">Data peminjaman akan muncul di sini</div>
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</main>
</body>
</html>