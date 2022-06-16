<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ArrayExport implements FromCollection, WithStyles, WithColumnWidths
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect($this->data);
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 16,
            'B' => 12,
            'C' => 16,
            'D' => 26,
        ];
    }

    /**
     * @return array
     */
    public function styles(Worksheet $sheet){
        return [
            'A1:Z1' => [
                'font' => ['bold' => true,'color' =>['argb' => 'FFFFFFFF'] ],
                'fill'=> [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF000000']
                ]
            ],
        ];
    }
}
