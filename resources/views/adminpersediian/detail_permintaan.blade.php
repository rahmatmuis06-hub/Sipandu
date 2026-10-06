<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Permintaan - Admin Persediaan</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
:root {
  --primary: #2563eb;
  --primary-light: #3b82f6;
  --success: #10b981;
  --warning: #f59e0b;
  --danger: #ef4444;
  --info: #06b6d4;
  --bg: #f8fafc;
  --card-bg: #ffffff;
  --text-primary: #0f172a;
  --text-secondary: #64748b;
  --border: #e2e8f0;
  --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
  --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  --radius: 12px;
}

* { margin: 0; padding: 0; box-sizing: border-box; }
body { 
  font-family: 'Plus Jakarta Sans', sans-serif; 
  background: var(--bg); 
  color: var(--text-primary);
  line-height: 1.6;
}

/* Base Layout */
.main { padding: 24px; max-width: 1400px; margin: 0 auto; margin-left: 260px; }
.topbar {
  display: flex; align-items: center; justify-content: space-between; 
  margin-bottom: 32px; padding: 20px 24px;
  background: var(--card-bg); border-radius: var(--radius); 
  box-shadow: var(--shadow);
}
.topbar-title { 
  font-family: 'Space Grotesk', sans-serif; 
  font-size: 24px; font-weight: 700; color: var(--text-primary);
}

