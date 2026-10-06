<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Kasubag - SIPANDU</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  :root {
    --primary: #2563eb; --primary-light: #3b82f6; --primary-dark: #1d4ed8;
    --accent: #06b6d4; --accent2: #8b5cf6;
    --success: #10b981; --warning: #f59e0b; --danger: #ef4444;
    --bg: #f0f4ff; --card-bg: #ffffff;
    --text-primary: #0f172a; --text-secondary: #64748b;
    --border: #e2e8f0; --radius: 16px; --radius-sm: 10px;
    --shadow: 0 4px 24px rgba(37,99,235,0.08);
    --blue: #3b6ff0; --blue-light: #eef2ff;
    --orange: #f59e0b; --orange-light: #fffbeb;
    --green: #10b981; --green-light: #ecfdf5;
    --red: #ef4444; --red-light: #fef2f2;
    --purple: #8b5cf6; --purple-light: #f5f3ff;
    --gray-50: #f8fafc; --gray-100: #f1f5f9; --gray-200: #e2e8f0;
    --gray-400: #94a3b8; --gray-600: #475569; --gray-800: #1e293b;
    --sidebar-w: 240px;
  }
  body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--gray-800); display: flex; min-height: 100vh; }

  /* MAIN CONTENT */
  .main { margin-left: var(--sidebar-w); flex: 1; padding: 0 32px 32px; }

  /* TOP BAR */
  .topbar { display: flex; align-items: center; justify-content: space-between; padding: 20px 0 24px; position: sticky; top: 0; z-index: 50; background: var(--bg); }
  .topbar-title { font-family: 'Space Grotesk', sans-serif; font-size: 22px; font-weight: 700; color: var(--text-primary); }
  .topbar-right { display: flex; align-items: center; gap: 12px; }
  .topbar-date { font-size: 13px; color: var(--text-secondary); font-weight: 500; background: var(--card-bg); border: 1px solid var(--border); padding: 8px 14px; border-radius: 10px; display: flex; align-items: center; gap: 6px; }
  .notif-btn { width: 40px; height: 40px; background: var(--card-bg); border: 1px solid var(--border); border-radius: 10px; display: grid; place-items: center; cursor: pointer; color: var(--text-secondary); transition: all .2s; }
  .notif-btn:hover { border-color: var(--primary); color: var(--primary); }
  .notif-dot { position: absolute; top: 8px; right: 8px; width: 7px; height: 7px; background: var(--danger); border-radius: 50%; border: 1.5px solid var(--card-bg); }

  /* HERO BANNER */
  .hero { background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #0891b2 100%); border-radius: 20px; padding: 32px 36px; display: flex; align-items: center; justify-content: space-between; position: relative; overflow: hidden; margin-bottom: 28px; box-shadow: 0 8px 32px rgba(37,99,235,0.28); }
  .hero-blob { position: absolute; width: 300px; height: 300px; border-radius: 50%; background: rgba(255,255,255,0.05); right: -60px; top: -80px; pointer-events: none; }
  .hero-blob2 { position: absolute; width: 180px; height: 180px; border-radius: 50%; background: rgba(255,255,255,0.04); right: 120px; bottom: -60px; pointer-events: none; }
  .hero-left { position: relative; z-index: 2; }
  .hero-greeting { font-size: 13px; color: rgba(255,255,255,0.7); font-weight: 500; margin-bottom: 6px; letter-spacing: .5px; }
  .hero-title { font-family: 'Space Grotesk', sans-serif; font-size: 28px; font-weight: 800; color: #fff; margin-bottom: 8px; }
  .hero-sub { font-size: 14px; color: rgba(255,255,255,0.75); max-width: 400px; line-height: 1.6; }
  .hero-right { position: relative; z-index: 2; text-align: right; }
  .hero-inst { font-size: 12px; color: rgba(255,255,255,0.65); margin-bottom: 4px; }
  .hero-nip { font-size: 13px; color: rgba(255,255,255,0.85); font-weight: 600; margin-bottom: 14px; }
  .hero-btn { display: inline-flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.18); backdrop-filter: blur(8px); border: 1.5px solid rgba(255,255,255,0.25); color: #fff; font-size: 13px; font-weight: 600; padding: 10px 20px; border-radius: 10px; cursor: pointer; text-decoration: none; transition: all .2s; }
  .hero-btn:hover { background: rgba(255,255,255,0.28); }

  /* STAT CARDS */
  .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 28px; }
  .stat-card { background: #fff; border: 1px solid var(--gray-200); border-radius: 14px; padding: 20px 22px; display: flex; flex-direction: column; gap: 10px; }
  .stat-icon { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
  .stat-icon i { font-size: 18px; }
  .stat-icon.orange { background: var(--orange-light); color: var(--orange); }
  .stat-icon.green { background: var(--green-light); color: var(--green); }
  .stat-icon.red { background: var(--red-light); color: var(--red); }
  .stat-icon.blue { background: var(--blue-light); color: var(--blue); }
  .stat-value { font-size: 30px; font-weight: 700; line-height: 1; }
  .stat-label { font-size: 12.5px; color: var(--gray-400); font-weight: 500; }

  /* BOTTOM GRID */
  .bottom-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
  .card { background: #fff; border: 1px solid var(--gray-200); border-radius: 14px; padding: 22px; }
  .card-title { font-size: 14px; font-weight: 700; margin-bottom: 18px; }

  /* CATEGORY BARS */
  .cat-row { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; }
  .cat-row:last-child { margin-bottom: 0; }
  .cat-icon { width: 26px; height: 26px; border-radius: 7px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
  .cat-icon i { font-size: 12px; }
  .cat-label { font-size: 13px; font-weight: 500; width: 90px; }
  .bar-wrap { flex: 1; background: var(--gray-100); border-radius: 999px; height: 24px; overflow: hidden; }
  .bar { height: 100%; border-radius: 999px; display: flex; align-items: center; justify-content: flex-end; padding-right: 10px; font-size: 11.5px; font-weight: 600; color: #fff; white-space: nowrap; min-width: 15%; transition: width 0.5s ease; }
  .bar.blue { background: #93c5fd; color: #1e40af; }
  .bar.purple { background: #c4b5fd; color: #6d28d9; }
  .bar.orange { background: #fcd34d; color: #92400e; }
  .bar.green { background: #6ee7b7; color: #065f46; }

  /* PENDING LIST */
  .pending-item { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid var(--gray-100); }
  .pending-item:last-child { border-bottom: none; }
  .pending-ico { width: 34px; height: 34px; border-radius: 9px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
  .pending-text { flex: 1; }
  .pending-text strong { font-size: 13px; font-weight: 600; display: block; color: var(--text-primary); }
  .pending-text span { font-size: 11.5px; color: var(--gray-400); }
  .badge-pending { background: var(--orange-light); color: var(--orange); font-size: 11px; font-weight: 600; padding: 3px 9px; border-radius: 20px; white-space: nowrap; }
  
  .empty-state { text-align: center; padding: 30px 10px; color: var(--gray-400); font-size: 13px; font-weight: 500; }

  @media (max-width: 1024px) {
    .stats { grid-template-columns: repeat(2, 1fr); }
    .bottom-grid { grid-template-columns: 1fr; }
  }
  @media (max-width: 768px) {
    .main { margin-left: 0; padding: 0 16px 32px; padding-top: 60px; }
    .stats { grid-template-columns: 1fr; }
    .hero { flex-direction: column; text-align: center; gap: 16px; padding: 24px 20px; }
    .hero-right { text-align: center; }
    .hero-title { font-size: 22px; }
    .topbar { flex-direction: column; align-items: flex-start; gap: 8px; }
  }
</style>
</head>
<body>

@include('partials.sidebar')

<main class="main">
  <div class="topbar">
    <div class="topbar-title">Dashboard Kasubag</div>
    <div class="topbar-right">
      <div class="topbar-date">
        <i class="fas fa-calendar-alt" style="color:var(--primary)"></i>
        {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
      </div>
    </div>
  </div>

  <div class="hero">
    <div class="hero-blob"></div>
    <div class="hero-blob2"></div>
    <div class="hero-left">
      <div class="hero-greeting">👋 Selamat datang kembali!</div>
      <div class="hero-title">Halo, {{ auth()->user()->name ?? 'Kasubag' }}!</div>
      <div class="hero-sub">Sebagai Kasubag, Anda memverifikasi persetujuan peminjaman barang dan peminjaman kendaraan, serta memantau transaksi keluar barang & aset di BPMP Provinsi Gorontalo.</div>
    </div>
    <div class="hero-right">
      <div class="hero-inst">BPMP Provinsi Gorontalo</div>
      <div class="hero-nip">NIP: {{ auth()->user()->nip ?? '-' }}</div>
      <div style="display:flex; gap:8px; justify-content:flex-end; flex-wrap:wrap;">
        <a href="{{ route('kasubag.persetujuan-peminjaman-barang') }}" class="hero-btn">
          <i class="fas fa-box"></i> Persetujuan Barang
        </a>
        <a href="{{ route('kasubag.persetujuan-peminjaman-kendaraan') }}" class="hero-btn">
          <i class="fas fa-car"></i> Persetujuan Kendaraan
        </a>
        <a href="{{ route('kasubag.monitoring-persediaan') }}" class="hero-btn">
          <i class="fas fa-boxes"></i> Monitoring Persediaan
        </a>
        <a href="{{ route('kasubag.monitoring-kendaraan') }}" class="hero-btn">
          <i class="fas fa-car-side"></i> Monitoring Kendaraan
        </a>
      </div>
    </div>
  </div>

  <div class="stats">
    <div class="stat-card">
      <div class="stat-icon blue"><i class="fas fa-box"></i></div>
      <div class="stat-value" style="color:var(--blue);">{{ $barangPending ?? 0 }}</div>
      <div class="stat-label">Peminjaman Barang (Pending)</div>
    </div>
    <div class="stat-card">
      <div class="stat-icon orange"><i class="fas fa-car"></i></div>
      <div class="stat-value" style="color:var(--orange);">{{ $kendaraanPending ?? 0 }}</div>
      <div class="stat-label">Peminjaman Kendaraan (Pending)</div>
    </div>
    <div class="stat-card">
      <div class="stat-icon purple" style="background:#f5f3ff;color:#8b5cf6;"><i class="fas fa-dolly"></i></div>
      <div class="stat-value" style="color:#8b5cf6;">{{ number_format($totalTransaksiKeluar ?? 0, 0, ',', '.') }}</div>
      <div class="stat-label">Total Transaksi Keluar</div>
    </div>
    <div class="stat-card">
      <div class="stat-icon red"><i class="fas fa-coins"></i></div>
      <div class="stat-value" style="font-size:20px; color:#D97706;">Rp {{ number_format($totalNilaiKeluar ?? 0, 0, ',', '.') }}</div>
      <div class="stat-label">Nilai Pengeluaran Barang</div>
    </div>
  </div>

  <div class="bottom-grid">
    <div class="card">
      <div class="card-title" style="display:flex; justify-content:space-between; align-items:center;">
        <span>Monitoring Terpadu</span>
        <a href="{{ route('kasubag.laporan-transaksi-keluar') }}" style="font-size:12px; color:var(--primary); text-decoration:none; font-weight:600;">
          Laporan Transaksi Keluar &rarr;
        </a>
      </div>
      
      <div style="margin-top:10px; display:flex; flex-direction:column; gap:12px;">
        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:14px; display:flex; justify-content:space-between; align-items:center;">
          <div style="display:flex; align-items:center; gap:10px;">
            <div style="width:34px; height:34px; border-radius:8px; background:#eff6ff; color:#3b82f6; display:flex; align-items:center; justify-content:center;">
              <i class="fas fa-boxes"></i>
            </div>
            <div>
              <strong style="font-size:13px; display:block;">Permintaan Persediaan</strong>
              <span style="font-size:11.5px; color:#64748b;">Pengajuan ATK, kertas & barang habis pakai</span>
            </div>
          </div>
          <div style="text-align:right;">
            <a href="{{ route('kasubag.monitoring-persediaan') }}" style="display:inline-block; font-size:12px; color:#3b82f6; text-decoration:none; font-weight:600;">Pantau &rarr;</a>
          </div>
        </div>

        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:14px; display:flex; justify-content:space-between; align-items:center;">
          <div style="display:flex; align-items:center; gap:10px;">
            <div style="width:34px; height:34px; border-radius:8px; background:#ecfdf5; color:#10b981; display:flex; align-items:center; justify-content:center;">
              <i class="fas fa-cubes"></i>
            </div>
            <div>
              <strong style="font-size:13px; display:block;">Barang / Aset Tetap</strong>
              <span style="font-size:11.5px; color:#64748b;">Master BMN, ketersediaan, kondisi & NUP</span>
            </div>
          </div>
          <div style="text-align:right;">
            <a href="{{ route('kasubag.monitoring-aset-tetap') }}" style="display:inline-block; font-size:12px; color:#10b981; text-decoration:none; font-weight:600;">Pantau &rarr;</a>
          </div>
        </div>

        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:14px; display:flex; justify-content:space-between; align-items:center;">
          <div style="display:flex; align-items:center; gap:10px;">
            <div style="width:34px; height:34px; border-radius:8px; background:#fffbeb; color:#f59e0b; display:flex; align-items:center; justify-content:center;">
              <i class="fas fa-car-side"></i>
            </div>
            <div>
              <strong style="font-size:13px; display:block;">Kendaraan Dinas</strong>
              <span style="font-size:11.5px; color:#64748b;">Unit sedang dipinjam, ketersediaan & jadwal</span>
            </div>
          </div>
          <div style="text-align:right;">
            <a href="{{ route('kasubag.monitoring-kendaraan') }}" style="display:inline-block; font-size:12px; color:#f59e0b; text-decoration:none; font-weight:600;">Pantau &rarr;</a>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-title" style="display:flex; justify-content:space-between; align-items:center;">
        <span>Antrean Persetujuan (Barang & Kendaraan)</span>
        <span style="font-size:11.5px; color:#64748b;">{{ $totalPending ?? 0 }} Pengajuan Menunggu</span>
      </div>
      
      @forelse($recentPending as $item)
          <div class="pending-item">
            @if($item['tipe'] === 'Barang')
              <div class="pending-ico" style="background:#eff6ff;color:#3b82f6"><i class="fas fa-box"></i></div>
            @else
              <div class="pending-ico" style="background:#f5f3ff;color:#8b5cf6"><i class="fas fa-car"></i></div>
            @endif

            <div class="pending-text">
              <strong>{{ $item['nama_item'] }}</strong>
              <span>{{ $item['nama_peminjam'] }} &middot; {{ \Carbon\Carbon::parse($item['tanggal'])->format('d M Y H:i') }}</span>
            </div>
            <a href="{{ $item['url'] ?? route('kasubag.persetujuan-peminjaman-kendaraan') }}" class="badge-pending" style="text-decoration:none;">Periksa</a>
          </div>
      @empty
          <div class="empty-state">
              <i class="fas fa-check-circle" style="font-size: 30px; color: var(--green); margin-bottom: 10px; display: block;"></i>
              Bagus! Tidak ada pengajuan barang atau kendaraan yang menunggu persetujuan Kasubag saat ini.
          </div>
      @endforelse

    </div>
  </div>
</main>

</body>
</html>