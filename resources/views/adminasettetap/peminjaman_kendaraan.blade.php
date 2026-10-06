<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPANDU - Admin Peminjaman Kendaraan</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --blue: #4F6FFF;
            --purple: #8b5cf6;
            --bg: #F4F6FB;
            --surface: #FFFFFF;
            --text: #1E293B;
            --muted: #94A3B8;
            --border: #E8EDF5;
            --radius: 16px;
            --sidebar-w: 240px;
            --success: #10b981;
            --danger: #ef4444;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); display: flex; min-height: 100vh; }

        .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; }
        .topbar { background: var(--surface); border-bottom: 1px solid var(--border); padding: 0 28px; height: 56px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 50; }
        .content { padding: 28px; flex: 1; }

        .page-top h1 { font-size: 22px; font-weight: 800; color: var(--blue); margin-bottom: 4px; }
        .page-top p { font-size: 13px; color: var(--muted); margin-bottom: 20px;}

        .table-card { background: var(--surface); border-radius: var(--radius); border: 1px solid var(--border); overflow: hidden; }
        .table-toolbar { display: flex; align-items: center; gap: 12px; padding: 16px 20px; border-bottom: 1px solid var(--border); }
        
        .search-wrap { flex: 1; display: flex; align-items: center; gap: 8px; border: 1.5px solid var(--border); border-radius: 10px; padding: 8px 14px; background: var(--bg); }
        .search-wrap input { border: none; background: none; outline: none; font-size: 13.5px; width: 100%; }

        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #F8FAFF; }
        th { padding: 12px 20px; text-align: left; font-size: 11px; font-weight: 700; color: var(--blue); text-transform: uppercase; border-bottom: 1px solid var(--border); }
        td { padding: 14px 20px; font-size: 13.5px; border-bottom: 1px solid var(--border); vertical-align: middle; }

        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; }
        .status-diterima { background: #DCFCE7; color: #16A34A; }
        .status-pending { background: #FEF3C7; color: #D97706; }
        .status-review { background: #E0E7FF; color: #4338CA; }
        .status-ditolak { background: #FEE2E2; color: #DC2626; }

        .action-btn { 
            padding: 7px 12px; 
            border-radius: 8px; 
            border: 1px solid var(--border); 
            background: var(--surface); 
            cursor: pointer; 
            font-size: 12px; 
            font-weight: 600; 
            display: inline-flex; 
            align-items: center; 
            gap: 6px; 
            transition: all .2s; 
            text-decoration: none;
            color: var(--gray-600);
            margin-bottom: 4px;
        }
        .action-btn:hover { background: #f8fafc; border-color: var(--blue); color: var(--blue); }
        .action-btn.teruskan { color: var(--blue); }
        .action-btn.tolak { color: var(--danger); }
        .action-btn.upload { color: var(--success); }
        .action-btn.upload:hover { background: #ecfdf5; border-color: var(--success); }

        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; }
        .modal-box { background: #fff; width: 420px; border-radius: 16px; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .modal-title { font-size: 18px; font-weight: 700; margin-bottom: 16px; }
        .form-control { width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 8px; font-size: 13px; margin-bottom: 16px; font-family: inherit; }
    </style>
</head>
<body>

@include('partials.sidebar')

<main class="main">
    <div class="topbar">
        <span class="topbar-title">Manajemen Peminjaman Kendaraan</span>
    </div>

    <div class="content">
        <div class="page-top">
            <h1>Daftar Peminjaman Kendaraan</h1>
            <p>Verifikasi pengajuan kendaraan dinas BPMP Provinsi Gorontalo.</p>
        </div>

        @if(session('success'))
            <div style="background:#DCFCE7; color:#16A34A; padding:12px; border-radius:8px; margin-bottom:16px; font-size:13px; font-weight:600; border: 1px solid #bbf7d0;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <div class="table-card">
            <div class="table-toolbar">
                <form action="{{ url()->current() }}" method="GET" class="search-wrap">
                    <i class="fas fa-search" style="color: var(--muted)"></i>
                    <input type="text" name="search" placeholder="Cari kendaraan atau peminjam..." value="{{ request('search') }}">
                </form>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Detail Kendaraan</th>
                        <th>No. Polisi</th> <!-- Kolom Baru Nopol -->
                        <th>Peminjam</th>
                        <th>Jadwal Pinjam</th>
                        <th>Status</th>
                        <th style="width: 280px;">Aksi Admin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjamanKendaraan as $index => $item)
                    <tr>
                        <td><strong>{{ $peminjamanKendaraan->firstItem() + $index }}</strong></td>
                        <td>
                            <div style="font-weight:700">{{ $item->nama_barang }}</div>
                            <div style="font-size:11px; color:var(--muted);">
                                {{ $item->merek ?? '-' }} | NUP: {{ $item->nup ?? '-' }}
                            </div>
                        </td>
                        <td>
                            <!-- Menampilkan Nopol dari snapshot transaksi -->
                            <span style="font-weight: 700; color: var(--blue);">{{ $item->nomor_polisi_saat_pinjam ?? '-' }}</span>
                        </td>
                        <td>{{ $item->user->name }}</td>
                        <td>
                            <div style="font-size: 12px;">
                                <i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($item->tanggal_peminjaman)->format('d/m/Y') }}
                            </div>
                            <small style="color:var(--muted)">s.d {{ \Carbon\Carbon::parse($item->tanggal_pengembalian)->format('d/m/Y') }}</small>
                        </td>
                        <td>
                            @php 
                                $statusClass = 'status-pending'; 
                                $statusText = $item->status;
                                
                                if($item->status == 'disetujui') { $statusClass = 'status-diterima'; }
                                elseif($item->status == 'ditolak') { $statusClass = 'status-ditolak'; }
                                elseif($item->status == 'diteruskan_kasubag') { $statusClass = 'status-review'; $statusText = 'Di teruskan kekasubag'; }
                                elseif($item->status == 'dibatalkan') { $statusClass = 'status-ditolak'; $statusText = 'Dibatalkan'; }
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ str_replace('_', ' ', ucfirst($statusText)) }}</span>
                        </td>
                        <td>
                            @if(in_array($item->status, ['dibatalkan', 'ditolak']))
                                <button class="action-btn" onclick="showDetail({{ $item->id }})">
                                    <i class="fas fa-eye"></i> Detail
                                </button>
                                <span style="font-size: 12px; color: var(--muted); font-style: italic; margin-left: 6px;">
                                    {{ $item->status == 'ditolak' ? 'Ditolak' : 'Dibatalkan' }}
                                </span>
                            @else
                                <button class="action-btn" onclick="showDetail({{ $item->id }})">
                                    <i class="fas fa-eye"></i> Detail
                                </button>

                                @if(in_array($item->status, ['disetujui', 'diteruskan_kasubag', 'dikembalikan']))
                                <form action="{{ route('adminasettetap.peminjaman-kendaraan.print', $item->id) }}" method="GET" target="_blank" style="display:inline-flex; gap:4px; align-items:center;">
                                    <input type="date" name="tanggal_surat" value="{{ now()->format('Y-m-d') }}" required title="Tanggal surat" style="padding:5px; max-width:135px;">
                                    <button type="submit" class="action-btn" style="color: var(--purple);"><i class="fas fa-file-pdf"></i> Cetak</button>
                                </form>
                                @endif

                                @if($item->status == 'pending')
                                    <button class="action-btn teruskan" onclick="openReviewModal({{ $item->id }}, 'teruskan')">
                                        <i class="fas fa-paper-plane"></i> Teruskan
                                    </button>
                                    <button class="action-btn tolak" onclick="openReviewModal({{ $item->id }}, 'tolak')">
                                        <i class="fas fa-times"></i> Tolak
                                    </button>
                                @elseif(in_array($item->status, ['diteruskan_kasubag', 'disetujui']))
                                    <button class="action-btn upload" onclick="openUploadModal({{ $item->id }})">
                                        <i class="fas fa-upload"></i> Upload Surat
                                    </button>
                                @endif

                                @if(!empty($item->surat_bast_path))
                                    <i class="fas fa-check-double" style="color: var(--success); margin-left: 4px;" title="Surat Terunggah"></i>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align:center; padding:40px; color:var(--muted);">Belum ada pengajuan kendaraan.</td></tr>
                    @endforelse
                </tbody>
            </table>
            
            <div style="padding: 20px;">
                {{ $peminjamanKendaraan->links() }}
            </div>
        </div>
    </div>
</main>

<!-- Modal Review -->
<div class="modal-overlay" id="reviewModal">
    <div class="modal-box">
        <div class="modal-title" id="reviewTitle">Review Pengajuan</div>
        <form id="reviewForm" method="POST">
            @csrf
            <input type="hidden" name="action" id="reviewAction">
            <div id="rejectReason" style="display:none;">
                <label style="font-size:12px; font-weight:600; display:block; margin-bottom:8px;">Alasan Penolakan</label>
                <textarea name="komentar" class="form-control" rows="3" placeholder="Berikan alasan penolakan..."></textarea>
            </div>
            <p id="reviewText" style="font-size:13px; color:var(--muted); margin-bottom:20px;">Apakah anda yakin ingin memproses data ini?</p>
            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" class="action-btn" onclick="closeModal('reviewModal')">Batal</button>
                <button type="submit" class="action-btn teruskan" id="confirmBtn">Konfirmasi</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Upload Surat -->
<div class="modal-overlay" id="uploadModal">
    <div class="modal-box">
        <div class="modal-title">Unggah Surat Persetujuan</div>
        <form id="uploadForm" method="POST" enctype="multipart/form-data">
            @csrf
            <label style="font-size:12px; font-weight:600; display:block; margin-bottom:8px;">File Scan Surat (PDF)</label>
            <input type="file" name="surat_bast" accept="application/pdf" class="form-control" required>
            <p style="font-size: 11px; color: var(--muted); margin-bottom: 15px;">File ini akan tampil di halaman riwayat pegawai.</p>
            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" class="action-btn" onclick="closeModal('uploadModal')">Batal</button>
                <button type="submit" class="action-btn upload">Simpan File</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL DETAIL PEMINJAMAN ADMIN -->
<div id="detailModalAdmin" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center; backdrop-filter:blur(4px);">
    <div style="background:#fff; width:90%; max-width:550px; border-radius:16px; padding:24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; border-bottom:1px solid #f1f5f9; padding-bottom:12px;">
            <h3 style="font-family:'Space Grotesk'; font-weight:700; color:var(--blue);"><i class="fas fa-info-circle"></i> Detail Pengajuan Kendaraan</h3>
            <button onclick="closeDetailModal()" style="border:none; background:none; cursor:pointer; color:var(--muted); font-size:18px;"><i class="fas fa-times"></i></button>
        </div>
        
        <div id="loadingDetail" style="text-align:center; padding:30px;">
            <i class="fas fa-spinner fa-spin fa-2x" style="color:var(--blue)"></i>
            <p style="margin-top:10px; font-size:13px; color:var(--muted)">Mengambil data...</p>
        </div>

        <div id="contentDetail" style="display:none;">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:15px;">
                <div>
                    <label style="font-size:10px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:1px;">Peminjam</label>
                    <div id="txtPeminjam" style="font-size:14px; font-weight:700; color:var(--text);"></div>
                </div>
                <div>
                    <label style="font-size:10px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:1px;">Unit Kendaraan</label>
                    <div id="txtKendaraan" style="font-size:14px; font-weight:700; color:var(--blue);"></div>
                </div>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:15px;">
                <div>
                    <label style="font-size:10px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:1px;">Merek / NUP</label>
                    <div id="txtInfo" style="font-size:13px; color:var(--text);"></div>
                </div>
                <div>
                    <label style="font-size:10px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:1px;">Durasi Pinjam</label>
                    <div id="txtJadwal" style="font-size:13px; color:var(--text);"></div>
                </div>
            </div>

            <!-- Bagian Tambahan Spesifikasi Lengkap Kendaraan -->
            <div style="background:#f8faff; padding:12px; border-radius:10px; border:1px solid #eef1ff; margin-bottom:15px;">
                <label style="font-size:10px; font-weight:700; color:var(--blue); text-transform:uppercase; margin-bottom:8px; display:block;">Spesifikasi Kendaraan</label>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; font-size:12.5px;">
                    <div><strong>No. Polisi:</strong> <span id="txtNopol">-</span></div>
                    <div><strong>No. BPKB:</strong> <span id="txtBpkb">-</span></div>
                    <div><strong>No. Rangka:</strong> <span id="txtRangka">-</span></div>
                    <div><strong>No. Mesin:</strong> <span id="txtMesin">-</span></div>
                </div>
            </div>

            <div style="padding:15px; background:#f8faff; border-radius:12px; border:1px solid #eef1ff;">
                <label style="font-size:10px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:1px; display:block; margin-bottom:5px;">Tujuan Penggunaan</label>
                <div id="txtTujuan" style="font-size:13px; line-height:1.6; color:var(--text);"></div>
            </div>

            <div style="margin-top:25px; text-align:right;">
                <button onclick="closeDetailModal()" class="action-btn" style="padding:10px 25px;">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    function openReviewModal(id, action) {
        const form = document.getElementById('reviewForm');
        form.action = `/adminasettetap/peminjaman-kendaraan/${id}/review`;
        document.getElementById('reviewAction').value = action;
        
        const reasonDiv = document.getElementById('rejectReason');
        const confirmBtn = document.getElementById('confirmBtn');
        const reviewText = document.getElementById('reviewText');

        if(action === 'tolak') {
            document.getElementById('reviewTitle').innerText = 'Tolak Pengajuan Kendaraan';
            reviewText.innerText = 'Permintaan akan ditolak dan peminjam akan menerima notifikasi.';
            reasonDiv.style.display = 'block';
            confirmBtn.className = 'action-btn tolak';
        } else {
            document.getElementById('reviewTitle').innerText = 'Teruskan ke Kasubag';
            reviewText.innerText = 'Kirim data ini ke Kasubag untuk mendapatkan persetujuan final.';
            reasonDiv.style.display = 'none';
            confirmBtn.className = 'action-btn teruskan';
        }
        document.getElementById('reviewModal').style.display = 'flex';
    }

    function openUploadModal(id) {
        document.getElementById('uploadForm').action = `/adminasettetap/peminjaman-kendaraan/${id}/upload`;
        document.getElementById('uploadModal').style.display = 'flex';
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }

    function showDetail(id) {
        const modal = document.getElementById('detailModalAdmin');
        const loading = document.getElementById('loadingDetail');
        const content = document.getElementById('contentDetail');
        
        modal.style.display = 'flex';
        loading.style.display = 'block';
        content.style.display = 'none';

        fetch(`/adminasettetap/peminjaman-kendaraan/${id}/json`)
            .then(response => {
                if (!response.ok) throw new Error('Akses ditolak atau data tidak ditemukan');
                return response.json();
            })
            .then(res => {
                if (res.success) {
                    const data = res.data;
                    
                    const tglPinjam = data.tanggal_peminjaman ? data.tanggal_peminjaman.split('T')[0] : '-';
                    const tglKembali = data.tanggal_pengembalian ? data.tanggal_pengembalian.split('T')[0] : '-';

                    document.getElementById('txtPeminjam').innerText = data.user ? data.user.name : '-';
                    document.getElementById('txtKendaraan').innerText = data.nama_barang;
                    document.getElementById('txtInfo').innerText = (data.merek || '-') + ' | NUP: ' + (data.nup || '-');
                    document.getElementById('txtJadwal').innerText = tglPinjam + ' s.d ' + tglKembali;
                    
                    // Memasukkan detail spesifikasi kendaraan
                    document.getElementById('txtNopol').innerText = data.nomor_polisi_saat_pinjam || '-';
                    document.getElementById('txtBpkb').innerText = data.no_bpkb_saat_pinjam || '-';
                    document.getElementById('txtRangka').innerText = data.nomor_rangka_saat_pinjam || '-';
                    document.getElementById('txtMesin').innerText = data.nomor_mesin_saat_pinjam || '-';

                    document.getElementById('txtTujuan').innerText = data.deskripsi_peruntukan;

                    loading.style.display = 'none';
                    content.style.display = 'block';
                } else {
                    alert(res.message);
                    closeDetailModal();
                }
            })
            .catch(err => {
                alert('Gagal mengambil data: ' + err.message);
                closeDetailModal();
            });
    }

    function closeDetailModal() {
        document.getElementById('detailModalAdmin').style.display = 'none';
    }
</script>
</body>
</html>
