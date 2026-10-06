<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SIPANDU - Peminjaman Kendaraan</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  
  <style>
    :root { --primary: #2563eb; --primary-light: #3b82f6; --accent: #06b6d4; --accent2: #8b5cf6; --success: #10b981; --warning: #f59e0b; --danger: #ef4444; --bg: #f0f4ff; --sidebar-bg: #0f172a; --sidebar-text: #94a3b8; --card-bg: #ffffff; --text-primary: #0f172a; --text-secondary: #64748b; --border: #e2e8f0; --radius: 16px; --radius-sm: 10px; --shadow: 0 4px 24px rgba(37, 99, 235, 0.08); --shadow-lg: 0 8px 40px rgba(37, 99, 235, 0.14); }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text-primary); display: flex; min-height: 100vh; overflow-x: hidden; }
    
    /* SIDEBAR */
    .sidebar { width: 260px; background: var(--sidebar-bg); height: 100vh; position: fixed; left: 0; top: 0; padding: 24px 16px; overflow-y: auto; z-index: 1000; }
    .sidebar-logo { font-size: 20px; font-weight: 800; color: #fff; margin-bottom: 32px; display: flex; align-items: center; gap: 8px; }
    .sidebar-menu { display: flex; flex-direction: column; gap: 8px; }
    .sidebar-item { padding: 12px 14px; border-radius: 10px; color: var(--sidebar-text); cursor: pointer; font-size: 13px; font-weight: 600; transition: all .2s; display: flex; align-items: center; gap: 10px; }
    .sidebar-item:hover { background: rgba(255, 255, 255, 0.08); color: #fff; }
    .sidebar-item.active { background: var(--primary); color: #fff; }
    .sidebar-toggle { display: none; position: fixed; top: 20px; left: 20px; width: 40px; height: 40px; background: var(--card-bg); border: 1px solid var(--border); border-radius: 10px; cursor: pointer; z-index: 1100; align-items: center; justify-content: center; }
    
    /* MAIN & TOPBAR */
    .main { margin-left: 260px; flex: 1; padding: 0 32px 40px; width: calc(100% - 260px); }
    .topbar { display: flex; align-items: center; justify-content: space-between; padding: 20px 0 24px; position: sticky; top: 0; z-index: 50; background: var(--bg); border-bottom: 1px solid transparent; flex-wrap: wrap; gap: 16px; }
    .topbar-left { display: flex; align-items: center; gap: 14px; flex: 1; min-width: 0; }
    .breadcrumb { font-size: 13px; color: var(--text-secondary); display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
    .breadcrumb span { color: var(--primary); font-weight: 600; }
    .topbar-title { font-family: 'Space Grotesk', sans-serif; font-size: 22px; font-weight: 700; white-space: nowrap; }
    .topbar-right { display: flex; align-items: center; gap: 12px; }
    .notif-btn { width: 40px; height: 40px; background: var(--card-bg); border: 1px solid var(--border); border-radius: 10px; display: grid; place-items: center; cursor: pointer; position: relative; color: var(--text-secondary); transition: all .2s; }
    .notif-btn:hover { border-color: var(--primary); color: var(--primary); }
    .notif-dot { position: absolute; top: 8px; right: 8px; width: 7px; height: 7px; background: var(--danger); border-radius: 50%; border: 1.5px solid var(--card-bg); }
    
    /* LAYOUT & FORM */
    .content-grid { display: grid; grid-template-columns: 1fr 1.4fr; gap: 28px; }
    .form-card { background: var(--card-bg); border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden; position: sticky; top: 90px; height: fit-content; }
    .form-header { padding: 24px 28px 20px; background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%); position: relative; overflow: hidden; }
    .form-header::before { content: ''; position: absolute; right: -30px; top: -30px; width: 120px; height: 120px; border-radius: 50%; background: rgba(255, 255, 255, 0.08); }
    .form-header-icon { position: relative; z-index: 1; width: 46px; height: 46px; background: rgba(255, 255, 255, 0.2); border-radius: 13px; display: grid; place-items: center; font-size: 20px; color: #fff; margin-bottom: 12px; backdrop-filter: blur(8px); border: 1px solid rgba(255, 255, 255, 0.2); }
    .form-header-title { font-family: 'Space Grotesk', sans-serif; font-size: 18px; font-weight: 700; color: #fff; }
    .form-header-sub { font-size: 12px; color: rgba(255, 255, 255, 0.75); margin-top: 4px; }
    .form-body { padding: 24px 28px; }
    .form-group { margin-bottom: 18px; }
    .form-label { display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: .6px; margin-bottom: 8px; }
    .form-label i { color: var(--primary); font-size: 11px; }
    .form-label .req { color: var(--danger); }
    .form-input, .form-select, .form-textarea { width: 100%; padding: 11px 14px; border: 1.5px solid var(--border); border-radius: 10px; font-size: 13px; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text-primary); background: #fff; transition: all .2s; outline: none; }
    .form-input:focus, .form-textarea:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }
    .form-input::placeholder, .form-textarea::placeholder { color: #b0bcd4; }
    .form-select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 14px center; padding-right: 36px; cursor: pointer; }
    .form-textarea { resize: vertical; min-height: 90px; }
    .input-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .facility-preview { display: none; margin-top: 8px; padding: 12px 14px; border-radius: 10px; background: linear-gradient(135deg, #eff4ff, #f0fdff); border: 1px solid #c7d7ff; }
    .facility-preview.show { display: flex; align-items: center; gap: 12px; }
    .fp-icon { width: 36px; height: 36px; border-radius: 9px; background: var(--primary); display: grid; place-items: center; color: #fff; font-size: 15px; flex-shrink: 0; }
    .fp-name { font-size: 13px; font-weight: 700; color: var(--text-primary); }
    .fp-details { display: flex; gap: 10px; margin-top: 3px; }
    .fp-tag { font-size: 10px; background: rgba(37, 99, 235, 0.1); color: var(--primary); padding: 2px 8px; border-radius: 5px; font-weight: 600; }
    .submit-btn { width: 100%; padding: 13px; background: linear-gradient(135deg, var(--primary), var(--primary-light)); color: #fff; border: none; border-radius: 11px; font-size: 14px; font-weight: 700; font-family: 'Plus Jakarta Sans', sans-serif; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all .2s; box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35); margin-top: 8px; }
    .submit-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4); }
    .submit-btn:active { transform: translateY(0); }
    
    /* Kustomisasi Select2 Tema SIPANDU */
    .select2-container .select2-selection--single { height: 42px !important; border: 1.5px solid var(--border) !important; border-radius: 10px !important; font-family: 'Plus Jakarta Sans', sans-serif !important; font-size: 13px !important; display: flex; align-items: center; transition: all .2s; }
    .select2-container--default .select2-selection--single .select2-selection__rendered { color: var(--text-primary) !important; padding-left: 14px !important; line-height: normal !important; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 40px !important; right: 10px !important; }
    .select2-dropdown { border: 1.5px solid var(--primary) !important; border-radius: 10px !important; font-family: 'Plus Jakarta Sans', sans-serif !important; font-size: 13px !important; box-shadow: var(--shadow-lg) !important; }
    .select2-container--default.select2-container--focus .select2-selection--single { border-color: var(--primary) !important; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1) !important; }
    .select2-container--default .select2-search--dropdown .select2-search__field { border-radius: 6px !important; border: 1px solid var(--border) !important; padding: 8px 12px !important; }
    
    /* RIWAYAT CARD & LIST */
    .history-card { background: var(--card-bg); border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden; }
    .history-header { padding: 22px 28px 18px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border); flex-wrap: wrap; gap: 16px; }
    .history-title { font-family: 'Space Grotesk', sans-serif; font-size: 17px; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 8px; }
    .history-title i { color: var(--primary); }
    .req-list { padding: 20px 28px; display: flex; flex-direction: column; gap: 16px; }
    .req-card { border: 1.5px solid var(--border); border-radius: 14px; overflow: hidden; transition: all .2s; }
    .req-card:hover { box-shadow: var(--shadow); transform: translateY(-1px); }
    .req-card-top { padding: 16px 18px; display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
    .req-card-icon { width: 42px; height: 42px; border-radius: 11px; display: grid; place-items: center; font-size: 17px; flex-shrink: 0; background: rgba(37, 99, 235, 0.1); color: #2563eb; }
    .req-card-name { font-size: 14px; font-weight: 700; color: var(--text-primary); }
    .status-badge { font-size: 11px; font-weight: 700; padding: 4px 11px; border-radius: 7px; letter-spacing: .3px; display: flex; align-items: center; gap: 5px; white-space: nowrap; }
    .status-badge.approved, .status-badge.disetujui, .status-badge.disetujui_admin { background: rgba(16, 185, 129, 0.1); color: var(--success); border: 1px solid rgba(16, 185, 129, 0.2); }
    .status-badge.pending, .status-badge.diteruskan_kasubag { background: rgba(245, 158, 11, 0.1); color: var(--warning); border: 1px solid rgba(245, 158, 11, 0.2); }
    .status-badge.rejected, .status-badge.ditolak { background: rgba(239, 68, 68, 0.1); color: var(--danger); border: 1px solid rgba(239, 68, 68, 0.2); }
    .status-badge i { font-size: 9px; }
    .req-card-meta { padding: 12px 18px; background: #f8faff; display: grid; grid-template-columns: 1fr 1fr; gap: 10px; border-top: 1px solid #eef1ff; }
    .meta-label { font-size: 10px; text-transform: uppercase; letter-spacing: .6px; color: #94a3b8; font-weight: 700; margin-bottom: 3px; }
    .meta-value { font-size: 12px; font-weight: 600; color: var(--text-primary); }
    .req-card-footer { padding: 12px 18px; display: flex; gap: 8px; border-top: 1px solid #eef1ff; }
    .card-btn { flex: 1; padding: 9px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; border: none; display: flex; align-items: center; justify-content: center; gap: 6px; font-family: 'Plus Jakarta Sans', sans-serif; transition: all .2s; white-space: nowrap; text-decoration: none; }
    .card-btn.detail { background: rgba(37, 99, 235, 0.08); color: var(--primary); }
    .card-btn.detail:hover { background: rgba(37, 99, 235, 0.15); }
    .card-btn.cancel { background: rgba(239, 68, 68, 0.08); color: var(--danger); }
    .card-btn.cancel:hover { background: rgba(239, 68, 68, 0.15); }
    .empty-state { text-align: center; padding: 60px 20px; }
    .empty-icon { font-size: 56px; color: #dde5f9; margin-bottom: 14px; }
    .empty-text { font-size: 15px; font-weight: 600; color: var(--text-primary); margin-bottom: 6px; }
    .empty-sub { font-size: 13px; color: var(--text-secondary); }
    
    /* RESPONSIVE DESIGN */
    @media (max-width: 1024px) { .content-grid { grid-template-columns: 1fr; gap: 24px; } .form-card { position: static; top: auto; } .main { padding: 0 24px 40px; margin-left: 260px; width: calc(100% - 260px); } }
    @media (max-width: 768px) { .sidebar { width: 200px; padding: 16px 12px; } .main { margin-left: 200px; width: calc(100% - 200px); padding: 0 20px 40px; } .topbar { padding: 16px 0 20px; flex-direction: column; align-items: flex-start; gap: 12px; } }
    @media (max-width: 480px) { .main { margin-left: 0; width: 100%; padding: 60px 16px 40px; } .input-row { grid-template-columns: 1fr; } .req-card-top { flex-direction: column; } .req-card-meta { grid-template-columns: 1fr; } .req-card-footer { flex-direction: column; } }
    @media (min-width: 1440px) { .main { padding: 0 48px 40px; } .content-grid { gap: 32px; } }
  </style>
</head>

<body>

  @include('partials.sidebar')

  <main class="main">
    <div class="topbar">
      <div class="topbar-left">
        <div>
          <div class="breadcrumb">
            <a href="#" style="text-decoration:none;color:var(--text-secondary)">Dashboard</a>
            <i class="fas fa-chevron-right" style="font-size:10px"></i>
            <span>Peminjaman Kendaraan</span>
          </div>
          <div class="topbar-title">Peminjaman Kendaraan Operasional</div>
        </div>
      </div>
    </div>

    <div class="content-grid">
      <div class="form-card">
        <div class="form-header">
          <div class="form-header-icon"><i class="fas fa-car-side"></i></div>
          <div class="form-header-title">Buat Permintaan Peminjaman</div>
          <div class="form-header-sub">Isi formulir peminjaman kendaraan di bawah ini</div>
        </div>

        <form action="{{ route('pegawai.peminjaman-kendaraan.store') }}" method="POST" id="peminjamanForm">
          @csrf
          <div class="form-body">

            @if(session('success'))
            <div style="background: rgba(16,185,129,0.1); color: var(--success); padding: 12px; border-radius: 8px; margin-bottom: 16px; font-size: 13px; border: 1px solid rgba(16,185,129,0.2);">
              <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div style="background: rgba(239,68,68,0.1); color: var(--danger); padding: 12px; border-radius: 8px; margin-bottom: 16px; font-size: 13px; border: 1px solid rgba(239,68,68,0.2);">
              <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
            </div>
            @endif
            @if($errors->any())
            <div style="background: rgba(239,68,68,0.1); color: var(--danger); padding: 12px; border-radius: 8px; margin-bottom: 16px; font-size: 13px; border: 1px solid rgba(239,68,68,0.2);">
              <i class="fas fa-exclamation-triangle"></i> {{ $errors->first() }}
            </div>
            @endif

            <div style="font-size: 12px; color: var(--danger); background: rgba(239, 68, 68, 0.1); padding: 8px 12px; border-radius: 8px; margin-bottom: 14px; border: 1px solid rgba(239, 68, 68, 0.2); font-weight: 500;">
              <i class="fas fa-info-circle"></i> <b>Perhatian:</b> Pengajuan peminjaman kendaraan wajib dilakukan maksimal H-1. Anda tidak bisa memilih tanggal hari ini.
            </div>

            <div class="form-group">
              <div class="form-label"><i class="fas fa-search"></i> Pilih Kendaraan <span class="req">*</span></div>
              <select class="form-select" name="kode_barang" id="kendaraanSelect" required>
                <option value="">-- Ketik untuk mencari kendaraan... --</option>
                @foreach($kendaraan as $k)
                <!-- Tambahkan atribut data-nopol, data-bpkb, data-rangka, data-mesin -->
                <option value="{{ $k->kode_barang }}"
                  data-nama="{{ $k->nama_barang }}"
                  data-merek="{{ $k->merek }}"
                  data-nup="{{ $k->nup }}"
                  data-nopol="{{ $k->detailKendaraan->nomor_polisi ?? '-' }}"
                  data-bpkb="{{ $k->detailKendaraan->no_bpkb ?? '-' }}"
                  data-rangka="{{ $k->detailKendaraan->nomor_rangka ?? '-' }}"
                  data-mesin="{{ $k->detailKendaraan->nomor_mesin ?? '-' }}">
                  {{ $k->nama_barang }} ({{ $k->merek }}) - Plat: {{ $k->detailKendaraan->nomor_polisi ?? 'Belum ada plat' }}
                </option>
                @endforeach
              </select>
            </div>

            <div class="facility-preview" id="previewKendaraan">
              <div class="fp-icon"><i class="fas fa-car"></i></div>
              <div>
                <div class="fp-name" id="previewNama">-</div>
                <div class="fp-details">
                  <span class="fp-tag" style="background:rgba(16,185,129,0.1);color:var(--success)"><i class="fas fa-check-circle"></i> Tersedia</span>
                </div>
              </div>
            </div>

            <div class="input-row" style="margin-top: 14px;">
              <div class="form-group">
                <div class="form-label"><i class="fas fa-tags"></i> Merek Kendaraan</div>
                <input type="text" class="form-input" id="merekInput" placeholder="Otomatis terisi..." readonly style="background: #f8fafc; cursor: not-allowed; color: var(--text-secondary);">
              </div>
              <div class="form-group">
                <div class="form-label"><i class="fas fa-barcode"></i> NUP</div>
                <input type="text" class="form-input" name="nup" id="nupInput" placeholder="Otomatis terisi..." readonly style="background: #f8fafc; cursor: not-allowed; color: var(--text-secondary);">
              </div>
            </div>

            <!-- BARIS BARU UNTUK NOPOL DAN BPKB -->
            <div class="input-row">
              <div class="form-group">
                <div class="form-label"><i class="fas fa-id-card"></i> Nomor Polisi</div>
                <input type="text" class="form-input" id="nopolInput" placeholder="Otomatis terisi..." readonly style="background: #f8fafc; cursor: not-allowed; color: var(--text-secondary);">
              </div>
              <div class="form-group">
                <div class="form-label"><i class="fas fa-file-alt"></i> Nomor BPKB</div>
                <input type="text" class="form-input" id="bpkbInput" placeholder="Otomatis terisi..." readonly style="background: #f8fafc; cursor: not-allowed; color: var(--text-secondary);">
              </div>
            </div>

            <!-- BARIS BARU UNTUK NOMOR RANGKA DAN MESIN -->
            <div class="input-row">
              <div class="form-group">
                <div class="form-label"><i class="fas fa-cogs"></i> Nomor Rangka</div>
                <input type="text" class="form-input" id="rangkaInput" placeholder="Otomatis terisi..." readonly style="background: #f8fafc; cursor: not-allowed; color: var(--text-secondary);">
              </div>
              <div class="form-group">
                <div class="form-label"><i class="fas fa-wrench"></i> Nomor Mesin</div>
                <input type="text" class="form-input" id="mesinInput" placeholder="Otomatis terisi..." readonly style="background: #f8fafc; cursor: not-allowed; color: var(--text-secondary);">
              </div>
            </div>

            <div class="form-group">
              <div class="form-label"><i class="fas fa-cubes"></i> Jumlah Unit <span class="req">*</span></div>
              <input type="number" class="form-input" name="jumlah" value="1" min="1" placeholder="Masukkan jumlah unit" required>
            </div>

            <div class="input-row">
              <div class="form-group">
                <div class="form-label"><i class="fas fa-calendar"></i> Tgl Pinjam <span class="req">*</span></div>
                <input type="date" class="form-input" name="tanggal_peminjaman" id="tglPinjam" min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}" required>
              </div>
              <div class="form-group">
                <div class="form-label"><i class="fas fa-calendar-check"></i> Tgl Kembali <span class="req">*</span></div>
                <input type="date" class="form-input" name="tanggal_pengembalian" id="tglKembali" min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}" required>
              </div>
            </div>

            <div class="form-group">
              <div class="form-label"><i class="fas fa-bullseye"></i> Tujuan Penggunaan <span class="req">*</span></div>
              <textarea class="form-textarea" name="deskripsi_peruntukan" placeholder="Contoh: Operasional Monev..." required></textarea>
            </div>

            <button type="submit" class="submit-btn" id="btnSubmit">
              <i class="fas fa-paper-plane"></i> Kirim Permintaan Peminjaman
            </button>
          </div>
        </form>
      </div>

      <div>
        <div class="history-card">
          <div class="history-header">
            <div class="history-title"><i class="fas fa-clock-rotate-left"></i> Riwayat Peminjaman Anda</div>
          </div>
          <div class="req-list">

            @forelse($riwayat as $item)
            <div class="req-card">
              <div class="req-card-top">
                <div style="display:flex;align-items:center;gap:12px;flex:1;">
                  <div class="req-card-icon"><i class="fas fa-car"></i></div>
                  <div>
                    <div class="req-card-name">{{ $item->nama_barang }}</div>
                    <div style="font-size:11px; color:var(--text-secondary); margin-top:2px;">
                      Kode: {{ $item->kode_barang }} | NUP: {{ $item->nup ?? '-' }}
                    </div>
                  </div>
                </div>

                @php
                $badgeClass = 'pending'; $icon = 'fa-clock'; $statusText = 'Menunggu Admin';
                if($item->status == 'disetujui' || $item->status == 'disetujui_admin') { $badgeClass = 'approved'; $icon = 'fa-check'; $statusText = 'Disetujui'; }
                elseif($item->status == 'ditolak') { $badgeClass = 'rejected'; $icon = 'fa-times'; $statusText = 'Ditolak'; }
                elseif($item->status == 'diteruskan_kasubag') { $badgeClass = 'pending'; $icon = 'fa-eye'; $statusText = 'Di teruskan kekasubag'; }
                elseif($item->status == 'pending') { $badgeClass = 'pending'; $icon = 'fa-clock'; $statusText = 'Menunggu Admin'; }
                elseif($item->status == 'dibatalkan') { $badgeClass = 'rejected'; $icon = 'fa-ban'; $statusText = 'Dibatalkan'; }
                @endphp

                <div class="status-badge {{ $badgeClass }}"><i class="fas {{ $icon }}"></i> {{ $statusText }}</div>
              </div>

              <div class="req-card-meta">
                <div class="meta-item">
                  <div class="meta-label">Merek</div>
                  <div class="meta-value">{{ $item->merek ?? '-' }}</div>
                </div>
                <div class="meta-item">
                  <div class="meta-label">Jumlah</div>
                  <div class="meta-value">{{ $item->jumlah }} Unit</div>
                </div>
                <div class="meta-item">
                  <div class="meta-label">Tgl Pengajuan</div>
                  <div class="meta-value">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</div>
                </div>
                <div class="meta-item">
                  <div class="meta-label">Durasi</div>
                  <div class="meta-value">{{ \Carbon\Carbon::parse($item->tanggal_peminjaman)->format('d M') }} – {{ \Carbon\Carbon::parse($item->tanggal_pengembalian)->format('d M Y') }}</div>
                </div>
              </div>

              @if(in_array($item->status, ['disetujui', 'disetujui_admin', 'disetujui_kasubag']))
              <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 10px 14px; margin: 10px 0; display: flex; align-items: flex-start; gap: 10px; font-size: 12.5px; color: #166534;">
                <i class="fas fa-check-circle" style="color: #16a34a; font-size: 16px; margin-top: 2px;"></i>
                <div>
                  <strong>Peminjaman Kendaraan Telah Disetujui!</strong>
                  <div style="margin-top: 2px; color: #15803d; line-height: 1.4;">
                    Silakan mengambil kunci armada dan dokumen Surat Izin Jalan di Subbagian Umum / Pengelola Kendaraan Dinas.
                  </div>
                </div>
              </div>
              @endif

              <div class="req-card-footer">
                <button class="card-btn detail" onclick="showDetail({{ $item->id }})">
                  <i class="fas fa-eye"></i> Detail
                </button>

                @if($item->status == 'pending')
                <button class="card-btn cancel" onclick="cancelPeminjamanKendaraan({{ $item->id }}, this)">
                  <i class="fas fa-xmark"></i> Batalkan
                </button>
                @endif

                @if(!empty($item->surat_bast_path))
                <a href="{{ asset('storage/' . $item->surat_bast_path) }}" target="_blank" class="card-btn" style="background: rgba(16,185,129,0.1); color: var(--success);">
                  <i class="fas fa-file-contract"></i> Unduh BAST
                </a>
                @endif
              </div>
            </div>
            @empty
            <div class="empty-state">
              <div class="empty-icon"><i class="fas fa-car-side"></i></div>
              <div class="empty-text">Belum ada riwayat peminjaman</div>
              <div class="empty-sub">Ajukan peminjaman kendaraan di form sebelah kiri.</div>
            </div>
            @endforelse

          </div>
        </div>
      </div>

      <div class="modal-overlay" id="detailModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
        <div class="modal-content" style="background: #fff; width: 100%; max-width: 500px; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
          <div style="padding: 20px 24px; background: var(--primary); color: white; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 16px;"><i class="fas fa-car"></i> Detail Peminjaman</h3>
            <i class="fas fa-times" style="cursor: pointer;" onclick="document.getElementById('detailModal').style.display='none'"></i>
          </div>
          <div style="padding: 24px;" id="detailBody">
            <div id="detailLoading" style="text-align: center; color: var(--text-secondary);"><i class="fas fa-spinner fa-spin fa-2x"></i>
              <p style="margin-top:10px; font-size:13px;">Memuat...</p>
            </div>

            <div id="detailContent" style="display: none;">
              <table style="width: 100%; font-size: 13px; line-height: 2;">
                <tr>
                  <td style="color: var(--text-secondary); width: 40%;">Kendaraan</td>
                  <td style="font-weight: bold;" id="detBarang">-</td>
                </tr>
                <tr>
                  <td style="color: var(--text-secondary);">Kode / NUP</td>
                  <td id="detKode">-</td>
                </tr>
                <tr>
                  <td style="color: var(--text-secondary);">Merek</td>
                  <td id="detMerek">-</td>
                </tr>
                <tr>
                  <td style="color: var(--text-secondary);">Jumlah</td>
                  <td id="detJumlah">-</td>
                </tr>
                <tr>
                  <td style="color: var(--text-secondary);">Tgl Pinjam - Kembali</td>
                  <td id="detTanggal">-</td>
                </tr>
                <tr>
                  <td style="color: var(--text-secondary);">Tujuan</td>
                  <td id="detTujuan">-</td>
                </tr>
                <tr>
                  <td style="color: var(--text-secondary);">Status</td>
                  <td style="font-weight: bold;" id="detStatus">-</td>
                </tr>
              </table>
            </div>
          </div>
          <div style="padding: 16px 24px; background: #f8fafc; text-align: right; border-top: 1px solid var(--border);">
            <button onclick="document.getElementById('detailModal').style.display='none'" style="padding: 8px 16px; border: 1px solid var(--border); background: white; border-radius: 8px; cursor: pointer; font-weight: 600;">Tutup</button>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <script>
    $(document).ready(function() {
      // Inisialisasi Select2
      $('#kendaraanSelect').select2({
        placeholder: "-- Ketik untuk mencari kendaraan... --",
        allowClear: true,
        width: '100%'
      });

      // Sinkronisasi Select2 dengan fungsi update detail
      $('#kendaraanSelect').on('select2:select select2:clear', function (e) {
        updateDetailKendaraan();
      });
    });

    function updateDetailKendaraan() {
      const select = document.getElementById('kendaraanSelect');
      const selected = select.options[select.selectedIndex];
      const previewBox = document.getElementById('previewKendaraan');

      const merekInput = document.getElementById('merekInput');
      const nupInput = document.getElementById('nupInput');
      
      // Definisikan elemen input baru
      const nopolInput = document.getElementById('nopolInput');
      const bpkbInput = document.getElementById('bpkbInput');
      const rangkaInput = document.getElementById('rangkaInput');
      const mesinInput = document.getElementById('mesinInput');

      if (select.value !== "") {
        document.getElementById('previewNama').innerText = selected.getAttribute('data-nama');
        
        merekInput.value = selected.getAttribute('data-merek') || '-';
        nupInput.value = selected.getAttribute('data-nup') || '-';
        
        // Memasukkan value dari atribut data-* ke kolom input
        nopolInput.value = selected.getAttribute('data-nopol') || '-';
        bpkbInput.value = selected.getAttribute('data-bpkb') || '-';
        rangkaInput.value = selected.getAttribute('data-rangka') || '-';
        mesinInput.value = selected.getAttribute('data-mesin') || '-';

        previewBox.style.display = 'flex';
      } else {
        previewBox.style.display = 'none';
        merekInput.value = '';
        nupInput.value = '';
        
        // Mengosongkan kolom saat opsi dibatalkan
        nopolInput.value = '';
        bpkbInput.value = '';
        rangkaInput.value = '';
        mesinInput.value = '';
      }
    }

    document.getElementById('peminjamanForm').addEventListener('submit', function() {
      const btn = document.getElementById('btnSubmit');
      btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
      btn.style.opacity = '0.7';
      btn.disabled = true;
    });

    // SINKRONISASI JAVASCRIPT VALIDASI TANGGAL MINIMAL BESOK (H-1)
    document.addEventListener('DOMContentLoaded', function() {
      const tomorrow = new Date();
      tomorrow.setDate(tomorrow.getDate() + 1);
      const minDate = tomorrow.toISOString().split('T')[0];
      
      if (document.getElementById('tglPinjam')) document.getElementById('tglPinjam').min = minDate;
      if (document.getElementById('tglKembali')) document.getElementById('tglKembali').min = minDate;
    });

    function showDetail(id) {
      const modal = document.getElementById('detailModal');
      const loading = document.getElementById('detailLoading');
      const content = document.getElementById('detailContent');

      modal.style.display = 'flex';
      loading.style.display = 'block';
      content.style.display = 'none';

      fetch(`/pegawai/peminjaman-kendaraan/${id}/show`)
        .then(response => response.json())
        .then(res => {
          if (res.success) {
            let data = res.data;
            document.getElementById('detBarang').innerText = data.nama_barang;
            document.getElementById('detKode').innerText = data.kode_barang + ' / ' + (data.nup || '-');
            document.getElementById('detMerek').innerText = data.merek || '-';
            
            let jml = data.jumlah ? data.jumlah : '1';
            document.getElementById('detJumlah').innerText = jml + ' Unit';

            let tglPinjam = new Date(data.tanggal_peminjaman).toLocaleDateString('id-ID');
            let tglKembali = new Date(data.tanggal_pengembalian).toLocaleDateString('id-ID');
            document.getElementById('detTanggal').innerText = tglPinjam + ' s/d ' + tglKembali;

            document.getElementById('detTujuan').innerText = data.deskripsi_peruntukan;
            document.getElementById('detStatus').innerText = data.status.toUpperCase().replace(/_/g, ' ');

            loading.style.display = 'none';
            content.style.display = 'block';
          }
        })
        .catch(err => {
          alert("Gagal mengambil data detail.");
          modal.style.display = 'none';
        });
    }

    function cancelPeminjamanKendaraan(id, btnElement) {
      if (!confirm('Apakah Anda yakin ingin membatalkan peminjaman kendaraan ini?')) return;

      const originalText = btnElement.innerHTML;
      btnElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Batal...';
      btnElement.disabled = true;

      fetch(`/pegawai/peminjaman-kendaraan/${id}/cancel`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const card = btnElement.closest('.req-card');
            const statusBadge = card.querySelector('.status-badge');
            if (statusBadge) {
                statusBadge.className = 'status-badge rejected';
                statusBadge.innerHTML = '<i class="fas fa-ban"></i> Dibatalkan';
            }
            btnElement.remove();
            if (typeof showToast === 'function') showToast('Peminjaman kendaraan berhasil dibatalkan!', 'success');
            else alert('Peminjaman kendaraan berhasil dibatalkan!');
          } else {
            alert(data.message || 'Gagal membatalkan peminjaman.');
            btnElement.innerHTML = originalText;
            btnElement.disabled = false;
          }
        })
        .catch(error => {
          alert('Terjadi kesalahan saat membatalkan peminjaman.');
          btnElement.innerHTML = originalText;
          btnElement.disabled = false;
        });
    } 
  </script>

</body>
</html>