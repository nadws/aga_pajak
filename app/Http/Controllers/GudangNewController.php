<?php

namespace App\Http\Controllers;

use App\Models\GudangBkModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;
use Illuminate\Support\Facades\Http;

class GudangNewController extends Controller
{
    protected $tgl1, $tgl2, $period, $linkApi;
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
        $this->linkApi = "https://sarang.ptagafood.com/api/apibk";
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
        return view('gudangnew.index', $data);
    }


    public function laporan_produksi(Request $r)
    {
        $gudang = GudangBkModel::getSummaryWip('data');
        $listBulan = DB::table('bulan')->get();
        $data =  [
            'title' => 'Laporan Produksi',
            'gudang' => $gudang,
            'listbulan' => $listBulan,
            'linkApi' => $this->linkApi,
        ];
        return view('laporan_produksi.index', $data);
    }

    public function gudang_p_kerja(Request $r)
    {
        $response = Http::get("https://sarang.ptagafood.com/api/apibk/bikin_box");
        $cabut = $response->object();
        $data =  [
            'title' => 'Gudang Partai Kerja',
            'cabut' => $cabut,
        ];
        return view('gudangnew.p_kerja', $data);
    }
}
