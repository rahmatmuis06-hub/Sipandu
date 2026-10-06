<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIPANDU - Ajuan Mutasi</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">

<style>
:root{--blue:#4F6FFF;--blue-light:#EEF2FF;--blue-lighter:#F0F4FF;--green:#10B981;--green-light:#D1FAE5;--red:#EF4444;--red-light:#FEE2E2;--yellow:#F59E0B;--yellow-light:#FEF3C7;--gray-50:#F9FAFB;--gray-100:#F3F4F6;--gray-200:#E5E7EB;--gray-300:#D1D5DB;--gray-400:#9CA3AF;--gray-500:#6B7280;--gray-600:#4B5563;--gray-700:#374151;--gray-900:#1F2937;--sidebar-w:240px;--radius:16px;--radius-sm:8px;--radius-xs:6px;--bg:#F4F6FB;--surface:#FFFFFF;--text:#1E293B;--muted:#94A3B8;--border:#E8EDF5;--shadow-xs:0 1px 2px 0 rgba(0,0,0,0.05);--shadow-sm:0 1px 3px 0 rgba(0,0,0,0.1);--shadow-md:0 4px 6px -1px rgba(0,0,0,0.1);--shadow-lg:0 10px 15px -3px rgba(0,0,0,0.1)}*{margin:0;padding:0;box-sizing:border-box}body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--bg);color:var(--text);display:flex;min-height:100vh}.sidebar{width:var(--sidebar-w);background:var(--surface);border-right:1px solid var(--border);display:flex;flex-direction:column;position:fixed;top:0;left:0;bottom:0;z-index:100}.sidebar-logo{display:flex;align-items:center;gap:10px;padding:20px 20px 16px;border-bottom:1px solid var(--border)}.logo-icon{width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,var(--blue),#7C3AED);display:flex;align-items:center;justify-content:center;box-shadow:var(--shadow-md)}.logo-icon svg{width:20px;height:20px;fill:white}.logo-text strong{font-size:15px;font-weight:800;color:var(--text);display:block}.logo-text span{font-size:10px;color:var(--muted);font-weight:600;letter-spacing:1px}.nav{flex:1;overflow-y:auto;padding:4px 12px}.nav-item{display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:10px;font-size:13.5px;font-weight:500;color:#64748B;cursor:pointer;transition:all .15s;margin-bottom:2px}.nav-item:hover{background:var(--bg);color:var(--text)}.nav-item.active{background:linear-gradient(135deg,#EEF2FF,#E0E7FF);color:var(--blue);font-weight:700}.sidebar-footer{border-top:1px solid var(--border);padding:14px 16px}.user-avatar{width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--blue),#7C3AED);display:flex;align-items:center;justify-content:center;color:white;font-size:13px;font-weight:700}.main{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;min-height:100vh}.topbar{background:var(--surface);border-bottom:1px solid var(--border);padding:0 28px;height:56px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:50}.topbar-title{font-size:16px;font-weight:700}.topbar-right{display:flex;align-items:center;gap:16px}.content{padding:28px;flex:1}.page-top{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:28px}.page-top h1{font-size:28px;font-weight:800;color:var(--blue);margin-bottom:8px}.page-top p{font-size:14px;color:var(--muted)}.btn-tambah{display:flex;align-items:center;gap:7px;padding:10px 18px;border-radius:var(--radius-sm);background:linear-gradient(135deg,var(--blue),#7C3AED);color:white;font-size:13.5px;font-weight:700;font-family:inherit;border:none;cursor:pointer;box-shadow:0 4px 14px rgba(79,111,255,.35);transition:all .2s}.btn-tambah:hover{transform:translateY(-2px);box-shadow:0 8px 22px rgba(79,111,255,.45)}.table-card{background:var(--surface);border-radius:var(--radius);border:1px solid var(--border);overflow:hidden;box-shadow:var(--shadow-xs)}.table-toolbar{display:flex;align-items:center;gap:12px;padding:16px 20px;border-bottom:1px solid var(--border);background:var(--gray-50)}.search-wrap{flex:1;display:flex;align-items:center;gap:8px;border:1.5px solid var(--border);border-radius:var(--radius-sm);padding:8px 14px;background:var(--surface);transition:all .15s}.search-wrap:focus-within{border-color:var(--blue);box-shadow:0 0 0 3px rgba(79,111,255,0.1)}.search-wrap input{border:none;background:none;outline:none;font-family:inherit;font-size:13.5px;color:var(--text);width:100%}.btn-filter{display:flex;align-items:center;gap:6px;padding:8px 14px;border-radius:var(--radius-sm);border:1.5px solid var(--border);background:var(--surface);font-family:inherit;font-size:13px;color:#64748B;cursor:pointer;transition:all .15s}.btn-filter:hover{border-color:var(--blue);color:var(--blue);background:var(--blue-lighter)}table{width:100%;border-collapse:collapse}thead tr{background:var(--gray-50)}th{padding:14px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--blue);letter-spacing:.8px;text-transform:uppercase;border-bottom:2px solid var(--border)}td{padding:14px 20px;font-size:13.5px;color:var(--text);border-bottom:1px solid var(--border)}tbody tr{transition:all .15s}tbody tr:hover{background:var(--gray-50)}.action-btn{display:inline-flex;align-items:center;justify-content:center;gap:0;margin-left:6px;width:32px;height:32px;border:1.5px solid var(--border);background:var(--surface);border-radius:var(--radius-sm);cursor:pointer;transition:all .15s;color:var(--gray-500)}.action-btn:hover{background:var(--blue-lighter);border-color:var(--blue);color:var(--blue)}.action-btn.danger:hover{background:var(--red-light);border-color:var(--red);color:var(--red)}.action-btn svg{width:14px;height:14px}.table-footer{display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-top:1px solid var(--border);font-size:13px;color:var(--muted);background:var(--gray-50)}.empty-state{display:flex;flex-direction:column;align-items:center;justify-content:center;padding:60px 20px;text-align:center}.empty-state-icon{width:80px;height:80px;background:var(--gray-100);border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:16px}.empty-state h5{font-size:18px;font-weight:700;color:var(--gray-900);margin-bottom:8px}.empty-state p{font-size:14px;color:var(--muted)}.modal{--bs-modal-zindex:1050}.modal-backdrop{--bs-backdrop-zindex:1040;background-color:rgba(0,0,0,0.5)}.modal.show{display:flex!important;justify-content:center;align-items:center}.modal.show .modal-dialog{animation:modalSlideIn .3s ease-out}@keyframes modalSlideIn{from{transform:translate(0,50px);opacity:0}to{transform:translate(0,0);opacity:1}}.modal-content{border:none;border-radius:var(--radius);box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);overflow:hidden}.modal-header{border-bottom:1px solid var(--border);padding:24px;background:linear-gradient(135deg,var(--blue-lighter),#F0F4FF)}.modal-header .modal-title{font-size:18px;font-weight:700;color:var(--blue)}.modal-header .btn-close{opacity:.5;transition:opacity .2s}.modal-header .btn-close:hover{opacity:1}.modal-body{padding:28px;background:var(--surface)}.modal-footer{border-top:1px solid var(--border);padding:18px 28px;background:var(--gray-50);gap:12px}.form-label{font-size:13px;font-weight:600;color:var(--gray-700);margin-bottom:8px;display:block}.form-control,.form-select{border:1.5px solid var(--border);border-radius:var(--radius-sm);padding:10px 12px;font-family:inherit;font-size:13.5px;color:var(--text);transition:all .15s;background:var(--surface)}.form-control:focus,.form-select:focus{border-color:var(--blue);box-shadow:0 0 0 3px rgba(79,111,255,0.1);outline:none}.form-control[readonly]{background:var(--gray-50);color:var(--gray-600)}.mb-3{margin-bottom:16px}.btn{font-family:inherit;border-radius:var(--radius-sm);font-weight:600;font-size:13px;padding:10px 16px;transition:all .15s;cursor:pointer;border:none}.btn-primary{background:linear-gradient(135deg,var(--blue),#7C3AED);color:white;box-shadow:0 4px 12px rgba(79,111,255,0.3)}.btn-primary:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(79,111,255,0.4)}.btn-primary:disabled{opacity:.6;cursor:not-allowed;transform:none}.btn-secondary{background:var(--gray-200);color:var(--gray-700)}.btn-secondary:hover{background:var(--gray-300)}.alert{padding:14px 16px;border-radius:var(--radius-sm);font-size:13.5px;display:flex;align-items:center;gap:10px;border:1px solid;animation:slideIn .3s ease-out}@keyframes slideIn{from{transform:translateX(400px);opacity:0}to{transform:translateX(0);opacity:1}}.alert-success{background:linear-gradient(135deg,#D1FAE5,#A7F3D0);color:#065F46;border-color:rgba(16,185,129,0.3)}.alert-danger{background:linear-gradient(135deg,#FEE2E2,#FECACA);color:#7F1D1D;border-color:rgba(239,68,68,0.3)}.detail-item{display:flex;align-items:flex-start;gap:12px;padding:16px;background:var(--gray-50);border-radius:var(--radius-sm);border:1px solid var(--border)}.detail-icon{width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:16px}.detail-content h6{font-size:12px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px}.detail-content p{font-size:14px;font-weight:600;color:var(--text);margin:0}@media (max-width:768px){.sidebar{width:0}.main{margin-left:0}.page-top{flex-direction:column;gap:16px}.table-toolbar{flex-direction:column;align-items:stretch}.search-wrap{width:100%}}.pagination{margin:0;font-size:13.5px}.page-link{color:var(--blue);border:1px solid var(--border);padding:6px 12px;border-radius:var(--radius-xs);margin:0 2px}.page-item.active .page-link{background-color:var(--blue);border-color:var(--blue);color:#fff}.page-link:hover{background-color:var(--blue-lighter);color:var(--blue)}.page-item.disabled .page-link{color:var(--muted);background-color:var(--gray-50)}
.ts-control { border-radius: var(--radius-sm); padding: 10px 12px; font-family: inherit; font-size: 13.5px; border-color: var(--border); }
.ts-control.focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(79, 111, 255, 0.1); }
</style>
</head>
<body>

