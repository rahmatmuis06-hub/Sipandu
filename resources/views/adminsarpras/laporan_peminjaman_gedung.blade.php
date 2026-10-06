<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIPANDU - Laporan Peminjaman & Pengembalian</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  :root {
    --blue: #4F6FFF;
    --blue-dark: #3B5BDB;
    --green: #10B981;
    --red: #EF4444;
    --purple: #8B5CF6;
    --amber: #F59E0B;
    --teal: #14B8A6;
    --orange: #F97316;
    --sidebar-w: 240px;
    --radius: 16px;
    --bg: #F4F6FB;
    --surface: #FFFFFF;
    --text: #1E293B;
    --muted: #94A3B8;
    --border: #E8EDF5;
  }
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); display: flex; min-height: 100vh; }

  /* SIDEBAR */
  .sidebar {
    width: var(--sidebar-w); background: var(--surface); border-right: 1px solid var(--border);
    display: flex; flex-direction: column; position: fixed; top: 0; left: 0; bottom: 0; z-index: 100;
  }
  .sidebar-logo {
    display: flex; align-items: center; gap: 10px; padding: 20px 20px 16px;
    border-bottom: 1px solid var(--border);
  }
  .logo-icon {
    width: 38px; height: 38px; border-radius: 10px;
    background: linear-gradient(135deg, var(--blue), #7C3AED);
    display: flex; align-items: center; justify-content: center;
  }
  .logo-icon svg { width: 20px; height: 20px; fill: white; }
  .logo-text strong { font-size: 15px; font-weight: 800; display: block; }
  .logo-text span { font-size: 10px; color: var(--muted); font-weight: 600; letter-spacing: 1px; }
  .role-badge {
    background: linear-gradient(135deg, #EEF2FF, #E0E7FF); border: 1px solid #C7D2FE;
    border-radius: 10px; padding: 8px 12px; font-size: 12px; font-weight: 700; color: var(--blue);
    margin: 14px 16px 8px;
  }
  .sidebar-label { font-size: 10px; font-weight: 700; color: var(--muted); letter-spacing: 1.5px; text-transform: uppercase; padding: 8px 20px 4px; }
  .nav { flex: 1; overflow-y: auto; padding: 4px 12px; }
  .nav-item {
    display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 10px;
    font-size: 13.5px; font-weight: 500; color: #64748B; cursor: pointer; transition: all .15s; margin-bottom: 2px;
  }
  .nav-item:hover { background: var(--bg); color: var(--text); }
  .nav-item.active { background: linear-gradient(135deg, #EEF2FF, #E0E7FF); color: var(--blue); font-weight: 700; }
  .nav-item svg { width: 17px; height: 17px; flex-shrink: 0; }
  .chevron { margin-left: auto; width: 14px !important; height: 14px !important; }
  .nav-sub { padding-left: 14px; }
  .nav-sub .nav-item { font-size: 13px; padding: 7px 12px; }
  .sidebar-footer { border-top: 1px solid var(--border); padding: 14px 16px; }
  .user-info { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
  .user-avatar {
    width: 34px; height: 34px; border-radius: 50%;
    background: linear-gradient(135deg, var(--blue), var(--purple));
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 13px; font-weight: 700;
  }
  .user-detail strong { font-size: 13px; font-weight: 700; display: block; }
  .user-detail span { font-size: 11px; color: var(--muted); }
  .btn-logout {
    width: 100%; display: flex; align-items: center; justify-content: center; gap: 6px;
    padding: 8px; border-radius: 8px; border: 1px solid var(--border);
    background: transparent; color: #64748B; font-size: 13px; font-weight: 600; font-family: inherit; cursor: pointer; transition: all .15s;
  }
  .btn-logout:hover { background: #FEF2F2; color: #EF4444; border-color: #FECACA; }

  /* MAIN */
  .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; }
  .topbar {
    background: var(--surface); border-bottom: 1px solid var(--border); padding: 0 28px; height: 56px;
    display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 50;
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
    display: flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 8px;
    border: 1px solid var(--border); background: var(--surface); color: #64748B;
    font-size: 13px; font-weight: 600; font-family: inherit; cursor: pointer;
  }
  .btn-keluar:hover { background: #FEF2F2; color: #EF4444; }

  .content { padding: 28px; flex: 1; }
  .page-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; }
  .page-top h1 { font-size: 22px; font-weight: 800; color: var(--blue); margin-bottom: 4px; }
  .page-top p { font-size: 13px; color: var(--muted); }
  .btn-unduh {
    display: flex; align-items: center; gap: 7px; padding: 10px 18px; border-radius: 10px;
    background: var(--blue); color: white; font-size: 13.5px; font-weight: 700;
    font-family: inherit; border: none; cursor: pointer;
    box-shadow: 0 4px 14px rgba(79,111,255,.35); transition: all .2s;
  }
  .btn-unduh:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(79,111,255,.45); }

  /* STATS */
  .stats-grid-4 { display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; margin-bottom: 16px; }
  .stats-grid-3 { display: grid; grid-template-columns: repeat(3,1fr); gap: 16px; margin-bottom: 20px; }
  .stat-card {
    background: var(--surface); border-radius: var(--radius); padding: 20px;
    border: 1px solid var(--border); transition: transform .2s, box-shadow .2s;
  }
  .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.07); }
  .stat-header { display: flex; align-items: center; gap: 8px; margin-bottom: 12px; }
  .stat-icon { width: 36px; height: 36px; border-radius: 9px; display: flex; align-items: center; justify-content: center; }
  .stat-icon svg { width: 17px; height: 17px; }
  .stat-label-sm { font-size: 12.5px; font-weight: 600; color: #64748B; line-height: 1.3; }
  .stat-value { font-size: 30px; font-weight: 800; margin-bottom: 4px; }
  .stat-sub { font-size: 12px; color: var(--muted); }
  .stat-trend { display: flex; align-items: center; gap: 4px; margin-top: 6px; font-size: 12px; font-weight: 600; }
  .trend-up { color: var(--green); }
  .trend-neu { color: var(--muted); }

  .ic-blue { background: #EFF6FF; } .ic-blue svg { fill: var(--blue); }
  .ic-green { background: #ECFDF5; } .ic-green svg { fill: var(--green); }
  .ic-red { background: #FEF2F2; } .ic-red svg { fill: var(--red); }
  .ic-purple { background: #F5F3FF; } .ic-purple svg { fill: var(--purple); }
  .ic-teal { background: #F0FDFA; } .ic-teal svg { fill: var(--teal); }
  .ic-orange { background: #FFF7ED; } .ic-orange svg { fill: var(--orange); }

  /* CHARTS */
  .charts-row { display: grid; grid-template-columns: 1fr 320px; gap: 16px; margin-bottom: 20px; }
  .chart-card { background: var(--surface); border-radius: var(--radius); border: 1px solid var(--border); padding: 22px; }
  .chart-title { font-size: 15px; font-weight: 700; margin-bottom: 4px; }
  .chart-sub { font-size: 12px; color: var(--muted); margin-bottom: 18px; }
  .bar-chart { display: flex; align-items: flex-end; gap: 8px; height: 150px; }
  .bar-col { display: flex; flex-direction: column; align-items: center; flex: 1; gap: 4px; }
  .bar-wrap { flex: 1; display: flex; align-items: flex-end; width: 100%; }
  .bar { width: 100%; border-radius: 5px 5px 0 0; min-height: 4px; background: linear-gradient(180deg, var(--blue), var(--blue-dark)); transition: opacity .2s; }
  .bar.green { background: linear-gradient(180deg, var(--green), #059669); }
  .bar:hover { opacity: .7; }
  .bar-val { font-size: 9.5px; font-weight: 700; color: var(--text); }
  .bar-lbl { font-size: 9.5px; color: var(--muted); }
  .chart-legend { display: flex; gap: 16px; margin-top: 12px; }
  .legend-item { display: flex; align-items: center; gap: 6px; font-size: 12px; color: #64748B; }
  .legend-dot { width: 10px; height: 10px; border-radius: 3px; }

  /* Donut */
  .donut-svg { width: 150px; height: 150px; display: block; margin: 0 auto 14px; }
  .donut-labels { width: 100%; }
  .donut-row { display: flex; align-items: center; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid var(--border); font-size: 12.5px; }
  .donut-row:last-child { border-bottom: none; }
  .donut-dot { width: 10px; height: 10px; border-radius: 50%; margin-right: 8px; flex-shrink: 0; }
  .donut-name { display: flex; align-items: center; color: #64748B; }
  .donut-pct { font-weight: 700; }

  /* TABS */
  .tab-card { background: var(--surface); border-radius: var(--radius); border: 1px solid var(--border); overflow: hidden; }
  .tab-bar { display: flex; border-bottom: 1px solid var(--border); padding: 0 20px; }
  .tab-btn {
    padding: 14px 4px; font-size: 13.5px; font-weight: 600; color: var(--muted);
    border: none; background: none; cursor: pointer; font-family: inherit;
    border-bottom: 2.5px solid transparent; margin-right: 20px; transition: all .15s;
  }
  .tab-btn.active { color: var(--blue); border-bottom-color: var(--blue); }
  .tab-btn:hover:not(.active) { color: var(--text); }
  .tab-toolbar {
    display: flex; align-items: center; gap: 10px; padding: 14px 20px; border-bottom: 1px solid var(--border);
  }
  .search-wrap {
    flex: 1; display: flex; align-items: center; gap: 8px;
    border: 1.5px solid var(--border); border-radius: 10px; padding: 8px 14px; background: var(--bg);
  }
  .search-wrap:focus-within { border-color: var(--blue); }
  .search-wrap input { border: none; background: none; outline: none; font-family: inherit; font-size: 13px; width: 100%; }
  .search-wrap input::placeholder { color: var(--muted); }
  .filter-select {
    padding: 8px 12px; border-radius: 10px; border: 1.5px solid var(--border);
    background: var(--bg); font-family: inherit; font-size: 13px; cursor: pointer; outline: none;
  }
  .filter-select:focus { border-color: var(--blue); }

  table { width: 100%; border-collapse: collapse; }
  thead tr { background: #F8FAFF; }
  th { padding: 12px 18px; text-align: left; font-size: 11px; font-weight: 700; color: var(--blue); letter-spacing: .8px; text-transform: uppercase; border-bottom: 1px solid var(--border); }
  td { padding: 13px 18px; font-size: 13px; color: var(--text); border-bottom: 1px solid var(--border); vertical-align: middle; }
  tr:last-child td { border-bottom: none; }
  tbody tr:hover { background: #F8FAFF; }

  .id-badge { display: inline-block; padding: 3px 9px; border-radius: 7px; background: #EEF2FF; color: var(--blue); font-size: 11.5px; font-weight: 700; }
  .priority-badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; }
  .prio-tinggi { background: #FEE2E2; color: #DC2626; }
  .prio-normal { background: #DBEAFE; color: #2563EB; }
  .prio-rendah { background: #F1F5F9; color: #64748B; }
  .status-badge { display: inline-block; padding: 4px 11px; border-radius: 20px; font-size: 11px; font-weight: 700; }
  .status-pending { background: #FEF3C7; color: #B45309; }
  .status-disetujui { background: #DCFCE7; color: #15803D; }
  .status-ditolak { background: #FEE2E2; color: #DC2626; }
  .action-btn {
    width: 30px; height: 30px; border-radius: 7px; border: 1px solid var(--border);
    background: var(--surface); display: inline-flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all .15s;
  }
  .action-btn:hover { background: #EEF2FF; border-color: var(--blue); }

  .table-footer { display: flex; align-items: center; justify-content: space-between; padding: 13px 20px; border-top: 1px solid var(--border); font-size: 13px; color: var(--muted); }
  .pagination { display: flex; gap: 5px; }
  .page-btn { padding: 5px 11px; border-radius: 7px; border: 1px solid var(--border); background: var(--surface); font-family: inherit; font-size: 13px; color: #64748B; cursor: pointer; }
  .page-btn.active { background: var(--blue); border-color: var(--blue); color: white; font-weight: 700; }
  .page-btn:hover:not(.active) { border-color: var(--blue); color: var(--blue); }

  .tab-panel { display: none; }
  .tab-panel.active { display: block; }

  .progress-bar { background: #F1F5F9; border-radius: 20px; height: 8px; margin-top: 10px; overflow: hidden; }
  .progress-fill { height: 100%; border-radius: 20px; }
</style>
</head>
<body>
@php
// ✅ FIXED GLOBAL STATUS HELPER - PHP 7.4+ Compatible
function getStatusBadge($status) {
    $badges = [
        'disetujui' => ['class' => 'status-disetujui', 'text' => '✅ Disetujui'],
        'pending' => ['class' => 'status-pending', 'text' => '⏳ Pending'],
        'dalam_review' => ['class' => 'status-pending', 'text' => '🔍 Dalam Review'],
        'dipinjam' => ['class' => 'status-disetujui', 'text' => '📍 Dipinjam'],
        'ditolak' => ['class' => 'status-ditolak', 'text' => '❌ Ditolak'],
        'terlambat' => ['class' => 'status-ditolak', 'text' => '⚠️ Terlambat']
    ];
    
    if (isset($badges[$status])) {
        return $badges[$status];
    }
    
    return ['class' => 'status-pending', 'text' => '⏳ Pending'];
}
@endphp
@include('partials.sidebar')

<!-- MAIN -->
<main class="main">
  <div class="topbar">
    <span class="topbar-title">Laporan Peminjaman</span>
    <div class="topbar-right">
      {{-- <div class="notif-btn">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="#64748B"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
        <span class="notif-dot"></span>
      </div> --}}
      <span class="date-text">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}</span>
      <button class="btn-keluar">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5-5-5zm-5 11H5V5h7V3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h7v-2z"/></svg>
        Keluar
      </button>
    </div>
  </div>

  <div class="content">
    <!-- Header -->
    <div class="page-top">
      <div>
        <h1>Laporan Peminjaman</h1>
        <p>Analisis lengkap peminjaman Gedung</p>
      </div>
      <a href="{{ route('adminsarpras.laporan.peminjaman.download', request()->query()) }}" class="btn-unduh" style="text-decoration: none;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="white"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>
        Unduh Laporan
      </a>
    </div>

    <!-- STAT ROW 1 - DYNAMIC -->
    <div class="stats-grid-3">
      <div class="stat-card">
        <div class="stat-header">
          <div class="stat-icon ic-blue">
            <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/></svg>
          </div>
          <span class="stat-label-sm">Total Peminjaman</span>
        </div>
        <div class="stat-value" style="color:var(--blue)">{{ $stats['total_peminjaman'] }}</div>
        <div class="stat-sub">Keseluruhan</div>
        <div class="stat-trend trend-up">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M7 14l5-5 5 5z"/></svg>
          {{ number_format($stats['total_peminjaman']) }} transaksi
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-header">
          <div class="stat-icon ic-green">
            <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/></svg>
          </div>
          <span class="stat-label-sm">Sedang Dipinjam</span>
        </div>
        <div class="stat-value" style="color:var(--green)">{{ $stats['sedang_dipinjam'] }}</div>
        <div class="stat-sub">{{ $stats['persentase_approval'] ?? 0 }}% dari total</div>
        <div class="stat-trend trend-neu">— {{ $stats['sedang_dipinjam'] }} aktif</div>
      </div>

      <div class="stat-card">
        <div class="stat-header">
          <div class="stat-icon ic-red">
            <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
          </div>
          <span class="stat-label-sm">Terlambat</span>
        </div>
        <div class="stat-value" style="color:var(--red)">{{ $stats['terlambat'] }}</div>
        <div class="stat-sub">Perlu tindakan</div>
        <div class="stat-trend" style="color:var(--green)">
          ✓ {{ $stats['total_peminjaman'] - $stats['terlambat'] }} tepat waktu
        </div>
      </div>
    </div>

    <!-- STATUS BULAN INI - DYNAMIC -->
    <div class="stats-grid-3">
      <div class="stat-card" style="background:linear-gradient(135deg,#EFF6FF,#F5F8FF)">
        <div class="stat-header">
          <div class="stat-icon ic-blue">
            <svg viewBox="0 0 24 24"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
          </div>
          <span class="stat-label-sm">Status Peminjaman Bulan Ini</span>
        </div>
        <div style="font-size:22px;font-weight:800;color:var(--blue);margin-bottom:12px">
          {{ $statusBulanIni['total'] ?? 0 }} 
          <span style="font-size:14px;color:var(--muted);font-weight:500">transaksi</span>
        </div>
        <div style="display:flex;gap:14px">
          <div style="text-align:center">
            <div style="font-size:20px;font-weight:800;color:var(--green)">{{ $statusBulanIni['disetujui'] ?? 0 }}</div>
            <div style="font-size:11px;color:#64748B">Disetujui</div>
          </div>
          <div style="text-align:center">
            <div style="font-size:20px;font-weight:800;color:var(--amber)">{{ $statusBulanIni['pending'] ?? 0 }}</div>
            <div style="font-size:11px;color:#64748B">Pending</div>
          </div>
          <div style="text-align:center">
            <div style="font-size:20px;font-weight:800;color:var(--red)">{{ $statusBulanIni['ditolak'] ?? 0 }}</div>
            <div style="font-size:11px;color:#64748B">Ditolak</div>
          </div>
        </div>
      </div>

      <!-- Tingkat Persetujuan -->
      <div class="stat-card" style="background:linear-gradient(135deg,#ECFDF5,#F5FFF9)">
        <div class="stat-header">
          <div class="stat-icon ic-teal"><!-- svg --></div>
          <span class="stat-label-sm">Tingkat Persetujuan</span>
        </div>
        <div style="font-size:22px;font-weight:800;color:var(--teal);margin-bottom:8px">
          {{ $stats['persentase_approval'] ?? 0 }}% 
          <span style="font-size:13px;color:var(--muted);font-weight:500">disetujui</span>
        </div>
        <div class="progress-bar">
          <div class="progress-fill" style="background:var(--teal);width:{{ $stats['persentase_approval'] ?? 0 }}%"></div>
        </div>
        <div style="font-size:11.5px;color:#64748B;margin-top:8px">
          {{ $stats['disetujui'] }} dari {{ $stats['total_peminjaman'] }} peminjaman
        </div>
      </div>

      <!-- Rata-rata Durasi -->
      <div class="stat-card" style="background:linear-gradient(135deg,#FFF7ED,#FFFBF5)">
      <div class="stat-header">
        <div class="stat-icon ic-orange"><!-- svg --></div>
        <span class="stat-label-sm">Rata-rata Durasi</span>
      </div>
      <div style="font-size:22px;font-weight:800;color:var(--orange);margin-bottom:8px">
        {{ $stats['rata_rata_hari'] }} 
        <span style="font-size:13px;color:var(--muted);font-weight:500">hari</span>
      </div>
      <div style="font-size:11.5px;color:#64748B">Per transaksi</div>
    </div>
  </div>

    <!-- DONUT CHART - FULL DYNAMIC -->
    <div style="margin-bottom: 20px; max-width: 420px;">
      <div class="chart-card">
        <div class="chart-title">Distribusi Status</div>
        <div class="chart-sub">Komposisi keseluruhan ({{ $donutData['total'] ?? 0 }} total)</div>
        
        <!-- SVG Donut dengan data real -->
        <svg class="donut-svg" viewBox="0 0 150 150">
          <!-- Background circle -->
          <circle cx="75" cy="75" r="55" fill="none" stroke="#F1F5F9" stroke-width="26"/>
          
          <!-- Disetujui (hijau) - mulai dari 0° -->
          @if($donutData['disetujui_pct'] ?? 0 > 0)
          <circle cx="75" cy="75" r="55" fill="none" stroke="#10B981" stroke-width="26" 
            stroke-dasharray="{{ ($donutData['disetujui_pct'] ?? 0) * 3.6 }} {{ 360 - (($donutData['disetujui_pct'] ?? 0) * 3.6) }}" 
            stroke-linecap="round"/>
          @endif
          
          <!-- Pending (kuning) - mulai setelah hijau -->
          @php $startPending = ($donutData['disetujui_pct'] ?? 0) * 3.6; @endphp
          @if($donutData['pending_pct'] ?? 0 > 0)
          <circle cx="75" cy="75" r="55" fill="none" stroke="#F59E0B" stroke-width="26" 
            stroke-dasharray="{{ ($donutData['pending_pct'] ?? 0) * 3.6 }} {{ 360 - (($donutData['pending_pct'] ?? 0) * 3.6) }}" 
            stroke-dashoffset="-{{ $startPending }}" stroke-linecap="round"/>
          @endif
          
          <!-- Ditolak (merah) - mulai setelah hijau + kuning -->
          @php $startDitolak = $startPending + (($donutData['pending_pct'] ?? 0) * 3.6); @endphp
          @if($donutData['ditolak_pct'] ?? 0 > 0)
          <circle cx="75" cy="75" r="55" fill="none" stroke="#EF4444" stroke-width="26" 
            stroke-dasharray="{{ ($donutData['ditolak_pct'] ?? 0) * 3.6 }} {{ 360 - (($donutData['ditolak_pct'] ?? 0) * 3.6) }}" 
            stroke-dashoffset="-{{ $startDitolak }}" stroke-linecap="round"/>
          @endif
          
          <!-- Center text -->
          <text x="75" y="71" text-anchor="middle" dominant-baseline="middle" 
                font-size="20" font-weight="800" fill="#1E293B" font-family="Plus Jakarta Sans">
            {{ $donutData['total'] ?? 0 }}
          </text>
          <text x="75" y="87" text-anchor="middle" font-size="10" fill="#94A3B8" 
                font-family="Plus Jakarta Sans">transaksi</text>
        </svg>

        <!-- Legend dengan data real -->
        <div class="donut-labels">
          <div class="donut-row">
            <div class="donut-name">
              <div class="donut-dot" style="background:#10B981"></div>Disetujui
            </div>
            <div class="donut-pct" style="color:#10B981">
              {{ $donutData['disetujui'] ?? 0 }} 
              <span style="color:var(--muted);font-weight:400;font-size:11px">({{ $donutData['disetujui_pct'] ?? 0 }}%)</span>
            </div>
          </div>
          <div class="donut-row">
            <div class="donut-name">
              <div class="donut-dot" style="background:#F59E0B"></div>Pending
            </div>
            <div class="donut-pct" style="color:#F59E0B">
              {{ $donutData['pending'] ?? 0 }} 
              <span style="color:var(--muted);font-weight:400;font-size:11px">({{ $donutData['pending_pct'] ?? 0 }}%)</span>
            </div>
          </div>
          <div class="donut-row">
            <div class="donut-name">
              <div class="donut-dot" style="background:#EF4444"></div>Ditolak
            </div>
            <div class="donut-pct" style="color:#EF4444">
              {{ $donutData['ditolak'] ?? 0 }} 
              <span style="color:var(--muted);font-weight:400;font-size:11px">({{ $donutData['ditolak_pct'] ?? 0 }}%)</span>
            </div>
          </div>
        </div>
      </div>

      <!-- TABLE PEMINJAMAN - DYNAMIC -->
    <div class="tab-panel active" id="tab-pinjam">
      <table>
        <thead>
          <tr>
            <th><span class="id-badge">ID</span></th><th>Pemohon</th><th>Gedung</th><th>Tanggal Pinjam</th><th>Status</th><th>Total Bayar</th><th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($peminjaman as $item)
          <tr>
            <td><span class="id-badge">PG-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</span></td>
            <td><strong>{{ $item->nama_lengkap }}</strong><br><small>{{ $item->instansi_lembaga }}</small></td>
            <td>{{ $item->gedung->nama_gedung ?? '—' }}<br><small>{{ $item->tanggal_pinjam->locale('id')->isoFormat('D MMM YYYY') }}</small></td>
            <td>
              <strong>{{ $item->tanggal_pinjam->locale('id')->isoFormat('D MMM') }}</strong><br>
              <small>{{ $item->jam_mulai }} - {{ $item->jam_selesai }}</small>
            </td>
            <td>
              @php 
                $statusBadge = getStatusBadge($item->status);
              @endphp
              <span class="status-badge {{ $statusBadge['class'] }}">{{ $statusBadge['text'] }}</span>
            </td>
            <td>Rp {{ number_format($item->total_pembayaran ?? 0, 0, ',', '.') }}</td>
            <td>
              <a href="{{ route('adminsarpras.download-surat', $item->id) }}" class="action-btn" title="Download Surat">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="#94A3B8">
                  <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/>
                </svg>
              </a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center py-8" style="color:var(--muted)">Belum ada data peminjaman</td>
          </tr>
          @endforelse
        </tbody>
      </table>
  </div>
</main>

<script>
  document.querySelectorAll('.tab-btn[data-tab]').forEach(btn => {
    btn.addEventListener('click', function() {
      document.querySelectorAll('.tab-btn[data-tab]').forEach(b => b.classList.remove('active'));
      document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
      this.classList.add('active');
      document.getElementById('tab-' + this.dataset.tab).classList.add('active');
    });
  });
</script>

</body>
</html>