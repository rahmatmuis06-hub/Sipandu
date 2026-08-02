<?php

namespace App\Http\Controllers;

use App\Exports\AsetTetapTemplateExport;
use App\Imports\AsetTetapImport;
use App\Jobs\SendFonnteNotification;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log; // ✅ ADD
use Illuminate\Support\Facades\DB;
use App\Models\{
    User,
    Gedung,
    AssetTetap,
    TransaksiMasukAssetTetap,
    TransaksiKeluarAssetTetap,
    MutasiBarang,
    Persediaan,
    PermintaanPersediaan,
    PeminjamanBarang,
    PengembalianBarang,
    PeminjamanKendaraan,
    PengembalianKendaraan,
    AjuanMutasi,
    DetailKendaraan
};
use App\Services\FonnteService;

class AdminAsettetapController extends Controller
{
    // ========== DASHBOARD ==========
    public function dashboard()
    {
        // return view('adminasettetap.dashbord');
        $stats = [
            'totalAset' => AssetTetap::count(),
            'transaksiMasuk' => TransaksiMasukAssetTetap::count(),
            'peminjamanBarangAktif' => PeminjamanBarang::where('status', 'disetujui')->count(),
            'pengembalianPending' => PengembalianBarang::where('status_verifikasi', 'pending')->count(),
            'kendaraanDipinjam' => PeminjamanKendaraan::where('status', 'disetujui')->count(),
        ];

        $recentPengembalian = PengembalianBarang::with('peminjamanBarang', 'user')
            ->where('status_verifikasi', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        return view('adminasettetap.dashbord', compact('stats', 'recentPengembalian'));
    }

    

    // ========== DATA ASET TETAP - INDEX (sudah ada, update query) ==========
    public function dataAsetTetap(Request $request)
    {
        $query = AssetTetap::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($subQ) use ($request) {
                    $subQ->where('nama_barang', 'like', "%{$request->search}%")
                        ->orWhere('kode_barang', 'like', "%{$request->search}%")
                        ->orWhere('nup', 'like', "%{$request->search}%")
                        ->orWhere('merek', 'like', "%{$request->search}%");
                });
            })
            ->when($request->filled('kondisi'), function ($q) use ($request) {
                $q->where('kondisi', $request->kondisi);
            })
            ->orderBy('created_at', 'desc');

        $asetTetap = $query->paginate(15)->withQueryString();

        $asetTetapOptions = AssetTetap::select('id', 'kode_barang', 'nama_barang', 'nup', 'lokasi', 'kondisi', 'merek', 'tanggal_perolehan', 'nilai_perolehan')
            ->where(function ($q) {
                $q->where('status', 'Tersedia')
                    ->orWhereNull('status');
            })
            ->orderBy('nama_barang', 'asc')
            ->orderBy('kode_barang', 'asc')
            ->get();

        // ✅ FIXED: Pass asetTetapOptions to view
        return view('adminasettetap.data_asettetap', compact('asetTetap', 'asetTetapOptions'));
    }

    // ========== DOWNLOAD TEMPLATE EXCEL ==========
    public function downloadTemplate()
    {
        // Akan langsung mendownload file berekstensi .xlsx dengan desain dari class AsetTetapTemplateExport
        return Excel::download(new AsetTetapTemplateExport, 'Template_Import_Aset_Tetap.xlsx');
    }

    // ========== PROSES IMPORT EXCEL ==========
    public function importAset(Request $request)
    {
        
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file_excel.required' => 'Silakan pilih file Excel terlebih dahulu.',
            'file_excel.mimes'    => 'Format file harus .xlsx, .xls, atau .csv.',
            'file_excel.max'      => 'Ukuran file maksimal 5MB.'
        ]);

        try {
            Excel::import(new AsetTetapImport, $request->file('file_excel'));

            return redirect()->route('adminasettetap.data-aset-tetap')
                ->with('success', 'Data Aset Tetap berhasil diimport secara massal!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            // Menangkap error jika header file Excel tidak sesuai standar
            return back()->with('error', 'Gagal mengimpor file! Pastikan format tabel sesuai dengan template.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }

        

    return redirect()->route('adminasettetap.data-aset-tetap')
        ->with('success', 'Data Aset Tetap berhasil diimport secara massal!');
    }

    // ========== CREATE ==========
    public function create()
    {
        return view('adminasettetap.data_asettetap_create');
    }

    // ========== w2 DATA ASET TETAP (Method yang hilang) ==========
    public function storeDataAsetTetap(Request $request)
    {
        $validated = $request->validate([
            'tanggal_input' => 'required|date|before_or_equal:today',
            'kode_barang' => 'required|string|max:50',
            'nup' => 'nullable|string|max:50',
            'nama_barang' => 'required|string|max:255',
            'merek' => 'nullable|string|max:100',
            'kategori' => 'required|string|max:100',
            'tanggal_perolehan' => 'nullable|date',
            'nilai_perolehan' => 'required|numeric|min:0',
            'kondisi' => 'required|in:baik,rusak ringan,rusak berat',
            'lokasi' => 'required|string|max:100',
            'jumlah' => 'required|integer|min:1',
            'status' => 'required|in:Tersedia,Keluar,Rusak,Dipinjam',
            'nomor_polisi' => 'nullable|string|max:50',
            'no_bpkb' => 'nullable|string|max:100',
            'nomor_rangka' => 'nullable|string|max:100',
            'nomor_mesin' => 'nullable|string|max:100',
        ]);

        
        // 1. Simpan Data Aset Tetap Utama
        $aset = AssetTetap::create($request->only([
            'tanggal_input', 'kode_barang', 'nup', 'nama_barang', 'merek', 
            'kategori', 'tanggal_perolehan', 'nilai_perolehan', 'kondisi', 
            'lokasi', 'jumlah', 'status'
        ]));

        // 2. LOGIKA PENYIMPANAN DETAIL KENDARAAN (Tambahkan blok ini)
        $kat = strtolower(trim($request->kategori));
        if (str_contains($kat, 'kendaraan') || str_contains($kat, 'angkutan bermotor')) {
            \App\Models\DetailKendaraan::create([
                'aset_tetap_id' => $aset->id,
                'nomor_polisi'   => $request->nomor_polisi,
                'no_bpkb'        => $request->no_bpkb,
                'nomor_rangka'   => $request->nomor_rangka,
            'nomor_mesin'    => $request->nomor_mesin,
        ]);
    }
        AssetTetap::create($validated);

        return redirect()->route('adminasettetap.data-aset-tetap')
            ->with('success', 'Aset tetap berhasil ditambahkan!');
    }

    // ========== SHOW (Detail) ==========
    public function showDataAsetTetap(AssetTetap $aset)
    {
        return view('adminasettetap.data_asettetap_show', compact('aset'));
    }

    // ========== EDIT ==========
    public function editDataAsetTetap(AssetTetap $aset)
    {
        return view('adminasettetap.data_asettetap_edit', compact('aset'));
    }

    // ========== UPDATE ==========
    public function updateDataAsetTetap(Request $request, $id)
    {
        $aset = \App\Models\AssetTetap::findOrFail($id);

        // 1. Validasi Data (Sekarang NUP yang wajib unik, Kode Barang bebas sama)
        $validated = $request->validate([
            'tanggal_input'     => 'required|date',
            // // 'kode_barang'       => 'required|string|max:50', // Kode barang bebas duplikat
            // // 'nup'               => [
            // //     'required', 
            // //     'string', 
            // //     'max:50', 
            // //     Rule::unique('aset_tetap', 'nup')->ignore($aset->id)
            // ], // NUP dikunci agar tidak boleh ada yang sama
            'nama_barang'       => 'required|string|max:255',
            'merek'             => 'nullable|string|max:100',
            'kategori'          => 'required|string|max:100',
            'tanggal_perolehan' => 'nullable|date',
            'nilai_perolehan'   => 'nullable|numeric|min:0',
            'kondisi'           => 'required|in:baik,rusak ringan,rusak berat',
            'lokasi'            => 'nullable|string|max:100',
            'jumlah'            => 'required|integer|min:1',
            'status'            => 'required|in:Tersedia,Keluar,Rusak,Dipinjam',
            'nomor_polisi'      => 'nullable|string|max:50',
            'no_bpkb'           => 'nullable|string|max:100',
            'nomor_rangka'      => 'nullable|string|max:100',
            'nomor_mesin'       => 'nullable|string|max:100',
        ]);

        // 2. Update Data Aset Tetap Utama
        $aset->update([
            'tanggal_input'     => $request->tanggal_input,
            'kode_barang'       => $request->kode_barang,
            'nup'               => $request->nup,
            'nama_barang'       => $request->nama_barang,
            'merek'             => $request->merek,
            'kategori'          => $request->kategori,
            'tanggal_perolehan' => $request->tanggal_perolehan,
            'nilai_perolehan'   => $request->nilai_perolehan,
            'kondisi'           => $request->kondisi,
            'lokasi'            => $request->lokasi,
            'jumlah'            => $request->jumlah,
            'status'            => $request->status,
        ]);

        // 3. Update Data Kendaraan
        $kat = strtolower(trim($request->kategori));

        if (str_contains($kat, 'kendaraan') || str_contains($kat, 'angkutan bermotor')) {
            
            // DETEKTOR 1: JIKA MASUK KE SINI, BERARTI KATEGORI BENAR
            // dd('VALIDASI LOLOS & KATEGORI COCOK!', $request->all());

            \App\Models\DetailKendaraan::updateOrCreate(
                ['aset_tetap_id' => $aset->id],
                [
                    'nomor_polisi' => $request->nomor_polisi,
                    'no_bpkb'      => $request->no_bpkb,
                    'nomor_rangka' => $request->nomor_rangka,
                    'nomor_mesin'  => $request->nomor_mesin,
                ]
            );
        } else {
            
            // DETEKTOR 2: JIKA MASUK KE SINI, BERARTI NAMA KATEGORI TIDAK DIKENALI
            // dd('GAGAL MENYIMPAN! KATEGORI BUKAN KENDARAAN. KATEGORI SAAT INI ADALAH:', $kat);
            
            if ($aset->detailKendaraan) {
                $aset->detailKendaraan()->delete();
            }
        }

        return redirect()->route('adminasettetap.data-aset-tetap')
            ->with('success', 'Aset tetap beserta detailnya berhasil diperbarui!');
    }

    // ========== DESTROY ==========
    public function destroyDataAsetTetap(AssetTetap $aset)
    {
        // Cek apakah aset sedang terikat dengan tabel lain
        if ($aset->transaksiKeluar()->exists() || $aset->peminjamanBarang()->exists() || $aset->mutasiBarang()->exists()) {
            return back()->withErrors(['error' => 'Aset tidak bisa dihapus permanen karena memiliki riwayat transaksi/peminjaman. Jika barang rusak/hilang, silakan gunakan fitur Transaksi Keluar.']);
        }

        $aset->delete();
        return redirect()->route('adminasettetap.data-aset-tetap')->with('success', 'Aset tetap berhasil dihapus!');
    }
    

    // ========== TRANSAKSI MASUK ✅ FIXED ==========
    public function TransaksiMasuk(Request $request)
    {
        $kondisiOptions = ['baik', 'rusak_ringan', 'rusak_berat'];
        $kategoriOptions = AssetTetap::select('kategori')
            ->distinct()
            ->whereNotNull('kategori')
            ->pluck('kategori')
            ->filter()
            ->values();

        // ✅ FIXED: Filter null + eager loading + whereNotNull
        $query = TransaksiMasukAssetTetap::with(['user'])
            ->whereNotNull('id') // Pastikan ada data
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($subQ) use ($request) {
                    $subQ->where('kode_barang', 'like', "%{$request->search}%")
                        ->orWhere('nup', 'like', "%{$request->search}%")
                        ->orWhere('nama_barang', 'like', "%{$request->search}%");
                });
            })
            ->when($request->filled('kondisi'), function ($q) use ($request) {
                $q->where('kondisi', $request->kondisi);
            })
            ->when($request->filled('kategori'), function ($q) use ($request) {
                $q->where('kategori', 'like', "%{$request->kategori}%");
            })
            ->orderBy('created_at', 'desc');

        $transaksi = $query->paginate(15)->withQueryString();

        // ✅ FIXED: Transform dengan null safety
        $transaksi->getCollection()->transform(function ($item) {
            if (!$item) return null;

            $tanggalInput = $item->tanggal_input ?? $item->created_at;
            $item->tanggal_input_formatted = $tanggalInput
                ? Carbon::parse($tanggalInput)->format('d/m/Y') : '-';

            try {
                $item->tanggal_perolehan_formatted = $item->tanggal_perolehan
                    ? Carbon::parse($item->tanggal_perolehan)->format('d/m/Y')
                    : '-';
            } catch (\Exception $e) {
                $item->tanggal_perolehan_formatted = '-';
                Log::warning("Error parsing tanggal_perolehan: " . $item->id, ['error' => $e->getMessage()]);
            }

            $item->nilai_format = $item->nilai_perolehan
                ? 'Rp ' . number_format($item->nilai_perolehan, 0, ',', '.')
                : '-';

            // Kondisi badge
            $kondisi = $item->kondisi ?? 'unknown';
            $item->kondisi_badge = match ($kondisi) {
                'baik' => ['color' => 'success', 'icon' => 'fa-check-circle', 'text' => 'Baik'],
                'rusak_ringan' => ['color' => 'warning', 'icon' => 'fa-exclamation-triangle', 'text' => 'Rusak Ringan'],
                'rusak_berat' => ['color' => 'danger', 'icon' => 'fa-times-circle', 'text' => 'Rusak Berat'],
                default => ['color' => 'secondary', 'icon' => 'fa-question-circle', 'text' => 'Tidak Diketahui']
            };

            return $item;
        })->reject(fn($item) => is_null($item)); // ✅ Remove null items

        return view('adminasettetap.transaksi_masuk', compact('transaksi', 'kondisiOptions', 'kategoriOptions'));
    }

    // CRUD Transaksi Masuk
    public function createTransaksiMasuk()
    {
        return view('adminasettetap.transaksi_masuk_create');
    }

    public function showTransaksiMasuk(TransaksiMasukAssetTetap $transaksi)
    {
        return view('adminasettetap.transaksi_masuk_show', compact('transaksi'));
    }

    public function editTransaksiMasuk(TransaksiMasukAssetTetap $transaksi)
    {
        return view('adminasettetap.transaksi_masuk_edit', compact('transaksi'));
    }

    // ========== SIMPAN TRANSAKSI MASUK & TAMBAH KE MASTER ASET ==========
    public function storeTransaksiMasuk(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|string|max:100',
            'nup' => 'required|string|max:100|unique:transaksi_masuk_aset_tetap,nup',
            'nama_barang' => 'required|string|max:255',
            'merek' => 'nullable|string|max:100',
            'kategori' => 'required|string|max:100',
            'tanggal_perolehan' => 'nullable|date',
            'nilai_perolehan' => 'nullable|numeric|min:0',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'lokasi' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'tanggal_input' => 'nullable|date', // Tambahkan validasi tanggal input
        ]);

        try {
            DB::beginTransaction();

            // 1. Simpan barang sebagai data baru di tabel master AssetTetap
            $aset = AssetTetap::create([
                'kode_barang' => $validated['kode_barang'],
                'nup' => $validated['nup'],
                'nama_barang' => $validated['nama_barang'],
                'merek' => $validated['merek'],
                'kategori' => $validated['kategori'],
                'tanggal_perolehan' => $validated['tanggal_perolehan'],
                'nilai_perolehan' => $validated['nilai_perolehan'],
                'kondisi' => $validated['kondisi'],
                'lokasi' => $validated['lokasi'],
                'jumlah' => $validated['jumlah'],
                'tanggal_input' => $validated['tanggal_input'] ?? now(),
                'status' => 'Tersedia' // Aset langsung tersedia
            ]);

            // 2. Simpan histori ke tabel transaksi_masuk_aset_tetap (kaitkan ID Master)
            $validated['aset_tetap_id'] = $aset->id; 
            TransaksiMasukAssetTetap::create($validated);

            DB::commit();

            return redirect()->route('adminasettetap.transaksi-masuk')
                ->with('success', 'Barang baru berhasil ditambahkan ke Master Aset Tetap dan dicatat sebagai Transaksi Masuk!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()])->withInput();
        }
    }

    // ========== UPDATE TRANSAKSI MASUK SINKRON MASTER ASET ==========
    public function updateTransaksiMasuk(Request $request, TransaksiMasukAssetTetap $transaksi)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|string|max:100',
            'nup' => 'required|string|max:100|unique:transaksi_masuk_aset_tetap,nup,' . $transaksi->id,
            'nama_barang' => 'required|string|max:255',
            'merek' => 'nullable|string|max:100',
            'kategori' => 'required|string|max:100',
            'tanggal_perolehan' => 'nullable|date',
            'nilai_perolehan' => 'nullable|numeric|min:0',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'lokasi' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'tanggal_input' => 'nullable|date',
        ]);

        try {
            DB::beginTransaction();

            // 1. Update data di histori Transaksi Masuk
            $transaksi->update($validated);

            // 2. Sinkronisasikan perubahan ke tabel Master Aset Tetap
            if ($transaksi->aset_tetap_id) {
                $aset = AssetTetap::find($transaksi->aset_tetap_id);
                if ($aset) {
                    $aset->update([
                        'kode_barang' => $validated['kode_barang'],
                        'nup' => $validated['nup'],
                        'nama_barang' => $validated['nama_barang'],
                        'merek' => $validated['merek'],
                        'kategori' => $validated['kategori'],
                        'tanggal_perolehan' => $validated['tanggal_perolehan'],
                        'nilai_perolehan' => $validated['nilai_perolehan'],
                        'kondisi' => $validated['kondisi'],
                        'lokasi' => $validated['lokasi'],
                        'jumlah' => $validated['jumlah'],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('adminasettetap.transaksi-masuk')
                ->with('success', 'Histori transaksi dan data master aset tetap berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal mengupdate data: ' . $e->getMessage()])->withInput();
        }
    }

    // ========== HAPUS TRANSAKSI MASUK & MASTER ASET ==========
    public function destroyTransaksiMasuk(TransaksiMasukAssetTetap $transaksi)
    {
        try {
            DB::beginTransaction();
            
            // Hapus Master Aset Tetap nya juga, HANYA JIKA barang belum pernah digunakan
            if ($transaksi->aset_tetap_id) {
                $aset = AssetTetap::find($transaksi->aset_tetap_id);
                if ($aset) {
                    // Proteksi: cegah penghapusan jika barang sedang dipinjam atau sudah dihapuskan
                    if ($aset->status != 'Tersedia') {
                        return back()->withErrors(['error' => 'Transaksi tidak dapat dibatalkan. Barang sedang dipinjam atau sudah dikeluarkan.']);
                    }
                    $aset->delete();
                }
            }
            
            $transaksi->delete();

            DB::commit();

            return redirect()->route('adminasettetap.transaksi-masuk')
                ->with('success', 'Transaksi masuk dibatalkan. Barang dihapus dari data Master Aset Tetap!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menghapus data: ' . $e->getMessage()]);
        }
    }
    
    // ========== INDEX TRANSAKSI KELUAR ASET TETAP ==========
    public function TransaksiKeluar(Request $request)
    {
        $query = TransaksiKeluarAssetTetap::with(['asetTetap', 'user'])
            ->when($request->filled('search'), function($q) use ($request) {
                $q->where(function($subQ) use ($request) {
                    $subQ->where('kode_barang', 'like', "%{$request->search}%")
                         ->orWhere('nup', 'like', "%{$request->search}%")
                         ->orWhere('nama_barang', 'like', "%{$request->search}%")
                         ->orWhere('nomor_sk', 'like', "%{$request->search}%")
                         ->orWhere('keterangan', 'like', "%{$request->search}%");
                });
            })
            ->when($request->filled('status'), function($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->orderBy('tanggal_input', 'desc');

        $transaksi = $query->paginate(15)->withQueryString();

        // ✅ SINKRON: Menggunakan nama suffix '_format' agar sesuai dengan template Blade
        $transaksi->getCollection()->transform(function ($item) {
            $item->tanggal_input_format = $item->tanggal_input ? $item->tanggal_input->format('d/m/Y') : '-';
            $item->tanggal_sk_format = $item->tanggal_sk ? $item->tanggal_sk->format('d/m/Y') : '-';
            $item->tanggal_perolehan_format = $item->tanggal_perolehan ? $item->tanggal_perolehan->format('d/m/Y') : '-';
            $item->nilai_format = $item->nilai_perolehan ? 'Rp ' . number_format($item->nilai_perolehan, 0, ',', '.') : 'Rp 0';
            return $item;
        });

        // ✅ PERBAIKAN: Ambil yang 'Tersedia', 'NULL', ATAU yang statusnya sudah 'Keluar' agar saat edit tetap terbaca
        $asetTetapOptions = AssetTetap::select('id', 'kode_barang', 'nama_barang', 'nup')
            ->whereIn('status', ['Tersedia', 'Keluar'])
            ->orWhereNull('status')
            ->orderBy('nama_barang')
            ->get();


        return view('adminasettetap.transaksi_keluar', compact('transaksi', 'asetTetapOptions'));
    }

    // ========== SIMPAN TRANSAKSI KELUAR BARU ==========
    public function storeTransaksiKeluar(Request $request)
    {
        $validated = $request->validate([
            'aset_tetap_id' => 'required|exists:aset_tetap,id',
            'tanggal_input' => 'required|date|before_or_equal:today',
            'nomor_sk' => 'nullable|string|max:100',
            'tanggal_sk' => 'nullable|date|after_or_equal:tanggal_input',
            'keterangan' => 'nullable|string|max:1000'
        ]);

        try {
            DB::beginTransaction();

            $aset = AssetTetap::findOrFail($validated['aset_tetap_id']);
            
            if ($aset->status !== 'Tersedia' && !is_null($aset->status)) {
                return back()->withErrors(['aset_tetap_id' => 'Aset tidak tersedia untuk transaksi keluar']);
            }

            TransaksiKeluarAssetTetap::create([
                'tanggal_input' => $validated['tanggal_input'],
                'aset_tetap_id' => $validated['aset_tetap_id'],
                'kode_barang' => $aset->kode_barang,
                'nup' => $aset->nup,
                'nama_barang' => $aset->nama_barang,
                'merek' => $aset->merek,
                'tanggal_perolehan' => $aset->tanggal_perolehan,
                'nilai_perolehan' => $aset->nilai_perolehan,
                'lokasi' => $aset->lokasi,
                'nomor_sk' => $validated['nomor_sk'],
                'tanggal_sk' => $validated['tanggal_sk'],
                'keterangan' => $validated['keterangan'],
                'user_id' => Auth::id(),
                'status' => 'aktif'
            ]);

            // Ubah status aset utama menjadi Keluar
            $aset->update(['status' => 'Keluar']);

            DB::commit();
            return redirect()->route('adminasettetap.transaksi-keluar')
                ->with('success', 'Transaksi keluar aset tetap berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }

    // ========== EDIT TRANSAKSI KELUAR (JSON DATA FETCH) ==========
    public function editJson($id)
    {
        try {
            $transaksi = TransaksiKeluarAssetTetap::findOrFail($id);
            return response()->json([
                'aset_tetap_id' => $transaksi->aset_tetap_id,
                'tanggal_input' => $transaksi->tanggal_input ? $transaksi->tanggal_input->format('Y-m-d') : '',
                'nomor_sk' => $transaksi->nomor_sk,
                'tanggal_sk' => $transaksi->tanggal_sk ? $transaksi->tanggal_sk->format('Y-m-d') : '',
                'keterangan' => $transaksi->keterangan,
                'status' => $transaksi->status,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Transaksi tidak ditemukan'], 404);
        }
    }

    // ========== UPDATE DATA TRANSAKSI KELUAR ==========
    public function updateTransaksiKeluar(Request $request, TransaksiKeluarAssetTetap $transaksi)
    {
        $validated = $request->validate([
            'aset_tetap_id' => 'required|exists:aset_tetap,id',
            'tanggal_input' => 'required|date|before_or_equal:today',
            'nomor_sk' => 'nullable|string|max:100',
            'tanggal_sk' => 'nullable|date|after_or_equal:tanggal_input',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Jika aset berubah, kembalikan status aset lama dan kunci aset baru
            if ($transaksi->aset_tetap_id != $validated['aset_tetap_id']) {
                $asetLama = AssetTetap::find($transaksi->aset_tetap_id);
                if ($asetLama) {
                    $asetLama->update(['status' => 'Tersedia']);
                }

                $asetBaru = AssetTetap::findOrFail($validated['aset_tetap_id']);
                $asetBaru->update(['status' => 'Keluar']);
            } else {
                $asetBaru = AssetTetap::find($transaksi->aset_tetap_id);
            }

            $updateData = [
                'aset_tetap_id' => $validated['aset_tetap_id'],
                'tanggal_input' => $validated['tanggal_input'],
                'nomor_sk' => $validated['nomor_sk'],
                'tanggal_sk' => $validated['tanggal_sk'],
                'keterangan' => $validated['keterangan'],
            ];

            if ($asetBaru) {
                $updateData = array_merge($updateData, [
                    'kode_barang' => $asetBaru->kode_barang,
                    'nup' => $asetBaru->nup,
                    'nama_barang' => $asetBaru->nama_barang,
                    'merek' => $asetBaru->merek,
                    'tanggal_perolehan' => $asetBaru->tanggal_perolehan,
                    'nilai_perolehan' => $asetBaru->nilai_perolehan,
                    'lokasi' => $asetBaru->lokasi,
                ]);
            }

            $transaksi->update($updateData);

            DB::commit();
            return redirect()->route('adminasettetap.transaksi-keluar')
                ->with('success', 'Transaksi keluar aset tetap berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal mengupdate data: ' . $e->getMessage()]);
        }
    }

    // ========== HAPUS/BATALKAN TRANSAKSI KELUAR ==========
    public function destroyTransaksiKeluar(TransaksiKeluarAssetTetap $transaksi)
    {
        try {
            DB::beginTransaction();
            
            $aset = AssetTetap::find($transaksi->aset_tetap_id);
            if ($aset) {
                $aset->update(['status' => 'Tersedia']);
            }
            
            $transaksi->delete();
            
            DB::commit();
            return redirect()->route('adminasettetap.transaksi-keluar')
                ->with('success', 'Transaksi keluar berhasil dihapus! Barang dikembalikan ke status Tersedia.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error delete transaksi keluar: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Gagal menghapus data: ' . $e->getMessage()]);
        }
    }

    // ========== AJAX METHOD: AMBIL DATA ASAL UNTUK AUTO-FILL FORM ==========
    public function getAsetData($id)
    {
        try {
            $aset = AssetTetap::findOrFail($id);
            return response()->json([
                'kode_barang' => $aset->kode_barang,
                'nup' => $aset->nup,
                'nama_barang' => $aset->nama_barang,
                'merek' => $aset->merek ?? '-',
                'tanggal_perolehan' => $aset->tanggal_perolehan ? $aset->tanggal_perolehan->format('Y-m-d') : '',
                'nilai_perolehan' => $aset->nilai_perolehan ?? 0,
                'lokasi' => $aset->lokasi ?? '-'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Aset tidak ditemukan'], 404);
        }
    }

    // ========== AJAX METHOD: RENDER DETAIL DATA MODAL VIEW ==========
    public function showWithAset($transaksiId)
    {
        try {
            $transaksi = TransaksiKeluarAssetTetap::with('asetTetap')->findOrFail($transaksiId);
            return response()->json([
                'kode_barang' => $transaksi->kode_barang ?? '-',
                'nup' => $transaksi->nup ?? '-',
                'nama_barang' => $transaksi->nama_barang ?? '-',
                'merek' => $transaksi->merek ?? '-',
                'tanggal_perolehan_format' => $transaksi->tanggal_perolehan ? $transaksi->tanggal_perolehan->format('d/m/Y') : '-',
                'nilai_format' => $transaksi->nilai_perolehan ? 'Rp ' . number_format($transaksi->nilai_perolehan, 0, ',', '.') : '-',
                'lokasi' => $transaksi->lokasi ?? '-',
                'tanggal_input_format' => $transaksi->tanggal_input ? $transaksi->tanggal_input->format('d/m/Y') : '-',
                'nomor_sk' => $transaksi->nomor_sk ?? '-',
                'tanggal_sk_format' => $transaksi->tanggal_sk ? $transaksi->tanggal_sk->format('d/m/Y') : '-',
                'keterangan' => $transaksi->keterangan ?? '-',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }
    }

    // ========== MUTASI BARANG ==========
   public function mutasiBarang(Request $request)
    {
        $query = MutasiBarang::with(['asetTetap', 'user'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($subQ) use ($request) {
                    $subQ->where('nup', 'like', "%{$request->search}%")
                        ->orWhere('lokasi_awal', 'like', "%{$request->search}%")
                        ->orWhere('lokasi_akhir', 'like', "%{$request->search}%")
                        ->orWhereHas('asetTetap', function ($asetQ) use ($request) {
                            $asetQ->where('kode_barang', 'like', "%{$request->search}%")
                                ->orWhere('nama_barang', 'like', "%{$request->search}%");
                        });
                });
            })
            ->orderBy('tanggal_mutasi', 'desc');

        // Batasi 20 data per halaman sesuai request
        $mutasi_barang = $query->paginate(20)->withQueryString();

        // Transform data untuk format tanggal di view
        $mutasi_barang->getCollection()->transform(function ($item) {
            $item->tanggal_mutasi_formatted = $item->tanggal_mutasi?->format('d/m/Y');
            $item->tanggal_input = $item->created_at?->format('d/m/Y H:i');
            return $item;
        });

        // Ambil data untuk dropdown pencarian Tom Select
        $asetTetap = AssetTetap::select('id', 'kode_barang', 'nup', 'nama_barang', 'lokasi', 'kondisi')
            ->orderBy('nama_barang', 'asc')
            ->orderBy('kode_barang', 'asc')
            ->get();

        return view('adminasettetap.mutasi_barang', compact('mutasi_barang', 'asetTetap'));
    }

    // ✅ STORE: Menyimpan data mutasi baru (Tanpa no_mutasi)
    public function mutasiBarangStore(Request $request)
    {
        $validated = $request->validate([
            'aset_tetap_id' => 'required|exists:aset_tetap,id',
            'lokasi_awal' => 'required|string|max:255',
            'lokasi_akhir' => 'required|string|max:255',
            'tanggal_mutasi' => 'required|date',
            'keterangan' => 'nullable|string|max:1000'
        ]);

        DB::beginTransaction();
        try {
            $aset = AssetTetap::findOrFail($validated['aset_tetap_id']);

            MutasiBarang::create([
                'aset_tetap_id' => $validated['aset_tetap_id'],
                'kode_barang'   => $aset->kode_barang,
                'nup'           => $aset->nup,
                'nama_barang'   => $aset->nama_barang,
                'lokasi_awal'   => $validated['lokasi_awal'],
                'lokasi_akhir'  => $validated['lokasi_akhir'],
                'kondisi'       => $aset->kondisi,
                'tanggal_mutasi'=> $validated['tanggal_mutasi'],
                'keterangan'    => $validated['keterangan'],
                'user_id'       => Auth::id(),
            ]);

            // Update lokasi terkini di tabel aset_tetap
            $aset->update(['lokasi' => $validated['lokasi_akhir']]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Mutasi barang berhasil disimpan!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Mutasi store error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan mutasi: ' . $e->getMessage()
            ], 500);
        }
    }

    // ✅ SHOW: Detail mutasi berdasarkan ID
    public function mutasiBarangShow($id)
    {
        $mutasi = MutasiBarang::with(['asetTetap', 'user'])->findOrFail($id);

        $mutasi->tanggal_mutasi_formatted = $mutasi->tanggal_mutasi?->format('d/m/Y');
        $mutasi->tanggal_input = $mutasi->created_at?->format('d/m/Y H:i');

        return response()->json($mutasi);
    }

    // ✅ EDIT: Mengambil data JSON untuk form edit modal
    public function mutasiBarangEdit($id)
    {
        $mutasi = MutasiBarang::with('asetTetap')->findOrFail($id);

        return response()->json([
            'id'            => $mutasi->id,
            'aset_tetap_id' => $mutasi->aset_tetap_id,
            'lokasi_awal'   => $mutasi->lokasi_awal,
            'lokasi_akhir'  => $mutasi->lokasi_akhir,
            'tanggal_mutasi'=> $mutasi->tanggal_mutasi?->format('Y-m-d'),
            'keterangan'    => $mutasi->keterangan ?? '',
            'kode_barang'   => $mutasi->kode_barang,
            'nup'           => $mutasi->nup,
            'nama_barang'   => $mutasi->nama_barang,
            'kondisi'       => $mutasi->kondisi
        ]);
    }

    // ✅ UPDATE: Memperbarui data mutasi (Tanpa no_mutasi)
    public function mutasiBarangUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'aset_tetap_id' => 'required|exists:aset_tetap,id',
            'lokasi_awal' => 'required|string|max:255',
            'lokasi_akhir' => 'required|string|max:255',
            'tanggal_mutasi' => 'required|date',
            'keterangan' => 'nullable|string|max:1000'
        ]);

        DB::beginTransaction();
        try {
            $mutasi = MutasiBarang::findOrFail($id);
            $aset = AssetTetap::findOrFail($validated['aset_tetap_id']);

            $mutasi->update([
                'aset_tetap_id' => $validated['aset_tetap_id'],
                'kode_barang'   => $aset->kode_barang,
                'nup'           => $aset->nup,
                'nama_barang'   => $aset->nama_barang,
                'lokasi_awal'   => $validated['lokasi_awal'],
                'lokasi_akhir'  => $validated['lokasi_akhir'],
                'kondisi'       => $aset->kondisi,
                'tanggal_mutasi'=> $validated['tanggal_mutasi'],
                'keterangan'    => $validated['keterangan'],
            ]);

            // Selaraskan lokasi terbaru di data induk aset tetap
            $aset->update(['lokasi' => $validated['lokasi_akhir']]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Mutasi barang berhasil diupdate!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate mutasi'
            ], 500);
        }
    }

    // ✅ DESTROY: Menghapus data mutasi dan mengembalikan lokasi aset ke semula
    public function mutasiBarangDestroy($id)
    {
        DB::beginTransaction();
        try {
            $mutasi = MutasiBarang::with('asetTetap')->findOrFail($id);

            // Rollback lokasi aset ke lokasi awal sebelum mutasi ini terjadi
            if ($mutasi->asetTetap) {
                $mutasi->asetTetap->update(['lokasi' => $mutasi->lokasi_awal]);
            }

            $mutasi->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Mutasi barang berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus mutasi'
            ], 500);
        }
    }

    // ✅ API AUTOFILL: Mengambil data spesifik aset tetap saat dropdown dipilih
    public function getAsetTetapData($id)
    {
        $aset = AssetTetap::select(['id', 'kode_barang', 'nup', 'nama_barang', 'lokasi', 'kondisi'])->findOrFail($id);

        return response()->json([
            'kode_barang'     => $aset->kode_barang,
            'nup'             => $aset->nup,
            'nama_barang'     => $aset->nama_barang,
            'kondisi'         => $aset->kondisi,
            'lokasi_sekarang' => $aset->lokasi
        ]);
    }


    // 1. Menampilkan daftar ajuan
    public function infoAjuanIndex(Request $request)
    {
        $query = AjuanMutasi::with(['asetTetap', 'user'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($subQ) use ($request) {
                    $subQ->where('nup', 'like', "%{$request->search}%")
                        ->orWhere('lokasi_awal', 'like', "%{$request->search}%")
                        ->orWhere('lokasi_akhir', 'like', "%{$request->search}%")
                        ->orWhere('keterangan', 'like', "%{$request->search}%")
                        // Pencarian berdasarkan nama pegawai
                        ->orWhereHas('user', function ($userQ) use ($request) {
                            $userQ->where('name', 'like', "%{$request->search}%")
                            ->orWhere('unit_kerja_id', 'like', "%{$request->search}%");
                        })
                        ->orWhereHas('asetTetap', function ($asetQ) use ($request) {
                            $asetQ->where('kode_barang', 'like', "%{$request->search}%")
                                ->orWhere('nama_barang', 'like', "%{$request->search}%");
                        });
                });
            })
            ->orderBy('created_at', 'desc');

        $ajuan_mutasi = $query->paginate(20)->withQueryString();

        $ajuan_mutasi->getCollection()->transform(function ($item) {
            $item->tanggal_mutasi_formatted = $item->tanggal_mutasi?->format('d/m/Y');
            $item->tanggal_input = $item->created_at?->format('d/m/Y H:i');
            return $item;
        });

        return view('adminasettetap.info_ajuan', compact('ajuan_mutasi'));
    }

    // 2. Menampilkan detail ajuan untuk modal pop-up
    public function infoAjuanShow($id)
    {
        $ajuan = AjuanMutasi::with(['asetTetap', 'user'])->findOrFail($id);
        $ajuan = AjuanMutasi::with(['asetTetap', 'user.unitKerja'])->findOrFail($id);

        $ajuan->tanggal_mutasi_formatted = $ajuan->tanggal_mutasi?->format('d/m/Y');
        $ajuan->tanggal_input = $ajuan->created_at?->format('d/m/Y H:i');

        return response()->json($ajuan);
    }

    // 3. Menghapus / Menolak ajuan
    public function infoAjuanDestroy($id)
    {
        DB::beginTransaction();
        try {
            $ajuan = AjuanMutasi::with('asetTetap')->findOrFail($id);

            if ($ajuan->asetTetap) {
                $ajuan->asetTetap->update(['lokasi' => $ajuan->lokasi_awal]);
            }

            $ajuan->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data ajuan mutasi berhasil dihapus/ditolak!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus ajuan'
            ], 500);
        }
    }

    // ========== PEMINJAMAN BARANG ==========
    // Tampilan Peminjaman Barang Admin
    public function PeminjamanBarang(Request $request)
    {
        $query = PeminjamanBarang::with(['user'])
            ->when($request->search, function ($q, $search) {
                $q->where('kode_barang', 'like', "%{$search}%")
                    ->orWhere('nama_barang', 'like', "%{$search}%")
                    ->orWhereHas('user', fn($qb) => $qb->where('name', 'like', "%{$search}%"));
            });

        // Filter status (contoh: Semua Status, Diterima, Pending, Ditolak)
        if ($request->status && $request->status !== 'Semua Status') {
            $statusMap = [
                'Diterima' => ['disetujui', 'disetujui_admin'],
                'Pending'  => ['pending', 'dalam_review', 'diteruskan_kasubag'],
                'Ditolak'  => ['ditolak']
            ];
            $selectedStatuses = $statusMap[$request->status] ?? [];
            if (!empty($selectedStatuses)) {
                $query->whereIn('status', $selectedStatuses);
            }
        }

        $peminjamanBarang = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('adminasettetap.peminjaman_barang', compact('peminjamanBarang'));
    }

    // Aksi: Teruskan / Tolak Permintaan
    public function reviewPeminjaman(Request $request, $id)
    {
        $peminjaman = PeminjamanBarang::findOrFail($id);

        if ($request->action == 'teruskan') {
            // 🔥 KITA UBAH CARANYA JADI MANUAL SEPERTI INI (Pasti Tembus)
            $peminjaman->status = 'diteruskan_kasubag';
            $peminjaman->reviewed_by_adminasettetap_id = auth()->id();
            $peminjaman->diteruskan_ke_kasubag_date = now();
            $peminjaman->save(); // Simpan paksa ke database

            $pesan = 'Peminjaman diteruskan ke Kasubag untuk persetujuan.';
            // --- 1. NOTIFIKASI KE KASUBAG 
            $kasubag = User::where('role', 'kasubag')->first();
            if ($kasubag && $kasubag->nomor_telepon) {
                $namaPegawai = $peminjaman->user->name ?? 'Pegawai';

                // Detail Pesan ke Kasubag
                $pesanWa = "*Persetujuan Peminjaman BARANG*\n\n";
                $pesanWa .= "Yth. Kasubag,\n";
                $pesanWa .= "Admin Aset Tetap meneruskan permintaan peminjaman barang untuk disetujui:\n\n";
                $pesanWa .= "👤 *Pemohon:* {$namaPegawai}\n";
                $pesanWa .= "📦 *Barang:* {$peminjaman->nama_barang}\n";
                $pesanWa .= "🔢 *Jumlah:* {$peminjaman->jumlah}\n";
                $pesanWa .= "📅 *Tgl Pinjam:* {$peminjaman->tanggal_peminjaman}\n";
                $pesanWa .= "📝 *Keperluan:* {$peminjaman->deskripsi_peruntukan}\n\n";
                $pesanWa .= "Silakan login ke sistem untuk memberikan persetujuan akhir.";

                $noHpKasubag = preg_replace('/[^0-9]/', '', $kasubag->nomor_telepon);
                SendFonnteNotification::dispatch($noHpKasubag, $pesanWa);
            }
            // --- 2.  NOTIFIKASI INFO KE PEGAWAI ---
            $pegawai = $peminjaman->user;
            if ($pegawai && $pegawai->nomor_telepon) {
                $noHpPegawai = preg_replace('/[^0-9]/', '', $pegawai->nomor_telepon);

                $pesanWaPegawai = "*Status Peminjaman Barang*\n\n";
                $pesanWaPegawai .= "Halo {$pegawai->name},\n";
                $pesanWaPegawai .= "Pengajuan peminjaman barang Anda telah diverifikasi oleh Admin dan *sedang diteruskan ke Kasubag* untuk proses persetujuan akhir.\n\n";
                $pesanWaPegawai .= "📦 *Barang:* {$peminjaman->nama_barang}\n";
                $pesanWaPegawai .= "Kami akan mengabari Anda kembali setelah ada keputusan dari Kasubag. Terima kasih.";

                SendFonnteNotification::dispatch($noHpPegawai, $pesanWaPegawai);
            }
        } elseif ($request->action == 'tolak') {
            // 🔥 CARA MANUAL UNTUK TOLAK
            $peminjaman->status = 'ditolak';
            $peminjaman->reviewed_by_adminasettetap_id = auth()->id();
            $peminjaman->komentar = $request->komentar;
            $peminjaman->save(); // Simpan paksa ke database

            $pesan = 'Peminjaman berhasil ditolak.';

            $pegawai = $peminjaman->user;
            if ($pegawai && $pegawai->nomor_telepon) {

                // Detail Pesan Penolakan ke Pegawai
                $pesanWa = "*Peminjaman DITOLAK Admin*\n\n";
                $pesanWa .= "Halo {$pegawai->name},\n";
                $pesanWa .= "Maaf, pengajuan peminjaman barang Anda telah *ditolak* oleh Admin.\n\n";
                $pesanWa .= "📦 *Barang:* {$peminjaman->nama_barang}\n";
                $pesanWa .= "📅 *Tgl Pinjam:* {$peminjaman->tanggal_peminjaman}\n";
                $pesanWa .= "💬 *Catatan Admin:* " . ($request->komentar ?? '-') . "\n\n";
                $pesanWa .= "Silakan hubungi Admin Aset Tetap jika ada pertanyaan lebih lanjut.";

                $noHpPegawai = preg_replace('/[^0-9]/', '', $pegawai->nomor_telepon);
                SendFonnteNotification::dispatch($noHpPegawai, $pesanWa);
            }
        }

        return back()->with('success', $pesan);
    }

    // Aksi: Upload Surat BAST
    public function uploadSuratBast(Request $request, $id)
    {
        $request->validate([
            'surat_bast' => 'required|mimes:pdf|max:2048', // Maksimal 2MB, hanya PDF
        ]);

        $peminjaman = PeminjamanBarang::findOrFail($id);

        if ($request->hasFile('surat_bast')) {
            // Hapus file lama jika ada
            if ($peminjaman->surat_bast_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($peminjaman->surat_bast_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($peminjaman->surat_bast_path);
            }

            $file = $request->file('surat_bast');
            $filename = 'BAST_Pinjam_' . $peminjaman->kode_barang . '_' . time() . '.pdf';

            // Simpan ke storage/app/public/surat_peminjaman
            $path = $file->storeAs('surat_peminjaman', $filename, 'public');

            $peminjaman->update([
                'surat_bast_path' => $path
            ]);

            return back()->with('success', 'Surat BAST berhasil diunggah! Dokumen dapat diunduh oleh peminjam.');
        }

        return back()->withErrors(['error' => 'Gagal mengunggah surat.']);
    }

    // Method untuk Generate/Print Surat Peminjaman
    public function generateSuratPeminjaman(PeminjamanBarang $peminjaman)
    {
        // 1. Ambil data pihak terkait
        $peminjam = $peminjaman->user; // Pegawai yang meminjam
        $admin = auth()->user(); // Admin yang sedang login dan memproses
        $kasubag = User::where('role', 'kasubag')->first(); // Akun Kasubag untuk tanda tangan
        $kepala = User::where('role', 'kepalabpmp')->first(); // Akun Kepala BPMP

        // 2. Fungsi bantuan untuk mengubah tanda tangan (lokal) menjadi Base64 agar tampil di PDF
        $getBase64 = function ($path) {
            if ($path && Storage::disk('public')->exists($path)) {
                $type = pathinfo(storage_path('app/public/' . $path), PATHINFO_EXTENSION);
                $data = Storage::disk('public')->get($path);
                return 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
            return null;
        };

        // 3. Siapkan gambar tanda tangan (Signature) masing-masing pihak
        $ttdPeminjam = $peminjam ? $getBase64($peminjam->signature) : null;
        $ttdAdmin = $admin ? $getBase64($admin->signature) : null;
        $ttdKasubag = $kasubag ? $getBase64($kasubag->signature) : null;
        $ttdKepala = $kepala ? $getBase64($kepala->signature) : null;

        // 4. Load View khusus untuk Surat Peminjaman Barang
        // Pastikan file view ini ada di resources/views/surat/peminjaman_barang.blade.php
        $pdf = Pdf::loadView('surat.peminjaman_barang', compact(
            'peminjaman',
            'peminjam',
            'admin',
            'kasubag',
            'kepala',
            'ttdPeminjam',
            'ttdAdmin',
            'ttdKasubag',
            'ttdKepala'
        ))->setPaper('a4', 'portrait');

        // 5. Download otomatis dengan nama file yang rapi
        $fileName = 'Berita_Acara_Peminjaman_' . $peminjaman->kode_barang . '_' . time() . '.pdf';
        return $pdf->download($fileName);
    }

    // ========== PENGEMBALIAN BARANG ==========
    public function PengembalianBarang(Request $request)
    {
        $query = PengembalianBarang::with([
            'peminjamanBarang',
            'user',
            'adminVerifier'
        ])
            ->when($request->kondisi, fn($q, $k) => $q->kondisi($k))
            ->when($request->status_verifikasi, fn($q, $s) => $q->where('status_verifikasi', $s))
            ->when($request->search, fn($q, $s) => $q->search($s))
            ->orderBy('tanggal_pengembalian_aktual', 'desc');

        $pengembalianbarang = $query->paginate(10)->withQueryString();

        return view('adminasettetap.pengembalian_barang', compact('pengembalianbarang'));
    }


    public function verifikasiPengembalianBarang(Request $request, $id)
    {
        $pengembalian = PengembalianBarang::findOrFail($id);

        $request->validate([
            'status_verifikasi' => 'required|in:diterima,ditolak',
            'komentar_admin' => 'nullable|string'
        ]);

        $pengembalian->update([
            'status_verifikasi' => $request->status_verifikasi,
            'komentar_admin' => $request->komentar_admin,
            'verified_by_adminAsetTetap_id' => auth()->id(),
            'verified_at' => now()
        ]);

        // Update status tabel peminjaman utama
        if ($request->status_verifikasi === 'diterima') {
            $pengembalian->peminjamanBarang->update(['status' => 'dikembalikan']);

            // Kembalikan stok jika diperlukan
            $aset = $pengembalian->peminjamanBarang->barang;
            if ($aset) {
                $aset->status = 'Tersedia'; // Sesuaikan dengan alur bisnis Anda
                $aset->save();
            }
        } else {
            // Jika ditolak, kembalikan status ke disetujui agar pegawai merevisi pengembalian
            $pengembalian->peminjamanBarang->update(['status' => 'disetujui']);
        }

        $pegawai = $pengembalian->user;
        if ($pegawai && $pegawai->nomor_telepon) {
            $namaBarang = $pengembalian->peminjamanBarang->nama_barang ?? 'Barang';

            if ($request->status_verifikasi === 'diterima') {
                $pesanWa = "*Pengembalian Barang DITERIMA*\n\n";
                $pesanWa .= "Halo {$pegawai->name},\n";
                $pesanWa .= "Terima kasih, laporan pengembalian barang Anda telah diverifikasi dan *Diterima* oleh Admin.\n\n";
                $pesanWa .= "📦 *Barang:* {$namaBarang}\n";
                $pesanWa .= "📅 *Tgl Kembali:* {$pengembalian->tanggal_pengembalian_aktual}\n";
                $pesanWa .= "🔍 *Kondisi:* " . ucfirst($pengembalian->kondisi_barang) . "\n\n";
                $pesanWa .= "Status peminjaman Anda sekarang telah selesai.";
            } else {
                $pesanWa = "*Pengembalian Barang DITOLAK/REVISI*\n\n";
                $pesanWa .= "Halo {$pegawai->name},\n";
                $pesanWa .= "Laporan pengembalian barang Anda *Ditolak* oleh Admin.\n\n";
                $pesanWa .= "📦 *Barang:* {$namaBarang}\n";
                $pesanWa .= "💬 *Catatan Admin:* " . ($request->komentar_admin ?? '-') . "\n\n";
                $pesanWa .= "Silakan login ke sistem untuk memperbaiki data laporan pengembalian Anda.";
            }

            $noHpPegawai = preg_replace('/[^0-9]/', '', $pegawai->nomor_telepon);
            SendFonnteNotification::dispatch($noHpPegawai, $pesanWa);
        }

        return back()->with('success', 'Verifikasi pengembalian berhasil disimpan!');
    }

    public function showPengembalianJsonAdmin($id)
    {
        $data = PengembalianBarang::with(['peminjamanBarang', 'user'])->find($id);
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function cetakSuratPengembalianBarang($id)
    {
        $pengembalian = PengembalianBarang::with(['peminjamanBarang', 'user', 'adminVerifier'])->findOrFail($id);

        // Pastikan status sudah diterima agar bisa dicetak
        if ($pengembalian->status_verifikasi !== 'diterima') {
            abort(403, 'Hanya pengembalian yang disetujui yang dapat dicetak.');
        }

        $pdf = Pdf::loadView('surat.pengembalian_barang', compact('pengembalian'))->setPaper('a4', 'portrait');

        $fileName = 'Surat_Pengembalian_' . $pengembalian->peminjamanBarang->kode_barang . '.pdf';
        return $pdf->stream($fileName);
    }

    // ========== PEMINJAMAN KENDARAAN ==========
    public function PeminjamanKendaraan(Request $request)
    {
        $peminjamanKendaraan = PeminjamanKendaraan::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('adminasettetap.peminjaman_kendaraan', compact('peminjamanKendaraan'));
    }

    public function reviewPeminjamanKendaraan(Request $request, $id)
    {
        $peminjaman = PeminjamanKendaraan::findOrFail($id);

        if ($request->action == 'teruskan') {
            $peminjaman->status = 'diteruskan_kasubag';
            $peminjaman->reviewed_by_adminasettetap_id = auth()->id();
            $peminjaman->diteruskan_ke_kasubag_date = now();
            $peminjaman->save();
            $msg = 'Permintaan diteruskan ke Kasubag.';
            // --- 1. NOTIFIKASI KE KASUBAG
            $kasubag = User::where('role', 'kasubag')->first();
            if ($kasubag && $kasubag->nomor_telepon) {
                $namaPegawai = $peminjaman->user->name ?? 'Pegawai';

                $pesanWa = "*Persetujuan Peminjaman KENDARAAN*\n\n";
                $pesanWa .= "Yth. Kasubag,\n";
                $pesanWa .= "Admin Aset Tetap meneruskan permintaan peminjaman kendaraan dinas untuk disetujui:\n\n";
                $pesanWa .= "👤 *Pemohon:* {$namaPegawai}\n";
                $pesanWa .= "🚗 *Kendaraan:* {$peminjaman->nama_barang}\n";
                $pesanWa .= "📅 *Tgl Pinjam:* {$peminjaman->tanggal_peminjaman}\n";
                $pesanWa .= "📅 *Tgl Kembali:* {$peminjaman->tanggal_pengembalian}\n";
                $pesanWa .= "📝 *Keperluan:* {$peminjaman->deskripsi_peruntukan}\n\n";
                $pesanWa .= "Silakan login ke sistem untuk memberikan persetujuan akhir.";

                $noHpKasubag = preg_replace('/[^0-9]/', '', $kasubag->nomor_telepon);
                SendFonnteNotification::dispatch($noHpKasubag, $pesanWa);
            }
            // --- 2. TAMBAHKAN KODE INI: NOTIFIKASI INFO KE PEGAWAI ---
            $pegawai = $peminjaman->user;
            if ($pegawai && $pegawai->nomor_telepon) {
                $noHpPegawai = preg_replace('/[^0-9]/', '', $pegawai->nomor_telepon);

                $pesanWaPegawai = "*Status Peminjaman Kendaraan*\n\n";
                $pesanWaPegawai .= "Halo {$pegawai->name},\n";
                $pesanWaPegawai .= "Pengajuan peminjaman kendaraan dinas Anda telah diverifikasi oleh Admin dan *sedang diteruskan ke Kasubag* untuk proses persetujuan akhir.\n\n";
                $pesanWaPegawai .= "🚗 *Kendaraan:* {$peminjaman->nama_barang}\n";
                $pesanWaPegawai .= "Kami akan mengabari Anda kembali setelah ada keputusan dari Kasubag. Terima kasih.";

                SendFonnteNotification::dispatch($noHpPegawai, $pesanWaPegawai);
            }
        } else {
            $peminjaman->status = 'ditolak';
            $peminjaman->komentar = $request->komentar;
            $peminjaman->save();
            $msg = 'Permintaan ditolak.';

            $pegawai = $peminjaman->user;
            if ($pegawai && $pegawai->nomor_telepon) {
                $pesanWa = "*Peminjaman Kendaraan DITOLAK Admin*\n\n";
                $pesanWa .= "Halo {$pegawai->name},\n";
                $pesanWa .= "Maaf, pengajuan peminjaman kendaraan dinas Anda telah *ditolak* oleh Admin.\n\n";
                $pesanWa .= "🚗 *Kendaraan:* {$peminjaman->nama_barang}\n";
                $pesanWa .= "📅 *Tgl Pinjam:* {$peminjaman->tanggal_peminjaman}\n";
                $pesanWa .= "💬 *Catatan Admin:* " . ($request->komentar ?? '-') . "\n\n";
                $pesanWa .= "Silakan hubungi Admin Aset Tetap jika ada pertanyaan lebih lanjut.";

                $noHpAdmin = preg_replace('/[^0-9]/', '', $pegawai->nomor_telepon);
                SendFonnteNotification::dispatch($noHpAdmin, $pesanWa);
            }
        }

        return back()->with('success', $msg);
    }

    public function uploadBastKendaraan(Request $request, $id)
    {
        $request->validate(['surat_bast' => 'required|mimes:pdf|max:2048']);
        $peminjaman = PeminjamanKendaraan::findOrFail($id);

        if ($request->hasFile('surat_bast')) {
            $path = $request->file('surat_bast')->store('surat_peminjaman_kendaraan', 'public');
            $peminjaman->update(['surat_bast_path' => $path]);
        }

        return back()->with('success', 'Surat persetujuan berhasil diunggah.');
    }

    public function generateSuratPeminjamanKendaraan(PeminjamanKendaraan $peminjaman)
    {
        // 1. Ambil data pihak terkait
        // Data peminjam sudah otomatis terambil dari relasi (jika sudah diset di model)
        $peminjam = $peminjaman->user;
        $admin = auth()->user(); // Admin yang sedang login dan mencetak surat
        $kasubag = User::where('role', 'kasubag')->first();
        $kepala = User::where('role', 'kepalabpmp')->first(); // Pastikan rolenya sesuai dengan database Anda

        // 2. Fungsi bantuan untuk mengubah tanda tangan (lokal) menjadi Base64 agar bisa dirender DomPDF
        $getBase64 = function ($path) {
            if ($path && Storage::disk('public')->exists($path)) {
                $type = pathinfo(storage_path('app/public/' . $path), PATHINFO_EXTENSION);
                $data = Storage::disk('public')->get($path);
                return 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
            return null;
        };

        // 3. Siapkan gambar tanda tangan (Signature) masing-masing pihak
        $ttdPeminjam = $peminjam ? $getBase64($peminjam->signature) : null;
        $ttdAdmin = $admin ? $getBase64($admin->signature) : null;
        $ttdKasubag = $kasubag ? $getBase64($kasubag->signature) : null;
        $ttdKepala = $kepala ? $getBase64($kepala->signature) : null;

        // 4. Load View khusus untuk Surat Peminjaman Kendaraan
        // Sesuaikan path 'surat.peminjaman_kendaraan' dengan lokasi file blade Berita Acara Anda
        $pdf = Pdf::loadView('surat.peminjaman_kendaraan', compact(
            'peminjaman',
            'peminjam',
            'admin',
            'kasubag',
            'kepala',
            'ttdPeminjam',
            'ttdAdmin',
            'ttdKasubag',
            'ttdKepala'
        ))->setPaper('a4', 'portrait');

        // 5. Tampilkan PDF (Stream) atau Download Otomatis
        $fileName = 'BA_Pinjam_Kendaraan_' . ($peminjaman->kode_barang ?? $peminjaman->id) . '_' . time() . '.pdf';

        // Gunakan ->stream() jika ingin melihat preview di browser dulu
        // Gunakan ->download() jika ingin file langsung terunduh ke komputer
        return $pdf->stream($fileName);
    }

    public function showJsonKendaraan($id)
    {
        // Mengambil data peminjaman beserta nama user
        $data = PeminjamanKendaraan::with('user')->find($id);

        if (!$data) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }


    // ========== PENGEMBALIAN KENDARAAN ==========
    public function PengembalianKendaraan(Request $request)
    {
        $query = PengembalianKendaraan::with([
            'peminjamanKendaraan',
            'peminjamanKendaraan.user',
            'user',
            'admin' // verified_by_admin_id
        ])
            ->when($request->kondisi, function ($q, $kondisi) {
                $q->where('kondisi_kendaraan', $kondisi);
            })
            ->when($request->status, function ($q, $status) { // ✅ Ganti status_verifikasi → status_pengembalian
                $q->where('status_pengembalian', $status);
            })
            ->when($request->search, function ($q, $search) {
                $q->whereHas('peminjamanKendaraan.kendaraan', function ($qb) use ($search) {
                    $qb->where('nopol', 'like', "%{$search}%")
                        ->orWhere('merk', 'like', "%{$search}%")
                        ->orWhere('tipe', 'like', "%{$search}%");
                })
                    ->orWhereHas('peminjamanKendaraan.user', function ($qb) use ($search) {
                        $qb->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhere('catatan', 'like', "%{$search}%");
            })
            ->orderBy('tanggal_pengembalian_aktual', 'desc');

        $pengembalianKendaraan = $query->paginate(10)->withQueryString();

        return view('adminasettetap.pengembalian_kendaraan', compact('pengembalianKendaraan'));
    }


    public function verifikasiPengembalianKendaraan(Request $request, $id)
    {
        $pengembalian = PengembalianKendaraan::findOrFail($id);

        $request->validate([
            'status_verifikasi' => 'required|in:diterima,ditolak',
            'komentar_admin' => 'nullable|string'
        ]);

        $pengembalian->update([
            'status_verifikasi' => $request->status_verifikasi,
            'komentar_admin' => $request->komentar_admin,
            'verified_by_admin_id' => auth()->id(),
            'verified_at' => now()
        ]);

        if ($request->status_verifikasi === 'pending') {
            $pengembalian->peminjamanKendaraan()->update(['status' => 'diterima']);

            // Kembalikan stok jika diperlukan
            $aset = $pengembalian->peminjamanKendaraan()->barang;
            if ($aset) {
                $aset->status = 'Tersedia'; // Sesuaikan dengan alur bisnis Anda
                $aset->save();
            }
        } else {
            // Jika ditolak, kembalikan status ke disetujui agar pegawai merevisi pengembalian
            $pengembalian->peminjamanKendaraan->update(['status' => 'disetujui']);
        }

        $pegawai = $pengembalian->user;
        if ($pegawai && $pegawai->nomor_telepon) {
            $namaKendaraan = $pengembalian->peminjamanKendaraan->nama_barang ?? 'Kendaraan';

            // Status pada kendaraan biasanya menggunakan 'diproses' / 'diterima'
            if ($request->status_verifikasi === 'diterima' || $request->status_verifikasi === 'pending') {
                $pesanWa = "*Pengembalian Kendaraan DITERIMA*\n\n";
                $pesanWa .= "Halo {$pegawai->name},\n";
                $pesanWa .= "Terima kasih, laporan pengembalian kendaraan dinas Anda telah diverifikasi dan *Diterima* oleh Admin.\n\n";
                $pesanWa .= "🚗 *Kendaraan:* {$namaKendaraan}\n";
                $pesanWa .= "📅 *Tgl Kembali:* {$pengembalian->tanggal_pengembalian_aktual}\n";
                $pesanWa .= "🔍 *Kondisi:* " . ucfirst($pengembalian->kondisi_kendaraan) . "\n\n";
                $pesanWa .= "Status peminjaman kendaraan Anda sekarang telah selesai.";
            } else {
                $pesanWa = "*Pengembalian Kendaraan DITOLAK/REVISI*\n\n";
                $pesanWa .= "Halo {$pegawai->name},\n";
                $pesanWa .= "Laporan pengembalian kendaraan dinas Anda *Ditolak* oleh Admin.\n\n";
                $pesanWa .= "🚗 *Kendaraan:* {$namaKendaraan}\n";
                $pesanWa .= "💬 *Catatan Admin:* " . ($request->komentar_admin ?? '-') . "\n\n";
                $pesanWa .= "Silakan login ke sistem untuk memperbaiki data laporan pengembalian atau foto kendaraan Anda.";
            }

            $noHpPegawai = preg_replace('/[^0-9]/', '', $pegawai->nomor_telepon);
            SendFonnteNotification::dispatch($noHpPegawai, $pesanWa);
        }

        return back()->with('success', 'Verifikasi pengembalian kendaraan berhasil disimpan!');
    }

    public function showPengembalianKendaraanJsonAdmin($id)
    {
        $data = PengembalianKendaraan::with(['peminjamanKendaraan', 'user'])->find($id);
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function cetakSuratPengembalianKendaraan($id)
    {
        $pengembalian = PengembalianKendaraan::with(['peminjamanKendaraan', 'user', 'admin'])->findOrFail($id);

        // Ambil data user admin dan kepala dari tabel users
        $admin = auth()->user();
        $kepala = \App\Models\User::where('role', 'kepalabpmp')->first();

        $pdf = Pdf::loadView('surat.pengembalian_kendaraan', compact('pengembalian', 'admin', 'kepala'))
            ->setPaper('a4', 'portrait');

        $kodeBarang = $pengembalian->peminjamanKendaraan->nama_barang ?? 'Kendaraan';
        return $pdf->stream('Surat_Pengembalian_' . $kodeBarang . '.pdf');
    }

    // ========== LAPORAN PEMINJAMAN KENDARAAN ==========
    public function laporanPeminjamanKendaraan(Request $request)
    {
        $query = PeminjamanKendaraan::with(['kendaraan', 'user', 'sopir'])
            ->when($request->date_range, function ($q, $range) {
                [$start, $end] = explode(' - ', $range);
                $q->whereBetween('tanggal_pinjam', [Carbon::parse($start), Carbon::parse($end)]);
            });

        $laporanPeminjamanKendaraan = $query->get();
        return view('adminasettetap.laporan_peminjaman_kendaraan', compact('laporanPeminjamanKendaraan'));
    }

    // ========== LAPORAN PENGEMBALIAN KENDARAAN ==========
    public function laporanPengembalianKendaraan(Request $request)
    {
        $query = PengembalianKendaraan::with([
            'peminjamanKendaraan.kendaraan',
            'peminjamanKendaraan.user',
            'user'
        ])
            ->when($request->date_range, function ($q, $range) {
                [$start, $end] = explode(' - ', $range);
                $q->whereBetween('tanggal_pengembalian_aktual', [Carbon::parse($start), Carbon::parse($end)]);
            })
            ->when($request->status, fn($q, $s) => $q->where('status_pengembalian', $s));

        $laporanPengembalianKendaraan = $query->get();
        return view('adminasettetap.laporan_pengembalian_kendaraan', compact('laporanPengembalianKendaraan'));
    }

    // ========== COMMON: Update Status (UPDATED) ==========
    public function updateStatus(Request $request, $id)
    {
        $model = match ($request->type) {
            'peminjaman-barang' => PeminjamanBarang::findOrFail($id),
            'pengembalian-barang' => PengembalianBarang::findOrFail($id),
            'peminjaman-kendaraan' => PeminjamanKendaraan::findOrFail($id),
            'pengembalian-kendaraan' => PengembalianKendaraan::findOrFail($id), // ✅ FIXED
            default => abort(404)
        };

        // Handle different status fields
        $updateData = [
            'catatan_admin' => $request->catatan,
            'verified_at' => now(),
            // 'verified_by_admin_id' => auth()->id(), // ✅ Tambah verifier
        ];

        if (in_array($model->getTable(), ['pengembalian_barang', 'pengembalian_kendaraan'])) {
            $updateData['status_verifikasi'] = $request->status ?? $request->status_verifikasi;
        } else {
            $updateData['status'] = $request->status;
        }

        // Untuk pengembalian kendaraan, update juga status peminjaman
        if ($request->type === 'pengembalian-kendaraan') {
            $pengembalian = PengembalianKendaraan::findOrFail($id);
            $pengembalian->peminjamanKendaraan->update(['status' => 'dikembalikan']);
        }

        $model->update($updateData);

        return back()->with('success', 'Status berhasil diperbarui!');
    }


    // ========== LAPORAN ==========
    public function laporanPeminjamanBarang(Request $request)
    {
        $query = PeminjamanBarang::with(['barang', 'user'])
            ->when($request->date_range, function ($q, $range) {
                [$start, $end] = explode(' - ', $range);
                $q->whereBetween('tanggal_peminjaman', [Carbon::parse($start), Carbon::parse($end)]);
            });

        $laporanPeminjamanBarang = $query->get();
        return view('adminasettetap.laporan_peminjaman_barang', compact('laporanPeminjamanBarang'));
    }

    public function laporanTransaksiMasuk(Request $request)
    {
        $query = TransaksiMasukAssetTetap::with(['aset', 'pemasok'])
            ->when($request->date_range, function ($q, $range) {
                [$start, $end] = explode(' - ', $range);
                $q->whereBetween('tanggal_transaksi', [Carbon::parse($start), Carbon::parse($end)]);
            });

        $laporanTransaksiMasuk = $query->get();
        return view('adminasettetap.laporan_transaksimasuk', compact('laporanTransaksiMasuk'));
    }

    public function laporanTransaksiKeluar(Request $request)
    {
        $query = TransaksiKeluarAssetTetap::with(['aset', 'penerima'])
            ->when($request->date_range, function ($q, $range) {
                [$start, $end] = explode(' - ', $range);
                $q->whereBetween('tanggal_transaksi', [Carbon::parse($start), Carbon::parse($end)]);
            });

        $laporanTransaksiKeluar = $query->get();
        return view('adminasettetap.laporan_transaksikeluar', compact('laporanTransaksiKeluar'));
    }

    public function laporanMutasiAsetTetap(Request $request)
    {
        $query = MutasiBarang::with(['barangAsal', 'barangTujuan'])
            ->when($request->date_range, function ($q, $range) {
                [$start, $end] = explode(' - ', $range);
                $q->whereBetween('tanggal_mutasi', [Carbon::parse($start), Carbon::parse($end)]);
            });

        $laporanMutasiAsetTetap = $query->get();
        return view('adminasettetap.laporan_mutasibarang', compact('laporanMutasiAsetTetap'));
    }


    // ========== PENGADUAN ==========
    //====landing page====//
   //====landing page====//
    public function landingpage()
    {
        // 1. Total Item BMN (Gabungan Aset Tetap dan Master Barang Persediaan)
        $totalAset = \App\Models\AssetTetap::count();
        $totalPersediaan = \App\Models\Persediaan::count();
        $totalItemBMN = $totalAset + $totalPersediaan;

        // --- Mengambil data Pegawai dan Fasilitas ---
        $totalPegawai = \App\Models\User::count(); // Ganti dengan User::where('role', 'pegawai')->count() jika hanya role pegawai
        $totalFasilitas = \App\Models\Gedung::count(); // Pastikan model Gedung di-import atau panggil dengan \App\Models\Gedung
        // --------------------------------------------

        // 2. Nilai Aset Terkelola (Menjumlahkan kolom nilai_perolehan)
        $nilaiAsetTotal = \App\Models\AssetTetap::sum('nilai_perolehan'); 
        $formattedNilaiAset = number_format($nilaiAsetTotal / 1000000000, 1); // Konversi ke Milyar (M)

        // 3. Transaksi Bulan Ini
        $transaksiAset = \App\Models\PeminjamanBarang::whereMonth('created_at', \Carbon\Carbon::now()->month)->count();
        $transaksiPersediaan = \App\Models\PermintaanPersediaan::whereMonth('created_at', \Carbon\Carbon::now()->month)->count();
        $totalTransaksiBulanIni = $transaksiAset + $transaksiPersediaan;

        // 4. Kondisi Baik 
        $asetBaik = \App\Models\AssetTetap::where('kondisi', 'Baik')->count();
        $persentaseKondisiBaik = $totalAset > 0 ? round(($asetBaik / $totalAset) * 100, 1) : 0;

        return view('welcome', compact(
            'totalItemBMN',
            'formattedNilaiAset',
            'totalTransaksiBulanIni',
            'persentaseKondisiBaik',
            'totalPegawai',
            'totalFasilitas'
        ));
    }
}
