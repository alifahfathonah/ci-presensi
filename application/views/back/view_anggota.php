
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
      <h5 class="card-title display-4" style="font-size: 25px">Data Anggota</h5>
      <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
        <a role="button" class="btn btn-sm btn-success" href="<?php echo base_url('anggota/add'); ?>"><b class="fa fa-plus"></b> Tambah</a>
      </div>
      <div class="btn-group btn-group-sm float-right" role="group" aria-label="Basic example">
        <!-- <a href="<?php echo base_url('anggota/export_to_excel'); ?>" target="_blanks" role="button" class="btn btn-sm btn-info"><b class="fa fa-file"></b> Ekspor</a> -->
        <button type="button" onclick="export_excel()" class="btn btn-sm btn-info"><b class="fa fa-file"></b> Export</button>
        <button type="button" class="btn btn-sm btn-warning"><b class="fa fa-print"></b> Print</button>
      </div>
      <hr>
      <div class="table-responsive">
        <table id="table_anggota" class="table table-hover table-sm small" style="width:150%" >
          <thead class='thead-dark'>
            <tr>
              <th >Photo </th>
              <th >ID Anggota </th>
              <th >Korporasi </th>
              <th >Nama Lengkap </th>
              <th >Simpanan Pokok </th>
              <th >Simpanan Wajib </th>
              <th >Simpanan Sukarela </th>
              <th >Aktif Keanggotaan </th>
              <th >Jenis Kelamin </th>
              <th >Alamat </th>
              <th >Jabatan </th>
              <th >Tanggal Registrasi </th>
              <th >Pilihan </th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
  </div>
</div>
