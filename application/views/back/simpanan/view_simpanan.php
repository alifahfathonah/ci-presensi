
<div class="content flashdata_container">
  <?php echo $this->session->flashdata('message'); ?>
</div>
<div class="content mt-3">
  <div class="card" style="border-top: solid 3px #7ea4b3">
    <div class="card-body card-block">
      <h5 class="card-title display-4" style="font-size: 25px">Data Transaksi Setoran Tunai</h5>
      <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
        <button onclick="add()" type="button" class="btn small btn-warning" ><b class="fa fa-plus"></b> Tambah</button>
    </div>
    <div class="btn-group btn-group-sm float-right" id="button_container">
    </div>
    <hr>
    <div class="table-responsive">
      <table id="table_simpanan" class="table table-hover table-striped table-sm small" style="width:100%" >
        <thead class='thead-dark'>
          <tr>
            <th >No </th>
            <th >KD Trans </th>
            <th >Tgl Transaksi </th>
            <th >ID Anggota </th>
            <th >Nama Anggota </th>
            <th >Department </th>
            <th >Jenis Simpanan </th>
            <th >Jumlah </th>
            <th >User </th>
            <th >Cetak Nota </th>
            <th >Pilihan </th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal_add_trans_simpanan">
  <div class="modal-dialog modal-lg mw-100" role="document" style="width: 1200px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form_trans_simpanan">
          <input type="hidden" name="id" id="id">


          <div class="row justify-content-between">
            <div class="col-md-5">
              <div class="form-group row">
                <label for="id_input_tanggal" class="control-label col-md-4">Tanggal Transaksi</label>
                <div class="col-md-8">
                  <div class="input-group input-group-sm date">
                    <input type="text" class="form-control form-control-sm" name="input_tanggal_trans"  id='input_tanggal_trans' readonly>
                    <div class="input-group-append">
                      <span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar"></i></span>
                    </div>
                  </div>
                  <small class="form-text text-danger" id="input_tanggal_trans_error" ></small>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-4">

            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-12 col-form-label"><b>Identitas Penyetor</b> </label>
            </div>

            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">Nama </label>
              <div class="col-sm-8">
                <input type="text" class="form-control form-control-sm" id="input_nama_penyetor" name="input_nama_penyetor" >
                <small id="input_nama_penyetor_error" class="text-danger"></small>
              </div>
            </div>

            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">Nomor Identitas </label>
              <div class="col-sm-8">
                <input type="text" class="form-control form-control-sm" id="input_nomor_id_penyetor" name="input_nomor_id_penyetor" >
                <small id="input_nomor_id_penyetor_error" class="text-danger"></small>
              </div>
            </div>

            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">Alamat </label>
              <div class="col-sm-8">
                <textarea class="form-control form-control-sm" id="input_alamat_penyetor" name="input_alamat_penyetor" rows="2" cols="80"></textarea>
                <small id="input_alamat_penyetor_error" class="text-danger"></small>
              </div>
            </div>

          </div>

          <div class="col-md-6">
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-12 col-form-label"><b>Identitas Penerima</b> </label>
            </div>

            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">Nama Anggota </label>
              <div class="col-sm-8" id="prefetch">
                <?php
                // echo cmb_dinamis('input_nama_anggota','tbl_anggota','nama','id');
                ?>
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
              <label for="inputEmail3" class="col-sm-4 col-form-label">Jenis Simpanan </label>
              <div class="col-sm-8">
                <?php
                echo cmb_dinamis('input_jenis_simpanan','jns_simpan','jns_simpan','id' );
                ?>
                <small id="input_jenis_simpanan_error" class="text-danger"></small>
              </div>
            </div>

            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">Jumlah Simpanan </label>
              <div class="col-sm-8">
                <input type="text" class="form-control form-control-sm" id="input_jumlah_simpanan" name="input_jumlah_simpanan" onkeypress="return isNumberKey(event)">
                <small id="input_jumlah_simpanan_error" class="text-danger"></small>
              </div>
            </div>

            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">Keterangan </label>
              <div class="col-sm-8">
                <input type="text" class="form-control form-control-sm" id="input_keterangan" name="input_keterangan">
                <small id="input_keterangan_error" class="text-danger"></small>
              </div>
            </div>

            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-4 col-form-label">Simpan Ke Kas</label>
              <div class="col-sm-8">
                <?php
                $where = array('tmpl_pemasukan' => 'Y' );
                echo cmb_dinamis_2('-- Pilih Kas --','input_simpan_ke_kas','nama_kas_tbl','nama','nama','id',$where);
                ?>
                <small id="input_simpan_ke_kas_error" class="text-danger"></small>
              </div>
            </div>

          </div>

          <div class="col-md-2">
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-12 col-form-label"><b>Photo</b></label>
            </div>
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
