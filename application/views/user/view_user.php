<style media="screen">
.btn-file {
  position: relative;
  overflow: hidden;
}
.btn-file input[type=file] {
  position: absolute;
  top: 0;
  right: 0;
  min-width: 100%;
  min-height: 100%;
  font-size: 100px;
  text-align: right;
  filter: alpha(opacity=0);
  opacity: 0;
  outline: none;
  background: white;
  cursor: inherit;
  display: block;
}

#img-upload{
  width: 100%;
}

#img-upload-pulang{
  width: 100%;
}
</style>

<div class="row">

    <?php if(!empty($this->session->flashdata('message'))){
      echo '<div class="col-md-12">';
      echo $this->session->flashdata('message');
      echo '</div>';
    } ?>

  <div class="col-md-12 card-avatar text-center" style="border: 1px solid none; margin-top: 20px">
    <?php
    $img_path = base_url('uploads/no_image.png');
    if($logo['image_path'] == null){
      $img_path = base_url('uploads/no_image.png');
    } else {
      $img_path = base_url('uploads/logo/'.$logo['image_path']);
    }
    ?>
    <img id="profil_img" class="img"  src="<?php echo $img_path; ?>" style="max-width: 40%">
    <div class="" style="margin-top: 10px; color: white;">
      <h1 class="text-display" style='font-size: 15px; font-weight: bold; border: 1px solid none; margin-bottom:5px'><?php echo ucwords(strtolower($karyawan['nama_lengkap'])); ?></h1>
      <h1 class="text-display" style='font-size: 12px; border: 1px solid none; margin-top: 0px'><?php echo ucwords($departemen['nama_departemen']); ?></h1>

    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="alert text-center" style="background: rgba(0, 0, 0, 0.4); margin-top: 10px; color: white">
      <div class="user_rekap">
        <div class="user_rekap_child">
          <p style="border: 1px solid none; margin-bottom: 0px; font-size: 13px;" for="">Hadir</p>
          <b style="font-size: 18px" class="fa fa-check-square-o"></b>

          <p style="border: 1px solid none; margin-bottom: 0px;  margin-top:12px; font-weight: bold" for=""><?php echo $hadir; ?></p>
          <p style="border: 1px solid none; margin-bottom: 0px; font-size: 13px;" for="">Hari</p>
        </div>

        <div class="user_rekap_child">
          <p style="border: 1px solid none; margin-bottom: 0px; font-size: 13px;" for="">Izin</p>
          <b style="font-size: 18px" class="fa fa-file-text-o"></b>

          <p style="border: 1px solid none; margin-bottom: 0px;  margin-top:12px; font-weight: bold" for=""><?php echo $izin; ?></p>
          <p style="border: 1px solid none; margin-bottom: 0px; font-size: 13px;" for="">Hari</p>
        </div>

        <div class="user_rekap_child">
          <p style="border: 1px solid none; margin-bottom: 0px; font-size: 13px;" for="">Sisa Cuti</p>
          <b style="font-size: 18px" class="fa fa-star-o"></b>

          <p style="border: 1px solid none; margin-bottom: 0px;  margin-top:12px; font-weight: bold" for=""><?php echo $sisa_cuti; ?></p>
          <p style="border: 1px solid none; margin-bottom: 0px; font-size: 13px;" for="">Hari</p>
        </div>
      </div>
      <div class="user_rekap text-center" style="margin-bottom: 0px; borde: 1px solid red">
        <div class="user_rekap_child" style=" font-size: 13px;">
          <?php echo "Jadwal hari ini : ". $title_1; ?>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-12">
    <div class="alert text-center" style="background: rgba(0, 0, 0, 0.4); margin-top: 5px; color: white; padding-bottom: 1px;  padding-top: 5px">
      Detail
      <div class="user_rekap">
        <div class="user_rekap_child" onclick="modal_presensi()">
          <p style="border: 1px solid none; margin-bottom: 0px; font-size: 13px;" for="">Kehadiran</p>
          <i class="material-icons">calendar_today</i>
        </div>

        <div class="user_rekap_child">
          <p style="border: 1px solid none; margin-bottom: 0px; font-size: 13px;" for="">Izin</p>
          <i class="material-icons">file_copy</i>
        </div>

        <div class="user_rekap_child">
          <p style="border: 1px solid none; margin-bottom: 0px; font-size: 13px;" for="">Gaji</p>
          <i class="material-icons">money</i>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="col-12 text-center" style="margin-bottom: 0px;">
  <?php echo $button; ?>
