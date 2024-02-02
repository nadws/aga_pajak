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
        $gudang = GudangBkModel::getSummary('wipsortir');
        $data =  [
            'title' => 'Summary Wip Sortir',
            'gudang' => $gudang,
            'nm_gudang' => $nmgudang,
            'linkApi' => $this->linkApi,
        ];
        return view('summarybksortir.index', $data);
    }

    public function cetak(Request $r)
    {
        if (empty($r->nm_gudang)) {
            $nmgudang = 'bk';
        } else {
            $nmgudang = $r->nm_gudang;
        }
        $gudang = GudangBkModel::getSummary('wipcetak');
        $data =  [
            'title' => 'Summary Wip Cetak',
            'gudang' => $gudang,
            'nm_gudang' => $nmgudang,
            'linkApi' => $this->linkApi,
        ];
        return view('summarybksortir.cetak', $data);
    }

    public function susut_wip_cabut(Request $r)
    {
        if (empty($r->nm_gudang)) {
            $nmgudang = 'bk';
        } else {
            $nmgudang = $r->nm_gudang;
        }

        if ($nmgudang == 'wip') {
            $kategori = 'cabut';
        } elseif ($nmgudang == 'wipcetak') {
            $kategori = 'cetak';
        } else {
            $kategori = 'sortir';
        }


        $gudang = GudangBkModel::getSummary($nmgudang);
        $listBulan = DB::table('bulan')->get();
        $data =  [
            'title' => 'Susut  ' . $nmgudang,
            'gudang' => $gudang,
            'listbulan' => $listBulan,
            'nm_gudang' => $nmgudang,
            'kategori' => $kategori,
            'linkApi' => $this->linkApi,
        ];
        return view('summarybksortir.susut_wip_cabut', $data);
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
}
