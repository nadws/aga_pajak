{{-- obat campuran pakan --}}
<div class="row">
    <div class="col-lg-3">
        <div class="form-group">
            <label for="">Obat</label>
            <select name="id_obatPakan[]" id="" count="1" class="form-control select2-edit obat_pakan_input" detail="1">
                <option value="">- Pilih Obat -</option>
                @foreach ($pakan as $o)
                    <option value="{{ $o->id_produk }}">{{ $o->nm_produk }}</option>
                @endforeach
                <option value="tambah">+ Obat</option>
            </select>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label for="">Dosis</label>
            <input type="text" class="form-control" id="ds1" name="dosisPakan[]">
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label for="">Satuan</label>
            <input type="text" readonly name="satuanObat[]" id="stn1" class="form-control get_dosis_satuan1">
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label for="">Campuran</label>
            <input type="text" class="form-control" id="cmpr1" name="obatCampuran[]">
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label for="">Satuan</label>
            <input type="text" id="stnc1" readonly name="satuanObat2[]" class="form-control get_campuran_satuan1">
        </div>
    </div>
    <div class="col-lg-1">
        <div class="form-group">
            <label for="">Aksi</label><br>
            <button class="btn btn-primary btn-md tbhObatPakan" type="button"><i
                    class="fa fa-plus"></i></button>
        </div>
    </div>
</div>
<div id="tbhObatPakan"></div>
