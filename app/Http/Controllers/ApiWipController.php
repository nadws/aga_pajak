<?php

namespace App\Http\Controllers;

use App\Models\GudangBkModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $result = DB::table('gudang_ctk')->where('selesai', 'selesai')->get();
        return $result;
    }

    public function sumWip()
    {
        $response = DB::selectOne("SELECT 
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
        order by a.ket2 ASC;");
        return response()->json($response);
    }
}
