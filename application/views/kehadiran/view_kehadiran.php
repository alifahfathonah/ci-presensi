<?php $this->session->userdata['page_title'] = "Data Kehadiran"; ?>
<div class="row" style="">
  <div class="col-lg-12 col-md-12">
    <div class="card">

      <div class="card-header card-header-info d-flex flex-row align-items-center">
        <h4 class="card-title">Data Kehadiran</h4>
      </div>
      <div class="card-body ">
        <div class="row">
          <div class="col-md-12">
            <div class="btn-group" role="group" aria-label="Basic example">
              <button onclick="open_filter()" title="Filter" type="button" class="btn btn-sm btn-outline-info" ><b class="fa fa-filter"></b></button>
              <button onclick="export_dept()" title="Export" type="button" class="btn btn-sm btn-outline-info" >Export</button>
            </div>
          </div>
          <div class="col-md-12">
              <!-- <h4 class="text-center display-4" style="font-size: 20px">Data Kehadiran Periode</h4> -->
              <h4 id="judul_page_kehadirankaryawan" class="text-center display-4" style="font-size: 20px">Data Kehadiran</h4>
              <div id="table-scroll" class="table-scroll">

              </div>

            </div>
          </div>
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
              <h4 class="card-title">Silahkan Pilih</h4>
            </div>

            <div class="card-body ">
              <form method="post" id="form-filter">
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

            </div>
            <div class="card-footer">
              <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><b class="fa fa-close"></b></button>
              <button type="button" class="btn btn-success btn-sm" onclick="load_data()" >Proses</button>
            </div>
          </form>
          </div>
        </div>
      </div>
    </div>
  </div>
