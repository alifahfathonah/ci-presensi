<?php $this->session->userdata['page_title'] = "Data Jenis Kantor"; ?>
<div class="col-md-12">
  <div class="card ">
    <div class="card-header card-header-success card-header-text">
      <div class="card-icon">
        <i class="material-icons">post_add</i>
      </div>
      <h4 class="card-title">Form Tambah Data Jenis Kantor</h4>
    </div>

    <div class="card-body ">
      <form method="post" class="form-horizontal" id="form-jeniskantor" onSubmit="return false;">
        <div class="row">
          <div class="col-md-12">

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_nama_jeniskantor">Nama Jenis Kantor</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_nama_jeniskantor_error_container">
                  <label class="control-label" id="input_nama_jeniskantor_error_detail"></label>
                  <input type="text"  class="form-control" id="input_nama_jeniskantor" name="input_nama_jeniskantor" />
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_nama_jeniskantor_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

          </div>

        </div>

    </div>
    <div class="card-footer">
      <button type="submit" class="btn btn-success btn-sm  ml-auto" id="btn-save">
          <!-- <i class="fa fa-save"></i> Save --> SIMPAN
      </button>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
    save_method = "add";
</script>
