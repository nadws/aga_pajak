<?php

namespace App\Http\Controllers;

use App\Models\GudangBkModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class GudangBkController extends Controller
{
    protected $tgl1, $tgl2, $period;
    public function __construct(Request $r)
    {
        if (empty($r->period)) {
            $this->tgl1 = date('Y-m-01');
            $this->tgl2 = date('Y-m-t');
        } elseif ($r->period == 'daily') {
            $this->tgl1 = date('Y-m-d');
            $this->tgl2 = date('Y-m-d');
        } elseif ($r->period == 'weekly') {
            $this->tgl1 = date('Y-m-d', strtotime("-6 days"));
            $this->tgl2 = date('Y-m-d');
        } elseif ($r->period == 'mounthly') {
            $bulan = $r->bulan;
            $tahun = $r->tahun;
            $tgl = "$tahun" . "-" . "$bulan" . "-" . "01";

            $this->tgl1 = date('Y-m-01', strtotime($tgl));
            $this->tgl2 = date('Y-m-t', strtotime($tgl));
        } elseif ($r->period == 'costume') {
            $this->tgl1 = $r->tgl1;
            $this->tgl2 = $r->tgl2;
        } elseif ($r->period == 'years') {
            $tahun = $r->tahunfilter;
            $tgl_awal = "$tahun" . "-" . "01" . "-" . "01";
            $tgl_akhir = "$tahun" . "-" . "12" . "-" . "01";

            $this->tgl1 = date('Y-m-01', strtotime($tgl_awal));
            $this->tgl2 = date('Y-m-t', strtotime($tgl_akhir));
        }
    }
    function index(Request $r)
    {
        $tgl1 =  $this->tgl1;
        $tgl2 =  $this->tgl2;

        if (empty($r->nm_gudang)) {
            $nmgudang = 'bk';
        } else {
            $nmgudang = $r->nm_gudang;
        }
        $gudang = GudangBkModel::getPembelianBk($nmgudang);

        $listBulan = DB::table('bulan')->get();
        $id_user = auth()->user()->id;
        $data =  [
            'title' => 'Gudang BK',
            'gudang' => $gudang,
            'listbulan' => $listBulan,
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
            'nm_gudang' => $nmgudang
        ];
        return view('gudang_bk.index', $data);
    }

    public function export_buku_campur_bk(Request $r)
    {
        if ($r->submit == 'export_produksi') {
            $this->export_gudang_produksi($r);
        } else {
            $this->export_gudang_bk($r);
        }
    }

    private function export_gudang_bk(Request $r)
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
        $sheet1->setTitle('Gudang BK');


        $sheet1->getStyle("A1:P1")->applyFromArray($style_atas);

        $sheet1->setCellValue('A1', 'ID');
        $sheet1->setCellValue('B1', 'Buku');
        $sheet1->setCellValue('C1', 'Suplier Awal');
        $sheet1->setCellValue('D1', 'Date');
        $sheet1->setCellValue('E1', 'Grade');
        $sheet1->setCellValue('F1', 'Pcs');
        $sheet1->setCellValue('G1', 'Gram');
        $sheet1->setCellValue('H1', 'Rp/Gr');
        $sheet1->setCellValue('I1', 'Lot');
        $sheet1->setCellValue('J1', 'Keterangan');
        $sheet1->setCellValue('K1', 'Ttl Rp');
        $sheet1->setCellValue('L1', 'Lok');
        $sheet1->setCellValue('M1', 'Gudang BK');
        $sheet1->setCellValue('N1', 'Gudang Produksi');
        // $sheet1->setCellValue('O1', 'Gudang Wip');
        $sheet1->setCellValue('O1', 'Gudang Reject');
        $sheet1->setCellValue('P1', 'Hapus');

        $sheet1->setCellValue('R2', 'kl mau import barang baru id kosongkan');
        $kolom = 2;


        if (empty($r->id_buku_campur)) {
            $pembelian = GudangBkModel::getPembelianBk($r->gudang);

            foreach ($pembelian as $d) {
                $sheet1->setCellValue('A' . $kolom, $d->id_buku_campur);
                $sheet1->setCellValue('B' . $kolom, $d->buku);
                $sheet1->setCellValue('C' . $kolom, $d->suplier_awal);
                $sheet1->setCellValue('D' . $kolom, $d->tgl);
                $sheet1->setCellValue('E' . $kolom, $d->nm_grade);
                $sheet1->setCellValue('F' . $kolom, $d->pcs);
                $sheet1->setCellValue('G' . $kolom, $d->gr);
                $sheet1->setCellValue('H' . $kolom, $d->rupiah);
                $sheet1->setCellValue('I' . $kolom, $d->no_lot);
                $sheet1->setCellValue('J' . $kolom, $d->ket);
                $sheet1->setCellValue('K' . $kolom, $d->rupiah * $d->gr);
                $sheet1->setCellValue('L' . $kolom, $d->lok_tgl);
                $sheet1->setCellValue('M' . $kolom, $d->gudang == 'bk' ? 'Y' : 'T');
                $sheet1->setCellValue('N' . $kolom, $d->gudang == 'produksi' ? 'Y' : 'T');
                // $sheet1->setCellValue('O' . $kolom, $d->gudang == 'wip' ? 'Y' : 'T');
                $sheet1->setCellValue('O' . $kolom, $d->gudang == 'reject' ? 'Y' : 'T');
                $sheet1->setCellValue('P' . $kolom, $d->gabung);

                $kolom++;
            }
        } else {

            for ($x = 0; $x < count($r->id_buku_campur); $x++) {
                $id_buku_campur = $r->id_buku_campur[$x];
                $pembelian = GudangBkModel::getPembelianBkExport($id_buku_campur);

                $sheet1->setCellValue('A' . $kolom, $pembelian->id_buku_campur);
                $sheet1->setCellValue('B' . $kolom, $pembelian->buku);
                $sheet1->setCellValue('C' . $kolom, $pembelian->suplier_awal);
                $sheet1->setCellValue('D' . $kolom, $pembelian->tgl);
                $sheet1->setCellValue('E' . $kolom, $pembelian->nm_grade);
                $sheet1->setCellValue('F' . $kolom, $pembelian->pcs);
                $sheet1->setCellValue('G' . $kolom, $pembelian->gr);
                $sheet1->setCellValue('H' . $kolom, $pembelian->rupiah);
                $sheet1->setCellValue('I' . $kolom, $pembelian->no_lot);
                $sheet1->setCellValue('J' . $kolom, $pembelian->ket);
                $sheet1->setCellValue('K' . $kolom, $pembelian->rupiah * $pembelian->gr);
                $sheet1->setCellValue('L' . $kolom, $pembelian->lok_tgl);
                $sheet1->setCellValue('M' . $kolom, $pembelian->gudang == 'bk' ? 'Y' : 'T');
                $sheet1->setCellValue('N' . $kolom, $pembelian->gudang == 'produksi' ? 'Y' : 'T');
                // $sheet1->setCellValue('O' . $kolom, $pembelian->gudang == 'wip' ? 'Y' : 'T');
                $sheet1->setCellValue('O' . $kolom, $pembelian->gudang == 'reject' ? 'Y' : 'T');
                $sheet1->setCellValue('P' . $kolom, $pembelian->gabung);

                $kolom++;
            }
        }
        $sheet1->getStyle('A2:P' . $kolom - 1)->applyFromArray($style);
        $namafile = "Gudang Bk.xlsx";

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $namafile);
        header('Cache-Control: max-age=0');


        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
    }
    private function export_gudang_produksi(Request $r)
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
        $sheet1->setTitle('Gudang BK');


        $sheet1->getStyle("A1:O1")->applyFromArray($style_atas);

        $sheet1->setCellValue('A1', 'ID');
        $sheet1->setCellValue('B1', 'Buku');
        $sheet1->setCellValue('C1', 'Suplier Awal');
        $sheet1->setCellValue('D1', 'Date');
        $sheet1->setCellValue('E1', 'Grade');
        $sheet1->setCellValue('F1', 'Pcs');
        $sheet1->setCellValue('G1', 'Gram');
        $sheet1->setCellValue('H1', 'Rp/Gr');
        $sheet1->setCellValue('I1', 'Lot');
        $sheet1->setCellValue('J1', 'Keterangan');
        $sheet1->setCellValue('K1', 'Ttl Rp');
        $sheet1->setCellValue('L1', 'Lok');
        $sheet1->setCellValue('M1', 'Gudang Produksi');
        $sheet1->setCellValue('N1', 'Gudang Wip');
        $sheet1->setCellValue('O1', 'Hapus');
        $sheet1->setCellValue('Q2', 'kl mau import barang baru id kosongkan');
        $kolom = 2;


        if (empty($r->id_buku_campur)) {
            $pembelian = GudangBkModel::getPembelianBk($r->gudang);

            foreach ($pembelian as $d) {
                $sheet1->setCellValue('A' . $kolom, $d->id_buku_campur);
                $sheet1->setCellValue('B' . $kolom, $d->buku);
                $sheet1->setCellValue('C' . $kolom, $d->suplier_awal);
                $sheet1->setCellValue('D' . $kolom, $d->tgl);
                $sheet1->setCellValue('E' . $kolom, $d->nm_grade);
                $sheet1->setCellValue('F' . $kolom, $d->pcs);
                $sheet1->setCellValue('G' . $kolom, $d->gr);
                $sheet1->setCellValue('H' . $kolom, $d->rupiah);
                $sheet1->setCellValue('I' . $kolom, $d->no_lot);
                $sheet1->setCellValue('J' . $kolom, $d->ket);
                $sheet1->setCellValue('K' . $kolom, $d->rupiah * $d->gr);
                $sheet1->setCellValue('L' . $kolom, $d->lok_tgl);
                $sheet1->setCellValue('M' . $kolom, $d->gudang == 'produksi' ? 'Y' : 'T');
                $sheet1->setCellValue('N' . $kolom, $d->gudang == 'wip' ? 'Y' : 'T');
                $sheet1->setCellValue('O' . $kolom, $d->gabung);

                $kolom++;
            }
        } else {

            for ($x = 0; $x < count($r->id_buku_campur); $x++) {
                $id_buku_campur = $r->id_buku_campur[$x];
                $pembelian = GudangBkModel::getPembelianBkExport($id_buku_campur);

                $sheet1->setCellValue('A' . $kolom, $pembelian->id_buku_campur);
                $sheet1->setCellValue('B' . $kolom, $pembelian->buku);
                $sheet1->setCellValue('C' . $kolom, $pembelian->suplier_awal);
                $sheet1->setCellValue('D' . $kolom, $pembelian->tgl);
                $sheet1->setCellValue('E' . $kolom, $pembelian->nm_grade);
                $sheet1->setCellValue('F' . $kolom, $pembelian->pcs);
                $sheet1->setCellValue('G' . $kolom, $pembelian->gr);
                $sheet1->setCellValue('H' . $kolom, $pembelian->rupiah);
                $sheet1->setCellValue('I' . $kolom, $pembelian->no_lot);
                $sheet1->setCellValue('J' . $kolom, $pembelian->ket);
                $sheet1->setCellValue('K' . $kolom, $pembelian->rupiah * $pembelian->gr);
                $sheet1->setCellValue('L' . $kolom, $pembelian->lok_tgl);
                $sheet1->setCellValue('M' . $kolom, $pembelian->gudang == 'produksi' ? 'Y' : 'T');
                $sheet1->setCellValue('N' . $kolom, $pembelian->gudang == 'wip' ? 'Y' : 'T');
                $sheet1->setCellValue('O' . $kolom, $pembelian->gabung);

                $kolom++;
            }
        }
        $sheet1->getStyle('A2:O' . $kolom - 1)->applyFromArray($style);
        $namafile = "Gudang Bk.xlsx";

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $namafile);
        header('Cache-Control: max-age=0');


        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
    }

    public function import_buku_campur_bk(Request $r)
    {
        if ($r->gudang == 'produksi' || $r->gudang == 'wip') {
            $this->import_gudang_produksi($r);
        } else {
            $this->import_gudang_bk($r);
        }
        return redirect()->route('gudangBk.index', ['nm_gudang' => $r->gudang])->with('sukses', 'Data berhasil import');
    }

    private function import_gudang_bk(Request $r)
    {
        $uploadedFile = $r->file('file');
        $allowedExtensions = ['xlsx'];
        $extension = $uploadedFile->getClientOriginalExtension();

        if (in_array($extension, $allowedExtensions)) {
            $spreadsheet = IOFactory::load($uploadedFile->getPathname());
            $sheet2 = $spreadsheet->getSheetByName('Gudang BK');
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
                    $rowBk = 12;

                    if (($rowData[$rowBk] == 'Y' && $rowData[$rowBk + 1] == 'Y') ||
                        ($rowData[$rowBk] == 'Y' && $rowData[$rowBk + 2] == 'Y') ||
                        ($rowData[$rowBk + 2] == 'Y' && $rowData[$rowBk + 1] == 'Y') ||
                        ($rowData[$rowBk] == 'Y' && $rowData[$rowBk + 1] == 'Y' && $rowData[$rowBk + 2] == 'Y')
                    ) {
                        $importGagal = true;
                        break;
                    }

                    if ($rowData[$rowBk] == 'Y') {
                        $gudang = 'bk';
                    } elseif ($rowData[$rowBk + 1] == 'Y') {
                        $gudang = 'produksi';
                    } elseif ($rowData[$rowBk + 2] == 'Y') {
                        $gudang = 'reject';
                    }

                    if (empty($rowData[0])) {
                        DB::table('buku_campur')->insert([
                            'no_lot' => $rowData[8],
                            'id_grade' => '1',
                            'pcs' => $rowData[5],
                            'gr' => $rowData[6],
                            'rupiah' => $rowData[7],
                            'ket' => empty($rowData[9]) ? ' ' : $rowData[9],
                            'lok_tgl' => empty($rowData[11]) ? ' ' : $rowData[11],
                            'approve' => 'Y',
                            'gabung' => $rowData[15],
                            'gudang' => $gudang,

                        ]);
                        $idBukuCampur = DB::getPdo()->lastInsertId();

                        $tgl = $rowData[3];
                        if (is_numeric($tgl)) {
                            // Jika nilai berupa angka, konversi ke format tanggal
                            $tanggalExcel = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tgl);
                            $tanggalFormatted = $tanggalExcel->format('Y-m-d');
                        } else {
                            // Jika nilai sudah dalam format tanggal, pastikan formatnya adalah 'Y-m-d'
                            $tanggalFormatted = date('Y-m-d', strtotime($tgl));
                        }




                        DB::table('buku_campur_approve')->insert([
                            'id_buku_campur' => $idBukuCampur,
                            'buku' => empty($rowData[1]) ? ' ' : $rowData[1],
                            'suplier_awal' => empty($rowData[2]) ? ' ' : $rowData[2],
                            'tgl' => empty($tanggalFormatted) ? '0000-00-00' : $tanggalFormatted,
                            'nm_grade' => $rowData[4],
                            'pcs' => $rowData[5],
                            'gr' => $rowData[6],
                            'no_lot' => $rowData[8],
                            'ket' => empty($rowData[9]) ? ' ' : $rowData[9],
                            'rupiah' => $rowData[7],
                            'lok_tgl' => empty($rowData[11]) ? ' ' : $rowData[11],
                            'gudang' => $gudang,
                        ]);
                    } else {
                        DB::table('buku_campur')->where('id_buku_campur', $rowData[0])->update([
                            'approve' => 'Y',
                            'gabung' => $rowData[15],
                            'gudang' => $gudang,
                        ]);

                        $bk_approve = DB::table('buku_campur_approve')->where('id_buku_campur', $rowData[0])->first();

                        $tgl = $rowData[3];
                        if (is_numeric($tgl)) {
                            // Jika nilai berupa angka, konversi ke format tanggal
                            $tanggalExcel = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tgl);
                            $tanggalFormatted = $tanggalExcel->format('Y-m-d');
                        } else {
                            // Jika nilai sudah dalam format tanggal, pastikan formatnya adalah 'Y-m-d'
                            $tanggalFormatted = date('Y-m-d', strtotime($tgl));
                        }

                        if (empty($bk_approve)) {
                            DB::table('buku_campur_approve')->insert([
                                'id_buku_campur' => $rowData[0],
                                'buku' => $rowData[1],
                                'suplier_awal' => $rowData[2],
                                'tgl' => empty($tanggalFormatted) ? '0000-00-00' : $tanggalFormatted,
                                'nm_grade' => $rowData[4],
                                'pcs' => $rowData[5],
                                'gr' => $rowData[6],
                                'no_lot' => $rowData[8],
                                'ket' => empty($rowData[9]) ? ' ' : $rowData[9],
                                'rupiah' => $rowData[7],
                                'lok_tgl' => empty($rowData[11]) ? ' ' : $rowData[11],
                                'gudang' => $gudang,
                            ]);
                        } else {
                            DB::table('buku_campur_approve')->where('id_buku_campur', $rowData[0])->update([
                                'id_buku_campur' => $rowData[0],
                                'buku' => $rowData[1],
                                'suplier_awal' => $rowData[2],
                                'tgl' => empty($tanggalFormatted) ? '0000-00-00' : $tanggalFormatted,
                                'nm_grade' => $rowData[4],
                                'pcs' => $rowData[5],
                                'gr' => $rowData[6],
                                'no_lot' => $rowData[8],
                                'ket' => empty($rowData[9]) ? ' ' : $rowData[9],
                                'rupiah' => $rowData[7],
                                'lok_tgl' => empty($rowData[11]) ? ' ' : $rowData[11],
                                'gudang' => $gudang,
                            ]);
                        }
                    }
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
    private function import_gudang_produksi(Request $r)
    {
        $uploadedFile = $r->file('file');
        $allowedExtensions = ['xlsx'];
        $extension = $uploadedFile->getClientOriginalExtension();

        if (in_array($extension, $allowedExtensions)) {
            $spreadsheet = IOFactory::load($uploadedFile->getPathname());
            $sheet2 = $spreadsheet->getSheetByName('Gudang BK');
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
                    $rowBk = 12;

                    if (($rowData[$rowBk] == 'Y' && $rowData[$rowBk + 1] == 'Y')) {
                        $importGagal = true;
                        break;
                    }

                    if ($rowData[$rowBk] == 'Y') {
                        $gudang = 'produksi';
                    } elseif ($rowData[$rowBk + 1] == 'Y') {
                        $gudang = 'wip';
                    }

                    if (empty($rowData[0])) {
                        DB::table('buku_campur')->insert([
                            'no_lot' => $rowData[8],
                            'id_grade' => '1',
                            'pcs' => $rowData[5],
                            'gr' => $rowData[6],
                            'rupiah' => $rowData[7],
                            'ket' => empty($rowData[9]) ? ' ' : $rowData[9],
                            'lok_tgl' => empty($rowData[11]) ? ' ' : $rowData[11],
                            'approve' => 'Y',
                            'gabung' => $rowData[14],
                            'gudang' => $gudang,

                        ]);
                        $idBukuCampur = DB::getPdo()->lastInsertId();

                        $tanggal_excel = $rowData[3];
                        if (\DateTime::createFromFormat('Y-m-d', $tanggal_excel) !== false) {
                            $tanggal_mysql = $tanggal_excel;
                        } else {
                            $tanggal_mysql = \DateTime::createFromFormat('d/m/Y', $tanggal_excel);
                            if ($tanggal_mysql !== false) {
                                $tanggal_mysql = $tanggal_mysql->format('Y-m-d');
                            } else {
                                $tanggal_mysql = '0000-00-00';
                            }
                        }

                        DB::table('buku_campur_approve')->insert([
                            'id_buku_campur' => $idBukuCampur,
                            'buku' => $rowData[1],
                            'suplier_awal' => $rowData[2],
                            'tgl' => $tanggal_excel,
                            'nm_grade' => $rowData[4],
                            'pcs' => $rowData[5],
                            'gr' => $rowData[6],
                            'no_lot' => $rowData[8],
                            'ket' => empty($rowData[9]) ? ' ' : $rowData[9],
                            'rupiah' => $rowData[7],
                            'lok_tgl' => empty($rowData[11]) ? ' ' : $rowData[11],
                            'gudang' => $gudang,
                        ]);
                    } else {
                        DB::table('buku_campur')->where('id_buku_campur', $rowData[0])->update([
                            'approve' => 'Y',
                            'gabung' => $rowData[14],
                            'gudang' => $gudang,
                        ]);

                        $bk_approve = DB::table('buku_campur_approve')->where('id_buku_campur', $rowData[0])->first();

                        if (empty($bk_approve)) {
                            DB::table('buku_campur_approve')->insert([
                                'id_buku_campur' => $rowData[0],
                                'buku' => $rowData[1],
                                'suplier_awal' => $rowData[2],
                                'tgl' => $rowData[3],
                                'nm_grade' => $rowData[4],
                                'pcs' => $rowData[5],
                                'gr' => $rowData[6],
                                'no_lot' => $rowData[8],
                                'ket' => empty($rowData[9]) ? ' ' : $rowData[9],
                                'rupiah' => $rowData[7],
                                'lok_tgl' => empty($rowData[11]) ? ' ' : $rowData[11],
                                'gudang' => $gudang,
                            ]);
                        } else {
                            DB::table('buku_campur_approve')->where('id_buku_campur', $rowData[0])->update([
                                'id_buku_campur' => $rowData[0],
                                'buku' => $rowData[1],
                                'suplier_awal' => $rowData[2],
                                'tgl' => $rowData[3],
                                'nm_grade' => $rowData[4],
                                'pcs' => $rowData[5],
                                'gr' => $rowData[6],
                                'no_lot' => $rowData[8],
                                'ket' => empty($rowData[9]) ? ' ' : $rowData[9],
                                'rupiah' => $rowData[7],
                                'lok_tgl' => empty($rowData[11]) ? ' ' : $rowData[11],
                                'gudang' => $gudang,
                            ]);
                        }
                    }
                }

                if ($importGagal) {
                    DB::rollback(); // Batalkan transaksi jika ada kesalahan
                    return redirect()->route('gudangBk.index')->with('error', 'Data tidak valid: Kolom M,  dan N tidak boleh memiliki nilai Y yang sama');
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
