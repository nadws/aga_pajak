<?php

namespace App\Http\Controllers;

use App\Models\GudangBkModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

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
}
