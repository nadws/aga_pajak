<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
