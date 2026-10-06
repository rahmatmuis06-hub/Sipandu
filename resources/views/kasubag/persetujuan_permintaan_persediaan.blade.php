<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persetujuan Permintaan Persediaan - Dashboard Kasubag</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --blue: #3b6ff0;
            --blue-light: #eef2ff;
            --orange: #f59e0b;
            --orange-light: #fffbeb;
            --green: #10b981;
            --green-light: #ecfdf5;
            --red: #ef4444;
            --red-light: #fef2f2;
            --purple: #8b5cf6;
            --purple-light: #f5f3ff;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-400: #94a3b8;
            --gray-600: #475569;
            --gray-800: #1e293b;
            --sidebar-w: 240px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--gray-50);
            color: var(--gray-800);
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: var(--sidebar-w);
            background: #fff;
            border-right: 1px solid var(--gray-200);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 10;
        }

        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: 60px;
            background: #fff;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 28px;
            gap: 16px;
            z-index: 9;
        }

        .notif-btn {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            position: relative;
            border: none;
        }

        .notif-btn svg {
            width: 18px;
            height: 18px;
            stroke: var(--gray-600);
            fill: none;
            stroke-width: 2;
        }

        .user-chip {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--gray-100);
            border-radius: 10px;
            padding: 6px 12px 6px 6px;
        }

        .user-avatar {
            width: 30px;
            height: 30px;
            background: var(--blue);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            color: #fff;
        }

        .user-info strong {
            font-size: 13px;
            font-weight: 600;
            display: block;
        }

        .user-info span {
            font-size: 11px;
            color: var(--gray-400);
        }

        .main {
            margin-left: var(--sidebar-w);
            margin-top: 60px;
            padding: 32px;
            flex: 1;
            width: calc(100% - var(--sidebar-w));
        }

        .page-header {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 28px;
        }

        .page-header-icon {
            width: 48px;
            height: 48px;
            background: var(--green-light);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .page-header-icon svg {
            width: 24px;
            height: 24px;
            stroke: var(--green);
            fill: none;
            stroke-width: 2;
        }

        .page-header-text h1 {
            font-size: 22px;
            font-weight: 700;
        }

        .page-header-text p {
            font-size: 13px;
            color: var(--gray-400);
            margin-top: 2px;
        }

        /* SUMMARY CARDS CSS */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 32px;
        }

        .summary-card {
            background: #fff;
            padding: 20px 24px;
            border-radius: 16px;
            border: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.02);
        }

        .summary-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .summary-icon svg {
            width: 26px;
            height: 26px;
            fill: none;
            stroke-width: 2;
        }

        .summary-info h3 {
            font-size: 12px;
            color: var(--gray-400);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .summary-info .num {
            font-size: 28px;
            font-weight: 700;
            color: var(--gray-800);
            line-height: 1;
        }

        .section-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 16px;
            border-bottom: 2px solid var(--gray-200);
            padding-bottom: 10px;
        }

        .req-card {
            background: #fff;
            border: 1px solid var(--gray-200);
            border-radius: 14px;
            padding: 20px 22px;
            margin-bottom: 14px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            transition: box-shadow .15s;
        }

        .req-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, .05);
        }

        .req-card.highlighted {
            border: 2px solid var(--orange);
            background: #fffdf5;
        }

        .req-info {
            flex: 1;
        }

        .req-tags {
            display: flex;
            gap: 8px;
            margin-bottom: 8px;
        }

        .tag {
            font-size: 11.5px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 6px;
        }

        .tag.id {
            background: var(--gray-100);
            color: var(--gray-600);
        }

        .tag.pending {
            background: var(--orange-light);
            color: var(--orange);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .tag.success {
            background: var(--green-light);
            color: var(--green);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .tag.danger {
            background: var(--red-light);
            color: var(--red);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .req-name {
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--blue);
        }

        .req-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4px 32px;
            font-size: 12.5px;
            color: var(--gray-600);
            margin-bottom: 6px;
        }

        .req-meta span strong {
            color: var(--gray-800);
        }

        .req-purpose {
            font-size: 12.5px;
            color: var(--gray-600);
            background: rgba(255, 255, 255, 0.5);
            padding: 8px 12px;
            border-radius: 8px;
            margin-top: 10px;
            border: 1px solid var(--gray-200);
        }

        .req-purpose span {
            color: var(--gray-800);
            font-weight: 500;
        }

        .req-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
            align-items: stretch;
            flex-shrink: 0;
            min-width: 130px;
        }

        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all .15s;
            width: 100%;
        }

        .btn-detail {
            background: var(--gray-100);
            color: var(--gray-600);
        }

        .btn-detail:hover {
            background: var(--gray-200);
        }

        .btn-detail svg {
            width: 15px;
            height: 15px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
        }

        .btn-approve {
            background: var(--green);
            color: #fff;
        }

        .btn-approve:hover {
            background: #059669;
        }

        .btn-approve svg {
            width: 14px;
            height: 14px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2.5;
        }

        .btn-reject {
            background: var(--red);
            color: #fff;
        }

        .btn-reject:hover {
            background: #dc2626;
        }

        .btn-reject svg {
            width: 14px;
            height: 14px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2.5;
        }
    </style>
