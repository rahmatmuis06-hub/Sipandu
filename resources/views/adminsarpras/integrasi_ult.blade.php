<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Integrasi ULT - Admin Sarana Prasarana</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
  :root {
    --primary: #4361ee;
    --primary-light: #eef0fd;
    --success: #10b981;
    --success-light: #ecfdf5;
    --warning: #f59e0b;
    --warning-light: #fffbeb;
    --danger: #ef4444;
    --danger-light: #fef2f2;
    --info: #0ea5e9;
    --info-light: #e0f2fe;
    --sidebar-bg: #fff;
    --body-bg: #f8fafc;
    --text-primary: #1e293b;
    --text-secondary: #64748b;
    --border: #e2e8f0;
    --card-bg: #fff;
    --sidebar-width: 240px;
  }
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--body-bg); color: var(--text-primary); display: flex; min-height: 100vh; }

  .topbar {
    background: var(--card-bg); border-bottom: 1px solid var(--border);
    padding: 0 28px; height: 56px; display: flex; align-items: center; justify-content: space-between;
    position: sticky; top: 0; z-index: 50;
  }
  .topbar-title { font-size: 16px; font-weight: 700; }
  .topbar-right { display: flex; align-items: center; gap: 16px; }
  .date-text { font-size: 13px; color: var(--text-secondary); font-weight: 500; }

  .main { margin-left: var(--sidebar-width); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
  .content { padding: 28px; flex: 1; }

  .page-hdr {
    display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; flex-wrap: wrap; gap: 14px;
  }
  .page-hdr h1 { font-size: 22px; font-weight: 800; color: #1e293b; }
  .page-hdr p { font-size: 13.5px; color: var(--text-secondary); margin-top: 4px; }

  .stats-grid {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-bottom: 24px;
  }
  .stat-card {
    background: #fff; border: 1px solid var(--border); border-radius: 14px; padding: 20px;
    display: flex; align-items: center; gap: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);
  }
  .stat-icon {
    width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
  }
  .stat-num { font-size: 24px; font-weight: 800; color: #0f172a; line-height: 1.2; }
  .stat-lbl { font-size: 12.5px; color: var(--text-secondary); font-weight: 600; margin-top: 2px; }

  .card {
    background: #fff; border: 1px solid var(--border); border-radius: 16px; padding: 24px;
    margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);
  }
  .card-title {
    font-size: 16px; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 10px; margin-bottom: 14px;
  }

  .api-badge {
    background: #0f172a; color: #38bdf8; font-family: monospace; font-size: 11.5px; padding: 3px 8px; border-radius: 6px; font-weight: 700;
  }
  .method-badge {
    padding: 3px 8px; border-radius: 6px; font-size: 11px; font-weight: 800; font-family: monospace;
  }
  .method-get { background: #dcfce7; color: #166534; }
  .method-post { background: #dbeafe; color: #1e40af; }

  table { width: 100%; border-collapse: collapse; font-size: 13px; }
  th { background: #f8fafc; padding: 12px 14px; text-align: left; font-weight: 700; color: #475569; border-bottom: 1px solid var(--border); }
  td { padding: 12px 14px; border-bottom: 1px solid var(--border); color: #334155; }
  tr:hover { background: #f8fafc; }

  .status-pill {
    display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 700;
  }
  .status-pending { background: #fef3c7; color: #b45309; }
  .status-disetujui { background: #dcfce7; color: #15803d; }
  .status-ditolak { background: #fee2e2; color: #b91c1c; }

  .btn {
    padding: 9px 16px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer;
    border: none; display: inline-flex; align-items: center; gap: 8px; transition: all .15s; font-family: inherit; text-decoration: none;
  }
  .btn-primary { background: var(--primary); color: #fff; }
  .btn-primary:hover { background: #3751d6; }
  .btn-outline { background: #fff; border: 1px solid var(--border); color: #475569; }
  .btn-outline:hover { background: #f1f5f9; }

  .info-box {
    background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 16px; margin-bottom: 20px; font-size: 13px; color: #166534;
  }
</style>
</head>
<body>

@include('partials.sidebar')

<main class="main">
  <div class="topbar">
    <span class="topbar-title">Integrasi Sistem ULT (Unit Layanan Terpadu)</span>
    <div class="topbar-right">
      <span class="date-text">{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
    </div>
  </div>

  <div class="content">
    <div class="page-hdr">
      <div>
        <h1>Penjajakan & Integrasi Sistem ULT</h1>
        <p>Sinkronisasi layanan peminjaman sarpras & pengecekan ketersediaan gedung langsung dari loket pelayanan terpadu BPMP Gorontalo.</p>
      </div>
      <div>
        <a href="{{ route('adminsarpras.daftar-peminjaman') }}" class="btn btn-outline">
          <i class="fas fa-arrow-left"></i> Kembali ke Peminjaman
        </a>
      </div>
    </div>

    <div class="info-box">
      <strong><i class="fas fa-info-circle"></i> Gambaran Arsitektur Integrasi ULT & SIPANDU:</strong><br>
      Sistem Unit Layanan Terpadu (ULT) BPMP Provinsi Gorontalo kini dapat terhubung secara real-time dengan SIPANDU. Petugas loket ULT dapat memeriksa jadwal ketersediaan ruangan (Aula Dulohupa, Gedung Huyula, Ruang SNT, Mess, dll.) tanpa harus cek manual, dan memasukkan permohonan peminjaman langsung ke meja kerja Admin Sarpras.
    </div>

    <!-- STATISTIK KONTRIBUSI LAYANAN ULT -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon" style="background:#eff6ff; color:#2563eb;">
          <i class="fas fa-ticket-alt"></i>
        </div>
        <div>
          <div class="stat-num">{{ $stats['total'] ?? 0 }}</div>
          <div class="stat-lbl">Total Permohonan via ULT</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:#fffbeb; color:#d97706;">
          <i class="fas fa-clock"></i>
        </div>
        <div>
          <div class="stat-num">{{ $stats['pending'] ?? 0 }}</div>
          <div class="stat-lbl">Menunggu Verifikasi Sarpras</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:#f0fdf4; color:#16a34a;">
          <i class="fas fa-check-circle"></i>
        </div>
        <div>
          <div class="stat-num">{{ $stats['disetujui'] ?? 0 }}</div>
          <div class="stat-lbl">Permohonan Disetujui</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:#faf5ff; color:#9333ea;">
          <i class="fas fa-building"></i>
        </div>
        <div>
          <div class="stat-num">{{ $gedungList->count() }}</div>
          <div class="stat-lbl">Fasilitas Terkoneksi</div>
        </div>
      </div>
    </div>

    <!-- ENDPOINT API REST TERBUKA UNTUK SISTEM ULT -->
    <div class="card">
      <div class="card-title">
        <i class="fas fa-code text-primary"></i> Endpoint REST API Siap Pakai untuk Integrasi ULT
      </div>
      <p style="font-size:13px; color:var(--text-secondary); margin-bottom:16px;">
        Gunakan endpoint API di bawah ini untuk menghubungkan aplikasi front-office ULT / Kiosk Antrian dengan SIPANDU:
      </p>

      <div style="display:flex; flex-direction:column; gap:12px;">
        <div style="background:#f8fafc; border:1px solid var(--border); border-radius:10px; padding:14px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
          <div style="display:flex; align-items:center; gap:10px;">
            <span class="method-badge method-get">GET</span>
            <code style="font-weight:700; color:#0f172a;">/api/ult/ketersediaan-gedung?tanggal_mulai=YYYY-MM-DD</code>
          </div>
          <div style="font-size:12px; color:var(--text-secondary);">Cek jadwal bentrok & ketersediaan seluruh ruangan secara real-time</div>
          <a href="/api/ult/ketersediaan-gedung" target="_blank" class="btn btn-outline" style="padding:4px 10px; font-size:11.5px;">Test Endpoint</a>
        </div>

        <div style="background:#f8fafc; border:1px solid var(--border); border-radius:10px; padding:14px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
          <div style="display:flex; align-items:center; gap:10px;">
            <span class="method-badge method-get">GET</span>
            <code style="font-weight:700; color:#0f172a;">/api/ult/katalog-layanan</code>
          </div>
          <div style="font-size:12px; color:var(--text-secondary);">Daftar tarif, kapasitas & fasilitas gedung yang dapat dipinjam/disewa</div>
          <a href="/api/ult/katalog-layanan" target="_blank" class="btn btn-outline" style="padding:4px 10px; font-size:11.5px;">Test Endpoint</a>
        </div>

        <div style="background:#f8fafc; border:1px solid var(--border); border-radius:10px; padding:14px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
          <div style="display:flex; align-items:center; gap:10px;">
            <span class="method-badge method-post">POST</span>
            <code style="font-weight:700; color:#0f172a;">/api/ult/permohonan-sarpras</code>
          </div>
          <div style="font-size:12px; color:var(--text-secondary);">Pengiriman data permohonan baru dari loket tamu ULT ke SIPANDU</div>
          <span class="status-pill status-disetujui">Aktif & Siap Menerima Data</span>
        </div>

        <div style="background:#f8fafc; border:1px solid var(--border); border-radius:10px; padding:14px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
          <div style="display:flex; align-items:center; gap:10px;">
            <span class="method-badge method-get">GET</span>
            <code style="font-weight:700; color:#0f172a;">/api/ult/tracking-layanan/{kode_tiket}</code>
          </div>
          <div style="font-size:12px; color:var(--text-secondary);">Pelacakan progres status verifikasi permohonan untuk tamu/pemohon</div>
          <span class="status-pill status-disetujui">Aktif</span>
        </div>
      </div>
    </div>

    <!-- TABEL DAFTAR PERMOHONAN DARI LOKET ULT -->
    <div class="card">
      <div class="card-title">
        <i class="fas fa-list-check text-primary"></i> Daftar Permohonan Sarpras dari Loket ULT
      </div>
      <div style="overflow-x:auto;">
        <table>
          <thead>
            <tr>
              <th>No</th>
              <th>Pemohon / Instansi</th>
              <th>Fasilitas</th>
              <th>Jadwal Peminjaman</th>
              <th>Peserta</th>
              <th>Estimasi Biaya</th>
              <th>Status SIPANDU</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($peminjamanUlt as $idx => $item)
              <tr>
                <td><strong>{{ $peminjamanUlt->firstItem() + $idx }}</strong></td>
                <td>
                  <strong>{{ $item->nama_lengkap }}</strong><br>
                  <small style="color:var(--text-secondary);">{{ $item->instansi_lembaga }}</small>
                </td>
                <td>{{ $item->gedung->nama_gedung ?? $item->nama_fasilitas }}</td>
                <td>
                  {{ $item->tanggal_pinjam?->format('d/m/Y') }} - {{ $item->tanggal_kembali?->format('d/m/Y') }}<br>
                  <small style="color:var(--text-secondary);">({{ $item->lama_peminjaman_hari }} Hari)</small>
                </td>
                <td><strong>{{ $item->jumlah_peserta }}</strong> Orang</td>
                <td>Rp {{ number_format($item->total_pembayaran ?? 0, 0, ',', '.') }}</td>
                <td>
                  @php
                    $statusClass = match($item->status) {
                      'disetujui', 'disetujui_kasubag' => 'status-disetujui',
                      'ditolak' => 'status-ditolak',
                      default => 'status-pending',
                    };
                  @endphp
                  <span class="status-pill {{ $statusClass }}">
                    {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                  </span>
                </td>
                <td>
                  <a href="{{ route('adminsarpras.daftar-peminjaman') }}" class="btn btn-outline" style="padding:5px 10px; font-size:12px;">
                    Kelola di Peminjaman
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" style="text-align:center; padding:30px; color:var(--text-secondary);">
                  <i class="fas fa-inbox fa-2x" style="margin-bottom:8px; opacity:0.4;"></i><br>
                  Belum ada permohonan masuk dari Loket ULT. Semua data peminjaman yang diajukan via integrasi API ULT akan otomatis muncul di sini.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div style="margin-top:16px;">
        {{ $peminjamanUlt->links() }}
      </div>
    </div>

  </div>
</main>

</body>
</html>
