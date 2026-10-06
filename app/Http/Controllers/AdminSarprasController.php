<?php

namespace App\Http\Controllers;

use App\Jobs\SendFonnteNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; // Tambahkan ini di atas
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\{
    AdminSarpras,
    AssetTetap,
    Gedung,
    FasilitasBeranda,
    PeminjamanGedung,
    Kerusakan,
    PerbaikanKerusakan,
    User
};
use App\Services\FonnteService;

class AdminSarprasController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        // ── Statistik Gedung ──
        $totalGedung = Gedung::count();
        $gedungTersedia = Gedung::where('ketersediaan', 'Tersedia')->count();
        $gedungDigunakan = Gedung::where('ketersediaan', 'Sedang Dipakai')->count();

        // ── Statistik Peminjaman ──
        $totalPeminjaman = PeminjamanGedung::count();
        $peminjamanPending = PeminjamanGedung::where('status', 'pending')->count();
        $peminjamanDalamReview = PeminjamanGedung::where('status', 'dalam_review')->count();
        $peminjamanDisetujui = PeminjamanGedung::whereIn('status', ['disetujui', 'disetujui_kasubag'])->count();
        $peminjamanDitolak = PeminjamanGedung::where('status', 'ditolak')->count();
        $peminjamanAktif = $peminjamanPending + $peminjamanDalamReview;

        // ── Statistik Kerusakan ──
        $totalKerusakan = Kerusakan::count();
        $rusakBerat = Kerusakan::where('kondisi', 'Rusak Berat')->count();
        $rusakRingan = Kerusakan::where('kondisi', 'Rusak Ringan')->count();
        $perluPerbaikan = $rusakBerat + $rusakRingan;

        // ── Statistik Pembayaran ──
        $totalPendapatan = PeminjamanGedung::whereIn('status', ['disetujui', 'disetujui_kasubag'])
            ->sum('total_pembayaran');

        // ── Chart: Peminjaman per bulan (tahun ini) ──
        $chartData = [];
        $bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $maxMonthly = 1;

        for ($m = 1; $m <= 12; $m++) {
            $count = PeminjamanGedung::whereYear('created_at', now()->year)
                ->whereMonth('created_at', $m)
                ->count();
            $chartData[] = $count;
            if ($count > $maxMonthly) $maxMonthly = $count;
        }

        // Hitung persentase height untuk chart bars
        $chartBars = [];
        foreach ($chartData as $i => $count) {
            $chartBars[] = [
                'label' => $bulanLabels[$i],
                'count' => $count,
                'height' => $maxMonthly > 0 ? round(($count / $maxMonthly) * 100) : 0,
            ];
        }

        // ── Peminjaman Terbaru (5 terakhir) ──
        $peminjamanTerbaru = PeminjamanGedung::with('gedung')
            ->latest()
            ->limit(5)
            ->get();

        // ── Aktivitas Terbaru ──
        $aktivitasTerbaru = PeminjamanGedung::with('gedung')
            ->latest('updated_at')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $iconMap = [
                    'pending' => ['icon' => 'fa-clock', 'bg' => '#f3e8fd', 'color' => '#9333ea'],
                    'dalam_review' => ['icon' => 'fa-paper-plane', 'bg' => '#eef0fd', 'color' => '#4361ee'],
                    'disetujui_kasubag' => ['icon' => 'fa-check-circle', 'bg' => '#e8faf9', 'color' => '#2ec4b6'],
                    'disetujui' => ['icon' => 'fa-check-double', 'bg' => '#e8faf9', 'color' => '#2ec4b6'],
                    'ditolak' => ['icon' => 'fa-times-circle', 'bg' => '#fdecea', 'color' => '#e63946'],
                ];
                $statusInfo = $iconMap[$item->status] ?? $iconMap['pending'];
                $labelMap = [
                    'pending' => 'Peminjaman baru',
                    'dalam_review' => 'Diteruskan ke Kasubag',
                    'disetujui_kasubag' => 'Disetujui Kasubag',
                    'disetujui' => 'Peminjaman disetujui',
                    'ditolak' => 'Peminjaman ditolak',
                ];

                return [
                    'text' => ($labelMap[$item->status] ?? 'Peminjaman') . ' - ' . ($item->nama_fasilitas ?? $item->gedung?->nama_gedung ?? 'Gedung'),
                    'by' => $item->nama_lengkap,
                    'time' => $item->updated_at->locale('id')->diffForHumans(),
                    'icon' => $statusInfo['icon'],
                    'bg' => $statusInfo['bg'],
                    'color' => $statusInfo['color'],
                ];
            });

        // ── Gedung baru ditambahkan bulan ini ──
        $gedungBaruBulanIni = Gedung::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        // ── Peminjaman bulan ini ──
        $peminjamanBulanIni = PeminjamanGedung::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        return view('adminsarpras.dashbord', compact(
            'user',
            'totalGedung',
            'gedungTersedia',
            'gedungDigunakan',
            'totalPeminjaman',
            'peminjamanPending',
            'peminjamanDalamReview',
            'peminjamanDisetujui',
            'peminjamanDitolak',
            'peminjamanAktif',
            'totalKerusakan',
            'perluPerbaikan',
            'rusakBerat',
            'rusakRingan',
            'totalPendapatan',
            'chartBars',
            'peminjamanTerbaru',
            'aktivitasTerbaru',
            'gedungBaruBulanIni',
            'peminjamanBulanIni'
        ));
    }

    // DATA GEDUNG
    public function dataGedung(Request $request)
    {
        // Auto-sinkronisasi awal jika tabel gedung masih kosong tetapi fasilitas beranda ada
        if (Gedung::count() === 0 && class_exists(FasilitasBeranda::class) && FasilitasBeranda::count() > 0) {
            $this->performSyncFromFasilitas();
        }

        $query = Gedung::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_gedung', 'like', '%' . $request->search . '%')
                    ->orWhere('lokasi', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('ketersediaan')) {
            $query->where('ketersediaan', $request->ketersediaan);
        }

        $gedung = $query->latest()->paginate(10);

        $stats = [
            'total' => Gedung::count(),
            'tersedia' => Gedung::where('ketersediaan', 'Tersedia')->count(),
            'dipakai' => Gedung::where('ketersediaan', 'Sedang Dipakai')->count(),
            'renovasi' => Gedung::whereIn('ketersediaan', ['Renovasi', 'Perlu Perbaikan'])->count(),
        ];

        return view('adminsarpras.data_gedung', compact('gedung', 'stats'));
    }

    // Sinkronisasi data gedung dari fasilitas beranda
    public function syncGedungFromFasilitas()
    {
        $count = $this->performSyncFromFasilitas();
        return redirect()->route('adminsarpras.data-gedung')
            ->with('success', "Berhasil menyinkronkan {$count} data gedung dari Fasilitas Beranda!");
    }

    private function performSyncFromFasilitas(): int
    {
        $fasilitasItems = FasilitasBeranda::orderBy('urutan')->orderBy('id')->get();
        if ($fasilitasItems->isEmpty()) {
            return 0;
        }

        $count = 0;
        foreach ($fasilitasItems as $item) {
            $k = $item->konten;
            if (empty($k['name'])) continue;

            $kapasitas = 50;
            if (!empty($k['capacity'])) {
                if (preg_match('/(\d+)\s*-\s*(\d+)/', $k['capacity'], $m)) {
                    $kapasitas = (int)$m[2];
                } elseif (preg_match('/(\d+)/', $k['capacity'], $m)) {
                    $kapasitas = (int)$m[1];
                }
            }

            $luas = '100';
            if (!empty($k['luas'])) {
                $cleaned = trim(str_replace(['m²', 'm2', 'M²', 'M2'], '', $k['luas']));
                if (!empty($cleaned)) {
                    $luas = $cleaned;
                }
            }

            $fotoUrl = null;
            if (!empty($k['images']) && is_array($k['images']) && !empty($k['images'][0])) {
                $fotoUrl = ltrim(preg_replace('#^/?storage/#', '', $k['images'][0]), '/');
            }

            $fasilitasText = null;
            if (!empty($k['features']) && is_array($k['features'])) {
                $fasilitasText = implode(', ', array_filter($k['features']));
            }

            $kategori = $k['category'] ?? 'ruang';

            $tarifMap = [
                'ruang' => 1500000,
                'kelas' => 500000,
                'penginapan' => 500000,
                'ruang_makan' => 300000,
                'outdoor' => 100000,
                'lapangan_Upacara' => 200000,
                'kantor' => 0,
                'sarana_ibadah' => 0,
                'kesehatan' => 0,
                'gedung' => 0,
            ];
            $tarif = $tarifMap[$kategori] ?? 0;

            $gedung = Gedung::where('nama_gedung', $k['name'])->first();
            if ($gedung) {
                $gedung->update([
                    'lokasi' => $k['location'] ?? $gedung->lokasi,
                    'luas_bangunan' => $luas,
                    'kapasitas' => $kapasitas,
                    'kategori' => $kategori,
                    'fasilitas' => $fasilitasText,
                    'foto_url' => $fotoUrl ?? $gedung->foto_url,
                ]);
            } else {
                Gedung::create([
                    'nama_gedung' => $k['name'],
                    'foto_url' => $fotoUrl,
                    'lokasi' => $k['location'] ?? 'BPMP Gorontalo',
                    'luas_bangunan' => $luas,
                    'tarif_sewa' => $tarif,
                    'kapasitas' => $kapasitas,
                    'ketersediaan' => 'Tersedia',
                    'fasilitas' => $fasilitasText,
                    'kategori' => $kategori,
                ]);
            }
            $count++;
        }

        return $count;
    }

    // Tambah gedung
    public function storeGedung(Request $request)
    {
        $validated = $request->validate([
            'nama_gedung' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'luas_bangunan' => 'required|string|max:100',
            'tarif_sewa' => 'required|integer|min:0',
            'kapasitas' => 'required|integer|min:1',
            'ketersediaan' => 'required|in:Tersedia,Sedang Dipakai,Renovasi,Perlu Perbaikan',
            'kategori' => ['required', Rule::in(array_keys(Gedung::kategoriOptions()))],
            'fasilitas' => 'nullable|string|max:1000',
            'foto_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = [
            'nama_gedung' => $validated['nama_gedung'],
            'lokasi' => $validated['lokasi'],
            'luas_bangunan' => $validated['luas_bangunan'],
            'tarif_sewa' => $validated['tarif_sewa'],
            'kapasitas' => $validated['kapasitas'],
            'ketersediaan' => $validated['ketersediaan'],
            'kategori' => $validated['kategori'],
            'fasilitas' => $validated['fasilitas'] ?? null,
        ];

        //✅ UPLOAD FOTO TERpisah
        if ($request->hasFile('foto_url')) {
            $data['foto_url'] = $request->file('foto_url')->store('gedung_photos', 'public');
        }

        // ✅ SEKARANG AMAN
        $gedung = Gedung::create($data);

        return redirect()->route('adminsarpras.data-gedung')
            ->with('success', 'Gedung berhasil ditambahkan!');
    }

    // Edit gedung
    public function updateGedung(Request $request, Gedung $gedung)
    {
        $validated = $request->validate([
            'nama_gedung' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'luas_bangunan' => 'required|string|max:100',
            'tarif_sewa' => 'required|integer|min:0',
            'kapasitas' => 'required|integer|min:1',
            'ketersediaan' => 'required|in:Tersedia,Sedang Dipakai,Renovasi,Perlu Perbaikan',
            'kategori' => ['nullable', Rule::in(array_keys(Gedung::kategoriOptions()))],
            'fasilitas' => 'nullable|string',
            'foto_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();

        // Update foto
        if ($request->hasFile('foto_url')) {
            // Hapus foto lama
            if ($gedung->foto_url && Storage::disk('public')->exists($gedung->foto_url)) {
                Storage::disk('public')->delete($gedung->foto_url);
            }
            $data['foto_url'] = $request->file('foto_url')->store('gedung_photos', 'public');
        }

        $gedung->update($data);

       return redirect()->route('adminsarpras.data-gedung')->with('success', 'Gedung berhasil diupdate!');

    }

    // Hapus gedung
    public function destroyGedung(Gedung $gedung)
    {
        // Hapus foto
        if ($gedung->foto_url && Storage::disk('public')->exists($gedung->foto_url)) {
            Storage::disk('public')->delete($gedung->foto_url);
        }

        $gedung->delete();

        return redirect()->back()->with('success', 'Gedung berhasil dihapus!');
    }

    // API untuk detail JSON
    public function showGedungJson(Gedung $gedung)
    {
        return response()->json([
            'id' => $gedung->id,
            'nama_gedung' => $gedung->nama_gedung,
            'foto_url' => $gedung->foto_path,
            'lokasi' => $gedung->lokasi,
            'luas_bangunan' => $gedung->luas_bangunan,
            'tarif_sewa' => $gedung->tarif_sewa,
            'kapasitas' => $gedung->kapasitas,
            'ketersediaan' => $gedung->ketersediaan,
            'kategori' => $gedung->kategori,
            'fasilitas' => $gedung->fasilitas,
        ]);
    }


    public function daftarPengembalian(Request $request)
    {
        return redirect()->route('adminsarpras.daftar-peminjaman');
    }

    //====DAFTAR PEMINJAMAN=========
    public function daftarPeminjaman(Request $request)
    {
        $query = PeminjamanGedung::with(['user', 'reviewer', 'approver'])
            ->orderBy('created_at', 'desc');

        // Filter status berdasarkan tab
        if ($request->filled('status')) {
            if ($request->status == 'menunggu') {
                $query->pendingReview();
            } elseif ($request->status == 'disetujui') {
                $query->whereIn('status', ['disetujui_kasubag', 'disetujui']);
            } elseif ($request->status == 'ditolak') {
                $query->where('status', 'ditolak');
            }
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
                    ->orWhere('instansi_lembaga', 'like', '%' . $request->search . '%')
                    ->orWhere('fasilitas', 'like', '%' . $request->search . '%')
                    ->orWhere('nama_fasilitas', 'like', '%' . $request->search . '%');
            });
        }

        $peminjaman = $query->paginate(15);

        return view('adminsarpras.daftar_peminjaman', compact('peminjaman'));
    }

    // Action: Teruskan ke Kasubag
    public function forwardToKasubag(Request $request, PeminjamanGedung $peminjaman)
    {
        try {
            // ✅ CEK AUTH DULU
            if (!auth()->check()) {
                return response()->json(['success' => false, 'message' => 'Belum login'], 401);
            }

            $adminId = auth()->id();

            $request->validate([
                'komentar' => 'nullable|string|max:1000'
            ]);

            $peminjaman->update([
                'status' => 'diteruskan_kasubag',
                'reviewed_by_admin_id' => $adminId,
                'diteruskan_ke_kasubag_date' => now(),
                'komentar' => $request->komentar
            ]);

            // --- 1. NOTIFIKASI KE KASUBAG ---
            $kasubag = User::where('role', 'kasubag')->first();
            if ($kasubag && $kasubag->nomor_telepon) {
                $noHpKasubag = preg_replace('/[^0-9]/', '', $kasubag->nomor_telepon);

                // Mengambil nama gedung (asumsi ada relasi 'gedung')
                $namaGedung = $peminjaman->gedung->nama_gedung ?? 'Gedung/Ruangan';
                $instansi = $peminjaman->instansi_lembaga ? "({$peminjaman->instansi_lembaga})" : "";

                $pesanKasubag = "*Persetujuan Peminjaman GEDUNG*\n\n";
                $pesanKasubag .= "Yth. Kasubag,\n";
                $pesanKasubag .= "Admin Sarpras meneruskan permintaan peminjaman gedung dari Tamu untuk disetujui:\n\n";
                $pesanKasubag .= "👤 *Pemohon:* {$peminjaman->nama_lengkap} {$instansi}\n";
                $pesanKasubag .= "🏫 *Gedung:* {$namaGedung}\n";
                $pesanKasubag .= "📅 *Tanggal:* " . ($peminjaman->tanggal_mulai ?? '-') . " s/d " . ($peminjaman->tanggal_selesai ?? '-') . "\n";
                $pesanKasubag .= "📝 *Kegiatan:* " . ($peminjaman->nama_kegiatan ?? '-') . "\n\n";
                $pesanKasubag .= "Silakan login ke sistem untuk memberikan persetujuan akhir.";

                SendFonnteNotification::dispatch($noHpKasubag, $pesanKasubag);
            }

            // --- 2. NOTIFIKASI INFO KE TAMU (Bukan Ditolak!) ---
            if ($peminjaman->nomor_kontak) {
                $noHpTamu = preg_replace('/[^0-9]/', '', $peminjaman->nomor_kontak);

                $pesanTamu = "*Status Peminjaman Gedung*\n\n";
                $pesanTamu .= "Halo {$peminjaman->nama_lengkap},\n";
                $pesanTamu .= "Pengajuan peminjaman gedung Anda telah diverifikasi oleh Admin Sarpras dan *sedang diteruskan ke Kasubag* untuk proses persetujuan akhir.\n\n";
                $pesanTamu .= "Kami akan mengabari Anda kembali setelah ada keputusan dari Kasubag. Terima kasih.";

                SendFonnteNotification::dispatch($noHpTamu, $pesanTamu);
            }

            return response()->json([
                'success' => true,
                'message' => 'Peminjaman berhasil diteruskan ke Kasubag!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Forward Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    // Action: Tolak oleh Admin
    public function rejectByAdmin(Request $request, PeminjamanGedung $peminjaman)
    {
        try {
            if (!auth()->check()) {
                return response()->json(['success' => false, 'message' => 'Belum login'], 401);
            }

            $request->validate([
                'komentar' => 'required|string|max:1000'
            ]);

            $adminId = auth()->id();

            $peminjaman->update([
                'status' => 'ditolak',
                'reviewed_by_admin_id' => $adminId,
                'komentar' => $request->komentar
            ]);

            // --- NOTIFIKASI KE TAMU (DITOLAK OLEH ADMIN) ---
            if ($peminjaman->nomor_kontak) {
                // Bersihkan format nomor HP
                $noHpTamu = preg_replace('/[^0-9]/', '', $peminjaman->nomor_kontak);

                // Ambil nama gedung
                $namaGedung = $peminjaman->gedung->nama_gedung ?? ($peminjaman->nama_fasilitas ?? 'Fasilitas');

                $pesanTamu = "*Peminjaman Gedung DITOLAK Admin*\n\n";
                $pesanTamu .= "Halo {$peminjaman->nama_lengkap},\n";
                $pesanTamu .= "Maaf, pengajuan peminjaman fasilitas Anda telah *ditolak* oleh Admin Sarpras:\n\n";
                $pesanTamu .= "🏫 *Fasilitas:* {$namaGedung}\n";
                $pesanTamu .= "📅 *Tanggal:* {$peminjaman->tanggal_pinjam} s/d {$peminjaman->tanggal_kembali}\n";
                $pesanTamu .= "💬 *Alasan Penolakan:* " . $request->komentar . "\n\n";
                $pesanTamu .= "Silakan hubungi bagian Admin Sarpras jika ada pertanyaan lebih lanjut. Terima kasih.";

                SendFonnteNotification::dispatch($noHpTamu, $pesanTamu);
            }

            return response()->json([
                'success' => true,
                'message' => 'Peminjaman berhasil ditolak!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Reject Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    // Action: Update Peminjaman (Edit Modal)
    public function updatePeminjaman(Request $request, $id)
    {
        try {
            if (!auth()->check()) {
                return response()->json(['success' => false, 'message' => 'Belum login'], 401);
            }

            $peminjaman = PeminjamanGedung::findOrFail($id);

            $validated = $request->validate([
                'total_pembayaran' => 'required|numeric|min:0',
                'status_pembayaran' => 'required|in:belum_lunas,lunas',
                'status' => 'required|in:pending,dalam_review,disetujui_kasubag,disetujui,ditolak',
            ]);

            $peminjaman->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Data peminjaman berhasil diperbarui!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Update Peminjaman Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    // Download surat
    public function downloadSurat(PeminjamanGedung $peminjaman)
    {
        // Cek apakah file surat ada
        if (!$peminjaman->surat_path || !Storage::disk('public')->exists($peminjaman->surat_path)) {
            return back()->with('error', 'Surat peminjaman tidak ditemukan!');
        }

        // Ambil path lengkap file
        $filePath = storage_path('app/public/' . $peminjaman->surat_path);

        // Nama file download (dengan timestamp dan nama peminjam)
        $originalName = pathinfo($peminjaman->surat_path, PATHINFO_BASENAME);
        $downloadName = "Surat_Peminjaman_{$peminjaman->nama_lengkap}_{$peminjaman->id}." .
            pathinfo($originalName, PATHINFO_EXTENSION);

        return response()->download($filePath, $downloadName);
    }

    public function laporanPeminjamanGedung(Request $request)
    {
        // Query utama
        $query = PeminjamanGedung::with(['user', 'gedung', 'reviewer', 'approver'])
            ->select(
                'id',
                'user_id',
                'gedung_id',
                'nama_lengkap',
                'instansi_lembaga',
                'tanggal_pinjam',
                'tanggal_kembali',
                'jam_mulai',
                'jam_selesai',
                'status',
                'komentar',
                'total_pembayaran',
                'surat_path',
                'lama_peminjaman_hari',
                'created_at'
            );

        // Filters
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
                    ->orWhere('instansi_lembaga', 'like', '%' . $request->search . '%')
                    ->orWhere('tujuan_penggunaan', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $peminjaman = $query->latest('tanggal_pinjam')->paginate(10);

        // ✅ SEMUA STATS DIHITUNG DI SINI
        $stats = [
            'total_peminjaman' => PeminjamanGedung::count(),
            'sedang_dipinjam' => PeminjamanGedung::whereIn('status', ['disetujui', 'dipinjam'])->count(),
            'terlambat' => PeminjamanGedung::where('status', 'terlambat')->count(),
            'disetujui' => PeminjamanGedung::where('status', 'disetujui')->count(),
            'pending' => PeminjamanGedung::whereIn('status', ['pending', 'dalam_review'])->count(),
            'ditolak' => PeminjamanGedung::where('status', 'ditolak')->count(),
            'total_pembayaran' => (float) PeminjamanGedung::sum('total_pembayaran'),
            'rata_rata_hari' => (int) PeminjamanGedung::avg('lama_peminjaman_hari'),
        ];

        $donutData = [
            'total' => $stats['total_peminjaman'],
            'disetujui' => $stats['disetujui'],
            'pending' => $stats['pending'],
            'ditolak' => $stats['ditolak'],
            'disetujui_pct' => $stats['total_peminjaman'] > 0 ? round(($stats['disetujui'] / $stats['total_peminjaman']) * 100) : 0,
            'pending_pct' => $stats['total_peminjaman'] > 0 ? round(($stats['pending'] / $stats['total_peminjaman']) * 100) : 0,
            'ditolak_pct' => $stats['total_peminjaman'] > 0 ? round(($stats['ditolak'] / $stats['total_peminjaman']) * 100) : 0,
        ];

        // Status bulan ini
        $bulanIniQuery = PeminjamanGedung::whereYear('tanggal_pinjam', now()->year)
            ->whereMonth('tanggal_pinjam', now()->month);
        $statusBulanIni = [
            'disetujui' => $bulanIniQuery->where('status', 'disetujui')->count(),
            'pending' => $bulanIniQuery->clone()->whereIn('status', ['pending', 'dalam_review'])->count(),
            'ditolak' => $bulanIniQuery->clone()->where('status', 'ditolak')->count(),
            'total' => $bulanIniQuery->count()
        ];

        // Persentase approval
        $total = $stats['total_peminjaman'] ?: 1;
        $stats['persentase_approval'] = round(($stats['disetujui'] / $total) * 100);

        // Trend data 6 bulan terakhir
        $trendData = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $count = PeminjamanGedung::whereYear('tanggal_pinjam', $bulan->year)
                ->whereMonth('tanggal_pinjam', $bulan->month)
                ->count();
            $maxCount = PeminjamanGedung::whereYear('tanggal_pinjam', '>=', now()->subMonths(6)->year)
                ->whereMonth('tanggal_pinjam', '>=', now()->subMonths(6)->month)
                ->count();
            $height = $maxCount > 0 ? ($count / $maxCount) * 100 : 0;

            $trendData[] = [
                'val' => $count,
                'height' => $height,
                'label' => $bulan->locale('id')->isoFormat('MMM'),
                'isGreen' => false
            ];
        }

        return view('adminsarpras.laporan_peminjaman_gedung', compact(
            'peminjaman',
            'stats',
            'statusBulanIni',
            'trendData',
            'donutData'
        ));
    }

    /**
     * Generate Surat Perjanjian Sewa Peminjaman Gedung (PDF)
     */
    public function generateSuratPerjanjianSewa(Request $request, PeminjamanGedung $peminjaman)
    {
        $request->validate(['tanggal_surat' => 'nullable|date']);
        $tanggalSurat = $request->date('tanggal_surat');
        // 1. Ambil data entitas yang terlibat
        $admin = Auth::user();
        $kepala = User::where('role', 'kepalabpmp')->first();

        // 2. Fungsi bantuan untuk mengubah tanda tangan menjadi Base64
        $getBase64 = function ($path) {
            if ($path && Storage::disk('public')->exists($path)) {
                $type = pathinfo(storage_path('app/public/' . $path), PATHINFO_EXTENSION);
                $data = Storage::disk('public')->get($path);
                return 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
            return null;
        };

        // 3. Konversi signature menjadi Base64
        $ttdAdmin = $getBase64($admin->signature);
        $ttdKepala = $kepala ? $getBase64($kepala->signature) : null;

        // Catatan: Jika tamu/peminjam memiliki kolom ttd, kita bisa memanggilnya di sini.
        // Berdasarkan skema tabel Anda sebelumnya, pendaftar meminjam lewat form dan mungkin tidak punya field signature di model User untuk tamu.
        // Jika ada, tambahkan logika untuk $ttdPeminjam di bawah ini.
        $ttdPeminjam = null;
        if ($peminjaman->user && isset($peminjaman->user->signature)) {
            $ttdPeminjam = $getBase64($peminjaman->user->signature);
        }

        // 4. Render tampilan PDF
        $pdf = Pdf::loadView('surat.peminjaman_gedung', compact(
            'peminjaman',
            'admin',
            'kepala',
            'ttdAdmin',
            'ttdKepala',
            'ttdPeminjam',
            'tanggalSurat'
        ));

        // 5. Unduh file PDF
        return $pdf->download('Surat_peminjaman_gedung' . str_pad($peminjaman->id, 3, '0', STR_PAD_LEFT) . '.pdf');
    }

    /**
     * Upload Surat Perjanjian BAST Final oleh Admin
     */
    public function uploadSuratPerjanjianSewa(Request $request, PeminjamanGedung $peminjaman)
    {
        $request->validate([
            'surat_bast' => 'required|mimes:pdf|max:5120' // Wajib PDF, max 5MB
        ]);

        if ($request->hasFile('surat_bast')) {
            // Hapus file lama jika admin mengunggah ulang
            if ($peminjaman->surat_path && Storage::disk('public')->exists($peminjaman->surat_path)) {
                Storage::disk('public')->delete($peminjaman->surat_path);
            }

            $filename = 'SP_Sewa_Gedung_' . str_pad($peminjaman->id, 3, '0', STR_PAD_LEFT) . '_' . time() . '.pdf';

            // Simpan file ke storage
            $path = $request->file('surat_bast')->storeAs('peminjaman_surat', $filename, 'public');

            // Update record
            $peminjaman->update([
                'surat_perjanjian_path' => $path,
            ]);

            return back()->with('success', 'Surat Perjanjian BAST Final berhasil diunggah!');
        }

        return back()->with('error', 'Gagal mengunggah surat perjanjian.');
    }

    //====DATA KERUSAKAN=========
    public function dataKerusakan(Request $request)
    {
        $query = Kerusakan::query()
            ->orderBy('tanggal_input', 'desc')
            ->orderBy('id', 'desc');

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->search . '%')
                    ->orWhere('kode_barang', 'like', '%' . $request->search . '%')
                    ->orWhere('nup', 'like', '%' . $request->search . '%')
                    ->orWhere('lokasi', 'like', '%' . $request->search . '%');
            });
        }

        // Filter kondisi
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        // ✅ VARIABEL YANG BENAR: $kerusakans (plural)
        $kerusakans = $query->paginate(15);

        // ✅ STATS LENGKAP untuk dashboard laporan
        $stats = $this->getKerusakanStats();

        return view('adminsarpras.data_kerusakan', compact('kerusakans', 'stats'));
    }

    /**
     * Hitung semua statistik kerusakan
     */
    private function getKerusakanStats()
    {
        $total = Kerusakan::count();

        // Stats kondisi
        $stats = [
            'baik' => Kerusakan::where('kondisi', 'Baik')->count(),
            'rusak_ringan' => Kerusakan::where('kondisi', 'Rusak Ringan')->count(),
            'rusak_berat' => Kerusakan::where('kondisi', 'Rusak Berat')->count(),
            'total' => $total,
        ];

        // Stats per lokasi (top 10)
        $lokasiStats = Kerusakan::select('lokasi')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN kondisi = 'Baik' THEN 1 ELSE 0 END) as baik")
            ->selectRaw("SUM(CASE WHEN kondisi = 'Rusak Ringan' THEN 1 ELSE 0 END) as rusak_ringan")
            ->selectRaw("SUM(CASE WHEN kondisi = 'Rusak Berat' THEN 1 ELSE 0 END) as rusak_berat")
            ->groupBy('lokasi')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $stats['lokasi'] = [];
        foreach ($lokasiStats as $item) {
            $stats['lokasi'][$item->lokasi] = [
                'total' => (int) $item->total,
                'baik' => (int) $item->baik,
                'rusak_ringan' => (int) $item->rusak_ringan,
                'rusak_berat' => (int) $item->rusak_berat,
            ];
        }

        // Lokasi terbanyak
        $lokasiTerbanyak = $lokasiStats->first();
        $stats['lokasi_terbanyak'] = $lokasiTerbanyak ? [
            'lokasi' => $lokasiTerbanyak->lokasi,
            'count' => (int) $lokasiTerbanyak->total
        ] : ['lokasi' => '—', 'count' => 0];

        return $stats;
    }

    /**
     * Simpan data kerusakan baru (via form/modal)
     */
    public function storeKerusakan(Request $request)
    {
        $validated = $request->validate([
            'tanggal_input' => 'required|date',
            'nama_barang' => 'required|string|max:255',
            'kode_barang' => 'required|string|max:50',
            'nup' => 'nullable|string|max:100',
            'kondisi' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Upload foto
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('kerusakan_photos', 'public');
        }

        Kerusakan::create($validated);

        return redirect()->route('adminsarpras.data-kerusakan')
            ->with('success', 'Data kerusakan berhasil ditambahkan!');
    }

    /**
     * Update data kerusakan (Sekarang menggunakan redirect normal, bukan AJAX JSON)
     */
    public function updateKerusakanAjax(Request $request, $id)
    {
        $kerusakan = Kerusakan::findOrFail($id);

        $validated = $request->validate([
            'tanggal_input' => 'required|date',
            'nama_barang' => 'required|string|max:255',
            'kode_barang' => 'required|string|max:50',
            'nup' => 'nullable|string|max:100',
            'kondisi' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Upload foto baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($kerusakan->foto && Storage::disk('public')->exists($kerusakan->foto)) {
                Storage::disk('public')->delete($kerusakan->foto);
            }
            $validated['foto'] = $request->file('foto')->store('kerusakan_photos', 'public');
        }

        $kerusakan->update($validated);

        // ✅ PERUBAHAN DI SINI: Mengubah JSON menjadi Redirect
        return redirect()->route('adminsarpras.data-kerusakan')
            ->with('success', 'Data kerusakan berhasil diupdate!');
    }

    /**
     * Hapus data kerusakan
     */
    public function destroyKerusakan($id)
    {
        $kerusakan = Kerusakan::findOrFail($id);
        if ($kerusakan->riwayat()->exists() || $kerusakan->riwayatPerbaikan()->exists()) {
            return back()->with('error', 'Data ini memiliki riwayat. Gunakan edit kondisi agar riwayat tetap tersimpan.');
        }

        // Hapus foto
        if ($kerusakan->foto && Storage::disk('public')->exists($kerusakan->foto)) {
            Storage::disk('public')->delete($kerusakan->foto);
        }

        $kerusakan->delete();

        return redirect()->route('adminsarpras.data-kerusakan')
            ->with('success', 'Data kerusakan berhasil dihapus!');
    }

    /**
     * API pencarian barang / aset tetap untuk popup pemilihan pada form data kerusakan
     */
    public function getBarangPilihan(Request $request)
    {
        $search = trim($request->get('q', ''));
        $page = max(1, (int) $request->get('page', 1));
        $perPage = 10;

        $query = AssetTetap::query()
            ->select('id', 'kode_barang', 'nup', 'nama_barang', 'merek', 'kategori', 'lokasi', 'kondisi');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                    ->orWhere('kode_barang', 'like', "%{$search}%")
                    ->orWhere('nup', 'like', "%{$search}%")
                    ->orWhere('merek', 'like', "%{$search}%")
                    ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        $total = $query->count();
        $items = $query->orderBy('nama_barang', 'asc')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        // Cek apakah barang sudah pernah dicatat di data kerusakan
        $existingKodes = Kerusakan::pluck('kode_barang')->toArray();
        $items->transform(function ($item) use ($existingKodes) {
            $item->sudah_ada = in_array($item->kode_barang, $existingKodes);
            return $item;
        });

        return response()->json([
            'items' => $items,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'last_page' => (int) ceil($total / $perPage),
        ]);
    }

    /**
     * API untuk modal EDIT (JSON) - Tetap dibiarkan karena dipakai untuk mengambil data ke dalam form modal
     */
    public function editKerusakanJson($id)
    {
        $kerusakan = Kerusakan::findOrFail($id);
        $kerusakan->foto_url = $kerusakan->foto ? asset('storage/' . $kerusakan->foto) : null;
        return response()->json($kerusakan);
    }

    /**
     * API untuk modal DETAIL (JSON) - Tetap dibiarkan karena dipakai untuk menampilkan detail
     */
    public function showKerusakanJson($id)
    {
        $kerusakan = Kerusakan::with(['riwayatPerbaikan.dicatatOleh'])->findOrFail($id);
        $kerusakan->foto_url = $kerusakan->foto ? asset('storage/' . $kerusakan->foto) : null;
        $kerusakan->tanggal_input_formatted = $kerusakan->tanggal_input->locale('id')->isoFormat('D MMMM Y');
        return response()->json($kerusakan);
    }

    public function storePerbaikanKerusakan(Request $request, Kerusakan $kerusakan)
    {
        $validated = $request->validate([
            'tanggal_perbaikan' => 'required|date',
            'tindakan' => 'required|string|max:2000',
            'biaya' => 'nullable|numeric|min:0|max:9999999999999.99',
            'pelaksana' => 'nullable|string|max:255',
            'status' => 'required|in:Proses,Selesai,Tidak Dapat Diperbaiki',
            'catatan' => 'nullable|string|max:2000',
        ]);

        DB::transaction(function () use ($validated, $kerusakan) {
            $kerusakan->riwayatPerbaikan()->create([
                ...$validated,
                'biaya' => $validated['biaya'] ?? 0,
                'user_id' => Auth::id(),
            ]);

            if ($validated['status'] === 'Selesai') {
                $kerusakan->update(['kondisi' => 'Baik']);
            }
        });

        return redirect()->route('adminsarpras.data-kerusakan')
            ->with('success', 'Riwayat perbaikan berhasil ditambahkan.');
    }

    public function riwayatKerusakan(Kerusakan $kerusakan)
    {
        $kerusakan->load(['riwayat.pencatat', 'riwayatPerbaikan.dicatatOleh']);
        return view('adminsarpras.riwayat_kerusakan', compact('kerusakan'));
    }

    /**
     * Laporan kerusakan (statistik bulanan/tahunan)
     */
    public function laporanKerusakan(Request $request)
    {
        $query = Kerusakan::query();

        // Filter bulan/tahun
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereYear('tanggal_input', $request->tahun)
                ->whereMonth('tanggal_input', $request->bulan);
        }

        // Filter kondisi
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        $laporan = $query->latest()->paginate(20);

        $stats = [
            'total' => Kerusakan::count(),
            'per_kondisi' => Kerusakan::selectRaw('kondisi, count(*) as total')
                ->groupBy('kondisi')
                ->pluck('total', 'kondisi'),
            'per_lokasi' => Kerusakan::selectRaw('lokasi, count(*) as total')
                ->groupBy('lokasi')
                ->orderByDesc('total')
                ->limit(5)
                ->pluck('total', 'lokasi')
        ];

        $kerusakans = $query->latest()->paginate(20); // ✅ Plural
        $stats = $this->getKerusakanStats();

        return view('adminsarpras.laporan_kerusakan', compact('kerusakans', 'stats'));
    }

    /**
     * Download Laporan Peminjaman Gedung (PDF)
     */
    public function downloadLaporanPeminjaman(Request $request)
    {
        $query = PeminjamanGedung::with(['gedung']);

        // Terapkan filter yang sama dengan tampilan web
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
                    ->orWhere('instansi_lembaga', 'like', '%' . $request->search . '%')
                    ->orWhere('tujuan_penggunaan', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Ambil semua data tanpa pagination untuk di-print
        $peminjaman = $query->latest('tanggal_pinjam')->get();

        // Hitung total pendapatan dari laporan yang diunduh
        $totalPendapatan = $peminjaman->whereIn('status', ['disetujui', 'disetujui_kasubag', 'dipinjam'])->sum('total_pembayaran');

        // Buat PDF
        $pdf = Pdf::loadView('adminsarpras.pdf_laporan_peminjaman', compact('peminjaman', 'totalPendapatan'))
            ->setPaper('A4', 'landscape');

        // Kembalikan file untuk di-download
        return $pdf->download('Laporan_Peminjaman_Gedung_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Download Laporan Kerusakan (PDF)
     */
    public function downloadLaporanKerusakan(Request $request)
    {
        $query = Kerusakan::query();

        // Terapkan filter yang sama dengan tampilan web
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereYear('tanggal_input', $request->tahun)
                ->whereMonth('tanggal_input', $request->bulan);
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        // Search (jika ada input search)
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->search . '%')
                    ->orWhere('kode_barang', 'like', '%' . $request->search . '%')
                    ->orWhere('lokasi', 'like', '%' . $request->search . '%');
            });
        }

        // Ambil semua data tanpa pagination
        $kerusakans = $query->orderBy('tanggal_input', 'desc')->get();

        // Buat PDF
        $pdf = Pdf::loadView('adminsarpras.pdf_laporan_kerusakan', compact('kerusakans'))
            ->setPaper('A4', 'landscape');

        // Kembalikan file untuk di-download
        return $pdf->download('Laporan_Kerusakan_Sarpras_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Halaman Monitoring & Penjajakan Integrasi ULT (Unit Layanan Terpadu)
     */
    public function integrasiUlt()
    {
        $gedungList = Gedung::all();
        $peminjamanUlt = PeminjamanGedung::with('gedung')
            ->where(function ($q) {
                $q->where('tujuan_penggunaan', 'like', '%Tiket ULT%')
                  ->orWhere('nama_lengkap', 'like', '%Loket ULT%');
            })
            ->latest()
            ->paginate(15);

        $stats = [
            'total'     => PeminjamanGedung::where(fn($q) => $q->where('tujuan_penggunaan', 'like', '%Tiket ULT%')->orWhere('nama_lengkap', 'like', '%Loket ULT%'))->count(),
            'pending'   => PeminjamanGedung::where(fn($q) => $q->where('tujuan_penggunaan', 'like', '%Tiket ULT%')->orWhere('nama_lengkap', 'like', '%Loket ULT%'))->where('status', 'pending')->count(),
            'disetujui' => PeminjamanGedung::where(fn($q) => $q->where('tujuan_penggunaan', 'like', '%Tiket ULT%')->orWhere('nama_lengkap', 'like', '%Loket ULT%'))->whereIn('status', ['disetujui', 'disetujui_kasubag'])->count(),
        ];

        return view('adminsarpras.integrasi_ult', compact('gedungList', 'peminjamanUlt', 'stats'));
    }
}
