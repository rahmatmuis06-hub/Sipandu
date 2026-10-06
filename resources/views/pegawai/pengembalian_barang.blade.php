<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIPANDU - Pengembalian Barang (Pegawai)</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
  :root {
    --primary: #2563eb; --primary-light: #3b82f6;
    --accent: #06b6d4; --accent2: #8b5cf6;
    --success: #10b981; --warning: #f59e0b; --danger: #ef4444;
    --bg: #f0f4ff; --sidebar-bg: #0f172a; --sidebar-text: #94a3b8;
    --card-bg: #ffffff; --text-primary: #0f172a; --text-secondary: #64748b;
    --border: #e2e8f0; --radius: 16px; --radius-sm: 10px;
    --shadow: 0 4px 24px rgba(37,99,235,0.08);
  }

  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text-primary); display: flex; min-height: 100vh; }

  .main { margin-left: 260px; flex: 1; padding: 0 32px 40px; }

  .topbar { display: flex; align-items: center; justify-content: space-between; padding: 20px 0 24px; position: sticky; top: 0; z-index: 50; background: var(--bg); border-bottom: 1px solid transparent; }
  .breadcrumb { font-size: 13px; color: var(--text-secondary); display: flex; align-items: center; gap: 6px; }
  .breadcrumb span { color: var(--primary); font-weight: 600; }
  .topbar-title { font-family: 'Space Grotesk', sans-serif; font-size: 22px; font-weight: 700; }
  
  .content-grid { display: grid; grid-template-columns: 1fr 1.4fr; gap: 28px; }

  /* FORM CARD */
  .form-card { background: var(--card-bg); border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden; position: sticky; top: 90px; height: fit-content; }
  .form-header { padding: 24px 28px 20px; background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%); position: relative; overflow: hidden; }
  .form-header::before { content: ''; position: absolute; right: -30px; top: -30px; width: 120px; height: 120px; border-radius: 50%; background: rgba(255,255,255,0.08); }
  .form-header-icon { position: relative; z-index: 1; width: 46px; height: 46px; background: rgba(255,255,255,0.2); border-radius: 13px; display: grid; place-items: center; font-size: 20px; color: #fff; margin-bottom: 12px; backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.2); }
  .form-header-title { font-family: 'Space Grotesk', sans-serif; font-size: 18px; font-weight: 700; color: #fff; }
  .form-header-sub { font-size: 12px; color: rgba(255,255,255,0.75); margin-top: 4px; }

  .form-body { padding: 24px 28px; }
  .form-group { margin-bottom: 18px; }
  .form-label { display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: .6px; margin-bottom: 8px; }
  .form-label .req { color: var(--danger); }
  
  .form-input, .form-select, .form-textarea { width: 100%; padding: 11px 14px; border: 1.5px solid var(--border); border-radius: 10px; font-size: 13px; font-family: inherit; color: var(--text-primary); background: #fff; transition: all .2s; outline: none; }
  .form-input:focus, .form-select:focus, .form-textarea:focus { border-color: var(--danger); box-shadow: 0 0 0 3px rgba(239,68,68,0.1); }
  .form-select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 14px center; padding-right: 36px; cursor: pointer; }
  .form-textarea { resize: vertical; min-height: 90px; }
  .input-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

  .barang-preview { display: none; margin-top: 8px; padding: 12px 14px; border-radius: 10px; background: linear-gradient(135deg, #fef2f2, #fee2e2); border: 1px solid #fecaca; }
  .barang-preview.show { display: flex; align-items: center; gap: 12px; }
  .bp-icon { width: 36px; height: 36px; border-radius: 9px; background: var(--danger); display: grid; place-items: center; color: #fff; font-size: 15px; flex-shrink: 0; }
  .bp-name { font-size: 13px; font-weight: 700; color: var(--text-primary); }
  .bp-details { display: flex; gap: 10px; margin-top: 3px; }
  .bp-tag { font-size: 10px; background: rgba(239,68,68,0.1); color: var(--danger); padding: 2px 8px; border-radius: 5px; font-weight: 600; }

  .submit-btn { width: 100%; padding: 13px; background: linear-gradient(135deg, var(--danger), #dc2626); color: #fff; border: none; border-radius: 11px; font-size: 14px; font-weight: 700; font-family: inherit; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all .2s; margin-top: 8px; }
  .submit-btn:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(239,68,68,0.4); }
  .submit-btn:disabled { opacity: 0.6; cursor: not-allowed; }

  /* HISTORY CARD */
  .history-card { background: var(--card-bg); border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden; }
  .history-header { padding: 22px 28px 18px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border); }
  .history-title { font-family: 'Space Grotesk', sans-serif; font-size: 17px; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 8px; }
  
  .filter-tabs { display: flex; gap: 6px; }
  .filter-tab { font-size: 11px; font-weight: 600; padding: 5px 12px; border-radius: 7px; cursor: pointer; border: 1.5px solid var(--border); background: transparent; color: var(--text-secondary); transition: all .2s; font-family: inherit; }
  .filter-tab.active { background: var(--danger); color: #fff; border-color: var(--danger); }

  .req-list { padding: 20px 28px; display: flex; flex-direction: column; gap: 16px; }
  .req-card { border: 1.5px solid var(--border); border-radius: 14px; overflow: hidden; transition: all .2s; }
  .req-card-top { padding: 16px 18px; display: flex; align-items: flex-start; justify-content: space-between; }
  .req-card-icon { width: 42px; height: 42px; border-radius: 11px; display: grid; place-items: center; font-size: 17px; flex-shrink: 0; background:rgba(37,99,235,0.1); color:#2563eb; }
  .req-card-name { font-size: 14px; font-weight: 700; color: var(--text-primary); }
  .req-card-code { font-size: 11px; color: var(--text-secondary); margin-top: 2px; }
  
  .status-badge { font-size: 11px; font-weight: 700; padding: 4px 11px; border-radius: 7px; display: flex; align-items: center; gap: 5px; }
  .status-badge.pending { background: rgba(245,158,11,0.1); color: var(--warning); border: 1px solid rgba(245,158,11,0.2); }
  .status-badge.diterima { background: rgba(16,185,129,0.1); color: var(--success); border: 1px solid rgba(16,185,129,0.2); }
  .status-badge.ditolak { background: rgba(239,68,68,0.1); color: var(--danger); border: 1px solid rgba(239,68,68,0.2); }

  .req-card-meta { padding: 12px 18px; background: #f8fafc; display: grid; grid-template-columns: 1fr 1fr; gap: 10px; border-top: 1px solid var(--border); }
  .meta-label { font-size: 10px; text-transform: uppercase; color: #94a3b8; font-weight: 700; margin-bottom: 3px; }
  .meta-value { font-size: 12px; font-weight: 600; color: var(--text-primary); }

  .req-card-footer { padding: 12px 18px; display: flex; gap: 8px; border-top: 1px solid var(--border); background: #fff; }
  .card-btn { flex: 1; padding: 9px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; border: none; display: flex; align-items: center; justify-content: center; gap: 6px; font-family: inherit; transition: all .2s; text-decoration: none; }
  .card-btn.detail { background: rgba(37,99,235,0.08); color: var(--primary); }
  .card-btn.detail:hover { background: rgba(37,99,235,0.15); }
  .card-btn.cancel { background: rgba(239,68,68,0.08); color: var(--danger); }
  .card-btn.cancel:hover { background: rgba(239,68,68,0.15); }

  /* MODAL DETAIL */
  .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px); }
  .modal-box { background: #fff; width: 90%; max-width: 550px; border-radius: 16px; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
</style>
</head>
<body>

@include('partials.sidebar')

<main class="main">
  <div class="topbar">
    <div class="topbar-left">
      <div>
        <div class="breadcrumb"><a href="{{ route('pegawai.dashboard') }}" style="text-decoration:none;color:var(--text-secondary)">Dashboard</a> <i class="fas fa-chevron-right" style="font-size:10px"></i> <span>Pengembalian Barang</span></div>
        <div class="topbar-title">Pengembalian Barang</div>
      </div>
    </div>
  </div>

  @if(session('success'))
    <div style="background:#DCFCE7; color:#16A34A; padding:12px 16px; border-radius:10px; margin-bottom:20px; font-size:13px; font-weight:600; border: 1px solid #bbf7d0;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
  @endif

  @if($errors->any())
    <div style="background:#FEE2E2; color:#DC2626; padding:12px 16px; border-radius:10px; margin-bottom:20px; font-size:13px; font-weight:600; border: 1px solid #fecaca;">
        <ul style="padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
  @endif

  <div class="content-grid">
    <!-- FORM SECTION -->
    <div class="form-card" id="formCard">
      <div class="form-header">
        <div class="form-header-icon"><i class="fas fa-box-open"></i></div>
        <div class="form-header-title">Lapor Pengembalian</div>
        <div class="form-header-sub">Isi form ini untuk mengembalikan barang yang sedang dipinjam</div>
      </div>
      
      <div class="form-body">
        <form action="{{ route('pegawai.pengembalian-barang.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="form-group">
            <div class="form-label"><i class="fas fa-clipboard-list"></i> Pilih Peminjaman Aktif <span class="req">*</span></div>
            <select name="peminjaman_barang_id" class="form-select" id="peminjamanSelect" onchange="onPeminjamanChange()" required>
              <option value="">-- Pilih Barang yang Akan Dikembalikan --</option>
              @foreach($peminjamanAktif as $pinjam)
                <option value="{{ $pinjam->id }}" 
                        data-name="{{ $pinjam->nama_barang }}" 
                        data-code="{{ $pinjam->kode_barang }}" 
                        data-jumlah="{{ $pinjam->jumlah }}">
                  {{ $pinjam->nama_barang }} (Pinjam: {{ $pinjam->jumlah }} Unit)
                </option>
              @endforeach
            </select>
            
            <div class="barang-preview" id="barangPreview">
              <div class="bp-icon"><i class="fas fa-box" id="bpIcon"></i></div>
              <div>
                <div class="bp-name" id="bpName">-</div>
                <div class="bp-details">
                  <span class="bp-tag" id="bpCode">-</span>
                </div>
              </div>
            </div>
          </div>

          <div class="input-row">
            <div class="form-group">
              <div class="form-label"><i class="fas fa-calendar-check"></i> Tanggal Kembali <span class="req">*</span></div>
              <input type="date" name="tanggal_pengembalian_aktual" class="form-input" id="tglPengembalian" required>
            </div>
            <div class="form-group">
              <div class="form-label"><i class="fas fa-clock"></i> Jam <span class="req">*</span></div>
              <input type="time" name="jam_pengembalian" class="form-input" required>
            </div>
          </div>

          <div class="input-row">
            <div class="form-group">
              <div class="form-label"><i class="fas fa-hashtag"></i> Jml Dikembalikan <span class="req">*</span></div>
              <input type="number" name="jumlah_dikembalikan" class="form-input" id="jumlahKembali" min="1" placeholder="0" required>
            </div>
            <div class="form-group">
              <div class="form-label"><i class="fas fa-hashtag"></i> Jml Dipinjam</div>
              <input type="number" class="form-input" id="jumlahPinjam" readonly style="background:#f8fafc">
            </div>
          </div>

          <div class="form-group">
            <div class="form-label"><i class="fas fa-exclamation-triangle"></i> Kondisi Barang <span class="req">*</span></div>
            <select name="kondisi_barang" class="form-select" required>
              <option value="">-- Pilih Kondisi Saat Ini --</option>
              <option value="baik">Baik (Lengkap, Normal)</option>
              <option value="rusak-ringan">Rusak Ringan (Perlu perbaikan minor)</option>
              <option value="rusak-berat">Rusak Berat (Tidak bisa digunakan)</option>
              <option value="hilang">Hilang / Tidak Kembali</option>
            </select>
          </div>

          <div class="form-group">
            <div class="form-label"><i class="fas fa-camera"></i> Bukti Foto Barang <span class="req">*</span></div>
            <input type="file" name="foto_sesudah" class="form-input" accept="image/*" required>
            <small style="color:var(--text-secondary); font-size:11px;">*Upload foto terbaru barang saat dikembalikan.</small>
          </div>

          <div class="form-group">
            <div class="form-label"><i class="fas fa-clipboard"></i> Catatan</div>
            <textarea name="catatan" class="form-textarea" placeholder="Tuliskan keterangan tambahan jika ada kerusakan atau informasi penting lainnya..."></textarea>
          </div>

          <button type="submit" class="submit-btn" id="submitBtn" disabled>
            <i class="fas fa-paper-plane"></i> Kirim Laporan Pengembalian
          </button>
        </form>
      </div>
    </div>

    <!-- HISTORY SECTION -->
    <div>
      <div class="history-card">
        <div class="history-header">
          <div class="history-title"><i class="fas fa-history"></i> Riwayat Pengembalian</div>
          <div class="filter-tabs">
            <button class="filter-tab active" onclick="filterTab(this,'all')">Semua</button>
            <button class="filter-tab" onclick="filterTab(this,'pending')">Pending</button>
            <button class="filter-tab" onclick="filterTab(this,'diterima')">Selesai</button>
          </div>
        </div>
        <div class="req-list">
          @forelse($riwayat as $item)
            <div class="req-card" data-status="{{ $item->status_verifikasi }}">
              <div class="req-card-top">
                <div style="display:flex;align-items:center;gap:12px">
                  <div class="req-card-icon"><i class="fas fa-box"></i></div>
                  <div>
                    <div class="req-card-name">{{ $item->peminjamanBarang->nama_barang ?? 'Barang Dihapus' }}</div>
                    <div class="req-card-code">{{ $item->peminjamanBarang->kode_barang ?? '-' }}</div>
                  </div>
                </div>
                
                @if($item->status_verifikasi == 'pending')
                    <div class="status-badge pending"><i class="fas fa-clock"></i> Verifikasi Admin</div>
                @elseif($item->status_verifikasi == 'diterima')
                    <div class="status-badge diterima"><i class="fas fa-check-circle"></i> Selesai</div>
                @elseif($item->status_verifikasi == 'ditolak')
                    <div class="status-badge ditolak"><i class="fas fa-times-circle"></i> Ditolak Admin</div>
                @endif
              </div>
              
              <div class="req-card-meta">
                <div class="meta-item"><div class="meta-label">Tgl Kembali</div><div class="meta-value">{{ \Carbon\Carbon::parse($item->tanggal_pengembalian_aktual)->format('d M Y') }}</div></div>
                <div class="meta-item"><div class="meta-label">Jumlah</div><div class="meta-value">{{ $item->jumlah_dikembalikan }} Unit</div></div>
                <div class="meta-item"><div class="meta-label">Kondisi</div><div class="meta-value">{{ ucwords(str_replace('_', ' ', $item->status_pengembalian)) }}</div></div>
                <div class="meta-item"><div class="meta-label">Status Akhir</div><div class="meta-value">{{ ucfirst($item->status_verifikasi) }}</div></div>
              </div>
              
              <div class="req-card-footer">
                <button type="button" class="card-btn detail" onclick="showDetailModal({{ $item->id }})"><i class="fas fa-eye"></i> Detail</button>
                
                @if($item->status_verifikasi == 'pending')
                  <form action="{{ route('pegawai.pengembalian-barang.cancel', $item->id) }}" method="POST" style="flex:1;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="card-btn cancel" onclick="return confirm('Yakin ingin membatalkan laporan pengembalian ini?')" style="width:100%"><i class="fas fa-times"></i> Batalkan</button>
                  </form>
                @endif
              </div>
            </div>
          @empty
            <div style="text-align:center; padding:40px; color:var(--text-secondary);">
                <i class="fas fa-box-open" style="font-size:40px; margin-bottom:10px; opacity:0.3"></i>
                <p style="font-size:13px; font-weight:600;">Belum ada riwayat pengembalian barang.</p>
            </div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</main>

<!-- MODAL DETAIL -->
<div id="detailModal" class="modal-overlay">
    <div class="modal-box">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; border-bottom:1px solid var(--border); padding-bottom:12px;">
            <h3 style="font-family:'Space Grotesk'; font-weight:700; color:var(--primary);"><i class="fas fa-info-circle"></i> Detail Pengembalian</h3>
            <button type="button" onclick="closeModal()" style="border:none; background:none; cursor:pointer; color:var(--text-secondary); font-size:18px;"><i class="fas fa-times"></i></button>
        </div>
        
        <div id="loadingModal" style="text-align:center; padding:30px;"><i class="fas fa-spinner fa-spin fa-2x" style="color:var(--primary)"></i></div>
        
        <div id="contentModal" style="display:none;">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:15px;">
                <div>
                    <label style="font-size:10px; font-weight:700; color:var(--text-secondary); text-transform:uppercase;">Barang</label>
                    <div id="modNama" style="font-size:13px; font-weight:700; color:var(--text-primary);"></div>
                    <div id="modKode" style="font-size:11px; color:var(--text-secondary);"></div>
                </div>
                <div>
                    <label style="font-size:10px; font-weight:700; color:var(--text-secondary); text-transform:uppercase;">Tanggal Lapor</label>
                    <div id="modTgl" style="font-size:13px; font-weight:600;"></div>
                </div>
            </div>

            <div style="background:#f8fafc; padding:12px; border-radius:10px; border:1px solid var(--border); margin-bottom:15px;">
                <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px;">
                    <div>
                        <label style="font-size:10px; font-weight:700; color:var(--text-secondary); text-transform:uppercase;">Dikembalikan</label>
                        <div id="modJml" style="font-size:13px; font-weight:700; color:var(--primary);"></div>
                    </div>
                    <div>
                        <label style="font-size:10px; font-weight:700; color:var(--text-secondary); text-transform:uppercase;">Kondisi</label>
                        <div id="modKondisi" style="font-size:13px; font-weight:600;"></div>
                    </div>
                    <div>
                        <label style="font-size:10px; font-weight:700; color:var(--text-secondary); text-transform:uppercase;">Status</label>
                        <div id="modStatus" style="font-size:13px; font-weight:700;"></div>
                    </div>
                </div>
            </div>

            <div style="margin-bottom:15px;">
                <label style="font-size:10px; font-weight:700; color:var(--text-secondary); text-transform:uppercase;">Catatan Anda</label>
                <div id="modCatatan" style="font-size:13px; color:var(--text-primary); margin-top:4px;"></div>
            </div>

            <div id="komentarAdminArea" style="display:none; margin-bottom:15px; padding:12px; background:#fef2f2; border-left:3px solid var(--danger); border-radius:6px;">
                <label style="font-size:10px; font-weight:700; color:var(--danger); text-transform:uppercase;">Tanggapan Admin / Alasan Ditolak</label>
                <div id="modKomentar" style="font-size:13px; color:var(--text-primary); margin-top:4px;"></div>
            </div>

            <div style="text-align:right; margin-top:20px;">
                <button type="button" class="card-btn" style="background:#e2e8f0; color:var(--text-primary); width:auto; padding:10px 24px; display:inline-block;" onclick="closeModal()">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
// Auto Set Date to Today
document.addEventListener('DOMContentLoaded', function() {
  const today = new Date().toISOString().split('T')[0];
  document.getElementById('tglPengembalian').value = today;
});

// Handle Peminjaman Select Change
function onPeminjamanChange() {
  const select = document.getElementById('peminjamanSelect');
  const preview = document.getElementById('barangPreview');
  const submitBtn = document.getElementById('submitBtn');
  const jumlahInput = document.getElementById('jumlahKembali');
  
  if (select.value) {
    const option = select.options[select.selectedIndex];
    document.getElementById('bpName').textContent = option.dataset.name;
    document.getElementById('bpCode').textContent = option.dataset.code;
    
    // Set max & value attribute dynamically
    const maxJumlah = option.dataset.jumlah;
    document.getElementById('jumlahPinjam').value = maxJumlah;
    jumlahInput.max = maxJumlah;
    jumlahInput.value = maxJumlah; // Auto fill penuh

    preview.classList.add('show');
    submitBtn.disabled = false;
  } else {
    preview.classList.remove('show');
    submitBtn.disabled = true;
    document.getElementById('jumlahPinjam').value = '';
    jumlahInput.value = '';
  }
}

// Filter Tabs
function filterTab(btn, status) {
  document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
  btn.classList.add('active');
  
  document.querySelectorAll('.req-card').forEach(card => {
    if (status === 'all' || card.dataset.status === status) {
      card.style.display = 'block';
    } else {
      card.style.display = 'none';
    }
  });
}

// Modal JSON Detail
function showDetailModal(id) {
  const modal = document.getElementById('detailModal');
  const loading = document.getElementById('loadingModal');
  const content = document.getElementById('contentModal');
  
  modal.style.display = 'flex';
  loading.style.display = 'block';
  content.style.display = 'none';

  fetch(`/pegawai/pengembalian-barang/${id}/json`)
    .then(response => response.json())
    .then(res => {
      if(res.success) {
        const data = res.data;
        document.getElementById('modNama').textContent = data.peminjaman_barang ? data.peminjaman_barang.nama_barang : '-';
        document.getElementById('modKode').textContent = data.peminjaman_barang ? data.peminjaman_barang.kode_barang : '-';
        document.getElementById('modTgl').textContent = data.tanggal_pengembalian_aktual ? data.tanggal_pengembalian_aktual.split(' ')[0] : '-';
        document.getElementById('modJml').textContent = data.jumlah_dikembalikan + ' Unit';
        
        // Format String Status/Kondisi
        let kondisiTxt = data.status_pengembalian.replace('_', ' ');
        document.getElementById('modKondisi').textContent = kondisiTxt.toUpperCase();
        document.getElementById('modStatus').textContent = data.status_verifikasi.toUpperCase();
        
        document.getElementById('modCatatan').textContent = data.catatan || 'Tidak ada catatan.';

        const komArea = document.getElementById('komentarAdminArea');
        if(data.komentar_admin && data.status_verifikasi === 'ditolak') {
            document.getElementById('modKomentar').textContent = data.komentar_admin;
            komArea.style.display = 'block';
        } else {
            komArea.style.display = 'none';
        }

        loading.style.display = 'none';
        content.style.display = 'block';
      }
    })
    .catch(err => {
      alert('Gagal memuat detail data.');
      closeModal();
    });
}

function closeModal() {
  document.getElementById('detailModal').style.display = 'none';
}
</script>
</body>
</html>