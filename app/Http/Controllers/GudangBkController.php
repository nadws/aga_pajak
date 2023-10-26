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

    function export_gudang_bk(Request $r)
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
        $sheet1->getStyle('A1:J1')->applyFromArray($style_atas);

        $sheet1->setCellValue('A1', 'ID');
        $sheet1->setCellValue('B1', 'No Nota');
        $sheet1->setCellValue('C1', 'No Lot');
        $sheet1->setCellValue('D1', 'Grade');
        $sheet1->setCellValue('E1', 'Pcs');
        $sheet1->setCellValue('F1', 'Gram');
        $sheet1->setCellValue('G1', 'Rupiah');
        $sheet1->setCellValue('H1', 'Gudang BK');
        $sheet1->setCellValue('I1', 'Gudang Produksi');
        $sheet1->setCellValue('J1', 'Gudang Reject');

        $kolom = 2;
        for ($x = 0; $x < count($r->id_buku_campur); $x++) {
            $id_buku_campur = $r->id_buku_campur[$x];
            $pembelian = DB::selectOne("SELECT *
            FROM buku_campur as a
            left join grade as b on b.id_grade = a.id_grade
            where a.id_buku_campur = '$id_buku_campur'
            group by a.id_buku_campur
            ");

            $sheet1->setCellValue('A' . $kolom, $pembelian->id_buku_campur);
            $sheet1->setCellValue('B' . $kolom, $pembelian->no_nota);
            $sheet1->setCellValue('C' . $kolom, $pembelian->no_lot);
            $sheet1->setCellValue('D' . $kolom, $pembelian->nm_grade);
            $sheet1->setCellValue('E' . $kolom, $pembelian->pcs);
            $sheet1->setCellValue('F' . $kolom, $pembelian->gr);
            $sheet1->setCellValue('G' . $kolom, $pembelian->rupiah);
            $sheet1->setCellValue('H' . $kolom, $pembelian->gudang == 'bk' ? 'Y' : 'T');
            $sheet1->setCellValue('I' . $kolom, $pembelian->gudang == 'produksi' ? 'Y' : 'T');
            $sheet1->setCellValue('J' . $kolom, $pembelian->gudang == 'reject' ? 'Y' : 'T');
            $kolom++;
        }
        $sheet1->getStyle('A2:J' . $kolom - 1)->applyFromArray($style);
        $namafile = "Gudang Bk.xlsx";

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $namafile);
        header('Cache-Control: max-age=0');


        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
    }

    function import_gudang_bk(Request $r)
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
                    if (($rowData[7] == 'Y' && $rowData[8] == 'Y') ||
                        ($rowData[7] == 'Y' && $rowData[9] == 'Y') ||
                        ($rowData[9] == 'Y' && $rowData[8] == 'Y') ||
                        ($rowData[7] == 'Y' && $rowData[8] == 'Y' && $rowData[9] == 'Y')
                    ) {
                        $importGagal = true;
                        break;
                    }

                    if ($rowData[7] == 'Y') {
                        $gudang = 'bk';
                    } elseif ($rowData[8] == 'Y') {
                        $gudang = 'produksi';
                    } elseif ($rowData[9] == 'Y') {
                        $gudang = 'reject';
                    }

                    DB::table('buku_campur')->where('id_buku_campur', $rowData[0])->update([
                        'gudang' => $gudang,
                    ]);
                }

                if ($importGagal) {
                    DB::rollback(); // Batalkan transaksi jika ada kesalahan
                    return redirect()->route('gudangBk.index')->with('error', 'Data tidak valid: Kolom H, I, dan J tidak boleh memiliki nilai Y yang sama');
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
