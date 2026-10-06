<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPANDU - Admin Peminjaman Barang</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            min-height: 100vh;
        }

        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 28px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .content {
            padding: 28px;
            flex: 1;
        }

        .page-top h1 {
            font-size: 22px;
            font-weight: 800;
            color: var(--blue);
            margin-bottom: 4px;
        }

        .page-top p {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 20px;
        }

        .table-card {
            background: var(--surface);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .table-toolbar {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
        }

        .search-wrap {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 8px 14px;
            background: var(--bg);
        }

        .search-wrap input {
            border: none;
            background: none;
            outline: none;
            font-size: 13.5px;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead tr {
            background: #F8FAFF;
        }

        th {
            padding: 12px 20px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            color: var(--blue);
            text-transform: uppercase;
            border-bottom: 1px solid var(--border);
        }

        td {
            padding: 14px 20px;
            font-size: 13.5px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .status-diterima {
            background: #DCFCE7;
            color: #16A34A;
        }

        .status-pending {
            background: #FEF3C7;
            color: #D97706;
        }

        .status-ditolak {
            background: #FEE2E2;
            color: #DC2626;
        }

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
            color: var(--gray-600);
            margin-bottom: 4px;
        }

        .action-btn:hover {
            background: #f8fafc;
            border-color: var(--blue);
            color: var(--blue);
        }

        .action-btn.teruskan {
            color: var(--blue);
        }

        .action-btn.tolak {
            color: var(--danger);
        }

        .action-btn.upload {
            color: var(--success);
        }

        .action-btn.upload:hover {
            background: #ecfdf5;
            border-color: var(--success);
        }

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-box {
            background: #fff;
            width: 420px;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .modal-box.large {
            width: 550px;
        }

        /* Tambahan untuk Modal Detail */
        .modal-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 16px;
            font-family: inherit;
        }

        /* Tabel khusus di dalam Modal Detail */
        .detail-table {
            width: 100%;
            border: none;
        }

        .detail-table td {
            padding: 8px 0;
            border: none;
            font-size: 13px;
            vertical-align: top;
        }

        .detail-table td.label-col {
            font-weight: 600;
            width: 140px;
            color: var(--text);
        }

        .detail-table td.colon-col {
            width: 10px;
        }
    </style>
</head>

<body>

    @include('partials.sidebar')

    <main class="main">
        <div class="topbar">
            <span class="topbar-title">Manajemen Peminjaman Barang</span>
        </div>

        <div class="content">
            <div class="page-top">
                <h1>Daftar Peminjaman Barang</h1>
                <p>Kelola verifikasi dan dokumen BMN BPMP Provinsi Gorontalo.</p>
            </div>

            @if (session('success'))
                <div
                    style="background:#DCFCE7; color:#16A34A; padding:12px; border-radius:8px; margin-bottom:16px; font-size:13px; font-weight:600; border: 1px solid #bbf7d0;">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <div class="table-card">
                <div class="table-toolbar">
                    <form action="{{ url()->current() }}" method="GET" class="search-wrap">
                        <i class="fas fa-search" style="color: var(--muted)"></i>
                        <input type="text" name="search" placeholder="Cari barang atau peminjam..."
                            value="{{ request('search') }}">
                    </form>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Detail Barang</th>
                            <th>Peminjam</th>
                            <th>Jadwal</th>
                            <th>Status</th>
                            <th style="width: 280px;">Aksi Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamanBarang as $index => $item)
                            <tr>
                                <td><strong>{{ $index + 1 }}</strong></td>
                                <td>
                                    <div style="font-weight:700">{{ $item->nama_barang }}</div>
                                    <div style="font-size:11px; color:var(--muted);">NUP: {{ $item->nup ?? '-' }} |
                                        Kategori: {{ $item->kategori ?? 'Umum' }}</div>
                                </td>
                                <td>{{ $item->user->name ?? '-' }}</td>
                                <td>
                                    <span
                                        style="font-size: 12px;">{{ \Carbon\Carbon::parse($item->tanggal_peminjaman)->format('d/m/Y') }}</span><br>
                                    <small style="color:var(--muted)">s/d
                                        {{ \Carbon\Carbon::parse($item->tanggal_pengembalian)->format('d/m/Y') }}</small>
                                </td>
                                <td>
                                    @php
                                        $statusClass = 'status-pending';
                                        $statusText = 'Pending';
                                        if (in_array($item->status, ['disetujui', 'disetujui_admin'])) {
                                            $statusClass = 'status-diterima';
                                            $statusText = 'Disetujui';
                                        } elseif ($item->status == 'ditolak') {
                                            $statusClass = 'status-ditolak';
                                            $statusText = 'Ditolak';
                                        } elseif ($item->status == 'diteruskan_kasubag') {
                                            $statusText = 'Di teruskan kekasubag';
                                        } elseif ($item->status == 'dibatalkan') {
                                            $statusClass = 'status-ditolak';
                                            $statusText = 'Dibatalkan';
                                        }

                                    @endphp
                                    <span
                                        class="status-badge {{ $statusClass }}">{{ str_replace('_', ' ', ucfirst($statusText)) }}</span>
                                </td>
                                <td>
                                    @if(in_array($item->status, ['dibatalkan', 'ditolak']))
                                        <button class="action-btn" data-item="{{ json_encode($item) }}"
                                            data-peminjam="{{ $item->user->name ?? '-' }}"
                                            data-tglpinjam="{{ \Carbon\Carbon::parse($item->tanggal_peminjaman)->format('d/m/Y') }}"
                                            data-tglkembali="{{ \Carbon\Carbon::parse($item->tanggal_pengembalian)->format('d/m/Y') }}"
                                            onclick="openDetailModal(this)">
                                            <i class="fas fa-eye"></i> Detail
                                        </button>
                                        <span style="font-size: 12px; color: var(--muted); font-style: italic; margin-left: 6px;">
                                            {{ $item->status == 'ditolak' ? 'Ditolak' : 'Dibatalkan' }}
                                        </span>
                                    @else
                                        <button class="action-btn" data-item="{{ json_encode($item) }}"
                                            data-peminjam="{{ $item->user->name ?? '-' }}"
                                            data-tglpinjam="{{ \Carbon\Carbon::parse($item->tanggal_peminjaman)->format('d/m/Y') }}"
                                            data-tglkembali="{{ \Carbon\Carbon::parse($item->tanggal_pengembalian)->format('d/m/Y') }}"
                                            onclick="openDetailModal(this)">
                                            <i class="fas fa-eye"></i> Detail
                                        </button>

                                        @if(in_array($item->status, ['disetujui', 'diteruskan_kasubag', 'dikembalikan']))
                                        <form action="{{ route('adminasettetap.peminjaman-barang.print', $item->id) }}" method="GET" target="_blank" style="display:inline-flex; gap:4px; align-items:center;">
                                            <input type="date" name="tanggal_surat" value="{{ now()->format('Y-m-d') }}" required title="Tanggal surat" style="padding:5px; max-width:135px;">
                                            <button type="submit" class="action-btn" style="color: var(--purple);">
                                            <i class="fas fa-file-pdf"></i> Cetak
                                            </button>
                                        </form>
                                        @endif

                                         @if ($item->status == 'pending')
                                              <button class="action-btn teruskan" style="background:#2563eb; color:#fff;"
                                                  onclick="openReviewModal({{ $item->id }}, 'teruskan')">
                                                  <i class="fas fa-arrow-right"></i> Teruskan
                                              </button>
                                              <button class="action-btn tolak"
                                                  onclick="openReviewModal({{ $item->id }}, 'tolak')">
                                                  <i class="fas fa-times"></i> Tolak
                                              </button>
                                         @elseif(in_array($item->status, ['diteruskan_kasubag', 'disetujui']))
                                            <button class="action-btn upload"
                                                onclick="openUploadModal({{ $item->id }})">
                                                <i class="fas fa-upload"></i> Upload BAST
                                            </button>
                                        @endif

                                        @if (!empty($item->surat_bast_path))
                                            <i class="fas fa-check-double" style="color: var(--success); margin-left: 4px;"
                                                title="BAST Terunggah"></i>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center; padding:40px; color:var(--muted);">Data
                                    tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modal Detail (BARU) -->
    <div class="modal-overlay" id="detailModal">
        <div class="modal-box large">
            <div class="modal-title" style="display:flex; justify-content:space-between; align-items:center;">
                <span>Detail Peminjaman Barang</span>
                <button onclick="closeModal('detailModal')"
                    style="background:none; border:none; cursor:pointer; font-size:18px; color:var(--muted);"><i
                        class="fas fa-times"></i></button>
            </div>
            <div style="background: var(--bg); padding: 16px; border-radius: 12px; margin-bottom: 20px;">
                <table class="detail-table">
                    <tr>
                        <td class="label-col">Peminjam</td>
                        <td class="colon-col">:</td>
                        <td><span id="detPeminjam" style="font-weight:600; color:var(--blue);"></span></td>
                    </tr>
                    <tr>
                        <td class="label-col">Nama Barang</td>
                        <td class="colon-col">:</td>
                        <td><span id="detBarang"></span></td>
                    </tr>
                    <tr>
                        <td class="label-col">Nomor Urut Pendaftaran</td>
                        <td class="colon-col">:</td>
                        <td><span id="detNup"></span></td>
                    </tr>
                    <tr>
                        <td class="label-col">Kategori</td>
                        <td class="colon-col">:</td>
                        <td><span id="detKategori"></span></td>
                    </tr>
                    <tr>
                        <td class="label-col">Jadwal Pinjam</td>
                        <td class="colon-col">:</td>
                        <td><span id="detTglPinjam"></span> <span style="color:var(--muted); margin:0 4px;">s/d</span>
                            <span id="detTglKembali"></span></td>
                    </tr>
                    <tr>
                        <td class="label-col">Keperluan / Kegiatan</td>
                        <td class="colon-col">:</td>
                        <td><span id="detKeperluan"></span></td>
                    </tr>
                    <tr>
                        <td class="label-col">Status Saat Ini</td>
                        <td class="colon-col">:</td>
                        <td><span id="detStatus"></span></td>
                    </tr>
                    <tr id="rowKeterangan" style="display: none;">
                        <td class="label-col">Keterangan/Catatan</td>
                        <td class="colon-col">:</td>
                        <td><span id="detKeterangan" style="color: var(--danger); font-style: italic;"></span></td>
                    </tr>
                    <tr id="rowMultiItems" style="display: none;">
                        <td colspan="3" style="padding-top: 12px;">
                            <div style="font-weight: 700; margin-bottom: 6px; color: var(--text);">Daftar Rincian Barang (Multi-Item):</div>
                            <div style="border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
                                <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                                    <thead style="background: #f1f5f9; text-align: left;">
                                        <tr>
                                            <th style="padding: 6px 8px;">No</th>
                                            <th style="padding: 6px 8px;">Nama Barang</th>
                                            <th style="padding: 6px 8px;">Kode</th>
                                            <th style="padding: 6px 8px;">NUP</th>
                                            <th style="padding: 6px 8px;">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detMultiItemsBody"></tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div style="display:flex; justify-content:flex-end;">
                <button type="button" class="action-btn" onclick="closeModal('detailModal')">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Modal Review -->
    <div class="modal-overlay" id="reviewModal">
        <div class="modal-box">
            <div class="modal-title" id="reviewTitle">Konfirmasi Aksi</div>
            <form id="reviewForm" method="POST">
                @csrf
                <input type="hidden" name="action" id="reviewAction">
                <div id="rejectReason" style="display:none;">
                    <label style="font-size:12px; font-weight:600; display:block; margin-bottom:8px;">Alasan
                        Penolakan</label>
                    <textarea name="komentar" class="form-control" rows="3"></textarea>
                </div>
                <p style="font-size:13px; color:var(--muted); margin-bottom:20px;">Lanjutkan proses peminjaman barang
                    ini?</p>
                <div style="display:flex; justify-content:flex-end; gap:10px;">
                    <button type="button" class="action-btn" onclick="closeModal('reviewModal')">Batal</button>
                    <button type="submit" class="action-btn teruskan" id="confirmBtn">Konfirmasi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Upload -->
    <div class="modal-overlay" id="uploadModal">
        <div class="modal-box">
            <div class="modal-title">Unggah BAST Final</div>
            <form id="uploadForm" method="POST" enctype="multipart/form-data">
                @csrf
                <label style="font-size:12px; font-weight:600; display:block; margin-bottom:8px;">File Surat BAST
                    (PDF)</label>
                <input type="file" name="surat_bast" accept="application/pdf" class="form-control" required>
                <div style="display:flex; justify-content:flex-end; gap:10px;">
                    <button type="button" class="action-btn" onclick="closeModal('uploadModal')">Batal</button>
                    <button type="submit" class="action-btn upload">Unggah File</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Fungsi Baru untuk membuka Modal Detail
        function openDetailModal(buttonElement) {
            // Parsing data dari attributes tombol
            const itemData = JSON.parse(buttonElement.getAttribute('data-item'));
            const peminjam = buttonElement.getAttribute('data-peminjam');
            const tglPinjam = buttonElement.getAttribute('data-tglpinjam');
            const tglKembali = buttonElement.getAttribute('data-tglkembali');

            // Isi data ke dalam modal
            document.getElementById('detPeminjam').innerText = peminjam;
            document.getElementById('detBarang').innerText = itemData.nama_barang || '-';
            document.getElementById('detNup').innerText = itemData.nup || '-';
            document.getElementById('detKategori').innerText = itemData.kategori || 'Umum';
            document.getElementById('detTglPinjam').innerText = tglPinjam;
            document.getElementById('detTglKembali').innerText = tglKembali;

            // Cek property keperluan atau kegiatan (sesuaikan dengan nama kolom DB Anda)
            document.getElementById('detKeperluan').innerText = itemData.deskripsi_peruntukan || itemData
                .deskripsi_peruntukan || '-';

            // Penanganan Badge Status
            let statusSpan = document.getElementById('detStatus');
            let status = itemData.status;
            let statusClass = 'status-pending';
            let statusText = 'Pending';

            if (status === 'disetujui' || status === 'disetujui_admin') {
                statusClass = 'status-diterima';
                statusText = 'Disetujui';
            } else if (status === 'ditolak') {
                statusClass = 'status-ditolak';
                statusText = 'Ditolak';
            } else if (status === 'diteruskan_kasubag') {
                statusClass = 'status-pending';
                statusText = 'Review Kasubag';
            } else if (status === 'dibatalkan') { 
                statusClass = 'status-ditolak';
                statusText = 'Dibatalkan';
            }

            statusSpan.className = 'status-badge ' + statusClass;
            statusSpan.innerText = statusText;

            // Tampilkan Alasan/Keterangan jika ditolak
            const rowKeterangan = document.getElementById('rowKeterangan');
            if (status === 'ditolak' && itemData.komentar) {
                document.getElementById('detKeterangan').innerText = itemData.komentar;
                rowKeterangan.style.display = 'table-row';
            } else {
                rowKeterangan.style.display = 'none';
            }

            // Penanganan Multi-Item
            const rowMultiItems = document.getElementById('rowMultiItems');
            const tbodyMulti = document.getElementById('detMultiItemsBody');
            if (rowMultiItems && tbodyMulti) {
                if (itemData.items && itemData.items.length > 0) {
                    let rows = '';
                    itemData.items.forEach((it, idx) => {
                        rows += `
                            <tr style="border-bottom: 1px solid #e2e8f0;">
                                <td style="padding: 6px 8px;">${idx + 1}</td>
                                <td style="padding: 6px 8px; font-weight: 600;">${it.nama_barang}</td>
                                <td style="padding: 6px 8px; font-family: monospace;">${it.kode_barang}</td>
                                <td style="padding: 6px 8px;">${it.nup || '-'}</td>
                                <td style="padding: 6px 8px; font-weight: 700; color: #10B981;">${it.jumlah} Unit</td>
                            </tr>
                        `;
                    });
                    tbodyMulti.innerHTML = rows;
                    rowMultiItems.style.display = 'table-row';
                } else {
                    rowMultiItems.style.display = 'none';
                }
            }

            // Tampilkan modal
            document.getElementById('detailModal').style.display = 'flex';
        }

        function openReviewModal(id, action) {
            const form = document.getElementById('reviewForm');
            form.action = `/adminasettetap/peminjaman-barang/${id}/review`;
            document.getElementById('reviewAction').value = action;

            const reasonDiv = document.getElementById('rejectReason');
            const confirmBtn = document.getElementById('confirmBtn');

            if (action === 'tolak') {
                document.getElementById('reviewTitle').innerText = 'Tolak Permintaan';
                reasonDiv.style.display = 'block';
                confirmBtn.className = 'action-btn tolak';
                confirmBtn.innerText = 'Tolak';
            } else {
                document.getElementById('reviewTitle').innerText = 'Teruskan ke Kasubag';
                reasonDiv.style.display = 'none';
                confirmBtn.className = 'action-btn teruskan';
                confirmBtn.style.background = '#2563eb';
                confirmBtn.style.color = '#fff';
                confirmBtn.innerText = 'Teruskan';
            }
            document.getElementById('reviewModal').style.display = 'flex';
        }

        function openUploadModal(id) {
            document.getElementById('uploadForm').action = `/adminasettetap/peminjaman-barang/${id}/upload-bast`;
            document.getElementById('uploadModal').style.display = 'flex';
        }

        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
        }
    </script>
</body>

</html>
