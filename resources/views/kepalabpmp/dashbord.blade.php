<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPANDU - Dashboard Kepala BPMP</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #2563eb;
            --accent: #8b5cf6;
            --accent2: #06b6d4;
            --success: #22c55e;
            --warning: #f59e0b;
            --danger: #ef4444;
            --bg: #eef2ff;
            --card-bg: #ffffff;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --border: #e2e8f0;
            --radius: 20px;
            --shadow: 0 16px 50px rgba(15, 23, 42, 0.08);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text-primary); display: flex; min-height: 100vh; }
        .main { margin-left: 260px; flex: 1; padding: 28px 32px 40px; }
        .topbar { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding-bottom: 18px; margin-bottom: 18px; }
        .breadcrumb { display: inline-flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text-secondary); }
        .breadcrumb span { font-weight: 700; color: var(--primary); }
        .topbar-title { font-family: 'Space Grotesk', sans-serif; font-size: 24px; font-weight: 800; }
        .topbar-right { display: flex; align-items: center; gap: 14px; }
        .topbar-pill { inline-flex; align-items: center; gap: 8px; background: #fff; border: 1px solid var(--border); border-radius: 999px; padding: 10px 14px; font-size: 13px; color: var(--text-secondary); }
        .topbar-pill i { color: var(--primary); }
        .notif-btn { width: 44px; height: 44px; background: #fff; border: 1px solid var(--border); border-radius: 14px; display: grid; place-items: center; position: relative; cursor: pointer; color: var(--text-secondary); transition: all .2s; }
        .notif-btn:hover { border-color: var(--primary); color: var(--primary); }
        .notif-dot { position: absolute; top: 10px; right: 10px; width: 8px; height: 8px; border-radius: 999px; background: var(--danger); border: 2px solid #fff; }
        
        .hero { background: linear-gradient(135deg, #4338ca 0%, #2563eb 100%); border-radius: 32px; color: #fff; padding: 34px; overflow: hidden; position: relative; margin-bottom: 30px; box-shadow: 0 16px 50px rgba(37, 99, 235, 0.16); }
        .hero::before { content: ''; position: absolute; width: 260px; height: 260px; border-radius: 50%; background: rgba(255, 255, 255, 0.12); top: -80px; right: -100px; }
        .hero::after { content: ''; position: absolute; width: 180px; height: 180px; border-radius: 50%; background: rgba(255, 255, 255, 0.08); bottom: -60px; left: -40px; }
        .hero-grid { display: grid; grid-template-columns: 1.6fr 1fr; gap: 24px; position: relative; }
        .hero-title { font-family: 'Space Grotesk', sans-serif; font-size: 34px; font-weight: 800; line-height: 1.05; margin-bottom: 14px; }
        .hero-subtitle { font-size: 15px; color: rgba(255, 255, 255, 0.9); line-height: 1.75; margin-bottom: 22px; }
        .hero-badges { display: flex; flex-wrap: wrap; gap: 12px; }
        .hero-badge { display: inline-flex; align-items: center; gap: 10px; background: rgba(255, 255, 255, 0.14); border: 1px solid rgba(255, 255, 255, 0.18); padding: 12px 16px; border-radius: 16px; font-size: 13px; font-weight: 600; }
        .hero-card { background: rgba(255, 255, 255, 0.16); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 24px; padding: 28px; box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.06); }
        .hero-card-title { font-size: 12px; text-transform: uppercase; letter-spacing: .9px; color: rgba(255, 255, 255, 0.82); margin-bottom: 16px; }
        .hero-card-number { font-size: 44px; font-weight: 800; margin-bottom: 10px; }
        .hero-card-note { font-size: 14px; color: rgba(255, 255, 255, 0.88); line-height: 1.7; }
        
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 22px; margin-bottom: 32px; }
        .stat-card { background: #fff; border-radius: 24px; border: 1px solid var(--border); box-shadow: 0 10px 32px rgba(15, 23, 42, 0.08); padding: 24px; display: flex; align-items: center; gap: 18px; transition: transform .2s, box-shadow .2s; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 18px 45px rgba(15, 23, 42, 0.12); }
        .stat-icon { width: 52px; height: 52px; border-radius: 16px; display: grid; place-items: center; color: #fff; font-size: 20px; flex-shrink: 0; }
        .stat-value { font-family: 'Space Grotesk', sans-serif; font-size: 26px; font-weight: 800; color: var(--text-primary); }
        .stat-label { font-size: 11px; color: var(--text-secondary); text-transform: uppercase; letter-spacing: .8px; margin-top: 6px; }
        
        .dashboard-grid { display: grid; gap: 24px; margin-bottom: 32px; }
        .dashboard-grid.columns-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .chart-card, .activity-card, .actions-card { background: #fff; border-radius: 24px; border: 1px solid var(--border); box-shadow: 0 10px 32px rgba(15, 23, 42, 0.08); padding: 24px; }
        .chart-header, .activity-header { margin-bottom: 22px; }
        .chart-title, .activity-title, .actions-title { font-family: 'Space Grotesk', sans-serif; font-size: 18px; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 10px; }
        .chart-title i, .activity-title i, .actions-title i { color: var(--primary); }
        
        .activity-list { padding: 0; }
        .activity-item { display: flex; gap: 16px; align-items: flex-start; padding: 14px 0; border-bottom: 1px solid var(--border); }
        .activity-item:last-child { border-bottom: none; }
        .activity-icon { width: 38px; height: 38px; border-radius: 12px; display: grid; place-items: center; color: #fff; font-size: 14px; flex-shrink: 0; }
        .activity-text { font-size: 14px; color: var(--text-primary); line-height: 1.6; }
        .activity-time { font-size: 12px; color: var(--text-secondary); margin-top: 4px; }
        
        .actions-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 14px; }
        .action-btn { display: flex; flex-direction: column; align-items: center; gap: 10px; padding: 18px 14px; border-radius: 20px; background: #f8fbff; border: 1px solid var(--border); color: var(--text-primary); text-decoration: none; transition: all .2s; }
        .action-btn:hover { transform: translateY(-2px); box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08); }
        .action-icon { width: 46px; height: 46px; border-radius: 16px; display: grid; place-items: center; color: #fff; font-size: 18px; }
        .action-label { font-size: 13px; font-weight: 700; text-align: center; }
        
        @media (max-width: 1120px) {
            .hero-grid, .dashboard-grid.columns-2 { grid-template-columns: 1fr; }
            .main { margin-left: 0; padding: 20px; padding-top: 70px; }
        }
        @media (max-width: 768px) {
            .stats-grid { grid-template-columns: 1fr; }
            .hero-title { font-size: 24px; }
            .topbar { flex-direction: column; align-items: flex-start; gap: 8px; }
            .actions-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>
    @include('partials.sidebar')

    <div class="main">
        <div class="topbar">
            <div>
                <div class="breadcrumb"><i class="fas fa-home"></i><span>Dashboard</span></div>
                <div class="topbar-title">Dashboard Kepala BPMP</div>
            </div>
            <div class="topbar-right">
                <div class="topbar-pill"><i class="fas fa-calendar-day"></i>{{ date('d M Y') }}</div>
                {{-- <div class="notif-btn">
                    <i class="fas fa-bell"></i>
                    @if($menungguApproval > 0)<span class="notif-dot"></span>@endif
                </div> --}}
            </div>
        </div>

        <section class="hero">
            <div class="hero-grid">
                <div>
                    <div class="hero-title">Selamat datang, {{ auth()->user()->name ?? 'Kepala BPMP' }}</div>
                    <div class="hero-subtitle">Pantau seluruh siklus logistik, mutasi internal, peminjaman aktif, serta kerusakan sarana prasarana dalam satu panel kendali strategis.</div>
                    <div class="hero-badges">
                        <span class="hero-badge"><i class="fas fa-shield-alt"></i> Sistem Amankan</span>
                        <span class="hero-badge"><i class="fas fa-chart-line"></i> Validasi Real-time</span>
                    </div>
                </div>
                <div class="hero-card">
                    <div class="hero-card-title">Menunggu Persetujuan</div>
                    <div class="hero-card-number">{{ $menungguApproval }}</div>
                    <div class="hero-card-note">Dokumen permintaan & berkas peminjaman aktif yang memerlukan tindakan persetujuan Anda.</div>
                </div>
            </div>
        </section>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #2563eb, #3b82f6);"><i class="fas fa-warehouse"></i></div>
                <div class="stat-details">
                    <div class="stat-value">{{ number_format($totalAsetTetap) }}</div>
                    <div class="stat-label">Total Aset Tetap</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6, #a855f7);"><i class="fas fa-boxes"></i></div>
                <div class="stat-details">
                    <div class="stat-value">{{ number_format($totalPersediaan) }}</div>
                    <div class="stat-label">Total Persediaan</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #0ea5e9, #22d3ee);"><i class="fas fa-building"></i></div>
                <div class="stat-details">
                    <div class="stat-value">{{ number_format($totalGedung) }}</div>
                    <div class="stat-label">Total Gedung</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f97316, #fb923c);"><i class="fas fa-exchange-alt"></i></div>
                <div class="stat-details">
                    <div class="stat-value">{{ number_format($totalMutasi) }}</div>
                    <div class="stat-label">Total Mutasi</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #f43f5e);"><i class="fas fa-tools"></i></div>
                <div class="stat-details">
                    <div class="stat-value">{{ number_format($totalKerusakan) }}</div>
                    <div class="stat-label">Total Kerusakan</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #22c55e, #16a34a);"><i class="fas fa-handshake"></i></div>
                <div class="stat-details">
                    <div class="stat-value">{{ number_format($totalPeminjaman) }}</div>
                    <div class="stat-label">Total Peminjaman</div>
                </div>
            </div>

            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #64748b, #94a3b8);"><i class="fas fa-users"></i></div>
                <div class="stat-details">
                    <div class="stat-value">{{ number_format($totalPengguna) }}</div>
                    <div class="stat-label">Total Pengguna</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #14b8a6, #06b6d4);"><i class="fas fa-file-alt"></i></div>
                <div class="stat-details">
                    <div class="stat-value">{{ number_format($permintaanBulanIni) }}</div>
                    <div class="stat-label">Permintaan Bulan Ini</div>
                </div>
            </div>
        </div>

        <div class="dashboard-grid" style="margin-bottom: 24px;">
            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-title"><i class="fas fa-chart-pie"></i> Distribusi Logistik & Infrastruktur</div>
                </div>
                <canvas id="assetsChart" width="400" height="220"></canvas>
            </div>
        </div>

        <div class="dashboard-grid columns-2">
            <div class="activity-card">
                <div class="activity-header">
                    <div class="activity-title"><i class="fas fa-history"></i> Log Aktivitas Terbaru</div>
                </div>
                <div class="activity-list">
                    @forelse($recentActivities as $activity)
                    <div class="activity-item">
                        <div class="activity-icon" style="background: {{ $activity['color'] }};"><i class="{{ $activity['icon'] }}"></i></div>
                        <div class="activity-content">
                            <div class="activity-text">{{ $activity['text'] }}</div>
                            <div class="activity-time">{{ $activity['time'] ? \Carbon\Carbon::parse($activity['time'])->diffForHumans() : '-' }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="activity-item">
                        <div class="activity-content">
                            <div class="activity-text" style="color:var(--text-secondary)">Belum ada aktivitas terbaru yang terekam.</div>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
            <div class="actions-card">
                <div class="actions-title"><i class="fas fa-bolt"></i> Navigasi Aksi Cepat</div>
                <div class="actions-grid" style="margin-top:16px">
                    <a href="{{ route('kepalabpmp.laporan') }}" class="action-btn">
                        <div class="action-icon" style="background: linear-gradient(135deg,#4338ca,#8b5cf6);"><i class="fas fa-file-alt"></i></div>
                        <div class="action-label">Lihat Laporan</div>
                    </a>
                    <a href="{{ route('kepalabpmp.laporan.download-lengkap') }}" class="action-btn">
                        <div class="action-icon" style="background: linear-gradient(135deg,#22c55e,#16a34a);"><i class="fas fa-download"></i></div>
                        <div class="action-label">Download Lengkap</div>
                    </a>
                    <a href="{{ route('kepalabpmp.laporan.download-persediaan') }}" class="action-btn">
                        <div class="action-icon" style="background: linear-gradient(135deg,#f97316,#ea580c);"><i class="fas fa-boxes"></i></div>
                        <div class="action-label">Lap. Persediaan</div>
                    </a>
                    <a href="{{ route('kepalabpmp.pengaturan-akun') }}" class="action-btn">
                        <div class="action-icon" style="background: linear-gradient(135deg,#a855f7,#7c3aed);"><i class="fas fa-cog"></i></div>
                        <div class="action-label">Pengaturan Akun</div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        const assetsCtx = document.getElementById('assetsChart').getContext('2d');
        new Chart(assetsCtx, {
            type: 'doughnut',
            data: {
                labels: @json(array_keys($distribusiAset)),
                datasets: [{
                    data: @json(array_values($distribusiAset)),
                    backgroundColor: ['#8b5cf6', '#2563eb', '#0ea5e9', '#f97316']
                }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });
    </script>
</body>
</html>