@include('partials.sidebar')

<main class="main">
    <div class="topbar">
        <span class="topbar-title">Ajuan Mutasi</span>
        <div class="topbar-right">
            {{-- <button class="btn" style="background: transparent; border: 1px solid var(--border); width: 36px; height: 36px; border-radius: 50%; padding: 0; display: flex; align-items: center; justify-content: center;" title="Notifikasi">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
            </button> --}}
            <span class="topbar-title" style="font-size: 13px; margin: 0;">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}</span>
<form method="POST" action="{{ route('logout', [], false) }}" style="display:inline; margin:0;">
@csrf
<button type="submit" class="btn" style="background: transparent; border: 1px solid var(--border); padding: 6px 14px; font-size: 13px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" style="vertical-align: middle; margin-right: 6px;"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5-5-5zm-5 11H5V5h7V3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h7v-2z"/></svg>
                Keluar
            </button>
</form>
        </div>
    </div>

    <div class="content">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show position-fixed" style="top: 80px; right: 20px; z-index: 9999; min-width: 340px;" role="alert">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show position-fixed" style="top: 80px; right: 20px; z-index: 9999; min-width: 340px;" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="page-top">
            <div>
                <h1>Ajuan Mutasi</h1>
                <p id="dataCount">{{ $ajuan_mutasi->total() }} data ditemukan</p>
            </div>
            <button class="btn-tambah" onclick="ajuanManager.openCreateModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                Buat Ajuan Baru
            </button>
        </div>

        <div class="table-card">
            <div class="table-toolbar">
                <form method="GET" action="{{ route('pegawai.ajuan-mutasi.index') }}" id="searchForm" style="display: flex; gap: 12px; width: 100%; flex-wrap: wrap;">
                    <div class="search-wrap flex-grow-1">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="color: var(--muted);">
                            <path d="M15.5 14h-.79l-.28-.27A6.47 6.47 0 0016 9.5 6.5 6.5 0 109.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"></path>
                        </svg>
                        <input type="text" name="search" placeholder="Cari kode barang, nama barang, NUP, lokasi..." value="{{ request('search') }}" style="border: none; background: none; outline: none; font-family: inherit; font-size: 13.5px; width: 100%;">
                    </div>
                    <button type="submit" class="btn-filter">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M10 18h4v-2h-4v2zM3 6v2h18V6H3zm3 7h12v-2H6v2z"/></svg>
                        Cari
                    </button>
                    @if(request('search'))
                    <a href="{{ route('pegawai.ajuan-mutasi.index') }}" class="btn-filter" style="text-decoration: none; display: inline-flex;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                    </a>
                    @endif
                </form>
            </div>

            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal Ajuan</th>
                            <th>Kode Barang</th>
                            <th>NUP</th>
                            <th>Nama Barang</th>
                            <th>Lokasi Awal</th>
                            <th>Lokasi Akhir</th>
                            <th>Kondisi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ajuan_mutasi as $index => $item)
                        <tr>
                            <td>{{ $item->tanggal_mutasi_formatted }}</td>
                            <td><strong>{{ $item->kode_barang }}</strong></td>
                            <td>{{ $item->nup ?? '-' }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>{{ $item->lokasi_awal }}</td>
                            <td>{{ $item->lokasi_akhir }}</td>
                            <td>{{ $item->kondisi ?? '-' }}</td>
                            <td>
                                <div style="display: flex; gap: 4px;">
                                    <button class="action-btn" onclick="ajuanManager.openDetailModal({{ $item->id }})" title="Lihat Detail">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                                    </button>
                                    <button class="action-btn" onclick="ajuanManager.openEditModal({{ $item->id }})" title="Edit">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                                    </button>
                                    <button class="action-btn danger" onclick="ajuanManager.deleteItem({{ $item->id }})" title="Hapus">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <svg width="40" height="40" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                    </div>
                                    <h5>Tidak ada data</h5>
                                    <p>Belum ada riwayat ajuan mutasi</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($ajuan_mutasi->hasPages())
            <div class="table-footer">
                <span>Menampilkan {{ $ajuan_mutasi->firstItem() }}–{{ $ajuan_mutasi->lastItem() }} dari {{ $ajuan_mutasi->total() }} data</span>
                <div class="d-flex justify-content-end mb-0">
                    {!! $ajuan_mutasi->appends(request()->query())->links('pagination::bootstrap-5') !!}
                </div>
            </div>
            @endif
        </div>
    </div>
</main>

<div class="modal fade" id="crudModal" aria-labelledby="crudModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="crudModalLabel">Loading...</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="crudForm" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" id="modalId">
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                        <div>
                            <label class="form-label">Pilih Aset Tetap <span style="color: var(--red);">*</span></label>
                            <select name="aset_tetap_id" id="aset_tetap_id" class="form-select" required>
                                <option value="">-- Ketik kode atau nama barang --</option>
                                @foreach($asetTetap ?? [] as $aset)
                                <option value="{{ $aset->id }}">
                                    {{ $aset->kode_barang }} - {{ $aset->nama_barang }} (NUP: {{ $aset->nup ?? '-' }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="form-label">Lokasi Saat Ini <span style="color: var(--red);">*</span></label>
                            <input type="text" name="lokasi_awal" id="lokasi_awal" class="form-control" required>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                        <div>
                            <label class="form-label">Lokasi Tujuan <span style="color: var(--red);">*</span></label>
                            <input type="text" name="lokasi_akhir" id="lokasi_akhir" class="form-control" required>
                        </div>
                        <div>
                            <label class="form-label">Tanggal Ajuan <span style="color: var(--red);">*</span></label>
                            <input type="date" name="tanggal_mutasi" id="tanggal_mutasi" class="form-control" required value="{{ old('tanggal_mutasi', now()->format('Y-m-d')) }}">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                        <div>
                            <label class="form-label">Kode Barang</label>
                            <input type="text" name="kode_barang" id="kode_barang" class="form-control" readonly>
                        </div>
                        <div>
                            <label class="form-label">NUP</label>
                            <input type="text" name="nup" id="nup" class="form-control" readonly>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                        <div>
                            <label class="form-label">Nama Barang</label>
                            <input type="text" name="nama_barang" id="nama_barang" class="form-control" readonly>
                        </div>
                        <div>
                            <label class="form-label">Kondisi</label>
                            <input type="text" name="kondisi" id="kondisi" class="form-control" readonly>
                        </div>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label class="form-label">Alasan/Keterangan Ajuan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control" rows="3" placeholder="Sebutkan alasan mengapa barang ini dipindahkan..."></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <span class="spinner-border spinner-border-sm d-none me-2" id="loadingSpinner" style="width: 14px; height: 14px; border-width: 2px;"></span>
                        <span id="submitText">Kirim Ajuan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="var(--blue)" style="vertical-align: middle; margin-right: 8px;">
                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                    </svg>
                    Detail Ajuan Mutasi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailContent" style="padding: 28px;">
                </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<script>
class AjuanManager {
    constructor() {
        this.modal = null;
        this.detailModal = null;
        this.tomSelect = null;
        this.initializeModals();
        this.initEventListeners();
    }

    initializeModals() {
        const crudModalEl = document.getElementById('crudModal');
        const detailModalEl = document.getElementById('detailModal');
        
        if (crudModalEl) {
            this.modal = new bootstrap.Modal(crudModalEl, {
                backdrop: 'static',
                keyboard: true
            });
        }
        
        if (detailModalEl) {
            this.detailModal = new bootstrap.Modal(detailModalEl, {
                backdrop: 'static',
                keyboard: true
            });
        }
    }

    initEventListeners() {
        const asetSelect = document.getElementById('aset_tetap_id');
        const crudForm = document.getElementById('crudForm');
        const searchForm = document.getElementById('searchForm');

        if (asetSelect) {
            this.tomSelect = new TomSelect(asetSelect, {
                create: false,
                sortField: { field: "text", direction: "asc" },
                placeholder: "-- Ketik kode atau nama barang --"
            });

            this.tomSelect.on('change', (value) => {
                this.loadAsetData(value);
            });
        }

        if (crudForm) {
            crudForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleSubmit(e);
            });
        }

        if (searchForm) {
            searchForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.search();
            });
        }
    }

    async loadAsetData(id) {
        if (!id) {
            this.clearAutoFillFields();
            document.getElementById('lokasi_awal').value = '';
            return;
        }

        try {
            // ✅ UPDATE URL AJAX: Diselaraskan dengan prefix '/pegawai/...'
            const response = await fetch(`/pegawai/ajuan-mutasi/aset/${id}`);
            const data = await response.json();
            
            document.getElementById('kode_barang').value = data.kode_barang;
            document.getElementById('nup').value = data.nup || '';
            document.getElementById('nama_barang').value = data.nama_barang;
            document.getElementById('kondisi').value = data.kondisi || '';
            document.getElementById('lokasi_awal').value = data.lokasi_sekarang;
        } catch (error) {
            console.error('Error loading aset data:', error);
        }
    }

    clearAutoFillFields() {
        ['kode_barang', 'nup', 'nama_barang', 'kondisi', 'keterangan'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });
    }

    openCreateModal() {
        document.getElementById('crudModalLabel').textContent = 'Buat Ajuan Mutasi Baru';
        // ✅ UPDATE ROUTE:
        document.getElementById('crudForm').action = "{{ route('pegawai.ajuan-mutasi.store') }}";
        document.getElementById('modalId').value = '';
        document.getElementById('submitText').textContent = 'Kirim Ajuan';
        
        this.resetForm();
        if (this.modal) {
            this.modal.show();
        }
    }

    async openEditModal(id) {
        try {
            // ✅ UPDATE URL AJAX: 
            const response = await fetch(`/pegawai/ajuan-mutasi/${id}/edit`);
            const data = await response.json();
            
            document.getElementById('crudModalLabel').textContent = 'Edit Ajuan Mutasi';
            // ✅ UPDATE URL AJAX ACTION:
            document.getElementById('crudForm').action = `/pegawai/ajuan-mutasi/${id}`;
            document.getElementById('modalId').value = id;
            
            let methodInput = document.getElementById('_method');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.id = '_method';
                document.getElementById('crudForm').appendChild(methodInput);
            }
            methodInput.value = 'PUT';
            
            document.getElementById('submitText').textContent = 'Update Ajuan';
            
            if (this.tomSelect) {
                this.tomSelect.setValue(data.aset_tetap_id, true);
            }
            
            await this.loadAsetData(data.aset_tetap_id);
            document.getElementById('lokasi_awal').value = data.lokasi_awal;
            document.getElementById('lokasi_akhir').value = data.lokasi_akhir;
            document.getElementById('tanggal_mutasi').value = data.tanggal_mutasi;
            document.getElementById('keterangan').value = data.keterangan || '';
            
            if (this.modal) {
                this.modal.show();
            }
        } catch (error) {
            this.showAlert('error', 'Gagal memuat data untuk edit');
        }
    }

    async openDetailModal(id) {
        try {
            // ✅ UPDATE URL AJAX:
            const response = await fetch(`/pegawai/ajuan-mutasi/${id}`);
            const data = await response.json();
            this.renderDetailModal(data);
            if (this.detailModal) {
                this.detailModal.show();
            }
        } catch (error) {
            this.showAlert('error', 'Gagal memuat detail');
        }
    }

    renderDetailModal(data) {
        document.getElementById('detailContent').innerHTML = `
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="detail-item">
                    <div class="detail-icon" style="background: rgba(79, 111, 255, 0.1); color: var(--blue);">
                        <i class="fas fa-file-alt"></i>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--green);">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <div class="detail-content">
                        <h6>Tanggal Ajuan</h6>
                        <p>${data.tanggal_mutasi_formatted}</p>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-icon" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6;">
                        <i class="fas fa-barcode"></i>
                    </div>
                    <div class="detail-content">
                        <h6>Kode Barang</h6>
                        <p>${data.kode_barang}</p>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--yellow);">
                        <i class="fas fa-hashtag"></i>
                    </div>
                    <div class="detail-content">
                        <h6>NUP</h6>
                        <p>${data.nup || '-'}</p>
                    </div>
                </div>
                <div class="detail-item" style="grid-column: 1 / -1;">
                    <div class="detail-icon" style="background: rgba(107, 114, 128, 0.1); color: var(--gray-600);">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="detail-content">
                        <h6>Nama Barang</h6>
                        <p>${data.nama_barang}</p>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-icon" style="background: rgba(79, 111, 255, 0.1); color: var(--blue);">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="detail-content">
                        <h6>Lokasi Saat Ini</h6>
                        <p>${data.lokasi_awal}</p>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--green);">
                        <i class="fas fa-map-pin"></i>
                    </div>
                    <div class="detail-content">
                        <h6>Lokasi Tujuan</h6>
                        <p>${data.lokasi_akhir}</p>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--green);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="detail-content">
                        <h6>Kondisi</h6>
                        <p>${data.kondisi || '-'}</p>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-icon" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6;">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="detail-content">
                        <h6>Diajukan oleh</h6>
                        <p>${data.user?.name || '-'}</p>
                    </div>
                </div>
                <div class="detail-item" style="grid-column: 1 / -1;">
                    <div class="detail-icon" style="background: rgba(107, 114, 128, 0.1); color: var(--gray-600);">
                        <i class="fas fa-align-left"></i>
                    </div>
                    <div class="detail-content">
                        <h6>Alasan/Keterangan Ajuan</h6>
                        <p>${data.keterangan || '-'}</p>
                    </div>
                </div>
            </div>
        `;
    }

    async handleSubmit(e) {
        const form = e.target;
        const submitBtn = document.getElementById('submitBtn');
        const spinner = document.getElementById('loadingSpinner');
        const submitText = document.getElementById('submitText');

        submitBtn.disabled = true;
        spinner.classList.remove('d-none');
        submitText.textContent = 'Memproses...';

        try {
            const formData = new FormData(form);

            if (form.querySelector('input[name="_method"]')) {
                formData.append('_method', 'PUT');
            }
            
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (response.ok) {
                this.showAlert('success', result.message);
                if (this.modal) {
                    this.modal.hide();
                }
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showAlert('error', result.message || 'Terjadi kesalahan');
            }
        } catch (error) {
            this.showAlert('error', 'Terjadi kesalahan koneksi');
        } finally {
            submitBtn.disabled = false;
            spinner.classList.add('d-none');
            submitText.textContent = 'Kirim Ajuan';
        }
    }

    async deleteItem(id) {
        if (!confirm('Apakah Anda yakin ingin membatalkan dan menghapus ajuan ini?')) return;

        try {
            // ✅ UPDATE URL AJAX:
            const response = await fetch(`/pegawai/ajuan-mutasi/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();
            if (response.ok) {
                this.showAlert('success', result.message);
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showAlert('error', result.message || 'Gagal menghapus data');
            }
        } catch (error) {
            this.showAlert('error', 'Gagal menghapus data');
        }
    }

    search() {
        document.getElementById('searchForm').submit();
    }

    resetForm() {
        document.getElementById('crudForm').reset();
        this.clearAutoFillFields();
        const methodInput = document.getElementById('_method');
        if (methodInput) methodInput.remove();
        
        if (this.tomSelect) {
            this.tomSelect.clear(true);
        }
    }

    showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

        const alert = document.createElement('div');
        alert.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
        alert.style.cssText = `top: 80px; right: 20px; z-index: 9999; min-width: 340px;`;
        alert.innerHTML = `
            <i class="fas ${icon}"></i>
            <span>${message}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        document.body.appendChild(alert);

        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    }
}

let ajuanManager;

document.addEventListener('DOMContentLoaded', () => {
    ajuanManager = new AjuanManager();

    document.querySelectorAll('.alert-dismissible').forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>

<meta name="csrf-token" content="{{ csrf_token() }}">

</body>
</html>
