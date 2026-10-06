<?php
namespace App\Http\Controllers;
use App\Models\FasilitasBeranda;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FasilitasBerandaController extends Controller {
    public const KATEGORI = [
        'kantor'=>'Kantor', 'ruang'=>'Ruang Pertemuan', 'kelas'=>'Ruang Kelas',
        'penginapan'=>'Asrama/mess', 'ruang_makan'=>'Ruang Makan', 'gedung'=>'Gedung Arsip',
        'outdoor'=>'Fasilitas Olahraga', 'lapangan_Upacara'=>'Lapangan Upacara',
        'sarana_ibadah'=>'Sarana Ibadah', 'kesehatan'=>'Kesehatan',
    ];
    public function index() {
        return view('adminsarpras.fasilitas_beranda', ['items'=>FasilitasBeranda::orderBy('urutan')->orderBy('id')->get()]);
    }
    public function edit(FasilitasBeranda $fasilitas) {
        return view('adminsarpras.edit_fasilitas_beranda', ['item'=>$fasilitas, 'kategori'=>self::KATEGORI]);
    }
    public function update(Request $request, FasilitasBeranda $fasilitas) {
        $data = $request->validate([
            'name'=>'required|string|max:255', 'category'=>['required',Rule::in(array_keys(self::KATEGORI))],
            'location'=>'required|string|max:255', 'capacity'=>'nullable|string|max:100',
            'luas'=>'nullable|string|max:100', 'operasional'=>'nullable|string|max:255',
            'kontak'=>'nullable|string|max:100', 'description'=>'nullable|string|max:5000',
            'features'=>'nullable|string|max:5000', 'rules'=>'nullable|string|max:5000',
            'urutan'=>'required|integer|min:0|max:10000', 'tampil'=>'required|boolean',
            'foto'=>'nullable|array|max:10', 'foto.*'=>'image|mimes:jpg,jpeg,png,webp|max:5120',
            'hapus_foto'=>'nullable|array', 'hapus_foto.*'=>'integer|min:0',
        ]);
        $konten = $fasilitas->konten;
        foreach (['name','category','location','capacity','luas','operasional','kontak','description'] as $field) {
            $konten[$field] = $data[$field] ?? '';
        }
        foreach (['features','rules'] as $field) {
            $konten[$field] = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $data[$field] ?? ''))));
        }
        $images = array_values(array_filter($konten['images'] ?? [], fn($key)=>!in_array($key, $data['hapus_foto'] ?? []), ARRAY_FILTER_USE_KEY));
        foreach ($request->file('foto', []) as $foto) {
            $images[] = '/storage/'.$foto->store('fasilitas_beranda', 'public');
        }
        if (!$images) {
            throw \Illuminate\Validation\ValidationException::withMessages(['foto'=>'Sisakan minimal satu foto atau unggah foto baru.']);
        }
        $konten['images'] = $images;
        $fasilitas->update(['konten'=>$konten, 'urutan'=>$data['urutan'], 'tampil'=>$data['tampil']]);
        return redirect()->route('adminsarpras.fasilitas-beranda.index')->with('success', 'Fasilitas beranda berhasil diperbarui.');
    }
}
