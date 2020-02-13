
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
      <h5 class="card-title display-4" style="font-size: 25px">Data Transaksi Pemasukan Kas</h5>
      <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
        <button onclick="add()" type="button" class="btn btn-sm btn-success" ><b class="fa fa-plus"></b> Tambah</button>
        <!-- <button onclick="reload_table()" type="button" class="btn btn-sm btn-outline-primary" ><b class="fa fa-refresh"></b></button>
        <div id="reportrange" class="btn btn-outline-success btn-sm">
          <b class="fa fa-filter"></b>
        </div> -->
      </div>
      <div class="btn-group btn-group-sm float-right" id="button_container">
        <!-- <button type="button" onclick="export_excel()" class="btn btn-sm btn-info"><b class="fa fa-file"></b> Export</button>
        <button type="button" class="btn btn-sm btn-warning"><b class="fa fa-print"></b> Print</button> -->
      </div>
      <hr>
      <div class="table-responsive">
        <table id="table_pemasukankas" class="table table-hover table-striped table-sm small" style="width:100%" >
          <thead class='thead-dark'>
            <tr>
              <th >No </th>
              <th >KD Trans </th>
              <th >Tgl Transaksi </th>
              <th >Uraian </th>
              <th >Dari Akun </th>
              <th >Untuk Kas </th>
              <th >Jumlah </th>
              <th >User </th>
              <th >Pilihan </th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="modal fade" tabindex="-1" role="dialog" id="modal_add_trans_pemasukan_kas">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Data</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="form_trans_pemasukan_kas">
            <input type="hidden" name="id" id="id">

            <div class="form-group row">
              <label for="id_input_tanggal" class="control-label col-md-4">Tanggal Transaksi</label>
              <div class="col-md-8" id="sandbox-container">
                <div class="input-daterange input-group mb-3">
                  <input readonly="" type="text" class="form-control form-control-sm" name="input_tanggal_trans" id="input_tanggal_trans"
                  required value="<?php echo Date('Y-m-d'); ?>" >
                  <div class="input-group-append">
                    <span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar"></i></span>
                  </div>
                </div>
                <small class="form-text text-danger" id="input_tanggal_registrasi_error" ></small>
              </div>
            </div>

            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">Jumlah </label>
              <div class="col-sm-8">
                <input type="text" class="form-control form-control-sm" id="input_jumlah" name="input_jumlah" onkeypress="return isNumberKey(event)">
                <small id="input_jumlah_error" class="text-danger"></small>
              </div>
            </div>

            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">Keterangan </label>
              <div class="col-sm-8">
                <textarea class="form-control form-control-sm" id="input_uraian" name="input_uraian" rows="2" cols="80"></textarea>
                <small id="input_uraian_error" class="text-danger"></small>
              </div>
            </div>

            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">Dari Akun </label>
              <div class="col-sm-8">
                <?php
                $where = array('pemasukan' => 'Y' );
                echo cmb_dinamis_3('-- Pilih Jenis Akun --','input_dari_akun','jns_akun','jns_trans','kd_aktiva','id',$where);
                ?>
                <small id="input_dari_akun_error" class="text-danger"></small>
              </div>
            </div>

            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">Untuk Kas</label>
              <div class="col-sm-8">
                <?php
                $where = array('tmpl_pemasukan' => 'Y' );
                // cmb_dinamis($pilih,$name,$table,$field,$order_by,$pk,$where,$selected=null, $action=null)
                echo cmb_dinamis_2('-- Pilih Kas --','input_untuk_kas','nama_kas_tbl','nama','nama','id',$where);
                ?>
                <small id="input_untuk_kas_error" class="text-danger"></small>
              </div>
            </div>
            <input type="hidden" name="alasan_hapus" id="alasan_hapus">
          </div>
          <div class="modal-footer">
            <button type="submit" id="btn-save" class="btn btn-sm btn-primary">Simpan</button>
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Batal</button>
          </form>
        </div>
      </div>
    </div>
  </div>

<div id="export_container">

</div>
