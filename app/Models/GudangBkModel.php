<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GudangBkModel extends Model
{
    use HasFactory;
    protected $table = 'buku_campur';

    public static function getPembelianBk($nmgudang)
    {

        $result = DB::select("SELECT 
        a.id_buku_campur, a.approve,
        if(a.approve = 'T',c.tgl,d.tgl) as tgl, 
        a.no_lot, 
        if(a.approve = 'T',b.nm_grade,d.nm_grade) as nm_grade, 
        if(a.approve = 'T',a.pcs,d.pcs) as pcs, 
        if(a.approve = 'T',a.gr,d.gr) as gr, 
        if(a.approve = 'T',a.rupiah,d.rupiah) as rupiah,
        if(a.approve = 'T',a.ket,d.ket) as ket,
        if(a.approve = 'T',a.lok_tgl,d.lok_tgl) as lok_tgl,
        if(a.approve = 'T',a.no_produksi,d.no_produksi) as no_produksi,d.pcs_diambil,d.gr_diambil
        FROM buku_campur as a
        left join grade as b on b.id_grade = a.id_grade
        left join grading as c on c.no_nota = a.no_nota
        left join buku_campur_approve as d on d.id_buku_campur = a.id_buku_campur
        where if(a.approve = 'T',a.gudang,d.gudang) = ? and a.gabung = 'T' ;
        ", [$nmgudang]);

        return $result;
    }
}
