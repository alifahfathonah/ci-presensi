<?php $this->session->userdata['page_title'] = "Data Izin"; ?>
<div class="row" style="">
  <div class="col-lg-12 col-md-12">
    <div class="card">

      <div class="card-header card-header-info d-flex flex-row align-items-center">
        <h4 class="card-title">Data Izin</h4>
        <div class="btn-group btn-group-sm ml-auto" role="group" aria-label="Basic example">
          <a role="button" target="_self" class="btn btn-sm btn-secondary text-success" href="<?php echo base_url('izin/add');?>"><b class="fa fa-plus"></b> Tambah</a>
        </div>

      </div>
      <div class="card-body table-responsive">
        <?php echo $this->session->flashdata('message'); ?>
        <table class="table table-hover" id="table_izin" style="width:100%">
          <thead class="text-info">
            <tr>
              <th>No.</th>
              <th>Nama Karyawan</th>
              <th>Departemen</th>
              <th>Jenis Izin</th>
              <th>Keterangan</th>
              <th>Periode</th>
              <th>Status</th>
              <th>Attachment</th>
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


<!-- The Modal Image-->
<div class="modal fade" tabindex="-1" role="dialog" id="modal_image" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <img class="img img-thumbnail" id="imgBig"></img>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="modal-filter" >
  <div class="modal-dialog" role="document"  style="background: none;">
    <div class="modal-content"  style="background: none; box-shadow: none; border: none">
      <div class="modal-body">
        <div class="card " style="box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.5);">

          <div class="card-header card-header-info card-header-icon d-flex flex-row">
            <div class="card-icon">
              <i class="material-icons">filter_alt</i>
            </div>
            <h4 class="card-title">Filter Data</h4>
          </div>

          <div class="card-body ">
            <!-- <form method="post" id="form-filter"> -->
            <div class="row">
              <label class="col-sm-6 col-form-label" for="input_jenis_izin_filter">Jenis Izin</label>
              <div class="col-sm-6">
                <div class="form-group label-floating " id="input_jenis_izin_filter_error_container">
                  <select class="form-control" id="input_jenis_izin_filter" name="input_jenis_izin_filter" >
                    <option value="x" selected>All</option>
                    <?php
                    foreach ($list->result() as $r) {
                      echo '<option value="'.$r->id.'">('.$r->kode.') '.$r->nama_izin.'</option>';
                    }
                    ?>
                  </select>

                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_jenis_izin_filter_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row ">
              <label class="col-sm-6 col-form-label" for="input_departemen_filter">Departemen</label>
              <div class="col-sm-6">
                <div class="form-group label-floating " id="input_departemen_filter_error_container">
                  <select class="form-control" id="input_departemen_filter" name="input_departemen_filter" >
                    <option value="x" selected>All</option>
                    <?php
                    foreach ($departemen as $r) {
                      echo '<option value="'.$r->id.'"> '.$r->nama_departemen.'</option>';
                    }
                    ?>
                  </select>
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_tanggal_awal_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row ">
              <label class="col-sm-6 col-form-label" for="input_tanggal_awal_filter">Dari</label>
              <div class="col-sm-6">
                <div class="form-group label-floating " id="input_tanggal_awal_filter_error_container">
                  <label class="control-label center" id="input_tanggal_awal_filter_error_detail"></label>
                  <input type="text" class="form-control datetimepicker" id="input_tanggal_awal_filter" name="input_tanggal_awal_filter"/>
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_tanggal_awal_filter_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-6 col-form-label" for="input_tanggal_akhir_filter">Sampai</label>
              <div class="col-sm-6">
                <div class="form-group label-floating " id="input_tanggal_akhir_filter_error_container">
                  <label class="control-label" id="input_tanggal_akhir_filter_error_detail"></label>
                  <input type="text" class="form-control datetimepicker" id="input_tanggal_akhir_filter" name="input_tanggal_akhir_filter"/>
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_tanggal_akhir_filter_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-6 col-form-label" for="input_status_approval">Status Approval</label>
              <div class="col-sm-6">
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
          <div class="card-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><b class="fa fa-close"></b></button>
            <button type="button" class="btn btn-success btn-sm" >Cari</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
