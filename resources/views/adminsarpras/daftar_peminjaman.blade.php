<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar Peminjaman - Admin Sarana Prasarana</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  :root {
    --primary: #4361ee;
    --primary-light: #eef0fd;
    --success: #2ec4b6;
    --success-light: #e8faf9;
    --warning: #f4a261;
    --warning-light: #fff4ec;
    --danger: #e63946;
    --danger-light: #fdecea;
    --info: #0ea5e9;
    --info-light: #e0f2fe;
    --sidebar-bg: #fff;
    --body-bg: #f0f2f9;
    --text-primary: #1a1f36;
    --text-secondary: #6b7280;
    --border: #e5e7eb;
    --card-bg: #fff;
    --sidebar-width: 240px;
  }
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--body-bg); color: var(--text-primary); display: flex; min-height: 100vh; }

  /* Tombol Aksi */
  .action-btns { display: flex; gap: 6px; align-items: center; flex-wrap: wrap; }
  .act-btn {
    width: 30px; height: 30px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; border: none; transition: all .2s;
  }
  .act-detail { background: var(--info-light); color: var(--info); }
  .act-detail:hover { background: var(--info); color: #fff; }
  .act-edit { background: var(--primary-light); color: var(--primary); }
  .act-edit:hover { background: var(--primary); color: #fff; }
  .act-forward { background: var(--warning); color: #fff; }
  .act-forward:hover { background: #d97706; }
  .act-reject { background: var(--danger-light); color: var(--danger); }
  .act-reject:hover { background: var(--danger); color: #fff; }
  .act-dash { background: #f3f4f6; color: var(--text-secondary); }
  .act-dash:hover { background: #e5e7eb; color: var(--text-primary); }

  .badge-warning { background: var(--warning-light); color: var(--warning); }
  .topbar {
    background: var(--card-bg); border-bottom: 1px solid var(--border);
    padding: 0 28px; height: 56px; display: flex; align-items: center; justify-content: space-between;
    position: sticky; top: 0; z-index: 50;
  }
  .topbar-title { font-size: 16px; font-weight: 700; }
  .topbar-right { display: flex; align-items: center; gap: 16px; }
  .notif-btn {
    width: 36px; height: 36px; border-radius: 50%; border: 1px solid var(--border);
    background: var(--card-bg); display: flex; align-items: center; justify-content: center;
    cursor: pointer; position: relative;
  }
  .notif-dot { width: 8px; height: 8px; background: #EF4444; border-radius: 50%; position: absolute; top: 6px; right: 6px; border: 2px solid white; }
  .date-text { font-size: 13px; color: #64748B; font-weight: 500; }
  .btn-keluar {
    display: flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 8px;
    border: 1px solid var(--border); background: var(--card-bg); color: #64748B;
    font-size: 13px; font-weight: 600; font-family: inherit; cursor: pointer; transition: all .15s;
  }
  .btn-keluar:hover { background: #FEF2F2; color: #EF4444; }

  .content { padding: 28px; flex: 1; overflow-x: hidden; }

  /* Tabs */
  .top-bar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
  .tabs { display: flex; gap: 4px; border-bottom: 2px solid var(--border); }
  .tab {
    padding: 10px 20px; font-size: 14px; font-weight: 500;
    color: var(--text-secondary); cursor: pointer; border-bottom: 2px solid transparent;
    margin-bottom: -2px; transition: all .2s;
  }
  .tab.active { color: var(--primary); border-bottom-color: var(--primary); font-weight: 600; }
  .tab:hover:not(.active) { color: var(--text-primary); }
  
  .btn-primary {
    display: flex; align-items: center; gap: 8px;
    padding: 10px 18px; background: var(--primary); color: #fff;
    border: none; border-radius: 10px; font-family: inherit;
    font-size: 14px; font-weight: 600; cursor: pointer; transition: background .2s;
  }
  .btn-primary:hover { background: #3251d4; }

  .page-hdr { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; margin-bottom:22px; }
  .page-hdr h1 { font-size:22px; font-weight:700; letter-spacing:-.5px; }
  .page-hdr p { font-size:13px; color:var(--text-secondary); margin-top:2px; }

  /* Table */
  .table-card { background: var(--card-bg); border-radius: 16px; border: 1px solid var(--border); overflow-x: auto; }
  table { width: 100%; border-collapse: collapse; min-width: 900px; }
  thead th {
    font-size: 12px; font-weight: 600; color: var(--text-secondary);
    padding: 12px 16px; text-align: left; border-bottom: 1px solid var(--border);
    text-transform: uppercase; letter-spacing: .05em; background: #fafbff;
  }
  tbody td { padding: 14px 16px; font-size: 13px; border-bottom: 1px solid var(--border); }
  tbody tr:last-child td { border-bottom: none; }
  tbody tr:hover { background: #fafbff; }

  .status-badge { display: inline-flex; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; white-space: nowrap; }
  .badge-approved { background: var(--success-light); color: var(--success); }
  .badge-pending { background: var(--warning-light); color: var(--warning); }
  .badge-rejected { background: var(--danger-light); color: var(--danger); }
  .peminjam-name { font-weight: 600; color: var(--primary); }

  /* PAGINATION */
  .table-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 20px;
    border-top: 1px solid var(--border);
    font-size: 13px; color: var(--muted);
  }
  /* Modal */
  .modal-overlay {
    position: fixed; inset: 0; background: rgba(0,0,0,.4);
    display: none; align-items: center; justify-content: center; z-index: 200;
  }
  .modal-overlay.open { display: flex; }
  .modal {
    background: #fff; border-radius: 16px; padding: 28px;
    width: 520px; max-width: 90vw; box-shadow: 0 20px 60px rgba(0,0,0,.15);
  }
  .modal.modal-lg { width: 600px; }
  .modal-title { font-size: 18px; font-weight: 700; margin-bottom: 20px; }
  .form-group { margin-bottom: 16px; }
  .form-label { font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block; color: var(--text-primary); }
  .form-input {
    width: 100%; padding: 10px 14px; border: 1px solid var(--border);
    border-radius: 10px; font-family: inherit; font-size: 13px; outline: none;
    transition: border .2s; background: #fff; color: var(--text-primary);
  }
  .form-input:disabled { background: #f9fafb; color: #9ca3af; cursor: not-allowed; }
  .form-input:focus { border-color: var(--primary); }
  .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
  .modal-footer { display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px; }
  .btn-cancel {
    padding: 10px 18px; border: 1px solid var(--border); border-radius: 10px;
    font-family: inherit; font-size: 14px; cursor: pointer; background: #fff;
    font-weight: 500; color: var(--text-secondary);
  }
  .btn-cancel:hover { background: #f9fafb; }

  /* Detail Table in Modal */
  .detail-table { width: 100%; border: none; min-width: unset; }
  .detail-table td { padding: 8px 0; border: none; font-size: 13.5px; vertical-align: top; }
  .detail-table td.label-col { font-weight: 600; width: 150px; color: var(--text-primary); }
  .detail-table td.colon-col { width: 15px; }
</style>
</head>
<body>

@include('partials.sidebar')

<main class="main">
  <div class="topbar">
    <span class="topbar-title">Daftar Peminjam</span>
    <div class="topbar-right">
      <div class="notif-btn">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="#64748B"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
        <span class="notif-dot"></span>
      </div>
      <span class="date-text">{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
      <button class="btn-keluar">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5-5-5zm-5 11H5V5h7V3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h7v-2z"/></svg>
        Keluar
      </button>
    </div>
  </div>

  <div class="content">
    <div class="top-bar">
      <div class="page-hdr">
        <div>
          <h1>Daftar Peminjaman Gedung</h1>
          <p>{{ $peminjaman->total() }} data ditemukan</p>
        </div>
      </div>
      <div class="tabs">
        <div class="tab active" onclick="filterTab('semua', this)">Semua</div>
        <div class="tab" onclick="filterTab('disetujui', this)">Disetujui</div>
        <div class="tab" onclick="filterTab('menunggu', this)">Menunggu</div>
        <div class="tab" onclick="filterTab('ditolak', this)">Ditolak</div>
      </div>
    </div>

    <!-- TOAST NOTIFICATION -->
    @if(session('error'))
    <div style="position:fixed;top:90px;right:20px;z-index:1000;padding:12px 20px;background:#fee;color:#dc2626;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.15);font-weight:500;max-width:400px;" id="errorToast">
        {{ session('error') }}
        <button onclick="this.parentElement.remove()" style="margin-left:12px;background:none;border:none;font-size:18px;cursor:pointer;float:right;">&times;</button>
    </div>
    @endif

    @if(session('success'))
    <div style="position:fixed;top:90px;right:20px;z-index:1000;padding:12px 20px;background:#d1fae5;color:#065f46;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.15);font-weight:500;max-width:400px;" id="successToast">
        {{ session('success') }}
        <button onclick="this.parentElement.remove()" style="margin-left:12px;background:none;border:none;font-size:18px;cursor:pointer;float:right;">&times;</button>
    </div>
    @endif

    <div class="table-card">
      <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Fasilitas</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Tujuan</th>
                <th>Total Bayar</th>
                <th>Status Pembayaran</th>
                <th>Cara Bayar</th>
                <th>Status</th>
                <th style="min-width: 160px; text-align: center;">Aksi</th>
            </tr>
        </thead>

        <tbody>
        @forelse($peminjaman as $item)
        <tr data-status="{{ $item->status }}">
            <td>{{ $loop->iteration + $peminjaman->firstItem() - 1 }}</td>
            <td class="peminjam-name">{{ $item->nama_lengkap }}<br><small class="text-muted" style="color:var(--text-secondary); font-weight:400;">{{ $item->instansi_lembaga }}</small></td>
            <td>{{ $item->nama_fasilitas ?? $item->fasilitas }}</td>
            <td>{{ $item->tanggal_pinjam->locale('id')->isoFormat('D MMM YYYY') }}</td>
            <td>{{ $item->tanggal_kembali->locale('id')->isoFormat('D MMM YYYY') }}</td>
            
            <td>
                    <div style="margin-bottom:4px;"><b>Tujuan:</b> {{ Str::limit($item->tujuan_penggunaan, 30) }}</div>
                    <div style="font-size: 11px; color: var(--text-secondary);">
                        <div>👥 Peserta: <b>{{ $item->jumlah_peserta ?? 0 }} Orang</b></div>
                    </div>
                </td>
            <td>Rp {{ number_format($item->total_pembayaran, 0, ',', '.') }}</td>
            <td>
                @if($item->status_pembayaran == 'lunas')
                    <span class="status-badge badge-approved">Lunas</span>
                @else
                    <span class="status-badge badge-pending">Belum Lunas</span>
                @endif
            </td>
            <td>
                <span class="status-badge" style="background: var(--primary-light); color: var(--primary);">
                    E-Billing
                </span>
            </td>
            <td>
                <span class="status-badge 
                    @if($item->status == 'disetujui' || $item->status == 'disetujui_kasubag') badge-approved
                    @elseif($item->status == 'pending') badge-pending
                    @elseif($item->status == 'dalam_review') badge-warning
                    @else badge-rejected @endif">
                    {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                </span>
                @if($item->diteruskan_ke_kasubag_date)
                <br><small style="color:var(--text-secondary);">Fwd: {{ $item->diteruskan_ke_kasubag_date->locale('id')->isoFormat('D MMM') }}</small>
                @endif
            </td>
            <td>
                @if($item->status == 'dibatalkan')
                    <div style="text-align: center;">
                        <span style="font-size: 13px; color: var(--text-secondary); font-style: italic;">Tidak ada aksi</span>
                    </div>
                @else
                    <div class="action-btns" style="justify-content: center;">
                        <button class="act-btn act-detail" title="👁️ Detail" 
                                data-item="{{ json_encode($item) }}"
                                onclick="openDetailModal(this)">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>

                        <button class="act-btn act-edit" title="✏️ Edit" 
                                data-item="{{ json_encode($item) }}"
                                onclick="openEditModal(this)">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                            </svg>
                        </button>

                        @if($item->status == 'pending')
                            <button class="act-btn act-forward" title="➡️ Teruskan ke Kasubag" onclick="openForwardModal({{ $item->id }})">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                            </button>

                            <button class="act-btn act-reject" title="❌ Tolak" onclick="openRejectModal({{ $item->id }})">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"/></svg>
                            </button>
                        @endif

                        @if(in_array($item->status, ['disetujui_kasubag', 'disetujui']))
                            <form action="{{ route('adminsarpras.peminjaman.generate-surat', $item->id) }}" method="GET" target="_blank" style="display:inline-flex; gap:4px; align-items:center;">
                              <input type="date" name="tanggal_surat" value="{{ now()->format('Y-m-d') }}" required title="Tanggal surat" style="padding:5px; max-width:135px;">
                              <button type="submit" class="act-btn" style="background: #e0e7ff; color: #4361ee;" title="Generate Surat Perjanjian">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                              </button>
                            </form>

                            <button type="button" class="act-btn" style="background: #dcfce7; color: #10b981;" title="Upload Surat Perjanjian Final" onclick="openUploadModal({{ $item->id }})">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line>
                                </svg>
                            </button>

                            @if($item->surat_path)
                            <a href="{{ asset('storage/' . $item->surat_path) }}" target="_blank" class="act-btn" style="background: #f3e8fd; color: #9333ea;" title="Lihat Dokumen Final">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                            </a>
                            @endif
                        @endif
                    </div>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="10" style="text-align: center; padding: 40px; color: var(--text-secondary);">
                Tidak ada data peminjaman
            </td>
        </tr>
        @endforelse
        </tbody>
      </table>
    </div>

    <!-- PAGINATION -->
    @if($peminjaman->hasPages())
    <div class="table-footer">
        <span>Menampilkan {{ ($peminjaman->currentPage() - 1) * $peminjaman->perPage() + 1 }}–{{ min($peminjaman->total(), $peminjaman->currentPage() * $peminjaman->perPage()) }} dari {{ $peminjaman->total() }} data</span>
        <div class="pagination">
          @if($peminjaman->onFirstPage())
            <button class="page-btn" disabled style="opacity:0.5; cursor:not-allowed;"><i class="fas fa-chevron-left"></i></button>
          @else
            <a href="{{ $peminjaman->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>
          @endif

          @foreach($peminjaman->getUrlRange(max(1, $peminjaman->currentPage() - 2), min($peminjaman->lastPage(), $peminjaman->currentPage() + 2)) as $page => $url)
            <a href="{{ $url }}" class="page-btn {{ $peminjaman->currentPage() == $page ? 'active' : '' }}">{{ $page }}</a>
          @endforeach

          @if($peminjaman->hasMorePages())
            <a href="{{ $peminjaman->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>
          @else
            <button class="page-btn" disabled style="opacity:0.5; cursor:not-allowed;"><i class="fas fa-chevron-right"></i></button>
          @endif
        </div>
      </div>
      @endif
    <div class="table-footer">
        <span>Menampilkan {{ $peminjaman->firstItem() ?? 0 }}–{{ $peminjaman->lastItem() ?? 0 }} dari {{ $peminjaman->total() }} data</span>
        <div class="pagination">
          {{ $peminjaman->appends(request()->query())->links() }}
        </div>
      </div>

  </div>
</main>

<!-- ✅ MODAL DETAIL PEMINJAMAN -->
<div class="modal-overlay" id="detailModal">
    <div class="modal modal-lg">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h3 class="modal-title" style="margin-bottom:0;">👁️ Detail Peminjaman Gedung</h3>
            <button onclick="closeModal('detailModal')" style="background:none; border:none; font-size:24px; cursor:pointer; color:var(--text-secondary);">&times;</button>
        </div>
        
        <table class="detail-table">
            <tr><td class="label-col">Nama Lengkap</td><td class="colon-col">:</td><td><span id="dtNama"></span></td></tr>
            <tr><td class="label-col">NIP/NIK</td><td class="colon-col">:</td><td><span id="dtNik"></span></td></tr>
            <tr><td class="label-col">Instansi/Lembaga</td><td class="colon-col">:</td><td><span id="dtInstansi"></span></td></tr>
            <tr><td class="label-col">Kontak (HP/WA)</td><td class="colon-col">:</td><td><span id="dtKontak"></span></td></tr>
            <tr><td class="label-col">Gedung / Fasilitas</td><td class="colon-col">:</td><td><span id="dtFasilitas" style="font-weight:600; color:var(--primary);"></span></td></tr>
            <tr><td class="label-col">Jadwal Pinjam</td><td class="colon-col">:</td><td><span id="dtTglPinjam"></span> <span style="color:var(--text-secondary); margin:0 6px;">s/d</span> <span id="dtTglKembali"></span></td></tr>
            <tr><td class="label-col">Jam Penggunaan</td><td class="colon-col">:</td><td><span id="dtWaktu"></span></td></tr>
            <tr><td class="label-col">Tujuan/Keperluan</td><td class="colon-col">:</td><td><span id="dtTujuan"></span></td></tr>
            <tr><td class="label-col">Tarif per Hari</td><td class="colon-col">:</td><td>Rp <span id="dtTarif"></span></td></tr>
            <tr><td class="label-col">Total Bayar</td><td class="colon-col">:</td><td><span style="font-weight:700; color:var(--success);">Rp <span id="dtTotal"></span></span></td></tr>
            <tr><td class="label-col">Status Pembayaran</td><td class="colon-col">:</td><td><span id="dtStatusBayar"></span></td></tr>
            <tr><td class="label-col">Cara Pembayaran</td><td class="colon-col">:</td><td><span id="dtCaraBayar"></span></td></tr>
            <tr><td class="label-col">Status Peminjaman</td><td class="colon-col">:</td><td><span id="dtStatus" style="font-weight:700;"></span></td></tr>
            <tr id="rowDtKomentar" style="display:none;">
                <td class="label-col" style="color:var(--danger)">Alasan / Komentar</td><td class="colon-col" style="color:var(--danger)">:</td>
                <td><span id="dtKomentar" style="color:var(--danger); font-style:italic;"></span></td>
            </tr>
            <tr> 
                <td class="label-col">Lampiran Surat</td><td class="colon-col">:
                    @if($peminjaman->first() && $peminjaman->first()->surat_path)
                        <a href="{{ route('adminsarpras.download-surat', $peminjaman->first()->id) }}" class="btn-primary" title="📄 Download Surat" download>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="#fff">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14,2 14,8 20,8"/>
                                <line x1="16" y1="13" x2="8" y2="21"/>
                                <line x1="16" y1="21" x2="8" y2="13"/>
                            </svg>
                            Download Surat
                        </a>
                    @else
                        <span style="color:var(--text-secondary); font-style:italic;">Tidak ada lampiran</span>
                    @endif
                </td>
            </tr>
        </table>

        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeModal('detailModal')">Tutup</button>
        </div>
    </div>
</div>


<!-- ✅ MODAL EDIT PEMINJAMAN -->
<div class="modal-overlay" id="editModal">
    <div class="modal modal-lg">
        <h3 class="modal-title">✏️ Edit Data Peminjaman</h3>
        <form id="editForm">
            <input type="hidden" id="editId" name="id">
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nama Peminjam</label>
                    <input type="text" class="form-input" id="editNama" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">Fasilitas / Gedung</label>
                    <input type="text" class="form-input" id="editFasilitas" disabled>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Total Pembayaran (Rp) <span style="color:#e63946">*</span></label>
                    <input type="number" class="form-input" name="total_pembayaran" id="editTotalBayar" required min="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Status Pembayaran <span style="color:#e63946">*</span></label>
                    <select class="form-input" name="status_pembayaran" id="editStatusPembayaran" required>
                        <option value="belum_lunas">Belum Lunas</option>
                        <option value="lunas">Lunas</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Status Verifikasi / Peminjaman <span style="color:#e63946">*</span></label>
                <select class="form-input" name="status" id="editStatus" required>
                    <option value="pending">Pending</option>
                    <option value="dalam_review">Dalam Review (Di Kasubag)</option>
                    <option value="disetujui_kasubag">Disetujui Kasubag</option>
                    <option value="disetujui">Disetujui Final</option>
                    <option value="ditolak">Ditolak</option>
                </select>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('editModal')">Batal</button>
                <button type="submit" class="btn-primary">💾 Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Forward to Kasubag -->
<div class="modal-overlay" id="forwardModal">
    <div class="modal">
        <h3 class="modal-title">📤 Teruskan ke Kasubag</h3>
        <form id="forwardForm">
            <input type="hidden" id="forwardId" name="id">
            <div class="form-group">
                <label class="form-label">Komentar untuk Kasubag (Opsional)</label>
                <textarea class="form-input" name="komentar" rows="4" placeholder="Tulis instruksi/keterangan untuk Kasubag..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('forwardModal')">Batal</button>
                <button type="submit" class="btn-primary">✅ Teruskan ke Kasubag</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Reject -->
<div class="modal-overlay" id="rejectModal">
    <div class="modal">
        <h3 class="modal-title">❌ Tolak Peminjaman</h3>
        <form id="rejectForm">
            <input type="hidden" id="rejectId" name="id">
            <div class="form-group">
                <label class="form-label">Alasan Penolakan <span style="color:#e63946">*</span></label>
                <textarea class="form-input" name="komentar" rows="4" required placeholder="Jelaskan alasan penolakan secara jelas..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('rejectModal')">Batal</button>
                <button type="submit" class="btn-primary" style="background:var(--danger)">🚫 Tolak</button>
            </div>
        </form>
    </div>
</div>

<!--- Modal Upload Surat Perjanjian Final -->
<div class="modal-overlay" id="uploadModal">
    <div class="modal">
        <h3 class="modal-title">📤 Upload Surat Perjanjian Final</h3>
        <form id="uploadForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="form-label">File Surat (PDF) <span style="color:#e63946">*</span></label>
                <input type="file" name="surat_bast" class="form-input" accept="application/pdf" required style="background: white;">
                <p style="font-size: 11px; color: var(--text-secondary); margin-top: 8px;">Pastikan file PDF yang diunggah adalah dokumen final yang sudah ditandatangani. Ukuran maksimal 5MB.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('uploadModal')">Batal</button>
                <button type="submit" class="btn-primary" style="background: var(--success);"><i class="fas fa-upload"></i> Upload Dokumen</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Tab Filter Helper
    window.filterTab = function(status, element) {
        document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
        element.classList.add('active');
        
        const url = new URL(window.location);
        if (status !== 'semua') {
            url.searchParams.set('status', status === 'menunggu' ? 'pending' : status);
        } else {
            url.searchParams.delete('status');
        }
        window.location.href = url;
    }

    // Modal Trigger Helpers
    window.openForwardModal = function(id) {
        document.getElementById('forwardId').value = id;
        document.getElementById('forwardModal').classList.add('open');
    }
    window.openRejectModal = function(id) {
        document.getElementById('rejectId').value = id;
        document.getElementById('rejectModal').classList.add('open');
    }
    
    // ✅ BUKA MODAL DETAIL DAN ISI DATA
    window.openDetailModal = function(btnElement) {
        const item = JSON.parse(btnElement.getAttribute('data-item'));
        
        document.getElementById('dtNama').innerText = item.nama_lengkap || '-';
        document.getElementById('dtNik').innerText = item.nip_nik || '-';
        document.getElementById('dtInstansi').innerText = item.instansi_lembaga || '-';
        document.getElementById('dtFasilitas').innerText = item.nama_fasilitas || item.fasilitas || '-';
        
        document.getElementById('dtTglPinjam').innerText = item.tanggal_pinjam ? item.tanggal_pinjam.substring(0, 10) : '-';
        document.getElementById('dtTglKembali').innerText = item.tanggal_kembali ? item.tanggal_kembali.substring(0, 10) : '-';
        document.getElementById('dtWaktu').innerText = (item.jam_mulai || '-') + ' s/d ' + (item.jam_selesai || '-');
        document.getElementById('dtTujuan').innerText = item.tujuan_penggunaan || '-';
        
        document.getElementById('dtTarif').innerText = parseInt(item.tarif_per_hari || 0).toLocaleString('id-ID');
        document.getElementById('dtTotal').innerText = parseInt(item.total_pembayaran || 0).toLocaleString('id-ID');
        document.getElementById('dtStatusBayar').innerText = item.status_pembayaran === 'lunas' ? 'Lunas' : 'Belum Lunas';
        document.getElementById('dtCaraBayar').innerText = (item.cara_pembayaran || 'E-Billing').toUpperCase();
        
        document.getElementById('dtStatus').innerText = item.status.replace(/_/g, ' ').toUpperCase();
        
        if (item.komentar) {
            document.getElementById('dtKomentar').innerText = item.komentar;
            document.getElementById('rowDtKomentar').style.display = 'table-row';
        } else {
            document.getElementById('rowDtKomentar').style.display = 'none';
        }
        
        document.getElementById('detailModal').classList.add('open');
    }

    // ✅ BUKA MODAL EDIT DAN ISI DATA
    window.openEditModal = function(btnElement) {
        const item = JSON.parse(btnElement.getAttribute('data-item'));
        
        document.getElementById('editId').value = item.id;
        document.getElementById('editNama').value = item.nama_lengkap;
        document.getElementById('editFasilitas').value = item.nama_fasilitas || item.fasilitas;
        
        document.getElementById('editTotalBayar').value = Math.floor(item.total_pembayaran);
        document.getElementById('editStatusPembayaran').value = item.status_pembayaran;
        document.getElementById('editStatus').value = item.status;
        
        document.getElementById('editModal').classList.add('open');
    }

    window.closeModal = function(modalId) {
        document.getElementById(modalId).classList.remove('open');
        if (modalId === 'forwardModal') document.getElementById('forwardForm').reset();
        if (modalId === 'rejectModal') document.getElementById('rejectForm').reset();
        if (modalId === 'editModal') document.getElementById('editForm').reset();
    }

    // ✅ EDIT FORM SUBMIT (Fetch API)
    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('editId').value;
        const payload = {
            total_pembayaran: this.querySelector('input[name="total_pembayaran"]').value,
            status_pembayaran: this.querySelector('select[name="status_pembayaran"]').value,
            status: this.querySelector('select[name="status"]').value,
        };
        
        fetch(`/adminsarpras/peminjaman/${id}/update`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Gagal menyimpan perubahan!');
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('Terjadi kesalahan koneksi.');
        });
    });

    // ✅ FORWARD FORM - JSON
    document.getElementById('forwardForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('forwardId').value;
        const komentar = this.querySelector('textarea[name="komentar"]').value;
        
        fetch(`/adminsarpras/peminjaman/${id}/forward`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ komentar })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) { location.reload(); } 
            else { alert(data.message || 'Gagal meneruskan!'); }
        })
        .catch(err => { console.error('Error:', err); alert('Terjadi kesalahan.'); });
    });

    // ✅ REJECT FORM - JSON
    document.getElementById('rejectForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('rejectId').value;
        const komentar = this.querySelector('textarea[name="komentar"]').value;
        
        if (!komentar.trim()) {
            alert('Alasan penolakan wajib diisi!');
            return;
        }
        
        fetch(`/adminsarpras/peminjaman/${id}/reject`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ komentar })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) { location.reload(); } 
            else { alert(data.message || 'Gagal menolak!'); }
        })
        .catch(err => { console.error('Error:', err); alert('Terjadi kesalahan.'); });
    });

    // Auto hide toast
    setTimeout(() => {
        const toasts = document.querySelectorAll('#errorToast, #successToast');
        toasts.forEach(toast => toast.remove());
    }, 5000);

    // ✅ BUKA MODAL UPLOAD SURAT
    window.openUploadModal = function(id) {
        const form = document.getElementById('uploadForm');
        // Sesuaikan route action dengan struktur route web.php Anda
        form.action = `/adminsarpras/peminjaman-gedung/${id}/upload-surat`; 
        document.getElementById('uploadModal').classList.add('open');
    }
});
</script>
</body>
</html>
