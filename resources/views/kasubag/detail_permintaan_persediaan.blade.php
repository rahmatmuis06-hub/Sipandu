<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Permintaan - Dashboard Kasubag</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  :root {
    --blue: #3b6ff0; --blue-light: #eef2ff;
    --orange: #f59e0b; --orange-light: #fffbeb;
    --green: #10b981; --green-light: #ecfdf5;
    --gray-50: #f8fafc; --gray-100: #f1f5f9;
    --gray-200: #e2e8f0; --gray-400: #94a3b8;
    --gray-600: #475569; --gray-800: #1e293b;
    --sidebar-w: 240px;
  }
  .main { margin-left: var(--sidebar-w); padding: 32px; flex: 1; }
  
  body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--gray-50); color: var(--gray-800); display: flex; min-height: 100vh; }
  .sidebar { width: var(--sidebar-w); background: #fff; border-right: 1px solid var(--gray-200); display: flex; flex-direction: column; position: fixed; top: 0; left: 0; bottom: 0; z-index: 10; }
  .topbar { position: fixed; top: 0; left: var(--sidebar-w); right: 0; height: 60px; background: #fff; border-bottom: 1px solid var(--gray-200); display: flex; align-items: center; justify-content: flex-end; padding: 0 28px; gap: 16px; z-index: 9; }
  .user-chip { display: flex; align-items: center; gap: 10px; background: var(--gray-100); border-radius: 10px; padding: 6px 12px 6px 6px; }
  .user-avatar { width: 30px; height: 30px; background: var(--blue); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; color: #fff; }
  .user-info strong { font-size: 13px; font-weight: 600; display: block; }
  .user-info span { font-size: 11px; color: var(--gray-400); }
  
  .main { margin-left: var(--sidebar-w); margin-top: 60px; padding: 32px; flex: 1; max-width: 1000px; }
  
  .btn-back { display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; background: #fff; border: 1px solid var(--gray-200); color: var(--gray-800); border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; margin-bottom: 24px; transition: .2s; }
  .btn-back:hover { background: var(--gray-100); }
  .btn-back svg { width: 16px; height: 16px; }

  .content-card { background: #fff; border: 1px solid var(--gray-200); border-radius: 16px; padding: 32px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
  .header-detail { border-bottom: 1px solid var(--gray-200); padding-bottom: 20px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: flex-start; }
  .header-detail h1 { font-size: 24px; font-weight: 700; color: var(--blue); margin-bottom: 6px; }
  .header-detail p { font-size: 13px; color: var(--gray-400); }
  
  .status-badge { padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; background: var(--orange-light); color: var(--orange); border: 1px solid rgba(245,158,11,0.2); }
  
  .grid-detail { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
  .detail-item { background: var(--gray-50); padding: 16px 20px; border-radius: 12px; border: 1px solid var(--gray-100); }
  .detail-item.full { grid-column: 1 / -1; }
  .detail-label { font-size: 11px; font-weight: 700; color: var(--gray-400); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
  .detail-value { font-size: 15px; font-weight: 600; color: var(--gray-800); }
  .detail-value.highlight { font-size: 20px; color: var(--blue); }
  
  .action-box { margin-top: 32px; padding-top: 24px; border-top: 1px dashed var(--gray-200); display: flex; gap: 12px; justify-content: flex-end; }
  .btn-action { padding: 10px 24px; border-radius: 8px; font-size: 14px; font-weight: 600; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: .2s; }
  .btn-approve { background: var(--green); color: #fff; }
  .btn-approve:hover { background: #059669; }
  .btn-reject { background: var(--red-light); color: var(--red); }
  .btn-reject:hover { background: #fecaca; }
</style>
</head>
<body>

@include('partials.sidebar')

<div class="topbar">
  <div class="user-chip">
    <div class="user-avatar">{{ substr(Auth::user()->name ?? 'K', 0, 1) }}</div>
    <div class="user-info"><strong>{{ Auth::user()->name ?? 'Kasubag' }}</strong><span>Kepala Sub Bagian</span></div>
  </div>
</div>

<main class="main">
  <a href="{{ route('kasubag.persetujuan-permintaan-persediaan') }}" class="btn-back">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
    Kembali ke Daftar
  </a>

  <div class="content-card">
    <div class="header-detail">
      <div>
        <h1>REQ-{{ str_pad($permintaan->id, 4, '0', STR_PAD_LEFT) }}</h1>
        <p>Diajukan pada: {{ $permintaan->created_at->format('d F Y - H:i') }} WIB</p>
      </div>
      <div class="status-badge">
        {{ ucfirst(str_replace('_', ' ', $permintaan->status)) }}
      </div>
    </div>

    <div class="grid-detail">
      <div class="detail-item">
        <div class="detail-label">Pemohon</div>
        <div class="detail-value">{{ $permintaan->nama_lengkap }}</div>
      </div>
      <div class="detail-item">
        <div class="detail-label">Akun User</div>
        <div class="detail-value">{{ $permintaan->user->name ?? '-' }}</div>
      </div>
      <div class="detail-item">
        <div class="detail-label">Nama Barang</div>
        <div class="detail-value">{{ $permintaan->persediaan->nama_barang ?? $permintaan->nama_barang }}</div>
      </div>
      <div class="detail-item">
        <div class="detail-label">Kode Barang</div>
        <div class="detail-value">{{ $permintaan->kode_barang }}</div>
      </div>
      <div class="detail-item">
        <div class="detail-label">Jumlah Diminta</div>
        <div class="detail-value highlight">{{ $permintaan->jumlah_diminta }} Unit</div>
      </div>
      <div class="detail-item">
        <div class="detail-label">Tanggal Dibutuhkan</div>
        <div class="detail-value">{{ \Carbon\Carbon::parse($permintaan->tanggal_dibutuhkan)->format('d M Y') }}</div>
      </div>
      @if($permintaan->items && $permintaan->items->count() > 0)
      <div class="detail-item full" style="background: #fff; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0;">
        <div class="detail-label" style="font-size: 14px; font-weight: 700; color: #1e293b; margin-bottom: 10px;">
          <i class="fas fa-list-check" style="color: #2563eb;"></i> Rincian Barang Diminta (Multi-Item)
        </div>
        <div style="overflow-x: auto;">
          <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
            <thead>
              <tr style="background: #f1f5f9; border-bottom: 2px solid #cbd5e1; text-align: left;">
                <th style="padding: 8px 10px;">No</th>
                <th style="padding: 8px 10px;">Nama Barang</th>
                <th style="padding: 8px 10px;">Kode Barang</th>
                <th style="padding: 8px 10px;">Satuan</th>
                <th style="padding: 8px 10px;">Jumlah Diminta</th>
                <th style="padding: 8px 10px;">Sisa Stok Fisik</th>
              </tr>
            </thead>
            <tbody>
              @foreach($permintaan->items as $idx => $it)
              <tr style="border-bottom: 1px solid #e2e8f0;">
                <td style="padding: 8px 10px;">{{ $idx + 1 }}</td>
                <td style="padding: 8px 10px; font-weight: 600;">{{ $it->nama_barang }}</td>
                <td style="padding: 8px 10px; font-family: monospace;">{{ $it->kode_barang }}</td>
                <td style="padding: 8px 10px;">{{ $it->satuan ?? ($it->persediaan->satuan ?? '-') }}</td>
                <td style="padding: 8px 10px; font-weight: 700; color: #2563eb;">{{ $it->jumlah_diminta }}</td>
                <td style="padding: 8px 10px;">{{ $it->persediaan->jumlah ?? '-' }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      @endif

      <div class="detail-item full">
        <div class="detail-label">Tujuan Penggunaan</div>
        <div class="detail-value" style="font-weight: 400; line-height: 1.6;">{{ $permintaan->tujuan_penggunaan }}</div>
      </div>
    </div>

  @if($permintaan->status == 'diteruskan_kasubag')
    <form action="{{ route('kasubag.approve-permintaan', $permintaan->id) }}" method="POST" class="d-inline">
        @csrf
        <input type="hidden" name="action" value="setuju">
        <label for="tanggal_penerimaan">Tanggal Penerimaan</label>
        <input id="tanggal_penerimaan" type="date" name="tanggal_penerimaan"
          value="{{ old('tanggal_penerimaan', now()->format('Y-m-d')) }}" required>
        <button type="submit" class="btn btn-success btn-sm">Setuju</button>
    </form>
    
    <form action="{{ route('kasubag.approve-permintaan', $permintaan->id) }}" method="POST" class="d-inline">
        @csrf
        <input type="hidden" name="action" value="tolak">
        <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
    </form>
    @else
        <button class="btn btn-secondary btn-sm" disabled>Sudah Diproses</button>
        @if($permintaan->tanggal_penerimaan)
          <div class="detail-label">Tanggal Penerimaan</div>
          <div class="detail-value">{{ $permintaan->tanggal_penerimaan->translatedFormat('d F Y') }}</div>
        @endif
  @endif
  </div>
</main>

</body>
</html>
