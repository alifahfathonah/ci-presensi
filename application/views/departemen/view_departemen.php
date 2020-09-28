<?php $this->session->userdata['page_title'] = "Departemen"; ?>
<div class="row" style="">
<div class="col-lg-12 col-md-12">
  <div class="card">

    <div class="card-header card-header-info d-flex flex-row align-items-center">
          <h4 class="card-title">Data Departemen</h4>
          <div class="btn-group btn-group-sm ml-auto" role="group" aria-label="Basic example">
            <a role="button" target="_self" class="btn btn-sm btn-secondary text-success" href="<?php echo base_url('departemen/add');?>"><b class="fa fa-plus"></b> Tambah</a>
          </div>

    </div>
    <div class="card-body table-responsive">

      <table class="table table-hover" id="table_departemen">
        <thead class="text-info">
          <tr>
            <th>No.</th>
            <th>Kode Departemen</th>
            <th>Nama Departemen</th>
            <th>Keterangan</th>
            <th>#</th>
          </tr>
        </thead>

          <tbody>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
          </tbody>

        </table>
      </div>
    </div>
  </div>
</div>
