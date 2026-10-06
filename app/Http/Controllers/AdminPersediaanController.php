<?php

namespace App\Http\Controllers;

use App\Exports\PersediaanTemplateExport;
use App\Imports\PersediaanImport;
use App\Jobs\SendFonnteNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\{
    User,
    Persediaan,
    PermintaanPersediaan,
    TransaksiKeluarPersediaan,
    TransaksiMasukPersediaan,
};
use App\Services\FonnteService;
use Maatwebsite\Excel\Facades\Excel;

class AdminPersediaanController extends Controller
{
    public function dashboard()
    {

        $bulanIni = now()->month;
        $tahunIni = now()->year;

        // 1. Hitung Total & Nilai Persediaan
        $totalPersediaan = \App\Models\Persediaan::count();
        $totalNilaiPersediaan = \App\Models\Persediaan::sum('harga_total') ?? 0;

        // 2. Transaksi Bulan Ini
        $masukBulanIni = \App\Models\TransaksiMasukPersediaan::whereMonth('tanggal_input', $bulanIni)
            ->whereYear('tanggal_input', $tahunIni)->count();
        $keluarBulanIni = \App\Models\TransaksiKeluarPersediaan::whereMonth('tanggal_input', $bulanIni)
            ->whereYear('tanggal_input', $tahunIni)->count();

        // 3. Status Permintaan Persediaan
        $permintaanPending = \App\Models\PermintaanPersediaan::whereIn('status', ['pending', 'dalam_review'])->count();
        $permintaanDisetujui = \App\Models\PermintaanPersediaan::whereIn('status', ['disetujui', 'disetujui_kasubag'])->count();
        $permintaanDitolak = \App\Models\PermintaanPersediaan::whereIn('status', ['ditolak', 'ditolak_kasubag'])->count();
        $totalPermintaan = \App\Models\PermintaanPersediaan::count();

        // 4. Data Grafik: Tren Transaksi Keluar Bulanan (Tahun ini)
        $chartData = [];
        $maxChart = 1; // Mencegah division by zero
        for ($i = 1; $i <= 12; $i++) {
            $count = \App\Models\TransaksiKeluarPersediaan::whereMonth('tanggal_input', $i)
                ->whereYear('tanggal_input', $tahunIni)->count();
            $chartData[$i] = $count;
            if ($count > $maxChart) {
                $maxChart = $count;
            }
        }

        return view('adminpersediian.dashbord', compact(
            'totalPersediaan',
            'totalNilaiPersediaan',
            'masukBulanIni',
            'keluarBulanIni',
            'permintaanPending',
            'permintaanDisetujui',
            'permintaanDitolak',
            'totalPermintaan',
            'chartData',
            'maxChart',
            'tahunIni'
        ));
    }

    // 📋 DATA PERSEDIAAN
    public function dataPersediaan(Request $request)
    {
        $query = Persediaan::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->search . '%')
                    ->orWhere('kode_unik_barang', 'like', '%' . $request->search . '%')
                    ->orWhere('kode_barang', 'like', '%' . $request->search . '%')
                    ->orWhere('kategori', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kode_kategori', $request->kategori);
        }

        $persediaan = $query->latest()->paginate(10);

