<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrintNotaPajakController extends Controller
{
    public function index(Request $r)
    {
        $data = [
            'title' => 'Print Nota'
        ];
        return view('printnota.index', $data);
    }

    public function get_data(Request $r)
    {
        $data = [
            'bk' => DB::table('bkinpajak')
                ->where('id', 'LIKE', '%1')
                ->get()
        ];
    }
}
