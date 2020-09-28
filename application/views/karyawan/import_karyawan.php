<?php $this->session->userdata['page_title'] = "Data Karyawan"; ?>
<div class="row">
  <div class="col-md-8 offset-2">
    <?php echo $this->session->flashdata('notif') ?>
    <form method="POST" action="<?php echo base_url() ?>karyawan/do_import" enctype="multipart/form-data">
      <input type="file" name="userfile" class="form-control">
      <button type="submit" class="btn btn-success">UPLOAD</button>
    </form>
  </div>
</div>
