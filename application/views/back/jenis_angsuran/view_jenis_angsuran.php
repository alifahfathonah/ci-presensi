
<!-- <div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Profil Koperasi</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title" id="txt">
            </div>
        </div>
    </div>
</div> -->

<div class="content flashdata_container">
    <?php echo $this->session->flashdata('message'); ?>
</div>
<div class="content mt-3">
  <div class="card" style="border-top: solid 3px #7ea4b3">
    <div class="card-body card-block">
      <h5 class="card-title display-4" style="font-size: 25px">Jenis Angsuran</h5>
      <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
        <button onclick="add_jenis_usaha()" type="button" class="btn btn-sm btn-success" ><b class="fa fa-plus"></b> Tambah</button>
      </div>
      <div class="btn-group btn-group-sm float-right" role="group" aria-label="Basic example">
        <button type="button" onclick="export_excel()" class="btn btn-sm btn-info"><b class="fa fa-file"></b> Export</button>
        <button type="button" class="btn btn-sm btn-warning"><b class="fa fa-print"></b> Print</button>
      </div>
      <hr>
      <div class="table-responsive">
        <table id="table_jenisangsuran" class="table table-hover table-striped table-sm small" style="width:100%" >
          <thead class='thead-dark'>
            <tr>
              <th >Lama Angsuran (Bulan) </th>
              <th >Aktif </th>
              <th >Pilihan </th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
  </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal_add_jenis_angsuran">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Jenis Angsuran</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form_jenis_angsuran">
          <input type="hidden" name="id" id="id">

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-6 col-form-label">Lama Angsuran (Bulan)* :  </label>
            <div class="col-sm-6">
              <input type="number" class="form-control form-control-sm" id="input_angsuran" name="input_angsuran" onkeypress="return isNumberKey(event)">
              <small id="input_angsuran_error" class="text-danger"></small>
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-6 col-form-label">Aktif : </label>
            <div class="col-sm-6">
              <select class="form-control form-control-sm" id="input_aktif" name="input_aktif" >
                <option value="x" >-- Pilih --</option>
                <option value="Y">Y</option>
                <option value="T">T</option>
              </select>
              <small id="input_aktif_error" class="text-danger"></small>
            </div>
          </div>

      </div>
      <div class="modal-footer">
        <button type="submit" id="btn-save" class="btn btn-sm btn-primary">Simpan</button>
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Batal</button>
        </form>
      </div>
    </div>
  </div>
</div>
