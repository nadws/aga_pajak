<?php

use App\Http\Controllers\AktivaController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\BukuBesarController;
use App\Http\Controllers\BukuCampurController;
use App\Http\Controllers\CashflowController;
use App\Http\Controllers\ConganController;
use App\Http\Controllers\ControlflowController;
use App\Http\Controllers\CrudPermissionController;
use App\Http\Controllers\FakturPenjualanController;
use App\Http\Controllers\GudangBjController;
use App\Http\Controllers\GudangBkController;
use App\Http\Controllers\GudangCetakController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\GudangGradingController;
use App\Http\Controllers\GudangNewController;
use App\Http\Controllers\HalawalGudangController;
use App\Http\Controllers\JurnalController;
use App\Http\Controllers\JurnalPenyesuaianController;
use App\Http\Controllers\NavbarController;
use App\Http\Controllers\NeracaController;
use App\Http\Controllers\OpnamemtdController;
use App\Http\Controllers\PembayaranBkController;
use App\Http\Controllers\PembelianBahanBakuController;
use App\Http\Controllers\Penjualan_martadah_alpaController;
use App\Http\Controllers\Penjualan_umum_cekController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\Penyetoran_telurController;
use App\Http\Controllers\PiutangtelurController;
use App\Http\Controllers\PrintNotaPajakController;
use App\Http\Controllers\Produk_telurController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfitController;
use App\Http\Controllers\StokMasukController;
use App\Http\Controllers\ProyekController;
use App\Http\Controllers\Saldo;
use App\Http\Controllers\SaldoController;
use App\Http\Controllers\Stock_telurController;
use App\Http\Controllers\Stok_telur_alpaController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\SummarySortirController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('login');
});

Route::get('/template1', function () {
    return view('template-notable');
})->name('template1');
Route::get('/template2', function () {
    return view('template-table');
})->name('template2');