/* Detail Card */
.content { 
  background: var(--card-bg); border-radius: var(--radius); 
  box-shadow: var(--shadow-lg); overflow: hidden; padding: 32px; 
}
.btn-back { 
  display: inline-flex; align-items: center; gap: 8px; 
  padding: 10px 20px; background: #f1f5f9; color: var(--text-primary); 
  text-decoration: none; border-radius: 10px; font-weight: 600; 
  margin-bottom: 24px; transition: all 0.2s; border: 1px solid var(--border);
}
.btn-back:hover { background: #e2e8f0; transform: translateY(-1px); }

.page-header {
  border-bottom: 1px solid var(--border); padding-bottom: 20px; margin-bottom: 24px;
  display: flex; justify-content: space-between; align-items: flex-start;
}
.page-header h1 { font-family: 'Space Grotesk', sans-serif; font-size: 28px; margin-bottom: 8px; color: var(--text-primary); }
.page-header p { color: var(--text-secondary); font-size: 14px; display: flex; align-items: center; gap: 8px; }

/* Grid Info */
.detail-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
.detail-box { 
  background: #f8fafc; padding: 20px 24px; 
  border-radius: 12px; border: 1px solid var(--border); 
}
.detail-box.full-width { grid-column: 1 / -1; }
.label { font-size: 12px; color: var(--text-secondary); text-transform: uppercase; font-weight: 700; margin-bottom: 6px; letter-spacing: 0.5px; }
.value { font-size: 16px; font-weight: 600; color: var(--text-primary); }
.value-text { font-size: 15px; color: var(--text-primary); line-height: 1.7; font-weight: 400; }

/* Badges */
.status-badge {
  padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;
}

@media (max-width: 768px) {
  .main { padding: 16px; margin-left: 0; }
  .detail-grid { grid-template-columns: 1fr; }
  .page-header { flex-direction: column; gap: 16px; }
}
</style>
</head>
<body>

@include('partials.sidebar')

<main class="main">
  <!-- TOPBAR -->
  <div class="topbar">
    <span class="topbar-title">Detail Permintaan Persediaan</span>
    <div class="topbar-right" style="display: flex; align-items: center; gap: 16px;">
      <span style="font-size: 14px; color: var(--text-secondary);">
        {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}
      </span>
    </div>
  </div>

  <!-- KONTEN DETAIL -->
  <div class="content">
    <a href="{{ route('adminpersediaan.permintaan-persediaan') }}" class="btn-back">
      <i class="fas fa-arrow-left"></i> Kembali ke Daftar Permintaan
    </a>

    <div class="page-header">
      <div>
        <h1>REQ-{{ str_pad($permintaan->id, 4, '0', STR_PAD_LEFT) }}</h1>
        <p>
          <i class="fas fa-calendar-alt"></i> 
          Diajukan pada: {{ $permintaan->created_at->format('d F Y - H:i') }} WIB
        </p>
      </div>
      <div>
        @php $badge = $permintaan->status_badge; @endphp
        <span class="status-badge" style="background: var(--{{ $badge['color'] }}); color: white; opacity: 0.9;">
          <i class="fas fa-{{ $badge['icon'] }}"></i> {{ $badge['text'] }}
        </span>
      </div>
    </div>

    <div class="detail-grid">
      <!-- Info Pemohon -->
      <div class="detail-box">
        <div class="label"><i class="fas fa-user" style="color: var(--primary); margin-right: 4px;"></i> Nama Pemohon</div>
        <div class="value">{{ $permintaan->nama_lengkap }}</div>
        <div style="font-size: 13px; color: var(--text-secondary); margin-top: 4px;">Akun: {{ $permintaan->user->name ?? '-' }}</div>
      </div>

      <!-- Info Barang -->
      <div class="detail-box">
        <div class="label"><i class="fas fa-box" style="color: var(--info); margin-right: 4px;"></i> Nama Barang</div>
        <div class="value">{{ $permintaan->persediaan->nama_barang ?? $permintaan->nama_barang }}</div>
        <div style="font-size: 13px; color: var(--text-secondary); margin-top: 4px;">
          Kode: <code style="background: #e2e8f0; padding: 2px 6px; border-radius: 4px;">{{ $permintaan->kode_barang }}</code>
        </div>
      </div>

      <!-- Info Jumlah & Jadwal -->
      <div class="detail-box">
        <div class="label"><i class="fas fa-hashtag" style="color: var(--success); margin-right: 4px;"></i> Jumlah Diminta</div>
        <div class="value" style="font-size: 24px; color: var(--primary);">{{ $permintaan->jumlah_diminta }} Unit</div>
        <div style="font-size: 13px; color: var(--text-secondary); margin-top: 4px;">
          Sisa Stok Gudang: <strong>{{ $permintaan->persediaan->jumlah ?? 0 }} Unit</strong>
        </div>
      </div>

      <div class="detail-box">
        <div class="label"><i class="fas fa-calendar-check" style="color: var(--warning); margin-right: 4px;"></i> Jadwal Dibutuhkan</div>
        <div class="value" style="font-size: 18px;">{{ \Carbon\Carbon::parse($permintaan->tanggal_dibutuhkan)->format('d F Y') }}</div>
        <div style="font-size: 13px; color: var(--text-secondary); margin-top: 4px;">
          Tgl Permintaan: {{ \Carbon\Carbon::parse($permintaan->tanggal_permintaan)->format('d F Y') }}
        </div>
      </div>

      @if($permintaan->items && $permintaan->items->count() > 0)
      <div class="detail-box full-width" style="background: #fff;">
        <div class="label" style="margin-bottom: 12px;"><i class="fas fa-list-check" style="color: var(--primary); margin-right: 4px;"></i> Rincian Barang Diminta (Multi-Item)</div>
        <div style="overflow-x: auto;">
          <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
            <thead>
              <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: left;">
                <th style="padding: 8px 10px;">No</th>
                <th style="padding: 8px 10px;">Nama Barang</th>
                <th style="padding: 8px 10px;">Kode Barang</th>
                <th style="padding: 8px 10px;">Satuan</th>
                <th style="padding: 8px 10px;">Jumlah Diminta</th>
                <th style="padding: 8px 10px;">Stok Gudang</th>
              </tr>
            </thead>
            <tbody>
              @foreach($permintaan->items as $idx => $it)
              <tr style="border-bottom: 1px solid #edf2f7;">
                <td style="padding: 8px 10px;">{{ $idx + 1 }}</td>
                <td style="padding: 8px 10px; font-weight: 600;">{{ $it->nama_barang }}</td>
                <td style="padding: 8px 10px; font-family: monospace;">{{ $it->kode_barang }}</td>
                <td style="padding: 8px 10px;">{{ $it->satuan ?? ($it->persediaan->satuan ?? '-') }}</td>
                <td style="padding: 8px 10px; font-weight: 700; color: var(--primary);">{{ $it->jumlah_diminta }}</td>
                <td style="padding: 8px 10px;">{{ $it->persediaan->jumlah ?? '-' }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      @endif

      <!-- Tujuan Penggunaan -->
      <div class="detail-box full-width">
        <div class="label"><i class="fas fa-bullseye" style="color: var(--danger); margin-right: 4px;"></i> Tujuan Penggunaan</div>
        <div class="value-text">
          {{ $permintaan->tujuan_penggunaan ?: 'Tidak ada keterangan tujuan penggunaan yang dilampirkan.' }}
        </div>
      </div>

      <!-- Info Reviewer (Jika sudah direview) -->
      @if($permintaan->reviewed_by_adminpersediaan_id || $permintaan->approved_by_kasubag_id)
      <div class="detail-box full-width" style="background: #fff; border-left: 4px solid var(--primary);">
        <div class="label">Informasi Persetujuan</div>
        <div style="display: flex; gap: 32px; margin-top: 12px;">
          @if($permintaan->reviewedBy)
          <div>
            <div style="font-size: 13px; color: var(--text-secondary);">Direview Oleh (Admin):</div>
            <div style="font-weight: 600;">{{ $permintaan->reviewedBy->name ?? 'Admin Persediaan' }}</div>
          </div>
          @endif
          @if($permintaan->approvedByKasubag)
          <div>
            <div style="font-size: 13px; color: var(--text-secondary);">Disetujui Oleh (Kasubag):</div>
            <div style="font-weight: 600;">{{ $permintaan->approvedByKasubag->name ?? 'Kepala Sub Bagian' }}</div>
          </div>
          @endif
        </div>
      </div>
      @endif

    </div>
  </div>
</main>

</body>
</html>