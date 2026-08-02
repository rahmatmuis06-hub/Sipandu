<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPANDU - Data Aset Tetap</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --blue: #4F6FFF;
            --sidebar-w: 240px;
            --radius: 16px;
            --bg: #F4F6FB;
            --surface: #FFFFFF;
            --text: #1E293B;
            --muted: #94A3B8;
            --border: #E8EDF5;
            --success: #10B981;
            --danger: #EF4444;
            --warning: #F59E0B;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 100;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 20px 20px 16px;
            border-bottom: 1px solid var(--border);
        }

        .logo-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--blue), #7C3AED);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-icon svg {
            width: 20px;
            height: 20px;
            fill: white;
        }

        .logo-text strong {
            font-size: 15px;
            font-weight: 800;
            color: var(--text);
            display: block;
        }

        .logo-text span {
            font-size: 10px;
            color: var(--muted);
            font-weight: 600;
            letter-spacing: 1px;
        }

        .sidebar-section {
            padding: 16px 16px 4px;
        }

        .sidebar-label {
            font-size: 10px;
            font-weight: 700;
            color: var(--muted);
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 0 4px 8px;
        }

        .role-badge {
            background: linear-gradient(135deg, #EEF2FF, #E0E7FF);
            border: 1px solid #C7D2FE;
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 12px;
            font-weight: 700;
            color: var(--blue);
            margin: 0 16px 16px;
        }

        .nav {
            flex: 1;
            overflow-y: auto;
            padding: 4px 12px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 500;
            color: #64748B;
            cursor: pointer;
            transition: all .15s;
            margin-bottom: 2px;
        }

        .nav-item:hover {
            background: var(--bg);
            color: var(--text);
        }

        .nav-item.active {
            background: linear-gradient(135deg, #EEF2FF, #E0E7FF);
            color: var(--blue);
            font-weight: 700;
        }

        .nav-item svg {
            width: 17px;
            height: 17px;
            flex-shrink: 0;
        }

        .nav-item .chevron {
            margin-left: auto;
            width: 14px;
            height: 14px;
        }

        .sidebar-footer {
            border-top: 1px solid var(--border);
            padding: 14px 16px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--blue), #7C3AED);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 13px;
            font-weight: 700;
        }

        .user-detail strong {
            font-size: 13px;
            font-weight: 700;
            display: block;
        }

        .user-detail span {
            font-size: 11px;
            color: var(--muted);
        }

        .btn-logout {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: transparent;
            color: #64748B;
            font-size: 13px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all .15s;
        }

        .btn-logout:hover {
            background: #FEF2F2;
            color: #EF4444;
            border-color: #FECACA;
        }

        /* MAIN */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 28px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-title {
            font-size: 16px;
            font-weight: 700;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .date-text {
            font-size: 13px;
            color: #64748B;
            font-weight: 500;
        }

        .content {
            padding: 28px;
            flex: 1;
        }

        /* TOMBOL IMPORT & HEADER ACTIONS */
        .header-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .btn-import {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 10px 18px;
            border-radius: 10px;
            background: var(--surface);
            color: var(--blue);
            font-size: 13.5px;
            font-weight: 700;
            font-family: inherit;
            border: 1.5px solid var(--blue);
            cursor: pointer;
            transition: all .2s;
        }

        .btn-import:hover {
            background: #EEF2FF;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 111, 255, .15);
        }

        .btn-import svg {
            width: 16px;
            height: 16px;
            fill: currentColor;
        }

        .upload-area {
            border: 2px dashed var(--border);
            padding: 32px 20px;
            text-align: center;
            border-radius: 12px;
            background: #F8FAFF;
            margin-bottom: 12px;
            transition: border-color 0.2s;
        }

        .upload-area:hover {
            border-color: var(--blue);
        }

        /* ALERT */
        .alert {
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
        }

        .alert-success {
            background: #ECFDF5;
            color: var(--success);
            border: 1px solid #BBF7D0;
        }

        .alert-danger {
            background: #FEF2F2;
            color: var(--danger);
            border: 1px solid #FECACA;
        }

        /* PAGE HEADER */
        .page-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .page-top h1 {
            font-size: 22px;
            font-weight: 800;
            color: var(--blue);
            margin-bottom: 4px;
        }

        .page-top p {
            font-size: 13px;
            color: var(--muted);
        }

        .btn-tambah {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 10px 18px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--blue), #7C3AED);
            color: white;
            font-size: 13.5px;
            font-weight: 700;
            font-family: inherit;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(79, 111, 255, .35);
            transition: all .2s;
            text-decoration: none;
        }

        .btn-tambah:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(79, 111, 255, .45);
        }

        /* TABLE CARD */
        .table-card {
            background: var(--surface);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .table-toolbar {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
        }

        .search-wrap {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 8px 14px;
            background: var(--bg);
            transition: border-color .15s;
        }

        .search-wrap:focus-within {
            border-color: var(--blue);
        }

        .search-wrap input {
            border: none;
            background: none;
            outline: none;
            font-family: inherit;
            font-size: 13.5px;
            color: var(--text);
            width: 100%;
        }

        .search-wrap input::placeholder {
            color: var(--muted);
        }

        .filter-select {
            padding: 8px 14px;
            border-radius: 10px;
            border: 1.5px solid var(--border);
            background: var(--bg);
            font-family: inherit;
            font-size: 13px;
            color: var(--text);
            cursor: pointer;
            outline: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead tr {
            background: #F8FAFF;
        }

        th {
            padding: 12px 20px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            color: var(--blue);
            letter-spacing: .8px;
            text-transform: uppercase;
            border-bottom: 1px solid var(--border);
        }

        td {
            padding: 14px 20px;
            font-size: 13.5px;
            color: var(--text);
            border-bottom: 1px solid var(--border);
        }

        tr:last-child td {
            border-bottom: none;
        }

        tbody tr {
            transition: background .15s;
        }

        tbody tr:hover {
            background: #F8FAFF;
        }

        /* STATUS BADGE */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: capitalize;
            letter-spacing: .3px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .status-baik { background: #ECFDF5; color: var(--success); }
        .status-rusak { background: #FEF2F2; color: var(--danger); }
        .status-perawatan { background: #FEF3C7; color: var(--warning); }

        /* ACTION BUTTONS */
        td:last-child {
            white-space: nowrap;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: var(--surface);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all .15s;
            margin-left: 4px;
        }

        .action-btn:hover {
            background: #EEF2FF;
            border-color: var(--blue);
            transform: translateY(-1px);
        }

        .action-btn.danger:hover {
            background: #FEF2F2;
            border-color: var(--danger);
        }

        .action-btn svg { width: 16px; height: 16px; }

        /* MODAL */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, .5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all .2s;
        }

        .modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .modal {
            background: var(--surface);
            border-radius: var(--radius);
            padding: 28px;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            transform: scale(.9) translateY(-20px);
            transition: all .2s;
        }

        .modal-overlay.show .modal {
            transform: scale(1) translateY(0);
        }

        .modal-title {
            font-size: 20px;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 6px;
        }

        .form-input,
        .form-select {
            width: 100%;
            padding: 12px 16px;
            border-radius: 10px;
            border: 1.5px solid var(--border);
            background: var(--bg);
            font-family: inherit;
            font-size: 14px;
            transition: all .15s;
        }

        .form-input:focus,
        .form-select:focus {
            outline: none;
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(79, 111, 255, .1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all .2s;
            font-family: inherit;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--blue), #7C3AED);
            color: white;
            box-shadow: 0 4px 14px rgba(79, 111, 255, .35);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(79, 111, 255, .45);
        }

        .btn-secondary {
            background: var(--bg);
            color: var(--text);
            border: 1.5px solid var(--border);
        }

        .btn-group {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        /* PAGINATION */
        .table-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            border-top: 1px solid var(--border);
            font-size: 13px;
            color: var(--muted);
        }

        .form-kendaraan {
            background: #F8FAFF;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            display: none;
        }

        .form-kendaraan-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--blue);
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border);
        }

        @media (max-width: 768px) {
            .main { margin-left: 0; }
            .sidebar { transform: translateX(-100%); }
            .form-row { grid-template-columns: 1fr; }
            .table-card { overflow-x: auto; }
            table { min-width: 900px; }
        }
    </style>
</head>

<body>

    @include('partials.sidebar')

    <main class="main">
        <div class="topbar">
            <span class="topbar-title">Data Aset Tetap</span>
            <div class="topbar-right">
                <span class="date-text">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}</span>
            </div>
        </div>
        
        <div class="content">
            @if (session('success'))
                <div class="alert alert-success">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- PERBAIKAN 1: PENANGKAP ERROR VALIDASI AGAR FORM TIDAK "MENGUAP" --}}
            @if ($errors->any())
                <div class="alert alert-danger" style="display: block;">
                    <div style="display: flex; align-items: center; gap: 10px; font-weight: bold; margin-bottom: 8px;">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                        </svg>
                        Terdapat Kesalahan Input (Silakan cek form kembali):
                    </div>
                    <ul style="margin-left: 35px; font-size: 13px; font-weight: normal;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="page-top">
                <div>
                    <h1>Data Aset Tetap</h1>
                    <p>{{ $asetTetap->total() }} data ditemukan</p>
                </div>
                <div class="header-actions">
                    <button class="btn-import" onclick="openModal('modal-import')">
                        <svg viewBox="0 0 24 24">
                            <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z" />
                        </svg>
                        Import Excel
                    </button>
                    <a href="#modal-tambah" class="btn-tambah" onclick="openModal('modal-tambah')">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="white">
                            <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z" />
                        </svg>
                        Tambah Baru
                    </a>
                </div>
            </div>

            <div class="table-card">
                <div class="table-toolbar">
                    <form method="GET" action="{{ route('adminasettetap.data-aset-tetap') }}" class="search-wrap">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="#94A3B8">
                            <path d="M15.5 14h-.79l-.28-.27A6.47 6.47 0 0016 9.5 6.5 6.5 0 109.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" />
                        </svg>
                        <input type="text" name="search" placeholder="Cari aset tetap..." value="{{ request('search') }}">
                    </form>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Merek</th>
                            <th>Kategori</th>
                            <th>Kondisi</th>
                            <th>Lokasi</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($asetTetap as $index => $aset)
                            <tr>
                                <td><strong>{{ $asetTetap->firstItem() + $index }}</strong></td>
                                <td><strong>{{ $aset->kode_barang ?? '-' }}</strong></td>
                                <td>{{ $aset->nama_barang ?? '-' }}</td>
                                <td>{{ $aset->merek ?? '-' }}</td>
                                <td>{{ $aset->kategori ?? '-' }}</td>
                                <td>
                                    @php $kondisi = $aset->kondisi ?? 'baik'; @endphp
                                    <span class="status-badge status-{{ str_replace(' ', '-', $kondisi) }}">
                                        {{ ucwords(str_replace(['rusak ringan', 'rusak berat'], ['rusak ringan', 'rusak berat'], $kondisi)) }}
                                    </span>
                                </td>
                                <td>{{ $aset->lokasi ?? '-' }}</td>
                                <td><strong>{{ $aset->jumlah ?? 0 }}</strong></td>
                                <td>
                                    @php
                                        $status = $aset->status ?? 'Tersedia';
                                        $statusColors = [
                                            'Tersedia' => ['bg' => '#ECFDF5', 'text' => '#10B981', 'icon' => '🟢'],
                                            'Dipinjam' => ['bg' => '#DBEAFE', 'text' => '#3B82F6', 'icon' => '🔵'],
                                            'Keluar' => ['bg' => '#FEF3C7', 'text' => '#F59E0B', 'icon' => '🟡'],
                                            'Rusak' => ['bg' => '#FEF2F2', 'text' => '#EF4444', 'icon' => '🔴'],
                                        ];
                                        $color = $statusColors[$status] ?? $statusColors['Tersedia'];
                                    @endphp
                                    <span class="status-badge" style="background: {{ $color['bg'] }}; color: {{ $color['text'] }};">
                                        {{ $color['icon'] }} {{ $status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="#modal-detail-{{ $aset->id }}" class="action-btn" onclick="openModal('modal-detail-{{ $aset->id }}')" title="Detail">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="#94A3B8">
                                            <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                                        </svg>
                                    </a>
                                    <a href="#modal-edit-{{ $aset->id }}" class="action-btn" onclick="openModal('modal-edit-{{ $aset->id }}')" title="Edit">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="#94A3B8">
                                            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm18-11.5c0-.41-.17-.79-.44-1.06l-2.25-2.25a1.5 1.5 0 0 0-2.12 0l-1.83 1.83 3.75 3.75 1.83-1.83c.27-.27.44-.65.44-1.06z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('adminasettetap.data-aset-tetap.destroy', $aset->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus aset tetap ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn danger" title="Hapus">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="#94A3B8">
                                                <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            {{-- MODAL DETAIL --}}
                            <div id="modal-detail-{{ $aset->id }}" class="modal-overlay">
                                <div class="modal">
                                    <h2 class="modal-title">Detail Aset Tetap</h2>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <div class="form-label">Tanggal Input</div>
                                            <div>{{ $aset->tanggal_input?->format('d F Y') ?? '-' }}</div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-label">Kode Barang</div>
                                            <div><strong>{{ $aset->kode_barang ?? '-' }}</strong></div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <div class="form-label">Nama Barang</div>
                                            <div><strong>{{ $aset->nama_barang ?? '-' }}</strong></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-label">NUP</div>
                                            <div><strong>{{ $aset->nup ?? '-' }}</strong></div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <div class="form-label">Merek</div>
                                            <div>{{ $aset->merek ?? '-' }}</div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-label">Kategori</div>
                                            <div>{{ $aset->kategori ?? '-' }}</div>
                                        </div>
                                    </div>

                                    {{-- INFO DETAIL KENDARAAN --}}
                                    @php
                                        $katDetail = strtolower($aset->kategori ?? '');
                                        $isKendaraan = (str_contains($katDetail, 'kendaraan') || str_contains($katDetail, 'angkutan bermotor'));
                                    @endphp
                                    @if($isKendaraan && $aset->detailKendaraan)
                                    <div class="form-kendaraan" style="display: block;">
                                        <div class="form-kendaraan-title">Spesifikasi Kendaraan</div>
                                        <div class="form-row">
                                            <div class="form-group">
                                                <div class="form-label">Nomor Polisi</div>
                                                <div><strong>{{ $aset->detailKendaraan->nomor_polisi ?? '-' }}</strong></div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-label">Nomor BPKB</div>
                                                <div><strong>{{ $aset->detailKendaraan->no_bpkb ?? '-' }}</strong></div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group">
                                                <div class="form-label">Nomor Rangka</div>
                                                <div><strong>{{ $aset->detailKendaraan->nomor_rangka ?? '-' }}</strong></div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-label">Nomor Mesin</div>
                                                <div><strong>{{ $aset->detailKendaraan->nomor_mesin ?? '-' }}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    <div class="form-row">
                                        <div class="form-group">
                                            <div class="form-label">Tanggal Perolehan</div>
                                            <div>{{ $aset->tanggal_perolehan?->format('d F Y') ?? '-' }}</div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-label">Nilai Perolehan</div>
                                            <div><strong>Rp {{ number_format($aset->nilai_perolehan ?? 0, 0, ',', '.') }}</strong></div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <div class="form-label">Kondisi</div>
                                            <div>
                                                @php $kondisi = $aset->kondisi ?? 'baik'; @endphp
                                                <span class="status-badge status-{{ str_replace(' ', '-', $kondisi) }}">
                                                    {{ ucwords(str_replace(['rusak ringan', 'rusak berat'], ['rusak ringan', 'rusak berat'], $kondisi)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-label">Jumlah / Lokasi</div>
                                            <div><strong>{{ $aset->jumlah ?? 0 }} Unit</strong> ({{ $aset->lokasi ?? '-' }})</div>
                                        </div>
                                    </div>

                                    <div class="btn-group">
                                        <button class="btn btn-secondary" onclick="closeModal('modal-detail-{{ $aset->id }}')">Tutup</button>
                                    </div>
                                </div>
                            </div>

                            {{-- MODAL EDIT --}}
                            <div id="modal-edit-{{ $aset->id }}" class="modal-overlay">
                                <div class="modal">
                                    <h2 class="modal-title">Edit Aset Tetap</h2>
                                    <form action="{{ route('adminasettetap.data-aset-tetap.update', $aset->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="form-row">
                                            <div class="form-group">
                                                <label class="form-label">Tanggal Input <span class="text-red-500">*</span></label>
                                                <input type="date" name="tanggal_input" class="form-input" value="{{ old('tanggal_input', $aset->tanggal_input?->format('Y-m-d')) }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Kode Barang <span class="text-red-500">*</span></label>
                                                <input type="text" name="kode_barang" class="form-input" value="{{ old('kode_barang', $aset->kode_barang) }}" required>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group">
                                                <label class="form-label">NUP <span class="text-red-500">*</span></label>
                                                <input type="text" name="nup" class="form-input" value="{{ old('nup', $aset->nup) }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Nama Barang <span class="text-red-500">*</span></label>
                                                <input type="text" name="nama_barang" class="form-input" value="{{ old('nama_barang', $aset->nama_barang) }}" required>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group">
                                                <label class="form-label">Merek</label>
                                                <input type="text" name="merek" class="form-input" value="{{ old('merek', $aset->merek) }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Kategori <span class="text-red-500">*</span></label>
                                                <input type="text" name="kategori" class="form-input" value="{{ old('kategori', $aset->kategori) }}" required oninput="toggleKendaraanFields(this, 'kendaraan_fields_edit_{{ $aset->id }}')" placeholder="Contoh: ALAT ANGKUTAN BERMOTOR">
                                            </div>
                                        </div>

                                        <!-- AREA DETAIL KENDARAAN -->
                                        @php
                                            $katEdit = strtolower(old('kategori', $aset->kategori ?? ''));
                                            $showEdit = (str_contains($katEdit, 'kendaraan') || str_contains($katEdit, 'angkutan bermotor')) ? 'block' : 'none';
                                        @endphp
                                        <div id="kendaraan_fields_edit_{{ $aset->id }}" class="form-kendaraan" style="display: {{ $showEdit }};">
                                            <div class="form-kendaraan-title">Informasi Detail Kendaraan</div>
                                            <div class="form-row">
                                                <div class="form-group">
                                                    <label class="form-label">Nomor Polisi</label>
                                                    <input type="text" name="nomor_polisi" class="form-input" value="{{ old('nomor_polisi', $aset->detailKendaraan->nomor_polisi ?? '') }}" placeholder="Contoh: DM 1234 A">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Nomor BPKB</label>
                                                    <input type="text" name="no_bpkb" class="form-input" value="{{ old('no_bpkb', $aset->detailKendaraan->no_bpkb ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group">
                                                    <label class="form-label">Nomor Rangka</label>
                                                    <input type="text" name="nomor_rangka" class="form-input" value="{{ old('nomor_rangka', $aset->detailKendaraan->nomor_rangka ?? '') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Nomor Mesin</label>
                                                    <input type="text" name="nomor_mesin" class="form-input" value="{{ old('nomor_mesin', $aset->detailKendaraan->nomor_mesin ?? '') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group">
                                                <label class="form-label">Tanggal Perolehan</label>
                                                <input type="date" name="tanggal_perolehan" class="form-input" value="{{ old('tanggal_perolehan', $aset->tanggal_perolehan?->format('Y-m-d')) }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Kondisi <span class="text-red-500">*</span></label>
                                                <select name="kondisi" class="form-select" required>
                                                    <option value="baik" {{ old('kondisi', $aset->kondisi) == 'baik' ? 'selected' : '' }}>Baik</option>
                                                    <option value="rusak ringan" {{ old('kondisi', $aset->kondisi) == 'rusak ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                                    <option value="rusak berat" {{ old('kondisi', $aset->kondisi) == 'rusak berat' ? 'selected' : '' }}>Rusak Berat</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group">
                                                <label class="form-label">Status <span class="text-red-500">*</span></label>
                                                <select name="status" class="form-select" required>
                                                    <option value="Tersedia" {{ old('status', $aset->status) == 'Tersedia' ? 'selected' : '' }}>🟢 Tersedia</option>
                                                    <option value="Dipinjam" {{ old('status', $aset->status) == 'Dipinjam' ? 'selected' : '' }}>🔵 Dipinjam</option>
                                                    <option value="Keluar" {{ old('status', $aset->status) == 'Keluar' ? 'selected' : '' }}>🟡 Keluar</option>
                                                    <option value="Rusak" {{ old('status', $aset->status) == 'Rusak' ? 'selected' : '' }}>🔴 Rusak</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Nilai Perolehan (Rp)</label>
                                                <input type="number" name="nilai_perolehan" class="form-input" value="{{ old('nilai_perolehan', $aset->nilai_perolehan) }}" step="0.01" min="0">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group">
                                                <label class="form-label">Jumlah <span class="text-red-500">*</span></label>
                                                <input type="number" name="jumlah" class="form-input" value="{{ old('jumlah', $aset->jumlah) }}" min="0" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Lokasi</label>
                                                <input type="text" name="lokasi" class="form-input" value="{{ old('lokasi', $aset->lokasi) }}" placeholder="Ruang Server / Gudang Utama">
                                            </div>
                                        </div>

                                        <div class="btn-group">
                                            <button type="button" class="btn btn-secondary" onclick="closeModal('modal-edit-{{ $aset->id }}')">Batal</button>
                                            <button type="submit" class="btn btn-primary">Update Aset</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-8">
                                    <div class="text-center py-12">
                                        <h3 style="color:var(--muted); text-align: center; width: 100%;">Belum ada data aset tetap yang ditemukan</h3>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="table-footer">
                    @if ($asetTetap->hasPages())
                        <span>Menampilkan {{ $asetTetap->firstItem() }}–{{ $asetTetap->lastItem() }} dari {{ $asetTetap->total() }} data</span>
                    @else
                        <span>Menampilkan {{ $asetTetap->total() }} data</span>
                    @endif
                    {!! $asetTetap->appends(request()->query())->links() !!}
                </div>
            </div>
        </div>
    </main>

    {{-- MODAL IMPORT EXCEL --}}
    <div id="modal-import" class="modal-overlay">
        <div class="modal">
            <h2 class="modal-title">Import Data Excel</h2>
            <form action="{{ route('adminasettetap.data-aset-tetap.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="upload-area">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="#94A3B8" style="margin-bottom: 12px;">
                        <path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM14 13v4h-4v-4H7l5-5 5 5h-3z" />
                    </svg>
                    <p style="font-size: 14px; font-weight: 600; color: var(--text); margin-bottom: 8px;">Pilih file Excel untuk diupload</p>
                    <p style="font-size: 12px; color: var(--muted); margin-bottom: 16px;">Format yang didukung: .xlsx, .xls</p>
                    <input type="file" name="file_excel" accept=".xlsx, .xls" required style="font-size: 13px; max-width: 100%;">
                </div>
                <div style="margin-bottom: 20px;">
                    <a href="{{ route('adminasettetap.data-aset-tetap.template') }}" style="font-size: 13px; color: var(--blue); text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z" />
                        </svg>
                        Download Template Excel
                    </a>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('modal-import')">Batal</button>
                    <button type="submit" class="btn btn-primary">Upload & Import</button>
                </div>
            </form>
        </div>
    </div>
    
    {{-- MODAL TAMBAH --}}
    <div id="modal-tambah" class="modal-overlay">
        <div class="modal">
            <h2 class="modal-title">Tambah Aset Tetap</h2>
            <form action="{{ route('adminasettetap.data-aset-tetap.store') }}" method="POST">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tanggal Input <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_input" class="form-input" value="{{ old('tanggal_input') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kode Barang <span class="text-red-500">*</span></label>
                        <input type="text" name="kode_barang" class="form-input" value="{{ old('kode_barang') }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">NUP <span class="text-red-500">*</span></label>
                        <input type="text" name="nup" class="form-input" value="{{ old('nup') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Barang <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_barang" class="form-input" value="{{ old('nama_barang') }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Merek</label>
                        <input type="text" name="merek" class="form-input" value="{{ old('merek') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kategori <span class="text-red-500">*</span></label>
                        <input type="text" name="kategori" class="form-input" value="{{ old('kategori') }}" required oninput="toggleKendaraanFields(this, 'kendaraan_fields_tambah')" placeholder="Contoh: ALAT ANGKUTAN BERMOTOR">
                    </div>
                </div>

                <!-- PERBAIKAN 2: AREA DETAIL KENDARAAN (Menghapus pemanggilan variabel $aset karena ini form Tambah) -->
                @php
                    $katTambah = strtolower(old('kategori', ''));
                    $showTambah = (str_contains($katTambah, 'kendaraan') || str_contains($katTambah, 'angkutan bermotor')) ? 'block' : 'none';
                @endphp

                <div id="kendaraan_fields_tambah" class="form-kendaraan" style="display: {{ $showTambah }};">
                    <div class="form-kendaraan-title">Informasi Detail Kendaraan</div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Nomor Polisi</label>
                            <input type="text" name="nomor_polisi" class="form-input" value="{{ old('nomor_polisi') }}" placeholder="Contoh: DM 1234 A">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nomor BPKB</label>
                            <input type="text" name="no_bpkb" class="form-input" value="{{ old('no_bpkb') }}" placeholder="Contoh: BPKB-12345">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Nomor Rangka</label>
                            <input type="text" name="nomor_rangka" class="form-input" value="{{ old('nomor_rangka') }}" placeholder="Nomor Rangka">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nomor Mesin</label>
                            <input type="text" name="nomor_mesin" class="form-input" value="{{ old('nomor_mesin') }}" placeholder="Nomor Mesin">
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tanggal Perolehan</label>
                        <input type="date" name="tanggal_perolehan" class="form-input" value="{{ old('tanggal_perolehan') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kondisi <span class="text-red-500">*</span></label>
                        <select name="kondisi" class="form-select" required>
                            <option value="baik" {{ old('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="rusak ringan" {{ old('kondisi') == 'rusak ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="rusak berat" {{ old('kondisi') == 'rusak berat' ? 'selected' : '' }}>Rusak Berat</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Status <span class="text-red-500">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="Tersedia" {{ old('status') == 'Tersedia' ? 'selected' : '' }}>🟢 Tersedia</option>
                            <option value="Dipinjam" {{ old('status') == 'Dipinjam' ? 'selected' : '' }}>🔵 Dipinjam</option>
                            <option value="Keluar" {{ old('status') == 'Keluar' ? 'selected' : '' }}>🟡 Keluar</option>
                            <option value="Rusak" {{ old('status') == 'Rusak' ? 'selected' : '' }}>🔴 Rusak</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nilai Perolehan (Rp)</label>
                        <input type="number" name="nilai_perolehan" class="form-input" value="{{ old('nilai_perolehan') }}" step="0.01" min="0">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Jumlah <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah" class="form-input" value="{{ old('jumlah') }}" min="0" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Lokasi</label>
                        <input type="text" name="lokasi" class="form-input" value="{{ old('lokasi') }}" placeholder="Ruang Server / Gudang Utama">
                    </div>
                </div>

                <div class="btn-group">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('modal-tambah')">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Aset</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        // FUNGSI JS UNTUK SHOW/HIDE FORM KENDARAAN BERDASARKAN KATEGORI YANG DIKETIK
        function toggleKendaraanFields(inputElement, containerId) {
            var container = document.getElementById(containerId);
            var val = inputElement.value.toLowerCase().trim();
            
            // Logika baru: jika teks mengandung kata 'kendaraan' atau 'angkutan bermotor'
            if(val.includes('kendaraan') || val.includes('angkutan bermotor')) {
                container.style.display = 'block';
            } else {
                container.style.display = 'none';
            }
        }

        document.querySelectorAll('.modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) {
                    overlay.classList.remove('show');
                    document.body.style.overflow = 'auto';
                }
            });
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal-overlay.show').forEach(overlay => {
                    overlay.classList.remove('show');
                    document.body.style.overflow = 'auto';
                });
            }
        });
    </script>
</body>
</html>