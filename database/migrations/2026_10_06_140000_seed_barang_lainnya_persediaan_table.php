<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('persediaan')) {
            return;
        }

        $items = [
            [
                'kode_kategori'    => 'BLN',
                'kategori'         => 'Barang Lainnya',
                'kode_barang'      => '000001',
                'nama_barang'      => 'STEKER / COLOKAN LISTRIK ARDE',
                'satuan'           => 'Buah',
                'tanggal_masuk'    => date('Y-m-d'),
                'harga_satuan'     => 15000,
                'harga_total'      => 300000,
                'jumlah'           => 20,
                'kode_unik_barang' => 'BLN-000001',
            ],
            [
                'kode_kategori'    => 'BLN',
                'kategori'         => 'Barang Lainnya',
                'kode_barang'      => '000002',
                'nama_barang'      => 'STOPKONTAK KABEL ROLL 10 METER',
                'satuan'           => 'Unit',
                'tanggal_masuk'    => date('Y-m-d'),
                'harga_satuan'     => 75000,
                'harga_total'      => 750000,
                'jumlah'           => 10,
                'kode_unik_barang' => 'BLN-000002',
            ],
            [
                'kode_kategori'    => 'BLN',
                'kategori'         => 'Barang Lainnya',
                'kode_barang'      => '000003',
                'nama_barang'      => 'STOPKONTAK KABEL ROLL 15 METER',
                'satuan'           => 'Unit',
                'tanggal_masuk'    => date('Y-m-d'),
                'harga_satuan'     => 110000,
                'harga_total'      => 1100000,
                'jumlah'           => 10,
                'kode_unik_barang' => 'BLN-000003',
            ],
            [
                'kode_kategori'    => 'BLN',
                'kategori'         => 'Barang Lainnya',
                'kode_barang'      => '000004',
                'nama_barang'      => 'T-PLUG / STOP KONTAK CABANG 3',
                'satuan'           => 'Buah',
                'tanggal_masuk'    => date('Y-m-d'),
                'harga_satuan'     => 25000,
                'harga_total'      => 625000,
                'jumlah'           => 25,
                'kode_unik_barang' => 'BLN-000004',
            ],
            [
                'kode_kategori'    => 'BLN',
                'kategori'         => 'Barang Lainnya',
                'kode_barang'      => '000005',
                'nama_barang'      => 'ADAPTOR STEKER LISTRIK UNIVERSAL',
                'satuan'           => 'Buah',
                'tanggal_masuk'    => date('Y-m-d'),
                'harga_satuan'     => 20000,
                'harga_total'      => 400000,
                'jumlah'           => 20,
                'kode_unik_barang' => 'BLN-000005',
            ],
            [
                'kode_kategori'    => 'BLN',
                'kategori'         => 'Barang Lainnya',
                'kode_barang'      => '000006',
                'nama_barang'      => 'KABEL EXTENSION / SAMBUNGAN LISTRIK 5M',
                'satuan'           => 'Unit',
                'tanggal_masuk'    => date('Y-m-d'),
                'harga_satuan'     => 45000,
                'harga_total'      => 675000,
                'jumlah'           => 15,
                'kode_unik_barang' => 'BLN-000006',
            ],
            [
                'kode_kategori'    => 'BLN',
                'kategori'         => 'Barang Lainnya',
                'kode_barang'      => '000007',
                'nama_barang'      => 'BATERAI AA ALKALINE (ISI 2)',
                'satuan'           => 'Pasang',
                'tanggal_masuk'    => date('Y-m-d'),
                'harga_satuan'     => 18000,
                'harga_total'      => 540000,
                'jumlah'           => 30,
                'kode_unik_barang' => 'BLN-000007',
            ],
            [
                'kode_kategori'    => 'BLN',
                'kategori'         => 'Barang Lainnya',
                'kode_barang'      => '000008',
                'nama_barang'      => 'BATERAI AAA ALKALINE (ISI 2)',
                'satuan'           => 'Pasang',
                'tanggal_masuk'    => date('Y-m-d'),
                'harga_satuan'     => 18000,
                'harga_total'      => 540000,
                'jumlah'           => 30,
                'kode_unik_barang' => 'BLN-000008',
            ],
            [
                'kode_kategori'    => 'BLN',
                'kategori'         => 'Barang Lainnya',
                'kode_barang'      => '000009',
                'nama_barang'      => 'LAKBAN / ISOLASI LISTRIK HITAM',
                'satuan'           => 'Roll',
                'tanggal_masuk'    => date('Y-m-d'),
                'harga_satuan'     => 12000,
                'harga_total'      => 360000,
                'jumlah'           => 30,
                'kode_unik_barang' => 'BLN-000009',
            ],
            [
                'kode_kategori'    => 'BLN',
                'kategori'         => 'Barang Lainnya',
                'kode_barang'      => '000010',
                'nama_barang'      => 'KABEL HDMI HIGH SPEED 5 METER',
                'satuan'           => 'Unit',
                'tanggal_masuk'    => date('Y-m-d'),
                'harga_satuan'     => 65000,
                'harga_total'      => 650000,
                'jumlah'           => 10,
                'kode_unik_barang' => 'BLN-000010',
            ],
            [
                'kode_kategori'    => 'BLN',
                'kategori'         => 'Barang Lainnya',
                'kode_barang'      => '000011',
                'nama_barang'      => 'LAMPU LED HEMAT ENERGI 14 WATT',
                'satuan'           => 'Buah',
                'tanggal_masuk'    => date('Y-m-d'),
                'harga_satuan'     => 45000,
                'harga_total'      => 900000,
                'jumlah'           => 20,
                'kode_unik_barang' => 'BLN-000011',
            ],
        ];

        foreach ($items as $item) {
            $existing = DB::table('persediaan')
                ->where('kode_unik_barang', $item['kode_unik_barang'])
                ->orWhere(function ($q) use ($item) {
                    $q->where('kode_kategori', $item['kode_kategori'])
                      ->where('kode_barang', $item['kode_barang']);
                })
                ->first();

            if (!$existing) {
                $item['created_at'] = now();
                $item['updated_at'] = now();
                DB::table('persediaan')->insert($item);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('persediaan')) {
            DB::table('persediaan')->where('kode_kategori', 'BLN')->delete();
        }
    }
};