Route::get('/dashboard', function () {
    return view('dashboard', ['title' => 'Administrator']);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // 
    Route::controller(NavbarController::class)->group(function () {
        Route::get('/data_master', 'data_master')->name('data_master');
        Route::get('/buku_besar', 'buku_besar')->name('buku_besar');
        Route::get('/penjualan', 'penjualan')->name('penjualan');
        Route::get('/pembelian', 'pembelian')->name('pembelian');
        Route::get('/pembayaran', 'pembayaran')->name('pembayaran');
        Route::get('/persediaan_barang', 'persediaan_barang')->name('persediaan_barang');
        Route::get('/asset', 'asset')->name('asset');
        Route::get('/penjualan_umum', 'penjualan_umum')->name('penjualan_umum');
        Route::get('/testing', 'testing')->name('testing');
        Route::get('/penjualan_agl', 'penjualan_agl')->name('penjualan_agl');
        Route::get('/kandang', 'kandang')->name('kandang');
        Route::get('/gudang_new', 'gudang_new')->name('gudang_new');
        Route::get('/kelompok_gudang', 'kelompok_gudang')->name('kelompok_gudang');
        Route::get('/kelompok_laporan', 'kelompok_laporan')->name('kelompok_laporan');
    });


    Route::controller(JurnalController::class)->group(function () {
        Route::get('/jurnal', 'index')->name('jurnal');
        Route::post('/jurnal-update', 'update')->name('jurnal.update');
        Route::get('/jurnal-delete', 'delete')->name('jurnal-delete');
        Route::get('/jurnal-add', 'add')->name('jurnal.add');
        Route::get('/load_menu', 'load_menu')->name('load_menu');
        Route::get('/tambah_baris_jurnal', 'tambah_baris_jurnal')->name('tambah_baris_jurnal');
        Route::get('/export_jurnal', 'export')->name('export_jurnal');
        Route::post('/save_jurnal', 'save_jurnal')->name('save_jurnal');
        Route::get('/edit_jurnal', 'edit')->name('edit_jurnal');
        Route::post('/edit_jurnal', 'edit_save')->name('edit_jurnal');
        Route::get('/detail_jurnal', 'detail_jurnal')->name('detail_jurnal');
        Route::post('/import_jurnal', 'import_jurnal')->name('import_jurnal');
        Route::get('/saldo_akun', 'saldo_akun')->name('saldo_akun');
        Route::get('/get_post', 'get_post')->name('get_post');
    });

    Route::controller(AkunController::class)->group(function () {
        Route::get('/akun', 'index')->name('akun');
        Route::post('/akun', 'create')->name('akun');
        Route::post('/akun-update', 'update')->name('akun.update');
        Route::get('/akun-delete', 'delete')->name('akun.delete');
        Route::get('/akun-sub', 'add_sub')->name('akun.add_sub');
        Route::get('/remove_sub', 'remove_sub')->name('akun.remove_sub');
        Route::get('/get_kode', 'get_kode')->name('get_kode');
        Route::get('/export_akun', 'export_akun')->name('export_akun');
        Route::post('/importAkun', 'importAkun')->name('importAkun');
        Route::get('/get_edit_akun/{id_akun}', 'get_edit_akun')->name('get_edit_akun');
        Route::get('/load_sub_akun/{id_akun}', 'load_sub_akun')->name('load_sub_akun');
    });
    Route::controller(SaldoController::class)->group(function () {
        Route::get('/saldo_awal', 'index')->name('saldo_awal');
        Route::get('/saveSaldo', 'saveSaldo')->name('saveSaldo');
    });



    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    Route::controller(ProdukController::class)
        ->prefix('produk')
        ->name('produk.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'create')->name('create');
            Route::get('/delete', 'delete')->name('delete');
            Route::post('/edit', 'edit')->name('edit');
            Route::get('/{gudang_id}', 'index')->name('detail');
            Route::get('/edit/{id_produk}', 'edit_load')->name('edit_load');
        });

    Route::controller(StokMasukController::class)
        ->prefix('stok_masuk')
        ->name('stok_masuk.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/add', 'add')->name('add');
            Route::post('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/load', 'load_menu')->name('load_menu');
            Route::get('/tbh_baris', 'tbh_baris')->name('tbh_baris');
            Route::get('/get_stok_sebelumnya', 'get_stok_sebelumnya')->name('get_stok_sebelumnya');
            Route::get('/cetak', 'cetak')->name('cetak');
            Route::get('/{gudang_id}', 'index')->name('detail');
            Route::get('/delete/{no_nota}', 'delete')->name('delete');
            Route::get('/edit/{no_nota}', 'edit')->name('edit_load');
            Route::post('/edit', 'update')->name('edit');
        });



    Route::controller(GudangController::class)
        ->prefix('gudang')
        ->name('gudang.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'create')->name('create');
            Route::get('/edit/{id_gudang}', 'edit_load')->name('edit_load');
            Route::post('/edit', 'edit')->name('edit');
            Route::get('/delete/{id_gudang}', 'delete')->name('delete');
        });

    Route::controller(CrudPermissionController::class)
        ->prefix('permis')
        ->name('permis.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'create')->name('create');
            Route::get('/edit/{id_permis}', 'edit')->name('edit');
        });
    Route::controller(BukuBesarController::class)
        ->prefix('summary_buku_besar')
        ->name('summary_buku_besar.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/detail', 'detail')->name('detail');
            Route::get('/export_detail', 'export_detail')->name('export_detail');
        });

    Route::controller(ProyekController::class)->group(function () {
        Route::get('/proyek', 'index')->name('proyek');
        Route::post('/proyek', 'add')->name('proyek');
        Route::get('/proyek_delete', 'delete')->name('proyek_delete');
        Route::get('/proyek_selesai', 'proyek_selesai')->name('proyek_selesai');
        Route::get('/get_proyek_selesai', 'get_proyek_selesai')->name('get_proyek_selesai');
    });
    Route::controller(FakturPenjualanController::class)->group(function () {
        Route::get('/faktur_penjualan', 'index')->name('faktur_penjualan');
    });

    Route::controller(ProfitController::class)->group(function () {
        Route::get('/profit', 'index')->name('profit');
        Route::get('/profit_print', 'print')->name('profit_print');
    });

    Route::controller(AktivaController::class)->group(function () {
        Route::get('/aktiva', 'index')->name('aktiva');
        Route::get('/aktiva.add', 'add')->name('aktiva.add');
        Route::get('/load_aktiva', 'load_aktiva')->name('load_aktiva');
        Route::get('/tambah_baris_aktiva', 'tambah_baris_aktiva')->name('tambah_baris_aktiva');
        Route::get('/get_data_kelompok', 'get_data_kelompok')->name('get_data_kelompok');
        Route::post('/save_aktiva', 'save_aktiva')->name('save_aktiva');
        Route::get('/print_aktiva', 'print')->name('print_aktiva');
    });

    Route::controller(JurnalPenyesuaianController::class)->group(function () {
        Route::get('/jurnal_penyesuaian', 'index')->name('jurnal_penyesuaian');
        Route::get('/jurnal_aktiva', 'jurnal')->name('jurnal_aktiva');
        Route::post('/save_penyesuaian_aktiva', 'save_penyesuaian_aktiva')->name('save_penyesuaian_aktiva');
    });
    Route::controller(PembelianBahanBakuController::class)->group(function () {
        Route::get('/pembelian_bk', 'index')->name('pembelian_bk');
        Route::get('/pembelian_bk.add', 'add')->name('pembelian_bk.add');
        Route::get('/get_satuan_produk', 'get_satuan_produk')->name('get_satuan_produk');
        Route::get('/tambah_baris_bk', 'tambah_baris_bk')->name('tambah_baris_bk');
        Route::post('/save_pembelian_bk', 'save_pembelian_bk')->name('save_pembelian_bk');
        Route::get('/print_bk', 'print')->name('print_bk');
        Route::get('/delete_bk', 'delete_bk')->name('delete_bk');
        Route::get('/edit_pembelian_bk', 'edit_pembelian_bk')->name('edit_pembelian_bk');
        Route::post('/edit_pembelian_bk', 'edit_save')->name('edit_pembelian_bk');
        Route::post('/grading', 'grading')->name('grading');
        Route::post('/approve_invoice_bk', 'approve_invoice_bk')->name('approve_invoice_bk');
        Route::get('/get_grading', 'get_grading')->name('get_grading');
        Route::get('/get_grading2', 'get_grading2')->name('get_grading2');
        Route::get('/nota_invoice_bk', 'nota_invoice_bk')->name('nota_invoice_bk');
        Route::get('/export_bk', 'export_bk')->name('export_bk');
        Route::get('/export_bk_m', 'export_bk_m')->name('export_bk_m');
        Route::get('/delete_tipe_grade', 'delete_tipe_grade')->name('delete_tipe_grade');
        Route::get('/load_grade', 'load_grade')->name('load_grade');
        Route::get('/save_grade', 'save_grade')->name('save_grade');
        Route::get('/add_new_bk', 'add_new')->name('add_new_bk');
        Route::get('/get_print', 'get_print')->name('get_print');
    });

    Route::controller(BukuCampurController::class)->group(function () {
        Route::get('/buku_campur', 'index')->name('buku_campur');
        Route::get('/export_buku_campur', 'export_buku_campur')->name('export_buku_campur');
        Route::post('/import_buku_campur', 'import_buku_campur')->name('import_buku_campur');
    });

    Route::controller(PembayaranBkController::class)->group(function () {
        Route::get('/pembayaranbk', 'index')->name('pembayaranbk');
        Route::get('/pembayaranbk.add', 'add')->name('pembayaranbk.add');
        Route::get('/pembayaranbk.tambah', 'tambah')->name('pembayaranbk.tambah');
        Route::post('/pembayaranbk.save_pembayaran', 'save_pembayaran')->name('pembayaranbk.save_pembayaran');
        Route::get('/pembayaranbk.edit', 'edit')->name('pembayaranbk.edit');
        Route::post('/pembayaranbk.save_edit', 'save_edit')->name('pembayaranbk.save_edit');
        Route::get('/get_kreditBK', 'get_kreditBK')->name('get_kreditBK');
        Route::get('/exportBayarbk', 'exportBayarbk')->name('exportBayarbk');
    });
    Route::controller(ControlflowController::class)->group(function () {
        Route::get('/controlflow', 'index')->name('controlflow');
        Route::get('/loadcontrolflow', 'loadcontrolflow')->name('loadcontrolflow');
        Route::get('/loadInputAkunCashflow', 'loadInputAkunCashflow')->name('loadInputAkunCashflow');
        Route::get('/save_kategoriCashcontrol', 'save_kategoriCashcontrol')->name('save_kategoriCashcontrol');
        Route::get('/edit_kategoriCashcontrol', 'edit_kategoriCashcontrol')->name('edit_kategoriCashcontrol');
        Route::get('/loadInputsub', 'loadInputsub')->name('loadInputsub');
        Route::get('/SaveSubAkunCashflow', 'SaveSubAkunCashflow')->name('SaveSubAkunCashflow');
        Route::get('/deleteSubAkunCashflow', 'deleteSubAkunCashflow')->name('deleteSubAkunCashflow');
        Route::get('/deleteAkunCashflow', 'deleteAkunCashflow')->name('deleteAkunCashflow');
        Route::get('/view_akun', 'view_akun')->name('view_akun');
        Route::get('/print_cashflow', 'print')->name('print_cashflow');
    });

    Route::controller(CashflowController::class)->group(function () {
        Route::get('/cashflow_ibu', 'index')->name('cashflow_ibu');
        Route::get('/loadInputKontrol', 'loadInputKontrol')->name('loadInputKontrol');
        Route::get('/save_akun_ibu', 'save_akun_ibu')->name('save_akun_ibu');
        Route::get('/delete_akun_ibu', 'delete_akun_ibu')->name('delete_akun_ibu');
        Route::get('/delete_akun_ibu', 'delete_akun_ibu')->name('delete_akun_ibu');
        Route::get('/edit_akun_ibu', 'edit_akun_ibu')->name('edit_akun_ibu');
    });
    Route::controller(NeracaController::class)->group(function () {
        Route::get('/neraca', 'index')->name('neraca');
        Route::get('/loadNeraca', 'loadneraca')->name('loadNeraca');
        Route::get('/loadinputSub_neraca', 'loadinputSub_neraca')->name('loadinputSub_neraca');
        Route::get('/view_akun_neraca', 'view_akun_neraca')->name('view_akun_neraca');
        Route::get('/saveSub_neraca', 'saveSub_neraca')->name('saveSub_neraca');
        Route::get('/loadinputAkun_neraca', 'loadinputAkun_neraca')->name('loadinputAkun_neraca');
        Route::get('/saveAkunNeraca', 'saveAkunNeraca')->name('saveAkunNeraca');
        Route::get('/delete_akun_neraca', 'delete_akun_neraca')->name('delete_akun_neraca');
        Route::get('/akun_neraca', 'akun_neraca')->name('akun_neraca');
        Route::get('/print_neraca', 'print_neraca')->name('print_neraca');
    });

    Route::controller(PenjualanController::class)->group(function () {
        Route::get('/penjualan_agrilaras', 'index')->name('penjualan_agrilaras');
        Route::get('/tbh_invoice_telur', 'tbh_invoice_telur')->name('tbh_invoice_telur');
        Route::get('/loadkginvoice', 'loadkginvoice')->name('loadkginvoice');
        Route::get('/tambah_baris_kg', 'tambah_baris_kg')->name('tambah_baris_kg');
        Route::get('/tbh_pembayaran', 'tbh_pembayaran')->name('tbh_pembayaran');
        Route::post('/save_penjualan_telur', 'save_penjualan_telur')->name('save_penjualan_telur');
        Route::get('/detail_invoice_telur', 'detail_invoice_telur')->name('detail_invoice_telur');
        Route::get('/loadpcsinvoice', 'loadpcsinvoice')->name('loadpcsinvoice');
        Route::get('/tambah_baris_pcs', 'tambah_baris_pcs')->name('tambah_baris_pcs');
        Route::get('/edit_invoice_telur', 'edit_invoice_telur')->name('edit_invoice_telur');
        Route::get('/loadkginvoiceedit', 'loadkginvoiceedit')->name('loadkginvoiceedit');
        Route::post('/edit_penjualan_telur', 'edit_penjualan_telur')->name('edit_penjualan_telur');
        Route::get('/delete_invoice_telur', 'delete_invoice_telur')->name('delete_invoice_telur');
        Route::get('/loadpcsinvoiceedit', 'loadpcsinvoiceedit')->name('loadpcsinvoiceedit');
    });

    Route::controller(Stock_telurController::class)->group(function () {
        Route::get('/stok_telur', 'index')->name('stok_telur');
        Route::get('/tbh_stok_telur', 'tbh_stok_telur')->name('tbh_stok_telur');
        Route::get('/load_menu_telur', 'load_menu_telur')->name('load_menu_telur');
        Route::get('/tbh_baris_telur', 'tbh_baris_telur')->name('tbh_baris_telur');
        Route::post('/save_stok_telur', 'save_stok_telur')->name('save_stok_telur');
        Route::get('/transfer_stok_telur', 'transfer_stok_telur')->name('transfer_stok_telur');
        Route::get('/load_transfer_telur', 'load_transfer_telur')->name('load_transfer_telur');
        Route::get('/tbh_baris_transfer', 'tbh_baris_transfer')->name('tbh_baris_transfer');
        Route::post('/save_transfer_stok_telur', 'save_transfer_stok_telur')->name('save_transfer_stok_telur');
        Route::get('/get_stok_telur', 'get_stok')->name('get_stok_telur');
        Route::get('/edit_telur', 'edit_telur')->name('edit_telur');
        Route::post('/save_edit_stok_telur', 'save_edit_stok_telur')->name('save_edit_stok_telur');
        Route::get('/delete_telur', 'delete_telur')->name('delete_telur');
    });
    Route::controller(Stok_telur_alpaController::class)->group(function () {
        Route::get('/stok_telur_alpa', 'index')->name('stok_telur_alpa');
        Route::get('/detail_stok_telur_alpa', 'detail_stok_telur_alpa')->name('detail_stok_telur_alpa');
        Route::get('/delete_transfer', 'delete_transfer')->name('delete_transfer');
    });

    Route::controller(PiutangtelurController::class)->group(function () {
        Route::get('/piutang_telur', 'index')->name('piutang_telur');
        Route::get('/bayar_piutang_telur', 'bayar_piutang_telur')->name('bayar_piutang_telur');
        Route::post('/save_bayar_piutang', 'save_bayar_piutang')->name('save_bayar_piutang');
        Route::get('/get_pembayaranpiutang_telur', 'get_pembayaranpiutang_telur')->name('get_pembayaranpiutang_telur');
        Route::get('/edit_pembayaran_piutang_telur', 'edit_piutang')->name('edit_pembayaran_piutang_telur');
        Route::post('/edit_bayar_piutang', 'edit_bayar_piutang')->name('edit_bayar_piutang');
    });
    Route::controller(Penyetoran_telurController::class)->group(function () {
        Route::get('/penyetoran_telur', 'index')->name('penyetoran_telur');
        Route::get('/perencanaan_setor_telur', 'perencanaan_setor_telur')->name('perencanaan_setor_telur');
        Route::post('/save_perencanaan_telur', 'save_perencanaan_telur')->name('save_perencanaan_telur');
        Route::get('/get_list_perencanaan', 'get_list_perencanaan')->name('get_list_perencanaan');
        Route::get('/get_perencanaan', 'get_perencanaan')->name('get_perencanaan');
        Route::post('/save_setoran', 'save_setoran')->name('save_setoran');
        Route::get('/print_setoran', 'print_setoran')->name('print_setoran');
        Route::get('/delete_perencanaan', 'delete_perencanaan')->name('delete_perencanaan');
        Route::get('/get_history_perencanaan', 'get_history_perencanaan')->name('get_history_perencanaan');
    });

    Route::controller(Produk_telurController::class)->group(function () {
        Route::get('/produk_telur', 'index')->name('produk_telur');
        Route::get('/CheckMartadah', 'CheckMartadah')->name('CheckMartadah');
        Route::get('/CheckAlpa', 'CheckAlpa')->name('CheckAlpa');
        Route::get('/HistoryMtd', 'HistoryMtd')->name('HistoryMtd');
        Route::get('/edit_telur_dashboard', 'edit_telur_dashboard')->name('edit_telur_dashboard');
        Route::get('/export_telur', 'export')->name('export_telur');
        Route::get('/terima_invoice_mtd', 'terima_invoice_mtd')->name('terima_invoice_mtd');
        Route::post('/save_terima_invoice', 'save_terima_invoice')->name('save_terima_invoice');
        // History Alpa
        Route::get('/HistoryAlpa', 'HistoryAlpa')->name('HistoryAlpa');
    });
    Route::controller(Penjualan_martadah_alpaController::class)->group(function () {
        Route::get('/penjualan_martadah_cek', 'index')->name('penjualan_martadah_cek');
    });
    Route::controller(Penjualan_umum_cekController::class)->group(function () {
        Route::get('/penjualan_umum_cek', 'index')->name('penjualan_umum_cek');
        Route::get('/terima_invoice_umum_cek', 'terima_invoice_umum_cek')->name('terima_invoice_umum_cek');
        Route::post('/save_cek_umum_invoice', 'save_cek_umum_invoice')->name('save_cek_umum_invoice');
    });

    Route::controller(OpnamemtdController::class)->group(function () {
        Route::get('/opnamemtd', 'index')->name('opnamemtd');
        Route::get('/bayar_opname', 'bayar_opname')->name('bayar_opname');
        Route::post('/save_opname_telur_mtd', 'save_opname_telur_mtd')->name('save_opname_telur_mtd');
        Route::post('/save_bayar_opname', 'save_bayar_opname')->name('save_bayar_opname');
        Route::get('/bukukan_opname_martadah', 'bukukan_opname_martadah')->name('bukukan_opname_martadah');
        Route::get('/terima_opname', 'terima_opname')->name('terima_opname');
        Route::get('/history_opname_mtd', 'history_opname_mtd')->name('history_opname_mtd');
    });

    Route::controller(GudangBkController::class)
        ->prefix('gudangBk')
        ->name('gudangBk.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/wip', 'wip')->name('wip');
            Route::get('/gudangProduksiGabung', 'gudangProduksiGabung')->name('gudangProduksiGabung');
            Route::post('/export_buku_campur_bk', 'export_buku_campur_bk')->name('export_buku_campur_bk');
            Route::post('/import_buku_campur_bk', 'import_buku_campur_bk')->name('import_buku_campur_bk');
            Route::post('/import_gudang_produksi_new', 'import_gudang_produksi_new')->name('import_gudang_produksi_new');
            Route::post('/import_summary_wip', 'import_summary_wip')->name('import_summary_wip');
            Route::post('/export_wip_cetak', 'export_wip_cetak')->name('export_wip_cetak');
            Route::post('/import_wip_cetak', 'import_wip_cetak')->name('import_wip_cetak');
            Route::post('/save_bj_baru', 'save_bj_baru')->name('save_bj_baru');
            Route::post('/pindah_gudang', 'pindah_gudang')->name('pindah_gudang');

            Route::post('/export_gudang_produksi', 'export_gudang_produksi')->name('export_gudang_produksi');
        });
    Route::controller(SummaryController::class)
        ->prefix('summarybk')
        ->name('summarybk.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/export_summary', 'export_summary')->name('export_summary');
            Route::get('/import_summary_bk', 'import_summary_bk')->name('import_summary_bk');
            Route::get('/selesai1', 'selesai1')->name('selesai1');
            Route::get('/selesai2', 'selesai2')->name('selesai2');

            Route::get('/get_no_lot', 'get_no_lot')->name('get_no_lot');
            Route::get('/get_no_box', 'get_no_box')->name('get_no_box');
            Route::get('/export_summary_lot', 'export_summary_lot')->name('export_summary_lot');
            Route::get('/susut', 'susut')->name('susut');
            Route::post('/save_susut', 'save_susut')->name('save_susut');
            Route::get('/export_show_box', 'export_show_box')->name('export_show_box');
            Route::get('/selesai_susut', 'selesai_susut')->name('selesai_susut');
            Route::get('/cancel_susut', 'cancel_susut')->name('cancel_susut');
            Route::get('/sum_bagi', 'sum_bagi')->name('sum_bagi');
            Route::get('/selesai_partai', 'selesai_partai')->name('selesai_partai');
        });
    Route::controller(ConganController::class)
        ->prefix('congan')
        ->name('congan.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/add_congan', 'add_congan')->name('add_congan');
            Route::get('/load_row', 'load_row')->name('load_row');
            Route::get('/detail_nota', 'detail_nota')->name('detail_nota');
            Route::post('/edit_congan', 'edit_congan')->name('edit_congan');
            Route::get('/buat_nota', 'buat_nota')->name('buat_nota');
            Route::post('/save_pembelian_bk', 'save_pembelian_bk')->name('save_pembelian_bk');
            Route::get('/export', 'export')->name('export');
            Route::get('/delete_nota', 'delete_nota')->name('delete_nota');
        });
    Route::controller(HalawalGudangController::class)
        ->prefix('halawal')
        ->name('halawal.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/summary_wip', 'summary_wip')->name('summary_wip');
            Route::get('/susut', 'susut')->name('susut');
            Route::get('/load_row_cetak', 'load_row_cetak')->name('load_row_cetak');
        });
    Route::controller(SummarySortirController::class)
        ->prefix('sumsortir')
        ->name('sumsortir.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/cetak', 'cetak')->name('cetak');
            Route::get('/cetak2', 'cetak2')->name('cetak2');
            Route::get('/susut_wip_cabut', 'susut_wip_cabut')->name('susut_wip_cabut');

            Route::get('/get_no_box_sortir', 'get_no_box_sortir')->name('get_no_box_sortir');
            Route::get('/load_box_selesai', 'load_box_selesai')->name('load_box_selesai');
            Route::post('/save_selesai', 'save_selesai')->name('save_selesai');
            Route::get('/export_opname_cetak', 'export_opname_cetak')->name('export_opname_cetak');
        });
    Route::controller(GudangNewController::class)
        ->prefix('gudangnew')
        ->name('gudangnew.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/tbh_baris', 'tbh_baris')->name('tbh_baris');
            Route::post('/save_gudang_bk', 'save_gudang_bk')->name('save_gudang_bk');
            Route::get('/gudang_p_kerja', 'gudang_p_kerja')->name('gudang_p_kerja');
            Route::get('/gudang_grading', 'gudang_grading')->name('gudang_grading');
            Route::post('/import_buku_campur_produksi', 'import_buku_campur_produksi')->name('import_buku_campur_produksi');
            Route::get('/gudang_cabut', 'gudang_cabut')->name('gudang_cabut');
            Route::get('/gudang_c_pgws', 'gudang_c_pgws')->name('gudang_c_pgws');
            Route::get('/export_g_cabut', 'export_g_cabut')->name('export_g_cabut');
            // Laporan
            Route::get('/laporan_produksi', 'laporan_produksi')->name('laporan_produksi');
            Route::get('/laporan_boxproduksi', 'laporan_boxproduksi')->name('laporan_boxproduksi');
            Route::get('/export_laporan_boxproduksi', 'export_laporan_boxproduksi')->name('export_laporan_boxproduksi');
            Route::get('/export_g_c_pgws', 'export_g_c_pgws')->name('export_g_c_pgws');
            Route::get('/get_no_box', 'get_no_box')->name('get_no_box');
            Route::get('/export_show_box', 'export_show_box')->name('export_show_box');
            Route::get('/get_susut', 'get_susut')->name('get_susut');
            Route::post('/save_susut', 'save_susut')->name('save_susut');
        });
    Route::controller(GudangGradingController::class)
        ->prefix('gudang_grading')
        ->name('gudang_grading.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/selesai', 'selesai')->name('selesai');
            Route::get('/export', 'export')->name('export');
            Route::post('/create_suntikan', 'create_suntikan')->name('create_suntikan');
        });
    Route::controller(GudangCetakController::class)
        ->prefix('gudangcetak')
        ->name('gudangcetak.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/export_g_cetak', 'export_g_cetak')->name('export_g_cetak');
            Route::get('/masuk_bk_grading', 'masuk_bk_grading')->name('masuk_bk_grading');
            Route::post('/import_bk_ctk', 'import_bk_ctk')->name('import_bk_ctk');
            Route::post('/save_cetak', 'save_cetak')->name('save_cetak');
            Route::get('/g_ctk_pgws', 'g_ctk_pgws')->name('g_ctk_pgws');
            Route::get('/g_ctk_in_progres', 'g_ctk_in_progres')->name('g_ctk_in_progres');
            Route::get('/lap_box_cetak', 'lap_box_cetak')->name('lap_box_cetak');
            Route::get('/export_laporan_boxproduksicetak', 'export_laporan_boxproduksicetak')->name('export_laporan_boxproduksicetak');
        });
    Route::controller(GudangBjController::class)
        ->prefix('gudangBj')
        ->name('gudangBj.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/bk_sortir', 'bk_sortir')->name('bk_sortir');
        });
    Route::controller(PrintNotaPajakController::class)
        ->prefix('bahanbaku')
        ->name('bahanbaku.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/get_data', 'get_data')->name('get_data');
            Route::get('/print_nota', 'print_nota')->name('print_nota');
        });
});
