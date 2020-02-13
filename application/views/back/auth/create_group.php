<div class="animated fadeIn">
  <div class="row">
    <div class="col-lg-12">
      <?php if($message != null){ ?>
        <div class="alert alert-danger" role="alert" id="infoMessage" >
          <?php echo $message;?>
        </div>
      <?php } ?>
      <div class="card">
        <div class="card-header">
          <strong>Group Form</strong>
        </div>
        <div class="card-body card-block">
            <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal"> -->
            <?php echo form_open("auth/create_group", array('class' => 'form-horizontal' ));?>

              <div class="row form-group">
                <div class="col col-md-3"><label for="group_name" class=" form-control-label">Group Name</label></div>
                <div class="col-12 col-md-9">
                  <?php echo form_input($group_name, '', array('class' => 'form-control' ));?>
                </div>
              </div>

              <div class="row form-group">
                <div class="col col-md-3"><label for="description" class=" form-control-label">Descriptions</label></div>
                <div class="col-12 col-md-9">
                  <?php echo form_input($description, '', array('class' => 'form-control' ));?>
                </div>
              </div>

        </div>
        <div class="card-footer">
          <?php echo form_submit('submit', 'Submit', array('class' => 'btn btn-primary btn-sm' ));?>
          <!-- <button type="submit" class="btn btn-primary btn-sm">
            <i class="fa fa-dot-circle-o"></i> Submit
          </button> -->
          <button type="reset" class="btn btn-danger btn-sm">
            Reset
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
</form>
