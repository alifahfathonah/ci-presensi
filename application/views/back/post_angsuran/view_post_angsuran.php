<div class="content flashdata_container">
  <?php echo $this->session->flashdata('message'); ?>
</div>

<div class="content mt-3">
  <div class="card" style="border-top: solid 3px #7ea4b3">
    <div class="card-body card-block">
      <h5 class="card-title display-4" style="font-size: 20px">Posting Angsuran</h5>
      <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
        <div class="input-group">
          <button onclick="getDataTemp()" type="button" class="btn btn-sm btn-info" ><b class="fa fa-download"></b> Get Data</button>
          <button onclick="toExcel()" type="button" class="btn btn-sm btn-success" ><b class="fa fa-file-o"></b> Export </button>
          <button onclick="clear_table()" type="button" class="btn btn-sm btn-light" ><b class="fa fa-times"></b> Batal</button>
        </div>
      </div>

      <div class="btn-group btn-group-sm float-right" id="button_container">
        <div class="input-group">
          <input id="input_report_id" type="text" class="form-control" placeholder="Report ID" aria-label="Report ID didapat setelah generate file excel" aria-describedby="button-addon4">
          <div class="input-group-append" id="button-addon4">
            <button onclick="cari()" class="btn btn-sm btn-outline-secondary" type="button" id="button-addon1"><b class="fa fa-search"></b></button>
            <button onclick="doPosting()" type="button" class="btn btn-sm btn-danger" ><b class="fa fa-upload"></b> Posting</button>
          </div>
        </div>
        <!-- <div class="input-group-prepend">
          <button class="btn btn-outline-secondary" type="button" id="button-addon1" disabled><b class="fa fa-search"></b></button>
        </div>
        <input type="text" class="form-control" placeholder="" aria-label="Example text with button addon" aria-describedby="button-addon1">
        <button onclick="add()" type="button" class="btn btn-sm btn-danger" ><b class="fa fa-save"></b> Posting</button> -->
      </div>
      <hr>

      <div class="alert alert-warning" id="alert_container">
        <strong>Proses posting sedang running. Dilarang menutup halaman ini/browser!</strong>
      </div>

      <div class="col-md-6 borderr" style="padding:0px;">
        <div class="card border border-success" style="margin:0px;">
          <div class="card-header">
            Posting Simpanan <footer class="blockquote-footer">Report ID <cite title="Source Title" class="report_id_label">Source Title</cite></footer>
          </div>
          <div class="card-body" >
            <div class="table-responsive overflow-auto" id="table_post_simpanan_container" style="width:100%; max-height: 350px">
              <table id="table_post_simpanan" class="table table-hover table-striped table-sm small" style="width:100%">
                <thead class=''>
                  <tr>
                    <th >No </th>
                    <th >Aksi </th>
                    <th >Nama </th>
                    <th >Simpanan Wajib </th>
                    <th >Simpanan Sukarela </th>
                    <th >Jumlah Simpanan </th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 borderr" style="padding:0px;">
        <div class="card border border-primary" style="margin:0px;">
          <div class="card-header">
            Posting Angsuran <footer class="blockquote-footer">Report ID <cite title="Source Title" class="report_id_label">Source Title</cite></footer>
          </div>
          <div class="card-body">
            <div class="table-responsive overflow-auto" id="table_post_angsuran_container" style="width:100%; max-height: 350px">
              <table id="table_post_angsuran" class="table table-hover table-striped table-sm small " style="width:100%;">
                <thead class=''>
                  <tr>
                    <th >No </th>
                    <th >Aksi </th>
                    <th >Nama </th>
                    <th >Sisa Pinjaman </th>
                    <th >Ke </th>
                    <th >Pokok </th>
                    <th >Bunga </th>
                    <th >Jumlah Angsuran </th>
                    <th >Lainnya </th>
                    <th >Keterangan </th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div id="export_container">

  </div>
