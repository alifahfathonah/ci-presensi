<?php $this->session->userdata['page_title'] = "Data Profil"; ?>
<div class="row" style="">
  <div class="col-lg-12 col-md-12">
    <div class="card">

      <div class="card-header card-header-info d-flex flex-row align-items-center">
        <h4 class="card-title">Profil Perusahaan</h4>
      </div>
      <div class="card-body table-responsive">

        <div class="row">
          <div class="col-md-6">
            <div class="card">
              <div class="card-header card-header-icon card-header-success">
                <div class="card-icon">
                  <i class="material-icons">photo</i>
                </div>
                <h4 class="card-title">Logo Perusahaan</h4>
              </div>
              <div class="card-body">
                <div class="row">
                    <div class="ml-2 col-sm-12">
                        <div id="msg"></div>
                        <form method="post" id="image-form" enctype="multipart/form-data" onSubmit="return false;">
                            <div class="form-group">
                                <input type="file" name="file" class="file" style="visibility: hidden; position: absolute; ">
                                <div class="input-group my-3">
                                    <input type="text" class="form-control" disabled placeholder="Upload File Max. Size 1MB" id="file">
                                    <div class="input-group-append">
                                        <button type="button" class="browse btn btn-sm btn-primary"><b class="fa fa-search"></b></button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <?php if(empty($logo)){ ?>
                                  <img src="https://placehold.it/80x80" id="preview" class="img-thumbnail">
                                <?php } else { ?>
                                  <img src="<?php echo base_url('uploads/logo/'.$logo['image_path'])?>" id="preview" class="img-thumbnail">
                                <?php } ?>
                            </div>
                    </div>
                </div>
              </div>
              <div class="card-footer ">
                <input type="submit" name="submit" value="Upload" class="btn btn-sm btn-success ml-auto">
                </form>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="card ">
                <div class="card-header card-header-success card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">text_snippet</i>
                  </div>
                  <h4 class="card-title">Detail Profil Perusahaan</h4>
                </div>
                <div class="card-body ">
                  <div id="msg-profil"></div>
                  <form method="post" id="profil-form" enctype="multipart/form-data" onSubmit="return false;">
                    <div class="form-group bmd-form-group">
                      <label for="input_nama_perusahaan" class="bmd-label-floating">Nama Perusahaan</label>
                      <input type="text" class="form-control" id="input_nama_perusahaan" name="input_nama_perusahaan" value="<?php echo $profil['nama_perusahaan']; ?>">
                    </div>
                    <div class="form-group bmd-form-group">
                      <label for="input_alamat_perusahaan" class="bmd-label-floating">Alamat Perusahaan</label>
                      <textarea class="form-control" id="input_alamat_perusahaan" name="input_alamat_perusahaan" rows="1"><?php echo $profil['alamat_perusahaan']; ?></textarea>
                    </div>
                    <div class="form-group bmd-form-group">
                      <label for="input_deskripsi_perusahaan" class="bmd-label-floating">Deskripsi</label>
                      <textarea class="form-control" id="input_deskripsi_perusahaan" name="input_deskripsi_perusahaan" rows="5"><?php echo $profil['deksripsi']; ?></textarea>
                    </div>
                </div>
                <div class="card-footer ">
                  <button type="submit" class="btn btn-sm btn-success ml-auto">Simpan</button>
                  </form>
                </div>
              </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
