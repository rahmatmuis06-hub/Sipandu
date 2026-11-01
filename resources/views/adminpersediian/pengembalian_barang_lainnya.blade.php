<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPANDU - Admin Pengembalian Barang Lainnya</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary: #d97706;
            --primary-light: #f59e0b;
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
            --warning: #f59e0b;
            --danger: #ef4444;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); display: flex; min-height: 100vh; }

        .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; }
        .topbar { background: var(--surface); border-bottom: 1px solid var(--border); padding: 0 28px; height: 56px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 50; }
        .content { padding: 28px; flex: 1; }

        .page-top h1 { font-size: 22px; font-weight: 800; color: #b45309; margin-bottom: 4px; }
        .page-top p { font-size: 13px; color: var(--muted); margin-bottom: 20px;}

        .table-card { background: var(--surface); border-radius: var(--radius); border: 1px solid var(--border); overflow: hidden; }
        .table-toolbar { display: flex; align-items: center; gap: 12px; padding: 16px 20px; border-bottom: 1px solid var(--border); }
        
        .search-wrap { flex: 1; display: flex; align-items: center; gap: 8px; border: 1.5px solid var(--border); border-radius: 10px; padding: 8px 14px; background: var(--bg); }
        .search-wrap input { border: none; background: none; outline: none; font-size: 13.5px; width: 100%; }

        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #FFFBEB; }
        th { padding: 12px 20px; text-align: left; font-size: 11px; font-weight: 700; color: #b45309; text-transform: uppercase; border-bottom: 1px solid var(--border); }
        td { padding: 14px 20px; font-size: 13.5px; border-bottom: 1px solid var(--border); vertical-align: middle; }

        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; }
        .status-diterima { background: #DCFCE7; color: #16A34A; }
        .status-pending { background: #FEF3C7; color: #D97706; }
        .status-ditolak { background: #FEE2E2; color: #DC2626; }
        .status-dibatalkan { background: #F1F5F9; color: #64748B; }
        
        .kondisi-badge { font-size: 10px; padding: 3px 8px; border-radius: 6px; font-weight: 600; border: 1px solid var(--border); }
        .kondisi-lengkap { background: #F8FAFF; color: var(--blue); border-color: #DDE4FF; }
        .kondisi-rusak { background: #FFF7ED; color: var(--warning); border-color: #FFEDD5; }
        .kondisi-hilang { background: #FEF2F2; color: var(--danger); border-color: #FEE2E2; }

        /* Tombol Aksi */
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
            color: #4b5563;
            margin-bottom: 4px;
        }
        .action-btn:hover { background: #f8fafc; border-color: var(--primary); color: var(--primary); }
        .action-btn.terima { color: var(--success); }
        .action-btn.terima:hover { background: #DCFCE7; border-color: var(--success); }
        .action-btn.tolak { color: var(--danger); }
        .action-btn.tolak:hover { background: #FEE2E2; border-color: var(--danger); }

        /* Modal */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px); }
        .modal-box { background: #fff; width: 90%; max-width: 500px; border-radius: 16px; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .modal-title { font-size: 18px; font-weight: 700; margin-bottom: 16px; color: #b45309; border-bottom: 1px solid var(--border); padding-bottom: 12px; }
        .form-control { width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 8px; font-size: 13px; margin-bottom: 16px; font-family: inherit; resize: vertical; }
    </style>
</head>
<body>

@include('partials.sidebar')

<main class="main">
    <div class="topbar">
        <span class="topbar-title" style="font-weight:700;">Manajemen Pengembalian Barang Lainnya</span>
    </div>

    <div class="content">
        <div class="page-top">
            <h1>Verifikasi Pengembalian Barang Lainnya</h1>
            <p>Tinjau dan verifikasi laporan pengembalian peralatan / barang lainnya dari pegawai BPMP Provinsi Gorontalo.</p>
        </div>

        @if(session('success'))
            <div style="background:#DCFCE7; color:#16A34A; padding:12px; border-radius:8px; margin-bottom:16px; font-size:13px; font-weight:600; border: 1px solid #bbf7d0;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background:#FEE2E2; color:#DC2626; padding:12px; border-radius:8px; margin-bottom:16px; font-size:13px; font-weight:600; border: 1px solid #fecaca;">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <div class="table-card">
            <div class="table-toolbar">
                <form action="{{ url()->current() }}" method="GET" class="search-wrap">
                    <i class="fas fa-search" style="color: var(--muted)"></i>
                    <input type="text" name="search" placeholder="Cari nama barang atau peminjam..." value="{{ request('search') }}">
                </form>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Detail Barang</th>
                        <th>Kondisi & Jumlah</th>
                        <th>Pengembali</th>
                        <th>Tgl Lapor</th>
                        <th>Status Verifikasi</th>
                        <th style="width: 250px;">Aksi Admin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengembalianList as $index => $item)
                    <tr>
                        <td><strong>{{ $pengembalianList->firstItem() + $index }}</strong></td>
                        <td>
                            <div style="font-weight:700; color:#b45309;">{{ $item->peminjamanBarangLainnya->nama_barang ?? 'Barang Dihapus' }}</div>
                            <div style="font-size:11px; color:var(--muted); display:flex; align-items:center; gap:6px; flex-wrap:wrap; margin-top:2px;">
                                <span>Kode: {{ $item->peminjamanBarangLainnya->kode_barang ?? '-' }}</span>
                                @if(!empty($item->peminjamanBarangLainnya->kode_unit))
                                    <span style="background:#FEF3C7; color:#B45309; border:1px solid #FDE68A; padding:1px 7px; border-radius:6px; font-weight:800;">
                                        🏷️ Kode Unit: {{ $item->peminjamanBarangLainnya->kode_unit }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div style="font-weight:700; font-size:13px;">{{ $item->jumlah_dikembalikan }} {{ $item->peminjamanBarangLainnya->satuan ?? 'Unit' }}</div>
                            @php
                                $kondisiClass = 'kondisi-lengkap';
                                $kondisiText = 'Lengkap / Baik';
                                if($item->status_pengembalian == 'rusak_ringan' || $item->status_pengembalian == 'rusak_berat') {
                                    $kondisiClass = 'kondisi-rusak';
                                    $kondisiText = str_replace('_', ' ', ucwords($item->status_pengembalian));
                                } elseif($item->status_pengembalian == 'hilang') {
                                    $kondisiClass = 'kondisi-hilang';
                                    $kondisiText = 'Hilang';
                                }
                            @endphp
                            <span class="kondisi-badge {{ $kondisiClass }}">{{ $kondisiText }}</span>
                        </td>
                        <td>
                            <div style="font-weight:600;">{{ $item->user->name ?? 'Unknown' }}</div>
                        </td>
                        <td>
                            <span style="font-size: 12px; font-weight:600;"><i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($item->tanggal_pengembalian_aktual)->format('d/m/Y') }}</span>
                        </td>
                        <td>
                            @php 
                                $statusClass = 'status-pending'; $statusText = 'Pending';
                                if($item->status_verifikasi == 'diterima') { $statusClass = 'status-diterima'; $statusText = 'Diterima'; }
                                elseif($item->status_verifikasi == 'ditolak') { $statusClass = 'status-ditolak'; $statusText = 'Ditolak'; }
                                elseif($item->status_verifikasi == 'dibatalkan') { $statusClass = 'status-dibatalkan'; $statusText = 'Dibatalkan'; }
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                        </td>
                        <td>
                            @if($item->status_verifikasi == 'dibatalkan')
                                <span style="font-size: 13px; color: var(--muted); font-style: italic;">Dibatalkan peminjam</span>
                            @else
                                <button class="action-btn" onclick="showDetailModal({{ $item->id }})">
                                    <i class="fas fa-eye"></i> Detail
                                </button>

                                @if($item->status_verifikasi == 'pending')
                                    <button class="action-btn terima" onclick="openVerifikasiModal({{ $item->id }}, 'diterima')">
                                        <i class="fas fa-check"></i> Terima
                                    </button>
                                    <button class="action-btn tolak" onclick="openVerifikasiModal({{ $item->id }}, 'ditolak')">
                                        <i class="fas fa-times"></i> Tolak
                                    </button>
                                @elseif($item->status_verifikasi == 'diterima')
                                    <form action="{{ route('adminpersediaan.pengembalian-barang-lainnya.cetak', $item->id) }}" method="GET" target="_blank" style="display:inline-flex; gap:4px; align-items:center;">
                                        <input type="date" name="tanggal_surat" value="{{ optional($item->tanggal_pengembalian_aktual)->format('Y-m-d') ?? now()->format('Y-m-d') }}" required title="Tanggal surat" style="padding:5px; max-width:135px; border:1px solid var(--border); border-radius:6px; font-size:12px;">
                                        <button type="submit" class="action-btn" style="color: #b45309;"><i class="fas fa-file-pdf"></i> Cetak Bukti</button>
                                    </form>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align:center; padding:40px; color:var(--muted);">Tidak ada data pengembalian barang lainnya.</td></tr>
                    @endforelse
                </tbody>
            </table>
            
            <div style="padding: 15px 20px;">
                {{ $pengembalianList->links() }}
            </div>
        </div>
    </div>
</main>

<!-- Modal Verifikasi -->
<div class="modal-overlay" id="verifikasiModal">
    <div class="modal-box">
        <div class="modal-title" id="verifikasiTitle">Verifikasi Pengembalian</div>
        <form id="verifikasiForm" method="POST">
            @csrf
            <input type="hidden" name="status_verifikasi" id="verifikasiAction">
            
            <div style="margin-bottom: 16px;">
                <label style="font-size:12px; font-weight:600; display:block; margin-bottom:8px; color:var(--text);">Catatan Admin / Alasan <span style="color:var(--muted); font-weight:400;">(Opsional)</span></label>
                <textarea name="komentar_admin" class="form-control" rows="4" placeholder="Berikan catatan terkait kondisi barang atau alasan penolakan..."></textarea>
            </div>
            
            <div id="verifikasiWarning" style="font-size:12.5px; color:var(--muted); margin-bottom:20px; background:#FFFBEB; padding:10px; border-radius:8px; border:1px solid #FDE68A;">
                Menerima laporan ini akan mengembalikan stok unit fisik barang lainnya dan menandai peminjaman selesai.
            </div>

            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" class="action-btn" onclick="closeModal('verifikasiModal')">Batal</button>
                <button type="submit" class="action-btn" id="confirmBtn" style="color:white; background:#d97706; border:none;">Konfirmasi</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detail JSON -->
<div class="modal-overlay" id="detailModal">
    <div class="modal-box">
        <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid var(--border); padding-bottom:12px; margin-bottom:15px;">
            <h3 style="font-size:18px; font-weight:700; color:#b45309;"><i class="fas fa-info-circle"></i> Detail Laporan Pengembalian</h3>
            <button type="button" onclick="closeModal('detailModal')" style="border:none; background:none; cursor:pointer; color:var(--muted); font-size:18px;"><i class="fas fa-times"></i></button>
        </div>
        
        <div id="loadingDetail" style="text-align:center; padding:30px;"><i class="fas fa-spinner fa-spin fa-2x" style="color:#d97706"></i></div>
        
        <div id="contentDetail" style="display:none;">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:15px;">
                <div>
                    <label style="font-size:10px; font-weight:700; color:var(--muted); text-transform:uppercase;">Barang Dikembalikan</label>
                    <div id="detNama" style="font-size:13.5px; font-weight:700; color:var(--text);"></div>
                    <div id="detKode" style="font-size:11px; color:var(--muted);"></div>
                </div>
                <div>
                    <label style="font-size:10px; font-weight:700; color:var(--muted); text-transform:uppercase;">Pengembali</label>
                    <div id="detPeminjam" style="font-size:13.5px; font-weight:700; color:var(--text);"></div>
                </div>
            </div>

            <div style="background:#F8FAFF; padding:12px; border-radius:10px; border:1px solid var(--border); margin-bottom:15px;">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                    <div>
                        <label style="font-size:10px; font-weight:700; color:var(--muted); text-transform:uppercase;">Kondisi Laporan</label>
                        <div id="detKondisi" style="font-size:13px; font-weight:700; color:var(--text);"></div>
                    </div>
                    <div>
                        <label style="font-size:10px; font-weight:700; color:var(--muted); text-transform:uppercase;">Catatan Pegawai</label>
                        <div id="detCatatan" style="font-size:12px; color:var(--text); line-height:1.4; margin-top:2px;"></div>
                    </div>
                </div>
            </div>

            <!-- Tempat Foto Bukti -->
            <div style="margin-bottom:15px;">
                <label style="font-size:10px; font-weight:700; color:var(--muted); text-transform:uppercase; display:block; margin-bottom:6px;">Foto Bukti Kondisi</label>
                <div id="detFotoContainer" style="background:#F4F6FB; border-radius:10px; height:150px; display:flex; align-items:center; justify-content:center; overflow:hidden; border:1px dashed var(--border);">
                    <img id="detFoto" src="" alt="Bukti Foto" style="max-height:100%; max-width:100%; display:none;">
                    <span id="detFotoEmpty" style="font-size:12px; color:var(--muted);"><i class="fas fa-image"></i> Tidak ada foto</span>
                </div>
            </div>

            <div style="text-align:right;">
                <button type="button" class="action-btn" onclick="closeModal('detailModal')">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    function openVerifikasiModal(id, action) {
        const form = document.getElementById('verifikasiForm');
        form.action = `/adminpersediaan/pengembalian-barang-lainnya/${id}/verifikasi`;
        document.getElementById('verifikasiAction').value = action;
        
        const btn = document.getElementById('confirmBtn');
        const warning = document.getElementById('verifikasiWarning');

        if(action === 'ditolak') {
            document.getElementById('verifikasiTitle').innerText = 'Tolak Pengembalian';
            btn.style.background = 'var(--danger)';
            btn.innerText = 'Tolak Laporan';
            warning.innerText = 'Menolak laporan ini akan mengembalikan status peminjaman menjadi disetujui agar pegawai dapat merevisi laporannya.';
            warning.style.color = 'var(--danger)';
            warning.style.background = '#FEE2E2';
            warning.style.borderColor = '#FECACA';
        } else {
            document.getElementById('verifikasiTitle').innerText = 'Terima Pengembalian';
            btn.style.background = 'var(--success)';
            btn.innerText = 'Terima Laporan';
            warning.innerText = 'Menerima laporan ini akan mengembalikan stok fisik barang dan menandai peminjaman selesai.';
            warning.style.color = '#92400e';
            warning.style.background = '#FEF3C7';
            warning.style.borderColor = '#FDE68A';
        }
        document.getElementById('verifikasiModal').style.display = 'flex';
    }

    function showDetailModal(id) {
        const modal = document.getElementById('detailModal');
        const loading = document.getElementById('loadingDetail');
        const content = document.getElementById('contentDetail');
        
        modal.style.display = 'flex';
        loading.style.display = 'block';
        content.style.display = 'none';

        fetch(`/adminpersediaan/pengembalian-barang-lainnya/${id}/json`)
            .then(res => res.json())
            .then(res => {
                if(res.success) {
                    const data = res.data;
                    document.getElementById('detNama').innerText = data.peminjaman_barang_lainnya ? data.peminjaman_barang_lainnya.nama_barang : '-';
                    const detKodeBrg = data.peminjaman_barang_lainnya ? (data.peminjaman_barang_lainnya.kode_barang || '-') : '-';
                    const detKodeUnt = data.peminjaman_barang_lainnya?.kode_unit ? ` • Kode Unit: ${data.peminjaman_barang_lainnya.kode_unit}` : '';
                    document.getElementById('detKode').innerText = detKodeBrg + detKodeUnt;
                    document.getElementById('detPeminjam').innerText = data.user ? data.user.name : '-';
                    
                    let kondisiText = (data.status_pengembalian || '').replace('_', ' ').toUpperCase();
                    let satuan = data.peminjaman_barang_lainnya?.satuan || 'Unit';
                    document.getElementById('detKondisi').innerText = `${kondisiText} (${data.jumlah_dikembalikan} ${satuan})`;
                    document.getElementById('detCatatan').innerText = data.catatan || 'Tidak ada catatan khusus.';

                    const imgEl = document.getElementById('detFoto');
                    const emptyEl = document.getElementById('detFotoEmpty');
                    if(data.foto_sesudah) {
                        imgEl.src = `/storage/${data.foto_sesudah}`;
                        imgEl.style.display = 'block';
                        emptyEl.style.display = 'none';
                    } else {
                        imgEl.style.display = 'none';
                        emptyEl.style.display = 'block';
                    }

                    loading.style.display = 'none';
                    content.style.display = 'block';
                }
            })
            .catch(err => {
                alert('Gagal mengambil data detail.');
                closeModal('detailModal');
            });
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }
</script>
</body>
</html>
