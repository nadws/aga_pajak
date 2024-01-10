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
        a.no_lot,  a.gudang, a.gabung,
        if(a.approve = 'T',b.nm_grade,d.nm_grade) as nm_grade, 
        if(a.approve = 'T',c.no_campur,d.buku) as buku, 
        if(a.approve = 'T',f.nm_suplier,d.suplier_awal) as suplier_awal, 
        if(a.approve = 'T',a.pcs,d.pcs) as pcs, 
        if(a.approve = 'T',a.gr,d.gr) as gr, 
        if(a.approve = 'T',a.rupiah,d.rupiah) as rupiah,
        if(a.approve = 'T',a.ket,d.ket) as ket,
        if(a.approve = 'T',a.ket2,d.ket2) as ket2,
        if(a.approve = 'T',a.lok_tgl,d.lok_tgl) as lok_tgl,
        if(a.approve = 'T',a.no_produksi,d.no_produksi) as no_produksi,d.pcs_diambil,d.gr_diambil
        FROM buku_campur as a
        left join grade as b on b.id_grade = a.id_grade
        left join grading as c on c.no_nota = a.no_nota
        left join buku_campur_approve as d on d.id_buku_campur = a.id_buku_campur
        left join invoice_bk as e on e.no_nota = a.no_nota
        left join tb_suplier as f on f.id_suplier = e.id_suplier
        where if(a.approve = 'T',a.gudang,d.gudang) = ? and a.gabung = 'T' ;
        ", [$nmgudang]);

        return $result;
    }
    public static function export_getPembelianBk($gudang)
    {

        $result = DB::select("SELECT 
        a.id_buku_campur, a.approve,
        if(a.approve = 'T',c.tgl,d.tgl) as tgl, 
        a.no_lot,  a.gudang, a.gabung,
        if(a.approve = 'T',b.nm_grade,d.nm_grade) as nm_grade, 
        if(a.approve = 'T',c.no_campur,d.buku) as buku, 
        if(a.approve = 'T',f.nm_suplier,d.suplier_awal) as suplier_awal, 
        if(a.approve = 'T',a.pcs,d.pcs) as pcs, 
        if(a.approve = 'T',a.gr,d.gr) as gr, 
        if(a.approve = 'T',a.rupiah,d.rupiah) as rupiah,
        if(a.approve = 'T',a.ket,d.ket) as ket,
        if(a.approve = 'T',a.ket2,d.ket2) as ket2,
        if(a.approve = 'T',a.lok_tgl,d.lok_tgl) as lok_tgl,
        if(a.approve = 'T',a.no_produksi,d.no_produksi) as no_produksi,d.pcs_diambil,d.gr_diambil
        FROM buku_campur as a
        left join grade as b on b.id_grade = a.id_grade
        left join grading as c on c.no_nota = a.no_nota
        left join buku_campur_approve as d on d.id_buku_campur = a.id_buku_campur
        left join invoice_bk as e on e.no_nota = a.no_nota
        left join tb_suplier as f on f.id_suplier = e.id_suplier
        where if(a.approve = 'T',a.gudang,d.gudang) = ? and a.gabung = 'T' ;
        ", [$gudang]);
        return $result;
    }
    public static function getPembelianBkExport($id_buku_campur)
    {
        $result = DB::selectOne("SELECT 
        a.id_buku_campur, a.approve,
        if(a.approve = 'T',c.tgl,d.tgl) as tgl, 
        a.no_lot,  a.gudang, a.gabung,
        if(a.approve = 'T',b.nm_grade,d.nm_grade) as nm_grade, 
        if(a.approve = 'T',c.no_campur,d.buku) as buku, 
        if(a.approve = 'T',f.nm_suplier,d.suplier_awal) as suplier_awal, 
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
        left join invoice_bk as e on e.no_nota = a.no_nota
        left join tb_suplier as f on f.id_suplier = e.id_suplier
        where a.id_buku_campur = ?;
        ", [$id_buku_campur]);

        return $result;
    }
    public static function getPembelianBkExportnota($no_nota)
    {
        $result = DB::select("SELECT 
        a.id_buku_campur, a.approve,
        if(a.approve = 'T',c.tgl,d.tgl) as tgl, 
        a.no_lot,  a.gudang, a.gabung,
        if(a.approve = 'T',b.nm_grade,d.nm_grade) as nm_grade, 
        if(a.approve = 'T',c.no_campur,d.buku) as buku, 
        if(a.approve = 'T',f.nm_suplier,d.suplier_awal) as suplier_awal, 
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
        left join invoice_bk as e on e.no_nota = a.no_nota
        left join tb_suplier as f on f.id_suplier = e.id_suplier
        where a.no_nota = ? 
        order by a.no_nota ASC, b.urutan ASC;
        ", [$no_nota]);

        return $result;
    }

    public static function getSummaryWip()
    {
        $result = DB::select("SELECT a.nm_grade,count(a.no_lot) as no_lot1, a.id_buku_campur, a.no_lot, a.ket, sum(a.pcs) as pcs, sum(a.gr) as gr, sum(a.rupiah * a.gr) as total_rp , a.selesai_1, a.selesai_2, a.ket2
        FROM buku_campur_approve as a 
        left join buku_campur as b on b.id_buku_campur = a.id_buku_campur
        WHERE a.gudang = 'wip' and b.gabung = 'T'
        GROUP by a.ket2
        order by a.ket2 ASC
        ");

        return $result;
    }
    public static function getSummaryWipLot($ket)
    {
        $result = DB::select("SELECT a.id_buku_campur, a.no_lot, a.ket, sum(a.pcs) as pcs, sum(a.gr) as gr, sum(a.rupiah * a.gr) as total_rp , a.selesai_1, a.selesai_2
        FROM buku_campur_approve as a 
        left join buku_campur as b on b.id_buku_campur = a.id_buku_campur
        WHERE a.gudang = 'wip' and a.ket = ? and b.gabung = 'T'
        GROUP by a.ket, a.no_lot;
        ", [$ket]);

        return $result;
    }
    public static function getSummaryWipLotexport()
    {
        $result = DB::select("SELECT a.id_buku_campur, a.no_lot, a.ket, sum(a.pcs) as pcs, sum(a.gr) as gr, sum(a.rupiah * a.gr) as total_rp , a.selesai_1, a.selesai_2
        FROM buku_campur_approve as a
        left join buku_campur as b on b.id_buku_campur = a.id_buku_campur
        WHERE a.gudang = 'wip' and b.gabung = 'T'
        GROUP by a.ket, a.no_lot
        order by a.ket ASC
        ");
        return $result;
    }
    public static function getSummaryWip_pcs($no_lot, $ket)
    {
        $result = DB::select("SELECT a.id_buku_campur, a.no_lot, a.ket, sum(a.pcs) as pcs, sum(a.gr) as gr, sum(a.rupiah * a.gr) as total_rp , a.selesai_1, a.selesai_2
        FROM buku_campur_approve as a 
        WHERE a.gudang = 'wip' and a.no_lot = ? and a.ket = ?
        GROUP by a.no_lot, a.ket;
        ", [$no_lot, $ket]);

        return $result;
    }
}
