<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\{
    Gedung,
    PeminjamanGedung,
    User,
};

class TamuController extends Controller
{
    // Di Controller (misal: TamuDashboardController.php)
    public function dashboard()
    {
        $user = auth()->user();

        // Stats
        $stats = [
            'total' => $user->peminjamanGedung()->count(),
            'pending' => $user->peminjamanGedung()->where('status', 'pending')->count(),
            'approved' => $user->peminjamanGedung()->where('status', 'disetujui')->count(),
        ];

        // Recent requests (5 terbaru)
        $peminjaman_terbaru = $user->peminjamanGedung()
            ->with('gedung')
            ->latest()
            ->limit(5)
            ->get();

        // Notifications (optional)
        // $notifikasi = $user->notifications()->unread()->limit(5)->get();

        return view('tamu.dashbord', compact('stats', 'peminjaman_terbaru'));
    }

    public function peminjamanGedung()
    {
        // ✅ 1. Ambil gedung yang tersedia
        $gedungs = Gedung::where('ketersediaan', 'Tersedia')
            ->orderBy('kategori')
            ->orderBy('nama_gedung')
            ->get()
            ->groupBy('kategori');

        // ✅ 2. Generate $fasilitasData dari database (BUKAN hardcoded)
        $fasilitasData = [];
        foreach ($gedungs as $kategori => $items) {
            foreach ($items as $gedung) {
                $fasilitasData[$gedung->id] = [
                    'id' => $gedung->id,
                    'nama' => $gedung->nama_gedung,
                    'kategori' => $gedung->kategori,
                    'lokasi' => $gedung->lokasi,
                    'kapasitas' => $gedung->kapasitas . ' Orang',
                    'tarif' => $gedung->tarif_sewa,
                    'icon' => $gedung->icon,
                    'ketersediaan' => $gedung->ketersediaan,
                    'foto_url' => $gedung->foto_url,
                    'luas' => $gedung->luas_bangunan,
                ];
            }
        }

        // ✅ 3. Riwayat peminjaman
        $riwayat = collect();
        if (auth('web')->check()) {
            $riwayat = PeminjamanGedung::where('user_id', auth('web')->id())
                ->with('gedung')
                ->latest()
                ->limit(10)
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'nama_fasilitas' => $item->nama_fasilitas ?? $item->gedung?->nama_gedung,
                        'kode' => 'REQ-TAM-' . str_pad($item->id, 3, '0', STR_PAD_LEFT),
                        'instansi_lembaga' => $item->instansi_lembaga,
                        'tujuan_penggunaan' => Str::limit($item->tujuan_penggunaan, 25),
                        'tanggal_pinjam' => $item->tanggal_pinjam?->format('d M Y'),
                        'tanggal_kembali' => $item->tanggal_kembali?->format('d M Y'),
                        'range_tanggal' => $item->tanggal_pinjam?->format('d M') . ' – ' . $item->tanggal_kembali?->format('d M Y'),
                        'total_pembayaran' => 'Rp ' . number_format($item->total_pembayaran, 0, ',', '.'),
                        'jumlah_peserta' => $item->jumlah_peserta,
                        'status' => $item->status,
                        'status_pembayaran' => $item->status_pembayaran,
                        'created_at' => $item->created_at?->format('d M Y'),
                        'fasilitas' => $item->fasilitas,
                        'gedung_id' => $item->gedung_id,
                        // 'surat_path' => $item->surat_path,
                        'surat_perjanjian_path' => $item->surat_perjanjian_path,
                    ];
                });
        }

        // ✅ 4. Pass SEMUA variabel ke view
        return view('tamu.peminjaman_gedung', compact('gedungs', 'fasilitasData', 'riwayat'));
    }

    public function storePeminjamanGedung(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nip_nik' => 'required|string|max:50',
            'instansi_lembaga' => 'required|string|max:255',
            'kabupaten_kota' => 'required|string|max:100',
            'gedung_id' => 'required|exists:gedung,id',
            'tanggal_pinjam' => 'required|date|after_or_equal:' . now()->addDays(2)->format('Y-m-d'), // Minimal H-2
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'jumlah_peserta' => 'required|integer|min:1',
            'tujuan_penggunaan' => 'required|string|max:1000',
            'surat_path' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120'
        ], [
            // Tambahkan pesan custom agar tamu paham kenapa error
            'tanggal_pinjam.after_or_equal' => 'Peminjaman gedung harus dilakukan minimal H-2 (2 hari sebelum hari H).'
        ]);

        // ====================================================================
        // LOGIKA ANTI-BENTROK JADWAL (OVERLAPPING DATE & TIME VALIDATION)
        // ====================================================================
        $isBentrok = \App\Models\PeminjamanGedung::where('gedung_id', $validated['gedung_id'])
            ->whereNotIn('status', ['ditolak', 'dibatalkan']) // Abaikan yang sudah ditolak/batal
            ->where(function ($query) use ($validated) {
                // Cek apakah rentang tanggal beririsan
                $query->where('tanggal_pinjam', '<=', $validated['tanggal_kembali'])
                      ->where('tanggal_kembali', '>=', $validated['tanggal_pinjam']);
            })
            ->where(function ($query) use ($validated) {
                // Cek apakah rentang jam juga beririsan (di hari yang sama)
                $query->where('jam_mulai', '<', $validated['jam_selesai'])
                      ->where('jam_selesai', '>', $validated['jam_mulai']);
            })
            ->exists();

        if ($isBentrok) {
            // Karena pakai AJAX/Fetch, kita kembalikan response JSON Error 400
            return response()->json([
                'success' => false,
                'message' => 'Gagal! Gedung atau fasilitas ini sudah dibooking pada rentang tanggal dan jam tersebut. Silakan pilih waktu atau fasilitas lain.'
            ], 400); 
        }
        // ====================================================================

        // Ambil data gedung
        $gedung = \App\Models\Gedung::findOrFail($validated['gedung_id']);

        // Hitung durasi dan total pembayaran
        $start = \Carbon\Carbon::parse($validated['tanggal_pinjam']);
        $end = \Carbon\Carbon::parse($validated['tanggal_kembali']);
        $lamaPeminjaman = $start->diffInDays($end) + 1;
        $totalPembayaran = $gedung->tarif_sewa * $lamaPeminjaman;

        // Upload surat
        $suratPath = null;
        if ($request->hasFile('surat_path')) {
            $suratPath = $request->file('surat_path')->store('peminjaman_surat', 'public');
        }

        // Buat peminjaman
        $peminjaman = \App\Models\PeminjamanGedung::create([
            'user_id' => auth('web')->id(),
            'gedung_id' => $gedung->id,
            'nama_lengkap' => $validated['nama_lengkap'],
            'nip_nik' => $validated['nip_nik'],
            'instansi_lembaga' => $validated['instansi_lembaga'],
            'kabupaten_kota' => $validated['kabupaten_kota'],
            'fasilitas' => $gedung->kategori,
            'nama_fasilitas' => $gedung->nama_gedung,
            'tarif_per_hari' => $gedung->tarif_sewa,
            'tanggal_pinjam' => $validated['tanggal_pinjam'],
            'tanggal_kembali' => $validated['tanggal_kembali'],
            'jam_mulai' => $validated['jam_mulai'],
            'jam_selesai' => $validated['jam_selesai'],
            'lama_peminjaman_hari' => $lamaPeminjaman,
            'jumlah_peserta' => $validated['jumlah_peserta'],
            'total_pembayaran' => $totalPembayaran,
            'tujuan_penggunaan' => $validated['tujuan_penggunaan'],
            'surat_path' => $suratPath,
            'status' => 'pending',
            'status_pembayaran' => 'belum_lunas',
            'cara_pembayaran' => 'e-billing',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permintaan peminjaman ' . $gedung->nama_gedung . ' berhasil dikirim!',
            'data' => $peminjaman
        ]);
    }

    /**
     * Detail peminjaman gedung (JSON untuk modal)
     */
    public function showPeminjamanGedung(PeminjamanGedung $peminjaman)
    {
        // Pastikan hanya pemilik yang bisa melihat detail
        if ($peminjaman->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        $peminjaman->load('gedung');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $peminjaman->id,
                'kode' => 'REQ-TAM-' . str_pad($peminjaman->id, 3, '0', STR_PAD_LEFT),
                'nama_lengkap' => $peminjaman->nama_lengkap,
                'nip_nik' => $peminjaman->nip_nik,
                'instansi_lembaga' => $peminjaman->instansi_lembaga,
                'kabupaten_kota' => $peminjaman->kabupaten_kota,
                'nama_fasilitas' => $peminjaman->nama_fasilitas ?? $peminjaman->gedung?->nama_gedung,
                'fasilitas' => $peminjaman->fasilitas,
                'lokasi' => $peminjaman->gedung?->lokasi ?? '-',
                'tanggal_pinjam' => $peminjaman->tanggal_pinjam?->format('d M Y'),
                'tanggal_kembali' => $peminjaman->tanggal_kembali?->format('d M Y'),
                'jam_mulai' => $peminjaman->jam_mulai ? \Carbon\Carbon::parse($peminjaman->jam_mulai)->format('H:i') : '-',
                'jam_selesai' => $peminjaman->jam_selesai ? \Carbon\Carbon::parse($peminjaman->jam_selesai)->format('H:i') : '-',
                'jumlah_peserta' => $peminjaman->jumlah_peserta,
                'lama_peminjaman_hari' => $peminjaman->lama_peminjaman_hari,
                'tarif_per_hari' => 'Rp ' . number_format($peminjaman->tarif_per_hari, 0, ',', '.'),
                'total_pembayaran' => 'Rp ' . number_format($peminjaman->total_pembayaran, 0, ',', '.'),
                'tujuan_penggunaan' => $peminjaman->tujuan_penggunaan,
                'status' => $peminjaman->status,
                'status_label' => ucfirst(str_replace('_', ' ', $peminjaman->status)),
                'status_pembayaran' => $peminjaman->status_pembayaran,
                'cara_pembayaran' => ucfirst($peminjaman->cara_pembayaran),
                'komentar' => $peminjaman->komentar,
                'surat_permohonan_url' => $peminjaman->surat_path ? asset('storage/' . $peminjaman->surat_path) : null,
                'surat_perjanjian_url' => $peminjaman->surat_perjanjian_path ? asset('storage/' . $peminjaman->surat_perjanjian_path) : null,
                'created_at' => $peminjaman->created_at?->format('d M Y H:i'),
                'diteruskan_ke_kasubag_date' => $peminjaman->diteruskan_ke_kasubag_date?->format('d M Y H:i'),
            ]
        ]);
    }

    /**
     * Batalkan Permintaan Peminjaman Gedung (AJAX)
     */
    public function cancelPeminjaman($id)
    {
        try {
            $peminjaman = PeminjamanGedung::findOrFail($id);

            // Pastikan hanya peminjam yang bisa membatalkan datanya sendiri
            if ($peminjaman->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk membatalkan permintaan ini.'
                ], 403);
            }

            // Pastikan hanya yang berstatus pending / dalam review yang bisa dibatalkan
            if (!in_array($peminjaman->status, ['pending', 'dalam_review'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Permintaan sudah diproses dan tidak dapat dibatalkan.'
                ], 400);
            }

            // ✅ PERBAIKAN: Ubah status menjadi dibatalkan, JANGAN dihapus (delete)
            $peminjaman->update([
                'status' => 'dibatalkan',
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permintaan peminjaman berhasil dibatalkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function pengaturanAkun()
    {
        // Logika untuk pengaturan akun
        return view('tamu.pengaturan_akun');
    }

    /**
     * Info Fasilitas – data diambil dari tabel gedung (relasi dengan CRUD admin sarpras)
     */
    public function infoFasilitas(Request $request)
    {
        $query = Gedung::query();

        // Filter pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_gedung', 'like', '%' . $request->search . '%')
                    ->orWhere('lokasi', 'like', '%' . $request->search . '%')
                    ->orWhere('fasilitas', 'like', '%' . $request->search . '%');
            });
        }

        // Filter kategori
        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->where('kategori', $request->kategori);
        }

        $gedungs = $query->latest()->get();

        // Statistik untuk hero section
        $totalGedung = Gedung::count();
        $tersedia    = Gedung::where('ketersediaan', 'Tersedia')->count();

        return view('tamu.info_fasilitas', compact('gedungs', 'totalGedung', 'tersedia'));
    }

    public function surveiLayanan()
    {
        // Logika untuk pengembalian gedung
        return view('tamu.survei_layanan');
    }
}
