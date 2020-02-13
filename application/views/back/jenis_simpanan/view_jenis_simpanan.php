
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
      <h5 class="card-title display-4" style="font-size: 25px">Jenis Simpanan</h5>
      <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
      </div>
      <div class="btn-group btn-group-sm float-right" role="group" aria-label="Basic example">
        <button type="button" onclick="export_excel()" class="btn btn-sm btn-info"><b class="fa fa-file"></b> Export</button>
        <button type="button" class="btn btn-sm btn-warning"><b class="fa fa-print"></b> Print</button>
      </div>
      <hr>
      <div class="table-responsive">
        <table id="table_jenissimpanan" class="table table-hover table-sm small" style="width:100%" >
          <thead class='thead-dark'>
            <tr>
              <th >Jenis Simpanan </th>
              <th >Jumlah </th>
              <th >Tampil </th>
              <th >Pilihan </th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
  </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal_add_jenis_simpanan">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Ubah Jenis Simpanan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form_jenis_simpanan">
          <input type="hidden" name="id" id="id">
          <div class="form-group">
            <label for="formGroupExampleInput">Nama Jenis Simpanan :</label>
            <input type="text" class="form-control" id="input_nama_jenis_simpanan" name="input_nama_jenis_simpanan" readonly>
          </div>
          <div class="form-group">
            <label for="formGroupExampleInput2">Jumlah</label>
            <input type="text" class="form-control" id="input_jumlah" name="input_jumlah" >
          </div>
          <div class="form-group">
            <label for="formGroupExampleInput2">Tampil</label>
            <!-- <input type="text" class="form-control" id="input_tampil" name="input_tampil" > -->
            <select class="form-control" id="input_tampil" name="input_tampil" >
              <option value="Y">Ya</option>
              <option value="T">Tidak</option>
            </select>
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
