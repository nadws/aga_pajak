<div class="row">
    <div class="col-lg-6">
        <h6>No Lot :{{ $invoice->no_lot }}</h6>
    </div>
    <div class="col-lg-6">
        <h6 class="text-end">No Nota :{{ $invoice->no_nota }}</h6>
    </div>
    <div class="col-lg-12">
        <hr>
    </div>
    @if (empty($grading))
        <div class="col-lg-3">
            <label for="">Tanggal</label>
            <input type="date" class="form-control" name="tgl" required>
        </div>
        <div class="col-lg-3">
            <label for="">No Campur</label>
            <input type="text" class="form-control" name="no_campur">
            <input type="hidden" class="form-control nota_grading" name="no_nota" required>
            <input type="hidden" class="form-control" name="no_lot" required value="{{ $invoice->no_lot }}">
        </div>
        <div class="col-lg-2">
            <label for="">Gram Basah</label>
            <input type="text" class="form-control text-end" name="gr_basah" value="0" required>
        </div>
        <div class="col-lg-2">
            <label for="">Pcs Awal</label>
            <input type="text" class="form-control text-end" name="pcs_awal" value="0" required>
        </div>
        <div class="col-lg-2">
            <label for="">Gr Kering</label>
            <input type="text" class="form-control text-end" name="gr_kering" value="0" required>
        </div>
    @else
        <div class="col-lg-3">
            <label for="">Tanggal</label>
            <input type="date" class="form-control" name="tgl" value="{{ $grading->tgl }}">
        </div>
        <div class="col-lg-3">
            <label for="">No Campur</label>
            <input type="text" class="form-control" name="no_campur" value="{{ $grading->no_campur }}"
                {{ empty($grading->no_campur) ? '' : '' }}>
            <input type="hidden" class="form-control nota_grading" name="no_nota" value="{{ $grading->no_nota }}">
            <input type="hidden" class="form-control" name="no_lot" required value="{{ $invoice->no_lot }}">
        </div>
        <div class="col-lg-2">
            <label for="">Gram Basah</label>
            <input type="text" class="form-control" name="gr_basah" value="{{ $grading->gr_basah }}"
                {{ $grading->gr_basah == '0' ? '' : '' }}>
        </div>
        <div class="col-lg-2">
            <label for="">Pcs Awal</label>
            <input type="text" class="form-control" name="pcs_awal" value="{{ $grading->pcs_awal }}"
                {{ $grading->pcs_awal == '0' ? '' : '' }}>
        </div>
        <div class="col-lg-2">
            <label for="">Gr Kering</label>
            <input type="text" class="form-control" name="gr_kering" value="{{ $grading->gr_kering }}"
                {{ $grading->gr_kering == '0' ? '' : '' }}>
        </div>
    @endif

</div>
<div class="row">
    <div class="col-lg-6">
        <hr>
        <form id="save_grade">
            <div class="row" x-data="{
                open1: false,
            }">
                <div class="col-lg-12">
                    <button type="button" class="btn btn-primary btn-sm btn-buka mb-4" @click="open1 = ! open1">Tambah
                        Grade</button>
                </div>


                <div class="col-lg-8" x-show="open1">
                    <input type="text" class="form-control" name="nm_grade" placeholder="Nama Grade">
                    <input type="hidden" class="form-control" name="no_nota" value="{{ $invoice->no_nota }}">
                </div>
                <div class="col-lg-4" x-show="open1">
                    <button type="button" class="btn btn-primary btn-save">Save</button>
                </div>
                <br>
                <br>
                <br>
            </div>
        </form>
        <div id="load_grade"></div>
    </div>
</div>
