<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Peminjaman Barang - Dashboard Kasubag</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  :root {
    --blue: #3b6ff0; --blue-light: #eef2ff;
    --orange: #f59e0b; --orange-light: #fffbeb;
    --green: #10b981; --green-light: #ecfdf5;
    --red: #ef4444; --red-light: #fef2f2;
    --gray-50: #f8fafc; --gray-100: #f1f5f9;
    --gray-200: #e2e8f0; --gray-400: #94a3b8;
    --gray-600: #475569; --gray-800: #1e293b;
    --sidebar-w: 240px;
  }
  body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--gray-50); color: var(--gray-800); display: flex; min-height: 100vh; }
  
  .topbar { position: fixed; top: 0; left: var(--sidebar-w); right: 0; height: 60px; background: #fff; border-bottom: 1px solid var(--gray-200); display: flex; align-items: center; justify-content: flex-end; padding: 0 28px; gap: 16px; z-index: 9; }
  .notif-btn { width: 38px; height: 38px; border-radius: 10px; background: var(--gray-100); display: flex; align-items: center; justify-content: center; cursor: pointer; position: relative; border: none; }
  .notif-btn svg { width: 18px; height: 18px; stroke: var(--gray-600); fill: none; stroke-width: 2; }
  .user-chip { display: flex; align-items: center; gap: 10px; background: var(--gray-100); border-radius: 10px; padding: 6px 12px 6px 6px; }
  .user-avatar { width: 30px; height: 30px; background: var(--blue); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; color: #fff; }
  .user-info strong { font-size: 13px; font-weight: 600; display: block; }
  .user-info span { font-size: 11px; color: var(--gray-400); }
  
  .main { margin-left: var(--sidebar-w); margin-top: 60px; padding: 32px; flex: 1; width: calc(100% - var(--sidebar-w)); }
  .page-header { display: flex; align-items: center; gap: 14px; margin-bottom: 24px; }
  .page-header h1 { font-size: 22px; font-weight: 700; }
  .page-header p { font-size: 13.5px; color: var(--gray-400); margin-top: 4px; }

  /* STATS CARDS */
  .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 32px; }
  .stat-card { background: #fff; border: 1px solid var(--gray-200); border-radius: 16px; padding: 20px; display: flex; align-items: center; gap: 16px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
  .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
  .stat-icon.yellow { background: var(--orange-light); color: var(--orange); }
  .stat-icon.green { background: var(--green-light); color: var(--green); }
  .stat-icon.blue { background: var(--blue-light); color: var(--blue); }
  .stat-label { font-size: 11px; font-weight: 700; color: var(--gray-400); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
  .stat-value { font-size: 24px; font-weight: 800; color: var(--gray-800); }

  .section-label { display: flex; align-items: center; gap: 8px; font-size: 13.5px; font-weight: 600; color: var(--gray-600); margin-bottom: 16px; border-bottom: 1px solid var(--gray-200); padding-bottom: 12px; }
  
  .req-card { background: #fff; border: 1px solid var(--gray-200); border-radius: 14px; padding: 20px; margin-bottom: 14px; display: flex; justify-content: space-between; gap: 16px; }
  .req-tags { display: flex; gap: 8px; margin-bottom: 8px; }
  .tag { font-size: 11.5px; font-weight: 600; padding: 3px 10px; border-radius: 6px; }
  .tag.pending { background: #fffbeb; color: var(--orange); }
  .tag.approved { background: #ecfdf5; color: var(--green); }
  .tag.rejected { background: #fef2f2; color: var(--red); }
  .req-name { font-size: 17px; font-weight: 700; margin-bottom: 10px; }
  .req-meta { display: grid; grid-template-columns: 1fr 1fr; gap: 4px 32px; font-size: 12.5px; color: var(--gray-600); margin-bottom: 6px; }
  .req-actions { display: flex; flex-direction: column; gap: 8px; align-items: flex-end; justify-content: flex-start; }
  
  .btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; border: none; cursor: pointer; transition: .15s; font-family: inherit; }
  .btn-detail { background: var(--gray-100); color: var(--gray-600); width: 100%; } .btn-detail:hover { background: var(--gray-200); }
  .btn-approve { background: var(--green-light); color: var(--green); width: 100%; } .btn-approve:hover { background: #bbf7d0; }
  .btn-reject { background: var(--red-light); color: var(--red); width: 100%; } .btn-reject:hover { background: #fecaca; }

  /* MODAL DETAIL */
  .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 100; align-items: center; justify-content: center; backdrop-filter: blur(2px); }
  .modal-box { background: #fff; width: 450px; border-radius: 16px; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
  .modal-title { font-size: 18px; font-weight: 700; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; color: var(--blue); }
  .detail-row { display: flex; flex-direction: column; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px dashed var(--gray-200); }
  .detail-label { font-size: 11px; color: var(--gray-400); font-weight: 600; text-transform: uppercase; margin-bottom: 4px; }
  .detail-value { font-size: 14px; font-weight: 600; color: var(--gray-800); }
</style>
</head>
<body>

@include('partials.sidebar')

<div class="topbar">
  {{-- <button class="notif-btn"><svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg></button> --}}
  <div class="user-chip">
    <div class="user-avatar">{{ substr(Auth::user()->name ?? 'K', 0, 1) }}</div>
    <div class="user-info"><strong>{{ Auth::user()->name ?? 'Kasubag Umum' }}</strong><span>Kepala Sub Bagian</span></div>
  </div>
</div>

<main class="main">
  <div class="page-header">
    <div class="page-header-text">
      <h1>Persetujuan Peminjaman Barang</h1>
      <p>Kelola dan tinjau seluruh permintaan peminjaman aset dari pegawai yang telah diteruskan admin.</p>
    </div>
  </div>

  <!-- STATS CARDS (Seperti pada gambar) -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon yellow"><i class="fas fa-clock"></i></div>
      <div>
        <div class="stat-label">Menunggu Review</div>
        <div class="stat-value">{{ $stats['menunggu'] }}</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
      <div>
        <div class="stat-label">Telah Disetujui</div>
        <div class="stat-value">{{ $stats['disetujui'] }}</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon blue"><i class="fas fa-file-alt"></i></div>
      <div>
        <div class="stat-label">Total Permintaan</div>
        <div class="stat-value">{{ $stats['total'] }}</div>
      </div>
    </div>
  </div>

  @if(session('success'))
    <div style="background:#DCFCE7;color:#16A34A;padding:12px 16px;border-radius:10px;margin-bottom:20px;font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px;">
      <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
  @endif

  <div class="section-label">Daftar Pengajuan</div>

  @forelse($peminjaman as $item)
  <div class="req-card">
    <div class="req-info">
      <div class="req-tags">
        <span class="tag" style="background:#f1f5f9;">REQ-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</span>
        @if($item->status == 'diteruskan_kasubag') <span class="tag pending"><i class="fas fa-clock"></i> Menunggu Review</span>
        @elseif($item->status == 'disetujui') <span class="tag approved"><i class="fas fa-check"></i> Telah Disetujui</span>
        @else <span class="tag rejected"><i class="fas fa-times"></i> Ditolak</span> @endif
      </div>
      <div class="req-name">{{ $item->nama_barang }} ({{ $item->jumlah }} Unit)</div>
      <div class="req-meta">
        <span>Pemohon: <strong>{{ $item->user->name }}</strong></span>
        <span>Kategori: <strong>{{ $item->kategori ?? 'Umum' }}</strong></span>
        <span>Durasi: <strong>{{ \Carbon\Carbon::parse($item->tanggal_peminjaman)->format('d M') }} - {{ \Carbon\Carbon::parse($item->tanggal_pengembalian)->format('d M Y') }}</strong></span>
        <span>Tujuan: <strong>{{ Str::limit($item->deskripsi_peruntukan, 50) }}</strong></span>
      </div>
    </div>
    
    <div class="req-actions">
      <!-- Tombol Detail (Selalu Muncul) -->
      <button class="btn btn-detail" onclick="openDetailModal({{ $item->id }})"><i class="fas fa-eye"></i> Detail</button>
      
      <!-- Tombol Aksi (Hanya muncul jika status Menunggu) -->
      @if($item->status == 'diteruskan_kasubag')
        <div style="display: flex; gap: 8px; width: 100%;">
          <form action="{{ route('kasubag.peminjaman-barang.approve', $item->id) }}" method="POST" style="flex:1;">
            @csrf <input type="hidden" name="action" value="setuju">
            <button type="submit" class="btn btn-approve" onclick="return confirm('Setujui peminjaman ini?')"><i class="fas fa-check"></i> Setuju</button>
          </form>
          <form action="{{ route('kasubag.peminjaman-barang.approve', $item->id) }}" method="POST" onsubmit="return submitTolak(this)" style="flex:1;">
            @csrf <input type="hidden" name="action" value="tolak">
            <input type="hidden" name="komentar" class="alasanTolak">
            <button type="submit" class="btn btn-reject"><i class="fas fa-times"></i> Tolak</button>
          </form>
        </div>
      @elseif($item->status == 'disetujui')
        <div style="display:flex; flex-direction:column; gap:4px; width:100%;">
          <form action="{{ route('adminasettetap.peminjaman-barang.print', $item->id) }}" method="GET" target="_blank" style="display:flex; gap:4px; align-items:center; width:100%;">
            <input type="date" name="tanggal_surat" value="{{ now()->format('Y-m-d') }}" required title="Tanggal surat" style="padding:6px; font-size:11px; border:1px solid var(--gray-200); border-radius:6px; flex:1;">
            <button type="submit" class="btn btn-detail" style="color:var(--blue); width:auto; padding:6px 12px;" title="Cetak Berita Acara">
              <i class="fas fa-file-pdf"></i> Cetak BAST
            </button>
          </form>
          @if(!empty($item->surat_bast_path))
            <a href="{{ asset('storage/' . $item->surat_bast_path) }}" target="_blank" class="btn btn-detail" style="color:var(--green); text-decoration:none;" title="Lihat BAST Terunggah">
              <i class="fas fa-file-circle-check"></i> BAST Fisik
            </a>
          @endif
        </div>
      @endif
    </div>
  </div>
  @empty
    <div style="text-align:center; padding: 60px 20px; background: #fff; border: 1px dashed var(--gray-400); border-radius: 14px;">
      <i class="fas fa-check-circle" style="font-size: 48px; color: var(--gray-200); margin-bottom: 16px;"></i>
      <h3 style="font-size: 16px; color: var(--gray-800); margin-bottom: 4px;">Semua Beres!</h3>
      <p style="font-size: 13px; color: var(--gray-400);">Belum ada riwayat permintaan peminjaman barang di sistem.</p>
    </div>
  @endforelse
  
  <div style="margin-top: 20px;">
    {{ $peminjaman->links() }}
  </div>
</main>

<!-- MODAL DETAIL -->
<div class="modal-overlay" id="detailModal">
  <div class="modal-box">
    <div class="modal-title"><i class="fas fa-box"></i> Detail Peminjaman</div>
    
    <div id="modalLoading" style="text-align: center; padding: 20px; color: var(--gray-400);">
      <i class="fas fa-spinner fa-spin fa-2x"></i>
      <p style="margin-top: 10px; font-size: 12px;">Memuat data...</p>
    </div>

    <div id="modalContent" style="display: none;">
      <div class="detail-row">
        <span class="detail-label">Nama Pemohon</span>
        <span class="detail-value" id="detPemohon">-</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Barang (Kode / NUP)</span>
        <span class="detail-value" id="detBarang">-</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Jumlah / Durasi</span>
        <span class="detail-value" id="detDurasi">-</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Tujuan Peminjaman</span>
        <span class="detail-value" style="font-weight: 500;" id="detTujuan">-</span>
      </div>
      <div style="text-align: right; margin-top: 20px;">
        <button class="btn btn-detail" style="width: auto; padding: 8px 24px;" onclick="document.getElementById('detailModal').style.display='none'">Tutup</button>
      </div>
    </div>
  </div>
</div>

<script>
// Fungsi untuk Submit Tolak dengan Alasan
function submitTolak(form) {
  let alasan = prompt("Masukkan alasan penolakan (Opsional):");
  if(alasan !== null) {
    form.querySelector('.alasanTolak').value = alasan;
    return true;
  }
  return false;
}

// Fungsi Buka Modal Detail
function openDetailModal(id) {
  document.getElementById('detailModal').style.display = 'flex';
  document.getElementById('modalLoading').style.display = 'block';
  document.getElementById('modalContent').style.display = 'none';

  // Fetch Data menggunakan AJAX
  fetch(`/kasubag/peminjaman-barang/${id}/detail`)
    .then(response => response.json())
    .then(res => {
      if(res.success) {
        let data = res.data;
        document.getElementById('detPemohon').innerText = data.user.name;
        document.getElementById('detBarang').innerText = `${data.nama_barang} (${data.kode_barang} / ${data.nup || '-'})`;
        
        let tglPinjam = new Date(data.tanggal_peminjaman).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year:'numeric'});
        let tglKembali = new Date(data.tanggal_pengembalian).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year:'numeric'});
        
        document.getElementById('detDurasi').innerText = `${data.jumlah} Unit | ${tglPinjam} - ${tglKembali}`;
        document.getElementById('detTujuan').innerText = data.deskripsi_peruntukan;

        document.getElementById('modalLoading').style.display = 'none';
        document.getElementById('modalContent').style.display = 'block';
      }
    });
}
</script>

</body>
</html>