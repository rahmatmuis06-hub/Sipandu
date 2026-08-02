<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AsetTetapTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    /**
     * Membuat Baris Judul (Header)
     */
    public function headings(): array
    {
        return [
            'tanggal_input',
            'kode_barang',
            'nup',
            'nama_barang',
            'merek',
            'kategori',
            'tanggal_perolehan',
            'nilai_perolehan',
            'kondisi',
            'lokasi',
            'jumlah',
            'status',
            'Nomor Polisi',
            'Nomor BPKB',
            'Nomor Rangka',
            'Nomor Mesin'
        ];
    }

    /**
     * Memasukkan baris contoh sebagai panduan pengisian
     */
    public function array(): array
    {
        return [
            [
                date('Y-m-d'),
                'AST-001',
                '001',
                'Laptop Lenovo Thinkpad',
                'Lenovo',
                'Elektronik',
                date('Y-m-d'),
                15000000,
                'baik',
                'Ruang IT',
                1,
                'Tersedia',
                'DM 1234 A',
                'BPKB-99887766',
                'MHFGB8GS8L090',
                '2GD-C667811'
            ]
        ];
    }

    /**
     * Memberikan Styling (Desain) pada Excel
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style untuk Baris Pertama (Header)
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFF'] // Teks Putih
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => '4F6FFF'] // Background Biru (Sesuai tema web Anda)
                ],
            ],
        ];
    }
}
