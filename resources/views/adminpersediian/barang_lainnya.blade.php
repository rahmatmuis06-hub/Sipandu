<!DOCTYPE html>
<html lang="id">
<head>
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIPANDU - Barang Lainnya</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
  :root {
    --primary: #d97706;
    --primary-light: #fef3c7;
    --blue: #4F6FFF;
    --sidebar-w: 240px;
    --radius: 16px;
    --bg: #F8FAFC;
    --surface: #FFFFFF;
    --text: #1E293B;
    --muted: #64748B;
    --border: #E2E8F0;
    --success: #10B981;
    --danger: #EF4444;
    --warning: #F59E0B;
  }
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); display: flex; min-height: 100vh; }

  .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }

  .topbar {
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    padding: 0 28px; height: 56px;
    display: flex; align-items: center; justify-content: space-between;
    position: sticky; top: 0; z-index: 50;
  }
  .topbar-title { font-size: 16px; font-weight: 700; }
  .topbar-right { display: flex; align-items: center; gap: 16px; }
  .date-text { font-size: 13px; color: #64748B; font-weight: 500; }

  .content { padding: 28px; flex: 1; }

  .alert {
    padding: 14px 18px; border-radius: 10px; margin-bottom: 20px;
    display: flex; align-items: center; gap: 10px; font-weight: 600; font-size: 13.5px;
  }
  .alert-success { background: #ECFDF5; color: var(--success); border: 1px solid #BBF7D0; }
  .alert-danger { background: #FEF2F2; color: var(--danger); border: 1px solid #FECACA; }

  .page-top {
    display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 14px;
  }
  .page-top h1 { font-size: 22px; font-weight: 800; color: #b45309; display: flex; align-items: center; gap: 8px; }
  .page-top p { font-size: 13px; color: var(--muted); margin-top: 4px; }

  .btn-tambah {
    display: flex; align-items: center; gap: 7px;
    padding: 10px 20px; border-radius: 10px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white; font-size: 13.5px; font-weight: 700;
    font-family: inherit; border: none; cursor: pointer;
    box-shadow: 0 4px 14px rgba(245, 158, 11, .35);
    transition: all .2s;
  }
  .btn-tambah:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(245, 158, 11, .45); }

  /* STATS CARDS */
  .stats-grid {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;
  }
  .stat-card {
    background: #fff; border: 1px solid var(--border); border-radius: 14px; padding: 18px 20px;
    display: flex; align-items: center; gap: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);
  }
  .stat-icon {
    width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
  }
  .stat-num { font-size: 22px; font-weight: 800; color: #0f172a; line-height: 1.2; }
  .stat-lbl { font-size: 12.5px; color: var(--muted); font-weight: 600; margin-top: 2px; }

  /* TABLE CARD */
  .table-card {
    background: var(--surface);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
  }
  .table-toolbar {
    display: flex; align-items: center; gap: 12px;
    padding: 16px 20px; border-bottom: 1px solid var(--border);
  }
  .search-wrap {
    flex: 1; display: flex; align-items: center; gap: 8px;
    border: 1.5px solid var(--border); border-radius: 10px;
    padding: 8px 14px; background: #fff;
  }
  .search-wrap input {
    border: none; background: none; outline: none;
    font-family: inherit; font-size: 13.5px; color: var(--text); width: 100%;
  }

  table { width: 100%; border-collapse: collapse; }
  thead tr { background: #FFFBEB; }
  th {
    padding: 12px 18px; text-align: left;
    font-size: 11.5px; font-weight: 700; color: #92400e;
    letter-spacing: .5px; text-transform: uppercase;
    border-bottom: 1px solid var(--border); white-space: nowrap;
  }
  td {
    padding: 14px 18px; font-size: 13px; color: var(--text);
    border-bottom: 1px solid var(--border); white-space: nowrap;
  }
  tbody tr:hover { background: #FEFCE8; }

  .badge-kode {
    background: #F1F5F9; color: #475569; font-family: monospace; font-weight: 700;
    padding: 3px 8px; border-radius: 6px; font-size: 12px;
  }
  .badge-stok {
    display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 20px;
    font-size: 12px; font-weight: 700;
  }
  .badge-stok-aman { background: #ECFDF5; color: #059669; }
  .badge-stok-kritis { background: #FEF2F2; color: #DC2626; }

  .action-btns { display: flex; gap: 6px; }
  .btn-action {
    width: 32px; height: 32px; border-radius: 8px; border: 1px solid var(--border);
    background: #fff; display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: var(--muted); transition: all .15s;
  }
  .btn-action:hover { background: #FEF3C7; color: #D97706; border-color: #FCD34D; }
  .btn-action.danger:hover { background: #FEF2F2; color: #EF4444; border-color: #FECACA; }

  /* MODALS */
  .modal-overlay {
    position: fixed; inset: 0; background: rgba(15, 23, 42, 0.45);
    backdrop-filter: blur(4px); z-index: 1000;
    display: none; align-items: center; justify-content: center; padding: 20px;
  }
  .modal-overlay.show { display: flex; }
  .modal {
    background: #fff; border-radius: 16px; width: 100%; max-width: 600px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.12); overflow: hidden;
    animation: modalIn .2s ease-out;
  }
  @keyframes modalIn { from { opacity: 0; transform: translateY(12px) scale(.98); } to { opacity: 1; transform: translateY(0) scale(1); } }

  .form-group { margin-bottom: 14px; }
  .form-label { font-size: 12.5px; font-weight: 600; color: #334155; margin-bottom: 5px; display: block; }
  .form-input, .form-select {
    width: 100%; padding: 9px 12px; border-radius: 8px; border: 1px solid var(--border);
    font-family: inherit; font-size: 13px; outline: none; transition: border-color .15s; background: #fff;
  }
  .form-input:focus, .form-select:focus { border-color: var(--primary); }
  .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

  .btn {
    padding: 9px 18px; border-radius: 8px; font-size: 13px; font-weight: 700;
    font-family: inherit; border: none; cursor: pointer; transition: all .15s;
  }
  .btn-secondary { background: var(--bg); border: 1px solid var(--border); color: var(--muted); }
  .btn-secondary:hover { background: #e2e8f0; color: var(--text); }
  .btn-primary { background: var(--primary); color: #fff; }
  .btn-primary:hover { background: #b45309; }
</style>
</head>
<body>

@include('partials.sidebar')

<main class="main">
  <div class="topbar">
    <span class="topbar-title">Menu Khusus: Barang Lainnya</span>
    <div class="topbar-right">
      <span class="date-text">{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
    </div>
  </div>

  <div class="content">

    @if(session('success'))
      <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
      </div>
    @endif

    <div class="page-top">
      <div>
        <h1>⚡ Barang Lainnya</h1>
        <p>Pengelolaan khusus peralatan listrik, steker, colokan, kabel roll, baterai, dan barang pendukung operasional.</p>
      </div>
      <div>
        <button type="button" onclick="openModal('modalTambahLainnya')" class="btn-tambah">
          <i class="fas fa-plus"></i> Tambah Barang Lainnya
        </button>
      </div>
    </div>

    <!-- STATS CARDS -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon" style="background:#fef3c7; color:#d97706;">
          <i class="fas fa-plug"></i>
        </div>
        <div>
          <div class="stat-num">{{ $stats['total_item'] ?? 0 }}</div>
          <div class="stat-lbl">Jenis Barang Terdaftar</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:#ecfdf5; color:#059669;">
          <i class="fas fa-cubes"></i>
        </div>
        <div>
          <div class="stat-num">{{ number_format($stats['total_stok'] ?? 0) }}</div>
          <div class="stat-lbl">Total Unit Stok Tersedia</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:#eff6ff; color:#2563eb;">
          <i class="fas fa-coins"></i>
        </div>
        <div>
          <div class="stat-num">Rp {{ number_format($stats['total_nilai'] ?? 0, 0, ',', '.') }}</div>
          <div class="stat-lbl">Total Nilai Persediaan</div>
        </div>
      </div>
    </div>

    <!-- TABLE CARD -->
    <div class="table-card">
      <div class="table-toolbar">
        <form method="GET" action="{{ route('adminpersediaan.barang-lainnya') }}" class="search-wrap">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="#94A3B8">
            <path d="M15.5 14h-.79l-.28-.27A6.47 6.47 0 0016 9.5 6.5 6.5 0 109.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
          </svg>
          <input type="text" name="search" placeholder="Cari nama barang, steker, kabel roll, baterai..." value="{{ request('search') }}">
        </form>
      </div>

      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Tanggal Masuk</th>
            <th>Satuan</th>
            <th>Harga Satuan</th>
            <th>Total Nilai</th>
            <th>Stok Tersedia</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($barangLainnya as $idx => $item)
            @php
              $nama = strtoupper($item->nama_barang);
              $icon = '⚡';
              if (str_contains($nama, 'STEKER') || str_contains($nama, 'COLOKAN') || str_contains($nama, 'PLUG')) $icon = '🔌';
              elseif (str_contains($nama, 'KABEL') || str_contains($nama, 'ROLL')) $icon = '🌀';
              elseif (str_contains($nama, 'BATERAI')) $icon = '🔋';
              elseif (str_contains($nama, 'LAMPU')) $icon = '💡';
              elseif (str_contains($nama, 'ISOLASI') || str_contains($nama, 'LAKBAN')) $icon = '🩹';
            @endphp
            <tr>
              <td><strong>{{ $barangLainnya->firstItem() + $idx }}</strong></td>
              <td><span class="badge-kode">{{ $item->kode_unik_barang ?? ($item->kode_kategori . '-' . $item->kode_barang) }}</span></td>
              <td>
                <span style="font-size:15px; margin-right:4px;">{{ $icon }}</span>
                <strong>{{ $item->nama_barang }}</strong>
              </td>
              <td>{{ $item->tanggal_masuk?->format('d/m/Y') ?? '-' }}</td>
              <td><span style="font-weight:600; text-transform:capitalize;">{{ $item->satuan }}</span></td>
              <td>Rp {{ number_format($item->harga_satuan ?? 0, 0, ',', '.') }}</td>
              <td><strong style="color:#15803d;">Rp {{ number_format($item->harga_total ?? 0, 0, ',', '.') }}</strong></td>
              <td>
                <span class="badge-stok {{ $item->jumlah > 5 ? 'badge-stok-aman' : 'badge-stok-kritis' }}">
                  {{ number_format($item->jumlah) }} {{ $item->satuan }}
                </span>
              </td>
              <td>
                <div class="action-btns">
                  <button type="button" class="btn-action" title="Detail" onclick="openDetail({{ json_encode($item) }})">
                    <i class="fas fa-eye"></i>
                  </button>
                  <button type="button" class="btn-action" title="Edit" onclick="openEdit({{ json_encode($item) }})">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button type="button" class="btn-action danger" title="Hapus" onclick="openDelete({{ $item->id }}, '{{ addslashes($item->nama_barang) }}')">
                    <i class="fas fa-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" style="text-align: center; padding: 40px; color: var(--muted);">
                <div style="font-size: 32px; margin-bottom: 8px;">⚡</div>
                <strong>Belum ada barang di kategori Barang Lainnya</strong>
                <p style="font-size: 12.5px; margin-top: 4px;">Klik tombol "Tambah Barang Lainnya" untuk mendaftarkan colokan, steker, atau perlengkapan kantor lainnya.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>

      <div style="padding: 16px 20px; border-top: 1px solid var(--border);">
        {{ $barangLainnya->links() }}
      </div>
    </div>

  </div>
</main>

{{-- MODAL TAMBAH BARANG LAINNYA --}}
<div id="modalTambahLainnya" class="modal-overlay">
  <div class="modal">
    <div style="padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
      <div style="display: flex; align-items: center; gap: 10px;">
        <div style="width: 38px; height: 38px; border-radius: 10px; background: #FEF3C7; color: #D97706; display: flex; align-items: center; justify-content: center; font-size: 18px;">
          ⚡
        </div>
        <div>
          <div style="font-size: 16px; font-weight: 800;">Tambah Barang Lainnya</div>
          <div style="font-size: 12px; color: var(--muted);">Pilih template cepat atau input barang manual</div>
        </div>
      </div>
      <button type="button" onclick="closeModal('modalTambahLainnya')" class="btn-action">✕</button>
    </div>

    <form method="POST" action="{{ route('adminpersediaan.barang-lainnya.store') }}">
      @csrf
      <div style="padding: 20px 24px; max-height: 70vh; overflow-y: auto;">
        
        <!-- Pilihan Cepat Template -->
        <div class="form-group" style="background: #FFFBEB; border: 1.5px dashed #FCD34D; border-radius: 10px; padding: 12px;">
          <label class="form-label" style="color: #92400E; margin-bottom: 4px;">⚡ Template Barang Cepat</label>
          <select id="presetBarangLainnya" class="form-select" onchange="pilihPresetBarangLainnya(this)">
            <option value="">-- Ketik Manual atau Pilih Template di Sini --</option>
            <option value="STEKER / COLOKAN LISTRIK ARDE" data-satuan="buah" data-harga="15000">🔌 Steker / Colokan Listrik Arde (Buah - Rp 15.000)</option>
            <option value="STOPKONTAK KABEL ROLL 10 METER" data-satuan="unit" data-harga="75000">🌀 Stopkontak Kabel Roll 10 Meter (Unit - Rp 75.000)</option>
            <option value="STOPKONTAK KABEL ROLL 15 METER" data-satuan="unit" data-harga="110000">🌀 Stopkontak Kabel Roll 15 Meter (Unit - Rp 110.000)</option>
            <option value="T-PLUG / STOP KONTAK CABANG 3" data-satuan="buah" data-harga="25000">🔌 T-Plug / Cabang 3 (Buah - Rp 25.000)</option>
            <option value="ADAPTOR STEKER LISTRIK UNIVERSAL" data-satuan="buah" data-harga="20000">🔌 Adaptor Steker Universal Travel (Buah - Rp 20.000)</option>
            <option value="KABEL EXTENSION / SAMBUNGAN LISTRIK 5M" data-satuan="unit" data-harga="45000">🔌 Kabel Extension / Sambungan Listrik 5M (Unit - Rp 45.000)</option>
            <option value="BATERAI AA ALKALINE (ISI 2)" data-satuan="pasang" data-harga="18000">🔋 Baterai AA Alkaline (Pasang - Rp 18.000)</option>
            <option value="BATERAI AAA ALKALINE (ISI 2)" data-satuan="pasang" data-harga="18000">🔋 Baterai AAA Alkaline (Pasang - Rp 18.000)</option>
            <option value="LAKBAN / ISOLASI LISTRIK HITAM" data-satuan="roll" data-harga="12000">🩹 Lakban / Isolasi Listrik Hitam (Roll - Rp 12.000)</option>
            <option value="KABEL HDMI HIGH SPEED 5 METER" data-satuan="unit" data-harga="65000">🖥️ Kabel HDMI High Speed 5M (Unit - Rp 65.000)</option>
            <option value="LAMPU LED HEMAT ENERGI 14 WATT" data-satuan="buah" data-harga="45000">💡 Lampu LED Hemat Energi 14W (Buah - Rp 45.000)</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Nama Barang <span style="color:var(--danger)">*</span></label>
          <input type="text" name="nama_barang" id="inputNamaLainnya" class="form-input" placeholder="Contoh: STOPKONTAK KABEL ROLL 10 METER" required>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Satuan <span style="color:var(--danger)">*</span></label>
            <select name="satuan" id="inputSatuanLainnya" class="form-select" required>
              <option value="buah">Buah</option>
              <option value="unit">Unit</option>
              <option value="roll">Roll</option>
              <option value="pasang">Pasang</option>
              <option value="dos">Dos</option>
              <option value="set">Set</option>
              <option value="box">Box</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Jumlah Stok Masuk <span style="color:var(--danger)">*</span></label>
            <input type="number" name="jumlah" id="inputJumlahLainnya" class="form-input" min="1" value="10" required oninput="hitungSubtotalTambah()">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Harga Satuan (Rp) <span style="color:var(--danger)">*</span></label>
            <input type="text" name="harga_satuan" id="inputHargaLainnya" class="form-input" placeholder="0" required oninput="handlePriceInput(this); hitungSubtotalTambah();">
          </div>
          <div class="form-group">
            <label class="form-label">Tanggal Masuk <span style="color:var(--danger)">*</span></label>
            <input type="date" name="tanggal_masuk" class="form-input" value="{{ date('Y-m-d') }}" required>
          </div>
        </div>

        <div class="form-group">
          <div style="background: #F8FAFC; border: 1px solid var(--border); border-radius: 8px; padding: 10px 14px; display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 12.5px; font-weight: 600; color: #475569;">Estimasi Nilai Total:</span>
            <span id="labelTotalNilaiTambah" style="font-size: 15px; font-weight: 800; color: #16A34A;">Rp 0</span>
          </div>
        </div>

      </div>

      <div style="padding: 16px 24px; border-top: 1px solid var(--border); background: #FAFBFF; display: flex; justify-content: flex-end; gap: 10px;">
        <button type="button" onclick="closeModal('modalTambahLainnya')" class="btn btn-secondary">Batal</button>
        <button type="submit" class="btn btn-primary" style="background:linear-gradient(135deg,#f59e0b,#d97706);">Simpan Barang</button>
      </div>
    </form>
  </div>
</div>

{{-- MODAL DETAIL --}}
<div id="modalDetail" class="modal-overlay">
  <div class="modal">
    <div style="padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
      <h3 style="font-size: 16px; font-weight: 800;">Detail Barang Lainnya</h3>
      <button type="button" onclick="closeModal('modalDetail')" class="btn-action">✕</button>
    </div>
    <div style="padding: 20px 24px; font-size: 13.5px;" id="detailBody">
      <!-- Injected by JS -->
    </div>
    <div style="padding: 14px 24px; border-top: 1px solid var(--border); background: #FAFBFF; display: flex; justify-content: flex-end;">
      <button type="button" onclick="closeModal('modalDetail')" class="btn btn-secondary">Tutup</button>
    </div>
  </div>
</div>

{{-- MODAL EDIT BARANG LAINNYA --}}
<div id="modalEditLainnya" class="modal-overlay">
  <div class="modal">
    <div style="padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
      <div style="display: flex; align-items: center; gap: 10px;">
        <div style="width: 38px; height: 38px; border-radius: 10px; background: #FEF3C7; color: #D97706; display: flex; align-items: center; justify-content: center; font-size: 18px;">
          ✏️
        </div>
        <div>
          <div style="font-size: 16px; font-weight: 800;">Edit Barang Lainnya</div>
          <div style="font-size: 12px; color: var(--muted);">Perbarui informasi stok dan harga barang</div>
        </div>
      </div>
      <button type="button" onclick="closeModal('modalEditLainnya')" class="btn-action">✕</button>
    </div>

    <form id="formEditLainnya" method="POST">
      @csrf
      @method('PUT')
      <div style="padding: 20px 24px; max-height: 70vh; overflow-y: auto;">
        
        <div class="form-group">
          <label class="form-label">Nama Barang <span style="color:var(--danger)">*</span></label>
          <input type="text" name="nama_barang" id="editNamaLainnya" class="form-input" required>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Satuan <span style="color:var(--danger)">*</span></label>
            <select name="satuan" id="editSatuanLainnya" class="form-select" required>
              <option value="buah">Buah</option>
              <option value="unit">Unit</option>
              <option value="roll">Roll</option>
              <option value="pasang">Pasang</option>
              <option value="dos">Dos</option>
              <option value="set">Set</option>
              <option value="box">Box</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Jumlah Stok <span style="color:var(--danger)">*</span></label>
            <input type="number" name="jumlah" id="editJumlahLainnya" class="form-input" min="0" required oninput="hitungSubtotalEdit()">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Harga Satuan (Rp) <span style="color:var(--danger)">*</span></label>
            <input type="text" name="harga_satuan" id="editHargaLainnya" class="form-input" placeholder="0" required oninput="handlePriceInput(this); hitungSubtotalEdit();">
          </div>
          <div class="form-group">
            <label class="form-label">Tanggal Masuk <span style="color:var(--danger)">*</span></label>
            <input type="date" name="tanggal_masuk" id="editTanggalLainnya" class="form-input" required>
          </div>
        </div>

        <div class="form-group">
          <div style="background: #F8FAFC; border: 1px solid var(--border); border-radius: 8px; padding: 10px 14px; display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 12.5px; font-weight: 600; color: #475569;">Estimasi Nilai Total:</span>
            <span id="labelTotalNilaiEdit" style="font-size: 15px; font-weight: 800; color: #16A34A;">Rp 0</span>
          </div>
        </div>

      </div>

      <div style="padding: 16px 24px; border-top: 1px solid var(--border); background: #FAFBFF; display: flex; justify-content: flex-end; gap: 10px;">
        <button type="button" onclick="closeModal('modalEditLainnya')" class="btn btn-secondary">Batal</button>
        <button type="submit" class="btn btn-primary" style="background:linear-gradient(135deg,#f59e0b,#d97706);">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

{{-- MODAL DELETE --}}
<div id="modalDelete" class="modal-overlay">
  <div class="modal" style="max-width: 440px;">
    <div style="padding: 24px; text-align: center;">
      <div style="width: 56px; height: 56px; border-radius: 16px; background: #FEF2F2; color: #DC2626; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 24px;">
        <i class="fas fa-trash"></i>
      </div>
      <h3 style="font-size: 18px; font-weight: 800; margin-bottom: 6px;">Hapus Barang?</h3>
      <p style="font-size: 13px; color: var(--muted); margin-bottom: 14px;">Barang <strong id="deleteNamaBarang">-</strong> akan dihapus permanen dari daftar Barang Lainnya.</p>
      <form id="formDelete" method="POST">
        @csrf
        @method('DELETE')
        <div style="display: flex; gap: 10px; justify-content: center;">
          <button type="button" onclick="closeModal('modalDelete')" class="btn btn-secondary">Batal</button>
          <button type="submit" class="btn btn-primary" style="background: #DC2626;">Ya, Hapus</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function openModal(id) {
  document.getElementById(id).classList.add('show');
}
function closeModal(id) {
  if (id) {
    document.getElementById(id).classList.remove('show');
  } else {
    document.querySelectorAll('.modal-overlay.show').forEach(m => m.classList.remove('show'));
  }
}

document.querySelectorAll('.modal-overlay').forEach(m => {
  m.addEventListener('click', e => { if (e.target === m) closeModal(); });
});

function pilihPresetBarangLainnya(el) {
  const opt = el.options[el.selectedIndex];
  if (!opt || !opt.value) return;

  document.getElementById('inputNamaLainnya').value = opt.value;
  if (opt.dataset.satuan) {
    document.getElementById('inputSatuanLainnya').value = opt.dataset.satuan;
  }
  if (opt.dataset.harga) {
    document.getElementById('inputHargaLainnya').value = formatRupiah(opt.dataset.harga);
  }
  hitungSubtotalTambah();
}

function handlePriceInput(input) {
  const clean = input.value.replace(/\D/g, '');
  input.value = clean ? formatRupiah(clean) : '';
}

function formatRupiah(num) {
  return new Intl.NumberFormat('id-ID').format(num || 0);
}

function hitungSubtotalTambah() {
  const qty = parseFloat(document.getElementById('inputJumlahLainnya').value) || 0;
  const hargaRaw = document.getElementById('inputHargaLainnya').value.replace(/\D/g, '');
  const harga = parseFloat(hargaRaw) || 0;
  const total = qty * harga;
  document.getElementById('labelTotalNilaiTambah').innerText = 'Rp ' + formatRupiah(total);
}

function openDetail(item) {
  const body = document.getElementById('detailBody');
  body.innerHTML = `
    <div style="display:flex; flex-direction:column; gap:12px;">
      <div><span style="color:#64748b; font-size:12px;">Kode Unik:</span><br><strong>${item.kode_unik_barang || '-'}</strong></div>
      <div><span style="color:#64748b; font-size:12px;">Nama Barang:</span><br><strong style="font-size:15px; color:#d97706;">${item.nama_barang}</strong></div>
      <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
        <div><span style="color:#64748b; font-size:12px;">Kategori:</span><br><strong>${item.kategori || 'Barang Lainnya'}</strong></div>
        <div><span style="color:#64748b; font-size:12px;">Satuan:</span><br><strong style="text-transform:capitalize;">${item.satuan || '-'}</strong></div>
      </div>
      <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
        <div><span style="color:#64748b; font-size:12px;">Stok Tersedia:</span><br><strong style="color:#059669; font-size:16px;">${item.jumlah} ${item.satuan}</strong></div>
        <div><span style="color:#64748b; font-size:12px;">Harga Satuan:</span><br><strong>Rp ${formatRupiah(item.harga_satuan)}</strong></div>
      </div>
      <div><span style="color:#64748b; font-size:12px;">Total Nilai Persediaan:</span><br><strong style="color:#15803d; font-size:16px;">Rp ${formatRupiah(item.harga_total)}</strong></div>
    </div>
  `;
  openModal('modalDetail');
}

function hitungSubtotalEdit() {
  const qty = parseFloat(document.getElementById('editJumlahLainnya').value) || 0;
  const hargaRaw = document.getElementById('editHargaLainnya').value.replace(/\D/g, '');
  const harga = parseFloat(hargaRaw) || 0;
  const total = qty * harga;
  document.getElementById('labelTotalNilaiEdit').innerText = 'Rp ' + formatRupiah(total);
}

function openEdit(item) {
  document.getElementById('formEditLainnya').action = `/adminpersediaan/barang-lainnya/${item.id}`;
  document.getElementById('editNamaLainnya').value = item.nama_barang;
  document.getElementById('editSatuanLainnya').value = (item.satuan || 'buah').toLowerCase();
  document.getElementById('editJumlahLainnya').value = item.jumlah;
  document.getElementById('editHargaLainnya').value = formatRupiah(item.harga_satuan);
  if (item.tanggal_masuk) {
    document.getElementById('editTanggalLainnya').value = item.tanggal_masuk.substring(0, 10);
  } else {
    document.getElementById('editTanggalLainnya').value = new Date().toISOString().substring(0, 10);
  }
  hitungSubtotalEdit();
  openModal('modalEditLainnya');
}

function openDelete(id, nama) {
  document.getElementById('deleteNamaBarang').innerText = nama;
  document.getElementById('formDelete').action = `/adminpersediaan/data-persediaan/${id}`;
  openModal('modalDelete');
}
</script>

</body>
</html>
