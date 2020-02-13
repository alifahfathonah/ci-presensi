
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
      <h5 class="card-title display-4" style="font-size: 25px">Jenis Akun Transaksi</h5>
      <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
        <button onclick="add_jenis_usaha()" type="button" class="btn btn-sm btn-success" ><b class="fa fa-plus"></b> Tambah</button>
      </div>
      <div class="btn-group btn-group-sm float-right" role="group" aria-label="Basic example">
        <button type="button" onclick="export_excel()" class="btn btn-sm btn-info"><b class="fa fa-file"></b> Export</button>
        <button type="button" class="btn btn-sm btn-warning"><b class="fa fa-print"></b> Print</button>
      </div>
      <hr>
      <div class="table-responsive">
        <table id="table_jenisakun" class="table table-hover table-striped table-sm small" style="width:100%" >
          <thead class='thead-dark'>
            <tr>
              <th >Kd Aktiva </th>
              <th >Jenis Transaksi </th>
              <th >Akun </th>
              <th >Pemasukan </th>
              <th >Pengeluaran </th>
              <th >Aktif </th>
              <th >Laba-Rugi </th>
              <th >Pilihan </th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
  </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal_add_jenis_akun">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Jenis Akun Transaksi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form_jenis_akun">
          <input type="hidden" name="id" id="id">

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">Kd aktiva* : </label>
            <div class="col-sm-8">
              <input type="text" class="form-control form-control-sm" id="input_kd_aktiva" name="input_kd_aktiva">
              <small id="input_kd_aktiva_error" class="text-danger"></small>
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">Jenis Transaksi* : </label>
            <div class="col-sm-8">
              <input type="text" class="form-control form-control-sm" id="input_jenis_trans" name="input_jenis_trans" >
              <small id="input_jenis_trans_error" class="text-danger"></small>
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">Akun* : </label>
            <div class="col-sm-8">
              <select class="form-control form-control-sm" id="input_akun" name="input_akun" >
                <option value="x">-- Pilih --</option>
                <option value="Aktiva">Aktiva</option>
                <option value="Pasiva">Pasiva</option>
              </select>
              <small id="input_akun_error" class="text-danger"></small>
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">Pemasukan* : </label>
            <div class="col-sm-8">
              <select class="form-control form-control-sm" id="input_pemasukan" name="input_pemasukan" >
                <option value="x">-- Pilih --</option>
                <option value="Y">Y</option>
                <option value="N">N</option>
              </select>
              <small id="input_pemasukan_error" class="text-danger"></small>
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">Pengeluaran* : </label>
            <div class="col-sm-8">
              <select class="form-control form-control-sm" id="input_pengeluaran" name="input_pengeluaran" >
                <option value="x">-- Pilih --</option>
                <option value="Y">Y</option>
                <option value="N">N</option>
              </select>
              <small id="input_pengeluaran_error" class="text-danger"></small>
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">Aktif* : </label>
            <div class="col-sm-8">
              <select class="form-control form-control-sm" id="input_aktif" name="input_aktif" >
                <option value="x" >-- Pilih --</option>
                <option value="Y">Y</option>
                <option value="N">N</option>
              </select>
              <small id="input_aktif_error" class="text-danger"></small>
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">Laba rugi : </label>
            <div class="col-sm-8">
              <select class="form-control form-control-sm" id="input_labarugi" name="input_labarugi" >
                <option value="x">-- Pilih --</option>
                <option value="PENDAPATAN">Pendapatan</option>
                <option value="BIAYA">Biaya</option>
              </select>
              <small id="input_labarugi_error" class="text-danger"></small>
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
