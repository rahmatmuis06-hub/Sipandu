<?php

namespace App\Http\Controllers;

use App\Exports\PersediaanTemplateExport;
use App\Imports\PersediaanImport;
use App\Jobs\SendFonnteNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
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
        // Bersihkan titik ribuan dan pastikan jika ada koma desimal (,00) dibersihkan dengan benar
        $clean_harga = str_replace('.', '', $request->harga_satuan);
        if (strpos($clean_harga, ',') !== false) {
            $clean_harga = explode(',', $clean_harga)[0]; // Mengambil angka sebelum koma desimal
        }

        $request->merge([
            'harga_satuan' => $clean_harga
        ]);

        $request->validate([
            'kode_kategori' => 'required|string|max:20',
            'kategori' => 'required|string|max:100',
            'kode_barang' => 'required|string|max:50|unique:persediaan,kode_barang',
            'nama_barang' => 'required|string|max:200',
            'satuan' => 'required|string|max:50', // VALIDASI SATUAN
            'tanggal_masuk' => 'required|date',
            'harga_satuan' => 'required|numeric|min:0',
            'jumlah' => 'required|integer|min:1',
        ]);

        Persediaan::create($request->all() + [
            'harga_total' => $request->harga_satuan * $request->jumlah,
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
        // Bersihkan titik ribuan dan pastikan jika ada koma desimal (,00) dibersihkan dengan benar
        $clean_harga = str_replace('.', '', $request->harga_satuan);
        if (strpos($clean_harga, ',') !== false) {
            $clean_harga = explode(',', $clean_harga)[0]; // Mengambil angka sebelum koma desimal
        }

        $request->merge([
            'harga_satuan' => $clean_harga
        ]);

        $request->validate([
            'kode_kategori' => 'required|string|max:20',
            'kategori' => 'required|string|max:100',
            'kode_barang' => ['required', 'string', 'max:50', Rule::unique('persediaan')->ignore($persediaan)],
            'nama_barang' => 'required|string|max:200',
            'satuan' => 'required|string|max:50', // VALIDASI SATUAN
            'tanggal_masuk' => 'required|date',
            'harga_satuan' => 'required|numeric|min:0',
            'jumlah' => 'required|integer|min:1',
        ]);

        $persediaan->update($request->all() + [
            'harga_total' => $request->harga_satuan * $request->jumlah,
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

    // 📤 Transaksi Keluar
    //=========TRANSAKSI KELUAR PERSEDIAAN========//

    /** INDEX - Tampilkan daftar transaksi keluar */
    public function transaksiKeluar(Request $request)
    {
        $query = TransaksiKeluarPersediaan::query();

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
        $masterPersediaan = \App\Models\Persediaan::orderBy('nama_barang')->get()->map(function($item) {
            return [
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
        $request->validate([
            'tanggal_input' => 'required|date',
            'kode_kategori' => 'required|string|max:20',
            'kategori' => 'required|string|max:100',
            'kode_barang' => 'required|string|max:50',
            'nama_barang' => 'required|string|max:200',
            'satuan' => 'required|string|max:50', // VALIDASI SATUAN
            'jumlah_keluar' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
        ]);

        // Cek stok persediaan
        $persediaan = Persediaan::where('kode_kategori', $request ->kode_kategori)
                                 ->where('kode_barang', $request->kode_barang)
                                 ->first();
        if (!$persediaan || $persediaan->jumlah < $request->jumlah_keluar) {
            return back()->withErrors(['jumlah_keluar' => 'Stok persediaan tidak mencukupi!'])
                ->withInput();
        }

        // Simpan transaksi
        TransaksiKeluarPersediaan::create($request->all());

        // Kurangi stok persediaan
        $persediaan->decrement('jumlah', $request->jumlah_keluar);

        return redirect()->route('adminpersediaan.transaksi-keluar')
            ->with('success', 'Transaksi keluar berhasil disimpan!');
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
            Excel::import(new PersediaanImport, $request->file('file_excel'));

            return redirect()->route('adminpersediaan.data-persediaan')
                ->with('success', 'Data Persediaan berhasil diimport secara massal!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            return back()->withErrors(['error' => 'Gagal mengimpor file! Pastikan format tabel sesuai dengan template.']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
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
        $request->validate([
            'kode_kategori' => 'required|string|max:20',
            'kategori' => 'required|string|max:100',
            'kode_barang' => 'required|string|max:50',
            'nama_barang' => 'required|string|max:200',
            'satuan' => 'required|string|max:50', // VALIDASI SATUAN
            'jumlah_keluar' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        // 🔥 VALIDASI STOK: Cek apakah kode barang berubah
        $persediaanLama = Persediaan::where('kode_kategori', $transaksiKeluar->kode_kategori)
                                      ->where('kode_barang', $transaksiKeluar->kode_barang)->first();
        $persediaanBaru = Persediaan::where('kode_kategori', $request->kode_kategori)
                                      ->where('kode_barang', $request->kode_barang)->first();

        // Cek stok persediaan BARU
        if (!$persediaanBaru || $persediaanBaru->jumlah < $request->jumlah_keluar) {
            return back()->withErrors(['jumlah_keluar' => 'Stok persediaan tidak mencukupi!'])
                ->withInput();
        }

        // 🔥 Backup data lama untuk adjust stok
        $oldJumlahKeluar = $transaksiKeluar->jumlah_keluar;
        $oldKodeBarang = $transaksiKeluar->kode_barang;
        $kodeBarangBerubah = $oldKodeBarang !== $request->kode_barang;

        // 🔥 SATU KALI UPDATE SAJA - Biarkan mutator handle total
        $transaksiKeluar->update($request->all());

        //🔥 Adjust stok persediaan
        if ($kodeBarangBerubah) {
            // Kembalikan stok BARANG LAMA
            if ($persediaanLama) {
                $persediaanLama->increment('jumlah', $oldJumlahKeluar);
            }
            // Kurangi stok BARANG BARU
            $persediaanBaru->decrement('jumlah', $request->jumlah_keluar);
        } 
        else {
            // Sama barang, adjust selisih jumlah
            $selisih = $oldJumlahKeluar - $request->jumlah_keluar;
            if ($selisih > 0) {
                $persediaanBaru->increment('jumlah', $selisih);
            } elseif ($selisih < 0) {
                $persediaanBaru->decrement('jumlah', abs($selisih));
            }
        }
        return redirect()->route('adminpersediaan.transaksi-keluar')
            ->with('success', 'Transaksi keluar berhasil diupdate!');
    }

    /** DESTROY - Hapus transaksi keluar */
    public function destroyTransaksiKeluar(TransaksiKeluarPersediaan $transaksiKeluar)
    {
        // Kembalikan stok persediaan
        $persediaan = Persediaan::where('kode_kategori', $transaksiKeluar->kode_kategori)
                                  ->where('kode_barang', $transaksiKeluar->kode_barang)->first();
        if ($persediaan) {
            $persediaan->increment('jumlah', $transaksiKeluar->jumlah_keluar);
        }

        $transaksiKeluar->delete();

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

        return view('adminpersediian.transaksi_masuk', compact('transaksi'));
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
        $request->validate([
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
        $total = $request->harga_satuan * $request->jumlah_masuk;
        
        \App\Models\TransaksiMasukPersediaan::create($request->all() + [
            
            'total' => $total,
            'user_id'       => auth()->id()
        ]);
        // 7. SINKRONISASI KE MASTER PERSEDIAAN
        $persediaan = \App\Models\Persediaan::where('kode_barang', $request->kode_barang)->first();

        if ($persediaan) {
            // Jika barang sudah ada di master, tambahkan stoknya
            $persediaan->increment('jumlah', $request->jumlah_masuk);
            
            // Update harga total di master persediaan menyesuaikan stok baru
            $persediaan->update([
                'harga_total' => $persediaan->jumlah * $persediaan->harga_satuan
            ]);
        } else {
            // Jika barang ini barang baru yang belum ada di master, buat data master baru
            \App\Models\Persediaan::create([
                'kode_kategori' => $request->kode_kategori,
                'kategori'      => $request->kategori,
                'kode_barang'   => $request->kode_barang,
                'nama_barang'   => $request->nama_barang,
                'satuan'        => $request->satuan,
                'tanggal_masuk' => $request->tanggal_input,
                'harga_satuan'  => $request->harga_satuan,
                'jumlah'        => $request->jumlah_masuk,
                'harga_total'   => $total,
            ]);
        }
        return redirect()->route('adminpersediaan.transaksi-masuk')
            ->with('success', 'Transaksi masuk berhasil disimpan!');
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
        $request->validate([
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
        $total = $request->harga_satuan * $request->jumlah_masuk;

        // 8. Update ke database
        $transaksiMasuk->update($request->all() + [
            'total' => $total
        ]);

        return redirect()->route('adminpersediaan.transaksi-masuk')
            ->with('success', 'Transaksi masuk berhasil diupdate!');
    }

    /** DESTROY - Hapus transaksi */
    public function destroyTransaksiMasuk(TransaksiMasukPersediaan $transaksiMasuk)
    {
        // Kurangi stok persediaan
        $persediaan = Persediaan::where('kode_barang', $transaksiMasuk->kode_barang)->first();
        if ($persediaan) {
            $persediaan->decrement('jumlah', $transaksiMasuk->jumlah_masuk);
            if ($persediaan->jumlah == 0) {
                $persediaan->delete();
            }
        }

        $transaksiMasuk->delete();

        return redirect()->route('adminpersediaan.transaksi-masuk')
            ->with('success', 'Transaksi masuk berhasil dihapus!');
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

    public function reviewPermintaan(Request $request, PermintaanPersediaan $permintaan)
    {
        // Pastikan hanya permintaan berstatus pending yang bisa direview admin
        if (!in_array($permintaan->status, ['pending'])) {
            return back()->with('error', 'Permintaan sudah diproses sebelumnya!');
        }

        $namaPegawai = $permintaan->user->name ?? 'Pegawai';
        $satuan = $permintaan->satuan ?? 'Unit'; // AMBIL DATA SATUAN DINAMIS

        if ($request->action === 'teruskan') {
            
            // 1. VALIDASI INPUT ADMIN: Pastikan jumlah yang disetujui tidak melebihi stok fisik
            $request->validate([
                'jumlah_disetujui' => 'required|integer|min:1|max:' . $permintaan->persediaan->jumlah
            ], [
                'jumlah_disetujui.max' => 'Gagal meneruskan! Jumlah yang disetujui tidak boleh melebihi sisa stok fisik (' . $permintaan->persediaan->jumlah . ' unit).'
            ]);

            // 2. SIMPAN DATA: Status berubah, dan nilai rekomendasi Admin disimpan ke kolom jumlah_disetujui
            $permintaan->update([
                'status' => 'diteruskan_kasubag',
                'reviewed_by_adminpersediaan_id' => Auth::id(),
                'jumlah_disetujui' => $request->jumlah_disetujui, // <--- Simpan angka revisi Admin
                'komentar' => $request->komentar // Opsional jika admin ingin memberi catatan ke Kasubag
            ]);

            $pesanFlash = 'Permintaan berhasil direvisi dan diteruskan ke Kasubag!';

            // 3. NOTIFIKASI WA KE KASUBAG
            $kasubag = User::where('role', 'kasubag')->first();

            if ($kasubag && $kasubag->nomor_telepon) {
                $noHpKasubag = preg_replace('/[^0-9]/', '', $kasubag->nomor_telepon);

                $pesanWa = "*Persetujuan Permintaan Persediaan*\n\n";
                $pesanWa .= "Yth. Kasubag,\n";
                $pesanWa .= "Admin Persediaan meneruskan permintaan barang persediaan untuk disetujui:\n\n";
                $pesanWa .= "👤 *Pemohon:* {$namaPegawai}\n";
                $pesanWa .= "📦 *Barang:* {$permintaan->nama_barang}\n";
                $pesanWa .= "🔢 *Jumlah Diminta Awal:* {$permintaan->jumlah_diminta} {$satuan}\n"; // PAKAI SATUAN DINAMIS
                $pesanWa .= "✅ *Rekomendasi Admin:* {$request->jumlah_disetujui} {$satuan}\n\n"; // PAKAI SATUAN DINAMIS
                $pesanWa .= "Silakan login ke sistem untuk memberikan persetujuan akhir.";

                SendFonnteNotification::dispatch($noHpKasubag, $pesanWa);
            }

            // --- 2. NOTIFIKASI INFO KE PEGAWAI ---
            $pegawai = $permintaan->user;
            if ($pegawai && $pegawai->nomor_telepon) {
                $noHpPegawai = preg_replace('/[^0-9]/', '', $pegawai->nomor_telepon);

                $pesanWaPegawai = "*Status Permintaan Persediaan*\n\n";
                $pesanWaPegawai .= "Halo {$pegawai->name},\n";
                $pesanWaPegawai .= "Permintaan barang persediaan Anda telah diverifikasi oleh Admin dan *sedang diteruskan ke Kasubag* untuk proses persetujuan akhir.\n\n";
                $pesanWaPegawai .= "📦 *Barang:* {$permintaan->nama_barang}\n";
                $pesanWaPegawai .= "✅ *Disetujui Admin:* {$request->jumlah_disetujui} {$satuan}\n\n"; // PAKAI SATUAN DINAMIS
                $pesanWaPegawai .= "Kami akan mengabari Anda kembali setelah ada keputusan dari Kasubag. Terima kasih.";

                SendFonnteNotification::dispatch($noHpPegawai, $pesanWaPegawai);
            }

        } else {
            // --- JIKA DITOLAK OLEH ADMIN PERSEDIAAN ---
            $permintaan->update([
                'status' => 'ditolak',
                'reviewed_by_adminpersediaan_id' => Auth::id(),
                'komentar' => $request->komentar, // Wajib jika ditolak, untuk alasan penolakan
            ]);

            $pesanFlash = 'Permintaan berhasil ditolak!';

            // NOTIFIKASI WA PENOLAKAN KE PEGAWAI
            $pegawai = $permintaan->user;
            if ($pegawai && $pegawai->nomor_telepon) {
                $noHpPegawai = preg_replace('/[^0-9]/', '', $pegawai->nomor_telepon);

                $pesanWa = "*Permintaan Persediaan DITOLAK Admin*\n\n";
                $pesanWa .= "Halo {$pegawai->name},\n";
                $pesanWa .= "Maaf, pengajuan barang persediaan Anda telah *ditolak* oleh Admin Persediaan.\n\n";
                $pesanWa .= "📦 *Barang:* {$permintaan->nama_barang}\n";
                $pesanWa .= "🔢 *Jumlah Diminta:* {$permintaan->jumlah_diminta} {$satuan}\n"; // PAKAI SATUAN DINAMIS
                $pesanWa .= "💬 *Catatan Admin:* " . ($request->komentar ?? 'Tidak ada catatan khusus') . "\n\n";
                $pesanWa .= "Silakan hubungi Admin Persediaan jika ada pertanyaan lebih lanjut.";

                SendFonnteNotification::dispatch($noHpPegawai, $pesanWa);
            }
        }

        return back()->with('success', $pesanFlash);
    }

    // 1. FUNGSI GENERATE SURAT (Dilengkapi TTD Base64)
    public function generateSuratPermintaan(PermintaanPersediaan $permintaan)
    {
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
            'ttdKepala'
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
        $monthlyData = TransaksiKeluarPersediaan::selectRaw('
                DATE_FORMAT(tanggal_input, "%Y-%m") as bulan,
                SUM(jumlah_keluar) as total_jumlah,
                SUM(total) as total_nilai
            ')
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
        $query = TransaksiKeluarPersediaan::query();

        // Terapkan filter yang sama dengan web
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_barang', 'like', "%{$search}%")
                    ->orWhere('nama_barang', 'like', "%{$search}%")
                    ->orWhere('nomor_transaksi', 'like', "%{$search}%");
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