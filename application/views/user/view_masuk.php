<?php $this->session->userdata['page_title'] = "Presensi Masuk"; ?>
<div class="col-md-12" style="padding:0; margin: 0;">
  <div class="card ">
    <div class="card-header card-header-success card-header-text">
      <div class="card-icon">
        <i class="material-icons">my_location</i>
      </div>
      <h4 class="card-title">Presensi Masuk</h4>
    </div>

    <div class="card-body ">
      <form method="post" class="form-horizontal" id="form-presensi-masuk" enctype="multipart/form-data" action="<?php echo base_url('user/masuk'); ?>">
        <div class="row">
          <div class="col-md-12">

            <div class="row">
              <div class="col-md-12">
                <button type="button" name="btn-refresh-map" onclick="initMap()" style="width: 100%"  class="btn btn-sm btn-outline-info"><b class="fa fa-refresh"></b> Refresh Lokasi</button>
              </div>
            </div>

            <div class="row">
              <div class="col-md-8">
                <div id="map" style="height: 150px">

                </div>
              </div>
              <div class="col-md-4">
                <input type="hidden" id="status_lock" value="<?php echo $karyawan['lock_area']; ?>">
                <label id="label-lokasi" class="text-dark">Lokasi : </label>
                <input type="hidden" name="input_lokasi" id="input_lokasi">
                <input type="hidden" name="input_lat" id="input_lat">
                <input type="hidden" name="input_long" id="input_long">
                
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <button type="button" name="btn-kamera" id="btn-kamera" class="btn btn-lg btn-outline-success" style="width: 100%" onclick='$("#imgInp").trigger("click");'>
                  <b class="fa fa-camera"></b> Ambil Foto
                </button>
                <input class="form-control" type="file" id="imgInp" name="imgInp" accept="image/*" capture style="display: none">
              </div>
              <div class="col-md-12 text-center">
                <input type="text" class="form-control" readonly style="border: 1px solid none; display: none"  >
                <img id='img-upload' style="max-width: 50%;"/>
                <input type="hidden" name="input_img_base64" id="input_img_base64">
              </div>
            </div>
            
            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_keterangan">Keterangan</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_keterangan_error_container">
                  <textarea class="form-control" id="input_keterangan" name="input_keterangan" rows="1" ></textarea>
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
      <button type="submit" class="btn btn-success btn-sm  ml-auto" id="btn-save-upload" style="display: none;">

      </button>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
    save_method = "add";
</script>
