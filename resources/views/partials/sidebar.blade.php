@php
    $user = auth()->user();
    $currentRole = $user->role ?? 'guest';

    // Definisi warna dan konfigurasi untuk setiap role
    $roleConfig = [
        'superadmin' => [
            'bgGradient' => 'from-slate-900 to-slate-800',
            'badgeText' => 'Operator',
            'badgeColor' => 'bg-red-500/20 text-red-300',
            'navItems' => [
                [
                    'href' => route('superadmin.dashboard'),
                    'label' => 'Dashboard',
                    'icon' => 'fas fa-chart-line',
                    'route' => 'superadmin.dashboard',
                ],
                [
                    'href' => route('superadmin.manajemen-user'),
                    'label' => 'Kelola Pengguna',
                    'icon' => 'fas fa-users',
                    'route' => 'superadmin.manajemen-user',
                ],
                [
                    'href' => route('superadmin.unit_kerja.index'),
                    'label' => 'Kelola Unit Kerja',
                    'icon' => 'fas fa-building',
                    'route' => 'superadmin.unit_kerja.index',
                ],
            ],
        ],
        'pegawai' => [
            'bgGradient' => 'from-blue-900 to-blue-800',
            'badgeText' => 'Pegawai',
            'badgeColor' => 'bg-blue-500/20 text-blue-300',
            'navItems' => [
                [
                    'href' => route('pegawai.dashboard'),
                    'label' => 'Dashboard',
                    'icon' => 'fas fa-home',
                    'route' => 'pegawai.dashboard',
                ],
                [
                    'href' => route('pegawai.ajuan-mutasi.index'),
                    'label' => 'Ajuan Mutasi',
                    'icon' => 'fas fa-map-marker-alt',
                    'route' => 'pegawai.ajuan-mutasi.index',
                ],
                [
                    'href' => route('pegawai.peminjaman-barang'),
                    'label' => 'Peminjaman Barang',
                    'icon' => 'fas fa-box',
                    'route' => 'pegawai.peminjaman-barang',
                ],
                [
                    'href' => route('pegawai.peminjaman-kendaraan'),
                    'label' => 'Peminjaman Kendaraan',
                    'icon' => 'fas fa-car',
                    'route' => 'pegawai.peminjaman-kendaraan',
                ],
                [
                    'href' => route('pegawai.permintaan-persediaan'),
                    'label' => 'Permintaan Persediaan',
                    'icon' => 'fas fa-boxes',
                    'route' => 'pegawai.permintaan-persediaan',
                ],

                [
                    'href' => route('pegawai.pengembalian-barang'),
                    'label' => 'Pengembalian Barang',
                    'icon' => 'fas fa-undo',
                    'route' => 'pegawai.pengembalian-barang',
                ],
                [
                    'href' => route('pegawai.pengembalian-kendaraan'),
                    'label' => 'Pengembalian Kendaraan',
                    'icon' => 'fas fa-undo',
                    'route' => 'pegawai.pengembalian-kendaraan',
                ],

                [
                    'href' => route('pegawai.pengaturan-akun'),
                    'label' => 'Pengaturan Akun',
                    'icon' => 'fas fa-gear',
                    'route' => 'pegawai.pengaturan-akun',
                ],
            ],
        ],
        'adminasettetap' => [
            'bgGradient' => 'from-orange-900 to-orange-800',
            'badgeText' => 'Admin Aset Tetap',
            'badgeColor' => 'bg-orange-500/20 text-orange-300',
            'navItems' => [
                [
                    'href' => route('adminasettetap.dashboard'),
                    'label' => 'Dashboard',
                    'icon' => 'fas fa-tachometer-alt',
                    'route' => 'adminasettetap.dashboard',
                ],
                [
                    'href' => route('adminasettetap.data-aset-tetap'),
                    'label' => 'Data Aset Tetap',
                    'icon' => 'fas fa-database',
                    'route' => 'adminasettetap.data-aset-tetap',
                ],
                [
                    'href' => route('adminasettetap.transaksi-masuk'),
                    'label' => 'Transaksi Masuk',
                    'icon' => 'fas fa-boxes',
                    'route' => 'adminasettetap.transaksi-masuk',
                ],
                [
                    'href' => route('adminasettetap.transaksi-keluar'),
                    'label' => 'Transaksi Keluar',
                    'icon' => 'fas fa-boxes',
                    'route' => 'adminasettetap.transaksi-keluar',
                ],
                [
                    'href' => route('adminasettetap.mutasi-barang'),
                    'label' => 'Mutasi Barang',
                    'icon' => 'fas fa-map-marker-alt',
                    'route' => 'adminasettetap.mutasi-barang',
                ],

                [
                    'href' => route('adminasettetap.info-ajuan.index'),
                    'label' => 'Info Ajuan Mutasi',
                    'icon' => 'fas fa-map-marker-alt',
                    'route' => 'adminasettetap.info-ajuan.index',
                ],

                [
                    'label' => 'Manajemen Peminjaman',
                    'icon' => 'fas fa-exchange-alt',
                    'route' => 'NONE',
                    'children' => [
                        [
                            'href' => route('adminasettetap.peminjaman-barang'),
                            'label' => 'Peminjaman Barang',
                            'route' => 'adminasettetap.peminjaman-barang',
                        ],
                        [
                            'href' => route('adminasettetap.peminjaman-kendaraan'),
                            'label' => 'Peminjaman Kendaraan',
                            'route' => 'adminasettetap.peminjaman-kendaraan',
                        ],
                    ],
                ],
                [
                    'label' => 'Manajemen Pengembalian',
                    'icon' => 'fas fa-undo-alt',
                    'route' => 'NONE',
                    'children' => [
                        [
                            'href' => route('adminasettetap.pengembalian-barang'),
                            'label' => 'Pengembalian Barang',
                            'route' => 'adminasettetap.pengembalian-barang',
                        ],
                        [
                            'href' => route('adminasettetap.pengembalian-kendaraan'),
                            'label' => 'Pengembalian Kendaraan',
                            'route' => 'adminasettetap.pengembalian-kendaraan',
                        ],
                    ],
                ],
                [
                    'label' => 'Laporan & Statistik',
                    'icon' => 'fas fa-chart-pie',
                    'route' => 'NONE',
                    'children' => [
                        [
                            'href' => route('adminasettetap.laporan'),
                            'label' => 'Ringkasan & Analitik',
                            'route' => 'adminasettetap.laporan',
                        ],
                        [
                            'href' => route('adminasettetap.laporan-transaksi-keluar'),
                            'label' => 'Laporan Transaksi Keluar',
                            'route' => 'adminasettetap.laporan-transaksi-keluar',
                        ],
                    ],
                ],
                [
                    'href' => route('adminasettetap.pengaturan-akun'),
                    'label' => 'Pengaturan Akun',
                    'icon' => 'fas fa-gear',
                    'route' => 'adminasettetap.pengaturan-akun',
                ],
            ],
        ],
        'adminpersediaan' => [
            'bgGradient' => 'from-green-900 to-green-800',
            'badgeText' => 'Admin Persediaan',
            'badgeColor' => 'bg-green-500/20 text-green-300',
            'navItems' => [
                [
                    'href' => route('adminpersediaan.dashboard'),
                    'label' => 'Dashboard',
                    'icon' => 'fas fa-tachometer-alt',
                    'route' => 'adminpersediaan.dashboard',
                ],
                [
                    'href' => route('adminpersediaan.data-persediaan'),
                    'label' => 'Data Persediaan',
                    'icon' => 'fas fa-database',
                    'route' => 'adminpersediaan.data-persediaan',
                ],
                ...(config('features.show_stok_opname') ? [[
                    'href' => route('adminpersediaan.opname.index'),
                    'label' => 'Stok Opname Bulanan',
                    'icon' => 'fas fa-clipboard-check',
                    'route' => 'adminpersediaan.opname.*',
                ]] : []),
                [
                    'href' => route('adminpersediaan.transaksi-masuk'),
                    'label' => 'Transaksi Masuk',
                    'icon' => 'fas fa-boxes',
                    'route' => 'adminpersediaan.transaksi-masuk',
                ],
                [
                    'href' => route('adminpersediaan.transaksi-keluar'),
                    'label' => 'Transaksi Keluar',
                    'icon' => 'fas fa-boxes',
                    'route' => 'adminpersediaan.transaksi-keluar',
                ],

                [
                    'label' => 'Manajemen Permintaan Persediaan',
                    'icon' => 'fas fa-exchange-alt',
                    'route' => 'NONE',
                    'children' => [
                        [
                            'href' => route('adminpersediaan.permintaan-persediaan'),
                            'label' => 'Permintaan Persediaan',
                            'route' => 'adminpersediaan.permintaan-persediaan',
                        ],
                    ],
                ],
                [
                    'label' => 'Laporan & Statistik',
                    'icon' => 'fas fa-chart-bar',
                    'route' => 'NONE',
                    'children' => [
                        [
                            'href' => route('adminpersediaan.laporan-transaksi-masuk'),
                            'label' => 'Laporan Transaksi Masuk',
                            'route' => 'adminpersediaan.laporan-transaksi-masuk',
                        ],
                        [
                            'href' => route('adminpersediaan.laporan-transaksi-keluar'),
                            'label' => 'Laporan Transaksi Keluar',
                            'route' => 'adminpersediaan.laporan-transaksi-keluar',
                        ],
                        [
                            'href' => route('adminpersediaan.laporan-permintaan-persediaan'),
                            'label' => 'Laporan Permintaan Persediaan',
                            'route' => 'adminpersediaan.laporan-permintaan-persediaan',
                        ],
                    ],
                ],
                [
                    'href' => route('adminpersediaan.pengaturan-akun'),
                    'label' => 'Pengaturan Akun',
                    'icon' => 'fas fa-gear',
                    'route' => 'adminpersediaan.pengaturan-akun',
                ],
            ],
        ],

        //ADMIN SARPRAS
        'adminsarpras' => [
            'bgGradient' => 'from-cyan-900 to-cyan-800',
            'badgeText' => 'Admin Sarpras',
            'badgeColor' => 'bg-cyan-500/20 text-cyan-300',
            'navItems' => [
                [
                    'href' => route('adminsarpras.dashboard'),
                    'label' => 'Dashboard',
                    'icon' => 'fas fa-tachometer-alt',
                    'route' => 'adminsarpras.dashboard',
                ],
                [
                    'href' => route('adminsarpras.data-gedung'),
                    'label' => 'Data Gedung',
                    'icon' => 'fas fa-building',
                    'route' => 'adminsarpras.data-gedung',
                ],
                [
                    'href' => route('adminsarpras.data-kerusakan'),
                    'label' => 'Data Kerusakan',
                    'icon' => 'fas fa-wrench',
                    'route' => 'adminsarpras.data-kerusakan',
                ],
                [
                    'href' => route('adminsarpras.laporan-kerusakan'),
                    'label' => 'Laporan Kerusakan',
                    'icon' => 'fas fa-file-alt',
                    'route' => 'adminsarpras.laporan-kerusakan',
                ],
                [
                    'href' => route('adminsarpras.integrasi-ult'),
                    'label' => 'Integrasi ULT',
                    'icon' => 'fas fa-network-wired',
                    'route' => 'adminsarpras.integrasi-ult',
                ],

                [
                    'href' => route('adminsarpras.pengaturan-akun'),
                    'label' => 'Pengaturan Akun',
                    'icon' => 'fas fa-gear',
                    'route' => 'adminsarpras.pengaturan-akun',
                ],
            ],
        ],
        'kasubag' => [
            'bgGradient' => 'from-indigo-900 to-indigo-800',
            'badgeText' => 'Kasubag Umum',
            'badgeColor' => 'bg-indigo-500/20 text-indigo-300',
            'navItems' => [
                [
                    'href' => route('kasubag.dashboard'),
                    'label' => 'Dashboard',
                    'icon' => 'fas fa-tachometer-alt',
                    'route' => 'kasubag.dashboard',
                ],

                // ✅ DROPDOWN MENU - MANAJEMEN PERSETUJUAN
                [
                    'label' => 'Manajemen Persetujuan',
                    'icon' => 'fas fa-check-square',
                    'route' => 'NONE',
                    'children' => [
                        [
                            'href' => route('kasubag.persetujuan-peminjaman-barang'),
                            'label' => 'Peminjaman Barang',
                            'route' => 'kasubag.persetujuan-peminjaman-barang',
                        ],
                        [
                            'href' => route('kasubag.persetujuan-peminjaman-kendaraan'),
                            'label' => 'Peminjaman Kendaraan',
                            'route' => 'kasubag.persetujuan-peminjaman-kendaraan',
                        ],
                    ],
                ],
                // ✅ DROPDOWN MENU - MONITORING
                [
                    'label' => 'Monitoring',
                    'icon' => 'fas fa-chart-line',
                    'route' => 'NONE',
                    'children' => [
                        [
                            'href' => route('kasubag.monitoring-persediaan'),
                            'label' => 'Permintaan Persediaan',
                            'route' => 'kasubag.monitoring-persediaan*',
                        ],
                        [
                            'href' => route('kasubag.monitoring-aset-tetap'),
                            'label' => 'Barang / Aset Tetap',
                            'route' => 'kasubag.monitoring-aset-tetap*',
                        ],
                        [
                            'href' => route('kasubag.monitoring-kendaraan'),
                            'label' => 'Kendaraan',
                            'route' => 'kasubag.monitoring-kendaraan*',
                        ],
                    ],
                ],
                [
                    'href' => route('kasubag.laporan-transaksi-keluar'),
                    'label' => 'Laporan Transaksi Keluar',
                    'icon' => 'fas fa-file-invoice',
                    'route' => 'kasubag.laporan-transaksi-keluar*',
                ],

                [
                    'href' => route('kasubag.pengaturan-akun'),
                    'label' => 'Pengaturan Akun',
                    'icon' => 'fas fa-gear',
                    'route' => 'kasubag.pengaturan-akun',
                ],
            ],
        ],
        'kepalabpmp' => [
            'bgGradient' => 'from-purple-900 to-purple-800',
            'badgeText' => 'Kepala BPMP',
            'badgeColor' => 'bg-purple-500/20 text-purple-300',
            'navItems' => [
                [
                    'href' => route('kepalabpmp.dashboard'),
                    'label' => 'Dashboard',
                    'icon' => 'fas fa-tachometer-alt',
                    'route' => 'kepalabpmp.dashboard',
                ],
                [
                    'href' => route('kepalabpmp.laporan'),
                    'label' => 'Laporan BMN',
                    'icon' => 'fas fa-chart-bar',
                    'route' => 'kepalabpmp.laporan',
                ],
                [
                    'label' => 'Download Laporan',
                    'icon' => 'fas fa-download',
                    'route' => 'NONE',
                    'children' => [
                        [
                            'href' => route('kepalabpmp.laporan.download-persediaan'),
                            'label' => 'Laporan Persediaan',
                            'route' => 'kepalabpmp.laporan.download-persediaan',
                        ],
                        [
                            'href' => route('kepalabpmp.laporan.download-aset-tetap'),
                            'label' => 'Laporan Aset Tetap',
                            'route' => 'kepalabpmp.laporan.download-aset-tetap',
                        ],
                        [
                            'href' => route('kepalabpmp.laporan.download-sarpras'),
                            'label' => 'Laporan Sarpras',
                            'route' => 'kepalabpmp.laporan.download-sarpras',
                        ],
                        [
                            'href' => route('kepalabpmp.laporan.download-lengkap'),
                            'label' => 'Laporan Lengkap',
                            'route' => 'kepalabpmp.laporan.download-lengkap',
                        ],
                    ],
                ],
                [
                    'href' => route('kepalabpmp.pengaturan-akun'),
                    'label' => 'Pengaturan Akun',
                    'icon' => 'fas fa-gear',
                    'route' => 'kepalabpmp.pengaturan-akun',
                ],
            ],
        ],
    ];

    // Dapatkan konfigurasi dari role saat ini
    $config = $roleConfig[$currentRole] ?? $roleConfig['pegawai'];
