<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIMASET - Laporan Mutasi Barang</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
  /* 1. Lengkapi CSS Variables */
:root {
  --blue: #4F6FFF;
  --blue-dark: #3B5BDB;
  --green: #10B981;
  --red: #EF4444;
  --purple: #8B5CF6;
  --amber: #F59E0B;
  --teal: #14B8A6;
  --orange: #F97316;
  --sidebar-w: 260px;  /* ✅ Sesuaikan dengan sidebar */
  --radius: 16px;
  --bg: #F4F6FB;
  --surface: #FFFFFF;
  --text-primary: #1E293B;      /* ✅ TAMBAHKAN */
  --text-secondary: #64748B;    /* ✅ TAMBAHKAN */
  --text-muted: #94A3B8;
  --border: #E8EDF5;
  --shadow-sm: 0 1px 2px 0 rgba(16, 24, 40, 0.05);
  --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  --primary: var(--blue);
  --main-bg: #F8FAFC;           /* ✅ TAMBAHKAN */
  --sidebar-bg: #FFFFFF;        /* ✅ TAMBAHKAN */
  --sidebar-active-bg: #EFF6FF; /* ✅ TAMBAHKAN */
  --sidebar-active-text: #1E293B; /* ✅ TAMBAHKAN */
  --card-bg: #FFFFFF;           /* ✅ TAMBAHKAN */
  --primary-light: #EFF6FF;     /* ✅ TAMBAHKAN */
  --primary-dark: #3B5BDB;      /* ✅ TAMBAHKAN */
  --accent-green-light: #ECFDF5; /* ✅ TAMBAHKAN */
  --accent-orange-light: #FEF3C7; /* ✅ TAMBAHKAN */
  --accent-purple-light: #F3E8FF; /* ✅ TAMBAHKAN */
}

/* 2. Perbaiki Body */
body {
  font-family: 'Plus Jakarta Sans', sans-serif;
  background: var(--main-bg);
  color: var(--text-primary);
  display: flex;
  min-height: 100vh;    /* ✅ Ubah dari height */
}

/* 3. Perbaiki Main Content */
.main { 
  margin-left: var(--sidebar-w);
  flex: 1; 
  display: flex; 
  flex-direction: column;
  min-height: 100vh;    /* ✅ Tambahkan */
  overflow: hidden;     /* ✅ Biarkan main tidak scroll */
}

.content { 
  padding: 28px; 
  flex: 1; 
  overflow-y: auto;     /* ✅ SCROLL DI SINI */
}

/* 4. Table Responsive */
.table-card {
  max-height: 600px;    /* ✅ Batasi tinggi */
  overflow: hidden;
}

#mutasiTable {
  max-height: 500px;    /* ✅ Batasi tinggi table */
  overflow-y: auto;     /* ✅ Scroll table */
  display: block;
}

#mutasiTable thead,
#mutasiTable tbody,
#mutasiTable tr {
  display: table;
  width: 100%;
  table-layout: fixed;  /* ✅ Fixed layout */
}

/* 5. Custom Scrollbar (Opsional - Biar Cantik) */
.content::-webkit-scrollbar,
#mutasiTable::-webkit-scrollbar {
  width: 8px;
}

.content::-webkit-scrollbar-track,
#mutasiTable::-webkit-scrollbar-track {
  background: #F1F5F9;
  border-radius: 10px;
}

.content::-webkit-scrollbar-thumb,
#mutasiTable::-webkit-scrollbar-thumb {
  background: var(--blue);
  border-radius: 10px;
}

