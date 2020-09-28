<?php $this->session->userdata['page_title'] = "Data Shift"; ?>
<div class="row" style="">
  <div class="col-lg-12 col-md-12">
    <div class="card">

      <div class="card-header card-header-info d-flex flex-row align-items-center">
        <h4 class="card-title">Data Shift</h4>
        <div class="btn-group btn-group-sm ml-auto" role="group" aria-label="Basic example">
          <a role="button" target="_self" class="btn btn-sm btn-secondary text-success" onclick="open_modal()" ><b class="fa fa-plus"></b> Tambah</a>
        </div>

      </div>
      <div class="card-body table-responsive">
        <?php echo $this->session->flashdata('message'); ?>
        <table class="table table-hover" id="table_shift" style="width:100%">
          <thead class="text-info">
            <tr>
              <th>No.</th>
              <th>Kode Shift</th>
              <th>Nama</th>
              <th>Jenis Shift</th>
              <th>Jam Masuk</th>
              <th>Jam Pulang</th>
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
            </tr>
          </tbody>

        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="modal-add" >
  <div class="modal-dialog" role="document"  style="background: none;">
    <div class="modal-content"  style="background: none; box-shadow: none; border: none">
      <div class="modal-body">
        <div class="card " style="box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.5);">

          <div class="card-header card-header-info card-header-icon d-flex flex-row">
            <div class="card-icon">
              <i class="material-icons">filter_alt</i>
            </div>
            <h4 class="card-title">Tambah Data</h4>
          </div>

          <div class="card-body ">
            <form method="post" class="form-horizontal" id="form-shift" onSubmit="return false;">
              <input type="hidden" name="id">
              <div class="row">
                <label class="col-sm-3 col-form-label" for="input_kode_shift">Kode Shift</label>
                <div class="col-sm-9">
                  <div class="form-group label-floating " id="input_kode_shift_error_container">
                    <label class="control-label" id="input_kode_shift_error_detail"></label>
                    <input type="text" class="form-control typeahead" id="input_kode_shift" name="input_kode_shift"  >
                    <span class="form-control-feedback">
                      <i class="material-icons" id="input_kode_shift_error_icon"></i>
                    </span>
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-sm-3 col-form-label" for="input_nama_shift">Nama Shift</label>
                <div class="col-sm-9">
                  <div class="form-group label-floating " id="input_nama_shift_error_container">
                    <label class="control-label" id="input_nama_shift_error_detail"></label>
                    <input type="text" class="form-control typeahead" id="input_nama_shift" name="input_nama_shift" >
                    <span class="form-control-feedback">
                      <i class="material-icons" id="input_nama_shift_error_icon"></i>
                    </span>
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-sm-3 col-form-label" for="input_jenis_shift">Jenis Shift</label>
                <div class="col-sm-9">
                  <div class="form-group label-floating " id="input_jenis_shift_error_container">
                    <div class="form-check">
                      <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="input_jenis_shift" id="input_jenis_shift_1" value="1" checked="" onclick="show_jam()"> Masuk
                        <span class="circle">
                          <span class="check"></span>
                        </span>
                      </label>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" >
                        <input class="form-check-input" type="radio" name="input_jenis_shift" id="input_jenis_shift_0" value="0" onclick="hide_jam()"> Libur
                        <span class="circle">
                          <span class="check"></span>
                        </span>
                      </label>
                    </div>

                  </div>
                </div>
              </div>

              <div id="jam_container" >
                <div class="row">
                  <label class="col-sm-3 col-form-label" for="input_jam_masuk">Jam Masuk</label>
                  <div class="col-sm-9">
                    <div class="form-group label-floating " id="input_jam_masuk_error_container">
                      <label class="control-label center" id="input_jam_masuk_awal_error_detail"></label>
                      <input type="text" class="form-control datetimepicker" id="input_jam_masuk" name="input_jam_masuk" readonly value="00:00" placeholder="Klik logo jam disamping"/>
                      <span class="form-control-feedback">
                        <i class="material-icons" id="input_jam_masuk_error_icon"></i>
                      </span>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <label class="col-sm-3 col-form-label" for="input_jam_pulang">Jam Pulang</label>
                  <div class="col-sm-9">
                    <div class="form-group label-floating " id="input_jam_pulang_error_container">
                      <label class="control-label center" id="input_jam_pulang_awal_error_detail"></label>
                      <input type="text" class="form-control datetimepicker" id="input_jam_pulang" name="input_jam_pulang" readonly value="00:00" placeholder="Klik logo jam disamping"/>
                      <span class="form-control-feedback">
                        <i class="material-icons" id="input_jam_pulang_error_icon"></i>
                      </span>
                    </div>
                  </div>
                </div>
              </div>

            </div>
            <div class="card-footer">
              <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><b class="fa fa-close"></b></button>
              <button type="submit" class="btn btn-success btn-sm" >Simpan</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
