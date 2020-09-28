<?php $this->session->userdata['page_title'] = "Data Kehadiran"; ?>
<div class="row" style="">
  <div class="col-lg-12 col-md-12">
    <div class="card">
      <div class="card-header card-header-success card-header-text">
        <div class="card-icon">
          <i class="material-icons">assignment</i>
        </div>
        <h4 class="card-title">Detail Kehadiran</h4>
      </div>

      <div class="card-body table-responsive">
        <div class="row">
          <div class="col-md-6">
            Nama : <?php echo $nama_lengkap; ?> <br>
            Departemen : <?php echo $nama_departemen; ?> <br>
            Tanggal : <?php echo formatTglIndo($tanggal); ?>
          </div>
          <div class="col-md-6">
            Jadwal Kerja : <?php echo $jam_kerja; ?> <br>
            Status Kehadiran : <?php echo $status_kehadiran; ?> <br>
            Terlambat : <?php echo $terlambat; ?>
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
        Jam : <?php echo $jam_masuk; ?><br>
        Lokasi : <?php echo $lokasi_masuk; ?><br>
        Keterangan : <?php echo $keterangan_masuk; ?><br>
        <hr>
        Lokasi Presensi
        <?php $loc_masuk = explode(",", $kordinat_masuk)?>
        <input type="hidden" id="lat_masuk" value="<?php echo $loc_masuk[0]; ?>">
        <input type="hidden" id="lon_masuk" value="<?php echo $loc_masuk[1]; ?>">
        <input type="hidden" id="lokasi_masuk" value="<?php echo $lokasi_masuk; ?>">
        <div id="map-container-masuk" class="z-depth-1-half map-container mb-5" style="height: 400px">

        </div>
        <div class="text-center">
          <?php
          if($foto_masuk_text !== "" && $foto_masuk_text !== "-"){
            // if( file_exists('uploads/foto_presensi/'.$foto_masuk) ) {
            //   $img_url = base_url('uploads/foto_presensi/'.$foto_masuk);
            // } else {
            //   $img_url = "https://hcp1.co.id/api/public/uploads/".$foto_masuk;
            // }
            $img_url =  $foto_masuk_text;
          } else {
            $img_url = base_url('uploads/test_foto_absen.jpg');
          }
          ?>
          <img src="<?php echo $img_url; ?>" class="img img-thumbnail" style='max-width:40%'>
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
        Jam : <?php echo $jam_pulang; ?><br>
        Lokasi : <?php echo $lokasi_pulang; ?><br>
        Keterangan : <?php echo $keterangan_pulang; ?><br>
        <hr>
        Lokasi Presensi
        <?php if($kordinat_pulang == "") {?>
            <input type="hidden" id="lat_pulang" value="0">
            <input type="hidden" id="lon_pulang" value="0">
            <input type="hidden" id="lokasi_pulang" value="Tidak/Belum Absen Pulang">
            <div id="map-container-pulang" class="z-depth-1-half map-container mb-5" style="height: 400px">
            </div>
        <?php } else { ?>
            <?php $loc_pulang = explode(",", $kordinat_pulang)?>
            <input type="hidden" id="lat_pulang" value="<?php echo $loc_pulang[0]; ?>">
            <input type="hidden" id="lon_pulang" value="<?php echo $loc_pulang[1]; ?>">
            <input type="hidden" id="lokasi_pulang" value="<?php echo $lokasi_masuk; ?>">
            <div id="map-container-pulang" class="z-depth-1-half map-container mb-5" style="height: 400px">
            </div>
        <?php } ?>
        

        
        
        <div class="text-center">
          <?php
          if($foto_pulang_text !== "" && $foto_pulang_text !== "-"){
            // if( file_exists('uploads/foto_presensi/'.$foto_pulang) ) {
            //   $img_url = base_url('uploads/foto_presensi/'.$foto_pulang);
            // } else {
            //   $img_url = "https://hcp1.co.id/api/public/uploads/".$foto_pulang;
            // }
            $img_url =  $foto_pulang_text;
          } else {
            $img_url = base_url('uploads/test_foto_absen.jpg');
          }
          ?>
          <img src="<?php echo $img_url; ?>" class="img img-thumbnail" style='max-width:40%'>
        </div>
      </div>
    </div>
  </div>
</div>
