<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SIPANDU - Peminjaman Barang Lainnya</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  
  <style>
    :root {
      --primary: #d97706;
      --primary-light: #f59e0b;
      --primary-dark: #b45309;
      --accent: #fcd34d;
      --success: #10b981;
      --warning: #f59e0b;
      --danger: #ef4444;
      --bg: #f8fafc;
      --sidebar-bg: #0f172a;
      --sidebar-text: #94a3b8;
      --card-bg: #ffffff;
      --text-primary: #0f172a;
      --text-secondary: #64748b;
      --border: #e2e8f0;
      --radius: 16px;
      --radius-sm: 10px;
      --shadow: 0 4px 24px rgba(217, 119, 6, 0.08);
      --shadow-lg: 0 8px 40px rgba(217, 119, 6, 0.14);
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: var(--bg);
      color: var(--text-primary);
      display: flex;
      min-height: 100vh;
      overflow-x: hidden;
    }

    .main {
      margin-left: 260px;
      flex: 1;
      padding: 0 32px 40px;
      width: calc(100% - 260px);
    }

    .topbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 20px 0 24px;
      position: sticky;
      top: 0;
      z-index: 50;
      background: var(--bg);
      border-bottom: 1px solid transparent;
      flex-wrap: wrap;
      gap: 16px;
    }

    .topbar-left {
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .breadcrumb {
      font-size: 13px;
      color: var(--text-secondary);
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .breadcrumb span {
      color: var(--primary);
      font-weight: 600;
    }

    .topbar-title {
      font-family: 'Space Grotesk', sans-serif;
      font-size: 22px;
      font-weight: 700;
    }

    .content-grid {
      display: grid;
      grid-template-columns: 1fr 1.35fr;
      gap: 28px;
    }

    @media (max-width: 1024px) {
      .main { margin-left: 0 !important; width: 100% !important; padding: 70px 20px 40px !important; }
      .content-grid { grid-template-columns: 1fr; gap: 24px; }
      .form-card { position: static; top: auto; }
    }

    @media (max-width: 768px) {
      .main { padding: 65px 14px 32px !important; }
      .topbar { padding: 10px 0 16px; flex-direction: column; align-items: flex-start; gap: 8px; }
      .topbar-title { font-size: 19px; }
      .form-header { padding: 18px 16px; }
      .form-header-title { font-size: 16px; }
      .form-body { padding: 16px; }
      .input-row { grid-template-columns: 1fr; gap: 12px; }
      .unit-picker-box { padding: 12px 10px; }
      .unit-chips-grid { grid-template-columns: repeat(auto-fill, minmax(85px, 1fr)); gap: 6px; }
      .unit-chip { padding: 8px 6px; border-radius: 8px; }
      .unit-chip-code { font-size: 12px; }
      .unit-chip-status { font-size: 9px; padding: 1px 5px; }
      .history-header { padding: 14px 16px; flex-direction: column; align-items: flex-start; gap: 10px; }
      .filter-tabs { width: 100%; overflow-x: auto; flex-wrap: nowrap; gap: 6px; -webkit-overflow-scrolling: touch; }
      .filter-tab { font-size: 11px; padding: 6px 12px; white-space: nowrap; flex-shrink: 0; }
      .req-list { padding: 12px 14px; gap: 12px; }
      .req-card-top { flex-direction: column; align-items: flex-start; gap: 10px; padding: 12px 14px; }
      .req-card-meta { grid-template-columns: 1fr; gap: 8px; padding: 10px 14px; }
      .req-card-footer { padding: 12px 14px; flex-direction: column; align-items: stretch; gap: 8px; }
      .card-btn { width: 100%; justify-content: center; }
    }

    @media (max-width: 480px) {
      .main { padding: 60px 10px 24px !important; }
      .topbar-title { font-size: 17px; }
      .form-header { padding: 16px 12px; }
      .form-body { padding: 14px 10px; }
      .unit-chips-grid { grid-template-columns: repeat(3, 1fr); gap: 5px; }
    }

    /* FORM CARD */
    .form-card {
      background: var(--card-bg);
      border-radius: var(--radius);
      border: 1px solid var(--border);
      box-shadow: var(--shadow);
      overflow: hidden;
      position: sticky;
      top: 90px;
      height: fit-content;
    }

    .form-header {
      padding: 24px 28px 20px;
      background: linear-gradient(135deg, #b45309 0%, #d97706 50%, #f59e0b 100%);
      position: relative;
      overflow: hidden;
    }

    .form-header-icon {
      position: relative;
      z-index: 1;
      width: 46px;
      height: 46px;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 13px;
      display: grid;
      place-items: center;
      font-size: 20px;
      color: #fff;
      margin-bottom: 12px;
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .form-header-title {
      font-family: 'Space Grotesk', sans-serif;
      font-size: 18px;
      font-weight: 700;
      color: #fff;
    }

    .form-header-sub {
      font-size: 12px;
      color: rgba(255, 255, 255, 0.85);
      margin-top: 4px;
    }

    .form-body { padding: 24px 28px; }

    .form-group { margin-bottom: 16px; }

    .form-label {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 12px;
      font-weight: 700;
      color: var(--text-secondary);
      text-transform: uppercase;
      letter-spacing: .6px;
      margin-bottom: 8px;
    }

    .form-label i { color: var(--primary); font-size: 11px; }

    .form-input, .form-select, .form-textarea {
      width: 100%;
      padding: 11px 14px;
      border: 1.5px solid var(--border);
      border-radius: 10px;
      font-size: 13px;
      font-family: 'Plus Jakarta Sans', sans-serif;
      color: var(--text-primary);
      background: #fff;
      transition: all .2s;
      outline: none;
    }

    .form-input:focus, .form-select:focus, .form-textarea:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.12);
    }

    .input-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

    .facility-preview {
      display: none;
      margin-top: 10px;
      padding: 12px 14px;
      border-radius: 10px;
      background: #fefce8;
      border: 1px solid #fef08a;
    }
    .facility-preview.show { display: flex; align-items: center; gap: 12px; }

    .fp-icon {
      width: 36px; height: 36px; border-radius: 9px;
      background: var(--primary); display: grid; place-items: center;
      color: #fff; font-size: 16px; flex-shrink: 0;
    }

    .submit-btn {
      width: 100%;
      padding: 13px;
      background: linear-gradient(135deg, var(--primary), var(--primary-light));
      color: #fff;
      border: none;
      border-radius: 11px;
      font-size: 14px;
      font-weight: 700;
      font-family: 'Plus Jakarta Sans', sans-serif;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: all .2s;
      box-shadow: 0 4px 14px rgba(217, 119, 6, 0.35);
      margin-top: 8px;
    }

    .submit-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(217, 119, 6, 0.45);
    }

    /* RIWAYAT CARD */
    .history-card {
      background: var(--card-bg);
      border-radius: var(--radius);
      border: 1px solid var(--border);
      box-shadow: var(--shadow);
      overflow: hidden;
    }

    .history-header {
      padding: 22px 28px 18px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: 1px solid var(--border);
      flex-wrap: wrap;
      gap: 12px;
    }

    .history-title {
      font-family: 'Space Grotesk', sans-serif;
      font-size: 17px;
      font-weight: 700;
      color: var(--text-primary);
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .filter-tabs { display: flex; gap: 6px; flex-wrap: wrap; }

    .filter-tab {
      font-size: 11px;
      font-weight: 600;
      padding: 5px 12px;
      border-radius: 7px;
      cursor: pointer;
      border: 1.5px solid var(--border);
      background: transparent;
      color: var(--text-secondary);
      transition: all .2s;
    }

    .filter-tab.active {
      background: var(--primary);
      color: #fff;
      border-color: var(--primary);
    }

    .req-list { padding: 20px 28px; display: flex; flex-direction: column; gap: 16px; }

    .req-card {
      border: 1.5px solid var(--border);
      border-radius: 14px;
      overflow: hidden;
      transition: all .2s;
    }
    .req-card:hover { box-shadow: var(--shadow); transform: translateY(-1px); }

    .req-card-top {
      padding: 16px 18px;
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 12px;
    }

    .req-card-icon {
      width: 42px; height: 42px; border-radius: 11px;
      display: grid; place-items: center; font-size: 18px; flex-shrink: 0;
      background: #fef3c7; color: #b45309;
    }

    .status-badge {
      font-size: 11.5px;
      font-weight: 700;
      padding: 4px 11px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      gap: 5px;
      white-space: nowrap;
    }
    .status-badge.pending { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
    .status-badge.disetujui { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
    .status-badge.dikembalikan { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
    .status-badge.ditolak { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
    .status-badge.dibatalkan { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; }

    .req-card-meta {
      padding: 12px 18px;
      background: #fffdf5;
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 10px;
      border-top: 1px solid #fef3c7;
    }

    .meta-label { font-size: 10px; text-transform: uppercase; color: #94a3b8; font-weight: 700; margin-bottom: 2px; }
    .meta-value { font-size: 12px; font-weight: 600; color: var(--text-primary); }

    .alert {
      padding: 14px 18px; border-radius: 10px; margin-bottom: 20px;
      display: flex; align-items: center; gap: 10px; font-size: 13.5px; font-weight: 600;
    }
    .alert-success { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
    .alert-danger { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }

    /* KODE UNIT SELECTOR */
    .unit-picker-box {
      display: none;
      margin-bottom: 16px;
      padding: 14px;
      border-radius: 12px;
      background: #fffbeb;
      border: 1.5px solid #fde68a;
    }
    .unit-picker-box.show { display: block; }
    .unit-chips-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(105px, 1fr));
      gap: 8px;
      margin-top: 10px;
    }
    .unit-chip {
      position: relative;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 9px 8px;
      border-radius: 10px;
      border: 1.5px solid #cbd5e1;
      background: #fff;
      cursor: pointer;
      transition: all .18s ease;
      text-align: center;
      user-select: none;
    }
    .unit-chip input[type="checkbox"] {
      position: absolute;
      opacity: 0;
      pointer-events: none;
    }
    .unit-chip:hover:not(.disabled) {
      border-color: var(--primary);
      background: #fffdf5;
      transform: translateY(-1px);
    }
    .unit-chip.selected {
      border-color: #059669;
      background: #ecfdf5;
      box-shadow: 0 2px 10px rgba(16, 185, 129, 0.18);
    }
    .unit-chip.disabled {
      background: #f1f5f9;
      border-color: #e2e8f0;
      color: #94a3b8;
      cursor: not-allowed;
      opacity: 0.78;
    }
    .unit-chip-code {
      font-size: 13px;
      font-weight: 800;
      color: var(--text-primary);
      display: flex;
      align-items: center;
      gap: 5px;
    }
    .unit-chip.selected .unit-chip-code { color: #047857; }
    .unit-chip.disabled .unit-chip-code { color: #94a3b8; }
    .unit-chip-status {
      font-size: 10px;
      font-weight: 700;
      margin-top: 3px;
      padding: 1px 6px;
      border-radius: 99px;
    }
    .ucs-tersedia { background: #dcfce7; color: #15803d; }
    .ucs-pending { background: #fef3c7; color: #b45309; }
    .ucs-dipinjam { background: #fee2e2; color: #b91c1c; }
    .ucs-rusak { background: #e2e8f0; color: #475569; }
  </style>
</head>

<body>

  @include('partials.sidebar')

  <div class="main">
    <div class="topbar">
      <div class="topbar-left">
        <div>
          <div class="breadcrumb">
            <span>Pegawai</span> <i class="fas fa-chevron-right" style="font-size:10px;"></i> Peminjaman Barang Lainnya
          </div>
          <h1 class="topbar-title">⚡ Peminjaman Barang Lainnya</h1>
        </div>
      </div>
      <div>
        <span style="font-size: 13px; color: var(--text-secondary); font-weight: 500;">
          {{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
        </span>
      </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
      </div>
    @endif

    @if($errors->any())
      <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle"></i>
        <span>{{ $errors->first() }}</span>
      </div>
    @endif

    <div class="content-grid">

      <!-- FORM PENGAJUAN PINJAM -->
      <div class="form-card">
        <div class="form-header">
          <div class="form-header-icon">
            <i class="fas fa-plug"></i>
          </div>
          <div class="form-header-title">Form Pinjam Barang Lainnya</div>
          <div class="form-header-sub">Peralatan listrik, kabel roll, steker, adaptor, dll.</div>
        </div>

        <form method="POST" action="{{ route('pegawai.peminjaman-barang-lainnya.store') }}" class="form-body" onsubmit="return validateUnitSelection()">
          @csrf

          <div class="form-group">
            <label class="form-label"><i class="fas fa-box"></i> Pilih Barang Lainnya</label>
            <select name="barang_lainnya_id" id="barangLainnyaSelect" class="form-select" required onchange="updatePreview(this)">
              <option value="">-- Pilih Barang yang Tersedia --</option>
              @forelse($barangLainnya as $b)
                <option value="{{ $b->id }}"
                        data-nama="{{ $b->nama_barang }}"
                        data-kode="{{ $b->kode_barang }}"
                        data-satuan="{{ $b->satuan }}"
                        data-stok="{{ $b->jumlah }}"
                        {{ old('barang_lainnya_id') == $b->id ? 'selected' : '' }}>
                  ⚡ {{ $b->nama_barang }} (Stok Tersedia: {{ $b->jumlah }} {{ $b->satuan }})
                </option>
              @empty
                <option value="" disabled>Belum ada stok barang lainnya yang tersedia</option>
              @endforelse
            </select>

            <div class="facility-preview" id="previewBox">
              <div class="fp-icon"><i class="fas fa-plug"></i></div>
              <div>
                <div style="font-size:13px; font-weight:700;" id="prevNama">-</div>
                <div style="display:flex; gap:8px; margin-top:3px; flex-wrap:wrap;">
                  <span style="font-size:11px; background:#fef3c7; color:#b45309; padding:2px 8px; border-radius:5px; font-weight:600;" id="prevKode">-</span>
                  <span style="font-size:11px; background:#ecfdf5; color:#059669; padding:2px 8px; border-radius:5px; font-weight:600;" id="prevStok">-</span>
                </div>
              </div>
            </div>
          </div>

          <!-- PILIH KODE UNIT BARANG -->
          <div class="unit-picker-box" id="unitPickerBox">
            <div style="display:flex; align-items:center; justify-content:space-between; gap:8px; flex-wrap:wrap;">
              <label class="form-label" style="margin-bottom:0; color:#92400e;">
                <i class="fas fa-tag"></i> Pilih Kode Unit Barang <span style="color:var(--danger);">*</span>
              </label>
              <span id="selectedUnitSummary" style="font-size:11.5px; font-weight:700; color:#059669; background:#ecfdf5; border:1px solid #a7f3d0; padding:2px 9px; border-radius:99px;">
                Belum ada unit dipilih
              </span>
            </div>
            <div style="font-size:11.5px; color:#78350f; margin-top:4px;">
              Klik kode unit fisik yang ingin Anda pinjam (bisa pilih lebih dari 1 kode unit). Admin akan memverifikasi kode unit saat penyerahan.
            </div>
            <div class="unit-chips-grid" id="unitChipsContainer"></div>
          </div>

          <div class="input-row">
            <div class="form-group">
              <label class="form-label"><i class="fas fa-cubes"></i> Jumlah Pinjam</label>
              <input type="number" name="jumlah" id="inputJumlah" class="form-input" min="1" value="1" readonly style="background:#f8fafc; font-weight:700; color:#0f172a;" title="Otomatis mengikuti jumlah Kode Unit yang dipilih">
            </div>
            <div class="form-group">
              <label class="form-label"><i class="fas fa-tag"></i> Satuan</label>
              <input type="text" id="displaySatuan" class="form-input" value="unit" readonly style="background:#f1f5f9; color:#64748b;">
            </div>
          </div>

          <div class="input-row">
            <div class="form-group">
              <label class="form-label"><i class="fas fa-calendar-alt"></i> Tgl Pinjam</label>
              <input type="date" name="tanggal_pinjam" class="form-input" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
            </div>
            <div class="form-group">
              <label class="form-label"><i class="fas fa-calendar-check"></i> Est. Tgl Kembali</label>
              <input type="date" name="tanggal_kembali" class="form-input" value="{{ date('Y-m-d', strtotime('+3 days')) }}" min="{{ date('Y-m-d') }}">
            </div>
          </div>

          <div class="form-group">
            <label class="form-label"><i class="fas fa-clipboard-list"></i> Tujuan Penggunaan / Keperluan</label>
            <textarea name="keperluan" class="form-input" rows="3" placeholder="Contoh: Keperluan instalasi sound dan kelistrikan rapat koordinasi di Aula BPMP..." required></textarea>
          </div>

          <button type="submit" class="submit-btn">
            <i class="fas fa-paper-plane"></i> Ajukan Peminjaman
          </button>
        </form>
      </div>

      <!-- DAFTAR RIWAYAT PINJAM -->
      <div class="history-card">
        <div class="history-header">
          <div class="history-title">
            <i class="fas fa-history" style="color:var(--primary);"></i>
            <span>Riwayat Peminjaman ({{ $riwayat->count() }})</span>
          </div>
          <div class="filter-tabs">
            <button type="button" class="filter-tab active" onclick="filterStatus('all', this)">Semua</button>
            <button type="button" class="filter-tab" onclick="filterStatus('pending', this)">Menunggu</button>
            <button type="button" class="filter-tab" onclick="filterStatus('disetujui', this)">Disetujui</button>
            <button type="button" class="filter-tab" onclick="filterStatus('dikembalikan', this)">Kembali</button>
          </div>
        </div>

        <div class="req-list">
          @forelse($riwayat as $item)
            <div class="req-card" data-status="{{ $item->status }}">
              <div class="req-card-top">
                <div style="display:flex; align-items:center; gap:12px;">
                  <div class="req-card-icon">⚡</div>
                  <div>
                    <div style="font-size:14px; font-weight:700;">{{ $item->nama_barang }}</div>
                    <div style="font-size:11px; color:var(--text-secondary); margin-top:3px; display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
                      <span>Kode: <strong>{{ $item->kode_barang ?? '-' }}</strong></span>
                      @if(!empty($item->kode_unit))
                        <span style="background:#fef3c7; color:#b45309; border:1px solid #fde68a; padding:1px 8px; border-radius:6px; font-weight:800;">
                          🏷️ Kode Unit: {{ $item->kode_unit }}
                        </span>
                      @endif
                      <span>• Diajukan: {{ $item->created_at?->format('d/m/Y H:i') ?? '-' }}</span>
                    </div>
                  </div>
                </div>

                <div>
                  @if($item->status === 'pending')
                    <span class="status-badge pending"><i class="fas fa-clock"></i> Menunggu Persetujuan</span>
                  @elseif($item->status === 'disetujui')
                    <span class="status-badge disetujui"><i class="fas fa-check-circle"></i> Sedang Dipinjam</span>
                  @elseif($item->status === 'proses_pengembalian')
                    <span class="status-badge pending"><i class="fas fa-hourglass-half"></i> Verifikasi Kembali</span>
                  @elseif($item->status === 'dikembalikan')
                    <span class="status-badge dikembalikan"><i class="fas fa-undo"></i> Selesai / Dikembalikan</span>
                  @elseif($item->status === 'ditolak')
                    <span class="status-badge ditolak"><i class="fas fa-times-circle"></i> Ditolak</span>
                  @else
                    <span class="status-badge dibatalkan"><i class="fas fa-ban"></i> Dibatalkan</span>
                  @endif
                </div>
              </div>

              <div class="req-card-meta">
                <div>
                  <div class="meta-label">Jumlah & Kode Unit</div>
                  <div class="meta-value">
                    {{ $item->jumlah }} {{ $item->satuan }}
                    @if(!empty($item->kode_unit))
                      <span style="color:#b45309; font-weight:800;">(Unit {{ $item->kode_unit }})</span>
                    @endif
                  </div>
                </div>
                <div>
                  <div class="meta-label">Tgl Pinjam</div>
                  <div class="meta-value">{{ $item->tanggal_pinjam?->format('d/m/Y') ?? '-' }}</div>
                </div>
                <div>
                  <div class="meta-label">Est. Kembali</div>
                  <div class="meta-value">{{ $item->tanggal_kembali?->format('d/m/Y') ?? 'Tidak Ditentukan' }}</div>
                </div>
              </div>

              <div style="padding:10px 18px; background:#fff; display:flex; justify-content:space-between; align-items:center; border-top:1px solid #f1f5f9;">
                <div style="font-size:12px; color:var(--text-secondary); flex:1; margin-right:12px;">
                  <i class="fas fa-info-circle"></i> <strong>Keperluan:</strong> {{ Str::limit($item->keperluan, 60) }}
                </div>
                @if($item->status === 'pending')
                  <form method="POST" action="{{ route('pegawai.peminjaman-barang-lainnya.cancel', $item->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan ini?')">
                    @csrf
                    <button type="submit" style="padding:4px 10px; font-size:11.5px; border-radius:6px; background:#fef2f2; color:#dc2626; border:1px solid #fecaca; cursor:pointer; font-weight:600;">
                      <i class="fas fa-times"></i> Batalkan
                    </button>
                  </form>
                @endif
              </div>
            </div>
          @empty
            <div style="text-align:center; padding:40px; color:var(--text-secondary);">
              <div style="font-size:36px; margin-bottom:8px;">⚡</div>
              <strong>Belum ada riwayat peminjaman barang lainnya</strong>
              <p style="font-size:12.5px; margin-top:4px;">Silakan pilih barang lainnya pada form di sebelah kiri untuk mengajukan peminjaman kabel roll, steker, atau peralatan pendukung.</p>
            </div>
          @endforelse
        </div>
      </div>

    </div>
  </div>

  <script>
    const UNITS_BY_BARANG = @json($unitsByBarangId ?? []);
    const OLD_UNIT_IDS = (@json(old('unit_ids', [])) || []).map(Number);

    function escapeHtml(str) {
      return String(str ?? '').replace(/[&<>"']/g, s => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
      }[s]));
    }

    function updatePreview(select, preserveOld = false) {
      const opt = select.options[select.selectedIndex];
      const preview = document.getElementById('previewBox');
      const pickerBox = document.getElementById('unitPickerBox');
      const chipsContainer = document.getElementById('unitChipsContainer');

      if (!opt || !opt.value) {
        preview.classList.remove('show');
        pickerBox.classList.remove('show');
        chipsContainer.innerHTML = '';
        document.getElementById('displaySatuan').value = 'unit';
        document.getElementById('inputJumlah').value = 1;
        updateSelectedUnitsSummary();
        return;
      }

      document.getElementById('prevNama').textContent = opt.dataset.nama || '-';
      document.getElementById('prevKode').textContent = 'Kode: ' + (opt.dataset.kode || '-');
      document.getElementById('prevStok').textContent = 'Tersedia: ' + (opt.dataset.stok || 0) + ' ' + (opt.dataset.satuan || 'unit');
      document.getElementById('displaySatuan').value = opt.dataset.satuan || 'unit';
      preview.classList.add('show');

      renderUnitChips(opt.value, preserveOld);
      pickerBox.classList.add('show');
    }

    function renderUnitChips(barangId, preserveOld = false) {
      const container = document.getElementById('unitChipsContainer');
      const rawUnits = UNITS_BY_BARANG[barangId] || [];
      const units = [...rawUnits].sort((a, b) => {
        const codeA = String(a.kode_unit ?? '').trim();
        const codeB = String(b.kode_unit ?? '').trim();
        return codeA.localeCompare(codeB, undefined, { numeric: true, sensitivity: 'base' });
      });

      if (!units.length) {
        container.innerHTML = '<div style="grid-column:1/-1; font-size:12px; color:#dc2626; padding:8px;">Belum ada kode unit terdaftar untuk barang ini.</div>';
        updateSelectedUnitsSummary();
        return;
      }

      container.innerHTML = units.map(u => {
        const isAvailable = u.status === 'tersedia';
        const isPrechecked = isAvailable && preserveOld && OLD_UNIT_IDS.includes(Number(u.id));
        let statusText = 'Tersedia';
        let statusClass = 'ucs-tersedia';

        if (!isAvailable) {
          if (u.status === 'dipinjam') {
            statusText = 'Dipinjam';
            statusClass = 'ucs-dipinjam';
          } else {
            statusText = u.status === 'hilang' ? 'Hilang' : 'Rusak';
            statusClass = 'ucs-rusak';
          }
        } else if (u.pending_count > 0) {
          statusText = 'Ada Pengajuan';
          statusClass = 'ucs-pending';
        }

        return `
          <label class="unit-chip ${isAvailable ? '' : 'disabled'} ${isPrechecked ? 'selected' : ''}"
                 title="${isAvailable ? (u.pending_count > 0 ? 'Tersedia, namun sedang ada pengajuan pending dari pegawai lain' : 'Klik untuk memilih Kode ' + escapeHtml(u.kode_unit)) : 'Unit tidak dapat dipilih (' + statusText + ')'}">
            <input type="checkbox"
                   name="unit_ids[]"
                   value="${u.id}"
                   data-kode="${escapeHtml(u.kode_unit)}"
                   ${isAvailable ? '' : 'disabled'}
                   ${isPrechecked ? 'checked' : ''}
                   onchange="onUnitChipToggle(this)">
            <div class="unit-chip-code">
              <i class="fas ${isPrechecked ? 'fa-check-circle' : (isAvailable ? 'fa-tag' : 'fa-lock')}" style="font-size:11px;"></i>
              <span>Kode ${escapeHtml(u.kode_unit)}</span>
            </div>
            <span class="unit-chip-status ${statusClass}">${statusText}</span>
          </label>
        `;
      }).join('');

      updateSelectedUnitsSummary();
    }

    function onUnitChipToggle(checkbox) {
      const label = checkbox.closest('.unit-chip');
      const icon = label ? label.querySelector('.unit-chip-code i') : null;
      if (checkbox.checked) {
        label.classList.add('selected');
        if (icon) icon.className = 'fas fa-check-circle';
      } else {
        label.classList.remove('selected');
        if (icon) icon.className = 'fas fa-tag';
      }
      updateSelectedUnitsSummary();
    }

    function updateSelectedUnitsSummary() {
      const checked = Array.from(document.querySelectorAll('#unitChipsContainer input[type="checkbox"]:checked'));
      const summaryEl = document.getElementById('selectedUnitSummary');
      const inputJumlah = document.getElementById('inputJumlah');

      if (!checked.length) {
        summaryEl.textContent = 'Belum ada unit dipilih';
        summaryEl.style.background = '#fef2f2';
        summaryEl.style.color = '#dc2626';
        summaryEl.style.borderColor = '#fecaca';
        inputJumlah.value = 0;
        return;
      }

      const codes = checked.map(c => c.dataset.kode);
      summaryEl.textContent = `Dipilih (${codes.length}): Kode ${codes.join(', ')}`;
      summaryEl.style.background = '#ecfdf5';
      summaryEl.style.color = '#059669';
      summaryEl.style.borderColor = '#a7f3d0';
      inputJumlah.value = codes.length;
    }

    function validateUnitSelection() {
      const checked = document.querySelectorAll('#unitChipsContainer input[type="checkbox"]:checked');
      if (!checked.length) {
        alert('Silakan pilih minimal 1 Kode Unit barang terlebih dahulu (misalnya Kode A atau Kode B)!');
        return false;
      }
      return true;
    }

    function filterStatus(status, btn) {
      document.querySelectorAll('.filter-tab').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      document.querySelectorAll('.req-card').forEach(card => {
        if (status === 'all' || card.dataset.status === status || (status === 'disetujui' && card.dataset.status === 'proses_pengembalian')) {
          card.style.display = 'block';
        } else {
          card.style.display = 'none';
        }
      });
    }

    document.addEventListener('DOMContentLoaded', function() {
      const select = document.getElementById('barangLainnyaSelect');
      if (select && select.value) {
        updatePreview(select, true);
      }
    });
  </script>
</body>
</html>
