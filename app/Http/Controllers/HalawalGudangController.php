<?php

namespace App\Http\Controllers;

use App\Models\GudangBkModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HalawalGudangController extends Controller
{
    public function index(Request $r)
    {
        $data = [
            'title' => 'Gudang Wip',
            'nm_gudang' => $r->nm_gudang
        ];
        return view('halawal.gudangwip', $data);
    }

    function summary_wip(Request $r)
    {
        $data = [
            'title' => 'Summary Wip',
            'nm_gudang' => $r->nm_gudang
        ];
        return view('halawal.summarywip', $data);
    }
    function susut(Request $r)
    {
        $data = [
            'title' => 'Summary Wip',
            'nm_gudang' => $r->nm_gudang
        ];
        return view('halawal.susut', $data);
    }
}
