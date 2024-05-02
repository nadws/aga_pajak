<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrintNotaPajakController extends Controller
{
    public function index(Request $r)
    {
        $data = [
            'title' => 'Print Nota',
            'bk1' => DB::table('bkinpajak')->where('id_bkin', $r->id_bkin)->first(),
            'bk2' => DB::table('bkinpajak')->where('id_bkin', $r->id_bkin + 1)->first(),
        ];
        return view('printnota.index', $data);
    }

    public function get_data(Request $r)
    {
        $data = [
            'bk' => DB::table('bkinpajak')
                ->orderBy('nota_bk', 'DESC')
                ->get()
        ];
        return view('printnota.getdata', $data);
    }

    public function print_nota(Request $r)
    {
        $data = [
            'title' => 'Print Nota',
            'bk1' => DB::table('bkinpajak')->where('id_bkin', $r->id_bkin)->first(),
        ];
        return view('printnota.print_nota', $data);
    }
}
