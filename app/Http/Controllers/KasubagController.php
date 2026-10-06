<?php

namespace App\Http\Controllers;

use App\Jobs\SendFonnteNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\{
    PeminjamanGedung,
    PermintaanPersediaan,
    PeminjamanBarang,
    PengembalianBarang,
    PeminjamanKendaraan,
    PengembalianKendaraan,
    Persediaan,                   // ✅ Ditambahkan untuk akses master stok
    TransaksiKeluarPersediaan,    // ✅ Ditambahkan untuk mencatat riwayat keluar
    TransaksiKeluarAssetTetap,    // ✅ Monitoring transaksi keluar aset tetap
    AssetTetap,                   // ✅ Monitoring aset tetap
    DetailPermintaanPersediaan    // ✅ Detail permintaan
};
use App\Services\FonnteService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class KasubagController extends Controller
{
    public function dashboard()
    {
        // 1. Hitung Statistik Kendaraan (Tugas Persetujuan Utama Kasubag)
        $kendaraanTotal = PeminjamanKendaraan::count();
        $kendaraanPending = PeminjamanKendaraan::whereIn('status', ['pending', 'diteruskan_kasubag'])->count();
        $kendaraanSetuju = PeminjamanKendaraan::where('status', 'disetujui')->count();
        $kendaraanTolak = PeminjamanKendaraan::where('status', 'ditolak')->count();

        // 2. Hitung Statistik Barang & Persediaan (Hanya untuk overview sistem)
        $barangTotal = PeminjamanBarang::count();
        $barangPending = PeminjamanBarang::where('status', 'diteruskan_kasubag')->count();
        $barangSetuju = PeminjamanBarang::where('status', 'disetujui')->count();
        $barangTolak = PeminjamanBarang::where('status', 'ditolak')->count();

        $persediaanTotal = PermintaanPersediaan::count();
        $persediaanPending = PermintaanPersediaan::whereIn('status', ['pending', 'diproses'])->count();
        $persediaanSetuju = PermintaanPersediaan::whereIn('status', ['disetujui', 'disetujui_kasubag'])->count();
        $persediaanTolak = PermintaanPersediaan::where('status', 'ditolak')->count();

        $gedungTotal = PeminjamanGedung::count();
        $gedungPending = PeminjamanGedung::where('status', 'dalam_review')->count(); 

        // 3. Status Pending untuk Kasubag (Peminjaman Barang & Peminjaman Kendaraan)
        $totalPending = $barangPending + $kendaraanPending;
        $totalDisetujui = $barangSetuju + $kendaraanSetuju;
        $totalDitolak = $barangTolak + $kendaraanTolak;
        $totalPermintaan = $barangTotal + $kendaraanTotal;

        // 4. Statistik Monitoring Transaksi Keluar (Persediaan + Aset Tetap)
        $totalTrxPersediaan = TransaksiKeluarPersediaan::count();
        $totalNilaiPersediaan = (float) TransaksiKeluarPersediaan::sum('total');
        $totalTrxAset = TransaksiKeluarAssetTetap::count();
        $totalNilaiAset = (float) TransaksiKeluarAssetTetap::sum('nilai_perolehan');
        $totalTransaksiKeluar = $totalTrxPersediaan + $totalTrxAset;
        $totalNilaiKeluar = $totalNilaiPersediaan + $totalNilaiAset;

        // 5. Antrean Verifikasi Kasubag Terbaru (Peminjaman Barang & Kendaraan)
        $recentBarang = PeminjamanBarang::with('user')
            ->where('status', 'diteruskan_kasubag')
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($item) {
                return [
                    'tipe' => 'Barang',
                    'nama_item' => $item->nama_barang,
                    'nama_peminjam' => $item->user->name ?? 'Pegawai',
                    'tanggal' => $item->created_at,
                    'url' => route('kasubag.persetujuan-peminjaman-barang')
                ];
            });

        $recentKendaraan = PeminjamanKendaraan::with(['user']) 
            ->whereIn('status', ['pending', 'diteruskan_kasubag'])
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($item) {
                return [
                    'tipe' => 'Kendaraan',
                    'nama_item' => $item->merek ?? $item->nama_barang ?? 'Kendaraan Dinas',
                    'nama_peminjam' => $item->user->name ?? 'Pegawai',
                    'tanggal' => $item->created_at,
                    'url' => route('kasubag.persetujuan-peminjaman-kendaraan')
                ];
            });

        $recentPending = collect($recentBarang)->merge($recentKendaraan)->sortByDesc('tanggal')->take(5);

        // 6. Kirim data ke View
        return view('kasubag.dashbord', compact(
            'totalPending',
            'totalDisetujui',
            'totalDitolak',
            'totalPermintaan',
            'kendaraanTotal',
            'kendaraanPending',
            'barangTotal',
            'barangPending',
            'gedungTotal',
            'gedungPending',
            'persediaanTotal',
            'persediaanPending',
            'totalTransaksiKeluar',
            'totalNilaiKeluar',
            'totalTrxPersediaan',
            'totalTrxAset',
            'recentPending'
        ));
    }

    public function persetujuanPeminjamanGedung(Request $request)
    {
        $query = PeminjamanGedung::with(['user', 'reviewer'])
            ->whereIn('status', ['diteruskan_kasubag', 'disetujui_kasubag'])
            ->orderBy('diteruskan_ke_kasubag_date', 'desc');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
                    ->orWhere('instansi_lembaga', 'like', '%' . $request->search . '%');
            });
        }

        $peminjaman = $query->paginate(15);

        return view('kasubag.persetujuan_peminjaman_gedung', compact('peminjaman'));
    }

    public function approveByKasubag(Request $request, PeminjamanGedung $peminjaman)
    {
        $request->validate(['komentar' => 'nullable|string|max:1000']);

        // Cek apakah masih dalam review
        if ($peminjaman->status !== 'diteruskan_kasubag') {
            return response()->json([
                'success' => false,
                'message' => 'Peminjaman belum diteruskan ke Kasubag atau sudah diproses!'
            ], 400);
        }

        $peminjaman->update([
            'status' => 'disetujui_kasubag',
            'approved_by_kasubag_id' => auth()->id(),
            'approved_by_kasubag_date' => now(),
            'komentar' => $request->komentar
        ]);

        $namaGedung = $peminjaman->gedung->nama_gedung ?? ($peminjaman->nama_fasilitas ?? 'Fasilitas');

        $tglPinjam = \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d/m/Y');
        $tglKembali = \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d/m/Y');

        $jamMulai = $peminjaman->jam_mulai ?? '--:--';
        $jamSelesai = $peminjaman->jam_selesai ?? '--:--';

        // --- 1. NOTIFIKASI KE TAMU (DISETUJUI) ---
        if ($peminjaman->nomor_kontak) {
            $noHpTamu = preg_replace('/[^0-9]/', '', $peminjaman->nomor_kontak);

            $pesanTamu = "*Peminjaman Gedung DISETUJUI*\n\n";
            $pesanTamu .= "Halo {$peminjaman->nama_lengkap},\n";
            $pesanTamu .= "Pengajuan peminjaman fasilitas Anda telah disetujui oleh Kasubag:\n\n";
            $pesanTamu .= "🏫 *Fasilitas:* {$namaGedung}\n";
            $pesanTamu .= "📅 *Tanggal:* {$tglPinjam} s/d {$tglKembali}\n";
            $pesanTamu .= "⏰ *Waktu:* {$jamMulai} - {$jamSelesai} WITA\n\n"; 
            $pesanTamu .= "Silakan tunggu Surat Perjanjian yang akan disiapkan oleh Admin Sarpras. Terima kasih.";

            SendFonnteNotification::dispatch($noHpTamu, $pesanTamu);
        }

        // --- 2. NOTIFIKASI KE ADMIN SARPRAS (INFO DISETUJUI) ---
        $adminSarpras = \App\Models\User::where('role', 'admin_sarpras')->first();
        if ($adminSarpras && $adminSarpras->nomor_telepon) {
            $noHpAdmin = preg_replace('/[^0-9]/', '', $adminSarpras->nomor_telepon);

            $pesanAdmin = "*Info Persetujuan Kasubag (Gedung)*\n\n";
            $pesanAdmin .= "Halo Admin Sarpras,\n";
            $pesanAdmin .= "Kasubag telah *MENYETUJUI* peminjaman fasilitas dari Tamu:\n\n";
            $pesanAdmin .= "👤 *Pemohon:* {$peminjaman->nama_lengkap}\n";
            $pesanAdmin .= "🏫 *Fasilitas:* {$namaGedung}\n";
            $pesanAdmin .= "📅 *Tanggal:* {$tglPinjam} s/d {$tglKembali}\n";
            $pesanAdmin .= "⏰ *Waktu:* {$jamMulai} - {$jamSelesai} WITA\n\n"; 
            $pesanAdmin .= "Silakan login ke sistem untuk membuat/mengunggah Surat Perjanjian Peminjaman Gedung.";

            SendFonnteNotification::dispatch($noHpAdmin, $pesanAdmin);
        }

        return response()->json([
            'success' => true,
            'message' => 'Peminjaman berhasil disetujui!'
        ]);
    }

    public function rejectByKasubag(Request $request, PeminjamanGedung $peminjaman)
    {
        $request->validate(['komentar' => 'required|string|max:1000']);

        if ($peminjaman->status !== 'diteruskan_kasubag') {
            return response()->json([
                'success' => false,
                'message' => 'Peminjaman ditolak Kasubag!'
            ], 400);
        }

        $peminjaman->update([
            'status' => 'ditolak',
            'approved_by_kasubag_id' => auth()->id(),
            'approved_by_kasubag_date' => now(),
            'komentar' => $request->komentar
        ]);

        $namaGedung = $peminjaman->gedung->nama_gedung ?? ($peminjaman->nama_fasilitas ?? 'Fasilitas');

        // --- 1. NOTIFIKASI KE TAMU (DITOLAK) ---
        if ($peminjaman->nomor_kontak) {
            $noHpTamu = preg_replace('/[^0-9]/', '', $peminjaman->nomor_kontak);

            $pesanTamu = "*Peminjaman Gedung DITOLAK*\n\n";
            $pesanTamu .= "Halo {$peminjaman->nama_lengkap},\n";
            $pesanTamu .= "Maaf, pengajuan peminjaman fasilitas Anda ditolak oleh Kasubag:\n\n";
            $pesanTamu .= "🏫 *Fasilitas:* {$namaGedung}\n";
            $pesanTamu .= "📅 *Tanggal:* {$peminjaman->tanggal_pinjam} s/d {$peminjaman->tanggal_kembali}\n";
            $pesanTamu .= "💬 *Catatan Kasubag:* " . $request->komentar . "\n\n";
            $pesanTamu .= "Silakan hubungi Admin Sarpras untuk informasi lebih lanjut.";

            SendFonnteNotification::dispatch($noHpTamu, $pesanTamu);
        }

        // --- 2. NOTIFIKASI KE ADMIN SARPRAS (INFO DITOLAK) ---
        $adminSarpras = \App\Models\User::where('role', 'admin_sarpras')->first();
        if ($adminSarpras && $adminSarpras->nomor_telepon) {
            $noHpAdmin = preg_replace('/[^0-9]/', '', $adminSarpras->nomor_telepon);

            $pesanAdmin = "*Info Penolakan Kasubag (Gedung)*\n\n";
            $pesanAdmin .= "Halo Admin Sarpras,\n";
            $pesanAdmin .= "Kasubag telah *MENOLAK* peminjaman fasilitas dari Tamu:\n\n";
            $pesanAdmin .= "👤 *Pemohon:* {$peminjaman->nama_lengkap}\n";
            $pesanAdmin .= "🏫 *Fasilitas:* {$namaGedung}\n";
            $pesanAdmin .= "💬 *Catatan Kasubag:* " . $request->komentar;

            SendFonnteNotification::dispatch($noHpAdmin, $pesanAdmin);
        }

        return response()->json([
            'success' => true,
            'message' => 'Peminjaman berhasil ditolak!'
        ]);
    }

    public function downloadSurat(PeminjamanGedung $peminjaman)
    {
        if (!$peminjaman->surat_path || !Storage::disk('public')->exists($peminjaman->surat_path)) {
            return back()->with('error', 'Surat peminjaman tidak ditemukan!');
        }

        $filePath = storage_path('app/public/' . $peminjaman->surat_path);
        $originalName = pathinfo($peminjaman->surat_path, PATHINFO_BASENAME);
        $downloadName = "Surat_Peminjaman_{$peminjaman->nama_lengkap}_{$peminjaman->id}." .
            pathinfo($originalName, PATHINFO_EXTENSION);

        return response()->download($filePath, $downloadName);
    }

    public function show(PeminjamanGedung $peminjaman)
    {
        $peminjaman->load(['user', 'gedung', 'reviewer', 'approver']);

        return response()->json([
            'id' => $peminjaman->id,
            'gedung' => $peminjaman->gedung ? [
                'nama_gedung' => $peminjaman->gedung->nama_gedung,
                'lokasi' => $peminjaman->gedung->lokasi,
            ] : null,
            'nama_lengkap' => $peminjaman->nama_lengkap,
            'nip_nik' => $peminjaman->nip_nik,
            'instansi_lembaga' => $peminjaman->instansi_lembaga,
            'kabupaten_kota' => $peminjaman->kabupaten_kota,
            'fasilitas' => $peminjaman->fasilitas,
            'nama_fasilitas' => $peminjaman->nama_fasilitas,
            'tarif_per_hari' => $peminjaman->tarif_per_hari,
            'tanggal_pinjam' => $peminjaman->tanggal_pinjam,
            'tanggal_kembali' => $peminjaman->tanggal_kembali,
            'jam_mulai' => $peminjaman->jam_mulai,
            'jam_selesai' => $peminjaman->jam_selesai,
            'lama_peminjaman_hari' => $peminjaman->lama_peminjaman_hari,
            'total_pembayaran' => $peminjaman->total_pembayaran,
            'tujuan_penggunaan' => $peminjaman->tujuan_penggunaan,
            'nomor_kontak' => $peminjaman->nomor_kontak,
            'status' => $peminjaman->status,
            'status_text' => $peminjaman->status_text,
            'komentar' => $peminjaman->komentar,
            'reviewer' => $peminjaman->reviewer ? ['name' => $peminjaman->reviewer->name] : null,
            'diteruskan_ke_kasubag_date' => $peminjaman->diteruskan_ke_kasubag_date,
            'surat_url' => $peminjaman->surat_url,
            'created_at' => $peminjaman->created_at->toISOString(),
        ]);
    }

    // ====================================================================
    // PEMINJAMAN BARANG (ASET TETAP)
    // ====================================================================

    public function persetujuanPeminjamanBarang(Request $request)
    {
        $stats = [
            'menunggu'  => PeminjamanBarang::where('status', 'diteruskan_kasubag')->count(),
            'disetujui' => PeminjamanBarang::where('status', 'disetujui')->count(),
            'total'     => PeminjamanBarang::whereIn('status', ['diteruskan_kasubag', 'disetujui', 'ditolak'])->count()
        ];

        $query = PeminjamanBarang::with('user')
            ->whereIn('status', ['diteruskan_kasubag', 'disetujui', 'ditolak'])
            ->orderByRaw("CASE WHEN status = 'diteruskan_kasubag' THEN 0 ELSE 1 END")
            ->orderBy('diteruskan_ke_kasubag_date', 'desc');

        $peminjaman = $query->paginate(15);

        return view('kasubag.persetujuan_peminjaman_barang', compact('peminjaman', 'stats'));
    }

    public function detailPeminjamanBarang($id)
    {
        $peminjaman = PeminjamanBarang::with('user')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $peminjaman
        ]);
    }

    public function approvePeminjamanBarang(Request $request, $id)
    {
        $peminjaman = PeminjamanBarang::findOrFail($id);

        if ($peminjaman->status !== 'diteruskan_kasubag') {
            return back()->with('error', 'Data belum diteruskan ke Kasubag atau sudah diproses sebelumnya!');
        }
        
        if ($request->action == 'setuju') {
            $peminjaman->status = 'disetujui';
            $peminjaman->approved_by_kasubag_id = auth()->id();
            $peminjaman->approved_by_kasubag_date = now();
            $peminjaman->save(); 

            $pesan = 'Peminjaman berhasil disetujui.';
        } elseif ($request->action == 'tolak') {
            $peminjaman->status = 'ditolak';
            $peminjaman->approved_by_kasubag_id = auth()->id();
            $peminjaman->approved_by_kasubag_date = now();
            $peminjaman->komentar = $request->komentar;
            $peminjaman->save(); 

            $pesan = 'Peminjaman berhasil ditolak.';
        }

        // PENGIRIMAN NOTIFIKASI WA (DIBUNGKUS TRY-CATCH AGAR TIDAK CRASH 500 JIKA WA TIMEOUT/GAGAL)
        try {
            $pegawai = $peminjaman->user;
            // 1. NOTIFIKASI KE PEGAWAI
            if ($pegawai && $pegawai->nomor_telepon) {
                $noHpPegawai = preg_replace('/[^0-9]/', '', $pegawai->nomor_telepon);

                if ($request->action == 'setuju') {
                    $pesanPegawai = "*Peminjaman Barang DISETUJUI*\n\n";
                    $pesanPegawai .= "Halo {$pegawai->name},\n";
                    $pesanPegawai .= "Permintaan peminjaman barang Anda telah disetujui oleh Kasubag:\n\n";
                    $pesanPegawai .= "📦 *Barang:* {$peminjaman->nama_barang}\n";
                    $pesanPegawai .= "🔢 *Jumlah:* {$peminjaman->jumlah}\n";
                    $pesanPegawai .= "📅 *Tgl Pinjam:* {$peminjaman->tanggal_peminjaman}\n\n";
                    $pesanPegawai .= "Admin akan segera membuatkan Surat BAST. Silakan cek sistem secara berkala.";
                } else {
                    $pesanPegawai = "*Peminjaman Barang DITOLAK*\n\n";
                    $pesanPegawai .= "Halo {$pegawai->name},\n";
                    $pesanPegawai .= "Maaf, permintaan peminjaman barang Anda ditolak oleh Kasubag:\n\n";
                    $pesanPegawai .= "📦 *Barang:* {$peminjaman->nama_barang}\n";
                    $pesanPegawai .= "💬 *Catatan Kasubag:* " . ($request->komentar ?? '-') . "\n\n";
                    $pesanPegawai .= "Silakan hubungi Admin untuk informasi lebih lanjut.";
                }

                SendFonnteNotification::dispatch($noHpPegawai, $pesanPegawai);
            }

            // 2. NOTIFIKASI KE ADMIN ASET TETAP
            $adminAset = \App\Models\User::whereIn('role', ['admin_aset_tetap', 'adminasettetap'])->first();
            if ($adminAset && $adminAset->nomor_telepon) {
                $noHpAdmin = preg_replace('/[^0-9]/', '', $adminAset->nomor_telepon);
                $namaPegawai = $pegawai ? $pegawai->name : 'Pegawai';

                if ($request->action == 'setuju') {
                    $pesanAdmin = "*Info Persetujuan Kasubag (Barang)*\n\n";
                    $pesanAdmin .= "Halo Admin Aset Tetap,\n";
                    $pesanAdmin .= "Kasubag telah *MENYETUJUI* peminjaman barang dari {$namaPegawai}:\n\n";
                    $pesanAdmin .= "📦 *Barang:* {$peminjaman->nama_barang}\n";
                    $pesanAdmin .= "Silakan login ke sistem untuk men-generate Surat BAST.";
                } else {
                    $pesanAdmin = "*Info Penolakan Kasubag (Barang)*\n\n";
                    $pesanAdmin .= "Halo Admin Aset Tetap,\n";
                    $pesanAdmin .= "Kasubag telah *MENYETUJUI* peminjaman barang dari {$namaPegawai}:\n\n";
                    $pesanAdmin .= "📦 *Barang:* {$peminjaman->nama_barang}\n";
                    $pesanAdmin .= "💬 *Catatan Kasubag:* " . ($request->komentar ?? '-');
                }

                SendFonnteNotification::dispatch($noHpAdmin, $pesanAdmin);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Gagal mengirim notifikasi WA persetujuan barang: ' . $e->getMessage());
        }

        return back()->with('success', $pesan);
    }

    // ====================================================================
    // PEMINJAMAN KENDARAAN
    // ====================================================================

    public function persetujuanPeminjamanKendaraan()
    {
        $peminjaman = PeminjamanKendaraan::with('user')
            ->whereIn('status', ['pending', 'diteruskan_kasubag', 'disetujui', 'ditolak'])
            ->orderByRaw("CASE WHEN status IN ('pending', 'diteruskan_kasubag') THEN 1 WHEN status = 'disetujui' THEN 2 WHEN status = 'ditolak' THEN 3 ELSE 4 END")
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'menunggu' => PeminjamanKendaraan::whereIn('status', ['pending', 'diteruskan_kasubag'])->count(),
            'disetujui' => PeminjamanKendaraan::where('status', 'disetujui')->count(),
            'total' => $peminjaman->count(),
        ];

        return view('kasubag.persetujuan_peminjaman_kendaraan', compact('peminjaman', 'stats'));
    }

    public function approveKendaraan(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:setuju,tolak'
        ]);

        $peminjaman = PeminjamanKendaraan::findOrFail($id);

        if (!in_array($peminjaman->status, ['pending', 'diteruskan_kasubag'])) {
            return back()->with('error', 'Peminjaman kendaraan belum diteruskan ke Kasubag atau sudah diproses!');
        }

        if ($request->action === 'setuju') {
            $peminjaman->status = 'disetujui';
            $pesan = 'Peminjaman kendaraan berhasil disetujui.';
        } else {
            $peminjaman->status = 'ditolak';
            $pesan = 'Peminjaman kendaraan telah ditolak.';
        }

        $peminjaman->approved_by_kasubag_id = auth()->id();
        $peminjaman->approved_by_kasubag_date = now();
        $peminjaman->save();

        // PENGIRIMAN NOTIFIKASI WA (DIBUNGKUS TRY-CATCH AGAR TIDAK CRASH 500 JIKA WA TIMEOUT/GAGAL)
        try {
            $pegawai = $peminjaman->user;
            // 1. NOTIFIKASI KE PEGAWAI
            if ($pegawai && $pegawai->nomor_telepon) {
                $noHpPegawai = preg_replace('/[^0-9]/', '', $pegawai->nomor_telepon);

                if ($request->action == 'setuju') {
                    $pesanPegawai = "*Peminjaman Kendaraan DISETUJUI*\n\n";
                    $pesanPegawai .= "Halo {$pegawai->name},\n";
                    $pesanPegawai .= "Permintaan peminjaman kendaraan dinas Anda telah disetujui oleh Kasubag:\n\n";
                    $pesanPegawai .= "🚗 *Kendaraan:* {$peminjaman->nama_barang}\n";
                    $pesanPegawai .= "📅 *Tgl Pinjam:* {$peminjaman->tanggal_peminjaman}\n\n";
                    $pesanPegawai .= "Admin akan segera membuatkan Surat BAST. Silakan cek sistem secara berkala.";
                } else {
                    $pesanPegawai = "*Peminjaman Kendaraan DITOLAK*\n\n";
                    $pesanPegawai .= "Halo {$pegawai->name},\n";
                    $pesanPegawai .= "Maaf, permintaan peminjaman kendaraan dinas Anda ditolak oleh Kasubag:\n\n";
                    $pesanPegawai .= "🚗 *Kendaraan:* {$peminjaman->nama_barang}\n";
                    $pesanPegawai .= "💬 *Catatan Kasubag:* " . ($request->komentar ?? '-') . "\n\n";
                    $pesanPegawai .= "Silakan hubungi Admin untuk informasi lebih lanjut.";
                }

                SendFonnteNotification::dispatch($noHpPegawai, $pesanPegawai);
            }

            // 2. NOTIFIKASI KE ADMIN ASET TETAP
            $adminAset = \App\Models\User::whereIn('role', ['admin_aset_tetap', 'adminasettetap'])->first();
            if ($adminAset && $adminAset->nomor_telepon) {
                $noHpAdmin = preg_replace('/[^0-9]/', '', $adminAset->nomor_telepon);
                $namaPegawai = $pegawai ? $pegawai->name : 'Pegawai';

                if ($request->action == 'setuju') {
                    $pesanAdmin = "*Info Persetujuan Kasubag (Kendaraan)*\n\n";
                    $pesanAdmin .= "Halo Admin Aset Tetap,\n";
                    $pesanAdmin .= "Kasubag telah *MENYETUJUI* peminjaman kendaraan dinas dari {$namaPegawai}:\n\n";
                    $pesanAdmin .= "🚗 *Kendaraan:* {$peminjaman->nama_barang}\n";
                    $pesanAdmin .= "Silakan login ke sistem untuk men-generate Surat BAST Kendaraan.";
                } else {
                    $pesanAdmin = "*Info Penolakan Kasubag (Kendaraan)*\n\n";
                    $pesanAdmin .= "Halo Admin Aset Tetap,\n";
                    $pesanAdmin .= "Kasubag telah *MENOLAK* peminjaman kendaraan dinas dari {$namaPegawai}:\n\n";
                    $pesanAdmin .= "🚗 *Kendaraan:* {$peminjaman->nama_barang}\n";
                    $pesanAdmin .= "💬 *Catatan Kasubag:* " . ($request->komentar ?? '-');
                }

                SendFonnteNotification::dispatch($noHpAdmin, $pesanAdmin);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Gagal mengirim notifikasi WA persetujuan kendaraan: ' . $e->getMessage());
        }

        return back()->with('success', $pesan);
    }

    public function showJsonKendaraan($id)
    {
        $data = PeminjamanKendaraan::with('user')->find($id);

        if (!$data) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    // ====================================================================
    // PERMINTAAN PERSEDIAAN
    // ====================================================================

    public function persetujuanPermintaanPersediaan()
    {
        $stats = [
            'menunggu' => PermintaanPersediaan::where('status', 'diteruskan_kasubag')->count(),
            'disetujui' => PermintaanPersediaan::whereIn('status', ['disetujui', 'disetujui_kasubag'])->count(),
            'total' => PermintaanPersediaan::whereIn('status', ['disetujui', 'disetujui_kasubag', 'ditolak', 'ditolak_kasubag'])->count()
        ];

        $permintaan = PermintaanPersediaan::with(['user', 'persediaan'])
            ->whereIn('status', ['diteruskan_kasubag', 'disetujui', 'disetujui_kasubag', 'ditolak', 'ditolak_kasubag'])
            ->orderByRaw("CASE WHEN status = 'diteruskan_kasubag' THEN 1 ELSE 2 END")
            ->latest()
            ->get();

        return view('kasubag.persetujuan_permintaan_persediaan', compact('permintaan', 'stats'));
    }

    // 🔥 METHOD YANG DIPERBAIKI UNTUK OTOMATISASI STOK DAN NOTIFIKASI QTY 🔥
    public function approvePermintaan(Request $request, PermintaanPersediaan $permintaan)
    {
        $request->validate([
            'action' => 'required|in:setuju,tolak',
            'tanggal_penerimaan' => 'required_if:action,setuju|nullable|date',
        ], [
            'tanggal_penerimaan.required_if' => 'Tanggal penerimaan wajib dipilih saat permintaan disetujui.',
            'tanggal_penerimaan.date' => 'Tanggal penerimaan tidak valid.',
        ]);

        if (!in_array($permintaan->status, ['diteruskan_kasubag', 'disetujui_kasubag'])) {
            return back()->with('error', 'Permintaan tidak valid untuk diproses!');
        }

        if ($permintaan->status !== 'diteruskan_kasubag') {
            return back()->with('error', 'Permintaan belum diteruskan ke Kasubag atau tidak valid untuk diproses!');
        }

        if ($request->action === 'setuju') {
            $permintaan->loadMissing(['items.persediaan', 'persediaan']);

            if ($permintaan->items && $permintaan->items->count() > 0) {
                // ==========================================
                // MULTI-ITEM: VALIDASI STOK TIAP ITEM
                // ==========================================
                foreach ($permintaan->items as $detail) {
                    $pers = $detail->persediaan ?? Persediaan::find($detail->persediaan_id);
                    if (!$pers || $pers->jumlah < $detail->jumlah_diminta) {
                        $sisa = $pers->jumlah ?? 0;
                        $nama = $pers->nama_barang ?? $detail->nama_barang;
                        return back()->with('error', "Gagal! Stok fisik '{$nama}' tidak mencukupi (Sisa stok: {$sisa}).");
                    }
                }

                $permintaan->update([
                    'status' => 'disetujui',
                    'approved_by_kasubag_id' => Auth::id(),
                    'tanggal_penerimaan' => $request->tanggal_penerimaan,
                ]);

                // POTONG STOK & CATAT TRANSAKSI KELUAR UNTUK TIAP ITEM
                foreach ($permintaan->items as $detail) {
                    $pers = $detail->persediaan ?? Persediaan::find($detail->persediaan_id);
                    if ($pers) {
                        $pers->decrement('jumlah', $detail->jumlah_diminta);

                        TransaksiKeluarPersediaan::create([
                            'persediaan_id' => $pers->id,
                            'tanggal_input' => $request->tanggal_penerimaan,
                            'kode_kategori' => $pers->kode_kategori,
                            'kategori'      => $pers->kategori,
                            'kode_barang'   => $pers->kode_barang,
                            'nama_barang'   => $pers->nama_barang,
                            'jumlah_keluar' => $detail->jumlah_diminta,
                            'harga'         => $pers->harga_satuan,
                            'total'         => $pers->harga_satuan * $detail->jumlah_diminta,
                            'satuan'        => $pers->satuan,
                            'user_id'       => Auth::id(),
                            'keterangan'    => 'Disetujui otomatis dari Permintaan Pegawai: ' . ($permintaan->user->name ?? 'Pegawai')
                        ]);
                    }
                }
            } else {
                // ==========================================
                // SINGLE-ITEM FALLBACK
                // ==========================================
                $persediaan = Persediaan::find($permintaan->persediaan_id);
                
                if (!$persediaan || $persediaan->jumlah < $permintaan->jumlah_disetujui) {
                    return back()->with('error', 'Gagal! Stok fisik persediaan saat ini tidak mencukupi untuk memenuhi jumlah yang disetujui (Sisa stok: ' . ($persediaan->jumlah ?? 0) . ' unit).');
                }

                $permintaan->update([
                    'status' => 'disetujui',
                    'approved_by_kasubag_id' => Auth::id(),
                    'tanggal_penerimaan' => $request->tanggal_penerimaan,
                ]);

                if ($persediaan) {
                    $persediaan->decrement('jumlah', $permintaan->jumlah_disetujui);

                    TransaksiKeluarPersediaan::create([
                        'persediaan_id' => $persediaan->id,
                        'tanggal_input' => $request->tanggal_penerimaan,
                        'kode_kategori' => $persediaan->kode_kategori,
                        'kategori'      => $persediaan->kategori,
                        'kode_barang'   => $persediaan->kode_barang,
                        'nama_barang'   => $persediaan->nama_barang,
                        'jumlah_keluar' => $permintaan->jumlah_disetujui,
                        'harga'         => $persediaan->harga_satuan,
                        'total'         => $persediaan->harga_satuan * $permintaan->jumlah_disetujui,
                        'satuan'        => $persediaan->satuan,
                        'user_id'       => Auth::id(),
                        'keterangan'    => 'Disetujui otomatis dari Permintaan Pegawai: ' . ($permintaan->user->name ?? 'Pegawai')
                    ]);
                }
            }

        } else {
            $permintaan->update([
                'status' => 'ditolak',
                'approved_by_kasubag_id' => Auth::id(),
            ]);
        }

        // PENGIRIMAN NOTIFIKASI WA (DIBUNGKUS TRY-CATCH AGAR TIDAK CRASH 500 JIKA WA TIMEOUT/GAGAL)
        try {
            $pegawai = $permintaan->user;
            if ($pegawai && $pegawai->nomor_telepon) {
                $noHpPegawai = preg_replace('/[^0-9]/', '', $pegawai->nomor_telepon);

                if ($request->action === 'setuju') {
                    // ✅ PERBAIKAN NOTIFIKASI PEGAWAI: Menampilkan Komparasi Diminta vs Disetujui
                    $pesanPegawai = "*Permintaan Persediaan DISETUJUI*\n\n";
                    $pesanPegawai .= "Halo {$pegawai->name},\n";
                    $pesanPegawai .= "Permintaan barang persediaan Anda telah disetujui Kasubag:\n\n";
                    $pesanPegawai .= "📦 *Barang:* {$permintaan->nama_barang}\n";
                    $pesanPegawai .= "📝 *Diminta:* {$permintaan->jumlah_diminta} Unit\n";
                    $pesanPegawai .= "✅ *Disetujui:* {$permintaan->jumlah_disetujui} Unit\n";
                    $pesanPegawai .= "Silakan hubungi Admin Persediaan untuk pengambilan barang atau unduh Surat BAST jika sudah diunggah.";
                } else {
                    $pesanPegawai = "*Permintaan Persediaan DITOLAK*\n\n";
                    $pesanPegawai .= "Halo {$pegawai->name},\n";
                    $pesanPegawai .= "Maaf, permintaan barang persediaan Anda ditolak oleh Kasubag:\n\n";
                    $pesanPegawai .= "📦 *Barang:* {$permintaan->nama_barang}\n";
                    $pesanPegawai .= "📝 *Diminta:* {$permintaan->jumlah_diminta} Unit\n";
                    $pesanPegawai .= "💬 *Catatan Kasubag:* " . ($request->komentar ?? '-') . "\n\n";
                    $pesanPegawai .= "Silakan hubungi Admin Persediaan jika ada pertanyaan lebih lanjut.";
                }
                SendFonnteNotification::dispatch($noHpPegawai, $pesanPegawai);
            }

            // 2. NOTIFIKASI KE ADMIN PERSEDIAAN
            $adminPersediaan = \App\Models\User::where('role', 'admin_persediaan')->first();
            if ($adminPersediaan && $adminPersediaan->nomor_telepon) {
                $noHpAdmin = preg_replace('/[^0-9]/', '', $adminPersediaan->nomor_telepon);
                $namaPegawai = $pegawai ? $pegawai->name : 'Pegawai';

                if ($request->action === 'setuju') {
                    // ✅ PERBAIKAN NOTIFIKASI ADMIN: Menampilkan instruksi spesifik QTY Disetujui
                    $pesanAdmin = "*Info Persetujuan Kasubag (Persediaan)*\n\n";
                    $pesanAdmin .= "Halo Admin Persediaan,\n";
                    $pesanAdmin .= "Kasubag telah *MENYETUJUI* permintaan barang persediaan dari {$namaPegawai}:\n\n";
                    $pesanAdmin .= "📦 *Barang:* {$permintaan->nama_barang}\n";
                    $pesanAdmin .= "📝 *Diminta:* {$permintaan->jumlah_diminta} Unit\n";
                    $pesanAdmin .= "✅ *Jumlah Dikeluarkan:* {$permintaan->jumlah_disetujui} Unit\n\n";
                    $pesanAdmin .= "Sistem telah memotong stok secara otomatis. Silakan siapkan barang fisik sejumlah tersebut dan unggah Surat BAST ke dalam sistem.";
                } else {
                    $pesanAdmin = "*Info Penolakan Kasubag (Persediaan)*\n\n";
                    $pesanAdmin .= "Halo Admin Persediaan,\n";
                    $pesanAdmin .= "Kasubag telah *MENOLAK* permintaan persediaan dari {$namaPegawai}:\n\n";
                    $pesanAdmin .= "📦 *Barang:* {$permintaan->nama_barang}\n";
                    $pesanAdmin .= "💬 *Catatan Kasubag:* " . ($request->komentar ?? '-');
                }

                SendFonnteNotification::dispatch($noHpAdmin, $pesanAdmin);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Gagal mengirim notifikasi WA persetujuan persediaan: ' . $e->getMessage());
        }

        return redirect()->route('kasubag.persetujuan-permintaan-persediaan')
            ->with('success', 'Permintaan persediaan berhasil diproses!');
    }

    public function showPermintaan($id)
    {
        $permintaan = PermintaanPersediaan::with(['persediaan', 'user', 'reviewedBy', 'approvedByKasubag'])
            ->findOrFail($id);

        return view('kasubag.detail_permintaan_persediaan', compact('permintaan'));
    }

    public function pengaturanAkun()
    {
        return view('kasubag.pengaturan_akun');
    }

    // ====================================================================
    // MONITORING KASUBAG (PERMINTAAN PERSEDIAAN, ASET TETAP, KENDARAAN)
    // ====================================================================

    /**
     * 1. MONITORING PERMINTAAN PERSEDIAAN
     */
    public function monitoringPersediaan(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = PermintaanPersediaan::with(['user', 'items.persediaan', 'persediaan', 'reviewedBy']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_barang', 'like', "%{$search}%")
                  ->orWhere('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('tujuan_penggunaan', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($status && $status !== 'semua') {
            if ($status === 'disetujui') {
                $query->whereIn('status', ['disetujui', 'disetujui_kasubag']);
            } elseif ($status === 'pending') {
                $query->whereIn('status', ['pending', 'dalam_review', 'diproses', 'diteruskan_kasubag']);
            } elseif ($status === 'ditolak') {
                $query->whereIn('status', ['ditolak', 'ditolak_kasubag']);
            } else {
                $query->where('status', $status);
            }
        }

        if ($startDate) {
            $query->whereDate('tanggal_permintaan', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('tanggal_permintaan', '<=', $endDate);
        }

        $stats = [
            'total'     => PermintaanPersediaan::count(),
            'disetujui' => PermintaanPersediaan::whereIn('status', ['disetujui', 'disetujui_kasubag'])->count(),
            'pending'   => PermintaanPersediaan::whereIn('status', ['pending', 'dalam_review', 'diproses', 'diteruskan_kasubag'])->count(),
            'ditolak'   => PermintaanPersediaan::whereIn('status', ['ditolak', 'ditolak_kasubag'])->count(),
        ];

        $permintaan = $query->orderBy('tanggal_permintaan', 'desc')->paginate(15)->appends($request->query());

        return view('kasubag.monitoring_persediaan', compact('permintaan', 'stats', 'search', 'status', 'startDate', 'endDate'));
    }

    public function detailMonitoringPersediaanJson($id)
    {
        $permintaan = PermintaanPersediaan::with(['user', 'items.persediaan', 'persediaan', 'reviewedBy'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $permintaan
        ]);
    }

    /**
     * 2. MONITORING BARANG / ASET TETAP
     */
    public function monitoringAsetTetap(Request $request)
    {
        $search = $request->get('search');
        $kategori = $request->get('kategori');
        $kondisi = $request->get('kondisi');
        $status = $request->get('status');

        $query = AssetTetap::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_barang', 'like', "%{$search}%")
                  ->orWhere('nup', 'like', "%{$search}%")
                  ->orWhere('nama_barang', 'like', "%{$search}%")
                  ->orWhere('merek', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        if ($kondisi) {
            $query->where('kondisi', $kondisi);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $stats = [
            'total'     => AssetTetap::count(),
            'tersedia'  => AssetTetap::where('status', 'Tersedia')->count(),
            'dipinjam'  => AssetTetap::where('status', 'Dipinjam')->count(),
            'keluar_rusak' => AssetTetap::whereIn('status', ['Keluar', 'Rusak'])->count(),
            'peminjamanTotal' => PeminjamanBarang::count(),
            'peminjamanDisetujui' => PeminjamanBarang::where('status', 'disetujui')->count(),
        ];

        $kategoriList = AssetTetap::select('kategori')->whereNotNull('kategori')->where('kategori', '!=', '')->distinct()->pluck('kategori');

        $asetTetap = $query->orderBy('kode_barang', 'asc')->paginate(15)->appends($request->query());

        // Data Peminjaman Barang untuk monitoring & Berita Acara (BAST)
        $tab = $request->get('tab', 'master');
        $queryPinjam = PeminjamanBarang::with('user');
        if ($search) {
            $queryPinjam->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_barang', 'like', "%{$search}%")
                  ->orWhere('nup', 'like', "%{$search}%")
                  ->orWhere('merek', 'like', "%{$search}%")
                  ->orWhere('deskripsi_peruntukan', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%");
                  });
            });
        }
        $peminjamanBarang = $queryPinjam->orderBy('created_at', 'desc')->paginate(15, ['*'], 'peminjaman_page')->appends($request->query());

        return view('kasubag.monitoring_aset_tetap', compact('asetTetap', 'peminjamanBarang', 'tab', 'stats', 'kategoriList', 'search', 'kategori', 'kondisi', 'status'));
    }

    /**
     * 3. MONITORING KENDARAAN
     */
    public function monitoringKendaraan(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Master Unit Kendaraan (Aset Tetap kategori kendaraan/angkutan)
        $unitKendaraan = AssetTetap::where(function ($q) {
            $q->where('kategori', 'like', '%kendaraan%')
              ->orWhere('kategori', 'like', '%angkutan%')
              ->orWhere('nama_barang', 'like', '%mobil%')
              ->orWhere('nama_barang', 'like', '%motor%')
              ->orWhere('nama_barang', 'like', '%innova%')
              ->orWhere('nama_barang', 'like', '%avanza%')
              ->orWhere('nama_barang', 'like', '%hilux%')
              ->orWhere('nama_barang', 'like', '%kendaraan%');
        })->get();

        // Riwayat / Pengajuan Peminjaman Kendaraan
        $query = PeminjamanKendaraan::with(['user', 'approvedByKasubag']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_barang', 'like', "%{$search}%")
                  ->orWhere('merek', 'like', "%{$search}%")
                  ->orWhere('deskripsi_peruntukan', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($status && $status !== 'semua') {
            $query->where('status', $status);
        }

        if ($startDate) {
            $query->whereDate('tanggal_peminjaman', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('tanggal_peminjaman', '<=', $endDate);
        }

        $stats = [
            'totalUnit'      => $unitKendaraan->count() > 0 ? $unitKendaraan->count() : PeminjamanKendaraan::distinct('nama_barang')->count(),
            'sedangDipinjam' => PeminjamanKendaraan::where('status', 'disetujui')->count(),
            'menunggu'       => PeminjamanKendaraan::whereIn('status', ['pending', 'diteruskan_kasubag'])->count(),
            'selesai'        => PeminjamanKendaraan::where('status', 'dikembalikan')->count(),
        ];

        // Peminjaman yang sedang aktif
        $peminjamanAktif = PeminjamanKendaraan::with('user')
            ->where('status', 'disetujui')
            ->orderBy('tanggal_pengembalian', 'asc')
            ->get();

        $riwayatPeminjaman = $query->orderBy('created_at', 'desc')->paginate(15)->appends($request->query());

        return view('kasubag.monitoring_kendaraan', compact(
            'unitKendaraan',
            'peminjamanAktif',
            'riwayatPeminjaman',
            'stats',
            'search',
            'status',
            'startDate',
            'endDate'
        ));
    }

    /**
     * MONITORING TRANSAKSI KELUAR UNTUK KASUBAG
     */
    public function transaksiKeluar(Request $request)
    {
        $sumber = $request->get('sumber', 'persediaan'); // 'persediaan' atau 'aset_tetap'
        $search = $request->get('search');
        $tanggalInput = $request->get('tanggal_input');
        $kategori = $request->get('kategori');

        // Statistik Keseluruhan Transaksi Keluar
        $totalTrxPersediaan = TransaksiKeluarPersediaan::count();
        $totalNilaiPersediaan = (float) TransaksiKeluarPersediaan::sum('total');
        $totalItemPersediaan = (int) TransaksiKeluarPersediaan::sum('jumlah_keluar');

        $totalTrxAset = TransaksiKeluarAssetTetap::count();
        $totalNilaiAset = (float) TransaksiKeluarAssetTetap::sum('nilai_perolehan');
        $totalItemAset = $totalTrxAset;

        $totalSemuaTrx = $totalTrxPersediaan + $totalTrxAset;
        $totalSemuaNilai = $totalNilaiPersediaan + $totalNilaiAset;

        if ($sumber === 'aset_tetap') {
            $query = TransaksiKeluarAssetTetap::query();

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode_barang', 'like', "%{$search}%")
                        ->orWhere('nama_barang', 'like', "%{$search}%")
                        ->orWhere('nup', 'like', "%{$search}%")
                        ->orWhere('merek', 'like', "%{$search}%")
                        ->orWhere('nomor_sk', 'like', "%{$search}%")
                        ->orWhere('lokasi', 'like', "%{$search}%");
                });
            }

            if ($tanggalInput) {
                $query->whereDate('tanggal_input', $tanggalInput);
            }

            if ($kategori) {
                $query->where('merek', $kategori);
            }

            $currentTotalTrx = (clone $query)->count();
            $currentTotalNilai = (float) (clone $query)->sum('nilai_perolehan');
            $currentTotalItem = $currentTotalTrx;

            $listData = $query->orderBy('tanggal_input', 'desc')->paginate(15)->appends($request->query());
            $kategoriList = TransaksiKeluarAssetTetap::select('merek as kategori')->whereNotNull('merek')->where('merek', '!=', '')->distinct()->pluck('kategori');
        } else {
            // Default: Persediaan
            $query = TransaksiKeluarPersediaan::query();

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode_barang', 'like', "%{$search}%")
                        ->orWhere('nama_barang', 'like', "%{$search}%")
                        ->orWhere('kategori', 'like', "%{$search}%")
                        ->orWhere('kode_kategori', 'like', "%{$search}%");
                });
            }

            if ($tanggalInput) {
                $query->whereDate('tanggal_input', $tanggalInput);
            }

            if ($kategori) {
                $query->where('kategori', $kategori);
            }

            $currentTotalTrx = (clone $query)->count();
            $currentTotalNilai = (float) (clone $query)->sum('total');
            $currentTotalItem = (int) (clone $query)->sum('jumlah_keluar');

            $listData = $query->orderBy('tanggal_input', 'desc')->paginate(15)->appends($request->query());
            $kategoriList = TransaksiKeluarPersediaan::select('kategori')->whereNotNull('kategori')->where('kategori', '!=', '')->distinct()->pluck('kategori');
        }

        return view('kasubag.transaksi_keluar', compact(
            'sumber',
            'listData',
            'kategoriList',
            'totalTrxPersediaan',
            'totalNilaiPersediaan',
            'totalItemPersediaan',
            'totalTrxAset',
            'totalNilaiAset',
            'totalItemAset',
            'totalSemuaTrx',
            'totalSemuaNilai',
            'currentTotalTrx',
            'currentTotalNilai',
            'currentTotalItem'
        ));
    }

    /**
     * LAPORAN TRANSAKSI KELUAR UNTUK KASUBAG
     * Format: 'keseluruhan' (Rekapitulasi Global & per Kategori) atau 'detail' (Rincian Transaksi per Item)
     */
    public function laporanTransaksiKeluar(Request $request)
    {
        $mode = $request->get('mode', 'keseluruhan'); // 'keseluruhan' atau 'detail'
        $sumber = $request->get('sumber', 'persediaan'); // 'persediaan' atau 'aset_tetap'
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $kategori = $request->get('kategori');
        $search = $request->get('search');

        if ($sumber === 'aset_tetap') {
            $query = TransaksiKeluarAssetTetap::query();

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode_barang', 'like', "%{$search}%")
                        ->orWhere('nama_barang', 'like', "%{$search}%")
                        ->orWhere('nup', 'like', "%{$search}%")
                        ->orWhere('merek', 'like', "%{$search}%")
                        ->orWhere('nomor_sk', 'like', "%{$search}%")
                        ->orWhere('lokasi', 'like', "%{$search}%");
                });
            }

            if ($startDate) {
                $query->whereDate('tanggal_input', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('tanggal_input', '<=', $endDate);
            }
            if ($kategori) {
                $query->where('merek', $kategori);
            }

            $totalTransaksi = (clone $query)->count();
            $totalNilai = (float) (clone $query)->sum('nilai_perolehan');
            $totalUnit = $totalTransaksi;

            // Rekap Keseluruhan per Kategori / Merek
            $rekapKategori = (clone $query)
                ->selectRaw("COALESCE(NULLIF(merek, ''), 'Lainnya') as nama_kategori, COUNT(*) as frekuensi, COUNT(*) as total_unit, SUM(nilai_perolehan) as total_nominal")
                ->groupBy('nama_kategori')
                ->orderByDesc('total_nominal')
                ->get();

            // Data untuk mode detail
            $detailTransaksi = $query->orderBy('tanggal_input', 'desc')->paginate(15)->appends($request->query());
            $kategoriList = TransaksiKeluarAssetTetap::select('merek as kategori')->whereNotNull('merek')->where('merek', '!=', '')->distinct()->pluck('kategori');

        } else {
            // Persediaan
            $query = TransaksiKeluarPersediaan::query();

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode_barang', 'like', "%{$search}%")
                        ->orWhere('nama_barang', 'like', "%{$search}%")
                        ->orWhere('kategori', 'like', "%{$search}%")
                        ->orWhere('kode_kategori', 'like', "%{$search}%");
                });
            }

            if ($startDate) {
                $query->whereDate('tanggal_input', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('tanggal_input', '<=', $endDate);
            }
            if ($kategori) {
                $query->where('kategori', $kategori);
            }

            $totalTransaksi = (clone $query)->count();
            $totalNilai = (float) (clone $query)->sum('total');
            $totalUnit = (int) (clone $query)->sum('jumlah_keluar');

            // Rekap Keseluruhan per Kategori
            $rekapKategori = (clone $query)
                ->selectRaw("COALESCE(NULLIF(kategori, ''), 'Tanpa Kategori') as nama_kategori, kode_kategori, COUNT(*) as frekuensi, SUM(jumlah_keluar) as total_unit, SUM(total) as total_nominal")
                ->groupBy('nama_kategori', 'kode_kategori')
                ->orderByDesc('total_nominal')
                ->get();

            // Data untuk mode detail
            $detailTransaksi = $query->orderBy('tanggal_input', 'desc')->paginate(15)->appends($request->query());
            $kategoriList = TransaksiKeluarPersediaan::select('kategori')->whereNotNull('kategori')->where('kategori', '!=', '')->distinct()->pluck('kategori');
        }

        // Tren Bulanan (6 bulan terakhir)
        $isSqlite = DB::connection()->getDriverName() === 'sqlite';
        $dateCol = 'tanggal_input';
        $monthFormat = $isSqlite ? "strftime('%Y-%m', {$dateCol})" : "DATE_FORMAT({$dateCol}, '%Y-%m')";

        if ($sumber === 'aset_tetap') {
            $monthlyStats = TransaksiKeluarAssetTetap::selectRaw("{$monthFormat} as periode, COUNT(*) as total_item, SUM(nilai_perolehan) as total_rp")
                ->where('tanggal_input', '>=', now()->subMonths(5)->startOfMonth())
                ->groupBy('periode')
                ->orderBy('periode')
                ->get()
                ->keyBy('periode');
        } else {
            $monthlyStats = TransaksiKeluarPersediaan::selectRaw("{$monthFormat} as periode, SUM(jumlah_keluar) as total_item, SUM(total) as total_rp")
                ->where('tanggal_input', '>=', now()->subMonths(5)->startOfMonth())
                ->groupBy('periode')
                ->orderBy('periode')
                ->get()
                ->keyBy('periode');
        }

        $chartTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $key = now()->subMonths($i)->format('Y-m');
            $label = now()->subMonths($i)->locale('id')->isoFormat('MMM Y');
            $itemStat = $monthlyStats->get($key);
            $chartTrend[] = [
                'periode' => $label,
                'total_item' => (int) ($itemStat->total_item ?? 0),
                'total_rp' => (float) ($itemStat->total_rp ?? 0),
            ];
        }

        return view('kasubag.laporan_transaksi_keluar', compact(
            'mode',
            'sumber',
            'startDate',
            'endDate',
            'kategori',
            'search',
            'kategoriList',
            'totalTransaksi',
            'totalUnit',
            'totalNilai',
            'rekapKategori',
            'detailTransaksi',
            'chartTrend'
        ));
    }

    /**
     * EKSPOR PDF LAPORAN TRANSAKSI KELUAR UNTUK KASUBAG
     */
    public function exportLaporanTransaksiKeluarPdf(Request $request)
    {
        $mode = $request->get('mode', 'keseluruhan');
        $sumber = $request->get('sumber', 'persediaan');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $kategori = $request->get('kategori');
        $search = $request->get('search');

        if ($sumber === 'aset_tetap') {
            $query = TransaksiKeluarAssetTetap::query();

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode_barang', 'like', "%{$search}%")
                        ->orWhere('nama_barang', 'like', "%{$search}%")
                        ->orWhere('nup', 'like', "%{$search}%")
                        ->orWhere('merek', 'like', "%{$search}%")
                        ->orWhere('nomor_sk', 'like', "%{$search}%")
                        ->orWhere('lokasi', 'like', "%{$search}%");
                });
            }

            if ($startDate) {
                $query->whereDate('tanggal_input', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('tanggal_input', '<=', $endDate);
            }
            if ($kategori) {
                $query->where('merek', $kategori);
            }

            $totalTransaksi = (clone $query)->count();
            $totalNilai = (float) (clone $query)->sum('nilai_perolehan');
            $totalUnit = $totalTransaksi;

            $rekapKategori = (clone $query)
                ->selectRaw("COALESCE(NULLIF(merek, ''), 'Lainnya') as nama_kategori, COUNT(*) as frekuensi, COUNT(*) as total_unit, SUM(nilai_perolehan) as total_nominal")
                ->groupBy('nama_kategori')
                ->orderByDesc('total_nominal')
                ->get();

            $detailTransaksi = $query->orderBy('tanggal_input', 'desc')->get();
        } else {
            $query = TransaksiKeluarPersediaan::query();

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode_barang', 'like', "%{$search}%")
                        ->orWhere('nama_barang', 'like', "%{$search}%")
                        ->orWhere('kategori', 'like', "%{$search}%")
                        ->orWhere('kode_kategori', 'like', "%{$search}%");
                });
            }

            if ($startDate) {
                $query->whereDate('tanggal_input', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('tanggal_input', '<=', $endDate);
            }
            if ($kategori) {
                $query->where('kategori', $kategori);
            }

            $totalTransaksi = (clone $query)->count();
            $totalNilai = (float) (clone $query)->sum('total');
            $totalUnit = (int) (clone $query)->sum('jumlah_keluar');

            $rekapKategori = (clone $query)
                ->selectRaw("COALESCE(NULLIF(kategori, ''), 'Tanpa Kategori') as nama_kategori, kode_kategori, COUNT(*) as frekuensi, SUM(jumlah_keluar) as total_unit, SUM(total) as total_nominal")
                ->groupBy('nama_kategori', 'kode_kategori')
                ->orderByDesc('total_nominal')
                ->get();

            $detailTransaksi = $query->orderBy('tanggal_input', 'desc')->get();
        }

        $kasubagUser = Auth::user();

        $pdf = Pdf::loadView('kasubag.pdf_laporan_transaksi_keluar', compact(
            'mode',
            'sumber',
            'startDate',
            'endDate',
            'kategori',
            'search',
            'totalTransaksi',
            'totalUnit',
            'totalNilai',
            'rekapKategori',
            'detailTransaksi',
            'kasubagUser'
        ))->setPaper('a4', 'landscape');

        $fileName = 'Laporan_Transaksi_Keluar_' . ucfirst($sumber) . '_' . ucfirst($mode) . '_' . date('Ymd_His') . '.pdf';

        return $pdf->download($fileName);
    }
}
