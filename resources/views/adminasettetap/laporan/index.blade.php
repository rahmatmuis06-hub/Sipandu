<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan & Statistik | Admin Aset Tetap - BPMP Gorontalo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --blue-50: #EFF6FF;
            --blue-100: #DBEAFE;
            --blue-500: #3B82F6;
            --blue-600: #2563EB;
            --blue-700: #1D4ED8;
            --blue-900: #1E3A8A;
        }

        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        body {
            background: #F0F4F8;
            min-height: 100vh;
        }

        /* ---- SIDEBAR ---- */
        .sidebar-gradient {
            background: linear-gradient(160deg, #0F172A 0%, #1E3A8A 60%, #1D4ED8 100%);
        }

        /* ---- HEADER ---- */
        .header-bar {
            background: #fff;
            border-bottom: 1px solid #E2E8F0;
        }

        /* ---- SECTION TITLES ---- */
        .section-title {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: #94A3B8;
        }

        /* ---- DOWNLOAD AREA ---- */
        .dl-section {
            background: linear-gradient(135deg, #0F172A 0%, #1E3A8A 50%, #2563EB 100%);
            border-radius: 20px;
            position: relative;
            overflow: hidden;
        }
        .dl-section::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 320px; height: 320px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
            pointer-events: none;
        }
        .dl-section::after {
            content: '';
            position: absolute;
            bottom: -60px; left: 20px;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: rgba(255,255,255,0.03);
            pointer-events: none;
        }

        .dl-card {
            background: rgba(255,255,255,0.95);
            border-radius: 14px;
            padding: 20px 16px 18px;
            text-align: center;
            transition: transform .22s ease, box-shadow .22s ease, background .2s;
            border: 1.5px solid transparent;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }
        .dl-card::after {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 60%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left .5s ease;
        }
        .dl-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(0,0,0,0.15); background: #fff; }
        .dl-card:hover::after { left: 140%; }

        .dl-card.green:hover  { border-color: #6EE7B7; }
        .dl-card.red:hover    { border-color: #FCA5A5; }
        .dl-card.orange:hover { border-color: #FCD34D; }
        .dl-card.emerald:hover{ border-color: #34D399; }
        .dl-card.purple:hover { border-color: #C4B5FD; }
        .dl-card.blue:hover   { border-color: #93C5FD; }
        .dl-card.dark:hover   { border-color: #94A3B8; }

        .dl-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            margin: 0 auto 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
            transition: transform .2s;
        }
        .dl-card:hover .dl-icon { transform: scale(1.1); }

        .dl-badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 10px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .05em;
        }

        /* ---- KPI CARDS ---- */
        .kpi-card {
            background: #fff;
            border-radius: 16px;
            padding: 22px 22px;
            border: 1px solid #E8EEF4;
            transition: transform .2s, box-shadow .2s;
            position: relative;
            overflow: hidden;
        }
        .kpi-card::before {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 80px; height: 80px;
            border-radius: 0 0 0 80px;
            opacity: .06;
        }
        .kpi-card.blue::before   { background: #3B82F6; }
        .kpi-card.green::before  { background: #10B981; }
        .kpi-card.red::before    { background: #EF4444; }
        .kpi-card.purple::before { background: #8B5CF6; }
        .kpi-card.orange::before { background: #F97316; }
        .kpi-card.emerald::before{ background: #059669; }
        .kpi-card:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(0,0,0,0.08); }

        .kpi-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }
        .kpi-value {
            font-size: 28px;
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -.02em;
        }
        .kpi-label {
            font-size: 12px;
            font-weight: 600;
            color: #64748B;
            margin-bottom: 4px;
        }

        /* ══════════════════════════════════════
           CHART CARDS — VERSI BARU (COMPACT)
        ══════════════════════════════════════ */
        .chart-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
        @media (max-width: 900px) {
            .charts-grid { grid-template-columns: 1fr; }
        }

        .chart-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #E8EEF4;
            overflow: hidden;
            transition: box-shadow .2s, transform .2s;
        }
        .chart-card:hover {
            box-shadow: 0 6px 24px rgba(0,0,0,0.07);
            transform: translateY(-1px);
        }

        /* Compact header strip */
        .chart-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px 10px;
            border-bottom: 1px solid #F1F5F9;
            background: #FAFBFC;
        }
        .chart-header-left {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .chart-icon-sm {
            width: 28px; height: 28px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px;
            flex-shrink: 0;
        }
        .chart-title-text {
            font-size: 13px;
            font-weight: 700;
            color: #1E293B;
            line-height: 1.2;
        }
        .chart-subtitle-text {
            font-size: 10px;
            color: #94A3B8;
            font-weight: 500;
        }

        /* Stat pill in header */
        .chart-stat-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 9px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
        }

        /* Chart body - fixed compact height */
        .chart-body {
            padding: 14px 16px 12px;
            position: relative;
        }
        .chart-body canvas {
            max-height: 140px !important;
        }

        /* Doughnut layout: chart + legend side by side */
        .donut-layout {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 14px 16px;
        }
        .donut-wrap {
            width: 110px;
            height: 110px;
            flex-shrink: 0;
            position: relative;
        }
        .donut-wrap canvas {
            max-height: 110px !important;
        }
        .donut-center-label {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            pointer-events: none;
        }
        .donut-center-label span:first-child {
            font-size: 18px;
            font-weight: 800;
            color: #1E293B;
            line-height: 1;
        }
        .donut-center-label span:last-child {
            font-size: 9px;
            color: #94A3B8;
            font-weight: 600;
            letter-spacing: .05em;
            text-transform: uppercase;
        }
        .donut-legend {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .donut-legend-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
        }
        .donut-legend-left {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .donut-dot {
            width: 8px; height: 8px;
            border-radius: 3px;
            flex-shrink: 0;
        }
        .donut-legend-label {
            font-size: 11px;
            color: #64748B;
            font-weight: 500;
        }
        .donut-legend-val {
            font-size: 11px;
            font-weight: 700;
            color: #1E293B;
        }

        /* ══════════════════════════════════════ */

        /* ---- ACTIVITY FEED ---- */
        .activity-card { background: #fff; border-radius: 16px; border: 1px solid #E8EEF4; overflow: hidden; }
        .activity-header {
            background: linear-gradient(90deg, #F8FAFC 0%, #F1F5F9 100%);
            padding: 18px 24px;
            border-bottom: 1px solid #E8EEF4;
            display: flex; align-items: center; gap: 12px;
        }
        .activity-item {
            display: flex; align-items: flex-start; gap: 14px;
            padding: 14px 24px;
            border-bottom: 1px solid #F1F5F9;
            transition: background .15s;
        }
        .activity-item:last-child { border-bottom: none; }
        .activity-item:hover { background: #FAFBFC; }
        .activity-dot {
            width: 40px; height: 40px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
        }

        /* ---- DATE FILTER ---- */
        .filter-input {
            border: 1.5px solid #E2E8F0;
            border-radius: 9px;
            padding: 8px 12px;
            font-size: 13px;
            color: #334155;
            transition: border-color .2s, box-shadow .2s;
            background: #F8FAFC;
            font-family: 'DM Mono', monospace;
        }
        .filter-input:focus {
            outline: none;
            border-color: #3B82F6;
            box-shadow: 0 0 0 3px rgba(59,130,246,.12);
            background: #fff;
        }
        .filter-btn {
            background: #1D4ED8;
            color: #fff;
            border: none;
            border-radius: 9px;
            padding: 8px 18px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s, transform .1s;
            display: flex; align-items: center; gap: 6px;
        }
        .filter-btn:hover { background: #1E40AF; transform: translateY(-1px); }

        /* ---- PULSE ANIMATION ---- */
        @keyframes pulse-badge {
            0%,100% { opacity: 1; }
            50%      { opacity: .55; }
        }
        .pulse { animation: pulse-badge 2s ease-in-out infinite; }

        /* ---- GRID TWEAKS ---- */
        .dl-grid { display: grid; gap: 14px; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); }
        @media(max-width:640px) { .dl-grid { grid-template-columns: 1fr 1fr; } }

        /* ---- SCROLLBAR ---- */
        .nice-scroll::-webkit-scrollbar { width: 4px; }
        .nice-scroll::-webkit-scrollbar-track { background: #F1F5F9; border-radius: 4px; }
        .nice-scroll::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 4px; }

        @media (max-width: 768px) {
            .lg\:ml-64 { padding-top: 60px; }
            .header-bar > div { padding: 12px 14px; }
            #filterForm { flex-wrap: wrap; width: 100%; }
            .filter-input { flex: 1; min-width: 120px; }
            main.p-6 { padding: 14px !important; }
        }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Sidebar Placeholder -->
    @include('partials.sidebar')

    <!-- Overlay untuk mobile -->
    <div id="sidebarOverlay" class="fixed inset-0 z-30 bg-gray-900 bg-opacity-50 lg:hidden hidden"></div>

    <!-- Konten Utama -->
    <div class="lg:ml-64">

        <!-- ═══ HEADER ═══ -->
        <header class="header-bar sticky top-0 z-20">
            <div class="flex items-center justify-between px-6 py-3 gap-4">
                <div class="flex items-center gap-4">
                    <button id="sidebarToggle" class="text-gray-500 hover:text-gray-700 transition-colors lg:hidden p-2 rounded-xl hover:bg-gray-100">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 leading-tight">Laporan & Statistik</h1>
                        <p class="text-xs text-gray-400 font-medium">Pengelolaan Aset Tetap · BPMP Gorontalo</p>
                    </div>
                </div>

                <!-- Filter & Tanggal -->
                <div class="flex items-center gap-3 flex-wrap">
                    <form method="GET" action="{{ route('adminasettetap.laporan') }}" class="flex items-center gap-2" id="filterForm">
                        @csrf
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="filter-input">
                        <span class="text-gray-300 text-sm">–</span>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="filter-input">
                        <button type="submit" class="filter-btn">
                            <i class="fas fa-search" style="font-size:11px"></i>Filter
                        </button>
                    </form>
                    <div class="hidden md:flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-3 py-2">
                        <i class="fas fa-calendar-alt text-gray-400 text-xs"></i>
                        <span id="currentDate" class="text-xs text-gray-500 font-medium" style="font-family:'DM Mono',monospace"></span>
                    </div>
                </div>
            </div>
        </header>

        <!-- ═══ MAIN CONTENT ═══ -->
        <main class="p-6 space-y-8">

            <!-- ── DOWNLOAD SECTION ── -->
            <div class="dl-section p-8">
                <div class="relative z-10">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-7 gap-4">
                        <div>
                            <p class="section-title" style="color:rgba(148,163,184,.8); margin-bottom:6px">Unduh Laporan</p>
                            <h2 class="text-2xl font-bold text-white leading-tight">Download Laporan</h2>
                            <p class="text-blue-200 text-sm mt-1">Format PDF · Filter tanggal otomatis diterapkan</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1.5 rounded-full text-xs font-semibold" style="background:rgba(255,255,255,0.12);color:#BAE6FD">
                                <i class="fas fa-clock mr-1"></i>Auto-filter aktif
                            </span>
                        </div>
                    </div>

                    <div class="dl-grid">

                        <!-- Transaksi Masuk -->
                        <a href="{{ route('adminasettetap.transaksi-masuk.download', request()->query()) }}" class="dl-card green" target="_blank">
                            <div class="dl-icon" style="background:#DCFCE7">
                                <i class="fas fa-arrow-down" style="color:#16A34A"></i>
                            </div>
                            <h4 class="font-bold text-sm text-gray-800 mb-0.5">Transaksi Masuk</h4>
                            <p class="text-xs text-gray-400 mb-3">{{ number_format($stats['transaksi_masuk'] ?? 0) }} data</p>
                            <span class="dl-badge" style="background:#DCFCE7;color:#15803D">PDF</span>
                        </a>

                        <!-- Transaksi Keluar -->
                        <a href="{{ route('adminasettetap.transaksi-keluar.download', request()->query()) }}" class="dl-card red" target="_blank">
                            <div class="dl-icon" style="background:#FEE2E2">
                                <i class="fas fa-arrow-up" style="color:#DC2626"></i>
                            </div>
                            <h4 class="font-bold text-sm text-gray-800 mb-0.5">Transaksi Keluar</h4>
                            <p class="text-xs text-gray-400 mb-3">{{ number_format($stats['transaksi_keluar'] ?? 0) }} data</p>
                            <span class="dl-badge" style="background:#FEE2E2;color:#B91C1C">PDF</span>
                        </a>

                        <!-- Mutasi Barang (BARU) -->
                        <div class="dl-card" style="border-color: #E0F2FE;">
                            <div class="dl-icon" style="background:#E0F2FE">
                                <i class="fas fa-exchange-alt" style="color:#0369A1"></i>
                            </div>
                            <h4 class="font-bold text-sm text-gray-800 mb-0.5">Mutasi Barang</h4>
                            <p class="text-xs text-gray-400 mb-3">{{ number_format($stats['mutasi_barang'] ?? 0) }} data</p>
                            <div class="flex justify-center gap-1">
                                <a href="{{ route('adminasettetap.mutasi.download', array_merge(request()->query(), ['format' => 'pdf'])) }}" class="px-2 py-1 bg-red-50 text-[9px] font-bold text-red-600 rounded hover:bg-red-500 hover:text-white transition-colors">PDF</a>
                                
                            </div>
                        </div>

                        <!-- Pengaduan -->
                        {{-- <a href="{{ route('adminasettetap.pengaduan.download', request()->query()) }}" class="dl-card orange" target="_blank">
                            <div class="dl-icon" style="background:#FEF3C7">
                                <i class="fas fa-exclamation-triangle" style="color:#D97706"></i>
                            </div>
                            <h4 class="font-bold text-sm text-gray-800 mb-0.5">Pengaduan</h4>
                            <p class="text-xs text-gray-400 mb-3">{{ number_format($stats['total_pengaduan'] ?? 0) }} total</p>
                            <span class="dl-badge" style="background:#FEF3C7;color:#92400E">PDF/Excel</span>
                        </a>

                        <!-- Survey -->
                        <a href="{{ route('adminasettetap.survey.download', request()->query()) }}" class="dl-card emerald" target="_blank">
                            <div class="dl-icon" style="background:#D1FAE5">
                                <i class="fas fa-star" style="color:#059669"></i>
                            </div>
                            <h4 class="font-bold text-sm text-gray-800 mb-0.5">Survey Kepuasan</h4>
                            <p class="text-xs text-gray-400 mb-3">{{ number_format($stats['total_survey'] ?? 0) }} responden</p>
                            <span class="dl-badge" style="background:#D1FAE5;color:#065F46">PDF/Excel</span>
                        </a> --}}

                        <!-- Peminjaman -->
                        <a href="{{ route('adminasettetap.peminjaman.download', request()->query()) }}" class="dl-card purple" target="_blank">
                            <div class="dl-icon" style="background:#EDE9FE">
                                <i class="fas fa-handshake" style="color:#7C3AED"></i>
                            </div>
                            <h4 class="font-bold text-sm text-gray-800 mb-0.5">Peminjaman</h4>
                            <p class="text-xs text-gray-400 mb-3">{{ number_format(($stats['peminjaman_barang_aktif'] ?? 0) + ($stats['peminjaman_kendaraan_aktif'] ?? 0)) }} aktif</p>
                            <span class="dl-badge" style="background:#EDE9FE;color:#5B21B6">PDF</span>
                        </a>

                        <!-- Pengembalian (BARU) -->
                        <div class="dl-card blue">
                            <div class="dl-icon" style="background:#DBEAFE">
                                <i class="fas fa-undo-alt" style="color:#2563EB"></i>
                            </div>
                            <h4 class="font-bold text-sm text-gray-800 mb-0.5">Pengembalian</h4>
                            <p class="text-xs text-gray-400 mb-3">Barang & Kendaraan</p>
                            <div class="flex justify-center gap-1">
                                <a href="{{ route('adminasettetap.pengembalian.download', array_merge(request()->query(), ['format' => 'pdf'])) }}" class="px-2 py-1 bg-red-50 text-[9px] font-bold text-red-600 rounded hover:bg-red-500 hover:text-white transition-colors">PDF</a>
                                {{-- <a href="{{ route('adminasettetap.pengembalian.download', array_merge(request()->query(), ['format' => 'excel'])) }}" class="px-2 py-1 bg-green-50 text-[9px] font-bold text-green-600 rounded hover:bg-green-500 hover:text-white transition-colors">EXCEL</a> --}}
                            </div>
                        </div>

                        <!-- Ringkasan Dashboard -->
                        <a href="{{ route('adminasettetap.dashboard.download', request()->query()) }}" class="dl-card blue" target="_blank">
                            <div class="dl-icon" style="background:#DBEAFE">
                                <i class="fas fa-chart-pie" style="color:#1D4ED8"></i>
                            </div>
                            <h4 class="font-bold text-sm text-gray-800 mb-0.5">Ringkasan Dashboard</h4>
                            <p class="text-xs text-gray-400 mb-3">Semua statistik</p>
                            <span class="dl-badge" style="background:#DBEAFE;color:#1E40AF">PDF</span>
                        </a>

                        {{-- <!-- Download All ZIP -->
                        <a href="{{ route('adminasettetap.all.download', request()->query()) }}" class="dl-card dark" target="_blank" style="background:rgba(255,255,255,0.92)">
                            <div class="dl-icon" style="background:linear-gradient(135deg,#334155,#1E293B)">
                                <i class="fas fa-file-archive" style="color:#fff;font-size:20px"></i>
                            </div>
                            <h4 class="font-bold text-sm text-gray-800 mb-0.5">📦 Download Semua</h4>
                            <p class="text-xs text-gray-400 mb-3">ZIP 7 laporan lengkap</p>
                            <span class="dl-badge" style="background:#1E293B;color:#E2E8F0">ZIP File</span>
                        </a> --}}

                    </div>
                </div>
            </div>

            <!-- ── KPI CARDS ── -->
            <div>
                <p class="section-title mb-4">Ringkasan Kinerja</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">

                    <div class="kpi-card blue xl:col-span-1">
                        <div class="flex items-start justify-between mb-3">
                            <p class="kpi-label">Total Aset Tetap</p>
                            <div class="kpi-icon" style="background:#EFF6FF">
                                <i class="fas fa-building" style="color:#3B82F6"></i>
                            </div>
                        </div>
                        <p class="kpi-value" style="color:#1D4ED8">{{ number_format($stats['total_asset'] ?? 0) }}</p>
                        <p class="text-xs mt-1.5 font-semibold {{ ($stats['growth_asset'] ?? 0) >= 0 ? 'text-emerald-500' : 'text-red-400' }}">
                            {{ ($stats['growth_asset'] ?? 0) >= 0 ? '↑' : '↓' }} {{ $stats['growth_asset'] ?? 0 }}% pertumbuhan
                        </p>
                    </div>

                    <div class="kpi-card green xl:col-span-1">
                        <div class="flex items-start justify-between mb-3">
                            <p class="kpi-label">Transaksi Masuk</p>
                            <div class="kpi-icon" style="background:#ECFDF5">
                                <i class="fas fa-arrow-down" style="color:#10B981"></i>
                            </div>
                        </div>
                        <p class="kpi-value" style="color:#059669">{{ number_format($stats['transaksi_masuk'] ?? 0) }}</p>
                        <p class="text-xs mt-1.5 text-emerald-400 font-semibold">Bulan ini</p>
                    </div>

                    <div class="kpi-card red xl:col-span-1">
                        <div class="flex items-start justify-between mb-3">
                            <p class="kpi-label">Transaksi Keluar</p>
                            <div class="kpi-icon" style="background:#FEF2F2">
                                <i class="fas fa-arrow-up" style="color:#EF4444"></i>
                            </div>
                        </div>
                        <p class="kpi-value" style="color:#DC2626">{{ number_format($stats['transaksi_keluar'] ?? 0) }}</p>
                        <p class="text-xs mt-1.5 text-red-400 font-semibold">Bulan ini</p>
                    </div>

                    <div class="kpi-card purple xl:col-span-1">
                        <div class="flex items-start justify-between mb-3">
                            <p class="kpi-label">Peminjaman Aktif</p>
                            <div class="kpi-icon" style="background:#F5F3FF">
                                <i class="fas fa-handshake" style="color:#8B5CF6"></i>
                            </div>
                        </div>
                        <p class="kpi-value" style="color:#7C3AED">{{ number_format(($stats['peminjaman_barang_aktif'] ?? 0) + ($stats['peminjaman_kendaraan_aktif'] ?? 0)) }}</p>
                        <p class="text-xs mt-1.5 text-purple-400 font-semibold">Barang + Kendaraan</p>
                    </div>


                </div>
            </div>

            <!-- ══════════════════════════════════════
                 ── CHARTS — COMPACT 2×2 GRID ──
            ══════════════════════════════════════ -->
            <div>
                <div class="chart-section-header">
                    <p class="section-title">Grafik &amp; Analitik</p>
                    <span style="font-size:11px;color:#CBD5E1;font-weight:500">4 grafik · data real-time</span>
                </div>

                <div class="charts-grid">

                    <!-- 1. Transaksi Masuk vs Keluar -->
                    <div class="chart-card">
                        <div class="chart-header">
                            <div class="chart-header-left">
                                <div class="chart-icon-sm" style="background:#F0FDF4">
                                    <i class="fas fa-chart-bar" style="color:#16A34A;font-size:11px"></i>
                                </div>
                                <div>
                                    <div class="chart-title-text">Transaksi Masuk vs Keluar</div>
                                    <div class="chart-subtitle-text">Perbandingan per periode</div>
                                </div>
                            </div>
                            <span class="chart-stat-pill" style="background:#F0FDF4;color:#15803D">
                                <i class="fas fa-arrow-trend-up" style="font-size:9px"></i>
                                {{ number_format(($stats['transaksi_masuk'] ?? 0) + ($stats['transaksi_keluar'] ?? 0)) }} total
                            </span>
                        </div>
                        <div class="chart-body">
                            <canvas id="transactionChart"></canvas>
                        </div>
                    </div>

                    <!-- 2. Status Peminjaman (Doughnut) -->
                    <div class="chart-card">
                        <div class="chart-header">
                            <div class="chart-header-left">
                                <div class="chart-icon-sm" style="background:#F5F3FF">
                                    <i class="fas fa-chart-pie" style="color:#7C3AED;font-size:11px"></i>
                                </div>
                                <div>
                                    <div class="chart-title-text">Status Peminjaman</div>
                                    <div class="chart-subtitle-text">Distribusi barang &amp; kendaraan</div>
                                </div>
                            </div>
                            <span class="chart-stat-pill" style="background:#F5F3FF;color:#5B21B6">
                                <i class="fas fa-handshake" style="font-size:9px"></i>
                                {{ number_format(($stats['peminjaman_barang_aktif'] ?? 0) + ($stats['peminjaman_kendaraan_aktif'] ?? 0)) }} aktif
                            </span>
                        </div>
                        <!-- Donut + inline legend -->
                        <div class="donut-layout">
                            <div class="donut-wrap">
                                <canvas id="peminjamanChart"></canvas>
                                <div class="donut-center-label">
                                    <span>{{ ($stats['peminjaman_barang_aktif'] ?? 0) + ($stats['peminjaman_kendaraan_aktif'] ?? 0) }}</span>
                                    <span>aktif</span>
                                </div>
                            </div>
                            <div class="donut-legend" id="donutLegend">
                                <div class="donut-legend-item">
                                    <div class="donut-legend-left">
                                        <div class="donut-dot" style="background:#3B82F6"></div>
                                        <span class="donut-legend-label">Aktif</span>
                                    </div>
                                    <span class="donut-legend-val">{{ ($stats['peminjaman_barang_aktif'] ?? 0) + ($stats['peminjaman_kendaraan_aktif'] ?? 0) }}</span>
                                </div>
                                <div class="donut-legend-item">
                                    <div class="donut-legend-left">
                                        <div class="donut-dot" style="background:#10B981"></div>
                                        <span class="donut-legend-label">Selesai</span>
                                    </div>
                                    <span class="donut-legend-val">{{ number_format(($stats['peminjaman_barang_aktif'] ?? 0) * 0.3) }}</span>
                                </div>
                                <div class="donut-legend-item">
                                    <div class="donut-legend-left">
                                        <div class="donut-dot" style="background:#F59E0B"></div>
                                        <span class="donut-legend-label">Pending</span>
                                    </div>
                                    <span class="donut-legend-val">{{ number_format(($stats['peminjaman_barang_aktif'] ?? 0) * 0.2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                     <!-- 4. Peminjaman barang -->
                    <div class="chart-card">
                        <div class="chart-header">
                            <div class="chart-header-left">
                                <div class="chart-icon-sm" style="background:#EFF6FF">
                                    <i class="fas fa-car" style="color:#2563EB;font-size:11px"></i>
                                </div>
                                <div>
                                    <div class="chart-title-text">Peminjaman Barang</div>
                                    <div class="chart-subtitle-text">Volume per periode</div>
                                </div>
                            </div>
                            <span class="chart-stat-pill" style="background:#EFF6FF;color:#1E40AF">
                                <i class="fas fa-car" style="font-size:9px"></i>
                                {{ number_format($stats['peminjaman_barang_aktif'] ?? 0) }} aktif
                            </span>
                        </div>
                        <div class="chart-body">
                            <canvas id="barangChart"></canvas>
                        </div>
                    </div>

                    <!-- 4. Peminjaman Kendaraan -->
                    <div class="chart-card">
                        <div class="chart-header">
                            <div class="chart-header-left">
                                <div class="chart-icon-sm" style="background:#EFF6FF">
                                    <i class="fas fa-car" style="color:#2563EB;font-size:11px"></i>
                                </div>
                                <div>
                                    <div class="chart-title-text">Peminjaman Kendaraan</div>
                                    <div class="chart-subtitle-text">Volume per periode</div>
                                </div>
                            </div>
                            <span class="chart-stat-pill" style="background:#EFF6FF;color:#1E40AF">
                                <i class="fas fa-car" style="font-size:9px"></i>
                                {{ number_format($stats['peminjaman_kendaraan_aktif'] ?? 0) }} aktif
                            </span>
                        </div>
                        <div class="chart-body">
                            <canvas id="kendaraanChart"></canvas>
                        </div>
                    </div>

                </div>
            </div>
            <!-- ══ END CHARTS ══ -->

            <!-- ── AKTIVITAS TERBARU ── -->
            <div class="activity-card">
                <div class="activity-header">
                    <div style="width:36px;height:36px;border-radius:10px;background:#F1F5F9;display:flex;align-items:center;justify-content:center">
                        <i class="fas fa-history" style="color:#64748B;font-size:13px"></i>
                    </div>
                    <div>
                        <h3 style="font-size:15px;font-weight:700;color:#1E293B;margin:0">Aktivitas Terbaru</h3>
                        <p style="font-size:11px;color:#94A3B8;margin:0">{{ count($recentActivities ?? []) }} catatan terbaru</p>
                    </div>
                </div>
                <div class="max-h-96 overflow-y-auto nice-scroll">
                    @forelse($recentActivities ?? [] as $activity)
                        <div class="activity-item">
                            <div class="activity-dot bg-{{ $activity['color'] }}-100">
                                <i class="{{ $activity['icon'] }} text-{{ $activity['color'] }}-500" style="font-size:13px"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p style="font-size:13px;font-weight:600;color:#1E293B;margin:0 0 2px">{{ $activity['title'] }}</p>
                                <p style="font-size:12px;color:#64748B;margin:0 0 4px">{{ $activity['desc'] }}</p>
                                <span style="font-size:11px;color:#94A3B8;font-family:'DM Mono',monospace">
                                    {{ \Carbon\Carbon::parse($activity['date'])->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div style="padding:48px;text-align:center">
                            <div style="width:56px;height:56px;border-radius:16px;background:#F1F5F9;display:flex;align-items:center;justify-content:center;margin:0 auto 14px">
                                <i class="fas fa-inbox" style="color:#CBD5E1;font-size:22px"></i>
                            </div>
                            <p style="font-size:14px;font-weight:600;color:#94A3B8;margin:0 0 4px">Belum ada aktivitas</p>
                            <p style="font-size:12px;color:#CBD5E1;margin:0">Aktivitas akan muncul di sini</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </main>
    </div>

    <!-- ═══ SCRIPTS ═══ -->
    <script>
        // Current date
        document.getElementById('currentDate').textContent = new Date().toLocaleDateString('id-ID', {
            weekday: 'short', year: 'numeric', month: 'short', day: 'numeric'
        });

        // Sidebar Toggle
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('-translate-x-full');
            document.getElementById('sidebarOverlay').classList.toggle('hidden');
        });
        document.getElementById('sidebarOverlay')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.add('-translate-x-full');
            this.classList.add('hidden');
        });

        // Shared chart defaults
        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
        Chart.defaults.font.size = 10;
        Chart.defaults.color = '#94A3B8';

        document.addEventListener('DOMContentLoaded', function () {

            // ── 1. Transaction Bar Chart (Masuk vs Keluar) ──
            const transactionCtx = document.getElementById('transactionChart');
            if (transactionCtx) {
                // Tarik data dengan fallback array kosong
                const txMasuk = @json($charts['transaksi_masuk_chart'] ?? []);
                const txKeluar = @json($charts['transaksi_keluar_chart'] ?? []);

                // Gabungkan semua tanggal unik agar grafik Masuk dan Keluar presisi sejajar
                const allDates = [...new Set([
                    ...txMasuk.map(i => i.date), 
                    ...txKeluar.map(i => i.date)
                ])].sort();

                // Petakan jumlah (count) berdasarkan susunan tanggal unik
                const dataMasuk = allDates.map(date => {
                    const found = txMasuk.find(i => i.date === date);
                    return found ? found.count : 0;
                });
                const dataKeluar = allDates.map(date => {
                    const found = txKeluar.find(i => i.date === date);
                    return found ? found.count : 0;
                });

                if (allDates.length > 0) {
                    new Chart(transactionCtx, {
                        type: 'bar',
                        data: {
                            labels: allDates,
                            datasets: [{
                                label: 'Masuk',
                                data: dataMasuk,
                                backgroundColor: 'rgba(16,185,129,0.75)',
                                borderColor: 'rgba(16,185,129,1)',
                                borderRadius: 5,
                                borderSkipped: false,
                            }, {
                                label: 'Keluar',
                                data: dataKeluar,
                                backgroundColor: 'rgba(239,68,68,0.7)',
                                borderColor: 'rgba(239,68,68,1)',
                                borderRadius: 5,
                                borderSkipped: false,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'top',
                                    align: 'end',
                                    labels: { boxWidth: 8, borderRadius: 3, usePointStyle: true, padding: 12, font: { size: 10 } }
                                }
                            },
                            scales: {
                                y: { beginAtZero: true, grid: { color: '#F1F5F9' }, border: { dash: [3,3] }, ticks: { maxTicksLimit: 4 } },
                                x: { grid: { display: false } }
                            }
                        }
                    });
                }
            }

            // ── 2. Peminjaman Doughnut (Status Peminjaman) ──
            const peminjamanCtx = document.getElementById('peminjamanChart');
            if (peminjamanCtx) {
                // Gunakan kurung pada PHP ?? untuk menghindari error matematika
                const totalAktif = {{ ($stats['peminjaman_barang_aktif'] ?? 0) + ($stats['peminjaman_kendaraan_aktif'] ?? 0) }};
                const totalSelesai = {{ ($stats['peminjaman_barang_selesai'] ?? 0) + ($stats['peminjaman_kendaraan_selesai'] ?? 0) }};
                const totalPending = {{ ($stats['peminjaman_barang_pending'] ?? 0) + ($stats['peminjaman_kendaraan_pending'] ?? 0) }};

                // Fallback visual jika data belum didefinisikan secara backend (sama seperti fitur sebelumnya)
                const finalSelesai = totalSelesai > 0 ? totalSelesai : Math.round(totalAktif * 0.3);
                const finalPending = totalPending > 0 ? totalPending : Math.round(totalAktif * 0.2);

                new Chart(peminjamanCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Aktif', 'Selesai', 'Pending'],
                        datasets: [{
                            data: [totalAktif, finalSelesai, finalPending],
                            backgroundColor: ['#3B82F6','#10B981','#F59E0B'],
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: { legend: { display: false } }
                    }
                });
            }

            // ── 3. Peminjaman Barang Bar Chart (DITAMBAHKAN) ──
            const barangCtx = document.getElementById('barangChart');
            if (barangCtx && @json($charts['peminjaman_barang_chart'] ?? [])?.length) {
                new Chart(barangCtx, {
                    type: 'bar',
                    data: {
                        labels: @json(collect($charts['peminjaman_barang_chart'] ?? [])->pluck('date')->toArray()),
                        datasets: [{
                            label: 'Peminjaman Barang',
                            data: @json(collect($charts['peminjaman_barang_chart'] ?? [])->pluck('count')->toArray()),
                            backgroundColor: 'rgba(139,92,246,0.75)', // Warna ungu
                            borderColor: 'rgba(139,92,246,1)',
                            borderRadius: 5,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { color: '#F1F5F9' }, border: { dash: [3,3] }, ticks: { maxTicksLimit: 4 } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }

            // ── 4. Kendaraan Bar Chart ──
            const kendaraanCtx = document.getElementById('kendaraanChart');
            if (kendaraanCtx && @json($charts['peminjaman_kendaraan_chart'] ?? [])?.length) {
                new Chart(kendaraanCtx, {
                    type: 'bar',
                    data: {
                        labels: @json(collect($charts['peminjaman_kendaraan_chart'] ?? [])->pluck('date')->toArray()),
                        datasets: [{
                            label: 'Peminjaman Kendaraan',
                            data: @json(collect($charts['peminjaman_kendaraan_chart'] ?? [])->pluck('count')->toArray()),
                            backgroundColor: 'rgba(59,130,246,0.75)', // Warna biru
                            borderColor: 'rgba(59,130,246,1)',
                            borderRadius: 5,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { color: '#F1F5F9' }, border: { dash: [3,3] }, ticks: { maxTicksLimit: 4 } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }
        });

        // Reload after filter submit
        document.getElementById('filterForm')?.addEventListener('submit', function () {
            setTimeout(() => location.reload(), 100);
        });
    </script>
</body>
</html>
