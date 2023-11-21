<?php

namespace App\Http\Controllers;

use App\Models\GudangBkModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SummaryController extends Controller
{
    function index(Request $r)
    {
        if (empty($r->nm_gudang)) {
            $nmgudang = 'bk';
        } else {
            $nmgudang = $r->nm_gudang;
        }

        $gudang = GudangBkModel::getSummaryWip();

        $listBulan = DB::table('bulan')->get();
        $id_user = auth()->user()->id;
        $data =  [
            'title' => 'Summary Wip',
            'gudang' => $gudang,
            'listbulan' => $listBulan,
            'nm_gudang' => $nmgudang
        ];
        return view('summarybk.index', $data);
    }

    function export_summary(Request $r)
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
        $sheet1->setTitle('Summary Wip');


        $sheet1->getStyle("A1:AC2")->applyFromArray($style_atas);

        $sheet1->setCellValue('A1', 'ID');
        $sheet1->setCellValue('B1', 'No Lot');
        $sheet1->setCellValue('C1', 'Ket / nama partai');
        $sheet1->setCellValue('D1', 'Gudang');
        $sheet1->setCellValue('F1', 'Cabut');
        $sheet1->setCellValue('K1', 'Cetak');
        $sheet1->setCellValue('P1', 'Sortir');
        $sheet1->setCellValue('U1', 'Siap Kirim');
        $sheet1->setCellValue('Y1', 'Sudah Kirim');
        $sheet1->setCellValue('AC1', 'Total Rp C');

        $sheet1->setCellValue('D2', 'Pcs');
        $sheet1->setCellValue('E2', 'Gr');

        $sheet1->setCellValue('F2', 'Pcs');
        $sheet1->setCellValue('G2', 'Gr');
        $sheet1->setCellValue('H2', 'Rp C');
        $sheet1->setCellValue('I2', 'Rp/gr');
        $sheet1->setCellValue('J2', 'Susut');

        $sheet1->setCellValue('K2', 'Pcs');
        $sheet1->setCellValue('L2', 'Gr');
        $sheet1->setCellValue('M2', 'Rp C');
        $sheet1->setCellValue('N2', 'Rp/gr');
        $sheet1->setCellValue('O2', 'Susut');

        $sheet1->setCellValue('P2', 'Pcs');
        $sheet1->setCellValue('Q2', 'Gr');
        $sheet1->setCellValue('R2', 'Rp C');
        $sheet1->setCellValue('S2', 'Rp/gr');
        $sheet1->setCellValue('T2', 'Susut');

        $sheet1->setCellValue('U2', 'Pcs');
        $sheet1->setCellValue('V2', 'Gr');
        $sheet1->setCellValue('W2', 'Rp C');
        $sheet1->setCellValue('X2', 'Rp/gr');

        $sheet1->setCellValue('Y2', 'Pcs');
        $sheet1->setCellValue('Z2', 'Gr');
        $sheet1->setCellValue('AA2', 'Rp C');
        $sheet1->setCellValue('AB2', 'Rp/gr');

        $sheet1->mergeCells('A1:A2');
        $sheet1->mergeCells('B1:B2');
        $sheet1->mergeCells('C1:C2');
        $sheet1->mergeCells('AC1:AC2');

        $sheet1->mergeCells('D1:E1');
        $sheet1->mergeCells('F1:J1');
        $sheet1->mergeCells('K1:O1');
        $sheet1->mergeCells('P1:T1');
        $sheet1->mergeCells('U1:X1');
        $sheet1->mergeCells('Y1:AB1');
        $kolom = 3;
        $gudang = GudangBkModel::getSummaryWip();

        foreach ($gudang as $g) {
            $response = Http::get("http://127.0.0.1:4000/api/apibk/sarang?no_lot=$g->no_lot&nm_partai=$g->ket");
            $cbt = $response['data']['cabut'] ?? null;
            $c = json_decode(json_encode($cbt));
            $ctk = $response['data']['cetak'] ?? null;
            $ck = json_decode(json_encode($ctk));
            $str = $response['data']['sortir'] ?? null;
            $st = json_decode(json_encode($str));

            $sheet1->setCellValue('A' . $kolom, $g->id_buku_campur);
            $sheet1->setCellValue('B' . $kolom, $g->no_lot);
            $sheet1->setCellValue('C' . $kolom, $g->ket);
            $sheet1->setCellValue('D' . $kolom, $g->pcs);
            $sheet1->setCellValue('E' . $kolom, $g->gr);

            $sheet1->setCellValue('F' . $kolom, $c->pcs_awal ?? 0);
            $sheet1->setCellValue('G' . $kolom, $c->gr_akhir ?? 0);
            $sheet1->setCellValue('H' . $kolom, round($c->ttl_rp ?? 0, 0));
            $sheet1->setCellValue('I' . $kolom, round($c->rp_gram ?? 0, 0));
            $sheet1->setCellValue('J' . $kolom, round($c->susut ?? 0, 0) . '%');

            $sheet1->setCellValue('K' . $kolom, $ck->pcs_awal ?? 0);
            $sheet1->setCellValue('L' . $kolom, $ck->gr_awal ?? 0);
            $sheet1->setCellValue('M' . $kolom, $ck->rp_c ?? 0);
            $sheet1->setCellValue('N' . $kolom, round($ck->rp_gram ?? 0, 0));
            $sheet1->setCellValue('O' . $kolom, round($ck->susut ?? 0, 0) . '%');

            $sheet1->setCellValue('P' . $kolom, $st->pcs_awal ?? 0);
            $sheet1->setCellValue('Q' . $kolom, $st->gr_awal ?? 0);
            $sheet1->setCellValue('R' . $kolom, $st->rp_c ?? 0);
            $sheet1->setCellValue('S' . $kolom, round($st->rp_gram ?? 0, 0));
            $sheet1->setCellValue('T' . $kolom, round($st->susut ?? 0, 0) . '%');

            $sheet1->setCellValue('U' . $kolom, ' ');
            $sheet1->setCellValue('V' . $kolom, ' ');
            $sheet1->setCellValue('W' . $kolom, ' ');
            $sheet1->setCellValue('X' . $kolom, ' ');
            $sheet1->setCellValue('Y' . $kolom, ' ');
            $sheet1->setCellValue('Z' . $kolom, ' ');
            $sheet1->setCellValue('AA' . $kolom, ' ');
            $sheet1->setCellValue('AB' . $kolom, ' ');

            $rp_cabut = $c->ttl_rp ?? 0;
            $rp_cetak = $ck->rp_c ?? 0;
            $rp_sortir = $st->rp_c ?? 0;

            $sheet1->setCellValue('AC' . $kolom, round($rp_cabut + $rp_cetak + $rp_sortir, 0));




            $kolom++;
        }

        $sheet1->getStyle('A2:AC' . $kolom - 1)->applyFromArray($style);
        $namafile = "Summary Wip.xlsx";

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
}
