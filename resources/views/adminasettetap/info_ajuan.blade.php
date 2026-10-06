<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIPANDU - Info Ajuan Mutasi Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
:root{--blue:#4F6FFF;--blue-light:#EEF2FF;--blue-lighter:#F0F4FF;--green:#10B981;--green-light:#D1FAE5;--red:#EF4444;--red-light:#FEE2E2;--yellow:#F59E0B;--yellow-light:#FEF3C7;--gray-50:#F9FAFB;--gray-100:#F3F4F6;--gray-200:#E5E7EB;--gray-300:#D1D5DB;--gray-400:#9CA3AF;--gray-500:#6B7280;--gray-600:#4B5563;--gray-700:#374151;--gray-900:#1F2937;--sidebar-w:240px;--radius:16px;--radius-sm:8px;--radius-xs:6px;--bg:#F4F6FB;--surface:#FFFFFF;--text:#1E293B;--muted:#94A3B8;--border:#E8EDF5;--shadow-xs:0 1px 2px 0 rgba(0,0,0,0.05);--shadow-sm:0 1px 3px 0 rgba(0,0,0,0.1);--shadow-md:0 4px 6px -1px rgba(0,0,0,0.1);--shadow-lg:0 10px 15px -3px rgba(0,0,0,0.1)}*{margin:0;padding:0;box-sizing:border-box}body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--bg);color:var(--text);display:flex;min-height:100vh}.sidebar{width:var(--sidebar-w);background:var(--surface);border-right:1px solid var(--border);display:flex;flex-direction:column;position:fixed;top:0;left:0;bottom:0;z-index:100}.main{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;min-height:100vh}.topbar{background:var(--surface);border-bottom:1px solid var(--border);padding:0 28px;height:56px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:50}.topbar-title{font-size:16px;font-weight:700}.content{padding:28px;flex:1}.page-top{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:28px}.page-top h1 { font-size: 22px; font-weight: 800; color: var(--blue); margin-bottom: 4px; }.table-card{background:var(--surface);border-radius:var(--radius);border:1px solid var(--border);overflow:hidden;box-shadow:var(--shadow-xs)}.table-toolbar{display:flex;align-items:center;gap:12px;padding:16px 20px;border-bottom:1px solid var(--border);background:var(--gray-50)}.search-wrap{flex:1;display:flex;align-items:center;gap:8px;border:1.5px solid var(--border);border-radius:var(--radius-sm);padding:8px 14px;background:var(--surface);transition:all .15s}.btn-filter{display:flex;align-items:center;gap:6px;padding:8px 14px;border-radius:var(--radius-sm);border:1.5px solid var(--border);background:var(--surface);font-size:13px;color:#64748B;cursor:pointer;transition:all .15s}.btn-filter:hover{border-color:var(--blue);color:var(--blue);background:var(--blue-lighter)}table{width:100%;border-collapse:collapse}th{padding:14px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--blue);letter-spacing:.8px;text-transform:uppercase;border-bottom:2px solid var(--border);background:var(--gray-50)}td{padding:14px 20px;font-size:13.5px;color:var(--text);border-bottom:1px solid var(--border)}tbody tr:hover{background:var(--gray-50)}.action-btn{display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border:1.5px solid var(--border);background:var(--surface);border-radius:var(--radius-sm);cursor:pointer;color:var(--gray-500);transition:all .15s}.action-btn:hover{background:var(--blue-lighter);border-color:var(--blue);color:var(--blue)}.action-btn.danger:hover{background:var(--red-light);border-color:var(--red);color:var(--red)}.badge-user{display:inline-flex;align-items:center;gap:6px;background:var(--blue-light);color:var(--blue);padding:4px 10px;border-radius:20px;font-size:12px;font-weight:700;border:1px solid rgba(79,111,255,0.2)}.detail-item{display:flex;align-items:flex-start;gap:12px;padding:15px;background:var(--gray-50);border-radius:var(--radius-sm);border:1px solid var(--border)}.detail-icon{width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:14px}.detail-content h6{font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;margin-bottom:4px}.detail-content p{font-size:14px;font-weight:600;color:var(--text);margin:0}.pagination{margin:0;font-size:13px}.page-link{color:var(--blue);border-radius:var(--radius-xs);margin:0 2px}@media (max-width:768px){.sidebar{width:0}.main{margin-left:0}}
</style>
</head>
<body>

@include('partials.sidebar')

<main class="main">
    <div class="topbar">
        <span class="topbar-title">Admin Aset Tetap- Info Ajuan</span>
        <div class="topbar-right d-flex align-items-center gap-3">
            <span style="font-size: 13px; color: var(--gray-600)">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}</span>