</head>

<body>

    @include('partials.sidebar')

    <div class="topbar">
        {{-- <button class="notif-btn"><svg viewBox="0 0 24 24">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                <path d="M13.73 21a2 2 0 0 1-3.46 0" />
            </svg></button> --}}
        <div class="user-chip">
            <div class="user-avatar">{{ substr(Auth::user()->name ?? 'K', 0, 1) }}</div>
            <div class="user-info"><strong>{{ Auth::user()->name ?? 'Kasubag' }}</strong><span>Kepala Sub Bagian</span>
            </div>
        </div>
    </div>


    <main class="main">
        <div class="page-header">
            <div class="page-header-icon"><svg viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="3" />
                    <path d="M19.07 4.93a10 10 0 0 1 0 14.14" />
                    <path d="M4.93 4.93a10 10 0 0 0 0 14.14" />
                </svg></div>
            <div class="page-header-text">
                <h1>Persetujuan Permintaan Persediaan</h1>
                <p>Kelola dan tinjau seluruh permintaan persediaan dari pegawai</p>
            </div>
        </div>

        @if (session('success'))
            <div
                style="background: var(--green-light); color: var(--green); padding: 14px 20px; border-radius: 10px; margin-bottom: 24px; font-size: 13.5px; font-weight: 600; border: 1px solid #bbf7d0;">
                <i class="fas fa-check-circle" style="margin-right: 6px;"></i> {{ session('success') }}
            </div>
        @endif

        <!-- SUMMARY CARDS -->
        <div class="summary-grid">
            <div class="summary-card">
                <div class="summary-icon" style="background: var(--orange-light);"><svg stroke="var(--orange)">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg></div>
                <div class="summary-info">
                    <h3>Menunggu Review</h3>
                    <div class="num">{{ $stats['menunggu'] }}</div>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon" style="background: var(--green-light);"><svg stroke="var(--green)">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                        <polyline points="22 4 12 14.01 9 11.01" />
                    </svg></div>
                <div class="summary-info">
                    <h3>Telah Disetujui</h3>
                    <div class="num">{{ $stats['disetujui'] }}</div>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon" style="background: var(--blue-light);"><svg stroke="var(--blue)">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" y1="13" x2="8" y2="13" />
                        <line x1="16" y1="17" x2="8" y2="17" />
                        <polyline points="10 9 9 9 8 9" />
                    </svg></div>
                <div class="summary-info">
                    <h3>Total Permintaan</h3>
                    <div class="num">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>

        <div class="section-label">Daftar Pengajuan</div>

        <!-- LOOPING DATA DARI DATABASE -->
        @forelse($permintaan as $item)
            <div class="req-card {{ $item->status === 'dalam_review' ? 'highlighted' : '' }}">
                <div class="req-info">
                    <div class="req-tags">
                        <span class="tag id">REQ-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</span>

                        <!-- Badge Status Dinamis -->
                        @if($item->status == 'diteruskan_kasubag')
                            <span class="badge bg-warning">Menunggu Persetujuan Kasubag</span>
                        @elseif($item->status == 'disetujui' || $item->status == 'disetujui_kasubag')
                            <span class="badge bg-success">Disetujui</span>
                        @elseif($item->status == 'ditolak' || $item->status == 'ditolak_kasubag')
                            <span class="badge bg-danger">Ditolak</span>
                        @endif
                    </div>

                    <div class="req-name">
                        {{ $item->persediaan->nama_barang ?? $item->nama_barang }}
                        <span style="color: var(--gray-600); font-weight: 500;">({{ $item->jumlah_diminta }}
                            Unit)</span>
                    </div>

                    <div class="req-meta">
                        <span>Pemohon: <strong>{{ $item->nama_lengkap }}</strong></span>
                        <span>Kode Barang: <strong>{{ $item->kode_barang }}</strong></span>
                        <span>Tanggal Permintaan:
                            <strong>{{ \Carbon\Carbon::parse($item->tanggal_permintaan)->format('d M Y') }}</strong></span>
                        <span>Tanggal Dibutuhkan:
                            <strong>{{ \Carbon\Carbon::parse($item->tanggal_dibutuhkan)->format('d M Y') }}</strong></span>
                    </div>

                    <div class="req-purpose">Tujuan Penggunaan: <span>{{ $item->tujuan_penggunaan }}</span></div>
                </div>

                <div class="req-actions">
                    <!-- Tombol Detail -->
                    <button class="btn btn-detail"
                        onclick="window.location.href='/kasubag/permintaan-persediaan/{{ $item->id }}'">
                        <svg viewBox="0 0 24 24">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg> Detail
                    </button>

                    <!-- Tampilkan Form Setuju/Tolak HANYA jika status masih 'dalam_review' -->
                    @if ($item->status === 'diteruskan_kasubag')
                        <form action="{{ route('kasubag.approve-permintaan', $item->id) }}" method="POST"
                            style="width: 100%;">
                            @csrf
                            <input type="hidden" name="action" value="setuju">
                            <label for="tanggal-penerimaan-{{ $item->id }}"
                                style="display:block; margin-bottom:4px; font-size:12px; font-weight:700;">Tanggal Penerimaan</label>
                            <input id="tanggal-penerimaan-{{ $item->id }}" type="date" name="tanggal_penerimaan"
                                value="{{ old('tanggal_penerimaan', now()->format('Y-m-d')) }}" required
                                style="width:100%; padding:9px; margin-bottom:8px; border:1px solid var(--gray-200); border-radius:8px;">
                            <button type="submit" class="btn btn-approve"
                                onclick="return confirm('Yakin ingin MENYETUJUI permintaan ini?')">
                                <svg viewBox="0 0 24 24">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg> Setuju
                            </button>
                        </form>

                        <form action="{{ route('kasubag.approve-permintaan', $item->id) }}" method="POST"
                            style="width: 100%;">
                            @csrf
                            <input type="hidden" name="action" value="tolak">
                            <button type="submit" class="btn btn-reject"
                                onclick="return confirm('Yakin ingin MENOLAK permintaan ini?')">
                                <svg viewBox="0 0 24 24">
                                    <line x1="18" y1="6" x2="6" y2="18" />
                                    <line x1="6" y1="6" x2="18" y2="18" />
                                </svg> Tolak
                            </button>
                        </form>
                    @else
                        <!-- Label jika sudah diproses -->
                        <div
                            style="background: var(--gray-100); color: var(--gray-600); padding: 10px; border-radius: 8px; text-align: center; font-size: 12px; font-weight: 700; border: 1px dashed var(--gray-200); margin-top: auto;">
                            Telah Diproses
                        </div>
                        @if($item->tanggal_penerimaan)
                            <div style="font-size:12px; text-align:center; margin-top:6px;">
                                Diterima: <strong>{{ $item->tanggal_penerimaan->translatedFormat('d F Y') }}</strong>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        @empty
            <div
                style="text-align: center; padding: 60px 20px; border: 2px dashed var(--gray-200); border-radius: 14px; background: #fff;">
                <svg viewBox="0 0 24 24"
                    style="width: 48px; height: 48px; stroke: var(--gray-400); fill: none; stroke-width: 1.5; margin-bottom: 12px; margin-left: auto; margin-right: auto; display: block;">
                    <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                </svg>
                <h3 style="font-size: 16px; color: var(--gray-800); margin-bottom: 4px;">Semua Beres!</h3>
                <p style="color: var(--gray-400); font-size: 13px;">Belum ada riwayat permintaan persediaan di sistem.
                </p>
            </div>
        @endforelse

    </main>

</body>

</html>
