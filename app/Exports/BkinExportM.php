<?php

namespace App\Exports;

use App\Models\Jurnal;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;


class BkinExportM  implements FromView, WithEvents
{
    protected $tgl1;
    protected $tgl2;
    protected $totalrow;
    protected $pembelian;

    public function __construct($tgl1, $tgl2, $totalrow, $pembelian)
    {
        $this->tgl1 = $tgl1;
        $this->tgl2 = $tgl2;
        $this->totalrow = $totalrow;
        $this->pembelian = $pembelian;
    }

    public function view(): View
    {
        return view('exports.pembelian', [
            'pembelian' => $this->pembelian,
        ]);
    }



    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $totalrow = $this->totalrow + 1;
                $cellRange = 'A1:N1';
                // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->setAutoFilter($cellRange);
                $event->sheet->getStyle('A1:N1')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'font' => [
                        'name'  =>  'Calibri',
                        'size'  =>  12,
                        'bold' => true
                    ]
                ]);
                $event->sheet->getStyle('A2:N' . $totalrow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'font' => [
                        'name'  =>  'Calibri',
                        'size'  =>  12,
                        'bold' => false
                    ]
                ]);
            },
        ];
    }
}
