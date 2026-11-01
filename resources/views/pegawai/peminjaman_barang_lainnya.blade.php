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
      .main { margin-left: 0; width: 100%; padding: 16px; }
      .content-grid { grid-template-columns: 1fr; }
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

        <form method="POST" action="{{ route('pegawai.peminjaman-barang-lainnya.store') }}" class="form-body">
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
                        data-stok="{{ $b->jumlah }}">
                  ⚡ {{ $b->nama_barang }} (Stok: {{ $b->jumlah }} {{ $b->satuan }})
                </option>
              @empty
                <option value="" disabled>Belum ada stok barang lainnya yang tersedia</option>
              @endforelse
            </select>

            <div class="facility-preview" id="previewBox">
              <div class="fp-icon"><i class="fas fa-plug"></i></div>
              <div>
                <div style="font-size:13px; font-weight:700;" id="prevNama">-</div>
                <div style="display:flex; gap:8px; margin-top:3px;">
                  <span style="font-size:11px; background:#fef3c7; color:#b45309; padding:2px 8px; border-radius:5px; font-weight:600;" id="prevKode">-</span>
                  <span style="font-size:11px; background:#ecfdf5; color:#059669; padding:2px 8px; border-radius:5px; font-weight:600;" id="prevStok">-</span>
                </div>
              </div>
            </div>
          </div>

          <div class="input-row">
            <div class="form-group">
              <label class="form-label"><i class="fas fa-cubes"></i> Jumlah Pinjam</label>
              <input type="number" name="jumlah" id="inputJumlah" class="form-input" min="1" value="1" required>
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
                    <div style="font-size:11px; color:var(--text-secondary); margin-top:2px;">
                      Kode: <strong>{{ $item->kode_barang ?? '-' }}</strong> • 
                      Diajukan: {{ $item->created_at?->format('d/m/Y H:i') ?? '-' }}
                    </div>
                  </div>
                </div>

                <div>
                  @if($item->status === 'pending')
                    <span class="status-badge pending"><i class="fas fa-clock"></i> Menunggu Persetujuan</span>
                  @elseif($item->status === 'disetujui')
                    <span class="status-badge disetujui"><i class="fas fa-check-circle"></i> Sedang Dipinjam</span>
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
                  <div class="meta-label">Jumlah Pinjam</div>
                  <div class="meta-value">{{ $item->jumlah }} {{ $item->satuan }}</div>
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
    function updatePreview(select) {
      const opt = select.options[select.selectedIndex];
      const preview = document.getElementById('previewBox');
      if (!opt || !opt.value) {
        preview.classList.remove('show');
        document.getElementById('displaySatuan').value = 'unit';
        return;
      }

      document.getElementById('prevNama').textContent = opt.dataset.nama || '-';
      document.getElementById('prevKode').textContent = 'Kode: ' + (opt.dataset.kode || '-');
      document.getElementById('prevStok').textContent = 'Tersedia: ' + (opt.dataset.stok || 0) + ' ' + (opt.dataset.satuan || 'unit');
      document.getElementById('displaySatuan').value = opt.dataset.satuan || 'unit';
      document.getElementById('inputJumlah').max = opt.dataset.stok || 999;
      preview.classList.add('show');
    }

    function filterStatus(status, btn) {
      document.querySelectorAll('.filter-tab').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      document.querySelectorAll('.req-card').forEach(card => {
        if (status === 'all' || card.dataset.status === status) {
          card.style.display = 'block';
        } else {
          card.style.display = 'none';
        }
      });
    }
  </script>
</body>
</html>