@endphp

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
    rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<script src="https://cdn.tailwindcss.com/3.4.17"></script>
<script src="https://cdn.jsdelivr.net/npm/lucide@0.263.0/dist/umd/lucide.min.js"></script>
<script src="/_sdk/element_sdk.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
    rel="stylesheet">

<script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    jakarta: ['Plus Jakarta Sans', 'sans-serif']
                }
            }
        }
    }
</script>

<style>
    :root {
        --sidebar-width: 256px;
        --primary-color: #2563eb;
        --primary-light: #dbeafe;
        --primary-dark: #1e40af;
        --border-color: #e5e7eb;
        --text-muted: #6b7280;
        --text-dark: #111827;
        --bg-light: #f9fafb;
    }

    .sidebar-wrapper {
        width: var(--sidebar-width);
        background: #ffffff;
        border-right: 1px solid var(--border-color);
        display: flex;
        flex-direction: column;
        position: fixed;
        left: 0;
        top: 0;
        bottom: 0;
        z-index: 100;
        overflow-y: auto;
        /* font-family: 'Plus Jakarta Sans', sans-serif; */
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        font-weight: 500;
    }

    .main {
        margin-left: var(--sidebar-width);
        min-height: 100vh;
    }

    /* ── HEADER ── */
    .sidebar-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 20px 16px;
        border-bottom: 1px solid var(--border-color);
    }

    .sidebar-logo {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        flex-shrink: 0;
    }

    .sidebar-brand {
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .sidebar-brand-name {
        color: var(--text-dark);
        font-weight: 700;
        font-size: 15px;
        line-height: 1.2;
    }

    .sidebar-brand-sub {
        color: var(--text-muted);
        font-size: 11px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    /* ── ROLE BADGE SECTION ── */
    .sidebar-role-section {
        padding: 12px 16px;
        border-bottom: 1px solid var(--border-color);
    }

    .sidebar-role-label {
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        margin-bottom: 8px;
        display: block;
    }

    .sidebar-role-badge {
        display: inline-block;
        background: #dbeafe;
        color: var(--primary-dark);
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        border: 1px solid #bfdbfe;
        width: 100%;
        text-align: center;
    }

    /* ── NAVIGATION ── */
    .sidebar-nav {
        flex: 1;
        padding: 8px 8px;
        overflow-y: auto;
    }

    .nav-group {
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    /* Menu Item */
    .nav-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        margin: 2px 0;
        border-radius: 6px;
        color: var(--text-muted);
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
    }

    .nav-item:hover {
        background: var(--bg-light);
        color: var(--text-dark);
    }

    .nav-item.active {
        background: var(--primary-light);
        color: var(--primary-dark);
        border-left-color: var(--primary-color);
        font-weight: 600;
    }

    .nav-icon {
        width: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    /* Toggle Button */
    .nav-item-toggle {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        cursor: pointer;
        border: none;
        background: none;
        padding: 10px 12px;
        margin: 2px 0;
        border-radius: 6px;
        color: var(--text-muted);
        font-size: 13px;
        font-weight: 500;
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .nav-item-toggle:hover {
        background: var(--bg-light);
        color: var(--text-dark);
    }

    .nav-item-toggle.active {
        background: var(--primary-light);
        color: var(--primary-dark);
        border-left-color: var(--primary-color);
        font-weight: 600;
    }

    .nav-arrow {
        font-size: 12px;
        transition: transform 0.3s ease;
        margin-left: auto;
        width: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .nav-item-toggle.active .nav-arrow {
        transform: rotate(180deg);
    }

    /* Submenu */
    .nav-submenu {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
        opacity: 0;
    }

    .nav-submenu.expanded {
        max-height: 500px;
        opacity: 1;
    }

    .nav-subitem {
        display: block;
        padding: 8px 12px 8px 40px;
        margin: 0;
        border-radius: 6px;
        color: var(--text-muted);
        text-decoration: none;
        font-size: 12px;
        font-weight: 500;
        transition: all 0.2s ease;
        border-left: 2px solid transparent;
    }

    .nav-subitem:hover {
        background: var(--bg-light);
        color: var(--text-dark);
    }

    .nav-subitem.active {
        background: var(--primary-light);
        color: var(--primary-dark);
        border-left-color: var(--primary-color);
        font-weight: 600;
    }

    /* ── FOOTER ── */
    .sidebar-footer {
        padding: 12px 8px;
        border-top: 1px solid var(--border-color);
        background: var(--bg-light);
    }

    .sidebar-user {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        margin-bottom: 8px;
        background: white;
        border-radius: 6px;
        border: 1px solid var(--border-color);
    }

    .user-avatar {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 14px;
        flex-shrink: 0;
    }

    .user-info {
        flex: 1;
        min-width: 0;
    }

    .user-name {
        color: var(--text-dark);
        font-weight: 600;
        font-size: 13px;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-role {
        color: var(--text-muted);
        font-size: 11px;
        line-height: 1.2;
    }

    /* Logout Button */
    .logout-btn {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 12px;
        border: 1px solid #fecaca;
        background: #fef2f2;
        border-radius: 6px;
        color: #dc2626;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .logout-btn:hover {
        background: #fee2e2;
        border-color: #fca5a5;
    }

    /* Scrollbar */
    .sidebar-nav::-webkit-scrollbar {
        width: 4px;
    }

    .sidebar-nav::-webkit-scrollbar-track {
        background: transparent;
    }

    .sidebar-nav::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 2px;
    }

    .sidebar-nav::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }

    /* ── MOBILE HAMBURGER ── */
    .mobile-topbar {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 90;
        background: #ffffff;
        border-bottom: 1px solid var(--border-color);
        padding: 10px 16px;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .hamburger-btn {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: none;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        cursor: pointer;
        color: var(--text-dark);
        font-size: 18px;
        transition: all 0.2s;
    }

    .hamburger-btn:hover {
        background: var(--bg-light);
    }

    .sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.45);
        z-index: 99;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .sidebar-overlay.active {
        display: block;
        opacity: 1;
    }

    @media (max-width: 768px) {
        .mobile-topbar {
            display: flex;
        }

        .sidebar-wrapper {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            z-index: 200;
        }

        .sidebar-wrapper.mobile-open {
            transform: translateX(0);
        }

        .main {
            margin-left: 0 !important;
            padding-top: 60px !important;
        }
    }
</style>

<!-- Mobile Top Bar -->
<div class="mobile-topbar" id="mobileTopbar">
    <button class="hamburger-btn" onclick="toggleMobileSidebar()">
        <i class="fas fa-bars"></i>
    </button>
    <div style="display:flex;align-items:center;gap:8px;">
        <div
            style="width:28px;height:28px;border-radius:6px;background:linear-gradient(135deg,#2563eb,#1d4ed8);display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-boxes" style="color:#fff;font-size:12px;"></i>
        </div>
        <span style="font-weight:700;font-size:14px;color:#111827;">SIPANDU</span>
    </div>
    <div style="width:40px;"></div>
</div>
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleMobileSidebar()"></div>

<aside class="sidebar-wrapper" id="sidebarWrapper">
    <!-- Header -->
    <div class="sidebar-header">
        <div class="flex items-center gap-3">
            <div
                class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    data-lucide="boxes" class="lucide lucide-boxes w-5 h-5 text-white">
                    <path
                        d="M2.97 12.92A2 2 0 0 0 2 14.63v3.24a2 2 0 0 0 .97 1.71l3 1.8a2 2 0 0 0 2.06 0L12 19v-5.5l-5-3-4.03 2.42Z">
                    </path>
                    <path d="m7 16.5-4.74-2.85"></path>
                    <path d="m7 16.5 5-3"></path>
                    <path d="M7 16.5v5.17"></path>
                    <path
                        d="M12 13.5V19l3.97 2.38a2 2 0 0 0 2.06 0l3-1.8a2 2 0 0 0 .97-1.71v-3.24a2 2 0 0 0-.97-1.71L17 10.5l-5 3Z">
                    </path>
                    <path d="m17 16.5-5-3"></path>
                    <path d="m17 16.5 4.74-2.85"></path>
                    <path d="M17 16.5v5.17"></path>
                    <path
                        d="M7.97 4.42A2 2 0 0 0 7 6.13v4.37l5 3 5-3V6.13a2 2 0 0 0-.97-1.71l-3-1.8a2 2 0 0 0-2.06 0l-3 1.8Z">
                    </path>
                    <path d="M12 8 7.26 5.15"></path>
                    <path d="m12 8 4.74-2.85"></path>
                    <path d="M12 13.5V8"></path>
                </svg>
            </div>
            <div>
                <h1 id="sidebar-title" class="text-slate-800 font-bold text-sm leading-tight">SIPANDU</h1>
                <p class="text-slate-500 text-[10px] tracking-wider">BPMP Provinsi Gorontalo</p>
            </div>
        </div>
    </div>

    <!-- Role Badge -->
    <div class="sidebar-role-section">
        <label class="sidebar-role-label">Peran Aktif</label>
        <div class="sidebar-role-badge">{{ $config['badgeText'] }}</div>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <div class="nav-group">
            @foreach ($config['navItems'] as $item)
                @if (isset($item['children']))
                    @php
                        $isParentActive = collect($item['children'])->contains(function ($child) {
                            return request()->routeIs($child['route'] ?? '');
                        });
                    @endphp
                    <div class="nav-item-parent">
                        <button class="nav-item-toggle {{ $isParentActive ? 'active' : '' }}"
                            onclick="this.nextElementSibling.classList.toggle('expanded'); this.classList.toggle('active');">
                            <span class="flex items-center gap-2.5"
                                style="display: flex; align-items: center; gap: 10px;">
                                <i class="{{ $item['icon'] }} nav-icon"></i>
                                <span>{{ $item['label'] }}</span>
                            </span>
                            <i class="fas fa-chevron-down nav-arrow"></i>
                        </button>
                        <div class="nav-submenu {{ $isParentActive ? 'expanded' : '' }}">
                            @foreach ($item['children'] as $child)
                                <a href="{{ $child['href'] }}"
                                    class="nav-subitem {{ request()->routeIs($child['route'] ?? '') ? 'active' : '' }}">
                                    {{ $child['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ $item['href'] }}"
                        class="nav-item {{ request()->routeIs($item['route'] ?? '') ? 'active' : '' }}">
                        <i class="{{ $item['icon'] }} nav-icon"></i>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endif
            @endforeach
        </div>
    </nav>

    <!-- Footer -->
    <!-- GANTI footer di sidebar Anda dengan ini -->
    <div class="sidebar-footer">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    data-lucide="user" class="lucide lucide-user w-4 h-4 text-blue-600">
                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <div>
                <p class="text-slate-800 text-xs font-semibold">{{ $user->name ?? 'Administrator' }}</p>
                <p class="text-slate-500 text-[10px]">{{ $config['badgeText'] }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout', [], false) }}" class="mt-4">
            @csrf
            <button type="submit"
                class="w-full flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100 logout-btn">
                <i data-lucide="log-out" class="w-4 h-4"></i>
                Keluar
            </button>
        </form>
    </div>
</aside>

<script>
    function toggleMobileSidebar() {
        const sidebar = document.getElementById('sidebarWrapper');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.toggle('mobile-open');
        overlay.classList.toggle('active');
    }
</script>

{{-- {{-- <div class="sidebar-footer"> --}}
{{-- <div class="sidebar-user">
      <div class="user-avatar">{{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}</div>
<div class="user-info">
  <div class="user-name">{{ $user->name ?? 'User' }}</div>
  <div class="user-role">{{ $config['badgeText'] }}</div>
</div>
</div>
<form method="POST" action="{{ route('logout', [], false) }}" style="margin: 0;">
  @csrf
  <button type="submit" class="logout-btn">
    <i class="fas fa-sign-out-alt"></i>
    <span>Keluar</span>
  </button>
</form>
</div> --}}
{{-- </aside> --}}
