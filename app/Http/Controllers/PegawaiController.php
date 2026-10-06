<?php

namespace App\Http\Controllers;

use App\Jobs\SendFonnteNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\PeminjamanBarang;
use App\Models\DetailPeminjamanBarang;
use App\Models\PengembalianBarang;
use App\Models\PeminjamanKendaraan;
use App\Models\PengembalianKendaraan;
use App\Models\AssetTetap;
use App\Models\PermintaanPersediaan;
use App\Models\DetailPermintaanPersediaan;
use App\Models\Persediaan;
use App\Models\User;
use App\Services\FonnteService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

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
        $statusAktifBarang = ['pending', 'diteruskan_kasubag', 'disetujui', 'disetujui_admin', 'proses_pengembalian'];

        // Skenario 1: Multi-Item
        if ($request->has('items') && is_array($request->items)) {
            $request->validate([
                'items' => 'required|array|min:1',
                'items.*.kode_barang' => 'required|string',
                'items.*.nup' => 'nullable|string',
                'items.*.jumlah' => 'required|integer|min:1',
                'tanggal_peminjaman' => 'required|date|after_or_equal:today',
                'tanggal_pengembalian' => 'required|date|after_or_equal:tanggal_peminjaman',
                'deskripsi_peruntukan' => 'required|string',
            ], [
                'items.required' => 'Daftar barang pinjaman tidak boleh kosong. Silakan tambahkan minimal 1 barang.',
                'items.min' => 'Silakan tambahkan minimal 1 barang ke dalam daftar pinjaman.',
                'tanggal_peminjaman.required' => 'Tanggal peminjaman wajib diisi.',
                'tanggal_peminjaman.after_or_equal' => 'Tanggal peminjaman boleh hari ini atau setelahnya.',
                'tanggal_pengembalian.required' => 'Tanggal pengembalian wajib diisi.',
                'tanggal_pengembalian.after_or_equal' => 'Tanggal pengembalian harus sama dengan atau setelah tanggal pinjam.',
                'deskripsi_peruntukan.required' => 'Tujuan penggunaan barang wajib diisi.',
            ]);

            // Validasi bentrok & ambil detail setiap item
            $collectedItems = [];
            foreach ($request->items as $item) {
                $kodeBarang = $item['kode_barang'];
                $nup = $item['nup'] ?? null;
                $jumlah = (int)$item['jumlah'];

                // Cek bentrok di detail_peminjaman_barang
                $bentrokDetail = DetailPeminjamanBarang::where('kode_barang', $kodeBarang)
                    ->when($nup, fn($q) => $q->where('nup', $nup))
                    ->whereHas('peminjamanBarang', function($q) use ($statusAktifBarang, $request) {
                        $q->whereIn('status', $statusAktifBarang)
                          ->whereDate('tanggal_peminjaman', '<=', $request->tanggal_pengembalian)
                          ->whereDate('tanggal_pengembalian', '>=', $request->tanggal_peminjaman);
                    })->exists();

                // Cek bentrok di peminjaman_barang legacy
                $bentrokLegacy = PeminjamanBarang::where('kode_barang', $kodeBarang)
                    ->when($nup, fn($q) => $q->where('nup', $nup))
                    ->whereIn('status', $statusAktifBarang)
                    ->where(function ($query) use ($request) {
                        $query->whereDate('tanggal_peminjaman', '<=', $request->tanggal_pengembalian)
                              ->whereDate('tanggal_pengembalian', '>=', $request->tanggal_peminjaman);
                    })->exists();

                if ($bentrokDetail || $bentrokLegacy) {
                    $labelNup = $nup ? " (NUP: {$nup})" : "";
                    return back()->withErrors([
                        'items' => "Barang {$kodeBarang}{$labelNup} sudah memiliki jadwal peminjaman pada rentang tanggal tersebut. Silakan pilih NUP lain atau hapus dari daftar."
                    ])->withInput();
                }

                // Cek belum dikembalikan
                $belumKembali = DetailPeminjamanBarang::where('kode_barang', $kodeBarang)
                    ->when($nup, fn($q) => $q->where('nup', $nup))
                    ->whereHas('peminjamanBarang', function($q) use ($statusAktifBarang, $request) {
                        $q->whereIn('status', $statusAktifBarang)
                          ->whereDate('tanggal_pengembalian', '<', $request->tanggal_peminjaman);
                    })->exists();

                if (!$belumKembali) {
                    $belumKembali = PeminjamanBarang::where('kode_barang', $kodeBarang)
                        ->when($nup, fn($q) => $q->where('nup', $nup))
                        ->whereIn('status', $statusAktifBarang)
                        ->whereDate('tanggal_pengembalian', '<', $request->tanggal_peminjaman)
                        ->exists();
                }

                if ($belumKembali) {
                    $labelNup = $nup ? " (NUP: {$nup})" : "";
                    return back()->withErrors([
                        'items' => "Barang {$kodeBarang}{$labelNup} belum dikembalikan oleh peminjam sebelumnya."
                    ])->withInput();
                }

                $aset = AssetTetap::where('kode_barang', $kodeBarang)
                    ->when($nup, fn($q) => $q->where('nup', $nup))
                    ->first();

                if (!$aset) {
                    return back()->withErrors(['items' => "Aset {$kodeBarang} tidak ditemukan dalam sistem."])->withInput();
                }

                $collectedItems[] = [
                    'aset' => $aset,
                    'jumlah' => $jumlah,
                    'nup' => $nup ?? $aset->nup,
                ];
            }

            // Simpan Parent & Detail dalam Transaction
            $peminjaman = DB::transaction(function () use ($request, $collectedItems) {
                $first = $collectedItems[0]['aset'];
                $totalJumlah = array_sum(array_column($collectedItems, 'jumlah'));
                $totalItemCount = count($collectedItems);

                $namaBarangHeader = $totalItemCount > 1 
                    ? "{$first->nama_barang} (+ " . ($totalItemCount - 1) . " barang lainnya)" 
                    : $first->nama_barang;

                $parent = PeminjamanBarang::create([
                    'user_id' => Auth::id(),
                    'nama_barang' => $namaBarangHeader,
                    'kode_barang' => $first->kode_barang,
                    'nup' => $totalItemCount > 1 ? 'Multi-NUP' : ($collectedItems[0]['nup'] ?? '-'),
                    'kategori' => $totalItemCount > 1 ? 'Multi-Item' : ($first->kategori ?? '-'),
                    'merek' => $totalItemCount > 1 ? 'Beragam' : ($first->merek ?? '-'),
                    'jumlah' => $totalJumlah,
                    'request_date' => now(),
                    'tanggal_peminjaman' => $request->tanggal_peminjaman,
                    'tanggal_pengembalian' => $request->tanggal_pengembalian,
                    'deskripsi_peruntukan' => $request->deskripsi_peruntukan,
                    'status' => 'pending',
                ]);

                foreach ($collectedItems as $item) {
                    $aset = $item['aset'];
                    DetailPeminjamanBarang::create([
                        'peminjaman_barang_id' => $parent->id,
                        'aset_tetap_id' => $aset->id,
                        'kode_barang' => $aset->kode_barang,
                        'nup' => $item['nup'] ?? $aset->nup,
                        'nama_barang' => $aset->nama_barang,
                        'merek' => $aset->merek,
                        'kategori' => $aset->kategori,
                        'jumlah' => $item['jumlah'],
                        'kondisi' => $aset->kondisi,
                    ]);
                }

                return $parent;
            });

            // Notifikasi WA
            $adminAset = User::where('role', 'adminasettetap')->first();
            if ($adminAset && $adminAset->nomor_telepon) {
                $namaPegawai = Auth::user()->name;
                $pesan = "*Permintaan Peminjaman BARANG (Multi-Item)*\n\n";
                $pesan .= "Halo Admin Aset Tetap,\n";
                $pesan .= "Pegawai atas nama *{$namaPegawai}* mengajukan peminjaman beberapa barang:\n\n";
                foreach ($collectedItems as $i => $item) {
                    $num = $i + 1;
                    $pesan .= "{$num}. {$item['aset']->nama_barang} (NUP: " . ($item['nup'] ?? '-') . ") - {$item['jumlah']} Unit\n";
                }
                $pesan .= "\n📅 *Tgl Pinjam:* {$request->tanggal_peminjaman}\n";
                $pesan .= "📅 *Tgl Kembali:* {$request->tanggal_pengembalian}\n";
                $pesan .= "📝 *Keperluan:* {$request->deskripsi_peruntukan}\n\n";
                $pesan .= "Silakan login ke sistem untuk melakukan review.";

                $noHpAdmin = preg_replace('/[^0-9]/', '', $adminAset->nomor_telepon);
                SendFonnteNotification::dispatch($noHpAdmin, $pesan);
            }

            return back()->with('success', 'Permintaan peminjaman ' . count($collectedItems) . ' aset berhasil diajukan dan sedang menunggu persetujuan Admin.');
        }

        // Skenario 2: Single-Item Legacy Fallback
        if ($request->has('kode_barang') && str_contains($request->kode_barang, '|')) {
            [$kodeBarang, $nupParsed] = explode('|', $request->kode_barang, 2);
            $request->merge([
                'kode_barang' => $kodeBarang,
                'nup' => $request->filled('nup') ? $request->nup : $nupParsed
            ]);
        }

        $request->validate([
            'kode_barang' => 'required|exists:aset_tetap,kode_barang',
            'nup' => 'nullable',
            'jumlah' => 'required|integer|min:1',
            'tanggal_peminjaman' => 'required|date|after_or_equal:today',
            'tanggal_pengembalian' => 'required|date|after_or_equal:tanggal_peminjaman',
            'deskripsi_peruntukan' => 'required|string',
        ], [
            'kode_barang.required' => 'Silakan pilih aset yang ingin dipinjam.',
            'kode_barang.exists' => 'Aset yang dipilih tidak ditemukan dalam sistem.',
            'jumlah.required' => 'Jumlah barang yang diminta wajib diisi.',
            'jumlah.min' => 'Jumlah barang minimal 1 unit.',
            'tanggal_peminjaman.required' => 'Tanggal peminjaman wajib diisi.',
            'tanggal_peminjaman.after_or_equal' => 'Tanggal peminjaman boleh hari ini atau setelahnya.',
            'tanggal_pengembalian.required' => 'Tanggal pengembalian wajib diisi.',
            'tanggal_pengembalian.after_or_equal' => 'Tanggal pengembalian harus sama dengan atau setelah tanggal peminjaman (tidak boleh mundur/lebih awal dari tanggal pinjam).',
            'deskripsi_peruntukan.required' => 'Tujuan penggunaan barang wajib diisi.',
        ]);

        // Cek bentrok
        $bentrokTanggal = DetailPeminjamanBarang::where('kode_barang', $request->kode_barang)
            ->when($request->filled('nup'), fn($q) => $q->where('nup', $request->nup))
            ->whereHas('peminjamanBarang', function($q) use ($statusAktifBarang, $request) {
                $q->whereIn('status', $statusAktifBarang)
                  ->whereDate('tanggal_peminjaman', '<=', $request->tanggal_pengembalian)
                  ->whereDate('tanggal_pengembalian', '>=', $request->tanggal_peminjaman);
            })->exists();

        if (!$bentrokTanggal) {
            $bentrokTanggal = PeminjamanBarang::where('kode_barang', $request->kode_barang)
                ->when($request->filled('nup'), fn($q) => $q->where('nup', $request->nup))
                ->whereIn('status', $statusAktifBarang)
                ->where(function ($query) use ($request) {
                    $query->whereDate('tanggal_peminjaman', '<=', $request->tanggal_pengembalian)
                          ->whereDate('tanggal_pengembalian', '>=', $request->tanggal_peminjaman);
                })->exists();
        }

        if ($bentrokTanggal) {
            $labelNup = $request->filled('nup') ? ' (NUP: ' . $request->nup . ')' : '';
            return back()->withErrors([
                'kode_barang' => 'Barang ini' . $labelNup . ' sudah memiliki jadwal peminjaman/booking pada rentang tanggal tersebut. Silakan pilih NUP lain atau cek Riwayat Peminjaman Anda di sebelah kanan.'
            ])->withInput();
        }

        $aset = AssetTetap::where('kode_barang', $request->kode_barang)
                ->when($request->nup, fn($q) => $q->where('nup', $request->nup))
                ->firstOrFail();

        $peminjaman = DB::transaction(function() use ($request, $aset) {
            $parent = PeminjamanBarang::create([
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

            DetailPeminjamanBarang::create([
                'peminjaman_barang_id' => $parent->id,
                'aset_tetap_id' => $aset->id,
                'kode_barang' => $aset->kode_barang,
                'nup' => $aset->nup,
                'nama_barang' => $aset->nama_barang,
                'merek' => $aset->merek,
                'kategori' => $aset->kategori,
                'jumlah' => $request->jumlah,
                'kondisi' => $aset->kondisi,
            ]);

            return $parent;
        });

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
        $peminjaman = PeminjamanBarang::with(['user', 'items'])->findOrFail($id);
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

        $view = view()->exists('pegawai.pengembalian_barang')
            ? 'pegawai.pengembalian_barang'
            : (view()->exists('pegawai.Pengembalian_barang') ? 'pegawai.Pengembalian_barang' : 'pegawai.pengembalian_barang');

        return view($view, compact('peminjamanAktif', 'riwayat'));
    }

    public function getPeminjamanJson($id)
    {
        $peminjaman = PeminjamanBarang::where('user_id', Auth::id())->findOrFail($id);
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

        $peminjaman = PeminjamanBarang::where('user_id', Auth::id())
            ->whereIn('status', ['disetujui', 'disetujui_admin', 'disetujui_kasubag'])
            ->findOrFail($request->peminjaman_barang_id);

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
        $data = PengembalianBarang::with('peminjamanBarang')
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function cancelPengembalian($id)
    {
        $pengembalian = PengembalianBarang::where('user_id', Auth::id())->findOrFail($id);
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
        $persediaan = Persediaan::select('id', 'kode_barang', 'nama_barang', 'jumlah', 'satuan', 'kategori')
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
        // 1. Skenario Multi-Item
        if ($request->has('items') && is_array($request->items)) {
            $request->validate([
                'items' => 'required|array|min:1',
                'items.*.persediaan_id' => 'required|exists:persediaan,id',
                'items.*.jumlah_diminta' => 'required|integer|min:1',
                'tanggal_permintaan' => 'required|date',
                'tujuan_penggunaan' => 'required|string|max:1000',
            ], [
                'items.required' => 'Daftar barang persediaan tidak boleh kosong.',
                'items.min' => 'Silakan pilih minimal 1 barang persediaan.',
                'tanggal_permintaan.required' => 'Tanggal permintaan wajib diisi.',
                'tujuan_penggunaan.required' => 'Tujuan penggunaan wajib diisi.',
            ]);

            $verifiedItems = [];
            foreach ($request->items as $it) {
                $persediaan = Persediaan::find($it['persediaan_id']);
                if (!$persediaan) {
                    return back()->withErrors(['items' => 'Barang tidak ditemukan!'])->withInput();
                }

                $jumlahSedangDiproses = PermintaanPersediaan::where('persediaan_id', $persediaan->id)
                    ->whereIn('status', ['pending', 'dalam_review'])
                    ->sum('jumlah_diminta');

                $detailSedangDiproses = DetailPermintaanPersediaan::where('persediaan_id', $persediaan->id)
                    ->whereHas('permintaanPersediaan', function($q) {
                        $q->whereIn('status', ['pending', 'dalam_review']);
                    })->sum('jumlah_diminta');

                $totalAntrean = max($jumlahSedangDiproses, $detailSedangDiproses);
                $sisaStokRiil = $persediaan->jumlah - $totalAntrean;

                if ((int)$it['jumlah_diminta'] > $sisaStokRiil) {
                    return back()->withErrors([
                        'items' => "Stok barang '{$persediaan->nama_barang}' yang bisa diminta saat ini hanya {$sisaStokRiil} {$persediaan->satuan}. (Terdapat {$totalAntrean} {$persediaan->satuan} dalam antrean pengajuan pegawai lain)."
                    ])->withInput();
                }

                $verifiedItems[] = [
                    'persediaan' => $persediaan,
                    'jumlah_diminta' => (int)$it['jumlah_diminta'],
                ];
            }

            $permintaan = DB::transaction(function () use ($request, $verifiedItems) {
                $first = $verifiedItems[0]['persediaan'];
                $totalDiminta = array_sum(array_column($verifiedItems, 'jumlah_diminta'));
                $itemCount = count($verifiedItems);

                $namaHeader = $itemCount > 1 
                    ? "{$first->nama_barang} (+ " . ($itemCount - 1) . " barang lainnya)" 
                    : $first->nama_barang;

                $parent = PermintaanPersediaan::create([
                    'kode_barang' => $first->kode_barang,
                    'nama_barang' => $namaHeader,
                    'persediaan_id' => $first->id,
                    'user_id' => Auth::id(),
                    'jumlah_diminta' => $totalDiminta,
                    'satuan' => $itemCount > 1 ? 'Beragam' : $first->satuan,
                    'tanggal_permintaan' => $request->tanggal_permintaan,
                    'tujuan_penggunaan' => $request->tujuan_penggunaan,
                    'status' => 'pending',
                ]);

                foreach ($verifiedItems as $v) {
                    $p = $v['persediaan'];
                    DetailPermintaanPersediaan::create([
                        'permintaan_persediaan_id' => $parent->id,
                        'persediaan_id' => $p->id,
                        'kode_barang' => $p->kode_barang,
                        'nama_barang' => $p->nama_barang,
                        'satuan' => $p->satuan,
                        'jumlah_diminta' => $v['jumlah_diminta'],
                    ]);
                }

                return $parent;
            });

            $adminPersediaan = User::where('role', 'adminpersediaan')->first();
            if ($adminPersediaan && $adminPersediaan->nomor_telepon) {
                $namaPegawai = Auth::user()->name;
                $pesan = "*Permintaan PERSEDIAAN Baru (Multi-Item)*\n\n";
                $pesan .= "Halo Admin Persediaan,\n";
                $pesan .= "Pegawai atas nama *{$namaPegawai}* mengajukan permintaan beberapa barang persediaan:\n\n";
                foreach ($verifiedItems as $idx => $v) {
                    $num = $idx + 1;
                    $pesan .= "{$num}. {$v['persediaan']->nama_barang} - {$v['jumlah_diminta']} {$v['persediaan']->satuan}\n";
                }
                $pesan .= "\n📝 *Keperluan:* {$request->tujuan_penggunaan}\n";
                $pesan .= "📅 *Tgl Permintaan:* {$request->tanggal_permintaan}\n\n";
                $pesan .= "Silakan login ke sistem untuk melakukan review permintaan.";

                $noHpAdmin = preg_replace('/[^0-9]/', '', $adminPersediaan->nomor_telepon);
                SendFonnteNotification::dispatch($noHpAdmin, $pesan);
            }

            return redirect()->route('pegawai.permintaan-persediaan')
                ->with('success', 'Permintaan ' . count($verifiedItems) . ' item persediaan berhasil dikirim! Menunggu persetujuan Admin Persediaan.');
        }

        // 2. Skenario Single-Item Fallback
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

        $permintaan = DB::transaction(function() use ($request, $persediaan) {
            $parent = PermintaanPersediaan::create([
                'kode_barang' => $persediaan->kode_barang,           
                'nama_barang' => $persediaan->nama_barang,
                'persediaan_id' => $persediaan->id,               
                'user_id' => Auth::id(),
                'jumlah_diminta' => $request->jumlah_diminta,
                'satuan'=> $request->satuan ?? $persediaan->satuan,
                'tanggal_permintaan' => $request->tanggal_permintaan,
                'tujuan_penggunaan' => $request->tujuan_penggunaan,
                'status' => 'pending',
            ]);

            DetailPermintaanPersediaan::create([
                'permintaan_persediaan_id' => $parent->id,
                'persediaan_id' => $persediaan->id,
                'kode_barang' => $persediaan->kode_barang,
                'nama_barang' => $persediaan->nama_barang,
                'satuan' => $request->satuan ?? $persediaan->satuan,
                'jumlah_diminta' => $request->jumlah_diminta,
            ]);

            return $parent;
        });

        $adminPersediaan = User::where('role', 'adminpersediaan')->first();
        if ($adminPersediaan && $adminPersediaan->nomor_telepon) {
            $namaPegawai = Auth::user()->name;

            $pesan = "*Permintaan PERSEDIAAN Baru*\n\n";
            $pesan .= "Halo Admin Persediaan,\n";
            $pesan .= "Pegawai atas nama *{$namaPegawai}* mengajukan permintaan barang persediaan:\n\n";
            $pesan .= "📦 *Barang:* {$persediaan->nama_barang}\n";
            $pesan .= "🔖 *Kode:* {$persediaan->kode_barang}\n";
            $pesan .= "🔢 *Jumlah:* {$request->jumlah_diminta}\n";
            $pesan .= "📝 *Keperluan:* {$request->tujuan_penggunaan}\n\n";
            $pesan .= "Silakan login ke sistem untuk melakukan review permintaan.";

            $noHpAdmin = preg_replace('/[^0-9]/', '', $adminPersediaan->nomor_telepon);
            SendFonnteNotification::dispatch($noHpAdmin, $pesan);
        }

        return redirect()->route('pegawai.permintaan-persediaan')
            ->with('success', 'Permintaan berhasil dikirim! Menunggu persetujuan Admin Persediaan.');
    }

    public function detailPermintaanPersediaan($id)
    {
        $permintaan = PermintaanPersediaan::with(['persediaan', 'items', 'user', 'reviewedBy', 'approvedByKasubag'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $permintaan->admin_approved_by = $permintaan->reviewedBy?->name;
        $permintaan->kasubag_approved_by = $permintaan->approvedByKasubag?->name;
        $permintaan->komentar_admin = $permintaan->komentar;

        return response()->json([
            'success' => true,
            'data' => $permintaan
        ]);
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
        $permintaan = PermintaanPersediaan::with(['persediaan', 'user'])
            ->where('user_id', Auth::id())
            ->find($id);

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

        try {
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

            $asetQuery = AssetTetap::where('kode_barang', $request->kode_barang)
                ->when($request->nup, function ($query) use ($request) {
                    return $query->where('nup', $request->nup);
                });

            if (Schema::hasTable('detail_kendaraan')) {
                $asetQuery->with('detailKendaraan');
            }

            $aset = $asetQuery->first();

            if (!$aset) {
                return back()->withErrors(['kode_barang' => 'Data aset kendaraan tidak ditemukan.'])->withInput();
            }

            $peminjamanData = [
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
            ];

            // Cek apakah kolom snapshot kendaraan ada di tabel database sebelum menyimpan
            if (Schema::hasColumn('peminjaman_kendaraan', 'nomor_polisi_saat_pinjam')) {
                $peminjamanData['nomor_polisi_saat_pinjam'] = $aset->detailKendaraan->nomor_polisi ?? '-';
                $peminjamanData['no_bpkb_saat_pinjam']      = $aset->detailKendaraan->no_bpkb ?? '-';
                $peminjamanData['nomor_rangka_saat_pinjam'] = $aset->detailKendaraan->nomor_rangka ?? '-';
                $peminjamanData['nomor_mesin_saat_pinjam']  = $aset->detailKendaraan->nomor_mesin ?? '-';
            }

            PeminjamanKendaraan::create($peminjamanData);

            // Kirim notifikasi WA (dibungkus try-catch agar kegagalan queue/WA tidak mengagalkan simpan)
            try {
                $adminAset = User::where('role', 'adminasettetap')->first();
                if ($adminAset && $adminAset->nomor_telepon) {
                    $namaPegawai = Auth::user()->name;

                    $pesan = "*Permintaan Peminjaman KENDARAAN Baru*\n\n";
                    $pesan .= "Halo Admin Aset Tetap,\n";
                    $pesan .= "Pegawai atas nama *{$namaPegawai}* mengajukan peminjaman kendaraan dinas:\n\n";
                    $merek = $aset->merek ? " ({$aset->merek})" : "";
                    $pesan .= "🚗 *Kendaraan:* {$aset->nama_barang}{$merek}\n";
                    
                    $platNomor = $aset->detailKendaraan->nomor_polisi ?? '-';
                    $pesan .= "🔢 *Plat Nomor:* {$platNomor}\n";
                    
                    $pesan .= "📅 *Tgl Pinjam:* {$request->tanggal_peminjaman}\n";
                    $pesan .= "📅 *Tgl Kembali:* {$request->tanggal_pengembalian}\n";
                    $pesan .= "📝 *Keperluan:* {$request->deskripsi_peruntukan}\n\n";
                    $pesan .= "Silakan login ke sistem untuk melakukan review.";

                    $noHpAdmin = preg_replace('/[^0-9]/', '', $adminAset->nomor_telepon);
                    SendFonnteNotification::dispatch($noHpAdmin, $pesan);
                }
            } catch (\Throwable $waError) {
                Log::warning('Gagal dispatch notifikasi WA peminjaman kendaraan: ' . $waError->getMessage());
            }

            return back()->with('success', 'Permintaan peminjaman berhasil dikirim.');

        } catch (\Throwable $e) {
            Log::error('Error storePeminjamanKendaraan: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return back()->withErrors(['error' => 'Gagal memproses peminjaman: ' . $e->getMessage()])->withInput();
        }
    }

    public function showPeminjamanKendaraan($id)
    {
        $data = PeminjamanKendaraan::with('user')
            ->where('user_id', Auth::id())
            ->findOrFail($id);
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
        $peminjaman = PeminjamanKendaraan::where('user_id', Auth::id())->findOrFail($id);
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

        $peminjaman = PeminjamanKendaraan::where('user_id', Auth::id())
            ->where('status', 'disetujui')
            ->findOrFail($request->peminjaman_kendaraan_id);

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
        $data = PengembalianKendaraan::with('peminjamanKendaraan')
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function cancelPengembalianKendaraan($id)
    {
        $pengembalian = PengembalianKendaraan::where('user_id', Auth::id())->findOrFail($id);
        
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
