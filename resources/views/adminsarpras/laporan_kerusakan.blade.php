<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Laporan Kerusakan - Admin Sarana Prasarana</title>
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
  .bar.amber { background: linear-gradient(180deg, var(--amber), #D97706); }
  .bar.red { background: linear-gradient(180deg, var(--red), #DC2626); }
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
  .kondisi-badge { display: inline-block; padding: 4px 11px; border-radius: 20px; font-size: 11px; font-weight: 700; }
  .kondisi-baik { background: #DCFCE7; color: #15803D; }
  .kondisi-ringan { background: #FEF3C7; color: #B45309; }
  .kondisi-sedang { background: #DBEAFE; color: #2563EB; }
  .kondisi-berat { background: #FEE2E2; color: #DC2626; }
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

  /* KODE BARANG STYLE */
  .kode-barang {
    font-family: 'Monaco', 'Menlo', monospace;
    background: #F8FAFF;
    padding: 2px 8px;
    border-radius: 6px;
    font-size: 11.5px;
    font-weight: 600;
    color: var(--blue);
  }
</style>
</head>
<body>

@include('partials.sidebar')

<!-- MAIN -->
<main class="main">
  <div class="topbar">
    <span class="topbar-title">Laporan Kerusakan</span>
    <div class="topbar-right">
      {{-- <div class="notif-btn">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="#64748B"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
        <span class="notif-dot"></span>
      </div> --}}
      <span class="date-text">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}</span>
<form method="POST" action="{{ route('logout', [], false) }}" style="display:inline; margin:0;">
@csrf
<button type="submit" class="btn-keluar">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5-5-5zm-5 11H5V5h7V3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h7v-2z"/></svg>
        Keluar
      </button>
</form>
    </div>
  </div>

  <div class="content">
    <!-- Header -->
    <div class="page-top">
      <div>
        <h1>Laporan Kerusakan</h1>
        <p>Analisis lengkap kondisi sarana prasarana gedung</p>
      </div>
      <a href="{{ route('adminsarpras.laporan.kerusakan.download', request()->query()) }}" class="btn-unduh" style="text-decoration: none;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="white">
            <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/>
        </svg>
        Unduh Laporan (PDF)
      </a>
    </div>

    {{-- FLASH MESSAGES --}}
    @if (session('success'))
    <div class="alert-flash alert-success-flash" role="alert" style="background:#ECFDF5;border:1px solid #A7F3D0;padding:14px 18px;border-radius:12px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;">
      <div style="display:flex;align-items:center;gap:8px;">
        <svg width="16" height="16" fill="none" stroke="#10B981" viewBox="0 0 24 24" stroke-width="2.5">
          <path d="M20 6L9 17l-5-5"/>
        </svg>
        {{ session('success') }}
      </div>
      <button onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;font-size:18px;color:inherit;line-height:1;">&times;</button>
    </div>
    @endif

    <!-- STAT ROW 1 -->
    <div class="stats-grid-4">
      <div class="stat-card">
        <div class="stat-header">
          <div class="stat-icon ic-blue">
            <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/></svg>
          </div>
          <span class="stat-label-sm">Total Kerusakan</span>
        </div>
        <div class="stat-value" style="color:var(--blue)">{{ $kerusakans->total() ?? 0 }}</div>
        <div class="stat-sub">Keseluruhan</div>
        <div class="stat-trend trend-up">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M7 14l5-5 5 5z"/></svg>
          +{{ $kerusakans->total() ?? 0 }} dari bulan lalu
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-header">
          <div class="stat-icon ic-green">
            <svg viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
          </div>
          <span class="stat-label-sm">Kondisi Baik</span>
        </div>
        <div class="stat-value" style="color:var(--green)">{{ $stats['baik'] ?? 0 }}</div>
        <div class="stat-sub">{{ number_format(($stats['baik'] ?? 0)/max(1,$kerusakans->total())*100,1) }}% dari total</div>
        <div class="stat-trend trend-up">✓ Aman digunakan</div>
      </div>
      <div class="stat-card">
        <div class="stat-header">
          <div class="stat-icon ic-red">
            <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
          </div>
          <span class="stat-label-sm">Rusak Berat</span>
        </div>
        <div class="stat-value" style="color:var(--red)">{{ $stats['rusak_berat'] ?? 0 }}</div>
        <div class="stat-sub">Perlu perbaikan segera</div>
        <div class="stat-trend" style="color:var(--orange)">⚠ Prioritas tinggi</div>
      </div>
      <div class="stat-card">
        <div class="stat-header">
          <div class="stat-icon ic-purple">
            <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zM13 17h-2v-6h2v6zm0-9h-2V7h2v1z"/></svg>
          </div>
          <span class="stat-label-sm">Lokasi Terdampak</span>
        </div>
        <div class="stat-value" style="color:var(--purple)">{{ count($stats['lokasi'] ?? []) }}</div>
        <div class="stat-sub">Lokasi berbeda</div>
        <div class="stat-trend trend-neu">— Perlu pemantauan</div>
      </div>
    </div>

    <!-- STAT ROW 2 -->
    <div class="stats-grid-3">
      <div class="stat-card" style="background:linear-gradient(135deg,#EFF6FF,#F5F8FF)">
        <div class="stat-header">
          <div class="stat-icon ic-blue">
            <svg viewBox="0 0 24 24"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
          </div>
          <span class="stat-label-sm">Distribusi Kondisi Bulan Ini</span>
        </div>
        <div style="font-size:22px;font-weight:800;color:var(--blue);margin-bottom:12px">{{ $kerusakans->total() ?? 0 }} <span style="font-size:14px;color:var(--muted);font-weight:500">laporan</span></div>
        <div style="display:flex;gap:14px">
          <div style="text-align:center">
            <div style="font-size:20px;font-weight:800;color:var(--green)">{{ $stats['baik'] ?? 0 }}</div>
            <div style="font-size:11px;color:#64748B">Baik</div>
          </div>
          <div style="text-align:center">
            <div style="font-size:20px;font-weight:800;color:var(--amber)">{{ $stats['rusak_ringan'] ?? 0 }}</div>
            <div style="font-size:11px;color:#64748B">Ringan</div>
          </div>
          <div style="text-align:center">
            <div style="font-size:20px;font-weight:800;color:var(--red)">{{ $stats['rusak_berat'] ?? 0 }}</div>
            <div style="font-size:11px;color:#64748B">Berat</div>
          </div>
        </div>
      </div>
      <div class="stat-card" style="background:linear-gradient(135deg,#ECFDF5,#F5FFF9)">
        <div class="stat-header">
          <div class="stat-icon ic-teal">
            <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/></svg>
          </div>
          <span class="stat-label-sm">Tingkat Kerusakan</span>
        </div>
        <div style="font-size:22px;font-weight:800;color:var(--teal);margin-bottom:8px">
          {{ number_format((($stats['rusak_ringan'] ?? 0) + ($stats['rusak_berat'] ?? 0))/max(1,$kerusakans->total())*100,1) }}% 
          <span style="font-size:13px;color:var(--muted);font-weight:500">rusak</span>
        </div>
        <div class="progress-bar">
          <div class="progress-fill" style="background:var(--teal);width:{{ number_format((($stats['rusak_ringan'] ?? 0) + ($stats['rusak_berat'] ?? 0))/max(1,$kerusakans->total())*100,1) }}%"></div>
        </div>
        <div style="font-size:11.5px;color:#64748B;margin-top:8px">{{ ($stats['rusak_ringan'] ?? 0) + ($stats['rusak_berat'] ?? 0) }} dari {{ $kerusakans->total() ?? 0 }} laporan rusak</div>
      </div>
      <div class="stat-card" style="background:linear-gradient(135deg,#FFF7ED,#FFFBF5)">
        <div class="stat-header">
          <div class="stat-icon ic-orange">
            <svg viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67V7z"/></svg>
          </div>
          <span class="stat-label-sm">Lokasi Paling Banyak</span>
        </div>
        <div style="font-size:22px;font-weight:800;color:var(--orange);margin-bottom:8px">
          {{ $stats['lokasi_terbanyak']['count'] ?? 0 }} 
          <span style="font-size:13px;color:var(--muted);font-weight:500">laporan</span>
        </div>
        <div style="font-size:11.5px;color:#64748B">{{ $stats['lokasi_terbanyak']['lokasi'] ?? '—' }}</div>
        <div style="font-size:11.5px;color:var(--orange);font-weight:600;margin-top:6px">↑ {{ $stats['lokasi_terbanyak']['count'] ?? 0 }} kasus</div>
      </div>
    </div>

    <!-- CHARTS ROW -->
    <div style="margin-bottom: 20px; max-width: 420px;">
      <div class="chart-card">
        <div class="chart-title">Distribusi Kondisi</div>
        <div class="chart-sub">Komposisi per tingkat kerusakan</div>
        <svg class="donut-svg" viewBox="0 0 150 150">
          <circle cx="75" cy="75" r="55" fill="none" stroke="#F1F5F9" stroke-width="26"/>
          <circle cx="75" cy="75" r="55" fill="none" stroke="#10B981" stroke-width="26"
            stroke-dasharray="115 345" stroke-dashoffset="0" stroke-linecap="butt"/>
          <circle cx="75" cy="75" r="55" fill="none" stroke="#F59E0B" stroke-width="26"
            stroke-dasharray="172 288" stroke-dashoffset="115" stroke-linecap="butt"
            transform="rotate(-90 75 75)"/>
                    <circle cx="75" cy="75" r="55" fill="none" stroke="#EF4444" stroke-width="26"
            stroke-dasharray="173 287" stroke-dashoffset="287" stroke-linecap="butt"
            transform="rotate(-262 75 75)"/>
          <text x="75" y="71" text-anchor="middle" dominant-baseline="middle" font-size="20" font-weight="800" fill="#1E293B" font-family="Plus Jakarta Sans">{{ $kerusakans->total() ?? 0 }}</text>
          <text x="75" y="87" text-anchor="middle" font-size="10" fill="#94A3B8" font-family="Plus Jakarta Sans">laporan</text>
        </svg>
        <div class="donut-labels">
          <div class="donut-row">
            <div class="donut-name"><div class="donut-dot" style="background:#10B981"></div>Baik</div>
            <div class="donut-pct" style="color:#10B981">{{ $stats['baik'] ?? 0 }} <span style="color:var(--muted);font-weight:400;font-size:11px">{{ number_format(($stats['baik'] ?? 0)/max(1,$kerusakans->total())*100,0) }}%</span></div>
          </div>
          <div class="donut-row">
            <div class="donut-name"><div class="donut-dot" style="background:#F59E0B"></div>Rusak Ringan</div>
            <div class="donut-pct" style="color:#F59E0B">{{ $stats['rusak_ringan'] ?? 0 }} <span style="color:var(--muted);font-weight:400;font-size:11px">{{ number_format(($stats['rusak_ringan'] ?? 0)/max(1,$kerusakans->total())*100,0) }}%</span></div>
          </div>
          <div class="donut-row">
            <div class="donut-name"><div class="donut-dot" style="background:#EF4444"></div>Rusak Berat</div>
            <div class="donut-pct" style="color:#EF4444">{{ $stats['rusak_berat'] ?? 0 }} <span style="color:var(--muted);font-weight:400;font-size:11px">{{ number_format(($stats['rusak_berat'] ?? 0)/max(1,$kerusakans->total())*100,0) }}%</span></div>
          </div>
        </div>
      </div>
    </div>

    <!-- TAB TABLE -->
    <div class="tab-card">
      <div class="tab-bar">
        <button class="tab-btn active" data-tab="semua">Semua Kerusakan</button>
        <button class="tab-btn" data-tab="rusak">Hanya Rusak</button>
        <button class="tab-btn" data-tab="lokasi">Ringkasan Lokasi</button>
      </div>
      <div class="tab-toolbar">
        <div class="search-wrap">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="#94A3B8"><path d="M15.5 14h-.79l-.28-.27A6.47 6.47 0 0016 9.5 6.5 6.5 0 109.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
          <input type="text" placeholder="Cari nama barang, kode, lokasi..." id="searchInput" oninput="filterTable()">
        </div>
        <select class="filter-select" id="kondisiFilter" onchange="filterTable()">
          <option value="">Semua Kondisi</option>
          <option value="Baik">Baik</option>
          <option value="Rusak Ringan">Rusak Ringan</option>
          <option value="Rusak Berat">Rusak Berat</option>
        </select>
      </div>

      <!-- Tab Semua Kerusakan -->
      <div class="tab-panel active" id="tab-semua">
        <table id="kerusakanTable">
          <thead>
            <tr>
              <th>ID</th>
              <th>Tanggal</th>
              <th>Nama Barang</th>
              <th>Kode</th>
              <th>NUP</th>
              <th>Kondisi</th>
              <th>Lokasi</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody id="tableBody">
            @forelse($kerusakans as $kerusakan)
            <tr data-id="{{ $kerusakan->id }}"
                data-kode="{{ strtolower($kerusakan->kode_barang) }}"
                data-nama="{{ strtolower($kerusakan->nama_barang) }}"
                data-kondisi="{{ strtolower($kerusakan->kondisi) }}"
                data-lokasi="{{ strtolower($kerusakan->lokasi) }}">
              <td><span class="id-badge">KR-{{ str_pad($kerusakan->id, 3, '0', STR_PAD_LEFT) }}</span></td>
              <td>{{ \Carbon\Carbon::parse($kerusakan->tanggal_input)->format('d/m/Y') }}</td>
              <td style="font-weight:500;">{{ $kerusakan->nama_barang }}</td>
              <td><span class="kode-barang">{{ $kerusakan->kode_barang }}</span></td>
              <td>{{ $kerusakan->nup }}</td>
              <td>
                @php
                  $kondisiClass = match($kerusakan->kondisi) {
                    'Baik'         => 'kondisi-baik',
                    'Rusak Ringan' => 'kondisi-ringan',
                    'Rusak Berat'  => 'kondisi-berat',
                    default        => 'kondisi-baik'
                  };
                @endphp
                <span class="kondisi-badge {{ $kondisiClass }}">{{ $kerusakan->kondisi }}</span>
              </td>
              <td style="color:#64748B">{{ $kerusakan->lokasi }}</td>
              <td>
                <button class="action-btn" title="Lihat Detail" onclick="viewDetail({{ $kerusakan->id }})">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="#94A3B8">
                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                  </svg>
                </button>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="8" style="text-align:center;padding:40px;color:var(--muted)">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="#CBD5E1" style="display:block;margin:0 auto 10px">
                  <path d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2z"/>
                </svg>
                Belum ada data kerusakan
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
        <div class="table-footer">
          <span>Menampilkan {{ $kerusakans->firstItem() ?? 0 }}–{{ $kerusakans->lastItem() ?? 0 }} dari {{ $kerusakans->total() ?? 0 }} data</span>
          <div class="pagination">
            @if($kerusakans->onFirstPage())
              <button class="page-btn" disabled>Prev</button>
            @else
              <a href="{{ $kerusakans->previousPageUrl() }}" class="page-btn">Prev</a>
            @endif
            @foreach($kerusakans->getUrlRange(1, $kerusakans->lastPage()) as $page => $url)
              <a href="{{ $url }}" class="page-btn {{ $kerusakans->currentPage() == $page ? 'active' : '' }}">{{ $page }}</a>
            @endforeach
            @if($kerusakans->hasMorePages())
              <a href="{{ $kerusakans->nextPageUrl() }}" class="page-btn">Next</a>
            @else
              <button class="page-btn" disabled>Next</button>
            @endif
          </div>
        </div>
      </div>

      <!-- Tab Hanya Rusak -->
      {{-- <div class="tab-panel" id="tab-rusak">
        <table>
          <thead>
            <tr>
              <th>Tanggal</th><th>Barang</th><th>Kode</th><th>Lokasi</th><th>Kondisi</th><th>Deskripsi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($kerusakans->whereIn('kondisi', ['Rusak Ringan', 'Rusak Berat']) as $kerusakan)
            <tr>
              <td>{{ \Carbon\Carbon::parse($kerusakans->tanggal_input)->format('d/m/Y') }}</td>
              <td style="font-weight:500;">{{ $kerusakans->nama_barang }}</td>
              <td><span class="kode-barang">{{ $kerusakans->kode_barang }}</span></td>
              <td>{{ $kerusakans->lokasi }}</td>
              <td>
                <span class="kondisi-badge {{ $kerusakans->kondisi == 'Rusak Ringan' ? 'kondisi-ringan' : 'kondisi-berat' }}">
                  {{ $kerusakans->kondisi }}
                </span>
              </td>
              <td style="font-size:12.5px;color:#64748B">{{ Str::limit($kerusakans->deskripsi ?? '—', 50) }}</td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--muted)">Belum ada laporan kerusakan</td></tr>
            @endforelse
          </tbody>
        </table>
      </div> --}}

      <!-- Tab Ringkasan Lokasi -->
      <div class="tab-panel" id="tab-lokasi">
        <table>
          <thead>
            <tr>
              <th>Lokasi</th>
              <th>Total</th>
              <th>Baik</th>
              <th>Ringan</th>
              <th>Berat</th>
              <th>% Rusak</th>
            </tr>
          </thead>
          <tbody>
            @foreach($stats['lokasi'] ?? [] as $lokasi => $data)
            <tr>
              <td><strong>{{ $lokasi }}</strong></td>
              <td>{{ $data['total'] }}</td>
              <td><span style="color:var(--green);font-weight:700">{{ $data['baik'] }}</span></td>
              <td><span style="color:var(--amber);font-weight:700">{{ $data['rusak_ringan'] }}</span></td>
              <td><span style="color:var(--red);font-weight:700">{{ $data['rusak_berat'] }}</span></td>
              <td style="color:var(--orange);font-weight:700">{{ number_format(($data['rusak_ringan'] + $data['rusak_berat'])/max(1,$data['total'])*100,1) }}%</td>
            </tr>
            @endforeach
            @if(empty($stats['lokasi']))
            <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--muted)">Belum ada data lokasi</td></tr>
            @endif
            <tr style="background:#F8FAFF;font-weight:700">
              <td><strong>Total</strong></td>
              <td><strong>{{ $kerusakans->total() ?? 0 }}</strong></td>
              <td style="color:var(--green)">{{ $stats['baik'] ?? 0 }}</td>
              <td style="color:var(--amber)">{{ $stats['rusak_ringan'] ?? 0 }}</td>
              <td style="color:var(--red)">{{ $stats['rusak_berat'] ?? 0 }}</td>
              <td style="color:var(--orange)">{{ number_format((($stats['rusak_ringan'] ?? 0) + ($stats['rusak_berat'] ?? 0))/max(1,$kerusakans->total())*100,1) }}%</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</main>

<script>
  // Tab switching
  document.querySelectorAll('.tab-btn[data-tab]').forEach(btn => {
    btn.addEventListener('click', function() {
      document.querySelectorAll('.tab-btn[data-tab]').forEach(b => b.classList.remove('active'));
      document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
      this.classList.add('active');
      document.getElementById('tab-' + this.dataset.tab).classList.add('active');
    });
  });

  // Filter table
  function filterTable() {
    const q = document.getElementById('searchInput').value.toLowerCase().trim();
    const kondisi = document.getElementById('kondisiFilter').value.toLowerCase();
    
    document.querySelectorAll('#tableBody tr').forEach(row => {
      const kode = row.dataset.kode || '';
      const nama = row.dataset.nama || '';
      const lokasi = row.dataset.lokasi || '';
      const rowKondisi = row.dataset.kondisi || '';
      
      const matchSearch = (!q || kode.includes(q) || nama.includes(q) || lokasi.includes(q));
      const matchKondisi = (!kondisi || rowKondisi.includes(kondisi));
      
      row.style.display = (matchSearch && matchKondisi) ? '' : 'none';
    });
  }

  // View detail (placeholder)
  function viewDetail(id) {
    alert('Fitur detail akan membuka modal atau halaman detail kerusakan ID: ' + id);
    // Bisa diintegrasikan dengan modal dari halaman sebelumnya
  }

  // Auto-hide flash alerts
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.alert-flash').forEach(el => {
      setTimeout(() => {
        el.style.transition = 'opacity .4s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 400);
      }, 5000);
    });
  });
</script>

</body>
</html>
