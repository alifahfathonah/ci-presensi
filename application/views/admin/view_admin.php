<?php $this->session->userdata['page_title'] = "Update Profil"; ?>
<div class="col-md-12">
  <div class="card ">
    <div class="card-header card-header-success card-header-text">
      <div class="card-icon">
        <i class="material-icons">post_add</i>
      </div>
      <h4 class="card-title">Update Profil</h4>
    </div>

    <div class="card-body ">
      <div id="alert-password-message">

      </div>
      <form class="form-horizontal" id="form-password">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="hidden" name="client_id" value="<?php echo $client_id; ?>">
        <div class="row">
          <label class="col-md-3 col-form-label" for="input_username">Username</label>
          <div class="col-md-9">
            <div class="form-group has-default bmd-form-group">
              <input type="hidden" class="form-control" value="<?php echo $username; ?>" id="input_username_old" name="input_username_old">
              <input type="text" class="form-control" value="<?php echo $username; ?>" id="input_username" name="input_username">
            </div>
          </div>
        </div>
        <div class="row">
          <label class="col-md-3 col-form-label" for="input_password">Password</label>
          <div class="col-md-9">
            <div class="form-group bmd-form-group">
              <input type="password" class="form-control" value="" placeholder="Isi jika ingin mengganti password" id="input_password" name="input_password">
            </div>
          </div>
        </div>
        <div class="row">
          <label class="col-sm-3 col-form-label" for="input_hak_akses">Hak Akses</label>
          <div class="col-sm-9" >
            <div class="form-group label-floating " >
              <select class="form-control" id="input_hak_akses" name="input_hak_akses" disabled>

                <?php
                $data = array("1" => "Administrator", "2" => "Kepala Departemen", "3" => "Staff" );
                foreach ($data as $key => $value) {
                  $selected = ($username['id_hak_akses'] == $key) ? "Selected" : "" ;
                  echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
                }
                ?>

              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <!-- <button type="button" name="btn-reset-password" class="btn btn-sm btn-link" onclick="reset_password()">Reset Password</button> -->
          </div>
          <div class="col-md-9 text-right">
            <button type="button" name="btn-update-password" class="btn btn-sm btn-warning" onclick="update_password()">Update</button>
          </div>
        </div>

      </form>

    </div>
    <div class="card-footer">
      <!-- <button type="submit" class="btn btn-success btn-sm  ml-auto" id="btn-save">SIMPAN</button>
      </form> -->
    </div>
  </div>
</div>
<script type="text/javascript">
    save_method = "add";
</script>
