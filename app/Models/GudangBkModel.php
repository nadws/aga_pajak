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
        if(a.approve = 'T',a.rupiah,if(d.rupiah is null , a.rupiah,d.rupiah)) as rupiah,
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
        if(a.approve = 'T',a.rupiah, if(d.rupiah is null , a.rupiah,d.rupiah)) as rupiah,
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
        if(a.approve = 'T',a.no_produksi,d.no_produksi) as no_produksi,d.pcs_diambil,d.gr_diambil,
        d.selesai_1
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

    public static function getSummaryWip($kat)
    {
        if ($kat == 'data') {
            $result = DB::select("SELECT a.nm_grade,count(a.no_lot) as no_lot1, a.id_buku_campur, a.no_lot, a.ket,a.ket2, sum(a.pcs) as pcs, sum(a.gr) as gr, sum(a.rupiah * a.gr) as total_rp , a.selesai_1, a.selesai_2, a.ket2, c.pcs as pcs_susut, c.gr as gr_susut, c.selesai
            FROM buku_campur_approve as a 
            left join buku_campur as b on b.id_buku_campur = a.id_buku_campur
            left join table_susut as c on c.ket = a.ket2 and c.gudang = 'wip'
            WHERE a.gudang = 'wip' and b.gabung = 'T' and a.selesai_2 = 'T'
            GROUP by a.ket2
            order by a.ket2 ASC
            ");
        } else {
            $result = DB::select("SELECT a.nm_grade,count(a.no_lot) as no_lot1, a.id_buku_campur, a.no_lot, a.ket,a.ket2, sum(a.pcs) as pcs, sum(a.gr) as gr, sum(a.rupiah * a.gr) as total_rp , a.selesai_1, a.selesai_2, a.ket2, c.pcs as pcs_susut, c.gr as gr_susut, c.selesai
            FROM buku_campur_approve as a 
            left join buku_campur as b on b.id_buku_campur = a.id_buku_campur
            left join table_susut as c on c.ket = a.ket2 and c.gudang = 'wip'
            WHERE a.gudang = 'wip' and b.gabung = 'T' and a.selesai_2 = 'Y'
            GROUP by a.ket2
            order by a.ket2 ASC
            ");
        }



        return $result;
    }
    public static function getSummaryWipLot($ket)
    {
        $result = DB::select("SELECT a.id_buku_campur, a.no_lot, a.ket,a.ket2, sum(a.pcs) as pcs, sum(a.gr) as gr, sum(a.rupiah * a.gr) as total_rp , a.selesai_1, a.selesai_2
        FROM buku_campur_approve as a 
        left join buku_campur as b on b.id_buku_campur = a.id_buku_campur
        WHERE a.gudang = 'wip' and a.ket2 = ? and b.gabung = 'T'
        GROUP by a.ket2, a.no_lot;
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


    public static function getSummary($gudang, $kat)
    {
        if ($kat == 'data') {
            $result = DB::select("SELECT a.nm_grade,count(a.no_lot) as no_lot1, a.id_buku_campur, a.no_lot, a.ket,a.ket2, sum(a.pcs) as pcs, sum(a.gr) as gr, sum(a.rupiah * a.gr) as total_rp , a.selesai_1, a.selesai_2, a.ket2, c.pcs as pcs_susut, c.gr as gr_susut, c.selesai
            FROM buku_campur_approve as a 
            left join buku_campur as b on b.id_buku_campur = a.id_buku_campur
            left join table_susut as c on c.ket = a.ket2 and c.gudang = ?
            WHERE a.gudang = ? and b.gabung = 'T' and a.selesai_2 = 'T'
            GROUP by a.ket2
            order by a.ket2 ASC
            ", [$gudang, $gudang]);
        } else {
            $result = DB::select("SELECT a.nm_grade,count(a.no_lot) as no_lot1, a.id_buku_campur, a.no_lot, a.ket,a.ket2, sum(a.pcs) as pcs, sum(a.gr) as gr, sum(a.rupiah * a.gr) as total_rp , a.selesai_1, a.selesai_2, a.ket2, c.pcs as pcs_susut, c.gr as gr_susut, c.selesai
            FROM buku_campur_approve as a 
            left join buku_campur as b on b.id_buku_campur = a.id_buku_campur
            left join table_susut as c on c.ket = a.ket2 and c.gudang = ?
            WHERE a.gudang = ? and b.gabung = 'T' and a.selesai_2 = 'Y'
            GROUP by a.ket2
            order by a.ket2 ASC
            ", [$gudang, $gudang]);
        }

        return $result;
    }
    public static function getSummarypartai($gudang, $nm_partai)
    {
        $result = DB::select("SELECT a.nm_grade,count(a.no_lot) as no_lot1, a.id_buku_campur, a.no_lot, a.ket,a.ket2, sum(a.pcs) as pcs, sum(a.gr) as gr, sum(a.rupiah * a.gr) as total_rp , a.selesai_1, a.selesai_2, a.ket2, c.gr as gr_susut, c.selesai
        FROM buku_campur_approve as a 
        left join buku_campur as b on b.id_buku_campur = a.id_buku_campur
        left join table_susut as c on c.ket = a.ket2 and c.gudang = ?
        WHERE a.gudang = ? and b.gabung = 'T' and a.ket2 = ?
        GROUP by a.ket2
        order by a.ket2 ASC
        ", [$gudang, $gudang, $nm_partai]);

        return $result;
    }

    public static function getPartaicetak($nm_partai)
    {
        $result = DB::selectOne("SELECT a.nm_grade,count(a.no_lot) as no_lot1, a.id_buku_campur, a.no_lot, a.ket,a.ket2, sum(a.pcs) as pcs, sum(a.gr) as gr, sum(a.rupiah * a.gr) as total_rp , a.selesai_1, a.selesai_2, a.ket2, c.pcs as pcs_susut, c.gr as gr_susut, c.selesai
        FROM buku_campur_approve as a 
        left join buku_campur as b on b.id_buku_campur = a.id_buku_campur
        left join table_susut as c on c.ket = a.ket2 and c.gudang = 'wip'
        WHERE a.gudang = 'wip' and b.gabung = 'T' and a.selesai_2 = 'T' and a.ket2 = ?
        GROUP by a.ket2
        order by a.ket2 ASC", [$nm_partai]);

        return $result;
    }

    public static function getProduksiGabung()
    {
        $result = DB::select("SELECT a.nm_grade,count(a.no_lot) as no_lot1, a.id_buku_campur, a.no_lot, a.ket,a.ket2, sum(a.pcs) as pcs, sum(a.gr) as gr, sum(a.rupiah * a.gr) as total_rp , a.selesai_1, a.selesai_2, a.ket2, c.pcs as pcs_susut, c.gr as gr_susut, c.selesai
        FROM buku_campur_approve as a 
        left join buku_campur as b on b.id_buku_campur = a.id_buku_campur
        left join table_susut as c on c.ket = a.ket2 and c.gudang = 'wip'
        WHERE a.gudang = 'produksi' and b.gabung = 'T' and a.selesai_2 = 'T'
        GROUP by a.ket
        order by a.ket ASC;
        ");

        return $result;
    }

    public static function getSumWipCetak()
    {
        $result = DB::select("SELECT a.partai_h , a.grade, sum(a.pcs_cabut) as pcs_cabut, sum(a.gr_cabut) as gr_cabut, sum(a.ttl_rp) as ttl_rp, sum(a.cost_cabut) as cost_cabut, b.pcs as pcs_susut, b.gr as gr_susut, a.selesai2, b.selesai as selesai_1
        FROM gudang_ctk as a 
        left join table_susut as b on b.ket = a.partai_h and b.gudang = 'wipcetak'
        where a.gudang = 'cetak' and a.selesai = 'selesai'
        GROUP by a.partai_h
        Order by a.partai_h ASC
        ");

        return $result;
    }
}
