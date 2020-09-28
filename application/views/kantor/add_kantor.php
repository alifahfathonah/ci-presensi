<?php $this->session->userdata['page_title'] = "Data Kantor"; ?>
<div class="col-md-12">
  <div class="card ">
    <div class="card-header card-header-success card-header-text">
      <div class="card-icon">
        <i class="material-icons">post_add</i>
      </div>
      <h4 class="card-title">Form Tambah Data Kantor</h4>
    </div>

    <div class="card-body ">
      <form method="post" class="form-horizontal" id="form-add-kantor" onSubmit="return false;">
        <div class="row">
          <div class="col-md-12">

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_nama_kantor">Nama Kantor</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_nama_kantor_error_container">
                  <label class="control-label" id="input_nama_kantor_error_detail"></label>
                  <input type="text"  class="form-control" id="input_nama_kantor" name="input_nama_kantor" />
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_nama_kantor_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_jenis_kantor">Jenis Kantor</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_jenis_kantor_error_container">
                  <!-- <label class="control-label" id="input_provinsi_error_detail"></label> -->
                  <select class="form-control" id="input_jenis_kantor" name="input_jenis_kantor" >
                    <option value="x" selected>-- Silahkan Pilih --</option>

                    <?php
                    foreach ($jenis_kantor->result() as $r) {
                      echo '<option value="'.$r->id.'">'.$r->nama_jenis_kantor.'</option>';
                    }
                    ?>
                  </select>
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_jenis_kantor_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_alamat">Alamat Kantor</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_alamat_error_container">
                  <label class="control-label" id="input_alamat_error_detail"></label>
                  <input type="text"  class="form-control" id="input_alamat" name="input_alamat" />
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_alamat_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_provinsi">Provinsi</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_provinsi_error_container">
                  <!-- <label class="control-label" id="input_provinsi_error_detail"></label> -->
                  <select class="form-control" id="input_provinsi" name="input_provinsi" onchange="get_kota()">
                    <option value="x" selected>-- Silahkan Pilih --</option>

                    <?php
                    foreach ($provinsi->result() as $r) {
                      echo '<option value="'.$r->id.'">'.$r->name.'</option>';
                    }
                    ?>
                  </select>
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_provinsi_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_kota">Kota / Kabupaten</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_kota_error_container">
                  <!-- <label class="control-label" id="input_kota_error_detail"></label> -->
                  <select class="form-control" id="input_kota" name="input_kota" disabled>
                    <option value="x" selected>-- Silahkan Pilih Provinsi Dahulu --</option>
                  </select>
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_kota_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_no_telp_1">No Telp 1</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_no_telp_1_error_container">
                  <label class="control-label" id="input_no_telp_1_error_detail"></label>
                  <input type="text"  class="form-control" id="input_no_telp_1" name="input_no_telp_1" />
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_no_telp_1_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_no_telp_2">No Telp 2</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_no_telp_2_error_container">
                  <label class="control-label" id="input_no_telp_2_error_detail"></label>
                  <input type="text"  class="form-control" id="input_no_telp_2" name="input_no_telp_2" />
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_no_telp_2_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_keterangan">Keterangan</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_keterangan_error_container">
                  <!-- <label class="control-label" id="input_keterangancerror_detail"></label> -->
                  <!-- <input type="text"  class="form-control" id="input_keterangan" name="input_keterangan" /> -->
                  <textarea class="form-control" id="input_keterangan" name="input_keterangan" rows="2" ></textarea>
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_keteranganc_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

          </div>

        </div>

    </div>
    <div class="card-footer">
      <!-- <button type="reset" class="btn btn-outline-secondary btn-sm" id="btn-reset">
          <i class="fa fa-refresh"></i> Reset
      </button> -->
      <button type="submit" class="btn btn-success btn-sm  ml-auto" id="btn-save">
          <!-- <i class="fa fa-save"></i> Save --> SIMPAN
      </button>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
    save_method = "add";
</script>
