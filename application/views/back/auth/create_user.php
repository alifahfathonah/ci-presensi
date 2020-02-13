<!-- <div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>User List</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title" id="txt">
            </div>
        </div>
    </div>
</div> -->

<div class="content mt-3">
  <div class="col-lg-12">
    <?php if($message != null){ ?>
      <div class="alert alert-danger" role="alert" id="infoMessage" >
        <?php echo $message;?>
      </div>
    <?php } ?>
    <div class="card">
      <div class="card-header">
        User Form
      </div>
      <div class="card-body card-block">
          <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal"> -->
          <?php echo form_open("auth/create_user", array('class' => 'form-horizontal' ));?>

            <div class="row form-group">
              <div class="col col-md-3"><label for="first_name" class=" form-control-label">First Name</label></div>
              <div class="col-12 col-md-9">
                <?php echo form_input($first_name, '', array('class' => 'form-control' ));?>
              </div>
            </div>

            <div class="row form-group">
              <div class="col col-md-3"><label for="last_name" class=" form-control-label">Last Name</label></div>
              <div class="col-12 col-md-9">
                <?php echo form_input($last_name, '', array('class' => 'form-control' ));?>
                <!-- <input type="text" id="input_last_name" name="input_last_name" placeholder="Text" class="form-control"> -->
              </div>
            </div>

            <?php
              if($identity_column!=='email') { ?>
                   <!-- echo '<p>';
                   echo lang('create_user_identity_label', 'identity');
                   echo '<br />';
                   echo form_error('identity');
                   echo form_input($identity);
                   echo '</p>'; -->

                  <div class="row form-group">
                    <div class="col col-md-3"><label for="identity" class=" form-control-label">Username</label></div>
                    <div class="col-12 col-md-9">
                      <?php echo form_input($identity, '', array('class' => 'form-control' ));?>
                      <?php echo form_error('identity'); ?>
                      <!-- <input type="text" id="input_last_name" name="input_last_name" placeholder="Text" class="form-control"> -->
                    </div>
                  </div>
            <?php  }
            ?>

            <div class="row form-group">
              <div class="col col-md-3"><label for="company" class=" form-control-label">Company Name</label></div>
              <div class="col-12 col-md-9">
                <?php echo form_input($company, '', array('class' => 'form-control' ));?>
                <!-- <input type="text" id="input_company_name" name="input_company_name" placeholder="Text" class="form-control"> -->
              </div>
            </div>

            <div class="row form-group">
              <div class="col col-md-3"><label for="email" class=" form-control-label">Email</label></div>
              <div class="col-12 col-md-9">
                <?php echo form_input($email, '', array('class' => 'form-control' ));?>
                <!-- <input type="email" id="input_email" name="input_email" placeholder="Enter Email" class="form-control"> -->
              </div>
            </div>

            <div class="row form-group">
              <div class="col col-md-3"><label for="phone" class="form-control-label">Phone</label></div>
              <div class="col-12 col-md-9">
                <?php echo form_input($phone, '', array('class' => 'form-control' ));?>
                <!-- <input type="email" id="input_phone" name="input_phone" placeholder="Enter Email" class="form-control"> -->
              </div>
            </div>

            <div class="row form-group">
              <div class="col col-md-3"><label for="password" class="form-control-label">Password</label></div>
              <div class="col-12 col-md-9">
                <?php echo form_input($password, '', array('class' => 'form-control'));?>
                <!-- <input type="password" id="input_password" name="input_password" placeholder="Password" class="form-control"> -->
              </div>
            </div>

            <div class="row form-group">
              <div class="col col-md-3"><label for="input_password_2" class=" form-control-label">Password_2</label></div>
              <div class="col-12 col-md-9">
                <?php echo form_input($password_confirm, '', array('class' => 'form-control' ));?>
                <!-- <input type="password" id="input_password_2" name="input_password_2" placeholder="Password" class="form-control"> -->
              </div>
            </div>

            <?php if ($this->ion_auth->is_admin()): ?>

            <h3><?php echo lang('edit_user_groups_heading');?></h3>
            <?php foreach ($groups as $group):?>
                <label class="checkbox">
                <?php
                    // $gID=$grou
                ?>
                <input type="checkbox" name="groups[]" value="<?php echo $group['id'];?>">
                <?php echo htmlspecialchars($group['name'],ENT_QUOTES,'UTF-8');?>
                <?php echo "(".data_korporasi($group['id_korporasi'])['nama_klien'].")"; ?>
                </label>
                <br>
            <?php endforeach?>

        <?php endif ?>

      </div>
      <div class="card-footer pull-right">
        <?php echo form_submit('submit', 'Submit', array('class' => 'btn btn-outline-primary btn-sm' ));?>
        <!-- <button type="submit" class="btn btn-primary btn-sm">
          <i class="fa fa-dot-circle-o"></i> Submit
        </button> -->
        <button type="reset" class="btn btn-outline-danger btn-sm">
          Reset
        </button>
      </div>
    </div>
  </div>
  </form>
</div>
