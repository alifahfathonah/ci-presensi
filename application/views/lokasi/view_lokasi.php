<?php $this->session->userdata['page_title'] = "Data Lokasi"; ?>
<div class="row" style="">
<div class="col-lg-12 col-md-12">
  <div class="card">

    <div class="card-header card-header-info d-flex flex-row align-items-center">
          <h4 class="card-title">Data Lokasi Absen</h4>
          <div class="btn-group btn-group-sm ml-auto" role="group" aria-label="Basic example">
            <a role="button" target="_self" class="btn btn-sm btn-secondary text-success" href="<?php echo base_url('lokasi/add');?>"><b class="fa fa-plus"></b> Tambah</a>
          </div>

    </div>
    <div class="card-body table-responsive">

      <div id="map-container" class="z-depth-1-half map-container mb-5" style="height: 400px">
        <!-- <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.180050098429!2d109.23811962895113!3d-7.445322248232697!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e655dd4194172d1%3A0xc5c22517967c8acf!2sPT.Hablun%20Citramas%20Persada%20Purwokerto!5e0!3m2!1sid!2sid!4v1595695152419!5m2!1sid!2sid"
        width="100%" height="100%" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe> -->
      </div>

      <table class="table table-hover" id="table_lokasi">
        <thead class="text-info">
          <tr>
            <th>No.</th>
            <th>Nama Lokasi</th>
            <th>Latitude</th>
            <th>Longitude</th>
            <th>Radius</th>
            <th>#</th>
          </tr>
        </thead>

          <tbody>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
          </tbody>

        </table>
      </div>
    </div>
  </div>
</div>
