<div class="row baris{{ $count }}">
    <div class="col-lg-12">
        <hr style="border: 1px solid">
    </div>
    <div class="col-lg-2 col-4">
        <label for="">Keterangan</label>
        <input type="text" class="form-control" name="ket[]">
    </div>
    <div class="col-lg-2 col-4">
        <label for="">Persen Air</label>
        <input type="text" class="form-control" value="0" name="persen_air[]">
    </div>
</div>
<div class="row  mt-4 baris{{ $count }}">
    <div class="col-lg-4">
        <div class="row">
            <div class="col-lg-6 col-4"><label for="">Grade</label></div>
            <div class="col-lg-3 col-4"><label for="">Gr</label></div>
            <div class="col-lg-3 col-4"><label for="">Harga</label></div>
        </div>
    </div>
    <div class="col-lg-4 label_hilang">
        <div class="row">
            <div class="col-lg-6 col-4"><label for="">Grade</label></div>
            <div class="col-lg-3 col-4"><label for="">Gr</label></div>
            <div class="col-lg-3 col-4"><label for="">Harga</label></div>
        </div>
    </div>
    <div class="col-lg-4 label_hilang">
        <div class="row">
            <div class="col-lg-6 col-4"><label for="">Grade</label></div>
            <div class="col-lg-3 col-4"><label for="">Gr</label></div>
            <div class="col-lg-3 col-4"><label for="">Harga</label></div>
        </div>
    </div>
</div>
<div class="row baris{{ $count }}">
    @foreach ($grade as $g)
        <div class="col-lg-4 mt-2" style="border-right: 1px solid black">
            <div class="row">
                <div class="col-lg-6 col-4">

                    <input type="hidden" name="id_grade{{ $count }}[]" value="{{ $g->id_grade_cong }}">
                    <input type="text" class="form-control" style="font-size: 12px" value="{{ $g->nm_grade }}"
                        readonly>
                </div>
                <div class="col-lg-3 col-4">

                    <input type="text" class="form-control gr gr{{ $count }}" count="{{ $count }}"
                        value="0" name="gr{{ $count }}[]">
                </div>
                <div class="col-lg-3 col-4">

                    <input type="text" class="form-control" value="0" name="harga{{ $count }}[]"
                        readonly>
                </div>
            </div>
        </div>
    @endforeach
</div>
<br>
<br>
<div class="row baris{{ $count }}">
    <div class="col-lg-2">
        <table style="padding: 10px">
            <tr>
                <td>
                    <h6>Total Gram &nbsp;</h6>
                </td>
                <td><input type="text" class="form-control total_gram{{ $count }}" readonly value="0">
                </td>

            </tr>
            <tr>
                <td>
                    <h6>Harga Beli &nbsp;</h6>
                </td>
                <td><input type="text" class="form-control hrga_beli" name="hrga_beli[]" value="0"></td>
            </tr>
            <tr>
                <td>
                    <h6>Harga(100%) &nbsp;</h6>
                </td>
                <td><input type="text" class="form-control" readonly value="0"></td>
            </tr>
            <tr>
                <td>
                    <h6>Harga(%) &nbsp;</h6>
                </td>
                <td><input type="text" class="form-control" readonly></td>
                <input type="hidden" name="count[]" value="{{ $count }}">
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2" class="text-center"><button type="button"
                        class="btn btn-sm btn-danger  remove_baris mb-4" count="{{ $count }}"><i
                            class="fas fa-minus"></i> Hapus Baris</button></td>
            </tr>
        </table>

    </div>
    <div class="col-lg-12">
        <hr style="border: 1px solid">
    </div>


</div>
