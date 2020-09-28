<?php $this->session->userdata['page_title'] = "Data Izin"; ?>
<div class="col-md-12">
  <div class="card ">
    <div class="card-header card-header-success card-header-text">
      <div class="card-icon">
        <i class="material-icons">post_add</i>
      </div>
      <h4 class="card-title">Form Edit Data Izin</h4>
    </div>

    <div class="card-body ">
      <form method="post" class="form-horizontal" id="form-izin" onSubmit="return false;">
        <div class="row">
          <div class="col-md-7">
            <input type="hidden" name="id" id="id" value="<?php echo $record['id']; ?>">
            <!-- <div class="row">
              <label class="col-sm-3 col-form-label" for="input_nama_karyawan">Nama Karyawan</label>
              <div class="col-sm-9">
                <div class="form-group label-floating " id="input_nama_karyawan_error_container">
                  <label class="control-label" id="input_nama_karyawan_error_detail"></label>
                  <input type="text" class="form-control typeahead" id="input_nama_karyawan" name="input_nama_karyawan" value="<?php echo $record['nama_karyawan']; ?>">
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_nama_karyawan_error_icon"></i>
                  </span>
                </div>
              </div>
            </div> -->

            <div class="row ">
              <label class="col-sm-3 col-form-label" for="input_departemen">Departemen</label>
              <div class="col-sm-9">
                <div class="form-group label-floating " id="input_departemen_error_container">
                  <select class="form-control" id="input_departemen" name="input_departemen" onchange="getKaryawanByDept()">
                    <option value="x" >-- Silahkan Pilih --</option>
                    <?php
                    foreach ($departemen as $r) {
                      $selected = ($r->id == $record['id_departemen']) ? "selected" : "" ;
                      echo '<option '.$selected.' value="'.$r->id.'"> '.$r->nama_departemen.'</option>';
                    }
                    ?>
                  </select>
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
                  <select class="form-control" id="input_karyawan" name="input_karyawan" onchange="validasi_cuti()">
                    <option value="x">-- Silahkan Pilih --</option>
                    <?php
                    foreach ($karyawan as $r) {
                      $selected = ($r->id == $record['id_karyawan']) ? "selected" : "" ;
                      echo '<option '.$selected.' value="'.$r->id.'"> '.$r->nama_lengkap.'</option>';
                    }
                    ?>
                  </select>
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
                      $selected = ($record['id_jenis_izin'] == $r->id ) ? "selected" : "" ;
                      echo '<option '.$selected.' value="'.$r->id.'">('.$r->kode.') '.$r->nama_izin.'</option>';
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
                  <?php
                    $originalDate = $record['tanggal_awal'];
                    $tgl_awal = date("d-m-Y", strtotime($originalDate));
                  ?>
                  <input type="text" class="form-control datetimepicker" id="input_tanggal_awal" name="input_tanggal_awal" value="<?php echo $tgl_awal; ?>"/>
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
                  <?php
                    $originalDate = $record['tanggal_akhir'];
                    $tgl_akhir = date("d-m-Y", strtotime($originalDate));
                  ?>
                  <input type="text" class="form-control datetimepicker" id="input_tanggal_akhir" name="input_tanggal_akhir" value="<?php echo $tgl_akhir; ?>"/>
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
                  <textarea class="form-control" id="input_keterangan" name="input_keterangan" rows="2" ><?php echo $record['keterangan']; ?></textarea>
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
                      // $selected = ($key == 0) ? "selected" : "" ;
                      $selected = ($record['status_approval'] == $key ) ? "selected" : "" ;
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
                  <input id="attachment-label" type="text" class="form-control" readonly style="border: 1px solid none; padding-top: 5%;" placeholder="<?php echo $record['attachment']; ?>">
                </div>
                <p class="text-muted">File : JPG/JPEG/PNG, Max 1mb</p>
                <hr>
                <?php
                    // if($record['attachment'] !== null){
                    //   $src = base_url().'uploads/attachment/'.$record['attachment'];
                    // } else {
                    //   $src = "";
                    // }
                ?>
                
                <?php
                  if($record['attachment'] !== null){
                    if( file_exists('uploads/foto_presensi/'.$record['attachment']) ) {
                      $src = base_url('uploads/foto_presensi/'.$record['attachment']);
                    } else {
                      $src = "https://hcp1.co.id/api/public/uploads/".$record['attachment'];
                    }
                  } else {
                    $src = "";
                  }
                 ?>
          
                <!-- <img id='img-upload' style="max-width: 50%" src="<?php echo $src; ?>" /> -->
                <input type="hidden" name="old_image" value="<?php echo $record['attachment']; ?>">

                <div class="card text-center" >
                  <img src="<?php echo $src; ?>" alt="" id="img-upload" >
                  <div class="card-body text-center">
                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                      <?php
                      echo '<div id="btn-hps-foto-container">';
                      if($record['attachment'] != null){
                        echo '<button id="btn-hps-attachment" type="button" class="btn btn-sm btn-danger btn-link" title="Hapus file"
                        onclick="hapus_file_attachment(\''. $record['id'].'\' , \'' . $record['attachment'] . '\')"><i class="material-icons">clear</i> Hapus lampiran</button>';
                      }
                      echo '</div>';
                      ?>
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
save_method = "edit";
</script>
