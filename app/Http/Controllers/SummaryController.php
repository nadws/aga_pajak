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
            'lot' => $gudang
        ];
        return view('summarybk.get_lot', $data);
    }
    function get_no_box(Request $r)
    {
        $response = Http::get("http://127.0.0.1:8000/api/apibk/show_box?nm_partai=$r->nm_partai&no_lot=$r->no_lot");
        $bk = $response['data']['bk_cabut'] ?? null;
        $b = json_decode(json_encode($bk));

        $data =  [
            'bk' => $b,
        ];
        return view('summarybk.get_box', $data);
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


        $sheet1->getStyle("A1:N2")->applyFromArray($style_atas);

        $sheet1->setCellValue('A1', '#');
        $sheet1->setCellValue('B1', 'Ket / nama partai');
        $sheet1->setCellValue('C1', 'Gudang Wip');
        $sheet1->setCellValue('E1', 'BK');
        $sheet1->setCellValue('G1', 'Cabut');
        $sheet1->setCellValue('M1', 'Sisa');



        $sheet1->setCellValue('C2', 'Pcs');
        $sheet1->setCellValue('D2', 'Gr');

        $sheet1->setCellValue('E2', 'Pcs');
        $sheet1->setCellValue('F2', 'Gr');

        $sheet1->setCellValue('G2', 'Pcs Awal');
        $sheet1->setCellValue('H2', 'Gr Awal');
        $sheet1->setCellValue('I2', 'Pcs Akhir');
        $sheet1->setCellValue('J2', 'Gr Akhir');
        $sheet1->setCellValue('K2', 'Susut');
        $sheet1->setCellValue('L2', 'Rp');

        $sheet1->setCellValue('M2', 'Pcs');
        $sheet1->setCellValue('N2', 'Gr');


        $sheet1->mergeCells('A1:A2');
        $sheet1->mergeCells('B1:B2');
        $sheet1->mergeCells('C1:D1');
        $sheet1->mergeCells('E1:F1');
        $sheet1->mergeCells('G1:L1');
        $sheet1->mergeCells('M1:N1');

        $kolom = 3;
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
        foreach ($gudang as $no => $g) {
            $response = Http::get("http://127.0.0.1:8000/api/apibk/bk_sum?nm_partai=$g->ket");
            $bk = $response['data']['bk_cabut'] ?? null;
            $b = json_decode(json_encode($bk));

            $response = Http::get("http://127.0.0.1:8000/api/apibk/sarang_sum?nm_partai=$g->ket");
            $cbt = $response['data']['cabut'] ?? null;
            $c = json_decode(json_encode($cbt));


            $sheet1->setCellValue('A' . $kolom, $no + 1);
            $sheet1->setCellValue('B' . $kolom, $g->ket);

            $sheet1->setCellValue('C' . $kolom, $g->pcs ?? 0);
            $sheet1->setCellValue('D' . $kolom, $g->gr ?? 0);

            $sheet1->setCellValue('E' . $kolom, $b->pcs_awal ?? 0);
            $sheet1->setCellValue('F' . $kolom, $b->gr_awal ?? 0);

            $sheet1->setCellValue('G' . $kolom, $c->pcs_awal ?? 0);
            $sheet1->setCellValue('H' . $kolom, $c->gr_awal ?? 0);
            $sheet1->setCellValue('I' . $kolom, $c->pcs_akhir ?? 0);
            $sheet1->setCellValue('J' . $kolom, $c->gr_akhir ?? 0);
            $sheet1->setCellValue('K' . $kolom, number_format($c->susut ?? 0, 1));
            $sheet1->setCellValue('L' . $kolom, $c->ttl_rp ?? 0);

            $pcs_awal_bk = $b->pcs_awal ?? 0;
            $gr_awal_bk = $b->gr_awal ?? 0;

            $pcs_awal_cbt = $c->pcs_awal ?? 0;
            $gr_awal_cbt = $c->gr_awal ?? 0;

            $sheet1->setCellValue('M' . $kolom, $pcs_awal_bk - $pcs_awal_cbt);
            $sheet1->setCellValue('N' . $kolom, $gr_awal_bk - $gr_awal_cbt);

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
        $sheet1->setCellValue('B' . $kolom, 'Total');

        $sheet1->setCellValue('C' . $kolom, $pcs_wip);
        $sheet1->setCellValue('D' . $kolom, $gr_wip);

        $sheet1->setCellValue('E' . $kolom, $pcs_bk);
        $sheet1->setCellValue('F' . $kolom, $gr_bk);

        $sheet1->setCellValue('G' . $kolom, $pcs_awal_cbt_ttl);
        $sheet1->setCellValue('H' . $kolom, $gr_awal_cbt_ttl);
        $sheet1->setCellValue('I' . $kolom, $pcs_akhir_cbt_ttl);
        $sheet1->setCellValue('J' . $kolom, $gr_akhir_cbt_ttl);
        $sheet1->setCellValue('K' . $kolom, '');
        $sheet1->setCellValue('L' . $kolom, $ttl_rp);
        $sheet1->setCellValue('M' . $kolom, $pcs_bk - $pcs_awal_cbt_ttl);
        $sheet1->setCellValue('N' . $kolom, $gr_bk - $gr_awal_cbt_ttl);

        $sheet1->getStyle('A2:N' . $kolom)->applyFromArray($style);
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
}
