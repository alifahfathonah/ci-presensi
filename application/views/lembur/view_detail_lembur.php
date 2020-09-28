<?php $this->session->userdata['page_title'] = "Data Lembur"; ?>
<div class="row" style="">
  <div class="col-lg-12 col-md-12">
    <div class="card">
      <div class="card-header card-header-success card-header-text">
        <div class="card-icon">
          <i class="material-icons">assignment</i>
        </div>
        <h4 class="card-title">Detail Lembur</h4>
      </div>

      <div class="card-body table-responsive">
        <div class="row">
          <div class="col-md-6">
            Nama : <?php echo $record['nama_lengkap']; ?> <br>
            Departemen : <?php echo $record['nama_departemen']; ?> <br>
            Tanggal : <?php echo formatTglIndo($record['tanggal']); ?>
          </div>
          <div class="col-md-6">
            <div class="row">
              <label class="col-sm-3 col-form-label" for="input_status_approval">Status Approval</label>
              <div class="col-sm-9">
                <input type="hidden" id="input_id_lembur" name="input_id_lembur" value="<?php echo $record['id']; ?>">
                  <select class="form-control" id="input_status_approval" name="input_status_approval" onchange="updateStatusApproval()" >
                    <?php
                    $status = array(0 => "Belum", 1 => "Ya", 2 => "Tidak");

                    foreach ($status as $key => $value) {
                      $selected = ($key == $record['status_approval']) ? "selected" : "" ;
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
        <hr>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card">
      <div class="card-header card-header-info d-flex flex-row align-items-center">
        <h4 class="card-title"><b class="fa fa-clock-o"></b> Jam Masuk</h4>
      </div>
      <div class="card-body table-responsive">
        Jam : <?php echo $record['jam_masuk']; ?><br>
        Lokasi : <?php echo $record['lokasi_masuk']; ?><br>
        Keterangan : <?php echo $record['keterangan_masuk']; ?><br>
        <hr>
        Lokasi Presensi
        <?php $loc_masuk = explode(",", $record['kordinat_masuk'])?>
        <input type="hidden" id="lat_masuk" value="<?php echo $loc_masuk[0]; ?>">
        <input type="hidden" id="lon_masuk" value="<?php echo $loc_masuk[1]; ?>">
        <input type="hidden" id="lokasi_masuk" value="<?php echo $record['lokasi_masuk']; ?>">
        <div id="map-container-masuk" class="z-depth-1-half map-container mb-5" style="height: 400px">

        </div>
        <div class="text-center">
          <?php
          if($record['foto_masuk'] !== "" && $record['foto_masuk'] !== "-"){
            $img_url = "https://hcp1.co.id/api/public/uploads/".$record['foto_masuk'];
          } else {
            $img_url = base_url('uploads/test_foto_absen.jpg');
          }
          ?>
          <img src="<?php echo $img_url; ?>" class="img img-thumbnail">
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card">
      <div class="card-header card-header-warning d-flex flex-row align-items-center">
        <h4 class="card-title"><b class="fa fa-clock-o"></b> Jam Pulang</h4>
      </div>
      <div class="card-body table-responsive">
        Jam : <?php echo $record['jam_pulang']; ?><br>
        Lokasi : <?php echo $record['lokasi_pulang']; ?><br>
        Keterangan : <?php echo $record['keterangan_pulang']; ?><br>
        <hr>
        Lokasi Presensi
        <?php $loc_pulang = explode(",", $record['kordinat_pulang'])?>
        <input type="hidden" id="lat_pulang" value="<?php echo $loc_pulang[0]; ?>">
        <input type="hidden" id="lon_pulang" value="<?php echo $loc_pulang[1]; ?>">
        <input type="hidden" id="lokasi_pulang" value="<?php echo $record['lokasi_pulang']; ?>">
        <div id="map-container-pulang" class="z-depth-1-half map-container mb-5" style="height: 400px">

        </div>
        <div class="text-center">
          <?php
          if($record['foto_pulang'] !== "" && $record['foto_pulang'] !== "-"){
            $img_url = "https://hcp1.co.id/api/public/uploads/".$record['foto_pulang'];
          } else {
            $img_url = base_url('uploads/test_foto_absen.jpg');
          }
          ?>
          <img src="<?php echo $img_url; ?>" class="img img-thumbnail">
        </div>
      </div>
    </div>
  </div>
</div>
