<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIPANDU - Dashboard Persediaan</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  :root {
    --blue: #4F6FFF; --green: #22C55E; --purple: #A855F7; --orange: #F97316;
    --teal: #14B8A6; --pink: #EC4899; --indigo: #6366F1; --amber: #F59E0B;
    --sidebar-w: 240px; --radius: 16px; --bg: #F4F6FB; --surface: #FFFFFF;
    --text: #1E293B; --muted: #94A3B8; --border: #E8EDF5;
  }
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); display: flex; min-height: 100vh; }

  .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
  .topbar { background: var(--surface); border-bottom: 1px solid var(--border); padding: 0 28px; height: 56px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 50; }
  .topbar-title { font-size: 16px; font-weight: 700; }
  .topbar-right { display: flex; align-items: center; gap: 16px; }
  .notif-btn { width: 36px; height: 36px; border-radius: 50%; border: 1px solid var(--border); background: var(--surface); display: flex; align-items: center; justify-content: center; cursor: pointer; position: relative; }
  .notif-dot { width: 8px; height: 8px; background: #EF4444; border-radius: 50%; position: absolute; top: 6px; right: 6px; border: 2px solid white; }
  .date-text { font-size: 13px; color: #64748B; font-weight: 500; }
  .btn-keluar { display: flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface); color: #64748B; font-size: 13px; font-weight: 600; font-family: inherit; cursor: pointer; transition: all .15s; }
  .btn-keluar:hover { background: #FEF2F2; color: #EF4444; }

  .content { padding: 28px; flex: 1; }
  .page-header { margin-bottom: 24px; }
  .page-header h1 { font-size: 22px; font-weight: 800; margin-bottom: 4px; }
  .page-header p { font-size: 13.5px; color: var(--muted); }

  .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 28px; }
  .stat-card { background: var(--surface); border-radius: var(--radius); padding: 20px; position: relative; overflow: hidden; border: 1px solid var(--border); transition: transform .2s, box-shadow .2s; }
  .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.07); }
  .stat-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 14px; }
  .stat-icon svg { width: 22px; height: 22px; fill: white; }
  .stat-badge { position: absolute; top: 16px; right: 16px; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 20px; }
  .stat-badge.pos { background: #DCFCE7; color: #16A34A; }
  .stat-badge.neg { background: #FEE2E2; color: #DC2626; }
  .stat-badge.neu { background: #F1F5F9; color: #64748B; }
  .stat-value { font-size: 26px; font-weight: 800; margin-bottom: 4px; }
  .stat-label { font-size: 13px; color: var(--muted); font-weight: 500; }

  .bg-blue { background: linear-gradient(135deg, #4F6FFF, #7C3AED); } .bg-green { background: linear-gradient(135deg, #10B981, #059669); }
  .bg-purple { background: linear-gradient(135deg, #A855F7, #7C3AED); } .bg-orange { background: linear-gradient(135deg, #F97316, #EF4444); }
  .bg-teal { background: linear-gradient(135deg, #14B8A6, #0891B2); } .bg-pink { background: linear-gradient(135deg, #EC4899, #F97316); }
  .bg-indigo { background: linear-gradient(135deg, #6366F1, #4F6FFF); } .bg-amber { background: linear-gradient(135deg, #F59E0B, #F97316); }

  .card-bg-blue { background: linear-gradient(135deg, #EEF2FF 0%, #F8FAFF 100%); } .card-bg-green { background: linear-gradient(135deg, #ECFDF5 0%, #F8FFFB 100%); }
  .card-bg-purple { background: linear-gradient(135deg, #FAF5FF 0%, #F8FAFF 100%); } .card-bg-orange { background: linear-gradient(135deg, #FFF7ED 0%, #FFFBF5 100%); }
  .card-bg-teal { background: linear-gradient(135deg, #F0FDFA 0%, #F8FFFE 100%); } .card-bg-pink { background: linear-gradient(135deg, #FDF2F8 0%, #FFF8FC 100%); }
  .card-bg-indigo { background: linear-gradient(135deg, #EEF2FF 0%, #F5F7FF 100%); } .card-bg-amber { background: linear-gradient(135deg, #FFFBEB 0%, #FFFDF5 100%); }

  .chart-card { background: var(--surface); border-radius: var(--radius); border: 1px solid var(--border); padding: 24px; }
  .chart-title { font-size: 16px; font-weight: 700; margin-bottom: 20px; }
  .chart-area { position: relative; height: 180px; margin-bottom: 8px; }
  .chart-bars { display: flex; align-items: flex-end; gap: 10px; height: 100%; }
  .chart-col { display: flex; flex-direction: column; align-items: center; flex: 1; gap: 6px; height: 100%; justify-content: flex-end; }
  .bar-wrap { flex: 1; display: flex; align-items: flex-end; width: 100%; height: 100%; }
  .bar { width: 100%; border-radius: 6px 6px 0 0; background: linear-gradient(180deg, var(--blue), #7C3AED); opacity: .85; transition: opacity .2s; min-height: 4px; }
  .bar:hover { opacity: 1; }
  .bar-val { font-size: 11px; font-weight: 700; color: var(--text); }
  .bar-label { font-size: 11px; color: var(--muted); }
  
  @media (max-width: 1024px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
  }
  @media (max-width: 768px) {
    .main { margin-left: 0; }
    .stats-grid { grid-template-columns: 1fr; }
    .topbar { padding-top: 60px; flex-wrap: wrap; gap: 8px; }
    .content { padding: 16px; }
    .page-header h1 { font-size: 18px; }
  }
</style>
</head>
<body>

@include('partials.sidebar')

<!-- MAIN -->
<main class="main">
  <div class="topbar">
    <span class="topbar-title">Dashboard Admin Persediaan</span>
    <div class="topbar-right">
      {{-- <div class="notif-btn">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="#64748B"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
        <span class="notif-dot"></span>
      </div> --}}
      <span class="date-text">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}</span>
      <form method="POST" action="{{ route('logout', [], false) }}" style="display:inline;">
          @csrf
          <button type="submit" class="btn-keluar">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5-5-5zm-5 11H5V5h7V3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h7v-2z"/></svg>
            Keluar
          </button>
      </form>
    </div>
  </div>

  <div class="content">
    <div class="page-header">
      <h1>Selamat Datang di Sistem Manajemen Persediaan</h1>
      <p>Ringkasan data persediaan dan transaksi barang Anda hari ini</p>
    </div>

    <!-- Row 1: Persediaan & Transaksi -->
    <div class="stats-grid">
      <div class="stat-card card-bg-blue">
        <div class="stat-icon bg-blue">
          <svg viewBox="0 0 24 24"><path d="M4 4h4v4H4zm6 0h4v4h-4zm6 0h4v4h-4zM4 10h4v4H4zm6 0h4v4h-4zm6 0h4v4h-4zM4 16h4v4H4zm6 0h4v4h-4zm6 0h4v4h-4z"/></svg>
        </div>
        <div class="stat-value">{{ number_format($totalPersediaan, 0, ',', '.') }}</div>
        <div class="stat-label">Total Jenis Barang</div>
      </div>
      
      <div class="stat-card card-bg-green">
        <div class="stat-icon bg-green">
          <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
        </div>
        <div class="stat-value">Rp {{ number_format($totalNilaiPersediaan, 0, ',', '.') }}</div>
        <div class="stat-label">Total Nilai Persediaan</div>
      </div>
      
      <div class="stat-card card-bg-purple">
        <div class="stat-icon bg-purple">
          <svg viewBox="0 0 24 24"><path d="M20 6h-2.18c.07-.44.18-.88.18-1.3C18 2.12 15.88 0 13.3 0c-1.3 0-2.4.56-3.2 1.44L9 3.5 7.9 1.44C7.1.56 6 0 4.7 0 2.12 0 0 2.12 0 4.7c0 .42.11.86.18 1.3H0v2h20V6z"/></svg>
        </div>
        <div class="stat-value">{{ number_format($masukBulanIni, 0, ',', '.') }}</div>
        <div class="stat-label">Transaksi Masuk (Bulan Ini)</div>
      </div>
      
      <div class="stat-card card-bg-orange">
        <div class="stat-icon bg-orange">
          <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
        </div>
        <div class="stat-value">{{ number_format($keluarBulanIni, 0, ',', '.') }}</div>
        <div class="stat-label">Transaksi Keluar (Bulan Ini)</div>
      </div>
    </div>

    <!-- Row 2: Status Permintaan -->
    <div class="stats-grid" style="margin-bottom:28px">
      <div class="stat-card card-bg-teal">
        <div class="stat-icon bg-teal">
          <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/></svg>
        </div>
        <div class="stat-value">{{ number_format($permintaanDisetujui, 0, ',', '.') }}</div>
        <div class="stat-label">Permintaan Disetujui</div>
      </div>
      
      <div class="stat-card card-bg-amber">
        <div class="stat-icon bg-amber">
          <svg viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67V7z"/></svg>
        </div>
        <div class="stat-value">{{ number_format($permintaanPending, 0, ',', '.') }}</div>
        <div class="stat-label">Menunggu Persetujuan</div>
      </div>
      
      <div class="stat-card card-bg-pink">
        <div class="stat-icon bg-pink">
          <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
        </div>
        <div class="stat-value">{{ number_format($permintaanDitolak, 0, ',', '.') }}</div>
        <div class="stat-label">Permintaan Ditolak</div>
      </div>
      
      <div class="stat-card card-bg-indigo">
        <div class="stat-icon bg-indigo">
          <svg viewBox="0 0 24 24"><path d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 4h14v-2H7v2zm0 4h14v-2H7v2zM7 7v2h14V7H7z"/></svg>
        </div>
        <div class="stat-value">{{ number_format($totalPermintaan, 0, ',', '.') }}</div>
        <div class="stat-label">Total Semua Permintaan</div>
      </div>
    </div>
  </div>
</main>

</body>
</html>
