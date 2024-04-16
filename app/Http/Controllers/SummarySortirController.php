<?php

namespace App\Http\Controllers;

use App\Models\GudangBkModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class SummarySortirController extends Controller
{
    protected $linkApi;
    public function __construct()
    {
        $this->linkApi = "https://sarang.ptagafood.com/api/apibk";
    }
    public function index(Request $r)
    {
        if (empty($r->nm_gudang)) {
            $nmgudang = 'bk';
        } else {
            $nmgudang = $r->nm_gudang;
        }
        if (empty($r->kategori)) {
            $kat = 'data';
            $view = 'summarybksortir.index';
        } else {
            $kat = 'history';
            $view = 'summarybksortir.index2';
        }
        $gudang = GudangBkModel::getSummary('wipsortir', $kat);

        $wipSortir = Http::get('https://sarang.ptagafood.com/api/apibk/wipSortir')->object();

        $data =  [
            'title' => 'Summary Wip Sortir',
            'gudang' => $gudang,
            'nm_gudang' => $nmgudang,
            'wipSortir' => $wipSortir,
            'linkApi' => $this->linkApi,
            'lokasi' => $r->lokasi,
        ];
        return view($view, $data);
    }

    public function cetak(Request $r)
    {
        if (empty($r->nm_gudang)) {
            $nmgudang = 'bk';
        } else {
            $nmgudang = $r->nm_gudang;
        }
        if (empty($r->kategori)) {
            $kat = 'data';
            $view = 'summarybksortir.cetak';
        } else {
            $kat = 'history';
            $view = 'summarybksortir.cetak2';
        }
        $gudang = GudangBkModel::getSumWipCetak();
        $data =  [
            'title' => 'Summary Wip Cetak',
            'gudang' => $gudang,
            'nm_gudang' => $nmgudang,
            'linkApi' => $this->linkApi,
        ];
        return view($view, $data);
    }
    public function cetak2(Request $r)
    {
        if (empty($r->nm_gudang)) {
            $nmgudang = 'bk';
        } else {
            $nmgudang = $r->nm_gudang;
        }

        if (empty($r->limit)) {
            $limit = '10';
        } else {
            $limit = $r->limit;
        }

        $perPage = $r->perpage;

        $search = $r->input('search');
        $wip = DB::table('gudang_ctk')->where('selesai', 'selesai')->where('gudang', 'cetak');
        $wip
            ->where(function ($query) use ($search) {
                $query->where('gudang_ctk.no_box', 'like', '%' . $search . '%')
                    ->orWhere('gudang_ctk.partai_h', 'like', '%' . $search . '%');
            });
        $wip_cetak = $wip->orderBy('gudang_ctk.no_box', 'ASC')->paginate('10');

        // $response = Http::get("https://sarang.ptagafood.com/api/apibk/cabut_selesai");
        // $cabut = $response->object();
        $data =  [
            'title' => 'Summary Wip Cetak',
            // 'cabut' => $cabut,
            'wip_cetak' => $wip_cetak,
            'nm_gudang' => $nmgudang,
            'linkApi' => $this->linkApi,
        ];
        return view('summarybksortir.cetak3', $data);
    }

    public function susut_wip_cabut(Request $r)
    {
        if (empty($r->nm_gudang)) {
            $nmgudang = 'bk';
        } else {
            $nmgudang = $r->nm_gudang;
        }
        if (empty($r->kategori)) {
            $kat = 'data';
        } else {
            $kat = 'history';
        }
        if ($nmgudang == 'wip') {
            $kategori = 'cabut';
            $gudang = GudangBkModel::getSummary($nmgudang, $kat);
            $view = 'summarybksortir.susut_wip_cabut';
        } elseif ($nmgudang == 'wipcetak') {
            $kategori = 'cetak';
            $gudang = GudangBkModel::getSumWipCetak();
            $view = 'summarybksortir.susut_wip_cetak';
        } else {
            $kategori = 'sortir';
            $gudang = GudangBkModel::getSummary($nmgudang, $kat);
            $view = 'summarybksortir.susut_wip_cabut';
        }




        $listBulan = DB::table('bulan')->get();
        $data =  [
            'title' => 'Susut  ' . $nmgudang,
            'gudang' => $gudang,
            'listbulan' => $listBulan,
            'nm_gudang' => $nmgudang,
            'kategori' => $kategori,
            'linkApi' => $this->linkApi,
        ];
        return view($view, $data);
    }

    function get_no_box_sortir(Request $r)
    {
        $response = Http::get(
            "$this->linkApi/show_box_sortir",
            [
                'nm_partai' => $r->nm_partai,
                'limit' => $r->limit,
            ]
        );
        $b = $response->object();
        $data =  [
            'bk' => $b,
            'linkApi' => $this->linkApi,
            'nm_partai' => $r->nm_partai,
        ];
        return view('summarybksortir.get_box_sortir', $data);
    }

    public function load_box_selesai(Request $r)
    {
        if (empty($r->lokasi)) {
            $lokasi = 'bk';
        } else {
            $lokasi = $r->lokasi;
        }

        if ($lokasi == 'wip') {
            $kategori = 'cabut';
        } elseif ($lokasi == 'wipcetak') {
            $kategori = 'cetak';
        } else {
            $kategori = 'sortir';
        }
        $gudang = GudangBkModel::getSummarypartai($lokasi, $r->nm_partai);
        $listBulan = DB::table('bulan')->get();
        $data =  [
            'gudang' => $gudang,
            'listbulan' => $listBulan,
            'lokasi' => $lokasi,
            'kategori' => $kategori,
            'linkApi' => $this->linkApi,
            'nm_gudang' => $r->nm_gudang
        ];
        return view('summarybksortir.selesai', $data);
    }

    public function save_selesai(Request $r)
    {
        if (empty($r->pindah)) {
            DB::table('buku_campur_approve')->where('ket2', $r->ket_sebelum)->update(['selesai_1' => 'Y']);
        } else {
            $buku = DB::table('buku_campur_approve')->where('ket2', $r->partai)->first();

            if (empty($buku->ket2)) {
                $data = [
                    'ket2' => $r->partai,
                    'pcs' => $r->pcs,
                    'gr' => $r->gr,
                    'gudang' => $r->lokasi,
                    'rupiah' => $r->rp_satuan,
                    'approve' => 'Y'
                ];
                $id = DB::table('buku_campur')->insertGetId($data);

                $data = [
                    'id_buku_campur' => $id,
                    'tgl' => date('Y-m-d'),
                    'nm_grade' => $r->grade,
                    'ket2' => $r->partai,
                    'pcs' => $r->pcs,
                    'gr' => $r->gr,
                    'gudang' => $r->lokasi,
                    'rupiah' => $r->rp_satuan,
                    'partai_dari' => $r->ket_sebelum
                ];
                DB::table('buku_campur_approve')->insert($data);

                DB::table('buku_campur_approve')->where('ket2', $r->ket_sebelum)->update(['selesai_1' => 'Y', 'partai_ke' => $r->partai]);
            }
        }
    }

    public function export_opname_cetak(Request $r)
    {
        $cetak = Http::get("https://sarang.ptagafood.com/api/apibk/cetak_detail_export");
        $dt_cetak = json_decode($cetak, TRUE);
        DB::table('cetak')->truncate();
        foreach ($dt_cetak as $v) {
            $data = [
                'id_cetak' => $v['id_cetak'],
                'no_box' => $v['no_box'],
                'tgl' => $v['tgl'],
                'tgl_serah' => $v['tgl_serah'],
                'id_kelas' => $v['id_kelas'],
                'id_anak' => $v['id_anak'],
                'pcs_awal' => $v['pcs_awal'],
                'gr_awal' => $v['gr_awal'],
                'pcs_awal_ctk' => $v['pcs_awal_ctk'],
                'gr_awal_ctk' => $v['gr_awal_ctk'],
                'pcs_akhir' => $v['pcs_akhir'],
                'gr_akhir' => $v['gr_akhir'],
                'grade' => $v['grade'],
                'target' => $v['target'],
                'rp_pcs' => $v['rp_pcs'],
                'pcs_tidak_ctk' => $v['pcs_tidak_ctk'],
                'gr_tidak_ctk' => $v['gr_tidak_ctk'],
                'pcs_hcr' => $v['pcs_hcr'],
                'pcs_cu' => $v['pcs_cu'],
                'gr_cu' => $v['gr_cu'],
                'id_pengawas' => $v['id_pengawas'],
                'selesai' => $v['selesai'],
                'bulan_dibayar' => $v['bulan_dibayar'],
                'status' => $v['status'],
                'penutup' => $v['penutup'],
                'rp_harian' => $v['rp_harian'],
            ];
            DB::table('cetak')->insert($data);
        }

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
        $sheet1->setTitle('wipcetak');


        $sheet1->getStyle("A1:Y1")->applyFromArray($style_atas);

        $sheet1->setCellValue('A1', 'no box');
        $sheet1->setCellValue('B1', 'tipe');
        $sheet1->setCellValue('C1', 'grade');
        $sheet1->setCellValue('D1', 'partai');
        $sheet1->setCellValue('E1', 'pcs bk');
        $sheet1->setCellValue('F1', 'gr bk');
        $sheet1->setCellValue('G1', 'cost bk');

        $sheet1->setCellValue('H1', 'pcs timbang ulang');
        $sheet1->setCellValue('I1', 'gr timbang ulang');
        $sheet1->setCellValue('J1', 'cost timbang ulang');

        $sheet1->setCellValue('K1', 'pcs sst');
        $sheet1->setCellValue('L1', 'gr sst');
        $sheet1->setCellValue('M1', 'sst %');

        $sheet1->setCellValue('N1', 'pcs awal box');
        $sheet1->setCellValue('O1', 'gr awal box');
        $sheet1->setCellValue('P1', 'pcs tdk ctk');
        $sheet1->setCellValue('Q1', 'gr tdk ctk');
        $sheet1->setCellValue('R1', 'pcs awal ctk');
        $sheet1->setCellValue('S1', 'gr awal ctk');
        $sheet1->setCellValue('T1', 'pcs cu');
        $sheet1->setCellValue('U1', 'gr cu');
        $sheet1->setCellValue('V1', 'pcs ctk akhir');
        $sheet1->setCellValue('W1', 'gr ctk akhir');
        $sheet1->setCellValue('X1', 'sst ctk');
        $sheet1->setCellValue('Y1', 'rp ctk');

        $cetak =  GudangBkModel::export_cetak();
        $kolom = 2;

        foreach ($cetak as $c) {
            $sheet1->setCellValue('A' . $kolom, $c->no_box);
            $sheet1->setCellValue('B' . $kolom, $c->tipe);
            $sheet1->setCellValue('C' . $kolom, $c->grade);
            $sheet1->setCellValue('D' . $kolom, $c->partai_h);
            $sheet1->setCellValue('E' . $kolom, $c->pcs_cabut);
            $sheet1->setCellValue('F' . $kolom, $c->gr_cabut);
            $sheet1->setCellValue('G' . $kolom, $c->ttl_rp + $c->cost_cabut);

            $sheet1->setCellValue('H' . $kolom, $c->pcs_timbang_ulang);
            $sheet1->setCellValue('I' . $kolom, $c->gr_timbang_ulang);
            $sheet1->setCellValue('J' . $kolom, $c->ttl_rp + $c->cost_cabut);

            $sheet1->setCellValue('K' . $kolom, $c->pcs_cabut - $c->pcs_timbang_ulang);
            $sheet1->setCellValue('L' . $kolom, $c->gr_cabut - $c->gr_timbang_ulang);
            $sheet1->setCellValue('M' . $kolom, round((1 - ($c->gr_timbang_ulang / $c->gr_cabut)) * 100, 0) . ' %');

            $sheet1->setCellValue('N' . $kolom, $c->pcs_awal);
            $sheet1->setCellValue('O' . $kolom, $c->gr_awal);
            $sheet1->setCellValue('P' . $kolom, $c->pcs_tidak_ctk);
            $sheet1->setCellValue('Q' . $kolom, $c->gr_tidak_ctk);
            $sheet1->setCellValue('R' . $kolom, $c->pcs_awal_ctk);
            $sheet1->setCellValue('S' . $kolom, $c->gr_awal_ctk);
            $sheet1->setCellValue('T' . $kolom, $c->pcs_cu);
            $sheet1->setCellValue('U' . $kolom, $c->gr_cu);
            $sheet1->setCellValue('V' . $kolom, $c->pcs_akhir);
            $sheet1->setCellValue('W' . $kolom, $c->gr_akhir);
            $sheet1->setCellValue('X' . $kolom, empty($c->gr_akhir) ? '0%' : round((1 - (($c->gr_akhir + $c->gr_cu) / $c->gr_awal_ctk)) * 100, 0) . '%');
            $sheet1->setCellValue('Y' . $kolom, $c->rp_ctk);


            $kolom++;
        }


        $sheet1->getStyle('A2:Y' . $kolom - 1)->applyFromArray($style);
        $namafile = "Wip Cetak.xlsx";

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $namafile);
        header('Cache-Control: max-age=0');


        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
    }
}
