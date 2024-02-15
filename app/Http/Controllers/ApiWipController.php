<?php

namespace App\Http\Controllers;

use App\Models\GudangBkModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiWipController extends Controller
{
    function bk_pilih(Request $r)
    {
        $gudang = GudangBkModel::getSummaryWip();
        $response = [
            'status' => 'success',
            'message' => 'Data Sarang berhasil diambil',
            'data' => [
                'gudang' => $gudang
            ],
        ];
        return response()->json($response);
    }

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
        $result = DB::table('gudang_ctk')->where('gr_timbang_ulang', '!=', '0')->get();
        return $result;
    }
}
