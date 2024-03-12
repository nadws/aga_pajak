<?php

namespace App\Http\Controllers;

use App\Models\GudangBkModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ApiWipController extends Controller
{


    function get_sum_wip(Request $r)
    {
        $no_lot = $r->no_lot;
        $ket = $r->ket;
        $gudang =  GudangBkModel::getSummaryWip_pcs($no_lot, $ket);
        $response = [
            'status' => 'success',
            'message' => 'Data Sarang berhasil diambil',
            'data' => [
                'gudang' => $gudang
            ],
        ];
        return response()->json($response);
    }

    public function bkCetakApi(Request $r)
    {
        $result = DB::table('gudang_ctk')->where('selesai', 'selesai')->where('gudang', 'cetak')->get();
        return $result;
    }
    public function bkSortirApi(Request $r)
    {
        $result = DB::table('gudang_ctk')->where('selesai', 'selesai')->where('gudang', 'sortir')->get();
        return $result;
    }

    public function sumWip()
    {
        $response = DB::selectOne("SELECT 
        a.nm_grade as tipe,
                sum(a.pcs) as pcs,
                 sum(a.gr) as gr,
                 sum(a.rupiah * a.gr) as total_rp ,
                 sum(c.pcs) as pcs_susut,
                 sum(c.gr) as gr_susut,
                 SUM(a.rupiah * a.gr) / (SUM(a.gr) - SUM(c.gr)) AS harga_modal_satuan
                FROM buku_campur_approve as a 
                left join buku_campur as b on b.id_buku_campur = a.id_buku_campur
                left join table_susut as c on c.ket = a.ket2 and c.gudang = 'wip'
                WHERE a.gudang = 'wip' and b.gabung = 'T' and a.selesai_2 = 'T'
                order by a.nm_grade ASC");

        return response()->json($response);
    }
    public function detailSumWip()
    {
        $response = DB::select("SELECT 
        a.nm_grade as tipe,
        c.ket as nm_partai,
                a.pcs as pcs,
                 a.gr as gr,
                 a.rupiah * a.gr as total_rp ,
                 c.pcs as pcs_susut,
                 c.gr as gr_susut,
                 a.rupiah * a.gr / (a.gr - c.gr) AS harga_modal_satuan
                FROM buku_campur_approve as a 
                left join buku_campur as b on b.id_buku_campur = a.id_buku_campur
                left join table_susut as c on c.ket = a.ket2 and c.gudang = 'wip'
                WHERE a.gudang = 'wip' and b.gabung = 'T' and a.selesai_2 = 'T'");
        return response()->json($response);
    }
    public function bkCbtAwal()
    {
        $que = DB::select("SELECT a.nm_grade,count(a.no_lot) as no_lot1, a.id_buku_campur, a.no_lot, a.ket,a.ket2, sum(a.pcs) as pcs, sum(a.gr) as gr, sum(a.rupiah * a.gr) as total_rp , a.selesai_1, a.selesai_2, a.ket2, c.pcs as pcs_susut, c.gr as gr_susut, c.selesai
        FROM buku_campur_approve as a 
        left join buku_campur as b on b.id_buku_campur = a.id_buku_campur
        left join table_susut as c on c.ket = a.ket2 and c.gudang = 'wip'
        WHERE a.gudang = 'wip' and b.gabung = 'T' and a.selesai_2 = 'T'
        GROUP by a.ket2
        order by a.nm_grade ASC");

        $linkApi = "https://sarang.ptagafood.com/api/apibk";

        $pcs = 0;
        $gr = 0;
        $ttl_rp = 0;

        $pcs_susut = 0;
        $gr_susut = 0;

        $pcs_sisa = 0;
        $gr_sisa = 0;
        $ttl_rp_sisa = 0;
        $arr = [];
        foreach ($que as $d) {
            $api = Http::get("$linkApi/datacabutsum2", ['nm_partai' => $d->ket2])->object();
            dd($api);
            $wipGr = $d->gr ?? 0;
            $grAwalBk = $api->gr_awal_bk ?? 0;
            $pcsAwalBk = $api->pcs_bk ?? 0;
            $modal_satuan = $d->total_rp / ($wipGr - $d->gr_susut);
            $modal = $d->selesai == 'Y' ? $grAwalBk * $modal_satuan : '0';
            $WipSisaGr = $wipGr - $grAwalBk - $d->gr_susut;
            $ttlrpSisa = empty($d->gr) ? 0 : $modal_satuan * $WipSisaGr;
            $arr[] = [
                'partai' => $api->nm_partai,
                'pcsAwalBk' => $pcsAwalBk,
                'grAwalBk' => $grAwalBk,
            ];
            $pcs += $pcsAwalBk;
            $gr += $grAwalBk;
            $ttl_rp += $modal;

            $pcs_susut += $d->pcs_susut;
            $gr_susut += $d->gr_susut;

            $pcs_sisa += $d->pcs - ($pcsAwalBk + $d->pcs_susut);
            $gr_sisa += $wipGr - ($grAwalBk + $d->gr_susut);
            $ttl_rp_sisa += $ttlrpSisa;
        }
        return response()->json($arr);
        return response()->json([
            'pcs' => $pcs,
            'gr' => $gr,
            'ttl_rp' => $ttl_rp,

            'pcs_susut' => $pcs_susut,
            'gr_susut' => $gr_susut,

            'pcs_sisa' => $pcs_sisa,
            'gr_sisa' => $gr_sisa,
            'ttl_rp_sisa' => $ttl_rp_sisa,
        ]);
    }

    public function bkCbtAwalygAdaTipe()
    {
        $que = DB::select("SELECT a.nm_grade,count(a.no_lot) as no_lot1, a.id_buku_campur, a.no_lot, a.ket,a.ket2, sum(a.pcs) as pcs, sum(a.gr) as gr, sum(a.rupiah * a.gr) as total_rp , a.selesai_1, a.selesai_2, a.ket2, c.pcs as pcs_susut, c.gr as gr_susut, c.selesai
        FROM buku_campur_approve as a 
        left join buku_campur as b on b.id_buku_campur = a.id_buku_campur
        left join table_susut as c on c.ket = a.ket2 and c.gudang = 'wip'
        WHERE a.gudang = 'wip' and b.gabung = 'T' and a.selesai_2 = 'T'
        GROUP by a.ket2
        order by a.nm_grade ASC");

        $linkApi = "https://sarang.ptagafood.com/api/apibk";
        $arrBaru = [];
        $totals = [];

        $pcs = 0;
        $gr = 0;
        $ttl_rp = 0;

        $pcs_sisa = 0;
        $gr_sisa = 0;
        $ttl_rp_sisa = 0;
        foreach ($que as $d) {
            $api = Http::get("$linkApi/datacabutsum2", ['nm_partai' => $d->ket2])->object();

            $wipGr = $d->gr ?? 0;
            $grAwalBk = $api->gr_awal_bk ?? 0;
            $pcsAwalBk = $api->pcs_bk ?? 0;
            $modal_satuan = $d->total_rp / ($wipGr - $d->gr_susut);
            $modal = $d->selesai == 'Y' ? $grAwalBk * $modal_satuan : '0';
            $WipSisaGr = $wipGr - $grAwalBk - $d->gr_susut;
            $ttlrpSisa = empty($d->gr) ? 0 : $modal_satuan * $WipSisaGr;

            // if (isset($totals[$d->nm_grade])) {

            //     // Jika sudah ada, tambahkan nilai pcs dan gr
            //     $totals[$d->nm_grade]['pcs'] += $pcsAwalBk + $d->pcs_susut;
            //     $totals[$d->nm_grade]['gr'] += $grAwalBk + $d->gr_susut;
            //     $totals[$d->nm_grade]['ttl_rp'] += $modal;


            //     $totals[$d->nm_grade]['pcs_sisa'] += $d->pcs - ($pcsAwalBk + $d->pcs_susut);
            //     $totals[$d->nm_grade]['gr_sisa'] += $wipGr - ($grAwalBk + $d->gr_susut);
            //     $totals[$d->nm_grade]['ttl_rp_sisa'] += $ttlrpSisa;
            // } else {
            //     // Jika belum ada, tambahkan nama grade ke array $totals
            //     $totals[$d->nm_grade] = [
            //         'pcs' => $pcsAwalBk + $d->pcs_susut,
            //         'gr' => $grAwalBk + $d->gr_susut,
            //         'ttl_rp' => $modal,

            //         'pcs_sisa' => $d->pcs - ($pcsAwalBk + $d->pcs_susut),
            //         'gr_sisa' => $wipGr - ($grAwalBk + $d->gr_susut),
            //         'ttl_rp_sisa' => $ttlrpSisa
            //     ];
            // }
            $pcs += $pcsAwalBk + $d->pcs_susut;
            $gr += $grAwalBk + $d->gr_susut;
            $ttl_rp += $modal;

            $pcs_sisa += $d->pcs - ($pcsAwalBk + $d->pcs_susut);
            $gr_sisa += $wipGr - ($grAwalBk + $d->gr_susut);
            $ttl_rp_sisa += $ttlrpSisa;
        }

        // foreach ($totals as $grade => $total) {
        //     $arrBaru[] = [
        //         'tipe' => $grade,
        //         'pcs' => $total['pcs'],
        //         'gr' => $total['gr'],
        //         'ttl_rp' => $total['ttl_rp'],


        //         'pcs_sisa' => $total['pcs_sisa'],
        //         'gr_sisa' => $total['gr_sisa'],
        //         'ttl_rp_sisa' => $total['ttl_rp_sisa']
        //     ];
        // }

        return response()->json([
            'pcs' => $pcs,
            'gr' => $gr,
            'ttl_rp' => $ttl_rp,

            'pcs_sisa' => $pcs_sisa,
            'gr_sisa' => $gr_sisa,
            'ttl_rp_sisa' => $ttl_rp_sisa,
        ]);
    }

    public function sumCtk()
    {
        $r = DB::selectOne("SELECT sum(pcs_cabut) as pcs,
         sum(gr_cabut) as gr,
         sum(ttl_rp) as ttl_rp,
         sum(cost_cabut) as cost_cabut
         FROM gudang_ctk WHERE selesai = 'selesai'");

        return response()->json($r);
    }
}
