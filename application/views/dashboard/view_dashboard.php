<?php $this->session->userdata['page_title'] = "Dashboard"; ?>
<div class="row">
  <div class="col-lg-4 col-md-6 col-sm-6">
    <div class="card card-stats">
      <div class="card-header card-header-success card-header-icon">
        <div class="card-icon" onclick="open_modal_presensi_hari_ini()" style="cursor: pointer;" title="Klik utk lihat data">
          <i class="material-icons">alarm_on</i>
        </div>
        <p class="card-category">Presensi Hari Ini</p>
        <h3 class="card-title"><?php echo count($presensi_hari_ini); ?>
          <small>Orang</small>
        </h3>
      </div>
      <div class="card-footer">
        <!-- <div class="stats">
        <i class="material-icons">date_range</i> Last 24 Hours
      </div> -->
    </div>
  </div>
</div>
<div class="col-lg-4 col-md-6 col-sm-6">
  <div class="card card-stats">
    <div class="card-header card-header-warning card-header-icon">
      <div class="card-icon" onclick="open_modal_belum_hadir_hari_ini()" style="cursor: pointer;" title="Klik utk lihat data">
        <i class="material-icons">help_outline</i>
      </div>
      <p class="card-category">Belum Hadir</p>
      <h3 class="card-title"><?php echo count($belum_absen); ?>
        <small>Orang</small>
      </h3>
    </div>
    <div class="card-footer">
      <!-- <div class="stats">
      <i class="material-icons text-danger">warning</i>
      <a href="javascript:;">Get More Space...</a>
    </div> -->
  </div>
</div>
</div>

<div class="col-lg-4 col-md-6 col-sm-6">
  <div class="card card-stats">
    <div class="card-header card-header-info card-header-icon">
      <div class="card-icon" onclick="open_modal_izin_hari_ini()" style="cursor: pointer;" title="Klik utk lihat data">
        <i class="material-icons">content_copy</i>
      </div>
      <p class="card-category">Izin/Cuti/Sakit</p>
      <h3 class="card-title"><?php echo count($izin); ?>
        <small>Orang</small>
      </h3>
    </div>
    <div class="card-footer">
      <!-- <div class="stats">
      <i class="material-icons">update</i> Just Updated
    </div> -->
  </div>
</div>
</div>
</div>

<div class="row">
  <div class="col-lg-12 col-md-12">
    <div class="card">
      <div class="card-header card-header-warning">
        <h4 class="card-title">Data Karyawan</h4>
        <p class="card-category"><?php echo formatTglIndo(Date("Y-m-d")); ?></p>
      </div>
      <div class="card-body table-responsive">
        <table class="table table-hover table-striped" id="table_karyawan" style="width: 100%">
          <thead class="text-info small">
            <tr>
              <th style="width: 5%">No.</th>
              <th style="width: 10%">Nomor Induk</th>
              <th style="width: 15%">Nama</th>
              <th style="width: 10%">Departemen</th>
              <th style="width: 20%">Jabatan</th>
              <th style="width: 5%">Status</th>
              <th style="width: 5%">No. Telp</th>
              <th style="width: 5%">#</th>
            </tr>
          </thead>

            <tbody>
            </tbody>

          </table>
        </div>
      </div>
    </div>
  </div>


  <div id="modal_today_attendance" class="modal fade" tabindex="-1" role="dialog" style="padding-right: 17px;" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" style="padding-right: 17px;">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close<div class="ripple-container"></div></button>
        </div>
      </div>
    </div>
  </div>
