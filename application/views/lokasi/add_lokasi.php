<?php $this->session->userdata['page_title'] = "Data Lokasi"; ?>
<div class="col-md-12">
  <div class="card ">
    <div class="card-header card-header-success card-header-text">
      <div class="card-icon">
        <i class="material-icons">post_add</i>
      </div>
      <h4 class="card-title">Form Tambah Data Lokasi Absen</h4>
    </div>

    <div class="card-body ">
      <form method="post" class="form-horizontal" id="form-lokasi" onSubmit="return false;">
        <div class="row">
          <div class="col-md-8" style="">
            <div id="map-container-add" class="col-md-12 " style="height: 400px;  margin: 0">
              <!-- <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.180050098429!2d109.23811962895113!3d-7.445322248232697!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e655dd4194172d1%3A0xc5c22517967c8acf!2sPT.Hablun%20Citramas%20Persada%20Purwokerto!5e0!3m2!1sid!2sid!4v1595695152419!5m2!1sid!2sid"
              width="100%" height="100%" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe> -->
            </div>
            <div class="col-md-12 text-danger" style="margin: 0">
              Klik lokasi di peta untuk mendapatkan koordinat latitude dan longitude
            </div>
          </div>
          <div class="col-md-4">

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_nama_lokasi">Nama Lokasi</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_nama_lokasi_error_container">
                  <label class="control-label" id="input_nama_lokasi_error_detail"></label>
                  <input type="text"  class="form-control" id="input_nama_lokasi" name="input_nama_lokasi" />
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_nama_lokasi_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_latitude">Latitude</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_latitude_error_container">
                  <label class="control-label" id="input_latitude_error_detail"></label>
                  <input type="text"  class="form-control" id="input_latitude" name="input_latitude" />
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_latitude_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_longitude">Longitude</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_longitude_error_container">
                  <label class="control-label" id="input_longitude_error_detail"></label>
                  <input type="text"  class="form-control" id="input_longitude" name="input_longitude" />
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_longitude_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_radius">Radius (meter)</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_radius_error_container">
                  <label class="control-label" id="input_radius_error_detail"></label>
                  <input type="text"  class="form-control" id="input_radius" name="input_radius" />
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_radius_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_radius"></label>
              <div class="col-sm-8">
                <button type="submit" class="btn btn-success btn-sm  ml-auto" id="btn-save">SIMPAN
                </button>
              </div>
            </div>

          </div>

        </div>

    </div>
    <div class="card-footer">

      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
    save_method = "add";
</script>
