<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SIPANDU - Kelola & Backup Data Inventaris</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
  <style>
    :root {
      --primary: #3b5bdb;
      --primary-dark: #2f4ac7;
      --primary-light: #eef2ff;
      --sidebar-bg: #1e2a4a;
      --text: #0f172a;
      --muted: #64748b;
      --border: #e2e8f0;
      --card-bg: #ffffff;
      --bg: #f8fafc;
      --success: #10b981;
      --danger: #ef4444;
      --warning: #f59e0b;
      --purple: #8b5cf6;
      --orange: #f97316;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); display: flex; min-height: 100vh; }
    
    .main { margin-left: 260px; flex: 1; padding: 0 32px 40px; }
    .topbar { display: flex; align-items: center; justify-content: space-between; padding: 20px 0 24px; position: sticky; top: 0; z-index: 50; background: var(--bg); }
    .page-title { font-family: 'Space Grotesk', sans-serif; font-size: 24px; font-weight: 700; color: var(--text); }
    .page-sub { font-size: 13.5px; color: var(--muted); margin-top: 4px; }
    .avatar-top { width: 42px; height: 42px; background: linear-gradient(135deg, var(--primary), #6366f1); border-radius: 12px; display: grid; place-items: center; color: #fff; font-weight: 700; font-size: 14px; box-shadow: 0 4px 12px rgba(59, 91, 219, 0.25); }

    /* ALERT */
    .alert { padding: 14px 18px; border-radius: 12px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; font-size: 14px; font-weight: 500; }
    .alert-success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
    .alert-danger { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

    /* STATS SUMMARY */
    .section-title { font-family: 'Space Grotesk', sans-serif; font-size: 17px; font-weight: 700; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; color: var(--text); }
    .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 28px; }
    .stat-card { background: var(--card-bg); border-radius: 16px; padding: 22px; border: 1px solid var(--border); box-shadow: 0 4px 18px rgba(0,0,0,0.03); transition: transform .2s; }
    .stat-card:hover { transform: translateY(-2px); }
    .stat-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
    .stat-badge { font-size: 11.5px; font-weight: 700; padding: 4px 10px; border-radius: 20px; text-transform: uppercase; }
    .stat-icon { width: 46px; height: 46px; border-radius: 12px; display: grid; place-items: center; font-size: 20px; flex-shrink: 0; }
    .stat-num { font-size: 26px; font-weight: 800; line-height: 1.1; margin-bottom: 4px; }
    .stat-label { font-size: 13px; color: var(--muted); font-weight: 600; }
    .stat-details { margin-top: 14px; padding-top: 12px; border-top: 1px dashed var(--border); font-size: 12px; color: var(--muted); display: flex; flex-direction: column; gap: 4px; }

    /* BACKUP SECTION */
    .backup-panel { background: var(--card-bg); border-radius: 18px; padding: 24px; border: 1px solid var(--border); box-shadow: 0 4px 20px rgba(0,0,0,0.03); margin-bottom: 32px; }
    .backup-banner { background: linear-gradient(135deg, #eff6ff, #eef2ff); border: 1.5px solid #bfdbfe; border-radius: 14px; padding: 20px; display: flex; align-items: center; justify-content: space-between; gap: 20px; margin-bottom: 22px; }
    .backup-banner-info h4 { font-size: 16px; font-weight: 700; color: #1e3a8a; margin-bottom: 4px; display: flex; align-items: center; gap: 8px; }
    .backup-banner-info p { font-size: 13px; color: #3b82f6; }
    .btn-backup-full { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; border: none; padding: 13px 26px; border-radius: 12px; font-weight: 700; font-size: 14px; display: inline-flex; align-items: center; gap: 10px; cursor: pointer; box-shadow: 0 4px 16px rgba(37, 99, 235, 0.3); transition: all .2s; text-decoration: none; }
    .btn-backup-full:hover { background: linear-gradient(135deg, #1d4ed8, #1e40af); transform: translateY(-2px); box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4); }

    .category-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; }
    .category-card { background: #f8fafc; border: 1px solid var(--border); border-radius: 14px; padding: 18px; display: flex; flex-direction: column; justify-content: space-between; gap: 14px; }
    .cat-head { display: flex; align-items: center; gap: 12px; }
    .cat-head-icon { width: 38px; height: 38px; border-radius: 10px; display: grid; place-items: center; font-size: 16px; }
    .cat-head-title { font-weight: 700; font-size: 14.5px; }
    .cat-head-desc { font-size: 12px; color: var(--muted); }
    .cat-actions { display: flex; gap: 8px; }
    .btn-download-sm { flex: 1; padding: 8px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 6px; border: 1px solid var(--border); background: #fff; color: var(--text); text-decoration: none; cursor: pointer; transition: all .15s; }
    .btn-download-sm:hover { background: #f1f5f9; border-color: #cbd5e1; }

    /* DANGER ZONE (HAPUS DATA) */
    .danger-panel { background: #fff; border-radius: 18px; padding: 24px; border: 1.5px solid #fecaca; box-shadow: 0 4px 20px rgba(239, 68, 68, 0.05); }
    .danger-header { display: flex; align-items: center; gap: 12px; margin-bottom: 8px; color: #dc2626; }
    .danger-header h3 { font-family: 'Space Grotesk', sans-serif; font-size: 18px; font-weight: 700; }
    .danger-sub { font-size: 13.5px; color: var(--muted); margin-bottom: 22px; }

    .delete-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; margin-bottom: 22px; }
    .delete-card { border: 1px solid #fee2e2; background: #fff5f5; border-radius: 14px; padding: 18px; display: flex; flex-direction: column; justify-content: space-between; gap: 14px; }
    .del-info h5 { font-size: 14.5px; font-weight: 700; color: #991b1b; margin-bottom: 4px; display: flex; align-items: center; gap: 6px; }
    .del-info p { font-size: 12px; color: #7f1d1d; line-height: 1.4; }
    .btn-del-action { width: 100%; padding: 10px 14px; border-radius: 10px; font-size: 12.5px; font-weight: 700; border: 1px solid #f87171; background: #fee2e2; color: #b91c1c; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 6px; transition: all .2s; }
    .btn-del-action:hover { background: #fecaca; color: #991b1b; }

    /* RESET ALL BOX */
    .reset-all-box { background: linear-gradient(135deg, #fef2f2, #fff1f2); border: 2px dashed #f87171; border-radius: 14px; padding: 20px; display: flex; align-items: center; justify-content: space-between; gap: 20px; }
    .reset-all-info h4 { font-size: 16px; font-weight: 800; color: #991b1b; margin-bottom: 4px; display: flex; align-items: center; gap: 8px; }
    .reset-all-info p { font-size: 12.5px; color: #b91c1c; }
    .btn-reset-all { background: #dc2626; color: #fff; border: none; padding: 12px 24px; border-radius: 10px; font-weight: 800; font-size: 13.5px; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; box-shadow: 0 4px 14px rgba(220, 38, 38, 0.35); transition: all .2s; }
    .btn-reset-all:hover { background: #b91c1c; transform: translateY(-2px); }

    /* MODAL */
    .modal-overlay { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 1000; display: none; place-items: center; padding: 20px; }
    .modal-overlay.active { display: grid; }
    .modal { background: #fff; border-radius: 20px; width: 100%; max-width: 520px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); overflow: hidden; animation: popIn .2s ease-out; }
    @keyframes popIn { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }

    .modal-header { padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; background: #fff5f5; }
    .modal-header h3 { font-size: 17px; font-weight: 800; color: #dc2626; display: flex; align-items: center; gap: 8px; }
    .modal-body { padding: 22px 24px; }
    .modal-footer { padding: 16px 24px; border-top: 1px solid var(--border); background: #f8fafc; display: flex; justify-content: flex-end; gap: 12px; }

    .form-group { margin-bottom: 16px; }
    .form-label { display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 6px; color: var(--text); }
    .form-control { width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 10px; font-size: 13.5px; font-family: inherit; transition: border-color .15s; }
    .form-control:focus { outline: none; border-color: #ef4444; }

    .btn-cancel { padding: 10px 18px; border-radius: 10px; border: 1px solid var(--border); background: #fff; color: var(--muted); font-weight: 600; font-size: 13.5px; cursor: pointer; }
    .btn-confirm-delete { padding: 10px 22px; border-radius: 10px; border: none; background: #dc2626; color: #fff; font-weight: 700; font-size: 13.5px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: background .15s; }
    .btn-confirm-delete:hover { background: #b91c1c; }

    @media (max-width: 992px) {
      .stats-grid, .category-grid, .delete-grid { grid-template-columns: 1fr; }
      .main { margin-left: 0; padding: 16px; padding-top: 60px; }
      .backup-banner, .reset-all-box { flex-direction: column; align-items: flex-start; }
    }
  </style>
</head>
<body>

@include('partials.sidebar')

<div class="main">
  <div class="topbar">
    <div>
      <div class="page-title">⚡ Pusat Kelola & Backup Data Inventaris</div>
      <div class="page-sub">Kelola pencadangan (backup) dan pembersihan data Aset Tetap, Persediaan, dan Barang Lainnya secara terpusat.</div>
    </div>
    <div style="display: flex; align-items: center; gap: 14px;">
      <a href="{{ route('superadmin.dashboard') }}" class="btn-download-sm" style="padding: 10px 16px; font-size: 13px;">
        <i class="fas fa-arrow-left"></i> Dashboard Operator
      </a>
      <div class="avatar-top">{{ substr(Auth::user()->name ?? 'OP', 0, 2) }}</div>
    </div>
  </div>

  {{-- ALERT MESSAGES --}}
  @if(session('success'))
    <div class="alert alert-success">
      <div style="display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-check-circle" style="font-size: 18px;"></i>
        <span>{{ session('success') }}</span>
      </div>
      <button onclick="this.parentElement.remove()" style="border:none; background:none; cursor:pointer; color:inherit;">✕</button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger">
      <div style="display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-exclamation-triangle" style="font-size: 18px;"></i>
        <span>{{ session('error') }}</span>
      </div>
      <button onclick="this.parentElement.remove()" style="border:none; background:none; cursor:pointer; color:inherit;">✕</button>
    </div>
  @endif

  {{-- ROW 1: RINGKASAN STATUS DATA INVENTARIS --}}
  <div class="section-title">
    <i class="fas fa-chart-pie" style="color: var(--primary);"></i> Ringkasan Volume Data Saat Ini
  </div>

  <div class="stats-grid">
    {{-- ASET TETAP --}}
    <div class="stat-card">
      <div class="stat-header">
        <div class="stat-icon" style="background:#e0e7ff; color:#4338ca;"><i class="fas fa-database"></i></div>
        <span class="stat-badge" style="background:#e0e7ff; color:#3730a3;">Aset Tetap</span>
      </div>
      <div class="stat-num" style="color:#3730a3;">{{ number_format($stats['aset']['total_item'] ?? 0) }}</div>
      <div class="stat-label">Total Unit Aset Terdaftar</div>
      <div class="stat-details">
        <div><strong>Total Nilai Perolehan:</strong> Rp {{ number_format($stats['aset']['total_nilai'] ?? 0, 0, ',', '.') }}</div>
        <div><strong>Transaksi Masuk / Keluar:</strong> {{ $stats['aset']['transaksi_masuk'] ?? 0 }} / {{ $stats['aset']['transaksi_keluar'] ?? 0 }}</div>
        <div><strong>Mutasi Aset:</strong> {{ $stats['aset']['mutasi'] ?? 0 }} transaksi</div>
      </div>
    </div>

    {{-- PERSEDIAAN --}}
    <div class="stat-card">
      <div class="stat-header">
        <div class="stat-icon" style="background:#dcfce7; color:#15803d;"><i class="fas fa-cubes"></i></div>
        <span class="stat-badge" style="background:#dcfce7; color:#166534;">Persediaan ATK</span>
      </div>
      <div class="stat-num" style="color:#166534;">{{ number_format($stats['persediaan']['total_item'] ?? 0) }}</div>
      <div class="stat-label">Jenis Barang Persediaan</div>
      <div class="stat-details">
        <div><strong>Total Stok Tersedia:</strong> {{ number_format($stats['persediaan']['total_unit'] ?? 0) }} unit</div>
        <div><strong>Total Nilai Persediaan:</strong> Rp {{ number_format($stats['persediaan']['total_nilai'] ?? 0, 0, ',', '.') }}</div>
        <div><strong>Permintaan Pegawai:</strong> {{ $stats['persediaan']['permintaan'] ?? 0 }} pengajuan</div>
      </div>
    </div>

    {{-- BARANG LAINNYA --}}
    <div class="stat-card">
      <div class="stat-header">
        <div class="stat-icon" style="background:#fef3c7; color:#b45309;"><i class="fas fa-plug"></i></div>
        <span class="stat-badge" style="background:#fef3c7; color:#92400e;">Barang Lainnya</span>
      </div>
      <div class="stat-num" style="color:#b45309;">{{ number_format($stats['barang_lainnya']['total_item'] ?? 0) }}</div>
      <div class="stat-label">Peralatan Listrik & Pendukung</div>
      <div class="stat-details">
        <div><strong>Total Stok Fisik:</strong> {{ number_format($stats['barang_lainnya']['total_unit'] ?? 0) }} unit</div>
        <div><strong>Total Nilai Estimasi:</strong> Rp {{ number_format($stats['barang_lainnya']['total_nilai'] ?? 0, 0, ',', '.') }}</div>
        <div><strong>Peminjaman Pegawai:</strong> {{ $stats['barang_lainnya']['peminjaman'] ?? 0 }} pengajuan</div>
      </div>
    </div>
  </div>

  {{-- PENGATURAN FITUR FIFO PERSEDIAAN --}}
  @php
    $fifoEnabled = $fifoEnabled ?? \App\Services\PersediaanBatchService::isFifoEnabled();
  @endphp
  <div class="backup-panel" style="margin-bottom: 28px; border-color: {{ $fifoEnabled ? '#a7f3d0' : '#e2e8f0' }};">
    <div style="display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
      <div style="flex: 1; min-width: 260px;">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px; flex-wrap: wrap;">
          <span style="font-family: 'Space Grotesk', sans-serif; font-size: 16.5px; font-weight: 700; color: var(--text);">
            <i class="fas fa-layer-group" style="color: var(--primary); margin-right: 6px;"></i> Pengaturan Metode FIFO Persediaan
          </span>
          @if($fifoEnabled)
            <span style="padding: 4px 12px; border-radius: 999px; background: #dcfce7; color: #166534; font-size: 11.5px; font-weight: 800;">
              ● AKTIF
            </span>
          @else
            <span style="padding: 4px 12px; border-radius: 999px; background: #f1f5f9; color: #475569; font-size: 11.5px; font-weight: 800;">
              ○ NONAKTIF (Disembunyikan)
            </span>
          @endif
        </div>
        <p style="font-size: 13px; color: var(--muted); line-height: 1.55;">
          @if($fifoEnabled)
            Mode <strong>FIFO (First-In, First-Out)</strong> sedang aktif pada Data Persediaan & Transaksi Keluar. Anda dapat menonaktifkannya kembali tanpa menghapus data batch harga yang tersimpan.
          @else
            Bagian <strong>FIFO sedang disembunyikan</strong> sesuai pengaturan standar (riwayat list harga tiap barang tetap tersimpan & tampil di Detail Persediaan). Klik tombol di kanan untuk menghidupkan kembali bagian FIFO.
          @endif
        </p>
      </div>
      <form method="POST" action="{{ route('superadmin.toggle-fifo') }}">
        @csrf
        @if($fifoEnabled)
          <button type="submit" class="btn-backup-full" style="background: linear-gradient(135deg, #64748b, #475569); box-shadow: none;">
            <i class="fas fa-eye-slash"></i> Sembunyikan / Nonaktifkan FIFO
          </button>
        @else
          <button type="submit" class="btn-backup-full" style="background: linear-gradient(135deg, #10b981, #059669); box-shadow: 0 4px 16px rgba(16, 185, 129, 0.3);">
            <i class="fas fa-power-off"></i> Hidupkan Kembali Fitur FIFO
          </button>
        @endif
      </form>
    </div>
  </div>

  {{-- ROW 2: PUSAT PENCADANGAN (BACKUP) DATA --}}
  <div class="backup-panel">
    <div class="section-title">
      <i class="fas fa-shield-alt" style="color: var(--primary);"></i> Cadangkan (Backup) Database & Data
    </div>

    <div class="backup-banner">
      <div class="backup-banner-info">
        <h4><i class="fas fa-download"></i> Unduh Cadangan Penuh (Full Database Dump)</h4>
        <p>Ekspor seluruh struktur tabel dan data SIPANDU ke dalam format <strong>.SQL</strong>. File ini dapat langsung direstore via phpMyAdmin atau MySQL jika diperlukan.</p>
      </div>
      <form method="POST" action="{{ route('superadmin.kelola-data.backup') }}">
        @csrf
        <input type="hidden" name="kategori" value="full">
        <input type="hidden" name="format" value="sql">
        <button type="submit" class="btn-backup-full">
          <i class="fas fa-file-export"></i> Unduh Full Backup (.SQL)
        </button>
      </form>
    </div>

    <div class="category-grid">
      {{-- BACKUP ASET TETAP --}}
      <div class="category-card">
        <div class="cat-head">
          <div class="cat-head-icon" style="background:#e0e7ff; color:#4338ca;"><i class="fas fa-database"></i></div>
          <div>
            <div class="cat-head-title">Backup Data Aset Tetap</div>
            <div class="cat-head-desc">Tabel aset, transaksi masuk/keluar, mutasi, peminjaman barang.</div>
          </div>
        </div>
        <div class="cat-actions">
          <form method="POST" action="{{ route('superadmin.kelola-data.backup') }}" style="flex:1;">
            @csrf
            <input type="hidden" name="kategori" value="aset">
            <input type="hidden" name="format" value="sql">
            <button type="submit" class="btn-download-sm" style="width:100%;">
              <i class="fas fa-file-code" style="color:#2563eb;"></i> Format .SQL
            </button>
          </form>
          <form method="POST" action="{{ route('superadmin.kelola-data.backup') }}" style="flex:1;">
            @csrf
            <input type="hidden" name="kategori" value="aset">
            <input type="hidden" name="format" value="json">
            <button type="submit" class="btn-download-sm" style="width:100%;">
              <i class="fas fa-file-alt" style="color:#10b981;"></i> Format .JSON
            </button>
          </form>
        </div>
      </div>

      {{-- BACKUP PERSEDIAAN --}}
      <div class="category-card">
        <div class="cat-head">
          <div class="cat-head-icon" style="background:#dcfce7; color:#15803d;"><i class="fas fa-cubes"></i></div>
          <div>
            <div class="cat-head-title">Backup Data Persediaan</div>
            <div class="cat-head-desc">Tabel persediaan, transaksi masuk/keluar, permintaan, stok opname.</div>
          </div>
        </div>
        <div class="cat-actions">
          <form method="POST" action="{{ route('superadmin.kelola-data.backup') }}" style="flex:1;">
            @csrf
            <input type="hidden" name="kategori" value="persediaan">
            <input type="hidden" name="format" value="sql">
            <button type="submit" class="btn-download-sm" style="width:100%;">
              <i class="fas fa-file-code" style="color:#2563eb;"></i> Format .SQL
            </button>
          </form>
          <form method="POST" action="{{ route('superadmin.kelola-data.backup') }}" style="flex:1;">
            @csrf
            <input type="hidden" name="kategori" value="persediaan">
            <input type="hidden" name="format" value="json">
            <button type="submit" class="btn-download-sm" style="width:100%;">
              <i class="fas fa-file-alt" style="color:#10b981;"></i> Format .JSON
            </button>
          </form>
        </div>
      </div>

      {{-- BACKUP BARANG LAINNYA --}}
      <div class="category-card">
        <div class="cat-head">
          <div class="cat-head-icon" style="background:#fef3c7; color:#b45309;"><i class="fas fa-plug"></i></div>
          <div>
            <div class="cat-head-title">Backup Barang Lainnya</div>
            <div class="cat-head-desc">Tabel stok barang lainnya & riwayat peminjaman pegawai.</div>
          </div>
        </div>
        <div class="cat-actions">
          <form method="POST" action="{{ route('superadmin.kelola-data.backup') }}" style="flex:1;">
            @csrf
            <input type="hidden" name="kategori" value="barang_lainnya">
            <input type="hidden" name="format" value="sql">
            <button type="submit" class="btn-download-sm" style="width:100%;">
              <i class="fas fa-file-code" style="color:#2563eb;"></i> Format .SQL
            </button>
          </form>
          <form method="POST" action="{{ route('superadmin.kelola-data.backup') }}" style="flex:1;">
            @csrf
            <input type="hidden" name="kategori" value="barang_lainnya">
            <input type="hidden" name="format" value="json">
            <button type="submit" class="btn-download-sm" style="width:100%;">
              <i class="fas fa-file-alt" style="color:#10b981;"></i> Format .JSON
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- ROW 3: DANGER ZONE (HAPUS & KOSONGKAN DATA) --}}
  <div class="danger-panel">
    <div class="danger-header">
      <i class="fas fa-exclamation-triangle" style="font-size: 20px;"></i>
      <h3>Zona Bahaya - Hapus & Kosongkan Data Inventaris</h3>
    </div>
    <div class="danger-sub">
      Tindakan di bawah ini bersifat <strong>permanen dan tidak dapat dibatalkan</strong>. Gunakan fitur ini jika ingin mereset data lama sebelum memulai pencatatan inventaris baru. Selalu unduh file backup di atas sebelum melakukan tindakan ini.
    </div>

    <div class="delete-grid">
      {{-- HAPUS ASET TETAP --}}
      <div class="delete-card">
        <div class="del-info">
          <h5><i class="fas fa-trash-alt"></i> Hapus Data Aset Tetap</h5>
          <p>Mengosongkan seluruh {{ number_format($stats['aset']['total_item'] ?? 0) }} item Aset Tetap beserta transaksi masuk/keluar & mutasi terkait.</p>
        </div>
        <button type="button" class="btn-del-action" onclick="openDeleteModal('aset', 'Data Aset Tetap', {{ $stats['aset']['total_item'] ?? 0 }})">
          <i class="fas fa-trash"></i> Hapus Data Aset...
        </button>
      </div>

      {{-- HAPUS PERSEDIAAN --}}
      <div class="delete-card">
        <div class="del-info">
          <h5><i class="fas fa-trash-alt"></i> Hapus Data Persediaan</h5>
          <p>Mengosongkan seluruh {{ number_format($stats['persediaan']['total_item'] ?? 0) }} jenis barang persediaan, mutasi stok, & permintaan pegawai.</p>
        </div>
        <button type="button" class="btn-del-action" onclick="openDeleteModal('persediaan', 'Data Persediaan ATK', {{ $stats['persediaan']['total_item'] ?? 0 }})">
          <i class="fas fa-trash"></i> Hapus Persediaan...
        </button>
      </div>

      {{-- HAPUS BARANG LAINNYA --}}
      <div class="delete-card">
        <div class="del-info">
          <h5><i class="fas fa-trash-alt"></i> Hapus Barang Lainnya</h5>
          <p>Mengosongkan {{ number_format($stats['barang_lainnya']['total_item'] ?? 0) }} item peralatan colokan/kabel roll & pengajuan pinjam pegawai.</p>
        </div>
        <button type="button" class="btn-del-action" onclick="openDeleteModal('barang_lainnya', 'Data Barang Lainnya', {{ $stats['barang_lainnya']['total_item'] ?? 0 }})">
          <i class="fas fa-trash"></i> Hapus Barang Lainnya...
        </button>
      </div>
    </div>

    {{-- RESET ALL --}}
    <div class="reset-all-box">
      <div class="reset-all-info">
        <h4><i class="fas fa-skull-crossbones"></i> Reset Seluruh Data Inventaris (Semua Kategori)</h4>
        <p>Mengosongkan Data Aset Tetap, Persediaan, dan Barang Lainnya sekaligus. Akun Pengguna (Users) dan Master Unit Kerja tetap aman dan tidak akan terhapus.</p>
      </div>
      <button type="button" class="btn-reset-all" onclick="openDeleteModal('all', 'SEMUA DATA INVENTARIS (Aset + Persediaan + Barang Lainnya)', {{ ($stats['aset']['total_item'] ?? 0) + ($stats['persediaan']['total_item'] ?? 0) + ($stats['barang_lainnya']['total_item'] ?? 0) }})">
        <i class="fas fa-bomb"></i> Reset Seluruh Data...
      </button>
    </div>
  </div>

</div>

{{-- MODAL KONFIRMASI HAPUS DATA --}}
<div id="modalHapusData" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <h3 id="modalHapusTitle"><i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus Data</h3>
      <button type="button" onclick="closeDeleteModal()" style="border:none; background:none; font-size:18px; cursor:pointer; color:#991b1b;">✕</button>
    </div>

    <form method="POST" action="{{ route('superadmin.kelola-data.hapus') }}" onsubmit="return validateDeleteForm()">
      @csrf
      <input type="hidden" name="kategori" id="inputKategoriHapus" value="">

      <div class="modal-body">
        <div style="background:#fee2e2; border-left:4px solid #ef4444; padding:12px 16px; border-radius:8px; margin-bottom:18px; font-size:13px; color:#991b1b;">
          <strong>PERINGATAN KERAS!</strong> Anda akan menghapus <span id="labelNamaKategori" style="font-weight:800;">-</span> dengan perkiraan volume <strong id="labelVolumeData">0</strong> item. Tindakan ini tidak dapat dipulihkan.
        </div>

        <div class="form-group">
          <label style="display:flex; align-items:center; gap:8px; font-size:13px; font-weight:600; cursor:pointer;">
            <input type="checkbox" name="include_relations" value="1" checked style="width:16px; height:16px; accent-color:#dc2626;">
            <span>Sertakan riwayat transaksi masuk, keluar, dan mutasi terkait</span>
          </label>
        </div>

        <div class="form-group">
          <label class="form-label">1. Ketik kata <span style="color:#dc2626; font-weight:800;">HAPUS DATA</span> di bawah ini:</label>
          <input type="text" name="konfirmasi" id="inputKonfirmasiText" class="form-control" placeholder="Ketik HAPUS DATA" autocomplete="off" required>
        </div>

        <div class="form-group">
          <label class="form-label">2. Masukkan Password Akun Operator Anda:</label>
          <input type="password" name="password_operator" id="inputPasswordOperator" class="form-control" placeholder="Password akun Anda yang sedang aktif" required>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn-cancel" onclick="closeDeleteModal()">Batal</button>
        <button type="submit" class="btn-confirm-delete">
          <i class="fas fa-trash-alt"></i> Ya, Hapus Data Sekarang
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  function openDeleteModal(kategori, labelNama, volume) {
    document.getElementById('inputKategoriHapus').value = kategori;
    document.getElementById('labelNamaKategori').textContent = labelNama;
    document.getElementById('labelVolumeData').textContent = new Intl.NumberFormat('id-ID').format(volume || 0);
    document.getElementById('inputKonfirmasiText').value = '';
    document.getElementById('inputPasswordOperator').value = '';

    document.getElementById('modalHapusData').classList.add('active');
    setTimeout(() => {
      document.getElementById('inputKonfirmasiText').focus();
    }, 150);
  }

  function closeDeleteModal() {
    document.getElementById('modalHapusData').classList.remove('active');
  }

  function validateDeleteForm() {
    const input = document.getElementById('inputKonfirmasiText').value.trim();
    if (input !== 'HAPUS DATA') {
      alert('Kata konfirmasi tidak sesuai! Anda wajib mengetik "HAPUS DATA" persis dengan huruf besar.');
      return false;
    }
    const pwd = document.getElementById('inputPasswordOperator').value;
    if (!pwd) {
      alert('Silakan masukkan password Operator Anda untuk verifikasi.');
      return false;
    }
    return confirm('Apakah Anda benar-benar yakin ingin mengosongkan data ini secara permanen?');
  }

  // Tutup modal jika klik di luar
  window.onclick = function(e) {
    const modal = document.getElementById('modalHapusData');
    if (e.target === modal) {
      closeDeleteModal();
    }
  }
</script>

</body>
</html>