        return view('adminpersediian.data_persediaan', compact('persediaan'));
    }

    public function create()
    {
        return view('adminpersediian.form_persediaan');
    }

    public function store(Request $request)
    {
        // 1. Dukungan Multi-Item
        if ($request->has('items') && is_array($request->items)) {
            $items = $request->items;
            foreach ($items as $idx => $item) {
                $clean_harga = $this->cleanRupiah($item['harga_satuan'] ?? '0');
                $kodeKategori = trim($item['kode_kategori'] ?? '');
                $kodeBarang = trim($item['kode_barang'] ?? '');
                $items[$idx]['harga_satuan'] = $clean_harga;
                $items[$idx]['kode_unik_barang'] = $kodeKategori . '-' . $kodeBarang;
            }
            $request->merge(['items' => $items]);

            $request->validate([
                'items' => 'required|array|min:1',
                'items.*.kode_unik_barang' => 'required|string|max:100|regex:/^.+-[^-]+$/|unique:persediaan,kode_unik_barang',
                'items.*.kode_kategori' => 'required|string|max:20',
                'items.*.kategori' => 'required|string|max:100',
                'items.*.kode_barang' => 'required|string|max:50',
                'items.*.nama_barang' => 'required|string|max:200',
                'items.*.satuan' => 'required|string|max:50',
                'items.*.tanggal_masuk' => 'required|date',
                'items.*.harga_satuan' => 'required|numeric|min:0',
                'items.*.jumlah' => 'required|integer|min:1',
            ], [
                'items.*.kode_unik_barang.unique' => 'Ada kode barang (kode kategori - kode barang) yang sudah terdaftar dalam sistem.',
                'items.*.kode_unik_barang.regex' => 'Format kode unik tidak sesuai (harus: KodeKategori-KodeBarang).',
            ]);

            DB::transaction(function () use ($request) {
                foreach ($request->items as $row) {
                    $row['harga_total'] = $row['harga_satuan'] * $row['jumlah'];
                    Persediaan::create($row);
                }
            });

            $count = count($request->items);
            return redirect()->route('adminpersediaan.data-persediaan')
                ->with('success', "Berhasil menambahkan {$count} data persediaan!");
        }

        // 2. Single-Item Fallback
        $this->pisahkanKodeUnikBarang($request);

        $request->merge([
            'harga_satuan' => $this->cleanRupiah($request->harga_satuan)
        ]);

        $validated = $request->validate([
            'kode_unik_barang' => 'required|string|max:100|regex:/^.+-[^-]+$/|unique:persediaan,kode_unik_barang',
            'kode_kategori' => 'required|string|max:20',
            'kategori' => 'required|string|max:100',
            'kode_barang' => 'required|string|max:50',
            'nama_barang' => 'required|string|max:200',
            'satuan' => 'required|string|max:50', // VALIDASI SATUAN
            'tanggal_masuk' => 'required|date',
            'harga_satuan' => 'required|numeric|min:0',
            'jumlah' => 'required|integer|min:1',
        ]);

        Persediaan::create($validated + [
            'harga_total' => $validated['harga_satuan'] * $validated['jumlah'],
        ]);

        return redirect()->route('adminpersediaan.data-persediaan')
            ->with('success', 'Data persediaan berhasil ditambahkan!');
    }

    public function show(Persediaan $persediaan)
    {
        return view('adminpersediian.detail_persediaan', compact('persediaan'));
    }

    public function edit(Persediaan $persediaan)
    {
        return view('adminpersediian.form_persediaan', compact('persediaan'));
    }

    public function update(Request $request, Persediaan $persediaan)
    {
        $this->pisahkanKodeUnikBarang($request);

        $request->merge([
            'harga_satuan' => $this->cleanRupiah($request->harga_satuan)
        ]);

        $validated = $request->validate([
            'kode_unik_barang' => [
                'required', 'string', 'max:100', 'regex:/^.+-[^-]+$/',
                Rule::unique('persediaan', 'kode_unik_barang')->ignore($persediaan),
            ],
            'kode_kategori' => 'required|string|max:20',
            'kategori' => 'required|string|max:100',
            'kode_barang' => ['required', 'string', 'max:50'],
            'nama_barang' => 'required|string|max:200',
            'satuan' => 'required|string|max:50', // VALIDASI SATUAN
            'tanggal_masuk' => 'required|date',
            'harga_satuan' => 'required|numeric|min:0',
            'jumlah' => 'required|integer|min:1',
        ]);

        $persediaan->update($validated + [
            'harga_total' => $validated['harga_satuan'] * $validated['jumlah'],
        ]);

        return redirect()->route('adminpersediaan.data-persediaan')
            ->with('success', 'Data persediaan berhasil diupdate!');
    }

    public function destroy(Persediaan $persediaan)
    {
        $persediaan->delete();
        return redirect()->route('adminpersediaan.data-persediaan')
            ->with('success', 'Data persediaan berhasil dihapus!');
    }

    /** Pecah kode unik pada tanda hubung pertama untuk menjaga kompatibilitas laporan lama. */
    private function pisahkanKodeUnikBarang(Request $request): void
    {
        $kodeUnik = trim((string) $request->input('kode_unik_barang'));

        if ($kodeUnik === '' && $request->filled('kode_kategori') && $request->filled('kode_barang')) {
            $kodeUnik = trim((string) $request->input('kode_kategori'))
                .'-'.trim((string) $request->input('kode_barang'));
        }

        $posisiPemisah = strpos($kodeUnik, '-');

        if ($posisiPemisah === false) {
            return;
        }

        $request->merge([
            'kode_unik_barang' => $kodeUnik,
            'kode_kategori' => trim(substr($kodeUnik, 0, $posisiPemisah)),
            'kode_barang' => trim(substr($kodeUnik, $posisiPemisah + 1)),
        ]);
    }

    /**
     * Membersihkan input mata uang rupiah, mendukung pemisah ribuan berupa titik maupun koma.
     */
    private function cleanRupiah($value): float
    {
        $val = trim((string) $value);
        if ($val === '') return 0.0;
        
        $val = preg_replace('/[^\d.,]/', '', $val);
        
        if (strpos($val, '.') !== false && strpos($val, ',') !== false) {
            if (strrpos($val, ',') > strrpos($val, '.')) {
                $val = str_replace('.', '', $val);
                $val = str_replace(',', '.', $val);
            } else {
                $val = str_replace(',', '', $val);
            }
        } elseif (strpos($val, ',') !== false) {
            if (preg_match('/,\d{3}$/', $val)) {
                $val = str_replace(',', '', $val);
            } else {
                $val = str_replace(',', '.', $val);
            }
        } elseif (strpos($val, '.') !== false) {
            if (substr_count($val, '.') > 1 || preg_match('/\.\d{3}$/', $val) || !preg_match('/\.\d{1,2}$/', $val)) {
                $val = str_replace('.', '', $val);
            }
        }

        return (float) $val;
    }

    // 📤 Transaksi Keluar
    //=========TRANSAKSI KELUAR PERSEDIAAN========//

    /** INDEX - Tampilkan daftar transaksi keluar */
    public function transaksiKeluar(Request $request)
    {
        $query = TransaksiKeluarPersediaan::with('persediaan');

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_barang', 'like', '%' . $request->search . '%')
                    ->orWhere('nama_barang', 'like', '%' . $request->search . '%')
                    ->orWhere('kode_kategori', 'like', '%' . $request->search . '%')
                    ->orWhere('kategori', 'like', '%' . $request->search . '%');
            });
        }

        // Filter tanggal
        if ($request->filled('tanggal_input')) {
            $query->whereDate('tanggal_input', $request->tanggal_input);
        }

        // Filter kode kategori
        if ($request->filled('kode_kategori')) {
            $query->where('kode_kategori', $request->kode_kategori);
        }

        $transaksi = $query->latest('tanggal_input')->paginate(10);

        // 🔥 AMBIL DATA MASTER PERSEDIAAN UNTUK DROPDOWN DI MODAL INDEX
        // Menggunakan getRawOriginal() agar harga_satuan murni berupa nominal numerik asli database (tanpa embel-embel string "Rp")
        $masterPersediaan = Persediaan::orderBy('nama_barang')->get()->map(function($item) {
            return [
                'id'            => $item->id,
                'kode_unik_barang' => $item->kode_unik_barang,
                'kode_barang'   => $item->kode_barang,
                'nama_barang'   => $item->nama_barang,
                'kode_kategori' => $item->kode_kategori,
                'kategori'      => $item->kategori,
                'satuan'        => $item->satuan, // MAP DATA SATUAN KE FRONTEND
                'harga_satuan'  => (float) $item->getRawOriginal('harga_satuan'),
                'stok_tersedia' => (int) $item->jumlah
            ];
        });
        
        return view('adminpersediian.transaksi_keluar', compact('transaksi', 'masterPersediaan'));

    }

    /** CREATE - Tampilkan form tambah */
    public function createTransaksiKeluar()
    {
        $persediaan = Persediaan::select('kode_barang', 'nama_barang')
            ->orderBy('kategori')
            ->get();

        return view('adminpersediian.form_transaksi_keluar', compact('persediaan'));
    }

    /** STORE - Simpan transaksi keluar */
    public function storeTransaksiKeluar(Request $request)
    {
        $validated = $request->validate([
            'tanggal_input' => 'required|date',
            'persediaan_id' => 'nullable|integer|exists:persediaan,id',
            'kode_kategori' => 'nullable|string|max:20',
            'kode_barang' => 'nullable|string|max:50',
            'jumlah_keluar' => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $persediaan = isset($validated['persediaan_id'])
                    ? Persediaan::lockForUpdate()->find($validated['persediaan_id'])
                    : Persediaan::where('kode_kategori', $validated['kode_kategori'] ?? '')
                        ->where('kode_barang', $validated['kode_barang'] ?? '')
                        ->lockForUpdate()->first();

                if (!$persediaan || $persediaan->jumlah < $validated['jumlah_keluar']) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'jumlah_keluar' => 'Stok persediaan tidak mencukupi!',
                    ]);
                }

                TransaksiKeluarPersediaan::create([
                    'persediaan_id' => $persediaan->id,
                    'tanggal_input' => $validated['tanggal_input'],
                    'kode_kategori' => $persediaan->kode_kategori,
                    'kategori' => $persediaan->kategori,
                    'kode_barang' => $persediaan->kode_barang,
                    'nama_barang' => $persediaan->nama_barang,
                    'satuan' => $persediaan->satuan,
                    'jumlah_keluar' => $validated['jumlah_keluar'],
                    'harga' => $persediaan->harga_satuan,
                    'user_id' => auth()->id(),
                ]);

                $jumlahBaru = $persediaan->jumlah - $validated['jumlah_keluar'];
                $persediaan->update([
                    'jumlah' => $jumlahBaru,
                    'harga_total' => $jumlahBaru * $persediaan->harga_satuan,
                ]);
            });
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()->route('adminpersediaan.transaksi-keluar')
            ->with('success', 'Transaksi keluar berhasil disimpan dan stok telah dikurangi!');
    }

    // ========== DOWNLOAD TEMPLATE EXCEL PERSEDIAAN ==========
    public function downloadTemplate()
    {
        return Excel::download(new PersediaanTemplateExport, 'Template_Import_Persediaan.xlsx');
    }

    // ========== PROSES IMPORT EXCEL PERSEDIAAN ==========
    public function importPersediaan(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file_excel.required' => 'Silakan pilih file Excel terlebih dahulu.',
            'file_excel.mimes'    => 'Format file harus .xlsx, .xls, atau .csv.',
            'file_excel.max'      => 'Ukuran file maksimal 5MB.'
        ]);

        try {
            $import = new PersediaanImport();
            Excel::import($import, $request->file('file_excel'));

            $total = $import->insertedCount + $import->updatedCount;
            if ($total === 0) {
                if (!empty($import->lastError)) {
                    return back()->with('error', 'Gagal menyimpan baris data ke database: ' . $import->lastError);
                } elseif ($import->rowsCount === 0) {
                    return back()->with('error', 'File Excel terbaca kosong (0 baris data). Pastikan data berada di Sheet pertama (Sheet 1) dan bukan di Sheet 2.');
                } else {
                    $keys = !empty($import->debugKeys) ? implode(', ', $import->debugKeys) : 'tidak ada';
                    return back()->with('error', "Terbaca {$import->rowsCount} baris di Excel, tetapi tidak ada nama barang yang valid. Kolom terbaca: [{$keys}].");
                }
            }

            $pesan = "Berhasil memproses {$total} data persediaan ({$import->insertedCount} baru ditambahkan, {$import->updatedCount} diperbarui)";
            if ($import->skippedCount > 0) {
                $pesan .= ", {$import->skippedCount} baris kosong/tidak valid dilewati";
            }
            $pesan .= "!";

            return redirect()->route('adminpersediaan.data-persediaan')->with('success', $pesan);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            return back()->with('error', 'Gagal mengimpor file! Pastikan format tabel sesuai dengan template.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /** SHOW - Detail transaksi */
    public function showTransaksiKeluar(TransaksiKeluarPersediaan $transaksiKeluar)
    {
        return response()->json($transaksiKeluar);
    }

    /** EDIT - Form edit */
    public function editTransaksiKeluar(TransaksiKeluarPersediaan $transaksiKeluar)
    {
        $persediaan = Persediaan::select('kode_barang', 'nama_barang')
            ->orderBy('kategori')
            ->get();

        return view('adminpersediian.form_transaksi_keluar', [
            'transaksi' => $transaksiKeluar,
            'persediaan' => $persediaan
        ]);
    }

    /** UPDATE - Update transaksi */    
    public function updateTransaksiKeluar(Request $request, 
    TransaksiKeluarPersediaan $transaksiKeluar)
    {
        $validated = $request->validate([
            'tanggal_input' => 'nullable|date',
            'persediaan_id' => 'nullable|integer|exists:persediaan,id',
            'kode_kategori' => 'nullable|string|max:20',
            'kode_barang' => 'nullable|string|max:50',
            'jumlah_keluar' => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($validated, $transaksiKeluar) {
                $persediaanLama = $transaksiKeluar->persediaan_id
                    ? Persediaan::lockForUpdate()->find($transaksiKeluar->persediaan_id)
                    : Persediaan::where('kode_kategori', $transaksiKeluar->kode_kategori)
                        ->where('kode_barang', $transaksiKeluar->kode_barang)
                        ->lockForUpdate()->first();

                $persediaanBaru = isset($validated['persediaan_id'])
                    ? Persediaan::lockForUpdate()->find($validated['persediaan_id'])
                    : Persediaan::where('kode_kategori', $validated['kode_kategori'] ?? '')
                        ->where('kode_barang', $validated['kode_barang'] ?? '')
                        ->lockForUpdate()->first();

                if (!$persediaanBaru) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'persediaan_id' => 'Data persediaan tidak ditemukan.',
                    ]);
                }

                $barangSama = $persediaanLama && $persediaanLama->id === $persediaanBaru->id;
                $stokTersedia = $persediaanBaru->jumlah + ($barangSama ? $transaksiKeluar->jumlah_keluar : 0);

                if ($stokTersedia < $validated['jumlah_keluar']) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'jumlah_keluar' => 'Stok persediaan tidak mencukupi!',
                    ]);
                }

                if (!$barangSama && $persediaanLama) {
                    $stokLama = $persediaanLama->jumlah + $transaksiKeluar->jumlah_keluar;
                    $persediaanLama->update([
                        'jumlah' => $stokLama,
                        'harga_total' => $stokLama * $persediaanLama->harga_satuan,
                    ]);
                }

                $stokBaru = $stokTersedia - $validated['jumlah_keluar'];
                $persediaanBaru->update([
                    'jumlah' => $stokBaru,
                    'harga_total' => $stokBaru * $persediaanBaru->harga_satuan,
                ]);

                $transaksiKeluar->update([
                    'persediaan_id' => $persediaanBaru->id,
                    'tanggal_input' => $validated['tanggal_input'] ?? $transaksiKeluar->tanggal_input,
                    'kode_kategori' => $persediaanBaru->kode_kategori,
                    'kategori' => $persediaanBaru->kategori,
                    'kode_barang' => $persediaanBaru->kode_barang,
                    'nama_barang' => $persediaanBaru->nama_barang,
                    'satuan' => $persediaanBaru->satuan,
                    'jumlah_keluar' => $validated['jumlah_keluar'],
                    'harga' => $persediaanBaru->harga_satuan,
                ]);
            });
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()->route('adminpersediaan.transaksi-keluar')
            ->with('success', 'Transaksi keluar dan stok berhasil diperbarui!');
    }

    /** DESTROY - Hapus transaksi keluar */
    public function destroyTransaksiKeluar(TransaksiKeluarPersediaan $transaksiKeluar)
    {
        DB::transaction(function () use ($transaksiKeluar) {
            $persediaan = $transaksiKeluar->persediaan_id
                ? Persediaan::lockForUpdate()->find($transaksiKeluar->persediaan_id)
                : Persediaan::where('kode_kategori', $transaksiKeluar->kode_kategori)
                    ->where('kode_barang', $transaksiKeluar->kode_barang)
                    ->lockForUpdate()->first();

            if ($persediaan) {
                $jumlahBaru = $persediaan->jumlah + $transaksiKeluar->jumlah_keluar;
                $persediaan->update([
                    'jumlah' => $jumlahBaru,
                    'harga_total' => $jumlahBaru * $persediaan->harga_satuan,
                ]);
            }

            $transaksiKeluar->delete();
        });

        return redirect()->route('adminpersediaan.transaksi-keluar')
            ->with('success', 'Transaksi keluar berhasil dihapus!');
    }

    // 📥 Transaksi Masuk Persediaan
    /** INDEX - Tampilkan daftar transaksi masuk */
    public function transaksiMasuk(Request $request)
    {
        $query = TransaksiMasukPersediaan::query();

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_barang', 'like', '%' . $request->search . '%')
                    ->orWhere('nama_barang', 'like', '%' . $request->search . '%')
                    ->orWhere('no', 'like', '%' . $request->search . '%')
                    ->orWhere('kode_kategori', 'like', '%' . $request->search . '%')
                    ->orWhere('kategori', 'like', '%' . $request->search . '%');
            });
        }

        // Filter tanggal
        if ($request->filled('tanggal_input')) {
            $query->whereDate('tanggal_input', $request->tanggal_input);
        }

        // Filter kategori
        if ($request->filled('kode_kategori')) {
            $query->where('kode_kategori', $request->kode_kategori);
        }

        $transaksi = $query->latest('tanggal_input')->paginate(10);
        $daftarPersediaan = Persediaan::orderBy('nama_barang')
            ->orderBy('kode_barang')
            ->get([
                'id',
                'kode_kategori',
                'kategori',
                'kode_barang',
                'kode_unik_barang',
                'nama_barang',
                'satuan',
                'harga_satuan',
                'jumlah',
            ]);

        return view('adminpersediian.transaksi_masuk', compact('transaksi', 'daftarPersediaan'));
    }

    /** CREATE - Tampilkan form tambah */
    public function createTransaksiMasuk()
    {
        return view('adminpersediian.form_transaksi_masuk');
    }

    public function storeTransaksiMasuk(Request $request)
    {
        // 1. Ambil input harga satuan mentah
        $raw_harga = $request->harga_satuan;

        // 2. Jika input mengandung koma desimal (seperti 75.000,00), buang bagian setelah koma
        if (strpos($raw_harga, ',') !== false) {
            $raw_harga = explode(',', $raw_harga)[0];
        }

        // 3. Bersihkan titik ribuan agar murni menjadi angka numerik (contoh: 75.000 menjadi 75000)
        $clean_harga = str_replace('.', '', $raw_harga);

        // 4. Masukkan kembali ke dalam request sebelum validasi dijalankan
        $request->merge([
            'harga_satuan' => $clean_harga
        ]);

        // 5. Validasi data transaksi
        $validated = $request->validate([
            'tanggal_input' => 'required|date',
            'kode_kategori' => 'required|string|max:20',
            'kategori'      => 'required|string|max:100',
            'kode_barang'   => 'required|string|max:50',
            'nama_barang'   => 'required|string|max:200',
            'satuan'        => 'required|string|max:50', // VALIDASI SATUAN
            'jumlah_masuk'  => 'required|integer|min:1',
            'harga_satuan'  => 'required|numeric|min:0',
            
        ]);

        // 6. Simpan transaksi (Pastikan field total dihitung murni secara otomatis)
        $validated['total'] = $validated['harga_satuan'] * $validated['jumlah_masuk'];
        $validated['user_id'] = auth()->id();

        // Riwayat transaksi dan stok master harus berhasil atau gagal bersama-sama.
        DB::transaction(function () use ($validated) {
            TransaksiMasukPersediaan::create($validated);
            $this->tambahkanKePersediaan($validated);
        });

        return redirect()->route('adminpersediaan.transaksi-masuk')
            ->with('success', 'Transaksi masuk berhasil disimpan dan stok Data Persediaan telah ditambahkan!');
    }

    /** SHOW - Detail transaksi */
    public function showTransaksiMasuk(TransaksiMasukPersediaan $transaksiMasuk)
    {
        return view('adminpersediian.detail_transaksi_masuk', compact('transaksiMasuk'));
    }

    /** EDIT - Form edit */
    public function editTransaksiMasuk(TransaksiMasukPersediaan $transaksiMasuk)
    {
        return view('adminpersediian.form_transaksi_masuk', compact('transaksiMasuk'));
    }

    /** UPDATE - Update transaksi masuk */
    public function updateTransaksiMasuk(Request $request, $id)
    {
        // 1. Temukan data transaksi masuk yang akan diubah
        $transaksiMasuk = \App\Models\TransaksiMasukPersediaan::findOrFail($id);

        // 2. Ambil input harga satuan mentah dari form edit
        $raw_harga = $request->harga_satuan;

        // 3. Jika input mengandung koma desimal, buang bagian setelah koma
        if (strpos($raw_harga, ',') !== false) {
            $raw_harga = explode(',', $raw_harga)[0];
        }

        // 4. Bersihkan titik ribuan agar kembali menjadi angka clean (75.000 -> 75000)
        $clean_harga = str_replace('.', '', $raw_harga);

        // 5. Masukkan kembali ke request
        $request->merge([
            'harga_satuan' => $clean_harga
        ]);

        // 6. Jalankan validasi
        $validated = $request->validate([
            'tanggal_input' => 'required|date',
            'kode_kategori' => 'required|string|max:20',
            'kategori'      => 'required|string|max:100',
            'kode_barang'   => 'required|string|max:50',
            'nama_barang'   => 'required|string|max:200',
            'satuan'        => 'required|string|max:50', // VALIDASI SATUAN
            'jumlah_masuk'  => 'required|integer|min:1',
            'harga_satuan'  => 'required|numeric|min:0',
        ]);

        // 7. Hitung ulang total
        $validated['total'] = $validated['harga_satuan'] * $validated['jumlah_masuk'];

        DB::transaction(function () use ($transaksiMasuk, $validated) {
            // Batalkan dahulu dampak transaksi lama, lalu terapkan data yang baru.
            $this->kurangiDariPersediaan($transaksiMasuk);
            $transaksiMasuk->update($validated);
            $this->tambahkanKePersediaan($validated);
        });

        return redirect()->route('adminpersediaan.transaksi-masuk')
            ->with('success', 'Transaksi masuk dan stok Data Persediaan berhasil diperbarui!');
    }

    /** DESTROY - Hapus transaksi */
    public function destroyTransaksiMasuk(TransaksiMasukPersediaan $transaksiMasuk)
    {
        DB::transaction(function () use ($transaksiMasuk) {
            $this->kurangiDariPersediaan($transaksiMasuk);
            $transaksiMasuk->delete();
        });

        return redirect()->route('adminpersediaan.transaksi-masuk')
            ->with('success', 'Transaksi masuk dihapus dan stok Data Persediaan telah disesuaikan!');
    }

    /** Tambahkan dampak transaksi masuk ke master Data Persediaan. */
    private function tambahkanKePersediaan(array $data): void
    {
        $persediaan = Persediaan::where('kode_kategori', $data['kode_kategori'])
            ->where('kode_barang', $data['kode_barang'])
            ->lockForUpdate()
            ->first();

        if (!$persediaan) {
            Persediaan::create([
                'kode_kategori' => $data['kode_kategori'],
                'kategori'      => $data['kategori'],
                'kode_barang'   => $data['kode_barang'],
                'nama_barang'   => $data['nama_barang'],
                'satuan'        => $data['satuan'],
                'tanggal_masuk' => $data['tanggal_input'],
                'harga_satuan'  => $data['harga_satuan'],
                'jumlah'        => $data['jumlah_masuk'],
                'harga_total'   => $data['total'],
            ]);

            return;
        }

        $jumlahBaru = $persediaan->jumlah + $data['jumlah_masuk'];
        $persediaan->update([
            'kode_kategori' => $data['kode_kategori'],
            'kategori'      => $data['kategori'],
            'nama_barang'   => $data['nama_barang'],
            'satuan'        => $data['satuan'],
            'tanggal_masuk' => $data['tanggal_input'],
            'harga_satuan'  => $data['harga_satuan'],
            'jumlah'        => $jumlahBaru,
            'harga_total'   => $jumlahBaru * $data['harga_satuan'],
        ]);
    }

    /** Batalkan dampak transaksi masuk dari master Data Persediaan. */
    private function kurangiDariPersediaan(TransaksiMasukPersediaan $transaksi): void
    {
        $persediaan = Persediaan::where('kode_kategori', $transaksi->kode_kategori)
            ->where('kode_barang', $transaksi->kode_barang)
            ->lockForUpdate()
            ->first();

        if (!$persediaan) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'jumlah_masuk' => 'Data persediaan untuk transaksi ini tidak ditemukan.',
            ]);
        }

        if ($persediaan->jumlah < $transaksi->jumlah_masuk) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'jumlah_masuk' => 'Transaksi tidak dapat diubah atau dihapus karena sebagian stok sudah digunakan.',
            ]);
        }

        $jumlahBaru = $persediaan->jumlah - $transaksi->jumlah_masuk;

        if ($jumlahBaru === 0) {
            $persediaan->delete();
            return;
        }

        $persediaan->update([
            'jumlah' => $jumlahBaru,
            'harga_total' => $jumlahBaru * $persediaan->harga_satuan,
        ]);
    }

    //PERMINTAAN PERSEDIAAN
    public function permintaanPersediaan(Request $request)
    {
        $query = PermintaanPersediaan::with(['user', 'persediaan', 'reviewedBy'])
            ->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('persediaan', fn($x) => $x->where('nama_barang', 'like', '%' . $request->search . '%'))
                    ->orWhere('nama_lengkap', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $permintaan = PermintaanPersediaan::with(['user', 'persediaan'])
            ->latest()
            ->get();

        $permintaan = $query->paginate(10);
        return view('adminpersediian.permintaan_persediaan', compact('permintaan'));
    }

    public function showPermintaan($id)
    {
        $permintaan = PermintaanPersediaan::with(['persediaan', 'user', 'reviewedBy', 'approvedByKasubag'])
            ->findOrFail($id);

        return view('adminpersediian.detail_permintaan', compact('permintaan'));
    }

    public function reviewPermintaan(Request $request, $id)
    {
        $permintaan = PermintaanPersediaan::findOrFail($id);

        // Pastikan hanya permintaan berstatus pending yang bisa direview admin
        if (!in_array($permintaan->status, ['pending'])) {
            return redirect()->route('adminpersediaan.permintaan-persediaan')
                ->with('error', 'Permintaan sudah diproses sebelumnya!');
        }

        try {
            if ($request->action === 'teruskan' || $request->action === 'setuju') {
                DB::beginTransaction();

                // 1. Cari data persediaan master
                $persediaan = $permintaan->persediaan;
                if (!$persediaan && $permintaan->persediaan_id) {
                    $persediaan = Persediaan::find($permintaan->persediaan_id);
                }
                if (!$persediaan && $permintaan->kode_barang) {
                    $persediaan = Persediaan::where('kode_barang', $permintaan->kode_barang)->first();
                }

                $maxStok = $persediaan ? (int)$persediaan->jumlah : 999999;
                
                $request->validate([
                    'jumlah_disetujui' => 'required|integer|min:1|max:' . $maxStok
                ], [
                    'jumlah_disetujui.required' => 'Jumlah yang disetujui wajib diisi.',
                    'jumlah_disetujui.integer' => 'Jumlah yang disetujui harus berupa angka.',
                    'jumlah_disetujui.min' => 'Jumlah yang disetujui minimal 1.',
                    'jumlah_disetujui.max' => 'Gagal menyetujui! Jumlah disetujui melebihi sisa stok fisik (' . $maxStok . ').'
                ]);

                $jumlahDisetujui = (int)$request->jumlah_disetujui;

                // Cek ketersediaan stok fisik
                if ($persediaan && $persediaan->jumlah < $jumlahDisetujui) {
                    DB::rollBack();
                    return redirect()->route('adminpersediaan.permintaan-persediaan')
                        ->with('error', "Gagal! Sisa stok barang '{$persediaan->nama_barang}' ({$persediaan->jumlah}) tidak mencukupi untuk disetujui ({$jumlahDisetujui}).");
                }

                // 2. Update status permintaan persediaan
                $updateData = [
                    'status' => 'disetujui',
                    'reviewed_by_adminpersediaan_id' => Auth::id(),
                    'approved_by_kasubag_id' => Auth::id(),
                    'jumlah_disetujui' => $jumlahDisetujui,
                ];

                if (\Illuminate\Support\Facades\Schema::hasColumn('permintaan_persediaan', 'tanggal_penerimaan')) {
                    $updateData['tanggal_penerimaan'] = now()->toDateString();
                }

                $permintaan->update($updateData);

                // 3. Potong stok dan catat transaksi keluar jika data master persediaan ditemukan
                if ($persediaan) {
                    $sisaStok = max(0, $persediaan->jumlah - $jumlahDisetujui);
                    $hargaSatuan = $persediaan->harga_satuan ?? 0;

                    DB::table('persediaan')->where('id', $persediaan->id)->update([
                        'jumlah' => $sisaStok,
                        'harga_total' => $sisaStok * $hargaSatuan,
                    ]);

                    $transaksiData = [
                        'tanggal_input' => now()->toDateString(),
                        'kode_kategori' => $persediaan->kode_kategori ?? '-',
                        'kategori'      => $persediaan->kategori ?? '-',
                        'kode_barang'   => $persediaan->kode_barang ?? ($permintaan->kode_barang ?? '-'),
                        'nama_barang'   => $persediaan->nama_barang ?? ($permintaan->nama_barang ?? '-'),
                        'jumlah_keluar' => $jumlahDisetujui,
                        'harga'         => $hargaSatuan,
                        'total'         => $hargaSatuan * $jumlahDisetujui,
                        'satuan'        => $persediaan->satuan ?? ($permintaan->satuan ?? 'Unit'),
                        'user_id'       => Auth::id() ?? $permintaan->user_id,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ];

                    if (\Illuminate\Support\Facades\Schema::hasColumn('transaksi_keluar_persediaan', 'persediaan_id')) {
                        $transaksiData['persediaan_id'] = $persediaan->id;
                    }

                    DB::table('transaksi_keluar_persediaan')->insert($transaksiData);
                }

                DB::commit();
                return redirect()->route('adminpersediaan.permintaan-persediaan')
                    ->with('success', 'Permintaan persediaan berhasil disetujui dan stok gudang telah diperbarui!');

            } elseif ($request->action === 'tolak') {
                $updateData = [
                    'status' => 'ditolak',
                    'reviewed_by_adminpersediaan_id' => Auth::id(),
                ];

                if (\Illuminate\Support\Facades\Schema::hasColumn('permintaan_persediaan', 'komentar')) {
                    $updateData['komentar'] = $request->komentar;
                }

                $permintaan->update($updateData);
                return redirect()->route('adminpersediaan.permintaan-persediaan')
                    ->with('success', 'Permintaan persediaan berhasil ditolak!');
            }

            return redirect()->route('adminpersediaan.permintaan-persediaan')
                ->with('error', 'Aksi tidak valid.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            return redirect()->route('adminpersediaan.permintaan-persediaan')
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Throwable $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            try {
                \Illuminate\Support\Facades\Log::error('Error saat review permintaan persediaan: ' . $e->getMessage());
            } catch (\Throwable $logEx) {}

            return redirect()->route('adminpersediaan.permintaan-persediaan')
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    // 1. FUNGSI GENERATE SURAT (Dilengkapi TTD Base64)
    public function generateSuratPermintaan(Request $request, PermintaanPersediaan $permintaan)
    {
        $request->validate(['tanggal_surat' => 'nullable|date']);
        $tanggalSurat = $request->date('tanggal_surat');
        $permintaan->loadMissing(['items', 'user', 'persediaan']);
        // Ambil data user terkait
        $peminjam = $permintaan->user;
        $admin = Auth::user();
        $kasubag = User::where('role', 'kasubag')->first(); // Ambil akun kasubag
        $kepala = User::where('role', 'kepalabpmp')->first(); // Ambil akun kepala

        // Fungsi bantuan untuk mengubah gambar lokal menjadi Base64
        $getBase64 = function ($path) {
            if ($path && Storage::disk('public')->exists($path)) {
                $type = pathinfo(storage_path('app/public/' . $path), PATHINFO_EXTENSION);
                $data = Storage::disk('public')->get($path);
                return 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
            return null;
        };

        // Siapkan TTD (Gunakan properti ->signature sesuai nama di db mu)
        $ttdPeminjam = $peminjam ? $getBase64($peminjam->signature) : null;
        $ttdAdmin = $getBase64($admin->signature);
        $ttdKasubag = $kasubag ? $getBase64($kasubag->signature) : null;
        $ttdKepala = $kepala ? $getBase64($kepala->signature) : null;

        // Load View PDF
        $pdf = PDF::loadView('surat.permintaan_persediaan', compact(
            'permintaan',
            'peminjam',
            'admin',
            'kasubag',
            'kepala',
            'ttdPeminjam',
            'ttdAdmin',
            'ttdKasubag',
            'ttdKepala',
            'tanggalSurat'
        ));

        return $pdf->download('Berita_Acara_Permintaan_' . $permintaan->id . '.pdf');
    }

    // 2. FUNGSI UPLOAD SURAT BAST FINAL OLEH ADMIN
    public function uploadSuratBast(Request $request, PermintaanPersediaan $permintaan)
    {
        $request->validate([
            'surat_bast' => 'required|mimes:pdf|max:2048' // Wajib PDF, max 2MB
        ]);

        if ($request->hasFile('surat_bast')) {
            // Hapus file lama jika admin mengupload ulang
            if ($permintaan->surat_bast_path && Storage::disk('public')->exists($permintaan->surat_bast_path)) {
                Storage::disk('public')->delete($permintaan->surat_bast_path);
            }

            $filename = 'BAST_Persediaan_' . $permintaan->id . '_' . time() . '.pdf';
            $path = $request->file('surat_bast')->storeAs('surat_bast', $filename, 'public');

            $permintaan->update([
                'surat_bast_path' => $path,
                // 'status' => 'selesai' // Opsional, update status menjadi selesai
            ]);

            return back()->with('success', 'Surat Persetujuan Final berhasil diunggah!');
        }

        return back()->with('error', 'Gagal mengunggah surat.');
    }

    public function laporanPermintaanPersediaan()
    {
        // 1. Ambil SEMUA data permintaan
        $permintaan = PermintaanPersediaan::with(['user', 'persediaan'])->latest()->get();

        // 2. Statistik Keseluruhan
        $stats = [
            'total' => $permintaan->count(),
            'diproses' => $permintaan->whereIn('status', ['pending', 'dalam_review'])->count(),
            'ditolak' => $permintaan->whereIn('status', ['ditolak', 'ditolak_kasubag'])->count(),
            'disetujui' => $permintaan->whereIn('status', ['disetujui', 'disetujui_kasubag'])->count(),
        ];

        // 3. Statistik Bulan Ini 
        // 🔥 PERBAIKAN 1: Tambahkan '?->' agar aman dari error tanggal null
        $bulanIni = $permintaan->filter(fn($item) => $item->created_at?->isCurrentMonth());
        $statsBulanIni = [
            'total' => $bulanIni->count(),
            'disetujui' => $bulanIni->whereIn('status', ['disetujui', 'disetujui_kasubag'])->count(),
            'pending' => $bulanIni->whereIn('status', ['pending', 'dalam_review'])->count(),
            'ditolak' => $bulanIni->whereIn('status', ['ditolak', 'ditolak_kasubag'])->count(),
        ];

        // 4. Perhitungan Lanjutan (Rata-rata & Persentase)
        $approvalRate = $stats['total'] > 0 ? round(($stats['disetujui'] / $stats['total']) * 100) : 0;
        
        // 🔥 PERBAIKAN 2: Hindari method ->avg() yang sering error pada Collection. 
        // Gunakan ->sum() dibagi total agar 100% aman dan akurat.
        $avgItems = $stats['total'] > 0 ? round($permintaan->sum('jumlah_diminta') / $stats['total']) : 0;

        // 5. Data Grafik Bulanan (Januari - Desember Tahun Ini)
        $monthlyData = [];
        $maxMonth = 1; // Untuk rasio tinggi diagram batang
        for ($i = 1; $i <= 12; $i++) {
            // 🔥 PERBAIKAN 3: Tambahkan '?->' pada pencarian bulan
            $count = $permintaan->filter(fn($item) => $item->created_at?->month == $i && $item->created_at?->year == date('Y'))->count();
            $monthlyData[$i] = $count;
            if ($count > $maxMonth) $maxMonth = $count;
        }

        // 6. Data Tab Gabungan (Dikelompokkan berdasarkan Pemohon)
        $summaryData = $permintaan->groupBy('nama_lengkap')->map(function ($group) {
            return [
                'total' => $group->count(),
                'disetujui' => $group->whereIn('status', ['disetujui', 'disetujui_kasubag'])->count(),
                'pending' => $group->whereIn('status', ['pending', 'dalam_review'])->count(),
                'ditolak' => $group->whereIn('status', ['ditolak', 'ditolak_kasubag'])->count(),
            ];
        });

        return view('adminpersediian.laporan_permintaan_persediaan', compact(
            'permintaan',
            'stats',
            'statsBulanIni',
            'approvalRate',
            'avgItems',
            'monthlyData',
            'maxMonth',
            'summaryData'
        ));
    }
    // LAPORAN TRANSAKSI MASUK

    public function laporanTransaksiMasuk(Request $request)
    {
        $query = TransaksiMasukPersediaan::query();

        // Filters...
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_barang', 'like', '%' . $request->search . '%')
                    ->orWhere('nama_barang', 'like', '%' . $request->search . '%')
                    ->orWhere('kode_transaksi', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('tanggal_input')) {
            $query->whereDate('tanggal_input', $request->tanggal_input);
        }
        if ($request->filled('kode_kategori')) {
            $query->where('kode_kategori', $request->kode_kategori);
        }

        $transaksi = $query->latest()->paginate(10);

        return view('adminpersediian.laporan_transaksimasuk', compact('transaksi'));
    }

    /**
     * Download Laporan Transaksi Masuk PDF
     */
    public function downloadLaporanTransaksiMasuk(Request $request)
    {
        $query = TransaksiMasukPersediaan::query();

        // Filter sama persis
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_barang', 'like', '%' . $request->search . '%')
                    ->orWhere('nama_barang', 'like', '%' . $request->search . '%')
                    ->orWhere('kode_kategori', 'like', '%' . $request->search . '%')
                    ->orWhere('kategori', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('tanggal_input')) {
            $query->whereDate('tanggal_input', $request->tanggal_input);
        }

        if ($request->filled('kode_kategori')) {
            $query->where('kode_kategori', $request->kode_kategori);
        }

        $transaksi = $query->latest()->get(); // Semua data untuk PDF

        $stats = [
            'total_transaksi' => $transaksi->count(),
            'total_nilai' => $transaksi->sum('total'),
            'total_item' => $transaksi->sum('jumlah_masuk'),
        ];

        // ✅ PAKAI BLADE YANG SUDAH ADA!
        $pdf = PDF::loadView('adminpersediian.laporan_transaksimasuk_pdf', compact('transaksi', 'stats'));

        $filename = 'Laporan_Transaksi_Masuk_' . now()->format('d-m-Y_His') . '.pdf';

        return $pdf->download($filename);
    }
    /**
     * LAPORAN TRANSAKSI KELUAR - Index dengan chart & stats
     */
    public function laporanTransaksiKeluar(Request $request)
    {
        // 1. Tambahkan eager loading 'persediaan' (atau nama relasi master data Anda)
        // Ini berfungsi agar satuan barang bisa dipanggil di view
        $query = TransaksiKeluarPersediaan::with('persediaan');

        // Filters
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_barang', 'like', '%' . $request->search . '%')
                    ->orWhere('nama_barang', 'like', '%' . $request->search . '%')
                    ->orWhere('nomor_transaksi', 'like', '%' . $request->search . '%')
                    ->orWhere('kode_kategori', 'like', '%' . $request->search . '%')
                    ->orWhere('kategori', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('tanggal_input')) {
            $query->whereDate('tanggal_input', $request->tanggal_input);
        }

        if ($request->filled('kode_kategori')) {
            $query->where('kode_kategori', $request->kode_kategori);
        }

        // 2. Hitung Summary (TOTAL) BERDASARKAN FILTER SEBELUM PAGINATION
        // Gunakan 'clone' agar kondisi $query (filter) ikut terhitung tapi tidak merusak query utama
        $totalTransaksi = (clone $query)->count();
        $totalItem      = (int) (clone $query)->sum('jumlah_keluar');
        $totalNilai     = (int) (clone $query)->sum('total');

        // 3. Eksekusi Pagination
        $transaksi = $query->latest()->paginate(10);

        // 📊 CHART & STATS DATA
        $chartData = [];

        // 1. Chart: Total Keluar per Bulan (12 bulan terakhir)
        $startDate = now()->subMonths(11)->startOfMonth();
        $isSqlite = DB::connection()->getDriverName() === 'sqlite';
        $dateFormatRaw = $isSqlite 
            ? "strftime('%Y-%m', tanggal_input) as bulan"
            : 'DATE_FORMAT(tanggal_input, "%Y-%m") as bulan';

        $monthlyData = TransaksiKeluarPersediaan::selectRaw("
                {$dateFormatRaw},
                SUM(jumlah_keluar) as total_jumlah,
                SUM(total) as total_nilai
            ")
            ->where('tanggal_input', '>=', $startDate)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->keyBy('bulan');

        $chartData['monthly'] = [
            'labels' => [],
            'jumlah_data' => [],
            'nilai_data' => []
        ];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i)->format('Y-m');
            $monthName = now()->subMonths($i)->translatedFormat('M Y');

            $chartData['monthly']['labels'][] = $monthName;
            $chartData['monthly']['jumlah_data'][] = (int)($monthlyData[$date]->total_jumlah ?? 0);
            $chartData['monthly']['nilai_data'][] = (int)($monthlyData[$date]->total_nilai ?? 0);
        }

        // 2. Top 5 Kategori (berdasarkan jumlah keluar)
        $chartData['top_kategori'] = TransaksiKeluarPersediaan::selectRaw('
                kode_kategori, kategori,
                COUNT(*) as total_transaksi,
                SUM(jumlah_keluar) as total_jumlah,
                SUM(total) as total_nilai
            ')
            ->where('tanggal_input', '>=', now()->subMonths(3))
            ->groupBy('kode_kategori', 'kategori')
            ->orderByDesc('total_jumlah')
            ->limit(5)
            ->get();

        // 3. Summary Stats (Dimasukkan ke chartData jika Anda memakainya di view)
        $chartData['summary'] = [
            'total_transaksi'     => $totalTransaksi,
            'total_jumlah'        => $totalItem,
            'total_nilai'         => $totalNilai,
            'rata_rata_transaksi' => $totalTransaksi > 0 ? $totalNilai / $totalTransaksi : 0,
        ];

        // Pastikan variabel total juga di-passing sebagai variabel terpisah 
        // agar mudah dipanggil langsung di card Blade Anda
        return view('adminpersediian.laporan_transaksikeluar', compact(
            'transaksi', 
            'chartData',
            'totalTransaksi',
            'totalItem',
            'totalNilai'
        ));
    }

    /**
     * Download Laporan Transaksi Keluar PDF
     */
    public function downloadLaporanTransaksiKeluarPdf(Request $request)
    {
        $query = TransaksiKeluarPersediaan::with('persediaan');

        // Terapkan filter yang sama dengan web
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_barang', 'like', "%{$search}%")
                    ->orWhere('nama_barang', 'like', "%{$search}%")
                    ->orWhere('nomor_transaksi', 'like', "%{$search}%")
                    ->orWhere('kode_kategori', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%");
            });
        }
        if ($request->filled('tanggal_input')) {
            $query->whereDate('tanggal_input', $request->tanggal_input);
        }
        if ($request->filled('kode_kategori')) {
            $query->where('kode_kategori', $request->kode_kategori);
        }

        // Ambil semua data tanpa pagination
        $transaksi = $query->orderBy('tanggal_input', 'desc')->get();

        // Generate PDF
        $pdf = Pdf::loadView('adminpersediian.pdf_laporan_transaksi_keluar', compact('transaksi'))
            ->setPaper('A4', 'landscape');

        return $pdf->download('Laporan_Transaksi_Keluar_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Download Laporan Permintaan Persediaan (PDF)
     */
    public function downloadLaporanPermintaan(Request $request)
    {
        // Gunakan eager loading untuk efisiensi query
        $query = \App\Models\PermintaanPersediaan::with(['user', 'persediaan']);

        // Terapkan filter yang sama dengan tampilan web jika diperlukan
        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
                    ->orWhereHas('persediaan', function ($p) use ($request) {
                        $p->where('nama_barang', 'like', '%' . $request->search . '%');
                    });
            });
        }

        // Ambil semua data sesuai filter tanpa pagination
        $permintaan = $query->orderBy('tanggal_permintaan', 'desc')->get();

        // Hitung total item yang disetujui untuk laporan
        $totalItemDisetujui = $permintaan->whereIn('status', ['disetujui', 'disetujui_kasubag'])->sum('jumlah_diminta');
        $totalTransaksiDisetujui = $permintaan->whereIn('status', ['disetujui', 'disetujui_kasubag'])->count();

        // Generate PDF
        $pdf = Pdf::loadView('adminpersediian.pdf_laporan_permintaan', compact('permintaan', 'totalItemDisetujui', 'totalTransaksiDisetujui'))
            ->setPaper('A4', 'landscape');

        // Download file
        return $pdf->download('Laporan_Permintaan_Persediaan_' . now()->format('Y-m-d') . '.pdf');
    }
}
