<div class="row baris{{ $count }}">
    <div class="col-lg-2 mt-2">
        <input type="text" class="form-control" name="partai[]">
    </div>
    <div class="col-lg-2 mt-2">
        <input type="text" class="form-control" name="no_box[]">
    </div>
    <div class="col-lg-1 mt-2">
        <input type="text" class="form-control" name="tipe[]">
    </div>
    {{-- <div class="col-lg-1 mt-2">
        <input type="text" class="form-control" name="grade[]">
    </div> --}}
    <div class="col-lg-1 mt-2">
        <input type="text" class="form-control" name="pcs[]">
    </div>
    <div class="col-lg-1 mt-2">
        <input type="text" class="form-control" name="gr[]">
    </div>
    <div class="col-lg-2 mt-2">
        <input type="text" class="form-control" name="ttl_rp[]">
    </div>
    <div class="col-lg-2 mt-2">
        <input type="text" class="form-control" name="cost_cabut[]">
    </div>
    <div class="col-lg-1 mt-2">
        <button class="btn btn-sm btn-danger delete_row" count="{{ $count }}"><i
                class="fas fa-minus"></i></button>
    </div>
</div>
