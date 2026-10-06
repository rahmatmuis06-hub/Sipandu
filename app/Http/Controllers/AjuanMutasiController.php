<?php

namespace App\Http\Controllers;

use App\Models\AjuanMutasi;
use App\Models\AssetTetap; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AjuanMutasiController extends Controller
{
    public function index(Request $request)
    {
        $query = AjuanMutasi::with(['asetTetap', 'user'])
            ->where('user_id', Auth::id())
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

        $ajuan_mutasi = $query->paginate(20)->withQueryString();

        $ajuan_mutasi->getCollection()->transform(function ($item) {
            $item->tanggal_mutasi_formatted = $item->tanggal_mutasi?->format('d/m/Y');
            $item->tanggal_input = $item->created_at?->format('d/m/Y H:i');
            return $item;
        });

        $asetTetap = AssetTetap::select('id', 'kode_barang', 'nup', 'nama_barang', 'lokasi', 'kondisi')
            ->orderBy('nama_barang', 'asc')
            ->orderBy('kode_barang', 'asc')
            ->get();

        // Sesuaikan 'pegawai.ajuan_mutasi' dengan folder view Anda
        return view('pegawai.ajuan_mutasi', compact('ajuan_mutasi', 'asetTetap'));
    }

    public function store(Request $request)
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

            AjuanMutasi::create([
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

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ajuan mutasi berhasil disimpan!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ajuan store error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan ajuan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $ajuan = AjuanMutasi::with(['asetTetap', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $ajuan->tanggal_mutasi_formatted = $ajuan->tanggal_mutasi?->format('d/m/Y');
        $ajuan->tanggal_input = $ajuan->created_at?->format('d/m/Y H:i');

        return response()->json($ajuan);
    }

    public function edit($id)
    {
        $ajuan = AjuanMutasi::with('asetTetap')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return response()->json([
            'id'            => $ajuan->id,
            'aset_tetap_id' => $ajuan->aset_tetap_id,
            'lokasi_awal'   => $ajuan->lokasi_awal,
            'lokasi_akhir'  => $ajuan->lokasi_akhir,
            'tanggal_mutasi'=> $ajuan->tanggal_mutasi?->format('Y-m-d'),
            'keterangan'    => $ajuan->keterangan ?? '',
            'kode_barang'   => $ajuan->kode_barang,
            'nup'           => $ajuan->nup,
            'nama_barang'   => $ajuan->nama_barang,
            'kondisi'       => $ajuan->kondisi
        ]);
    }

    public function update(Request $request, $id)
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
            $ajuan = AjuanMutasi::where('user_id', Auth::id())->findOrFail($id);
            $aset = AssetTetap::findOrFail($validated['aset_tetap_id']);

            $ajuan->update([
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

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ajuan mutasi berhasil diupdate!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate ajuan'
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $ajuan = AjuanMutasi::where('user_id', Auth::id())->findOrFail($id);

            $ajuan->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ajuan mutasi berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus ajuan'
            ], 500);
        }
    }

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
}
