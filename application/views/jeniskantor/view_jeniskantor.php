<?php $this->session->userdata['page_title'] = "Data Jenis Kantor"; ?>
<div class="row" style="">
<div class="col-lg-12 col-md-12">
  <div class="card">

    <div class="card-header card-header-info d-flex flex-row align-items-center">
          <h4 class="card-title">Data Jenis Kantor</h4>
          <div class="btn-group btn-group-sm ml-auto" role="group" aria-label="Basic example">
            <a role="button" target="_self" class="btn btn-sm btn-secondary text-success" href="<?php echo base_url('jeniskantor/add');?>"><b class="fa fa-plus"></b> Tambah</a>
          </div>

    </div>
    <div class="card-body table-responsive">

      <table class="table table-hover" id="table_jeniskantor">
        <thead class="text-info">
          <tr>
            <th>No.</th>
            <th>Nama Jenis Kantor</th>
            <th>#</th>
          </tr>
        </thead>

          <tbody>
            <tr>
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
