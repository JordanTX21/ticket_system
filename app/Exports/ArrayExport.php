<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;

class ArrayExport implements FromCollection, WithStyles
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
    public function styles(Worksheet $sheet){
        return [
            'A1:Z1' => [
                'font' => ['bold' => true,'color' =>['argb' => 'FFFFFFFF'] ],
                'fill'=> [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFFFFFFF']
                ]
            ],
        ];
    }
}
