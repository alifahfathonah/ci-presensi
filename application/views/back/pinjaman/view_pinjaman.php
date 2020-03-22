<div class="content flashdata_container">
  <?php echo $this->session->flashdata('message'); ?>
</div>

<div class="content mt-3">
  <div class="card" style="border-top: solid 3px #7ea4b3">
    <div class="card-body card-block">
      <h5 class="card-title display-4" style="font-size: 25px">Data Pinjaman Anggota</h5>
      <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
        <button onclick="add()" type="button" class="btn small btn-warning" ><b class="fa fa-plus"></b> Tambah</button>
    </div>
    <div class="btn-group btn-group-sm float-right" id="button_container">
    </div>
    <hr>
    <div class="table-responsive">
      <table id="table_pinjaman" class="table table-hover table-sm small" style="width:100%; align: justify" >
        <thead class='thead-dark'>
          <tr>
            <th >No </th>
            <th >Kode </th>
            <th >Tgl Pinjam </th>
            <th >Nama Anggota </th>
            <th >Hitungan </th>
            <th >Total Tagihan </th>
            <th >Lunas </th>
            <th >User </th>
            <th width="50px">Pilihan </th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal_add_trans_pinjaman">
  <div class="modal-dialog modal-lg" role="document" style="width: 1200px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form_trans_pinjaman">
          <input type="hidden" name="id" id="id">

          <div class="col-md-8">

            <div class="form-group row">
              <label for="id_input_tanggal" class="control-label col-md-4">Tanggal Pinjam</label>
              <div class="col-md-8">
                <div class="input-group input-group-sm date">
                  <input type="text" class="form-control form-control-sm" name="input_tanggal_pinjam"  id='input_tanggal_pinjam' readonly>
                  <div class="input-group-append">
                    <span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar"></i></span>
                  </div>
                </div>
                <small class="form-text text-danger" id="input_tanggal_pinjam_error" ></small>
              </div>
            </div>

            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">Nama Anggota </label>
              <div class="col-sm-8" id="prefetch">
                <input type="text" name="input_nama_anggota" id="input_nama_anggota"
                class="form-control form-control-sm typeahead" placeholder="Ketik Nama Anggota" />
              </div>
              <div class=" col-sm-4">
              </div>
              <div class="col-sm-8" >
                <small id="input_nama_anggota_error" class="text-danger"></small>
              </div>
            </div>

            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">Jenis Pinjaman </label>
              <div class="col-sm-8">
                <?php
                echo cmb_dinamis('input_jenis_pinjaman','tbl_barang','nm_barang','id' );
                ?>
                <small id="input_jenis_pinjaman_error" class="text-danger"></small>
              </div>
            </div>

            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">Nominal </label>
              <div class="col-sm-8">
                <input type="text" class="form-control form-control-sm" id="input_jumlah_pinjaman" name="input_jumlah_pinjaman" onkeypress="return isNumberKey(event)" >
                
                <small id="input_jumlah_pinjaman_error" class="text-danger"></small>
              </div>
            </div>

            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">Lama Angsuran </label>
              <div class="col-sm-8">
                <?php
                echo cmb_dinamis_4('input_lama_angsuran','view_jns_angsuran', 'ket','ket' );
                ?>
                <small id="input_lama_angsuran_error" class="text-danger"></small>
              </div>
            </div>

            <?php

            # ambil suku bunga
          	foreach ($suku_bunga as $row) {
          		$bunga = $row->bg_pinjam;
            }
            
          	# ambil biaya admin
          	foreach ($biaya as $row) {
          		$biaya_adm = $row->biaya_adm;
          	}

            ?>

            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">Bunga </label>
              <div class="col-sm-8">
                <input type="text" class="form-control form-control-sm" id="input_bunga" name="input_bunga" value="<?php echo $bunga; ?> %" readonly>
                <small id="input_bunga_error" class="text-danger"></small>
              </div>
            </div>

            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">Biaya Admin </label>
              <div class="col-sm-8">
                <input type="text" class="form-control form-control-sm" id="input_biaya_admin" name="input_biaya_admin" value="<?php echo $biaya_adm; ?> %" readonly>
                <small id="input_biaya_admin_error" class="text-danger"></small>
              </div>
            </div>

            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">Ambil dari Kas</label>
              <div class="col-sm-8">
                <?php
                $where = array('tmpl_pemasukan' => 'Y' );
                echo cmb_dinamis_2('-- Pilih Kas --','input_ambil_dari_kas','nama_kas_tbl','nama','nama','id',$where);
                ?>
                <small id="input_ambil_dari_kas_error" class="text-danger"></small>
              </div>
            </div>

            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">Keterangan </label>
              <div class="col-sm-8">
                <input type="text" class="form-control form-control-sm" id="input_keterangan" name="input_keterangan">
                <small id="input_keterangan_error" class="text-danger"></small>
              </div>
            </div>

          </div>

          <div class="col-md-4">
            <img id="img_prev" src="<?php echo base_url().'uploads/photo_default.jpg' ?>" alt="..." class="img-thumbnail">
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
