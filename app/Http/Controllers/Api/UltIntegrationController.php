<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gedung;
use App\Models\PeminjamanGedung;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class UltIntegrationController extends Controller
{
    /**
     * Cek ketersediaan gedung & ruangan BPMP Gorontalo secara real-time untuk loket ULT.
     */
    public function ketersediaanGedung(Request $request): JsonResponse
    {
        $tanggalMulai = $request->query('tanggal_mulai', date('Y-m-d'));
        $tanggalSelesai = $request->query('tanggal_selesai', $tanggalMulai);
        $kategori = $request->query('kategori');

        $query = Gedung::query();
        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        $gedungList = $query->get()->map(function ($gedung) use ($tanggalMulai, $tanggalSelesai) {
            // Cek apakah ada jadwal peminjaman bentrok pada rentang tanggal tersebut
            $bentrok = PeminjamanGedung::where('gedung_id', $gedung->id)
                ->whereIn('status', ['pending', 'dalam_review', 'disetujui', 'disetujui_kasubag'])
                ->where(function ($q) use ($tanggalMulai, $tanggalSelesai) {
                    $q->whereBetween('tanggal_pinjam', [$tanggalMulai, $tanggalSelesai])
                      ->orWhereBetween('tanggal_kembali', [$tanggalMulai, $tanggalSelesai])
                      ->orWhere(function ($sub) use ($tanggalMulai, $tanggalSelesai) {
                          $sub->where('tanggal_pinjam', '<=', $tanggalMulai)
                              ->where('tanggal_kembali', '>=', $tanggalSelesai);
                      });
                })
                ->exists();

            $statusKetersediaan = $bentrok ? 'Terjadwal / Tidak Tersedia' : $gedung->ketersediaan;
            $isTersedia = (!$bentrok && $gedung->ketersediaan === 'Tersedia');

            return [
                'id'                 => $gedung->id,
                'nama_gedung'        => $gedung->nama_gedung,
                'kategori'           => $gedung->kategori,
                'lokasi'             => $gedung->lokasi,
                'kapasitas'          => $gedung->kapasitas,
                'tarif_sewa_per_hari'=> $gedung->tarif_sewa,
                'fasilitas'          => $gedung->fasilitas,
                'status_realtime'    => $statusKetersediaan,
                'is_tersedia'        => $isTersedia,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Data ketersediaan gedung/sarpras berhasil diambil',
            'periode' => [
                'tanggal_mulai'   => $tanggalMulai,
                'tanggal_selesai' => $tanggalSelesai,
            ],
            'total'   => $gedungList->count(),
            'data'    => $gedungList,
        ]);
    }

    /**
     * Katalog seluruh fasilitas & sarpras yang dapat dilayani untuk pemohon eksternal/ULT.
     */
    public function katalogLayanan(): JsonResponse
    {
        $katalog = Gedung::select('id', 'nama_gedung', 'kategori', 'lokasi', 'kapasitas', 'tarif_sewa', 'fasilitas', 'ketersediaan')
            ->orderBy('kategori')
            ->orderBy('nama_gedung')
            ->get();

        return response()->json([
            'success'   => true,
            'instansi'  => 'BPMP Provinsi Gorontalo',
            'sistem'    => 'SIPANDU - Unit Layanan Terpadu (ULT)',
            'total'     => $katalog->count(),
            'katalog'   => $katalog,
        ]);
    }

    /**
     * Endpoint untuk Loket ULT menginput permohonan peminjaman fasilitas dari pemohon publik/eksternal.
     */
    public function storePermohonanSarpras(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nomor_tiket_ult'    => 'nullable|string|max:100',
            'nama_pemohon'       => 'required|string|max:255',
            'nip_nik'            => 'nullable|string|max:50',
            'instansi_lembaga'   => 'required|string|max:255',
            'kabupaten_kota'     => 'nullable|string|max:100',
            'gedung_id'          => 'required|exists:gedung,id',
            'tanggal_pinjam'     => 'required|date',
            'tanggal_kembali'    => 'required|date|after_or_equal:tanggal_pinjam',
            'jam_mulai'          => 'nullable',
            'jam_selesai'        => 'nullable',
            'jumlah_peserta'     => 'required|integer|min:1',
            'tujuan_penggunaan'  => 'required|string',
            'kontak_pemohon'     => 'nullable|string|max:50',
        ]);

        $gedung = Gedung::findOrFail($validated['gedung_id']);

        // Cari user default tamu/ULT atau admin
        $userUlt = User::where('role', 'tamu')
            ->orWhere('role', 'pegawai')
            ->first();
        $userId = $userUlt ? $userUlt->id : 1;

        $tglPinjam = Carbon::parse($validated['tanggal_pinjam']);
        $tglKembali = Carbon::parse($validated['tanggal_kembali']);
        $durasiHari = max(1, $tglPinjam->diffInDays($tglKembali) + 1);

        $totalTarif = ($gedung->tarif_sewa ?? 0) * $durasiHari;

        // Kode tiket ULT bila tidak dikirim dari luar
        $nomorTiket = $validated['nomor_tiket_ult'] ?? ('ULT-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4)));

        $peminjaman = PeminjamanGedung::create([
            'user_id'              => $userId,
            'gedung_id'            => $gedung->id,
            'nama_lengkap'         => $validated['nama_pemohon'] . " (Loket ULT)",
            'nip_nik'              => $validated['nip_nik'] ?? '-',
            'instansi_lembaga'     => $validated['instansi_lembaga'],
            'kabupaten_kota'       => $validated['kabupaten_kota'] ?? 'Gorontalo',
            'fasilitas'            => $gedung->fasilitas,
            'nama_fasilitas'       => $gedung->nama_gedung,
            'tarif_per_hari'       => $gedung->tarif_sewa ?? 0,
            'tanggal_pinjam'       => $validated['tanggal_pinjam'],
            'tanggal_kembali'      => $validated['tanggal_kembali'],
            'jam_mulai'            => $validated['jam_mulai'] ?? '08:00',
            'jam_selesai'          => $validated['jam_selesai'] ?? '17:00',
            'lama_peminjaman_hari' => $durasiHari,
            'jumlah_peserta'       => $validated['jumlah_peserta'],
            'total_pembayaran'     => $totalTarif,
            'tujuan_penggunaan'    => "[Tiket ULT: {$nomorTiket}] " . $validated['tujuan_penggunaan'],
            'status'               => 'pending',
            'komentar'             => "Permohonan terdaftar melalui Loket Pelayanan ULT. Menunggu verifikasi Admin Sarpras.",
            'status_pembayaran'    => 'belum_lunas',
        ]);

        return response()->json([
            'success'      => true,
            'message'      => 'Permohonan peminjaman sarpras dari ULT berhasil dicatat ke SIPANDU',
            'nomor_tiket'  => $nomorTiket,
            'id_registrasi'=> $peminjaman->id,
            'detail'       => [
                'gedung'           => $gedung->nama_gedung,
                'pemohon'          => $validated['nama_pemohon'],
                'instansi'         => $validated['instansi_lembaga'],
                'durasi_hari'      => $durasiHari,
                'estimasi_biaya'   => $totalTarif,
                'status'           => 'Pending (Menunggu Verifikasi Admin Sarpras)',
            ],
        ], 201);
    }

    /**
     * Endpoint tracking status layanan peminjaman untuk loket ULT / pemohon.
     */
    public function trackingLayanan(string $kodeTiket): JsonResponse
    {
        $peminjaman = PeminjamanGedung::with('gedung')
            ->where(function ($q) use ($kodeTiket) {
                $q->where('id', $kodeTiket)
                  ->orWhere('tujuan_penggunaan', 'like', "%{$kodeTiket}%");
            })
            ->first();

        if (!$peminjaman) {
            return response()->json([
                'success' => false,
                'message' => "Permohonan dengan kode atau nomor tiket '{$kodeTiket}' tidak ditemukan di SIPANDU.",
            ], 404);
        }

        $statusHuman = match ($peminjaman->status) {
            'pending'           => 'Menunggu Review Admin Sarpras',
            'dalam_review'      => 'Diteruskan ke Kasubag / Sedang Ditinjau',
            'disetujui'         => 'Disetujui Admin Sarpras & Siap Digunakan',
            'disetujui_kasubag' => 'Disetujui Kasubag & Final',
            'ditolak'           => 'Permohonan Ditolak',
            default             => ucfirst($peminjaman->status),
        };

        return response()->json([
            'success'      => true,
            'kode_tiket'   => $kodeTiket,
            'nama_pemohon' => $peminjaman->nama_lengkap,
            'instansi'     => $peminjaman->instansi_lembaga,
            'fasilitas'    => $peminjaman->gedung->nama_gedung ?? $peminjaman->nama_fasilitas,
            'tanggal'      => $peminjaman->tanggal_pinjam?->format('d/m/Y') . ' s.d ' . $peminjaman->tanggal_kembali?->format('d/m/Y'),
            'status'       => $peminjaman->status,
            'status_label' => $statusHuman,
            'catatan'      => $peminjaman->komentar ?? '-',
            'terakhir_update' => $peminjaman->updated_at?->format('d-m-Y H:i:s'),
        ]);
    }

    /**
     * Ringkasan statistik permohonan layanan ULT untuk dashboard & monitoring.
     */
    public function ringkasanLayanan(): JsonResponse
    {
        $totalUlt = PeminjamanGedung::where('tujuan_penggunaan', 'like', '%Tiket ULT%')
            ->orWhere('nama_lengkap', 'like', '%Loket ULT%')
            ->count();

        $pending = PeminjamanGedung::where(function($q) {
                $q->where('tujuan_penggunaan', 'like', '%Tiket ULT%')
                  ->orWhere('nama_lengkap', 'like', '%Loket ULT%');
            })
            ->where('status', 'pending')
            ->count();

        $disetujui = PeminjamanGedung::where(function($q) {
                $q->where('tujuan_penggunaan', 'like', '%Tiket ULT%')
                  ->orWhere('nama_lengkap', 'like', '%Loket ULT%');
            })
            ->whereIn('status', ['disetujui', 'disetujui_kasubag'])
            ->count();

        return response()->json([
            'success'   => true,
            'sistem'    => 'SIPANDU BPMP Gorontalo - Modul Integrasi ULT',
            'ringkasan' => [
                'total_permohonan_ult' => $totalUlt,
                'menunggu_verifikasi'  => $pending,
                'disetujui'            => $disetujui,
            ],
        ]);
    }
}
