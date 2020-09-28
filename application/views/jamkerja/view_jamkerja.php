<?php $this->session->userdata['page_title'] = "Data Jam Kerja"; ?>
<div class="row" style="">
  <div class="col-lg-12 col-md-12">
    <div class="card">

      <div class="card-header card-header-info d-flex flex-row align-items-center">
        <h4 class="card-title">Data Jam Kerja</h4>
      </div>

      <div class="card-body table-responsive">
        <div class="col-md-12">
          <table class="table table-hover" id="table_jamkerja">
            <thead class="text-info">
              <tr>
                <th>Hari</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>#</th>
              </tr>
            </thead>
            <tbody>

            </tbody>

          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="modal-edit">
  <div class="modal-dialog" role="document" style="background: none;">
    <div class="modal-content" style="background: none; box-shadow: none; border: none">
      <div class="modal-body">
        <div class="card " style="box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.5);">
          <div class="card-header card-header-rose card-header-icon">
            <div class="card-icon">
              <i class="material-icons">access_time</i>
            </div>
            <h4 class="card-title">Jam Kerja</h4>
          </div>
          <div class="card-body ">
            <form method="post" id="form-jamkerja">
              <input type="hidden" name="id" id="id">
              <div class="row">
                <label class="col-sm-4 col-form-label" for="input_jam_masuk">Jam Masuk</label>
                <div class="col-sm-8">
                  <div class="form-group label-floating " id="input_jam_masuk_error_container">
                    <label class="control-label" id="input_jam_masuk_error_detail"></label>
                    <input type="text"  class="form-control" id="input_jam_masuk" name="input_jam_masuk"  />
                    <span class="form-control-feedback">
                      <i class="material-icons" id="input_jam_masuk_error_icon" style="display: none"></i>
                    </span>
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-sm-4 col-form-label" for="input_jam_pulang">Jam Pulang</label>
                <div class="col-sm-8">
                  <div class="form-group label-floating " id="input_jam_pulang_error_container">
                    <label class="control-label" id="input_jam_pulang_error_detail"></label>
                    <input type="text"  class="form-control" id="input_jam_pulang" name="input_jam_pulang"  />
                    <span class="form-control-feedback">
                      <i class="material-icons" id="input_jam_pulang_error_icon" style="display: none"></i>
                    </span>
                  </div>
                </div>
              </div>
          </div>
          <div class="card-footer ">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success btn-sm" id="btn-save">Simpan</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