<form method="POST" action="{{ route('logout', [], false) }}" style="display:inline; margin:0;">
@csrf
<button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Keluar</button>
</form>
        </div>
    </div>

    <div class="content">
        <div class="page-top">
            <div>
                <h1>Daftar Ajuan Mutasi Barang</h1>
                <p class="text-muted">Kelola dan tinjau informasi ajuan mutasi barang dari seluruh pegawai</p>
            </div>
            <div class="badge bg-primary px-3 py-2" style="border-radius: 10px;">{{ $ajuan_mutasi->total() }} Ajuan Masuk</div>
        </div>

        <div class="table-card">
            <div class="table-toolbar">
                <form method="GET" action="{{ route('adminasettetap.info-ajuan.index') }}" class="w-100 d-flex gap-2">
                    <div class="search-wrap">
                        <i class="fas fa-search text-muted"></i>
                        <input type="text" name="search" placeholder="Cari nama pegawai, aset, NUP, atau lokasi..." value="{{ request('search') }}" style="border:none; outline:none; background:none; font-size:13.5px; width:100%">
                    </div>
                    <button type="submit" class="btn-filter"><i class="fas fa-filter"></i> Filter</button>
                    @if(request('search'))
                        <a href="{{ route('adminasettetap.info-ajuan.index') }}" class="btn-filter text-decoration-none"><i class="fas fa-times"></i> Reset</a>
                    @endif
                </form>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Pegawai Pengaju</th>
                            <th>Tgl Ajuan</th>
                            <th>Kode & NUP</th>
                            <th>Nama Barang</th>
                            <th>Lokasi Awal</th>
                            <th>Lokasi Tujuan</th>
                            <th>Kondisi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ajuan_mutasi as $item)
                        <tr>
                            <td>
                                <span class="fw-bold">
                                    <i class="fas fa-user-circle"></i> {{ $item->user->name ?? 'User dihapus' }}
                                </span>
                                <p><span class="text-muted" style="font-size: 11.5px; font-weight: 600;">
                                    <i class="fas fa-building me-1"></i> {{ optional($item->user->unitKerja)->nama_unit ?? 'Unit Kerja Tidak Tercatat' }}
                                </span></p>
                            </td>
                            <td>{{ $item->tanggal_mutasi_formatted }}</td>
                            <td>
                                <div class="fw-bold">{{ $item->kode_barang }}</div>
                                <div class="text-muted small">NUP: {{ $item->nup ?? '-' }}</div>
                            </td>
                            <td>{{ $item->nama_barang }}</td>
                            <td><span class="text-danger small"><i class="fas fa-map-marker-alt"></i> {{ $item->lokasi_awal }}</span></td>
                            <td><span class="text-success small"><i class="fas fa-arrow-right"></i> {{ $item->lokasi_akhir }}</span></td>
                            <td>{{ $item->kondisi ?? '-' }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <button class="action-btn" onclick="infoManager.showDetail({{ $item->id }})" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="action-btn danger" onclick="infoManager.deleteAjuan({{ $item->id }})" title="Tolak / Hapus Ajuan">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="mb-3 opacity-50">
                                <h6 class="text-muted">Belum ada ajuan mutasi dari pegawai</h6>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($ajuan_mutasi->hasPages())
            <div class="p-3 border-top d-flex justify-content-between align-items-center">
                <span class="text-muted small">Menampilkan {{ $ajuan_mutasi->firstItem() }} - {{ $ajuan_mutasi->lastItem() }} dari {{ $ajuan_mutasi->total() }} data</span>
                {!! $ajuan_mutasi->appends(request()->query())->links('pagination::bootstrap-5') !!}
            </div>
            @endif
        </div>
    </div>
</main>

<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border:none; overflow:hidden">
            <div class="modal-header border-0 p-4" style="background: var(--blue-lighter)">
                <h5 class="modal-title fw-bold text-primary"><i class="fas fa-info-circle"></i> Detail Permohonan Mutasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="detailContent">
                </div>
            <div class="modal-footer border-0 p-4 bg-light">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal" style="border-radius:10px">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
class AdminInfoAjuan {
    constructor() {
        this.detailModal = new bootstrap.Modal(document.getElementById('modalDetail'));
    }

    async showDetail(id) {
        try {
            const response = await fetch(`/adminasettetap/info-ajuan/${id}`);
            const data = await response.json();
            
            document.getElementById('detailContent').innerHTML = `
                <div class="alert alert-info border-0 mb-4" style="border-radius:12px">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-user-tie fa-2x text-primary"></i>
                        <div>
                            <div class="small text-muted text-uppercase fw-bold">Pegawai Pengaju</div>
                            <div class="fw-bold fs-5">${data.user?.name || 'Unknown'}</div>
                        </div>
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="detail-item"><div class="detail-icon bg-white text-primary"><i class="fas fa-barcode"></i></div>
                        <div class="detail-content"><h6>Kode Barang</h6><p>${data.kode_barang}</p></div>
                    </div>
                    <div class="detail-item"><div class="detail-icon bg-white text-primary"><i class="fas fa-hashtag"></i></div>
                        <div class="detail-content"><h6>NUP</h6><p>${data.nup || '-'}</p></div>
                    </div>
                    <div class="detail-item" style="grid-column: span 2"><div class="detail-icon bg-white text-primary"><i class="fas fa-box"></i></div>
                        <div class="detail-content"><h6>Nama Barang</h6><p>${data.nama_barang}</p></div>
                    </div>
                    <div class="detail-item"><div class="detail-icon bg-white text-danger"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="detail-content"><h6>Lokasi Saat Ini</h6><p>${data.lokasi_awal}</p></div>
                    </div>
                    <div class="detail-item"><div class="detail-icon bg-white text-success"><i class="fas fa-map-pin"></i></div>
                        <div class="detail-content"><h6>Lokasi Tujuan</h6><p>${data.lokasi_akhir}</p></div>
                    </div>
                    <div class="detail-item"><div class="detail-icon bg-white text-warning"><i class="fas fa-thermometer-half"></i></div>
                        <div class="detail-content"><h6>Kondisi</h6><p>${data.kondisi || '-'}</p></div>
                    </div>
                    <div class="detail-item"><div class="detail-icon bg-white text-info"><i class="fas fa-calendar-day"></i></div>
                        <div class="detail-content"><h6>Tgl Ajuan</h6><p>${data.tanggal_mutasi_formatted}</p></div>
                    </div>
                    <div class="detail-item" style="grid-column: span 2"><div class="detail-icon bg-white text-muted"><i class="fas fa-comment-alt"></i></div>
                        <div class="detail-content"><h6>Alasan / Keterangan</h6><p>${data.keterangan || '-'}</p></div>
                    </div>
                </div>
            `;
            this.detailModal.show();
        } catch (e) { alert('Gagal mengambil data'); }
    }

    async deleteAjuan(id) {
        if(!confirm('Apakah anda yakin ingin menolak/menghapus ajuan ini?')) return;
        try {
            const res = await fetch(`/adminasettetap/info-ajuan/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            const result = await res.json();
            if(res.ok) { alert(result.message); location.reload(); }
        } catch (e) { alert('Terjadi kesalahan koneksi'); }
    }
}
const infoManager = new AdminInfoAjuan();
</script>
</body>
</html>
