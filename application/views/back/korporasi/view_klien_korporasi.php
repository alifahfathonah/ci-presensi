
<div class="content mt-3">
  <div class="card" style="border-top: solid 3px green">
    <div class="card-body card-block">
      <h5 class="card-title display-4" style="font-size: 20px">Data Klien Korporasi</h5>
      <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
        <button class="btn btn-sm btn-success" onclick="modal_add()"><b class="fa fa-plus"></b> Tambah</button>
      </div>
      <div class="btn-group btn-group-sm float-right" id="button_container">
      </div>
      <hr>
      <div class="table-responsive">
        <table class="table table-hover table-striped table-sm small" style="width:100%" id="table_korporasi">
          <thead class="thead-dark">
            <tr class="text-center">
              <th style="width:5%; vertical-align: middle ">No.</th>
              <th style="width:30%; vertical-align: middle">Nama</th>
              <th style="width:30%; vertical-align: middle">Alamat</th>
              <th style="width:5%; vertical-align: middle">Kota</th>
              <th style="width:15%; vertical-align: middle">No. Telp</th>
              <th style="width:10%; vertical-align: middle">#</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="modal_add">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Data Klien Korporasi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form_korporasi">
          <input type="hidden" name="id">
          <div class="form-group">
            <label for="input_nama_korporasi">Nama</label>
            <input type="text" class="form-control" id="input_nama_korporasi" name="input_nama_korporasi" >
            <small class="form-text text-danger" id="input_nama_korporasi_error"></small>
          </div>

          <div class="form-group">
            <label for="input_alamat_korporasi">Alamat</label>
            <input type="text" class="form-control" id="input_alamat_korporasi" name="input_alamat_korporasi" >
            <small class="form-text text-danger" id="input_alamat_korporasi_error"></small>
          </div>

          <div class="form-group">
            <label for="input_kota_korporasi">Kota</label>
            <input type="text" class="form-control" id="input_kota_korporasi" name="input_kota_korporasi" >
            <small class="form-text text-danger" id="input_kota_korporasi_error"></small>
          </div>

          <div class="form-group">
            <label for="input_telepon_korporasi">No. Telepon</label>
            <input type="text" class="form-control" id="input_telepon_korporasi" name="input_telepon_korporasi" >
            <small class="form-text text-danger" id="input_telepon_korporasi_error"></small>
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="btn_save">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
document.title = "Klien Korporasi - KSU Sakrawarih"
</script>
