<?php $this->session->userdata['page_title'] = "Pengaturan"; ?>
<div class="row" style="">
  <div class="col-lg-12 col-md-12">
    <div class="card">

      <div class="card-header card-header-info d-flex flex-row align-items-center">
        <h4 class="card-title">Pengaturan Cuti</h4>
      </div>
      <div class="card-body">
        <div class="col-md-4">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title">Jumlah cuti dalam 1 Tahun</h4>
            </div>
            <div class="card-body">

              <form id="form-cuti" method="post" enctype="multipart/form-data" onSubmit="return false;">
                <div class="form-group label-floating has-success" id="form-input-container">
                  <label class="control-label" id="success-label"></label>
                  <div class="input-group">
                    <input type="text" class="form-control" id="input_hak_cuti" name="input_hak_cuti" placeholder="Jumlah hari" onkeypress="return isNumberKey(event)" value="<?php echo $cuti['hak_cuti']; ?>">
                    <div class="input-group-append">
                      <button type="submit" class="btn btn-sm btn-outline-warning">Update</button>
                    </div>
                  </div>
                </div>
              </form>



            </div>
          </div>
        </div>
        <div class="col-md-6">

        </div>
      </div>
    </div>
  </div>
</div>
