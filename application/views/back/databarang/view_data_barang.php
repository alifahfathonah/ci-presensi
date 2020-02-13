
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
      <h5 class="card-title display-4" style="font-size: 25px">Data Barang</h5>
      <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
        <button onclick="add_jenis_usaha()" type="button" class="btn btn-sm btn-success" ><b class="fa fa-plus"></b> Tambah</button>
      </div>
      <div class="btn-group btn-group-sm float-right" role="group" aria-label="Basic example">
        <button type="button" onclick="export_excel()" class="btn btn-sm btn-info"><b class="fa fa-file"></b> Export</button>
        <button type="button" class="btn btn-sm btn-warning"><b class="fa fa-print"></b> Print</button>
      </div>
      <hr>
      <div class="table-responsive">
        <table id="table_databarang" class="table table-hover table-striped table-sm small" style="width:100%" >
          <thead class='thead-dark'>
            <tr>
              <th >Nama Barang </th>
              <th >Type </th>
              <th >Merk </th>
              <th >Harga </th>
              <th >Jumlah Barang </th>
              <th >Keterangan </th>
              <th >Pilihan </th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
  </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal_add_databarang">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Data Barang</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form_databarang">
          <input type="hidden" name="id" id="id">

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-6 col-form-label">Nama Barang* :</label>
            <div class="col-sm-6">
              <input type="text" class="form-control form-control-sm" id="input_nama_barang" name="input_nama_barang" >
              <small id="input_nama_barang_error" class="text-danger"></small>
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-6 col-form-label">Type :</label>
            <div class="col-sm-6">
              <input type="text" class="form-control form-control-sm" id="input_type" name="input_type" >
              <small id="input_type_error" class="text-danger"></small>
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-6 col-form-label">Merk :  </label>
            <div class="col-sm-6">
              <input type="text" class="form-control form-control-sm" id="input_merk" name="input_merk" >
              <small id="input_merk_error" class="text-danger"></small>
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-6 col-form-label">Harga* :  </label>
            <div class="col-sm-6">
              <input type="text" class="form-control form-control-sm" id="input_harga" name="input_harga" onkeypress="return isNumberKey(event)">
              <small id="input_harga_error" class="text-danger"></small>
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-6 col-form-label">Jumlah Barang* :  </label>
            <div class="col-sm-6">
              <input type="text" class="form-control form-control-sm" id="input_jml_barang" name="input_jml_barang" onkeypress="return isNumberKey(event)">
              <small id="input_jml_barang_error" class="text-danger"></small>
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-6 col-form-label">Ket </label>
            <div class="col-sm-6">
              <input type="text" class="form-control form-control-sm" id="input_keterangan" name="input_keterangan" >
              <small id="input_angsuran_error" class="text-danger"></small>
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
