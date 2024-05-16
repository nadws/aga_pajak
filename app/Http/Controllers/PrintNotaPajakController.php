<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrintNotaPajakController extends Controller
{
    protected $tgl1, $tgl2, $period;
    public function __construct(Request $r)
    {
        if (empty($r->period)) {
            $this->tgl1 = date('Y-m-01');
            $this->tgl2 = date('Y-m-t');
        } elseif ($r->period == 'daily') {
            $this->tgl1 = date('Y-m-d');
            $this->tgl2 = date('Y-m-d');
        } elseif ($r->period == 'weekly') {
            $this->tgl1 = date('Y-m-d', strtotime("-6 days"));
            $this->tgl2 = date('Y-m-d');
        } elseif ($r->period == 'mounthly') {
            $bulan = $r->bulan;
            $tahun = $r->tahun;
            $tglawal = "$tahun" . "-" . "$bulan" . "-" . "01";
            $tglakhir = "$tahun" . "-" . "$bulan" . "-" . "01";

            $this->tgl1 = date('Y-m-01', strtotime($tglawal));
            $this->tgl2 = date('Y-m-t', strtotime($tglakhir));
        } elseif ($r->period == 'costume') {
            $this->tgl1 = $r->tgl1;
            $this->tgl2 = $r->tgl2;
        } elseif ($r->period == 'years') {
            $tahun = $r->tahunfilter;
            $tgl_awal = "$tahun" . "-" . "01" . "-" . "01";
            $tgl_akhir = "$tahun" . "-" . "12" . "-" . "01";

            $this->tgl1 = date('Y-m-01', strtotime($tgl_awal));
            $this->tgl2 = date('Y-m-t', strtotime($tgl_akhir));
        }
    }
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
                ->whereRaw('id_bkin % 2 <> 0')
                ->orderBy('nota_bk', 'ASC')
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
