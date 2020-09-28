<?php $this->session->userdata['page_title'] = "Data Karyawan"; ?>
<div class="row" style="">
<div class="col-lg-12 col-md-12">
  <div class="card">

    <div class="card-header card-header-info d-flex flex-row align-items-center">
          <h4 class="card-title">Data Karyawan</h4>
          <div class="btn-group btn-group-sm ml-auto" role="group" aria-label="Basic example">
            <a role="button" target="_self" class="btn btn-sm btn-secondary text-dark" href="<?php echo base_url('karyawan/add');?>"><b class="fa fa-plus"></b> Tambah</a>
            <div class="btn-group" role="group">
              <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Tools
              </button>
              <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                <a class="dropdown-item" href="<?php echo base_url('export/karyawan'); ?>" target="_self">Export</a>
                <button class="dropdown-item" onclick="open_modal_import()">Import</button>
              </div>
            </div>
          </div>

    </div>
    <div class="card-body table-responsive">

      <table class="table table-hover table-striped" id="table_karyawan" style="width: 100%">
        <thead class="text-info small">
          <tr>
            <th style="width: 5%">No.</th>
            <th style="width: 10%">Nomor Induk</th>
            <th style="width: 15%">Nama</th>
            <th style="width: 10%">Departemen</th>
            <th style="width: 20%">Jabatan</th>
            <th style="width: 5%">Status</th>
            <th style="width: 5%">No. Telp</th>
            <th style="width: 5%">#</th>
          </tr>
        </thead>

          <tbody>
          </tbody>

        </table>
      </div>
    </div>
  </div>
</div>


<div class="modal" tabindex="-1" role="dialog" id="modal-filter" >
  <div class="modal-dialog" role="document"  style="background: none;">
    <div class="modal-content"  style="background: none; box-shadow: none; border: none">
      <div class="modal-body">
        <div class="card " style="box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.5);">

          <div class="card-header card-header-info card-header-icon d-flex flex-row">
            <div class="card-icon">
              <i class="material-icons">filter_alt</i>
            </div>
            <h4 class="card-title">Filter Data</h4>
          </div>

          <div class="card-body ">
            <!-- <form method="post" id="form-filter"> -->
              <input type="hidden" name="id" id="id">
              <div class="row">
                <label class="col-sm-4 col-form-label" for="input_departemen">Departemen</label>
                <div class="col-sm-8">
                  <div class="form-group label-floating " id="input_departemen_error_container">
                    <select class="form-control" id="input_departemen_filter" name="input_departemen_filter" onchange="filter_data()">
                      <option value="x" selected>-- Silahkan Pilih --</option>
                      <?php
                      foreach ($departemen->result() as $r) {
                        echo '<option value="'.$r->id.'">'.$r->nama_departemen . ' (Kode: '.$r->kode_departemen.')' .'</option>';
                      }
                      ?>
                    </select>
                  </div>
                </div>
              </div>

          </div>
          <div class="card-footer">
            <div class="text-right">
              <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><b class="fa fa-close"></b></button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="modal-import" >
  <div class="modal-dialog" role="document"  style="background: none;">
    <div class="modal-content"  style="background: none; box-shadow: none; border: none">
      <div class="modal-body">
        <div class="card " style="box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.5);">

          <div class="card-header card-header-info card-header-icon d-flex flex-row">
            <div class="card-icon">
              <i class="material-icons">filter_alt</i>
            </div>
            <h4 class="card-title">Import Data</h4>
          </div>

          <div class="card-body ">
            <div class="row">
              <div class="col-md-12">
                <form method="POST" action="<?php echo base_url() ?>karyawan/do_import" enctype="multipart/form-data" id="form-import">
                  <input type="file" name="userfile" class="form-control">
              </div>
            </div>
            <a href="<?php echo base_url('karyawan/downladTemplate'); ?>"><b class="fa fa-download"></b> Download Template Upload Karyawan</a>
            <div id="alert-import-message">
            </div>
          </div>
          <div class="card-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><b class="fa fa-close"></b></button>
            <button type="button" class="btn btn-success btn-sm" onclick="do_import()">UPLOAD</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
