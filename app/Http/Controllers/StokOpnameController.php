<?php
namespace App\Http\Controllers;
use App\Models\{StokOpname,Persediaan,TransaksiMasukPersediaan,TransaksiKeluarPersediaan};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
class StokOpnameController extends Controller {
 public function index(){return view('adminpersediian.opname.index',['items'=>StokOpname::withCount('details')->orderByDesc('bulan')->get()]);}
 public function store(Request $r){
  $v=$r->validate(['bulan'=>'required|date_format:Y-m|unique:stok_opname,bulan','tanggal'=>'required|date|before_or_equal:today']);
  if(substr($v['tanggal'],0,7)!==$v['bulan'])throw ValidationException::withMessages(['tanggal'=>'Tanggal pemeriksaan harus berada dalam bulan yang dipilih.']);
  $op=DB::transaction(function()use($v){
   $items=Persediaan::orderBy('nama_barang')->get();
   if($items->isEmpty())throw ValidationException::withMessages(['bulan'=>'Data persediaan masih kosong.']);
   $op=StokOpname::create($v+['user_id'=>auth()->id()]);
   $start=$v['bulan'].'-01';$end=$v['tanggal'];
   foreach($items as $p){
    $in=TransaksiMasukPersediaan::where('kode_kategori',$p->kode_kategori)->where('kode_barang',$p->kode_barang)->whereDate('tanggal_input','>=',$start)->whereDate('tanggal_input','<=',$end)->sum('jumlah_masuk');
    $out=TransaksiKeluarPersediaan::where(function($q)use($p){$q->where('persediaan_id',$p->id)->orWhere(function($q)use($p){$q->whereNull('persediaan_id')->where('kode_kategori',$p->kode_kategori)->where('kode_barang',$p->kode_barang);});})->whereDate('tanggal_input','>=',$start)->whereDate('tanggal_input','<=',$end)->sum('jumlah_keluar');
    $op->details()->create(['persediaan_id'=>$p->id,'kode'=>$p->kode_unik_barang,'nama'=>$p->nama_barang,'satuan'=>$p->satuan,'harga'=>$p->harga_satuan,'masuk'=>$in,'keluar'=>$out,'stok_buku'=>$end===today()->format('Y-m-d')?$p->jumlah:null]);
   }
   return $op;
  });
  return redirect()->route('adminpersediaan.opname.show',$op);
 }
 public function show(StokOpname $opname){return view('adminpersediian.opname.show',['op'=>$opname,'rows'=>$opname->details()->orderBy('nama')->get()]);}
 public function update(Request $r,StokOpname $opname){
  $v=$r->validate(['detail_id'=>'required|integer','stok_buku'=>'required|integer|min:0|max:2147483647','stok_fisik'=>'nullable|integer|min:0|max:2147483647','catatan'=>'nullable|string|max:2000']);
  DB::transaction(function()use($v,$opname){
   $op=StokOpname::whereKey($opname->id)->lockForUpdate()->firstOrFail();abort_if($op->status!=='draft',403,'Opname final tidak dapat diubah.');
   $d=$op->details()->findOrFail($v['detail_id']);unset($v['detail_id']);$d->update($v);
  });
  return back()->with('success','Hasil hitung barang disimpan.');
 }
 public function finalize(StokOpname $opname){
  DB::transaction(function()use($opname){
   $op=StokOpname::whereKey($opname->id)->lockForUpdate()->firstOrFail();abort_if($op->status!=='draft',403);
   if($op->details()->where(function($q){$q->whereNull('stok_buku')->orWhereNull('stok_fisik');})->exists())throw ValidationException::withMessages(['opname'=>'Lengkapi saldo buku dan stok fisik seluruh barang sebelum finalisasi.']);
   $op->update(['status'=>'final','finalized_at'=>now()]);
  });
  return back()->with('success','Opname difinalisasi. Laporan tersimpan dan terkunci.');
 }
 public function report(Request $r,StokOpname $opname){
  $r->validate(['barang'=>'nullable|integer']);
  $rows=$opname->details()->when($r->filled('barang'),fn($q)=>$q->where('persediaan_id',$r->integer('barang')))->orderBy('nama')->get();
  abort_if($rows->isEmpty(),404);
  // Gunakan pelaksana yang tercatat, bukan akun yang sedang mencetak laporan.
  $pelaksana=User::find($opname->user_id);
  $kasubag=User::where('role','kasubag')->where('is_active',true)->orderBy('id')->first();
  $signature=function(?User $user): ?string {
   $path=$user?->signature;
   if(!$path || !Storage::disk('public')->exists($path)) return null;
   $bytes=Storage::disk('public')->get($path);
   $info=@getimagesizefromstring($bytes);
   if(!$info || !in_array($info['mime'],['image/png','image/jpeg'])) return null;
   return 'data:'.$info['mime'].';base64,'.base64_encode($bytes);
  };
  $data=['op'=>$opname,'rows'=>$rows,'pelaksana'=>$pelaksana,'kasubag'=>$kasubag,
   'ttdPelaksana'=>$signature($pelaksana),'ttdKasubag'=>$signature($kasubag)];
  if($r->boolean('pdf'))return Pdf::loadView('adminpersediian.opname.report',$data)->setPaper('a4','landscape')->download('Stok-Opname-'.$opname->bulan.($r->filled('barang')?'-Barang-'.$r->integer('barang'):'-Semua').'.pdf');
  return view('adminpersediian.opname.report',$data);
 }
}
