<?php

namespace App\Http\Controllers;

use App\Jobs\SendFonnteNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PeminjamanBarang;
use App\Models\PengembalianBarang;
use App\Models\PeminjamanKendaraan;
use App\Models\PengembalianKendaraan;
use App\Models\AssetTetap;
use App\Models\PermintaanPersediaan;
use App\Models\Persediaan;
use App\Models\User;
use App\Services\FonnteService;

class PegawaiController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Dashboard Pegawai
     */
    public function dashboard()
    {
        // ... (Kode dashboard Anda tetap sama, tidak ada error di sini) ...
        $userId = Auth::id();

        $statBarang = PeminjamanBarang::where('user_id', $userId)->count();
        $barangPending = PeminjamanBarang::where('user_id', $userId)
            ->whereIn('status', ['pending', 'diteruskan_kasubag'])->count();
        $barangSetuju = PeminjamanBarang::where('user_id', $userId)->where('status', 'disetujui')->count();

        $statKendaraan = PeminjamanKendaraan::where('user_id', $userId)->count();
        $kendaraanPending = PeminjamanKendaraan::where('user_id', $userId)->where('status', 'pending')->count();
        $kendaraanSetuju = PeminjamanKendaraan::where('user_id', $userId)->where('status', 'disetujui')->count();

        $statGedung = 0; $gedungPending = 0; $gedungSetuju = 0;
        $statPersediaan = 0; $persediaanPending = 0; $persediaanSetuju = 0;

        $riwayatBarang = PeminjamanBarang::where('user_id', $userId)
            ->latest()->take(5)->get()->map(function ($item) {
                return [
                    'tipe' => 'Barang',
                    'nama_item' => $item->nama_barang,
                    'status' => $item->status,
                    'tanggal' => $item->created_at
                ];
            });

        $riwayatKendaraan = PeminjamanKendaraan::where('user_id', $userId)
            ->latest()->take(5)->get()->map(function ($item) {
                return [
                    'tipe' => 'Kendaraan',
                    'nama_item' => $item->merek ?? $item->nama_barang ?? 'Kendaraan Dinas',
                    'status' => $item->status,
                    'tanggal' => $item->created_at
                ];
            });

        $riwayatTerbaru = collect($riwayatBarang)
            ->merge($riwayatKendaraan)
            ->sortByDesc('tanggal')
            ->take(5);

        return view('pegawai.dashbord', compact(
            'statBarang', 'barangPending', 'barangSetuju', 'statKendaraan', 'kendaraanPending', 'kendaraanSetuju',
            'statGedung', 'gedungPending', 'gedungSetuju', 'statPersediaan', 'persediaanPending', 'persediaanSetuju', 'riwayatTerbaru'
        ));
    }

    /**
     * Peminjaman Barang
     */
    public function peminjamanBarang(Request $request)
    {
        $asetTetap = AssetTetap::where('status', 'Tersedia')
            ->where('kondisi', '!=', 'rusak berat')
            ->where('kategori', '!=', 'Kendaraan')
            ->orderBy('nama_barang', 'asc')
            ->get();

        $riwayat = PeminjamanBarang::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pegawai.peminjaman_barang', compact('asetTetap', 'riwayat'));
    }

    public function storePeminjamanBarang(Request $request)
    {
       $request->validate([
            'kode_barang' => 'required|exists:aset_tetap,kode_barang',
            'nup' => 'nullable', // Pastikan form Blade Anda juga mengirimkan input NUP
            'jumlah' => 'required|integer|min:1',
            'tanggal_peminjaman' => 'required|date|after:today',
            'tanggal_pengembalian' => 'required|date|after_or_equal:tanggal_peminjaman',
            'deskripsi_peruntukan' => 'required|string',
        ], [
            'tanggal_peminjaman.after' => 'Pengajuan peminjaman barang wajib dilakukan maksimal H-1. Anda tidak bisa meminjam untuk hari ini.'
        ]);

        $statusAktifBarang = ['pending', 'diteruskan_kasubag', 'disetujui', 'disetujui_admin', 'proses_pengembalian'];

        // LAPIS 1: Cek Bentrok Tanggal BERDASARKAN KODE & NUP menggunakan whereDate
        $bentrokTanggal = PeminjamanBarang::where('kode_barang', $request->kode_barang)
            ->when($request->nup, function ($query) use ($request) {
                // Membedakan laptop satu dan lainnya berdasarkan NUP
                return $query->where('nup', $request->nup);
            })
            ->whereIn('status', $statusAktifBarang)
            ->where(function ($query) use ($request) {
                $query->whereDate('tanggal_peminjaman', '<=', $request->tanggal_pengembalian)
                      ->whereDate('tanggal_pengembalian', '>=', $request->tanggal_peminjaman);
            })
            ->exists();

        if ($bentrokTanggal) {
            return back()->withErrors([
                'kode_barang' => 'Maaf, barang ini (NUP: '.($request->nup ?? '-').') sudah dibooking/dipinjam pada rentang tanggal tersebut.'
            ])->withInput();
        }

        // LAPIS 2: Cek Barang Belum Dikembalikan
        $belumDikembalikan = PeminjamanBarang::where('kode_barang', $request->kode_barang)
            ->when($request->nup, function ($query) use ($request) {
                return $query->where('nup', $request->nup);
            })
            ->whereIn('status', $statusAktifBarang)
            ->whereDate('tanggal_pengembalian', '<', $request->tanggal_peminjaman)
            ->exists();

        if ($belumDikembalikan) {
            return back()->withErrors([
                'kode_barang' => 'Tidak bisa dipinjam. Peminjam sebelumnya belum mengembalikan barang ini.'
            ])->withInput();
        }

        // AMBIL DETAIL ASET BERDASARKAN KODE & NUP agar tidak salah ambil barang urutan pertama
        $aset = AssetTetap::where('kode_barang', $request->kode_barang)
                ->when($request->nup, function ($query) use ($request) {
                    return $query->where('nup', $request->nup);
                })->firstOrFail();

        // Simpan ke database
        PeminjamanBarang::create([
            'user_id' => Auth::id(),
            'nama_barang' => $aset->nama_barang,
            'kode_barang' => $aset->kode_barang,
            'nup' => $aset->nup,
            'kategori' => $aset->kategori,
            'merek' => $aset->merek,
            'jumlah' => $request->jumlah,
            'request_date' => now(),
            'tanggal_peminjaman' => $request->tanggal_peminjaman,
            'tanggal_pengembalian' => $request->tanggal_pengembalian,
            'deskripsi_peruntukan' => $request->deskripsi_peruntukan,
            'status' => 'pending',
        ]);

        $adminAset = User::where('role', 'adminasettetap')->first();
        if ($adminAset && $adminAset->nomor_telepon) {
            $namaPegawai = Auth::user()->name;

            $pesan = "*Permintaan Peminjaman BARANG Baru*\n\n";
            $pesan .= "Halo Admin Aset Tetap,\n";
            $pesan .= "Pegawai atas nama *{$namaPegawai}* mengajukan peminjaman dengan detail berikut:\n\n";
            $pesan .= "📦 *Nama Barang:* {$aset->nama_barang}\n";
            $pesan .= "🔖 *Kode/NUP:* {$aset->kode_barang} / " . ($aset->nup ?? '-') . "\n";
            $pesan .= "🔢 *Jumlah:* {$request->jumlah}\n";
            $pesan .= "📅 *Tgl Pinjam:* {$request->tanggal_peminjaman}\n";
            $pesan .= "📅 *Tgl Kembali:* {$request->tanggal_pengembalian}\n";
            $pesan .= "📝 *Keperluan:* {$request->deskripsi_peruntukan}\n\n";
            $pesan .= "Silakan login ke sistem untuk melakukan review.";

            $noHpAdmin = preg_replace('/[^0-9]/', '', $adminAset->nomor_telepon);
            SendFonnteNotification::dispatch($noHpAdmin, $pesan);
        }

        return back()->with('success', 'Permintaan peminjaman aset berhasil dikirim dan sedang menunggu persetujuan Admin.');
    }

    public function detailPeminjaman($id)
    {
        $peminjaman = PeminjamanBarang::with('user')->findOrFail($id);
        if ($peminjaman->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        return response()->json(['success' => true, 'data' => $peminjaman]);
    }

    public function cancelPeminjaman($id)
    {
        $peminjaman = \App\Models\PeminjamanBarang::where('id', $id)
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->first();

        if ($peminjaman) {
            $peminjaman->status = 'dibatalkan';
            $peminjaman->save(); 
            return response()->json(['success' => true, 'message' => 'Peminjaman berhasil dibatalkan.']);
        }

        return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
    }

    /**
     * Pengembalian Barang
     */
    public function pengembalianBarang()
    {
        $peminjamanAktif = PeminjamanBarang::where('user_id', auth()->id())
            ->whereIn('status', ['disetujui', 'disetujui_admin', 'disetujui_kasubag'])
            ->get();

        $riwayat = PengembalianBarang::with('peminjamanBarang')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pegawai.pengembalian_barang', compact('peminjamanAktif', 'riwayat'));
    }

    public function getPeminjamanJson($id)
    {
        $peminjaman = PeminjamanBarang::with('barang')->find($id);
        return response()->json($peminjaman);
    }

    public function storePengembalianBarang(Request $request)
    {
        $request->validate([
            'peminjaman_barang_id' => 'required|exists:peminjaman_barang,id',
            'tanggal_pengembalian_aktual' => 'required|date',
            'jumlah_dikembalikan' => 'required|integer|min:1',
            'kondisi_barang' => 'required|string',
            'foto_sesudah' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $peminjaman = PeminjamanBarang::findOrFail($request->peminjaman_barang_id);

        $fotoPath = null;
        if ($request->hasFile('foto_sesudah')) {
            $fotoPath = $request->file('foto_sesudah')->store('foto_pengembalian', 'public');
        }

        $statusMap = [
            'baik' => 'lengkap',
            'rusak-ringan' => 'rusak_ringan',
            'rusak-berat' => 'rusak_berat',
            'hilang' => 'hilang'
        ];

        PengembalianBarang::create([
            'peminjaman_barang_id' => $request->peminjaman_barang_id,
            'user_id' => auth()->id(),
            'tanggal_pengembalian_aktual' => $request->tanggal_pengembalian_aktual . ' ' . ($request->jam_pengembalian ?? '00:00:00'),
            'jumlah_dikembalikan' => $request->jumlah_dikembalikan,
            'kondisi_barang' => $request->kondisi_barang,
            'status_pengembalian' => $statusMap[$request->kondisi_barang] ?? 'lengkap',
            'catatan' => $request->catatan,
            'foto_sesudah' => $fotoPath,
            'status_verifikasi' => 'pending',
        ]);

        if ($request->jumlah_dikembalikan >= $peminjaman->jumlah) {
            $peminjaman->update(['status' => 'proses_pengembalian']);
        }

        $adminAset = User::where('role', 'adminasettetap')->first();
        if ($adminAset && $adminAset->nomor_telepon) {
            $namaPegawai = Auth::user()->name;

            $pesan = "*Laporan Pengembalian BARANG*\n\n";
            $pesan .= "Halo Admin Aset Tetap,\n";
            $pesan .= "Pegawai atas nama *{$namaPegawai}* melaporkan pengembalian barang dengan detail:\n\n";
            $pesan .= "📦 *Nama Barang:* {$peminjaman->nama_barang}\n";
            $pesan .= "🔢 *Jumlah Dikembalikan:* {$request->jumlah_dikembalikan}\n";
            $pesan .= "📅 *Tanggal Kembali:* {$request->tanggal_pengembalian_aktual}\n";
            $pesan .= "🔍 *Kondisi:* " . ucfirst($request->kondisi_barang) . "\n";
            $pesan .= "📝 *Catatan:* " . ($request->catatan ?? '-') . "\n\n";
            $pesan .= "Silakan login ke sistem untuk melakukan verifikasi foto dan laporan.";

            $noHpAdmin = preg_replace('/[^0-9]/', '', $adminAset->nomor_telepon);
            SendFonnteNotification::dispatch($noHpAdmin, $pesan);
        }

        return back()->with('success', 'Laporan pengembalian berhasil dikirim dan menunggu verifikasi Admin!');
    }

    public function showPengembalianJson($id)
    {
        $data = PengembalianBarang::with('peminjamanBarang')->find($id);
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function cancelPengembalian($id)
    {
        $pengembalian = PengembalianBarang::findOrFail($id);
        if ($pengembalian->status_verifikasi === 'pending') {
            $pengembalian->peminjamanBarang->update(['status' => 'disetujui']);
            $pengembalian->update([
                'status_verifikasi' => 'dibatalkan',
                'updated_at' => now()
            ]);
            return back()->with('success', 'Laporan pengembalian berhasil dibatalkan.');
        }
        return back()->with('error', 'Laporan yang sudah diverifikasi tidak dapat dibatalkan.');
    }

    /**
     * Permintaan Persediaan
     */
    public function permintaanPersediaan(Request $request)
    {
        $persediaan = Persediaan::select('id', 'kode_barang', 'nama_barang', 'jumlah', 'satuan')
            ->where('jumlah', '>', 0)
            ->orderBy('nama_barang')
            ->get();

        $riwayat = PermintaanPersediaan::where('user_id', Auth::id())
            ->with('persediaan')
            ->latest()
            ->limit(5)
            ->get();

        return view('pegawai.permintaan_persediaan', compact('persediaan', 'riwayat'));
    }

    public function storePermintaanPersediaan(Request $request)
    {
        $request->validate([
            'persediaan_id' => 'required|exists:persediaan,id',
            'jumlah_diminta' => 'required|integer|min:1',
            'tanggal_permintaan' => 'required|date',
            'tujuan_penggunaan' => 'required|string|max:1000',
        ]);

        $persediaan = Persediaan::find($request->persediaan_id);

        if (!$persediaan) {
            return back()->withErrors(['persediaan_id' => 'Barang tidak ditemukan!'])->withInput();
        }

        $jumlahSedangDiproses = PermintaanPersediaan::where('persediaan_id', $persediaan->id)
            ->whereIn('status', ['pending', 'dalam_review'])
            ->sum('jumlah_diminta');

        $sisaStokRiil = $persediaan->jumlah - $jumlahSedangDiproses;

        if ($request->jumlah_diminta > $sisaStokRiil) {
            return back()->withErrors([
                'persediaan_id' => "Maaf, sisa stok yang bisa diminta saat ini hanya {$sisaStokRiil} unit. (Terdapat {$jumlahSedangDiproses} unit yang sedang dalam antrean pengajuan oleh pegawai lain)."
            ])->withInput();
        }

        PermintaanPersediaan::create([
            'kode_barang' => $persediaan->kode_barang,           
            'nama_barang' => $persediaan->nama_barang,
            'persediaan_id' => $persediaan->id,               
            'user_id' => Auth::id(),
            'jumlah_diminta' => $request->jumlah_diminta,
            'satuan'=> $request->satuan,
            'tanggal_permintaan' => $request->tanggal_permintaan,
            'tujuan_penggunaan' => $request->tujuan_penggunaan,
            'status' => 'pending',
        ]);

        $adminPersediaan = User::where('role', 'adminpersediaan')->first();
        if ($adminPersediaan && $adminPersediaan->nomor_telepon) {
            $namaPegawai = Auth::user()->name;

            $pesan = "*Permintaan PERSEDIAAN Baru*\n\n";
            $pesan .= "Halo Admin Persediaan,\n";
            $pesan .= "Pegawai atas nama *{$namaPegawai}* mengajukan permintaan barang persediaan:\n\n";
            $pesan .= "📦 *Barang:* {$persediaan->nama_barang}\n";
            $pesan .= "🔖 *Kode:* {$request->kode_barang}\n";
            $pesan .= "🔢 *Jumlah:* {$request->jumlah_diminta}\n";
            $pesan .= "📝 *Keperluan:* {$request->tujuan_penggunaan}\n\n";
            $pesan .= "Silakan login ke sistem untuk melakukan review permintaan.";

            $noHpAdmin = preg_replace('/[^0-9]/', '', $adminPersediaan->nomor_telepon);
            SendFonnteNotification::dispatch($noHpAdmin, $pesan);
        }

        return redirect()->route('pegawai.permintaan-persediaan')
            ->with('success', 'Permintaan berhasil dikirim! Menunggu persetujuan Admin Persediaan.');
    }

    public function riwayatPermintaan(Request $request)
    {
        $query = PermintaanPersediaan::where('user_id', Auth::id())
            ->with('persediaan', 'reviewedBy', 'approvedByKasubag')
            ->latest();

        $riwayat = $query->paginate(10);
        return view('pegawai.permintaan_persediaan', compact('riwayat'));
    }

    public function detailPermintaanPersediaan($id)
    {
        try {
            $permintaan = PermintaanPersediaan::with(['persediaan', 'user', 'reviewedBy', 'approvedByKasubag'])
                ->findOrFail($id);

            if ($permintaan->user_id !== Auth::id()) {
                return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses ke data ini.']);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $permintaan->id,
                    'kode' => 'REQ-' . str_pad($permintaan->id, 4, '0', STR_PAD_LEFT),
                    'nama_barang' => $permintaan->nama_barang ?? '-',
                    'persediaan' => $permintaan->persediaan ? [
                        'nama_barang' => $permintaan->persediaan->nama_barang,
                        'kode_barang' => $permintaan->persediaan->kode_barang,
                        'kategori' => $permintaan->persediaan->kategori,
                        'jumlah' => $permintaan->persediaan->jumlah,
                    ] : null,
                    'jumlah_diminta' => $permintaan->jumlah_diminta,
                    'tanggal_permintaan' => $permintaan->tanggal_permintaan ? \Carbon\Carbon::parse($permintaan->tanggal_permintaan)->format('d M Y') : '-',
                    'tujuan_penggunaan' => $permintaan->tujuan_penggunaan,
                    'status' => $permintaan->status,
                    'status_label' => isset($permintaan->status_badge['text']) ? $permintaan->status_badge['text'] : ucfirst($permintaan->status),
                    'created_at' => $permintaan->created_at ? $permintaan->created_at->format('d M Y H:i') : '-',
                    'admin_approved_by' => $permintaan->reviewedBy?->name ?? null,
                    'kasubag_approved_by' => $permintaan->approvedByKasubag?->name ?? null,
                    'komentar_admin' => $permintaan->komentar ?? null,
                    'surat_path' => $permintaan->surat_url ? asset('storage/' . $permintaan->surat_url) : null,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage() . ' (Baris: ' . $e->getLine() . ' di ' . basename($e->getFile()) . ')'
            ], 200);
        }
    }

    public function cancelPermintaanPersediaan($id)
    {
        $permintaan = PermintaanPersediaan::where('user_id', Auth::id())
            ->whereIn('status', ['pending'])
            ->findOrFail($id);

        $permintaan->update([
            'status' => 'dibatalkan',
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permintaan persediaan berhasil dibatalkan!'
        ]);
    }

    public function showPermintaanPersediaanJson($id)
    {
        $permintaan = PermintaanPersediaan::with(['persediaan', 'user'])->find($id);

        if (!$permintaan) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan']);
        }

        return response()->json(['success' => true, 'data' => $permintaan]);
    }


    /**
     * Peminjaman Kendaraan
     */
    public function peminjamanKendaraan()
    {
        $kendaraan = AssetTetap::whereIn('kategori', ['Kendaraan', 'ALAT ANGKUTAN BERMOTOR'])
            ->where('status', 'Tersedia')
            ->get();

        $riwayat = PeminjamanKendaraan::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pegawai.peminjaman_kendaraan', compact('kendaraan', 'riwayat'));
    }

    public function storePeminjamanKendaraan(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required',
            'nup' => 'nullable', // Menambahkan NUP untuk kendaraan jika ada
            'jumlah' => 'required|integer|min:1',
            'tanggal_peminjaman' => 'required|date|after:today',
            'tanggal_pengembalian' => 'required|date|after_or_equal:tanggal_peminjaman',
            'deskripsi_peruntukan' => 'required|string',
        ], [
            'tanggal_peminjaman.after' => 'Pengajuan peminjaman kendaraan dinas wajib dilakukan maksimal H-1. Anda tidak bisa meminjam untuk hari ini.'
        ]);

        $statusAktifKendaraan = ['pending', 'dalam_review', 'disetujui', 'proses_pengembalian'];

        // LAPIS 1: Cek Bentrok Tanggal (Menggunakan NUP + Date)
        $bentrokTanggal = PeminjamanKendaraan::where('kode_barang', $request->kode_barang)
            ->when($request->nup, function ($query) use ($request) {
                return $query->where('nup', $request->nup);
            })
            ->whereIn('status', $statusAktifKendaraan)
            ->where(function ($query) use ($request) {
                $query->whereDate('tanggal_peminjaman', '<=', $request->tanggal_pengembalian)
                      ->whereDate('tanggal_pengembalian', '>=', $request->tanggal_peminjaman);
            })
            ->exists();

        if ($bentrokTanggal) {
            return back()->withErrors([
                'kode_barang' => 'Maaf, kendaraan ini sudah dibooking/dipinjam pada rentang tanggal tersebut.'
            ])->withInput();
        }

        // LAPIS 2: Cek Kendaraan Belum Dikembalikan
        $belumDikembalikan = PeminjamanKendaraan::where('kode_barang', $request->kode_barang)
            ->when($request->nup, function ($query) use ($request) {
                return $query->where('nup', $request->nup);
            })
            ->whereIn('status', $statusAktifKendaraan)
            ->whereDate('tanggal_pengembalian', '<', $request->tanggal_peminjaman)
            ->exists();

        if ($belumDikembalikan) {
            return back()->withErrors([
                'kode_barang' => 'Tidak bisa dipinjam. Peminjam sebelumnya belum mengembalikan kendaraan ini.'
            ])->withInput();
        }

        $aset = AssetTetap::with('detailKendaraan')
                ->where('kode_barang', $request->kode_barang)
                ->when($request->nup, function ($query) use ($request) {
                    return $query->where('nup', $request->nup);
                })->firstOrFail();

        PeminjamanKendaraan::create([
            'user_id' => auth()->id(),
            'nama_barang' => $aset->nama_barang,
            'kode_barang' => $aset->kode_barang,
            'nup' => $aset->nup,
            'merek' => $aset->merek,
            'jumlah' => $request->jumlah,
            'tanggal_peminjaman' => $request->tanggal_peminjaman,
            'tanggal_pengembalian' => $request->tanggal_pengembalian,
            'deskripsi_peruntukan' => $request->deskripsi_peruntukan,
            'status' => 'pending',

            // Tarik data dari relasi master, berikan default '-' jika data master masih kosong
            'nomor_polisi_saat_pinjam' => $aset->detailKendaraan->nomor_polisi ?? '-',
            'no_bpkb_saat_pinjam'      => $aset->detailKendaraan->no_bpkb ?? '-',
            'nomor_rangka_saat_pinjam' => $aset->detailKendaraan->nomor_rangka ?? '-',
            'nomor_mesin_saat_pinjam'  => $aset->detailKendaraan->nomor_mesin ?? '-',
        ]);

        $adminAset = User::where('role', 'adminasettetap')->first();
        if ($adminAset && $adminAset->nomor_telepon) {
            $namaPegawai = Auth::user()->name;

            $pesan = "*Permintaan Peminjaman KENDARAAN Baru*\n\n";
            $pesan .= "Halo Admin Aset Tetap,\n";
            $pesan .= "Pegawai atas nama *{$namaPegawai}* mengajukan peminjaman kendaraan dinas:\n\n";
            $merek = $aset->merek ? " ({$aset->merek})" : "";
            $pesan .= "🚗 *Kendaraan:* {$aset->nama_barang}{$merek}\n";
            
            // Opsional: Tambahkan nomor polisi ke pesan WhatsApp admin agar lebih informatif
            $platNomor = $aset->detailKendaraan->nomor_polisi ?? '-';
            $pesan .= "🔢 *Plat Nomor:* {$platNomor}\n";
            
            $pesan .= "📅 *Tgl Pinjam:* {$request->tanggal_peminjaman}\n";
            $pesan .= "📅 *Tgl Kembali:* {$request->tanggal_pengembalian}\n";
            $pesan .= "📝 *Keperluan:* {$request->deskripsi_peruntukan}\n\n";
            $pesan .= "Silakan login ke sistem untuk melakukan review.";

            $noHpAdmin = preg_replace('/[^0-9]/', '', $adminAset->nomor_telepon);
            SendFonnteNotification::dispatch($noHpAdmin, $pesan);
        }

        return back()->with('success', 'Permintaan peminjaman berhasil dikirim.');
    }

    public function showPeminjamanKendaraan($id)
    {
        $data = PeminjamanKendaraan::with('user')->findOrFail($id);
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function cancelPeminjamanKendaraan($id)
    {
        $data = PeminjamanKendaraan::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->firstOrFail();

        $data->update([
            'status' => 'dibatalkan',
            'updated_at' => now()
        ]);

        return response()->json(['success' => true, 'message' => 'Peminjaman kendaraan berhasil dibatalkan.']);
    }

    /**
     * Pengembalian Kendaraan
     */
    public function pengembalianKendaraan()
    {
        $peminjamanKendaraan = \App\Models\PeminjamanKendaraan::where('user_id', auth()->id())
            ->whereIn('status', ['disetujui']) 
            ->get();

        $pengembalianKendaraan = \App\Models\PengembalianKendaraan::with('peminjamanKendaraan')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pegawai.pengembalian_kendaraan', compact('peminjamanKendaraan', 'pengembalianKendaraan'));
    }

    public function getPeminjamanKendaraanJson($id)
    {
        $peminjaman = PeminjamanKendaraan::find($id);
        return response()->json($peminjaman);
    }

    public function storePengembalianKendaraan(Request $request)
    {
        $request->validate([
            'peminjaman_kendaraan_id' => 'required|exists:peminjaman_kendaraan,id',
            'tanggal_pengembalian_aktual' => 'required|date',
            'kondisi_kendaraan' => 'required|string',
            'foto_sebelum' => 'required|image|max:2048',
            'foto_sesudah' => 'required|image|max:2048',
        ]);

        $peminjaman = PeminjamanKendaraan::findOrFail($request->peminjaman_kendaraan_id);

        if (in_array($peminjaman->status, ['proses_pengembalian', 'selesai', 'diterima'])) {
            return back()->withErrors(['Kendaraan ini sudah dilaporkan untuk dikembalikan.']);
        }

        $fotoSebelum = $request->file('foto_sebelum')->store('pengembalian_kendaraan/sebelum', 'public');
        $fotoSesudah = $request->file('foto_sesudah')->store('pengembalian_kendaraan/sesudah', 'public');

        $statusMap = [
            'baik' => 'lengkap',
            'rusak-ringan' => 'rusak_ringan',
            'rusak-berat' => 'rusak_berat',
            'hilang' => 'hilang'
        ];

        PengembalianKendaraan::create([
            'peminjaman_kendaraan_id' => $request->peminjaman_kendaraan_id,
            'user_id' => auth()->id(),
            'tanggal_pengembalian_aktual' => $request->tanggal_pengembalian_aktual,
            'kondisi_kendaraan' => $request->kondisi_kendaraan,
            'catatan' => $request->catatan,
            'foto_sebelum' => $fotoSebelum,
            'foto_sesudah' => $fotoSesudah,
            // 🐛 PERBAIKAN TYPO DI SINI: sebelumnya $request->kondisi_barang
            'status_pengembalian' => $statusMap[$request->kondisi_kendaraan] ?? 'lengkap', 
            'biaya_denda' => 0,
            'status_verifikasi' => 'pending',
        ]);

        $peminjaman->update(['status' => 'proses_pengembalian']);

        $adminAset = User::where('role', 'adminasettetap')->first();
        if ($adminAset && $adminAset->nomor_telepon) {
            $namaPegawai = Auth::user()->name;

            $pesan = "*Laporan Pengembalian KENDARAAN*\n\n";
            $pesan .= "Halo Admin Aset Tetap,\n";
            $pesan .= "Pegawai atas nama *{$namaPegawai}* melaporkan pengembalian kendaraan dinas:\n\n";
            $pesan .= "🚗 *Kendaraan:* {$peminjaman->nama_barang}\n";
            
            // MENAMPILKAN PLAT NOMOR DARI DATA SNAPSHOT
            $platNomor = $peminjaman->nomor_polisi_saat_pinjam ?? '-';
            $pesan .= "🔢 *Plat Nomor:* {$platNomor}\n";
            
            $pesan .= "📅 *Tanggal Kembali:* {$request->tanggal_pengembalian_aktual}\n";
            $pesan .= "🔍 *Kondisi Kendaraan:* " . ucfirst($request->kondisi_kendaraan) . "\n";
            $pesan .= "📝 *Catatan:* " . ($request->catatan ?? '-') . "\n\n";
            $pesan .= "Silakan login ke sistem untuk melakukan verifikasi foto kendaraan.";

            $noHpAdmin = preg_replace('/[^0-9]/', '', $adminAset->nomor_telepon);
            SendFonnteNotification::dispatch($noHpAdmin, $pesan);
        }

        return back()->with('success', 'Laporan pengembalian kendaraan berhasil dikirim!');
    }

    public function showPengembalianKendaraanJson($id)
    {
        $data = PengembalianKendaraan::with('peminjamanKendaraan')->find($id);
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function cancelPengembalianKendaraan($id)
    {
        $pengembalian = PengembalianKendaraan::findOrFail($id);
        
        // 🐛 PERBAIKAN DI SINI: sebelumnya 'diproses', seharusnya 'pending' sesuai saat create
        if ($pengembalian->status_verifikasi === 'pending') { 
            $pengembalian->peminjamanKendaraan->update(['status' => 'disetujui']);
            $pengembalian->update([
                'status_verifikasi' => 'dibatalkan',
                'updated_at' => now()
            ]);
            return back()->with('success', 'Laporan pengembalian berhasil dibatalkan.');
        }
        return back()->with('error', 'Laporan yang sudah diverifikasi tidak dapat dibatalkan.');
    }

    /**
     * Pengaturan Akun
     */
    public function pengaturanAkun()
    {
        $pegawai = Auth::user();
        return view('pegawai.pengaturan_akun', compact('pegawai'));
    }

    /**
     * Update Profile
     */
    public function updateProfile(Request $request)
    {
        // $request->validate([ ... ]);
    }

    public function infoMutasi()
    {
        $mutasiBarang = \App\Models\MutasiBarang::with(['asetTetap', 'user'])
            ->orderBy('tanggal_mutasi', 'desc')
            ->paginate(10);

        return view('pegawai.info_mutasibarang', compact('mutasiBarang'));
    }
}