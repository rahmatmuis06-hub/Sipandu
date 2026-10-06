<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PersediaanTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'kode_unik_barang',
            'kategori',
            'nama_barang',
            'tanggal_masuk',
            'harga_satuan',
            'jumlah',
            'satuan'
        ];
    }

    public function array(): array
    {
        return [
            [
                'ATK-001',
                'Alat Tulis Kantor',
                'Kertas HVS A4 Sidu',
                date('Y-m-d'),
                50000,
                10,
                'rim'
            ]
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => '4F6FFF']
                ],
            ],
        ];
    }
}
