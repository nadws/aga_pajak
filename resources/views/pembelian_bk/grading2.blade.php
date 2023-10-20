<div class="row">
    <div class="col-lg-12">
        <h5>{{ $invoice->no_nota }}</h5>
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
            <input type="date" class="form-control" name="tgl" value="{{ $grading->tgl }}" readonly>
        </div>
        <div class="col-lg-3">
            <label for="">No Campur</label>
            <input type="text" class="form-control" name="no_campur" value="{{ $grading->no_campur }}"
                {{ empty($grading->no_campur) ? '' : 'readonly' }}>
            <input type="hidden" class="form-control nota_grading" name="no_nota" value="{{ $grading->no_nota }}">
        </div>
        <div class="col-lg-2">
            <label for="">Gram Basah</label>
            <input type="text" class="form-control" name="gr_basah" value="{{ $grading->gr_basah }}"
                {{ $grading->gr_basah == '0' ? '' : 'readonly' }}>
        </div>
        <div class="col-lg-2">
            <label for="">Pcs Awal</label>
            <input type="text" class="form-control" name="pcs_awal" value="{{ $grading->pcs_awal }}"
                {{ $grading->pcs_awal == '0' ? '' : 'readonly' }}>
        </div>
        <div class="col-lg-2">
            <label for="">Gr Kering</label>
            <input type="text" class="form-control" name="gr_kering" value="{{ $grading->gr_kering }}"
                {{ $grading->gr_kering == '0' ? '' : 'readonly' }}>
        </div>
    @endif

</div>
<div class="row">
    <div class="col-lg-12">
        <hr>
        <table class="">

        </table>
    </div>
</div>