</div>

<div class="modal" tabindex="-1" role="dialog" id="modal-rekap-presensi" aria-hidden="true" >
  <div class="modal-dialog" role="document"  style="background: none;">
    <div class="modal-content"  style="background: none; box-shadow: none; border: none">
      <div class="modal-body">
        <div class="card " style="box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.5);">

          <div class="card-header card-header-info card-header-icon d-flex flex-row">
            <div class="card-icon">
              <i class="material-icons">calendar_today</i>
            </div>
            <h4 class="card-title" id="judul-modal-upload">Rekap Kehadiran</h4>
          </div>

          <div class="card-body " style="height: 100%;">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><b class="fa fa-calendar"></b></span>
              </div>
              <select class="form-control" name="input_bulan_presensi" id="input_bulan_presensi" onchange="modal_presensi()">
                <?php
                $nama_bulan = array("01" => "Januari", "02" => "Februari", "03" => "Maret", "04" => "April", "05" => "Mei", "06" => "Juni",
                "07" =>  "Juli", "08" => "Agustus", "09" => "September", "10" => "Oktober", "11" => "November", "12" => "Desember");
                foreach ($nama_bulan as $r => $value) {

                  echo '<option value="'.$r.'">'.$value.'</option>';
                }
                ?>
              </select>
              <select class="form-control" name="input_tahun_presensi" id="input_tahun_presensi" onchange="modal_presensi()">
                <?php
                $already_selected_value = date("Y");
                $earliest_year = date("Y",strtotime("-3 year"));
                foreach (range(date('Y'), $earliest_year) as $x) {
                    print '<option value="'.$x.'"'.($x === $already_selected_value ? ' selected="selected"' : '').'>'.$x.'</option>';
                }
                ?>
              </select>
            </div>
            <hr>
            <div class="container-fluid h-100" style="overflow-y: scroll; padding: 0" id="result-presensi-container">

              <!-- <div class="card" style="border: 1px solid none; margin-bottom: 5px; margin-top: 0px; margin-left: 0px; margin-right: 0px">
                <div class="card-header" style="border: 1px solid none; padding-top: 5px; padding-bottom: 5px;">
                  Selasa, 11 Agustus 2020
                </div>
                <div class="card-body" style=" padding-top: 0px; padding-bottom: 0px; font-size:10px; padding-bottom: 5px">
                  <p style="padding: 0; border: 1px solid none; margin: 1px" class="text-success">Masuk: 08:00:00 </p>
                  <p style="padding: 0; border: 1px solid none; margin: 1px" class="text-success">Lokasi: PT Hablun Citrmas Persada </p>
                  <p style="padding: 0; border: 1px solid none; margin: 1px" class="text-primary">Pulang: 08:00:00 </p>
                  <p style="padding: 0; border: 1px solid none; margin: 1px" class="text-primary">Lokasi: PT Hablun Citrmas Persada </p>
                </div>
              </div> -->

            </div>
          </div>

          <div class="card-footer text-center ">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><b class="fa fa-close"></b></button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

<style media="screen">
.user_rekap {
  display: table;
  max-width: 100%;
  table-layout: fixed;
  margin-bottom: 20px;
}

.user_rekap > .user_rekap_child {
  display: table-cell;
  /* border: 1px dotted red; */
  padding: 4px 6px;
  width: 2%;
  margin-bottom: 0;
}
</style>
