<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SIPANDU - Dashboard Pegawai</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root {
      --primary: #2563eb; --primary-light: #3b82f6; --primary-dark: #1d4ed8;
      --accent: #06b6d4; --accent2: #8b5cf6;
      --success: #10b981; --warning: #f59e0b; --danger: #ef4444;
      --bg: #f0f4ff; --sidebar-bg: #0f172a; --sidebar-text: #94a3b8;
      --card-bg: #ffffff; --text-primary: #0f172a; --text-secondary: #64748b;
      --border: #e2e8f0; --radius: 16px; --radius-sm: 10px;
      --shadow: 0 4px 24px rgba(37,99,235,0.08);
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text-primary); display: flex; min-height: 100vh; overflow-x: hidden; }

    /* ===== MAIN ===== */
    .main { margin-left: 256px; flex: 1; padding: 0 32px 32px; }

    /* TOP BAR */
    .topbar { display: flex; align-items: center; justify-content: space-between; padding: 20px 0 24px; position: sticky; top: 0; z-index: 50; background: var(--bg); }
    .topbar-title { font-family: 'Space Grotesk', sans-serif; font-size: 22px; font-weight: 700; color: var(--text-primary); }
    .topbar-right { display: flex; align-items: center; gap: 12px; }
    .topbar-date { font-size: 13px; color: var(--text-secondary); font-weight: 500; background: var(--card-bg); border: 1px solid var(--border); padding: 8px 14px; border-radius: 10px; display: flex; align-items: center; gap: 6px; }
    .notif-btn { width: 40px; height: 40px; background: var(--card-bg); border: 1px solid var(--border); border-radius: 10px; display: grid; place-items: center; cursor: pointer; position: relative; color: var(--text-secondary); transition: all .2s; }
    .notif-btn:hover { border-color: var(--primary); color: var(--primary); }
    .notif-dot { position: absolute; top: 8px; right: 8px; width: 7px; height: 7px; background: var(--danger); border-radius: 50%; border: 1.5px solid var(--card-bg); }

    /* HERO BANNER */
    .hero { background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #0891b2 100%); border-radius: 20px; padding: 32px 36px; display: flex; align-items: center; justify-content: space-between; position: relative; overflow: hidden; margin-bottom: 28px; box-shadow: 0 8px 32px rgba(37,99,235,0.28); }
    .hero-blob { position: absolute; width: 300px; height: 300px; border-radius: 50%; background: rgba(255,255,255,0.05); right: -60px; top: -80px; pointer-events: none; }
    .hero-blob2 { position: absolute; width: 180px; height: 180px; border-radius: 50%; background: rgba(255,255,255,0.04); right: 120px; bottom: -60px; pointer-events: none; }
    .hero-left { position: relative; z-index: 2; }
    .hero-greeting { font-size: 13px; color: rgba(255,255,255,0.7); font-weight: 500; margin-bottom: 6px; letter-spacing: .5px; }
    .hero-title { font-family: 'Space Grotesk', sans-serif; font-size: 28px; font-weight: 800; color: #fff; margin-bottom: 8px; }
    .hero-sub { font-size: 14px; color: rgba(255,255,255,0.75); max-width: 450px; line-height: 1.6; }
    .hero-right { position: relative; z-index: 2; text-align: right; }
    .hero-inst { font-size: 12px; color: rgba(255,255,255,0.65); margin-bottom: 4px; }
    .hero-nip { font-size: 13px; color: rgba(255,255,255,0.85); font-weight: 600; margin-bottom: 14px; }
    .hero-btn { display: inline-flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.18); backdrop-filter: blur(8px); border: 1.5px solid rgba(255,255,255,0.25); color: #fff; font-size: 13px; font-weight: 600; padding: 10px 20px; border-radius: 10px; cursor: pointer; text-decoration: none; transition: all .2s; }
    .hero-btn:hover { background: rgba(255,255,255,0.28); }

    .content { padding: 0; }
    /* ===== STAT CARDS ===== */
    .stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 28px; }
    .stat-card { background: var(--card-bg); border-radius: var(--radius); padding: 20px; border: 1px solid var(--border); box-shadow: var(--shadow); display: flex; align-items: center; gap: 16px; position: relative; overflow: hidden; }
    .stat-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
    .stat-icon.blue { background: rgba(37,99,235,0.1); color: var(--primary); }
    .stat-icon.amber { background: rgba(245,158,11,0.1); color: var(--warning); }
    .stat-icon.purple { background: rgba(139,92,246,0.1); color: var(--accent2); }
    .stat-icon.green { background: rgba(16,185,129,0.1); color: var(--success); }
    .stat-icon.teal { color: #14b8a6; background-color: rgba(20, 184, 166, 0.1); }
    .stat-icon.rose { color: #f43f5e; background-color: rgba(244, 63, 94, 0.1); }
    .stat-num.teal { color: #14b8a6; }
    .stat-num.rose { color: #f43f5e; }
    .stat-num { font-size: 24px; font-weight: 700; line-height: 1; margin-top: 4px;}
    .stat-num.blue  { color: var(--primary); }
    .stat-num.amber { color: var(--warning); }
    .stat-num.purple { color: var(--accent2); }
    .stat-num.green { color: var(--success); }
    .stat-label { font-size: 12.5px; color: var(--text-secondary); font-weight: 600; line-height: 1.2; }

    /* ===== QUICK ACTIONS ===== */
    .quick-actions { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 28px; }
    .qa-btn { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 16px 20px; border-radius: 12px; color: white; text-decoration: none; font-weight: 600; font-size: 13px; transition: all 0.2s; cursor: pointer; text-align: center; }
    .qa-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    .qa-blue { background: linear-gradient(135deg, #2563eb, #3b82f6); }
    .qa-amber { background: linear-gradient(135deg, #f59e0b, #fbbf24); }
    .qa-purple { background: linear-gradient(135deg, #7c3aed, #a78bfa); }
    .qa-green { background: linear-gradient(135deg, #10b981, #34d399); }
    /* --- WARNA UNTUK QUICK ACTIONS (TOMBOL AKSES CEPAT) --- */
    .qa-btn.qa-teal {background-color: #14b8a6; color: #ffffff !important;border: none;}
    .qa-btn.qa-teal:hover {background-color: #0d9488;color: #ffffff !important;}
    .qa-btn.qa-rose { background-color: #f43f5e; /* Warna Merah Muda/Rose */ color: #ffffff !important; border: none;}
    .qa-btn.qa-rose:hover {background-color: #e11d48;color: #ffffff !important;}

    /* ===== BOTTOM ROW ===== */
    .bottom-row { display: grid; grid-template-columns: 1.5fr 1fr; gap: 16px; }
    .panel { background: white; border-radius: 14px; padding: 20px; border: 1px solid var(--border); box-shadow: var(--shadow); }
    .panel-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid #f0f0f0; }
    .panel-title { font-size: 15px; font-weight: 700; color: #222; display: flex; align-items: center; gap: 8px; }
    .badge-count { background: #f0f2f5; border-radius: 10px; padding: 4px 12px; font-size: 11px; font-weight: 600; color: #555; }

    /* ===== REQUEST ITEM ===== */
    .request-item { border: 1px solid var(--border); border-radius: 10px; padding: 14px; margin-bottom: 12px; transition: all 0.2s; }
    .request-item:hover { border-color: var(--primary); box-shadow: 0 2px 10px rgba(37,99,235,0.05); }
    .request-item:last-child { margin-bottom: 0; }
    .req-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px; }
    .req-name { font-size: 14px; font-weight: 700; color: var(--text-primary); }
    .status-badge { font-size: 10.5px; padding: 4px 12px; border-radius: 20px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; text-transform: uppercase; letter-spacing: 0.5px;}
    .status-badge.diproses { background: #fef3c7; color: #d97706; }
    .status-badge.disetujui { background: #dcfce7; color: #15803d; }
    .status-badge.ditolak  { background: #fee2e2; color: #b91c1c; }
    .req-type-tag { font-size: 11px; font-weight: 600; padding: 4px 8px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px; margin-bottom: 8px; }
    .tag-blue { background: #eff6ff; color: #2563eb; }
    .tag-amber { background: #fffbeb; color: #f59e0b; }
    .tag-purple { background: #f5f3ff; color: #8b5cf6; }
    .tag-green { background: #ecfdf5; color: #10b981; }
    
    .req-date { font-size: 12px; font-weight: 500; color: var(--text-secondary); display: flex; align-items: center; gap: 6px; }
    
    .empty-state { text-align: center; padding: 30px 10px; color: var(--text-secondary); font-size: 13px; font-weight: 500; }

    @media (max-width: 1024px) {
      .stats-row { grid-template-columns: repeat(2, 1fr); }
      .quick-actions { grid-template-columns: repeat(2, 1fr); }
      .bottom-row { grid-template-columns: 1fr; }
    }
    @media (max-width: 768px) {
      .main { margin-left: 0; padding: 0 16px 32px; padding-top: 60px; }
      .stats-row { grid-template-columns: 1fr; }
      .quick-actions { grid-template-columns: 1fr 1fr; }
      .hero { flex-direction: column; text-align: center; gap: 16px; padding: 24px 20px; }
      .hero-right { text-align: center; }
      .hero-title { font-size: 22px; }
      .topbar { flex-direction: column; align-items: flex-start; gap: 8px; }
      .topbar-right { width: 100%; justify-content: flex-end; }
      .table-container { overflow-x: auto; }
      table { min-width: 500px; }
    }
    @media (max-width: 480px) {
      .quick-actions { grid-template-columns: 1fr; }
      .main { padding: 0 12px 24px; padding-top: 60px; }
    }
  </style>
</head>
<body>

@include('partials.sidebar')

<div class="main">
  <!-- TOPBAR -->
  <div class="topbar">
    <div class="topbar-title">Dashboard Pegawai</div>
    <div class="topbar-right">
      <div class="topbar-date">
        <i class="fas fa-calendar" style="color:var(--primary)"></i>
        {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
      </div>
      <button class="notif-btn">
        {{-- <i class="fas fa-bell"></i>
        <span class="notif-dot"></span> --}}
      </button>
    </div>
  </div>

  <div class="content">
    <!-- HERO BANNER -->
    <div class="hero">
      <div class="hero-blob"></div>
      <div class="hero-blob2"></div>
      <div class="hero-left">
        <div class="hero-greeting">Selamat Datang 👋</div>
        <div class="hero-title">{{ auth()->user()->name ?? 'Pegawai' }}</div>
        <div class="hero-sub">Kelola dan pantau seluruh riwayat pemintaan aset, kendaraan, dan fasilitas BPMP Provinsi Gorontalo melalui panel ini.</div>
      </div>
      <div class="hero-right">
        <div class="hero-inst">BPMP Provinsi Gorontalo</div>
        <div class="hero-nip">NIP. {{ auth()->user()->nip ?? '-' }}</div>
        <a href="{{ route('pegawai.pengaturan-akun') }}" class="hero-btn">
          <i class="fas fa-cog"></i> Pengaturan Akun
        </a>
      </div>
    </div>

    <!-- STAT CARDS -->
    <div class="stats-row">
    <div class="stat-card">
      <div class="stat-icon blue"><i class="fas fa-box"></i></div>
      <div>
        <div class="stat-label">Peminjaman Barang</div>
        <div class="stat-num blue">{{ $statBarang ?? 0 }}</div>
      </div>
    </div>
    
    <div class="stat-card">
      <div class="stat-icon amber"><i class="fas fa-car"></i></div>
      <div>
        <div class="stat-label">Peminjaman Kendaraan</div>
        <div class="stat-num amber">{{ $statKendaraan ?? 0 }}</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon green"><i class="fas fa-cubes"></i></div>
      <div>
        <div class="stat-label">Permintaan Persediaan</div>
        <div class="stat-num green">{{ $statPersediaan ?? 0 }}</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon teal"><i class="fas fa-undo"></i></div>
      <div>
        <div class="stat-label">Pengembalian Barang</div>
        <div class="stat-num teal">{{ $statKembaliBarang ?? 0 }}</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon rose"><i class="fas fa-sync-alt"></i></div>
      <div>
        <div class="stat-label">Pengembalian Kendaraan</div>
        <div class="stat-num rose">{{ $statKembaliKendaraan ?? 0 }}</div>
      </div>
    </div>
  </div>

    <!-- QUICK ACTIONS -->
    <div style="margin-bottom: 28px;">
      <h2 style="font-family: 'Space Grotesk', sans-serif; font-size: 18px; font-weight: 700; color: var(--text-primary); margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
        <i class="fas fa-bolt" style="color: var(--primary);"></i> Akses Cepat Peminjaman
      </h2>
      <div class="quick-actions" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
        <a href="{{ route('pegawai.peminjaman-barang') }}" class="qa-btn qa-blue">
          <i class="fas fa-box"></i> Peminjaman Barang
        </a>
        <a href="{{ route('pegawai.peminjaman-kendaraan') }}" class="qa-btn qa-amber">
          <i class="fas fa-car"></i> Peminjaman Kendaraan
        </a>
        <a href="{{ route('pegawai.permintaan-persediaan') }}" class="qa-btn qa-green">
          <i class="fas fa-cubes"></i> Permintaan Persediaan
        </a>
        <a href="{{ route('pegawai.pengembalian-barang') }}" class="qa-btn qa-teal">
          <i class="fas fa-undo"></i> Pengembalian Barang
        </a>
        <a href="{{ route('pegawai.pengembalian-kendaraan') }}" class="qa-btn qa-rose">
          <i class="fas fa-sync-alt"></i> Pengembalian Kendaraan
        </a>
      </div>
    </div>

    <!-- BOTTOM ROW -->
    <div class="bottom-row">

      <!-- RIWAYAT PERMINTAAN TERBARU -->
      <div class="panel">
        <div class="panel-header">
          <span class="panel-title">
            <i class="fas fa-history" style="color: var(--primary);"></i>
            Riwayat Pengajuan Terbaru Anda
          </span>
          <span class="badge-count">{{ count($riwayatTerbaru ?? []) }} data</span>
        </div>

        @forelse($riwayatTerbaru as $item)
            <div class="request-item">
              <div class="req-top">
                <div class="req-name">{{ $item['nama_item'] }}</div>
                @php
                    $statusClass = 'diproses';
                    $iconStatus = 'fa-clock';
                    if($item['status'] == 'disetujui' || $item['status'] == 'diterima') { $statusClass = 'disetujui'; $iconStatus = 'fa-check-circle'; }
                    elseif($item['status'] == 'ditolak') { $statusClass = 'ditolak'; $iconStatus = 'fa-times-circle'; }
                @endphp
                <span class="status-badge {{ $statusClass }}">
                  <i class="fas {{ $iconStatus }}"></i> {{ str_replace('_', ' ', $item['status']) }}
                </span>
              </div>
              
              @if($item['tipe'] == 'Barang')
                  <div class="req-type-tag tag-blue"><i class="fas fa-box"></i> Barang</div>
              @elseif($item['tipe'] == 'Kendaraan')
                  <div class="req-type-tag tag-amber"><i class="fas fa-car"></i> Kendaraan</div>
              @elseif($item['tipe'] == 'Gedung')
                  <div class="req-type-tag tag-purple"><i class="fas fa-building"></i> Ruangan</div>
              @else
                  <div class="req-type-tag tag-green"><i class="fas fa-cubes"></i> Persediaan</div>
              @endif

              <div class="req-date">
                <i class="far fa-calendar-alt"></i> Diajukan pada: {{ \Carbon\Carbon::parse($item['tanggal'])->locale('id')->translatedFormat('d F Y (H:i)') }}
              </div>
            </div>
        @empty
            <div class="empty-state">
              <i class="fas fa-folder-open" style="font-size: 32px; color: var(--border); margin-bottom: 12px; display: block;"></i>
              Anda belum mengajukan peminjaman atau permintaan apapun.
            </div>
        @endforelse

      </div>

      <!-- STATUS RINGKAS -->
      <div class="panel">
        <div class="panel-header">
          <span class="panel-title">
            <i class="fas fa-chart-pie" style="color: var(--primary);"></i>
            Rekapitulasi Pengajuan Anda
          </span>
        </div>
        
        <div style="padding: 10px 0;">
          <!-- Status Barang -->
          <div style="padding: 14px 0; border-bottom: 1px solid #f0f0f0; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 12px;">
              <i class="fas fa-box" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; background: #eff6ff; color: var(--primary); border-radius: 8px; font-size: 14px;"></i>
              <div>
                <div style="font-size: 13px; font-weight: 700; color: var(--text-primary);">Barang Dinas</div>
                <div style="font-size: 11.5px; color: var(--text-secondary); margin-top:2px;">{{ $barangPending ?? 0 }} Diproses, {{ $barangSetuju ?? 0 }} Disetujui</div>
              </div>
            </div>
            <span style="font-size: 16px; font-weight: 800; color: var(--primary);">{{ $statBarang ?? 0 }}</span>
          </div>

          <!-- Status Kendaraan -->
          <div style="padding: 14px 0; border-bottom: 1px solid #f0f0f0; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 12px;">
              <i class="fas fa-car" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; background: #fffbeb; color: var(--warning); border-radius: 8px; font-size: 14px;"></i>
              <div>
                <div style="font-size: 13px; font-weight: 700; color: var(--text-primary);">Kendaraan Dinas</div>
                <div style="font-size: 11.5px; color: var(--text-secondary); margin-top:2px;">{{ $kendaraanPending ?? 0 }} Diproses, {{ $kendaraanSetuju ?? 0 }} Disetujui</div>
              </div>
            </div>
            <span style="font-size: 16px; font-weight: 800; color: var(--warning);">{{ $statKendaraan ?? 0 }}</span>
          </div>
          
          <!-- Status Gedung -->
          <div style="padding: 14px 0; border-bottom: 1px solid #f0f0f0; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 12px;">
              <i class="fas fa-building" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; background: #f5f3ff; color: var(--accent2); border-radius: 8px; font-size: 14px;"></i>
              <div>
                <div style="font-size: 13px; font-weight: 700; color: var(--text-primary);">Gedung / Ruangan</div>
                <div style="font-size: 11.5px; color: var(--text-secondary); margin-top:2px;">{{ $gedungPending ?? 0 }} Diproses, {{ $gedungSetuju ?? 0 }} Disetujui</div>
              </div>
            </div>
            <span style="font-size: 16px; font-weight: 800; color: var(--accent2);">{{ $statGedung ?? 0 }}</span>
          </div>

          <!-- Status Persediaan -->
          <div style="padding: 14px 0; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 12px;">
              <i class="fas fa-cubes" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; background: #ecfdf5; color: var(--success); border-radius: 8px; font-size: 14px;"></i>
              <div>
                <div style="font-size: 13px; font-weight: 700; color: var(--text-primary);">Persediaan / ATK</div>
                <div style="font-size: 11.5px; color: var(--text-secondary); margin-top:2px;">{{ $persediaanPending ?? 0 }} Diproses, {{ $persediaanSetuju ?? 0 }} Disetujui</div>
              </div>
            </div>
            <span style="font-size: 16px; font-weight: 800; color: var(--success);">{{ $statPersediaan ?? 0 }}</span>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

</body>
</html>