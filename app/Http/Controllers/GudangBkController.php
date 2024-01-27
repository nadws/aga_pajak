<?php

namespace App\Http\Controllers;

use App\Models\GudangBkModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;



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
            'presiden' => auth()->user()->posisi_id == 1 ? true : false,
            'nm_gudang' => $nmgudang
        ];
        return view('gudang_bk.index', $data);
    }

    public function export_buku_campur_bk(Request $r)
    {
        if (auth()->user()->posisi_id == 1) {
            $this->export_gudang_bk($r);
        } else {
            $this->export_gudang_produksi($r);
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
        $sheet1->setTitle('baku dari bahan sdh grade');

        $sheet1->getStyle("A1:M1")->applyFromArray($style_atas);
        $sheet1->setCellValue('A1', 'ID');
        $sheet1->setCellValue('B1', 'Buku');
        $sheet1->setCellValue('C1', 'Suplier Awal');
        $sheet1->setCellValue('D1', 'Date');
        $sheet1->setCellValue('E1', 'Grade');
        $sheet1->setCellValue('F1', 'Pcs');
        $sheet1->setCellValue('G1', 'Gram');
        $sheet1->setCellValue('H1', 'Rp/Gr');
        $sheet1->setCellValue('I1', 'Lot');
        $sheet1->setCellValue('J1', 'Keterangan/Nama Partai Herry');
        $sheet1->setCellValue('K1', 'Keterangan/Nama Partai Sinta');
        $sheet1->setCellValue('L1', 'Ttl Rp');
        $sheet1->setCellValue('M1', 'Lok');
        $sheet1->setCellValue('O2', 'kl mau import barang baru id kosongkan');
        $kolom = 2;
        $pembelian = GudangBkModel::export_getPembelianBk('bk');
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
            $sheet1->setCellValue('K' . $kolom, $d->ket2);
            $sheet1->setCellValue('L' . $kolom, $d->rupiah * $d->gr);
            $sheet1->setCellValue('M' . $kolom, $d->lok_tgl);


            $kolom++;
        }

        $sheet1->getStyle('A2:M' . $kolom - 1)->applyFromArray($style);

        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(1);
        $sheet2 = $spreadsheet->getActiveSheet();
        $sheet2->setTitle('⁠penggabungan grd sama');

        $sheet2->getStyle("A1:M1")->applyFromArray($style_atas);
        $sheet2->setCellValue('A1', 'ID');
        $sheet2->setCellValue('B1', 'Buku');
        $sheet2->setCellValue('C1', 'Suplier Awal');
        $sheet2->setCellValue('D1', 'Date');
        $sheet2->setCellValue('E1', 'Grade');
        $sheet2->setCellValue('F1', 'Pcs');
        $sheet2->setCellValue('G1', 'Gram');
        $sheet2->setCellValue('H1', 'Rp/Gr');
        $sheet2->setCellValue('I1', 'Lot');
        $sheet2->setCellValue('J1', 'Keterangan/Nama Partai Herry');
        $sheet2->setCellValue('K1', 'Keterangan/Nama Partai Sinta');
        $sheet2->setCellValue('L1', 'Ttl Rp');
        $sheet2->setCellValue('M1', 'Lok');
        $sheet2->setCellValue('O2', 'kl mau import barang baru id kosongkan');
        $kolom = 2;
        $kolom = 2;
        $pembelian = GudangBkModel::export_getPembelianBk('gabung');

        foreach ($pembelian as $d) {
            $sheet2->setCellValue('A' . $kolom, $d->id_buku_campur);
            $sheet2->setCellValue('B' . $kolom, $d->buku);
            $sheet2->setCellValue('C' . $kolom, $d->suplier_awal);
            $sheet2->setCellValue('D' . $kolom, $d->tgl);
            $sheet2->setCellValue('E' . $kolom, $d->nm_grade);
            $sheet2->setCellValue('F' . $kolom, $d->pcs);
            $sheet2->setCellValue('G' . $kolom, $d->gr);
            $sheet2->setCellValue('H' . $kolom, $d->rupiah);
            $sheet2->setCellValue('I' . $kolom, $d->no_lot);
            $sheet2->setCellValue('J' . $kolom, $d->ket);
            $sheet2->setCellValue('K' . $kolom, $d->ket2);
            $sheet2->setCellValue('L' . $kolom, $d->rupiah * $d->gr);
            $sheet2->setCellValue('M' . $kolom, $d->lok_tgl);
            $kolom++;
        }

        $sheet2->getStyle('A2:M' . $kolom - 1)->applyFromArray($style);




        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(2);
        $sheet3 = $spreadsheet->getActiveSheet();
        $sheet3->setTitle('Produksi (ini nama herry)');

        $sheet3->getStyle("A1:M1")->applyFromArray($style_atas);
        $sheet3->setCellValue('A1', 'ID');
        $sheet3->setCellValue('B1', 'Buku');
        $sheet3->setCellValue('C1', 'Suplier Awal');
        $sheet3->setCellValue('D1', 'Date');
        $sheet3->setCellValue('E1', 'Grade');
        $sheet3->setCellValue('F1', 'Pcs');
        $sheet3->setCellValue('G1', 'Gram');
        $sheet3->setCellValue('H1', 'Rp/Gr');
        $sheet3->setCellValue('I1', 'Lot');
        $sheet3->setCellValue('J1', 'Keterangan/Nama Partai Herry');
        $sheet3->setCellValue('K1', 'Keterangan/Nama Partai Sinta');
        $sheet3->setCellValue('L1', 'Ttl Rp');
        $sheet3->setCellValue('M1', 'Lok');
        $sheet3->setCellValue('O2', 'kl mau import barang baru id kosongkan');
        $kolom = 2;
        $pembelian = GudangBkModel::export_getPembelianBk('produksi');
        foreach ($pembelian as $d) {
            $sheet3->setCellValue('A' . $kolom, $d->id_buku_campur);
            $sheet3->setCellValue('B' . $kolom, $d->buku);
            $sheet3->setCellValue('C' . $kolom, $d->suplier_awal);
            $sheet3->setCellValue('D' . $kolom, $d->tgl);
            $sheet3->setCellValue('E' . $kolom, $d->nm_grade);
            $sheet3->setCellValue('F' . $kolom, $d->pcs);
            $sheet3->setCellValue('G' . $kolom, $d->gr);
            $sheet3->setCellValue('H' . $kolom, $d->rupiah);
            $sheet3->setCellValue('I' . $kolom, $d->no_lot);
            $sheet3->setCellValue('J' . $kolom, $d->ket);
            $sheet3->setCellValue('K' . $kolom, $d->ket2);
            $sheet3->setCellValue('L' . $kolom, $d->rupiah * $d->gr);
            $sheet3->setCellValue('M' . $kolom, $d->lok_tgl);

            $kolom++;
        }

        $sheet3->getStyle('A2:M' . $kolom - 1)->applyFromArray($style);

        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(3);
        $sheet4 = $spreadsheet->getActiveSheet();
        $sheet4->setTitle('Reject');

        $sheet4->getStyle("A1:M1")->applyFromArray($style_atas);
        $sheet4->setCellValue('A1', 'ID');
        $sheet4->setCellValue('B1', 'Buku');
        $sheet4->setCellValue('C1', 'Suplier Awal');
        $sheet4->setCellValue('D1', 'Date');
        $sheet4->setCellValue('E1', 'Grade');
        $sheet4->setCellValue('F1', 'Pcs');
        $sheet4->setCellValue('G1', 'Gram');
        $sheet4->setCellValue('H1', 'Rp/Gr');
        $sheet4->setCellValue('I1', 'Lot');
        $sheet4->setCellValue('J1', 'Keterangan/Nama Partai Herry');
        $sheet4->setCellValue('K1', 'Keterangan/Nama Partai Sinta');
        $sheet4->setCellValue('L1', 'Ttl Rp');
        $sheet4->setCellValue('M1', 'Lok');

        $sheet4->setCellValue('O2', 'kl mau import barang baru id kosongkan');
        $kolom = 2;
        $pembelian = GudangBkModel::export_getPembelianBk('reject');
        foreach ($pembelian as $d) {
            $sheet4->setCellValue('A' . $kolom, $d->id_buku_campur);
            $sheet4->setCellValue('B' . $kolom, $d->buku);
            $sheet4->setCellValue('C' . $kolom, $d->suplier_awal);
            $sheet4->setCellValue('D' . $kolom, $d->tgl);
            $sheet4->setCellValue('E' . $kolom, $d->nm_grade);
            $sheet4->setCellValue('F' . $kolom, $d->pcs);
            $sheet4->setCellValue('G' . $kolom, $d->gr);
            $sheet4->setCellValue('H' . $kolom, $d->rupiah);
            $sheet4->setCellValue('I' . $kolom, $d->no_lot);
            $sheet4->setCellValue('J' . $kolom, $d->ket);
            $sheet4->setCellValue('K' . $kolom, $d->ket2);
            $sheet4->setCellValue('L' . $kolom, $d->rupiah * $d->gr);
            $sheet4->setCellValue('M' . $kolom, $d->lok_tgl);


            $kolom++;
        }

        $sheet4->getStyle('A2:L' . $kolom - 1)->applyFromArray($style);

        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(4);
        $sheet5 = $spreadsheet->getActiveSheet();
        $sheet5->setTitle('wip ( ini nama sinta)');

        $sheet5->getStyle("A1:M1")->applyFromArray($style_atas);
        $sheet5->setCellValue('A1', 'ID');
        $sheet5->setCellValue('B1', 'Buku');
        $sheet5->setCellValue('C1', 'Suplier Awal');
        $sheet5->setCellValue('D1', 'Date');
        $sheet5->setCellValue('E1', 'Grade');
        $sheet5->setCellValue('F1', 'Pcs');
        $sheet5->setCellValue('G1', 'Gram');
        $sheet5->setCellValue('H1', 'Rp/Gr');
        $sheet5->setCellValue('I1', 'Lot');
        $sheet5->setCellValue('J1', 'Keterangan/Nama Partai Herry');
        $sheet5->setCellValue('K1', 'Keterangan/Nama Partai Sinta');
        $sheet5->setCellValue('L1', 'Ttl Rp');
        $sheet5->setCellValue('M1', 'Lok');

        $sheet5->setCellValue('O2', 'kl mau import barang baru id kosongkan');
        $kolom = 2;
        $pembelian = GudangBkModel::export_getPembelianBk('wip');
        foreach ($pembelian as $d) {
            $sheet5->setCellValue('A' . $kolom, $d->id_buku_campur);
            $sheet5->setCellValue('B' . $kolom, $d->buku);
            $sheet5->setCellValue('C' . $kolom, $d->suplier_awal);
            $sheet5->setCellValue('D' . $kolom, $d->tgl);
            $sheet5->setCellValue('E' . $kolom, $d->nm_grade);
            $sheet5->setCellValue('F' . $kolom, $d->pcs);
            $sheet5->setCellValue('G' . $kolom, $d->gr);
            $sheet5->setCellValue('H' . $kolom, $d->rupiah);
            $sheet5->setCellValue('I' . $kolom, $d->no_lot);
            $sheet5->setCellValue('J' . $kolom, $d->ket);
            $sheet5->setCellValue('K' . $kolom, $d->ket2);
            $sheet5->setCellValue('L' . $kolom, $d->rupiah * $d->gr);
            $sheet5->setCellValue('M' . $kolom, $d->lok_tgl);

            $kolom++;
        }

        $sheet5->getStyle('A2:M' . $kolom - 1)->applyFromArray($style);


        $namafile = "Gudang Bahan baku.xlsx";

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
        $sheet1->setTitle('⁠produksi ( ini nama herry)');


        $sheet1->getStyle("A1:K1")->applyFromArray($style_atas);

        $sheet1->setCellValue('A1', 'ID');
        $sheet1->setCellValue('B1', 'Buku');
        $sheet1->setCellValue('C1', 'Suplier Awal');
        $sheet1->setCellValue('D1', 'Date');
        $sheet1->setCellValue('E1', 'Grade');
        $sheet1->setCellValue('F1', 'Pcs');
        $sheet1->setCellValue('G1', 'Gram');
        $sheet1->setCellValue('H1', 'Lot');
        $sheet1->setCellValue('I1', 'Keterangan/Nama Partai Herry');
        $sheet1->setCellValue('J1', 'Keterangan/Nama Partai Sinta');
        $sheet1->setCellValue('K1', 'Lok');
        $sheet1->setCellValue('O2', 'kl mau import barang baru id kosongkan');
        $kolom = 2;
        $pembelian = GudangBkModel::export_getPembelianBk('produksi');
        foreach ($pembelian as $d) {
            $sheet1->setCellValue('A' . $kolom, $d->id_buku_campur);
            $sheet1->setCellValue('B' . $kolom, $d->buku);
            $sheet1->setCellValue('C' . $kolom, $d->suplier_awal);
            $sheet1->setCellValue('D' . $kolom, $d->tgl);
            $sheet1->setCellValue('E' . $kolom, $d->nm_grade);
            $sheet1->setCellValue('F' . $kolom, $d->pcs);
            $sheet1->setCellValue('G' . $kolom, $d->gr);
            $sheet1->setCellValue('H' . $kolom, $d->no_lot);
            $sheet1->setCellValue('I' . $kolom, $d->ket);
            $sheet1->setCellValue('J' . $kolom, $d->ket2);
            $sheet1->setCellValue('K' . $kolom, $d->lok_tgl);

            $kolom++;
        }
        $sheet1->getStyle('A2:K' . $kolom - 1)->applyFromArray($style);


        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(1);
        $sheet2 = $spreadsheet->getActiveSheet();
        $sheet2->setTitle('wip ( ini nama sinta)');

        $sheet2->getStyle("A1:K1")->applyFromArray($style_atas);

        $sheet2->setCellValue('A1', 'ID');
        $sheet2->setCellValue('B1', 'Buku');
        $sheet2->setCellValue('C1', 'Suplier Awal');
        $sheet2->setCellValue('D1', 'Date');
        $sheet2->setCellValue('E1', 'Grade');
        $sheet2->setCellValue('F1', 'Pcs');
        $sheet2->setCellValue('G1', 'Gram');
        $sheet2->setCellValue('H1', 'Lot');
        $sheet2->setCellValue('I1', 'Keterangan/Nama Partai Herry');
        $sheet2->setCellValue('J1', 'Keterangan/Nama Partai Sinta');
        $sheet2->setCellValue('K1', 'Lok');
        $sheet2->setCellValue('O2', 'kl mau import barang baru id kosongkan');
        $kolom = 2;
        $pembelian = GudangBkModel::export_getPembelianBk('wip');
        foreach ($pembelian as $d) {
            $sheet2->setCellValue('A' . $kolom, $d->id_buku_campur);
            $sheet2->setCellValue('B' . $kolom, $d->buku);
            $sheet2->setCellValue('C' . $kolom, $d->suplier_awal);
            $sheet2->setCellValue('D' . $kolom, $d->tgl);
            $sheet2->setCellValue('E' . $kolom, $d->nm_grade);
            $sheet2->setCellValue('F' . $kolom, $d->pcs);
            $sheet2->setCellValue('G' . $kolom, $d->gr);
            $sheet2->setCellValue('H' . $kolom, $d->no_lot);
            $sheet2->setCellValue('I' . $kolom, $d->ket);
            $sheet2->setCellValue('J' . $kolom, $d->ket2);
            $sheet2->setCellValue('K' . $kolom, $d->lok_tgl);

            $kolom++;
        }
        $sheet2->getStyle('A2:K' . $kolom - 1)->applyFromArray($style);


        $namafile = "Gudang Produksi.xlsx";

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
        if (auth()->user()->posisi_id == 1) {
            $this->import_buku_bk($r);
        } else {
            $this->import_gudang_produksi($r);
        }

        // $this->import_gudang_produksi($r);

        return redirect()->route('gudangBk.index')->with('sukses', 'Data berhasil import');;
    }


    private function import_gudang_produksi(Request $r)
    {
        $hasError = false; // Variabel flag untuk melacak apakah ada kesalahan

        if ($r->hasFile('file')) {
            $file = $r->file('file');
            $filePath = $file->storeAs('temp', 'imported_file.xlsx');

            try {
                $spreadsheet = IOFactory::load(storage_path("app/{$filePath}"));
                $sheetNames = $spreadsheet->getSheetNames();

                foreach ($sheetNames as $sheetName) {
                    $currentSheet = $spreadsheet->getSheetByName($sheetName);
                    $title = $currentSheet->getTitle();

                    if ($title === 'Produksi (ini nama herry)') {
                        $gudang = 'produksi';
                    } elseif ($title === 'wip ( ini nama sinta)') {
                        $gudang = 'wip';
                    } else {
                        $hasError = true;
                    }

                    foreach ($currentSheet->getRowIterator() as $rowIndex => $row) {
                        if ($rowIndex === 1) {
                            continue;
                        }
                        $rowData = [];
                        $cellIterator = $row->getCellIterator();

                        foreach ($cellIterator as $cell) {
                            $rowData[] = $cell->getValue();
                        }

                        if (empty(array_filter(array_slice($rowData, 0, 13), 'strlen'))) {
                            continue;
                        }

                        try {
                            if (empty($rowData[0])) {
                                DB::table('buku_campur')->insert([
                                    'id_grade' => '1',
                                    'pcs' => $rowData[5],
                                    'gr' => $rowData[6],
                                    'no_lot' => $rowData[7],
                                    'ket' => empty($rowData[8]) ? ' ' : $rowData[8],
                                    'ket2' => empty($rowData[9]) ? ' ' : $rowData[9],
                                    'lok_tgl' => empty($rowData[10]) ? ' ' : $rowData[10],
                                    'approve' => 'Y',
                                    'gudang' => $gudang,

                                ]);
                                $idBukuCampur = DB::getPdo()->lastInsertId();

                                $tgl = $rowData[3];
                                if (is_numeric($tgl)) {
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
                                    'no_lot' => $rowData[7],
                                    'ket' => empty($rowData[8]) ? ' ' : $rowData[8],
                                    'ket2' => empty($rowData[9]) ? ' ' : $rowData[9],
                                    'lok_tgl' => empty($rowData[10]) ? ' ' : $rowData[10],
                                    'gudang' => $gudang,
                                ]);
                            } else {
                                $buku_campur = DB::table('buku_campur')->where('id_buku_campur', $rowData[0])->first();
                                DB::table('buku_campur')->where('id_buku_campur', $rowData[0])->update([
                                    'approve' => 'Y',
                                    'no_lot' => empty($buku_campur->no_nota) ? $rowData[7] : $buku_campur->no_lot,
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
                                        'no_lot' => empty($buku_campur->no_nota) ? $rowData[7] : $buku_campur->no_lot,
                                        'ket' => empty($rowData[8]) ? ' ' : $rowData[8],
                                        'ket2' => empty($rowData[9]) ? ' ' : $rowData[9],
                                        'lok_tgl' => empty($rowData[10]) ? ' ' : $rowData[10],
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
                                        'no_lot' => empty($buku_campur->no_nota) ? $rowData[7] : $buku_campur->no_lot,
                                        'ket' => empty($rowData[8]) ? ' ' : $rowData[8],
                                        'ket2' => empty($rowData[9]) ? ' ' : $rowData[9],
                                        'lok_tgl' => empty($rowData[10]) ? ' ' : $rowData[10],
                                        'gudang' => $gudang,
                                    ]);
                                }
                            }
                        } catch (\Exception $e) {
                            // Jika terjadi kesalahan, atur flag dan hentikan pembaruan
                            $hasError = true;
                            break 2; // Keluar dari kedua loop (sheet dan row)
                        }
                    }
                }
            } catch (\Exception $e) {
                echo 'Error loading spreadsheet: ' . $e->getMessage();
            }

            unlink(storage_path("app/{$filePath}"));

            // Jika ada kesalahan, batalkan semua pembaruan
            if ($hasError) {
                echo 'Pembaruan dibatalkan karena terjadi kesalahan.';
            }
        }
    }
    private function import_buku_bk(Request $r)
    {
        DB::table('buku_campur')->update(['gabung' => 'Y']);
        $hasError = false; // Variabel flag untuk melacak apakah ada kesalahan
        if ($r->hasFile('file')) {
            $file = $r->file('file');
            $filePath = $file->storeAs('temp', 'imported_file.xlsx');

            try {
                $spreadsheet = IOFactory::load(storage_path("app/{$filePath}"));
                $sheetNames = $spreadsheet->getSheetNames();

                foreach ($sheetNames as $sheetName) {
                    $currentSheet = $spreadsheet->getSheetByName($sheetName);
                    $title = $currentSheet->getTitle();

                    if ($title === 'baku dari bahan sdh grade') {
                        $gudang = 'bk';
                    } elseif ($title === 'Produksi (ini nama herry)') {
                        $gudang = 'produksi';
                    } elseif ($title === 'Reject') {
                        $gudang = 'reject';
                    } elseif ($title === 'wip ( ini nama sinta)') {
                        $gudang = 'wip';
                    } elseif ($title === '⁠penggabungan grd sama') {
                        $gudang = 'gabung';
                    } else {
                        $hasError = true;
                    }

                    foreach ($currentSheet->getRowIterator() as $rowIndex => $row) {
                        if ($rowIndex === 1) {
                            continue;
                        }
                        $rowData = [];
                        $cellIterator = $row->getCellIterator();

                        foreach ($cellIterator as $cell) {
                            $rowData[] = $cell->getValue();
                        }

                        if (empty(array_filter(array_slice($rowData, 0, 13), 'strlen'))) {
                            continue;
                        }

                        try {
                            if (empty($rowData[0])) {
                                DB::table('buku_campur')->insert([
                                    'no_lot' => $rowData[8],
                                    'id_grade' => '1',
                                    'pcs' => $rowData[5],
                                    'gr' => $rowData[6],
                                    'rupiah' => $rowData[7],
                                    'ket' => empty($rowData[9]) ? ' ' : $rowData[9],
                                    'ket2' => empty($rowData[10]) ? ' ' : $rowData[10],
                                    'lok_tgl' => empty($rowData[12]) ? ' ' : $rowData[12],
                                    'approve' => 'Y',
                                    'gudang' => $gudang,
                                    'gabung' => 'T'

                                ]);
                                $idBukuCampur = DB::getPdo()->lastInsertId();

                                $tgl = $rowData[3];
                                if (is_numeric($tgl)) {
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
                                    'rupiah' => $rowData[7],
                                    'no_lot' => $rowData[8],
                                    'ket' => empty($rowData[9]) ? ' ' : $rowData[9],
                                    'ket2' => empty($rowData[10]) ? ' ' : $rowData[10],
                                    'lok_tgl' => empty($rowData[12]) ? ' ' : $rowData[12],
                                    'gudang' => $gudang,
                                ]);
                            } else {
                                $buku_campur = DB::table('buku_campur')->where('id_buku_campur', $rowData[0])->first();
                                DB::table('buku_campur')->where('id_buku_campur', $rowData[0])->update([
                                    'approve' => 'Y',
                                    'no_lot' => empty($buku_campur->no_nota) ? $rowData[8] : $buku_campur->no_lot,
                                    'gudang' => $gudang,
                                    'gabung' => 'T'

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
                                        'rupiah' => $rowData[7],
                                        'no_lot' => empty($buku_campur->no_nota) ? $rowData[8] : $buku_campur->no_lot,
                                        'ket' => empty($rowData[9]) ? ' ' : $rowData[9],
                                        'ket2' => empty($rowData[10]) ? ' ' : $rowData[10],
                                        'lok_tgl' => empty($rowData[12]) ? ' ' : $rowData[12],
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
                                        'rupiah' => $rowData[7],
                                        'no_lot' => empty($buku_campur->no_nota) ? $rowData[8] : $buku_campur->no_lot,
                                        'ket' => empty($rowData[9]) ? ' ' : $rowData[9],
                                        'ket2' => empty($rowData[10]) ? ' ' : $rowData[10],
                                        'lok_tgl' => empty($rowData[12]) ? ' ' : $rowData[12],
                                        'gudang' => $gudang,
                                    ]);
                                }
                            }
                        } catch (\Exception $e) {
                            // Jika terjadi kesalahan, atur flag dan hentikan pembaruan
                            $hasError = true;
                            break 2; // Keluar dari kedua loop (sheet dan row)
                        }
                    }
                }
            } catch (\Exception $e) {
                echo 'Error loading spreadsheet: ' . $e->getMessage();
            }

            unlink(storage_path("app/{$filePath}"));

            // Jika ada kesalahan, batalkan semua pembaruan
            if ($hasError) {
                echo 'Pembaruan dibatalkan karena terjadi kesalahan.';
            }
        }
    }
}
