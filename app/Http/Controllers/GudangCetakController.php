<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class GudangCetakController extends Controller
{
    public function index(Request $r)
    {
        $response = Http::get("https://sarang.ptagafood.com/api/apibk/cabut_selesai_g_cetak");
        $cabut = $response->object();
        $data =  [
            'title' => 'Gudang Cetak',
            'cabut' => $cabut,
        ];
        return view('gudangcetak.index', $data);
    }

    public function export_g_cetak(Request $r)
    {
        $style_atas = array(
            'font' => [
                'bold' => true, // Mengatur teks menjadi tebal
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ]
            ],
        );

        $style = [
            'borders' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ],
            ],
        ];
        $spreadsheet = new Spreadsheet();

        $spreadsheet->setActiveSheetIndex(0);
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Gudang Cabut');


        $sheet1->getStyle("A1:K1")->applyFromArray($style_atas);

        $sheet1->setCellValue('A1', 'No');
        $sheet1->setCellValue('B1', 'Partai');
        $sheet1->setCellValue('C1', 'No Box');
        $sheet1->setCellValue('D1', 'Tipe');
        $sheet1->setCellValue('E1', 'Pengawas');
        $sheet1->setCellValue('F1', 'Nama Anak');
        $sheet1->setCellValue('G1', 'Kelas');
        $sheet1->setCellValue('H1', 'Pcs');
        $sheet1->setCellValue('I1', 'Gr');
        $sheet1->setCellValue('J1', 'Pcs timbang ulang');
        $sheet1->setCellValue('K1', 'Gr timbang ulang');
        $kolom = 2;
        $response = Http::get("https://sarang.ptagafood.com/api/apibk/cabut_selesai_g_cetak");
        $cabut = $response->object();
        $no = 1;
        foreach ($cabut as  $g) {
            $gdng_ctk = DB::table('gudang_ctk')
                ->where('no_box', $g->no_box)
                ->first();

            if (isset($gdng_ctk) && $gdng_ctk->gudang == 'sortir') {
                continue;
            }
            $sheet1->setCellValue('A' . $kolom, $no);
            $sheet1->setCellValue('B' . $kolom, $g->nm_partai);
            $sheet1->setCellValue('C' . $kolom, $g->no_box);
            $sheet1->setCellValue('D' . $kolom, $g->tipe);
            $sheet1->setCellValue('E' . $kolom, $g->name);
            $sheet1->setCellValue('F' . $kolom, $g->nama);
            $sheet1->setCellValue('G' . $kolom, $g->id_kelas);
            $sheet1->setCellValue('H' . $kolom, $g->pcs_akhir);
            $sheet1->setCellValue('I' . $kolom, $g->gr_akhir);
            $sheet1->setCellValue('J' . $kolom, $gdng_ctk->pcs_timbang_ulang ?? 0);
            $sheet1->setCellValue('K' . $kolom, $gdng_ctk->gr_timbang_ulang ?? 0);
            $no++;
            $kolom++;
        }
        $sheet1->getStyle('A2:K' . $kolom - 1)->applyFromArray($style);
        $namafile = "Gudang Cetak.xlsx";

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $namafile);
        header('Cache-Control: max-age=0');


        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
    }
}
