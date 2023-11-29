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


        $sheet1->getStyle("A1:V2")->applyFromArray($style_atas);

        $sheet1->setCellValue('A1', 'ID');
        $sheet1->setCellValue('B1', 'No Lot');
        $sheet1->setCellValue('C1', 'Ket / nama partai');
        $sheet1->setCellValue('D1', 'Grade');
        $sheet1->setCellValue('E1', 'Ket');
        $sheet1->setCellValue('F1', 'Warna');
        $sheet1->setCellValue('G1', 'Gudang Bk');
        $sheet1->setCellValue('I1', 'Cabut');
        $sheet1->setCellValue('N1', 'Gdg cbt selesai');
        $sheet1->setCellValue('P1', 'Cetak');
        $sheet1->setCellValue('U1', 'Gdg ctk selesai');


        $sheet1->setCellValue('G2', 'Pcs');
        $sheet1->setCellValue('H2', 'Gr');

        $sheet1->setCellValue('I2', 'Pcs');
        $sheet1->setCellValue('J2', 'Gr');
        $sheet1->setCellValue('K2', 'Rp C');
        $sheet1->setCellValue('L2', 'Rp gram');
        $sheet1->setCellValue('M2', 'Susut');

        $sheet1->setCellValue('N2', 'Pcs');
        $sheet1->setCellValue('O2', 'Gr');

        $sheet1->setCellValue('P2', 'Pcs');
        $sheet1->setCellValue('Q2', 'Gr');
        $sheet1->setCellValue('R2', 'Rp C');
        $sheet1->setCellValue('S2', 'Rp gram');
        $sheet1->setCellValue('T2', 'Susut');

        $sheet1->setCellValue('U2', 'Pcs');
        $sheet1->setCellValue('V2', 'Gr');

        $sheet1->mergeCells('A1:A2');
        $sheet1->mergeCells('B1:B2');
        $sheet1->mergeCells('C1:C2');
        $sheet1->mergeCells('D1:D2');
        $sheet1->mergeCells('E1:E2');
        $sheet1->mergeCells('F1:F2');
        $sheet1->mergeCells('G1:H1');
        $sheet1->mergeCells('I1:M1');
        $sheet1->mergeCells('N1:O1');
        $sheet1->mergeCells('P1:T1');
        $sheet1->mergeCells('U1:V1');

        $kolom = 3;
        $gudang = GudangBkModel::getSummaryWip();
        foreach ($gudang as $g) {
            $response = Http::get("http://127.0.0.1:4000/api/apibk/export_sarang?no_lot=$g->no_lot&nm_partai=$g->ket");
            $cbt = $response['data']['bk_cabut'] ?? null;
            $c = json_decode(json_encode($cbt));


            foreach ($c as $s) {
                $response = Http::get("http://127.0.0.1:4000/api/apibk/cabut_export?no_box=$s->no_box");
                $cbt_2 = $response['data']['cabut'] ?? null;
                $ct = json_decode(json_encode($cbt_2));
                $ctk_2 = $response['data']['cetak'] ?? null;
                $ck = json_decode(json_encode($ctk_2));

                $sheet1->setCellValue('A' . $kolom, $g->id_buku_campur);
                $sheet1->setCellValue('B' . $kolom, $s->no_lot);
                $sheet1->setCellValue('C' . $kolom, $s->nm_partai);
                $sheet1->setCellValue('D' . $kolom, $s->tipe);
                $sheet1->setCellValue('E' . $kolom, $s->ket);
                $sheet1->setCellValue('F' . $kolom, $s->warna);

                $pcs_awal_cbt = $ct->pcs_awal ?? 0;
                $gr_awal_cbt = $ct->gr_awal ?? 0;
                $pcs_akhir_cbt = $ct->pcs_akhir ?? 0;
                $gr_akhir_cbt = $ct->gr_akhir ?? 0;

                $sheet1->setCellValue('G' . $kolom, $s->pcs_awal - $pcs_awal_cbt ?? 0);
                $sheet1->setCellValue('H' . $kolom, $s->gr_awal - $gr_awal_cbt ?? 0);

                $sheet1->setCellValue('I' . $kolom, $pcs_awal_cbt - $pcs_akhir_cbt);
                $sheet1->setCellValue('J' . $kolom, $gr_awal_cbt - $gr_akhir_cbt);
                $sheet1->setCellValue('K' . $kolom, $ct->rp_c ?? 0);
                $sheet1->setCellValue('L' . $kolom, round($ct->rp_gram ?? 0, 0));
                $sheet1->setCellValue('M' . $kolom, round($ct->susut ?? 0, 0) . '%');

                $ckpcs_awal = $ck->pcs_awal ?? 0;
                $ckgr_awal = $ck->gr_awal ?? 0;
                $ckpcs_akhir = $ck->pcs_akhir ?? 0;
                $ckgr_akhir = $ck->gr_akhir ?? 0;
                $sheet1->setCellValue('N' . $kolom, $pcs_akhir_cbt - $ckpcs_awal);
                $sheet1->setCellValue('O' . $kolom, $gr_akhir_cbt -  $ckgr_awal);

                $sheet1->setCellValue('P' . $kolom, $ckpcs_awal - $ckpcs_akhir);
                $sheet1->setCellValue('Q' . $kolom, $ckgr_awal - $ckgr_akhir);
                $sheet1->setCellValue('R' . $kolom, $ck->rp_c ?? 0);
                $sheet1->setCellValue('S' . $kolom, $ck->rp_gram ?? 0);
                $sheet1->setCellValue('T' . $kolom, $ck->susut ?? 0);

                $sheet1->setCellValue('U' . $kolom, $ck->pcs_akhir ?? 0);
                $sheet1->setCellValue('V' . $kolom, $ck->gr_akhir ?? 0);


                $kolom++;
            }
        }

        $sheet1->getStyle('A2:V' . $kolom - 1)->applyFromArray($style);
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
