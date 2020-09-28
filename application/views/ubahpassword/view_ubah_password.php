<?php $this->session->userdata['page_title'] = "Presensi Masuk"; ?>
<div class="col-md-12" style="padding:0; margin: 0;">
  <div class="card ">
    <div class="card-header card-header-success card-header-text">
      <div class="card-icon">
        <i class="material-icons">vpn_key</i>
      </div>
          <h4 class="card-title">Ubah Password</h4>
    </div>

    <div class="card-body ">
      <form method="post" class="form-horizontal" id="form-presensi-masuk" enctype="multipart/form-data" action="<?php echo base_url('ubahpassword/update_password'); ?>">
        <div class="row">
          <div class="col-md-12">
            
            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_password">Password Baru</label>
              <div class="col-sm-8">
                <div class="input-group">
                    <input type="password" class="form-control" id="input_password" name="input_password">
                    <span class="input-group-btn" onclick="show_password()">
                        <button type="button" class="btn btn-fab btn-round btn-primary" >
                            <i class="material-icons" id="label_pass">visibility_off</i>
                        </button>
                    </span>
                </div>
              </div>
            </div>
            
            <!--<div class="row">-->
            <!--  <label class="col-sm-4 col-form-label" for="input_password_2">Ulangi Password Baru</label>-->
            <!--  <div class="col-sm-8">-->
            <!--    <div class="input-group">-->
            <!--        <input type="password" class="form-control" id="input_password_2" name="input_password_2">-->
            <!--        <span class="input-group-btn" onclick="show_pass_2()">-->
            <!--            <button type="button" class="btn btn-fab btn-round btn-primary" >-->
            <!--                <i class="material-icons" id="label_pass_2">visibility_off</i>-->
            <!--            </button>-->
            <!--        </span>-->
            <!--    </div>-->
            <!--  </div>-->
            <!--</div>-->

          </div>

        </div>

    </div>
    <div class="card-footer">
      <button type="submit" class="btn btn-success btn-sm  ml-auto" id="btn-save-upload" >
        Update Password
      </button>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
    save_method = "add";
</script>