.content::-webkit-scrollbar-thumb:hover,
#mutasiTable::-webkit-scrollbar-thumb:hover {
  background: var(--blue-dark);
}

  /* ─── MAIN CONTENT ─── */
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

  /* ─── TABLE ─── */
  .table-card {
    background: var(--card-bg);
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border);
    overflow: hidden;
    animation: fadeUp 0.4s ease 0.30s both;
    margin-bottom: 10px;
  }

  .table-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 22px 14px;
    border-bottom: 1px solid var(--border);
  }
  .table-header h3 { font-size: 15px; font-weight: 700; }

  .filter-row {
    display: flex; gap: 10px; align-items: center;
    padding: 12px 22px;
    border-bottom: 1px solid var(--border);
    background: #fafbfd;
  }

  .filter-input, .filter-select {
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 13px;
    font-family: inherit;
    color: var(--text-primary);
    background: white;
    outline: none;
    transition: border-color 0.2s;
  }
  .filter-input:focus, .filter-select:focus { border-color: var(--primary); }
  .filter-input { flex: 1; }

  .btn-export {
    padding: 8px 16px;
    background: var(--primary);
    color: white;
    border: none; border-radius: 8px;
    font-size: 13px; font-weight: 600;
    font-family: inherit;
    cursor: pointer;
    display: flex; align-items: center; gap: 6px;
    transition: background 0.2s, transform 0.1s;
  }
  .btn-export:hover { background: var(--primary-dark); }
  .btn-export svg { width: 14px; height: 14px; }

  table {
    width: 100%; border-collapse: collapse;
  }

  thead th {
    padding: 12px 18px;
    font-size: 11px; font-weight: 700;
    letter-spacing: 0.8px; text-transform: uppercase;
    color: var(--primary);
    background: #f8fffe;
    text-align: left;
    border-bottom: 1px solid var(--border);
  }

  tbody tr {
    border-bottom: 1px solid var(--border);
    transition: background 0.15s;
  }
  tbody tr:last-child { border-bottom: none; }
  tbody tr:hover { background: #f8fffe; }

  tbody td {
    padding: 13px 18px;
    font-size: 13.5px;
    vertical-align: middle;
  }

  .id-cell {
    font-family: 'DM Mono', monospace;
    font-size: 12.5px;
    color: var(--primary-dark);
    font-weight: 500;
  }

  .date-cell {
    font-family: 'DM Mono', monospace;
    font-size: 12.5px;
    color: var(--text-secondary);
  }

  .item-name { font-weight: 600; }

  .qty-cell {
    font-weight: 700;
    font-size: 15px;
    color: var(--text-primary);
  }

  .loc-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
  }
  .loc-a { background: var(--primary-light); color: var(--primary-dark); }
  .loc-b { background: var(--accent-green-light); color: #047857; }
  .loc-pusat { background: #f0f9ff; color: #0369a1; }
  .loc-dept { background: var(--accent-purple-light); color: #6d28d9; }

  .status-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; border-radius: 20px;
    font-size: 11.5px; font-weight: 600;
  }
  .status-selesai { background: var(--accent-green-light); color: #047857; }
  .status-proses { background: var(--accent-orange-light); color: #92400e; }
  .status-dot { width: 6px; height: 6px; border-radius: 50%; }
  .status-selesai .status-dot { background: #10b981; }
  .status-proses .status-dot { background: #f59e0b; }

  .pagination {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 22px;
    border-top: 1px solid var(--border);
    background: #fafbfd;
  }
  .pagination span { font-size: 13px; color: var(--text-muted); }
  .page-btns { display: flex; gap: 6px; }
  .page-btn {
    width: 32px; height: 32px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 600;
    cursor: pointer; border: 1px solid var(--border);
    background: white; color: var(--text-secondary);
    transition: all 0.15s;
  }
  .page-btn:hover, .page-btn.active {
    background: var(--primary); color: white; border-color: var(--primary);
  }

  /* ─── ANIMATIONS ─── */
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(14px); }
    to { opacity: 1; transform: translateY(0); }
  }

  /* ─── SCROLLBAR ─── */
  .progress-bar { background: #F1F5F9; border-radius: 20px; height: 8px; margin-top: 10px; overflow: hidden; }
  .progress-fill { height: 100%; border-radius: 20px; }
</style>
</head>
<body>

@include('partials.sidebar')

<!-- ══════════════════ MAIN ══════════════════ -->
<!-- MAIN -->
<main class="main">
  <div class="topbar">
    <span class="topbar-title">Laporan Mutasi Barang </span>
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
        <h1>Laporan Mutasi Barang</h1>
        <p>Analisis Mutasi Barang</p>
      </div>
      <button class="btn-unduh">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="white"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>
        Unduh Laporan
      </button>
    </div>

    <!-- STAT ROW 1 -->
    <div class="stats-grid-3">
      <div class="stat-card">
        <div class="stat-header">
          <div class="stat-icon ic-blue">
            <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/></svg>
          </div>
          <span class="stat-label-sm">Total Mutasi</span>
        </div>
        <div class="stat-value" style="color:var(--blue)">3</div>
        <div class="stat-sub">Keseluruhan</div>
        <div class="stat-trend trend-up">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M7 14l5-5 5 5z"/></svg>
          +2 dari bulan lalu
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-header">
          <div class="stat-icon ic-green">
            <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/></svg>
          </div>
          <span class="stat-label-sm">Dalam Proses</span>
        </div>
        <div class="stat-value" style="color:var(--green)">0</div>
        <div class="stat-sub">0% dari total</div>
        <div class="stat-trend trend-neu">— Tidak ada aktif</div>
      </div>
      <div class="stat-card">
        <div class="stat-header">
          <div class="stat-icon ic-red">
            <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
          </div>
          <span class="stat-label-sm">Selesai</span>
        </div>
        <div class="stat-value" style="color:var(--red)">0</div>
        <div class="stat-sub">Perlu tindakan</div>
        <div class="stat-trend" style="color:var(--green)">✓ Semua tepat waktu</div>
      </div>
    </div>

    <!-- STAT ROW 2 -->
    <div class="stats-grid-3">
      <div class="stat-card" style="background:linear-gradient(135deg,#EFF6FF,#F5F8FF)">
        <div class="stat-header">
          <div class="stat-icon ic-blue">
            <svg viewBox="0 0 24 24"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
          </div>
          <span class="stat-label-sm">Status Mutasi Bulan Ini</span>
        </div>
        <div style="font-size:22px;font-weight:800;color:var(--blue);margin-bottom:12px">3 <span style="font-size:14px;color:var(--muted);font-weight:500">transaksi</span></div>
        <div style="display:flex;gap:14px">
          <div style="text-align:center">
            <div style="font-size:20px;font-weight:800;color:var(--green)">1</div>
            <div style="font-size:11px;color:#64748B">Disetujui</div>
          </div>
          <div style="text-align:center">
            <div style="font-size:20px;font-weight:800;color:var(--amber)">2</div>
            <div style="font-size:11px;color:#64748B">Pending</div>
          </div>
          <div style="text-align:center">
            <div style="font-size:20px;font-weight:800;color:var(--red)">0</div>
            <div style="font-size:11px;color:#64748B">Ditolak</div>
          </div>
        </div>
      </div>
      <div class="stat-card" style="background:linear-gradient(135deg,#ECFDF5,#F5FFF9)">
        <div class="stat-header">
          <div class="stat-icon ic-teal">
            <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/></svg>
          </div>
          <span class="stat-label-sm">Tingkat Persetujuan</span>
        </div>
        <div style="font-size:22px;font-weight:800;color:var(--teal);margin-bottom:8px">33% <span style="font-size:13px;color:var(--muted);font-weight:500">disetujui</span></div>
        <div class="progress-bar">
          <div class="progress-fill" style="background:var(--teal);width:33%"></div>
        </div>
        <div style="font-size:11.5px;color:#64748B;margin-top:8px">1 dari 3 peminjaman disetujui</div>
      </div>
      <div class="stat-card" style="background:linear-gradient(135deg,#FFF7ED,#FFFBF5)">
        <div class="stat-header">
          <div class="stat-icon ic-orange">
            <svg viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67V7z"/></svg>
          </div>
          <span class="stat-label-sm">Rata-rata Mutasi Barang</span>
        </div>
        <div style="font-size:22px;font-weight:800;color:var(--orange);margin-bottom:8px">7 <span style="font-size:13px;color:var(--muted);font-weight:500">hari</span></div>
        <div style="font-size:11.5px;color:#64748B">Per transaksi peminjaman</div>
        <div style="font-size:11.5px;color:var(--orange);font-weight:600;margin-top:6px">↑ 1 dari jumlah rata-rata</div>
      </div>
    </div>

    <!-- CHARTS ROW -->
    <div style="margin-bottom: 20px; max-width: 420px;">
      <div class="chart-card">
        <div class="chart-title">Distribusi Status</div>
        <div class="chart-sub">Komposisi per status</div>
        <svg class="donut-svg" viewBox="0 0 150 150">
          <circle cx="75" cy="75" r="55" fill="none" stroke="#F1F5F9" stroke-width="26"/>
          <circle cx="75" cy="75" r="55" fill="none" stroke="#F59E0B" stroke-width="26"
            stroke-dasharray="231.2 114.5" stroke-dashoffset="86.4" stroke-linecap="butt"/>
          <circle cx="75" cy="75" r="55" fill="none" stroke="#10B981" stroke-width="26"
            stroke-dasharray="114.5 231.2" stroke-dashoffset="86.4" stroke-linecap="butt"
            transform="rotate(-119.7 75 75)"/>
          <text x="75" y="71" text-anchor="middle" dominant-baseline="middle" font-size="20" font-weight="800" fill="#1E293B" font-family="Plus Jakarta Sans">3</text>
          <text x="75" y="87" text-anchor="middle" font-size="10" fill="#94A3B8" font-family="Plus Jakarta Sans">transaksi</text>
        </svg>
        <div class="donut-labels">
          <div class="donut-row">
            <div class="donut-name"><div class="donut-dot" style="background:#10B981"></div>Disetujui</div>
            <div class="donut-pct" style="color:#10B981">1 <span style="color:var(--muted);font-weight:400;font-size:11px">33%</span></div>
          </div>
          <div class="donut-row">
            <div class="donut-name"><div class="donut-dot" style="background:#F59E0B"></div>Pending</div>
            <div class="donut-pct" style="color:#F59E0B">2 <span style="color:var(--muted);font-weight:400;font-size:11px">67%</span></div>
          </div>
          <div class="donut-row">
            <div class="donut-name"><div class="donut-dot" style="background:#EF4444"></div>Ditolak</div>
            <div class="donut-pct" style="color:#EF4444">0 <span style="color:var(--muted);font-weight:400;font-size:11px">0%</span></div>
          </div>
          <div class="donut-row">
            <div class="donut-name"><div class="donut-dot" style="background:#8B5CF6"></div>Selesai</div>
            <div class="donut-pct" style="color:#8B5CF6">0 <span style="color:var(--muted);font-weight:400;font-size:11px">0%</span></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="table-card">
      <div class="table-header">
        <h3>Detail Mutasi Barang</h3>
      </div>
      <div class="filter-row">
        <input class="filter-input" type="text" placeholder="🔍  Cari nama barang / ID mutasi…" id="searchInput" oninput="filterTable()">
        <select class="filter-select" id="statusFilter" onchange="filterTable()">
          <option value="">Semua Status</option>
          <option value="Selesai">Selesai</option>
          <option value="Dalam Proses">Dalam Proses</option>
        </select>
        <button class="btn-export" onclick="exportCSV()">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          Export CSV
        </button>
      </div>

      <div style="overflow-x:auto">
        <table id="mutasiTable">
          <thead>
            <tr>
              <th>ID Mutasi</th>
              <th>Tanggal</th>
              <th>Nama Barang</th>
              <th>QTY</th>
              <th>Dari Lokasi</th>
              <th>Ke Lokasi</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody id="tableBody">
            <tr>
              <td class="id-cell">MT-101</td>
              <td class="date-cell">2025-01-15</td>
              <td class="item-name">Toner Printer</td>
              <td class="qty-cell">20</td>
              <td><span class="loc-badge loc-a">Gudang A</span></td>
              <td><span class="loc-badge loc-b">Gudang B</span></td>
              <td><span class="status-badge status-selesai"><span class="status-dot"></span>Selesai</span></td>
            </tr>
            <tr>
              <td class="id-cell">MT-102</td>
              <td class="date-cell">2025-01-14</td>
              <td class="item-name">Kertas Folio</td>
              <td class="qty-cell">50</td>
              <td><span class="loc-badge loc-pusat">Gudang Pusat</span></td>
              <td><span class="loc-badge loc-dept">Dept. IT</span></td>
              <td><span class="status-badge status-selesai"><span class="status-dot"></span>Selesai</span></td>
            </tr>
            <tr>
              <td class="id-cell">MT-103</td>
              <td class="date-cell">2025-01-13</td>
              <td class="item-name">Buku Tulis</td>
              <td class="qty-cell">30</td>
              <td><span class="loc-badge loc-b">Gudang B</span></td>
              <td><span class="loc-badge loc-a">Gudang A</span></td>
              <td><span class="status-badge status-proses"><span class="status-dot"></span>Dalam Proses</span></td>
            </tr>
            <tr>
              <td class="id-cell">MT-104</td>
              <td class="date-cell">2025-01-10</td>
              <td class="item-name">Spidol Whiteboard</td>
              <td class="qty-cell">15</td>
              <td><span class="loc-badge loc-pusat">Gudang Pusat</span></td>
              <td><span class="loc-badge loc-a">Gudang A</span></td>
              <td><span class="status-badge status-selesai"><span class="status-dot"></span>Selesai</span></td>
            </tr>
            <tr>
              <td class="id-cell">MT-105</td>
              <td class="date-cell">2025-01-08</td>
              <td class="item-name">Mouse Wireless</td>
              <td class="qty-cell">10</td>
              <td><span class="loc-badge loc-a">Gudang A</span></td>
              <td><span class="loc-badge loc-dept">Dept. IT</span></td>
              <td><span class="status-badge status-proses"><span class="status-dot"></span>Dalam Proses</span></td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="pagination">
        <span>Menampilkan 1–5 dari 5 data</span>
        <div class="page-btns">
          <div class="page-btn">‹</div>
          <div class="page-btn active">1</div>
          <div class="page-btn">2</div>
          <div class="page-btn">›</div>
        </div>
      </div>
    </div>

  </div><!-- /page-content -->
</div><!-- /main -->

<!-- Chart.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
// ─── Chart ───
const ctx = document.getElementById('trendChart').getContext('2d');
const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
const data   = [6, 9, 8, 11, 7, 10, 12, 9, 13, 11, 10, 14];

const gradient = ctx.createLinearGradient(0, 0, 0, 160);
gradient.addColorStop(0, 'rgba(11,191,191,0.25)');
gradient.addColorStop(1, 'rgba(11,191,191,0.00)');

new Chart(ctx, {
  type: 'line',
  data: {
    labels: months,
    datasets: [{
      data,
      borderColor: '#0bbfbf',
      borderWidth: 2.5,
      pointBackgroundColor: '#0bbfbf',
      pointBorderColor: '#fff',
      pointBorderWidth: 2,
      pointRadius: 4,
      pointHoverRadius: 6,
      backgroundColor: gradient,
      fill: true,
      tension: 0.4,
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false }, tooltip: {
      backgroundColor: '#1a2332',
      padding: 10,
      titleFont: { family: 'Plus Jakarta Sans', size: 12 },
      bodyFont:  { family: 'Plus Jakarta Sans', size: 13 },
      callbacks: { label: ctx => `  ${ctx.raw} mutasi` }
    }},
    scales: {
      x: { grid: { display: false }, ticks: { font: { family:'Plus Jakarta Sans', size:11 }, color:'#94a3b8' } },
      y: { display: false, grid: { display: false } }
    }
  }
});

// ─── Filter ───
function filterTable() {
  const search = document.getElementById('searchInput').value.toLowerCase();
  const status = document.getElementById('statusFilter').value;
  document.querySelectorAll('#tableBody tr').forEach(tr => {
    const text = tr.textContent.toLowerCase();
    const rowStatus = tr.querySelector('.status-badge')?.textContent.trim() || '';
    const matchSearch = text.includes(search);
    const matchStatus = !status || rowStatus.includes(status);
    tr.style.display = matchSearch && matchStatus ? '' : 'none';
  });
}

// ─── Export CSV ───
function exportCSV() {
  const rows = [['ID Mutasi','Tanggal','Nama Barang','QTY','Dari Lokasi','Ke Lokasi','Status']];
  document.querySelectorAll('#tableBody tr').forEach(tr => {
    if (tr.style.display === 'none') return;
    const cells = tr.querySelectorAll('td');
    rows.push([...cells].map(td => td.textContent.trim()));
  });
  const csv = rows.map(r => r.join(',')).join('\n');
  const a = document.createElement('a');
  a.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv);
  a.download = 'laporan-mutasi-barang.csv';
  a.click();
}
</script>
</body>
</html>