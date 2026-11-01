<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SIPANDU - Dashboard Operator</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
  <style>
    :root {
      --primary: #3b5bdb;
      --sidebar-bg: #1e2a4a;
      --text: #1a1a2e;
      --muted: #6b7280;
      --border: #e5e7eb;
      --card-bg: #ffffff;
      --bg: #f4f6fb;
      --success: #10b981;
      --danger: #ef4444;
      --warning: #f59e0b;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); display: flex; min-height: 100vh; }
    
    .main { margin-left: 260px; flex: 1; padding: 0 32px 40px; }
    .topbar { display: flex; align-items: center; justify-content: space-between; padding: 20px 0 24px; position: sticky; top: 0; z-index: 50; background: var(--bg); }
    .page-title { font-size: 24px; font-weight: 700; }
    .page-sub { font-size: 14px; color: var(--muted); margin-top: 4px; }
    .avatar-top { width: 40px; height: 40px; background: var(--primary); border-radius: 50%; display: grid; place-items: center; color: #fff; font-weight: 700; font-size: 14px; }

    /* STATS CARDS */
    .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 32px; }
    .stat-card { background: var(--card-bg); border-radius: 16px; padding: 24px; border: 1px solid var(--border); display: flex; align-items: center; gap: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
    .stat-icon { width: 56px; height: 56px; border-radius: 14px; display: grid; place-items: center; font-size: 24px; flex-shrink: 0; }
    
    .card-total .stat-icon { background: #eef2ff; color: var(--primary); }
    .card-active .stat-icon { background: #dcfce7; color: var(--success); }
    .card-inactive .stat-icon { background: #fee2e2; color: var(--danger); }

    .stat-info h3 { font-size: 28px; font-weight: 700; margin-bottom: 4px; }
    .stat-info p { font-size: 13px; color: var(--muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }

    /* QUICK ACTIONS */
    .quick-actions { background: var(--card-bg); border-radius: 16px; padding: 24px; border: 1px solid var(--border); box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
    .qa-header { font-size: 18px; font-weight: 700; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
    .qa-header i { color: var(--primary); }
    .btn-kelola { display: inline-flex; align-items: center; gap: 8px; background: var(--primary); color: #fff; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 14px; transition: .2s; }
    .btn-kelola:hover { background: #2f4ac7; transform: translateY(-2px); }

    @media (max-width: 768px) {
      .stats-grid { grid-template-columns: 1fr; }
      .main { margin-left: 0; padding: 16px; padding-top: 60px; }
      .topbar { flex-direction: column; align-items: flex-start; gap: 8px; }
      .page-title { font-size: 20px; }
    }
  </style>
</head>
<body>

@include('partials.sidebar')

<div class="main">
  <div class="topbar">
    <div>
      <div class="page-title">Halo, Operator! 👋</div>
      <div class="page-sub">Selamat datang di Pusat Kontrol SIPANDU BPMP Provinsi Gorontalo</div>
    </div>
    <div>
      <div class="avatar-top">{{ substr(Auth::user()->name ?? 'SA', 0, 2) }}</div>
    </div>
  </div>

  @php
    $totalUsers = $totalUsers ?? \App\Models\User::count();
    $activeUsers = $activeUsers ?? \App\Models\User::where('is_active', true)->count();
    $inactiveUsers = $inactiveUsers ?? \App\Models\User::where('is_active', false)->count();

    $countAset = $countAset ?? (\Illuminate\Support\Facades\Schema::hasTable('aset_tetap') ? \App\Models\AssetTetap::count() : 0);
    $countPersediaan = $countPersediaan ?? (\Illuminate\Support\Facades\Schema::hasTable('persediaan') ? \App\Models\Persediaan::where('kode_kategori', '!=', 'BLN')->count() : 0);
    $countBarangLainnya = $countBarangLainnya ?? (\Illuminate\Support\Facades\Schema::hasTable('barang_lainnya') ? \App\Models\BarangLainnya::count() : 0);
    $fifoEnabled = $fifoEnabled ?? \App\Services\PersediaanBatchService::isFifoEnabled();
  @endphp

  @if(session('success'))
    <div style="padding: 14px 18px; border-radius: 12px; margin-bottom: 24px; background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; display: flex; align-items: center; justify-content: space-between; font-size: 14px; font-weight: 600;">
      <div style="display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-check-circle" style="font-size: 18px;"></i>
        <span>{{ session('success') }}</span>
      </div>
      <button onclick="this.parentElement.remove()" style="border:none; background:none; cursor:pointer; color:inherit; font-size:15px;">✕</button>
    </div>
  @endif

  <div style="font-weight: 700; font-size: 15px; color: var(--text); margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
    <i class="fas fa-users-cog" style="color: var(--primary);"></i> Ringkasan Pengguna Sistem
  </div>
  <div class="stats-grid" style="margin-bottom: 24px;">
    <div class="stat-card card-total">
      <div class="stat-icon"><i class="fas fa-users"></i></div>
      <div class="stat-info">
        <h3>{{ $totalUsers }}</h3>
        <p>Total Pengguna</p>
      </div>
    </div>
    <div class="stat-card card-active">
      <div class="stat-icon"><i class="fas fa-user-check"></i></div>
      <div class="stat-info">
        <h3>{{ $activeUsers }}</h3>
        <p>Akun Aktif</p>
      </div>
    </div>
    <div class="stat-card card-inactive">
      <div class="stat-icon"><i class="fas fa-user-lock"></i></div>
      <div class="stat-info">
        <h3>{{ $inactiveUsers }}</h3>
        <p>Akun Nonaktif</p>
      </div>
    </div>
  </div>

  <div style="font-weight: 700; font-size: 15px; color: var(--text); margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
    <i class="fas fa-boxes" style="color: #059669;"></i> Ringkasan Data Inventaris & Logistik
  </div>
  <div class="stats-grid" style="margin-bottom: 32px;">
    <div class="stat-card">
      <div class="stat-icon" style="background:#e0e7ff; color:#4338ca;"><i class="fas fa-database"></i></div>
      <div class="stat-info">
        <h3>{{ number_format($countAset) }}</h3>
        <p>Data Aset Tetap</p>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon" style="background:#dcfce7; color:#15803d;"><i class="fas fa-cubes"></i></div>
      <div class="stat-info">
        <h3>{{ number_format($countPersediaan) }}</h3>
        <p>Data Persediaan</p>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon" style="background:#fef3c7; color:#b45309;"><i class="fas fa-plug"></i></div>
      <div class="stat-info">
        <h3>{{ number_format($countBarangLainnya) }}</h3>
        <p>Barang Lainnya</p>
      </div>
    </div>
  </div>

  <div class="quick-actions">
    <div class="qa-header"><i class="fas fa-cogs"></i> Kontrol Sistem & Pengelolaan Data</div>
    <p style="color: var(--muted); font-size: 14px; margin-bottom: 20px;">
      Gunakan panel ini untuk mengelola data master pengguna, mengatur hak akses, serta melakukan pencadangan (backup) dan pembersihan data inventaris (Aset Tetap, Persediaan, dan Barang Lainnya).
    </p>
    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
      <a href="{{ route('superadmin.manajemen-user') }}" class="btn-kelola">
        <i class="fas fa-user-shield"></i> Kelola Hak Akses Pengguna
      </a>
      <a href="{{ route('superadmin.kelola-data') }}" class="btn-kelola" style="background: linear-gradient(135deg, #059669, #047857);">
        <i class="fas fa-database"></i> Kelola & Backup Data Inventaris
      </a>
      <a href="{{ route('superadmin.unit_kerja.index') }}" class="btn-kelola" style="background: #475569;">
        <i class="fas fa-building"></i> Kelola Unit Kerja
      </a>
    </div>

    <div style="margin-top: 24px; padding-top: 20px; border-top: 1px dashed var(--border); display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
      <div style="flex: 1; min-width: 260px;">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px; flex-wrap: wrap;">
          <span style="font-size: 15px; font-weight: 700; color: var(--text);">
            <i class="fas fa-layer-group" style="color: var(--primary); margin-right: 4px;"></i> Metode FIFO Data Persediaan
          </span>
          @if($fifoEnabled)
            <span style="padding: 3px 10px; border-radius: 999px; background: #dcfce7; color: #166534; font-size: 11.5px; font-weight: 700;">
              ● AKTIF
            </span>
          @else
            <span style="padding: 3px 10px; border-radius: 999px; background: #f1f5f9; color: #64748b; font-size: 11.5px; font-weight: 700;">
              ○ NONAKTIF (Disembunyikan)
            </span>
          @endif
        </div>
        <p style="font-size: 13px; color: var(--muted); line-height: 1.5;">
          @if($fifoEnabled)
            Saat ini perhitungan stok keluar & rincian sisa per batch menggunakan metode <strong>FIFO (First-In, First-Out)</strong>. Klik tombol di samping jika ingin menyembunyikan bagian FIFO kembali.
          @else
            Saat ini bagian <strong>FIFO disembunyikan</strong> (daftar riwayat harga masuk barang tetap tersimpan di Detail Data Persediaan). Anda dapat menghidupkan kembali fitur FIFO kapan saja.
          @endif
        </p>
      </div>
      <form method="POST" action="{{ route('superadmin.toggle-fifo') }}">
        @csrf
        @if($fifoEnabled)
          <button type="submit" class="btn-kelola" style="background: #64748b; border: none; cursor: pointer;">
            <i class="fas fa-eye-slash"></i> Sembunyikan / Nonaktifkan FIFO
          </button>
        @else
          <button type="submit" class="btn-kelola" style="background: linear-gradient(135deg, #10b981, #059669); border: none; cursor: pointer;">
            <i class="fas fa-power-off"></i> Hidupkan Fitur FIFO
          </button>
        @endif
      </form>
    </div>
  </div>

</div>

</body>
</html>