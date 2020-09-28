<?php $this->session->userdata['page_title'] = "Data Shift Karyawan"; ?>
<div class="row" style="">
  <div class="col-lg-12 col-md-12">
    <div class="card">

      <div class="card-header card-header-info d-flex flex-row align-items-center">
        <h4 class="card-title">Data Shift Karyawan</h4>
      </div>
      <div class="card-body ">
        <div class="row">
          <div class="col-md-12">
            <!-- <div class="btn-group" role="group" aria-label="Basic example">
              <button onclick="open_filter()" title="Filter" type="button" class="btn btn-sm btn-outline-info" ><b class="fa fa-filter"></b></button>
              <button onclick="open_form()" title="Filter" type="button" class="btn btn-sm btn-outline-info" ><b class="fa fa-plus"></b></button>
              <button onclick="open_modal_import()" title="Export" type="button" class="btn btn-sm btn-outline-info" >Import</button>
              <button onclick="export_dept()" title="Export" type="button" class="btn btn-sm btn-outline-info" >Export</button>
            </div> -->

            <div class="btn-group btn-group-sm ml-auto" role="group" aria-label="Basic example">
              <button onclick="open_filter()" title="Filter" type="button" class="btn btn-sm btn-outline-info" ><b class="fa fa-filter"></b></button>
              <button onclick="open_form()" title="Filter" type="button" class="btn btn-sm btn-outline-info" ><b class="fa fa-plus"></b></button>
              <div class="btn-group" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Tools
                <div class="ripple-container"></div></button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" x-placement="bottom-start" style="position: absolute; top: 29px; left: -34px; will-change: top, left;">
                  <button class="dropdown-item" onclick="open_modal_import()" title="Export" type="button"  >Import</button>
                  <!-- <button class="dropdown-item" onclick="export_dept()" title="Export" type="button" >Export</button> -->
                  <button class="dropdown-item text-danger" onclick="open_form_delete()" title="Export" type="button" >Delete</button>
                </div>
              </div>
            </div>

          </div>
          <div class="col-md-12">
            <h4 id="judul_page_shiftkaryawan" class="text-center display-4" style="font-size: 20px">Data Shift Karyawan</h4>
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
              <button type="button" class="btn btn-success btn-sm" onclick="load_data()">Cari</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="modal" tabindex="-1" role="dialog" id="modal-import" >
  <div class="modal-dialog modal-lg" role="document"  style="background: none;">
    <div class="modal-content"  style="background: none; box-shadow: none; border: none">
      <div class="modal-body">
        <div class="card " style="box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.5);">

          <div class="card-header card-header-info card-header-icon d-flex flex-row">
            <div class="card-icon">
              <i class="material-icons">filter_alt</i>
            </div>
            <h4 class="card-title">Import Data</h4>
          </div>

          <div class="card-body ">
            <div class="row">
              <div class="col-md-6">
                <form method="post" id="form-filter">
                  <div class="row ">
                    <label class="col-sm-6 col-form-label" for="input_departemen_filter">Departemen</label>
                    <div class="col-sm-6">
                      <div class="form-group label-floating " id="input_departemen_filter_error_container">
                        <select class="form-control" id="input_departemen_filter_2" name="input_departemen_filter_2" >
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
                        <input type="text" class="form-control datetimepicker" id="input_tanggal_awal_filter_2" name="input_tanggal_awal_filter_2"/>
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
                        <input type="text" class="form-control datetimepicker" id="input_tanggal_akhir_filter_2" name="input_tanggal_akhir_filter_2"/>
                        <span class="form-control-feedback">
                          <i class="material-icons" id="input_tanggal_akhir_filter_error_icon"></i>
                        </span>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <!-- <label class="col-sm-6 col-form-label" for="input_tanggal_akhir_filter"></label> -->
                    <div class="col-sm-12 text-center">
                      <a role="button" name="button" class="btn btn-sm btn-primary text-light" onclick="download_template()"><b class="fa fa-download"></b> Download Template Upload Shift Karyawan </a>
                    </div>
                  </div>

                </form>
                  <!-- <a href="<?php echo base_url('karyawan/downladTemplate'); ?>"><b class="fa fa-download"></b> Download Template Upload Karyawan</a>
                  <div id="alert-import-message"></div><form method="POST" action="<?php echo base_url() ?>karyawan/do_import" enctype="multipart/form-data" id="form-import"> -->
              </div>
              <div class="col-md-6">
                <form method="POST" action="<?php echo base_url() ?>karyawan/do_import" enctype="multipart/form-data" id="form-import">
                  <input type="file" name="userfile" class="form-control">
              </div>
            </div>
          </div>
          <div class="card-footer">
              <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><b class="fa fa-close"></b></button>
              <button type="button" class="btn btn-success btn-sm" onclick="do_import()">UPLOAD</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="modal" tabindex="-1" role="dialog" id="modal-form" >
  <div class="modal-dialog" role="document"  style="background: none;">
    <div class="modal-content"  style="background: none; box-shadow: none; border: none">
      <div class="modal-body">
        <div class="card " style="box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.5);">

          <div class="card-header card-header-info card-header-icon d-flex flex-row">
            <div class="card-icon">
              <i class="material-icons">post_add</i>
            </div>
            <h4 class="card-title">Tambah Data Shift Karyawan</h4>
          </div>

          <div class="card-body ">
            <form method="post" id="form-add">
              <input type="hidden" name="id">
              <div class="row ">
                <label class="col-sm-3 col-form-label" for="input_departemen">Departemen</label>
                <div class="col-sm-9">
                  <div class="form-group label-floating " id="input_departemen_error_container">
                    <select class="form-control" id="input_departemen" name="input_departemen" onchange="getKaryawanByDept()">
                      <option value="x" selected>-- Silahkan Pilih --</option>
                      <?php
                      foreach ($departemen as $r) {
                        echo '<option value="'.$r->id.'"> '.$r->nama_departemen.'</option>';
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
                    <select class="form-control" id="input_karyawan" name="input_karyawan" >
                      <option value="x" selected>-- Silahkan Pilih --</option>
                    </select>
                    <span class="form-control-feedback">
                      <i class="material-icons" id="input_karyawan_error_icon"></i>
                    </span>
                    <label class="control-label text-danger" id="input_karyawan_sisa_cuti" style="display: none"></label>
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-sm-3 col-form-label" for="input_tanggal_form">Tanggal</label>
                <div class="col-sm-9">
                  <div class="form-group label-floating " id="input_tanggal_form_error_container">
                    <label class="control-label" id="input_tanggal_form_error_detail"></label>
                    <input type="text" class="form-control datetimepicker" id="input_tanggal_form" name="input_tanggal_form"/>
                    <span class="form-control-feedback">
                      <i class="material-icons" id="input_tanggal_form_error_icon"></i>
                    </span>
                  </div>
                </div>
              </div>

              <div class="row ">
                <label class="col-sm-3 col-form-label" for="input_kode_shift">Jenis Shift</label>
                <div class="col-sm-9">
                  <div class="form-group label-floating " id="input_kode_shift_error_container">
                    <select class="form-control" id="input_kode_shift" name="input_kode_shift" >
                      <option value="x" selected>-- Silahkan Pilih --</option>
                      <?php
                      foreach ($shift as $r) {
                        echo '<option value="'.$r->id.'"> ('.$r->kode.') '.$r->nama_shift.'</option>';
                      }
                      ?>
                    </select>
                    <span class="form-control-feedback">
                      <i class="material-icons" id="input_kode_shift_error_icon"></i>
                    </span>
                  </div>
                </div>
              </div>

            </div>
            <div class="card-footer">
              <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><b class="fa fa-close"></b></button>
              <button type="submit" class="btn btn-success btn-sm" id="btn-save">Simpan</button>
              <button type="submit" class="btn btn-link text-danger btn-sm" id="btn-hapus" onclick="hapus_shift()" style="display: none">Hapus</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="modal" tabindex="-1" role="dialog" id="modal-delete" >
  <div class="modal-dialog" role="document"  style="background: none;">
    <div class="modal-content"  style="background: none; box-shadow: none; border: none">
      <div class="modal-body">
        <div class="card " style="box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.5);">

          <div class="card-header card-header-info card-header-icon d-flex flex-row">
            <div class="card-icon">
              <i class="material-icons">post_add</i>
            </div>
            <h4 class="card-title">Hapus Data Shift Karyawan</h4>
          </div>

          <div class="card-body ">
            <form method="post" id="form-delete">
              <input type="hidden" name="id_delete">
              <div class="row ">
                <label class="col-sm-3 col-form-label" for="input_departemen_delete">Departemen</label>
                <div class="col-sm-9">
                  <div class="form-group label-floating " id="input_departemen_delete_error_container">
                    <select class="form-control" id="input_departemen_delete" name="input_departemen_delete" onchange="getKaryawanByDeptDelete()">
                      <option value="x" selected>-- Silahkan Pilih --</option>
                      <?php
                      foreach ($departemen as $r) {
                        echo '<option value="'.$r->id.'"> '.$r->nama_departemen.'</option>';
                      }
                      ?>
                    </select>
                    <span class="form-control-feedback">
                      <i class="material-icons" id="input_departemen_delete_error_icon"></i>
                    </span>
                  </div>
                </div>
              </div>

              <div class="row ">
                <label class="col-sm-3 col-form-label" for="input_karyawan_delete">Karyawan</label>
                <div class="col-sm-9">
                  <div class="form-group label-floating " id="input_karyawan_delete_error_container">
                    <select class="form-control" id="input_karyawan_delete" name="input_karyawan_delete" >
                      <option value="x" selected>-- Silahkan Pilih --</option>
                    </select>
                    <span class="form-control-feedback">
                      <i class="material-icons" id="input_karyawan_delete_error_icon"></i>
                    </span>
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-sm-3 col-form-label" for="input_tanggal_start_delete">Dari</label>
                <div class="col-sm-9">
                  <div class="form-group label-floating " id="input_tanggal_start_delete_error_container">
                    <label class="control-label" id="input_tanggal_start_delete_error_detail"></label>
                    <input type="text" class="form-control datetimepicker" id="input_tanggal_start_delete" name="input_tanggal_start_delete"/>
                    <span class="form-control-feedback">
                      <i class="material-icons" id="input_tanggal_start_delete_error_icon"></i>
                    </span>
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-sm-3 col-form-label" for="input_tanggal_end_delete">Sampai</label>
                <div class="col-sm-9">
                  <div class="form-group label-floating " id="input_tanggal_end_delete_error_container">
                    <label class="control-label" id="input_tanggal_end_delete_error_detail"></label>
                    <input type="text" class="form-control datetimepicker" id="input_tanggal_end_delete" name="input_tanggal_end_delete"/>
                    <span class="form-control-feedback">
                      <i class="material-icons" id="input_tanggal_end_delete_error_icon"></i>
                    </span>
                  </div>
                </div>
              </div>

            </div>
            <div class="card-footer">
              <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><b class="fa fa-close"></b></button>
              <button type="submit" class="btn btn-link text-danger btn-sm" id="btn-hapus-form">Hapus</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
