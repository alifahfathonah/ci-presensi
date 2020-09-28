<?php $this->session->userdata['page_title'] = "Data Izin"; ?>
<div class="col-md-12">
  <div class="card ">
    <div class="card-header card-header-success card-header-text">
      <div class="card-icon">
        <i class="material-icons">post_add</i>
      </div>
      <h4 class="card-title">Form Tambah Data Izin</h4>
    </div>

    <div class="card-body ">
      <form method="post" class="form-horizontal" id="form-izin" onSubmit="return false;">
        <div class="row">
          <div class="col-md-7">

            <div class="row ">
              <label class="col-sm-3 col-form-label" for="input_departemen">Departemen</label>
              <div class="col-sm-9">
                <div class="form-group label-floating " id="input_departemen_error_container">
                  <?php if($this->session->userdata('id_hak_akses') == '3'){ ?>
                    <select class="form-control" id="input_departemen" name="input_departemen">
                      <?php
                      echo '<option value="'.$departemen['id'].'"> '.$departemen['nama_departemen'].'</option>';
                      ?>
                    </select>
                  <?php } else { ?>
                    <select class="form-control" id="input_departemen" name="input_departemen" onchange="getKaryawanByDept()">
                      <option value="x" selected>-- Silahkan Pilih --</option>
                      <?php
                      foreach ($departemen as $r) {
                        echo '<option value="'.$r->id.'"> '.$r->nama_departemen.'</option>';
                      }
                      ?>
                    </select>
                  <?php } ?>

                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_departemen_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row ">
              <label class="col-sm-3 col-form-label" for="input_karyawan">Karyawan</label>
              <div class="col-sm-9">
                <div class="form-group label-floating " id="input_karyawan_error_container">

                  <?php if($this->session->userdata('id_hak_akses') == '3'){ ?>
                    <select class="form-control" id="input_karyawan" name="input_karyawan" onload="validasi_cuti()">
                      <?php
                      echo '<option value="'.$karyawan['id'].'"> '.$karyawan['nama_lengkap'].'</option>';
                      ?>
                    </select>
                  <?php } else { ?>
                    <select class="form-control" id="input_karyawan" name="input_karyawan" onchange="validasi_cuti()" >
                      <option value="x" selected>-- Silahkan Pilih --</option>
                    </select>
                  <?php } ?>


                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_karyawan_error_icon"></i>
                  </span>
                  <label class="control-label text-danger" id="input_karyawan_sisa_cuti" style="display: none"></label>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-3 col-form-label" for="input_jenis_izin">Jenis Izin</label>
              <div class="col-sm-9">
                <div class="form-group label-floating " id="input_jenis_izin_error_container">
                  <select class="form-control" id="input_jenis_izin" name="input_jenis_izin" >
                    <option value="x" selected>-- Silahkan Pilih --</option>
                    <?php
                    foreach ($list->result() as $r) {
                      echo '<option value="'.$r->id.'">('.$r->kode.') '.$r->nama_izin.'</option>';
                    }
                    ?>
                  </select>

                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_jenis_izin_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row ">
              <label class="col-sm-3 col-form-label" for="input_tanggal_awal">Tanggal Awal</label>
              <div class="col-sm-3">
                <div class="form-group label-floating " id="input_tanggal_awal_error_container">
                  <label class="control-label center" id="input_tanggal_awal_error_detail"></label>
                  <input type="text" class="form-control datetimepicker" id="input_tanggal_awal" name="input_tanggal_awal"/>
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_tanggal_awal_error_icon"></i>
                  </span>
                </div>
              </div>
              <div class="text-center col-sm-3">
                <label class="col-form-label" for="input_tanggal_akhir" style="border: 1px solid none">Sampai dengan</label>
              </div>
              <div class="col-sm-3">
                <div class="form-group label-floating " id="input_tanggal_akhir_error_container">
                  <label class="control-label" id="input_tanggal_akhir_error_detail"></label>
                  <input type="text" class="form-control datetimepicker" id="input_tanggal_akhir" name="input_tanggal_akhir"/>
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_tanggal_akhir_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-3 col-form-label" for="input_keterangan">Keterangan</label>
              <div class="col-sm-9">
                <div class="form-group label-floating " id="input_keterangan_error_container">
                  <label class="control-label" id="input_keterangan_error_detail"></label>
                  <textarea class="form-control" id="input_keterangan" name="input_keterangan" rows="2" ></textarea>
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_keterangan_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-3 col-form-label" for="input_status_approval">Status Approval</label>
              <div class="col-sm-9">
                <div class="form-group label-floating " id="input_status_approval_error_container">
                  <!-- <label class="control-label" id="input_status_approval_error_detail"></label> -->
                  <!-- <input type="text"  class="form-control" id="input_status_approval" name="input_status_approval" /> -->
                  <select class="form-control" id="input_status_approval" name="input_status_approval" >
                    <?php
                    $status = array(0 => "Belum", 1 => "Ya", 2 => "Tidak");

                    foreach ($status as $key => $value) {
                      $selected = ($key == 0) ? "selected" : "" ;
                      echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
                    }
                    ?>
                  </select>
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_status_approval_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

          </div>
          <div class="col-md-5">
            <div class="row">
              <div class="text-center">
                <div class="input-group" >
                  <span class="input-group-btn" style="border: 1px solid none; margin: 0; padding: 0">
                    <span class="btn btn-link btn-file border border-success" style="margin: 0; height: 36px; padding-top: 10%;">
                      <b class="fa fa-search" style="border: 1px solid none;"></b> <input class="form-control" type="file" id="imgInp" name="imgInp" >
                    </span>
                  </span>
                  <input id="attachment-label" type="text" class="form-control" readonly style="border: 1px solid none; padding-top: 5%;" placeholder=" Pilih file attachment">
                </div>
                <p class="text-muted">File : JPG/JPEG/PNG, Max 1mb</p>
                <hr>
                <!-- <img id='img-upload' style="max-width: 50%"/> -->
                <div class="card text-center" >
                  <img  id="img-upload" >
                  <div class="card-body text-center">
                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                      <button id="btn-hps-attachment" type="button" class="btn btn-sm btn-danger btn-link" title="Hapus file" style="display: none;"
                      onclick="hapus_file_attachment()"><i class="material-icons">clear</i> Hapus lampiran</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>

      </div>
      <div class="card-footer">
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
