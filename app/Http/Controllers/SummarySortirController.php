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


        $sheet1->getStyle("A1:L1")->applyFromArray($style_atas);

        $sheet1->setCellValue('A1', 'ID');
        $sheet1->setCellValue('B1', 'Partai h');
        $sheet1->setCellValue('C1', 'No Box');
        $sheet1->setCellValue('D1', 'Tipe');
        $sheet1->setCellValue('E1', 'Grade');
        $sheet1->setCellValue('F1', 'Pcs sdh cabut');
        $sheet1->setCellValue('G1', 'Gr sdh cabut');
        $sheet1->setCellValue('H1', 'Ttl Rp');
        $sheet1->setCellValue('I1', 'Cost Cabut');
        $sheet1->setCellValue('J1', 'Pcs timbang ulang');
        $sheet1->setCellValue('K1', 'Gr timbang ulang');
        $sheet1->setCellValue('L1', 'Selesai');
        $kolom = 2;
        $response = Http::get("https://sarang.ptagafood.com/api/apibk/cabut_selesai");
        $cabut = $response->object();

        $wip_cetak = DB::table('gudang_ctk')->where('selesai', 'selesai')->get();

        foreach ($cabut as $d) {
            $bk = GudangBkModel::getPartaicetak($d->nm_partai);
            $gdng_ctk = DB::table('gudang_ctk')->where('no_box', $d->no_box)->where('selesai', 'selesai')->first();

            if (empty($gdng_ctk->selesai)) {
            } else {
                continue;
            }
            $sheet1->setCellValue('A' . $kolom, $gdng_ctk->id_gudang_ctk ?? '');
            $sheet1->setCellValue('B' . $kolom, $d->nm_partai);
            $sheet1->setCellValue('C' . $kolom, $d->no_box);
            $sheet1->setCellValue('D' . $kolom, $d->tipe);
            $sheet1->setCellValue('E' . $kolom, $bk->nm_grade ?? '');
            $sheet1->setCellValue('F' . $kolom, $d->pcs_akhir);
            $sheet1->setCellValue('G' . $kolom, $d->gr_akhir);
            $sheet1->setCellValue('H' . $kolom, empty($bk->total_rp) ? '0' : ($bk->total_rp / $bk->gr) * $d->gr_akhir);
            $sheet1->setCellValue('I' . $kolom, $d->ttl_rp);
            $sheet1->setCellValue('J' . $kolom, $gdng_ctk->pcs_timbang_ulang ?? 0);
            $sheet1->setCellValue('K' . $kolom, $gdng_ctk->gr_timbang_ulang ?? 0);
            $sheet1->setCellValue('L' . $kolom, 'proses');

            $kolom++;
        }
        foreach ($wip_cetak as $c) {
            $bk = GudangBkModel::getPartaicetak($c->partai_h);
            $ttl_rp = $bk->total_rp ?? 0;
            $gr = $bk->gr ?? 0;
            $sheet1->setCellValue('A' . $kolom, $c->id_gudang_ctk);
            $sheet1->setCellValue('B' . $kolom, $c->partai_h);
            $sheet1->setCellValue('C' . $kolom, $c->no_box);
            $sheet1->setCellValue('D' . $kolom, $c->tipe);
            $sheet1->setCellValue('E' . $kolom, $c->grade);
            $sheet1->setCellValue('F' . $kolom, $c->pcs_cabut);
            $sheet1->setCellValue('G' . $kolom, $c->gr_cabut);
            $sheet1->setCellValue('H' . $kolom, $c->ttl_rp == 0 ? ($ttl_rp == 0 ? 0 : ($ttl_rp / $gr) * $c->gr_cabut) : 0);
            $sheet1->setCellValue('I' . $kolom, $c->cost_cabut);
            $sheet1->setCellValue('J' . $kolom, $c->pcs_timbang_ulang ?? 0);
            $sheet1->setCellValue('K' . $kolom, $c->gr_timbang_ulang ?? 0);
            $sheet1->setCellValue('L' . $kolom, $c->selesai);

            $kolom++;
        }
        $sheet1->getStyle('A2:L' . $kolom - 1)->applyFromArray($style);
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
