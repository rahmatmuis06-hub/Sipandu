<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIMASET - Laporan Transaksi Masuk</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  :root {
    --green: #10B981;
    --green-light: #ECFDF5;
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
  .notif-dot { width: 8px; height: 8px; background: #EF4444; border-radius: 50%; position: absolute; top: 6px; right: 6px; border: 2px solid white; }
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
  .page-top h1 { font-size: 22px; font-weight: 800; color: var(--green); margin-bottom: 4px; }
  .page-top p { font-size: 13px; color: var(--muted); }
  .btn-unduh {
    display: flex; align-items: center; gap: 7px;
    padding: 10px 18px; border-radius: 10px;
    background: var(--green);
    color: white; font-size: 13.5px; font-weight: 700;
    font-family: inherit; border: none; cursor: pointer;
    box-shadow: 0 4px 14px rgba(16,185,129,.3);
    transition: all .2s;
  }
  .btn-unduh:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(16,185,129,.4); }

  /* STAT CARDS */
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
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

  .ic-green { background: #DCFCE7; }
  .ic-green svg { fill: var(--green); }
  .ic-teal { background: #ECFDF5; }
  .ic-teal svg { fill: #0D9488; }
  .ic-amber { background: #FEF3C7; }
  .ic-amber svg { fill: var(--amber); }
  .ic-purple { background: #F5F3FF; }
  .ic-purple svg { fill: var(--purple); }

  /* CHART */
  .chart-card {
    background: linear-gradient(135deg, #ECFDF5 0%, #F0FDF9 100%);
    border-radius: var(--radius);
    border: 1px solid #BBF7D0;
    padding: 24px;
    margin-bottom: 24px;
  }
  .chart-title { font-size: 16px; font-weight: 700; color: var(--text); margin-bottom: 20px; }
  .chart-area { height: 150px; display: flex; align-items: flex-end; gap: 8px; margin-bottom: 8px; }
  .chart-col { display: flex; flex-direction: column; align-items: center; flex: 1; gap: 6px; }
  .bar-wrap { flex: 1; display: flex; align-items: flex-end; width: 100%; }
  .bar {
    width: 100%; border-radius: 6px 6px 0 0;
    background: linear-gradient(180deg, #10B981, #059669);
    min-height: 4px;
    transition: opacity .2s;
  }
  .bar:hover { opacity: .8; }
  .bar-val { font-size: 10px; font-weight: 700; color: #064E3B; }
  .bar-label { font-size: 10px; color: #6B7280; }
  .chart-baseline { border-top: 1.5px solid #D1FAE5; }

  /* TABLE */
  .table-card {
    background: var(--surface);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    overflow: hidden;
  }
  table { width: 100%; border-collapse: collapse; }
  thead tr { background: #F0FDF4; }
  th {
    padding: 13px 20px;
    text-align: left;
    font-size: 11px; font-weight: 700;
    color: var(--green); letter-spacing: .8px; text-transform: uppercase;
    border-bottom: 1.5px solid #D1FAE5;
  }
  td {
    padding: 14px 20px;
    font-size: 13.5px; color: var(--text);
    border-bottom: 1px solid var(--border);
  }
  tr:last-child td { border-bottom: none; }
  tbody tr:hover { background: #F0FDF4; }

  .id-cell { font-weight: 700; color: var(--green); }
  .nilai-cell { font-weight: 700; color: var(--green); }

  .status-badge {
    display: inline-block; padding: 4px 12px; border-radius: 20px;
    font-size: 12px; font-weight: 700;
  }
  .status-diterima { background: #DCFCE7; color: #15803D; }
  .status-pending { background: #FEF3C7; color: #B45309; }
</style>
</head>
<body>
@include('partials.sidebar')
<main class="main">

<div class="topbar">
  <span class="topbar-title">Laporan Transaksi Masuk</span>
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
  <div class="page-top">
    <div>
      <h1>Laporan Transaksi Masuk</h1>
      <p>Detail dan analisis semua transaksi masuk aset</p>
    </div>
    <button class="btn-unduh">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>
      Unduh Laporan
    </button>
  </div>

  <!-- Stats -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-header">
        <div class="stat-icon ic-green">
          <svg width="16" height="16" viewBox="0 0 24 24"><path d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2z"/></svg>
        </div>
        <span class="stat-label-sm">Total Transaksi</span>
      </div>
      <div class="stat-value">3</div>
      <div class="stat-sub">Bulan ini</div>
    </div>
    <div class="stat-card">
      <div class="stat-header">
        <div class="stat-icon ic-teal">
          <svg width="16" height="16" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/></svg>
        </div>
        <span class="stat-label-sm">Diterima</span>
      </div>
      <div class="stat-value" style="color:#10B981">2</div>
      <div class="stat-sub">67%</div>
    </div>
    <div class="stat-card">
      <div class="stat-header">
        <div class="stat-icon ic-amber">
          <svg width="16" height="16" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
        </div>
        <span class="stat-label-sm">Pending</span>
      </div>
      <div class="stat-value" style="color:#F59E0B">1</div>
      <div class="stat-sub">33%</div>
    </div>
    <div class="stat-card">
      <div class="stat-header">
        <div class="stat-icon ic-purple">
          <svg width="16" height="16" viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
        </div>
        <span class="stat-label-sm">Total Nilai</span>
      </div>
      <div class="stat-value" style="font-size:22px">Rp 202 Jt</div>
      <div class="stat-sub">Estimasi</div>
    </div>
  </div>

  <!-- Table -->
  <div class="table-card">
    <table>
      <thead>
        <tr>
          <th>ID Transaksi</th>
          <th>Tanggal</th>
          <th>Nama Aset</th>
          <th>Kategori</th>
          <th>QTY</th>
          <th>Nilai</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="id-cell">TM-001</td>
          <td>2025-01-15</td>
          <td>Laptop Dell Latitude</td>
          <td>Elektronik</td>
          <td>10</td>
          <td class="nilai-cell">Rp 150.000.000</td>
          <td><span class="status-badge status-diterima">Diterima</span></td>
        </tr>
        <tr>
          <td class="id-cell">TM-002</td>
          <td>2025-01-14</td>
          <td>Meja Kerja Executive</td>
          <td>Furnitur</td>
          <td>5</td>
          <td class="nilai-cell">Rp 25.000.000</td>
          <td><span class="status-badge status-diterima">Diterima</span></td>
        </tr>
        <tr>
          <td class="id-cell">TM-003</td>
          <td>2025-01-13</td>
          <td>AC Split 2 PK</td>
          <td>Elektronik</td>
          <td>3</td>
          <td class="nilai-cell" style="color:#F59E0B">Rp 27.000.000</td>
          <td><span class="status-badge status-pending">Pending</span></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
</main>
</body>
</html>