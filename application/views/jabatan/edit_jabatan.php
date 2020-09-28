<?php $this->session->userdata['page_title'] = "Data Jabatan"; ?>
<div class="col-md-12">
  <div class="card ">
    <div class="card-header card-header-success card-header-text">
      <div class="card-icon">
        <i class="material-icons">post_add</i>
      </div>
      <h4 class="card-title">Form Edit Data Jabatan</h4>
    </div>

    <div class="card-body ">
      <form method="post" class="form-horizontal" id="form-jabatan" onSubmit="return false;">
        <div class="row">
          <div class="col-md-12">
            <input type="hidden" name="id" value="<?php echo $record['id']; ?>">

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_nama_jabatan">Nama Jabatan</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_nama_jabatan_error_container">
                  <label class="control-label" id="input_nama_jabatan_error_detail"></label>
                  <input type="text"  class="form-control" id="input_nama_jabatan" name="input_nama_jabatan" value="<?php echo $record['nama_jabatan']; ?>"/>
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_nama_jabatan_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_keterangan">Keterangan</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_keterangan_error_container">
                  <textarea class="form-control" id="input_keterangan" name="input_keterangan" rows="2" ><?php echo $record['keterangan']; ?></textarea>
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
      <button type="button" class="btn btn-link btn-sm text-danger" id="btn-hapus" onclick="hapus_data('<?php echo $record['id']; ?>')">
          <b class="fa fa-trash"></b> Hapus Data
      </button>
      <button type="submit" class="btn btn-success btn-sm  ml-auto" id="btn-save">
          <!-- <i class="fa fa-save"></i> Save --> UPDATE
      </button>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
    save_method = "edit";
</script>
