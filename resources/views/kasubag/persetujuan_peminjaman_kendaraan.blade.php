<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Peminjaman Kendaraan - Dashboard Kasubag</title>
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
  
  .page-header { display: flex; align-items: center; gap: 14px; margin-bottom: 28px; }
  .page-header-icon { width: 48px; height: 48px; background: var(--blue-light); border-radius: 14px; display: flex; align-items: center; justify-content: center; }
  .page-header-icon svg { width: 24px; height: 24px; stroke: var(--blue); fill: none; stroke-width: 2; }
  .page-header-text h1 { font-size: 22px; font-weight: 700; }
  .page-header-text p { font-size: 13px; color: var(--gray-400); margin-top: 2px; }

  .summary-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 32px; width: 100%; }
  .summary-card { background: #fff; padding: 20px 24px; border-radius: 16px; border: 1px solid var(--gray-200); display: flex; align-items: center; gap: 16px; box-shadow: 0 4px 10px rgba(0,0,0,0.02); }
  .summary-icon { width: 56px; height: 56px; border-radius: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
  .summary-icon svg { width: 26px; height: 26px; fill: none; stroke-width: 2; }
  .summary-info h3 { font-size: 12px; color: var(--gray-400); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
  .summary-info .num { font-size: 28px; font-weight: 700; color: var(--gray-800); line-height: 1; }
  
  .section-label { display: flex; align-items: center; gap: 8px; font-size: 13.5px; font-weight: 600; color: var(--gray-800); margin-bottom: 16px; border-bottom: 2px solid var(--gray-200); padding-bottom: 10px; width: 100%; }
  
  /* PERBAIKAN: Memastikan kartu pengajuan mengambil porsi 100% lebar layar */
  .req-card { background: #fff; border: 1px solid var(--gray-200); border-radius: 14px; padding: 20px 22px; margin-bottom: 14px; display: flex; align-items: flex-start; justify-content: space-between; gap: 24px; transition: box-shadow .15s; width: 100%; }
  .req-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.05); }
  .req-card.highlighted { border: 2px solid var(--orange); background: #fffdf5; }
  .req-info { flex: 1; }
  .req-tags { display: flex; gap: 8px; margin-bottom: 8px; }
  .tag { font-size: 11.5px; font-weight: 600; padding: 4px 10px; border-radius: 6px; }
  .tag.id { background: var(--gray-100); color: var(--gray-600); }
  .tag.pending { background: var(--orange-light); color: var(--orange); border: 1px solid rgba(245,158,11,0.2); }
  .tag.success { background: var(--green-light); color: var(--green); border: 1px solid rgba(16,185,129,0.2); }
  .tag.danger { background: var(--red-light); color: var(--red); border: 1px solid rgba(239,68,68,0.2); }

  .req-name { font-size: 17px; font-weight: 700; margin-bottom: 10px; color: var(--blue); }
  .req-meta { display: grid; grid-template-columns: 1fr 1fr; gap: 6px 32px; font-size: 12.5px; color: var(--gray-600); margin-bottom: 6px; }
  .req-meta span strong { color: var(--gray-800); }
  
  /* PERBAIKAN: Mengatur peruntukan agar melebarkan ruangnya sendiri */
  .req-purpose { font-size: 12.5px; color: var(--gray-600); background: var(--gray-50); padding: 10px 14px; border-radius: 8px; margin-top: 12px; border: 1px solid var(--gray-200); width: 100%; }
  .req-purpose span { color: var(--gray-800); font-weight: 500; }
  
  .req-actions { display: flex; flex-direction: column; gap: 8px; align-items: stretch; flex-shrink: 0; min-width: 140px; }
  .btn { display: flex; align-items: center; justify-content: center; gap: 6px; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; border: none; cursor: pointer; transition: all .15s; width: 100%; }
  .btn-detail { background: var(--gray-100); color: var(--gray-600); }
  .btn-detail:hover { background: var(--gray-200); }
  .btn-detail svg { width: 15px; height: 15px; stroke: currentColor; fill: none; stroke-width: 2; }
  .btn-approve { background: var(--green); color: #fff; }
  .btn-approve:hover { background: #059669; }
  .btn-approve svg { width: 14px; height: 14px; stroke: currentColor; fill: none; stroke-width: 2.5; }
  .btn-reject { background: var(--red); color: #fff; }
  .btn-reject:hover { background: #dc2626; }
  .btn-reject svg { width: 14px; height: 14px; stroke: currentColor; fill: none; stroke-width: 2.5; }

  /* STYLE MODAL */
  .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px); }
  .modal-box { background: #fff; width: 90%; max-width: 550px; border-radius: 16px; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
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
    <div class="page-header-icon"><svg viewBox="0 0 24 24"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1 .4-1 1v10H2v2h2"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/></svg></div>
    <div class="page-header-text">
        <h1>Peminjaman Kendaraan</h1>
        <p>Kelola dan tinjau seluruh pengajuan penggunaan kendaraan dinas</p>
    </div>
  </div>

  @if(session('success'))
    <div style="background: var(--green-light); color: var(--green); padding: 14px 20px; border-radius: 10px; margin-bottom: 24px; font-size: 13.5px; font-weight: 600; border: 1px solid #bbf7d0;">
        <i class="fas fa-check-circle" style="margin-right: 6px;"></i> {{ session('success') }}
    </div>
  @endif

  <div class="summary-grid">
    <div class="summary-card">
      <div class="summary-icon" style="background: var(--orange-light);"><svg stroke="var(--orange)"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
      <div class="summary-info">
        <h3>Menunggu Review</h3>
        <div class="num">{{ $stats['menunggu'] }}</div>
      </div>
    </div>
    <div class="summary-card">
      <div class="summary-icon" style="background: var(--green-light);"><svg stroke="var(--green)"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>
      <div class="summary-info">
        <h3>Telah Disetujui</h3>
        <div class="num">{{ $stats['disetujui'] }}</div>
      </div>
    </div>
    <div class="summary-card">
      <div class="summary-icon" style="background: var(--blue-light);"><svg stroke="var(--blue)"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg></div>
      <div class="summary-info">
        <h3>Total Pengajuan</h3>
        <div class="num">{{ $stats['total'] }}</div>
      </div>
    </div>
  </div>

  <div class="section-label">Daftar Pengajuan Kendaraan</div>

  @forelse($peminjaman as $item)
  <div class="req-card {{ $item->status === 'dalam_review' ? 'highlighted' : '' }}">
    <div class="req-info">
      <div class="req-tags">
        <span class="tag id">KND-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</span>
        
        @if($item->status === 'dalam_review')
            <span class="tag pending">Menunggu Review Anda</span>
        @elseif($item->status === 'disetujui')
            <span class="tag success">Disetujui</span>
        @elseif($item->status === 'ditolak')
            <span class="tag danger">Ditolak</span>
        @else
            <span class="tag id">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</span>
        @endif
      </div>
      
      <div class="req-name">
          {{ $item->nama_barang }} 
          <span style="color: var(--gray-600); font-weight: 500;">({{ $item->merek ?? 'Unit' }})</span>
      </div>
      
      <div class="req-meta">
        <span>Pemohon: <strong>{{ $item->user->name }}</strong></span>
        <span>Kode Barang: <strong>{{ $item->kode_barang }}</strong></span>
        <span>Mulai Pinjam: <strong>{{ \Carbon\Carbon::parse($item->tanggal_peminjaman)->format('d M Y') }}</strong></span>
        <span>Selesai Pinjam: <strong>{{ \Carbon\Carbon::parse($item->tanggal_pengembalian)->format('d M Y') }}</strong></span>
      </div>
      
      <div class="req-purpose">Tujuan Operasional: <span>{{ $item->deskripsi_peruntukan }}</span></div>
    </div>
    
    <div class="req-actions">
      <button class="btn btn-detail" onclick="showDetailModal({{ $item->id }})">
        <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> Lihat Detail
      </button>

      @if($item->status === 'diteruskan_kasubag')
        <form action="{{ route('kasubag.peminjaman-kendaraan.approve', $item->id) }}" method="POST" style="width: 100%;">
          @csrf
          <input type="hidden" name="action" value="setuju">
          <button type="submit" class="btn btn-approve" onclick="return confirm('Yakin ingin MENYETUJUI peminjaman kendaraan ini?')">
            <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg> Setuju
          </button>
        </form>

        <form action="{{ route('kasubag.peminjaman-kendaraan.approve', $item->id) }}" method="POST" style="width: 100%;">
          @csrf
          <input type="hidden" name="action" value="tolak">
          <button type="submit" class="btn btn-reject" onclick="return confirm('Yakin ingin MENOLAK peminjaman kendaraan ini?')">
            <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> Tolak
          </button>
        </form>
      @else
        @if(in_array($item->status, ['disetujui', 'dikembalikan']))
          <form action="{{ route('adminasettetap.peminjaman-kendaraan.print', $item->id) }}" method="GET" target="_blank" style="display:flex; gap:4px; align-items:center; width:100%; margin-top:auto;">
            <input type="date" name="tanggal_surat" value="{{ now()->format('Y-m-d') }}" required title="Tanggal surat" style="padding:6px; font-size:11px; border:1px solid var(--gray-200); border-radius:8px; flex:1;">
            <button type="submit" class="btn btn-detail" style="color:var(--blue); width:auto; padding:8px 12px;" title="Cetak Berita Acara">
              <i class="fas fa-file-pdf"></i> Cetak BAST
            </button>
          </form>
          @if(!empty($item->surat_bast_path))
            <a href="{{ asset('storage/' . $item->surat_bast_path) }}" target="_blank" class="btn btn-detail" style="color:var(--green); text-decoration:none; margin-top:4px;" title="Lihat BAST Terunggah">
              <i class="fas fa-file-circle-check"></i> BAST Fisik
            </a>
          @endif
        @else
          <div style="background: var(--gray-100); color: var(--gray-600); padding: 10px; border-radius: 8px; text-align: center; font-size: 12px; font-weight: 700; border: 1px dashed var(--gray-200); margin-top: auto; width: 100%;">
              Telah Diproses
          </div>
        @endif
      @endif
    </div>
  </div>
  @empty
  <div style="text-align: center; padding: 60px 20px; border: 2px dashed var(--gray-200); border-radius: 14px; background: #fff; width: 100%;">
      <h3 style="font-size: 16px; color: var(--gray-800); margin-bottom: 4px;">Semua Beres!</h3>
      <p style="color: var(--gray-400); font-size: 13px;">Belum ada pengajuan peminjaman kendaraan yang menunggu tinjauan Anda.</p>
  </div>
  @endforelse

</main>

<div id="detailModalKasubag" class="modal-overlay">
    <div class="modal-box">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; border-bottom:1px solid #f1f5f9; padding-bottom:12px;">
            <h3 style="font-family:'Plus Jakarta Sans'; font-weight:700; color:var(--blue);"><i class="fas fa-file-alt"></i> Detail Pengajuan Kendaraan</h3>
            <button onclick="closeDetailModal()" style="border:none; background:none; cursor:pointer; color:var(--gray-400); font-size:18px;"><i class="fas fa-times"></i></button>
        </div>
        
        <div id="loadingDetail" style="text-align:center; padding:30px;">
            <i class="fas fa-spinner fa-spin fa-2x" style="color:var(--blue)"></i>
            <p style="margin-top:10px; font-size:13px; color:var(--gray-400)">Mengambil data...</p>
        </div>

        <div id="contentDetail" style="display:none;">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:15px;">
                <div>
                    <label style="font-size:10px; font-weight:700; color:var(--gray-400); text-transform:uppercase; letter-spacing:1px;">Peminjam</label>
                    <div id="mdlPeminjam" style="font-size:14px; font-weight:700; color:var(--gray-800);"></div>
                </div>
                <div>
                    <label style="font-size:10px; font-weight:700; color:var(--gray-400); text-transform:uppercase; letter-spacing:1px;">Kendaraan</label>
                    <div id="mdlKendaraan" style="font-size:14px; font-weight:700; color:var(--blue);"></div>
                </div>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:15px;">
                <div>
                    <label style="font-size:10px; font-weight:700; color:var(--gray-400); text-transform:uppercase; letter-spacing:1px;">Merek / NUP</label>
                    <div id="mdlInfo" style="font-size:13px; color:var(--gray-800);"></div>
                </div>
                <div>
                    <label style="font-size:10px; font-weight:700; color:var(--gray-400); text-transform:uppercase; letter-spacing:1px;">Durasi Pinjam</label>
                    <div id="mdlJadwal" style="font-size:13px; color:var(--gray-800);"></div>
                </div>
            </div>

            <div style="margin-top:15px; padding:15px; background:var(--gray-50); border-radius:12px; border:1px solid var(--gray-200);">
                <label style="font-size:10px; font-weight:700; color:var(--gray-400); text-transform:uppercase; letter-spacing:1px; display:block; margin-bottom:5px;">Tujuan Penggunaan</label>
                <div id="mdlTujuan" style="font-size:13px; line-height:1.6; color:var(--gray-800);"></div>
            </div>

            <div style="margin-top:25px; text-align:right;">
                <button onclick="closeDetailModal()" class="btn btn-detail" style="display:inline-flex; width:auto; padding:10px 25px;">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    function showDetailModal(id) {
        const modal = document.getElementById('detailModalKasubag');
        const loading = document.getElementById('loadingDetail');
        const content = document.getElementById('contentDetail');
        
        modal.style.display = 'flex';
        loading.style.display = 'block';
        content.style.display = 'none';

        fetch(`/kasubag/peminjaman-kendaraan/${id}/json`)
            .then(response => {
                if (!response.ok) throw new Error('Akses ditolak atau data tidak ditemukan');
                return response.json();
            })
            .then(res => {
                if (res.success) {
                    const data = res.data;
                    
                    const tglPinjam = data.tanggal_peminjaman ? data.tanggal_peminjaman.split('T')[0] : '-';
                    const tglKembali = data.tanggal_pengembalian ? data.tanggal_pengembalian.split('T')[0] : '-';

                    document.getElementById('mdlPeminjam').innerText = data.user ? data.user.name : '-';
                    document.getElementById('mdlKendaraan').innerText = data.nama_barang;
                    document.getElementById('mdlInfo').innerText = (data.merek || '-') + ' | NUP: ' + (data.nup || '-');
                    document.getElementById('mdlJadwal').innerText = tglPinjam + ' s.d ' + tglKembali;
                    document.getElementById('mdlTujuan').innerText = data.deskripsi_peruntukan;

                    loading.style.display = 'none';
                    content.style.display = 'block';
                } else {
                    alert(res.message);
                    closeDetailModal();
                }
            })
            .catch(err => {
                alert('Gagal mengambil data detail: ' + err.message);
                closeDetailModal();
            });
    }

    function closeDetailModal() {
        document.getElementById('detailModalKasubag').style.display = 'none';
    }
</script>
</body>
</html>