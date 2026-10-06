<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIPANDU - Transaksi Keluar</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); display: flex; min-height: 100vh; }

        /* SIDEBAR */
        .sidebar {
            width: var(--sidebar-w); background: var(--surface); border-right: 1px solid var(--border);
            display: flex; flex-direction: column; position: fixed; top: 0; left: 0; bottom: 0; z-index: 100;
        }
        .sidebar-logo { display: flex; align-items: center; gap: 10px; padding: 20px 20px 16px; border-bottom: 1px solid var(--border); }
        .logo-icon {
            width: 38px; height: 38px; border-radius: 10px; background: linear-gradient(135deg, var(--blue), #7C3AED);
            display: flex; align-items: center; justify-content: center;
        }
        .logo-icon svg { width: 20px; height: 20px; fill: white; }
        .logo-text strong { font-size: 15px; font-weight: 800; color: var(--text); display: block; }
        .logo-text span { font-size: 10px; color: var(--muted); font-weight: 600; letter-spacing: 1px; }
        .sidebar-section { padding: 16px 16px 4px; }
        .sidebar-label { font-size: 10px; font-weight: 700; color: var(--muted); letter-spacing: 1.5px; text-transform: uppercase; padding: 0 4px 8px; }
        .role-badge {
            background: linear-gradient(135deg, #EEF2FF, #E0E7FF); border: 1px solid #C7D2FE;
            border-radius: 10px; padding: 8px 12px; font-size: 12px; font-weight: 700; color: var(--blue); margin: 0 16px 16px;
        }
        .nav { flex: 1; overflow-y: auto; padding: 4px 12px; }
        .nav-item {
            display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 10px;
            font-size: 13.5px; font-weight: 500; color: #64748B; cursor: pointer; transition: all .15s; margin-bottom: 2px; text-decoration: none;
        }
        .nav-item:hover { background: var(--bg); color: var(--text); }
        .nav-item.active { background: linear-gradient(135deg, #EEF2FF, #E0E7FF); color: var(--blue); font-weight: 700; }
        .nav-item svg { width: 17px; height: 17px; flex-shrink: 0; }
        .nav-item .chevron { margin-left: auto; width: 14px; height: 14px; }
        .sidebar-footer { border-top: 1px solid var(--border); padding: 14px 16px; }
        .user-info { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
        .user-avatar {
            width: 34px; height: 34px; border-radius: 50%; background: linear-gradient(135deg, var(--blue), #7C3AED);
            display: flex; align-items: center; justify-content: center; color: white; font-size: 13px; font-weight: 700;
        }
        .user-detail strong { font-size: 13px; font-weight: 700; display: block; }
        .user-detail span { font-size: 11px; color: var(--muted); }
        .btn-logout {
            width: 100%; display: flex; align-items: center; justify-content: center; gap: 6px; padding: 8px;
            border-radius: 8px; border: 1px solid var(--border); background: transparent; color: #64748B;
            font-size: 13px; font-weight: 600; font-family: inherit; cursor: pointer; transition: all .15s;
        }
        .btn-logout:hover { background: #FEF2F2; color: #EF4444; border-color: #FECACA; }

        /* MAIN CORES */
        .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
        .topbar {
            background: var(--surface); border-bottom: 1px solid var(--border); padding: 0 28px; height: 56px;
            display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 50;
        }
        .topbar-title { font-size: 16px; font-weight: 700; }
        .topbar-right { display: flex; align-items: center; gap: 16px; }
        .notif-btn {
            width: 36px; height: 36px; border-radius: 50%; border: 1px solid var(--border); background: var(--surface);
            display: flex; align-items: center; justify-content: center; cursor: pointer; position: relative;
        }
        .notif-dot { width: 8px; height: 8px; background: #EF4444; border-radius: 50%; position: absolute; top: 6px; right: 6px; border: 2px solid white; }
        .date-text { font-size: 13px; color: #64748B; font-weight: 500; }
        .btn-keluar {
            display: flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 8px; border: 1px solid var(--border);
            background: var(--surface); color: #64748B; font-size: 13px; font-weight: 600; font-family: inherit; cursor: pointer; transition: all .15s;
        }
        .btn-keluar:hover { background: #FEF2F2; color: #EF4444; }
        .content { padding: 28px; flex: 1; }

        /* PAGE HEADERS */
        .page-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 20px; }
        .page-top h1 { font-size: 22px; font-weight: 800; color: var(--blue); margin-bottom: 4px; }
        .page-top p { font-size: 13px; color: var(--muted); }
        .btn-tambah {
            display: flex; align-items: center; gap: 7px; padding: 10px 18px; border-radius: 10px;
            background: linear-gradient(135deg, var(--blue), #7C3AED); color: white; font-size: 13.5px; font-weight: 700;
            font-family: inherit; border: none; cursor: pointer; box-shadow: 0 4px 14px rgba(79,111,255,.35); transition: all .2s;
        }
        .btn-tambah:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(79,111,255,.45); }

        /* TABLE STRUCT */
        .table-card { background: var(--surface); border-radius: var(--radius); border: 1px solid var(--border); overflow: hidden; }
        .table-toolbar { display: flex; align-items: center; gap: 12px; padding: 16px 20px; border-bottom: 1px solid var(--border); }
        .search-wrap {
            flex: 1; display: flex; align-items: center; gap: 8px; border: 1.5px solid var(--border);
            border-radius: 10px; padding: 8px 14px; background: var(--bg); transition: border-color .15s;
        }
        .search-wrap:focus-within { border-color: var(--blue); }
        .search-wrap input { border: none; background: none; outline: none; font-family: inherit; font-size: 13.5px; color: var(--text); width: 100%; }
        .search-wrap input::placeholder { color: var(--muted); }
        .btn-filter {
            display: flex; align-items: center; gap: 6px; padding: 8px 14px; border-radius: 10px; border: 1.5px solid var(--border);
            background: var(--bg); font-family: inherit; font-size: 13px; color: #64748B; cursor: pointer; transition: all .15s; text-decoration: none;
        }
        .btn-filter:hover { border-color: var(--blue); color: var(--blue); }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #F8FAFF; }
        th { padding: 12px 20px; text-align: left; font-size: 11px; font-weight: 700; color: var(--blue); letter-spacing: .8px; text-transform: uppercase; border-bottom: 1px solid var(--border); }
        td { padding: 14px 20px; font-size: 13.5px; color: var(--text); border-bottom: 1px solid var(--border); }
        tr:last-child td { border-bottom: none; }
        tbody tr { transition: background .15s; }
        tbody tr:hover { background: #F8FAFF; }
        td:last-child { white-space: nowrap; }

        .action-btn {
            display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px;
            border-radius: 7px; border: 1.5px solid var(--border); background: var(--surface); color: #64748B;
            cursor: pointer; transition: all .15s; margin-left: 4px;
        }
        .action-btn:first-child { margin-left: 0; }
        .action-btn:hover { background: #EEF2FF; border-color: var(--blue); color: var(--blue); }
        .action-btn.danger:hover { background: #FEF2F2; border-color: #EF4444; color: #EF4444; }
        .action-btn.edit:hover { background: #F0FDF4; border-color: #22C55E; color: #22C55E; }
        .table-footer { display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; border-top: 1px solid var(--border); font-size: 13px; color: var(--muted); }

        /* MODAL CUSTOM STYLES */
        .modal-dialog { max-height: calc(100vh - 40px) !important; display: flex !important; flex-direction: column !important; margin: 20px auto !important; }
        .modal-content {
            border: none !important; border-radius: 20px !important; overflow: hidden !important;
            box-shadow: 0 25px 60px rgba(0,0,0,.18), 0 8px 24px rgba(79,111,255,.08) !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important; display: flex !important; flex-direction: column !important; max-height: calc(100vh - 40px) !important;
        }
        .modal-header { flex: 0 0 auto !important; border-bottom: 1px solid var(--border) !important; padding: 20px 26px 16px !important; }
        .modal-body { flex: 1 1 auto !important; overflow-y: auto !important; min-height: 0 !important; padding: 22px 26px !important; background: var(--surface) !important; }
        .modal-body::-webkit-scrollbar { width: 5px; }
        .modal-body::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }
        .modal-footer { flex: 0 0 auto !important; display: flex !important; border-top: 1px solid var(--border) !important; padding: 14px 26px !important; background: #FAFBFF !important; }
        
        .modal .form-label { font-size: 12px !important; font-weight: 700 !important; color: var(--text) !important; margin-bottom: 5px !important; }
        .modal .form-control, .modal .form-select {
            font-family: 'Plus Jakarta Sans', sans-serif !important; font-size: 13.5px !important; border: 1.5px solid var(--border) !important;
            border-radius: 10px !important; padding: 9px 13px !important; color: var(--text) !important; background: var(--bg) !important;
        }
        .modal .form-control:focus, .modal .form-select:focus { border-color: var(--blue) !important; background: var(--surface) !important; outline: none; box-shadow: 0 0 0 3px rgba(79,111,255,.1) !important; }
        .modal .form-control[readonly] { background: #F1F5F9 !important; color: var(--muted) !important; cursor: not-allowed !important; }

        /* Custom Styling Select2 */
        .select2-container .select2-selection--single {
            height: 42px !important;
            border: 1.5px solid var(--border) !important;
            border-radius: 10px !important;
            background: var(--bg) !important;
            display: flex;
            align-items: center;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
            right: 10px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: var(--text) !important;
            font-size: 13.5px !important;
            font-family: 'Plus Jakarta Sans', sans-serif;
            padding-left: 13px !important;
        }
        .select2-container--open {
            z-index: 9999 !important; 
        }

        /* GRID COMPONENTS */
        .detail-section { margin-bottom: 22px; }
        .detail-section-title { font-size: 10.5px; font-weight: 700; color: var(--blue); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1.5px solid var(--border); }
        .detail-section-title span { background: linear-gradient(135deg, #EEF2FF, #E0E7FF); border-radius: 4px; padding: 2px 8px; }
        .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .detail-item label { font-size: 11px; color: var(--muted); font-weight: 600; display: block; margin-bottom: 4px; }
        .detail-item span { font-size: 13.5px; color: var(--text); font-weight: 600; display: block; padding: 8px 12px; background: var(--bg); border-radius: 8px; border: 1px solid var(--border); }
        .detail-item.full { grid-column: 1 / -1; }

        .btn-secondary-modal { display: flex; align-items: center; gap: 6px; padding: 9px 18px; border-radius: 10px; border: 1.5px solid var(--border); background: var(--surface); color: #64748B; font-weight: 600; font-size: 13.5px; cursor: pointer; }
        .btn-danger-modal { display: flex; align-items: center; gap: 7px; padding: 9px 20px; border-radius: 10px; border: none; background: linear-gradient(135deg, #EF4444, #DC2626); color: white; font-weight: 700; font-size: 13.5px; cursor: pointer; box-shadow: 0 4px 12px rgba(239,68,68,.35); }
        .modal-section-label { font-size: 10.5px; font-weight: 700; color: var(--blue); letter-spacing: 1.2px; text-transform: uppercase; padding-bottom: 8px; border-bottom: 1.5px solid var(--border); margin-bottom: 14px; margin-top: 20px; }
        .modal-section-label:first-child { margin-top: 0; }
        .modal-section-label span { background: linear-gradient(135deg, #EEF2FF, #E0E7FF); border-radius: 4px; padding: 2px 8px; }
        .modal-section-label.red span { background: linear-gradient(135deg, #FFF1F1, #FFE4E4); color: #DC2626; }
        .modal-info-note { display: flex; align-items: flex-start; gap: 8px; padding: 10px 13px; border-radius: 10px; background: #EEF2FF; border: 1px solid #C7D2FE; margin-bottom: 18px; font-size: 12px; color: #4F46E5; font-weight: 600; }
        .modal-info-note svg { width: 14px; height: 14px; fill: #4F46E5; flex-shrink: 0; margin-top: 1px; }

        .input-prefix-wrap { display: flex; align-items: center; border: 1.5px solid var(--border); border-radius: 10px; overflow: hidden; background: #F1F5F9; }
        .input-prefix-wrap .prefix { padding: 9px 12px; font-size: 12px; font-weight: 700; color: var(--muted); border-right: 1.5px solid var(--border); background: #E9EEF5; white-space: nowrap; }
        .input-prefix-wrap input { flex: 1; border: none; background: transparent; padding: 9px 13px; font-size: 13.5px; color: var(--muted); outline: none; cursor: not-allowed; }

        @media (max-width: 768px) { .main { margin-left: 0; } .sidebar { transform: translateX(-100%); } .page-top { flex-direction: column; gap: 16px; } }
    </style>
</head>
<body>

@include('partials.sidebar')

<main class="main">
    <div class="topbar">
        <span class="topbar-title">Transaksi Keluar Aset Tetap</span>
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
        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show position-fixed" style="top: 80px; right: 20px; z-index: 9999; min-width: 300px;">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="page-top">
            <div>
                <h1>Transaksi Keluar Aset Tetap</h1>
                <p>{{ $transaksi->total() }} data ditemukan</p>
            </div>
            <button class="btn-tambah" onclick="openCreateModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                Tambah Baru
            </button>
        </div>

        {{-- Table Card --}}
        <div class="table-card">
            <div class="table-toolbar">
                <form method="GET" action="{{ route('adminasettetap.transaksi-keluar') }}" style="display:flex; align-items:center; gap:12px; width:100%;">
                    <div class="search-wrap">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="#94A3B8"><path d="M15.5 14h-.79l-.28-.27A6.47 6.47 0 0016 9.5 6.5 6.5 0 109.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                        <input type="text" name="search" placeholder="Cari kode barang, NUP, nama barang..." value="{{ request('search') }}">
                    </div>
                    <button type="submit" class="btn-filter">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M10 18h4v-2h-4v2zM3 6v2h18V6H3zm3 7h12v-2H6v2z"/></svg> Filter
                    </button>
                    @if(request('search'))
                        <a href="{{ route('adminasettetap.transaksi-keluar') }}" class="btn-filter" style="color:#EF4444; border-color:#FECACA;"><i class="fas fa-times"></i> Reset</a>
                    @endif
                </form>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Input</th>
                            <th>Kode Barang</th>
                            <th>NUP</th>
                            <th>Nama Barang</th>
                            <th>Merek</th>
                            <th>Tgl. Perolehan</th>
                            <th>Nilai Perolehan</th>
                            <th>Lokasi</th>
                            <th>Nomor SK</th>
                            <th>Tanggal SK</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksi as $index => $item)
                        <tr>
                            <td><strong>{{ $transaksi->firstItem() + $index }}</strong></td>
                            <td>{{ $item->tanggal_input_format }}</td>
                            <td><strong>{{ $item->kode_barang }}</strong></td>
                            <td><strong>{{ $item->nup }}</strong></td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>{{ $item->merek ?? '-' }}</td>
                            <td>{{ $item->tanggal_perolehan_format }}</td>
                            <td>{{ $item->nilai_format }}</td>
                            <td>{{ $item->lokasi ?? '-' }}</td>
                            <td>{{ $item->nomor_sk ?? '-' }}</td>
                            <td>{{ $item->tanggal_sk_format }}</td>
                            <td title="{{ $item->keterangan ?? '-' }}">{{ Str::limit($item->keterangan ?? '-', 20) }}</td>
                            <td>
                                <button class="action-btn" onclick="openDetailModal({{ $item->id }})" title="Detail"><i class="fas fa-eye" style="font-size:12px;"></i></button>
                                <button class="action-btn edit" onclick="openEditModal({{ $item->id }})" title="Edit"><i class="fas fa-edit" style="font-size:12px;"></i></button>
                                <button class="action-btn danger" onclick="deleteItem({{ $item->id }})" title="Hapus"><i class="fas fa-trash" style="font-size:12px;"></i></button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="13" style="text-align:center; padding: 48px 20px;">
                                <i class="fas fa-inbox" style="font-size:36px; color:var(--muted); display:block; margin-bottom:12px;"></i>
                                <strong style="color:var(--muted); font-size:14px;">Tidak ada data</strong>
                                <p style="color:var(--muted); font-size:13px; margin-top:4px;">Belum ada catatan transaksi keluar</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($transaksi->hasPages())
            <div class="table-footer">
                <span>Menampilkan {{ $transaksi->firstItem() }}–{{ $transaksi->lastItem() }} dari {{ $transaksi->total() }} data</span>
                <div class="pagination">{!! $transaksi->appends(request()->query())->links() !!}</div>
            </div>
            @endif
        </div>
    </div>
</main>

{{-- ================================================================
     MODAL CRUD
     ================================================================ --}}
<div class="modal fade" id="crudModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        
        <form id="crudForm" method="POST" class="w-100">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <input type="hidden" name="id" id="modalId">

            <div class="modal-content">
                {{-- HEADER MODAL --}}
                <div class="modal-header" style="background:linear-gradient(135deg,#FFF8F8,#FEE2E2) !important;">
                    <div style="display:flex; align-items:center; justify-content:space-between; width:100%;">
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div style="width:40px; height:40px; border-radius:12px; background:linear-gradient(135deg,#EF4444,#DC2626); display:flex; align-items:center; justify-content:center; box-shadow:0 4px 12px rgba(239,68,68,.35);">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="white"><path d="M20 6h-2.18c.07-.44.18-.86.18-1a3 3 0 0 0-6 0c0 .14.11.56.18 1H10v2h10V6zm-7-1c0-.55.45-1 1-1s1 .45 1 1-.45 1-1 1-1-.45-1-1zM4 8v14h16v-2H6V8H4zm4 6h8v2H8v-2zm0-4h8v2H8v-2z"/></svg>
                            </div>
                            <div>
                                <div id="modalTitle" style="font-size:16px; font-weight:800; color:var(--text);">Tambah Transaksi Keluar</div>
                                <div style="font-size:12px; color:var(--muted); margin-top: 2px;">Form mutasi keluar/penghapusan BMN aset tetap</div>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="border:1.5px solid var(--border); border-radius:8px; width:30px; height:30px;"></button>
                    </div>
                </div>

                {{-- BODY MODAL --}}
                <div class="modal-body">
                    <div class="modal-info-note">
                        <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                        Pilih aset tetap terlebih dahulu — rincian BMN akan dimuat otomatis oleh sistem.
                    </div>

                    {{-- Seksi 1: Pilih Komponen --}}
                    <div class="modal-section-label" style="margin-top:0;"><span>Pilih Komponen Aset</span></div>
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Aset Tetap <span class="text-danger">*</span></label>
                            <select name="aset_tetap_id" id="aset_tetap_id" class="form-select" required>
                                <option value="">-- Pilih Aset Tetap --</option>
                                @foreach($asetTetapOptions as $aset)
                                    <option value="{{ $aset->id }}">
                                        {{ $aset->kode_barang }} - {{ $aset->nama_barang }} (NUP: {{ $aset->nup }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Keluar <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_input" id="tanggal_input" class="form-control" required>
                        </div>
                    </div>

                    {{-- Seksi 2: Read-Only Data --}}
                    <div class="modal-section-label"><span>Data Pelengkap Spasial <span class="text-muted" style="text-transform:none; font-weight:400;">(Read-only)</span></span></div>
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Kode Barang</label><input type="text" id="kode_barang" class="form-control" readonly></div>
                        <div class="col-md-6"><label class="form-label">NUP</label><input type="text" id="nup" class="form-control" readonly></div>
                        <div class="col-md-6"><label class="form-label">Nama Barang</label><input type="text" id="nama_barang" class="form-control" readonly></div>
                        <div class="col-md-6"><label class="form-label">Merek</label><input type="text" id="merek" class="form-control" readonly></div>
                        <div class="col-md-6"><label class="form-label">Tanggal Perolehan</label><input type="date" id="tanggal_perolehan" class="form-control" readonly></div>
                        <div class="col-md-6">
                            <label class="form-label">Nilai Perolehan</label>
                            <div class="input-prefix-wrap"><span class="prefix">Rp</span><input type="number" id="nilai_perolehan" readonly></div>
                        </div>
                        <div class="col-12"><label class="form-label">Lokasi Ruangan Terakhir</label><input type="text" id="lokasi" class="form-control" readonly></div>
                    </div>

                    {{-- Seksi 3: Legalitas SK --}}
                    <div class="modal-section-label red"><span>Legalitas Dokumen SK</span></div>
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Nomor SK Penghapusan</label><input type="text" name="nomor_sk" id="nomor_sk" class="form-control" placeholder="SK-001/BPMP/2026"></div>
                        <div class="col-md-6"><label class="form-label">Tanggal SK Resmi</label><input type="date" name="tanggal_sk" id="tanggal_sk" class="form-control"></div>
                        <div class="col-12"><label class="form-label">Keterangan Tambahan Alasan Keluar</label><textarea name="keterangan" id="keterangan" rows="3" class="form-control" placeholder="Ketik alasan penghapusan aset atau tujuan mutasi keluar..."></textarea></div>
                    </div>
                </div>

                {{-- FOOTER MODAL --}}
                <div class="modal-footer" style="align-items:center; justify-content:space-between;">
                    <div style="font-size:12px; color:var(--muted);"><span class="text-danger">*</span> Parameter field wajib dimasukkan.</div>
                    <div style="display:flex; gap:10px;">
                        <button type="button" class="btn-secondary-modal" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" id="submitBtn" class="btn-danger-modal">
                            <span class="spinner-border spinner-border-sm d-none" id="loadingSpinner" style="width:14px;height:14px;"></span>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="white"><path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/></svg>
                            <span id="submitText">Simpan Transaksi</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>

{{-- ================================================================
     MODAL DETAIL VIEW
     ================================================================ --}}
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#F8FAFF,#EEF2FF) !important;">
                <div style="display:flex; align-items:center; justify-content:space-between; width:100%;">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div style="width:40px; height:40px; border-radius:12px; background:linear-gradient(135deg,var(--blue),#7C3AED); display:flex; align-items:center; justify-content:center; box-shadow:0 4px 12px rgba(79,111,255,.35);">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="white"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                        </div>
                        <div>
                            <div style="font-size:16px; font-weight:800; color:var(--text);">Detail Dokumen Mutasi Keluar</div>
                            <div style="font-size:12px; color:var(--muted);">Rincian data legalitas dan spesifikasi fisik BMN</div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" style="border:1.5px solid var(--border); border-radius:8px; width:30px; height:30px;"></button>
                </div>
            </div>
            <div class="modal-body" id="detailContent"></div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary-modal" data-bs-dismiss="modal">Tutup Dokumen</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
let crudModalInstance = null;
let detailModalInstance = null;

function initModals() {
    if (typeof bootstrap !== 'undefined') {
        if (!crudModalInstance) crudModalInstance = new bootstrap.Modal(document.getElementById('crudModal'));
        if (!detailModalInstance) detailModalInstance = new bootstrap.Modal(document.getElementById('detailModal'));
        return true;
    } else {
        alert('⚠️ Library Bootstrap gagal dimuat oleh browser. Pastikan koneksi internet aktif.');
        return false;
    }
}

// ✅ ACTION OPEN CREATE MODAL
window.openCreateModal = function() {
    if (!initModals()) return;

    const form = document.getElementById('crudForm');
    form.reset();
    document.getElementById('modalTitle').textContent = 'Tambah Catatan Transaksi Keluar';
    form.action = "{{ route('adminasettetap.transaksi-keluar.store') }}";
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('modalId').value = '';
    document.getElementById('submitText').textContent = 'Simpan Transaksi';
    document.getElementById('tanggal_input').value = new Date().toISOString().split('T')[0];
    clearFields();
    
    // Reset Select2 saat buka modal Create
    $('#aset_tetap_id').val(null).trigger('change.select2');
    
    crudModalInstance.show();
};

window.openEditModal = function(id) {
    if (!initModals()) return;

    document.getElementById('modalTitle').textContent = 'Edit Data Transaksi Keluar';
    document.getElementById('submitText').textContent = 'Perbarui Transaksi';
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('crudForm').action = `/adminasettetap/transaksi-keluar/${id}`;
    document.getElementById('modalId').value = id;

    fetch(`/adminasettetap/transaksi-keluar/${id}/edit-json`, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.aset_tetap_id) {
            // Set Select2 ke data aset_tetap_id yang sudah ada
            $('#aset_tetap_id').val(data.aset_tetap_id).trigger('change.select2');
            
            showLoading(true);
            
            fetch(`/adminasettetap/aset-tetap/${data.aset_tetap_id}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(asetData => {
                fillFields(asetData);
                showLoading(false);
                
                document.getElementById('tanggal_input').value = data.tanggal_input || '';
                document.getElementById('nomor_sk').value = data.nomor_sk || '';
                document.getElementById('tanggal_sk').value = data.tanggal_sk || '';
                document.getElementById('keterangan').value = data.keterangan || '';
                
                crudModalInstance.show();
            })
            .catch(() => {
                showLoading(false);
                alert('Gagal menyinkronkan data spesifikasi aset tetap.');
            });
        }
    })
    .catch(() => alert('Sistem gagal memproses data JSON transaksi.'));
};

window.openDetailModal = function(id) {
    if (!initModals()) return;

    document.getElementById('detailContent').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2 text-muted">Mengunduh berkas...</p>
        </div>`;
    
    detailModalInstance.show();

    fetch(`/adminasettetap/transaksi-keluar/${id}/show-aset`, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.error) {
            document.getElementById('detailContent').innerHTML = `<div class="text-center py-4 text-danger"><i class="fas fa-exclamation-circle fa-2x mb-2 d-block"></i>${data.error}</div>`;
            return;
        }
        document.getElementById('detailContent').innerHTML = `
            <div class="detail-section">
                <div class="detail-section-title"><span>Spesifikasi Teknis Aset</span></div>
                <div class="detail-grid">
                    <div class="detail-item"><label>Kode Barang</label><span>${data.kode_barang}</span></div>
                    <div class="detail-item"><label>NUP (Nomor Urut Pendaftaran)</label><span>${data.nup}</span></div>
                    <div class="detail-item"><label>Nama Barang</label><span>${data.nama_barang}</span></div>
                    <div class="detail-item"><label>Merek/Type</label><span>${data.merek}</span></div>
                    <div class="detail-item"><label>Tanggal Perolehan Awal</label><span>${data.tanggal_perolehan_format}</span></div>
                    <div class="detail-item"><label>Nilai Perolehan Awal</label><span>${data.nilai_format}</span></div>
                    <div class="detail-item full"><label>Lokasi Penempatan Ruangan</label><span>${data.lokasi}</span></div>
                </div>
            </div>
            <div class="detail-section">
                <div class="detail-section-title"><span style="background:linear-gradient(135deg,#FFF1F1,#FFE4E4);color:#DC2626;">Atribut Dokumen Keluar</span></div>
                <div class="detail-grid">
                    <div class="detail-item"><label>Tanggal Input Mutasi</label><span>${data.tanggal_input_format}</span></div>
                    <div class="detail-item"><label>Nomor Surat Keputusan (SK)</label><span>${data.nomor_sk}</span></div>
                    <div class="detail-item"><label>Tanggal SK Pengesahan</label><span>${data.tanggal_sk_format}</span></div>
                    <div class="detail-item full"><label>Keterangan Alasan</label><span>${data.keterangan}</span></div>
                </div>
            </div>`;
    })
    .catch(() => {
        document.getElementById('detailContent').innerHTML = `<div class="text-center py-4 text-danger"><i class="fas fa-exclamation-circle fa-2x mb-2 d-block"></i>Gagal memuat data dari server.</div>`;
    });
};

function fetchAsetData(id) {
    showLoading(true);
    fetch(`/adminasettetap/aset-tetap/${id}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Data fetch failed');
        return response.json();
    })
    .then(data => {
        if (data.error) {
            alert('Informasi: ' + data.error);
            clearFields();
        } else {
            fillFields(data);
        }
        showLoading(false);
    })
    .catch(() => {
        alert('Koneksi bermasalah saat memuat data pelengkap aset.');
        clearFields();
        showLoading(false);
    });
}

function fillFields(data) {
    document.getElementById('kode_barang').value = data.kode_barang || '';
    document.getElementById('nup').value = data.nup || '';
    document.getElementById('nama_barang').value = data.nama_barang || '';
    document.getElementById('merek').value = data.merek || '';
    document.getElementById('tanggal_perolehan').value = data.tanggal_perolehan || '';
    document.getElementById('nilai_perolehan').value = data.nilai_perolehan || '';
    document.getElementById('lokasi').value = data.lokasi || '';
}

function clearFields() {
    const fields = ['kode_barang', 'nup', 'nama_barang', 'merek', 'tanggal_perolehan', 'nilai_perolehan', 'lokasi'];
    fields.forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });
}

function showLoading(show) {
    const fields = document.querySelectorAll('.form-control[readonly]');
    fields.forEach(field => {
        field.style.background = show ? '#e2e8f0' : '';
        field.placeholder = show ? 'Sinkronisasi data...' : '';
    });
}

// Inisialisasi Select2 dan Event Listeners saat DOM siap
document.addEventListener('DOMContentLoaded', function() {
    initModals();

    // Inisialisasi Select2 di elemen dropdown aset tetap
    if ($('#aset_tetap_id').length) {
        $('#aset_tetap_id').select2({
            dropdownParent: $('#crudModal'), // Agar dropdown muncul di atas Bootstrap Modal
            placeholder: "-- Pilih Aset Tetap --",
            width: '100%'
        });

        // Trigger pencarian data aset melalui JQuery Change (menggantikan eventListener lama)
        $('#aset_tetap_id').on('change', function() {
            if (!this.value) {
                clearFields();
                return;
            }
            fetchAsetData(this.value);
        });
    }

    const crudForm = document.getElementById('crudForm');
    if (crudForm) {
        crudForm.addEventListener('submit', function() {
            const submitBtn = document.getElementById('submitBtn');
            const spinner = document.getElementById('loadingSpinner');
            if (submitBtn && spinner) {
                submitBtn.disabled = true;
                spinner.classList.remove('d-none');
            }
        });
    }
});

// ✅ ACTION DESTROY/DELETE MUTASI
window.deleteItem = function(id) {
    if (!confirm('⚠️ Anda yakin akan menghapus permanen data transaksi keluar ini?\n\nStatus barang akan dikembalikan utuh menjadi "Tersedia" pada modul gudang.')) {
        return;
    }
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/adminasettetap/transaksi-keluar/${id}`;
    form.style.display = 'none';
    
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';
    
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
    
    form.appendChild(methodInput);
    form.appendChild(csrfInput);
    document.body.appendChild(form);
    form.submit();
};
</script>
</body>
</html>
