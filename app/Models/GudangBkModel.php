<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangBkModel extends Model
{
    use HasFactory;
    protected $table = 'buku_campur';

    public static function getPembelianBk($nmgudang)
    {
        return self::select('*')
            ->leftJoin('grade', 'grade.id_grade', '=', 'buku_campur.id_grade')
            ->leftJoin('grading', 'grading.no_nota', '=', 'buku_campur.no_nota')
            ->where('buku_campur.gudang', $nmgudang)
            ->get();
    }
}
