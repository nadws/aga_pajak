<?php

namespace App\Http\Controllers;

use App\Models\GudangBkModel;
use Illuminate\Http\Request;

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
}
