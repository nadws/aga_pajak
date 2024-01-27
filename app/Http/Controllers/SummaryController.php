<?php

namespace App\Http\Controllers;

use App\Models\GudangBkModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SummaryController extends Controller
{
    protected $linkApi;
    public function __construct()
    {
        $this->linkApi = "https://sarang.ptagafood.com/api/apibk";
    }
    function index(Request $r)
    {
        if (empty($r->nm_gudang)) {
            $nmgudang = 'bk';
        } else {
            $nmgudang = $r->nm_gudang;
        }

        $gudang = GudangBkModel::getSummaryWip();
        $total = GudangBkModel::getPembelianBk('bk');

        $ttl_bk = 0;
        foreach ($total as $t) {
            $ttl_bk += $t->rupiah * $t->gr;
        }

        $listBulan = DB::table('bulan')->get();
        $id_user = auth()->user()->id;
        $data =  [
            'title' => 'Summary Wip',
            'gudang' => $gudang,
            'listbulan' => $listBulan,
            'nm_gudang' => $nmgudang,
            'total_bk' => $ttl_bk,
            'linkApi' => $this->linkApi,
            'total_invoice' => DB::selectOne("SELECT a.no_nota, b.no_nota, sum(a.total_harga) as ttl_hrga
            FROM invoice_bk as a left 
            join grading as b on b.no_nota = a.no_nota 
            where b.no_nota is null;")
        ];
        return view('summarybk.index', $data);
    }
    function get_no_lot(Request $r)
    {

        $gudang = GudangBkModel::getSummaryWipLot($r->nm_partai);
        $data =  [
            'lot' => $gudang,
            'linkApi' => $this->linkApi
        ];
        return view('summarybk.get_lot', $data);
    }

    public function export_summary(Request $r)
    {
        if ($r->nm_gudang == 'summary') {
            $this->export_summary1($r);
        } else {
            $this->export_summary2($r);
        }
    }

    private function export_summary1(Request $r)
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
        $style_bawah = array(
            'font' => [
                'bold' => true, // Mengatur teks menjadi tebal
            ],
            'borders' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ],
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
        $sheet1->setTitle('Summary Wip');


        $sheet1->getStyle("A1:X1")->applyFromArray($style_atas);

        $koloms = [
            'A' => '#',
            'B' => 'Ket / nama partai',
            'C' => 'Grade / No Lot',

            'D' => 'Wip Pcs',
            'E' => "Wip Gr",
            'F' => "Wip Ttl Rp",

            'G' => "Bk Pcs",
            'H' => "Bk Gr",
            'I' => "Bk Ttl Rp",

            'J' => "Susut Gr",
            'K' => "Susut %",

            'L' => "Wip Pcs Sisa",
            'M' => "Wip Gr Sisa",
            'N' => "Wip Ttl Rp",


            'O' => "Cbt Pcs Awal",
            'P' => "Cbt Gr Awal",
            'Q' => "Cbt Pcs Akhir",
            'R' => "Cbt Gr Akhir",
            'S' => "Cbt Susut",
            'T' => "Eo",
            'U' => "Flx",
            'V' => "Bk Pcs Sisa Pgws",
            'W' => "Bk Gr Sisa Pgws",
            'X' => "Ttl Rp"
        ];
        foreach ($koloms as $kolom => $d) {
            $sheet1->setCellValue($kolom . '1', $d);
        }

        $kolom = 2;
        $gudang = GudangBkModel::getSummaryWip();

        $pcs_wip = 0;
        $gr_wip = 0;
        $pcs_bk = 0;
        $gr_bk = 0;

        $pcs_awal_cbt_ttl = 0;
        $gr_awal_cbt_ttl = 0;
        $pcs_akhir_cbt_ttl = 0;
        $gr_akhir_cbt_ttl = 0;
        $ttl_rp = 0;
        $ttlPcsSisaSinta = 0;
        $ttGrSisaSinta = 0;
        foreach ($gudang as $no => $g) {
            $response = Http::get("$this->linkApi/bk_sum?nm_partai=$g->ket2");
            $b = $response->object();

            $resSum = Http::get("$this->linkApi/sarang_sum?nm_partai=$g->ket2");
            $c = $resSum->object();


            $sheet1->setCellValue('A' . $kolom, $no + 1);
            $sheet1->setCellValue('B' . $kolom, $g->ket2);
            $sheet1->setCellValue('C' . $kolom, $g->nm_grade);

            $sheet1->setCellValue('D' . $kolom, $g->pcs ?? 0);
            $sheet1->setCellValue('E' . $kolom, $g->gr ?? 0);
            $sheet1->setCellValue('F' . $kolom, $g->total_rp ?? 0);
            $wipTllrp = $g->total_rp ?? 0;
            $wipGr = $g->gr ?? 0;
            $hrga_modal_satuan = $wipTllrp / $wipGr;

            $sheet1->setCellValue('G' . $kolom, $b->pcs_awal ?? 0);
            $sheet1->setCellValue('H' . $kolom, $b->gr_awal ?? 0);
            $sheet1->setCellValue('I' . $kolom, $hrga_modal_satuan * $b->gr_awal ?? 0);

            $bkSusut = empty($b->gr_awal) ? 0 : (1 - ($b->gr_awal / $g->gr)) * 100;
            $sheet1->setCellValue('J' . $kolom, $g->gr_susut);
            $sheet1->setCellValue('K' . $kolom, round($bkSusut, 1));

            $pcs_awal_wip = $g->pcs ?? 0;
            $gr_awal_wip = $g->gr ?? 0;
            $gr_awal_susut = $g->gr_susut ?? 0;

            $pcs_awal_bk = $b->pcs_awal ?? 0;
            $gr_awal_bk = $b->gr_awal ?? 0;

            $sheet1->setCellValue('L' . $kolom, $pcs_awal_wip - $pcs_awal_bk);
            $sheet1->setCellValue('M' . $kolom, $gr_awal_wip - $gr_awal_bk - $gr_awal_susut);
            $sheet1->setCellValue('N' . $kolom,  $hrga_modal_satuan * ($gr_awal_wip - $gr_awal_bk - $gr_awal_susut));

            $sheet1->setCellValue('O' . $kolom, $c->pcs_awal ?? 0);
            $sheet1->setCellValue('P' . $kolom, $c->gr_awal ?? 0);
            $sheet1->setCellValue('Q' . $kolom, $c->pcs_akhir ?? 0);
            $sheet1->setCellValue('R' . $kolom, $c->gr_akhir ?? 0);

            $pcs_awal_cbt = $c->pcs_awal ?? 0;
            $gr_awal_cbt = $c->gr_awal ?? 0;

            $cbtSusut = empty($c->gr_awal) ? 0 : 1 - ((($gr_awal_bk - $gr_awal_cbt) + $c->gr_akhir) / $c->gr_awal);
            $sheet1->setCellValue('S' . $kolom, $c->susut);
            $sheet1->setCellValue('T' . $kolom, $c->eot ?? 0);
            $sheet1->setCellValue('U' . $kolom, $c->gr_flx ?? 0);

            $sheet1->setCellValue('V' . $kolom, $pcs_awal_bk - $pcs_awal_cbt);
            $sheet1->setCellValue('W' . $kolom, $gr_awal_bk - $gr_awal_cbt);

            // $sheet1->setCellValue('O' . $kolom, number_format($c->susut ?? 0, 1));
            $sheet1->setCellValue('X' . $kolom, $c->ttl_rp ?? 0);

            $kolom++;

            $pcs_wip += $g->pcs ?? 0;
            $gr_wip += $g->gr ?? 0;
            $pcs_bk += $b->pcs_awal ?? 0;
            $gr_bk += $b->gr_awal ?? 0;

            $ttlPcsSisaSinta += $pcs_awal_wip - $pcs_awal_bk;
            $ttGrSisaSinta += $gr_awal_wip - $gr_awal_bk;


            $pcs_awal_cbt_ttl += $c->pcs_awal ?? 0;
            $gr_awal_cbt_ttl += $c->gr_awal ?? 0;
            $pcs_akhir_cbt_ttl += $c->pcs_akhir ?? 0;
            $gr_akhir_cbt_ttl += $c->gr_akhir ?? 0;
            $ttl_rp += $c->ttl_rp ?? 0;
        }


        $sheet1->getStyle('A2:X' . $kolom - 1)->applyFromArray($style);

        $sheet1->getStyle('A' . $kolom . ':X' . $kolom - 1)->applyFromArray($style_bawah);

        $sheet1->getStyle('L1:N' . $kolom - 1)->getFont()
            ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED));
        $sheet1->getStyle('V1:W' . $kolom - 1)->getFont()
            ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED));

        $sheet1->getStyle('L' . $kolom . ':N' . $kolom - 1)->getFont()
            ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED));
        $sheet1->getStyle('V' . $kolom . ':W' . $kolom - 1)->getFont()
            ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED));

        $namafile = "Summary Wip.xlsx";


        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $namafile);
        header('Cache-Control: max-age=0');


        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
    }
    private function export_summary2(Request $r)
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
        $style_bawah = array(
            'font' => [
                'bold' => true, // Mengatur teks menjadi tebal
            ],
            'borders' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ],
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
        $sheet1->setTitle('Summary Wip');


        $sheet1->getStyle("A1:U1")->applyFromArray($style_atas);

        $koloms = [
            'A' => '#',
            'B' => 'Ket / nama partai',
            'C' => 'Grade / No Lot',
            'D' => 'Wip Pcs',
            'E' => "Wip Gr",
            'F' => "Bk Pcs",
            'G' => "Bk Gr",
            'H' => "Susut Gr",
            'I' => "Susut %",
            'J' => "Wip Pcs Sisa",
            'K' => "Wip Gr Sisa",
            'L' => "Cbt Pcs Awal",
            'M' => "Cbt Gr Awal",
            'N' => "Cbt Pcs Akhir",
            'O' => "Cbt Gr Akhir",
            'P' => "Cbt Susut",
            'Q' => "Eo",
            'R' => "Flx",
            'S' => "Bk Pcs Sisa Pgws",
            'T' => "Bk Gr Sisa Pgws",
            'U' => "Ttl Rp"
        ];
        foreach ($koloms as $kolom => $d) {
            $sheet1->setCellValue($kolom . '1', $d);
        }

        $kolom = 2;
        $gudang = GudangBkModel::getSummaryWip();

        $pcs_wip = 0;
        $gr_wip = 0;
        $pcs_bk = 0;
        $gr_bk = 0;

        $pcs_awal_cbt_ttl = 0;
        $gr_awal_cbt_ttl = 0;
        $pcs_akhir_cbt_ttl = 0;
        $gr_akhir_cbt_ttl = 0;
        $ttl_rp = 0;
        $ttlPcsSisaSinta = 0;
        $ttGrSisaSinta = 0;
        foreach ($gudang as $no => $g) {
            $response = Http::get("$this->linkApi/bk_sum?nm_partai=$g->ket2");
            $b = $response->object();

            $resSum = Http::get("$this->linkApi/sarang_sum?nm_partai=$g->ket2");
            $c = $resSum->object();


            $sheet1->setCellValue('A' . $kolom, $no + 1);
            $sheet1->setCellValue('B' . $kolom, $g->ket2);
            $sheet1->setCellValue('C' . $kolom, $g->nm_grade);

            $sheet1->setCellValue('D' . $kolom, $g->pcs ?? 0);
            $sheet1->setCellValue('E' . $kolom, $g->gr ?? 0);

            $sheet1->setCellValue('F' . $kolom, $b->pcs_awal ?? 0);
            $sheet1->setCellValue('G' . $kolom, $b->gr_awal ?? 0);
            $bkSusut = empty($b->gr_awal) ? 0 : (1 - ($b->gr_awal / $g->gr)) * 100;
            $sheet1->setCellValue('H' . $kolom, $g->gr_susut);
            $sheet1->setCellValue('I' . $kolom, round($bkSusut, 1));

            $pcs_awal_wip = $g->pcs ?? 0;
            $gr_awal_wip = $g->gr ?? 0;
            $gr_awal_susut = $g->gr_susut ?? 0;

            $pcs_awal_bk = $b->pcs_awal ?? 0;
            $gr_awal_bk = $b->gr_awal ?? 0;

            $sheet1->setCellValue('J' . $kolom, $pcs_awal_wip - $pcs_awal_bk);
            $sheet1->setCellValue('K' . $kolom, $gr_awal_wip - $gr_awal_bk - $gr_awal_susut);

            $sheet1->setCellValue('L' . $kolom, $c->pcs_awal ?? 0);
            $sheet1->setCellValue('M' . $kolom, $c->gr_awal ?? 0);
            $sheet1->setCellValue('N' . $kolom, $c->pcs_akhir ?? 0);
            $sheet1->setCellValue('O' . $kolom, $c->gr_akhir ?? 0);

            $pcs_awal_cbt = $c->pcs_awal ?? 0;
            $gr_awal_cbt = $c->gr_awal ?? 0;

            $cbtSusut = empty($c->gr_awal) ? 0 : 1 - ((($gr_awal_bk - $gr_awal_cbt) + $c->gr_akhir) / $c->gr_awal);
            $sheet1->setCellValue('P' . $kolom, $cbtSusut);
            $sheet1->setCellValue('Q' . $kolom, $c->eot ?? 0);
            $sheet1->setCellValue('R' . $kolom, $c->gr_flx ?? 0);

            $sheet1->setCellValue('S' . $kolom, $pcs_awal_bk - $pcs_awal_cbt);
            $sheet1->setCellValue('T' . $kolom, $gr_awal_bk - $gr_awal_cbt);

            // $sheet1->setCellValue('O' . $kolom, number_format($c->susut ?? 0, 1));
            $sheet1->setCellValue('U' . $kolom, $c->ttl_rp ?? 0);

            $kolom++;

            $pcs_wip += $g->pcs ?? 0;
            $gr_wip += $g->gr ?? 0;
            $pcs_bk += $b->pcs_awal ?? 0;
            $gr_bk += $b->gr_awal ?? 0;

            $ttlPcsSisaSinta += $pcs_awal_wip - $pcs_awal_bk;
            $ttGrSisaSinta += $gr_awal_wip - $gr_awal_bk;


            $pcs_awal_cbt_ttl += $c->pcs_awal ?? 0;
            $gr_awal_cbt_ttl += $c->gr_awal ?? 0;
            $pcs_akhir_cbt_ttl += $c->pcs_akhir ?? 0;
            $gr_akhir_cbt_ttl += $c->gr_akhir ?? 0;
            $ttl_rp += $c->ttl_rp ?? 0;
        }


        $sheet1->getStyle('A2:U' . $kolom - 1)->applyFromArray($style);

        $sheet1->getStyle('A' . $kolom . ':U' . $kolom - 1)->applyFromArray($style_bawah);

        $sheet1->getStyle('J1:K' . $kolom - 1)->getFont()
            ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED));
        $sheet1->getStyle('S1:T' . $kolom - 1)->getFont()
            ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED));

        $sheet1->getStyle('J' . $kolom . ':K' . $kolom - 1)->getFont()
            ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED));
        $sheet1->getStyle('S' . $kolom . ':T' . $kolom - 1)->getFont()
            ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED));

        $namafile = "Summary Wip.xlsx";


        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $namafile);
        header('Cache-Control: max-age=0');


        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
    }

    function export_summary_lot(Request $r)
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
        $style_bawah = array(
            'font' => [
                'bold' => true, // Mengatur teks menjadi tebal
            ],
            'borders' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ],
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
        $sheet1->setTitle('Summary Wip');


        $sheet1->getStyle("A1:O1")->applyFromArray($style_atas);

        $sheet1->setCellValue('A1', '#');
        $sheet1->setCellValue('B1', 'Ket / nama partai');
        $sheet1->setCellValue('C1', 'No Lot');
        $sheet1->setCellValue('D1', 'Pcs Wip');
        $sheet1->setCellValue('E1', 'Gr Wip');
        $sheet1->setCellValue('F1', 'Pcs BK');
        $sheet1->setCellValue('G1', 'Gr BK');

        $sheet1->setCellValue('H1', 'Pcs Awal cbt');
        $sheet1->setCellValue('I1', 'Gr Awal cbt');
        $sheet1->setCellValue('J1', 'Pcs Akhir cbt');
        $sheet1->setCellValue('K1', 'Gr Akhir cbt');
        $sheet1->setCellValue('L1', 'Susut');
        $sheet1->setCellValue('M1', 'Rp Cabut');

        $sheet1->setCellValue('N1', 'Pcs Sisa cbt');
        $sheet1->setCellValue('O1', 'Gr Sisa cbt');

        $kolom = 2;
        $gudang = GudangBkModel::getSummaryWipLotexport();

        $pcs_wip = 0;
        $gr_wip = 0;
        $pcs_bk = 0;
        $gr_bk = 0;

        $pcs_awal_cbt_ttl = 0;
        $gr_awal_cbt_ttl = 0;
        $pcs_akhir_cbt_ttl = 0;
        $gr_akhir_cbt_ttl = 0;
        $ttl_rp = 0;
        foreach ($gudang as $no => $g) {
            $response = Http::get("$this->linkApi/sarang", [
                'nm_partai' => $g->ket2,
                'no_lot' => $g->no_lot
            ]);
            $b = $response->object()->bk_cabut;
            $c = $response->object()->cabut;


            $sheet1->setCellValue('A' . $kolom, $no + 1);
            $sheet1->setCellValue('B' . $kolom, $g->ket2);
            $sheet1->setCellValue('C' . $kolom, $g->no_lot);

            $sheet1->setCellValue('D' . $kolom, $g->pcs ?? 0);
            $sheet1->setCellValue('E' . $kolom, $g->gr ?? 0);

            $sheet1->setCellValue('F' . $kolom, $b->pcs_awal ?? 0);
            $sheet1->setCellValue('G' . $kolom, $b->gr_awal ?? 0);

            $sheet1->setCellValue('H' . $kolom, $c->pcs_awal ?? 0);
            $sheet1->setCellValue('I' . $kolom, $c->gr_awal ?? 0);
            $sheet1->setCellValue('J' . $kolom, $c->pcs_akhir ?? 0);
            $sheet1->setCellValue('K' . $kolom, $c->gr_akhir ?? 0);
            $sheet1->setCellValue('L' . $kolom, number_format($c->susut ?? 0, 1));
            $sheet1->setCellValue('M' . $kolom, $c->ttl_rp ?? 0);

            $pcs_awal_bk = $b->pcs_awal ?? 0;
            $gr_awal_bk = $b->gr_awal ?? 0;

            $pcs_awal_cbt = $c->pcs_awal ?? 0;
            $gr_awal_cbt = $c->gr_awal ?? 0;

            $sheet1->setCellValue('N' . $kolom, $pcs_awal_bk - $pcs_awal_cbt);
            $sheet1->setCellValue('O' . $kolom, $gr_awal_bk - $gr_awal_cbt);

            $kolom++;

            $pcs_wip += $g->pcs ?? 0;
            $gr_wip += $g->gr ?? 0;
            $pcs_bk += $b->pcs_awal ?? 0;
            $gr_bk += $b->gr_awal ?? 0;


            $pcs_awal_cbt_ttl += $c->pcs_awal ?? 0;
            $gr_awal_cbt_ttl += $c->gr_awal ?? 0;
            $pcs_akhir_cbt_ttl += $c->pcs_akhir ?? 0;
            $gr_akhir_cbt_ttl += $c->gr_akhir ?? 0;
            $ttl_rp += $c->ttl_rp ?? 0;
        }
        $sheet1->setCellValue('A' . $kolom, '');
        $sheet1->setCellValue('B' . $kolom, '');
        $sheet1->setCellValue('C' . $kolom, 'Total');

        $sheet1->setCellValue('D' . $kolom, $pcs_wip);
        $sheet1->setCellValue('E' . $kolom, $gr_wip);

        $sheet1->setCellValue('F' . $kolom, $pcs_bk);
        $sheet1->setCellValue('G' . $kolom, $gr_bk);

        $sheet1->setCellValue('H' . $kolom, $pcs_awal_cbt_ttl);
        $sheet1->setCellValue('I' . $kolom, $gr_awal_cbt_ttl);
        $sheet1->setCellValue('J' . $kolom, $pcs_akhir_cbt_ttl);
        $sheet1->setCellValue('K' . $kolom, $gr_akhir_cbt_ttl);
        $sheet1->setCellValue('L' . $kolom, '');
        $sheet1->setCellValue('M' . $kolom, $ttl_rp);
        $sheet1->setCellValue('N' . $kolom, $pcs_bk - $pcs_awal_cbt_ttl);
        $sheet1->setCellValue('O' . $kolom, $gr_bk - $gr_awal_cbt_ttl);

        $sheet1->getStyle('A2:O' . $kolom - 1)->applyFromArray($style);
        $sheet1->getStyle('A' . $kolom . ':O' . $kolom)->applyFromArray($style_bawah);
        $namafile = "Summary Wip Lot.xlsx";

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $namafile);
        header('Cache-Control: max-age=0');


        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
    }

    function import_summary_bk(Request $r)
    {
        $uploadedFile = $r->file('file');
        $allowedExtensions = ['xlsx'];
        $extension = $uploadedFile->getClientOriginalExtension();

        if (in_array($extension, $allowedExtensions)) {
            $spreadsheet = IOFactory::load($uploadedFile->getPathname());
            $sheet2 = $spreadsheet->getSheetByName('Summary Wip');
            $data = [];

            foreach ($sheet2->getRowIterator() as $index => $row) {
                if ($index === 1) {
                    continue;
                }

                $rowData = [];
                foreach ($row->getCellIterator() as $cell) {
                    $rowData[] = $cell->getValue();
                }
                $data[] = $rowData;
            }

            $importGagal = false;

            DB::beginTransaction(); // Mulai transaksi database

            try {
                foreach ($data as $rowData) {
                    DB::table('summary_wip')->insert([
                        'id_summary_wip' => $rowData[0],
                        'id_grade' => '1',
                        'pcs' => $rowData[5],
                        'gr' => $rowData[6],
                        'rupiah' => $rowData[7],
                        'ket' => empty($rowData[9]) ? ' ' : $rowData[9],
                        'lok_tgl' => empty($rowData[11]) ? ' ' : $rowData[11],
                        'approve' => 'Y',
                        'gabung' => $rowData[15],
                    ]);
                }

                if ($importGagal) {
                    DB::rollback(); // Batalkan transaksi jika ada kesalahan
                    return redirect()->route('gudangBk.index')->with('error', 'Data tidak valid: Kolom M, N, dan O tidak boleh memiliki nilai Y yang sama');
                }

                DB::commit(); // Konfirmasi transaksi jika berhasil
                return redirect()->route('gudangBk.index')->with('sukses', 'Data berhasil import');
            } catch (\Exception $e) {
                DB::rollback(); // Batalkan transaksi jika terjadi kesalahan lain
                return redirect()->route('gudangBk.index')->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
            }
        } else {
            return redirect()->route('gudangBk.index')->with('error', 'File yang diunggah bukan file Excel yang valid');
        }
    }

    function selesai1(Request $r)
    {
        DB::table('buku_campur_approve')->where([['gudang', '=', 'wip'], ['no_lot', '=', $r->no_lot], ['ket', '=', $r->ket]])->update([
            'selesai_1' => 'Y'
        ]);
        return redirect()->route('summarybk.index', ['nm_gudang', 'summary'])->with('sukses', 'Data berhasil diselesaikan');
    }
    function selesai2(Request $r)
    {
        DB::table('buku_campur_approve')->where([['gudang', '=', 'wip'], ['no_lot', '=', $r->no_lot], ['ket', '=', $r->ket]])->update([
            'selesai_2' => 'Y'
        ]);
        return redirect()->route('summarybk.index', ['nm_gudang' => 'summary'])->with('sukses', 'Data berhasil diselesaikan');
    }

    public function susut(Request $r)
    {
        if (empty($r->nm_gudang)) {
            $nmgudang = 'bk';
        } else {
            $nmgudang = $r->nm_gudang;
        }

        $gudang = GudangBkModel::getSummaryWip();
        $total = GudangBkModel::getPembelianBk('bk');

        $ttl_bk = 0;
        foreach ($total as $t) {
            $ttl_bk += $t->rupiah * $t->gr;
        }

        $listBulan = DB::table('bulan')->get();
        $id_user = auth()->user()->id;
        $data =  [
            'title' => 'Summary Wip',
            'gudang' => $gudang,
            'listbulan' => $listBulan,
            'nm_gudang' => $nmgudang,
            'total_bk' => $ttl_bk,
            'linkApi' => $this->linkApi,
            'total_invoice' => DB::selectOne("SELECT a.no_nota, b.no_nota, sum(a.total_harga) as ttl_hrga
            FROM invoice_bk as a left 
            join grading as b on b.no_nota = a.no_nota 
            where b.no_nota is null;")
        ];
        return view('summarybk.susut', $data);
    }

    public function save_susut(Request $r)
    {
        DB::table('table_susut')->truncate();
        for ($x = 0; $x < count($r->ket); $x++) {

            $data = [
                'ket' => $r->ket[$x],
                'gr' => $r->gr_susut[$x],
                'admin' => Auth::user()->name,
            ];
            DB::table('table_susut')->insert($data);
        }

        return redirect()->route('summarybk.susut', ['nm_gudang' => 'susut'])->with('sukses', 'Data berhasil diselesaikan');
    }

    function get_no_box(Request $r)
    {
        $response = Http::get(
            "$this->linkApi/show_box",
            [
                'nm_partai' => $r->nm_partai,
                'no_lot' => $r->no_lot,
                'limit' => $r->limit,
            ]
        );
        $b = $response->object();
        $data =  [
            'bk' => $b,
            'linkApi' => $this->linkApi,
            'nm_partai' => $r->nm_partai,
            'no_lot' => $r->no_lot,
        ];
        return view('summarybk.get_box', $data);
    }

    public function export_show_box(Request $r)
    {
        $response = Http::get(
            "$this->linkApi/show_box",
            [
                'nm_partai' => $r->nm_partai,
                'no_lot' => $r->no_lot,
                'limit' => $r->limit,
            ]
        );
        $b = $response->object();
        $data =  [
            'bk' => $b,
            'linkApi' => $this->linkApi
        ];
        return view('summarybk.export_show', $data);
    }
}
